import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                "primary": "#137fec",
                "background-light": "#f6f7f8",
                "background-dark": "#101922",
                "danger": "#ef4444",
                "electric-blue": "#00BFFF",
                "bright-magenta": "#FF00FF",
                "dark-gray": "#1a1a1a",
                "medium-gray": "#2c2c2c",
                "hero-bg": "#0B0F1A",
                "green-accent": "#28a745",
                "red-accent": "#dc3545",
                "card-light": "#ffffff",
                "card-dark": "#161d31",
                "border-light": "#e2e8f0",
                "border-dark": "#334155",
                "text-light": "#64748b",
                "text-dark": "#94a3b8",
                "heading-light": "#0f172a",
                "heading-dark": "#ffffff",
            },
            fontFamily: {
                "display": ["Inter", "sans-serif"]
            },
            borderRadius: {
                "DEFAULT": "0.5rem",
                "lg": "1rem",
                "xl": "1.5rem",
                "full": "9999px"
            },
            keyframes: {
                'form-item-fade-in': {
                    from: { opacity: '0', transform: 'translateY(10px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
                'globe-spin': {
                    from: { transform: 'rotateY(0deg)' },
                    to: { transform: 'rotateY(360deg)' },
                },
                'link-arc-in': {
                    '0%': { strokeDashoffset: '1', opacity: '0' },
                    '20%': { opacity: '1' },
                    '100%': { strokeDashoffset: '0', opacity: '1' },
                },
                'float-up': {
                    '0%': { transform: 'translateY(0) scale(0.5)', opacity: '0' },
                    '50%': { opacity: '1' },
                    '100%': { transform: 'translateY(-100px) scale(1)', opacity: '0' },
                },
                'pulse-subtle': {
                   '0%, 100%': { transform: 'scale(1)', boxShadow: '0 0 0 0 rgba(13, 127, 242, 0.4)' },
                   '50%': { transform: 'scale(1.05)', boxShadow: '0 0 15px 5px rgba(13, 127, 242, 0.2)' },
                },
                'spin': {
                    from: { transform: 'rotate(0deg)' },
                    to: { transform: 'rotate(360deg)' },
                },
                'link-flow': {
                    from: { strokeDashoffset: '200' },
                    to: { strokeDashoffset: '0' },
                },
                'data-pulse': {
                    '0%, 100%': { transform: 'scale(1)', opacity: '0.8' },
                    '50%': { transform: 'scale(1.1)', opacity: '1' },
                },
                'earnings-rise': {
                    '0%': { transform: 'translateY(100%) scaleY(0)', opacity: '0.5' },
                    '50%': { opacity: '1' },
                    '100%': { transform: 'translateY(0%) scaleY(1)', opacity: '0' },
                },
                'celestial-orbit': {
                   '0%': { transform: 'rotate(0deg) translateX(var(--orbit-radius)) rotate(0deg)' },
                   '100%': { transform: 'rotate(360deg) translateX(var(--orbit-radius)) rotate(-360deg)' }
                },
                'float': {
                   '0%, 100%': { transform: 'translateY(0)' },
                   '50%': { transform: 'translateY(-10px)' }
                },
                'slide': {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(calc(-250px * 7))' }
                },
                'fade-in': {
                    '0%': { opacity: 0, transform: 'translateY(10px)' },
                    '100%': { opacity: 1, transform: 'translateY(0)' },
                },
                'scroll-up': {
                    '0%': { transform: 'translateY(100%)' },
                    '100%': { transform: 'translateY(-100%)' },
                },
            },
            animation: {
                'form-item-fade-in': 'form-item-fade-in 0.5s ease-out forwards',
                'globe-spin': 'globe-spin 40s linear infinite',
                'link-arc-in': 'link-arc-in 2s ease-out forwards',
                'float-up': 'float-up 5s ease-in-out infinite',
                'pulse-subtle': 'pulse-subtle 4s ease-in-out infinite',
                'spin': 'spin 1s linear infinite',
                'link-flow': 'link-flow 3s linear infinite',
                'data-pulse': 'data-pulse 2s ease-in-out infinite',
                'earnings-rise': 'earnings-rise 4s ease-out infinite',
                'celestial-orbit': 'celestial-orbit var(--duration) linear infinite',
                'float': 'float 4s ease-in-out infinite',
                'slide': 'slide 40s linear infinite',
                'fade-in': 'fade-in 0.5s ease-out forwards',
                'scroll-up': 'scroll-up 15s linear infinite',
            },
        },
    },
    plugins: [
        forms,
    ],
}
