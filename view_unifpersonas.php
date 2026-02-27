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
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script src="js/Utils.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <!--<script type="text/javascript" src = "js/Funciones.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="js/ValidarUnifPersonas.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
       function buscarPersonas_1(){
        var xNombre = document.getElementById('SearchPersonas_1').value;
        var textoBusqueda = xNombre;
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosPersonas_1").innerHTML=contenidosRecibidos;
            }
        }
        xmlhttp.open('POST', 'buscarPersonas_1.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
        xmlhttp.send();
      }

      function buscarPersonas_2(){
        var xNombre = document.getElementById('SearchPersonas_2').value;
        var textoBusqueda = xNombre;
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosPersonas_2").innerHTML=contenidosRecibidos;
            }
        }
        xmlhttp.open('POST', 'buscarPersonas_2.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
        xmlhttp.send();
      }

      function seleccionPersona_1(xNombre,xID){
        var Persona = document.getElementById("Persona_1");
        var ID_Persona = document.getElementById("ID_Persona_1");
        Persona.innerHTML = "";
        Persona.innerHTML = "<p>"+xNombre+"</p>";
        ID_Persona.setAttribute('value',xID);
      }

      function seleccionPersona_2(xNombre,xID){
        var Persona = document.getElementById("Persona_2");
        var ID_Persona = document.getElementById("ID_Persona_2");
        Persona.innerHTML = "";
        Persona.innerHTML = "<p>"+xNombre+"</p>";
        ID_Persona.setAttribute('value',xID);
      }

      function VerificarUnificacion(){
              var ID_Persona_1 = document.getElementById("ID_Persona_1");
              var ID_Persona_2 = document.getElementById("ID_Persona_2");
              var Form_1= document.getElementById("form_1");
              var Bandera = ValidarUnifPersonas();
              if (Bandera == false){
                return Bandera;
              }

              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer unificar estas personas?",
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


  </script>

</head>
<body>
<div class = "row margin-right-cero">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_UNIFICACION_PERSONA);
  ?>
  <div class = "col-md-9 inicio-md-2">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Unificar Personas</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row" style="justify-content: center;">
      <div class = "col-10">
          <p class = "TextoAdvertenciaUnificar">¡ADVERTENCIA! Todos los datos y movimientos de la segunda persona seleccionada se unirán a la primera persona seleccionada. La segunda persona se eliminará.</p>
          <br>
           <!-- Carga -->
          <!--<form method = "post" action = "Controladores/unificarpersonas.php" onSubmit = "return VerificarUnificacion();"> -->
          <form id="form_1" method = "post" action = "Controladores/unificarpersonas.php">
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-3 col-form-label LblForm">Primera Persona: </label>
                  <div class="col-md-9" id = "Persona_1">
                    <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalPersona_1">Seleccione una Persona</button>  
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-3 col-form-label LblForm">Segunda Persona: </label>
                  <div class="col-md-9" id = "Persona_2">
                    <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalPersona_2">Seleccione una Persona</button> 
                  </div>
              </div>  
              <div class="form-group row" style="justify-content: center;">
              <div class=" col-md-3">
                <input type="hidden" name="ID_Persona_1" id = "ID_Persona_1" value = "0">
                <input type="hidden" name="ID_Persona_2" id = "ID_Persona_2" value = "0">
                <button type="button" class="btn btn-outline-success" onclick="return VerificarUnificacion();">Aceptar</button>
                <button type="button" class="btn btn-outline-secondary" onclick="location.href = 'view_inicio.php'">Volver</button>

              </div>
             
            </div>
          </form>
          <br><br>
          <!-- Fin Carga -->
          <!-- SECCION DE MODALES -->
      <!-- Modal SELECCION PERSONAS -->
      <div class="modal fade bd-example-modal-lg" id="ModalPersona_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Persona</h5>
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
                      <input class = "form-control" type="text" name="BuscarPersona" id = "SearchPersonas_1" onKeyUp="buscarPersonas_1()" autocomplete="off" placeholder="Ingrese el nombre, apellido o documento de la persona">
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
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION PERSONAS -->
      <!-- Modal SELECCION PERSONAS -->
      <div class="modal fade bd-example-modal-lg" id="ModalPersona_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Persona</h5>
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
                      <input class = "form-control" type="text" name="BuscarPersona" id = "SearchPersonas_2" onKeyUp="buscarPersonas_2()" autocomplete="off" placeholder="Ingrese el nombre, apellido o documento de la persona">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosPersonas_2">
                    
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
      <!-- FIN MODAL SELECCION PERSONAS -->
      <!-- FIN SELECCION MODAL -->
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