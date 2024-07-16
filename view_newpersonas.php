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
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
  <link rel="import" href="https://sites.google.com/view/generales2019riotercero/página-principal">

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <!--<script type="text/javascript" src = "js/Funciones.js"></script> -->
  <script src="js/bootstrap-datepicker.min.js"></script> <!-- ESTO ES NECESARIO PARA QUE ANDE EN ESPAÑOL -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="js/ValidarPersona.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
       $(document).ready(function(){
              var date_input=$('input[name="Fecha_Nacimiento"]'); //our date input has the name "date"
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

        function ValidarDocumento(){
          var Documento = document.getElementById("idDocumento");
          var NroDocumento = Documento.value;
          if (NroDocumento.toString().length < 8){
            NotShowModalError();
            return true;
          }

          const DniNoRepetido = "<p>No hay ningún registro con ese nombre, documento o legajo</p>";
          xmlhttp=new XMLHttpRequest();

          xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              var contenidosRecibidos = xmlhttp.responseText;
              if(DniNoRepetido != contenidosRecibidos){ 
                Documento.value = "";
                swal({
                  title: "El Documento ingresado "+ NroDocumento +" ya esta registrado",
                  icon: "info",
                  text: "Por favor ingrese un Documento diferente",
                  confirmButtonText: 'OK'
                })
              }
            }
          }
          xmlhttp.open('POST', 'buscarPersonas.php?valorBusqueda='+NroDocumento, true); // Método post y url invocada
          xmlhttp.send();

        }

        function ShowModalError(){
          var modal = document.getElementById("ErrorDocumento");
          modal.style.display = "block";
          modal.innerText="El Documento ingresado ya Existe";
        }

        function NotShowModalError(){
          var modal = document.getElementById("ErrorDocumento");
          modal.style.display = "none";
        }

       function CargarEscuelas(xValor){
       		ID_Nivel = xValor;
       		var xMLHTTP = new XMLHttpRequest();

       		xMLHTTP.onreadystatechange = function(){
       			if(this.readyState == 4 && this.status == 200){
       				document.getElementById("Escuelas").innerHTML = this.responseText;
       			}
       		};
       		xMLHTTP.open("GET","CargarEscuelas.php?q="+xValor,true);
       		xMLHTTP.send();
       }

       function calcularEdad(){
            var Fecha_Nac = document.getElementById("Fecha_Nacimiento").value;
            var Fecha = Fecha_Nac.split('/').reverse().join('-');
            var hoy = new Date();
            var cumpleanos = new Date(Fecha);
            var edad = hoy.getFullYear() - cumpleanos.getFullYear();
            var m = hoy.getMonth() - cumpleanos.getMonth();

            if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
                edad--;
            }

            var Anios = document.getElementById("Edad");
            if(!isNaN(edad)){
              Anios.value = edad;
            }
            

            var CalcMeses = 0;
            if(m<0){
              CalcMeses = (12 + m);
            }else{
              CalcMeses = m;
            }
            
            var Meses = document.getElementById("Meses");
            if(!isNaN(CalcMeses)){
              Meses.value = CalcMeses;        
            }
            
      } 

      function buscarCalles(){
      var xNombre = document.getElementById('SearchCalle').value;
      console.log(xNombre);
      var textoBusqueda = xNombre;
      xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          contenidosRecibidos = xmlhttp.responseText;
          document.getElementById("ResultadosCalles").innerHTML=contenidosRecibidos;
          }
      }
      xmlhttp.open('POST', 'buscarCalle.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
      xmlhttp.send();
    }

    function seleccionCalle(xNombre,xID){
          var BotonModalPersona = document.getElementById("BotonModalDireccion_1");
          var Calle = document.getElementById("Calle");
          BotonModalPersona.innerHTML = "";
          BotonModalPersona.innerHTML = xNombre;
          Calle.setAttribute('value',xNombre);
        }
  </script>
  <!--
  <script type="text/javascript">
      var getImport = document.quearySelector ('link [rel = import]'); 
      var getContent = getImport.import.querySelector('body');

      var ContenidoPagina = document.getElementById("ContenidoPagina");

      ContenidoPagina.appendChild(document.importNode(getContent, true));
  </script>
