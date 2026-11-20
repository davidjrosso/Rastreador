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
    ReporteListado : './js/ReporteListado.js',
    movimiento : './js/MovimientosControl.js',
    formulariosReporte : './js/ReportesFiltros.js',
    excel : './js/excel.js',
    excelReporte : './js/excel-reporte.js',
    personasListado: "./js/PersonaListado.js",
    validarPersona: "./js/ValidarPersona.js",
    preferencia: "./js/Preferencia.js",
    navegacion: "./js/ReporteNavegacion.js"
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
        use: ["style-loader",
          {
            loader: 'css-loader',
            options: {
              // Ensure it doesn't treat standard assets mistakenly as CSS modules
              modules: false 
            }
          },
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: {
                plugins: [
                  require('postcss-nesting'),
                  require('postcss-custom-properties')
                ]
              }
            }
          }
        ], 
      }]
}};