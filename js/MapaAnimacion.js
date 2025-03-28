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
                  this.#listaAnimacion = [];
                  this.#idIntervalo = null;
                  this.#tiempo=1000;
                  this.#listaUbicacion = new WeakMap();
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
                  this.#listaAnimacion = [];
                  this.#idIntervalo = null;
                  this.#tiempo=1000;
                  this.#listaUbicacion = new WeakMap();
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
        }
        this.#tiempo = this.#tiempo * 2;
    }

    ordenFecha(personaObjectA, personaObjectB) {
        if (Date.parse(personaObjectA.fecha) < Date.parse(personaObjectB.fecha)) {
            return -1;
        } else if (Date.parse(personaObjectA.fecha) > Date.parse(personaObjectB.fecha)) {
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
            this.#idIntervalo = null;
        }
    }

    stop() {
        if (this.isAnimated()) {
            this.paused();
            super.deleteFeatures();
            this.#idIntervalo = null;
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
                  this.#listaAnimacion = [];
                  this.#idIntervalo = null;
                  this.#tiempo=1000;
                  this.#listaUbicacion = new WeakMap();
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
        }
    }

    addListaAnimacion(element) {
      this.#listaAnimacion.push(element);
    }

    animacion() {
      super.addVectorLayer();
      super.addHandlerSource();
      this.#listaAnimacion = this.#listaAnimacion.sort(this.ordenFecha)
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
