<?php

class CRM_Leaveregistration_Form_Report_LeaveRegistration extends CRM_Report_Form {

  protected $_formValues = array();
  
  function __construct() {
    $leaveregistrationConfig = CRM_Leaveregistration_Config::singleton();
    
    $this->_columns = array(
      'civicrm_contact' => array(
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' => array(
          'id' => array(
            'title' => ts('Contact Id'),
            'required' => TRUE,
            'no_display' => TRUE,
            'default' => TRUE,
          ),
          'first_name' => array(
            'title' => ts('First Name'),
            'no_repeat' => TRUE,
            'default' => TRUE,
          ),
          'last_name' => array(
            'title' => ts('Last Name'),
            'no_repeat' => TRUE,
            'default' => TRUE,
          ),
          'display_name' => array(
            'title' => ts('Display Name'),
            'no_repeat' => TRUE,
            'default' => TRUE,
          ),
        ),
        'filters' => array(
          'id' => array(
            'title' => ts('Contact Id'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $leaveregistrationConfig->employees,
          ),
        ),
        'order_bys' => array(
          'id' => array(
            'title' => ts('Contact Id'),
          ),
          'first_name' => array(
            'title' => ts('First Name'),
          ),
          'last_name' => array(
            'title' => ts('Last Name'),
          ),
          'display_name' => array(
            'title' => ts('Display Name'),
            'default' => TRUE,
            'default_order' => 'ASC',
          ),
        ),
      ),
      'department' => array(
        'filters' => array(
          'department' => array(
            'title' => ts('Department'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $leaveregistrationConfig->departments,
          )
        )
      ),
      'business' => array(
        'filters' => array(
          'business' => array(
            'title' => ts('Business'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $leaveregistrationConfig->business,
          )
        )
      ),
      'leaveregistration' => array(
        'filters' => array(
          'period' => array(
            'title' => ts('Period'),
            'operatorType' => CRM_Report_Form::OP_SELECT,
            //'options' => ['year' => ts('Year'), 'month' => ts('Month'), 'week' => ts('Week')]
            'options' => ['month' => ts('Month'), 'week' => ts('Week')]
          ),
          'year' => array(
            'title' => ts('Year'),
            'operatorType' => CRM_Report_Form::OP_SELECT,
            'options' => $leaveregistrationConfig->years,
            'default' => date('Y'),
          ),
          'month' => array(
            'title' => ts('Month'),
            'operatorType' => CRM_Report_Form::OP_SELECT,
            'options' => $leaveregistrationConfig->months,
            'default' => date('m'),
          ),
          'week' => array(
            'title' => ts('Week'),
            'operatorType' => CRM_Report_Form::OP_SELECT,
            'options' => $leaveregistrationConfig->weeks,
            'default' => date('W'),
          ),
        ),
      ),
    );
    
    parent::__construct();
  }

  function preProcess() {
    $this->assign('reportTitle', ts('Leave Registration Report'));
    parent::preProcess();
  }
  
  function select() {
    $select = $this->_columnHeaders = array();
    
    foreach ($this->_columns as $tableName => $table) {
      if('civicrm_contact' == $tableName){
        if (array_key_exists('fields', $table)) {
          foreach ($table['fields'] as $fieldName => $field) {
            if (CRM_Utils_Array::value('required', $field) ||
              CRM_Utils_Array::value($fieldName, $this->_params['fields'])) {
              $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
              $this->_columnHeaders["civicrm_contact_{$fieldName}"]['title'] = $field['title'];
              $this->_columnHeaders["civicrm_contact_{$fieldName}"]['type'] = CRM_Utils_Array::value('type', $field);
            }
          }
        }
      }
    }

    $this->_select = "SELECT " . implode(', ', $select) . " ";
  }

  function from() {
    $this->_from = NULL;

    $this->_from = "
         FROM  civicrm_contact {$this->_aliases['civicrm_contact']} {$this->_aclFrom} ";
  }

  function where() {
    $clauses = array();
    
    $leaveregistrationConfig = CRM_Leaveregistration_Config::singleton();
        
    // deparment emplyees ids
    if(isset($this->_params['department_value'][0]) and !empty($this->_params['department_value'][0])){
      $this->_params['id_op'] = 'in';
      
      $ids = $leaveregistrationConfig->getDepartmentEmployeesIds($this->_params['department_value']);
      $this->_params['id_value'] = array_merge($this->_params['id_value'], $ids);
      $this->_formValues['id_value'] = array_merge($this->_params['id_value'], $ids);
    }
    
    // busniess emplyees ids
    if(isset($this->_params['business_value'][0]) and !empty($this->_params['business_value'][0])){
      $this->_params['id_op'] = 'in';
      
      $ids = $leaveregistrationConfig->getBusinessEmployeesIds($this->_params['business_value']);
      $this->_params['id_value'] = array_merge($this->_params['id_value'], $ids);
      $this->_formValues['id_value'] = array_merge($this->_params['id_value'], $ids);
    }
    
    foreach ($this->_columns as $tableName => $table) {
      if('civicrm_contact' == $tableName){
        if (array_key_exists('filters', $table)) {
          foreach ($table['filters'] as $fieldName => $field) {
            $clause = NULL;
            if (CRM_Utils_Array::value('operatorType', $field) & CRM_Utils_Type::T_DATE) {
              $relative = CRM_Utils_Array::value("{$fieldName}_relative", $this->_params);
              $from     = CRM_Utils_Array::value("{$fieldName}_from", $this->_params);
              $to       = CRM_Utils_Array::value("{$fieldName}_to", $this->_params);

              $clause = $this->dateClause($field['name'], $relative, $from, $to, $field['type']);
            }
            else {
              $op = CRM_Utils_Array::value("{$fieldName}_op", $this->_params);
              if ($op) {
                $clause = $this->whereClause($field,
                  $op,
                  CRM_Utils_Array::value("{$fieldName}_value", $this->_params),
                  CRM_Utils_Array::value("{$fieldName}_min", $this->_params),
                  CRM_Utils_Array::value("{$fieldName}_max", $this->_params)
                );
              }
            }

            if (!empty($clause)) {
              $clauses[] = $clause;
            }
          }
        }
      }
    }
    
    if (empty($clauses)) {
      $this->_where = "WHERE ( 1 ) ";
    }
    else {
      $this->_where = "WHERE " . implode(' AND ', $clauses);
    }

    if ($this->_aclWhere) {
      $this->_where .= " AND {$this->_aclWhere} ";
    }
  }

  function groupBy() {
    //$this->_groupBy = " GROUP BY {$this->_aliases['civicrm_contact']}.id, {$this->_aliases['civicrm_membership']}.membership_type_id";
  }

  function orderBy() {
    //$this->_orderBy = " ORDER BY {$this->_aliases['civicrm_contact']}.sort_name, {$this->_aliases['civicrm_contact']}.id, {$this->_aliases['civicrm_membership']}.membership_type_id";
    $orderby = [];
    foreach($this->_formValues['order_bys'] as $key => $orderby){
       $orderby[] = " ORDER BY {$this->_aliases['civicrm_contact']}_" . $orderby['column'] . " " . $orderby['ASC'];
    }
    
    $this->_orderBy = implode(', ', $orderby);
  }

  function postProcess() {

    $this->beginPostProcess();

    // get the acl clauses built before we assemble the query
    $this->buildACLClause($this->_aliases['civicrm_contact']);
    $sql = $this->buildQuery(TRUE);

    $rows = array();
    $this->buildRows($sql, $rows);
    $this->alterDisplay($rows);

    $this->formatDisplay($rows);
    $this->doTemplateAssignment($rows);
    $this->endPostProcess($rows);
  }
      
  function buildRows($sql, &$rows) {
    echo('$sql: ' . $sql) . '<br/>' . PHP_EOL;
    
    echo('<pre>');
    print_r($rows);
    print_r($this->_formValues);
    print_r($this->_columns);
    print_r($this->_formValues['id_value']);
    echo('</pre>');
    //CRM_Utils_System::civiExit();
    
     // set days, months and years to empty
    $days = array();
    $months = array();
    $years = array();
    $week = $this->_formValues['week_value'];
    
    switch($this->_formValues['period_value']){
      case 'year':
        $years = [$this->_formValues['year_value']];
        $months = [];
        
        $first_day = date('Y-m-d', strtotime($this->_formValues['year_value'] . '-01-01'));
        $last_day = date('Y-m-d', strtotime($this->_formValues['year_value'] . '-12-31'));
        
        break;
      case 'month':
        $years = [$this->_formValues['year_value']];
        $months = [$this->_formValues['month_value']];
        
        $first_day = date('Y-m-d', strtotime($this->_formValues['year_value'] . '-' . $this->_formValues['month_value'] . '-01'));
        $last_day = date('Y-m-t', strtotime($this->_formValues['year_value'] . '-' . $this->_formValues['month_value'] . '-02'));
        break;
      case 'week':
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
        
        $first_day = date('Y-m-d', strtotime($days[0]));
        $last_day = date('Y-m-t', strtotime(end($days)));
        break;
    }
    
    $start_date_totime = strtotime($first_day);
    $end_date_totime = strtotime($last_day);
    
    // set column header
    for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+1 day' , $timestamp )){
      $day = date('d', $timestamp);  
      $month = date('m', $timestamp);  
      $year = date('Y', $timestamp);
      
      switch($this->_formValues['period_value']){
        case 'year':
          $this->_columnHeaders[date('m-d', $timestamp)] = array('title' => date('m-d', $timestamp));
          break;
        case 'month':
          $this->_columnHeaders[date('d', $timestamp)] = array('title' => date('d', $timestamp));
          break;
        case 'week':
          $this->_columnHeaders[date('Y-m-d', $timestamp)] = array('title' => date('d', $timestamp));
          break;
      }
    }   
        
    $lr = new leaveregistration('civicrm', 'CRM_Leaveregistration_Form_Report_LeaveRegistration');
    $lr->set_fields();
    $lr->set_contacts($this->_formValues['id_value']);
    $lr->set_data($years, $months);
    
    $rows = [];
    
    $dao = CRM_Core_DAO::executeQuery($sql);
    while ($dao->fetch()) {
      $row = [];
      
      foreach($this->_columnHeaders as $id => $columnheader){
        $row[$id] = $dao->$id;
      }
      
      for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+1 day' , $timestamp )){
        $day = date('d', $timestamp);  
        $month = date('m', $timestamp);  
        $year = date('Y', $timestamp);
        
        // get the data from the contact, year, month and day
        $data = $lr->data[$dao->civicrm_contact_id][$year][$month][$day];
        
        // empty content
        $content = [];
        $leave = 0;
        $paid_leave = 0;
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
              $paid_leave += $data['request']['duration'];
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
          $content[] = 'Onb. v: ' . $hours . ':' . sprintf("%02s", $minutes);
        }
        
        if($paid_leave > 0){
          $hours = floor($paid_leave / 60);
          $minutes = $leave - ($hours * 60);
          $content[] = 'b. v: ' . $hours . ':' . sprintf("%02s", $minutes);
        }

        if($sick > 0){
          $hours = floor($sick / 60);
          $minutes = $sick - ($hours * 60);
          $content[] = 'z: ' . $hours . ':' . sprintf("%02s", $minutes);
        }
        
        // calculate the sick hours and the minutes from the duration (in minutes)
        if($time_for_time > 0){
          $hours = floor($time_for_time / 60);
          $minutes = $time_for_time - ($hours * 60);
          $content[] = 't: ' . $hours . ':' . sprintf("%02s", $minutes);
        }
        
        switch($this->_formValues['period_value']){
          case 'year':
            $row[date('m-d', $timestamp)] = implode(' ', $content);
            break;
          case 'month':
            $row[date('d', $timestamp)] = implode(' ', $content);
            break;
          case 'week':
            $row[date('Y-m-d', $timestamp)] = implode(' ', $content);
            break;
        }
      }
      
      $rows[] = $row;
    }
  }
}