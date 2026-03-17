<?php 
class Solicitud_CrearCategoria
{
    //DECLARACION DE VARIABLES
    private $coneccion_base;
    private $ID;
    private $Fecha;
    private $Codigo;
    private $Categoria;
    private $ID_Forma;
    private $Color;
    private $Estado;
    private $ID_Usuario;

    //METODO CONSTRUCTOR
    public function __construct(
                                $coneccion_base=null,
                                $xID=null,
                                $xFecha=null,
                                $xCodigo=null,
                                $xCategoria=null,
                                $xID_Forma=null,
                                $xColor=null,
                                $xEstado=null,
                                $xID_Usuario=null,
    ) {
        $this->coneccion_base = $coneccion_base;
        if (!$xID) {
            $this->Fecha = $xFecha;
            $this->Codigo = $xCodigo;
            $this->Categoria = $xCategoria;
            $this->ID_Forma = $xID_Forma;
            $this->Color = $xColor;
            $this->Estado = $xEstado;
            $this->ID_Usuario = $xID_Usuario;
        } else {
			$consulta = "SELECT *
						 FROM solicitudes_crearcategorias 
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
            $this->Color = $ret["Color"];
            $this->Estado = $ret["Estado"];
            $this->ID_Usuario = $ret["ID_Usuario"];
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

    public function setColor($xColor)
    {
        $this->Color = $xColor;
    }

    public function setEstado($xEstado)
    {
        $this->Estado = $xEstado;
    }

    public function setID_Usuario($xID_Usuario)
    {
        $this->ID_Usuario = $xID_Usuario;
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

    public function getColor()
    {
        return $this->Color;
    }

    public function getEstado()
    {
        return $this->Estado;
    }

    public function getID_Usuario()
    {
        return $this->ID_Usuario;
    }

    function delete()
    {
        $consulta = "delete solicitudes_crearcategorias
                     where ID = " . $this->getID();
        $MensajeError = "No se pudo del la solicitud";
        mysqli_query($this->coneccion_base->Conexion,$consulta) or die($MensajeError);
    }

    public function save()
    {
        $consulta = "INSERT INTO solicitudes_crearcategorias(
                                                                 Fecha,
                                                                 Codigo,
                                                                 Categoria,
                                                                 ID_Forma,
                                                                 Color,
                                                                 Estado,
                                                                 ID_Usuario,
                                                                 ) values (
                                                                      '" . (($this->getFecha()) ? $this->getFecha() : "null") . "',
                                                                       " . (($this->getCodigo()) ? "'" . $this->getCodigo() . "'" : "null") . ",
                                                                       " . (($this->getCategoria()) ? "'" . $this->getCategoria() . "'" : "null") . ",
                                                                       " . (($this->getID_Forma()) ? $this->getID_Forma() : "null") .  ",
                                                                       " . (($this->getColor()) ?  "'" . $this->getColor() . "'" : "null") . ",
                                                                       " . (($this->getEstado()) ? $this->getEstado() :  "null")  . ",
                                                                       " . (($this->getID_Usuario()) ? $this->getID_Usuario() :  "null")  . "
                                                                )";
        $MensajeError = "No se pudo enviar la solicitud";
        mysqli_query($this->coneccion_base->Conexion,$consulta) or die($MensajeError);
		$this->ID = mysqli_insert_id($this->coneccion_base->Conexion);
    }
}
