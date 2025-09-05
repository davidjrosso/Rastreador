import swal from 'sweetalert2';
import XMLHttpRequest from '../node_modules/xhr2/lib/browser';

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

export function VerificarCrearCategoria() {
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

export function VerificarUnificacion() {
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
    let xNombre = document.getElementById('SearchPersonas_' + id).value;
    let textoBusqueda = xNombre;
    let xmlhttp = null;
    let contenidosRecibidos = null;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        contenidosRecibidos = xmlhttp.responseText;
        document.getElementById("ResultadosPersonas_"  + id).innerHTML = contenidosRecibidos;
        }
    }
    xmlhttp.open('POST', 'buscar_personas', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send('valorBusqueda=' + textoBusqueda + '&ID=' + id);
}

export function seleccionPersona(id, xNombre, xID) {
    let Persona = document.getElementById("Persona_" + id);
    let ID_Persona = document.getElementById("ID_Persona_" + id);
    Persona.innerHTML = "";
    Persona.innerHTML = "<p>" + xNombre + "</p>";
    ID_Persona.setAttribute('value', xID);
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