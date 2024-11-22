<?php  
class Movimiento{
	//DECLARACION DE VARIABLES
	private $ID_Movimiento;
	private $Fecha;
	private $Fecha_Creacion;
	private $ID_Persona;
	private $ID_Motivo_1;
	private $ID_Motivo_2;
	private $ID_Motivo_3;
	private $ID_Motivo_4;
	private $ID_Motivo_5;
	private $Observaciones;
	private $ID_Responsable;
	private $ID_Responsable_2;
	private $ID_Responsable_3;
	private $ID_Responsable_4;
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
			$xID_Motivo_1=null,
			$xID_Motivo_2=null,
			$xID_Motivo_3=null,
			$xID_Motivo_4=null,
			$xID_Motivo_5=null,
			$xObservaciones=null,
			$xID_Responsable=null,
			$xID_Responsable_2=null,
			$xID_Responsable_3=null,
			$xID_Responsable_4=null,
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
			$this->ID_Motivo_1 = $xID_Motivo_1;
			$this->ID_Motivo_2 = $xID_Motivo_2;
			$this->ID_Motivo_3 = $xID_Motivo_3;
			$this->ID_Motivo_4 = $xID_Motivo_4;
			$this->ID_Motivo_5 = $xID_Motivo_5;
			$this->Observaciones = $xObservaciones;
			$this->ID_Responsable = $xID_Responsable;
			$this->ID_Responsable_2 = $xID_Responsable_2;
			$this->ID_Responsable_3 = $xID_Responsable_3;
			$this->ID_Responsable_4 = $xID_Responsable_4;
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
			$this->coneccion_base, 
			$consultar_usuario) or die("Problemas al consultar filtro Usuario");
			$ret = mysqli_fetch_assoc($ejecutar_consultar_persona);
			if (!is_null($ret)) {
				$mov_id_movimiento = $ret["id_movimiento"];
				$mov_fecha = $ret["fecha"];
				$mov_id_persona = $ret["id_persona"];
				$mov_motivo_1 = $ret["motivo_1"];
				$mov_motivo_2 = $ret["motivo_2"];
				$mov_motivo_3 = $ret["motivo_3"];
				$mov_motivo_4 = $ret["motivo_4"];
				$mov_motivo_5 = $ret["motivo_5"];
				$mov_observaciones = $ret["observaciones"];
				$mov_id_resp = $ret["id_resp"];
				$mov_id_resp_2 = $ret["id_resp_2"];
				$mov_id_resp_3 = $ret["id_resp_3"];
				$mov_id_resp_4 = $ret["id_resp_4"];
				$mov_id_centro = $ret["id_centro"];
				$mov_id_otrainstitucion = $ret["id_otrainstitucion"];
				$mov_estado = $ret["estado"];
				$mov_fecha_creacion = $ret["fecha_creacion"];

				$this->id_movimiento = $mov_id_movimiento;
				$this->motivo_1 = $mov_motivo_1;
				$this->motivo_2 = $mov_motivo_2;
				$this->motivo_3 = $mov_motivo_3;
				$this->motivo_4 = $mov_motivo_4;
				$this->motivo_5 = $mov_motivo_5;
				$this->Fecha = (($mov_fecha) ? $mov_fecha : null);
				$this->Observaciones = (($mov_observaciones) ? $xObservaciones : null);
				$this->ID_Responsable = (($mov_id_resp) ? $mov_id_resp : null);
				$this->ID_Responsable_2 = (($mov_id_resp_2) ? $mov_id_resp_2  : null);
				$this->ID_Responsable_3 = (($mov_id_resp_3) ? $mov_id_resp_3 : null);
				$this->ID_Responsable_4 = (($mov_id_resp_4) ? $mov_id_resp_4 : null);
				$this->mov_id_centro = (($mov_id_centro) ? $mov_id_centro : null);
				$this->mov_id_otrainstitucion = (($mov_id_otrainstitucion) ?  $mov_id_otrainstitucion : null);
				$this->Fecha_Creacion = (($mov_fecha_creacion) ? $mov_fecha_creacion : null);
				$this->ID_Persona = (($mov_id_persona) ? $mov_id_persona : null);
				$this->estado = (($mov_estado) ? $mov_estado : null);
			}
		}
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

	public function setID_Motivo_1($xID_Motivo_1){
		$this->ID_Motivo_1 = $xID_Motivo_1;
	}

	public function setID_Motivo_2($xID_Motivo_2){
		$this->ID_Motivo_2 = $xID_Motivo_2;
	}

	public function setID_Motivo_3($xID_Motivo_3){
		$this->ID_Motivo_3 = $xID_Motivo_3;
	}

	public function setObservaciones($xObservaciones){
		$this->Observaciones = $xObservaciones;
	}

	public function setID_Responsable($xID_Responsable){
		$this->ID_Responsable = $xID_Responsable;
	}

	public function setID_Responsable_2($xID_Responsable_2){
		$this->ID_Responsable_2 = $xID_Responsable_2;
	}

	public function setID_Responsable_3($xID_Responsable_3){
		$this->ID_Responsable_3 = $xID_Responsable_3;
	}

	public function setID_Responsable_4($xID_Responsable_4){
		$this->ID_Responsable_4 = $xID_Responsable_4;
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

	public function getID_Motivo_1(){
		return $this->ID_Motivo_1;
	}

	public function getID_Motivo_2(){
		return $this->ID_Motivo_2;
	}

	public function getID_Motivo_3(){
		return $this->ID_Motivo_3;
	}

	public function getID_Motivo_4(){
		return $this->ID_Motivo_4;
	}

	public function getID_Motivo_5(){
		return $this->ID_Motivo_5;
	}

	public function getObservaciones(){
		return $this->Observaciones;
	}

	public function getID_Responsable(){
		return $this->ID_Responsable;
	}

	public function getID_Responsable_2(){
		return $this->ID_Responsable_2;
	}

	public function getID_Responsable_3(){
		return $this->ID_Responsable_3;
	}

	public function getID_Responsable_4(){
		return $this->ID_Responsable_4;
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

	public function udpate(){
		$Consulta = "udpate movimiento
					 set fecha = '" . $this->getFecha()."',
						 id_persona = " . $this->getID_Persona() . ", 
						 motivo_1 = " . $this->getID_Motivo_1() . ",
						 motivo_2 = " . $this->getID_Motivo_2().",
						 motivo_3 = " . $this->getID_Motivo_3() . ",
						 motivo_4 = '" . $this->getID_Motivo_4() . ",
						 motivo_5 = '" . $this->getID_Motivo_5() . ",
						 observaciones = '" . $this->getObservaciones() . "',
						 id_resp = " . $this->getID_Responsable() . ",
						 id_resp_2 = " . $this->getID_Responsable_2() . ",
						 id_resp_3 = " . $this->getID_Responsable_3() . ",
						 id_resp_4 = " . $this->getID_Responsable_4() . ",
						 id_centro = " . $this->getID_Centro() . ",
						 id_otrainstitucion = " . $this->getID_OtraInstitucion() . ",
						 estado = " . $this->getEstado() . "
					 where id_movimiento = " . $this->getID_Movimiento();
	}
	public function save(){
		$consulta = "insert into movimiento(
											fecha,
											id_persona,
											fecha_creacion,
											motivo_1,
											motivo_2,
											motivo_3,
											motivo_4,
											motivo_5,
											observaciones,
											id_resp,
											id_resp_2,
											id_resp_3,
											id_resp_4,
											id_centro,
											id_otrainstitucion,
											estado) 
				values(
						" . (($this->getFecha()) ? "'" . $this->getFecha() . "'" : "null") . ",
						" . (($this->getID_Persona()) ? $this->getID_Persona() : "null") . ",
						" . (($this->getFecha_Creacion()) ? "'" . $this->getFecha_Creacion() . "'" : "null") . ",
						" . (($this->getID_Motivo_1()) ? $this->getID_Motivo_1() : "null") . ",
						" . (($this->getID_Motivo_2()) ? $this->getID_Motivo_2() : "null") . ",
						" . (($this->getID_Motivo_3()) ? $this->getID_Motivo_3() : "null") . ",
						" . (($this->getID_Motivo_4()) ? $this->getID_Motivo_4() : "null") . ",
						" . (($this->getID_Motivo_5()) ? $this->getID_Motivo_5() : "null") . ",
						" . (($this->getObservaciones()) ? "'" . $this->getObservaciones() . "'" : "null") .",
						" . (($this->getID_Responsable()) ? $this->getID_Responsable() : "null") .",
						" . (($this->getID_Responsable_2()) ? $this->getID_Responsable_2() : "null") .",
						" . (($this->getID_Responsable_3()) ? $this->getID_Responsable_3() : "null") .",
						" . (($this->getID_Responsable_4()) ? $this->getID_Responsable_4() : "null") .",
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
}