<?php
require_once('Accion.php');
class Persona implements JsonSerializable {
	//DECLARACION DE VARIABLES
	private $Apellido;
	private $Barrio;
	private $Cambio_Domicilio;
	private $Calle;
	private $Circunscripcion;
	private $DNI;
	private $Domicilio;
	private $Edad;
	private $Estado;
	private $Familia;
	private $Fecha_Nacimiento;
	private $Georeferencia;
	private $ID_Escuela;
	private $ID_Persona;
	private $Localidad;
	private $Lote;
	private $Mail;
	private $Manzana;
	private $Meses;
	private $Nombre;
	private $Nro_Legajo;
	private $Nro_Carpeta;
	private $Nro;
	private $Obra_Social;
	private $Observaciones;
	private $Seccion;
	private $Telefono;
	private $Trabajo;


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
		$xTrabajo = null,
		$xGeoreferencia = null,
		$xCalle = null,
		$xNro = null
	){
		if (!$ID_Persona) {
			$this->Apellido = $xApellido;
			$this->Barrio = $xBarrio;
			$this->Calle = $xCalle;
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
			$this->Nro = $xNro;
			$this->Nro_Carpeta = $xNro_Carpeta;
			$this->Nro_Legajo = $xNro_Legajo;
			$this->Obra_Social = $xObra_Social;
			$this->Observaciones = $xObservaciones;
			$this->Seccion = $xSeccion;
			$this->Telefono = $xTelefono;
			$this->Trabajo = $xTrabajo;
			if ((!$xGeoreferencia) && ($this->Domicilio || ($this->Nro && $this->Calle))) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://nominatim.openstreetmap.org/search?street=" . str_replace(" ", "+", $this->Domicilio) . "&city=rio+tercero&format=jsonv2&limit=1&email=martinmonnittola@gmail.com");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($ch);
				$arr_obj_json = json_decode($response);
				curl_close($ch);
				if ($arr_obj_json) {
					if (!is_null($arr_obj_json[0]->lat) || !is_null($arr_obj_json[0]->lon)) {
						$point = "POINT(" . $arr_obj_json[0]->lat . ", " . $arr_obj_json[0]->lon . ")";
						$this->Georeferencia = $point;
					} else {
						$this->Georeferencia = null;
					}
				} else {
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?address=Rio+Tercero,+" . str_replace(" ", "+", $this->Domicilio) . "&key=AIzaSyAdiF1F7NoZbmAzBWfV6rxjJrGsr1Yvb1g");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$response = curl_exec($ch);
					$arr_obj_json = json_decode($response);
					curl_close($ch);
					if ($arr_obj_json) {
						if (!is_null($arr_obj_json->results[0]->geometry->location->lat) 
									 || !is_null($arr_obj_json->results[0]->geometry->location->lng)) {
							$point = "POINT(" . $arr_obj_json->results[0]->geometry->location->lat . ", " . $arr_obj_json->results[0]->geometry->location->lng . ")";
							$this->Georeferencia = $point;
						} else {
							$this->Georeferencia = null;
						}
					} else {
						$this->Georeferencia = null;
					}
				}
			} else {
	
				$this->Georeferencia = $xGeoreferencia;
			}
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
			} else {
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
			$calle = $ret["calle"];
			$nro = $ret["nro"];
			$georefencia = (isset($ret["georefencia"])) ? $ret["georefencia"] : null;
			$this->ID_Persona = $ID_Persona;
			$this->Apellido = ($xApellido) ? $xApellido : $apellido;
			$this->Nombre = ($xNombre) ? $xNombre : $nombre;
			$this->DNI = ($xDNI) ? $xDNI : $dni;
			$this->Nro_Legajo = ($xNro_Legajo) ? $xNro_Legajo : $nro_Legajo;
			$this->Edad = ($xEdad) ? $xEdad : $edad;
			$this->Meses = ($xMeses) ? $xMeses : $meses;
			$this->Fecha_Nacimiento = ($xFecha_Nacimiento) ? $xFecha_Nacimiento : $fecha_nacimiento;
			$this->Nro_Carpeta = ($xNro_Carpeta) ? $xNro_Carpeta : $nro_Carpeta;
			$this->Obra_Social = ($xObra_Social) ? $xObra_Social : $obra_Social;
			$this->Domicilio = (!empty($xDomicilio)) ? $xDomicilio : $domicilio;
			$this->Barrio = ($xBarrio) ? $xBarrio : $barrio;
			$this->Localidad = ($xLocalidad) ? $xLocalidad : $localidad;
			$this->Circunscripcion = ($xCircunscripcion) ? $xCircunscripcion : $circunscripcion;
			$this->Seccion = ($xSeccion) ? $xSeccion : $seccion;
			$this->Manzana = ($xManzana) ? : $manzana;
			$this->Lote = ($xLote) ? $xLote : $lote;
			$this->Familia = ($xFamilia) ? $xFamilia : $familia;
			$this->Observaciones = ($xObservaciones) ? $xObservaciones : $observacion;
			$this->Cambio_Domicilio = ($xCambio_Domicilio) ? $xCambio_Domicilio : $cambio_Domicilio;
			$this->Telefono = ($xTelefono) ? $xTelefono : $telefono;
			$this->Mail = ($xMail) ? $xMail : $mail;
			$this->ID_Escuela = ($xID_Escuela) ? $xID_Escuela : $ID_Escuela;	
			$this->Estado = ($xEstado) ? $xEstado : $estado;
			$this->Trabajo = ($xTrabajo) ? $xTrabajo : $trabajo;
			$this->Georeferencia = ($xGeoreferencia) ? $xGeoreferencia : $georefencia;
			$this->Nro = ($xNro) ? $xNro : $nro;
			$this->Calle = ($xCalle) ? $xCalle : $calle;
			$Con->CloseConexion();
		}
	}


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

