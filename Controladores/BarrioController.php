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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . '/Modelo/Barrio.php');
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");


class BarrioController 
{

    public function listado_barrios($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();
            $DTGeneral = new CtrGeneral();

            include("./Views/view_barrios.php");
        }
        exit();
    }

    public function mod_barrio($id_barrio)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_modbarrios.php");
        }
        exit();
    }

    public function datos_barrio($id_barrio)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $id_usuario = $_SESSION["Usuario"];
            $account = new Account(account_id: $id_usuario);
            $tipo_usuario = $account->get_id_tipo_usuario();
            $Element = new Elements();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_verbarrios.php");
        }
        exit();
    }

    public function crear_barrio($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $id_usuario = $_SESSION["Usuario"];
            $account = new Account(account_id: $id_usuario);
            $tipo_usuario = $account->get_id_tipo_usuario();
            $Element = new Elements();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_newbarrios.php");
        }
        exit();
    }

    public function crear_barrio_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $Barrio = ucwords($_REQUEST["Barrio"]);
        $Estado = 1;

        $georeferencia_point = null;
        if (!empty($_REQUEST["lat"])) {
            $lat_point = $_REQUEST["lat"];
            $georeferencia_point = "POINT(" . $lat_point;

            if (!empty($_REQUEST["lon"])){
                $lon_point = $_REQUEST["lon"];
                $georeferencia_point .= "," . $lon_point . ")";
            } else {
                $georeferencia_point = null;
            }
        }

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 1;
        $Detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Barrio. Datos: $Barrio";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();
            $existe = Barrio::existe_barrio(coneccion: $Con, name: $Barrio);
            if ($existe > 0) {
                $Con->CloseConexion();
                $Mensaje = "Ya existe un Barrio con ese Nombre";
                header('Location: /barrio/nuevo?MensajeError=' . $Mensaje);
            } else {
                $barrio = new Barrio(coneccion: $Con, barrio: $Barrio, georeferencia: $georeferencia_point);
                $barrio->save(coneccion: $Con);

                $accion = new Accion(
                    xaccountid: $ID_Usuario,
                    xFecha : $Fecha,
                    xDetalles: $Detalles,
                    xID_TipoAccion: $ID_TipoAccion	 
                );
                $accion->save();
                $Con->CloseConexion();
                $Mensaje = "El Barrio se registro Correctamente";
                header('Location: /barrio/nuevo?Mensaje=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function del_barrio_control($id_barrio)
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Barrio = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja un Barrio. Datos: Barrio: $ID_Barrio";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Consulta = "update barrios set estado = 0 where ID_Barrio = $ID_Barrio";
            if (!$Ret = mysqli_query($Con->Conexion,$Consulta)) {
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 0);		
            }
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if (!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)) {
                throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 1);
            }	
            $Con->CloseConexion();
            $Mensaje = "El barrio fue eliminado Correctamente";
            header('Location: /barrios?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function mod_barrio_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Barrio = $_REQUEST["ID"];
        $barrio_nombre = ucwords($_REQUEST["Barrio"]);

        $georeferencia_point = null;

        if (!empty($_REQUEST["lat"])) {
            $lat_point = $_REQUEST["lat"];
            $georeferencia_point = "POINT(" . $lat_point;

            if (!empty($_REQUEST["lon"])){
                $lon_point = $_REQUEST["lon"];
                $georeferencia_point .= "," . $lon_point . ")";
            } else {
                $georeferencia_point = null;
            }
        }

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        $Con = new Conexion();
        $Con->OpenConexion();

        try {
            $Con = new Conexion();
            $Con->OpenConexion();
            $existe = Barrio::existe_barrio(coneccion: $Con, name: $barrio_nombre);
            $id_barrio_control = Barrio::get_id_by_name(coneccion: $Con, name: $barrio_nombre);
            if ($existe > 0 && $id_barrio_control != $ID_Barrio) {
                $Con->CloseConexion();
                $Mensaje = "Ya existe un Barrio con ese Nombre";
                header('Location: /barrio/editar?ID=' . $ID_Barrio . '&MensajeError=' . $Mensaje);
            } else {
                $barrio = new Barrio(coneccion: $Con, id_barrio: $ID_Barrio);

                $barrio_viejo = $barrio->get_barrio();

                $barrio->set_barrio($barrio_nombre);
                $barrio->set_georeferencia($georeferencia_point);
                $barrio->update($Con);

                $Detalles = "El usuario con ID: $ID_Usuario ha modificado un Barrio. Datos: Dato Anterior: $barrio_viejo , Dato Nuevo: $barrio_nombre";
                $accion = new Accion(
                    xaccountid: $ID_Usuario,
                    xFecha : $Fecha,
                    xDetalles: $Detalles,
                    xID_TipoAccion: $ID_TipoAccion	 
                );
                $accion->save();
                
                $Con->CloseConexion();
                $Mensaje = "El Barrio se modificó Correctamente";
                header('Location: /barrio/editar?ID=' . $ID_Barrio . '&Mensaje=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function sol_unif_barrio()
    {
        $Fecha = Date("Y-m-d");
        $ID_Registro_1 = $_REQUEST["ID_Barrio_1"];
        $ID_Registro_2 = $_REQUEST["ID_Barrio_2"];
        $ID_Usuario = $_SESSION["Usuario"];
        $Estado = 1;
        $TipoUnif = 5;

        if ($ID_Registro_1 > 0 && $ID_Registro_2 > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Solicitud = new Solicitud_Unificacion(0,$Fecha,$ID_Registro_1,$ID_Registro_2,$ID_Usuario,$Estado,$TipoUnif);
            $Insert_Solicitud = "insert into solicitudes_unificacion(Fecha,ID_Registro_1,ID_Registro_2,ID_Usuario,Estado,ID_TipoUnif) values('{$Solicitud->getFecha()}',{$Solicitud->getID_Registro_1()},{$Solicitud->getID_Registro_2()},{$Solicitud->getID_Usuario()},{$Solicitud->getEstado()},{$Solicitud->getTipoUnif()})";
            $MensajeError = "No se pudo enviar la solicitud";

            mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);

            $Con->CloseConexion();
            $Mensaje = "La solicitud de unificación se envió a los administradores para ser confirmada.";
            header('Location: /barrio/unificar?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Barrio y Segundo Barrio";
            header('Location: /barrio/unificar?MensajeError=' . $MensajeError);
        }
        exit();
    }

    public function unif_barrios($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();

            include("./Views/view_unifbarrios.php");
        }
        exit();
    }

    public function unif_barrio_control()
    {
        $ID_Solicitud = $_REQUEST["ID_Solicitud"];
        $ID_Barrio_1 = $_REQUEST["ID_Barrio_1"];
        $ID_Barrio_2 = $_REQUEST["ID_Barrio_2"];

        if($ID_Barrio_1 > 0 && $ID_Barrio_2 > 0){
            $Con = new Conexion();
            $Con->OpenConexion();

            $ConsultarBarrios = "select * from persona where ID_Barrio = $ID_Barrio_2 and estado = 1";
            $MensajeErrorConsultarBarrios = "No se pudieron consultar los casos de igualdad en el Barrio 1";

            $EjecutarConsultarBarrios = mysqli_query($Con->Conexion, $ConsultarBarrios) or die($MensajeErrorConsultarBarrios);
            while($RetBarrios = mysqli_fetch_assoc($EjecutarConsultarBarrios)){
                $ID_PersonaBarrio = $RetBarrios["id_persona"];
                $CambiarBarrios = "update persona set ID_Barrio = $ID_Barrio_1 where id_persona = $ID_PersonaBarrio";
                $MensajeErrorCambiarBarrios = "No se pudieron cambiar los barrios";
                mysqli_query($Con->Conexion, $CambiarBarrios) or die($MensajeErrorCambiarBarrios);
            }

            $ConsultaBajaBarrio = "update barrios set estado = 0 where ID_Barrio = $ID_Barrio_2";
            $MensajeErrorBajaBarrio = "No se pudo dar de baja el Barrio";

            mysqli_query($Con->Conexion,$ConsultaBajaBarrio) or die($MensajeErrorBajaBarrio);

            $ConsultaSolicitud = "update solicitudes_unificacion set Estado = 0 where ID_Solicitud_Unificacion = $ID_Solicitud";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
                throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
            }

            $Con->CloseConexion();
            $Mensaje = "Los datos se unificaron Correctamente";
            header('Location: /home?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Barrio y Segundo Barrio";
            header('Location: /home?MensajeError=' . $MensajeError);
        }
        exit();
    }

    public function buscar_barrio()
    {

        $consultaBusqueda = $_REQUEST['valorBusqueda'];
        $id = $_REQUEST['id'];


        //Filtro anti-XSS
        $caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
        $caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
        $consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

        //Variable vacía (para evitar los E_NOTICE)
        $mensaje = "";


        //Comprueba si $consultaBusqueda está seteado
        if (isset($consultaBusqueda)) {

            $Con = new Conexion();
            $Con->OpenConexion();
            //Selecciona todo de la tabla mmv001 
            //donde el nombre sea igual a $consultaBusqueda, 
            //o el apellido sea igual a $consultaBusqueda, 
            //o $consultaBusqueda sea igual a nombre + (espacio) + apellido

            $consulta = mysqli_query($Con->Conexion, "SELECT ID_Barrio, Barrio FROM barrios WHERE Barrio LIKE '%$consultaBusqueda%' and estado = 1");


            //Obtiene la cantidad de filas que hay en la consulta
            $filas = mysqli_num_rows($consulta);

            //Si no existe ninguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
            if ($filas === 0) {
                $mensaje = "<p>No hay ningún registro con ese dato</p>";
            } else {
                //Si existe alguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
                //echo 'Resultados para <strong>'.$consultaBusqueda.'</strong>';

                $mensaje .= '<table class="table">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">ID</th>			      
                        <th scope="col">Barrio</th>			      			     
                        <th scope="col">Accion</th>	
                        </tr>
                    </thead>
                    <tbody>';

                //La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
                while($resultados = mysqli_fetch_array($consulta)) {
                    $ID_Barrio = $resultados["ID_Barrio"];			
                    $Barrio = $resultados['Barrio'];										

                    //Output
                    $mensaje .= '
                        <tr>
                        <th scope="row">' . $ID_Barrio . '</th>
                        <th scope="row">' . $Barrio . '</th>			      			      
                        <td><button type = "button" class = "btn btn-outline-success" onClick="seleccionBarrio_' . $id  . '(\''.$Barrio.'\','.$ID_Barrio.')" data-dismiss="modal">seleccionar</button></td>
                        </tr>';




                };//Fin while $resultados

                $mensaje .= '</tbody>
                    </table>';

            }; //Fin else $filas
            $Con->CloseConexion();

        };//Fin isset $consultaBusqueda

        echo $mensaje;
    }
}