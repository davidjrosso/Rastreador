import { Preferencia } from "./Preferencia.js";

let formulario = new Preferencia();
let time = null;
let idTime = null;


function buscarPersonas() {
    let xNombre = document.getElementById('SearchPersonas').value;
    let textoBusqueda = xNombre;
    let contenidosRecibidos = null;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(e) {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        contenidosRecibidos = xmlhttp.responseText;
        document.getElementById("ResultadosPersonas").innerHTML = contenidosRecibidos;
        }
    }
    xmlhttp.open('POST', 'buscarPersonas.php?valorBusqueda=' + textoBusqueda, true); // Método post y url invocada
    xmlhttp.send();
}

function buscarMotivos() {
    let xMotivo = document.getElementById('SearchMotivos1').value;
    let bodyJson = Object.fromEntries(formulario.getListaMotivos());
    let textoBusqueda = xMotivo;
    let contenidosRecibidos = null;
    let vs = $("#select-motivo1")[0].value
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(e) {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosMotivos1").innerHTML = contenidosRecibidos;
            $("div[data-id-element]").css("display", "block");
        }
    }
    xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda=' + textoBusqueda + "&vs=" + vs, true);
    xmlhttp.setRequestHeader("Content-Type", "application/json;");
    xmlhttp.send(JSON.stringify(bodyJson));
}

function buscarMotivos2() {
    let xMotivo = document.getElementById('SearchMotivos2').value;
    let bodyJson = Object.fromEntries(formulario.getListaMotivos());
    let textoBusqueda = xMotivo;
    let contenidosRecibidos = null;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(e) {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosMotivos2").innerHTML = contenidosRecibidos;
            $("div[data-id-element]").css("display", "block");
        }
    }
    xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda=' + textoBusqueda+'&number=2', true); // Método post y url invocada
    xmlhttp.setRequestHeader("Content-Type", "application/json;");
    xmlhttp.send(JSON.stringify(bodyJson));
}

function buscarMotivos3() {
    let xMotivo = document.getElementById('SearchMotivos3').value;
    let bodyJson = Object.fromEntries(formulario.getListaMotivos());
    let textoBusqueda = xMotivo;
    let contenidosRecibidos = null;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(e) {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosMotivos3").innerHTML = contenidosRecibidos;
            $("div[data-id-element]").css("display", "block");
        }
    }
    xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda=' + textoBusqueda+'&number=3', true); // Método post y url invocada
    xmlhttp.setRequestHeader("Content-Type", "application/json;");
    xmlhttp.send(JSON.stringify(bodyJson));
}

function buscarMotivos4(motivoNumero) {
    let xMotivo = document.getElementById('SearchMotivos' + motivoNumero).value;
    let bodyJson = Object.fromEntries(formulario.getListaMotivos());
    let textoBusqueda = xMotivo;
    let contenidosRecibidos = null;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosMotivos" + motivoNumero).innerHTML = contenidosRecibidos;
            $("div[data-id-element]").css("display", "block");
        }
    }
    xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda=' + textoBusqueda+'&number=' + motivoNumero, true); // Método post y url invocada
    xmlhttp.setRequestHeader("Content-Type", "application/json;");
    xmlhttp.send(JSON.stringify(bodyJson));
}

function buscarMotivosGeneral(id_Motivo, bodyJson) {
    let xMotivo = document.getElementById("SearchMotivos" + id_Motivo).value;
    let textoBusqueda = xMotivo;
    let contenidosRecibidos = null;
    let vs = $("#select-motivo" + id_Motivo)[0].value;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosMotivos" + id_Motivo).innerHTML = contenidosRecibidos;
            $("div[data-id-element]").css("display", "block");
            $("button[data-motivo-select]").on("click", function (e) {
                let nombreMotivo = $(this).attr("data-nombre-mv");
                let idMotivo = $(this).attr("data-id-mv");
                formulario.addMultipleMotivo(nombreMotivo, idMotivo, $(this).get(0));
            });
        }
    }
    xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda=' + textoBusqueda + '&number=' + id_Motivo + "&vs=" + vs, true); // Método post y url invocada
    xmlhttp.send(JSON.stringify(bodyJson));
}

