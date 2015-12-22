{* form *}
{if $type eq 'form'}
    {foreach from=$form key=key item=item}
        {assign var='form' value=$item}
        {assign var='key' value=$key}
        {assign var='type' value=$item.type}
        
        {include file='CRM/Leaveregistration/Page/LeaveRegistration.tpl'}
                
    {/foreach}  
    
{* fieldset *}
{elseif $type eq 'fieldset'}    
    <fieldset id="edit-{$key}" class="form-wrapper">
        <legend>
            <span class="fieldset-legend">{$form.title}</span>
        </legend>
        
        <div class="fieldset-wrapper">
            
            {if $form|is_array}
                {foreach from=$form key=key item=item}

                    {if isset($item.type)}
                        {assign var='form' value=$item}
                        {assign var='key' value=$key}
                        {assign var='type' value=$item.type}

                        {include file='CRM/Leaveregistration/Page/LeaveRegistration.tpl'}
                    {/if} 

                    {if $item|is_array}
                        {foreach from=$item key=key2 item=item2}

                            {if isset($item2.type)}
                                {assign var='form' value=$item2}
                                {assign var='key' value=$key2}

                                {assign var='type' value=$item2.type}

                                {include file='CRM/Leaveregistration/Page/LeaveRegistration.tpl'}
                            {/if}

                        {/foreach}
                    {/if}
                {/foreach}
            {/if}

        </div>
        
    </fieldset>

{elseif $type eq 'select'}
    <div id="{$form.attributes.id}" class="form-item form-type-select form-item-{$key}-select">
        
        {if '' neq $form.attributes.onchange}
            <select id="edit-{$key}-select" class="form-select" name="{$key}_select" onchange="{$form.attributes.onchange}">
                
        {elseif '' eq $form.attributes.onchange}
            <select id="edit-{$key}-select" class="form-select" name="{$key}_select">
        {/if}
                
            {foreach from=$form.options key=key item=item}
                {if $key eq $form.default_value}
                    <option selected="selected" value="{$key}">{$item}</option>
                {else}
                    <option value="{$key}">{$item}</option>
                {/if}
            {/foreach} 
            
        </select>
        
        {if $script eq 'year'}
            <script type="text/javascript"> var select_year = '{$form.attributes.id}'; </script>            
            {literal}
            <script type="text/javascript">          

                (function($) {

                    $(document).ready(function() {
                        
                        $('#' + select_year + ' select').bind('change', function () {
                            window.location = $(this).val();
                        });
                    });
               })(jQuery_leave_registration);
            </script>
           {/literal}
        {/if}
        
        {if $script eq 'months'}           
            <script type="text/javascript">                
                var select_months = '{$form.attributes.id}';
                var base_url = '{$base_url}';
                
                var error_id = 'leaveregistration_ajax';
                var error_platform = '{$error_platform}';
                var cid = '{$cid}'; 
                var user_cid = '{$user_cid}'; 
                var user_id = '{$user_id}'; 
                var years = '{$years}';
                var year = '{$year}';
                var months = '{$months}'; 
                var month = '{$month}'; 
                 
                var type = '{$type}'; 
                var _return = '{$return}';
                var select_text = "{ts escape='js'}Choose calendar or a month above !{/ts}";
            </script> 
            
            {literal}
            <script type="text/javascript">          

                (function($) {

                    $(document).ready(function() {
                        
                        $('#' + select_months + ' select').bind('change', function () {
                            $('#edit-calendar_year .fieldset-wrapper').append('<div class="loading"><span></span></div>');
                            
                            month = $(this).val();
                            switch(month)
                            {
                                case 'all':
                                    element = 'get_calendar_year'
                                    break;
                                case 'select':
                                    element = 'get_calendar_select'
                                    $('#edit-calendar_year .fieldset-wrapper').html('<span>' + select_text + '</span>');
                                    return false;
                                    break;
                                    
                                default:
                                    element = 'get_calendar_month';
                            }
        
                            $.post( 
                                base_url + 'civicrm/leaveregistration',
                                {
                                    'error_id': error_id,
                                    'error_platform': error_platform,
                                    'cid': cid,
                                    'user_cid': user_cid,
                                    'user_id': user_id,
                                    'years': years,
                                    'year': year,
                                    'months': months,
                                    'month': month,
                                    'type': type,
                                    'return': 'echo',
                                    'element': element
                                },
                                function(data) {
                                   $('#edit-calendar_year .fieldset-wrapper').html(data);
                                }
                             );
                                 
                        });
                    });
               })(jQuery_leave_registration);
            </script>
           {/literal}
        {/if}
        
        {if $script eq 'dephead_months'}           
            <script type="text/javascript">                
                var select_dephead_months = '{$form.attributes.id}';
                var base_url = '{$base_url}';
                
                var error_id = 'leaveregistration_ajax';
                var error_platform = '{$error_platform}';
                var cid = '{$cid}'; 
                var user_cid = '{$user_cid}'; 
                var user_id = '{$user_id}'; 
                var years = '{$years}';
                var year = '{$year}';
                var months = '{$months}'; 
                var month = '{$month}'; 
                 
                var type = '{$type}'; 
                var _return = '{$return}';
                var select_text = "{ts escape='js'}Choose calendar or a month above !{/ts}";
            </script> 
            
            {literal}
            <script type="text/javascript">          

                (function($) {

                    $(document).ready(function() {
                            
                        $('#' + select_dephead_months + ' select').bind('change', function () {
                            $('#edit-dephead_calendar_year .fieldset-wrapper').append('<div class="loading"><span></span></div>');
                            
                            month = $(this).val();
                            switch(month)
                            {
                                case 'all':
                                    element = 'get_dephead_calendar_year'
                                    break;
                                case 'select':
                                    element = 'get_dephead_calendar_select'
                                    $('#edit-dephead_calendar_year .fieldset-wrapper').html('<span>' + select_text + '</span>');
                                    return false;
                                    break;
                                    
                                default:
                                    element = 'get_dephead_calendar_month';
                            }
        
                            $.post( 
                                base_url + 'civicrm/leaveregistration',
                                {
                                    'error_id': error_id,
                                    'error_platform': error_platform,
                                    'cid': cid,
                                    'user_cid': user_cid,
                                    'user_id': user_id,
                                    'years': years,
                                    'year': year,
                                    'months': months,
                                    'month': month,
                                    'type': type,
                                    'return': 'echo',
                                    'element': element
                                },
                                function(data) {
                                   $('#edit-dephead_calendar_year .fieldset-wrapper').html(data);
                                }
                             );
                                 
                        });
                    });
               })(jQuery_leave_registration);
            </script>
           {/literal}
        {/if}
    </div>
  
