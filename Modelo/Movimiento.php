<?php  
class Movimiento implements JsonSerializable
{
	//DECLARACION DE VARIABLES
	private $ID_Movimiento;
	private $Fecha;
	private $Fecha_Creacion;
	private $ID_Persona;
	private $Observaciones;
	private $ID_Centro;
	private $ID_OtraInstitucion;
	private $Estado;
	private $coneccion_base;

	public function __construct(
			$coneccion_base=null,
			$xID_Movimiento=null,
			$xFecha=null,
			$Fecha_Creacion=null,
			$xID_Persona=null,
			$xObservaciones=null,
			$xID_Centro=null,
			$xID_OtraInstitucion=null,
			$xEstado=null
	) {
		$this->coneccion_base = $coneccion_base;
		$fecha_actual = DateTime::createFromFormat(format: 'Y-m-d', datetime: date('Y-m-d'));
		if (!$xID_Movimiento) {
			$this->ID_Movimiento = $xID_Movimiento;
			$this->Fecha = (($xFecha) ? $xFecha : $fecha_actual);
			$this->ID_Persona = $xID_Persona;
			$this->Observaciones = $xObservaciones;
			$this->ID_Centro = $xID_Centro;
			$this->ID_OtraInstitucion = $xID_OtraInstitucion;
			$this->Estado = $xEstado;
			$this->Fecha_Creacion = (($Fecha_Creacion) ? $Fecha_Creacion : $fecha_actual);
		} else {
			$consultar_usuario = "select *
									from movimiento 
									where id_movimiento = " . $xID_Movimiento . " 
									and estado = 1";
			$ejecutar_consultar_persona = mysqli_query(
			$this->coneccion_base->Conexion, 
			$consultar_usuario) or die("Problemas al consultar filtro Usuario");
			$ret = mysqli_fetch_assoc($ejecutar_consultar_persona);
			if (!is_null($ret)) {
				$mov_id_movimiento = $ret["id_movimiento"];
				$mov_fecha = $ret["fecha"];
				$mov_id_persona = $ret["id_persona"];
				$mov_observaciones = $ret["observaciones"];
				$mov_id_centro = $ret["id_centro"];
				$mov_id_otrainstitucion = $ret["id_otrainstitucion"];
				$mov_estado = $ret["estado"];
				$mov_fecha_creacion = $ret["fecha_creacion"];

				$this->ID_Movimiento = $mov_id_movimiento;
				$this->Fecha = (($mov_fecha) ? $mov_fecha : null);
				$this->Observaciones = (($mov_observaciones) ? $xObservaciones : null);
				$this->ID_Centro = (($mov_id_centro) ? $mov_id_centro : null);
				$this->ID_OtraInstitucion = (($mov_id_otrainstitucion) ?  $mov_id_otrainstitucion : null);
				$this->Fecha_Creacion = (($mov_fecha_creacion) ? $mov_fecha_creacion : null);
				$this->ID_Persona = (($mov_id_persona) ? $mov_id_persona : null);
				$this->Estado = (($mov_estado) ? $mov_estado : null);
			}
		}
	}

	public static function is_exist($coneccion, $id_movimiento)
	{
		$consulta = "select * 
					 from movimiento 
					 where id_movimiento = $id_movimiento 
					   and estado = 1";
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

	public static function is_exist_movimiento_fecha(
													 $coneccion, 
													 $fecha,
													 $id_persona
													 )
	{
		$consulta = "select * 
					 from movimiento 
					 where fecha = '$fecha'
					   and id_persona = $id_persona
					   and estado = 1";
		$mensaje_error = "Hubo un problema al consultar los registros para validar";
		$ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		) or die(
			$mensaje_error
		);
		$ret_query = mysqli_fetch_assoc($ret);
		$is_multiple = ((!empty($ret_query["id_movimiento"])) ? $ret_query["id_movimiento"] : 0);
		return $is_multiple;
	}

	// METODOS SET
	public function setID_Movimiento($xID_Movimiento){
		$this->ID_Movimiento = $xID_Movimiento;
	}

	public function setFecha($xFecha){
		$this->Fecha = $xFecha;
	}

