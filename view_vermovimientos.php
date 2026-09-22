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

session_start(); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Elements.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Persona.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/CtrGeneral.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Movimiento.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Responsable.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Account.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/MovimientoMotivo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Categoria.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/CategoriaRol.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/DtoMovimiento.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/OtraInstitucion.php';

header("Content-Type: text/html;charset=utf-8");

/*     CONTROL DE USUARIOS                    */
if(!isset($_SESSION["Usuario"])){
    header("Location: Error_Session.php");
    exit();
}

$ID_Usuario = $_SESSION["Usuario"];
$usuario = new Account(account_id: $ID_Usuario);
$TipoUsuario = $usuario->get_id_tipo_usuario();
$Element = new Elements();

if (isset($_REQUEST["ID"])) {
    $ID_Movimiento = $_REQUEST["ID"];

    $Con = new Conexion();
    $Con->OpenConexion();

    $movimiento = new Movimiento(coneccion_base: $Con,
                                  xID_Movimiento: $ID_Movimiento);

    $persona = new Persona(ID_Persona: $movimiento->getID_Persona());

    $responsable = new Responsable(coneccion_base: $Con, 
                                    id_responsable: $movimiento->getID_Responsable()
                                    );

    $Centro_Salud = null;
    if ($movimiento->getID_Centro()){
        $centro = new CentroSalud(coneccion_base: $Con,
                                  id_centro: $movimiento->getID_Centro());   
        $Centro_Salud =  $centro->get_centro_salud();       
    }

    $OtraInstitucion = null;
    if ($movimiento->getID_OtraInstitucion()) {
        $ins = new OtraInstitucion(xConeccion: $Con,
                                  xID_OtraInstitucion: $movimiento->getID_OtraInstitucion()
                                  );
        $OtraInstitucion = $ins->getNombre();
    }

    $Fecha = $Fecha_Nacimiento = implode("-", array_reverse(explode("-", $movimiento->getFecha())));
    $Apellido = $persona->getApellido();
    $Nombre = $persona->getNombre();
    $Observaciones = $movimiento->getObservaciones();
    $Responsable = $responsable->get_responsable();
    $ID_Resp_2 = $movimiento->getID_Responsable_2();
    $ID_Resp_3 = $movimiento->getID_Responsable_3();
    $ID_Resp_4 = $movimiento->getID_Responsable_4();

    $lista_motivo = MovimientoMotivo::get_lista_motivos_por_movimiento(
                                                    coneccion: $Con,
                                                    movimiento: $movimiento
                                                  );
    $lista_motivo_nombre = [];

    foreach ($lista_motivo as $motivo) {
        $exist = false;
        $id_categoria = Categoria::exist_cod_categoria(connection: $Con,
                                                       cod_categoria: $motivo->get_cod_categoria());
        if ($id_categoria) {
            $categoria = new Categoria(xConecction: $Con,
                                       xID_Categoria: $id_categoria
                                      );
            $exist = CategoriaRol::exist_rol(connection: $Con, 
                                            id_categoria: $categoria->getID_Categoria(),
                                            id_tipo_usuario: $TipoUsuario
                                            );
        }

        if ($exist) {
            $lista_motivo_nombre[] = $motivo->get_motivo();
        }
    }

    if ($ID_Resp_2) {
      $responsable = new Responsable(
                                    coneccion_base: $Con,
                                    id_responsable: $ID_Resp_2
                                    );
      $Responsable_2 = $responsable->get_responsable();
    }

    if ($ID_Resp_3) {
      $responsable = new Responsable(
                                    coneccion_base: $Con,
                                    id_responsable: $ID_Resp_3
                                    );
      $Responsable_3 = $responsable->get_responsable();
    }

    if ($ID_Resp_4) {
      $responsable = new Responsable(
                                    coneccion_base: $Con,
                                    id_responsable: $ID_Resp_4
                                    );
      $Responsable_4 = $responsable->get_responsable();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</head>
<body>
<div class = "row margin-right-cero">
<?php
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_MOVIMIENTO);
  ?>
  <div class = "col-md-9 inicio-md-2">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Movimientos</p>
      </div>
      <div class="col"></div>
    </div>
    <br>
    <br>
    <div class = "row" style="justify-content: center;">
      <div class = "col-10">
          <!-- Search -->
        <div class = "row">
          <?php  
            if (isset($_REQUEST["ID"])) {

              $Table = "<table class='table'>
                          <thead>
                            <tr>
                              <th colspan=2 style='text-align: center;'>Detalles del Movimiento</th>
                            </tr>
                          </thead>";

              $Table .= "<tr>
                            <td>Fecha</td>
                            <td>" . $Fecha . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Apellido</td>
                            <td>" . $Apellido . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Nombre</td>
                            <td>" . $Nombre . "</td>
                          </tr>";
              foreach ($lista_motivo_nombre as $key => $motivo_nombre) {
                  $Table .= "<tr>
                                <td>Motivo " . ($key + 1) . "</td>
                                <td>" . $motivo_nombre . "</td>
                            </tr>";
              }

              $Table .= "<tr>
                            <td>Observaciones</td>
                            <td>" . $movimiento->getObservaciones() . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Responsable</td>
                            <td>" . $Responsable . "</td>
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
                            <td>" . $Centro_Salud . "</td>
                         </tr>";
              $Table .= "<tr>
                            <td>Institucion</td>
                            <td>" . $OtraInstitucion . "</td>
                         </tr>";

              $Table .= "</table>";

              echo $Table;

              $Con->CloseConexion();
              

            } else {
              $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID del Movimiento";
              echo $Mensaje;
            }
          ?>
        </div>
        <div class="row" style="justify-content: center;">
            <div class="col-2">       
              <button type = "button" class = "btn btn-danger" onClick = "location.href = 'view_movimientos.php'">Atras</button>
            </div>
        </div>
        <br>
  </div>
</div>
</div>
</body>
</html>