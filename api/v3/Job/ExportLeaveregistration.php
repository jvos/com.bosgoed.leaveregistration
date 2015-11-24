<?php
//require_once(drupal_get_path('module', 'civicrm').'/../CRM/Contact/BAO/Contact/Utils.php');
require_once(drupal_get_path('module', 'civicrm').'/../CRM/Utils/Mail.php');

//require_once(drupal_get_path('module', 'civicrm').'/../CRM/Core/Report/Excel.php');

/**
 * Job.ExportLeaveregistration API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_job_exportleaveregistration_spec(&$spec) {
  //$spec['magicword']['api.required'] = 1;
}

/**
 * Job.ExportLeaveregistration API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_job_exportleaveregistration($params) {  
  // set max exution to unlimited
  // the leaveregistration class is quite big and we do it for every employee
  ini_set('max_execution_time', 0); 
  
  // trimm all the paramaters like from GET or POST
  foreach($params as $field => $value){
    $params[$field] = trim($value);
  }
  
  echo('<pre>');
  print_r($params);
  echo('</pre>');
  
  // week
  if('Week' == $params['do']){
    if(isset($params['fromWeek']) and is_numeric($params['fromWeek']) and isset($params['toWeek']) and is_numeric($params['toWeek'])){
      for($week=$params['fromWeek']; $week <= $params['toWeek']; $week++){
        $params['week'] = $week;
        civicrm_api3_job_exportleaveregistration_week($params);
      }

    }else {
      civicrm_api3_job_exportleaveregistration_week($params);
    }
  }
  
  // year month
  if('Months' == $params['do']){
    if(isset($params['Year']) and !empty($params['Year']) and isset($params['Months']) and !empty($params['Months']) and isset($params['Months']) and !empty($params['Months'])){
      civicrm_api3_job_exportleaveregistration_months($params);
      
    }else {
      $params['Year'] = date('Y');
      $params['Months'] = date('m') . ',' . date('m', strtotime('+1 month'));
      civicrm_api3_job_exportleaveregistration_months($params);
    }
  }
}

function civicrm_api3_job_exportleaveregistration_week($params){
  $week = date('W'); // set the default week to now
  $year = date('Y'); // set default year to now
  
  // if week exists and is not empty use it
  if(isset($params['week']) and !empty($params['week'])){
    $week = $params['week'];
    
  }else { // if the week is not defined, get the previous week and year
    if('1' == $week){
      $week = '52';
      $year = $year-1;
    }else {
      $week = $week - 1;
    }
  }
  
  // if year exists and is not empty use it
  if(isset($params['year']) and !empty($params['year'])){
    $year = $params['year']; 
  }
  
  // if date is defined use it and calculate the week number and year
  if(isset($params['date']) and !empty($params['date'])){
    $week = date('W', strtotime($params['date'])); 
    $year = date('Y', strtotime($params['date']));
  }
  
  echo '<br/>' . PHP_EOL;
  echo(ts('Week: ') . $week) . '<br/>' . PHP_EOL;
  
  // range of days, use to calculate the days in the week, 1 is monday and 7 is sunday
  $dayrange  = array(1,2,3,4,5,6,7);
  
  // set days, months and years to empty
  $days = array();
  $months = array();
  $years = array();
    
  // calculate the days in the week
  for($count=0; $count<=6; $count++) {
    $week = ($count == 7)?($week + 1): ($week);
    $week = str_pad($week,2,'0',STR_PAD_LEFT);
        
    $days[] = date('d-m-Y', strtotime($year."W".$week.($dayrange[$count]))); 
  }
  
  // calculate months and years from date
  // calculate years from days
  foreach($days as $date){
    $year = date('Y', strtotime($date));
    $years[$year] = $year;
    $month = date('m', strtotime($date));
    $months[$month] = $month;
  }
  
  echo('<pre>');
  print_r($days);
  print_r($months);
  print_r($years);
  echo('</pre>');
  
  // set rows to empty
  $rows = array();
  
  // set content to empty (content is the actualy content of the csv file)
  $content = '';
  
  // get the first day of the days
  $from = $days[0];
  // get the last days of the days
  $to = $days[count($days)-1];
  
  // get all the employees, from civicrm 
  $parameters = array(
    'version' => 3,
    'sequential' => 1,
    'contact_type' => 'Individual',
    'contact_sub_type' => 'Employee',
    'options' => array(
      'limit' => 0,
      'sort' => 'last_name ASC',
    ),
    'is_deleted' => 0,
  );
  $result = civicrm_api('Contact', 'get', $parameters);
  
  // if there is a error, set everything to empty expect the week, from day and to day, and set the error in the leave column
  if($result['is_error']){
    $row = array('last_name' => '', 'first_name' => '', 'display_name' => '', 'week' => $week, 'from' => $from, 'to' => $to, 'leave' => ts('Failt get all employees !'), 'sick' => '');
  }
  
  /*echo('<pre>');
  print_r($result['values']);
  echo('</pre>');*/
  
  // loop through the employees
  foreach($result['values'] as $employee){
    $data = array();      
    
    // define the variable that hold the class
    $lr = array();
    
    // set the class
    $lr = new leaveregistration('civicrm'); 
    // if there is a error, set sick to empty and set the error in the leave column
    if($lr->isset_error()){
      $rows[] = array('last_name' => $employee['last_name'], 'first_name' => $employee['first_name'], 'display_name' => $employee['display_name'], 'week' => $week, 'from' => $from, 'to' => $to, 'leave' => ts('Failt construct leave registration class !'), 'sick' => '');
      continue;
    }
    
    // set the contact in the class
    $lr->set_contacts($employee['contact_id'], $employee['contact_id']);
    // if there is a error, set sick to empty and set the error in the leave column
    if($lr->isset_error()){
      $rows[] = array('last_name' => $employee['last_name'], 'first_name' => $employee['first_name'], 'display_name' => $employee['display_name'], 'week' => $week, 'from' => $from, 'to' => $to, 'leave' => ts('Failt set contacts leave registration class !'), 'sick' => '');
      continue;
    }
    
    // set the data in the class
    $lr->set_data($years, array());
    // if there is a error, set sick to empty and set the error in the leave column
    if($lr->isset_error()){
      $rows[] = array('last_name' => $employee['last_name'], 'first_name' => $employee['first_name'], 'display_name' => $employee['display_name'], 'week' => $week, 'from' => $from, 'to' => $to, 'leave' => ts('Failt set contacts leave registration class !'), 'sick' => '');
      continue;
    }
    
    // set all the counters of work, time for time, leave and sick to zero
    $work = 0;
    $time_for_time = 0;
    $leave = 0;
    $sick = 0;
    
    // loop through the days in that week
    foreach($days as $date){
      // calculate the day, month and year
      $day = date('d', strtotime($date));
      $month = date('m', strtotime($date));
      $year = date('Y', strtotime($date));

      // get the data from the contact, year, month and day
      $data = $lr->data[$employee['contact_id']][$year][$month][$day];
                  
      // switch between the leave types
      // and add the duration to the right counter (work, time for time, leave or sick)
      if(isset($data['request']['is_request']) and 1 == $data['request']['is_request'] and 'approved' == $data['request']['status']){
        switch($data['request']['leave_type']){
          case 'mom_dad_day':
          case 'mom_dad_day_contiguous':
          case 'doctor_visit':
          case 'study_leave':
          case 'care':   
          case 'special_leave':
          case 'maternity':
            $leave += $data['request']['duration'];
            break;
          case 'sick_less_one_day': 
          case 'sick':
            $sick += $data['request']['duration'];
            break;
        }
      }

      // time_for_time
      if(isset($data['time_for_time']['is_time_for_time']) and 1 == $data['time_for_time']['is_time_for_time'] and 'approved' == $data['time_for_time']['status']){
        $time_for_time += $data['time_for_time']['duration'];
      }

      // normal_leave
      // leave type is normal_leave_less_one_day and normal_leave
      if(isset($data['normal_leave']['is_normal_leave']) and 1 == $data['normal_leave']['is_normal_leave'] and 'approved' == $data['normal_leave']['status']){
        $leave += $data['normal_leave']['duration'];
      }
    }
    
    // calculate the leave hours and the minutes from the duration (in minutes)
    $hours = floor($leave / 60);
    $minutes = $leave - ($hours * 60);
    $leave = $hours . ':' . sprintf("%02s", $minutes);
    
    // calculate the sick hours and the minutes from the duration (in minutes)
    $hours = floor($sick / 60);
    $minutes = $sick - ($hours * 60);
    $sick = $hours . ':' . sprintf("%02s", $minutes);
    
    // create the row from the contact wiht the information
    $rows[] = array($employee['last_name'], $employee['first_name'], $employee['display_name'], $week, $from, $to, $leave, $sick);
    
    unset($data);
    unset($lr);
  }
    
  // set the column names
  $column_names = array(ts('Last name'), ts('First name'), ts('Full name'), ts('Week'), ts('From'), ts('To'), ts('Leave'), ts('Sick'));
  // create the actualy content of the csv file
  // first the column names
  $content .= (implode(';', $column_names)) . "\r\n";
    
  // loop through the rows of data
  // add them to the content for the csv file
  foreach($rows as $key => $data){
    $content .= (implode(';', $data)) . "\r\n";
  }
    
  echo($content) . '<br/>' . PHP_EOL;
  
  unset($rows);
  unset($result);
  
  // donnot email if it is no
  if(isset($params['doEmail']) and 'Yes' == $params['doEmail']){
    civicrm_api3_job_exportleaveregistration_email($params, $content, $week);
  }
}