function buscarCategorias() {
    let xCategoria = document.getElementById('SearchCategorias').value;
    let textoBusqueda = xCategoria;
    let xmlhttp = new XMLHttpRequest();
    let contenidosRecibidos = null;
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosCategorias").innerHTML = contenidosRecibidos;
            $("div[data-id-element]").css("display", "block");
            $("button[data-categoria-select]").on("click", function (e) {
                let nombreCategoria = $(this).attr("data-nombre-ca");
                let idCategoria = $(this).attr("data-id-ca");
                formulario.addMultipleCategoria(nombreCategoria, idCategoria, $(this).get(0));
            });
        }
    }
    xmlhttp.open('POST', 'buscarCategorias.php?valorBusqueda=' + textoBusqueda, true); // Método post y url invocada
    xmlhttp.send();
}

export function seleccionPersona(xNombre, xID) {
    let Persona = document.getElementById("Persona");
    let ID_Persona = document.getElementById("ID_Persona");
    Persona.innerHTML = "<p>" + xNombre + "<button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalPersona'><i class='fa fa-cog text-secondary'></i></button></p>";
    ID_Persona.setAttribute('value' , xID);
    /*
    let BtnBarrios = document.getElementById("agregarBarrio");
    BtnBarrios.setAttribute('disabled', true);      
    let SelMostrar = document.getElementById("inpMostrar");
    SelMostrar.setAttribute('disabled', true);
    */
}

function seleccionMotivo(xMotivo, xID, xNumber) {
    if (xNumber > 1) {
        let Motivo = document.getElementById("Motivo" + xNumber);
        let ID_Motivo = document.getElementById("ID_Motivo" + xNumber);
        Motivo.innerHTML = "";
        Motivo.innerHTML = "<p>" + xMotivo + "</p>";
        ID_Motivo.setAttribute('value', xID);
        } else {
        let Motivo = document.getElementById("Motivo");
        let ID_Motivo = document.getElementById("ID_Motivo");
        Motivo.innerHTML = "";
        Motivo.innerHTML = "<p>" + xMotivo + "</p>";
        ID_Motivo.setAttribute('value' , xID);
    }
}

function cambiarConfig() {
    let ID_Config = document.getElementById('ID_Config');
    let formatConfig = document.getElementById('formatConfig');
    ID_Config.value = formatConfig.value;
}

function toastMessage(val) {
    let edadHasta = $("#Edad_Hasta").prop("value");
    let edadDesde = $("#Edad_Desde").prop("value");
    let mesesDesde = $("#Meses_Desde").prop("value");
    let mesesHasta = $("#Meses_Hasta").prop("value");
    let dato = null;
    if (edadHasta && val == "años") {
        dato = edadHasta + " años y 364 días ";
    }
    if (mesesHasta && val == "meses") {
        dato = mesesHasta + " meses y x dias";
    }
    return dato;
}


function disabledMeses(xBool) {
    meses_desde = document.getElementById('Meses_Desde');
    meses_hasta = document.getElementById('Meses_Hasta');

    if(xBool) {
        meses_desde.setAttribute('disabled', true);
        meses_hasta.setAttribute('disabled', true);
    } else {
        meses_desde.removeAttribute('disabled');
        meses_hasta.removeAttribute('disabled');
    }
}

function habilitarMeses(xElemento) {
    let edadHasta = $("#Edad_Hasta");
    let mesesDesde = $("#Meses_Desde");
    let mesesHasta = $("#Meses_Hasta");
    let valueElem = xElemento.value;
    let idInput = xElemento.id;
    if (idInput == "Edad_Desde") {
        if (valueElem === "") {
            mesesDesde.prop("readonly", false);
            mesesDesde.val("");
            mesesHasta.prop("readonly", false);
            edadHasta.prop("readonly", false);
        } else {
            mesesDesde.prop("readonly", true);
            edadHasta.prop("readonly", false);
            mesesDesde.val("0");
        }
    }
}

