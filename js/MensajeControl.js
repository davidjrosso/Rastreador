import swal from 'sweetalert2';
import "./MapaOl.js";

export function controlMovimiento(object) {
    if (object.value == "1") {
        swal.fire({
        title: "",
        html: "Al seleccionar la opción 'Todos' se mostrarán las personas con y sin movimiento",
        icon: "warning",
        customClass: {
            htmlContainer: "text-dialog"
        },
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: `OK`,
        cancelButtonText: `Cancel`
        }).then((selectOption) => {
            if (selectOption.isConfirmed) {
                object.value = "1";
            } else {
                object.value = "0";
            }
        });
    };
}

export function Verificar(xID) {
    swal.fire({
      title: "¿Está seguro?",
      text: "¿Seguro de querer eliminar este movimiento?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: `OK`,
      cancelButtonText: `Cancel`,
    })
    .then((selectOption) => {
        if (selectOption.isConfirmed) {
        window.location.href = 'Controladores/DeleteMovimiento.php?ID=' + xID;
      }
    });
}

export function mensajeDeProcesamiento(mensaje) {
    swal.fire(mensaje, '', 'success');
}

export function insercionDatosFormulario() {
    let check_calle = ($("#input-calle").val() != "no disponible") ? 1 : 0;
    let check_nro = ($("#input-nro").val() != "no disponible") ? 1 : 0;
    let check_barrio = ($("#control-barrio").text() != "no disponible") ? 1 : 0;
    let request = null;
    let query = "?";
    let time = null;
    if (check_calle) {
        query += "calle=" + $("#input-calle").val();
    }

    if (check_nro) {
        query += (check_calle) ? "&" : "";
        query += "nro=" + $("#input-nro").val();
    }

    if (check_barrio) {
        query += (check_calle || check_nro) ? "&" : "";
        query += "barrio=" + $("#barrio-georeferencia").text();
    }

    request = $.ajax({
        type: "POST",
        cache: false,
        url: "/Controladores/UbicacionesInformacion.php" + query,
        async: true,
        processData: false,
        contentType: false,
        success: function (response) {
            let index = null;
            if (response.id_calle) {
                if (!$("#calle_" + response.id_calle)[0]) {
                    $("#Calle").val(response.id_calle);
                    $("#BotonModalDireccion_1").text(response.nombre_calle);

                } else {
                    index = $("#calle_" + response.id_calle)[0].index;
                    $("#Calle")[0].selectedIndex = index;
                }
            }

            if (response.nro) {
                $("#NumeroDeCalle").val(response.nro);  
            }

            if (response.id_barrio) {
                index = $("#barrio_" + response.id_barrio)[0].index;
                $("#ID_Barrio")[0].selectedIndex = index;
            }
        },
        error: function (response) {
            $("#input-calle").val("Error");
            $("#input-nro").val("Error");
            $("#control-barrio").text("Error");
        }
    });

    $("#control-calle").css("display", "none");
    $("#control-nro").css("display", "none");
    $("#control-barrio").css("display", "none");
    $("#formulario-save").css("display", "none");
    $("#formulario-cancel").css("display", "none");
    $("#formulario-succes").show();
    time = setTimeout(function (e) {
        $("#formulario-succes").hide();
    }, 3000);
}

function listadoDeCallesError() {
    $("#lista-calles-georeferencia").hide();
    $("#input-calle").val("Error");
    $("#input-nro").val("0");
    $("#control-barrio").text("Error");
}

export function listadoDeCalles(mapa) {
    let lista = $("#listado-calles");
    let request = null;
    let query = "?";
    $("#lista-calles-georeferencia").show();
    query += "calle=" + $("#input-calle").val();
    request = $.ajax({
        type: "GET",
        cache: false,
        url: "/Controladores/listarCalles.php" + query,
        async: true,
        processData: false,
        contentType: false,
        success: function (response) {
            let count = 1;
            let nro = $("#input-nro").val();
            let listaLength = $("#listado-calles li").length;
            if (listaLength > 0) $("#listado-calles li").remove();
            response.forEach(element => {
                let obj = $("<li id='" + element.id_calle + "' class='dropdown-item'>" + 
                                count++ + " " + element.calle_nombre + "</li>");
                lista.append(obj);
                obj.on("click", function (e) {
                    $("#input-calle").val(element.calle_nombre);
                    $("#lista-calles-georeferencia").hide();
                    if (!isNaN(parseInt(nro))) mapa.queryDatosDomicilio();
                })
            });
        },
        error: listadoDeCallesError
    });
}