public function setDomicilio($xDomicilio = null)
{
	$id_calle = (!$xDomicilio) ? $this->getId_Calle() : null;
	$numero_calle = (!$xDomicilio) ? trim($this->getNro()) : null;
	$domicilio = ($xDomicilio) ? $xDomicilio : null;
	$nombre_calle = null;
	$con = new Conexion();
	$con->OpenConexion();
	if (!is_null($id_calle)) {
		$nombre_calle = $this->getNombre_Calle();
		$domicilio = "$nombre_calle $numero_calle";
		$domicilio = str_replace(array('á','é','í','ó','ú','ñ'), array('a','e','i','o','u','n'), $domicilio);
	} else if ($domicilio) {
		$consulta = "select calle_open
					 from calle
					 where lower(calle_nombre) like CONCAT(
															'%',
															REGEXP_REPLACE( 
																	REGEXP_REPLACE(
																					REGEXP_SUBSTR(
																							lower('$domicilio'), 
																							'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)'
																					),
																					'( )+',
																					'%'
																					),
																			'(\\\\.)',
																			''
																			),
															'%'
															);";
		$query_object = mysqli_query($con->Conexion, $consulta) or die("Error al consultar datos");
		$ret = mysqli_fetch_assoc($query_object);
		$nombre_calle = $ret["calle_open"];
		$domicilio = "$nombre_calle " . $this->getNro();
		$domicilio = str_replace(array('á','é','í','ó','ú','ñ'), array('a','e','i','o','u','n'), $domicilio);
	}

	$Fecha = date("Y-m-d");
	if ($domicilio) {
		$ch = curl_init();
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . str_replace(" ", "+", trim($domicilio)) . "+Rio+Tercero,Cordoba&key=AIzaSyAdiF1F7NoZbmAzBWfV6rxjJrGsr1Yvb1g";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER  , 1);
		$response = curl_exec($ch);
		$response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$arr_obj_json = json_decode($response);
		curl_close($ch);
		if ($response_status == 200) {
			$body_request = (($arr_obj_json) ? " " . json_encode($arr_obj_json->results[0]) : "");
		} else {
			$body_request = "- El estado de la respuesta de google api es : " . $response_status;
			$arr_obj_json = null;
		}
		$detalles = $url . $body_request;
		$accion = new Accion(
			xFecha : $Fecha,
			xDetalles : $detalles,
			xID_TipoAccion : 1
		);
		$accion->save();

		$center_rio_tercero_lat = -32.194998;
		$center_rio_tercero_lon = -64.1684546;
		if ($arr_obj_json && $arr_obj_json->results) {
			if ((!is_null($arr_obj_json->results[0]->geometry->location->lat) 
				|| !is_null($arr_obj_json->results[0]->geometry->location->lng))
				&& ($center_rio_tercero_lat != $arr_obj_json->results[0]->geometry->location->lat)
				&& ($center_rio_tercero_lon != $arr_obj_json->results[0]->geometry->location->lng)
			) {
				$point = "POINT(" . $arr_obj_json->results[0]->geometry->location->lat . ", " . $arr_obj_json->results[0]->geometry->location->lng . ")";
				$this->Georeferencia = $point;
			} else {
				$ch = curl_init();
				$url = "https://nominatim.openstreetmap.org/search?street=" . str_replace(" ", "+", trim($domicilio)) . "&city=rio+tercero&format=jsonv2&limit=1&email=martinmonnittola@gmail.com";
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($ch);
				$arr_obj_json = json_decode($response);
				curl_close($ch);
				$body_request = (($arr_obj_json[0]) ? " " . json_encode($arr_obj_json[0]) : "");
				$body_request = str_replace("'", "", $body_request);
				$detalles = $url . $body_request;
				$accion = new Accion(
					xFecha : $Fecha,
					xDetalles : $detalles,
					xID_TipoAccion : 1
				);
				$accion->save();
				if ($arr_obj_json) {
					if (!is_null($arr_obj_json[0]->lat) || !is_null($arr_obj_json[0]->lon)) {
						$point = "POINT(" . $arr_obj_json[0]->lat . ", " . $arr_obj_json[0]->lon . ")";
						$this->Georeferencia = $point;
					} else {
						$this->Georeferencia = null;
					}
				} else {
					$this->Georeferencia = null;
				}
			}
		} else {
			$ch = curl_init();
			$url = "https://nominatim.openstreetmap.org/search?street=" . str_replace(" ", "+", trim($domicilio)) . "&city=rio+tercero&format=jsonv2&limit=1&email=martinmonnittola@gmail.com";
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			$arr_obj_json = json_decode($response);
			curl_close($ch);
			$body_request = (($arr_obj_json[0]) ? " " . json_encode($arr_obj_json[0]) : "");
			$body_request = str_replace("'", "", $body_request);
			$detalles = $url . $body_request;
			$accion = new Accion(
				xFecha : $Fecha,
				xDetalles : $detalles,
				xID_TipoAccion : 1
			);
			$accion->save();
			if ($arr_obj_json) {
				if (!is_null($arr_obj_json[0]->lat) || !is_null($arr_obj_json[0]->lon)) {
					$point = "POINT(" . $arr_obj_json[0]->lat . ", " . $arr_obj_json[0]->lon . ")";
					$this->Georeferencia = $point;
				} else {
					$this->Georeferencia = null;
				}
			} else {
				$this->Georeferencia = null;
			}
		}
	}
	$this->Domicilio = $domicilio;
	$con->CloseConexion();
}


