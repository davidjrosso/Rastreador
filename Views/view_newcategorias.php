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
  <base href="/">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="js/ValidarCategoria.js"></script>
	<script src="dist/control.js"></script>

  <!-- Include the plugin's CSS and JS: -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/davidstutz/bootstrap-multiselect@master/dist/js/bootstrap-multiselect.min.js"></script>

  <script type="text/javascript">

			let mensajeError = '<?php echo $mensaje_error;?>';
			let mensajeSuccess = '<?php echo $mensaje_success;?>';

      /*var getImport = document.quearySelector ('link [rel = import]'); 
      var getContent = getImport.import.querySelector('body');

      var ContenidoPagina = document.getElementById("ContenidoPagina");

      ContenidoPagina.appendChild(document.importNode(getContent, true));
      */

      $(document).ready(function() {
        $('#Tipo_Usuario').multiselect({
            onChange: function(element, checked) {
            var opts = $('*[data-group="'+ element.val() +'"]');
                if (checked === true) {
                    opts.prop('disabled', false).prop('selected', false);
                }
                else if (checked === false) {
									opts.prop('disabled', true).prop('selected', false);
                }
            }
        });
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
        <p>Nueva Categoría</p>
      </div>
      <div class="col"></div>
    </div><br>
    <div class="row">
      <div class="col"></div>
      <div class="col-10">
          <!--
          <div class="row">
              <center><button class = "btn btn-secondary btn-sm" onClick="location.href ='view_newcategorias.php'">Agregar Nueva Categoría</button></center>
          </div>
          -->
      </div>
      <div class="col"></div>
    </div>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Carga -->
          <p class = "Titulos">Cargar Nueva Categoría</p>
          <form method = "post" onKeydown="return event.key != 'Enter';" id = "form_1" action = "/crear_categoria" onSubmit = "return ValidarCategoria();">
          <div class="form-group row">
              <label id="denominacion" for="Categoria" class="col-md-2 col-form-label LblForm">Denominación</label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Categoria" id="Categoria" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="ID_Forma" class="col-md-2 col-form-label LblForm">Forma</label>
              <div class="col-md-10">
                <?php
                echo $Element->CBFormas_Categoria();?>
              </div>
            </div>
            <div class="form-group row">
              <label for="Codigo" class="col-md-2 col-form-label LblForm">Código</label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Codigo" id="Codigo" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label id="grupousuarios" for="Tipo_Usuario" class="col-md-2 col-form-label LblForm">Permisos</label>
              
              <div class="col-md-10">
                <?php
                echo $Element->CBTipos_Usuario();?>              
              </div>
            </div>
            <div class="form-group row">
              <div class="offset-md-2 col-md-10">
                <button type="button" class="btn btn-outline-success" onClick ="VerificarCreacionCategoria();">Guardar</button>
                <button type = "button" class = "btn btn-danger" onClick = "location.href = '/categorias'">Atrás</button>
              </div>
            </div>
          </form>
          <!-- Fin Carga -->
          <div class="row">
            <div class="col-10"></div>
            <div class="col-2">
              
            </div>
        </div>
  </div>
</div>
</div>
</body>
</html>