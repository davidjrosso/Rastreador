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

                foreach ($array_filas[$i] as $key => $value) {
                    if (preg_match("~[0-9][0-9]/[0-9][0-9][0-9][0-9]~", $key)) {
                        $row .= "<td style=\"font-family: 'DejaVu Sans'; font-size: 7px;\">" . 
                                    bloqueMotivos($array_filas[$i][$key]) . "
                                 </td>";
                    } else {
                        $row .= "<td></td>";
                    }
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

}