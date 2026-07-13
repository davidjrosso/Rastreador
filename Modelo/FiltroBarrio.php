<?php  
class FiltroBarrio implements JsonSerializable
{
	//DECLARACION DE VARIABLES
    private $id_barrio;
    private $id_filtro_barrio;
    private $id_filtro;
	private $estado;
	private $coneccion_base;

    public function __construct(
			$coneccion_base = null,
			$id_filtro = null,
            $id_filtro_barrio = null,
            $id_barrio = null,
			$estado = null
	) {
		$this->coneccion_base = $coneccion_base;
		if (!$id_filtro_barrio) {
			$this->id_barrio = $id_barrio;
            $this->id_filtro = $id_filtro;
            $this->id_filtro_barrio = $id_filtro_barrio;
            $this->estado = $estado;
		} else {
			$consultar = "select *
                          from filtros_barrios
                          where id_filtro_barrio = " . $id_filtro_barrio . " 
                            and estado = 1";
			$ejecutar_consultar = mysqli_query(
			$this->coneccion_base->Conexion, 
			$consultar) or die("Problemas al consultar filtro");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_id_filtro_barrio = $ret["id_filtro_barrio"];
				$row_id_barrio = $ret["id_barrio"];
				$row_estado = $ret["estado"];
                $row_id_filtro = $ret["id_filtro"];

                $this->id_filtro_barrio = $row_id_filtro_barrio;
                $this->id_barrio = $row_id_barrio;

                $this->id_filtro = $row_id_filtro;
                $this->estado = $row_estado;

			}
		}
	}

	public static function is_exist($coneccion, $id_filtro_barrio)
	{
		$consulta = "select * 
					 from filtros_barrios
					 where id_filtro = $id_filtro_barrio
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
	public function set_id_barrio($id_barrio)
    {
		$this->id_barrio = $id_barrio;
	}

    public function set_id_filtro_barrio($id_filtro_barrio)
    {
        $this->id_filtro_barrio = $id_filtro_barrio;
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
	public function get_id_barrio()
    {
		return $this->id_barrio;
	}

	public function get_id_filtro_barrio()
    {
		return $this->id_filtro_barrio;
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
			'id_barrio' => $this->id_barrio,
			'id_filtro_barrio' => $this->id_filtro_barrio,
			'estado' => $this->estado
		];
	}

	public function udpate()
    {
		$consulta = "update filtros_barrios
					 set id_barrio = " . (($this->get_id_barrio()) ? "'" . $this->get_id_barrio() . "'" : "null") . ", 
                         id_filtro = " . (($this->get_id_filtro()) ? "'" . $this->get_id_filtro() . "'" : "null") . ", 
						 estado = " . (($this->get_estado()) ? $this->get_estado() : "null") . "
					 where id_filtro_barrio = " . $this->get_id_filtro_barrio();
		$mensaje_error = "No se pudo modificar el filtro_barrio";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function save()
    {
		$consulta = "insert into filtros_barrios (
												id_filtro_barrio,
                                                id_barrio,
												id_filtro,
												estado
                                                ) 
				values(
						" . (($this->get_id_filtro_barrio()) ? $this->get_id_filtro_barrio() : "null") . ",
						" . (($this->get_id_barrio()) ? $this->get_id_barrio() : "null") . ",
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