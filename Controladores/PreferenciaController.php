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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Filtro.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/FiltroMotivo.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/FiltroResponsable.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/FiltroCategoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/FiltroBarrio.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/SolicitudItem.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/TipoAccion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/TipoGrupoOperacion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");


class PreferenciaController 
{

    public function listado_preferencias($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("../Error_Session.php");
        } else {
            $id_usuario = $_SESSION["Usuario"];
            $account = new Account(account_id: $id_usuario);
            $tipo_usuario = $account->get_id_tipo_usuario();
            $Element = new Elements();
            $DTGeneral = new CtrGeneral();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_usuarios.php");
        }
        exit();
    }

    public function mod_preferencia(
                                $id_account,
                                $mensaje = null,
                                $mensaje_error = null
    ) {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("../Error_Session.php");
        } else {

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $AccountID = $_REQUEST["account_id"];
            $usuario = new Account(account_id: $AccountID);
            $lastname = ucfirst($usuario->get_last_name());
            $firstname = ucwords($usuario->get_first_name());
            $initials = strtoupper($usuario->get_initials());
            $username = $usuario->get_user_name();
            $userpass = $usuario->get_password();
            $email = $usuario->get_email();
            $ID_Tipo = $usuario->get_id_tipo_usuario();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_modusuario.php");
        }
        exit();
    }

    public function datos_preferencia($id_preferencia)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("../Error_Session.php");
        } else {
            include("../view_verusuarios.php");
        }
        exit();
    }

    public function dato_preferencia_user($id_account = null, $mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");

        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $AccountID = $_REQUEST["account_id"];
            $account = new Account(account_id: $AccountID);
            $exist_user = Account::exist_account(account_id: $AccountID);
            $lastname = ucfirst($account->get_last_name());
            $firstname = ucwords($account->get_first_name());
            $initials = strtoupper($account->get_initials());
            $username = $account->get_user_name();
            $userpass = $account->get_password();
            $email = $account->get_email();
            $ID_Tipo = $account->get_id_tipo_usuario();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            $Element = new Elements();

            include("./Views/view_perfilusuario.php");
        }

        exit();
    }

    public function new_preferencia()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("../Error_Session.php");
        } else {
            $id_usuario = $_SESSION["Usuario"];
            $account = new Account(account_id: $id_usuario);
            $tipo_usuario = $account->get_id_tipo_usuario();
            $Element = new Elements();
            $DTGeneral = new CtrGeneral();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_newusuarios.php");
        }
         
    }

    public function eliminar_preferencia_control()
    {
        try {
            if (!isset($_SESSION["Usuario"])) {
                header("Content-Type: text/html;charset=utf-8;");
                include("../Error_Session.php");
            } else {
                header("Content-Type: application/json;");
                $id_usuario = $_SESSION["Usuario"];
                $id_solicitud = $_POST["id_solicitud"];
                $fecha = date(format: "Y-m-d");

                $con = new Conexion();
                $con->OpenConexion();

                $solicitud = new Solicitud(
                                coneccion: $con,
                                id_solicitud: $id_solicitud
                    );
                $id_filtro = SolicitudItem::get_valor_item_por_identificador_solicitud_id(
                                                                    coneccion: $con, 
                                                                    identificador: "ID_FILTRO",
                                                                    id_solicitud: $solicitud->get_id_solicitud()
                                                                    );
                $filtro = new Filtro(
                                coneccion: $con,
                                id_filtro: $id_filtro
                    );
                $filtro->delete();
                $solicitud->delete();
                $con->CloseConexion();
                $ret["mensaje"] = "La solicitud fue registrada Correctamente";
                $ret["estado"] = 1;
            }
            echo json_encode($ret);
        } catch (Exception $e) {
            $ret["mensaje"] = "error";
            $ret["estado"] = 0;
            echo json_encode($ret);
        }
    }

    public function eliminar_solicitud_control()
    {
        try {
            if (!isset($_SESSION["Usuario"])) {
                header("Content-Type: text/html;charset=utf-8;");
                include("../Error_Session.php");
            } else {
                header("Content-Type: application/json;");
                $id_solicitud = $_POST["id_solicitud"];

                $con = new Conexion();
                $con->OpenConexion();

                $solicitud = new Solicitud(
                                           coneccion: $con,
                                           id_solicitud: $id_solicitud
                                          );
                $solicitud->delete();
                $con->CloseConexion();
                $ret["mensaje"] = "La solicitud fue borrada Correctamente";
                $ret["estado"] = 1;
            }
            echo json_encode($ret);
        } catch (Exception $e) {
            $ret["mensaje"] = "error";
            $ret["estado"] = 0;
            echo json_encode($ret);
        }
    }

    public function nueva_preferencia_control()
    {
        try {
            if (!isset($_SESSION["Usuario"])) {
                header("Content-Type: text/html;charset=utf-8;");
                include("../Error_Session.php");
            } else {
                header("Content-Type: application/json;");
                $id_usuario = $_SESSION["Usuario"];
                $id_solicitud = $_POST["id_solicitud"];
                $fecha = date(format: "Y-m-d");

                $con = new Conexion();
                $con->OpenConexion();

                $solicitud = new Solicitud(
                                coneccion: $con,
                                id_solicitud: $id_solicitud
                    );
                $filtro = new Filtro(
                                coneccion: $con,
                                id_usuario: $solicitud->get_id_usuario(),
                                fecha: $fecha,
                                estado: 1
                    );
                $filtro->save();

                $list = SolicitudItem::get_solicitud_items_por_id_solicitud(coneccion: $con,
                                                                            id_solicitud: $id_solicitud);
                $list = array_map(function ($e) use ($filtro, $con) {
                    $key = $e->get_identificador();
                    $val = $e->get_valor();
                    $exist = false;
                    if ( $key == "ID_Persona") $filtro->set_id_persona($val);
                    if ( $key == "Meses_Desde") $filtro->set_meses_desde($val);
                    if ( $key == "Meses_Hasta") $filtro->set_meses_hasta($val);
                    if ( $key == "Nro_Legajo") $filtro->set_nro_legajo($val);
                    if ( $key == "Nro_Carpeta") $filtro->set_nro_carpeta($val);
                    if ( $key == "Manzana") $filtro->set_manzana($val);
                    if ( $key == "titulo") $filtro->set_titulo($val);

                    if ($key == "ID_OtraInstitucion") $filtro->set_id_otra_institucion($val);
                    if ($key == "Lote") $filtro->set_lote($val);
                    if ($key == "Sub_Lote") $filtro->set_sub_lote($val);
                    if ($key == "ID_Responsable") {
                        $exist = FiltroResponsable::exist_responsable_con_filtro(
                                    coneccion: $con, 
                                    id_filtro: $filtro->get_id_filtro(),
                                    id_responsable: $val
                                    );
                        if (!$exist) {
                            $filtro_responsable = new FiltroResponsable(
                                                                coneccion: $con,
                                                                id_responsable: $val,
                                                                id_filtro: $filtro->get_id_filtro(),
                                                                estado: 1
                            );
                            $filtro_responsable->save();
                        }
                    }
                    if ($key == "ID_Categoria") {
                        $exist = FiltroCategoria::exist_categoria_con_filtro(
                                    coneccion: $con, 
                                    id_filtro: $filtro->get_id_filtro(),
                                    id_categoria: $val);
                        if (!$exist) {
                            $filtro_categoria = new FiltroCategoria(
                                                            coneccion: $con,
                                                            id_categoria: $val,
                                                            id_filtro: $filtro->get_id_filtro(),
                                                            estado: 1
                            );
                            $filtro_categoria->save();
                        }
                    }
                    if ($key == "ID_Motivo") {
                        $exist = FiltroMotivo::exist_motivo_con_filtro(
                                    coneccion: $con, 
                                    id_filtro: $filtro->get_id_filtro(),
                                    id_motivo: $val);
                        if (!$exist) {
                            $filtro_motivo = new FiltroMotivo(
                                                            coneccion: $con,
                                                            id_motivo: $val,
                                                            id_filtro: $filtro->get_id_filtro(),
                                                            estado: 1
                            );
                            $filtro_motivo->save();
                        }
                    }
                    if ($key == "ID_Barrio") {
                        $exist = FiltroBarrio::exist_barrio_con_filtro(
                                    coneccion: $con, 
                                    id_filtro: $filtro->get_id_filtro(),
                                    id_barrio: $val
                                    );
                        if (!$exist) {
                            $filtro_barrio = new FiltroBarrio(
                                                            coneccion: $con,
                                                            id_barrio: $val,
                                                            id_filtro: $filtro->get_id_filtro(),
                                                            estado: 1
                            );
                            $filtro_barrio->save();
                        }
                    }
                }, $list);
                $filtro->update();
                $solicitud->delete();
                $con->CloseConexion();
                $ret["mensaje"] = "La solicitud fue registrada Correctamente";
                $ret["estado"] = 1;
            }
            echo json_encode($ret);
        } catch (Exception $e) {
            $ret["mensaje"] = "error";
            $ret["estado"] = 0;
            echo json_encode($ret);
        }
    }

    public function solicitud_nueva_preferencia_control()
    {
        $lista_preferencia = json_decode(file_get_contents('php://input'), true, 4);
        $id_usuario = $_SESSION["Usuario"];
        $fecha = date(format: "Y-m-d");
        try {
            if (!isset($_SESSION["Usuario"])) {
                header("Content-Type: text/html;charset=utf-8;");
                include("../Error_Session.php");
            } else {
                header("Content-Type: application/json;");
                $id_usuario = $_SESSION["Usuario"];
                $account = new Account(account_id: $id_usuario);
                $tipo_usuario = $account->get_id_tipo_usuario();
                $con = new Conexion();
                $con->OpenConexion();
                $id_tipo_grupo_operacion = TipoGrupoOperacion::get_id_por_tipo(
                                                    coneccion: $con,
                                                    tipo: "PREFERENCIA"
                                                    );
                $id_tipo_accion = TipoAccion::get_id_tipo_acciones_por_tipo(
                                                    coneccion: $con,
                                                    tipo: "INSERT"
                                                    );
                $solicitud = new Solicitud(
                                coneccion: $con,
                                id_usuario: $id_usuario,
                                fecha: $fecha,
                                id_tipo_grupo_operacion: $id_tipo_grupo_operacion,
                                id_tipo_accion: $id_tipo_accion,
                                estado: 1
                    );
                $solicitud->save();
                foreach($lista_preferencia as $key => $valor) {
                    if (isset($valor["ID_Motivo"])) {
                        $flag = array_map(function ($value) use ($con, $solicitud) {
                            try {
                                $rev = true;
                                $solicitud_item = new SolicitudItem(
                                            coneccion: $con,
                                            id_solicitud: $solicitud->get_id_solicitud(),
                                            valor: $value,
                                            identificador: "ID_Motivo",
                                            estado: 1
                                );
                                $solicitud_item->save();
                            } catch (Exception $e) {
                                $rev = false;
                            }
                            return $rev;
                        }, $valor["ID_Motivo"]);
                    } elseif (isset($valor["ID_Categoria"])) {
                        $flag = array_map(function ($value) use ($con, $solicitud) {
                            try {
                                $rev = true;
                                $solicitud_item = new SolicitudItem(
                                            coneccion: $con,
                                            id_solicitud: $solicitud->get_id_solicitud(),
                                            valor: $value,
                                            identificador: "ID_Categoria",
                                            estado: 1
                                );
                                $solicitud_item->save();
                            } catch (Exception $e) {
                                $rev = false;
                            }
                            return $rev;
                        }, $valor["ID_Categoria"]);
                    } elseif (isset($valor["ID_Responsable"])) {
                        $flag = array_map(function ($value) use ($con, $solicitud) {
                            try {
                                $rev = true;
                                $solicitud_item = new SolicitudItem(
                                            coneccion: $con,
                                            id_solicitud: $solicitud->get_id_solicitud(),
                                            valor: $value,
                                            identificador: "ID_Responsable",
                                            estado: 1
                                );
                                $solicitud_item->save();
                            } catch (Exception $e) {
                                $rev = false;
                            }
                            return $rev;
                        }, $valor["ID_Responsable"]);
                    } elseif (isset($valor["ID_Barrio"])) {
                        $flag = array_map(function ($value) use ($con, $solicitud) {
                            try {
                                $rev = true;
                                $solicitud_item = new SolicitudItem(
                                            coneccion: $con,
                                            id_solicitud: $solicitud->get_id_solicitud(),
                                            valor: $value,
                                            identificador: "ID_Barrio",
                                            estado: 1
                                );
                                $solicitud_item->save();
                            } catch (Exception $e) {
                                $rev = false;
                            }
                            return $rev;
                        }, $valor["ID_Barrio"]);
                    } else {
                        $rev = true;
                        $solicitud_item = new SolicitudItem(
                                    coneccion: $con,
                                    id_solicitud: $solicitud->get_id_solicitud(),
                                    valor: $valor[array_key_first($valor)],
                                    identificador: array_key_first($valor),
                                    estado: 1
                        );
                        $solicitud_item->save();
                    }
                }

                $resp["mensaje"] = "La solicitud fue registrada Correctamente";
                $resp["estado"] = 1;
            }
            echo json_encode($resp);
        } catch (Exception $e) {
            $resp["mensaje"] = "error" . $e->getMessage();
            $resp["estado"] = 0;
            echo json_encode($resp);
        }
    }

    public function solicitud_eliminar_preferencia_control()
    {
        $id_solicitud = $_POST["id_solicitud"];
        $id_usuario = $_SESSION["Usuario"];
        $fecha = date(format: "Y-m-d");
        try {
            if (!isset($_SESSION["Usuario"])) {
                header("Content-Type: text/html;charset=utf-8;");
                include("../Error_Session.php");
            } else {
                header("Content-Type: application/json;");
                $id_usuario = $_SESSION["Usuario"];
                $account = new Account(account_id: $id_usuario);
                $tipo_usuario = $account->get_id_tipo_usuario();
                $con = new Conexion();
                $con->OpenConexion();
                $id_tipo_grupo_operacion = TipoGrupoOperacion::get_id_por_tipo(
                                                    coneccion: $con,
                                                    tipo: "PREFERENCIA"
                                                    );
                $id_tipo_accion = TipoAccion::get_id_tipo_acciones_por_tipo(
                                                    coneccion: $con,
                                                    tipo: "DELETE"
                                                    );
                $solicitud = new Solicitud(
                                coneccion: $con,
                                id_usuario: $id_usuario,
                                fecha: $fecha,
                                id_tipo_grupo_operacion: $id_tipo_grupo_operacion,
                                id_tipo_accion: $id_tipo_accion,
                                estado: 1
                    );
                $solicitud->save();
                $solicitud_item = new SolicitudItem(
                            coneccion: $con,
                            id_solicitud: $solicitud->get_id_solicitud(),
                            valor: $id_solicitud,
                            identificador: "ID_FILTRO",
                            estado: 1
                );
                $solicitud_item->save();
                $resp["mensaje"] = "La solicitud fue registrada Correctamente";
                $resp["estado"] = 1;
            }
            echo json_encode($resp);
        } catch (Exception $e) {
            $resp["mensaje"] = "error" . $e->getMessage();
            $resp["estado"] = 0;
            echo json_encode($resp);
        }
    }

}