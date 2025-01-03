  function dialogCargaEnlace() {
    swal({
      title: "El proceso de carga de Excel finalizo",
      text: "Los registros de casos de Dengue han sido cargados al sistema",
      icon: "success",
      showCancelButton: false
    });
  }

  function dialogErrorCargaEnlace() {
    swal({
      title: "Fallo de carga de Excel",
      text: "Los registros de casos de Dengue no se han cargados al sistema",
      icon: "error",
      showCancelButton: false,
      dangerMode: true
    });
  }

  function cargaMovimientosFormulario(){
    swal({
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