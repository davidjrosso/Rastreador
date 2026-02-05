<?php
/*
 *
 * This file is part of Rastreador3.
 *
 * Rastreador3 is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Rastreador3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Rastreador3; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 */

if(session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");

header("Content-Type: text/html;charset=utf-8");

$http_referer = (!empty($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : null;

if (!preg_match("~view_personas~", $http_referer)) {
  $_SESSION["from_reporte_grafico"] = true;
} else {
  $_SESSION["from_reporte_grafico"] = false;
}

$ID_Usuario = (isset($_SESSION["Usuario"])) ? $_SESSION["Usuario"] : null;
$account = new Account(account_id: $ID_Usuario);
$TipoUsuario = $account->get_id_tipo_usuario();

$mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
$mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

?>
<!DOCTYPE html>
<html>

<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.0.0.min.js"></script>
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
  <script src="./dist/mapa.js"></script>
  <script src="./dist/control.js"></script>

  <script>
    var map = null;
    var objectJsonPersona = {};
    var isSave = false;
    let fullscreen = false;

    $(document).ready(function () {
      let date_input = $('input[name="Fecha_Nacimiento"]'); //our date input has the name "date"
      let container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
      let mensajeError = '<?php echo $mensaje_error;?>';
      let mensajeSuccess = '<?php echo $mensaje_success;?>';

      controlMensaje(mensajeSuccess, mensajeError);
      date_input.datepicker({
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
        closeText: 'Cerrar',
        days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
        daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        today: "Hoy",
        monthsTitle: "Meses",
        clear: "Borrar",
        weekStart: 1
      }).on('changeDate', calcularEdad);

      $("#map-modal").on("transitionend", function(e) {
        if (!map) {
          map = init(
                     objectJsonPersona.lat,
                     objectJsonPersona.lon,
                     map
                  );
          map.setGeoreferenciacion();
          if (objectJsonPersona.lat && objectJsonPersona.lon) {
            map.addPersonMap(
                             objectJsonPersona.lat,
                             objectJsonPersona.lon,
                             null
                            );
          }
        };
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

      $("#boton-min").on("click", function (e) {
        $("button[class='ol-zoom-out']").click();
      });

      $("#boton-plus").on("click", function (e) {
        $("button[class='ol-zoom-in']").click();
      });

      $("#ID_Calle").on("input", function(e) {
        let nro = $("#NumeroDeCalle").val();
        let calleId = null;
        let calleNombre = null;
        if (nro) {
          $("#mapa-sig").prop('disabled', false);
          calleNombre = $("#ID_Calle").find(":selected").text();
          calleId = $("#ID_Calle").val();
          nro = $("#NumeroDeCalle").val();
          if (!map) {
            map = init(
                       objectJsonPersona.lat, 
                       objectJsonPersona.lon,
                       map
                      );
            map.setGeoreferenciacion();
          }
          map.addPersonMapAddress(
                                  calleNombre,
                                  nro,
                                  calleId
                                 );
        }
      });
      $("#NumeroDeCalle").on("input", function(e) {
        let calleNombre = $("#ID_Calle").find(":selected").text();
        let calleId = $("#ID_Calle").val();
        let nro = $(this).val();
        if (calleNombre) {
          $("#mapa-sig").prop('disabled', false);
          if (!map) {
            map = init(
                       objectJsonPersona.lat, 
                       objectJsonPersona.lon,
                       map
                      );
            map.setGeoreferenciacion();
          }
          if (nro) {
            map.addPersonMapAddress(
                                    calleNombre,
                                    nro,
                                    calleId
                                   );
          } 
        }
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

      $("#opcion_f").on("click", function (e) {
          $("#opcion_m").prop("checked", false);
          $("#opcion_x").prop("checked", false);
      });

      $("#opcion_m").on("click", function (e) {
          $("#opcion_f").prop("checked", false);
          $("#opcion_x").prop("checked", false);
      });

      $("#opcion_x").on("click", function (e) {
          $("#opcion_f").prop("checked", false);
          $("#opcion_m").prop("checked", false);
      });

      $("#indications").on("click", function (e) {
        $("#liveToast").toggle();
      });

      if($("#ID_Calle").find(":selected").val()) {
        $("#mapa-sig").prop('disabled', false);
      }
    });

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
  </script>
</head>

<body>
  <div class="row margin-right-cero">
    <?php
    $Element = new Elements();
    echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_PERSONA);
    ?>
    <div class="col-md-9 inicio-md-2 row margin-right-cero">
            <div class="col-1"></div>
            <div class="col">
                  <div class="row">
                          <div class="col Titulo">
                            <p>Persona</p>
                          </div>
                  </div>
                  <br>
                  <br>
                  <div class="row">
                    <div class="col">
                      <!-- Search -->
                      <div class="row">
                        <?php
                        if (isset($_REQUEST["ID"])) {
                          $ID = $_REQUEST["ID"];

                          $Con = new Conexion();
                          $Con->OpenConexion();

                          $ConsultarDatos = "select p.*, 
                                                    ST_X(p.georeferencia) as lat, 
                                                    ST_Y(p.georeferencia) as lon
                                            from persona p
                                            where id_persona = $ID";
                          $MensajeErrorDatos = "No se pudo consultar los Datos de la Persona";

                          $EjecutarConsultarDatos = mysqli_query($Con->Conexion, $ConsultarDatos) or die($MensajeErrorDatos);

                          $Ret = mysqli_fetch_assoc($EjecutarConsultarDatos);

                          $ID_Persona = $Ret["id_persona"];
                          $Apellido = $Ret["apellido"];
                          $Nombre = $Ret["nombre"];
                          $DNI = $Ret["documento"];
                          $Nro_Legajo = $Ret["nro_legajo"];
                          $Edad = $Ret["edad"];
                          $Meses = $Ret["meses"];
                          $Fecha_Nacimiento = implode("/", array_reverse(explode("-", $Ret["fecha_nac"])));
                          $Nro_Carpeta = $Ret["nro_carpeta"];
                          $Obra_Social = $Ret["obra_social"];
                          $Domicilio = $Ret["domicilio"];
                          $Barrio = $Ret["ID_Barrio"];
                          $Localidad = $Ret["localidad"];
                          $Circunscripcion = $Ret["circunscripcion"];
                          $Seccion = $Ret["seccion"];
                          $Manzana = $Ret["manzana"];
                          $Lote = $Ret["lote"];
                          $Familia = $Ret["familia"];
                          $Observaciones = $Ret["observacion"];
                          $Cambio_Domicilio = $Ret["cambio_domicilio"];
                          $Telefono = $Ret["telefono"];
                          $Mail = $Ret["mail"];
                          $Estado = $Ret["estado"];
                          $ID_Escuela = $Ret["ID_Escuela"];
                          $Trabajo = $Ret["Trabajo"];

                          $Persona = new Persona($ID_Persona);
                          $Con->CloseConexion();

                          $opcion_f = ($Persona->getSexo() == 'f') ? true : false;
                          $opcion_m = ($Persona->getSexo() == 'm') ? true : false;
                          $opcion_x = ($Persona->getSexo() == 'x') ? true : false;

                          ?>
                          <div class="col-10">
                            <form id="form-mod-persona" method="post" onsubmit="return ValidarPersona()" action="Controladores/ModificarPersona.php">
                              <input type="hidden" name="ID" value="<?php echo $Persona->getID_Persona(); ?>">
                              <div class="form-group row">
                                <label for="apellido" class="col-md-2 col-form-label LblForm">Apellido: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Apellido" id="apellido" autocomplete="off"
                                    value="<?php echo strtoupper($Persona->getApellido()); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="nombre" class="col-md-2 col-form-label LblForm">Nombre: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Nombre" id="nombre" autocomplete="off"
                                    value="<?php echo $Persona->getNombre(); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="documento" class="col-md-2 col-form-label LblForm">Documento: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="DNI" id="documento" autocomplete="off"
                                    value="<?php echo $Persona->getDNI(); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="Fecha_Nacimiento" class="col-md-2 col-form-label LblForm" style="margin-bottom: -8px;">Fecha
                                  de Nacimiento: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Fecha_Nacimiento" id="Fecha_Nacimiento"
                                    autocomplete="off" <?php if ($Fecha_Nacimiento != "null") {
                                      echo "value = '" . $Persona->getFecha_Nacimiento() . "'";
                                    }
                                    ; ?>>
                                </div>
                              </div>
                              <div class="row LblForm col-md-2" style="margin-bottom: 1.04%; font-size: 1.031rem">
                                Edad <br>
                              </div>
                              <div class="form-group row">
                                <label for="Edad" class="col-md-2 col-form-label LblForm">Años: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Edad" id="Edad" autocomplete="off" readonly
                                    value="<?php echo $Persona->getEdad(); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="Meses" class="col-md-2 col-form-label LblForm">Meses: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Meses" id="Meses" autocomplete="off" readonly
                                    value="<?php echo $Persona->getMeses(); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="opcion_f" class="col-md-2 col-form-label LblForm">Sexo: </label>
                                <div class="col-md-10">
                                  <div class="form-check form-check-inline" style="margin-left: 2%; margin-top: 1%;">
                                    <input class="form-check-input" type="radio" name="opcion_f" id="opcion_f" value="f">
                                    <label class="form-check-label" style="font-size: 1.2rem; margin-left: 7px;" for="opcion_f">f</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="opcion_m" id="opcion_m" value="m">
                                    <label class="form-check-label" style="font-size: 1.2rem; margin-left: 7px;" for="opcion_m">m</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="opcion_x" id="opcion_x" value="x">
                                    <label class="form-check-label" style="font-size: 1.2rem; margin-left: 7px;" for="opcion_x">x</label>
                                  </div>
                                  <!-- TOAST PROGRESO ENLACE -->
                                  <span id="liveToast" class="position-relative top-0 end-0 toast" style="border-radius: 10%;top: 7%; color: #020202; background-color: rgb(150 127 127 / 61%); right: -11%; padding: 10px;">
                                      Ley N° 26.743 - Decreto 476/202
                                  </span>
                                  <!-- FIN TOAST PROGRESO ENLACE -->
                                </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="nro_carpeta" class="col-md-2 col-form-label LblForm">Nro. Carpeta: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Nro_Carpeta" id="nro_carpeta" autocomplete="off"
                                    value="<?php echo $Persona->getNro_Carpeta(); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="Nro_Legajo" class="col-md-2 col-form-label LblForm">Nro. Legajo: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Nro_Legajo" id="Nro_Legajo" autocomplete="off" <?php if ($Nro_Legajo != "null") {
                                    echo "value = '" . $Persona->getNro_Legajo() . "'";
                                  }
                                  ?>>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="localidad" class="col-md-2 col-form-label LblForm">Localidad: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Localidad" id="localidad" autocomplete="off"
                                    value="<?php echo $Persona->getLocalidad(); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="ID_Barrio" class="col-md-2 col-form-label LblForm">Barrio: </label>
                                <div class="col-md-10">
                                  <?php
                                  $Element = new Elements();
                                  echo $Element->CBModBarrios($Persona->getId_Barrio());
                                  ?>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="NumeroDeCalle" class="col-md-2 col-form-label LblForm">Domicilio: </label>
                                <div class="col-md-6 flex-sm-boton">
                                  <?php
                                    if (!empty($Persona->getId_Calle())) {
                                      echo $Element->CBCallesNombre($Persona->getId_Calle());
                                    } else {
                                      echo $Element->CBCallesNombre($Persona->getCalle());
                                    }
                                  ?>

                                </div>
                                <div class="col-md-2 form-boton-widht">
                                  <input type="number" class="form-control" name="NumeroDeCalle" id="NumeroDeCalle" placeholder="Nro"
                                    min="1" autocomplete="off" <?php
                                    $NroCalle = $Persona->getNro();
                                    echo "value = '$NroCalle'"; ?>>
                                </div>
                                <div class="col-md-2 form-boton-widht">
                                  <button id="mapa-sig" type="button" class="btn btn-secondary" disabled data-toggle="modal"
                                    style="background-color: #ffc6b1; color: black; border-color: white; " data-target="#map-modal">S.
                                    I. G.</button>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="manzana" class="col-md-2 col-form-label LblForm">Manzana: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Manzana" id="manzana" autocomplete="off" <?php if ($Manzana != "null") {
                                    echo "value = '" . $Persona->getManzana() . "'";
                                  }
                                  ; ?>>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="lote" class="col-md-2 col-form-label LblForm">Lote: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Lote" id="lote" autocomplete="off"
                                    value="<?php echo $Persona->getLote(); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="familia" class="col-md-2 col-form-label LblForm">Sub-lote: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Familia" id="familia" autocomplete="off"
                                    value="<?php echo $Persona->getFamilia(); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="telefono" class="col-md-2 col-form-label LblForm">Telefono: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Telefono" id="telefono" autocomplete="off"
                                    value="<?php echo $Persona->getTelefono(); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="mail" class="col-md-2 col-form-label LblForm">Mail: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Mail" id="mail" autocomplete="off"
                                    value="<?php echo $Persona->getMail(); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="obra-social" class="col-md-2 col-form-label LblForm">Obra Social(Si/No): </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Obra_Social" id="obra-social" autocomplete="off"
                                    value="<?php echo $Persona->getObra_Social(); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="ID_Escuela" class="col-md-2 col-form-label LblForm">Escuela: </label>
                                <div class="col-md-10">
                                  <?php
                                  echo $Element->CBModEscuelas($Persona->getID_Escuela());
                                  ?>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="trabajo" class="col-md-2 col-form-label LblForm">Lugar de Trabajo: </label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="Trabajo" id="trabajo" autocomplete="off"
                                    value="<?php echo $Persona->getTrabajo(); ?>">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="observaciones" class="col-md-2 col-form-label LblForm">Observación: </label>
                                <div class="col-md-10">
                                  <textarea class="form-control" row="3" name="Observaciones" id="observaciones"
                                    value="<?php echo $Persona->getObservaciones(); ?>"><?php echo $Persona->getObservaciones(); ?></textarea>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="cambio-domicilio" class="col-md-2 col-form-label LblForm">Cambio de Domicilio:
                                </label>
                                <div class="col-md-10">
                                  <textarea class="form-control" row="3" name="Cambio_Domicilio" id="cambio-domicilio"
                                    value="<?php echo $Persona->getCambio_Domicilio(); ?>"><?php echo $Persona->getCambio_Domicilio(); ?></textarea>
                                </div>
                              </div>
                              <input type="hidden" id="lat" name="lat" value="">
                              <input type="hidden" id="lon" name="lon" value="">
                              <div class="form-group row">
                                <div class="offset-md-2 col-md-10">
                                  <button type="submit" class="btn btn-outline-success">Guardar</button>
                                  <button type="button" class="btn btn-danger"
                                    onClick="location.href = '/view_personas.php'">Atras</button>
                                </div>
                              </div>
                            </form>
                          </div>
                          <?php
                        } else {
                          $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID de la Persona";
                          echo $Mensaje;
                        }
                        ?>
                      </div>
                      <div class="row">
                        <div class="col-10"></div>
                        <div class="col-2">
                          <!-- <button type = "button" class = "btn btn-outline-secondary" onClick = "location.href = 'personas'">Volver</button> -->
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
            <div class="col-2"></div>
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
                              <input id="input-calle" style="appearance: none;" type="text" value="<?php echo $Persona->getNombre_Calle();?>">
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Nro
                            </td>
                            <td id="nro-georeferencia">
                              <input id="input-nro" style="appearance: none;" type="number" value="<?php echo $Persona->getNro();?>">
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Barrio
                            </td>
                            <td id="barrio-georeferencia">
                              <?php echo $Persona->getBarrio();?>
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
  <script>
    objectJsonPersona.lat = <?php echo (!empty($Persona->getLatitud())) ? $Persona->getLatitud() : "null" ; ?>;
    objectJsonPersona.lon = <?php echo (!empty($Persona->getLonguitud())) ? $Persona->getLonguitud() : "null"; ?>;
  </script>
</body>

</html>