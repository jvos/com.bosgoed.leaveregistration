<?php

/**
 * Collection of upgrade steps
 */
class CRM_Leaveregistration_Upgrader extends CRM_Leaveregistration_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  /**
   * Example: Run an external SQL script when the module is installed
   *
  public function install() {
    $this->executeSqlFile('sql/myinstall.sql');
  }

  /**
   * Example: Run an external SQL script when the module is uninstalled
   *
  public function uninstall() {
   $this->executeSqlFile('sql/myuninstall.sql');
  }

  /**
   * Example: Run a simple query when a module is enabled
   *
  public function enable() {
    CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 1 WHERE bar = "whiz"');
  }

  /**
   * Example: Run a simple query when a module is disabled
   *
  public function disable() {
    CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
  }

  /**
   * Example: Run a couple simple queries
   *
   * @return TRUE on success
   * @throws Exception
   *
  public function upgrade_4200() {
    $this->ctx->log->info('Applying update 4200');
    CRM_Core_DAO::executeQuery('UPDATE foo SET bar = "whiz"');
    CRM_Core_DAO::executeQuery('DELETE FROM bang WHERE willy = wonka(2)');
    return TRUE;
  } // */


  /**
   * Example: Run an external SQL script
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4201() {
    $this->ctx->log->info('Applying update 4201');
    // this path is relative to the extension base dir
    $this->executeSqlFile('sql/upgrade_4201.sql');
    return TRUE;
  } // */


  /**
   * Example: Run a slow upgrade process by breaking it up into smaller chunk
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4202() {
    $this->ctx->log->info('Planning update 4202'); // PEAR Log interface

    $this->addTask(ts('Process first step'), 'processPart1', $arg1, $arg2);
    $this->addTask(ts('Process second step'), 'processPart2', $arg3, $arg4);
    $this->addTask(ts('Process second step'), 'processPart3', $arg5);
    return TRUE;
  }
  public function processPart1($arg1, $arg2) { sleep(10); return TRUE; }
  public function processPart2($arg3, $arg4) { sleep(10); return TRUE; }
  public function processPart3($arg5) { sleep(10); return TRUE; }
  // */


  /**
   * Example: Run an upgrade with a query that touches many (potentially
   * millions) of records by breaking it up into smaller chunks.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4203() {
    $this->ctx->log->info('Planning update 4203'); // PEAR Log interface

    $minId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(min(id),0) FROM civicrm_contribution');
    $maxId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(max(id),0) FROM civicrm_contribution');
    for ($startId = $minId; $startId <= $maxId; $startId += self::BATCH_SIZE) {
      $endId = $startId + self::BATCH_SIZE - 1;
      $title = ts('Upgrade Batch (%1 => %2)', array(
        1 => $startId,
        2 => $endId,
      ));
      $sql = '
        UPDATE civicrm_contribution SET foobar = whiz(wonky()+wanker)
        WHERE id BETWEEN %1 and %2
      ';
      $params = array(
        1 => array($startId, 'Integer'),
        2 => array($endId, 'Integer'),
      );
      $this->addTask($title, 'executeSql', $sql, $params);
    }
    return TRUE;
  } // */
  
  /*
   * Upgrade to version 2.0
   * Task 1: Create / Update contact sub types, relationship types, activity type, custom groups, option groups, custom fields and option values
   * Task 2: Copy all information from leaveregistration request fields to the new leaveregistration request fields
   * Task 3: Delete the old leaveregistration fields, namely leave_request_each, leave_request_day_week_month_year, leave_request_on, leave_request_day_of_the_week and leave_request_month
   * Task 3: Delete leave type mom_dad_day_contiguous
   */
  public function upgrade_2000() {
    $this->ctx->log->info('Upgrade to version 2.0'); // PEAR Log interface
    
    // task 1
    $this->addTask(ts('Create / Update contact sub types, relationship types, activity type and custom fields'), 'processPart1');
    
    // get old fields column name
    $old_fields = array
    (
      'leave_request_each' => array('name' => 'leave_request_each'),
      'leave_request_day_week_month_year' => array('name' => 'leave_request_day_week_month_year'),
      'leave_request_on' => array('name' => 'leave_request_on'),
      'leave_request_day_of_the_week' => array('name' => 'leave_request_day_of_the_week'),
      'leave_request_month' => array('name' => 'leave_request_month'),
    );
    
    foreach($old_fields as $name => $custom_field){
      $params = array(
        'version' => 3,
        'sequential' => 1,
        'name' => $name,
      );
      $result = civicrm_api('CustomField', 'getsingle', $params);
      $old_fields[$name] = $result;
    }
    
    // task 2
    $this->addTask(ts('Copy all information from leaveregistration request fields to the new leaveregistration request fields'), 'processPart2', $old_fields);
    
    // task 3
    $this->addTask(ts('Delete the old leaveregistration fields'), 'processPart3', $old_fields);
    
    // task 4
    $this->addTask(ts('Delete leave type mom dad day contiguous'), 'processPart4');
    
    return TRUE;   
  }
  
  public function processPart1(){
    return leaveregistration_civicrm_install();
  }
  
  public function processPart2($old_fields){  
     // set all fields
    $lr = new leaveregistration('civicrm', 'upgrade_2000_processPart2');
    $lr->set_fields();
        
    // get all request with leave type mom_dad_day, study_leave and care
    $query = "SELECT * FROM " . $lr->custom_groups['leave_request']['table_name'];
    $query .= " WHERE " . $lr->custom_fields['leave_request_leave_type']['column_name'] . " = 'mom_dad_day' ";  
    $query .= " OR " . $lr->custom_fields['leave_request_leave_type']['column_name'] . " = 'mom_dad_day_contiguous' ";  
    $query .= " OR " . $lr->custom_fields['leave_request_leave_type']['column_name'] . " = 'study_leave' ";  
    $query .= " OR " . $lr->custom_fields['leave_request_leave_type']['column_name'] . " = 'care' ";  
    
    $dao = CRM_Core_DAO::executeQuery($query);    
    while($dao->fetch()){            
      // copy all data
      $query = "UPDATE " . $lr->custom_groups['leave_request']['table_name'];
      $query .= " SET";      
      
      switch($dao->{$lr->custom_fields['leave_request_leave_type']['column_name']}){
        case 'mom_dad_day_contiguous':
          $query .= " " . $lr->custom_fields['leave_request_leave_type']['column_name'] . " = 'mom_dad_day'";
          $query .= ", " . $lr->custom_fields['leave_request_daily_weekly_monthly_annually']['column_name'] . " = 'daily'";
          $query .= ", " . $lr->custom_fields['leave_request_daily_each']['column_name'] . " = '1'";
          $query .= ", " . $lr->custom_fields['leave_request_daily_every_day_working_day']['column_name'] . " = 'working_day'";
          break;
        
        case 'mom_dad_day':
        case 'study_leave':
        case 'care':
          switch($dao->{$old_fields['leave_request_day_week_month_year']['column_name']}){
            case 'day':
              $query .= " " . $lr->custom_fields['leave_request_daily_weekly_monthly_annually']['column_name'] . " = 'daily'";
              $query .= ", " . $lr->custom_fields['leave_request_daily_each']['column_name'] . " = '" . $dao->{$old_fields['leave_request_each']['column_name']} . "'";
              break;

            case 'week':
              $query .= " " . $lr->custom_fields['leave_request_daily_weekly_monthly_annually']['column_name'] . " = 'weekly'";
              $query .= ", " . $lr->custom_fields['leave_request_weekly_each']['column_name'] . " = '" . $dao->{$old_fields['leave_request_each']['column_name']} . "'";
              if('nvt' != $dao->{$old_fields['leave_request_day_of_the_week']['column_name']}){
                $query .= ", " . $lr->custom_fields['leave_request_weekly_day_of_the_week']['column_name'] . " = '" . strtolower($dao->{$old_fields['leave_request_day_of_the_week']['column_name']}) . "'";
              }
              break;

            case 'month':
              $query .= " " . $lr->custom_fields['leave_request_daily_weekly_monthly_annually']['column_name'] . " = 'monthly'";
              $query .= ", " . $lr->custom_fields['leave_request_monthly_each']['column_name'] . " = '" . $dao->{$old_fields['leave_request_each']['column_name']} . "'";
              
              switch($dao->{$old_fields['leave_request_on']['column_name']}){
                case 'nvt':
                  switch($dao->{$old_fields['leave_request_on']['column_name']}){
                    case 'nvt':
                      // get day number of the month
                      $day = date('d', strtotime($lr->custom_fields['leave_request_from_date']['column_name']));
                      $query .= ", " . $lr->custom_fields['leave_request_monthly_every_day_of_the_month_day_of_the_week']['column_name'] . " = 'every_day'";
                      $query .= ", " . $lr->custom_fields['leave_request_monthly_day_of_the_month']['column_name'] . " = '" . $day . "'";
                      break;
                    
                    default:
                      // check if it is the first, second, third, fourth or last day of the month
                      
                  }
                  break;
                
                case 'next':
                  switch($dao->{$old_fields['leave_request_on']['column_name']}){
                    case 'nvt':
                  
                      break;
                    
                    default:
                      
                  }
                  break;
                
                case 'previous':
                  switch($dao->{$old_fields['leave_request_on']['column_name']}){
                    case 'nvt':
                  
                      break;
                    
                    default:
                      
                  }
                  break;
                
                case 'first':
                  switch($dao->{$old_fields['leave_request_on']['column_name']}){
                    case 'nvt':
                  
                      break;
                    
                    default:
                      
                  }
                  break;
                
                case 'last':
                  switch($dao->{$old_fields['leave_request_on']['column_name']}){
                    case 'nvt':
                  
                      break;
                    
                    default:
                      
                  }
                  break;
              }
              break;

            case 'year':
              $query .= " " . $this->custom_fields['leave_request_daily_weekly_monthly_annually']['column_name'] . " = 'annually'";
              $query .= ", " . $this->custom_fields['leave_request_annually_each']['column_name'] . " = '" . $dao->{$old_fields['leave_request_each']['column_name']} . "'";
              
              switch($dao->{$old_fields['leave_request_on']['column_name']}){
                case 'nvt':
                  switch($dao->{$old_fields['leave_request_on']['column_name']}){
                    case 'nvt':
                  
                      break;
                    
                    default:
                      
                  }
                  break;
                
                case 'next':
                  switch($dao->{$old_fields['leave_request_on']['column_name']}){
                    case 'nvt':
                  
                      break;
                    
                    default:
                      
                  }
                  break;
                
                case 'previous':
                  switch($dao->{$old_fields['leave_request_on']['column_name']}){
                    case 'nvt':
                  
                      break;
                    
                    default:
                      
                  }
                  break;
                
                case 'first':
                  switch($dao->{$old_fields['leave_request_on']['column_name']}){
                    case 'nvt':
                  
                      break;
                    
                    default:
                      
                  }
                  break;
                
                case 'last':
                  switch($dao->{$old_fields['leave_request_on']['column_name']}){
                    case 'nvt':
                  
                      break;
                    
                    default:
                      
                  }
                  break;
              }
              break;
          }
          break;
      }
      
      $query .= " WHERE id = '" . $dao->id . "' ";
      
      CRM_Core_DAO::executeQuery($query);
    }
    return TRUE;
  }
  
  public function processPart3($old_fields){
    
    foreach($old_fields as $name => $custom_field){
      $params = array(
        'version' => 3,
        'sequential' => 1,
        'id' => $custom_field['id'],
      );
      $result = civicrm_api('CustomField', 'delete', $params);
      
    }
    
    return TRUE;
  }
  
  public function processPart4(){
    // get option_group_id from leave_request_leave_type for mom_dad_day_contiguous
    $params = array(
      'version' => 3,
      'sequential' => 1,
      'name' => 'leave_request_leave_type',
    );
    $result = civicrm_api('OptionGroup', 'getsingle', $params);
    $option_group_id = $result['id'];
    
    // get mom_dad_day_contiguous
    $params = array(
      'version' => 3,
      'sequential' => 1,
      'option_group_id' => $option_group_id,
      'name' => 'Parental contiguous',
      'value' => 'mom_dad_day_contiguous',
    );
    $result = civicrm_api('OptionValue', 'getsingle', $params);
    
    // delete option value mom_dad_day_contiguous
    $params = array(
      'version' => 3,
      'sequential' => 1,
      'id' => $result['id'],
    );
    $result = civicrm_api('OptionValue', 'delete', $params);

    return TRUE;
  }
}
