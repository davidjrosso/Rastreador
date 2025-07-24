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
header('Content-Type: text/html; charset=utf-8');

$consultaBusqueda = $_REQUEST['valorBusqueda'];
$responsable_nro = $_REQUEST['idResponsable'];

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
