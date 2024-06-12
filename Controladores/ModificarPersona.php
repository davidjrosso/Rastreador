<?php 
session_start();
require_once 'Conexion.php';
require_once '../Modelo/Persona.php';
header("Content-Type: text/html;charset=utf-8");

$ID_Usuario = $_SESSION["Usuario"];

$ID_Persona = $_REQUEST["ID"];
$Apellido = ucwords($_REQUEST["Apellido"]);
$Nombre = ucwords($_REQUEST["Nombre"]);
$DNI = trim(str_replace(array('.'),'',$_REQUEST["DNI"]));
$Nro_Legajo = $_REQUEST["Nro_Legajo"];

$Edad = $_REQUEST["Edad"];
$Meses = $_REQUEST["Meses"];

if(empty($_REQUEST["Fecha_Nacimiento"])){
	$Fecha_Nacimiento = 'null';
}else{
	$Fecha_Nacimiento = implode("-", array_reverse(explode("/",$_REQUEST["Fecha_Nacimiento"])));
}

///////////////////////////CALCULAR EDAD//////////////////////////////////////////////////
if($Edad == 'null' || $Edad == ""){
	list($ano,$mes,$dia) = explode("-",$Fecha_Nacimiento);
	$ano_diferencia = date("Y") - $ano;
	$mes_diferencia = date("m") - $mes;
	$dia_diferencia = date("d") - $dia;
	if($dia_diferencia < 0 || $mes_diferencia < 0){
		$ano_diferencia--;
	}
	$Edad = $ano_diferencia;
}


if($Edad == 0){
	$Fecha_Actual = new DateTime();
	$Fecha_Nacimiento_Registrada = new DateTime($Fecha_Nacimiento);
	$Diferencia = $Fecha_Nacimiento_Registrada->diff($Fecha_Actual);
	$Meses = $Diferencia->m;
}
/////////////////////////////////////////////////////////////////////////////////////////


$Nro_Carpeta = $_REQUEST["Nro_Carpeta"];
$Obra_Social = $_REQUEST["Obra_Social"];
$Domicilio = ucwords($_REQUEST["Calle"]);
if(isset($_REQUEST["NumeroDeCalle"])){
  $Domicilio .= " ".$_REQUEST["NumeroDeCalle"];
}
$ID_Barrio = $_REQUEST["ID_Barrio"];
$Localidad = ucwords($_REQUEST["Localidad"]);
$Circunscripcion = 0;
$Seccion = 0;
$Manzana = $_REQUEST["Manzana"];
$Lote = $_REQUEST["Lote"];
$Familia = ucfirst($_REQUEST["Familia"]);
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



// PASANDO LOS DATOS NUMERICOS VACIOS A NULL
if(empty($Edad)){
	$Edad = 'null';
}
if(empty($Meses)){
	$Meses = 'null';
}
if(empty($Nro_Carpeta)){
	$Nro_Carpeta = 'null';
}
if(empty($Circunscripcion)){
	$Circunscripcion = 'null';
}
if(empty($Seccion)){
	$Seccion = 'null';
}
if(empty($Manzana)){
	$Manzana = 'null';
}
if(empty($Lote)){
	$Lote = 'null';
}
if(empty($Familia)){
	$Familia = 'null';
}

if(empty($ID_Barrio)){
	$ID_Barrio = 37;
}



/////////////////////////////////////////////

$Persona = new Persona($ID_Persona,$Apellido,$Nombre,$DNI,$Nro_Legajo,$Edad,$Meses,$Fecha_Nacimiento,$Nro_Carpeta,$Obra_Social,$Domicilio,$ID_Barrio,$Localidad,$Circunscripcion,$Seccion,$Manzana,$Lote,$Familia,$Observaciones,$Cambio_Domicilio,$Telefono,$Mail,$ID_Escuela,$Estado,$Trabajo);


$Fecha = date("Y-m-d");
$ID_TipoAccion = 2;

