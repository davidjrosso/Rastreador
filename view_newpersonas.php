<?php 
  session_start(); 
  require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
  header("Content-Type: text/html;charset=utf-8");

  /*     CONTROL DE USUARIOS                    */
  if(!isset($_SESSION["Usuario"])) {
      header("Location: Error_Session.php");
      exit();
  }

  $Con = new Conexion();
  $Con->OpenConexion();
  $id_usuario = $_SESSION["Usuario"];
  $account = new Account(account_id: $id_usuario);
  $tipo_usuario = $account->get_id_tipo_usuario();
  $Con->CloseConexion();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
  <link rel="import" href="https://sites.google.com/view/generales2019riotercero/página-principal">

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="js/ValidarPersona.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="./dist/mapa.js"></script>
  <script src="./dist/control.js"></script>

  <script>
      var map = null;
      var objectJsonPersona = {};
      var nombreCalle;
      let fullscreen = false;

       $(document).ready(function () {
              var date_input=$('input[name="Fecha_Nacimiento"]'); //our date input has the name "date"
              var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
              date_input.datepicker({
                  format: 'dd/mm/yyyy',
                  container: container,
                  todayHighlight: true,
                  autoclose: true,
                  closeText: 'Cerrar', /* HASTA ACA */
                  days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                  daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                  daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                  months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                  monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                  today: "Hoy",
                  monthsTitle: "Meses",
                  clear: "Borrar",
                  weekStart: 1,
              }).on("changeDate", calcularEdad);

              $("#boton-min").on("click", function (e) {
                $("button[class='ol-zoom-out']").click();
              });

              $("#boton-plus").on("click", function (e) {
                $("button[class='ol-zoom-in']").click();
              });

              $("#NumeroDeCalle").on("input", function(e) {
                let calle = $("#Calle").val();
                let nro = $(this).val();
                if (calle && nro) {
                  $("#mapa-sig").prop('disabled', false);
                  if($("#NumeroDeCalle").val() && $("#Calle").val()) {
                      $("#mapa-sig").prop('disabled', false);
                      if (!map) {
                        map = init(
                                  objectJsonPersona.lat, 
                                  objectJsonPersona.lon,
                                  map
                        );
                        map.setGeoreferenciacion();
                      }
                      let nro = $("#NumeroDeCalle").val();
                      if (!nombreCalle && !nro) {
                        map.addPersonMap(
                                      objectJsonPersona.lon,
                                      objectJsonPersona.lat,
                                      null
                                      );
                      } else if (nombreCalle && nro) {
                        map.addPersonMapAddress(
                                                nombreCalle,
                                                nro,
                                                calle
                                              );
                        
                      }
                  }
                } else {
                  $("#mapa-sig").prop('disabled', true);
                }
              });

              $("#input-calle").on("input",function (e) {
                listadoDeCalles(map);
              });

              $("#input-nro").on("click ",function (e) {
                $("#lista-calles-georeferencia").hide();
              });

              $("#input-nro").on("input",function (e) {
                map.queryDatosDomicilio();
              });

              $("#boton-desplegale").on("click", function (e) {
                $("#desplegable").toggle();
              });

              $("button[class='close']").on("click", function (e) {
                if (fullscreen) {
                  document.exitFullscreen();
                  fullscreen = false;
                }
              });

              $("#boton-fullscreen").on("click", function (e) {
                if (!fullscreen) {
                  $("#map-modal div[class='modal-content']")[0].requestFullscreen();
                  fullscreen = true;
                } else {
                  document.exitFullscreen();
                  fullscreen = false;
                }
              });

              if (!map) {
                  map = init(
                             objectJsonPersona.lat, 
                             objectJsonPersona.lon,
                             map
                  );
                  map.setGeoreferenciacion();
                }
          });

          function ValidarDocumento(){
            var Documento = document.getElementById("idDocumento");
            var NroDocumento = Documento.value;
            if (NroDocumento.toString().length < 8){
              NotShowModalError();
              return true;
            }

            const DniNoRepetido = "<p>No hay ningún registro con ese nombre, documento o legajo</p>";
            xmlhttp=new XMLHttpRequest();

            xmlhttp.onreadystatechange = function() {
              if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var contenidosRecibidos = xmlhttp.responseText;
                if(DniNoRepetido != contenidosRecibidos){ 
                  Documento.value = "";
                  swal({
                    title: "El Documento ingresado "+ NroDocumento +" ya esta registrado",
                    icon: "info",
                    text: "Por favor ingrese un Documento diferente",
                    confirmButtonText: 'OK'
                  })
                }
              }
            }
            xmlhttp.open('POST', 'buscarPersonas.php?valorBusqueda='+NroDocumento, true); // Método post y url invocada
            xmlhttp.send();

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

        function CargarEscuelas(xValor){
            ID_Nivel = xValor;
            var xMLHTTP = new XMLHttpRequest();

            xMLHTTP.onreadystatechange = function(){
              if(this.readyState == 4 && this.status == 200){
                document.getElementById("Escuelas").innerHTML = this.responseText;
              }
            };
            xMLHTTP.open("GET","CargarEscuelas.php?q="+xValor,true);
            xMLHTTP.send();
        }

        function calcularEdad() {
                let fecha = document.getElementById("Fecha_Nacimiento").value;
                if (fecha !== null && fecha.length != 0) {
                  fecha = fecha.split('/').reverse().join('-');
                  cumpleanos = new Date(fecha + " GMT-0300");
                } else {
                  cumpleanos = new Date();
                }

                let mes = cumpleanos.getMonth() + 1;
                let ano = cumpleanos.getFullYear();
                let dia = cumpleanos.getDate();

                let fecha_hoy = new Date();
                let ahora_ano = fecha_hoy.getYear();
                let ahora_mes = fecha_hoy.getMonth() + 1;
                let ahora_dia = fecha_hoy.getDate();

                let edad = (ahora_ano + 1900) - ano;
                if (ahora_mes < mes) {
                    edad--;
                }

                if ((mes == ahora_mes) && (ahora_dia < dia)) {
                    edad--;
                }

                if (edad > 1900) {
                    edad -= 1900;
                }

                let meses = 0;

                if (ahora_mes > mes && dia > ahora_dia)
                    meses = ahora_mes - mes - 1;
                else if (ahora_mes > mes)
                    meses = ahora_mes - mes
                if (ahora_mes < mes && dia < ahora_dia)
                    meses = 12 - (mes - ahora_mes);
                else if (ahora_mes < mes)
                    meses = 12 - (mes - ahora_mes + 1);
                if (ahora_mes == mes && dia > ahora_dia)
                    meses = 11;

                let Anios = document.getElementById("Edad");
                Anios.value = edad;

                let Meses = document.getElementById("Meses");
                Meses.value = meses;
          }

        function buscarCalles(){
        var xNombre = document.getElementById('SearchCalle').value;
        var textoBusqueda = xNombre;
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosCalles").innerHTML=contenidosRecibidos;
            }
        }
        xmlhttp.open('POST', 'buscarCalle.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
        xmlhttp.send();
      }

      function seleccionCalle(xNombre, xID) {
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
  </script>
</head>
<body>
<div class = "row margin-right-cero">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($tipo_usuario, $id_usuario, $Element::PAGINA_PERSONA);
  ?>
  <div class = "col-md-9 inicio-md-2">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Nueva Persona</p>
      </div>
      <div class="col"></div>
    </div><br>
    <div class="row">
      <div class="col"></div>
      <div class="col-10">
          <div class="row">
              <center><button class = "btn btn-secondary btn-sm" onClick="location.href ='view_newmovimientos.php'">Agregar Nuevo Movimiento</button></center>
          </div>
      </div>
      <div class="col"></div>
    </div>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Carga -->
          <p class = "Titulos">Cargar Nueva Persona</p>
          <form method = "post" onKeydown="return event.key != 'Enter';" action = "Controladores/InsertPersona.php" onSubmit = "return ValidarPersona();">
            <div class="form-group row">
              <label for="Apellido" class="col-md-2 col-form-label LblForm">Apellido: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Apellido" id="Apellido" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="Nombre" class="col-md-2 col-form-label LblForm">Nombre: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Nombre" id="Nombre" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="idDocumento" class="col-md-2 col-form-label LblForm">Documento: </label>
              <div class="col-md-10">
                <input type="number" class="form-control number-to-text" name = "DNI" oninput="ValidarDocumento()" id="idDocumento" required minlength="7" maxlength="8" autocomplete="off">
              </div>
            </div>
            <div class="div-modal-Error" id="ErrorDocumento">
            </div>
            <div class="form-group row">
              <label for="Fecha_Nacimiento" class="col-md-2 col-form-label LblForm" style="margin-bottom: -8px;">Fecha de Nacimiento: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Fecha_Nacimiento" id="Fecha_Nacimiento" autocomplete="off" placeholder="Ejemplo: 01/01/2010" onclick="calcularEdad()"> 
              </div>
            </div>
            <div class="row LblForm col-md-2" style="margin-bottom: 1.04%; font-size: 1.031rem">
                  Edad <br>
            </div>
            <div class="form-group row">
              <label for="Edad" class="col-md-2 col-form-label LblForm">Años: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Edad" id="Edad" autocomplete="off" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="Meses" class="col-md-2 col-form-label LblForm">Meses: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Meses" id="Meses" autocomplete="off" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="Nro_Carpeta" class="col-md-2 col-form-label LblForm">Nro. Carpeta: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Nro_Carpeta" id="Nro_Carpeta" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="Nro_Legajo" class="col-md-2 col-form-label LblForm">Nro. Legajo: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Nro_Legajo" id="Nro_Legajo" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Localidad: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Localidad" id="inputPassword" autocomplete="off" value = "Río Tercero">
              </div>
            </div>
            <div class="form-group row">
              <label for="ID_Barrio" class="col-md-2 col-form-label LblForm">Barrio: </label>
              <div class="col-md-10">
                <?php 
                $Element = new Elements();
                echo $Element->CBBarrios();
                ?>
              </div>
            </div>
            <div class="form-group row" style="margin-bottom: 0.6rem;">
              <label for="BotonModalDireccion_1" class="col-md-2 col-form-label LblForm">Domicilio: </label>
              <div class="col-md-6 flex-sm-boton" id = "Persona">
              	 	<button type = "button" id="BotonModalDireccion_1" class = "btn btn-lg btn-primary btn-block" style="padding-top: 4px;padding-bottom: 4px;" data-toggle="modal" data-target="#ModalCalle">Seleccione una Calle</button>
              </div>
              <div class="col-md-2 form-boton-widht">
                <input type="number" class="form-control" style="margin-top: 1px;" name = "NumeroDeCalle" id="NumeroDeCalle" placeholder="Número" min="1" autocomplete="off">
              </div>
              <div class="col-md-2">
                <button id="mapa-sig" type="button" class="btn btn-secondary" disabled data-toggle="modal"
                  style="background-color: #ffc6b1; color: black; border-color: white; " data-target="#map-modal">S.
                  I. G.</button>
              </div>
            </div>

            <div class="form-group row">
              <label for="Manzana" class="col-md-2 col-form-label LblForm">Manzana: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Manzana" id="Manzana" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="Lote" class="col-md-2 col-form-label LblForm">Lote: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Lote" id="Lote" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="Familia" class="col-md-2 col-form-label LblForm">Sublote: </label>
              <div class="col-md-10">
                <input type="number" class="form-control" name = "Familia" id="Familia" autocomplete="off">
              </div>
            </div>            
            <div class="form-group row">
              <label for="Telefono" class="col-md-2 col-form-label LblForm">Teléfono: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Telefono" id="Telefono" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="Mail" class="col-md-2 col-form-label LblForm">Mail: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Mail" id="Mail" autocomplete="off">
              </div>
            </div>            
            <div class="form-group row">
              <label for="Obra_Social" class="col-md-2 col-form-label LblForm">Obra Social(Si/No): </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Obra_Social" id="Obra_Social" autocomplete="off">
              </div>
            </div>                        
            <div class="form-group row">
              <label for="ID_Nivel" class="col-md-2 col-form-label LblForm">Nivel Escolar: </label>
              <div class="col-md-10">
                <?php 
                $Element = new Elements();
                echo $Element->CBNivelEscuelas();
                ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="ID_Escuela" class="col-md-2 col-form-label LblForm">Escuela: </label>
              <div class="col-md-10" id = "Escuelas">
                <?php 
                $Element = new Elements();
                echo $Element->CBEscuelas(0);
            ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="trabajo" class="col-md-2 col-form-label LblForm">Lugar de Trabajo: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Trabajo" id="trabajo" autocomplete="off">
              </div>
            </div>                                                
            <div class="form-group row">
              <label for="observaciones" class="col-md-2 col-form-label LblForm">Observaciones: </label>
              <div class="col-md-10">
                <textarea class = "form-control" row = "3" name = "Observaciones" id="observaciones"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label for="cambio_domicilio" class="col-md-2 col-form-label LblForm">Cambio de Domicilio:</label>
              <div class="col-md-10">
                <textarea class="form-control" row="3" name="Cambio_Domicilio" id="cambio_domicilio"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <div class="offset-md-2 col-md-10">
                <button type="submit" class="btn btn-outline-success">Guardar</button>
                <button type = "button" class = "btn btn-danger" onClick = "location.href = 'view_personas.php'">Atras</button>
              </div>
            </div>
            <input type="hidden" id="lat" name="lat" value="">
            <input type="hidden" id="lon" name="lon" value="">
            <input type="hidden" name="Calle" id="Calle" value = "">

          </form>
          <div class="row">
              <div class="col-10"></div>
              <div class="col-2"></div>
          </div>
          <!-- Fin Carga -->

          <!-- Modal de Carga de Calle-->
      <div class="modal fade bd-example-modal-lg" id="ModalCalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Seleccione una Calle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form>
                  <div class="row">
                    <div class="col"></div>
                    <div class="col-8">
                      <div class="input-group mb-3">
                        <input class = "form-control" type="text" name="BuscarCalle" id = "SearchCalle" onKeyUp="buscarCalles()" autocomplete="off" placeholder="Ingrese el nombre de calle">
                        <div class="input-group-append">
                          <span class="input-group-text" id="basic-addon2">Buscar</span>
                        </div>	
                      </div>		        				
                    </div>
                    <div class="col"></div>
                  </div>
                  <div class="row">
                    <div class="col"></div>
                    <div class="col-10" id = "ResultadosCalles">
                      
                    </div>
                    <div class="col"></div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>			        
              </div>
            </div>
        </div>
      </div>
      <!-- Modal de Carga de Calle-->
  </div>
</div>
<div class="modal fade modal--show-overall" id="map-modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 2001; overflow: hidden">
    <div class="class_modal-dialog modal-dialog" role="document" id="id_modal-dialog"
      style="min-width: 80%; height: 1000px;">
      <div class="modal-content" style="height: 60%;">
        <div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <button type="button" id="boton-desplegale" class="button-arrow" aria-label="desplegable">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
            </svg>
          </button>
          <button type="button" id="boton-fullscreen" class="button-fullscreen" aria-label="fullscreen">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm5.854 8.803a.5.5 0 1 1-.708-.707L9.243 6H6.475a.5.5 0 1 1 0-1h3.975a.5.5 0 0 1 .5.5v3.975a.5.5 0 1 1-1 0V6.707z"/>
              </svg>
          </button>
          <button type="button" id="boton-plus" class="button-plus clear-outline" aria-label="plus">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-zoom-in" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11M13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0"/>
                <path d="M10.344 11.742q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1 6.5 6.5 0 0 1-1.398 1.4z"/>
                <path fill-rule="evenodd" d="M6.5 3a.5.5 0 0 1 .5.5V6h2.5a.5.5 0 0 1 0 1H7v2.5a.5.5 0 0 1-1 0V7H3.5a.5.5 0 0 1 0-1H6V3.5a.5.5 0 0 1 .5-.5"/>
              </svg>
          </button>
          <button type="button" id="boton-min" class="button-min clear-outline" aria-label="min">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-zoom-out" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11M13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0"/>
              <path d="M10.344 11.742q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1 6.5 6.5 0 0 1-1.398 1.4z"/>
              <path fill-rule="evenodd" d="M3 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5"/>
            </svg>
          </button>
          <button type="button" id="boton-save" class="button-fullscreen" onclick="showControlFormulario();" aria-label="save">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                <path d="M11 2H9v3h2z"/>
                <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
              </svg>
          </button>
        </div>
        <div class="modal-body" style="padding-top: 0px">
          <div id="basicMap"></div>
        </div>
        <div id="desplegable" style="display: flex; position: absolute; top: 30px; left: 20px; z-index: 1000">
              <div style="display: flex; flex-direction: column;">
                    <table class="tabla-direccion">
                        <thead>
                          <th></th>
                          <th></th>
                        </thead>
                        <tbody> 
                          <tr>
                            <td>
                              Calle
                            </td>
                            <td  id="calle-georeferencia">
                              <input id="input-calle" style="appearance: none;" type="text" value="">
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Nro
                            </td>
                            <td id="nro-georeferencia">
                              <input id="input-nro" style="appearance: none;" type="number" value="0">
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Barrio
                            </td>
                            <td id="barrio-georeferencia">
                            </td>
                          </tr>
                        </tbody>
                    </table>
                    <div style="display:flex; justify-content: space-around">
                          <button type="button" id="formulario-save" class="btn btn-danger btn-sm" 
                                  style="display: none; flex-grow: 1; flex-basis: 40%" onclick="insercionDatosFormulario();" aria-label="mapa-ok">
                            OK
                          </button>
                          <button type="button" id="formulario-cancel" class="btn btn-primary btn-sm" 
                                  style="display: none; flex-grow: 1; flex-basis: 40%" onclick="clearDatosFormulario();" aria-label="mapa-cancel">
                            Cancel
                          </button>
                          <button type="button" id="formulario-succes" class="btn btn-success btn-sm"
                                  style="width: 100%; display: none;" aria-label="mapa-succes">
                            Dirección actualizada
                          </button>
                    </div>
              </div>
              <div id="lista-calles-georeferencia" style="display: none" class="dropdown" aria-labelledby="dropdownMenuButton1">
                  <div  id="listado-calles" class="dropdown-menu" style="display: block; top: 1px; max-height: 325px; overflow-y: auto; overflow-x: hidden; width: 255px; font-size: 0.90rem; position: static; margin-top: 6px; padding: 0px;">
                    <h6 class="dropdown-header" style="text-align: center; padding-top: 5px;">Calles</h6>
                  </div>
              </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php  
if(isset($_REQUEST["Mensaje"])){
  echo "<script type='text/javascript'>
  swal('".$_REQUEST["Mensaje"]."','','success');
</script>";
}
?>
</body>
</html>