import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['system-ui', '-apple-system', 'BlinkMacSystemFont', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary-gradient-start': '#667eea',
                'primary-gradient-end': '#764ba2',
                'logo-gradient-start': '#4ecdc4',
                'logo-gradient-middle': '#667eea',
                'logo-gradient-end': '#c3a6ff',
                'accent-coral': '#ff7b7b',
                'accent-turquoise': '#4ecdc4',
                'accent-lavender': '#c3a6ff',
                'accent-orange': '#ffb366',
                'accent-blue-purple': '#6b73ff',
                'neutral-white': '#ffffff',
                'neutral-light-gray': '#f5f5f7',
                'neutral-medium-gray': '#8e8e93',
                'neutral-dark-gray': '#1d1d1f',
                'neutral-input-gray': '#f2f2f7',
                'neutral-border-gray': '#e5e5ea',
            },
            boxShadow: {
                'subtle': '0 2px 8px rgba(0, 0, 0, 0.04)',
                'medium': '0 4px 16px rgba(0, 0, 0, 0.08)',
                'elevated': '0 8px 25px rgba(102, 126, 234, 0.4)',
                'focus': '0 0 0 3px rgba(102, 126, 234, 0.1)',
            }
        },
    },

    plugins: [forms],
};
