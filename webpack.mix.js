const mix = require('laravel-mix');

mix
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/config.js', 'public/js')
    .js('resources/js/main.js', 'public/js')
    .js('resources/js/menu.js', 'public/js')
    .js('resources/js/helpers.js', 'public/js')
    .js('resources/js/ajax.js', 'public/js')
    .js('resources/js/filtros.js', 'public/js')
    .js('resources/js/input.js', 'public/js')
    .js('resources/js/telas.js', 'public/js')
    .js('resources/js/utils.js', 'public/js')
    .css('resources/css/app.css', 'public/css')
    .css('resources/css/core.css', 'public/css')
    .css('resources/css/core-dark.css', 'public/css')
    .css('resources/css/theme-default.css', 'public/css')
    .css('resources/css/theme-token.css', 'public/css')
    .css('resources/css/theme-default-dark.css', 'public/css')
    .css('resources/css/theme-semi-dark.css', 'public/css')
    .css('resources/css/theme-custom.css', 'public/css')
    .css('resources/css/theme-custom-dark.css', 'public/css')
    .css('resources/css/theme-custom-price.css', 'public/css')
    .css('resources/css/pages/page-auth.css', 'public/css/pages')
    .css('resources/css/pages/page-help-center.css', 'public/css/pages')
    .sourceMaps();

mix.copyDirectory("resources/img", "public/img");
mix.copyDirectory("resources/vendor", "public/vendor");

mix.version();
