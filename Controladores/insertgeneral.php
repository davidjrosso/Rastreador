<?php 
require_once 'Conexion.php';
require_once '../Modelo/General.php';

$Fecha = $_REQUEST["date"];
$NroRemito = $_REQUEST["Remito"];
$NroCompromiso = $_REQUEST["Compromiso"];
$Agente = $_REQUEST["Agente"];
$Vehiculo = $_REQUEST["Vehiculo"];
$Proveedor = $_REQUEST["Proveedor"];
$Combustible = $_REQUEST["Combustible"];
$Litros = $_REQUEST["Litros"];
$Precio = $_REQUEST["Precio"];

$General = new General(0,$Fecha,$Proveedor,$Vehiculo,$Agente,$Combustible,$Precio,$NroRemito,$NroCompromiso,$Litros);
$Con = new Conexion();
$Con->OpenConexion();
$Consulta = "insert into general(Id,Fecha,Prov,Vehiculo,Agente,Combustible,Precio,nroRemito,nroCompromiso,LitrosCombustible) values(1111,'".$General->getFecha()."',".$General->getProv().",".$General->getVehiculo().",".$General->getAgente().",".$General->getCombustible().",'".$General->getPrecio()."',".$General->getNroRemito().",".$General->getNroCompromiso().",".$General->getLitrosCombustible().")";
$Ret = mysqli_query($Con->Conexion,$Consulta)or die("Problemas en la consulta");
$Con->CloseConexion();

header("Location: ../view_general.php");

?>