export function clearDatosFormulario() {
    $("#control-calle").css("display", "none");
    $("#control-nro").css("display", "none");
    $("#control-barrio").css("display", "none");
    $("#control-calle").prop("checked", false);
    $("#control-nro").prop("checked", false);
    $("#control-barrio").prop("checked", false);
    $("#formulario-save").css("display", "none");
    $("#formulario-cancel").css("display", "none");
    $("#formulario-succes").css("display", "none");
    $("#calle-buttom").css("display", "none");
    $("#nro-buttom").css("display", "none");
    $("#barrio-buttom").css("display", "none");
}

export function showControlFormulario() {
    $("#control-calle").show();
    $("#control-nro").show();
    $("#control-barrio").show();
    $("#formulario-save").show();
    $("#formulario-cancel").show();
    $("#calle-buttom").show();
    $("#nro-buttom").show();
    $("#barrio-buttom").show();
    $("#formulario-succes").hide();
}

export function controlMensaje(mensajeSuccess, mensajeError) {
    if (mensajeSuccess) {
        swal.fire(mensajeSuccess, '', 'success');
    }

    if (mensajeError) {
        swal.fire(mensajeError, '', 'warning');
    }
}

export function titleInstSet(e) {
    let text = e.target.value;
    let data = "valor=" + text;
    let request = $.ajax({
        type: "POST",
        cache: false,
        url: "/Controladores/modificaciontituloinst.php",
        data: data,
        contentType: "application/x-www-form-urlencoded",
        async: true,
        processData: false,
        success: function (response) {
            let index = null;
            if (response.status) {
                controlMensaje("La modificacion se realizo", null)
            } else {
                controlMensaje(null, "Error en modificacion del tit");
            }
        },
        error: function (response) {
            controlMensaje(null, "Error");
        }
    });    
}

export function VerificarCrearFiltro(xID) {
    swal.fire({
        title: "¿Está seguro?",
        text: "¿Confirma la creación de esta filtro?",
        icon: "warning",
        showCloseButton: true,
        confirmButtonColor: "#e64942",
        cancelButtonColor: "#efefef",
        cancelButtonText: '<span style="color:#555">Cancel</span>',
        showCancelButton: true,
        showConfirmButton: true
    })
    .then((willDelete) => {
        if (willDelete) {
            let url = 'preferencia/nueva_preferencia_control';
            let datos = 'id_solicitud=' + xID;
            let request = $.ajax({
                type:"POST",
                url : url,
                async: true,
                contentType: 'application/x-www-form-urlencoded',
                data: datos,
                success : function (data, status, requestHttp) {
                    let count = $("#solicitudes-nueva-preferencia tbody tr").length;
                    if (requestHttp.responseJSON.mensaje) {
                        controlMensaje(requestHttp.responseJSON.mensaje, null);
                        $(this).parent().parent().remove();
                        if (count == 1) {
                            $("#solicitudes-nueva-preferencia").remove();
                            $("#header-solicitudes-nueva-preferencia").remove();
                        }
                    } else if (requestHttp.responseJSON.mensaje_error) {
                        controlMensaje(null, requestHttp.responseJSON.mensaje_error);
                    }
                }.bind(this)
            });

    }
    });
}

export function CancelarCrearFiltro(xID) {
    swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de eliminar esta solicitud de nuevo filtro?",
        icon: "warning",
        showCloseButton: true,
        confirmButtonColor: "#e64942",
        cancelButtonColor: "#efefef",
        cancelButtonText: '<span style="color:#555">Cancel</span>',
        showCancelButton: true,
        showConfirmButton: true
    })
    .then((willDelete) => {
        if (willDelete) {
            let url = 'preferencia/eliminar_solicitud_preferencia';
            let datos = 'id_solicitud=' + xID;
            let request = $.ajax({
                type:"POST",
                url : url,
                async: true,
                contentType: 'application/x-www-form-urlencoded',
                data: datos,
                success : function (data, status, requestHttp) {
                    let count = $("#solicitudes-nueva-preferencia tbody tr").length;
                    if (requestHttp.responseJSON.mensaje) {
                        controlMensaje(requestHttp.responseJSON.mensaje, null);
                        $(this).parent().parent().remove();
                        if (count == 1) {
                            $("#solicitudes-nueva-preferencia").remove();
                            $("#header-solicitudes-nueva-preferencia").remove();
                        }
                    } else if (requestHttp.responseJSON.mensaje_error) {
                        controlMensaje(null, requestHttp.responseJSON.mensaje_error);
                    }
                }.bind(this)
            });

    }
    });
}

