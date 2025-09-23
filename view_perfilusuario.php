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
$AccountID = $_REQUEST["account_id"];
$ConsultarUsuario = "select * from accounts where accountid = $AccountID";
$MensajeErrorConsultarModificacion = "No se pudo consultar el Tipo de Usuario";
$EjecutarConsultarUsuario = mysqli_query($Con->Conexion,$ConsultarUsuario) or die($MensajeErrorConsultarModificacion);
$Registros = mysqli_fetch_assoc($EjecutarConsultarUsuario);
$lastname = ucfirst($Registros["lastname"]);
$firstname = ucwords($Registros["firstname"]);
$initials = strtoupper($Registros["initials"]);
$username = $Registros["username"];
$userpass = $Registros["password"];
$email = $Registros["email"];
$ID_Tipo = $Registros["ID_TipoUsuario"];



$Con->CloseConexion();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
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
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 
  <script type="text/javascript">
      var getImport = document.quearySelector ('link [rel = import]'); 
      var getContent = getImport.import.querySelector('body');

      var ContenidoPagina = document.getElementById("ContenidoPagina");

      ContenidoPagina.appendChild(document.importNode(getContent, true));


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
<div class = "row margin-right-cero">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_USUARIO);
  ?>
  <div class = "col-md-9 inicio-md-2">
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
          <form method = "post" onKeydown="return event.key != 'Enter';" action = "Controladores/ModificarUsuario.php" onSubmit = "return ValidarModificacionUsuario();">
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
                <button type = "button" class = "btn btn-danger" onClick = "location.href = 'view_inicio.php'">Atras</button>
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
<?php  
if(isset($_REQUEST['Mensaje'])){
  $Mensaje = $_REQUEST['Mensaje'];
  echo "<script type='text/javascript'>
  swal('".$Mensaje."','','success');
</script>";
}
if(isset($_REQUEST['MensajeError'])){
  $MensajeError = $_REQUEST['MensajeError'];
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
</body>
</html>