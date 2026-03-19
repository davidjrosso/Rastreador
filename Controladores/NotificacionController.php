<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Calle.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");


class NotificacionController 
{

    public function listado_notificacion($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {

            $id_usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $id_usuario);
            $tipo_usuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();
            $dt_general = new CtrGeneral();

            $valor = null;
            $id_filtro = null;

            if (isset($_REQUEST["Filtro"])) $valor = $_REQUEST["Filtro"];
            if (isset($_REQUEST["ID_Filtro"])) $id_filtro = $_REQUEST["ID_Filtro"];

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_notificacion.php");
        }
        exit();
    }

    public function listado_notificacion_control($id_filtro = null, $filtro = null)
    {
        $ID_Filtro = $_REQUEST["ID_Filtro"];
        header("Location: ../notificacion?ID_Filtro=" . $id_filtro);
    }

    public function delete_notificacion_control()
    {
        $ID = $_REQUEST["ID"];

        $ID_Usuario = $_SESSION["Usuario"];
        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Notificacion. Datos: Notificacion: $ID";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Consulta = "update notificaciones set estado = 0 where ID_Notificacion = $ID";
            if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 0);		
            }
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 1);
            }
            $Con->CloseConexion();
            $Mensaje = "La notificación fue eliminada correctamente";
            header('Location: ../view_inicio.php?Mensaje='.$Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        
    }
}