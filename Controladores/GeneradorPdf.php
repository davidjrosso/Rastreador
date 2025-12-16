<?php
//session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$from_reporte_listado = (preg_match("~view_vermovlistados~", $_SERVER["HTTP_REFERER"])) ? true : false;
$from_reporte_grafico = (preg_match("~view_rep_general_new~", $_SERVER["HTTP_REFERER"])) ? true : false;
$nro_paquete = getallheaders()["x-request-id"];
ini_set('pcre.backtrack_limit', 5000000);
ini_set('memory_limit', '-1');
ini_set( 'max_execution_time', '120');
try {
    $json_filas = file_get_contents('php://input');
    $array_filas = json_decode($json_filas, true);
    $row = "";
    $row_head = "";
    if ($from_reporte_grafico) {
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

                            .table_pdf {
                                width: 100%;
                            }

                            .thead-dark{
                                background-color: #ccc;
                                font-size: 12px;
                            }

                            tr td {
                                text-align: center;
                                font-size: 12px;
                            }

                            table thead tr th {
                                background-color: #ccc;
                            }

                            h5, h2 {
                                text-align: center;
                                margin-bottom: 0px
                            }

                            #InformacionDeCentro {
                                width: 70px;
                                float: right; 
                                text-align: left;
                            }

                            #frase {
                                font-weight: bold;
                            }

                            #encabezado {
                                width: 70px;
                                text-align: center;
                                float: right;
                            }

                            #InformacionDeCiudad {
                                width: 70px;
                                text-align: left;
                                margin-bottom: 2rem;
                                margin-top: 2rem;
                                margin-bottom: 200px;
                            }

                            table, th, td {
                                border: 0.5px solid;
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
        $header_mov_general = (isset($array_filas["header_movimientos_general"])) ? $array_filas["header_movimientos_general"] : $array_filas["head_movimientos_persona"];
        $count = (isset($array_filas["cant"])) ? $array_filas["cant"] : 0;
        $flag = (!empty($array_filas["last"])) ? $array_filas["last"] : null;
        if ($nro_paquete == 0) {
            $page_height = 563;
        } else {
            $page_height = 650;
        }

        $height = 45;
        $cell_height = round($page_height/$count, 2);
        if ($height < $cell_height) {
            $cell_height = $height;
        }

        /*
        if ($flag && $nro_paquete != 0) {
            $hight_text = "'max-height: " . $cell_height . "px; min-height: " . $cell_height . "px;'"; 
        } else {
            $hight_text = "'height: " . $cell_height . "px;'";
        }
        */
        $hight_text = "'height: " . $cell_height . "px;'";

        $row_head .= "<tr>";
        for ($h = 0; $h < count($header_mov_general); $h++) {
            if (isset($header_mov_general[$h])) {
                $list = preg_split("~[ ]+~", $header_mov_general[$h]);
                $row_head .= "<th style='width:40px'>" . 
                                implode("<br>",  $list) . "
                              </th>";
            }
        }
        $row_head .= "</tr>";
        for ($i = 0; $i < $count; $i++) {
            $row .= "<tr>";
            for ($h = 0; $h < count($header_mov_general); $h++) {
                if (isset($array_filas[$i][$header_mov_general[$h]])) {
                    $row .= "<td style=" . $hight_text . ">";
                    if ($header_mov_general[$h] == "Localidad"
                        || $header_mov_general[$h] == "Domicilio"
                        || $header_mov_general[$h] == "Responsable") {
                        $list = preg_split("~[ ]+~", ucwords(strtolower($array_filas[$i][$header_mov_general[$h]])));
                        $row .= implode("<br>",  $list);
                        continue;
                    }

                    if ($header_mov_general[$h] == "Persona") {
                        $list = preg_split("~[ ]+~", ucwords(strtolower($array_filas[$i][$header_mov_general[$h]])));
                        $list[0] = strtoupper($list[0]);
                        $row .= implode("<br>",  $list);
                        continue;
                    }

                    if ($header_mov_general[$h] == "Centro Salud") {
                        $list = preg_split("~[()]+~", $array_filas[$i][$header_mov_general[$h]], limit: -1, flags: PREG_SPLIT_NO_EMPTY);
                        $row .= strtolower(implode("<br>",  $list));
                        continue;
                    }

                    /*
                    if ($header_mov_general[$h] == "Observaciones") {
                        $row .= substr(
                            ucfirst(strtolower($array_filas[$i][$header_mov_general[$h]])),
                            0, 
                            34
                            );
                        $row .= "<br>";

                        if (strlen($array_filas[$i][$header_mov_general[$h]]) > 34) {
                            $row .= substr(
                                    ucfirst(strtolower($array_filas[$i][$header_mov_general[$h]])),
                                    33, 
                                    34
                                    );
                            $row .= "<br>";
                            if (strlen($array_filas[$i][$header_mov_general[$h]]) > 68) {
                                $row .= substr(
                                        ucfirst(strtolower($array_filas[$i][$header_mov_general[$h]])),
                                        67,
                                        34
                                        );
                                $row .= "<br>";
                            }
                        }
                        continue;
                    }
                    */

                    if ($header_mov_general[$h] == "Fecha") {
                        $list = preg_split("~[-]+~", $array_filas[$i][$header_mov_general[$h]]);
                        $row .= $list[0] . "-" . $list[1] . "<br>" . $list[2];
                        continue;
                    }

                    if ($header_mov_general[$h] == "Fecha Nac") {
                        $is_fech = preg_match("~[-]+~", $array_filas[$i][$header_mov_general[$h]]);
                        if ($is_fech) {
                            $list = preg_split("~[-]+~", $array_filas[$i][$header_mov_general[$h]]);
                            $row .= $list[0] . "-" . $list[1] . "<br>" . $list[2];
                        } else {
                            $row .= $array_filas[$i][$header_mov_general[$h]];
                        }
                        continue;
                    }

                    $row .= substr(
                            ucfirst(strtolower($array_filas[$i][$header_mov_general[$h]])),
                            0,
                            70
                            );
                    $row .= "<br>";
                    /*
                    if ($header_mov_general[$h] != "Motivo 1"
                        && $header_mov_general[$h] != "Motivo 2"
                        && $header_mov_general[$h] != "Motivo 3"
                    ) {
                        $row .= substr(
                                $array_filas[$i][$header_mov_general[$h]],
                                0, 
                                70
                                );
                        $row .= "<br>";
                        continue;
                    }
                    $row .= substr(
                            $array_filas[$i][$header_mov_general[$h]],
                            0, 
                            21
                            );
                    $row .= "<br>";

                    if (strlen($array_filas[$i][$header_mov_general[$h]]) > 21) {
                        $row .= substr(
                                $array_filas[$i][$header_mov_general[$h]],
                                21, 
                                21
                                );
                        $row .= "<br>";
                        if (strlen($array_filas[$i][$header_mov_general[$h]]) > 43) {
                            $row .= substr(
                                    $array_filas[$i][$header_mov_general[$h]],
                                    43, 
                                    21
                                    );
                            $row .= "<br>";
                            if (strlen($array_filas[$i][$header_mov_general[$h]]) > 65) {
                                $row .= substr(
                                        $array_filas[$i][$header_mov_general[$h]],
                                        65, 
                                        21
                                        );
                            }   
                        }
                    }
                    */
                    $row .= "</td>";
                } else {
                    $row .=  "<td></td>";
                }
            }
            $row .= "</tr>";
        }
        $tabla_detalle_persona = "";
        $inicio = "";
        if ($nro_paquete == 0) {
            $etiqueta_Fecha_Inicio = $array_filas["fecha_desde"];
            $etiqueta_Fecha_Inicio = implode("-", array_reverse(explode("-", $etiqueta_Fecha_Inicio)));
            $etiqueta_Fecha_Fin = $array_filas["fecha_hasta"];
            $etiqueta_Fecha_Fin = implode("-", array_reverse(explode("-", $etiqueta_Fecha_Fin)));
            $filtros = $array_filas["fitros"];
            $filtro = "";
            foreach ($filtros as $value) {
                if (str_contains($value, "Motivos")
                    || str_contains($value, "Categorias")) {
                    $filtro .= implode("<br>", explode("-", $value));
                    $filtro .= "<br>";
                    continue;
                }
                $filtro .= $value . "<br>";
            }
            $det_persona = (isset($array_filas["det_persona"]) ? $array_filas["det_persona"] : null);
            
            $inicio = "<p id='InformacionDeCentro'>
                            DESDE : ". $etiqueta_Fecha_Inicio . " HASTA : " . $etiqueta_Fecha_Fin ."
                        </p>
                        <p id='encabezado'>
                           " . $filtro .  "
                        </p>
                        <p id='InformacionDeCiudad'>
                            Municipialidad de Rio Tercero <br>
                            Secertaría de Salud y Desarrollo Social<br>
                            Progama Rastreador <br>
                        </p> 
                        <br>
                        <br>";
            $tabla_detalle_persona = "";
            if ($det_persona) {
                $tabla_detalle_persona = "<table id='detalle-persona' class='table--border-colapsed'>
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
                                                    <td>" . $det_persona["Años"] . "</td>
                                                    <td>" . $det_persona["Meses"] . "</td>
                                                    <td>" . $det_persona["Localidad"] . "</td>
                                                    <td>" . $det_persona["Barrio"] . "</td>
                                                    <td>" . $det_persona["Domicilio"] . "</td>
                                                    <td>" . $det_persona["manzana"] . "</td>
                                                    <td>" . $det_persona["lote"] . "</td>
                                                    <td>" . $det_persona["sub_lote"] . "</td>
                                                    <td>" . $det_persona["telefono"] . "</td>
                                                    <td>" . $det_persona["mail"] . "</td>
                                                    <td>" . $det_persona["Obra Social"] . "</td>
                                                    <td>" . $det_persona["escuela"] . "</td>
                                                    <td>" . $det_persona["nro_legajo"] . "</td>
                                                    <td>" . $det_persona["nro_carpeta"] . "</td>
                                                    <td>" . $det_persona["observacion"] . "</td>
                                                    <td>" . $det_persona["cmb_domicilio"] . "</td>
                                                </tr>
                                                </tbody>
                                            </table>";
            }
        }

        $table = "<html>
                    <head>
                    <link href='https://fonts.cdnfonts.com/css/symbol' rel='stylesheet'>
                    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

                    <style>
                        @page {
                        margin-left: 11px !important;
                        margin-right: 11px !important;
                        margin-top: 11px !important;
                        padding: 11px !important;
                        margin-bottom: 3px !important;
                        }

                        .table--border-colapsed{
                            border-collapse: collapse;
                            width: 100%;
                        }

                        .thead-dark{
                            background-color: #ccc;
                            font-size: 11px;
                        }
                        .table_pdf {
                            width: 100%;
                        }

                        tr td {
                            text-align: center;
                            font-size: 10px;
                        }

                        tr th {
                        text-align: center;
                            font-size: 11px;
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
                            width:400px;
                            text-align: right;
                            font-size: 13.5px;
                        }

                        #frase {
                            font-weight: bold;
                        }

                        #encabezado {
                            float: right;
                            width: 400px;
                            text-align: center;
                            font-size: 13.5px;
                        }

                        #detalle-persona {
                            margin-bottom: 30px;                        
                        }

                        #InformacionDeCiudad {
                            float: right;
                            text-align: left;
                            font-size: 13.5px;
                        }

                        table, th, td {
                            border: 0.001px inset;
                            border-width: 0.001px;
                        }
                    </style>
                    </head> 
                    <body> " . $inicio . $tabla_detalle_persona;
                    if (count($header_mov_general) > 0) {
                        $table .= "<table class='table--border-colapsed'>
                                     <thead>
                                        " . $row_head . "
                                     </thead>
                                         <tbody>" . 
                                            $row . "
                                         </tbody>
                                     </table>";
                    }
                    $table .= "</body>
                            </html>";
    }
    /*
    $dompdf = new Dompdf();
    $dompdf->loadHtml(mb_convert_encoding($table, 'HTML-ENTITIES', 'UTF-8'));
    $dompdf->setPaper('legal', 'landscape');
    $dompdf->render();
    $output = $dompdf->output();
    */

    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-L', // 'A4-L' es para A4 apaisado (Landscape)
        'orientation' => 'L', // Alternativa directa
        'tempDir' => sys_get_temp_dir()
    ]);
    $mpdf->WriteHTML(mb_convert_encoding($table, 'HTML-ENTITIES', 'UTF-8'));
    $output = $mpdf->OutputBinaryData();

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