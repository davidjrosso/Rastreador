<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Responsable.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");


class ResponsableController 
{

    public function listado_responsables($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_responsables.php");
        }
        exit();
    }

    public function mod_responsable($id_responsable)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_modresponsables.php");
        }
        exit();
    }

    public function datos_responsable($id_responsable)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_verresponsables.php");
        }
        exit();
    }

    public function sol_del_control($id_responsable)
    {
        $id_usuario = $_SESSION["Usuario"];

        $id_responsable = $_REQUEST["ID"];
        $fecha = date("Y-m-d");
        $estado = 0;

        try {
            $con = new Conexion();
            $con->OpenConexion();

            $existe_responsable = Responsable::existe_id_responsable(
                                        coneccion_base: $con,
                                        id_responsable: $id_responsable
                                        );
            if ($existe_responsable) {
                $solicitud = new SolicitudModificacion(
                                                    coneccion_base: $con,
                                                    id_usuario: $id_usuario,
                                                    id_registro: $id_responsable,
                                                    id_tipo: 3,
                                                    fecha: $fecha
                                                    );
                $solicitud->save();
            }
            $con->CloseConexion();
            $Mensaje = "La solicitud de eliminación se envió a los administradores para ser confirmada.";
            header('Location: ./responsables?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function del_responsable_control($id_responsable)
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_responsable = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una responsable. Datos: responsable: $ID_responsable";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Consulta = "update responsable set estado = 0 where id_responsable = $ID_responsable";
            if (!$Ret = mysqli_query($Con->Conexion,$Consulta)) {
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 0);		
            }
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if (!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)) {
                throw new Exception("Error al intentar registrar Accion. Consulta: " . $ConsultaAccion, 1);
            }

            $ConsultaSolicitud = "update solicitudes_eliminarresponsables set estado = 0 where ID_responsable = $ID_responsable";
            if (!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)) {
                throw new Exception("Problemas en la consulta. Consulta: " . $ConsultaSolicitud, 3);			
            }

            $Con->CloseConexion();
            $Mensaje = "La responsable fue eliminada Correctamente";
            header('Location: /home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function sol_mod_control(
                                    $id_responsable = null,
                                    $mensaje = null,
                                    $mensaje_error = null
    ){
        $id_usuario = $_SESSION["Usuario"];

        $id_responsable = $_REQUEST["ID"];
        $responsable_nombre = trim($_REQUEST["Responsable"]);

        $fecha = date("Y-m-d");
        $estado = 1;

        if($id_responsable > 0){
            $con = new Conexion();
            $con->OpenConexion();


            $existe_nombre = Responsable::is_registered_name_with_id_responsable(
                                                                                coneccion_base: $con,
                                                                                nombre: $responsable_nombre,
                                                                                id_responsable: $id_responsable
                                                                                );
            $existe_responsable = Responsable::existe_id_responsable(
                                                                    coneccion_base: $con, 
                                                                    id_responsable: $id_responsable
                                                                    );
            if ($existe_nombre) {
                $con->CloseConexion();
                $mensaje = "Ya existe un Responsable con ese Nombre";
                header('Location: /responsables/editar?ID=' . $id_responsable.'&MensajeError=' . $mensaje);
            } else if ($existe_responsable) {
                $solicitud_modificacion = new SolicitudModificacion(
                    coneccion_base: $con,
                    id_usuario: $id_usuario,
                    id_registro: $id_responsable,
                    id_tipo: 1,
                    valor: $responsable_nombre,
                    fecha: $fecha
                );
                $solicitud_modificacion->save();
            
                $con->CloseConexion();
                $mensaje = "La solicitud de modificacion de responsable se envió a los administradores para ser confirmada.";
                header('Location: /responsables/editar?ID=' . $id_responsable . '&Mensaje=' . $mensaje);
            } else {
                $con->CloseConexion();
                $mensaje = "El responsable no esta registrado.";
                header('Location: /responsables?MensajeError=' . $mensaje);
            }

        } else {
            $mensaje_error = "Debe seleccionar una Responsable";
            header('Location: /responsables/editar?MensajeError=' . $mensaje_error);
        }
        exit();
    }

    public function mod_responsable_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];
        $ID_responsable = $_REQUEST["ID_responsable"];
        $ID_Solicitud = $_REQUEST["ID"];
        $Codigo = strtoupper($_REQUEST["Codigo"]);
        $responsable = $_REQUEST["responsable"];
        $ID_Forma = $_REQUEST["ID_Forma"];
        $NuevoColor = base64_decode($_REQUEST["CodigoColor"]);

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $ConsultarDatosViejos = "select * from responsable where id_responsable = $ID_responsable and estado = 1";
            $ErrorDatosViejos = "No se pudieron consultar los datos";
            if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
                throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
            }
            $TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
            $Cod_responsableViejo = $TomarDatosViejos["cod_responsable"];
            $responsableViejo = $TomarDatosViejos["responsable"];
            $ID_FormaViejo = $TomarDatosViejos["ID_Forma"];
            $ColorViejo = $TomarDatosViejos["color"];

            $Consulta = "update motivos 
                        set cod_responsable = '$Codigo' 
                        where cod_responsable = '$Cod_responsableViejo' 
                        and estado = 1";

            $CodigoColorEsc = mysqli_real_escape_string($Con->Conexion, $NuevoColor);
            $Consulta = "update responsable 
                        set cod_responsable = '$Codigo', 
                            responsable = '$responsable', 
                            ID_Forma = $ID_Forma, 
                            color = '$CodigoColorEsc' 
                        where id_responsable = $ID_responsable 
                        and estado = 1";
            
            if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
            }

            $Detalles = "El usuario con ID: $ID_Usuario ha modificado la responsable: $ID_responsable. Datos: Dato Anterior: $Cod_responsableViejo , Dato Nuevo: $Codigo - Dato Anterior: $responsableViejo , Dato Nuevo: $responsable - Dato Anterior: $ID_FormaViejo , Dato Nuevo: $ID_Forma - Dato Anterior: $ColorViejo , Dato Nuevo: $NuevoColor con id solicitud : $ID_Solicitud";
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
            }

            $ConsultaPermisos = "select tip.ID_TipoUsuario, IF(slp.ID_TipoUsuario IS NULL, 'si', 'no') as disable
                                from (SELECT * FROM solicitudes_permisos WHERE ID = {$ID_Solicitud} and estado = 1) slp right join Tipo_Usuarios tip ON slp.ID_TipoUsuario = tip.ID_TipoUsuario";
            $MessageError = "Problemas al consultar Solicitudes Permisos";
            if(!$Resultados = mysqli_query($Con->Conexion,$ConsultaPermisos)){
                throw new Exception("No se pudo insertar el conjunto de permisos. Consulta: ".$ConsultaPermisos, 2);
            }
            while ($RetPermisos = mysqli_fetch_array($Resultados)) {
                $GrupoUsuarios = $RetPermisos["ID_TipoUsuario"];

                $ConsultaPermisosresponsable = "select *
                                            from responsables_roles
                                            where id_responsable = {$ID_responsable}
                                                and id_tipousuario = {$GrupoUsuarios} 
                                                and estado = 1";

                $MessageError = "Problemas al consultar responsables Permisos";
                if(!$ResultadosPermisosresponsables = mysqli_query($Con->Conexion,$ConsultaPermisosresponsable)){
                    throw new Exception("No se pudo consultar conjunto de permisos sobre la responsable. Consulta: ".$ConsultaPermisosresponsable, 2);
                }

                if(mysqli_num_rows($ResultadosPermisosresponsables) == 0){
                    if( $RetPermisos["disable"] == "no"){
                        $Insert_Permiso = "insert into responsables_roles(id_responsable, fecha, ID_TipoUsuario, estado) values('{$ID_responsable}', '{$Fecha}','{$GrupoUsuarios}', 1)";
                        if(!$RetID = mysqli_query($Con->Conexion,$Insert_Permiso)){
                            throw new Exception("No se pudo actualizar el permisos. Consulta: ".$Insert_Permiso, 2);
                        }
                        $updatePermisos = "update solicitudes_permisos
                                        set estado = 0
                                        where ID = {$ID_Solicitud}
                                            and ID_TipoUsuario = {$GrupoUsuarios} 
                                            and estado = 1";
                        $MensajeError = "No se pudo dar de baja el permiso responsable {$ID_responsable} rol {$GrupoUsuarios}";
                        $ResultadosUpdate = mysqli_query($Con->Conexion,$updatePermisos) or die($MessageError);
                    }
                } else {
                    if( $RetPermisos["disable"] == "si"){
                        $updatePermisos = "update responsables_roles
                                        set estado = 0
                                        where id_responsable = {$ID_responsable}
                                        and ID_TipoUsuario = {$GrupoUsuarios} 
                                        and estado = 1";
                        $MensajeError = "No se pudo dar de baja el permiso responsable {$ID_responsable} rol {$GrupoUsuarios}";
                        if(!$RetID = mysqli_query($Con->Conexion,$updatePermisos)){
                            throw new Exception($MensajeError . ". Consulta: ".$updatePermisos, 2);
                        }
                    } else {
                        $updatePermisos = "update solicitudes_permisos
                                        set estado = 0
                                        where ID = {$ID_Solicitud}
                                        and ID_TipoUsuario = {$GrupoUsuarios} 
                                        and estado = 1";
                        $MensajeError = "No se pudo dar de baja el permiso responsable {$ID_responsable} rol {$GrupoUsuarios}";
                        $ResultadosUpdate = mysqli_query($Con->Conexion,$updatePermisos) or die($MessageError);
                    }
                }
            }

            $ConsultaSolicitud = "update solicitudes_modificarresponsables set estado = 0 where Codigo = '$Codigo' and estado = 1";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
                throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
            }

            $Con->CloseConexion();
            $Mensaje = "La responsable se modifico Correctamente";
            header('Location: /home.php?ID=' . $ID_responsable . '&Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function sol_unif_control()
    {
        $id_usuario = $_SESSION["Usuario"];
        $id_responsable_unif = $_REQUEST["ID_responsable_unif"];
        $id_responsable_del = $_REQUEST["ID_responsable_del"];

        $fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        try {
            $con = new Conexion();
            $con->OpenConexion();

            if (!empty($id_responsable_unif) && !empty($id_responsable_del)) {
                $con = new Conexion();
                $con->OpenConexion();

                $existe_responsable_unif = Responsable::exist_responsable(
                                                            connection: $con,
                                                            id_responsable: $id_responsable_unif
                                                        );
                $existe_responsable_del = Responsable::exist_responsable(
                                                            connection: $con,
                                                            id_responsable: $id_responsable_del
                                                        );

                if(!$existe_responsable_unif || !$existe_responsable_del){
                    $con->CloseConexion();
                    $mensaje = "No existen la responsable a unificar";
                    header('Location: /responsable/unificar?MensajeError=' . $mensaje);
                } else {
                    $solicitud_unificacion = new Solicitud_Unificacion(
                        coneccion: $con,
                        xID_Usuario : $id_usuario,
                        xID_Registro_1 : $id_responsable_unif,
                        xTipoUnif: 6,
                        xID_Registro_2 : $id_responsable_del,
                        xFecha: $fecha
                    );
                    $solicitud_unificacion->save();
                
                    $con->CloseConexion();
                    $mensaje = "La solicitud de unificacion de responsable se envió a los administradores para ser confirmada.";
                    header('Location: /responsable/unificar?Mensaje=' . $mensaje);
                }

            } else {
                $mensaje_error = "Debe seleccionar una responsable";
                header('Location: /responsable/unificar?MensajeError=' . $mensaje_error);
            }

        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function unif_responsable($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_unifresponsables.php");
        }
        exit();
    }

    public function unif_responsable_control()
    {
        $id_usuario = $_SESSION["Usuario"];

        $id_solicitud = $_REQUEST["ID"];

        $fecha = date("Y-m-d");
        $id_tipo_accion = 2;

        $con = new Conexion();
        $con->OpenConexion();

        try {
            if ($id_solicitud > 0) {
                $solicitud = new Solicitud_Unificacion(
                                                    coneccion: $con,
                                                    xID_Solicitud : $id_solicitud
                                                    );
                $id_responsable_unif = $solicitud->getID_Registro_1();
                $id_responsable_del = $solicitud->getID_Registro_2();
                $solicitud->delete();

                $responsable_unif = new Responsable(
                                                    coneccion_base: $con,
                                                    id_responsable: $id_responsable_unif
                                                );
                $responsable_del = new Responsable(
                                                coneccion_base: $con,
                                                id_responsable: $id_responsable_del
                                                );
                $resp_unif_nombre = $responsable_unif->get_responsable();
                $resp_del_nombre = $responsable_del->get_responsable();

                $Consulta = "UPDATE movimiento 
                            SET id_resp = $id_responsable_unif
                            WHERE id_resp = $id_responsable_del
                            AND estado = 1";
                
                if(!$Ret = mysqli_query($con->Conexion,$Consulta)){
                    throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
                }

                $Consulta = "UPDATE movimiento 
                            SET id_resp_2 = $id_responsable_unif
                            WHERE id_resp_2 = $id_responsable_del
                            AND estado = 1";
                
                if(!$Ret = mysqli_query($con->Conexion,$Consulta)){
                    throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
                }

                $Consulta = "UPDATE movimiento 
                            SET id_resp_3 = $id_responsable_unif
                            WHERE id_resp_3 = $id_responsable_del
                            AND estado = 1";
                
                if(!$Ret = mysqli_query($con->Conexion,$Consulta)){
                    throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
                }

                $Consulta = "UPDATE movimiento 
                            SET id_resp_4 = $id_responsable_unif
                            WHERE id_resp_4 = $id_responsable_del
                            AND estado = 1";
                
                if(!$Ret = mysqli_query($con->Conexion,$Consulta)){
                    throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
                }

                $Consulta = "UPDATE archivos 
                            SET responsable = $id_responsable_unif
                            WHERE responsable = '$id_responsable_del' 
                            AND estado = 1";
                
                if(!$Ret = mysqli_query($con->Conexion,$Consulta)){
                    throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
                }

                $responsable_del->delete();
                $detalles = "El usuario con ID: $id_usuario ha unificado a Responsables. Datos: Dato responsable unif: $resp_unif_nombre , Dato responsable del : $resp_del_nombre";

                $accion = new Accion(
                    xaccountid: $id_usuario,
                    xFecha : $fecha,
                    xDetalles: $detalles,
                    xID_TipoAccion: $id_tipo_accion	 
                );		

                $accion->save();

                $mensaje = "El Responsable se unificó correctamente";
                header('Location: /home?&Mensaje=' . $mensaje);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        exit();
    }
}