	public function setID_Persona($xID_Persona){
		$this->ID_Persona = $xID_Persona;
	}

	public function setObservaciones($xObservaciones){
		$this->Observaciones = $xObservaciones;
	}

	public function setID_Centro($xID_Centro){
		$this->ID_Centro = $xID_Centro;
	}

	public function setID_OtraInstitucion($xID_OtraInstitucion){
		$this->ID_OtraInstitucion = $xID_OtraInstitucion;
	}

	public function setEstado($xEstado){
		$this->Estado = $xEstado;
	}

	public function set_coneccion_base($coneccion_base){
		$this->coneccion_base = $coneccion_base;
	}

	//METODOS GET
	public function getID_Movimiento(){
		return $this->ID_Movimiento;
	}

	public function getFecha(){
		return $this->Fecha;
	}

	public function getFecha_Creacion(){
		return $this->Fecha_Creacion;
	}

	public function getID_Persona(){
		return $this->ID_Persona;
	}

	public function getObservaciones(){
		return $this->Observaciones;
	}

	public function getID_Centro(){
		return $this->ID_Centro;
	}

	public function getID_OtraInstitucion(){
		return $this->ID_OtraInstitucion;
	}

	public function getEstado(){
		return $this->Estado;
	}

	public function get_coneccion_base(){
		return $this->coneccion_base;
	}

	public function jsonSerialize() 
	{
		return [
			'id_persona' => $this->ID_Persona,
			'estado' => $this->Estado,
			'observaciones' => $this->Observaciones,
			'id_centro' => $this->ID_Centro,
			'id_otrainstitucion' => $this->ID_OtraInstitucion,
			'id_movimiento' => $this->ID_Movimiento,
			'Fecha_Creacion' => $this->Fecha_Creacion,
			'Fecha' => $this->Fecha
		];
	}

	public function udpate(){
		$fecha = $this->getFecha_Creacion();
		$fecha_format = (($fecha) ? $fecha->format("Y-m-d") : "null");
		$consulta = "update movimiento
					 set fecha = " . (($this->getFecha()) ? "'" . $this->getFecha() . "'" : "null") .",
					 	 fecha_creacion = " . (($fecha_format) ? "'" . $fecha_format . "'" : "null") . ",
						 id_persona = " . (($this->getID_Persona()) ? $this->getID_Persona() : "null") . ", 
						 observaciones = " . (($this->getObservaciones()) ? "'" . $this->getObservaciones() . "'" : "null") . ",
						 id_centro = " . (($this->getID_Centro()) ? $this->getID_Centro() : "null") . ",
						 id_otrainstitucion = " . (($this->getID_OtraInstitucion()) ? $this->getID_OtraInstitucion() : "null") . ",
						 estado = " . (($this->getEstado()) ? $this->getEstado() : "1") . "
					 where id_movimiento = " . $this->getID_Movimiento();
		$mensaje_error = "No se pudo modificar el movimiento";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function save(){
		$consulta = "insert into movimiento(
											fecha,
											id_persona,
											fecha_creacion,
											observaciones,
											id_centro,
											id_otrainstitucion,
											estado) 
				values(
						" . (($this->getFecha()) ? "'" . $this->getFecha() . "'" : "null") . ",
						" . (($this->getID_Persona()) ? $this->getID_Persona() : "null") . ",
						" . (($this->getFecha_Creacion()) ? "'" . $this->getFecha_Creacion() . "'" : "null") . ",
						" . (($this->getObservaciones()) ? "'" . $this->getObservaciones() . "'" : "null") .",
						" . (($this->getID_Centro()) ? $this->getID_Centro() : "null") .",
						" . (($this->getID_OtraInstitucion()) ? $this->getID_OtraInstitucion() : "null") .",
						" . (($this->getEstado()) ? $this->getEstado() : "null") ."
						)";
		$mensaje_error = "No se pudo insertar el movimiento";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
		$this->ID_Movimiento = mysqli_insert_id($this->coneccion_base->Conexion);
	}

	public function delete()
	{
		$consulta = "update movimiento
					 set 	 estado = 0
					 where id_movimiento = " . $this->getID_Movimiento();
		$mensaje_error = "No se pudo modificar el movimiento";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}

	}

}