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
  <!--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

       function Verificar(xID){
              /*swal({
                title: "¿Está seguro?",
                text: "¿Seguro de querer eliminar esta persona? \n Se eliminaran los movimientos vinculados con la persona a eliminar",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  window.location.href = 'Controladores/DeletePersona.php?ID='+xID;
                } else {        
                }
              });*/
              swal.fire({
                title: "¿Está seguro?",
                icon: "warning",
                html: `<p style="margin-bottom:0px">¿Seguro de querer eliminar esta persona?</p>
                       <p style="margin-bottom:0px">Si se borra el registro de una persona</p>
                       <p style="margin-bottom:0px"> también se eliminan sus movimientos</p>`,
                showCloseButton: true,
                confirmButtonColor: "#e64942",
                cancelButtonColor: "#efefef",
                cancelButtonText: '<span style="color:#555">Cancel</span>',
                showCancelButton: true,
                showConfirmButton: true
              })
              .then((willDelete) => {
                if (willDelete.isConfirmed) {
                  window.location.href = 'Controladores/DeletePersona.php?ID='+xID;
                } else {        
                }
              });
              

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
        <p>Actualización de Personas</p>
      </div>
      <div class="col"></div>
    </div><br>
    <div class="row">
      <div class = "col"></div>
      <div class = "col-4">
          <center><button class = "btn btn-secondary" onClick = "location.href='view_newpersonas.php'">Agregar Nueva Persona</button></center>
      </div>
      <div class="col-2">
                <button type="button" class="btn btn-outline-secondary" onclick="location.href = 'view_inicio.php'">Volver</button>
      </div>
      <div class = "col"></div>
    </div>
    <br>
     <div class = "row">
      <div class = "col-10">
           <!-- Carga -->
          <form method = "post" action = "Controladores/CtrBuscarPersonas.php">
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Buscar: </label>
              <div class="col-md-4">
                <input type="text" class="form-control" name = "Search" id="inputPassword" width="100%" autocomplete="off">
              </div>
              <label for="inputPassword" class="col-md-1 col-form-label LblForm">En: </label>
              <div class="col-md-3">
                <select name = "ID_Filtro" class = "form-control">                    
                    <option value = "Apellido">Apellido</option>
                    <option value = "Nombre">Nombre</option>
                    <option value = "DNI">Documento</option>
                    <!-- <option value = "ID">Id</option> -->
                    <option value = "Legajo">Nro. Legajo</option>
                    <option value = "Carpeta">Nro. Carpeta</option>
                    <option value = "Domicilio">Domicilio</option>
                </select>
              </div>
              <div class = "col-md-1">
                  <button class = "btn btn-secondary">Ir</button>
              </div>
            </div>
          </form>
          <br><br>
          <!-- Fin Carga -->
          <!-- Search -->
        <div class = "row">
          <?php  
            if(isset($_REQUEST["Filtro"]) && $_REQUEST["Filtro"]!=null){
              $Filtro = $_REQUEST["Filtro"];
              $ID_Filtro = $_REQUEST["ID_Filtro"];
              $DTGeneral = new CtrGeneral();

              switch ($ID_Filtro) {
                case 'ID': echo $DTGeneral->getPersonasxID($Filtro);break;
                case 'Apellido': echo $DTGeneral->getPersonasxApellido($Filtro);break;
                case 'Nombre': echo $DTGeneral->getPersonasxNombre($Filtro);break;
                case 'DNI': echo $DTGeneral->getPersonasxDNI($Filtro);break;
                case 'Legajo': echo $DTGeneral->getPersonasxLegajo($Filtro);break;
                case 'Carpeta': echo $DTGeneral->getPersonasxCarpeta($Filtro);break;
                case 'Domicilio': echo $DTGeneral->getPersonasxDomicilio($Filtro);break;
                default: echo $DTGeneral->getPersonasxID($Filtro);break;
              }
            }else{
              $DTGeneral = new CtrGeneral();
              echo $DTGeneral->getPersonas();
            }
          ?>
        </div>
  </div>
</div>
</div>
<?php  
if(isset($Mensaje)){
  echo "<script type='text/javascript'>
    swal('$Mensaje','','success');
</script>";
}
?>
</body>
</html>