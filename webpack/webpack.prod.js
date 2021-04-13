const {ProvidePlugin} = require("webpack");

const path = require("path");
const common = require("./webpack.common");
const merge = require("webpack-merge");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = merge(common, {
    mode: "production",
    output: {
        filename: "[name].bundle.js?id=[contentHash]",
        path: path.resolve(__dirname, "../public/build/"),
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].css?id=[contentHash]",
            chunkFilename: '[id].css?id=[contentHash]'
        }),
        new ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
            Popper: ['popper.js', 'default']
        })
    ],
    module: {
        rules: [
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader, //4. Extract css into files
                        options: {
                            outputPath: "../css/"
                        }
                    },
                    {
                        loader: "css-loader", //3. Turns css into commonjs
                        options: {
                            importLoaders: 1
                        }
                    },
                    'postcss-loader', //2. Use autoprefixer plugin
                    "sass-loader" //1. Turns sass into css
                ]
            }
        ]
    },
    devtool: "source-map"
});
