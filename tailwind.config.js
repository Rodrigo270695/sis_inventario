import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                'arriba-1': '9px 9px 15px #cbced1, -9px -9px 15px white',
                'arriba-2': '6px 6px 10px #cbced1, -6px -6px 10px white',
                'abajo-2': 'inset 6px 6px 6px #cbced1, inset -6px -6px 6px white',
                'abajo-2-cambio': 'inset 6px 6px 6px white, inset -6px -6px 6px #cbced1',
                'abajo-1':  '4px 4px 4px #cbced1,  -4px -4px 4px white',
            },
            backgroundColor: {
                '3D-50': '#ecf0f3',
            },
        },
    },

    plugins: [forms, typography],
};
