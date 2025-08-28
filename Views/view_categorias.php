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
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
		<script src="dist/control.js"></script>
  <script>
			$(document).ready(function() {
				let mensajeError = '<?php echo $mensaje_error;?>';
				let mensajeSuccess = '<?php echo $mensaje_success;?>';
			  controlMensaje(mensajeSuccess, mensajeError);
			});
  </script>

</head>
<body>
<div class = "row">
<?php
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_CATEGORIA);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Categorias</p>
      </div>
      <div class="col"></div>
    </div><br>
    <div class="row">
      <div class = "col"></div>
      <div class = "col-4">
          <center><button class = "btn btn-secondary" onClick = "location.href='/categoria/nueva'">Agregar Nueva Categoria</button></center>
      </div>
      <div class="col-2">
                <button type="button" class="btn btn-outline-secondary" onclick="location.href = '/'">Volver</button>
      </div>

      <div class = "col"></div>
    </div>
    <br>
     <div class = "row">
      <div class = "col-10">
           <!-- Carga -->
          <form method = "post" action = "buscar_categorias_filtrado">
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Buscar: </label>
              <div class="col-md-4">
                <input type="text" class="form-control" name = "Search" id="inputPassword" width="100%" autocomplete="off">
              </div>
              <label for="inputPassword" class="col-md-1 col-form-label LblForm">En: </label>
              <div class="col-md-3">
                <select name = "ID_Filtro" class = "form-control">                  
                    <!--  <option value = "Codigo">Codigo</option>-->
                    <option value = "Categoria">Categoría</option>
                    <!-- <option value = "ID">Id</option> -->
                </select>
              </div>
              <div class = "col-md-1">
                  <button class = "btn btn-secondary">Ir</button>
              </div>
            </div>
          </form>
          <br><br>
          <!-- Fin Carga -->
          <!-- Search -->
        <div class = "row">
          <?php  
            if ($Filtro){
              switch ($ID_Filtro) {
                case 'ID':
                  echo $DTGeneral->getCategoriasxID($Filtro);
                  break;
                //case 'Codigo': echo $DTGeneral->getCategoriasxCodigo($Filtro);break;
                case 'Categoria':
                  echo $DTGeneral->getCategoriasxCategoria($Filtro);
                  break;
                default:
                  echo $DTGeneral->getCategoriasxID($Filtro);
                  break;
              }
            } else {
              echo $DTGeneral->getCategorias();
            }
          ?>
        </div>
  </div>
</div>
</div>
</body>
</html>