<?php 
session_start(); 
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
header("Content-Type: text/html;charset=utf-8");

/*     CONTROL DE USUARIOS                    */
if(!isset($_SESSION["Usuario"])){
    header("Location: Error_Session.php");
}

$Con = new Conexion();
$Con->OpenConexion();
$ID_Usuario = $_SESSION["Usuario"];
$ConsultarTipoUsuario = "select ID_TipoUsuario from accounts where accountid = $ID_Usuario";
$MensajeErrorConsultarTipoUsuario = "No se pudo consultar el Tipo de Usuario";
$EjecutarConsultarTipoUsuario = mysqli_query($Con->Conexion,$ConsultarTipoUsuario) or die($MensajeErrorConsultarTipoUsuario);
$Ret = mysqli_fetch_assoc($EjecutarConsultarTipoUsuario);
$TipoUsuario = $Ret["ID_TipoUsuario"];
$Con->CloseConexion();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <!--<script type="text/javascript" src = "js/Funciones.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
       $(document).ready(function(){
              var date_input=$('input[name="date"]'); //our date input has the name "date"
              var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
              date_input.datepicker({
                  format: 'dd/mm/yyyy',
                  container: container,
                  todayHighlight: true,
                  autoclose: true,
              });
          });

       function CalcularPrecio(){
        //var Combus = document.getElementById("Combustible").value;
        var Litros = document.getElementById("Litros").value;
        var Combustible = document.getElementById("Combustible");
        var PrecioxL = Combustible.options[Combustible.selectedIndex].getAttribute("name");
        
        var Total = parseFloat(PrecioxL) * parseFloat(Litros);

        var Precio = document.getElementById("Precio");
        Precio.setAttribute("value",parseFloat(Total).toFixed(2));
        //Terminar esta parte cuando termine lo demas.
       }

       function MostrarColor(xDiv){
         var Color = xDiv.style.backgroundColor;

         var Partes = Color.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

         delete (Partes[0]);
         for (var i = 1; i <= 3; ++i) {
              Partes[i] = parseInt(Partes[i]).toString(16);
              if (Partes[i].length == 1) Partes[i] = '0' + Partes[i];
          } 

          var ColorHexa = '#'+Partes.join('').toUpperCase();
         var Codigo = document.getElementById("CodigoColor");
         Codigo.value = ColorHexa;
         var Muestra = document.getElementById("MuestraColor");
         Muestra.style.backgroundColor = ColorHexa;
       }

       function PintarColoresUsados(xColor, xCat, xColorLetra){
          var Contenedor = document.getElementById(xColor); 
          Contenedor.style.color = xColorLetra;
          Contenedor.style.fontWeight = 900;         
          Contenedor.innerHTML = xCat;
       }

  </script>

