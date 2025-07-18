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
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Elements.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/CtrGeneral.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Movimiento.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Responsable.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Account.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/MovimientoMotivo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/DtoMovimiento.php';
header("Content-Type: text/html;charset=utf-8");

/*     CONTROL DE USUARIOS                    */
if(!isset($_SESSION["Usuario"])){
    header("Location: Error_Session.php");
}

$ID_Usuario = $_SESSION["Usuario"];
$usuario = new Account(account_id: $ID_Usuario);
$TipoUsuario = $usuario->get_id_tipo_usuario();
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

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script> <!-- ESTO ES NECESARIO PARA QUE ANDE EN ESPAÑOL -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="js/ValidarMovimiento.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
        var cantMotivos = 3;
       $(document).ready(function(){
              var date_input=$('input[name="Fecha"]'); //our date input has the name "date"
              var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
              date_input.datepicker({
                  format: 'dd/mm/yyyy',
                  container: container,
                  todayHighlight: true,
                  autoclose: true,
                  closeText: 'Cerrar', /* HASTA ACA */
                  days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                  daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                  daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                  months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                  monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                  today: "Hoy",
                  monthsTitle: "Meses",
                  clear: "Borrar",
                  weekStart: 1,
              });
          });

          function agregarMotivo(){
            if (cantMotivos <= 4) {
              var BanderaMotivoDisponible = false;
              var divBotonMotivo = null;

              while (cantMotivos <= 4 && BanderaMotivoDisponible == false) {
                cantMotivos++;
                divBotonMotivo = document.getElementById("Motivo_" + cantMotivos);
                BanderaMotivoDisponible = (divBotonMotivo === null)? true:false;
              }

              if (BanderaMotivoDisponible == true){
                var divContenedor = document.getElementById('contenedorMotivos');
                var divMotivo = document.createElement("div");
                divMotivo.setAttribute('class','form-group row');
                var labelMotivo = document.createElement("label");
                labelMotivo.setAttribute('class','col-md-2 col-form-label LblForm');
                labelMotivo.innerText = 'Motivo '+ cantMotivos +':';
                divBotonMotivo = document.createElement("div");
                divBotonMotivo.setAttribute("id", "Motivo_" + cantMotivos);
                divBotonMotivo.setAttribute('class','col-md-10');
                var boton = "<button type = 'button' class = 'btn btn-lg btn-primary btn-block' data-toggle='modal' data-target='#ModalMotivo_" + cantMotivos + "'>Seleccione un Motivo</button>";
                divBotonMotivo.innerHTML = boton;      
                divMotivo.appendChild(labelMotivo);
                divMotivo.appendChild(divBotonMotivo);
                divContenedor.appendChild(divMotivo);
                var divInputsGenerales = document.getElementById('InputsGenerales');
                var divInput = document.createElement("input");
                divInput.setAttribute("id", "ID_Motivo_" + cantMotivos);
                divInput.setAttribute("name", "ID_Motivo_" + cantMotivos);
                divInput.setAttribute("type", "hidden");
                divInputsGenerales.appendChild(divInput);
              }
            }
          }


       function buscarPersonas(){
        var xNombre = document.getElementById('SearchPersonas').value;
        var textoBusqueda = xNombre;
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosPersonas").innerHTML=contenidosRecibidos;
            }
        }
        xmlhttp.open('POST', 'buscarPersonas.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
        xmlhttp.send();
      }

      function buscarMotivos_1(){
        var xMotivo = document.getElementById('SearchMotivos_1').value;
        var textoBusqueda = xMotivo;
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosMotivos_1").innerHTML=contenidosRecibidos;
            }
        }
        xmlhttp.open('POST', 'buscarMotivos_1.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
        xmlhttp.send();
      }

      function buscarMotivos_2(){
        var xMotivo = document.getElementById('SearchMotivos_2').value;
        var textoBusqueda = xMotivo;
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosMotivos_2").innerHTML=contenidosRecibidos;
            }
        }
        xmlhttp.open('POST', 'buscarMotivos_2.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
        xmlhttp.send();
      }

      function buscarMotivos_3(){
        var xMotivo = document.getElementById('SearchMotivos_3').value;
        var textoBusqueda = xMotivo;
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosMotivos_3").innerHTML=contenidosRecibidos;
            }
        }
        xmlhttp.open('POST', 'buscarMotivos_3.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
        xmlhttp.send();
      }

      function buscarMotivosGeneral(id_Motivo){
        var xMotivo = document.getElementById("SearchMotivos" + id_Motivo).value;
        var textoBusqueda = xMotivo;
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            contenidosRecibidos = xmlhttp.responseText;
            document.getElementById("ResultadosMotivos" + id_Motivo).innerHTML=contenidosRecibidos;
          }
        }
        xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda='+textoBusqueda + '&number=' + id_Motivo, true); // Método post y url invocada
        xmlhttp.send();
      }

      function seleccionPersona(xNombre,xID){
        var Persona = document.getElementById("Persona");
        var ID_Persona = document.getElementById("ID_Persona");
        Persona.innerHTML = "";
        Persona.innerHTML = "<p>"+xNombre+"</p>";
        ID_Persona.setAttribute('value',xID);
      }

      function seleccionMotivo_1(xMotivo,xID){
        var Motivo = document.getElementById("Motivo_1");
        var ID_Motivo = document.getElementById("ID_Motivo_1");
        Motivo.innerHTML = "";
        Motivo.innerHTML = "<p>"+xMotivo+"</p>";
        ID_Motivo.setAttribute('value',xID);
      }

      function seleccionMotivo_2(xMotivo,xID){
        var Motivo = document.getElementById("Motivo_2");
        var ID_Motivo = document.getElementById("ID_Motivo_2");
        Motivo.innerHTML = "";
        Motivo.innerHTML = "<p>"+xMotivo+"</p>";
        ID_Motivo.setAttribute('value',xID);
      }

      function seleccionMotivo_3(xMotivo,xID){
        var Motivo = document.getElementById("Motivo_3");
        var ID_Motivo = document.getElementById("ID_Motivo_3");
        Motivo.innerHTML = "";
        Motivo.innerHTML = "<p>"+xMotivo+"</p>";
        ID_Motivo.setAttribute('value',xID);
      }

      function seleccionMotivo(xMotivo,xID,xNumber){
        if(xNumber > 1){
          var Motivo = document.getElementById("Motivo_"+xNumber);
          var ID_Motivo = document.getElementById("ID_Motivo_"+xNumber);
          Motivo.innerHTML = "";
          Motivo.innerHTML = "<p>"+xMotivo+"</p>";
          ID_Motivo.setAttribute('value',xID);
        } else{
          var Motivo = document.getElementById("Motivo");
          var ID_Motivo = document.getElementById("ID_Motivo");
          Motivo.innerHTML = "";
          Motivo.innerHTML = "<p>"+xMotivo+"</p>";
          ID_Motivo.setAttribute('value',xID);
        }
      }
  </script>

