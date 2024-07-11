<?php
session_start(); 
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
require_once "Controladores/Conexion.php";
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

       function VerificarUnificacion(xID_Registro_1,xID_Registro_2,xID_TipoUnif,xID_Solicitud){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer unificar estos motivos?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  switch(xID_TipoUnif){
                    case 'MOTIVO': window.location.href = 'Controladores/unificarmotivos.php?ID_Motivo_1='+xID_Registro_1+'&ID_Motivo_2='+xID_Registro_2+'&ID_Solicitud='+xID_Solicitud; break; 
                    case 'PERSONAS': window.location.href = 'Controladores/unificarpersonas.php?ID_Persona_1='+xID_Registro_1+'&ID_Persona_2='+xID_Registro_2+'&ID_Solicitud='+xID_Solicitud; break;
                    case 'CENTROS SALUD': window.location.href = 'Controladores/unificarcentros.php?ID_Centro_1='+xID_Registro_1+'&ID_Centro_2='+xID_Registro_2+'&ID_Solicitud='+xID_Solicitud; break;
                    case 'ESCUELAS': window.location.href = 'Controladores/unificarescuelas.php?ID_Escuela_1='+xID_Registro_1+'&ID_Escuela_2='+xID_Registro_2+'&ID_Solicitud='+xID_Solicitud; break;
                    case 'BARRIOS': window.location.href = 'Controladores/unificarbarrios.php?ID_Barrio_1='+xID_Registro_1+'&ID_Barrio_2='+xID_Registro_2+'&ID_Solicitud='+xID_Solicitud; break;
                    default: swal("Algo salio mal consulte con el equipo de desarrollo","","warning");break;                   
                  }                  
                  //alert('SI');
                } else {        
                }
              });
        }

        function VerificarCrearMotivo(xID,xFecha,xMotivo,xCodigo,xNum_Motivo,xCategoria){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer crear este motivo?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/InsertMotivo.php?ID='+xID+'&Fecha='+xFecha+'&Motivo='+xMotivo+'&Codigo='+xCodigo+'&Num_Motivo='+xNum_Motivo+'&Cod_Categoria='+xCategoria;
                }
              });
        }

       function VerificarModificarMotivo(xID,xFecha,xMotivo,xCodigo,xNum_Motivo,xID_Motivo){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer modificar este motivo?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/ModificarMotivo.php?ID='+xID+'&Fecha='+xFecha+'&Motivo='+xMotivo+'&Codigo='+xCodigo+'&Num_Motivo='+xNum_Motivo+'&ID_Motivo='+xID_Motivo;                
                  //alert('SI');
                } else {        
                }
              });
        }

        function VerificarCrearCategoria(xID,xFecha,xCodigo,xCategoria,xID_Forma,xColor){
              var ColorBase = btoa(xColor);
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer crear esta categoría?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/InsertCategoria.php?ID='+xID+'&Fecha='+xFecha+'&Codigo='+xCodigo+'&Categoria='+xCategoria+'&ID_Forma='+xID_Forma+'&ID_Categoria='+xID+'&Color='+ColorBase;
                } else {
                }
              });
        }

        function VerificarModificarCategoria(xID,xFecha,xCodigo,xCategoria,xID_Forma,xNuevoColor,xID_Categoria){
              var NuevoColorBase = btoa(xNuevoColor);

              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer modificar esta categoría?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/ModificarCategoria.php?ID='+xID+'&Fecha='+xFecha+'&Codigo='+xCodigo+'&Categoria='+xCategoria+'&ID_Forma='+xID_Forma+'&ID_Categoria='+xID_Categoria+'&CodigoColor='+NuevoColorBase;
                  //alert('SI');
                } else {        
                }
              });
        }

       function VerificarEliminarMotivo(xID_Motivo){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer eliminar este motivo?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/DeleteMotivo.php?ID='+xID_Motivo;
                } else {        
                }
              });
        }

        function VerificarEliminarCategoria(xID_Categoria){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer eliminar este categoria?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/DeleteCategoria.php?ID='+xID_Categoria;
                  //alert('SI');
                } else {
                  console.log("por aca");
                  console.log(willDelete);        
                }
              });
        }

        function VerificarEliminarNotificacion(xID_Notificacion){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer eliminar esta notificación?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/DeleteNotificacion.php?ID='+xID_Notificacion;
                } else {
                }
              });
        }

        function CancelarUnificacion(xID_Peticion){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer borrar esta petición de unificación?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/DeletePeticion.php?ID='+xID_Peticion;
                  //alert('SI');
                } else {        
                }
              });
        }

        function CancelarModificacionMotivo(xID){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer borrar esta petición de modificación?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/DeletePeticionModificacionMotivo.php?ID='+xID;
                  //alert('SI');
                } else {        
                }
              });
        }
        function CancelarCrearMotivo(xID){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer borrar esta petición de creación?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/DeletePeticionCrearMotivo.php?ID='+xID;
                }
              });
        }

        function CancelarCrearCategoria(xID){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer borrar esta petición de creación?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/DeletePeticionCrearCategoria.php?ID='+xID;
                }
              });
        }

        function CancelarModificacionCategoria(xID){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer borrar esta petición de modificación?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/DeletePeticionModificacionCategoria.php?ID='+xID;
                  //alert('SI');
                } else {        
                }
              });
        }

        function CancelarEliminacionMotivo(xID){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer borrar esta petición de eliminación?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/DeletePeticionEliminacion.php?ID='+xID;
                  //alert('SI');
                } else {        
                }
              });
        }

        function CancelarEliminacionCategoria(xID){
              swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer borrar esta petición de eliminación?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/DeletePeticionEliminacionCategoria.php?ID='+xID;
                  //alert('SI');
                } else {        
                }
              });
        }

  </script>

