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
$_SESSION["reporte_listado"] = true;
$_SESSION["reporte_grafico"] = false;
$ID_Config = $_REQUEST["ID_Config"];
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <!--<script type="text/javascript" src = "js/Funciones.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="js/acciones-reporte-grafico.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/pdf-lib/dist/pdf-lib.js"></script>
<script>
        const { PDFDocument, StandardFonts, rgb } = PDFLib
        var objectJsonTabla = {};
        let jsonTabla = {};
        var rowsRequest = {};
        let listaDeRequest = new Array();
        let listaDePdf = new Array();
        let nroPaginaPdf = 0;
        let nroPaginaGeneradas = 0;
        let documentoPdf = PDFDocument.create()
        let idRequestField = 0;
        let fechaDesde = null;
        let fechaHasta = null;
        let filtroSeleccionados = null;
       $(document).ready(function(){
              var date_input=$('input[name="date"]'); //our date input has the name "date"
              var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
              date_input.datepicker({
                  format: 'dd/mm/yyyy',
                  container: container,
                  todayHighlight: true,
                  autoclose: true,
              });
              nroFilasTabla = $("#tabla-movimiento-general tbody > tr").length - 2;
              nroFilasTabla = (nroFilasTabla) ? $("#tabla-movimiento tbody > tr").length - 2 : 0;
              nroColumnasTabla = $("thead > tr > th").length - 2;
              let nroPag = (nroFilasTabla + 2) / 16;
              let floorPag = Math.floor((nroFilasTabla + 2) / 16);
              //nroPaginaPdf = (nroPag > floorPag) ? (floorPag + 1) : floorPag;
              nroPaginaPdf = 0;
              thTable = $("thead > tr > th");
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


       function base64ToArrayBuffer(data) {
         var binaryString = window.atob(data);
         var binaryLen = binaryString.length;
         var bytes = new Uint8Array(binaryLen);
         for (var i = 0; i < binaryLen; i++) {
             var ascii = binaryString.charCodeAt(i);
             bytes[i] = ascii;
         }
         return bytes;
        };

        function enviarImprimir() {     

          alert("hacer imprimir!");
      
        }

        /*function enviarImprimirPdf() {
          var tabla1 = document.getElementById("tabla_1");
          var dataString = "tabla=" + tabla1.value;
          console.log(dataString);
          $.ajax({
            type: "POST",
            dataType: "html",
            contentType: "application/x-www-form-urlencoded",
            cache: false,
            url: "Controladores/GeneradorPdf.php",
            data: dataString,
            async: false,
            cache: false,
            success: function (res) {
              var arrBuffer = base64ToArrayBuffer(res);
              var blob = new Blob([arrBuffer], { type: "application/pdf" });
              //location.href = res;
              var link=document.createElement('a');
              var url1 = window.URL.createObjectURL(blob);
              window.open(url1);
              //link.href=window.URL.createObjectURL(blob);
              //link.download="<FILENAME_TO_SAVE_WITH_EXTENSION>.pdf";
              //link.click();
            },
            error: function (e) {
              var errorJsonString = JSON.stringify(e);
              console.log(errorJsonString);
              var error = JSON.parse(errorJsonString);
              console.log(error);
              alert("error " + atob(error.responseText));
            }
          });
        }*/

      function enviarImprimirPdf() {
        configColumnasTabla();
        if (objectJsonTabla["movimientos_general"] !== undefined) {
          let filas = objectJsonTabla["movimientos_general"].forEach((element, index, array) => {envioDeFilasMultiplesEnBloques(element, index, array);});
        } else if (objectJsonTabla["movimientos_persona"] !== undefined) {
          let filas = objectJsonTabla["movimientos_persona"].forEach((element, index, array) => {envioDeFilasMultiplesEnBloques(element, index, array);});
        }
        if (rowsRequest != {} && idRequestField >= 1) {
          rowsRequest["header_movimientos_general"] = objectJsonTabla["header_movimientos_general"];
          rowsRequest["head_movimientos_persona"] = objectJsonTabla["head_movimientos_persona"]
          let request = new XMLHttpRequest();
          listaDeRequest[idRequestField] = request;
          request.open("POST", "Controladores/GeneradorPdf.php", true);
          request.setRequestHeader("x-request-id", idRequestField);
          request.onreadystatechange = addPdf;
          request.send(JSON.stringify(rowsRequest));
          nroPaginaPdf++;
          rowsRequest = {};
        } else if ((rowsRequest != {} && idRequestField == 0)) {
          rowsRequest["head_det_persona"] = objectJsonTabla["head_det_persona"];
          rowsRequest["det_persona"] = objectJsonTabla["det_persona"];
          rowsRequest["header_movimientos_general"] = objectJsonTabla["header_movimientos_general"];
          rowsRequest["head_movimientos_persona"] = objectJsonTabla["head_movimientos_persona"]
          rowsRequest["fecha_desde"] = fechaDesde;
          rowsRequest["fecha_hasta"] = fechaHasta;
          rowsRequest["fitros"] = filtroSeleccionados;
          let request = new XMLHttpRequest();
          listaDeRequest[idRequestField] = request;
          request.open("POST", "Controladores/GeneradorPdf.php", true);
          request.setRequestHeader("x-request-id", idRequestField);
          request.onreadystatechange = addPdf;
          request.send(JSON.stringify(rowsRequest));
          nroPaginaPdf++;
          rowsRequest = {};
        }
          idRequestField = 0;
      }
  </script>  
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
            $Element->getMenuReportes(2);?>
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
        <div class="brand">Reportes</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuReportes(0);?>
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
            $Element->getMenuReportes(2);?>
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
        <p>Movimientos de Persona</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
    <div class="row">
        <div class="col-md-2" style="padding-right: 0px;padding-left: 0pc;">
          <button type = "button" class = "btn btn-danger" onClick = "location.href = 'view_listados.php'">Atras</button>
          <button type="button" class="btn btn-secondary" onclick="enviarImprimirPdf();"> Imprimir</button>
        </div>
        <div class="col-md-8">
        <?php
        $Con = new Conexion();
        $Con->OpenConexion();

        $consultaGeneralPermisos = "CREATE TEMPORARY TABLE GIN " ;
        $consultaUsuarioPermisos = "CREATE TEMPORARY TABLE INN ";
    
        $motivosVisiblesParaUsuario =  "SELECT MT.id_motivo
                                        FROM motivo MT,
                                            categoria  C,
                                            categorias_roles CS
                                        WHERE C.cod_categoria = MT.cod_categoria
                                          and MT.estado = 1
                                          and C.estado = 1
                                          and CS.id_categoria = C.id_categoria
                                          and CS.id_tipousuario = $TipoUsuario
                                          and CS.estado = 1";
    
        $motivosVisiblesParaTodoUsuario = "SELECT MT.id_motivo
                                            FROM motivo MT,
                                                categoria  C
                                        WHERE C.cod_categoria = MT.cod_categoria
                                          and MT.estado = 1
                                          and C.estado = 1
                                          and MT.id_motivo <> 1   
                                          and C.id_categoria NOT IN (SELECT id_categoria
                                                                      FROM categorias_roles CS)";
        $motivosVisiblesParaUsuario = $consultaUsuarioPermisos . $motivosVisiblesParaUsuario;
        $motivosVisiblesParaTodoUsuario = $consultaGeneralPermisos . $motivosVisiblesParaTodoUsuario;
        $MessageError = "Problemas al crear la tabla temporaria de usuarios";
        $motivosUsuario = mysqli_query(
                          $Con->Conexion,$motivosVisiblesParaUsuario
                          ) or die($MessageError);
    
        $MessageError = "Problemas al crear la tabla temporaria general";
        $motivosTodoUsuario = mysqli_query(
                          $Con->Conexion,$motivosVisiblesParaTodoUsuario
                          ) or die($MessageError);
      	if(!isset($_REQUEST["Anio"])){
              $Fecha_Inicio = implode("-", array_reverse(explode("/",$_REQUEST["Fecha_Desde"])));
              $Fecha_Fin = implode("-", array_reverse(explode("/",$_REQUEST["Fecha_Hasta"])));
              $ID_Persona = $_REQUEST["ID_Persona"];
              $Edad_Desde = $_REQUEST["Edad_Desde"];
              $Edad_Hasta = $_REQUEST["Edad_Hasta"];
              $Meses_Desde = $_REQUEST["Meses_Desde"];
              $Meses_Hasta = $_REQUEST["Meses_Hasta"];
              $Domicilio = $_REQUEST["Domicilio"];
              $Manzana = $_REQUEST["Manzana"];
              $Lote = $_REQUEST["Lote"];
              $Familia = $_REQUEST["Familia"];
              $Barrio = $_REQUEST["ID_Barrio"];

              $Nro_Carpeta = $_REQUEST["Nro_Carpeta"];
              $Nro_Legajo = $_REQUEST["Nro_Legajo"];
              
              $ID_Motivo = $_REQUEST["ID_Motivo"];
              $ID_Motivo2 = $_REQUEST["ID_Motivo2"];
              $ID_Motivo3 = $_REQUEST["ID_Motivo3"];
              $MotivosOpciones = [
                "ID_Motivo" => $ID_Motivo,
                "ID_Motivo2" => $ID_Motivo2,
                "ID_Motivo3" => $ID_Motivo3,
              ];

              if (isset($_REQUEST["ID_Motivo4"])) {
                $ID_Motivo4 = $_REQUEST["ID_Motivo4"];
                $MotivosOpciones["ID_Motivo4"] = $ID_Motivo4;
              } else {
                $ID_Motivo4 = 1;
              }
              if (isset($_REQUEST["ID_Motivo5"])) {
                $ID_Motivo5 = $_REQUEST["ID_Motivo5"];
                $MotivosOpciones["ID_Motivo5"] = $ID_Motivo5;
              } else {
                $ID_Motivo5 = 1;
              }
              $ID_Categoria = $_REQUEST["ID_Categoria"]; 
              $ID_Escuela = $_REQUEST["ID_Escuela"];
              if (isset($_REQUEST["Trabajo"])) {
                $Trabajo = $_REQUEST["Trabajo"];
              } else {
                $Trabajo = null;
              }
              $Mostrar = $_REQUEST["Mostrar"];
              $ID_CentroSalud = $_REQUEST["ID_CentroSalud"];
              $ID_OtraInstitucion = $_REQUEST["ID_OtraInstitucion"];
              $ID_Responsable = $_REQUEST["ID_Responsable"];

              $listaDeMotivos = "(".implode(",",array_filter($MotivosOpciones)).")";

              // Las dos querys siguientes son iguales salvo que en la primer query se filtra por persona,
              // ya que el foco es considerar a una sola persona
              // pero en la segunda query se traen todos los registros de todas las personas, no solo una persona.
              $ConsultarMovimientosPersona = "select M.id_movimiento, M.fecha, P.apellido, P.nombre, P.domicilio,
                                                P.fecha_nac, P.documento, P.obra_social, P.localidad, P.edad, P.meses,
                                                B.Barrio, M.motivo_1, M.motivo_2, M.motivo_3, M.motivo_4,M.motivo_5,
                                                M.observaciones, R.responsable, CS.centro_salud, I.Nombre as 'NombreInst' 
                                              from movimiento M,
                                                   persona P,
                                                   barrios B,
                                                   motivo MT,
                                                   categoria C,
                                                   centros_salud CS,
                                                   otras_instituciones I,
                                                   responsable R
                                              where M.id_persona = P.id_persona 
                                                    and B.ID_Barrio = P.ID_Barrio
                                                    and M.id_centro = CS.id_centro
                                                    and M.id_otrainstitucion = I.ID_OtraInstitucion
                                                    and R.id_resp = M.id_resp
                                                    and M.estado = 1
                                                    and P.estado = 1
                                                    and MT.estado = 1
                                                    and C.estado = 1
                                                    and M.fecha between '$Fecha_Inicio' and '$Fecha_Fin'
                                                    and P.ID_Persona = $ID_Persona";

          	  $Consulta = "select M.id_movimiento, M.fecha, M.id_persona, MONTH(M.fecha) as 'Mes',
                                  YEAR(M.fecha) as 'Anio', B.Barrio, P.manzana, P.documento, P.obra_social,
                                  P.localidad, P.edad, P.meses, P.lote, P.familia, P.apellido,
                                  P.nombre, P.fecha_nac, P.domicilio, M.motivo_1, M.motivo_2, M.motivo_3,
                                  M.motivo_4,M.motivo_5,R.responsable, M.observaciones, CS.centro_salud,
                                  I.Nombre as 'NombreInst' 
                            from movimiento M,
                                 persona P,
                                 barrios B,
                                 motivo MT,
                                 categoria C,
                                 centros_salud CS,
                                 otras_instituciones I,
                                 responsable R
                            where M.id_persona = P.id_persona
                                  and B.ID_Barrio = P.ID_Barrio
                                  and M.id_centro = CS.id_centro
                                  and M.id_otrainstitucion = I.ID_OtraInstitucion
                                  and R.id_resp = M.id_resp
                                  and M.estado = 1
                                  and P.estado = 1
                                  and MT.estado = 1
                                  and C.estado = 1
                                  and M.fecha between '$Fecha_Inicio' and '$Fecha_Fin'"; 

              $filtros = [];
              $filtrosSeleccionados = [];
              $tomarRetTodos = array();

              $filtrosSeleccionados["Fecha_Desde"] = $_REQUEST["Fecha_Desde"];
              $filtrosSeleccionados["Fecha_Hasta"] = $_REQUEST["Fecha_Hasta"];

              //$Con = new Conexion();
              //$Con->OpenConexion();
              
              $filtMovimientoPorMotivo = " and (M.motivo_1 IN $listaDeMotivos
                                            or M.motivo_2 IN $listaDeMotivos
                                            or M.motivo_3 IN $listaDeMotivos
                                            or M.motivo_4 IN $listaDeMotivos
                                            or M.motivo_5 IN $listaDeMotivos )";

              if(count(array_filter($MotivosOpciones)) > 0){
                $Consulta .= $filtMovimientoPorMotivo;
                $ConsultarMovimientosPersona .= $filtMovimientoPorMotivo;
              }

              if($ID_Persona > 0){
                $Consulta .= " and P.id_persona = $ID_Persona";

                $ConsultarPersona = "select apellido, nombre
                                     from persona
                                     where ID_Persona = " . $ID_Persona." limit 1";

                $EjecutarConsultarPersona = mysqli_query($Con->Conexion,$ConsultarPersona) or die("Problemas al consultar filtro Persona");
                $RetConsultarPersona = mysqli_fetch_assoc($EjecutarConsultarPersona);
                $filtros[] = "Persona: " . $RetConsultarPersona["apellido"].", " . $RetConsultarPersona["nombre"];
                $filtrosSeleccionados["ID_Persona"] = $ID_Persona;
              }

              /*
              if ($Meses_Desde !== null && $Meses_Desde !== "" && $Meses_Hasta !== null && $Meses_Hasta !== "") {
                // $Consulta .= " and P.edad between $Edad_Desde and $Edad_Hasta";
                $Consulta .= "and P.meses <= $Meses_Hasta";
                if ($Edad_Desde == null) {
                  $Consulta .= " and P.edad >= $Edad_Desde";
                  $filtros[] = "Meses: Desde " . $Meses_Desde . " hasta " . $Meses_Hasta;
                } else {
                  $filtros[] = "Meses: Desde 0 hasta " . $Meses_Hasta;
                }
              }
              */
              /*
              if ($Meses_Desde !== null && $Meses_Desde !== "" && $Meses_Hasta !== null && $Meses_Hasta !== "") {
                // $Consulta .= " and P.edad between $Edad_Desde and $Edad_Hasta";
                $Consulta .= "and P.meses <= $Meses_Hasta";
                if ($Edad_Desde == null) {
                  $Consulta .= " and P.edad >= $Edad_Desde";
                  $filtros[] = "Meses: Desde " . $Meses_Desde . " hasta " . $Meses_Hasta;
                } else {
                  $filtros[] = "Meses: Desde 0 hasta " . $Meses_Hasta;
                }
              }
              */

            if ($Edad_Desde !== null && $Edad_Desde !== "" && $Edad_Hasta !== null && $Edad_Hasta !== "") {
              $Consulta .= " and P.edad >= $Edad_Desde and P.edad <= $Edad_Hasta";
              $filtros[] = "Edad: Desde " . $Edad_Desde . " hasta " . $Edad_Hasta;
              if ($Meses_Hasta !== null && $Meses_Hasta !== "") {
                $Consulta .= " and (P.edad < $Edad_Hasta or P.meses <= $Meses_Hasta)";
                if ($Meses_Desde != null) {
                  $Consulta .= " and P.meses >= $Meses_Desde ";
                  $filtros[] = "Meses: Desde " . $Meses_Desde . " hasta " . $Meses_Hasta;
                } else {
                  $filtros[] = "Meses: Desde 0 hasta " . $Meses_Hasta;
                }
              }
            } else {
              if ($Meses_Desde !== null && $Meses_Desde !== "" && $Meses_Hasta !== null && $Meses_Hasta !== "") {
                $Consulta .= " and P.meses <= $Meses_Hasta and P.edad = 0 ";
                if ($Meses_Desde != null) {
                  $Consulta .= " and P.meses >= $Meses_Desde";
                  $filtros[] = "Meses: Desde " . $Meses_Desde . " hasta " . $Meses_Hasta;
                } else {
                  $filtros[] = "Meses: Desde 0 hasta " . $Meses_Hasta;
                }
              }
            }

              if($Domicilio != null && $Domicilio != ""){
                $ConsultarMovimientosPersona .= " and P.domicilio like '%$Domicilio%'";
                $Consulta .= " and P.domicilio like '%$Domicilio%'";
                $filtros[] = "Domicilio: " . $Domicilio;
                $filtrosSeleccionados["Domicilio"] = $Domicilio;
              }

              if($Manzana != null && $Manzana != ""){
                $ConsultarMovimientosPersona .= " and P.manzana = '$Manzana'";
                $Consulta .= " and P.manzana = '$Manzana'";
                $filtros[] = "Manzana: " . $Manzana;
                $filtrosSeleccionados["Manzana"] = $Manzana;
              }

              if($Lote != null && $Lote != ""){
                $ConsultarMovimientosPersona .= " and P.lote = $Lote";
                $Consulta .= " and P.lote = $Lote";
                $filtros[] = "Lote: " . $Lote;
                $filtrosSeleccionados["Lote"] = $Lote;
              }

              if($Familia != null && $Familia != ""){
                $ConsultarMovimientosPersona .= " and P.familia = $Familia";
                $Consulta .= " and P.familia = $Familia";
                $filtros[] = "Sublote: " . $Familia;
                $filtrosSeleccionados["Familia"] = $Familia;
              }

              if($Nro_Carpeta != null && $Nro_Carpeta != ""){
                $ConsultarMovimientosPersona .= " and P.nro_carpeta = '$Nro_Carpeta'";
                $Consulta.= " and P.nro_carpeta = '$Nro_Carpeta'";
                $filtros[] = "Nro_carpeta: " . $Nro_Carpeta;
                // $filtrosSeleccionados["Nro_Carpeta"] = $Nro_Carpeta;
              }
              $filtrosSeleccionados["Nro_Carpeta"] = $Nro_Carpeta;

              if($Nro_Legajo != null && $Nro_Legajo != ""){
                $ConsultarMovimientosPersona .= " and P.nro_legajo = '$Nro_Legajo'";
                $Consulta.= " and P.nro_legajo = '$Nro_Legajo'";
                $filtros[] =  " Nro_legajo : " . $Nro_Legajo;
                $filtrosSeleccionados["Nro_Legajo"] = $Nro_Legajo;
              }
              
              // if(count($Barrio) > 1){
              if(count((Array)$Barrio) > 1){
                $filtroBarrios = 'Barrios:';
                foreach($Barrio as $key => $valueBarrio){
                  if($key == $Barrio->array_key_first){
                    $Consulta .= " and (";
                  }
                  if($valueBarrio > 0){
                    if($key === count($Barrio) - 1){
                      $Consulta .= " P.ID_Barrio = $valueBarrio )";
                    }else{
                      $Consulta .= " P.ID_Barrio = $valueBarrio or";
                    }

                    $ConsultarBarrio = "select Barrio 
                                        from barrios 
                                        where ID_Barrio = " . $valueBarrio." limit 1";

                    $EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                    $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
                    if($key == $Barrio->array_key_first){
                      $filtroBarrios .= " " . $RetConsultarBarrio['Barrio'];
                    }else{
                      $filtroBarrios .= " - " . $RetConsultarBarrio['Barrio'];
                    }
                  }
                }
                $filtros[] = $filtroBarrios;
              }else{
                if($Barrio[0] > 0){
                  $Consulta .= " and P.ID_Barrio = $Barrio[0]";
                  $ConsultarBarrio = "select Barrio from barrios where ID_Barrio = " . $Barrio[0]." limit 1";
                  $EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                  $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
                  $filtros[] = "Barrio: " . $RetConsultarBarrio['Barrio'];
                  $filtrosSeleccionados["ID_Barrio"] = $Barrio[0];
                }
              }
              
              // $Consulta.= ")";

              // if($Barrio > 0){
              //   $Consulta .= " and P.ID_Barrio = $Barrio";
              // }

              if($ID_Escuela > 0){                
                $ConsultarMovimientosPersona .= " and P.ID_Escuela = $ID_Escuela";
                $Consulta .= " and P.ID_Escuela = $ID_Escuela";
                $ConsultarEscuela = "select Escuela from escuelas where ID_Escuela = " . $ID_Escuela." limit 1";
                $EjecutarConsultarEscuela = mysqli_query($Con->Conexion,$ConsultarEscuela) or die("Problemas al consultar filtro Escuela");
                $RetConsultarEscuela = mysqli_fetch_assoc($EjecutarConsultarEscuela);  
                $filtros[] = "Escuela: " . $RetConsultarEscuela['Escuela'];
                $filtrosSeleccionados["ID_Escuela"] = $ID_Escuela;
              }

              if($Trabajo != null && $Trabajo != ""){
                $ConsultarMovimientosPersona .= " and P.Trabajo like '%$Trabajo%'";
                $Consulta .= " and P.Trabajo like '%$Trabajo%'";
                $filtros[] = "Trabajo: " . $Trabajo;
                $filtrosSeleccionados["Trabajo"] = $Trabajo;                
              }
              /*
              if($ID_Motivo > 0){
                if($ID_Motivo2 > 0 || $ID_Motivo3 > 0){
                  $ConsultarMovimientosPersona .= " and (";
                  $Consulta .= " and (";
                }else{
                  $ConsultarMovimientosPersona .= " and ";
                  $Consulta .= " and ";
                }
                $ConsultarMovimientosPersona .= " (M.motivo_1 = $ID_Motivo or M.motivo_2 = $ID_Motivo or M.motivo_3 = $ID_Motivo)";
                $Consulta .= " (M.motivo_1 = $ID_Motivo or M.motivo_2 = $ID_Motivo or M.motivo_3 = $ID_Motivo)";

                $ConsultarMotivo = "select motivo 
                                    from motivo 
                                    where id_motivo = " . $ID_Motivo." limit 1";

                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);  
                $filtros[] = "Motivo 1: " . $RetConsultarMotivo['motivo'];                
                //$filtrosSeleccionados["ID_Motivo1"] = $ID_Motivo;
              }
              *//*
              $CantOpMotivos = count(array_filter($MotivosOpciones, function($x) { return !empty($x); }));

              if($CantOpMotivos > 0){
                $ConsultarMovimientosPersona .= " and ";
                $Consulta .= " and ";
                if($CantOpMotivos > 1){
                  $ConsultarMovimientosPersona .= " (";
                  $Consulta .= " (";
                }
              }

              if($ID_Motivo > 1){
                $ConsultarMovimientosPersona .= " (M.motivo_1 = $ID_Motivo or M.motivo_2 = $ID_Motivo or M.motivo_3 = $ID_Motivo)";
                $Consulta .= " (M.motivo_1 = $ID_Motivo or M.motivo_2 = $ID_Motivo or M.motivo_3 = $ID_Motivo)";

                $ConsultarMotivo = "select motivo 
                                    from motivo 
                                    where id_motivo = " . $ID_Motivo." limit 1";

                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);  
                $filtros[] = "Motivo 1: " . $RetConsultarMotivo['motivo'];                
                //$filtrosSeleccionados["ID_Motivo1"] = $ID_Motivo;
              }
              if($ID_Motivo2 > 1){
                if($ID_Motivo > 1 ){
                  $ConsultarMovimientosPersona .= " or ";
                  $Consulta .= " or ";
                }
                $ConsultarMovimientosPersona .= "(M.motivo_1 = $ID_Motivo2 or M.motivo_2 = $ID_Motivo2 or M.motivo_3 = $ID_Motivo2)";
                $Consulta .= "(M.motivo_1 = $ID_Motivo2 or M.motivo_2 = $ID_Motivo2 or M.motivo_3 = $ID_Motivo2)";

                $ConsultarMotivo = "select motivo 
                                    from motivo 
                                    where id_motivo = " . $ID_Motivo2." limit 1";

                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);
                $filtros[] = "Motivo 2: " . $RetConsultarMotivo['motivo'];
                //$filtrosSeleccionados["ID_Motivo2"] = $ID_Motivo2;
              }

              if($ID_Motivo3 > 1){
                if($ID_Motivo > 1 || $ID_Motivo2 > 1){
                  $ConsultarMovimientosPersona .= " or ";
                  $Consulta .= " or ";
                }
                $ConsultarMovimientosPersona .= "(M.motivo_1 = $ID_Motivo3 
                                               or M.motivo_2 = $ID_Motivo3 
                                               or M.motivo_3 = $ID_Motivo3)";

                $Consulta .= "(M.motivo_1 = $ID_Motivo3 
                            or M.motivo_2 = $ID_Motivo3 
                            or M.motivo_3 = $ID_Motivo3)";

                $ConsultarMotivo = "select motivo 
                                    from motivo 
                                    where id_motivo = " . $ID_Motivo3." limit 1";

                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);  
                $filtros[] = "Motivo 3: " . $RetConsultarMotivo['motivo'];
                //$filtrosSeleccionados["ID_Motivo3"] = $ID_Motivo3;
              }

              if($ID_Motivo4 > 1){
                if($ID_Motivo > 1 || $ID_Motivo2 > 1 || $ID_Motivo3 > 1){
                  $ConsultarMovimientosPersona .= " or ";
                  $Consulta .= " or ";
                }
                $ConsultarMovimientosPersona .= "(M.motivo_1 = $ID_Motivo4 
                                               or M.motivo_2 = $ID_Motivo4 
                                               or M.motivo_3 = $ID_Motivo4)";

                $Consulta .= "(M.motivo_1 = $ID_Motivo4 
                            or M.motivo_2 = $ID_Motivo4 
                            or M.motivo_3 = $ID_Motivo4)";

                $ConsultarMotivo = "select motivo 
                                    from motivo 
                                    where id_motivo = " . $ID_Motivo4." limit 1";

                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);  
                $filtros[] = "Motivo 4: " . $RetConsultarMotivo['motivo'];
              }

              if($ID_Motivo5 > 1){
                if($ID_Motivo > 1 || $ID_Motivo2 > 1 || $ID_Motivo3 > 1 || $ID_Motivo4 > 1){
                  $ConsultarMovimientosPersona .= " or ";
                  $Consulta .= " or ";
                }
                $ConsultarMovimientosPersona .= "(M.motivo_1 = $ID_Motivo5 
                                               or M.motivo_2 = $ID_Motivo5 
                                               or M.motivo_3 = $ID_Motivo5)";

                $Consulta .= "(M.motivo_1 = $ID_Motivo5
                            or M.motivo_2 = $ID_Motivo5
                            or M.motivo_3 = $ID_Motivo5)";

                $ConsultarMotivo = "select motivo 
                                    from motivo 
                                    where id_motivo = " . $ID_Motivo5." limit 1";
                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);  
                $filtros[] = "Motivo 5: " . $RetConsultarMotivo['motivo'];
              }

              if($CantOpMotivos > 1){
                $ConsultarMovimientosPersona .= ")";
                $Consulta .= ")";
              }
              */
              if($ID_Categoria > 0){
                $ConsultarMovimientosPersona .= " and  ((M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria)
                                                     or (M.motivo_2 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria)
                                                     or (M.motivo_3 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria)
                                                     or (M.motivo_4 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria)
                                                     or (M.motivo_5 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria))";

                $Consulta .= " and  ((M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria)
                                  or (M.motivo_2 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria)
                                  or (M.motivo_3 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria)
                                  or (M.motivo_4 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria)
                                  or (M.motivo_5 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria))";

                $ConsultarCategoria = "select categoria
                                       from categoria
                                       where id_categoria = " . $ID_Categoria." limit 1";

                $EjecutarConsultarCategoria = mysqli_query($Con->Conexion,$ConsultarCategoria) or die("Problemas al consultar filtro Categoria");
                $RetConsultarCategoria = mysqli_fetch_assoc($EjecutarConsultarCategoria);
                $filtros[] = "Categoria: " . $RetConsultarCategoria['categoria'];
                $filtrosSeleccionados["ID_Categoria"] = $ID_Categoria;
              }

              if($ID_CentroSalud > 0){
                $ConsultarMovimientosPersona .= " and CS.id_centro = $ID_CentroSalud";
                $Consulta .= " and CS.id_centro = $ID_CentroSalud";
                $ConsultarCentroSalud = "select centro_salud from centros_salud where id_centro = " . $ID_CentroSalud." limit 1";
                $EjecutarConsultarCentroSalud = mysqli_query($Con->Conexion,$ConsultarCentroSalud) or die("Problemas al consultar filtro Categoria");
                $RetConsultarCentroSalud = mysqli_fetch_assoc($EjecutarConsultarCentroSalud);                  
                $filtros[] = "Centro Salud: " . $RetConsultarCentroSalud['centro_salud'];
                $filtrosSeleccionados["ID_CentroSalud"] = $ID_CentroSalud;
              }

              if($ID_OtraInstitucion > 0){
                $ConsultarMovimientosPersona .= " and I.ID_OtraInstitucion = $ID_OtraInstitucion";
                $Consulta .= " and I.ID_OtraInstitucion = $ID_OtraInstitucion";
                $ConsultarOtraInstitucion = "select Nombre from otras_instituciones where ID_OtraInstitucion = " . $ID_OtraInstitucion." limit 1";
                $EjecutarConsultarOtraInstitucion = mysqli_query($Con->Conexion,$ConsultarOtraInstitucion) or die("Problemas al consultar filtro Categoria");
                $RetConsultarOtraInstitucion = mysqli_fetch_assoc($EjecutarConsultarOtraInstitucion);   
                $filtros[] = "Otra Institucion: " . $RetConsultarOtraInstitucion['Nombre'];
                $filtrosSeleccionados["ID_OtraInstitucion"] = $ID_OtraInstitucion;
              }

              if($ID_Responsable > 0){
                $ConsultarMovimientosPersona .= " and R.ID_Responsable = $ID_Responsable";
                $Consulta .= " and R.id_resp = $ID_Responsable";
                $ConsultarResponsable = "select responsable from responsable where id_resp = " . $ID_Responsable." limit 1";
                $EjecutarConsultarResponsable = mysqli_query($Con->Conexion,$ConsultarResponsable) or die("Problemas al consultar filtro Responsable");
                $RetConsultarResponsable = mysqli_fetch_assoc($EjecutarConsultarResponsable);   
                $filtros[] = "Responsable: " . $RetConsultarResponsable['responsable'];
                $filtrosSeleccionados["ID_Responsable"] = $ID_Responsable;
              }

              $ConsultarMovimientosPersona .= " group by M.id_movimiento,  M.fecha order by M.fecha DESC";

              // Si la persona fue cargada en el formulario de la pagina de filtros de movimiento, se ordenara la lista
              // de movimientos usando los atributos de los movimiento asociados a la persona, en casos contrario, si no
              // se agrega a la persona en el filtrado, se ordenara la lista de movimientos sobre los atributos de cada persona en general.

              if($ID_Persona > 0){
                $Consulta .= " group by M.id_movimiento order by M.fecha DESC, B.Barrio DESC, P.domicilio DESC, P.manzana DESC, P.lote DESC, P.familia DESC, P.domicilio DESC, P.apellido DESC, M.id_movimiento DESC";
              }else{
                $Consulta .= " group by M.id_persona order by M.fecha DESC, B.Barrio DESC, P.domicilio DESC, P.manzana DESC, P.lote DESC, P.familia DESC, P.domicilio DESC, P.apellido DESC, M.id_movimiento DESC";
              }
                            

              //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

              //	CREANDO FILTRO MOSTRAR PERSONAS (SIN MOVIMIENTOS)              
              if($Mostrar > 0){
                //, P.nro_legajo, P.nro_carpeta
              	$ConsultarTodos = "select P.id_persona, B.Barrio, P.manzana, 
                                          P.lote, P.familia, P.apellido, P.nombre,
                                          P.fecha_nac, P.domicilio
                                   from persona P, 
                                        barrios B, 
                                        movimiento M
                                   where not exists(select * 
                                                    from movimiento M2 
                                                    where M2.id_persona = P.id_persona) 
                                     and B.ID_Barrio = P.ID_Barrio 
                                     and P.estado = 1";

                if($ID_Persona > 0){
                  $ConsultarTodos .= " and P.id_persona = $ID_Persona";
                }

                if($Edad_Desde != null && $Edad_Desde != "" && $Edad_Hasta != null && $Edad_Hasta != ""){                  
                  $ConsultarTodos .= " and P.edad > $Edad_Desde and P.edad < $Edad_Hasta";
                }

                if($Meses_Desde != null && $Meses_Desde != "" && $Meses_Hasta != null && $Meses_Hasta != ""){                  
                  $ConsultarTodos .= " and P.edad = 0 and P.meses > $Meses_Desde and P.meses < $Meses_Hasta";
                }

                if($Domicilio != null && $Domicilio != ""){
                  $ConsultarTodos .= " and P.domicilio like '%$Domicilio%'";
                }

                if($Manzana != null && $Manzana != ""){
                  $ConsultarTodos .= " and P.manzana = '$Manzana'";
                }

                if($Lote != null && $Lote != ""){
                  $ConsultarTodos .= " and P.lote = $Lote";
                }

                if($Familia != null && $Familia != ""){
                  $ConsultarTodos .= " and P.familia = $Familia";
                }

                if($Nro_Carpeta != null && $Nro_Carpeta != ""){
                  $ConsultarTodos .= " and P.nro_carpeta = $Nro_Carpeta";
                }

                if($Nro_Legajo != null && $Nro_Legajo != ""){
                  $ConsultarTodos .= " and P.nro_legajo = $Nro_Legajo";
                }
                if(count($Barrio) > 1){
                  $filtroBarrios = 'Barrios:';
                  foreach($Barrio as $key => $valueBarrio){
                    if($key == $Barrio->array_key_first){
                      $ConsultarTodos .= " and (";
                    }
                    if($valueBarrio > 0){
                      if($key === count($Barrio) - 1){
                        $ConsultarTodos .= " P.ID_Barrio = $valueBarrio )";
                      }else{
                        $ConsultarTodos .= " P.ID_Barrio = $valueBarrio or";
                      }
                      $ConsultarBarrio = "select Barrio from barrios where ID_Barrio = " . $valueBarrio." limit 1";
                      $EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                      $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);   
                      if($key == $Barrio->array_key_first){
                        $filtroBarrios .= " " . $RetConsultarBarrio['Barrio'];   
                      }else{
                        $filtroBarrios .= " - " . $RetConsultarBarrio['Barrio'];   
                      }                                   
                    }
                  }
                  $filtros[] = $filtroBarrios;
                }else{
                  if($Barrio[0] > 0){
                    $ConsultarTodos .= " and P.ID_Barrio = $Barrio[0]";
                    $ConsultarBarrio = "select Barrio from barrios where ID_Barrio = " . $Barrio[0]." limit 1";
                    $EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                    $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
                    $filtros[] = "Barrio: " . $RetConsultarBarrio['Barrio'];
                  }                
                }

                if($ID_Escuela > 0){
                  $ConsultarTodos .= " and P.ID_Escuela = $ID_Escuela";
                }

                if($Trabajo != null && $Trabajo != ""){
                  $ConsultarTodos .= " and P.Trabajo like '%$Trabajo%'";
                }

                if($ID_Persona > 0){
                  $ConsultarTodos .= " group by P.id_movimiento, M.fecha order by M.fecha DESC, P.domicilio DESC, P.apellido DESC, P.nombre DESC";
                }else{
                  $ConsultarTodos .= " group by P.id_persona,P.id_movimiento, M.fecha order by M.fecha DESC, P.domicilio DESC, P.apellido DESC, P.nombre DESC";
                }

                // $ConsultarTodos .= " group by P.id_persona order by P.apellido, P.nombre";

                // echo "DEBUG: " . $ConsultarTodos;
                // var_dump($ConsultarTodos);



              	$MensajeErrorTodos = "No se pudieron consultar los datos de todas las personas";

              	$EjecutarConsultarTodos = mysqli_query($Con->Conexion,$ConsultarTodos) or die($MensajeErrorTodos);                

                // CAMBIOS CON TODOS                
                // $tomarRetTodos = mysqli_fetch_array($EjecutarConsultarTodos);
                
                                
              	while($RetTodos = mysqli_fetch_assoc($EjecutarConsultarTodos)){
                  // PASAR A TODOS
              		// if($RetTodos["fecha_nac"] == 'null'){
	                //   $Fecha_Nacimiento = "Sin Datos";
	                // }else{
	                //   $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$RetTodos["fecha_nac"])));
	                // }

	                // $Table .= "<tr class='SinMovimientos Datos'>";
                  // $Table .= "<td id='Contenido-1'>" . $RetTodos["Barrio"]."</td><td id='Contenido-2'>" . $RetTodos["domicilio"]."</td><td id='Contenido-3' name='datosflia' style='max-width: 50px;'>" . $RetTodos["manzana"]."</td><td id='Contenido-4' name='datosflia' style='max-width: 50px;'>" . $RetTodos["lote"]."</td><td id='Contenido-5' name='datosflia' style='max-width: 50px;'>" . $RetTodos["familia"]."</td><td id='Contenido-6'><a href = 'javascript:window.open(\"view_modpersonas.php?ID=" . $RetTodos["id_persona"]."\",\"Ventana" . $RetTodos["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>" . $RetTodos["apellido"].", " . $RetTodos["nombre"]."</a></td><td id='Contenido-7' style='max-width: 100px;'>" . $Fecha_Nacimiento."</td>";

                  // $ColSpans = $MesesDiferencia * 270;
                  // $Table .= "<td style='width:" . $ColSpans."px'></td>";

                  $RetTodos['tipo'] = "SM";
                  $tomarRetTodos[] = $RetTodos;

              	}
              }

              //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              

              //$Con->CloseConexion();

              // $Consulta .= " group by M.id_persona order by Anio, Mes, B.Barrio, P.domicilio, P.manzana, P.lote, P.familia, P.domicilio, P.apellido, M.id_movimiento";
            
              // $_SESSION["datosNav"] = $filtrosSeleccionados;

              $MensajeError = "No se pudieron consultar los Datos";

              $Etiqueta_Fecha_Inicio = implode("-", array_reverse(explode("-",$Fecha_Inicio)));
              $Etiqueta_Fecha_Fin = implode("-", array_reverse(explode("-",$Fecha_Fin)));

      	?>
        <center><p class = "LblForm">ENTRE: <?php echo $Etiqueta_Fecha_Inicio." Y " . $Etiqueta_Fecha_Fin; ?></p></center>
        <span> Filtros seleccionados </span>
        <!-- <i class="fa fa-filter"></i> -->
        <!-- < ?php echo "DEBUG: " . $Consulta; ?>       -->
        <?php
        // echo "DEBUG: " . $Consulta."<br><br>";
          foreach($filtros as $value){
            echo "<span class='etFiltros'>" . $value."</span> ";
            // echo "DEBUG: " . $ConsultarMovimientosPersona;
          }
        }
        ?>
        </div>
        <div class="col-md-2">
          <div class="row">
            <button type = "button" class = "btn btn-secondary" data-toggle="modal" data-target="#configModal">Config</button>
            <!--<button type="button" class="btn btn-secondary" onClick="enviarImprimir()">Imprimir</button>-->
          </div>
        </div>
     </div>
     <div class = "row">
      <div class = "col-10">
          <!-- Search -->
        <div class = "row">
          <?php
            if(isset($_REQUEST["ID_Persona"]) && $_REQUEST["ID_Persona"]!=null && $_REQUEST["ID_Persona"] > 0){
              $ID_Persona = $_REQUEST["ID_Persona"];

              //$Con = new Conexion();
              //$Con->OpenConexion();

              $ConsultarDatos = "select * 
                                 from persona 
                                 where id_persona = $ID_Persona";
              $MensajeErrorDatos = "No se pudo consultar los Datos de la Persona";

              $EjecutarConsultarDatos = mysqli_query($Con->Conexion,$ConsultarDatos) or die($MensajeErrorDatos);

              $Ret = mysqli_fetch_assoc($EjecutarConsultarDatos);

              $filtrosSeleccionados["NombrePersona"] = $Ret["apellido"].", " . $Ret["nombre"];

              $_SESSION["datosNav"]["NombrePersona"] = $filtrosSeleccionados["NombrePersona"];

              $Persona = new Persona($ID_Persona);
              $jsonTable = array();
              $head_det_persona = [];
              $head_movimientos = [];
              if($ID_Config == 'grid'){
                $Table = "<table class='table'><thead><tr><th></th><th>Detalles de la Persona</th></tr></thead>";
                $tablePrint = "<table class='table'><thead><tr><th></th><th>Detalles de la Persona</th></tr></thead>";
                $Table .= "<tr><td>Id</td><td>" . $Persona->getID_Persona() . "</td></tr>";
                $tablePrint .= "<tr><td>Id</td><td>" . $Persona->getID_Persona() . "</td></tr>";
                $jsonTable["det_persona"]["id"] = $Persona->getID_Persona();
                $head_det_persona[] = "id";
                $Table .= "<tr><td>Apellido</td><td>" . $Persona->getApellido() . "</td></tr>";
                $tablePrint .= "<tr><td>Apellido</td><td>" . $Persona->getApellido() . "</td></tr>";
                $jsonTable["det_persona"]["apellido"] = $Persona->getApellido();
                $head_det_persona[] = "apellido";
                $Table .= "<tr><td>Nombre</td><td>" . $Persona->getNombre() . "</td></tr>";
                $tablePrint .= "<tr><td>Nombre</td><td>" . $Persona->getNombre() . "</td></tr>";
                $jsonTable["det_persona"]["nombre"] = $Persona->getNombre();
                $head_det_persona[] = "nombre";
                $Table .= "<tr><td>Documento</td><td>" . $Persona->getDNI() . "</td></tr>";
                $tablePrint .= "<tr><td>Documento</td><td>" . $Persona->getDNI() . "</td></tr>";
                $jsonTable["det_persona"]["documento"] = $Persona->getDNI();
                $head_det_persona[] = "documento";
                $Table .= "<tr><td>Fecha de Nacimiento</td><td>" . $Persona->getFecha_Nacimiento() . "</td></tr>";
                $tablePrint .= "<tr><td>Fecha de Nacimiento</td><td>" . $Persona->getFecha_Nacimiento() . "</td></tr>";
                $jsonTable["det_persona"]["fecha_nacimiento"] = $Persona->getFecha_Nacimiento();
                $head_det_persona[] = "fecha_nacimiento";
                if($Persona->getEdad() == 2020){
                  $Table .= "<tr><td>Edad</td><td>No se cargo fecha de nacimiento</td></tr>";
                  $tablePrint .= "<tr><td>Edad</td><td>No se cargo fecha de nacimiento</td></tr>";
                  $jsonTable["det_persona"]["Años"] = "sin datos";
                  $head_det_persona[] = "Años";
                }else{
                  $Table .= "<tr><td>Edad</td><td>" . $Persona->getEdad() . "</td></tr>";
                  $tablePrint .= "<tr><td>Edad</td><td>" . $Persona->getEdad() . "</td></tr>";
                  $jsonTable["det_persona"]["Años"] = $Persona->getEdad();
                  $head_det_persona[] = "Años";
                }
                $Table .= "<tr><td>Meses</td><td>" . $Persona->getMeses() . "</td></tr>";              
                $tablePrint .= "<tr><td>Meses</td><td>" . $Persona->getMeses() . "</td></tr>";
                $jsonTable["det_persona"]["Meses"] = $Persona->getMeses();
                $head_det_persona[] = "Meses";
                $Table .= "<tr><td>Localidad</td><td>" . $Persona->getLocalidad() . "</td></tr>";
                $tablePrint .= "<tr><td>Localidad</td><td>" . $Persona->getLocalidad() . "</td></tr>";
                $jsonTable["det_persona"]["Localidad"] = $Persona->getLocalidad();
                $head_det_persona[] = "Localidad";
                $Table .= "<tr><td>Barrio</td><td>" . $Persona->getBarrio() . "</td></tr>";      
                $tablePrint .= "<tr><td>Barrio</td><td>" . $Persona->getBarrio() . "</td></tr>";      
                $jsonTable["det_persona"]["Barrio"] = $Persona->getBarrio();
                $head_det_persona[] = "Barrio";
                $Table .= "<tr><td>Domicilio</td><td>" . $Persona->getDomicilio() . "</td></tr>";
                $tablePrint .= "<tr><td>Domicilio</td><td>" . $Persona->getDomicilio() . "</td></tr>";
                $jsonTable["det_persona"]["Domicilio"] = $Persona->getDomicilio();
                $head_det_persona[] = "Domicilio";
                $Table .= "<tr><td>Manzana</td><td>" . $Persona->getManzana() . "</td></tr>";
                $tablePrint .= "<tr><td>Manzana</td><td>" . $Persona->getManzana() . "</td></tr>";
                $jsonTable["det_persona"]["manzana"] = $Persona->getManzana();
                $head_det_persona[] = "manzana";
                $Table .= "<tr><td>Lote</td><td>" . $Persona->getLote() . "</td></tr>";
                $tablePrint .= "<tr><td>Lote</td><td>" . $Persona->getLote() . "</td></tr>";
                $jsonTable["det_persona"]["lote"] = $Persona->getLote();
                $head_det_persona[] = "lote";
                $Table .= "<tr><td>Sub-lote</td><td>" . $Persona->getFamilia() . "</td></tr>";
                $tablePrint .= "<tr><td>Sub-lote</td><td>" . $Persona->getFamilia() . "</td></tr>";
                $jsonTable["det_persona"]["sub_lote"] = $Persona->getFamilia();
                $head_det_persona[] = "sub_lote";
                $Table .= "<tr><td>Telefono</td><td>" . $Persona->getTelefono() . "</td></tr>";
                $tablePrint .= "<tr><td>Telefono</td><td>" . $Persona->getTelefono() . "</td></tr>";
                $jsonTable["det_persona"]["telefono"] = $Persona->getTelefono();
                $head_det_persona[] = "telefono";
                $Table .= "<tr><td>E-Mail</td><td>" . $Persona->getMail() . "</td></tr>";
                $tablePrint .= "<tr><td>E-Mail</td><td>" . $Persona->getMail() . "</td></tr>";
                $jsonTable["det_persona"]["mail"] = $Persona->getMail();
                $head_det_persona[] = "mail";
                $Table .= "<tr><td>Obra Social</td><td>" . $Persona->getObra_Social() . "</td></tr>";
                $tablePrint .= "<tr><td>Obra Social</td><td>" . $Persona->getObra_Social() . "</td></tr>";
                $jsonTable["det_persona"]["Obra Social"] = $Persona->getObra_Social();
                $head_det_persona[] = "Obra Social";
                $Table .= "<tr><td>Escuela</td><td>" . $Persona->getEscuela() . "</td></tr>";
                $tablePrint .= "<tr><td>Escuela</td><td>" . $Persona->getEscuela() . "</td></tr>";
                $jsonTable["det_persona"]["escuela"] = $Persona->getEscuela();
                $head_det_persona[] = "escuela";
                $Table .= "<tr><td>Nro. Legajo</td><td>" . $Persona->getNro_legajo() . "</td></tr>";
                $tablePrint .= "<tr><td>Nro. Legajo</td><td>" . $Persona->getNro_legajo() . "</td></tr>";
                $jsonTable["det_persona"]["nro_legajo"] = $Persona->getNro_Legajo();
                $head_det_persona[] = "nro_legajo";
                $Table .= "<tr><td>Nro. Carpeta</td><td>" . $Persona->getNro_Carpeta() . "</td></tr>";
                $tablePrint .= "<tr><td>Nro. Carpeta</td><td>" . $Persona->getNro_Carpeta() . "</td></tr>";
                $jsonTable["det_persona"]["nro_carpeta"] = $Persona->getNro_Carpeta();
                $head_det_persona[] = "nro_carpeta";
                $Table .= "<tr><td>Observación</td><td>" . $Persona->getObservaciones() . "</td></tr>";
                $tablePrint .= "<tr><td>Observación</td><td>" . $Persona->getObservaciones() ."</td></tr>";
                $jsonTable["det_persona"]["observacion"] = $Persona->getObservaciones();
                $head_det_persona[] = "observacion";
                $Table .= "<tr><td>Cambio de Domicilio</td><td>" . $Persona->getCambio_Domicilio() . "</td></tr>";
                $tablePrint .= "<tr><td>Cambio de Domicilio</td><td>" . $Persona->getCambio_Domicilio() . "</td></tr>";
                $jsonTable["det_persona"]["cmb_domicilio"] = $Persona->getCambio_Domicilio();
                $head_det_persona[] = "cmb_domicilio";
                $Table .= "</table>";
                $tablePrint .= "</table>";
              }else{
                $Table = "<table class='table'><thead><tr><th></th><th>Detalles de la Persona</th></tr></thead>";
                $tablePrint = "<table class='table'><thead><tr><th></th><th>Detalles de la Persona</th></tr></thead>";
                $Table .= "<tr><td>Id</td><td>" . $Persona->getID_Persona() . "</td></tr>";
                $tablePrint .= "<tr><td>Id</td><td>" . $Persona->getID_Persona() . "</td></tr>";
                $jsonTable["det_persona"]["id"] = $Persona->getID_Persona();
                $head_det_persona[] = "id";
                $Table .= "<tr><td>Apellido</td><td>" . $Persona->getApellido() . "</td></tr>";
                $tablePrint .= "<tr><td>Apellido</td><td>" . $Persona->getApellido() . "</td></tr>";
                $head_det_persona[] = "apellido";
                $jsonTable["det_persona"]["apellido"] = $Persona->getApellido();
                $Table .= "<tr><td>Nombre</td><td>" . $Persona->getNombre() . "</td></tr>";
                $tablePrint .= "<tr><td>Nombre</td><td>" . $Persona->getNombre() . "</td></tr>";
                $jsonTable["det_persona"]["nombre"] = $Persona->getNombre();
                $head_det_persona[] = "nombre";
                $Table .= "<tr><td>Documento</td><td>" . $Persona->getDNI() . "</td></tr>";
                $tablePrint .= "<tr><td>Documento</td><td>" . $Persona->getDNI() . "</td></tr>";
                $jsonTable["det_persona"]["documento"] = $Persona->getDNI();
                $head_det_persona[] = "documento";
                $Table .= "<tr><td>Fecha de Nacimiento</td><td>" . $Persona->getFecha_Nacimiento() . "</td></tr>";
                $tablePrint .= "<tr><td>Fecha de Nacimiento</td><td>" . $Persona->getFecha_Nacimiento() . "</td></tr>";
                $jsonTable["det_persona"]["fecha_nacimiento"] = $Persona->getFecha_Nacimiento();
                $head_det_persona[] = "fecha_nacimiento";
                if($Persona->getEdad() == 2020){
                  $Table .= "<tr><td>Edad</td><td>No se cargo fecha de nacimiento</td></tr>";
                  $tablePrint .= "<tr><td>Edad</td><td>No se cargo fecha de nacimiento</td></tr>";
                  $jsonTable["det_persona"]["Años"] = "sin datos";
                  $head_det_persona[] = "Años";
                }else{
                  $Table .= "<tr><td>Edad</td><td>" . $Persona->getEdad() . "</td></tr>";
                  $tablePrint .= "<tr><td>Edad</td><td>" . $Persona->getEdad() . "</td></tr>";
                  $jsonTable["det_persona"]["Años"] = $Persona->getEdad();
                  $head_det_persona[] = "Años";
                }                            
                $Table .= "<tr><td>Meses</td><td>" . $Persona->getMeses() . "</td></tr>";
                $tablePrint .= "<tr><td>Meses</td><td>" . $Persona->getMeses() . "</td></tr>";
                $jsonTable["det_persona"]["Meses"] = $Persona->getMeses();
                $head_det_persona[] = "Meses";
                $Table .= "<tr><td>Localidad</td><td>" . $Persona->getLocalidad() . "</td></tr>";
                $tablePrint .= "<tr><td>Localidad</td><td>" . $Persona->getLocalidad() . "</td></tr>";
                $jsonTable["det_persona"]["Localidad"] = $Persona->getLocalidad();
                $head_det_persona[] = "Localidad";
                $Table .= "<tr><td>Barrio</td><td>" . $Persona->getBarrio() . "</td></tr>";
                $tablePrint .= "<tr><td>Barrio</td><td>" . $Persona->getBarrio() . "</td></tr>";
                $jsonTable["det_persona"]["Barrio"] = $Persona->getBarrio();
                $head_det_persona[] = "Barrio";
                $Table .= "<tr><td>Domicilio</td><td>" . $Persona->getDomicilio() . "</td></tr>";
                $tablePrint .= "<tr><td>Domicilio</td><td>" . $Persona->getDomicilio() . "</td></tr>";
                $jsonTable["det_persona"]["Domicilio"] = $Persona->getDomicilio();
                $head_det_persona[] = "Domicilio";
                $Table .= "<tr><td>Manzana</td><td>" . $Persona->getManzana() . "</td></tr>";
                $tablePrint .= "<tr><td>Manzana</td><td>" . $Persona->getManzana() . "</td></tr>";
                $jsonTable["det_persona"]["manzana"] = $Persona->getManzana();
                $head_det_persona[] = "manzana";
                $Table .= "<tr><td>Lote</td><td>" . $Persona->getLote() . "</td></tr>";
                $tablePrint .= "<tr><td>Lote</td><td>" . $Persona->getLote() . "</td></tr>";
                $jsonTable["det_persona"]["lote"] = $Persona->getLote();
                $head_det_persona[] = "lote";
                $Table .= "<tr><td>Sub-lote</td><td>" . $Persona->getFamilia() . "</td></tr>";
                $tablePrint .= "<tr><td>Sub-lote</td><td>" . $Persona->getFamilia() . "</td></tr>";
                $jsonTable["det_persona"]["sub_lote"] = $Persona->getFamilia();
                $head_det_persona[] = "sub_lote";
                $Table .= "<tr><td>Telefono</td><td>" . $Persona->getTelefono() . "</td></tr>";
                $tablePrint .= "<tr><td>Telefono</td><td>" . $Persona->getTelefono() . "</td></tr>";
                $jsonTable["det_persona"]["telefono"] = $Persona->getTelefono();
                $head_det_persona[] = "telefono";
                $Table .= "<tr><td>E-Mail</td><td>" . $Persona->getMail() . "</td></tr>";
                $tablePrint .= "<tr><td>E-Mail</td><td>" . $Persona->getMail() . "</td></tr>";
                $jsonTable["det_persona"]["mail"] = $Persona->getMail();
                $head_det_persona[] = "email";
                $Table .= "<tr><td>Obra Social</td><td>" . $Persona->getObra_Social() . "</td></tr>";
                $tablePrint .= "<tr><td>Obra Social</td><td>" . $Persona->getObra_Social() . "</td></tr>";
                $jsonTable["det_persona"]["Obra Social"] = $Persona->getObra_Social();
                $head_det_persona[] = "Obra Social";
                $Table .= "<tr><td>Escuela</td><td>" . $Persona->getEscuela() . "</td></tr>";
                $tablePrint .= "<tr><td>Escuela</td><td>" . $Persona->getEscuela() . "</td></tr>";
                $jsonTable["det_persona"]["escuela"] = $Persona->getEscuela();
                $head_det_persona[] = "escuela";
                $Table .= "<tr><td>Nro. Legajo</td><td>" . $Persona->getNro_Legajo() . "</td></tr>";
                $tablePrint .= "<tr><td>Nro. Legajo</td><td>" . $Persona->getNro_Legajo() . "</td></tr>";
                $jsonTable["det_persona"]["nro_legajo"] = $Persona->getNro_Legajo();
                $head_det_persona[] = "nro_legajo";
                $Table .= "<tr><td>Nro. Carpeta</td><td>" . $Persona->getNro_Carpeta() . "</td></tr>";
                $tablePrint .= "<tr><td>Nro. Carpeta</td><td>" . $Persona->getNro_Carpeta() . "</td></tr>";
                $jsonTable["det_persona"]["nro_carpeta"] = $Persona->getNro_Carpeta();
                $head_det_persona[] = "nro_carpeta";
                $Table .= "<tr><td>Observación</td><td>" . $Persona->getObservaciones() . "</td></tr>";
                $tablePrint .= "<tr><td>Observación</td><td>" . $Persona->getObservaciones() . "</td></tr>";
                $jsonTable["det_persona"]["observacion"] = $Persona->getObservaciones();
                $head_det_persona[] = "observacion";
                $Table .= "<tr><td>Cambio de Domicilio</td><td>" . $Persona->getCambio_Domicilio() . "</td></tr>";
                $tablePrint .= "<tr><td>Cambio de Domicilio</td><td>" . $Persona->getCambio_Domicilio() . "</td></tr>";
                $jsonTable["det_persona"]["cmb_domicilio"] = $Persona->getCambio_Domicilio();
                $head_det_persona[] = "cmb_domicilio";
                $Table .= "</table>";
                $tablePrint .= "</table>";
              }
              $jsonTable["head_det_persona"] = $head_det_persona;

              echo $Table;

              //////////////////////////////////////////////////////TABLAS MOVIMIENTOS DE LA PERSONA /////////////////////////////

              //   $ConsultarMovimientos = "select M.id_movimiento, M.fecha, P.apellido, P.nombre, M.motivo_1, M.motivo_2, M.motivo_3, M.observaciones, R.responsable from movimiento M, responsable R, persona P where M.id_resp = R.id_resp and M.id_persona = P.id_persona and M.id_persona = $ID_Persona";
              $MensajeErrorMovimientos = "No se pudo consultar los movimientos de la persona";

              $TomarMovimientosPersona = mysqli_query($Con->Conexion,$ConsultarMovimientosPersona) or die($MensajeErrorMovimientos);

              $Rows = mysqli_num_rows($TomarMovimientosPersona);

              if($Rows == 0){
                echo "<div class = 'col'></div>";
                echo "<div class = 'col-6'>";
                echo "<p class = 'TextoSinResultados'>No se encontraron Resultados</p><center><button class = 'btn btn-danger' onClick = 'location.href= \"view_movpersonas.php\"'>Atras</button></center>";
                echo "</div>";
                echo "<div class = 'col'></div>";
              }

              while($RetMovimientos = mysqli_fetch_assoc($TomarMovimientosPersona)){
                $RetMovimientos['tipo'] = "CM";
                $tomarRetTodos[] = $RetMovimientos;
              }

              /*foreach ($tomarRetTodos as $clave => $reg) {
                $regdomicilio[$clave] = $reg['domicilio'];
              }*/

              //array_multisort($regdomicilio, SORT_ASC, $tomarRetTodos);

              if ($ID_Config == 'table') {
                $TableMov = "<table id='tabla-movimiento' class='table'>
                              <tr class='thead-dark'>
                                <th rowspan=2 class='trFecha' style='min-width: 150px;'>Fecha</th>
                                <th rowspan=2 class='trMotivos'>Motivo 1</th>
                                <th rowspan=2 class='trMotivos'>Motivo 2</th>
                                <th rowspan=2 class='trMotivos'>Motivo 3</th>
                                <th rowspan=2 class='trPersona'>Persona</th>
                                <th rowspan=2 class='trDNI'>DNI</th>
                                <th rowspan=2 class='trFechaNac'>Fecha Nac.</th>
                                <th rowspan=1 colspan=2 style='text-align:center;' class='trEdad'>Edad</th>
                                <th rowspan=2 class='trObraSocial'>Obra Social</th>
                                <th rowspan=2 class='trDomicilio'>Domicilio</th>
                                <th rowspan=2 class='trBarrio'>Barrio</th>
                                <th rowspan=2 class='trLocalidad'>Localidad</th>
                                <th rowspan=2 class='trObservaciones'>Observaciones</th>
                                <th rowspan=2 class='trResponsable'>Responsable</th>
                                <th rowspan=2 class='trCentrosSalud'>Centro de salud</th>
                                <th rowspan=2 class='trOtrasInstituciones'>Otras Instituciones</th>
                              </tr>
                              <tr>
                                <td class='trEdad' style='color: #fff;background-color: #212529;border-color: #212529;font-weight: bold;'>Año</th>
                                <td class='trMeses' style='color: #fff;background-color: #212529;border-color: #212529;font-weight: bold;'>Meses</th>
                              </tr>";
                
                $TableMovPrint = $TableMov;
              }
              $head_movimientos = [
                "Años",
                "Fecha Nac",
                "Centro Salud",
                "DNI",
                "Fecha",
                "Localidad",
                "Meses",
                "Motivo 1",
                "Motivo 2",
                "Motivo 3",
                "Persona",
                "Domicilio",
                "Barrio",
                "Obra Social",
                "Observaciones",
                "Otra Institucion",
                "Responsable"
              ];
              $jsonTable["head_movimientos_persona"] = $head_movimientos;
              $json_row = [];
              foreach($tomarRetTodos as $clave => $RetTodos){                
                // echo var_dump($RetTodos);
                // echo "<br>";
                if($RetTodos["fecha_nac"] == 'null'){
                  $Fecha_Nacimiento = "Sin Datos";
                }else{
                  $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$RetTodos["fecha_nac"])));
                }
                
                $ID_Movimiento = $RetTodos["id_movimiento"];
                $Fecha = implode("-", array_reverse(explode("-",$RetTodos["fecha"])));
                $Apellido = $RetTodos["apellido"];
                $Nombre = $RetTodos["nombre"];

                ///////////////////////////////////////////////////////////
                $DNI = $RetTodos["documento"];
                $Edad = $RetTodos["edad"];
                $Meses = $RetTodos["meses"];
                $Obra_Social = $RetTodos["obra_social"];
                $Domicilio = $RetTodos["domicilio"];
                $Barrio = $RetTodos["Barrio"];
                $Localidad = $RetTodos["localidad"];

                //////////////////////////////////////////////////////////

                $ID_Motivo_1 = $RetTodos["motivo_1"];
                $ConsultarMotivo_1 = "select M.id_motivo IN (SELECT id_motivo 
                                                             FROM INN) as ConPermisoParaUsr,
                                             M.id_motivo IN (SELECT id_motivo
                                                             FROM GIN) as ConPermisoGeneral,
                                             motivo
                                      from motivo M
                                      where id_motivo = $ID_Motivo_1";

                // echo "DEBUG: ".var_dump($ConsultarMotivo_1);

                $MensajeErrorMotivo_1 = "No se pudo consultar el motivo 1";
                $RetMotivo_1 = mysqli_query($Con->Conexion,$ConsultarMotivo_1) or die($MensajeErrorMotivo_1);
                $RetMotivo_1 = mysqli_fetch_assoc($RetMotivo_1);

                if(count(array_filter($MotivosOpciones)) > 0){
                  if(in_array($ID_Motivo_1, array_values($MotivosOpciones))){
                    $Motivo_1 = ($RetMotivo_1["ConPermisoParaUsr"] || $RetMotivo_1["ConPermisoGeneral"])?$RetMotivo_1["motivo"]:"";
                  } else {
                    $Motivo_1 = "";
                  }
                } else {
                  $Motivo_1 = ($RetMotivo_1["ConPermisoParaUsr"] || $RetMotivo_1["ConPermisoGeneral"])?$RetMotivo_1["motivo"]:"";
                }

                $ID_Motivo_2 = $RetTodos["motivo_2"];
                $ConsultarMotivo_2 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                             M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                             motivo
                                      from motivo M
                                      where id_motivo = $ID_Motivo_2";
                $RetMotivo_2 = mysqli_query($Con->Conexion,$ConsultarMotivo_2) or die($MensajeErrorMotivo_2);
                $RetMotivo_2 = mysqli_fetch_assoc($RetMotivo_2);
                if(count(array_filter($MotivosOpciones)) > 0){
                  if(in_array($ID_Motivo_2, array_values($MotivosOpciones))){
                    $Motivo_2 = ($RetMotivo_2["ConPermisoParaUsr"] || $RetMotivo_2["ConPermisoGeneral"])?$RetMotivo_2["motivo"]:"";
                  } else {
                    $Motivo_2 = "";
                  }
                } else {
                  $Motivo_2 = ($RetMotivo_2["ConPermisoParaUsr"] || $RetMotivo_2["ConPermisoGeneral"])?$RetMotivo_2["motivo"]:"";
                }

                $ID_Motivo_3 = $RetTodos["motivo_3"];
                $ConsultarMotivo_3 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                             M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                             motivo
                                      from motivo M
                                      where id_motivo = $ID_Motivo_3";
                $RetMotivo_3 = mysqli_query($Con->Conexion,$ConsultarMotivo_3) or die($MensajeErrorMotivo_3);
                $RetMotivo_3 = mysqli_fetch_assoc($RetMotivo_3);

                if(count(array_filter($MotivosOpciones)) > 0){
                  if(in_array($ID_Motivo_3, array_values($MotivosOpciones))){
                    $Motivo_3 = ($RetMotivo_3["ConPermisoParaUsr"] || $RetMotivo_3["ConPermisoGeneral"])?$RetMotivo_3["motivo"]:"";
                  } else {
                    $Motivo_3 = "";
                  }
                } else {
                  $Motivo_3 = ($RetMotivo_3["ConPermisoParaUsr"] || $RetMotivo_3["ConPermisoGeneral"])?$RetMotivo_3["motivo"]:"";
                }

                $ID_Motivo_4 = $RetTodos["motivo_4"];
                $ConsultarMotivo_4 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                               M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                               motivo
                                      from motivo M
                                      where id_motivo = $ID_Motivo_4";
                $RetMotivo_4 = mysqli_query($Con->Conexion,$ConsultarMotivo_4) or die($MensajeErrorMotivo_4);
                $RetMotivo_4 = mysqli_fetch_assoc($RetMotivo_4);

                if(count(array_filter($MotivosOpciones)) > 0){
                  if(in_array($ID_Motivo_4, array_values($MotivosOpciones))){
                    $Motivo_4 = ($RetMotivo_4["ConPermisoParaUsr"] || $RetMotivo_4["ConPermisoGeneral"])?$RetMotivo_4["motivo"]:"";
                  } else {
                    $Motivo_4 = "";
                  }
                } else {
                  $Motivo_4 = ($RetMotivo_4["ConPermisoParaUsr"] || $RetMotivo_4["ConPermisoGeneral"])?$RetMotivo_4["motivo"]:"";
                }

                $ID_Motivo_5 = $RetTodos["motivo_5"];
                $ConsultarMotivo_5 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                               M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                               motivo
                                      from motivo M
                                      where id_motivo = $ID_Motivo_5";
                $RetMotivo_5 = mysqli_query($Con->Conexion,$ConsultarMotivo_5) or die($MensajeErrorMotivo_3);
                $RetMotivo_5 = mysqli_fetch_assoc($RetMotivo_5);

                if(count(array_filter($MotivosOpciones)) > 0){
                  if(in_array($ID_Motivo_5, array_values($MotivosOpciones))){
                    $Motivo_5 = ($RetMotivo_5["ConPermisoParaUsr"] || $RetMotivo_5["ConPermisoGeneral"])?$RetMotivo_5["motivo"]:"";
                  } else {
                    $Motivo_5 = "";
                  }
                } else {
                  $Motivo_5 = ($RetMotivo_5["ConPermisoParaUsr"] || $RetMotivo_5["ConPermisoGeneral"])?$RetMotivo_5["motivo"]:"";
                }

                $movimientoVisible = false;
                $movimientoVisible = $movimientoVisible || ($Motivo_1 != "");
                $movimientoVisible = $movimientoVisible || ($Motivo_2 != "");
                $movimientoVisible = $movimientoVisible || ($Motivo_3 != "");
                $movimientoVisible = $movimientoVisible || ($Motivo_4 != "");
                $movimientoVisible = $movimientoVisible || ($Motivo_5 != "");

                if (!$movimientoVisible){
                  continue;
                }

                $Observaciones = $RetTodos["observaciones"];
                $Responsable = $RetTodos["responsable"];
                  //solucionar el error!
                  //  variables inventadas solo para que arme la tabla

                $CentroSalud = $RetTodos["centro_salud"]; //centro_salud
                $OtraInstitucion = $RetTodos["NombreInst"]; //otraInstitucion                
                $DtoMovimiento = new DtoMovimiento($ID_Movimiento,$Fecha,$Apellido,$Nombre,$Motivo_1,$Motivo_2,$Motivo_3,$Motivo_4,$Motivo_5,$Observaciones,$Responsable,$CentroSalud,$OtraInstitucion);                                 
                if($ID_Config == 'grid'){
                  $TableMov = "<table class='table table-dark'>";                
                  $TableMov .= "<tr class='trFecha'><td style = 'width: 30%;'>Fecha</td><td style = 'width: 70%;'>" . $DtoMovimiento->getFecha() . "</td></tr>";
                  $json_row["Fecha"] = $DtoMovimiento->getFecha();
                  if($ID_Motivo > 0 || $ID_Motivo2 > 0 || $ID_Motivo3 > 0){                                          
                    if($ID_Motivo == $ID_Motivo_1){

                      $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                      $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                    }
                    if($ID_Motivo2 == $ID_Motivo_2){
                      $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                      $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                    }
                    if($ID_Motivo3 == $ID_Motivo_3){
                      $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";                      
                      $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                    }

                  }else{                    
                    $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                    $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                    $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                    $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                    $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";
                    $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                  }
                  $TableMov .= "<tr class='trObservaciones'><td style = 'width: 30%;'>Observaciones</td><td style = 'width: 70%;'>" . $DtoMovimiento->getObservaciones() . "</td></tr>";
                  $json_row["obseravciones"] = $DtoMovimiento->getObservaciones();
                  $TableMov .= "<tr class='trResponsable'><td style = 'width: 30%;'>Responsable</td><td style = 'width: 70%;'>" . $DtoMovimiento->getResponsable() . "</td></tr>";
                  $json_row["Responsable"] = $DtoMovimiento->getResponsable();
                  $TableMov .= "<tr class='trCentrosSalud'><td style = 'width: 30%;'>Centro de salud</td><td style = 'width: 70%;'>" . $DtoMovimiento->getCentroSalud() . "</td></tr>";
                  $json_row["Centro Salud"] = $DtoMovimiento->getCentroSalud();
                  $TableMov .= "<tr class='trOtrasInstituciones'><td style = 'width: 30%;'>Otras instituciones</td><td style = 'width: 70%;'>" . $DtoMovimiento->getOtraInstitucion() . "</td></tr>";
                  $json_row["Otra Institucion"] = $DtoMovimiento->getOtraInstitucion();
                  $TableMov .= "</table>";
                  echo $TableMov;
                }else{
                  $TableMov .= "<tr>";              
                  $TableMov .= "<td class='trFecha' style = 'width: auto;'>" . $DtoMovimiento->getFecha() . "</td>";
                  $json_row["Fecha"] = $DtoMovimiento->getFecha();
                  $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                  $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                  $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_2() . "</td>";
                  $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                  $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_3() . "</td>";
                  $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                  $TableMov .= "<td class='trPersona' style = 'width: auto;'>" . $Apellido.", " . $Nombre."</td>";
                  $json_row["Persona"] = $Apellido." " . $Nombre;
                  $TableMov .= "<td class='trDNI' style = 'width: auto;'>" . $DNI."</td>";
                  $json_row["DNI"] = $DNI;
                  $TableMov .= "<td class='trFechaNac' style = 'width: auto;'>" . $Fecha_Nacimiento."</td>";
                  $json_row["Fecha Nac"] = $Fecha_Nacimiento;
                  $TableMov .= "<td class='trEdad' style = 'width: auto;'>" . $Edad."</td>";
                  $json_row["Años"] = $Edad;
                  $TableMov .= "<td class='trMeses' style = 'width: auto;'>" . $Meses."</td>";
                  $json_row["Meses"] = $Meses;
                  $TableMov .= "<td class='trObraSocial' style = 'width: auto;'>" . $Obra_Social."</td>";
                  $json_row["Obra Social"] = $Obra_Social;
                  $TableMov .= "<td class='trDomicilio' style = 'width: auto;'>" . $Domicilio."</td>";
                  $json_row["Domicilio"] = $Domicilio;
                  $TableMov .= "<td class='trBarrio' style = 'width: auto;'>" . $Barrio."</td>";
                  $json_row["Barrio"] = $Barrio;
                  $TableMov .= "<td class='trLocalidad' style = 'width: auto;'>" . $Localidad."</td>";
                  $json_row["Localidad"] = $Localidad;
                  $TableMov .= "<td class='trObservaciones' style = 'width: auto;'>" . $DtoMovimiento->getObservaciones() . "</td>";
                  $json_row["Observaciones"] = $DtoMovimiento->getObservaciones();
                  $TableMov .= "<td class='trResponsable' style = 'width: auto;'>" . $DtoMovimiento->getResponsable() . "</td>";
                  $json_row["Responsable"] = $DtoMovimiento->getResponsable();
                  $TableMov .= "<td class='trCentrosSalud' style = 'width: auto;'>" . $DtoMovimiento->getCentroSalud() . "</td>";
                  $json_row["Centro Salud"] = $DtoMovimiento->getCentroSalud();
                  $TableMov .= "<td class='trOtrasInstituciones' style = 'width: auto;'>" . $DtoMovimiento->getOtraInstitucion() . "</td>";
                  $json_row["Otra Institucion"] = $DtoMovimiento->getOtraInstitucion();
                  $TableMov .= "</tr>"; 
                }
                // Fin de Ciclo de creacion de tabla o grid de un determinado movimiento.
                $jsonTable["movimientos_persona"][] = $json_row;
              }
              if($ID_Config == 'table'){
                $TableMov .= "</table>";

                $classAReemplazar[0] = "~class='table'~";
                $classAReemplazar[] = "~class='table table-dark'~";
                $classAReemplazar[] = "~class='trMeses'~";
                $classAReemplazar[] = "~class='trEdad'~";
                $classAReemplazar[] = "~class='trFechaNac'~";
                $classAReemplazar[] = "~class='trDNI'~";
                $classAReemplazar[] = "~class='trPersona'~";
                $classAReemplazar[] = "~<class='trFecha'~";
                $classAReemplazar[] = "~class='trMotivos'~";
                $classAReemplazar[] = "~class='trOtrasInstituciones'~";
                $classAReemplazar[] = "~class='trCentrosSalud'~";
                $classAReemplazar[] = "~class='trResponsable'~";
                $classAReemplazar[] = "~class='trLocalidad'~";
                $classAReemplazar[] = "~class='trBarrio'~";
                $classAReemplazar[] = "~class='trDomicilio'~";
                $classAReemplazar[] = "~class='trObraSocial'~";
                //$TableMovPrint = preg_replace( $tdReemplazar, "", $TableMov);
                $TableMovPrint = $TableMov;

                echo $TableMov;
              }

              if($ID_Motivo > 0){
                $ConsultarFiltroMotivo = "select id_motivo, motivo from motivo where id_motivo = $ID_Motivo";
                $ErrorConsultarFiltroMotivo = "No se pudo consultar el motivo del filtro ID_Motivo";
                $RetConsFiltroMotivo = mysqli_query($Con->Conexion,$ConsultarFiltroMotivo) or die($ErrorConsultarFiltroMotivo);
                $RetConsMotivo = mysqli_fetch_assoc($RetConsFiltroMotivo);
                $filtrosSeleccionados["ID_Motivo"] = $RetConsMotivo["id_motivo"];  
                $filtrosSeleccionados["Motivo"] = $RetConsMotivo["motivo"];  
                $_SESSION["datosNav"]["ID_Motivo"] = $filtrosSeleccionados["ID_Motivo"];
                $_SESSION["datosNav"]["Motivo"] = $filtrosSeleccionados["Motivo"];
              }

              if($ID_Motivo2 > 0){                
                $ConsultarFiltroMotivo2 = "select id_motivo, motivo from motivo where id_motivo = $ID_Motivo2";
                $ErrorConsultarFiltroMotivo2 = "No se pudo consultar el motivo del filtro ID_Motivo2";
                $RetConsFiltroMotivo2 = mysqli_query($Con->Conexion,$ConsultarFiltroMotivo2) or die($ErrorConsultarFiltroMotivo2);
                $RetConsMotivo2 = mysqli_fetch_assoc($RetConsFiltroMotivo2);
                $filtrosSeleccionados["ID_Motivo2"] = $RetConsMotivo2["id_motivo"];  
                $filtrosSeleccionados["Motivo2"] = $RetConsMotivo2["motivo"];  
                $_SESSION["datosNav"]["ID_Motivo2"] = $filtrosSeleccionados["ID_Motivo2"];
                $_SESSION["datosNav"]["Motivo2"] = $filtrosSeleccionados["Motivo2"];
              }

              if($ID_Motivo3 > 0){                
                $ConsultarFiltroMotivo3 = "select id_motivo, motivo from motivo where id_motivo = $ID_Motivo3";
                $ErrorConsultarFiltroMotivo3 = "No se pudo consultar el motivo del filtro ID_Motivo3";
                $RetConsFiltroMotivo3 = mysqli_query($Con->Conexion,$ConsultarFiltroMotivo3) or die($ErrorConsultarFiltroMotivo3);
                $RetConsMotivo3 = mysqli_fetch_assoc($RetConsFiltroMotivo3);
                $filtrosSeleccionados["ID_Motivo3"] = $RetConsMotivo3["id_motivo"];  
                $filtrosSeleccionados["Motivo3"] = $RetConsMotivo3["motivo"];  
                $_SESSION["datosNav"]["ID_Motivo3"] = $filtrosSeleccionados["ID_Motivo3"];
                $_SESSION["datosNav"]["Motivo3"] = $filtrosSeleccionados["Motivo3"];
              }

                //               while ($RetMovimientos = mysqli_fetch_assoc($TomarMovimientosPersona)) {
                //                 $ID_Movimiento = $RetMovimientos["id_movimiento"];
                //                 $Fecha = implode("-", array_reverse(explode("-",$RetMovimientos["fecha"])));
                //                 $Apellido = $RetMovimientos["apellido"];
                //                 $Nombre = $RetMovimientos["nombre"];

                //                 $ID_Motivo_1 = $RetMovimientos["motivo_1"];
                //                 $ConsultarMotivo_1 = "select motivo from motivo where id_motivo = $ID_Motivo_1";

                //                 // echo "DEBUG: ".var_dump($ConsultarMotivo_1);

                //                 $MensajeErrorMotivo_1 = "No se pudo consultar el motivo 1";
                //                 $RetMotivo_1 = mysqli_query($Con->Conexion,$ConsultarMotivo_1) or die($MensajeErrorMotivo_1);
                //                 $RetMotivo_1 = mysqli_fetch_assoc($RetMotivo_1);
                //                 $Motivo_1 = $RetMotivo_1["motivo"];

                //                 $ID_Motivo_2 = $RetMovimientos["motivo_2"];
                //                 $ConsultarMotivo_2 = "select motivo from motivo where id_motivo = $ID_Motivo_2";
                //                 $RetMotivo_2 = mysqli_query($Con->Conexion,$ConsultarMotivo_2) or die($MensajeErrorMotivo_2);
                //                 $RetMotivo_2 = mysqli_fetch_assoc($RetMotivo_2);
                //                 $Motivo_2 = $RetMotivo_2["motivo"];

                //                 $ID_Motivo_3 = $RetMovimientos["motivo_3"];
                //                 $ConsultarMotivo_3 = "select motivo from motivo where id_motivo = $ID_Motivo_3";
                //                 $RetMotivo_3 = mysqli_query($Con->Conexion,$ConsultarMotivo_3) or die($MensajeErrorMotivo_3);
                //                 $RetMotivo_3 = mysqli_fetch_assoc($RetMotivo_3);
                //                 $Motivo_3 = $RetMotivo_3["motivo"];

                //                 $Observaciones = $RetMovimientos["observaciones"];
                //                 $Responsable = $RetMovimientos["responsable"];
                // //solucionar el error!
                // //  variables inventadas solo para que arme la tabla

                //                 $CentroSalud=$RetMovimientos["nombre"]; //centro_salud
                //                 $OtraInstitucion=$RetMovimientos["nombre"]; //otraInstitucion
                //                 $DtoMovimiento = new DtoMovimiento($ID_Movimiento,$Fecha,$Apellido,$Nombre,$Motivo_1,$Motivo_2,$Motivo_3,$Observaciones,$Responsable,$CentroSalud,$OtraInstitucion);                

                //                 $TableMov = "<table class='table table-dark'>";                
                //                 $TableMov .= "<tr><td style = 'width: 30%;'>Fecha</td><td style = 'width: 70%;'>" . $DtoMovimiento->getFecha() . "</td></tr>";
                //                 $TableMov .= "<tr><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                //                 $TableMov .= "<tr><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                //                 $TableMov .= "<tr><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";
                //                 $TableMov .= "<tr><td style = 'width: 30%;'>Observaciones</td><td style = 'width: 70%;'>" . $DtoMovimiento->getObservaciones() . "</td></tr>";
                //                 $TableMov .= "<tr><td style = 'width: 30%;'>Responsable</td><td style = 'width: 70%;'>" . $DtoMovimiento->getResponsable() . "</td></tr>";
                //                 $TableMov .= "<tr><td style = 'width: 30%;'>Centro de salud</td><td style = 'width: 70%;'>" . $DtoMovimiento->getCentroSalud() . "</td></tr>";
                //                 $TableMov .= "<tr><td style = 'width: 30%;'>Otras instituciones</td><td style = 'width: 70%;'>" . $DtoMovimiento->getOtraInstitucion() . "</td></tr>";
                //                 $TableMov .= "</table>";
                //                 echo $TableMov;

                //               }

                              //$Con->CloseConexion();
              

            } else {
              // En este bloque se ingresa si no se se ha seleccionado una persona determinada en los filtros de personas. 

              //////////////////////////////////////////////////////TABLAS MOVIMIENTOS DE LAS PERSONAS /////////////////////////////            

              // $Con = new Conexion();
              // $Con->OpenConexion();
              $MensajeErrorMovimientos = "No se pudo consultar los movimientos de la persona";

              $TomarMovimientos = mysqli_query($Con->Conexion,$Consulta) or die($MensajeErrorMovimientos);
              $Rows = mysqli_num_rows($TomarMovimientos);

              if($Rows == 0){
                echo "<div class = 'col'></div>";
                echo "<div class = 'col-6'>";
                echo "<p class = 'TextoSinResultados'>No se encontraron Resultados</p><center><button class = 'btn btn-danger' onClick = 'location.href= \"view_movpersonas.php\"'>Atras</button></center>";
                echo "</div>";
                echo "<div class = 'col'></div>";
              }

              while($RetMovimientos = mysqli_fetch_assoc($TomarMovimientos)){
                $RetMovimientos['tipo'] = "CM";
                $tomarRetTodos[] = $RetMovimientos;
              }

              /*foreach ($tomarRetTodos as $clave => $reg) {
                $regdomicilio[$clave] = $reg['domicilio'];                
              }*/

              //array_multisort($regdomicilio, SORT_DESC, $tomarRetTodos);
              $header_movimientos_general = [];
              if($ID_Config == 'table'){
                $MotivosTh = "";
                $header_movimientos_general[] = "Fecha";
                $header_movimientos_general[] = "Persona";
                if($ID_Motivo > 0 || $ID_Motivo2 > 0 || $ID_Motivo3 > 0){
                  $MotivosTh .= "<th rowspan='2' class='trMotivos'>Motivo</th>";
                  $header_movimientos_general[] = "Motivo";
                }else{
                  $MotivosTh .= "<th rowspan='2' class='trMotivos'>Motivo 1</th>";
                  $header_movimientos_general[] = "Motivo 1";
                  $MotivosTh .= "<th rowspan='2' class='trMotivos'>Motivo 2</th>";
                  $header_movimientos_general[] = "Motivo 2";
                  $MotivosTh .= "<th rowspan='2' class='trMotivos'>Motivo 3</th>";
                  $header_movimientos_general[] = "Motivo 3";
                }


                $TableMov = "<table id='tabla-movimiento-general'class='table'>
                              <tr rowspan='2' class='thead-dark'>
                                <th rowspan='2' class='trFecha' style='min-width: 150px;'>Fecha</th>
                                <th rowspan='2' class='trPersona'>Persona</th>";
                $TableMov .= $MotivosTh;

                $TableMov .= "  <th rowspan='2' class='trDNI'>DNI</th>
                                <th rowspan='2' class='trFechaNac'>Fecha Nac.</th>
                                <th colspan='2' class='trEdad' style='text-align: center;' >Edad</th>
                                <th rowspan='2' class='trObraSocial'>Obra Social</th>
                                <th rowspan='2' class='trDomicilio'>Domicilio</th>
                                <th rowspan='2' class='trBarrio'>Barrio</th>
                                <th rowspan='2' class='trLocalidad'>Localidad</th>
                                <th rowspan='2' class='trObservaciones'>Observaciones</th>
                                <th rowspan='2' class='trResponsable'>Responsable</th>
                                <th rowspan='2' class='trCentrosSalud'>Centro de salud</th>
                                <th rowspan='2' class='trOtrasInstituciones'>Otras Instituciones</th>
                              </tr>";
                $header_movimientos_general[] = "DNI";
                $header_movimientos_general[] = "Fecha Nac";
                //$header_movimientos_general[] = "Edad";
                $header_movimientos_general[] = "Años";
                $header_movimientos_general[] = "Meses";
                $header_movimientos_general[] = "obra social";
                $header_movimientos_general[] = "Domicilio";
                $header_movimientos_general[] = "Barrio";
                $header_movimientos_general[] = "Localidad";
                $header_movimientos_general[] = "Observaciones";
                $header_movimientos_general[] = "Responsable";
                $header_movimientos_general[] = "Centro Salud";
                $header_movimientos_general[] = "Otra Institucion";
                $TableMov .= " <tr class='thead-dark'>
                                  <th scope='col' class='trEdad' style='text-align: center;border-top: 0px solid #dee2e6;'>Años</th>
                                  <th scope='col' class='trMeses' style='text-align: center;border-top: 0px solid #dee2e6;'>Meses</th>
                                </tr>";
                $jsonTable["header_movimientos_general"] = $header_movimientos_general;
                $TableMovPrint = $TableMov;
              }
              $json_row = [];
              foreach($tomarRetTodos as $clave => $RetTodos){                
                // echo var_dump($RetTodos);
                // echo "<br>";
                if($RetTodos["fecha_nac"] == 'null'){
                  $Fecha_Nacimiento = "Sin Datos";
                }else{
                  $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$RetTodos["fecha_nac"])));
                }

                if($RetTodos["tipo"] == "SM"){                
                      $Apellido = $RetTodos["apellido"];
                      $Nombre = $RetTodos["nombre"];
                        //solucionar el error!
                        //  variables inventadas solo para que arme la tabla              

                      $TableMov = "<table class='table text-white' style='background-color: #AEB6BF;'>";                
                      $TableMov .= "<tr><td style = 'width: 30%;'>Nombre</td><td style = 'width: 70%;'>" . $Apellido.", " . $Nombre."</td></tr>";
                      $json_row["Persona"] = $Apellido." " . $Nombre;
                      $TableMov .= "<tr><td style = 'width: 30%;'>Estado</td><td style = 'width: 70%;'>SIN MOVIMIENTOS</td></tr>";
                      $json_row["estado"] = "sin movimientos";
                      $TableMov .= "</table>";
                      echo $TableMov;
                }else{
                  $ID_Movimiento = $RetTodos["id_movimiento"];
                  $Fecha = implode("-", array_reverse(explode("-",$RetTodos["fecha"])));
                  $Apellido = $RetTodos["apellido"];
                  $Nombre = $RetTodos["nombre"];

                  $ID_Motivo_1 = $RetTodos["motivo_1"];
                  $ConsultarMotivo_1 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                               M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                               motivo 
                                        from motivo M
                                        where id_motivo = $ID_Motivo_1";

                  // echo "DEBUG: ".var_dump($ConsultarMotivo_1);

                  $MensajeErrorMotivo_1 = "No se pudo consultar el motivo 1";
                  $RetMotivo_1 = mysqli_query($Con->Conexion,$ConsultarMotivo_1) or die($MensajeErrorMotivo_1);
                  $RetMotivo_1 = mysqli_fetch_assoc($RetMotivo_1);

                  if(count(array_filter($MotivosOpciones)) > 0){
                    if(in_array($ID_Motivo_1, array_values($MotivosOpciones))){
                      $Motivo_1 = ($RetMotivo_1["ConPermisoParaUsr"] || $RetMotivo_1["ConPermisoGeneral"])?$RetMotivo_1["motivo"]:"";
                    } else {
                      $Motivo_1 = "";
                    }
                  } else {
                    $Motivo_1 = ($RetMotivo_1["ConPermisoParaUsr"] || $RetMotivo_1["ConPermisoGeneral"])?$RetMotivo_1["motivo"]:"";
                  }

                  $ID_Motivo_2 = $RetTodos["motivo_2"];
                  $ConsultarMotivo_2 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                               M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                               motivo
                                        from motivo M
                                        where id_motivo = $ID_Motivo_2";
                  $RetMotivo_2 = mysqli_query($Con->Conexion,$ConsultarMotivo_2) or die($MensajeErrorMotivo_2);
                  $RetMotivo_2 = mysqli_fetch_assoc($RetMotivo_2);

                  if(count(array_filter($MotivosOpciones)) > 0){
                    if(in_array($ID_Motivo_2, array_values($MotivosOpciones))){
                      $Motivo_2 = ($RetMotivo_2["ConPermisoParaUsr"] || $RetMotivo_2["ConPermisoGeneral"])?$RetMotivo_2["motivo"]:"";
                    } else {
                      $Motivo_2 = "";
                    }
                  } else {
                    $Motivo_2 = ($RetMotivo_2["ConPermisoParaUsr"] || $RetMotivo_2["ConPermisoGeneral"])?$RetMotivo_2["motivo"]:"";
                  }

                  $ID_Motivo_3 = $RetTodos["motivo_3"];
                  $ConsultarMotivo_3 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                               M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                               motivo
                                        from motivo M
                                        where id_motivo = $ID_Motivo_3";
                  $RetMotivo_3 = mysqli_query($Con->Conexion,$ConsultarMotivo_3) or die($MensajeErrorMotivo_3);
                  $RetMotivo_3 = mysqli_fetch_assoc($RetMotivo_3);

                  if(count(array_filter($MotivosOpciones)) > 0){
                    if(in_array($ID_Motivo_3, array_values($MotivosOpciones))){
                      $Motivo_3 = ($RetMotivo_3["ConPermisoParaUsr"] || $RetMotivo_3["ConPermisoGeneral"])?$RetMotivo_3["motivo"]:"";
                    } else {
                      $Motivo_3 = "";
                    }
                  } else {
                    $Motivo_3 = ($RetMotivo_3["ConPermisoParaUsr"] || $RetMotivo_3["ConPermisoGeneral"])?$RetMotivo_3["motivo"]:"";
                  }

                  $ID_Motivo_4 = $RetTodos["motivo_4"];
                  $ConsultarMotivo_4 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                               M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                               motivo
                                        from motivo M
                                        where id_motivo = $ID_Motivo_4";
                  $RetMotivo_4 = mysqli_query($Con->Conexion,$ConsultarMotivo_4) or die($MensajeErrorMotivo_4);
                  $RetMotivo_4 = mysqli_fetch_assoc($RetMotivo_4);

                  if(count(array_filter($MotivosOpciones)) > 0){
                    if(in_array($ID_Motivo_4, array_values($MotivosOpciones))){
                      $Motivo_4 = ($RetMotivo_4["ConPermisoParaUsr"] || $RetMotivo_4["ConPermisoGeneral"])?$RetMotivo_4["motivo"]:"";
                    } else {
                      $Motivo_4 = "";
                    }
                  } else {
                    $Motivo_4 = ($RetMotivo_4["ConPermisoParaUsr"] || $RetMotivo_4["ConPermisoGeneral"])?$RetMotivo_4["motivo"]:"";
                  }
  
                  $ID_Motivo_5 = $RetTodos["motivo_5"];
                  $ConsultarMotivo_5 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                               M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                               motivo
                                        from motivo M
                                        where id_motivo = $ID_Motivo_5";
                  $RetMotivo_5 = mysqli_query($Con->Conexion,$ConsultarMotivo_5) or die($MensajeErrorMotivo_3);
                  $RetMotivo_5 = mysqli_fetch_assoc($RetMotivo_5);
                  if(count(array_filter($MotivosOpciones)) > 0){
                    if(in_array($ID_Motivo_5, array_values($MotivosOpciones))){
                      $Motivo_5 = ($RetMotivo_5["ConPermisoParaUsr"] || $RetMotivo_5["ConPermisoGeneral"])?$RetMotivo_5["motivo"]:"";
                    } else {
                      $Motivo_5 = "";
                    }
                  } else {
                    $Motivo_5 = ($RetMotivo_5["ConPermisoParaUsr"] || $RetMotivo_5["ConPermisoGeneral"])?$RetMotivo_5["motivo"]:"";
                  }

                  $movimientoVisible = false;
                  $movimientoVisible = $movimientoVisible || ($Motivo_1 != "");
                  $movimientoVisible = $movimientoVisible || ($Motivo_2 != "");
                  $movimientoVisible = $movimientoVisible || ($Motivo_3 != "");
                  $movimientoVisible = $movimientoVisible || ($Motivo_4 != "");
                  $movimientoVisible = $movimientoVisible || ($Motivo_5 != "");
  
                  if (!$movimientoVisible){
                    continue;
                  }

                  $Observaciones = $RetTodos["observaciones"];
                  $Responsable = $RetTodos["responsable"];
                    //solucionar el error!
                    //  variables inventadas solo para que arme la tabla

                  $CentroSalud = $RetTodos["centro_salud"]; //centro_salud
                  $OtraInstitucion = $RetTodos["NombreInst"]; //otraInstitucion
                  $DtoMovimiento = new DtoMovimiento($ID_Movimiento,$Fecha,$Apellido,$Nombre,$Motivo_1,$Motivo_2,$Motivo_3,$Motivo_4,$Motivo_5,$Observaciones,$Responsable,$CentroSalud,$OtraInstitucion);   

                  /////////////////////////////////////////////////////////////
                  $DNI = $RetTodos["documento"];
                  $Edad = $RetTodos["edad"];
                  $Meses = $RetTodos["meses"];
                  $Obra_Social = $RetTodos["obra_social"];
                  $Domicilio = $RetTodos["domicilio"];
                  $Barrio = $RetTodos["Barrio"];
                  $Localidad = $RetTodos["localidad"];
                  /////////////////////////////////////////////////////////////

                  if($ID_Config == 'grid'){
                    $TableMov = "<table class='table table-dark'>";                
                    $TableMov .= "<tr class='trFecha'><td style = 'width: 30%;'>Fecha</td><td style = 'width: 70%;'>" . $DtoMovimiento->getFecha() . "</td></tr>";
                    $json_row["fechas"] = $DtoMovimiento->getFecha();
                    $TableMovPrint = $TableMov;
                    $TableMov .= "<tr>
                                    <td style = 'width: 30%;'>
                                      Persona
                                    </td>
                                    <td style = 'width: 70%;'>
                                      <a href = 'javascript:window.open(\"view_modpersonas.php?ID=" . $RetTodos["id_persona"]."\",\"Ventana" . $RetTodos["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".
                                        $DtoMovimiento->getApellido() . ", " . $DtoMovimiento->getNombre() . "
                                      </a>
                                    </td>
                                  </tr>";
                    $TableMovPrint .= "<tr>
                                    <td style = 'width: 30%;'>
                                      Persona
                                    </td>
                                    <td style = 'width: 70%;'>".
                                        $DtoMovimiento->getApellido() . ", " . $DtoMovimiento->getNombre() . "
                                    </td>
                                  </tr>";
                    $json_row["Persona"] = $DtoMovimiento->getApellido() . " " . $DtoMovimiento->getNombre();
                    if($ID_Motivo > 0){    
                      switch ($ID_Motivo) {
                        case $ID_Motivo_1: 
                            $TableMov .= "<tr class='trMotivos'>
                                            <td style = 'width: 30%;'>
                                              Motivo 1
                                            </td>
                                          <td style = 'width: 70%;'>".
                                            $DtoMovimiento->getMotivo_1() . "
                                          </td>
                                        </tr>";
                            $TableMovPrint .= "<tr class='trMotivos'>
                                        <td style = 'width: 30%;'>
                                          Motivo 1
                                        </td>
                                      <td style = 'width: 70%;'>".
                                        $DtoMovimiento->getMotivo_1() . "
                                      </td>
                                    </tr>";
                             $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                            break;
                        case $ID_Motivo_2:
                            $TableMov .= "<tr class='trMotivos'>
                                            <td style = 'width: 30%;'>
                                              Motivo 2
                                            </td>
                                            <td style = 'width: 70%;'>".
                                              $DtoMovimiento->getMotivo_2() . "
                                            </td>
                                          </tr>";
                            $TableMovPrint .= "<tr class='trMotivos'>
                                            <td style = 'width: 30%;'>
                                              Motivo 2
                                            </td>
                                            <td style = 'width: 70%;'>".
                                              $DtoMovimiento->getMotivo_2() . "
                                            </td>
                                          </tr>";
                             $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                            break;
                        case $ID_Motivo_3: 
                            $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";
                            $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";
                             $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                            break;
                        
                        default: 
                            $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>"; 
                            $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>"; 
                             $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                            break;
                      }                  
  
                    }elseif($ID_Motivo2 > 0){

                      switch ($ID_Motivo2) {
                        case $ID_Motivo_1: 
                            $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                            $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                             $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                            break;
                        case $ID_Motivo_2:
                            $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                            $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                             $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                            break;
                        case $ID_Motivo_3:
                            $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";
                            $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                             $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                            break;
                        
                        default: 
                            $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                            $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                             $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                            break;
                      }

                    }elseif($ID_Motivo3 > 0){

                      switch ($ID_Motivo3) {
                        case $ID_Motivo_1:
                            $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                            $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                             $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                            break;
                        case $ID_Motivo_2: 
                            $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                            $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                             $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                            break;
                        case $ID_Motivo_3:
                            $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";
                            $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";
                             $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                            break;
                        
                        default:
                            $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                            $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                             $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                            break;
                      }

                    } else {
                      $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                      $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                       $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                      $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                      $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                       $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                      $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";
                      $TableMovPrint .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";
                       $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                    }
                    $TableMov .= "<tr class='trObservaciones'><td style = 'width: 30%;'>Observaciones</td><td style = 'width: 70%;'>" . $DtoMovimiento->getObservaciones() . "</td></tr>";
                    $TableMovPrint .= "<tr class='trObservaciones'><td style = 'width: 30%;'>Observaciones</td><td style = 'width: 70%;'>" . $DtoMovimiento->getObservaciones() . "</td></tr>";
                     $json_row["Observaciones"] = $DtoMovimiento->getObservaciones();
                    $TableMov .= "<tr class='trResponsable'><td style = 'width: 30%;'>Responsable</td><td style = 'width: 70%;'>" . $DtoMovimiento->getResponsable() . "</td></tr>";
                    $TableMovPrint .= "<tr class='trResponsable'><td style = 'width: 30%;'>Responsable</td><td style = 'width: 70%;'>" . $DtoMovimiento->getResponsable() . "</td></tr>";
                     $json_row["Responsable"] = $DtoMovimiento->getResponsable();
                    $TableMov .= "<tr class='trCentrosSalud'><td style = 'width: 30%;'>Centro de salud</td><td style = 'width: 70%;'>" . $DtoMovimiento->getCentroSalud() . "</td></tr>";
                    $TableMovPrint .= "<tr class='trCentrosSalud'><td style = 'width: 30%;'>Centro de salud</td><td style = 'width: 70%;'>" . $DtoMovimiento->getCentroSalud() . "</td></tr>";
                     $json_row["Centro Salud"] = $DtoMovimiento->getCentroSalud();
                    $TableMov .= "<tr class='trOtrasInstituciones'><td style = 'width: 30%;'>Otras instituciones</td><td style = 'width: 70%;'>" . $DtoMovimiento->getOtraInstitucion() . "</td></tr>";
                    $TableMovPrint .= "<tr class='trOtrasInstituciones'><td style = 'width: 30%;'>Otras instituciones</td><td style = 'width: 70%;'>" . $DtoMovimiento->getOtraInstitucion() . "</td></tr>";
                     $json_row["Otra Institucion"] = $DtoMovimiento->getOtraInstitucion();
                    $TableMov .= "</table>";
                    $TableMovPrint .= "</table>";
                    echo $TableMov;
                  } else {
                    $TableMov .= "<tr>";
                    $TableMovPrint .= "<tr>";
                    $TableMov .= "<td class='trFecha' style = 'width: auto;'>" . $DtoMovimiento->getFecha() . "</td>";
                    $TableMovPrint .= "<td class='trFecha' style = 'width: auto;'>" . $DtoMovimiento->getFecha() . "</td>";
                    $json_row["Fecha"] = $DtoMovimiento->getFecha();
                    $TableMov .= "<td class='trPersona' style = 'width: auto;'><a href = 'javascript:window.open(\"view_modpersonas.php?ID=" . $RetTodos["id_persona"]."\",\"Ventana" . $RetTodos["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>" . $DtoMovimiento->getApellido() . ", " . $DtoMovimiento->getNombre() . "</a></td>";
                    $TableMovPrint .= "<td class='trPersona' style = 'width: auto;'>".
                                          $DtoMovimiento->getApellido() . ", " . $DtoMovimiento->getNombre() . "
                                      </td>";
                    $json_row["Persona"] = $DtoMovimiento->getApellido() . " " . $DtoMovimiento->getNombre();

                    if($ID_Motivo > 0){    
                      switch ($ID_Motivo) {
                        case $ID_Motivo_1:
                            $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                            $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                             $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                            break;
                        case $ID_Motivo_2:
                            $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_2() . "</td>";
                            $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_2() . "</td>";
                             $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                            break;
                        case $ID_Motivo_3:
                            $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_3() . "</td>";
                            $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_3() . "</td>";
                             $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                            break;
                        
                        default:
                            $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                            $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                             $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                            break;
                      }                  
  
                    }elseif($ID_Motivo2 > 0){

                      switch ($ID_Motivo2) {
                        case $ID_Motivo_1:
                            $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                            $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                             $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                            break;
                        case $ID_Motivo_2:
                            $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_2() . "</td>";
                            $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_2() . "</td>";
                             $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                            break;
                        case $ID_Motivo_3:
                            $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_3() . "</td>";
                            $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_3() . "</td>";
                             $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                            break;
                        
                        default:
                            $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                            $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                             $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                            break;
                      }

                    }elseif($ID_Motivo3 > 0){

                      switch ($ID_Motivo3) {
                        case $ID_Motivo_1:
                            $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                            $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                             $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                            break;
                        case $ID_Motivo_2:
                            $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_2() . "</td>";
                            $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_2() . "</td>";
                             $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                            break;
                        case $ID_Motivo_3:
                            $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_3() . "</td>";
                            $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_3() . "</td>";
                             $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                            break;
                        
                        default:
                            $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                            $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                             $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                            break;
                      }

                    }else{
                      $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                      $TableMovPrint .=  "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td>";
                       $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                      $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_2() . "</td>";
                      $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_2() . "</td>";
                       $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                      $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_3() . "</td>";  
                      $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_3() . "</td>";  
                       $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                    }
                                      
                    $TableMov .= "<td class='trDNI' style = 'width: auto;'>" . $DNI."</td>";
                    $TableMovPrint .= "<td class='trDNI' style = 'width: auto;'>" . $DNI."</td>";
                    $json_row["DNI"] = $DNI;
                    $TableMov .= "<td class='trFechaNac' style = 'width: auto;'>" . $Fecha_Nacimiento."</td>";
                    $TableMovPrint .= "<td class='trFechaNac' style = 'width: auto;'>" . $Fecha_Nacimiento."</td>";
                    $json_row["Fecha Nac"] = $Fecha_Nacimiento;
                    $TableMov .= "<td class='trEdad' style = 'width: auto;'>" . $Edad."</td>";
                    $TableMovPrint .= "<td class='trEdad' style = 'width: auto;'>" . $Edad."</td>";
                    $json_row["Años"] = $Edad;
                    $TableMov .= "<td class='trMeses' style = 'width: auto;'>" . $Meses."</td>";
                    $TableMovPrint .= "<td class='trMeses' style = 'width: auto;'>" . $Meses."</td>";
                    $json_row["Meses"] = $Meses;
                    $TableMov .= "<td class='trObraSocial' style = 'width: auto;'>" . $Obra_Social."</td>";
                    $TableMovPrint .= "<td class='trObraSocial' style = 'width: auto;'>" . $Obra_Social."</td>";
                    $json_row["obra social"] = $Obra_Social;
                    $TableMov .= "<td class='trDomicilio' style = 'width: auto;'>" . $Domicilio."</td>";
                    $TableMovPrint .= "<td class='trDomicilio' style = 'width: auto;'>" . $Domicilio."</td>";
                    $json_row["Domicilio"] = $Domicilio;
                    $TableMov .= "<td class='trBarrio' style = 'width: auto;'>" . $Barrio."</td>";
                    $TableMovPrint .= "<td class='trBarrio' style = 'width: auto;'>" . $Barrio."</td>";
                    $json_row["Barrio"] = $Barrio;
                    $TableMov .= "<td class='trLocalidad' style = 'width: auto;'>" . $Localidad."</td>";
                    $TableMovPrint .= "<td class='trLocalidad' style = 'width: auto;'>" . $Localidad."</td>";
                    $json_row["Localidad"] = $Localidad;
                    $TableMov .= "<td class='trObservaciones' style = 'width: auto;'>" . $DtoMovimiento->getObservaciones() . "</td>";
                    $TableMovPrint .= "<td class='trObservaciones' style = 'width: auto;'>" . $DtoMovimiento->getObservaciones() . "</td>";
                    $json_row["Observaciones"] = $DtoMovimiento->getObservaciones();
                    $TableMov .= "<td class='trResponsable' style = 'width: auto;'>" . $DtoMovimiento->getResponsable() . "</td>";
                    $TableMovPrint .= "<td class='trResponsable' style = 'width: auto;'>" . $DtoMovimiento->getResponsable() . "</td>";
                    $json_row["Responsable"] = $DtoMovimiento->getResponsable();
                    $TableMov .= "<td class='trCentrosSalud' style = 'width: auto;'>" . $DtoMovimiento->getCentroSalud() . "</td>";
                    $TableMovPrint .= "<td class='trCentrosSalud' style = 'width: auto;'>" . $DtoMovimiento->getCentroSalud() . "</td>";
                    $json_row["Centro Salud"] = $DtoMovimiento->getCentroSalud();
                    $TableMov .= "<td class='trOtrasInstituciones' style = 'width: auto;'>" . $DtoMovimiento->getOtraInstitucion() . "</td>";
                    $TableMovPrint .= "<td class='trOtrasInstituciones' style = 'width: auto;'>" . $DtoMovimiento->getOtraInstitucion() . "</td>";
                    $json_row["Otra Institucion"] = $DtoMovimiento->getOtraInstitucion();
                    $TableMov .= "</tr>";
                    $TableMovPrint .= "</tr>";
                  }
                }
                $jsonTable["movimientos_general"][] = $json_row;
              }
              

              while ($RetMovimientos = mysqli_fetch_assoc($TomarMovimientos)) {
                $ID_Movimiento = $RetMovimientos["id_movimiento"];
                $Fecha = implode("-", array_reverse(explode("-",$RetMovimientos["fecha"])));
                $Apellido = $RetMovimientos["apellido"];
                $Nombre = $RetMovimientos["nombre"];

                $ID_Motivo_1 = $RetMovimientos["motivo_1"];
                $ConsultarMotivo_1 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                               M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                               motivo 
                                        from motivo M
                                        where id_motivo = $ID_Motivo_1";

                // echo "DEBUG: ".var_dump($ConsultarMotivo_1);

                $MensajeErrorMotivo_1 = "No se pudo consultar el motivo 1";
                $RetMotivo_1 = mysqli_query($Con->Conexion,$ConsultarMotivo_1) or die($MensajeErrorMotivo_1);
                $RetMotivo_1 = mysqli_fetch_assoc($RetMotivo_1);
                if(count(array_filter($MotivosOpciones)) > 0){
                  if(in_array($ID_Motivo_1, array_values($MotivosOpciones))){
                    $Motivo_1 = ($RetMotivo_1["ConPermisoParaUsr"] || $RetMotivo_1["ConPermisoGeneral"])?$RetMotivo_1["motivo"]:"";
                  } else {
                    $Motivo_1 = "";
                  }
                } else {
                  $Motivo_1 = ($RetMotivo_1["ConPermisoParaUsr"] || $RetMotivo_1["ConPermisoGeneral"])?$RetMotivo_1["motivo"]:"";
                }

                $ID_Motivo_2 = $RetMovimientos["motivo_2"];
                $ConsultarMotivo_2 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                               M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                               motivo 
                                        from motivo M
                                        where id_motivo = $ID_Motivo_2";
                $RetMotivo_2 = mysqli_query($Con->Conexion,$ConsultarMotivo_2) or die($MensajeErrorMotivo_2);
                $RetMotivo_2 = mysqli_fetch_assoc($RetMotivo_2);
                if(count(array_filter($MotivosOpciones)) > 0){
                  if(in_array($ID_Motivo_2, array_values($MotivosOpciones))){
                    $Motivo_2 = ($RetMotivo_2["ConPermisoParaUsr"] || $RetMotivo_2["ConPermisoGeneral"])?$RetMotivo_2["motivo"]:"";
                  } else {
                    $Motivo_2 = "";
                  }
                } else {
                  $Motivo_2 = ($RetMotivo_2["ConPermisoParaUsr"] || $RetMotivo_2["ConPermisoGeneral"])?$RetMotivo_2["motivo"]:"";
                }

                $ID_Motivo_3 = $RetMovimientos["motivo_3"];
                $ConsultarMotivo_3 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                               M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                               motivo 
                                        from motivo M
                                        where id_motivo = $ID_Motivo_3";
                $RetMotivo_3 = mysqli_query($Con->Conexion,$ConsultarMotivo_3) or die($MensajeErrorMotivo_3);
                $RetMotivo_3 = mysqli_fetch_assoc($RetMotivo_3);
                if(count(array_filter($MotivosOpciones)) > 0){
                  if(in_array($ID_Motivo_3, array_values($MotivosOpciones))){
                    $Motivo_3 = ($RetMotivo_3["ConPermisoParaUsr"] || $RetMotivo_3["ConPermisoGeneral"])?$RetMotivo_3["motivo"]:"";
                  } else {
                    $Motivo_3 = "";
                  }
                } else {
                  $Motivo_3 = ($RetMotivo_3["ConPermisoParaUsr"] || $RetMotivo_3["ConPermisoGeneral"])?$RetMotivo_3["motivo"]:"";
                }

                $ID_Motivo_4 = $RetMovimientos["motivo_4"];
                $ConsultarMotivo_4 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                               M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                               motivo 
                                        from motivo M
                                        where id_motivo = $ID_Motivo_4";
                $RetMotivo_4 = mysqli_query($Con->Conexion,$ConsultarMotivo_4) or die($MensajeErrorMotivo_4);
                $RetMotivo_4 = mysqli_fetch_assoc($RetMotivo_4);
                if(count(array_filter($MotivosOpciones)) > 0){
                  if(in_array($ID_Motivo_4, array_values($MotivosOpciones))){
                    $Motivo_4 = ($RetMotivo_4["ConPermisoParaUsr"] || $RetMotivo_4["ConPermisoGeneral"])?$RetMotivo_4["motivo"]:"";
                  } else {
                    $Motivo_4 = "";
                  }
                } else {
                  $Motivo_4 = ($RetMotivo_4["ConPermisoParaUsr"] || $RetMotivo_4["ConPermisoGeneral"])?$RetMotivo_4["motivo"]:"";
                }

                $ID_Motivo_5 = $RetMovimientos["motivo_5"];
                $ConsultarMotivo_5 = "select M.id_motivo IN (SELECT id_motivo 
                                                                      FROM INN) as ConPermisoParaUsr,
                                               M.id_motivo IN (SELECT id_motivo
                                                               FROM GIN) as ConPermisoGeneral,
                                               motivo 
                                        from motivo M
                                        where id_motivo = $ID_Motivo_5";
                $RetMotivo_5 = mysqli_query($Con->Conexion,$ConsultarMotivo_5) or die($MensajeErrorMotivo_5);
                $RetMotivo_5 = mysqli_fetch_assoc($RetMotivo_5);
                if(count(array_filter($MotivosOpciones)) > 0){
                  if(in_array($ID_Motivo_5, array_values($MotivosOpciones))){
                    $Motivo_5 = ($RetMotivo_5["ConPermisoParaUsr"] || $RetMotivo_5["ConPermisoGeneral"])?$RetMotivo_5["motivo"]:"";
                  } else {
                    $Motivo_5 = "";
                  }
                } else {
                  $Motivo_5 = ($RetMotivo_5["ConPermisoParaUsr"] || $RetMotivo_5["ConPermisoGeneral"])?$RetMotivo_5["motivo"]:"";
                }

                $movimientoVisible = false;
                $movimientoVisible = $movimientoVisible || ($Motivo_1 != "");
                $movimientoVisible = $movimientoVisible || ($Motivo_2 != "");
                $movimientoVisible = $movimientoVisible || ($Motivo_3 != "");
                $movimientoVisible = $movimientoVisible || ($Motivo_4 != "");
                $movimientoVisible = $movimientoVisible || ($Motivo_5 != "");

                if (!$movimientoVisible){
                  continue;
                }

                $Observaciones = $RetMovimientos["observaciones"];
                $Responsable = $RetMovimientos["responsable"];
                //solucionar el error!
                //  variables inventadas solo para que arme la tabla
                
                $CentroSalud=$RetMovimientos["nombre"]; //centro_salud
                $OtraInstitucion=$RetMovimientos["nombre"];
                $DtoMovimiento = new DtoMovimiento($ID_Movimiento,$Fecha,$Apellido,$Nombre,$Motivo_1,$Motivo_2,$Motivo_3,$Motivo_4,$Motivo_5,$Observaciones,$Responsable,$CentroSalud,$OtraInstitucion);

                /////////////////////////////////////////////////////////////
                $DNI = $RetMovimientos["documento"];
                $Edad = $RetMovimientos["edad"];
                $Meses = $RetMovimientos["meses"];
                $Obra_Social = $RetMovimientos["obra_social"];
                $Domicilio = $RetMovimientos["domicilio"];
                $Barrio = $RetMovimientos["Barrio"];
                $Localidad = $RetMovimientos["localidad"];
                /////////////////////////////////////////////////////////////

                if($ID_Config == 'grid'){
                  $TableMov = "<table class='table table-dark'>";
                  $TableMov .= "<tr class='trFecha'><td style = 'width: 30%;'>Fecha</td><td style = 'width: 70%;'>" . $DtoMovimiento->getFecha() . "</td></tr>";
                  $json_row["Fecha"] = $DtoMovimiento->getFecha();
                  $TableMov .= "<tr><td style = 'width: 30%;'>Persona</td><td style = 'width: 70%;'>" . $DtoMovimiento->getApellido() . ", " . $DtoMovimiento->getNombre() . "</td></tr>";
                  $json_row["Persona"] = $DtoMovimiento->getApellido() . " " . $DtoMovimiento->getNombre();
                  $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                  $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                  $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                  $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                  $TableMov .= "<tr class='trMotivos'><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";
                  $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                  $TableMov .= "<tr class='trObservaciones'><td style = 'width: 30%;'>Observaciones</td><td style = 'width: 70%;'>" . $DtoMovimiento->getObservaciones() . "</td></tr>";
                  $json_row["Observaciones"] = $DtoMovimiento->getObservaciones();
                  $TableMov .= "<tr class='trResponsable'><td style = 'width: 30%;'>Responsable</td><td style = 'width: 70%;'>" . $DtoMovimiento->getResponsable() . "</td></tr>";
                  $json_row["Responsable"] = $DtoMovimiento->getResponsable();
                  $TableMov .= "<tr class='trCentrosSalud'><td style = 'width: 30%;'>Centro de salud</td><td style = 'width: 70%;'>" . $DtoMovimiento->getCentroSalud() . "</td></tr>";
                  $json_row["Centro Salud"] = $DtoMovimiento->getCentroSalud();
                  $TableMov .= "<tr class='trOtrasInstituciones'><td style = 'width: 30%;'>Otras instituciones</td><td style = 'width: 70%;'>" . $DtoMovimiento->getOtraInstitucion() . "</td></tr>";
                  $json_row["Otra Institucion"] = $DtoMovimiento->getOtraInstitucion();
                  $TableMov .= "</table>";

                  $jsonTable["movimientos_general_grid"][] = $json_row;
                  $classAReemplazar[0] = "~class='table table-dark'~";
                  $classAReemplazar[] = "~<class='trFecha'~";
                  $classAReemplazar[] = "~class='trMotivos'~";
                  $classAReemplazar[] = "~class='trOtrasInstituciones'~";
                  $classAReemplazar[] = "~class='trCentrosSalud'~";
                  $classAReemplazar[] = "~class='trResponsable'~";
                  $classAReemplazar[] = "~class='trObservaciones'~";
                  $TableMovPrint .= preg_replace( $tdReemplazar, "", $TableMov);
                  echo $TableMov;
                }else{
                  $TableMov .= "<td class='trFecha' style = 'width: auto;'>" . $DtoMovimiento->getFecha() . "</td></tr>";
                  $TableMovPrint .= "<td class='trFecha' style = 'width: auto;'>" . $DtoMovimiento->getFecha() . "</td></tr>";
                  $json_row["Fecha"] = $DtoMovimiento->getFecha();
                  $TableMov .= "<td class='trPersona' style = 'width: auto;'><a href = 'javascript:window.open(\"view_modpersonas.php?ID=" . $RetTodos["id_persona"]."\",\"Ventana" . $RetTodos["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>" . $DtoMovimiento->getApellido() . ", " . $DtoMovimiento->getNombre() . "</a></td></tr>";
                  $TableMovPrint .= "<td class='trPersona' style = 'width: auto;'>".
                                      $DtoMovimiento->getApellido() . ", " . $DtoMovimiento->getNombre() . "
                                    </td>
                                  </tr>";
                  $json_row["Persona"] = $DtoMovimiento->getApellido() . " " . $DtoMovimiento->getNombre();
                  $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                  $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_1() . "</td></tr>";
                  $json_row["Motivo 1"] = $DtoMovimiento->getMotivo_1();
                  $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                  $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_2() . "</td></tr>";
                  $json_row["Motivo 2"] = $DtoMovimiento->getMotivo_2();
                  $TableMov .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";
                  $TableMovPrint .= "<td class='trMotivos' style = 'width: auto;'>" . $DtoMovimiento->getMotivo_3() . "</td></tr>";
                  $json_row["Motivo 3"] = $DtoMovimiento->getMotivo_3();
                  $TableMov .= "<td class='trDNI' style = 'width: auto;'>" . $DNI."</td>";
                  $TableMovPrint .= "<td class='trDNI' style = 'width: auto;'>" . $DNI."</td>";
                  $json_row["DNI"] = $DNI;
                  $TableMov .= "<td class='trFechaNac' style = 'width: auto;'>" . $Fecha_Nacimiento."</td>";
                  $TableMovPrint .= "<td class='trFechaNac' style = 'width: auto;'>" . $Fecha_Nacimiento."</td>";
                  $json_row["Fecha Nac"] = $Fecha_Nacimiento;
                  $TableMov .= "<td class='trEdad' style = 'width: auto;'>" . $Edad."</td>";
                  $TableMovPrint .= "<td class='trEdad' style = 'width: auto;'>" . $Edad."</td>";
                  $json_row["Años"] = $Edad;
                  $TableMov .= "<td class='trMeses' style = 'width: auto;'>" . $Meses."</td>";
                  $TableMovPrint .= "<td class='trMeses' style = 'width: auto;'>" . $Meses."</td>";
                  $json_row["Meses"] = $Meses;
                  $TableMov .= "<td class='trObraSocial' style = 'width: auto;'>" . $Obra_Social."</td>";
                  $TableMovPrint .= "<td class='trObraSocial' style = 'width: auto;'>" . $Obra_Social."</td>";
                  $json_row["Obra Social"] = $Obra_Social;
                  $TableMov .= "<td class='trDomicilio' style = 'width: auto;'>" . $Domicilio."</td>";
                  $TableMovPrint .= "<td class='trDomicilio' style = 'width: auto;'>" . $Domicilio."</td>";
                  $json_row["Domicilio"] = $Domicilio;
                  $TableMov .= "<td class='trBarrio' style = 'width: auto;'>" . $Barrio."</td>";
                  $TableMovPrint .= "<td class='trBarrio' style = 'width: auto;'>" . $Barrio."</td>";
                  $json_row["Barrio"] = $Barrio;
                  $TableMov .= "<td class='trLocalidad' style = 'width: auto;'>" . $Localidad."</td>";
                  $TableMovPrint .= "<td class='trLocalidad' style = 'width: auto;'>" . $Localidad."</td>";
                  $json_row["Localidad"] = $Localidad;
                  $TableMov .= "<td class='trObservaciones' style = 'width: auto;'>" . $DtoMovimiento->getObservaciones() . "</td></tr>";
                  $TableMovPrint .= "<td class='trObservaciones' style = 'width: auto;'>" . $DtoMovimiento->getObservaciones() . "</td></tr>";
                  $json_row["observacion"] = $DtoMovimiento->getObservaciones();
                  $TableMov .= "<td class='trResponsable' style = 'width: auto;'>" . $DtoMovimiento->getResponsable() . "</td></tr>";
                  $TableMovPrint .= "<td class='trResponsable' style = 'width: auto;'>" . $DtoMovimiento->getResponsable() . "</td></tr>";
                  $json_row["Responsable"] = $DtoMovimiento->getResponsable();
                  $TableMov .= "<td class='trCentrosSalud' style = 'width: auto;'>" . $DtoMovimiento->getCentroSalud() . "</td></tr>";
                  $TableMovPrint .= "<td class='trCentrosSalud' style = 'width: auto;'>" . $DtoMovimiento->getCentroSalud() . "</td></tr>";
                  $json_row["Centro Salud"] = $DtoMovimiento->getCentroSalud();
                  $TableMov .= "<td class='trOtrasInstituciones' style = 'width: auto;'>" . $DtoMovimiento->getOtraInstitucion() . "</td></tr>";
                  $TableMovPrint .= "<td class='trOtrasInstituciones' style = 'width: auto;'>" . $DtoMovimiento->getOtraInstitucion() . "</td></tr>";
                  $json_row["otra_institucional"] = $DtoMovimiento->getOtraInstitucion();
                  $jsonTable["movimientos_general"][] = $json_row;
                }
              }

              if($ID_Config == 'table'){
                $TableMov .= "</table>";

                $classAReemplazar[0] = "~class='table'~";
                $classAReemplazar[] = "~class='table table-dark'~";
                $classAReemplazar[] = "~class='trMeses'~";
                $classAReemplazar[] = "~class='trEdad'~";
                $classAReemplazar[] = "~class='trFechaNac'~";
                $classAReemplazar[] = "~class='trDNI'~";
                $classAReemplazar[] = "~class='trPersona'~";
                $classAReemplazar[] = "~<class='trFecha'~";
                $classAReemplazar[] = "~class='trMotivos'~";
                $classAReemplazar[] = "~class='trOtrasInstituciones'~";
                $classAReemplazar[] = "~class='trCentrosSalud'~";
                $classAReemplazar[] = "~class='trResponsable'~";
                $classAReemplazar[] = "~class='trLocalidad'~";
                $classAReemplazar[] = "~class='trBarrio'~";
                $classAReemplazar[] = "~class='trDomicilio'~";
                $classAReemplazar[] = "~class='trObraSocial'~";
                $TableMovPrint .= preg_replace( $classAReemplazar, "", $TableMovPrint);

                echo $TableMov;
              }

              if($ID_Motivo > 0){
                $ConsultarFiltroMotivo = "select id_motivo, motivo from motivo where id_motivo = $ID_Motivo";
                $ErrorConsultarFiltroMotivo = "No se pudo consultar el motivo del filtro ID_Motivo";
                $RetConsFiltroMotivo = mysqli_query($Con->Conexion,$ConsultarFiltroMotivo) or die($ErrorConsultarFiltroMotivo);
                $RetConsMotivo = mysqli_fetch_assoc($RetConsFiltroMotivo);
                $filtrosSeleccionados["ID_Motivo"] = $RetConsMotivo["id_motivo"];  
                $filtrosSeleccionados["Motivo"] = $RetConsMotivo["motivo"];  
                $_SESSION["datosNav"]["ID_Motivo"] = $filtrosSeleccionados["ID_Motivo"];
                $_SESSION["datosNav"]["Motivo"] = $filtrosSeleccionados["Motivo"];
              }

              if($ID_Motivo2 > 0){                
                $ConsultarFiltroMotivo2 = "select id_motivo, motivo from motivo where id_motivo = $ID_Motivo2";
                $ErrorConsultarFiltroMotivo2 = "No se pudo consultar el motivo del filtro ID_Motivo2";
                $RetConsFiltroMotivo2 = mysqli_query($Con->Conexion,$ConsultarFiltroMotivo2) or die($ErrorConsultarFiltroMotivo2);
                $RetConsMotivo2 = mysqli_fetch_assoc($RetConsFiltroMotivo2);
                $filtrosSeleccionados["ID_Motivo2"] = $RetConsMotivo2["id_motivo"];  
                $filtrosSeleccionados["Motivo2"] = $RetConsMotivo2["motivo"];  
                $_SESSION["datosNav"]["ID_Motivo2"] = $filtrosSeleccionados["ID_Motivo2"];
                $_SESSION["datosNav"]["Motivo2"] = $filtrosSeleccionados["Motivo2"];
              }

              if($ID_Motivo3 > 0){                
                $ConsultarFiltroMotivo3 = "select id_motivo, motivo from motivo where id_motivo = $ID_Motivo3";
                $ErrorConsultarFiltroMotivo3 = "No se pudo consultar el motivo del filtro ID_Motivo3";
                $RetConsFiltroMotivo3 = mysqli_query($Con->Conexion,$ConsultarFiltroMotivo3) or die($ErrorConsultarFiltroMotivo3);
                $RetConsMotivo3 = mysqli_fetch_assoc($RetConsFiltroMotivo3);
                $filtrosSeleccionados["ID_Motivo3"] = $RetConsMotivo3["id_motivo"];  
                $filtrosSeleccionados["Motivo3"] = $RetConsMotivo3["motivo"];  
                $_SESSION["datosNav"]["ID_Motivo3"] = $filtrosSeleccionados["ID_Motivo3"];
                $_SESSION["datosNav"]["Motivo3"] = $filtrosSeleccionados["Motivo3"];
              }

              $Con->CloseConexion();
              // $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID de la Persona";
              // echo $Mensaje;
            }

            // ACA PONER
            
            //$_SESSION["datosNav"] = $filtrosSeleccionados;

            //unset($_SESSION["datosNav"]);
          ?>
        </div>
        <?php
        $htmlPrint = "<html>
                        <head>
                        <link href='https://fonts.cdnfonts.com/css/symbol' rel='stylesheet'>
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    
                          <style>
                            @page {
                              margin: 15px !important;
                              padding: 15px !important;
                            }
                            .table{
                              border-collapse: collapse;
                            }
                            .thead-dark{
                              background-color: #ccc;
                              font-size: 12px;
                            }
                            .table_pdf {
                              width: 100%;
                            }
                            tr td {
                              text-align: center;
                              font-size: 12px;
                            }

                            table thead tr th {
                              background-color: #ccc;
                            }

                            h5, h2{
                              text-align: center;
                              margin-bottom: 0px
                            }

                            #InformacionDeCentro {
                              float: right; 
                              text-align: left;
                            }

                            #frase {
                              font-weight: bold;
                            }

                            #encabezado {
                              text-align: center;
                              float: right;
                              padding-right: 13rem;
                            }

                            #InformacionDeCiudad {
                              text-align: left;
                              margin-bottom: 2rem;
                              margin-top: 2rem;
                            }

                            table, th, td {
                              border: 1px solid;
                            }
                          </style>
                          </head> 
                          <body>
                            <p id='InformacionDeCentro'> Centro de Atencion Primaria en Salud<br>
                              Direccion de Accion Social<br>
                              DESDE : ". $Etiqueta_Fecha_Inicio . " HASTA : " . $Etiqueta_Fecha_Fin ." </p>
                            <p id='encabezado'> Plan para el fortalecimiento del bienestar Comunitario<br>
                              <span id='frase'> 
                                Listado de moviminetos
                              </span>
                              <br>
                            </p>
                            <p id='InformacionDeCiudad'>
                              Municipialidad de Rio Tercero
                            </p>" .
                            ((empty($tablePrint))?"":$tablePrint) ."
                            <br>".
                            ((empty($TableMovPrint))?"":$TableMovPrint)."
                        </body>
                      </html>";
        
        ?>
        <input type="hidden" name="tabla_1" id = "tabla_1" value = "<?php echo $htmlPrint;?>">
  </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="class_modal-dialog modal-dialog" role="document"  id="id_modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="margin-left: auto;">Configurar resultados</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul type=none>
          <li><input type="checkbox" id="chkFecha"> Fecha </li>
          <li><input type="checkbox" id="chkMotivos"> Motivos</li>
          <li><input type="checkbox" id="chkPersona"> Persona</li> 
          <li><input type="checkbox" id="chkDNI"> DNI </li>
          <li><input type="checkbox" id="chkFechaNac"> Fecha Nac. </li>
          <li><input type="checkbox" id="chkEdad"> Edad </li>
          <li><input type="checkbox" id="chkMeses"> Meses </li>
          <li><input type="checkbox" id="chkObraSocial"> Obra Social </li>
          <li><input type="checkbox" id="chkDomicilio"> Domicilio </li>
          <li><input type="checkbox" id="chkBarrio"> Barrio </li>
          <li><input type="checkbox" id="chkLocalidad"> Localidad </li>
          <li><input type="checkbox" id="chkObservaciones"> Observaciones</li>
          <li><input type="checkbox" id="chkResponsable"> Responsable</li>
          <li><input type="checkbox" id="chkCentrosSalud"> Centro de salud </li>
          <li><input type="checkbox" id="chkOtrasInstituciones"> Otras instituciones</li>
        </ul>
      </div>
      <div class="modal-footer modal-footer-flex-center">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onClick="configResultados()" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>
