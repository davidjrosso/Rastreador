<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Responsable.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/SolicitudModificacion.php");

class ResponsableController 
{

    public function listado_responsables($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_responsables.php");
        }
        exit();
    }

    public function new_responsable()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $Con = new Conexion();
            $Con->OpenConexion();
            $ID_Usuario = $_SESSION["Usuario"];
            $ConsultarTipoUsuario = "select ID_TipoUsuario from accounts where accountid = $ID_Usuario";
            $MensajeErrorConsultarTipoUsuario = "No se pudo consultar el Tipo de Usuario";
            $EjecutarConsultarTipoUsuario = mysqli_query($Con->Conexion,$ConsultarTipoUsuario) or die($MensajeErrorConsultarTipoUsuario);
            $Ret = mysqli_fetch_assoc($EjecutarConsultarTipoUsuario);
            $TipoUsuario = $Ret["ID_TipoUsuario"];
            $Con->CloseConexion();
            $Element = new Elements();
            
            include("./Views/view_newresponsables.php");
        }
        exit();
    }


    public function mod_responsable($id_responsable)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $account = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $account->get_id_tipo_usuario();
            $Element = new Elements();

            $exist = false;
            if (isset($_REQUEST["ID"])) {
              $exist = true;

              $id_responsable = $_REQUEST["ID"];

              $con = new Conexion();
              $con->OpenConexion();
              $responsable = new Responsable(
                                             coneccion_base: $con,
                                             id_responsable: $id_responsable
                                            );
              $id_responsable = $responsable->get_id_responsable();
              $Responsable = $responsable->get_responsable();
              $con->CloseConexion();
            }

            include("./Views/view_modresponsables.php");
        }
        exit();
    }

    public function datos_responsable($id_responsable)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
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
            header('Location: ./responsable?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function del_responsable_control($id_responsable)
    {

        $id_usuario = $_SESSION["Usuario"];

        $id_solicitud = $_REQUEST["ID"];

        $fecha = date("Y-m-d");
        $id_tipo_accion = 3;

        try {
            $con = new Conexion();
            $con->OpenConexion();
            $solicitud = new SolicitudModificacion(
                                                    coneccion_base: $con,
                                                    id_solicitud: $id_solicitud
                                                );
            $id_responsable = $solicitud->get_id_registro();
            $existe_responsable = Responsable::existe_id_responsable(
                                                                    coneccion_base: $con,
                                                                    id_responsable: $id_responsable
                                                                    );
            if ($existe_responsable) {
                $detalles = "El usuario con ID: $id_usuario ha dado de baja un Responsable. Datos: Responsable: $id_responsable";
                $responsable = new Responsable(
                                            coneccion_base: $con,
                                            id_responsable: $id_responsable
                                            );
                $responsable->delete();
                $solicitud->delete();
                $accion = new Accion(
                                    xaccountid: $id_usuario,
                                    xFecha: $fecha,
                                    xDetalles:$detalles,
                                    xID_TipoAccion: $id_tipo_accion
                                    );
                $accion->save();
                $con->CloseConexion();
                $Mensaje = "El responsable fue eliminado Correctamente";
                header('Location: /home?Mensaje=' . $Mensaje);
            } else {
                $Mensaje = "El responsable no existe o ya fue eliminado.";
                $solicitud->delete();
                header('Location: /home?MensajeError=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
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
                header('Location: /responsable/editar?ID=' . $id_responsable . '&Mensaje=' . $mensaje);
            } else {
                $con->CloseConexion();
                $mensaje = "El responsable no esta registrado.";
                header('Location: /responsable?MensajeError=' . $mensaje);
            }

        } else {
            $mensaje_error = "Debe seleccionar una Responsable";
            header('Location: /responsables/editar?MensajeError=' . $mensaje_error);
        }
        exit();
    }

    public function mod_responsable_control()
    {
        $id_usuario = $_SESSION["Usuario"];

        $id_solicitud = $_REQUEST["ID"];
        $responsable = ucfirst($_REQUEST["Responsable"]);

        $fecha = date("Y-m-d");
        $id_tipo_accion = 2;

        $con = new Conexion();
        $con->OpenConexion();

        try {
            if($id_solicitud > 0){
                $solicitud = new SolicitudModificacion(
                                                        coneccion_base: $con,
                                                        id_solicitud: $id_solicitud
                );
                $id_responsable = $solicitud->get_id_registro();
                $solicitud->delete();
                $ResponsableDatosViejos = new Responsable(
                                                        coneccion_base: $con,
                                                        id_responsable: $id_responsable

                                                    );		
                $ResponsableViejo = $ResponsableDatosViejos->get_responsable();

                $ResponsableDatosViejos->set_responsable($responsable);
                $ResponsableDatosViejos->update();

                $detalles = "El usuario con ID: $id_usuario ha modificado un Responsable. Datos: Dato Anterior: $ResponsableViejo , Dato Nuevo: $responsable";

                $accion = new Accion(
                    xaccountid: $id_usuario,
                    xFecha : $fecha,
                    xDetalles: $detalles,
                    xID_TipoAccion: $id_tipo_accion	 
                );		

                $accion->save();

                $mensaje = "El Responsable se modificó Correctamente";
                header('Location: /home?&Mensaje=' . $mensaje);
            }
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

                $existe_responsable_unif = Responsable::existe_id_responsable(
                                                            coneccion_base: $con,
                                                            id_responsable: $id_responsable_unif
                                                        );
                $existe_responsable_del = Responsable::existe_id_responsable(
                                                           coneccion_base: $con,
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
            include("./Views/Error_Session.php");
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

    public function new_responsable_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $Responsable = ucfirst($_REQUEST["Responsable"]);
        $Estado = 1;

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 1;
        $Detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Responsable. Datos: $Responsable";

        $Con = new Conexion();
        $Con->OpenConexion();

        try {
            $responsable_obj = new Responsable(responsable: $Responsable, coneccion_base: $Con);
            $ConsultarResponsablesIguales = "select * from responsables where responsable = '$Responsable' and estado = 1";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultarResponsablesIguales)){
                throw new Exception("Error al consultar registros. Consulta: ".$ConsultarResponsablesIguales, 0);		
            }
            $Resultado = mysqli_num_rows($Ret);
            if(Responsable::get_id_responsable_by_name(coneccion_base: $Con, responsable: $Responsable) > 0){
                $Con->CloseConexion();
                $Mensaje = "Ya existe un Responsable con ese Nombre";
                header('Location: /responsable/nuevo?MensajeError='.$Mensaje);
            } else {
                $Consulta = "insert into responsables(responsable, estado) values('$Responsable',$Estado)";
                if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                    throw new Exception("Error al intentar registrar. Consulta: ".$Consulta, 1);
                }	
                $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
                if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                    throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 2);
                }
                $Con->CloseConexion();
                $Mensaje = "El Responsable se registro Correctamente";
                header('Location: /responsable/nuevo?Mensaje='.$Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }        
    }

    public function buscar_responsable($valor, $id)
    {
        header('Content-Type: text/html; charset=utf-8');

        $consultaBusqueda = $valor;
        $responsable_nro = $id;

        //Filtro anti-XSS
        $caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
        $caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
        $consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

        $mensaje = "";

        if (isset($consultaBusqueda)) {

            $Con = new Conexion();
            $Con->OpenConexion();

            $query = "SELECT * 
                    FROM responsable 
                    WHERE responsable LIKE '%$consultaBusqueda%' 
                        AND estado = 1
                    ORDER BY responsable ASC";

            $consulta = mysqli_query($Con->Conexion, $query);

            $filas = mysqli_num_rows($consulta);

            if ($filas === 0) {
                $mensaje = "<p>No hay ningún registro con ese dato</p>";
            } else {
                $mensaje .= '<table class="table">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">id</th>
                        <th scope="col">Responsable</th>	
                        <th scope="col">Acción</th>	
                        </tr>
                    </thead>
                    <tbody>';

                while($resultados = mysqli_fetch_array($consulta)) {
                    $id_responsable = $resultados["id_resp"];			
                    $responsable = $resultados['responsable'];
                    $mensaje .= '<tr>
                                    <th scope="row">' . $id_responsable . '</th>
                                    <td>' . $responsable . '</td>';
                    $mensaje .= '<td>
                                    <button type = "button" class = "btn btn-outline-success" onClick="seleccionResponsable(\'' . $responsable . '\', ' . $id_responsable . ', ' . $responsable_nro . ')" data-dismiss="modal">
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
}