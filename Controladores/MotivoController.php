<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Calle.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Categoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Calle.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");


class MotivoController 
{

    public function listado_motivos($mensaje = null)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_motivos.php");
        }
        exit();
    }

    public function mod_motivo($id_motivo)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_modmotivos.php");
        }
        exit();
    }

    public function del_motivo_control($id_motivo)
    {
        $ID_Usuario = $_SESSION["Usuario"];
        $ID_Motivo = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja un Motivo. Datos: Motivo: $ID_Motivo";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Consulta = "update motivo set estado = 0 where id_motivo = $ID_Motivo";
            if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 0);		
            }


            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 1);
            }	

            $ConsultaSolicitud = "update solicitudes_eliminarmotivos set estado = 0 where ID_Motivo = $ID_Motivo";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
                throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
            }
            $Con->CloseConexion();
            $Mensaje = "El motivo se elimino Correctamente";
            header('Location: /home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function sol_mod_control(
                                    $id_motivo = null,
                                    $mensaje = null,
                                    $mensaje_error = null
    ){
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Motivo = $_REQUEST["ID"];
        $Motivo = $_REQUEST["Motivo"];
        $Codigo = strtoupper($_REQUEST["Codigo"]);
        $ID_Categoria = $_REQUEST["ID_Categoria"];
        $Num_Motivo = 0;

        $Fecha = date("Y-m-d");
        $Estado = 1;

        if ($ID_Categoria > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $categoria = new Categoria(
                                       xConecction: $Con,
                                       xID_Categoria: $ID_Categoria
                                      );
            $Cod_Categoria = $categoria->getCod_Categoria();

            $Solicitud = new Solicitud_ModificarMotivo(
                                                  0,
                                                    $Fecha,
                                                   $Motivo,
                                                   $Codigo,
                                                   $Cod_Categoria,
                                                   $Num_Motivo,
                                                   $Estado,
                                                   $ID_Usuario,
                                                   $ID_Motivo
                                                      );
            $Insert_Solicitud = "insert into solicitudes_modificarmotivos(Fecha,Motivo,Codigo,Cod_Categoria,Num_Motivo,Estado,ID_Usuario,ID_Motivo) values('{$Solicitud->getFecha()}','{$Solicitud->getMotivo()}','{$Solicitud->getCodigo()}','{$Solicitud->getCod_Categoria()}',{$Solicitud->getNum_Motivo()},{$Solicitud->getEstado()},{$Solicitud->getID_Usuario()},{$Solicitud->getID_Motivo()})";
            $MensajeError = "No se pudo enviar la solicitud";

            mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);

            $Con->CloseConexion();
            $Mensaje = "La solicitud de modificación se envió a los administradores para ser confirmada.";
            header('Location: /motivos?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar una Categoria";
            header('Location: /motivo/editar?ID=' . $ID_Motivo . '&MensajeError=' . $MensajeError);
        }
        exit();
    }

    public function mod_persona_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Solicitud = $_REQUEST["ID"];
        $ID_Motivo = $_REQUEST["ID_Motivo"];
        $Motivo = $_REQUEST["Motivo"];
        $Codigo = $_REQUEST["Codigo"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        try {
            $Con = new Conexion();
            $Con->OpenConexion();
            $ConsultarRegistrosIguales = "select * from motivo where motivo = '$Motivo' and id_motivo != $ID_Motivo and estado = 1";

            if (!$RetIguales = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)) {
                throw new Exception("Problemas al consultar registros iguales. Consulta: " . $ConsultarRegistrosIguales, 0);		
            }
            $Resultado = mysqli_num_rows($RetIguales);
            if ($Resultado > 0) {
                mysqli_free_result($RetIguales);
                $Con->CloseConexion();
                $Mensaje = "Ya existe un motivo con ese Dato ingrese otro valor";
                header('Location: /motivos?ID=' . $ID_Motivo . '&MensajeError=' . $Mensaje);
            } else {
                $ConsultarDatosViejos = "select * from motivo where id_motivo = $ID_Motivo and estado = 1";
                $ErrorDatosViejos = "No se pudieron consultar los datos";
                if (!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)) {
                    throw new Exception("Error al intentar registrar. Consulta: " . $ConsultarDatosViejos, 1);
                }		
                $TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
                $MotivoViejo = $TomarDatosViejos["Motivo"];
                $Cod_Viejo = $TomarDatosViejos["Codigo"];		

                // $ConsultarCod_Categoria = "select cod_categoria from categoria where id_categoria = $ID_Categoria";
                // if(!$RetCod = mysqli_query($Con->Conexion,$ConsultarCod_Categoria)){
                // 	throw new Exception("Problemas al consultar cod_categoria. Consulta: ".$ConsultarCod_Categoria, 1);			
                // }
                // $TomarCod = mysqli_fetch_assoc($RetCod);
                // $Cod_Categoria = $TomarCod["cod_categoria"];

                $Consulta = "update motivo set motivo = '$Motivo', codigo = '$Codigo' where id_motivo = $ID_Motivo and estado = 1";
                if (!$Ret = mysqli_query($Con->Conexion,$Consulta)) {
                    throw new Exception("Problemas en la consulta. Consulta: " . $Consulta, 2);			
                }
                
                $ConsultaSolicitud = "update solicitudes_modificarmotivos set estado = 0 where ID = $ID_Solicitud";
                if (!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)) {
                    throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
                }

                $Detalles = "El usuario con ID: $ID_Usuario ha modificado un Motivo. Datos: Dato Anterior: $MotivoViejo , Dato Nuevo: $Motivo - Dato Anterior: $Cod_Viejo , Dato Nuevo: $Codigo";
                $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
                if (!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)) {
                    throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 4);
                }	
                $Con->CloseConexion();
                $Mensaje = "El Motivo se modifico Correctamente";
                header('Location: /home?ID=' . $ID_Motivo . '&Mensaje='.$Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function sol_unif_control()
    {
        $Fecha = Date("Y-m-d");
        $ID_Registro_1 = $_REQUEST["ID_Motivo_1"];
        $ID_Registro_2 = $_REQUEST["ID_Motivo_2"];
        $ID_Usuario = $_SESSION["Usuario"];
        $Estado = 1;
        $TipoUnif = 1;

        if ($ID_Registro_1 > 0 && $ID_Registro_2 > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Solicitud = new Solicitud_Unificacion(0,$Fecha,$ID_Registro_1,$ID_Registro_2,$ID_Usuario,$Estado,$TipoUnif);
            $Insert_Solicitud = "insert into solicitudes_unificacion(Fecha,ID_Registro_1,ID_Registro_2,ID_Usuario,Estado,ID_TipoUnif) values('{$Solicitud->getFecha()}',{$Solicitud->getID_Registro_1()},{$Solicitud->getID_Registro_2()},{$Solicitud->getID_Usuario()},{$Solicitud->getEstado()},{$Solicitud->getTipoUnif()})";
            $MensajeError = "No se pudo enviar la solicitud";

            mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);

            $Con->CloseConexion();
            $Mensaje = "La solicitud de unificación se envió a los administradores para ser confirmada.";
            header('Location: /motivo/unificar?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Motivo y Segundo Motivo";
            header('Location: /motivo/unificar?MensajeError=' . $MensajeError);
        }
        exit();
    }

    public function unif_motivo($mensaje = null)
    {
        if (!isset($_SESSION["Usuario"])) {
            include("Error_Session.php");
        } else {
            include("./Views/view_unifmotivos.php");
        }
        exit();
    }

    public function unif_motivo_control()
    {
        $ID_Solicitud = $_REQUEST["ID_Solicitud"];
        $ID_Motivo_1 = $_REQUEST["ID_Motivo_1"];
        $ID_Motivo_2 = $_REQUEST["ID_Motivo_2"];

        if ($ID_Motivo_1 > 0 && $ID_Motivo_2 > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $ConsultarMotivos_1 = "select * from movimiento where motivo_1 = $ID_Motivo_2 and estado = 1";
            $MensajeErrorConsultarMotivos_1 = "No se pudieron consultar los casos de igualdad en el Motivo 1";

            $EjecutarConsultarMotivos_1 = mysqli_query($Con->Conexion, $ConsultarMotivos_1) or die($MensajeErrorConsultarMotivos_1);
            while($RetMotivos_1 = mysqli_fetch_assoc($EjecutarConsultarMotivos_1)){
                $ID_MovimientoMotivos_1 = $RetMotivos_1["id_movimiento"];
                $CambiarMotivos_1 = "update movimiento set motivo_1 = $ID_Motivo_1 where id_movimiento = $ID_MovimientoMotivos_1";
                $MensajeErrorCambiarMotivos_1 = "No se pudieron cambiar los motivos 1";
                mysqli_query($Con->Conexion, $CambiarMotivos_1) or die($MensajeErrorCambiarMotivos_1);
            }

            $ConsultarMotivos_2 = "select * from movimiento where motivo_2 = $ID_Motivo_2 and estado = 1";
            $MensajeErrorConsultarMotivos_2 = "No se pudieron consultar los casos de igualdad en el Motivo 2";

            $EjecutarConsultarMotivos_2 = mysqli_query($Con->Conexion, $ConsultarMotivos_2) or die($MensajeErrorConsultarMotivos_2);
            while($RetMotivos_2 = mysqli_fetch_assoc($EjecutarConsultarMotivos_2)){
                $ID_MovimientoMotivos_2 = $RetMotivos_2["id_movimiento"];
                $CambiarMotivos_2 = "update movimiento set motivo_2 = $ID_Motivo_1 where id_movimiento = $ID_MovimientoMotivos_2";
                $MensajeErrorCambiarMotivos_2= "No se pudieron cambiar los motivos 2";
                mysqli_query($Con->Conexion, $CambiarMotivos_2) or die($MensajeErrorCambiarMotivos_2);
            }

            $ConsultarMotivos_3 = "select * from movimiento where motivo_3 = $ID_Motivo_2 and estado = 1";
            $MensajeErrorConsultarMotivos_3 = "No se pudieron consultar los casos de igualdad en el Motivo 3";

            $EjecutarConsultarMotivos_3 = mysqli_query($Con->Conexion, $ConsultarMotivos_3) or die($MensajeErrorConsultarMotivos_3);
            while($RetMotivos_3 = mysqli_fetch_assoc($EjecutarConsultarMotivos_3)){
                $ID_MovimientoMotivos_3 = $RetMotivos_3["id_movimiento"];
                $CambiarMotivos_3 = "update movimiento set motivo_3 = $ID_Motivo_1 where id_movimiento = $ID_MovimientoMotivos_3";
                $MensajeErrorCambiarMotivos_3 = "No se pudieron cambiar los motivos 3";
                mysqli_query($Con->Conexion, $CambiarMotivos_3) or die($MensajeErrorCambiarMotivos_3);
            }

            $ConsultaBajaMotivo = "update motivo set estado = 0 where id_motivo = $ID_Motivo_2";
            $MensajeErrorBajaMotivo = "No se pudo dar de baja el Motivo";

            mysqli_query($Con->Conexion,$ConsultaBajaMotivo) or die($MensajeErrorBajaMotivo);

            $ConsultaSolicitud = "update solicitudes_unificacion set Estado = 0 where ID_Solicitud_Unificacion = $ID_Solicitud";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
                throw new Exception("Problemas en la consulta. Consulta: " . $ConsultaSolicitud, 3);			
            }

            $Con->CloseConexion();
            $Mensaje = "Los datos se unificaron Correctamente";
            header('Location: /home?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Motivo y Segundo Motivo";
            header('Location: /homep?MensajeError=' . $MensajeError);
        }

    }
}