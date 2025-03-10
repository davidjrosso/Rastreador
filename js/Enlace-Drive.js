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
    let dataRequest = new FormData();

    dataRequest.append("id_archivo", idArchivo);
    dataRequest.append("centro_salud", idCentroSalud);

    $.ajax({
      type: "POST",
      cache: false,
      url: "./Controladores/insertExcel.php",
      async: true,
      data: dataRequest,
      processData: false,
      contentType: false,
      success: dialogCargaEnlace,
      error: dialogErrorCargaEnlace
    });
  }