<?php  
class FiltroCategoria implements JsonSerializable
{
	//DECLARACION DE VARIABLES
    private $id_categoria;
    private $id_filtro_categoria;
    private $id_filtro;
	private $estado;
	private $coneccion;

    public function __construct(
			$coneccion = null,
			$id_filtro = null,
            $id_filtro_categoria = null,
            $id_categoria = null,
			$estado = null
	) {
		$this->coneccion = $coneccion;
		if (!$id_filtro_categoria) {
			$this->id_categoria = $id_categoria;
            $this->id_filtro = $id_filtro;
            $this->id_filtro_categoria = $id_filtro_categoria;
            $this->estado = $estado;
		} else {
			$consultar = "select *
                          from filtros_categorias
                          where id_filtro_categoria = " . $id_filtro_categoria . " 
                            and estado = 1";
			$ejecutar_consultar = mysqli_query(
			$this->coneccion->Conexion, 
			$consultar) or die("Problemas al consultar filtro");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_id_filtro_categoria = $ret["id_filtro_categoria"];
				$row_id_categoria = $ret["id_categoria"];
				$row_estado = $ret["estado"];
                $row_id_filtro = $ret["id_filtro"];

                $this->id_filtro_categoria = $row_id_filtro_categoria;
                $this->id_categoria = $row_id_categoria;

                $this->id_filtro = $row_id_filtro;
                $this->estado = $row_estado;

			}
		}
	}

	public static function is_exist($coneccion, $id_filtro_cateogoria)
	{
		$consulta = "select * 
					 from filtros_categoria
					 where id_filtro = $id_filtro_cateogoria
					   and estado = 1";
		$mensaje = "Hubo un problema al consultar los registros para validar";
		$ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		);
		if (!$ret) throw new Exception($mensaje, 3);
		$is_multiple = (mysqli_num_rows($ret) >= 1);
		return $is_multiple;
	}

	public static function exist_categoria_con_filtro($coneccion, $id_filtro, $id_categoria)
	{
		$is_multiple = 0;
		if ($id_filtro && $id_categoria) {
			$consulta = "select * 
						from filtros_categorias
						where id_filtro = $id_filtro
						and id_categoria = $id_categoria
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

	public static function get_list_categoria_por_id_filtro($coneccion, $id_filtro)
	{
		$list = [];
		if ($id_filtro) {
			$consulta = "select * 
					 from filtros_categorias
					 where id_filtro = $id_filtro
					   and estado = 1";
            $mensaje = "Error en consultar datos.";

            $ret = mysqli_query($coneccion->Conexion, $consulta);

            if (!$ret) throw new Exception($mensaje, 1);

            while($res = mysqli_fetch_assoc($ret)) {
                $list[] = new self(
                                   coneccion: $coneccion,
                                   id_filtro_categoria: $res["id_filtro_categoria"]
                                   );
            }
        }
        return $list;
	}

	// METODOS SET
	public function set_id_categoria($id_categoria)
	{
		$this->id_categoria = $id_categoria;
	}

    public function set_id_filtro_categoria($id_filtro_categoria)
    {
        $this->id_filtro_categoria = $id_filtro_categoria;
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
	public function get_id_categoria()
	{
		return $this->id_categoria;
	}

	public function get_id_filtro_categoria()
	{
		return $this->id_filtro_categoria;
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
			'id_categoria' => $this->id_categoria,
			'id_filtro_categoria' => $this->id_filtro_categoria,
			'estado' => $this->estado
		];
	}

	public function udpate()
    {
		$consulta = "update filtros_categorias
					 set id_categoria = " . (($this->get_id_categoria()) ? "'" . $this->get_id_categoria() . "'" : "null") . ", 
                         id_filtro = " . (($this->get_id_filtro()) ? "'" . $this->get_id_filtro() . "'" : "null") . ", 
						 estado = " . (($this->get_estado()) ? $this->get_estado() : "null") . "
					 where id_filtro_categoria = " . $this->get_id_filtro_categoria();
		$mensaje_error = "No se pudo modificar el filtros_categorias";
		$ret = mysqli_query($this->coneccion->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function save()
    {
		$consulta = "insert into filtros_categorias (
												id_filtro_categoria,
                                                id_categoria,
												id_filtro,
												estado
                                                ) 
				values(
						" . (($this->get_id_filtro_categoria()) ? $this->get_id_filtro_categoria() : "null") . ",
						" . (($this->get_id_categoria()) ? $this->get_id_categoria() : "null") . ",
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