export function VerificarEliminarFiltro(xID) {
    swal.fire({
        title: "¿Está seguro?",
        text: "¿Confirma la creación de esta filtro?",
        icon: "warning",
        showCloseButton: true,
        confirmButtonColor: "#e64942",
        cancelButtonColor: "#efefef",
        cancelButtonText: '<span style="color:#555">Cancel</span>',
        showCancelButton: true,
        showConfirmButton: true
    })
    .then((willDelete) => {
        if (willDelete) {
            let url = 'preferencia/eliminar_preferencia_control';
            let datos = 'id_solicitud=' + xID;
            let request = $.ajax({
                type:"POST",
                url : url,
                async: true,
                contentType: 'application/x-www-form-urlencoded',
                data: datos,
                success : function (data, status, requestHttp) {
                    let count = $("#solicitudes-eliminar-preferencia tbody tr").length;
                    if (requestHttp.responseJSON.mensaje) {
                        controlMensaje(requestHttp.responseJSON.mensaje, null);
                        $(this).parent().parent().remove();
                        if (count == 1) {
                            $("#solicitudes-eliminar-preferencia").remove();
                            $("#header-solicitudes-eliminar-preferencia").remove();
                        }
                    } else if (requestHttp.responseJSON.mensaje_error) {
                        controlMensaje(null, requestHttp.responseJSON.mensaje_error);
                    }
                }.bind(this)
            });

    }
    });
}

export function CancelarEliminarFiltro(xID) {
    swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de eliminar esta solicitud de nuevo filtro?",
        icon: "warning",
        showCloseButton: true,
        confirmButtonColor: "#e64942",
        cancelButtonColor: "#efefef",
        cancelButtonText: '<span style="color:#555">Cancel</span>',
        showCancelButton: true,
        showConfirmButton: true
    })
    .then((willDelete) => {
        if (willDelete) {
            let url = 'preferencia/eliminar_solicitud_preferencia';
            let datos = 'id_solicitud=' + xID;
            let request = $.ajax({
                type:"POST",
                url : url,
                async: true,
                contentType: 'application/x-www-form-urlencoded',
                data: datos,
                success : function (data, status, requestHttp) {
                    let count = $("#solicitudes-eliminar-preferencia tbody tr").length;
                    if (requestHttp.responseJSON.mensaje) {
                        controlMensaje(requestHttp.responseJSON.mensaje, null);
                        $(this).parent().parent().remove();
                        if (count == 1) {
                            $("#solicitudes-eliminar-preferencia").remove();
                            $("#header-solicitudes-eliminar-preferencia").remove();
                        }
                    } else if (requestHttp.responseJSON.mensaje_error) {
                        controlMensaje(null, requestHttp.responseJSON.mensaje_error);
                    }
                }.bind(this)
            });

    }
    });
}

$(function (e) {
    $("button[data-tarea~='cancelar-eliminar-solicitud'").on("click", function (e) {
        let idSolicitud = $(this).attr("data-id-solicitud");
        CancelarEliminarFiltro.bind(this)(idSolicitud);
    });

    $("button[data-tarea~='confirmar-eliminar-solicitud'").on("click", function (e) {
        let idSolicitud = $(this).attr("data-id-solicitud");
        VerificarEliminarFiltro.bind(this)(idSolicitud);
    });

    $("button[data-tarea~='cancelar-nueva-solicitud'").on("click", function (e) {
        let idSolicitud = $(this).attr("data-id-solicitud");
        CancelarCrearFiltro.bind(this)(idSolicitud);
    });

    $("button[data-tarea~='confirmar-nueva-solicitud'").on("click", function (e) {
        let idSolicitud = $(this).attr("data-id-solicitud");
        VerificarCrearFiltro.bind(this)(idSolicitud);
    });

});