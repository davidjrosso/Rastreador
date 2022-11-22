<?php 
require_once 'Conexion.php';
require_once '../Modelo/Vehiculo.php';

$Numero = $_REQUEST["Numero"];
$Dominio = $_REQUEST["Dominio"];
$Detalle = $_REQUEST["Detalle"];
$Modelo = $_REQUEST["Modelo"];
$Area = $_REQUEST["Area"];
$Combustible = $_REQUEST["Combustible"];


$Vehiculo = new Vehiculo($Numero,$Dominio,$Detalle,$Modelo,$Area,$Combustible);
$Con = new Conexion();
$Con->OpenConexion();
$Consulta = "insert into vehi(Numero,Dominio,Detalle,Modelo,area,'Tipo de Combustible') values('".$Vehiculo->getNumero()."','".$Vehiculo->getDominio()."','".$Vehiculo->getDetalle()."',".$Vehiculo->getModelo().",".$Vehiculo->getArea().",".$Vehiculo->getTipoCombustible().")";
$Ret = mysqli_query($Con->Conexion,$Consulta)or die("Problemas en la consulta");
$Con->CloseConexion();

echo "Se inserto";

?>