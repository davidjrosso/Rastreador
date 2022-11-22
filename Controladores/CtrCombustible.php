<?php  
require_once 'Conexion.php';

class CtrCombustible{
	//Instanciando la Conexion
	public function __construct(){

	}

//Metodos Get Agentes
	public function getCombustibles(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select IdConbus,TipoConbu,precioxL from combus";
		$MessageError = "Problemas al intentar mostrar Proveedores";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Tipo</th><th>PrecioxL</th></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["IdConbus"]."</td><td>".$Ret["TipoConbu"]."</td><td>".$Ret["precioxL"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getCombustiblesxTipo($_xTipoCombustible){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select IdConbus,TipoConbu,precioxL from combus where TipoConbu like '%$_xTipoCombustible%'";
		$MessageError = "Problemas al intentar mostrar Combustibles";
		$Table = "<table class='table table-dark'><thead><tr><th>ID</th><th>Tipo</th><th>PrecioxL</th></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["IdConbus"]."</td><td>".$Ret["TipoConbu"]."</td><td>".$Ret["precioxL"]."</td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

}
?>