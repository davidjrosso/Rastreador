<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");

class TipoObservacion
{
	// DECLARACION DE VARIABLES
    private $coneccion;
    private $id_tipo_observacion;
    private $descripcion;
	private $estado;

	public function __construct(
        $coneccion = null,
		$descripcion = null,
        $id_tipo_observacion = null,
		$estado = null
	) {
		$this->coneccion = $coneccion;

		if ($id_tipo_observacion) {
			$consulta = "SELECT * 
						 FROM tipos_observaciones
						 WHERE id_tipo_observacion = $id_tipo_observacion
						   AND estado = 1";
			$rs = mysqli_query(
							$this->coneccion->Conexion,
							$consulta
							  );
			if (mysqli_num_rows($rs) < 1) {
				$this->id_tipo_observacion = $id_tipo_observacion;
				$this->descripcion = $descripcion;
				$this->estado = (!empty($estado))? $estado : 1;
			} else {
				$result = mysqli_fetch_assoc($rs);
				$this->id_tipo_observacion = $result["id_tipo_observacion"];
				$this->descripcion = $result["descripcion"];
				$this->estado = (!empty($result["estado"]))? $result["estado"] : 1;
			}
		}  else {
			$this->id_tipo_observacion = $id_tipo_observacion;
			$this->descripcion = $descripcion;
			$this->estado = (!empty($estado))? $estado : 1;
}
	}


    public static function exist_tipo_observacion($coneccion, $id_tipo_observacion)
	{
		$consulta = "select * 
					from tipos_observaciones
					where id_tipo_observacion = " .  $id_tipo_observacion . "
					  and estado = 1";
		$rs = mysqli_query($coneccion->Conexion, $consulta);

        $mensaje = "error al consultar tipos observaciones";
        if (!$rs) throw new Exception($mensaje, 1);

		$ret_query = mysqli_fetch_assoc($rs);
		$exist = ((!empty($ret_query["id_tipo_observacion"])) ? $ret_query["id_tipo_observacion"] : 0);
        return ($exist);
	}

	// METODOS SET
	public function set_id_tipo_observacion($id_tipo_observacion)
	{
		$this->id_tipo_observacion = $id_tipo_observacion;
	}

	public function set_descripcion($descripcion)
	{
		$this->descripcion = $descripcion;
	}

	public function set_estado($estado)
	{
		$this->estado = $estado;
	}

	// METODOS GET
	public function get_id_tipo_observacion()
	{
		return $this->id_tipo_observacion;
	}

	public function get_descripcion()
	{
		return $this->descripcion;
	}

	public function get_estado()
	{
		return $this->estado;
	}

	public function save() 
	{
		$consulta = "insert into tipos_observaciones(
												   id_tipo_observacion, 
												   descripcion,
												   estado
                                                   ) 
									values(" . $this->id_tipo_observacion . "," 
											 . $this->descripcion . ",
										       1)";
		if (!$RetAccion = mysqli_query($this->coneccion->Conexion, $consulta)) {
			throw new Exception("Error al intentar insertar el tipo observacion.", 3);
		}
		$this->id_tipo_observacion = mysqli_insert_id($this->coneccion->Conexion);
	}

    public function update() 
	{
		$consulta = "update tipos_observaciones
                            set estado = " . $this->estado . ",
								descripcion = '" . $this->descripcion . "',
                            where id_tipo_observacion = " . $this->id_tipo_observacion;
		if (!$RetAccion = mysqli_query($this->coneccion->Conexion, $consulta)) {
			throw new Exception("Error al intentar actualizar el tipos observaciones.", 3);
		}
	}

	public function delete() 
	{
		$consulta = "update tipos_observaciones
                            set estado = 0
                            where id_tipo_observacion = " . $this->id_tipo_observacion;
		if (!$RetAccion = mysqli_query($this->coneccion->Conexion, $consulta)) {
			throw new Exception("Error al intentar borrar el tipo observacion.", 3);
		}
	}
}
