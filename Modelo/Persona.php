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

public function getNroCalle()
{
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

public function getBarrio()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$ConsultarBarrio = "select * 
						from barrios 
						where ID_Barrio = {$this->Barrio}";
	$MensajeErrorBarrio = "No se pudo consultar el Barrio de la persona";
	$EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die($MensajeErrorBarrio);
	$RetBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
	$Con->CloseConexion();
	return $RetBarrio["Barrio"];
}

public function getId_Barrio()
{
	return $this->Barrio;
}

public function getLocalidad()
{
	return $this->Localidad;
}

public function getCircunscripcion()
{
	return $this->Circunscripcion;
}

public function getSeccion()
{
	return $this->Seccion;
}

public function getManzana()
{
	return $this->Manzana;
}

public function getLote()
{
	return $this->Lote;
}

public function getFamilia()
{
	return $this->Familia;
}

public function getObservaciones()
{
	return $this->Observaciones;
}

public function getCambio_Domicilio()
{
	return $this->Cambio_Domicilio;
}

public function getTelefono()
{
	return $this->Telefono;
}

public function getMail()
{
	return $this->Mail;
}

public function getEstado()
{
	return $this->Estado;
}

public function getID_Escuela()
{
	return $this->ID_Escuela;
}

public function getEscuela()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$ConsultarEscuela = "select Escuela 
						 from escuelas 
						 where ID_Escuela = {$this->ID_Escuela}";
	$MensajeErrorConsultarEscuela = "No se pudo consultar la Escuela";
	$EjecutarConsultarEscuela = mysqli_query(
		$Con->Conexion,
		$ConsultarEscuela
		) or die($MensajeErrorConsultarEscuela);
	$RetEscuela = mysqli_fetch_assoc($EjecutarConsultarEscuela);
	$RetEscuela["Escuela"];
	$Con->CloseConexion();
	return $RetEscuela["Escuela"];
}
public function getTrabajo()
{
	return $this->Trabajo;
}

public static function is_registered($documento)
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$ConsRegistrosIguales = "select id_persona from persona where documento like '%" . $documento. "%' and estado = 1";
	$MensajeErrorRegistrosIguales = "Hubo un problema al consultar los registros para validar";
	$Ret = mysqli_query($Con->Conexion,
		$ConsRegistrosIguales
	) or die(
		$MensajeErrorRegistrosIguales . " Consulta: " . $ConsRegistrosIguales
	);
	$is_multiple = (mysqli_num_rows($Ret) > 1);
	$Con->CloseConexion();
	return $is_multiple;
}

public function update()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$Consulta = "update persona 
				 set apellido = " . ((!is_null($this->getApellido())) ? "'" . $this->getApellido()."'" : "null").", 
				 	 nombre = " . ((!is_null($this->getNombre())) ? "'" . $this->getNombre()."'" : "null").", 
					 documento = " . ((!is_null($this->getDNI())) ? "'" . $this->getDNI()."'" : "null").", 
					 nro_legajo = " . ((!is_null($this->getNro_Legajo())) ? "'" . $this->getNro_Legajo()."'" : "null").", 
					 edad = " . ((!is_null($this->getEdad())) ? "'" . $this->getEdad()."'" : "null").", 
					 fecha_nac = " . ((!is_null($this->getFecha_Nacimiento())) ? "'" . $this->getFecha_Nacimiento()."'" : "null").", 
					 telefono = " . ((!is_null($this->getTelefono())) ? "'" . $this->getTelefono()."'" : "null").", 
					 mail = " . ((!is_null($this->getMail())) ? "'" . $this->getMail()."'" : "null").", 
					 nro_carpeta = " . ((!is_null($this->getNro_Carpeta())) ? "'" . $this->getNro_Carpeta()."'" : "null").", 
					 obra_social = " . ((!is_null($this->getObra_Social())) ? "'" . $this->getObra_Social()."'" : "null").", 
					 domicilio = " . ((!is_null($this->getDomicilio())) ? "'" . $this->getDomicilio()."'" : "null").", 
					 ID_Barrio = " . ((!is_null($this->getId_Barrio())) ? "'" . $this->getId_Barrio()."'" : "null").", 
					 localidad = " . ((!is_null($this->getLocalidad())) ? "'" . $this->getLocalidad()."'" : "null").", 
					 circunscripcion = " . ((!is_null($this->getCircunscripcion())) ? "'" . $this->getCircunscripcion()."'" : "null").", 
					 seccion = " . ((!is_null($this->getSeccion())) ? "'" . $this->getSeccion()."'" : "null").", 
					 manzana = " . ((!is_null($this->getManzana())) ? "'" . $this->getManzana()."'" : "null").", 
					 lote = " . ((!is_null($this->getLote())) ? $this->getLote() : "null").", 
					 familia = " . ((!is_null($this->getFamilia())) ? $this->getFamilia() : "null").", 
					 observacion = " . ((!is_null($this->getObservaciones())) ? "'" . $this->getObservaciones()."'" : "null").", 
					 cambio_domicilio = " . ((!is_null($this->getCambio_Domicilio())) ? "'" . $this->getCambio_Domicilio()."'" : "null").", 
					 telefono = " . ((!is_null($this->getTelefono())) ? "'" . $this->getTelefono()."'" : "null").", 
					 ID_Escuela = " . ((!is_null($this->getID_Escuela())) ? "'" . $this->getID_Escuela()."'" : "null").", 
					 meses = " . ((!is_null($this->getMeses())) ? "'" . $this->getMeses()."'" : "null").", 
					 Trabajo = " . ((!is_null($this->getTrabajo())) ? "'" . $this->getTrabajo()."'" : "null")." 
				 where id_persona = " . $this->getID_Persona();
				 $MensajeErrorConsultar = "No se pudo actualizar la Persona";
				 if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
					throw new Exception($MensajeErrorConsultar . $Consulta, 2);
				}
				 $Con->CloseConexion();
}

