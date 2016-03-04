<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 => 
  array (
    'name' => 'CRM_Leaveregistration_Form_Report_LeaveRegistrationTotal',
    'entity' => 'ReportTemplate',
    'params' => 
    array (
      'version' => 3,
      'label' => 'LeaveRegistrationTotal',
      'description' => 'Leave Registration Total (com.bosgoed.leaveregistration)',
      'class_name' => 'CRM_Leaveregistration_Form_Report_LeaveRegistrationTotal',
      'report_url' => 'com.bosgoed.leaveregistration/leaveregistrationtotal',
      'component' => '',
    ),
  ),
);