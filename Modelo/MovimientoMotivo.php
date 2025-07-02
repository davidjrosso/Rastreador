<?php

class MovimientoMotivo 
{
	// DECLARACION DE VARIABLES
    private $connection;
	private $id_movimiento_motivo;
	private $id_movimiento;
	private $id_motivo;
	private $estado;

	public function __construct(
        $connection = null,
		$id_movimiento = null,
		$id_motivo = null,
		$estado = null
	) {
		$this->connection = $connection;

		if ($id_movimiento && $id_motivo) {
			$consulta = "SELECT * 
						 FROM movimiento_motivo
						 WHERE id_movimiento = $id_movimiento
						   AND id_motivo = $id_motivo 
						   AND estado = 1";
			$rs = mysqli_query(
							$this->connection->Conexion,
							$consulta
							  );
			if (mysqli_num_rows($rs) < 1) {
				$this->id_movimiento = $id_movimiento;
				$this->id_motivo = $id_motivo;
				$this->estado = (!empty($estado))? $estado : 1;
			} else {
				$result = mysqli_fetch_assoc($rs);
				$this->id_movimiento_motivo = $result["id_movimiento_motivo"];
				$this->id_movimiento = $id_movimiento;
				$this->id_motivo = $id_motivo;
				$this->estado = (!empty($result["estado"]))? $result["estado"] : 1;
			}
		}  else {
			$this->id_movimiento = $id_movimiento;
			$this->id_motivo = $id_motivo;
			$this->estado = (!empty($estado))? $estado : 1;
}
	}

    public static function exist_movimiento_motivo($connection, $movimiento, $motivo)
	{
		$consulta = "select * 
					from movimiento_motivo
					where id_movimiento = $movimiento
                      and id_motivo = $motivo 
					  and estado = 1";
		$rs = mysqli_query($connection->Conexion,$consulta) or die("Problemas al consultar las acciones.");
		$ret_query = mysqli_fetch_assoc($rs);
		$exist = ((!empty($ret_query["id_movimiento"])) ? $ret_query["id_movimiento"] : 0);
        return ($exist);
	}

	// METODOS SET
	public function set_id_movimiento($id_movimiento)
	{
		$this->id_movimiento = $id_movimiento;
	}

	public function set_id_motivo($id_motivo)
	{
		$this->id_motivo = $id_motivo;
	}

	public function set_estado($estado)
	{
		$this->estado = $estado;
	}

	// METODOS GET
	public function get_id_movimiento()
	{
		return $this->id_movimiento;
	}

	public function get_id_motivo()
	{
		return $this->id_motivo;
	}

	public function get_estado()
	{
		return $this->estado;
	}

	public function save() 
	{
		$consulta = "insert into movimiento_motivo(id_movimiento, 
												   id_motivo, 
												   estado
                                                   ) 
									values(" . $this->id_movimiento . "," 
											 . $this->id_motivo . ",
										       1)";
		if (!$RetAccion = mysqli_query($this->connection->Conexion,$consulta)) {
			throw new Exception("Error al intentar insertar el movimiento_motivo. Consulta: ". $consulta, 3);
		}
	}

    public function update() 
	{
		$consulta = "update movimiento_motivo
                            set estado = " . $this->estado . "
                            where id_movimiento = " . $this->id_movimiento . "
                              and id_motivo = " . $this->id_motivo;
		if (!$RetAccion = mysqli_query($this->connection->Conexion,$consulta)) {
			throw new Exception("Error al intentar actualizar el movimiento_motivo. Consulta: ". $consulta, 3);
		}
	}

	public function delete() 
	{
		$consulta = "update movimiento_motivo
                            set estado = 0
                            where id_movimiento = " . $this->id_movimiento . "
                              and id_motivo = " . $this->id_motivo;
		if (!$RetAccion = mysqli_query($this->connection->Conexion,$consulta)) {
			throw new Exception("Error al intentar borrar el movimiento_motivo. Consulta: ". $consulta, 3);
		}
	}
}
