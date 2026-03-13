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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/OtraInstitucion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Movimiento.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");


class OtraInstitucionController 
{

    public function listado_otras_instituciones($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $mensaje_success = $mensaje;
            $mensaje_error = "";            
            $Element = new Elements();
            $DTGeneral = new CtrGeneral();
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            include("./Views/view_otrasinstituciones.php");
        }
        exit();
    }

    public function mod_otra_institucion($id = null, $mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $mensaje_success = $mensaje;
            $mensaje_error = "";            

            $Element = new Elements();
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            include("./Views/view_modotrasinstituciones.php");
        }
        exit();
    }

    public function del_otra_institucion_control($id_otra_institucion)
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $id = $id_otra_institucion;

        $fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $detalles = "El usuario con ID: $ID_Usuario ha dado de baja una otra_institucion. Datos: otra_institucion: $id";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $otra_institucion = new OtraInstitucion(xConeccion: $Con, xID_OtraInstitucion: $id);
            $otra_institucion->delete();

            $accion = new Accion(
                                 xaccountid: $ID_Usuario,
                                 xFecha: $fecha, 
                                 xDetalles: $detalles);
            $accion->save();
            $Con->CloseConexion();
            $Mensaje = "La otra_institucion fue eliminada Correctamente";
            header('Location: /home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function mod_otra_institucion_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];
        $id = $_REQUEST["ID"];
        $nombre = strtoupper($_REQUEST["Nombre"]);
        $mail = $_REQUEST["Mail"];
        $Telefono  = $_REQUEST["Telefono"];

        $fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $otra_institucion = new OtraInstitucion(xConeccion: $Con, xID_OtraInstitucion: $id);
            if ($nombre) $otra_institucion->setNombre($nombre);
            if ($mail) $otra_institucion->setMail($mail);
            if ($Telefono) $otra_institucion->setTelefono($Telefono);
            if ($nombre || $mail || $Telefono) $otra_institucion->update();

            $detalles = "El usuario con ID: $ID_Usuario ha modificado la otra_institucion: $id. Datos: Dato Anterior: $nombre , Dato Nuevo: $nombre - Dato Anterior: $mail , Dato Nuevo: $mail - Dato Anterior: $Telefono , Dato Nuevo: $Telefono";
            $accion = new Accion(
                                 xaccountid: $ID_Usuario,
                                 xFecha: $fecha, 
                                 xDetalles: $detalles);
            $accion->save();

            $mensaje = "La otra_institucion se modifico Correctamente";
            $Con->CloseConexion();
            header("Content-Type: text/html;charset=utf-8");
            header('Location: /otrainstitucion/editar?ID=' . $id . '&Mensaje=' . $mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function unif_otra_institucion($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_unifotrasinstituciones.php");
        }
        exit();
    }

    public function unif_otra_institucion_control()
    {
        $ID_Institucion_1 = $_REQUEST["ID_Institucion_1"];
        $ID_Institucion_2 = $_REQUEST["ID_Institucion_2"];

        if ($ID_Institucion_1 > 0 && $ID_Institucion_2 > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $ConsultarInstituciones = "select * from movimiento where id_otrainstitucion = $ID_Institucion_2 and estado = 1";
            $MensajeErrorConsultarInstituciones = "No se pudieron consultar los casos de igualdad en la Institución 1";

            $EjecutarConsultarInstituciones = mysqli_query($Con->Conexion, $ConsultarInstituciones) or die($MensajeErrorConsultarInstituciones);
            while($RetInstituciones = mysqli_fetch_assoc($EjecutarConsultarInstituciones)){
                $ID_Movimiento = $RetInstituciones["id_movimiento"];
                $CambiarInstituciones = "update movimiento set id_otrainstitucion = $ID_Institucion_1 where id_movimiento = $ID_Movimiento";
                $MensajeErrorCambiarInstituciones = "No se pudieron cambiar las instituciones 1";
                mysqli_query($Con->Conexion, $CambiarInstituciones) or die($MensajeErrorCambiarInstituciones);
            }

            $ConsultaBajaInstitucion = "update otras_instituciones set Estado = 0 where ID_OtraInstitucion = $ID_Institucion_2";
            $MensajeErrorBajaInstitucion = "No se pudo dar de baja la Institución";

            mysqli_query($Con->Conexion,$ConsultaBajaInstitucion) or die($MensajeErrorBajaInstitucion);

            $Con->CloseConexion();
            $Mensaje = "Los datos se unificaron Correctamente";
            header('Location: /otrainstitucion/unificar?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Barrio y Segundo Barrio";
            header('Location: /otrainstitucion/unificar?MensajeError=' . $MensajeError);
        }
        exit();
    }

    public function new_otra_institucion($mensaje = null, $mensaje_error = null)
    {
        if (!isset($_SESSION["Usuario"])) {

            include("./Views/Error_Session.php");
        } else {
            header("Content-Type: text/html;charset=utf-8");
            $mensaje_success = $mensaje;

            $Element = new Elements();
            $DTGeneral = new CtrGeneral();
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            include("./Views/view_newotrasinstituciones.php");
        }
        exit();
        
    }
    public function new_otra_institucion_control()
    {
        $id_usuario = $_SESSION['Usuario'];
        $name = (!empty($_REQUEST['Nombre'])) ? ucfirst($_REQUEST['Nombre']) : null;
        $telefono = (!empty($_REQUEST['Telefono'])) ? $_REQUEST['Telefono'] : null;
        $mail = (!empty($_REQUEST['Mail'])) ? $_REQUEST['Mail'] : null;
        $estado = 1;
        $fecha = date('Y-m-d');

        try {
            $con = new Conexion();
            $con->OpenConexion();
            if (OtraInstitucion::get_id_by_name(coneccion: $con, name: $name)) {
                $con->CloseConexion();
                $mensaje = "Ya existe una Institución con ese Nombre";
                header('Location: /otrainstitucion/nueva?MensajeError=' . $mensaje);
            } else {
                $otra_institucion = new OtraInstitucion(
                                                        xConeccion: $con,
                                                        xNombre: $name,
                                                        xTelefono: $telefono,
                                                        xMail: $mail,
                                                        xEstado: $estado);
                $otra_institucion->save();
                $detalle = "el usuario $id_usuario a registrado una institucion. datos : name - $name  telefono - $telefono  mail : $mail .";
                $accion = new Accion(
                                     xDetalles: $detalle,
                                     xFecha: $fecha,
                                     xaccountid: $id_usuario);
                $accion->save();
                $mensaje = "La Institución se registro Correctamente";
                header('Location: ../otrainstitucion/nueva?Mensaje=' . $mensaje);
            }
        } catch (Exception $e) {
            $con->CloseConexion();
            echo $e->getCode();
        }
    }


}