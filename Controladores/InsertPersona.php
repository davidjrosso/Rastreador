<?php 
session_start();
require_once 'Conexion.php';
require_once '../Modelo/Persona.php';
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

///////////////////////////CALCULAR EDAD//////////////////////////////////////////////////
/*if (($Edad == 'null' || is_null($Edad)) 
	&& ($Fecha_Nacimiento != 'null' && !is_null($Fecha_Nacimiento))) {
	list($ano,$mes,$dia) = explode("-", $Fecha_Nacimiento);
	$ano_diferencia = date("Y") - $ano;
	$mes_diferencia = date("m") - $mes;
	$dia_diferencia = date("d") - $dia;
	if ($dia_diferencia < 0 || $mes_diferencia < 0) {
		$ano_diferencia--;
	}
	$Edad = $ano_diferencia;
}*/
if (($Edad == 'null' || is_null($Edad)) 
	&& ($Fecha_Nacimiento != 'null' && !is_null($Fecha_Nacimiento))) {
	list($ano,$mes,$dia) = explode("-", $Fecha_Nacimiento);
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
if ($Fecha_Nacimiento != 'null' || !is_null($Fecha_Nacimiento)) {
	$Fecha_Actual = new DateTime();
	$Fecha_Nacimiento_Registrada = new DateTime($Fecha_Nacimiento);
	$Diferencia = $Fecha_Nacimiento_Registrada->diff($Fecha_Actual);
	$Meses = $Diferencia->m;
	$Edad = $Diferencia->y;
}
////////////////////////////////////////////////////////////////////////////////////////	/
$Nro_Carpeta = $_REQUEST["Nro_Carpeta"];
if (empty($Nro_Carpeta)) {
	$Nro_Carpeta = null;
}
$Obra_Social = $_REQUEST["Obra_Social"];
$Domicilio = ucwords($_REQUEST["Calle"]);
$Domicilio .= " " . $_REQUEST["NumeroDeCalle"];
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
			xNro_Carpeta: $Nro_Carpeta,
			xNro_Legajo : $Nro_Legajo,
			xObservaciones : $Observaciones,
			xObra_Social: $Obra_Social,
			xSeccion : $Seccion,
			xTelefono : $Telefono,
			xTrabajo : $Trabajo
		);
		$Persona->save();

		//TOMAR DATOS PARA ACTUALIZAR MESES
		$Con = new Conexion();
		$Con->OpenConexion();
		if ($Persona->getEdad() == 0) {
			$ConsultarID_Persona = "select id_persona from persona where nombre = '{$Persona->getNombre()}' and apellido = '{$Persona->getApellido()}' and documento = '{$Persona->getDNI()}' limit 1";
			$MensajeErrorConsultarID_Persona = "No se pudo consultar el ID de la persona";
			$EjecutarConsultarID_Persona = mysqli_query(
				$Con->Conexion,
				$ConsultarID_Persona
			) or die($MensajeErrorConsultarID_Persona);
			$TomarID_Persona = mysqli_fetch_assoc($EjecutarConsultarID_Persona);
			$RetID_PersonaRegistrada = $TomarID_Persona["id_persona"];
			
			$RegistrarMeses = "update persona set meses = $Meses where id_persona = $RetID_PersonaRegistrada";
			$MensajeErrorRegistrarMeses = "No se pudo actualizar los meses";
			$EjecutarRegistrarMeses = mysqli_query($Con->Conexion,$RegistrarMeses) or die($MensajeErrorRegistrarMeses);
		}

		$ConsultaAccion = "INSERT INTO Acciones(accountid, Fecha, Detalles, ID_TipoAccion) 
						   VALUES($ID_Usuario, '$Fecha', '$Detalles', $ID_TipoAccion)";
		if (!$RetAccion = mysqli_query($Con->Conexion, $ConsultaAccion)) {
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 1);
		}
		$Con->CloseConexion();
		$Mensaje = "La persona fue registrada Correctamente";
		header('Location: ../view_newpersonas.php?Mensaje='.$Mensaje);
	}
	
} catch (Exception $e) {
	echo $e->getMessage();
}
?>