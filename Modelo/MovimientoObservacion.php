<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Movimiento.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/TipoObservacion.php");


class MovimientoObservacion 
{
	// DECLARACION DE VARIABLES
    private $coneccion;
	private $id_movimiento_observacion;
	private $movimiento;
	private $observacion;
    private $tipo_observacion;
	private $estado;

	public function __construct(
        $coneccion = null,
        $id_movimiento_observacion = null,
		$movimiento = null,
		$observacion = null,
        $tipo_observacion = null,
		$estado = null
	) {
		$this->coneccion = $coneccion;

		if ($id_movimiento_observacion) {
			$consulta = "SELECT * 
						 FROM movimientos_observaciones
						 WHERE id_movimiento_observacion = $id_movimiento_observacion
						   AND estado = 1";
			$rs = mysqli_query(
							$this->coneccion->Conexion,
							$consulta
							  );
			if (mysqli_num_rows($rs) < 1) {
                $this->$id_movimiento_observacion = $id_movimiento_observacion;
				$this->movimiento = $movimiento;
				$this->observacion = $observacion;
				$this->tipo_observacion = $tipo_observacion;
				$this->estado = (!empty($estado))? $estado : 1;
			} else {
				$result = mysqli_fetch_assoc($rs);
				$this->id_movimiento_observacion = $result["id_movimiento_observacion"];
				$this->movimiento = new Movimiento(coneccion_base : $this->coneccion,
													  xID_Movimiento : $result["id_movimiento"]);
                $this->tipo_observacion = new TipoObservacion(coneccion: $this->coneccion,
															  id_tipo_observacion: $result["id_tipo_observacion"]);
				$this->observacion = $result["observacion"];
				$this->estado = (!empty($result["estado"]))? $result["estado"] : 1;
			}
		}  else {
			$this->movimiento = $movimiento;
			$this->tipo_observacion = $tipo_observacion;
			$this->observacion = $observacion;
			$this->estado = (!empty($estado))? $estado : 1;
}
	}

	public static function get_lista_observaciones_por_movimiento($coneccion, $movimiento)
	{
		$list = [];
		$consulta = "select * 
					from movimientos_observaciones
					where id_movimiento = " . $movimiento->getID_Movimiento() . "
					  and estado = 1";

		$mensaje = "error al consultar observaciones movimientos";
		$rs = mysqli_query($coneccion->Conexion, $consulta);
		if (!$rs) throw new Exception($mensaje, 1);

		while($ret = mysqli_fetch_assoc($rs)) {
			$list[] = new self(coneccion: $coneccion,
                        id_movimiento_observacion: $ret["id_movimiento_observacion"]);
		}
		return $list;
	}

    public static function exist_movimiento_observacion($coneccion, $movimiento)
	{
		$consulta = "select * 
					from movimientos_observaciones
					where id_movimiento = " .  $movimiento->getID_Movimiento() . "
					  and estado = 1";
		$rs = mysqli_query($coneccion->Conexion, $consulta);

        $mensaje = "error al consultar observaciones movimientos";
        if (!$rs) throw new Exception($mensaje, 1);

		$ret_query = mysqli_fetch_assoc($rs);
		$exist = ((!empty($ret_query["id_movimiento_observacion"])) ? $ret_query["id_movimiento_observacion"] : 0);
        return ($exist);
	}

    public static function exist_movimiento_observacion_con_tipo($coneccion, 
																 $movimiento,
																 $tipo_observacion
																 )
	{
		$consulta = "select * 
					from movimientos_observaciones
					where id_movimiento = " .  $movimiento->getID_Movimiento() . "
					  and id_tipo_observacion = " . $tipo_observacion->get_id_tipo_observacion() . "
					  and estado = 1";
		$rs = mysqli_query($coneccion->Conexion, $consulta);

        $mensaje = "error al consultar observaciones movimientos";
        if (!$rs) throw new Exception($mensaje, 1);

		$ret_query = mysqli_fetch_assoc($rs);
		$exist = ((!empty($ret_query["id_movimiento_observacion"])) ? $ret_query["id_movimiento_observacion"] : 0);
        return ($exist);
	}

	// METODOS SET
	public function set_id_movimiento_observacion($id_movimiento_observacion)
	{
		$this->id_movimiento_observacion = $id_movimiento_observacion;
	}

	public function set_id_movimiento($id_movimiento)
	{
		$this->movimiento = $id_movimiento;
	}

	public function set_observacion($observacion)
	{
		$this->observacion = $observacion;
	}

	public function set_tipo_observacion($tipo_observacion)
	{
		$this->tipo_observacion = $tipo_observacion;
	}

	public function set_estado($estado)
	{
		$this->estado = $estado;
	}

	// METODOS GET
	public function get_id_movimiento_observacion()
	{
		return $this->id_movimiento_observacion;
	}

	public function get_id_movimiento()
	{
		return $this->id_movimiento;
	}

	public function get_observacion()
	{
		return $this->observacion;
	}

	public function get_tipo_observacion()
	{
		return $this->tipo_observacion;
	}

	public function get_estado()
	{
		return $this->estado;
	}

	public function save() 
	{
		$consulta = "insert into movimientos_observaciones(
												   id_movimiento, 
												   observacion,
                                                   id_tipo_obseravacion,
												   estado
                                                   ) 
									values(" . $this->movimiento->getID_Movimiento() . "," 
											 . $this->observacion . ","
                                             . $this->tipo_observacion->get_id_tipo_observacion() . "
										       1)";
		if (!$RetAccion = mysqli_query($this->coneccion->Conexion, $consulta)) {
			throw new Exception("Error al intentar insertar el movimiento observacion. Consulta: ". $consulta, 3);
		}
		$this->id_movimiento_observacion = mysqli_insert_id($this->coneccion->Conexion);
	}

    public function update() 
	{
		$consulta = "update movimientos_observaciones
                            set estado = " . $this->estado . ",
								id_tipo_observacion = " . $this->tipo_observacion->get_id_tipo_observacion() . ",
								id_movimiento = " . $this->movimiento->getID_Movimiento() . ",
								observacion = " . $this->observacion . "
                            where id_movimiento_observacion = " . $this->id_movimiento_observacion;
		if (!$RetAccion = mysqli_query($this->coneccion->Conexion, $consulta)) {
			throw new Exception("Error al intentar actualizar el movimiento_observacion.", 3);
		}
	}

	public function delete() 
	{
		$consulta = "update movimientos_observaciones
                            set estado = 0
                            where id_movimiento_observacion = " . $this->id_movimiento_observacion;
		if (!$RetAccion = mysqli_query($this->coneccion->Conexion,$consulta)) {
			throw new Exception("Error al intentar borrar el movimiento observacion. Consulta: ". $consulta, 3);
		}
	}
}
