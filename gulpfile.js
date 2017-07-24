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
    mix.copy('resources/assets/js/wykresy/dist/static/css/wykresy.css', 'public/css/wykresy.css')
    mix.copy('resources/assets/js/wykresy/dist/static/js/manifest.js', 'public/js/manifest.js');
    mix.copy('resources/assets/js/wykresy/dist/static/js/wykresy.js', 'public/js/wykresy.js');
    mix.copy('resources/assets/js/wykresy/dist/static/js/vendor.js', 'public/js/vendor.js');
});
