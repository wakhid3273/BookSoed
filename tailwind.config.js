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
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brand: {
                    50: '#F2F8F5',
                    100: '#E1F0EA',
                    200: '#C2E0D4',
                    500: '#2E7D60',
                    600: '#1E6349',
                    700: '#174E3A',
                    800: '#113A2C',
                    900: '#0C281F',
                },
                accent: {
                    50: '#FFFDF5',
                    100: '#FFF7D6',
                    500: '#D97706',
                    600: '#B45309',
                },
                warm: {
                    50: '#FDFCF9',
                    100: '#F9F6F0',
                    200: '#F0EAE1',
                    300: '#E2D8C9',
                    800: '#3D362E',
                    900: '#231F1A',
                }
            }
        },
    },

    plugins: [forms],
};
