<?php
/**
 * This is a copy of the LeaveRegistration Report, with small 
 * changes in the __contstruct and where and the biggest in
 * the buildRow function.
 */
class CRM_Leaveregistration_Form_Report_LeaveRegistrationTotal extends CRM_Report_Form {

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
        'fields' => array(
          'department' => array(
            'title' => ts('Department'),
            'default' => TRUE,
          ),
        ),
        'filters' => array(
          'department' => array(
            'title' => ts('Department'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $leaveregistrationConfig->departments,
          ),
        ),
        'order_bys' => array(
          'department' => array(
            'title' => ts('Department'),
          ),
        ),
      ),
      'business' => array(
        'fields' => array(
          'business' => array(
            'title' => ts('Business'),
            'default' => TRUE,
          ),
        ),
        'filters' => array(
          'business' => array(
            'title' => ts('Business'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $leaveregistrationConfig->business,
          ),
        ),
        'order_bys' => array(
          'business' => array(
            'title' => ts('Business'),
          ),
        ),
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
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $leaveregistrationConfig->months,
            'default' => date('m'),
          ),
          'week' => array(
            'title' => ts('Week'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
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
    
    // need to be after department and business, if id_value is still empty, set to all emplyees ids
    if(!isset($this->_params['id_value']) or empty($this->_params['id_value'])){
      $ids = $leaveregistrationConfig->getEmployeesIds();
      $this->_params['id_value'] = array_merge($this->_params['id_value'], $ids);
      $this->_formValues['id_value'] = array_merge($this->_formValues['id_value'], $ids);
    }
    
    // if month_value is empty set to all months in a year
    if('month' == $this->_params['period_value']){
      if(!isset($this->_params['month_value']) or empty($this->_params['month_value'])){
        $months = $leaveregistrationConfig->months;
        $this->_params['month_value'] = array_merge($this->_params['month_value'], $months);
        $this->_formValues['month_value'] = array_merge($this->_formValues['month_value'], $months);
      }
    }
    
    // if week_value is empty set to all weeks in a year
    if('week' == $this->_params['period_value']){
      if(!isset($this->_params['week_value']) or empty($this->_params['week_value'])){
        $weeks = $leaveregistrationConfig->weeks;
        $this->_params['week_value'] = array_merge($this->_params['week_value'], $weeks);
        $this->_formValues['week_value'] = array_merge($this->_formValues['week_value'], $weeks);
      }
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
    
    $clauses[] = " contact_civireport.contact_type = 'Individual' ";
    $clauses[] = " contact_civireport.contact_sub_type = 'Employee' ";
    
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
    foreach($this->_params['order_bys'] as $key => $order){
      if('department' != $order['column'] and 'business' != $order['column']){
        $orderby[] = $this->_aliases['civicrm_contact']. "." . $order['column'] . " " . $order['order'];
      }
    }
        
    $this->_orderBy = " ORDER BY " . implode(', ', $orderby);
  }

  function postProcess() {

    $this->beginPostProcess();

    // get the acl clauses built before we assemble the query
    $this->buildACLClause($this->_aliases['civicrm_contact']);
    $sql = $this->buildQuery(TRUE);

    $rows = array();
    $this->buildRows($sql, $rows);
    //$this->alterDisplay($rows);

    $this->formatDisplay($rows);
    $this->doTemplateAssignment($rows);
    $this->endPostProcess($rows);
  }
      
  function buildRows($sql, &$rows) {        
    // set days, months and years to empty
    $days = array();
    $months = array();
    $years = array();
        
    switch($this->_formValues['period_value']){
      case 'year':
        $years = [$this->_formValues['year_value']];
        $months = [];
        
        $first_day = date('Y-m-d', strtotime($this->_formValues['year_value'] . '-01-01'));
        $last_day = date('Y-m-d', strtotime($this->_formValues['year_value'] . '-12-31'));
        
        break;
      case 'month':
        $years = [$this->_formValues['year_value']];
        $months = $this->_formValues['month_value'];
        
        $first_last_days = [];
        foreach($months as $month){
          $first_last_days[$month] = [];
          $first_last_days[$month]['first'] = date('Y-m-d', strtotime($this->_formValues['year_value'] . '-' . $month . '-01'));
          $first_last_days[$month]['last'] = date('Y-m-t', strtotime($this->_formValues['year_value'] . '-' . $month . '-02'));
        }
        break;
      case 'week':
        $dayrange  = array(1,2,3,4,5,6,7);
               
        $years = [];
        $months = [];
        
        $first_last_days = [];
        foreach($this->_formValues['week_value'] as $week){
          $days = [];          
          
          // calculate the days in the week
          for($count=0; $count<=6; $count++) {
            $week = ($count == 7)?($week + 1): ($week);
            $week = str_pad($week,2,'0',STR_PAD_LEFT);

            $days[] = date('Y-m-d', strtotime($this->_formValues['year_value']."W".$week.($dayrange[$count]))); 
          }

          // calculate months and years from date
          // calculate years from days
          foreach($days as $date){
            $year = date('Y', strtotime($date));
            $years[$year] = $year;
            $month = date('m', strtotime($date));
            $months[$month] = $month;
          }

          $first_last_days[$week] = [];
          $first_last_days[$week]['first'] = date('Y-m-d', strtotime($days[0]));
          $first_last_days[$week]['last'] = date('Y-m-d', strtotime(end($days)));
        }
        break;
    }
        
    $start_date_totime = strtotime($first_day);
    $end_date_totime = strtotime($last_day);    
    
    $lr = new leaveregistration('civicrm', 'CRM_Leaveregistration_Form_Report_LeaveRegistration');
    $lr->set_fields();
    $lr->set_contacts($this->_formValues['id_value']);
    $lr->set_data($years, $months);
        
    // create a array with all the employees, add department en business to the array, this
    // is also needed for order by department or business
    $datas = [];
    $dao = CRM_Core_DAO::executeQuery($sql);
    while ($dao->fetch()) {
      $data = [];
      foreach($this->_columnHeaders as $id => $columnheader){
        $data[$id] = $dao->$id;
      }
      
      foreach($lr->employees[$dao->civicrm_contact_id]['departments'] as $did => $department_id){
        $data['department'] = [];
        $data['department']['id'] = $did;
        $data['departments'][$did] = [];
        $data['departments'][$did]['id'] = $lr->departments[$dao->civicrm_contact_id][$did]['id'];
        $data['departments'][$did]['display_name'] = $lr->departments[$dao->civicrm_contact_id][$did]['display_name'];
      }

      foreach($lr->employees[$dao->civicrm_contact_id]['businesses'] as $bid => $business_id){
        $data['business'] = [];
        $data['business']['id'] = $bid;
        $data['businesses'][$bid] = [];
        $data['businesses'][$bid]['id'] = $lr->businesses[$dao->civicrm_contact_id][$bid]['id'];
        $data['businesses'][$bid]['display_name'] = $lr->businesses[$dao->civicrm_contact_id][$bid]['display_name'];
      }
      
      $datas[$dao->civicrm_contact_id] = $data;
    }
        
    // order department and business
    if(isset($this->_formValues['order_bys'])){
      foreach($this->_formValues['order_bys'] as $key => $orderby){
        
        if('department' == $orderby['column']){          
          $orderby_department_name = [];
          $orderby_department_id = [];
          foreach($datas as $cid => $data){
            $orderby_department_name[$data['department']['id']] = $data['departments'][$data['department']['id']]['display_name'];
            $orderby_department_id[$data['departments'][$data['department']['id']]['display_name']] = $data['department']['id'];
          }

          sort($orderby_department_name);
          if('DESC' == $orderby['order']){
            rsort($orderby_department_name);
          }
                    
          $orderby_department = [];
          foreach($datas as $cid => $data){
            if(!isset($orderby_department[$data['department']['id']])){
              $orderby_department[$data['department']['id']] = [];
            }
            $orderby_department[$data['department']['id']][$cid] = $cid;
          }
                    
          $orderby_datas = $datas; // copy datas
          
          $datas = [];
          foreach($orderby_department_name as $key => $display_name){
            $did = $orderby_department_id[$display_name];
            
            foreach($orderby_department[$did] as $cid => $employee){
              $datas[$cid] = $orderby_datas[$cid];
            }
          }
        }
        
        if('business' == $orderby['column']){          
          $orderby_business_name = [];
          $orderby_business_id = [];
          foreach($datas as $cid => $data){
            $orderby_business_name[$data['business']['id']] = $data['businesses'][$data['business']['id']]['display_name'];
            $orderby_business_id[$data['businesses'][$data['business']['id']]['display_name']] = $data['business']['id'];
          }

          sort($orderby_business_name);
          if('DESC' == $orderby['order']){
            rsort($orderby_business_name);
          }
                    
          $orderby_business = [];
          foreach($datas as $cid => $data){
            if(!isset($orderby_business[$data['business']['id']])){
              $orderby_business[$data['business']['id']] = [];
            }
            $orderby_business[$data['business']['id']][$cid] = $cid;
          }
                    
          $orderby_datas = $datas; // copy datas

          $datas = [];
          foreach($orderby_business_name as $key => $display_name){
            $bid = $orderby_business_id[$display_name];
            
            foreach($orderby_business[$bid] as $cid => $employee){
              $datas[$cid] = $orderby_datas[$cid];
            }
          }
        }
      }
    }
            
    // set column header
    if(isset($this->_formValues['fields']['department']) and $this->_formValues['fields']['department']){
      $this->_columnHeaders['department'] = array('title' => ts('Department'));
    }
    
    if(isset($this->_formValues['fields']['business']) and $this->_formValues['fields']['business']){
      $this->_columnHeaders['business'] = array('title' => ts('Business'));
    }
        
    foreach($first_last_days as $month_week => $first_last){
      $timestamp = strtotime($first_last['first']);
      $timestamp_last = strtotime($first_last['last']);

      switch($this->_formValues['period_value']){
        case 'year':
          $this->_columnHeaders[date('Y', $timestamp)] = array('title' => ts('Year') . ' ' . date('Y'));
          $this->_columnHeaders[date('Y', $timestamp) . '_sub_total'] = array('title' => ts('Sub total') . ' ' . ts('Year') . ' ' . date('Y'));
          break;
        case 'month':
          $this->_columnHeaders[date('m', $timestamp)] = array('title' => ts('Month') . ' ' . ts(date('F', $timestamp)) . ' ' . date('m', $timestamp));
          $this->_columnHeaders[date('m', $timestamp) . '_sub_total'] = array('title' => ts('Sub total') . ' ' . ts('Month') . ' ' . ts(date('F', $timestamp)) . ' ' . date('m', $timestamp));
          break;
        case 'week':
          $this->_columnHeaders[date('W', $timestamp)] = array('title' => ts('Week') . ' ' . date('W', $timestamp) . ' ' . date('m-d', $timestamp) . '/' . date('m-d', $timestamp_last));
          $this->_columnHeaders[date('W', $timestamp) . '_sub_total'] = array('title' => ts('Sub total') . ' ' . ts('Week') . ' ' . date('W', $timestamp));
          break;
      }
    }
    
    $this->_columnHeaders['total'] = array('title' => ts('Total'));
        
    $rows = [];
    foreach ($datas as $cid => $data) {
      $row = [];
            
      // must before department and business
      foreach($this->_columnHeaders as $id => $columnheader){
        $row[$id] = $data[$id];
      }
      
      // department and business fields
      if(isset($this->_formValues['fields']['department']) and $this->_formValues['fields']['department']){
        $departments = [];
        foreach($data['departments'] as $did => $department){
          $departments[] = $department['display_name'];
        }
                
        $row['department'] = implode(', ', $departments);
      }

      if(isset($this->_formValues['fields']['business']) and $this->_formValues['fields']['business']){
        $businesses = [];
        foreach($data['businesses'] as $bid => $business){
          $businesses[] = $business['display_name'];
        }
        
        $row['business'] = implode(', ', $businesses);
      }
      
      $total = 0;
      
      foreach($first_last_days as $month_week => $first_last){
        $start_date_totime = strtotime($first_last['first']);
        $end_date_totime = strtotime($first_last['last']);
      
        // empty content
        $content = [];
        $leave = 0;
        $paid_leave = 0;
        $sick = 0;
        $time_for_time = 0;
        
        for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+1 day' , $timestamp )){
          $day = date('d', $timestamp);  
          $month = date('m', $timestamp);  
          $year = date('Y', $timestamp);

          // get the request from the contact, year, month and day
          $request = $lr->data[$data['civicrm_contact_id']][$year][$month][$day];

          /*if(isset($request['adjustments']['duration']) and 0 == $request['adjustments']['duration']){
            $content[] = 'rv';    
          }*/

          // switch between the leave types
          // and add the duration to the right counter (work, time for time, leave or sick)
          if(isset($request['request']['is_request']) and 1 == $request['request']['is_request'] and 'approved' == $request['request']['status']){
            switch($request['request']['leave_type']){
              case 'mom_dad_day':
              case 'mom_dad_day_contiguous':
              case 'doctor_visit':
              case 'study_leave':
              case 'care':   
              case 'special_leave':
              case 'maternity':
                $paid_leave += $request['request']['duration'];
                break;
              case 'sick_less_one_day': 
              case 'sick':
                $sick += $request['request']['duration'];
                break;
            }
          }

          // time_for_time
          if(isset($request['time_for_time']['is_time_for_time']) and 1 == $request['time_for_time']['is_time_for_time'] and 'approved' == $request['time_for_time']['status']){
            $time_for_time += $request['time_for_time']['duration'];
          }

          // normal_leave
          // leave type is normal_leave_less_one_day and normal_leave
          if(isset($request['normal_leave']['is_normal_leave']) and 1 == $request['normal_leave']['is_normal_leave'] and 'approved' == $request['normal_leave']['status']){
            $leave += $request['normal_leave']['duration'];
          }
        }
        
        // calculate the leave hours and the minutes from the duration (in minutes)
        $hours = floor($leave / 60);
        $minutes = $leave - ($hours * 60);
        $content[] = 'v: ' . $hours . ':' . sprintf("%02s", $minutes);

        $hours = floor($paid_leave / 60);
        $minutes = $paid_leave - ($hours * 60);
        $content[] = 'bv: ' . $hours . ':' . sprintf("%02s", $minutes);

        $hours = floor($sick / 60);
        $minutes = $sick - ($hours * 60);
        $content[] = 'z: ' . $hours . ':' . sprintf("%02s", $minutes);
        
        $hours = floor($time_for_time / 60);
        $minutes = $time_for_time - ($hours * 60);
        $content[] = 't: ' . $hours . ':' . sprintf("%02s", $minutes);
                
        $sub_total = ($leave + $paid_leave + $sick) - $time_for_time;
        $total += $sub_total;
        
        $hours = floor($sub_total / 60);
        $minutes = $sub_total - ($hours * 60);
        $sub_total = $hours . ':' . sprintf("%02s", $minutes);
        
        switch($this->_formValues['period_value']){
          case 'year':
            $row[date('Y', $start_date_totime)] = implode(' ', $content);
            $row[date('Y', $start_date_totime) . '_sub_total'] = $sub_total;
            break;
          case 'month':
            $row[date('m', $start_date_totime)] = implode(' ', $content);
            $row[date('m', $start_date_totime) . '_sub_total'] = $sub_total;
            break;
          case 'week':
            $row[date('W', $start_date_totime)] = implode(' ', $content);
            $row[date('W', $start_date_totime) . '_sub_total'] = $sub_total;
            break;
        }
      }
      
      $hours = floor($total / 60);
      $minutes = $total - ($hours * 60);
      $total = $hours . ':' . sprintf("%02s", $minutes);
        
      $row['total'] = $total;
      $rows[] = $row;
    }    
  }
}
