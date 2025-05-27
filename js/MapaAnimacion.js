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
    #estado = 0;

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

    incrementar(incremento) {
        if (this.isAnimated()) {
            if (this.#idIntervalo) {
              clearInterval(this.#idIntervalo);
            }
            let rango = this.#fechaFin - this.#fechaInicio;
            let lengList = this.#listaAnimacion.length;
            const superAddIconLayerAnimacion = super.addIconLayerAnimacion.bind(this);
            this.#tiempo= this.#tiempo / incremento;
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
                          $("#barra-temporal-motivos").prop(
                                                            "data-prev-value", 
                                                            ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                          );
                          $("#barra-temporal-motivos").prop(
                                                            "value",
                                                            ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                          );
                          this.#ind++;
                        } else {
                          break;
                        }
                      }
                    } else {
                      this.#ind = 0;
                      clearInterval(this.#idIntervalo);
                      this.#idIntervalo = null;
                      this.#estado = 0;
                      this.#fechaIndice = this.#fechaInicio;
                      this.#tiempo=1000;
                      $("#barra-temporal-motivos").prop("data-prev-value", 100);
                      $("#barra-temporal-motivos").prop("value", 100);
                    }
                }
                this.incrementFechaIndice();
              } else {
                if (lengList <= this.#ind) {
                  this.#ind = 0;
                  clearInterval(this.#idIntervalo);
                  this.#idIntervalo = null;
                  this.#estado = 0;
                  this.#tiempo = 1000;
                  $("#barra-temporal-motivos").prop("data-prev-value", 100);
                  $("#barra-temporal-motivos").prop("value", 100);
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
                  this.setFechaIndice(this.#listaAnimacion[this.#ind]["fecha"]);
                  $("#barra-temporal-motivos").prop(
                                                    "data-prev-value", 
                                                    ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                  );
                  $("#barra-temporal-motivos").prop(
                                                    "value",
                                                    ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                  );
                  this.#ind++;
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
            this.#estado = 0;
            this.#tiempo = 1000;
            this.#fechaIndice = this.#fechaInicio;
            this.#listaUbicacion = new WeakMap();
            this.#tiempo = this.#tiempo / incremento;
            $("#barra-temporal-motivos").prop("data-prev-value", 100);
            $("#barra-temporal-motivos").prop("value", 100);
        }
    }

    decrementar(decremento) {
        if (this.isAnimated()) {
            if (this.#idIntervalo) {
              clearInterval(this.#idIntervalo);
            }
            let rango = this.#fechaFin - this.#fechaInicio;
            let lengList = this.#listaAnimacion.length;
            const superAddIconLayerAnimacion = super.addIconLayerAnimacion.bind(this);
            this.#tiempo = this.#tiempo * decremento;
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
                            $("#barra-temporal-motivos").prop(
                                                              "data-prev-value", 
                                                              ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                            );
                            $("#barra-temporal-motivos").prop(
                                                              "value",
                                                              ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                            );
                            this.#ind++;
                          } else {
                            break;
                          }
                        }
                      } else {
                        this.#ind = 0;
                        clearInterval(this.#idIntervalo);
                        this.#idIntervalo = null;
                        this.#tiempo = 1000;
                        this.#estado = 0;
                        this.#fechaIndice = this.#fechaInicio;
                        $("#barra-temporal-motivos").prop("data-prev-value", 100);
                        $("#barra-temporal-motivos").prop("value", 100);
                      }
                  }
                  this.incrementFechaIndice();
                } else {
                  if (lengList <= this.#ind) {
                    this.#ind = 0;
                    clearInterval(this.#idIntervalo);
                    this.#idIntervalo = null;
                    this.#tiempo = 1000;
                    this.#estado = 0;
                    $("#barra-temporal-motivos").prop("data-prev-value", 100);
                    $("#barra-temporal-motivos").prop("value", 100);
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
                    this.setFechaIndice(this.#listaAnimacion[this.#ind]["fecha"]);
                    $("#barra-temporal-motivos").prop(
                                                      "data-prev-value", 
                                                      ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                    );
                    $("#barra-temporal-motivos").prop(
                                                      "value",
                                                      ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                    );
                    this.#ind++;
                  }
                }
            this.updateCronometro();
            $("#digit-anio").text(this.getCronometroAnio());
            $("#digit-mes").text(this.getCronometroMes());
            $("#digit-dia").text(this.getCronometroDia());
          }.bind(this), this.#tiempo);
        } else {
          this.#tiempo = this.#tiempo * decremento;
        }
    }

    decAnimacion(decremento) {
        if (this.isAnimated()) {
            if (this.#idIntervalo) {
              clearInterval(this.#idIntervalo);
            }
            let rango = this.#fechaFin - this.#fechaInicio;
            let lengList = this.#listaAnimacion.length;
            const superRevIconLayerAnimacion = super.revIconLayerAnimacion.bind(this);
            this.#tiempo = this.#tiempo * decremento;
            this.#idIntervalo = setInterval(function () {
                if (this.#tipo == "CR") {
                  if (this.#fechaInicio <= this.getFechaIndice() 
                    && this.#fechaFin > this.getFechaIndice()) {
                      if (lengList > this.#ind && this.#ind >= 0) {
                        for (let i = this.#ind; i >= 0; i--) {
                          let fecha = Date.parse(this.#listaAnimacion[this.#ind - 1]["fecha"]);
                          if (fecha == this.getFechaIndice()) {
                            superRevIconLayerAnimacion(
                              this.#listaAnimacion[this.#ind - 1]["positionFormas"][0],
                              this.#listaAnimacion[this.#ind - 1]["positionFormas"][1],
                              0,
                              0,
                              this.#listaAnimacion[this.#ind - 1]["feature"]
                            );
                            $("#barra-temporal-motivos").prop(
                                                              "data-prev-value", 
                                                              ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                            );
                            $("#barra-temporal-motivos").prop(
                                                              "value",
                                                              ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                            );
                            this.#ind--;
                          } else {
                            break;
                          }
                        }
                      } else {
                        this.#ind = 0;
                        clearInterval(this.#idIntervalo);
                        this.#idIntervalo = null;
                        this.#tiempo = 1000;
                        this.#estado = 0;
                        this.#fechaIndice = this.#fechaInicio;
                        $("#barra-temporal-motivos").prop("data-prev-value", 0);
                        $("#barra-temporal-motivos").prop("value", 0);
                      }
                  }
                  this.decrementFechaIndice();
                } else {
                  if (this.#ind <= 0) {
                    this.#ind = 0;
                    clearInterval(this.#idIntervalo);
                    this.#idIntervalo = null;
                    this.#tiempo = 1000;
                    this.#estado = 0;
                    $("#barra-temporal-motivos").prop("data-prev-value", 0);
                    $("#barra-temporal-motivos").prop("value", 0);
                  } else {
                    superRevIconLayerAnimacion(
                      this.#listaAnimacion[this.#ind - 1]["positionFormas"][0],
                      this.#listaAnimacion[this.#ind - 1]["positionFormas"][1],
                      0,
                      0,
                      this.#listaAnimacion[this.#ind - 1]["feature"]
                    );
                    this.setFechaIndice(this.#listaAnimacion[this.#ind - 1]["fecha"]);
                    $("#barra-temporal-motivos").prop(
                                                      "data-prev-value", 
                                                      ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                    );
                    $("#barra-temporal-motivos").prop(
                                                      "value",
                                                      ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                    );
                    this.#ind--;
                  }
                }
            this.updateCronometro();
            $("#digit-anio").text(this.getCronometroAnio());
            $("#digit-mes").text(this.getCronometroMes());
            $("#digit-dia").text(this.getCronometroDia());
          }.bind(this), this.#tiempo);
        } else {
          this.#tiempo = this.#tiempo * decremento;
        }
    }

    dec(fecha) {
        if (this.#idIntervalo) {
          clearInterval(this.#idIntervalo);
        }
        const superRevIconLayerAnimacion = super.revIconLayerAnimacion.bind(this);
        let lengList = this.#listaAnimacion.length;        
        this.#fechaIndice = this.#fechaInicio + (this.#fechaFin - this.#fechaInicio) * (fecha / 100);
        for (let i = this.#ind; i >= 0; i--) {
          let fechaMovimiento = null;
          if (!this.#listaAnimacion[this.#ind]) {
            this.#ind--;
            continue;
          }
          fechaMovimiento = Date.parse(this.#listaAnimacion[this.#ind]["fecha"]);
          if (this.getFechaIndice() <= fechaMovimiento) {
            superRevIconLayerAnimacion(
              this.#listaAnimacion[this.#ind]["positionFormas"][0],
              this.#listaAnimacion[this.#ind]["positionFormas"][1],
              0,
              0,
              this.#listaAnimacion[this.#ind]["feature"]
            );
            this.#ind--;
          } else {
            break;
          }
        }
        this.updateCronometro();
        $("#digit-anio").text(this.getCronometroAnio());
        $("#digit-mes").text(this.getCronometroMes());
        $("#digit-dia").text(this.getCronometroDia());
    }

    incr(fecha) {
        if (this.isAnimated()) {
          if (this.#idIntervalo) {
            clearInterval(this.#idIntervalo);
          }
          let lengList = this.#listaAnimacion.length;
          const superAddIconLayerAnimacion = super.addIconLayerAnimacion.bind(this);
          this.#fechaIndice = this.#fechaInicio + (this.#fechaFin - this.#fechaInicio) * (fecha / 100);
          for (let i = this.#ind; i <= lengList; i++) {
            if (!this.#listaAnimacion[this.#ind - 1]) {
              this.#ind++;
              continue;
            }
            let fechaMotivo = Date.parse(this.#listaAnimacion[this.#ind - 1]["fecha"]);
            if (fechaMotivo <= this.getFechaIndice()) {
              superAddIconLayerAnimacion( 
                this.#listaAnimacion[this.#ind - 1]["positionFormas"][0],
                this.#listaAnimacion[this.#ind - 1]["positionFormas"][1],
                0,
                0,
                this.#listaAnimacion[this.#ind - 1]["id_persona"],
                this.#listaAnimacion[this.#ind - 1]["categoriaForma"],
                this.#listaAnimacion[this.#ind - 1]["color"]
              );
              this.#ind++;
            } else {
              break;
            }
          }
          this.updateCronometro();
          $("#digit-anio").text(this.getCronometroAnio());
          $("#digit-mes").text(this.getCronometroMes());
          $("#digit-dia").text(this.getCronometroDia());
        }
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
        let isAnimation = this.#estado > 0;
        return isAnimation;
    }

    paused() {
        if (this.#idIntervalo) {
            clearInterval(this.#idIntervalo);
            this.#idIntervalo = null;
            this.#estado = 2;
            this.#tiempo=1000;
        }
    }

    stop() {
        if (this.isAnimated()) {
            this.paused();
            super.deleteFeatures();
            this.#listaAnimacion = [];
            this.#listaUbicacion = new WeakMap();
            this.#tiempo = 1000;
            this.#fechaIndice = this.#fechaInicio;
            this.#ind = 0;
            this.#estado = 0;
            this.#fechaIndice = this.#fechaInicio;
            $("#cronometro").css("display", "none");
            $("#boton-calendario").css("display", "none");
            $("#boton-cron").css("display", "none");
        }
        $("#barra-temporal-motivos").prop("data-prev-value", 0);
        $("#barra-temporal-motivos").prop("value", 0);
    }

    restart() {
        if (this.isAnimated()) {
            let lengList = this.#listaAnimacion.length;
            this.#estado = 1;
            let rango = this.#fechaFin - this.#fechaInicio;
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
                            $("#barra-temporal-motivos").prop(
                                                              "data-prev-value", 
                                                              ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                            );
                            $("#barra-temporal-motivos").prop(
                                                              "value",
                                                              ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                            );
                            this.#ind++;
                          } else {
                            break;
                          }
                        }
                      } else {
                        this.#ind = 0;
                        clearInterval(this.#idIntervalo);
                        this.#idIntervalo = null;
                        this.#estado = 0;
                        this.#tiempo = 1000;
                        $("#barra-temporal-motivos").prop("data-prev-value", 100);
                        $("#barra-temporal-motivos").prop("value", 100);
                      }
                  }
                  this.incrementFechaIndice();
                } else {
                  if (lengList <= this.#ind) {
                    this.#ind = 0;
                    clearInterval(this.#idIntervalo);
                    this.#idIntervalo = null;
                    this.#estado = 0;
                    this.#tiempo = 1000;
                    this.#fechaIndice = this.#fechaInicio;
                    $("#barra-temporal-motivos").prop("data-prev-value", 100);
                    $("#barra-temporal-motivos").prop("value", 100);
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
                    this.setFechaIndice(this.#listaAnimacion[this.#ind]["fecha"]);
                    $("#barra-temporal-motivos").prop(
                                                      "data-prev-value", 
                                                      ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                    );
                    $("#barra-temporal-motivos").prop(
                                                      "value",
                                                      ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                    );
                    this.#ind++;
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

    addListaUbicacion(lon, lat) {
      let clave = {lon: lat};
      let count = 0;
      if (this.#listaUbicacion.has(clave)) {
        count = this.#listaUbicacion.get(clave);
        this.#listaUbicacion.set(clave, count + 1);
      } else {
        this.#listaUbicacion.set(clave, 0);
      }
    }

    getListaAnimacion(element) {
      this.#listaAnimacion.find(element);
    }

    incrementFechaIndice() {
      this.#fechaIndice += (1000 * 60 * 60 * 24);
    }

    decrementFechaIndice() {
      this.#fechaIndice -= (1000 * 60 * 60 * 24);
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

    clearListaAnimacion() {
      if (this.#listaAnimacion) {
        this.#listaAnimacion = [];
      }
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
      this.#estado = 1;
      let rango = this.#fechaFin - this.#fechaInicio;
      this.#listaAnimacion = this.#listaAnimacion.sort(this.ordenFecha);
      let lengList = this.#listaAnimacion.length;
      this.setTipo("CR");
      $("#cronometro").css("display", "inline-flex");
      $("#boton-calendario").css("display", "inline-block");
      $("#boton-cron").css("display", "none");
      $("#barra-temporal-motivos").prop(
                                        "data-prev-value", 
                                        ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
      );
        $("#barra-temporal-motivos").prop(
                                        "value",
                                        ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
      );
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
                    $("#barra-temporal-motivos").prop(
                                                      "data-prev-value", 
                                                      ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                    );
                     $("#barra-temporal-motivos").prop(
                                                      "value",
                                                      ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
                    );
                  } else {
                    break;
                  }
                }
              } else {
                this.#ind = 0;
                clearInterval(this.#idIntervalo);
                this.#idIntervalo = null;
                this.#estado = 0;
                this.#tiempo = 1000;
                this.#fechaIndice = this.#fechaInicio;
                $("#barra-temporal-motivos").prop("data-prev-value", 100);
                $("#barra-temporal-motivos").prop("value", 100);
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
        this.#estado = 1;
        let rango = this.#fechaFin - this.#fechaInicio;
        this.#listaAnimacion = this.#listaAnimacion.sort(this.ordenFecha)
        let lengList = this.#listaAnimacion.length;
        this.setTipo("CL");
        $("#cronometro").css("display", "inline-flex");
        $("#boton-calendario").css("display", "none");
        $("#boton-cron").css("display", "inline-block");
        $("#barra-temporal-motivos").prop(
                                          "data-prev-value", 
                                          ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
        );
          $("#barra-temporal-motivos").prop(
                                          "value",
                                          ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
        );
        const superAddIconLayerAnimacion = super.addIconLayerAnimacion.bind(this);
        this.#idIntervalo = setInterval(function () {
          if (lengList <= this.#ind) {
            this.#ind = 0;
            clearInterval(this.#idIntervalo);
            this.#estado = 0;
            this.#idIntervalo = null;
            this.#fechaIndice = this.#fechaInicio;
            $("#barra-temporal-motivos").prop("data-prev-value", 100);
            $("#barra-temporal-motivos").prop("value", 100);
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

            $("#barra-temporal-motivos").prop(
                                              "data-prev-value", 
                                              ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
            );
            $("#barra-temporal-motivos").prop(
                                              "value",
                                              ((this.getFechaIndice() - this.#fechaInicio) / rango) * 100
            );

            this.setFechaIndice(this.#listaAnimacion[this.#ind]["fecha"]);
            this.updateCronometro();
            this.#ind++;
          }

          $("#digit-anio").text(this.getCronometroAnio());
          $("#digit-mes").text(this.getCronometroMes());
          $("#digit-dia").text(this.getCronometroDia());
        }.bind(this), this.#tiempo);
        super.viewPersonaGeoreferenciada();
      }

}
