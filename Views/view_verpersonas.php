<?php 

?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
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

  </script>

</head>
<body>
<div class = "row">
  <?php
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_PERSONA);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Actualización de Persona</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Search -->
        <div class = "row">
          <?php  
            if (isset($_REQUEST["ID"])) {
 
              $Table = "<table class='table'><thead><tr><th></th><th>Detalles de la Persona</th></tr></thead>";

              // $Table .= "<tr><td>Id</td><td>".$Persona->getID_Persona()."</td></tr>";
              $Table .= "<tr><td>Apellido</td><td>".$Persona->getApellido()."</td></tr>";
              $Table .= "<tr><td>Nombre</td><td>".$Persona->getNombre()."</td></tr>";
              $Table .= "<tr><td>Documento</td><td>".$Persona->getDNI()."</td></tr>";
              $Table .= "<tr><td>Fecha de Nacimiento</td><td>".$Persona->getFecha_Nacimiento()."</td></tr>";
              if($Persona->getEdad() == 2020){
              	$Table .= "<tr><td>Edad</td><td>No se cargo fecha de nacimiento</td></tr>";
              }else{
              	$Table .= "<tr><td>Años</td><td>".$Persona->getEdad()."</td></tr>";
              }              
              $Table .= "<tr><td>Meses</td><td>". $Persona->getMeses() ."</td></tr>";            
              $Table .= "<tr><td>Nro. Carpeta</td><td>".(($historia_clinica) ? $historia_clinica->getNro_Carpeta():"")."</td></tr>";               
              $Table .= "<tr><td>Nro. Legajo</td><td>".(($historia_clinica)? $historia_clinica->getNro_Legajo():"")."</td></tr>";             
              $Table .= "<tr><td>Localidad</td><td>".$domicilio->getLocalidad()."</td></tr>";
              $Table .= "<tr><td>Barrio</td><td>".$domicilio->getBarrio()."</td></tr>";  
              $Table .= "<tr><td>Domicilio</td><td>". $calle . "</td></tr>";              
              $Table .= "<tr><td>Manzana</td><td>".(($domicilio)? $domicilio->getManzana():"")."</td></tr>";
              $Table .= "<tr><td>Lote</td><td>".(($domicilio)? $domicilio->getLote():"")."</td></tr>";
              $Table .= "<tr><td>Sub-lote</td><td>".$domicilio->getFamilia()."</td></tr>";
              $Table .= "<tr><td>Telefono</td><td>".$contacto->getTelefono()."</td></tr>";
              $Table .= "<tr><td>Mail</td><td>".$contacto->getMail()."</td></tr>";                            
              $Table .= "<tr><td>Obra Social</td><td>".$Persona->getObra_Social()."</td></tr>";              
              $Table .= "<tr><td>Escuela</td><td>".$Escuela."</td></tr>";
              $Table .= "<tr><td>Lugar de Trabajo</td><td>".$contacto->getTrabajo()."</td></tr>";                            
              $Table .= "<tr><td>Observación</td><td>".$Persona->getObservaciones()."</td></tr>";
              $Table .= "<tr><td>Cambio de Domicilio</td><td>".$domicilio->getCambio_Domicilio()."</td></tr>";


              $Table .= "</table>";

              echo $Table;

            } else {
              $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID de la Persona";
              echo $Mensaje;
            }
          ?>
        </div>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2">
            <button type = "button" class = "btn btn-danger" onClick = "location.href = 'personas'">Atras</button>
              
            </div>
        </div>
  </div>
</div>
</div>
</body>
</html>