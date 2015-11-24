<?php
/**
 * Implementation of hook_civicrm_post
 * 
 * Add, update or delete drupal user account
 */
function leaveregistration_civicrm_post( $op, $objectName, $objectId, &$objectRef )
{
  switch($objectName)
  {
    case 'Email':
      switch($op)
      {
        case 'create': 
        case 'edit':          
          $contacts = civicrm_api("Contact","get", array ('version' => '3','sequential' =>'1', 'id' => $objectRef->contact_id ));          
          $contact = $contacts['values'][0];
                    
          $contact_sub_type = array();
          if(is_array($contact['contact_sub_type'])){
            $contact_sub_type = $contact['contact_sub_type'];
          }else {
            $contact_sub_type[] = $contact['contact_sub_type'];
          }
                      
          if(in_array('Employee', $contact_sub_type)){ // if it`s a employee
            $user = user_load_by_mail($contact['email']);

            $roles = $user->roles;
            if (isset($contact['contact_sub_type']) and in_array('Employee', $contact['contact_sub_type'])) {
              $rid = array_search('employee', user_roles());
              $roles[$rid] = 'employee';
            }

            // department head
            $relationshiptypes = civicrm_api("RelationshipType","get", array ('version' => '3','sequential' =>'1', 'name_a_b' =>'Department head'));
            $relationshiptype = $relationshiptypes['values'][0];
            $relationship_type_id = $relationshiptype['id'];

            $relationships = civicrm_api("Relationship","get", array ('version' => '3','sequential' =>'1', 'relationship_type_id' => $relationship_type_id, 'contact_id_b' => $contact['contact_id']));

            if(!empty($relationships['values'][0])){
              $rid = array_search('department head', user_roles());
              $roles[$rid] = 'department head';
            }
            
            // finance
            $relationshiptypes = civicrm_api("RelationshipType","get", array ('version' => '3','sequential' =>'1', 'name_a_b' =>'Finance of'));
            $relationshiptype = $relationshiptypes['values'][0];
            $relationship_type_id = $relationshiptype['id'];

            $relationships = civicrm_api("Relationship","get", array ('version' => '3','sequential' =>'1', 'relationship_type_id' => $relationship_type_id, 'contact_id_a' => $contact['contact_id']));

            if(!empty($relationships['values'][0])){
              $rid = array_search('finance', user_roles());
              $roles[$rid] = 'finance';
            }
            
            $username = '';
            if('' != $contact['first_name'] and '' != $contact['last_name']){
              // first_name
              $contact['first_name'] = strtolower(trim($contact['first_name']));
              $username = substr(strtolower($contact['first_name']), 0, 1);
              
              // middle_name
              if('' != $contact['middle_name']){
                $contact['middle_name'] = strtolower(trim($contact['middle_name']));
                if(false !== strpos($contact['middle_name'], ' ')){
                  $middle_names = explode(' ', $contact['middle_name']);
                }else {
                  $middle_names[] = $contact['middle_name'];
                }
                
                $username .= '.';
                foreach($middle_names as $key => $middle_name){
                  $username .= substr($middle_name, 0, 1);
                }
              }
              
              // last_name
              $contact['last_name'] = strtolower(trim($contact['last_name']));
              $username .= '.' . $contact['last_name'];
              
            }else {
              $contact['email'] = strtolower(trim($contact['email']));
              $username = substr($contact['email'], 0, strpos($contact['email'], '@'));
            }
            
            if(0 != $user->uid and $contact['email'] == $user->mail){
              // edit
              $edit = array
              (
                'mail' => $contact['email'],
                'init' => $contact['email'],
                'status' => 1,
                'roles' => $roles
              );
              $account = user_save($user, $edit);

            }else {
              // add
              $edit = array
              (
                'name' => $username,
                'pass' => 'Welkom01',
                'mail' => $contact['email'],
                'init' => $contact['email'],
                'status' => 1,
                'roles' => $roles
              );

              $account = user_save('', $edit);
            }

            if (!$account) {
              CRM_Core_Session::setStatus( ts('A error occure on add/edit drupal account !'), ts('Add/edit drupal account'), 'error');

            }else {
              CRM_Core_Session::setStatus( ts('Drupal account added/updated !'), ts('Add/edit drupal account'), 'success');
            }
          }
          break;  
    }
    break;
    
    case 'Individual':
      switch($op)
      {
        case 'trash':
          $contacts = civicrm_api("Contact","get", array ('version' => '3','sequential' =>'1', 'id' => $objectRef->id ));
          $contact = $contacts['values'][0];

          $user = user_load_by_mail($contact['email']);
                    
          if(0 != $user->uid and $contact['email'] == $user->mail){
            
            user_delete($user->uid);
            CRM_Core_Session::setStatus( ts('Drupal account deleted !'), ts('Delete drupal account'), 'success');
          }
        
          break;
      }
      break;
    
    case 'Relationship':
      switch($op)
      {
        case 'create':
        case 'edit':
          // department head
          $contacts = civicrm_api("Contact","get", array ('version' => '3','sequential' =>'1', 'id' => $objectRef->contact_id_b, 'contact_sub_type' => 'Employee' ));
                    
          if(!empty($contacts['values'][0])){
            $contact = $contacts['values'][0];

            $user = user_load_by_mail($contact['email']);

            if(0 != $user->uid and $contact['email'] == $user->mail){
              $roles = $user->roles;
              
              
              $relationshiptypes = civicrm_api("RelationshipType","get", array ('version' => '3','sequential' =>'1', 'name_a_b' =>'Department head'));
              $relationshiptype = $relationshiptypes['values'][0];
              $relationship_type_id = $relationshiptype['id'];

              if($objectRef->relationship_type_id == $relationship_type_id and $objectRef->contact_id_b == $contact['contact_id']){
                $rid = array_search('department head', user_roles());
                $roles[$rid] = 'department head';

                $edit = array
                (
                  'roles' => $roles
                );
                $account = user_save($user, $edit);

                if (!$account) {
                  CRM_Core_Session::setStatus( ts('A error occure on edit drupal contact, when add/edit relationship !'), ts('Add/edit relationship'), 'error');

                }else {
                  CRM_Core_Session::setStatus( ts('Drupal account updated, when add/edit relationship !'), ts('Add/edit relationship'), 'success');
                }
              }
            }
          }
          
          // administration
          $contacts = civicrm_api("Contact","get", array ('version' => '3','sequential' =>'1', 'id' => $objectRef->contact_id_a, 'contact_sub_type' => 'Employee' ));
                    
          if(!empty($contacts['values'][0])){
            $contact = $contacts['values'][0];

            $user = user_load_by_mail($contact['email']);

            if(0 != $user->uid and $contact['email'] == $user->mail){
              $roles = $user->roles;
              
              // department head
              $relationshiptypes = civicrm_api("RelationshipType","get", array ('version' => '3','sequential' =>'1', 'name_a_b' =>'Administration of'));
              $relationshiptype = $relationshiptypes['values'][0];
              $relationship_type_id = $relationshiptype['id'];

              if($objectRef->relationship_type_id == $relationship_type_id and $objectRef->contact_id_a == $contact['contact_id']){
                $rid = array_search('administration', user_roles());
                $roles[$rid] = 'administration';

                $edit = array
                (
                  'roles' => $roles
                );
                $account = user_save($user, $edit);

                if (!$account) {
                  CRM_Core_Session::setStatus( ts('A error occure on edit drupal contact, when add/edit relationship !'), ts('Add/edit relationship'), 'error');

                }else {
                  CRM_Core_Session::setStatus( ts('Drupal account updated, when add/edit relationship !'), ts('Add/edit relationship'), 'success');
                }
              }
            }
          }
          break;
          
        case 'delete':
          // department head
          $contacts = civicrm_api("Contact","get", array ('version' => '3','sequential' =>'1', 'id' => $objectRef->contact_id_b, 'contact_sub_type' => 'Employee' ));
          
          if(!empty($contacts['values'][0])){
            $contact = $contacts['values'][0];

            $user = user_load_by_mail($contact['email']);

            if(0 != $user->uid and $contact['email'] == $user->mail){
              $roles = $user->roles;
              
              $relationshiptypes = civicrm_api("RelationshipType","get", array ('version' => '3','sequential' =>'1', 'name_a_b' =>'Department head'));
              $relationshiptype = $relationshiptypes['values'][0];
              $relationship_type_id = $relationshiptype['id'];

              if($objectRef->relationship_type_id == $relationship_type_id and $objectRef->contact_id_b == $contact['contact_id']){
                $rid = array_search('department head', user_roles());
                unset($roles[$rid]);

                $edit = array
                (
                  'roles' => $roles
                );
                $account = user_save($user, $edit);

                if (!$account) {
                  CRM_Core_Session::setStatus( ts('A error occure on edit drupal contact, when delete relationship !'), ts('Delete relationship'), 'error');

                }else {
                  CRM_Core_Session::setStatus( ts('Drupal account updated, when delete relationship !'), ts('Delete relationship'), 'success');
                }
              }
            }
          }
          
          // administration
          $contacts = civicrm_api("Contact","get", array ('version' => '3','sequential' =>'1', 'id' => $objectRef->contact_id_a, 'contact_sub_type' => 'Employee' ));
                    
          if(!empty($contacts['values'][0])){
            $contact = $contacts['values'][0];

            $user = user_load_by_mail($contact['email']);

            if(0 != $user->uid and $contact['email'] == $user->mail){
              $roles = $user->roles;
              
              // department head
              $relationshiptypes = civicrm_api("RelationshipType","get", array ('version' => '3','sequential' =>'1', 'name_a_b' =>'Administration of'));
              $relationshiptype = $relationshiptypes['values'][0];
              $relationship_type_id = $relationshiptype['id'];

              if($objectRef->relationship_type_id == $relationship_type_id and $objectRef->contact_id_a == $contact['contact_id']){
                $rid = array_search('administration', user_roles());
                unset($roles[$rid]);

                $edit = array
                (
                  'roles' => $roles
                );
                $account = user_save($user, $edit);

                if (!$account) {
                  CRM_Core_Session::setStatus( ts('A error occure on edit drupal contact, when add/edit relationship !'), ts('Add/edit relationship'), 'error');

                }else {
                  CRM_Core_Session::setStatus( ts('Drupal account updated, when add/edit relationship !'), ts('Add/edit relationship'), 'success');
                }
              }
            }
          }
          break;
      }
      
      break;
  }
}
?>
