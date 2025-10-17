import swal from 'sweetalert2';
import XMLHttpRequest from '../node_modules/xhr2/lib/browser';

$(function() {
    controlMensaje(mensajeSuccess, mensajeError);
});

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
        window.location.href = 'delete_movimiento?ID=' + xID;
      }
    });
}

export function VerificarEliminarCentro(xID){
        swal.fire({
            title: "¿Está seguro?",
            text: "¿Seguro de querer eliminar este centro de salud?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                window.location.href = 'delete_centro_salud?ID=' + xID;
            }
        });
}

export function VerificarCategoria(xID) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer eliminar esta categoría?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                window.location.href = 'pedireliminarcategoria?ID=' + xID;
            }
        });
}

export function VerificarCreacionCategoria() {
        let form_1= document.getElementById("form_1");
        swal.fire({
          title: "¿Está seguro?",
          text: "¿Seguro de querer crear esta categoría?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((result) => {
            if (result) {
                form_1.submit();
                return true;
            } else {
                return false;
            }
        });
      }

export function VerificarDeleteBarrio(xID){
        swal.fire({
            title: "¿Está seguro?",
            text: "¿Seguro de querer eliminar este barrio?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                window.location.href = 'delete_barrio?ID='+xID;
            }
        });
}

export function VerificarDeletePersona(xID){
        swal.fire({
            title: "¿Está seguro?",
            icon: "warning",
            html: `<p style="margin-bottom:0px">¿Seguro de querer eliminar esta persona?</p>
                    <p style="margin-bottom:0px">Si se borra el registro de una persona</p>
                    <p style="margin-bottom:0px"> también se eliminan sus movimientos</p>`,
            showCloseButton: true,
            confirmButtonColor: "#e64942",
            cancelButtonColor: "#efefef",
            cancelButtonText: '<span style="color:#555">Cancel</span>',
            showCancelButton: true,
            showConfirmButton: true
        })
        .then((willDelete) => {
            if (willDelete.isConfirmed) {
                window.location.href = '/delete_persona?ID=' + xID;
            }
        });
}

function ShowModalError(){
    var modal = document.getElementById("ErrorDocumento");
    modal.style.display = "block";
    modal.innerText="El Documento ingresado ya Existe";
}

function NotShowModalError(){
    var modal = document.getElementById("ErrorDocumento");
    modal.style.display = "none";
}

export function ValidarDocumento() {
    let Documento = document.getElementById("idDocumento");
    let NroDocumento = Documento.value;
    let xmlhttp = null;
    let contenidosRecibidos = null;
 
    if (NroDocumento.toString().length < 8) {
        NotShowModalError();
        return true;
    }

    const DniNoRepetido = "<p>No hay ningún registro con ese nombre, documento o legajo</p>";
    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            contenidosRecibidos = xmlhttp.responseText;
            if(DniNoRepetido != contenidosRecibidos){ 
            Documento.value = "";
            swal.fire({
                    title: "El Documento ingresado " + NroDocumento + " ya esta registrado",
                    icon: "info",
                    text: "Por favor ingrese un Documento diferente",
                    confirmButtonText: 'OK'
                });
            }
        }
    }
    xmlhttp.open('POST', '/buscar_personas', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send('valorBusqueda=' + NroDocumento);

}

export function successHandler(jqxhr, textStatus) {
    let response = jqxhr.requestJSON;
    if (response.mensaje) {
    swal.fire({
        text: response.mensaje,
        icon: "success",
        buttons: true,
        dangerMode: true,
    });
    } else if (response.mensajeerror) {
    swal.fire({
        text: response.mensajeerror,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    });
    }
}

export function errorHandler(jqxhr, textStatus, error) {
    swal({
    title: "Error en la solicitud",
    text: "Error al procesar la solicitud, comunicarse con el administrador",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    });
}

