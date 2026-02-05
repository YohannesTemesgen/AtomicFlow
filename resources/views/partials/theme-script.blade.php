<script>
    /**
     * Theme management system
     * Handles dark/light mode detection, persistence, and transitions
     */
    const themeManager = {
        init() {
            const savedTheme = localStorage.getItem('theme') || 'system';
            this.applyTheme(savedTheme);
            
            // Listen for system preference changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (localStorage.getItem('theme') === 'system') {
                    this.applyTheme('system');
                }
            });
        },

        applyTheme(theme) {
            const html = document.documentElement;
            let actualTheme = theme;

            if (theme === 'system') {
                actualTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            if (actualTheme === 'dark') {
                html.classList.add('dark');
                html.classList.remove('light');
            } else {
                html.classList.remove('dark');
                html.classList.add('light');
            }

            // Sync with Flux UI if present
            if (window.Flux) {
                // Flux usually uses 'appearance' key
                localStorage.setItem('appearance', theme);
            }
            
            localStorage.setItem('theme', theme);
            
            // Dispatch event for other components
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme, actualTheme } }));
        },

        toggle() {
            const currentTheme = localStorage.getItem('theme') || 'system';
            const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
            this.applyTheme(nextTheme);
        },

        getTheme() {
            return localStorage.getItem('theme') || 'system';
        }
    };

    // Initialize theme as soon as possible to avoid flash of unstyled content
    themeManager.init();
</script>