<script>
        // <input type="checkbox" id="chkPersona"> Persona 
        // <input type="checkbox" id="chkDNI"> DNI 
        // <input type="checkbox" id="chkFechaNac"> Fecha Nac. 
        // <input type="checkbox" id="chkEdad"> Edad 
        // <input type="checkbox" id="chkMeses"> Meses 
        // <input type="checkbox" id="chkObraSocial"> Obra Social 
        // <input type="checkbox" id="chkDomicilio"> Domicilio 
        // <input type="checkbox" id="chkBarrio"> Barrio 
        // <input type="checkbox" id="chkLocalidad"> Localidad 

  <?php
  //$_SESSION["meses"] = $mesesHeader; ?>
  objectJsonTabla = <?php echo json_encode($jsonTable);?>;
  jsonTabla = <?php echo json_encode($jsonTable);?>;
  fechaDesde = "<?php echo $Fecha_Inicio;?>";
  fechaHasta = "<?php echo $Fecha_Fin;?>";
  filtroSeleccionados = <?php echo json_encode($filtros); ?>;
  function configResultados() {
    var chkFecha = document.getElementById('chkFecha').checked;
    var chkMotivos = document.getElementById('chkMotivos').checked;
    var chkPersona = document.getElementById('chkPersona').checked;
    var chkDNI = document.getElementById('chkDNI').checked;
    var chkFechaNac = document.getElementById('chkFechaNac').checked;
    var chkEdad = document.getElementById('chkEdad').checked;
    var chkMeses = document.getElementById('chkMeses').checked;
    var chkObraSocial = document.getElementById('chkObraSocial').checked;
    var chkDomicilio = document.getElementById('chkDomicilio').checked;
    var chkBarrio = document.getElementById('chkBarrio').checked;
    var chkLocalidad= document.getElementById('chkLocalidad').checked;


    var chkObservaciones= document.getElementById('chkObservaciones').checked;
    var chkResponsable= document.getElementById('chkResponsable').checked;
    var chkCentrosSalud= document.getElementById('chkCentrosSalud').checked;
    var chkOtrasInstituciones = document.getElementById('chkOtrasInstituciones').checked;

    var trFecha = document.getElementsByClassName('trFecha');
    var trMotivos= document.getElementsByClassName('trMotivos');
    var trPersona = document.getElementsByClassName('trPersona');
    var trDNI= document.getElementsByClassName('trDNI');
    var trFechaNac= document.getElementsByClassName('trFechaNac');
    var trEdad = document.getElementsByClassName('trEdad');
    var trMeses = document.getElementsByClassName('trMeses');
    var trObraSocial = document.getElementsByClassName('trObraSocial');
    var trDomicilio = document.getElementsByClassName('trDomicilio');
    var trBarrio = document.getElementsByClassName('trBarrio');
    var trLocalidad = document.getElementsByClassName('trLocalidad');

    var trObservaciones= document.getElementsByClassName('trObservaciones');
    var trResponsable= document.getElementsByClassName('trResponsable');
    var trCentrosSalud= document.getElementsByClassName('trCentrosSalud');
    var trOtrasInstituciones = document.getElementsByClassName('trOtrasInstituciones');

    if(!chkFecha){
      for (let i = 0; i < trFecha.length; i++) {        
        trFecha[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trFecha.length; i++) {        
        trFecha[i].removeAttribute('hidden');        
      }
    }

    if(!chkMotivos){
      for (let i = 0; i < trMotivos.length; i++) {        
        trMotivos[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trMotivos.length; i++) {        
        trMotivos[i].removeAttribute('hidden');        
      }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if(!chkPersona){
      for (let i = 0; i < trPersona.length; i++) {        
        trPersona[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trPersona.length; i++) {        
        trPersona[i].removeAttribute('hidden');        
      }
    }

    if(!chkDNI){
      for (let i = 0; i < trDNI.length; i++) {        
        trDNI[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trDNI.length; i++) {        
        trDNI[i].removeAttribute('hidden');        
      }
    }

    if(!chkFechaNac){
      for (let i = 0; i < trFechaNac.length; i++) {        
        trFechaNac[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trFechaNac.length; i++) {        
        trFechaNac[i].removeAttribute('hidden');        
      }
    }

    if(!chkEdad){
      for (let i = 0; i < trEdad.length; i++) {        
        trEdad[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trEdad.length; i++) {        
        trEdad[i].removeAttribute('hidden');        
      }
    }

    if(!chkMeses){
      for (let i = 0; i < trMeses.length; i++) {        
        trMeses[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trMeses.length; i++) {        
        trMeses[i].removeAttribute('hidden');        
      }
    }

    if(!chkObraSocial){
      for (let i = 0; i < trObraSocial.length; i++) {        
        trObraSocial[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trObraSocial.length; i++) {        
        trObraSocial[i].removeAttribute('hidden');        
      }
    }

    if(!chkDomicilio){
      for (let i = 0; i < trDomicilio.length; i++) {        
        trDomicilio[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trDomicilio.length; i++) {        
        trDomicilio[i].removeAttribute('hidden');        
      }
    }

    if(!chkBarrio){
      for (let i = 0; i < trBarrio.length; i++) {        
        trBarrio[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trBarrio.length; i++) {        
        trBarrio[i].removeAttribute('hidden');        
      }
    }

    if(!chkLocalidad){
      for (let i = 0; i < trLocalidad.length; i++) {        
        trLocalidad[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trLocalidad.length; i++) {        
        trLocalidad[i].removeAttribute('hidden');        
      }
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if(!chkObservaciones){
      for (let i = 0; i < trObservaciones.length; i++) {        
        trObservaciones[i].setAttribute('hidden', true);        
      }
    }else{  
      for (let i = 0; i < trObservaciones.length; i++) {        
        trObservaciones[i].removeAttribute('hidden');        
      }
    }

    if(!chkResponsable){
      for (let i = 0; i < trResponsable.length; i++) {        
        trResponsable[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trResponsable.length; i++) {        
        trResponsable[i].removeAttribute('hidden');        
      }
    }

    if(!chkCentrosSalud){
      for (let i = 0; i < trCentrosSalud.length; i++) {        
        trCentrosSalud[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trCentrosSalud.length; i++) {        
        trCentrosSalud[i].removeAttribute('hidden');        
      }
    }

    if(!chkOtrasInstituciones){
      for (let i = 0; i < trOtrasInstituciones.length; i++) {        
        trOtrasInstituciones[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trOtrasInstituciones.length; i++) {        
        trOtrasInstituciones[i].removeAttribute('hidden');        
      }
    }
  }
</script>
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
<!-- <script>
    function guardarFiltrosSeleccionados() {
      window.localStorage.clear();      
      window.localStorage.setItem("filtrosSeleccionados", < ?php echo implode($filtrosSeleccionados); ?>);

      location.href = 'view_listados.php';
    }
</script> -->
</body>
</html>