<?php  
class Motivo implements JsonSerializable
{
	//DECLARACION DE VARIABLES
	private $id_motivo;
	private $motivo;
	private $codigo;
	private $cod_categoria;
    private $num_motivo;
	private $estado;
	private $coneccion_base;

	public function __construct(
			$coneccion_base=null,
			$id_motivo=null,
			$motivo=null,
			$cod_categoria=null,
			$num_motivo=null,
			$estado=null
	) {
		$this->coneccion_base = $coneccion_base;
		if (!$id_motivo) {
			$this->id_motivo = $id_motivo;
			$this->motivo = $motivo;
			$this->cod_categoria = $cod_categoria;
			$this->num_motivo = $num_motivo;
			$this->estado = $estado;
		} else {
			$consultar = "select *
                          from motivo 
                          where id_motivo = " . $id_motivo . " 
                            and estado = 1";
			$ejecutar_consultar = mysqli_query(
			$this->coneccion_base->Conexion, 
			$consultar) or die("Problemas al consultar filtro Motivo");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_id_motivo = $ret["id_motivo"];
				$row_motivo = $ret["motivo"];
				$row_cod_categoria = $ret["cod_categoria"];
				$row_num_motivo = $ret["num_motivo"];
				$row_estado = $ret["estado"];

				$this->id_motivo = $row_id_motivo;
				$this->motivo = $row_motivo;
				$this->cod_categoria = $row_cod_categoria;
				$this->num_motivo = $row_num_motivo;
				$this->estado = $row_estado;
			}
		}
	}

	public static function is_exist($coneccion, $id_motivo)
	{
		$consulta = "select * 
					 from motivo 
					 where id_motivo = $id_motivo 
					   and estado = 1";
		$mensaje_error = "Hubo un problema al consultar los registros para validar";
		$Ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		) or die(
			$mensaje_error
		);
		$is_multiple = (mysqli_num_rows($Ret) >= 1);
		return $is_multiple;
	}

    public static function get_id_by_name($coneccion, $motivo){
        $consulta = "select * 
					 from motivo 
					 where motivo like '%$motivo%' 
					   and estado = 1";
		$mensaje_error = "Hubo un problema al consultar los registros";
		$ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		) or die(
			$mensaje_error
		);
		$row = mysqli_fetch_assoc($ret);
        $id_motivo = (!empty($row["id_motivo"])) ? $row["id_motivo"] : 1;
		return $id_motivo;
    }

    public static function get_id_by_codigo($coneccion, $codigo){
        $consulta = "select * 
					 from motivo 
					 where codigo like '%$codigo%' 
					   and estado = 1";
		$mensaje_error = "Hubo un problema al consultar los registros";
		$ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		) or die(
			$mensaje_error
		);
		$row = mysqli_fetch_assoc($ret);
        $id_motivo = (!empty($row["id_motivo"])) ? $row["id_motivo"] : 1;
		return $id_motivo;
    }

	// METODOS SET
	public function set_id_motivo($id_motivo){
		$this->id_motivo = $id_motivo;
	}

	public function set_motivo($motivo){
		$this->motivo = $motivo;
	}

	public function set_cod_categoria($xID_Persona){
		$this->ID_Persona = $xID_Persona;
	}

	public function set_num_estado($num_estado){
		$this->num_estado = $num_estado;
	}

	public function set_estado($estado){
		$this->estado = $estado;
	}

	public function set_coneccion_base($coneccion_base){
		$this->coneccion_base = $coneccion_base;
	}

	//METODOS GET
	public function get_id_motivo(){
		return $this->id_motivo;
	}

	public function get_motivo(){
		return $this->motivo;
	}

	public function get_codigo(){
		return $this->motivo;
	}

	public function get_cod_categoria(){
		return $this->cod_categoria;
	}

	public function get_num_motivo(){
		return $this->num_motivo;
	}

	public function get_estado(){
		return $this->estado;
	}

	public function get_coneccion_base(){
		return $this->coneccion_base;
	}

	public function jsonSerialize() 
	{
		return [
			'id_motivo' => $this->id_motivo,
			'motivo' => $this->motivo,
			'codigo' => $this->codigo,
			'cod_categoria' => $this->cod_categoria,
			'num_motivo' => $this->num_motivo,
			'estado' => $this->estado
		];
	}

	public function udpate(){
		$consulta = "update motivo
					 set motivo = " . (($this->get_motivo()) ? $this->get_motivo() : "null") . ",
						 codigo = " . (($this->get_codigo()) ? $this->get_codigo() : "null") . ", 
						 cod_categoria = " . (($this->get_cod_categoria()) ? $this->get_cod_categoria() : "null") . ",
						 num_motivo = " . (($this->get_num_motivo()) ? $this->get_num_motivo() : "null") .",
						 estado = " . (($this->get_estado()) ? $this->get_estado() : "null") . "
					 where id_motivo = " . $this->get_id_motivo();
		$mensaje_error = "No se pudo modificar el motivo";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}
	public function save(){
		$consulta = "insert into motivo (
											id_motivo,
											motivo,
											codigo,
											cod_categoria,
											num_motivo,
											estado
											) 
				values(
						" . (($this->get_id_motivo()) ? $this->get_id_motivo() : "null") . ",
						" . (($this->get_motivo()) ? "'" . $this->get_motivo() . "'" : "null") . ",
						" . (($this->get_codigo()) ? "'" . $this->get_codigo() . "'" : "null") . ",
						" . (($this->get_cod_categoria()) ? "'" . $this->get_cod_categoria() . "'" : "null") . ",
						" . (($this->get_num_motivo()) ? $this->get_num_motivo() : "null") . ",
						" . (($this->get_estado()) ? $this->get_estado() : "null") . "
						)";
		$mensaje_error = "No se pudo insertar el motivo";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
		$this->id_motivo = mysqli_insert_id($this->coneccion_base->Conexion);
	}
}