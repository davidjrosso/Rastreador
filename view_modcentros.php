<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Elements.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/CtrGeneral.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/CentroSalud.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Account.php';


header("Content-Type: text/html;charset=utf-8");

$ID_Usuario = $_SESSION["Usuario"];
$usuario = new Account(account_id: $ID_Usuario); 
$TipoUsuario = $usuario->get_id_tipo_usuario();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <base href="/">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="./dist/alerta.js"></script>
  <script>

    var cantArchivo = 0;
       $(document).ready(function(){
              var date_input=$('input[name="date"]');
              var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
              date_input.datepicker({
                  format: 'dd/mm/yyyy',
                  container: container,
                  todayHighlight: true,
                  autoclose: true,
              });
          });

          function validarNombreArchivo() {
          }

    function agregarArchivo(){
      cantArchivo++;
      var divContenedor = document.getElementById('contenedor-archivos');
      var divBarrio = document.createElement("div");
      divBarrio.setAttribute('class','form-group row');
      var labelBarrio = document.createElement("label");
      labelBarrio.setAttribute('class','col-md-2 col-form-label LblForm');
      labelBarrio.innerText = 'Barrio ' + cantArchivo + ':';
      var divSelectBarrio = document.createElement("div");
      divSelectBarrio.setAttribute('class','col-md-10');
      var select = `<?php $Element = new Elements(); echo $Element->CBRepBarrios(); ?>`;
      divSelectBarrio.innerHTML = select;      
      divBarrio.appendChild(labelBarrio);
      divBarrio.appendChild(divSelectBarrio);
      divContenedor.appendChild(divBarrio);
    }

    function agregarPlanilla(){
      cantBarrios++;
      var divContenedor = document.getElementById('contenedor-planillas');
      var divBarrio = document.createElement("div");
      divBarrio.setAttribute('class','form-group row');
      var labelBarrio = document.createElement("label");
      labelBarrio.setAttribute('class','col-md-2 col-form-label LblForm');
      labelBarrio.innerText = 'Barrio '+cantBarrios+':';
      var divSelectBarrio = document.createElement("div");
      divSelectBarrio.setAttribute('class','col-md-10');
      var select = `<?php $Element = new Elements(); echo $Element->CBRepBarrios(); ?>`;
      divSelectBarrio.innerHTML = select;      
      divBarrio.appendChild(labelBarrio);
      divBarrio.appendChild(divSelectBarrio);
      divContenedor.appendChild(divBarrio);
    }

  </script>

</head>
<body>
<div class = "row">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_CENTRO_SALUD);
  ?>
  <div class = "col-md-9">
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
            if(isset($_REQUEST["ID"]) && $_REQUEST["ID"]!=null){
              $id_centro = $_REQUEST["ID"];

              $con = new Conexion();
              $con->OpenConexion();
              $centro_salud = new CentroSalud(
                                              coneccion_base: $con, 
                                              id_centro: $id_centro
                                            );

              $id_centro = $centro_salud->get_id_centro();
              $centro_salud_nombre = $centro_salud->get_centro_salud();

              $consulta = "select * from archivos where centro_salud = $id_centro";
              $mensaje = "Error al consultar los registros de archivos";
              $result = mysqli_query($con->Conexion,$consulta) or die($mensaje);

            ?>
            <div class = "col-10">
            <form method="post" onKeydown="return event.key != 'Enter';" action = "mod_centro_salud">
                <!-- <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Id: </label>
                  <div class="col-md-10">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">< ?php echo $ID_Centro; ?></label>
                  </div>
                </div> -->
                <input type="hidden" name="ID" value = "<?php echo $id_centro; ?>">
                <div class="form-group row">
                  <label for="Centro_Salud" class="col-md-2 col-form-label LblForm">Centro Salud: </label>
                  <div class="col-md-10">
                    <input type="text" class="form-control" name = "Centro_Salud" id="Centro_Salud" 
                           autocomplete="off" value = "<?php echo $centro_salud_nombre; ?>"
                           onclick="validarNombreArchivo()" >
                  </div>
                </div>
                <?php 
                  $max_count = mysqli_num_rows($result);
                  $count = 0;
                  while($row = mysqli_fetch_array($result)) {
                    if ($count == 0) {
                ?>
                <div class="form-group row">
                  <label for="archivo" class="col-md-2 col-form-label LblForm">Archivo: </label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name = "archivo" id="archivo" autocomplete="off" value = "<?php echo $row["archivo"]; ?>">
                  </div>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-primary" onClick="agregarArchivo()" id="agregar-archivo-id">+</button>
                  </div>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-primary" onClick="agregarArchivo()" id="agregar-archivo-id">&#xF2A7;</button>
                  </div>
                </div>
                <?php
                    }
                ?>
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Planilla: </label>
                  <div class="col-md-7">
                    <input type="text" class="form-control" name = "planilla" id="planilla" autocomplete="off" value = "<?php echo $row["planilla"]; ?>">
                  </div>
                  <?php
                    if ($count + 1 == $max_count) {
                  ?>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-primary" onClick="agregarPlanilla()" id="agregar-planilla-id">+</button>
                  </div>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-primary" onClick="agregarPlanilla()" id="agregar-planilla-id">&#xF2A7;</button>
                  </div>
                  <?php
                    }
                  ?>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-primary" onClick="georeferenciaPersonasExcel(<?php echo $row["id_archivo"];?>, <?php echo $id_centro;?>)" id="georeferencia-personas-planilla">&#xF2A7;</button>
                  </div>
                </div>
                <div id="contenedor-planilla">
                </div>
                <?php
                  $count++;
                }
                ?>
                <div id="contenedor-archivos">
                </div>
                <div class="form-group row">
                  <div class="offset-md-2 col-md-10">
                    <button type="submit" class="btn btn-outline-success">Guardar</button>
                    <button type = "button" class = "btn btn-danger" onClick = "location.href = '/home'">Atras</button>
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
              $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID del Motivo";
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