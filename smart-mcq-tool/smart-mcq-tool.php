<?php
/*
Plugin Name: Smart MCQ Tool V3
Description: High performance MCQ practice platform
Version: 3.0.3
Author: Smart MCQ
*/

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('SMPP_PATH')) {
    define('SMPP_PATH', plugin_dir_path(__FILE__));
}
if (!defined('SMPP_URL')) {
    define('SMPP_URL', plugin_dir_url(__FILE__));
}

require_once SMPP_PATH . 'bootstrap.php';
