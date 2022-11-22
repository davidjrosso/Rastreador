<?php 
require_once "Conexion.php";
header("Pragma: public");
header("Expires: 0");
$hoy = date('d-m-Y');
$filename = $hoy." Grafico Rastreador.xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
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

$Consulta = base64_decode($_REQUEST["consulta"]);
$Fecha_Inicio = base64_decode($_REQUEST["fechaInicio"]);
$Fecha_Fin = base64_decode($_REQUEST["fechaFin"]);

$Con = new Conexion();
$Con->OpenConexion();

$tomarRetTodos = array();

$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MensajeError." Consulta: ".$Consulta);           

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// TOMANDO LOS ID DE LOS MOVIMIENTOS PARA LUEGO HACER LA COMPARACION PARA EL PINTADO DE LOS MOTIVOS.
$ResultadosPrincipal = $Con->ResultSet->fetch_assoc();

$arrIDMovimientos = array();

foreach($ResultadosPrincipal as $value){
    $arrIDMovimientos[] = $value['id_movimiento'];
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                              
$Table = "<table class='table table-fixeder table-bordered table-sm' cellspacing='0'
id='tablaMovimientos'><thead class='thead-dark'><tr align='center' valign='middle'><th id='Contenido-Titulo-1'>Barrio</th><th id='Contenido-Titulo-2'>Direc.</th><th id='Contenido-Titulo-3' name='datosflia' style='max-width: 50px;'>Mz.</th><th id='Contenido-Titulo-4' name='datosflia' style='max-width: 50px;'>Lote</th><th id='Contenido-Titulo-5' name='datosflia' style='max-width: 50px;'>Sublote.</th><th id='Contenido-Titulo-6'>Persona</th><th id='Contenido-Titulo-7' style='max-width: 100px;'>Fecha Nac.</th>";                

$Tomar_Meses = mysqli_query($Con->Conexion,$Consulta) or die($MensajeError." Consulta: ".$Consulta);


// echo var_dump($Tomar_Meses);
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

foreach ($arr as $key => $value) {                

    if($value != ""){
      $Table .= "<th name='DatosResultados' style='min-width: 270px;'>".$value."</th>";  
    }              		
    
}

// echo var_dump($arr);

$Table .= "</tr></thead><tbody id='cuerpo-tabla'>";

$EjecutarConsulta2 = mysqli_query($Con->Conexion, $Consulta) or die("Error al consultar datos");

while ($Ret = mysqli_fetch_array($EjecutarConsulta2)) {
    if($Ret["fecha_nac"] == 'null'){
        $Fecha_Nacimiento = "Sin Datos";
    }else{
        $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$Ret["fecha_nac"])));
    }                             

    $ID_Persona_Nuevo = $Ret["id_persona"];  

    if($ID_Persona_Nuevo !== $ID_Persona_Bandera){
        $ID_Persona_Bandera = $Ret["id_persona"];                                                                           
    }
    $Ret['tipo'] = "CM";
    $tomarRetTodos[] = $Ret;
}

foreach ($tomarRetTodos as $clave => $reg) {
    $regdomicilio[$clave] = $reg['domicilio'];                
}

array_multisort($regdomicilio, SORT_DESC, $tomarRetTodos);

