<?php 

require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");

class Solicitud_EliminarCategoria
{
    //DECLARACION DE VARIABLES
    private $ID;
    private $Fecha;
    private $Categoria;
    private $Cod_Categoria;
    private $Estado;
    private $ID_Usuario;
    private $ID_Categoria;
    private $coneccion;
    public static function get_categorias()
    {
        $con = new Conexion();
        $con->OpenConexion();
        $consulta = "select * 
                    from solicitudes_eliminarcategorias
                    where accountid is not null";
        $rs = mysqli_query($con->Conexion,$consulta) or die("Problemas al consultar las acciones.");
        $lista_acciones = [];
        while ($ret = mysqli_fetch_assoc($rs)) {
            $row["ID"] = ((!empty($ret["ID"])) ? $ret["accountid"] : null);
            $row["Fecha"] = ((!empty($ret["Fecha"])) ? $ret["Detalles"] : null);
            $row["Categoria"] = ((!empty($ret["Categoria"])) ? $ret["Fecha"] : null);
            $row["Cod_Categoria"] = (!empty($ret["Cod_Categoria"])) ? $ret["ID_TipoAccion"] : null;
            $lista_acciones[] = $row;
        }
        return $lista_acciones;
    }

    public static function get_id_categoria_sl($coneccion, $id)
    {

        $consulta = "SELECT *
						 FROM solicitudes_eliminarcategorias 
						 WHERE ID_Categoria = " . $id . " 
						   AND Estado = 1";
        $rs = mysqli_query($coneccion->Conexion,$consulta) or die("Problemas al consultar las acciones.");
        $ret = mysqli_fetch_assoc($rs);
        if (!$ret) $id_categoria = $ret["ID_Categoria"];
        return $id_categoria;
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

    public function setCategoria($xCategoria)
    {
        $this->Categoria = $xCategoria;
    }

    public function setCod_Categoria($xCod_Categoria)
    {
        $this->Cod_Categoria = $xCod_Categoria;
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

    public function getCategoria()
    {
        return $this->Categoria;
    }

    public function getCod_Categoria()
    {
        return $this->Cod_Categoria;
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


    //METODO CONSTRUCTOR
    public function __construct(
                                $xID = null,
                                $xFecha = null,
                                $xCategoria = null,
                                $xCod_Categoria = null,
                                $xEstado = null,
                                $xID_Usuario = null,
                                $xID_Categoria = null,
                                $xConeccion = null
                                ) {
        $this->coneccion = $xConeccion;
        if (empty($xID)) {
            $this->ID = $xID;
            $this->Fecha = $xFecha;
            $this->Categoria = $xCategoria;
            $this->Cod_Categoria = $xCod_Categoria;
            $this->Estado = $xEstado;
            $this->ID_Usuario = $xID_Usuario;
            $this->ID_Categoria = $xID_Categoria;
        } else {
			$consulta = "SELECT *
						 FROM solicitudes_eliminarcategorias 
						 WHERE ID = " . $xID . " 
						   AND Estado = 1";
			$ejec = mysqli_query(
				$this->coneccion->Conexion,
				$consulta) or die("Problemas al consultar filtro solicitudes_modificarcategorias");
			$ret = mysqli_fetch_assoc($ejec);

            $this->ID = $xID;
            $this->Fecha = $ret["Fecha"];
            $this->Cod_Categoria = $ret["Cod_Categoria"];
            $this->Categoria = $ret["Categoria"];
            $this->Estado = $ret["Estado"];
            $this->ID_Usuario = $ret["ID_Usuario"];
            $this->ID_Categoria = $ret["ID_Categoria"];
               
        }
    }

    public function save()
    {
        $consulta = "INSERT INTO solicitudes_eliminarcategorias(
                                                                 Fecha,
                                                                 Cod_Categoria,
                                                                 Categoria,
                                                                 
                                                                 Estado,
                                                                 ID_Usuario,
                                                                 ID_Categoria
                                                                 ) values (
                                                                      " . (($this->getFecha()) ? "'" . $this->getFecha() . "'" : "null") . ",
                                                                       " . (($this->getCod_Categoria()) ? "'" . $this->getCod_Categoria() . "'" : "null") . ",
                                                                       " . (($this->getCategoria()) ? "'" . $this->getCategoria() . "'" : "null") . ",
                                                                       " . (($this->getEstado()) ? $this->getEstado() :  "null")  . ",
                                                                       " . (($this->getID_Usuario()) ? $this->getID_Usuario() :  "null")  . ",
                                                                       " . (($this->getID_Categoria()) ? $this->getID_Categoria() :  "null")  . "
                                                                )";
        $MensajeError = "No se pudo enviar la solicitud";
        mysqli_query($this->coneccion->Conexion, $consulta) or die($MensajeError);
		$this->ID = mysqli_insert_id($this->coneccion->Conexion);

    }

    function delete()
    {
        $consulta = "delete solicitudes_eliminarcategorias
                     where ID = " . $this->getID();
        $MensajeError = "No se pudo del la solicitud";
        mysqli_query($this->coneccion->Conexion, $consulta) or die($MensajeError);

    }
}
