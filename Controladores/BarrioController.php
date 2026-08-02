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

require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . '/Modelo/Barrio.php');
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");


class BarrioController 
{

    public function listado_barrios($mensaje = null)
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

            include("./Views/view_barrios.php");
        }
        exit();
    }

    public function mod_barrio($id_barrio)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_modbarrios.php");
        }
        exit();
    }

    public function datos_barrio($id_barrio)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            //include("./Views/Error_Session.php");
            include("../Error_Session.php");
        } else {
            $id_usuario = $_SESSION["Usuario"];
            $account = new Account(account_id: $id_usuario);
            $tipo_usuario = $account->get_id_tipo_usuario();
            $Element = new Elements();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            //include("./Views/view_verbarrios.php");
            include("../view_verbarrios.php");
        }
        exit();
    }

    public function crear_barrio($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            //include("./Views/Error_Session.php");
            include("../Error_Session.php");
        } else {
            $id_usuario = $_SESSION["Usuario"];
            $account = new Account(account_id: $id_usuario);
            $tipo_usuario = $account->get_id_tipo_usuario();
            $Element = new Elements();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            //include("./Views/view_newbarrios.php");
            include("../view_newbarrios.php");
        }
        exit();
    }

    public function crear_barrio_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $Barrio = ucwords($_REQUEST["Barrio"]);
        $Estado = 1;

        $georeferencia_point = null;
        if (!empty($_REQUEST["lat"])) {
            $lat_point = $_REQUEST["lat"];
            $georeferencia_point = "POINT(" . $lat_point;

            if (!empty($_REQUEST["lon"])){
                $lon_point = $_REQUEST["lon"];
                $georeferencia_point .= "," . $lon_point . ")";
            } else {
                $georeferencia_point = null;
            }
        }

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 1;
        $Detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Barrio. Datos: $Barrio";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();
            $existe = Barrio::existe_barrio(coneccion: $Con, name: $Barrio);
            if ($existe > 0) {
                $Con->CloseConexion();
                $Mensaje = "Ya existe un Barrio con ese Nombre";
                header('Location: /barrio/nuevo?MensajeError=' . $Mensaje);
            } else {
                $barrio = new Barrio(coneccion: $Con, barrio: $Barrio, georeferencia: $georeferencia_point);
                $barrio->save(coneccion: $Con);

                $accion = new Accion(
                    xaccountid: $ID_Usuario,
                    xFecha : $Fecha,
                    xDetalles: $Detalles,
                    xID_TipoAccion: $ID_TipoAccion	 
                );
                $accion->save();
                $Con->CloseConexion();
                $Mensaje = "El Barrio se registro Correctamente";
                header('Location: /barrio/nuevo?Mensaje=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function del_barrio_control($id_barrio)
    {
        $id_usuario = $_SESSION["Usuario"];

        $id_barrio = $_REQUEST["ID"];

        $fecha = date("Y-m-d");
        $id_tipo_accion = 3;
        $detalles = "El usuario con ID: $id_usuario ha dado de baja un Barrio. Datos: Barrio: $id_barrio";

        try {
            $con = new Conexion();
            $con->OpenConexion();

            $barrio = new Barrio(coneccion: $con, id_barrio: $id_barrio);

            $barrio->delete();

            $accion = new Accion(
                xaccountid: $id_usuario,
                xFecha : $fecha,
                xDetalles: $detalles,
                xID_TipoAccion: $id_tipo_accion	 
            );
            $accion->save();

            $con->CloseConexion();
            $mensaje = "El barrio fue eliminado Correctamente";
            //header('Location: /barrios?Mensaje=' . $Mensaje);
            header('Location: /barrios?Mensaje=' . $mensaje);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        exit();
    }

    public function mod_barrio_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Barrio = $_REQUEST["ID"];
        $barrio_nombre = ucwords($_REQUEST["Barrio"]);

        $georeferencia_point = null;

        if (!empty($_REQUEST["lat"])) {
            $lat_point = $_REQUEST["lat"];
            $georeferencia_point = "POINT(" . $lat_point;

            if (!empty($_REQUEST["lon"])){
                $lon_point = $_REQUEST["lon"];
                $georeferencia_point .= "," . $lon_point . ")";
            } else {
                $georeferencia_point = null;
            }
        }

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        $Con = new Conexion();
        $Con->OpenConexion();

        try {
            $Con = new Conexion();
            $Con->OpenConexion();
            $existe = Barrio::existe_barrio(coneccion: $Con, name: $barrio_nombre);
            $id_barrio_control = Barrio::get_id_by_name(coneccion: $Con, name: $barrio_nombre);
            if ($existe > 0 && $id_barrio_control != $ID_Barrio) {
                $Con->CloseConexion();
                $Mensaje = "Ya existe un Barrio con ese Nombre";
                header('Location: /barrio/editar?ID=' . $ID_Barrio . '&MensajeError=' . $Mensaje);
            } else {
                $barrio = new Barrio(coneccion: $Con, id_barrio: $ID_Barrio);

                $barrio_viejo = $barrio->get_barrio();

                $barrio->set_barrio($barrio_nombre);
                $barrio->set_georeferencia($georeferencia_point);
                $barrio->update($Con);

                $Detalles = "El usuario con ID: $ID_Usuario ha modificado un Barrio. Datos: Dato Anterior: $barrio_viejo , Dato Nuevo: $barrio_nombre";
                $accion = new Accion(
                    xaccountid: $ID_Usuario,
                    xFecha : $Fecha,
                    xDetalles: $Detalles,
                    xID_TipoAccion: $ID_TipoAccion	 
                );
                $accion->save();
                
                $Con->CloseConexion();
                $Mensaje = "El Barrio se modificó Correctamente";
                header('Location: /barrio/editar?ID=' . $ID_Barrio . '&Mensaje=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function sol_unif_barrio()
    {
        $Fecha = Date("Y-m-d");
        $ID_Registro_1 = $_REQUEST["ID_Barrio_1"];
        $ID_Registro_2 = $_REQUEST["ID_Barrio_2"];
        $ID_Usuario = $_SESSION["Usuario"];
        $Estado = 1;
        $TipoUnif = 5;

        if ($ID_Registro_1 && $ID_Registro_2) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Solicitud = new Solicitud_Unificacion(
                                                    xFecha: $Fecha,
                                                    xID_Registro_1: $ID_Registro_1,
                                                    xID_Registro_2: $ID_Registro_2,
                                                    xID_Usuario: $ID_Usuario,
                                                    xEstado: $Estado,
                                                    xTipoUnif: $TipoUnif,
                                                    coneccion: $Con);
            $Solicitud->save();

            $Con->CloseConexion();
            $Mensaje = "La solicitud de unificación se envió a los administradores para ser confirmada.";
            header('Location: /barrio/unificar?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Barrio y Segundo Barrio";
            header('Location: /barrio/unificar?MensajeError=' . $MensajeError);
        }
        exit();
    }

    public function unif_barrios($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();

            include("./Views/view_unifbarrios.php");
        }
        exit();
    }

    public function unif_barrio_control()
    {
        $id_solicitud = $_REQUEST["ID_Solicitud"];
        $ID_Barrio_1 = $_REQUEST["ID_Barrio_1"];
        $ID_Barrio_2 = $_REQUEST["ID_Barrio_2"];

        if($ID_Barrio_1 && $ID_Barrio_2) {
            $con = new Conexion();
            $con->OpenConexion();

            $list = Persona::get_list_barrios(coneccion: $con, id_barrio: $ID_Barrio_2);

            foreach ($list as $val => $persona) {
                $persona->set_barrio($ID_Barrio_1);
                $persona->update_barrio();
            }

            $barrio = new Barrio(coneccion: $con, id_barrio: $ID_Barrio_2);
            $barrio->delete();

            $sl = new Solicitud_Unificacion(coneccion: $con, xID_Solicitud: $id_solicitud);
            $sl->delete();

            $con->CloseConexion();
            $Mensaje = "Los datos se unificaron Correctamente";
            header('Location: /home?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Barrio y Segundo Barrio";
            header('Location: /home?MensajeError=' . $MensajeError);
        }
        exit();
    }

    public function buscar_barrio()
    {

        $consultaBusqueda = $_REQUEST['valorBusqueda'];
        $id = $_REQUEST['id'];


        //Filtro anti-XSS
        $caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
        $caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
        $consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

        //Variable vacía (para evitar los E_NOTICE)
        $mensaje = "";

        if (isset($consultaBusqueda)) {

            $con = new Conexion();
            $con->OpenConexion();

            $list = Barrio::get_list_like_name(coneccion: $con, name: $consultaBusqueda);
            $cant = count($list);

            if (!$cant) {
                $mensaje = "<p>No hay ningún registro con ese dato</p>";
            } else {

                $mensaje .= '<table class="table">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">ID</th>			      
                        <th scope="col">Barrio</th>			      			     
                        <th scope="col">Accion</th>	
                        </tr>
                    </thead>
                    <tbody>';

                foreach($list as $val => $barrio) {
									
                    $mensaje .= '
                        <tr>
                        <th scope="row">' . $barrio->get_id_barrio() . '</th>
                        <th scope="row">' . $barrio->get_barrio() . '</th>			      			      
                        <td>
                            <button type = "button" class = "btn btn-outline-success" 
                                    onClick="seleccionBarrio_' . $id  . '(\''. $barrio->get_id_barrio() .'\','. $barrio->get_barrio() .')" 
                                    data-dismiss="modal">
                                seleccionar
                            </button>
                        </td>
                        </tr>';

                };

                $mensaje .= '</tbody>
                    </table>';

            };
            $con->CloseConexion();

        };

        echo $mensaje;
    }
}