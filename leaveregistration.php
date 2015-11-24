<?php

require_once 'leaveregistration.civix.php';

/**
 * Implementation of hook_civicrm_config
 */
function leaveregistration_civicrm_config(&$config) {
  _leaveregistration_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function leaveregistration_civicrm_xmlMenu(&$files) {
  _leaveregistration_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
/*
function leaveregistration_civicrm_install() {
  return _leaveregistration_civix_civicrm_install();
}
*/

/**
 * Implementation of hook_civicrm_uninstall
 */
function leaveregistration_civicrm_uninstall() {
  return _leaveregistration_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function leaveregistration_civicrm_enable() {
  return _leaveregistration_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function leaveregistration_civicrm_disable() {
  return _leaveregistration_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function leaveregistration_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _leaveregistration_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function leaveregistration_civicrm_managed(&$entities) {
  return _leaveregistration_civix_civicrm_managed($entities);
}

include_once 'leaveregistration.class.php';
include_once 'leaveregistration.install.php';
include_once 'leaveregistration.contacts.php';
include_once 'leaveregistration.form.php';