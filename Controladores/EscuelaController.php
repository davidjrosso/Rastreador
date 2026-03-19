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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Escuela.php");



class EscuelaController 
{

    public function listado_escuelas($mensaje = null)
    {
        header('Content-Type: text/html; charset=utf-8');
        if (!isset($_SESSION["Usuario"])) {
           include("./Views/Error_Session.php");

        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();
            $DTGeneral = new CtrGeneral();
            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_escuelas.php");
        }
        exit();
    }

    public function mod_escuela($id_escuela)
    {
        header('Content-Type: text/html; charset=utf-8');
        if (!isset($_SESSION["Usuario"])) {

            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            include("./Views/view_modescuelas.php");
        }
        exit();
    }

    public function new_escuela_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $Codigo = $_REQUEST["Codigo"];
        $Escuela = ucfirst($_REQUEST["Escuela"]);
        $CUE = $_REQUEST["CUE"];
        $Localidad = ucwords($_REQUEST["Localidad"]);
        $Departamento = ucwords($_REQUEST["Departamento"]);
        $Directora = ucwords($_REQUEST["Directora"]);
        $Telefono = $_REQUEST["Telefono"];
        $Mail = ucfirst($_REQUEST["Mail"]);
        $Estado = 1;

        if($_REQUEST["ID_Nivel"] > 0){
            $ID_Nivel = $_REQUEST["ID_Nivel"];
        }else{
            $ID_Nivel = 'null';
        }

        if(empty($CUE)){
            $CUE = 'null';
        }

        if(empty($Localidad)){
            $Localidad = 'null';
        }

        if(empty($Departamento)){
            $Departamento = 'null';
        }

        if(empty($Directora)){
            $Directora = 'null';
        }

        if(empty($Telefono)){
            $Telefono = 'null';
        }

        if(empty($Mail)){
            $Mail = 'null';
        }

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 1;
        $Detalles = "El usuario con ID: $ID_Usuario ha registrado una nueva Escuela. Datos: Codigo: $Codigo - Escuela: $Escuela - CUE: $CUE - Localidad: $Localidad - Departamento: $Departamento - Directora: $Directora - Telefono: $Telefono - Mail: $Mail - Nivel: $ID_Nivel";

        $Con = new Conexion();
        $Con->OpenConexion();

