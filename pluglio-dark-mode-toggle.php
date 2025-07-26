<?php
/**
 * Plugin Name: Pluglio Dark Mode Toggle
 * Plugin URI: https://www.jezweb.com.au
 * Description: Add a beautiful dark/light mode toggle to your WordPress website with smooth transitions and persistent user preferences.
 * Version: 1.0.0
 * Author: Jezweb
 * Author URI: https://www.jezweb.com.au
 * License: GPL v2 or later
 * Text Domain: pluglio-dark-mode-toggle
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('DLT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DLT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('DLT_PLUGIN_VERSION', '1.0.0');

// Load dependencies
require_once DLT_PLUGIN_PATH . 'includes/class-dlt-css-handler.php';
require_once DLT_PLUGIN_PATH . 'includes/class-dlt-javascript-handler.php';
require_once DLT_PLUGIN_PATH . 'includes/class-dlt-settings-manager.php';
require_once DLT_PLUGIN_PATH . 'includes/class-dlt-admin-ui.php';
require_once DLT_PLUGIN_PATH . 'includes/class-dlt-frontend-ui.php';

class DarkModeToggle {
    
    private $plugin_name = 'pluglio-dark-mode-toggle';
    private $version = DLT_PLUGIN_VERSION;
    private $css_handler;
    private $js_handler;
    private $settings_manager;
    private $admin_ui;
    private $frontend_ui;
    
    public function __construct() {
        $this->load_dependencies();
        add_action('init', array($this, 'init'));
    }
    
    private function load_dependencies() {
        $this->settings_manager = new DLT_Settings_Manager();
        $this->css_handler = new DLT_CSS_Handler($this->settings_manager);
        $this->js_handler = new DLT_JavaScript_Handler($this->settings_manager);
        $this->admin_ui = new DLT_Admin_UI($this->settings_manager);
        $this->frontend_ui = new DLT_Frontend_UI($this->settings_manager);
    }
    
    public function init() {
        // Hook into WordPress
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this->frontend_ui, 'render_toggle_button'));
        
        // Admin hooks
        if (is_admin()) {
            add_action('admin_menu', array($this->admin_ui, 'add_admin_menu'));
            add_action('admin_init', array($this->admin_ui, 'admin_init'));
        }
        
        // Plugin activation/deactivation hooks are registered outside the class
    }
    
    public function enqueue_scripts() {
        // Register and enqueue a dummy style to attach our inline CSS
        wp_register_style('dlt-style', false);
        wp_enqueue_style('dlt-style');
        wp_add_inline_style('dlt-style', $this->css_handler->get_css());
        
        // Register and enqueue a dummy script to attach our inline JavaScript
        wp_register_script('dlt-script', false, array(), $this->version, true);
        wp_enqueue_script('dlt-script');
        wp_add_inline_script('dlt-script', $this->js_handler->get_javascript());
    }
    
    
    public function activate() {
        $this->settings_manager->set_defaults();
    }
    
    public function deactivate() {
        // Clean up can be handled by settings manager if needed
    }
}

// Initialize the plugin
$dark_mode_toggle = new DarkModeToggle();

// Register activation/deactivation hooks
register_activation_hook(__FILE__, array($dark_mode_toggle, 'activate'));
register_deactivation_hook(__FILE__, array($dark_mode_toggle, 'deactivate'));

?>