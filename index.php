<?php
//require_once $_SERVER['DOCUMENT_ROOT'] . 'vendor/autoload.php';
header("Content-Type: text/html;charset=utf-8");
?>
<!DOCTYPE html>
<html lang="es" >

<head>
  <meta charset="UTF-8">
  <title>Rastreador III</title>
  <meta charset="utf-8">

  <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="js/ValidarGeneral.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.slim.js"
		  integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc="
		  crossorigin="anonymous"></script>

  <script>
	$(document).ready(function(){
	  $("#spanMostrar").on("click", function(){
		var elementInput= $("#login-pass");
		var elementIcon= $("#iconMostrar");
		if(elementIcon.hasClass("active")){
			elementIcon.removeClass("active");
			elementIcon.html("visibility_off");
			elementInput.prop("type","password");
		} else{
			elementIcon.addClass("active");
			elementIcon.html("visibility");
			elementInput.prop("type","text");
		}
	  });
	});
  </script>
</head>
<body>
  <body>
	<div class="login">
		<div class="login-screen">
			<div class="app-title">
				<h1>Acceder</h1>
			</div>

			<div class="login-form">
				<form method = "post" action = "Controladores/CtrLogin.php" autocomplete="off">
				<div class="control-group">
					<input type="text" class="login-field" value="" placeholder="Nombre de Usuario" id="login-name" name = "UserName" autocomplete = "off">
					<label class="login-field-icon fui-user" for="login-name"></label>
				</div>

				<div class="control-group" style="position:relative">
					<input type="password" autocomplete="off" class="login-field" value="" placeholder="Contraseña" id="login-pass" name = "UserPass" >
					<label class="login-field-icon fui-lock" for="login-pass"></label>
					<span id="spanMostrar" class="form-clear d-none">
						<i id="iconMostrar" class="material-icons mdc-text-field__icon">
							visibility
						</i>
					</span>
				</div>
				<button class="btn btn-primary btn-large btn-block Hander" type = "submit">Entrar</button>				
				</form>
			</div>
		</div>
	</div>

</body>    
</body>
<?php
if(isset($_REQUEST["MensajeError"])){
	$MensajeError = $_REQUEST["MensajeError"]; 
	echo "<script type='text/javascript'>
		  swal('".$MensajeError."','','warning');
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
</html>
