<?php
require_once 'CRM/Core/Page.php';

class CRM_Leaveregistration_Page_LeaveRegistration extends CRM_Core_Page {
  
  private $data = array();
  public $lr = array();
  public $lr_deph_col = array();
  public $lr_colids = array();
  
  function run($data = array()){ 
    $this->data = $data;
        
    if(isset($_POST) and !empty($_POST)){
      foreach($_POST as $field => $value){
        if('years' == $field or 'months' == $field or 'elements' == $field){
          $this->data[$field] = unserialize($value);
        }else {
          $this->data[$field] = $value;
        }
      }
    }
        
    $this->assign('base_url', CIVICRM_UF_BASEURL);
    $this->assign('extension_url', CIVICRM_UF_BASEURL . 'sites/all/modules/custom/civicrm/extensions/');
    
    // get civicrm contact id from drupal user
    $session = CRM_Core_Session::singleton();
    $user_cid = $session->get('userID'); 
    
    $this->data['user_cid'] = $user_cid;
    
    foreach($this->data as $field => $value){
      if('years' == $field or 'months' == $field){
        $this->assign($field, serialize($value));
      }else {
        $this->assign($field, $value);
      }
    }
        
    if(!isset($this->data['error_id']) or empty($this->data['error_id'])){
      echo('Leaveregistration, no error id !') . '<br/>' . PHP_EOL;
      return false;
    }
    
    if(!isset($this->data['error_platform']) or empty($this->data['error_platform'])){
      echo('Leaveregistration, no error platform !') . '<br/>' . PHP_EOL;
      return false;
    }
    
    // merge cid with user_cid, if it is the same
    $cids = array();
    $cids[$this->data['cid']] = $this->data['cid'];
    $cids[$this->data['user_cid']] = $this->data['user_cid'];
        
    // cid
    $this->lr = new leaveregistration($this->data['error_platform'], $this->data['error_id'], ['do' => true]);
    $this->lr->set_fields();
    $this->lr->set_contacts($cids);
    $this->lr->set_data($this->data['years'], $this->data['months']);
        
    // department head colleages
    if($this->lr->is_department_head[$this->data['cid']] or $this->lr->is_department_head[$this->data['user_cid']]){
      if($this->lr->is_department_head[$this->data['user_cid']]){
        $deph_col_cid = $this->data['user_cid'];
      }
      
      if($this->lr->is_department_head[$this->data['cid']]){
        $deph_col_cid = $this->data['cid'];
      }
      
      $colleages = array();
      foreach($this->lr->department_heads_colleages_ids[$deph_col_cid] as $did => $department){
        foreach($department['employees'] as $cid => $employee){
          if($cid != $this->data['user_cid'] and $cid != $this->data['cid']){
            $colleages[htmlentities($employee['display_name'])] = $employee;
          }
        }
      }

      $colids = array();      
      ksort($colleages);
      foreach($colleages as $display_name => $employee){
        $this->lr_colids[] = $employee['id'];
      }      
            
      $this->lr_deph_col = new leaveregistration($this->data['error_platform'], $this->data['error_id'] . ': department_heads_colleages_ids');
      $this->lr_deph_col->set_fields();
      $this->lr_deph_col->set_contacts($this->lr_colids);
      $this->lr_deph_col->set_data($this->data['years'], $this->data['months']);
    }
    
    if(isset($_POST['element']) and !empty($_POST['element'])){ 
      echo(call_user_func(array($this, $_POST['element'])));
      CRM_Utils_System::civiExit();
    }
  }
  
  public function js(){
    $this->assign('type', 'script');
    $this->assign('action', 'js');
    return self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');
  }
  
  public function css(){
    $this->assign('type', 'script');
    $this->assign('action', 'css');
    return self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');
  }
    
  private function response($name, $form, $type, $script){
    if('serialize' == $this->data['return']){
      return serialize($form);
      
    }else if('json' == $this->data['return']){
      return json_encode($form);
      
    }else {
      $this->assign('form', $form);
      $this->assign('type', $type);
      $this->assign('script', $script);
      
      return self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');
    }
  }
  
  /*
   * Only show if current cid is department head or administration
   */
  public function get_dephead_admin_link(){
    $error = '';
    
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_dephead_admin_link, no user_cid !');
    }
    
    // year
    if(!isset($this->data['year']) or empty($this->data['year'])){
      $error = ts('Error in get_dephead_admin_link, no year !');
    }
    