export function VerificarUnificacion(
                                xID_Registro_1,
                                xID_Registro_2,
                                xID_TipoUnif,
                                xID_Solicitud
){
        let mensaje = null;
        let url = null;
        switch (xID_TipoUnif) {
        case 'MOTIVO':
            mensaje = "estos motivos";
            break; 
        case 'PERSONAS':
            mensaje = "estas personas";
            break; 
        case 'CENTROS SALUD':
            mensaje = "estos centros de salud";
            break; 
        case 'ESCUELAS':
            mensaje = "estas escuelas";
            break; 
        case 'BARRIOS':
            mensaje = "estos barrios";
            break; 
        case 'CATEGORIA':
            mensaje = "estas categorias";
            break; 
        case 'RESPONSABLE':
            mensaje = "estos responsables";
            break; 
        default: 
            swal.fire("Algo salio mal consulte con el equipo de desarrollo", "", "warning");
            break;
        }

        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer unificar " + mensaje + "?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            switch (xID_TipoUnif) {
            case 'MOTIVO':
                url = '../Controladores/unificarmotivos.php?ID_Motivo_1=' + xID_Registro_1 + '&ID_Motivo_2=' + xID_Registro_2 + '&ID_Solicitud=' + xID_Solicitud;
                break;
            case 'PERSONAS':
                url = '../Controladores/unificarpersonas.php?ID_Persona_1=' + xID_Registro_1 + '&ID_Persona_2=' + xID_Registro_2 + '&ID_Solicitud=' + xID_Solicitud;
                break;
            case 'CENTROS SALUD':
                url = '../Controladores/unificarcentros.php?ID_Centro_1=' + xID_Registro_1 + '&ID_Centro_2=' + xID_Registro_2 + '&ID_Solicitud=' + xID_Solicitud;
                break;
            case 'ESCUELAS':
                url = '../Controladores/unificarescuelas.php?ID_Escuela_1=' + xID_Registro_1 + '&ID_Escuela_2=' + xID_Registro_2 + '&ID_Solicitud=' + xID_Solicitud;
                break;
            case 'BARRIOS':
                url = '../Controladores/unificarbarrios.php?ID_Barrio_1=' + xID_Registro_1 + '&ID_Barrio_2=' + xID_Registro_2 + '&ID_Solicitud=' + xID_Solicitud;
                break;
            case 'CATEGORIA':
                url = '../Controladores/unificarcategorias.php?ID=' + xID_Solicitud;
                break;
            case 'RESPONSABLE':
                url = '../Controladores/unificarresponsables.php?ID=' + xID_Solicitud;
                break;
            default:
                swal.fire("Algo salio mal consulte con el equipo de desarrollo", "", "warning");
                break;
            }
            window.location.href = url;
            /*
            let request = $.ajax({
            url: url,
            async: true,
            success: successHandler,
            error: errorHandler
            })
            */
        }
        });
}

export function VerificarCrearMotivo(
                                xID,
                                xFecha,
                                xMotivo,
                                xCodigo,
                                xNum_Motivo,
                                xCategoria
){
    swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer crear este motivo?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
        window.location.href = 'Controladores/InsertMotivo.php?ID=' + xID + '&Fecha=' + xFecha + '&Motivo=' + xMotivo + '&Codigo=' + xCodigo + '&Num_Motivo=' + xNum_Motivo + '&Cod_Categoria=' + xCategoria;
        }
    });
}

export function VerificarModificarMotivo(
                                    xID,
                                    xFecha,
                                    xMotivo,
                                    xCodigo,
                                    xNum_Motivo,
                                    xID_Motivo
){
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer modificar este motivo?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/ModificarMotivo.php?ID=' + xID + '&Fecha=' + xFecha + '&Motivo=' + xMotivo + '&Codigo=' + xCodigo + '&Num_Motivo=' + xNum_Motivo + '&ID_Motivo=' + xID_Motivo;                
        }
        });
}

export function VerificarModificacion(id, valor) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer modificar este Responsable?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/ModificarResponsable.php?ID=' + id + '&Responsable=' + valor;
        }
        });
}

export function VerificarEliminacion(id) {
        swal.fire({
            title: "¿Está seguro?",
            text: "¿Seguro de querer eliminar este Responsable?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                window.location.href = 'Controladores/DeleteResponsable.php?ID=' + id;
            }
        });
}

export function VerificarCrearCategoria(xID,xFecha,xCodigo,xCategoria,xID_Forma,xColor) {
        var ColorBase = btoa(xColor);
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer crear esta categoría?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((option) => {
        if (option) {
            let datos = 'ID=' + xID + '&Fecha=' + xFecha + '&Codigo=' + xCodigo + '&Categoria=' + xCategoria + '&ID_Forma=' + xID_Forma + '&ID_Categoria=' + xID + '&Color='+ColorBase;
            let addres = '/insertar_categoria';
            let request = $.ajax({
                type:"POST",
                url : addres,
                async: false,
                contentType: 'application/x-www-form-urlencoded',
                data: datos,
                success : function (data, status, requestHttp) {
                    if (requestHttp.responseJSON.mensaje) {
                        controlMensaje(requestHttp.responseJSON.mensaje, null);
                    } else if (requestHttp.responseJSON.mensaje_error) {
                        controlMensaje(null, requestHttp.responseJSON.mensaje_error);
                    }
                }
            });
        }
        });
}