</head>
<body>
<div class = "row">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_CATEGORIA);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Actualización de Categoria</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Search -->
        <div class = "row">
          <?php  
            if(isset($_REQUEST["ID"]) && $_REQUEST["ID"]!=null){
              $ID_Categoria = $_REQUEST["ID"];
              $ID_Forma = $_REQUEST["ID_Forma"];

              $Con = new Conexion();
              $Con->OpenConexion();

              //$ConsultarDatos = "select * from categoria where id_categoria = $ID_Categoria";
              $ConsultarID = "select * 
                              from solicitudes_crearcategorias 
                              where id = '$ID_Categoria' 
                              limit 1";
              $MensajeErrorDatos = "No se pudo consultar los Datos de la Categoria";

              $EjecutarConsultarDatos = mysqli_query($Con->Conexion,$ConsultarID) or die($MensajeErrorDatos);

              $Ret = mysqli_fetch_assoc($EjecutarConsultarDatos);    

              $Cod_Categoria = $Ret["Codigo"];
              $Categoria = $Ret["Categoria"];

              $Table = "<table class='table'><thead><tr><th></th><th>Detalles de la Categoria</th></tr></thead>";

              $Table .= "<tr><td>Código</td><td>".$Cod_Categoria."</td></tr>";
              $Table .= "<tr><td>Categoria</td><td>".$Categoria."</td></tr>";
              $Table .= "<tr><td>Color</td><td><div class = 'row'>
                  <div class = 'TablaColores' style='background-color:#006600;' onClick = 'MostrarColor(this)' id = '#006600'></div><div class = 'TablaColores' style='background-color:#006633;' onClick = 'MostrarColor(this)' id = '#006633'></div><div class = 'TablaColores' style='background-color:#006666;' onClick = 'MostrarColor(this)' id = '#006666'></div><div class = 'TablaColores' style='background-color:#006699;' onClick = 'MostrarColor(this)' id = '#006699'></div><div class = 'TablaColores' style='background-color:#0066CC;' onClick = 'MostrarColor(this)' id = '#0066CC'></div><div class = 'TablaColores' style='background-color:#0066FF;' onClick = 'MostrarColor(this)' id = '#0066FF'></div><div class = 'TablaColores' style='background-color:#003300;' onClick = 'MostrarColor(this)' id = '#003300'></div><div class = 'TablaColores' style='background-color:#003333;' onClick = 'MostrarColor(this)' id = '#003333'></div><div class = 'TablaColores' style='background-color:#003366;' onClick = 'MostrarColor(this)' id = '#003366'></div><div class = 'TablaColores' style='background-color:#003399;' onClick = 'MostrarColor(this)' id = '#003399'></div><div class = 'TablaColores' style='background-color:#0033CC;' onClick = 'MostrarColor(this)' id = '#0033CC'></div><div class = 'TablaColores' style='background-color:#0033FF;' onClick = 'MostrarColor(this)' id = '#0033FF'></div><div class = 'TablaColores' style='background-color:#000000;' onClick = 'MostrarColor(this)' id = '#000000'></div><div class = 'TablaColores' style='background-color:#000033;' onClick = 'MostrarColor(this)' id = '#000033'></div><div class = 'TablaColores' style='background-color:#000066;' onClick = 'MostrarColor(this)' id = '#000066'></div><div class = 'TablaColores' style='background-color:#000099;' onClick = 'MostrarColor(this)' id = '#000099'></div><div class = 'TablaColores' style='background-color:#0000CC;' onClick = 'MostrarColor(this)' id = '#0000CC'></div><div class = 'TablaColores' style='background-color:#0000FF;' onClick = 'MostrarColor(this)' id = '#0000FF'></div>
              </div>
              <div class = 'row'>
                 <div class = 'TablaColores' style='background-color:#336600;' onClick = 'MostrarColor(this)' id = '#336600'></div><div class = 'TablaColores' style='background-color:#336633;' onClick = 'MostrarColor(this)' id = '#336633'></div><div class = 'TablaColores' style='background-color:#336666;' onClick = 'MostrarColor(this)' id = '#336666'></div><div class = 'TablaColores' style='background-color:#336699;' onClick = 'MostrarColor(this)' id = '#336699'></div><div class = 'TablaColores' style='background-color:#3366CC;' onClick = 'MostrarColor(this)' id = '#3366CC'></div><div class = 'TablaColores' style='background-color:#3366FF;' onClick = 'MostrarColor(this)' id = '#3366FF'></div><div class = 'TablaColores' style='background-color:#333300;' onClick = 'MostrarColor(this)' id = '#333300'></div><div class = 'TablaColores' style='background-color:#333333;' onClick = 'MostrarColor(this)' id = '#333333'></div><div class = 'TablaColores' style='background-color:#333366;' onClick = 'MostrarColor(this)' id = '#333366'></div><div class = 'TablaColores' style='background-color:#333399;' onClick = 'MostrarColor(this)' id = '#333399'></div><div class = 'TablaColores' style='background-color:#3333CC;' onClick = 'MostrarColor(this)' id = '#3333CC'></div><div class = 'TablaColores' style='background-color:#3333FF;' onClick = 'MostrarColor(this)' id = '#3333FF'></div><div class = 'TablaColores' style='background-color:#330000;' onClick = 'MostrarColor(this)' id = '#330000'></div><div class = 'TablaColores' style='background-color:#330033;' onClick = 'MostrarColor(this)' id = '#330033'></div><div class = 'TablaColores' style='background-color:#330066;' onClick = 'MostrarColor(this)' id = '#330066'></div><div class = 'TablaColores' style='background-color:#330099;' onClick = 'MostrarColor(this)' id = '#330099'></div><div class = 'TablaColores' style='background-color:#3300CC;' onClick = 'MostrarColor(this)' id = '#3300CC'></div><div class = 'TablaColores' style='background-color:#3300FF;' onClick = 'MostrarColor(this)' id = '#3300FF'></div>
              </div>
              <div class = 'row'>
                 <div class = 'TablaColores' style='background-color:#666600;' onClick = 'MostrarColor(this)' id = '#666600'></div><div class = 'TablaColores' style='background-color:#666633;' onClick = 'MostrarColor(this)' id = '#666633'></div><div class = 'TablaColores' style='background-color:#666666;' onClick = 'MostrarColor(this)' id = '#666666'></div><div class = 'TablaColores' style='background-color:#666699;' onClick = 'MostrarColor(this)' id = '#666699'></div><div class = 'TablaColores' style='background-color:#6666CC;' onClick = 'MostrarColor(this)' id = '#6666CC'></div><div class = 'TablaColores' style='background-color:#6666FF;' onClick = 'MostrarColor(this)' id = '#6666FF'></div><div class = 'TablaColores' style='background-color:#663300;' onClick = 'MostrarColor(this)' id = '#663300'></div><div class = 'TablaColores' style='background-color:#663333;' onClick = 'MostrarColor(this)' id = '#663333'></div><div class = 'TablaColores' style='background-color:#663366;' onClick = 'MostrarColor(this)' id = '#663366'></div><div class = 'TablaColores' style='background-color:#663399;' onClick = 'MostrarColor(this)' id = '#663399'></div><div class = 'TablaColores' style='background-color:#6633CC;' onClick = 'MostrarColor(this)' id = '#6633CC'></div><div class = 'TablaColores' style='background-color:#6633FF;' onClick = 'MostrarColor(this)' id = '#6633FF'></div><div class = 'TablaColores' style='background-color:#660000;' onClick = 'MostrarColor(this)' id = '#660000'></div><div class = 'TablaColores' style='background-color:#660033;' onClick = 'MostrarColor(this)' id = '#660033'></div><div class = 'TablaColores' style='background-color:#660066;' onClick = 'MostrarColor(this)' id = '#660066'></div><div class = 'TablaColores' style='background-color:#660099;' onClick = 'MostrarColor(this)' id = '#660099'></div><div class = 'TablaColores' style='background-color:#6600CC;' onClick = 'MostrarColor(this)' id = '#6600CC'></div><div class = 'TablaColores' style='background-color:#6600FF;' onClick = 'MostrarColor(this)' id = '#6600FF'></div>
              </div>
              <div class = 'row'>
                 <div class = 'TablaColores' style='background-color:#996600;' onClick = 'MostrarColor(this)' id = '#996600'></div><div class = 'TablaColores' style='background-color:#996633;' onClick = 'MostrarColor(this)' id = '#996633'></div><div class = 'TablaColores' style='background-color:#996666;' onClick = 'MostrarColor(this)' id = '#996666'></div><div class = 'TablaColores' style='background-color:#996699;' onClick = 'MostrarColor(this)' id = '#996699'></div><div class = 'TablaColores' style='background-color:#9966CC;' onClick = 'MostrarColor(this)' id = '#9966CC'></div><div class = 'TablaColores' style='background-color:#9966FF;' onClick = 'MostrarColor(this)' id = '#9966FF'></div><div class = 'TablaColores' style='background-color:#993300;' onClick = 'MostrarColor(this)' id = '#993300'></div><div class = 'TablaColores' style='background-color:#993333;' onClick = 'MostrarColor(this)' id = '#993333'></div><div class = 'TablaColores' style='background-color:#993366;' onClick = 'MostrarColor(this)' id = '#993366'></div><div class = 'TablaColores' style='background-color:#993399;' onClick = 'MostrarColor(this)' id = '#993399'></div><div class = 'TablaColores' style='background-color:#9933CC;' onClick = 'MostrarColor(this)' id = '#9933CC'></div><div class = 'TablaColores' style='background-color:#9933FF;' onClick = 'MostrarColor(this)' id = '#9933FF'></div><div class = 'TablaColores' style='background-color:#990000;' onClick = 'MostrarColor(this)' id = '#990000'></div><div class = 'TablaColores' style='background-color:#990033;' onClick = 'MostrarColor(this)' id = '#990033'></div><div class = 'TablaColores' style='background-color:#990066;' onClick = 'MostrarColor(this)' id = '#990066'></div><div class = 'TablaColores' style='background-color:#990099;' onClick = 'MostrarColor(this)' id = '#990099'></div><div class = 'TablaColores' style='background-color:#9900CC;' onClick = 'MostrarColor(this)' id = '#9900CC'></div><div class = 'TablaColores' style='background-color:#9900FF;' onClick = 'MostrarColor(this)' id = '#9900FF'></div>
              </div>
              <div class = 'row'>
                 <div class = 'TablaColores' style='background-color:#CC6600;' onClick = 'MostrarColor(this)' id = '#CC6600'></div><div class = 'TablaColores' style='background-color:#CC6633;' onClick = 'MostrarColor(this)' id = '#CC6633'></div><div class = 'TablaColores' style='background-color:#CC6666;' onClick = 'MostrarColor(this)' id = '#CC6666'></div><div class = 'TablaColores' style='background-color:#CC6699;' onClick = 'MostrarColor(this)' id = '#CC6699'></div><div class = 'TablaColores' style='background-color:#CC66CC;' onClick = 'MostrarColor(this)' id = '#CC66CC'></div><div class = 'TablaColores' style='background-color:#CC66FF;' onClick = 'MostrarColor(this)' id = '#CC66FF'></div><div class = 'TablaColores' style='background-color:#CC3300;' onClick = 'MostrarColor(this)' id = '#CC3300'></div><div class = 'TablaColores' style='background-color:#CC3333;' onClick = 'MostrarColor(this)' id = '#CC3333'></div><div class = 'TablaColores' style='background-color:#CC3366;' onClick = 'MostrarColor(this)' id = '#CC3366'></div><div class = 'TablaColores' style='background-color:#CC3399;' onClick = 'MostrarColor(this)' id = '#CC3399'></div><div class = 'TablaColores' style='background-color:#CC33CC;' onClick = 'MostrarColor(this)' id = '#CC33CC'></div><div class = 'TablaColores' style='background-color:#CC33FF;' onClick = 'MostrarColor(this)' id = '#CC33FF'></div><div class = 'TablaColores' style='background-color:#CC0000;' onClick = 'MostrarColor(this)' id = '#CC0000'></div><div class = 'TablaColores' style='background-color:#CC0033;' onClick = 'MostrarColor(this)' id = '#CC0033'></div><div class = 'TablaColores' style='background-color:#CC0066;' onClick = 'MostrarColor(this)' id = '#CC0066'></div><div class = 'TablaColores' style='background-color:#CC0099;' onClick = 'MostrarColor(this)' id = '#CC0099'></div><div class = 'TablaColores' style='background-color:#CC00CC;' onClick = 'MostrarColor(this)' id = '#CC00CC'></div><div class = 'TablaColores' style='background-color:#CC00FF;' onClick = 'MostrarColor(this)' id = '#CC00FF'></div>
              </div>
              <div class = 'row'>
                 <div class = 'TablaColores' style='background-color:#FF6600;' onClick = 'MostrarColor(this)' id = '#FF6600'></div><div class = 'TablaColores' style='background-color:#FF6633;' onClick = 'MostrarColor(this)' id = '#FF6633'></div><div class = 'TablaColores' style='background-color:#FF6666;' onClick = 'MostrarColor(this)' id = '#FF6666'></div><div class = 'TablaColores' style='background-color:#FF6699;' onClick = 'MostrarColor(this)' id = '#FF6699'></div><div class = 'TablaColores' style='background-color:#FF66CC;' onClick = 'MostrarColor(this)' id = '#FF66CC'></div><div class = 'TablaColores' style='background-color:#FF66FF;' onClick = 'MostrarColor(this)' id = '#FF66FF'></div><div class = 'TablaColores' style='background-color:#FF3300;' onClick = 'MostrarColor(this)' id = '#FF3300'></div><div class = 'TablaColores' style='background-color:#FF3333;' onClick = 'MostrarColor(this)' id = '#FF3333'></div><div class = 'TablaColores' style='background-color:#FF3366;' onClick = 'MostrarColor(this)' id = '#FF3366'></div><div class = 'TablaColores' style='background-color:#FF3399;' onClick = 'MostrarColor(this)' id = '#FF3399'></div><div class = 'TablaColores' style='background-color:#FF33CC;' onClick = 'MostrarColor(this)' id = '#FF33CC'></div><div class = 'TablaColores' style='background-color:#FF33FF;' onClick = 'MostrarColor(this)' id = '#FF33FF'></div><div class = 'TablaColores' style='background-color:#FF0000;' onClick = 'MostrarColor(this)' id = '#FF0000'></div><div class = 'TablaColores' style='background-color:#FF0033;' onClick = 'MostrarColor(this)' id = '#FF0033'></div><div class = 'TablaColores' style='background-color:#FF0066;' onClick = 'MostrarColor(this)' id = '#FF0066'></div><div class = 'TablaColores' style='background-color:#FF0099;' onClick = 'MostrarColor(this)' id = '#FF0099'></div><div class = 'TablaColores' style='background-color:#FF00CC;' onClick = 'MostrarColor(this)' id = '#FF00CC'></div><div class = 'TablaColores' style='background-color:#FF00FF;' onClick = 'MostrarColor(this)' id = '#FF00FF'></div>
              </div>
              <div class = 'row'>
                 <div class = 'TablaColores' style='background-color:#00FF00;' onClick = 'MostrarColor(this)' id = '#00FF00'></div><div class = 'TablaColores' style='background-color:#00FF33;' onClick = 'MostrarColor(this)' id = '#00FF33'></div><div class = 'TablaColores' style='background-color:#00FF66;' onClick = 'MostrarColor(this)' id = '#00FF66'></div><div class = 'TablaColores' style='background-color:#00FF99;' onClick = 'MostrarColor(this)' id = '#00FF99'></div><div class = 'TablaColores' style='background-color:#00FFCC;' onClick = 'MostrarColor(this)' id = '#00FFCC'></div><div class = 'TablaColores' style='background-color:#00FFFF;' onClick = 'MostrarColor(this)' id = '#00FFFF'></div><div class = 'TablaColores' style='background-color:#00CC00;' onClick = 'MostrarColor(this)' id = '#00CC00'></div><div class = 'TablaColores' style='background-color:#00CC33;' onClick = 'MostrarColor(this)' id = '#00CC33'></div><div class = 'TablaColores' style='background-color:#00CC66;' onClick = 'MostrarColor(this)' id = '#00CC66'></div><div class = 'TablaColores' style='background-color:#00CC99;' onClick = 'MostrarColor(this)' id = '#00CC99'></div><div class = 'TablaColores' style='background-color:#00CCCC;' onClick = 'MostrarColor(this)' id = '#00CCCC'></div><div class = 'TablaColores' style='background-color:#00CCFF;' onClick = 'MostrarColor(this)' id = '#00CCFF'></div><div class = 'TablaColores' style='background-color:#009900;' onClick = 'MostrarColor(this)' id = '#009900'></div><div class = 'TablaColores' style='background-color:#009933;' onClick = 'MostrarColor(this)' id = '#009933'></div><div class = 'TablaColores' style='background-color:#009966;' onClick = 'MostrarColor(this)' id = '#009966'></div><div class = 'TablaColores' style='background-color:#009999;' onClick = 'MostrarColor(this)' id = '#009999'></div><div class = 'TablaColores' style='background-color:#0099CC;' onClick = 'MostrarColor(this)' id = '#0099CC'></div><div class = 'TablaColores' style='background-color:#0099FF;' onClick = 'MostrarColor(this)' id = '#0099FF'></div>
              </div>
              <div class = 'row'>
                 <div class = 'TablaColores' style='background-color:#33FF00;' onClick = 'MostrarColor(this)' id = '#33FF00'></div><div class = 'TablaColores' style='background-color:#33FF33;' onClick = 'MostrarColor(this)' id = '#33FF33'></div><div class = 'TablaColores' style='background-color:#33FF66;' onClick = 'MostrarColor(this)' id = '#33FF66'></div><div class = 'TablaColores' style='background-color:#33FF99;' onClick = 'MostrarColor(this)' id = '#33FF99'></div><div class = 'TablaColores' style='background-color:#33FFCC;' onClick = 'MostrarColor(this)' id = '#33FFCC'></div><div class = 'TablaColores' style='background-color:#33FFFF;' onClick = 'MostrarColor(this)' id = '#33FFFF'></div><div class = 'TablaColores' style='background-color:#33CC00;' onClick = 'MostrarColor(this)' id = '#33CC00'></div><div class = 'TablaColores' style='background-color:#33CC33;' onClick = 'MostrarColor(this)' id = '#33CC33'></div><div class = 'TablaColores' style='background-color:#33CC66;' onClick = 'MostrarColor(this)' id = '#33CC66'></div><div class = 'TablaColores' style='background-color:#33CC99;' onClick = 'MostrarColor(this)' id = '#33CC99'></div><div class = 'TablaColores' style='background-color:#33CCCC;' onClick = 'MostrarColor(this)' id = '#33CCCC'></div><div class = 'TablaColores' style='background-color:#33CCFF;' onClick = 'MostrarColor(this)' id = '#33CCFF'></div><div class = 'TablaColores' style='background-color:#339900;' onClick = 'MostrarColor(this)' id = '#339900'></div><div class = 'TablaColores' style='background-color:#339933;' onClick = 'MostrarColor(this)' id = '#339933'></div><div class = 'TablaColores' style='background-color:#339966;' onClick = 'MostrarColor(this)' id = '#339966'></div><div class = 'TablaColores' style='background-color:#339999;' onClick = 'MostrarColor(this)' id = '#339999'></div><div class = 'TablaColores' style='background-color:#3399CC;' onClick = 'MostrarColor(this)' id = '#3399CC'></div><div class = 'TablaColores' style='background-color:#3399FF;' onClick = 'MostrarColor(this)' id = '#3399FF'></div>
              </div>
              <div class = 'row'>
                 <div class = 'TablaColores' style='background-color:#66FF00;' onClick = 'MostrarColor(this)' id = '#66FF00'></div><div class = 'TablaColores' style='background-color:#66FF33;' onClick = 'MostrarColor(this)' id = '#66FF33'></div><div class = 'TablaColores' style='background-color:#66FF66;' onClick = 'MostrarColor(this)' id = '#66FF66'></div><div class = 'TablaColores' style='background-color:#66FF99;' onClick = 'MostrarColor(this)' id = '#66FF99'></div><div class = 'TablaColores' style='background-color:#66FFCC;' onClick = 'MostrarColor(this)' id = '#66FFCC'></div><div class = 'TablaColores' style='background-color:#66FFFF;' onClick = 'MostrarColor(this)' id = '#66FFFF'></div><div class = 'TablaColores' style='background-color:#66CC00;' onClick = 'MostrarColor(this)' id = '#66CC00'></div><div class = 'TablaColores' style='background-color:#66CC33;' onClick = 'MostrarColor(this)' id = '#66CC33'></div><div class = 'TablaColores' style='background-color:#66CC66;' onClick = 'MostrarColor(this)' id = '#66CC66'></div><div class = 'TablaColores' style='background-color:#66CC99;' onClick = 'MostrarColor(this)' id = '#66CC99'></div><div class = 'TablaColores' style='background-color:#66CCCC;' onClick = 'MostrarColor(this)' id = '#66CCCC'></div><div class = 'TablaColores' style='background-color:#66CCFF;' onClick = 'MostrarColor(this)' id = '#66CCFF'></div><div class = 'TablaColores' style='background-color:#669900;' onClick = 'MostrarColor(this)' id = '#669900'></div><div class = 'TablaColores' style='background-color:#669933;' onClick = 'MostrarColor(this)' id = '#669933'></div><div class = 'TablaColores' style='background-color:#669966;' onClick = 'MostrarColor(this)' id = '#669966'></div><div class = 'TablaColores' style='background-color:#669999;' onClick = 'MostrarColor(this)' id = '#669999'></div><div class = 'TablaColores' style='background-color:#6699CC;' onClick = 'MostrarColor(this)' id = '#6699CC'></div><div class = 'TablaColores' style='background-color:#6699FF;' onClick = 'MostrarColor(this)' id = '#6699FF'></div>
              </div>
              <div class = 'row'>
                 <div class = 'TablaColores' style='background-color:#99FF00;' onClick = 'MostrarColor(this)' id = '#99FF00'></div><div class = 'TablaColores' style='background-color:#99FF33;' onClick = 'MostrarColor(this)' id = '#99FF33'></div><div class = 'TablaColores' style='background-color:#99FF66;' onClick = 'MostrarColor(this)' id = '#99FF66'></div><div class = 'TablaColores' style='background-color:#99FF99;' onClick = 'MostrarColor(this)' id = '#99FF99'></div><div class = 'TablaColores' style='background-color:#99FFCC;' onClick = 'MostrarColor(this)' id = '#99FFCC'></div><div class = 'TablaColores' style='background-color:#99FFFF;' onClick = 'MostrarColor(this)' id = '#99FFFF'></div><div class = 'TablaColores' style='background-color:#99CC00;' onClick = 'MostrarColor(this)' id = '#99CC00'></div><div class = 'TablaColores' style='background-color:#99CC33;' onClick = 'MostrarColor(this)' id = '#99CC33'></div><div class = 'TablaColores' style='background-color:#99CC66;' onClick = 'MostrarColor(this)' id = '#99CC66'></div><div class = 'TablaColores' style='background-color:#99CC99;' onClick = 'MostrarColor(this)' id = '#99CC99'></div><div class = 'TablaColores' style='background-color:#99CCCC;' onClick = 'MostrarColor(this)' id = '#99CCCC'></div><div class = 'TablaColores' style='background-color:#99CCFF;' onClick = 'MostrarColor(this)' id = '#99CCFF'></div><div class = 'TablaColores' style='background-color:#999900;' onClick = 'MostrarColor(this)' id = '#999900'></div><div class = 'TablaColores' style='background-color:#999933;' onClick = 'MostrarColor(this)' id = '#999933'></div><div class = 'TablaColores' style='background-color:#999966;' onClick = 'MostrarColor(this)' id = '#999966'></div><div class = 'TablaColores' style='background-color:#999999;' onClick = 'MostrarColor(this)' id = '#999999'></div><div class = 'TablaColores' style='background-color:#9999CC;' onClick = 'MostrarColor(this)' id = '#9999CC'></div><div class = 'TablaColores' style='background-color:#9999FF;' onClick = 'MostrarColor(this)' id = '#9999FF'></div>
              </div>
              <div class = 'row'>
                 <div class = 'TablaColores' style='background-color:#CCFF00;' onClick = 'MostrarColor(this)' id = '#CCFF00'></div><div class = 'TablaColores' style='background-color:#CCFF33;' onClick = 'MostrarColor(this)' id = '#CCFF33'></div><div class = 'TablaColores' style='background-color:#CCFF66;' onClick = 'MostrarColor(this)' id = '#CCFF66'></div><div class = 'TablaColores' style='background-color:#CCFF99;' onClick = 'MostrarColor(this)' id = '#CCFF99'></div><div class = 'TablaColores' style='background-color:#CCFFCC;' onClick = 'MostrarColor(this)' id = '#CCFFCC'></div><div class = 'TablaColores' style='background-color:#CCFFFF;' onClick = 'MostrarColor(this)' id = '#CCFFFF'></div><div class = 'TablaColores' style='background-color:#CCCC00;' onClick = 'MostrarColor(this)' id = '#CCCC00'></div><div class = 'TablaColores' style='background-color:#CCCC33;' onClick = 'MostrarColor(this)' id = '#CCCC33'></div><div class = 'TablaColores' style='background-color:#CCCC66;' onClick = 'MostrarColor(this)' id = '#CCCC66'></div><div class = 'TablaColores' style='background-color:#CCCC99;' onClick = 'MostrarColor(this)' id = '#CCCC99'></div><div class = 'TablaColores' style='background-color:#CCCCCC;' onClick = 'MostrarColor(this)' id = '#CCCCCC'></div><div class = 'TablaColores' style='background-color:#CCCCFF;' onClick = 'MostrarColor(this)' id = '#CCCCFF'></div><div class = 'TablaColores' style='background-color:#CC9900;' onClick = 'MostrarColor(this)' id = '#CC9900'></div><div class = 'TablaColores' style='background-color:#CC9933;' onClick = 'MostrarColor(this)' id = '#CC9933'></div><div class = 'TablaColores' style='background-color:#CC9966;' onClick = 'MostrarColor(this)' id = '#CC9966'></div><div class = 'TablaColores' style='background-color:#CC9999;' onClick = 'MostrarColor(this)' id = '#CC9999'></div><div class = 'TablaColores' style='background-color:#CC99CC;' onClick = 'MostrarColor(this)' id = '#CC99CC'></div><div class = 'TablaColores' style='background-color:#CC99FF;' onClick = 'MostrarColor(this)' id = '#CC99FF'></div>
              </div>
              <div class = 'row'>
                 <div class = 'TablaColores' style='background-color:#FFFF00;' onClick = 'MostrarColor(this)' id = '#FFFF00'></div><div class = 'TablaColores' style='background-color:#FFFF33;' onClick = 'MostrarColor(this)' id = '#FFFF33'></div><div class = 'TablaColores' style='background-color:#FFFF66;' onClick = 'MostrarColor(this)' id = '#FFFF66'></div><div class = 'TablaColores' style='background-color:#FFFF99;' onClick = 'MostrarColor(this)' id = '#FFFF99'></div><div class = 'TablaColores' style='background-color:#FFFFCC;' onClick = 'MostrarColor(this)' id = '#FFFFCC'></div><div class = 'TablaColores' style='background-color:#FFFFFF;' onClick = 'MostrarColor(this)' id = '#FFFFFF'></div><div class = 'TablaColores' style='background-color:#FFCC00;' onClick = 'MostrarColor(this)' id = '#FFCC00'></div><div class = 'TablaColores' style='background-color:#FFCC33;' onClick = 'MostrarColor(this)' id = '#FFCC33'></div><div class = 'TablaColores' style='background-color:#FFCC66;' onClick = 'MostrarColor(this)' id = '#FFCC66'></div><div class = 'TablaColores' style='background-color:#FFCC99;' onClick = 'MostrarColor(this)' id = '#FFCC99'></div><div class = 'TablaColores' style='background-color:#FFCCCC;' onClick = 'MostrarColor(this)' id = '#FFCCCC'></div><div class = 'TablaColores' style='background-color:#FFCCFF;' onClick = 'MostrarColor(this)' id = '#FFCCFF'></div><div class = 'TablaColores' style='background-color:#FF9900;' onClick = 'MostrarColor(this)' id = '#FF9900'></div><div class = 'TablaColores' style='background-color:#FF9933;' onClick = 'MostrarColor(this)' id = '#FF9933'></div><div class = 'TablaColores' style='background-color:#FF9966;' onClick = 'MostrarColor(this)' id = '#FF9966'></div><div class = 'TablaColores' style='background-color:#FF9999;' onClick = 'MostrarColor(this)' id = '#FF9999'></div><div class = 'TablaColores' style='background-color:#FF99CC;' onClick = 'MostrarColor(this)' id = '#FF99CC'></div><div class = 'TablaColores' style='background-color:#FF99FF;' onClick = 'MostrarColor(this)' id = '#FF99FF'></div>
              </div>
              <br>
              <div class = 'row'>
                  <div class = 'TablaColores' style='background-color:#FFFFFF;' onClick = 'MostrarColor(this)' id = '#FFFFFF'></div><div class = 'TablaColores' style='background-color:#DDDDDD;' onClick = 'MostrarColor(this)' id = '#DDDDDD'></div><div class = 'TablaColores' style='background-color:#C0C0C0;' onClick = 'MostrarColor(this)' id = '#C0C0C0'></div><div class = 'TablaColores' style='background-color:#969696;' onClick = 'MostrarColor(this)' id = '#969696'></div><div class = 'TablaColores' style='background-color:#808080;' onClick = 'MostrarColor(this)' id = '#808080'></div><div class = 'TablaColores' style='background-color:#646464;' onClick = 'MostrarColor(this)' id = '#646464'></div><div class = 'TablaColores' style='background-color:#4B4B4B;' onClick = 'MostrarColor(this)' id = '#4B4B4B'></div><div class = 'TablaColores' style='background-color:#242424;' onClick = 'MostrarColor(this)' id = '#242424'></div><div class = 'TablaColores' style='background-color:#000000;' onClick = 'MostrarColor(this)' id = '#000000'></div>
              </div>
              <br>
              <form method = 'post' action = 'Controladores/CrearCategoria.php'>
              <div class = 'row'>
                  <div class = 'col-md-6'>
                        <label>Codigo del Color</label>
                        <input name = 'CodigoColor' id = 'CodigoColor' required>
                        <input type = 'hidden' name = 'ID' value = '".$ID_Categoria."'>
                        <input type = 'hidden' name = 'ID_Forma' value = '".$ID_Forma."'>
                  </div>
                  <div class = 'col-md-6'>
                        <label>Muestra</label>
                        <div class = 'MuestraColor' id = 'MuestraColor'></div>
                  </div>
              </div>
              <div class = 'row'>
                <div class='offset-md-2 col-md-10'>
                  <button type='submit' class='btn btn-outline-success'>Guardar</button>
                </div>
              </div>
              </form>
              </td></tr>";
              $Table .= "</table>";

              echo $Table;

              //PINTANDO LOS COLORES QUE YA ESTAN SELECCIONADO
              $ConsultarColoresUsados = "select color, cod_categoria from categoria where estado = 1";
              $MensajeErrorColoresUsados = "No se pudieron consultar los colores utilizados";
              $EjecutarColoresUsados = mysqli_query($Con->Conexion,$ConsultarColoresUsados) or die($MensajeErrorColoresUsados);
              while($RetColoresUsados = mysqli_fetch_assoc($EjecutarColoresUsados)){  
              	/*       ESTO ES PARA PONER UN COLOR CLARO O UNO OSCURO DEPENDIENDO DEL RANGO QUE TIENE EL VALOR HEXADECIMAL PERO NO ME SALIO :P      	
              	$ColorUsado = ltrim($RetColoresUsados["color"],"#");
              	$ColorHex3 = mysql_escape_string($ColorUsado);
              	$ValorComparar_1 = implode(unpack("H*", $ColorHex3));
              	$ValorComparar_2 = implode(unpack("H*", '777777'));
              	if($ValorComparar_1 > $ValorComparar_2){
              		$ColorLetra = 'FFFFFF';
              		//echo "<script>alert(".$ColorLetra.")</script>";
              	}else{
              		$ColorLetra = '000000';
              		//echo "<script>alert(".$ColorLetra.")</script>";
              	}  
              	*/ 

              	$ColorLetra = 'FFFFFF';
              
                                     
                echo "<script>PintarColoresUsados('".$RetColoresUsados["color"]."','".$RetColoresUsados["cod_categoria"]."','#$ColorLetra')</script>";
              }
              /////////////////////////////////////////////////
              $Con->CloseConexion();                          
            }else{
              $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID de la Categoria";
              echo $Mensaje;
            }
          ?>
        </div>
  </div>
</div>
</div>
<?php  
if(isset($MensajeError)){
  echo "<script type='text/javascript'>
  swal('".$MensajeError."','','warning');
</script>";
}
?>
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