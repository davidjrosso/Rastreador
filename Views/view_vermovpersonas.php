<?php 
session_start(); 
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
require_once "Modelo/Persona.php";
require_once "Modelo/DtoMovimiento.php";
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
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_MOVIMIENTO);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Movimientos de Persona</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Search -->
        <div class = "row">
          <?php  
            if(isset($_REQUEST["ID_Persona"]) && $_REQUEST["ID_Persona"]!=null){
              $ID_Persona = $_REQUEST["ID_Persona"];

              $Con = new Conexion();
              $Con->OpenConexion();

              $ConsultarDatos = "select * from persona where id_persona = $ID_Persona";
              $MensajeErrorDatos = "No se pudo consultar los Datos de la Persona";

              $EjecutarConsultarDatos = mysqli_query($Con->Conexion,$ConsultarDatos) or die($MensajeErrorDatos);

              $Ret = mysqli_fetch_assoc($EjecutarConsultarDatos);

              $ID_Barrio = $Ret["ID_Barrio"];
              $ConsultarBarrio = "select * from barrios where ID_Barrio = $ID_Barrio";
              $MensajeErrorBarrio = "No se pudo consultar el Barrio de la persona";

              $EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die($MensajeErrorBarrio);

              $RetBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);


              $ID_Persona = $Ret["id_persona"];
              $Apellido = $Ret["apellido"];
              $Nombre = $Ret["nombre"];
              $DNI = $Ret["documento"];
              
              $Edad = $Ret["edad"];
              $Meses = $Ret["meses"];
              if(is_null($Ret["fecha_nac"]) || $Ret["fecha_nac"] == "null"){
                $Fecha_Nacimiento = "No se cargo fecha de nacimiento";
              }else{
                $Fecha_Nacimiento = implode("/", array_reverse(explode("-",$Ret["fecha_nac"])));    
              }
              $Nro_Carpeta = $Ret["nro_carpeta"];
              $Nro_Legajo = $Ret["nro_legajo"];
              $Obra_Social = $Ret["obra_social"];
              $Domicilio = $Ret["domicilio"];
              $Barrio = $RetBarrio["Barrio"];
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
              $ID_Escuela = $Ret["ID_Escuela"];
              $Estado = $Ret["estado"];


              $Persona = new Persona($ID_Persona,$Apellido,$Nombre,$DNI,$Nro_Legajo,$Edad,$Meses,$Fecha_Nacimiento,$Nro_Carpeta,$Obra_Social,$Domicilio,$Barrio,$Localidad,$Circunscripcion,$Seccion,$Manzana,$Lote,$Familia,$Observacion,$Cambio_Domicilio,$Telefono,$Mail,$ID_Escuela,$Estado);

              $ConsultarEscuela = "select Escuela from escuelas where ID_Escuela = $ID_Escuela";
              $MensajeErrorConsultarEscuela = "No se pudo consultar la Escuela";

              $EjecutarConsultarEscuela = mysqli_query($Con->Conexion,$ConsultarEscuela) or die($MensajeErrorConsultarEscuela);
              $RetEscuela = mysqli_fetch_assoc($EjecutarConsultarEscuela);
              $Escuela = $RetEscuela["Escuela"];

              $Table = "<table class='table'><thead><tr><th></th><th>Detalles de la Persona</th></tr></thead>";

              $Table .= "<tr><td>Id</td><td>".$Persona->getID_Persona()."</td></tr>";
              $Table .= "<tr><td>Apellido</td><td>".$Persona->getApellido()."</td></tr>";
              $Table .= "<tr><td>Nombre</td><td>".$Persona->getNombre()."</td></tr>";
              $Table .= "<tr><td>Documento</td><td>".$Persona->getDNI()."</td></tr>";
              $Table .= "<tr><td>Fecha de Nacimiento</td><td>".$Persona->getFecha_Nacimiento()."</td></tr>";
              if($Persona->getEdad() == 2020){
                $Table .= "<tr><td>Edad</td><td>No se cargo fecha de nacimiento</td></tr>";
              }else{
                $Table .= "<tr><td>Edad</td><td>".$Persona->getEdad()."</td></tr>";
              }                            
              $Table .= "<tr><td>Meses</td><td>".$Persona->getMeses()."</td></tr>";              
              $Table .= "<tr><td>Nro. Carpeta</td><td>".$Persona->getNro_Carpeta()."</td></tr>";       
              $Table .= "<tr><td>Nro. Legajo</td><td>".$Persona->getNro_Legajo()."</td></tr>";       
              $Table .= "<tr><td>Localidad</td><td>".$Persona->getLocalidad()."</td></tr>";
              $Table .= "<tr><td>Barrio</td><td>".$Persona->getBarrio()."</td></tr>";      
              $Table .= "<tr><td>Domicilio</td><td>".$Persona->getDomicilio()."</td></tr>";
              $Table .= "<tr><td>Manzana</td><td>".$Persona->getManzana()."</td></tr>";
              $Table .= "<tr><td>Lote</td><td>".$Persona->getLote()."</td></tr>";
              $Table .= "<tr><td>Familia</td><td>".$Persona->getFamilia()."</td></tr>";
              $Table .= "<tr><td>Telefono</td><td>".$Persona->getTelefono()."</td></tr>";
              $Table .= "<tr><td>E-Mail</td><td>".$Persona->getMail()."</td></tr>";                          
              $Table .= "<tr><td>Obra Social</td><td>".$Persona->getObra_Social()."</td></tr>";
              $Table .= "<tr><td>Escuela</td><td>".$Escuela."</td></tr>";                                        
              $Table .= "<tr><td>Observación</td><td>".$Persona->getObservaciones()."</td></tr>";
              $Table .= "<tr><td>Cambio de Domicilio</td><td>".$Persona->getCambio_Domicilio()."</td></tr>";

              $Table .= "</table>";

              echo $Table;

              //////////////////////////////////////////////////////TABLAS MOVIMIENTOS DE LA PERSONA /////////////////////////////

              $ConsultarMovimientos = "select M.id_movimiento, M.fecha, P.apellido, P.nombre, M.motivo_1, M.motivo_2, M.motivo_3, M.observaciones, R.responsable from movimiento M, responsable R, persona P where M.id_resp = R.id_resp and M.id_persona = P.id_persona and M.id_persona = $ID_Persona";
              $MensajeErrorMovimientos = "No se pudo consultar los movimientos de la persona";

              $TomarMovimientosPersona = mysqli_query($Con->Conexion,$ConsultarMovimientos) or die($MensajeErrorMovimientos);

              $Rows = mysqli_num_rows($TomarMovimientosPersona);

              if($Rows == 0){
                echo "<div class = 'col'></div>";
                echo "<div class = 'col-6'>";
                echo "<p class = 'TextoSinResultados'>No se encontraron Resultados</p><center><button class = 'btn btn-danger' onClick = 'location.href= \"view_movpersonas.php\"'>Atras</button></center>";
                echo "</div>";
                echo "<div class = 'col'></div>";
              }

              while ($RetMovimientos = mysqli_fetch_assoc($TomarMovimientosPersona)) {
                $ID_Movimiento = $RetMovimientos["id_movimiento"];
                $Fecha = implode("-", array_reverse(explode("-",$RetMovimientos["fecha"])));
                $Apellido = $RetMovimientos["apellido"];
                $Nombre = $RetMovimientos["nombre"];

                $ID_Motivo_1 = $RetMovimientos["motivo_1"];
                $ConsultarMotivo_1 = "select motivo from motivo where id_motivo = $ID_Motivo_1";
                $MensajeErrorMotivo_1 = "No se pudo consultar el motivo 1";
                $RetMotivo_1 = mysqli_query($Con->Conexion,$ConsultarMotivo_1) or die($MensajeErrorMotivo_1);
                $RetMotivo_1 = mysqli_fetch_assoc($RetMotivo_1);
                $Motivo_1 = $RetMotivo_1["motivo"];

                $ID_Motivo_2 = $RetMovimientos["motivo_2"];
                $ConsultarMotivo_2 = "select motivo from motivo where id_motivo = $ID_Motivo_2";
                $RetMotivo_2 = mysqli_query($Con->Conexion,$ConsultarMotivo_2) or die($MensajeErrorMotivo_2);
                $RetMotivo_2 = mysqli_fetch_assoc($RetMotivo_2);
                $Motivo_2 = $RetMotivo_2["motivo"];

                $ID_Motivo_3 = $RetMovimientos["motivo_3"];
                $ConsultarMotivo_3 = "select motivo from motivo where id_motivo = $ID_Motivo_3";
                $RetMotivo_3 = mysqli_query($Con->Conexion,$ConsultarMotivo_3) or die($MensajeErrorMotivo_3);
                $RetMotivo_3 = mysqli_fetch_assoc($RetMotivo_3);
                $Motivo_3 = $RetMotivo_3["motivo"];

                $Observaciones = $RetMovimientos["observaciones"];
                $Responsable = $RetMovimientos["responsable"];

                $DtoMovimiento = new DtoMovimiento($ID_Movimiento,$Fecha,$Apellido,$Nombre,$Motivo_1,$Motivo_2,$Motivo_3,$Observaciones,$Responsable);
                $TableMov = "<table class='table table-dark'>";
                $TableMov .= "<tr><td style = 'width: 30%;'>Fecha</td><td style = 'width: 70%;'>".$DtoMovimiento->getFecha()."</td></tr>";
                $TableMov .= "<tr><td style = 'width: 30%;'>Motivo 1</td><td style = 'width: 70%;'>".$DtoMovimiento->getMotivo_1()."</td></tr>";
                $TableMov .= "<tr><td style = 'width: 30%;'>Motivo 2</td><td style = 'width: 70%;'>".$DtoMovimiento->getMotivo_2()."</td></tr>";
                $TableMov .= "<tr><td style = 'width: 30%;'>Motivo 3</td><td style = 'width: 70%;'>".$DtoMovimiento->getMotivo_3()."</td></tr>";
                $TableMov .= "<tr><td style = 'width: 30%;'>Observaciones</td><td style = 'width: 70%;'>".$DtoMovimiento->getObservaciones()."</td></tr>";
                $TableMov .= "<tr><td style = 'width: 30%;'>Responsable</td><td style = 'width: 70%;'>".$DtoMovimiento->getResponsable()."</td></tr>";
                $TableMov .= "</table>";
                echo $TableMov;

              }

              $Con->CloseConexion();
              

            }else{
              $Mensaje = "No se pudo consultar los Datos porque no se pudo obtener el ID de la Persona";
              echo $Mensaje;
            }
          ?>
        </div>
  </div>
</div>
</div>
</body>
</html>