const colors = require('tailwindcss/colors');

module.exports = {
    content : [
        './src/app/dom/admin/**/*.{php,html,js,vue}',
        './src/app/dom/public/**/*.{php,html,js,vue}',
        './src/app/dom/clients/**/*.{php,html,js,vue}',
        './src/app/dom/vue/**/*.{php,html,js,vue}',
        './src/app/dom/*.{php,html,js,vue}',
        './src/public/vue/**/*.{html,css,js,vue}'
    ],
    future : {
        hoverOnlyWhenSupported : true,
    },
    theme : {
        extend : {
            boxShadow : {
                't-sm' : '0 -2px 3px rgba(0, 0, 0, 0.1)',
            },
            colors : {
                'if-emerald'          : '#5ac278',
                'if-emerald-900'      : '#5ac278',
                'if-emerald-700'      : '#86cd92',
                'if-emerald-500'      : '#a3d9ad',
                'if-emerald-300'      : '#c1e6c9',
                'if-emerald-100'      : '#e0f3e4',
                'if-emerald-dark'     : '#3a9248',
                'if-hippie-blue'      : '#5093a6',
                'if-hippie-blue-900'  : '#5093a6',
                'if-hippie-blue-700'  : '#7aa9b8',
                'if-hippie-blue-500'  : '#9bbeca',
                'if-hippie-blue-300'  : '#bcd4db',
                'if-hippie-blue-100'  : '#dde9ed',
                'if-hippie-blue-dark' : '#376470',
                'if-silver'           : '#cccccc',
                'if-silver-900'       : '#cccccc',
                'if-silver-700'       : '#d6d6d6',
                'if-silver-500'       : '#e0e0e0',
                'if-silver-300'       : '#ebebeb',
                'if-silver-100'       : '#f5f5f5',
                'if-shark'            : '#1c1f21',
                'if-shark-900'        : '#1c1f21',
                'if-shark-700'        : '#494c4d',
                'if-shark-500'        : '#77797a',
                'if-shark-300'        : '#a4a5a6',
                'if-shark-100'        : '#d2d2d3',
                'if-shark-50'         : '#e2e2e3',
                'if-ripe-lemon'       : '#f0c912',
                'if-ripe-lemon-900'   : '#f0c912',
                'if-ripe-lemon-700'   : '#efd443',
                'if-ripe-lemon-500'   : '#f3df72',
                'if-ripe-lemon-300'   : '#f7e9a0',
                'if-ripe-lemon-100'   : '#fbf4d0',
                'if-pizazz'           : '#fe8a01',
                'if-pizazz-900'       : '#fe8a01',
                'if-pizazz-700'       : '#f6a23a',
                'if-pizazz-500'       : '#f7ba69',
                'if-pizazz-300'       : '#fad09a',
                'if-pizazz-100'       : '#fce8cc',
                'if-peach'            : '#ffe6b6',
                'if-peach-900'        : '#ffe6b6',
                'if-peach-700'        : '#fdebc5',
                'if-peach-500'        : '#fdf0d3',
                'if-peach-300'        : '#fef5e2',
                'if-peach-100'        : '#fefaf0',
                'if-denim'            : '#1974d3',
                'if-denim-900'        : '#1974d3',
                'if-denim-700'        : '#5590dc',
                'if-denim-500'        : '#7eace5',
                'if-denim-300'        : '#a8c7ed',
                'if-denim-100'        : '#d3e3f6',
            },
            fontFamily: {
                'montserrat': ['"Montserrat"'],
                'mont': ['"Montserrat"'],
                'opensans': ['"Open Sans"'],
                'open': ['"Open Sans"'],
            },
            screens: {
                'xs': '512px',
                'sm': '640px',
                'md': '768px',
                'lg': '1024px',
                'xl': '1280px',
                '2xl': '1536px',
                '3xl': '1680px',
                '4xl': '1920px',
            },
        },
    },
    plugins : [],
};
