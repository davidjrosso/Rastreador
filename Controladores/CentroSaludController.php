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

require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Movimiento.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/CentroSalud.php") ;

class CentroSaludController 
{
    const CHARS_DEN = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
    const CHARS_APROV = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;",
                                   "& #060;", "& #062;", "& #039;", "& #047;");

    public function listado_centros_salud($mensaje = null, $id_filtro = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $Element = new Elements();
            $DTGeneral = new CtrGeneral();

            $Filtro = null;
            $ID_Filtro = null;
            if (isset($_REQUEST["Filtro"])) $Filtro = $_REQUEST["Filtro"];
            if (isset($_REQUEST["ID_Filtro"])) $ID_Filtro = $_REQUEST["ID_Filtro"];

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_centros.php");
        }
        exit();
    }

    public function buscar_centos_salud($filtro_nombre = null, $filtro_id = null)
    {
        $filtro = $_REQUEST["Search"];
        $id_filtro = $_REQUEST["ID_Filtro"];
        header("Location: /centrosdesalud?Filtro=" . $filtro . "&ID_Filtro=" . $id_filtro);
        exit();
    }

    /*
    public function mod_centro_salud($id_centro)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();
            $DTGeneral = new CtrGeneral();
            
            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            $exist = false;

            if(isset($_REQUEST["ID"])){
              $exist = true;
              $id_centro = $_REQUEST["ID"];

              $con = new Conexion();
              $con->OpenConexion();
              $centro_salud = new CentroSalud(
                                              coneccion_base: $con, 
                                              id_centro: $id_centro
                                            );

              $id_centro = $centro_salud->get_id_centro();
              $centro_salud_nombre = $centro_salud->get_centro_salud();
              $list_arch = [];
              if (Archivo::exist_id_cs(id: $id_centro, coneccion: $con)) {
                $list_arch = Archivo::get_ids_cs( id: $id_centro, coneccion: $con );              
              }
             
            }

            include("./Views/view_modcentros.php");
        }
        exit();
    }
    */

    public function crear_centro_salud()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();
            $DTGeneral = new CtrGeneral();
            
            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_newcentros.php");
        }
        exit();
    }

    public function centro_salud_lista()
    {
        $consulta = $_REQUEST['valorBusqueda'];
        $id = $_REQUEST['ID'];

        //Filtro anti-XSS

        $consulta = str_replace(self::CHARS_DEN, self::CHARS_APROV, $consulta);

        //Variable vacía (para evitar los E_NOTICE)
        $mensaje = "";

        if (isset($consulta)) {

            $con = new Conexion();
            $con->OpenConexion();

            $list = CentroSalud::get_list_por_nombre(coneccion: $con, centro_salud: $consulta);
            $con->CloseConexion();

            $cant = count($list);

            if (!$cant) {
                $mensaje = "<p>No hay ningún registro con ese dato</p>";
            } else {

                $mensaje .= '<table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                    <th scope="col">Centro de Salud</th>
                                    <th scope="col">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>';

                foreach($list as $val => $centro_salud) {

                    $mensaje .= '
                        <tr>
                            <td scope="row">' . $centro_salud->get_centro_salud() . '</td>
                            <td>
                                <button type = "button" class = "btn btn-outline-success" 
                                        onClick="seleccionCentro(' . $id . ',\'' . $centro_salud->get_centro_salud() . '\',' . $centro_salud->get_id_centro() . ')" data-dismiss="modal">
                                    seleccionar
                                </button>
                            </td>
                        </tr>';

                };

                $mensaje .= '</tbody>
                    </table>';

            };
        }
        echo $mensaje;
    }

    public function del_centro_control($id_centro)
    {
        $id_usuario = $_SESSION["Usuario"];

        $id_centro = $_REQUEST["ID"];

        $fecha = date("Y-m-d");
        $id_tipoAccion = 3;
        $detalles = "El usuario con ID: $id_usuario ha dado de baja un Centro de Salud. Datos: Centro: $id_centro";

        try {
            $con = new Conexion();
            $con->OpenConexion();

            $centro = new CentroSalud(coneccion_base: $con, id_centro: $id_centro);
            $centro->delete();

            $accion = new Accion(
                xaccountid: $id_usuario,
                xFecha: $fecha,
                xDetalles: $detalles,
                xID_TipoAccion: $id_tipoAccion
            );
            $accion->save();

            $con->CloseConexion();

            $mensaje = "El centro de salud fue eliminado Correctamente";
            header('Location: /centrosdesalud?Mensaje=' . $mensaje);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        exit();
    }

    public function mod_centro_salud_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Centro = $_REQUEST["ID"];
        $Centro_Salud = ucfirst($_REQUEST["Centro_Salud"]);

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        try {

            $Con = new Conexion();
            $Con->OpenConexion();
            $cant = CentroSalud::exist_nombre_con_diferente_id(
                                                               coneccion: $Con,
                                                               nombre: $Centro_Salud,
                                                               id_centro_salud: $ID_Centro
                                                               );
            if ($cant) {
                $Con->CloseConexion();
                $Mensaje = "Ya existe un Centro de Salud con ese Nombre";
                header('Location: /centrosalud/editar?ID=' . $ID_Centro . '&MensajeError=' . $Mensaje);
            } else {
                $centro = new CentroSalud(coneccion_base: $Con, id_centro: $ID_Centro);

                $CentroViejo = $centro->get_centro_salud();
                $centro->set_centro_salud($Centro_Salud );
                $centro->udpate();                


                $detalles = "El usuario con ID: $ID_Usuario ha modificado un Centro de Salud. Datos: Dato Anterior: $CentroViejo , Dato Nuevo: $Centro_Salud";

                $accion = new Accion(xaccountid: $ID_Usuario,
                                    xFecha: $Fecha,
                                    xDetalles: $detalles,
                                    xID_TipoAccion: $ID_TipoAccion
                                    );
                $accion->save();
                $Mensaje = "El Centro de Salud se modificó Correctamente";
                header('Location: /centrosalud/editar?ID=' . $ID_Centro . '&Mensaje=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        exit();
    }

    public function insert_centro_salud()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $Centro_Salud = ucfirst($_REQUEST["Centro"]);
        $Estado = 1;

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 1;
        $detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Centro de Salud. Datos: $Centro_Salud";

        $Con = new Conexion();
        $Con->OpenConexion();

        try {
            $exist = CentroSalud::get_id_por_nombre(coneccion: $Con, centro_salud: $Centro_Salud);
            if($exist) {
                $Con->CloseConexion();
                $Mensaje = "Ya existe un Centro de Salud con ese Nombre";
                header('Location: /centrodesalud/nuevo?MensajeError=' . $Mensaje);
            } else {

                $centro = new CentroSalud(coneccion_base: $Con, estado: $Estado);
                $centro->save();

                $accion = new Accion(xaccountid: $ID_Usuario,
                                    xFecha: $Fecha,
                                    xDetalles: $detalles,
                                    xID_TipoAccion: $ID_TipoAccion
                                    );
                $accion->save();
                $Con->CloseConexion();
                $Mensaje = "El Centro de Salud se registro Correctamente";
                header('Location: /centrodesalud/nuevo?Mensaje=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function sol_unif_control()
    {
        $Fecha = Date("Y-m-d");
        $ID_Registro_1 = $_REQUEST["ID_Centro_1"];
        $ID_Registro_2 = $_REQUEST["ID_Centro_2"];
        $ID_Usuario = $_SESSION["Usuario"];
        $Estado = 1;
        $TipoUnif = 3;

        if ($ID_Registro_1 && $ID_Registro_2) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Solicitud = new Solicitud_Unificacion(
                                                   coneccion: $Con,
                                                   xFecha: $Fecha,
                                                   xID_Registro_1: $ID_Registro_1,
                                                   xID_Registro_2: $ID_Registro_2,
                                                   xID_Usuario: $ID_Usuario,
                                                   xEstado: $Estado,
                                                   xTipoUnif: $TipoUnif);
            $Solicitud->save();
            $Con->CloseConexion();
            $Mensaje = "La solicitud de unificación se envió a los administradores para ser confirmada.";
            header('Location: /centrosalud/unificar?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Centro y Segundo Centro";
            header('Location: /centrosalud/unificar?MensajeError=' . $MensajeError);
        }
        exit();
    }

    public function unif_centro_salud($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $Element = new Elements();

            include("./Views/view_unifcentros.php");
        }
        exit();
    }

    public function unif_centro_salud_control()
    {
        $ID_Solicitud = $_REQUEST["ID_Solicitud"];
        $ID_Centro_1 = $_REQUEST["ID_Centro_1"];
        $ID_Centro_2 = $_REQUEST["ID_Centro_2"];

        if ($ID_Centro_1 && $ID_Centro_2) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $lista = Movimiento::get_list_movimiento(coneccion: $Con, id_movimiento: $ID_Centro_2);

            foreach($lista as $val => $movimiento) {
                $movimiento->setID_Centro($ID_Centro_1);
                $movimiento->udpate();
            }

            $centro_salud_2 = new CentroSalud(coneccion_base: $Con, id_centro: $ID_Centro_2);
            $centro_salud_2->delete();

            $sl = new Solicitud_Unificacion(coneccion: $Con, xID_Solicitud: $ID_Solicitud);
            $sl->delete();
            $Con->CloseConexion();
            $Mensaje = "Los datos se unificaron Correctamente";
            header('Location: /home?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Centro y Segundo Centro";
            header('Location: /home?MensajeError=' . $MensajeError);
        }
        exit();
    }
}