function habilitarEdad(xElemento) {
    let edadHasta = $("#Edad_Hasta");
    let valueElem = xElemento.value;
    let idInput = xElemento.id;
    if (idInput == "Meses_Desde") {
        if (valueElem === "") {
            edadHasta.prop("readonly", false);
            edadHasta.val("");
        } else {
            edadHasta.prop('readonly', true);
            edadHasta.val("");
        }
    }
}

function buscarCalles() {
    let xNombre = document.getElementById('SearchCalle').value;
    let textoBusqueda = xNombre;
    let contenidosRecibidos = null;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(e) {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosCalles").innerHTML = contenidosRecibidos;
            $("button[data-seleccion-calle]").on("click", function (e) {
                seleccionCalle($(this).attr("data-nombre-calle"), $(this).attr("data-id-calle"));
            });
        }
    }
    xmlhttp.open('POST', 'buscarCalle.php?valorBusqueda=' + textoBusqueda, true); // Método post y url invocada
    xmlhttp.send();
}

function seleccionCalle(xNombre, xID) {
    let BotonModalPersona = document.getElementById("BotonModalDireccion_1");
    let calle = document.getElementById("Calle");
    BotonModalPersona.innerHTML = "";
    BotonModalPersona.innerHTML = xNombre;
    calle.setAttribute('value' , xID);
    let nro = $("#NumeroDeCalle").val();
    if (nro && map) {
        $("#mapa-sig").prop('disabled', false);
        map.addPersonMapAddress(
                                xNombre,
                                nro,
                                xID
                                );
    }
}

function seleccionCategoria(xCategoria, xID) {
    let Categoria = document.getElementById("Categoria");
    let ID_Categoria = document.getElementById("ID_Categoria");
    Categoria.innerHTML = "";
    Categoria.innerHTML = "<p>" + xCategoria+"</p>";
    ID_Categoria.setAttribute('value' , xID);
}

export function seleccionMotivoInicial(descripcionMotivo, idMotivo) {
    if (!listaMotivos.has(descripcionMotivo) && (listaMotivos.size <= 4)) {
        listaMotivos.set(descripcionMotivo, idMotivo);
    }
    formulario.seleccionMultipleMotivo();
}


