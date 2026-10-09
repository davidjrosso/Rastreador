<?php 
session_start(); 
require_once($_SERVER['DOCUMENT_ROOT'] . "/Controladores/Elements.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");

header("Content-Type: text/html;charset=utf-8");

/*     CONTROL DE USUARIOS                    */
if(!isset($_SESSION["Usuario"])){
    header("Location: Error_Session.php");
    exit();
}

$ID_Usuario = $_SESSION["Usuario"];
$usuario = new Account(account_id: $ID_Usuario);
$TipoUsuario = $usuario->get_id_tipo_usuario();
$Element = new Elements();
$DTGeneral = new CtrGeneral();

$num_row = 5000;

$limit = 250;
$offset = 0;

?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="./dist/personas.js"></script>
  <script src="js/Utils.js"></script>

  <script>
       function Verificar(xID){
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
                  window.location.href = 'Controladores/DeletePersona.php?ID=' + xID;
                }
              });
              

        }

  </script>

</head>
<body>
<div class="col-md-2" id="expandir" style="padding-left: 6px; position: fixed; z-index: 1000" hidden>
  <a id="abrir" class="btn btn-secondary btn-sm" href="javascript:void(0)" onclick="mostrar()">
    <i class="fa fa-arrows-alt fa-lg" color="tomato"></i>
  </a>
</div>
<div class = "row margin-right-cero">
  <?php
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_PERSONA);
  ?>
  <div class = "col-md-9 inicio-md-2">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Actualización de Personas</p>
      </div>
      <div class="col"></div>
    </div><br>
    <div class="row">
      <div class = "col"></div>
      <div class = "col-9">
          <center><button class = "btn btn-secondary" onClick = "location.href='view_newpersonas.php'">Agregar Nueva Persona</button></center>
      </div>
      <div class="col-1">
                <button type="button" class="btn btn-outline-secondary" onclick="location.href = 'view_inicio.php'">Inicio</button>
      </div>
      <div class = "col"></div>
    </div>
    <br>
     <div class = "row" style="justify-content: center;">
      <div class = "col-10">
           <!-- Carga -->
          <form method = "post" action = "Controladores/CtrBuscarPersonas.php">
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Buscar: </label>
              <div class="col-md-5">
                <input type="text" class="form-control" name = "Search" id="inputPassword" width="100%" autocomplete="off">
              </div>
              <label for="inputPassword" class="col-md-1 col-form-label LblForm">En: </label>
              <div class="col-md-3">
                <select name = "ID_Filtro" class = "form-control">                    
                    <option value = "<?=CtrGeneral::APELLIDO_NOMBRE?>ApellidoYNombre">Apellido y Nombre</option>
                    <option value = "<?=CtrGeneral::APELLIDO?>">Apellido</option>
                    <option value = "<?=CtrGeneral::NOMBRE?>">Nombre</option>
                    <option value = "<?=CtrGeneral::DOCUMENTO?>" selected>Documento</option>
                    <!-- <option value = "ID">Id</option> -->
                    <option value = "<?=CtrGeneral::LEGAJO?>">Nro. Legajo</option>
                    <option value = "<?=CtrGeneral::CARPETA?>">Nro. Carpeta</option>
                    <option value = "<?=CtrGeneral::DOMICILIO?>">Domicilio</option>
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

              switch ($ID_Filtro) {
                case CtrGeneral::ID_PERSONA: 
                    echo $DTGeneral->getPersonasxID($Filtro, $limit);
                    break;
                case CtrGeneral::APELLIDO:
                    echo $DTGeneral->getPersonasxApellido($Filtro, $limit);
                    break;
                case CtrGeneral::APELLIDO_NOMBRE:
                    echo $DTGeneral->getPersonasxApellidoYNombre($Filtro, $limit);
                    break;
                case CtrGeneral::NOMBRE:
                    echo $DTGeneral->getPersonasxNombre($Filtro, $lmiit);
                    break;
                case CtrGeneral::DOCUMENTO:
                    echo $DTGeneral->getPersonasxDNI($Filtro, $limit);
                    break;
                case CtrGeneral::LEGAJO:
                    echo $DTGeneral->getPersonasxLegajo($Filtro, $limit);
                    break;
                case CtrGeneral::CARPETA:
                    echo $DTGeneral->getPersonasxCarpeta($Filtro, $limit);
                    break;
                case CtrGeneral::DOMICILIO:
                    echo $DTGeneral->getPersonasxDomicilio($Filtro, $limit);
                    break;
                default:
                    echo $DTGeneral->getPersonasxID($Filtro, $limit);
                    break;
              }
            }else{
              echo $DTGeneral->getPersonas($limit);
            }
          ?>
        </div>
          <div class="row" style="justify-content: center;">
            <div class="col-6">
              <button type="button" class="btn btn-outline-secondary"
                      onclick="location.href = 'view_inicio.php'">Inicio</button>
              <button type="button" class="btn btn-secondary" id="bn-carga-personas" style="position: relative;"
                      data-offset="<?=$offset + $limit;?>" data-limit="<?= $limit;?>">
                Cargar +250 personas <div id="circle"> </div>
              </button>
            </div>
          </div>
      <br>
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