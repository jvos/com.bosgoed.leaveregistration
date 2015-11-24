<?php
class leaveregistration 
{
  private $contact_sub_types = array
  (
    'business' => array('contact_type' => 'Organization', 'contact_sub_type' => 'Business', 'parent_id' => '3'),
    'department' => array('contact_type' => 'Organization', 'contact_sub_type' => 'Department', 'parent_id' => '3'),
    'employee' => array('contact_type' => 'Individual', 'contact_sub_type' => 'Employee', 'parent_id' => '1')
  );
  
  private $relationship_types = array
  (
    'employee_of' => array('name_a_b' => 'Employee of', 'description' => 'Employee relationship.', 'contact_type_a' => 'Individual', 'contact_type_b' => 'Organization', 'contact_sub_type_a' => 'Employee', 'contact_sub_type_b' => 'Department'),
    'department_of' => array('name_a_b' => 'Department of', 'description' => 'Department relationship.', 'contact_type_a' => 'Organization', 'contact_type_b' => 'Organization', 'contact_sub_type_a' => 'Department', 'contact_sub_type_b' => 'Business'),
    'department_head' => array('name_a_b' => 'Department head', 'description' => 'Department head relationship.', 'contact_type_a' => 'Organization', 'contact_type_b' => 'Individual', 'contact_sub_type_a' => 'Department', 'contact_sub_type_b' => 'Employee'),
    'main_organization' => array('name_a_b' => 'Main Organization of', 'description' => 'Main Organization relationship.', 'contact_type_a' => 'Organization', 'contact_type_b' => 'Organization', 'contact_sub_type_a' => 'Business', 'contact_sub_type_b' => 'Business'),
    'administration_of' => array('name_a_b' => 'Administration of', 'description' => 'Administration relationship.', 'contact_type_a' => 'Individual', 'contact_type_b' => 'Organization', 'contact_sub_type_a' => 'Employee', 'contact_sub_type_b' => 'Business'),
  );
  
	private $custom_groups = array
	(
    'leave_holidays' => array('name' => 'leave_holidays', 'title' => 'Leave - Holidays', 'extends' => 'Organization', 'extends_entity_column_value' => 'DepartmentBusiness', 'style' => 'Tab', 'title_en_US' => 'Leave - Holidays', 'is_multiple' => '1'),
    'leave_general' => array('name' => 'leave_general', 'title' => 'Leave - General', 'extends' => 'Organization', 'extends_entity_column_value' => 'DepartmentBusiness', 'style' => 'Tab', 'title_en_US' => 'Leave - General', 'is_multiple' => '0'),
    'leave_overall_adjustments' => array('name' => 'leave_overall_adjustments', 'title' => 'Leave - Overall adjustments', 'extends' => 'Organization', 'extends_entity_column_value' => 'DepartmentBusiness', 'style' => 'Tab', 'title_en_US' => 'Leave - Overall adjustments', 'is_multiple' => '1'),
    'leave_overall_credit' => array('name' => 'leave_overall_credit', 'title' => 'Leave - Overall credit', 'extends' => 'Organization', 'extends_entity_column_value' => 'DepartmentBusiness', 'style' => 'Tab', 'title_en_US' => 'Leave - Overall credit', 'is_multiple' => '1'),
    
    'leave_settings' => array('name' => 'leave_settings', 'title' => 'Leave - Settings', 'extends' => 'Individual', 'extends_entity_column_value' => 'Employee', 'style' => 'Tab', 'title_en_US' => 'Leave - Settings', 'is_multiple' => '0'),
    'leave_adjustments' => array('name' => 'leave_adjustments', 'title' => 'Leave - Adjustments', 'extends' => 'Individual', 'extends_entity_column_value' => 'Employee', 'style' => 'Tab', 'title_en_US' => 'Leave - Adjustments', 'is_multiple' => '1'),
    'leave_credit' => array('name' => 'leave_credit', 'title' => 'Leave - Credit', 'extends' => 'Individual', 'extends_entity_column_value' => 'Employee', 'style' => 'Tab', 'title_en_US' => 'Leave - Credit', 'is_multiple' => '1'),
    'leave_request' => array('name' => 'leave_request', 'title' => 'Leave - Request', 'extends' => 'Activity', 'extends_entity_column_value' => '', 'style' => 'Inline', 'title_en_US' => 'Leave - Request', 'is_multiple' => '0'),
	);
	
  private $custom_groups_fields = array
  (
    'leave_holidays' => array
    (
      'leave_holidays_name',
      'leave_holidays_start_date',
      'leave_holidays_end_date',
      'leave_holidays_infinite',
    ),
    'leave_general' => array
    (
      'leave_general_monday',
      'leave_general_tuesday',
      'leave_general_wednesday',
      'leave_general_thursday',
      'leave_general_friday',
      'leave_general_saturday',
      'leave_general_sunday',
      'leave_general_total_leave',
    ),
    'leave_overall_adjustments' => array
    (
      'leave_overall_adjustments_start_date', 
      'leave_overall_adjustments_end_date', 
      'leave_overall_adjustments_monday',
      'leave_overall_adjustments_tuesday',
      'leave_overall_adjustments_wednesday',
      'leave_overall_adjustments_thursday',
      'leave_overall_adjustments_friday',
      'leave_overall_adjustments_saturday',
      'leave_overall_adjustments_sunday',
    ),
    /*'leave_overall_credit' => array
    (
      'leave_overall_credit_start_date',
      'leave_overall_credit_end_date',
      'leave_overall_credit_following_year_to',
      'leave_overall_credit_total_leave',
    ),*/
    'leave_overall_credit' => array
    (
      'leave_overall_credit_from_year',
      'leave_overall_credit_to_year',
      'leave_overall_credit_infinite',
      'leave_overall_credit_total_leave_per_year',
    ),
    'leave_settings' => array
    (
      'leave_settings_show_all_colleagues_of',
      'leave_settings_show_department_head_to',
    ),
    'leave_adjustments' => array
    (
      'leave_adjustments_start_date',
      'leave_adjustments_end_date',
      'leave_adjustments_infinite',
      'leave_adjustments_each',
      'leave_adjustments_day_week_month_year',
      'leave_adjustments_on',
      'leave_adjustments_day_of_the_week',
      'leave_adjustments_duration',
    ),
    /*'leave_credit' => array
    (
      'leave_credit_start_date',
      'leave_credit_end_date',
      'leave_credit_following_year_to',
      'leave_credit_total_leave',
    ),*/
    'leave_credit' => array
    (
      'leave_credit_from_year',
      'leave_credit_to_year',
      'leave_credit_infinite',
      'leave_credit_total_leave_per_year',
      'leave_credit_total_leave_per_year_over',
    ),
    'leave_request' => array
    (
      'leave_request_leave_type',
      'leave_request_reason',
      'leave_request_status',
      'leave_request_from_date',
      'leave_request_to_date',
      'leave_request_date',
      'leave_request_each',
      'leave_request_day_week_month_year',
      'leave_request_on',
      'leave_request_day_of_the_week',
      'leave_request_duration',
    )
  );
  
