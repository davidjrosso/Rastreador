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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
  <link rel="import" href="https://sites.google.com/view/generales2019riotercero/página-principal">

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="js/ValidarUsuario.js"></script>
	<script src="dist/control.js"></script>
 
  <script type="text/javascript">
      var getImport = document.quearySelector ('link [rel = import]'); 
      var getContent = getImport.import.querySelector('body');

      var ContenidoPagina = document.getElementById("ContenidoPagina");
      let mensajeError = '<?php echo $mensaje_error;?>';
      let mensajeSuccess = '<?php echo $mensaje_success;?>';
      ContenidoPagina.appendChild(document.importNode(getContent, true));

			$(document).ready(function() {
			  controlMensaje(mensajeSuccess, mensajeError);
      });

      function verPassword(){
        var x = document.getElementById("user-pass");
        if (x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";    
      }
    }
  </script>

</head>
<body>
<div class = "row">
<?php
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_USUARIO);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Perfil Usuario</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Carga -->
          <form method = "post" onKeydown="return event.key != 'Enter';" action = "modificar_usuario" onSubmit = "return ValidarModificacionUsuario();">
            <div class="form-group row">
              <label for="Apellido" class="col-md-2 col-form-label LblForm">Apellido*: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "lastname" id="Apellido" value="<?php echo $lastname;?>"  autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="Nombre" class="col-md-2 col-form-label LblForm">Nombre*: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "firstname" id="Nombre" value="<?php echo $firstname;?>" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Iniciales: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "initials" id="inputPassword" value="<?php echo $initials;?>" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="user-name" class="col-md-2 col-form-label LblForm">Nombre de Usuario*: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" readonly name = "username" id = "user-name" value="<?php echo $username;?>" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="user-pass" class="col-md-2 col-form-label LblForm">Password*: </label>
              <div class="col-md-9">
                <input type="password" class="form-control input-password" name = "userpass" id = "user-pass" autocomplete="off">
              </div>
              <div class="col-md-1 div-buttom-padding">
                <button type="button" class="btn btn-primary" onclick="verPassword()">Ver</button>
              </div>
            </div>
            <div class="form-group row">
              <label for="email" class="col-md-2 col-form-label LblForm">E-Mail: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "email" id="email" value="<?php echo $email;?>" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <div class="offset-md-2 col-md-10">
                <button type="submit" class="btn btn-outline-success">Guardar</button>
                <button type = "button" class = "btn btn-danger" onClick = "location.href = '/'">Atras</button>
              </div>
            </div>
            <input type="number" hidden id="account_id" name="account_id" value="<?php echo $AccountID;?>"> 
          </form>
          <div class="row">
              <div class="col-10"></div>
              <div class="col-2">
                
              </div>
          </div>
          <!-- Fin Carga -->
  </div>
</div>
</div>
</body>
</html>