export function VerificarModificarCategoria(xID,xFecha,xCodigo,xCategoria,xID_Forma,xNuevoColor,xID_Categoria) {
        var NuevoColorBase = btoa(xNuevoColor);

        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer modificar esta categoría?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/ModificarCategoria.php?ID=' + xID + '&Fecha=' + xFecha + '&Codigo=' + xCodigo + '&Categoria=' + xCategoria + '&ID_Forma=' + xID_Forma + '&ID_Categoria=' + xID_Categoria + '&CodigoColor='+NuevoColorBase;
        }
        });
}

export function VerificarEliminarMotivo(xID_Motivo) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer eliminar este motivo?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/DeleteMotivo.php?ID=' + xID_Motivo;
        }
        });
}

export function VerificarEliminarCategoria(xID_Categoria) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer eliminar este categoria?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/DeleteCategoria.php?ID=' + xID_Categoria;
        }
        });
}

export function VerificarEliminarNotificacion(xID_Notificacion) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer eliminar esta notificación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/DeleteNotificacion.php?ID=' + xID_Notificacion;
        } else {
        }
        });
}

export function VerificarModificarUsuario(xID_Solcitud) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer procesar esta solicitud?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores//modificar_account?id_solcitud=' + xID_Solcitud;
        }
        });
}

export function CancelarUnificacion(xID_Peticion) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de unificación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/DeletePeticion.php?ID=' + xID_Peticion;
        }
        });
}

export function CancelarModificacionMotivo(xID) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de modificación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionModificacionMotivo.php?ID=' + xID;
        }
        });
}

export function CancelarModificacion(xID) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de modificación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionModificacion.php?ID=' + xID;
        }
        });
}

export function CancelarCrearMotivo(xID) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de creación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionCrearMotivo.php?ID=' + xID;
        }
        });
}

export function CancelarCrearCategoria(xID) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de creación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionCrearCategoria.php?ID=' + xID;
        }
        });
}

export function CancelarModificacionCategoria(xID) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de modificación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionModificacionCategoria.php?ID=' + xID;
        } 
        });
}

export function CancelarEliminacionMotivo(xID) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de eliminación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionEliminacion.php?ID=' + xID;
        }
        });
}

export function CancelarEliminacionCategoria(xID) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de eliminación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionEliminacionCategoria.php?ID=' + xID;
        }
        });
}

export function CancelarSolciitudUsuario(xID) {
        swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de usuario?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionSolicitudUsuario.php?ID=' + xID;
        } else {        
        }
        });
}

export function VerificarUnificacionPersona() {
    let ID_Persona_1 = document.getElementById("ID_Persona_1");
    let ID_Persona_2 = document.getElementById("ID_Persona_2");
    let Form_1= document.getElementById("form_1");
    let Bandera = ValidarUnifPersonas();
    if (Bandera == false){
        return Bandera;
    }

    swal.fire({
        title: "¿Está seguro?",
        text: "¿Seguro de querer unificar estas personas?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((result) => {
        if (result) {
            Form_1.submit();
            return true;
        } else {
            return false;
        }
    });
}

export function mensajeDeProcesamiento(mensaje) {
    swal.fire(mensaje, '', 'success');
}

export function CargarEscuelas(xValor){
    let ID_Nivel = xValor;
    let xMLHTTP = new XMLHttpRequest();

    xMLHTTP.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("Escuelas").innerHTML = this.responseText;
        }
    };
    xMLHTTP.open("GET","lista_escuelas?q=" + ID_Nivel, true);
    xMLHTTP.send();
}

export function buscarCalles(){
    let xNombre = document.getElementById('SearchCalle').value;
    let textoBusqueda = xNombre;
    let xmlhttp = null;
    let contenidosRecibidos = null;

    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        contenidosRecibidos = xmlhttp.responseText;
        document.getElementById("ResultadosCalles").innerHTML=contenidosRecibidos;
        }
    }
    xmlhttp.open('POST', 'buscar_calle', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send('valorBusqueda=' + textoBusqueda);
}

export function buscarCategoria(id){
    let xBarrio = document.getElementById('SearchCategoria_' + id).value;
    let textoBusqueda = xBarrio;
    let xmlhttp = null;
    let contenidosRecibidos = null;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosCategoria_" + id).innerHTML=contenidosRecibidos;
        }
    }
    xmlhttp.open('POST', 'buscar_categoria_lista', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send('valorBusqueda=' + textoBusqueda + '&ID=' + id);
}

export function seleccionCategoria(id, xCategoria, xID) {
    let categoria = $("#categoria_" + id);
    if (id == 1) $("#ID_Categoria_unif").val(xID);
    if (id == 2) $("#ID_Categoria_del").val(xID);
    categoria.html("");
    categoria.html(xCategoria);
}

