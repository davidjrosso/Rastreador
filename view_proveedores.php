<div?php 
require_once "Controladores/CtrProveedor.php";
require_once "Controladores/Elements.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sistema Combustibles</title>
	<link rel="stylesheet" type="text/css" href="css/Estilos.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
	<!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->

</head>
<body>
<div class = "row">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario);
  ?>
  </div>
	<div class = "col-9">
	   <div class = "container">
      <div class = "col-7">
           <!-- Carga -->
          <p class = "Titulos">Cargar Nuevo Proveedor</p>
          <form method = "post" action = "Controladores/InsertProveedor.php">
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Proveedor: </label>
              <div class="col-md-10">
                <input type="password" class="form-control" name = "NombreProv" id="inputPassword" placeholder="Name" width="100%" autocomplete="off" required>
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
          <p class = "Titulos">Buscar Proveedor</p>
          <form class="form-inline" method = "post" action = "view_proveedores.php">
            <div class="form-group mb-2">
               <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Buscar: </label>
            </div>
            <div class="form-group mx-sm-2 mb-2">
              <input type="text" class="form-control" id="inputPassword2" placeholder="Filtro" name = "Filtro" autocomplete="off">
            </div>
            <button type="submit" class="btn btn-primary mb-2"><span class="fa fa-search fa-lg"></span>  IR</button>
          </form>
          <div class = "row">
          <?php  
            if(isset($_REQUEST["Filtro"]) && $_REQUEST["Filtro"]!=null){
              $DTProveedor = new CtrProveedor();
              echo $DTProveedor->getProveedoresxNombre($_REQUEST["Filtro"]);
            }
          ?>
        </div>
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