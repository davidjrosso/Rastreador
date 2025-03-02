import swal from '../node_modules/sweetalert2';

  export function dialogCargaEnlace() {
    swal.fire({
      title: "El proceso de carga de Excel finalizo",
      text: "Los registros han sido cargados al sistema",
      icon: "success",
      showCancelButton: false
    });
  }

  export function dialogErrorCargaEnlace() {
    swal.fire({
      title: "Fallo de carga de Excel",
      text: "Los registros no se han cargados al sistema",
      icon: "error",
      showCancelButton: false,
      dangerMode: true
    });
  }

  export function cargaMovimientosExcel(idArchivo, idCentroSalud) {
    swal.fire({
      title: "Proceso de carga de Excel",
      text: "Los registros estan siendo cargados al sistema",
      icon: "warning",
      showConfirmButton: true,
      dangerMode: true,
    });

    $.ajax({
      type: "POST",
      cache: false,
      url: "./Controladores/InsertExcel.php",
      async: true,
      data: "{id_archivo: " + idArchivo + "," +
             "centro_salud: " + idCentroSalud + "}",
      success: dialogCargaEnlace,
      error: dialogErrorCargaEnlace
    });
  }