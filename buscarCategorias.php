<?php
//Fin isset $consultaBusqueda
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

require('Controladores/Conexion.php');
header('Content-Type: text/html; charset=utf-8');

//Variable de búsqueda
$consultaBusqueda = $_REQUEST['valorBusqueda'];

$json_string = file_get_contents('php://input');
$lista_categoria = json_decode($json_string, true);

//Filtro anti-XSS
$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
$caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

$mensaje = "";

if (isset($consultaBusqueda)) {

	$Con = new Conexion();
	$Con->OpenConexion();

	$consulta = mysqli_query($Con->Conexion, "SELECT * FROM categoria WHERE categoria LIKE '%$consultaBusqueda%' and estado = 1");

	$filas = mysqli_num_rows($consulta);

	if ($filas === 0) {
		$mensaje = "<p>No hay ningún registro con ese dato</p>";
	} else {
		$mensaje .= '<table class="table">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col">Categoría</th>
			      <th scope="col">Cod_Categoria</th>	
			      <th scope="col">Acción</th>	
			    </tr>
			  </thead>
			  <tbody>';

		$valores_categorias = ($lista_categoria) ? array_values(array: $lista_categoria) : [];

		while($resultados = mysqli_fetch_array($consulta)) {
			$ID_Categoria = $resultados["id_categoria"];			
			$Categoria = $resultados['categoria'];
			$Cod_Categoria = $resultados['cod_categoria'];
			$mensaje .= '<tr>
							<th scope="row">' . $Categoria . '</th>
							<td>' . $Cod_Categoria . '</td>';

			if (in_array($ID_Categoria, $valores_categorias)) {
				$mensaje .= '<td>
								<button type = "button" style=\'width:12ch\' class = "btn btn-outline-success" onClick="addMultipleCategoria(\'' . $Categoria . '\',' . $ID_Categoria . ', this)">
									&#10003
								</button>
							</td>
						</tr>';
			} else {
				$mensaje .= '<td>
								<button type = "button" class = "btn btn-outline-success" onClick="addMultipleCategoria(\'' . $Categoria . '\',' . $ID_Categoria . ', this)">
									seleccionar
								</button>
							</td>
						</tr>';
			}
		};

		$mensaje .= '</tbody>
			</table>';

	};
	$Con->CloseConexion();

};

echo $mensaje;
?>