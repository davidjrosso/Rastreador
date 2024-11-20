<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Controladores/Conexion.php");

class Responsable implements JsonSerializable
{
	//DECLARACION DE VARIABLES
	private $id_responsable;
	private $responsable;
	private $account_id;
	private $estado;
	private $coneccion_base;

	public function __construct(
		$coneccion_base=null,
		$id_responsable=null,
		$responsable=null,
		$account_id=null,
		$estado=null
	){
		$this->coneccion_base = $coneccion_base;
		if (!$id_responsable) {
			$this->estado = $estado;
			$this->account_id = $account_id;
			$this->responsable = $responsable;
		} else {
			$consultar = "select *
						  from responsable 
						  where id_resp = " . $id_responsable . " 
							and estado = 1";
			$ejecutar_consultar = mysqli_query(
				$this->coneccion_base, 
				$consultar) or die("Problemas al consultar filtro pesona");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$resp_id_responsable = $ret["id_resp"];
				$resp_responsable = $ret["responsable"];
				$resp_estado = $ret["estado"];
				$resp_account_id = $ret["accountid"];
	
				$this->id_responsable = $resp_id_responsable;
				$this->responsable = $resp_responsable;
				$this->estado = $resp_estado;
				$this->movimiento = $resp_account_id;
			}
		}
	}
	

	//METODOS SET
	public function set_id_responsable($id_responsable)
	{
		$this->id_responsable = $id_responsable;
	}

	public function set_responsable($responsable)
	{
		$this->responsable = $responsable;
	}

	public function set_estado($estado)
	{
		$this->estado = $estado;
	}

	public function set_account_id($account_id)
	{
		$this->account_id = $account_id;
	}

	public function set_connecion_base($conneccion_base)
	{
		$this->coneccion_base = $conneccion_base;
	}
	//METODOS GET
	
	public function get_id_responsable(){
		return $this->id_responsable;
	}

	public function get_estado()
	{
		return $this->estado;
	}

	public function get_responsable()
	{
		return $this->responsable;
	}

	public function get_account_id()
	{
		return $this->account_id;
	}

	public function jsonSerialize() 
	{
		return [
			'id_responsable' => $this->id_responsable,
			'responsable' => $this->responsable,
			'estado' => $this->estado,
			'account_id' => $this->account_id
		];
	}

	public function update()
	{
		$consulta = "update responsable 
					set responsable = " . ((!is_null($this->get_responsable())) ? "'" . $this->get_responsable() . "'" : "null") . ", 
						accountid = " . ((!is_null($this->get_account_id())) ? "'" . $this->get_account_id() . "'" : "null") . ", 
						estado = " . ((!is_null($this->get_estado())) ? "'" . $this->get_estado() . "'" : "null") . "
					where id_resp = " . $this->get_id_responsable();
		$mensaje_error = "No se pudo actualizar la Responsable";
		$ret = mysqli_query($this->coneccion_base, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function save()
	{
		$consulta = "INSERT INTO responsable ( 
										responsable, 
										accountid, 
										estado
					)
					VALUES ( " . ((!is_null($this->get_responsable())) ? "'" . $this->get_responsable() . "'" : "null") . ", 
							" . ((!is_null($this->get_account_id())) ? "'" . $this->get_account_id() . "'" : "null") . ", 
							" . ((!is_null($this->get_estado())) ? "'" . $this->get_estado() . "'" : "null") . "
					)";
		$mensaje_error = "No se pudo insertar el responsable";
		$ret = mysqli_query($this->coneccion_base, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}
}
