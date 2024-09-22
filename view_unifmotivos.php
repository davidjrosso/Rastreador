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
       function buscarMotivos_1(){
          var xMotivo = document.getElementById('SearchMotivos_1').value;
          var textoBusqueda = xMotivo;
          xmlhttp=new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              contenidosRecibidos = xmlhttp.responseText;
              document.getElementById("ResultadosMotivos_1").innerHTML=contenidosRecibidos;
              }
          }
          xmlhttp.open('POST', 'buscarMotivos_1.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
          xmlhttp.send();
        }

        function buscarMotivos_2(){
          var xMotivo = document.getElementById('SearchMotivos_2').value;
          var textoBusqueda = xMotivo;
          xmlhttp=new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              contenidosRecibidos = xmlhttp.responseText;
              document.getElementById("ResultadosMotivos_2").innerHTML=contenidosRecibidos;
              }
          }
          xmlhttp.open('POST', 'buscarMotivos_2.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
          xmlhttp.send();
        }

        function seleccionMotivo_1(xMotivo,xID){
          var Motivo = document.getElementById("Motivo_1");
          var ID_Motivo = document.getElementById("ID_Motivo_1");
          Motivo.innerHTML = "";
          Motivo.innerHTML = "<p>"+xMotivo+"</p>";
          ID_Motivo.setAttribute('value',xID);
        }

        function seleccionMotivo_2(xMotivo,xID){
          var Motivo = document.getElementById("Motivo_2");
          var ID_Motivo = document.getElementById("ID_Motivo_2");
          Motivo.innerHTML = "";
          Motivo.innerHTML = "<p>"+xMotivo+"</p>";
          ID_Motivo.setAttribute('value',xID);
        }
  </script>

</head>
<body>
<div class = "row">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_UNIFICACION_MOTIVO);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Unificar Motivo</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <p class = "TextoAdvertenciaUnificar">¡ADVERTENCIA! El segundo motivo seleccionado se unirá al primer motivo seleccionado. El segundo motivo se eliminará.</p>
          <br>
           <!-- Carga -->
          <form method = "post" action = "Controladores/pedirunificarmotivos.php" onSubmit = "return ValidarUnifMotivos();">
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-3 col-form-label LblForm">Primer Motivo: </label>
                  <div class="col-md-9" id = "Motivo_1">
                    <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo_1">Seleccione un Motivo</button>  
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-3 col-form-label LblForm">Segundo Motivo: </label>
                  <div class="col-md-9" id = "Motivo_2">
                    <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo_2">Seleccione un Motivo</button>  
                  </div>
              </div>  
              <div class="form-group row">
              <div class="offset-md-3 col-md-9">
                <input type="hidden" name="ID_Motivo_1" id = "ID_Motivo_1" value = "0">
                <input type="hidden" name="ID_Motivo_2" id = "ID_Motivo_2" value = "0">
                <button type="submit" class="btn btn-outline-success">Aceptar</button>
                <button type="button" class="btn btn-outline-secondary" onclick="location.href = 'view_inicio.php'">Volver</button>
              </div>
            </div>
          </form>
          <br><br>
          <!-- Fin Carga -->
          <!-- Modal SELECCION MOTIVO -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Motivo</h5>
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
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos_1" onKeyUp="buscarMotivos_1()" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos_1">
                    
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
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Motivo</h5>
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
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos_2" onKeyUp="buscarMotivos_2()" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos_2">
                    
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