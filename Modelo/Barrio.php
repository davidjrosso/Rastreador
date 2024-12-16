<?php
class Barrio 
{
	private $coneccion;
	private $id_barrio;
	private $barrio;
	private $estado;

	public function __construct(
								$coneccion=null,
								$id_barrio=null,
								$barrio=null,
								$estado=null
								)
	{
		$this->coneccion = $coneccion;
		$this->id_barrio = $id_barrio;
		$this->barrio = $barrio;
		$this->estado = $estado;
	}

	public static function get_id_by_name($coneccion, $name)
	{
		$consulta = "select ID_Barrio 
					from barrios
					where lower(Barrio) like lower('%" . $name . "%')";
		$rs = mysqli_query($coneccion->Conexion,
						   $consulta) or die("Problemas al consultar las acciones.");
		$ret = mysqli_fetch_assoc($rs);
		$id = (!empty($ret["ID_Barrio"])) ? $ret["ID_Barrio"] : null;
		return $id;
	}

	public function set_id_barrio($id_barrio)
	{
		$this->id_barrio = $id_barrio;
	}

	public function set_barrio($barrio)
	{
		$this->barrio = $barrio;
	}

	public function set_estado($estado)
	{
		$this->estado = $estado;
	}

	public function get_id_barrio()
	{
		return $this->id_barrio;
	}

	public function get_barrio()
	{
		return $this->barrio;
	}

	public function get_estado()
	{
		return $this->estado;
	}
}