        try {
            $ConsultarResponsablesIguales = "select * from escuelas where Escuela = '$Escuela' and Estado = 1";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultarResponsablesIguales)){
                throw new Exception("Error al consultar registros. Consulta: ".$ConsultarResponsablesIguales, 0);		
            }
            $Resultado = mysqli_num_rows($Ret);
            if($Resultado > 0){
                $Con->CloseConexion();
                $Mensaje = "Ya existe una Escuela con ese Nombre";
                header('Location: ../view_newescuelas.php?MensajeError='.$Mensaje);
            }else{
                $InstEscuela = new Escuela(0,$Codigo,$Escuela,$CUE,$Localidad,$Departamento,$Directora,$Telefono,$Mail,$ID_Nivel,$Estado);
                $Consulta = "insert into escuelas(Codigo,Escuela,CUE,Localidad,Departamento,Directora,Telefono,Mail,ID_Nivel,Estado) values('{$InstEscuela->getCodigo()}','{$InstEscuela->getEscuela()}','{$InstEscuela->getCUE()}','{$InstEscuela->getLocalidad()}','{$InstEscuela->getDepartamento()}','{$InstEscuela->getDirectora()}','{$InstEscuela->getTelefono()}','{$InstEscuela->getMail()}',{$InstEscuela->getID_Nivel()},{$InstEscuela->getEstado()})";
                if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                    throw new Exception("Error al intentar registrar. Consulta: ".$Consulta, 1);
                }	
                $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
                if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                    throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 2);
                }
                $Con->CloseConexion();
                $Mensaje = "La Escuela se registro Correctamente";
                header('Location: ../view_newescuelas.php?Mensaje='.$Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }        
    }
    public function datos_escuela($id_escuela)
    {
        header('Content-Type: text/html; charset=utf-8');
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            include("./Views/view_verescuelas.php");
        }
        exit();
    }

    public function escuelas_lista()
    {
        $ID_Nivel = $_REQUEST["q"];

        $Element = new Elements();

        switch ($ID_Nivel) {
            case '1':
                echo $Element->CBEscuelas(1);
                break;
            case '2':
                echo $Element->CBEscuelas(2);
                break;
            case '3':
                echo $Element->CBEscuelas(3);
                break;
            case '4':
                echo $Element->CBEscuelas(4);
                break;	
            default:
                echo $Element->CBEscuelas(0);
                break;
        }
    }

    public function del_escuela_control($id_escuela)
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Escuela = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Escuela. Datos: Escuela: $ID_Escuela";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();
            $escuela = new Escuela(coneccion_base: $Con, xEscuela: $ID_Escuela);
            $Consulta = "update escuelas set Estado = 0 where ID_Escuela = $ID_Escuela";
            if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 0);		
            }
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 1);
            }	
            $Con->CloseConexion();
            $Mensaje = "La Escuela fue eliminada Correctamente";
            header('Location: /escuelas?Mensaje='.$Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function mod_escuela_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Escuela = $_REQUEST["ID"];
        $Codigo = $_REQUEST["Codigo"];
        $Escuela = ucfirst($_REQUEST["Escuela"]);
        $CUE = $_REQUEST["CUE"];
        $Localidad = ucwords($_REQUEST["Localidad"]);
        $Departamento = ucwords($_REQUEST["Departamento"]);
        $Directora = ucwords($_REQUEST["Directora"]);
        $Telefono = $_REQUEST["Telefono"];
        $Mail = ucfirst($_REQUEST["Mail"]);
        $ID_Nivel = $_REQUEST["ID_Nivel"];
        $Estado = 1;

        $Con = new Conexion();
        $Con->OpenConexion();
        $Escuela_Nueva = new Escuela(
                                     $Con,
                                     $ID_Escuela,
                                     $Codigo,
                                     $Escuela,
                                     $CUE,
                                     $Localidad,
                                     $Departamento,
                                     $Directora,
                                     $Telefono,
                                     $Mail,
                                     $ID_Nivel,
                                     $Estado);

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        try {
            $ConsultarRegistrosIguales = "select * from escuelas where Escuela = '$Escuela' and ID_Escuela != $ID_Escuela and estado = 1";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
                throw new Exception("Error al consultar registros. Consulta: ".$ConsultarRegistrosIguales, 0);		
            }
            $Resultado = mysqli_num_rows($Ret);
            if ($Resultado > 0) {
                $Con->CloseConexion();
                $Mensaje = "Ya existe una Escuela con ese Nombre";
                header('Location: /escuelas?ID='.$ID_Escuela.'&MensajeError='.$Mensaje);
            } else {
                $ConsultarDatosViejos = "select * from escuelas where ID_Escuela = $ID_Escuela and Estado = 1";
                $ErrorDatosViejos = "No se pudieron consultar los datos";
                if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
                    throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
                }		
                $TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
                $ID_Escuela = $TomarDatosViejos["ID_Escuela"];
                $Codigo = $TomarDatosViejos["Codigo"];
                $Escuela = $TomarDatosViejos["Escuela"];
                $CUE = $TomarDatosViejos["CUE"];
                $Localidad = $TomarDatosViejos["Localidad"];
                $Departamento = $TomarDatosViejos["Departamento"];
                $Directora = $TomarDatosViejos["Directora"];
                $Telefono = $TomarDatosViejos["Telefono"];
                $Mail = $TomarDatosViejos["Mail"];
                $ID_Nivel = $TomarDatosViejos["ID_Nivel"];
                $Estado = $TomarDatosViejos["Estado"];
                
                $Escuela_Vieja = new Escuela($ID_Escuela,$Codigo,$Escuela,$CUE,$Localidad,$Departamento,$Directora,$Telefono,$Mail,$ID_Nivel,$Estado);
                

                $Consulta = "update escuelas set Codigo = '{$Escuela_Nueva->getCodigo()}', Escuela = '{$Escuela_Nueva->getEscuela()}', CUE = '{$Escuela_Nueva->getCUE()}', Localidad = '{$Escuela_Nueva->getLocalidad()}', Departamento = '{$Escuela_Nueva->getDepartamento()}', Directora = '{$Escuela_Nueva->getDirectora()}', Telefono = '{$Escuela_Nueva->getTelefono()}', Mail = '{$Escuela_Nueva->getMail()}', ID_Nivel = {$Escuela_Nueva->getID_Nivel()} where ID_Escuela = {$Escuela_Nueva->getID_Escuela()} and Estado = 1";
                if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                    throw new Exception("Error al intentar registrar. Consulta: " . $ConsultarRegistrosIguales, 2);
                }

                $Detalles = "El usuario con ID: $ID_Usuario ha modificado una Escuela. Datos: Dato Anterior: {$Escuela_Vieja->getCodigo()} , Dato Nuevo: {$Escuela_Nueva->getCodigo()} - Dato Anterior: {$Escuela_Vieja->getEscuela()} , Dato Nuevo: {$Escuela_Nueva->getEscuela()} - Dato Anterior: {$Escuela_Vieja->getCUE()} , Dato Nuevo: {$Escuela_Nueva->getCUE()} - Dato Anterior: {$Escuela_Vieja->getLocalidad()} , Dato Nuevo: {$Escuela_Nueva->getLocalidad()} - Dato Anterior: {$Escuela_Vieja->getDepartamento()} , Dato Nuevo: {$Escuela_Nueva->getDepartamento()} - Dato Anterior: {$Escuela_Vieja->getDirectora()} , Dato Nuevo: {$Escuela_Nueva->getDirectora()} - Dato Anterior: {$Escuela_Vieja->getTelefono()} , Dato Nuevo: {$Escuela_Nueva->getTelefono()} - Dato Anterior: {$Escuela_Vieja->getMail()} , Dato Nuevo: {$Escuela_Nueva->getMail()} - Dato Anterior: {$Escuela_Vieja->getID_Nivel()} , Dato Nuevo: {$Escuela_Nueva->getID_Nivel()}.";
                $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
                if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                    throw new Exception("Error al intentar registrar Accion. Consulta: " . $ConsultaAccion, 3);
                }
                $Con->CloseConexion();
                $Mensaje = "La Escuela se modificó Correctamente";
                header('Location: /escuela/editar?ID=' . $ID_Escuela . '&Mensaje=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        exit();
    }

    public function unif_escuelas($mensaje = null)
    {
        header('Content-Type: text/html; charset=utf-8');
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();

            $Element = new Elements();
            include("./Views/view_unifescuelas.php");
        }
        exit();
    }

    public function unif_escuela_control()
    {
        $ID_Solicitud = $_REQUEST["ID_Solicitud"];
        $ID_Escuela_1 = $_REQUEST["ID_Escuela_1"];
        $ID_Escuela_2 = $_REQUEST["ID_Escuela_2"];

        if ($ID_Escuela_1 > 0 && $ID_Escuela_2 > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $ConsultarEscuelas = "select * from persona where ID_Escuela = $ID_Escuela_2 and estado = 1";
            $MensajeErrorConsultarEscuelas = "No se pudieron consultar los casos de igualdad en la Escuela 1";

            $EjecutarConsultarEscuelas = mysqli_query($Con->Conexion, $ConsultarEscuelas) or die($MensajeErrorConsultarEscuelas);
            while ($RetEscuelas = mysqli_fetch_assoc($EjecutarConsultarEscuelas)) {
                $ID_PersonaEscuela = $RetEscuelas["id_persona"];
                $CambiarEscuelas = "update persona set ID_Escuela = $ID_Escuela_1 where id_persona = $ID_PersonaEscuela";
                $MensajeErrorCambiarEscuelas = "No se pudieron cambiar las Escuelas";
                mysqli_query($Con->Conexion, $CambiarEscuelas) or die($MensajeErrorCambiarEscuelas);
            }

            $escuela_2 = new Escuela(coneccion_base: $Con, xID_Escuela: $ID_Escuela_2);
            $escuela_2->delete();

            $Con->CloseConexion();
            $Mensaje = "Los datos se unificaron Correctamente";
            header('Location: /home?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Escuela y Segunda Escuela";
            header('Location: /home?MensajeError=' . $MensajeError);
        }
        
    }

    public function unif_escuela_lista()
    {
        header('Content-Type: text/html; charset=utf-8');

        $consultaBusqueda = $_REQUEST['valorBusqueda'];
        $id = $_REQUEST["ID"];
        //Filtro anti-XSS
        $caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
        $caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
        $consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

        $mensaje = "";

        if (isset($consultaBusqueda)) {

            $Con = new Conexion();
            $Con->OpenConexion();

            $consulta = mysqli_query($Con->Conexion, "SELECT E.ID_Escuela, E.Codigo, E.Escuela, N.Nivel, E.Localidad FROM escuelas E, nivel_escuelas N WHERE E.ID_Nivel = N.ID_Nivel and E.Escuela LIKE '%$consultaBusqueda%' and E.Estado = 1");

            $filas = mysqli_num_rows($consulta);

            if ($filas === 0) {
                $mensaje = "<p>No hay ningún registro con ese dato</p>";
            } else {

                $mensaje .= '<table class="table">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">Codigo</th>			      
                        <th scope="col">Escuela</th>
                        <th scope="col">Nivel</th>			      
                        <th scope="col">Localidad</th>			      
                        <th scope="col">Accion</th>	
                        </tr>
                    </thead>
                    <tbody>';

                while($resultados = mysqli_fetch_array($consulta)) {
                    $ID_Escuela = $resultados["ID_Escuela"];			
                    $Codigo = $resultados['Codigo'];
                    $Escuela = $resultados['Escuela'];
                    $Nivel = $resultados['Nivel'];
                    $Localidad = $resultados['Localidad'];							

                    $mensaje .= '
                        <tr>
                        <th scope="row">' . $Codigo . '</th>
                        <th scope="row">' . $Escuela . '</th>
                        <th scope="row">' . $Nivel . '</th>
                        <th scope="row">' . $Localidad . '</th>			      	
                        <td><button type = "button" class = "btn btn-outline-success" onClick="seleccionEscuela(' . $id . ',\'' . $Escuela . '\',' . $ID_Escuela . ')" data-dismiss="modal">seleccionar</button></td>
                        </tr>';
                };//Fin while $resultados

                $mensaje .= '</tbody>
                    </table>';

            }; //Fin else $filas
            $Con->CloseConexion();

        };//Fin isset $consultaBusqueda
        echo $mensaje;
    }

    public function sol_unif_escuela_control()
    {
        $Fecha = Date("Y-m-d");
        $ID_Registro_1 = $_REQUEST["ID_Escuela_1"];
        $ID_Registro_2 = $_REQUEST["ID_Escuela_2"];
        $ID_Usuario = $_SESSION["Usuario"];
        $Estado = 1;
        $TipoUnif = 4;

        if ($ID_Registro_1 > 0 && $ID_Registro_2 > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Solicitud = new Solicitud_Unificacion(
                                                   coneccion: $Con,
                                                   xFecha: $Fecha,
                                                   xID_Registro_1: $ID_Registro_1,
                                                   xID_Registro_2: $ID_Registro_2,
                                                   xID_Usuario: $ID_Usuario,
                                                   xEstado: $Estado,
                                                   xTipoUnif: $TipoUnif);
            $Solicitud->save();

            $Con->CloseConexion();
            $Mensaje = "La solicitud de unificación se envió a los administradores para ser confirmada.";
            header('Location: /escuelas?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar Primer Centro y Segundo Centro";
            header('Location: /escuelas?MensajeError=' . $MensajeError);
        }
    }

}