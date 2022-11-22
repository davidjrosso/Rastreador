<?php  
require_once 'Conexion.php';

class CtrAcciones{
//DECLARACION DE VARIABLES
private $Accion;

//METODOS SET
public function setAccion($xAccion){
	$this->Accion = $xAccion;
}

//METODOS GET
public function getAccion($xAccion){
	return $Accion;
}


//METODOS EN DB
public function InsertAccion(){
	try {
		$Con = new Conexion();
		$Con->OpenConexion();
		$ConsultaInsert = "insert into Acciones(accountid, Fecha, ip, Detalles, ID_TipoAccion) values(".$Accion->getaccountid().",'".$Accion->getFecha()."','".$Accion->getip()."','".$Accion->getDetalles()."',".$Accion->getID_TipoAccion().")";
		$MensajeErrorInsert = "No se pudieron insertar los datos correctamente";
		mysqli_query($Con->Conexion,$ConsultaInsert) or die($MensajeErrorInsert);	
		$Con->CloseConexion();
	} catch (Exception $e) {
		echo "Excepcion capturada : ".$e->getMessage();
	}
	
}

public function ModifyAccion($xID){
	try {
		$Con = new Conexion();
		$Con->OpenConexion();
		$ConsultaModify = "update set accountid = ".$Accion->getaccountid().", Fecha = '".$Accion->getFecha()."', ip = '".$Accion->getip()."', Detalles = '".$Accion->getDetalles()."', ID_TipoAccion = ".$Accion->getID_TipoAccion()." where ID_Accion = $xID";	
		$MensajeErrorModify = "No se pudieron modificar los datos correctamente";

		mysqli_query($Con->Conexion, $ConsultaModify) or die($MensajeErrorModify);
		$Con->CloseConexion();	
	} catch (Exception $e) {
		echo "Excepcion capturada : ".$e->getMessage();
	}
}


//METODO CONSTRUCTOR
public function __construct($xAccion){
	$this->Accion = $xAccion;
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