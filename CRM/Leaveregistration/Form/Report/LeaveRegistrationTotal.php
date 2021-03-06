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
        'alias' => 'department',
        'fields' => array(
          'department' => array(
            'title' => ts('Department'),
            'default' => TRUE,
            'dbAlias' => 'department_civireport.display_name',
          ),
        ),
        'filters' => array(
          'department' => array(
            'title' => ts('Department'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $leaveregistrationConfig->departments,
            'dbAlias' => 'department_civireport_relationship.contact_id_b',
          ),
        ),
        'order_bys' => array(
          'department' => array(
            'title' => ts('Department'),
            'name' => 'department_department',
          ),
        ),
      ),
      'business' => array(
        'alias' => 'business',
        'fields' => array(
          'business' => array(
            'title' => ts('Business'),
            'default' => TRUE,
            'dbAlias' => 'business_civireport.display_name',
          ),
        ),
        'filters' => array(
          'business' => array(
            'title' => ts('Business'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => $leaveregistrationConfig->business,
            'dbAlias' => 'business_civireport_relationship.contact_id_b',
          ),
        ),
        'order_bys' => array(
          'business' => array(
            'title' => ts('Business'),
            'name' => 'business_business',
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
    $this->assign('reportTitle', ts('Leave Registration Total Report'));
    parent::preProcess();
  }
  
  function select() {
    $select = $this->_columnHeaders = array();
    
    foreach ($this->_columns as $tableName => $table) {
      if('leaveregistration' != $tableName){
        if (array_key_exists('fields', $table)) {
          foreach ($table['fields'] as $fieldName => $field) {
            if (CRM_Utils_Array::value('required', $field) ||
              CRM_Utils_Array::value($fieldName, $this->_params['fields'])) {
              $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
              $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'];
              $this->_columnHeaders["{$tableName}_{$fieldName}"]['type'] = CRM_Utils_Array::value('type', $field);
            }
          }
        }
      }
    }
    
    //$this->_select = " SELECT " . implode(', ', $select) . " ";
    $this->_select = " " . implode(', ', $select) . " ";
  }

  function from() {
    $this->_from = NULL;
    
    $this->_from = " FROM civicrm_contact {$this->_aliases['civicrm_contact']} {$this->_aclFrom} ";
    
    $this->_from .= " LEFT JOIN civicrm_relationship AS {$this->_aliases['department']}_relationship ON {$this->_aliases['department']}{$this->_aclFrom}_relationship.contact_id_a = {$this->_aliases['civicrm_contact']}.id";
    $this->_from .= " LEFT JOIN civicrm_contact AS {$this->_aliases['department']} ON {$this->_aliases['department']}.id = {$this->_aliases['department']}_relationship.contact_id_b";
    
    $this->_from .= " LEFT JOIN civicrm_relationship AS {$this->_aliases['business']}_department_relationship ON {$this->_aliases['business']}_department_relationship.contact_id_a = {$this->_aliases['civicrm_contact']}.id";		
    $this->_from .= " INNER JOIN civicrm_relationship AS {$this->_aliases['business']}_relationship ON {$this->_aliases['business']}_relationship.contact_id_a = {$this->_aliases['business']}_department_relationship.contact_id_b ";
    $this->_from .= " LEFT JOIN civicrm_contact AS {$this->_aliases['business']} ON {$this->_aliases['business']}.id = {$this->_aliases['business']}_relationship.contact_id_b";
  }

  function where() {
    $leaveregistrationConfig = CRM_Leaveregistration_Config::singleton();
    
    $clauses = array();
    
    foreach ($this->_columns as $tableName => $table) {
      if('leaveregistration' != $tableName){
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
    
    $clauses[] = " {$this->_aliases['civicrm_contact']}.contact_type = 'Individual' ";
    $clauses[] = " {$this->_aliases['civicrm_contact']}.contact_sub_type = 'Employee' ";
    
    
    $clauses[] = " {$this->_aliases['department']}_relationship.relationship_type_id = '{$leaveregistrationConfig->lr->relationship_types['employee_of']['id']}' ";
    
    $clauses[] = " {$this->_aliases['business']}_department_relationship.relationship_type_id = '{$leaveregistrationConfig->lr->relationship_types['employee_of']['id']}' ";
    $clauses[] = " {$this->_aliases['business']}_relationship.relationship_type_id = '{$leaveregistrationConfig->lr->relationship_types['department_of']['id']}' ";
        
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
      if('department' == $order['column']){
        $prefix = 'department';
      }else if('business' == $order['column']){
        $prefix = 'business';
      }else {
        $prefix = 'civicrm_contact';
      }
      
      $orderby[] = $prefix . "_" . $order['column'] . " " . $order['order'];
    }
        
    $this->_orderBy = " ORDER BY " . implode(', ', $orderby) . " ";
  }

  function buildQuery($applyLimit = TRUE) {
    $this->select();
    $this->from();
    $this->customDataFrom();
    $this->where();
    $this->groupBy();
    $this->orderBy();

    // order_by columns not selected for display need to be included in SELECT
    $unselectedSectionColumns = $this->unselectedSectionColumns();
    foreach ($unselectedSectionColumns as $alias => $section) {
      $this->_select .= ", {$section['dbAlias']} as {$alias}";
    }

    if ($applyLimit && empty($this->_params['charts'])) {
      $this->limit();
    }
    CRM_Utils_Hook::alterReportVar('sql', $this, $this);

    $sql = "SELECT * FROM (SELECT {$this->_select} {$this->_from} {$this->_where} {$this->_groupBy} {$this->_having} {$this->_orderBy} {$this->_limit} ) as CC";
    return $sql;
  }
  
  function postProcess() {

    $this->beginPostProcess();

    // get the acl clauses built before we assemble the query
    $this->buildACLClause($this->_aliases['civicrm_contact']);
    $sql = $this->buildQuery(TRUE);

    $rows = [];
    $this->buildRows($sql, $rows);
    //$this->alterDisplay($rows);

    $this->formatDisplay($rows);
    $this->doTemplateAssignment($rows);
    $this->endPostProcess($rows);
  }
    
  function setPager($rowCount = self::ROW_COUNT_LIMIT) {

    // CRM-14115, over-ride row count if rowCount is specified in URL
    if ($this->_dashBoardRowCount) {
      $rowCount = $this->_dashBoardRowCount;
    }

    if ($this->_limit && ($this->_limit != '')) {
      //$sql = "SELECT FOUND_ROWS();";
      //$this->_rowsFound = CRM_Core_DAO::singleValueQuery($sql);
      
      $sql = "SELECT COUNT(*) FROM (SELECT {$this->_select} {$this->_from} {$this->_where} {$this->_groupBy} {$this->_having} {$this->_orderBy}  ) as CC";
      $this->_rowsFound = CRM_Core_DAO::singleValueQuery($sql);
      
      
      $params = array(
        'total' => $this->_rowsFound,
        'rowCount' => $rowCount,
        'status' => ts('Records') . ' %%StatusMessage%%',
        'buttonBottom' => 'PagerBottomButton',
        'buttonTop' => 'PagerTopButton',
        'pageID' => $this->get(CRM_Utils_Pager::PAGE_ID),
      );

      $pager = new CRM_Utils_Pager($params);
      $this->assign_by_ref('pager', $pager);
      $this->ajaxResponse['totalRows'] = $this->_rowsFound;
    }
  }
  
  function buildRows($sql, &$rows) {
    $leaveregistrationConfig = CRM_Leaveregistration_Config::singleton();
    
    // set days, months and years to empty
    $days = array();
    $months = array();
    $years = array();
        
    $year = $this->_formValues['year_value'];
    
    switch($this->_formValues['period_value']){
      case 'year':
        $years = [$this->_formValues['year_value']];
        $months = [];
        
        $first_day = date('Y-m-d', strtotime($this->_formValues['year_value'] . '-01-01'));
        $last_day = date('Y-m-d', strtotime($this->_formValues['year_value'] . '-12-31'));
        
        break;
      case 'month':
        $years = [$this->_formValues['year_value']];
        $months =[];
        
        if(isset($this->_formValues['month_op']) and 'in' == $this->_formValues['month_op']){
          if(!isset($this->_formValues['month_value']) or empty($this->_formValues['month_value'])){
            $months = $leaveregistrationConfig->months;
          }else {
            $months = $this->_formValues['month_value'];
          }
        }
        
        if(isset($this->_formValues['month_op']) and 'notin' == $this->_formValues['month_op']){
          foreach ($leaveregistrationConfig->months as $month){
            if(!in_array($month, $this->_formValues['month_value'])){
              $months[] = $month;
            }
          }
        }
        
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
        $weeks = [];
        
        if(isset($this->_formValues['week_op']) and 'in' == $this->_formValues['week_op']){
          if(!isset($this->_formValues['week_value']) or empty($this->_formValues['week_value'])){
            $weeks = $leaveregistrationConfig->weeks;
          }else {
            $weeks = $this->_formValues['week_value'];
          }
        }
        
        if(isset($this->_formValues['week_op']) and 'notin' == $this->_formValues['week_op']){
          foreach ($leaveregistrationConfig->weeks as $week){
            if(!in_array($month, $this->_formValues['week_value'])){
              $weeks[] = $week;
            }
          }
        }
        
        $first_last_days = [];
        foreach($weeks as $week){
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
        
    // create a array with all the employees, add department en business to the array, this
    // is also needed for order by department or business
    $datas = [];
    $cids = [];
        
    $dao = CRM_Core_DAO::executeQuery($sql);
    while ($dao->fetch()) {
      $data = [];
      foreach($this->_columnHeaders as $id => $columnheader){
        $data[$id] = $dao->$id;
      }
      
      $datas[$dao->civicrm_contact_id] = $data;
      $cids[] = $dao->civicrm_contact_id;
    }
        
    $lr = new leaveregistration('civicrm', 'CRM_Leaveregistration_Form_Report_LeaveRegistration');
    $lr->set_fields();
    $lr->set_contacts($cids);
    $lr->set_data($years, $months);
        
    foreach($first_last_days as $month_week => $first_last){
      $timestamp = strtotime($first_last['first']);
      $timestamp_last = strtotime($first_last['last']);

      switch($this->_formValues['period_value']){
        case 'year':
          $this->_columnHeaders[date('Y', $timestamp)] = array('title' => ts('Year') . ' ' . date('Y'));
          $this->_columnHeaders[date('Y', $timestamp) . '_sub_total'] = array('title' => ts('Total') . ' ' . ts('Year') . ' ' . date('Y'));
          break;
        case 'month':
          $this->_columnHeaders[date('m', $timestamp)] = array('title' => ts('Month') . ' ' . ts(date('F', $timestamp)) . ' ' . date('m', $timestamp));
          $this->_columnHeaders[date('m', $timestamp) . '_sub_total'] = array('title' => ts('Total') . ' ' . ts('Month') . ' ' . ts(date('F', $timestamp)) . ' ' . date('m', $timestamp));
          break;
        case 'week':
          $this->_columnHeaders[date('W', $timestamp)] = array('title' => ts('Week') . ' ' . date('W', $timestamp) . ' ' . date('m-d', $timestamp) . '/' . date('m-d', $timestamp_last));
          $this->_columnHeaders[date('W', $timestamp) . '_sub_total'] = array('title' => ts('Total') . ' ' . ts('Week') . ' ' . date('W', $timestamp));
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
        $hours = floor(round($leave) / 60);
        $minutes = round($leave) - ($hours * 60);
        $content[] = 'v: ' . $hours . ':' . sprintf("%02s", $minutes);

        $hours = floor(round($paid_leave) / 60);
        $minutes = round($paid_leave) - ($hours * 60);
        $content[] = 'bv: ' . $hours . ':' . sprintf("%02s", $minutes);

        $hours = floor(round($sick) / 60);
        $minutes = round($sick) - ($hours * 60);
        $content[] = 'z: ' . $hours . ':' . sprintf("%02s", $minutes);
        
        $hours = floor(round($time_for_time) / 60);
        $minutes = round($time_for_time) - ($hours * 60);
        $content[] = 't: ' . $hours . ':' . sprintf("%02s", $minutes);
                
        $sub_total = ($leave + $paid_leave + $sick) - $time_for_time;
        $total += $sub_total;
        
        if('-' == substr($sub_total, 0, 1)){
          $sub_total = substr($sub_total, 1); 
          $hours = floor(round($sub_total) / 60);
          $minutes = round($sub_total) - ($hours * 60);
          $sub_total = '-' . $hours . ':' . sprintf("%02s", $minutes);

        }else {
          $hours = floor(round($sub_total) / 60);
          $minutes = round($sub_total) - ($hours * 60);
          $sub_total = $hours . ':' . sprintf("%02s", $minutes);
        }
        
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
      
      if('-' == substr($total, 0, 1)){
        $total = substr($total, 1); 
        $hours = floor(round($total) / 60);
        $minutes = round($total) - ($hours * 60);
        $total = '-' . $hours . ':' . sprintf("%02s", $minutes);

      }else {
        $hours = floor(round($total) / 60);
        $minutes = round($total) - ($hours * 60);
        $total = $hours . ':' . sprintf("%02s", $minutes);
      }
      
      $row['total'] = $total;
      $rows[] = $row;
    }
  }
}