</head>
<body>
<div class = "row">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_MOVIMIENTO);
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
              $ID_Movimiento = $_REQUEST["ID"];

              $Con = new Conexion();
              $Con->OpenConexion();

              $ConsultarDatos = "select M.id_movimiento, M.fecha, M.id_centro, P.id_persona, P.apellido, 
                                        P.nombre, M.observaciones, R.id_resp, M.id_resp_2, M.id_resp_3, M.id_resp_4,
                                        R.responsable, C.centro_salud, I.ID_OtraInstitucion, I.Nombre, MT.id_motivo,
                                        MT.motivo
                                 from movimiento M 
                                      INNER JOIN movimiento_motivo MEMT ON (M.id_movimiento = MEMT.id_movimiento)
                                      INNER JOIN motivo MT ON (MEMT.id_motivo = MT.id_motivo)
                                      INNER JOIN persona P ON (M.id_persona = P.id_persona)
                                      INNER JOIN responsable R ON (M.id_resp = R.id_resp) 
                                      LEFT JOIN centros_salud C ON (M.id_centro = C.id_centro)
                                      LEFT JOIN otras_instituciones I ON (M.id_otrainstitucion = I.ID_OtraInstitucion )
                                 where M.id_movimiento = $ID_Movimiento";

              $MensajeErrorDatos = "No se pudo consultar los Datos del Movimiento";

              $EjecutarConsultarDatos = mysqli_query($Con->Conexion,$ConsultarDatos) or die($MensajeErrorDatos);

              $Ret = mysqli_fetch_assoc($EjecutarConsultarDatos);

              $ID_Movimiento = $Ret["id_movimiento"];
              $id_motivo = $Ret["id_motivo"];
              $Fecha = implode("/", array_reverse(explode("-",$Ret["fecha"])));
              $Apellido = $Ret["apellido"];
              $Nombre = $Ret["nombre"];
              $Observaciones = $Ret["observaciones"];
              $Responsable = $Ret["responsable"];
              $ID_Persona = $Ret["id_persona"];
              $ID_Responsable = $Ret["id_resp"];
              $ID_Responsable_2 = $Ret["id_resp_2"];
              $ID_Responsable_3 = $Ret["id_resp_3"];
              $ID_Responsable_4 = $Ret["id_resp_4"];
              $ID_Centro = $Ret["id_centro"];
              $Centro_Salud = (!empty($Ret["centro_salud"])) ? $Ret["centro_salud"] : null;
              $ID_OtraInstitucion = $Ret["ID_OtraInstitucion"];
              $OtraInstitucion = (!empty($Ret["Nombre"])) ? $Ret["Nombre"] : null;

              $DtoMovimiento = new DtoMovimiento(
                                                xID_Movimiento: $ID_Movimiento,
                                                xFecha: $Fecha,
                                                xApellido: $Apellido,
                                                xNombre: $Nombre,
                                                xMotivo_1: $id_motivo,
                                                xObservaciones: $Observaciones,
                                                xResponsable: $Responsable,
                                                xCentroSalud: $Centro_Salud,
                                                xOtraInstitucion: $OtraInstitucion
              );

              $count_motivo = 2;
              while ($Ret = mysqli_fetch_assoc($EjecutarConsultarDatos)) {
                if ($count_motivo == 2) $DtoMovimiento->setMotivo_2($Ret["id_motivo"]);
                if ($count_motivo == 3) $DtoMovimiento->setMotivo_3($Ret["id_motivo"]);
                if ($count_motivo == 4) $DtoMovimiento->setMotivo_4($Ret["id_motivo"]);
                if ($count_motivo == 5) $DtoMovimiento->setMotivo_5($Ret["id_motivo"]);
                $count_motivo++;
              }

              $Con->CloseConexion();
              ?>
            <div class = "col-10">
            <form method = "post" onKeydown="return event.key != 'Enter';" action = "Controladores/ModificarMovimiento.php" onSubmit = "return ValidarMovimiento();">
                <!-- <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Id: </label>
                  <div class="col-md-10">
                    <label for="inputPassword" class="col-md-2 col-form-label LblForm">< ?php echo $DtoMovimiento->getID_Movimiento(); ?></label>
                    <input type="hidden" name="ID" value = "< ?php echo $DtoMovimiento->getID_Movimiento(); ?>">
                  </div>
                </div> -->
                <input type="hidden" name="ID" value = "<?php echo $DtoMovimiento->getID_Movimiento(); ?>">
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Fecha: </label>
                  <div class="col-md-10">
                    <input type="text" class="form-control" name = "Fecha" id="datepicker" placeholder="01/01/2001" width="100%" autocomplete="off" value = "<?php echo $DtoMovimiento->getFecha(); ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Persona: </label>
                  <div class="col-md-10" id = "Persona">
                    <?php  
                    $Element = new Elements();
                    echo $Element->BTNModPersonas($ID_Persona);
                    ?>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 1: </label>
                  <div class="col-md-9" id = "Motivo_1">
                    <?php  
                      $Element = new Elements();
                      echo $Element->BTNModMotivo_1($DtoMovimiento->getMotivo_1());
                    ?>
                  </div>
                  <div class="col-md-1">
                  <button type="button" class="btn btn-primary" onClick="agregarMotivo()" id="agregarMotivoID">+</button>
              </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 2: </label>
                  <div class="col-md-10" id = "Motivo_2">
                    <?php  
                    $Element = new Elements();
                    echo $Element->BTNModMotivo_2($DtoMovimiento->getMotivo_2());
                    ?>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 3: </label>
                  <div class="col-md-10" id = "Motivo_3">
                    <?php  
                    $Element = new Elements();
                    echo $Element->BTNModMotivo_3($DtoMovimiento->getMotivo_3());
                    ?>
                  </div>
                </div>
                <?php  
                  if($DtoMovimiento->getMotivo_4() != "" && $DtoMovimiento->getMotivo_4() != 1){
                ?>
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 4: </label>
                  <div class="col-md-10" id = "Motivo_4">
                <?php
                  $Element = new Elements();
                  echo $Element->BTNModMotivo_4($DtoMovimiento->getMotivo_4());
                ?>
                  </div>
                </div>
                <?php
                }
                ?>
                <?php
                  if($DtoMovimiento->getMotivo_5() != "" && $DtoMovimiento->getMotivo_5() != 1){
                ?>
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 5: </label>
                  <div class="col-md-10" id = "Motivo_5">
                <?php
                  $Element = new Elements();
                  echo $Element->BTNModMotivo_5($DtoMovimiento->getMotivo_5());
                ?>
                  </div>
                </div>
                <?php
                }
                ?>
                <div id="contenedorMotivos">              
                </div>
                <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Observaciones: </label>
                  <div class="col-md-10">
                    <textarea class = "form-control" row = "3" name = "Observaciones" value = ""><?php echo $DtoMovimiento->getObservaciones(); ?></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Responsable: </label>
                  <div class = "col-md-10">
                    <?php  
                    $Element = new Elements();
                    echo $Element->CBModResponsables($ID_Responsable);
                    ?>
                  </div>
                </div>
                <?php if($ID_Responsable_2 != null){ ?>
                  <div class="form-group row">
                    <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Responsable 2: </label>
                    <div class = "col-md-10">
                      <?php  
                      $Element = new Elements();
                      echo $Element->CBModResponsables($ID_Responsable_2);
                      ?>
                    </div>
                  </div>
                <?php  
                }
                ?>
                <?php if($ID_Responsable_3 != null){ ?>
                  <div class="form-group row">
                    <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Responsable 3: </label>
                    <div class = "col-md-10">
                      <?php  
                      $Element = new Elements();
                      echo $Element->CBModResponsables($ID_Responsable_3);
                      ?>
                    </div>
                  </div>
                <?php  
                }
                ?>
                <?php if($ID_Responsable_4 != null){ ?>
                  <div class="form-group row">
                    <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Responsable 4: </label>
                    <div class = "col-md-10">
                      <?php  
                      $Element = new Elements();
                      echo $Element->CBModResponsables($ID_Responsable_4);
                      ?>
                    </div>
                  </div>
                <?php
                }
                ?>
                <div class="form-group row">
                  <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Centro de Salud: </label>
                  <div class = "col-md-10">
                    <?php  
                    $Element = new Elements();
                    echo $Element->CBModCentros($ID_Centro);
                    ?>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Institución: </label>
                  <div class = "col-md-10">
                    <?php  
                    $Element = new Elements();
                    echo $Element->CBModOtrasInstituciones($ID_OtraInstitucion);
                    ?>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="offset-md-2 col-md-10" id = "InputsGenerales">
                    <input type="hidden" name="ID_Persona" id = "ID_Persona" value = "<?php echo $ID_Persona; ?>">
                    <input type="hidden" name="ID_Motivo_1" id = "ID_Motivo_1" value = "<?php echo $DtoMovimiento->getMotivo_1();?>">
                    <input type="hidden" name="ID_Motivo_2" id = "ID_Motivo_2" value = "<?php echo $DtoMovimiento->getMotivo_2();?>">
                    <input type="hidden" name="ID_Motivo_3" id = "ID_Motivo_3" value = "<?php echo $DtoMovimiento->getMotivo_3();?>">
                    <?php
                      if($DtoMovimiento->getMotivo_4() != "" && $DtoMovimiento->getMotivo_4() != 1){
                    ?>
                    <input type="hidden" name="ID_Motivo_4" id = "ID_Motivo_4" value = "<?php echo $DtoMovimiento->getMotivo_4();?>">
                    <?php
                      }
                    ?>
                    <?php
                      if($DtoMovimiento->getMotivo_5() != "" && $DtoMovimiento->getMotivo_5() != 1){
                    ?>
                    <input type="hidden" name="ID_Motivo_5" id = "ID_Motivo_5" value = "<?php echo $DtoMovimiento->getMotivo_5();?>">
                    <?php
                      }
                    ?>
                    <button type="submit" class="btn btn-outline-success">Guardar</button>
                    <button type = "button" class = "btn btn-danger" onClick = "location.href = 'view_movimientos.php'">Atras</button>
                  </div>
                </div>
            </form>
            </div>
              <?php  
            }else{
              $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID del Movimiento";
              echo $Mensaje;
            }
          ?>
        </div>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2">
              
              <!-- <button type = "button" class = "btn btn-danger" onClick = "location.href = 'view_movimientos.php'">Atras</button> -->
            </div>
        </div>
         <!-- SECCION DE MODALES -->
      <!-- Modal SELECCION PERSONAS -->
      <div class="modal fade bd-example-modal-lg" id="ModalPersona" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Persona</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-8">
                    <div class="input-group mb-3">
                      <input class = "form-control" type="text" name="BuscarPersona" id = "SearchPersonas" onKeyUp="buscarPersonas()" placeholder="Ingrese el nombre, apellido o dni de la persona">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosPersonas">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION PERSONAS -->
      <!-- Modal SELECCION MOTIVO -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Motivo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-8">
                    <div class="input-group mb-3">
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos_1" onKeyUp="buscarMotivos_1()">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos_1">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION MOTIVO -->
      <!-- Modal SELECCION MOTIVO -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Motivo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-8">
                    <div class="input-group mb-3">
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos_2" onKeyUp="buscarMotivos_2()">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos_2">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION MOTIVO -->
      <!-- Modal SELECCION MOTIVO -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo_3" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Motivo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-8">
                    <div class="input-group mb-3">
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos_3" onKeyUp="buscarMotivos_3()">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos_3">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION MOTIVO -->
      <!-- Modal SELECCION MOTIVO 4 -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Motivo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-8">
                    <div class="input-group mb-3">
                      <input class = "form-control" type="text" name="BuscarMotivos4" id = "SearchMotivos4" onKeyUp="buscarMotivosGeneral(4)" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos4">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION MOTIVO -->
      <!-- Modal SELECCION MOTIVO 5 -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo_5" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Motivo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-8">
                    <div class="input-group mb-3">
                      <input class = "form-control" type="text" name="BuscarMotivos5" id = "SearchMotivos5" onKeyUp="buscarMotivosGeneral(5)" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos5">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION MOTIVO -->
      <!-- FIN SECCION DE MODALES -->
  </div>
</div>
</div>
</body>
</html>