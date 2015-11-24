<?php
/**
 * Implementation of hook_civicrm_install
 * 
 * Add contact sub types, relationship types, 
 * custom groups, custom fields and option groups
 */
function leaveregistration_civicrm_install() {
  $lr =  new leaveregistration('civicrm', 'leaveregistration_civicrm_install');
  
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
        //CRM_Core_Session::setStatus( ts('Contact type employee created !'), ts('Creating contact type'), 'success');
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
        //CRM_Core_Session::setStatus( ts('Relationship ') . $array['name_a_b'] . ts(' created !'), ts('Creating relationship'), 'success');
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
      //CRM_Core_Session::setStatus( ts('Activity type Leave - Request created !'), ts('Creating activity type'), 'success');
    }
  }
  
  // custom groups
  $custom_groups = $lr->__get('custom_groups');
     
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
        return false;
      }else {
        //CRM_Core_Session::setStatus( ts('Custom group ') . $custom_group . ts(' created !'), ts('Creating custom group'), 'success');
      }
    }
    $custom_groups[$custom_group]['id'] = $cgroup['values'][0]['id'];
  }
  
  // option groups
  $option_groups = $lr->__get('option_groups');
  foreach($option_groups as $option_group => $array){
    
    $params = array(
      'version' => 3,
      'sequential' => 1,
      'name' => $array['name'],
      'title' => $array['label'],
      'is_active' => '1',
    );

    $ogroup = civicrm_api('OptionGroup', 'get', array('version' => 3, 'sequential' => 1, 'name' => $array['name']));
    
    if(empty($ogroup['values'])){
      // create option group
      $ogroup = civicrm_api('OptionGroup', 'create', $params);
      
      if(!$ogroup){
        CRM_Core_Session::setStatus( ts('An error occurred when creating the option group ') . $array['name'] . ts(' !'), ts('Creating option group'), 'error');
        return false;
      }else {
        //CRM_Core_Session::setStatus( ts('Option group ') . $array['name'] . ts(' created !'), ts('Creating option group'), 'success');
      }
    }
    $option_groups[$option_group]['id'] = $ogroup['values'][0]['id'];
  }
    
  // custom fields
  $cfweight = 1;
  $cfcur = '';
  $custom_fields = $lr->__get('custom_fields');
  foreach($custom_fields as $custom_field => $array){
    
    if($cfcur != $custom_groups[$array['custom_group_name']]['id']){
      $cfweight = 1;
    }
    
    $cfcur = $custom_groups[$array['custom_group_name']]['id'];
    
    // first create field with name as label
    $ffparams = array(
      'version' => 3,
      'sequential' => 1,
      'custom_group_id' => $custom_groups[$array['custom_group_name']]['id'],
      'name' => $array['name'],
      'label' => $array['name'],
      'data_type' => $array['data_type'],
      'html_type' => $array['html_type'],
      'default_value' => $array['default_value'],
      'is_required' => $array['is_required'],
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
      'custom_group_id' => $custom_groups[$array['custom_group_name']]['id'],
      'name' => $array['name'],
      'label' => $array['label'],
      'data_type' => $array['data_type'],
      'html_type' => $array['html_type'],
      'is_required' => $array['is_required'],
      'is_searchable' => '1',
      'weight' => $cfweight,
      'is_active' => '1',
    );

    if(isset($array['help_pre']) and '' != $array['help_pre']){
      $ffparams['help_pre'] = $array['help_pre'];
      $sfparams['help_pre'] = $array['help_pre'];
    }

    if(isset($array['help_post']) and '' != $array['help_post']){
      $ffparams['help_post'] = $array['help_post'];
      $sfparams['help_post'] = $array['help_post'];
    }

    if('Select Date' == $array['html_type']){
      $ffparams['date_format'] = $array['date_format'];
      $ffparams['time_format'] = $array['time_format'];

      $sfparams['date_format'] = $array['date_format'];
      $sfparams['time_format'] = $array['time_format']; 
    }
    
    if('Select' == $array['html_type'] or 'Multi-Select' == $array['html_type'] or 'CheckBox' == $array['html_type']){
      // custom field
      $ffparams['option_group_id'] = $option_groups[$array['option_group_name']]['id'];
      $sfparams['option_group_id'] = $option_groups[$array['option_group_name']]['id'];
      
    }else {
      $ffparams['option_group_id'] = NULL;
      $sfparams['option_group_id'] = NULL;
    }
      
    if('Select Date' == $array['html_type']){
      $fparams['date_format'] = 'dd-mm-yy';
      
    }else {
      $fparams['date_format'] = NULL;
    }
    
    // custom field
    $cfield = civicrm_api('CustomField', 'get', array('version' => 3, 'sequential' => 1, 'name' => $array['name'], 'custom_group_id' => $custom_groups[$array['custom_group_name']]['id']));
        
    if(empty($cfield['values'])){
      // first create field with name as label
      $cfield = civicrm_api('CustomField', 'create', $ffparams);
      
      // second update field with label as label
      $sfparams['id'] = $cfield['values'][0]['id'];
      $cfield = civicrm_api('CustomField', 'create', $sfparams);
    
      if(!$cfield){
        CRM_Core_Session::setStatus( ts('An error occurred when creating the custom field ') . $array['name'] . ts(' !'), ts('Creating custom field'), 'error');
        return false;
      }else {
        //CRM_Core_Session::setStatus( ts('Custom field ') . $array['name'] . ts(' created !'), ts('Creating custom field'), 'success');
      }
    }else {
      // update weight, is_required, is_searchable and is_active
      $cfield = civicrm_api('CustomField', 'create', array('version' => 3, 'sequential' => 1, 'id' => $cfield['values'][0]['id'], 'weight' => $cfweight, 'is_required' => $array['is_required'], 'is_searchable' => '1', 'is_active' => '1'));
    }
    
    $cfweight++;
    
    $custom_fields[$custom_field]['id'] = $cfield['values'][0]['id'];
  }
    
  // option values
  $option_values = $lr->__get('option_values');
  foreach($option_values as $option_value => $array){
    
    $ovweight = 1;
    foreach($array['options'] as $value => $name){
      $params = array(
        'version' => 3,
        'sequential' => 1,
        'option_group_id' => $option_groups[$option_value]['id'],
        'value' => $value,
        'name' => $name,
        'weight' => $ovweight,
        'is_active' => '1'
      );

      $result = civicrm_api('OptionValue', 'get', array('version' => 3, 'sequential' => 1, 'value' => $value, 'name' => $name, 'option_group_id' => $option_groups[$option_value]['id']));
      
      $ovweight++;

      if(empty($result['values'])){
        $result = civicrm_api('OptionValue', 'create', $params);

        if(!$result){
          CRM_Core_Session::setStatus( ts('An error occurred when creating the option value ') . $name . ts(' !'), ts('Creating option value'), 'error');
          return false;
        }else {
          //CRM_Core_Session::setStatus( ts('Option value ') . $name . ts(' created !'), ts('Creating option value'), 'success');
        }
      }
    }
  }
  
  return TRUE;
}
?>