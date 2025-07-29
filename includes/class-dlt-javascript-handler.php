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
        $dark_mode_logo = $this->settings_manager->get_option('dark_mode_logo');
        
        return "
        (function() {
            'use strict';
            
            const DLT = {
                storageKey: 'dlt-theme',
                darkModeLogo: " . json_encode($dark_mode_logo) . ",
                originalLogos: {},
                
                init: function() {
                    this.loadTheme();
                    this.setupEventListeners();
                    this.setupLogoSwitching();
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
                    this.updateLogos(theme);
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
                },
                
                setupLogoSwitching: function() {
                    if (!this.darkModeLogo) return;
                    
                    // Wait for DOM to be ready
                    const initLogos = () => {
                        this.findAndStoreLogos();
                        this.updateLogos(document.documentElement.getAttribute('data-theme'));
                    };
                    
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initLogos);
                    } else {
                        initLogos();
                    }
                },
                
                findAndStoreLogos: function() {
                    // Very specific logo selectors
                    const logoSelectors = [
                        '.elementor-widget-theme-site-logo .elementor-widget-container img',
                        'img.custom-logo',
                        '.site-logo > img',
                        'a.custom-logo-link img'
                    ];
                    
                    logoSelectors.forEach(selector => {
                        const logos = document.querySelectorAll(selector);
                        logos.forEach((logo, index) => {
                            // Skip if already processed or if it's not actually a logo
                            if (logo.dataset.dltProcessed || !this.isActuallyLogo(logo)) {
                                return;
                            }
                            
                            const key = 'logo_' + index;
                            logo.dataset.dltLogoKey = key;
                            logo.dataset.dltProcessed = 'true';
                            
                            this.originalLogos[key] = {
                                src: logo.src,
                                srcset: logo.srcset || ''
                            };
                        });
                    });
                },
                
                updateLogos: function(theme) {
                    if (!this.darkModeLogo) return;
                    
                    // Only update images that we've marked as logos
                    const processedLogos = document.querySelectorAll('img[data-dlt-logo-key]');
                    
                    processedLogos.forEach(logo => {
                        const key = logo.dataset.dltLogoKey;
                        
                        if (!this.originalLogos[key]) {
                            return;
                        }
                        
                        if (theme === 'dark' && this.darkModeLogo) {
                            // Switch to dark mode logo
                            logo.src = this.darkModeLogo;
                            if (logo.srcset) {
                                logo.srcset = this.darkModeLogo;
                            }
                        } else {
                            // Restore original logo
                            logo.src = this.originalLogos[key].src;
                            if (this.originalLogos[key].srcset) {
                                logo.srcset = this.originalLogos[key].srcset;
                            }
                        }
                    });
                    
                    // Also handle logo changes in srcset for retina displays
                    this.updateRetinaSources(theme);
                },
                
                updateRetinaSources: function(theme) {
                    // Only process picture elements that contain our marked logos
                    const processedLogos = document.querySelectorAll('img[data-dlt-logo-key]');
                    
                    processedLogos.forEach(logo => {
                        // Check if this logo is inside a picture element
                        const picture = logo.closest('picture');
                        if (picture) {
                            const sources = picture.querySelectorAll('source');
                            sources.forEach(source => {
                                if (!source.dataset.originalSrcset) {
                                    source.dataset.originalSrcset = source.srcset;
                                }
                                
                                if (theme === 'dark' && this.darkModeLogo) {
                                    source.srcset = this.darkModeLogo;
                                } else if (source.dataset.originalSrcset) {
                                    source.srcset = source.dataset.originalSrcset;
                                }
                            });
                        }
                    });
                },
                
                isActuallyLogo: function(img) {
                    // Strict check - only consider it a logo if it's in a logo-specific container
                    let parent = img.parentElement;
                    let depth = 0;
                    
                    while (parent && depth < 5) {
                        const className = parent.className || '';
                        const id = parent.id || '';
                        
                        // Check for Elementor site logo widget
                        if (className.includes('elementor-widget-theme-site-logo') ||
                            className.includes('elementor-widget-site-logo')) {
                            return true;
                        }
                        
                        // Check for WordPress custom logo
                        if (className.includes('custom-logo-link') || 
                            img.classList.contains('custom-logo')) {
                            return true;
                        }
                        
                        // Check for specific site-logo class (direct parent only)
                        if (depth === 0 && className === 'site-logo') {
                            return true;
                        }
                        
                        parent = parent.parentElement;
                        depth++;
                    }
                    
                    return false;
                },
                
                isLikelyLogo: function(img) {
                    // This is now unused but kept for compatibility
                    return false;
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