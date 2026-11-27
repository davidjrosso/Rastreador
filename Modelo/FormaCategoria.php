<?php  
class FormaCategoria
{
	private $coneccion;
	private $id_forma_categoria;
	private $forma_categoria;
	private $figura;

	public function __construct(
								$coneccion=null,
								$id_forma_categoria=null,
								$forma_categoria=null,
								$figura=null
	){
		$this->coneccion = $coneccion;
		if (!$id_forma_categoria) {
			$this->id_forma_categoria = $id_forma_categoria;
			$this->forma_categoria = $forma_categoria;
			$this->figura = $figura;
		} else {
			$consultar_usuario = "select *
								 from formas_categorias 
								 where ID_Forma = $id_forma_categoria"; 
			$ejecutar_consultar = mysqli_query(
				$this->coneccion_base->Conexion, 
				$consultar_usuario) or die("Problemas al consultar filtro formas_categorias");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_id_forma_categoria = $ret['ID_Forma'];
				$row_forma_categoria = $ret['Forma_Categoria'];
                $row_figura = $ret['Figura'];

				$this->id_forma_categoria = $row_id_forma_categoria;
				$this->forma_categoria = $row_forma_categoria;
				$this->figura = $row_figura;
			}
		}
	}

	public static function exist_figura($figura = null, $coneccion = null)
	{
		$query = "select *
				  from formas_categorias
				  where Figura like '%$figura%'";
		if (!$obj = mysqli_query($coneccion->Conexion, $query))	
			throw new Exception("Error query");
		$result = mysqli_num_rows($obj);
		return $result;
	}

	public static function exist_figura_con_forma($figura = null, $forma = null, $coneccion = null)
	{
		$query = "select *
				  from formas_categorias
				  where Figura like '%$figura%'
				  	and Forma_Categoria like '%$forma%'";
		if (!$obj = mysqli_query($coneccion->Conexion, $query))	
			throw new Exception("Error query");
		$result = mysqli_num_rows($obj);
		return $result;
	}

	public static function exist_id($id = null, $coneccion = null)
	{
		$query = "select *
				  from formas_categorias
				  where ID_Forma = $id";
		if (!$obj = mysqli_query($coneccion->Conexion, $query))	
			throw new Exception("Error query");
		$result = mysqli_num_rows($obj);
		return $result;

	}
	
	//METODOS SET
	public function set_id_forma_categoria($id_forma_categoria)
	{
		$this->id_forma_categoria = $id_forma_categoria;
	}

	public function set_coneccion_base($coneccion)
	{
		$this->coneccion = $coneccion;
	}

	public function set_forma_categoria($forma_categoria)
	{
		$this->forma_categoria = $forma_categoria;
	}

	public function set_figura($figura)
	{
		$this->figura = $figura;
	}



	public function get_id_forma_categoria()
	{
		return $this->id_forma_categoria;
	}

	public function get_forma_categoria()
	{
		return $this->forma_categoria;
	}

	public function get_figura()
	{
		return $this->figura;
	}

	public function update()
	{
        $consulta = "update formas_categorias
					 set Forma_Categoria =  " . (!empty($this->get_forma_categoria()) ? "'" . $this->get_forma_categoria() . "'": "null") . ",
					 		Figura =  " . (!empty($this->get_figura()) ? "'" . $this->get_figura() . "'" : "null") . "
					 where ID_Forma= " . $this->get_id_forma_categoria();
		if (!$obj = mysqli_query($this->coneccion->Conexion, $consulta))
			throw new Exception("Error update dato formas categorias");
		
	}

	public function save()
	{
        $consulta = "insert into formas_categorias(Forma_Categoria,
										  Figura) 
					 values(" . (!empty($this->get_forma_categoria()) ? "'" . $this->get_forma_categoria() . "'": "null") . ","
							   . (!empty($this->get_figura()) ? "'" . $this->get_figura() . "'": "null"). ")";
		if (!$ret = mysqli_query($this->coneccion->Conexion, $consulta))
			throw new Exception("Error query save dato formas categorias");
		$this->id_forma_categoria = mysqli_insert_id($ret);
	}

}
