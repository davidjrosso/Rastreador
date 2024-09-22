<?php 
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
require_once "Controladores/Conexion.php";
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

</head>
<body>
<div class = "row">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $pagina);
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
            if(isset($_REQUEST["Fecha_Desde"]) && $_REQUEST["Fecha_Desde"]!=null && isset($_REQUEST["Fecha_Hasta"]) && $_REQUEST["Fecha_Hasta"]!=null){
              $Fecha_Desde = implode("-", array_reverse(explode("/",$_REQUEST["Fecha_Desde"])));
              $Fecha_Hasta = implode("-", array_reverse(explode("/",$_REQUEST["Fecha_Hasta"])));
              $Manzana = $_REQUEST["Manzana"];
              $Lote = $_REQUEST["Lote"];
              $Familia = $_REQUEST["Familia"];
              $Barrio = $_REQUEST["Barrio"];
              $ID_Motivo = $_REQUEST["ID_Motivo"];
              $ID_Categoria = $_REQUEST["ID_Categoria"]; 
              $ID_Responsable = $_REQUEST["ID_Responsable"];

              $Consulta = "select M.fecha, M.id_movimiento, M.id_persona, P.barrio, P.manzana, P.lote, P.familia, P.apellido, P.nombre, P.fecha_nac from movimiento M, persona P, motivo MO, categoria C where M.id_persona = P.id_persona and MO.id_motivo = M.motivo_1 and M.fecha between '$Fecha_Desde' and '$Fecha_Hasta'";

              if($Manzana != null && $Manzana != ""){
                $Consulta .= " and P.manzana = $Manzana";
              }

              if($Lote != null && $Lote != ""){
                $Consulta .= " and P.lote = $Lote";
              }

              if($Familia != null && $Familia != ""){
                $Consulta .= " and P.familia = $Familia";
              }

              if($Barrio != null && $Barrio != ""){
                $Consulta .= " and P.barrio like '%$Barrio%'";
              }

              if($ID_Motivo > 0){
                $Consulta .= " and M.motivo_1 = $ID_Motivo";
              }

              if($ID_Categoria > 0){
                $Consulta .= " and M.motivo_1 = MO.num_motivo and MO.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria";
              }

              if($ID_Responsable > 0){
                $Consulta .= " and M.id_resp = $ID_Responsable";
              }

              $Consulta .= " group by M.id_movimiento order by M.fecha";

              $MensajeError = "No se pudieron consultar los Datos";             

              $Con = new Conexion();
              $Con->OpenConexion();

              $Table = "<table class='table'><thead><tr><th>Barrio</th><th>Mz.</th><th>Lote</th><th>Flia.</th><th>Persona</th><th>Fecha Nac.</th>";

              $Ultimo_Mes = 00;

              $Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MensajeError);
              while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
                $FechaInt = strtotime($Ret["fecha"]);
                $Tomar_Anio = date("Y", $FechaInt);
                $Tomar_Mes = date("m", $FechaInt);

                if($Tomar_Mes != $Ultimo_Mes){
                  $Table .= "<th>".$Tomar_Mes."/".$Tomar_Anio."</th>";
                }

                $Ultimo_Mes = $Tomar_Mes;
              }


              $Table .= "</tr></thead>";

              $Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MensajeError);

              $Ultimo_Mes_Datos = 00;
              while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
                $FechaInt = strtotime($Ret["fecha"]);
                $Tomar_Anio = date("Y", $FechaInt);
                $Tomar_Mes = date("m", $FechaInt);

                $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$Ret["fecha_nac"])));

                $Consultar_Datos_Meses = "select M.id_movimiento, MT.cod_categoria, C.color from movimiento M, motivo MT, categoria C where M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret['id_movimiento']." and MONTh(M.fecha) = '$Tomar_Mes'";
                $MensajeErrorConsultar_Datos_Meses = "No se pudieron consultar los Datos de movimientos";
                $Tomar_Datos_Meses = mysqli_query($Con->Conexion,$Consultar_Datos_Meses) or die($MensajeErrorConsultar_Datos_Meses);
                $Consultar_Febrero;

                $Table .= "<tr>";
                $Table .= "<td>".$Ret["barrio"]."</td><td>".$Ret["manzana"]."</td><td>".$Ret["lote"]."</td><td>".$Ret["familia"]."</td><td><a href = 'javascript:window.open(\"view_verpersonas.php?ID=".$Ret["id_persona"]."\",\"Ventana".$Ret["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$Ret["apellido"].", ".$Ret["nombre"]."</a></td><td>".$Fecha_Nacimiento."</td>";

                $Table .= "<td>";

                if(isset($Ultimo_Mes_Datos) && $Ultimo_Mes_Datos != $Tomar_Mes){
                  $Table .= "</td><td>";
                  while ($RetEnero = mysqli_fetch_assoc($Tomar_Datos_Meses)) {
                    $Table .= "N° <a href = 'view_vermovimientos.php?ID=".$RetEnero["id_movimiento"]."'>".$RetEnero["id_movimiento"]."</a><br><span style='padding: 5px; color: white; background-color: ".$RetEnero["color"].";'>".$RetEnero["cod_categoria"]."</span><br>";
                  }
                  $Table .= "</td>";
                }else{
                   while ($RetEnero = mysqli_fetch_assoc($Tomar_Datos_Meses)) {
                    $Table .= "N° <a href = 'view_vermovimientos.php?ID=".$RetEnero["id_movimiento"]."'>".$RetEnero["id_movimiento"]."</a><br><span style='padding: 5px; color: white; background-color: ".$RetEnero["color"].";'>".$RetEnero["cod_categoria"]."</span><br>";
                  }
                }

                $Table .= "</td>";
                
                $Table .= "</tr>";

                $Ultimo_Mes_Datos = $Tomar_Mes;
              }
              $Con->CloseConexion();
              $Table .= "</table>";

              echo $Table;
            
             
            }else{
              echo "No se pudo obtener el año";
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