const { VueLoaderPlugin } = require('vue-loader');
const webpack = require('webpack');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = {
    mode  : 'development',
    entry : {
        'admin'   : './src/app/dom/admin/js/entry.js',
        'clients' : './src/app/dom/clients/js/entry.js',
        'public'  : './src/app/dom/public/js/entry.js',
    },
    output : {
        path     :  path.resolve(__dirname, './src/public'),
        filename : 'js/[name].min.js',
        library  : 'IF'
    },
    resolve : {
        extensions : ['*', '.js', '.vue', '.json'],
        alias      : {
            '@'        : path.resolve(__dirname, './src/app/dom/vue'),
            '@mixins'  : path.resolve(__dirname, './src/app/dom/vue/mixins'),
            '@plugins' : path.resolve(__dirname, './src/app/dom/vue/plugins'),
            '@stores'  : path.resolve(__dirname, './src/app/dom/vue/stores'),
            '@widgets' : path.resolve(__dirname, './src/app/dom/vue/widgets'),
        },
    },
    module : {
        rules : [
            {
                test : /\.vue$/,
                use  : 'vue-loader',
            },
            {
                test   : /\.js$/,
                loader : 'babel-loader',
            },
            {
                test : /\.css$/,
                use  : [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'postcss-loader'
                ],
            },
        ],
    },
    plugins : [
        new VueLoaderPlugin(),
        new webpack.DefinePlugin({
            __VUE_OPTIONS_API__   : true,
            __VUE_PROD_DEVTOOLS__ : true,
        }),
        new MiniCssExtractPlugin({
            filename : 'css/[name].min.css',
        })
    ],
    optimization : {
        minimizer : [new TerserPlugin({
            extractComments : false,
        })],
    },
};
