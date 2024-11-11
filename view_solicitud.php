<?php
session_start();
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
require_once "Modelo/Account.php";
header("Content-Type: text/html;charset=utf-8");

/*     CONTROL DE USUARIOS                    */
if (!isset($_SESSION["Usuario"])) {
  header("Location: Error_Session.php");
}

$id_usuario = $_SESSION["Usuario"];
$usuario = new Account(account_id: $id_usuario);
$tipo_usuario = $usuario->get_id_tipo_usuario();

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
  <!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <!--<script type="text/javascript" src = "js/Funciones.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
    $(document).ready(function () {
      var date_input = $('input[name="date"]'); //our date input has the name "date"
      var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
      date_input.datepicker({
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
      });
    });

    function VerificarUnificacion(xID_Registro_1, xID_Registro_2, xID_TipoUnif, xID_Solicitud) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer unificar estos motivos?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            switch (xID_TipoUnif) {
              case 'MOTIVO': window.location.href = 'Controladores/unificarmotivos.php?ID_Motivo_1=' + xID_Registro_1 + '&ID_Motivo_2=' + xID_Registro_2 + '&ID_Solicitud=' + xID_Solicitud; break;
              case 'PERSONAS': window.location.href = 'Controladores/unificarpersonas.php?ID_Persona_1=' + xID_Registro_1 + '&ID_Persona_2=' + xID_Registro_2 + '&ID_Solicitud=' + xID_Solicitud; break;
              case 'CENTROS SALUD': window.location.href = 'Controladores/unificarcentros.php?ID_Centro_1=' + xID_Registro_1 + '&ID_Centro_2=' + xID_Registro_2 + '&ID_Solicitud=' + xID_Solicitud; break;
              case 'ESCUELAS': window.location.href = 'Controladores/unificarescuelas.php?ID_Escuela_1=' + xID_Registro_1 + '&ID_Escuela_2=' + xID_Registro_2 + '&ID_Solicitud=' + xID_Solicitud; break;
              case 'BARRIOS': window.location.href = 'Controladores/unificarbarrios.php?ID_Barrio_1=' + xID_Registro_1 + '&ID_Barrio_2=' + xID_Registro_2 + '&ID_Solicitud=' + xID_Solicitud; break;
              default: swal("Algo salio mal consulte con el equipo de desarrollo", "", "warning"); break;
            }
            //alert('SI');
          } else {
          }
        });
    }

    function VerificarCrearMotivo(xID, xFecha, xMotivo, xCodigo, xNum_Motivo, xCategoria) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer crear este motivo?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = 'Controladores/InsertMotivo.php?ID=' + xID + '&Fecha=' + xFecha + '&Motivo=' + xMotivo + '&Codigo=' + xCodigo + '&Num_Motivo=' + xNum_Motivo + '&Cod_Categoria=' + xCategoria;
          }
        });
    }

    function VerificarModificarMotivo(xID, xFecha, xMotivo, xCodigo, xNum_Motivo, xID_Motivo) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer modificar este motivo?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = 'Controladores/ModificarMotivo.php?ID=' + xID + '&Fecha=' + xFecha + '&Motivo=' + xMotivo + '&Codigo=' + xCodigo + '&Num_Motivo=' + xNum_Motivo + '&ID_Motivo=' + xID_Motivo;
            //alert('SI');
          } else {
          }
        });
    }

    function VerificarCrearCategoria(xID, xFecha, xCodigo, xCategoria, xID_Forma, xColor) {
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
            window.location.href = 'Controladores/InsertCategoria.php?ID=' + xID + '&Fecha=' + xFecha + '&Codigo=' + xCodigo + '&Categoria=' + xCategoria + '&ID_Forma=' + xID_Forma + '&ID_Categoria=' + xID + '&Color=' + ColorBase;
          } else {
          }
        });
    }

    function VerificarModificarCategoria(xID, xFecha, xCodigo, xCategoria, xID_Forma, xNuevoColor, xID_Categoria) {
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
            window.location.href = 'Controladores/ModificarCategoria.php?ID=' + xID + '&Fecha=' + xFecha + '&Codigo=' + xCodigo + '&Categoria=' + xCategoria + '&ID_Forma=' + xID_Forma + '&ID_Categoria=' + xID_Categoria + '&CodigoColor=' + NuevoColorBase;
            //alert('SI');
          } else {
          }
        });
    }

    function VerificarEliminarMotivo(xID_Motivo) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer eliminar este motivo?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = 'Controladores/DeleteMotivo.php?ID=' + xID_Motivo;
          } else {
          }
        });
    }

    function VerificarEliminarCategoria(xID_Categoria) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer eliminar este categoria?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = 'Controladores/DeleteCategoria.php?ID=' + xID_Categoria;
            //alert('SI');
          } else {
            console.log("por aca");
            console.log(willDelete);
          }
        });
    }

    function VerificarEliminarNotificacion(xID_Notificacion) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer eliminar esta notificación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = 'Controladores/DeleteNotificacion.php?ID=' + xID_Notificacion;
          } else {
          }
        });
    }

    function CancelarUnificacion(xID_Peticion) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de unificación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = 'Controladores/DeletePeticion.php?ID=' + xID_Peticion;
            //alert('SI');
          } else {
          }
        });
    }

    function CancelarModificacionMotivo(xID) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de modificación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionModificacionMotivo.php?ID=' + xID;
            //alert('SI');
          } else {
          }
        });
    }
    function CancelarCrearMotivo(xID) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de creación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionCrearMotivo.php?ID=' + xID;
          }
        });
    }

    function CancelarCrearCategoria(xID) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de creación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionCrearCategoria.php?ID=' + xID;
          }
        });
    }

    function CancelarModificacionCategoria(xID) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de modificación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionModificacionCategoria.php?ID=' + xID;
            //alert('SI');
          } else {
          }
        });
    }

    function CancelarEliminacionMotivo(xID) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de eliminación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionEliminacion.php?ID=' + xID;
            //alert('SI');
          } else {
          }
        });
    }

    function CancelarEliminacionCategoria(xID) {
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer borrar esta petición de eliminación?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = 'Controladores/DeletePeticionEliminacionCategoria.php?ID=' + xID;
            //alert('SI');
          } else {
          }
        });
    }

  </script>
  <style>
    #solicitudes-categoria {
      display: table;
    }

    #solicitudes-motivo {
      display: table;
    }

    #solicitudes-unificacion {
      display: table !important;
    }
  </style>
