<?php
/**
 * Implementation of hook_civicrm_install
 * 
 * Add contact sub types, relationship types, 
 * custom groups, custom fields and option groups
 */
function leaveregistration_civicrm_install()
{
  $lr =  new leaveregistration('civicrm');
  
  // create contact_sub_types
  $contact_sub_types = $lr->__get('contact_sub_types');
  foreach($contact_sub_types as $contact_sub_type => $array){
    $params = array(
      'version' => 3,
      'sequential' => 1,
      'parent_id' => $array['parent_id'],
      'name' => $array['contact_sub_type'],
      'label' => $array['contact_sub_type']
    );
    
    $result = civicrm_api('ContactType', 'get', array('version' => 3, 'sequential' => 1, 'name' => $contact_sub_type));
    
    if(empty($result['values'])){
      $result = civicrm_api('ContactType', 'create', $params);

      if(!$result){
        CRM_Core_Session::setStatus( ts('An error occurred when creating the contact type ') .  $contact_sub_type . ts(' !'), ts('Creating contact type'), 'error');
      }else {
        CRM_Core_Session::setStatus( ts('Contact type employee created !'), ts('Creating contact type'), 'success');
      }
      
    }
  }

  // create relationship_types
  $relationship_types = $lr->__get('relationship_types');
  foreach($relationship_types as $relationship_type => $array){
    $params = array(
      'version' => 3,
      'sequential' => 1,
      'name_a_b' => $array['name_a_b'],
      'label_a_b' => $array['name_a_b'],
      'name_b_a' => $array['name_a_b'],
      'label_b_a' => $array['name_a_b'],
      'description' => $array['description'] . ' relationship.',
      'contact_type_a' => $array['contact_type_a'],
      'contact_type_b' => $array['contact_type_b'],
      'contact_sub_type_a' => $array['contact_sub_type_a'],
      'contact_sub_type_b' => $array['contact_sub_type_b'],
      'is_active' => '1'

    );
    
    $result = civicrm_api('RelationshipType', 'get', array('version' => 3, 'sequential' => 1, 'name_a_b' => $array['name_a_b']));
    
    if(empty($result['values'])){
      $result = civicrm_api('RelationshipType', 'create', $params);

      if(!$result){
        CRM_Core_Session::setStatus( ts('An error occurred when creating the relationship ') . $array['name_a_b'] . ts(' of !'), ts('Creating relationship'), 'error');
      }else {
        CRM_Core_Session::setStatus( ts('Relationship ') . $array['name_a_b'] . ts(' created !'), ts('Creating relationship'), 'success');
      }
    }
  }
  
  // activity type
  $params = array(
    'version' => 3,
    'sequential' => 1,
    'name' => 'Leave__Request',
    'label' => 'Leave - Request',
    'title' => 'Leave - Request',
    'is_active' => '1',
    'weight' => 3,
  );
  
  $activity = civicrm_api('OptionValue', 'get', array('version' => 3,'sequential' => 1, 'title' => 'Leave - Request', 'label' => 'Leave - Request'));
    
  // if don`t exists
  if(empty($activity['values'])){
    $activity = civicrm_api('ActivityType', 'create', $params);

    if(!$activity){
      CRM_Core_Session::setStatus( ts('An error occurred when creating the activity type Leave - Request !'), ts('Creating activity type'), 'error');
    }else {
      CRM_Core_Session::setStatus( ts('Activity type Leave - Request created !'), ts('Creating activity type'), 'success');
    }
  }
  
  $custom_groups = $lr->__get('custom_groups');
  $custom_groups_fields = $lr->__get('custom_groups_fields');
  $custom_fields = $lr->__get('custom_fields');
  $option_groups = $lr->__get('option_group');
    
  foreach($custom_groups as $custom_group => $array){
    // first create group with title as name
    $fgparams = array(
      'version' => 3,
      'sequential' => 1,
      'name' => $array['name'],
      'title' => $array['name'],
      'extends' => $array['extends'],
      'extends_entity_column_value' => $array['extends_entity_column_value'],
      'style' => $array['style'],
      'collapse_display' => '1',
      'is_active' => '1',
      'weight' => '1',
      'is_multiple' => $array['is_multiple']
    );
    
    // second update field with title as title
    $sgparams = array(
      'version' => 3,
      'sequential' => 1,
      'title' => $array['title'],
      'extends' => $array['extends'],
      'extends_entity_column_value' => $array['extends_entity_column_value'],
      'style' => $array['style'],
      'collapse_display' => '1',
      'is_active' => '1',
      'weight' => '1',
      'is_multiple' => $array['is_multiple']
    );
    
    if('leave_request' == $custom_group){
      $fgparams['extends_entity_column_value'] = '' . $activity['values'][0]['value'] . '';
      $sgparams['extends_entity_column_value'] = '' . $activity['values'][0]['value'] . '';
    }
         
    $cgroup = civicrm_api('CustomGroup', 'get', array('version' => 3,'sequential' => 1,'title' => $array['title']));

    // if don`t exists
    if(empty($cgroup['values'])){
      // first create group with name as title
      $cgroup = civicrm_api('CustomGroup', 'create', $fgparams);
      
      // second update group with title as title
      $sgparams['id'] = $cgroup['values'][0]['id'];
      $cgroup = civicrm_api('CustomGroup', 'create', $sgparams);
    
      if(!$cgroup){
        CRM_Core_Session::setStatus( ts('An error occurred when creating the custom group ') . $custom_group . ts(' !'), ts('Creating custom group'), 'error');
      }else {
        CRM_Core_Session::setStatus( ts('Custom group ') . $custom_group . ts(' created !'), ts('Creating custom group'), 'success');

        leaveregistration_civicrm_install_custom_fields($cgroup, $custom_group, $custom_groups, $custom_groups_fields, $custom_fields, $option_groups);
      }
    }else {
      leaveregistration_civicrm_install_custom_fields($cgroup, $custom_group, $custom_groups, $custom_groups_fields, $custom_fields, $option_groups);
    }
  }
  
  return _leaveregistration_civix_civicrm_install();
}

