<?php  
require_once 'Conexion.php';

class CtrVehiculo{
	//Instanciando la Conexion
	public function __construct(){

	}

//Metodos Get Agentes
	public function getVehiculos(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select v.idVehi,v.Numero,v.Dominio,v.Detalle,v.Modelo,ar.Area from vehi v,area ar where v.area = ar.IdArea";
		$MessageError = "Problemas al intentar mostrar Vehiculos";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Numero</th><th>Dominio</th><th>Descripcion</th><th>Modelo</th><th>Area</th><th>Tipo</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["idVehi"]."</td><td>".$Ret["Numero"]."</td><td>".$Ret["Dominio"]."</td><td>".$Ret["Detalle"]."</td><td>".$Ret["Modelo"]."</td><td>".$Ret["Area"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getVehiculosxNumero($_xNumero){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select v.idVehi,v.Numero,v.Dominio,v.Detalle,v.Modelo,ar.Area from vehi v,area ar where v.area = ar.IdArea and v.Numero = $_xNumero";
		$MessageError = "Problemas al intentar mostrar Vehiculos";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Numero</th><th>Dominio</th><th>Descripcion</th><th>Modelo</th><th>Area</th><th>Tipo</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["idVehi"]."</td><td>".$Ret["Numero"]."</td><td>".$Ret["Dominio"]."</td><td>".$Ret["Detalle"]."</td><td>".$Ret["Modelo"]."</td><td>".$Ret["Area"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getVehiculosxDominio($_xDominio){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select v.idVehi,v.Numero,v.Dominio,v.Detalle,v.Modelo,ar.Area from vehi v,area ar where v.area = ar.IdArea and v.Dominio like '%$_xDominio%'";
		$MessageError = "Problemas al intentar mostrar Vehiculos";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Numero</th><th>Dominio</th><th>Descripcion</th><th>Modelo</th><th>Area</th><th>Tipo</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["idVehi"]."</td><td>".$Ret["Numero"]."</td><td>".$Ret["Dominio"]."</td><td>".$Ret["Detalle"]."</td><td>".$Ret["Modelo"]."</td><td>".$Ret["Area"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getVehiculosxArea($_xArea){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select v.idVehi,v.Numero,v.Dominio,v.Detalle,v.Modelo,ar.Area from vehi v,area ar where v.area = ar.IdArea and ar.Area like '%$_xArea%'";
		$MessageError = "Problemas al intentar mostrar Vehiculos";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Numero</th><th>Dominio</th><th>Descripcion</th><th>Modelo</th><th>Area</th><th>Tipo</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["idVehi"]."</td><td>".$Ret["Numero"]."</td><td>".$Ret["Dominio"]."</td><td>".$Ret["Detalle"]."</td><td>".$Ret["Modelo"]."</td><td>".$Ret["Area"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

}
?>