<?php 
session_start();
require_once 'Conexion.php';
require_once '../Modelo/Persona.php';
header("Content-Type: text/html;charset=utf-8");

$ID_Usuario = $_SESSION["Usuario"];

$Apellido = ucwords($_REQUEST["Apellido"]);
$Nombre = ucwords($_REQUEST["Nombre"]);
$DNI = trim(str_replace(array('.'),'',$_REQUEST["DNI"]));

$Nro_Legajo = $_REQUEST["Nro_Legajo"];
if(empty($Nro_Legajo)){
	$Nro_Legajo = 'null';
}

$Edad = $_REQUEST["Edad"];
$Meses = $_REQUEST["Meses"];
if(empty($Edad)){
	$Edad = 'null';
}

if(empty($Meses)){
	$Meses = 'null';
}

if(empty($_REQUEST["Fecha_Nacimiento"])){
	$Fecha_Nacimiento = 'null';
}else{
	$Fecha_Nacimiento = implode("-", array_reverse(explode("/",$_REQUEST["Fecha_Nacimiento"])));
}

///////////////////////////CALCULAR EDAD//////////////////////////////////////////////////
if(($Edad == 'null' || $Edad == "") && $Fecha_Nacimiento != 'null'){
	list($ano,$mes,$dia) = explode("-",$Fecha_Nacimiento);
	$ano_diferencia = date("Y") - $ano;
	$mes_diferencia = date("m") - $mes;
	$dia_diferencia = date("d") - $dia;
	if($dia_diferencia < 0 || $mes_diferencia < 0){
		$ano_diferencia--;
	}
	$Edad = $ano_diferencia;
}


//PROBAR SI ESTO DA LA DIFERENCIA ENTRE MESES NOMAS O TAMBIEN TOMA LOS AÑOS COMO MESES EN ESE CASO TOMAR LA CANTIDAD DE AÑOS Y MULTIPLICARLO POR 12 Y A ESO RESTARLE AL RESULTADO DEL TOTAL DE MESES DE DIFERENCIA.
if($Fecha_Nacimiento != 'null'){
	$Fecha_Actual = new DateTime();
	$Fecha_Nacimiento_Registrada = new DateTime($Fecha_Nacimiento);
	$Diferencia = $Fecha_Nacimiento_Registrada->diff($Fecha_Actual);
	$Meses = $Diferencia->m;
}
/////////////////////////////////////////////////////////////////////////////////////////
$Nro_Carpeta = $_REQUEST["Nro_Carpeta"];
if(empty($Nro_Carpeta)){
	$Nro_Carpeta = 'null';
}
$Obra_Social = $_REQUEST["Obra_Social"];
$Domicilio = ucwords($_REQUEST["Calle"]);
$Domicilio .= " " . $_REQUEST["NumeroDeCalle"];
$ID_Barrio = ucwords($_REQUEST["ID_Barrio"]);
if(empty($ID_Barrio)){
	$ID_Barrio = 37;
}

$Localidad = ucwords($_REQUEST["Localidad"]);

$Circunscripcion = 0;
if(empty($Circunscripcion)){
	$Circunscripcion = 'null';
}
$Seccion = 0;
if(empty($Seccion)){
	$Seccion = 'null';
}
$Manzana = $_REQUEST["Manzana"];
if(empty($Manzana)){
	$Manzana = 'null';
}
$Lote = $_REQUEST["Lote"];
if(empty($Lote)){
	$Lote = 'null';
}
$Familia = $_REQUEST["Familia"];
if(empty($Familia)){
	$Familia = 'null';
}
$Observaciones = ucfirst($_REQUEST["Observaciones"]);
$Cambio_Domicilio = $_REQUEST["Cambio_Domicilio"];
$Telefono = $_REQUEST["Telefono"];
$Mail = ucfirst($_REQUEST["Mail"]);
$Estado = 1;
$ID_Escuela = $_REQUEST["ID_Escuela"];
$Trabajo = strtoupper($_REQUEST["Trabajo"]);

if(empty($ID_Escuela)){
	$ID_Escuela = 2;
}

