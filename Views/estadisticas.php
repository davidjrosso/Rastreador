<?php 
include "Controladores/CtrAgente.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Inicio</title>
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>-->
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.src.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <!-- Combinados -->
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/series-label.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <?php  
    $Conexion = mysqli_connect("localhost","root","","ods_db") or die("Problemas en la conexion");
    $MasComprado = mysqli_query($Conexion,"select Apellido from agen limit 3");
    $RetMes = mysqli_fetch_array($MasComprado);
    mysqli_close($Conexion); 
  ?>
  <script>
    $(function () {
    $('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Productos mas Comprados'
        },
        xAxis: {
            categories: ['Agosto', 'Septiembre', 'Octubre']
        },
        yAxis: {
            title: {
                text: 'Fruit eaten'
            }
        },
        series: [
        /* Cargar las Series */
        <?php $Conexion = mysqli_connect("localhost","root","","ods_db") or die("Problemas en la conexion");
              $MasComprado = mysqli_query($Conexion,"select SUM(LitrosCombustible) as Cantidad, Vehiculo from general group by Vehiculo order by SUM(LitrosCombustible) desc limit 3");
              //$MasComprado = mysqli_query($Conexion,"select SUM(LitrosCombustible) as Cantidad, Vehiculo from general where MONTH(Fecha) = 5 and YEAR(Fecha) = YEAR(now()) group by Vehiculo order by SUM(LitrosCombustible) desc limit 3");
              while($RetMes = mysqli_fetch_array($MasComprado)){ ?>
        {
            name: <?php echo "'".$RetMes['Vehiculo']."'" ?>,
            data: [<?php echo $RetMes['Cantidad'] ?>]
        }, <?php } mysqli_close($Conexion);?>
        // Fin carga de series
        {
            name: 'John',
            data: [5, 7, 3]
        }],
    });
    $('#container2').highcharts({
    title: {
        text: 'Sumatoria de Montos totales por mes'
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    series: [{
        <?php $Conexion = mysqli_connect("localhost","root","","ods_db") or die("Problemas en la conexion");
              $MasComprado = mysqli_query($Conexion,"select SUM(Precio) as MontoTotal, Vehiculo from general group by Fecha.MES order by SUM(Precio) desc limit 2");
              //$MasComprado = mysqli_query($Conexion,"select SUM(LitrosCombustible) as Cantidad, Vehiculo from general where MONTH(Fecha) = 5 and YEAR(Fecha) = YEAR(now()) group by Vehiculo order by SUM(LitrosCombustible) desc limit 3");
              ?>
        data: [<?php while($RetMes = mysqli_fetch_array($MasComprado)){ echo $RetMes["MontoTotal"].","; } ?>9, 10, 11, 12, 17, 14, 15, 25, 17],
        step: 'left',
        name: 'Left'
    }]

    });
    $('#container3').highcharts({
    title: {
        text: 'Compras o ventas por Rubro dentro de meses'
    },
    xAxis: {
        categories: ['Apples', 'Oranges', 'Pears', 'Bananas', 'Plums']
    },
    labels: {
        items: [{
            html: 'Total fruit consumption',
            style: {
                left: '50px',
                top: '18px',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
            }
        }]
    },
    series: [{
        type: 'column',
        name: 'Jane',
        data: [3, 2, 1, 3, 4]
    }, {
        type: 'column',
        name: 'John',
        data: [2, 3, 5, 7, 6]
    }, {
        type: 'column',
        name: 'Joe',
        data: [4, 3, 3, 9, 0]
    }, {
        type: 'pie',
        name: 'Total consumption',
        data: [{
            name: 'Jane',
            y: 13,
            color: Highcharts.getOptions().colors[0] // Jane's color
        }, {
            name: 'John',
            y: 23,
            color: Highcharts.getOptions().colors[1] // John's color
        }, {
            name: 'Joe',
            y: 19,
            color: Highcharts.getOptions().colors[2] // Joe's color
        }],
        center: [100, 80],
        size: 100,
        showInLegend: false,
        dataLabels: {
            enabled: false
        }
    }]
    });
  });
  </script>
  <?php 
  // INFORMES POR PRECIO DE PRODUCTOS - SELECCIONAR PRODUCTOS Y Q ME GENERE UNA LISTA DE PROVEEDORES + ULTIMAS COMPRAS REALIZADAS- PRODUCTOS DENTRO DE UN RANGO DE PRECIO- BUSCAR PROVEEDOR Y MOSTRAR QUE PRODUCTOS VENDE Y LISTADO DE COMPRAS REALIZADO A ESE PROVEEDOR.
  // EN LA PARTE DE COMPRA Y VENTAS PONER UN LISTADO DE COMPRAS Y VENTAS EN LO QUE VA DEL MES Y COMO OPCION UN FILTRO PARA PODER BUSCAR UN PRODUCTO ESPECIFICO POR NOMBRE ADEMAS DE PODER BUSCAR COMPRAS Y VENTAS REALIZADAS EN MESES ANTERIORES DE LOS MESES ANTERIORES
   ?>
</head>
<body>
<div class = "row">
  <div class = "col-4">
    <div class="nav-side-menu">
    <div class="brand">Combustibles</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
            <ul id="menu-content" class="menu-content collapse out">
                <li>
                  <a href="#"><i class="fa fa-file-text fa-lg"></i> Facturas</a>
                </li>
                <li class="collapsed active">
                  <a href="#"><i class="fa fa-user fa-lg"></i> Agentes</a>
                </li>
                <li class="collapsed">
                  <a href="#"><i class="fa fa-car fa-lg"></i> Vehiculos</a>
                </li>  
                <li class="collapsed">
                  <a href="#"><i class="fa fa-cube fa-lg"></i> Proveedores</a>
                </li>
                <li class="collapsed">
                  <a href="#"><i class="fa fa-tachometer fa-lg"></i> Combustibles</a>
                </li>
            </ul>
     </div>
</div>
  </div>
  <div class = "col-8">
     <div class = "container">
      <div class = "col-7">
          <!-- GRAFICOS Y TABLAS ESTADISTICAS -->
          <div id="container" style="width:100%; height:400px;"></div><br>
          <div id="container2" style="height: 400px"></div>
          <div id="container3" style="height: 400px"></div>
          <!-- END -->

        </div>
     </div>
  </div>
</div>
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
?>
</body>
</html>