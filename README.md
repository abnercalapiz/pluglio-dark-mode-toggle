# Pluglio Dark Mode Toggle

A beautiful and customizable dark/light mode toggle plugin for WordPress with smooth transitions and persistent user preferences.

## Description

Pluglio Dark Mode Toggle adds a sleek dark/light mode switcher to your WordPress website. It features smooth CSS transitions, remembers user preferences across sessions, and provides extensive customization options through the WordPress admin panel.

## Features

- **Automatic Theme Persistence**: User's theme preference is saved and remembered across sessions
- **Smooth CSS Transitions**: Beautiful transitions when switching between light and dark modes
- **System Preference Detection**: Optionally detect and respect user's system theme preference
- **Customizable Position**: Place the toggle button in any corner of the screen
- **Mobile Responsive**: Optimized for all device sizes
- **Accessibility Support**: Proper ARIA labels and keyboard navigation
- **Elementor Compatibility**: Full support for Elementor page builder including nested sections and containers
- **Color Customization**: Control link and button colors in dark mode
- **No Dependencies**: Lightweight implementation with vanilla JavaScript

## Installation

1. Upload the `pluglio-dark-mode-toggle` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Settings > Pluglio Dark Mode to configure the plugin

## Configuration

### Admin Settings

Access the plugin settings from **Settings > Pluglio Dark Mode** in your WordPress admin:

- **Toggle Position**: Choose where the toggle button appears (top-left, top-right, bottom-left, bottom-right)
- **Show Text Labels**: Display "Dark" and "Light" text alongside icons
- **Auto-detect System Preference**: Respect user's system theme preference
- **Dark Mode Link Color**: Customize the color of links in dark mode
- **Dark Mode Button Background**: Customize the background color of Elementor buttons in dark mode

### JavaScript API

The plugin exposes a global JavaScript API for programmatic control:

```javascript
// Toggle between dark and light mode
window.DarkLightToggle.toggle();

// Set specific theme
window.DarkLightToggle.setTheme('dark'); // or 'light'

// Get current theme
const currentTheme = window.DarkLightToggle.getTheme();

// Listen for theme changes
window.addEventListener('dltThemeChanged', function(e) {
    console.log('Theme changed to:', e.detail.theme);
});
```

## CSS Customization

The plugin uses CSS custom properties (variables) that you can override in your theme:

```css
/* Light mode variables */
:root {
    --dlt-bg-color: #ffffff;
    --dlt-text-color: #333333;
    --dlt-header-bg: #f8f9fa;
    --dlt-border-color: #e0e0e0;
    --dlt-card-bg: #ffffff;
    --dlt-link-color: #007cba;
    --dlt-button-bg: #007cba;
    --dlt-button-text: #ffffff;
}

/* Dark mode variables */
[data-theme='dark'] {
    --dlt-bg-color: #1a1a1a;
    --dlt-text-color: #e0e0e0;
    --dlt-header-bg: #2d2d2d;
    --dlt-border-color: #404040;
    --dlt-card-bg: #2d2d2d;
    --dlt-link-color: #4a9eff;
    --dlt-button-bg: #4a9eff;
    --dlt-button-text: #ffffff;
}
```

## Elementor Support

The plugin provides comprehensive support for Elementor page builder:

- Classic sections and inner sections
- New flexbox containers (e-con)
- Nested sections and containers
- Column backgrounds and widget wraps
- All Elementor widgets (headings, text, buttons, etc.)
- Custom background handling
- Editor mode protection (dark mode doesn't affect Elementor editor)

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Changelog

### Version 1.0.2
- Fixed CSS styling issues to enhance light and dark mode

### Version 1.0.1
- Fixed Elementor column nested background issue

### Version 1.0.0
- Initial release
- Basic dark/light mode toggle functionality
- Admin settings panel
- Elementor compatibility
- Custom color options for links and buttons
- System preference detection
- Mobile responsive design

## Support

For support and documentation, visit [Jezweb](https://www.jezweb.com.au)

## License

GPL v2 or later

## Credits

Developed by [Jezweb](https://www.jezweb.com.au)