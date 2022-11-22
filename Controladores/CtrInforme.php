<?php  
require_once 'Conexion.php';

class CtrGeneral{
	//Instanciando la Conexion

//Metodos Get Agentes
	public function getInforme(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select G.Id,G.Fecha,P.NombProv,G.Vehiculo,A.Apellido,C.TipoConbu,G.Precio,G.NroRemito,G.NroCompromiso,G.LitrosCombustible from general G,prov P,combus C, agen A where G.Prov = P.IdProv and G.Combustible = C.IdConbus and G.Agente = A.IDAgente";
		$MessageError = "Problemas al intentar mostrar General";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Fecha</th><th>Prov</th><th>Vehiculo</th><th>Agente</th><th>Combustible</th><th>Precio</th><th>Remito</th><th>Compromiso</th><th>Litros</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Id"]."</td><td>".$Ret["Fecha"]."</td><td>".$Ret["NombProv"]."</td><td>".$Ret["Vehiculo"]."</td><td>".$Ret["Apellido"]."</td><td>".$Ret["TipoConbu"]."</td><td>".$Ret["Precio"]."</td><td>".$Ret["NroRemito"]."</td><td>".$Ret["NroCompromiso"]."</td><td>".$Ret["LitrosCombustible"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getInforme($_xFecha1,$_xFecha2){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select G.Id,G.Fecha,P.NombProv,G.Vehiculo,A.Apellido,C.TipoConbu,G.Precio,G.NroRemito,G.NroCompromiso,G.LitrosCombustible from general G,prov P,combus C, agen A where G.Prov = P.IdProv and G.Combustible = P.IdConbus and G.Agente = A.IDAgente and G.Fecha like '%$_xFecha%'";
		$MessageError = "Problemas al intentar mostrar General";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Fecha</th><th>Prov</th><th>Vehiculo</th><th>Agente</th><th>Combustible</th><th>Precio</th><th>Remito</th><th>Compromiso</th><th>Litros</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Id"]."</td><td>".$Ret["Fecha"]."</td><td>".$Ret["NombProv"]."</td><td>".$Ret["Vehiculo"]."</td><td>".$Ret["Agente"]."</td><td>".$Ret["TipoConbu"]."</td><td>".$Ret["Precio"]."</td><td>".$Ret["NroRemito"]."</td><td>".$Ret["NroCompromiso"]."</td><td>".$Ret["LitrosCombustible"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getGeneralxAgente($_xAgente){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select G.Id,G.Fecha,P.NombProv,G.Vehiculo,A.Apellido,C.TipoConbu,G.Precio,G.NroRemito,G.NroCompromiso,G.LitrosCombustible from general G,prov P,combus C, agen A where G.Prov = P.IdProv and G.Combustible = P.IdConbus and G.Agente = A.IDAgente and A.Apellido like '%$_xAgente%'";
		$MessageError = "Problemas al intentar mostrar General";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Fecha</th><th>Prov</th><th>Vehiculo</th><th>Agente</th><th>Combustible</th><th>Precio</th><th>Remito</th><th>Compromiso</th><th>Litros</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Id"]."</td><td>".$Ret["Fecha"]."</td><td>".$Ret["NombProv"]."</td><td>".$Ret["Vehiculo"]."</td><td>".$Ret["Agente"]."</td><td>".$Ret["TipoConbu"]."</td><td>".$Ret["Precio"]."</td><td>".$Ret["NroRemito"]."</td><td>".$Ret["NroCompromiso"]."</td><td>".$Ret["LitrosCombustible"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getGeneralxRemito($_xNroRemito){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select G.Id,G.Fecha,P.NombProv,G.Vehiculo,A.Apellido,C.TipoConbu,G.Precio,G.NroRemito,G.NroCompromiso,G.LitrosCombustible from general G,prov P,combus C, agen A where G.Prov = P.IdProv and G.Combustible = P.IdConbus and G.Agente = A.IDAgente and G.nroRemito = $_xNroRemito";
		$MessageError = "Problemas al intentar mostrar General";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Fecha</th><th>Prov</th><th>Vehiculo</th><th>Agente</th><th>Combustible</th><th>Precio</th><th>Remito</th><th>Compromiso</th><th>Litros</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Id"]."</td><td>".$Ret["Fecha"]."</td><td>".$Ret["NombProv"]."</td><td>".$Ret["Vehiculo"]."</td><td>".$Ret["Agente"]."</td><td>".$Ret["TipoConbu"]."</td><td>".$Ret["Precio"]."</td><td>".$Ret["NroRemito"]."</td><td>".$Ret["NroCompromiso"]."</td><td>".$Ret["LitrosCombustible"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

}
?>