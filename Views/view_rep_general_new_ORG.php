<?php 
session_start(); 
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
require_once "Controladores/Conexion.php";
require_once "sys_config.php";

require_once 'dompdf/autoload.inc.php';


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
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
  <script src="js/FileSaver.js"></script> 
  <script src="js/jquery.wordexport.js"></script> 
  
  <script src="html2pdf.bundle.min.js"></script>
  

  <script>

		function mostrar() {
      // alert("mostrar!")

        document.getElementById("expandir").hidden=true;
        document.getElementById("ContenidoMenu").hidden=false;
      
			  var ContenidoMenu = document.getElementById("ContenidoMenu");
		    ContenidoMenu.setAttribute("class","col-md-2");
		    document.getElementById("sidebar").style.width = "200px";
		    
		    var ContenidoTabla = document.getElementById("ContenidoTabla");
		    ContenidoTabla.setAttribute("class","col-md-10");
		    document.getElementById("abrir").style.display = "none";
		    document.getElementById("cerrar").style.display = "inline";
		}

		function ocultar() {		 
        // alert("mostrar el boton expandir")
        document.getElementById("expandir").hidden=false;
        document.getElementById("ContenidoMenu").hidden=true;
		    
        // var ContenidoMenu = document.getElementById("ContenidoMenu");
		    // ContenidoMenu.setAttribute("class","col-md-1");
		    // document.getElementById("sidebar").style.width = "3%"; //5


		    //document.getElementById("ContenidoTabla").style.marginLeft = "0";
		    var ContenidoTabla = document.getElementById("ContenidoTabla");
		    ContenidoTabla.setAttribute("class","col-md-12");

		    document.getElementById("abrir").style.display = "inline";
		    document.getElementById("cerrar").style.display = "none";
		}

		
  </script>

  <style>
     table thead tr th{
        background-color: #ccc; 
        position: sticky;
        top: 0;
        z-index: 100; 
        display: block;
      }

      /* table tbody tr td{
        position: sticky;
        top: 100;
        z-index: 90;
        display: inline-block;
      } */

      .table-responsive{
        height: 480px;
        width: 98%;
        overflow-y: scroll;
        overflow-x: scroll; 
        position: absolute;       
      }

      .table-fixeder{
        width: max-content; 
        /* width: auto; */
        height: 470px;
        /* overflow-x: scroll;  */
        display: table;  
        position: relative;
        /* white-space: nowrap;                  */
      }

      .table-fixeder thead{
        width: max-content;   
        z-index: 2000;
        position: sticky;
        top: 0;   
        display: block;
        /* display: block;             */
      }

      /* .table-fixeder tbody {
        height: 400px;
        width: max-content;
        z-index: 20;
        overflow-y: auto;
        overflow-x: hidden;        
      } */

      

      .table-fixeder td,
      .table-fixeder thead,
      .table-fixeder tbody,
      .table-fixeder tr,      
      .table-fixeder th {
        display: block;                    
      }

      .table-fixeder tbody td,
      .table-fixeder tbody tr td,
      .table-fixeder thead > tr> th{
        float: left;
        border-bottom-width: 0; 
        width: 150px; 
        height: 70px;                 
      }

      .table-fixeder tbody tr td .Datos,
      .table-fixeder thead tr th .Datos{
        min-width: 150px;
        height: 70px;
      }

      .Datos{
        font-size: 18px; 
        font-weight: bold;                
      }

      .SinMovimientos td{
        background-color: #ccc;
      }

      .etFiltros{
        color: #FFF;        
        background-color: #BBB;
        border-radius: 20px;
        padding: 8px;
        display: inline-block;
        margin: 3px;
      }

      .nombreCategoria{
        font-size: 10px;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        display: block;
        text-align: center;                
      }

      /*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
      /*/////////////////////////////////////////INTENDANDO SOLUCIONAR EL SCROLL HORIZONTAL DE LOS DATOS////////////////////////////////////////////////////*/
      /*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
      	#Contenido-1{            
            position: sticky;
            left: 0;
            width: 150px;
            z-index: 200;
            background-color: #FFF;
       	}

       	#Contenido-2{            
            position: sticky;
            left: 150px;
            width: 150px;
            z-index: 200;
            background-color: #FFF;
        }

        #Contenido-3{            
            position: sticky;
            left: 300px;
            width: 50px;
            z-index: 200;
            background-color: #FFF;
        }

        #Contenido-4{            
            position: sticky;
            left: 350px;
            width: 50px;
            z-index: 200;
            background-color: #FFF;
       	}

       	#Contenido-5{            
            position: sticky;
            left: 400px;
            width: 60px;
            z-index: 200;
            background-color: #FFF;
        }

        #Contenido-6{            
            position: sticky;
            left: 450px;
            width: 150px;
            z-index: 200;
            background-color: #FFF;
        }

        #Contenido-7{            
            position: sticky;
            left: 600px;
            width: 150px;
            z-index: 200;
            background-color: #FFF;
        }

        #Contenido-Titulo-1{            
            position: sticky;
            left: 0;            
            width: 150px;
            z-index: 200;
        }

        #Contenido-Titulo-2{            
            position: sticky;
            left: 150px;
            width: 150px;
            z-index: 200;
        }

        #Contenido-Titulo-3{            
            position: sticky;
            left: 300px;
            width: 50px;
            z-index: 200;
        }

        #Contenido-Titulo-4{            
            position: sticky;
            left: 350px;
            width: 50px;
            z-index: 200;
        }

        #Contenido-Titulo-5{            
            position: sticky;
            left: 400px;
            width: 70px;
            z-index: 200;
        }

        #Contenido-Titulo-6{            
            position: sticky;
            left: 450px;
            width: 150px;
            z-index: 200;
        }

        #Contenido-Titulo-7{            
            position: sticky;
            left: 600px;
            width: 150px;
            z-index: 200;
        }

      /*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
      /*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
      /*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/                                                                                  
    </style>  
</head>

<body>
<div class = "row">
   <?php  
  if($TipoUsuario == 1){  
  ?>
    <div class = "col-md-2" id="expandir" hidden>
       <a id="abrir" class="btn btn-secondary btn-sm" href="javascript:void(0)" onclick="mostrar()">
	        <i class="fa fa-arrows-alt fa-lg" color="tomato"></i>
	    </a>
    </div> 

  <div class = "col-md-2" id="ContenidoMenu">
   
    <div class="nav-side-menu" id="sidebar" >   
	    <a id="cerrar" class="btn btn-secondary btn-sm" href="javascript:void(0)" onclick="ocultar()">
	        <i class="fa fa-arrow-left fa-lg"></i>
	    </a>

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
        <div class="brand btn-Salir" onClick = "location.href = '/logout'">Salir***</div>
    </div>
  </div>
  <?php 
    }
    if($TipoUsuario == 2 || $TipoUsuario > 3){
  ?>
  <div class = "col-md-2" id="ContenidoMenu">
    <div class="nav-side-menu" id="sidebar" style="padding-left: 5px;">
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
        <div class="brand btn-Salir" onClick = "location.href = '/logout'">Salir</div>
    </div>
  </div>
  <?php
  }  
  if($TipoUsuario == 3){    
  ?>
  <div class = "col-md-2" id="ContenidoMenu">
    <div class="nav-side-menu" id="sidebar" style="padding-left: 5px;">
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
        <div class="brand btn-Salir" onClick = "location.href = '/logout'">Salir</div>
    </div>
  </div>
<?php } ?>

    
  <div class = "col-md-10" id="ContenidoTabla">
     
    
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Rastreador. Gráfico de co-evolución para la evaluación  comunitaria de cobertura.<br>Sistema orientado a la  georreferenciación.</p>
      </div>
      <div class="col"></div>
    </div><br>
    <div class="row">
      <div class="col">
        <button class = "btn btn-info btn-sm" onClick="toggleZoomScreen()">Zoom +</button> <button class = "btn btn-info btn-sm" onClick="toggleZoomScreenNormal()">Zoom -</button>
      </div>
      <div class="col">
      	<?php  
      	if(!isset($_REQUEST["Anio"])){
              $Fecha_Inicio = implode("-", array_reverse(explode("/",$_REQUEST["Fecha_Desde"])));
              $Fecha_Fin = implode("-", array_reverse(explode("/",$_REQUEST["Fecha_Hasta"])));
              $ID_Persona = $_REQUEST["ID_Persona"];
              $Edad_Desde = $_REQUEST["Edad_Desde"];
              $Edad_Hasta = $_REQUEST["Edad_Hasta"];
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
              $ID_Categoria = $_REQUEST["ID_Categoria"]; 
              $ID_Escuela = $_REQUEST["ID_Escuela"];
              $Trabajo = $_REQUEST["Trabajo"];
              $Mostrar = $_REQUEST["Mostrar"];
              $ID_CentroSalud = $_REQUEST["ID_CentroSalud"];
              $ID_OtraInstitucion = $_REQUEST["ID_OtraInstitucion"];

          	  $Consulta = "SELECT M.id_movimiento, M.id_persona, MONTH(M.fecha) as 'Mes', YEAR(M.fecha) as 'Anio', B.Barrio, P.manzana, P.lote, P.familia, P.apellido, P.nombre, P.fecha_nac, P.domicilio
               from movimiento M, persona P, barrios B, motivo MT, categoria C, centros_salud CS, otras_instituciones I
               WHERE M.id_persona = P.id_persona and B.ID_Barrio = P.ID_Barrio and M.id_centro = CS.id_centro and M.id_otrainstitucion = I.ID_OtraInstitucion and M.estado = 1 and P.estado = 1 and MT.estado = 1 and C.estado = 1 and M.fecha between '$Fecha_Inicio' and '$Fecha_Fin'"; 

              $filtros = [];
              $Con = new Conexion();
              $Con->OpenConexion();             

              if($ID_Persona > 0){ //P.nro_legajo,P.nro_carpeta
                $Consulta .= " and P.id_persona = $ID_Persona";
                $ConsultarPersona = "select apellido, nombre from persona where ID_Persona = ".$ID_Persona." limit 1";
                $EjecutarConsultarPersona = mysqli_query($Con->Conexion,$ConsultarPersona) or die("Problemas al consultar filtro Persona");
                $RetConsultarPersona = mysqli_fetch_assoc($EjecutarConsultarPersona);
                $filtros[] = "Persona: ".$RetConsultarPersona["apellido"].", ".$RetConsultarPersona["nombre"];
              }

              if($Edad_Desde != null && $Edad_Desde != "" && $Edad_Hasta != null && $Edad_Hasta != ""){
                // $Consulta .= " and P.edad between $Edad_Desde and $Edad_Hasta";
                $Consulta .= " and P.edad > $Edad_Desde and P.edad < $Edad_Hasta";
                $filtros[] = "Edad: Desde ".$Edad_Desde." hasta ".$Edad_Hasta;
              }

              if($Domicilio != null && $Domicilio != ""){
                $Consulta .= " and P.domicilio like '%$Domicilio%'";
                $filtros[] = "Domicilio: ".$Domicilio;
              }

              if($Manzana != null && $Manzana != ""){
                $Consulta .= " and P.manzana = '$Manzana'";
                $filtros[] = "Manzana: ".$Manzana;
              }

              if($Lote != null && $Lote != ""){
                $Consulta .= " and P.lote = $Lote";
                $filtros[] = "Lote: ".$Lote;
              }

              if($Familia != null && $Familia != ""){
                $Consulta .= " and P.familia = $Familia";
                $filtros[] = "Sublote: ".$Familia;
              }

              if($Nro_Carpeta != null && $Nro_Carpeta != ""){
                $Consulta.= " and P.nro_carpeta = '$Nro_Carpeta'";
                $filtros[] = "Nro_carpeta: ".$Nro_Carpeta;
              }

              if($Nro_Legajo != null && $Nro_Legajo != ""){
                $Consulta.= " and P.nro_legajo = '$Nro_Legajo'";
                $filtros[] =  " Nro_legajo : ".$Nro_Legajo;
              }


              if(count($Barrio) > 1){
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
                    $ConsultarBarrio = "select Barrio from barrios where ID_Barrio = ".$valueBarrio." limit 1";
                    $EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                    $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);   
                    if($key == $Barrio->array_key_first){
                      $filtroBarrios .= " ".$RetConsultarBarrio['Barrio'];   
                    }else{
                      $filtroBarrios .= " - ".$RetConsultarBarrio['Barrio'];   
                    }                                   
                  }
                }
                $filtros[] = $filtroBarrios;
              }else{
                if($Barrio[0] > 0){
                  $Consulta .= " and P.ID_Barrio = $Barrio[0]";
                  $ConsultarBarrio = "select Barrio from barrios where ID_Barrio = ".$Barrio[0]." limit 1";
                  $EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                  $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
                  $filtros[] = "Barrio: ".$RetConsultarBarrio['Barrio'];
                }                
              }
              
              // $Consulta.= ")";

              // if($Barrio > 0){
              //   $Consulta .= " and P.ID_Barrio = $Barrio";
              // }

              if($ID_Escuela > 0){
                $Consulta .= " and P.ID_Escuela = $ID_Escuela";
                $ConsultarEscuela = "select Escuela from escuelas where ID_Escuela = ".$ID_Escuela." limit 1";
                $EjecutarConsultarEscuela = mysqli_query($Con->Conexion,$ConsultarEscuela) or die("Problemas al consultar filtro Escuela");
                $RetConsultarEscuela = mysqli_fetch_assoc($EjecutarConsultarEscuela);  
                $filtros[] = "Escuela: ".$RetConsultarEscuela['Escuela'];
              }

              if($Trabajo != null && $Trabajo != ""){
                $Consulta .= " and P.Trabajo like '%$Trabajo%'";
                $filtros[] = "Trabajo: ".$Trabajo;
              }

              if($ID_Motivo > 0){
                if($ID_Motivo2 > 0 || $ID_Motivo3 > 0){
                  $Consulta .= " and (";
                }else{
                  $Consulta .= " and ";
                }
                $Consulta .= " (M.motivo_1 = $ID_Motivo or M.motivo_2 = $ID_Motivo or M.motivo_3 = $ID_Motivo)";
                // $Consulta .= "M.motivo_1 = $ID_Motivo or M.motivo_2 = $ID_Motivo or M.motivo_3 = $ID_Motivo)";
                $ConsultarMotivo = "select motivo from motivo where id_motivo = ".$ID_Motivo." limit 1";
                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);  
                $filtros[] = "Motivo 1: ".$RetConsultarMotivo['motivo'];                
              }

              if($ID_Motivo2 > 0){
                $Consulta .= " or (M.motivo_1 = $ID_Motivo2 or M.motivo_2 = $ID_Motivo2 or M.motivo_3 = $ID_Motivo2)";
                $ConsultarMotivo = "select motivo from motivo where id_motivo = ".$ID_Motivo2." limit 1";
                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);  
                $filtros[] = "Motivo 2: ".$RetConsultarMotivo['motivo'];

                if($ID_Motivo3 == 0){
                  $Consulta .= ")";
                }
              }

              if($ID_Motivo3 > 0){
                $Consulta .= " or (M.motivo_1 = $ID_Motivo3 or M.motivo_2 = $ID_Motivo3 or M.motivo_3 = $ID_Motivo3))";
                $ConsultarMotivo = "select motivo from motivo where id_motivo = ".$ID_Motivo3." limit 1";
                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);  
                $filtros[] = "Motivo 3: ".$RetConsultarMotivo['motivo'];
              }

              if($ID_Categoria > 0){
                $Consulta .= " and  ((M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria) or (M.motivo_2 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria) or (M.motivo_3 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria))";
                $ConsultarCategoria = "select categoria from categoria where id_categoria = ".$ID_Categoria." limit 1";
                $EjecutarConsultarCategoria = mysqli_query($Con->Conexion,$ConsultarCategoria) or die("Problemas al consultar filtro Categoria");
                $RetConsultarCategoria = mysqli_fetch_assoc($EjecutarConsultarCategoria);  
                $filtros[] = "Categoria: ".$RetConsultarCategoria['categoria'];
              }

              if($ID_CentroSalud > 0){
                $Consulta .= " and CS.id_centro = $ID_CentroSalud";
                $ConsultarCentroSalud = "select centro_salud from centros_salud where id_centro = ".$ID_CentroSalud." limit 1";
                $EjecutarConsultarCentroSalud = mysqli_query($Con->Conexion,$ConsultarCentroSalud) or die("Problemas al consultar filtro Categoria");
                $RetConsultarCentroSalud = mysqli_fetch_assoc($EjecutarConsultarCentroSalud);                  
                $filtros[] = "Centro Salud: ".$RetConsultarCentroSalud['centro_salud'];
              }

              if($ID_OtraInstitucion > 0){
                $Consulta .= " and I.ID_OtraInstitucion = $ID_OtraInstitucion";
                $ConsultarOtraInstitucion = "select Nombre from otras_instituciones where ID_OtraInstitucion = ".$ID_OtraInstitucion." limit 1";
                $EjecutarConsultarOtraInstitucion = mysqli_query($Con->Conexion,$ConsultarOtraInstitucion) or die("Problemas al consultar filtro Categoria");
                $RetConsultarOtraInstitucion = mysqli_fetch_assoc($EjecutarConsultarOtraInstitucion);   
                $filtros[] = "Otra Institucion: ".$RetConsultarOtraInstitucion['Nombre'];
              }


              if($ID_Persona > 0){
                // SE PUEDE ROMPER
                // $Consulta .= " group by M.id_movimiento order by Anio, Mes, B.Barrio, P.domicilio, P.manzana, P.lote, P.familia, P.domicilio, P.apellido, M.id_movimiento";
                $Consulta .= " group by M.id_persona order by P.domicilio, P.apellido, M.id_movimiento";
              }else{
                // SE PUEDE ROMPER
                // $Consulta .= " group by M.id_persona order by Anio, Mes, B.Barrio, P.domicilio, P.manzana, P.lote, P.familia, P.domicilio, P.apellido, M.id_movimiento";
                $Consulta .= " group by M.id_persona order by P.domicilio, P.apellido, M.id_movimiento";
              }

              $Con->CloseConexion();

              // $Consulta .= " group by M.id_persona order by Anio, Mes, B.Barrio, P.domicilio, P.manzana, P.lote, P.familia, P.domicilio, P.apellido, M.id_movimiento";
            
              

              $MensajeError = "No se pudieron consultar los Datos";

              $Etiqueta_Fecha_Inicio = implode("-", array_reverse(explode("-",$Fecha_Inicio)));
              $Etiqueta_Fecha_Fin = implode("-", array_reverse(explode("-",$Fecha_Fin)));             

      	?>
        <center><p class = "LblForm">ENTRE: <?php echo $Etiqueta_Fecha_Inicio." Y ".$Etiqueta_Fecha_Fin; ?></p></center>  
        <!-- <span><i class="fa fa-filter"></i> Filtros </span> -->
        <span> Filtros seleccionados </span>
        <!-- < ?php echo "DEBUG: ".$Consulta; ?>       -->
        <?php
        // echo "DEBUG: ".$Consulta;
          foreach($filtros as $value){
            echo "<span class='etFiltros'>".$value."</span> ";
          }
        
     
        ?>

      </div>
      <div class="col">
      
        <button type = "button" class = "btn btn-danger" onclick = "location.href = 'view_general_new.php'">Atras</button>
        
        <button type="button" class="btn btn-secondary" onclick="enviarImprimir()" >**Imprimir</button>

        <button type="button" class="btn btn-secondary" onclick="enviarImprimirPdf();" > Imprimir</button>
        
      </div>
    
    </div>
      <br>   
      <div class="row">
        <div class="offset-md-3 col-md-6">
          <?php echo NOMBRE_ENTIDAD ?> 
        </div>
      </div>
     <div class = "row">
      <div class = "col-md-12">
        <!-- <
        ?php echo "DEBUG: ".$Consulta; ?> -->
          <!-- Search -->
          
        <div class = "table-responsive" id="tabla-responsive">
          <?php                
              $Con = new Conexion();
              $Con->OpenConexion();

              $tomarRetTodos = array();

              $Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MensajeError." Consulta: ".$Consulta);    
              
              // echo var_dump($Consulta);
              
              /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              // TOMANDO LOS ID DE LOS MOVIMIENTOS PARA LUEGO HACER LA COMPARACION PARA EL PINTADO DE LOS MOTIVOS.
              $ResultadosPrincipal = $Con->ResultSet->fetch_array();
              // echo "DEBUG DATOS IDS: ".var_dump($ResultadosPrincipal);

              $arrIDMovimientos = array();

              // var_dump($ResultadosPrincipal["id_movimiento"]);

              // if($ResultadosPrincipal->num_rows > 1){

              // }

              // foreach($ResultadosPrincipal as $valor){   
              //     // echo var_dump($valor);               
              //     $arrIDMovimientos[] = $value["id_movimiento"];
              //     //TODO: revisar bien esto
              //     // $arrIDMovimientos[] = $value;
              // }

              while($Ret = $Con->ResultSet->fetch_assoc()){
                // echo "DEBUG :".$Ret['id_movimiento'];
                  $arrIDMovimientos[] = $Ret['id_movimiento'];
              }

              // echo "DEBUG IDS:".var_dump($arrIDMovimientos);
              
                
              /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

              if($Con->ResultSet->num_rows == 0){  
                   
              	// echo "<div class = 'col'></div>";
              	echo "<div class = 'col-6'>";
              	echo "<p class = 'TextoSinResultados'>No se encontraron Resultados</p>";
              	echo "</div>";
              	// echo "<div class = 'col'></div>";
              }else{                               
              	$Table = "<table class='table table-fixeder table-bordered table-sm' cellspacing='0' id='tablaMovimientos'>
                             <thead class='thead-dark'>
                              <tr align='center' valign='middle'>
                              <th id='Contenido-Titulo-1'>Barrio</th>
                              <th id='Contenido-Titulo-2'>Direc.</th>
                              <th id='Contenido-Titulo-3' name='datosflia' style='max-width: 50px;'>Mz.</th>
                              <th id='Contenido-Titulo-4' name='datosflia' style='max-width: 50px;'>Lote</th>
                              <th id='Contenido-Titulo-5' name='datosflia' style='max-width: 50px;'>Sublote.</th>
                              <th id='Contenido-Titulo-6'>Persona</th>
                              <th id='Contenido-Titulo-7' style='max-width: 100px;'>Fecha Nac.</th>";                
              } 

              $Tomar_Meses = mysqli_query($Con->Conexion,$Consulta) or die($MensajeError." Consulta: ".$Consulta);

              /* TOMAR LOS MESES ENTRE LAS FECHAS  */
              $MesFecha_Inicio = new DateTime($Fecha_Inicio);
              $MesFecha_Fin = new DateTime($Fecha_Fin);

              $MesesDiferencia = $MesFecha_Inicio->diff($MesFecha_Fin);

              $MesesDiferencia = ($MesesDiferencia->y * 12) + $MesesDiferencia->m + 1;

              $Mes_Actual_Bandera = (int)$MesFecha_Inicio->format("m");
              $Anio_Actual_Bandera = $MesFecha_Inicio->format("y");
              for($i=0; $i < $MesesDiferencia; $i++){
                  if($Mes_Actual_Bandera > 12){
                    $Mes_Actual_Bandera = 01;
                    $Anio_Actual_Bandera++;
                  }
                  $arr[] = $Mes_Actual_Bandera."/".$Anio_Actual_Bandera;
                  $Mes_Actual_Bandera++;
              }    
              
              // echo "DEBUG:".var_dump($arr);


              /*             FIN TOMAR MESES */

              // while($RetMeses = mysqli_fetch_array($Tomar_Meses)){
              // 	$Bandera = 0;
              // 	//COMPARANDO LOS VALORES PARA NO TENER CAMPOS REPETIDOS
              // 	foreach ($arr as $key => $value) {
              // 		$Dato_Nuevo = $RetMeses["Mes"]."/".$RetMeses["Anio"];
              // 		if(strcmp($Dato_Nuevo, $value) == 0){
              // 			$Bandera = 1;              			
              // 		}else{
              // 			$Bandera = 0;              			
              // 		}              		
              // 	}
              //   //ACOMODANDO LAS FECHAS EN UN ARREGLO PARA CREAR LAS FILAS
              //     if($Bandera == 0){
              //       $arr[] = $RetMeses["Mes"]."/".$RetMeses["Anio"];                    
              //     }              	
	              	              
              // }             
              // $arr_reverse = array_reverse($arr);

              // var_dump($Table);
              // array(13) { [0]=> string(5) "12/21" [1]=> string(4) "1/22" [2]=> string(4) "2/22" [3]=> string(4) "3/22"
              //    [4]=> string(4) "4/22" [5]=> string(4) "5/22" [6]=> string(4) "6/22" [7]=> string(4) "7/22"
              //     [8]=> string(4) "8/22" [9]=> string(4) "9/22" [10]=> string(5) "10/22" [11]=> string(5) "11/22" [12]=> string(5) "12/22" }
              foreach ($arr as $key => $value) {                

                  if($value != ""){
                    // TODO: Cambiando de tamaño las columnas
                    $Table .= "<th name='DatosResultados' style='min-width: 225px;'>".$value."</th>";  
                  }              		
                  
              }
              

              // echo "DEBUG:".var_dump($arr);
          // ob_start();   

              $Table .= "</tr>
                    </thead>
                <tbody id='cuerpo-tabla'>";

              
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////  // FIN TABLE HEADER //////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


              //	CREANDO FILTRO MOSTRAR              
              if($Mostrar > 0){ 
                //, P.nro_legajo, P.nro_carpeta
              	$ConsultarTodos = "select P.id_persona, B.Barrio, P.manzana, P.lote, P.familia, P.apellido, P.nombre, P.fecha_nac, P.domicilio
                 from persona P, barrios B, movimiento M
                 where not exists(select * from movimiento M2 where M2.id_persona = P.id_persona) and B.ID_Barrio = P.ID_Barrio and P.estado = 1";

                if($ID_Persona > 0){
                  $ConsultarTodos .= " and P.id_persona = $ID_Persona";
                }

                if($Edad_Desde != null && $Edad_Desde != "" && $Edad_Hasta != null && $Edad_Hasta != ""){                  
                  $ConsultarTodos .= " and P.edad > $Edad_Desde and P.edad < $Edad_Hasta";
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
                      $ConsultarBarrio = "select Barrio from barrios where ID_Barrio = ".$valueBarrio." limit 1";
                      $EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                      $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);   
                      if($key == $Barrio->array_key_first){
                        $filtroBarrios .= " ".$RetConsultarBarrio['Barrio'];   
                      }else{
                        $filtroBarrios .= " - ".$RetConsultarBarrio['Barrio'];   
                      }                                   
                    }
                  }
                  $filtros[] = $filtroBarrios;
                }else{
                  if($Barrio[0] > 0){
                    $ConsultarTodos .= " and P.ID_Barrio = $Barrio[0]";
                    $ConsultarBarrio = "select Barrio from barrios where ID_Barrio = ".$Barrio[0]." limit 1";
                    $EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                    $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
                    $filtros[] = "Barrio: ".$RetConsultarBarrio['Barrio'];
                  }                
                }

                if($ID_Escuela > 0){
                  $ConsultarTodos .= " and P.ID_Escuela = $ID_Escuela";
                }

                if($Trabajo != null && $Trabajo != ""){
                  $ConsultarTodos .= " and P.Trabajo like '%$Trabajo%'";
                }

                if($ID_Persona > 0){
                  $ConsultarTodos .= " group by P.id_movimiento order by P.domicilio, P.apellido, P.nombre";
                }else{
                  $ConsultarTodos .= " group by P.id_persona order by P.domicilio, P.apellido, P.nombre";
                }

                // $ConsultarTodos .= " group by P.id_persona order by P.apellido, P.nombre";

                // echo "DEBUG: ".$ConsultarTodos;
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
                  // $Table .= "<td id='Contenido-1'>".$RetTodos["Barrio"]."</td><td id='Contenido-2'>".$RetTodos["domicilio"]."</td><td id='Contenido-3' name='datosflia' style='max-width: 50px;'>".$RetTodos["manzana"]."</td><td id='Contenido-4' name='datosflia' style='max-width: 50px;'>".$RetTodos["lote"]."</td><td id='Contenido-5' name='datosflia' style='max-width: 50px;'>".$RetTodos["familia"]."</td><td id='Contenido-6'><a href = 'javascript:window.open(\"persona/editar?ID=".$RetTodos["id_persona"]."\",\"Ventana".$RetTodos["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$RetTodos["apellido"].", ".$RetTodos["nombre"]."</a></td><td id='Contenido-7' style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";

                  // $ColSpans = $MesesDiferencia * 270;
                  // $Table .= "<td style='width:".$ColSpans."px'></td>";

                  $RetTodos['tipo'] = "SM";
                  $tomarRetTodos[] = $RetTodos;

              	}
              }

              $EjecutarConsulta2 = mysqli_query($Con->Conexion, $Consulta) or die("Error al consultar datos");
              // while ($Ret = mysqli_fetch_array($Con->ResultSet)) {                     

              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  


              while ($Ret = mysqli_fetch_array($EjecutarConsulta2)) {

                // echo "A";
                
                // echo var_dump($Ret);                
                if($Ret["fecha_nac"] == 'null'){
                  $Fecha_Nacimiento = "Sin Datos";
                }else{
                  $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$Ret["fecha_nac"])));
                }                 
             
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
                /////////////////////////////////// CODIGICANDO EL MUESTREO DE MESES DE UNA FORMA MAS EFICIENTE //////////////////////               

                $ID_Persona_Nuevo = $Ret["id_persona"];  
                // PASAR A TODOS              
                // if($ID_Persona_Nuevo == $ID_Persona_Bandera){                                                 
                //   $Table .= "<tr style='border: 0px;'>";
                //   $Table .= "<td colspan = '7' style='border: 0px;'></td>";   
                  
                // }else{                               
                //   $Table .= "<tr class='Datos'>";
                //   $Table .= "<td id='Contenido-1'>".$Ret["Barrio"]."</td><td id='Contenido-2'>".$Ret["domicilio"]."</td><td id='Contenido-3' name='datosflia' style='max-width: 50px;'>".$Ret["manzana"]."</td><td id='Contenido-4' name='datosflia' style='max-width: 50px;'>".$Ret["lote"]."</td><td id='Contenido-5' name='datosflia' style='max-width: 50px;'>".$Ret["familia"]."</td><td id='Contenido-6'><a href = 'javascript:window.open(\"persona/editar?ID=".$Ret["id_persona"]."\",\"Ventana".$Ret["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$Ret["apellido"].", ".$Ret["nombre"]."</a></td><td id='Contenido-7' style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";
                // }
                
             
                if($ID_Persona_Nuevo !== $ID_Persona_Bandera){
                  // foreach ($arr as $key => $value) {
                  //     $Separar = explode("/",$value);
                  //     $Mes = $Separar[0];
                  //     $Anio = $Separar[1];                                          
                  //     $Consultar_Movimientos_Persona = "select * from movimiento where id_persona = ".$Ret["id_persona"]." and MONTH(fecha) = ".$Mes." and YEAR(fecha) like '%".$Anio."'";
                      

                  //     $Tomar_Movimientos_Persona = mysqli_query($Con->Conexion,$Consultar_Movimientos_Persona) or die($MensajeErrorConsultar_Mov_Persona." - ".$Consultar_Movimientos_Persona);

                  // echo var_dump($arr);
                  
                    // PASAR A TODOS
                    // $Table .= "<td name='DatosResultados' style='min-width:270px'><div class = 'row'>";                    

                    // while($Ret_Movimientos_Persona = mysqli_fetch_assoc($Tomar_Movimientos_Persona)){	                	

                    //   $Num_Movimientos_Persona = mysqli_num_rows($Tomar_Movimientos_Persona);
                      //echo $Ret_Movimientos_Persona['id_persona']." - ".$Ret_Movimientos_Persona['id_movimiento'];
                      
                      
                          // $Consultar_Datos_Movimientos = "select M.id_movimiento, MONTH(M.fecha) as 'Mes', YEAR(M.fecha) as 'Anio', M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where (M.motivo_1 = MT.id_motivo or M.motivo_2 = MT.id_motivo or M.motivo_3 = MT.id_motivo) and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret_Movimientos_Persona['id_movimiento']." and M.id_persona = ".$Ret_Movimientos_Persona['id_persona']." group by M.id_movimiento";	                      



                          // $MensajeErrorConsultar_Datos_Movimientos = "No se pudieron consultar los datos del movimiento";
                          // $Tomar_Datos_Movimientos = mysqli_query($Con->Conexion,$Consultar_Datos_Movimientos) or die($MensajeErrorConsultar_Datos_Movimientos." - ".$Consultar_Datos_Movimientos);
                          // $Ret_Datos_Movimiento = mysqli_fetch_assoc($Tomar_Datos_Movimientos);

                              ////////////////////////////////////////////////////////////////                                             
                              ////////////////////////////////////////////////////////////////
                              ////////////////////////////////////////////////////////////////
                              
                              // if($Ret_Datos_Movimiento["motivo_1"] > 1){
                              //   if($ID_Motivo > 0){
                              //     if($ID_Motivo == $Ret_Datos_Movimiento["motivo_1"]){
                              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
    
                              //       //echo $ConsultarCodyColor;               
    
                              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
    
                              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
    
                              //       $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                              //     }
                              //   }elseif($ID_Motivo2 > 0){
                              //     if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_1"]){
                              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
    
                              //       //echo $ConsultarCodyColor;               
    
                              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
    
                              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
    
                              //       $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                              //     }
                              //   }elseif($ID_Motivo3 > 0){
                              //     if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_1"]){
                              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
    
                              //       //echo $ConsultarCodyColor;               
    
                              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
    
                              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
    
                              //       $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                              //     }
                              //   }else{                                                        
                              //     $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                              //     $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";

                              //     //echo $ConsultarCodyColor;               

                              //     $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);

                              //     $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);

                              //     $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; padding: 0px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";
                              //   }
                              // }

                              // if($Ret_Datos_Movimiento["motivo_2"] > 1){
                              //   if($ID_Motivo > 0){
                              //     if($ID_Motivo == $Ret_Datos_Movimiento["motivo_2"]){
                              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
    
                              //       //echo $ConsultarCodyColor;               
    
                              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_2"]);
    
                              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
    
                              //       $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                              //     }
                              //   }elseif($ID_Motivo2 > 0){
                              //     if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_2"]){
                              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
    
                              //       //echo $ConsultarCodyColor;               
    
                              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_2"]);
    
                              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
    
                              //       $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                              //     }
                              //   }elseif($ID_Motivo3 > 0){
                              //     if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_2"]){
                              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
    
                              //       //echo $ConsultarCodyColor;               
    
                              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_2"]);
    
                              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
    
                              //       $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                              //     }
                              //   }else{ 
                              //     $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                              //     $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";


                              //     $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor);

                              //     $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
        
                                  

                              //     $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"]."; text-align= center;'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";
                              //   }
                              // }


                              // if($Ret_Datos_Movimiento["motivo_3"] > 1){
                              //   if($ID_Motivo > 0){
                              //     if($ID_Motivo == $Ret_Datos_Movimiento["motivo_3"]){
                              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
    
                              //       //echo $ConsultarCodyColor;               
    
                              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_3"]);
    
                              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
    
                              //       $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                              //     }
                              //   }elseif($ID_Motivo2 > 0){
                              //     if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_3"]){
                              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
    
                              //       //echo $ConsultarCodyColor;               
    
                              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_3"]);
    
                              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
    
                              //       $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                              //     }
                              //   }elseif($ID_Motivo3 > 0){
                              //     if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_3"]){
                              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
    
                              //       //echo $ConsultarCodyColor;               
    
                              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_3"]);
    
                              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
    
                              //       $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                              //     }
                              //   }else{ 
                              //     $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                              //     $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";


                              //     $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor);

                              //     $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
                                  

                              //     $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";
                              //   }
                              // }     
                              ////////////////////////////////////////////////////////////////                                             
                              ////////////////////////////////////////////////////////////////
                              ////////////////////////////////////////////////////////////////
                                                                                                              
                    // }   
                    
                    // $Table .= "</div></td>";

                    $ID_Persona_Bandera = $Ret["id_persona"]; 
                    
                    
                                                                  
                }
                $Ret['tipo'] = "CM";
                $tomarRetTodos[] = $Ret;
              }

              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




              // SE PINTO ESTE CIERRE
              // $Table .= "</tr>";

              //////////////////////////////////////////////////////////////////////////////////
              // ACA REBERIA GRAFICAR EL ARREGLO DE LOS REGISTROS UNIDOS
              //////////////////////////////////////////////////////////////////////////////////

              
              foreach ($tomarRetTodos as $clave => $reg) {
                $regdomicilio[$clave] = $reg['domicilio'];                
              }

              array_multisort($regdomicilio, SORT_DESC, $tomarRetTodos);

              // echo "DEBUG 1: ".var_dump($tomarRetTodos);    

              foreach($tomarRetTodos as $clave => $RetTodos){                
                // echo var_dump($RetTodos);
                // echo "<br>";
                if($RetTodos["fecha_nac"] == 'null'){
                  $Fecha_Nacimiento = "Sin Datos";
                }else{
                  $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$RetTodos["fecha_nac"])));
                }

                if($RetTodos["tipo"] == "SM"){
                  // echo "Entra aca SM";
                  $Table .= "<tr class='SinMovimientos Datos'>";
                  $Table .= "<td id='Contenido-1'>".$RetTodos["Barrio"]."</td>
                  <td id='Contenido-2'>".$RetTodos["domicilio"]."</td>
                  <td id='Contenido-3' name='datosflia' style='max-width: 50px;'>".$RetTodos["manzana"]."</td>
                  <td id='Contenido-4' name='datosflia' style='max-width: 50px;'>".$RetTodos["lote"]."</td>
                  <td id='Contenido-5' name='datosflia' style='max-width: 60px;'>".$RetTodos["familia"]."</td>
                  <td id='Contenido-6'><a href = 'javascript:window.open(\"persona/editar?ID=".$RetTodos["id_persona"]."\",\"Ventana".$RetTodos["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$RetTodos["apellido"].", ".$RetTodos["nombre"]."</a></td>
                  <td id='Contenido-7' style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";
  
                  $ColSpans = $MesesDiferencia * 270;
                  $Table .= "<td name='DatosSinResultados' style='width:".$ColSpans."px'></td>";                
                }else{
                  // echo "Entra aca CM";
                  // ACA IRIA TODO LO OTRO PARA LOS QUE SI TIENEN MOVIMIENTOS
                  $ID_Persona_Nuevo = $RetTodos["id_persona"];

                  // POSIBLE BUG
                  // if($ID_Persona_Nuevo == $ID_Persona_Bandera){                                                 
                  //   $Table .= "<tr style='border: 0px;'>";
                  //   $Table .= "<td colspan = '7' style='border: 0px;'></td>";                     
                  // }else{                               
                  //   $Table .= "<tr class='Datos'>";
                  //   $Table .= "<td id='Contenido-1'>".$RetTodos["Barrio"]."</td><td id='Contenido-2'>".$RetTodos["domicilio"]."</td><td id='Contenido-3' name='datosflia' style='max-width: 50px;'>".$RetTodos["manzana"]."</td><td id='Contenido-4' name='datosflia' style='max-width: 50px;'>".$RetTodos["lote"]."</td><td id='Contenido-5' name='datosflia' style='max-width: 50px;'>".$RetTodos["familia"]."</td><td id='Contenido-6'><a href = 'javascript:window.open(\"persona/editar?ID=".$RetTodos["id_persona"]."\",\"Ventana".$RetTodos["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$RetTodos["apellido"].", ".$RetTodos["nombre"]."</a></td><td id='Contenido-7' style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";
                  // }

                  $Table .= "<tr class='Datos'>";
                  $Table .= "<td id='Contenido-1'>".$RetTodos["Barrio"]."</td>
                  <td id='Contenido-2'>".$RetTodos["domicilio"]."</td>
                  <td id='Contenido-3' name='datosflia' style='max-width: 50px;'>".$RetTodos["manzana"]."</td>
                  <td id='Contenido-4' name='datosflia' style='max-width: 50px;'>".$RetTodos["lote"]."</td>
                  <td id='Contenido-5' name='datosflia' style='max-width: 60px;'>".$RetTodos["familia"]."</td>
                  <td id='Contenido-6'><a href = 'javascript:window.open(\"persona/editar?ID=".$RetTodos["id_persona"]."\",\"Ventana".$RetTodos["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$RetTodos["apellido"].", ".$RetTodos["nombre"]."</a></td>
                  <td id='Contenido-7' style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";

                  // if($ID_Persona_Nuevo !== $ID_Persona_Bandera){
                    foreach ($arr as $key => $value) {
                        $Separar = explode("/",$value);
                        $Mes = $Separar[0];
                        $Anio = $Separar[1];                                          
                        $Consultar_Movimientos_Persona = "select * from movimiento where id_persona = ".$RetTodos["id_persona"]." and MONTH(fecha) = ".$Mes." and YEAR(fecha) like '%".$Anio."' order by fecha";

                        echo "<br> DEBUG CONSULTAR MOVIMIENTO: ".var_dump($Consultar_Movimientos_Persona);
                        
  
                        $Tomar_Movimientos_Persona = mysqli_query($Con->Conexion,$Consultar_Movimientos_Persona) or die($MensajeErrorConsultar_Mov_Persona." - ".$Consultar_Movimientos_Persona);

                        // TODO: CAMBIANDO TOAMAÑO DE COLUMNAS
                        $Table .= "<td name='DatosResultados' style='min-width:225px'><div class = 'row'>";                  //250   

                        $Num_Movimientos_Persona = mysqli_num_rows($Tomar_Movimientos_Persona);                        

                        while($Ret_Movimientos_Persona = mysqli_fetch_assoc($Tomar_Movimientos_Persona)){	                	                                                    

                          $Consultar_Datos_Movimientos = "select M.id_movimiento, MONTH(M.fecha) as 'Mes', YEAR(M.fecha) as 'Anio', M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where (M.motivo_1 = MT.id_motivo or M.motivo_2 = MT.id_motivo or M.motivo_3 = MT.id_motivo) and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret_Movimientos_Persona['id_movimiento']." and M.id_persona = ".$Ret_Movimientos_Persona['id_persona']." group by M.id_movimiento order by M.fecha";	                      

                          echo " <br> DEBUG CONSULTAR MOVIMIENTO: ".var_dump($Consultar_Datos_Movimientos);

                          $MensajeErrorConsultar_Datos_Movimientos = "No se pudieron consultar los datos del movimiento";
                          $Tomar_Datos_Movimientos = mysqli_query($Con->Conexion,$Consultar_Datos_Movimientos) or die($MensajeErrorConsultar_Datos_Movimientos." - ".$Consultar_Datos_Movimientos);
                          $Ret_Datos_Movimiento = mysqli_fetch_assoc($Tomar_Datos_Movimientos);

                          // echo "DEBUG ID_Motivo2 : ".$ID_Motivo2;
                          // if($Ret_Datos_Movimiento["motivo_1"] == $ID_Motivo2 || $Ret_Datos_Movimiento["motivo_2"] == $ID_Motivo2 || $Ret_Datos_Movimiento["motivo_3"] == $ID_Motivo2){
                          //   echo "DEBUG SI HAY: ".var_dump($ID_Motivo2);
                          //   echo "DATOS: ".var_dump($Ret_Datos_Movimiento);
                          //   if($Ret_Datos_Movimiento["motivo_1"] > 1){
                          //     echo "ENTRA AQUI 1";
                          //     if($ID_Motivo2 > 0){
                          //       echo "ENTRA AQUI 2";
                          //       if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_1"]){
                          //         echo "ENTRA AQUI 3";
                          //         $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                          //         echo $ConsultarCodyColor;
                          //       }
                          //     }
                          //   }
                          // }
                          

                              ////////////////////////////////////////////////////////////////                                             
                              ////////////////////////////////////////////////////////////////
                              ////////////////////////////////////////////////////////////////
                              
                              if($Ret_Datos_Movimiento["motivo_1"] > 1){
                                if($ID_Motivo > 0){
                                  if($ID_Motivo == $Ret_Datos_Movimiento["motivo_1"]){
                                    $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo 
									from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                                    $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
    
                                                  
    
                                    $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
    
                                    $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);

                                    // echo "DEBUG: ".var_dump($RetMotivo);
									
    
                                    $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px;'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' >".$RetMotivo["codigo"]."</span></center></span></a></div>";                                  
                                  }
                                }
                                if($ID_Motivo2 > 0){
                                  if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_1"]){
                                    $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                                    $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
  
                                                  
    
                                    $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
    
                                    $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);

                                    // echo "DEBUG: ".var_dump($RetMotivo);
    
                                    $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px;'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria'>".$RetMotivo["codigo"]."</span></center></span></a></div>";                                  
                                  }
                                }
                                if($ID_Motivo3 > 0){
                                  if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_1"]){
                                    $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                                    $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
    
                                    // echo $ConsultarCodyColor;               
     
                                    $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
    
                                    $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);

                                    // echo "DEBUG: ".var_dump($RetMotivo);
    
                                    $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; '>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria'>".$RetMotivo["codigo"]."</span></center></span></a></div>";                                  
                                  }
                                }
                                if($ID_Motivo == 0 && $ID_Motivo2 == 0 && $ID_Motivo3 == 0){                                                        
                                  $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                                  $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
 
                                  //echo $ConsultarCodyColor;               
     
                                  $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);

                                  $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);

                                  // echo "DEBUG NOT: ".var_dump($RetMotivo);

                                  $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; padding: 0px;'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria'>".$RetMotivo["codigo"]."</span></center></span></a></div>";
                                }
                              }

                              if($Ret_Datos_Movimiento["motivo_2"] > 1){
                                if($ID_Motivo > 0){
                                  if($ID_Motivo == $Ret_Datos_Movimiento["motivo_2"]){
                                    $ConsultarCodyColor2 = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                                    $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";
           
    
                                    $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2." - ".$ConsultarCodyColor2." valor:".$Ret_Datos_Movimiento["motivo_2"]);
    
                                    $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);
    
                                    $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; padding: 0px;'>".$RetMotivo2["Forma_Categoria"]."<center><span class='nombreCategoria'>".$RetMotivo2["codigo"]."</span></center></span></a></div>";                                  
                                  }
                                }
                                if($ID_Motivo2 > 0){
                                  if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_2"]){
                                    $ConsultarCodyColor2 = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                                    $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";
         
    
                                    $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2." - ".$ConsultarCodyColor2." valor:".$Ret_Datos_Movimiento["motivo_2"]);
    
                                    $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);
    
                                    $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; '>".$RetMotivo2["Forma_Categoria"]."<center><span class='nombreCategoria'>".$RetMotivo2["codigo"]."</span></center></span></a></div>";                                  
                                  }
                                }
                                if($ID_Motivo3 > 0){
                                  if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_2"]){
                                    $ConsultarCodyColor2 = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                                    $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";
  
    
                                    $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2." - ".$ConsultarCodyColor2." valor:".$Ret_Datos_Movimiento["motivo_2"]);
    
                                    $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);
    
                                    $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; '>".$RetMotivo2["Forma_Categoria"]."<center><span class='nombreCategoria'>".$RetMotivo2["codigo"]."</span></center></span></a></div>";                                  
                                  }
                                }
                                if($ID_Motivo == 0 && $ID_Motivo2 == 0 && $ID_Motivo3 == 0){ 
                                  $ConsultarCodyColor2 = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                                  $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";


                                  $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2." - ".$ConsultarCodyColor2);

                                  $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);
        
                                  

                                  $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; text-align= center;'>".$RetMotivo2["Forma_Categoria"]."<center><span class='nombreCategoria'>".$RetMotivo2["codigo"]."</span></center></span></a></div>";
                                }
                              }


                              if($Ret_Datos_Movimiento["motivo_3"] > 1){
                                if($ID_Motivo > 0){
                                  if($ID_Motivo == $Ret_Datos_Movimiento["motivo_3"]){
                                    $ConsultarCodyColor3 = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                                    $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos de los Movimientos";
    
                                    // echo $ConsultarCodyColor;               
    
                                    $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3." - ".$ConsultarCodyColor3." valor:".$Ret_Datos_Movimiento["motivo_3"]);
    
                                    $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);
    
                                    $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px;>".$RetMotivo3["Forma_Categoria"]."<center><span class='nombreCategoria' >".$RetMotivo3["codigo"]."</span></center></span></a></div>";                                  
                                  }
                                }
                                if($ID_Motivo2 > 0){
                                  if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_3"]){
                                    $ConsultarCodyColor3 = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                                    $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos de los Movimientos";
    
                                    // echo $ConsultarCodyColor;               
    
                                    $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3." - ".$ConsultarCodyColor3." valor:".$Ret_Datos_Movimiento["motivo_3"]);
    
                                    $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);
    
                                    $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px;>".$RetMotivo3["Forma_Categoria"]."<center><span class='nombreCategoria'>".$RetMotivo3["codigo"]."</span></center></span></a></div>";                                  
                                  }
                                }
                                if($ID_Motivo3 > 0){
                                  if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_3"]){
                                    $ConsultarCodyColor3 = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                                    $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos de los Movimientos";
    
                                    // echo $ConsultarCodyColor;               
    
                                    $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3." - ".$ConsultarCodyColor3." valor:".$Ret_Datos_Movimiento["motivo_3"]);
    
                                    $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);
    
                                    $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; >".$RetMotivo3["Forma_Categoria"]."<center><span class='nombreCategoria'>".$RetMotivo3["codigo"]."</span></center></span></a></div>";                                  
                                  }
                                }
                                if($ID_Motivo == 0 && $ID_Motivo2 == 0 && $ID_Motivo3 == 0){ 
                                  $ConsultarCodyColor3 = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                                  $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";


                                  $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3." - ".$ConsultarCodyColor3);

                                  $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);
                                  

                                  $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"/movimiento?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; >".$RetMotivo3["Forma_Categoria"]."<center><span class='nombreCategoria'>".$RetMotivo3["codigo"]."</span></center></span></a></div>";
                                }
                              }     
                              ////////////////////////////////////////////////////////////////                                             
                              ////////////////////////////////////////////////////////////////
                              ////////////////////////////////////////////////////////////////



                        }   
                    
                        $Table .= "</div></td>";
    
                        $ID_Persona_Bandera = $RetTodos["id_persona"];                     
                  // POSIBLEMENTE OBSOLETO      
                  // }

                } 
                
                 $Table .= "</tr>";

              }


              //////////////////////////////////////////////////////////////////////////////////
              //////////////////////////////////////////////////////////////////////////////////

              }
            
              $Table .= "</tbody>";
              // echo "DEBUG: ".var_dump($tomarRetTodos);
              
              $Table .= "</table>";      

               if($Con->ResultSet->num_rows > 0){
                  echo $Table;                                    
               }                                   
               
               $Con->CloseConexion();
            }else{
              echo "No se pudo obtener el año";
            }

            
          ?>
        </div>           

  </div>
