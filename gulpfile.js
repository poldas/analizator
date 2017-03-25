var elixir = require('laravel-elixir');
require('laravel-elixir-webpack');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    // mix.less('app.less');
    // mix.less('admin-lte/AdminLTE.less');
    // mix.less('bootstrap/bootstrap.less')
    mix.copy('resources/assets/js/chart-app/dist/chart-app-styles.css', 'public/css/chart-app-styles.css')
    mix.copy('resources/assets/js/chart-app/dist/chart-app.min.js', 'public/js/chart-app.min.js');
    mix.copy('resources/assets/js/chart-app/dist/vendor.js', 'public/js/vendor.js');
});