public function setCalle($xCalle)
{
	$this->Calle = $xCalle;
}

public function setNro($xNro)
{
	$nro_calle = null;
	if (is_string($xNro) && preg_match('~ [0-9]+$~', $xNro, $out)) {
		$nro_calle = trim($out[0]);
	};
	$this->Nro = ((!is_null($nro_calle)) ? $nro_calle : $xNro);
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

public function setGeoreferencia($xGeoreferencia){
	$this->Georeferencia = $xGeoreferencia;
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

public function getId_Calle(){
	return $this->Calle;
}

public function getNombre_Calle(){
	$con = new Conexion();
	$con->OpenConexion();
	$consulta_calle = "select *
					   from calle 
					   where id_calle = " . $this->getId_Calle() . " 
					     and estado = 1";
	$result = mysqli_query($con->Conexion, $consulta_calle);
	if (!$result) {
		$mensaje_error_consultar = "No se pudo consultar la tabla calle";
		throw new Exception($mensaje_error_consultar . $consulta_calle, 2);
	}
	$result_row = mysqli_fetch_assoc($result);
	return $result_row["calle_open"];
}

public function getNro(){
	return $this->Nro;
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

public function getGeoreferencia()
{
	return $this->Georeferencia;
}

public function getLonguitud()
{
	$con = new Conexion();
	$con->OpenConexion();
	$consulta = "select p.*,
						ST_Y(p.georeferencia) as lon
				 from persona p
				 where id_persona = " . $this->getID_Persona();
	$query_object = mysqli_query($con->Conexion, $consulta) or die("Error al consultar datos");
	$ret = mysqli_fetch_assoc($query_object);
	return $ret["lon"];
}
public function getLatitud()
{
	$con = new Conexion();
	$con->OpenConexion();
	$consulta = "select p.*,
						ST_X(p.georeferencia) as lat
				 from persona p
				 where id_persona = " . $this->getID_Persona();
	$query_object = mysqli_query($con->Conexion, $consulta) or die("Error al consultar datos");
	$ret = mysqli_fetch_assoc($query_object);
	return $ret["lat"];
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

public static function is_exist($coneccion, $id_persona)
{
	$consulta = "select * 
				 from persona 
				 where id_persona = $id_persona 
				   and estado = 1";
	$mensaje_error = "Hubo un problema al consultar los registros para validar";
	$ret = mysqli_query(
				$coneccion->Conexion,
				$consulta
	) or die(
		$mensaje_error
	);
	$is_multiple = (mysqli_num_rows($ret) >= 1);
	return $is_multiple;
}

public static function is_registered($documento)
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$ConsRegistrosIguales = "select id_persona 
							 from persona 
							 where documento like '%" . $documento. "%' 
							   and estado = 1";
	$MensajeErrorRegistrosIguales = "Hubo un problema al consultar los registros para validar";
	$ret = mysqli_query($Con->Conexion,
		$ConsRegistrosIguales
	) or die(
		$MensajeErrorRegistrosIguales . " Consulta: " . $ConsRegistrosIguales
	);
	$is_multiple = (mysqli_num_rows($ret) >= 1);
	$Con->CloseConexion();
	return $is_multiple;
}

public static function get_id_persona_by_dni($documento)
{
	$con = new Conexion();
	$con->OpenConexion();
	$consulta = "select id_persona from persona where documento like '%" . $documento. "%' and estado = 1";
	$mensaje_error = "Hubo un problema al consultar el id de la persona";
	$ret = mysqli_query($con->Conexion,
	$consulta
	) or die(
		$mensaje_error . " Consulta: " . $consulta
	);
	$row = mysqli_fetch_assoc($ret);
	$id = $row["id_persona"];
	$con->CloseConexion();
	return $id;
}

public function jsonSerialize() {
	return [
	'ID_Persona' => $this->ID_Persona,
	'Nombre' => $this->Nombre,
	'Apellido' => $this->Apellido,
	'DNI' => $this->DNI,
	'Nro_Legajo' => $this->Nro_Legajo,
	'Edad' => $this->Edad,
	'Meses' => $this->Meses,
	'Fecha_Nacimiento' => $this->Fecha_Nacimiento,
	'Nro_Carpeta' => $this->Nro_Carpeta,
	'Obra_Social' => $this->Obra_Social,
	'Domicilio' => $this->Domicilio,
	'Barrio' => $this->Barrio,
	'Localidad' => $this->Localidad,
	'Circunscripcion' => $this->Circunscripcion,
	'Seccion' => $this->Seccion,
	'Manzana' => $this->Manzana,
	'Lote' => $this->Lote,
	'Familia' => $this->Familia,
	'Observaciones' => $this->Observaciones,
	'Cambio_Domicilio' => $this->Cambio_Domicilio,
	'Telefono' => $this->Telefono,
	'Mail' => $this->Mail,
	'ID_Escuela' => $this->ID_Escuela,	
	'Estado' => $this->Estado,
	'Trabajo' => $this->Trabajo,
	'Georeferencia' => $this->Georeferencia,
	'Nro' => $this->Nro,
	'Calle' => $this->Calle
	];
}

public function update_geo()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$Consulta = "update persona 
				 set georeferencia = " . ((!is_null($this->getGeoreferencia())) ? $this->getGeoreferencia() : "null") . " 
				 where id_persona = " . $this->getID_Persona();
				 $MensajeErrorConsultar = "No se pudo actualizar la Persona ";
				 if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
					throw new Exception($MensajeErrorConsultar . $Consulta, 2);
				}
				 $Con->CloseConexion();
}

public function update_calle()
{
	$con = new Conexion();
	$con->OpenConexion();
	$consulta = "update persona 
				 set calle = " . ((!is_null($this->getId_Calle())) ? $this->getId_Calle() : "null") . " 
				 where id_persona = " . $this->getID_Persona();
	$mensaje_error_consultar = "No se pudo actualizar la Persona ";
	if (!$Ret = mysqli_query($con->Conexion, $consulta)) {
		throw new Exception($mensaje_error_consultar . $consulta, 2);
	}
	$con->CloseConexion();
}

public function update_nro()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$Consulta = "update persona 
				 set nro = " . ((!is_null($this->getNro())) ? $this->getNro() : "null") . " 
				 where id_persona = " . $this->getID_Persona();
				 $MensajeErrorConsultar = "No se pudo actualizar la Persona ";
				 if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
					throw new Exception($MensajeErrorConsultar . $Consulta, 2);
				}
				 $Con->CloseConexion();
}

