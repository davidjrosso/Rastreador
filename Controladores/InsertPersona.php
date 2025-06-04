<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Persona.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Calle.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Accion.php';
header("Content-Type: text/html;charset=utf-8");

$ID_Usuario = $_SESSION["Usuario"];

$Apellido = ucwords($_REQUEST["Apellido"]);
$Nombre = ucwords($_REQUEST["Nombre"]);
$DNI = trim(str_replace(array('.'), '', $_REQUEST["DNI"]));

$Nro_Legajo = $_REQUEST["Nro_Legajo"];
if (empty($Nro_Legajo)) {
	$Nro_Legajo = null;
}

$Edad = $_REQUEST["Edad"];
$Meses = $_REQUEST["Meses"];
if (empty($Edad)) {
	$Edad = null;
}

if (empty($Meses)) {
	$Meses = null;
}

if (empty($_REQUEST["Fecha_Nacimiento"])) {
	$Fecha_Nacimiento = null;
} else {
	$Fecha_Nacimiento = implode("-", array_reverse(explode("/",$_REQUEST["Fecha_Nacimiento"])));
}

$Nro_Carpeta = $_REQUEST["Nro_Carpeta"];
if (empty($Nro_Carpeta)) {
	$Nro_Carpeta = null;
}
$Obra_Social = $_REQUEST["Obra_Social"];

$id_nombre_calle = null;
if(isset($_REQUEST["Calle"])){
	$calle = ucwords($_REQUEST["Calle"]);
	$nombre_calle = new Calle(id_calle : $calle);
	$Domicilio = $nombre_calle->get_calle_nombre();
	$id_nombre_calle = $nombre_calle->get_id_calle();
}
$nro_calle = null;
if(isset($_REQUEST["NumeroDeCalle"])){
  $nro_calle = $_REQUEST["NumeroDeCalle"];
  $Domicilio .= " ". $nro_calle;
}

$georeferencia_point = null;
if (!empty($_REQUEST["lat"])) {
	$lat_point = $_REQUEST["lat"];
	$georeferencia_point = "POINT(" . $lat_point;

	if (!empty($_REQUEST["lon"])){
		$lon_point = $_REQUEST["lon"];
		$georeferencia_point .= "," . $lon_point . ")";
	} else {
		$georeferencia_point = null;
	}
}

if (!isset($_REQUEST["ID_Barrio"])) {
	$ID_Barrio = null;
} else {
	$ID_Barrio = $_REQUEST["ID_Barrio"];
}
if (empty($ID_Barrio)) {
	$ID_Barrio = 37;
}

$Localidad = ucwords($_REQUEST["Localidad"]);

$Circunscripcion = 0;
if (empty($Circunscripcion)) {
	$Circunscripcion = null;
}
$Seccion = 0;
if (empty($Seccion)) {
	$Seccion = null;
}
$Manzana = $_REQUEST["Manzana"];
if (empty($Manzana)) {
	$Manzana = null;
}
$Lote = $_REQUEST["Lote"];
if (empty($Lote)) {
	$Lote = null;
}
$Familia = $_REQUEST["Familia"];
if (empty($Familia)) {
	$Familia = null;
}
$Observaciones = ucfirst($_REQUEST["Observaciones"]);
$Cambio_Domicilio = $_REQUEST["Cambio_Domicilio"];
$Telefono = $_REQUEST["Telefono"];
$Mail = ucfirst($_REQUEST["Mail"]);
$Estado = 1;
if (!isset($_REQUEST["ID_Escuela"])) {
	$ID_Escuela = null;
} else {
	$ID_Escuela = $_REQUEST["ID_Escuela"];
}

$Trabajo = strtoupper($_REQUEST["Trabajo"]);

if (empty($ID_Escuela)) {
	$ID_Escuela = 2;
}

$Fecha = date("Y-m-d");
$ID_TipoAccion = 1;
$Detalles = "El usuario con ID: $ID_Usuario ha registrado una nueva Persona. Datos: Apellido: $Apellido - Nombre: $Nombre - Documento: $DNI - Nro Legajo: $Nro_Legajo - Edad: $Edad - Meses: $Meses - Fecha de Nacimiento: $Fecha_Nacimiento - Telefono: $Telefono - E-Mail: $Mail - Nro Carpeta: $Nro_Carpeta - Obra Social: $Obra_Social - Domicilio: $Domicilio - Barrio: $ID_Barrio - Escuela: $ID_Escuela - Localidad: $Localidad - Circunscripcion: $Circunscripcion - Seccion: $Seccion - Manzana: $Manzana - Lote: $Lote - Familia: $Familia - Observaciones: $Observaciones - Cambio Domicilio: $Cambio_Domicilio";

try {
	if (Persona::is_registered($DNI)) {
		$Mensaje = "Ya existe un Usuario con el mismo Apellido y Nombre que el que esta intentando crear. Por favor ingrese un DNI para identificar a la persona.";
		header('Location: ../view_newpersonas.php?Mensaje=' . $Mensaje);
	} else {
		$Persona = new Persona(
			xApellido : $Apellido,
			xBarrio : $ID_Barrio,
			xCambio_Domicilio : $Cambio_Domicilio,
			xCalle: $id_nombre_calle,
			xCircunscripcion : $Circunscripcion,
			xDNI : $DNI,
			xDomicilio : $Domicilio,
			xEdad : $Edad,
			xEstado : $Estado,
			xFamilia : $Familia,
			xFecha_Nacimiento: $Fecha_Nacimiento,
			xID_Escuela : $ID_Escuela,
			xLote : $Lote,
			xLocalidad : $Localidad,
			xMail : $Mail,
			xManzana : $Manzana,
			xMeses : $Meses,
			xNombre : $Nombre,
			xNro: $nro_calle,
			xNro_Carpeta: $Nro_Carpeta,
			xNro_Legajo : $Nro_Legajo,
			xObservaciones : $Observaciones,
			xObra_Social: $Obra_Social,
			xSeccion : $Seccion,
			xTelefono : $Telefono,
			xTrabajo : $Trabajo
		);
		$Persona->setDomicilio();
		if (!empty($georeferencia_point)) {
			$Persona->setGeoreferencia($georeferencia_point);
		}
		$Persona->save();

		if ($Persona->getEdad() == 0) {
			$Persona->update_edad_meses();
		}
		$accion = new Accion(
			xaccountid: $ID_Usuario,
			xFecha : $Fecha,
			xDetalles: $Detalles,
			xID_TipoAccion: $ID_TipoAccion	 
		);
		$accion->save();
		$Mensaje = "La persona fue registrada Correctamente";
		header('Location: ../view_newpersonas.php?Mensaje=' . $Mensaje);
	}
	
} catch (Exception $e) {
	echo $e->getMessage();
}
