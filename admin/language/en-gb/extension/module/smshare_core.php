<?php

$_['heading_title']    = 'ProWebTec 》Smshare 》Core extension';

$_['text_module']      = 'Modules';
$_['text_success']     = 'Success: You have modified module ' . $_['heading_title'];
$_['text_home']        = 'Home';
$_['text_yes']         = 'Yes';
$_['text_no']          = 'No';
$_['button_save']          = 'Save and exit';
$_['button_save_and_stay']          = 'Save and stay';

$_['error_permission'] = 'Warning: You do not have permission to modify module ' . $_['heading_title'];

$_['text_smartphone_access_token'] = "Access token";
$_['text_smartphone_uuid']         = "Smartphone uuid";


$_['smshare_entry_notify_customer_by_sms_on_registration'] 		= 'Notify customer on registration' ;
$_['smshare_entry_notify_customer_by_sms_on_registration_help'] = 'Send a SMS to customer once she completes the registration.';


$_['smshare_entry_cstmr_reg_available_vars']               = 'Currently, only <b>{firstname}</b>, <b>{lastname}</b>, <b>{phonenumber}</b> and <b>{password}</b> are available for substitution';

$_['smshare_entry_notify_customer_by_sms_on_checkout']     = 'Notify customer for new order' ;
$_['smshare_entry_notify_customer_by_sms_on_checkout_help']= 'Send a SMS to customer once she completes the online transaction.';

$_['smshare_entry_ntfy_admin_by_sms_on_reg'] 		       = 'Notify store owner on registration' ;
$_['smshare_entry_ntfy_admin_by_sms_on_reg_help']	       = 'Send a SMS to store owner when a customer completes the registration.';


$_['smshare_entry_notify_admin_by_sms_on_checkout']        = 'Notify store owner for new order:';
$_['smshare_entry_notify_admin_by_sms_on_checkout_help']   = 'Send a SMS to the store owner when a new order is created';


$_['smshare_entry_notify_extra_by_sms_on_checkout']        = 'Additional Alert phone numbers:' ;
$_['smshare_entry_notify_extra_by_sms_on_checkout_help']   = 'Any additional phone numbers you want to receive the alert by sms (comma separated). ' .
    '<br />If filled then SMS will be sent even if you disable «Notify store owner for new order»';
