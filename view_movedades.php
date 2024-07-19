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
  <script src="js/bootstrap-datepicker.min.js"></script> <!-- ESTO ES NECESARIO PARA QUE ANDE EN ESPAÑOL -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="js/ValidarMovEdades.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
      function buscarMotivos(){
          var xMotivo = document.getElementById('SearchMotivos').value;
          var textoBusqueda = xMotivo;
          xmlhttp=new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              contenidosRecibidos = xmlhttp.responseText;
              document.getElementById("ResultadosMotivos").innerHTML=contenidosRecibidos;
              }
          }
          xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
          xmlhttp.send();
        }

        function buscarCategorias(){
          var xCategoria = document.getElementById('SearchCategorias').value;
          var textoBusqueda = xCategoria;
          xmlhttp=new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              contenidosRecibidos = xmlhttp.responseText;
              document.getElementById("ResultadosCategorias").innerHTML=contenidosRecibidos;
              }
          }
          xmlhttp.open('POST', 'buscarCategorias.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
          xmlhttp.send();
        }

        function seleccionMotivo(xMotivo,xID){
          var Motivo = document.getElementById("Motivo");
          var ID_Motivo = document.getElementById("ID_Motivo");
          Motivo.innerHTML = "";
          Motivo.innerHTML = "<p>"+xMotivo+"</p>";
          ID_Motivo.setAttribute('value',xID);
        }

        function seleccionCategoria(xCategoria,xID){
          var Categoria = document.getElementById("Categoria");
          var ID_Categoria = document.getElementById("ID_Categoria");
          Categoria.innerHTML = "";
          Categoria.innerHTML = "<p>"+xCategoria+"</p>";
          ID_Categoria.setAttribute('value',xID);
        }
  </script>
</head>
<body>
<div class = "row">
   <?php  
  if($TipoUsuario == 1){  
  ?>
  <div class = "col-md-3">
    <div class="nav-side-menu">
    <div class="brand">General</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuGeneral(0);?>
        </div>
        <div class="brand">Actualizaciones</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuActualizaciones(0);?>
        </div>
        <div class="brand">Reportes</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuReportes(4);?>
        </div>
        <div class="brand">Unificación</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuUnificacion(0);?>
        </div>
        <div class="brand">Seguridad</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuSeguridad(0);?>
        </div>
        <div class="brand">El Proyecto</div>
        <div class="menu-list">
            <?php $Element = new Elements();
            $Element->getMenuHistorial(0);?>
        </div>
        <div class="brand btn-Salir" onClick = "location.href = 'Controladores/CtrLogout.php'">Salir</div>
    </div>
  </div>
  <?php 
    }
    if($TipoUsuario == 2 || $TipoUsuario > 3){
  ?>
  <div class = "col-md-3">
    <div class="nav-side-menu">
    <div class="brand">General</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuGeneral(0);?>
        </div>
        <div class="brand">Actualizaciones</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuActualizaciones(0);?>
        </div>
        <div class="brand">El Proyecto</div>
        <div class="menu-list">
            <?php $Element = new Elements();
            $Element->getMenuHistorial(0);?>
        </div>
        <div class="brand btn-Salir" onClick = "location.href = 'Controladores/CtrLogout.php'">Salir</div>
    </div>
  </div>
  <?php
  }  
  if($TipoUsuario == 3){    
  ?>
  <div class = "col-md-3">
    <div class="nav-side-menu">
    <div class="brand">General</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuGeneral(0);?>
        </div>
        <div class="brand">Actualizaciones</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuActualizaciones(0);?>
        </div>
        <div class="brand">Reportes</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuReportes(4);?>
        </div>
        <div class="brand">Unificación</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuUnificacion(0);?>
        </div>
        <div class="brand">El Proyecto</div>
        <div class="menu-list">
            <?php $Element = new Elements();
            $Element->getMenuHistorial(0);?>
        </div>
        <div class="brand btn-Salir" onClick = "location.href = 'Controladores/CtrLogout.php'">Salir</div>
    </div>
  </div>
<?php } ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Movimientos por Edades</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
           <!-- Carga -->
          <form method = "post" onKeydown="return event.key != 'Enter';" action = "view_vermovedades.php" onSubmit = "return ValidarMovEdades();">
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Desde *: </label>
                  <div class="col-md-10">
                      <input type="text" name="Edad_Desde" id = "Edad_Desde" class="form-control" autocomplete="off">
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Hasta *: </label>
                  <div class="col-md-10">
                      <input type="text" name="Edad_Hasta" id = "Edad_Hasta" class="form-control" autocomplete="off">
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Manzana: </label>
                  <div class="col-md-10">
                      <input type="text" name="Manzana" class="form-control" autocomplete="off">
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Lote: </label>
                  <div class="col-md-10">
                      <input type="text" name="Lote" class="form-control" autocomplete="off">
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Sub-lote: </label>
                  <div class="col-md-10">
                      <input type="number" name="Familia" class="form-control" autocomplete="off">
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Barrio: </label>
                  <div class="col-md-10">
	                  <?php  
	                  $Element = new Elements();
	                  echo $Element->CBRepBarrios();
	                  ?>
                  </div>
              </div>  
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo: </label>
                  <div class="col-md-10" id = "Motivo">
                    <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo">Seleccione un Motivo</button>   
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Categoria: </label>
                  <div class="col-md-10" id = "Categoria">
                    <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalCategoria">Seleccione una Categoria</button> 
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Responsable: </label>
                  <div class="col-md-10">
                    <?php  
                    $Element = new Elements();
                    echo $Element->CBRepResponsable();
                    ?>
                  </div>
              </div> 
              <div class="form-group row">
              <div class="offset-md-2 col-md-10">
                <input type="hidden" name="ID_Motivo" id = "ID_Motivo" value = "0">
                <input type="hidden" name="ID_Categoria" id = "ID_Categoria" value = "0">
                <button type="submit" class="btn btn-outline-success">Aceptar</button>
              </div>
            </div>
          </form>
          <br><br>
          <!-- Fin Carga -->
          <!-- SECCION DE MODALES -->
          <!-- Modal SELECCION MOTIVO -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos" onKeyUp="buscarMotivos()" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos">
                    
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
      <!-- Modal SELECCION CATEGORIA -->
      <div class="modal fade bd-example-modal-lg" id="ModalCategoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Categoria</h5>
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
                      <input class = "form-control" type="text" name="BuscarCategorias" id = "SearchCategorias" onKeyUp="buscarCategorias()" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosCategorias">
                    
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
      <!-- FIN MODAL SELECCION CATEGORIA -->
          <!-- FIN SECCION DE MODALES -->
  </div>
</div>
</div>
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