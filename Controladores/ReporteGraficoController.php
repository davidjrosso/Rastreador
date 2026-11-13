<?php
/*
 *
 * This file is part of Rastreador3.
 *
 * Rastreador3 is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Rastreador3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Rastreador3; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 */

require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Motivo.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Categoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/CategoriaRol.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/sys_config.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/dompdf/autoload.inc.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use Dompdf\Dompdf;

class ReporteGraficoController 
{

    public function filtro_movimientos()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {

            $ID_Usuario = $_SESSION["Usuario"];
            $account = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $account->get_id_tipo_usuario();

            $Element = new Elements();

            include("./Views/view_general_new.php");
        }
        exit();
    }

    public function reporte()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {

            $ID_Usuario = $_SESSION["Usuario"];
            $_SESSION["reporte_grafico"] = true;
            session_write_close();

            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $width_dispay = (isset($_REQUEST["width-display"])) ? $_REQUEST["width-display"] : null;

            if (isset($_REQUEST["Fecha_Desde"])) {
                $lista_animacion = explode("/", $_REQUEST["Fecha_Desde"]);
                $Fecha_Inicio = implode("-", array_reverse($lista_animacion));
                $fecha_init_animacion = $Fecha_Inicio;
                $anio_animacion = $lista_animacion[2];
                $mes_animacion = $lista_animacion[1];
                $dia_animacion = $lista_animacion[0];
            } else {
                $Fecha_Inicio = null;
            }
            if (isset($_REQUEST["Fecha_Hasta"])) {
                $Fecha_Fin = implode("-", array_reverse(explode("/", $_REQUEST["Fecha_Hasta"])));
                $fecha_end_animacion = $Fecha_Fin;
            } else {
                $Fecha_Fin = null;
            }

            $Element = new Elements();

            include("./Views/view_rep_general_new.php");
        }
        exit();
    }

    public function generar_pdf()
    {
        ini_set('pcre.backtrack_limit', 5000000);
        ini_set('memory_limit', '-1');
        ini_set( 'max_execution_time', '120');
        $nro_paquete = getallheaders()["x-request-id"];

        try {
            $json_filas = file_get_contents('php://input');
            $array_filas = json_decode($json_filas, true);
            $row = "";
            $row_head = "";

            $fecha_desde = $array_filas["fechaDesde"];
            $fecha_hasta = $array_filas["fechaHasta"];
            $header_mov_general = (isset($array_filas["header_movimientos_general"])) ? $array_filas["header_movimientos_general"] : $array_filas["head_movimientos_persona"];
            $count = (isset($array_filas["cant"])) ? $array_filas["cant"] : 0;
            $flag = (!empty($array_filas["last"])) ? $array_filas["last"] : null;
            if ($nro_paquete == 0) {
                $page_height = 563;
            } else {
                $page_height = 650;
            }

            $meses_header = array_filter($header_mov_general, function ($e) {
                return preg_match("~[0-9]+/[0-9]+~", $e); 
            });

            $max_diag_por_meses = array_combine($meses_header, 
                                                array_fill(0, count($meses_header), 0)
                                                );
            foreach ($array_filas as $ind => $value) {
                foreach ($meses_header as $key => $value) {
                    $fecha = preg_replace("~([0-9]+)/([0-9][0-9])~", "$1/20$2", $value);
                    if (!isset($array_filas[$ind][$fecha])) continue;
                    $cant_diag = count($array_filas[$ind][$fecha]);
                    if ($max_diag_por_meses[$value] < $cant_diag) $max_diag_por_meses[$value] = $cant_diag;
                }
            }
            $height = 45;
            $cell_height = round($page_height/$count, 2);
            if ($height < $cell_height) {
                $cell_height = $height;
            }

            /*
            for ($i = 0; $i < $count; $i++) {
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

                foreach ($meses_header as $key => $value) {
                    $fecha = preg_replace("~([0-9]+)/([0-9][0-9])~", "$1/20$2", $value);
                    if (isset($array_filas[$i][$fecha])) {
                        $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                                    $this->bloqueMotivos($array_filas[$i][$fecha]) . "
                                 </td>";
                    } else {
                        $row .= "<td> </td>";
                    }
                }
                $row .= "</tr>";
            }

            */

            $row_1 = "";
            $row_2 = "";
            for ($i = 0; $i < $count; $i++) {
                $row_1 .= "<tr>
                            <td rowspan='2' style='width: 60px'>" . 
                                (isset(($array_filas[$i]["barrio"])) ? $array_filas[$i]["barrio"] : "" ) . "
                            </td>
                            <td rowspan='2' style='width: 60px'>" . 
                                (isset(($array_filas[$i]["domicilio"])) ? $array_filas[$i]["domicilio"] : "" ) . "
                            </td>
                            <td rowspan='2' style='width: 60px'>" . 
                                (isset(($array_filas[$i]["persona"])) ? $array_filas[$i]["persona"] : "" ) . "
                            </td>
                            <td rowspan='2' style='width: 60px'>" . 
                                (isset(($array_filas[$i]["fechanac"])) ? $array_filas[$i]["fechanac"] : "" ) . "
                            </td>";

                $row_2 .= "<tr>";

                foreach ($meses_header as $key => $value) {
                    $fecha = preg_replace("~([0-9]+)/([0-9][0-9])~", "$1/20$2", $value);

                    $col_can = ($max_diag_por_meses[$value]) ? $max_diag_por_meses[$value] : 1;
                    $cant_diag_fila = ($col_can > 6) ? 6 : $col_can;

                    if (isset($array_filas[$i][$fecha])) {

                        $array_diag = $array_filas[$i][$fecha];
                        $cant_diag_mes_persona = count($array_diag);

                        if ($cant_diag_mes_persona > $cant_diag_fila) {

                            $sub_array_diag = array_slice($array_diag, 0, $cant_diag_fila);
                            array_walk($sub_array_diag, function ($valor, $indice, $last_inidice) use (&$row_1) {
                                $border = "border-right: 0px; border-left: 0px;";
                                if ($indice == $last_inidice) $border = "border-left: 0px;";
                                if ($indice == 0 && $indice != $last_inidice) $border = "border-right: 0px;";
                                $row_1 .= "<td style='border-top: 0px; border-bottom: 0px; $border'>
                                                <div style='color: " . $valor[2] . "'>" . 
                                                    $valor[0] . "
                                                </div>" . 
                                                $valor[1]  . "
                                            </td>";
                            }, $cant_diag_fila - 1);

                            $sub_diag = array_slice($array_diag, $cant_diag_fila);

                            array_walk($sub_diag, function ($valor, $indice, $last_inidice) use (&$row_2) {
                                $border = "border-right: 0px; border-left: 0px;";
                                if ($indice == $last_inidice) $border = "border-left: 0px; border-color: ";
                                if ($indice == 0 && $indice != $last_inidice) $border = "border-right: 0px; border-left: 0px";
                                $row_2 .= "<td style='border-top: 0px; $border'>
                                                <div style='color: " . $valor[2] . "'>" . 
                                                    $valor[0] . "
                                                </div>" . 
                                                $valor[1]  . "
                                            </td>";
                            }, $cant_diag_fila - 1);

                            for ($j = count($sub_diag); $j < $cant_diag_fila; $j++) {
                                $border = "border-right: 0px; border-left: 0px;";
                                if ($j == $cant_diag_fila - 1) $border = "border-left: 0px;";
                                if ($j == 0 && $j != ($cant_diag_fila - 1)) $border = "border-right: 0px;";
                                $row_2 .= "<td style='$border border-top: 0px;'></td>";    
                            }
                        } else {
                            array_walk($array_diag, function ($valor, $indice, $last_inidice) use (&$row_1) {
                                $border = "border-right: 0px; border-left: 0px;";
                                if ($indice == $last_inidice) $border = "border-left: 0px;";
                                if ($indice == 0  && $indice != $last_inidice) $border = "border-right: 0px;";
                                $row_1 .= "<td style='border-top: 0px; border-bottom: 0px; $border'>
                                                <div style='color: " . $valor[2] . "'>" . 
                                                    $valor[0] . "
                                                </div>" . 
                                                $valor[1]  . "
                                            </td>";
                            }, $cant_diag_fila - 1);

                            for ($j = count($array_diag); $j < $cant_diag_fila; $j++) {
                                $border = "border-right: 0px; border-left: 0px;";
                                if ($j == $cant_diag_fila - 1) $border = "border-right: 0px;";
                                if ($j == 0 && $j != ($cant_diag_fila - 1)) $border = "border-left: 0px;";
                                $row_1 .= "<td style='$border border-top: 0px;'></td>";
                            }

                            for ($j = 0; $j < $cant_diag_fila; $j++) {
                                $border = "border-right: 0px; border-left: 0px;";
                                if ($j == $cant_diag_fila - 1) $border = "border-left: 0px;";
                                if ($j == 0 && $j != ($cant_diag_fila - 1)) $border = "border-right: 0px;";
                                $row_2 .= "<td style='$border border-top: 0px;'></td>";    
                            }
                        }

                    } else {
                        $row_1 .= "<td style='border-bottom: 0px;'> </td>";
                        $row_2 .= "<td style='border-top: 0px;'> </td>";
                    }
                }
                $row_1 .= "</tr>";
                $row_2 .= "</tr>";

                $row .= $row_1 . $row_2;
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

                                table, th, td {
                                    border: 1px solid;
                                }

                                tr td {
                                    border-width: 1px;
                                    border-color: #8b8787;
                                    text-align: center;
                                    font-size: 12px;
                                }

                                table thead tr th {
                                    border-width: 1px;
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
                            </style>
                        </head> 
                        <body>";
            $table .= "<table class='table--border-colapsed'>
                        <thead>
                            <tr>
                                <th>Barrio</th>
                                <th>Direc.</th>
                                <th>Persona</th>
                                <th>Fecha Nac.</th>";
            $head_table = "";
            foreach ($meses_header as $key => $value) {
                $colspan = ($max_diag_por_meses[$value]) ? $max_diag_por_meses[$value] : 1;
                $colspan = ($colspan > 6) ? 6 : $colspan;
                $head_table .= "<th colspan='" . $colspan . "'>" . $value  . "</th>";
            }
            $table .= $head_table;
            $table .= "</tr>
                        </thead>
                        <tbody>". 
                            $row."
                        </tbody>
                      </table>
                    </body>
                </html>";

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'orientation' => 'L',
                'tempDir' => sys_get_temp_dir()
            ]);

            $mt = mb_convert_encoding($table, 'HTML-ENTITIES', 'UTF-8');
            $mpdf->WriteHTML($mt);
            $output = $mpdf->OutputBinaryData();
            $data = base64_encode($output);
            header('Content-Type: application/pdf');
            echo $data;
        } catch (Exception $e) {
            header("HTTP/1.1 503 Service Unavailable");
            header('Content-Type: text/plain');
            echo 'Error producido al generar el pdf: ',  $e->getMessage(), "\n";
        }

    }

    function concatMotivo($elemento)
    {
        $td_table = "<td style='border-top: 0px; border-right: 0px; border-bottom: 0px'> 
                        <div style='color: " . $elemento[2] . "'>" . 
                            $elemento[0] . "
                        </div>" . 
                        $elemento[1]  . "
                    </td>";
        return $td_table;
    }

    function bloqueMotivos($array_motivos, $max_diag_por_meses)
    {   $bloque = "";
        if (count($array_motivos) > 5) {
            $bloque .= array_reduce(array_slice($array_motivos, 0, 5), [$this, "concatMotivo"], "");
            $bloque .= array_reduce(array_slice($array_motivos, 6), [$this, "concatMotivo"], "");
        } else {
            $bloque .= array_reduce($array_motivos, [$this, "concatMotivo"], $bloque);
        }
        return $bloque;
    }

    /*
    function concatMotivo($acumulado, $elemento)
    {
        $acumulado .= "<td> 
                        <span style='color: " . $elemento[2] . "'>" . 
                            $elemento[0] . "
                        </span>" . 
                        $elemento[1]  . "
                    </td>";
        return $acumulado;
    }

    function bloqueMotivos($array_motivos)
    {   $bloque = "<table>
                     <thead>
                     <th></th>
                     </thead>
                     <tbody>";
        if (count($array_motivos) > 5) {
        $bloque .= "<row>";
        $bloque .= array_reduce(array_slice($array_motivos, 0, 5), [$this, "concatMotivo"], $bloque);
        $bloque .= "</row>";
        $bloque .= "<row>";
        $bloque .= array_reduce(array_slice($array_motivos, 6), [$this, "concatMotivo"], $bloque);
        $bloque .= "</row>";
        } else {
            $bloque .= "<row>";
            $bloque .= array_reduce($array_motivos, [$this, "concatMotivo"], $bloque);
            $bloque .= "</row>";
        }
        $bloque .= "</tbody>
                 </table>";
        return $bloque;
    }

    */
    /*
    function concatMotivo($acumulado, $elemento)
    {
        $acumulado .= "<span style='float: right;'> 
                        <div style='color: " . $elemento[2] . "'>" . 
                            $elemento[0] . "
                        </div>" . 
                        $elemento[1]  . "
                    </span>";
        return $acumulado;
    }

    function bloqueMotivos($array_motivos)
    {   $bloque = "";
        if (count($array_motivos) > 5) {
        $bloque .= array_reduce(array_slice($array_motivos, 0, 5), [$this, "concatMotivo"], $bloque);
        $bloque .= array_reduce(array_slice($array_motivos, 6), [$this, "concatMotivo"], $bloque);
        } else {
            $bloque .= array_reduce($array_motivos, [$this, "concatMotivo"], $bloque);
        }
        return $bloque;
    }
        */

}