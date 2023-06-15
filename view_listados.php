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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
  <!--<script type="text/javascript" src = "js/Funciones.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js" integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script> <!-- ESTO ES NECESARIO PARA QUE ANDE EN ESPAÑOL -->
  <script src="js/ValidarGeneral.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!--
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
-->
  <script>
    var cantBarrios = 1;
    $(document).ready(function(){
              var date_input=$('input[name="Fecha_Desde"]'); //our date input has the name "date"
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
              var date_input2=$('input[name="Fecha_Hasta"]'); //our date input has the name "date"
              var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
              date_input2.datepicker({
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
    /*
  	$(document).ready(function() {
		    $("#ResultadosPersonas").html('<p>No se ha ingresado ningun dato</p>');		    
		});

      function buscarPersonas(xNombre) {
		    var textoBusqueda = xNombre;
		 
		     if (textoBusqueda != "") {
		        $.post("buscarPersonas.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
		            $("#ResultadosPersonas").html(mensaje);
		         }); 
		     } else { 
	        	$("#ResultadosPersonas").html('<p>No se ha seleccionado ninguna Exploracion Clinica</p>');
	        };
		};
    */
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

    function buscarMotivos(){
      var xMotivo = document.getElementById('SearchMotivos').value;
      var textoBusqueda = xMotivo;
      xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          contenidosRecibidos = xmlhttp.responseText;
          document.getElementById("ResultadosMotivos").innerHTML=contenidosRecibidos;
          }
      }
      xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
      xmlhttp.send();
    }

    function buscarMotivos2(){
      var xMotivo = document.getElementById('SearchMotivos2').value;
      var textoBusqueda = xMotivo;
      xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          contenidosRecibidos = xmlhttp.responseText;
          document.getElementById("ResultadosMotivos2").innerHTML=contenidosRecibidos;
          }
      }
      xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda='+textoBusqueda+'&number=2', true); // Método post y url invocada
      xmlhttp.send();
    }

    function buscarMotivos3(){
      var xMotivo = document.getElementById('SearchMotivos3').value;
      var textoBusqueda = xMotivo;
      xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          contenidosRecibidos = xmlhttp.responseText;
          document.getElementById("ResultadosMotivos3").innerHTML=contenidosRecibidos;
          }
      }
      xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda='+textoBusqueda+'&number=3', true); // Método post y url invocada
      xmlhttp.send();
    }

    function buscarCategorias(){
      var xCategoria = document.getElementById('SearchCategorias').value;
      var textoBusqueda = xCategoria;
      xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          contenidosRecibidos = xmlhttp.responseText;
          document.getElementById("ResultadosCategorias").innerHTML=contenidosRecibidos;
          }
      }
      xmlhttp.open('POST', 'buscarCategorias.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
      xmlhttp.send();
    }
    
    function seleccionPersona(xNombre,xID){
      var Persona = document.getElementById("Persona");
      var ID_Persona = document.getElementById("ID_Persona");
      Persona.innerHTML = "";
      Persona.innerHTML = "<p>"+xNombre+"</p>";
      ID_Persona.setAttribute('value',xID);
      var BtnBarrios = document.getElementById("agregarBarrio");
      BtnBarrios.setAttribute('disabled', true);      
      var SelMostrar = document.getElementById("inpMostrar");
      SelMostrar.setAttribute('disabled', true);
    }

    function seleccionMotivo(xMotivo,xID,xNumber){
      if(xNumber > 1){
        var Motivo = document.getElementById("Motivo"+xNumber);
        var ID_Motivo = document.getElementById("ID_Motivo"+xNumber);
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

    function seleccionCategoria(xCategoria,xID){
      var Categoria = document.getElementById("Categoria");
      var ID_Categoria = document.getElementById("ID_Categoria");
      Categoria.innerHTML = "";
      Categoria.innerHTML = "<p>"+xCategoria+"</p>";
      ID_Categoria.setAttribute('value',xID);
    }

    function agregarBarrio(){
      cantBarrios++;
      var divContenedor = document.getElementById('contenedorBarrios');
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

    function cambiarConfig(){
      var ID_Config = document.getElementById('ID_Config');
      var formatConfig = document.getElementById('formatConfig');
      ID_Config.value = formatConfig.value;      
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
    if($TipoUsuario == 2){
  ?>
  <div class = "col-md-3">
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
            $Element->getMenuReportes(1);?>
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
        <p>Reporte por listados</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
    <div class="row">
      <div class="offset-md-10 col-md-2">
        <button class="btn btn-md btn-secondary" data-toggle="modal" data-target="#ModalConfig">Config</button>
      </div>
    </div>
     <div class = "row">
      <div class = "col-10">
          <!-- Carga -->
          <p class = "Titulos">Parámetros</p>
          <form method = "post" onKeydown="return event.key != 'Enter';" action = "view_vermovlistados.php" onSubmit = "return ValidarGeneral();">
            <div class="form-group row">
                <label for="inputPassword" class="col-md-2 col-form-label LblForm">Fecha desde *: </label>
                <div class="col-md-10">
                    <input type="text" name="Fecha_Desde" id = "Fecha_Desde" class="form-control" autocomplete="off" value = "<?php echo implode("/", array_reverse(explode("-",date('Y-m-d',strtotime(date('Y-m-d')."- 1 year"))))); ?>">
                </div>
            </div> 
            <div class="form-group row">
                <label for="inputPassword" class="col-md-2 col-form-label LblForm">Fecha hasta *: </label>
                <div class="col-md-10">
                    <input type="text" name="Fecha_Hasta" id = "Fecha_Hasta" class="form-control" autocomplete="off" value = "<?php echo implode("/", array_reverse(explode("-",date('Y-m-d')))); ?>">
                </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Persona: </label>
              <div class="col-md-10" id = "Persona">
              	 	<button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalPersona">Seleccione una Persona</button>                  
              </div>
            </div>
            <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Desde (Edad): </label>
                  <div class="col-md-10">
                      <input type="number" name="Edad_Desde" class="form-control" autocomplete="off" placeholder="Sólo Números">
                      <input type="hidden" name="ID_Persona" id = "ID_Persona" value = "0">
                  </div>
            </div> 
            <div class="form-group row">
                <label for="inputPassword" class="col-md-2 col-form-label LblForm">Hasta (Edad): </label>
                <div class="col-md-10">
                    <input type="number" name="Edad_Hasta" class="form-control" autocomplete="off" placeholder="Sólo Números">
                </div>
            </div> 
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Domicilio/Familia: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Domicilio" id="inputPassword" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Manzana: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Manzana" id="inputPassword" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Lote: </label>
              <div class="col-md-10">
                <input type="number" class="form-control" name = "Lote" id="inputPassword" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Sub-lote: </label>
              <div class="col-md-10">
                <input type="number" class="form-control" name = "Familia" id="inputPassword" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Barrio: </label>
              <div class="col-md-9">
                <?php  
                $Element = new Elements();
                echo $Element->CBRepBarrios();
                ?>
              </div>
              <div class="col-md-1">
                  <button type="button" class="btn btn-primary" onClick="agregarBarrio()" id="agregarBarrio">+</button>
              </div>
            </div>
            <div id="contenedorBarrios">              
            </div>

            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Nro. Carpeta: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Nro_Carpeta" id="Nro_Carpeta" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Nro. Legajo: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Nro_Legajo" id="Nro_Legajo" autocomplete="off">
              </div>
            </div>
            
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Escuela: </label>
              <div class="col-md-10">
                <?php  
                $Element = new Elements();
                echo $Element->CBRepEscuelas();
                ?>
              </div>
            </div>
          
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Trabajo: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Trabajo" id="inputPassword" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 1: </label>
              <div class="col-md-10" id = "Motivo">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo">Seleccione un Motivo</button>   
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 2: </label>
              <div class="col-md-10" id = "Motivo2">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo2">Seleccione un Motivo</button>   
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 3: </label>
              <div class="col-md-10" id = "Motivo3">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo3">Seleccione un Motivo</button>   
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Categoría: </label>
              <div class="col-md-10" id = "Categoria">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalCategoria">Seleccione una Categoria</button>  
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Centro Salud: </label>
              <div class="col-md-10">
                <?php  
                $Element = new Elements();
                echo $Element->CBRepCentros();
                ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Otras Instituciones: </label>
              <div class="col-md-10">
                <?php  
                $Element = new Elements();
                echo $Element->CBRepOtrasInstituciones();
                ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Mostrar Personas: </label>
              <div class="col-md-10">
                <select class="form-control" name="Mostrar" id="inpMostrar">
                	<option value="0">Con Movimientos</option>
                	<option value="1">Todos</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="offset-md-2 col-md-10">
                <input type="hidden" name="ID_Motivo" id = "ID_Motivo" value = "0">
                <input type="hidden" name="ID_Motivo2" id = "ID_Motivo2" value = "0">
                <input type="hidden" name="ID_Motivo3" id = "ID_Motivo3" value = "0">
                <input type="hidden" name="ID_Categoria" id = "ID_Categoria" value = "0">
                <input type="hidden" name="ID_Config" id="ID_Config" value="grid">
                <button type="submit" class="btn btn-outline-success">Aceptar</button>
                <button type="button" class="btn btn-outline-secondary" onclick="location.href = 'view_inicio.php'">Volver</button>
              </div>
            </div>
          </form>
          <br><br><br>
          <!-- Fin Carga -->
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
				        			<input class = "form-control" type="text" name="BuscarPersona" id = "SearchPersonas" onKeyUp="buscarPersonas()" autocomplete="off" placeholder="Ingrese el nombre, apellido o documento de la persona">
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
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos" onKeyUp="buscarMotivos()" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos">
                    
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
      <!-- Modal SELECCION MOTIVO 2 -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                      <input class = "form-control" type="text" name="BuscarMotivos2" id = "SearchMotivos2" onKeyUp="buscarMotivos2()" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos2">
                    
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
      <!-- Modal SELECCION MOTIVO 3 -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo3" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                      <input class = "form-control" type="text" name="BuscarMotivos3" id = "SearchMotivos3" onKeyUp="buscarMotivos3()" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos3">
                    
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
      <!-- Modal SELECCION CATEGORIA -->
      <div class="modal fade bd-example-modal-lg" id="ModalCategoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Categoria</h5>
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
                      <input class = "form-control" type="text" name="BuscarCategorias" id = "SearchCategorias" onKeyUp="buscarCategorias()" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosCategorias">
                    
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
      <!-- FIN MODAL SELECCION CATEGORIA -->
      <!-- Modal CONFIGURACION-->
      <div class="modal fade bd-example-modal-lg" id="ModalConfig" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h1>Configuración</h1>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="offset-4 col-2">
                  <label for="formatConfig">Formato: </label>
                </div>
                <div class="col-4">
                  <select class="input-group mb-3" name="formatConfig" id="formatConfig" onchange="cambiarConfig()">
                    <option value="grid">Grid</option>
                    <option value="table">Table</option>
                  </select>
                </div>
              </div>              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION CATEGORIA -->
          <!-- FIN SECCION DE MODALES -->
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