	private $custom_fields = array
	(
    'leave_general_monday' => array('name' => 'leave_general_monday', 'label' => 'Monday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Monday must be hours and minutes separated by :'),
    'leave_general_tuesday' => array('name' => 'leave_general_tuesday', 'label' => 'Tuesday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Tuesday must be hours and minutes separated by :'),
    'leave_general_wednesday' => array('name' => 'leave_general_wednesday', 'label' => 'Wednesday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Wednesday must be hours and minutes separated by :'),
    'leave_general_thursday' => array('name' => 'leave_general_thursday', 'label' => 'Thursday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Thursday must be hours and minutes separated by :'),
    'leave_general_friday' => array('name' => 'leave_general_friday', 'label' => 'Friday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Friday must be hours and minutes separated by :'),
    'leave_general_saturday' => array('name' => 'leave_general_saturday', 'label' => 'Saturday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '0:00', 'is_required' => '1', 'help_pre' => 'Saturday must be hours and minutes separated by :'),
    'leave_general_sunday' => array('name' => 'leave_general_sunday', 'label' => 'Sunday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '0:00', 'is_required' => '1', 'help_pre' => 'Sunday must be hours and minutes separated by :'),
    'leave_general_total_leave' => array('name' => 'leave_general_total_leave', 'label' => 'Total leave', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '200:00', 'is_required' => '1', 'help_pre' => 'Total leave must be hours and minutes separated by :'),
		
    'leave_overall_adjustments_start_date' => array('name' => 'leave_overall_adjustments_start_date', 'label' => 'Start date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null'),
    'leave_overall_adjustments_end_date' => array('name' => 'leave_overall_adjustments_end_date', 'label' => 'End date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null'),
    'leave_overall_adjustments_monday' => array('name' => 'leave_overall_adjustments_monday', 'label' => 'Monday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Monday must be hours and minutes separated by :'),
    'leave_overall_adjustments_tuesday' => array('name' => 'leave_overall_adjustments_tuesday', 'label' => 'Tuesday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Tuesday must be hours and minutes separated by :'),
    'leave_overall_adjustments_wednesday' => array('name' => 'leave_overall_adjustments_wednesday', 'label' => 'Wednesday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Wednesday must be hours and minutes separated by :'),
    'leave_overall_adjustments_thursday' => array('name' => 'leave_overall_adjustments_thursday', 'label' => 'Thursday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Thursday must be hours and minutes separated by :'),
    'leave_overall_adjustments_friday' => array('name' => 'leave_overall_adjustments_friday', 'label' => 'Friday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Friday must be hours and minutes separated by :'),
    'leave_overall_adjustments_saturday' => array('name' => 'leave_overall_adjustments_saturday', 'label' => 'Saturday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '0:00', 'is_required' => '1', 'help_pre' => 'Saturday must be hours and minutes separated by :'),
    'leave_overall_adjustments_sunday' => array('name' => 'leave_overall_adjustments_sunday', 'label' => 'Sunday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '0:00', 'is_required' => '1', 'help_pre' => 'Sunday must be hours and minutes separated by :'),
    
    /*'leave_overall_credit_start_date' => array('name' => 'leave_overall_credit_start_date', 'label' => 'Start date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'help_pre' => 'The start date must be in the same year as the end date'),
    'leave_overall_credit_end_date' => array('name' => 'leave_overall_credit_end_date', 'label' => 'End date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'help_pre' => 'The end date must be in the same year as the start date'),
    'leave_overall_credit_following_year_to' => array('name' => 'leave_overall_credit_following_year_to', 'label' => 'Following years to', 'data_type' => 'Boolean', 'html_type' => 'Radio', 'default_value' => 'Null', 'is_required' => '1', 'help_pre' => 'The same for the following years'),
    'leave_overall_credit_total_leave' => array('name' => 'leave_overall_credit_total_leave', 'label' => 'Total leave', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '200:00', 'is_required' => '1', 'help_pre' => 'Total leave must be hours and minutes separated by :'),
    */
    'leave_overall_credit_from_year' => array('name' => 'leave_overall_credit_from_year', 'label' => 'From year', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy', 'time_format' => 'null', 'help_pre' => ''),
    'leave_overall_credit_to_year' => array('name' => 'leave_overall_credit_to_year', 'label' => 'To year', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '0', 'date_format' => 'yy', 'time_format' => 'null', 'help_pre' => 'If you specify to year than the total leave is untill that year, even if you fill out infinite.'),
    'leave_overall_credit_infinite' => array('name' => 'leave_overall_credit_infinite', 'label' => 'Infinite', 'data_type' => 'Boolean', 'html_type' => 'Radio', 'default_value' => 'Null', 'is_required' => '1', 'help_pre' => 'If it is infinite, it valid for any subsequent years.'),
    'leave_overall_credit_total_leave_per_year' => array('name' => 'leave_overall_credit_total_leave_per_year', 'label' => 'Total leave per year', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '200:00', 'is_required' => '1', 'help_pre' => 'Total leave per year must be hours and minutes separated by :'),
    
    'leave_holidays_name' => array('name' => 'leave_holidays_name', 'label' => 'Name', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => 'Null', 'is_required' => '1'),
    'leave_holidays_start_date' => array('name' => 'leave_holidays_start_date', 'label' => 'Start date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null'),
    'leave_holidays_end_date' => array('name' => 'leave_holidays_end_date', 'label' => 'End date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '0', 'date_format' => 'yy-mm-dd', 'time_format' => 'null'),
    'leave_holidays_infinite' => array('name' => 'leave_holidays_infinite', 'label' => 'Infinite', 'data_type' => 'Boolean', 'html_type' => 'Radio', 'default_value' => '1', 'is_required' => '1', 'help_pre' => 'If it is infinite, it valid for any subsequent years'),
    
    'leave_settings_show_all_colleagues_of' => array('name' => 'leave_settings_show_all_colleagues_of', 'label' => 'Show all colleagues of', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'department', 'is_required' => '1', 'help_pre' => 'Show in the calendar all the employees of department or business.'),
    'leave_settings_show_department_head_to' => array('name' => 'leave_settings_show_department_head_to', 'label' => 'Show department head to', 'data_type' => 'Boolean', 'html_type' => 'Radio', 'default_value' => '0', 'is_required' => '1', 'help_pre' => 'Show in the calendar the department head of the department.'),
    
    'leave_adjustments_start_date' => array('name' => 'leave_adjustments_start_date', 'label' => 'Start date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null'),
    'leave_adjustments_end_date' => array('name' => 'leave_adjustments_end_date', 'label' => 'End date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '0', 'date_format' => 'yy-mm-dd', 'time_format' => 'null'),
    'leave_adjustments_infinite' => array('name' => 'leave_adjustments_infinite', 'label' => 'Infinite', 'data_type' => 'Boolean', 'html_type' => 'Radio', 'default_value' => 'Null', 'is_required' => '1', 'help_pre' => 'If it is infinite'),
    'leave_adjustments_each' => array('name' => 'leave_adjustments_each', 'label' => 'Each', 'data_type' => 'Int', 'html_type' => 'Text', 'default_value' => '1', 'is_required' => '1', 'help_pre' => 'Example: Each 2 week monday 8:00, <br>Example 2: Each 3 month first friday 6:00'),
    'leave_adjustments_day_week_month_year' => array('name' => 'leave_adjustments_day_week_month_year', 'label' => 'Day / Week / Month / Year', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'request', 'is_required' => '1', 'help_pre' => 'Example: week, <br>Example 2: month'),
    'leave_adjustments_on' => array('name' => 'leave_adjustments_on', 'label' => 'On', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'nvt', 'is_required' => '0', 'help_pre' => 'Example: n.v.t, <br>Example 2: first'),
    'leave_adjustments_day_of_the_week' => array('name' => 'leave_adjustments_day_of_the_week', 'label' => 'Day of the week', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'nvt', 'is_required' => '0', 'help_pre' => 'Example: monday, <br>Example 2: friday'),
    'leave_adjustments_duration' => array('name' => 'leave_adjustments_duration', 'label' => 'Duration', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Example: 8:00, <br>Example 2: 6:00. <br>Duration must be hours and minutes separated by :<br/>Duration of hours that a person work, not the duration that a person not work.'),
    				
    /*'leave_credit_start_date' => array('name' => 'leave_credit_start_date', 'label' => 'Start date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'help_pre' => 'The start date must be in the same year as the end date'),
    'leave_credit_end_date' => array('name' => 'leave_credit_end_date', 'label' => 'End date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '0', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'help_pre' => 'The end date must be in the same year as the start date'),
    'leave_credit_following_year_to' => array('name' => 'leave_credit_following_year_to', 'label' => 'Following years to', 'data_type' => 'Boolean', 'html_type' => 'Radio', 'default_value' => 'Null', 'is_required' => '1', 'help_pre' => 'The same for the following years'),
    'leave_credit_total_leave' => array('name' => 'leave_credit_total_leave', 'label' => 'Total leave', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '200:00', 'is_required' => '1', 'help_pre' => 'Total leave must be hours and minutes separated by :'),
    */
    'leave_credit_from_year' => array('name' => 'leave_credit_from_year', 'label' => 'From year', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy', 'time_format' => 'null', 'help_pre' => ''),
    'leave_credit_to_year' => array('name' => 'leave_credit_to_year', 'label' => 'To year', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '0', 'date_format' => 'yy', 'time_format' => 'null', 'help_pre' => 'If you specify to year than the total leave is untill that year, even if you fill out infinite.'),
    'leave_credit_infinite' => array('name' => 'leave_credit_infinite', 'label' => 'Infinite', 'data_type' => 'Boolean', 'html_type' => 'Radio', 'default_value' => 'Null', 'is_required' => '1', 'help_pre' => 'If it is infinite, it valid for any subsequent years'),
    'leave_credit_total_leave_per_year' => array('name' => 'leave_credit_total_leave_per_year', 'label' => 'Total leave per year', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '200:00', 'is_required' => '0', 'help_pre' => 'Total leave per year must be hours and minutes separated by :'),
    'leave_credit_total_leave_per_year_over' => array('name' => 'leave_credit_total_leave_per_year_over', 'label' => 'Total leave per year over', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '0:00', 'is_required' => '0', 'help_pre' => 'Total leave per year over must be hours and minutes separated by :, it can also be negative'),

    'leave_request_leave_type' => array('name' => 'leave_request_leave_type', 'label' => 'Leave type', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'normal_leave', 'is_required' => '1'),
    'leave_request_reason' => array('name' => 'leave_request_reason', 'label' => 'Reason', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => 'Null', 'is_required' => '0'),
    'leave_request_status' => array('name' => 'leave_request_status', 'label' => 'Status', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'request', 'is_required' => '1'),
    'leave_request_from_date' => array('name' => 'leave_request_from_date', 'label' => 'From date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null'),
    'leave_request_to_date' => array('name' => 'leave_request_to_date', 'label' => 'To date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null'),
    'leave_request_date' => array('name' => 'leave_request_date', 'label' => 'Date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null'),
    'leave_request_each' => array('name' => 'leave_request_each', 'label' => 'Each', 'data_type' => 'Int', 'html_type' => 'Text', 'default_value' => '1', 'is_required' => '1'),
    'leave_request_day_week_month_year' => array('name' => 'leave_request_day_week_month_year', 'label' => 'Day / Week / Month / Year', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'request', 'is_required' => '1'),
    'leave_request_on' => array('name' => 'leave_request_on', 'label' => 'On', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'nvt', 'is_required' => '0'),
    'leave_request_day_of_the_week' => array('name' => 'leave_request_day_of_the_week', 'label' => 'Day of the week', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'Null', 'is_required' => '0'),
    'leave_request_duration' => array('name' => 'leave_request_duration', 'label' => 'Duration', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Duration must be hours and minutes separated by :'),
	);
  
  private $option_group = array
  (
    'leave_settings_show_all_colleagues_of' => array('title_en_US' => 'Leave - Settings - Show all colleagues of', 
      'options' => array
      (
        'department' => 'Department',
        'business' => 'Business',
        //'main business' => 'Main business'
      )
    ),
    
    'leave_adjustments_day_week_month_year' => array('title_en_US' => 'Leave - Adjustments - Day / Week / Month / Year', 
      'options' => array
      (
        'day' => 'Day',
        'week' => 'Week',
        'month' => 'Month',
        'year' => 'Year'
      )
    ),
    'leave_adjustments_on' => array('title_en_US' => 'Leave - Adjustments - On', 
      'options' => array
      (
        'nvt' => 'n.v.t',
        'next' => 'Next',
        'previous' => 'Previous',
        'first' => 'First',
        'last' => 'Last',
      )
    ),
    'leave_adjustments_day_of_the_week' => array('title_en_US' => 'Leave - Adjustments - Day of the week',
      'options' => array
      (
        'nvt' => 'n.v.t',
        'Monday' => 'Monday',
        'Tuesday' => 'Tuesday',
        'Wednesday' => 'Wednesday',
        'Thursday' => 'Thursday',
        'Friday' => 'Friday',
        'Saturday' => 'Saturday',
        'Sunday' => 'Sunday',
      )
    ),
    'leave_request_leave_type' => array('title_en_US' => 'Leave - Request - Leave type',
      'options' => array
      (
        //'atv' => 'ATV', //
        'special_leave' => 'Special leave', // buitengewoon verlof
        'doctor_visit' => 'Doctor visit', // dokter bezoek
        'normal_leave' => 'Normal leave', // normaal verlof
        'normal_leave_less_one_day' => 'Normal leave < 1 day', // Normaal verlof < 1 dag
        'mom_dad_day' => 'Parental', // ouderschapsverlof
        'mom_dad_day_contiguous' => 'Parental contiguous', // ouderschapsverlof aaneensluitend
        'study_leave' => 'Study leave', // studieverlof
        'time_for_time' => 'Time for time', // tijd voor tijd
        'care' => 'Care', // zorgverlof
        'maternity' => 'Maternity', // zwangerschapsverlof
        'sick' => 'Sick', // ziek
        'sick_less_one_day' => 'Sick < 1 day', // Ziek < 1 dag
      )
    ),
    'leave_request_status' => array('title_en_US' => 'Leave - Request - Status',
      'options' => array
      (
        'request' => 'Request',
        'in_treatment' => 'In treatment',
        'approved' => 'Approved',
        'rejected' => 'Rejected'
      )
    ),
    'leave_request_day_week_month_year' => array('title_en_US' => 'Leave - Request - Day / Week / Month / Year', 
      'options' => array
      (
        //'day' => 'Day',
        'week' => 'Week',
        'month' => 'Month',
        'year' => 'Year',
      )
    ),
    'leave_request_on' => array('title_en_US' => 'Leave - Request - On', 
      'options' => array
      (
        'nvt' => 'n.v.t',
        'next' => 'Next',
        'previous' => 'Previous',
        'first' => 'First',
        'last' => 'Last',
      )
    ),
    'leave_request_day_of_the_week' => array('title_en_US' => 'Leave - Request - Day of the week',
      'options' => array
      (
        'nvt' => 'n.v.t.',
        'Monday' => 'Monday',
        'Tuesday' => 'Tuesday',
        'Wednesday' => 'Wednesday',
        'Thursday' => 'Thursday',
        'Friday' => 'Friday',
        'Saturday' => 'Saturday',
        'Sunday' => 'Sunday',
      )
    ),
  );
  
  private $error_platform = 'civicrm';
  private $error = false;
  
  private $cids = array();
      
  private $employees = array();
  private $departments = array();
  private $department_heads = array();
  private $business = array();
  private $main_business = array();
  
  private $is_department_head = false;
  private $department_head_dids = false;
  private $department_head_collids = array();
  
  private $is_administration = false;
  private $administration_aids = false;
  private $administration_collids = array();
  
  private $settings = array();
  private $collids = array();
  private $dphids = array();
  
  private $data = array(); 
  private $months = array();
  private $years = array();  
    
  private $weight = array
  (
    'sick' => 1,
    'sick_less_one_day' => 2,
    'maternity' => 3,
    'care' => 4,
    'study_leave' => 5,
    'mom_dad_day' => 6,
    'doctor_visit' => 7,
    'atv' => 8,
    'special_leave' => 9,
    'normal_leave' => 1,
    'normal_leave_less_one_day' => 11,
  );
    
  private $request = array();
  private $department_head_request = array();
  
  private $total = array();
  
  public function __construct($error_platform = 'civicrm')
  {    
    if('' == $error_platform){
      echo('No error platform !');
      return false;
    }
    
    $this->set_error_platform($error_platform);
        
    if(!$this->set_custom_groups()){
      $this->set_error( ts('An error occur in custom groups !'), ts('Construct'));
      return false;
    }
    
    if(!$this->set_custom_fields()){
      $this->set_error( ts('An error occur in custom fields !'), ts('Construct'));
      return false;
    }
    
    if(!$this->set_options_groups()){
      $this->set_error( ts('An error occur in options groups !'), ts('Construct'));
      return false;
    }
  }
  
  public function set_contacts($cid, $user_id)
  {    
    if(!$this->set_cid($cid)){
      $this->set_error( ts('An error occur in contact id !'), ts('Contacts'));
      return false;
    }

    if(!$this->set_employees()){
      $this->set_error( ts('An error occur in employees !'), ts('Contacts'));
      return false;
    }
    
    if(!$this->set_department()){
      $this->set_error( ts('An error occur in department !'), ts('Contacts'));
      return false;
    }
    
    if(!$this->set_business()){
      $this->set_error( ts('An error occur in business !'), ts('Contacts'));
      return false;
    }
    
    if(!$this->set_department_head()){
      $this->set_error( ts('An error occur in department head !'), ts('Contacts'));
      return false;
    }
    
    if(!$this->set_main_business()){
      $this->set_error( ts('An error occur in main business !'), ts('Contacts'));
      return false;
    }
        
    if(!$this->set_department_head_collids($user_id)){
      $this->set_error( ts('An error occur in collids deparetment head !'), ts('Contacts'));
      return false;
    }
    
    if(!$this->set_administration_collids($user_id)){
      $this->set_error( ts('An error occur in collids administration !'), ts('Contacts'));
      return false;
    }
    
    if(!$this->set_settings()){
      $this->set_error( ts('An error occur in settings !'), ts('Contacts'));
      return false;
    } 
    
    switch($this->settings[$cid]['show_all_colleagues'])
    {        
      case 'business':
        if(!$this->set_business_ids()){
          $this->set_error( ts('An error occur in do colleague ids !'), ts('Contacts'));
          return false;
        }
        break;
      
       case 'main_business':
        if(!$this->set_main_business_ids()){
          $this->set_error( ts('An error occur in do colleague ids !'), ts('Contacts'));
          return false;
        }
        break;
        
      default:
        if(!$this->set_department_ids()){
          $this->set_error( ts('An error occur in department ids !'), ts('Contacts'));
          return false;
        }
        break;
    }
    
    if($this->settings[$cid]['show_department_head']){
      if(!$this->set_department_head_ids()){
        $this->set_error( ts('An error occur in department head ids !'), ts('Contacts'));
        return false;
      }
    }
    
    return true;
  }
  
  private function set_custom_groups()
  {    
    $query = "SELECT id, name, table_name, extends_entity_column_value FROM civicrm_custom_group";
    $query .= " WHERE";
    
    $where = "";
    foreach($this->custom_groups as $id => $custom_group){
      $where .= " OR name = '" . $custom_group['name'] . "'";
    }
        
    $query .= substr($where, 3);
            
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      foreach($this->custom_groups as $id => $custom_group){
        if($custom_group['name'] == $dao->name){          
          $this->custom_groups[$id]['id'] = $dao->id; 
          $this->custom_groups[$id]['table_name'] = $dao->table_name; 
          $this->custom_groups[$id]['extends_entity_column_value'] = str_replace('', '', $dao->extends_entity_column_value); 
        }
      }
    }
    
    // check
    foreach($this->custom_groups as $id => $custom_group){
      if(!isset($custom_group['id']) or '' == $custom_group['id']){
        $this->set_error( ts('The group with name ') . $custom_group['name'] . ts(' has no id !'), ts('Custom group'));
      }
      
      if(!isset($custom_group['table_name']) or '' == $custom_group['table_name']){
        $this->set_error( ts('The group with name ') . $custom_group['name'] . ts(' has no table name !'), ts('Custom group'));
      }
      
      if(!isset($custom_group['extends_entity_column_value']) or '' == $custom_group['extends_entity_column_value']){
        $this->set_error( ts('The group with name ') . $custom_group['name'] . ts(' has no extends entity column value !'), ts('Custom group'));
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  private function set_custom_fields()
  {
    $query = "SELECT name, custom_group_id, column_name FROM civicrm_custom_field";
    $query .= " WHERE";
           
    $where = "";
    foreach($this->custom_fields as $id => $custom_field){
      $where .= " OR name = '" . $custom_field['name'] . "'";
    }
    
    $query .= substr($where, 3);
    
    $dao = CRM_Core_DAO::executeQuery($query);    
    while($dao->fetch()){
      foreach($this->custom_fields as $id => $custom_field){

        $custom_group_field = '';
        foreach($this->custom_groups_fields as $custom_groups_field => $array){
          if(in_array($id, $array)){
            $custom_group_field = $custom_groups_field;
          }
        }
        
        if(isset($this->custom_groups[$custom_group_field]['id'])){
          $custom_group_id = $this->custom_groups[$custom_group_field]['id'];
            
          if($custom_field['name'] == $dao->name and $custom_group_id == $dao->custom_group_id){
            $this->custom_fields[$id]['column_name'] = $dao->column_name; 
          }
        }
      }
    }
    
    foreach($this->custom_fields as $id => $custom_field){
      if(!isset($custom_field['column_name']) or '' == $custom_field['column_name']){
        $this->set_error( ts('The field with name ') . $custom_field['name'] . ts(' has no column name !'), ts('Custom fields'));
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
    
  private function set_options_groups()
  {
    $query = "SELECT civicrm_option_value.value, civicrm_option_value.label, civicrm_option_group.title FROM civicrm_option_value";
    $query .= " INNER JOIN civicrm_option_group ON civicrm_option_value.option_group_id = civicrm_option_group.id";
    $query .= " WHERE";
    
    $where = "";
    foreach($this->option_group as $key => $option_group){
      $where .= " OR civicrm_option_group.title = '" . $option_group['title_en_US'] . "'";
    }
    
    $query .= substr($where, 3);
    $query .= " ORDER BY civicrm_option_group.title, civicrm_option_value.weight ASC";
    
    $options = array();
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      $options[$dao->title][$dao->value] = $dao->label;
    }
    
    foreach($this->option_group as $key => $option_group){
      if(isset($options[$option_group['title_en_US']]) and !empty($options[$option_group['title_en_US']])){
        foreach($options[$option_group['title_en_US']] as $value => $label){
          if(!isset($this->option_group[$key]['options'][$value]) or '' == $this->option_group[$key]['options']){
            $this->option_group[$key]['options'][$value] = $label;
          }
        }
        //$this->option_group[$key]['options'] = $options[$option_group['title_en_US']];
      }
    }
    
    foreach($this->option_group as $key => $option_group){
      if(!isset($option_group['options']) or empty($option_group['options'])){
        $this->set_error( ts('the option group with name ') . $option_group['title_en_US'] . ts(' has no options !'), ts('Options groups'));
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }

  public function __set($name, $value)
  {
    if($this->__isset($name)){
      $this->{$name} = $value;
    }
  }
  
  public function __get($name) 
  {
    if($this->__isset($name)){
      return $this->{$name};
    }
  }
  
  public function __isset($name) 
  {
    return isset($this->{$name});
  }
  
  public function __unset($name)
  {
    if($this->__isset($name)){
      unset($this->{$name});
    }
  }
  
  public function set_error_platform($error_platform = 'civicrm')
  {
    $this->error_platform = $error_platform;
  }
  
  private function set_error($text, $function)
  {
    $this->error = true;
    
    switch($this->error_platform)
    {
      case 'drupal':
        drupal_set_message( ts($text) . ' (' . ts('leave registration class, ') . ' ' . $function . ')', 'error');
        break;
      case 'echo':
        echo(ts($text) . ' (' . ts('leave registration class, ') . ' ' . $function . ')');
        break;
      default:
        CRM_Core_Session::setStatus( ts($text) . ' (' . ts('leave registration class, ') . ' ' . $function . ')', ts('leave registration class, ') . ' ' . $function, 'error');
    }
  }
    
  public function isset_error()
  {
    return $this->error;
  }
  
  private function set_cid($cid)
  {
    $this->cids[$cid] = array();
    $this->employees[$cid] = array();
    
    if(empty($this->cids)){
      $this->set_error(ts('No contact ids !'), ts('Contact ids'));
      return false;
    }
    
    if(empty($this->employees)){
      $this->set_error(ts('No contact ids !'), ts('Contact ids'));
      return false;
    }
        
    return true;
  }
    
  private function set_employees()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->cids)){
      $this->set_error(ts('No contact ids !'), ts('Employees'));
      return false;
    }
    
    $query = "SELECT CC.id, CC.contact_type, CC.contact_sub_type, CC.display_name, CE.email";
    $query .= " FROM civicrm_contact as CC";
    $query .= " LEFT JOIN civicrm_email as CE ON CC.id = CE.contact_id";
    
    $where = "";

    foreach($this->cids as $cid => $cids){
      $where .= " OR CC.id = '" . $cid . "'"; 
    }
    
    $query .= " WHERE " . substr($where, 3);
    
    // filter on contact_type and contact_sub_type, in case of a empty contact
    $query .= " AND CC.contact_type = '" . $this->contact_sub_types['employee']['contact_type'] . "'";
    $query .= " AND CC.contact_sub_type = '" . $this->contact_sub_types['employee']['contact_sub_type'] . "'";
        
    $dao = CRM_Core_DAO::executeQuery($query);    
    while($dao->fetch()){
      $this->employees[$dao->id]['contact_type'] = $dao->contact_type;
      $this->employees[$dao->id]['contact_sub_type'] = $dao->contact_sub_type;
      $this->employees[$dao->id]['display_name'] = $dao->display_name;
      $this->employees[$dao->id]['email'] = $dao->email;
    }
        
    // check
    foreach($this->employees as $id => $employee){
      if(!isset($employee['display_name']) or '' == $employee['display_name']){
        $this->set_error(ts('Employee with id ') . $id . ts(' has no display name !'), ts('Employees'));
      }
      
      if(!isset($employee['email']) or '' == $employee['email']){
        $this->set_error(ts('Employee with id ') . $id . ts(' has no email !'), ts('Employees'));
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  private function set_department($do_collids = false, $do_dphids = false)
  {
    if($this->isset_error()){
      return false;
    }
    
    if(!$do_collids and !$do_dphids){
      if(empty($this->cids)){
        $this->set_error(ts('No contact ids !'), ts('Department'));
        return false;
      }
    }
    
    if($do_collids){
      if(empty($this->collids)){
        $this->set_error(ts('No contact colleagues ids !'), ts('Department'));
        return false;
      }
    }
    
    if($do_dphids){
      if(empty($this->dphids)){
        $this->set_error(ts('No contact department heads ids !'), ts('Department'));
        return false;
      }
    }
    
    $query = "SELECT civicrm_contact.id, civicrm_contact.contact_type, civicrm_contact.contact_sub_type, civicrm_contact.display_name, civicrm_relationship.contact_id_a";
    $query .= " FROM civicrm_contact";
    $query .= " LEFT JOIN civicrm_relationship ON civicrm_contact.id = civicrm_relationship.contact_id_b";
    $query .= " LEFT JOIN civicrm_relationship_type ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";
    
    $query .= " WHERE civicrm_contact.contact_type = '" . $this->contact_sub_types['department']['contact_type'] . "'";
    $query .= " AND civicrm_contact.contact_sub_type = '" . $this->contact_sub_types['department']['contact_sub_type'] . "'";
    $query .= " AND civicrm_relationship_type.name_a_b = '" . $this->relationship_types['employee_of']['name_a_b'] . "'";   
        
    $where = '';
      
    
    if(!$do_collids and !$do_dphids){
      foreach($this->cids as $cid => $cids){
        $where .= "OR civicrm_relationship.contact_id_a = '" . $cid . "'";
      }
    }
    
    if($do_collids){
      foreach($this->collids as $cid => $cids){
        $where .= "OR civicrm_relationship.contact_id_a = '" . $cid . "'";  
      }
    }
    
    if($do_dphids){
      foreach($this->dphids as $dphid => $dphids){
        $where .= "OR civicrm_relationship.contact_id_a = '" . $dphid . "'";  
      }
    }
    
    $query .= " AND (" . substr($where, 3) . ")";
    
    // filter on contact_type and contact_sub_type, in case of a empty contact    
    $query .= " AND civicrm_contact.contact_type = '" . $this->contact_sub_types['department']['contact_type'] . "'";
    $query .= " AND civicrm_contact.contact_sub_type = '" . $this->contact_sub_types['department']['contact_sub_type'] . "'";
        
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $this->departments[$dao->id] = array();
      $this->departments[$dao->id]['contact_type'] = $dao->contact_type;
      $this->departments[$dao->id]['contact_sub_type'] = $dao->contact_sub_type;
      $this->departments[$dao->id]['display_name'] = $dao->display_name;
      
      $this->employees[$dao->contact_id_a]['department'] = array();
      $this->employees[$dao->contact_id_a]['department']['id'] = $dao->id; // attach department id to employee
    }
        
    // check if all cids has a department
    foreach($this->cids as $cid => $cids){
      if(!isset($this->employees[$cid]['department']['id']) or '' == $this->employees[$cid]['department']['id']){
        $this->set_error( ts('Employee with the name ') . $this->employees[$cid]['display_name'] . ts(' has no department !'), ts('Department'));
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  private function set_business()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('Business'));
      return false;
    }

    $query = "SELECT civicrm_contact.id, civicrm_contact.contact_type, civicrm_contact.contact_sub_type, civicrm_contact.display_name, civicrm_relationship.contact_id_a";
    $query .= " FROM civicrm_relationship";
    $query .= " LEFT JOIN civicrm_contact ON civicrm_relationship.contact_id_b = civicrm_contact.id";
    $query .= " LEFT JOIN civicrm_relationship_type  ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";
    $query .= " WHERE civicrm_relationship_type.name_a_b = '" . $this->relationship_types['department_of']['name_a_b'] . "'";
    
    $where = '';
    foreach($this->departments as $did => $department){
      $where .= " OR civicrm_relationship.contact_id_a = '" . $did . "'";
    }
    $query .= " AND (" . substr($where, 3) . ")";
    
    // filter on contact_type and contact_sub_type, in case of a empty contact    
    $query .= " AND civicrm_contact.contact_type = '" . $this->contact_sub_types['business']['contact_type'] . "'";
    $query .= " AND civicrm_contact.contact_sub_type = '" . $this->contact_sub_types['business']['contact_sub_type'] . "'";
         
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $this->business[$dao->id] = array();
      $this->business[$dao->id]['contact_type'] = $dao->contact_type;
      $this->business[$dao->id]['contact_sub_type'] = $dao->contact_sub_type;
      $this->business[$dao->id]['display_name'] = $dao->display_name;
      
      // add business to employee
      foreach($this->employees as $cid => $employee){
        if($employee['department']['id'] == $dao->contact_id_a){
          $this->employees[$cid]['business'] = array();
          $this->employees[$cid]['business']['id'] = $dao->id;
        }
      }
    }
    
    // check if all cids has a department
    foreach($this->cids as $cid => $cids){
      if(!isset($this->employees[$cid]['business']['id']) or '' == $this->employees[$cid]['business']['id']){
        $this->set_error( ts('Employee with the name ') . $this->employees[$cid]['display_name'] . ts(' has no department !'), ts('Business'));
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
    
  private function set_department_head()
  {    
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('Department head'));
      return false;
    }
    
    $query = "SELECT civicrm_contact.id, civicrm_contact.contact_type, civicrm_contact.contact_sub_type, civicrm_contact.display_name, civicrm_relationship.contact_id_a, civicrm_email.email";
    $query .= " FROM civicrm_relationship";
    $query .= " LEFT JOIN civicrm_contact ON civicrm_relationship.contact_id_b = civicrm_contact.id";
    $query .= " LEFT JOIN civicrm_relationship_type  ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";
    $query .= " LEFT JOIN civicrm_email ON civicrm_contact.id = civicrm_email.contact_id";
    $query .= " WHERE civicrm_relationship_type.name_a_b = '" . $this->relationship_types['department_head']['name_a_b'] . "'";
    
    $where = '';
    foreach($this->departments as $did => $department){
      $where .= " OR civicrm_relationship.contact_id_a = '" . $did . "'";
    }
    $query .= " AND (" . substr($where, 3) . ")";
        
    // filter on contact_type and contact_sub_type, in case of a empty contact    
    $query .= " AND civicrm_contact.contact_type = '" . $this->contact_sub_types['employee']['contact_type'] . "'";
    $query .= " AND civicrm_contact.contact_sub_type = '" . $this->contact_sub_types['employee']['contact_sub_type'] . "'";    
        
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $this->department_heads[$dao->id] = array();
      $this->department_heads[$dao->id]['contact_type'] = $dao->contact_type;
      $this->department_heads[$dao->id]['contact_sub_type'] = $dao->contact_sub_type;
      $this->department_heads[$dao->id]['display_name'] = $dao->display_name;
      $this->department_heads[$dao->id]['email'] = $dao->email;
      
      $this->departments[$dao->contact_id_a]['department_head'] = array();
      $this->departments[$dao->contact_id_a]['department_head']['id'] = $dao->id; // attach department head to deparment
                  
      // add department head to employee
      foreach($this->employees as $cid => $employee){
        if($employee['department']['id'] == $dao->contact_id_a){
          $this->employees[$cid]['department_head'] = array();
          $this->employees[$cid]['department_head']['id'] = $dao->id;
        }
      }
    }
    
    // check if all departments has a department head
    foreach($this->departments as $did => $department){
      if(!isset($this->departments[$did]['department_head']['id']) or '' == $this->departments[$did]['department_head']['id']){
        $this->set_error( ts('The department with name ') . $this->departments[$did]['display_name'] . ts(' has no department head !'), ts('Department head'));
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  private function set_main_business()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->business)){
      $this->set_error(ts('No business are set !'), ts('Main business'));
      return false;
    }
    
    $query = "SELECT civicrm_contact.id, civicrm_contact.contact_type, civicrm_contact.contact_sub_type, civicrm_contact.display_name, civicrm_relationship.contact_id_a";
    $query .= " FROM civicrm_relationship";
    $query .= " LEFT JOIN civicrm_contact ON civicrm_relationship.contact_id_b = civicrm_contact.id";
    $query .= " LEFT JOIN civicrm_relationship_type  ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";
    $query .= " WHERE civicrm_relationship_type.name_a_b = '" . $this->relationship_types['main_organization']['name_a_b'] . "'";
    
    $where = '';
    foreach($this->business as $bid => $bunsiness){
      $where .= " OR civicrm_relationship.contact_id_a = '" . $bid . "'";
    }
    $query .= " AND (" . substr($where, 3) . ")";
    
    // filter on contact_type and contact_sub_type, in case of a empty contact    
    $query .= " AND civicrm_contact.contact_type = '" . $this->contact_sub_types['business']['contact_type'] . "'";
    $query .= " AND civicrm_contact.contact_sub_type = '" . $this->contact_sub_types['business']['contact_sub_type'] . "'";
        
    $dao = CRM_Core_DAO::executeQuery($query);   
    while($dao->fetch()){
      
      $this->main_business[$dao->id] = array();
      $this->main_business[$dao->id]['contact_type'] = $dao->contact_type;
      $this->main_business[$dao->id]['contact_sub_type'] = $dao->contact_sub_type;
      $this->main_business[$dao->id]['display_name'] = $dao->display_name;
      
      $this->business[$dao->contact_id_a]['main_business'] = array();
      $this->business[$dao->contact_id_a]['main_business']['id'] = $dao->id; // attach department head to deparment
            
      // add main business to employee
      foreach($this->employees as $cid => $employee){
        if($employee['business']['id'] == $dao->contact_id_a){
          $this->employees[$cid]['main_business'] = array();
          $this->employees[$cid]['main_business']['id'] = $dao->id;
        }
        
        /*
        // if the employee is linked derectly to the main business
        if($employee['business']['id'] == $dao->id){
          $this->employees[$cid]['main_business'] = array();
          $this->employees[$cid]['main_business']['id'] = $dao->id;
        }
        
         */
      }
    }
    
    return true;
  }
  
  private function set_department_head_collids($current_cid)
  {
    if($this->isset_error()){
      return false;
    }
        
    if(0 == $current_cid or '' == $current_cid){
      $this->set_error(ts('No current contact id !'), ts('Department head colleagues ids'));
    }
    
    if($this->isset_error()){
      return false;
    }
    
    // check if current cid is departement head
    $query = "SELECT civicrm_contact.id, civicrm_contact.contact_type, civicrm_contact.contact_sub_type, civicrm_contact.display_name, civicrm_relationship.contact_id_b";
    $query .= " FROM civicrm_relationship";
    $query .= " LEFT JOIN civicrm_contact ON civicrm_relationship.contact_id_a = civicrm_contact.id";
    $query .= " LEFT JOIN civicrm_relationship_type  ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";
    $query .= " WHERE civicrm_relationship_type.name_a_b = '" . $this->relationship_types['department_head']['name_a_b'] . "'";
    $query .= " AND civicrm_relationship.contact_id_b = '" . $current_cid . "'";     
    
    // filter on contact_type and contact_sub_type, in case of a empty contact    
    $query .= " AND civicrm_contact.contact_type = '" . $this->contact_sub_types['department']['contact_type'] . "'";
    $query .= " AND civicrm_contact.contact_sub_type = '" . $this->contact_sub_types['department']['contact_sub_type'] . "'";
    
    $query .= " ORDER BY civicrm_contact.display_name ASC";
        
    $this->department_head_dids = array();
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $this->department_head_dids[$dao->id] = array();
      $this->department_head_dids[$dao->id]['contact_type'] = $dao->contact_type;
      $this->department_head_dids[$dao->id]['contact_sub_type'] = $dao->contact_sub_type;
      $this->department_head_dids[$dao->id]['display_name'] = $dao->display_name;
      $this->department_head_dids[$dao->id]['didh'] = $dao->contact_id_b;
      
      $this->is_department_head[$dao->contact_id_b] = true;
    }
    
    // get all colleagues from department head
    if(!empty($this->department_head_dids)){
      $query = "SELECT civicrm_contact.id, civicrm_contact.contact_type, civicrm_contact.contact_sub_type, civicrm_contact.display_name, civicrm_relationship.contact_id_b, civicrm_email.email";
      $query .= " FROM civicrm_contact";
      $query .= " LEFT JOIN civicrm_relationship ON civicrm_contact.id = civicrm_relationship.contact_id_a";
      $query .= " LEFT JOIN civicrm_relationship_type ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";
      $query .= " LEFT JOIN civicrm_email ON civicrm_contact.id = civicrm_email.contact_id";
      $query .= " WHERE civicrm_relationship_type.name_a_b = '" . $this->relationship_types['employee_of']['name_a_b'] . "'";   

      $where = '';
      foreach($this->department_head_dids as $did => $dids){
        $where .= "OR civicrm_relationship.contact_id_b = '" . $did . "'";
      }

      $query .= " AND (" . substr($where, 3) . ")";
      
      // filter on contact_type and contact_sub_type, in case of a empty contact    
      $query .= " AND civicrm_contact.contact_type = '" . $this->contact_sub_types['employee']['contact_type'] . "'";
      $query .= " AND civicrm_contact.contact_sub_type = '" . $this->contact_sub_types['employee']['contact_sub_type'] . "'";
      
      $query .= " ORDER BY civicrm_contact.display_name ASC";
      
      $dao = CRM_Core_DAO::executeQuery($query);
      while($dao->fetch()){

        $this->department_head_collids[$dao->id] = array();
        $this->department_head_collids[$dao->id]['contact_type'] = $dao->contact_type;
        $this->department_head_collids[$dao->id]['contact_sub_type'] = $dao->contact_sub_type;
        $this->department_head_collids[$dao->id]['display_name'] = $dao->display_name;
        $this->department_head_collids[$dao->id]['email'] = $dao->email;
      }
    }
    
    return true;
  }
  
  private function set_administration_collids($current_cid)
  {
    if($this->isset_error()){
      return false;
    }
        
    if(0 == $current_cid or '' == $current_cid){
      $this->set_error(ts('No current contact id !'), ts('Administration colleagues ids'));
    }
    
    if($this->isset_error()){
      return false;
    }
    
    // check if current cid is departement head
    $query = "SELECT civicrm_contact.id, civicrm_contact.contact_type, civicrm_contact.contact_sub_type, civicrm_contact.display_name, civicrm_relationship.contact_id_b";
    $query .= " FROM civicrm_relationship";
    $query .= " LEFT JOIN civicrm_contact ON civicrm_relationship.contact_id_a = civicrm_contact.id";
    $query .= " LEFT JOIN civicrm_relationship_type  ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";
    $query .= " WHERE civicrm_relationship_type.name_a_b = '" . $this->relationship_types['administration_of']['name_a_b'] . "'";
    $query .= " AND civicrm_relationship.contact_id_a = '" . $current_cid . "'";     
    
    // filter on contact_type and contact_sub_type, in case of a empty contact    
    $query .= " AND civicrm_contact.contact_type = '" . $this->contact_sub_types['employee']['contact_type'] . "'";
    $query .= " AND civicrm_contact.contact_sub_type = '" . $this->contact_sub_types['employee']['contact_sub_type'] . "'";
    
    $query .= " ORDER BY civicrm_contact.display_name ASC";
    
    $this->administration_aids = array();
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $this->administration_aids[$dao->contact_id_b] = array();
      $this->administration_aids[$dao->contact_id_b]['contact_type'] = $dao->contact_type;
      $this->administration_aids[$dao->contact_id_b]['contact_sub_type'] = $dao->contact_sub_type;
      $this->administration_aids[$dao->contact_id_b]['display_name'] = $dao->display_name;
      $this->administration_aids[$dao->contact_id_b]['aid'] = $dao->id;
      
      $this->is_administration[$dao->id] = true;
    }
    
    // get all colleagues from administration
    if(!empty($this->administration_aids)){
      
      // business
      $query = "SELECT civicrm_contact.id, civicrm_contact.contact_type, civicrm_contact.contact_sub_type, civicrm_contact.display_name, civicrm_relationship.contact_id_b, civicrm_email.email";
      $query .= " FROM civicrm_contact";

      $query .= " LEFT JOIN civicrm_relationship ON civicrm_contact.id = civicrm_relationship.contact_id_a";
      $query .= " LEFT JOIN civicrm_relationship_type ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";
      $query .= " LEFT JOIN civicrm_email ON civicrm_contact.id = civicrm_email.contact_id";

      $query .= " WHERE civicrm_relationship_type.name_a_b = 'Employee of'";


      $query .= " AND (civicrm_relationship.contact_id_b IN (";

      $query .= " SELECT civicrm_contact.id";
      $query .= " FROM civicrm_contact";

      $query .= " LEFT JOIN civicrm_relationship ON civicrm_contact.id = civicrm_relationship.contact_id_a";
      $query .= " LEFT JOIN civicrm_relationship_type ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";

      $query .= " WHERE civicrm_relationship_type.name_a_b = 'Department of'";


      $query .= " AND (civicrm_relationship.contact_id_b IN (";

      $query .= " SELECT civicrm_relationship.contact_id_b";
      $query .= " FROM civicrm_contact";

      $query .= " LEFT JOIN civicrm_relationship ON civicrm_contact.id = civicrm_relationship.contact_id_a";
      $query .= " LEFT JOIN civicrm_relationship_type ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";

      $query .= " WHERE civicrm_relationship_type.name_a_b = 'Administration of'";

      $where = '';
      foreach($this->administration_aids as $mbid => $mbids){
        $where .= "OR civicrm_relationship.contact_id_b = '" . $mbid . "'";
      }

      $query .= " AND (" . substr($where, 3) . ")";

      $query .= "))))";

      // filter on contact_type and contact_sub_type, in case of a empty contact    
      $query .= " AND civicrm_contact.contact_type = '" . $this->contact_sub_types['employee']['contact_type'] . "'";
      $query .= " AND civicrm_contact.contact_sub_type = '" . $this->contact_sub_types['employee']['contact_sub_type'] . "'";
      
      $query .= " ORDER BY civicrm_contact.display_name ASC";
      
      $dao = CRM_Core_DAO::executeQuery($query);
      
      while($dao->fetch()){
        $this->administration_collids[$dao->id] = array();
        $this->administration_collids[$dao->id]['contact_type'] = $dao->contact_type;
        $this->administration_collids[$dao->id]['contact_sub_type'] = $dao->contact_sub_type;
        $this->administration_collids[$dao->id]['display_name'] = $dao->display_name;
        $this->administration_collids[$dao->id]['email'] = $dao->email;
      }
      
      
      // main business
      $query = "SELECT civicrm_contact.id, civicrm_contact.contact_type, civicrm_contact.contact_sub_type, civicrm_contact.display_name, civicrm_relationship.contact_id_b, civicrm_email.email";
      $query .= " FROM civicrm_contact";

      $query .= " LEFT JOIN civicrm_relationship ON civicrm_contact.id = civicrm_relationship.contact_id_a";
      $query .= " LEFT JOIN civicrm_relationship_type ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";
      $query .= " LEFT JOIN civicrm_email ON civicrm_contact.id = civicrm_email.contact_id";

      $query .= " WHERE civicrm_relationship_type.name_a_b = 'Employee of'";


      $query .= " AND (civicrm_relationship.contact_id_b IN (";

      $query .= " SELECT civicrm_contact.id";
      $query .= " FROM civicrm_contact";

      $query .= " LEFT JOIN civicrm_relationship ON civicrm_contact.id = civicrm_relationship.contact_id_a";
      $query .= " LEFT JOIN civicrm_relationship_type ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";

      $query .= " WHERE civicrm_relationship_type.name_a_b = 'Department of'";


      $query .= " AND (civicrm_relationship.contact_id_b IN (";

      $query .= " SELECT civicrm_contact.id";
      $query .= " FROM civicrm_contact";

      $query .= " LEFT JOIN civicrm_relationship ON civicrm_contact.id = civicrm_relationship.contact_id_a";
      $query .= " LEFT JOIN civicrm_relationship_type ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";

      $query .= " WHERE civicrm_relationship_type.name_a_b = 'Main Organization of'";


      $query .= " AND (civicrm_relationship.contact_id_b IN (";

      $query .= " SELECT civicrm_relationship.contact_id_b";
      $query .= " FROM civicrm_contact";

      $query .= " LEFT JOIN civicrm_relationship ON civicrm_contact.id = civicrm_relationship.contact_id_a";
      $query .= " LEFT JOIN civicrm_relationship_type ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";

      $query .= " WHERE civicrm_relationship_type.name_a_b = 'Administration of'";

      $where = '';
      foreach($this->administration_aids as $mbid => $mbids){
        $where .= "OR civicrm_relationship.contact_id_b = '" . $mbid . "'";
      }

      $query .= " AND (" . substr($where, 3) . ")";

      $query .= "))))))";
      
      // filter on contact_type and contact_sub_type, in case of a empty contact    
      $query .= " AND civicrm_contact.contact_type = '" . $this->contact_sub_types['employee']['contact_type'] . "'";
      $query .= " AND civicrm_contact.contact_sub_type = '" . $this->contact_sub_types['employee']['contact_sub_type'] . "'";
      
      $query .= "ORDER BY civicrm_contact.display_name ASC";
      
      $dao = CRM_Core_DAO::executeQuery($query);
      
      while($dao->fetch()){

        $this->administration_collids[$dao->id] = array();
        $this->administration_collids[$dao->id]['contact_type'] = $dao->contact_type;
        $this->administration_collids[$dao->id]['contact_sub_type'] = $dao->contact_sub_type;
        $this->administration_collids[$dao->id]['display_name'] = $dao->display_name;
        $this->administration_collids[$dao->id]['email'] = $dao->email;
      }
    }
    
    return true;
  }
  
  private function set_settings()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->employees)){
      $this->set_error(ts('No employees !'), ts('Credit'));
    }
        
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT * FROM " . $this->custom_groups['leave_settings']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->employees as $cid => $employee){
      $where .= " OR entity_id = '" . $cid . "'";
    }
    
    $query .= " (" . substr($where, 3) . ")";
    
    $dao = CRM_Core_DAO::executeQuery($query);
    
    while($dao->fetch()){
      if(!isset($this->settings[$dao->entity_id])){
        $this->settings[$dao->entity_id] = array();
      }
      
      $this->settings[$dao->entity_id]['show_all_colleagues'] = $dao->{$this->custom_fields['leave_settings_show_all_colleagues_of']['column_name']};
      $this->settings[$dao->entity_id]['show_department_head'] = $dao->{$this->custom_fields['leave_settings_show_department_head_to']['column_name']};
    }
    
    // set to default if not exists
    foreach($this->employees as $cid => $employee){
      if(!isset($this->settings[$cid])){
        $this->settings[$cid] = array();
      }
      
      if(!isset($this->settings[$cid]['show_all_colleagues'])){
        $this->settings[$cid]['show_all_colleagues'] = $this->custom_fields['leave_settings_show_all_colleagues_of']['default_value'];
      }
      
      if(!isset($this->settings[$cid]['show_department_head'])){
        $this->settings[$cid]['show_department_head'] = $this->custom_fields['leave_settings_show_department_head_to']['default_value'];
      }
    }
    
    return true;
  }
  
  private function set_department_ids()
  {    
    if($this->isset_error()){
      return false;
    }
        
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('colleague ids'));
    }
    
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT civicrm_contact.id, civicrm_contact.contact_type, civicrm_contact.contact_sub_type, civicrm_contact.display_name, civicrm_relationship.contact_id_b, civicrm_email.email";
    $query .= " FROM civicrm_relationship";
    $query .= " LEFT JOIN civicrm_contact ON civicrm_relationship.contact_id_a = civicrm_contact.id";
    $query .= " LEFT JOIN civicrm_relationship_type  ON civicrm_relationship.relationship_type_id = civicrm_relationship_type.id";
    $query .= " LEFT JOIN civicrm_email ON civicrm_contact.id = civicrm_email.contact_id";
    $query .= " WHERE civicrm_relationship_type.name_a_b = '" . $this->relationship_types['employee_of']['name_a_b'] . "'";
    
    $where = '';
    foreach($this->departments as $did => $department){
      $where .= " OR civicrm_relationship.contact_id_b = '" . $did . "'";
    }
    $query .= " AND (" . substr($where, 3) . ")"; 
        
    // filter on contact_type and contact_sub_type, in case of a empty contact    
    $query .= " AND civicrm_contact.contact_type = '" . $this->contact_sub_types['employee']['contact_type'] . "'";
    $query .= " AND civicrm_contact.contact_sub_type = '" . $this->contact_sub_types['employee']['contact_sub_type'] . "'";
        
    $query .= " ORDER BY civicrm_contact.display_name ASC";    
        
    $dao = CRM_Core_DAO::executeQuery($query);    
    while($dao->fetch()){   
      if(!isset($this->collids[$dao->id])){
        $this->collids[$dao->id] = $dao->id;
      }
      
      // add coleague to employee
      if(!isset($this->employees[$dao->id]) or empty($this->employees[$dao->id])){
        $this->employees[$dao->id]['contact_type'] = $dao->contact_type;
        $this->employees[$dao->id]['contact_sub_type'] = $dao->contact_sub_type;
        $this->employees[$dao->id]['display_name'] = $dao->display_name;
        $this->employees[$dao->id]['email'] = $dao->email;
      }
    }
    
    // re do department
    if(!$this->set_department(true)){
      $this->set_error( ts('An error occur in department !'), ts('Department head ids'));
      return false;
    }
    
    if(!$this->set_business()){
      $this->set_error( ts('An error occur in business !'), ts('Department head ids'));
      return false;
    }
    
    if(!$this->set_department_head()){
      $this->set_error( ts('An error occur in department head !'), ts('Department head ids'));
      return false;
    }
    
    if(!$this->set_main_business()){
      $this->set_error( ts('An error occur in main business !'), ts('Department head ids'));
      return false;
    }
    
    return true;
  }
    
  /*
   * 
   */
  private function set_business_ids()
  {
    // get from every department head the id of the colleages    
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->business)){
      $this->set_error(ts('No business !'), ts('Business ids'));
      return false;
    }
    
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT CC.id, CC.contact_type, CC.contact_sub_type, CC.display_name, CR.contact_id_b, CR.contact_id_a, CE.email,";
    
    $query .= " ( SELECT CB.contact_id_b FROM civicrm_relationship AS CB";
    $query .= " WHERE CB.contact_id_a = CR.contact_id_b";
    $query .= " AND CB.relationship_type_id = (";
    
    //$query .= " SELECT id FROM civicrm_relationship_type WHERE name_a_b = 'Department of'";
    $query .= " SELECT id FROM civicrm_relationship_type WHERE name_a_b = '" . $this->relationship_types['department_of']['name_a_b'] . "'";
    
    $query .= " )";
    
    $query .= " ) as bid";
    
    $query .= " FROM civicrm_contact AS CC";
    $query .= " LEFT JOIN civicrm_relationship AS CR";
    $query .= " ON CC.id = CR.contact_id_a";
    $query .= " LEFT JOIN civicrm_email as CE ON CC.id = CE.contact_id";
    $query .= " WHERE CR.contact_id_a IN (";
    
    $query .= " SELECT CR2.contact_id_a";
    $query .= " FROM civicrm_relationship AS CR2";
    $query .= " WHERE CR2.contact_id_b IN (";
    
    $query .= " SELECT CR3.contact_id_a";
    $query .= " FROM civicrm_relationship AS CR3";
    $query .= " WHERE ";
    
    foreach($this->business as $bid => $business){
      $query .= " CR3.contact_id_b = '" . $bid . "' OR";
    }
    $query = substr($query, 0, -2); 
        
    $query .= " AND CC.contact_sub_type = '" . $this->contact_sub_types['employee']['contact_sub_type'] . "'";
    
    $query .= ")";
    $query .= ")";

    // filter on contact_type and contact_sub_type, in case of a empty contact    
    $query .= " AND CC.contact_type = '" . $this->contact_sub_types['employee']['contact_type'] . "'";
    $query .= " AND CC.contact_sub_type = '" . $this->contact_sub_types['employee']['contact_sub_type'] . "'";
    
    $query .= " ORDER BY CC.display_name ASC";
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){      
      if(!isset($this->collids[$dao->id])){
        $this->collids[$dao->id] = $dao->id;
      }
      
      if(!isset($this->employees[$dao->id]) or empty($this->employees[$dao->id])){
        $this->employees[$dao->id]['contact_type'] = $dao->contact_type;
        $this->employees[$dao->id]['contact_sub_type'] = $dao->contact_sub_type;
        $this->employees[$dao->id]['display_name'] = $dao->display_name;
        $this->employees[$dao->id]['email'] = $dao->email;
      }
    }
        
    // re do department
    if(!$this->set_department(true)){
      $this->set_error( ts('An error occur in department !'), ts('Business head ids'));
      return false;
    }
    
    if(!$this->set_business()){
      $this->set_error( ts('An error occur in business !'), ts('Business head ids'));
      return false;
    }
    
    if(!$this->set_department_head()){
      $this->set_error( ts('An error occur in department head !'), ts('Business head ids'));
      return false;
    }
    
    if(!$this->set_main_business()){
      $this->set_error( ts('An error occur in main business !'), ts('Business head ids'));
      return false;
    }
        
    return true;
  }
  
  private function set_department_head_ids()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->department_heads)){
      $this->set_error(ts('No department heads !'), ts('Department heads ids'));
      return false;
    }
    
    if($this->isset_error()){
      return false;
    }
    
    foreach($this->department_heads as $dphids => $department_head){
      $this->dphids[$dphids] = $dphids; 
      
      // add coleague to employee
      if(!isset($this->employees[$dphids]) or empty($this->employees[$dphids])){
        $this->employees[$dphids] = $department_head;
      }
    }
    
    // re do department
    if(!$this->set_department(false, true)){
      $this->set_error( ts('An error occur in department !'), ts('Department head ids'));
      return false;
    }
    
    if(!$this->set_business()){
      $this->set_error( ts('An error occur in business !'), ts('Department head ids'));
      return false;
    }
    
    if(!$this->set_department_head()){
      $this->set_error( ts('An error occur in department head !'), ts('Department head ids'));
      return false;
    }
    
    if(!$this->set_main_business()){
      $this->set_error( ts('An error occur in main business !'), ts('Department head ids'));
      return false;
    }
    
    return true;
  }
  
