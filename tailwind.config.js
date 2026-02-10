const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Instrument Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Palette Cale & Sons
                cale: {
                    blue: {
                        50: '#eff6ff',
                        100: '#dbeafe',
                        200: '#bfdbfe',
                        300: '#93c5fd',
                        400: '#60a5fa',
                        500: '#3b82f6',
                        600: '#2563eb',
                        700: '#1d4ed8',
                        800: '#1e40af',
                        900: '#1e3a8a',
                        950: '#172554',
                    },
                    orange: {
                        50: '#fff7ed',
                        100: '#ffedd5',
                        200: '#fed7aa',
                        300: '#fdba74',
                        400: '#fb923c',
                        500: '#f97316',
                        600: '#ea580c',
                        700: '#c2410c',
                        800: '#9a3412',
                        900: '#7c2d12',
                        950: '#431407',
                    },
                    dark: {
                        50: '#f8fafc',
                        100: '#f1f5f9',
                        200: '#e2e8f0',
                        300: '#cbd5e1',
                        400: '#94a3b8',
                        500: '#64748b',
                        600: '#475569',
                        700: '#334155',
                        800: '#1e293b',
                        900: '#0f172a',
                        950: '#020617',
                    },
                },
            },
            backgroundImage: {
                'gradient-cale': 'linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0f1419 100%)',
                'gradient-blue': 'linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #2563eb 100%)',
                'gradient-blue-orange': 'linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #f97316 100%)',
            },
            boxShadow: {
                'glow-blue': '0 0 20px rgba(59, 130, 246, 0.5)',
                'glow-blue-lg': '0 0 30px rgba(59, 130, 246, 0.8)',
                'card': '0 10px 30px rgba(0, 0, 0, 0.2)',
                'card-hover': '0 20px 60px rgba(59, 130, 246, 0.2)',
            },
            backdropBlur: {
                xs: '2px',
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
    ],
};