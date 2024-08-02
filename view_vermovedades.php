<?php 
session_start(); 
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
require_once "Modelo/Persona.php";
require_once "Modelo/DtoMovimiento.php";
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
</head>
<body>
<div class = "row">
   <?php  
  if($TipoUsuario == 1){  
  ?>
  <div class = "col-md-3">
<div class="nav-side-menu">
      <?php $Element = new Elements();
            echo $Element->CBSessionNombre($ID_Usuario);
      ?>
    <div class="brand">General</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuGeneral(0);?>
        </div>
        <div class="brand">Actualizaciones</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuActualizaciones(0);?>
        </div>
        <div class="brand">Reportes</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuReportes(4);?>
        </div>
        <div class="brand">Unificación</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuUnificacion(0);?>
        </div>
        <div class="brand">Seguridad</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuSeguridad(0);?>
        </div>
        <div class="brand">El Proyecto</div>
        <div class="menu-list">
            <?php $Element = new Elements();
            $Element->getMenuHistorial(0);?>
        </div>
        <div class="brand btn-Salir" onClick = "location.href = 'Controladores/CtrLogout.php'">Salir</div>
    </div>
  </div>
  <?php 
    }
    if($TipoUsuario == 2 || $TipoUsuario > 3){
  ?>
  <div class = "col-md-3">
<div class="nav-side-menu">
      <?php $Element = new Elements();
            echo $Element->CBSessionNombre($ID_Usuario);
      ?>
    <div class="brand">General</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuGeneral(0);?>
        </div>
        <div class="brand">Actualizaciones</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuActualizaciones(0);?>
        </div>
        <div class="brand">El Proyecto</div>
        <div class="menu-list">
            <?php $Element = new Elements();
            $Element->getMenuHistorial(0);?>
        </div>
        <div class="brand btn-Salir" onClick = "location.href = 'Controladores/CtrLogout.php'">Salir</div>
    </div>
  </div>
  <?php
  }  
  if($TipoUsuario == 3){    
  ?>
  <div class = "col-md-3">
<div class="nav-side-menu">
      <?php $Element = new Elements();
            echo $Element->CBSessionNombre($ID_Usuario);
      ?>
    <div class="brand">General</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuGeneral(0);?>
        </div>
        <div class="brand">Actualizaciones</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuActualizaciones(0);?>
        </div>
        <div class="brand">Reportes</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuReportes(4);?>
        </div>
        <div class="brand">Unificación</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuUnificacion(0);?>
        </div>
        <div class="brand">El Proyecto</div>
        <div class="menu-list">
            <?php $Element = new Elements();
            $Element->getMenuHistorial(0);?>
        </div>
        <div class="brand btn-Salir" onClick = "location.href = 'Controladores/CtrLogout.php'">Salir</div>
    </div>
  </div>
<?php } ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Movimientos por Edades</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Search -->
        <div class = "row">
          <?php  
            if(isset($_REQUEST["Edad_Desde"]) && $_REQUEST["Edad_Desde"]!=null && isset($_REQUEST["Edad_Hasta"]) && $_REQUEST["Edad_Hasta"] != null){

              $Edad_Desde = $_REQUEST["Edad_Desde"];
              $Edad_Hasta = $_REQUEST["Edad_Hasta"];
              $Manzana = $_REQUEST["Manzana"];
              $Lote = $_REQUEST["Lote"];
              $Familia = $_REQUEST["Familia"];
              $Barrio = $_REQUEST["ID_Barrio"];
              $ID_Motivo = $_REQUEST["ID_Motivo"];
              $ID_Categoria = $_REQUEST["ID_Categoria"]; 
              $ID_Responsable = $_REQUEST["ID_Responsable"];

              $Con = new Conexion();
              $Con->OpenConexion();

              //////////////////////////////////////////////////////TABLAS MOVIMIENTOS DE LA PERSONA /////////////////////////////

              $ConsultarMovimientos = "select M.id_movimiento, M.fecha, P.apellido, P.nombre, P.edad, P.meses, M.motivo_1, M.motivo_2, M.motivo_3, M.observaciones, R.responsable from movimiento M, responsable R, persona P, motivo MO, categoria C where M.id_resp = R.id_resp and M.id_persona = P.id_persona and M.estado = 1 and P.edad between $Edad_Desde and $Edad_Hasta";

              if($Manzana != null && $Manzana != ""){
                $ConsultarMovimientos .= " and P.manzana = '$Manzana'";
              }

              if($Lote != null && $Lote != ""){
                $ConsultarMovimientos .= " and P.lote = $Lote";
              }

              if($Familia != null && $Familia != ""){
                $ConsultarMovimientos .= " and P.familia = $Familia";
              }

              if($Barrio > 0){
                $ConsultarMovimientos .= " and P.ID_Barrio = $Barrio";
              }

              if($ID_Motivo > 0){
                $ConsultarMovimientos .= " and M.motivo_1 = $ID_Motivo";
              }

              if($ID_Categoria > 0){
                $ConsultarMovimientos .= " and M.motivo_1 = MO.num_motivo and MO.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria";
              }

              if($ID_Responsable > 0){
                $ConsultarMovimientos .= " and M.id_resp = $ID_Responsable";
              }

              $ConsultarMovimientos .= " group by M.id_movimiento order by P.edad";



              $MensajeErrorMovimientos = "No se pudo consultar los movimientos de la persona";

              $TomarMovimientosEntreEdades = mysqli_query($Con->Conexion,$ConsultarMovimientos) or die($MensajeErrorMovimientos);

              $Rows = mysqli_num_rows($TomarMovimientosEntreEdades);

              if($Rows == 0){
                echo "<div class = 'col'></div>";
                echo "<div class = 'col-6'>";
                echo "<p class = 'TextoSinResultados'>No se encontraron Resultados</p><center><button class = 'btn btn-danger' onClick = 'location.href= \"view_movedades.php\"'>Atras</button></center>";
                echo "</div>";
                echo "<div class = 'col'></div>";
              }

              while ($RetMovimientos = mysqli_fetch_assoc($TomarMovimientosEntreEdades)) {
                $ID_Movimiento = $RetMovimientos["id_movimiento"];
                $Fecha = implode("-", array_reverse(explode("-",$RetMovimientos["fecha"])));
                $Apellido = $RetMovimientos["apellido"];
                $Nombre = $RetMovimientos["nombre"];
                $Edad = $RetMovimientos["edad"];
                $Meses = $RetMovimientos["meses"];
                $ID_Motivo_1 = $RetMovimientos["motivo_1"];
                $ConsultarMotivo_1 = "select motivo from motivo where id_motivo = $ID_Motivo_1";
                $MensajeErrorMotivo_1 = "No se pudo consultar el motivo 1";
                $RetMotivo_1 = mysqli_query($Con->Conexion,$ConsultarMotivo_1) or die($MensajeErrorMotivo_1);
                $RetMotivo_1 = mysqli_fetch_assoc($RetMotivo_1);
                $Motivo_1 = $RetMotivo_1["motivo"];

                $ID_Motivo_2 = $RetMovimientos["motivo_2"];
                $ConsultarMotivo_2 = "select motivo from motivo where id_motivo = $ID_Motivo_2";
                $RetMotivo_2 = mysqli_query($Con->Conexion,$ConsultarMotivo_2) or die($MensajeErrorMotivo_2);
                $RetMotivo_2 = mysqli_fetch_assoc($RetMotivo_2);
                $Motivo_2 = $RetMotivo_2["motivo"];

                $ID_Motivo_3 = $RetMovimientos["motivo_3"];
                $ConsultarMotivo_3 = "select motivo from motivo where id_motivo = $ID_Motivo_3";
                $RetMotivo_3 = mysqli_query($Con->Conexion,$ConsultarMotivo_3) or die($MensajeErrorMotivo_3);
                $RetMotivo_3 = mysqli_fetch_assoc($RetMotivo_3);
                $Motivo_3 = $RetMotivo_3["motivo"];

                $Observaciones = $RetMovimientos["observaciones"];
                $Responsable = $RetMovimientos["responsable"];

                $DtoMovimiento = new DtoMovimiento($ID_Movimiento,$Fecha,$Apellido,$Nombre,$Motivo_1,$Motivo_2,$Motivo_3,$Observaciones,$Responsable);
                $TableMov = "<table class='table table-dark'>";             
                if($Edad == 0 && !is_null($Meses)){
                  $TableMov .= "<tr><td style = 'width: 30%;'>Edad</td><td style = 'width: 70%;'>".$Meses." meses</td></tr>";
                }elseif($Edad == 2020){
                  $TableMov .= "<tr><td style = 'width: 30%;'>Edad</td><td style = 'width: 70%;'>Sin Datos</td></tr>";
                }else{
                  $TableMov .= "<tr><td style = 'width: 30%;'>Edad</td><td style = 'width: 70%;'>".$Edad."</td></tr>";
                }                
                $TableMov .= "<tr><td style = 'width: 30%;'>Persona</td><td style = 'width: 70%;'>".$DtoMovimiento->getApellido().", ".$DtoMovimiento->getNombre()."</td></tr>";
                $TableMov .= "<tr><td style = 'width: 30%;'>Fecha</td><td style = 'width: 70%;'>".$DtoMovimiento->getFecha()."</td></tr>";
                $TableMov .= "<tr><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>".$DtoMovimiento->getMotivo_1()."</td></tr>";
                $TableMov .= "<tr><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>".$DtoMovimiento->getMotivo_2()."</td></tr>";
                $TableMov .= "<tr><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>".$DtoMovimiento->getMotivo_3()."</td></tr>";
                $TableMov .= "<tr><td style = 'width: 30%;'>Observaciones</td><td style = 'width: 70%;'>".$DtoMovimiento->getObservaciones()."</td></tr>";
                $TableMov .= "<tr><td style = 'width: 30%;'>Responsable</td><td style = 'width: 70%;'>".$DtoMovimiento->getResponsable()."</td></tr>";
                $TableMov .= "</table>";
                echo $TableMov;

              }

              $Con->CloseConexion();
              

            }else{
              $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID de la Persona";
              echo $Mensaje;
            }
          ?>
        </div>
  </div>
</div>
</div>
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