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

	$consulta = mysqli_query($Con->Conexion, "SELECT * FROM motivo WHERE motivo LIKE '%$consultaBusqueda%' and estado = 1");


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
			      <th scope="col">Motivo</th>
			      <th scope="col">Código</th>	
			      <th scope="col">Acción</th>	
			    </tr>
			  </thead>
			  <tbody>';

		//La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
		while($resultados = mysqli_fetch_array($consulta)) {
			$ID_Motivo = $resultados["id_motivo"];			
			$Motivo = $resultados['motivo'];
			$codigo = $resultados['codigo'];					

			//Output
			$mensaje .= '
			    <tr>
			      <th scope="row">'.$Motivo.'</th>
			      <td>'.$codigo.'</td>		
			      <td><button type = "button" class = "btn btn-outline-success" onClick="seleccionMotivo_3(\''.$Motivo.'\','.$ID_Motivo.')" data-dismiss="modal">seleccionar</button></td>
			    </tr>';




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