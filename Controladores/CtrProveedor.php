<?php  
require_once 'Conexion.php';

class CtrProveedor{
	//Instanciando la Conexion
	public function __construct(){

	}

//Metodos Get Agentes
	public function getProveedores(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select IdProv,NombProv from prov";
		$MessageError = "Problemas al intentar mostrar Proveedores";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Proveedor</th></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["IdProv"]."</td><td>".$Ret["NombProv"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getProveedoresxNombre($_xNombreProv){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select IdProv,NombProv from prov where NombProv like '%$_xNombreProv%'";
		$MessageError = "Problemas al intentar mostrar Proveedores";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Proveedor</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["IdProv"]."</td><td>".$Ret["NombProv"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

}

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
?>