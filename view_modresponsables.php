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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Responsable.php");


header("Content-Type: text/html;charset=utf-8");

/*     CONTROL DE USUARIOS                    */
if(!isset($_SESSION["Usuario"])){
    header("Location: Error_Session.php");
}

$ID_Usuario = $_SESSION["Usuario"];
$account = new Account(account_id: $ID_Usuario);
$TipoUsuario = $account->get_id_tipo_usuario();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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

</head>
<body>
<div class = "row margin-right-cero">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_RESPONSABLE);
  ?>
  <div class = "col-md-9 inicio-md-2">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Movimientos</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Search -->
        <div class = "row">
          <?php  
            if (isset($_REQUEST["ID"]) && $_REQUEST["ID"] != null) {
              $id_responsable = $_REQUEST["ID"];

              $con = new Conexion();
              $con->OpenConexion();
              $responsable = new Responsable(
                                             coneccion_base: $con,
                                             id_responsable: $id_responsable
                                            );
              $id_responsable = $responsable->get_id_responsable();
              $Responsable = $responsable->get_responsable();
              $con->CloseConexion();

              ?>
            <div class = "col-10">
            <form method = "post" onKeydown="return event.key != 'Enter';" action = "Controladores/pedirmodificarresponsable.php">
                <!-- <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Id: </label>
                  <div class="col-md-10">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">< ?php echo $ID_Responsable; ?></label>
                  </div>
                </div> -->
                <input type="hidden" name="ID" value = "<?php echo $id_responsable;?>">
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Responsable: </label>
                  <div class="col-md-10">
                    <input type="text" class="form-control" name = "Responsable" id="inputPassword" autocomplete="off" value = "<?php echo $Responsable; ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="offset-md-2 col-md-10">
                    <button type="submit" class="btn btn-outline-success">Guardar</button>
                    <button type = "button" class = "btn btn-danger" onClick = "location.href = 'view_responsables.php'">Atras</button>
                  </div>
                </div>
            </form>
            <div class="row">
                <div class="col-10"></div>
                <div class="col-2">
                  
                </div>
            </div>
            </div>
              <?php  
            } else {
              $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID del Responsable";
              echo $Mensaje;
            }
          ?>
        </div>        
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
</body>
</html>