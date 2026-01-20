<?php
//Archivo de conexión a la base de datos
require('Controladores/Conexion.php');
header('Content-Type: text/html; charset=utf-8');

//Variable de búsqueda
$consultaBusqueda = $_REQUEST['valorBusqueda'];

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
	$caracater = str_contains($consultaBusqueda, ",");
	if(is_numeric($consultaBusqueda)) {
		if(strlen((string)$consultaBusqueda) >= 8){
			$consulta = mysqli_query(
							  $Con->Conexion, 
							  "SELECT p.id_persona, UPPER(p.apellido) AS apellido, 
						  				 CONCAT(UPPER(SUBSTRING(p.nombre,1,1)),LOWER(SUBSTRING(p.nombre,2))) as nombre,
							  			 p.documento, p.nro_carpeta, CONCAT(c.calle_nombre, ' ', p.nro) as domicilio 
								  	  FROM persona p RIGHT JOIN calle c ON (p.calle = c.id_calle) 
									  WHERE p.documento LIKE '%$consultaBusqueda%' 
									  	and estado = 1 
									  order by upper(p.apellido) ASC, upper(p.nombre) ASC, upper(p.documento) ASC"
									);
	    } else {
			$consulta = mysqli_query(
							  $Con->Conexion, 
							  "SELECT p.id_persona, UPPER(p.apellido) AS apellido, 
						  				 CONCAT(UPPER(SUBSTRING(p.nombre,1,1)),LOWER(SUBSTRING(p.nombre,2))) as nombre,
							  			 p.documento, p.nro_carpeta, CONCAT(c.calle_nombre, ' ', p.nro) as domicilio 
								  	  FROM persona p RIGHT JOIN calle c ON (p.calle = c.id_calle)
									  WHERE p.nro_legajo LIKE '%$consultaBusqueda%' 
										AND p.estado = 1 
									  ORDER BY upper(p.apellido) ASC, upper(p.nombre) ASC, upper(p.documento) ASC"
									);
		}
	} else {

		$query_filter = "";

		if ($caracater) {
			$consulta = preg_replace("~[ ]+~", " ", $consultaBusqueda);
			$elements = array_map("trim", explode(",", $consulta));
			$apellidos = false;
			$nombres = false;
			if ($elements[0]) $apellidos = preg_split("~[ ]+~", $elements[0]);
			if ($elements[1]) $nombres = preg_split("~[ ]+~", $elements[1]);

			$cant_apellido = ($apellidos) ? count($apellidos) : 0;
			$cant_nombres = ($nombres) ? count($nombres) : 0;
			$query_apellido = "(";

			if (!$apellidos) $apellidos = [];

			foreach ($apellidos as $key => $value) {
				$query_apellido .= "(TRIM(apellido) REGEXP '^$value')";
				if ($key < $cant_apellido - 1) $query_apellido .= " or ";
			}
			$query_apellido .= ")";

			$query_nombre = "(";

			if (!$nombres) $nombres = [];

			foreach ($nombres as $key => $value) {
				$query_nombre .= "(TRIM(nombre) REGEXP '^$value')";
				if ($key < $cant_nombres - 1) $query_nombre .= " or ";
			}
			$query_nombre .= ")";

			if (!$cant_apellido) $query_apellido = "";

			$query_filter .= $query_apellido;
			if ($cant_nombres > 0) {
				if ($cant_apellido) $query_filter .= " and ";
				$query_filter .= $query_nombre; 
			}

			if ($cant_apellido || $cant_nombres) {
				$query_filter = " and " . $query_filter;
			}

			if (!$cant_apellido && !$cant_nombres) $query_filter = "";

		} else {
			$query_filter = " and ((TRIM(apellido) REGEXP '^$consultaBusqueda' or TRIM(apellido) REGEXP '[ ]+$consultaBusqueda') 
								  	 or (TRIM(nombre) REGEXP '^$consultaBusqueda' or TRIM(nombre) REGEXP '[ ]+$consultaBusqueda'))";
		}

		$consulta = mysqli_query(
						  $Con->Conexion, 
						  "SELECT p.id_persona, UPPER(p.apellido) AS apellido, 
						  				 CONCAT(UPPER(SUBSTRING(p.nombre,1,1)),LOWER(SUBSTRING(p.nombre,2))) as nombre,
							  			 p.documento, p.nro_carpeta, CONCAT(c.calle_nombre, ' ', p.nro) as domicilio 
								  FROM persona p RIGHT JOIN calle c ON (p.calle = c.id_calle)
								  WHERE  p.estado = 1
								  		 $query_filter
								  ORDER BY upper(p.apellido) ASC, upper(p.nombre) ASC, upper(p.documento) ASC"
								);
	}

	//Obtiene la cantidad de filas que hay en la consulta
	$filas = mysqli_num_rows($consulta);

	//Si no existe ninguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
	if ($filas === 0) {
		$mensaje = "<p>No hay ningún registro con ese nombre, documento o legajo</p>";
	} else {
		//Si existe alguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
		//echo 'Resultados para <strong>'.$consultaBusqueda.'</strong>';

		$mensaje .= '<table class="table">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col">Nombre</th>
			      <th scope="col">DNI</th>
			      <th scope="col">Nro Carpeta</th>
			      <th scope="col">Domicilio</th>
			      <th scope="col">Accion</th>
			    </tr>
			  </thead>
			  <tbody>';

		//La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
		while($resultados = mysqli_fetch_array($consulta)) {
			$ID_Persona = $resultados["id_persona"];
			$Nombre = $resultados['apellido'].", ".$resultados['nombre'];
			$DNI = $resultados['documento'];
			$Nro_Carpeta = $resultados['nro_carpeta'];
			// $Nro_Legajo= $resultados['nro_legajo'];
			$Domicilio = $resultados['domicilio'];			

			//Output
			$mensaje .= '
			    <tr>
			      <th scope="row">'.$Nombre.'</th>
			      <td>'.$DNI.'</td>
			      <td>'.$Nro_Carpeta.'</td>				
			      <td>'.$Domicilio.'</td>
			      <td>
				  	<button type = "button" class = "btn btn-outline-success" 
							onClick="seleccionPersona(\''.$Nombre.'\','.$ID_Persona.')" data-dismiss="modal">
						seleccionar
					</button>
				  </td>
			    </tr>';

					//   <td>'.$Nro_Legajo.'</td>


		};//Fin while $resultados

		$mensaje .= '</tbody>
			</table>';

	}; //Fin else $filas
	$Con->CloseConexion();

};//Fin isset $consultaBusqueda
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

//Devolvemos el mensaje que tomará jQuery
echo $mensaje;
?>