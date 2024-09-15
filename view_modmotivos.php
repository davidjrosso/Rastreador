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
$Con->CloseConexion();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <!--<script type="text/javascript" src = "js/Funciones.js"></script> -->
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
<div class = "row">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Modificación de Motivos</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Search -->
        <div class = "row">
          <?php  
            if(isset($_REQUEST["ID"]) && $_REQUEST["ID"]!=null){
              $ID_Motivo = $_REQUEST["ID"];

              $Con = new Conexion();
              $Con->OpenConexion();

              $ConsultarDatos = "select * from motivo where id_motivo = $ID_Motivo and estado = 1";
              $MensajeErrorDatos = "No se pudo consultar los Datos del Motivo";

              $EjecutarConsultarDatos = mysqli_query($Con->Conexion,$ConsultarDatos) or die($MensajeErrorDatos);

              $Ret = mysqli_fetch_assoc($EjecutarConsultarDatos);

              $ID_Motivo = $Ret["id_motivo"];
              $Motivo = $Ret["motivo"];
              $Codigo = $Ret["codigo"];
              $Cod_Categoria = $Ret["cod_categoria"];
              $Num_Motivo = $Ret["num_motivo"];
              $Estado = $Ret["estado"];

              $ConsultarIDCategoria = "select id_categoria from categoria where cod_categoria = '$Cod_Categoria' and estado = 1 limit 1";
              $MensajeErrorIDCategoria = "No se pudo consultar el ID de la Categoria";

              $EjecutarConsultarIDCategoria = mysqli_query($Con->Conexion,$ConsultarIDCategoria) or die($MensajeErrorIDCategoria);

              $RetID_Categoria = mysqli_fetch_assoc($EjecutarConsultarIDCategoria);
              $ID_Categoria = $RetID_Categoria["id_categoria"];

              $Con->CloseConexion();

              ?>
            <div class = "col-10">
            <form method = "post" onKeydown="return event.key != 'Enter';" action = "Controladores/pedirmodificarmotivo.php">
                <!-- <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Id: </label>
                  <div class="col-md-10">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">< ?php echo $ID_Motivo; ?></label>
                  </div>
                </div> -->
                <input type="hidden" name="ID" value = "<?php echo $ID_Motivo; ?>">
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo: </label>
                  <div class="col-md-10">
                    <input type="text" class="form-control" name = "Motivo" id="inputPassword" autocomplete="off" value = "<?php echo $Motivo; ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Codigo: </label>
                  <div class="col-md-10">
                    <input type="text" class="form-control" name = "Codigo" id="inputPassword" autocomplete="off" value = "<?php echo $Codigo; ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Categoria: </label>
                  <div class="col-md-10">
                    <?php  
                    $Element = new Elements();
                    echo $Element->CBModCategoria($ID_Categoria);                    
                    ?>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="offset-md-2 col-md-10">
                    <button type="submit" class="btn btn-outline-success">Guardar</button>
                    <button type = "button" class = "btn btn-danger" onClick = "location.href = 'view_motivos.php'">Atras</button>
                  </div>
                </div>
            </form>
            </div>
              <?php  
            }else{
              $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID del Motivo";
              echo $Mensaje;
            }
          ?>
        </div>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2">
              
            
            </div>
        </div>
  </div>
</div>
</div>
<?php  
if(isset($Mensaje)){
  echo "<script type='text/javascript'>
  swal('".$Mensaje."','','success');
</script>";
}
if(isset($MensajeError)){
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