foreach($tomarRetTodos as $clave => $RetTodos){                
    if($RetTodos["fecha_nac"] == 'null'){
      $Fecha_Nacimiento = "Sin Datos";
    }else{
      $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$RetTodos["fecha_nac"])));
    }

    if($RetTodos["tipo"] == "SM"){      
      $Table .= "<tr class='SinMovimientos Datos'>";
      $Table .= "<td id='Contenido-1'>".$RetTodos["Barrio"]."</td><td id='Contenido-2'>".$RetTodos["domicilio"]."</td><td id='Contenido-3' name='datosflia' style='max-width: 50px;'>".$RetTodos["manzana"]."</td><td id='Contenido-4' name='datosflia' style='max-width: 50px;'>".$RetTodos["lote"]."</td><td id='Contenido-5' name='datosflia' style='max-width: 50px;'>".$RetTodos["familia"]."</td><td id='Contenido-6'>".$RetTodos["apellido"].", ".$RetTodos["nombre"]."</td><td id='Contenido-7' style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";

      $ColSpans = $MesesDiferencia * 270;
      $Table .= "<td name='DatosSinResultados' style='width:".$ColSpans."px'></td>";                
    }else{
      $ID_Persona_Nuevo = $RetTodos["id_persona"];

      $Table .= "<tr class='Datos'>";
      $Table .= "<td id='Contenido-1'>".$RetTodos["Barrio"]."</td><td id='Contenido-2'>".$RetTodos["domicilio"]."</td><td id='Contenido-3' name='datosflia' style='max-width: 50px;'>".$RetTodos["manzana"]."</td><td id='Contenido-4' name='datosflia' style='max-width: 50px;'>".$RetTodos["lote"]."</td><td id='Contenido-5' name='datosflia' style='max-width: 50px;'>".$RetTodos["familia"]."</td><td id='Contenido-6'>".$RetTodos["apellido"].", ".$RetTodos["nombre"]."</td><td id='Contenido-7' style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";      
        foreach ($arr as $key => $value) {
            $Separar = explode("/",$value);
            $Mes = $Separar[0];
            $Anio = $Separar[1];                                          
            $Consultar_Movimientos_Persona = "select * from movimiento where id_persona = ".$RetTodos["id_persona"]." and MONTH(fecha) = ".$Mes." and YEAR(fecha) like '%".$Anio."'";            
            

            $Tomar_Movimientos_Persona = mysqli_query($Con->Conexion,$Consultar_Movimientos_Persona) or die($MensajeErrorConsultar_Mov_Persona." - ".$Consultar_Movimientos_Persona);

            $Table .= "<td name='DatosResultados' style='min-width:500px'>";                    

            $Num_Movimientos_Persona = mysqli_num_rows($Tomar_Movimientos_Persona);                        

            while($Ret_Movimientos_Persona = mysqli_fetch_assoc($Tomar_Movimientos_Persona)){	                	                                                    

              $Consultar_Datos_Movimientos = "select M.id_movimiento, MONTH(M.fecha) as 'Mes', YEAR(M.fecha) as 'Anio', M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where (M.motivo_1 = MT.id_motivo or M.motivo_2 = MT.id_motivo or M.motivo_3 = MT.id_motivo) and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret_Movimientos_Persona['id_movimiento']." and M.id_persona = ".$Ret_Movimientos_Persona['id_persona']." group by M.id_movimiento";	                                    

              $MensajeErrorConsultar_Datos_Movimientos = "No se pudieron consultar los datos del movimiento";
              $Tomar_Datos_Movimientos = mysqli_query($Con->Conexion,$Consultar_Datos_Movimientos) or die($MensajeErrorConsultar_Datos_Movimientos." - ".$Consultar_Datos_Movimientos);
              $Ret_Datos_Movimiento = mysqli_fetch_assoc($Tomar_Datos_Movimientos);
                  
                  if($Ret_Datos_Movimiento["motivo_1"] > 1){
                    if($ID_Motivo > 0){
                      if($ID_Motivo == $Ret_Datos_Movimiento["motivo_1"]){
                        $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";

                        // echo $ConsultarCodyColor;               

                        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);

                        $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);

                        // echo "DEBUG: ".var_dump($RetMotivo);

                        $Table .= "".$RetMotivo["Forma_Categoria"]." (".$RetMotivo["cod_categoria"].") ";                                  
                      }
                    }
                    if($ID_Motivo2 > 0){
                      if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_1"]){
                        $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";

                        // echo $ConsultarCodyColor;               

                        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);

                        $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);

                        // echo "DEBUG: ".var_dump($RetMotivo);

                        $Table .= "".$RetMotivo["Forma_Categoria"]." (".$RetMotivo["cod_categoria"].") ";                                  
                      }
                    }
                    if($ID_Motivo3 > 0){
                      if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_1"]){
                        $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                        $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";

                        // echo $ConsultarCodyColor;               

                        $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);

                        $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);

                        // echo "DEBUG: ".var_dump($RetMotivo);

                        $Table .= "".$RetMotivo["Forma_Categoria"]." (".$RetMotivo["cod_categoria"].") ";                                  
                      }
                    }
                    if($ID_Motivo == 0 && $ID_Motivo2 == 0 && $ID_Motivo3 == 0){                                                        
                      $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                      $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";

                      //echo $ConsultarCodyColor;               

                      $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);

                      $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);

                      // echo "DEBUG NOT: ".var_dump($RetMotivo);

                      $Table .= "".$RetMotivo["Forma_Categoria"]." (".$RetMotivo["cod_categoria"].") ";
                    }
                  }

                  if($Ret_Datos_Movimiento["motivo_2"] > 1){
                    if($ID_Motivo > 0){
                      if($ID_Motivo == $Ret_Datos_Movimiento["motivo_2"]){
                        $ConsultarCodyColor2 = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";


                        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2." - ".$ConsultarCodyColor2." valor:".$Ret_Datos_Movimiento["motivo_2"]);

                        $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);

                        $Table .= "".$RetMotivo2["Forma_Categoria"]." (".$RetMotivo2["cod_categoria"].") ";                                  
                      }
                    }
                    if($ID_Motivo2 > 0){
                      if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_2"]){
                        $ConsultarCodyColor2 = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";


                        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2." - ".$ConsultarCodyColor2." valor:".$Ret_Datos_Movimiento["motivo_2"]);

                        $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);

                        $Table .= "".$RetMotivo2["Forma_Categoria"]." (".$RetMotivo2["cod_categoria"].") ";                                  
                      }
                    }
                    if($ID_Motivo3 > 0){
                      if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_2"]){
                        $ConsultarCodyColor2 = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                        $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";


                        $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2." - ".$ConsultarCodyColor2." valor:".$Ret_Datos_Movimiento["motivo_2"]);

                        $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);

                        $Table .= "".$RetMotivo2["Forma_Categoria"]." (".$RetMotivo2["cod_categoria"].") ";                                  
                      }
                    }
                    if($ID_Motivo == 0 && $ID_Motivo2 == 0 && $ID_Motivo3 == 0){ 
                      $ConsultarCodyColor2 = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                      $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";


                      $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2." - ".$ConsultarCodyColor2);

                      $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);

                      

                      $Table .= "".$RetMotivo2["Forma_Categoria"]." (".$RetMotivo2["cod_categoria"].") ";
                    }
                  }


                  if($Ret_Datos_Movimiento["motivo_3"] > 1){
                    if($ID_Motivo > 0){
                      if($ID_Motivo == $Ret_Datos_Movimiento["motivo_3"]){
                        $ConsultarCodyColor3 = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos de los Movimientos";

                        // echo $ConsultarCodyColor;               

                        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3." - ".$ConsultarCodyColor3." valor:".$Ret_Datos_Movimiento["motivo_3"]);

                        $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);

                        $Table .= "".$RetMotivo3["Forma_Categoria"]." (".$RetMotivo3["cod_categoria"].") ";                                  
                      }
                    }
                    if($ID_Motivo2 > 0){
                      if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_3"]){
                        $ConsultarCodyColor3 = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos de los Movimientos";

                        // echo $ConsultarCodyColor;               

                        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3." - ".$ConsultarCodyColor3." valor:".$Ret_Datos_Movimiento["motivo_3"]);

                        $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);
                        
                        $Table .= "".$RetMotivo3["Forma_Categoria"]." (".$RetMotivo3["cod_categoria"].") ";                                  
                      }
                    }
                    if($ID_Motivo3 > 0){
                      if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_3"]){
                        $ConsultarCodyColor3 = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                        $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos de los Movimientos";

                        // echo $ConsultarCodyColor;               

                        $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3." - ".$ConsultarCodyColor3." valor:".$Ret_Datos_Movimiento["motivo_3"]);

                        $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);

                        $Table .= "".$RetMotivo3["Forma_Categoria"]." (".$RetMotivo3["cod_categoria"].") ";                                  
                      }
                    }
                    if($ID_Motivo == 0 && $ID_Motivo2 == 0 && $ID_Motivo3 == 0){ 
                      $ConsultarCodyColor3 = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                      $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";


                      $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3." - ".$ConsultarCodyColor3);

                      $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);
                      

                      $Table .= "".$RetMotivo3["Forma_Categoria"]." (".$RetMotivo3["cod_categoria"].") ";
                    }
                  }     
            }   
        
            $Table .= "</td>";

            $ID_Persona_Bandera = $RetTodos["id_persona"];                     

        } 
    
        $Table .= "</tr>";

    }

//////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////
}

$Table .= "</tbody>";
// echo "DEBUG: ".var_dump($tomarRetTodos);

$Table .= "</table>";                                       

$Con->CloseConexion();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Reporte Excel</title>
    <style>
      th{
        border:1px; 
        border-style: solid; 
        border-color: #000;
      }  
      td{
        border:1px; 
        border-style: solid; 
        border-color: #000;
      }   
    </style>
</head>
<body>
<?php echo $Table; ?>
</body>
</html>
