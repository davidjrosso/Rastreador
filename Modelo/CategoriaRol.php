<?php  
class CategoriaRol {
//DECLARACION DE VARIABLES
	private $id_categoria_rol;
	private $id_categoria;
	private $fecha;
	private $id_tipo_usuario;
	private $conecction;
	private $estado;	

	//METODO CONSTRUCTOR
	public function __construct(
		$id_categoria_rol=null,
		$id_categoria=null,
		$fecha=null,
		$id_tipo_usuario=null,
        $conecction=null,
		$estado=null
	){
		$this->conecction = $conecction;

		if (!$id_categoria_rol) {
			$this->id_categoria_rol = $id_categoria_rol;
			$this->id_categoria = $id_categoria;
			$this->fecha = $fecha;
			$this->id_tipo_usuario = $id_tipo_usuario;
			$this->estado = ($estado) ? $estado : 1;
		} else {
			$consultar = "select *
						  from categorias_roles 
						  where id_categoria_rol = " . $this->id_categoria_rol . " 
							and estado = 1";
			$ejecutar_consultar = mysqli_query(
				$this->conecction->Conexion,
				$consultar) or die("Problemas al consultar filtro categirias");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_id_categoria_rol = $ret["id_categoria_rol"];
				$row_id_categoria = $ret["id_categoria"];
				$row_fecha = $ret["fecha"];
				$row_id_tipo_usuario = $ret["id_tipo_usuario"];
				$row_estado = $ret["estado"];

				$this->id_categoria_rol = $row_id_categoria_rol;
				$this->id_categoria = $row_id_categoria;
				$this->estado = ($row_estado) ? $row_estado : 0;
				$this->id_tipo_usuario = $row_id_tipo_usuario;
				$this->fecha = $row_fecha;
			}			
		}
	}

    public static function exist_rol($connection, $id_categoria, $id_tipo_usuario) 
    {
        $consultar = "select *
                        from categorias_roles 
                        where id_categoria = " . $id_categoria . "
                          and id_tipo_usuario = " . $id_tipo_usuario . "
                          and estado = 1";
        $ejecutar_consultar = mysqli_query(
            $connection->Conexion,
            $consultar) or die("Problemas al consultar categorias roles");
        $num_rows = mysqli_num_rows($ejecutar_consultar);
        return $num_rows;
    }

	//METODOS SET
	public function set_id_categoria($id_categoria)
	{
		$this->id_categoria = $id_categoria;
	}

	public function set_id_categoria_rol($id_categoria_rol)
	{
		$this->id_categoria_rol = $id_categoria_rol;
	}

	public function set_id_tipo_usuario($id_tipo_usuario)
	{
		$this->id_tipo_usuario = $id_tipo_usuario;
	}

	public function set_fecha($fecha)
	{
		$this->fecha = $fecha;
	}
	public function set_estado($estado)
	{
		$this->estado = $estado;
	}

	//METODOS GET
	public function get_id_categoria_rol()
	{
		return $this->id_categoria_rol;
	}

	public function get_id_categoria()
	{
		return $this->id_categoria;
	}

	public function get_fecha()
	{
		return $this->fecha;
	}
	public function get_id_tipo_usuario()
	{
		return $this->id_tipo_usuario;
	}
	public function get_estado()
	{
		return $this->estado;
	}
	public function delete()
	{
		$this->estado = 0;

	}
	public function update(){
		$consulta = "update categorias_roles
					 set id_tipousuario = " . (($this->get_id_tipo_usuario()) ? $this->get_id_tipo_usuario() : "null") . ",
						 id_categoria = " . (($this->get_id_categoria()) ? $this->get_id_categoria() : "null") . ", 
						 fecha = " . (($this->get_fecha()) ? "'" . $this->get_fecha() . "'" : "null") .",
						 estado = " . (($this->get_estado()) ? $this->get_estado() : "null") . "
					 where id_categoria_rol = " . $this->get_id_categoria_rol();
		$mensaje_error = "No se pudo modificar la categoria rol";
		$ret = mysqli_query($this->conecction->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function save(){
		$consulta = "insert into categorias_roles (
											id_categoria,
											id_tipousuario,
											fecha,
											estado
											) 
				values(
						" . (($this->get_id_categoria()) ? $this->get_id_categoria() : "null") . ",
						" . (($this->get_id_tipo_usuario()) ? $this->get_id_tipo_usuario() : "null") . ",
						" . (($this->get_fecha()) ? "'" . $this->get_fecha() . "'" : "null") . ",
						" . (($this->get_estado()) ? $this->get_estado() : "null") . "
						)";
		$mensaje_error = "No se pudo insertar la categoria rol";
		$ret = mysqli_query($this->conecction->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
		$this->id_categoria_rol = mysqli_insert_id($this->conecction->Conexion);
	}
}