function leaveregistration_civicrm_install_custom_fields($cgroup, $custom_group, $custom_groups, $custom_groups_fields, $custom_fields, $option_groups)
{      
  // custom field
  $cfweight = 1;
  foreach($custom_groups_fields[$custom_group] as $custom_groups_field){

    // first create field with name as label
    $ffparams = array(
      'version' => 3,
      'sequential' => 1,
      'custom_group_id' => $cgroup['values'][0]['id'],
      'name' => $custom_fields[$custom_groups_field]['name'],
      'label' => $custom_fields[$custom_groups_field]['name'],
      'data_type' => $custom_fields[$custom_groups_field]['data_type'],
      'html_type' => $custom_fields[$custom_groups_field]['html_type'],
      'default_value' => $custom_fields[$custom_groups_field]['default_value'],
      'is_required' => $custom_fields[$custom_groups_field]['is_required'],
      'is_searchable' => '1',
      'weight' => $cfweight,
      'is_active' => '1',
      'text_length' => '255',
      'date_format' => 'Null',
      'option_group_id' => 'Null',
    );
    
    // second update field with label as label
    $sfparams = array(
      'version' => 3,
      'sequential' => 1,
      'custom_group_id' => $cgroup['values'][0]['id'],
      'label' => $custom_fields[$custom_groups_field]['label'],
      'data_type' => $custom_fields[$custom_groups_field]['data_type'],
      'html_type' => $custom_fields[$custom_groups_field]['html_type'],
      'is_required' => $custom_fields[$custom_groups_field]['is_required'],
      'is_searchable' => '1',
      'weight' => $cfweight,
      'is_active' => '1',
    );

    if(isset($custom_fields[$custom_groups_field]['help_pre']) and '' != $custom_fields[$custom_groups_field]['help_pre']){
      $ffparams['help_pre'] = $custom_fields[$custom_groups_field]['help_pre'];
      $sfparams['help_pre'] = $custom_fields[$custom_groups_field]['help_pre'];
    }
    
    if(isset($custom_fields[$custom_groups_field]['help_post']) and '' != $custom_fields[$custom_groups_field]['help_post']){
      $ffparams['help_post'] = $custom_fields[$custom_groups_field]['help_post'];
      $sfparams['help_post'] = $custom_fields[$custom_groups_field]['help_post'];
    }
    
    if('Select Date' == $custom_fields[$custom_groups_field]['html_type']){
      $ffparams['date_format'] = $custom_fields[$custom_groups_field]['date_format'];
      $ffparams['time_format'] = $custom_fields[$custom_groups_field]['time_format'];
      
      $sfparams['date_format'] = $custom_fields[$custom_groups_field]['date_format'];
      $sfparams['time_format'] = $custom_fields[$custom_groups_field]['time_format']; 
    }
    
    // option_group
    if('Select' == $custom_fields[$custom_groups_field]['html_type']){
      $params = array(
        'version' => 3,
        'sequential' => 1,
        'name' => $custom_fields[$custom_groups_field]['name'],
        'title' => $custom_fields[$custom_groups_field]['label'],
        'is_active' => '1',
      );
      
      $ogroup = civicrm_api('OptionGroup', 'get', array('version' => 3, 'sequential' => 1, 'name' => $custom_fields[$custom_groups_field]['name']));
            
      if(empty($ogroup['values'])){
        $ogroup = civicrm_api('OptionGroup', 'create', $params);
              
        if(!$ogroup){
          CRM_Core_Session::setStatus( ts('An error occurred when creating the option group ') . $custom_groups_field . ts(' !'), ts('Creating option group'), 'error');
        }else {
          CRM_Core_Session::setStatus( ts('Option group ') . $custom_groups_field . ts(' created !'), ts('Creating option group'), 'success');
          
          // custom field
          $ffparams['option_group_id'] = $ogroup['values'][0]['id'];
          $sfparams['option_group_id'] = $ogroup['values'][0]['id'];
          
          leaveregistration_civicrm_install_option_values($ogroup, $custom_groups_field, $custom_groups, $custom_groups_fields, $custom_fields, $option_groups);
        }
      }else {
        // custom field
        $fparams['option_group_id'] = $ogroup['values'][0]['id'];
        
        leaveregistration_civicrm_install_option_values($ogroup, $custom_groups_field, $custom_groups, $custom_groups_fields, $custom_fields, $option_groups);
      }

    }else if('Select Date' == $custom_fields[$custom_groups_field]['html_type']){
      $fparams['date_format'] = 'dd-mm-yy';
    }

    // custom field
    $cfield = civicrm_api('CustomField', 'get', array('version' => 3, 'sequential' => 1, 'name' => $custom_fields[$custom_groups_field]['name'], 'custom_group_id' => $cgroup['values'][0]['id']));
        
    $cfweight++;
    
    if(empty($cfield['values'])){
      // first create field with name as label
      $cfield = civicrm_api('CustomField', 'create', $ffparams);
      
      // second update field with label as label
      $sfparams['id'] = $cfield['values'][0]['id'];
      $cfield = civicrm_api('CustomField', 'create', $sfparams);
      
      if(!$cfield){
        CRM_Core_Session::setStatus( ts('An error occurred when creating the custom field ') . $custom_groups_field . ts(' !'), ts('Creating custom field'), 'error');
      }else {
        CRM_Core_Session::setStatus( ts('Custom field ') . $custom_groups_field . ts(' created !'), ts('Creating custom field'), 'success');
      }
    }
  }
}

function leaveregistration_civicrm_install_option_values($ogroup, $custom_groups_field, $custom_groups, $custom_groups_fields, $custom_fields, $option_groups)
{   
  // option value
  $ovweight = 1;
  foreach($option_groups[$custom_groups_field]['options'] as $value => $name){

    $params = array(
      'version' => 3,
      'sequential' => 1,
      'option_group_id' => $ogroup['values'][0]['id'],
      'value' => $value,
      'name' => $name,
      'weight' => $ovweight,
      'is_active' => '1'
    );
       
    $result = civicrm_api('OptionValue', 'get', array('version' => 3, 'sequential' => 1, 'value' => $value, 'name' => $name, 'option_group_id' => $ogroup['values'][0]['id']));
    
    $ovweight++;
    
    if(empty($result['values'])){
      $result = civicrm_api('OptionValue', 'create', $params);
      
      if(!$result){
        CRM_Core_Session::setStatus( ts('An error occurred when creating the option value ') . $name . ts(' !'), ts('Creating option value'), 'error');
      }else {
        CRM_Core_Session::setStatus( ts('Option value ') . $name . ts(' created !'), ts('Creating option value'), 'success');
      }
    }
  }
}
?>