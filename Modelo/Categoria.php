<?php  
class Categoria {
//DECLARACION DE VARIABLES
	private $ID_Categoria;
	private $Cod_Categoria;
	private $Categoria;
	private $ID_Forma;
	private $Color;
	private $Tipo_Categoria;
	private $orden;
	private $Conecction;
	private $Estado;	

	//METODO CONSTRUCTOR
	public function __construct(
		$xID_Categoria=null,
		$xCod_Categoria=null,
		$xCategoria=null,
		$xID_Forma=null,
		$xColor=null,
		$xConecction=null,
		$xTipo_Categoria=null,
		$xOrden=null,
		$xEstado=null
	){
		$this->Conecction = $xConecction;

		if (!$xID_Categoria) {
			$this->Cod_Categoria = $xCod_Categoria;
			$this->Categoria = $xCategoria;
			$this->ID_Forma = $xID_Forma;
			$this->Color = $xColor;
			$this->orden = $xOrden;
			$this->Tipo_Categoria = $xTipo_Categoria;
			$this->Estado = ($xEstado) ? $xEstado : 1;
		} else {
			$consultar = "select *
						  from categoria 
						  where id_categoria = " . $xID_Categoria . " 
							and estado = 1";
			$ejecutar_consultar = mysqli_query(
				$this->Conecction->Conexion,
				$consultar) or die("Problemas al consultar filtro categirias");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_categoria = $ret["categoria"];
				$row_id_categoria = $ret["id_categoria"];
				$row_cod_categoria = $ret["cod_categoria"];
				$row_estado = $ret["estado"];
				$row_id_forma = $ret["ID_Forma"];
				$row_color = $ret["color"];
				$row_tipo_categoria = $ret["tipo_categoria"];
				$row_orden = $ret["orden"];
				
				$this->Categoria = $row_categoria;
				$this->ID_Categoria = $row_id_categoria;
				$this->archivo = $row_id_categoria;
				$this->Estado = ($row_estado) ? $row_estado : 0;
				$this->Cod_Categoria = $row_cod_categoria;
				$this->ID_Forma = $row_id_forma;
				$this->Color = $row_color;
				$this->Tipo_Categoria = $row_tipo_categoria;
				$this->orden = $row_orden;
			}			
		}
	}

    public static function exist_categoria($connection, $id_categoria) 
    {
        $consultar = "select *
                        from categoria 
                        where id_categoria = " . $id_categoria . "
                          and estado = 1";
        $ejecutar_consultar = mysqli_query(
            $connection->Conexion,
            $consultar) or die("Problemas al consultar la categoria");
        $num_rows = mysqli_num_rows($ejecutar_consultar);
        return $num_rows;
    }

	//METODOS SET
	public function setID_Categoria($xID_Categoria)
	{
		$this->ID_Categoria = $xID_Categoria;
	}

	public function setCod_Categoria($xCod_Categoria)
	{
		$this->Cod_Categoria = $xCod_Categoria;
	}

	public function setCategoria($xCategoria)
	{
		$this->Categoria = $xCategoria;
	}

	public function setID_Forma($xID_Forma)
	{
		$this->ID_Forma = $xID_Forma;
	}
	public function setTipo_Categoria($xTipo_Categoria)
	{
		$this->Tipo_Categoria = $xTipo_Categoria;
	}
	public function setOrden($xOrden)
	{
		return $this->orden = $xOrden;
	}

	public function setColor($xColor)
	{
		$this->Color = $xColor;
	}

	public function setEstado($xEstado)
	{
		$this->Estado = $xEstado;
	}

	//METODOS GET
	public function getID_Categoria()
	{
		return $this->ID_Categoria;
	}

	public function getCod_Categoria()
	{
		return $this->Cod_Categoria;
	}

	public function getCategoria()
	{
		return $this->Categoria;
	}

	public function getID_Forma()
	{
		return $this->ID_Forma;
	}
	public function getTipo_Categoria()
	{
		return $this->Tipo_Categoria;
	}
	public function getOrden()
	{
		return $this->orden;
	}

	public function getColor()
	{
		return $this->Color;
	}
	public function getEstado()
	{
		return $this->Estado;
	}
	public function delete()
	{
		$this->Estado = 0;

	}
	public function update(){
		$consulta = "update categoria
					 set tipo_categoria = " . (($this->getTipo_Categoria()) ? $this->getTipo_Categoria() : "null") . ",
						 categoria = " . (($this->getCategoria()) ? "'" . $this->getCategoria() . "'": "null") . ", 
						 cod_categoria = " . (($this->getCod_Categoria()) ? "'" . $this->getCod_Categoria() . "'": "null") . ", 
						 ID_Forma = " . (($this->getID_Forma()) ? "'" . $this->getID_Forma() . "'" : "null") .",
						 orden = " . (($this->getOrden()) ? $this->getOrden() : "null") . ",
						 color = " . (($this->getColor()) ? "'" . $this->getColor() . "'": "null") . ",
						 estado = " . (($this->getEstado()) ? $this->getEstado() : "0") . "
					 where id_categoria = " . $this->getID_Categoria();
		$mensaje_error = "No se pudo modificar la categoria";
		$ret = mysqli_query($this->Conecction->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}
}
?>