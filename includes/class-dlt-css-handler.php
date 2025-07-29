<?php
/**
 * CSS Handler for Dark Light Toggle Plugin
 *
 * @package DarkModeToggle
 * @author  Jezweb
 * @since   1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class DLT_CSS_Handler {
    
    private $settings_manager;
    
    public function __construct($settings_manager) {
        $this->settings_manager = $settings_manager;
    }
    
    public function get_css() {
        $position = $this->settings_manager->get_option('position');
        $show_text = $this->settings_manager->get_option('show_text');
        $dark_link_color = $this->settings_manager->get_option('dark_link_color');
        $dark_button_bg_color = $this->settings_manager->get_option('dark_button_bg_color');
        
        $position_styles = $this->get_position_styles($position);
        $text_display = ($show_text === 'no') ? 'display: none;' : '';
        
        return $this->generate_css($position_styles, $text_display, $dark_link_color, $dark_button_bg_color);
    }
    
    private function get_position_styles($position) {
        $positions = array(
            'top-left'     => 'top: 20px; left: 20px;',
            'top-right'    => 'top: 20px; right: 20px;',
            'bottom-left'  => 'bottom: 20px; left: 20px;',
            'bottom-right' => 'bottom: 20px; right: 20px;'
        );
        
        return isset($positions[$position]) ? $positions[$position] : $positions['top-right'];
    }
    
    private function generate_css($position_styles, $text_display, $dark_link_color, $dark_button_bg_color) {
        return "
        /* Dark Light Mode Toggle Plugin Styles */
        :root {
            --dlt-bg-color: #ffffff;
            --dlt-text-color: #333333;
            --dlt-header-bg: #f8f9fa;
            --dlt-border-color: #e0e0e0;
            --dlt-card-bg: #ffffff;
            --dlt-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            --dlt-link-color: #007cba;
            --dlt-button-bg: #007cba;
            --dlt-button-text: #ffffff;
        }

        [data-theme='dark'] {
            --dlt-bg-color: #2d2d2d;
            --dlt-text-color: #e0e0e0;
            --dlt-header-bg: #2d2d2d;
            --dlt-border-color: #404040;
            --dlt-card-bg: #2d2d2d;
            --dlt-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            --dlt-link-color: " . $dark_link_color . ";
            --dlt-button-bg: " . $dark_button_bg_color . ";
            --dlt-button-text: #ffffff;
        }
        /* Only apply color overrides in dark mode */
        [data-theme='dark'] body {
            background-color: var(--dlt-bg-color) !important;
            color: var(--dlt-text-color) !important;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        [data-theme='dark'] header,
        [data-theme='dark'] .site-header,
        [data-theme='dark'] .main-navigation,
        [data-theme='dark'] nav {
            background-color: var(--dlt-header-bg) !important;
            border-color: var(--dlt-border-color) !important;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        [data-theme='dark'] .card,
        [data-theme='dark'] .post,
        [data-theme='dark'] article,
        [data-theme='dark'] .widget,
        [data-theme='dark'] .wp-block-group,
        [data-theme='dark'] .wp-block-columns {
            background-color: var(--dlt-card-bg) !important;
            border-color: var(--dlt-border-color) !important;
            color: var(--dlt-text-color) !important;
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        [data-theme='dark'] a {
            color: var(--dlt-link-color) !important;
            transition: color 0.3s ease;
        }

        [data-theme='dark'] .wp-site-blocks,
        [data-theme='dark'] #main,
        [data-theme='dark'] .entry-content,
        [data-theme='dark'] .post-content {
            background-color: var(--dlt-bg-color) !important;
            color: var(--dlt-text-color) !important;
        }

        [data-theme='dark'] footer,
        [data-theme='dark'] .site-footer {
            background-color: var(--dlt-header-bg) !important;
            border-color: var(--dlt-border-color) !important;
            color: var(--dlt-text-color) !important;
        }

        /* Elementor Support - only in dark mode */
        .elementor-section, 
        .elementor-widget-container,
        .elementor-container,
        .e-container,
        .e-con,
        .e-con-inner,
        .e-con-boxed {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        [data-theme='dark'] .elementor-section,
        [data-theme='dark'] .elementor-container,
        [data-theme='dark'] .e-container,
        [data-theme='dark'] .e-con,
        [data-theme='dark'] .e-con-inner,
        [data-theme='dark'] .e-con-boxed,
        [data-theme='dark'] .elementor-inner-section,
        [data-theme='dark'] .elementor-section.elementor-inner-section,
        [data-theme='dark'] .elementor-column .elementor-widget-wrap {
            background-color: var(--dlt-card-bg) !important;
            color: var(--dlt-text-color) !important;
			border: none !important;
        }
		[data-theme='dark'] .elementor-shape .elementor-shape-fill {
			fill: transparent !important;
		}
		[data-theme='dark'] .dlt-toggle:hover {
			color: #000000 !important;
			background: #ffffff !important;
		}
		[data-theme='dark'] .dlt-toggle * {
			color: #000000 !important;
			fill: #000000 !important;
		}
        [data-theme='dark'] .elementor-section[data-settings*=\"background_background\"]:not([data-settings*=\"gradient\"]),
        [data-theme='dark'] .e-con[data-settings*=\"background_background\"]:not([data-settings*=\"gradient\"]) {
            background-color: var(--dlt-card-bg) !important;
        }

        [data-theme='dark'] .elementor-widget-heading .elementor-heading-title,
        [data-theme='dark'] .elementor-widget-text-editor,
        [data-theme='dark'] .elementor-widget-text-editor .elementor-text-editor,
        [data-theme='dark'] .elementor-widget p,
        [data-theme='dark'] .elementor-widget h1,
        [data-theme='dark'] .elementor-widget h2,
        [data-theme='dark'] .elementor-widget h3,
        [data-theme='dark'] .elementor-widget h4,
        [data-theme='dark'] .elementor-widget h5,
        [data-theme='dark'] .elementor-widget h6,
        [data-theme='dark'] .e-con .elementor-widget-heading .elementor-heading-title,
        [data-theme='dark'] .e-con .elementor-widget-text-editor {
            color: var(--dlt-text-color) !important;
        }

        [data-theme='dark'] .elementor-widget-icon .elementor-icon,
        [data-theme='dark'] .elementor-widget-icon-box .elementor-icon-box-icon,
        [data-theme='dark'] .elementor-widget-icon-list .elementor-icon-list-icon {
            color: var(--dlt-text-color) !important;
            fill: var(--dlt-text-color) !important;
        }

        [data-theme='dark'] .elementor-widget-button .elementor-button,
        [data-theme='dark'] a.elementor-button {
            background-color: var(--dlt-button-bg) !important;
            color: var(--dlt-button-text) !important;
            border-color: var(--dlt-border-color) !important;
        }

        [data-theme='dark'] .elementor-widget-divider .elementor-divider-separator {
            border-color: var(--dlt-border-color) !important;
        }

        /* Elementor forms - only in dark mode */
        [data-theme='dark'] .elementor-field,
        [data-theme='dark'] .elementor-field-textual {
            background-color: var(--dlt-card-bg) !important;
            color: var(--dlt-text-color) !important;
            border-color: var(--dlt-border-color) !important;
        }

        /* Handle Elementor background overlays */
        [data-theme='dark'] .elementor-background-overlay,
        [data-theme='dark'] .e-con-inner > .elementor-background-overlay {
            opacity: 0.8;
        }

        /* Ensure nested elements don't inherit transparent backgrounds */
        [data-theme='dark'] .e-con > .e-con,
        [data-theme='dark'] .e-con-inner > .e-con,
        [data-theme='dark'] .elementor-inner-section > .elementor-container {
            background-color: transparent !important;
        }

        /* Handle flex containers specifically */
        [data-theme='dark'] .e-con.e-flex,
        [data-theme='dark'] .e-con-inner.e-flex {
            background-color: var(--dlt-card-bg) !important;
        }

        /* Elementor editor - don't apply dark mode in editor */
        body.elementor-editor-active {
            background-color: initial !important;
            color: initial !important;
        }

        body.elementor-editor-active .elementor-widget-heading .elementor-heading-title,
        body.elementor-editor-active .elementor-widget-text-editor {
            color: initial !important;
        }

        .dlt-toggle {
            position: fixed;
            " . $position_styles . "
            background-color: var(--dlt-card-bg);
            border-radius: 50px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none !important;
            box-shadow: none !important;
            padding: 10px 15px !important;
            z-index: 99999;
            font-family: inherit;
            color: var(--dlt-text-color);
        }

        .dlt-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .dlt-toggle-icon {
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }

        .dlt-toggle-icon svg {
            width: 100%;
            height: 100%;
            fill: var(--dlt-text-color);
            transition: fill 0.3s ease;
        }

        .dlt-toggle-text {
            font-size: 14px;
            font-weight: 500;
            margin: 0 4px;
            " . $text_display . "
        }

        [data-theme='light'] .dlt-dark-icon {
            display: none;
        }

        [data-theme='dark'] .dlt-light-icon {
            display: none;
        }
		[data-theme='dark'] ul.sub-menu li a {
			background: var(--dlt-bg-color) !important;
		}
		[data-theme='light'] .dlt-toggle * {
			color: #ffffff !important;
			fill: #ffffff !important;
		}
		[data-theme='light'] button.dlt-toggle {
			background: #000000 !important;
			color: #ffffff !important;
		}
		[data-theme='dark'] button.dlt-toggle {
			background: #ffffff !important;
			color: #000000 !important;		
		}
		[data-theme='dark'] .no-bg-color .elementor-container,
		[data-theme='dark'] .no-bg-color .elementor-container .elementor-widget-wrap {
			background: none !important;
		}
        [data-theme='dark'] .elementor-widget-theme-site-logo .elementor-widget-container {
            background-color: var(--dlt-card-bg) !important;
        }
        @media (max-width: 768px) {
            .dlt-toggle {
                padding: 6px 10px;
            }
            .dlt-toggle-icon {
                width: 18px;
                height: 18px;
            }
            .dlt-toggle-text {
                font-size: 12px;
            }
        }
        ";
    }
}