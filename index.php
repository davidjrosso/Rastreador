<?php  
header("Content-Type: text/html;charset=utf-8");
?>
<!DOCTYPE html>
<html lang="es" >

<head>
  <meta charset="UTF-8">
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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

				<div class="control-group">
				<input type="password" class="login-field" value="" placeholder="Contraseña" id="login-pass" name = "UserPass" autocomplete="off">
				<label class="login-field-icon fui-lock" for="login-pass"></label>
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
