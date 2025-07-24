<?php
class Solicitud_Unificacion 
{
    //DECLARACION DE VARIABLES
    private $coneccion;
    private $ID_Solicitud;
    private $Fecha;
    private $ID_Registro_1;
    private $ID_Registro_2;
    private $ID_Usuario;
    private $Estado;
    private $TipoUnif;

    //METODOS CONSTRUCTORES
    public function __construct(
                                $xID_Solicitud=null,
                                $xFecha=null,
                                $xID_Registro_1=null,
                                $xID_Registro_2=null,
                                $xID_Usuario=null,
                                $xEstado=null,
                                $xTipoUnif=null,
                                $coneccion=null
    ){
		$this->coneccion = $coneccion;
        if (!$xID_Solicitud) {
            $this->ID_Solicitud = $xID_Solicitud;
            $this->Fecha = $xFecha;
            $this->ID_Registro_1 = $xID_Registro_1;
            $this->ID_Registro_2 = $xID_Registro_2;
            $this->ID_Usuario = $xID_Usuario;
            $this->Estado = ($xEstado) ? $xEstado : 1;
            $this->TipoUnif = $xTipoUnif;
        } else {
			$consultar = "select *
						  from solicitudes_unificacion 
						  where ID_Solicitud_Unificacion = " . $xID_Solicitud . " 
							and Estado = 1";
			$ejecutar_consultar = mysqli_query(
				$this->coneccion->Conexion,
				$consultar) or die("Problemas al consultar filtro solcitudes unificacion");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_id_solicitud_unificacion = $ret["ID_Solicitud_Unificacion"];
				$row_fecha = $ret["Fecha"];
				$row_id_registro_1 = $ret["ID_Registro_1"];
				$row_estado = $ret["Estado"];
				$row_id_registro_2 = $ret["ID_Registro_2"];
				$row_id_usuario = $ret["ID_Usuario"];
				$row_tipo_unif = $ret["ID_TipoUnif"];
				
				$this->ID_Solicitud = $row_id_solicitud_unificacion;
				$this->Fecha = $row_fecha;
				$this->ID_Registro_1 = $row_id_registro_1;
				$this->ID_Registro_2 = $row_id_registro_2;
				$this->Estado = ($row_estado) ? $row_estado : 0;
				$this->ID_Usuario = $row_id_usuario;
				$this->TipoUnif = $row_tipo_unif;
			}	
        }
    }

    public static function exist_categoria($connection, $ID_Solicitud_Unificacion) 
    {
        $consultar = "select *
                        from solicitudes_unificacion 
                        where ID_Solicitud_Unificacion = " . $ID_Solicitud_Unificacion . "
                          and Estado = 1";
        $ejecutar_consultar = mysqli_query(
            $connection->Conexion,
            $consultar) or die("Problemas al consultar la solicitudes de unificacion");
        $num_rows = mysqli_num_rows($ejecutar_consultar);
        return $num_rows;
    }

    //METODOS SET
    public function setID_Solicitud($xID_Solicitud)
    {
        $this->ID_Solicitud = $xID_Solicitud;
    }

    public function setFecha($xFecha)
    {
        $this->Fecha = $xFecha;
    }

    public function setID_Registro_1($xID_Registro_1)
    {
        $this->ID_Registro_1 = $xID_Registro_1;
    }

    public function setID_Registro_2($xID_Registro_2)
    {
        $this->setID_Registro_2 = $xID_Registro_2;
    }

    public function setID_Usuario($xID_Usuario)
    {
        $this->ID_Usuario = $xID_Usuario;
    }

    public function setEstado($xEstado)
    {
        $this->Estado = $xEstado;
    }

    public function setTipoUnif($xTipoUnif)
    {
        $this->TipoUnif = $xTipoUnif;
    }

    //METODOS GET
    public function getID_Solicitud()
    {
        return $this->ID_Solicitud;
    }

    public function getFecha()
    {
        return $this->Fecha;
    }

    public function getID_Registro_1()
    {
        return $this->ID_Registro_1;
    }

    public function getID_Registro_2()
    {
        return $this->ID_Registro_2;
    }

    public function getID_Usuario()
    {
        return $this->ID_Usuario;
    }

    public function getEstado()
    {
        return $this->Estado;
    }

    public function getTipoUnif()
    {
        return $this->TipoUnif;
    }

	public function delete(){
		$consulta = "UPDATE solicitudes_unificacion
					 SET Estado = 0
					 WHERE ID_Solicitud_Unificacion = " . $this->getID_Solicitud();
		$mensaje_error = "No se pudo modificar la solicitud de unificacion";
		$ret = mysqli_query($this->coneccion->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
        $this->Estado = 0;
	}

	public function update(){
		$consulta = "UPDATE solicitudes_unificacion
					 SET Fecha = " . (($this->getFecha()) ? "'" . $this->getFecha() . "'": "null") . ", 
						 ID_Registro_1 = " . (($this->getID_Registro_1()) ? $this->getID_Registro_1() : "null") . ", 
						 ID_Registro_2 = " . (($this->getID_Registro_2()) ? $this->getID_Registro_2() : "null") .",
						 ID_Usuario = " . (($this->getID_Usuario()) ? $this->getID_Usuario() : "null") . ",
						 ID_TipoUnif = " . (($this->getTipoUnif()) ? $this->getTipoUnif() : "null") . ",
						 Estado = " . (($this->getEstado()) ? $this->getEstado() : "0") . "
					 WHERE ID_Solicitud_Unificacion = " . $this->getID_Solicitud();
		$mensaje_error = "No se pudo modificar la solicitud de unificacion";
		$ret = mysqli_query($this->coneccion->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function save(){
		$consulta = "INSERT solicitudes_unificacion (Fecha, ID_Registro_1, ID_Registro_2, ID_Usuario, ID_TipoUnif, Estado)
					 VALUES (" . (($this->getFecha()) ? "'" . $this->getFecha() . "'": "null") . ", 
						     " . (($this->getID_Registro_1()) ? $this->getID_Registro_1() : "null") . ", 
						     " . (($this->getID_Registro_2()) ? $this->getID_Registro_2() : "null") .",
						     " . (($this->getID_Usuario()) ? $this->getID_Usuario() : "null") . ",
						     " . (($this->getTipoUnif()) ? $this->getTipoUnif() : "null") . ",
						     " . (($this->getEstado()) ? $this->getEstado() : "1") . 
                             ")";
		$mensaje_error = "No se pudo insertar la solicitud de unificacion";
		$ret = mysqli_query($this->coneccion->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
		$this->ID_Solicitud = mysqli_insert_id($this->coneccion->Conexion);
	}

}