function civicrm_api3_job_exportleaveregistration_email($params, $content, $week){
  // create a temp file, with the csv content
  $filename = tempnam($conf['file_temporary_path'], 'verlof.bosgoed.com-week-' . $week . '.csv');
  $fp = fopen($filename, 'w');
  fwrite($fp, $content);
  fclose($fp);

  if(isset($params['email']) and !empty($params['email'])){
    $email = $params['email'];
  }else {
    $email = 'marcel.groenendijk@bosgoedcompany.nl';
  }
  
  global $conf;

  // set the paramters of the email
  $parameters = array(
    'from' => '"' . $conf['site_name'] . '" <' . $conf['site_mail'] . '>', 
    'toName' => 'Marcel Groenendijk',  
    'toEmail' => $email, 
    'subject' => 'Overzicht verlof bosgoed week ' . $week,
    'text' => 'Overzicht verlof bosgoed week ' . $week,
    'attachments' => array('ExportLeaveregistration' => array( // add the temp csv to the email
      'fullPath' => $filename,
      'mime_type' => 'text/csv',
      'cleanName' => 'Verlof Bosgoed Com - Week ' . $week . '.csv',
    )),
  );

  // send the email
  if(!CRM_Utils_Mail::send($parameters)) {
    echo(ts('Failt to send mail !'));
  }

  // remove the temp csv file
  unlink($filename);
}