$(function() {
    let date_input=$('input[name="Fecha_Desde"]');
    let container1=$('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    date_input.datepicker({
        format: 'dd/mm/yyyy',
        container: container1,
        todayHighlight: true,
        autoclose: true,
        closeText: 'Cerrar',
        days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
        daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        today: "Hoy",
        monthsTitle: "Meses",
        clear: "Borrar",
        weekStart: 1,
    });
    date_input.on("change", (e) => $("#inicial-movimiento-check").val(""));
    let date_input2 =$('input[name="Fecha_Hasta"]');
    let container2=$('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    date_input2.datepicker({
        format: 'dd/mm/yyyy',
        container: container2,
        todayHighlight: true,
        autoclose: true,
        closeText: 'Cerrar',
        days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
        daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        today: "Hoy",
        monthsTitle: "Meses",
        clear: "Borrar",
        weekStart: 1,
    });
    date_input2.on("change", (e) => $("#fin-movimiento-check").val(""))

    $("#bn-filtro-dato").on("click", function (e) {
        formulario.datosFormulario();
        $("#text-filtro").val("");
        $("#save-data").toggle();
        $("#send-admin").toggle();
    });

    $("#cancel-data").on("click", function (e) {
        $(this).toggle();
    });

    $("#send-admin").on("click", function (e) {
        formulario.sendRequestPreferencia();
    });

    $("button[data-mod-filtro-id]").on("click", function (e) {
        formulario.sendRequestModificarPreferencia($(this).attr("data-mod-filtro-id"));
    });

    $("button[data-del-filtro-id]").on("click", function (e) {
        formulario.sendRequestDelPreferencia($(this).attr("data-del-filtro-id"));
    });

    $("button[data-sel-filtro-id]").on("click", function (e) {
        formulario.sendRequestSeleccionPreferencia($(this).attr("data-sel-filtro-id"));
    });

    $("#inpMostrar").on("change", function (e) {
        controlMovimiento(this);
    });

    $("#Edad_Hasta").on("mouseenter", function (e) {
        let val = $(this).val();
        if (val) {
        $("#edad-hasta-dato").html(toastMessage("años"));
        time = setTimeout(function (e) {
            $("#edad-hasta-toast").show();
        }, 1000);
        }
    }).on("mouseleave", function (e) {
        $("#edad-hasta-toast").hide();
        clearTimeout(time);
    }).on("input", function (e) {
        $("#edad-hasta-dato").html(toastMessage("años"));
        $("#edad-hasta-toast").show();
    });

    /*
        $("#Meses_Hasta").on("mouseenter", function () {
          let val = $(this).val();
          if (val) {
            $("#meses-hasta-dato").html(toastMessage("meses"));
            time = setTimeout(function () {
                $("#meses-hasta-toast").show();
            }, 1000);
          }
        }).on("mouseleave", function () {
          $("#meses-hasta-toast").hide();
          clearTimeout(time);
        }).on("input", function () {
          $("#meses-hasta-dato").html(toastMessage("meses"));
          $("#meses-hasta-toast").show();
        });


        $("#liveToast").on("click", function (e) {
          $(this).hide();
          modalCargaDeMovimiento();
        });

        $("#width-display").prop("value", window.screen.availWidth);

        function resetearForm() {
        swal({
            title: "¿Está seguro?",
            text: "¿Seguro de querer resetear el formulario?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
            reiniciarFormulario();
            }
        });
        }

        function tomarElemento(xID){
            return document.getElementById(xID);
        }

        function crearElemento(xTipo){
            return document.createElement(xTipo);
        }

        function agregarAtributoxElemento(xElemento,xAtributo,xValue){
            xElemento.setAttribute(xAtributo,xValue);
        }

        function agregarEtiqueta(xElemento,xEtiqueta){
            xElemento.innerHTML = xEtiqueta;
        }

        function resetearValorElemento(xID){
            document.getElementById(xID).value = "";
        }

        function resetearValorSelect(xID){
            document.getElementById(xID).selectedIndex = 0;
        }

        function resetearValorDiv(xDiv){
            xDiv.innerHTML = "";
        }

        function agregarElementoxDiv(xDiv,xElemento){
            xDiv.appendChild(xElemento);
        }



        function reiniciarFormulario(){
            //RESETEANDO CAMPO FECHA
            resetearValorElemento("Fecha_Desde");        
            resetearValorElemento("Fecha_Hasta");  
            var fechaDesde = tomarElemento("Fecha_Desde");      
            var fechaHasta = tomarElemento("Fecha_Hasta");
            fechaDesde.value = "<?php echo implode("/", array_reverse(explode("-",date('Y-m-d',strtotime(date('Y-m-d')."- 1 year"))))); ?>";
            fechaHasta.value = "<?php echo implode("/", array_reverse(explode("-",date('Y-m-d')))); ?>";
            //RESETEANDO BOTON PERSONA
            var btnPersona = crearElemento("button");
            agregarAtributoxElemento(btnPersona,"type","button");
            agregarAtributoxElemento(btnPersona,"class","btn btn-lg btn-primary btn-block");
            agregarAtributoxElemento(btnPersona,"data-toggle","modal");
            agregarAtributoxElemento(btnPersona,"data-target","#ModalPersona");        
            agregarEtiqueta(btnPersona,"Seleccione una Persona");        
            var div_btnPersona = tomarElemento("Persona");
            resetearValorDiv(div_btnPersona);        
            agregarElementoxDiv(div_btnPersona,btnPersona);  
            //RESETANDO CAMPOS
            resetearValorElemento("Edad_Desde"); 
            resetearValorElemento("Edad_Hasta"); 
            resetearValorElemento("Domicilio"); 
            resetearValorElemento("manzana"); 
            resetearValorElemento("lote"); 
            resetearValorElemento("familia");       
            resetearValorElemento("Nro_Carpeta"); 
            resetearValorElemento("Nro_Legajo"); 
            resetearValorSelect("ID_Escuela");
            resetearValorElemento("Trabajo"); 
            //RESETEANDO BOTON SELECCIONE UN MOTIVO 1
            var btnMotivo_1 = crearElemento("button");
            agregarAtributoxElemento(btnMotivo_1,"type","button");
            agregarAtributoxElemento(btnMotivo_1,"class","btn btn-lg btn-primary btn-block");
            agregarAtributoxElemento(btnMotivo_1,"data-toggle","modal");
            agregarAtributoxElemento(btnMotivo_1,"data-target","#ModalMotivo");        
            agregarEtiqueta(btnMotivo_1,"Seleccione Motivo");        
            var div_btnMotivo_1 = tomarElemento("Motivo");
            resetearValorDiv(div_btnMotivo_1);        
            agregarElementoxDiv(div_btnMotivo_1,btnMotivo_1); 
            //RESETEANDO BOTON SELECCIONE UN MOTIVO 2
            var btnMotivo_2 = crearElemento("button");
            agregarAtributoxElemento(btnMotivo_2,"type","button");
            agregarAtributoxElemento(btnMotivo_2,"class","btn btn-lg btn-primary btn-block");
            agregarAtributoxElemento(btnMotivo_2,"data-toggle","modal");
            agregarAtributoxElemento(btnMotivo_2,"data-target","#ModalMotivo2");        
            agregarEtiqueta(btnMotivo_2,"Seleccione Motivo");        
            var div_btnMotivo_2 = tomarElemento("Motivo2");
            resetearValorDiv(div_btnMotivo_2);        
            agregarElementoxDiv(div_btnMotivo_2,btnMotivo_2);  
            //RESETEANDO BOTON SELECCIONE UN MOTIVO 3
            var btnMotivo_3 = crearElemento("button");
            agregarAtributoxElemento(btnMotivo_3,"type","button");
            agregarAtributoxElemento(btnMotivo_3,"class","btn btn-lg btn-primary btn-block");
            agregarAtributoxElemento(btnMotivo_3,"data-toggle","modal");
            agregarAtributoxElemento(btnMotivo_3,"data-target","#ModalMotivo3");        
            agregarEtiqueta(btnMotivo_3,"Seleccione Motivo");        
            var div_btnMotivo_3 = tomarElemento("Motivo3");
            resetearValorDiv(div_btnMotivo_3);        
            agregarElementoxDiv(div_btnMotivo_3,btnMotivo_3);      
            //RESETEANDO BOTON SELECCIONE UNA MOTIVO 3
            var btnCategoria = crearElemento("button");
            agregarAtributoxElemento(btnCategoria,"type","button");
            agregarAtributoxElemento(btnCategoria,"class","btn btn-lg btn-primary btn-block");
            agregarAtributoxElemento(btnCategoria,"data-toggle","modal");
            agregarAtributoxElemento(btnCategoria,"data-target","#ModalCategoria");        
            agregarEtiqueta(btnCategoria,"Seleccione Categoría");       
            var div_btnCategoria = tomarElemento("Categoria");
            resetearValorDiv(div_btnCategoria);        
            agregarElementoxDiv(div_btnCategoria,btnCategoria);              
            //RESETEANDO CENTRO DE SALUD
            resetearValorSelect("ID_Centro");
            //RESETEANDO OTRAS INSTITUCIONES
            resetearValorSelect("ID_OtraInstitucion");
            //RESETEANDO MOSTRAR PERSONAS
            resetearValorSelect("inpMostrar");
        }

        function habilitar_seleccion(val) {
        // alert (val)
        if(val!=0){      
        
            if(val=="todos"){
            document.getElementById("div_manzana").hidden=false;
            document.getElementById("div_lote").hidden=false;
            document.getElementById("div_familia").hidden=false;            
            }
            else if(val=="manzana"){
            document.getElementById("div_manzana").hidden=false;  
            document.getElementById("div_lote").hidden=true;  
            document.getElementById("div_familia").hidden=true;   
            }
            else if(val=="lote"){
            document.getElementById("div_manzana").hidden=true;  
                document.getElementById("div_lote").hidden=false;  
            document.getElementById("div_familia").hidden=true;   
            
            }    
            else{
            document.getElementById("div_manzana").hidden=true;  
            document.getElementById("div_lote").hidden=true;  
            document.getElementById("div_familia").hidden=false;   
            }                             
        }

        }

    */

    $("#SearchPersonas").on("keyup", function (e) {
        buscarPersonas();
    });

    $("#SearchCalle").on("keyup", function (e) {
        buscarCalles();
    });

    $("#SearchMotivos1").on("keyup", function (e) {
        let bodyJson = Object.fromEntries(formulario.getListaMotivos());
        buscarMotivosGeneral(1, bodyJson);
    });

    $("#select-motivo1").on("input", function (e) {
        let bodyJson = Object.fromEntries(formulario.getListaMotivos());
        buscarMotivosGeneral(1, bodyJson);
    });

    $("#SearchMotivos2").on("keyup", function (e) {
        let bodyJson = Object.fromEntries(formulario.getListaMotivos());
        buscarMotivosGeneral(2, bodyJson);
    });

    $("#select-motivo2").on("input", function (e) {
        let bodyJson = Object.fromEntries(formulario.getListaMotivos());
        buscarMotivosGeneral(2, bodyJson);
    });

    $("#SearchMotivos3").on("keyup", function (e) {
        let bodyJson = Object.fromEntries(formulario.getListaMotivos());
        buscarMotivosGeneral(3, bodyJson);
    });

    $("#select-motivo3").on("input", function (e) {
        let bodyJson = Object.fromEntries(formulario.getListaMotivos());
        buscarMotivosGeneral(3, bodyJson);
    });

    $("#SearchMotivos3").on("keyup", function (e) {
        let bodyJson = Object.fromEntries(formulario.getListaMotivos());
        buscarMotivosGeneral(3, bodyJson);
    });

    $("#select-motivo4").on("input", function (e) {
        let bodyJson = Object.fromEntries(formulario.getListaMotivos());
        buscarMotivosGeneral(4, bodyJson);
    });

    $("#SearchMotivos4").on("keyup", function (e) {
        let bodyJson = Object.fromEntries(formulario.getListaMotivos());
        buscarMotivosGeneral(4, bodyJson);
    });

    $("#select-motivo5").on("input", function (e) {
        let bodyJson = Object.fromEntries(formulario.getListaMotivos());
        buscarMotivosGeneral(5, bodyJson);
    });

    $("#SearchCategorias").on("keyup", function (e) {
        buscarCategorias();
    });

    $("#Edad_Desde,  #Edad_Hasta").on("keyup", function (e) {
        habilitarMeses(e.target);
    });

    $("#Meses_Hasta, #Meses_Desde").on("keyup", function (e) {
        habilitarEdad(e.target);
    });

    $("#formatConfig").on("change", function (e) {
        cambiarConfig();
    });

    $("#agregarCategoriaID").on("click", function (e) {
        formulario.agregarCategoria();
    });

    $("#agregarMotivoID").on("click", function (e) {
        formulario.agregarMotivo();
    });

    $("#agregarResponsableID").on("click", function (e) {
        formulario.agregarResponsable();
    });

    $("#agregarBarrioID").on("click", function (e) {
        formulario.agregarBarrio();
    });

    $("#seleccionar-categorias").on("click", function (e) {
        formulario.seleccionMultipleCategoria();
    });

    $("button[data-seleccion-multiple-motivos], #seleccionar-motivo, #seleccionar-motivo2, #seleccionar-motivo3, #seleccionar-motivo4, #seleccionar-motivo5").on("click", function (e) {
        formulario.seleccionMultipleMotivo();
    });

    $("#Meses_Hasta").on("mouseenter", function (e) {
        let val = $(this).val();
        if (val) {
        $("#meses-hasta-dato").html(toastMessage("meses"));
        time = setTimeout(function () {
            $("#meses-hasta-toast").show();
        }, 1000);
        }
    }).on("mouseleave", function () {
        $("#meses-hasta-toast").hide();
        clearTimeout(time);
    }).on("input", function () {
        $("#meses-hasta-dato").html(toastMessage("meses"));
        $("#meses-hasta-toast").show();
    });

    $("#close-categorias").on("click", function (e) {
        $("#SearchCategorias").val("");
        $("#ResultadosCategorias").html("");
    });

    $("#cerrar-categorias").on("click", function (e) {
        $("#SearchCategorias").val("");
        $("#ResultadosCategorias").html("");
    });
});