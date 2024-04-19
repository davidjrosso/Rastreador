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
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> 
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"> 
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>   
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  <!--<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->  
  <!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script> -->
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> -->
  <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/ValidarMovimiento.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!--<script type="text/javascript" src = "js/Funciones.js"></script> -->
  <script>
      var cantResponsables = 1;
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


      function seleccionPersona(xNombre,xID){
        var Persona = document.getElementById("Persona");
        var ID_Persona = document.getElementById("ID_Persona");
        Persona.innerHTML = "";
        Persona.innerHTML = "<p>"+xNombre+" <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalPersona'><i class='fa fa-cog text-secondary'></i></button></p>";
        ID_Persona.setAttribute('value',xID);
      }

      function seleccionMotivo_1(xMotivo,xID){
        var Motivo = document.getElementById("Motivo_1");
        var ID_Motivo = document.getElementById("ID_Motivo_1");
        Motivo.innerHTML = "";
        Motivo.innerHTML = "<p>"+xMotivo+" <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalMotivo_1'><i class='fa fa-cog text-secondary'></i></button></p>";
        ID_Motivo.setAttribute('value',xID);
      }

      function seleccionMotivo_2(xMotivo,xID){
        var Motivo = document.getElementById("Motivo_2");
        var ID_Motivo = document.getElementById("ID_Motivo_2");
        Motivo.innerHTML = "";
        Motivo.innerHTML = "<p>"+xMotivo+" <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalMotivo_2'><i class='fa fa-cog text-secondary'></i></button></p>";
        ID_Motivo.setAttribute('value',xID);
      }

      function seleccionMotivo_3(xMotivo,xID){
        var Motivo = document.getElementById("Motivo_3");
        var ID_Motivo = document.getElementById("ID_Motivo_3");
        Motivo.innerHTML = "";
        Motivo.innerHTML = "<p>"+xMotivo+" <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalMotivo_3'><i class='fa fa-cog text-secondary'></i></button></p>";
        ID_Motivo.setAttribute('value',xID);
      }

      function agregarResponsable(){        
        cantResponsables++;
        if(cantResponsables < 5){
          var divContenedor = document.getElementById('contenedorResponsables');
          var divResponsables= document.createElement("div");
          divResponsables.setAttribute('class','form-group row');
          var labelResponsables= document.createElement("label");
          labelResponsables.setAttribute('class','col-md-2 col-form-label LblForm');
          labelResponsables.innerText = 'Responsable '+cantResponsables+':';
          var divSelectResponsables= document.createElement("div");
          divSelectResponsables.setAttribute('class','col-md-10');
          var select = `<?php $Element = new Elements(); echo $Element->CBResponsables(); ?>`;
          divSelectResponsables.innerHTML = select;      
          divResponsables.appendChild(labelResponsables);
          divResponsables.appendChild(divSelectResponsables);
          divContenedor.appendChild(divResponsables);
        }

      }

      function resetearForm(){
            swal({
              title: "¿Está seguro?",
              text: "¿Seguro de querer resetear el formulario?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                reiniciarFormulario();
                // window.location.href = 'Controladores/DeletePersona.php?ID='+xID;
                //alert('SI');
              } else {        
              }
            });
      }

      function tomarElemento(xID){
        return document.getElementById(xID);
      }

      function crearElemento(xTipo){
        return document.createElement(xTipo);
      }

      function agregarAtributoxElemento(xElemento,xAtributo,xValue){
          xElemento.setAttribute(xAtributo,xValue);
      }

      function agregarEtiqueta(xElemento,xEtiqueta){
        xElemento.innerHTML = xEtiqueta;
      }

      function resetearValorElemento(xID){
        document.getElementById(xID).value = "";
      }

      function resetearValorSelect(xID){
        document.getElementById(xID).selectedIndex = 0;
      }

      function resetearValorDiv(xDiv){
        xDiv.innerHTML = "";
      }

      function agregarElementoxDiv(xDiv,xElemento){
        xDiv.appendChild(xElemento);
      }



      function reiniciarFormulario(){
        //RESETEANDO CAMPO FECHA
        resetearValorElemento("datepicker");        
        //RESETEANDO BOTON PERSONA
        var btnPersona = crearElemento("button");
        agregarAtributoxElemento(btnPersona,"type","button");
        agregarAtributoxElemento(btnPersona,"class","btn btn-lg btn-primary btn-block");
        agregarAtributoxElemento(btnPersona,"data-toggle","modal");
        agregarAtributoxElemento(btnPersona,"data-target","#ModalPersona");        
        agregarEtiqueta(btnPersona,"Seleccione una Persona");        
        var div_btnPersona = tomarElemento("Persona");
        resetearValorDiv(div_btnPersona);        
        agregarElementoxDiv(div_btnPersona,btnPersona);        
        //RESETEANDO BOTON SELECCIONE UN MOTIVO 1
        var btnMotivo_1 = crearElemento("button");
        agregarAtributoxElemento(btnMotivo_1,"type","button");
        agregarAtributoxElemento(btnMotivo_1,"class","btn btn-lg btn-primary btn-block");
        agregarAtributoxElemento(btnMotivo_1,"data-toggle","modal");
        agregarAtributoxElemento(btnMotivo_1,"data-target","#ModalMotivo_1");        
        agregarEtiqueta(btnMotivo_1,"Seleccione un Motivo");        
        var div_btnMotivo_1 = tomarElemento("Motivo_1");
        resetearValorDiv(div_btnMotivo_1);        
        agregarElementoxDiv(div_btnMotivo_1,btnMotivo_1); 
        //RESETEANDO BOTON SELECCIONE UN MOTIVO 2
        var btnMotivo_2 = crearElemento("button");
        agregarAtributoxElemento(btnMotivo_2,"type","button");
        agregarAtributoxElemento(btnMotivo_2,"class","btn btn-lg btn-primary btn-block");
        agregarAtributoxElemento(btnMotivo_2,"data-toggle","modal");
        agregarAtributoxElemento(btnMotivo_2,"data-target","#ModalMotivo_2");        
        agregarEtiqueta(btnMotivo_2,"Seleccione un Motivo");        
        var div_btnMotivo_2 = tomarElemento("Motivo_2");
        resetearValorDiv(div_btnMotivo_2);        
        agregarElementoxDiv(div_btnMotivo_2,btnMotivo_2);  
        //RESETEANDO BOTON SELECCIONE UN MOTIVO 3
        var btnMotivo_3 = crearElemento("button");
        agregarAtributoxElemento(btnMotivo_3,"type","button");
        agregarAtributoxElemento(btnMotivo_3,"class","btn btn-lg btn-primary btn-block");
        agregarAtributoxElemento(btnMotivo_3,"data-toggle","modal");
        agregarAtributoxElemento(btnMotivo_3,"data-target","#ModalMotivo_3");        
        agregarEtiqueta(btnMotivo_3,"Seleccione un Motivo");        
        var div_btnMotivo_3 = tomarElemento("Motivo_3");
        resetearValorDiv(div_btnMotivo_3);        
        agregarElementoxDiv(div_btnMotivo_3,btnMotivo_3);  
        //RESETEANDO OBSERVACIONES
        resetearValorElemento("Observaciones");
        //RESETEANDO RESPONSABLE
        resetearValorSelect("ID_Responsable");
        //RESETEANDO CENTRO DE SALUD
        resetearValorSelect("ID_Centro");
        //RESETEANDO RESPONSABLES CREADOS
        var div_btnPersona = tomarElemento("contenedorResponsables");
        resetearValorDiv(div_btnPersona);   
        cantResponsables = 1; 
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
            $Element->getMenuGeneral(1);?>
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
            $Element->getMenuGeneral(1);?>
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
            $Element->getMenuGeneral(1);?>
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
        <p>Nuevo Movimiento</p>
      </div>
      <div class="col"></div>
    </div><br>
    <div class="row">
      <div class="col"></div>
      <div class="col-10">
          <div class="row">
              <center><button class = "btn btn-secondary btn-sm" onClick="location.href ='view_newmovimientos.php'">Agregar Nuevo Movimiento</button> <button class = "btn btn-secondary btn-sm" onClick="location.href='view_newpersonas.php'">Agregar Nueva Persona</button> <button class = "btn btn-secondary btn-sm" onClick="location.href='view_newmotivos.php'">Agregar Nuevo Motivo</button> <button class = "btn btn-secondary btn-sm" onClick="location.href='view_newresponsables.php'">Agregar Nuevo Responsable</button></center>
          </div><br>
          <div class="row">
              <center><button class = "btn btn-secondary btn-sm" onClick="location.href='view_newcentros.php'">Agregar Nuevo Centro</button></center>
          </div>
      </div>
      <div class="col"></div>
    </div>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Carga -->
          <p class = "Titulos">Cargar Nuevo Movimiento</p>
          <form method = "post" onKeydown="return event.key != 'Enter';" action = "Controladores/InsertMovimiento.php" onSubmit = "return ValidarMovimiento();">
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Fecha: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Fecha" data-date-format="dd/mm/yyyy" id="datepicker" width="100%" autocomplete="off" placeholder= "<?php echo implode("/", array_reverse(explode("-",date('Y-m-d')))); ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Persona: </label>
              <div class="col-md-10" id = "Persona">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalPersona">Seleccione una Persona</button>  
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 1: </label>
              <div class="col-md-10" id = "Motivo_1">
                 <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo_1">Seleccione un Motivo</button>   
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 2: </label>
              <div class="col-md-10" id = "Motivo_2">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo_2">Seleccione un Motivo</button> 
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 3: </label>
              <div class="col-md-10" id = "Motivo_3">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo_3">Seleccione un Motivo</button> 
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Observaciones: </label>
              <div class="col-md-10">
                <textarea class = "form-control" row = "3" name = "Observaciones" id="Observaciones"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Responsable: </label>
              <div class = "col-md-9">
                <?php  
                $Element = new Elements();
                /*if(isset($_SESSION["UltResponsable"])){
                  $xID_Responsable = $_SESSION["UltResponsable"];
                  echo $Element->CBModResponsables($xID_Responsable);
                }else{
                  echo $Element->CBResponsables();
                }*/
                echo $Element->CBResponsables();
                ?>
              </div>
              <div class="col-md-1">
                  <button type="button" class="btn btn-primary" onClick="agregarResponsable()" id="agregarResponsableID">+</button>
              </div>
            </div>
            <div id="contenedorResponsables">              
            </div>
            <div class="form-group row">
              <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Centro de Salud: </label>
              <div class = "col-md-10">
                <?php  
                $Element = new Elements();
                /*if(isset($_SESSION["UltCentro"])){
                  $xID_Centro = $_SESSION["UltCentro"];
                  echo $Element->CBModCentros($xID_Centro);                  
                }else{
                  echo $Element->CBCentros();
                }*/
                echo $Element->CBCentros();
                ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Otras Instituciones: </label>
              <div class = "col-md-10">
                <?php  
                $Element = new Elements();
                /*if(isset($_SESSION["UltOtraInstitucion"])){
                  $xID_OtraInstitucion = $_SESSION["UltOtraInstitucion"];
                  echo $Element->CBModOtrasInstituciones($xID_OtraInstitucion);                  
                }else{
                  echo $Element->CBOtrasInstituciones();
                }*/
                echo $Element->CBOtrasInstituciones();
                ?>
              </div>
            </div>
            <div class="form-group row">
              <div class="offset-md-2 col-md-10">
                <input type="hidden" name="ID_Persona" id = "ID_Persona" value = "0">
                <input type="hidden" name="ID_Motivo_1" id = "ID_Motivo_1" value = "0">
                <input type="hidden" name="ID_Motivo_2" id = "ID_Motivo_2" value = "0">
                <input type="hidden" name="ID_Motivo_3" id = "ID_Motivo_3" value = "0">
                <button type="submit" class="btn btn-outline-success">Guardar</button> 
                <button type="button" class="btn btn-outline-secondary" onClick="resetearForm()">Cancelar</button>
                <button type = "button" class = "btn btn-danger" onClick = "location.href = 'view_movimientos.php'">Atras</button>
              </div>
            </div>
          </form>
          <div class="row">
              <div class="col-10"></div>
              <div class="col-2">
                
              </div>
          </div>
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
                      <input class = "form-control" type="text" name="BuscarPersona" id = "SearchPersonas" onKeyUp="buscarPersonas()" autocomplete="off" placeholder="Ingrese el nombre, apellido o dni de la persona">
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
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos_1" onKeyUp="buscarMotivos_1()" autocomplete="off">
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
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos_2" onKeyUp="buscarMotivos_2()" autocomplete="off">
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
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos_3" onKeyUp="buscarMotivos_3()" autocomplete="off">
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
      <!-- FIN SECCION DE MODALES -->
  </div>
</div>
</div>
<?php  
if(isset($_REQUEST["Mensaje"])){
  echo "<script type='text/javascript'>
  swal('".$_REQUEST["Mensaje"]."','','success');
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