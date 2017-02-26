const path = require('path');
const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const ENV = process.env.NODE_ENV || 'production';

let config = {
    entry: './src/app.js',
    output: {
        path: path.resolve(__dirname, 'assets'),
        filename: 'app.bundle.js'
    },
    module: {
        rules: [{
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    query: {
                        presets: ['babel-preset-es2015']
                    }
                }
            },
            {
                test: /\.scss$/,
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: [{
                        loader: 'css-loader'
                    }, {
                        loader: 'postcss-loader',
                        options: {
                            plugins: () => [require('cssnano')]
                        }
                    }, {
                        loader: 'sass-loader'
                    }]
                })
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin('app.bundle.css')
    ]
};

if (ENV === 'development') {
    config.watch = true;
} else {
    config.plugins.push(new webpack.optimize.UglifyJsPlugin());
}

module.exports = config;
