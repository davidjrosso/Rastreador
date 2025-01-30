<?php

class MovimientoMotivo 
{
	// DECLARACION DE VARIABLES
    private $connection;
	private $id_movimiento;
	private $id_motivo;
	private $nro_motivo;
	private $estado;

	public function __construct(
        $connection = null,
		$id_movimiento = null,
		$id_motivo = null,
		$nro_motivo = null,
		$estado = null
	) {
		$this->id_movimiento = $id_movimiento;
		$this->id_motivo = $id_motivo;
		$this->nro_motivo = $nro_motivo;
		$this->estado = $estado;
        $this->connection = $connection;
	}

    public static function exist_movimiento_motivo($connection, $movimiento, $motivo)
	{
		$consulta = "select * 
					from movimiento_motivo
					where id_movimiento = $movimiento
                      and id_motivo = $motivo ";
		$rs = mysqli_query($connection->Conexion,$consulta) or die("Problemas al consultar las acciones.");
        return (mysqli_num_rows($rs) > 0);
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

	public function set_nro_motivo($nro_motivo)
	{
		$this->nro_motivo = $nro_motivo;
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

	public function get_nro_motivo()
	{
		return $this->nro_motivo;
	}

	public function get_estado()
	{
		return $this->estado;
	}

	public function save() 
	{
		$consulta_accion = "insert into movimiento_motivo(id_movimiento, 
												          id_motivo, 
												          nro_motivo, 
												          estado
                                                          ) 
									values(" . $this->id_movimiento . "," 
											 . $this->id_motivo . "," 
											 . $this->nro_motivo . ",
										        1)";
		if (!$RetAccion = mysqli_query($this->connection->Conexion,$consulta_accion)) {
			throw new Exception("Error al intentar registrar Accion. Consulta: ". $consulta_accion, 3);
		}
	}

    public function update_motivo($motivo) 
	{
		$consulta_accion = "update movimiento_motivo
                            set id_motivo = " . $motivo . ",
                                nro_motivo = " . $this->nro_motivo . "
                                estado = " . $this->estado . "
                            where id_movimiento = " . $this->id_movimiento . "
                              and id_motivo = " . $this->id_motivo;
		if (!$RetAccion = mysqli_query($this->connection->Conexion,$consulta_accion)) {
			throw new Exception("Error al intentar registrar Accion. Consulta: ". $consulta_accion, 3);
		}
	}

    public function update() 
	{
		$consulta_accion = "update movimiento_motivo
                            set nro_motivo = " . $this->nro_motivo . "
                                estado = " . $this->estado . "
                            where id_movimiento = " . $this->id_movimiento . "
                              and id_motivo = " . $this->id_motivo;
		if (!$RetAccion = mysqli_query($this->connection->Conexion,$consulta_accion)) {
			throw new Exception("Error al intentar registrar Accion. Consulta: ". $consulta_accion, 3);
		}
	}

}
