<?php

// Legacy loader kept for backward compatibility when this folder is copied standalone.
// Canonical plugin entry for repository ZIP installs: /smart-mcq-tool.php

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
