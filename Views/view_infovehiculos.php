<?php 
require_once "Controladores/CtrAgente.php";
require_once "Controladores/Elements.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Sistema Combustibles</title>
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

  <script>
       $(document).ready(function(){
              var date_input=$('input[name="date1"]'); //our date input has the name "date"
              var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
              date_input.datepicker({
                  format: 'dd/mm/yyyy',
                  container: container,
                  todayHighlight: true,
                  autoclose: true,
              });
              var date_input=$('input[name="date2"]'); //our date input has the name "date"
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
       
  </script>

</head>
<body>
<div class = "row">
  <div class = "col-3">
    <div class="nav-side-menu">
    <div class="brand">Secciones</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenu(0);?>
        </div>
        <div class="brand">Informes</div>
        <div class="menu-list">
  
            <?php $Element = new Elements();
            $Element->getMenuInformes(3);?>
        </div>
</div>
  </div>
  <div class = "col-9">
     <div class = "row">
      <div class = "col-7">
           <!-- Carga -->
          <p class = "Titulos">Generar Informe</p>
          <form method = "post" action = "Controladores/CtrInfoVehiculos.php">
            <div class="form-group row">
              <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Vehiculo: </label>
              <div class = "col-md-10">
                     <?php 
                     $Elements = new Elements();
                     echo $Elements->CBVehi();
                      ?>           
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Desde: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "date1" id="inputPassword" placeholder="" width="100%" autocomplete="off" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Hasta: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "date2" id="inputPassword" placeholder="" width="100%" autocomplete="off" required>
              </div>
            </div>
            <div class="form-group row">
              <div class="offset-md-2 col-md-10">
                <button type="submit" class="btn btn-outline-success">Guardar</button>
              </div>
            </div>
          </form>
          <br><br><br>
          <!-- Fin Carga -->
        </div>
     </div>
  </div>
</div>

</body>
</html>