public function update()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$Consulta = "update persona 
				 set apellido = " . ((!is_null($this->getApellido())) ? "'" . $this->getApellido() . "'" : "null") . ", 
				 	 nombre = " . ((!is_null($this->getNombre())) ? "'" . $this->getNombre() . "'" : "null") . ", 
					 documento = " . ((!is_null($this->getDNI())) ? "'" . $this->getDNI() . "'" : "null") . ", 
					 nro_legajo = " . ((!is_null($this->getNro_Legajo())) ? "'" . $this->getNro_Legajo() . "'" : "null") . ", 
					 edad = " . ((!is_null($this->getEdad())) ? "'" . $this->getEdad() . "'" : "null") . ", 
					 fecha_nac = " . ((!is_null($this->getFecha_Nacimiento())) ? "'" . $this->getFecha_Nacimiento() . "'" : "null") . ", 
					 telefono = " . ((!is_null($this->getTelefono())) ? "'" . $this->getTelefono() . "'" : "null") . ", 
					 mail = " . ((!is_null($this->getMail())) ? "'" . $this->getMail() . "'" : "null") . ", 
					 nro_carpeta = " . ((!is_null($this->getNro_Carpeta())) ? "'" . $this->getNro_Carpeta() . "'" : "null") . ", 
					 obra_social = " . ((!is_null($this->getObra_Social())) ? "'" . $this->getObra_Social() . "'" : "null") . ", 
					 domicilio = " . ((!is_null($this->getDomicilio())) ? "'" . $this->getDomicilio() . "'" : "null") . ", 
					 ID_Barrio = " . ((!is_null($this->getId_Barrio())) ? "'" . $this->getId_Barrio() . "'" : "null") . ", 
					 localidad = " . ((!is_null($this->getLocalidad())) ? "'" . $this->getLocalidad() . "'" : "null") . ", 
					 circunscripcion = " . ((!is_null($this->getCircunscripcion())) ? "'" . $this->getCircunscripcion() . "'" : "null") . ", 
					 seccion = " . ((!is_null($this->getSeccion())) ? "'" . $this->getSeccion() . "'" : "null") . ", 
					 manzana = " . ((!is_null($this->getManzana())) ? "'" . $this->getManzana() . "'" : "null") . ", 
					 lote = " . ((!is_null($this->getLote())) ? $this->getLote() : "null") . ", 
					 familia = " . ((!is_null($this->getFamilia())) ? $this->getFamilia() : "null") . ", 
					 observacion = " . ((!is_null($this->getObservaciones())) ? "'" . $this->getObservaciones() . "'" : "null") . ", 
					 cambio_domicilio = " . ((!is_null($this->getCambio_Domicilio())) ? "'" . $this->getCambio_Domicilio() . "'" : "null") . ", 
					 telefono = " . ((!is_null($this->getTelefono())) ? "'" . $this->getTelefono() . "'" : "null") . ", 
					 ID_Escuela = " . ((!is_null($this->getID_Escuela())) ? "'" . $this->getID_Escuela() . "'" : "null") . ", 
					 meses = " . ((!is_null($this->getMeses())) ? "'" . $this->getMeses() . "'" : "null") . ", 
					 Trabajo = " . ((!is_null($this->getTrabajo())) ? "'" . $this->getTrabajo() . "'" : "null") . ",
					 georeferencia = " . ((!is_null($this->getGeoreferencia())) ? $this->getGeoreferencia() : "null") . ", 
					 calle = " . ((!is_null($this->getId_Calle())) ? $this->getId_Calle() : "null") . ", 
					 nro = " . ((!is_null($this->getNro())) ? $this->getNro() : "null") . " 
				 where id_persona = " . $this->getID_Persona();
				 $MensajeErrorConsultar = "No se pudo actualizar la Persona";
				 if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
					throw new Exception($MensajeErrorConsultar . $Consulta, 2);
				}
				 $Con->CloseConexion();
}