export function buscarCentros(id) {
    let xCentro = document.getElementById('SearchCentros_' + id).value;
    let textoBusqueda = xCentro;
    let xmlhttp = null;
    let contenidosRecibidos = null;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        contenidosRecibidos = xmlhttp.responseText;
        document.getElementById("ResultadosCentros_" + id).innerHTML = contenidosRecibidos;
        }
    }
    xmlhttp.open('POST', 'centro_salud_lista', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send('valorBusqueda=' + textoBusqueda + '&ID=' + id);
}

export function seleccionCentro(id, xCentro, xID) {
    let Centro = document.getElementById("Centro_" + id);
    let ID_Centro = document.getElementById("ID_Centro_" + id);
    Centro.innerHTML = "";
    Centro.innerHTML = "<p>" + xCentro + "</p>";
    ID_Centro.setAttribute('value', xID);
}

export function buscarPersonas(id){
    let xNombre = null;
    let xmlhttp = null;
    let textoBusqueda = null;
    let contenidosRecibidos = null;
    let url = null;

    xNombre = (id) ? $('#SearchPersonas_' + id).val() : $('#SearchPersonas').val();
    textoBusqueda = xNombre;
    url = 'valorBusqueda=' + textoBusqueda;
    if (id) url += '&ID=' + id;

    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            contenidosRecibidos = xmlhttp.responseText;
            if (id) {
                $("#ResultadosPersonas_"  + id).html(contenidosRecibidos);
            } else {
                $("#ResultadosPersonas").html(contenidosRecibidos);
            }
        }
    }
    xmlhttp.open('POST', 'buscar_personas', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send(url);
}

export function buscarMotivos(id){
    let xMotivo = null;
    let xmlhttp = null;
    let textoBusqueda = null;
    let contenidosRecibidos = null;
    let url = null;

    xMotivo = (id) ? $('#SearchMotivos_' + id).val() : $('#SearchMotivos').val();
    textoBusqueda = xMotivo;
    url = 'valorBusqueda=' + textoBusqueda;
    if (id) url += '&ID=' + id;

    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            contenidosRecibidos = xmlhttp.responseText;
            if (id) {
                $("#ResultadosMotivos_"  + id).html(contenidosRecibidos);
            } else {
                $("#ResultadosMotivos").html(contenidosRecibidos);
            }
        }
    }
    xmlhttp.open('POST', '/buscar_motivos_filtro', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send(url);
}

export function seleccionMotivo(id, xMotivo, xID) {
    let Motivo = (id) ? $("#Motivo_" + id) : $("#Motivo");
    let ID_Motivo = (id) ? $("#ID_Motivo_" + id) : $("#ID_Motivo");
    Motivo.html("<p>" + xMotivo + " <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalMotivo_1'><i class='fa fa-cog text-secondary'></i></button></p>");
    ID_Motivo.prop('value', xID);
}

export function seleccionPersona(id, xNombre, xID) {
    let Persona = (id) ? $("#Persona_" + id) : $("#Persona");
    let ID_Persona = (id) ? $("#ID_Persona_" + id) : $("#ID_Persona");
    Persona.html("<p>" + xNombre + "</p>");
    ID_Persona.prop('value', xID);
}

export function seleccionCalle(xNombre, xID) {
        let BotonModalPersona = document.getElementById("BotonModalDireccion_1");
        let calle = document.getElementById("Calle");
        nombreCalle = xNombre;
        BotonModalPersona.innerHTML = "";
        BotonModalPersona.innerHTML = xNombre;
        calle.setAttribute('value',xID);
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

export function insercionDatosFormulario() {
    let check_calle = $("#control-calle").is(":checked");
    let check_nro = $("#control-nro").is(":checked");
    let check_barrio = $("#control-barrio").is(":checked");
    let request = null;
    let query = "?";
    if (check_calle) {
        query += "calle=" + $("#calle-georeferencia").text();
    }

    if (check_nro) {
        query += (check_calle) ? "&" : "";
        query += "nro=" + $("#nro-georeferencia").text();
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
                    $("#ID_Calle")[0].selectedIndex = index;
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

        }
    });

    $("#control-calle").css("display", "none");
    $("#control-nro").css("display", "none");
    $("#control-barrio").css("display", "none");
    $("#control-calle").prop("checked", false);
    $("#control-nro").prop("checked", false);
    $("#control-barrio").prop("checked", false);
    $("#formulario-save").css("display", "none");
    $("#formulario-cancel").css("display", "none");
    $("#formulario-succes").show();
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