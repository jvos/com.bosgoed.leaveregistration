<?php
set_time_limit(0);

class leaveregistration {
  private $contact_sub_types = array(
    'business' => array('contact_type' => 'Organization', 'contact_sub_type' => 'Business', 'parent_id' => '3'),
    'department' => array('contact_type' => 'Organization', 'contact_sub_type' => 'Department', 'parent_id' => '3'),
    'employee' => array('contact_type' => 'Individual', 'contact_sub_type' => 'Employee', 'parent_id' => '1')
  );
  
  private $relationship_types = array(
    'employee_of' => array('name_a_b' => 'Employee of', 'description' => 'Employee relationship.', 'contact_type_a' => 'Individual', 'contact_type_b' => 'Organization', 'contact_sub_type_a' => 'Employee', 'contact_sub_type_b' => 'Department'),
    'department_of' => array('name_a_b' => 'Department of', 'description' => 'Department relationship.', 'contact_type_a' => 'Organization', 'contact_type_b' => 'Organization', 'contact_sub_type_a' => 'Department', 'contact_sub_type_b' => 'Business'),
    'department_head' => array('name_a_b' => 'Department head', 'description' => 'Department head relationship.', 'contact_type_a' => 'Organization', 'contact_type_b' => 'Individual', 'contact_sub_type_a' => 'Department', 'contact_sub_type_b' => 'Employee'),
    'main_organization' => array('name_a_b' => 'Main Organization of', 'description' => 'Main Organization relationship.', 'contact_type_a' => 'Organization', 'contact_type_b' => 'Organization', 'contact_sub_type_a' => 'Business', 'contact_sub_type_b' => 'Business'),
    'administration_of' => array('name_a_b' => 'Administration of', 'description' => 'Administration relationship.', 'contact_type_a' => 'Individual', 'contact_type_b' => 'Organization', 'contact_sub_type_a' => 'Employee', 'contact_sub_type_b' => 'Business'),
  );
  
  private $custom_groups = array(
    'leave_holidays' => array('name' => 'leave_holidays', 'title' => 'Leave - Holidays', 'extends' => 'Organization', 'extends_entity_column_value' => 'DepartmentBusiness', 'style' => 'Tab', 'title_en_US' => 'Leave - Holidays', 'is_multiple' => '1'),
    'leave_general' => array('name' => 'leave_general', 'title' => 'Leave - General', 'extends' => 'Organization', 'extends_entity_column_value' => 'DepartmentBusiness', 'style' => 'Tab', 'title_en_US' => 'Leave - General', 'is_multiple' => '0'),
    'leave_overall_adjustments' => array('name' => 'leave_overall_adjustments', 'title' => 'Leave - Overall adjustments', 'extends' => 'Organization', 'extends_entity_column_value' => 'DepartmentBusiness', 'style' => 'Tab', 'title_en_US' => 'Leave - Overall adjustments', 'is_multiple' => '1'),
    'leave_overall_credit' => array('name' => 'leave_overall_credit', 'title' => 'Leave - Overall credit', 'extends' => 'Organization', 'extends_entity_column_value' => 'DepartmentBusiness', 'style' => 'Tab', 'title_en_US' => 'Leave - Overall credit', 'is_multiple' => '1'),
    
    'leave_settings' => array('name' => 'leave_settings', 'title' => 'Leave - Settings', 'extends' => 'Individual', 'extends_entity_column_value' => 'Employee', 'style' => 'Tab', 'title_en_US' => 'Leave - Settings', 'is_multiple' => '0'),
    'leave_adjustments' => array('name' => 'leave_adjustments', 'title' => 'Leave - Adjustments', 'extends' => 'Individual', 'extends_entity_column_value' => 'Employee', 'style' => 'Tab', 'title_en_US' => 'Leave - Adjustments', 'is_multiple' => '1'),
    'leave_credit' => array('name' => 'leave_credit', 'title' => 'Leave - Credit', 'extends' => 'Individual', 'extends_entity_column_value' => 'Employee', 'style' => 'Tab', 'title_en_US' => 'Leave - Credit', 'is_multiple' => '1'),
    'leave_request' => array('name' => 'leave_request', 'title' => 'Leave - Request', 'extends' => 'Activity', 'extends_entity_column_value' => '', 'style' => 'Inline', 'title_en_US' => 'Leave - Request', 'is_multiple' => '0'),
  );
  