{elseif $type eq 'checkbox'}
    <div id="{$form.attributes.id}" class="form-item form-type-checkbox form-item-{$key}-checkbox">
        <input id="edit-{$key}-checkbox" class="form-checkbox" type="checkbox" value="1" name="{$key}_select">
        <label class="option">{$form.title}</label>
    </div>
    
    {if $script eq 'show_colleagues'}  
        <script type="text/javascript">
            var checkbox_show_colleagues = '{$form.attributes.id}';
        </script> 

        {literal}
        <script type="text/javascript">          

            (function($) {

                $(document).ready(function() {
                    
                    $('#' + checkbox_show_colleagues + ' input').click(function() {                      
                        if ($(this).is(':checked')) {
                            $('#edit-calendar_year').addClass('colleagues');
                        }else {
                            $('#edit-calendar_year').removeClass('colleagues');
                        }
                    });
                });
            })(jQuery_leave_registration);
        </script>
        {/literal}
    {/if}
    
    {if $script eq 'dephead_show_colleagues'}   
        <script type="text/javascript">
            var checkbox_dephead_show_colleagues = '{$form.attributes.id}';
        </script> 

        {literal}
        <script type="text/javascript">          

            (function($) {

                $(document).ready(function() {
                    
                    $('#' + checkbox_dephead_show_colleagues + ' input').click(function() {                      
                        if ($(this).is(':checked')) {
                            $('#edit-dephead_calendar_year').addClass('colleagues');
                        }else {
                            $('#edit-dephead_calendar_year').removeClass('colleagues');
                        }
                    });
                });
            })(jQuery_leave_registration);
        </script>
        {/literal}
    {/if}
{elseif $type eq 'ul'} 
    
    {* prefix *}
    {if $form.prefix neq ''} 
        {$form.prefix}
    {/if} 
    
    {* ul *}
    <div class="item-list">
    <ul>
        
    {foreach from=$form.items key=key item=item}
        {if '' neq $item.class}
            <li class="{$item.class}">{$item.data}</li>

        {else}
             <li>{$item.data}</li>
             
        {/if}     
        
    {/foreach} 
    
    </ul> 
    </div>

    {* suffix *}
    {if $form.suffix neq ''} 
        {$form.suffix}
    {/if} 
   
