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
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
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
  <script>
		function mostrar() {
			var ContenidoMenu = document.getElementById("ContenidoMenu");
		    ContenidoMenu.setAttribute("class","col-md-2");
		    document.getElementById("sidebar").style.width = "200px";
		    //document.getElementById("ContenidoTabla").style.marginLeft = "300px";
		    var ContenidoTabla = document.getElementById("ContenidoTabla");
		    ContenidoTabla.setAttribute("class","col-md-10");
		    document.getElementById("abrir").style.display = "none";
		    document.getElementById("cerrar").style.display = "inline";
		}

		function ocultar() {		    
		    var ContenidoMenu = document.getElementById("ContenidoMenu");
		    ContenidoMenu.setAttribute("class","col-md-1");
		    document.getElementById("sidebar").style.width = "5%";
		    //document.getElementById("ContenidoTabla").style.marginLeft = "0";
		    var ContenidoTabla = document.getElementById("ContenidoTabla");
		    ContenidoTabla.setAttribute("class","col-md-11");
		    document.getElementById("abrir").style.display = "inline";
		    document.getElementById("cerrar").style.display = "none";
		}

		
  </script>
  <style>
    /*  table thead tr th{
        background-color: #ccc; 
        position: sticky;
        top: 0;
        z-index: 100; 
        display: inline-block;
      }*/

      .table-responsive{
        height: 480px;
        width: 98%;
        overflow-y: hidden;
      }

      .table-fixeder{
        width: max-content; 
        height: 470px;             
      }

      .table-fixeder thead{
        width: 100%;                
      }

      .table-fixeder tbody {
        height: 400px;
        overflow-y: auto;
        overflow-x: hidden;
        width: 100%;
      }

      /*.table-fixeder td,*/
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
        height: 50px;         
      }

      .table-fixeder tbody tr td .Datos,
      .table-fixeder thead tr th .Datos{
        min-width: 200px;
        height: 50px;
      }

      .Datos{
        font-size: 12px;
        font-weight: bold;
      }

      .SinMovimientos td{
        background-color: #ccc;
      }

      /*.table-fixeder .sticky{  
        display: unset;              
        position: sticky;
        left: 0px;
        background-color: #fff;        
        z-index: 100;
      }

      .table-fixeder .sticky-2{  
        display: unset;          
        position: sticky;
        left: 150px;
        background-color: #fff;        
        z-index: 100;
      }*/
    </style>  
