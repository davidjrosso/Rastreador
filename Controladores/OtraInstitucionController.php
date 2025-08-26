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


class OtraInstitucionController 
{

    public function listado_otras_instituciones($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_otrasinstituciones.php");
        }
        exit();
    }

    public function mod_otra_institucion($id_otra_institucion)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_modotrasinstituciones.php");
        }
        exit();
    }

    public function sol_del_control($id_otra_institucion)
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_otra_institucion= $_REQUEST["ID"];
        $Fecha = date("Y-m-d");
        $Estado = 1;

        $Con = new Conexion();
        $Con->OpenConexion();

        $Consultarotra_institucion = "select id_otra_institucion, otra_institucion, cod_otra_institucion from otra_institucion where id_otra_institucion = $ID_otra_institucion";

        if (!$Retotra_institucion = mysqli_query($Con->Conexion,$Consultarotra_institucion)) {
            throw new Exception("Problemas al consultar datos de otra_institucion. Consulta: ".$Consultarotra_institucion, 1);			
        }
        $Tomarotra_institucion = mysqli_fetch_assoc($Retotra_institucion);
        $otra_institucion = $Tomarotra_institucion['otra_institucion'];
        $Cod_otra_institucion = $Tomarotra_institucion["cod_otra_institucion"];

        $Solicitud = new Solicitud_Eliminarotra_institucion(0,$Fecha,$otra_institucion,$Cod_otra_institucion,$Estado,$ID_Usuario,$ID_otra_institucion);
        $Insert_Solicitud = "insert into solicitudes_eliminarotra_institucions(Fecha,otra_institucion,Cod_otra_institucion,Estado,ID_Usuario,ID_otra_institucion) values('{$Solicitud->getFecha()}','{$Solicitud->getotra_institucion()}','{$Solicitud->getCod_otra_institucion()}',{$Solicitud->getEstado()},{$Solicitud->getID_Usuario()},{$Solicitud->getID_otra_institucion()})";
        $MensajeError = "No se pudo enviar la solicitud";

        mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError." - ".$Insert_Solicitud);

        $Con->CloseConexion();
        $Mensaje = "La solicitud de eliminación se envió a los administradores para ser confirmada.";
        header('Location: /otra_institucions?Mensaje='.$Mensaje);
    }
    public function del_otra_institucion_control($id_otra_institucion)
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_otra_institucion = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una otra_institucion. Datos: otra_institucion: $ID_otra_institucion";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Consulta = "update otra_institucion set estado = 0 where id_otra_institucion = $ID_otra_institucion";
            if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 0);		
            }
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                throw new Exception("Error al intentar registrar Accion. Consulta: " . $ConsultaAccion, 1);
            }

            $ConsultaSolicitud = "update solicitudes_eliminarotra_institucions set estado = 0 where ID_otra_institucion = $ID_otra_institucion";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
                throw new Exception("Problemas en la consulta. Consulta: " . $ConsultaSolicitud, 3);			
            }

            $Con->CloseConexion();
            $Mensaje = "La otra_institucion fue eliminada Correctamente";
            header('Location: /home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function sol_mod_control(
                                    $id_otra_institucion = null,
                                    $mensaje = null,
                                    $mensaje_error = null
    ){
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Motivo = $_REQUEST["ID"];
        $Motivo = $_REQUEST["Motivo"];
        $Codigo = strtoupper($_REQUEST["Codigo"]);
        $ID_otra_institucion = $_REQUEST["ID_otra_institucion"];
        $Num_Motivo = 0;

        $Fecha = date("Y-m-d");
        $Estado = 1;

        if ($ID_otra_institucion > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $otra_institucion = new otra_institucion(
                                       xConecction: $Con,
                                       xID_otra_institucion: $ID_otra_institucion
                                      );
            $Cod_otra_institucion = $otra_institucion->getCod_otra_institucion();

            $Solicitud = new Solicitud_ModificarMotivo(
                                                  0,
                                                    $Fecha,
                                                   $Motivo,
                                                   $Codigo,
                                                   $Cod_otra_institucion,
                                                   $Num_Motivo,
                                                   $Estado,
                                                   $ID_Usuario,
                                                   $ID_Motivo
                                                      );
            $Insert_Solicitud = "insert into solicitudes_modificarmotivos(Fecha,Motivo,Codigo,Cod_otra_institucion,Num_Motivo,Estado,ID_Usuario,ID_Motivo) values('{$Solicitud->getFecha()}','{$Solicitud->getMotivo()}','{$Solicitud->getCodigo()}','{$Solicitud->getCod_otra_institucion()}',{$Solicitud->getNum_Motivo()},{$Solicitud->getEstado()},{$Solicitud->getID_Usuario()},{$Solicitud->getID_Motivo()})";
            $MensajeError = "No se pudo enviar la solicitud";

            mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);

            $Con->CloseConexion();
            $Mensaje = "La solicitud de modificación se envió a los administradores para ser confirmada.";
            header('Location: /motivos?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar una otra_institucion";
            header('Location: /motivo/editar?ID=' . $ID_Motivo . '&MensajeError=' . $MensajeError);
        }
        exit();
    }

    public function mod_otra_institucion_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];
        $ID_otra_institucion = $_REQUEST["ID_otra_institucion"];
        $ID_Solicitud = $_REQUEST["ID"];
        $Codigo = strtoupper($_REQUEST["Codigo"]);
        $otra_institucion = $_REQUEST["otra_institucion"];
        $ID_Forma = $_REQUEST["ID_Forma"];
        $NuevoColor = base64_decode($_REQUEST["CodigoColor"]);

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $ConsultarDatosViejos = "select * from otra_institucion where id_otra_institucion = $ID_otra_institucion and estado = 1";
            $ErrorDatosViejos = "No se pudieron consultar los datos";
            if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
                throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
            }
            $TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
            $Cod_otra_institucionViejo = $TomarDatosViejos["cod_otra_institucion"];
            $otra_institucionViejo = $TomarDatosViejos["otra_institucion"];
            $ID_FormaViejo = $TomarDatosViejos["ID_Forma"];
            $ColorViejo = $TomarDatosViejos["color"];

            $Consulta = "update motivos 
                        set cod_otra_institucion = '$Codigo' 
                        where cod_otra_institucion = '$Cod_otra_institucionViejo' 
                        and estado = 1";

            $CodigoColorEsc = mysqli_real_escape_string($Con->Conexion, $NuevoColor);
            $Consulta = "update otra_institucion 
                        set cod_otra_institucion = '$Codigo', 
                            otra_institucion = '$otra_institucion', 
                            ID_Forma = $ID_Forma, 
                            color = '$CodigoColorEsc' 
                        where id_otra_institucion = $ID_otra_institucion 
                        and estado = 1";
            
            if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
            }

            $Detalles = "El usuario con ID: $ID_Usuario ha modificado la otra_institucion: $ID_otra_institucion. Datos: Dato Anterior: $Cod_otra_institucionViejo , Dato Nuevo: $Codigo - Dato Anterior: $otra_institucionViejo , Dato Nuevo: $otra_institucion - Dato Anterior: $ID_FormaViejo , Dato Nuevo: $ID_Forma - Dato Anterior: $ColorViejo , Dato Nuevo: $NuevoColor con id solicitud : $ID_Solicitud";
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

                $ConsultaPermisosotra_institucion = "select *
                                            from otra_institucions_roles
                                            where id_otra_institucion = {$ID_otra_institucion}
                                                and id_tipousuario = {$GrupoUsuarios} 
                                                and estado = 1";

                $MessageError = "Problemas al consultar otra_institucions Permisos";
                if(!$ResultadosPermisosotra_institucions = mysqli_query($Con->Conexion,$ConsultaPermisosotra_institucion)){
                    throw new Exception("No se pudo consultar conjunto de permisos sobre la otra_institucion. Consulta: ".$ConsultaPermisosotra_institucion, 2);
                }

                if(mysqli_num_rows($ResultadosPermisosotra_institucions) == 0){
                    if( $RetPermisos["disable"] == "no"){
                        $Insert_Permiso = "insert into otra_institucions_roles(id_otra_institucion, fecha, ID_TipoUsuario, estado) values('{$ID_otra_institucion}', '{$Fecha}','{$GrupoUsuarios}', 1)";
                        if(!$RetID = mysqli_query($Con->Conexion,$Insert_Permiso)){
                            throw new Exception("No se pudo actualizar el permisos. Consulta: ".$Insert_Permiso, 2);
                        }
                        $updatePermisos = "update solicitudes_permisos
                                        set estado = 0
                                        where ID = {$ID_Solicitud}
                                            and ID_TipoUsuario = {$GrupoUsuarios} 
                                            and estado = 1";
                        $MensajeError = "No se pudo dar de baja el permiso otra_institucion {$ID_otra_institucion} rol {$GrupoUsuarios}";
                        $ResultadosUpdate = mysqli_query($Con->Conexion,$updatePermisos) or die($MessageError);
                    }
                } else {
                    if( $RetPermisos["disable"] == "si"){
                        $updatePermisos = "update otra_institucions_roles
                                        set estado = 0
                                        where id_otra_institucion = {$ID_otra_institucion}
                                        and ID_TipoUsuario = {$GrupoUsuarios} 
                                        and estado = 1";
                        $MensajeError = "No se pudo dar de baja el permiso otra_institucion {$ID_otra_institucion} rol {$GrupoUsuarios}";
                        if(!$RetID = mysqli_query($Con->Conexion,$updatePermisos)){
                            throw new Exception($MensajeError . ". Consulta: ".$updatePermisos, 2);
                        }
                    } else {
                        $updatePermisos = "update solicitudes_permisos
                                        set estado = 0
                                        where ID = {$ID_Solicitud}
                                        and ID_TipoUsuario = {$GrupoUsuarios} 
                                        and estado = 1";
                        $MensajeError = "No se pudo dar de baja el permiso otra_institucion {$ID_otra_institucion} rol {$GrupoUsuarios}";
                        $ResultadosUpdate = mysqli_query($Con->Conexion,$updatePermisos) or die($MessageError);
                    }
                }
            }

            $ConsultaSolicitud = "update solicitudes_modificarotra_institucions set estado = 0 where Codigo = '$Codigo' and estado = 1";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
                throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
            }

            $Con->CloseConexion();
            $Mensaje = "La otra_institucion se modifico Correctamente";
            header('Location: /home.php?ID=' . $ID_otra_institucion . '&Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function unif_otra_institucion($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_unifotrasinstituciones.php");
        }
        exit();
    }

    public function unif_otra_institucion_control()
    {
        $ID_Institucion_1 = $_REQUEST["ID_Institucion_1"];
        $ID_Institucion_2 = $_REQUEST["ID_Institucion_2"];

        if($ID_Institucion_1 > 0 && $ID_Institucion_2 > 0){
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
}