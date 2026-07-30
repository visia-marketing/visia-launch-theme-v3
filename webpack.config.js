const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const WebpackBar = require('webpackbar');
const webpack = require('webpack');

module.exports = {
    entry: {
        main: [
            './assets/src/scripts/main.js',
            // './assets/src/styles/main.scss'
        ],
        customizer: [
            './assets/src/scripts/customizer.js'
        ],
        // CSS-only entry for the TinyMCE editor iframe. MiniCssExtractPlugin names its
        // output after the chunk, so this emits assets/dist/styles/editor.min.css.
        // It also emits an empty assets/dist/scripts/editor.min.js stub, because
        // output.filename applies to every chunk; that stub is never enqueued.
        editor: [
            './assets/src/styles/editor.scss'
        ]
    },
    devtool: 'source-map',
    output: {
        filename: 'assets/dist/scripts/[name].min.js',
        path: path.resolve(__dirname),
        publicPath: '/wp-content/themes/visia-launch-theme-v3/'
    },
    externals: {
        // Resolve `import $ from 'jquery'` to the global jQuery that WordPress core
        // enqueues, rather than bundling a copy.
        //
        // Do not remove this. node_modules/jquery is 4.x (installed only as a
        // slick-carousel peer dependency), so dropping it would bundle a second jQuery
        // alongside core's 3.x — and jQuery 4 removed $.trim, $.isArray and .bind(),
        // which slick 1.8.1 depends on.
        jquery: 'jQuery'
    },
    resolve: {
        alias: {
            'slick-carousel': 'slick-carousel/slick/slick.js'
        }
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'postcss-loader',
                    {
                        loader: 'sass-loader',
                        options: {
                            sassOptions: {
                                includePaths: [
                                    path.resolve(__dirname, 'assets/src/styles')
                                ]
                            }
                        }
                    }
                ]
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                type: 'asset/resource',
                generator: {
                  filename: 'webfonts/[name][ext]'
                }
            },
            {
                test: /\.(png|jpg|jpeg|gif|svg)$/,
                type: 'asset/resource',
                generator: {
                    filename: 'assets/dist/styles/images/[name][ext]'
                }
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'assets/dist/styles/[name].min.css'
        }),
        new WebpackBar({
            name: 'build',
            color: '#00ff00',
        }),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery'
        })
    ],
    stats: {
        assets: true,
        assetsSort: "size",
        children: false,
        chunks: true,
        chunkModules: false,
        modules: false,
        warnings: false,
        performance: true,
        excludeAssets: [/assets\/dist\/styles\/fonts/, /assets\/dist\/styles\/images/],
    },
};