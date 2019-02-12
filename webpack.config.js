/* === dont forget to import scss to media-links.js file === */
/* ===> import './main.scss'; <=== */

const path = require("path");

module.exports = {
    entry: "./src/assets/index.js",
    output: {
        path: path.resolve(__dirname, "build"),
        filename: "bundle.js",
        publicPath: "./build"
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: ["es2015"]
                    },
                }
            },
            {
                test: /\.scss$/,
                use: [
                    {
                        loader: "style-loader" // creates style nodes from JS strings
                    },
                    {
                        loader: "css-loader" // translates CSS into CommonJS
                    },
                    {
                        loader: "sass-loader" // compiles Sass to CSS
                    },
                ],
            },
        ],
    },
};