</head>

<body>
  <div class="row">
    <?php
    $Element = new Elements();
    echo $Element->menuDeNavegacion(
      TipoUsuario: $tipo_usuario,
      ID_Usuario: $id_usuario,
      pagina: $Element::PAGINA_MOVIMIENTO
    );
    $dt_general = new CtrGeneral();
    ?>
    <div class="col-md-9">
      <div class="row">
        <div class="col"></div>
        <div class="col-10 Titulo">
          <p>Solicitudes</p>
        </div>
        <div class="col"></div>
      </div><br>
      <div class="row">
        <div class="col-4"></div>
        <div class="col-4">
        </div>
        <div class="col-2">
          <button type="button" class="btn btn-outline-secondary"
            onclick="location.href = 'view_inicio.php'">Volver</button>
        </div>
        <div class="col-1"></div>
      </div>
      <br>
      <div class="row">
        <div class="col-11">
          <!-- Carga -->
          <form method="post" action="Controladores/CtrBuscarAuditoria.php">
            <div class="form-group row">
              <label for="valor_filtro" class="col-md-2 col-form-label LblForm">Buscar: </label>
              <div class="col-md-4">
                <input type="text" class="form-control" name="Search" id="valor_filtro" width="100%" autocomplete="off">
              </div>
              <label for="inputPassword" class="col-md-1 col-form-label LblForm">En: </label>
              <div class="col-md-3">
                <select name="ID_Filtro" class="form-control">
                  <option value="categoria" selected>Categoria</option>
                  <option value="motivo">Motivo</option>
                  <option value="usuario">Permisos</option>
                  <option value="tipo_accion">Unificacion</option>
                </select>
              </div>
              <div class="col-md-1">
                <button class="btn btn-secondary">Ir</button>
              </div>
            </div>
          </form>
          <br><br>
          <!-- Fin Carga -->
          <!-- Search -->
          <div>
            <?php

            $CantUnif = $dt_general->getCantSolicitudes_Unificacion();
            $CantModMot = $dt_general->getCantSolicitudes_Modificacion_Motivo();
            $CantCrearMot = $dt_general->getCantSolicitudes_Crear_Motivo();
            $CantCrearCat = $dt_general->getCantSolicitudes_Crear_Categoria();
            $CantModCat = $dt_general->getCantSolicitudes_Modificacion_Categoria();
            $CantDel = $dt_general->getCantSolicitudes_EliminacionMotivo();
            $CantDelCat = $dt_general->getCantSolicitudes_EliminacionCategoria();

            if ($CantModMot > 0 || $CantUnif > 0 || $CantModCat > 0 || $CantDel > 0 || $CantDelCat > 0 || $CantCrearCat > 0 || $CantCrearMot > 0) {
            ?>
            <h3 class="bg-secondary text-light" style="text-align: center; padding: 10px;">Solicitudes por autorizar
            </h3>
            <?php
              // $CtrGeneral = new CtrGeneral();
              if ($CantUnif > 0) {
            ?>
                <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Unificar Motivos</h3>
            <?php
                echo $dt_general->getSolicitudes_Unificacion();
              }
              if ($CantCrearMot > 0 || $CantModMot > 0 || $CantDel > 0) {
            ?>
            <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Motivos</h3>
            <?php
                echo $dt_general->get_solicitudes_motivo();
              }
              if ($CantCrearCat > 0 || $CantModCat > 0 || $CantDelCat > 0) {
            ?>
            <h4 class="bg-info text-light" style="text-align: center; padding: 10px;">Categorías</h3>
            <?php
                echo $dt_general->get_solicitudes_categoria();
              }
            }
          ?>
          </div>
        </div>
      </div>
    </div>
    <?php
    if (isset($_REQUEST['Mensaje'])) {
      echo "<script type='text/javascript'>
              swal('" . $_REQUEST['Mensaje'] . "','','success');
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