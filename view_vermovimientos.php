<?php 
session_start(); 
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
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
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
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

              $consultaGeneral = "CREATE TEMPORARY TABLE MTPERM select DISTINCT(MT.id_motivo), MT.motivo 
                                   from motivo MT inner join categoria C on (MT.cod_categoria = C.cod_categoria) 
                                     inner join categorias_roles CR on (CR.id_categoria = CR.id_categoria)" ;
              $MessageError = "Problemas al crear la tabla temporaria de usuarios";
              $Con->ResultSet = mysqli_query($Con->Conexion,$consultaGeneral
                                            ) or die($MessageError);

              $ConsultarDatos = "select M.id_movimiento, M.fecha, P.apellido,
                                        P.nombre, M.observaciones,
                                        R.responsable, M.id_resp_2,
                                        M.id_resp_3, M.id_resp_4,
                                        C.centro_salud, I.Nombre,
                                        M.motivo_1,
                                        M.motivo_1 not in (SELECT id_motivo
                                                           FROM MTPERM) as mt1,
                                        (SELECT motivo
                                         FROM motivo
                                         WHERE id_motivo = M.motivo_1) as Mot1,
                                        M.motivo_2, 
                                        M.motivo_2 not in (SELECT id_motivo
                                                           FROM MTPERM) as mt2,
                                        (SELECT motivo
                                         FROM motivo
                                         WHERE id_motivo = M.motivo_2) as Mot2,
                                        M.motivo_3,
                                        M.motivo_3 not in (SELECT id_motivo
                                                           FROM MTPERM) as mt3,
                                        (SELECT motivo
                                         FROM motivo
                                         WHERE id_motivo = M.motivo_3) as Mot3, 
                                        M.motivo_4,
                                        M.motivo_4 not in (SELECT id_motivo
                                                           FROM MTPERM) as mt4,
                                        (SELECT motivo
                                         FROM motivo
                                         WHERE id_motivo = M.motivo_4) as Mot4,
                                        M.motivo_5,
                                        M.motivo_5 not in (SELECT id_motivo
                                                           FROM MTPERM) as mt5,
                                        (SELECT motivo
                                         FROM motivo
                                         WHERE id_motivo = M.motivo_5) as Mot5
                                 from movimiento M 
                                      INNER JOIN persona P ON (M.id_persona = P.id_persona)
                                      INNER JOIN responsable R ON (M.id_resp = R.id_resp) 
                                      LEFT JOIN centros_salud C ON (M.id_centro = C.id_centro)
                                      LEFT JOIN otras_instituciones I ON (M.id_otrainstitucion = I.ID_OtraInstitucion )
                                 where M.id_movimiento = $ID_Movimiento";
              $MensajeErrorDatos = "No se pudo consultar los Datos del Movimiento";

              $EjecutarConsultarDatos = mysqli_query($Con->Conexion,$ConsultarDatos) or die($MensajeErrorDatos);

              $Ret = mysqli_fetch_assoc($EjecutarConsultarDatos);

              $ID_Movimiento = $Ret["id_movimiento"];
              $ID_Motivo_1 = $Ret["motivo_1"];
              $tieneRolMT1 = $Ret["mt1"];
              $nombreRolMT1 = $Ret["Mot1"];
              $ID_Motivo_2 = $Ret["motivo_2"];
              $tieneRolMT2 = $Ret["mt2"];
              $nombreRolMT2 = $Ret["Mot2"];
              $ID_Motivo_3 = $Ret["motivo_3"];
              $tieneRolMT3 = $Ret["mt3"];
              $nombreRolMT3 = $Ret["Mot3"];
              $ID_Motivo_4 = $Ret["motivo_4"];
              $tieneRolMT4 = $Ret["mt4"];
              $nombreRolMT4 = $Ret["Mot4"];
              $ID_Motivo_5 = $Ret["motivo_5"];
              $tieneRolMT5 = $Ret["mt5"];
              $nombreRolMT5 = $Ret["Mot5"];

              if($tieneRolMT1 == "1"){
                $ConsultarMotivo1 = "select MT.motivo 
                                     from motivo MT inner join categoria C on (MT.cod_categoria = C.cod_categoria) 
                                          inner join categorias_roles CR on (CR.id_categoria = CR.id_categoria) 
                                     where MT.id_motivo = $ID_Motivo_1 
                                     and (CR.id_tipousuario = $TipoUsuario or MT.id_motivo = 1)
                                     and CR.estado = 1";
                $MensajeErrorMotivo1 = "No se pudo consultar el Motivo 1";
                $EjecutarConsultarMotivo1 = mysqli_query($Con->Conexion,$ConsultarMotivo1) or die($MensajeErrorMotivo1);
                $RetMotivo1 = mysqli_fetch_assoc($EjecutarConsultarMotivo1);
                $Motivo_1 = (isset($RetMotivo1["motivo"])?$RetMotivo1["motivo"]:"");
              } else {
                $Motivo_1 = $nombreRolMT1;
              }
              if($tieneRolMT2 == "1"){
                $ConsultarMotivo2 = "select MT.motivo 
                                   from motivo MT inner join categoria C on (MT.cod_categoria = C.cod_categoria) 
                                     inner join categorias_roles CR on (CR.id_categoria = CR.id_categoria) 
                                   where MT.id_motivo = $ID_Motivo_2
                                   and (CR.id_tipousuario = $TipoUsuario or MT.id_motivo = 1)
                                     and CR.estado = 1";
                $MensajeErrorMotivo2 = "No se pudo consultar el Motivo 2";
                $EjecutarConsultarMotivo2 = mysqli_query($Con->Conexion,$ConsultarMotivo2) or die($MensajeErrorMotivo2);
                $RetMotivo2 = mysqli_fetch_assoc($EjecutarConsultarMotivo2);
                $Motivo_2 = (isset($RetMotivo2["motivo"])?$RetMotivo2["motivo"]:"");
              } else {
                $Motivo_2 = $nombreRolMT2;
              }
              if($tieneRolMT3 == "1"){
                  $ConsultarMotivo3 = "select MT.motivo 
                                      from motivo MT inner join categoria C on (MT.cod_categoria = C.cod_categoria) 
                                        inner join categorias_roles CR on (CR.id_categoria = CR.id_categoria) 
                                      where MT.id_motivo = $ID_Motivo_3 
                                        and (CR.id_tipousuario = $TipoUsuario or MT.id_motivo = 1)
                                        and CR.estado = 1";
                  $MensajeErrorMotivo3 = "No se pudo consultar el Motivo 3";
                  $EjecutarConsultarMotivo3 = mysqli_query($Con->Conexion,$ConsultarMotivo3) or die($MensajeErrorMotivo3);
                  $RetMotivo3 = mysqli_fetch_assoc($EjecutarConsultarMotivo3);
                  $Motivo_3 = (isset($RetMotivo3["motivo"])?$RetMotivo3["motivo"]:"");
              } else {
                $Motivo_3 = $nombreRolMT3;
              }
              if($tieneRolMT4 == "1"){
                  $ConsultarMotivo4 = "select MT.motivo 
                                      from motivo MT inner join categoria C on (MT.cod_categoria = C.cod_categoria) 
                                        inner join categorias_roles CR on (CR.id_categoria = CR.id_categoria) 
                                      where MT.id_motivo = $ID_Motivo_4 
                                        and (CR.id_tipousuario = $TipoUsuario or MT.id_motivo = 1)
                                        and CR.estado = 1";
                  $MensajeErrorMotivo4 = "No se pudo consultar el Motivo 4";
                  $EjecutarConsultarMotivo4 = mysqli_query($Con->Conexion,$ConsultarMotivo4) or die($MensajeErrorMotivo4);
                  $RetMotivo4 = mysqli_fetch_assoc($EjecutarConsultarMotivo4);
                  $Motivo_4 = (isset($RetMotivo4["motivo"])?$RetMotivo4["motivo"]:"");
              } else {
                $Motivo_4 = $nombreRolMT4;
              }

              if($tieneRolMT5 == "1"){
                  $ConsultarMotivo5 = "select MT.motivo 
                                      from motivo MT inner join categoria C on (MT.cod_categoria = C.cod_categoria) 
                                        inner join categorias_roles CR on (CR.id_categoria = CR.id_categoria) 
                                      where MT.id_motivo = $ID_Motivo_5 
                                        and (CR.id_tipousuario = $TipoUsuario or MT.id_motivo = 1)
                                        and CR.estado = 1";
                  $MensajeErrorMotivo5 = "No se pudo consultar el Motivo 5";
                  $EjecutarConsultarMotivo5 = mysqli_query($Con->Conexion,$ConsultarMotivo5) or die($MensajeErrorMotivo5);
                  $RetMotivo5 = mysqli_fetch_assoc($EjecutarConsultarMotivo5);
                  $Motivo_5 = (isset($RetMotivo5["motivo"])?$RetMotivo5["motivo"]:"");
              } else {
                $Motivo_5 = $nombreRolMT5;
              }

              $Fecha = $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$Ret["fecha"])));
              $Apellido = $Ret["apellido"];
              $Nombre = $Ret["nombre"];
              $Observaciones = $Ret["observaciones"];
              $Responsable = $Ret["responsable"];
              $ID_Resp_2 = $Ret["id_resp_2"];
              $ID_Resp_3 = $Ret["id_resp_3"];
              $ID_Resp_4 = $Ret["id_resp_4"];
              $Centro_Salud = (!empty($Ret["centro_salud"])) ? $Ret["centro_salud"] : null;
              $OtraInstitucion = (!empty($Ret["Nombre"])) ? $Ret["Nombre"] : null;

              $DtoMovimiento = new DtoMovimiento($ID_Movimiento,$Fecha,$Apellido,$Nombre,$Motivo_1,$Motivo_2,$Motivo_3,$Motivo_4,$Motivo_5,$Observaciones,$Responsable,$Centro_Salud,$OtraInstitucion);


              if($ID_Resp_2 != null){
                $Consultar_Resp2 = "select responsable from responsable where id_resp = $ID_Resp_2 limit 1";
                $MensajeErrorResp2= "No se pudo consultar los datos del responsable 2";
                $Ejecutar_Resp2 = mysqli_query($Con->Conexion, $Consultar_Resp2) or die($MensajeErrorResp2);
                $RetResp2 = mysqli_fetch_assoc($Ejecutar_Resp2);
                $Responsable_2 = $RetResp2["responsable"];
              }

              if($ID_Resp_3 != null){
                $Consultar_Resp3 = "select responsable from responsable where id_resp = $ID_Resp_3 limit 1";
                $MensajeErrorResp3= "No se pudo consultar los datos del responsable 3";
                $Ejecutar_Resp3 = mysqli_query($Con->Conexion, $Consultar_Resp3) or die($MensajeErrorResp3);
                $RetResp3 = mysqli_fetch_assoc($Ejecutar_Resp3);
                $Responsable_3 = $RetResp3["responsable"];
              }

              if($ID_Resp_4 != null){
                $Consultar_Resp4 = "select responsable from responsable where id_resp = $ID_Resp_4 limit 1";
                $MensajeErrorResp4= "No se pudo consultar los datos del responsable 4";
                $Ejecutar_Resp4 = mysqli_query($Con->Conexion, $Consultar_Resp4) or die($MensajeErrorResp4);
                $RetResp4 = mysqli_fetch_assoc($Ejecutar_Resp4);
                $Responsable_4 = $RetResp4["responsable"];
              }

              $Table = "<table class='table'><thead><tr><th></th><th>Detalles del Movimiento</th></tr></thead>";

              $Table .= "<tr><td>Fecha</td><td>".$DtoMovimiento->getFecha()."</td></tr>";
              $Table .= "<tr><td>Apellido</td><td>".$DtoMovimiento->getApellido()."</td></tr>";
              $Table .= "<tr><td>Nombre</td><td>".$DtoMovimiento->getNombre()."</td></tr>";
              $Table .= "<tr><td>Motivo 1</td><td>".$DtoMovimiento->getMotivo_1()."</td></tr>";
              $Table .= "<tr><td>Motivo 2</td><td>".$DtoMovimiento->getMotivo_2()."</td></tr>";
              $Table .= "<tr><td>Motivo 3</td><td>".$DtoMovimiento->getMotivo_3()."</td></tr>";
              $Table .= "<tr><td>Motivo 4</td><td>".$DtoMovimiento->getMotivo_4()."</td></tr>";
              $Table .= "<tr><td>Motivo 5</td><td>".$DtoMovimiento->getMotivo_5()."</td></tr>";
              $Table .= "<tr><td>Observaciones</td><td>".$DtoMovimiento->getObservaciones()."</td></tr>";
              $Table .= "<tr><td>Responsable</td><td>".$DtoMovimiento->getResponsable()."</td></tr>";
              if($ID_Resp_2 != null){
                $Table .= "<tr><td>Responsable 2</td><td>".$Responsable_2."</td></tr>";
              }
              if($ID_Resp_3 != null){
                $Table .= "<tr><td>Responsable 3</td><td>".$Responsable_3."</td></tr>";
              }
              if($ID_Resp_4 != null){
                $Table .= "<tr><td>Responsable 4</td><td>".$Responsable_4."</td></tr>";
              }
              $Table .= "<tr><td>Centro de Salud</td><td>".$DtoMovimiento->getCentroSalud()."</td></tr>";
              $Table .= "<tr><td>Institucion</td><td>".$DtoMovimiento->getOtraInstitucion()."</td></tr>";

              $Table .= "</table>";

              echo $Table;

              $Con->CloseConexion();
              

            }else{
              $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID del Movimiento";
              echo $Mensaje;
            }
          ?>
        </div>
        <div class="row">
            <div class="col-10">
              
            </div>
            <div class="col-2">
              
              <button type = "button" class = "btn btn-danger" onClick = "location.href = 'view_movimientos.php'">Atras</button>
            </div>
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