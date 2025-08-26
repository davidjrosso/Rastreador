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
session_start(); 
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");

header("Content-Type: text/html;charset=utf-8");

/*     CONTROL DE USUARIOS                    */
if (!isset($_SESSION["Usuario"])) {
    header("Location: Error_Session.php");
}

$Con = new Conexion();
$Con->OpenConexion();
$ID_Usuario = $_SESSION["Usuario"];
$usuario = new Account(account_id: $ID_Usuario);
$TipoUsuario = $usuario->get_id_tipo_usuario();
$Con->CloseConexion();

$mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
$mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

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
  <script src="js/ValidarResponsable.js"></script>
  <script src="./dist/control.js"></script>

  <!--
  <script>
       $(document).ready(function(){
              var date_input=$('input[name="date"]'); //our date input has the name "date"
              var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
              date_input.datepicker({
                  format: 'dd/mm/yyyy',
                  container: container,
                  todayHighlight: true,
                  autoclose: true,
              });
          });

       function CalcularPrecio(){
        //var Combus = document.getElementById("Combustible").value;
        var Litros = document.getElementById("Litros").value;
        var Combustible = document.getElementById("Combustible");
        var PrecioxL = Combustible.options[Combustible.selectedIndex].getAttribute("name");
        
        var Total = parseFloat(PrecioxL) * parseFloat(Litros);

        var Precio = document.getElementById("Precio");
        Precio.setAttribute("value",parseFloat(Total).toFixed(2));
        //Terminar esta parte cuando termine lo demas.
       }

  </script>
  -->
  <script type="text/javascript">

			$(document).ready(function() {
				let mensajeError = '<?php echo $mensaje_error;?>';
				let mensajeSuccess = '<?php echo $mensaje_success;?>';

			  controlMensaje($mensaje_success, $mensaje_error);
			});
      var getImport = document.quearySelector('link [rel = import]');
      var getContent = getImport.import.querySelector('body');

      var ContenidoPagina = document.getElementById("ContenidoPagina");

      ContenidoPagina.appendChild(document.importNode(getContent, true));
  </script>

</head>
<body>
<div class = "row">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_OTRAS_INSTITUCIONES);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Nueva Institución</p>
      </div>
      <div class="col"></div>
    </div><br>
    <div class="row">
      <div class="col"></div>
      <div class="col-10">
          <div class="row">
              <center><button class = "btn btn-secondary btn-sm" onClick="location.href ='view_newmovimientos.php'">Agregar Nuevo Movimiento</button></center>
          </div>
      </div>
      <div class="col"></div>
    </div>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Carga -->
          <p class = "Titulos">Cargar Nueva Institución</p>
          <form method = "post" onKeydown="return event.key != 'Enter';" action = "Controladores/InsertOtraInstitucion.php">
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Nombre *: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Nombre" id="Nombre" autocomplete="off" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Telefono: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Telefono" id="Telefono" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">E-Mail: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Mail" id="Mail" autocomplete="off">
              </div>
            </div>            
            <div class="form-group row">
              <div class="offset-md-2 col-md-10">
                <button type="submit" class="btn btn-outline-success">Guardar</button>
                <button type = "button" class = "btn btn-danger" onClick = "location.href = 'otrasinstituciones'">Atras</button>
              </div>
            </div>
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