<?php  
class Persona{
	//DECLARACION DE VARIABLES
	private $ID_Persona;
	private $Apellido;
	private $Nombre;
	private $DNI;
	private $Nro_Legajo;
	private $Edad;
	private $Meses;
	private $Fecha_Nacimiento;	
	private $Nro_Carpeta;
	private $Obra_Social;
	private $Domicilio;
	private $Barrio;
	private $Localidad;
	private $Circunscripcion;
	private $Seccion;
	private $Manzana;
	private $Lote;
	private $Familia;
	private $Observaciones;
	private $Cambio_Domicilio;
	private $Telefono;
	private $Mail;
	private $Estado;
	private $ID_Escuela;	
	private $Trabajo;

//METODOS SET
public function setID_Persona($xID_Persona){
	$this->ID_Persona = $xID_Persona;
}

public function setApellido($xApellido){
	$this->Apellido = $xApellido;
}

public function setNombre($xNombre){
	$this->Nombre = $xNombre;
}

public function setDNI($xDNI){
	$this->DNI = $xDNI;
}

public function setNro_Legajo($xNro_Legajo){
	$this->Nro_Legajo = $xNro_Legajo;
}
public function setEdad($xEdad){
	$this->Edad = $xEdad;
}

public function setMeses($xMeses){
	$this->Meses = $xMeses;
}

public function setFecha_Nacimiento($xFecha_Nacimiento){
	$this->Fecha_Nacimiento = $xFecha_Nacimiento;
}

public function setNro_Carpeta($xNro_Carpeta){
	$this->Nro_Carpeta = $xNro_Carpeta;
}

public function setObra_Social($xObra_Social){
	$this->Obra_Social = $xObra_Social;
}

public function setDomicilio($xDomicilio){
	$this->Domicilio = $xDomicilio;
}

public function setBarrio($xBarrio){
	$this->Barrio = $xBarrio;
}

public function setLocalidad($xLocalidad){
	$this->Localidad = $xLocalidad;
}

public function setCircunscripcion($xCircunscripcion){
	$this->Circunscripcion = $xCircunscripcion;
}

public function setSeccion($xSeccion){
	$this->Seccion = $xSeccion;
}

public function setManzana($xManzana){
	$this->Manzana = $xManzana;
}

public function setLote($xLote){
	$this->Lote = $xLote;
}

public function setFamilia($xFamilia){
	$this->Familia = $xFamilia;
}

public function setObservaciones($xObservaciones){
	$this->Observaciones = $xObservaciones;
}

public function setCamio_Domicilio($xCambio_Domicilio){
	$this->Cambio_Domicilio = $xCambio_Domicilio;
}

public function setTelefono($xTelefono){
	$this->Telefono = $xTelefono;
}

public function setMail($xMail){
	$this->Mail = $xMail;
}

public function setEstado($xEstado){
	$this->Estado = $xEstado;
}

public function setID_Escuela($xID_Escuela){
	$this->ID_Escuela = $xID_Escuela;
}

public function setTrabajo($xTrabajo){
	$this->Trabajo = $xTrabajo;
}

//METODOS GET
public function getID_Persona(){
	return $this->ID_Persona;
}

public function getApellido(){
	return $this->Apellido;
}

public function getNombre(){
	return $this->Nombre;
}

public function getDNI(){
	return $this->DNI;
}

public function getNro_Legajo(){
	return $this->Nro_Legajo;
}
public function getEdad(){
	return $this->Edad;
}

public function getMeses(){
	return $this->Meses;
}

public function getFecha_Nacimiento(){
	return $this->Fecha_Nacimiento;
}

public function getNro_Carpeta(){
	return $this->Nro_Carpeta;
}

public function getObra_Social(){
	return $this->Obra_Social;
}

public function getDomicilio(){
	return $this->Domicilio;
}

public function getCalle(){
	$LongString = strlen($this->Domicilio); 
	$StringDelimitado = chunk_split($this->Domicilio,$LongString - 4,"-");
	$PartesDireccion = explode("-", $StringDelimitado);
	$DomActual = $PartesDireccion[0];
	return $DomActual;
}

public function getNroCalle(){
	$LongString = strlen($this->Domicilio); 
	$StringDelimitado = chunk_split($this->Domicilio,$LongString - 4,"-");
	$PartesDireccion = explode("-", $StringDelimitado);
	$NroDomActual = (int) filter_var($PartesDireccion[1], FILTER_SANITIZE_NUMBER_INT);
	return $NroDomActual;
}

public function getBarrio(){
	return $this->Barrio;
}

public function getLocalidad(){
	return $this->Localidad;
}

public function getCircunscripcion(){
	return $this->Circunscripcion;
}

public function getSeccion(){
	return $this->Seccion;
}

public function getManzana(){
	return $this->Manzana;
}

public function getLote(){
	return $this->Lote;
}

public function getFamilia(){
	return $this->Familia;
}

public function getObservaciones(){
	return $this->Observaciones;
}

public function getCambio_Domicilio(){
	return $this->Cambio_Domicilio;
}

public function getTelefono(){
	return $this->Telefono;
}

public function getMail(){
	return $this->Mail;
}

public function getEstado(){
	return $this->Estado;
}

public function getID_Escuela(){
	return $this->ID_Escuela;
}

public function getTrabajo(){
	return $this->Trabajo;
}

public function __construct($xID_Persona,$xApellido,$xNombre,$xDNI,$xNro_Legajo,$xEdad,$xMeses,$xFecha_Nacimiento,$xNro_Carpeta,$xObra_Social,$xDomicilio,$xBarrio,$xLocalidad,$xCircunscripcion,$xSeccion,$xManzana,$xLote,$xFamilia,$xObservaciones,$xCambio_Domicilio,$xTelefono,$xMail,$xID_Escuela,$xEstado,$xTrabajo){
	$this->ID_Persona = $xID_Persona;
	$this->Apellido = $xApellido;
	$this->Nombre = $xNombre;
	$this->DNI = $xDNI;
	$this->Nro_Legajo = $xNro_Legajo;
	$this->Edad = $xEdad;
	$this->Meses = $xMeses;
	$this->Fecha_Nacimiento = $xFecha_Nacimiento;
	$this->Nro_Carpeta = $xNro_Carpeta;
	$this->Obra_Social = $xObra_Social;
	$this->Domicilio = $xDomicilio;
	$this->Barrio = $xBarrio;
	$this->Localidad = $xLocalidad;
	$this->Circunscripcion = $xCircunscripcion;
	$this->Seccion = $xSeccion;
	$this->Manzana = $xManzana;
	$this->Lote = $xLote;
	$this->Familia = $xFamilia;
	$this->Observaciones = $xObservaciones;
	$this->Cambio_Domicilio = $xCambio_Domicilio;
	$this->Telefono = $xTelefono;
	$this->Mail = $xMail;
	$this->ID_Escuela = $xID_Escuela;	
	$this->Estado = $xEstado;	
	$this->Trabajo = $xTrabajo;
}

}


?>