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

require('Controladores/Conexion.php');
header('Content-Type: text/html; charset=utf-8');

$consultaBusqueda = $_REQUEST['valorBusqueda'];

$json_string = file_get_contents('php://input');
$lista_motivo = json_decode($json_string, true);

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
			  	and estado = 1
			  ORDER BY tipo_motivo ASC, orden DESC";
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
		$valores_motivos = ($lista_motivo) ? array_values(array: $lista_motivo) : [];

		while($resultados = mysqli_fetch_array($consulta)) {
			$ID_Motivo = $resultados["id_motivo"];			
			$Motivo = $resultados['motivo'];
			$codigo = $resultados['codigo'];
			$mensaje .= '<tr>
							<th scope="row">' . $Motivo . '</th>
							<td>' . $codigo . '</td>';

			if (in_array($ID_Motivo, $valores_motivos)) {
				$mensaje .= '<td>
								<button type = "button" style=\'width:12ch\' class = "btn btn-outline-success" onClick="addMultipleMotivo(\'' . $Motivo . '\',' . $ID_Motivo . ', this)">
									&#10003
								</button>
							</td>
						</tr>';
			} else {
				$mensaje .= '<td>
								<button type = "button" class = "btn btn-outline-success" onClick="addMultipleMotivo(\'' . $Motivo . '\',' . $ID_Motivo . ', this)">
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
