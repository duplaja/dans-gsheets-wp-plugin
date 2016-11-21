<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
$option_name = 'gsheets_api_key';
$option_name2 = 'gsheets_sheetsids';

delete_option($option_name);
delete_option($option_name2);

// for site options in Multisite
delete_site_option($option_name);
delete_site_option($option_name2);
