/**
 * Created by fruit on 2/4/2019.
 */

webpack = require("webpack")
path = require("path")
module.exports = {
    module: {
        loaders: [
            {
                test: /\.vue$/, loader: "vue-loader"
            },
            {
                test: /\.html$/, loader: "html"
            },
            {
                test: /\.coffee$/,
                loader: "coffee-loader"
            },
            {
                test: /\.css$/,
                loader: "style-loader!css-loader"
            },
            {
                test: /\.woff(\d*)\??(\d*)$/,
                loader: "url-loader?limit=10000&mimetype=application/font-woff"
            },
            {
                test: /\.ttf\??(\d*)$/,
                loader: "file-loader"
            },
            {
                test: /\.eot\??(\d*)$/,
                loader: "file-loader"
            },
            {
                test: /\.svg\??(\d*)$/,
                loader: "file-loader"
            },
            {
                test: /\.s[ac]ss$/,
                loader: "style!css!sass"
            },
        ],
        vue: {
            loaders: {
                scss: 'style!css!sass'
            }
        },
        postLoaders: [
            {test: /vue-icons/, loader: "callback-loader"}
        ],
        resolve: {
            extensions: ["", ".webpack.js", ".web.js", ".js", ".coffee", ".vue"]
        },
        callbackLoader: require("vue-icons/icon-loader")(["material-cancel", "fa-user", "fa-key", "material-close", "material-search"])
    }
}
/*
* module: {
 loaders: [
 {
 test: /\.vue$/,
 loader: 'vue'
 },
 {
 test: /\.s[a|c]ss$/,
 loader: 'style!css!sass'
 }
 ]
 },
 vue: {
 loaders: {
 scss: 'style!css!sass'
 }
 }*/