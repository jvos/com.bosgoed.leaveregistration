<?php
/**
 * Class configuration singleton
 * 
 * @client De Goede Woning (http://www.degoedewoning.nl)
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 12 May 2014
 * 
 * Copyright (C) 2014 Coöperatieve CiviCooP U.A. <http://www.civicoop.org>
 * Licensed to De Goede Woning <http://www.degoedewoning.nl> and CiviCRM under AGPL-3.0
 */
class CRM_Leaveregistration_Config {
  /*
   * singleton pattern
   */
  static private $_singleton = NULL;
  
  public $lr = [];
  
  public $years = [];
  public $months = [];
  public $weeks = [];
  public $weeksKey = [];
  
  public $employees = [];
  public $departments = [];
  public $business = [];
  
  public $relationship_types = [];
  
  /**
   * Constructor function
   */
  function __construct() {
    $this->lr = new leaveregistration('civicrm', 'CRM_Leaveregistration_Config');
    $this->lr->set_fields();
        
    $this->setYears();
    $this->setMonths();
    $this->setWeeks();
    $this->setWeeksKey();
    
    $this->setEmployees();
    $this->setDepartments();
    $this->setBusiness();
  }
    
  private function setYears(){
    $years = [];
    
    for($i=2008; $i <= date('Y')+2; $i++){
      $years[$i] = $i;
    }
    
    $this->years = $years;
  }
  
  private function setMonths(){
    $months = [];
    for($i=1; $i <= 12; $i++){
      if(1 == strlen($i)){
        $months['0' . $i] = '0' . $i;
      }else {
        $months[$i] = $i;
      }
    }
        
    $this->months = $months;
  }
  
  private function setWeeks(){
    $weeks = [];
    for($i=1; $i <= 52; $i++){
      if(1 == strlen($i)){
        $weeks['0' . $i] = '0' . $i;
      }else {
        $weeks[$i] = $i;
      }
    }
        
    $this->weeks = $weeks;
  }
  
  private function setWeeksKey(){
    $weeks = [];
    for($i=1; $i <= 52; $i++){
      if(1 == strlen($i)){
        $weeks[$i] = '0' . $i;
      }else {
        $weeks[$i] = $i;
      }
    }
        
    $this->weeksKey = $weeks;
  }
  
  private function setEmployees(){
    try {
      $result = civicrm_api3('Contact', 'get', array(
        'sequential' => 1,
        'contact_type' => "Individual",
        'contact_sub_type' => "Employee",
        'options' => array('limit' => 0, 'sort' => "display_name ASC"),
        'is_deleted' => 0,
      ));
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not get all the employees '.
        ', error from API Contact get : '.$ex->getMessage());
    }
    
    $employees = [];
    foreach ($result['values'] as $contact){
      $employees[$contact['contact_id']] = $contact['display_name'];
    }
    
    $this->employees = $employees;
  }
  
  public function getEmployees(){
    return $this->employees;
  }
  
  private function setDepartments(){
    try {
      $result = civicrm_api3('Contact', 'get', array(
        'sequential' => 1,
        'contact_type' => "Organization",
        'contact_sub_type' => "Department",
        'options' => array('limit' => 0, 'sort' => "display_name ASC"),
        'is_deleted' => 0,
      ));
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not get all the departments '.
        ', error from API Contact get : '.$ex->getMessage());
    }
    
    $departments = [];
    foreach ($result['values'] as $contact){
      $departments[$contact['contact_id']] = $contact['display_name'];
    }
    
    $this->departments = $departments;
  }
  
  private function setBusiness(){
    try {
      $result = civicrm_api3('Contact', 'get', array(
        'sequential' => 1,
        'contact_type' => "Organization",
        'contact_sub_type' => "Business",
        'options' => array('limit' => 0, 'sort' => "display_name ASC"),
        'is_deleted' => 0,
      ));
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not get all the departments '.
        ', error from API Contact get : '.$ex->getMessage());
    }
    
    $business = [];
    foreach ($result['values'] as $contact){
      $business[$contact['contact_id']] = $contact['display_name'];
    }
    
    $this->business = $business;
  }
  
  public function getEmployeesIds(){
    try {
      $result = civicrm_api3('Contact', 'get', array(
        'sequential' => 1,
        'contact_type' => "Individual",
        'contact_sub_type' => "Employee",
        'options' => array('limit' => 0, 'sort' => "display_name ASC"),
        'is_deleted' => 0,
      ));
    } catch (CiviCRM_API3_Exception $ex) {
      throw new Exception('Could not get all the employees '.
        ', error from API Contact get : '.$ex->getMessage());
    }
    
    $employees = [];
    foreach ($result['values'] as $contact){
      $employees[] = $contact['contact_id'];
    }
    
    return $employees;
  }
  
  public function getDepartmentEmployeesIds($departments_ids, $fields = []){
    // see leaveregistration.class.php set_department_colleages_ids()
    //$query = "SELECT civicrm_contact.id, civicrm_contact.contact_type, civicrm_contact.contact_sub_type, civicrm_contact.first_name, civicrm_contact.last_name, civicrm_contact.display_name, civicrm_email.email, civicrm_relationship.contact_id_a, civicrm_relationship.contact_id_b";
    $query = "SELECT civicrm_contact.id, civicrm_relationship.contact_id_a, civicrm_relationship.contact_id_b";
    $query .= " FROM `civicrm_contact`";
    //$query .= " LEFT JOIN civicrm_email ON civicrm_email.contact_id = civicrm_contact.id";
    $query .= " LEFT JOIN civicrm_relationship ON civicrm_relationship.contact_id_a = civicrm_contact.id";
    $query .= " WHERE";
    $query .= " civicrm_relationship.relationship_type_id = '" . $this->lr->relationship_types['employee_of']['id'] . "' AND civicrm_relationship.is_active = '1' AND"; 
    $query .= " is_deleted = '0' AND (";
    
    $where = "";
    foreach($departments_ids as $departments_id){
      $where .= " OR civicrm_relationship.contact_id_b = '" . $departments_id . "'";
    }
        
    $query .= substr($where, 3) . ")";
            
    $employees = [];
        
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      $employees[] = $dao->contact_id_a;
    }
    
    return $employees;
  }
  
  public function getBusinessEmployeesIds($business_ids, $fields = []){
    // see leaveregistration.class.php set_business_colleages_ids()
    $query = "SELECT 
      contact.id
      FROM `civicrm_contact` AS contact
      
      LEFT JOIN civicrm_relationship AS contact_relationship ON contact_relationship.contact_id_a = contact.id 		
      INNER JOIN civicrm_relationship AS business ON business.contact_id_a = contact_relationship.contact_id_b

      WHERE 
      contact_relationship.relationship_type_id = '" . $this->lr->relationship_types['employee_of']['id'] . "' AND 
      contact_relationship.is_active = '1' AND

      business.relationship_type_id = '" . $this->lr->relationship_types['department_of']['id'] . "' AND
      business.is_active = '1' AND (
      ";

    $where = "";
    foreach($business_ids as $business_id){
      $where .= " OR business.contact_id_b = '" . $business_id . "'";
    }

    $query .= substr($where, 3) . ")";
    
    $employees = [];
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      $employees[] = $dao->id;
    }
    
    return $employees;
  }
  
  /**
   * Function to return singleton object
   * 
   * @return object $_singleton
   * @access public
   * @static
   */
  public static function &singleton() {
    if (self::$_singleton === NULL) {
      self::$_singleton = new CRM_Leaveregistration_Config();
    }
    return self::$_singleton;
  }
}
