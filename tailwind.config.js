import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"DM Sans"', ...defaultTheme.fontFamily.sans],
                display: ['Syne', ...defaultTheme.fontFamily.sans],
                'dm-sans': ['"DM Sans"', ...defaultTheme.fontFamily.sans],
                syne: ['Syne', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                accent: {
                    DEFAULT: '#D4501E',
                    hover: '#BC4419',
                    green: '#2C6E49',
                    gold: '#C9A84C',
                    danger: '#C0392B',
                    info: '#1A5276',
                },
            },
        },
    },

    plugins: [forms],
};
