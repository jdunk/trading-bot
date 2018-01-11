let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
   .js('node_modules/chart.js/dist/Chart.bundle.js', 'public/js')
   .js('resources/assets/js/chartjs-chart-financial/Chart.Financial.js', 'public/js')
   .js('resources/assets/js/chartjs-chart-financial/index.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
