<?php 
session_start(); 
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
header("Content-Type: text/html;charset=utf-8");

/*     CONTROL DE USUARIOS                    */
if(!isset($_SESSION["Usuario"])){
    header("Location: Error_Session.php");
}

$ID_Usuario = $_SESSION["Usuario"];
$usuario = new Account(account_id: $ID_Usuario);
$TipoUsuario = $usuario->get_id_tipo_usuario();
$CtrGeneral = new CtrGeneral();
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
  <script>
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

  </script>
</head>
<body>
<div class="row">
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

              $Con = new Conexion();
              $Con->OpenConexion();

              $ConsultarDatos = "select C.id_categoria, C.cod_categoria, C.categoria, F.Forma_categoria, C.color from categoria C, formas_categorias F where C.ID_Forma = F.ID_Forma and C.id_categoria = $ID_Categoria and C.estado = 1";
              $MensajeErrorDatos = "No se pudo consultar los Datos de la Categoria";

              $EjecutarConsultarDatos = mysqli_query($Con->Conexion,$ConsultarDatos) or die($MensajeErrorDatos);

              $Ret = mysqli_fetch_assoc($EjecutarConsultarDatos);

              $ID_Categoria = $Ret["id_categoria"];
              $Cod_Categoria = $Ret["cod_categoria"];
              $Categoria = $Ret["categoria"];
              $Forma_Categoria = $Ret["Forma_categoria"];
              $Color = $Ret["color"];

              $Table = "<table id='ImagenDeCategoria' class='table'><thead><tr><th></th><th>Detalles de la Categoria</th></tr></thead>";

              $Table .= "<tr><td>Denominación</td><td>" . $Categoria . "</td></tr>";
              $Table .= "<tr><td>Código</td><td>" . $Cod_Categoria . "</td></tr>";
              $Table .= "<tr><td>Forma</td><td style='color:" . $Color . "'>" . $Forma_Categoria . "</td></tr>";
              $Table .= "<tr><td>Permisos</td><td>" . $CtrGeneral->getCategorias_Roles_ID($ID_Categoria) . "</td></tr>";
              $Table .= "<tr><td>Color</td><td bgcolor='" . $Color . "'></td></tr>";
              $Table .= "</table>";

              echo $Table;

              $Con->CloseConexion();
              

            }else{
              $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID de la Categoria";
              echo $Mensaje;
            }
          ?>
        </div>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2">
              <button type = "button" class = "btn btn-danger" onClick = "location.href = 'view_categorias.php'">Atras</button>
              
            </div>
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