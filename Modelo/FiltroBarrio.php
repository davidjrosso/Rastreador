<?php  
class FiltroBarrio implements JsonSerializable
{
	//DECLARACION DE VARIABLES
    private $id_barrio;
    private $id_filtro_barrio;
    private $id_filtro;
	private $estado;
	private $coneccion;

    public function __construct(
			$coneccion = null,
			$id_filtro = null,
            $id_filtro_barrio = null,
            $id_barrio = null,
			$estado = null
	) {
		$this->coneccion = $coneccion;
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
			$this->coneccion->Conexion, 
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

	public static function exist_barrio_con_filtro($coneccion, $id_filtro, $id_barrio)
	{
		$is_multiple = 0;
		if ($id_filtro && $id_barrio) {
			$consulta = "select * 
						from filtros_barrios
						where id_filtro = $id_filtro
						and id_barrio = $id_barrio
						and estado = 1";
			$mensaje = "Hubo un problema al consultar los registros para validar";
			$ret = mysqli_query(
						$coneccion->Conexion,
						$consulta
			);
			if (!$ret) throw new Exception($mensaje, 3);
			$is_multiple = (mysqli_num_rows($ret) >= 1);
		}
		return $is_multiple;
	}

	public static function get_list_barrio_por_id_filtro($coneccion, $id_filtro)
	{
		$list = [];
		if ($id_filtro) {
			$consulta = "select * 
					 from filtros_barrios
					 where id_filtro = $id_filtro
					   and estado = 1";
            $mensaje = "Error en consultar datos.";

            $ret = mysqli_query($coneccion->Conexion, $consulta);

            if (!$ret) throw new Exception($mensaje, 1);

            while($res = mysqli_fetch_assoc($ret)) {
                $list[] = new self(
                                   coneccion: $coneccion,
                                   id_filtro_barrio: $res["id_filtro_barrio"]
                                   );
            }
        }
        return $list;
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

	public function set_coneccion($coneccion)
    {
		$this->coneccion = $coneccion;
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

	public function get_coneccion()
	{
		return $this->coneccion;
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
		$ret = mysqli_query($this->coneccion->Conexion, $consulta);
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
		$ret = mysqli_query($this->coneccion->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
		$this->id_filtro = mysqli_insert_id($this->coneccion->Conexion);
	}
}