</div>
</div>  




<script>
    (function() {
      var tabla = document.getElementById("tabla-responsive");
      tabla.scrollLeft = '9999';
    })();

   function toggleZoomScreen() {
       document.body.style.zoom = "55%"; //
       var Tabla = document.getElementById("cuerpo-tabla");
       Tabla.style.height = "1800px";

       var DivTabla = document.getElementById("tabla-responsive");
       DivTabla.style.height = "1800px";

       var elements = document.getElementsByClassName("Datos");
       for (var i = 0; i < elements.length; i++) {
        var element = elements[i];
        element.style.fontSize = "18px";
      }

      var TTH = document.getElementsByTagName("th");
      var TTD = document.getElementsByTagName("td");
      for (var i = 0; i < TTH.length; i++){
        TTH[i].removeAttribute("style");
        TTH[i].setAttribute("min-width","400px");
        TTH[i].setAttribute("style","font-size: 24px;");        
      }

      for (var i = 0; i < TTD.length; i++){
        TTD[i].removeAttribute("style");
        TTD[i].setAttribute("min-width","400px");        
      }

      var DatosFlia = document.getElementsByName("datosflia");
      for (var i = 0; i < DatosFlia.length; i++){
         DatosFlia[i].removeAttribute("min-width");         
         DatosFlia[i].setAttribute("style","max-width: 50px; font-size: 24px;");    //50
      }

      var DatosResultados = document.getElementsByName("DatosResultados");
      for (var i = 0; i < DatosResultados.length; i++){
         // DatosResultados[i].removeAttribute("min-width");         
         DatosResultados[i].setAttribute("style","min-width: 250px; font-size: 28px;");   
      }

      var DatosSinResultados = document.getElementsByName("DatosSinResultados");
      for (var i = 0; i < DatosSinResultados.length; i++){
         // DatosResultados[i].removeAttribute("min-width");         
         DatosSinResultados[i].setAttribute("style","min-width: 82%;");   
      }
      
      
      // DTR.setAttribute("width","400px");

   } 

   function toggleZoomScreenNormal() {
       document.body.style.zoom = "normal";
       var Tabla = document.getElementById("cuerpo-tabla");
       Tabla.style.height = "480px";

       var DivTabla = document.getElementById("tabla-responsive");
       DivTabla.style.height = "480px";

       var elements = document.getElementsByClassName("Datos");
       for (var i = 0; i < elements.length; i++) {
        var element = elements[i];
        element.style.fontSize = "18px";
      }

      var TTH = document.getElementsByTagName("th");
      var TTD = document.getElementsByTagName("td");
      for (var i = 0; i < TTH.length; i++){
        TTH[i].removeAttribute("min-width");
        TTH[i].removeAttribute("style");                
      }

      for (var i = 0; i < TTD.length; i++){
        TTD[i].removeAttribute("style");
        TTD[i].setAttribute("min-width","400px");        
      }

      var DatosFlia = document.getElementsByName("datosflia");
      for (var i = 0; i < DatosFlia.length; i++){
         DatosFlia[i].removeAttribute("min-width");         
         DatosFlia[i].setAttribute("style","max-width: 50px;");   
      }

      var DatosResultados = document.getElementsByName("DatosResultados");
      for (var i = 0; i < DatosResultados.length; i++){
         // DatosResultados[i].removeAttribute("min-width");         
         DatosResultados[i].setAttribute("style","min-width: 250px;");   
      }

      var DatosSinResultados = document.getElementsByName("DatosSinResultados");
      for (var i = 0; i < DatosSinResultados.length; i++){
         // DatosResultados[i].removeAttribute("min-width");         
         DatosSinResultados[i].setAttribute("style","min-width: 82%;");   
      }
   } 



   
   function enviarImprimir() {
     alert("llega");
     

    var ficha =document.getElementById("ContenidoTabla");
    // document.getElementById("tabla-responsive");
   
	  var ventimp = window.open(' ', 'popimpr');  
	  ventimp.document.write(ficha.innerHTML);
	  ventimp.document.close();
	  ventimp.print( );
	  ventimp.close();
    
      
    /*
       const $elementoParaConvertir = document.body;        
        alert($elementoParaConvertir);   
              html2pdf()
                  .set({
                      margin: 1,
                      filename: 'documento.pdf',
                      image: {
                          type: 'jpeg',
                          quality: 0.98
                      },
                      html2canvas: {
                          scale: 3, // A mayor escala, mejores gráficos, pero más peso
                          letterRendering: true,
                      },
                      jsPDF: {
                          unit: "in",
                          format: "a3",
                          orientation: 'portrait' // landscape o portrait
                      }
                  })
                  .from($elementoParaConvertir)
                  .save()
                  .catch(err => console.log(err));      
      */
            
    }


   function enviarImprimir_222() {     
     var tablaMovimientos = document.getElementById("tabla-responsive");
     var tablaEnc = btoa(unescape(encodeURIComponent(tablaMovimientos.innerHTML)));   

     var consulta = '<?php echo base64_encode($Consulta); ?>';
     var fechaInicio = '<?php echo base64_encode($Fecha_Inicio) ?>';
     var fechaFin = '<?php echo base64_encode($Fecha_Fin) ?>';

    console.log(consulta);

     var arrTabla = [];
     arrTabla = tablaEnc;       
     console.log(arrTabla);   
      
     location.href='pruebas_PDF.php?consulta='+consulta+'&fechaInicio='+fechaInicio+'&fechaFin='+fechaFin;

     //location.href='Controladores/export_excel.php?consulta='+consulta+'&fechaInicio='+fechaInicio+'&fechaFin='+fechaFin;

   }

   function enviarImprimirPdf(){   

    console.log("imprimir tabla")
    var tablaMovimientos = document.getElementById("tabla-responsive");

    // var tablaMovimientos = document.getElementById("tablaMovimientos");          
    // let data={"tabla": tablaMovimientos };
    console.log(data);

   // location.href='pruebas_PDF.php?tabla='+tablaMovimientos;


    $.ajax({
        type: "POST",
        dataType: "html",
        contentType: "application/x-www-form-urlencoded",             
        cache: false,
        // data: data,
        data:"tabla=" + tablaMovimientos,
        url: "pruebas_PDF.php",
        async: false,
        cache: false,                    
        success: function(res){   
          console.log(res);
          location.href='pruebas_PDF.php';
        },
        error:function(){                
            alert("error ");
        }   
    });         
  }
  
   var tituloBarrio = document.getElementById("Contenido-Titulo-1");
   var tituloDirec = document.getElementById("Contenido-Titulo-2");
   var tituloMz = document.getElementById("Contenido-Titulo-3");
   var tituloLote = document.getElementById("Contenido-Titulo-4");
   var tituloFlia = document.getElementById("Contenido-Titulo-5");
   var tituloPersona = document.getElementById("Contenido-Titulo-6");
   var tituloFechaNac = document.getElementById("Contenido-Titulo-7");

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
</body>
</html>