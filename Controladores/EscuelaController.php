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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Escuela.php");



class EscuelaController 
{

    public function listado_escuelas($mensaje = null)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_escuelas.php");
        }
        exit();
    }

    public function mod_escuela($id_escuela)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_modescuelas.php");
        }
        exit();
    }

    public function datos_categoria($id_escuela)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_verescuelas.php");
        }
        exit();
    }

    public function del_escuela_control($id_escuela)
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Escuela = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Escuela. Datos: Escuela: $ID_Escuela";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Consulta = "update escuelas set Estado = 0 where ID_Escuela = $ID_Escuela";
            if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 0);		
            }
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 1);
            }	
            $Con->CloseConexion();
            $Mensaje = "La Escuela fue eliminada Correctamente";
            header('Location: /escuelas?Mensaje='.$Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function mod_escuela_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Escuela = $_REQUEST["ID"];
        $Codigo = $_REQUEST["Codigo"];
        $Escuela = ucfirst($_REQUEST["Escuela"]);
        $CUE = $_REQUEST["CUE"];
        $Localidad = ucwords($_REQUEST["Localidad"]);
        $Departamento = ucwords($_REQUEST["Departamento"]);
        $Directora = ucwords($_REQUEST["Directora"]);
        $Telefono = $_REQUEST["Telefono"];
        $Mail = ucfirst($_REQUEST["Mail"]);
        $ID_Nivel = $_REQUEST["ID_Nivel"];
        $Estado = 1;

        $Con = new Conexion();
        $Con->OpenConexion();
        $Escuela_Nueva = new Escuela($Con, $ID_Escuela,$Codigo,$Escuela,$CUE,$Localidad,$Departamento,$Directora,$Telefono,$Mail,$ID_Nivel,$Estado);

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        try {
            $ConsultarRegistrosIguales = "select * from escuelas where Escuela = '$Escuela' and ID_Escuela != $ID_Escuela and estado = 1";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
                throw new Exception("Error al consultar registros. Consulta: ".$ConsultarRegistrosIguales, 0);		
            }
            $Resultado = mysqli_num_rows($Ret);
            if ($Resultado > 0) {
                $Con->CloseConexion();
                $Mensaje = "Ya existe una Escuela con ese Nombre";
                header('Location: /escuelas?ID='.$ID_Escuela.'&MensajeError='.$Mensaje);
            } else {
                $ConsultarDatosViejos = "select * from escuelas where ID_Escuela = $ID_Escuela and Estado = 1";
                $ErrorDatosViejos = "No se pudieron consultar los datos";
                if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
                    throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
                }		
                $TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
                $ID_Escuela = $TomarDatosViejos["ID_Escuela"];
                $Codigo = $TomarDatosViejos["Codigo"];
                $Escuela = $TomarDatosViejos["Escuela"];
                $CUE = $TomarDatosViejos["CUE"];
                $Localidad = $TomarDatosViejos["Localidad"];
                $Departamento = $TomarDatosViejos["Departamento"];
                $Directora = $TomarDatosViejos["Directora"];
                $Telefono = $TomarDatosViejos["Telefono"];
                $Mail = $TomarDatosViejos["Mail"];
                $ID_Nivel = $TomarDatosViejos["ID_Nivel"];
                $Estado = $TomarDatosViejos["Estado"];
                
                $Escuela_Vieja = new Escuela($ID_Escuela,$Codigo,$Escuela,$CUE,$Localidad,$Departamento,$Directora,$Telefono,$Mail,$ID_Nivel,$Estado);
                

                $Consulta = "update escuelas set Codigo = '{$Escuela_Nueva->getCodigo()}', Escuela = '{$Escuela_Nueva->getEscuela()}', CUE = '{$Escuela_Nueva->getCUE()}', Localidad = '{$Escuela_Nueva->getLocalidad()}', Departamento = '{$Escuela_Nueva->getDepartamento()}', Directora = '{$Escuela_Nueva->getDirectora()}', Telefono = '{$Escuela_Nueva->getTelefono()}', Mail = '{$Escuela_Nueva->getMail()}', ID_Nivel = {$Escuela_Nueva->getID_Nivel()} where ID_Escuela = {$Escuela_Nueva->getID_Escuela()} and Estado = 1";
                if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                    throw new Exception("Error al intentar registrar. Consulta: " . $ConsultarRegistrosIguales, 2);
                }

                $Detalles = "El usuario con ID: $ID_Usuario ha modificado una Escuela. Datos: Dato Anterior: {$Escuela_Vieja->getCodigo()} , Dato Nuevo: {$Escuela_Nueva->getCodigo()} - Dato Anterior: {$Escuela_Vieja->getEscuela()} , Dato Nuevo: {$Escuela_Nueva->getEscuela()} - Dato Anterior: {$Escuela_Vieja->getCUE()} , Dato Nuevo: {$Escuela_Nueva->getCUE()} - Dato Anterior: {$Escuela_Vieja->getLocalidad()} , Dato Nuevo: {$Escuela_Nueva->getLocalidad()} - Dato Anterior: {$Escuela_Vieja->getDepartamento()} , Dato Nuevo: {$Escuela_Nueva->getDepartamento()} - Dato Anterior: {$Escuela_Vieja->getDirectora()} , Dato Nuevo: {$Escuela_Nueva->getDirectora()} - Dato Anterior: {$Escuela_Vieja->getTelefono()} , Dato Nuevo: {$Escuela_Nueva->getTelefono()} - Dato Anterior: {$Escuela_Vieja->getMail()} , Dato Nuevo: {$Escuela_Nueva->getMail()} - Dato Anterior: {$Escuela_Vieja->getID_Nivel()} , Dato Nuevo: {$Escuela_Nueva->getID_Nivel()}.";
                $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
                if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                    throw new Exception("Error al intentar registrar Accion. Consulta: " . $ConsultaAccion, 3);
                }
                $Con->CloseConexion();
                $Mensaje = "La Escuela se modificó Correctamente";
                header('Location: /escuela/editar?ID=' . $ID_Escuela . '&Mensaje=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        exit();
    }

    public function unif_escuelas($mensaje = null)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_unifescuelas.php");
        }
        exit();
    }

    public function unif_escuela_control()
    {
        $ID_Solicitud = $_REQUEST["ID_Solicitud"];
        $ID_Escuela_1 = $_REQUEST["ID_Escuela_1"];
        $ID_Escuela_2 = $_REQUEST["ID_Escuela_2"];

        if ($ID_Escuela_1 > 0 && $ID_Escuela_2 > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $ConsultarEscuelas = "select * from persona where ID_Escuela = $ID_Escuela_2 and estado = 1";
            $MensajeErrorConsultarEscuelas = "No se pudieron consultar los casos de igualdad en la Escuela 1";

            $EjecutarConsultarEscuelas = mysqli_query($Con->Conexion, $ConsultarEscuelas) or die($MensajeErrorConsultarEscuelas);
            while ($RetEscuelas = mysqli_fetch_assoc($EjecutarConsultarEscuelas)) {
                $ID_PersonaEscuela = $RetEscuelas["id_persona"];
                $CambiarEscuelas = "update persona set ID_Escuela = $ID_Escuela_1 where id_persona = $ID_PersonaEscuela";
                $MensajeErrorCambiarEscuelas = "No se pudieron cambiar las Escuelas";
                mysqli_query($Con->Conexion, $CambiarEscuelas) or die($MensajeErrorCambiarEscuelas);
            }

            $ConsultaBajaEscuela = "update escuelas set estado = 0 where ID_Escuela = $ID_Escuela_2";
            $MensajeErrorBajaEscuela = "No se pudo dar de baja la Escuela";

            mysqli_query($Con->Conexion,$ConsultaBajaEscuela) or die($MensajeErrorBajaEscuela);

            $ConsultaSolicitud = "update solicitudes_unificacion set Estado = 0 where ID_Solicitud_Unificacion = $ID_Solicitud";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
                throw new Exception("Problemas en la consulta. Consulta: " . $ConsultaSolicitud, 3);			
            }

            $Con->CloseConexion();
            $Mensaje = "Los datos se unificaron Correctamente";
            header('Location: /home?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Escuela y Segunda Escuela";
            header('Location: /home?MensajeError=' . $MensajeError);
        }
        
    }

    public function sol_unif_escuela_control()
    {
        $Fecha = Date("Y-m-d");
        $ID_Registro_1 = $_REQUEST["ID_Escuela_1"];
        $ID_Registro_2 = $_REQUEST["ID_Escuela_2"];
        $ID_Usuario = $_SESSION["Usuario"];
        $Estado = 1;
        $TipoUnif = 4;

        if ($ID_Registro_1 > 0 && $ID_Registro_2 > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Solicitud = new Solicitud_Unificacion(0,$Fecha,$ID_Registro_1,$ID_Registro_2,$ID_Usuario,$Estado,$TipoUnif);
            $Insert_Solicitud = "insert into solicitudes_unificacion(Fecha,ID_Registro_1,ID_Registro_2,ID_Usuario,Estado,ID_TipoUnif) values('{$Solicitud->getFecha()}',{$Solicitud->getID_Registro_1()},{$Solicitud->getID_Registro_2()},{$Solicitud->getID_Usuario()},{$Solicitud->getEstado()},{$Solicitud->getTipoUnif()})";
            $MensajeError = "No se pudo enviar la solicitud";

            mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);

            $Con->CloseConexion();
            $Mensaje = "La solicitud de unificación se envió a los administradores para ser confirmada.";
            header('Location: /escuelas?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Centro y Segundo Centro";
            header('Location: /escuelas?MensajeError=' . $MensajeError);
        }
    }
}