  private $custom_fields = array(
    'leave_general_monday' => array('name' => 'leave_general_monday', 'label' => 'Monday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Monday must be hours and minutes separated by :', 'custom_group_name' => 'leave_general'),
    'leave_general_tuesday' => array('name' => 'leave_general_tuesday', 'label' => 'Tuesday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Tuesday must be hours and minutes separated by :', 'custom_group_name' => 'leave_general'),
    'leave_general_wednesday' => array('name' => 'leave_general_wednesday', 'label' => 'Wednesday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Wednesday must be hours and minutes separated by :', 'custom_group_name' => 'leave_general'),
    'leave_general_thursday' => array('name' => 'leave_general_thursday', 'label' => 'Thursday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Thursday must be hours and minutes separated by :', 'custom_group_name' => 'leave_general'),
    'leave_general_friday' => array('name' => 'leave_general_friday', 'label' => 'Friday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Friday must be hours and minutes separated by :', 'custom_group_name' => 'leave_general'),
    'leave_general_saturday' => array('name' => 'leave_general_saturday', 'label' => 'Saturday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '0:00', 'is_required' => '1', 'help_pre' => 'Saturday must be hours and minutes separated by :', 'custom_group_name' => 'leave_general'),
    'leave_general_sunday' => array('name' => 'leave_general_sunday', 'label' => 'Sunday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '0:00', 'is_required' => '1', 'help_pre' => 'Sunday must be hours and minutes separated by :', 'custom_group_name' => 'leave_general'),
    'leave_general_total_leave' => array('name' => 'leave_general_total_leave', 'label' => 'Total leave', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '200:00', 'is_required' => '1', 'help_pre' => 'Total leave must be hours and minutes separated by :', 'custom_group_name' => 'leave_general'),
		
    'leave_overall_adjustments_start_date' => array('name' => 'leave_overall_adjustments_start_date', 'label' => 'Start date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'custom_group_name' => 'leave_overall_adjustments'),
    'leave_overall_adjustments_end_date' => array('name' => 'leave_overall_adjustments_end_date', 'label' => 'End date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'custom_group_name' => 'leave_overall_adjustments'),
    'leave_overall_adjustments_monday' => array('name' => 'leave_overall_adjustments_monday', 'label' => 'Monday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Monday must be hours and minutes separated by :', 'custom_group_name' => 'leave_overall_adjustments'),
    'leave_overall_adjustments_tuesday' => array('name' => 'leave_overall_adjustments_tuesday', 'label' => 'Tuesday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Tuesday must be hours and minutes separated by :', 'custom_group_name' => 'leave_overall_adjustments'),
    'leave_overall_adjustments_wednesday' => array('name' => 'leave_overall_adjustments_wednesday', 'label' => 'Wednesday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Wednesday must be hours and minutes separated by :', 'custom_group_name' => 'leave_overall_adjustments'),
    'leave_overall_adjustments_thursday' => array('name' => 'leave_overall_adjustments_thursday', 'label' => 'Thursday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Thursday must be hours and minutes separated by :', 'custom_group_name' => 'leave_overall_adjustments'),
    'leave_overall_adjustments_friday' => array('name' => 'leave_overall_adjustments_friday', 'label' => 'Friday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Friday must be hours and minutes separated by :', 'custom_group_name' => 'leave_overall_adjustments'),
    'leave_overall_adjustments_saturday' => array('name' => 'leave_overall_adjustments_saturday', 'label' => 'Saturday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '0:00', 'is_required' => '1', 'help_pre' => 'Saturday must be hours and minutes separated by :', 'custom_group_name' => 'leave_overall_adjustments'),
    'leave_overall_adjustments_sunday' => array('name' => 'leave_overall_adjustments_sunday', 'label' => 'Sunday', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '0:00', 'is_required' => '1', 'help_pre' => 'Sunday must be hours and minutes separated by :', 'custom_group_name' => 'leave_overall_adjustments'),
    
    'leave_overall_credit_from_year' => array('name' => 'leave_overall_credit_from_year', 'label' => 'From year', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy', 'time_format' => 'null', 'help_pre' => '', 'custom_group_name' => 'leave_overall_credit'),
    'leave_overall_credit_to_year' => array('name' => 'leave_overall_credit_to_year', 'label' => 'To year', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '0', 'date_format' => 'yy', 'time_format' => 'null', 'help_pre' => 'If you specify to year than the total leave is untill that year, even if you fill out infinite.', 'custom_group_name' => 'leave_overall_credit'),
    'leave_overall_credit_infinite' => array('name' => 'leave_overall_credit_infinite', 'label' => 'Infinite', 'data_type' => 'Boolean', 'html_type' => 'Radio', 'default_value' => 'Null', 'is_required' => '1', 'help_pre' => 'If it is infinite, it valid for any subsequent years.', 'custom_group_name' => 'leave_overall_credit'),
    'leave_overall_credit_total_leave_per_year' => array('name' => 'leave_overall_credit_total_leave_per_year', 'label' => 'Total leave per year', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '200:00', 'is_required' => '1', 'help_pre' => 'Total leave per year must be hours and minutes separated by :', 'custom_group_name' => 'leave_overall_credit'),
    
    'leave_holidays_name' => array('name' => 'leave_holidays_name', 'label' => 'Name', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => 'Null', 'is_required' => '1', 'custom_group_name' => 'leave_holidays'),
    'leave_holidays_start_date' => array('name' => 'leave_holidays_start_date', 'label' => 'Start date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'custom_group_name' => 'leave_holidays'),
    'leave_holidays_end_date' => array('name' => 'leave_holidays_end_date', 'label' => 'End date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '0', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'custom_group_name' => 'leave_holidays'),
    'leave_holidays_infinite' => array('name' => 'leave_holidays_infinite', 'label' => 'Infinite', 'data_type' => 'Boolean', 'html_type' => 'Radio', 'default_value' => '1', 'is_required' => '1', 'help_pre' => 'If it is infinite, it valid for any subsequent years', 'custom_group_name' => 'leave_holidays'),
    
    'leave_settings_show_all_colleagues_of' => array('name' => 'leave_settings_show_all_colleagues_of', 'label' => 'Show all colleagues of', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'department', 'is_required' => '1', 'help_pre' => 'Show in the calendar all the employees of department or business.', 'option_group_name' => 'leave_settings_show_all_colleagues_of', 'custom_group_name' => 'leave_settings'),
    'leave_settings_show_department_head_to' => array('name' => 'leave_settings_show_department_head_to', 'label' => 'Show department head to', 'data_type' => 'Boolean', 'html_type' => 'Radio', 'default_value' => '0', 'is_required' => '1', 'help_pre' => 'Show in the calendar the department head of the department.', 'custom_group_name' => 'leave_settings'),
    
    'leave_adjustments_start_date' => array('name' => 'leave_adjustments_start_date', 'label' => 'Start date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'custom_group_name' => 'leave_adjustments'),
    'leave_adjustments_end_date' => array('name' => 'leave_adjustments_end_date', 'label' => 'End date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '0', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'custom_group_name' => 'leave_adjustments'),
    'leave_adjustments_infinite' => array('name' => 'leave_adjustments_infinite', 'label' => 'Infinite', 'data_type' => 'Boolean', 'html_type' => 'Radio', 'default_value' => 'Null', 'is_required' => '1', 'help_pre' => 'If it is infinite', 'custom_group_name' => 'leave_adjustments'),
    'leave_adjustments_each' => array('name' => 'leave_adjustments_each', 'label' => 'Each', 'data_type' => 'Int', 'html_type' => 'Text', 'default_value' => '1', 'is_required' => '1', 'help_pre' => 'Example: Each 2 week monday 8:00, <br>Example 2: Each 3 month first friday 6:00', 'custom_group_name' => 'leave_adjustments'),
    'leave_adjustments_day_week_month_year' => array('name' => 'leave_adjustments_day_week_month_year', 'label' => 'Day / Week / Month / Year', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'request', 'is_required' => '1', 'help_pre' => 'Example: week, <br>Example 2: month', 'option_group_name' => 'leave_adjustments_day_week_month_year', 'custom_group_name' => 'leave_adjustments'),
    'leave_adjustments_on' => array('name' => 'leave_adjustments_on', 'label' => 'On', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'nvt', 'is_required' => '0', 'help_pre' => 'Example: n.v.t, <br>Example 2: first', 'option_group_name' => 'leave_adjustments_on', 'custom_group_name' => 'leave_adjustments'),
    'leave_adjustments_day_of_the_week' => array('name' => 'leave_adjustments_day_of_the_week', 'label' => 'Day of the week', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'nvt', 'is_required' => '0', 'help_pre' => 'Example: monday, <br>Example 2: friday', 'option_group_name' => 'leave_adjustments_day_of_the_week', 'custom_group_name' => 'leave_adjustments'),
    'leave_adjustments_duration' => array('name' => 'leave_adjustments_duration', 'label' => 'Duration', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Example: 8:00, <br>Example 2: 6:00. <br>Duration must be hours and minutes separated by :<br/>Duration of hours that a person work, not the duration that a person not work.', 'custom_group_name' => 'leave_adjustments'),
      
    'leave_credit_from_year' => array('name' => 'leave_credit_from_year', 'label' => 'From year', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy', 'time_format' => 'null', 'help_pre' => '', 'custom_group_name' => 'leave_credit'),
    'leave_credit_to_year' => array('name' => 'leave_credit_to_year', 'label' => 'To year', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '0', 'date_format' => 'yy', 'time_format' => 'null', 'help_pre' => 'If you specify to year than the total leave is untill that year, even if you fill out infinite.', 'custom_group_name' => 'leave_credit'),
    'leave_credit_infinite' => array('name' => 'leave_credit_infinite', 'label' => 'Infinite', 'data_type' => 'Boolean', 'html_type' => 'Radio', 'default_value' => 'Null', 'is_required' => '1', 'help_pre' => 'If it is infinite, it valid for any subsequent years', 'custom_group_name' => 'leave_credit'),
    'leave_credit_total_leave_per_year' => array('name' => 'leave_credit_total_leave_per_year', 'label' => 'Total leave per year', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '200:00', 'is_required' => '0', 'help_pre' => 'Total leave per year must be hours and minutes separated by :', 'custom_group_name' => 'leave_credit'),
    'leave_credit_total_leave_per_year_over' => array('name' => 'leave_credit_total_leave_per_year_over', 'label' => 'Total leave per year over', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '0:00', 'is_required' => '0', 'help_pre' => 'Total leave per year over must be hours and minutes separated by :, it can also be negative', 'custom_group_name' => 'leave_credit'),

    'leave_request_leave_type' => array('name' => 'leave_request_leave_type', 'label' => 'Leave type', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'normal_leave', 'is_required' => '1', 'option_group_name' => 'leave_request_leave_type', 'custom_group_name' => 'leave_request'),
    'leave_request_reason' => array('name' => 'leave_request_reason', 'label' => 'Reason', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => 'Null', 'is_required' => '0', 'custom_group_name' => 'leave_request'),
    'leave_request_status' => array('name' => 'leave_request_status', 'label' => 'Status', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'request', 'is_required' => '1', 'option_group_name' => 'leave_request_status', 'custom_group_name' => 'leave_request'),
    'leave_request_from_date' => array('name' => 'leave_request_from_date', 'label' => 'From date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'custom_group_name' => 'leave_request'),
    'leave_request_to_date' => array('name' => 'leave_request_to_date', 'label' => 'To date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'custom_group_name' => 'leave_request'),
    'leave_request_date' => array('name' => 'leave_request_date', 'label' => 'Date', 'data_type' => 'Date', 'html_type' => 'Select Date', 'default_value' => 'Null', 'is_required' => '1', 'date_format' => 'yy-mm-dd', 'time_format' => 'null', 'custom_group_name' => 'leave_request'),
      
    'leave_request_daily_weekly_monthly_annually' => array('name' => 'leave_request_daily_weekly_monthly_annually', 'label' => 'Daily / Weekly / Monthly / Annually', 'data_type' => 'String', 'html_type' => 'Radio', 'default_value' => 'daily', 'is_required' => '1', 'option_group_name' => 'leave_request_daily_weekly_monthly_annually', 'custom_group_name' => 'leave_request'),
      
    'leave_request_daily_every_day_working_day' => array('name' => 'leave_request_daily_every_day_working_day', 'label' => 'Daily - Every day / working day', 'data_type' => 'String', 'html_type' => 'Radio', 'default_value' => 'day', 'is_required' => '1', 'option_group_name' => 'leave_request_daily_every_day_working_day', 'custom_group_name' => 'leave_request'),
    'leave_request_daily_each' => array('name' => 'leave_request_daily_each', 'label' => 'Daily - Each', 'data_type' => 'Int', 'html_type' => 'Text', 'default_value' => '1', 'is_required' => '1', 'custom_group_name' => 'leave_request'),
      
    'leave_request_weekly_each' => array('name' => 'leave_request_weekly_each', 'label' => 'Weekly - Each', 'data_type' => 'Int', 'html_type' => 'Text', 'default_value' => '1', 'is_required' => '1', 'custom_group_name' => 'leave_request'),
    'leave_request_weekly_day_of_the_week' => array('name' => 'leave_request_weekly_day_of_the_week', 'label' => 'Weekly - Day of the week', 'data_type' => 'String', 'html_type' => 'CheckBox', 'default_value' => array('monday'), 'is_required' => '1', 'option_group_name' => 'leave_request_weekly_day_of_the_week', 'custom_group_name' => 'leave_request'),
      
    'leave_request_monthly_each' => array('name' => 'leave_request_monthly_each', 'label' => 'Monthly - Each', 'data_type' => 'Int', 'html_type' => 'Text', 'default_value' => '1', 'is_required' => '1', 'custom_group_name' => 'leave_request'), 
    'leave_request_monthly_every_day_of_the_month_day_of_the_week' => array('name' => 'leave_request_monthly_every_day_of_the_month_day_of_the_week', 'label' => 'Monthly - Every Day of the month / day of the week', 'data_type' => 'String', 'html_type' => 'Radio', 'default_value' => 'every_day_of_the_month', 'is_required' => '1', 'option_group_name' => 'leave_request_monthly_annually_every_day_of_the_month_day_of_the', 'custom_group_name' => 'leave_request'),
    'leave_request_monthly_day_of_the_month' => array('name' => 'leave_request_monthly_day_of_the_month', 'label' => 'Monthly - Day of the month', 'data_type' => 'Int', 'html_type' => 'Text', 'default_value' => '1', 'is_required' => '1', 'custom_group_name' => 'leave_request'),
    'leave_request_monthly_on' => array('name' => 'leave_request_monthly_on', 'label' => 'Monthly - On', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'first', 'is_required' => '1', 'option_group_name' => 'leave_request_monthly_annually_on', 'custom_group_name' => 'leave_request'),
    'leave_request_monthly_day_of_the_week' => array('name' => 'leave_request_monthly_day_of_the_week', 'label' => 'Monthly - Day of the week', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'monday', 'is_required' => '1', 'option_group_name' => 'leave_request_monthly_annually_day_of_the_week', 'custom_group_name' => 'leave_request'),
      
    'leave_request_annually_each' => array('name' => 'leave_request_annually_each', 'label' => 'Annually - Each', 'data_type' => 'Int', 'html_type' => 'Text', 'default_value' => '1', 'is_required' => '1', 'custom_group_name' => 'leave_request'),
    'leave_request_annually_every_day_of_the_month_day_of_the_week' => array('name' => 'leave_request_annually_every_day_of_the_month_day_of_the_week', 'label' => 'Annually - Every day of the month / day of the week of the month', 'data_type' => 'String', 'html_type' => 'Radio', 'default_value' => 'every_day_of_the_month', 'is_required' => '1', 'option_group_name' => 'leave_request_monthly_annually_every_day_of_the_month_day_of_the', 'custom_group_name' => 'leave_request'),
    'leave_request_annually_month' => array('name' => 'leave_request_annually_month', 'label' => 'Annually - Month', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'january', 'is_required' => '1', 'option_group_name' => 'leave_request_annually_month', 'custom_group_name' => 'leave_request'),
    'leave_request_annually_day_of_the_month' => array('name' => 'leave_request_annually_day_of_the_month', 'label' => 'Annually - Day of the month', 'data_type' => 'Int', 'html_type' => 'Text', 'default_value' => '1', 'is_required' => '1', 'custom_group_name' => 'leave_request'),
    'leave_request_annually_on' => array('name' => 'leave_request_annually_on', 'label' => 'Annually - On', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'first', 'is_required' => '1', 'option_group_name' => 'leave_request_monthly_annually_on', 'custom_group_name' => 'leave_request'),
    'leave_request_annually_day_of_the_week' => array('name' => 'leave_request_annually_day_of_the_week', 'label' => 'Annually - Day of the week', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'monday', 'is_required' => '1', 'option_group_name' => 'leave_request_monthly_annually_day_of_the_week', 'custom_group_name' => 'leave_request'),
    'leave_request_annually_month_of' => array('name' => 'leave_request_annually_month_of', 'label' => 'Annually - Month of', 'data_type' => 'String', 'html_type' => 'Select', 'default_value' => 'january', 'is_required' => '1', 'option_group_name' => 'leave_request_annually_month', 'custom_group_name' => 'leave_request'),
    'leave_request_duration' => array('name' => 'leave_request_duration', 'label' => 'Duration', 'data_type' => 'String', 'html_type' => 'Text', 'default_value' => '8:00', 'is_required' => '1', 'help_pre' => 'Duration must be hours and minutes separated by :', 'custom_group_name' => 'leave_request'),
  );
  
  private $option_groups = array(
    'leave_settings_show_all_colleagues_of' => array
    (
      'title_en_US' => 'Leave - Settings - Show all colleagues of', 
      'name' => 'leave_settings_show_all_colleagues_of', 
      'label' => 'Show all colleagues of', 
    ),
    'leave_adjustments_day_week_month_year' => array
    (
      'title_en_US' => 'Leave - Adjustments - Show all colleagues of', 
      'name' => 'leave_adjustments_day_week_month_year', 
      'label' => 'Day / Week / Month / Year',
    ),
    'leave_adjustments_on' => array
    (
      'title_en_US' => 'Leave - Adjustments - On', 
      'name' => 'leave_adjustments_on', 
      'label' => 'On',
    ),
    'leave_adjustments_day_of_the_week' => array
    (
      'title_en_US' => 'Leave - Adjustments - Day of the week', 
      'name' => 'leave_adjustments_day_of_the_week', 
      'label' => 'Day of the week',
    ),
    'leave_request_leave_type' => array
    (
      'title_en_US' => 'Leave - Request - Leave type', 
      'name' => 'leave_request_leave_type', 
      'label' => 'Leave type',
    ),
    'leave_request_status' => array
    (
      'title_en_US' => 'Leave - Request - Status', 
      'name' => 'leave_request_status', 
      'label' => 'Status',
    ),
    'leave_request_daily_weekly_monthly_annually' => array
    (
      'title_en_US' => 'Leave - Request - Daily / Weekly / Monthly / Annaually', 
      'name' => 'leave_request_daily_weekly_monthly_annually', 
      'label' => 'Daily / Weekly / Monthly / Annaually',
    ),
    'leave_request_daily_every_day_working_day' => array
    (
      'title_en_US' => 'Leave - Request - Every day / Working day', 
      'name' => 'leave_request_daily_every_day_working_day', 
      'label' => 'Every day / Working day',
    ),
    'leave_request_weekly_day_of_the_week' => array
    (
      'title_en_US' => 'Leave - Request - Day of the week', 
      'name' => 'leave_request_weekly_day_of_the_week', 
      'label' => 'Day of the week',
    ),
    'leave_request_monthly_annually_every_day_of_the_month_day_of_the' => array
    (
      'title_en_US' => 'Leave - Request - Every day of the month  / Day of the week', 
      'name' => 'leave_request_monthly_annually_every_day_of_the_month_day_of_the', 
      'label' => 'Every day of the month / Day of the week',
    ),
    'leave_request_monthly_annually_on' => array
    (
      'title_en_US' => 'Leave - Request - On', 
      'name' => 'leave_request_monthly_annually_on', 
      'label' => 'On',
    ),
    'leave_request_monthly_annually_day_of_the_week' => array
    (
      'title_en_US' => 'Leave - Request - Day of the week', 
      'name' => 'leave_request_monthly_annually_day_of_the_week', 
      'label' => 'Day of the week',
    ),
    'leave_request_annually_month' => array
    (
      'title_en_US' => 'Leave - Request - Month', 
      'name' => 'leave_request_annually_month', 
      'label' => 'Month',
    ),
  );
  
  private $option_values = array(
    'leave_settings_show_all_colleagues_of' => array( 
      'options' => array(
        'department' => 'Department',
        'business' => 'Business',
        //'main business' => 'Main business'
      )
    ),
    
    'leave_adjustments_day_week_month_year' => array(
      'options' => array(
        'day' => 'Day',
        'week' => 'Week',
        'month' => 'Month',
        'year' => 'Year'
      )
    ),
    'leave_adjustments_on' => array(
      'options' => array(
        'first' => 'First',
        'second' => 'Second',
        'third' => 'Third',
        'fourth' => 'Fourth',
        'last' => 'Last',
      )
    ),
    'leave_adjustments_day_of_the_week' => array(
      'options' => array(
        'Monday' => 'Monday',
        'Tuesday' => 'Tuesday',
        'Wednesday' => 'Wednesday',
        'Thursday' => 'Thursday',
        'Friday' => 'Friday',
        'Saturday' => 'Saturday',
        'Sunday' => 'Sunday',
      )
    ),
    'leave_adjustments_month' => array(
      'options' => array(
        'January' => 'January',
        'February' => 'February',
        'March' => 'March',
        'April' => 'April',
        'May' => 'May',
        'June' => 'June',
        'July' => 'July',
        'August' => 'August',
        'September' => 'September',
        'October' => 'October',
        'November' => 'November',
        'December' => 'December',
      )
    ),
    'leave_request_leave_type' => array(
      'options' => array(
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
    'leave_request_status' => array(
      'options' => array(
        'request' => 'Request',
        'in_treatment' => 'In treatment',
        'approved' => 'Approved',
        'rejected' => 'Rejected'
      )
    ),
    'leave_request_daily_weekly_monthly_annually' => array(
      'options' => array(
        'daily' => 'Daily',
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
        'annually' => 'Annually',
      )
    ),
    'leave_request_daily_every_day_working_day' => array(
      'options' => array(
        'day' => 'Day',
        'working_day' => 'Working day',
      )
    ),
    'leave_request_weekly_day_of_the_week' => array(
      'options' => array(
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
      )
    ),
    'leave_request_monthly_annually_every_day_of_the_month_day_of_the' => array(
      'options' => array(
        'every_day_of_the_month' => 'Every day of the month',
        'every_day_of_the_week' => 'Every day of the week',
      )
    ),
    'leave_request_monthly_annually_on' => array(
      'options' => array(
        'first' => 'First',
        'second' => 'Second',
        'third' => 'Third',
        'fourth' => 'Fourth',
        'last' => 'Last',
      )
    ),
    'leave_request_monthly_annually_day_of_the_week' => array(
      'options' => array(
        'day' => 'Day',
        'working_day' => 'Working day',
        'weekend_day' => 'Weekend day',
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
      )
    ),    
    'leave_request_annually_month' => array(
      'options' => array(
        'january' => 'January',
        'february' => 'February',
        'march' => 'March',
        'april' => 'April',
        'may' => 'May',
        'june' => 'June',
        'july' => 'July',
        'august' => 'August',
        'september' => 'September',
        'october' => 'October',
        'november' => 'November',
        'december' => 'December',
      )
    ),
  );
  
  private $error_platform = 'civicrm';
  private $error_id = '';
  private $error = false;
  private $errors = array();
  
  private $cids = array();
  private $employees = array();    
    
  private $departments = array();
  private $businesses = array();
  private $main_businesses = array();
  private $department_heads = array();
  private $administrations = array();
  
  private $department_colleages_ids = array();
  private $business_colleages_ids = array();
  
  private $is_employee = array();
  private $is_department_head = array();
  private $is_administration = array();
  
  private $department_heads_colleages_ids = array();
  private $administration_colleages_ids = array();  
        
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
    
  private $settings = array();
  private $request = array(); 
  private $total = array();
  
  private $cache = [];
  
  public function __construct($error_platform, $error_id, $cache = []){        
    if('' == $error_platform){
      echo('Leaveregistration class, no error platform !') . '<br/>' . PHP_EOL;
      return false;
    }
    
    $this->error_platform = $error_platform;
    
    if(empty($error_id)){
      $this->set_error( ts('error id is empty !'), ts('Construct'));
      return false;
    }
    
    $this->error_id = $error_id;
    
    // cache
    if(!isset($cache['do']) or empty($cache['do'])){
      $cache['do'] = false;
    }
    
    $this->cache = $cache;
    
    if(!$this->cache_settings()){
      $this->set_error( ts('An error occur in cache settings !'), ts('Construct'));
      return false;
    }
  }
  
  public function set_fields(){ 
    if($this->isset_error()){ // check for errors in the last called functions in this class
      return false;
    }
    
    $relationship_types = $this->cache_get('relationship_types');
    $custom_groups = $this->cache_get('custom_groups');    
    $custom_fields = $this->cache_get('custom_fields');
    $option_groups = $this->cache_get('option_groups');
    
    if(empty($relationship_types) or empty($custom_groups) or empty($custom_fields) or empty($option_groups)){
      $this->cache['do'] = true;
      
    }else {
      $this->relationship_types = $relationship_types;
      $this->custom_groups = $custom_groups;
      $this->custom_fields = $custom_fields;
      $this->option_groups = $option_groups;
    }
    
    if($this->cache['do']){
      if(!$this->set_relationship_types()){
        $this->set_error( ts('An error occur in relationship types !'), ts('set_fields'));
        return false;
      }
      
      if(!$this->set_custom_groups()){
        $this->set_error( ts('An error occur in custom groups !'), ts('set_fields'));
        return false;
      }
            
      if(!$this->set_custom_fields()){
        $this->set_error( ts('An error occur in custom fields !'), ts('set_fields'));
        return false;
      }
      
      if(!$this->set_option_groups()){
        $this->set_error( ts('An error occur in options groups !'), ts('set_fields'));
        return false;
      }
            
      $this->cache_set('relationship_types', $this->relationship_types);
      $this->cache_set('custom_groups', $this->custom_groups);
      $this->cache_set('custom_fields', $this->custom_fields);
      $this->cache_set('option_groups', $this->option_groups);
    }
    
    echo('$this->relationship_types: <pre>');
    print_r($this->relationship_types);
    echo('</pre>');
    echo('$this->custom_groups: <pre>');
    print_r($this->custom_groups);
    echo('</pre>');
    echo('$this->custom_fields: <pre>');
    print_r($this->custom_fields);
    echo('</pre>');
    echo('$this->option_groups: <pre>');
    print_r($this->option_groups);
    echo('</pre>');
  }
    
  /*
   * Get all the relationship type ids
   */
  private function set_relationship_types(){
    foreach($this->relationship_types as $relationship => $relationship_type){
      $params = array(
        'version' => 3,
        'sequential' => 1,
        'return'=> 'id',
        'name_a_b' => $relationship_type['name_a_b'],
        'contact_type_a' => $relationship_type['contact_type_a'],
        'contact_type_b' => $relationship_type['contact_type_b'],
      );
            
      $result = civicrm_api('RelationshipType', 'getsingle', $params); 
      unset($params);     
      
      if(isset($result['is_error']) and $result['is_error']){
        $this->set_error( ts('Error result RelationshipType getvalue, from relationship type with the name ') . $relationship_type['name_a_b'] . ts(' ! Error message: ') . $result['error_message'], ts('Relationship type'));
      }else {
        $this->relationship_types[$relationship]['id'] = $result['id'];
      }
    }
    
    unset($result); 
    
    // check if every relationship type has a id
    foreach($this->relationship_types as $relationship => $relationship_type){
      if(!isset($relationship_type['id']) or empty($relationship_type['id'])){
        $this->set_error( ts('Relationship type with the name ') . $relationship_type['name_a_b'] . ts(' has no id !'), ts('Relationship type'));
      }
    }
            
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  private function set_custom_groups(){    
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
  
  private function set_custom_fields(){
    $query = "SELECT name, custom_group_id, column_name, option_group_id FROM civicrm_custom_field";
    $query .= " WHERE";
           
    $where = "";
    foreach($this->custom_fields as $id => $custom_field){
      $where .= " OR name = '" . $custom_field['name'] . "'";
    }
    
    $query .= substr($where, 3);
                
    $dao = CRM_Core_DAO::executeQuery($query);    
    while($dao->fetch()){
      // update $this->custom_fields with custom_group_id, column_name and option_group_id
      $this->custom_fields[$dao->name]['custom_group_id'] = $dao->custom_group_id;
      $this->custom_fields[$dao->name]['column_name'] = $dao->column_name;
      $this->custom_fields[$dao->name]['option_group_id'] = $dao->option_group_id;
    }
        
    // check
    foreach($this->custom_fields as $id => $custom_field){
      // column name
      if(!isset($custom_field['column_name']) or '' == $custom_field['column_name']){
        $this->set_error( ts('The field with name ') . $custom_field['name'] . ts(' has no column name !'), ts('Custom fields'));
      }
      // option_group_id
      if(isset($custom_field['option_group_name']) and !empty($custom_field['option_group_name'])){
        if(!isset($custom_field['option_group_id']) or empty($custom_field['option_group_id'])){
          $this->set_error( ts('The field with name ') . $custom_field['name'] . ts(' has no option_group_id !'), ts('Custom fields'));
        }
      }
    }
        
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
    
  private function set_option_groups(){
    $query = "SELECT civicrm_option_value.value, civicrm_option_value.label, civicrm_option_group.name FROM civicrm_option_value";
    $query .= " INNER JOIN civicrm_option_group ON civicrm_option_value.option_group_id = civicrm_option_group.id";
    $query .= " WHERE";
    
    $where = "";
    foreach($this->option_groups as $key => $option_group){
      $where .= " OR civicrm_option_group.name = '" . $option_group['name'] . "'";
    }
    
    $query .= substr($where, 3);
    $query .= " ORDER BY civicrm_option_group.name, civicrm_option_value.weight ASC";
        
    $options = array();
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      $options[$dao->name][$dao->value] = $dao->label;
    }
    
    foreach($this->option_groups as $key => $option_group){
      if(isset($options[$option_group['name']]) and !empty($options[$option_group['name']])){
        $this->option_groups[$key]['options'] = $options[$option_group['name']];
      }
    }
    
    foreach($this->option_groups as $key => $option_group){
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
  
  public function __get($name) {
    if($this->__isset($name)){
      return $this->{$name};
    }
  }
  
  public function __isset($name) {
    return isset($this->{$name});
  }
  
  public function __unset($name){
    if($this->__isset($name)){
      unset($this->{$name});
    }
  }
    
  private function set_error($text, $function){
    $this->error = true;
    
    $this->errors[] = array('text' => $text, 'function' => $function, 'id' => $this->error_id);
    
    switch($this->error_platform)
    {
      case 'drupal':
        drupal_set_message( ts($text) . ' (' . ts('leave registration class, ') . ' ' . $function . ', ' . $this->error_id . ')', 'error');
        break;
      
      default:
        CRM_Core_Session::setStatus( ts($text) . ' (' . ts('leave registration class, ') . ' ' . $function . ', ' . $this->error_id . ')', ts('leave registration class, ') . ' ' . $function, 'error');
    }
  }
    
  public function isset_error(){
    return $this->error;
  }
  
  public function get_errors(){
    return $this->errors;
  }
  
  public function set_contacts($cids){  
    if($this->isset_error()){ // check for errors in the last called functions in this class
      return false;
    }
    
    if(empty($cids)){
      $this->set_error(ts('No contact id !'), ts('set_contacts'));
      return false;
    }
    
    if(!is_array($cids)){
      $this->cids = array($cids);
    }else {
      $this->cids = $cids;
    }
            
    foreach($this->cids as $cid){
      $this->is_employee[$cid] = false;
      $this->is_department_head[$cid] = false;
      $this->is_administration[$cid] = false;
    }
   
    if(!$this->set_employees()){
      $this->set_error( ts('An error occur in employee !'), ts('set_contacts'));
      return false;
    }

    if(!$this->set_departments()){
      $this->set_error( ts('An error occur in departments !'), ts('set_contacts'));
      return false;
    }

    if(!$this->set_department_colleages_ids()){
      $this->set_error( ts('An error occur in departments colleages ids !'), ts('set_contacts'));
      return false;
    }

    if(!$this->set_businesses()){
      $this->set_error( ts('An error occur in businesses !'), ts('set_contacts'));
      return false;
    }

    if(!$this->set_business_colleages_ids()){
      $this->set_error( ts('An error occur in business colleages ids !'), ts('set_contacts'));
      return false;
    }

    if(!$this->set_main_businesses()){
      $this->set_error( ts('An error occur in main businesses !'), ts('set_contacts'));
      return false;
    }

    if(!$this->set_department_head()){
      $this->set_error( ts('An error occur in department head !'), ts('set_contacts'));
      return false;
    }

    if(!$this->set_adminstration()){
      $this->set_error( ts('An error occur in administration !'), ts('set_contacts'));
      return false;
    }

    if(!$this->set_department_heads_colleages_ids()){
      $this->set_error( ts('An error occur in department head colleages ids !'), ts('set_contacts'));
      return false;
    }

    if(!$this->set_administration_colleages_ids()){
      $this->set_error( ts('An error occur in administration colleages ids !'), ts('set_contacts'));
      return false;
    }

    if(!$this->set_settings()){
      $this->set_error( ts('An error occur in settings !'), ts('set_contacts'));
      return false;
    }
    
    echo('$this->employees: <pre>');
    print_r($this->employees);
    echo('</pre>');
    echo('$this->departments: <pre>');
    print_r($this->departments);
    echo('</pre>');
    echo('$this->businesses: <pre>');
    print_r($this->businesses);
    echo('</pre>');
    echo('$this->business_colleages_ids: <pre>');
    print_r($this->business_colleages_ids);
    echo('</pre>');
    echo('$this->main_businesses: <pre>');
    print_r($this->main_businesses);
    echo('</pre>');
    echo('$this->department_heads: <pre>');
    print_r($this->department_heads);
    echo('</pre>');
    echo('$this->administrations: <pre>');
    print_r($this->administrations);
    echo('</pre>');
    echo('$this->department_heads_colleages_ids: <pre>');
    print_r($this->department_heads_colleages_ids);
    echo('</pre>');
    echo('$this->settings: <pre>');
    print_r($this->settings);
    echo('</pre>');
    
    return true;
  }
  
  private function set_employees(){
    if($this->isset_error()){
      return false;
    }
        
    if(empty($this->cids)){
      $this->set_error(ts('No contact ids !'), ts('set_employees'));
      return false;
    }
            
    foreach($this->cids as $cid){ 
      $this->employees[$cid] = $this->cache_get($cid . '_employees');
      
      if($this->cache['do'] or empty($this->employees[$cid])){
        $this->is_employee[$cid] = false;

        $params = array(
          'version' => 3,
          'sequential' => 1,
          'id' => $cid,
        );
        $contact = civicrm_api('Contact', 'getsingle', $params);
        unset($params);

        // count
        if(isset($contact['count']) and 0 == $contact['count']){
          $this->set_error(ts('Employee with contact id: ') . $cid . (' does not exists !'), ts('set_employees'));
          unset($contact);
          continue;
        }

        // error
        if(isset($contact['is_error']) and $contact['is_error']){
          $this->set_error(ts('Error result Contact getsingle employee ! Error message: ') . $administrations['error_message'], ts('set_employees'));
          unset($contact);
          continue;
        }

        $this->is_employee[$cid] = true;

        $this->employees[$cid] = array();
        $this->employees[$cid]['id'] = $cid;
        $this->employees[$cid]['contact_type'] = $contact['contact_type'];
        $this->employees[$cid]['contact_sub_type'] = $contact['contact_sub_type'];
        $this->employees[$cid]['display_name'] = $contact['display_name'];
        $this->employees[$cid]['email'] = $contact['email'];

        unset($contact);
        
        $this->cache_set($cid . '_is_employee', $this->is_employee[$cid]);
        $this->cache_set($cid . '_employees', $this->employees[$cid]);
      }
    }
    
    // check
    foreach($this->employees as $cid => $employee){
      if(!isset($employee['id']) or empty($employee['id'])){
        $this->set_error(ts('Employee has no id !'), ts('set_employees'));
      }

      if(!isset($employee['display_name']) or empty($employee['display_name'])){
        $this->set_error(ts('Employee has no display name !'), ts('set_employees'));
      }

      if(!isset($employee['email']) or empty($employee['email'])){
        $this->set_error(ts('Employee has no email !'), ts('set_employees'));
      }
    }
            
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  private function set_departments(){
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->cids)){
      $this->set_error(ts('No contact ids !'), ts('set_departments'));
      return false;
    }
    
    foreach($this->cids as $cid){ 
      $this->departments[$cid] = $this->cache_get($cid . '_departments');
      
      if($this->cache['do'] or empty($this->departments[$cid])){
        $this->departments[$cid] = array();
        $this->employees[$cid]['departments'] = array();

        $params = array(
          'version' => 3,
          'sequential' => 1,
          'relationship_type_id' => $this->relationship_types['employee_of']['id'],
          'contact_id_a' => $cid,
        );
        $departments = civicrm_api('Relationship', 'get', $params);
        unset($params);

        // count
        if(isset($departments['count']) and 0 == $departments['count']){
          $this->set_error(ts('Employee with the name ') . $this->employees[$cid]['display_name'] . (' has no department !'), ts('set_departments'));
          unset($departments);
          continue;
        }

        // error
        if(isset($departments['is_error']) and $departments['is_error']){
          $this->set_eror(ts('Error result Relationship get departments ! Error message: ') . $departments['error_message'], ts('set_departments'));
          unset($departments);
          continue;
        }

        foreach($departments['values'] as $key => $department){
          $params = array(
            'version' => 3,
            'sequential' => 1,
            'id' => $department['contact_id_b'],
          );
          $contact = civicrm_api('Contact', 'getsingle', $params);
          unset($params);

          // count
          if(isset($contact['count']) and 0 == $contact['count']){
            $this->set_error(ts('Department with contact id ') . $department['contact_id_b'] . (' does not exists !'), ts('set_departments'));
            unset($contact);
            continue;
          }

          // error
          if(isset($contact['is_error']) and $contact['is_error']){
            $this->set_eror(ts('Error result Contact getsingle department ! Error message: ') . $contact['error_message'], ts('set_departments'));
            unset($contact);
            continue;
          }

          $this->departments[$cid][$department['contact_id_b']] = array();
          $this->departments[$cid][$department['contact_id_b']]['id'] = $department['contact_id_b'];
          $this->departments[$cid][$department['contact_id_b']]['contact_type'] = $contact['contact_type'];
          $this->departments[$cid][$department['contact_id_b']]['contact_sub_type'] = $contact['contact_sub_type'];
          $this->departments[$cid][$department['contact_id_b']]['display_name'] = $contact['display_name'];
          $this->departments[$cid][$department['contact_id_b']]['email'] = $contact['email'];

          $this->employees[$cid]['departments'][$department['contact_id_b']] = $department['contact_id_b'];

          unset($contact);
        }
        unset($departments);

        $this->cache_set($cid . '_departments', $this->departments[$cid]);
        $this->cache_set($cid . '_employees', $this->employees[$cid]);
      }
    }
    
    // check if the employees has a department
    
    foreach($this->employees as $cid => $employee){
      if(!isset($this->departments[$cid]) or empty($this->departments[$cid])){
        $this->set_error( ts('Employee with name ') . $employee['display_name'] . (' has no department !'), ts('set_departments'));
      }
    }
        
    // check if the employee has multiple departments
    foreach($this->employees as $cid => $employee){
      if(isset($this->departments[$cid]) and 1 < count($this->departments[$cid])){
        $this->set_error( ts('Employee with name ') . $employee['display_name'] . (' has more than one department !'), ts('set_departments'));
      }
    }

    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  /**
   * Get all the colleages ids from the department
   * The department can have zero employees
   */
  private function set_department_colleages_ids(){
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('set_department_colleages_ids'));
      return false;
    }
        
    foreach($this->departments as $cid => $departments){
      $this->department_colleages_ids[$cid] = cache_get($cid . '_department_colleages_ids');
              
      if($this->cache['do'] or empty($this->department_colleages_ids[$cid])){
        foreach($departments as $did => $department){
          $this->department_colleages_ids[$cid] = array();  
          $this->department_colleages_ids[$cid][$did] = array();  
          $this->department_colleages_ids[$cid][$did]['employees'] = array();  

          $params = array(
            'version' => 3,
            'sequential' => 1,
            'relationship_type_id' => $this->relationship_types['employee_of']['id'],
            'contact_id_b' => $did,
          );
          $colleages = civicrm_api('Relationship', 'get', $params);
          unset($params);

          if(isset($contact['count']) and 0 != $contact['count']){
              // department can have zero employees
              unset($colleages);
              continue;
          }

          // error
          if(isset($colleages['is_error']) and $colleages['is_error']){
            $this->set_eror(ts('Error result relationship get department colleages ! Error message: ') . $administrations['error_message'], ts('set_department_colleages_ids'));
            unset($colleages);
            continue;
          }

          foreach($colleages['values'] as $key => $employee){
            $params = array(
              'version' => 3,
              'sequential' => 1,
              'id' => $employee['contact_id_a'],
              'is_deleted' => 0,
            );
            $contact = civicrm_api('Contact', 'getsingle', $params);
            unset($params);

            // sometimes a contact does not exist
            if(isset($contact['count']) and 0 == $contact['count']){
              unset($contact);
              continue;
            }

            // sometimes the contact does not exist in the contact table, so only set error if there is a error and not on zero count
            if(isset($contact['is_error']) and $contact['is_error']){  
              $this->set_error(ts('Error result contact getsingle contacts ! Error message: ') . $contact['error_message'], ts('set_department_colleages_ids'));
              unset($contact);
              continue;
            }

            $this->department_colleages_ids[$cid][$did]['employees'][$employee['contact_id_a']] = array();
            $this->department_colleages_ids[$cid][$did]['employees'][$employee['contact_id_a']]['id'] = $employee['contact_id_a'];
            $this->department_colleages_ids[$cid][$did]['employees'][$employee['contact_id_a']]['display_name'] = $contact['display_name'];
            $this->department_colleages_ids[$cid][$did]['employees'][$employee['contact_id_a']]['email'] = $contact['email'];

            unset($contact);
          }
          unset($colleages);
        }
        $this->cache_set($cid . '_department_colleages_ids', $this->department_colleages_ids[$cid]);
      }
    }
        
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  /*
   * Every department can have multiple business
   * Every department belongs to a business
   * 
   * Because the main business, business and department have settings
   * the general, overall credit, overall adjustments and holidays, with multiple 
   * businesses i have to guess witch setting i have to use, there for i build 
   * a check at the end if the department have multiple businesses, i keep the
   * option that the department have more than one business because maybe in the 
   * future it will be necessary.
   */
  private function set_businesses(){
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('set_businesses'));
      return false;
    }
    
    foreach($this->departments as $cid => $departments){ 
      $this->businesses[$cid] = cache_get($cid . '_businesses');
              
      if($this->cache['do'] or empty($this->businesses[$cid])){
      
        $this->businesses[$cid] = array();
        $this->employees[$cid]['businesses'] = array();

        foreach($departments as $did => $department){
          $this->departments[$cid][$did]['businesses'] = array();

          $params = array(
            'version' => 3,
            'sequential' => 1,
            'relationship_type_id' => $this->relationship_types['department_of']['id'],
            'contact_id_a' => $did,
          );
          $business = civicrm_api('Relationship', 'get', $params);
          unset($params);

          // count
          if(isset($business['count']) and 0 == $business['count']){
            $this->set_error(ts('Department with name ') . $department['display_name'] . (' has no business !'), ts('set_businesses'));
            unset($business);
            continue;
          }

          // error
          if(isset($business['is_error']) and $business['is_error']){
            $this->set_eror(ts('Error result Relationship get business ! Error message: ') . $business['error_message'], ts('set_businesses'));
            unset($business);
            continue;
          }

          foreach($business['values'] as $key => $business){
            $params = array(
              'version' => 3,
              'sequential' => 1,
              'id' => $business['contact_id_b'],
              'is_deleted' => 0,
            );
            $contact = civicrm_api('Contact', 'getsingle', $params);
            unset($params);

            // count
            if(isset($contact['count']) and 0 == $contact['count']){
              $this->set_error(ts('Business with contact id ') . $business['contact_id_b'] . (' does not exists !'), ts('set_businesses'));
              unset($contact);
              continue;
            }

            // error
            if(isset($contact['is_error']) and $contact['is_error']){
              $this->set_eror(ts('Error result Contact getsingle business ! Error message: ') . $contact['error_message'], ts('set_businesses'));
              unset($contact);
              continue;
            }

            $this->businesses[$cid][$business['contact_id_b']] = array();
            $this->businesses[$cid][$business['contact_id_b']]['id'] = $business['contact_id_b'];
            $this->businesses[$cid][$business['contact_id_b']]['contact_type'] = $contact['contact_type'];
            $this->businesses[$cid][$business['contact_id_b']]['contact_sub_type'] = $contact['contact_sub_type'];
            $this->businesses[$cid][$business['contact_id_b']]['display_name'] = $contact['display_name'];
            $this->businesses[$cid][$business['contact_id_b']]['email'] = $contact['email'];

            $this->departments[$cid][$did]['businesses'][$business['contact_id_b']] = $business['contact_id_b'];

            $this->employees[$cid]['businesses'][$business['contact_id_b']] =$business['contact_id_b'];

            unset($contact);
          }
          unset($business);
        }
        $this->cache_set($cid . '_businesses', $this->businesses[$cid]);
        $this->cache_set($cid . '_departments', $this->departments[$cid]);
        $this->cache_set($cid . '_employees', $this->employees[$cid]);
      }
    }
    
    // check if employee has a business
    foreach($this->employees as $cid => $employee){
      if(!isset($this->businesses[$cid]) or empty($this->businesses[$cid])){
        $this->set_error( ts('Employee with the name ') . $employee['display_name'] . ts(' has no business !'), ts('set_businesses'));
      }
    }
    
    // check if the departments has a business    
    foreach($this->departments as $cid => $departments){
      foreach($departments as $did => $department){
        if(!isset($department['businesses']) or empty($department['businesses'])){
          $this->set_error( ts('Department with the name ') . $department['display_name'] . ts(' has no business !'), ts('set_businesses'));
        }
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
    
  /*
   * Get all the colleages ids from business
   * The business can have zero employees (department to)
   */
  private function set_business_colleages_ids(){
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->businesses)){
      $this->set_error(ts('No business are set !'), ts('set_business_colleages_ids'));
      return false;
    }
    
    foreach($this->businesses as $cid => $businesses){
      $this->business_colleages_ids[$cid] = cache_get($cid . '_business_colleages_ids');
              
      if($this->cache['do'] or empty($this->business_colleages_ids[$cid])){
        foreach($businesses as $bid => $business){
          $this->business_colleages_ids[$cid] = array();
          $this->business_colleages_ids[$cid][$bid] = array();
          $this->business_colleages_ids[$cid][$bid]['employees'] = array();

          $params = array(
            'version' => 3,
            'sequential' => 1,
            'relationship_type_id' => $this->relationship_types['department_of']['id'],
            'contact_id_b' => $bid,
          );

          $departments = civicrm_api('Relationship', 'get', $params);
          unset($params);

          // count
          if(isset($departments['count']) and 0 == $departments['count']){
            // business can have zero departments
            unset($departments);
            continue;
          }

          // error
          if(isset($departments['is_error']) and $departments['is_error']){
            $this->set_error(ts('Error result relationship get department colleages ! Error message: ') . $administrations['error_message'], ts('set_business_colleages_ids'));
            unset($departments);
            continue;
          }

          foreach($departments['values'] as $key => $department){     

            $params = array(
              'version' => 3,
              'sequential' => 1,
              'relationship_type_id' => $this->relationship_types['employee_of']['id'],
              'contact_id_b' => $department['contact_id_a'],
            );
            $colleages = civicrm_api('Relationship', 'get', $params);
            unset($params);

            // count
            if(isset($colleages['count']) and 0 == $colleages['count']){
              // department can have zero employees
              unset($colleages);
              continue;
            }

            // error
            if(isset($colleages['is_error']) and $colleages['is_error']){
              $this->set_error(ts('Error result relationship get department colleages ! Error message: ') . $administrations['error_message'], ts('set_business_colleages_ids'));
              unset($colleages);
              continue;
            }

            foreach($colleages['values'] as $key => $employee){
              $params = array(
                'version' => 3,
                'sequential' => 1,
                'id' => $employee['contact_id_a'],
                'is_deleted' => 0,
              );
              $contact = civicrm_api('Contact', 'getsingle', $params);

              unset($params);

              // somnethimes a contact does not exist
              if(isset($contact['count']) and 0 == $contact['count']){
                unset($contact);
                continue;
              }

              // error
              if(isset($contact['is_error']) and $contact['is_error']){
                $this->set_error(ts('Error result contact getsingle contacts ! Error message: ') . $contact['error_message'], ts('set_business_colleages_ids'));
                unset($contact);
                continue;
              }

              $this->business_colleages_ids[$cid][$bid]['employees'][$employee['contact_id_a']] = array();
              $this->business_colleages_ids[$cid][$bid]['employees'][$employee['contact_id_a']]['id'] = $employee['contact_id_a'];
              $this->business_colleages_ids[$cid][$bid]['employees'][$employee['contact_id_a']]['display_name'] = $contact['display_name'];
              $this->business_colleages_ids[$cid][$bid]['employees'][$employee['contact_id_a']]['email'] = $contact['email'];      
            }
            unset($colleages);
          }
          unset($departments);
        }
        
        $this->cache_set($cid . '_business_colleages_ids', $this->business_colleages_ids[$cid]);
      } 
    }
      
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  /*
   * Every business can heave multiple main businesses
   * The main business doesnot have to exists
   * 
   * Because the main business, business and department have settings
   * the general, overall credit, overall adjustments and holidays, with multiple 
   * main businesses i have to guess witch setting i have to use, there for i build 
   * a check at the end if the business have multiple main businesses, i keep the
   * option that the business have more than one main business because maybe in the 
   * future it will be necessary.
   */
  private function set_main_businesses(){
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->businesses)){
      $this->set_error(ts('No businesses !'), ts('set_main_businesses'));
      return false;
    }
    
    foreach($this->businesses as $cid => $businesses){ 
      $this->main_businesses[$cid] = $this->cache_get($cid . '_main_businesses');
      
      if($this->cache['do'] or empty($this->main_businesses[$cid])){
        $this->main_businesses[$cid] = array();
        $this->employees[$cid]['main_businesses'] = array();


        foreach($businesses as $bid => $business){
          $this->businesses[$cid][$bid]['main_businesses'] = array();

          $params = array(
            'version' => 3,
            'sequential' => 1,
            'relationship_type_id' => $this->relationship_types['main_organization']['id'],
            'contact_id_a' => $bid,
          );
          $main_business = civicrm_api('Relationship', 'get', $params);
          unset($params);

          // count
          if(isset($main_business['count']) and 0 == $main_business['count']){
            //$this->set_error(ts('Business with name ') . $business['display_name'] . (' has no main business !'), ts('set_main_businesses'));
            unset($main_business);
            continue; 
          }

          // error
          if(isset($main_business['is_error']) and $main_business['is_error']){
            $this->set_eror(ts('Error result Relationship get main business ! Error message: ') . $main_business['error_message'], ts('set_main_businesses'));
            unset($main_business);
            continue;
          }

          foreach($main_business['values'] as $key => $main_business){
            $params = array(
              'version' => 3,
              'sequential' => 1,
              'id' => $main_business['contact_id_b'],
              'is_deleted' => 0,
            );
            $contact = civicrm_api('Contact', 'getsingle', $params);
            unset($params);

            // sometimes a contact does not exist, but the relationship does
            if(isset($contact['count']) and 0 == $contact['count']){
              //$this->set_error(ts('Main business with contact id ') . $main_business['contact_id_b'] . (' does not exists !'), ts('set_main_businesses'));
              unset($contact);
              continue;
            }

            // error
            if(isset($contact['is_error']) and $contact['is_error']){
              $this->set_eror(ts('Error result Contact getsingle main business ! Error message: ') . $contact['error_message'], ts('set_main_businesses'));
              unset($contact);
              continue;
            }

            $this->main_businesses[$cid][$main_business['contact_id_b']] = array();
            $this->main_businesses[$cid][$main_business['contact_id_b']]['id'] = $main_business['contact_id_b'];
            $this->main_businesses[$cid][$main_business['contact_id_b']]['contact_type'] = $business['contact_type'];
            $this->main_businesses[$cid][$main_business['contact_id_b']]['contact_sub_type'] = $business['contact_sub_type'];
            $this->main_businesses[$cid][$main_business['contact_id_b']]['display_name'] = $business['display_name'];
            $this->main_businesses[$cid][$main_business['contact_id_b']]['email'] = $business['email'];

            $this->businesses[$cid][$bid]['main_businesses'][$main_business['contact_id_b']] = $main_business['contact_id_b'];

            $this->employees[$cid]['main_businesses'][$main_business['contact_id_b']] = $main_business['contact_id_b'];

            unset($contact);
          }
          unset($main_business);
        }
        $this->cache_set($cid . '_main_businesses', $this->main_businesses[$cid]);
        $this->cache_set($cid . '_businesses', $this->businesses[$cid]);
        $this->cache_set($cid . '_employees', $this->employees[$cid]);
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  /*
   * Every department must have a department head
   * Every department can have multpile department heads
   */
  private function set_department_head(){    
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('set_department_head'));
      return false;
    }
    
    foreach($this->departments as $cid => $departments){
      $this->department_heads[$cid] = $this->cache_get($cid . '_department_heads');
      
      if($this->cache['do'] or empty($this->department_heads[$cid])){
        $this->department_heads[$cid] = array();
        $this->employees[$cid]['department_heads'] = array();

        foreach($departments as $did => $department){
          $this->departments[$cid][$did]['department_heads'] = array();

          $params = array(
            'version' => 3,
            'sequential' => 1,
            'relationship_type_id' => $this->relationship_types['department_head']['id'],
            'contact_id_a' => $did,
          );
          $department_heads = civicrm_api('Relationship', 'get', $params);
          unset($params);

          // count
          if(isset($department_heads['count']) and 0 == $department_heads['count']){
            $this->set_error(ts('Department with name ') . $department['display_name'] . (' has no department head !'), ts('set_department_head'));
            unset($department_heads);
            continue;
          }

          // error
          if(isset($department_heads['is_error']) and $department_heads['is_error']){
            $this->set_eror(ts('Error result Relationship get business ! Error message: ') . $department_heads['error_message'], ts('set_department_head'));
            unset($department_heads);
            continue;
          }

          foreach($department_heads['values'] as $key => $department_head){
            $params = array(
              'version' => 3,
              'sequential' => 1,
              'id' => $department_head['contact_id_b'],
              'is_deleted' => 0,
            );
            $contact = civicrm_api('Contact', 'getsingle', $params);
            unset($params);

            // sometimes a contact does not exist but the ralationship does
            if(isset($contact['count']) and 0 == $contact['count']){
              //$this->set_error(ts('Department head with contact id ') . $department_head['contact_id_b'] . (' does not exists !'), ts('set_department_head'));
              unset($contact);
              continue;
            }

            // error
            if(isset($contact['is_error']) and $contact['is_error']){
              $this->set_eror(ts('Error result Contact getsingle department head ! Error message: ') . $contact['error_message'], ts('set_department_head'));
              unset($contact);
              continue;
            }

            $this->department_heads[$cid][$department_head['contact_id_b']] = array();
            $this->department_heads[$cid][$department_head['contact_id_b']]['id'] = $department_head['contact_id_b'];
            $this->department_heads[$cid][$department_head['contact_id_b']]['contact_type'] = $contact['contact_type'];
            $this->department_heads[$cid][$department_head['contact_id_b']]['contact_sub_type'] = $contact['contact_sub_type'];
            $this->department_heads[$cid][$department_head['contact_id_b']]['display_name'] = $contact['display_name'];
            $this->department_heads[$cid][$department_head['contact_id_b']]['email'] = $contact['email'];

            $this->departments[$cid][$did]['department_heads'][$department_head['contact_id_b']] = $department_head['contact_id_b'];

            $this->employees[$cid]['department_heads'][$department_head['contact_id_b']] = $department_head['contact_id_b'];

            unset($contact);
          }
          unset($department_heads);
        }
        $this->cache_set($cid . '_department_heads', $this->department_heads[$cid]);
        $this->cache_set($cid . '_departments', $this->departments[$cid]);
        $this->cache_set($cid . '_employees', $this->employees[$cid]);
      }
    }
    
    // check if employee has a department head
    foreach($this->employees as $cid => $employee){
      if(!isset($this->department_heads[$cid]) or empty($this->department_heads[$cid])){
        $this->set_error( ts('Employee with the name ') . $employee['display_name'] . ts(' has no departmen head !'), ts('set_department_head'));
      }
    }
    
    // check if the departments has a departmen head
    foreach($this->departments as $cid => $departments){
      foreach($departments as $did => $department){
        if(!isset($department['department_heads']) or empty($department['department_heads'])){
          $this->set_error( ts('Department with the name ') . $department['display_name'] . ts(' has no department heads !'), ts('set_department_head'));
        }
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  /*
   * 
   */
  private function set_adminstration(){
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->businesses)){
      $this->set_error(ts('No business are set !'), ts('Administration'));
      return false;
    }
    
    // loop throught all the businesses and get the administration employee
    foreach($this->businesses as $bid => $business){
      $params = array(
        'version' => 3,
        'sequential' => 1,
        'relationship_type_id' => $this->relationship_types['administration_of']['id'],
        'contact_id_b' => $bid,
        'is_active' => 1,
      );
      $administrations = civicrm_api('Relationship', 'get', $params);
      unset($params);
         
      // administraion does not have to exists
      if(isset($administrations['count']) and 0 == $administrations['count']){
        continue;
      }
      
      // error
      if(isset($administrations['is_error']) and $administrations['is_error']){
        $this->set_error(ts('Error result relationship get administrations ! Error message: ') . $administrations['error_message'], ts('Administration'));
        continue;
      }
      
      foreach($administrations['values'] as $key => $administration){
        $params = array(
          'version' => 3,
          'sequential' => 1,
          'id' => $administration['contact_id_a'],
          'is_deleted' => 0,
        );
        $contact = civicrm_api('Contact', 'getsingle', $params);
        
        if(isset($contact['is_error']) and $contact['is_error']){
          $this->set_error(ts('Error result contact getsingle contacts ! Error message: ') . $contact['error_message'], ts('Administration'));
          
        }else {
          $this->administrations[$administration['contact_id_a']] = array();
          $this->administrations[$administration['contact_id_a']]['id'] = $administration['contact_id_a'];
          $this->administrations[$administration['contact_id_a']]['display_name'] = $contact['display_name'];
          $this->administrations[$administration['contact_id_a']]['email'] = $contact['email'];
        }        
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  /*
   * The employee can be department head of a other department then he belongs to
   * Get all the departments, where the employee is department head of, then
   * loop throught the departments and get all the employees of the department
   */  
  private function set_department_heads_colleages_ids(){
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->cids)){
      $this->set_error(ts('No contact id !'), ts('set_department_heads_colleages_ids'));
      return false;
    }
    
    // get all the departments where the employee is department head of
    foreach($this->cids as $cid){
      $this->department_heads_colleages_ids[$cid] = $this->cache_get($cid . '_department_heads_colleages_ids');
      
      if($this->cache['do'] or empty($this->department_heads_colleages_ids[$cid])){
        $this->is_department_head[$cid] = false;

        $params = array(
          'version' => 3,
          'sequential' => 1,
          'relationship_type_id' => $this->relationship_types['department_head']['id'],
          'contact_id_b' => $cid,
          'is_active' => 1,
        );
        $departments = civicrm_api('Relationship', 'get', $params);
        unset($params);

        // no department
        if(isset($departments['count']) and 0 == $departments['count']){
          $this->is_department_head[$cid] = false;
          unset($departments);
          continue;
        }

        // error
        if(isset($departments['is_error']) and $departments['is_error']){ 
          $this->set_error(ts('Error result relationship get department of ! Error message: ') . $departments['error_message'], ts('set_department_heads_colleages_ids'));
          $this->is_department_head[$cid] = false;
          unset($departments);
          continue;
        }

        $this->is_department_head[$cid] = true;
        $this->department_heads_colleages_ids[$cid] = array();

        // loop throught the departments (departments is contact_id_a), and get the employee ids
        foreach ($departments['values'] as $key => $department){

          // add the department to $department_heads_colleages_ids
          $this->department_heads_colleages_ids[$cid][$department['contact_id_a']] = array();
          $this->department_heads_colleages_ids[$cid][$department['contact_id_a']]['id'] = $department['contact_id_a'];
          $this->department_heads_colleages_ids[$cid][$department['contact_id_a']]['employees'] = array();

          $params = array(
            'version' => 3,
            'sequential' => 1,
            'relationship_type_id' => $this->relationship_types['employee_of']['id'],
            'contact_id_b' => $department['contact_id_a'],
            'is_active' => 1,
          );
          $employees = civicrm_api('Relationship', 'get', $params);
          unset($params);

          // count
          if(isset($employees['count']) and 0 == $employees['count']){
            $this->set_error( ts('The department with name ') . $this->departments[$department['contact_id_a']]['display_name'] . ts(' has no employees !'), ts('set_department_heads_colleages_ids'));
            // let the script run and let it generate multiple errors
            unset($employees);
            continue;
          }

          // error
          if(isset($employees['is_error']) and $employees['is_error']){
            $this->set_error(ts('Error result relationship get employee of ! Error message: ') . $employees['error_message'], ts('set_department_heads_colleages_ids'));
            unset($employees);
            continue;
          }

          // loop thought the employees and save the id in $department_heads_colleages_ids
          foreach($employees['values'] as $key => $employee){   

            // there are some relationships with contacts that not exists, so
            // we have to check if the contact exists
            $params = array(
              'version' => 3,
              'sequential' => 1,
              'id' => $employee['contact_id_a'],
              'contact_sub_type' => $this->contact_sub_types['employee']['contact_sub_type'],
              'is_deleted' => 0,
            );
            $contact = civicrm_api('Contact', 'getsingle', $params);
            unset($params);

            // sometimes a contact does not exists, but the relationship does
            if(isset($contact['count']) and 0 == $contact['count']){
              // the employee does not exsist
              unset($contact);
              continue;
            }

            // error
            if(isset($contact['is_error']) and $contact['is_error']){
              $this->set_error(ts('Error result contact get ! Error message: ') . $contact['error_message'], ts('set_department_heads_colleages_ids'));
              unset($contact);
              continue;
            }

            $this->department_heads_colleages_ids[$cid][$department['contact_id_a']]['employees'][$employee['contact_id_a']] = array();
            $this->department_heads_colleages_ids[$cid][$department['contact_id_a']]['employees'][$employee['contact_id_a']]['id'] = $employee['contact_id_a'];
            $this->department_heads_colleages_ids[$cid][$department['contact_id_a']]['employees'][$employee['contact_id_a']]['display_name'] = $contact['display_name'];
            $this->department_heads_colleages_ids[$cid][$department['contact_id_a']]['employees'][$employee['contact_id_a']]['email'] = $contact['email'];

          }
          unset($employees);
        }
        unset($departments);
        
        $this->cache_set($cid . '_department_heads_colleages_ids', $this->department_heads_colleages_ids[$cid]);
        $this->cache_set($cid . '_is_department_head', $this->is_department_head[$cid]);
      }
    }
    
    // if $department_heads_colleages_ids is not empty check if all the departments have employees
    foreach($this->department_heads_colleages_ids as $cid => $departments){
      foreach($departments as $did => $department){
        if(empty($department['employees'])){
          $this->set_error( ts('The department with name ') . $this->departments[$did]['display_name'] . ts(' has no employees ! Last check !'), ts('set_department_heads_colleages_ids'));
        }
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  /*
   * The employee can be administration of a other business then where he is empoyee of
   * Get all the business where he is administration of, then
   * get all the departments and then all the employees of the department
   */  
  private function set_administration_colleages_ids(){
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->cids)){
      $this->set_error(ts('No contact ids !'), ts('set_administration_colleages_ids'));
      return false;
    }
    
    // get all the business of where he is administration of
    foreach($this->cids as $cid){
      $this->administration_colleages_ids[$cid] = $this->cache_get($cid . '_administration_colleages_ids');
      
      if($this->cache['do'] or empty($this->administration_colleages_ids[$cid])){
        $this->is_administration[$cid] = false;

        $params = array(
          'version' => 3,
          'sequential' => 1,
          'relationship_type_id' => $this->relationship_types['administration_of']['id'],
          'contact_id_a' => $cid,
          'is_active' => 1,
        );
        $businesses = civicrm_api('Relationship', 'get', $params);
        unset($params);

        // if there is no result (count is zero), the employee is not a administration
        if(isset($businesses['count']) and 0 == $businesses['count']){
          $this->is_administration[$cid] = false;
          unset($businesses);
          continue;
        }

        // if there is a error (a error occure also if there is no business)
        if(isset($businesses['is_error']) and $businesses['is_error']){
          $this->set_error(ts('Error result relationship get business ! Error message: ') . $businesses['error_message'], ts('set_administration_colleages_ids'));
          unset($businesses);
          continue;
        }

        $this->is_administration[$cid] = true;
        $this->administration_colleages_ids[$cid] = array();

        // loop throught the businesses (business id is contact_id_b) and get all the departments
        foreach($businesses['values'] as $key => $business){

          // add the business to the $administration_colleages_ids
          $this->administration_colleages_ids[$cid][$business['contact_id_b']] = array();
          $this->administration_colleages_ids[$cid][$business['contact_id_b']]['id'] = $business['contact_id_b'];
          $this->administration_colleages_ids[$cid][$business['contact_id_b']]['employees'] = array();

          // get all the departments
          $params = array(
            'version' => 3,
            'sequential' => 1,
            'relationship_type_id' => $this->relationship_types['department_of']['id'],
            'contact_id_b' => $business['contact_id_b'],
            'is_active' => 1,
          );
          $departments = civicrm_api('Relationship', 'get', $params);
          unset($params);

          // count 
          if(isset($departments['count']) and 0 == $departments['count']){
            $this->set_error( ts('The business with name ') . $this->businesses[$business['contact_id_b']]['display_name'] . ts(' has no departments !'), ts('set_administration_colleages_ids'));
            unset($departments);
            continue;
          }

          // error
          if(isset($departments['is_error']) and $departments['is_error']){
            $this->set_error(ts('Error result relationship get department of ! Error message: ') . $departments['error_message'], ts('set_administration_colleages_ids'));
            unset($departments);
            continue;
          }

          // loop through all the departments (department id is contact_id_a) and get all the employees
          foreach ($departments['values'] as $key => $department){
            $params = array(
              'version' => 3,
              'sequential' => 1,
              'relationship_type_id' => $this->relationship_types['employee_of']['id'],
              'contact_id_b' => $department['contact_id_a'],
              'is_active' => 1,
            );
            $employees = civicrm_api('Relationship', 'get', $params);
            unset($params);

            // count
            if(isset($employees['count']) and 0 == $employees['count']){
              $this->set_error( ts('The department with name ') . $this->departments[$department['contact_id_a']]['display_name'] . ts(' has no employees !'), ts('set_administration_colleages_ids'));
              // let the script run and let it generate multiple errors
              unset($employees);
              continue;
            }

            // error
            if(isset($employees['is_error']) and $employees['is_error']){
              $this->set_error(ts('Error result relationship get employee of ! Error message: ') . $employees['error_message'], ts('set_administration_colleages_ids'));
              unset($employees);
              continue;
            }

            // loop thought the employees and save the id in $administration_colleages_ids
            foreach($employees['values'] as $key => $employee){   

              // there are some relationships with contacts that not exists, so
              // we have to check if the contact exists
              $params = array(
                'version' => 3,
                'sequential' => 1,
                'id' => $employee['contact_id_a'],
                'contact_sub_type' => $this->contact_sub_types['employee']['contact_sub_type'],
                'is_deleted' => 0,
              );
              $contact = civicrm_api('Contact', 'getsingle', $params);
              unset($params);

              // sometimes a contact does not exists, but the realtionship does
              if(isset($contact['count']) and 0 == $contact['count']){
                // the employee does not exsist
                unset($contact);
                continue;
              }

              // error
              if(isset($contact['is_error']) and $contact['is_error']){
                $this->set_error(ts('Error result contact get ! Error message: ') . $contact['error_message'], ts('set_administration_colleages_ids'));
                unset($contact);
                continue;
              }

              $this->administration_colleages_ids[$cid][$business['contact_id_b']]['employees'][$employee['contact_id_a']] = array();
              $this->administration_colleages_ids[$cid][$business['contact_id_b']]['employees'][$employee['contact_id_a']]['id'] = $employee['contact_id_a'];
              $this->administration_colleages_ids[$cid][$business['contact_id_b']]['employees'][$employee['contact_id_a']]['display_name'] = $contact['display_name'];
              $this->administration_colleages_ids[$cid][$business['contact_id_b']]['employees'][$employee['contact_id_a']]['email'] = $contact['email'];

              unset($contact);
            }

            unset($employees);
          }

          unset($departments);
        }

        unset($businesses);
        
        $this->cache_set($cid . '_administration_colleages_ids', $this->administration_colleages_ids[$cid]);
      }
    }
    
    // if $administration_colleages_ids is not empty check if all the business have employees
    foreach($this->administration_colleages_ids as $cid => $businessess){
      foreach($businessess as $bid => $business){
        if(empty($business['employees'])){
          $this->set_error( ts('The business with name ') . $this->businesses[$bid]['display_name'] . ts(' has no employees ! Last check !'), ts('set_administration_colleages_ids'));
        }
      }
    }
    
    if($this->isset_error()){
      return false;
    }else {
      return true;
    }
  }
  
  private function set_settings() {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->cids)){
      $this->set_error(ts('No contact ids !'), ts('set_settings'));
    }
        
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT * FROM " . $this->custom_groups['leave_settings']['table_name'];
    
    $where = "";
    foreach($this->cids as $cid){
      $where .= " entity_id = '" . $cid . "' OR ";
    }
    
    $query .= " WHERE " . substr($where, 0, -3);
        
    $dao = CRM_Core_DAO::executeQuery($query);
    
    while($dao->fetch()){
      if(!isset($this->settings[$dao->entity_id])){
        $this->settings[$dao->entity_id] = array();
      }
      
      $this->settings[$dao->entity_id]['show_all_colleagues'] = $dao->{$this->custom_fields['leave_settings_show_all_colleagues_of']['column_name']};
      $this->settings[$dao->entity_id]['show_department_head'] = $dao->{$this->custom_fields['leave_settings_show_department_head_to']['column_name']};
    }
    
    // set to default if not exists
    foreach($this->cids as $cid){
      
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
  
  public function set_data($years = array(), $months = array()) {    
    if($this->isset_error()){
      return false;
    }
              
    if(empty($this->cids)){
      $this->set_error(ts('No contact ids !'), ts('set_data'));
    }
    
    if(empty($this->employees)){
      $this->set_error(ts('No employees !'), ts('set_data'));
    }
    
    if(empty($years)){
      $this->set_error(ts('No years !'), ts('set_data'));
    }
    
    if($this->isset_error()){
      return false;
    }
    
    foreach($this->cids as $cid){
      foreach($years as $year){
        $this->data[$year] = $this->cache_get($cid . '_data_' . $year);
        if(empty($this->data[$cid])){
          $this->cache['do'] = true;
        }
      }
    }
        
    if($this->cache['do']){
      if(empty($this->departments)){
        $this->set_error(ts('No departments !'), ts('set_data'));
      }

      if(empty($this->businesses)){
        $this->set_error(ts('No business !'), ts('set_data'));
      }

      if(empty($this->department_heads)){
        $this->set_error(ts('No department heads !'), ts('set_data'));
      }

      if($this->isset_error()){
        return false;
      }

      $this->data = array();
      foreach($this->cids as $cid){
        $this->data[$cid] = array();
      }

      if(!$this->set_years($years)){
        $this->set_error( ts('An error occur in years !'), ts('set_data'));
        return false;
      }

      if(!$this->set_months($months)){
        $this->set_error( ts('An error occur in months !'), ts('set_data'));
        return false;
      }

      if(!$this->set_days()){
        $this->set_error( ts('An error occur in days !'), ts('set_data'));
        return false;
      }

      if(!$this->set_holidays()){
        $this->set_error( ts('An error occur in holidays !'), ts('set_data'));
        return false;
      }

      if(!$this->set_general()){
        $this->set_error( ts('An error occur in general !'), ts('set_data'));
        return false;
      }

      if(!$this->set_overall_adjustments()){
        $this->set_error( ts('An error occur in overall adjustments !'), ts('set_data'));
        return false;
      }

      if(!$this->set_overall_credit()){
        $this->set_error( ts('An error occur in overall credits!'), ts('set_data'));
        return false;
      }

      if(!$this->set_adjustments()){
        $this->set_error( ts('An error occur in adjustments !'), ts('set_data'));
        return false;
      }    

      if(!$this->set_credit()){
        $this->set_error( ts('An error occur in credits !'), ts('set_data'));
        return false;
      }  

      if(!$this->set_request()){
        $this->set_error( ts('An error occur in request !'), ts('set_data'));
        return false;
      }  

      if(!$this->set_total()){
        $this->set_error( ts('An error occur in total !'), ts('set_data'));
        return false;
      }
      
      foreach($this->cids as $cid){
        foreach($years as $year){
          $this->cache_set($cid . '_data_' . $year, $this->data[$year]);
        }
      }
    
    }
    
    foreach($this->data as $cid => $years){
      echo('$cid: ' . $cid) . '<br/>' . PHP_EOL;
      foreach($years as $year => $months){
        echo('$year: ' . $year) . '<br/>' . PHP_EOL;
      }
      
    }
    return true;
  }
      
  private function set_years(&$years = array()) {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($years)){
      $years = array(date('Y'));
    }
    
    $this->years = $years;
    
    return true;
  }
  
  private function set_months(&$months = array()) {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($months)){
      $months = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    }
    
    $this->months = $months;
    
    return true;
  }
  
  private function set_days() {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('set_days'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('set_days'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('set_days'));
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
  
  private function set_holidays() {
    if($this->isset_error()){
      return false;
    }
        
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('set_holidays'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('set_holidays'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('set_holidays'));
    }
    
    if(empty($this->employees)){
      $this->set_error(ts('No employees !'), ts('set_holidays'));
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('set_holidays'));
    }
    
    if(empty($this->businesses)){
      $this->set_error(ts('No business !'), ts('set_holidays'));
    }
    
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT * FROM " . $this->custom_groups['leave_holidays']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->departments as $cid => $departments){
      foreach($departments as $did => $department){
        $where .= " OR entity_id = '" . $did . "'";
      }
    }
    
    foreach($this->businesses as $cid => $businesses){
      foreach($businesses as $bid => $business){
        $where .= " OR entity_id = '" . $bid . "'";
      }
    }
    
    foreach($this->main_businesses as $cid => $main_businesses){
      foreach($main_businesses as $mbid => $main_business){
        $where .= " OR entity_id = '" . $mbid . "'";
      }
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
      $datas[$dao->entity_id][$dao->id] = array(
        'id' => $dao->id,
        'entity_id' => $dao->entity_id,
        'name' => $dao->{$this->custom_fields['leave_holidays_name']['column_name']},
        'start_date' => $dao->{$this->custom_fields['leave_holidays_start_date']['column_name']},
        'end_date' => $dao->{$this->custom_fields['leave_holidays_end_date']['column_name']},
        'infinite' => $dao->{$this->custom_fields['leave_holidays_infinite']['column_name']}
      );
    }

    if(empty($datas)){
      $this->set_error(ts('No data !'), ts('set_holidays'));
    }
        
    if($this->isset_error()){
      unset($datas);
      return false;
    }
    
    foreach($this->employees as $cid => $employee){
      $mbid = 0;
      $bid = 0;
      $did = 0;
            
      foreach($employee['main_businesses'] as $id){
        $mbid = $id;
      }
      
      foreach($employee['businesses'] as $id){
        $bid = $id;
      }
      
      foreach($employee['departments'] as $id){
        $did = $id;
      }
            
      if(isset($datas[$mbid]) and !empty($datas[$mbid])){
        foreach($datas[$mbid] as $key => $holiday){
          $check = $this->set_holidays_days($cid, $holiday, $check);
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
          $this->set_error( ts('In function holidays, ') . $this->employees[$cid]['display_name'] .  ts(' has no holidays in ') . $year . ts(' !'), ts('set_holidays'));
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
  
  private function set_holidays_days($cid, $holiday, $check) {    
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
  
  private function set_general() {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('set_general'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('set_general'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('set_general'));
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('set_general'));
    }
    
    if(empty($this->businesses)){
      $this->set_error(ts('No business !'), ts('set_general'));
    }
    
    if($this->isset_error()){
      return false;
    }   
    
    $query = "SELECT * FROM " . $this->custom_groups['leave_general']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->departments as $cid => $departments){
      foreach($departments as $did => $department){
        $where .= " OR entity_id = '" . $did . "'";
      }
    }
    
    foreach($this->businesses as $cid => $businesses){
      foreach($businesses as $bid => $business){
        $where .= " OR entity_id = '" . $bid . "'";
      }
    }
    
    foreach($this->main_businesses as $cid => $main_businesses){
      foreach($main_businesses as $mbid => $main_business){
        $where .= " OR entity_id = '" . $mbid . "'";
      }
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
      $this->set_error(ts('No general !'), ts('set_general'));
    }
    
    // check if for cid one of mbid, bid or did exists
    $exists = array();
    foreach($this->data as $cid => $years){     
      $mbid = 0;
      $bid = 0;
      $did = 0;
      
      foreach($this->employees[$cid]['main_businesses'] as $id){
        $mbid = $id;
      }
      
      foreach($this->employees[$cid]['businesses'] as $id){
        $bid = $id;
      }
      
      foreach($this->employees[$cid]['departments'] as $id){
        $did = $id;
      }
      
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
        $this->set_error($this->employees[$cid]['display_name'] . ts(' has no general !'), ts('set_general'));
      }
    }
    
    if($this->isset_error()){
      unset($datas);
      return false;
    } 
        
    foreach($this->data as $cid => $years){
      $mbid = 0;
      $bid = 0;
      $did = 0;
      
      foreach($this->employees[$cid]['main_businesses'] as $id){
        $mbid = $id;
      }
      
      foreach($this->employees[$cid]['businesses'] as $id){
        $bid = $id;
      }
      
      foreach($this->employees[$cid]['departments'] as $id){
        $did = $id;
      }
      
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
  
  private function set_overall_adjustments() {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('set_overall_adjustments'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('set_overall_adjustments'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('set_overall_adjustments'));
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('set_overall_adjustments'));
    }
    
    if(empty($this->businesses)){
      $this->set_error(ts('No business !'), ts('set_overall_adjustments'));
    }
    
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT * FROM " . $this->custom_groups['leave_overall_adjustments']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->departments as $cid => $departments){
      foreach($departments as $did => $department){
        $where .= " OR entity_id = '" . $did . "'";
      }
    }
    
    foreach($this->businesses as $cid => $businesses){
      foreach($businesses as $bid => $business){
        $where .= " OR entity_id = '" . $bid . "'";
      }
    }
    
    foreach($this->main_businesses as $cid => $main_businesses){
      foreach($main_businesses as $mbid => $main_business){
        $where .= " OR entity_id = '" . $mbid . "'";
      }
    }
    
    $query .= " (" . substr($where, 3) . ")";
    
    $query .= " AND (";
    
    $query .= " ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_overall_adjustments_start_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_overall_adjustments_start_date']['column_name'] . " )";
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' <= " . $this->custom_fields['leave_overall_adjustments_end_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' >= " . $this->custom_fields['leave_overall_adjustments_end_date']['column_name'] . " )";
    
    $query .= " OR ('" . min($this->years) . "-" . min($this->months) . "-01 00:00:00' >= " . $this->custom_fields['leave_overall_adjustments_start_date']['column_name'] . " AND '" . max($this->years) . "-" . max($this->months) . "-31 23:59:59' <= " . $this->custom_fields['leave_overall_adjustments_end_date']['column_name'] . " )";
        
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
      $bid = 0;
      $did = 0;
      
      foreach($this->employees[$cid]['main_businesses'] as $id){
        $mbid = $id;
      }
      
      foreach($this->employees[$cid]['businesses'] as $id){
        $bid = $id;
      }
      
      foreach($this->employees[$cid]['departments'] as $id){
        $did = $id;
      }
      
      foreach($years as $year => $months){
        
        foreach($months as $month => $days){
          
          foreach($days as $day => $general){
                        
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
  
  private function set_overall_credit() {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('set_overall_credit'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('set_overall_credit'));
    }
    
    if(empty($this->departments)){
      $this->set_error(ts('No departments !'), ts('set_overall_credit'));
    }
    
    if(empty($this->businesses)){
      $this->set_error(ts('No business !'), ts('set_overall_credit'));
    }
    
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT * FROM " . $this->custom_groups['leave_overall_credit']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->departments as $cid => $departments){
      foreach($departments as $did => $department){
        $where .= " OR entity_id = '" . $did . "'";
      }
    }
    
    foreach($this->businesses as $cid => $businesses){
      foreach($businesses as $bid => $business){
        $where .= " OR entity_id = '" . $bid . "'";
      }
    }
    
    foreach($this->main_businesses as $cid => $main_businesses){
      foreach($main_businesses as $mbid => $main_business){
        $where .= " OR entity_id = '" . $mbid . "'";
      }
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
        $bid = 0;
        $did = 0;

        foreach($this->employees[$cid]['main_businesses'] as $id){
          $mbid = $id;
        }

        foreach($this->employees[$cid]['businesses'] as $id){
          $bid = $id;
        }

        foreach($this->employees[$cid]['departments'] as $id){
          $did = $id;
        }

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
  
  private function set_adjustments() {    
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('set_adjustments'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('set_adjustments'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('set_adjustments'));
    }
    
    if(empty($this->cids)){
      $this->set_error(ts('No contact ids !'), ts('set_adjustments'));
    }
    
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT * FROM " . $this->custom_groups['leave_adjustments']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->cids as $cid){
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
    
  private function set_credit() {   
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->cids)){
      $this->set_error(ts('No contact ids !'), ts('set_credit'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('set_credit'));
    }
        
    if($this->isset_error()){
      return false;
    }
        
    $query = "SELECT * FROM " . $this->custom_groups['leave_credit']['table_name'];
    $query .= " WHERE";
    
    $where = "";
    foreach($this->cids as $cid){
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
              $duration_over = '-' . (($hours * 60) + $minutes);
                                
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
              $duration_over = '-' . (($hours * 60) + $minutes);
              
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
  
  private function set_request() {
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('set_request'));
    }
    
    if(empty($this->years)){
      $this->set_error(ts('No years !'), ts('set_request'));
    }
    
    if(empty($this->months)){
      $this->set_error(ts('No months !'), ts('set_request'));
    }
    
    if(empty($this->cids)){
      $this->set_error(ts('No contact ids !'), ts('set_request'));
    }
    
    $query = "SELECT civicrm_activity.source_record_id, " . $this->custom_groups['leave_request']['table_name'] . ".id, " . $this->custom_groups['leave_request']['table_name'] . ".entity_id";
    
    $query .= ", " . $this->custom_fields['leave_request_leave_type']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_reason']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_status']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_from_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_to_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_date']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_daily_weekly_monthly_annually']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_daily_every_day_working_day']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_daily_each']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_weekly_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_weekly_day_of_the_week']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_monthly_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_monthly_every_day_of_the_month_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_monthly_day_of_the_month']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_monthly_on']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_monthly_day_of_the_week']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_annually_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_every_day_of_the_month_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_month']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_day_of_the_month']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_on']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_month_of']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_duration']['column_name'];
        
    $query .= " FROM " . $this->custom_groups['leave_request']['table_name'];
    $query .= " LEFT JOIN civicrm_activity ON " . $this->custom_groups['leave_request']['table_name'] . ".entity_id = civicrm_activity.id";
    $query .= " WHERE civicrm_activity.activity_type_id = '" . trim($this->custom_groups['leave_request']['extends_entity_column_value']) . "'";
    
    $where = "";
    foreach($this->cids as $cid){
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
                
        'daily_weekly_monthly_annually' => $dao->{$this->custom_fields['leave_request_daily_weekly_monthly_annually']['column_name']},
                
        'daily_every_day_working_day' => $dao->{$this->custom_fields['leave_request_daily_every_day_working_day']['column_name']},
        'daily_each' => $dao->{$this->custom_fields['leave_request_daily_each']['column_name']},
                
        'weekly_each' => $dao->{$this->custom_fields['leave_request_weekly_each']['column_name']},
        'weekly_day_of_the_week' => $dao->{$this->custom_fields['leave_request_weekly_day_of_the_week']['column_name']},
                
        'monthly_each' => $dao->{$this->custom_fields['leave_request_monthly_each']['column_name']},
        'monthly_every_day_of_the_month_day_of_the_week' => $dao->{$this->custom_fields['leave_request_monthly_every_day_of_the_month_day_of_the_week']['column_name']},
        'monthly_day_of_the_month' => $dao->{$this->custom_fields['leave_request_monthly_day_of_the_month']['column_name']},
        'monthly_on' => $dao->{$this->custom_fields['leave_request_monthly_on']['column_name']},
        'monthly_day_of_the_week' => $dao->{$this->custom_fields['leave_request_monthly_day_of_the_week']['column_name']},
                
        'annually_each' => $dao->{$this->custom_fields['leave_request_annually_each']['column_name']},
        'annually_every_day_of_the_month_day_of_the_week' => $dao->{$this->custom_fields['leave_request_annually_every_day_of_the_month_day_of_the_week']['column_name']},
        'annually_month' => $dao->{$this->custom_fields['leave_request_annually_month']['column_name']},
        'annually_day_of_the_month' => $dao->{$this->custom_fields['leave_request_annually_day_of_the_month']['column_name']},
        'annually_on' => $dao->{$this->custom_fields['leave_request_annually_on']['column_name']},
        'annually_day_of_the_week' => $dao->{$this->custom_fields['leave_request_annually_day_of_the_week']['column_name']},
        'annually_month_of' => $dao->{$this->custom_fields['leave_request_annually_month_of']['column_name']},
                             
        'duration' => $dao->{$this->custom_fields['leave_request_duration']['column_name']}
      );
    }
    
    // this is removed, beacause if there is no request in that year there be also no datas    
    /*if(empty($datas)){
      $this->set_error(ts('No data !'), ts('Request'));
    }*/
    
    if($this->isset_error()){
      unset($datas);
      return false;
    }
    
    $this->request = $datas;
       
    // first
    foreach($datas as $id => $data){
      if('rejected' !== $data['status']){
      
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
                if(!isset($this->data[$data['cid']][$year][$month][$day]['request']['leave_type']) // if leave_type don exists
                or ('approved' == $data['status'] and 'approved' != $this->data[$data['cid']][$year][$month][$day]['request']['status'])){ // or if current request is approved and the other not
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
            $patterns = $this->set_pattern_outlook($data);
            
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
                          $adjustments_duration = $this->data[$data['cid']][$year][$month][$day]['adjustments']['duration'];

                        }else if(isset($this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration']){
                          $adjustments_duration = $this->data[$data['cid']][$year][$month][$day]['overall_adjustments']['duration'];

                        }else if(isset($this->data[$data['cid']][$year][$month][$day]['general']['duration']) and '' !== $this->data[$data['cid']][$year][$month][$day]['general']['duration']){
                          $adjustments_duration = $this->data[$data['cid']][$year][$month][$day]['general']['duration'];
                        }

                        // this correct the duration to the leave adjustments, because the work only a certain amound
                        if($duration > $adjustments_duration){
                          $duration = $adjustments_duration;
                        }

                        // if adjustment is not 0, if it does he/she is free
                        if(0 != $duration){

                          if(!isset($this->data[$cid][$year][$month][$day]['request']['leave_type']) // if leave_type don exists
                          or ('approved' == $pattern['status'] and 'approved' != $this->data[$cid][$year][$month][$day]['request']['status'])){ // or if current request is approved and the other not
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
                  if(!isset($this->data[$data['cid']][$year][$month][$day]['request']['leave_type']) // if leave_type don exists
                  or ('approved' == $data['status'] and 'approved' != $this->data[$data['cid']][$year][$month][$day]['request']['status'])){ // or if current request is approved and the other not
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
    }
        
    // second the rest
    foreach($datas as $id => $data){
      if('rejected' !== $data['status']){         
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

                if(!isset($this->data[$data['cid']][$year][$month][$day]['normal_leave']['leave_type']) // if leave_type don exists
                or ('approved' == $data['status'] and 'approved' != $this->data[$data['cid']][$year][$month][$day]['normal_leave']['status'])){ // or if current request is approved and the other not
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
          case 'atv':
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

                    if(!isset($this->data[$data['cid']][$year][$month][$day]['normal_leave']['leave_type']) // if leave_type don exists
                    or ('approved' == $data['status'] and 'approved' != $this->data[$data['cid']][$year][$month][$day]['normal_leave']['status'])){ // or if current request is approved and the other not
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
    }
    
    unset($datas);
    return true;
  }
  
  /**
   * Calculate pattern for general and request.
   */
  public function set_pattern($data, $type){
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
  
  /**
   * Calculate pattern for adjustments and request.
   */
  public function set_pattern_outlook($data){
    
    $start_date_totime = strtotime($data['from_date']);
    $end_date_totime = strtotime($data['to_date']);
    $cid = $data['cid'];
    
    $patterns = array();
    switch($data['daily_weekly_monthly_annually']){
      case 'daily':
        switch($data['daily_every_day_working_day']){
          case 'day':
            for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+' . $data['daily_each'] . ' day' , $timestamp )){
              $day = date('d', $timestamp);  
              $month = date('m', $timestamp);  
              $year = date('Y', $timestamp);
              
              // check if it is between start date and end date
              if($start_date_totime <= $timestamp and $end_date_totime >= $timestamp){
                $patterns[$cid][$year][$month][$day] = $data;
              }
            }
            break;
          case 'working_day':
            for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+' . $data['daily_each'] . ' day' , $timestamp )){
              $day_of_week = date('N', $timestamp);
              if((6 != $day_of_week) and (7 != $day_of_week)){
                $day = date('d', $timestamp);  
                $month = date('m', $timestamp);  
                $year = date('Y', $timestamp);
                
                // check if it is between start date and end date
                if($start_date_totime <= $timestamp and $end_date_totime >= $timestamp){
                  $patterns[$cid][$year][$month][$day] = $data;
                }
              }
            }
            break;
        }
        break;
      case 'weekly':
        for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+' . $data['weekly_each'] . ' week' , $timestamp )){ 
          if(empty($data['weekly_day_of_the_week'])){
            $day = date('d', $timestamp);  
            $month = date('m', $timestamp);  
            $year = date('Y', $timestamp);
            
            // check if it is between start date and end date
            if($start_date_totime <= $timestamp and $end_date_totime >= $timestamp){
              $patterns[$cid][$year][$month][$day] = $data;
            }
            
          }else {      
            $weekly_day_of_the_week = explode(CRM_Core_DAO::VALUE_SEPARATOR, $data['weekly_day_of_the_week']);
            
            foreach($weekly_day_of_the_week as $day_of_the_week){
              $day = date('d', strtotime($day_of_the_week . ' this week', $timestamp));
              $month = date('m', strtotime($day_of_the_week . ' this week', $timestamp));
              $year = date('Y', strtotime($day_of_the_week . ' this week', $timestamp));
              
              $day_of_the_week_timestamp = strtotime($year . '-' . $month . '-' . $day);
              
              // check if it is between start date and end date
              if($start_date_totime <= $day_of_the_week_timestamp and $end_date_totime >= $day_of_the_week_timestamp){
                $patterns[$cid][$year][$month][$day] = $data;
              }
            }
          }         
        }
        break;
      case 'monthly':
        for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+' . $data['monthly_each'] . ' month' , $timestamp )){
          switch($data['monthly_every_day_of_the_month_day_of_the_week']){
            case 'every_day_of_the_month':  
              $day = date('d', strtotime('1976-1-' . $data['monthly_day_of_the_month']));
              $month = date('m', $timestamp);  
              $year = date('Y', $timestamp);
              
              // check if it is between start date and end date
              if($start_date_totime <= $timestamp and $end_date_totime >= $timestamp){
                $patterns[$cid][$year][$month][$day] = $data;
              }
              break;
            case 'every_day_of_the_week':
              switch($data['monthly_day_of_the_week']){
                case 'day':
                  $day = date('d', strtotime($data['monthly_on'] . ' day of this month', $timestamp));
                  $month = date('m', strtotime($data['monthly_on'] . ' day of this month', $timestamp));
                  $year = date('Y', strtotime($data['monthly_on'] . ' day of this month', $timestamp));
                  
                  $every_day_of_the_week_timestamp = strtotime($year . '-' . $month . '-' . $day);
                  
                  // check if it is between start date and end date
                  if($start_date_totime <= $every_day_of_the_week_timestamp and $end_date_totime >= $every_day_of_the_week_timestamp){
                    $patterns[$cid][$year][$month][$day] = $data;
                  }
                  break;
                case 'working_day':
                  // loop trough all days
                  // remember the working days
                  $working_days = array();
                  $days_of_the_month_start_timestamp = strtotime('first day of this month', $timestamp);
                  $days_of_the_month_end_timestamp = strtotime('last day of this month', $timestamp);
                  for($days_of_the_month_timestamp = $days_of_the_month_start_timestamp; $days_of_the_month_timestamp <= $days_of_the_month_end_timestamp; $days_of_the_month_timestamp = strtotime( '+' . $data['daily_each'] . ' day' , $days_of_the_month_timestamp )){
                    $day_of_week = date('N', $days_of_the_month_timestamp);
                    if((6 != $day_of_week) and (7 != $day_of_week)){
                      $working_days[] = date('d', $days_of_the_month_timestamp);  
                    }
                  }
                  
                  switch($data['monthly_on']){
                    case 'first':
                      $day = $working_days[0];
                      break;
                    case 'second':
                      $day = $working_days[1];
                      break;
                    case 'third':
                      $day = $working_days[2];
                      break;
                    case 'fourth':
                      $day = $working_days[3];
                      break;
                    case 'last':
                      $day = $working_days[(count($working_days)-1)];
                      break;
                  }
                  
                  $month = date('m', $timestamp);
                  $year = date('Y', $timestamp);
                  
                  $every_day_of_the_week_timestamp = strtotime($year . '-' . $month . '-' . $day);
                  
                  // check if it is between start date and end date
                  if($start_date_totime <= $every_day_of_the_week_timestamp and $end_date_totime >= $every_day_of_the_week_timestamp){
                    $patterns[$cid][$year][$month][$day] = $data;
                  }
                  
                  break;
                case 'weekend_day':
                  // loop trough all days
                  // remember the weekend days
                  $weekend_days = array();
                  $days_of_the_month_start_timestamp = strtotime('first day of this month', $timestamp);
                  $days_of_the_month_end_timestamp = strtotime('last day of this month', $timestamp);
                  for($days_of_the_month_timestamp = $days_of_the_month_start_timestamp; $days_of_the_month_timestamp <= $days_of_the_month_end_timestamp; $days_of_the_month_timestamp = strtotime( '+' . $data['daily_each'] . ' day' , $days_of_the_month_timestamp )){
                    $day_of_week = date('N', $days_of_the_month_timestamp);
                    if((6 == $day_of_week) or (7 == $day_of_week)){
                      $weekend_days[] = date('d', $days_of_the_month_timestamp);  
                    }
                  }
                  
                  switch($data['monthly_on']){
                    case 'first':
                      $day = $weekend_days[0];
                      break;
                    case 'second':
                      $day = $weekend_days[1];
                      break;
                    case 'third':
                      $day = $weekend_days[2];
                      break;
                    case 'fourth':
                      $day = $weekend_days[3];
                      break;
                    case 'last':
                      $day = $weekend_days[(count($weekend_days)-1)];
                      break;
                  }
                  
                  $month = date('m', $timestamp);
                  $year = date('Y', $timestamp);
                  
                  $every_day_of_the_week_timestamp = strtotime($year . '-' . $month . '-' . $day);
                  
                  // check if it is between start date and end date
                  if($start_date_totime <= $every_day_of_the_week_timestamp and $end_date_totime >= $every_day_of_the_week_timestamp){
                    $patterns[$cid][$year][$month][$day] = $data;
                  }
                  break;
                default:
                  $day = date('d', strtotime($data['monthly_on'] . ' ' . $data['monthly_day_of_the_week'] . ' of this month', $timestamp));
                  $month = date('m', strtotime($data['monthly_on'] . ' ' . $data['monthly_day_of_the_week'] . ' of this month', $timestamp));
                  $year = date('Y', strtotime($data['monthly_on'] . ' ' . $data['monthly_day_of_the_week'] . ' of this month', $timestamp));

                  $every_day_of_the_week_timestamp = strtotime($year . '-' . $month . '-' . $day);
                  // check if it is between start date and end date
                  if($start_date_totime <= $every_day_of_the_week_timestamp and $end_date_totime >= $every_day_of_the_week_timestamp){
                    $patterns[$cid][$year][$month][$day] = $data;
                  }
              }
              break;
          }
        }
        break;
      case 'annually':
        for($timestamp = $start_date_totime; $timestamp <= $end_date_totime; $timestamp = strtotime( '+' . $data['annually_each'] . ' year' , $timestamp )){
          switch($data['annually_every_day_of_the_month_day_of_the_week']){
            case 'every_day_of_the_month':
              $day = date('d', strtotime('1976-1-' . $data['annually_day_of_the_month']));
              $month = date('m', strtotime($data['annually_month']));
              $year = date('Y', $timestamp);
              
              // check if it is between start date and end date
              if($start_date_totime <= $timestamp and $end_date_totime >= $timestamp){
                $patterns[$cid][$year][$month][$day] = $data;
              }
              break;
            case 'every_day_of_the_week':
              switch($data['annually_day_of_the_week']){
                case 'day':
                  $day = date('d', strtotime($data['monthly_on'] . ' day of ' . $data['annually_month_of'], $timestamp));
                  $month = date('m', strtotime($data['monthly_on'] . ' day of ' . $data['annually_month_of'], $timestamp));
                  $year = date('Y', strtotime($data['monthly_on'] . ' day of ' . $data['annually_month_of'], $timestamp));
                  
                  $every_day_of_the_week_timestamp = strtotime($year . '-' . $month . '-' . $day);
                  
                  // check if it is between start date and end date
                  if($start_date_totime <= $every_day_of_the_week_timestamp and $end_date_totime >= $every_day_of_the_week_timestamp){
                    $patterns[$cid][$year][$month][$day] = $data;
                  }
                  break;
                case 'working_day':
                  // loop trough all days
                  // remember the working days
                  $working_days = array();
                  $days_of_the_month_start_timestamp = strtotime('first day of ' . $data['annually_month_of'], $timestamp);
                  $days_of_the_month_end_timestamp = strtotime('last day of ' . $data['annually_month_of'], $timestamp);
                  for($days_of_the_month_timestamp = $days_of_the_month_start_timestamp; $days_of_the_month_timestamp <= $days_of_the_month_end_timestamp; $days_of_the_month_timestamp = strtotime( '+' . $data['daily_each'] . ' day' , $days_of_the_month_timestamp )){
                    $day_of_week = date('N', $days_of_the_month_timestamp);
                    if((6 != $day_of_week) and (7 != $day_of_week)){
                      $working_days[] = date('d', $days_of_the_month_timestamp);  
                    }
                  }
                  
                  switch($data['annually_on']){
                    case 'first':
                      $day = $working_days[0];
                      break;
                    case 'second':
                      $day = $working_days[1];
                      break;
                    case 'third':
                      $day = $working_days[2];
                      break;
                    case 'fourth':
                      $day = $working_days[3];
                      break;
                    case 'last':
                      $day = $working_days[(count($working_days)-1)];
                      break;
                  }
                  
                  $month = date('m', strtotime($day . ' of ' . $data['annually_month_of'], $timestamp));
                  $year = date('Y', $timestamp);
                  
                  $every_day_of_the_week_timestamp = strtotime($year . '-' . $month . '-' . $day);
                  
                  // check if it is between start date and end date
                  if($start_date_totime <= $every_day_of_the_week_timestamp and $end_date_totime >= $every_day_of_the_week_timestamp){
                    $patterns[$cid][$year][$month][$day] = $data;
                  }
                  
                  break;
                case 'weekend_day':
                  // loop trough all days
                  // remember the weekend days
                  $weekend_days = array();
                  $days_of_the_month_start_timestamp = strtotime('first day of ' . $data['annually_month_of'], $timestamp);
                  $days_of_the_month_end_timestamp = strtotime('last day of ' . $data['annually_month_of'], $timestamp);
                  for($days_of_the_month_timestamp = $days_of_the_month_start_timestamp; $days_of_the_month_timestamp <= $days_of_the_month_end_timestamp; $days_of_the_month_timestamp = strtotime( '+' . $data['daily_each'] . ' day' , $days_of_the_month_timestamp )){
                    $day_of_week = date('N', $days_of_the_month_timestamp);
                    if((6 == $day_of_week) or (7 == $day_of_week)){
                      $weekend_days[] = date('d', $days_of_the_month_timestamp);  
                    }
                  }
                  
                  switch($data['annually_on']){
                    case 'first':
                      $day = $weekend_days[0];
                      break;
                    case 'second':
                      $day = $weekend_days[1];
                      break;
                    case 'third':
                      $day = $weekend_days[2];
                      break;
                    case 'fourth':
                      $day = $weekend_days[3];
                      break;
                    case 'last':
                      $day = $weekend_days[(count($weekend_days)-1)];
                      break;
                  }
                  
                  $month = date('m', strtotime($day . ' of ' . $data['annually_month_of'], $timestamp));
                  $year = date('Y', $timestamp);
                  
                  $every_day_of_the_week_timestamp = strtotime($year . '-' . $month . '-' . $day);
                  
                  // check if it is between start date and end date
                  if($start_date_totime <= $every_day_of_the_week_timestamp and $end_date_totime >= $every_day_of_the_week_timestamp){
                    $patterns[$cid][$year][$month][$day] = $data;
                  }
                  break;
                default:
                  $day = date('d', strtotime($data['annually_on'] . ' ' . $data['annually_day_of_the_week'] . ' of ' . $data['annually_month_of'], $timestamp));
                  $month = date('m', strtotime($data['annually_on'] . ' ' . $data['annually_day_of_the_week'] . ' of ' . $data['annually_month_of'], $timestamp));
                  $year = date('Y', strtotime($data['annually_on'] . ' ' . $data['annually_day_of_the_week'] . ' of ' . $data['annually_month_of'], $timestamp));

                  $every_day_of_the_week_timestamp = strtotime($year . '-' . $month . '-' . $day);
                  // check if it is between start date and end date
                  if($start_date_totime <= $every_day_of_the_week_timestamp and $end_date_totime >= $every_day_of_the_week_timestamp){
                    $patterns[$cid][$year][$month][$day] = $data;
                  }
              }
              break;
          }
        }
        break;
    }
    
    return $patterns;
  }
  
  private function set_total(){
    if($this->isset_error()){
      return false;
    }
    
    if(empty($this->data)){
      $this->set_error(ts('No data !'), ts('set_total'));
    }
    
    if(empty($this->total)){
      $this->set_error(ts('No total !'), ts('set_total'));
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
        
        if(!isset($this->total[$cid][$year]['credit_total_over']) or '' == $this->total[$cid][$year]['credit_total_over']){
          $this->total[$cid][$year]['credit_total_over'] = 0;
        }
        
        $this->total[$cid][$year]['over'] = $this->total[$cid][$year]['credit_total'] + $this->total[$cid][$year]['credit_total_over'] - $this->total[$cid][$year]['used'];
      }
    }
    
    return true;
  }
  
  public function overview($cid, $from_date, $to_date){
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
  
  public function check($cids, $leave_type, $id = '', $from_date = '', $to_date = '', $date = '', $do_not_time_for_time = true, $limit = true){
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
  
  public function get($id){
    $query = "SELECT civicrm_activity.source_record_id, " . $this->custom_groups['leave_request']['table_name'] . ".id, " . $this->custom_groups['leave_request']['table_name'] . ".entity_id";
        
    $query .= ", " . $this->custom_fields['leave_request_leave_type']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_reason']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_status']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_from_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_to_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_date']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_daily_weekly_monthly_annually']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_daily_every_day_working_day']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_daily_each']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_weekly_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_weekly_day_of_the_week']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_monthly_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_monthly_every_day_of_the_month_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_monthly_day_of_the_month']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_monthly_on']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_monthly_day_of_the_week']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_annually_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_every_day_of_the_month_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_month']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_day_of_the_month']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_on']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_month_of']['column_name'];
    
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
        'date' => $dao->{$this->custom_fields['leave_request_date']['column_name']},
        
        'daily_weekly_monthly_annually' => $dao->{$this->custom_fields['leave_request_daily_weekly_monthly_annually']['column_name']},
                
        'daily_every_day_working_day' => $dao->{$this->custom_fields['leave_request_daily_every_day_working_day']['column_name']},
        'daily_each' => $dao->{$this->custom_fields['leave_request_daily_each']['column_name']},
                
        'weekly_each' => $dao->{$this->custom_fields['leave_request_weekly_each']['column_name']},
        'weekly_day_of_the_week' => $dao->{$this->custom_fields['leave_request_weekly_day_of_the_week']['column_name']},
                
        'monthly_each' => $dao->{$this->custom_fields['leave_request_monthly_each']['column_name']},
        'monthly_every_day_of_the_month_day_of_the_week' => $dao->{$this->custom_fields['leave_request_monthly_every_day_of_the_month_day_of_the_week']['column_name']},
        'monthly_day_of_the_month' => $dao->{$this->custom_fields['leave_request_monthly_day_of_the_month']['column_name']},
        'monthly_on' => $dao->{$this->custom_fields['leave_request_monthly_on']['column_name']},
        'monthly_day_of_the_week' => $dao->{$this->custom_fields['leave_request_monthly_day_of_the_week']['column_name']},
                
        'annually_each' => $dao->{$this->custom_fields['leave_request_annually_each']['column_name']},
        'annually_every_day_of_the_month_day_of_the_week' => $dao->{$this->custom_fields['leave_request_annually_every_day_of_the_month_day_of_the_week']['column_name']},
        'annually_month' => $dao->{$this->custom_fields['leave_request_annually_month']['column_name']},
        'annually_day_of_the_month' => $dao->{$this->custom_fields['leave_request_annually_day_of_the_month']['column_name']},
        'annually_on' => $dao->{$this->custom_fields['leave_request_annually_on']['column_name']},
        'annually_day_of_the_week' => $dao->{$this->custom_fields['leave_request_annually_day_of_the_week']['column_name']},
        'annually_month_of' => $dao->{$this->custom_fields['leave_request_annually_month_of']['column_name']},
        
        'duration' => $dao->{$this->custom_fields['leave_request_duration']['column_name']}
      );
    }
    
    return $data;
  }
  
  public function create($cid, $values){
    // civicrm_activity
    /*$query = "INSERT INTO civicrm_activity (id, source_record_id, activity_type_id, activity_date_time, status_id, priority_id, is_test, is_auto, is_current_revision, is_deleted)";
    $query .= " VALUES ('', '" . $cid . "', '" . $this->custom_groups['leave_request']['extends_entity_column_value'] . "','" . date('Y-m-d H:i:s') . "', '" . $values['status_id'] . "', '2', '0', '0', '1', '0') ";
         
    $dao = CRM_Core_DAO::executeQuery($query);
    $last_inserted_id = CRM_Core_DAO::singleValueQuery('SELECT LAST_INSERT_ID()');
    */
    
    $return = array();
    $return['is_error'] = false;
    
    // civicrm_activity
    $params = array(
      'version' => 3,
      'sequential' => 1,
      //'id' => $id,
      'source_record_id' => $values['source_record_id'], // contact_id of the request
      'activity_type_id' => $this->custom_groups['leave_request']['extends_entity_column_value'],
      'subject' => ts('Leave request from ') . $values['display_name'] . ts(', leave type ') . ts($this->option_groups['leave_request_leave_type']['options'][$values['leave_type']]),
      'activity_date_time' => date('Y-m-d H:i:s'),
      //'duration' => $values['duration'], // can not be 0:00
      'status_id' => $values['status_id'],
      'priority_id' => '2',
      'is_test' => '0',
      'is_auto' => '0',
      'is_current_revision' => '1',
      'is_deleted' => '0',
      'source_contact_id' => $values['source_contact_id'], // Toegevoegd door
      'target_contact_id' => $values['target_contact_id'], // Op welke naam
    );
    $activity = civicrm_api('Activity', 'create', $params);
    
    if(isset($activity['is_error']) and $activity['is_error']){
      $return['is_error'] = true;
      $return['error_message'] = $activity['error_message'];
      return $return;
    }  
    
    $id = $activity['id'];
    $return['id'] = $id;
    
    // civicrm_leave_request
    $query = "INSERT INTO " . $this->custom_groups['leave_request']['table_name'] . " (id, entity_id";
    $query .= ", " . $this->custom_fields['leave_request_leave_type']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_reason']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_status']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_from_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_to_date']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_date']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_daily_weekly_monthly_annually']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_daily_every_day_working_day']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_daily_each']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_weekly_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_weekly_day_of_the_week']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_monthly_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_monthly_every_day_of_the_month_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_monthly_day_of_the_month']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_monthly_on']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_monthly_day_of_the_week']['column_name'];
    
    $query .= ", " . $this->custom_fields['leave_request_annually_each']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_every_day_of_the_month_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_month']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_day_of_the_month']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_on']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_day_of_the_week']['column_name'];
    $query .= ", " . $this->custom_fields['leave_request_annually_month_of']['column_name'];
        
    $query .= ", " . $this->custom_fields['leave_request_duration']['column_name'];
    $query .= ")";
    
    $query .= " VALUES ('" . $id . "', '" . $id . "'";
    $query .= ", '" . $values['leave_type'] . "'";
    $query .= ", '" . mysql_real_escape_string($values['reason']) . "'";
    $query .= ", '" . $values['status'] . "'";
    $query .= ", '" . $values['from_date'] . "'";
    $query .= ", '" . $values['to_date'] . "'";
    $query .= ", '" . $values['date'] . "'";
    
    $query .= ", '" . $values['daily_weekly_monthly_annually'] . "'";
    
    $query .= ", '" . $values['daily_every_day_working_day'] . "'";
    $query .= ", '" . $values['daily_each'] . "'";
    
    $query .= ", '" . $values['weekly_each'] . "'";
    $query .= ", '" . $values['weekly_day_of_the_week'] . "'";
    
    $query .= ", '" . $values['monthly_each'] . "'";
    $query .= ", '" . $values['monthly_every_day_of_the_month_day_of_the_week'] . "'";
    $query .= ", '" . $values['monthly_day_of_the_month'] . "'";
    $query .= ", '" . $values['monthly_on'] . "'";
    $query .= ", '" . $values['monthly_day_of_the_week'] . "'";
    
    $query .= ", '" . $values['annually_each'] . "'";
    $query .= ", '" . $values['annually_every_day_of_the_month_day_of_the_week'] . "'";
    $query .= ", '" . $values['annually_month'] . "'";
    $query .= ", '" . $values['annually_day_of_the_month'] . "'";
    $query .= ", '" . $values['annually_on'] . "'";
    $query .= ", '" . $values['annually_day_of_the_week'] . "'";
    $query .= ", '" . $values['annually_month_of'] . "'";
        
    $query .= ", '" . $values['duration'] . "'";
    $query .= ")";

    $dao = CRM_Core_DAO::executeQuery($query);
    //$id = CRM_Core_DAO::singleValueQuery('SELECT LAST_INSERT_ID()');
    
    $return['id'] = $id;
        
    return $return;
  }
    
  public function update($id, $cid, $values){ 
    $return = array();
    $return['is_error'] = false;
    
    // civicrm_activity
    $params = array(
      'version' => 3,
      'sequential' => 1,
      'id' => $id,
      'source_record_id' => $values['source_record_id'], // contact_id of the request
      'activity_type_id' => $this->custom_groups['leave_request']['extends_entity_column_value'],
      'subject' => ts('Leave request from ') . $values['display_name'] . ts(', leave type ') . ts($this->option_groups['leave_request_leave_type']['options'][$values['leave_type']]),
      //'activity_date_time' => date('Y-m-d H:i:s'),
      //'duration' => $values['duration'], // can not be 0:00
      'status_id' => $values['status_id'],
      'priority_id' => '2',
      'is_test' => '0',
      'is_auto' => '0',
      'is_current_revision' => '1',
      'is_deleted' => '0',
      //'source_contact_id' => $values['source_contact_id'], // Toegevoegd door
      'target_contact_id' => $values['target_contact_id'], // Op welke naam
    );
    $activity = civicrm_api('Activity', 'create', $params);   
    
    if(isset($activity['is_error']) and $activity['is_error']){
      $return['is_error'] = true;
      $return['error_message'] = $activity['error_message'];
      return $return;
    }
    
    $return['id'] = $id;
                    
    // civicrm_leave_request
    $query = "UPDATE " . $this->custom_groups['leave_request']['table_name'];
    
    $query .= " SET";
    $query .= " " . $this->custom_fields['leave_request_leave_type']['column_name'] . " = '" . $values['leave_type'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_reason']['column_name'] . " = '" . mysql_real_escape_string($values['reason']) . "'";
    $query .= ", " . $this->custom_fields['leave_request_status']['column_name'] . " = '" . $values['status'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_from_date']['column_name'] . " = '" . $values['from_date'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_to_date']['column_name'] . " = '" . $values['to_date'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_date']['column_name'] . " = '" . $values['date'] . "'";
        
    $query .= ", " . $this->custom_fields['leave_request_daily_weekly_monthly_annually']['column_name'] . " = '" . $values['daily_weekly_monthly_annually'] . "'";
    
    $query .= ", " . $this->custom_fields['leave_request_daily_every_day_working_day']['column_name'] . " = '" . $values['daily_every_day_working_day'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_daily_each']['column_name'] . " = '" . $values['daily_each'] . "'";
    
    $query .= ", " . $this->custom_fields['leave_request_weekly_each']['column_name'] . " = '" . $values['weekly_each'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_weekly_day_of_the_week']['column_name'] . " = '" . $values['weekly_day_of_the_week'] . "'";
    
    $query .= ", " . $this->custom_fields['leave_request_monthly_each']['column_name'] . " = '" . $values['monthly_each'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_monthly_every_day_of_the_month_day_of_the_week']['column_name'] . " = '" . $values['monthly_every_day_of_the_month_day_of_the_week'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_monthly_day_of_the_month']['column_name'] . " = '" . $values['monthly_day_of_the_month'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_monthly_on']['column_name'] . " = '" . $values['monthly_on'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_monthly_day_of_the_week']['column_name'] . " = '" . $values['monthly_day_of_the_week'] . "'";
    
    $query .= ", " . $this->custom_fields['leave_request_annually_each']['column_name'] . " = '" . $values['annually_each'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_annually_every_day_of_the_month_day_of_the_week']['column_name'] . " = '" . $values['annually_every_day_of_the_month_day_of_the_week'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_annually_month']['column_name'] . " = '" . $values['annually_month'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_annually_day_of_the_month']['column_name'] . " = '" . $values['annually_day_of_the_month'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_annually_on']['column_name'] . " = '" . $values['annually_on'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_annually_day_of_the_week']['column_name'] . " = '" . $values['annually_day_of_the_week'] . "'";
    $query .= ", " . $this->custom_fields['leave_request_annually_month_of']['column_name'] . " = '" . $values['annually_month_of'] . "'";
    
    $query .= ", " . $this->custom_fields['leave_request_duration']['column_name'] . " = '" . $values['duration'] . "'";
    
    $query .= " WHERE id = '" . $id . "'";
    $dao = CRM_Core_DAO::executeQuery($query);
    
    return $return;
  }
  
  public function delete($id){
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
  
  public function credit_over_get($cid, $year){
    $query = "SELECT " . $this->custom_groups['leave_credit']['table_name'] . ".id, " . $this->custom_groups['leave_credit']['table_name'] . ".entity_id";
        
    $query .= ", " . $this->custom_fields['leave_credit_from_year']['column_name'];
    $query .= ", " . $this->custom_fields['leave_credit_to_year']['column_name'];
    $query .= ", " . $this->custom_fields['leave_credit_infinite']['column_name'];
    $query .= ", " . $this->custom_fields['leave_credit_total_leave_per_year']['column_name'];
    $query .= ", " . $this->custom_fields['leave_credit_total_leave_per_year_over']['column_name'];
    
    $query .= " FROM " . $this->custom_groups['leave_credit']['table_name'];
    
    $query .= " WHERE " . $this->custom_groups['leave_credit']['table_name'] . ".entity_id = '" . $cid . "'";
    $query .= " AND " . $this->custom_fields['leave_credit_from_year']['column_name'] . " LIKE '" . $year . "%'";
    $query .= " AND " . $this->custom_fields['leave_credit_to_year']['column_name'] . " IS NULL";
    $query .= " AND " . $this->custom_fields['leave_credit_infinite']['column_name'] . " = '0'";
    $query .= " LIMIT 1";
    
    $data = array();
    
    $dao = CRM_Core_DAO::executeQuery($query);
    while($dao->fetch()){
      
      $data = array
      (
        'id' => $dao->id,
        'entity_id' => $dao->entity_id,
        'from_year' => $dao->{$this->custom_fields['leave_credit_from_year']['column_name']},
        'to_year' => $dao->{$this->custom_fields['leave_credit_to_year']['column_name']},
        'infinite' => $dao->{$this->custom_fields['leave_credit_infinite']['column_name']},
        'total_leave_per_year' => $dao->{$this->custom_fields['leave_credit_total_leave_per_year']['column_name']},
        'leave_per_year_over' => $dao->{$this->custom_fields['leave_credit_total_leave_per_year_over']['column_name']}
      );
    }
    
    return $data;
  }
  
  public function credit_over_update($id, $over){
    if('-' == substr($over, 0, 1)){
      $credit_total_over = substr($over, 1); 
      $hours = floor(round($credit_total_over) / 60);
      $minutes = round($credit_total_over) - ($hours * 60);
      $over = '-' . $hours . ':' . sprintf("%02s", $minutes);

    }else {
      $hours = floor(round($over) / 60);
      $minutes = round($over) - ($hours * 60);
      $over = $hours . ':' . sprintf("%02s", $minutes);
    }
    
    $query = "UPDATE " . $this->custom_groups['leave_credit']['table_name'] . " SET " . $this->custom_fields['leave_credit_total_leave_per_year_over']['column_name'] . " = '" . $over . "' WHERE id = '" . $id . "'";
    $dao = CRM_Core_DAO::executeQuery($query);
    
    return $id;
  }
  
  public function credit_over_create($cid, $year, $over){
    if('-' == substr($over, 0, 1)){
      $credit_total_over = substr($over, 1); 
      $hours = floor(round($credit_total_over) / 60);
      $minutes = round($credit_total_over) - ($hours * 60);
      $over = '-' . $hours . ':' . sprintf("%02s", $minutes);

    }else {
      $hours = floor(round($over) / 60);
      $minutes = round($over) - ($hours * 60);
      $over = $hours . ':' . sprintf("%02s", $minutes);
    }
    
    $query = "INSERT INTO " . $this->custom_groups['leave_credit']['table_name'] . " (id, entity_id, 
      " . $this->custom_fields['leave_credit_from_year']['column_name'] . ", 
      " . $this->custom_fields['leave_credit_to_year']['column_name'] . ", 
      " . $this->custom_fields['leave_credit_infinite']['column_name'] . ", 
      " . $this->custom_fields['leave_credit_total_leave_per_year']['column_name'] . ", 
      " . $this->custom_fields['leave_credit_total_leave_per_year_over']['column_name'] . ")";
    $query .= " VALUES ('', '" . $cid . "', '" . $year . "-01-01 00:00:00', NULL, '0', '0:00', '" . $over . "') ";
    
    $dao = CRM_Core_DAO::executeQuery($query);
    $id = CRM_Core_DAO::singleValueQuery('SELECT LAST_INSERT_ID()');
    
    return $id;
  }
  
  public function cache_settings(){
    $private = drupal_realpath('private://');
    if(empty($private)){
      $this->set_error( ts('No private path in drupal file system !'), ts('Cache Settings'));
      return false;
    }
    
    $this->cache['path'] = $private . '/civicrm/leaveregistration/';
    if (!file_exists($this->cache['path'])) {
      if(mkdir($this->cache['path'], 0755, true)){
        $this->set_error( ts('Cannnot make dir (%1) !', array(1 => $this->cache['path'])), ts('cache_settings'));
        return false;
      }
    }
    
    return true;
  }
  
  public function cache_set($key, $data){
    $filename = $this->cache['path'] . $key . '.php';
        
    $content = "<?php" . PHP_EOL;
    $content .= "if(basename(__FILE__) == basename(\$_SERVER['PHP_SELF'])){return array();}" . PHP_EOL;
    $content .= "return " . var_export($data, true) . ";";  
    
    if (!$handle = fopen($filename, "w")){
      $this->set_error( ts('Cannnot open the cache file (%1) !', array(1 => $filename)), ts('cache_set'));
      return false;
    }

    if (fwrite($handle, $content) === FALSE) {
      fclose($handle);
      $this->set_error( ts('Cannnot wite to cache file (%1) !', array(1 => $filename)), ts('cache_set'));
      return false;
    }
    fclose($handle);
    
    return true;
  }
  
  public function cache_get($key){
    $filename = $this->cache['path'] . $key . '.php';
    
    $data = array();
    if (file_exists($filename)) {
      //ob_start();
      $data = include ($filename);
      //ob_end_clean();
      //echo('cache_get $data: ' . $data);
      //$data = unserialize($data);
    }
        
    return $data;
  }

  private function ical_date($timestamp) {
    return date('Ymd\THis\Z', $timestamp);
  }
 
  // Escapes a string of characters
  private function ical_escape($string) {
    return preg_replace('/([\,;])/','\\\$1', $string);
  }
  
  public function ical($id, $location, $uri, $summary){
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