{elseif $type eq 'table'} 
    <div id="{$form.attributes.id}" class="item-table">
        <table class="{$form.attributes.class}">
            <caption></caption>
            <thead>
                <tr>
                    {foreach from=$form.header key=key item=item}
                        <th>{$item.data}</th>
                    {/foreach} 
                </tr>
            </thead>
            <tbody>
                {foreach from=$form.rows key=key item=item}
                    <tr>
                        {foreach from=$item key=key2 item=item2}
                            {foreach from=$item2 key=key3 item=item3}
                                {if '' neq $item3.class and '' neq $item3.rel}
                                    <td class="{$item3.class}" rel="{$item3.rel}">{$item3.data}</td>
                                    
                                {elseif '' neq $item3.class and '' eq $item3.rel}
                                    <td class="{$item3.class}">{$item3.data}</td>
                                    
                                {elseif '' eq $item3.class and '' neq $item3.rel}
                                    <td rel="{$item3.rel}">{$item3.data}</td>
                                    
                                {elseif '' eq $item3.class and '' eq $item3.rel}
                                    <td>{$item3.data}</td>
                                {/if}
                            {/foreach} 
                        {/foreach} 
                    </tr>
                {/foreach} 
            </tbody>
        </table>
        
        {if $script eq 'calendar_year'}
            <div id="{$form.attributes.id}_mouseover" class="mouseover" style="display: none;">
                <div class="date"></div>
                <div class="holiday"></div>

                <div class="adjustments"></div>
                <div class="mom_dad_day"></div>
                <div class="leave"></div>
                
                <div class="colleagues"></div>
                <div class="rest"></div>
            </div>
                
            <script type="text/javascript"> var table_calendar_year = '{$form.attributes.id}'; </script>    
                
            {literal}
            <script type="text/javascript">          

                (function($) {

                    $(document).ready(function() {

                        $('#' + table_calendar_year + ' table td').each(function() {

                            $(this).mouseover(function() {

                                $('#' + table_calendar_year + '_mouseover').hide();

                                if($(this).attr('rel') && '' != $(this).attr('rel') ){

                                    var rel = unserialize(rawurldecode($(this).attr('rel')));

                                    var date = '';
                                    var holiday = '';
                                    var mom_dad_day = '';
                                    var leave = '';
                                    var adjustments = '';
                                    var colleagues = '';
                                    var rest = '';

                                    var i_colleagues = 0;
                                    var i_rest = 0;
                                    $.each( rel, function( key, value ) {            
                                        switch(key)
                                        {
                                            case 'day':
                                                date += value;
                                                break;
                                            case 'holiday':
                                                holiday += value;
                                                break;
                                            case 'mom_dad_day':
                                                mom_dad_day += value;
                                                break;
                                            case 'leave':
                                                leave += value;
                                                break;
                                            case 'adjustments':
                                                adjustments += value + ' ';
                                                break;
                                            case 'colleagues':
                                                colleagues += value + ' ';
                                                if(0 < i_colleagues){
                                                  colleagues += '<br />';
                                                }
                                                i_colleagues++;
                                                break;
                                            default:
                                              if(1 <= i_rest){
                                                rest += '<br />' + value;
                                              }else {
                                                rest += value;
                                              }
                                              i_rest++;
                                        }
                                    });

                                    $('#' + table_calendar_year + '_mouseover .date').html(date);
                                    $('#' + table_calendar_year + '_mouseover .holiday').html(holiday);
                                    $('#' + table_calendar_year + '_mouseover .mom_dad_day').html(mom_dad_day);
                                    $('#' + table_calendar_year + '_mouseover .leave').html(leave);
                                    $('#' + table_calendar_year + '_mouseover .adjustments').html(adjustments);
                                    $('#' + table_calendar_year + '_mouseover .colleagues').html(colleagues);
                                    $('#' + table_calendar_year + '_mouseover .rest').html(rest);
                                    
                                    var top = $(this).position().top - $('#' + table_calendar_year + '_mouseover').height() - $('#' + table_calendar_year + '_mouseover').css('padding-top');
                                    var top = $(this).position().top - $('#' + table_calendar_year + '_mouseover').height() - parseInt($('#' + table_calendar_year + '_mouseover').css('padding-top').slice(0,-2)) - parseInt($('#' + table_calendar_year + '_mouseover').css('padding-bottom').slice(0,-2));
                                    var left = $(this).position().left + 30;
                                   
                                    $('#' + table_calendar_year + '_mouseover').css('top', top);
                                    $('#' + table_calendar_year + '_mouseover').css('left', left);
                                    
                                    $('#' + table_calendar_year + '_mouseover').show();
                                }
                            });
                        });
                    });
               })(jQuery_leave_registration);

            </script>
           {/literal}
           
        {elseif $script eq 'dephead_calendar_year'}
            <div id="{$form.attributes.id}_mouseover" class="mouseover" style="display: none;">
                <div class="date"></div>
                <div class="holiday"></div>

                <div class="adjustments"></div>
                <div class="mom_dad_day"></div>
                <div class="leave"></div>
                
                <div class="colleagues"></div>
                <div class="rest"></div>
            </div>
            
            <script type="text/javascript"> var table_dephead_calendar_year = '{$form.attributes.id}'; </script>    
                
            {literal}
            <script type="text/javascript">          

                (function($) {

                    $(document).ready(function() {
                        
                        $('#' + table_dephead_calendar_year + ' table td').each(function() {

                            $(this).mouseover(function() {

                                $('#' + table_dephead_calendar_year + '_mouseover').hide();

                                if($(this).attr('rel') && '' != $(this).attr('rel') ){

                                    var rel = unserialize(rawurldecode($(this).attr('rel')));

                                    var date = '';
                                    var holiday = '';
                                    var mom_dad_day = '';
                                    var leave = '';
                                    var adjustments = '';
                                    var colleagues = '';
                                    var rest = '';

                                    var i_colleagues = 0;
                                    var i_rest = 0;
                                    $.each( rel, function( key, value ) {            
                                        switch(key)
                                        {
                                            case 'day':
                                                date += value;
                                                break;
                                            case 'holiday':
                                                holiday += value;
                                                break;
                                            case 'mom_dad_day':
                                                mom_dad_day += value;
                                                break;
                                            case 'leave':
                                                leave += value;
                                                break;
                                            case 'adjustments':
                                                adjustments += value + ' ';
                                                break;
                                            case 'colleagues':
                                                colleagues += value + ' ';
                                                if(0 < i_colleagues){
                                                  colleagues += '<br />';
                                                }
                                                i_colleagues++;
                                                break;
                                            default:
                                              if(1 <= i_rest){
                                                rest += '<br />' + value;
                                              }else {
                                                rest += value;
                                              }
                                              i_rest++;
                                        }
                                    });
                                    
                                    $('#' + table_dephead_calendar_year + '_mouseover .date').html(date);
                                    $('#' + table_dephead_calendar_year + '_mouseover .holiday').html(holiday);
                                    $('#' + table_dephead_calendar_year + '_mouseover .mom_dad_day').html(mom_dad_day);
                                    $('#' + table_dephead_calendar_year + '_mouseover .leave').html(leave);
                                    $('#' + table_dephead_calendar_year + '_mouseover .adjustments').html(adjustments);
                                    $('#' + table_dephead_calendar_year + '_mouseover .colleagues').html(colleagues);
                                    $('#' + table_dephead_calendar_year + '_mouseover .rest').html(rest);
                                    
                                    var top = $(this).position().top - $('#' + table_dephead_calendar_year + '_mouseover').height() - $('#' + table_dephead_calendar_year + '_mouseover').css('padding-top');
                                    var top = $(this).position().top - $('#' + table_dephead_calendar_year + '_mouseover').height() - parseInt($('#' + table_dephead_calendar_year + '_mouseover').css('padding-top').slice(0,-2)) - parseInt($('#' + table_dephead_calendar_year + '_mouseover').css('padding-bottom').slice(0,-2));
                                    var left = $(this).position().left + 30;
                                   
                                    $('#' + table_dephead_calendar_year + '_mouseover').css('top', top);
                                    $('#' + table_dephead_calendar_year + '_mouseover').css('left', left);
                                    
                                    $('#' + table_dephead_calendar_year + '_mouseover').show();
                                }
                            });
                        });
                    });
               })(jQuery_leave_registration);

            </script>
           {/literal}
           
        {elseif $script eq 'request'}
            <div id="{$form.attributes.id}_mouseout" class="mouseout" style="display: none;">
            </div>
                
            <script type="text/javascript"> var table_request = '{$form.attributes.id}'; </script>    
                
            {literal}
            <script type="text/javascript">          

                (function($) {

                    $(document).ready(function() {
                        
                        $('#' + table_request + ' table td.reason').each(function() {
                            $(this).mouseout(function() {
                                $('#' + table_request + '_mouseout').html($(this).attr(''));
                                $('#' + table_request + '_mouseout').hide();
                            });
                            
                            $(this).mouseover(function() {
                                
                                if ($(this).attr('rel') !== undefined && '' != $(this).attr('rel')) {
                                
                                    var top = $(this).position().top - $('#' + table_request + '_mouseout').height() - 10;
                                    $('#' + table_request + '_mouseout').css('top', top);                                    

                                    $('#' + table_request + '_mouseout').html($(this).attr('rel'));
                                    $('#' + table_request + '_mouseout').show();
                                }
                                
                           });
                           
                        });
                    });
                })(jQuery_leave_registration);
                
            </script>
           {/literal}
        
        {elseif $script eq 'department_head_request'}
            <div id="{$form.attributes.id}_mouseout" class="mouseout" style="display: none;">
            </div>
                
            <script type="text/javascript"> var table_department_head_request = '{$form.attributes.id}'; </script>    
                
            {literal}
            <script type="text/javascript">          

                (function($) {

                    $(document).ready(function() {
                        
                        $('#' + table_department_head_request + ' table td.reason').each(function() {
                            $(this).mouseout(function() {
                                $('#' + table_department_head_request + '_mouseout').html($(this).attr(''));
                                $('#' + table_department_head_request + '_mouseout').hide();
                            });
                            
                            $(this).mouseover(function() {
                                
                                if ($(this).attr('rel') !== undefined && '' != $(this).attr('rel')) {
                                
                                    var top = $(this).position().top - $('#' + table_department_head_request + '_mouseout').height() - 10;
                                    $('#' + table_department_head_request + '_mouseout').css('top', top);                                    

                                    $('#' + table_department_head_request + '_mouseout').html($(this).attr('rel'));
                                    $('#' + table_department_head_request + '_mouseout').show();
                                }
                                
                           });
                           
                        });
                    });
                })(jQuery_leave_registration);
                
            </script>
           {/literal}
        {/if}        
    </div>

