<?php 
class Solicitud_ModificarCategoria
{
    //DECLARACION DE VARIABLES
    private $coneccion_base;
    private $ID;
    private $Fecha;
    private $Codigo;
    private $Categoria;
    private $ID_Forma;
    private $NuevoColor;
    private $Estado;
    private $ID_Usuario;
    private $ID_Categoria;

    //METODO CONSTRUCTOR
    public function __construct(
                                $coneccion_base=null,
                                $xID=null,
                                $xFecha=null,
                                $xCodigo=null,
                                $xCategoria=null,
                                $xID_Forma=null,
                                $xNuevoColor=null,
                                $xEstado=null,
                                $xID_Usuario=null,
                                $xID_Categoria=null
    ){
        $this->coneccion_base = $coneccion_base;
        if (!$xID) {
            $this->Fecha = $xFecha;
            $this->Codigo = $xCodigo;
            $this->Categoria = $xCategoria;
            $this->ID_Forma = $xID_Forma;
            $this->NuevoColor = $xNuevoColor;
            $this->Estado = $xEstado;
            $this->ID_Usuario = $xID_Usuario;
            $this->ID_Categoria = $xID_Categoria;
        } else {
			$consulta = "SELECT *
						 FROM solicitudes_modificarcategorias 
						 WHERE ID = " . $xID . " 
						   AND Estado = 1";
			$ejec = mysqli_query(
				$this->coneccion_base->Conexion,
				$consulta) or die("Problemas al consultar filtro solicitudes_modificarcategorias");
			$ret = mysqli_fetch_assoc($ejec);

            $this->ID = $xID;
            $this->Fecha = $ret["Fecha"];
            $this->Codigo = $ret["Codigo"];
            $this->Categoria = $ret["Categoria"];
            $this->ID_Forma = $ret["ID_Forma"];
            $this->NuevoColor = $ret["NuevoColor"];
            $this->Estado = $ret["Estado"];
            $this->ID_Usuario = $ret["ID_Usuario"];
            $this->ID_Categoria = $ret["ID_Categoria"];
        }
    }

    //METODOS SET
    public function setID($xID)
    {
        $this->ID = $xID;
    }

    public function setFecha($xFecha)
    {
        $this->Fecha = $xFecha;
    }

    public function setCodigo($xCodigo)
    {
        $this->Codigo = $xCodigo;
    }

    public function setCategoria($xCategoria)
    {
        $this->Categoria = $xCategoria;
    }

    public function setID_Forma($xID_Forma)
    {
        $this->ID_Forma = $xID_Forma;
    }

    public function setNuevoColor($xNuevoColor)
    {
        $this->NuevoColor = $xNuevoColor;
    }

    public function setEstado($xEstado)
    {
        $this->Estado = $xEstado;
    }

    public function setID_Usuario($xID_Usuario)
    {
        $this->ID_Usuario = $xID_Usuario;
    }

    public function setID_Categoria($xID_Categoria)
    {
        $this->ID_Categoria = $xID_Categoria;
    }

    //METODOS GET
    public function getID()
    {
        return $this->ID;
    }

    public function getFecha()
    {
        return $this->Fecha;
    }

    public function getCodigo()
    {
        return $this->Codigo;
    }

    public function getCategoria()
    {
        return $this->Categoria;
    }

    public function getID_Forma()
    {
        return $this->ID_Forma;
    }

    public function getNuevoColor()
    {
        return $this->NuevoColor;
    }

    public function getEstado()
    {
        return $this->Estado;
    }

    public function getID_Usuario()
    {
        return $this->ID_Usuario;
    }

    public function getID_Categoria()
    {
        return $this->ID_Categoria;
    }

    public function save()
    {
        $consulta = "INSERT INTO solicitudes_modificarcategorias(
                                                                 Fecha,
                                                                 Codigo,
                                                                 Categoria,
                                                                 ID_Forma,
                                                                 NuevoColor,
                                                                 Estado,
                                                                 ID_Usuario,
                                                                 ID_Categoria
                                                                 ) values (
                                                                      '" . (($this->getFecha()) ? $this->getFecha() : "null") . "',
                                                                       " . (($this->getCodigo()) ? "'" . $this->getCodigo() . "'" : "null") . ",
                                                                       " . (($this->getCategoria()) ? "'" . $this->getCategoria() . "'" : "null") . ",
                                                                       " . (($this->getID_Forma()) ? $this->getID_Forma() : "null") .  ",
                                                                       " . (($this->getNuevoColor()) ?  "'" . $this->getNuevoColor() . "'" : "null") . ",
                                                                       " . (($this->getEstado()) ? $this->getEstado() :  "null")  . ",
                                                                       " . (($this->getID_Usuario()) ? $this->getID_Usuario() :  "null")  . ",
                                                                       " . (($this->getID_Categoria()) ? $this->getID_Categoria() :  "null")  . "
                                                                )";
        $MensajeError = "No se pudo enviar la solicitud";
        mysqli_query($this->coneccion_base->Conexion,$consulta) or die($MensajeError);
		$this->ID = mysqli_insert_id($this->coneccion_base->Conexion);
    }
}
