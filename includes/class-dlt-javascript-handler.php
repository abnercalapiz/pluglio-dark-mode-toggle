<?php
/**
 * JavaScript Handler for Dark Light Toggle Plugin
 *
 * @package DarkModeToggle
 * @author  Jezweb
 * @since   1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class DLT_JavaScript_Handler {
    
    private $settings_manager;
    
    public function __construct($settings_manager) {
        $this->settings_manager = $settings_manager;
    }
    
    public function get_javascript() {
        $auto_detect = $this->settings_manager->get_option('enable_auto_detect');
        
        return "
        (function() {
            'use strict';
            
            const DLT = {
                storageKey: 'dlt-theme',
                
                init: function() {
                    this.loadTheme();
                    this.setupEventListeners();
                    " . ($auto_detect === 'yes' ? 'this.watchSystemPreference();' : '') . "
                },
                
                toggleTheme: function() {
                    const currentTheme = document.documentElement.getAttribute('data-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    
                    this.setTheme(newTheme);
                    this.saveTheme(newTheme);
                    this.dispatchThemeEvent(newTheme);
                },
                
                setTheme: function(theme) {
                    document.documentElement.setAttribute('data-theme', theme);
                },
                
                saveTheme: function(theme) {
                    try {
                        localStorage.setItem(this.storageKey, theme);
                    } catch (e) {
                        console.warn('DLT: Could not save theme preference', e);
                    }
                },
                
                loadTheme: function() {
                    const savedTheme = this.getSavedTheme();
                    const defaultTheme = this.getDefaultTheme();
                    const theme = savedTheme || defaultTheme;
                    
                    this.setTheme(theme);
                },
                
                getSavedTheme: function() {
                    try {
                        return localStorage.getItem(this.storageKey);
                    } catch (e) {
                        return null;
                    }
                },
                
                getDefaultTheme: function() {
                    " . ($auto_detect === 'yes' ? 
                    "const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    return prefersDark ? 'dark' : 'light';" : 
                    "return 'light';") . "
                },
                
                setupEventListeners: function() {
                    const setupButton = () => {
                        const toggleButton = document.querySelector('.dlt-toggle');
                        if (toggleButton) {
                            toggleButton.addEventListener('click', () => this.toggleTheme());
                        }
                    };
                    
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', setupButton);
                    } else {
                        setupButton();
                    }
                },
                
                watchSystemPreference: function() {
                    if (!window.matchMedia) return;
                    
                    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                    mediaQuery.addEventListener('change', (e) => {
                        if (!this.getSavedTheme()) {
                            const theme = e.matches ? 'dark' : 'light';
                            this.setTheme(theme);
                            this.dispatchThemeEvent(theme);
                        }
                    });
                },
                
                dispatchThemeEvent: function(theme) {
                    window.dispatchEvent(new CustomEvent('dltThemeChanged', { 
                        detail: { theme: theme },
                        bubbles: true,
                        cancelable: false
                    }));
                }
            };
            
            // Initialize immediately
            DLT.init();
            
            // Expose API for external use
            window.DarkLightToggle = {
                toggle: () => DLT.toggleTheme(),
                setTheme: (theme) => {
                    DLT.setTheme(theme);
                    DLT.saveTheme(theme);
                    DLT.dispatchThemeEvent(theme);
                },
                getTheme: () => document.documentElement.getAttribute('data-theme')
            };
        })();
        ";
    }
}