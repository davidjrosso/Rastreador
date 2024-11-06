<?php
session_start();
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
require_once "Modelo/Persona.php";
header("Content-Type: text/html;charset=utf-8");

/*     CONTROL DE USUARIOS                    */
if (!isset($_SESSION["Usuario"])) {
  header("Location: Error_Session.php");
}

$Con = new Conexion();
$Con->OpenConexion();
$ID_Usuario = $_SESSION["Usuario"];
$ConsultarTipoUsuario = "select ID_TipoUsuario from accounts where accountid = $ID_Usuario";
$MensajeErrorConsultarTipoUsuario = "No se pudo consultar el Tipo de Usuario";
$EjecutarConsultarTipoUsuario = mysqli_query($Con->Conexion, $ConsultarTipoUsuario) or die($MensajeErrorConsultarTipoUsuario);
$Ret = mysqli_fetch_assoc($EjecutarConsultarTipoUsuario);
$TipoUsuario = $Ret["ID_TipoUsuario"];
$Con->CloseConexion();
?>
<!DOCTYPE html>
<html>

<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />

  <!--<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>-->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.0.0.min.js"></script>
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <!--<script type="text/javascript" src = "js/Funciones.js"></script> -->
  <script src="js/bootstrap-datepicker.min.js"></script> <!-- ESTO ES NECESARIO PARA QUE ANDE EN ESPAÑOL -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <script src="https://cdn.jsdelivr.net/npm/ol@v10.1.0/dist/ol.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.1.0/ol.css">

  <script src="js/OpenLayers.js"></script>
  <script src="js/leaflet-providers.js"></script>

  <script>
    var map = null;
    let objectJsonPersona = {};

    $(document).ready(function () {
      var date_input = $('input[name="Fecha_Nacimiento"]'); //our date input has the name "date"
      var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
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
        if (!map) init();
      });
      $("#ID_Calle").on("input", function(e) {
        let nro = $("#NumeroDeCalle").val();
        if (nro) {
          $("#mapa-sig").prop('disabled', false);
        }
      });
      $("#NumeroDeCalle").on("input", function(e) {
        let calle = $("#ID_Calle").val();
        let nro = $(this).val();
        if (calle && nro) {
          $("#mapa-sig").prop('disabled', false);
        } else {
          $("#mapa-sig").prop('disabled', true);
        }
      });
      if($("#NumeroDeCalle").val() && $("#ID_Calle").find(":selected").val()) {
        $("#mapa-sig").prop('disabled', false);
      }
    });

    function calcularEdad() {
      let Fecha_Nac = document.getElementById("Fecha_Nacimiento").value;
      let Fecha = Fecha_Nac.split('/').reverse().join('-');
      let hoy = new Date();
      let cumpleanos = new Date(Fecha);
      let edad = hoy.getFullYear() - cumpleanos.getFullYear();
      let m = hoy.getMonth() - cumpleanos.getMonth();
      if (m < 0 || (m === 0 && hoy.getDay() < cumpleanos.getDay())) {
        edad--;
      }
      let Anios = document.getElementById("Edad");
      Anios.value = edad;

      let CalcMeses = 0;
      if (m < 0) {
        CalcMeses = (12 + m);
      } else if (m == 0) {
        if (hoy.getDay() < cumpleanos.getDay()) {
          m = 11;
        }
        CalcMeses = m;
      } else {
        CalcMeses = m;
      }

      let Meses = document.getElementById("Meses");
      Meses.value = CalcMeses;
    }

    function init() {
      if (map === null) {
          map = new OpenLayers.Map("basicMap", {
            zoomDuration: 5, 
            projection: 'EPSG:3857', 
            controls: []
          });
          let position = null;
          let pos = null;
          let mapnik = new OpenLayers.Layer.OSM("OpenCycleMap",
                ["http://a.tile.thunderforest.com/transport/${z}/${x}/${y}.png?apikey=d03b42dcdc084e7cbab176997685b1ce",
                "http://b.tile.thunderforest.com/transport/${z}/${x}/${y}.png?apikey=d03b42dcdc084e7cbab176997685b1ce",
                "http://c.tile.thunderforest.com/transport/${z}/${x}/${y}.png?apikey=d03b42dcdc084e7cbab176997685b1ce"]);

          var fromProjection = new OpenLayers.Projection("EPSG:3857");
          var toProjection = new OpenLayers.Projection("EPSG:4326");
          if (objectJsonPersona.lon && objectJsonPersona.lat) {
            pos = new OpenLayers.LonLat(objectJsonPersona.lon, objectJsonPersona.lat).transform(toProjection, fromProjection);
          } else {
            pos = new OpenLayers.LonLat(-64.11844, -32.17022).transform(toProjection, fromProjection);
          }
          position = pos;
          let marker = null;
          let markerSelec = null;
          let zoom = 15;
          let positionFormas = null;
          let icon = new OpenLayers.Icon('./images/icons/location.png');
          let charCodeLetter = null;
          map.addLayer(mapnik);
          let markers = new OpenLayers.Layer.Markers("Markers");
          map.addLayer(markers);
          let popup = null;
          let size = new OpenLayers.Size(8,8);
          let offset = new OpenLayers.Pixel(-(size.w/2), -size.h);

          map.addControl(new OpenLayers.Control.PanZoomBar());
          map.addControl(new OpenLayers.Control.Navigation());
          map.addControl(new OpenLayers.Control.ArgParser());

          OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {               
                  defaultHandlerOptions: {
                    'single': true,
                    'double': false,
                    'pixelTolerance': 0,
                    'stopSingle': false,
                    'stopDouble': false
                  },

                  initialize: function(options) {
                    this.handlerOptions = OpenLayers.Util.extend(
                    {}, this.defaultHandlerOptions
                    );
                    OpenLayers.Control.prototype.initialize.apply(
                    this, arguments
                    );
                    this.handler = new OpenLayers.Handler.Click(
                    this, {
                      'click': this.trigger
                    }, this.handlerOptions
                    );
                  },

                  trigger: function(e) {
                    let lonlat = map.getLonLatFromPixel(e.xy);
                    lonlat = lonlat.add(0, 300)
                    if (marker) marker.display(false);
                    if (markerSelec) markerSelec.display(false);
                    markerSelec = new OpenLayers.Marker(lonlat, icon.clone());
                    markers.addMarker(markerSelec);
                    map.setCenter(lonlat, map.getZoom());
                    lonlat = lonlat.transform(fromProjection, toProjection);
                    $("#lat").val(lonlat.lat);
                    $("#lon").val(lonlat.lon);
                  }

          });

          let click = new OpenLayers.Control.Click();
          map.addControl(click);
          click.activate();

          positionFormas = pos;
          if (objectJsonPersona.lon && objectJsonPersona.lat) {
            marker = new OpenLayers.Marker(positionFormas, icon.clone());
            markers.addMarker(marker);
          }

          let feature = new OpenLayers.Feature(markers, positionFormas);
          map.setCenter(position, zoom);
      }
    }

  </script>

