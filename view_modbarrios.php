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

require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . '/Modelo/Barrio.php');
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");

header("Content-Type: text/html;charset=utf-8");

$id_usuario = $_SESSION["Usuario"];
$account = new Account(account_id: $id_usuario);
$tipo_usuario = $account->get_id_tipo_usuario();

?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <base href="/">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="./dist/mapa.js"></script>
  <script>
    let map = null;
    let objectJsonBarrio = {};
    let fullscreen = false;
    let nombreCalle = null;
    $(document).ready(function(){
          $("#boton-min").on("click", function (e) {
            $("button[class='ol-zoom-out']").click();
          });

          $("#boton-plus").on("click", function (e) {
            $("button[class='ol-zoom-in']").click();
          });

          $("#mapa-sig").on("click", function (e) {
            let calleId = $("#Calle").val();
            let nro = $("#NumeroDeCalle").val();
            nombreCalle = $("#BotonModalDireccion_1").text();
            if (!nombreCalle || !nro) {
              map.addPersonMap(
                            objectJsonBarrio.lat,
                            objectJsonBarrio.lon,
                            null
                            );
            } else if (nombreCalle && nro) {
              map.addPersonMapAddress(
                                      nombreCalle,
                                      nro,
                                      calleId
                                    );
            }
          });

          $("#NumeroDeCalle").on("input", function(e) {
            let calle = $("#Calle").val();
            let nro = $(this).val();
            if (calle && nro) {
              $("#mapa-sig").prop('disabled', false);
            } else {
              $("#mapa-sig").prop('disabled', true);
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

          if (!map) {
              map = init(
                          objectJsonBarrio.lat, 
                          objectJsonBarrio.lon,
                          map
              );
              map.setGeoreferenciacion();

              if (objectJsonBarrio.lon && objectJsonBarrio.lat) {
                map.addPersonMap(
                  objectJsonBarrio.lon,
                  objectJsonBarrio.lat,
                  null
                );
              } else {
                $("#mapa-sig").prop('disabled', true);
              }
          }

      });

      function buscarCalles(){
        let xNombre = $('#SearchCalle').val();
        let textoBusqueda = xNombre;
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            contenidosRecibidos = xmlhttp.responseText;
            $("#ResultadosCalles").html(contenidosRecibidos);
            }
        }
        xmlhttp.open('POST', 'buscarCalle.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
        xmlhttp.send();
      }

      function seleccionCalle(xNombre, xID){
        let BotonModalPersona = $("#BotonModalDireccion_1");
        let calle = $("#Calle");
        nombreCalle = xNombre;
        BotonModalPersona.html("");
        BotonModalPersona.html(xNombre);
        calle.prop('value',xID);
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
<div class = "row">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($tipo_usuario, $id_usuario, $Element::PAGINA_BARRIO);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Modificación Barrio</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Search -->
        <div class = "row">
          <?php  
            if(isset($_REQUEST["ID"]) && $_REQUEST["ID"]!=null){

              $Con = new Conexion();
              $Con->OpenConexion();
              $ID_Barrio = $_REQUEST["ID"];

		          $barrio = new Barrio(coneccion: $Con, id_barrio: $ID_Barrio);
              $ID_Barrio = $barrio->get_id_barrio();
              $Barrio = $barrio->get_barrio();
              $geo_lat_barrio = $barrio->get_lat_georeferencia();
              $geo_lon_barrio = $barrio->get_lon_georeferencia();

              ?>
            <div class = "col-10">
            <form method = "post" onKeydown="return event.key != 'Enter';" action = "modificar_barrio">
                <!-- <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Id: </label>
                  <div class="col-md-10">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">< ?php echo $ID_Barrio; ?></label>
                  </div>
                </div> -->
                <input type="hidden" name="ID" value = "<?php echo $ID_Barrio; ?>">
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Barrio: </label>
                  <div class="col-md-10">
                    <input type="text" class="form-control" name = "Barrio" id="inputPassword" autocomplete="off" value = "<?php echo $Barrio; ?>">
                  </div>
                </div>
                <div class="form-group row" style="margin-bottom: 0.6rem;">
                  <label for="BotonModalDireccion_1" class="col-md-2 col-form-label LblForm">Ubicacion: </label>
                  <div class="col-md-6" id = "Persona">
                      <button type = "button" id="BotonModalDireccion_1" class = "btn btn-lg btn-primary btn-block" style="padding-top: 4px;padding-bottom: 4px;" data-toggle="modal" data-target="#ModalCalle">Seleccione una Calle</button>
                  </div>
                  <div class="col-md-2">
                    <input type="number" class="form-control" style="margin-top: 1px; padding-right: 0px;" name = "NumeroDeCalle" id="NumeroDeCalle" placeholder="Número" min="1" autocomplete="off">
                  </div>
                  <div class="col-md-2">
                    <button id="mapa-sig" type="button" class="btn btn-secondary" data-toggle="modal"
                      style="background-color: #ffc6b1; color: black; border-color: white; " data-target="#map-modal">
                      S.I. G.
                    </button>
                  </div>
              </div>
                <div class="form-group row" style="margin-top: 36px;">
                  <div class="offset-md-4 col-md-8">
                    <button type="submit" class="btn btn-outline-success">Guardar</button>
                    <button type = "button" class = "btn btn-danger" onClick = "location.href = '/barrios'">Atras</button>
                  </div>
                </div>
                <input type="hidden" id="lat" name="lat" value="<?php echo $geo_lat_barrio?>">
                <input type="hidden" id="lon" name="lon" value="<?php echo $geo_lon_barrio?>">
                <input type="hidden" name="Calle" id="Calle" value = "">
            </form>
            <div class="row">
                <div class="col-10"></div>
                <div class="col-2">
                  
                </div>
            </div>
            </div>

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
                        <input type="hidden" id="lat" name="lat" value="">
                        <input type="hidden" id="lon" name="lon" value="">
                        <input type="hidden" name="Calle" id="Calle" value = "">
                      </form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>			        
                    </div>
                  </div>
              </div>
            </div>
            <!-- Modal de Carga de Calle-->

            <!-- Modal de georeferencia -->
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
                      <!--
                      <button type="button" id="boton-save" class="button-fullscreen" onclick="showControlFormulario();" aria-label="save">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                            <path d="M11 2H9v3h2z"/>
                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                          </svg>
                      </button>
                      -->
                    </div>
                    <div class="modal-body" style="padding-top: 0px">
                      <div id="basicMap"></div>
                      <div id="desplegable" style="display: none; position: absolute; top: 9px; left: 20px; z-index: 1000">
                        <table class="tabla-direccion">
                            <thead>
                              <th> </th>
                              <th> </th>
                            </thead>
                            <tbody> 
                              <tr>
                                <td>
                                  Calle
                                </td>
                                <td  id="calle-georeferencia">
                                  no disponible
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  Nro
                                </td>
                                <td id="nro-georeferencia">
                                  no disponible
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  Barrio
                                </td>
                                <td id="barrio-georeferencia">
                                  no disponible
                                </td>
                              </tr>
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <!-- Modal de georeferencia -->            
            <?php  
              } else {
                $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID del Barrio";
                echo $Mensaje;
              }
            ?>
        </div>
  </div>
</div>
</div>
<?php  
if(isset($_REQUEST['Mensaje'])){
  echo "<script type='text/javascript'>
  swal('".$_REQUEST['Mensaje']."','','success');
</script>";
}
if(isset($_REQUEST['MensajeError'])){
  echo "<script type='text/javascript'>
  swal('".$_REQUEST['MensajeError']."','','warning');
</script>";
}
?>

<script>
  objectJsonBarrio.lat = <?php echo $geo_lat_barrio?>;
  objectJsonBarrio.lon = <?php echo $geo_lon_barrio?>;
</script>

</body>
</html>