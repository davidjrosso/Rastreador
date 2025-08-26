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

header("Content-Type: text/html;charset=utf-8");
?>
<!DOCTYPE html>
<html lang="es" >
<head>
  <meta charset="UTF-8">
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
</head>
<body>
<div class="row">
	<div class="col"></div>
	<div class="col-8">
		<br>
		<div class="row">
			<div class="col-3">
				<center><img src="images/ErrorSession.png"></center>
			</div>
			<div class="col-9">
				<br>
				<p style="font-family: times; font-weight: bold; font-size: 20px;">La Sesión ha Caducado o No ha ingresado al Sistema. Vuelva a Ingresar <a href="/login">aquí</a></p>
			</div>
		</div>	
	</div>
	<div class="col"></div>		
</div>
</body>
</html>