</head>
<body>
<div class = "row">
  <?php  
  if($TipoUsuario == 1){  
  ?>
  <div class = "col-md-2">
    <div class="nav-side-menu">
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
            $Element->getMenuReportes(0);?>
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
        <div class="brand">Documentación</div>
        <div class="menu-list">
            <?php $Element = new Elements();
            $Element->getMenuHistorial(0);?>
        </div>
        <div class="brand btn-Salir" onClick = "location.href = 'Controladores/CtrLogout.php'">Salir</div>
    </div>
  </div>
  <?php 
    }
    if($TipoUsuario == 2){
  ?>
  <div class = "col-md-2">
    <div class="nav-side-menu">
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
            $Element->getMenuReportes(0);?>
        </div>
        <div class="brand">Documentación</div>
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
  <div class = "col-md-2">
    <div class="nav-side-menu">
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
            $Element->getMenuReportes(0);?>
        </div>
        <div class="brand">Unificación</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuUnificacion(0);?>
        </div>
        <div class="brand">Documentación</div>
        <div class="menu-list">
            <?php $Element = new Elements();
            $Element->getMenuHistorial(0);?>
        </div>
        <div class="brand btn-Salir" onClick = "location.href = 'Controladores/CtrLogout.php'">Salir</div>
    </div>
  </div>
<?php } ?>
  <div class = "col-md-10">
    <div class="row">
      <div class="col"></div>
      <div class="col-8">
        <div class="row">
          <div class="col-1">
            <img src="images/escudo.png" width="100%" height="auto">
          </div>
          <div class="col-11">
    	       <p class = "CopyRight">Desarrollado en cooperación con la Dirección de Cómputos de la Municipalidad de Río Tercero</p>
          </div>
        </div>
      </div>
      <div class="col"></div>
    </div>
    <br>
    <div class="row">
      <div class="col"></div>
      <div class="col-11 Titulo">
        <br>
        <p style="font-family: times; font-weight: bold;">RASTREADOR <br><i>GRÁFICO DE CO-EVOLUCIÓN PARA LA EVALUACIÓN COMUNITARIA DE COBERTURA</i><br> Sistema Orientado a la Georeferenciación</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
    <?php 
    $CtrGeneral = new CtrGeneral();

    // NOTIFICACIONES
    $Notificaciones = $CtrGeneral->getNotificaciones();

    if($Notificaciones["cant"] > 0){
      ?>
      <div class="alert alert-warning alert-dismissible fade show" role="alert" style="position: absolute; top: 5px; right: 5px;">
        <h5 class="alert-heading">¡Notificación!</h5>
        <p><i class="fa fa-info-circle"></i> <?= $Notificaciones["value"]["Detalle"];  ?></p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php        
    }
    ?>
    <?php if($TipoUsuario == 1){ 
      // $CtrGeneral = new CtrGeneral();
      // SOLICITUDES
      $CantUnif = $CtrGeneral->getCantSolicitudes_Unificacion();
      $CantModMot = $CtrGeneral->getCantSolicitudes_Modificacion_Motivo();
      $CantCrearMot = $CtrGeneral->getCantSolicitudes_Crear_Motivo();
      $CantCrearCat = $CtrGeneral->getCantSolicitudes_Crear_Categoria();
      $CantModCat = $CtrGeneral->getCantSolicitudes_Modificacion_Categoria();
      $CantDel = $CtrGeneral->getCantSolicitudes_EliminacionMotivo();
      $CantDelCat = $CtrGeneral->getCantSolicitudes_EliminacionCategoria();
      $CantNot = $Notificaciones["cant"];

      if($CantModMot > 0 || $CantUnif > 0 || $CantModCat > 0 || $CantDel > 0 || $CantDelCat > 0 || $CantNot > 0 || $CantCrearCat > 0 || $CantCrearMot > 0){
      ?>
      <div class = "row">
        <div class="col-1"></div>
        <div class="col-4 Contenedor-Imagen-Inicio">
          <img src="images/FondoInicio.jpg" class = "FondoInicio">
        </div>      
        <div class="col-6">
          <h3 class="bg-secondary text-light" style="text-align: center; padding: 10px;">Solicitudes por autorizar</h3>
          <?php 
            // $CtrGeneral = new CtrGeneral();
            if($CantUnif > 0){
              ?>
                <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Unificar Motivos</h3>
              <?php
              echo $CtrGeneral->getSolicitudes_Unificacion();
            }
            
            if($CantCrearMot > 0){
              ?>
                <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Crear Motivos</h3>
              <?php
              echo $CtrGeneral->getSolicitudes_Crear_Motivo();
            }
            if($CantModMot > 0){
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Modificar Motivos</h3>
              <?php
              echo $CtrGeneral->getSolicitudes_Modificacion_Motivo();
            }
            if($CantCrearCat > 0){
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Crear Categorías</h3>
              <?php              
              echo $CtrGeneral->getSolicitudes_Crear_Categoria();
            }
            if($CantModCat > 0){
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Modificar Categorías</h3>
              <?php              
              echo $CtrGeneral->getSolicitudes_Modificacion_Categoria();
            }
            if($CantDel > 0){
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Eliminar Motivos</h3>
              <?php
              echo $CtrGeneral->getSolicitudes_EliminacionMotivo();
            }
            if($CantDelCat > 0){
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Eliminar Categorias</h3>
              <?php
              echo $CtrGeneral->getSolicitudes_EliminacionCategoria();
            }
            if($CantNot > 0){
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Eliminar Notificaciones</h3>
              <?php
              echo $CtrGeneral->getSolicitudes_Notificaciones();
            }            
          ?>
        </div>  
        <div class="col-1"></div> 
      </div>     
  <?php }else{ ?>
      <div class = "row">
        <div class="col"></div>
        <div class="col-4 Contenedor-Imagen-Inicio">
          <img src="images/FondoInicio.jpg" class = "FondoInicio">
        </div>      
        <div class="col"></div>          
      </div>
  <?php }
      } else{ ?>
    <div class = "row">
      <div class="col"></div>
      <div class="col-4 Contenedor-Imagen-Inicio">
        <img src="images/FondoInicio.jpg" class = "FondoInicio">
      </div>      
      <div class="col"></div>  
    </div>
    <?php } ?>
    <br>
    <div class="row">
      <div class="col"></div>
      <div class="col-10">        
      </div>
      <div class="col"></div>
    </div>
    <br>	
  </div>
</div>
<?php  
if(isset($_REQUEST['Mensaje'])){
  echo "<script type='text/javascript'>
    swal('".$_REQUEST['Mensaje']."','','success');
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