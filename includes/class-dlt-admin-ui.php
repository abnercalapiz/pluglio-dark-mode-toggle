<?php
/**
 * Admin UI Handler for Dark Light Toggle Plugin
 *
 * @package DarkModeToggle
 * @author  Jezweb
 * @since   1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class DLT_Admin_UI {
    
    private $settings_manager;
    private $page_slug = 'pluglio-dark-mode-toggle';
    
    public function __construct($settings_manager) {
        $this->settings_manager = $settings_manager;
    }
    
    public function add_admin_menu() {
        add_options_page(
            __('Pluglio Dark Mode Toggle Settings', 'pluglio-dark-mode-toggle'),
            __('Pluglio Dark Mode', 'pluglio-dark-mode-toggle'),
            'manage_options',
            $this->page_slug,
            array($this, 'render_admin_page')
        );
    }
    
    public function admin_init() {
        // Register settings
        register_setting('dlt_settings_group', $this->settings_manager->get_option_name('position'), 
            array($this, 'sanitize_position'));
        register_setting('dlt_settings_group', $this->settings_manager->get_option_name('show_text'), 
            array($this, 'sanitize_yes_no'));
        register_setting('dlt_settings_group', $this->settings_manager->get_option_name('enable_auto_detect'), 
            array($this, 'sanitize_yes_no'));
        register_setting('dlt_settings_group', $this->settings_manager->get_option_name('dark_link_color'), 
            array($this, 'sanitize_color'));
        register_setting('dlt_settings_group', $this->settings_manager->get_option_name('dark_button_bg_color'), 
            array($this, 'sanitize_color'));
        
        // Add settings section
        add_settings_section(
            'dlt_main_section', 
            __('Toggle Settings', 'pluglio-dark-mode-toggle'), 
            array($this, 'render_section_description'), 
            'dlt_settings_page'
        );
        
        // Add settings fields
        $this->add_settings_fields();
    }
    
    private function add_settings_fields() {
        add_settings_field(
            'dlt_position',
            __('Toggle Position', 'pluglio-dark-mode-toggle'),
            array($this, 'render_position_field'),
            'dlt_settings_page',
            'dlt_main_section'
        );
        
        add_settings_field(
            'dlt_show_text',
            __('Show Text Labels', 'pluglio-dark-mode-toggle'),
            array($this, 'render_show_text_field'),
            'dlt_settings_page',
            'dlt_main_section'
        );
        
        add_settings_field(
            'dlt_enable_auto_detect',
            __('Auto-detect System Preference', 'pluglio-dark-mode-toggle'),
            array($this, 'render_auto_detect_field'),
            'dlt_settings_page',
            'dlt_main_section'
        );
        
        add_settings_field(
            'dlt_dark_link_color',
            __('Dark Mode Link Color', 'pluglio-dark-mode-toggle'),
            array($this, 'render_dark_link_color_field'),
            'dlt_settings_page',
            'dlt_main_section'
        );
        
        add_settings_field(
            'dlt_dark_button_bg_color',
            __('Dark Mode Button Background', 'pluglio-dark-mode-toggle'),
            array($this, 'render_dark_button_bg_color_field'),
            'dlt_settings_page',
            'dlt_main_section'
        );
    }
    
    public function render_admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <?php settings_errors('dlt_settings'); ?>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('dlt_settings_group');
                do_settings_sections('dlt_settings_page');
                submit_button();
                ?>
            </form>
            
            <?php $this->render_help_section(); ?>
        </div>
        <?php
    }
    
    private function render_help_section() {
        ?>
        <div class="card" style="margin-top: 20px; max-width: 800px;">
            <h2><?php _e('How to Use', 'pluglio-dark-mode-toggle'); ?></h2>
            <p><?php _e('The dark/light mode toggle is automatically added to your website. You can customize its appearance and behavior using the settings above.', 'pluglio-dark-mode-toggle'); ?></p>
            
            <h3><?php _e('Features:', 'pluglio-dark-mode-toggle'); ?></h3>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><?php _e('Automatic theme persistence across sessions', 'pluglio-dark-mode-toggle'); ?></li>
                <li><?php _e('Smooth CSS transitions', 'pluglio-dark-mode-toggle'); ?></li>
                <li><?php _e('System preference detection', 'pluglio-dark-mode-toggle'); ?></li>
                <li><?php _e('Mobile responsive design', 'pluglio-dark-mode-toggle'); ?></li>
                <li><?php _e('Accessibility support', 'pluglio-dark-mode-toggle'); ?></li>
            </ul>
            
            <h3><?php _e('JavaScript API:', 'pluglio-dark-mode-toggle'); ?></h3>
            <p><?php _e('You can interact with the toggle programmatically:', 'pluglio-dark-mode-toggle'); ?></p>
            <pre style="background: #f0f0f0; padding: 10px; border-radius: 4px;">
// Toggle theme
window.DarkLightToggle.toggle();

// Set specific theme
window.DarkLightToggle.setTheme('dark');

// Get current theme
const theme = window.DarkLightToggle.getTheme();

// Listen for theme changes
window.addEventListener('dltThemeChanged', function(e) {
    console.log('Theme changed to:', e.detail.theme);
});</pre>
            
            <p style="margin-top: 20px;">
                <strong><?php _e('Need help?', 'pluglio-dark-mode-toggle'); ?></strong> 
                <?php printf(
                    __('Visit %s for support and documentation.', 'pluglio-dark-mode-toggle'),
                    '<a href="https://www.jezweb.com.au" target="_blank">Jezweb</a>'
                ); ?>
            </p>
        </div>
        <?php
    }
    
    public function render_section_description() {
        echo '<p>' . __('Configure the appearance and behavior of the dark mode toggle button.', 'pluglio-dark-mode-toggle') . '</p>';
    }
    
    public function render_position_field() {
        $option_name = $this->settings_manager->get_option_name('position');
        $value = $this->settings_manager->get_option('position');
        ?>
        <select name="<?php echo esc_attr($option_name); ?>" id="dlt_position">
            <option value="top-left" <?php selected($value, 'top-left'); ?>>
                <?php _e('Top Left', 'pluglio-dark-mode-toggle'); ?>
            </option>
            <option value="top-right" <?php selected($value, 'top-right'); ?>>
                <?php _e('Top Right', 'pluglio-dark-mode-toggle'); ?>
            </option>
            <option value="bottom-left" <?php selected($value, 'bottom-left'); ?>>
                <?php _e('Bottom Left', 'pluglio-dark-mode-toggle'); ?>
            </option>
            <option value="bottom-right" <?php selected($value, 'bottom-right'); ?>>
                <?php _e('Bottom Right', 'pluglio-dark-mode-toggle'); ?>
            </option>
        </select>
        <p class="description"><?php _e('Choose where the toggle button appears on your site.', 'pluglio-dark-mode-toggle'); ?></p>
        <?php
    }
    
    public function render_show_text_field() {
        $option_name = $this->settings_manager->get_option_name('show_text');
        $value = $this->settings_manager->get_option('show_text');
        ?>
        <fieldset>
            <label>
                <input type="radio" name="<?php echo esc_attr($option_name); ?>" 
                       value="yes" <?php checked($value, 'yes'); ?>>
                <?php _e('Yes', 'pluglio-dark-mode-toggle'); ?>
            </label>
            <br>
            <label>
                <input type="radio" name="<?php echo esc_attr($option_name); ?>" 
                       value="no" <?php checked($value, 'no'); ?>>
                <?php _e('No (Icons only)', 'pluglio-dark-mode-toggle'); ?>
            </label>
        </fieldset>
        <p class="description"><?php _e('Show "Dark" and "Light" text labels alongside icons.', 'pluglio-dark-mode-toggle'); ?></p>
        <?php
    }
    
    public function render_auto_detect_field() {
        $option_name = $this->settings_manager->get_option_name('enable_auto_detect');
        $value = $this->settings_manager->get_option('enable_auto_detect');
        ?>
        <fieldset>
            <label>
                <input type="radio" name="<?php echo esc_attr($option_name); ?>" 
                       value="yes" <?php checked($value, 'yes'); ?>>
                <?php _e('Yes', 'pluglio-dark-mode-toggle'); ?>
            </label>
            <br>
            <label>
                <input type="radio" name="<?php echo esc_attr($option_name); ?>" 
                       value="no" <?php checked($value, 'no'); ?>>
                <?php _e('No', 'pluglio-dark-mode-toggle'); ?>
            </label>
        </fieldset>
        <p class="description">
            <?php _e('When enabled, the plugin will respect the user\'s system preference if they haven\'t manually selected a theme.', 'pluglio-dark-mode-toggle'); ?>
        </p>
        <?php
    }
    
    public function render_dark_link_color_field() {
        $option_name = $this->settings_manager->get_option_name('dark_link_color');
        $value = $this->settings_manager->get_option('dark_link_color');
        ?>
        <input type="color" name="<?php echo esc_attr($option_name); ?>" 
               value="<?php echo esc_attr($value); ?>" 
               id="dlt_dark_link_color">
        <input type="text" name="<?php echo esc_attr($option_name); ?>_text" 
               value="<?php echo esc_attr($value); ?>" 
               id="dlt_dark_link_color_text"
               style="margin-left: 10px; width: 100px;"
               pattern="^#[0-9A-Fa-f]{6}$"
               maxlength="7">
        <p class="description">
            <?php _e('Choose the color for links in dark mode. Default: #4a9eff', 'pluglio-dark-mode-toggle'); ?>
        </p>
        <?php
    }
    
    public function render_dark_button_bg_color_field() {
        $option_name = $this->settings_manager->get_option_name('dark_button_bg_color');
        $value = $this->settings_manager->get_option('dark_button_bg_color');
        ?>
        <input type="color" name="<?php echo esc_attr($option_name); ?>" 
               value="<?php echo esc_attr($value); ?>" 
               id="dlt_dark_button_bg_color">
        <input type="text" name="<?php echo esc_attr($option_name); ?>_text" 
               value="<?php echo esc_attr($value); ?>" 
               id="dlt_dark_button_bg_color_text"
               style="margin-left: 10px; width: 100px;"
               pattern="^#[0-9A-Fa-f]{6}$"
               maxlength="7">
        <p class="description">
            <?php _e('Choose the background color for Elementor buttons in dark mode. Default: #4a9eff', 'pluglio-dark-mode-toggle'); ?>
        </p>
        <script>
            // Sync color pickers and text inputs
            document.addEventListener('DOMContentLoaded', function() {
                // Link color sync
                const linkColorPicker = document.getElementById('dlt_dark_link_color');
                const linkTextInput = document.getElementById('dlt_dark_link_color_text');
                
                if (linkColorPicker && linkTextInput) {
                    linkColorPicker.addEventListener('change', function() {
                        linkTextInput.value = this.value;
                    });
                    
                    linkTextInput.addEventListener('input', function() {
                        if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                            linkColorPicker.value = this.value;
                        }
                    });
                }
                
                // Button background color sync
                const buttonColorPicker = document.getElementById('dlt_dark_button_bg_color');
                const buttonTextInput = document.getElementById('dlt_dark_button_bg_color_text');
                
                if (buttonColorPicker && buttonTextInput) {
                    buttonColorPicker.addEventListener('change', function() {
                        buttonTextInput.value = this.value;
                    });
                    
                    buttonTextInput.addEventListener('input', function() {
                        if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                            buttonColorPicker.value = this.value;
                        }
                    });
                }
                
                // Sync name attributes on form submit
                const form = document.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function() {
                        if (linkTextInput) linkTextInput.name = '';
                        if (buttonTextInput) buttonTextInput.name = '';
                    });
                }
            });
        </script>
        <?php
    }
    
    public function sanitize_position($value) {
        return $this->settings_manager->validate_position($value);
    }
    
    public function sanitize_yes_no($value) {
        return $this->settings_manager->validate_yes_no($value);
    }
    
    public function sanitize_color($value) {
        return $this->settings_manager->validate_color($value);
    }
}