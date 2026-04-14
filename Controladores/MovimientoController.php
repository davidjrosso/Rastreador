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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Categoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Movimiento.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Motivo.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/MovimientoMotivo.php");
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/DtoMovimiento.php';


class MovimientoController 
{

    public function listado_movimiento($mensaje = null, $id = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";
            $Element = new Elements();
            $DTGeneral = new CtrGeneral();

            include("./Views/view_movimientos.php");
        }
        exit();
    }

    public function crear_movimiento()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            $Element = new Elements();
            $DTGeneral = new CtrGeneral();

            include("./Views/view_newmovimientos.php");
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
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();
            include("./Views/view_vermovimientos.php");
        }
        exit();
    }

    public function mod_movimiento($id_movimiento)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $Element = new Elements();

            if (isset($_REQUEST["ID"])) {
              $ID_Movimiento = $_REQUEST["ID"];

              $Con = new Conexion();
              $Con->OpenConexion();

              $ConsultarDatos = "select M.id_movimiento, M.fecha, M.id_centro, P.id_persona, P.apellido, 
                                        P.nombre, M.observaciones, group_concat(distinct R.id_resp separator '|')
                                        R.responsable, C.centro_salud, I.ID_OtraInstitucion, I.Nombre, group_concat(distinct MT.id_motivo separator '|')
                                from movimientos M 
                                      INNER JOIN movimientos_motivos MEMT ON (M.id_movimiento = MEMT.id_movimiento)
                                      INNER JOIN motivo MT ON (MEMT.id_motivo = MT.id_motivo)
                                      INNER JOIN personas P ON (M.id_persona = P.id_persona)
                                      INNER JOIN movimientos_responsables RN ON (M.id_movimiento = RN.id_movimiento)
                                      INNER JOIN responsables R ON (RN.id_responsable = R.id_responsable)
                                      LEFT JOIN centros_salud C ON (M.id_centro = C.id_centro)
                                      LEFT JOIN otras_instituciones I ON (M.id_otrainstitucion = I.ID_OtraInstitucion )
                                 where M.id_movimiento = $ID_Movimiento
                                 group by M.id_movimiento, M.fecha, M.id_centro, P.id_persona, P.apellido, 
                                        P.nombre, M.observaciones, C.centro_salud, I.ID_OtraInstitucion";

              $MensajeErrorDatos = "No se pudo consultar los Datos del Movimiento";

              $EjecutarConsultarDatos = mysqli_query($Con->Conexion,$ConsultarDatos) or die($MensajeErrorDatos);

              $Ret = mysqli_fetch_assoc($EjecutarConsultarDatos);

              $ID_Movimiento = $Ret["id_movimiento"];
              $id_motivo = $Ret["id_motivo"];
              $Fecha = implode("/", array_reverse(explode("-",$Ret["fecha"])));
              $Apellido = $Ret["apellido"];
              $Nombre = $Ret["nombre"];
              $Observaciones = $Ret["observaciones"];
              $Responsable = $Ret["responsable"];
              $ID_Persona = $Ret["id_persona"];
              $ID_Responsable = $Ret["id_resp"];
              $ID_Responsable_2 = $Ret["id_resp_2"];
              $ID_Responsable_3 = $Ret["id_resp_3"];
              $ID_Responsable_4 = $Ret["id_resp_4"];
              $ID_Centro = $Ret["id_centro"];
              $Centro_Salud = (!empty($Ret["centro_salud"])) ? $Ret["centro_salud"] : null;
              $ID_OtraInstitucion = $Ret["ID_OtraInstitucion"];
              $OtraInstitucion = (!empty($Ret["Nombre"])) ? $Ret["Nombre"] : null;

              $DtoMovimiento = new DtoMovimiento(
                                                xID_Movimiento: $ID_Movimiento,
                                                xFecha: $Fecha,
                                                xApellido: $Apellido,
                                                xNombre: $Nombre,
                                                xMotivo_1: $id_motivo,
                                                xObservaciones: $Observaciones,
                                                xResponsable: $Responsable,
                                                xCentroSalud: $Centro_Salud,
                                                xOtraInstitucion: $OtraInstitucion
              );

              $count_motivo = 2;
              while ($Ret = mysqli_fetch_assoc($EjecutarConsultarDatos)) {
                if ($count_motivo == 2) $DtoMovimiento->setMotivo_2($Ret["id_motivo"]);
                if ($count_motivo == 3) $DtoMovimiento->setMotivo_3($Ret["id_motivo"]);
                if ($count_motivo == 4) $DtoMovimiento->setMotivo_4($Ret["id_motivo"]);
                if ($count_motivo == 5) $DtoMovimiento->setMotivo_5($Ret["id_motivo"]);
                $count_motivo++;
              }

              $Con->CloseConexion();
            }

            include("./Views/view_modmovimientos.php");
        }
        exit();
    }

    public function new_movimiento_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        if (empty($_REQUEST["Fecha"])) {
            $Fecha =  date("Y-m-d");
        } else {
            $Fecha = implode("-", array_reverse(explode("/",$_REQUEST["Fecha"])));
        }

        $Arr_ID_Responsable = $_REQUEST["ID_Responsable"];

        $ID_Persona = $_REQUEST["ID_Persona"];
        $ID_Motivo[] = (!empty($_REQUEST["ID_Motivo_1"])) ? $_REQUEST["ID_Motivo_1"] : 1;
        $ID_Motivo[] = (!empty($_REQUEST["ID_Motivo_1"])) ? $_REQUEST["ID_Motivo_2"] : 1;
        $ID_Motivo[] = (!empty($_REQUEST["ID_Motivo_1"])) ? $_REQUEST["ID_Motivo_3"] : 1;
        $ID_Motivo[] = (!empty($_REQUEST["ID_Motivo_4"])) ? $_REQUEST["ID_Motivo_4"] : 1;
        $ID_Motivo[] = (!empty($_REQUEST["ID_Motivo_5"])) ? $_REQUEST["ID_Motivo_5"] : 1;
        $Observaciones = $_REQUEST["Observaciones"];
        $ID_Centro = (isset($_REQUEST["ID_Centro"])) ? $_REQUEST["ID_Centro"]:0;
        $ID_OtraInstitucion = (isset($_REQUEST["ID_OtraInstitucion"])) ? $_REQUEST["ID_OtraInstitucion"]:0;
        $Estado = 1;


        if(empty($ID_Centro)){
            $ID_Centro = 7;
        }else{
            $_SESSION["UltCentro"] = $ID_Centro;
        }

        if(empty($ID_OtraInstitucion)){
            $ID_OtraInstitucion = 1;
        }else{
            $_SESSION["UltOtraInstitucion"] = $ID_OtraInstitucion;
        }

        $Fecha_Accion = date("Y-m-d");
        $ID_TipoAccion = 1;

        try {
            $Con = new Conexion();
            $Con->OpenConexion();
            $movimiento = new Movimiento(
                            coneccion_base: $Con,
                                    xFecha: $Fecha,
                            Fecha_Creacion: $Fecha_Accion,
                            xID_Persona: $ID_Persona,
                            xObservaciones: $Observaciones,
                                xID_Centro: $ID_Centro,
                    xID_OtraInstitucion: $ID_OtraInstitucion,
                                xEstado: $Estado
            );
            $movimiento->save();
            $id_movimiento = $movimiento->getID_Movimiento();

            $mensaje_motivo = "";
            foreach($ID_Motivo as $id) {
                $motivo = MovimientoMotivo::exist_movimiento_motivo(
                    connection: $Con,
                    movimiento: $id_movimiento,
                    motivo: $id
                );
                $mensaje_motivo .= "- $id";
                if (!$motivo) {
                    $movimiento_motivo = new MovimientoMotivo(
                                                                connection: $Con,
                                                            id_movimiento: $id_movimiento,
                                                                id_motivo: $id,
                                                                    estado: 1
                    );
                    $movimiento_motivo->save();
                }
                 
            }

            foreach ($Arr_ID_Responsable as $id_res) {
                $res = MovimientoResponsable::exist_movimiento_responsable(
                    connection: $Con,
                    movimiento: $id_movimiento,
                    id_responsable: $id_res
                );
                if (!$res) {
                    $mov = new MovimientoResponsable(
                                                     connection: $Con,
                                                     id_movimiento: $id_movimiento,
                                                     id_responsable: $id_res
                                                     );
                    $mov->save();
                }

            }
            $detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Movimiento. Datos: Fecha: $Fecha_Accion - Persona: $ID_Persona - motivo $mensaje_motivo - Observaciones: $Observaciones - Responsable: " . $Arr_ID_Responsable[0] . " - Centro Salud: $ID_Centro - Otra Institución: $ID_OtraInstitucion";

            $accion = new Accion(
                xaccountid: $ID_Usuario,
                xFecha: $Fecha_Accion,
                xDetalles: $detalles,
                xID_TipoAccion: $ID_TipoAccion
            );
            $accion->save();

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        $Con->CloseConexion();

        $Mensaje = "El Movimiento se ha cargado correctamente";

        header('Location: /movimiento/nuevo?Mensaje=' . $Mensaje);
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

            $mov = new Movimiento(coneccion_base: $Con, xID_Movimiento: $ID_Movimiento);
            $mov->delete();

            $accion = new Accion(
                xaccountid: $ID_Usuario,
                xFecha : $Fecha,
                xDetalles: $Detalles,
                xID_TipoAccion: $ID_TipoAccion	 
            );
            $accion->save();

            $ConsultarDatos = "select * 
                            from personas p inner join movimientos q on (p.id_persona = q.id_persona)
                            where id_movimiento = $ID_Movimiento";
            $ErrorDatos = "No se pudieron consultar los datos :";
            if (!$RetDatos = mysqli_query($Con->Conexion,$ConsultarDatos)) {
                throw new Exception($ErrorDatos . $ConsultarDatos, 1);
            }

            $TomarDatos = mysqli_fetch_assoc($RetDatos);
            $Apellido = $TomarDatos["apellido"];
            $Nombre = $TomarDatos["nombre"];
            $Fecha =  $TomarDatos["fecha"];
            $DNI = $TomarDatos["documento"];

            // CREANDO NOTIFICACION PARA EL USUARIO
            $detalle_not = 'Se elimino el movimiento vinculado a : ' . $Apellido . ', ' . $Nombre . (($Fecha == null)?'':' fecha: '. $Fecha);
            $expira = date("Y-m-d", strtotime($Fecha . " + 15 days"));

            $rev = new Notificacion(coneccion_base: $Con, detalle: $detalle_not , fecha: $expira);
            $rev->save();

            $Con->CloseConexion();
            $Mensaje = "El movimiento se elimino Correctamente";
            header('Location: /movimientos?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
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
        $lista_motivos[] = (!empty($_REQUEST["ID_Motivo_1"]) ? $_REQUEST["ID_Motivo_1"] : 1);
        $lista_motivos[] = (!empty($_REQUEST["ID_Motivo_2"]) ? $_REQUEST["ID_Motivo_2"] : 1);
        $lista_motivos[] = (!empty($_REQUEST["ID_Motivo_3"]) ? $_REQUEST["ID_Motivo_3"] : 1);
        $lista_motivos[] = (!empty($_REQUEST["ID_Motivo_4"]) ? $_REQUEST["ID_Motivo_4"] : 1);
        $lista_motivos[] = (!empty($_REQUEST["ID_Motivo_5"]) ? $_REQUEST["ID_Motivo_5"] : 1);
        $Observaciones = $_REQUEST["Observaciones"];
        $ID_Centro = $_REQUEST["ID_Centro"];
        $ID_OtraInstitucion = $_REQUEST["ID_OtraInstitucion"];
        $Estado = 1;
        if (!empty($Arr_ID_Responsable[1])) $lista_res[] = $Arr_ID_Responsable[1];
        if (!empty($Arr_ID_Responsable[2])) $lista_res[] = $Arr_ID_Responsable[2];
        if (!empty($Arr_ID_Responsable[3])) $lista_res[] = $Arr_ID_Responsable[3];
        if (!empty($Arr_ID_Responsable[0])) $lista_res[] = $Arr_ID_Responsable[0];

        if(empty($ID_Responsable[0])){
            $ID_Responsable = 'null';
        }

        if(empty($ID_Centro)){
            $ID_Centro = 'null';
        }

        $con = new Conexion();
        $con->OpenConexion();

        if (Persona::is_exist($con, $ID_Persona)) {
            $persona = new Persona($con, $ID_Persona);
        }

        if (Movimiento::is_exist($con, $ID_Movimiento)) {
            $movimiento_sin_modificar = new Movimiento(
                                        coneccion_base: $con, 
                                        xID_Movimiento: $ID_Movimiento
            );
            $fecha_previa = $movimiento_sin_modificar->getFecha();
            $id_persona_previa = $movimiento_sin_modificar->getID_Persona();
            $persona = new Persona(coneccion: $con, ID_Persona: $id_persona_previa);

            $movimiento = new Movimiento(
                coneccion_base: $con, 
                xFecha: $Fecha,
                Fecha_Creacion: $Fecha_Creacion,
                xID_Persona: $ID_Persona,
                xObservaciones: $Observaciones,
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

            $consulta = "SELECT * 
                        FROM movimiento_responsable
                        WHERE id_movimiento = $ID_Movimiento
                        AND estado = 1";
            $rs = mysqli_query($con->Conexion,$consulta) or die("Problemas al consultar las acciones.");

            while ($ret = mysqli_fetch_assoc($rs)) {
                if (!in_array($ret["id_responsable"], $lista_res)) {
                    $mov = new MovimientoResponsable(
                                                     connection: $con,
                                                     id_movimiento: $ID_Movimiento,
                                                     id_responsable: $ret["id_responsable"]
                                                     );
                   $mov->delete();
                }
            }

            foreach ($lista_res as $value) {
                $exist = MovimientoResponsable::exist_movimiento_responsable(
                    connection: $con,
                    movimiento: $ID_Movimiento,
                    id_responsable: $value
                );
                if (!$exist) {
                    $mov = new MovimientoResponsable(
                                                     connection: $con,
                                                     id_movimiento: $ID_Movimiento,
                                                     id_responsable: $ret["id_responsable"]
                                                     );

                    $mov->save();
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

        $rev = new Notificacion(coneccion_base: $con, detalle: $detalle_not , fecha: $expira);
        $rev->save();
        
        $con->CloseConexion();

        $Mensaje = "El Movimiento se modifico correctamente";
        header('Location: /movimientos?Mensaje=' . $Mensaje);
        exit();
    }
}