<?php
session_start();
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

try{
    $json_filas = file_get_contents('php://input');
    $array_filas = json_decode($json_filas, true);
    $row = "";
    $mesesHeader = $_SESSION["meses"];
    for ($i = 0; $i < (count($array_filas) - 1); $i++) {
        $row .= "<tr>
                    <td style='width: 60px'>" . 
                        (isset(($array_filas[$i]["barrio"])) ? $array_filas[$i]["barrio"] : "" ) . "
                    </td>
                    <td style='width: 60px'>" . 
                        (isset(($array_filas[$i]["domicilio"])) ? $array_filas[$i]["domicilio"] : "" ) . "
                    </td>
                    <td style='width: 60px'>" . 
                        (isset(($array_filas[$i]["persona"])) ? $array_filas[$i]["persona"] : "" ) . "
                    </td>
                    <td style='width: 60px'>" . 
                        (isset(($array_filas[$i]["fechanac"])) ? $array_filas[$i]["fechanac"] : "" ) . "
                    </td>";
        /*for ($h = 0; $h < count($mesesHeader); $h++) {
            if (isset($array_filas[$i][$mesesHeader[$h]])) {
                $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                          bloqueMotivos($array_filas[$i][$mesesHeader[$h]]) . "
                        </td>";
            } else {
                $row .=  "<td></td>";
            }
        }*/
        if (isset($array_filas[$i][$mesesHeader[0]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[0]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
        if (isset($array_filas[$i][$mesesHeader[1]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[1]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
        if (isset($array_filas[$i][$mesesHeader[2]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[2]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
        if (isset($array_filas[$i][$mesesHeader[3]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[3]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
        if (isset($array_filas[$i][$mesesHeader[4]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[4]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
        if (isset($array_filas[$i][$mesesHeader[5]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[5]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
        if (isset($array_filas[$i][$mesesHeader[6]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[6]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
        if (isset($array_filas[$i][$mesesHeader[7]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[7]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
        if (isset($array_filas[$i][$mesesHeader[8]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[8]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
        if (isset($array_filas[$i][$mesesHeader[9]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[9]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
        if (isset($array_filas[$i][$mesesHeader[10]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[10]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
        if (isset($array_filas[$i][$mesesHeader[11]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[11]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
        if (isset($array_filas[$i][$mesesHeader[12]])) {
            $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                      bloqueMotivos($array_filas[$i][$mesesHeader[11]]) . "
                    </td>";
        } else {
            $row .=  "<td></td>";
        }
    }
    $table = "<html>
                <head>
                <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

                    <style>
                    @page {
                        margin: 15px !important;
                        padding: 15px !important;
                    }
                    .table--border-colapsed{
                        border-collapse: collapse;
                        width: 100%;
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

                    "<table class='table--border-colapsed'>
                    <thead>
                    <tr>
                        <th>Barrio</th>
                        <th>Direc.</th>
                        <th>Persona</th>
                        <th>Fecha Nac.</th>
                        <th>" . $mesesHeader[0]  . "</th>
                        <th>" . $mesesHeader[1] . "</th>
                        <th>" . $mesesHeader[2] . "</th>
                        <th>" . $mesesHeader[3] . "</th>
                        <th>" . $mesesHeader[4] . "</th>
                        <th>" . $mesesHeader[5] . "</th>
                        <th>" . $mesesHeader[6] . "</th>
                        <th>" . $mesesHeader[7] . "</th>
                        <th>" . $mesesHeader[8] . "</th>
                        <th>" . $mesesHeader[9] . "</th>
                        <th>" . $mesesHeader[10] . "</th>
                        <th>" . $mesesHeader[11] . "</th>
                        <th>" . $mesesHeader[12] . "</th>
                    </tr>
            </thead>
            <tbody>". 
                $row."
            </tbody>
        </body>
    </html>";
    $dompdf = new Dompdf();
    $dompdf->loadHtml(mb_convert_encoding($table, 'HTML-ENTITIES', 'UTF-8'));
    $dompdf->setPaper('legal', 'landscape');
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

function concatMotivo($acumulado, $elemento)
{
    $acumulado .= "<div style='display:inline-block; width: 15px'> 
                     <span style='color: ".$elemento[2] . "'>" . 
                        $elemento[0] . "
                     </span>" . 
                     $elemento[1]  . "
                   </div>";
    return $acumulado;
}
function bloqueMotivos($array_motivos)
{   $bloque = "<div>";
    if (count($array_motivos) > 5) {
       $bloque .= array_reduce(array_slice($array_motivos, 0, 5), "concatMotivo", $bloque);
       $bloque .= "<div>";
       $bloque .= array_reduce(array_slice($array_motivos, 6), "concatMotivo", $bloque);
       $bloque .= "</div></div>";
    } else {
        $bloque .= array_reduce($array_motivos, "concatMotivo", $bloque);
        $bloque .= "<div>";
    }
    return $bloque;
}