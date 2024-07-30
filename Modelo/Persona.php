<?php
require_once "Controladores/Conexion.php";

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
	if($LongString > 1){
	  $StringDelimitado = chunk_split($this->Domicilio,$LongString - 4,"-");
	  $PartesDireccion = explode("-", $StringDelimitado);
	  $DomActual = $PartesDireccion[0];
	  if(!preg_match("~[0-9]~", $PartesDireccion[1])){
	    $DomActual = $this->Domicilio;
	  } else {
		$NroCalle = $this->getNroCalle();
		if($NroCalle < 10000){
			$DomActual = substr($this->Domicilio, 0, $LongString - 5);
			if($NroCalle < 1000){
				$DomActual = substr($this->Domicilio, 0, $LongString - 4);
				if($NroCalle < 100){
					$DomActual = substr($this->Domicilio, 0, $LongString - 3);
					if($NroCalle < 10){
						$DomActual = substr($this->Domicilio, 0, $LongString - 2);
					}
				}
			}
		}
	  }
	} else{
	  $DomActual = null;
	}
	return $DomActual;
}

public function getNroCalle(){
	$LongString = strlen($this->Domicilio);
	if($LongString > 1){
	  $StringDelimitado = chunk_split($this->Domicilio,$LongString - 4,"-");
	  $PartesDireccion = explode("-", $StringDelimitado);
	  $NroDomActual = (int) filter_var($PartesDireccion[1], FILTER_SANITIZE_NUMBER_INT);
	  if($NroDomActual == 0){
		$NroDomActual = null;
	  }
	} else {
	  $NroDomActual = null;
	}
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

public function __construct(
	$ID_Persona = null,
	$xApellido = null,
	$xNombre = null,
	$xDNI = null,
	$xNro_Legajo = null,
	$xEdad = null,
	$xMeses = null,
	$xFecha_Nacimiento = null,
	$xNro_Carpeta = null,
	$xObra_Social = null,
	$xDomicilio = null,
	$xBarrio = null,
	$xLocalidad = null,
	$xCircunscripcion = null,
	$xSeccion = null,
	$xManzana = null,
	$xLote = null,
	$xFamilia = null,
	$xObservaciones = null,
	$xCambio_Domicilio  = null,
	$xTelefono = null,
	$xMail = null,
	$xID_Escuela = null,
	$xEstado = null,
	$xTrabajo = null
){
	if (!$ID_Persona) {
		$this->ID_Persona =$ID_Persona;
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
	} else {
		$Con = new Conexion();
        $Con->OpenConexion();
		$ConsultarPersona = "select *
							 from persona 
							 where ID_Persona = " . $ID_Persona . " 
							   and estado = 1";
		$EjecutarConsultarPersona = mysqli_query(
			$Con->Conexion, 
			$ConsultarPersona) or die("Problemas al consultar filtro Persona");
		$ret = mysqli_fetch_assoc($EjecutarConsultarPersona);

		$ID_Persona = $ret["id_persona"];
		$apellido = $ret["apellido"];
		$nombre = $ret["nombre"];
		$dni = $ret["documento"];
		$edad = $ret["edad"];
		$meses = $ret["meses"];
		if(is_null($ret["fecha_nac"]) || $ret["fecha_nac"] == "null"){
			$fecha_nacimiento = "No se cargo fecha de nacimiento";
		}else{
			$fecha_nacimiento = implode("/", array_reverse(explode("-",$ret["fecha_nac"])));    
		}
		$nro_Carpeta = $ret["nro_carpeta"];
		$nro_Legajo = $ret["nro_legajo"];
		$obra_Social = $ret["obra_social"];
		$domicilio = $ret["domicilio"];
		$barrio = $ret["ID_Barrio"];
		$localidad = $ret["localidad"];
		$circunscripcion = $ret["circunscripcion"];
		$seccion = $ret["seccion"];
		$manzana = $ret["manzana"];
		$lote = $ret["lote"];
		$familia = $ret["familia"];
		$observacion = $ret["observacion"];
		$cambio_Domicilio = $ret["cambio_domicilio"];
		$telefono = $ret["telefono"];
		$mail = $ret["mail"];
		$ID_Escuela = $ret["ID_Escuela"];
		$estado = $ret["estado"];
		$trabajo = $ret["Trabajo"];
		$this->ID_Persona = $ID_Persona;
		$this->Apellido = $apellido;
		$this->Nombre = $nombre;
		$this->DNI = $dni;
		$this->Nro_Legajo = $nro_Legajo;
		$this->Edad = $edad;
		$this->Meses = $meses;
		$this->Fecha_Nacimiento = $fecha_nacimiento;
		$this->Nro_Carpeta = $nro_Carpeta;
		$this->Obra_Social = $obra_Social;
		$this->Domicilio = $domicilio;
		$this->Barrio = $barrio;
		$this->Localidad = $localidad;
		$this->Circunscripcion = $circunscripcion;
		$this->Seccion = $seccion;
		$this->Manzana = $manzana;
		$this->Lote = $lote;
		$this->Familia = $familia;
		$this->Observaciones = $observacion;
		$this->Cambio_Domicilio = $cambio_Domicilio;
		$this->Telefono = $telefono;
		$this->Mail = $mail;
		$this->ID_Escuela = $ID_Escuela;	
		$this->Estado = $estado;
		$this->Trabajo = $trabajo;
	}
}

}


?>