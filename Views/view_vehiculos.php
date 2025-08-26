<?php 
require_once "Controladores/CtrVehiculo.php";
require_once "Controladores/Elements.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sistema Combustibles</title>
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->

</head>
<body>
<div class = "row">
  <div class = "col-3">
    <div class="nav-side-menu">
    <div class="brand">Secciones</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
           <?php $Element = new Elements();
            $Element->getMenu(3);?>
        </div>
        <div class="brand">Informes</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuInformes(0);?>
        </div>
</div>
  </div>
  <div class = "col-9">
     <div class = "row">
      <div class = "col-7">
           <!-- Carga -->
          <p class = "Titulos">Cargar Nuevo Vehiculo</p>
          <form method = "post" action = "Controladores/InsertVehiculo.php">
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Numero: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Numero" id="inputPassword" placeholder="Number" width="100%" autocomplete="off" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Dominio: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Dominio" id="inputPassword" placeholder="Last Name" width="100%" autocomplete="off" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Descripcion: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Detalle" id="inputPassword" placeholder="N° Leg" width="100%" autocomplete="off" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Modelo: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Modelo" id="inputPassword" placeholder="N° Leg" width="100%" autocomplete="off" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Area: </label>
              <div class = "col-md-10">
                <?php 
                  $Elements = new Elements();
                  echo $Elements->CBAreas();
                ?> 
              </div>
            </div>
            <div class="form-group row">
              <div class="offset-md-2 col-md-10">
                <button type="submit" class="btn btn-outline-success">Guardar</button>
              </div>
            </div>
          </form>
          <br><br><br>
          <!-- Fin Carga -->
          <!-- Search -->
          <p class = "Titulos">Buscar Vehiculo</p>
          <form class="form-inline" method = "post" action = "view_vehiculos.php">
            <div class="form-group mb-2">
               <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Buscar: </label>
            </div>
             <div class="form-group mb-2">
               <select class = "form-control" name = "Opcion"><option value = "1">Todos</option><option value = "2">Numero</option><option value = "3">Dominio</option><option value = "4">Area</option></select>
            </div>
            <div class="form-group mx-sm-2 mb-2">
              <input type="text" class="form-control" id="inputPassword2" placeholder="Filtro" name = "Filtro" autocomplete="off">
            </div>
            <button type="submit" class="btn btn-primary mb-2"><span class="fa fa-search fa-lg"></span>  IR</button>
          </form>
           <?php  
            if(isset($_REQUEST["Filtro"]) && $_REQUEST["Filtro"]!=null){
              switch ($_REQUEST["Opcion"]) {
                case 1: $DTVehiculo = new CtrVehiculo();
                        echo $DTVehiculo->getVehiculos();
                break;
                case 2: $DTVehiculo = new CtrVehiculo();
                        echo $DTVehiculo->getVehiculosxNumero($_REQUEST["Filtro"]);
                break;
                case 3: $DTVehiculo = new CtrVehiculo();
                        echo $DTVehiculo->getVehiculosxDominio($_REQUEST["Filtro"]);
                break;
                case 4: $DTVehiculo = new CtrVehiculo();
                        echo $DTVehiculo->getVehiculosxArea($_REQUEST["Filtro"]);
                break;
                
                default:$DTVehiculo = new CtrVehiculo();
                        echo $DTVehiculo->getVehiculos();
                break;
              }
              
            }else{
              //no hacer nada
            }
          ?>

        </div>
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