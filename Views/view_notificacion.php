<?php 
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

       function Verificar(xID){
          swal({
            title: "¿Está seguro?",
            text: "¿Seguro de querer eliminar este movimiento?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              window.location.href = 'Controladores/DeleteMovimiento.php?ID='+xID;
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
  echo $Element->menuDeNavegacion(
                                  TipoUsuario: $tipo_usuario, 
                                  ID_Usuario: $id_usuario, 
                                  pagina: $Element::PAGINA_AUDITORIA_NOTIFICACION
    );
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Notificaciones</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <form method = "get" action = "/lista_notificacion_control">
            <div class="form-group row" style="justify-content: center">
              <label for="valor_filtro" class="col-md-1 col-form-label LblForm">Buscar: </label>
              <div class="col-md-3">
                <select name = "ID_Filtro" class = "form-control">                                        
                    <option value="activos" selected>Activos</option>
                    <option value="expirados">Expirados</option>
                </select>
              </div>
              <div class = "col-md-1">
                  <button class = "btn btn-secondary">Ir</button>
              </div>
              <div class="col-md-3">
              </div>
              <div class="col-md-3">
                <button type="button" class="btn btn-outline-secondary" onclick="location.href = '/home'">Volver</button>
              </div>
            </div>
          </form>
          <br><br>
        <div class = "row">
          <?php 
            if(!empty($_REQUEST["ID_Filtro"])){
              echo $dt_general->get_lista_notificaciones( $id_filtro );
            } else {
              echo $dt_general->get_lista_notificaciones();
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