try {
	$Con = new Conexion();
	$Con->OpenConexion();


	$ConsultarRegistrosIguales = "select * from persona where apellido = '{$Persona->getApellido()}' and nombre = '{$Persona->getNombre()}' and id_persona != $ID_Persona and estado = 1";
	if(!$RetIguales = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
		throw new Exception("Problemas al intentar Consultar Registros Iguales", 0);		
	}

	$Registros = mysqli_num_rows($RetIguales);
	if($Registros > 0){
		mysqli_free_result($RetIguales);
		$Con->CloseConexion();
		$Mensaje = "Ya existe una Persona con ese Apellido y Nombre por Favor Introduzca Otros Datos";
		header('Location: ../view_modpersonas.php?ID='.$ID_Persona.'&MensajeError='.$Mensaje);
	}else{
		$ConsultarDatosViejos = "select * from persona where id_persona = $ID_Persona and estado = 1";
		$ErrorDatosViejos = "No se pudieron consultar los datos";
		if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
			throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
		}		
		$TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
		$ID_Persona_Viejo = $TomarDatosViejos["id_persona"];
		$Apellido_Viejo = $TomarDatosViejos["apellido"];
		$Nombre_Viejo = $TomarDatosViejos["nombre"];
		$DNI_Viejo = $TomarDatosViejos["documento"];
		
		$Nro_Legajo_Viejo = $TomarDatosViejos["nro_legajo"];

		$Edad_Viejo = $TomarDatosViejos["edad"];
		$Meses_Viejo = $TomarDatosViejos["meses"];
		$Fecha_Nacimiento_Viejo = $TomarDatosViejos["fecha_nac"];
		$Nro_Carpeta_Viejo = $TomarDatosViejos["nro_carpeta"];
		$Obra_Social_Viejo = $TomarDatosViejos["obra_social"];
		$Domicilio_Viejo = $TomarDatosViejos["domicilio"];
		$ID_Barrio_Viejo = $TomarDatosViejos["ID_Barrio"];
		$Localidad_Viejo = $TomarDatosViejos["localidad"];
		$Circunscripcion_Viejo = $TomarDatosViejos["circunscripcion"];
		$Seccion_Viejo = $TomarDatosViejos["seccion"];
		$Manzana_Viejo = $TomarDatosViejos["manzana"];
		$Lote_Viejo = $TomarDatosViejos["lote"];
		$Familia_Viejo = $TomarDatosViejos["familia"];
		$Observaciones_Viejo = $TomarDatosViejos["observacion"];
		$Cambio_Domicilio_Viejo = $TomarDatosViejos["cambio_domicilio"];
		$Telefono_Viejo = $TomarDatosViejos["telefono"];
		$Mail_Viejo = $TomarDatosViejos["mail"];
		$ID_Escuela_Viejo = $TomarDatosViejos["ID_Escuela"];
		$Estado_Viejo = 0;
		$Trabajo_Viejo = $TomarDatosViejos["Trabajo"];

		$Persona_Viejo = new Persona($ID_Persona_Viejo,$Apellido_Viejo,$Nombre_Viejo,$DNI_Viejo,$Nro_Legajo_Viejo,$Edad_Viejo,$Meses_Viejo,$Fecha_Nacimiento_Viejo,$Nro_Carpeta_Viejo,$Obra_Social_Viejo,$Domicilio_Viejo,$ID_Barrio_Viejo,$Localidad_Viejo,$Circunscripcion_Viejo,$Seccion_Viejo,$Manzana_Viejo,$Lote_Viejo,$Familia_Viejo,$Observaciones_Viejo,$Cambio_Domicilio_Viejo,$Telefono_Viejo,$Mail_Viejo,$ID_Escuela_Viejo,$Estado_Viejo,$Trabajo_Viejo);

		$Consulta = "update persona set apellido = '{$Persona->getApellido()}', nombre = '{$Persona->getNombre()}', documento = '{$Persona->getDNI()}', nro_legajo = '{$Persona->getNro_Legajo()}', edad = {$Persona->getEdad()}, fecha_nac = '{$Persona->getFecha_Nacimiento()}', telefono = '{$Persona->getTelefono()}', mail = '{$Persona->getMail()}', nro_carpeta = {$Persona->getNro_Carpeta()}, obra_social = '{$Persona->getObra_Social()}', domicilio = '{$Persona->getDomicilio()}', ID_Barrio = {$Persona->getBarrio()}, localidad = '{$Persona->getLocalidad()}', circunscripcion = {$Persona->getCircunscripcion()}, seccion = {$Persona->getSeccion()}, manzana = '{$Persona->getManzana()}', lote = {$Persona->getLote()}, familia = {$Persona->getFamilia()}, observacion = '{$Persona->getObservaciones()}', cambio_domicilio = '{$Persona->getCambio_Domicilio()}', Telefono = '{$Persona->getTelefono()}', Mail = '{$Persona->getMail()}', ID_Escuela = {$Persona->getID_Escuela()}, Meses = {$Persona->getMeses()}, Trabajo = '{$Persona->getTrabajo()}' where id_persona = {$Persona->getID_Persona()}";

		

		if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
			throw new Exception("Problemas en la consulta".$Consulta, 2);		
		}


		$Detalles = "El usuario con ID: $ID_Usuario ha modificado una Persona. Datos: Dato Anterior: {$Persona_Viejo->getApellido()} , Dato Nuevo: {$Persona->getApellido()} - Dato Anterior: {$Persona_Viejo->getNombre()} , Dato Nuevo: {$Persona->getNombre()} - Dato Anterior: {$Persona_Viejo->getDNI()}, Dato Nuevo: {$Persona->getDNI()} - Dato Anterior: {$Persona_Viejo->getNro_Legajo()}, Dato Nuevo: {$Persona->getNro_Legajo()} - Dato Anterior: {$Persona_Viejo->getEdad()}, Dato Nuevo: {$Persona->getEdad()} - Dato Anterior: {$Persona_Viejo->getFecha_Nacimiento()}, Dato Nuevo: {$Persona->getFecha_Nacimiento()} - Dato Anterior: {$Persona_Viejo->getTelefono()}, Dato Nuevo: {$Persona->getTelefono()} - Dato Anterior: {$Persona_Viejo->getMail()}, Dato Nuevo: {$Persona->getMail()} - Dato Anterior: {$Persona_Viejo->getNro_Carpeta()}, Dato Nuevo: {$Persona->getNro_Carpeta()} - Dato Anterior: {$Persona_Viejo->getObra_Social()}, Dato Nuevo: {$Persona->getObra_Social()} - Dato Anterior: {$Persona_Viejo->getSeccion()}, Dato Nuevo: {$Persona->getSeccion()} - Dato Anterior: {$Persona_Viejo->getManzana()}, Dato Nuevo: {$Persona->getManzana()} - Dato Anterior: {$Persona_Viejo->getLote()}, Dato Nuevo: {$Persona->getLote()} - Dato Anteior: {$Persona_Viejo->getFamilia()}, Dato Nuevo: {$Persona->getFamilia()} - Dato Anterior: {$Persona_Viejo->getObservaciones()}, Dato Nuevo: {$Persona->getObservaciones()} - Dato Anterior: {$Persona_Viejo->getCambio_Domicilio()}, Dato Nuevo: {$Persona->getCambio_Domicilio()} - Dato Anterior: {$Persona_Viejo->getTelefono()}, Dato Nuevo: {$Persona->getTelefono()} - Dato Anterior: {$Persona_Viejo->getMail()}, Dato Nuevo: {$Persona->getMail()} - Dato Anterior: {$Persona_Viejo->getID_Escuela()}, Dato Nuevo: {$Persona->getID_Escuela()} - Dato Anterior: {$Persona_Viejo->getMeses()}, Dato Nuevo: {$Persona->getMeses()}";
		$ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";


		if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
		}

		// CREANDO NOTIFICACION PARA EL USUARIO		
		$DetalleNot = 'Se modifico la persona Nombre: '.$Persona->getApellido(). ', '.$Persona->getNombre(). (($Persona->getDNI() == null)?'':' dni: '. $Persona->getDNI());
		$Expira = date("Y-m-d", strtotime($Fecha." + 15 days"));
		
		$ConsultaNot = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('$DetalleNot','$Fecha', '$Expira',1)";
		if(!$RetNot = mysqli_query($Con->Conexion,$ConsultaNot)){
			throw new Exception("Error al intentar registrar Notificacion. Consulta: ".$ConsultaNot, 3);
		}

	 	$Con->CloseConexion();
	 	$Mensaje = "La Persona fue modificada Correctamente";
		header('Location: ../view_modpersonas.php?ID='.$ID_Persona.'&Mensaje='.$Mensaje);		
	}

} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>