/**
 * Webpack config 
 * 
 */
const webpack = require( 'webpack' )
const path = require( 'path' )

module.exports = {
  mode: 'production',
  entry: {
    frontend: './src/project-pack.js'
  },
  output: {
    path: path.resolve( __dirname, 'dist' ),
    filename: 'project-pack.[name].bundle.js'
  },
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          // Creates `style` nodes from JS strings
          "style-loader",
          // Translates CSS into CommonJS
          "css-loader",
          // Compiles Sass to CSS
          "sass-loader",
        ],
      }
    ]
  }
}