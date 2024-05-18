<?php
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
//use Dompdf\Options;

try{
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
} catch (Exception $e) {
    header("HTTP/1.1 503 Service Unavailable");
    header('Content-Type: text/plain');
    echo 'Error producido al generar el pdf: ',  $e->getMessage(), "\n";
}