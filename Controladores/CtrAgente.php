<?php 

header('Content-Type: text/html; charset=UTF-8');
require_once 'Conexion.php';


class CtrAgente{
	//Instanciando la Conexion
	public function __construct(){

	}

//Metodos Get Agentes
	public function getAgentes(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select a.IDAgente,a.Apellido,a.Nombre,a.Leg,ar.Area from agen a,area ar where a.Area = ar.IdArea order by a.Apellido";
		$MessageError = "Problemas al intentar mostrar Agentes";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Apellido</th><th>Nombre</th><th>Legajo</th><th>Area</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["IDAgente"]."</td><td>".utf8_encode($Ret["Apellido"])."</td><td>".$Ret["Nombre"]."</td><td>".$Ret["Leg"]."</td><td>".$Ret["Area"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getAgentesxApellido($_xApellido){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select a.IDAgente,a.Apellido,a.Nombre,a.Leg,ar.Area from agen a,area ar where a.Area = ar.IdArea and a.Apellido like '%$_xApellido%'";
		$MessageError = "Problemas al intentar mostrar Agentes";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Apellido</th><th>Nombre</th><th>Legajo</th><th>Area</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["IDAgente"]."</td><td>".$Ret["Apellido"]."</td><td>".$Ret["Nombre"]."</td><td>".$Ret["Leg"]."</td><td>".$Ret["Area"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getAgentesxLegajo($_xLeg){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select a.IDAgente,a.Apellido,a.Nombre,a.Leg,ar.Area from agen a,area ar where a.Area = ar.IdArea and a.Leg = $_xLeg";
		$MessageError = "Problemas al intentar mostrar Agentes";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Apellido</th><th>Nombre</th><th>Legajo</th><th>Area</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["IDAgente"]."</td><td>".$Ret["Apellido"]."</td><td>".$Ret["Nombre"]."</td><td>".$Ret["Leg"]."</td><td>".$Ret["Area"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

}
?>