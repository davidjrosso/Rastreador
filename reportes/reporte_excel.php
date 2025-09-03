<?php  
setlocale(LC_ALL, "es_ES");

header("Content-type:application/xls");
header("Content-Disposition: attachment;  filename= Tabla-Rastreador.xls");  
require_once("../Controladores/Conexion.php");

$Con = new Conexion();
$Con->OpenConexion();

$Consulta = $_REQUEST["Consulta"];
$Anio = $_REQUEST["Anio"];
$MensajeError = "No se pudo realizar correctamente la consulta a la base de datos";

$EjecutarConsulta = mysqli_query($Con->Conexion, $Consulta);

$Table =  "<table class='table table-bordered table-sm table-responsive' style='page-break-before: always;' cellspacing='0'
  width='100%' id = 'DivTab'><thead class = 'bg-secondary' style='color: #fff;'><tr align='center' valign='middle'><th><div style='width: 100px;'>Barrio</div></th><th>Mz.</th><th>Lote</th><th>Flia.</th><th>Persona</th><th min-width='400'><div style='width: 100px;'>Fecha Nac.</div></th><th>Enero</th><th>Febrero</th><th>Marzo</th><th>Abril</th><th>Mayo</th><th>Junio</th><th>Julio</th><th>Agosto</th><th>Septiembre</th><th>Octubre</th><th>Noviembre</th><th>Diciembre</th></tr></thead>";

   while ($Ret = mysqli_fetch_assoc($EjecutarConsulta)) {
    if($Ret["fecha_nac"] == 'null'){
      $Fecha_Nacimiento = "Sin Datos";
    }else{
      $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$Ret["fecha_nac"])));
    }                
    //////////////////////////////// TOMAR MOVIMIENTOS DE ENERO ///////////////////////////////////////////
    $Consultar_Enero = "select M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and M.id_persona = ".$Ret['id_persona']." and MONTH(M.fecha) = 01 and YEAR(M.fecha) = $Anio group by M.id_movimiento";
    $MensajeErrorConsultar_Enero = "No se pudieron consultar los movimientos de Enero";
    $Tomar_Enero = mysqli_query($Con->Conexion,$Consultar_Enero) or die($MensajeErrorConsultar_Enero);              

    //////////////////////////////// TOMAR MOVIMIENTOS DE FEBRERO ///////////////////////////////////////////
    $Consultar_Febrero = "select M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and M.id_persona = ".$Ret['id_persona']." and MONTH(M.fecha) = 02 and YEAR(M.fecha) = $Anio group by M.id_movimiento";
    $MensajeErrorConsultar_Febrero = "No se pudieron consultar los movimientos de Febrero";
    $Tomar_Febrero = mysqli_query($Con->Conexion,$Consultar_Febrero) or die($MensajeErrorConsultar_Febrero);

    //////////////////////////////// TOMAR MOVIMIENTOS DE MARZO ///////////////////////////////////////////
    $Consultar_Marzo = "select M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and M.id_persona = ".$Ret['id_persona']." and MONTH(M.fecha) = 03 and YEAR(M.fecha) = $Anio group by M.id_movimiento";
    $MensajeErrorConsultar_Marzo = "No se pudieron consultar los movimientos de Marzo";
    $Tomar_Marzo = mysqli_query($Con->Conexion,$Consultar_Marzo) or die($MensajeErrorConsultar_Marzo);

    //////////////////////////////// TOMAR MOVIMIENTOS DE ABRIL ///////////////////////////////////////////
    $Consultar_Abril = "select M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and M.id_persona = ".$Ret['id_persona']." and MONTH(M.fecha) = 04 and YEAR(M.fecha) = $Anio group by M.id_movimiento";
    $MensajeErrorConsultar_Abril = "No se pudieron consultar los movimientos de Abril";
    $Tomar_Abril = mysqli_query($Con->Conexion,$Consultar_Abril) or die($MensajeErrorConsultar_Abril);


    //////////////////////////////// TOMAR MOVIMIENTOS DE MAYO ///////////////////////////////////////////
    $Consultar_Mayo = "select M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and M.id_persona = ".$Ret['id_persona']." and MONTH(M.fecha) = 05 and YEAR(M.fecha) = $Anio group by M.id_movimiento";
    $MensajeErrorConsultar_Mayo = "No se pudieron consultar los movimientos de Mayo";
    $Tomar_Mayo = mysqli_query($Con->Conexion,$Consultar_Mayo) or die($MensajeErrorConsultar_Mayo);

    //////////////////////////////// TOMAR MOVIMIENTOS DE JUNIO ///////////////////////////////////////////
    $Consultar_Junio = "select M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and M.id_persona = ".$Ret['id_persona']." and MONTH(M.fecha) = 06 and YEAR(M.fecha) = $Anio group by M.id_movimiento";
    $MensajeErrorConsultar_Junio = "No se pudieron consultar los movimientos de Junio";
    $Tomar_Junio = mysqli_query($Con->Conexion,$Consultar_Junio) or die($MensajeErrorConsultar_Junio);

    //////////////////////////////// TOMAR MOVIMIENTOS DE JULIO ///////////////////////////////////////////
    $Consultar_Julio = "select M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and M.id_persona = ".$Ret['id_persona']." and MONTH(M.fecha) = 07 and YEAR(M.fecha) = $Anio group by M.id_movimiento";
    $MensajeErrorConsultar_Julio = "No se pudieron consultar los movimientos de Julio";
    $Tomar_Julio = mysqli_query($Con->Conexion,$Consultar_Julio) or die($MensajeErrorConsultar_Julio);

    //////////////////////////////// TOMAR MOVIMIENTOS DE AGOSTO ///////////////////////////////////////////
    $Consultar_Agosto = "select M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and M.id_persona = ".$Ret['id_persona']." and MONTH(M.fecha) = 08 and YEAR(M.fecha) = $Anio group by M.id_movimiento";
    $MensajeErrorConsultar_Agosto = "No se pudieron consultar los movimientos de Agosto";
    $Tomar_Agosto = mysqli_query($Con->Conexion,$Consultar_Agosto) or die($MensajeErrorConsultar_Agosto);


    //////////////////////////////// TOMAR MOVIMIENTOS DE SEPTIEMBRE ///////////////////////////////////////////
    $Consultar_Septiembre = "select M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and M.id_persona = ".$Ret['id_persona']." and MONTH(M.fecha) = 09 and YEAR(M.fecha) = $Anio group by M.id_movimiento";
    $MensajeErrorConsultar_Septiembre = "No se pudieron consultar los movimientos de Septiembre";
    $Tomar_Septiembre = mysqli_query($Con->Conexion,$Consultar_Septiembre) or die($MensajeErrorConsultar_Septiembre);

    //////////////////////////////// TOMAR MOVIMIENTOS DE OCTUBRE ///////////////////////////////////////////
    $Consultar_Octubre = "select M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and M.id_persona = ".$Ret['id_persona']." and MONTH(M.fecha) = 10 and YEAR(M.fecha) = $Anio group by M.id_movimiento";
    $MensajeErrorConsultar_Octubre = "No se pudieron consultar los movimientos de Octubre";
    $Tomar_Octubre = mysqli_query($Con->Conexion,$Consultar_Octubre) or die($MensajeErrorConsultar_Octubre);

    //////////////////////////////// TOMAR MOVIMIENTOS DE NOVIEMBRE ///////////////////////////////////////////
    $Consultar_Noviembre = "select M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and M.id_persona = ".$Ret['id_persona']." and MONTH(M.fecha) = 11 and YEAR(M.fecha) = $Anio group by M.id_movimiento";
    $MensajeErrorConsultar_Noviembre = "No se pudieron consultar los movimientos de Noviembre";
    $Tomar_Noviembre = mysqli_query($Con->Conexion,$Consultar_Noviembre) or die($MensajeErrorConsultar_Noviembre);

    //////////////////////////////// TOMAR MOVIMIENTOS DE DICIEMBRE ///////////////////////////////////////////
    $Consultar_Diciembre = "select M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and M.id_persona = ".$Ret['id_persona']." and MONTH(M.fecha) = 12 and YEAR(M.fecha) = $Anio group by M.id_movimiento";
    $MensajeErrorConsultar_Diciembre = "No se pudieron consultar los movimientos de Noviembre";
    $Tomar_Diciembre = mysqli_query($Con->Conexion,$Consultar_Diciembre) or die($MensajeErrorConsultar_Diciembre);

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $Table .= "<tr>";

    $Table .= "<td>".$Ret["Barrio"]."</td><td>".$Ret["manzana"]."</td><td>".$Ret["lote"]."</td><td>".$Ret["familia"]."</td><td><a href = 'javascript:window.open(\"persona?ID=".$Ret["id_persona"]."\",\"Ventana".$Ret["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$Ret["apellido"].", ".$Ret["nombre"]."</a></td><td>".$Fecha_Nacimiento."</td>";
    
    ///////////////////////////////// PASAR A LA TABLA ENERO ////////////////////////////////////////////////////
    $Table .= "<td>";
    while ($RetEnero = mysqli_fetch_assoc($Tomar_Enero)) {
      /////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 1 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetEnero["motivo_1"] > 1){

        $ConsultarCodyColor = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetEnero["motivo_1"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos 1 de Enero";


        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor);

        $RetMotivo_1 = mysqli_fetch_assoc($TomarCodyColor); 

        $Table .= "<center><div class = 'row container' style = 'width: 200px;'>";

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";                                      

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetEnero["id_movimiento"]."\",\"Ventana".$RetEnero["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetEnero["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_1["color"].";'>".$RetMotivo_1["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";

      }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 2 ///////////////////////////////////////////
      ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////
      if($RetEnero["motivo_2"] > 1){
        $ConsultarCodyColor2 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetEnero["motivo_2"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos 2 de Enero";

        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2);

        $RetMotivo_2 = mysqli_fetch_assoc($TomarCodyColor2);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";                      

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetEnero["id_movimiento"]."\",\"Ventana".$RetEnero["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetEnero["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_2["color"].";'>".$RetMotivo_2["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
      }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 3 ///////////////////////////////////////////
      ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////
      if($RetMotivo_3 > 1){                    
        $ConsultarCodyColor3 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetEnero["motivo_3"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos 3 de Enero";

        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3);

        $RetMotivo_3 = mysqli_fetch_assoc($TomarCodyColor3);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";                    

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetEnero["id_movimiento"]."\",\"Ventana".$RetEnero["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetEnero["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_3["color"].";'>".$RetMotivo_3["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
       	$Table .= "</div></center>";
       }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
      }

    $Table .= "</td>";

    ///////////////////////////////// PASAR A LA TABLA FEBRERO ////////////////////////////////////////////////////
    $Table .= "<td>";
    while ($RetFebrero = mysqli_fetch_assoc($Tomar_Febrero)) {                                    
        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 1 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetFebrero["motivo_1"] > 1){
        $ConsultarCodyColor = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetFebrero["motivo_1"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos 1 de Febrero";

        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor);

        $RetMotivo_1 = mysqli_fetch_assoc($TomarCodyColor);

        $Table .= "<center><div class = 'row container' style = 'width: 200px;'>";

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetFebrero["id_movimiento"]."\",\"Ventana".$RetFebrero["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetFebrero["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_1["color"].";'>".$RetMotivo_1["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
      }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 2 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetFebrero["motivo_2"] > 1){

        $ConsultarCodyColor2 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetFebrero["motivo_2"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos 2 de Febrero";

        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2);

        $RetMotivo_2 = mysqli_fetch_assoc($TomarCodyColor2);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";                   

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetFebrero["id_movimiento"]."\",\"Ventana".$RetFebrero["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetFebrero["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_2["color"].";'>".$RetMotivo_2["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
      }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 3 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetFebrero["motivo_3"] > 1){

        $ConsultarCodyColor3 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetFebrero["motivo_3"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos 3 de Febrero";

        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3);

        $RetMotivo_3 = mysqli_fetch_assoc($TomarCodyColor3);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";                      

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetFebrero["id_movimiento"]."\",\"Ventana".$RetFebrero["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetFebrero["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_3["color"].";'>".$RetMotivo_3["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        $Table .= "</div></center>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    $Table .= "</td>";

    ///////////////////////////////// PASAR A LA TABLA MARZO ////////////////////////////////////////////////////
    $Table .= "<td>";
    while ($RetMarzo = mysqli_fetch_assoc($Tomar_Marzo)) {              
        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 1 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetMarzo["motivo_1"] > 1){

        $ConsultarCodyColor = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetMarzo["motivo_1"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos 1 de Marzo";

        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor);

        $RetMotivo_1 = mysqli_fetch_assoc($TomarCodyColor);
        
        $Table .= "<center><div class = 'row container' style = 'width: 200px;'>";

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetMarzo["id_movimiento"]."\",\"Ventana".$RetMarzo["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetMarzo["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_1["color"].";'>".$RetMotivo_1["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 2 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetMarzo["motivo_2"] > 1){

        $ConsultarCodyColor2 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetMarzo["motivo_2"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos 2 de Marzo";

        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2);

        $RetMotivo_2 = mysqli_fetch_assoc($TomarCodyColor2);
        
        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetMarzo["id_movimiento"]."\",\"Ventana".$RetMarzo["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetMarzo["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_2["color"].";'>".$RetMotivo_2["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 3 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetMarzo["motivo_3"] > 1){

        $ConsultarCodyColor3 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetMarzo["motivo_3"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos 3 de Marzo";

        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3);

        $RetMotivo_3 = mysqli_fetch_assoc($TomarCodyColor3);
        
        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";

        $Table .= "N° <a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetMarzo["id_movimiento"]."\",\"Ventana".$RetMarzo["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetMarzo["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_3["color"].";'>".$RetMotivo_3["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        $Table .= "</div></center>";
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
    }

    $Table .= "</td>";

    ///////////////////////////////// PASAR A LA TABLA ABRIL ////////////////////////////////////////////////////
    $Table .= "<td>";
    while ($RetAbril = mysqli_fetch_assoc($Tomar_Abril)) {               
        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 1 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetAbril["motivo_1"] > 1){

        $ConsultarCodyColor = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetAbril["motivo_1"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos 1 de Abril";

        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor);

        $RetMotivo_1 = mysqli_fetch_assoc($TomarCodyColor);

        $Table .= "<center><div class = 'row container' style = 'width: 200px;'>";

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetAbril["id_movimiento"]."\",\"Ventana".$RetAbril["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetAbril["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_1["color"].";'>".$RetMotivo_1["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 2 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetAbril["motivo_2"] > 1){

        $ConsultarCodyColor2 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetAbril["motivo_2"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos 2 de Abril";

        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2);

        $RetMotivo_2 = mysqli_fetch_assoc($TomarCodyColor2);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetAbril["id_movimiento"]."\",\"Ventana".$RetAbril["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetAbril["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_2["color"].";'>".$RetMotivo_2["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 3 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetAbril["motivo_3"] > 1){

        $ConsultarCodyColor3 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetAbril["motivo_3"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos 3 de Abril";

        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3);

        $RetMotivo_3 = mysqli_fetch_assoc($TomarCodyColor3);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetAbril["id_movimiento"]."\",\"Ventana".$RetAbril["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetAbril["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_3["color"].";'>".$RetMotivo_3["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        $Table .= "</div></center>";
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    $Table .= "</td>";

    ///////////////////////////////// PASAR A LA TABLA MAYO ////////////////////////////////////////////////////
    $Table .= "<td>";
    while ($RetMayo = mysqli_fetch_assoc($Tomar_Mayo)) {              
        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 1 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetMayo["motivo_1"] > 1){

        $ConsultarCodyColor = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetMayo["motivo_1"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos 1 de Mayo";

        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor);

        $RetMotivo_1 = mysqli_fetch_assoc($TomarCodyColor);

        $Table .= "<center><div class = 'row container' style = 'width: 200px;'>";

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetMayo["id_movimiento"]."\",\"Ventana".$RetMayo["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetMayo["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_1["color"].";'>".$RetMotivo_1["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 2 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetMayo["motivo_2"] > 1){

        $ConsultarCodyColor2 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetMayo["motivo_2"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos 2 de Mayo";

        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2);

        $RetMotivo_2 = mysqli_fetch_assoc($TomarCodyColor2);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetMayo["id_movimiento"]."\",\"Ventana".$RetMayo["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetMayo["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_2["color"].";'>".$RetMotivo_2["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 3 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetMayo["motivo_3"] > 1){

        $ConsultarCodyColor3 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetMayo["motivo_3"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos 3 de Mayo";

        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3);

        $RetMotivo_3 = mysqli_fetch_assoc($TomarCodyColor3);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetMayo["id_movimiento"]."\",\"Ventana".$RetMayo["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetMayo["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_3["color"].";'>".$RetMotivo_3["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        $Table .= "</div></center>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    $Table .= "</td>";

    ///////////////////////////////// PASAR A LA TABLA JUNIO ////////////////////////////////////////////////////
    $Table .= "<td>";
    while ($RetJunio = mysqli_fetch_assoc($Tomar_Junio)) {               
        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 1 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetJunio["motivo_1"] > 1){

        $ConsultarCodyColor = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetJunio["motivo_1"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos 1 de Junio";

        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor);

        $RetMotivo_1 = mysqli_fetch_assoc($TomarCodyColor);

        $Table .= "<center><div class = 'row container' style = 'width: 200px;'>";

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetJunio["id_movimiento"]."\",\"Ventana".$RetJunio["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetJunio["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_1["color"].";'>".$RetMotivo_1["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 2 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetJunio["motivo_2"] > 1){

        $ConsultarCodyColor2 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetJunio["motivo_2"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos 2 de Junio";

        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2);

        $RetMotivo_2 = mysqli_fetch_assoc($TomarCodyColor2);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetJunio["id_movimiento"]."\",\"Ventana".$RetJunio["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetJunio["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_2["color"].";'>".$RetMotivo_2["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 3 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetJunio["motivo_3"] > 1){

        $ConsultarCodyColor3 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetJunio["motivo_3"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos 3 de Junio";

        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3);

        $RetMotivo_3 = mysqli_fetch_assoc($TomarCodyColor3);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetJunio["id_movimiento"]."\",\"Ventana".$RetJunio["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetJunio["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_3["color"].";'>".$RetMotivo_3["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        $Table .= "</div></center>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    $Table .= "</td>";

    ///////////////////////////////// PASAR A LA TABLA JULIO ////////////////////////////////////////////////////
    $Table .= "<td>";
    while ($RetJulio = mysqli_fetch_assoc($Tomar_Julio)) {             
        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 1 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetJulio["motivo_1"] > 1){

        $ConsultarCodyColor = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetJulio["motivo_1"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos 1 de Julio";

        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor);

        $RetMotivo_1 = mysqli_fetch_assoc($TomarCodyColor);

        $Table .= "<center><div class = 'row container' style = 'width: 200px;'>";

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetJulio["id_movimiento"]."\",\"Ventana".$RetJulio["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetJulio["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_1["color"].";'>".$RetMotivo_1["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 2 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetJulio["motivo_2"] > 1){

        $ConsultarCodyColor2 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetJulio["motivo_2"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos 2 de Julio";

        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2);

        $RetMotivo_2 = mysqli_fetch_assoc($TomarCodyColor2);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetJulio["id_movimiento"]."\",\"Ventana".$RetJulio["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetJulio["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_2["color"].";'>".$RetMotivo_2["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 3 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetJulio["motivo_3"] > 1){

        $ConsultarCodyColor3 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetJulio["motivo_3"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos 3 de Julio";

        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3);

        $RetMotivo_3 = mysqli_fetch_assoc($TomarCodyColor3);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetJulio["id_movimiento"]."\",\"Ventana".$RetJulio["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetJulio["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_3["color"].";'>".$RetMotivo_3["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";
        $Table .= "</div></center>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

    }
    $Table .= "</td>";

    ///////////////////////////////// PASAR A LA TABLA AGOSTO ////////////////////////////////////////////////////
    $Table .= "<td>";
    while ($RetAgosto = mysqli_fetch_assoc($Tomar_Agosto)) {              
        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 1 ///////////////////////////////////////////
         ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetAgosto["motivo_1"] > 1){

        $ConsultarCodyColor = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetAgosto["motivo_1"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos 1 de Agosto";

        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor);

        $RetMotivo_1 = mysqli_fetch_assoc($TomarCodyColor);

        $Table .= "<center><div class = 'row container' style = 'width: 200px;'>";

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetAgosto["id_movimiento"]."\",\"Ventana".$RetAgosto["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetAgosto["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_1["color"].";'>".$RetMotivo_1["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";   
        }                 

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 2 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetAgosto["motivo_2"] > 1){

        $ConsultarCodyColor2 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetAgosto["motivo_2"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos 2 de Agosto";

        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2);

        $RetMotivo_2 = mysqli_fetch_assoc($TomarCodyColor2);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetAgosto["id_movimiento"]."\",\"Ventana".$RetAgosto["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetAgosto["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_2["color"].";'>".$RetMotivo_2["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>";  
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 3 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetAgosto["motivo_3"] > 1){

        $ConsultarCodyColor3 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetAgosto["motivo_3"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos 3 de Agosto";

        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3);

        $RetMotivo_3 = mysqli_fetch_assoc($TomarCodyColor3);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetAgosto["id_movimiento"]."\",\"Ventana".$RetAgosto["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetAgosto["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_3["color"].";'>".$RetMotivo_3["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 
        $Table .= "</div></center>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
    }

    $Table .= "</td>";

    ///////////////////////////////// PASAR A LA TABLA SEPTIEMBRE ////////////////////////////////////////////////////
    $Table .= "<td>";
    while ($RetSeptiembre = mysqli_fetch_assoc($Tomar_Septiembre)) {           
        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 1 ///////////////////////////////////////////
         ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetSeptiembre["motivo_1"] > 1){

        $ConsultarCodyColor = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetSeptiembre["motivo_1"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos 1 de Septiembre";

        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor);

        $RetMotivo_1 = mysqli_fetch_assoc($TomarCodyColor);

        $Table .= "<center><div class = 'row container' style = 'width: 200px;'>";

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetSeptiembre["id_movimiento"]."\",\"Ventana".$RetSeptiembre["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetSeptiembre["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_1["color"].";'>".$RetMotivo_1["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 2 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetSeptiembre["motivo_2"] > 1){

        $ConsultarCodyColor2 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetSeptiembre["motivo_2"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos 2 de Septiembre";

        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2);

        $RetMotivo_2 = mysqli_fetch_assoc($TomarCodyColor2);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetSeptiembre["id_movimiento"]."\",\"Ventana".$RetSeptiembre["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetSeptiembre["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_2["color"].";'>".$RetMotivo_2["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 3 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetSeptiembre["motivo_3"] > 1){

        $ConsultarCodyColor3 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetSeptiembre["motivo_3"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos 3 de Septiembre";

        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3);

        $RetMotivo_3 = mysqli_fetch_assoc($TomarCodyColor3);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetSeptiembre["id_movimiento"]."\",\"Ventana".$RetSeptiembre["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetSeptiembre["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_3["color"].";'>".$RetMotivo_3["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 
        $Table .= "</div></center>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    $Table .= "</td>";

    ///////////////////////////////// PASAR A LA TABLA OCTUBRE ////////////////////////////////////////////////////
    $Table .= "<td>";
    while ($RetOctubre = mysqli_fetch_assoc($Tomar_Octubre)) {              
        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 1 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetOctubre["motivo_1"] > 1){

        $ConsultarCodyColor = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetOctubre["motivo_1"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos 1 de Octubre";

        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor);

        $RetMotivo_1 = mysqli_fetch_assoc($TomarCodyColor);

        $Table .= "<center><div class = 'row container' style = 'width: 200px;'>";

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetOctubre["id_movimiento"]."\",\"Ventana".$RetOctubre["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetOctubre["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_1["color"].";'>".$RetMotivo_1["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 2 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetOctubre["motivo_2"] > 1){

        $ConsultarCodyColor2 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetOctubre["motivo_2"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos 2 de Octubre";

        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2);

        $RetMotivo_2 = mysqli_fetch_assoc($TomarCodyColor2);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetOctubre["id_movimiento"]."\",\"Ventana".$RetOctubre["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetOctubre["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_2["color"].";'>".$RetMotivo_2["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 3 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetOctubre["motivo_3"] > 1){

        $ConsultarCodyColor3 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetOctubre["motivo_3"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos 3 de Octubre";

        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3);

        $RetMotivo_3 = mysqli_fetch_assoc($TomarCodyColor3);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetOctubre["id_movimiento"]."\",\"Ventana".$RetOctubre["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetOctubre["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_3["color"].";'>".$RetMotivo_3["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 
        $Table .= "</div></center>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    $Table .= "</td>";

    ///////////////////////////////// PASAR A LA TABLA NOVIEMBRE ////////////////////////////////////////////////////
    $Table .= "<td>";
    while ($RetNoviembre = mysqli_fetch_assoc($Tomar_Noviembre)) {                                   
        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 1 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetNoviembre["motivo_1"] > 1){

        $ConsultarCodyColor = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetNoviembre["motivo_1"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos 1 de Noviembre";

        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor);

        $RetMotivo_1 = mysqli_fetch_assoc($TomarCodyColor);

        $Table .= "<center><div class = 'row container' style = 'width: 200px;'>";

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetNoviembre["id_movimiento"]."\",\"Ventana".$RetNoviembre["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetNoviembre["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_1["color"].";'>".$RetMotivo_1["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 2 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetNoviembre["motivo_2"] > 1){

        $ConsultarCodyColor2 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetNoviembre["motivo_2"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos 2 de Noviembre";

        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2);

        $RetMotivo_2 = mysqli_fetch_assoc($TomarCodyColor2);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetNoviembre["id_movimiento"]."\",\"Ventana".$RetNoviembre["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetNoviembre["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_2["color"].";'>".$RetMotivo_2["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 3 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetNoviembre["motivo_3"] > 1){

        $ConsultarCodyColor3 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetNoviembre["motivo_3"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos 3 de Noviembre";

        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3);

        $RetMotivo_3 = mysqli_fetch_assoc($TomarCodyColor3);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetNoviembre["id_movimiento"]."\",\"Ventana".$RetNoviembre["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetNoviembre["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_3["color"].";'>".$RetMotivo_3["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 
        $Table .= "</div></center>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    $Table .= "</td>";

    ///////////////////////////////// PASAR A LA TABLA DICIEMBRE ////////////////////////////////////////////////////
    $Table .= "<td>";
    while ($RetDiciembre = mysqli_fetch_assoc($Tomar_Diciembre)) {              
        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 1 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetDiciembre["motivo_1"] > 1){

        $ConsultarCodyColor = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetDiciembre["motivo_1"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos 1 de Diciembre";

        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor);

        $RetMotivo_1 = mysqli_fetch_assoc($TomarCodyColor);

        $Table .= "<center><div class = 'row container' style = 'width: 200px;'>";

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetDiciembre["id_movimiento"]."\",\"Ventana".$RetDiciembre["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetDiciembre["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_1["color"].";'>".$RetMotivo_1["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 2 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetDiciembre["motivo_2"] > 1){

        $ConsultarCodyColor2 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetDiciembre["motivo_2"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos 2 de Diciembre";

        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2);

        $RetMotivo_2 = mysqli_fetch_assoc($TomarCodyColor2);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetDiciembre["id_movimiento"]."\",\"Ventana".$RetDiciembre["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetDiciembre["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_2["color"].";'>".$RetMotivo_2["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////DIBUJANDO EL CUADRADO DEL MOTIVO 3 ///////////////////////////////////////////
        ////CONSULTAR MOTIVOS DIFERENTES A SIN MOTIVO PARA PINTAR////////////////////

        if($RetDiciembre["motivo_3"] > 1){

        $ConsultarCodyColor3 = "select M.cod_categoria, C.color from motivo M, categoria C where M.id_motivo = ".$RetDiciembre["motivo_3"]." and M.cod_categoria = C.cod_categoria";
        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos 3 de Diciembre";

        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3);

        $RetMotivo_3 = mysqli_fetch_assoc($TomarCodyColor3);

        $Table .= "<div class = 'col-sm'>";

        $Table .= "<div class = 'row'>";
        

        $Table .= "<span>N° </span><a href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$RetDiciembre["id_movimiento"]."\",\"Ventana".$RetDiciembre["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>".$RetDiciembre["id_movimiento"]."</a>";

        $Table .= "</div>";

        $Table .= "<div class = 'row'>";

        $Table .= "<span style='padding: 5px; color: white; background-color: ".$RetMotivo_3["color"].";'>".$RetMotivo_3["cod_categoria"]."</span>";

        $Table .= "</div>";
        $Table .= "</div>"; 
        $Table .= "</div></center>";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    $Table .= "</td>";


    $Table .= "</tr>";

    $ID_Persona_Bandera = $Ret["id_persona"];
  }
  $Con->CloseConexion();
  $Table .= "</table>";


echo $Table;
?>