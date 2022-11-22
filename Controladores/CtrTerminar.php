<?php  
require_once 'Conexion.php';

$Registros = $_REQUEST["Registros"];
$NroCompromiso = $_REQUEST["NewNroCompromiso"];

date_default_timezone_set('America/Argentina/Cordoba');
$NombreDocumento = $hoy = date("d-m-Y H:i:s"); 


$Con = new Conexion();
$Con->OpenConexion();





foreach ($Registros as $Reg) {
	$Consulta = "update general set nroCompromiso = $NroCompromiso where Id = $Reg";
	$Ret = mysqli_query($Con->Conexion,$Consulta)or die("Problemas en la consulta");
}

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$NombreDocumento.doc");

$ConsultaProv = mysqli_query($Con->Conexion,"select P.NombProv from general G, prov P where G.Id = $Reg and G.Prov = P.IdProv")or die("Problemas en la consulta");
$RetProv = mysqli_fetch_assoc($ConsultaProv);


echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' crossorigin='anonymous'>";
echo "<body>";
echo "<h1 style = 'font-style: italic;'>Listado de Consumo de Combustibles</h1>";
echo "<p style = 'font-weight: bold; font-size: 20px;'>Proveedor - ".$RetProv["NombProv"]."</p><br/>";
echo "<p>Compromiso N&ordm;  2327</p><br/>";
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

$Consulta2 = mysqli_query($Con->Conexion,"select G.Fecha, G.nroRemito, C.TipoConbu, V.Detalle, V.Dominio, G.Precio, G.LitrosCombustible, A.Leg, A.Apellido, A.Nombre, AR.Area from general G, combus C,agen A,vehi V, area AR where G.Id = $Reg and AR.IdArea = A.Area and A.IDAgente = G.Agente and V.idVehi = G.Vehiculo and C.IdConbus = G.Combustible")or die("Problemas en la consulta");
while($Ret = mysqli_fetch_assoc($Consulta2)){
	echo "<tr>";
	echo "<td style='text-align: center;'>".$Ret["Fecha"]."</td>";
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