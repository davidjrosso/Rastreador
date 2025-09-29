import { MapaOl } from "./MapaOl.js";
import { MapaAnimacion } from "./MapaAnimacion.js";

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

export function initAnimation(
                     lat = null, 
                     lon = null, 
                     map = null,
                     fechaInicio = null,
                     fechaFin  = null
){
if (map === null) {
    map = new MapaAnimacion(
                     "basicMap",
                     15,
                     lat, 
                     lon,
                     fechaInicio,
                     fechaFin
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
  let row = [];
  let feature = null;
  let listaOrdenada = listReferencias.sort(ordenGeoreferencia); 
  map.addVectorLayer("personas_inicial");
  map.deleteHandlerSource();
  map.clearListaAnimacion();
  listaOrdenada.forEach(function (elemento, indice, array) {
    pos = [parseFloat(elemento.lon), parseFloat(elemento.lat)];
    let lista_formas = elemento.lista_formas_categorias;
    if (lista_formas) {
      if (indice >= 1) {
        if (posicionAnterior && (posicionAnterior[0] === pos[0] && posicionAnterior[1] === pos[1])) {
          pos = [pos[0] + ((-0.000067060) * nroLote), pos[1] + ((0.000067060) * nroLote)];
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
          offsetX = 0;
          //offsetY = -8.3;
          offsetY = 0;
          //positionFormas = positionFormas.add(-8.3, 4.5);
        }
        feature = map.addIconLayerR(
                    positionFormas[0],
                    positionFormas[1],
                    offsetY,
                    offsetX,
                    elemento,
                    categoria,
                    lista_formas[categoria][0]
        );
        row["id_persona"] = elemento.id_persona;
        row["positionFormas"] = [positionFormas[0] + offsetY,
                                 positionFormas[1] + offsetX];
        row["categoriaForma"] = categoria;
        row["color"] = lista_formas[categoria][0];
        row["fecha"] = lista_formas[categoria][2];
        row["feature"] = feature;
        map.addListaAnimacion(row);
        row = [];
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

export function animacionCalendarDeMapa(mapa) {
  if (mapa.isAnimated()) {
    mapa.setTipo("CL");
    mapa.restart();
  } else {
    mapa.deleteFeatures();
    mapa.animacionCalendar();
  }
}

export function animacionCronDeMapa(mapa) {
  if (mapa.isAnimated()) {
    mapa.setTipo("CR");
    mapa.restart();
  } else {
    mapa.deleteFeatures();
    mapa.animacionCron();
  }
}

export function animacionPaused(mapa) {
  if (mapa.isAnimated()) {
    mapa.paused();
  }
}

export function animacionStop(mapa) {
  if (mapa.isAnimated()) {
    mapa.stop();
  } else {
    $("#barra-temporal-motivos").prop("data-prev-value", 0);
    $("#barra-temporal-motivos").prop("value", 0);
  }
}

function agregadoDePacientes(map, jqxhr, textStatus, error) {
  let listReferencias = jqxhr.personas;
  let nroLote = 1;
  let pos = null;
  let posicionAnterior = null;
  let positionFormas = null;
  let offsetX = null;
  let offsetY = null;
  let feature = null;
  let listaOrdenada = listReferencias.sort(ordenGeoreferencia); 
  map.addVectorLayer("personas_paciente");
  map.addHandlerSource();
  listaOrdenada.forEach(function (elemento, indice, array) {
    pos = [parseFloat(elemento.lon), parseFloat(elemento.lat)];
    let caracter = elemento.caracter;
    if (caracter) {
      if (indice >= 1) {
        if (posicionAnterior && (posicionAnterior[0] === pos[0] && posicionAnterior[1] === pos[1])) {
          pos = [pos[0] + ((-0.000067060) * nroLote), pos[1] + ((0.000067060) * nroLote)];
          nroLote++;
        } else {
          posicionAnterior = pos;
          nroLote = 1;
        }
      } else {
        posicionAnterior = pos;
      }
      positionFormas = pos;

      offsetX = 0.000067060;
      offsetY = -0.00006002;
      feature = map.addIconLayerR(
                  positionFormas[0],
                  positionFormas[1],
                  offsetY,
                  offsetX,
                  elemento,
                  elemento.caracter,
                  elemento.simbolo
      );
    }
  });
  map.deleteHandlerSource();
  map.layerAddToMapp();
}

function errorHandler(jqxhr, textStatus, error) {
  swal({
    title: "Error en la solicitud",
    text: "Error al procesar la solicitud, comunicarse con el administrador",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  });
}

export function agregarPacientesAMapa(
                                mapa,
                                escuela,
                                motivo,
                                barrio,
                                otrainstitucion
                              ) {
  let request = null;
  let url = "../Controladores/listarPersonasMapa.php?";

  url += (escuela) ? "&id_escuela=" + escuela : "";
  url += (motivo) ? "&id_motivo=" + motivo : "";
  url += (barrio) ? "&id_barrio=" + barrio : "";
  url += (otrainstitucion) ? "&id_otra_institucion=" + otrainstitucion : "";

  request = $.ajax({
                url: url,
                async: true,
                success: agregadoDePacientes.bind(null, mapa),
                error: errorHandler
                });
}