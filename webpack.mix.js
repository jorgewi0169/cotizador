const mix = require('laravel-mix');

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
mix.styles([

    'resources/kartik/css/fileinput.min.css',
    'resources/plantilla/config.css',
], 'public/css/plantilla.css')
    .sass('resources/sass/app.scss', 'public/css')
    .scripts([
        'resources/plantilla/js/jquery.min.js',
        'resources/plantilla/js/bootstrap.min.js',
        'resources/plantilla/js/core.js',
        'resources/plantilla/js/bundle.js',
        'resources/plantilla/js/bootstrap-growl.js',
        'resources/plantilla/js/setting.js',
        'resources/plantilla/js/active.js',
        'resources/kartik/js/fileinput.min.js',
        'resources/kartik/js/locales/es.js',
        'resources/kartik/themes/fas/theme.min.js'
    ], 'public/js/plantilla.js')
    .js(['resources/js/app.js','resources/js/util.js',], 'public/js/app.js');
/**
 *
     <script src="../js/plugins/piexif.js" type="text/javascript"></script>
    <script src="../js/plugins/sortable.js" type="text/javascript"></script>
 */
