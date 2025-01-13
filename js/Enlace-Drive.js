import swal from '../node_modules/sweetalert2';

  export function dialogCargaEnlace(){
    swal.fire({
      title: "El proceso de carga de Excel finalizo",
      text: "Los registros de casos de Dengue han sido cargados al sistema",
      icon: "success",
      showCancelButton: false
    });
  }

  export function dialogErrorCargaEnlace(){
    swal.fire({
      title: "Fallo de carga de Excel",
      text: "Los registros de casos de Dengue no se han cargados al sistema",
      icon: "error",
      showCancelButton: false,
      dangerMode: true
    });
  }

  export function cargaMovimientosFormulario(){
    swal.fire({
      title: "Proceso de carga de Excel",
      text: "Los registros de casos de Dengue estan siendo cargados al sistema",
      icon: "warning",
      showConfirmButton: true,
      dangerMode: true,
    });

    $.ajax({
      type: "POST",
      cache: false,
      url: "./Controladores/InsertFormularios.php",
      async: true,
      success: dialogCargaEnlace,
      error: dialogErrorCargaEnlace
    });
  }