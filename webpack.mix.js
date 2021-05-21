const mix = require('laravel-mix');

mix
    .setPublicPath('public')
    .setResourceRoot('../')
    .css('public/css/all/app.css', 'public/css/app.css')
    .css('public/css/all/main.css', 'public/css/main.css')
    .version();
