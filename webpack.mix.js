let mix = require('laravel-mix');

mix.options({
    publicPath: 'public'
}).js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
