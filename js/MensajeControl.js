import swal from 'sweetalert2';

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