<?php 
session_start(); 
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
header("Content-Type: text/html;charset=utf-8");

/*     CONTROL DE USUARIOS                    */
if(!isset($_SESSION["Usuario"])){
    header("Location: Error_Session.php");
}

$Con = new Conexion();
$Con->OpenConexion();
$ID_Usuario = $_SESSION["Usuario"];
$ConsultarTipoUsuario = "select ID_TipoUsuario from accounts where accountid = $ID_Usuario";
$MensajeErrorConsultarTipoUsuario = "No se pudo consultar el Tipo de Usuario";
$EjecutarConsultarTipoUsuario = mysqli_query($Con->Conexion,$ConsultarTipoUsuario) or die($MensajeErrorConsultarTipoUsuario);
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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <!--<script type="text/javascript" src = "js/Funciones.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="js/ValidarUnifPersonas.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
        var Personas = [];

        function buscarDireccionModal(){
          var xDireccion = document.getElementById('SearchDireccion').value;
          var textoBusqueda = xDireccion;
          xmlhttp=new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              contenidosRecibidos = xmlhttp.responseText;
              document.getElementById("ResultadosPersonas_1").innerHTML=contenidosRecibidos;
              document.getElementById("ResultadosDirecciones").innerHTML=contenidosRecibidos;
              }
          }
          xmlhttp.open('POST', 'buscarUnifDirecciones.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
          xmlhttp.send();
        }

        function actualizarContenido(){
          var BotonModalPersona = document.getElementById("BotonModalPersona_1");
          var SearchDireccionValue = document.getElementById("SearchDireccion").value;
          
          if(SearchDireccionValue != ""){
            BotonModalPersona.innerText = SearchDireccionValue;
          } else {
            BotonModalPersona.innerText = "Buscar Dirección";
          }
        }

        function actualizarContenidoNuevaDireccion(){
          var BotonModalPersona = document.getElementById("BotonModalNuevaDireccion_1");
          var SearchDireccionValue = document.getElementById("DireccionNueva_1").value;
          var Calle = document.getElementById("Calle");
          if (SearchDireccionValue == "") {
            BotonModalPersona.innerText = "Nueva Dirección";
            Calle.value = "";
          }else {
            BotonModalPersona.innerText = SearchDireccionValue;
            Calle.value = SearchDireccionValue;
          }
        }

        function seleccionDireccion(xID_Persona,xBoton){
          var ResultadoPersonas = document.getElementById("ResultadosPersonas_1");
          var ResultadoDirecciones = document.getElementById("ResultadosDirecciones");
          var Table_1 = ResultadoPersonas.childNodes[0];
          var Table_2 = ResultadoDirecciones.childNodes[0];
          var IndexFilaTabla = xBoton.parentElement.parentElement.rowIndex;
          var Boton_1 = Table_1.rows[IndexFilaTabla].cells[2].childNodes[0];
          var Boton_2 = Table_2.rows[IndexFilaTabla].cells[2].childNodes[0];
          Personas.push(xID_Persona);
          var ArrPersonas = document.getElementById("ArrPersonas");
          ArrPersonas.value = Personas;
          Boton_1.setAttribute('class','btn btn-danger disabled');
          Boton_2.setAttribute('class','btn btn-danger disabled');
        }

        function VerificarUnificacion(){
              var Form_1= document.getElementById("form_1");
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer unificar estas direcciones?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((result) => {
                if (result) {
                  Form_1.submit();
                  return true;
                } else {
                  return false;
                }
              });
        }

        function buscarCalle(){
          var xNombre = document.getElementById('DireccionNueva_1').value;
          var textoBusqueda = xNombre;
          xmlhttp=new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              contenidosRecibidos = xmlhttp.responseText;
              document.getElementById("ResultadosDireccion").innerHTML=contenidosRecibidos;
              }
          }
          xmlhttp.open('POST', 'buscarCalle.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
          xmlhttp.send();
        }

        function seleccionCalle(xNombre,xID){
          var BotonModalPersona = document.getElementById("BotonModalNuevaDireccion_1");
          var Calle = document.getElementById("Calle");
          BotonModalPersona.innerHTML = "";
          BotonModalPersona.innerHTML = xNombre;
          Calle.setAttribute('value',xNombre);
        }

  </script>

