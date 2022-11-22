<?php 
require_once 'Conexion.php';
require_once '../Modelo/Combustible.php';

$NombreCombustible = $_REQUEST["NombreComb"];
$Precio = $_REQUEST["Precio"];


$Combustible = new Combustible($NombreCombustible,$Precio);
$Con = new Conexion();
$Con->OpenConexion();
$Consulta = "insert into Combus(TipoConbu,precioxL) values('".$Combustible->getTipoCombustible()."',".$Combustible->getPrecioxLitro().")";
$Ret = mysqli_query($Con->Conexion,$Consulta)or die("Problemas en la consulta");
$Con->CloseConexion();

echo "Se inserto";

?>