</head>

<body>
  <div class="row">
    <?php
    $Element = new Elements();
    echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_PERSONA);
    ?>
    <div class="col-md-9">
      <div class="row">
        <div class="col"></div>
        <div class="col-10 Titulo">
          <p>Persona</p>
        </div>
        <div class="col"></div>
      </div><br>
      <br>
      <div class="row">
        <div class="col-10">
          <!-- Search -->
          <div class="row">
            <?php
            if (isset($_REQUEST["ID"]) && $_REQUEST["ID"] != null) {
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

              ?>
              <div class="col-10">
                <form method="post" onKeydown="return event.key != 'Enter';" action="Controladores/ModificarPersona.php">
                  <input type="hidden" name="ID" value="<?php echo $Persona->getID_Persona(); ?>">
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Apellido: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Apellido" id="inputPassword" autocomplete="off"
                        value="<?php echo $Persona->getApellido(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Nombre: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Nombre" id="inputPassword" autocomplete="off"
                        value="<?php echo $Persona->getNombre(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Documento: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="DNI" id="inputPassword" autocomplete="off"
                        value="<?php echo $Persona->getDNI(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm" style="margin-bottom: -8px;">Fecha
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
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Años: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Edad" id="Edad" autocomplete="off" readonly
                        value="<?php echo $Persona->getEdad(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Meses: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Meses" id="Meses" autocomplete="off" readonly
                        value="<?php echo $Persona->getMeses(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Nro. Carpeta: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Nro_Carpeta" id="inputPassword" autocomplete="off"
                        value="<?php echo $Persona->getNro_Carpeta(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Nro. Legajo: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Nro_Legajo" id="Nro_Legajo" autocomplete="off" <?php if ($Nro_Legajo != "null") {
                        echo "value = '" . $Persona->getNro_Legajo() . "'";
                      }
                      ; ?>>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Localidad: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Localidad" id="inputPassword" autocomplete="off"
                        value="<?php echo $Persona->getLocalidad(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Barrio: </label>
                    <div class="col-md-10">
                      <?php
                      $Element = new Elements();
                      echo $Element->CBModBarrios($Persona->getId_Barrio());
                      ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Domicilio: </label>
                    <div class="col-md-6">
                      <?php
                      if (!empty($Persona->getId_Calle())) {
                        echo $Element->CBCallesNombre($Persona->getId_Calle());
                      } else {
                        echo $Element->CBCallesNombre($Persona->getCalle());
                      }
                      ?>

                    </div>
                    <div class="col-md-2">
                      <input type="number" class="form-control" name="NumeroDeCalle" id="NumeroDeCalle" placeholder="Nro"
                        min="1" autocomplete="off" <?php
                        $NroCalle = $Persona->getNro();
                        if ($NroCalle !== null) {
                          echo "value = '$NroCalle'";
                        } else {
                          echo "value =" . (($Persona->getNroCalle()) ? $Persona->getNroCalle() : "");
                        } ?>>
                    </div>
                    <div class="col-md-2">
                      <button id="mapa-sig" type="button" class="btn btn-secondary" disabled data-toggle="modal"
                        style="background-color: #ffc6b1; color: black; border-color: white; " data-target="#map-modal">S.
                        I. G.</button>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Manzana: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Manzana" id="inputPassword" autocomplete="off" <?php if ($Manzana != "null") {
                        echo "value = '" . $Persona->getManzana() . "'";
                      }
                      ; ?>>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Lote: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Lote" id="inputPassword" autocomplete="off"
                        value="<?php echo $Persona->getLote(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Sub-lote: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Familia" id="inputPassword" autocomplete="off"
                        value="<?php echo $Persona->getFamilia(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Telefono: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Telefono" id="inputPassword" autocomplete="off"
                        value="<?php echo $Persona->getTelefono(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Mail: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Mail" id="inputPassword" autocomplete="off"
                        value="<?php echo $Persona->getMail(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Obra Social(Si/No): </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Obra_Social" id="inputPassword" autocomplete="off"
                        value="<?php echo $Persona->getObra_Social(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Escuela: </label>
                    <div class="col-md-10">
                      <?php
                      echo $Element->CBModEscuelas($Persona->getID_Escuela());
                      ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Lugar de Trabajo: </label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="Trabajo" id="inputPassword" autocomplete="off"
                        value="<?php echo $Persona->getTrabajo(); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">Observación: </label>
                    <div class="col-md-10">
                      <textarea class="form-control" row="3" name="Observaciones"
                        value="<?php echo $Persona->getObservaciones(); ?>"><?php echo $Persona->getObservaciones(); ?></textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Cambio de Domicilio:
                    </label>
                    <div class="col-md-10">
                      <textarea class="form-control" row="3" name="Cambio_Domicilio"
                        value="<?php echo $Persona->getCambio_Domicilio(); ?>"><?php echo $Persona->getCambio_Domicilio(); ?></textarea>
                    </div>
                  </div>
                  <input type="hidden" id="lat" name="lat" value="">
                  <input type="hidden" id="lon" name="lon" value="">
                  <div class="form-group row">
                    <div class="offset-md-2 col-md-10">
                      <button type="submit" class="btn btn-outline-success">Guardar</button>
                      <button type="button" class="btn btn-danger"
                        onClick="location.href = 'view_personas.php'">Atras</button>
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
              <!-- <button type = "button" class = "btn btn-outline-secondary" onClick = "location.href = 'view_personas.php'">Volver</button> -->
            </div>
          </div>
        </div>
      </div>
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
        </div>
        <div class="modal-body" style="padding-top: 0px">
          <div id="basicMap"></div>
        </div>
      </div>
    </div>
  </div>
  <?php
  if (isset($_REQUEST["Mensaje"])) {
    echo "<script type='text/javascript'>
    swal('" . $_REQUEST['Mensaje'] . "','','success');
</script>";
  }

  if (isset($_REQUEST['MensajeError'])) {
    echo "<script type='text/javascript'>
    swal('" . $_REQUEST['MensajeError'] . "','','warning');
</script>";
  }
  ?>
  <script>
    objectJsonPersona.lat = <?php echo $Persona->getLatitud(); ?>;
    objectJsonPersona.lon = <?php echo $Persona->getLonguitud(); ?>;
  </script>
</body>

</html>