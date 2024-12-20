const path = require('path');

module.exports = {
  entry: {
    mapa : './js/mapa.js',
    reporte : './js/acciones-reporte-grafico.js',
  },
  output: {
    libraryTarget: 'umd',
    filename: '[name].js',
    path: path.resolve(__dirname, 'dist'),
  },
};