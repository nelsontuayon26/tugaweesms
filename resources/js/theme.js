/**
 * TESSMS Unified Theme Engine
 * Handles dark mode across all user roles: Admin, Teacher, Student, Registrar, Principal
 */

(function () {
    'use strict';

    const STORAGE_KEY = 'tessms_theme';
    const DARK_CLASS = 'dark';

    /**
     * Get the user's theme preference from all possible sources
     */
    function getThemePreference() {
        // 1. Check localStorage (most recent user choice)
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored === 'dark' || stored === 'light') {
            return stored;
        }

        // 2. Check admin settings (dark_mode boolean)
        const adminMeta = document.querySelector('meta[name="user-dark-mode"]');
        if (adminMeta) {
            return adminMeta.content === '1' ? 'dark' : 'light';
        }

        // 3. Check teacher/user settings (theme: light/dark/system)
        const themeMeta = document.querySelector('meta[name="user-theme"]');
        if (themeMeta) {
            const theme = themeMeta.content;
            if (theme === 'dark' || theme === 'light') {
                return theme;
            }
            if (theme === 'system') {
                return getSystemPreference();
            }
        }

        // 4. Check old localStorage key from admin settings
        const legacyDark = localStorage.getItem('app_dark_mode');
        if (legacyDark === '1') return 'dark';
        if (legacyDark === '0') return 'light';

        // 5. Fall back to system preference
        return getSystemPreference();
    }

    /**
     * Get system color scheme preference
     */
    function getSystemPreference() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
    }

    /**
     * Apply theme to the document
     */
    function applyTheme(theme) {
        const html = document.documentElement;
        const body = document.body;

        if (theme === 'dark') {
            html.classList.add(DARK_CLASS);
            if (body) body.classList.add('dark-mode');
        } else {
            html.classList.remove(DARK_CLASS);
            if (body) body.classList.remove('dark-mode');
        }

        // Dispatch custom event for charts and other dynamic content
        window.dispatchEvent(new CustomEvent('themechange', { detail: { theme } }));
    }

    /**
     * Set and persist theme preference
     */
    function setTheme(theme) {
        localStorage.setItem(STORAGE_KEY, theme);
        applyTheme(theme);
    }

    /**
     * Toggle between light and dark
     */
    function toggleTheme() {
        const current = getCurrentTheme();
        const next = current === 'dark' ? 'light' : 'dark';
        setTheme(next);
        return next;
    }

    /**
     * Get currently active theme
     */
    function getCurrentTheme() {
        return document.documentElement.classList.contains(DARK_CLASS) ? 'dark' : 'light';
    }

    /**
     * Initialize theme on page load (before render to prevent FOUC)
     */
    function init() {
        const theme = getThemePreference();
        applyTheme(theme);
    }

    // Run immediately to prevent flash of unstyled content
    init();

    // Re-run when DOM is ready (in case body wasn't available yet)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    }

    // Listen for system preference changes
    if (window.matchMedia) {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', function (e) {
            // Only auto-switch if user hasn't made an explicit choice
            const stored = localStorage.getItem(STORAGE_KEY);
            if (!stored) {
                applyTheme(e.matches ? 'dark' : 'light');
            }
        });
    }

    // Expose global API
    window.tessmsTheme = {
        set: setTheme,
        toggle: toggleTheme,
        get: getCurrentTheme,
        init: init
    };

    // Backward-compatible aliases
    window.toggleTheme = toggleTheme;
    window.getCurrentTheme = getCurrentTheme;
    window.setTheme = setTheme;
})();
