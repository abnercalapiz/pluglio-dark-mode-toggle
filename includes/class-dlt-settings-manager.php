<?php
/**
 * Settings Manager for Dark Light Toggle Plugin
 *
 * @package DarkModeToggle
 * @author  Jezweb
 * @since   1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class DLT_Settings_Manager {
    
    private $option_prefix = 'dlt_';
    
    private $defaults = array(
        'position' => 'top-right',
        'show_text' => 'yes',
        'enable_auto_detect' => 'yes',
        'dark_link_color' => '#4a9eff',
        'dark_button_bg_color' => '#4a9eff',
        'dark_mode_logo' => ''
    );
    
    public function get_option($option_name, $default = null) {
        if ($default === null && isset($this->defaults[$option_name])) {
            $default = $this->defaults[$option_name];
        }
        
        return get_option($this->option_prefix . $option_name, $default);
    }
    
    public function update_option($option_name, $value) {
        return update_option($this->option_prefix . $option_name, $value);
    }
    
    public function delete_option($option_name) {
        return delete_option($this->option_prefix . $option_name);
    }
    
    public function get_all_options() {
        $options = array();
        
        foreach ($this->defaults as $key => $default) {
            $options[$key] = $this->get_option($key);
        }
        
        return $options;
    }
    
    public function set_defaults() {
        foreach ($this->defaults as $key => $value) {
            add_option($this->option_prefix . $key, $value);
        }
    }
    
    public function get_option_name($option) {
        return $this->option_prefix . $option;
    }
    
    public function validate_position($value) {
        $valid_positions = array('top-left', 'top-right', 'bottom-left', 'bottom-right');
        return in_array($value, $valid_positions) ? $value : $this->defaults['position'];
    }
    
    public function validate_yes_no($value) {
        return in_array($value, array('yes', 'no')) ? $value : 'yes';
    }
    
    public function validate_color($value) {
        // Accept hex colors with or without #
        $value = ltrim($value, '#');
        
        // Validate hex color format
        if (preg_match('/^[0-9A-Fa-f]{6}$/', $value)) {
            return '#' . $value;
        }
        
        // Return default if invalid
        return $this->defaults['dark_link_color'];
    }
}