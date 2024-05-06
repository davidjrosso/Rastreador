<?php
require_once 'Conexion.php';
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
//use Dompdf\Options;

$tabla = $_REQUEST["tabla"];

//$options = new Options();
//$options->set('isRemoteEnabled',true);
//$dompdf = new Dompdf($options);

$dompdf = new Dompdf();
$dompdf->loadHtml($tabla);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$output = $dompdf->output();
$data = base64_encode($output);

header('Content-Type: application/pdf');
echo $data;