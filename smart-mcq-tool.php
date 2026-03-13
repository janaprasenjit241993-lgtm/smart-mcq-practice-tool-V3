<?php
/*
Plugin Name: Smart MCQ Tool V3
Description: High performance MCQ practice platform
Version: 3.0.2
Author: Smart MCQ
*/

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('SMPP_PATH')) {
    define('SMPP_PATH', plugin_dir_path(__FILE__) . 'smart-mcq-tool/');
}

if (!defined('SMPP_URL')) {
    define('SMPP_URL', plugin_dir_url(__FILE__) . 'smart-mcq-tool/');
}

require_once SMPP_PATH . 'includes/csv-loader.php';
require_once SMPP_PATH . 'includes/question-indexer.php';
require_once SMPP_PATH . 'includes/validator.php';
require_once SMPP_PATH . 'includes/question-cache.php';
require_once SMPP_PATH . 'includes/ai-engine.php';
require_once SMPP_PATH . 'includes/explanation-generator.php';
require_once SMPP_PATH . 'includes/duplicate-detector.php';
require_once SMPP_PATH . 'includes/difficulty-tagger.php';
require_once SMPP_PATH . 'includes/dataset-statistics.php';
require_once SMPP_PATH . 'includes/latex-formatter.php';
require_once SMPP_PATH . 'includes/reaction-formatter.php';
require_once SMPP_PATH . 'includes/table-renderer.php';
require_once SMPP_PATH . 'includes/ajax-handler.php';

if (!class_exists('SmartMCQTool')) {
    class SmartMCQTool {
        public function __construct() {
            add_shortcode('smart_mcq', [$this, 'render_ui']);
            add_action('admin_menu', [$this, 'admin_menu']);
            add_action('wp_enqueue_scripts', [$this, 'assets']);
        }

        public function assets() {
            wp_enqueue_style('smpp-style', SMPP_URL . 'assets/css/style.css', [], '3.0.2');

            wp_enqueue_script('smpp-script', SMPP_URL . 'assets/js/script.js', ['jquery'], '3.0.2', true);
            wp_localize_script('smpp-script', 'smppAjax', [
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('smpp_nonce'),
            ]);

            wp_enqueue_script(
                'mathjax',
                'https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js',
                [],
                null,
                true
            );
        }

        public function admin_menu() {
            add_menu_page('MCQ Dataset', 'MCQ Dataset', 'manage_options', 'smpp-dataset', [$this, 'dataset_page']);
        }

        public function dataset_page() {
            include SMPP_PATH . 'admin/dataset-manager.php';
        }

        public function render_ui() {
            ob_start();
            include SMPP_PATH . 'templates/mcq-ui.php';
            return ob_get_clean();
        }
    }
}

new SmartMCQTool();
