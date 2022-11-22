<?php  
class Categoria{
//DECLARACION DE VARIABLES
private $ID_Categoria;
private $Cod_Categoria;
private $Categoria;
private $ID_Forma;
private $Color;
private $Estado;	

//METODOS SET
public function setID_Categoria($xID_Categoria){
	$this->ID_Categoria = $xID_Categoria;
}

public function setCod_Categoria($xCod_Categoria){
	$this->Cod_Categoria = $xCod_Categoria;
}

public function setCategoria($xCategoria){
	$this->Categoria = $xCategoria;
}

public function setID_Forma($xID_Forma){
	$this->ID_Forma = $xID_Forma;
}

public function setColor($xColor){
	$this->Color = $xColor;
}

public function setEstado($xEstado){
	$this->Estado = $xEstado;
}

//METODOS GET
public function getID_Categoria(){
	return $this->ID_Categoria;
}

public function getCod_Categoria(){
	return $this->Cod_Categoria;
}

public function getCategoria(){
	return $this->Categoria;
}

public function getID_Forma(){
	return $this->ID_Forma;
}

public function getColor(){
	return $this->Color;
}

public function getEstado(){
	return $this->Estado;
}

//METODO CONSTRUCTOR
public function __construct($xID_Categoria,$xCod_Categoria,$xCategoria,$xID_Forma,$xColor,$xEstado){
	$this->ID_Categoria = $xID_Categoria;
	$this->Cod_Categoria = $xCod_Categoria;
	$this->Categoria = $xCategoria;
	$this->ID_Forma = $xID_Forma;
	$this->Color = $xColor;
	$this->Estado = $xEstado;
}

}
?>