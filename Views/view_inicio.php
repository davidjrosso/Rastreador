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
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="dist/control.js"></script>
  <script>
    let mensajeError = '<?php echo $mensaje_error;?>';
    let mensajeSuccess = '<?php echo $mensaje_success;?>';
  </script>
</head>
<body>
<div class = "row">
<?php
  if ($tipo_usuario == 1) {
  ?>
  <div class = "col-md-2">
    <div class="nav-side-menu">
      <?php
            echo $Element->CBSessionNombreUsuario($id_usuario);
      ?>
      <div class="brand">General</div>
        <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
        <div class="menu-list">
  
            <?php
            $Element->getMenuGeneral($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Actualizaciones</div>
        <div class="menu-list">
  
            <?php
            $Element->getMenuActualizaciones($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Reportes</div>
        <div class="menu-list">
  
            <?php
            $Element->getMenuReportes($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Unificación</div>
        <div class="menu-list">
  
            <?php
            $Element->getMenuUnificacion($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Seguridad</div>
        <div class="menu-list">
  
            <?php
            $Element->getMenuSeguridad($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Auditoria</div>
        <div class="menu-list">
            <?php
          $Element->getMenuNotificacion($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Documentación</div>
        <div class="menu-list">
            <?php
            $Element->getMenuHistorial($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand btn-Salir" onClick = "location.href = '/logout'">Salir</div>
    </div>
  </div>
  <?php 
    }
    if ($tipo_usuario == 2 || $tipo_usuario > 3) {
  ?>
  <div class = "col-md-2">
<div class="nav-side-menu">
      <?php
            echo $Element->CBSessionNombreUsuario($id_usuario);
      ?>
    <div class="brand">General</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
            <?php
            $Element->getMenuGeneral($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Actualizaciones</div>
        <div class="menu-list">
  
            <?php
            $Element->getMenuActualizaciones($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Reportes</div>
        <div class="menu-list">
            <?php
            $Element->getMenuReportes($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Auditoria</div>
        <div class="menu-list">
            <?php
          $Element->getMenuNotificacion($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Documentación</div>
        <div class="menu-list">
            <?php
            $Element->getMenuHistorial($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand btn-Salir" onClick = "location.href = '/logout'">Salir</div>
    </div>
  </div>
  <?php
  }  
  if ($tipo_usuario == 3) {    
  ?>
  <div class = "col-md-2">
<div class="nav-side-menu">
      <?php
            echo $Element->CBSessionNombreUsuario($id_usuario);
      ?>
    <div class="brand">General</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>

        <div class="menu-list">
  
            <?php
            $Element->getMenuGeneral($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Actualizaciones</div>
        <div class="menu-list">
  
            <?php
            $Element->getMenuActualizaciones($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Reportes</div>
        <div class="menu-list">
  
            <?php
            $Element->getMenuReportes($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Unificación</div>
        <div class="menu-list">
  
            <?php
            $Element->getMenuUnificacion($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Auditoria</div>
        <div class="menu-list">
            <?php
          $Element->getMenuNotificacion($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand">Documentación</div>
        <div class="menu-list">
            <?php
            $Element->getMenuHistorial($Element::PAGINA_INICIO);?>
        </div>
        <div class="brand btn-Salir" onClick = "location.href = '/logout'">Salir</div>
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
    $Notificaciones = $CtrGeneral->getNotificaciones();

    if ($Notificaciones["cant"] > 0) {
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
    <?php
    if ($tipo_usuario == 1) {
      $CantUnif = $CtrGeneral->getCantSolicitudes_Unificacion();
      $CantModMot = $CtrGeneral->getCantSolicitudes_Modificacion_Motivo();
      $CantCrearMot = $CtrGeneral->getCantSolicitudes_Crear_Motivo();
      $CantCrearCat = $CtrGeneral->getCantSolicitudes_Crear_Categoria();
      $CantModCat = $CtrGeneral->getCantSolicitudes_Modificacion_Categoria();
      $CantDel = $CtrGeneral->getCantSolicitudes_EliminacionMotivo();
      $CantDelCat = $CtrGeneral->getCantSolicitudes_EliminacionCategoria();
      $CantDelResp = $CtrGeneral->getCantSolicitudes_EliminacionResponsable();
      $CantSolUsr = $CtrGeneral->get_cant_solicitudes_usuario();
      $CantSolMod = $CtrGeneral->get_cant_solicitudes_modificacion();
      $CantNot = $Notificaciones["cant"];

      if ($CantModMot > 0 || $CantUnif > 0 
         || $CantModCat > 0 || $CantDel > 0 
         || $CantDelCat > 0 || $CantNot > 0 
         || $CantCrearCat > 0 || $CantCrearMot > 0
         || $CantSolUsr > 0 || $CantSolMod > 0
         || $CantDelResp > 0
      ) {
      ?>
      <div class = "row">
        <div class="col-1"></div>
        <div class="col-4 Contenedor-Imagen-Inicio">
          <img src="images/FondoInicio.jpg" class = "FondoInicio">
        </div>      
        <div class="col-6">
          <h3 class="bg-secondary text-light" style="text-align: center; padding: 10px;">Solicitudes por autorizar</h3>
          <?php 
            if ($CantUnif > 0) {
              ?>
                <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Unificaciones</h3>
              <?php
              echo $CtrGeneral->getSolicitudes_Unificacion();
            }
            
            if ($CantCrearMot > 0 ) {
              ?>
                <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Crear Motivos</h3>
              <?php
              echo $CtrGeneral->getSolicitudes_Crear_Motivo();
            }
            if ($CantModMot > 0) {
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Modificar Motivos</h3>
              <?php
              echo $CtrGeneral->getSolicitudes_Modificacion_Motivo();
            }
            if ($CantCrearCat > 0) {
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Crear Categorías</h3>
              <?php              
              echo $CtrGeneral->getSolicitudes_Crear_Categoria();
            }
            if ($CantModCat > 0) {
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Modificar Categorías</h3>
              <?php              
              echo $CtrGeneral->getSolicitudes_Modificacion_Categoria();
            }
            if ($CantSolMod > 0) {
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Modificar Responsables</h3>
              <?php              
              echo $CtrGeneral->getSolicitudes_Modificacion();
            }
            if ($CantDel > 0) {
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Eliminar Motivos</h3>
              <?php
              echo $CtrGeneral->getSolicitudes_EliminacionMotivo();
            }
            if ($CantDelCat > 0) {
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Eliminar Categorias</h3>
              <?php
              echo $CtrGeneral->getSolicitudes_EliminacionCategoria();
            }
            if ($CantSolUsr > 0) {
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Solcitud de Usuario</h3>
              <?php
              echo $CtrGeneral->get_solicitudes_usuario();
            }
            if ($CantDelResp > 0) {
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Eliminar Responsable</h3>
              <?php
              echo $CtrGeneral->getEliminacion_Responsable();
            }
            if ($CantNot > 0) {
              ?>
              <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Eliminar Notificaciones</h3>
              <?php
              echo $CtrGeneral->getSolicitudes_Notificaciones();
            }
          ?>
        </div>  
        <div class="col-1"></div> 
      </div>     
  <?php } else { ?>
      <div class = "row">
        <div class="col"></div>
        <div class="col-4 Contenedor-Imagen-Inicio">
          <img src="images/FondoInicio.jpg" class = "FondoInicio">
        </div>      
        <div class="col"></div>          
      </div>
  <?php }
      } else { ?>
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
</body>
</html>