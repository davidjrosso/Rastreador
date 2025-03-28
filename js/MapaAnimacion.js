import { MapaOl } from "./MapaOl.js";

export class MapaAnimacion extends MapaOl {
    #listaAnimacion=[];
    #listaUbicacion = new WeakMap();
    #idIntervalo;
    #tiempo=1000;
    #ind=0;

    constructor(
        target,
        zoom = null,
        lat = null,
        lon = null
    ) {
        super(
            target,
            zoom = null,
            lat = null,
            lon = null
        );
        this.#listaUbicacion = new WeakMap();
    }

    incrementar() {
        if (this.#idIntervalo) {
            clearInterval(this.#idIntervalo);
            let lengList = this.#listaAnimacion.length;
            const superAddIconLayerAnimacion = super.addIconLayerAnimacion.bind(this);
            this.tiempo= this.#tiempo / 2;
            this.#idIntervalo = setInterval(function () {
                if (lengList <= this.#ind) {
                  this.#ind = 0;
                  clearInterval(this.#idIntervalo);
                  this.#idIntervalo = null;
                } else {
                  superAddIconLayerAnimacion(
                    this.#listaAnimacion[this.#ind]["positionFormas"][0],
                    this.#listaAnimacion[this.#ind]["positionFormas"][1],
                    0,
                    0,
                    this.#listaAnimacion[this.#ind]["elemento"],
                    this.#listaAnimacion[this.#ind]["categoria"],
                    this.#listaAnimacion[this.#ind]["categoriaForma"]
                  );
                  this.#ind++;
                }
              }.bind(this), this.#tiempo);
        }
        this.#tiempo = this.#tiempo / 2;
    }

    decrementar() {
        if (this.#idIntervalo) {
            clearInterval(this.#idIntervalo);
            let lengList = this.#listaAnimacion.length;
            const superAddIconLayerAnimacion = super.addIconLayerAnimacion.bind(this);
            this.tiempo= this.#tiempo * 2 ;
            this.#idIntervalo = setInterval(function () {
                if (lengList <= this.#ind) {
                  this.#ind = 0;
                  clearInterval(this.#idIntervalo);
                  this.#idIntervalo = null;
                } else {
                  superAddIconLayerAnimacion(
                    this.#listaAnimacion[this.#ind]["positionFormas"][0],
                    this.#listaAnimacion[this.#ind]["positionFormas"][1],
                    0,
                    0,
                    this.#listaAnimacion[this.#ind]["elemento"],
                    this.#listaAnimacion[this.#ind]["categoria"],
                    this.#listaAnimacion[this.#ind]["categoriaForma"]
                  );
                  this.#ind++;
                }
              }.bind(this), this.#tiempo);
        }
        this.#tiempo = this.#tiempo * 2;
    }

    ordenFecha(personaObjectA, personaObjectB) {
        if (Date.parse(personaObjectA[5]) < Date.parse(personaObjectB[5])) {
            return -1;
        } else if (Date.parse(personaObjectA[5]) > Date.parse(personaObjectB[5])) {
            return 1;
        } else {
            return 0;
        }
        return 0;
    }

    isAnimated(){
        let lengthlist =  this.#listaAnimacion.length;
        let isAnimation = (this.#ind < lengthlist && this.#ind > 0) ? true : false;
        return isAnimation;
    }

    paused() {
        if (this.#idIntervalo) {
            clearInterval(this.#idIntervalo);
            this.#idIntervalo = 0;
        }
    }

    stop() {
        if (this.isAnimated()) {
            this.paused();
            super.deleteFeatures();
            this.#listaAnimacion = [];
            this.#listaUbicacion = new WeakMap();
            this.#tiempo=1000;
            this.#ind=0;
        }
    }

    restart() {
        if (this.isAnimated()) {
            let lengList = this.#listaAnimacion.length;
            const superAddIconLayerAnimacion = super.addIconLayerAnimacion.bind(this);
            this.#idIntervalo = setInterval(function () {
                if (lengList <= this.#ind) {
                  this.#ind = 0;
                  clearInterval(this.#idIntervalo);
                  this.#idIntervalo = null;
                } else {
                  superAddIconLayerAnimacion(
                    this.#listaAnimacion[this.#ind]["positionFormas"][0],
                    this.#listaAnimacion[this.#ind]["positionFormas"][1],
                    0,
                    0,
                    this.#listaAnimacion[this.#ind]["elemento"],
                    this.#listaAnimacion[this.#ind]["categoria"],
                    this.#listaAnimacion[this.#ind]["categoriaForma"]
                  );
                  this.#ind++;
                }
              }.bind(this), this.#tiempo);
        }
    }

    animacion(listReferencias) {
      let pos = null;
      let positionFormas = null;
      let motivo = {};
      super.addVectorLayer();
      super.addHandlerSource();
      listReferencias.sort(this.ordenFecha).forEach(function (elemento, indice, array) {
        pos = [parseFloat(elemento.lon), parseFloat(elemento.lat)];
        let lista_formas = elemento.lista_formas_categorias;
        if (lista_formas) {
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
            if (!this.#listaUbicacion.has(positionFormas)) {
              this.#listaUbicacion.set(positionFormas, 0);
            } else {
              this.#listaUbicacion.set(
                                       positionFormas, 
                                       this.#listaUbicacion.get(positionFormas) + 1
                                      );
            };
            motivo["positionFormas"] = positionFormas;
            motivo["offsetY"] = offsetY;
            motivo["offsetX"] = offsetX;
            motivo["elemento"] = elemento;
            motivo["categoria"] = categoria;
            motivo["categoriaForma"] = lista_formas[categoria][0];
            this.#listaAnimacion.push(motivo);
          }.bind(this));
          motivo = {};
        }
      }.bind(this));
    
      let lengList = this.#listaAnimacion.length;
      const superAddIconLayerAnimacion = super.addIconLayerAnimacion.bind(this);
      this.#idIntervalo = setInterval(function () {
        if (lengList <= this.#ind) {
          this.#ind = 0;
          clearInterval(this.#idIntervalo);
          this.#idIntervalo = null;
        } else {
          superAddIconLayerAnimacion(
            this.#listaAnimacion[this.#ind]["positionFormas"][0],
            this.#listaAnimacion[this.#ind]["positionFormas"][1],
            0,
            0,
            this.#listaAnimacion[this.#ind]["elemento"],
            this.#listaAnimacion[this.#ind]["categoria"],
            this.#listaAnimacion[this.#ind]["categoriaForma"]
          );
          this.#ind++;
        }
      }.bind(this), this.#tiempo);
      super.viewPersonaGeoreferenciada();
    }

    animacionSort(listReferencias) {
      let pos = null;
      let positionFormas = null;
      let motivo = {};
      super.addVectorLayer();
      super.addHandlerSource();
      listReferencias.sort(this.ordenFecha).forEach(function (elemento, indice, array) {
        positionFormas = [parseFloat(elemento[2]), parseFloat(elemento[1])];
        let offsetX = 0;
        let offsetY = 0;
        motivo["positionFormas"] = positionFormas;
        motivo["offsetY"] = offsetY;
        motivo["offsetX"] = offsetX;
        motivo["id_persona"] = elemento[0];
        motivo["categoriaForma"] = elemento[3];
        motivo["color"] = elemento[4];
        this.#listaAnimacion.push(motivo);
        motivo = {};
      }.bind(this));

      let lengList = this.#listaAnimacion.length;
      const superAddIconLayerAnimacion = super.addIconLayerAnimacion.bind(this);
      this.#idIntervalo = setInterval(function () {
        if (lengList <= this.#ind) {
          this.#ind = 0;
          clearInterval(this.#idIntervalo);
          this.#idIntervalo = null;
        } else {
          superAddIconLayerAnimacion(
            this.#listaAnimacion[this.#ind]["positionFormas"][0],
            this.#listaAnimacion[this.#ind]["positionFormas"][1],
            0,
            0,
            this.#listaAnimacion[this.#ind]["id_persona"],
            this.#listaAnimacion[this.#ind]["categoriaForma"],
            this.#listaAnimacion[this.#ind]["color"]
          );
          this.#ind++;
        }
      }.bind(this), this.#tiempo);
      super.viewPersonaGeoreferenciada();
    }

}
