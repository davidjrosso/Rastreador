import { MapaOl } from "./MapaOl.js";

export class MapaAnimacion extends MapaOl {
    #listaAnimacion=[];
    #listaUbicacion = new WeakMap();
    #idIntervalo;
    #tiempo=1000;
    #ind=0;
    #fechaInicio;
    #fechaFin;
    #fechaIndice;
    #cronometro;
    #tipo;

    constructor(
        target,
        zoom = null,
        lat = null,
        lon = null,
        fechaInicio = null,
        fechaFin = null
    ) {
        super(
            target,
            zoom,
            lat,
            lon
        );
        let cronometro = Date.parse(fechaInicio);
        this.#listaUbicacion = new WeakMap();
        this.#fechaInicio = Date.parse(fechaInicio);
        this.#fechaIndice = Date.parse(fechaInicio);
        this.#fechaFin = Date.parse(fechaFin);
        this.#cronometro = new Date(cronometro);
        this.#tipo = "CL";
    }

    incrementar() {
        if (this.#idIntervalo) {
            clearInterval(this.#idIntervalo);
            let lengList = this.#listaAnimacion.length;
            const superAddIconLayerAnimacion = super.addIconLayerAnimacion.bind(this);
            this.tiempo= this.#tiempo / 2;
            this.#idIntervalo = setInterval(function () {
              if (this.#tipo == "CR") {
                if (this.#fechaInicio <= this.getFechaIndice() 
                  && this.#fechaFin > this.getFechaIndice()) {
                    if (lengList > this.#ind) {
                      for (let i = this.#ind; i < lengList; i++) {
                        let fecha = Date.parse(this.#listaAnimacion[this.#ind]["fecha"]);
                        if (fecha == this.getFechaIndice()) {
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
                        } else {
                          break;
                        }
                      }
                    }
                }
                this.incrementFechaIndice();
              } else {
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
                  this.setFechaIndice(this.#listaAnimacion[this.#ind]["fecha"]);
                }
              }
              this.updateCronometro();
              $("#digit-anio").text(this.getCronometroAnio());
              $("#digit-mes").text(this.getCronometroMes());
              $("#digit-dia").text(this.getCronometroDia());
            }.bind(this), this.#tiempo);

        } else {
            this.#ind = 0;
            clearInterval(this.#idIntervalo);
            this.#idIntervalo = null;
            this.#listaAnimacion = [];
            this.#idIntervalo = null;
            this.#tiempo=1000;
            this.#listaUbicacion = new WeakMap();
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
                if (this.#tipo == "CR") {
                  if (this.#fechaInicio <= this.getFechaIndice() 
                    && this.#fechaFin > this.getFechaIndice()) {
                      if (lengList > this.#ind) {
                        for (let i = this.#ind; i < lengList; i++) {
                          let fecha = Date.parse(this.#listaAnimacion[this.#ind]["fecha"]);
                          if (fecha == this.getFechaIndice()) {
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
                          } else {
                            break;
                          }
                        }
                      }
                  }
                  this.incrementFechaIndice();
                } else {
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
                    this.setFechaIndice(this.#listaAnimacion[this.#ind]["fecha"]);
                  }
                }
            this.updateCronometro();
            $("#digit-anio").text(this.getCronometroAnio());
            $("#digit-mes").text(this.getCronometroMes());
            $("#digit-dia").text(this.getCronometroDia());
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
        let isAnimation = (this.#idIntervalo) ? true : false;
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
            this.#listaAnimacion = [];
            this.#listaUbicacion = new WeakMap();
            this.#tiempo=1000;
            this.#ind=0;
            $("#cronometro").css("display", "none");
            $("#boton-calendario").css("display", "none");
            $("#boton-cron").css("display", "none");
        }
    }

    restart() {
        if (this.isAnimated()) {
            let lengList = this.#listaAnimacion.length;
            clearInterval(this.#idIntervalo);
            const superAddIconLayerAnimacion = super.addIconLayerAnimacion.bind(this);
            if (this.#tipo == "CR") {
              $("#boton-calendario").css("display", "inline-block");
              $("#boton-cron").css("display", "none");
            } else {
              $("#boton-calendario").css("display", "none");
              $("#boton-cron").css("display", "inline-block");
            }
            this.#idIntervalo = setInterval(function () {
                if (this.#tipo == "CR") {
                  if (this.#fechaInicio <= this.getFechaIndice() 
                    && this.#fechaFin > this.getFechaIndice()) {
                      if (lengList > this.#ind) {
                        for (let i = this.#ind; i < lengList; i++) {
                          let fecha = Date.parse(this.#listaAnimacion[this.#ind]["fecha"]);
                          if (fecha == this.getFechaIndice()) {
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
                          } else {
                            break;
                          }
                        }
                      }
                  }
                  this.incrementFechaIndice();
                } else {
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
                    this.setFechaIndice(this.#listaAnimacion[this.#ind]["fecha"]);
                  }
                }
                this.updateCronometro();
                $("#digit-anio").text(this.getCronometroAnio());
                $("#digit-mes").text(this.getCronometroMes());
                $("#digit-dia").text(this.getCronometroDia());
            }.bind(this), this.#tiempo);
        }
    }

    addListaAnimacion(element) {
      this.#listaAnimacion.push(element);
    }

    getListaAnimacion(element) {
      this.#listaAnimacion.find(element);
    }

    incrementFechaIndice() {
      this.#fechaIndice += (1000 * 60 * 60 * 24);
    }

    getFechaIndice() {
      return this.#fechaIndice;
    }

    setTipo(tipo) {
      this.#tipo = tipo;
    }

    getTipo() {
      return this.#tipo;
    }


    setFechaIndice(fecha) {
      this.#fechaIndice = Date.parse(fecha);
    }

    getCronometroAnio() {
      let anio = this.#cronometro.getFullYear();
      return anio;
    }

    getCronometroMes() {
      let mes = this.#cronometro.getMonth() + 1;
      let mesRet = (mes < 10) ? "0" + mes: mes;
      return mesRet;
    }

    getCronometroDia() {
      let dia = this.#cronometro.getDate();
      let diaRet = (dia < 10) ? "0" + dia: dia;
      return diaRet;
    }

    updateCronometro() {
      this.#cronometro = new Date(this.getFechaIndice());
    }

    animacionCron() {
      super.addVectorLayer();
      super.addHandlerSource(); 
      this.#listaAnimacion = this.#listaAnimacion.sort(this.ordenFecha);
      let lengList = this.#listaAnimacion.length;
      this.setTipo("CR");
      $("#cronometro").css("display", "inline-flex");
      $("#boton-calendario").css("display", "inline-block");
      $("#boton-cron").css("display", "none");
      const superAddIconLayerAnimacion = super.addIconLayerAnimacion.bind(this);
      this.#idIntervalo = setInterval(function () {
        if (this.#fechaInicio <= this.getFechaIndice() 
            && this.#fechaFin > this.getFechaIndice()) {
              if (lengList > this.#ind) {
                for (let i = this.#ind; i < lengList; i++) {
                  let fecha = Date.parse(this.#listaAnimacion[this.#ind]["fecha"]);
                  if (fecha == this.getFechaIndice()) {
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
                  } else {
                    break;
                  }
                }
              }
        }
        this.incrementFechaIndice();
        this.updateCronometro();
        $("#digit-anio").text(this.getCronometroAnio());
        $("#digit-mes").text(this.getCronometroMes());
        $("#digit-dia").text(this.getCronometroDia());
      }.bind(this), this.#tiempo);
      super.viewPersonaGeoreferenciada();
    }

    animacionCalendar() {
        super.addVectorLayer();
        super.addHandlerSource();
        this.#listaAnimacion = this.#listaAnimacion.sort(this.ordenFecha)
        let lengList = this.#listaAnimacion.length;
        this.setTipo("CL");
        $("#cronometro").css("display", "inline-flex");
        $("#boton-calendario").css("display", "none");
        $("#boton-cron").css("display", "inline-block");
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
            this.setFechaIndice(this.#listaAnimacion[this.#ind]["fecha"]);
            this.updateCronometro();
          }

          $("#digit-anio").text(this.getCronometroAnio());
          $("#digit-mes").text(this.getCronometroMes());
          $("#digit-dia").text(this.getCronometroDia());
        }.bind(this), this.#tiempo);
        super.viewPersonaGeoreferenciada();
      }

}
