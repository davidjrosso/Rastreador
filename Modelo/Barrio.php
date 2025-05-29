<?php
class Barrio 
{
	private $coneccion;
	private $id_barrio;
	private $barrio;
	private $georeferencia;
	private $estado;

	public function __construct(
								$coneccion=null,
								$id_barrio=null,
								$barrio=null,
								$georeferencia=null,
								$estado=null
								)
	{
		$this->coneccion = $coneccion;
		if (!$id_barrio) {
			$this->id_barrio = $id_barrio;
			$this->barrio = $barrio;
			$this->georeferencia = $georeferencia;
			$this->estado = ($estado) ? $estado : 1;
		} else {
			$consulta = "select * 
						 from barrios
						 where ID_Barrio = $id_barrio";
			$rs = mysqli_query($this->coneccion->Conexion,
							$consulta) or die("Problemas al consultar las acciones.");
			$ret = mysqli_fetch_assoc($rs);
			$this->id_barrio = (!empty($ret["ID_Barrio"])) ? $ret["ID_Barrio"] : null;
			$this->barrio = (!empty($ret["Barrio"])) ? $ret["Barrio"] : null;
			$this->georeferencia = (!empty($ret["georeferencia"])) ? $ret["georeferencia"] : null;
			$this->estado = (!empty($ret["estado"])) ? $ret["estado"] : 0;
		}
	}

	public static function existe_barrio($coneccion, $name)
	{
		$consulta = "select ID_Barrio 
					from barrios
					where lower(Barrio) like lower('%" . $name . "%')
					and estado = 1";
		$rs = mysqli_query($coneccion->Conexion,
						   $consulta) or die("Problemas al consultar las acciones.");
		$count_row = mysqli_num_rows($rs);
		return $count_row;
	}

	public static function get_id_by_name($coneccion, $name)
	{
		$consulta = "select ID_Barrio 
					from barrios
					where lower(Barrio) like lower('%" . $name . "%')
					and estado = 1";
		$rs = mysqli_query($coneccion->Conexion,
						   $consulta) or die("Problemas al consultar las acciones.");
		$ret = mysqli_fetch_assoc($rs);
		$id = (!empty($ret["ID_Barrio"])) ? $ret["ID_Barrio"] : null;
		return $id;
	}

	public static function get_id_by_subpalabra($coneccion, $name)
	{
		$consulta = "select ID_Barrio 
					from barrios
					where lower('" . $name ."') like lower(REPLACE(Barrio, ' ', '%'))
					and estado = 1";
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

	public function set_georeferencia($georeferencia)
	{
		$this->georeferencia = $georeferencia;
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

	public function get_georeferencia()
	{
		return $this->georeferencia;
	}

	public function get_estado()
	{
		return $this->estado;
	}

	public function update($coneccion) {
		$consulta = "update accounts 
					set Barrio = " . ((!is_null($this->get_barrio())) ? "'" . $this->get_barrio() . "'" : "null") . ", 
						georeferencia = " . ((!is_null($this->get_georeferencia())) ? $this->get_georeferencia() : "null") . ",
						estado = " . ((!is_null($this->get_estado())) ? $this->get_estado() : "null") . " 
					where ID_Barrio = " . $this->get_id_barrio();

		$mensaje_error_consultar = "No se pudo actualizar la barrio";
		if (!$Ret = mysqli_query($coneccion->Conexion, $consulta)) {
			throw new Exception($mensaje_error_consultar, 2);
		}
	}

	public function save($coneccion) {
		$consulta = "INSERT INTO barrios (
										Barrio,
										georeferencia,
										estado 
					)
					VALUES ( " . ((!is_null($this->get_barrio())) ? "'" . $this->get_barrio() . "'" : "null") . ", 
							" . ((!is_null($this->get_georeferencia())) ? $this->get_georeferencia() : "null") . ",
							1
					)";
		echo $consulta;
		$mensaje_error_consultar = "No se pudo insertar el barrio";
		if (!$Ret = mysqli_query($coneccion->Conexion, $consulta)) {
			throw new Exception($mensaje_error_consultar, 2);
		}
	}
}
