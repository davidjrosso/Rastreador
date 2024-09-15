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
  <script src="js/ValidarMovFamilias.js"></script>
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
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Movimientos por Familia</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
           <!-- Carga -->
          <form method = "post" onKeydown="return event.key != 'Enter';" action = "view_vermovfamilias.php">
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Sub-lote : </label>
                  <div class="col-md-10">
                      <input type="number" name="Familia" id = "Familia" class="form-control" autocomplete="off">
                  </div>
              </div>
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Domicilio : </label>
                  <div class="col-md-10">
                    <input type="text" class="form-control" name = "Domicilio" id="inputPassword" autocomplete="off">
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Manzana : </label>
                  <div class="col-md-10">
                      <input type="text" name="Manzana" id = "Manzana" class="form-control" autocomplete="off">
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Lote : </label>
                  <div class="col-md-10">
                      <input type="text" name="Lote" id = "Lote" class="form-control" autocomplete="off">
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
              <div class="offset-md-2 col-md-10">
                <button type="submit" class="btn btn-outline-success">Aceptar</button>
              </div>
            </div>
          </form>
          <br><br>
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