public function update_edad_meses()
{
	$con = new Conexion();
	$con->OpenConexion();

	$Edad = (isset($this->Edad)) ? $this->Edad : null;
	$Meses = (isset($this->Meses)) ? $this->Meses : null;
	$Fecha_Nacimiento = $this->Fecha_Nacimiento;
	if ($Fecha_Nacimiento != 'null' && !empty($Fecha_Nacimiento)) {
		if (substr_count("-", $Fecha_Nacimiento)) {
			list($ano, $mes, $dia) = explode("-", $Fecha_Nacimiento);
		} else {
			list($ano, $mes, $dia) = explode("/", $Fecha_Nacimiento);
		}
		$ano_diferencia = date("Y") - $ano;
		$mes_diferencia = date("m") - $mes;
		$dia_diferencia = date("d") - $dia;
		if ($ano_diferencia > 0) {
			if ($mes_diferencia == 0) {
				if ($dia_diferencia < 0) {
					$ano_diferencia--;
				}
			} elseif ($mes_diferencia < 0) {
				$ano_diferencia--;
			}
		} else {
			if ($mes_diferencia > 0) {
				if ($dia_diferencia < 0) {
					$mes_diferencia--;
				}
			}
		}
		$Edad = $ano_diferencia;
		$Meses = $mes_diferencia;
	}

	//PROBAR SI ESTO DA LA DIFERENCIA ENTRE MESES NOMAS O TAMBIEN TOMA LOS AÑOS COMO MESES EN ESE CASO TOMAR LA CANTIDAD DE AÑOS Y MULTIPLICARLO POR 12 Y A ESO RESTARLE AL RESULTADO DEL TOTAL DE MESES DE DIFERENCIA.
	if ($Fecha_Nacimiento != 'null' && !empty($Fecha_Nacimiento)) {
		$Fecha_Actual = new DateTime();
		if (substr_count("-", $Fecha_Nacimiento)) {
			$fecha_activacion_registrada = DateTime::createFromFormat('d-m-Y', 
																	$Fecha_Nacimiento);
			$Diferencia = $fecha_activacion_registrada->diff($Fecha_Actual);
			$Meses = $Diferencia->m;
			$Edad = $Diferencia->y;
		} else if (substr_count("/", $Fecha_Nacimiento)){
			$fecha_activacion_registrada = DateTime::createFromFormat('d/m/Y', 
																	$Fecha_Nacimiento);
			$Diferencia = $fecha_activacion_registrada->diff($Fecha_Actual);
			$Meses = $Diferencia->m;
			$Edad = $Diferencia->y;
		}
	}

	$consulta = "update persona
				 set edad = " . ((!is_null($Edad)) ? "'" . $Edad . "'" : "null") . ", 
					 meses = " . ((!is_null($Meses)) ? "'" . $Meses . "'" : "null") . " 
				 where id_persona = " . $this->getID_Persona();
	$MensajeErrorConsultar = "No se pudo actualizar la Persona";
	if (!$Ret = mysqli_query($con->Conexion, $consulta)) {
		throw new Exception($MensajeErrorConsultar . $consulta, 2);
	}
	$con->CloseConexion();
}

