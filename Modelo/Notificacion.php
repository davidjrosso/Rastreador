<?php  
class Notificacion implements JsonSerializable
{
	//DECLARACION DE VARIABLES
	private $id_notificacion;
	private $detalle;
	private $fecha;
	private $expira;
	private $coneccion_base;
    private $estado;

	public function __construct(
			$coneccion_base=null,
			$id_notificacion=null,
			$detalle=null,
            $fecha=null,
            $expira=null,
			$estado=null
	) {
		$this->coneccion_base = $coneccion_base;
		if (!$id_notificacion) {
			$this->id_notificacion = $id_notificacion;
			$this->detalle = $detalle;
            $this->fecha = $fecha;
            $this->expira = $expira;
			$this->estado = $estado;
		} else {
			$consultar = "select *
                          from notificaciones 
                          where ID_Notificacion = " . $id_notificacion . " 
                            and Estado = 1";
			$ejecutar_consultar = mysqli_query(
			$this->coneccion_base->Conexion, 
			$consultar) or die("Problemas al consultar filtro centro");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_id_notificacion = $ret["ID_Notificacion"];
				$row_detalle = $ret["Detalle"];
                $row_fecha = $ret["Fecha"];
				$row_expira = $ret["Expira"];
				$row_estado = $ret["Estado"];

				$this->id_notificacion = $row_id_notificacion;
				$this->detalle = $row_detalle;
				$this->fecha = $row_fecha;
				$this->expira = $row_expira;
				$this->estado = $row_estado;
			}
		}
	}

	public static function is_exist($coneccion, $id_notificacion)
	{
		$consulta = "select * 
					 from notificaciones 
					 where ID_Notificacion = $id_notificacion 
					   and Estado = 1";
		$mensaje_error = "Hubo un problema al consultar los registros para validar";
		$Ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		) or die(
			$mensaje_error
		);
		$is_multiple = (mysqli_num_rows($Ret) >= 1);
		return $is_multiple;
	}

	// METODOS SET
	public function set_id_notificacion($id_notificacion)
    {
		$this->id_notificacion = $id_notificacion;
	}

	public function set_detalle($detalle)
    {
		$this->detalle = $detalle;
	}
	public function set_fecha($fecha)
    {
		$this->fecha = $fecha;
	}
	public function set_expira($expira)
    {
		$this->expira = $expira;
	}

	public function set_estado($estado)
    {
		$this->estado = $estado;
	}

	public function set_coneccion_base($coneccion_base)
    {
		$this->coneccion_base = $coneccion_base;
	}

	//METODOS GET
	public function get_id_notificacion()
    {
		return $this->id_notificacion;
	}

	
    public function get_detalle(){
		return $this->detalle;
	}
	public function get_fecha()
    {
		return $this->fecha;
	}
    public function get_expira()
    {
		return $this->expira;
	}

	public function get_estado()
    {
		return $this->estado;
	}

	public function get_coneccion_base()
    {
		return $this->coneccion_base;
	}

	public function jsonSerialize() 
	{
		return [
			'id_notificacion' => $this->id_notificacion,
			'detalle' => $this->detalle,
            'fecha' => $this->fecha,
			'expira' => $this->expira,
			'estado' => $this->estado
		];
	}

	public function udpate()
    {
		$consulta = "update notificaciones
					 set Detalle = " . (($this->get_detalle()) ? "'" . $this->get_detalle() . "'" : "null") . ", 
                         Fecha = " . (($this->get_fecha()) ? "'" . $this->get_fecha() . "'" : "null") . ", 
                         Expira = " . (($this->get_expira()) ? "'" . $this->get_expira() . "'" : "null") . ", 
						 Estado = " . (($this->get_estado()) ? $this->get_estado() : "null") . "
					 where ID_Notificacion = " . $this->get_id_notificacion();
		$mensaje_error = "No se pudo modificar la notificacion";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}
	public function save()
    {
		$consulta = "insert into notificaciones (
                                                ID_Notificacion,
                                                Detalle,
                                                Fecha,
                                                Expira,
                                                Estado
                                                ) 
				values(
						" . (($this->get_id_notificacion()) ? $this->get_id_notificacion() : "null") . ",
						" . (($this->get_detalle()) ? "'" . $this->get_detalle() . "'" : "null") . ",
						" . (($this->get_fecha()) ? "'" . $this->get_fecha() . "'" : "null") . ",
						" . (($this->get_expira()) ? "'" . $this->get_expira() . "'" : "null") . ",
						" . (($this->get_estado()) ? $this->get_estado() : "null") . "
						)";
		$mensaje_error = "No se pudo insertar la notificacion";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
		$this->id_centro = mysqli_insert_id($this->coneccion_base->Conexion);
	}
}