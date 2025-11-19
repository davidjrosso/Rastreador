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
    form : './js/form.js',
    reporte : './js/ReporteMovimiento.js',
    excel : './js/excel.js'
  },
  output: {
    libraryTarget: 'umd',
    filename: '[name].js',
    path: path.resolve(__dirname, 'dist'),
  },
  target: 'web'
};