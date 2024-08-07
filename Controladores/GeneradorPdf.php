<?php
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

try{
    $json_filas = file_get_contents('php://input');
    $array_filas = json_decode($json_filas, true);
    $row = "";
    for ($i = 0; $i < count($array_filas); $i++) {
        $row .= "<tr>
            <td>" . $array_filas[$i]["barrio"] . "</td>
            <td>" . $array_filas[$i]["domicilio"] . "</td>
            <td>" . $array_filas[$i]["persona"] . "</td>
            <td>" . $array_filas[$i]["fechanac"] . "</td>
            <td>" . $array_filas[$i]["8/23"] . "</td>
            <td>" . $array_filas[$i]["9/23"] . "</td>
            <td>" . $array_filas[$i]["10/23"] . "</td>
            <td>" . $array_filas[$i]["11/23"] . "</td>
            <td>" . $array_filas[$i]["12/23"] . "</td>
            <td>" . $array_filas[$i]["1/24"] . "</td>
            <td>" . $array_filas[$i]["2/24"] . "</td>
            <td>" . $array_filas[$i]["3/24"] . "</td>
            <td>" . $array_filas[$i]["4/24"] . "</td>
            <td>" . $array_filas[$i]["5/24"] . "</td>
            <td>" . $array_filas[$i]["6/24"] . "</td>
            <td>" . $array_filas[$i]["7/24"] . "</td>
            <td>" . $array_filas[$i]["8/24"] . "</td>
        </tr>";
    }
    $table = "<html>
                <head>
                <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

                    <style>
                    @page {
                        margin: 15px !important;
                        padding: 15px !important;
                    }
                    .table{
                        border-collapse: collapse;
                    }
                    .thead-dark{
                        background-color: #ccc;
                        font-size: 12px;
                    }
                    .table_pdf {
                        width: 100%;
                    }
                    tr td {
                        text-align: center;
                        font-size: 12px;
                    }

                    table thead tr th {
                        background-color: #ccc;
                    }

                    h5, h2{
                        text-align: center;
                        margin-bottom: 0px
                    }

                    #InformacionDeCentro {
                        float: right; 
                        text-align: left;
                    }

                    #frase {
                        font-weight: bold;
                    }

                    #encabezado {
                        text-align: center;
                        float: right;
                        padding-right: 13rem;
                    }

                    #InformacionDeCiudad {
                        text-align: left;
                        margin-bottom: 2rem;
                        margin-top: 2rem;
                    }

                    table, th, td {
                        border: 1px solid;
                    }
                    </style>
                    </head> 
                    <body>".

                    "<table>
                    <thead>
                    <tr>
                        <th >Barrio</th>
                        <th >Direc.</th>
                        <th>Persona</th>
                        <th>Fecha Nac.</th>
                        <th>8/23</th>
                        <th>9/23</th>
                        <th>10/2</th>
                        <th>11/2</th>
                        <th>12/2</th>
                        <th>1/24</th>
                        <th>2/24</th>
                        <th>3/24</th>
                        <th>4/24</th>
                        <th>5/24</th>
                        <th>6/24</th>
                        <th>7/24</th>
                        <th>8/24</th>
                    </tr>
            </thead>
            <tbody>". 
                $row."
            </tbody>
        </body>
    </html>";
    $dompdf = new Dompdf();
    $dompdf->loadHtml(mb_convert_encoding($table, 'HTML-ENTITIES', 'UTF-8'));
    //$dompdf->setPaper('legal', 'landscape');
    $dompdf->render();
    $output = $dompdf->output();
    $file = fopen("file.pdf", "w+");
    $out = fwrite($file, $output);
    $data = base64_encode($output);
    header('Content-Type: application/pdf');
    echo $data;
} catch (Exception $e) {
    header("HTTP/1.1 503 Service Unavailable");
    header('Content-Type: text/plain');
    echo 'Error producido al generar el pdf: ',  $e->getMessage(), "\n";
}
