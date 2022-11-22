<?php  
require_once 'Conexion.php';

$Vehiculo = $_REQUEST["Vehiculo"];
$Fecha1 = date('Y-d-m', strtotime($_REQUEST["date1"]));
$Fecha2 = date('Y-d-m', strtotime($_REQUEST["date2"]));

date_default_timezone_set('America/Argentina/Cordoba');
$NombreDocumento = $hoy = date("d-m-Y H:i:s"); 


$Con = new Conexion();
$Con->OpenConexion();

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$NombreDocumento.doc");

echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' crossorigin='anonymous'>";
echo "<body>";
echo "<h1 style = 'font-style: italic;'>Listado de Consumo de Combustibles</h1>";
echo "<table class='table' style = 'border: 1px; border-color: #000; border-style: solid;'>";
echo "<thead class = 'thead-dark'>
    <tr>
      <th scope='col'>Fecha</th>
      <th scope='col'>Remito</th>
      <th scope='col'>Combustible</th>
      <th scope='col'>Vehiculo</th>
      <th scope='col'>N Patente</th>
      <th scope='col'>Importe</th>
      <th scope='col'>Cant</th>
      <th scope='col'>Legajo</th>
      <th scope='col'>Apellido</th>
      <th scope='col'>Nombre</th>
      <th scope='col'>Area</th>
    </tr>
  </thead>";
echo "<tr>";

$Consulta2 = mysqli_query($Con->Conexion,"select G.Fecha2, G.nroRemito, C.TipoConbu, V.Detalle, V.Dominio, G.Precio, G.LitrosCombustible, A.Leg, A.Apellido, A.Nombre, AR.Area from general G, combus C,agen A,vehi V, area AR where AR.IdArea = A.Area and A.IDAgente = G.Agente and V.idVehi = G.Vehiculo and C.IdConbus = G.Combustible and V.idVehi = $Vehiculo and G.Fecha2 between '$Fecha1' and '$Fecha2' order by G.Fecha2 ")or die("Problemas en la consulta");
while($Ret = mysqli_fetch_assoc($Consulta2)){
	$Fecha = date('d-m-Y', strtotime($Ret["Fecha2"]));		
	echo "<tr>";
	echo "<td style='text-align: center;'>".$Fecha."</td>";
	echo "<td style='text-align: center;'>".$Ret["nroRemito"]."</td>";
	echo "<td>".$Ret["TipoConbu"]."</td>";
	echo "<td>".$Ret["Detalle"]."</td>";
	echo "<td>".$Ret["Dominio"]."</td>";
	echo "<td style='text-align: center;'>".$Ret["Precio"]."</td>";
	echo "<td style='text-align: center;'>".$Ret["LitrosCombustible"]."</td>";
	echo "<td style='text-align: center;'>".$Ret["Leg"]."</td>";
	echo "<td>".$Ret["Apellido"]."</td>";
	echo "<td>".$Ret["Nombre"]."</td>";
	echo "</tr>";
}
echo "</table>";

echo "</body>";
echo "</html>";

$Con->CloseConexion();

?>