function civicrm_api3_job_exportleaveregistration_months($params){
  $years = array($params['Year']);
  $months = explode(',', $params['Months']);
  
  echo('Year: ') . '<br/>' . PHP_EOL;
  echo('<pre>');
  print_r($years);
  echo('</pre>');
  
  echo('Months: ') . '<br/>' . PHP_EOL;
  echo('<pre>');
  print_r($months);
  echo('</pre>');
  
  $column_names = array(ts('Last name'), ts('First name'), ts('Full name'), ts('From'), ts('To'), ts('Message'));
  
  $column_days = array();
  
  $from = $params['Year'] . '-' . $months[0] . '-01';
  $to = $params['Year'] . '-' . end($months);
  $last_day = '';
  
  foreach($years as $year){
    foreach($months as $month){
      for($day_month = 1; $day_month <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $day_month++){
        
        
        $timestamp = strtotime($year . '-' . $month . '-' . $day_month);

        $day = date('d', $timestamp);
        
        $column_days[] = ts($day . '-' . $month);
        $last_day = $day;
      }
    }
  } 
  
  $to .= '-' . $last_day;
  
  /*echo('Columns Names / Days: ') . '<br/>' . PHP_EOL;
  echo('<pre>');
  print_r($column_names);
  print_r($column_days);
  echo('</pre>');*/
  
  // get all the employees, from civicrm 
  $parameters = array(
    'version' => 3,
    'sequential' => 1,
    'contact_type' => 'Individual',
    'contact_sub_type' => 'Employee',
    'options' => array(
      'limit' => 0,
      'sort' => 'last_name ASC',
    ),
    'is_deleted' => 0,
  );
  $result = civicrm_api('Contact', 'get', $parameters);
  
  // if there is a error, set everything to empty expect the week, from day and to day, and set the error in the leave column
  if($result['is_error']){
    $row = array_merge(array('last_name' => '', 'first_name' => '', 'display_name' => '', 'from' => $from, 'to' => $to, 'message' => ts('Failt get all employees !')), $column_days);
  }
  
  /*echo('<pre>');
  print_r($result['values']);
  echo('</pre>');*/
  
  // loop through the employees
  foreach($result['values'] as $employee){
    $data = array();      
        
    // define the variable that hold the class
    $lr = array();
    
    // set the class
    $lr = new leaveregistration('civicrm'); 
    // if there is a error, set sick to empty and set the error in the leave column
    if($lr->isset_error()){
      $rows[] = array_merge(array('last_name' => $employee['last_name'], 'first_name' => $employee['first_name'], 'display_name' => $employee['display_name'], 'from' => $from, 'to' => $to, 'message' => ts('Failt construct leave registration class !')), $column_days);
      continue;
    }
    
    // set the contact in the class
    $lr->set_contacts($employee['contact_id'], $employee['contact_id']);
    // if there is a error, set sick to empty and set the error in the leave column
    if($lr->isset_error()){
      $rows[] = array_merge(array('last_name' => $employee['last_name'], 'first_name' => $employee['first_name'], 'display_name' => $employee['display_name'], 'from' => $from, 'to' => $to, 'message' => ts('Failt set contacts leave registration class !')), $column_days);
      continue;
    }
    
    // set the data in the class
    $lr->set_data($years, array());
    // if there is a error, set sick to empty and set the error in the leave column
    if($lr->isset_error()){
      $rows[] = array_merge(array('last_name' => $employee['last_name'], 'first_name' => $employee['first_name'], 'display_name' => $employee['display_name'], 'from' => $from, 'to' => $to, 'message' => ts('Failt set contacts leave registration class !')), $column_days);
      continue;
    }
    
    $days = array();
    
    foreach($years as $year){
      foreach($months as $month){
        for($day_month = 1; $day_month <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $day_month++){
          $timestamp = strtotime($year . '-' . $month . '-' . $day_month);

          $day = date('d', $timestamp);

          // get the data from the contact, year, month and day
          $data = $lr->data[$employee['contact_id']][$year][$month][$day];
          
          // empty content
          $content = array();
          $leave = 0;
          $sick = 0;
          $time_for_time = 0;
          
          if(isset($data['adjustments']['duration']) and 0 == $data['adjustments']['duration']){
            $content[] = 'Vrij';
          }
          
          // switch between the leave types
          // and add the duration to the right counter (work, time for time, leave or sick)
          if(isset($data['request']['is_request']) and 1 == $data['request']['is_request'] and 'approved' == $data['request']['status']){
            switch($data['request']['leave_type']){
              case 'mom_dad_day':
              case 'mom_dad_day_contiguous':
              case 'doctor_visit':
              case 'study_leave':
              case 'care':   
              case 'special_leave':
              case 'maternity':
                $leave += $data['request']['duration'];
                break;
              case 'sick_less_one_day': 
              case 'sick':
                $sick += $data['request']['duration'];
                break;
            }
          }

          // time_for_time
          if(isset($data['time_for_time']['is_time_for_time']) and 1 == $data['time_for_time']['is_time_for_time'] and 'approved' == $data['time_for_time']['status']){
            $time_for_time = $data['time_for_time']['duration'];
          }

          // normal_leave
          // leave type is normal_leave_less_one_day and normal_leave
          if(isset($data['normal_leave']['is_normal_leave']) and 1 == $data['normal_leave']['is_normal_leave'] and 'approved' == $data['normal_leave']['status']){
            $leave += $data['normal_leave']['duration'];
          }

          // calculate the leave hours and the minutes from the duration (in minutes)
          if($leave > 0){
            $hours = floor($leave / 60);
            $minutes = $leave - ($hours * 60);
            $content[] = 'V: ' . $hours . ':' . sprintf("%02s", $minutes);
          }
            
          // calculate the sick hours and the minutes from the duration (in minutes)
          if($leave > 0){
            $hours = floor($sick / 60);
            $minutes = $sick - ($hours * 60);
            $content[] = 'Z: ' . $hours . ':' . sprintf("%02s", $minutes);
          }
          
          // create the row from the contact wiht the information
          $days[] = implode(' ', $content);
        }
      }
    }
      
    $rows[] = array_merge(array('last_name' => $employee['last_name'], 'first_name' => $employee['first_name'], 'display_name' => $employee['display_name'], 'from' => $from, 'to' => $to, 'message' => ts('')), $days);      
  }
  
  // create the actualy content of the csv file
  $content = '';
  
  // first the column names
  $content .= (implode(';', $column_names)) . ';' . (implode(';', $column_days)) . "\r\n";
    
  // loop through the rows of data
  // add them to the content for the csv file
  foreach($rows as $key => $data){
    $content .= (implode(';', $data)) . "\r\n";
  }
    
  echo($content) . '<br/>' . PHP_EOL;
  
  unset($rows);
  unset($result);
  
  // donnot email if it is no
  if(!isset($params['doEmail']) or 'No' != $params['doEmail']){
    civicrm_api3_job_exportleaveregistration_months_email($params, $content);
  }
}

