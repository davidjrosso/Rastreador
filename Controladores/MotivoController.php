<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Calle.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Categoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_EliminarMotivo.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_ModificarMotivo.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Motivo.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_CrearMotivo.php");


class MotivoController 
{

    public function listado_motivos($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $Filtro = null;
            $ID_Filtro = null;
            if (isset($_REQUEST["Filtro"])) $Filtro = $_REQUEST["Filtro"];
            if (isset($_REQUEST["ID_Filtro"])) $ID_Filtro = $_REQUEST["ID_Filtro"];

            $Element = new Elements();
            $DTGeneral = new CtrGeneral();

            include("./Views/view_motivos.php");
        }
        exit();
    }

    public function mod_motivo($id_motivo)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {

            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $ID_Motivo = null;
            if(isset($_REQUEST["ID"])) $ID_Motivo = $_REQUEST["ID"];

            $Element = new Elements();

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

            $rev = new Motivo(coneccion_base: $Con, id_motivo: $ID_Motivo );
            $rev->delete();

            $accion = new Accion(
                xaccountid: $ID_Usuario,
                xFecha : $Fecha,
                xDetalles: $Detalles,
                xID_TipoAccion: $ID_TipoAccion	 
            );
            $accion->save();
        
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
                                                    xFecha: $Fecha,
                                                   xMotivo: $Motivo,
                                                   xCodigo: $Codigo,
                                                   xCod_Categoria: $Cod_Categoria,
                                                   xNum_Motivo: $Num_Motivo,
                                                   xEstado: $Estado,
                                                   xID_Usuario: $ID_Usuario,
                                                   xID_Motivo: $ID_Motivo,
                                                    xConeccion: $Con
                                                   );
            $Solicitud->save();

            $Con->CloseConexion();
            $Mensaje = "La solicitud de modificación se envió a los administradores para ser confirmada.";
            header('Location: /motivos?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar una Categoria";
            header('Location: /motivo/editar?ID=' . $ID_Motivo . '&MensajeError=' . $MensajeError);
        }
        exit();
    }


    function mod_motivo_control()
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
                    throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
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
                if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                    throw new Exception("Problemas en la consulta. Consulta: " . $Consulta, 2);			
                }
                
                $ConsultaSolicitud = "update solicitudes_modificarmotivos set estado = 0 where ID = $ID_Solicitud";
                if(!$Ret = mysqli_query($Con->Conexion, $ConsultaSolicitud)){
                    throw new Exception("Problemas en la consulta. Consulta: " . $ConsultaSolicitud, 3);			
                }

                $Detalles = "El usuario con ID: $ID_Usuario ha modificado un Motivo. Datos: Dato Anterior: $MotivoViejo , Dato Nuevo: $Motivo - Dato Anterior: $Cod_Viejo , Dato Nuevo: $Codigo";
                $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
                if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                    throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 4);
                }	
                $Con->CloseConexion();
                $Mensaje = "El Motivo se modifico Correctamente";
                header('Location: /home?ID=' . $ID_Motivo . '&Mensaje=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
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

            $Solicitud = new Solicitud_Unificacion(
                                                    xFecha: $Fecha, 
                                                    xID_Registro_1: $ID_Registro_1, 
                                                    xID_Registro_2: $ID_Registro_2, 
                                                    xID_Usuario: $ID_Usuario, 
                                                    xEstado: $Estado, 
                                                    xTipoUnif: $TipoUnif,
                                                    coneccion: $Con);
            $Solicitud->save();

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
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_unifmotivos.php");
        }
        exit();
    }

    public function new_motivo_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $Motivo = $_REQUEST["Motivo"];
        $Codigo = $_REQUEST["Codigo"];
        $ID = $_REQUEST["ID"];
        $Cod_Categoria = $_REQUEST["Cod_Categoria"];
        $Estado = 1;

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 1;
        $Detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Motivo. Datos: Motivo: $Motivo - Categoría : $Cod_Categoria";

        try	 {
            $Con = new Conexion();
            $Con->OpenConexion();

           if (Motivo::existe_motivo_by_name($Con, $Motivo) > 0) {
                $Con->CloseConexion();
                $Mensaje = "Ya hay un Motivo con los datos ingresados";
                header('Location: /home?MensajeError=' . $Mensaje);
            } else {
                $motivo = new Motivo(coneccion_base: $Con,
                                motivo: $Motivo,
                                codigo: $Codigo,
                                cod_categoria: $Cod_Categoria,
                                estado: $Estado
                                    );
                $motivo->save();

                $accion = new Accion(
                    xaccountid: $ID_Usuario,
                    xFecha : $Fecha,
                    xDetalles: $Detalles,
                    xID_TipoAccion: $ID_TipoAccion	 
                );
                $accion->save();

                $rev = new Solicitud_CrearMotivo(xID: $ID, xConeccion: $Con);
                $rev->delete();

                $Mensaje = "El Motivo se registro Correctamente";
                $Con->CloseConexion();
                header('Location: /home?Mensaje=' . $Mensaje);
            }

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
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
            header('Location: /home?MensajeError=' . $MensajeError);
        }

    }

    public function buscar_motivos_filtro()
    {
        $consultaBusqueda = $_REQUEST['valorBusqueda'];
        $id = $_REQUEST['ID'];

        //Filtro anti-XSS
        $caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
        $caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
        $consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

        //Variable vacía (para evitar los E_NOTICE)
        $mensaje = "";

        if (isset($consultaBusqueda)) {

            $Con = new Conexion();
            $Con->OpenConexion();
            $query = "SELECT * 
                      FROM motivo 
                      WHERE motivo LIKE '%$consultaBusqueda%' 
                        and estado = 1";

            $consulta = mysqli_query($Con->Conexion, $query);
            $filas = mysqli_num_rows($consulta);

            if ($filas === 0) {
                $mensaje = "<p>No hay ningún registro con ese dato</p>";
            } else {

                $mensaje .= '<table class="table">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">Motivo</th>
                        <th scope="col">Código</th>	
                        <th scope="col">Acción</th>	
                        </tr>
                    </thead>
                    <tbody>';

                while($resultados = mysqli_fetch_array($consulta)) {
                    $ID_Motivo = $resultados["id_motivo"];			
                    $Motivo = $resultados['motivo'];
                    $codigo = $resultados['codigo'];					

                    $mensaje .= '
                        <tr>
                        <th scope="row">' . $Motivo . '</th>
                        <td>' . $codigo . '</td>		
                        <td>
                            <button type = "button" class = "btn btn-outline-success" 
                                    onClick="seleccionMotivo(' . (($id) ? $id : 'null') . ',\'' . $Motivo .'\',' . $ID_Motivo  .')" 
                                    data-dismiss="modal">
                                seleccionar
                            </button>
                        </td>
                        </tr>';
                };

                $mensaje .= '</tbody>
                    </table>';

            };
            $Con->CloseConexion();

        };

        echo $mensaje;
    }

    public function req_del_motivo_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Motivo = $_REQUEST["ID"];
        $Fecha = date("Y-m-d");
        $Estado = 1;

        $Con = new Conexion();
        $Con->OpenConexion();

        $motivo = new Motivo(id_motivo: $ID_Motivo, coneccion_base: $Con);
        $Motivo = $motivo->get_motivo();
        $Cod_Categoria = $motivo->get_cod_categoria();
        $Num_Motivo = 0;

        $Solicitud = new Solicitud_EliminarMotivo(
                                                  xFecha: $Fecha,
                                                  xMotivo: $Motivo,
                                                  xCod_Categoria: $Cod_Categoria,
                                                  xNum_Motivo: $Num_Motivo,
                                                  xEstado: $Estado,
                                                  xID_Usuario: $ID_Usuario,
                                                  xID_Motivo: $ID_Motivo);
        $Solicitud->save();
        $Con->CloseConexion();
        $Mensaje = "La solicitud de eliminación se envió a los administradores para ser confirmada.";
        header('Location: /motivo/unificar?Mensaje=' . $Mensaje);
    }

    public function buscar_motivos()
    {
        $Filtro = $_REQUEST["Search"];
        $ID_Filtro = $_REQUEST["ID_Filtro"];

        header("Location: /motivos?Filtro=" . $Filtro . "&ID_Filtro=" . $ID_Filtro);

    }

    public function req_new_motivo_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $Motivo = $_REQUEST["Motivo"];
        $Codigo = strtoupper($_REQUEST["Codigo"]);
        $ID_Categoria = $_REQUEST["ID_Categoria"];
        $Num_Motivo = 0;

        $Fecha = date("Y-m-d");
        $Estado = 1;

        if($ID_Categoria > 0){
            $Con = new Conexion();
            $Con->OpenConexion();

            $ConsultarCod_Categoria = "select cod_categoria from categoria where id_categoria = $ID_Categoria";
            if(!$RetCod = mysqli_query($Con->Conexion,$ConsultarCod_Categoria)){
                throw new Exception("Problemas al consultar cod_categoria. Consulta: ".$ConsultarCod_Categoria, 1);			
            }
            $TomarCod = mysqli_fetch_assoc($RetCod);
            $Cod_Categoria = $TomarCod["cod_categoria"];

            $Insert_Solicitud = "insert into solicitudes_crearmotivos(Fecha,Motivo,Codigo,Cod_Categoria,Num_Motivo,Estado,ID_Usuario) 
                                values('{$Fecha}','{$Motivo}','{$Codigo}','{$Cod_Categoria}',{$Num_Motivo},{$Estado},{$ID_Usuario})";
            $MensajeError = "No se pudo enviar la solicitud";
            mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);

            $DetalleNot = "Se ha creado un nuevo motivo: ".$Motivo." , codigo: ".$Codigo."";
            $Expira = date("Y-m-d", strtotime($Fecha." + 30 days"));

            $ConsultaNot = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('".$DetalleNot."','".$Fecha."', '".$Expira."',1)";
            if(!$RetNot = mysqli_query($Con->Conexion,$ConsultaNot)){
                throw new Exception("Error al intentar registrar Notificacion. Consulta: ".$ConsultaNot, 3);
            }

            $Con->CloseConexion();
            $Mensaje = "La solicitud de creación de motivo se envió a los administradores para ser confirmada.";
            header('Location: /motivos?Mensaje=' . $Mensaje);
        }else{
            $MensajeError = "Debe seleccionar una Categoria";
            header('Location: /motivo/editar?MensajeError=' . $MensajeError);
        }        
    }

}