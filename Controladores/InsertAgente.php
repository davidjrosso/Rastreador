<?php 
require_once 'Conexion.php';
require_once '../Modelo/Agente.php';
require_once '../Modelo/Movimiento.php';

$Apellido = $_REQUEST["Apellido"];
$Nombre = $_REQUEST["Nombre"];
$Leg = $_REQUEST["Leg"];
$Area = $_REQUEST["Area"];


$Agente2 = new Agente($Apellido,$Nombre,$Leg,$Area);
$Con = new Conexion();
$Con->OpenConexion();
$Consulta = "insert into agen(Apellido,Nombre,Leg,Area) values('".$Agente2->getApellido()."','".$Agente2->getNombre()."',".$Agente2->getLeg().",".$Agente2->getArea().")";
$Ret = mysqli_query($Con->Conexion,$Consulta)or die("Problemas en la consulta");
$Con->CloseConexion();

echo "Se inserto";

?>