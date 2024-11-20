<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Accion.php");

class Accion{
	// DECLARACION DE VARIABLES
	private $ID_Accion;
	private $accountid;
	private $Fecha;
	private $ip;
	private $Detalles;
	private $ID_TipoAccion;

	public function __construct(
		$xID_Accion = null,
		$xaccountid = null,
		$xFecha = null,
		$xip = null,
		$xDetalles = null,
		$xID_TipoAccion = null
		){
		$this->ID_Accion = $xID_Accion;
		$this->accountid = $xaccountid;
		$this->Fecha = $xFecha;
		$this->ip = $xip;
		$this->Detalles = $xDetalles;
		$this->ID_TipoAccion = $xID_TipoAccion;
	}
	public static function get_acciones(){
		$con = new Conexion();
		$con->OpenConexion();
		$consulta = "select * 
					from Acciones
					where accountid is not null";
		$rs = mysqli_query($con->Conexion,$consulta) or die("Problemas al consultar las acciones.");
		$lista_acciones = [];
		while ($ret = mysqli_fetch_assoc($rs)) {
			$row["accountid"] = ((!empty($ret["accountid"])) ? $ret["accountid"] : null);
			$row["Detalles"] = ((!empty($ret["Detalles"])) ? $ret["Detalles"] : null);
			$row["Fecha"] = ((!empty($ret["Fecha"])) ? $ret["Fecha"] : null);
			$row["ID_TipoAccion"] = (!empty($ret["ID_TipoAccion"])) ? $ret["ID_TipoAccion"] : null;
			$lista_acciones[] = $row;
		}
		return $lista_acciones;
	}

	public static function get_acciones_user_id($account_id){
		$con = new Conexion();
		$con->OpenConexion();
		$consulta = "select * 
					from Acciones 
					where accountid = '$account_id'";
		$rs = mysqli_query($con->Conexion,$consulta) or die("Problemas al consultar las acciones.");
		$lista_acciones = [];
		while ($ret = mysqli_fetch_assoc($rs)) {
			$row["accountid"] = ((!empty($ret["accountid"])) ? $ret["accountid"] : null);
			$row["Detalles"] = ((!empty($ret["Detalles"])) ? $ret["Detalles"] : null);
			$row["Fecha"] = ((!empty($ret["Fecha"])) ? $ret["Fecha"] : null);
			$row["ID_TipoAccion"] = (!empty($ret["ID_TipoAccion"])) ? $ret["ID_TipoAccion"] : null;
			$lista_acciones[] = $row;
		}
		return $lista_acciones;
	}

	public static function get_acciones_tipo($id_tipo_accion){
		$con = new Conexion();
		$con->OpenConexion();
		$consulta = "select * 
					from Acciones 
					where ID_TipoAccion = '$id_tipo_accion'
					and accountid is not null";
		$rs = mysqli_query($con->Conexion,$consulta) or die("Problemas al consultar las acciones.");
		$lista_acciones = [];
		while ($ret = mysqli_fetch_assoc($rs)) {
			$row["accountid"] = ((!empty($ret["accountid"])) ? $ret["accountid"] : null);
			$row["Detalles"] = ((!empty($ret["Detalles"])) ? $ret["Detalles"] : null);
			$row["Fecha"] = ((!empty($ret["Fecha"])) ? $ret["Fecha"] : null);
			$row["ID_TipoAccion"] = (!empty($ret["ID_TipoAccion"])) ? $ret["ID_TipoAccion"] : null;
			$lista_acciones[] = $row;
		}
		return $lista_acciones;
	}


	// METODOS SET
	public function setID_Accion($xID_Accion){
		$this->ID_Accion = $xID_Accion;
	}

	public function setaccountid($xaccountid){
		$this->accountid = $xaccountid;
	}

	public function setFecha($xFecha){
		$this->Fecha = $xFecha;
	}

	public function setip($xip){
		$this->ip = $xip;
	}

	public function setDetalles($xDetalles){
		$this->Detalles = $xDetalles;
	}

	public function setID_TipoAccion($xID_TipoAccion){
		$this->ID_TipoAccion = $xID_TipoAccion;
	}

	// METODOS GET
	public function getID_Accion(){
		return $this->ID_Accion;
	}

	public function getaccountid(){
		return $this->accountid;
	}

	public function getFecha(){
		return $this->Fecha;
	}

	public function getip(){
		return $this->ip;
	}

	public function getDetalles(){
		return $this->Detalles;
	}

	public function getID_TipoAccion(){
		return $this->ID_TipoAccion;
	}

	public function save() {
		$con = new Conexion();
		$con->OpenConexion();
		$consulta_accion = "insert into Acciones(accountid, 
												Fecha, 
												Detalles, 
												ID_TipoAccion) 
									values(" . ((!$this->accountid) ? "null" : $this->accountid) . ",'" 
											. $this->Fecha . "','" 
											. $this->Detalles . "',
												1)";
		if(!$RetAccion = mysqli_query($con->Conexion,$consulta_accion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ". $consulta_accion, 3);
		}
	}

}
