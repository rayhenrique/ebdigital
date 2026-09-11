import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'bg-purple-700',
        'bg-purple-600',
        'bg-purple-100',
        'bg-purple-50',
        'text-purple-700',
        'text-purple-800',
        'text-purple-900',
        'border-purple-200',
        'border-purple-300',
        'shadow-purple-700/20',
        'bg-emerald-600',
        'bg-emerald-100',
        'text-emerald-800',
        'bg-blue-600',
        'bg-blue-100',
        'text-blue-800',
        'bg-amber-600',
        'bg-amber-100',
        'text-amber-800',
        'bg-indigo-600',
        'bg-indigo-100',
        'text-indigo-800',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                display: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                slate: {
                    850: '#151e2e',
                },
            },
            spacing: {
                '4.5': '1.125rem',
            },
        },
    },

    plugins: [forms],
};
