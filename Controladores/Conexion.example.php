<?php  
class Conexion{
	public $Conexion;
	public $ResultSet;

	public function OpenConexion(){
		$Host = "localhost";
		$DataBase = "basededatos";
		$User = "usuariobasededatos";
		$Pass = "laclavedelusuariodelabasededatos";
		$MessageError = "Problemas en la Conexion";

		$this->Conexion = mysqli_connect($Host,$User,$Pass,$DataBase) or die($MessageError);
		$this->Conexion->set_charset('utf8');
	}

	public function CloseConexion(){
		mysqli_close($this->Conexion);
	}

	public function getCon(){
		return $this->Conexion;
	}

}
?>
