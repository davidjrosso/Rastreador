import swal from '../node_modules/sweetalert2';


  export function dialogCargaEnlace(data, status, request) {
    let apellido = null;
    let nombre = null;
    let response = JSON.parse(request.responseText).entries();
    let mensaje = "<p>Estos registros no han sido georeferenciados</p>";
    let table = `<table style='border: 2px solid; border-collapse: collapse; border-color: #6984a3'>
                      <thead>
                        <th style='border: 2px solid; border-color: #6984a3; background-color: #e9eef3;'>Persona</th>
                        <th style='border: 2px solid; border-color: #6984a3; background-color: #e9eef3;'>Calle</th>
                      </thead>
                      <tbody>`;
    let count = false;
    response.forEach(function (element) {
      let personaObj = element[1].formulario.form.persona;
      let existe_c = element[1].formulario.calle_rastreador;
      if (!existe_c) {
        count = true;
        if (personaObj.Apellido) {
          apellido = personaObj.Apellido;
          apellido = upperLetraPalabra(apellido);
        }

        if (personaObj.Nombre) {
          nombre = personaObj.Nombre;
          nombre = upperLetraPalabra(nombre);
        }
        table += "<tr>";
        table += "<td style='border: 2px solid; width: 15rem; text-align: left; padding: 4px; border-color: #6984a3;'>" + 
                    "<a href='view_modpersonas.php?ID=" + personaObj.ID_Persona +"' target=_blank>" + 
                    apellido+ ", " + nombre
                    "</a>" +
                  "</td>";
        table += "<td style='border: 2px solid; width: 15rem; text-align: left; padding: 4px; border-color: #6984a3;'>" + 
                    element[1].formulario.domicilio + 
                  "</td>";
        table += "</tr>";
      }
    });
    table += `</table>`;  
    mensaje += (count) ? table : "";
    swal.fire({
      title: "<strong>El proceso de carga finalizó</strong>",
      icon: "success",
      html: mensaje,
      showCloseButton: true,
      focusConfirm: false,
      confirmButtonText: `<i class="fa fa-thumbs-up"></i> OK`,
      confirmButtonAriaLabel: "Thumbs up, great!",
      cancelButtonAriaLabel: "Thumbs down"
    });
  }

  export function dialogErrorCargaEnlace(data, status, request) {
    swal.fire({
      title: "Fallo de carga de Excel",
      text: "Los registros no se han cargados al sistema",
      icon: "error",
      showCancelButton: false
    });
  }

  export function cargaMovimientosExcel(idArchivo, idCentroSalud) {
    swal.fire({
      title: "Proceso de carga de Excel",
      text: "Los registros estan siendo cargados al sistema",
      icon: "warning",
      showConfirmButton: true
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

  export function cargaMovimientosFormulario(){
    swal.fire({
      title: "Proceso de carga de Excel",
      text: "Los registros de casos estan siendo cargados al sistema",
      icon: "warning",
      showConfirmButton: true
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

  export function dialogGeoreferenciaExcel(data, status, request) {
    let apellido = null;
    let nombre = null;
    let response = JSON.parse(request.responseText).entries();
    let mensaje = "<p>Estos registros no han sido georeferenciados</p>";
    let table = `<table style='border: 2px solid; border-collapse: collapse; border-color: #6984a3'>
                      <thead>
                        <th style='border: 2px solid; border-color: #6984a3; background-color: #e9eef3;'>Persona</th>
                        <th style='border: 2px solid; border-color: #6984a3; background-color: #e9eef3;'>Calle</th>
                      </thead>
                      <tbody>`;
    let count = false;
    response.forEach(function (element) {
      let personaObj = element[1].formulario.form.persona;
      let existe_c = element[1].formulario.calle_rastreador;
      if (!existe_c) {
        count = true;
        if (personaObj.Apellido) {
          apellido = personaObj.Apellido;
          apellido = upperLetraPalabra(apellido);
        }

        if (personaObj.Nombre) {
          nombre = personaObj.Nombre;
          nombre = upperLetraPalabra(nombre);
        }

        table += "<tr>";
        table += "<td style='border: 2px solid; width: 15rem; text-align: left; padding: 4px; border-color: #6984a3;'>" + 
                    "<a href='view_modpersonas.php?ID=" + personaObj.ID_Persona +"' target=_blank>" + 
                      apellido + ", " + nombre
                    "</a>" +
                  "</td>";
        table += "<td style='border: 2px solid; width: 15rem; text-align: left; padding: 4px; border-color: #6984a3;'>" + 
                    element[1].formulario.domicilio + 
                  "</td>";
        table += "</tr>";
      }
    });
    table += `</table>`;  
    mensaje += (count) ? table : "";
    swal.fire({
      title: "<strong>El proceso de georeferenciacion finalizó</strong>",
      icon: "success",
      html: mensaje,
      showCloseButton: true,
      focusConfirm: false,
      confirmButtonText: `<i class="fa fa-thumbs-up"></i> OK`,
      confirmButtonAriaLabel: "Thumbs up, great!",
      cancelButtonAriaLabel: "Thumbs down"
    });
  }

  export function dialogErrorGeoreferenciaExcel(data, status, request) {
    swal.fire({
      title: "Fallo de georefencia de personas del Excel",
      text: "Los registros no se han cargados al sistema",
      icon: "error",
      showCancelButton: false
    });
  }

  export function georeferenciaPersonasExcel(idArchivo, idCentroSalud) {
    swal.fire({
      title: "Proceso de georeferenciacion de personas de Excel",
      text: "Las personas estan siendo georeferenciadas en el sistema",
      icon: "warning",
      showConfirmButton: true
    });
    let dataRequest = new FormData();

    dataRequest.append("id_archivo", idArchivo);
    dataRequest.append("centro_salud", idCentroSalud);

    $.ajax({
      type: "POST",
      cache: false,
      url: "./Controladores/GeoreferenciaExcel.php",
      async: true,
      data: dataRequest,
      processData: false,
      contentType: false,
      success: dialogGeoreferenciaExcel,
      error: dialogErrorGeoreferenciaExcel
    });
  }

  export function validarExcel(idArchivo, idCentroSalud) {
    swal.fire({
      title: "Proceso de georeferenciacion de personas de Excel",
      text: "Las personas estan siendo georeferenciadas en el sistema",
      icon: "warning",
      showConfirmButton: true
    });
    let dataRequest = new FormData();

    dataRequest.append("id_archivo", idArchivo);
    dataRequest.append("centro_salud", idCentroSalud);

    $.ajax({
      type: "POST",
      cache: false,
      url: "./Controladores/ControlFileDrive.php",
      async: true,
      data: dataRequest,
      processData: false,
      contentType: false,
      success: dialogGeoreferenciaExcel,
      error: dialogErrorGeoreferenciaExcel
    });
  }

  function upperLetraPalabra(palabra) {
    let lista = palabra.trim().split(" ");
    let listaConcat = lista.map(function (word) {
      return word.charAt(0) + word.slice(1).toLowerCase();
    });
    let palabraConcat = listaConcat.reduce(function (incial, word) {
      return incial + " " + word;
    }, "");
    return palabraConcat;
  }
