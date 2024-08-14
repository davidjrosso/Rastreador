<?php
session_start();
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$mesesHeader = (isset($_SESSION["meses"])) ? $_SESSION["meses"] : null;
$from_reporte_listado = (isset($_SESSION["reporte_listado"])) ? $_SESSION["reporte_listado"] : false;
$from_reporte_grafico = (isset($_SESSION["reporte_grafico"])) ? $_SESSION["reporte_grafico"] : false;
$header_mov_general = (isset($_SESSION["header_movimientos_general"])) ? $_SESSION["header_movimientos_general"] : null;
session_write_close();
$nro_paquete = getallheaders()["x-request-id"];

try{
    $json_filas = file_get_contents('php://input');
    $array_filas = json_decode($json_filas, true);
    $row = "";
    if ($from_reporte_grafico){
        for ($i = 0; $i < count($array_filas); $i++) {
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
                $row .= "<td></td>";
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
    } elseif ($from_reporte_listado) {
        for ($i = 0; $i < count($array_filas); $i++) {
            $row .= "<tr>";
            for ($h = 0; $h < count($header_mov_general); $h++) {
                if (isset($array_filas[$i][$header_mov_general[$h]])) {
                    $row .= "<td>" . 
                              $array_filas[$i][$header_mov_general[$h]] . "
                            </td>";
                } else {
                    $row .=  "<td>" . $header_mov_general[$h] . "</td>";
                }
            }
            $row .= "</tr>";
        }
        $tabla_detalle_persona = "";
        if ($nro_paquete == 0) {
            $Etiqueta_Fecha_Inicio = $array_filas["fecha_desde"];
            $Etiqueta_Fecha_Fin = $array_filas["fecha_hasta"];
            $det_persona = $array_filas["det_persona"];
            $tabla_detalle_persona = "  <p id='InformacionDeCentro'>
                                          DESDE : ". $Etiqueta_Fecha_Inicio . " HASTA : " . $Etiqueta_Fecha_Fin ." </p>
                                        <p id='encabezado'> <span id='frase'> Programa Rastreador </span><br>
                                          Filtro : <br>
                                        </p>
                                        <p id='InformacionDeCiudad'>
                                          Municipialidad de Rio Tercero
                                        </p>

                                       <table id='detalle-persona' class='table--border-colapsed'>
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Apellido</th>
                                                <th>Nombre</th>
                                                <th>Dni</th>
                                                <th>Fecha Nac</th>
                                                <th>Edad</th>
                                                <th>Meses</th>
                                                <th>Localidad</th>
                                                <th>Barrio</th>
                                                <th>Domicilio</th>
                                                <th>Mnz</th>
                                                <th>Lote</th>
                                                <th>Sub-lote</th>
                                                <th>Telefono</th>
                                                <th>E-Mail</th>
                                                <th>Obra Social</th>
                                                <th>Escuela</th>
                                                <th>Nro. Legajo</th>
                                                <th>Nro. Carpeta</th>
                                                <th>Observación</th>
                                                <th>Cambio de Domicilio	</th>
                                            </tr>
                                        </thead>
                                            <tbody> 
                                            <tr>
                                                <td>" . $det_persona["id"] . "</td>
                                                <td>" . $det_persona["apellido"] . "</td>
                                                <td>" . $det_persona["nombre"] . "</td>
                                                <td>" . $det_persona["documento"] . "</td>
                                                <td>" . $det_persona["fecha_nacimiento"] . "o</td>
                                                <td>" . $det_persona["edad"] . "</td>
                                                <td>" . $det_persona["meses"] . "</td>
                                                <td>" . $det_persona["localidad"] . "</td>
                                                <td>" . $det_persona["barrio"] . "</td>
                                                <td>" . $det_persona["domicilio"] . "</td>
                                                <td>" . $det_persona["manzana"] . "</td>
                                                <td>" . $det_persona["lote"] . "</td>
                                                <td>" . $det_persona["sub_lote"] . "</td>
                                                <td>" . $det_persona["telefono"] . "</td>
                                                <td>" . $det_persona["mail"] . "</td>
                                                <td>" . $det_persona["obra_social"] . "</td>
                                                <td>" . $det_persona["escuela"] . "</td>
                                                <td>" . $det_persona["nro_legajo"] . "</td>
                                                <td>" . $det_persona["nro_carpeta"] . "</td>
                                                <td>" . $det_persona["observacion"] . "</td>
                                                <td>" . $det_persona["cmb_domicilio"] . "</td>
                                            </tr>
                                            </tbody>
                                        </table>";


    
        }
        $table = "<html>
                    <head>
                    <link href='https://fonts.cdnfonts.com/css/symbol' rel='stylesheet'>
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
                        tr th {
                        text-align: center;
                            font-size: 15px;
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
                            padding-top: 11px;
                        }

                        #frase {
                            font-weight: bold;
                        }

                        #encabezado {
                            text-align: center;
                            float: right;
                            padding-right: 25rem;
                        }

                        #detalle-persona {
                            margin-bottom: 30px;                        
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
                    <body> " . $tabla_detalle_persona . "
                        <table class='table--border-colapsed'>
                                <thead>
                                    <tr>
                                        <th>" . $header_mov_general[0]  . "</th>
                                        <th>" . $header_mov_general[1] . "</th>
                                        <th>" . $header_mov_general[2] . "</th>
                                        <th>" . $header_mov_general[3] . "</th>
                                        <th>" . $header_mov_general[4] . "</th>
                                        <th>" . $header_mov_general[5] . "</th>
                                        <th>" . $header_mov_general[6] . "</th>
                                        <th>" . $header_mov_general[7] . "</th>
                                        <th>" . $header_mov_general[8] . "</th>
                                        <th>" . $header_mov_general[9] . "</th>
                                        <th>" . $header_mov_general[10] . "</th>
                                        <th>" . $header_mov_general[11] . "</th>
                                        <th>" . $header_mov_general[12] . "</th>
                                        <th>" . $header_mov_general[13] . "</th>
                                        <th>" . $header_mov_general[14] . "</th>
                                        <th>" . $header_mov_general[15] . "</th>
                                        <th>" . $header_mov_general[16] . "</th>
                                    </tr>
                                </thead>
                                    <tbody>" . 
                                        $row . "
                                    </tbody>
                        </table>
                    </body>
                </html>";
    }
    $dompdf = new Dompdf();
    $dompdf->loadHtml(mb_convert_encoding($table, 'HTML-ENTITIES', 'UTF-8'));
    $dompdf->setPaper('legal', 'landscape');
    $dompdf->render();
    $output = $dompdf->output();
    $data = base64_encode($output);
    header('Content-Type: application/pdf');
    header("X-Request-ID: $nro_paquete");
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