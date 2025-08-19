<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Elements.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/CtrGeneral.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Persona.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Barrio.php';
header("Content-Type: text/html;charset=utf-8");


$Con = new Conexion();
$Con->OpenConexion();
$ID_Usuario = $_SESSION["Usuario"];
$usuario = new Account(account_id: $ID_Usuario);
$TipoUsuario = $usuario->get_id_tipo_usuario();

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
  $Element = new Elements();
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
            if(isset($_REQUEST["ID"]) && $_REQUEST["ID"]!=null){
              $xID_Persona = $_REQUEST["ID"];

              $Con = new Conexion();
              $Con->OpenConexion();

              //////////////////////////////// CALCULAR EDAD EN CASO DE QUE NO SEA LA CORRECTA /////////////////////////////////////////////////////
              $ConsultarDatosEdad = "select edad, fecha_nac from persona where id_persona = $xID_Persona and estado = 1 limit 1";
              $MensajeErrorDatosEdad = "No se pudo consultar los Datos de la Persona";

              $EjecutarConsultarDatosEdad = mysqli_query($Con->Conexion,$ConsultarDatosEdad) or die($MensajeErrorDatosEdad);              

              $RetEdad = mysqli_fetch_assoc($EjecutarConsultarDatosEdad);            

    
              if(!is_null($RetEdad["fecha_nac"]) && $RetEdad["fecha_nac"] !== "null" && !empty($RetEdad["fecha_nac"])){
              	  $Fecha_Nac = $RetEdad["fecha_nac"];
                  $Fecha_Nacimiento_Registrada = new DateTime($Fecha_Nac);
                  $Hoy = new DateTime();
                  $Edad = $Hoy->diff($Fecha_Nacimiento_Registrada);           
                  $Edad_Actual = $RetEdad["edad"];                 
                  if($Edad->y !== $Edad_Actual){                  
                    $Nueva_Edad = $Edad->y;
                    $ActualizarEdad = "update persona set edad = $Nueva_Edad where id_persona = $xID_Persona and estado = 1";
                    $MensajeErrorActualizarEdad = "No se pudo actualizar la edad";
                    mysqli_query($Con->Conexion,$ActualizarEdad) or die($MensajeErrorActualizarEdad);          
                  }
              	  $Nueva_Edad_Meses = $Edad->m;                  	
              	  $ActualizarEdad_Meses = "update persona set meses = $Nueva_Edad_Meses where id_persona = $xID_Persona and estado = 1";
              	  $MensajeErrorActualizarEdadMeses = "No se pudo actualizar los meses de edad";
              	  mysqli_query($Con->Conexion,$ActualizarEdad_Meses) or die($MensajeErrorActualizarEdadMeses);      
                  
              }             
              ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

              $ConsultarDatos = "select id_persona, UPPER(apellido) AS apellido, nombre, documento, 
                                        edad, fecha_nac, nro_carpeta, nro_legajo,
                                        obra_social, domicilio, localidad, circunscripcion,
                                        seccion, manzana, lote, familia, mail, observacion,
                                        cambio_domicilio, estado, ID_Escuela, meses, Trabajo,
                                        ID_Barrio, telefono
                                 from persona where id_persona = $xID_Persona and estado = 1 limit 1";
              $MensajeErrorDatos = "No se pudo consultar los Datos de la Persona";

              $EjecutarConsultarDatos = mysqli_query($Con->Conexion,$ConsultarDatos) or die($MensajeErrorDatos);              

              $Ret = mysqli_fetch_assoc($EjecutarConsultarDatos);
              $ID_Barrio = $Ret["ID_Barrio"];

              $barrio = new Barrio(
                                   coneccion: $Con,
                                   id_barrio: $ID_Barrio
                                  );
              $RetBarrio = $barrio->get_barrio();
              
              $ID_Persona = $Ret["id_persona"];
              $Apellido = $Ret["apellido"];
              $Nombre = $Ret["nombre"];
              $DNI = $Ret["documento"];
              
              $Edad = $Ret["edad"];
              if(is_null($Ret["fecha_nac"]) || $Ret["fecha_nac"] == "null"){
              	$Fecha_Nacimiento = "No se cargo fecha de nacimiento";
              }else{
              	$Fecha_Nacimiento = implode("/", array_reverse(explode("-",$Ret["fecha_nac"])));  	
              }
                      
              $Nro_Carpeta = $Ret["nro_carpeta"];
              $Nro_Legajo = $Ret["nro_legajo"];
              $Obra_Social = $Ret["obra_social"];
              $Domicilio = $Ret["domicilio"];
              $Barrio = $RetBarrio;
              $Localidad = $Ret["localidad"];
              $Circunscripcion = $Ret["circunscripcion"];
              $Seccion = $Ret["seccion"];
              $Manzana = $Ret["manzana"];
              $Lote = $Ret["lote"];
              $Familia = $Ret["familia"];
              $Observacion = $Ret["observacion"];
              $Cambio_Domicilio = $Ret["cambio_domicilio"];
              $Telefono = $Ret["telefono"];
              $Mail = $Ret["mail"];
              $Estado = $Ret["estado"];
              $ID_Escuela = $Ret["ID_Escuela"];
              $Meses = $Ret["meses"];
              $Trabajo = $Ret["Trabajo"];

              $Persona = new Persona($ID_Persona);

              $ConsultarEscuela = "select Escuela from escuelas where ID_Escuela = $ID_Escuela";
              $MensajeErrorConsultarEscuela = "No se pudo consultar la Escuela";

              $EjecutarConsultarEscuela = mysqli_query($Con->Conexion,$ConsultarEscuela) or die($MensajeErrorConsultarEscuela);
              $RetEscuela = mysqli_fetch_assoc($EjecutarConsultarEscuela);
              $Escuela = $RetEscuela["Escuela"];

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
              $Table .= "<tr><td>Meses</td><td>".(($Meses!="null")? $Persona->getMeses():"")."</td></tr>";            
              $Table .= "<tr><td>Nro. Carpeta</td><td>".(($Nro_Carpeta!="null")?$Persona->getNro_Carpeta():"")."</td></tr>";               
              $Table .= "<tr><td>Nro. Legajo</td><td>".(($Nro_Legajo!="null")? $Persona->getNro_Legajo():"")."</td></tr>";             
              $Table .= "<tr><td>Localidad</td><td>".$Persona->getLocalidad()."</td></tr>";
              $Table .= "<tr><td>Barrio</td><td>".$Persona->getBarrio()."</td></tr>";  
              $Table .= "<tr><td>Domicilio</td><td>".$Persona->getDomicilio()."</td></tr>";              
              $Table .= "<tr><td>Manzana</td><td>".(($Manzana!="null")? $Persona->getManzana():"")."</td></tr>";
              $Table .= "<tr><td>Lote</td><td>".(($Lote!="null")? $Persona->getLote():"")."</td></tr>";
              $Table .= "<tr><td>Sub-lote</td><td>".$Persona->getFamilia()."</td></tr>";
              $Table .= "<tr><td>Telefono</td><td>".$Persona->getTelefono()."</td></tr>";
              $Table .= "<tr><td>Mail</td><td>".$Persona->getMail()."</td></tr>";                            
              $Table .= "<tr><td>Obra Social</td><td>".$Persona->getObra_Social()."</td></tr>";              
              $Table .= "<tr><td>Escuela</td><td>".$Escuela."</td></tr>";
              $Table .= "<tr><td>Lugar de Trabajo</td><td>".$Persona->getTrabajo()."</td></tr>";                            
              $Table .= "<tr><td>Observación</td><td>".$Persona->getObservaciones()."</td></tr>";
              $Table .= "<tr><td>Cambio de Domicilio</td><td>".$Persona->getCambio_Domicilio()."</td></tr>";


              $Table .= "</table>";

              echo $Table;

              $Con->CloseConexion();
              

            }else{
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