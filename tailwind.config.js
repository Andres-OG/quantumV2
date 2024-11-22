export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#FFFFFF', // Indigo
                secondary: '#FA653E', // Blue
                accent: '#22C55E', // Green
                neutral: '#F3F4F6', // Light Gray
                warning: '#F97316', // Orange
                danger: '#EF4444', // Red
                dark: '#1F2937', // Dark Gray
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
