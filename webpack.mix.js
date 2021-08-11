// const webpack = require('webpack');
let mix = require('laravel-mix');

mix
    .setPublicPath('public')
    .setResourceRoot('../')
    // .js('public/js/all/main.js', 'public/js/main.js')
    .css('public/css/all/app.css', 'public/css/app.css')
    .css('public/css/all/main.css', 'public/css/main.css')
    // .autoload({
    //     jquery: ['$', 'window.jQuery'],
    //     axios: ['window.axios'],
    // })
    // .extract([
    //     'jquery',
    //     'bootstrap',
    //     'axios',
    //     'popper.js',
    // ])
    // .webpackConfig({
    //     module: {
    //         rules: [
    //             {
    //                 test: require.resolve("jquery"),
    //                 loader: "expose-loader",
    //                 options: {
    //                     exposes: ["$", "jQuery"],
    //                 },
    //             },
    //         ],
    //     },
    //     plugins: [
    //         new webpack.ProvidePlugin({
    //             $: 'jquery',
    //             jQuery: 'jquery',
    //             "window.jQuery": 'jquery'
    //         }),
    //     ],
    // })
    .version();
