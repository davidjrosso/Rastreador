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
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Categoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/CategoriaRol.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_EliminarCategoria.php");


class CalleController 
{

    public function listado_calles($mensaje = null)
    {
        header('Content-Type: text/html; charset=utf-8');
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_calles.php");
        }
        exit();
    }

    public function mod_calle($id_calle)
    {
        header('Content-Type: text/html; charset=utf-8');
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_modcalles.php");
        }
        exit();
    }

    public function buscar_calle()
    {
        $consultaBusqueda = $_REQUEST['valorBusqueda'];

        //Filtro anti-XSS
        $caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
        $caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
        $consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);
        $consultaBusqueda = strtolower($consultaBusqueda);

        //Variable vacía (para evitar los E_NOTICE)
        $mensaje = "";


        if (isset($consultaBusqueda)) {

            $Con = new Conexion();
            $Con->OpenConexion();

            $consultaCalles = "SELECT id_calle, codigo_calle, calle_nombre
                            FROM calle	
                            WHERE estado = 1
                                and ((LOWER(calle_nombre) REGEXP '[a-z]* ".$consultaBusqueda."[a-z]*')
                                    or (LOWER(calle_nombre) REGEXP '^".$consultaBusqueda."[a-z]*'))
                            order by calle_nombre ASC";
            $consulta = mysqli_query($Con->Conexion, $consultaCalles);

            $filas = mysqli_num_rows($consulta);

            if ($filas === 0) {
                $mensaje = "<p>No hay ningún registro con ese nombre, documento o legajo</p>";
            } else {
                //Si existe alguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
                //echo 'Resultados para <strong>'.$consultaBusqueda.'</strong>';

                $mensaje .= '<table class="table">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">Codigo</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Accion</th>
                        </tr>
                    </thead>
                    <tbody>';

                while($resultados = mysqli_fetch_array($consulta)) {
                    $ID_Calle = $resultados["id_calle"];
                    $Codigo = $resultados["codigo_calle"];
                    $Nombre = $resultados['calle_nombre'];
                    //Output
                    //$fragmentos = explode(" ",$Nombre);
                    //preg_grep();
                    //if($Nombre){

                    //}
                    $mensaje .= '
                        <tr>
                        <th scope="row">'.$Codigo.'</th>
                        <td>'.$Nombre.'</td>			
                        <td><button type = "button" class = "btn btn-outline-success" onClick="seleccionCalle(\''.$Nombre.'\','.$ID_Calle.')" data-dismiss="modal">seleccionar</button></td>
                        </tr>';
                };

                $mensaje .= '</tbody>
                    </table>';

            };
            $Con->CloseConexion();

        };

        echo $mensaje;
    }

    public function del_calle_control($id_calle)
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Calle = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja un Calle. Datos: Calle: $ID_Calle";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Consulta = "update calle set estado = 0 where ID_Calle = $ID_Calle";
            if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 0);		
            }
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 1);
            }
            $Con->CloseConexion();
            $Mensaje = "La Calle fue eliminada Correctamente";
            header('Location: /calles?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function mod_calle_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Calle = $_REQUEST["ID"];
        $Calle = ucwords($_REQUEST["Calle"]);

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        $Con = new Conexion();
        $Con->OpenConexion();

        try {
            $ConsultarRegistrosIguales = "select * from calle where calle_nombre = '$Calle' and ID_Calle != $ID_Calle and estado = 1";
            if (!$Ret = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)) {
                throw new Exception("Error al consultar registros. Consulta: " . $ConsultarRegistrosIguales, 0);		
            }
            $Resultado = mysqli_num_rows($Ret);
            if ($Resultado > 0) {
                $Con->CloseConexion();
                $Mensaje = "Ya existe una Calle con ese Nombre";
                header('Location: /calles?ID='. $ID_Calle . '&MensajeError=' . $Mensaje);
            } else {
                $ConsultarDatosViejos = "select * from calle where ID_calle = $ID_Calle and estado = 1";
                $ErrorDatosViejos = "No se pudieron consultar los datos";
                if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
                    throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
                }		
                $TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
                $CalleViejo = $TomarDatosViejos["Calle"];
                

                $Consulta = "update calle set calle_nombre = '$Calle' where ID_Calle = $ID_Calle and estado = 1";
                if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                    throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarRegistrosIguales, 2);
                }

                $Detalles = "El usuario con ID: $ID_Usuario ha modificado un Calle. Datos: Dato Anterior: $CalleViejo , Dato Nuevo: $Calle";
                $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
                if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                    throw new Exception("Error al intentar registrar Accion. Consulta: " . $ConsultaAccion, 3);
                }
                $Con->CloseConexion();
                $Mensaje = "La Calle se modificó Correctamente";
                header('Location: /calle/editar?ID=' . $ID_Calle . '&Mensaje=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function unif_calle($mensaje = null)
    {
        header('Content-Type: text/html; charset=utf-8');
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_unifdirecciones.php");
        }
        exit();
    }

    public function unif_calle_control()
    {
        $ArrPersonas = $_REQUEST["ArrPersonas"];
        $Calle = ucwords($_REQUEST["Calle"]);

        $ArrPersonas = explode(",", $ArrPersonas);

        if ($ArrPersonas[0] === '0') {
            $MensajeError = "Debe seleccionar los datos a modificar";
            header('Location: /calles?MensajeError='.$MensajeError);
        } else {
            $Con = new Conexion();
            $Con->OpenConexion();

            foreach ($ArrPersonas as $value) {
                $ConsultarDireccion = "select domicilio from persona where id_persona = $value";
                $MensajeErrorConsultar = "No se pudo consultar la direccion de la persona";

                $EjecutarConsultarDireccion = mysqli_query($Con->Conexion,$ConsultarDireccion) or die($MensajeErrorConsultar);
                $RetConsultarDireccion = mysqli_fetch_assoc($EjecutarConsultarDireccion);
                $DomActual = $RetConsultarDireccion["domicilio"];

                //$DomActual = preg_replace('/[0-9]+/','', $DomActual);
                $LongString = strlen($DomActual); 
                $StringDelimitado = chunk_split($DomActual,$LongString - 4,"-");
                $PartesDireccion = explode("-", $StringDelimitado);
                $DomActual = $PartesDireccion[0];
                
                if ($DomActual == "") {
                    $ModificarDireccion = "update persona set domicilio = '$Calle' where id_persona = $value";
                } else {
                    $ModificarDireccion = "update persona set domicilio = REPLACE(domicilio,'$DomActual','$Calle ') where id_persona = $value";	
                }		
                
                $MensajeErrorModificar = "No se pudieron modificar las direcciones solicitadas";
                
                mysqli_query($Con->Conexion,$ModificarDireccion) or die($MensajeErrorModificar);
            }

            $Con->CloseConexion();
            $Mensaje = "Las direcciones se modificaron Correctamente";
            header('Location: /calles?Mensaje=' . $Mensaje);
            exit();
        }
    }

    public function buscar_unif_direcciones($valor)
    {
        header('Content-Type: text/html; charset=utf-8');
        //Variable de búsqueda
        $consultaBusqueda = $valor;

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

            $consulta = mysqli_query($Con->Conexion, "SELECT id_persona, apellido, nombre, domicilio FROM persona WHERE domicilio LIKE '%$consultaBusqueda%' and estado = 1 order by apellido ASC, nombre ASC, domicilio ASC");


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
                        <th scope="col">Persona</th>	
                        <th scope="col">Domicilio</th>			      
                        <th scope="col">Accion</th>	
                        </tr>
                    </thead>
                    <tbody>';

                //La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
                while($resultados = mysqli_fetch_array($consulta)) {
                    $ID_Persona = $resultados["id_persona"];
                    $NombrePersona = $resultados["apellido"].", ".$resultados["nombre"];
                    $Domicilio = $resultados["domicilio"];

                    //Output
                    $mensaje .= '
                        <tr>
                        <th scope="row">' . $NombrePersona . '</th>
                        <th scope="row">' . $Domicilio . '</th>
                        <td><button type = "button" class = "btn btn-outline-success" onClick="seleccionDireccion(\''.$ID_Persona.'\',this)">seleccionar</button></td>
                        </tr>';

                };//Fin while $resultados

                $mensaje .= '</tbody>
                    </table>';

            }; //Fin else $filas
            $Con->CloseConexion();

        };
        echo $mensaje;
    }
}