public function save(){
	$Con = new Conexion();
	$Con->OpenConexion();
	$Consulta = "INSERT INTO persona (
									  apellido, 
									  nombre, 
									  documento, 
									  nro_legajo,
									  edad, 
									  fecha_nac, 
									  telefono, 
									  mail, 
									  nro_carpeta, 
									  obra_social,
									  domicilio, 
									  ID_Barrio, 
									  localidad, 
									  circunscripcion, 
									  seccion,
									  manzana, 
									  lote, 
									  familia, 
									  observacion, 
									  cambio_domicilio,
									  ID_Escuela, 
									  meses, 
									  Trabajo, 
									  estado 
				 )
				 VALUES ( " . ((!is_null($this->getApellido())) ? "'" . $this->getApellido()."'" : "null").", 
						 " . ((!is_null($this->getNombre())) ? "'" . $this->getNombre()."'" : "null").", 
						 " . ((!is_null($this->getDNI())) ? "'" . $this->getDNI()."'" : "null").", 
						 " . ((!is_null($this->getNro_Legajo())) ? "'" . $this->getNro_Legajo()."'" : "null").", 
						 " . ((!is_null($this->getEdad())) ? $this->getEdad() : "null").", 
						 " . ((!is_null($this->getFecha_Nacimiento())) ? "'" . $this->getFecha_Nacimiento()."'" : "null").", 
						 " . ((!is_null($this->getTelefono())) ? "'" . $this->getTelefono()."'" : "null").", 
						 " . ((!is_null($this->getMail())) ? "'" . $this->getMail()."'" : "null").", 
						 " . ((!is_null($this->getNro_Carpeta())) ? "'" . $this->getNro_Carpeta()."'" : "null").", 
						 " . ((!is_null($this->getObra_Social())) ? "'" . $this->getObra_Social()."'" : "null").", 
						 " . ((!is_null($this->getDomicilio())) ? "'" . $this->getDomicilio()."'" : "null").", 
						 " . ((!is_null($this->getId_Barrio())) ? $this->getId_Barrio() : "null").", 
						 " . ((!is_null($this->getLocalidad())) ? "'" . $this->getLocalidad()."'" : "null").", 
						 " . ((!is_null($this->getCircunscripcion())) ? $this->getCircunscripcion() : "null").", 
						 " . ((!is_null($this->getSeccion())) ? $this->getSeccion() : "null").", 
						 " . ((!is_null($this->getManzana())) ? "'" . $this->getManzana()."'" : "null").", 
						 " . ((!is_null($this->getLote())) ? $this->getLote() : "null").", 
						 " . ((!is_null($this->getFamilia())) ? $this->getFamilia() : "null").", 
						 " . ((!is_null($this->getObservaciones())) ? "'" . $this->getObservaciones()."'" : "null").", 
						 " . ((!is_null($this->getCambio_Domicilio())) ? "'" . $this->getCambio_Domicilio()."'" : "null").", 
						 " . ((!is_null($this->getID_Escuela())) ? "'" . $this->getID_Escuela()."'" : "null").", 
						 " . ((!is_null($this->getMeses())) ? "'" . $this->getMeses()."'" : "null").", 
						 " . ((!is_null($this->getTrabajo())) ? "'" . $this->getTrabajo()."'" : "null").",
						 1
				 )";
				 $MensajeErrorConsultar = "No se pudo insertar la Persona";
				 if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
					throw new Exception($MensajeErrorConsultar . $Consulta, 2);
				 }
				 $Con->CloseConexion();
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
		$this->Apellido = $xApellido;
		$this->Barrio = $xBarrio;
		$this->Cambio_Domicilio = $xCambio_Domicilio;
		$this->Circunscripcion = $xCircunscripcion;
		$this->DNI = $xDNI;
		$this->Domicilio = $xDomicilio;
		$this->Edad = $xEdad;
		$this->Estado = $xEstado;
		$this->Familia = $xFamilia;
		$this->Fecha_Nacimiento = $xFecha_Nacimiento;
		$this->ID_Escuela = $xID_Escuela;	
		$this->ID_Persona =$ID_Persona;
		$this->Localidad = $xLocalidad;
		$this->Lote = $xLote;
		$this->Mail = $xMail;
		$this->Manzana = $xManzana;
		$this->Meses = $xMeses;
		$this->Nombre = $xNombre;
		$this->Nro_Carpeta = $xNro_Carpeta;
		$this->Nro_Legajo = $xNro_Legajo;
		$this->Obra_Social = $xObra_Social;
		$this->Observaciones = $xObservaciones;
		$this->Seccion = $xSeccion;
		$this->Telefono = $xTelefono;
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
		$Con->CloseConexion();
	}
}

}
