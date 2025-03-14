<?php
session_start();
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
header("Content-Type: text/html;charset=utf-8");

/*     CONTROL DE USUARIOS                    */
if (!isset($_SESSION["Usuario"])) {
  header("Location: Error_Session.php");
}

$Con = new Conexion();
$Con->OpenConexion();
$ID_Usuario = $_SESSION["Usuario"];
$ConsultarTipoUsuario = "select ID_TipoUsuario from accounts where accountid = $ID_Usuario";
$MensajeErrorConsultarTipoUsuario = "No se pudo consultar el Tipo de Usuario";
$EjecutarConsultarTipoUsuario = mysqli_query($Con->Conexion, $ConsultarTipoUsuario) or die($MensajeErrorConsultarTipoUsuario);
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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
  <script src="./dist/alerta.js"></script>
  <script src="./dist/control.js"></script>
  <script>
    $(document).ready(function () {
      var date_input = $('input[name="date"]');
      var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
      date_input.datepicker({
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
      });
    });
  </script>
</head>

<body>
  <div class="row">
    <?php
    $Element = new Elements();
    echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_MOVIMIENTO);
    ?>
    <div class="col-md-9">
      <div class="row">
        <div class="col"></div>
        <div class="col-10 Titulo">
          <p>Movimientos</p>
        </div>
        <div class="col"></div>
      </div><br>
      <div class="row">
        <div class="col-2">
        <button id="btn-enlace-driver" class="btn btn-md btn-secondary" data-toggle="modal" data-target="#modal-enlace-drive">Enlace</button>
        </div>
        <div class="col-4">
          <center><button class="btn btn-secondary" onClick="location.href='view_newmovimientos.php'">Agregar Nuevo
              Movimiento</button></center>
        </div>
        <div class="col-2">
          <button type="button" class="btn btn-outline-secondary"
            onclick="location.href = 'view_inicio.php'">Volver</button>
        </div>
        <div class="col"></div>
      </div>
      <br>
      <div class="row">
        <div class="col-10">
          <form method="post" action="Controladores/CtrBuscarMovimientos.php">
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Buscar: </label>
              <div class="col-md-4">
                <input type="text" class="form-control" name="Search" id="inputPassword" width="100%"
                  autocomplete="off">
              </div>
              <label for="inputPassword" class="col-md-1 col-form-label LblForm">En: </label>
              <div class="col-md-3">
                <select name="ID_Filtro" class="form-control">
                  <option value="Apellido">Apellido</option>
                  <option value="Nombre">Nombre</option>
                  <option value="Documento">Documento</option>
                  <option value="Responsable">Resp.</option>
                  <option value="Fecha">Fecha</option>
                  <!-- <option value = "ID">Id</option> -->
                  <option value="Legajo">Nro. Legajo</option>
                  <option value="Carpeta">Nro. Carpeta</option>

                </select>
              </div>
              <div class="col-md-1">
                <button class="btn btn-secondary">Ir</button>
              </div>
            </div>
          </form>
          <br><br>
          <div class="row">
            <?php
            if (isset($_REQUEST["Filtro"]) && $_REQUEST["Filtro"] != null) {
              $Filtro = $_REQUEST["Filtro"];
              $ID_Filtro = $_REQUEST["ID_Filtro"];
              $DTGeneral = new CtrGeneral();

              switch ($ID_Filtro) {
                // case 'ID': echo $DTGeneral->getMovimientosxID($Filtro);break;
                case 'Fecha':
                  echo $DTGeneral->getMovimientosxFecha($Filtro, $TipoUsuario);
                  break;
                case 'Apellido':
                  echo $DTGeneral->getMovimientosxApellido($Filtro, $TipoUsuario);
                  break;
                case 'Documento':
                  echo $DTGeneral->getMovimientosxDocumento($Filtro, $TipoUsuario);
                  break;
                case 'Nombre':
                  echo $DTGeneral->getMovimientosxNombre($Filtro, $TipoUsuario);
                  break;
                case 'Responsable':
                  echo $DTGeneral->getMovimientosxResponsable($Filtro, $TipoUsuario);
                  break;
                case 'Legajo':
                  echo $DTGeneral->getMovimientosxLegajo($Filtro, $TipoUsuario);
                  break;
                case 'Carpeta':
                  echo $DTGeneral->getMovimientosxCarpeta($Filtro, $TipoUsuario);
                  break;
                default:
                  echo $DTGeneral->getMovimientosxID($Filtro, $TipoUsuario);
                  break;
              }
            } else {
              $DTGeneral = new CtrGeneral();
              echo $DTGeneral->getMovimientos($TipoUsuario);
            }
            ?>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal ENLACE DRIVER-->
    <div class="modal fade bd-example-modal-lg" id="modal-enlace-drive" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header" style="justify-content: center;">
            <h1>Enlaces Drive</h1>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-4">
                <?php 
                  echo $Element->CBCSDrives();
                ?>
              </div>
              <div class="col-8">
                <?php 
                  echo $Element->CBDrive();
                ?>
              </div>
            </div>            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
          </div>
        </div>
      </div>
    </div>
  <!-- FIN MODAL SELECCION ENLACE DRIVER -->
    <?php
    if (isset($_REQUEST['Mensaje'])) {
      echo "<script type='text/javascript'>
          mensajeDeProcesamiento('" . $_REQUEST['Mensaje'] . "');
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