</head>
<body>
<div class = "row margin-right-cero">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_REPORTE_GRAFICO);
  ?>
  <div class = "col-md-10" id="ContenidoTabla">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Movimientos</p>
      </div>
      <div class="col"></div>
    </div><br>
    <div class="row">
      <div class="col">
        <button class = "btn btn-info btn-sm" onClick="toggleZoomScreen()">Zoom +</button> <button class = "btn btn-info btn-sm" onClick="toggleZoomScreenNormal()">Zoom -</button>
      </div>
      <div class="col-6">
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
              $ID_Motivo = $_REQUEST["ID_Motivo"];
              $ID_Categoria = $_REQUEST["ID_Categoria"]; 
              $ID_Escuela = $_REQUEST["ID_Escuela"];
              $Trabajo = $_REQUEST["Trabajo"];
              $Mostrar = $_REQUEST["Mostrar"];
              $ID_CentroSalud = $_REQUEST["ID_CentroSalud"];

          	$Consulta = "select M.id_movimiento, M.id_persona, MONTH(M.fecha) as 'Mes', YEAR(M.fecha) as 'Anio', B.Barrio, P.manzana, P.lote, P.familia, P.apellido, P.nombre, P.fecha_nac, P.domicilio from movimiento M, persona P, barrios B, motivo MT, categoria C, centros_salud CS where M.id_persona = P.id_persona and B.ID_Barrio = P.ID_Barrio and M.id_centro = CS.id_centro and M.estado = 1 and P.estado = 1 and MT.estado = 1 and C.estado = 1 and M.fecha between '$Fecha_Inicio' and '$Fecha_Fin'"; 
                           

              if($ID_Persona > 0){
                $Consulta .= " and P.id_persona = $ID_Persona";
              }

              if($Edad_Desde != null && $Edad_Desde != "" && $Edad_Hasta != null && $Edad_Hasta != ""){
                $Consulta .= " and P.edad between $Edad_Desde and $Edad_Hasta";
              }

              if($Domicilio != null && $Domicilio != ""){
                $Consulta .= " and P.domicilio like '%$Domicilio%'";
              }

              if($Manzana != null && $Manzana != ""){
                $Consulta .= " and P.manzana = '$Manzana'";
              }

              if($Lote != null && $Lote != ""){
                $Consulta .= " and P.lote = $Lote";
              }

              if($Familia != null && $Familia != ""){
                $Consulta .= " and P.familia = $Familia";
              }

              if($Barrio > 0){
                $Consulta .= " and P.ID_Barrio = $Barrio";
              }

              if($ID_Escuela > 0){
                $Consulta .= " and P.ID_Escuela = $ID_Escuela";
              }

              if($Trabajo != null && $Trabajo != ""){
                $Consulta .= " and P.Trabajo like '%$Trabajo%'";
              }

              if($ID_Motivo > 0){
                $Consulta .= " and M.motivo_1 = $ID_Motivo or M.motivo_2 = $ID_Motivo or M.motivo_3 = $ID_Motivo";
              }

              if($ID_Categoria > 0){
                $Consulta .= " and  (M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria) or (M.motivo_2 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria) or (M.motivo_3 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria)";
              }

              if($ID_CentroSalud > 0){
                $Consulta .= " and CS.id_centro = $ID_CentroSalud";
              }


              $Consulta .= " group by M.id_persona order by Anio, Mes, B.Barrio, P.domicilio, P.manzana, P.lote, P.familia, P.domicilio, P.apellido, M.id_movimiento";
            
              

              $MensajeError = "No se pudieron consultar los Datos";

              $Etiqueta_Fecha_Inicio = implode("-", array_reverse(explode("-",$Fecha_Inicio)));
              $Etiqueta_Fecha_Fin = implode("-", array_reverse(explode("-",$Fecha_Fin)));             

      	?>
        <center><p class = "LblForm">ENTRE: <?php echo $Etiqueta_Fecha_Inicio." Y ".$Etiqueta_Fecha_Fin; ?></p></center>        
      </div>
      <div class="col"></div>
    </div>
    <br>   
     <div class = "row">
      <div class = "col-md-12">
          <!-- Search -->
        <div class = "table-responsive" id="tabla-responsive">
          <?php                
              $Con = new Conexion();
              $Con->OpenConexion();

              $Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MensajeError." Consulta: ".$Consulta);                           

              if($Con->ResultSet->num_rows == 0){
              	echo "<div class = 'col'></div>";
              	echo "<div class = 'col-6'>";
              	echo "<p class = 'TextoSinResultados'>No se encontraron Resultados</p><center><button class = 'btn btn-danger' onClick = 'location.href= \"view_general_new.php\"'>Atras</button></center>";
              	echo "</div>";
              	echo "<div class = 'col'></div>";
              }else{                
              	$Table = "<table style='table-layout: fixed;' class='table table-fixeder table-bordered table-sm' cellspacing='0'
   id='dtVerticalScrollExample'><thead style='z-index: 1;' class='thead-dark'><tr align='center' valign='middle'><th style='position: -webkit-sticky; position: sticky; left: 0px; z-index: 2;'>Barrio</th><th class='sticky' style='position: -webkit-sticky; position: sticky; left: 150px; z-index: 2;'>Direc.</th><th name='datosflia' style='max-width: 50px; position: -webkit-sticky; position: sticky; left: 300px; z-index: 2;'>Mz.</th><th name='datosflia' style='max-width: 50px;  position: -webkit-sticky; position: sticky; left: 350px; z-index: 2;'>Lote</th><th name='datosflia' style='max-width: 50px; position: -webkit-sticky; position: sticky; left: 400px; z-index: 2;'>Flia.</th><th class='sticky-2' style='position: -webkit-sticky; position: sticky; left: 450px; z-index: 2;'>Persona</th><th style='max-width: 100px; position: -webkit-sticky; position: sticky; left: 600px; z-index: 2;'>Fecha Nac.</th>";
              } 

              $Tomar_Meses = mysqli_query($Con->Conexion,$Consulta) or die($MensajeError." Consulta: ".$Consulta);

              /* TOMAR LOS MESES ENTRE LAS FECHAS  */
              $MesFecha_Inicio = new DateTime($Fecha_Inicio);
              $MesFecha_Fin = new DateTime($Fecha_Fin);

              $MesesDiferencia = $MesFecha_Inicio->diff($MesFecha_Fin);

              $MesesDiferencia = ($MesesDiferencia->y * 12) + $MesesDiferencia->m + 1;


              // $arr[] = $MesFecha_Inicio->format("m")."/".$MesFecha_Inicio->format("y");
              $Mes_Actual_Bandera = (int)$MesFecha_Inicio->format("m");
              $Anio_Actual_Bandera = $MesFecha_Inicio->format("yy");
              for($i=0; $i < $MesesDiferencia; $i++){
                  if($Mes_Actual_Bandera > 12){
                    $Mes_Actual_Bandera = 01;
                    $Anio_Actual_Bandera++;
                  }
                  $arr[] = $Mes_Actual_Bandera."/".$Anio_Actual_Bandera;
                  $Mes_Actual_Bandera++;
                  // echo $Mes_Actual_Bandera." ".$Anio_Actual_Bandera;
                  // echo var_dump($arr);
                  // echo var_dump($MesesDiferencia);
              }              


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

              foreach ($arr as $key => $value) {
                if($value != ""){
                  $Table .= "<th name='DatosResultados' style='min-width: 270px;'>".$value."</th>";  
                }              		
	              
	          }


              $Table .= "</tr></thead><tbody id='cuerpo-tabla'>";


              //	CREANDO FILTRO MOSTRAR
              if($Mostrar > 0){
              	$ConsultarTodos = "select P.id_persona, B.Barrio, P.manzana, P.lote, P.familia, P.apellido, P.nombre, P.fecha_nac, P.domicilio from persona P, barrios B, movimiento M where not exists(select * from movimiento M2 where M2.id_persona = P.id_persona) and B.ID_Barrio = P.ID_Barrio and P.estado = 1";

                if($ID_Persona > 0){
                  $ConsultarTodos .= " and P.id_persona = $ID_Persona";
                }

                if($Edad_Desde != null && $Edad_Desde != "" && $Edad_Hasta != null && $Edad_Hasta != ""){
                  $ConsultarTodos .= " and P.edad between $Edad_Desde and $Edad_Hasta";
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

                if($Barrio > 0){
                  $ConsultarTodos .= " and P.ID_Barrio = $Barrio";
                }

                if($ID_Escuela > 0){
                  $ConsultarTodos .= " and P.ID_Escuela = $ID_Escuela";
                }

                if($Trabajo != null && $Trabajo != ""){
                  $ConsultarTodos .= " and P.Trabajo like '%$Trabajo%'";
                }

                $ConsultarTodos .= " group by P.id_persona order by P.apellido, P.nombre";



              	$MensajeErrorTodos = "No se pudieron consultar los datos de todas las personas";

              	$EjecutarConsultarTodos = mysqli_query($Con->Conexion,$ConsultarTodos) or die($MensajeErrorTodos);                

              	while($RetTodos = mysqli_fetch_assoc($EjecutarConsultarTodos)){
              		if($RetTodos["fecha_nac"] == 'null'){
	                  $Fecha_Nacimiento = "Sin Datos";
	                }else{
	                  $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$RetTodos["fecha_nac"])));
	                }

	                $Table .= "<tr class='SinMovimientos Datos'>";
                  	$Table .= "<td>".$RetTodos["Barrio"]."</td><td class='sticky'>".$RetTodos["domicilio"]."</td><td name='datosflia' style='max-width: 50px;'>".$RetTodos["manzana"]."</td><td name='datosflia' style='max-width: 50px;'>".$RetTodos["lote"]."</td><td name='datosflia' style='max-width: 50px;'>".$RetTodos["familia"]."</td><td class='sticky-2'><a href = 'javascript:window.open(\"view_modpersonas.php?ID=".$RetTodos["id_persona"]."\",\"Ventana".$RetTodos["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$RetTodos["apellido"].", ".$RetTodos["nombre"]."</a></td><td style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";

                    $ColSpans = $MesesDiferencia * 270;
                  	$Table .= "<td style='width:".$ColSpans."px'></td>";


              	}
              }

              while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
                if($Ret["fecha_nac"] == 'null'){
                  $Fecha_Nacimiento = "Sin Datos";
                }else{
                  $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$Ret["fecha_nac"])));
                } 
             
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
                /////////////////////////////////// CODIGICANDO EL MUESTREO DE MESES DE UNA FORMA MAS EFICIENTE //////////////////////               

                $ID_Persona_Nuevo = $Ret["id_persona"];                
                if($ID_Persona_Nuevo == $ID_Persona_Bandera){                  
                  $Table .= "<tr style='border: 0px;'>";
                  $Table .= "<td colspan = '7' style='border: 0px;'></td>";                  
                }else{

                  $Table .= "<tr class='Datos'>";
                  $Table .= "<td style='position: sticky; left: 0px; overflow: auto; z-index: 1;'>".$Ret["Barrio"]."</td><td class='sticky' style='position: -webkit-sticky; position: sticky; left: 150px;'>".$Ret["domicilio"]."</td><td name='datosflia' style='max-width: 50px;'>".$Ret["manzana"]."</td><td name='datosflia' style='max-width: 50px;'>".$Ret["lote"]."</td><td name='datosflia' style='max-width: 50px;'>".$Ret["familia"]."</td><td class='sticky-2'><a href = 'javascript:window.open(\"view_modpersonas.php?ID=".$Ret["id_persona"]."\",\"Ventana".$Ret["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$Ret["apellido"].", ".$Ret["nombre"]."</a></td><td style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";
                }

                
                foreach ($arr as $key => $value) {
                    $Separar = explode("/",$value);
                    $Mes = $Separar[0];
                    $Anio = $Separar[1];
                    $Consultar_Movimientos_Persona = "select * from movimiento where id_persona = ".$Ret["id_persona"]." and MONTH(fecha) = ".$Mes." and YEAR(fecha) = ".$Anio;

                // $Consultar_Movimientos_Persona = "select id_movimiento, id_persona from movimiento where id_persona = ".$Ret['id_persona']." order by MONTH(fecha)";
                // $MensajeErrorConsultar_Mov_Persona = "No se pudieron consultar los movimientos de la persona";

                $Tomar_Movimientos_Persona = mysqli_query($Con->Conexion,$Consultar_Movimientos_Persona) or die($MensajeErrorConsultar_Mov_Persona." - ".$Consultar_Movimientos_Persona);

                //echo $Consultar_Movimientos_Persona;
                
                  $Table .= "<td name='DatosResultados' style='min-width:270px'><div class = 'row'>";

	                while($Ret_Movimientos_Persona = mysqli_fetch_assoc($Tomar_Movimientos_Persona)){	                	

	                	$Num_Movimientos_Persona = mysqli_num_rows($Tomar_Movimientos_Persona);
	                	//echo $Ret_Movimientos_Persona['id_persona']." - ".$Ret_Movimientos_Persona['id_movimiento'];
	                  
	                	
	                      $Consultar_Datos_Movimientos = "select M.id_movimiento, MONTH(M.fecha) as 'Mes', YEAR(M.fecha) as 'Anio', M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret_Movimientos_Persona['id_movimiento']." and M.id_persona = ".$Ret_Movimientos_Persona['id_persona']." group by M.id_movimiento";	                      



	                      $MensajeErrorConsultar_Datos_Movimientos = "No se pudieron consultar los datos del movimiento";
	                      $Tomar_Datos_Movimientos = mysqli_query($Con->Conexion,$Consultar_Datos_Movimientos) or die($MensajeErrorConsultar_Datos_Movimientos." - ".$Consultar_Datos_Movimientos);
	                      $Ret_Datos_Movimiento = mysqli_fetch_assoc($Tomar_Datos_Movimientos);

	                      // $Datos_Mes_Anio = $Ret_Datos_Movimiento["Mes"]."/".$Ret_Datos_Movimiento["Anio"];	                      

	                      // foreach($arr as $key => $value){	

	                      //   if(strcmp($Ret_Datos_Movimiento["motivo_1"],"1") !== 0){  
	                          
	                          if($Ret_Datos_Movimiento["motivo_1"] > 1){                              
	                            $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
	                            $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";

	                            //echo $ConsultarCodyColor;               

	                            $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);

	                            $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);

	                            $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."</span></a></div>";
	                           }
	                          if($Ret_Datos_Movimiento["motivo_2"] > 1){
	                            $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
	                            $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";


	                            $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor);

	                            $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
    
	                            

	                            $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"]."; text-align= center;'>".$RetMotivo["Forma_Categoria"]."</span></a></div>";
	                          }
	                          if($Ret_Datos_Movimiento["motivo_3"] > 1){
	                            $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
	                            $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";


	                            $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor);

	                            $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
	                            

	                            $Table .= "<div class = 'col-md-1'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."</span></a></div>";
	                          }
	                      
	                          // $Table .= "</div></td>"; 
	                      //   }else{
	                      //     $Table .= "<td></td>";
	                      //   }

	                      // }
	                       
	                      ///////////////////////////////////////////////////////////////////////
	                      ///////////////////////////////////////////////////////////////////////
	                      ///////////////////////////////////////////////////////////////////////
	                      ///////////////////////////////////////////////////////////////////////
	                      ///////////////////BUSCAR EL ERROR/////////////////////////////
	                      ///////////////////////////////////////////////////////////////////////
	                      ///////////////////////////////////////////////////////////////////////
	                      ///////////////////////////////////////////////////////////////////////
	                      ///////////////////////////////////////////////////////////////////////	                                            

	                      // if($Num_Movimientos_Persona > 1){
	                      // 	$Table .= "<td></td>";	
	                      // }	

                        

	                                                                                                          
	                }   
	                
                  	$Table .= "</div></td>";

	                $ID_Persona_Bandera = $Ret["id_persona"]; 	                
                                                                 
              }
              $table .= "</tr></tbody>";
            }
              $Con->CloseConexion();
              $Table .= "</table>";

               if($Con->ResultSet->num_rows > 0){
                  echo $Table;
               }                                        
            }else{
              echo "No se pudo obtener el año";
            }
          ?>
        </div>        
  </div>
</div>
</div>
<script>
   function toggleZoomScreen() {
       document.body.style.zoom = "40%";
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
         DatosFlia[i].setAttribute("style","max-width: 50px; font-size: 24px;");   
      }

      var DatosResultados = document.getElementsByName("DatosResultados");
      for (var i = 0; i < DatosResultados.length; i++){
         // DatosResultados[i].removeAttribute("min-width");         
         DatosResultados[i].setAttribute("style","min-width: 270px; font-size: 24px;");   
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
        element.style.fontSize = "12px";
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
         DatosResultados[i].setAttribute("style","min-width: 270px;");   
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
</body>
</html>