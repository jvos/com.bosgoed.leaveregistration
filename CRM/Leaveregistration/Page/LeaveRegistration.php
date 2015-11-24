<?php
require_once 'CRM/Core/Page.php';

class CRM_Leaveregistration_Page_LeaveRegistration extends CRM_Core_Page {
  
  private $data =  array();
  
  private $error_platform = 'civicrm';
  private $return = 'serialize';
  
  private $error = false;
  
  private $lr = null;
  private $lr_dph = null;
  
  function run($data = array()) 
  {       
    $this->data = $data;
    
    if(isset($_POST['error_platform']) and '' != $_POST['error_platform']){
      $this->data['error_platform'] = $_POST['error_platform'];
    }
  
    if(isset($_POST['return']) and '' != $_POST['return']){
      $this->data['return'] = $_POST['return'];
    }
        
    if(!isset($this->data['error_platform']) or '' == $this->data['error_platform']){
      echo('No error platform !');
      return false;
    }
    
    if(!isset($this->data['return']) or '' == $this->data['return']){
      echo('No return type !');
      return false;
    }
    
    // error handler
    $this->set_error_platform($this->data['error_platform']);
    $this->set_return($this->data['return']);
    
    // set data with post data
    foreach($_POST as $field => $value){
      if('years' == $field or 'months' == $field or 'elements' == $field){
        $this->data[$field] = unserialize($value);
      }else {
        $this->data[$field] = $value;
      }
    }
     
    // check data
    if(empty($this->data['cid'])){
      $this->set_error('No contact id !', 'run');
    }
    
    if('' == $this->data['user_cid']){
      $this->set_error('No user contact id !', 'run');
    }
    
    if('' == $this->data['user_id']){
      $this->set_error('No user id !', 'run');
    }
        
    if(empty($this->data['years'])){
      $this->set_error('No years !', 'run');
    }
        
    if('' === $this->data['year']){
      $this->set_error('No year !', 'run');
    }
    
    if('' === $this->data['month']){
      $this->set_error('No month !', 'run');
    }
    
    if($this->isset_error()){
      if('return' == $this->return){
        return false;
      }else {
        CRM_Utils_System::civiExit();
      }
    }
    
    $form = array();
    
    // employee
    $this->lr = new leaveregistration($this->data['error_platform']); 
    if($this->lr->isset_error()){
      $this->set_error('Error construct leave registration class !', 'run');
      
      if($this->isset_error()){
        if('return' == $this->return){
          return false;
        }else {
          CRM_Utils_System::civiExit();
        }
      }
    }
        
    $this->lr->set_contacts($this->data['cid'], $this->data['user_cid']);
    if($this->lr->isset_error()){
      $this->set_error('Error contacts leave registration class !', 'run');
      
      if($this->isset_error()){
        if('return' == $this->return){
          return false;
        }else {
          CRM_Utils_System::civiExit();
        }
      }
    }
    
    $this->lr->set_data($this->data['years'], $this->data['months']);
    if($this->lr->isset_error()){
      $this->set_error('Error data leave registration class !', 'run');
      
      if($this->isset_error()){
        if('return' == $this->return){
          return false;
        }else {
          CRM_Utils_System::civiExit();
        }
      }
    }
    
    if(!isset($this->lr->employees[$this->data['cid']]['department_head']['id']) or '' == $this->lr->employees[$this->data['cid']]['department_head']['id']){
      $this->set_error('Current contact has no department head !', 'run');
      
      if($this->isset_error()){
       if('return' == $this->return){
         return false;
       }else {
         CRM_Utils_System::civiExit();
       }
     }
    }
    
    // base paths
    $this->assign('base_url', CIVICRM_UF_BASEURL);
    $this->assign('extension_url', CIVICRM_UF_BASEURL . 'sites/all/modules/custom/civicrm/extensions/');
    
    // variables
    foreach($this->data as $field => $value){
      if('years' == $field or 'months' == $field or 'elements' == $field){
        $this->assign($field, serialize($value));
      }else {
        $this->assign($field, $value);
      }
    }
    
    $form = array();
    
    $this->assign('type', 'script');
    $this->assign('action', 'js');
    $form['js'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');

    $this->assign('action', 'css');
    $form['css'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');

    foreach($this->data['elements'] as $element){

      switch($element)
      {     
        case 'department_head_administration_link':
          
          if($this->lr->is_administration or $this->lr->is_department_head){
            if(empty($this->data['user_cid'])){
              $this->set_error('No user cid !', 'run, Department head and administration link');
            }
            
            if(empty($this->data['user_id'])){
              $this->set_error('No user id !', 'run, Department head and administration link');
            }
            
            if('' == $this->data['year']){
              $this->set_error('No year !', 'run, Department head and administration link');
            }
                        
            $display_name = '';
            if(isset($this->lr->employees[$this->data['user_cid']]['display_name']) and '' != $this->lr->employees[$this->data['user_cid']]['display_name']){
              $display_name = $this->lr->employees[$this->data['user_cid']]['display_name'];
            }
            
            if(isset($this->lr->department_heads[$this->data['user_cid']]['display_name']) and '' != $this->lr->department_heads[$this->data['user_cid']]['display_name']){
              $display_name = $this->lr->department_heads[$this->data['user_cid']]['display_name'];
            }
            
            if(isset($this->lr->department_head_dids[$this->data['user_cid']]['display_name']) and '' != $this->lr->department_head_dids[$this->data['user_cid']]['display_name']){
              $display_name = $this->lr->department_head_dids[$this->data['user_cid']]['display_name'];
            }
            
            if(isset($this->lr->department_head_collids[$this->data['user_cid']]['display_name']) and '' != $this->lr->department_head_collids[$this->data['user_cid']]['display_name']){
              $display_name = $this->lr->department_head_collids[$this->data['user_cid']]['display_name'];
            }
            
            if(isset($this->lr->administration_collids[$this->data['user_cid']]['display_name']) and '' == $this->lr->administration_collids[$this->data['user_cid']]['display_name']){
              $display_name = $this->lr->administration_collids[$this->data['user_cid']]['display_name'];
            }
            
            /*if('' == $display_name){
              $this->set_error('No display name !', 'run, Department head and administration link');
            }*/
          
            if($this->isset_error()){
              if('return' == $this->return){
                return false;
              }else {
                CRM_Utils_System::civiExit();
              }
            }
            
            $this->assign('form', $this->get_department_head_administration_link($display_name, $this->data['user_id'], $this->data['year']));
            $this->assign('type', 'link');
            
            $this->assign('script', '');
            $form['department_head_administration_link'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');
          }
          
          break;
        
        case 'year':
          if(empty($this->data['cid'])){
            $this->set_error('No contact id !', 'run, year');
          }
          
          if('' == $this->data['year']){
            $this->set_error('No year !', 'run, year');
          }
          
          if($this->isset_error()){
            if('return' == $this->return){
              return false;
            }else {
              CRM_Utils_System::civiExit();
            }
          }
          
          
          if('form' == $this->data['type']){
            $this->assign('form', $this->get_year_form($this->data['cid'], $this->data['year']));
            $this->assign('type', 'form');

          }else {
            $this->assign('form', $this->get_year($this->data['cid'], $this->data['year']));
            $this->assign('type', 'select');
          }

          $this->assign('script', 'redirect');
          $form['year'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');
          break;
          
        case 'administration_collids':
                                        
          if($this->lr->is_administration){
                        
            if('' == $this->data['year']){
              $this->set_error('No year !', 'run, Administration colleagues');
            }

            if($this->isset_error()){
              if('return' == $this->return){
                return false;
              }else {
                CRM_Utils_System::civiExit();
              }
            }
            
            if('form' == $this->data['type']){
              $this->assign('form', $this->get_administration_collids_form($this->data['year']));
              $this->assign('type', 'form');

            }else {
              $this->assign('form', $this->get_administration_collids($this->data['year']));
              $this->assign('type', 'ul');
            }
            
            $this->assign('script', '');
            $form['administration_collids'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');
          }
          break;
          
        case 'department_head_collids':
                                        
          if($this->lr->is_department_head){
                        
            if('' == $this->data['year']){
              $this->set_error('No year !', 'run, Department head colleagues');
            }

            if($this->isset_error()){
              if('return' == $this->return){
                return false;
              }else {
                CRM_Utils_System::civiExit();
              }
            }
            
            if('form' == $this->data['type']){
              $this->assign('form', $this->get_department_head_collids_form($this->data['year'], $this->data['user_cid']));
              $this->assign('type', 'form');

            }else {
              $this->assign('form', $this->get_department_head_collids($this->data['year'], $this->data['user_cid']));
              $this->assign('type', 'ul');
            }
            
            $this->assign('script', '');
            $form['department_head_collids'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');
          }
          break;
        
        case 'department_head_request':     
          if($this->lr->is_department_head){
            if(0 == $this->data['user_cid'] or '' == $this->data['user_cid']){
              $this->set_error('No user contact id !', 'run, departemnt head request');
            }

            if(0 == $this->data['user_id'] or '' == $this->data['user_id']){
              $this->set_error('No user id !', 'run, departemnt head request');
            }

            if('' == $this->data['year']){
              $this->set_error('No year !', 'run, departemnt head request');
            }
            
            if($this->isset_error()){
              if('return' == $this->return){
                return false;
              }else {
                CRM_Utils_System::civiExit();
              }
            }
            
            if('form' == $this->data['type']){
              $this->assign('form', $this->get_department_head_request_form($this->data['user_cid'], $this->data['user_id'], $this->data['year']));
              $this->assign('type', 'form');

            }else {
              $this->assign('form', $this->get_department_head_request($this->data['user_cid'], $this->data['user_id'], $this->data['year']));
              $this->assign('type', 'table');
            }

            $this->assign('script', 'department_head_request');
            $form['request_colleague_didh'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');
          }
          break;
          
        case 'display_name':
          if(0 == $this->data['cid'] or '' == $this->data['cid']){
            $this->set_error('No contact id !', 'run, display name');
          }
          
          if($this->isset_error()){
            if('return' == $this->return){
              return false;
            }else {
              CRM_Utils_System::civiExit();
            }
          }
          
          $form['display_name'] = $this->get_display_name($this->data['cid']);
          break;
          
        case 'request':
          if(0 == $this->data['cid'] or '' == $this->data['cid']){
            $this->set_error('No contact id !', 'run, request');
          }
          
          if(0 == $this->data['user_id'] or '' == $this->data['user_id']){
            $this->set_error('No user id !', 'run, request');
          }
          
          if('' == $this->data['year']){
            $this->set_error('No year !', 'run, request');
          }
          
          if($this->isset_error()){
            if('return' == $this->return){
              return false;
            }else {
              CRM_Utils_System::civiExit();
            }
          }
          
          if('form' == $this->data['type']){
            $this->assign('form', $this->get_request_form($this->data['cid'], $this->data['user_id'], $this->data['year']));
            $this->assign('type', 'form');

          }else {
            $this->assign('form', $this->get_request($this->data['cid'], $this->data['user_id'], $this->data['year']));
            $this->assign('type', 'table');
          }
          
          $this->assign('script', 'request');
          $form['request'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');
          break;
             
        case 'legend_leave_type':
          if('form' == $this->data['type']){
            $this->assign('form', $this->get_legend_form_leave_type());
            $this->assign('type', 'form');

          }else {
            $this->assign('form', $this->get_legend_leave_type());
            $this->assign('type', 'table');
          }

          $this->assign('script', '');
          $form['legend_leave_type'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');         
          break;
          
        case 'credit':
          if(0 == $this->data['cid'] or '' == $this->data['cid']){
            $this->set_error('No contact id !', 'run, credit year');
          }

          if('' == $this->data['year']){
            $this->set_error('No year !', 'run, credit year');
          }

          if($this->isset_error()){
            if('return' == $this->return){
              return false;
            }else {
              CRM_Utils_System::civiExit();
            }
          }
          
          if('form' == $this->data['type']){
            $this->assign('form', $this->get_credit_form($this->data['cid'], $this->data['year']));
            $this->assign('type', 'form');

          }else {
            $this->assign('form', $this->get_credit($this->data['cid'], $this->data['year']));
            $this->assign('type', 'table');
          }

          $this->assign('script', '');
          $form['credit_year'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');
          break;
          
        case 'months':
          if('' == $this->data['month']){
            $this->set_error('No month !', 'run, months');
          }

          if($this->isset_error()){
            if('return' == $this->return){
              return false;
            }else {
              CRM_Utils_System::civiExit();
            }
          }
          
          if('form' == $this->data['type']){
            $this->assign('form', $this->get_months_form($this->data['month']));
            $this->assign('type', 'form');

          }else {
            $this->assign('form', $this->get_months($this->data['month']));
            $this->assign('type', 'select');
          }

          $this->assign('script', 'load');
          $form['months'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');
          break;
        
        case 'show_colleagues':
          if('form' == $this->data['type']){
            $this->assign('form', $this->get_show_colleagues_form());
            $this->assign('type', 'form');

          }else {
            $this->assign('form', $this->get_show_colleagues());
            $this->assign('type', 'checkbox');
          }

          $this->assign('script', 'show');
          $form['show_colleagues'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');
          break;
          
        case 'calendar_year':
          if(0 == $this->data['cid'] or '' == $this->data['cid']){
            $this->set_error('No contact id !', 'run, calendar year');
          }
          
          if(0 == $this->data['user_id'] or '' == $this->data['user_id']){
            $this->set_error('No user id !', 'run, calendar year');
          }
          
          if('' == $this->data['year']){
            $this->set_error('No year !', 'run, calendar year');
          }

          if($this->isset_error()){
            if('return' == $this->return){
              return false;
            }else {
              CRM_Utils_System::civiExit();
            }
          }
          
          if('form' == $this->data['type']){
            $this->assign('form', $this->get_calendar_year_form($this->data['cid'], $this->data['user_id'], $this->data['year']));
            $this->assign('type', 'form');

          }else {
            $this->assign('form', $this->get_calendar_year($this->data['cid'], $this->data['user_id'], $this->data['year']));
            $this->assign('type', 'table');
          }

          $this->assign('script', 'mouseover');
          $form['calendar_year'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');          
          break;
         
        case 'calendar_month':
          if(0 == $this->data['cid'] or '' == $this->data['cid']){
            $this->set_error('No contact id !', 'run, calendar month');
          }

          if(0 == $this->data['user_id'] or '' == $this->data['user_id']){
            $this->set_error('No user id !', 'run, calendar year');
          }
          
          if('' == $this->data['year']){
            $this->set_error('No year !', 'run, calendar month');
          }
          
          if(empty($this->data['month'])){
            $this->set_error('No month !', 'run, calendar month');
          }

          if($this->isset_error()){
            if('return' == $this->return){
              return false;
            }else {
              CRM_Utils_System::civiExit();
            }
          }
          
          if('form' == $this->data['type']){
            $this->assign('form', $this->get_calendar_month_form($this->data['cid'], $this->data['user_id'], $this->data['year'], $this->data['month']));
            $this->assign('type', 'form');

          }else {
            $this->assign('form', $this->get_calendar_month($this->data['cid'], $this->data['user_id'], $this->data['year'], $this->data['month']));
            $this->assign('type', 'table');
          }

          $this->assign('script', 'mouseover');
          $form['calendar_month'] = self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl');          
          break;
      }
    }
    
    switch($this->data['return'])
    {
      case 'return':
        return $form;
        break;
      
      case 'echo':        
        foreach($form as $element){
          echo($element);
        }
        CRM_Utils_System::civiExit();
        break;
      
      default:
        echo(serialize($form));
        CRM_Utils_System::civiExit();
    }
  }
  
  public function set_error_platform($error_platform = 'civicrm')
  {
    $this->error_platform = $error_platform;
  }
  
  public function set_return($return = 'serialize')
  {
    $this->return = $return;
  }
  
  private function set_error($text, $function)
  {
    $this->error = true;
    
    switch($this->return)
    {
      case 'return':
        switch($this->error_platform)
        {
          case 'drupal':
            drupal_set_message( t($text) . ' (' . t('leave registration page, ') . ' ' . $function . ')', 'error');
            break;

          default:
            CRM_Core_Session::setStatus( ts($text) . ' (' . ts('leave registration page, ') . ' ' . $function . ')', ts('leave registration page, ') . ' ' . $function, 'error');
        }
        break;
      
      case 'echo':
        echo('Error: ' . ts($text) . ' (' . ts('leave registration page, ') . ' ' . $function . ')');
        break;
      
      default:
       echo(serialize(array('error' => $text . ' (' . ts('leave registration page, ') . ' ' . $function . ')')));     

    }
  }
    
  private function isset_error()
  {
    return $this->error;
  }
    
  private function get_department_head_administration_link($display_name, $user_id, $year)
  {
    $form = array
    (
      'type' => 'link',
      'title' => $display_name,
      'href' => '/user/' . $user_id . '/leave?year=' . $year
    );
    
    return $form;
  }
  
  private function get_year_form($cid, $year)
  {
    $form['year'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Year')
    );
    
    $form['year']['year'] = $this->get_year($cid, $year);
        
    return $form;
  }
  
  private function get_year($cid, $current_year)
  {
    $options = array();
    
    for($year = 2008; $year <= date('Y')+1; $year++){
      $options['?year=' . $year . '&cid=' . $cid] = $year;
    }
   
    $form = array
    (
      'type' => 'select',
      'title' => '',
      'options' => $options,
      'default_value' => '?year=' . $current_year . '&cid=' . $cid,
      'attributes' => array('id' => 'year'),
    );
    
    return $form;
  }
  
  private function get_depids_form($year)
  {
    $form['depids'] = array
    (
      'type' => 'fieldset',
      'title' => t('Colleagues')
    );
    
    $form['depids']['depids'] = $this->get_depids($year);
        
    return $form;
  }
  
  private function get_depids($year)
  {    
    $form = array
    (
      'title' => '',
      'type' => 'ul',
      'prefix' => '<div id="colleagues">',
      'suffix' => '</div>',
      'attributes' => array(),
      'items' => array()
    );
    
    foreach($this->lr->busids as $bid => $array){
      foreach($array as $cid => $cids){
        
        $links = array
        (
          'type' => 'link',
          'title' => ts($this->lr->employees[$cid]['display_name']),
          'href' => '?year=' . $year . '&cid=' . $cid
        );
        
        $this->assign('form', $links);
        $this->assign('type', 'link');

        $form['items'][] = array('data' => self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl'));
      }
    }
    
    return $form;
  }
  
  private function get_administration_collids_form($year)
  {
    $form['administration_collids'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Colleagues')
    );
    
    $form['administration_collids']['administration_collids'] = $this->get_administration_collids($year);
        
    return $form;
  }
  
  private function get_administration_collids($year)
  {    
    $form = array
    (
      'title' => '',
      'type' => 'ul',
      'prefix' => '<div id="colleagues">',
      'suffix' => '</div>',
      'attributes' => array(),
      'items' => array()
    );
        
    // sort by display name
    $administration_collids = array();
    foreach($this->lr->administration_collids as $collid => $array){
      $administration_collids[htmlentities($array['display_name'])] = $array;
      $administration_collids[htmlentities($array['display_name'])]['cid'] = $collid;
    }
    
    ksort($administration_collids);    
    foreach($administration_collids as $display_name => $array){
      $links = array
      (
        'type' => 'link',
        'title' => ts($array['display_name']),
        'href' => '?year=' . $year . '&cid=' . $array['cid']
      );

      $this->assign('form', $links);
      $this->assign('type', 'link');

      $form['items'][] = array('data' => self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl'));
    }
    
    return $form;
  }
  
  private function get_department_head_collids_form($year)
  {
    $form['department_head_collids'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Colleagues')
    );
    
    $form['department_head_collids']['department_head_collids'] = $this->get_department_head_collids($year);
        
    return $form;
  }
  
  private function get_department_head_collids($year)
  {    
    $form = array
    (
      'title' => '',
      'type' => 'ul',
      'prefix' => '<div id="colleagues">',
      'suffix' => '</div>',
      'attributes' => array(),
      'items' => array()
    );
        
    // sort by display name
    $department_head_collids = array();
    foreach($this->lr->department_head_collids as $collid => $array){
      $department_head_collids[htmlentities($array['display_name'])] = $array;
      $department_head_collids[htmlentities($array['display_name'])]['cid'] = $collid;
    }
        
    ksort($department_head_collids);    
    foreach($department_head_collids as $display_name => $array){
      $links = array
      (
        'type' => 'link',
        'title' => ts($array['display_name']),
        'href' => '?year=' . $year . '&cid=' . $array['cid']
      );

      $this->assign('form', $links);
      $this->assign('type', 'link');

      $form['items'][] = array('data' => self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl'));
    }
    
    return $form;
  }
      
  private function get_department_head_request_form($user_cid, $user_id, $year)
  {
    $form['request_colleague_didh'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Request Colleague')
    );

    $form['request_colleague_didh']['request_colleague_didh'] = $this->get_department_head_request($user_cid, $user_id, $year);
        
    return $form;
  }
  
  private function get_department_head_request($user_cid, $user_id, $year)
  {    
    $ths = array('Display name','From date', 'To date', 'Date', 'Duration', 'Type', 'Reason', 'Status', 'Operations', 'iCal');
    
    $header = array();
    foreach($ths as $key => $th){
      $header[] = array('data' => ts($th));
    }
    
    $rows = array();
    foreach($this->lr->department_head_request as $rid => $request){
     
      if($user_cid != $request['cid'] and 'request' == $request['status']){

        if( ($year . '-01-01' <= $request['from_date'] and $year . '-31-31' >= $request['from_date']) 
            or ($year . '-01-01' <= $request['to_date'] and $year . '-31-31' >= $request['to_date'])
            or ($year . '-01-01' <= $request['date'] and $year . '-31-31' >= $request['date'])
            ){          
          
          $datas = array();
          
          $datas[] = array('data' => $this->lr->department_head_collids[$request['cid']]['display_name'], 'class' => $request['status']);
          
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
          
          
          
          $datas[] = array('data' => ts($this->lr->option_group['leave_request_leave_type']['options'][$request['leave_type']]), 'class' => $request['status']);
          $datas[] = array('data' => ts($this->lr->option_group['leave_request_status']['options'][$request['status']]), 'class' => $request['status']);
          
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

          $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/request/' . $request['id'] . '/view/?year=' . $year . '&cid=' . $request['cid'] . '">' . ts('view') . '</a>' , 'class' => 'view');
          $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/request/' . $request['id'] . '/request/?year=' . $year . '&cid=' . $request['cid'] . '">' . ts('change') . '</a>' , 'class' => 'change');
          $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/request/' . $request['id'] . '/delete/?year=' . $year . '&cid=' . $request['cid'] . '">' . ts('delete') . '</a>' , 'class' => 'delete');
            
          $this->assign('form', $operations);
          $this->assign('type', 'ul');
          
          $datas[] = array('data' => self::$_template->fetch('CRM/Leaveregistration/Page/LeaveRegistration.tpl'), 'class' => $request['status']);
          
          // iCal
          if('approved' == $request['status'] and 'time_for_time' != $request['leave_type']){
            $datas[] = array('data' => '<a href="/user/' . $user_id . '/leave/request/' . $request['id'] . '/ical/?year=' . $year . '&cid=' . $request['cid'] . '" target="_blank">' . ts('iCal') . '</a>', 'class' => 'ical');
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
      'attributes' => array('id' => 'request_colleague_didh', 'class' => 'request'),
      'caption' => '',
      'colgroups' => array(),
      'sticky' => false,
      'empty' => ''
    );
    
    return $form;
  }
  
  private function get_display_name($cid)
  {
    return '<h1>' . $this->lr->employees[$cid]['display_name'] . '</h1>';
  }
  
  private function get_request_form($cid, $user_id, $year)
  {
    $form['request'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Request')
    );

    $form['request']['request'] = $this->get_request($cid, $user_id, $year);
        
    return $form;
  }
  
  private function get_request($cid, $user_id, $year)
  {
    $form['add'] = array
    (
      'type' => 'link',
      'title' => ts('Request'),
      'href' => '/user/' . $user_id . '/leave/request/0/request/?year=' . $year . '&cid=' . $cid
    );
    
    $ths = array('From date', 'To date', 'Date', 'Duration', 'Type', 'Status', 'Reason', 'Operations', 'iCal');
    
    $header = array();
    foreach($ths as $key => $th){
      $header[] = array('data' => ts($th));
    }
    
    $rows = array();    
    foreach($this->lr->request as $rid => $request){
      if($cid == $request['cid']){

        if( ($year . '-01-01' <= $request['from_date'] and $year . '-31-31' >= $request['from_date']) 
            or ($year . '-01-01' <= $request['to_date'] and $year . '-31-31' >= $request['to_date'])
            or ($year . '-01-01' <= $request['date'] and $year . '-31-31' >= $request['date'])
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
          
          $datas[] = array('data' => ts($this->lr->option_group['leave_request_leave_type']['options'][$request['leave_type']]), 'class' => $request['status']);
          $datas[] = array('data' => ts($this->lr->option_group['leave_request_status']['options'][$request['status']]), 'class' => $request['status']);
          
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
          if($this->lr->is_department_head){
            $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/request/' . $request['id'] . '/view/?year=' . $year . '&cid=' . $cid . '">' . ts('view') . '</a>' , 'class' => 'view');
            $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/request/' . $request['id'] . '/request/?year=' . $year . '&cid=' . $cid . '">' . ts('change') . '</a>' , 'class' => 'change');
            $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/request/' . $request['id'] . '/delete/?year=' . $year . '&cid=' . $cid . '">' . ts('delete') . '</a>' , 'class' => 'delete');
            
          }else {
            // else 
            switch($request['status'])
            {
              case 'request':
              case 'rejected':
                $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/request/' . $request['id'] . '/view/?year=' . $year . '&cid=' . $cid . '">' . ts('view') . '</a>' , 'class' => 'view');
                $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/request/' . $request['id'] . '/request/?year=' . $year . '&cid=' . $cid . '">' . ts('change') . '</a>' , 'class' => 'change');
                $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/request/' . $request['id'] . '/delete/?year=' . $year . '&cid=' . $cid . '">' . ts('delete') . '</a>' , 'class' => 'delete');
                break;
              
              /*case 'in_treatment':
              case 'approved':
                $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/' . $request['id'] . '/delete/?year=' . $year . '&cid=' . $cid . '">' . ts('delete') . '</a>' , 'class' => 'delete');
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
            $datas[] = array('data' => '<a href="/user/' . $user_id . '/leave/request/' . $request['id'] . '/ical/?year=' . $year . '&cid=' . $request['cid'] . '" target="_blank">' . ts('iCal') . '</a>', 'class' => 'ical');
          }
                    
          $rows[] = array
          (
            'data' => $datas
          );
        }
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
    
    return $form;
  }
  
  private function get_legend_form_leave_type()
  {
    $form['legend'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Legend')
    );
        
    $form['legend']['legende'] = $this->get_legend_leave_type();
            
    return $form;
  }
  
  private function get_legend_leave_type()
  {    
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
    
    $leave_types = $this->lr->option_group['leave_request_leave_type']['options'];
    
    foreach($leave_types as $leave_type => $title){
      if(6 == $i){
        $key++;
      }
      
      $form[$key]['items'][] = array('data' => '<span></span><label>' . ts($title) . '</label>', 'class' => $leave_type); 
      $i++;
    }
        
    return $form;
  }
  
  private function get_credit_form($cid, $year)
  {
    $form['credit_year'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Credit')
    );
    
    $form['credit_year']['credit_year'] = $this->get_credit($cid, $year);
        
    return $form;
  }
  
  private function get_credit($cid, $current_year)
  {        
    $header = array();
    $header[] = array
    (
      'data' => ''
    );
    
    for($year = $current_year-1; $year <= $current_year; $year++){
      $header[] = array
      (
        'data' => $year
      );
    }
        
    /*$header[] = array
    (
      'data' => ts('Total')
    );*/

    $credit = array();    
    foreach($this->lr->total[$cid] as $year => $total){
      /*// credit
      // in minutes
      $hours = floor(round($total['credit']) / 60);
      $minutes = round($total['credit']) - ($hours * 60);
      $credit[$year]['credit'] = $hours . ':' . sprintf("%02s", $minutes);
      */
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
    
    /*    
    // total credit
    $credit['total']['credit'] = $this->lr->total[$cid][$current_year-1]['credit'] + $this->lr->total[$cid][$current_year]['credit'];
    
    // in minutes
    $hours = floor($credit['total']['credit'] / 60);
    $minutes = $credit['total']['credit'] - ($hours * 60);
    $credit['total']['credit'] = $hours . ':' . sprintf("%02s", $minutes);
    
    // total used
    $credit['total']['used'] = $this->lr->total[$cid][$current_year-1]['used'] + $this->lr->total[$cid][$current_year]['used'];
    
    // in minutes
    $hours = floor($credit['total']['used'] / 60);
    $minutes = $credit['total']['used'] - ($hours * 60);
    $credit['total']['used'] = $hours . ':' . sprintf("%02s", $minutes);
    
    // total over
    $credit['total']['over'] = $this->lr->total[$cid][$current_year-1]['over'] + $this->lr->total[$cid][$current_year]['over'];
    
    // in minutes
    $hours = floor($credit['total']['over'] / 60);
    $minutes = $credit['total']['over'] - ($hours * 60);
    $credit['total']['over'] = $hours . ':' . sprintf("%02s", $minutes);
    */
    
    $datas = array();
    $datas[] = array('data' => ts('Credit'));    
    $datas[] = array('data' => $credit[$current_year-1]['credit_total']);
    $datas[] = array('data' => $credit[$current_year]['credit_total']);
    //$datas[] = array('data' => $credit['total']['credit']);
    
    $rows[] = array
    (
      'data' => $datas
    );
    
    $datas = array();
    $datas[] = array('data' => ts('Credit over'));    
    $datas[] = array('data' => $credit[$current_year-1]['credit_total_over']);
    $datas[] = array('data' => $credit[$current_year]['credit_total_over']);
    //$datas[] = array('data' => $credit['total']['credit']);
    
    $rows[] = array
    (
      'data' => $datas
    );
    
    $datas = array();
    $datas[] = array('data' => ts('Used'));
    $datas[] = array('data' => $credit[$current_year-1]['used']);
    $datas[] = array('data' => $credit[$current_year]['used']);
    //$datas[] = array('data' => $credit['total']['used']);
    
    $rows[] = array
    (
      'data' => $datas
    );
    
    $datas = array();
    $datas[] = array('data' => ts('Over'));
    $datas[] = array('data' => $credit[$current_year-1]['over']);
    $datas[] = array('data' => $credit[$current_year]['over']);
    //$datas[] = array('data' => $credit['total']['over']);
        
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
    
    return $form;
  }
    
  private function get_months_form($current_month)
  {
    $form['months'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Months')
    );
    
    $form['months']['months'] = $this->get_months($current_month);
        
    return $form;
  }
  
  private function get_months($current_month)
  {
    $options = array('all' => ts('all'), '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12');
   
    $form = array
    (
      'type' => 'select',
      'title' => '',
      'options' => $options,
      'default_value' => $current_month,
      'attributes' => array('id' => 'months'),
    );
    
    return $form;
  }
  
  private function get_show_colleagues_form()
  {
    $form['show_colleagues'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Show colleagues')
    );
    
    $form['show_colleagues']['show_colleagues'] = $this->get_show_colleagues();
        
    return $form;
  }
  
  private function get_show_colleagues()
  {
    $form = array
    (
      'type' => 'checkbox',
      'title' => ts('Show colleagues'),
      'attributes' => array('id' => 'show_colleagues'),
    );
    
    return $form;
  }
   
  private function get_calendar_year_form($cid, $user_id, $year)
  {
    $form['calendar_year'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Calendar')
    );
    
    $form['calendar_year']['calendar_year'] = $this->get_calendar_year($cid, $user_id, $year);
        
    return $form;
  }
  
  private function get_calendar_year($cid, $user_id, $year)
  {    
    $header = array();
    $header[] = array
    (
      'data' => $this->lr->employees[$cid]['display_name']
    );
    
    foreach($this->lr->data[$cid][$year]['01'] as $day => $array){
      $header[] = array('data' => $day);
    }
            
    foreach($this->lr->data[$cid][$year] as $month => $days){
      $datas = array();
      
      $datas[] = array('data' => ts(date('F', strtotime($year . '-' . $month . '-01'))));
      
      foreach($days as $day => $array){
        
        list($data, $class, $rel) = $this->get_calendar_year_day($cid, $user_id, $day, $month, $year, $array);
        
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
      'attributes' => array('id' => 'calendar_year_' . $year, 'class' => 'calendar_year'),
      'caption' => '',
      'colgroups' => array(),
      'sticky' => false,
      'empty' => ''
    );
            
    return $form;
  }
  
  private function get_calendar_year_day($cid, $user_id, $day, $month, $year, $array)
  {
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
      $rel['adjustments'] = $this->lr->employees[$cid]['display_name'] . ' ' . ts('Lattice free');
    }
         
    /*// mom dad day
    if(isset($array['mom_dad_day']['is_mom_dad_day']) and 1 == $array['mom_dad_day']['is_mom_dad_day'] and 'approved' == $array['mom_dad_day']['status']){
      $hours = floor($array['mom_dad_day']['duration'] / 60);
      $minutes = $array['mom_dad_day']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
      
      $class .= 'is_mom_dad_day ' ;
      $class .= $array['mom_dad_day']['leave_type'] . ' ';
      
      $rel['mom_dad_day'] = $this->lr->employees[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['mom_dad_day']['leave_type']]) . ' ' . $duration;
    }*/
    
    // normal_leave status request
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'request' == $array['normal_leave']['status']){
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
        if($this->lr->is_department_head){
          $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['normal_leave']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
          
        }else {
          // else 
          switch($array['normal_leave']['status'])
          {
            case 'request':
            case 'rejected':
              $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['normal_leave']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';break;

            /*case 'in_treatment':
            case 'approved':
              $operations['items'][] = array('data' => '<a href="/user/' . $user_id . '/leave/' . $request['id'] . '/delete/?year=' . $year . '&cid=' . $cid . '">' . ts('delete') . '</a>' , 'class' => 'delete');
              break;*/
          }
        }
        
        $class .= 'is_normal_leave ';
        $class .= $array['normal_leave']['leave_type'] . ' ';

        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] = '<br />' . $this->lr->employees[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['normal_leave']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $this->lr->employees[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['normal_leave']['leave_type']]) . ' ' . $duration;
        }
      }
    }
    
    // request status request
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'request' == $array['request']['status']){
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
        if($this->lr->is_department_head){
          $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['request']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
          
        }else {
          // else 
          switch($array['request']['status'])
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
        
        $class .= 'is_request ';
        $class .= $array['request']['leave_type'] . ' ';

        if(isset($rel['leave']) and '' != $rel['leave']){
          $rel['leave'] .= '<br />' . $this->lr->employees[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['request']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $this->lr->employees[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['request']['leave_type']]) . ' ' . $duration;
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
        if($this->lr->is_department_head){
          $data = '<a href="/user/' . $user_id . '/leave/request/' . $array['time_for_time']['id'] . '/request/?year=' . $year . '&cid=' . $cid . '" class="change">' . $data . '</a>';
          
        }else {
          // else 
          switch($array['time_for_time']['status'])
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
          $rel['leave'] .= '<br />' . $this->lr->employees[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['time_for_time']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $this->lr->employees[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['time_for_time']['leave_type']]) . ' ' . $duration;
        }
      }
    }

    // everyone in the business
    foreach($this->lr->collids as $colid => $colids){
      if($cid != $colid){
        
        if(isset($this->lr->data[$colid][$year][$month][$day]['normal_leave']['is_normal_leave']) and 1 == $this->lr->data[$colid][$year][$month][$day]['normal_leave']['is_normal_leave'] and 'approved' == $this->lr->data[$colid][$year][$month][$day]['normal_leave']['status']){                
          $class .= 'colleagues ';
          $class .= str_replace(' ', '_', strtolower($this->lr->employees[$colid]['display_name'])) . ' ';

          $hours = floor($this->lr->data[$colid][$year][$month][$day]['normal_leave']['duration'] / 60);
          $minutes = $this->lr->data[$colid][$year][$month][$day]['normal_leave']['duration'] - ($hours *60);
          $duration = $hours . ':' . sprintf("%02s", $minutes);
          
          if('0:00' != $duration){ // if duration is not 0:00
            $rel[str_replace(' ', '_', strtolower($this->lr->employees[$colid]['display_name']))] = $this->lr->employees[$colid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$this->lr->data[$colid][$year][$month][$day]['normal_leave']['leave_type']]) . ' ' . $duration;
          }
        }
        
        if(isset($this->lr->data[$colid][$year][$month][$day]['request']['is_request']) and 1 == $this->lr->data[$colid][$year][$month][$day]['request']['is_request'] and 'approved' == $this->lr->data[$colid][$year][$month][$day]['request']['status']){                
          $class .= 'colleagues ';
          $class .= str_replace(' ', '_', strtolower($this->lr->employees[$colid]['display_name'])) . ' ';

          $hours = floor($this->lr->data[$colid][$year][$month][$day]['request']['duration'] / 60);
          $minutes = $this->lr->data[$colid][$year][$month][$day]['request']['duration'] - ($hours *60);
          $duration = $hours . ':' . sprintf("%02s", $minutes);
          
          if('0:00' != $duration){ // if duration is not 0:00
            $rel[str_replace(' ', '_', strtolower($this->lr->employees[$colid]['display_name']))] = $this->lr->employees[$colid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$this->lr->data[$colid][$year][$month][$day]['request']['leave_type']]) . ' ' . $duration;
          }
        }
      } 
    }
    
    // every department head
    foreach($this->lr->dphids as $dphid => $dphids){
      if($cid != $dphid){

        if(isset($this->lr->data[$dphid][$year][$month][$day]['normal_leave']['is_request']) and 1 == $this->lr->data[$dphid][$year][$month][$day]['normal_leave']['is_request'] and 'approved' == $this->lr->data[$dphid][$year][$month][$day]['normal_leave']['status']){                
          $class .= 'colleagues ';
          $class .= str_replace(' ', '_', strtolower($this->lr->employees[$dphid]['display_name'])) . ' ';

          $hours = floor($this->lr->data[$dphid][$year][$month][$day]['normal_leave']['duration'] / 60);
          $minutes = $this->lr->data[$dphid][$year][$month][$day]['normal_leave']['duration'] - ($hours *60);
          $duration = $hours . ':' . sprintf("%02s", $minutes);
          
          if('0:00' != $duration){ // if duration is not 0:00
            $rel[str_replace(' ', '_', strtolower($this->lr->employees[$dphid]['display_name']))] = $this->lr->employees[$dphid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$this->lr->data[$dphid][$year][$month][$day]['normal_leave']['leave_type']]) . ' ' . $duration;
          }
        }
        
        if(isset($this->lr->data[$dphid][$year][$month][$day]['request']['is_request']) and 1 == $this->lr->data[$dphid][$year][$month][$day]['request']['is_request'] and 'approved' == $this->lr->data[$dphid][$year][$month][$day]['request']['status']){                
          $class .= 'colleagues ';
          $class .= str_replace(' ', '_', strtolower($this->lr->employees[$dphid]['display_name'])) . ' ';

          $hours = floor($this->lr->data[$dphid][$year][$month][$day]['request']['duration'] / 60);
          $minutes = $this->lr->data[$dphid][$year][$month][$day]['request']['duration'] - ($hours *60);
          $duration = $hours . ':' . sprintf("%02s", $minutes);
          
          if('0:00' != $duration){ // if duration is not 0:00
            $rel[str_replace(' ', '_', strtolower($this->lr->employees[$dphid]['display_name']))] = $this->lr->employees[$dphid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$this->lr->data[$dphid][$year][$month][$day]['request']['leave_type']]) . ' ' . $duration;
          }
        }
      } 
    }
    
    return array($data, $class, $rel);
  }

  private function get_calendar_month_form($cid, $user_id, $year, $month)
  {
    $form['calendar_month'] = array
    (
      'type' => 'fieldset',
      'title' => ts('Calendar')
    );

    $form['calendar_month']['calendar_month'] = $this->get_calendar_month($cid, $user_id, $year, $month);
        
    return $form;
  }
  
  private function get_calendar_month($cid, $user_id, $year, $month)
  { 
    $header = array();
    $header[] = array
    (
      'data' => ''
    );
    
    foreach($this->lr->data[$cid][$year][$month] as $day => $array){
      $header[] = array('data' => $day);
    }
    
    // everyone in the business
    foreach($this->lr->collids as $collid => $colids){
      $datas = array();

      $class = '';
      $rel = array();

      $datas[] = array
      (
        'data' => $this->lr->employees[$collid]['display_name']
      );

      foreach($this->lr->data[$collid][$year][$month] as $day => $array){
        list($data, $class, $rel) = $this->get_calendar_month_day($this->lr->employees, $collid, $user_id, $day, $month, $year, $array);

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
    
    // every department head
    foreach($this->lr->dphids as $dphid => $dphids){
      $datas = array();

      $class = '';
      $rel = array();

      $datas[] = array
      (
        'data' => $this->lr->employees[$dphid]['display_name']
      );

      foreach($this->lr->data[$dphid][$year][$month] as $day => $array){
        list($data, $class, $rel) = $this->get_calendar_month_day($this->lr->employees, $dphid, $user_id, $day, $month, $year, $array);

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
            
    return $form;
  }
  
  private function get_calendar_month_day($employee, $cid, $user_id, $day, $month, $year, $array)
  {
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
      
      $rel['mom_dad_day'] = $this->lr->employees[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['mom_dad_day']['leave_type']]) . ' ' . $duration;
    }*/
    
    // normal_leave
    if(isset($array['normal_leave']['is_normal_leave']) and 1 == $array['normal_leave']['is_normal_leave'] and 'approved' == $array['normal_leave']['status']){
      $hours = floor($array['normal_leave']['duration'] / 60);
      $minutes = $array['normal_leave']['duration'] - ($hours * 60);
      $duration = $hours . ':' . sprintf("%02s", $minutes);
        
      if('0:00' != $duration){ // if duration is not 0:00
        $data = '-' . $hours;  
        
        // if is department head
        if($this->lr->is_department_head){
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
          $rel['leave'] = '<br />' . $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['normal_leave']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['normal_leave']['leave_type']]) . ' ' . $duration;
        }
      }
    }
    
    // request status request
    if(isset($array['request']['is_request']) and 1 == $array['request']['is_request'] and 'request' == $array['request']['status']){
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
        if($this->lr->is_department_head){
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
          $rel['leave'] .= '<br />' . $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['request']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['request']['leave_type']]) . ' ' . $duration;
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
        if($this->lr->is_department_head){
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
          $rel['leave'] .= '<br />' . $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['time_for_time']['leave_type']]) . ' ' . $duration;
        }else {
          $rel['leave'] = $employee[$cid]['display_name'] . ' ' . ts($this->lr->option_group['leave_request_leave_type']['options'][$array['time_for_time']['leave_type']]) . ' ' . $duration;
        }
      }
    }

    return array($data, $class, $rel);
  }
    
  public function get_depid($cid)
  {
    return $this->lr->employees[$cid]['department_head']['id'];
  }
  
  public function is_department_head()
  {
    return $this->lr->is_department_head;
  }
  
  public function get_option_group()
  {
    $return = array();
    
    $option_groups = $this->lr->option_group;
    foreach($option_groups as $name => $option_group){
      $return[$name] = array();
      // translate option group
      foreach($option_group['options'] as $value => $label){
        $return[$name][$value] = ts($label);
      }
    }
    
    return $return;
  }
  
  public function get_option_group_by_name($option_group)
  {
    $options = $this->lr->option_group[$option_group]['options'];
    
    // translate option group
    foreach($options as $value => $label){
      $options[$value] = ts($label);
    }
    
    return $options;
  }
  
  public function get_request_by_id($id)
  {
    return $this->lr->request[$id];
  }
  
  public function get_collids_only($cid)
  {
    // filter cid from collids
    $return = array();
    foreach($this->lr->collids as $collid => $collids){
      if($collid != $cid){
        $return[$collid] = $collid;
      }
    }
    return $return;
  }
  
  public function get_dphids_only($cid)
  {
    // filter cid from dphids
    $return = array();
    foreach($this->lr->dphids as $dphid => $dphids){
      if($dphid != $cid){
        $return[$dphid] = $dphid;
      }
    }
    return $return;
  }
  
  public function show_department_head($cid)
  {
    return $this->lr->settings[$cid]['show_department_head'];
  }
}
?>