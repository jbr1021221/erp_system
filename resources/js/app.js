import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// ============================================
// THEME MANAGEMENT SYSTEM
// ============================================

/**
 * Initialize theme on page load
 */
document.addEventListener('DOMContentLoaded', function() {
    initializeTheme();
});

/**
 * Initialize theme from localStorage or system preference
 */
function initializeTheme() {
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Determine initial theme
    const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
    
    // Apply theme
    applyTheme(theme);
    
    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            applyTheme(e.matches ? 'dark' : 'light');
        }
    });
}

/**
 * Apply theme to document — only uses .dark class to match Tailwind CSS
 * @param {string} theme - 'light' or 'dark'
 */
function applyTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

/**
 * Toggle between light and dark theme
 */
window.toggleTheme = function() {
    const isDark = document.documentElement.classList.toggle('dark');
    const newTheme = isDark ? 'dark' : 'light';

    // Save to localStorage
    localStorage.setItem('theme', newTheme);

    // Subtle transition
    document.body.style.transition = 'background-color 0.3s ease';

    return newTheme;
};

/**
 * Get current theme
 */
window.getCurrentTheme = function() {
    return document.documentElement.getAttribute('data-theme') || 'light';
};
