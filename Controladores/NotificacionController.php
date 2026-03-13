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
}