{elseif $type eq 'link'}
    <a href="{$form.href}">{$form.title}</a>

{elseif $type eq 'text'}
  <span>{$form.text}</span>
    
{elseif $type eq 'script'} 
    
    {if $action eq 'js'}
        <script src="{$extension_url}com.bosgoed.leaveregistration/js/jquery-1.8.2.js"></script>
        <script src="{$extension_url}com.bosgoed.leaveregistration/js/jquery-noconfilct.js"></script>
        
        <script src="{$extension_url}com.bosgoed.leaveregistration/js/utf8_decode.js"></script>
        <script src="{$extension_url}com.bosgoed.leaveregistration/js/rawurldecode.js"></script>
        <script src="{$extension_url}com.bosgoed.leaveregistration/js/rawurlencode.js"></script>
        <script src="{$extension_url}com.bosgoed.leaveregistration/js/serialize.js"></script>
        <script src="{$extension_url}com.bosgoed.leaveregistration/js/unserialize.js"></script>
        
    {elseif $action eq 'css'}
        <link rel="stylesheet" type="text/css" href="{$extension_url}com.bosgoed.leaveregistration/css/style.css">
        
    {elseif $action eq 'calendar_year'}
        <div id="calendar_year_item">
            <div class="date"></div>
            <div class="leave"></div>
            
            <div class="colleagues"></div>
        </div>
        {literal}
        <script type="text/javascript">

            
        </script>
       {/literal}
    {elseif $action eq 'script'}
    
    {/if}
    
{elseif $type eq 'error'}
    {$form.error}
{/if}