function civicrm_api3_job_exportleaveregistration_months_email($params, $content){
  // create a temp file, with the csv content
  $filename = tempnam($conf['file_temporary_path'], 'verlof.bosgoed.com-maand.csv');
  $fp = fopen($filename, 'w');
  fwrite($fp, $content);
  fclose($fp);

  if(isset($params['email']) and !empty($params['email'])){
    $email = $params['email'];
  }else {
    $email = 'i.dordievski@bosworx.nl';
  }
  
  global $conf;

  // set the paramters of the email
  $parameters = array(
    'from' => '"' . $conf['site_name'] . '" <' . $conf['site_mail'] . '>', 
    'toName' => 'Isolde Dordievski',  
    'toEmail' => $email, 
    'subject' => 'Overzicht verlof bosgoed Maand(en)',
    'text' => 'Overzicht verlof bosgoed maand(en)',
    'attachments' => array('ExportLeaveregistration' => array( // add the temp csv to the email
      'fullPath' => $filename,
      'mime_type' => 'text/csv',
      'cleanName' => 'Verlof Bosgoed Com - Maand(en).csv',
    )),
  );

  // send the email
  if(!CRM_Utils_Mail::send($parameters)) {
    echo(ts('Failt to send mail !'));
  }

  // remove the temp csv file
  unlink($filename);
}