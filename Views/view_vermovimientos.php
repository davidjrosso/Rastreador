<?php 

use function PHPUnit\Framework\isNull;
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


require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Elements.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/CtrGeneral.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Movimiento.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Responsable.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Account.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/MovimientoMotivo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/DtoMovimiento.php';
header("Content-Type: text/html;charset=utf-8");

$ID_Usuario = $_SESSION["Usuario"];
$usuario = new Account(account_id: $ID_Usuario);
$TipoUsuario = $usuario->get_id_tipo_usuario();
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

    if (isset($_REQUEST["ID"]) && $_REQUEST["ID"] != null) {
      $ID_Movimiento = $_REQUEST["ID"];

      $Con = new Conexion();
      $Con->OpenConexion();

      $consultaGeneral = "CREATE TEMPORARY TABLE MTPERM SELECT DISTINCT(MT.id_motivo), MT.motivo 
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
                                MT.motivo as Mot
                          from movimiento M 
                              INNER JOIN movimiento_motivo MEMT ON (M.id_movimiento = MEMT.id_movimiento)
                              INNER JOIN motivo MT ON (MEMT.id_motivo = MT.id_motivo)
                              INNER JOIN MTPERM ME ON (MT.id_motivo = ME.id_motivo)
                              INNER JOIN persona P ON (M.id_persona = P.id_persona)
                              INNER JOIN responsable R ON (M.id_resp = R.id_resp) 
                              LEFT JOIN centros_salud C ON (M.id_centro = C.id_centro)
                              LEFT JOIN otras_instituciones I ON (M.id_otrainstitucion = I.ID_OtraInstitucion )
                          where M.id_movimiento = $ID_Movimiento";
      $MensajeErrorDatos = "No se pudo consultar los Datos del Movimiento";

      $EjecutarConsultarDatos = mysqli_query($Con->Conexion,$ConsultarDatos) or die($MensajeErrorDatos);

      $Ret = mysqli_fetch_assoc($EjecutarConsultarDatos);
  }
  if (!isset($Ret)) {
        $empty_query_message = "El movimiento no posee datos.";
        header("Location: /movimientos?MensajeError=" . $empty_query_message);
        exit();
  } else {
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
              $id_motivo = $Ret["Mot"];

              $DtoMovimiento = new DtoMovimiento(
                                                 xID_Movimiento: $ID_Movimiento,
                                                 xFecha: $Fecha,
                                                 xApellido: $Apellido,
                                                 xNombre: $Nombre,
                                                 xMotivo_1: $id_motivo ,
                                                 xObservaciones: $Observaciones,
                                                 xResponsable: $Responsable,
                                                 xCentroSalud: $Centro_Salud,
                                                 xOtraInstitucion: $OtraInstitucion
                                                );
              $count_motivo = 2;
              while ($Ret = mysqli_fetch_assoc($EjecutarConsultarDatos)) {
                if ($count_motivo == 2) $DtoMovimiento->setMotivo_2($Ret["Mot"]);
                if ($count_motivo == 3) $DtoMovimiento->setMotivo_3($Ret["Mot"]);
                if ($count_motivo == 4) $DtoMovimiento->setMotivo_4($Ret["Mot"]);
                if ($count_motivo == 5) $DtoMovimiento->setMotivo_5($Ret["Mot"]);
                $count_motivo++;
              }

              if ($ID_Resp_2 != null) {
                $responsable = new Responsable(
                                              coneccion_base: $Con,
                                              id_responsable: $ID_Resp_2
                                              );
                $Responsable_2 = $responsable->get_responsable();
              }

              if ($ID_Resp_3 != null) {
                $responsable = new Responsable(
                                              coneccion_base: $Con,
                                              id_responsable: $ID_Resp_3
                                              );
                $Responsable_3 = $responsable->get_responsable();
              }

              if ($ID_Resp_4 != null) {
                $responsable = new Responsable(
                                              coneccion_base: $Con,
                                              id_responsable: $ID_Resp_4
                                              );
                $Responsable_4 = $responsable->get_responsable();
              }

              $Table = "<table class='table'>
                          <thead>
                            <tr>
                              <th></th>
                              <th>Detalles del Movimiento</th>
                            </tr>
                          </thead>";

              $Table .= "<tr>
                            <td>Fecha</td>
                            <td>" . $DtoMovimiento->getFecha() . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Apellido</td>
                            <td>" . $DtoMovimiento->getApellido() . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Nombre</td>
                            <td>" . $DtoMovimiento->getNombre() . "</td>
                          </tr>";
              $Table .= "<tr>
                            <td>Motivo 1</td>
                            <td>" . $DtoMovimiento->getMotivo_1() . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Motivo 2</td>
                            <td>" . $DtoMovimiento->getMotivo_2() . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Motivo 3</td>
                            <td>" . $DtoMovimiento->getMotivo_3() . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Motivo 4</td>
                            <td>" . $DtoMovimiento->getMotivo_4() . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Motivo 5</td>
                            <td>" . $DtoMovimiento->getMotivo_5() . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Observaciones</td>
                            <td>" . $DtoMovimiento->getObservaciones() . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Responsable</td>
                            <td>" . $DtoMovimiento->getResponsable() . "</td>
                         </tr>";
              if($ID_Resp_2 != null){
                $Table .= "<tr>
                              <td>Responsable 2</td>
                              <td>" . $Responsable_2 . "</td>
                           </tr>";
              }
              if($ID_Resp_3 != null){
                $Table .= "<tr>
                              <td>Responsable 3</td>
                              <td>" . $Responsable_3 . "</td>
                           </tr>";
              }
              if($ID_Resp_4 != null){
                $Table .= "<tr>
                              <td>Responsable 4</td>
                              <td>" . $Responsable_4 . "</td>
                           </tr>";
              }
              $Table .= "<tr>
                            <td>Centro de Salud</td>
                            <td>" . $DtoMovimiento->getCentroSalud() . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Institucion</td>
                            <td>" . $DtoMovimiento->getOtraInstitucion() . "</td>
                         </tr>";

              $Table .= "</table>";

              echo $Table;

              $Con->CloseConexion();
              

            }
          ?>
        </div>
        <div class="row">
            <div class="col-10">
              
            </div>
            <div class="col-2">       
              <button type = "button" class = "btn btn-danger" onClick = "location.href = '/movimientos'">Atras</button>
            </div>
        </div>
  </div>
</div>
</div>
</body>
</html>