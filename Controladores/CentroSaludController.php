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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");


class CentroSaludController 
{

    public function listado_centro_salud($mensaje = null)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("view_centros.php");
        }
        exit();
    }

    public function mod_centro_salud($id_centro)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("view_modcentros.php");
        }
        exit();
    }

    public function del_centro_control($id_centro)
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Centro = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja un Centro de Salud. Datos: Centro: $ID_Centro";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Consulta = "update centros_salud set estado = 0 where id_centro = $ID_Centro";
            if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 0);		
            }
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 1);
            }	
            $Con->CloseConexion();
            $Mensaje = "El centro de salud fue eliminado Correctamente";
            header('Location: ../view_centros.php?Mensaje='.$Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
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

        $Con = new Conexion();
        $Con->OpenConexion();

        try {
            $ConsultarRegistrosIguales = "select * from centros_salud where centro_salud = '$Centro_Salud' and id_centro != $ID_Centro and estado = 1";
            if (!$Ret = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)) {
                throw new Exception("Error al consultar registros. Consulta: " . $ConsultarRegistrosIguales, 0);		
            }
            $Resultado = mysqli_num_rows($Ret);
            if ($Resultado > 0) {
                $Con->CloseConexion();
                $Mensaje = "Ya existe un Centro de Salud con ese Nombre";
                header('Location: /centrosalud/editar?ID=' . $ID_Centro . '&MensajeError=' . $Mensaje);
            } else {
                $ConsultarDatosViejos = "select * from centros_salud where id_centro = $ID_Centro and estado = 1";
                $ErrorDatosViejos = "No se pudieron consultar los datos";
                if (!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)) {
                    throw new Exception("Error al intentar registrar. Consulta: " . $ConsultarDatosViejos, 1);
                }		
                $TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
                $CentroViejo = $TomarDatosViejos["centro_salud"];
                

                $Consulta = "update centros_salud set centro_salud = '$Centro_Salud' where id_centro = $ID_Centro and estado = 1";
                if (!$Ret = mysqli_query($Con->Conexion,$Consulta)) {
                    throw new Exception("Error al intentar registrar. Consulta: " . $ConsultarRegistrosIguales, 2);
                }

                $Detalles = "El usuario con ID: $ID_Usuario ha modificado un Centro de Salud. Datos: Dato Anterior: $CentroViejo , Dato Nuevo: $Centro_Salud";
                $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
                if (!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)) {
                    throw new Exception("Error al intentar registrar Accion. Consulta: " . $ConsultaAccion, 3);
                }
                $Con->CloseConexion();
                $Mensaje = "El Centro de Salud se modificó Correctamente";
                header('Location: /centrosalud/editar?ID=' . $ID_Centro . '&Mensaje=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function sol_unif_control()
    {
        $Fecha = Date("Y-m-d");
        $ID_Registro_1 = $_REQUEST["ID_Centro_1"];
        $ID_Registro_2 = $_REQUEST["ID_Centro_2"];
        $ID_Usuario = $_SESSION["Usuario"];
        $Estado = 1;
        $TipoUnif = 3;

        if ($ID_Registro_1 > 0 && $ID_Registro_2 > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Solicitud = new Solicitud_Unificacion(0,$Fecha,$ID_Registro_1,$ID_Registro_2,$ID_Usuario,$Estado,$TipoUnif);
            $Insert_Solicitud = "insert into solicitudes_unificacion(Fecha,ID_Registro_1,ID_Registro_2,ID_Usuario,Estado,ID_TipoUnif) values('{$Solicitud->getFecha()}',{$Solicitud->getID_Registro_1()},{$Solicitud->getID_Registro_2()},{$Solicitud->getID_Usuario()},{$Solicitud->getEstado()},{$Solicitud->getTipoUnif()})";
            $MensajeError = "No se pudo enviar la solicitud";

            mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);

            $Con->CloseConexion();
            $Mensaje = "La solicitud de unificación se envió a los administradores para ser confirmada.";
            header('Location: /centrosalud/unificar?Mensaje='.$Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Centro y Segundo Centro";
            header('Location: /centrosalud/unificar?MensajeError='.$MensajeError);
        }
        exit();
    }

    public function unif_centro_salud($mensaje = null)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("view_unifcentros.php");
        }
        exit();
    }

    public function unif_centro_salud_control()
    {
        $ID_Solicitud = $_REQUEST["ID_Solicitud"];
        $ID_Centro_1 = $_REQUEST["ID_Centro_1"];
        $ID_Centro_2 = $_REQUEST["ID_Centro_2"];

        if ($ID_Centro_1 > 0 && $ID_Centro_2 > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $ConsultarCentros = "select * from movimiento where id_centro = $ID_Centro_2 and estado = 1";
            $MensajeErrorConsultarCentros = "No se pudieron consultar los casos de igualdad en el Centro 1";

            $EjecutarConsultarCentros = mysqli_query($Con->Conexion, $ConsultarCentros) or die($MensajeErrorConsultarCentros);
            while ($RetCentros = mysqli_fetch_assoc($EjecutarConsultarCentros)) {
                $ID_MovimientoCentro = $RetCentros["id_movimiento"];
                $CambiarCentros = "update movimiento set id_centro = $ID_Centro_1 where id_movimiento = $ID_MovimientoCentro";
                $MensajeErrorCambiarCentros = "No se pudieron cambiar los centros";
                mysqli_query($Con->Conexion, $CambiarCentros) or die($MensajeErrorCambiarCentros);
            }

            $ConsultaBajaCentro = "update centros_salud set estado = 0 where id_centro = $ID_Centro_2";
            $MensajeErrorBajaCentro = "No se pudo dar de baja el Centro de Salud";

            mysqli_query($Con->Conexion,$ConsultaBajaCentro) or die($MensajeErrorBajaCentro);
            
            $ConsultaSolicitud = "update solicitudes_unificacion set Estado = 0 where ID_Solicitud_Unificacion = $ID_Solicitud";
            if (!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)) {
                throw new Exception("Problemas en la consulta. Consulta: " . $ConsultaSolicitud, 3);			
            }

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