$Fecha = date("Y-m-d");
$ID_TipoAccion = 1;
$Detalles = "El usuario con ID: $ID_Usuario ha registrado una nueva Persona. Datos: Apellido: $Apellido - Nombre: $Nombre - Documento: $DNI - Nro Legajo: $Nro_Legajo - Edad: $Edad - Meses: $Meses - Fecha de Nacimiento: $Fecha_Nacimiento - Telefono: $Telefono - E-Mail: $Mail - Nro Carpeta: $Nro_Carpeta - Obra Social: $Obra_Social - Domicilio: $Domicilio - Barrio: $ID_Barrio - Escuela: $ID_Escuela - Localidad: $Localidad - Circunscripcion: $Circunscripcion - Seccion: $Seccion - Manzana: $Manzana - Lote: $Lote - Familia: $Familia - Observaciones: $Observaciones - Cambio Domicilio: $Cambio_Domicilio";



$ConsultarRegistrosIguales = "select id_persona from persona where apellido = '$Apellido' and nombre = '$Nombre' and estado = 1";
$MensajeErrorRegistrosIguales = "Hubo un problema al consultar los registros para validar";
$Con = new Conexion();
$Con->OpenConexion();

//$Ret = mysqli_query($ConsultarRegistrosIguales) or die($MensajeErrorRegistrosIguales." Consulta: ".$ConsultarRegistrosIguales);

try {
	if(!$Ret = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
		throw new Exception("Error al consultar registros. Consulta: ".$ConsultarRegistrosIguales, 0);		
	}	
	$Rows = mysqli_num_rows($Ret);
	if($Rows > 0 && $DNI == ''){
		$Mensaje = "Ya existe un Usuario con el mismo Apellido y Nombre que el que esta intentando crear. Por favor ingrese un DNI para identificar a la persona.";
		mysqli_free_result($Ret);
		$Con->CloseConexion();
		header('Location: ../view_newpersonas.php?Mensaje='.$Mensaje);
	}else{
		$Persona = new Persona(0,$Apellido,$Nombre,$DNI,$Nro_Legajo, $Edad,$Meses,$Fecha_Nacimiento,$Nro_Carpeta,$Obra_Social,$Domicilio,$ID_Barrio,$Localidad,$Circunscripcion,$Seccion,$Manzana,$Lote,$Familia,$Observaciones,$Cambio_Domicilio,$Telefono,$Mail,$ID_Escuela,$Estado,$Trabajo);
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "insert into persona(apellido,nombre,documento,nro_legajo,edad,fecha_nac,nro_carpeta,obra_social,domicilio,ID_Barrio,localidad,circunscripcion,seccion,manzana,lote,familia,observacion,cambio_domicilio,telefono,mail,ID_Escuela,meses,estado,Trabajo) 
		values('".$Persona->getApellido()."','".$Persona->getNombre()."','".$Persona->getDNI()."','".$Persona->getNro_Legajo()."',".$Persona->getEdad().",'".$Persona->getFecha_Nacimiento()."',".$Persona->getNro_Carpeta().",'".$Persona->getObra_Social()."','".$Persona->getDomicilio()."',".$Persona->getBarrio().",'".$Persona->getLocalidad()."',".$Persona->getCircunscripcion().",".$Persona->getSeccion().",'".$Persona->getManzana()."',".$Persona->getLote().",".$Persona->getFamilia().",'".$Persona->getObservaciones()."','".$Persona->getCambio_Domicilio()."','".$Persona->getTelefono()."','".$Persona->getMail()."',".$Persona->getID_Escuela().",".$Persona->getMeses().",".$Persona->getEstado().",'".$Persona->getTrabajo()."')";



		$Ret = mysqli_query($Con->Conexion,$Consulta)or die($Consulta);

		//TOMAR DATOS PARA ACTUALIZAR MESES
		
		if($Persona->getEdad() == 0){
			$ConsultarID_Persona = "select id_persona from persona where nombre = '{$Persona->getNombre()}' and apellido = '{$Persona->getApellido()}' and documento = '{$Persona->getDNI()}' limit 1";
			$MensajeErrorConsultarID_Persona = "No se pudo consultar el ID de la persona";
			$EjecutarConsultarID_Persona = mysqli_query($Con->Conexion,$ConsultarID_Persona) or die($MensajeErrorConsultarID_Persona);
			$TomarID_Persona = mysqli_fetch_assoc($EjecutarConsultarID_Persona);
			$RetID_PersonaRegistrada = $TomarID_Persona["id_persona"];

			$RegistrarMeses = "update persona set meses = $Meses where id_persona = $RetID_PersonaRegistrada";
			$MensajeErrorRegistrarMeses = "No se pudo actualizar los meses";
			$EjecutarRegistrarMeses = mysqli_query($Con->Conexion,$RegistrarMeses) or die($MensajeErrorRegistrarMeses);
		}
		///////////////////////////////////////////


		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) 
						   values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
		if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
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