<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Categoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Movimiento.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/MovimientoMotivo.php");
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/DtoMovimiento.php';


class MovimientoController 
{

    public function listado_movimiento($mensaje = null, $id = null)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            header("Content-Type: text/html;charset=utf-8");

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";
            $Element = new Elements();
            $DTGeneral = new CtrGeneral();

            include("view_movimientos.php");
        }
        exit();
    }

    public function buscar_movimientos()
    {
        $Filtro = $_REQUEST["Search"];
        $ID_Filtro = $_REQUEST["ID_Filtro"];
        header("Location: /movimientos?Filtro=" . $Filtro . "&ID_Filtro=" . $ID_Filtro);
    }

    public function datos_movimiento($id_movimiento)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            header("Content-Type: text/html;charset=utf-8");

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();
            include("view_vermovimientos.php");
        }
        exit();
    }

    public function mod_movimiento($id_movimiento)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            header("Content-Type: text/html;charset=utf-8");

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $Element = new Elements();
            include("view_modmovimientos.php");
        }
        exit();
    }

    public function del_movimiento_control($id_movimiento)
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Movimiento = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja un Movimiento. Datos: Movimiento: $ID_Movimiento";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Consulta = "update movimiento set estado = 0 where id_movimiento = $ID_Movimiento";
            if (!$Ret = mysqli_query($Con->Conexion,$Consulta)) {
                throw new Exception("Problemas en la consulta", 0);		
            }	
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if (!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)) {
                throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 1);
            }	

            $ConsultarDatos = "select * 
                            from persona p inner join movimiento q on (p.id_persona = q.id_persona)
                            where id_movimiento = $ID_Movimiento";
            $ErrorDatos = "No se pudieron consultar los datos :";
            if (!$RetDatos = mysqli_query($Con->Conexion,$ConsultarDatos)) {
                throw new Exception($ErrorDatos.$ConsultarDatos, 1);
            }

            $TomarDatos = mysqli_fetch_assoc($RetDatos);
            $Apellido = $TomarDatos["apellido"];
            $Nombre = $TomarDatos["nombre"];
            $Fecha =  $TomarDatos["fecha"];
            $DNI = $TomarDatos["documento"];

            // CREANDO NOTIFICACION PARA EL USUARIO
            $DetalleNot = 'Se elimino el movimiento vinculado a : '.$Apellido. ', '.$Nombre. (($Fecha == null)?'':' fecha: '. $Fecha);
            $Expira = date("Y-m-d", strtotime($Fecha . " + 15 days"));

            $ConsultaNot = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('$DetalleNot','$Fecha', '$Expira',1)";
            if (!$RetNot = mysqli_query($Con->Conexion,$ConsultaNot)) {
                throw new Exception("Error al intentar registrar Notificacion. Consulta: ".$ConsultaNot, 3);
            }

            $Con->CloseConexion();
            $Mensaje = "El movimiento se elimino Correctamente";
            header('Location: /movimientos?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function mod_movimiento_control()
    {
        $Arr_ID_Responsable = $_REQUEST["ID_Responsable"];

        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Movimiento = $_REQUEST["ID"];
        $Fecha = implode("-", array_reverse(explode("/",$_REQUEST["Fecha"])));
        $Fecha_Creacion = null;
        $ID_Persona = $_REQUEST["ID_Persona"];
        $ID_Motivo_1 = $_REQUEST["ID_Motivo_1"];
        $ID_Motivo_2 = (!empty($_REQUEST["ID_Motivo_2"]) ? $_REQUEST["ID_Motivo_2"] : null);
        $ID_Motivo_3 = (!empty($_REQUEST["ID_Motivo_3"]) ? $_REQUEST["ID_Motivo_3"] : null);
        $ID_Motivo_4 = (!empty($_REQUEST["ID_Motivo_4"]) ? $_REQUEST["ID_Motivo_4"] : null);
        $ID_Motivo_5 = (!empty($_REQUEST["ID_Motivo_5"]) ? $_REQUEST["ID_Motivo_5"] : null);
        $lista_motivos = array($ID_Motivo_1);
        $Observaciones = $_REQUEST["Observaciones"];
        $ID_Responsable = $Arr_ID_Responsable[0];
        $ID_Centro = $_REQUEST["ID_Centro"];
        $ID_OtraInstitucion = $_REQUEST["ID_OtraInstitucion"];
        $Estado = 1;
        $ID_Responsable_2 = (isset($Arr_ID_Responsable[1])) ? $Arr_ID_Responsable[1] : 'null';
        $ID_Responsable_3 = (isset($Arr_ID_Responsable[2])) ? $Arr_ID_Responsable[2] : 'null';
        $ID_Responsable_4 = (isset($Arr_ID_Responsable[3])) ? $Arr_ID_Responsable[3] : 'null';

        if($ID_Motivo_2 == null){
            $ID_Motivo_2 = 1; 
        } else {
            $lista_motivos[] = $ID_Motivo_2;
        }
        if($ID_Motivo_3 == null){
            $ID_Motivo_3 = 1;
        } else {
            $lista_motivos[] = $ID_Motivo_3;
        }
        if($ID_Motivo_4 == null){
            $ID_Motivo_4 = 1;
        } else {
            $lista_motivos[] = $ID_Motivo_4;
        }
        if($ID_Motivo_5 == null){
            $ID_Motivo_5 = 1;
        } else {
            $lista_motivos[] = $ID_Motivo_5;
        }

        if(empty($ID_Responsable[0])){
            $ID_Responsable = 'null';
        }

        if(empty($ID_Centro)){
            $ID_Centro = 'null';
        }

        $con = new Conexion();
        $con->OpenConexion();

        if (Persona::is_exist($con, $ID_Persona)) {
            $persona = new Persona($ID_Persona);
        }

        if (Movimiento::is_exist($con, $ID_Movimiento)) {
            $movimiento_sin_modificar = new Movimiento(
                                        coneccion_base: $con, 
                                        xID_Movimiento: $ID_Movimiento
            );
            $fecha_previa = $movimiento_sin_modificar->getFecha();
            $id_persona_previa = $movimiento_sin_modificar->getID_Persona();
            $persona = new Persona(ID_Persona: $id_persona_previa);

            $movimiento = new Movimiento(
                coneccion_base: $con, 
                xFecha: $Fecha,
                Fecha_Creacion: $Fecha_Creacion,
                xID_Persona: $ID_Persona,
                xID_Motivo_1: $ID_Motivo_1,
                xID_Motivo_2: $ID_Motivo_2,
                xID_Motivo_3: $ID_Motivo_3,
                xID_Motivo_4: $ID_Motivo_4,
                xID_Motivo_5: $ID_Motivo_5,
                xObservaciones: $Observaciones,
                xID_Responsable: $ID_Responsable,
                xID_Responsable_2: $ID_Responsable_2,
                xID_Responsable_3: $ID_Responsable_3,
                xID_Responsable_4: $ID_Responsable_4,
                xID_Centro: $ID_Centro,
                xID_OtraInstitucion: $ID_OtraInstitucion,
                xEstado: $Estado
            );
            $movimiento->setID_Movimiento($ID_Movimiento);
            $movimiento->udpate();

            $consulta = "SELECT * 
                        FROM movimiento_motivo
                        WHERE id_movimiento = $ID_Movimiento
                        AND estado = 1";
            $rs = mysqli_query($con->Conexion,$consulta) or die("Problemas al consultar las acciones.");

            while ($ret = mysqli_fetch_assoc($rs)) {
                if (!in_array($ret["id_motivo"], $lista_motivos)) {
                    $movimiento_motivo = new MovimientoMotivo(
                                                            connection: $con,
                                                            id_movimiento: $ID_Movimiento,
                                                            id_motivo: $ret
                    );
                    $movimiento_motivo->delete();
                }
            }

            foreach ($lista_motivos as $value) {
                $motivo = MovimientoMotivo::exist_movimiento_motivo(
                    connection: $con,
                    movimiento: $ID_Movimiento,
                    motivo: $value
                );
                if (!$motivo) {
                    $movimiento_motivo = new MovimientoMotivo(
                                                            connection: $con,
                                                            id_movimiento: $ID_Movimiento,
                                                            id_motivo: $value
                    );
                    $movimiento_motivo->save();
                }
            }
            
            $fecha_accion = date("Y-m-d");
            $ID_TipoAccion = 2;
            $detalles = "El usuario con ID: $ID_Usuario ha modificado un Movimiento. Datos: id_movimiento: " . $movimiento->getID_Movimiento();
            $accion = new Accion(
                xaccountid: $ID_Usuario,
                xFecha: $Fecha,
                xDetalles: $detalles,
                xID_TipoAccion: $ID_TipoAccion
            );
            $accion->save();

        }

        $apellido = $persona->getApellido();
        $nombre = $persona->getNombre();
        $dni = $persona->getDNI();

        // CREANDO NOTIFICACION PARA EL USUARIO
        $detalle_not = 'Se modifico el movimiento vinculado a : '. $apellido . ', '. $nombre . ' fecha: '. $fecha_previa;
        $expira = date("Y-m-d", strtotime($fecha_accion . " + 15 days"));

        $consulta_not = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('$detalle_not','$Fecha', '$expira',1)";
        if(!$RetNot = mysqli_query($con->Conexion,$consulta_not)){
            throw new Exception("Error al intentar registrar Notificacion. Consulta: " . $consulta_not, 3);
        }

        $con->CloseConexion();

        $Mensaje = "El Movimiento se modifico correctamente";
        header('Location: /movimientos?Mensaje=' . $Mensaje);
        exit();
    }
}