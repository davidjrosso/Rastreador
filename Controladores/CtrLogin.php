<?php  
session_start();

require_once("Conexion.php");

$Con = new Conexion();
$Con->OpenConexion();
$ConsultarDatosPersonas = "select * 
						   from persona 
						   where fecha_nac is not null
						   	 and fecha_nac <> 'null'
						   	 and estado = 1";
$MensajeErrorDatosPersonas = "No se pudieron consultar los datos de las personas registradas en el sistema";
$EjecutarConsultarDatosPersonas = mysqli_query($Con->Conexion,$ConsultarDatosPersonas) or die($MensajeErrorDatosPersonas);

$Fecha_Nacimiento = 'null';
while($RetDatosPersonas = mysqli_fetch_assoc($EjecutarConsultarDatosPersonas)){
	$ID = $RetDatosPersonas['id_persona'];
	$Edad = (isset($RetDatosPersonas['edad']))?$RetDatosPersonas['edad']:null;
	$Fecha_Nacimiento = $RetDatosPersonas['fecha_nac'];
	if($Fecha_Nacimiento != 'null' && $Fecha_Nacimiento != ''){
		list($ano,$mes,$dia) = explode("-",$Fecha_Nacimiento);
		$ano_diferencia = date("Y") - $ano;
		$mes_diferencia = date("m") - $mes;
		$dia_diferencia = date("d") - $dia;
		if($dia_diferencia < 0 || $mes_diferencia > 0){
			$ano_diferencia--;
		}
		$Edad = $ano_diferencia;
	}

	if($Fecha_Nacimiento != 'null'){
		$Fecha_Actual = new DateTime();
		$Fecha_Nacimiento_Registrada = new DateTime($Fecha_Nacimiento);
		$Diferencia = $Fecha_Nacimiento_Registrada->diff($Fecha_Actual);
		//$Meses = ($Diferencia->y * 12) + $Diferencia->m + 1;
		$Meses = $Diferencia->m;
	}

	$ActualizarDatosPersonas = "update persona set edad = $Edad, meses = $Meses where id_persona = $ID";
	$MensajeErrorActualizar = "No se pudieron actualizar los datos de las personas registradas";
	mysqli_query($Con->Conexion,$ActualizarDatosPersonas) or die($MensajeErrorActualizar);
}

$Con->CloseConexion();

$UserName = $_REQUEST["UserName"];
$UserPass = md5($_REQUEST["UserPass"]);

if(isset($_SESSION["Usuario"])){
	header("Location: ../view_inicio.php");
}else{
	$Con = new Conexion();
	$Con->OpenConexion();
 	$Consulta = "select * from accounts where username = '$UserName' and password = '$UserPass'";
 	$RS = mysqli_query($Con->Conexion,$Consulta)or die("Problemas al tomar Sesion. Nombre de usuario o password incorrectos");
 	$Cont = mysqli_num_rows($RS);
 	if($Cont > 0){
 		$Ret = mysqli_fetch_assoc($RS);
		$_SESSION["Usuario"] = $Ret["accountid"];		
		header("Location: ../view_inicio.php");
 	}else{
 		$MensajeError = "Nombre de Usuario o Password incorrectos";
 		header("Location: ../index.php?MensajeError=".$MensajeError);
 	}
 	
}


/*
 *
 * This file is part of Rastreador3.
 *
 * Rastreador3 is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Rastreador3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Rastreador3; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 */


?>