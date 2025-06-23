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
			$this->Estado = $xEstado;
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
				$this->archivo = $row_id_categoria;
				$this->estado = $row_estado;
				$this->Cod_Categoria = $row_cod_categoria;
				$this->ID_Forma = $row_id_forma;
				$this->Color = $row_color;
				$this->Tipo_Categoria = $row_tipo_categoria;
				$this->orden = $row_orden;
			}			
		}
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

	public function getColor()
	{
		return $this->Color;
	}

	public function getEstado()
	{
		return $this->Estado;
	}
}
?>