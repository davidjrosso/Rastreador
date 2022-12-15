<?php  

$Filtro = $_REQUEST["Search"];
$ID_Filtro = $_REQUEST["ID_Filtro"];

header("Location: ../view_personas.php?Filtro=".$Filtro."&ID_Filtro=".$ID_Filtro);

?>