import { MapaOl } from "./MapaOl.js";


export function init(
                     lat = null, 
                     lon = null, 
                     map = null
){
if (map === null) {
    map = new MapaOl(
                     "basicMap",
                     15,
                     lat, 
                     lon
                    );
  }
  return map;
}

function ordenGeoreferencia(personaObjectA, personaObjectB) {
  if (personaObjectA.lat < personaObjectB.lat) {
    return -1;
  } else if (personaObjectA.lat > personaObjectB.lat) {
    return 1;
  } else {
    if (personaObjectA.lon < personaObjectB.lon) {
      return -1;
    } else if (personaObjectA.lon > personaObjectB.lon) {
      return 1;
    } else {
      return 0;
    }
  }
  return 0;
}

function ordenFecha(personaObjectA, personaObjectB) {
  if (Date.parse(personaObjectA.fecha) < Date.parse(personaObjectB.fecha)) {
    return -1;
  } else if (Date.parse(personaObjectA.fecha) > Date.parse(personaObjectB.fecha)) {
    return 1;
  } else {
    return 0;
  }
  return 0;
}

export function carga(map, listReferencias) {
  let nroLote = 1;
  let pos = null;
  let posicionAnterior = null;
  let positionFormas = null;
  listReferencias.sort(ordenGeoreferencia).forEach(function (elemento, indice, array) {
    pos = [parseFloat(elemento.lon), parseFloat(elemento.lat)];
    let lista_formas = elemento.lista_formas_categorias;
    if (lista_formas) {
      if (indice >= 1) {
        //console.log(posicionAnterior && (posicionAnterior[0] === pos[0] && posicionAnterior[1] === pos[1]));
        //console.log(posicionAnterior);
        //console.log(pos);
        //console.log(posicionAnterior[0] === pos[0] && posicionAnterior[1] === pos[1]);
        if (posicionAnterior && (posicionAnterior[0] === pos[0] && posicionAnterior[1] === pos[1])) {
          //pos = pos.add((-8.3) * nroLote, (4.5) * nroLote);
          pos = [pos[0] + ((-0.00006002) * nroLote, pos[1] + (0.000067060) * nroLote)];
          nroLote++;
        } else {
          posicionAnterior = pos;
          nroLote = 1;
        }
      } else {
        posicionAnterior = pos;
      }
      positionFormas = pos;

      let angulo = 360;
      let longuitud = Object.keys(lista_formas).length + 1;
      let puntos = angulo / longuitud;
      let listaDeClaves = Object.keys(lista_formas);
      let listaConOrden = listaDeClaves.sort(function (categoriaA, categoriaB) {
        if (lista_formas[categoriaA][1] < lista_formas[categoriaB][1]) {
          return -1;
        } else if (lista_formas[categoriaA][1] > lista_formas[categoriaB][1]) {
          return 1;
        } else {
          if (Date.parse(lista_formas[categoriaA][2]) > Date.parse(lista_formas[categoriaB][2])) {
            return -1;
          } else if (Date.parse(lista_formas[categoriaA][2]) < Date.parse(lista_formas[categoriaB][2])) {
            return 1;
          } else {
            return 0;
          }
        }
      });
      let tipoCategoriaPrevia = -1;
      let ordenPrevio = Date.parse("2000-01-01");
      listaConOrden.forEach(function (categoria, indice, array) {
        if (tipoCategoriaPrevia == lista_formas[categoria][1]
          && ordenPrevio > Date.parse(lista_formas[categoria][2])) {
          return;
        }
        if (lista_formas[categoria][1] != 0) {
          tipoCategoriaPrevia = lista_formas[categoria][1];
        }
        ordenPrevio = Date.parse(lista_formas[categoria][2]);

        let offsetX = 0;
        let offsetY = 0;
        if (indice > 0) {
          offsetX = Math.cos(indice * puntos) * (longuitud * 0.00001);
          offsetY = Math.sin(indice * puntos) * (longuitud * 0.00001);
        } else {
          //offsetX = 4.5;
          offsetX = 0.000067060;
          //offsetY = -8.3;
          offsetY = -0.00006002;
          //positionFormas = positionFormas.add(-8.3, 4.5);
        }
        map.addIconLayerR(
                    positionFormas[0],
                    positionFormas[1],
                    offsetY,
                    offsetX,
                    elemento,
                    categoria,
                    lista_formas[categoria][0]
        );
      });
    }
  });
  map.layerAddToMapp();
  map.viewPersonaGeoreferenciada();
}