</head>
<body>
<div class = "row">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_UNIFICACION_CALLE);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Unificar Calles</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <p class = "TextoAdvertenciaUnificar">¡ADVERTENCIA! Al nombre de calle que ingrese se le asignarán todos los nombres de calles que seleccione en las personas registradas.</p>
          <br>
           <!-- Carga -->
          <!--<form method = "post" onKeydown="return event.key != 'Enter';" action = "Controladores/unificardirecciones.php" onSubmit = "return ValidarUnifDirecciones();">-->
          <form method = "post" onKeydown="return event.key != 'Enter';" id="form_1" name="form_1" action = "Controladores/unificardirecciones.php">
          <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Cambiar: </label>
              <div class="col-md-8">
                <?php 
                  $Element = new Elements();
                  echo $Element->CBCalles();
                ?>
              </div>
          </div>
          <!--<div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Cambiar: </label>
              <div class="col-md-8">
                <button type = "button" id="BotonModalNuevaDireccion_1" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalDireccionNueva">Nombre de Calle elegido</button>-->
                <!--<input type="text" class="form-control" name="Calle" placeholder="Nueva Direccion" autocomplete="off"> -->
                <!--<input type="hidden" name="Calle" id = "Calle" value = "">
              </div>
            </div>-->
            <div class="form-group row">
              <div class="col-md-2"></div>
              <div class="col-md-8">
                <button type = "button" id="BotonModalPersona_1" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalPersona_1">Nombre de Calle a cambiar</button>
                <input type="hidden" name="ArrPersonas" id="ArrPersonas" value="0">
                <!-- <input type="text" class="form-control" id="SearchDireccion" onKeyUp="buscarDireccion()" placeholder="Buscar Direccion" autocomplete="off"> -->
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-2"></div>
              <div class="col-md-4">
                <!--<button type="submit" class="btn btn-primary btn-block">Ok</button>-->
                <button type="button" class="btn btn-outline-success" onclick="return VerificarUnificacion()">Aceptar</button>
                <button type="button" class="btn btn-outline-secondary" onclick="location.href = 'view_inicio.php'">Volver</button>
              </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
                <div class="col-md-8" id="ResultadosDirecciones">
                      <p>No se ha realizado ninguna búsqueda.</p>
                </div>         
            </div> 
          </form>
          <br><br>
          <!-- Fin Carga -->
          <!-- Modal SELECCION MOTIVO -->
      <div class="modal fade bd-example-modal-lg" id="ModalCentro_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Centro</h5>
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
                      <input class = "form-control" type="text" name="BuscarCentros" id = "SearchCentros_1" onKeyUp="buscarCentros_1()" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosCentros_1">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION MOTIVO -->
      <!-- Modal SELECCION MOTIVO -->
      <div class="modal fade bd-example-modal-lg" id="ModalCentro_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Centro</h5>
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
                      <input class = "form-control" type="text" name="BuscarCentros" id = "SearchCentros_2" onKeyUp="buscarCentros_2()" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosCentros_2">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION MOTIVO -->

      <!-- Modal SELECCION PERSONAS -->
      <div class="modal fade bd-example-modal-lg" id="ModalPersona_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Buscar Dirección</h5>
              <button type="button" class="close" data-dismiss="modal"  onclick="actualizarContenido();" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-8">
                    <div class="input-group mb-3">
                      <input class = "form-control" type="text" name="BuscarPersona" id = "SearchDireccion" onKeyUp="buscarDireccionModal()" autocomplete="off" placeholder="Ingrese la dirección">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosPersonas_1">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="actualizarContenido();">OK</button>
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION PERSONAS -->

      <!-- Modal INGRESO DE NUEVA DIRECCION -->
      <div class="modal fade bd-example-modal-lg" id="ModalDireccionNueva" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Nueva Dirección</h5>
              <!--<button type="button" class="close" onclick="actualizarContenidoNuevaDireccion();" data-dismiss="modal" aria-label="Close">-->
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-8">
                    <div class="input-group mb-3">
                      <input class = "form-control" type="text" name="DireccionNueva_1" id = "DireccionNueva_1" onKeyUp="buscarCalle()" placeholder="Ingrese la dirección nueva" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosDireccion">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <!--<button type="button" class="btn btn-danger" onclick="actualizarContenidoNuevaDireccion();" data-dismiss="modal">Cerrar</button>-->
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL INGRESO DE NUEVA DIRECCION -->

  </div>
</div>
</div>
<?php  
if(isset($_REQUEST["Mensaje"])){
  echo "<script type='text/javascript'>
  swal('".$_REQUEST["Mensaje"]."','','success');
</script>";
}
if(isset($_REQUEST["MensajeError"])){
  echo "<script type='text/javascript'>
  swal('".$_REQUEST["MensajeError"]."','','warning');
</script>";
}
?>
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
?>
</body>
</html>