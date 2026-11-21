import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // Hallmark tokens (resources/css/tokens.css), for pages built from here on.
                paper: { DEFAULT: 'var(--color-paper)', 2: 'var(--color-paper-2)', 3: 'var(--color-paper-3)' },
                ink: { DEFAULT: 'var(--color-ink)', 2: 'var(--color-ink-2)' },
                muted: 'var(--color-muted)',
                rule: 'var(--color-rule)',
                accent: { DEFAULT: 'var(--color-accent)', ink: 'var(--color-accent-ink)', soft: 'var(--color-accent-soft)', deep: 'var(--color-accent-deep)' },
                warn: { DEFAULT: 'var(--color-warn)', soft: 'var(--color-warn-soft)' },
                error: { DEFAULT: 'var(--color-error)', soft: 'var(--color-error-soft)' },
                success: { DEFAULT: 'var(--color-success)', soft: 'var(--color-success-soft)' },
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
