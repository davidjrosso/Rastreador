<?php  
class DtoMovimiento {
	//DECLARACION DE VARIABLES
	private $ID_Movimiento;
	private $Fecha;
	private $Apellido;
	private $Nombre;
	private $Motivo_1;
	private $Motivo_2;
	private $Motivo_3;
	private $Motivo_4;
	private $Motivo_5;
	private $id_persona;

	private $Observaciones;
	private $Responsable;
	private $CentroSalud;
	private $OtraInstitucion;

	public function __construct(
								$xID_Movimiento=null,
								$xFecha=null,
								$xApellido=null,
								$xNombre=null,
								$xMotivo_1=null,
								$xMotivo_2=null,
								$xMotivo_3=null,
								$xMotivo_4=null,
								$xMotivo_5=null,
								$xObservaciones=null,
								$xResponsable=null,
								$xCentroSalud=null,
								$xOtraInstitucion=null
	){
		$this->ID_Movimiento = $xID_Movimiento;
		$this->Fecha = $xFecha;
		$this->Apellido = $xApellido;
		$this->Nombre = $xNombre;
		$this->Motivo_1 = (!empty($xMotivo_1)) ? $xMotivo_1 : null;
		$this->Motivo_2 = (!empty($xMotivo_2)) ? $xMotivo_2 : null;
		$this->Motivo_3 = (!empty($xMotivo_3)) ? $xMotivo_3 : null;
		$this->Motivo_4 = (!empty($xMotivo_4)) ? $xMotivo_4 : null;
		$this->Motivo_5 = (!empty($xMotivo_5)) ? $xMotivo_5 : null;
		$this->Observaciones = $xObservaciones;
		$this->Responsable = $xResponsable;
		$this->CentroSalud = $xCentroSalud;
		$this->OtraInstitucion = $xOtraInstitucion;
	}

	// METODOS SET
	public function setID_Movimiento($xID_Movimiento){
		$this->ID_Movimiento = $xID_Movimiento;
	}

	public function setFecha($xFecha){
		$this->Fecha = $xFecha;
	}

	public function setApellido($xApellido){
		$this->Apellido = $xApellido;
	}

	public function setNombre($xNombre){
		$this->Nombre = $xNombre;
	}

	public function setMotivo_1($xMotivo_1){
		$this->Motivo_1 = $xMotivo_1;
	}

	public function setMotivo_2($xMotivo_2){
		$this->Motivo_2 = $xMotivo_2;
	}

	public function setMotivo_3($xMotivo_3){
		$this->Motivo_3 = $xMotivo_3;
	}

	public function setMotivo_4($xMotivo_3){
		$this->Motivo_3 = $xMotivo_3;
	}
	public function setMotivo_5($xMotivo_3){
		$this->Motivo_3 = $xMotivo_3;
	}
	public function setObservaciones($xObservaciones){
		$this->Observaciones = $xObservaciones;
	}

	public function setResponsable($xResponsable){
		$this->Responsable = $xResponsable;
	}

	public function setCentroSalud($xCentroSalud){
		$this->CentroSalud = $xCentroSalud;
	}

	public function setOtraInstitucion($xOtraInstitucion){
		$this->OtraInstitucion = $xOtraInstitucion;
	}

	public function setId_Persona($id_persona){
		$this->id_persona = $id_persona;
	}
	//METODOS GET
	public function getID_Movimiento(){
		return $this->ID_Movimiento;
	}

	public function getFecha(){
		return $this->Fecha;
	}

	public function getApellido(){
		return $this->Apellido;
	}

	public function getNombre(){
		return $this->Nombre;
	}

	public function getMotivo_1(){
		return $this->Motivo_1;
	}

	public function getMotivo_2(){
		return $this->Motivo_2;
	}

	public function getMotivo_3(){
		return $this->Motivo_3;
	}

	public function getMotivo_4(){
		return $this->Motivo_4;
	}

	public function getMotivo_5(){
		return $this->Motivo_5;
	}

	public function getObservaciones(){
		return $this->Observaciones;
	}

	public function getResponsable(){
		return $this->Responsable;
	}

	public function getCentroSalud(){
		return $this->CentroSalud;
	}

	public function getOtraInstitucion(){
		return $this->OtraInstitucion;
	}
	public function getId_Persona(){
		return $this->id_persona;
	}
}
