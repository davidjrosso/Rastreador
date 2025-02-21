<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/Controladores/Elements.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Controladores/CtrGeneral.php");
header("Content-Type: text/html;charset=utf-8");

?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<div class = "row" style="margin-right: 0px;margin-left: 0px;">
  <div class = "col-md-2"></div>
  <div class = "col-md-8">
    <div class="row">
      <div class="col-12 Titulo">
        <p>Recuperación de Usuario y Contraseña</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <form method = "post" onKeydown="return event.key != 'Enter';" action = "Controladores/RecuperarPassword.php">
            <div class="form-group row">
              <label for="username" style="text-align:center;" class="col-md-2 col-form-label LblForm">Usuario</label>
              <div class="col-md-10">
                <input type="text" class="form-control" name="username" id="username" autofocus autocomplete="off" placeholder="nombre de usuario">
              </div>
            </div>
            <div class="form-group row" style="align-content: center">
            <div class="col"></div>
              <div class="col">
                <button type="submit" class="btn btn-outline-success">Enviar</button>
                <button type = "button" class = "btn btn-danger" onClick = "location.href = 'index.php'">Atras</button>
              </div>
              <div class="col">
              </div>
            </div>
          </form>
  </div>
</div>
</div>
<?php  
if(isset($_REQUEST['Mensaje'])){
  echo "<script type='text/javascript'>
  swal('".$_REQUEST['Mensaje']."','','success');
</script>";
}
if(isset($_REQUEST['MensajeError'])){
  echo "<script type='text/javascript'>
  swal('".$_REQUEST['MensajeError']."','','warning');
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
</body>
</html>