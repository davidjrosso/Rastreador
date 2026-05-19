const path = require('path');
var webpack = require('webpack');

new webpack.ProvidePlugin({
  $: 'jquery',
  jQuery: 'jquery'
});

module.exports = {
  entry: {
    mapa : './js/mapa.js',
    reporte : './js/acciones-reporte-grafico.js',
    alerta : './js/Enlace-Drive.js',
    control : './js/MensajeControl.js',
    editor: "./js/editor.js",
    reporte : './js/ReporteMovimiento.js',
    excel : './js/excel.js',
    validarPersona: "./js/ValidarPersona.js"
  },
  output: {
    libraryTarget: 'umd',
    filename: '[name].js',
    path: path.resolve(__dirname, 'dist'),
  },
  target: 'web',
  module: {
    rules: [{
        test: /\.css$/i,
        // Loaders execute from right to left (sass -> css -> style)
        use: ["style-loader", "css-loader"], 
      }]
}};