-->
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
            $Element->getMenuActualizaciones(1);?>
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
    if($TipoUsuario == 2 || $TipoUsuario > 3){
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
            $Element->getMenuActualizaciones(1);?>
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
    <div class="brand">General</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuGeneral(0);?>
        </div>
        <div class="brand">Actualizaciones</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuActualizaciones(1);?>
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
        <p>Nueva Persona</p>
      </div>
      <div class="col"></div>
    </div><br>
    <div class="row">
      <div class="col"></div>
      <div class="col-10">
          <div class="row">
              <center><button class = "btn btn-secondary btn-sm" onClick="location.href ='view_newmovimientos.php'">Agregar Nuevo Movimiento</button></center>
          </div>
      </div>
      <div class="col"></div>
    </div>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Carga -->
          <p class = "Titulos">Cargar Nueva Persona</p>
          <form method = "post" onKeydown="return event.key != 'Enter';" action = "Controladores/InsertPersona.php" onSubmit = "return ValidarPersona();">
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Apellido: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Apellido" id="Apellido" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Nombre: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Nombre" id="Nombre" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="idDocumento" class="col-md-2 col-form-label LblForm">Documento: </label>
              <div class="col-md-10">
                <input type="number" class="form-control number-to-text" name = "DNI" oninput="ValidarDocumento()" id="idDocumento" required minlength="7" maxlength="8" autocomplete="off">
              </div>
            </div>
            <div class="div-modal-Error" id="ErrorDocumento">
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm" style="margin-bottom: -8px;">Fecha de Nacimiento: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Fecha_Nacimiento" id="Fecha_Nacimiento" autocomplete="off" placeholder="Ejemplo: 01/01/2010" onFocusOut="calcularEdad()"> 
              </div>
            </div>
            <div class="row LblForm col-md-2" style="margin-bottom: 1.04%; font-size: 1.031rem">
                  Edad <br>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Años: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Edad" id="Edad" autocomplete="off" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Meses: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Meses" id="Meses" autocomplete="off" readonly>
              </div>
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
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Localidad: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Localidad" id="inputPassword" autocomplete="off" value = "Río Tercero">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Barrio: </label>
              <div class="col-md-10">
                <?php 
                $Element = new Elements();
                echo $Element->CBBarrios();
                ?>
              </div>
            </div>
            <div class="form-group row" style="margin-bottom: 0.6rem;">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Domicilio: </label>
              <div class="col-md-8" id = "Persona">
              	 	<button type = "button" id="BotonModalDireccion_1" class = "btn btn-lg btn-primary btn-block" style="padding-top: 4px;padding-bottom: 4px;" data-toggle="modal" data-target="#ModalCalle">Seleccione una Calle</button>                  
              </div>
              <div class="col-md-2">
                <input type="number" class="form-control" style="margin-top: 1px;" name = "NumeroDeCalle" id="NumeroDeCalle" placeholder="Número" min="1" autocomplete="off">
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
                <input type="text" class="form-control" name = "Lote" id="inputPassword" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Sublote: </label>
              <div class="col-md-10">
                <input type="number" class="form-control" name = "Familia" id="inputPassword" autocomplete="off">
              </div>
            </div>            
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Teléfono: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Telefono" id="inputPassword" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Mail: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Mail" id="inputPassword" autocomplete="off">
              </div>
            </div>            
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Obra Social(Si/No): </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Obra_Social" id="inputPassword" autocomplete="off">
              </div>
            </div>                        
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Nivel Escolar: </label>
              <div class="col-md-10">
                <?php 
                $Element = new Elements();
                echo $Element->CBNivelEscuelas();
                ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Escuela: </label>
              <div class="col-md-10" id = "Escuelas">
                <?php 
                $Element = new Elements();
                echo $Element->CBEscuelas(0);
            ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Lugar de Trabajo: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Trabajo" id="inputPassword" autocomplete="off">
              </div>
            </div>                                                
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Observaciones: </label>
              <div class="col-md-10">
                <textarea class = "form-control" row = "3" name = "Observaciones"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Cambio de Domicilio: </label>
              <div class="col-md-10">
                <textarea class = "form-control" row = "3" name = "Cambio_Domicilio"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <div class="offset-md-2 col-md-10">
                <button type="submit" class="btn btn-outline-success">Guardar</button>
                <button type = "button" class = "btn btn-danger" onClick = "location.href = 'view_personas.php'">Atras</button>
              </div>
            </div>
            <input type="hidden" name="Calle" id="Calle" value = "">

          </form>
          <div class="row">
              <div class="col-10"></div>
              <div class="col-2"></div>
          </div>
          <!-- Fin Carga -->

          <!-- Modal de Carga de Calle-->

      <div class="modal fade bd-example-modal-lg" id="ModalCalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Seleccione una Calle</h5>
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
                        <input class = "form-control" type="text" name="BuscarCalle" id = "SearchCalle" onKeyUp="buscarCalles()" autocomplete="off" placeholder="Ingrese el nombre de calle">
                        <div class="input-group-append">
                          <span class="input-group-text" id="basic-addon2">Buscar</span>
                        </div>	
                      </div>		        				
                    </div>
                    <div class="col"></div>
                  </div>
                  <div class="row">
                    <div class="col"></div>
                    <div class="col-10" id = "ResultadosCalles">
                      
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
      <!-- Modal de Carga de Calle-->
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
</body>
</html>