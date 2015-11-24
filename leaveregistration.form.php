<?php
/**
 * Implementation of hook_civicrm_buildForm
 * 
 * Add contact sub types, relationship types, 
 * custom groups, custom fields and option groups
 */
function leaveregistration_civicrm_alterContent(  &$content, $context, $tplName, &$object )
{
  /*echo('$context: ' . $context . ' $tplName: ' . $tplName);
  print_r($object);
  */
  /*echo('<br/>||||||||');
  
  echo($content);
  echo('|||||||||<br/>');
  */

  
  /*if('page' == $context) {
    if('CRM/Activity/Page/Tab.tpl' == $tplName) {
      //if('Activiteiten' == $object->_title and 16 == $object->_action){
      if(16 == $object->_action){
 
        $cid = $object->_contactId;
        $cids[] = $cid;
        
        $year = '';
        $years = array();  
        if(isset($_GET['year']) and '' != $_GET['year']){
          $year = $_GET['year'];
          $years[] = $_GET['year']-1;
          $years[] = $_GET['year'];

        }else {
          $year = date('Y');
          $years[] = date('Y')-1;
          $years[] = date('Y');
        }
        
        $data = array
        (
          'cids' => $cids,
          'do_collids' => true,
          'do_depheadids' => false,
          'do_busids' => true,
          'years' => $years,
          'months' => array(),
          'cid' => $cid,
          'current_cid' => $cid,
          'user_id' => 0,
          'year' => $year,
          'month' => 'all',
          'error_platform' => 'civicrm',
          'type' => 'form',
          'return' => 'return',
          'elements' => array('year', 'legend_leave_type', 'credit_year', 'show_colleagues', 'calendar_year')
        );

        $lrp = new CRM_Leaveregistration_Page_LeaveRegistration();
        $form_elements = $lrp->run($data);

        if(empty($form_elements)){
          drupal_set_message( t('No form elements !'), 'error');
          CRM_Core_Session::setStatus( ts($text) . ' (' . ts('leave registration class, ') . ' ' . $function . ')', ts('leave registration class, ') . ' ' . $function, 'error');
          return $form;
        }
        
        foreach($form_elements as $name => $markup){
          $content = $content . $markup;
        }  
        
        return $content;
      }
    }
  }*/
}

/**
 * Implementation of hook_civicrm_buildForm
 * 
 * Add contact sub types, relationship types, 
 * custom groups, custom fields and option groups
 */
function leaveregistration_civicrm_buildForm( $formName, &$form ) 
{
  //echo('$formName: ' . $formName . ' $form->getAction(): ' . $form->getAction() . ' $form->getVar(\'_id\'): ' . $form->getVar('_id'));
  
  switch($formName){
    case 'CRM_Activity_Form_ActivityLinks':
      
      switch($form->getAction()){
      
        default:
          //print_r($form);
      }
      
      break;
  }
}
?>