  public function set_data($years = array(), $months = array())
  {    
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->cids)){
      $this->set_error(ts('No contact ids !'), ts('Data'));
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('Data'));
    }
    
    if(empty($this->business)){
      $this->set_error(ts('No business !'), ts('Data'));
    }
    
    if($this->isset_error()){
      return false;
    }
    
    $this->data = array();
    
    foreach($this->cids as $cid => $cids){
      $this->data[$cid] = array();
    }
        
    if(!$this->add_collids()){
      $this->set_error( ts('An error occur in add colleague ids !'), ts('Data'));
      return false;
    }
    
    if($this->settings[$cid]['show_department_head']){
      if(!$this->add_dphids()){
        $this->set_error( ts('An error occur in add department head ids !'), ts('Data'));
        return false;
      }
    }
    
    if(!$this->set_years($years)){
      $this->set_error( ts('An error occur in years !'), ts('Data'));
      return false;
    }
    
    if(!$this->set_months($months)){
      $this->set_error( ts('An error occur in months !'), ts('Data'));
      return false;
    }
        
    if(!$this->set_days()){
      $this->set_error( ts('An error occur in days !'), ts('Data'));
      return false;
    }
   
    if(!$this->set_holidays()){
      $this->set_error( ts('An error occur in holidays !'), ts('Data'));
      return false;
    }
    
    if(!$this->set_general()){
      $this->set_error( ts('An error occur in general !'), ts('Data'));
      return false;
    }
    
    if(!$this->set_overall_adjustments()){
      $this->set_error( ts('An error occur in overall adjustments !'), ts('Data'));
      return false;
    }
    
    if(!$this->set_overall_credit()){
      $this->set_error( ts('An error occur in overall credits!'), ts('Data'));
      return false;
    }
    
    if(!$this->set_adjustments()){
      $this->set_error( ts('An error occur in adjustments !'), ts('Data'));
      return false;
    }    
    
    if(!$this->set_credit()){
      $this->set_error( ts('An error occur in credits !'), ts('Data'));
      return false;
    }  

    if(!$this->set_request()){
      $this->set_error( ts('An error occur in request !'), ts('Data'));
      return false;
    }  
    
    if(!$this->set_department_head_request()){
      $this->set_error( ts('An error occur in request !'), ts('Data'));
      return false;
    }  
    
    if(!$this->set_total()){
      $this->set_error( ts('An error occur in total !'), ts('Data'));
      return false;
    }
    
    return true;
  }
  
  private function add_collids()
  {
    if($this->isset_error()){
      return false;
    }   
    
    if(empty($this->collids)){
      $this->set_error( ts('No colleague ids !'), ts('Add Colleague ids'));
      return false;
    }
   
    foreach($this->collids as $cid => $cids){
      $this->data[$cid] = array();
    }
    
    return true;
  }
  
  private function add_dphids()
  {
    if($this->isset_error()){
      return false;
    }   
    
    if(empty($this->dphids)){
      $this->set_error( ts('No department head ids !'), ts('Add department head ids'));
      return false;
    }

    foreach($this->dphids as $dphid => $dphids){
      $this->data[$dphid] = array();
    }
    
    return true;
  }
    
  private function set_years($years = array())
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($years)){
      $years = array(date('Y'));
    }
    
    $this->years = $years;
    
    return true;
  }
  
  private function set_months($months = array())
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($months)){
      $months = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    }
    
    $this->months = $months;
    
    return true;
  }
  
  private function set_days()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('Days'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('Days'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('Days'));
    }
    
    if($this->isset_error()){
      return false;
    }
    
    foreach($this->data as $cid => $array){
      
      foreach($this->years as $year){
        $this->data[$cid][$year] = array();
        
        foreach($this->months as $month){
          $this->data[$cid][$year][$month] = array();
          
          for($day = 1; $day <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $day++){
            $timestamp = strtotime($year . '-' . $month . '-' . $day);
            
            $day_month = date('d', $timestamp);

            $this->data[$cid][$year][$month][$day_month] = array();
            $this->data[$cid][$year][$month][$day_month]['day_week'] = array();
            if(6 == date('N', $timestamp) or 7 == date('N', $timestamp)){
              $this->data[$cid][$year][$month][$day_month]['day_week']['is_weekend'] = true;
            }
            $this->data[$cid][$year][$month][$day_month]['day_week']['numeric'] = date('N', $timestamp);
            $this->data[$cid][$year][$month][$day_month]['day_week']['textual'] = date('l', $timestamp);
          }
        }
      }
    }
    
    return true;
  }
  
  private function set_holidays()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('Holidays'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('Holidays'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('Holidays'));
    }
    
    if(empty($this->employees)){
      $this->set_error(ts('No employees !'), ts('Holidays'));
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('Holidays'));
    }
    
    if(empty($this->business)){
      $this->set_error(ts('No business !'), ts('Holidays'));
    }
    
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT * FROM " . $this->custom_groups['leave_holidays']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->departments as $did => $department){
      $where .= " OR entity_id = '" . $did . "'";
    }
    
    foreach($this->business as $bid => $business){
      $where .= " OR entity_id = '" . $bid . "'";
    }
    
    foreach($this->main_business as $mbid => $main_business){
      $where .= " OR entity_id = '" . $mbid . "'";
    }
        
    $query .= " (" . substr($where, 3) . ")";
            
    $query .= " AND (";
    
    $query .= " ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_holidays_start_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_holidays_start_date']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_holidays_end_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_holidays_end_date']['column_name'] . " )";
    
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' >= " . $this->custom_fields['leave_holidays_start_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' <= " . $this->custom_fields['leave_holidays_end_date']['column_name'] . " )";
    
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' >= " . $this->custom_fields['leave_holidays_start_date']['column_name'] . " AND 1 = " . $this->custom_fields['leave_holidays_infinite']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' >= " . $this->custom_fields['leave_holidays_end_date']['column_name'] . " AND 1 = " . $this->custom_fields['leave_holidays_infinite']['column_name'] . " )";
    
    $query .= " ) ";
        
    $query .= " ORDER BY " . $this->custom_fields['leave_holidays_start_date']['column_name'] . " ASC";
                 
    $datas = array();
    $check = array();
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      $datas[$dao->entity_id][$dao->id] = array
      (
        'id' => $dao->id,
        'entity_id' => $dao->entity_id,
        'name' => $dao->{$this->custom_fields['leave_holidays_name']['column_name']},
        'start_date' => $dao->{$this->custom_fields['leave_holidays_start_date']['column_name']},
        'end_date' => $dao->{$this->custom_fields['leave_holidays_end_date']['column_name']},
        'infinite' => $dao->{$this->custom_fields['leave_holidays_infinite']['column_name']}
      );
    }

    if(empty($datas)){
      $this->set_error(ts('No data !'), ts('Holidays'));
    }
        
    if($this->isset_error()){
      unset($datas);
      return false;
    }
           
    foreach($this->employees as $cid => $employee){
      if(isset($this->employees[$cid]['main_business']['id']) and '' !== $this->employees[$cid]['main_business']['id']){
        $mbid = $this->employees[$cid]['main_business']['id'];
      }
      $bid = $this->employees[$cid]['business']['id'];
      $did = $this->employees[$cid]['department']['id'];
      
      if(isset($this->employees[$cid]['main_business']['id']) and '' !== $this->employees[$cid]['main_business']['id']){
        if(isset($datas[$mbid]) and !empty($datas[$mbid])){

          foreach($datas[$mbid] as $key => $holiday){
            $check = $this->set_holidays_days($cid, $holiday, $check);
          }
        }
      }
      
      if(isset($datas[$bid]) and !empty($datas[$bid])){
        foreach($datas[$bid] as $key => $holiday){
          $check = $this->set_holidays_days($cid, $holiday, $check);
        }
      }
      
      if(isset($datas[$did]) and !empty($datas[$did])){
        foreach($datas[$did] as $key => $holiday){
          $check = $this->set_holidays_days($cid, $holiday, $check);
        }
      }
    }
      
    // check of all employee has a holiday set for each year
    foreach($this->employees as $cid => $employee){
      foreach($this->years as $year){
        if(!isset($check[$cid][$year]) or true != $check[$cid][$year]){
          $this->set_error( ts('In function holidays, ') . $this->employees[$cid]['display_name'] .  ts(' has no holidays in ') . $year . ts(' !'), ts('Holidays'));
        }
      }
    }
    
    unset($datas);
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  private function set_holidays_days($cid, $holiday, $check)
  {    
    $start_date_totime = strtotime($holiday['start_date']);
    if(1 == $holiday['infinite']){
      $end_date_totime = strtotime(max($this->years) . '-' . max($this->months) . '-31 23:59:59');
    }else {
      $end_date_totime = strtotime($holiday['end_date']);
    }
    
    for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+1 year' , $timestamp )){ 
      $year = date('Y', $timestamp);
      $month = date('m', $timestamp);
      $day = date('d', $timestamp);
      
      if(isset($this->data[$cid][$year][$month][$day])){
        if(!isset($this->data[$cid][$year][$month][$day]['holiday'])){
          $this->data[$cid][$year][$month][$day]['holiday'] = array();
        }

        $this->data[$cid][$year][$month][$day]['holiday']['is_holiday'] = true;
        $this->data[$cid][$year][$month][$day]['holiday']['name'] = $holiday['name'];
        
        $check[$cid][$year] = true;
      }
    }
    
    return $check;
  }
  
  private function set_general()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('General'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('General'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('General'));
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('General'));
    }
    
    if(empty($this->business)){
      $this->set_error(ts('No business !'), ts('General'));
    }
    
    if($this->isset_error()){
      return false;
    }   
    
    $query = "SELECT * FROM " . $this->custom_groups['leave_general']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->departments as $did => $department){
      $where .= " OR entity_id = '" . $did . "'";
    }
    
    foreach($this->business as $bid => $business){
      $where .= " OR entity_id = '" . $bid . "'";
    }
    
    foreach($this->main_business as $mbid => $main_business){
      $where .= " OR entity_id = '" . $mbid . "'";
    }

    $query .= " (" . substr($where, 3) . ")";
    
    $datas = array();
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $datas[$dao->entity_id] = array
      (
        'id' => $dao->id,
        'entity_id' => $dao->entity_id,
        '1' => $dao->{$this->custom_fields['leave_general_monday']['column_name']}, // Monday
        '2' => $dao->{$this->custom_fields['leave_general_tuesday']['column_name']}, // Tuesday
        '3' => $dao->{$this->custom_fields['leave_general_wednesday']['column_name']}, // Wednesday
        '4' => $dao->{$this->custom_fields['leave_general_thursday']['column_name']}, // Thursday
        '5' => $dao->{$this->custom_fields['leave_general_friday']['column_name']}, // Friday
        '6' => $dao->{$this->custom_fields['leave_general_saturday']['column_name']}, // Saturday
        '7' => $dao->{$this->custom_fields['leave_general_sunday']['column_name']}, // Sunday
        'total' => $dao->{$this->custom_fields['leave_general_total_leave']['column_name']}
      );
    }
    
    // check if empty
    if(empty($datas)){
      $this->set_error(ts('No general !'), ts('General'));
    }
    
    // check if for cid one of mbid, bid or did exists
    $exists = array();
    foreach($this->data as $cid => $years){     
      $mbid = 0;
      if(isset($this->employees[$cid]['main_business']['id']) and '' !== $this->employees[$cid]['main_business']['id']){
        $mbid = $this->employees[$cid]['main_business']['id'];
      }
      $bid = $this->employees[$cid]['business']['id'];
      $did = $this->employees[$cid]['department']['id'];
      
      $exists[$cid] = false;
      
      if(isset($datas[$mbid])){
        $exists[$cid] = true;
      }
      
      if(isset($datas[$bid])){
        $exists[$cid] = true;
      }
      
      if(isset($datas[$did])){
        $exists[$cid] = true;
      }
    }
    
    foreach($exists as $cid => $boolean){
      if(!$boolean){
        $this->set_error($this->employees[$cid]['display_name'] . ts(' has no general !'), ts('General'));
      }
    }
    
    if($this->isset_error()){
      unset($datas);
      return false;
    } 
        
    foreach($this->data as $cid => $years){
      $mbid = 0;
      if(isset($this->employees[$cid]['main_business']['id']) and '' !== $this->employees[$cid]['main_business']['id']){
        $mbid = $this->employees[$cid]['main_business']['id'];
      }
      $bid = $this->employees[$cid]['business']['id'];
      $did = $this->employees[$cid]['department']['id'];
      
      foreach($years as $year => $months){
        
        foreach($months as $month => $days){
          
          foreach($days as $day => $general){

            $timestamp = strtotime($year . '-' . $month . '-' . $day);
            $day_of_week = date('N', $timestamp);
            
            // main business
            if(isset($datas[$mbid][$day_of_week]) and '' !== $datas[$mbid][$day_of_week]){
              list($hours, $minutes) = explode(':', $datas[$mbid][$day_of_week]);
              $duration = ($hours * 60) + $minutes;
              
              list($hours, $minutes) = explode(':', $datas[$mbid]['total']);
              $duration_total = ($hours * 60) + $minutes;
            }  
            
            // business
            if(isset($datas[$bid][$day_of_week]) and '' !== $datas[$bid][$day_of_week]){
              list($hours, $minutes) = explode(':', $datas[$bid][$day_of_week]);
              $duration = ($hours * 60) + $minutes;
              
              list($hours, $minutes) = explode(':', $datas[$bid]['total']);
              $duration_total = ($hours * 60) + $minutes;
            }  
            
            // department
            if(isset($datas[$did][$day_of_week]) and '' !== $datas[$did][$day_of_week]){
              list($hours, $minutes) = explode(':', $datas[$did][$day_of_week]);
              $duration = ($hours * 60) + $minutes;
              
              list($hours, $minutes) = explode(':', $datas[$did]['total']);
              $duration_total = ($hours * 60) + $minutes;
            }
            
            if(!isset($this->data[$cid][$year][$month][$day]['general'])){
              $this->data[$cid][$year][$month][$day]['general'] = array();
            }
            
            $this->data[$cid][$year][$month][$day]['general']['duration'] = $duration;
            $this->total[$cid][$year]['credit_total'] = $duration_total;
          }
        }
      }
    }
    
    unset($datas);    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  private function set_overall_adjustments()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('Overall adjustments'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('Overall adjustments'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('Overall adjustments'));
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('Overall adjustments'));
    }
    
    if(empty($this->business)){
      $this->set_error(ts('No business !'), ts('Overall adjustments'));
    }
    
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT * FROM " . $this->custom_groups['leave_overall_adjustments']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->departments as $did => $department){
      $where .= " OR entity_id = '" . $did . "'";
    }
    
    foreach($this->business as $bid => $business){
      $where .= " OR entity_id = '" . $bid . "'";
    }
    
    foreach($this->main_business as $mbid => $main_business){
      $where .= " OR entity_id = '" . $mbid . "'";
    }
    
    $query .= " (" . substr($where, 3) . ")";
    
    $query .= " AND (";
    
    $query .= " ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_overall_adjustments_start_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_overall_adjustments_start_date']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_overall_adjustments_end_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_overall_adjustments_end_date']['column_name'] . " )";
    
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' >= " . $this->custom_fields['leave_overall_adjustments_start_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' <= " . $this->custom_fields['leave_overall_adjustments_end_date']['column_name'] . " )";
    
    /*
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' >= " . $this->custom_fields['leave_general_start_date']['column_name'] . " AND 1 = " . $this->custom_fields['leave_general_infinite']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' >= " . $this->custom_fields['leave_general_end_date']['column_name'] . " AND 1 = " . $this->custom_fields['leave_general_infinite']['column_name'] . " )";
    */
    
    $query .= " ) ";
    
    $query .= " ORDER BY " . $this->custom_fields['leave_overall_adjustments_start_date']['column_name'] . " ASC";
        
    $datas = array();
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $datas[$dao->entity_id][$dao->id] = array
      (
        'id' => $dao->id,
        'entity_id' => $dao->entity_id,
        'start_date' => $dao->{$this->custom_fields['leave_overall_adjustments_start_date']['column_name']},
        'end_date' => $dao->{$this->custom_fields['leave_overall_adjustments_end_date']['column_name']},
        '1' => $dao->{$this->custom_fields['leave_overall_adjustments_monday']['column_name']}, // Monday
        '2' => $dao->{$this->custom_fields['leave_overall_adjustments_tuesday']['column_name']}, // Tuesday
        '3' => $dao->{$this->custom_fields['leave_overall_adjustments_wednesday']['column_name']}, // Wednesday
        '4' => $dao->{$this->custom_fields['leave_overall_adjustments_thursday']['column_name']}, // Thursday
        '5' => $dao->{$this->custom_fields['leave_overall_adjustments_friday']['column_name']}, // Friday
        '6' => $dao->{$this->custom_fields['leave_overall_adjustments_saturday']['column_name']}, // Saturday
        '7' => $dao->{$this->custom_fields['leave_overall_adjustments_sunday']['column_name']}, // Sunday
      );
    }
     
    // whe don have to check if empty or exists
    
    $overall_adjustments = array();
    foreach($datas as $entity_id => $ids){
      foreach($ids as $id => $data){
    
        $start_date_totime = strtotime($data['start_date']);
        $end_date_totime = strtotime($data['end_date']);

        for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+1 day' , $timestamp )){
          $year = date('Y', $timestamp);
          $month = date('m', $timestamp);
          $day = date('d', $timestamp);
          $day_of_week = date('N', $timestamp);
          
          list($hours, $minutes) = explode(':', $data[$day_of_week]);
          $duration = ($hours * 60) + $minutes;
          $overall_adjustments[$entity_id][$year][$month][$day] = $duration;
        }
      }
    }
    
    foreach($this->data as $cid => $years){
      $mbid = 0;
      if(isset($this->employees[$cid]['main_business']['id']) and '' !== $this->employees[$cid]['main_business']['id']){
        $mbid = $this->employees[$cid]['main_business']['id'];
      }
      $bid = $this->employees[$cid]['business']['id'];
      $did = $this->employees[$cid]['department']['id'];
      
      foreach($years as $year => $months){
        
        foreach($months as $month => $days){
          
          foreach($days as $day => $general){
            
            //$this->data[$cid][$year][$month][$day]['general'] = array();
            
            $timestamp = strtotime($year . '-' . $month . '-' . $day);
            $day_of_week = date('N', $timestamp);
               
            
            $duration = 0;
            if(isset($overall_adjustments[$mbid][$year][$month][$day]) and !empty($overall_adjustments[$mbid][$year][$month][$day])){
              $duration = $overall_adjustments[$mbid][$year][$month][$day];
            }
            
            if(isset($overall_adjustments[$bid][$year][$month][$day]) and !empty($overall_adjustments[$bid][$year][$month][$day])){              
              $duration = $overall_adjustments[$bid][$year][$month][$day];
            }
            
            if(isset($overall_adjustments[$did][$year][$month][$day]) and !empty($overall_adjustments[$did][$year][$month][$day])){
              $duration = $overall_adjustments[$did][$year][$month][$day];
            }
            
            if(0 != $duration){
              if(!isset($this->data[$cid][$year][$month][$day]['overall_adjustments'])){
                $this->data[$cid][$year][$month][$day]['overall_adjustments'] = array();
              }
              $this->data[$cid][$year][$month][$day]['overall_adjustments']['duration'] = $duration;
            }
          }
        }
      }
    }
    
    unset($overall_adjustments);
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  private function set_overall_credit()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('Overall credit'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('Overall credit'));
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('Overall credit'));
    }
    
    if(empty($this->business)){
      $this->set_error(ts('No business !'), ts('Overall credit'));
    }
    
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT * FROM " . $this->custom_groups['leave_overall_credit']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->departments as $did => $department){
      $where .= " OR entity_id = '" . $did . "'";
    }
    
    foreach($this->business as $bid => $business){
      $where .= " OR entity_id = '" . $bid . "'";
    }
    
    foreach($this->main_business as $mbid => $main_business){
      $where .= " OR entity_id = '" . $mbid . "'";
    }
        
    $query .= " (" . substr($where, 3) . ")";
    
    $query .= " AND (";
    
    $query .= " ('" . min($this->years) . "-01-01 00:00:00' <= " . $this->custom_fields['leave_overall_credit_from_year']['column_name'] . " AND '" . max($this->years) . "-12-31 23:59:59' >= " . $this->custom_fields['leave_overall_credit_from_year']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-01-01 00:00:00' <= " . $this->custom_fields['leave_overall_credit_to_year']['column_name'] . " AND '" . max($this->years) . "-12-31 23:59:59' >= " . $this->custom_fields['leave_overall_credit_to_year']['column_name'] . " )";
    
    $query .= " OR ('" . min($this->years) . "-01-01 00:00:00' >= " . $this->custom_fields['leave_overall_credit_from_year']['column_name'] . " AND '" . max($this->years) . "-12-31 23:59:59' <= " . $this->custom_fields['leave_overall_credit_to_year']['column_name'] . " )";
    
    $query .= " OR ('" . min($this->years) . "-01-01 00:00:00' >= " . $this->custom_fields['leave_overall_credit_from_year']['column_name'] . " AND 1 = " . $this->custom_fields['leave_overall_credit_infinite']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-01-01 00:00:00' >= " . $this->custom_fields['leave_overall_credit_to_year']['column_name'] . " AND 1 = " . $this->custom_fields['leave_overall_credit_infinite']['column_name'] . " )";
    
    $query .= " ) ";
    
    $query .= " ORDER BY " . $this->custom_fields['leave_overall_credit_from_year']['column_name'] . " ASC";
        
    $datas = array();
        
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $datas[$dao->entity_id][$dao->id] = array
      (
        'id' => $dao->id,
        'entity_id' => $dao->entity_id,
        'from_year' => $dao->{$this->custom_fields['leave_overall_credit_from_year']['column_name']},
        'to_year' => $dao->{$this->custom_fields['leave_overall_credit_to_year']['column_name']},
        'infinite' => $dao->{$this->custom_fields['leave_overall_credit_infinite']['column_name']},
        'total_leave' => $dao->{$this->custom_fields['leave_overall_credit_total_leave_per_year']['column_name']},
      );
    }
        
    // whe don have to check if empty or exists
  
    $overall_credit = array();
    foreach($datas as $entity_id => $ids){
      $overall_credit[$entity_id] = array();
      
      foreach($ids as $id => $data){
    
        if(1 == $data['infinite']){
          $start_date_totime = strtotime($data['from_year']);
          if('' != $data['to_year']){
            $end_date_totime = strtotime($data['to_year']);
          }else {
            $end_date_totime = strtotime(max($this->years) . '-12-31 00:00:00');
          }
          
          for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+1 year' , $timestamp )){ 
            $year = date('Y', $timestamp);
            
            list($hours, $minutes) = explode(':', $data['total_leave']);
            $duration = ($hours * 60) + $minutes;
            
            if(!isset($overall_credit[$entity_id][$year])){
              $overall_credit[$entity_id][$year] = 0;
            }
            
            $overall_credit[$entity_id][$year] = $overall_credit[$entity_id][$year] + $duration;
          }
          
        }else {
          $start_date_totime = strtotime($data['from_year']);
          if('' != $data['to_year']){
            $end_date_totime = strtotime($data['to_year']);
          }else {
            $end_date_totime = strtotime($data['from_year']);
          }
          
          for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+1 year' , $timestamp )){ 
            $year = date('Y', $timestamp);
            
            list($hours, $minutes) = explode(':', $data['total_leave']);
            $duration = ($hours * 60) + $minutes;
            
            if(!isset($overall_credit[$entity_id][$year])){
              $overall_credit[$entity_id][$year] = 0;
            }
            
            $overall_credit[$entity_id][$year] = $overall_credit[$entity_id][$year] + $duration;
          }
        }
      }
    }
    
    foreach($this->data as $cid => $years){
      foreach($years as $year => $months){
        $mbid = 0;
        if(isset($this->employees[$cid]['main_business']['id']) and '' !== $this->employees[$cid]['main_business']['id']){
          $mbid = $this->employees[$cid]['main_business']['id'];
        }
        $bid = $this->employees[$cid]['business']['id'];
        $did = $this->employees[$cid]['department']['id'];

        $duration = false;
        if(isset($overall_credit[$mbid][$year]) and '' !== $overall_credit[$mbid][$year]){
          $duration = $overall_credit[$mbid][$year];
        }

        if(isset($overall_credit[$bid][$year]) and '' !== $overall_credit[$bid][$year]){
          $duration = $overall_credit[$bid][$year];
        }

        if(isset($overall_credit[$did][$year]) and '' !== $overall_credit[$did][$year]){
          $duration = $overall_credit[$did][$year];
        }
        
        if(!isset($this->total[$cid][$year]['credit_total'])){
          $this->total[$cid][$year]['credit_total'] = 0;
        }
        if(!isset($this->total[$cid][$year]['credit_total_over'])){
          $this->total[$cid][$year]['credit_total_over'] = 0;
        }
        if(!isset($this->total[$cid][$year]['credit'])){
          $this->total[$cid][$year]['credit'] = 0;
        }
        
        if(false !== $duration){
          $this->total[$cid][$year]['credit'] = $duration;
        }
      }
    }
    
    unset($overall_credit);
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  private function set_adjustments()
  {    
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('Adjustments'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('Adjustments'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('Adjustments'));
    }
    
    if(empty($this->employees)){
      $this->set_error(ts('No employees !'), ts('Adjustments'));
    }
    
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT * FROM " . $this->custom_groups['leave_adjustments']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->employees as $cid => $employee){
      $where .= " OR entity_id = '" . $cid . "'";
    }
    
    $query .= " (" . substr($where, 3) . ")";
        
    $query .= " AND (";
    
    $query .= " ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_adjustments_start_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_adjustments_start_date']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_adjustments_end_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_adjustments_end_date']['column_name'] . " )";

    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' >= " . $this->custom_fields['leave_adjustments_start_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' <= " . $this->custom_fields['leave_adjustments_end_date']['column_name'] . " )";
    
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' >= " . $this->custom_fields['leave_adjustments_start_date']['column_name'] . " AND 1 = " . $this->custom_fields['leave_adjustments_infinite']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' >= " . $this->custom_fields['leave_adjustments_end_date']['column_name'] . " AND 1 = " . $this->custom_fields['leave_adjustments_infinite']['column_name'] . " )";
    
    $query .= " ) ";
    
    $query .= " ORDER BY " . $this->custom_fields['leave_adjustments_start_date']['column_name'] . " ASC";
            
    $datas = array();
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $datas[$dao->entity_id][$dao->id] = array
      (
        'id' => $dao->id,
        'entity_id' => $dao->entity_id,
        'start_date' => $dao->{$this->custom_fields['leave_adjustments_start_date']['column_name']},
        'end_date' => $dao->{$this->custom_fields['leave_adjustments_end_date']['column_name']},
        'infinite' => $dao->{$this->custom_fields['leave_adjustments_infinite']['column_name']},
        'each' => $dao->{$this->custom_fields['leave_adjustments_each']['column_name']},
        'day_week_month_year' => $dao->{$this->custom_fields['leave_adjustments_day_week_month_year']['column_name']},
        'on' => $dao->{$this->custom_fields['leave_adjustments_on']['column_name']},
        'day_of_the_week' => $dao->{$this->custom_fields['leave_adjustments_day_of_the_week']['column_name']},
        'duration' => $dao->{$this->custom_fields['leave_adjustments_duration']['column_name']},
      );
      
           
    }
        
    /*if(empty($datas)){
      $this->set_error(ts('No adjustments !'), ts('Adjustments'));
    }
    
    if($this->isset_error()){
      unset($datas);
      return false;
    } */
            
    foreach($datas as $entity_id => $ids){
      foreach($ids as $id => $data){
        
        $patterns = $this->set_pattern($data, 'adjustments');
        
        foreach($patterns as $cid => $years){
            foreach($years as $year => $months){
              foreach($months as $month => $days){
                foreach($days as $day => $pattern){
        
                  if(isset($this->data[$cid][$year][$month][$day])){
                    // is holiday
                    if(isset($this->data[$cid][$year][$month][$day]['holiday']['is_holiday']) and ($this->data[$cid][$year][$month][$day]['holiday']['is_holiday'])){
                      //return true;
                    }else {

                      if(!isset($this->data[$cid][$year][$month][$day]['adjustments'])){
                        $this->data[$cid][$year][$month][$day]['adjustments'] = array();
                      }

                      list($hours, $minutes) = explode(':', $pattern['duration']);
                      $duration = ($hours * 60) + $minutes;

                      $this->data[$cid][$year][$month][$day]['adjustments']['duration'] = $duration;
                    }
                  }
                }
              }
            }
          }
        }
    }
    
    unset($datas);
    unset($patterns);
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
    
  private function set_credit()
  {   
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->employees)){
      $this->set_error(ts('No employees !'), ts('Credit'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('Credit'));
    }
        
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT * FROM " . $this->custom_groups['leave_credit']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->employees as $cid => $employee){
      $where .= " OR entity_id = '" . $cid . "'";
    }
        
    $query .= " (" . substr($where, 3) . ")";
        
    $query .= " AND (";
    
    $query .= " ('" . min($this->years) . "-01-01 00:00:00' <= " . $this->custom_fields['leave_credit_from_year']['column_name'] . " AND '" . max($this->years) . "-12-31 23:59:59' >= " . $this->custom_fields['leave_credit_from_year']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-01-01 00:00:00' <= " . $this->custom_fields['leave_credit_to_year']['column_name'] . " AND '" . max($this->years) . "-12-31 23:59:59' >= " . $this->custom_fields['leave_credit_to_year']['column_name'] . " )";
    
    $query .= " OR ('" . min($this->years) . "-01-01 00:00:00' >= " . $this->custom_fields['leave_credit_from_year']['column_name'] . " AND '" . max($this->years) . "-12-31 23:59:59' <= " . $this->custom_fields['leave_credit_to_year']['column_name'] . " )";
    
    $query .= " OR ('" . min($this->years) . "-01-01 00:00:00' >= " . $this->custom_fields['leave_credit_from_year']['column_name'] . " AND 1 = " . $this->custom_fields['leave_credit_infinite']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-01-01 00:00:00' >= " . $this->custom_fields['leave_credit_to_year']['column_name'] . " AND 1 = " . $this->custom_fields['leave_credit_infinite']['column_name'] . " )";
    
    $query .= " ) ";
    
    $query .= " ORDER BY " . $this->custom_fields['leave_credit_from_year']['column_name'] . " ASC";
        
    $dao = CRM_Core_DAO::executeQuery($query);
        
    $datas = array();
    while($dao->fetch()){
      
      $datas[$dao->entity_id][$dao->id] = array
      //$data = array
      (
        'id' => $dao->id,
        'entity_id' => $dao->entity_id,
        'from_year' => $dao->{$this->custom_fields['leave_credit_from_year']['column_name']},
        'to_year' => $dao->{$this->custom_fields['leave_credit_to_year']['column_name']},
        'infinite' => $dao->{$this->custom_fields['leave_credit_infinite']['column_name']},
        'total_leave' => $dao->{$this->custom_fields['leave_credit_total_leave_per_year']['column_name']},
        'total_leave_over' => $dao->{$this->custom_fields['leave_credit_total_leave_per_year_over']['column_name']},
      );
    }
    
    // whe don have to check if empty or exists
    
    $credit = array();
    foreach($datas as $entity_id => $ids){
      foreach($ids as $id => $data){
    
        if(1 == $data['infinite']){
          $start_date_totime = strtotime($data['from_year']);
          if('' != $data['to_year']){
            $end_date_totime = strtotime($data['to_year']);
          }else {
            $end_date_totime = strtotime(max($this->years) . '-12-31 00:00:00');
          }
          
          for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+1 year' , $timestamp )){ 
            $year = date('Y', $timestamp);
            
            if(!isset($credit[$entity_id])){
              $credit[$entity_id] = array();
            }
            
            if(!isset($credit[$entity_id][$year])){
              $credit[$entity_id][$year] = array();
            }
            
            if(!isset($credit[$entity_id][$year]['credit_total'])){
              $credit[$entity_id][$year]['credit_total'] = 0;
            }
            
            if(!isset($credit[$entity_id][$year]['credit_total_over'])){
              $credit[$entity_id][$year]['credit_total_over'] = 0;
            }
            
            list($hours, $minutes) = explode(':', $data['total_leave']);
            $duration_total = ($hours * 60) + $minutes;
            $credit[$entity_id][$year]['credit_total'] = $credit[$entity_id][$year]['credit_total'] + $duration_total;

            if('-' == substr($data['total_leave_over'], 0, 1)){
              list($hours, $minutes) = explode(':', str_replace('-', '', $data['total_leave_over']));
              $duration_over = '-' . ($hours * 60) + $minutes;
                  
            }else {
              list($hours, $minutes) = explode(':', $data['total_leave_over']);
              $duration_over = ($hours * 60) + $minutes;
            }
            
            $credit[$entity_id][$year]['credit_total_over'] = $credit[$entity_id][$year]['credit_total_over'] + $duration_over;
          }
          
        }else {
          $start_date_totime = strtotime($data['from_year']);
          if('' != $data['to_year']){
            $end_date_totime = strtotime($data['to_year']);
          }else {
            $end_date_totime = strtotime($data['from_year']);
          }
          
          for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+1 year' , $timestamp )){ 
            $year = date('Y', $timestamp);
            
            if(!isset($credit[$entity_id])){
              $credit[$entity_id] = array();
            }
            
            if(!isset($credit[$entity_id][$year])){
              $credit[$entity_id][$year] = array();
            }
            
            if(!isset($credit[$entity_id][$year]['credit_total'])){
              $credit[$entity_id][$year]['credit_total'] = 0;
            }
            
            if(!isset($credit[$entity_id][$year]['credit_total_over'])){
              $credit[$entity_id][$year]['credit_total_over'] = 0;
            }
            
            list($hours, $minutes) = explode(':', $data['total_leave']);
            $duration_total = ($hours * 60) + $minutes;
            $credit[$entity_id][$year]['credit_total'] = $credit[$entity_id][$year]['credit_total'] + $duration_total;

            if('-' == substr($data['total_leave_over'], 0, 1)){
              list($hours, $minutes) = explode(':', str_replace('-', '', $data['total_leave_over']));
              $duration_over = '-' . ($hours * 60) + $minutes;
                  
            }else {
              list($hours, $minutes) = explode(':', $data['total_leave_over']);
              $duration_over = ($hours * 60) + $minutes;
            }
            
            $credit[$entity_id][$year]['credit_total_over'] = $credit[$entity_id][$year]['credit_total_over'] + $duration_over;
          }
        }
      }
    }
    
    foreach($this->data as $cid => $years){
      foreach($years as $year => $months){
        if(isset($credit[$cid][$year]) and !empty($credit[$cid][$year])){
          
          if(!empty($credit[$cid][$year]['credit_total'])){
            $this->total[$cid][$year]['credit_total'] = $credit[$cid][$year]['credit_total'];
          }
          $this->total[$cid][$year]['credit_total_over'] = $credit[$cid][$year]['credit_total_over'];
          //$this->total[$cid][$year]['credit'] = $credit[$cid][$year]['credit'];
          
        }
      }
    }
    
    unset($credit);
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  private function set_request()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('Request'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('Request'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('Request'));
    }
    
    if(empty($this->employees)){
      $this->set_error(ts('No employees !'), ts('Request'));
    }
    
    if($this->isset_error()){
      return false;
    }
    
    $query = "SELECT civicrm_activity.source_record_id, " . $this->custom_groups['leave_request']['table_name'] . ".id, " . $this->custom_groups['leave_request']['table_name'] . ".entity_id";
    
    $query .= ", " . $this->custom_fields['leave_request_leave_type']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_reason']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_status']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_from_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_to_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_day_week_month_year']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_on']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_duration']['column_name'];
        
    $query .= " FROM " . $this->custom_groups['leave_request']['table_name'];
    $query .= " LEFT JOIN civicrm_activity ON " . $this->custom_groups['leave_request']['table_name'] . ".entity_id = civicrm_activity.id";
    $query .= " WHERE civicrm_activity.activity_type_id = '" . trim($this->custom_groups['leave_request']['extends_entity_column_value']) . "'";
    
    $where = "";
    foreach($this->employees as $cid => $employee){
      $where .= " OR civicrm_activity.source_record_id = '" . $cid . "'";
    }
    
    $query .= " AND (" . substr($where, 3) . ")";
    
    $query .= " AND (";
    
    $query .= " ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_request_from_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_request_from_date']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_request_to_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_request_to_date']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' >= " . $this->custom_fields['leave_request_from_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' <= " . $this->custom_fields['leave_request_to_date']['column_name'] . " )";
    
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_request_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_request_date']['column_name'] . " )";
    
    $query .= ")";
    
    $query .= " ORDER BY " . $this->custom_fields['leave_request_from_date']['column_name'] . ", " . $this->custom_fields['leave_request_date']['column_name'] . " ASC";
    
    $datas = array();   
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $datas[$dao->id] = array
      (
        'id' => $dao->id,
        'entity_id' => $dao->entity_id,
        'cid' => $dao->source_record_id,
        'leave_type' => $dao->{$this->custom_fields['leave_request_leave_type']['column_name']},
        'reason' => $dao->{$this->custom_fields['leave_request_reason']['column_name']},
        'status' => $dao->{$this->custom_fields['leave_request_status']['column_name']},
        'from_date' => $dao->{$this->custom_fields['leave_request_from_date']['column_name']},
        'to_date' => $dao->{$this->custom_fields['leave_request_to_date']['column_name']},
        'date' => $dao->{$this->custom_fields['leave_request_date']['column_name']},
        'each' => $dao->{$this->custom_fields['leave_request_each']['column_name']},
        'day_week_month_year' => $dao->{$this->custom_fields['leave_request_day_week_month_year']['column_name']},
        'on' => $dao->{$this->custom_fields['leave_request_on']['column_name']},
        'day_of_the_week' => $dao->{$this->custom_fields['leave_request_day_of_the_week']['column_name']},
        'duration' => $dao->{$this->custom_fields['leave_request_duration']['column_name']}
      );
    }
    
    /*if(empty($datas)){
      $this->set_error(ts('No data !'), ts('Request'));
    }
    
    if($this->isset_error()){
      unset($datas);
      return false;
    }*/
    
    $this->request = $datas;
    
    /*'atv' => 'ATV', //
    'special_leave' => 'Special leave', // buitengewoon verlof
    'doctor_visit' => 'Doctor visit', // dokter bezoek
    'normal_leave' => 'Normal leave', // normaal verlof
    'normal_leave' => 'Normal leave < 1 day', // normaal verlof < 1 dag
    //'mom_dad_day' => 'Mom/Dad day',
    'mom_dad_day' => 'Parental', // ouderschapsverlof
    //'parental' => 'Parental', // ouderschapsverlof
    'study_leave' => 'Study leave', // studieverlof
    'time_for_time' => 'Time for time', // tijd voor tijd
    'care' => 'Care', // zorgverlof
    'maternity' => 'Maternity', // zwangerschapsverlof
    'sick' => 'Sick', // ziek
    'sick_less_one_day' => 'Ziek < 1 day', // Ziek z 1 dag*/
    
    /*$weight = array
    (
      'sick' => 1,
      'sick_less_one_day' => 2,
      'maternity' => 3,
      'care' => 4,
      'study_leave' => 5,
      'mom_dad_day' => 6,
      'doctor_visit' => 7,
      'atv' => 8,
      'special_leave' => 9,
      'normal_leave' => 10,
      'normal_leave_less_one_day' => 11,
    );*/
    
    
    // first
    foreach($datas as $id => $data){
      switch($data['leave_type']){
        case 'doctor_visit':
        case 'sick_less_one_day': 
          $timestamp = strtotime($data['date']);
          
          $year = date('Y', $timestamp);
          $month = date('m', $timestamp);
          $day = date('d', $timestamp);
          
          list($hours, $minutes) = explode(':', $data['duration']);
          $duration = ($hours * 60) + $minutes;
             
          // is holiday
          if(isset($this->data[$data['cid']][$year][$month][$day]['holiday']['is_holiday']) and ($this->data[$data['cid']][$year][$month][$day]['holiday']['is_holiday'])){
            $duration = 0;
          }
          
          if(0 != $duration){
            if(!isset($this->data[$data['cid']][$year][$month][$day]['request'])){
              $this->data[$data['cid']][$year][$month][$day]['request'] = array();
            }

            if(0 != $duration){
              if(!isset($this->data[$data['cid']][$year][$month][$day]['request']['leave_type'])){ // if leave_type don exists
                $this->data[$data['cid']][$year][$month][$day]['request']['is_request'] = true;
                $this->data[$data['cid']][$year][$month][$day]['request']['id'] = $data['id'];
                $this->data[$data['cid']][$year][$month][$day]['request']['leave_type'] = $data['leave_type'];
                $this->data[$data['cid']][$year][$month][$day]['request']['reason'] = $data['reason'];
                $this->data[$data['cid']][$year][$month][$day]['request']['status'] = $data['status'];
                $this->data[$data['cid']][$year][$month][$day]['request']['duration'] = $duration;
              }
            }
          }
          break;
        
        case 'mom_dad_day':
        case 'study_leave':
        case 'care':      
          $patterns = $this->set_pattern($data, 'request');
          
          foreach($patterns as $cid => $years){
            foreach($years as $year => $months){
              foreach($months as $month => $days){
                foreach($days as $day => $pattern){
                
                  if(isset($this->data[$cid][$year][$month][$day])){     

                    list($hours, $minutes) = explode(':', $pattern['duration']);
                    $duration = ($hours * 60) + $minutes;

                    // is holiday
                    if(isset($this->data[$cid][$year][$month][$day]['holiday']['is_holiday']) and ($this->data[$cid][$year][$month][$day]['holiday']['is_holiday'])){
                      $duration = 0;
                    }
                    
                    if(0 != $duration){
                      if(!isset($this->data[$cid][$year][$month][$day]['request'])){
                        $this->data[$cid][$year][$month][$day]['request'] = array();
                      }

                      // adjustments
                      if(isset($this->data[$data['cid']][$year][$month][$day]['adjustments']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['adjustments']['duration']){
                        $duration = $this->data[$data['cid']][$year][$month][$day]['adjustments']['duration'];

                      }else if(isset($this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration']){
                        $duration = $this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration'];

                      }else if(isset($this->data[$data['cid']][$year][$month][$day]['general']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['general']['duration']){
                        $duration = $this->data[$data['cid']][$year][$month][$day]['general']['duration'];
                      }

                      // if adjustment is not 0, if it does he/she is free
                      if(0 != $duration){

                        if(!isset($this->data[$cid][$year][$month][$day]['request']['leave_type'])){ // if leave_type don exists
                          $this->data[$cid][$year][$month][$day]['request']['is_request'] = true;
                          $this->data[$cid][$year][$month][$day]['request']['id'] = $pattern['id'];
                          $this->data[$cid][$year][$month][$day]['request']['leave_type'] = $pattern['leave_type'];
                          $this->data[$cid][$year][$month][$day]['request']['reason'] = $pattern['reason'];
                          $this->data[$cid][$year][$month][$day]['request']['status'] = $pattern['status'];
                          $this->data[$cid][$year][$month][$day]['request']['duration'] = $duration;
                        }
                      }
                    }
                  }
                }
              }
            }
          }
          break;
          
        case 'special_leave':
        case 'mom_dad_day_contiguous':
        case 'maternity':
        case 'sick':
          $from_date_totime = strtotime($data['from_date']);
          $to_date_totime = strtotime($data['to_date']);
          
          for($timestamp = $from_date_totime; $timestamp <= $to_date_totime; $timestamp = strtotime( '+1 day' , $timestamp )){            
            $year = date('Y', $timestamp);
            $month = date('m', $timestamp);
            $day = date('d', $timestamp);
            
            $duration = 999;
            
            // is holiday
            if(isset($this->data[$data['cid']][$year][$month][$day]['holiday']['is_holiday']) and ($this->data[$data['cid']][$year][$month][$day]['holiday']['is_holiday'])){
              $duration = 0;
            }
            
            if(0 != $duration){
              if(!isset($this->data[$data['cid']][$year][$month][$day]['request'])){
                $this->data[$data['cid']][$year][$month][$day]['request'] = array();
              }

              $duration = 0;
              if(isset($this->data[$data['cid']][$year][$month][$day]['adjustments']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['adjustments']['duration']){
                $duration = $this->data[$data['cid']][$year][$month][$day]['adjustments']['duration'];

              }else if(isset($this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration']){
                $duration = $this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration'];

              }else if(isset($this->data[$data['cid']][$year][$month][$day]['general']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['general']['duration']){
                $duration = $this->data[$data['cid']][$year][$month][$day]['general']['duration'];
              }
              
              if(0 != $duration){
                if(!isset($this->data[$data['cid']][$year][$month][$day]['request']['leave_type'])){ // if leave_type don exists
                  $this->data[$data['cid']][$year][$month][$day]['request']['is_request'] = true;
                  $this->data[$data['cid']][$year][$month][$day]['request']['id'] = $data['id'];
                  $this->data[$data['cid']][$year][$month][$day]['request']['leave_type'] = $data['leave_type'];
                  $this->data[$data['cid']][$year][$month][$day]['request']['reason'] = $data['reason'];
                  $this->data[$data['cid']][$year][$month][$day]['request']['status'] = $data['status'];
                  $this->data[$data['cid']][$year][$month][$day]['request']['duration'] = $duration;
                }
              }
            }
          }
          break;
      }
    }
        
    // second the rest
    foreach($datas as $id => $data){
                 
      switch($data['leave_type']){
        case 'time_for_time': // always add time for time
          $timestamp = strtotime($data['date']);
          
          $year = date('Y', $timestamp);
          $month = date('m', $timestamp);
          $day = date('d', $timestamp);
          
          list($hours, $minutes) = explode(':', $data['duration']);
          $duration = ($hours * 60) + $minutes;
          
          $this->data[$data['cid']][$year][$month][$day]['time_for_time']['is_time_for_time'] = true;
          $this->data[$data['cid']][$year][$month][$day]['time_for_time']['leave_type'] = 'time_for_time';
          $this->data[$data['cid']][$year][$month][$day]['time_for_time']['id'] = $data['id'];
          $this->data[$data['cid']][$year][$month][$day]['time_for_time']['reason'] = $data['reason'];
          $this->data[$data['cid']][$year][$month][$day]['time_for_time']['status'] = $data['status'];
          $this->data[$data['cid']][$year][$month][$day]['time_for_time']['duration'] = $duration;
          
          break;
                
        case 'normal_leave_less_one_day': 
          $timestamp = strtotime($data['date']);
          
          $year = date('Y', $timestamp);
          $month = date('m', $timestamp);
          $day = date('d', $timestamp);
          
          list($hours, $minutes) = explode(':', $data['duration']);
          $duration = ($hours * 60) + $minutes;
             
          // is holiday
          if(isset($this->data[$data['cid']][$year][$month][$day]['holiday']['is_holiday']) and ($this->data[$data['cid']][$year][$month][$day]['holiday']['is_holiday'])){
            $duration = 0;
          }
          
          // is not request
          if(0 != $duration){
            if(!isset($this->data[$data['cid']][$year][$month][$day]['request']['is_request'])){  

              if(!isset($this->data[$data['cid']][$year][$month][$day]['normal_leave'])){
                $this->data[$data['cid']][$year][$month][$day]['normal_leave'] = array();
              }

              if(!isset($this->data[$data['cid']][$year][$month][$day]['normal_leave']['leave_type'])){ // if leave_type don exists
                $this->data[$data['cid']][$year][$month][$day]['normal_leave']['is_normal_leave'] = true;
                $this->data[$data['cid']][$year][$month][$day]['normal_leave']['id'] = $data['id'];
                $this->data[$data['cid']][$year][$month][$day]['normal_leave']['leave_type'] = $data['leave_type'];
                $this->data[$data['cid']][$year][$month][$day]['normal_leave']['reason'] = $data['reason'];
                $this->data[$data['cid']][$year][$month][$day]['normal_leave']['status'] = $data['status'];
                $this->data[$data['cid']][$year][$month][$day]['normal_leave']['duration'] = $duration;   
              }
            }
          }
          break;
                          
        case 'normal_leave':
          $from_date_totime = strtotime($data['from_date']);
          $to_date_totime = strtotime($data['to_date']);
          
          for($timestamp = $from_date_totime; $timestamp <= $to_date_totime; $timestamp = strtotime( '+1 day' , $timestamp )){            
            $year = date('Y', $timestamp);
            $month = date('m', $timestamp);
            $day = date('d', $timestamp);
                
            $duration = 999;
            
            // is holiday
            if(isset($this->data[$data['cid']][$year][$month][$day]['holiday']['is_holiday']) and ($this->data[$data['cid']][$year][$month][$day]['holiday']['is_holiday'])){
              $duration = 0;
            }
            
            if(0 != $duration){
              if(!isset($this->data[$data['cid']][$year][$month][$day]['request'])){
                $this->data[$data['cid']][$year][$month][$day]['request'] = array();
              }

              $duration = 0;
              if(isset($this->data[$data['cid']][$year][$month][$day]['adjustments']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['adjustments']['duration']){
                $duration = $this->data[$data['cid']][$year][$month][$day]['adjustments']['duration'];

              }else if(isset($this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration']){
                $duration = $this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration'];

              }else if(isset($this->data[$data['cid']][$year][$month][$day]['general']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['general']['duration']){
                $duration = $this->data[$data['cid']][$year][$month][$day]['general']['duration'];
              }

              if(0 != $duration){
                // is not request
                if(!isset($this->data[$data['cid']][$year][$month][$day]['request']['is_request']) or 'approved' != $this->data[$data['cid']][$year][$month][$day]['request']['status']){ 
                  if(!isset($this->data[$data['cid']][$year][$month][$day]['normal_leave'])){
                    $this->data[$data['cid']][$year][$month][$day]['normal_leave'] = array();
                  }

                  if(!isset($this->data[$data['cid']][$year][$month][$day]['normal_leave']['leave_type'])){ // if leave_type don exists
                    $this->data[$data['cid']][$year][$month][$day]['normal_leave']['is_normal_leave'] = true;
                    $this->data[$data['cid']][$year][$month][$day]['normal_leave']['id'] = $data['id'];
                    $this->data[$data['cid']][$year][$month][$day]['normal_leave']['leave_type'] = $data['leave_type'];
                    $this->data[$data['cid']][$year][$month][$day]['normal_leave']['reason'] = $data['reason'];
                    $this->data[$data['cid']][$year][$month][$day]['normal_leave']['status'] = $data['status'];
                    $this->data[$data['cid']][$year][$month][$day]['normal_leave']['duration'] = $duration;
                  }
                }
              }
            }
          }
          break;
          
        default:
      }
    }
    
    unset($datas);
    return true;
  }
    
  private function set_department_head_request()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('Request'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('Request'));
    }
        
    if($this->isset_error()){
      return false;
    }
    
    if(!empty($this->department_head_collids)){
      $query = "SELECT civicrm_activity.source_record_id, " . $this->custom_groups['leave_request']['table_name'] . ".id, " . $this->custom_groups['leave_request']['table_name'] . ".entity_id, civicrm_contact.display_name";

      $query .= ", " . $this->custom_fields['leave_request_leave_type']['column_name'];
      $query .= ", " . $this->custom_fields['leave_request_reason']['column_name'];
      $query .= ", " . $this->custom_fields['leave_request_status']['column_name'];
      $query .= ", " . $this->custom_fields['leave_request_from_date']['column_name'];
      $query .= ", " . $this->custom_fields['leave_request_to_date']['column_name'];
      $query .= ", " . $this->custom_fields['leave_request_date']['column_name'];
      $query .= ", " . $this->custom_fields['leave_request_each']['column_name'];
      $query .= ", " . $this->custom_fields['leave_request_day_week_month_year']['column_name'];
      $query .= ", " . $this->custom_fields['leave_request_on']['column_name'];
      $query .= ", " . $this->custom_fields['leave_request_day_of_the_week']['column_name'];
      $query .= ", " . $this->custom_fields['leave_request_duration']['column_name'];

      $query .= " FROM " . $this->custom_groups['leave_request']['table_name'];
      $query .= " LEFT JOIN civicrm_activity ON " . $this->custom_groups['leave_request']['table_name'] . ".entity_id = civicrm_activity.id";
      $query .= " LEFT JOIN civicrm_contact ON civicrm_activity.source_record_id = civicrm_contact.id";
      
      $query .= " WHERE civicrm_activity.activity_type_id = '" . trim($this->custom_groups['leave_request']['extends_entity_column_value']) . "'";

      $where = "";
      foreach($this->department_head_collids as $cid => $colids_didh){
        $where .= " OR civicrm_activity.source_record_id = '" . $cid . "'";
      }

      $query .= " AND (" . substr($where, 3) . ")";

      $query .= " AND (";

      $query .= " ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_request_from_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_request_from_date']['column_name'] . " )";
      $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_request_to_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_request_to_date']['column_name'] . " )";
      $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' >= " . $this->custom_fields['leave_request_from_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' <= " . $this->custom_fields['leave_request_to_date']['column_name'] . " )";

      $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_request_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_request_date']['column_name'] . " )";

      $query .= ")";

      $query .= " ORDER BY civicrm_contact.display_name, " . $this->custom_fields['leave_request_from_date']['column_name'] . ", " . $this->custom_fields['leave_request_date']['column_name'] . " ASC";
            
      $datas = array();   

      $dao = CRM_Core_DAO::executeQuery($query);
      while($dao->fetch()){

        $datas[$dao->id] = array
        (
          'id' => $dao->id,
          'entity_id' => $dao->entity_id,
          'cid' => $dao->source_record_id,
          'leave_type' => $dao->{$this->custom_fields['leave_request_leave_type']['column_name']},
          'reason' => $dao->{$this->custom_fields['leave_request_reason']['column_name']},
          'status' => $dao->{$this->custom_fields['leave_request_status']['column_name']},
          'from_date' => $dao->{$this->custom_fields['leave_request_from_date']['column_name']},
          'to_date' => $dao->{$this->custom_fields['leave_request_to_date']['column_name']},
          'date' => $dao->{$this->custom_fields['leave_request_date']['column_name']},
          'each' => $dao->{$this->custom_fields['leave_request_each']['column_name']},
          'day_week_month_year' => $dao->{$this->custom_fields['leave_request_day_week_month_year']['column_name']},
          'on' => $dao->{$this->custom_fields['leave_request_on']['column_name']},
          'day_of_the_week' => $dao->{$this->custom_fields['leave_request_day_of_the_week']['column_name']},
          'duration' => $dao->{$this->custom_fields['leave_request_duration']['column_name']}
        );
      }

      $this->department_head_request = $datas;
    }
    
    return true;
  }
  
  
  /**
   * Calculate pattern for general and request.
   */
  public function set_pattern($data, $type)
  {
    switch($type)
    {
      case 'adjustments':
        $start_date_totime = strtotime($data['start_date']);
        if(1 == $data['infinite']){
          $end_date_totime = strtotime(max($this->years) . '-' . max($this->months) . '-31 00:00:00');
        }else {
          $end_date_totime = strtotime($data['end_date']);
        }
        
        $cid = $data['entity_id'];
        break;
      
      case 'request':
        $start_date_totime = strtotime($data['from_date']);
        $end_date_totime = strtotime($data['to_date']);
        
        $cid = $data['cid'];
        break;
    }
    
    $patterns = array();
    
    switch($data['day_week_month_year'])
    {
      case 'day':

        for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+' . $data['each'] . ' day' , $timestamp )){            
          switch($data['day_of_the_week']){
            case 'nvt':
              switch($data['on'])
              {
                case 'first': 
                  $day = date('d', strtotime('monday this week', $timestamp));
                  $month = date('m', strtotime('monday this week', $timestamp));
                  $year = date('y', strtotime('monday this week', $timestamp));
                  break;

                case 'last': 
                  $day = date('d', strtotime('sunday this week', $timestamp)); 
                  $month = date('m', strtotime('sunday this week', $timestamp)); 
                  $year = date('Y', strtotime('sunday this week', $timestamp)); 
                  break;

                case 'next': 
                  $day = date('d', strtotime( '+1 day' , $timestamp ));
                  $month = date('m', strtotime( '+1 day' , $timestamp ));
                  $year = date('Y', strtotime( '+1 day' , $timestamp ));
                  break; 

                case 'previous': 
                  $day = date('d', strtotime( '-1 day' , $timestamp ));
                  $month = date('m', strtotime( '-1 day' , $timestamp ));
                  $year = date('Y', strtotime( '-1 day' , $timestamp ));
                  break;

                default :
                  $day = date('d', $timestamp);
                  $month = date('m', $timestamp);
                  $year = date('Y', $timestamp);
              }
              break;

            default:
              $day = date('d', strtotime($data['day_of_the_week'] . ' this week', $timestamp));  
              $month = date('m', strtotime($data['day_of_the_week'] . ' this week', $timestamp));  
              $year = date('Y', strtotime($data['day_of_the_week'] . ' this week', $timestamp));  
          }
          $patterns[$cid][$year][$month][$day] = $data;
        }
        break;

      case 'week':

        for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+' . $data['each'] . ' week' , $timestamp )){            
          switch($data['day_of_the_week']){
            case 'nvt':
              switch($data['on'])
              {
                case 'first': 
                  $day = date('d', strtotime('monday this week', $timestamp)); 
                  $month = date('m', strtotime('monday this week', $timestamp)); // update month
                  $year = date('Y', strtotime('monday this week', $timestamp)); // update year
                  break;

                case 'last': 
                  $day = date('d', strtotime('sunday this week', $timestamp)); 
                  $month = date('m', strtotime('sunday this week', $timestamp)); 
                  $year = date('Y', strtotime('sunday this week', $timestamp)); 
                  break;

                case 'next': 
                  $day = date('d', strtotime( '+1 day' , $timestamp ));
                  $month = date('m', strtotime( '+1 day' , $timestamp ));
                  $year = date('Y', strtotime( '+1 day' , $timestamp ));
                  break; 

                case 'previous': 
                  $day = date('d', strtotime( '-1 day' , $timestamp ));
                  $month = date('m', strtotime( '-1 day' , $timestamp ));
                  $year = date('Y', strtotime( '-1 day' , $timestamp ));
                  break;

                default :
                  $day = date('d', $timestamp);
                  $month = date('m', $timestamp);
                  $year = date('Y', $timestamp);

              }
              break;

            default:
              $day = date('d', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
              $month = date('m', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
              $year = date('Y', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
          }          
          $patterns[$cid][$year][$month][$day] = $data;
        }

        break;

      case 'month':

        for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+' . $data['each'] . ' month' , $timestamp )){            
          switch($data['day_of_the_week']){
            case 'nvt':
              switch($data['on'])
              {
                case 'first':
                  $day = date('d', strtotime('first day of this month', $timestamp));
                  $month = date('m', strtotime('first day of this month', $timestamp));
                  $year = date('Y', strtotime('first day of this month', $timestamp));

                  break;

                case 'last':
                  $day = date('d', strtotime('last day of this month', $timestamp));
                  $month = date('m', strtotime('last day of this month', $timestamp));
                  $year = date('Y', strtotime('last day of this month', $timestamp));
                  break;

                case 'next': 
                  $day = date('d', strtotime( '+1 day' , $timestamp ));
                  $month = date('m', strtotime( '+1 day' , $timestamp ));
                  $year = date('Y', strtotime( '+1 day' , $timestamp ));
                  break; 

                case 'previous': 
                  $day = date('d', strtotime( '-1 day' , $timestamp ));
                  $month = date('m', strtotime( '-1 day' , $timestamp ));
                  $year = date('Y', strtotime( '-1 day' , $timestamp ));
                  break;

                default :
                  $day = date('d', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
                  $month = date('m', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
                  $year = date('Y', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
              }
            break;

            default:
              switch($data['on'])
              {
                case 'first':
                  $day = date('d', strtotime('first ' . $data['day_of_the_week'] . ' of this month', $timestamp));
                  $month = date('m', strtotime('first ' . $data['day_of_the_week'] . ' of this month', $timestamp));
                  $year = date('Y', strtotime('first ' . $data['day_of_the_week'] . ' of this month', $timestamp));
                  break;

                case 'last':
                  $day = date('d', strtotime('last ' . $data['day_of_the_week'] . ' of this month', $timestamp));
                  $month = date('m', strtotime('last ' . $data['day_of_the_week'] . ' of this month', $timestamp));
                  $year = date('Y', strtotime('last ' . $data['day_of_the_week'] . ' of this month', $timestamp));
                  break;

                case 'next': 
                  $day = date('d', strtotime('next ' . $data['day_of_the_week'], $timestamp));
                  $month = date('m', strtotime('next ' . $data['day_of_the_week'], $timestamp));
                  $year = date('Y', strtotime('next ' . $data['day_of_the_week'], $timestamp));
                  break; 

                case 'previous': 
                  $day = date('d', strtotime('previous ' . $data['day_of_the_week'], $timestamp));
                  $month = date('m', strtotime('previous ' . $data['day_of_the_week'], $timestamp));
                  $year = date('Y', strtotime('previous ' . $data['day_of_the_week'], $timestamp));
                  break;

                default :
                  $day = date('d', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
                  $month = date('m', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
                  $year = date('Y', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
              }                
          }
          $patterns[$cid][$year][$month][$day] = $data;
        }

        break;

      case 'year':

        for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+' . $data['each'] . ' year' , $timestamp )){            
          $year = date('Y', $timestamp);

          switch($data['day_of_the_week']){
            case 'nvt':
              switch($data['on'])
              {
                case 'first':
                  $day = '01';
                  $month = '01';
                  $year = date('Y', $timestamp);
                  break;

                case 'last':
                  $day = '31';
                  $month = '12';
                  $year = date('Y', $timestamp);
                  break;

                case 'next': 
                  $day = date('d', strtotime( '+1 day' , $timestamp ));
                  $month = date('m', strtotime( '+1 day' , $timestamp ));
                  $year = date('Y', strtotime( '+1 day' , $timestamp ));
                  break; 

                case 'previous': 
                  $day = date('d', strtotime( '-1 day' , $timestamp ));
                  $month = date('m', strtotime( '-1 day' , $timestamp ));
                  $year = date('Y', strtotime( '-1 day' , $timestamp ));
                  break;

                default :
                  $day = date('d', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
                  $month = date('m', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
                  $year = date('Y', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
              }
            break;

            default:
              switch($data['on'])
              {
                case 'first':
                  $day = date('d', strtotime('first ' . $data['day_of_the_week'] . ' of this month', strtotime($year . '-01-01')));
                  $month = date('m', strtotime('first ' . $data['day_of_the_week'] . ' of this month', strtotime($year . '-01-01')));
                  break;

                case 'last':
                  $day = date('d', strtotime('last ' . $data['day_of_the_week'] . ' of this month', strtotime($year . '-12-31')));
                  $month = date('m', strtotime('last ' . $data['day_of_the_week'] . ' of this month', strtotime($year . '-12-31')));
                  break;

                case 'next': 
                  $day = date('d', strtotime('next ' . $data['day_of_the_week'], $timestamp));
                  $month = date('m', strtotime('next ' . $data['day_of_the_week'], $timestamp));
                  break; 

                case 'previous': 
                  $day = date('d', strtotime('previous ' . $data['day_of_the_week'], $timestamp));
                  $month = date('m', strtotime('previous ' . $data['day_of_the_week'], $timestamp));
                  break;

                default :                  
                  $day = date('d', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
                  $month = date('m', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
                  $year = date('Y', strtotime($data['day_of_the_week'] . ' this week', $timestamp));
              }                
          }
          $patterns[$cid][$year][$month][$day] = $data;
        }
        break;
    }
    
    return $patterns;
  }
  
  private function set_total()
  {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('Total'));
    }
    
    if(empty($this->total)){
      $this->set_error(ts('No total !'), ts('Total'));
    }
        
    if($this->isset_error()){
      return false;
    }
    
    foreach($this->data as $cid => $years){
      
      foreach($years as $year => $months){
        
        $this->total[$cid][$year]['used'] = 0;
        $this->total[$cid][$year]['over'] = 0;
        
        foreach($months as $month => $days){
          foreach($days as $day => $data){
              
            // normal_leave
            if(isset($data['normal_leave']['is_normal_leave']) and 1 == $data['normal_leave']['is_normal_leave'] and 'approved' == $data['normal_leave']['status']){
              $this->total[$cid][$year]['used'] = $this->total[$cid][$year]['used'] + $data['normal_leave']['duration'];
            }
            
            // used time for time
            if(isset($data['time_for_time']['is_time_for_time']) and 1 == $data['time_for_time']['is_time_for_time'] and 'approved' == $data['time_for_time']['status']){
              $this->total[$cid][$year]['used'] = $this->total[$cid][$year]['used'] - $data['time_for_time']['duration'];
            }
          }
        }
        
        if(!isset($this->total[$cid][$year]['credit_total']) or '' == $this->total[$cid][$year]['credit_total']){
          $this->total[$cid][$year]['credit_total'] = 0;
        }
        
        $this->total[$cid][$year]['over'] = $this->total[$cid][$year]['credit_total'] + $this->total[$cid][$year]['credit_total_over'] - $this->total[$cid][$year]['used'];
      }
    }
    
    return true;
  }
  
  public function overview($cid, $from_date, $to_date)
  {
    $query = "SELECT civicrm_activity.source_record_id, " . $this->custom_groups['leave_request']['table_name'] . ".id, " . $this->custom_groups['leave_request']['table_name'] . ".entity_id";
    
    $query .= ", " . $this->custom_fields['leave_request_leave_type']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_reason']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_status']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_from_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_to_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_day_week_month_year']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_on']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_duration']['column_name'];
        
    $query .= " FROM " . $this->custom_groups['leave_request']['table_name'];
    $query .= " LEFT JOIN civicrm_activity ON " . $this->custom_groups['leave_request']['table_name'] . ".entity_id = civicrm_activity.id";
    $query .= " WHERE civicrm_activity.activity_type_id = '" . trim($this->custom_groups['leave_request']['extends_entity_column_value']) . "'";
    
    $query .= " AND civicrm_activity.source_record_id = '" . $cid . "'";
        
    if('' !== $from_date and '' !== $to_date){
      $query .= " AND (";
      
      // from_date to_date
      $query .= " ('" . $from_date . " 00:00:00' <= " . $this->custom_fields['leave_request_from_date']['column_name'] . " AND '" . $to_date . " 23:59:59' >= " . $this->custom_fields['leave_request_from_date']['column_name'] . " )";
      $query .= " OR ('" . $from_date . " 00:00:00' <= " . $this->custom_fields['leave_request_to_date']['column_name'] . " AND '" . $to_date . " 23:59:59' >= " . $this->custom_fields['leave_request_to_date']['column_name'] . " )";
      $query .= " OR ('" . $from_date . " 00:00:00' >= " . $this->custom_fields['leave_request_from_date']['column_name'] . " AND '" . $to_date . " 23:59:59' <= " . $this->custom_fields['leave_request_to_date']['column_name'] . " )";
      
      // date
      $query .= " OR ('" . $from_date . " 00:00:00' <= " . $this->custom_fields['leave_request_date']['column_name'] . " AND '" . $to_date . " 23:59:59' >= " . $this->custom_fields['leave_request_date']['column_name'] . ")";
      
      $query .= ")";
    }

    $query .= " ORDER BY " . $this->custom_fields['leave_request_from_date']['column_name'] . ", " . $this->custom_fields['leave_request_date']['column_name'] . " ASC";
        
    $data = array();
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $data[$dao->id] = array
      (
        'id' => $dao->id,
        'entity_id' => $dao->entity_id,
        'cid' => $dao->source_record_id,
        'leave_type' => $dao->{$this->custom_fields['leave_request_leave_type']['column_name']},
        'reason' => $dao->{$this->custom_fields['leave_request_reason']['column_name']},
        'status' => $dao->{$this->custom_fields['leave_request_status']['column_name']},
        'from_date' => $dao->{$this->custom_fields['leave_request_from_date']['column_name']},
        'to_date' => $dao->{$this->custom_fields['leave_request_to_date']['column_name']},
        'date' => $dao->{$this->custom_fields['leave_request_date']['column_name']},
        'each' => $dao->{$this->custom_fields['leave_request_each']['column_name']},
        'day_week_month_year' => $dao->{$this->custom_fields['leave_request_day_week_month_year']['column_name']},
        'on' => $dao->{$this->custom_fields['leave_request_on']['column_name']},
        'day_of_the_week' => $dao->{$this->custom_fields['leave_request_date']['column_name']},
        'duration' => $dao->{$this->custom_fields['leave_request_day_of_the_week']['column_name']}
      );
    }
    
    return $data;
  }
  
  public function check($cids, $leave_type, $id = '', $from_date = '', $to_date = '', $date = '', $do_not_time_for_time = true, $limit = true)
  {
    $query = "SELECT civicrm_activity.source_record_id, " . $this->custom_groups['leave_request']['table_name'] . ".id, " . $this->custom_groups['leave_request']['table_name'] . ".entity_id";
    
    $query .= ", " . $this->custom_fields['leave_request_leave_type']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_reason']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_status']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_from_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_to_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_day_week_month_year']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_on']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_duration']['column_name'];
    
    $query .= ", civicrm_contact.display_name";
    
    $query .= " FROM " . $this->custom_groups['leave_request']['table_name'];
    $query .= " LEFT JOIN civicrm_activity ON " . $this->custom_groups['leave_request']['table_name'] . ".entity_id = civicrm_activity.id";
    $query .= " LEFT JOIN civicrm_contact ON civicrm_activity.source_record_id = civicrm_contact.id";
    $query .= " WHERE civicrm_activity.activity_type_id = '" . trim($this->custom_groups['leave_request']['extends_entity_column_value']) . "'";
            
    $query .= " AND (";
    foreach($cids as $key => $cid){
      $query .= " civicrm_activity.source_record_id = '" . $cid . "' OR";
    }
    
    $query = substr($query, 0, -2) . ")";
                
    /*'atv' => 'ATV', //
    'special_leave' => 'Special leave', // buitengewoon verlof
    'doctor_visit' => 'Doctor visit', // dokter bezoek
    'normal_leave' => 'Normal leave', // normaal verlof
    'normal_leave_less_one_day' => 'Normal leave < 1 DAY', // normaal verlof < 1 dag
    //'mom_dad_day' => 'Mom/Dad day',
    'mom_dad_day' => 'Parental', // ouderschapsverlof
    //'parental' => 'Parental', // ouderschapsverlof
    'study_leave' => 'Study leave', // studieverlof
    'time_for_time' => 'Time for time', // tijd voor tijd
    'care' => 'Care', // zorgverlof
    'maternity' => 'Maternity', // zwangerschapsverlof
    'sick' => 'Sick', // ziek
    'sick_less_one_day' => 'Sick < 1 day', // ziek < 1 dag*/
    
    /*switch($leave_type)
    {
      case 'sick':
      case 'sick_less_one_day':
      case 'doctor_visit':
        $query .= " AND " . $this->custom_fields['leave_request_leave_type']['column_name'] . " = '" . $leave_type . "'";
        break;
      
      case 'maternity':
      case 'care':  
      case 'study_leave':
      case 'mom_dad_day':
        $options = array('maternity', 'care', 'study_leave', 'mom_dad_day');
        $query .= " AND (";
        foreach($options as $key => $value){
          $query .= " " . $this->custom_fields['leave_request_leave_type']['column_name'] . " = '" . $key . "' OR";
        }
        $query = substr($query, 0, -2) . ")";
        break;
        
      case 'time_for_time':
        $query .= " AND (";
        foreach($this->option_group['leave_request_leave_type']['options'] as $key => $value){ // everything
          $query .= " " . $this->custom_fields['leave_request_leave_type']['column_name'] . " = '" . $key . "' OR";
        }
        $query = substr($query, 0, -2) . ")";
        break;
        
      case 'normal_leave':
      case 'special_leave':
      case 'atv':
        $options = array('normal_leave', 'special_leave', 'atv');
        $query .= " AND (";
        foreach($options as $key => $value){
          $query .= " " . $this->custom_fields['leave_request_leave_type']['column_name'] . " = '" . $key . "' OR";
        }
        $query = substr($query, 0, -2) . ")";
        break;
    } */  
    if($do_not_time_for_time){
      $query .= " AND " . $this->custom_fields['leave_request_leave_type']['column_name'] . " != 'time_for_time'";
    }
    
    if('' !== $id){
      $query .= " AND civicrm_activity.id != '" . $id . "'";
    }
    
    if('' !== $from_date and '' !== $to_date){
      $query .= " AND (";
      
      // from_date to_date
      $query .= " ('" . $from_date . " 00:00:00' <= " . $this->custom_fields['leave_request_from_date']['column_name'] . " AND '" . $to_date . " 23:59:59' >= " . $this->custom_fields['leave_request_from_date']['column_name'] . " )";
      $query .= " OR ('" . $from_date . " 00:00:00' <= " . $this->custom_fields['leave_request_to_date']['column_name'] . " AND '" . $to_date . " 23:59:59' >= " . $this->custom_fields['leave_request_to_date']['column_name'] . " )";
      $query .= " OR ('" . $from_date . " 00:00:00' >= " . $this->custom_fields['leave_request_from_date']['column_name'] . " AND '" . $to_date . " 23:59:59' <= " . $this->custom_fields['leave_request_to_date']['column_name'] . " )";
      
      // date
      $query .= " OR ('" . $from_date . " 00:00:00' <= " . $this->custom_fields['leave_request_date']['column_name'] . " AND '" . $to_date . " 23:59:59' >= " . $this->custom_fields['leave_request_date']['column_name'] . ")";
      
      $query .= ")";
    }
    
    if('' !== $date){
      $query .= " AND (";
      
      // from_date to_date
      $query .= " ('" . $date . " 00:00:00' >= " . $this->custom_fields['leave_request_from_date']['column_name'] . " AND '" . $date . " 23:59:59' <= " . $this->custom_fields['leave_request_to_date']['column_name'] . " )";
      
      // date
      $query .= " OR ('" . $date . "' = " . $this->custom_fields['leave_request_date']['column_name'] . ")";
      
      $query .= ")";
    }
    
    $query .= " ORDER BY " . $this->custom_fields['leave_request_from_date']['column_name'] . ", " . $this->custom_fields['leave_request_date']['column_name'] . " ASC";
    
    if($limit){
      $query .= " LIMIT 1";
    }
    
    $datas = array();
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $datas[$dao->id] = array
      (
        'id' => $dao->id,
        'entity_id' => $dao->entity_id,
        'cid' => $dao->source_record_id,
        'display_name' => $dao->display_name,
        'leave_type' => $dao->{$this->custom_fields['leave_request_leave_type']['column_name']},
        'reason' => $dao->{$this->custom_fields['leave_request_reason']['column_name']},
        'status' => $dao->{$this->custom_fields['leave_request_status']['column_name']},
        'from_date' => $dao->{$this->custom_fields['leave_request_from_date']['column_name']},
        'to_date' => $dao->{$this->custom_fields['leave_request_to_date']['column_name']},
        'date' => $dao->{$this->custom_fields['leave_request_date']['column_name']},
        'each' => $dao->{$this->custom_fields['leave_request_each']['column_name']},
        'day_week_month_year' => $dao->{$this->custom_fields['leave_request_day_week_month_year']['column_name']},
        'on' => $dao->{$this->custom_fields['leave_request_on']['column_name']},
        'day_of_the_week' => $dao->{$this->custom_fields['leave_request_date']['column_name']},
        'duration' => $dao->{$this->custom_fields['leave_request_duration']['column_name']}
      );
    }
    
    /*
    // first mom_dad_day
    foreach($datas as $id => $data){
      switch($data['leave_type']){
        case 'mom_dad_day':
        case 'study_leave':
        case 'care':
          $this->set_pattern($data, 'check');
          break;
      }
    }
        
    // second the rest
    foreach($datas as $id => $data){
                 
      switch($data['leave_type']){
        case 'time_for_time': // always add time for time
          $timestamp = strtotime($data['date']);
          
          $year = date('Y', $timestamp);
          $month = date('m', $timestamp);
          $day = date('d', $timestamp);
          
          list($hours, $minutes) = explode(':', $data['duration']);
          $duration = ($hours * 60) + $minutes;
          
          $this->data[$data['cid']][$year][$month][$day]['time_for_time']['is_time_for_time'] = true;
          $this->data[$data['cid']][$year][$month][$day]['time_for_time']['leave_type'] = 'time_for_time';
          $this->data[$data['cid']][$year][$month][$day]['time_for_time']['reason'] = $data['reason'];
          $this->data[$data['cid']][$year][$month][$day]['time_for_time']['status'] = $data['status'];
          $this->data[$data['cid']][$year][$month][$day]['time_for_time']['duration'] = $duration;
          
          break;
        
        case 'doctor_visit':  
        case 'normal_leave_less_one_day': 
        case 'sick_less_one_day': 
          $timestamp = strtotime($data['date']);
          
          $year = date('Y', $timestamp);
          $month = date('m', $timestamp);
          $day = date('d', $timestamp);
          
          list($hours, $minutes) = explode(':', $data['duration']);
          $duration = ($hours * 60) + $minutes;
             
          // is holiday
          if(isset($this->data[$data['cid']][$year][$month][$day]['holiday']['is_holiday']) and ($this->data[$data['cid']][$year][$month][$day]['holiday']['is_holiday'])){
            $duration = 0;
          }
          
          if(!isset($this->data[$data['cid']][$year][$month][$day]['request'])){
            $this->data[$data['cid']][$year][$month][$day]['request'] = array();
          }
          
          if(!isset($this->data[$data['cid']][$year][$month][$day]['request']['leave_type'])){ // if leave_type don exists
            $this->data[$data['cid']][$year][$month][$day]['request']['is_request'] = true;
            $this->data[$data['cid']][$year][$month][$day]['request']['leave_type'] = $data['leave_type'];
            $this->data[$data['cid']][$year][$month][$day]['request']['reason'] = $data['reason'];
            $this->data[$data['cid']][$year][$month][$day]['request']['status'] = $data['status'];
            $this->data[$data['cid']][$year][$month][$day]['request']['duration'] = $duration;
            
          }else { // check weight
            if($this->weight[$data['leave_type']] < $this->weight[$this->data[$data['cid']][$year][$month][$day]['request']['leave_type']]){ // if weight is lower
              $this->data[$data['cid']][$year][$month][$day]['request']['is_request'] = true;
              $this->data[$data['cid']][$year][$month][$day]['request']['leave_type'] = $data['leave_type'];
              $this->data[$data['cid']][$year][$month][$day]['request']['reason'] = $data['reason'];
              $this->data[$data['cid']][$year][$month][$day]['request']['status'] = $data['status'];
              $this->data[$data['cid']][$year][$month][$day]['request']['duration'] = $duration;
            }
          }
          break;
                  
        case 'mom_dad_day':
        case 'study_leave':
        case 'care':
          //$this->set_pattern($data, 'request');
          break;
        
        default:
          $from_date_totime = strtotime($data['from_date']);
          $to_date_totime = strtotime($data['to_date']);
          
          for($timestamp = $from_date_totime; $timestamp <= $to_date_totime; $timestamp = strtotime( '+1 day' , $timestamp )){            
            $year = date('Y', $timestamp);
            $month = date('m', $timestamp);
            $day = date('d', $timestamp);
                    
            if(!isset($this->data[$data['cid']][$year][$month][$day]['request'])){
              $this->data[$data['cid']][$year][$month][$day]['request'] = array();
            }
            
            $duration = 0;
            if(isset($this->data[$data['cid']][$year][$month][$day]['adjustments']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['adjustments']['duration']){
              $duration = $this->data[$data['cid']][$year][$month][$day]['adjustments']['duration'];
                  
            }else if(isset($this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration']){
              $duration = $this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration'];
              
            }else if(isset($this->data[$data['cid']][$year][$month][$day]['general']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['general']['duration']){
              $duration = $this->data[$data['cid']][$year][$month][$day]['general']['duration'];
            }
            
            // is holiday
            if(isset($this->data[$data['cid']][$year][$month][$day]['holiday']['is_holiday']) and ($this->data[$data['cid']][$year][$month][$day]['holiday']['is_holiday'])){
              $duration = 0;
            }
            
            if(!isset($this->data[$data['cid']][$year][$month][$day]['request']['leave_type'])){ // if leave_type don exists
              $this->data[$data['cid']][$year][$month][$day]['request']['is_request'] = true;
              $this->data[$data['cid']][$year][$month][$day]['request']['leave_type'] = $data['leave_type'];
              $this->data[$data['cid']][$year][$month][$day]['request']['reason'] = $data['reason'];
              $this->data[$data['cid']][$year][$month][$day]['request']['status'] = $data['status'];
              $this->data[$data['cid']][$year][$month][$day]['request']['duration'] = $duration;

            }else { // check weight
              if($this->weight[$data['leave_type']] < $this->weight[$this->data[$data['cid']][$year][$month][$day]['request']['leave_type']]){ // if weight is lower
                $this->data[$data['cid']][$year][$month][$day]['request']['is_request'] = true;
                $this->data[$data['cid']][$year][$month][$day]['request']['leave_type'] = $data['leave_type'];
                $this->data[$data['cid']][$year][$month][$day]['request']['reason'] = $data['reason'];
                $this->data[$data['cid']][$year][$month][$day]['request']['status'] = $data['status'];
                $this->data[$data['cid']][$year][$month][$day]['request']['duration'] = $duration;
              }
            }
          }
        }
    }*/
    
    return $datas;
  }
  
  public function get($id)
  {
    $query = "SELECT civicrm_activity.source_record_id, " . $this->custom_groups['leave_request']['table_name'] . ".id, " . $this->custom_groups['leave_request']['table_name'] . ".entity_id";
    
    $query .= ", " . $this->custom_fields['leave_request_leave_type']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_reason']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_status']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_from_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_to_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_day_week_month_year']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_on']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_duration']['column_name'];
    
    $query .= " FROM " . $this->custom_groups['leave_request']['table_name'];
    $query .= " LEFT JOIN civicrm_activity ON " . $this->custom_groups['leave_request']['table_name'] . ".entity_id = civicrm_activity.id";
    $query .= " WHERE civicrm_activity.activity_type_id = '" . trim($this->custom_groups['leave_request']['extends_entity_column_value']) . "'";
        
    $query .= " AND civicrm_activity.id = '" . $id . "'";
    
    $data = array();
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $data = array
      (
        'id' => $dao->id,
        'entity_id' => $dao->entity_id,
        'cid' => $dao->source_record_id,
        'leave_type' => $dao->{$this->custom_fields['leave_request_leave_type']['column_name']},
        'reason' => $dao->{$this->custom_fields['leave_request_reason']['column_name']},
        'status' => $dao->{$this->custom_fields['leave_request_status']['column_name']},
        'from_date' => $dao->{$this->custom_fields['leave_request_from_date']['column_name']},
        'to_date' => $dao->{$this->custom_fields['leave_request_to_date']['column_name']},
        'each' => $dao->{$this->custom_fields['leave_request_each']['column_name']},
        'day_week_month_year' => $dao->{$this->custom_fields['leave_request_day_week_month_year']['column_name']},
        'on' => $dao->{$this->custom_fields['leave_request_on']['column_name']},
        'day_of_the_week' => $dao->{$this->custom_fields['leave_request_day_of_the_week']['column_name']},
        'date' => $dao->{$this->custom_fields['leave_request_date']['column_name']},
        'duration' => $dao->{$this->custom_fields['leave_request_duration']['column_name']}
      );
    }
    
    return $data;
  }
  
  public function create($cid, $values)
  {
    // civicrm_activity
    $query = "INSERT INTO civicrm_activity (id, source_record_id, activity_type_id, activity_date_time, status_id, priority_id, is_test, is_auto, is_current_revision, is_deleted)";
    $query .= " VALUES ('', '" . $cid . "', '" . $this->custom_groups['leave_request']['extends_entity_column_value'] . "','" . date('Y-m-d H:i:s') . "', '" . $values['status_id'] . "', '2', '0', '0', '1', '0') ";
    
    $dao = CRM_Core_DAO::executeQuery($query);

    $last_inserted_id = CRM_Core_DAO::singleValueQuery('SELECT LAST_INSERT_ID()');
    
    // civicrm_leave_request
    $query = "INSERT INTO " . $this->custom_groups['leave_request']['table_name'] . " (id, entity_id";
    $query .= ", " . $this->custom_fields['leave_request_leave_type']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_reason']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_status']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_from_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_to_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_day_week_month_year']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_on']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_duration']['column_name'];
    $query .= ")";

    $query .= " VALUES ('" . $last_inserted_id . "', '" . $last_inserted_id . "'";
    $query .= ", '" . $values['leave_type'] . "'";
    $query .= ", '" . mysql_real_escape_string($values['reason']) . "'";
    $query .= ", '" . $values['status'] . "'";
    $query .= ", '" . $values['from_date'] . "'";
    $query .= ", '" . $values['to_date'] . "'";
    $query .= ", '" . $values['date'] . "'";
    $query .= ", '" . $values['each'] . "'";
    $query .= ", '" . $values['day_week_month_year'] . "'";
    $query .= ", '" . $values['on'] . "'";
    $query .= ", '" . $values['day_of_the_week'] . "'";
    $query .= ", '" . $values['duration'] . "'";
    $query .= ")";

    $dao = CRM_Core_DAO::executeQuery($query);
    $id = CRM_Core_DAO::singleValueQuery('SELECT LAST_INSERT_ID()');
    
    /*
    // civicrm_activity_assignment
    $query = "INSERT INTO civicrm_activity_assignment (id, activity_id, assignee_contact_id)";
    $query .= " VALUES ('', '" . $last_inserted_id . "', '" . $values['assignee_contact_id'] . "') ";

    $dao = CRM_Core_DAO::executeQuery($query);

    // civicrm_activity_target
    $query = "INSERT INTO civicrm_activity_target (id, activity_id, target_contact_id)";
    $query .= " VALUES ('', '" . $last_inserted_id . "', '" . $values['target_contact_id'] . "') ";
    
    $dao = CRM_Core_DAO::executeQuery($query);
    */
    
    // civicrm_activity_contact
    $query = "INSERT INTO civicrm_activity_contact (id, activity_id, contact_id, record_type_id)";
    $query .= " VALUES ('', '" . $last_inserted_id . "', '" . $values['target_contact_id'] . "', '3') ";
    
    $dao = CRM_Core_DAO::executeQuery($query);

    return $id;
  }
  
  public function update($id, $cid, $values)
  {    
    // civicrm_activity
    $query = "UPDATE civicrm_activity SET status_id = '" . $values['status_id'] . "' WHERE id = '" . $id . "'";
        
    $dao = CRM_Core_DAO::executeQuery($query);
    
    // civicrm_leave_request
    $query = "UPDATE " . $this->custom_groups['leave_request']['table_name'];
    
    $query .= " SET";
    $query .= " " . $this->custom_fields['leave_request_leave_type']['column_name'] . " = '" . $values['leave_type'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_reason']['column_name'] . " = '" . mysql_real_escape_string($values['reason']) . "'";
    $query .= ", " . $this->custom_fields['leave_request_status']['column_name'] . " = '" . $values['status'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_from_date']['column_name'] . " = '" . $values['from_date'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_to_date']['column_name'] . " = '" . $values['to_date'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_date']['column_name'] . " = '" . $values['date'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_each']['column_name'] . " = '" . $values['each'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_day_week_month_year']['column_name'] . " = '" . $values['day_week_month_year'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_on']['column_name'] . " = '" . $values['on'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_day_of_the_week']['column_name'] . " = '" . $values['day_of_the_week'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_duration']['column_name'] . " = '" . $values['duration'] . "'";
    
    $query .= " WHERE id = '" . $id . "'";
        
    $dao = CRM_Core_DAO::executeQuery($query);
    
    /*
    // civicrm_activity_assignment
    $query = "UPDATE civicrm_activity_assignment SET assignee_contact_id = '" . $values['assignee_contact_id'] . "' WHERE activity_id = '" . $id . "'";
    
    $dao = CRM_Core_DAO::executeQuery($query);
    
    // civicrm_activity_target
    $query = "UPDATE civicrm_activity_target SET target_contact_id = '" . $values['target_contact_id'] . "' WHERE activity_id = '" . $id . "'";
    
    $dao = CRM_Core_DAO::executeQuery($query);
    */
    
    // civicrm_activity_contact
    $query = "UPDATE civicrm_activity_contact SET contact_id = '" . $values['target_contact_id'] . "' WHERE activity_id = '" . $id . "' AND record_type_id = '3'";
    
    $dao = CRM_Core_DAO::executeQuery($query);
    
    return $id;
  }
  
  public function delete($id)
  {
    // civicrm_leave_request
    $query = "DELETE FROM " . $this->custom_groups['leave_request']['table_name'] . " WHERE id = '" . $id . "'";
    
    $dao = CRM_Core_DAO::executeQuery($query);
    
    /*
    // civicrm_activity_target
    $query = "DELETE FROM civicrm_activity_target WHERE activity_id = '" . $id . "'";

    $dao = CRM_Core_DAO::executeQuery($query);
    
    // civicrm_activity_assignment
    $query = "DELETE FROM civicrm_activity_assignment WHERE activity_id = '" . $id . "'";

    $dao = CRM_Core_DAO::executeQuery($query);
    */
    // civicrm_activity_contact
    $query = "DELETE FROM civicrm_activity_contact WHERE activity_id = '" . $id . "'";

    $dao = CRM_Core_DAO::executeQuery($query);
    
    // civicrm_activity
    $query = "DELETE FROM civicrm_activity WHERE id = '" . $id . "'";

    $dao = CRM_Core_DAO::executeQuery($query);

    return $id;
  }
  
  private function ical_date($timestamp) {
    return date('Ymd\THis\Z', $timestamp);
  }
 
  // Escapes a string of characters
  private function ical_escape($string) {
    return preg_replace('/([\,;])/','\\\$1', $string);
  }
  
  public function ical($id, $location, $uri, $summary)
  {
    $data = $this->get($id);
    
    header('Content-type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename=iCal.ical');
        
    $body = 'BEGIN:VCALENDAR' . PHP_EOL;
    $body .= 'VERSION:2.0' . PHP_EOL;
    $body .= 'PRODID:-//hacksw/handcal//NONSGML v1.0//EN' . PHP_EOL;
    $body .= 'CALSCALE:GREGORIAN' . PHP_EOL;
    $body .= 'BEGIN:VEVENT' . PHP_EOL;
    
    $message = array();
    switch($data['leave_type'])
    {
      case 'time_for_time':
      case 'doctor_visit':
      case 'normal_leave_less_one_day':
      case 'sick_less_one_day':
        $message[] = t('Date: ') . strftime("%d-%m-%Y", strtotime($data['date']));
        $message[] = t('Duration: ') . $data['duration'];
        break;

      case 'mom_dad_day':
      case 'study_leave':
      case 'care':
        $message[] = t('From date: ') . strftime("%d-%m-%Y", strtotime($data['from_date']));
        $message[] = t('To date: ') . strftime("%d-%m-%Y", strtotime($data['to_date']));
        $message[] = t('Each: ') . t($data['each']) . ' ' . t($data['day_week_month_year']);
        if('nvt' != $data['on']){
          $message[count($message)-1] .= ' ' . t('on') . ' ' . t($data['option_group']['leave_request_on']['options'][$data['on']]);
        }
        if('nvt' != $data['day_of_the_week']){
          $message[count($message)-1] .= ' ' . t($data['day_of_the_week']);
        }
        break;

      default:
        $message[] = t('From date: ') . strftime("%d-%m-%Y", strtotime($data['from_date']));
        $message[] = t('To date: ') . strftime("%d-%m-%Y", strtotime($data['to_date']));
    }

    $message[] = t('The status of the leave request is: ') . t($data['option_group']['leave_request_status']['options'][$data['status']]);

    if(!empty($data['reason'])){
      $message[] = t('Reason: ');
      $message[] = $data['reason'];
    }
    
    $description = implode(PHP_EOL, $message);

    
    switch($data['leave_type'])
    {
      case 'time_for_time': // always add time for time
      case 'doctor_visit':  
      case 'normal_leave_less_one_day': 
      case 'sick_less_one_day': 
        $timestamp = strtotime($data['date']);
        
        $body .= 'DTSTART:' . $this->ical_date($timestamp) . PHP_EOL;
        $body .= 'DTEND:' . $this->ical_date($timestamp) . PHP_EOL;
        break;

      case 'mom_dad_day':
      case 'study_leave':
      case 'care':
        //$this->set_pattern($data, 'request');
        break;

      default:
        $from_date_totime = strtotime($data['from_date']);
        $to_date_totime = strtotime($data['to_date']);
        
        $body .= 'DTSTART:' . $this->ical_date($from_date_totime) . PHP_EOL;
        $body .= 'DTEND:' . $this->ical_date($to_date_totime) . PHP_EOL;
    }
    
    $body .= 'UID:' . uniqid() . PHP_EOL;
    $body .= 'DTSTAMP:' . $this->ical_date(time()) . PHP_EOL;
    $body .= 'LOCATION:' . $this->ical_escape($location) . PHP_EOL;
    $body .= 'DESCRIPTION:' . $description . PHP_EOL;
    $body .= 'URL;VALUE=URI:' . $this->ical_escape($uri) . PHP_EOL;
    $body .= 'SUMMARY:' . $this->ical_escape($summary) . PHP_EOL;
    
    $body .= 'END:VEVENT' . PHP_EOL;
    $body .= 'END:VCALENDAR' . PHP_EOL;
    
    echo($body);
    exit();
  }
}
?>