export function animacion(map, listReferencias) {
  let nroLote = 1;
  let pos = null;
  let posicionAnterior = null;
  let positionFormas = null;
  listReferencias.sort(ordenFecha).forEach(function (elemento, indice, array) {
    pos = [parseFloat(elemento.lon), parseFloat(elemento.lat)];
    let lista_formas = elemento.lista_formas_categorias;
    if (lista_formas) {
      if (indice >= 1) {
        if (posicionAnterior && (posicionAnterior[0] === pos[0] && posicionAnterior[1] === pos[1])) {
          pos = [pos[0] + ((-0.00006002) * nroLote, pos[1] + (0.000067060) * nroLote)];
          nroLote++;
        } else {
          posicionAnterior = pos;
          nroLote = 1;
        }
      } else {
        posicionAnterior = pos;
      }
      positionFormas = pos;

      let angulo = 360;
      let longuitud = Object.keys(lista_formas).length + 1;
      let puntos = angulo / longuitud;
      let listaDeClaves = Object.keys(lista_formas);
      let listaConOrden = listaDeClaves.sort(function (categoriaA, categoriaB) {
        if (lista_formas[categoriaA][1] < lista_formas[categoriaB][1]) {
          return -1;
        } else if (lista_formas[categoriaA][1] > lista_formas[categoriaB][1]) {
          return 1;
        } else {
          if (Date.parse(lista_formas[categoriaA][2]) > Date.parse(lista_formas[categoriaB][2])) {
            return -1;
          } else if (Date.parse(lista_formas[categoriaA][2]) < Date.parse(lista_formas[categoriaB][2])) {
            return 1;
          } else {
            return 0;
          }
        }
      });
      let tipoCategoriaPrevia = -1;
      let ordenPrevio = Date.parse("2000-01-01");
      listaConOrden.forEach(function (categoria, indice, array) {
        if (tipoCategoriaPrevia == lista_formas[categoria][1]
          && ordenPrevio > Date.parse(lista_formas[categoria][2])) {
          return;
        }
        if (lista_formas[categoria][1] != 0) {
          tipoCategoriaPrevia = lista_formas[categoria][1];
        }
        ordenPrevio = Date.parse(lista_formas[categoria][2]);

        let offsetX = 0;
        let offsetY = 0;
        if (indice > 0) {
          offsetX = Math.cos(indice * puntos) * (longuitud * 0.00001);
          offsetY = Math.sin(indice * puntos) * (longuitud * 0.00001);
        } else {
          offsetX = 0.000067060;
          offsetY = -0.00006002;
        }
        map.addIconLayerR(
                    positionFormas[0],
                    positionFormas[1],
                    offsetY,
                    offsetX,
                    elemento,
                    categoria,
                    lista_formas[categoria][0]
        );
      });
    }
  });
  map.layerAddToMapp();
  map.viewPersonaGeoreferenciada();
}

function ordenCategoria(categoriaA, categoriaB) {
  if (categoriaA[1] < categoriaB[1]) {
    return -1;
  } else if (categoriaA[1] > categoriaB[1]) {
    return 1;
  } else {
    if (categoriaA[2] < categoriaB[2]) {
      return -1;
    } else if (categoriaA[2] > categoriaB[2]) {
      return 1;
    } else {
      return 0;
    }
  }
}

