<?php  
class FiltroResponsable implements JsonSerializable
{
	//DECLARACION DE VARIABLES
    private $id_responsable;
    private $id_filtro_responsable;
    private $id_filtro;
	private $estado;
	private $coneccion;

    public function __construct(
			$coneccion = null,
			$id_filtro = null,
            $id_filtro_responsable = null,
            $id_responsable = null,
			$estado = null
	) {
		$this->coneccion = $coneccion;
		if (!$id_filtro_responsable) {
			$this->id_responsable = $id_responsable;
            $this->id_filtro = $id_filtro;
            $this->id_filtro_responsable = $id_filtro_responsable;
            $this->estado = $estado;
		} else {
			$consultar = "select *
                          from filtros_responsables
                          where id_filtro_responsable = " . $id_filtro_responsable . " 
                            and estado = 1";
			$ejecutar_consultar = mysqli_query(
			$this->coneccion->Conexion, 
			$consultar) or die("Problemas al consultar filtro");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_id_filtro_responsable = $ret["id_filtro_responsable"];
				$row_id_responsable = $ret["id_responsable"];
				$row_estado = $ret["estado"];
                $row_id_filtro = $ret["id_filtro"];

                $this->id_filtro_responsable = $row_id_filtro_responsable;
                $this->id_responsable = $row_id_responsable;

                $this->id_filtro = $row_id_filtro;
                $this->estado = $row_estado;

			}
		}
	}

	public static function is_exist($coneccion, $id_filtro_responsable)
	{
		$consulta = "select * 
					 from filtros_responsables
					 where id_filtro = $id_filtro_responsable
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

	public static function exist_responsable_con_filtro($coneccion, $id_filtro, $id_responsable)
	{
		$is_multiple = 0;
		if ($id_filtro && $id_responsable) {
			$consulta = "select * 
						from filtros_responsables
						where id_filtro = $id_filtro
						and id_responsable = $id_responsable
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

	public static function get_list_responsable_por_id_filtro($coneccion, $id_filtro)
	{
		$list = [];
		if ($id_filtro) {
			$consulta = "select * 
					 from filtros_responsables
					 where id_filtro = $id_filtro
					   and estado = 1";
            $mensaje = "Error en consultar datos.";

            $ret = mysqli_query($coneccion->Conexion, $consulta);

            if (!$ret) throw new Exception($mensaje, 1);

            while($res = mysqli_fetch_assoc($ret)) {
                $list[] = new self(
                                   coneccion: $coneccion,
                                   id_filtro_responsable: $res["id_filtro_responsable"]
                                   );
            }
        }
        return $list;
	}

	// METODOS SET
	public function set_id_responsable($id_responsable)
    {
		$this->id_responsable = $id_responsable;
	}

    public function set_id_filtro_responsable($id_filtro_responsable)
    {
        $this->id_filtro_responsable = $id_filtro_responsable;
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
		$this->coneccion = $coneccion_base;
	}

	//METODOS GET
	public function get_id_responsable()
    {
		return $this->id_responsable;
	}

	public function get_id_filtro_responsable()
    {
		return $this->id_filtro_responsable;
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
		return $this->coneccion;
	}

	public function jsonSerialize() 
	{
		return [
			'id_responsable' => $this->id_responsable,
			'id_filtro_responsable' => $this->id_filtro_responsable,
			'estado' => $this->estado
		];
	}

	public function udpate()
    {
		$consulta = "update filtros_responsables
					 set id_responsable = " . (($this->get_id_responsable()) ? "'" . $this->get_id_responsable() . "'" : "null") . ", 
                         id_filtro = " . (($this->get_id_filtro()) ? "'" . $this->get_id_filtro() . "'" : "null") . ", 
						 estado = " . (($this->get_estado()) ? $this->get_estado() : "null") . "
					 where id_filtro_responsable = " . $this->get_id_filtro_responsable();
		$mensaje_error = "No se pudo modificar el filtro_responsable";
		$ret = mysqli_query($this->coneccion->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function save()
    {
		$consulta = "insert into filtros_responsables (
												id_filtro_responsable,
                                                id_responsable,
												id_filtro,
												estado
                                                ) 
				values(
						" . (($this->get_id_filtro_responsable()) ? $this->get_id_filtro_responsable() : "null") . ",
						" . (($this->get_id_responsable()) ? $this->get_id_responsable() : "null") . ",
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