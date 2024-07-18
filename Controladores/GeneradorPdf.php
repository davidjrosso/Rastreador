<?php
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
//use Dompdf\Options;

try{
    $tabla = $_REQUEST["tabla"];
    $dompdf = new Dompdf(array('tempDir'=>$_SERVER["DOCUMENT_ROOT"]."/Rastreador/dompdf/lib/fonts"));
    $dompdf->loadHtml(mb_convert_encoding($tabla, 'HTML-ENTITIES', 'UTF-8'));
    $dompdf->setPaper('legal', 'landscape');
    $dompdf->setOptions($dompdf->getOptions()->setFontDir($_SERVER["DOCUMENT_ROOT"]."/Rastreador/dompdf/lib/fonts"));
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