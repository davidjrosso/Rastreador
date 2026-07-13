<?php  
class FiltroMotivo implements JsonSerializable
{
	//DECLARACION DE VARIABLES
    private $id_motivo;
    private $id_filtro_motivo;
    private $id_filtro;
	private $estado;
	private $coneccion_base;

    public function __construct(
			$coneccion_base = null,
			$id_filtro = null,
            $id_filtro_motivo = null,
            $id_motivo = null,
			$estado = null
	) {
		$this->coneccion_base = $coneccion_base;
		if (!$id_filtro_motivo) {
			$this->id_motivo = $id_motivo;
            $this->id_filtro = $id_filtro;
            $this->id_filtro_motivo = $id_filtro_motivo;
            $this->estado = $estado;
		} else {
			$consultar = "select *
                          from filtros_motivos
                          where id_filtro_motivo = " . $id_filtro_motivo . " 
                            and estado = 1";
			$ejecutar_consultar = mysqli_query(
			$this->coneccion_base->Conexion, 
			$consultar) or die("Problemas al consultar filtro centro");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_id_filtro_motivo = $ret["id_filtro_motivo"];
				$row_id_motivo = $ret["id_motivo"];
				$row_estado = $ret["estado"];
                $row_id_filtro = $ret["id_filtro"];

                $this->id_filtro_motivo = $row_id_filtro_motivo;
                $this->id_motivo = $row_id_motivo;

                $this->id_filtro = $row_id_filtro;
                $this->estado = $row_estado;

			}
		}
	}

	public static function is_exist($coneccion, $id_filtro_motivo)
	{
		$consulta = "select * 
					 from filtros_motivos
					 where id_filtro = $id_filtro_motivo
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

	// METODOS SET
	public function set_id_motivo($id_motivo)
	{
		$this->id_motivo = $id_motivo;
	}

    public function set_id_filtro_motivo($id_filtro_motivo)
    {
        $this->id_filtro_motivo = $id_filtro_motivo;
    }

	public function set_id_filtro($id_filtro)
	{
		$this->id_filtro = $id_filtro;
	}

	public function set_estado($estado)
	{
		$this->estado = $estado;
	}

	public function set_coneccion_base($coneccion_base)
	{
		$this->coneccion_base = $coneccion_base;
	}

	//METODOS GET
	public function get_id_motivo()
	{
		return $this->id_motivo;
	}

	public function get_id_filtro_motivo()
	{
		return $this->id_filtro_motivo;
	}

    public function get_id_filtro()
    {
        return $this->id_filtro;
    }

	public function get_estado()
	{
		return $this->estado;
	}

	public function get_coneccion_base()
	{
		return $this->coneccion_base;
	}

	public function jsonSerialize() 
	{
		return [
			'id_motivo' => $this->id_motivo,
			'id_filtro_motivo' => $this->id_filtro_motivo,
			'estado' => $this->estado
		];
	}

	public function udpate()
    {
		$consulta = "update filtros_motivos
					 set id_motivo = " . (($this->get_id_motivo()) ? "'" . $this->get_id_motivo() . "'" : "null") . ", 
                         id_filtro = " . (($this->get_id_filtro()) ? "'" . $this->get_id_filtro() . "'" : "null") . ", 
						 estado = " . (($this->get_estado()) ? $this->get_estado() : "null") . "
					 where id_filtro_motivo = " . $this->get_id_filtro_motivo();
		$mensaje_error = "No se pudo modificar el filtro_motivo";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function save()
    {
		$consulta = "insert into filtros_motivos (
												id_filtro_motivo,
                                                id_motivo,
												id_filtro,
												estado
                                                ) 
				values(
						" . (($this->get_id_filtro_motivo()) ? $this->get_id_filtro_motivo() : "null") . ",
						" . (($this->get_id_motivo()) ? $this->get_id_motivo() : "null") . ",
						" . (($this->get_id_filtro()) ? $this->get_id_filtro() : "null") . ",
						" . (($this->get_estado()) ? $this->get_estado() : "null") . "
						)";
		$mensaje_error = "No se pudo insertar";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
		$this->id_filtro = mysqli_insert_id($this->coneccion_base->Conexion);
	}
}