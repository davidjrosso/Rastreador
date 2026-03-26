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

            $calle = new Calle(id_calle: $ID_Calle);
            $calle->delete();

            $accion = new Accion(
                xaccountid: $ID_Usuario,
                xFecha : $Fecha,
                xDetalles: $Detalles,
                xID_TipoAccion: $ID_TipoAccion	 
            );
            $accion->save();
 
            $Con->CloseConexion();
            $Mensaje = "La Calle fue eliminada Correctamente";
            header('Location: /calles?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
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
            if (Calle::existe_calle_con_id($Calle, $ID_Calle, $Con)) {
                $Con->CloseConexion();
                $Mensaje = "Ya existe una Calle con ese Nombre";
                header('Location: /calles?ID='. $ID_Calle . '&MensajeError=' . $Mensaje);
            } else {
                $calle_obj = new Calle(id_calle: $ID_Calle);
                $calle_obj->save();

                $CalleViejo = $calle_obj->get_calle_nombre();

                $Detalles = "El usuario con ID: $ID_Usuario ha modificado un Calle. Datos: Dato Anterior: $CalleViejo , Dato Nuevo: $Calle";
                $accion = new Accion(
                    xaccountid: $ID_Usuario,
                    xFecha : $Fecha,
                    xDetalles: $Detalles,
                    xID_TipoAccion: $ID_TipoAccion	 
                );
                $accion->save();
 
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
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();
            $DTGeneral = new CtrGeneral();
            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

           include("./Views/view_unifdirecciones.php");
        }
        exit();
    }

    public function unif_calle_control()
    {
        $ArrPersonas = $_REQUEST["ArrPersonas"];
        $Calle = ucwords($_REQUEST["Calle"]);

        $ArrPersonas = explode(",", $ArrPersonas);


        if (empty($ArrPersonas[0])) {
            $MensajeError = "Debe seleccionar los datos a modificar";
            header('Location: /persona/unif?MensajeError=' . $MensajeError);
        } else {
            $Con = new Conexion();
            $Con->OpenConexion();

            foreach ($ArrPersonas as $value) {
                $persona = new Persona(ID_Persona: $value);
                $DomActual = $persona->getDomicilio();

                //$DomActual = preg_replace('/[0-9]+/','', $DomActual);
                $LongString = strlen($DomActual); 
                $StringDelimitado = chunk_split($DomActual,$LongString - 4,"-");
                $PartesDireccion = explode("-", $StringDelimitado);
                $DomActual = $PartesDireccion[0];
                $persona->setDomicilio($Calle);
            }

            $Con->CloseConexion();
            $Mensaje = "Las direcciones se modificaron Correctamente";
            header('Location: /persona/unif?Mensaje=' . $Mensaje);
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
            $query = "SELECT id_persona, apellido, nombre, CONCAT(c.calle_nombre, ' ', s.nro) as direccion
                      FROM persona s INNER JOIN calle c ON (s.calle = c.id_calle)
                      WHERE calle_nombre LIKE '%$consultaBusqueda%' 
                        and s.estado = 1 
                        order by apellido ASC, nombre ASC, calle_nombre ASC";

            $consulta = mysqli_query($Con->Conexion, $query);


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
                    $Domicilio = $resultados["direccion"];

                    //Output
                    $mensaje .= '
                        <tr>
                        <th scope="row">' . $NombrePersona . '</th>
                        <th scope="row">' . $Domicilio . '</th>
                        <td><button type = "button" class = "btn btn-outline-success" onClick="seleccionDireccion(\'' . $ID_Persona . '\',this)">seleccionar</button></td>
                        </tr>';

                };//Fin while $resultados

                $mensaje .= '</tbody>
                    </table>';

            }; //Fin else $filas
            $Con->CloseConexion();

        };
        echo $mensaje;
    }

    public function new_calle_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $Calle = ucwords($_REQUEST["Calle"]);
        $Estado = 1;

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 1;
        $Detalles = "El usuario con ID: $ID_Usuario ha registrado un nuevo Calle. Datos: $Calle";

        $Con = new Conexion();
        $Con->OpenConexion();

        try {
            if (Calle::existe_calle_especif($Calle, $Con) > 0) {
                $Con->CloseConexion();
                $Mensaje = "Ya existe una Calle con ese Nombre";
                header('Location: /calle/nueva?MensajeError=' . $Mensaje);
            } else {
                $calle_obj = new Calle(
                                       calle_nombre: $Calle,
                                       calle_open: $Calle,
                                       estado: $Estado 
                                       );

                $calle_obj->save();

                $accion = new Accion(
                    xaccountid: $ID_Usuario,
                    xFecha : $Fecha,
                    xDetalles: $Detalles,
                    xID_TipoAccion: $ID_TipoAccion	 
                );
                $accion->save();
                
                $Mensaje = "La Calle se registro Correctamente";
                header('Location: /calle/nueva?Mensaje=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }        
    }
}