    $form = array();
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      return $this->response('dephead_admin_link', $form, 'error', '');
    }
    
    // check if the current cid is department head of the cid, or
    // check if the current cid is administration of the cid
    if($this->lr->is_department_head[$this->data['user_cid']] or $this->lr->is_administration[$this->data['user_cid']]){

      $form = $this->lr->cache_get($this->data['user_cid'] . '_dephead_admin_link');
      if($this->data['cache'] or empty($form)){

        $form = array(
          'type' => 'link',
          'title' => $this->lr->employees[$this->data['user_cid']]['display_name'],
          'href' => '/user/' . $this->data['user_cid'] . '/leave?year=' . $this->data['year']
        );

        $this->lr->cache_set($this->data['user_cid'] . '_dephead_admin_link', $form);
      }
      return $this->response('dephead_admin_link', $form, 'link', '');
    }    
  }
  
  public function get_year_form(){
    $form['year'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Year')
    );
    
    $form['year']['year'] = $this->get_year();
        
    return $this->response('year', $form, 'form', 'year');
  }
  
  public function get_year(){
    $error = '';
    
    // cid
    if(!isset($this->data['cid']) or empty($this->data['cid'])){
      $error = ts('Error in get_year, no cid !');
    }
    
    // year
    if(!isset($this->data['year']) or empty($this->data['year'])){
      $error = ts('Error in get_year, no year !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('get_year', $form, 'error', '');
      }
    }
    
    $form = array();
    
    $options = array();
    
    for($year = 2008; $year <= date('Y')+2; $year++){
      $options['?year=' . $year . '&cid=' . $this->data['cid']] = $year;
    }
   
    $form = array
    (
      'type' => 'select',
      'title' => '',
      'options' => $options,
      'default_value' => '?year=' . $this->data['year'] . '&cid=' . $this->data['cid'],
      'attributes' => array('id' => 'year'),
    );
        
    if('form' == $this->data['type']){
      return $form;
      
    }else {
      return $this->response('year', $form, 'select', 'year');
    }
  }
  
  /*
   * Only show if current cid is administration
   */
  public function get_admin_col_links_form(){
    $error = '';
    
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_admin_col_links_form, no user_cid !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      return $this->response('admin_col_links', $form, 'error', '');
    }
    
    $form = array();
    
    // check if the current cid is administration of the cid
    if($this->lr->is_administration[$this->data['user_cid']]){
      
      
      $form = $this->lr->cache_get($this->data['user_cid'] . '_admin_col_links_form');
      if($this->data['cache'] or empty($form)){

        $form['admin_col_links'] = array(
          'type' => 'fieldset',
          'title' => ts('Colleagues')
        );

        $form['admin_col_links']['admin_col_links'] = $this->get_admin_col_links();
        
        $this->lr->cache_set($this->data['user_cid'] . '_admin_col_links_form', $form);
      }
    }
    return $this->response('admin_col_links', $form, 'form', '');
  }
  
  /*
   * Only show if current cid is administration
   */
  public function get_admin_col_links(){ 
    $error = '';
    
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_admin_col_links, no user_cid !');
    }
    
    // year
    if(!isset($this->data['year']) or empty($this->data['year'])){
      $error = ts('Error in get_admin_col_links, no year !');
    }
    
    if(!empty($error)){
      $form = array(
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('admin_col_links', $form, 'error', '');
      }
    }
    
    // check if the current cid is administration of the cid
    if($this->lr->is_administration[$this->data['user_cid']]){
      $form = array(
        'title' => '',
        'type' => 'ul',
        'prefix' => '<div id="colleagues">',
        'suffix' => '</div>',
        'attributes' => array(),
        'items' => array()
      );
            
      $colleages = array();
      foreach($this->lr->administration_colleages_ids[$this->data['user_cid']] as $did => $department){
        foreach($department['employees'] as $cid => $employee){
          $colleages[htmlentities($employee['display_name'])] = $employee;
        }
      }

      ksort($colleages);    
      foreach($colleages as $display_name => $employee){
        $links = array(
          'type' => 'link',
          'title' => ts($employee['display_name']),
          'href' => '?year=' . $this->data['year'] . '&cid=' . $employee['id']
        );

        $this->assign('form', $links);
        $this->assign('type', 'link');

        $form['items'][] = array('data' => self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl'));
      }
    }
    
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('admin_col_links', $form, 'ul', '');
    }
  }
  
  /*
   * Only show if current cid is department head
   */
  public function get_dephead_col_links_form(){
    $error = '';
    
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_dephead_col_links_form, no user_cid !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      return $this->response('dephead_col_links', $form, 'error', '');
    }
    
    $form = array();
    
    // check if the current cid is department head
    if($this->lr->is_department_head[$this->data['user_cid']]){
      $form = $this->lr->cache_get($this->data['user_cid'] . '_dephead_col_links_form');
      if($this->data['cache'] or empty($form)){
        $form['dephead_col_links'] = array
        (
          'type' => 'fieldset',
          'title' => ts('Colleagues')
        );

        $form['dephead_col_links']['dephead_col_links'] = $this->get_dephead_col_links(); 
        $this->lr->cache_set($this->data['user_cid'] . '_dephead_col_links_form', $form);
      }
    }
    
    return $this->response('dephead_col_links', $form, 'form', '');
  }
  
  /*
   * Only show if current cid is department head
   */
  public function get_dephead_col_links(){
    $error = '';
    
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_dephead_col_links, no user_cid !');
    }
    
    // year
    if(!isset($this->data['year']) or empty($this->data['year'])){
      $error = ts('Error in get_dephead_col_links, no year !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('dephead_col_links', $form, 'error', '');
      }
    }
    
    $form = array();
    
    // check if the current cid is department head
    if($this->lr->is_department_head[$this->data['user_cid']]){
      $form = $this->lr->cache_get($this->data['user_cid'] . '_dephead_col_links');
      if($this->data['cache'] or empty($form)){
        $form = array(
          'title' => '',
          'type' => 'ul',
          'prefix' => '<div id="colleagues">',
          'suffix' => '</div>',
          'attributes' => array(),
          'items' => array()
        );

        $colleages = array();
        foreach($this->lr->department_heads_colleages_ids[$this->data['user_cid']] as $aid => $administration){
          foreach($administration['employees'] as $cid => $employee){
            $colleages[htmlentities($employee['display_name'])] = $employee;
          }
        }

        ksort($colleages);     
        foreach($colleages as $display_name => $employee){
          $links = array
          (
            'type' => 'link',
            'title' => ts($employee['display_name']),
            'href' => '?year=' . $this->data['year'] . '&cid=' . $employee['id']
          );

          $this->assign('form', $links);
          $this->assign('type', 'link');

          $form['items'][] = array('data' => self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl'));
        }
        $this->lr->cache_set($this->data['user_cid'] . '_dephead_col_links', $form);
      }
    }
    
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('dephead_col_links', $form, 'ul', '');
    }
  }
  
  /*
   * Only show if current cid is department head
   */
  public function get_dephead_request_form(){
    $error = '';
    
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_dephead_request_form, no user_cid !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      return $this->response('dephead_request', $form, 'error', '');
    }
    
    $form = array();
    
    if($this->lr->is_department_head[$this->data['user_cid']]){
      $form = $this->lr->cache_get($this->data['user_cid'] . '_' . $this->data['year'] . '_dephead_request_form');
      if($this->data['cache'] or empty($form)){
        $form['request_colleague_didh'] = array
        (
          'type' => 'fieldset',
          'title' => ts('Request Colleagues')
        );

        $form['request_colleague_didh']['request_colleague_didh'] = $this->get_dephead_request();
        
        $this->lr->cache_set($this->data['user_cid'] . '_' . $this->data['year'] . '_dephead_request_form', $form);
      }
    }   
    
    return $this->response('dephead_request', $form, 'form', 'department_head_request');
  }
  
  /*
   * Only show if current cid is department head
   */
  public function get_dephead_request(){  
    $error = '';
        
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_dephead_request, no user_cid !');
    }
    
    // year
    if(!isset($this->data['year']) or empty($this->data['year'])){
      $error = ts('Error in get_dephead_request, no year !');
    }
        
    // user_id
    if(!isset($this->data['user_id']) or empty($this->data['user_id'])){
      $error = ts('Error in get_dephead_request, no user_id !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('dephead_request', $form, 'error', '');
      }
    }
    
    $form = array();
    
    if($this->lr->is_department_head[$this->data['user_cid']]){
      $ths = array('Display name','From date', 'To date', 'Date', 'Duration', 'Type', 'Reason', 'Status', 'Operations', 'iCal');

      $header = array();
      foreach($ths as $key => $th){
        $header[] = array('data' => ts($th));
      }

      /*$colleages = array();
      foreach($this->lr->department_heads_colleages_ids[$this->data['user_cid']] as $did => $department){
        foreach($department['employees'] as $cid => $employee){
          $colleages[htmlentities($employee['display_name'])] = $employee;
        }
      }

      $cids = array();      
      ksort($colleages);
      foreach($colleages as $display_name => $employee){
        $cids[] = $employee['id'];
      }      
            
      $lr = new leaveregistration($this->data['error_platform'], $this->data['error_id'] . ': get_dephead_request');
      $lr->set_fields();
      $lr->set_contacts($cids);
      $lr->set_data($this->data['years'], $this->data['months']);*/
            
      $rows = array();
      foreach($this->lr_deph_col->request as $activity_id => $request){

        if('request' == $request['status']){

          if( ($this->data['year'] . '-01-01' <= $request['from_date'] and $this->data['year'] . '-31-31' >= $request['from_date']) 
            or ($this->data['year'] . '-01-01' <= $request['to_date'] and $this->data['year'] . '-31-31' >= $request['to_date'])
            or ($this->data['year'] . '-01-01' >= $request['from_date'] and $this->data['year'] . '-31-31' <= $request['to_date'])   
            or ($this->data['year'] . '-01-01' <= $request['date'] and $this->data['year'] . '-31-31' >= $request['date'])
            ){          

            $datas = array();

            $datas[] = array('data' => $this->lr_deph_col->employees[$request['cid']]['display_name'], 'class' => $request['status']);

            switch($request['leave_type'])
            {
              case 'doctor_visit':
              case 'time_for_time':
              case 'normal_leave_less_one_day':
              case 'sick_less_one_day':
                $datas[] = array('data' => '', 'class' => $request['status']);
                $datas[] = array('data' => '', '#attributes' => array('class' => array($request['status'])));
                $datas[] = array('data' => strftime("%d-%m-%Y", strtotime($request['date'])), 'class' => $request['status']);
                $datas[] = array('data' => $request['duration'], 'class' => $request['status']);
              break;

              default:
                $datas[] = array('data' => strftime("%d-%m-%Y", strtotime($request['from_date'])), 'class' => $request['status']);
                $datas[] = array('data' => strftime("%d-%m-%Y", strtotime($request['to_date'])), 'class' => $request['status']);
                $datas[] = array('data' => '', 'class' => $request['status']);
                $datas[] = array('data' => '', 'class' => $request['status']);
            }

            $datas[] = array('data' => ts($this->lr->option_groups['leave_request_leave_type']['options'][$request['leave_type']]), 'class' => $request['status']);
            $datas[] = array('data' => ts($this->lr->option_groups['leave_request_status']['options'][$request['status']]), 'class' => $request['status']);

            if('' == $request['reason']){
              $datas[] = array('data' => '&nbsp', 'class' => $request['status']);
            }else {
              $datas[] = array('data' => '&nbsp', 'class' => $request['status'] . ', reason', 'rel' => substr($request['reason'], 0, 160));
            }

            $operations = array
            (
              'title' => '',
              'type' => 'ul',
              'attributes' => array(),
              'items' => array()
            );

            $operations['items'][] = array('data' => '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $request['id'] . '/view/?year=' . $this->data['year'] . '&cid=' . $request['cid'] . '">' . ts('view') . '</a>' , 'class' => 'view');
            $operations['items'][] = array('data' => '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $request['id'] . '/request/?year=' . $this->data['year'] . '&cid=' . $request['cid'] . '">' . ts('change') . '</a>' , 'class' => 'change');
            $operations['items'][] = array('data' => '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $request['id'] . '/delete/?year=' . $this->data['year'] . '&cid=' . $request['cid'] . '">' . ts('delete') . '</a>' , 'class' => 'delete');

            $this->assign('form', $operations);
            $this->assign('type', 'ul');

            $datas[] = array('data' => self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl'), 'class' => $request['status']);

            // iCal
            if('approved' == $request['status'] and 'time_for_time' != $request['leave_type']){
              $datas[] = array('data' => '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $request['id'] . '/ical/?year=' . $this->data['year'] . '&cid=' . $request['cid'] . '" target="_blank">' . ts('iCal') . '</a>', 'class' => 'ical');
            }

            $rows[] = array
            (
              'data' => $datas
            );
          }
        }
      }

      $form['request_colleague_didh'] = array
      (
        'type' => 'table',
        'header' => $header,
        'rows' => $rows,
        'attributes' => array('id' => 'request_colleague_didh', 'class' => 'request '),
        'caption' => '',
        'colgroups' => array(),
        'sticky' => false,
        'empty' => ''
      );
    }
    
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('dephead_request', $form, 'table', 'department_head_request');
    }
  }
  
  public function get_display_name(){
    $error = '';
    
    // cid
    if(!isset($this->data['cid']) or empty($this->data['cid'])){
      $error = ts('Error in get_display_name, no cid !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      return $this->response('display_name', $form, 'error', '');
    }
    
    return '<h1>' . $this->lr->employees[$this->data['cid']]['display_name'] . '</h1>';
  }
  
  public function get_request_form(){
    
    $form = $this->lr->cache_get($this->data['cid'] . '_' . $this->data['year'] . '_request_form');
    if($this->data['cache'] or empty($form)){
      $form['request'] = array
      (
        'type' => 'fieldset',
        'title' => ts('Request')
      );
      
      $form['request']['request'] = $this->get_request();
      
      $this->lr->cache_set($this->data['cid'] . '_' . $this->data['year'] . '_request_form', $form);
    }
    return $this->response('request', $form, 'form', 'request');
  }
  
  public function get_request(){
    $error = '';
    
    // user_id
    if(!isset($this->data['user_id']) or empty($this->data['user_id'])){
      $error = ts('Error in get_request, no user_id !');
    }
    
    // year
    if(!isset($this->data['year']) or empty($this->data['year'])){
      $error = ts('Error in get_request, no year !');
    }
    
    // cid
    if(!isset($this->data['cid']) or empty($this->data['cid'])){
      $error = ts('Error in get_request, no cid !');
    }
    
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_request, no user_cid !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('request', $form, 'error', '');
      }
    }
        
    $form['add'] = array
    (
      'type' => 'link',
      'title' => ts('Request'),
      'href' => '/user/' . $this->data['user_id'] . '/leave/request/0/request/?year=' . $this->data['year'] . '&cid=' . $this->data['cid']
    );
    
    $ths = array('From date', 'To date', 'Date', 'Duration', 'Type', 'Status', 'Reason', 'Operations', 'iCal');
    
    $header = array();
    foreach($ths as $key => $th){
      $header[] = array('data' => ts($th));
    }
        
    $rows = array();    
    foreach($this->lr->request as $rid => $request){      
      if( (($this->data['year'] . '-01-01' <= $request['from_date'] and $this->data['year'] . '-31-31' >= $request['from_date']) 
        or ($this->data['year'] . '-01-01' <= $request['to_date'] and $this->data['year'] . '-31-31' >= $request['to_date'])
        or ($this->data['year'] . '-01-01' >= $request['from_date'] and $this->data['year'] . '-31-31' <= $request['to_date'])
        or ($this->data['year'] . '-01-01' <= $request['date'] and $this->data['year'] . '-31-31' >= $request['date']))
        and $this->data['cid'] == $request['cid']
        ){          

        $datas = array();
        
        switch($request['leave_type'])
        {
          case 'doctor_visit':
          case 'time_for_time':
          case 'normal_leave_less_one_day':
          case 'sick_less_one_day':
            $datas[] = array('data' => '', 'class' => $request['status']);
            $datas[] = array('data' => '', '#attributes' => array('class' => array($request['status'])));
            $datas[] = array('data' => strftime("%d-%m-%Y", strtotime($request['date'])), 'class' => $request['status']);
            $datas[] = array('data' => $request['duration'], 'class' => $request['status']);
          break;

          default:
            $datas[] = array('data' => strftime("%d-%m-%Y", strtotime($request['from_date'])), 'class' => $request['status']);
            $datas[] = array('data' => strftime("%d-%m-%Y", strtotime($request['to_date'])), 'class' => $request['status']);
            $datas[] = array('data' => '', 'class' => $request['status']);
            $datas[] = array('data' => '', 'class' => $request['status']);
        }
        
        $datas[] = array('data' => ts($this->lr->option_groups['leave_request_leave_type']['options'][$request['leave_type']]), 'class' => $request['status']);
        $datas[] = array('data' => ts($this->lr->option_groups['leave_request_status']['options'][$request['status']]), 'class' => $request['status']);

        if('' == $request['reason']){
          $datas[] = array('data' => '&nbsp', 'class' => $request['status']);
        }else {
          $datas[] = array('data' => '&nbsp', 'class' => $request['status'] . ', reason', 'rel' => substr($request['reason'], 0, 160));
        }

        $operations = array
        (
          'title' => '',
          'type' => 'ul',
          'attributes' => array(),
          'items' => array()
        );

        // if is department head
        if($this->lr->is_department_head[$this->data['user_cid']]){
          $operations['items'][] = array('data' => '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $request['id'] . '/view/?year=' . $this->data['year'] . '&cid=' . $this->data['cid'] . '">' . ts('view') . '</a>' , 'class' => 'view');
          $operations['items'][] = array('data' => '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $request['id'] . '/request/?year=' . $this->data['year'] . '&cid=' . $this->data['cid'] . '">' . ts('change') . '</a>' , 'class' => 'change');
          $operations['items'][] = array('data' => '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $request['id'] . '/delete/?year=' . $this->data['year'] . '&cid=' . $this->data['cid'] . '">' . ts('delete') . '</a>' , 'class' => 'delete');

        }else {
          $operations['items'][] = array('data' => '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $request['id'] . '/view/?year=' . $this->data['year'] . '&cid=' . $this->data['cid'] . '">' . ts('view') . '</a>' , 'class' => 'view');

          // else 
          switch($request['status'])
          {
            case 'request':
            case 'rejected':
              $operations['items'][] = array('data' => '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $request['id'] . '/request/?year=' . $this->data['year'] . '&cid=' . $this->data['cid'] . '">' . ts('change') . '</a>' , 'class' => 'change');
              $operations['items'][] = array('data' => '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $request['id'] . '/delete/?year=' . $this->data['year'] . '&cid=' . $this->data['cid'] . '">' . ts('delete') . '</a>' , 'class' => 'delete');
              break;

            /*case 'in_treatment':
            case 'approved':
              $operations['items'][] = array('data' => '<a href="/user/' . $this->data['user_id'] . '/leave/' . $request['id'] . '/delete/?year=' . $this->data['year'] . '&cid=' . $this->data['cid'] . '">' . ts('delete') . '</a>' , 'class' => 'delete');
              break;*/

            default:
              $operations['items'][] = array('data' => '');
          }
        }

        $this->assign('form', $operations);
        $this->assign('type', 'ul');

        $datas[] = array('data' => self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl'), 'class' => $request['status']);

        // iCal
        if('approved' == $request['status'] and 'time_for_time' != $request['leave_type']){
          $datas[] = array('data' => '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $request['id'] . '/ical/?year=' . $this->data['year'] . '&cid=' . $request['cid'] . '" target="_blank">' . ts('iCal') . '</a>', 'class' => 'ical');
        }

        $rows[] = array
        (
          'data' => $datas
        );
      }
      
    }
    
    $form['request'] = array
    (
      'type' => 'table',
      'header' => $header,
      'rows' => $rows,
      'attributes' => array('id' => 'request', 'class' => 'request'),
      'caption' => '',
      'colgroups' => array(),
      'sticky' => false,
      'empty' => ''
    );
    
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('request', $form, 'table', 'request');
    }
  }
  
  public function get_legend_form(){
    $form = $this->lr->cache_get($this->data['cid'] . '_' . $this->data['year'] . '_legend_form');
    if($this->data['cache'] or empty($form)){
      $form['legend'] = array
      (
        'type' => 'fieldset',
        'title' => ts('Legend')
      );

      $form['legend']['legende'] = $this->get_legend();
      $this->lr->cache_set($this->data['cid'] . '_' . $this->data['year'] . '_legend_form', $form);
    }
    
    return $this->response('legend', $form, 'form', '');
  }
  
  public function get_legend(){    
    $form = array();
    $form[0] = array
    (
      'title' => '',
      'type' => 'ul',
      'prefix' => '<div id="leave_legend_0" class="legend_leave_type">',
      'suffix' => '</div>',
      'attributes' => array(),
      'items' => array()
    );
    
    $form[1] = array
    (
      'title' => '',
      'type' => 'ul',
      'prefix' => '<div id="leave_legend_1" class="legend_leave_type">',
      'suffix' => '</div>',
      'attributes' => array(),
      'items' => array()
    );
    
    $form[2] = array
    (
      'title' => '',
      'type' => 'ul',
      'prefix' => '<div id="leave_legend_2" class="legend_leave_type">',
      'suffix' => '</div>',
      'attributes' => array(),
      'items' => array()
    );
    
    $form[0]['items'][] = array('data' => '<span></span><label>' . ts('Day') . '</label>', 'class' => 'day');
    $form[0]['items'][] = array('data' => '<span></span><label>' . ts('Weekend') . '</label>', 'class' => 'weekend');
    $form[0]['items'][] = array('data' => '<span></span><label>' . ts('Holiday') . '</label>', 'class' => 'holiday');
    $form[0]['items'][] = array('data' => '<span></span><label>' . ts('Today') . '</label>', 'class' => 'today');
    $form[0]['items'][] = array('data' => '<span></span><label>' . ts('Lattice free') . '</label>', 'class' => 'ajustments');
    $form[0]['items'][] = array('data' => '<span></span><label>' . ts('Colleagues') . '</label>', 'class' => 'colleagues');
    $form[0]['items'][] = array('data' => '<span></span><label>' . ts('Request') . '</label>', 'class' => 'request');
    
    $key = 1;
    $i = 0;
    
    $leave_types = $this->lr->option_groups['leave_request_leave_type']['options'];
    
    foreach($leave_types as $leave_type => $title){
      if(6 == $i){
        $key++;
      }
      
      $form[$key]['items'][] = array('data' => '<span></span><label>' . ts($title) . '</label>', 'class' => $leave_type); 
      $i++;
    }
        
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('legend', $form, 'table', '');
    }
  }
  
  public function get_credit_form(){
    $form = $this->lr->cache_get($this->data['cid'] . '_' . $this->data['year'] . '_credit_form');
    if($this->data['cache'] or empty($form)){
      $form['credit_year'] = array
      (
        'type' => 'fieldset',
        'title' => ts('Credit')
      );

      $form['credit_year']['credit_year'] = $this->get_credit();
      $this->lr->cache_set($this->data['cid'] . '_' . $this->data['year'] . '_credit_form', $form);
    }
        
    return $this->response('credit', $form, 'form', '');
  }
  
  public function get_credit(){
    $error = '';
    
    // year
    if(!isset($this->data['year']) or empty($this->data['year'])){
      $error = ts('Error in get_credit, no year !');
    }
    
    // cid
    if(!isset($this->data['cid']) or empty($this->data['cid'])){
      $error = ts('Error in get_credit, no cid !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('credit', $form, 'error', '');
      }
    }
    
    $header = array();
    $header[] = array
    (
      'data' => ''
    );
    
    for($year = $this->data['year']-1; $year <= $this->data['year']; $year++){
      $header[] = array
      (
        'data' => $year
      );
    }
    
    $credit = array();    
    foreach($this->lr->total[$this->data['cid']] as $year => $total){
      // credit_total
      // in minutes
      if('-' == substr($total['credit_total'], 0, 1)){
        $credit_total = substr($total['credit_total_over'], 1); 
        $hours = floor(round($credit_total) / 60);
        $minutes = round($credit_total) - ($hours * 60);
        $credit[$year]['credit_total'] = '-' . $hours . ':' . sprintf("%02s", $minutes);
        
      }else {
        $hours = floor(round($total['credit_total']) / 60);
        $minutes = round($total['credit_total']) - ($hours * 60);
        $credit[$year]['credit_total'] = $hours . ':' . sprintf("%02s", $minutes);
      }
      
      // credit_total_over
      // in minutes
      if('-' == substr($total['credit_total_over'], 0, 1)){
        $credit_total_over = substr($total['credit_total_over'], 1); 
        $hours = floor(round($credit_total_over) / 60);
        $minutes = round($credit_total_over) - ($hours * 60);
        $credit[$year]['credit_total_over'] = '-' . $hours . ':' . sprintf("%02s", $minutes);
        
      }else {
        $hours = floor(round($total['credit_total_over']) / 60);
        $minutes = round($total['credit_total_over']) - ($hours * 60);
        $credit[$year]['credit_total_over'] = $hours . ':' . sprintf("%02s", $minutes);
      }
      
      // used
      // in minutes
      if('-' == substr($total['used'], 0, 1)){
        $used = substr($total['used'], 1);  
        $hours = floor(round($used) / 60);
        $minutes = round($used) - ($hours * 60);
        $credit[$year]['used'] = '-' . $hours . ':' . sprintf("%02s", $minutes);
        
      }else {
        $hours = floor(round($total['used']) / 60);
        $minutes = round($total['used']) - ($hours * 60);
        $credit[$year]['used'] = $hours . ':' . sprintf("%02s", $minutes);
      }
      
      // over
      // in minutes
      if('-' == substr($total['over'], 0, 1)){
        $over = substr($total['over'], 1);  
        $hours = floor(round($over) / 60);
        $minutes = round($over) - ($hours * 60);
        $credit[$year]['over'] = '-' . $hours . ':' . sprintf("%02s", $minutes);
      }else {
        $hours = floor(round($total['over']) / 60);
        $minutes = round($total['over']) - ($hours * 60);
        $credit[$year]['over'] = $hours . ':' . sprintf("%02s", $minutes);
      }
    }
        
    $datas = array();
    $datas[] = array('data' => ts('Credit'));    
    $datas[] = array('data' => $credit[$this->data['year']-1]['credit_total']);
    $datas[] = array('data' => $credit[$this->data['year']]['credit_total']);
    
    $rows[] = array
    (
      'data' => $datas
    );
    
    $datas = array();
    $datas[] = array('data' => ts('Credit over'));    
    $datas[] = array('data' => $credit[$this->data['year']-1]['credit_total_over']);
    $datas[] = array('data' => $credit[$this->data['year']]['credit_total_over']);
    
    $rows[] = array
    (
      'data' => $datas
    );
    
    $datas = array();
    $datas[] = array('data' => ts('Used'));
    $datas[] = array('data' => $credit[$this->data['year']-1]['used']);
    $datas[] = array('data' => $credit[$this->data['year']]['used']);
    
    $rows[] = array
    (
      'data' => $datas
    );
    
    $datas = array();
    $datas[] = array('data' => ts('Over'));
    $datas[] = array('data' => $credit[$this->data['year']-1]['over']);
    $datas[] = array('data' => $credit[$this->data['year']]['over']);
        
    $rows[] = array
    (
      'data' => $datas
    );
   
    $form = array
    (
      'type' => 'table',
      'header' => $header,
      'rows' => $rows,
      'attributes' => array('class' => 'credit_year'),
      'caption' => '',
      'colgroups' => array(),
      'sticky' => false,
      'empty' => ''
    );
    
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('credit', $form, 'table', '');
    }
  }
  
  public function get_months_form(){
    if(!$this->lr->is_department_head[$this->data['cid']]){
      $form['months'] = array
      (
        'type' => 'fieldset',
        'title' => ts('Months')
      );

      $form['months']['months'] = $this->get_months();
    }
    
    return $this->response('months', $form, 'form', 'months');
  }
  
  public function get_months(){
    $error = '';
    
    // month
    if(!isset($this->data['month']) or empty($this->data['month'])){
      $error = ts('Error in get_months, no month !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('months', $form, 'error', '');
      }
    }
    
    $options = array('all' => ts('all'), '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12');
   
    $form = array
    (
      'type' => 'select',
      'title' => '',
      'options' => $options,
      'default_value' => $this->data['month'],
      'attributes' => array('id' => 'months'),
    );
    
    if($this->lr->is_department_head[$this->data['cid']]){
      $form['default_value'] = 'select';
    }
    
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('months', $form, 'select', 'months');
    }
  }
  
  public function get_dephead_months_form(){
    $error = '';
    
    // cid
    if(!isset($this->data['cid']) or empty($this->data['cid'])){
      $error = ts('Error in get_dephead_months_form, no cid !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      return $this->response('dephead_months', $form, 'error', '');
    }
    
    $form = array();
    
    if($this->lr->is_department_head[$this->data['cid']]){
      $form['dephead_months'] = array
      (
        'type' => 'fieldset',
        'title' => ts('Months')
      );

      $form['dephead_months']['dephead_months'] = $this->get_dephead_months();
    }
        
    return $this->response('dephead_months', $form, 'form', 'dephead_months');
  }
  
  public function get_dephead_months(){
    $error = '';
    
    // cid
    if(!isset($this->data['cid']) or empty($this->data['cid'])){
      $error = ts('Error in get_dephead_months, no cid !');
    }
    
    // month
    if(!isset($this->data['month']) or empty($this->data['month'])){
      $error = ts('Error in get_dephead_months, no month !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('dephead_months', $form, 'error', '');
      }
    }
    
    $form = array();
    
    if($this->lr->is_department_head[$this->data['cid']]){
      $options = array('all' => ts('all'), '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12');
   
      $form = array
      (
        'type' => 'select',
        'title' => '',
        'options' => $options,
        'default_value' => $this->data['month'],
        'attributes' => array('id' => 'dephead_months'),
      );
    }
    
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('dephead_months', $form, 'select', 'dephead_months');
    }
  }
    
  public function get_show_colleagues_form(){
    if(!$this->lr->is_department_head[$this->data['cid']]){
      $form['show_colleagues'] = array
      (
        'type' => 'fieldset',
        'title' => ts('Show colleagues')
      );

      $form['show_colleagues']['show_colleagues'] = $this->get_show_colleagues();
    }
    return $this->response('show_colleagues', $form, 'form', 'show_colleagues');
  }
  
  public function get_show_colleagues(){
    $form = array
    (
      'type' => 'checkbox',
      'title' => ts('Show colleagues'),
      'attributes' => array('id' => 'show_colleagues'),
    ); 
    
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('show_colleagues', $form, 'checkbox', 'show_colleagues');
    }
  }
  
  public function get_dephead_show_colleagues_form(){
    $error = '';
        
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      return $this->response('dephead_show_colleagues', $form, 'error', '');
    }
    
    $form = array();
        
    if($this->lr->is_department_head[$this->data['cid']]){
      $form['dephead_show_colleagues'] = array
      (
        'type' => 'fieldset',
        'title' => ts('Show colleagues')
      );

      $form['dephead_show_colleagues']['dephead_show_colleagues'] = $this->get_dephead_show_colleagues();
    }
    
    return $this->response('dephead_show_colleagues', $form, 'form', 'dephead_show_colleagues');
  }
  
  public function get_dephead_show_colleagues(){
    $error = '';
        
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('dephead_show_colleagues', $form, 'error', '');
      }
    }
    
    $form = array();
    
    if($this->lr->is_department_head[$this->data['cid']]){
      $form = array
      (
        'type' => 'checkbox',
        'title' => ts('Show colleagues'),
        'attributes' => array('id' => 'dephead_show_colleagues'),
      );
    }
    
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('dephead_show_colleagues', $form, 'checkbox', 'dephead_show_colleagues');
    }
  }
    
  public function get_calendar_year_form(){
    if(!$this->lr->is_department_head[$this->data['cid']]){
      $form = $this->lr->cache_get($this->data['cid'] . '_' . $this->data['year'] . '_calendar_year_form');
      if($this->data['cache'] or empty($form)){
        $form['calendar_year'] = array
        (
          'type' => 'fieldset',
          'title' => ts('Calendar')
        );

        if(!$this->lr->is_department_head[$this->data['cid']]){
          $form['calendar_year']['calendar_year'] = $this->get_calendar_year();
        }else {
          $form['calendar_year']['calendar_year'] = array(
              'type' => 'text',
              'text' => ts('Choose calendar or a month above !')
          );
        }
        $this->lr->cache_set($this->data['cid'] . '_' . $this->data['year'] . '_calendar_year_form', $form);
      }
    }
    return $this->response('calendar_year', $form, 'form', 'calendar_year');
  }
  
  public function get_calendar_year(){ 
    $error = '';
        
    // cid
    if(!isset($this->data['cid']) or empty($this->data['cid'])){
      $error = ts('Error in get_calendar_year, no cid !');
    }
    
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_calendar_year, no user_cid !');
    }
    
    // year
    if(!isset($this->data['year']) or empty($this->data['year'])){
      $error = ts('Error in get_calendar_year, no year !');
    }
    
    // user_id
    if(!isset($this->data['user_id']) or empty($this->data['user_id'])){
      $error = ts('Error in get_calendar_year, no user_id !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('calendar_year', $form, 'error', '');
      }
    }
    
    $form = $this->lr->cache_get($this->data['cid'] . '_' . $this->data['year'] . '_calendar_year');
    if($this->data['cache'] or empty($form)){
      // all the colleages
      $colleages = array();
      switch ($this->lr->settings[$this->data['cid']]['show_all_colleagues']){
        case 'business':
          foreach($this->lr->business_colleages_ids[$this->data['cid']] as $bid => $business){
            foreach($business['employees'] as $cid => $employee){
              if($cid != $this->data['cid']){
                $colleages[htmlentities($employee['display_name'])] = $employee;
              }
            }
          }
          break;
        /*case 'main_business':
          foreach($this->lr->department_heads_colleages_ids[$this->data['cid']] as $did => $department){
            foreach($department['employees'] as $cid => $employee){
              if($cid != $this->data['user_cid']){
                $colleages[htmlentities($employee['display_name'])] = $employee;
              }
            }
          }
          break;*/
        default:
          foreach($this->lr->department_colleages_ids[$this->data['cid']] as $did => $department){
            foreach($department['employees'] as $cid => $employee){
              if($cid != $this->data['cid']){
                $colleages[htmlentities($employee['display_name'])] = $employee;
              }
            }
          }
      }

      // all the department heads
      if($this->lr->settings[$this->data['cid']]['show_department_head']){
        foreach ($this->lr->department_heads[$this->data['cid']] as $dhid => $department_head){
          if($dhid != $this->data['cid']){
            $colleages[htmlentities($department_head['display_name'])] = $department_head;
          }
        }
      }

      $colids = array();      
      ksort($colleages);
      foreach($colleages as $display_name => $employee){
        $colids[] = $employee['id'];
      }

      if(!empty($colids)){
        $lr = new leaveregistration($this->data['error_platform'], $this->data['error_id'] . ': get_calendar_year');
        $lr->set_fields();
        $lr->set_contacts($colids);
        $lr->set_data($this->data['years'], $this->data['months']);
      }

      $header = array();
      $header[] = array
      (
        'data' => $this->lr->employees[$this->data['cid']]['display_name']
      );

      foreach($this->lr->data[$this->data['cid']][$this->data['year']]['01'] as $day => $array){
        $header[] = array('data' => $day);
      }

      foreach($this->lr->data[$this->data['cid']][$this->data['year']] as $month => $days){
        $datas = array();

        $datas[] = array('data' => ts(date('F', strtotime($this->data['year'] . '-' . $month . '-01'))));

        foreach($days as $day => $array){        
          $class = '';
          $rel = '';
          list($data, $class, $rel) = $this->get_calendar_year_day($day, $month, $this->data['year'], $array, $lr);

          $datas[] = array
          (
            'data' => $data,
            'class' => $class,
            'rel' => rawurlencode(serialize($rel))
          );
        }

        $rows[] = array
        (
          'data' => $datas
        );
      }

      $form = array
      (
        'type' => 'table',
        'header' => $header,
        'rows' => $rows,
        'attributes' => array('id' => 'calendar_year_' . $this->data['year'], 'class' => 'calendar_year'),
        'caption' => '',
        'colgroups' => array(),
        'sticky' => false,
        'empty' => ''
      );
      
      $this->lr->cache_set($this->data['cid'] . '_' . $this->data['year'] . '_calendar_year', $form);
    }
                
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('calendar_year', $form, 'table', 'calendar_year');
    }
  }
  
  public function get_calendar_year_day($day, $month, $year, $array, $lr){
    $data = '';
    $class = '';
    $rel = array();
    
    if(date('d-m-Y') == $day . '-' . $month . '-' . $year){
      $class .= 'today ';
    }
    
    $rel['day'] = ts($array['day_week']['textual']) . ' ' . $day . '-' . $month . '-' . $year;
    
    // is weekend
    if(isset($array['day_week']['is_weekend']) and 1 == $array['day_week']['is_weekend']){
      $class .= 'is_weekend ';
    }
       
    // adjustments
    if(isset($array['adjustments']['duration']) and 0 == $array['adjustments']['duration']){
      $class .= 'adjustments ';
      $rel['adjustments'] = $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts('Lattice free');
    }
         
    /*// mom dad day
    if(isset($array['mom_dad_day']['is_mom_dad_day']) and 1 == $array['mom_dad_day']['is_mom_dad_day'] and 'approved' == $array['mom_dad_day']['status']){
      $hours = floor($array['mom_dad_day']['duration'] / 60);
      $minutes = $array['mom_dad_day']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
      
      $class .= 'is_mom_dad_day ' ;
      $class .= $array['mom_dad_day']['leave_type'] . ' ';
      
      $rel['mom_dad_day'] = $this->lr->employees[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['mom_dad_day']['leave_type']]) . ' ' . $duration;
    }*/
    
    // normal_leave status request
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'request' == $array['normal_leave']['status']){
      $class .= 'request ';
    }
    
    // normal_leave status in_treatment
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'in_treatment' == $array['normal_leave']['status']){
      $class .= 'request ';
    }
    
    // normal_leave
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'approved' == $array['normal_leave']['status']){
      $hours = floor($array['normal_leave']['duration'] / 60);
      $minutes = $array['normal_leave']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
        
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '-' . $hours;      
        
        // if is department head
        if($this->lr->is_department_head[$this->data['user_cid']]){
          $data = '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $array['normal_leave']['id'] . '/request/?year=' . $year . '&cid=' . $this->data['cid'] . '" class="change">' . $data . '</a>';
          
        }else {
          // else 
          switch($array['normal_leave']['status'])
          {
            case 'request':
            case 'rejected':
              $data = '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $array['normal_leave']['id'] . '/request/?year=' . $year . '&cid=' . $this->data['cid'] . '" class="change">' . $data . '</a>';
              break;

            case 'in_treatment':
            case 'approved':
              $data = '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $array['normal_leave']['id'] . '/view/?year=' . $year . '&cid=' . $this->data['cid'] . '" class="change">' . $data . '</a>';
              break;
          }
        }
        
        $class .= 'is_normal_leave ';
        $class .= $array['normal_leave']['leave_type'] . ' ';

        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] = '<br />' . $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['normal_leave']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['normal_leave']['leave_type']]) . ' ' . $duration;
        }
      }
    }
    
    // request status request
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'request' == $array['request']['status']){
      $class .= 'request ';
    }
    
    // request status in_treatment
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'in_treatment' == $array['request']['status']){
      $class .= 'request ';
    }
    
    // request
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'approved' == $array['request']['status']){
      $hours = floor($array['request']['duration'] / 60);
      $minutes = $array['request']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
        
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '-' . $hours;      
        
        // if is department head
        if($this->lr->is_department_head[$this->data['user_cid']]){
          $data = '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $array['request']['id'] . '/request/?year=' . $year . '&cid=' . $this->data['cid'] . '" class="change">' . $data . '</a>';
          
        }else {
          // else 
          switch($array['request']['status'])
          {
            case 'request':
            case 'rejected':
              $data = '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $array['request']['id'] . '/request/?year=' . $year . '&cid=' . $this->data['cid'] . '" class="change">' . $data . '</a>';
              break;

            case 'in_treatment':
            case 'approved':
              $data = '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $array['request']['id'] . '/view/?year=' . $year . '&cid=' . $this->data['cid'] . '" class="change">' . $data . '</a>';
              break;
          }
        }
        
        $class .= 'is_request ';
        $class .= $array['request']['leave_type'] . ' ';

        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] .= '<br />' . $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['request']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['request']['leave_type']]) . ' ' . $duration;
        }
      }
    }
        
    // is holiday
    if(isset($array['holiday']['is_holiday']) and 1 == $array['holiday']['is_holiday']){
      $class .= 'is_holiday ';
      $data = substr($array['holiday']['name'], 0, 2);
      $rel['holiday'] = ts($array['holiday']['name']);
    }
    
    // is time for time
    if(isset($array['time_for_time']['is_time_for_time']) and 1 == $array['time_for_time']['is_time_for_time'] and 'approved' == $array['time_for_time']['status']){
      $hours = floor($array['time_for_time']['duration'] / 60);
      $minutes = $array['time_for_time']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
      
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '+' . $hours;
      
        // if is department head
        if($this->lr->is_department_head[$this->data['user_cid']]){
          $data = '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $array['time_for_time']['id'] . '/request/?year=' . $year . '&cid=' . $this->data['cid'] . '" class="change">' . $data . '</a>';
          
        }else {
          // else 
          switch($array['time_for_time']['status'])
          {
            case 'request':
            case 'rejected':
              $data = '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $array['time_for_time']['id'] . '/request/?year=' . $year . '&cid=' . $this->data['cid'] . '" class="change">' . $data . '</a>';
              break;

            case 'in_treatment':
            case 'approved':
              $data = '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $array['time_for_time']['id'] . '/view/?year=' . $year . '&cid=' . $this->data['cid'] . '" class="change">' . $data . '</a>';
              break;
          }
        }
        
        $class .= 'is_time_for_time ' ;
        $class .= $array['time_for_time']['leave_type'] . ' ';

        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] .= '<br />' . $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['time_for_time']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['time_for_time']['leave_type']]) . ' ' . $duration;
        }
      }
    }

    // everyone else
    foreach($lr->employees as $cid => $employee){
      if(isset($lr->data[$cid][$year][$month][$day]['normal_leave']['is_normal_leave']) and 1 == $lr->data[$cid][$year][$month][$day]['normal_leave']['is_normal_leave'] and 'approved' == $lr->data[$cid][$year][$month][$day]['normal_leave']['status']){                
        $class .= 'colleagues ';
        $class .= str_replace(' ', '_', strtolower($lr->employees[$cid]['display_name'])) . ' ';

        $hours = floor($lr->data[$cid][$year][$month][$day]['normal_leave']['duration'] / 60);
        $minutes = $lr->data[$cid][$year][$month][$day]['normal_leave']['duration'] - ($hours *60);
        $duration = $hours . ':' . sprintf("%02s", $minutes);

        if('0:00' != $duration){ // if duration is not 0:00
          $rel[str_replace(' ', '_', strtolower($lr->employees[$cid]['display_name']))] = $lr->employees[$cid]['display_name'] . ' ' . ts($lr->option_groups['leave_request_leave_type']['options'][$lr->data[$cid][$year][$month][$day]['normal_leave']['leave_type']]) . ' ' . $duration;
        }
      }

      if(isset($lr->data[$cid][$year][$month][$day]['request']['is_request']) and 1 == $lr->data[$cid][$year][$month][$day]['request']['is_request'] and 'approved' == $lr->data[$cid][$year][$month][$day]['request']['status']){                
        $class .= 'colleagues ';
        $class .= str_replace(' ', '_', strtolower($lr->employees[$cid]['display_name'])) . ' ';

        $hours = floor($lr->data[$cid][$year][$month][$day]['request']['duration'] / 60);
        $minutes = $lr->data[$cid][$year][$month][$day]['request']['duration'] - ($hours *60);
        $duration = $hours . ':' . sprintf("%02s", $minutes);

        if('0:00' != $duration){ // if duration is not 0:00
          $rel[str_replace(' ', '_', strtolower($lr->employees[$cid]['display_name']))] = $lr->employees[$cid]['display_name'] . ' ' . ts($lr->option_groups['leave_request_leave_type']['options'][$lr->data[$cid][$year][$month][$day]['request']['leave_type']]) . ' ' . $duration;
        }
      }
    }
        
    return array($data, $class, $rel);
  }
  
  
  /*
   * Only show it if the cid is department head
   */
  public function get_dephead_calendar_year_form(){
    $error = '';
        
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_dephead_calendar_year, no user_cid !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      return $this->response('dephead_calendar_year', $form, 'error', '');
    }
    
    $form = array();
    
    if($this->lr->is_department_head[$this->data['cid']]){
      $form = $this->lr->cache_get($this->data['cid'] . '_' . $this->data['year'] . '_dephead_calendar_year_form');
      if($this->data['cache'] or empty($form)){
        $form['dephead_calendar_year'] = array
        (
          'type' => 'fieldset',
          'title' => ts('Calendar')
        );

        $form['dephead_calendar_year']['dephead_calendar_year'] = $this->get_dephead_calendar_year();
        $this->lr->cache_set($this->data['cid'] . '_' . $this->data['year'] . '_dephead_calendar_year_form', $form);
      }
    }
        
    return $this->response('dephead_calendar_year', $form, 'form', 'dephead_calendar_year');
  }
  
  public function get_dephead_calendar_year(){   
    $error = '';
    
    // cid
    if(!isset($this->data['cid']) or empty($this->data['cid'])){
      $error = ts('Error in get_dephead_calendar_year, no cid !');
    }
    
    // error_platform
    if(!isset($this->data['error_platform']) or empty($this->data['error_platform'])){
      $error = ts('Error in get_dephead_calendar_year, no error_platform !');
    }
    
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_dephead_calendar_year, no user_cid !');
    }
    
    // years
    if(!isset($this->data['years']) or empty($this->data['years'])){
      $error = ts('Error in get_dephead_calendar_year, no years !');
    }
    
    // months
    if(!isset($this->data['months'])){
      $error = ts('Error in get_dephead_calendar_year, no months !');
    }
    
    // year
    if(!isset($this->data['year']) or empty($this->data['year'])){
      $error = ts('Error in get_dephead_calendar_year, no year !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('get_dephead_calendar_year', $form, 'error', '');
      }
    }
    
    $form = $this->lr->cache_get($this->data['cid'] . '_' . $this->data['year'] . '_dephead_calendar_year');
    if($this->data['cache'] or empty($form)){
      if($this->lr->is_department_head[$this->data['cid']]){
        /*      
        // all the employees where the cid department head is
        $colleages = array();
        foreach($this->lr->department_heads_colleages_ids[$this->data['cid']] as $did => $department){
          foreach($department['employees'] as $cid => $employee){
            if($cid != $this->data['user_cid'] and $cid != $this->data['cid']){
              $colleages[htmlentities($employee['display_name'])] = $employee;
            }
          }
        }

        $colids = array();      
        ksort($colleages);
        foreach($colleages as $display_name => $employee){
          $colids[] = $employee['id'];
        }      

        //if(!empty())
        $lr = new leaveregistration($this->data['error_platform'], $this->data['error_id'] . ': get_dephead_calendar_year');
        $lr->set_fields();
        $lr->set_contacts($colids);
        $lr->set_data($this->data['years'], $this->data['months']);*/

        $header = array();
        $header[] = array
        (
          'data' => $this->lr->employees[$this->data['cid']]['display_name']
        );

        foreach($this->lr->data[$this->data['cid']][$this->data['year']]['01'] as $day => $array){
          $header[] = array('data' => $day);
        }

        foreach($this->lr->data[$this->data['cid']][$this->data['year']] as $month => $days){
          $datas = array();

          $datas[] = array('data' => ts(date('F', strtotime($this->data['year'] . '-' . $month . '-01'))));

          foreach($days as $day => $array){
            $class = '';
            $rel = '';
            list($data, $class, $rel) = $this->get_dephead_calendar_year_day($day, $month, $this->data['year'], $array, $this->lr_deph_col);

            $datas[] = array
            (
              'data' => $data,
              'class' => $class,
              'rel' => rawurlencode(serialize($rel))
            );
          }

          $rows[] = array
          (
            'data' => $datas
          );
        }

        $form = array
        (
          'type' => 'table',
          'header' => $header,
          'rows' => $rows,
          'attributes' => array('id' => 'dephead_calendar_year_' . $this->data['year'], 'class' => 'calendar_year'),
          'caption' => '',
          'colgroups' => array(),
          'sticky' => false,
          'empty' => ''
        );
      }
      $this->lr->cache_set($this->data['cid'] . '_' . $this->data['year'] . '_dephead_calendar_year', $form);
    }
    
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('dephead_calendar_year', $form, 'table', 'dephead_calendar_year');
    }
  }
  
  public function get_dephead_calendar_year_day($day, $month, $year, $array, $lr){
    $data = '';
    $class = '';
    $rel = array();

    if(date('d-m-Y') == $day . '-' . $month . '-' . $year){
      $class .= 'today ';
    }
    
    $rel['day'] = ts($array['day_week']['textual']) . ' ' . $day . '-' . $month . '-' . $year;
    
    // is weekend
    if(isset($array['day_week']['is_weekend']) and 1 == $array['day_week']['is_weekend']){
      $class .= 'is_weekend ';
    }
       
    // adjustments
    if(isset($array['adjustments']['duration']) and 0 == $array['adjustments']['duration']){
      $class .= 'adjustments ';
      $rel['adjustments'] = $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts('Lattice free');
    }
    
    // normal_leave status request
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'request' == $array['normal_leave']['status']){
      $class .= 'request ';
    }
    
    // normal_leave status in_treatment
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'in_treatment' == $array['normal_leave']['status']){
      $class .= 'request ';
    }
    
    // normal_leave
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'approved' == $array['normal_leave']['status']){
      $hours = floor($array['normal_leave']['duration'] / 60);
      $minutes = $array['normal_leave']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
        
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '-' . $hours;
        $data = '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $array['normal_leave']['id'] . '/request/?year=' . $year . '&cid=' . $this->data['cid'] . '" class="change">' . $data . '</a>';        
        
        $class .= 'is_normal_leave ';
        $class .= $array['normal_leave']['leave_type'] . ' ';

        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] = '<br />' . $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['normal_leave']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['normal_leave']['leave_type']]) . ' ' . $duration;
        }
      }
    }
    
    // request status request
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'request' == $array['request']['status']){
      $class .= 'request ';
    }
    
    // request status in_treatment
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'in_treatment' == $array['request']['status']){
      $class .= 'request ';
    }
    
    // request
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'approved' == $array['request']['status']){
      $hours = floor($array['request']['duration'] / 60);
      $minutes = $array['request']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
        
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '-' . $hours;      
        $data = '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $array['request']['id'] . '/request/?year=' . $year . '&cid=' . $this->data['cid'] . '" class="change">' . $data . '</a>';
                
        $class .= 'is_request ';
        $class .= $array['request']['leave_type'] . ' ';

        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] .= '<br />' . $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['request']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['request']['leave_type']]) . ' ' . $duration;
        }
      }
    }
        
    // is holiday
    if(isset($array['holiday']['is_holiday']) and 1 == $array['holiday']['is_holiday']){
      $class .= 'is_holiday ';
      $data = substr($array['holiday']['name'], 0, 2);
      $rel['holiday'] = ts($array['holiday']['name']);
    }
    
    // is time for time
    if(isset($array['time_for_time']['is_time_for_time']) and 1 == $array['time_for_time']['is_time_for_time'] and 'approved' == $array['time_for_time']['status']){
      $hours = floor($array['time_for_time']['duration'] / 60);
      $minutes = $array['time_for_time']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
      
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '+' . $hours;
        $data = '<a href="/user/' . $this->data['user_id'] . '/leave/request/' . $array['time_for_time']['id'] . '/request/?year=' . $year . '&cid=' . $this->data['cid'] . '" class="change">' . $data . '</a>';
                
        $class .= 'is_time_for_time ' ;
        $class .= $array['time_for_time']['leave_type'] . ' ';

        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] .= '<br />' . $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['time_for_time']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $this->lr->employees[$this->data['cid']]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['time_for_time']['leave_type']]) . ' ' . $duration;
        }
      }
    }
          
    foreach($lr->employees as $cid => $employee){
      
      if(isset($lr->data[$cid][$year][$month][$day]['normal_leave']['is_normal_leave']) and 1 == $lr->data[$cid][$year][$month][$day]['normal_leave']['is_normal_leave'] and 'approved' == $lr->data[$cid][$year][$month][$day]['normal_leave']['status']){                
        $class .= 'colleagues ';
        $class .= str_replace(' ', '_', strtolower($lr->employees[$cid]['display_name'])) . ' ';

        $hours = floor($lr->data[$cid][$year][$month][$day]['normal_leave']['duration'] / 60);
        $minutes = $lr->data[$cid][$year][$month][$day]['normal_leave']['duration'] - ($hours *60);
        $duration = $hours . ':' . sprintf("%02s", $minutes);

        if('0:00' != $duration){ // if duration is not 0:00
          $rel[str_replace(' ', '_', strtolower($lr->employees[$cid]['display_name']))] = $lr->employees[$cid]['display_name'] . ' ' . ts($lr->option_groups['leave_request_leave_type']['options'][$lr->data[$cid][$year][$month][$day]['normal_leave']['leave_type']]) . ' ' . $duration;
        }
      }

      if(isset($lr->data[$cid][$year][$month][$day]['request']['is_request']) and 1 == $lr->data[$cid][$year][$month][$day]['request']['is_request'] and 'approved' == $lr->data[$cid][$year][$month][$day]['request']['status']){                
        $class .= 'colleagues ';
        $class .= str_replace(' ', '_', strtolower($lr->employees[$cid]['display_name'])) . ' ';

        $hours = floor($lr->data[$cid][$year][$month][$day]['request']['duration'] / 60);
        $minutes = $lr->data[$cid][$year][$month][$day]['request']['duration'] - ($hours *60);
        $duration = $hours . ':' . sprintf("%02s", $minutes);

        if('0:00' != $duration){ // if duration is not 0:00
          $rel[str_replace(' ', '_', strtolower($lr->employees[$cid]['display_name']))] = $lr->employees[$cid]['display_name'] . ' ' . ts($lr->option_groups['leave_request_leave_type']['options'][$lr->data[$cid][$year][$month][$day]['request']['leave_type']]) . ' ' . $duration;
        }
      }
    }
        
    return array($data, $class, $rel);
  }

  public function get_calendar_month_form(){
    $form['calendar_month'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Calendar')
    );
    $form['calendar_month']['calendar_month'] = $this->get_calendar_month();
        
    return $this->response('calendar_month', $form, 'form', '');
  }
  
  public function get_calendar_month(){ 
    $error = '';
    
    // cid
    if(!isset($this->data['cid']) or empty($this->data['cid'])){
      $error = ts('Error in get_calendar_month, no cid !');
    }
    
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_calendar_month, no user_cid !');
    }
    
    // year
    if(!isset($this->data['year']) or empty($this->data['year'])){
      $error = ts('Error in get_calendar_month, no year !');
    }
    
    // month
    if(!isset($this->data['month']) or empty($this->data['month'])){
      $error = ts('Error in get_calendar_month, no month !');
    }
        
    // user_id
    if(!isset($this->data['user_id']) or empty($this->data['user_id'])){
      $error = ts('Error in get_calendar_month, no user_id !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('calendar_month', $form, 'error', '');
      }
    }
    
    $header = array();
    $header[] = array
    (
      'data' => ts(date('F', strtotime($this->data['year'] . '-' . $this->data['month'] . '-01')))
    );
    
    foreach($this->lr->data[$this->data['cid']][$this->data['year']][$this->data['month']] as $day => $array){
      $header[] = array('data' => $day);
    }
    
    // employee self
    $datas = array();

    $class = '';
    $rel = array();

    $datas[] = array
    (
      'data' => $this->lr->employees[$this->data['cid']]['display_name']
    );

    foreach($this->lr->data[$this->data['cid']][$this->data['year']][$this->data['month']] as $day => $array){
      $class = '';
      $rel = '';
      list($data, $class, $rel) = $this->get_calendar_month_day($this->lr->employees, $this->data['cid'], $this->data['user_cid'], $this->data['user_id'], $day, $this->data['month'], $this->data['year'], $array);

      $datas[] = array
      (
        'data' => $data,
        'class' => $class,
        'rel' => rawurlencode(serialize($rel))
      );
    }

    $rows[] = array
    (
      'data' => $datas
    );
    
    // all the colleages
    $colleages = array();
    switch ($this->lr->settings[$this->data['cid']]['show_all_colleagues']){
      case 'business':
        foreach($this->lr->business_colleages_ids[$this->data['cid']] as $bid => $business){
          foreach($business['employees'] as $cid => $employee){
            if($cid != $this->data['cid']){
              $colleages[htmlentities($employee['display_name'])] = $employee;
            }
          }
        }
        break;
      /*case 'main_business':
        foreach($this->lr->department_heads_colleages_ids[$this->data['user_cid']] as $did => $department){
          foreach($department['employees'] as $cid => $employee){
            if($cid != $this->data['user_cid']){
              $colleages[htmlentities($employee['display_name'])] = $employee;
            }
          }
        }
        break;*/
      default:
        foreach($this->lr->department_colleages_ids[$this->data['cid']] as $did => $department){
          foreach($department['employees'] as $cid => $employee){
            if($cid != $this->data['cid']){
              $colleages[htmlentities($employee['display_name'])] = $employee;
            }
          }
        }
    }

    // all the department heads
    if($this->lr->settings[$this->data['cid']]['show_department_head']){
      foreach ($this->lr->department_heads[$this->data['cid']] as $dhid => $department_head){
        if($dhid != $this->data['cid']){
          $colleages[htmlentities($department_head['display_name'])] = $department_head;
        }
      }
    }
        
    $colids = array();      
    ksort($colleages);
    foreach($colleages as $display_name => $employee){
      $colids[] = $employee['id'];
    }      

    if(!empty($colids)){
      $lrcol = new leaveregistration($this->data['error_platform'], $this->data['error_id'] . ': get_calendar_month');
      $lrcol->set_fields();
      $lrcol->set_contacts($colids);
      $lrcol->set_data($this->data['years'], $this->data['months']);
    }
    
    foreach($colids as $collid){
      $datas = array();

      $class = '';
      $rel = array();

      $datas[] = array
      (
        'data' => $lrcol->employees[$collid]['display_name']
      );

      foreach($lrcol->data[$collid][$this->data['year']][$this->data['month']] as $day => $array){
        list($data, $class, $rel) = $this->get_calendar_month_day($lrcol->employees, $collid, $this->data['user_cid'], $this->data['user_id'], $day, $this->data['month'], $this->data['year'], $array);
        
        $datas[] = array
        (
          'data' => $data,
          'class' => $class,
          'rel' => rawurlencode(serialize($rel))
        );
      }
      
      $rows[] = array
      (
        'data' => $datas
      );
    }
    
    $form = array
    (
      'type' => 'table',
      'header' => $header,
      'rows' => $rows,
      'attributes' => array('id' => 'calendar_months_' . $month, 'class' => 'calendar_months'),
      'caption' => '',
      'colgroups' => array(),
      'sticky' => false,
      'empty' => ''
    );
    
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('calendar_month', $form, 'table', 'mouseover');
    }
  }
  
  private function get_calendar_month_day($employee, $cid, $user_cid, $user_id, $day, $month, $year, $array){
    $data = '';
    $class = '';
    $rel = array();

    if(date('d-m-Y') == $day . '-' . $month . '-' . $year){
      $class .= 'today ';
    }
    
    $rel['day'] = ts($array['day_week']['textual']) . ' ' . $day . '-' . $month . '-' . $year;
    
    // is weekend
    if(isset($array['day_week']['is_weekend']) and 1 == $array['day_week']['is_weekend']){
      $class .= 'is_weekend ';
    }
       
    // adjustments
    if(isset($array['adjustments']['duration']) and 0 == $array['adjustments']['duration']){
      $class .= 'adjustments ';
      $rel['adjustments'] = $employee[$cid]['display_name'] . ' ' . ts('Lattice free');
    }
         
    /*// mom dad day
    if(isset($array['mom_dad_day']['is_mom_dad_day']) and 1 == $array['mom_dad_day']['is_mom_dad_day'] and 'approved' == $array['mom_dad_day']['status']){
      $hours = floor($array['mom_dad_day']['duration'] / 60);
      $minutes = $array['mom_dad_day']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
      
      $class .= 'is_mom_dad_day ' ;
      $class .= $array['mom_dad_day']['leave_type'] . ' ';
      
      $rel['mom_dad_day'] = $this->lr->employees[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['mom_dad_day']['leave_type']]) . ' ' . $duration;
    }*/
    
    // normal_leave
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'approved' == $array['normal_leave']['status']){
      $hours = floor($array['normal_leave']['duration'] / 60);
      $minutes = $array['normal_leave']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
        
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '-' . $hours;  
        
        // if is department head
        if($this->lr->is_department_head[$this->data['user_cid']]){
          $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['normal_leave']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
          
        }else {
          // else 
          switch($request['status'])
          {
            case 'request':
            case 'rejected':
              $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['normal_leave']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
              break;

            case 'in_treatment':
            case 'approved':
              $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['normal_leave']['id'] . '/view/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
              break;
          }
        }
        
        $class .= 'is_normal_leave ' ;
        $class .= $array['normal_leave']['leave_type'] . ' ';
        
        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] = '<br />' . $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['normal_leave']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['normal_leave']['leave_type']]) . ' ' . $duration;
        }
      }
    }
    
    // request status request
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'request' == $array['request']['status']){
      $class .= 'request ';
    }
    
    // request status request
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'in_treatment' == $array['request']['status']){
      $class .= 'request ';
    }
    
    // request
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'approved' == $array['request']['status']){
      $hours = floor($array['request']['duration'] / 60);
      $minutes = $array['request']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
        
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '-' . $hours;  
        
        // if is department head
        if($this->lr->is_department_head[$this->data['user_cid']]){
          $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['request']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
          
        }else {
          // else 
          switch($request['status'])
          {
            case 'request':
            case 'rejected':
              $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['request']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
              break;

            case 'in_treatment':
            case 'approved':
              $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['request']['id'] . '/view/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
              break;
          }
        }
        
        $class .= 'is_request ' ;
        $class .= $array['request']['leave_type'] . ' ';

        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] .= '<br />' . $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['request']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['request']['leave_type']]) . ' ' . $duration;
        }
      }
    }
    
    // normal_leave status request
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'request' == $array['normal_leave']['status']){
      $class .= 'request ';
    }
    // is holiday
    if(isset($array['holiday']['is_holiday']) and 1 == $array['holiday']['is_holiday']){
      $class .= 'is_holiday ';
      $data = substr($array['holiday']['name'], 0, 2);
      $rel['holiday'] = ts($array['holiday']['name']);
    }
    
    // is time for time
    if(isset($array['time_for_time']['is_time_for_time']) and 1 == $array['time_for_time']['is_time_for_time'] and 'approved' == $array['time_for_time']['status']){
      $hours = floor($array['time_for_time']['duration'] / 60);
      $minutes = $array['time_for_time']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
      
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '+' . $hours;
      
        // if is department head
        if($this->lr->is_department_head[$this->data['user_cid']]){
          $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['time_for_time']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
          
        }else {
          // else 
          switch($request['status'])
          {
            case 'request':
            case 'rejected':
              $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['time_for_time']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';break;
            
            case 'in_treatment':
            case 'approved':
              $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['time_for_time']['id'] . '/view/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
              break;
          }
        }
        
        $class .= 'is_time_for_time ' ;
        $class .= $array['time_for_time']['leave_type'] . ' ';
        
        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] .= '<br />' . $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['time_for_time']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['time_for_time']['leave_type']]) . ' ' . $duration;
        }
      }
    }
    return array($data, $class, $rel);
  }
  
  public function get_dephead_calendar_month_form(){
    $error = '';
    
    // cid
    if(!isset($this->data['cid']) or empty($this->data['cid'])){
      $error = ts('Error in get_dephead_calendar_month_form, no cid !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      return $this->response('dephead_calendar_month', $form, 'error', '');
    }
    
    $form = array();
    
    if($this->lr->is_department_head[$this->data['cid']]){
      $form['dephead_calendar_month'] = array
      (
        'type' => 'fieldset',
        'title' => ts('Calendar')
      );

      $form['dephead_calendar_month']['dephead_calendar_month'] = $this->get_dephead_calendar_month();
    }
    
    return $this->response('dephead_calendar_month', $form, 'form', '');
  }
  
  public function get_dephead_calendar_month(){ 
    $error = '';
    
    // cid
    if(!isset($this->data['cid']) or empty($this->data['cid'])){
      $error = ts('Error in get_dephead_calendar_month, no cid !');
    }
    
    // error_platform
    if(!isset($this->data['error_platform']) or empty($this->data['error_platform'])){
      $error = ts('Error in get_dephead_calendar_month, no error_platform !');
    }
    
    // user_cid
    if(!isset($this->data['user_cid']) or empty($this->data['user_cid'])){
      $error = ts('Error in get_dephead_calendar_month, no user_cid !');
    }
    
    // years
    if(!isset($this->data['years']) or empty($this->data['years'])){
      $error = ts('Error in get_dephead_calendar_month, no years !');
    }
    
    // months
    if(!isset($this->data['months'])){
      $error = ts('Error in get_dephead_calendar_month, no months !');
    }
      
    // year
    if(!isset($this->data['year']) or empty($this->data['year'])){
      $error = ts('Error in get_dephead_calendar_month, no year !');
    }
    
    // month
    if(!isset($this->data['month']) or empty($this->data['month'])){
      $error = ts('Error in get_dephead_calendar_month, no month !');
    }
    
    if(!empty($error)){
      $form = array
      (
        'type' => 'error',
        'error' => $error
      );
      
      if('form' == $this->data['type']){
        return $form;
      }else {
        return $this->response('dephead_calendar_month', $form, 'error', '');
      }
    }
    
    $form = array();
    
    $header = array();
    $header[] = array
    (
      'data' => ts(date('F', strtotime($this->data['year'] . '-' . $this->data['month'] . '-01')))
    );

    foreach($this->lr->data[$this->data['cid']][$this->data['year']][$this->data['month']] as $day => $array){
      $header[] = array('data' => $day);
    }
    
    // employee self
    $datas = array();

    $class = '';
    $rel = array();

    $datas[] = array
    (
      'data' => $this->lr->employees[$this->data['cid']]['display_name']
    );

    foreach($this->lr->data[$this->data['cid']][$this->data['year']][$this->data['month']] as $day => $array){
      $class = '';
      $rel = '';
      list($data, $class, $rel) = $this->get_calendar_month_day($this->lr->employees, $this->data['cid'], $this->data['user_cid'], $this->data['user_id'], $day, $this->data['month'], $this->data['year'], $array);

      $datas[] = array
      (
        'data' => $data,
        'class' => $class,
        'rel' => rawurlencode(serialize($rel))
      );
    }

    $rows[] = array
    (
      'data' => $datas
    );
    
    if($this->lr->is_department_head[$this->data['cid']]){
      /*    
      // all the employees where the cid department head is
      $colleages = array();
      foreach($this->lr->department_heads_colleages_ids[$this->data['cid']] as $did => $department){
        foreach($department['employees'] as $cid => $employee){
          if($cid != $this->data['user_cid'] and $cid != $this->data['cid']){
            $colleages[htmlentities($employee['display_name'])] = $employee;
          }
        }
      }

      $colids = array();      
      ksort($colleages);
      foreach($colleages as $display_name => $employee){
        $colids[] = $employee['id'];
      }      

      //if(!empty())
      $lr = new leaveregistration($this->data['error_platform'], $this->data['error_id'] . ': get_dephead_calendar_month');
      $lr->set_fields();
      $lr->set_contacts($colids);
      $lr->set_data($this->data['years'], $this->data['months']);*/
            
      // every cid
      foreach($this->lr_colids as $colid){
        $datas = array();

        $class = '';
        $rel = array();

        $datas[] = array
        (
          'data' => $this->lr_deph_col->employees[$colid]['display_name']
        );

        foreach($this->lr_deph_col->data[$colid][$this->data['year']][$this->data['month']] as $day => $array){
          list($data, $class, $rel) = $this->get_dephead_calendar_month_day($this->lr_deph_col->employees, $colid, $this->data['user_cid'], $user_id, $day, $this->data['month'], $this->data['year'], $array);

          $datas[] = array
          (
            'data' => $data,
            'class' => $class,
            'rel' => rawurlencode(serialize($rel))
          );
        }

        $rows[] = array
        (
          'data' => $datas
        );
        
      }
      
      $form = array
      (
        'type' => 'table',
        'header' => $header,
        'rows' => $rows,
        'attributes' => array('id' => 'calendar_months_' . $this->data['month'], 'class' => 'calendar_months'),
        'caption' => '',
        'colgroups' => array(),
        'sticky' => false,
        'empty' => ''
      );
    }
            
    if('form' == $this->data['type']){
      return $form;
    }else {
      return $this->response('dephead_calendar_month', $form, 'table', 'mouseover');
    }
  }
    
  private function get_dephead_calendar_month_day($employee, $cid, $user_cid, $user_id, $day, $month, $year, $array){
    $data = '';
    $class = '';
    $rel = array();

    if(date('d-m-Y') == $day . '-' . $month . '-' . $year){
      $class .= 'today ';
    }
    
    $rel['day'] = ts($array['day_week']['textual']) . ' ' . $day . '-' . $month . '-' . $year;
    
    // is weekend
    if(isset($array['day_week']['is_weekend']) and 1 == $array['day_week']['is_weekend']){
      $class .= 'is_weekend ';
    }
       
    // adjustments
    if(isset($array['adjustments']['duration']) and 0 == $array['adjustments']['duration']){
      $class .= 'adjustments ';
      $rel['adjustments'] = $employee[$cid]['display_name'] . ' ' . ts('Lattice free');
    }
         
    /*// mom dad day
    if(isset($array['mom_dad_day']['is_mom_dad_day']) and 1 == $array['mom_dad_day']['is_mom_dad_day'] and 'approved' == $array['mom_dad_day']['status']){
      $hours = floor($array['mom_dad_day']['duration'] / 60);
      $minutes = $array['mom_dad_day']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
      
      $class .= 'is_mom_dad_day ' ;
      $class .= $array['mom_dad_day']['leave_type'] . ' ';
      
      $rel['mom_dad_day'] = $this->lr->employees[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['mom_dad_day']['leave_type']]) . ' ' . $duration;
    }*/
    
    // normal_leave
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'approved' == $array['normal_leave']['status']){
      $hours = floor($array['normal_leave']['duration'] / 60);
      $minutes = $array['normal_leave']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
        
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '-' . $hours;  
        
        // if is department head
        if($this->lr->is_department_head[$this->data['user_cid']]){
          $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['normal_leave']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
          
        }else {
          // else 
          switch($request['status'])
          {
            case 'request':
            case 'rejected':
              $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['normal_leave']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
              break;

            /*case 'in_treatment':
            case 'approved':
              $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/' . $request['id'] . '/delete/?year=' . $year . '&cid=' . $cid . '">' . ts('delete') . '</a>' , 'class' => 'delete');
              break;*/
          }
        }
        
        $class .= 'is_normal_leave ' ;
        $class .= $array['normal_leave']['leave_type'] . ' ';
        
        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] = '<br />' . $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['normal_leave']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['normal_leave']['leave_type']]) . ' ' . $duration;
        }
      }
    }
    
    // request status request
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'request' == $array['request']['status']){
      $class .= 'request ';
    }
    
    // request status in_treatment
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'in_treatment' == $array['request']['status']){
      $class .= 'request ';
    }
    
    // request
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'approved' == $array['request']['status']){
      $hours = floor($array['request']['duration'] / 60);
      $minutes = $array['request']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
        
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '-' . $hours;  
        
        // if is department head
        if($this->lr->is_department_head[$this->data['user_cid']]){
          $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['request']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
          
        }else {
          // else 
          switch($request['status'])
          {
            case 'request':
            case 'rejected':
              $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['request']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';break;

            /*case 'in_treatment':
            case 'approved':
              $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/' . $request['id'] . '/delete/?year=' . $year . '&cid=' . $cid . '">' . ts('delete') . '</a>' , 'class' => 'delete');
              break;*/
          }
        }
        
        $class .= 'is_request ' ;
        $class .= $array['request']['leave_type'] . ' ';

        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] .= '<br />' . $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['request']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['request']['leave_type']]) . ' ' . $duration;
        }
      }
    }
    
    // normal_leave status request
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'request' == $array['normal_leave']['status']){
      $class .= 'request ';
    }
    
    // normal_leave status in_treatment
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'in_treatment' == $array['normal_leave']['status']){
      $class .= 'request ';
    }
    
    // is holiday
    if(isset($array['holiday']['is_holiday']) and 1 == $array['holiday']['is_holiday']){
      $class .= 'is_holiday ';
      $data = substr($array['holiday']['name'], 0, 2);
      $rel['holiday'] = ts($array['holiday']['name']);
    }
    
    // is time for time
    if(isset($array['time_for_time']['is_time_for_time']) and 1 == $array['time_for_time']['is_time_for_time'] and 'approved' == $array['time_for_time']['status']){
      $hours = floor($array['time_for_time']['duration'] / 60);
      $minutes = $array['time_for_time']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
      
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '+' . $hours;
      
        // if is department head
        if($this->lr->is_department_head[$this->data['user_cid']]){
          $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['time_for_time']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
          
        }else {
          // else 
          switch($request['status'])
          {
            case 'request':
            case 'rejected':
              $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['time_for_time']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';break;

            /*case 'in_treatment':
            case 'approved':
              $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/' . $request['id'] . '/delete/?year=' . $year . '&cid=' . $cid . '">' . ts('delete') . '</a>' , 'class' => 'delete');
              break;*/
          }
        }
        
        $class .= 'is_time_for_time ' ;
        $class .= $array['time_for_time']['leave_type'] . ' ';
        
        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] .= '<br />' . $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['time_for_time']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_groups['leave_request_leave_type']['options'][$array['time_for_time']['leave_type']]) . ' ' . $duration;
        }
      }
    }

    return array($data, $class, $rel);
  }
}