public function save(){
	$Con = new Conexion();
	$Con->OpenConexion();
	$consulta = "INSERT INTO persona (
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
									  georeferencia,
									  calle,
									  nro, 
									  estado 
				 )
				 VALUES ( " . ((!is_null($this->getApellido())) ? "'" . $this->getApellido() . "'" : "null") . ", 
						 " . ((!is_null($this->getNombre())) ? "'" . $this->getNombre() . "'" : "null") . ", 
						 " . ((!is_null($this->getDNI())) ? "'" . $this->getDNI() . "'" : "null") . ", 
						 " . ((!is_null($this->getNro_Legajo())) ? "'" . $this->getNro_Legajo() . "'" : "null") . ", 
						 " . ((!is_null($this->getEdad())) ? $this->getEdad() : "null") . ", 
						 " . ((!is_null($this->getFecha_Nacimiento())) ? "'" . $this->getFecha_Nacimiento() . "'" : "null") . ", 
						 " . ((!is_null($this->getTelefono())) ? "'" . $this->getTelefono() . "'" : "null") . ", 
						 " . ((!is_null($this->getMail())) ? "'" . $this->getMail() . "'" : "null") . ", 
						 " . ((!is_null($this->getNro_Carpeta())) ? "'" . $this->getNro_Carpeta() . "'" : "null") . ", 
						 " . ((!is_null($this->getObra_Social())) ? "'" . $this->getObra_Social() . "'" : "null") . ", 
						 " . ((!is_null($this->getDomicilio())) ? "'" . $this->getDomicilio() . "'" : "null") . ", 
						 " . ((!is_null($this->getId_Barrio())) ? $this->getId_Barrio() : "null") . ", 
						 " . ((!is_null($this->getLocalidad())) ? "'" . $this->getLocalidad() . "'" : "null") . ", 
						 " . ((!is_null($this->getCircunscripcion())) ? $this->getCircunscripcion() : "null") . ", 
						 " . ((!is_null($this->getSeccion())) ? $this->getSeccion() : "null") . ", 
						 " . ((!is_null($this->getManzana())) ? "'" . $this->getManzana() . "'" : "null") . ", 
						 " . ((!is_null($this->getLote())) ? $this->getLote() : "null") . ", 
						 " . ((!is_null($this->getFamilia())) ? $this->getFamilia() : "null") . ", 
						 " . ((!is_null($this->getObservaciones())) ? "'" . $this->getObservaciones() . "'" : "null") . ", 
						 " . ((!is_null($this->getCambio_Domicilio())) ? "'" . $this->getCambio_Domicilio() . "'" : "null") . ", 
						 " . ((!is_null($this->getID_Escuela())) ? $this->getID_Escuela() : "null") . ", 
						 " . ((!is_null($this->getMeses())) ? "'" . $this->getMeses() . "'" : "null") . ", 
						 " . ((!is_null($this->getTrabajo())) ? "'" . $this->getTrabajo() . "'" : "null") . ",
						 " . ((!is_null($this->getGeoreferencia())) ? $this->getGeoreferencia() : "null") . ",
						 " . ((!empty($this->getId_Calle())) ? $this->getId_Calle() : "null") . ",
						 " . ((!empty($this->getNro())) ? $this->getNro() : "null") . ",
						 1
				 )";
				 $MensajeErrorConsultar = "No se pudo insertar la Persona";
				 $ret = mysqli_query($Con->Conexion, $consulta);
				 if (!$ret) {
					throw new Exception($MensajeErrorConsultar . $consulta, 2);
				 }
				 $this->ID_Persona = mysqli_insert_id($Con->Conexion);
				 $Con->CloseConexion();
}

}
