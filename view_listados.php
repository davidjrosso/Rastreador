<?php 
session_start(); 
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
header("Content-Type: text/html;charset=utf-8");

/*     CONTROL DE USUARIOS                    */
if (!isset($_SESSION["Usuario"])) {
    header("Location: Error_Session.php");
    exit();
}

$ID_Usuario = $_SESSION["Usuario"];
$account = new Account(account_id: $ID_Usuario);
$TipoUsuario = $account->get_id_tipo_usuario();

$Con = new Conexion();
$Con->OpenConexion();

$datosNav = (isset($_SESSION["datosNav"])) ? $_SESSION["datosNav"]: [];

$Element = new Elements();

$id_motivo = !empty($_REQUEST["id_motivo"]) ? $_REQUEST["id_motivo"] : null;
$_SESSION["redirect_motivo"] = isset($id_motivo);
if (!$id_motivo) $_SESSION["retorno"] = [];
$motivo = new Motivo(coneccion_base: $Con, id_motivo: $id_motivo);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <script src="https://code.jquery.com/jquery-1.9.1.min.js" integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ=" crossorigin="anonymous"></script>
  <script src="node_modules/@popperjs/core/dist/umd/popper.js"></script>
  <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
  <script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>

  <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/ValidarGeneral.js"></script>
  <script src="js/Utils.js"></script>
  <script src="./dist/preferencia.js"></script>
  <script src="./dist/alerta.js"></script>
  <script src="./dist/formulariosReporte.js"></script>
  <script src="./dist/control.js"></script>
  <?php if (!empty($id_motivo)) {?>
  <script>
    $(document).ready(function() {
          if(<?=$id_motivo?>) seleccionMotivoInicial('<?= $motivo->get_motivo();?>', <?= $id_motivo;?>);
    });
  </script>
  <?php } ?>
</head>
<body>

<div class='col-md-2' id='expandir' style='padding-left: 6px; position: fixed; z-index: 1000' hidden>
  <a id='abrir' class='btn btn-secondary btn-sm' href='javascript:void(0)' onclick='mostrar()'>
    <i class='fa fa-arrows-alt fa-lg' color='tomato'></i>
  </a>
</div>

<div class = "row margin-right-cero">
<?php
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_REPORTE_LISTADO);
  ?>
  <div class = "col-md-9 inicio-md-2">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Reporte por listado</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
    <div class="row" style="margin-bottom: 0.6rem;">
      <div style="flex: 0 0 4.333333%; max-width: 4.333333%;">
      </div>
      <div class="col-3">
        <button id="btn-enlace-driver" class="btn btn-md btn-secondary" data-toggle="modal" data-target="#modal-enlace-drive">Enlace</button>
        <button id="btn-datos" class="btn btn-md btn-secondary" data-toggle="modal" data-target="#modal-datos">Preferencias</button>
      </div>
      <div class="col-md-5">
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-outline-secondary" onclick="location.href = 'view_inicio.php'">Inicio</button>
        <button class="btn btn-md btn-secondary" data-toggle="modal" data-target="#ModalConfig">Config</button>
      </div>
    </div>
    <div style="justify-content: center" class = "row">
        <div class = "col-10">
            <!-- Carga -->
            <p class = "Titulos">Parámetros</p>
            <form method = "post" onKeydown="return event.key != 'Enter';" action = "view_vermovlistados.php" onSubmit = "return ValidarGeneral();">
              <div class="form-group row">
                  <label for="Fecha_Desde" class="col-md-2 col-form-label LblForm">Fecha desde *: </label>
                  <div class="col-md-10">
                    <div class="input-group mb-3">
                      <div class="input-group-append" id="fecha-desde-inicial">
                        <span class="input-group-text">&#8734;</span>
                      </div>
                      <input type="text" name="Fecha_Desde" id = "Fecha_Desde" class="form-control" autocomplete="off" value = "<?= (isset($datosNav["Fecha_Desde"])) ? $datosNav["Fecha_Desde"] : implode("/", array_reverse(explode("-",date('Y-m-d',strtotime(date('Y-m-d')."- 1 year"))))) ?>">
                    </div>
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="Fecha_Hasta" class="col-md-2 col-form-label LblForm">Fecha hasta *: </label>
                  <div class="col-md-10">
                    <div class="input-group mb-3">
                      <div class="input-group-append" id="fecha-hasta-inicial">
                        <span class="input-group-text">&#8734;</span>
                      </div>
                      <input type="text" name="Fecha_Hasta" id = "Fecha_Hasta" class="form-control" autocomplete="off" value = "<?= (isset($datosNav["Fecha_Hasta"])) ? $datosNav["Fecha_Hasta"] : implode("/", array_reverse(explode("-",date('Y-m-d')))) ?>">
                    </div>
                  </div>
              </div>
              <div class="form-group row" style="margin-bottom: 0.6rem;">
                <label for="btn-persona" class="col-md-2 col-form-label LblForm">Persona: </label>
                <div class="col-md-10" id = "Persona">
                    <button type = "button" id="btn-persona" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalPersona">Seleccione una Persona</button>                  
                </div>
              </div>
              <div class="row LblForm col-md-2" style="margin-bottom: 1.04%; font-size: 1.031rem">
                Edad <br>
              </div>
              <div class="form-group row">
                    <label for="Edad_Desde" class="col-md-2 col-form-label LblForm">Desde (Años): </label>
                    <div class="col-md-10">
                        <input type="number" name="Edad_Desde" data-pre = "1" id="Edad_Desde" class="form-control" autocomplete="off" placeholder="Sólo Números" min="0" value="<?= (isset($datosNav["Edad_Desde"])) ? $datosNav["Edad_Desde"] : '' ?>">                      
                        <input type="hidden" name="ID_Persona" data-pre = "1" id = "ID_Persona" value = "0">
                        <script>
                          <?php
                            if(isset($datosNav["ID_Persona"])){
                          ?>
                              seleccionPersona(<?= "'".$datosNav["NombrePersona"]."'" ?>, <?= $datosNav["ID_Persona"] ?>);                            
                          <?php   
                            }
                          ?>                        
                        </script>
                    </div>
              </div> 
              <div class="form-group row" style="position: relative;">
                  <label for="Edad_Hasta" class="col-md-2 col-form-label LblForm">Hasta (Años): </label>
                  <div class="col-md-10">
                      <input type="number" name="Edad_Hasta" data-pre = "1" id="Edad_Hasta" class="form-control" autocomplete="off" placeholder="Sólo Números" min="0" value="<?= (isset($datosNav["Edad_Hasta"])) ? $datosNav["Edad_Hasta"] : '' ?>">
                  </div>
                  <div class="position-absolute" style="z-index: 1100; width: auto; right: -20%; top: -83%" data-bs-delay="10">
                    <div id="edad-hasta-toast" class="toast hide dat-toast" style="width:auto;" role="alert" aria-live="assertive" aria-atomic="true">
                      <div class="toast-body">
                        <span id="edad-hasta-dato">0</span>
                      </div>
                    </div>
                  </div>
              </div> 
              <div class="form-group row">
                    <label for="Meses_Desde" class="col-md-2 col-form-label LblForm">Desde (Meses): </label>
                    <div class="col-md-10">
                        <input type="number" name="Meses_Desde" data-pre = "1" id="Meses_Desde" class="form-control" autocomplete="off" placeholder="Sólo Números" min="0" value="<?= (isset($datosNav["Meses_Desde"])) ? $datosNav["Meses_Desde"] : '' ?>">
                    </div>
              </div> 
              <div class="form-group row" style="position: relative;">
                  <label for="Meses_Hasta" class="col-md-2 col-form-label LblForm">Hasta (Meses): </label>
                  <div class="col-md-10">
                      <input type="number" name="Meses_Hasta" data-pre = "1" id="Meses_Hasta" class="form-control" autocomplete="off" placeholder="Sólo Números" min="0" max="11" value="<?= (isset($datosNav["Meses_Hasta"])) ? $datosNav["Meses_Hasta"] : '' ?>">
                  </div>
                  <div class="position-absolute" style="z-index: 1100; width: auto; right: -20%; top: -83%">
                    <div id="meses-hasta-toast" class="toast hide dat-toast" style="width:auto;" role="alert" aria-live="assertive" aria-atomic="true">
                      <div class="toast-body">
                        <span id="meses-hasta-dato">0</span>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group row">
                <label for="ID_Barrio" class="col-md-2 col-form-label LblForm">Barrio: </label>
                <div class="col-md-9">
                  <?php

                  if (isset($datosNav["ID_Barrio"])) {
                    echo $Element->CBModBarrios($datosNav["ID_Barrio"]);
                  } else {
                    echo $Element->CBRepBarrios();
                  }

                  ?>
                </div>
                <div class="col-md-1 div-button-center">
                    <button type="button" class="btn btn-primary" id="agregarBarrioID">+</button>
                </div>
              </div>
              <div id="contenedorBarrios">              
              </div> 
              <div id="contenedor-calle" class="form-group row" style="margin-bottom: 0.6rem;">
                <label for="BotonModalDireccion_1" style="overflow-wrap: break-word" class="col-md-2 col-form-label LblForm"><span style="display: inline-block;">Domicilio</span>/<span>Familia:</span> </label>
                <div class="col-md-8 flex-sm-boton">
                    <button type = "button" id="BotonModalDireccion_1" class = "btn btn-lg btn-primary btn-block form-control" style="padding-top: 4px;padding-bottom: 4px;" data-toggle="modal" data-target="#ModalCalle">Seleccione una Calle</button>
                </div>
                <div class="col-md-2 form-boton-widht">
                  <input type="number" class="form-control" style="margin-top: 1px;" name = "NumeroDeCalle" id="NumeroDeCalle" data-pre = '1' placeholder="Número" min="1" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label for="manzana" class="col-md-2 col-form-label LblForm">Manzana: </label>
                <div class="col-md-10">
                  <input type="text" class="form-control" name = "Manzana" data-pre = '1' id="manzana" autocomplete="off" value="<?= (isset($datosNav["Manzana"])) ? $datosNav["Manzana"] : '' ?>">
                </div>
              </div>
              <div class="form-group row">
                <label for="lote" class="col-md-2 col-form-label LblForm">Lote: </label>
                <div class="col-md-10">
                  <input type="number" class="form-control" name = "Lote" data-pre = '1' id="lote" autocomplete="off" value="<?= (isset($datosNav["Lote"])) ? $datosNav["Lote"] : '' ?>">
                </div>
              </div>
              <div class="form-group row">
                <label for="familia" class="col-md-2 col-form-label LblForm">Sub-lote: </label>
                <div class="col-md-10">
                  <input type="number" class="form-control" name = "Familia" data-pre = '1' id="familia" autocomplete="off" value="<?= (isset($datosNav["Familia"])) ? $datosNav["Familia"] : '' ?>">
                </div>
              </div>

              <!--<div class="form-group row">
                <label for="inputPassword" class="col-md-2 col-form-label LblForm">Trabajo: </label>
                <div class="col-md-10">
                  <input type="text" class="form-control" name = "Trabajo" id="inputPassword" autocomplete="off" value="<?= (isset($datosNav["Trabajo"])) ? $datosNav["Trabajo"] : '' ?>">
                </div>
              </div>-->
              <div class="form-group row">
                <label for="btn-categoria" class="col-md-2 col-form-label LblForm">Categoría: </label>
                <div class="col-md-9" id = "Categoria">
                  <button type = "button" id="btn-categoria" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalCategoria">Seleccione Categoría</button>  
                </div>
                <div class="col-md-1 div-button-center">
                    <button type="button" class="btn btn-primary" id="agregarCategoriaID">+</button>
                </div>
              </div>
              <div id="contenedorCategoria">              
              </div>
              <div class="form-group row">
                <label for="btn-motivo" class="col-md-2 col-form-label LblForm">Motivo 1: </label>
                <div class="col-md-9" id = "Motivo">
                  <button type = "button" id="btn-motivo" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo">Seleccione Motivo</button>   
                </div>
                  <div class="col-md-1 div-button-center">
                    <button type="button" class="btn btn-primary" id="agregarMotivoID">+</button>
                </div>
              </div>
              <div id="contenedorMotivos">              
              </div>
              <div class="form-group row">
                <label for="ID_Centro" class="col-md-2 col-form-label LblForm">Centro Salud: </label>
                <div class="col-md-10">
                  <?php  

                  if(isset($datosNav["ID_CentroSalud"])){
                    echo $Element->CBRepModCentros($datosNav["ID_CentroSalud"]);
                  }else{
                    echo $Element->CBRepCentros();
                  }
                  ?>
                </div>
              </div>
              <div class="form-group row">
                <label for="Nro_Carpeta" class="col-md-2 col-form-label LblForm">Nro. Carpeta: </label>
                <div class="col-md-10">
                  <input type="text" class="form-control" data-pre = '1' name = "Nro_Carpeta" id="Nro_Carpeta" autocomplete="off" value="<?= (isset($datosNav["Nro_Carpeta"])) ? $datosNav["Nro_Carpeta"] : '' ;?>">
                </div>
              </div>
              <div class="form-group row">
                <label for="Nro_Legajo" class="col-md-2 col-form-label LblForm">Nro. Legajo: </label>
                <div class="col-md-10">
                  <input type="text" class="form-control" name = "Nro_Legajo" data-pre = '1' id="Nro_Legajo" autocomplete="off" value="<?= (isset($datosNav["Nro_Legajo"])) ? $datosNav["Nro_Legajo"] : '' ;?>">
                </div>
              </div>
              
              <div class="form-group row">
                <label for="ID_OtraInstitucion" class="col-md-2 col-form-label LblForm">Otras Instituciones: </label>
                <div class="col-md-10">
                  <?php  
                  
                  if(isset($datosNav["ID_OtraInstitucion"])){
                    echo $Element->CBRepModOtrasInstituciones($datosNav["ID_OtraInstitucion"]);
                  }else{
                    echo $Element->CBRepOtrasInstituciones();
                  }
                  ?>
                </div>
              </div>
              <div class="form-group row">
                <label for="ID_Escuela" class="col-md-2 col-form-label LblForm">Escuela: </label>
                <div class="col-md-10">
                  <?php  

                  if (isset($datosNav["ID_Escuela"])) {
                    echo $Element->CBModEscuelas($datosNav["ID_Escuela"]);
                  } else {
                    echo $Element->CBRepEscuelas();
                  }
                  ?>
                </div>
              </div>
              <div class="form-group row">
                <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Responsable: </label>
                <div class="col-md-9">
                  <?php  

                  if(isset($datosNav["ID_Responsable"])){
                    echo $Element->CBRepModResponsables($datosNav["ID_Responsable"]);
                  }else{
                    echo $Element->CBRepResponsable();
                  }
                  ?>
                </div>
                <div class="col-md-1 div-button-center">
                    <button type="button" class="btn btn-primary" id="agregarResponsableID">+</button>
                </div>
              </div>
              <div id="responsables">

              </div>
              <div class="form-group row">
                <label for="inpMostrar" class="col-md-2 col-form-label LblForm">Mostrar Personas: </label>
                <div class="col-md-10">
                  <select class="form-control" name="Mostrar" data-pre = '1' id="inpMostrar">
                    <option value="0" selected>Con Movimientos</option>
                    <option value="1">Todos</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <div class="offset-md-1 col-md-10" style="text-align: center;" id = "InputsGenerales">
                  <input type="hidden" name="ID_Motivo" data-pre = '1' id = "ID_Motivo" value = "0">
                  <input type="hidden" name="ID_Categoria" data-pre = '1' id = "ID_Categoria" value = "0">
                  <input type="hidden" name="inicial-movimiento-check" id = "inicial-movimiento-check" value = "">
                  <input type="hidden" name="fin-movimiento-check" id = "fin-movimiento-check" value = "">
                  <input type="hidden" name="ID_Config" id="ID_Config" value="table">
                  <input type="hidden" name="Calle" data-pre = '1' id="Calle" value="0">
                  <button type="submit" style="display:block; margin: auto;" class="btn btn-outline-success">Aceptar</button>
                </div>
              </div>
            </form>
            <br><br><br>
            <!-- Fin Carga -->
            <!-- SECCION DE MODALES -->
            <!-- Modal SELECCION PERSONAS -->
            <div class="modal fade bd-example-modal-lg" id="ModalPersona" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="margin-left: auto;">Selección de Persona</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10">
                          <div class="input-group mb-3">
                            <input class = "form-control" type="text" name="BuscarPersona" id = "SearchPersonas" autocomplete="off" placeholder="Ingrese el nombre, apellido, documento o legajo">
                            <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">Buscar</span>
                            </div>	
                          </div>		        				
                        </div>
                        <div class="col"></div>
                      </div>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10" id = "ResultadosPersonas">
                          
                        </div>
                        <div class="col"></div>
                      </div>			        	
                    </form>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>			        
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN MODAL SELECCION PERSONAS -->
          <!-- Modal de Carga de Calle-->
            <div class="modal fade bd-example-modal-lg" id="ModalCalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Seleccione una Calle</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form>
                        <div class="row">
                          <div class="col"></div>
                          <div class="col-8">
                            <div class="input-group mb-3">
                              <input class = "form-control" type="text" name="BuscarCalle" id = "SearchCalle" autocomplete="off" placeholder="Ingrese el nombre de calle">
                              <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Buscar</span>
                              </div>	
                            </div>		        				
                          </div>
                          <div class="col"></div>
                        </div>
                        <div class="row">
                          <div class="col"></div>
                          <div class="col-10" id = "ResultadosCalles">
                            
                          </div>
                          <div class="col"></div>
                        </div>
                      </form>
                    </div>
                    <div class="modal-footer" style="justify-content: center;">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>			        
                    </div>
                  </div>
              </div>
            </div>
            <!-- Modal de Carga de Calle-->
            <!-- Modal SELECCION MOTIVO -->
            <div class="modal fade bd-example-modal-lg" id="ModalMotivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="margin-left: auto;">Selección de Motivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10">
                            <div class="input-group mb-3">
                              <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos1" autocomplete="off">
                              <select id="select-motivo1" name="select-motivo1" class="btn btn-outline-secondary dropdown-toggle input-group-text">
                                <option value="denominacion" selected>Denominacion</option>
                                <option value="codigo">Codigo</option>
                              </select>
                              <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Buscar</span>
                              </div>  
                            </div>
                            <!--<div class="input-group mb-3">
                                <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos" onKeyUp="buscarMotivos()" autocomplete="off">
                                <div class="input-group-append">
                                  <span class="input-group-text" id="basic-addon2">Buscar</span>
                                </div>  
                              </div> -->                    
                        </div>
                        <div class="col"></div>
                      </div>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10" id = "ResultadosMotivos1">
                          
                        </div>
                        <div class="col"></div>
                      </div>
                      <div id="btn-up-scroll" class="scroll" data-id-element='SearchMotivos1' style="display: none;">
                          <svg width="46px" height="46px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003" transform="rotate(180)">
                              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g>
                              <g id="SVGRepo_iconCarrier"> 
                                  <rect width="24" height="24" fill="white"></rect> 
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8L11 13.5858L9.70711 12.2929C9.31658 11.9024 8.68342 11.9024 8.29289 12.2929C7.90237 12.6834 7.90237 13.3166 8.29289 13.7071L11.2059 16.6201C11.2209 16.6351 11.2363 16.6497 11.252 16.6637C11.4352 16.87 11.7024 17 12 17C12.2976 17 12.5648 16.87 12.748 16.6637C12.7637 16.6497 12.7791 16.6351 12.7941 16.6201L15.7071 13.7071C16.0976 13.3166 16.0976 12.6834 15.7071 12.2929C15.3166 11.9024 14.6834 11.9024 14.2929 12.2929L13 13.5858L13 8Z" fill="#aea7a7"></path> 
                              </g>
                          </svg>
                      </div>
                      <div id="btn-down-scroll" class="scroll" style='bottom: 248px; display: none;' data-id-element='cerrar-motivo'>
                          <svg width="46px" height="46px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g><g id="SVGRepo_iconCarrier"> <rect width="24" height="24" fill="white"></rect> <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8L11 13.5858L9.70711 12.2929C9.31658 11.9024 8.68342 11.9024 8.29289 12.2929C7.90237 12.6834 7.90237 13.3166 8.29289 13.7071L11.2059 16.6201C11.2209 16.6351 11.2363 16.6497 11.252 16.6637C11.4352 16.87 11.7024 17 12 17C12.2976 17 12.5648 16.87 12.748 16.6637C12.7637 16.6497 12.7791 16.6351 12.7941 16.6201L15.7071 13.7071C16.0976 13.3166 16.0976 12.6834 15.7071 12.2929C15.3166 11.9024 14.6834 11.9024 14.2929 12.2929L13 13.5858L13 8Z" fill="#aea7a7"></path> </g></svg>
                          <i class='far fa-arrow-alt-circle-down' style='font-size:48px; color:red'></i>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-danger" id="seleccionar-motivo" data-dismiss="modal">OK</button>
                    <button id="cerrar-motivo" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>           
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN MODAL SELECCION MOTIVO -->
            <!-- Modal SELECCION MOTIVO 2 -->
            <div class="modal fade bd-example-modal-lg" id="ModalMotivo2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="margin-left: auto;">Selección de Motivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10">
                          <div class="input-group mb-3">
                              <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos2" autocomplete="off">
                              <select id="select-motivo2" name="select-motivo2" class="btn btn-outline-secondary dropdown-toggle input-group-text">
                                <option value="denominacion" selected>Denominacion</option>
                                <option value="codigo">Codigo</option>
                              </select>
                              <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Buscar</span>
                              </div>  
                          </div>                    
                        </div>
                        <div class="col"></div>
                      </div>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10" id = "ResultadosMotivos2">
                          
                        </div>
                        <div class="col"></div>
                      </div>
                      <div id="btn-up-scroll" class="scroll" data-id-element='SearchMotivos2' style="display: none;">
                          <svg width="46px" height="46px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003" transform="rotate(180)">
                              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g>
                              <g id="SVGRepo_iconCarrier"> 
                                  <rect width="24" height="24" fill="white"></rect> 
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8L11 13.5858L9.70711 12.2929C9.31658 11.9024 8.68342 11.9024 8.29289 12.2929C7.90237 12.6834 7.90237 13.3166 8.29289 13.7071L11.2059 16.6201C11.2209 16.6351 11.2363 16.6497 11.252 16.6637C11.4352 16.87 11.7024 17 12 17C12.2976 17 12.5648 16.87 12.748 16.6637C12.7637 16.6497 12.7791 16.6351 12.7941 16.6201L15.7071 13.7071C16.0976 13.3166 16.0976 12.6834 15.7071 12.2929C15.3166 11.9024 14.6834 11.9024 14.2929 12.2929L13 13.5858L13 8Z" fill="#aea7a7"></path> 
                              </g>
                          </svg>
                      </div>
                      <div id="btn-down-scroll" class="scroll" style='bottom: 248px; display: none;' data-id-element='cerrar-motivo2'>
                          <svg width="46px" height="46px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g><g id="SVGRepo_iconCarrier"> <rect width="24" height="24" fill="white"></rect> <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8L11 13.5858L9.70711 12.2929C9.31658 11.9024 8.68342 11.9024 8.29289 12.2929C7.90237 12.6834 7.90237 13.3166 8.29289 13.7071L11.2059 16.6201C11.2209 16.6351 11.2363 16.6497 11.252 16.6637C11.4352 16.87 11.7024 17 12 17C12.2976 17 12.5648 16.87 12.748 16.6637C12.7637 16.6497 12.7791 16.6351 12.7941 16.6201L15.7071 13.7071C16.0976 13.3166 16.0976 12.6834 15.7071 12.2929C15.3166 11.9024 14.6834 11.9024 14.2929 12.2929L13 13.5858L13 8Z" fill="#aea7a7"></path> </g></svg>
                          <i class='far fa-arrow-alt-circle-down' style='font-size:48px; color:red'></i>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-danger" id="seleccionar-motivo2" data-dismiss="modal">OK</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="cerrar-motivo2">Cerrar</button>            
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN MODAL SELECCION MOTIVO -->
            <!-- Modal SELECCION MOTIVO 3 -->
            <div class="modal fade bd-example-modal-lg" id="ModalMotivo3" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="margin-left: auto;">Selección de Motivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10">
                          <div class="input-group mb-3">
                              <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos3" autocomplete="off">
                              <select id="select-motivo3" name="select-motivo3" class="btn btn-outline-secondary dropdown-toggle input-group-text">
                                <option value="denominacion" selected>Denominacion</option>
                                <option value="codigo">Codigo</option>
                              </select>
                              <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Buscar</span>
                              </div>  
                          </div>                    
                        </div>
                        <div class="col"></div>
                      </div>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10" id = "ResultadosMotivos3">
                          
                        </div>
                        <div class="col"></div>
                      </div>
                      <div id="btn-up-scroll" class="scroll" data-id-element='SearchMotivos3' style="display: none;">
                          <svg width="46px" height="46px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003" transform="rotate(180)">
                              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g>
                              <g id="SVGRepo_iconCarrier"> 
                                  <rect width="24" height="24" fill="white"></rect> 
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8L11 13.5858L9.70711 12.2929C9.31658 11.9024 8.68342 11.9024 8.29289 12.2929C7.90237 12.6834 7.90237 13.3166 8.29289 13.7071L11.2059 16.6201C11.2209 16.6351 11.2363 16.6497 11.252 16.6637C11.4352 16.87 11.7024 17 12 17C12.2976 17 12.5648 16.87 12.748 16.6637C12.7637 16.6497 12.7791 16.6351 12.7941 16.6201L15.7071 13.7071C16.0976 13.3166 16.0976 12.6834 15.7071 12.2929C15.3166 11.9024 14.6834 11.9024 14.2929 12.2929L13 13.5858L13 8Z" fill="#aea7a7"></path> 
                              </g>
                          </svg>
                      </div>
                      <div id="btn-down-scroll" class="scroll" style='bottom: 248px; display: none;' data-id-element='cerrar-motivo3'>
                          <svg width="46px" height="46px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g><g id="SVGRepo_iconCarrier"> <rect width="24" height="24" fill="white"></rect> <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8L11 13.5858L9.70711 12.2929C9.31658 11.9024 8.68342 11.9024 8.29289 12.2929C7.90237 12.6834 7.90237 13.3166 8.29289 13.7071L11.2059 16.6201C11.2209 16.6351 11.2363 16.6497 11.252 16.6637C11.4352 16.87 11.7024 17 12 17C12.2976 17 12.5648 16.87 12.748 16.6637C12.7637 16.6497 12.7791 16.6351 12.7941 16.6201L15.7071 13.7071C16.0976 13.3166 16.0976 12.6834 15.7071 12.2929C15.3166 11.9024 14.6834 11.9024 14.2929 12.2929L13 13.5858L13 8Z" fill="#aea7a7"></path> </g></svg>
                          <i class='far fa-arrow-alt-circle-down' style='font-size:48px; color:red'></i>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-danger" id="seleccionar-motivo3" data-dismiss="modal">OK</button>
                    <button type="button" id="cerrar-motivo3" class="btn btn-primary" data-dismiss="modal">Cerrar</button>             
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN MODAL SELECCION MOTIVO -->
            <!-- Modal SELECCION MOTIVO 4 -->
            <div class="modal fade bd-example-modal-lg" id="ModalMotivo4" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="margin-left: auto;">Selección de Motivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10">
                          <div class="input-group mb-3">
                              <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos4" autocomplete="off">
                              <select id="select-motivo4" name="select-motivo4" class="btn btn-outline-secondary dropdown-toggle input-group-text">
                                <option value="denominacion" selected>Denominacion</option>
                                <option value="codigo">Codigo</option>
                              </select>
                              <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Buscar</span>
                              </div>  
                          </div>                    
                        </div>
                        <div class="col"></div>
                      </div>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10" id = "ResultadosMotivos4">
                          
                        </div>
                        <div class="col"></div>
                      </div>
                      <div id="btn-up-scroll" class="scroll" data-id-element='SearchMotivos4' style="display: none;">
                          <svg width="46px" height="46px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003" transform="rotate(180)">
                              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g>
                              <g id="SVGRepo_iconCarrier"> 
                                  <rect width="24" height="24" fill="white"></rect> 
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8L11 13.5858L9.70711 12.2929C9.31658 11.9024 8.68342 11.9024 8.29289 12.2929C7.90237 12.6834 7.90237 13.3166 8.29289 13.7071L11.2059 16.6201C11.2209 16.6351 11.2363 16.6497 11.252 16.6637C11.4352 16.87 11.7024 17 12 17C12.2976 17 12.5648 16.87 12.748 16.6637C12.7637 16.6497 12.7791 16.6351 12.7941 16.6201L15.7071 13.7071C16.0976 13.3166 16.0976 12.6834 15.7071 12.2929C15.3166 11.9024 14.6834 11.9024 14.2929 12.2929L13 13.5858L13 8Z" fill="#aea7a7"></path> 
                              </g>
                          </svg>
                      </div>
                      <div id="btn-down-scroll" class="scroll" style='bottom: 248px; display: none;' data-id-element='cerrar-motivo4'>
                          <svg width="46px" height="46px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g><g id="SVGRepo_iconCarrier"> <rect width="24" height="24" fill="white"></rect> <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8L11 13.5858L9.70711 12.2929C9.31658 11.9024 8.68342 11.9024 8.29289 12.2929C7.90237 12.6834 7.90237 13.3166 8.29289 13.7071L11.2059 16.6201C11.2209 16.6351 11.2363 16.6497 11.252 16.6637C11.4352 16.87 11.7024 17 12 17C12.2976 17 12.5648 16.87 12.748 16.6637C12.7637 16.6497 12.7791 16.6351 12.7941 16.6201L15.7071 13.7071C16.0976 13.3166 16.0976 12.6834 15.7071 12.2929C15.3166 11.9024 14.6834 11.9024 14.2929 12.2929L13 13.5858L13 8Z" fill="#aea7a7"></path> </g></svg>
                          <i class='far fa-arrow-alt-circle-down' style='font-size:48px; color:red'></i>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-danger" id="seleccionar-motivo4" data-dismiss="modal">OK</button>
                    <button id="cerrar-motivo4" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>          
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN MODAL SELECCION MOTIVO -->
            <!-- Modal SELECCION MOTIVO 5 -->
            <div class="modal fade bd-example-modal-lg" id="ModalMotivo5" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="margin-left: auto;">Selección de Motivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10">
                          <div class="input-group mb-3">
                              <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos5" autocomplete="off">
                              <select id="select-motivo5" name="select-motivo5" class="btn btn-outline-secondary dropdown-toggle input-group-text">
                                <option value="denominacion" selected>Denominacion</option>
                                <option value="codigo">Codigo</option>
                              </select>
                              <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Buscar</span>
                              </div>  
                          </div>                    
                        </div>
                        <div class="col"></div>
                      </div>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10" id = "ResultadosMotivos5">
                          
                        </div>
                        <div class="col"></div>
                      </div>
                      <div id="btn-up-scroll" class="scroll" data-id-element='SearchMotivos5' style="display: none;">
                          <svg width="46px" height="46px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003" transform="rotate(180)">
                              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g>
                              <g id="SVGRepo_iconCarrier"> 
                                  <rect width="24" height="24" fill="white"></rect> 
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8L11 13.5858L9.70711 12.2929C9.31658 11.9024 8.68342 11.9024 8.29289 12.2929C7.90237 12.6834 7.90237 13.3166 8.29289 13.7071L11.2059 16.6201C11.2209 16.6351 11.2363 16.6497 11.252 16.6637C11.4352 16.87 11.7024 17 12 17C12.2976 17 12.5648 16.87 12.748 16.6637C12.7637 16.6497 12.7791 16.6351 12.7941 16.6201L15.7071 13.7071C16.0976 13.3166 16.0976 12.6834 15.7071 12.2929C15.3166 11.9024 14.6834 11.9024 14.2929 12.2929L13 13.5858L13 8Z" fill="#aea7a7"></path> 
                              </g>
                          </svg>
                      </div>
                      <div id="btn-down-scroll" class="scroll" style='bottom: 248px; display: none;' data-id-element='cerrar-motivo5'>
                          <svg width="46px" height="46px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g><g id="SVGRepo_iconCarrier"> <rect width="24" height="24" fill="white"></rect> <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8L11 13.5858L9.70711 12.2929C9.31658 11.9024 8.68342 11.9024 8.29289 12.2929C7.90237 12.6834 7.90237 13.3166 8.29289 13.7071L11.2059 16.6201C11.2209 16.6351 11.2363 16.6497 11.252 16.6637C11.4352 16.87 11.7024 17 12 17C12.2976 17 12.5648 16.87 12.748 16.6637C12.7637 16.6497 12.7791 16.6351 12.7941 16.6201L15.7071 13.7071C16.0976 13.3166 16.0976 12.6834 15.7071 12.2929C15.3166 11.9024 14.6834 11.9024 14.2929 12.2929L13 13.5858L13 8Z" fill="#aea7a7"></path> </g></svg>
                          <i class='far fa-arrow-alt-circle-down' style='font-size:48px; color:red'></i>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-danger" id="seleccionar-motivo5" data-dismiss="modal">OK</button>
                    <button type="button" class="btn btn-primary" id="cerrar-motivo5" data-dismiss="modal">Cerrar</button>           
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN MODAL SELECCION MOTIVO -->
            <!-- Modal SELECCION CATEGORIA -->
            <div class="modal fade bd-example-modal-lg" id="ModalCategoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="margin-left: auto;">Selección de Categoria</h5>
                    <button type="button" class="close" id="close-categorias" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10">
                          <div class="input-group mb-3">
                            <input class = "form-control" type="text" name="BuscarCategorias" id = "SearchCategorias" autocomplete="off">
                            <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">Buscar</span>
                            </div>  
                          </div>                    
                        </div>
                        <div class="col"></div>
                      </div>
                      <div class="row">
                        <div class="col"></div>
                        <div class="col-10" id = "ResultadosCategorias">
                          
                        </div>
                        <div class="col"></div>
                      </div>
                      <div id="btn-up-scroll" class="scroll" data-id-element='SearchCategorias' style="display: none;">
                          <svg width="46px" height="46px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003" transform="rotate(180)">
                              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g>
                              <g id="SVGRepo_iconCarrier"> 
                                  <rect width="24" height="24" fill="white"></rect> 
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8L11 13.5858L9.70711 12.2929C9.31658 11.9024 8.68342 11.9024 8.29289 12.2929C7.90237 12.6834 7.90237 13.3166 8.29289 13.7071L11.2059 16.6201C11.2209 16.6351 11.2363 16.6497 11.252 16.6637C11.4352 16.87 11.7024 17 12 17C12.2976 17 12.5648 16.87 12.748 16.6637C12.7637 16.6497 12.7791 16.6351 12.7941 16.6201L15.7071 13.7071C16.0976 13.3166 16.0976 12.6834 15.7071 12.2929C15.3166 11.9024 14.6834 11.9024 14.2929 12.2929L13 13.5858L13 8Z" fill="#aea7a7"></path> 
                              </g>
                          </svg>
                      </div>
                      <div id="btn-down-scroll" class="scroll" style='bottom: 248px; display: none;' data-id-element='cerrar-categorias'>
                          <svg width="46px" height="46px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g><g id="SVGRepo_iconCarrier"> <rect width="24" height="24" fill="white"></rect> <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8L11 13.5858L9.70711 12.2929C9.31658 11.9024 8.68342 11.9024 8.29289 12.2929C7.90237 12.6834 7.90237 13.3166 8.29289 13.7071L11.2059 16.6201C11.2209 16.6351 11.2363 16.6497 11.252 16.6637C11.4352 16.87 11.7024 17 12 17C12.2976 17 12.5648 16.87 12.748 16.6637C12.7637 16.6497 12.7791 16.6351 12.7941 16.6201L15.7071 13.7071C16.0976 13.3166 16.0976 12.6834 15.7071 12.2929C15.3166 11.9024 14.6834 11.9024 14.2929 12.2929L13 13.5858L13 8Z" fill="#aea7a7"></path> </g></svg>
                          <i class='far fa-arrow-alt-circle-down' style='font-size:48px; color:red'></i>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-danger" id="seleccionar-categorias" data-dismiss="modal">OK</button>
                    <button type="button" class="btn btn-primary" id="cerrar-categorias" data-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN MODAL SELECCION CATEGORIA -->
            <!-- Modal CONFIGURACION-->
            <div class="modal fade bd-example-modal-lg" id="ModalConfig" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header" style="justify-content: center;">
                    <h1>Configuración</h1>
                  </div>
                  <div class="modal-body">
                    <div class="row" style="justify-content: center">
                      <div class="col-2">
                        <label for="formatConfig">Formato: </label>
                      </div>
                      <div class="col-4">
                        <select class="input-group mb-3" name="formatConfig" id="formatConfig">
                          <option value="grid">Grilla</option>
                          <option value="table" selected>Tabla</option>
                        </select>
                      </div>
                    </div>              
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>             
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN MODAL SELECCION CONFIGURACION -->
            <!-- Modal ENLACE DRIVER-->
            <div class="modal fade bd-example-modal-lg" id="modal-enlace-drive" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header" style="justify-content: center;">
                    <h1>Enlaces Drive</h1>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-4">
                        <?php 
                          echo $Element->CBCSDrives();
                        ?>
                      </div>
                      <div class="col-8">
                        <?php 
                          echo $Element->CBDrive();
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN MODAL SELECCION ENLACE DRIVER -->
            <!-- Modal filtro-->
            <div class="modal fade bd-example-modal-lg" id="modal-datos" style="overflow-y: scroll;" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 80%;" role="document">
                <div class="modal-content">
                  <div class="modal-header" style="justify-content: center;">
                    <h1 class="col-11" style="text-align: center;">Preferencias</h1>
                    <button type="button" class="close col-1" style="margin-left: 0px" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-5">
                        <?php
                          echo $Element->CBCSfiltros();
                        ?>
                      </div>
                      <div class="col-7">
                        <?php
                          echo $Element->CBfiltro();
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <button id='send-admin' class='btn btn-success' style='display: none;' type='button'>Enviar al administrador</button>
                    <button id='save-data' class='btn btn-primary' type='button' data-toggle='modal' data-target='#filtro-nombre-dato'> Guardar </button>
                    <button id='close-data' type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN MODAL SELECCION filtro -->

            <!--
            <div class="modal fade" style="top: 20%;" id="filtro-nombre-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title col-11" style="text-align: center;">Nombre de Preferencias</h5>
                    <button type="button" class="close col-1" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <div class="form-group">
                        <textarea class="form-control" id="message-text"></textarea>
                      </div>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-success" id="bn-new-filtro" data-dismiss="modal">Aceptar</button>
                    <button type="button" class="btn btn-danger">Cancelar</button>
                  </div>
                </div>
              </div>
            </div>
            -->

            <div class="modal fade" style="top: 20%;" id="filtro-nombre-dato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title col-11" style="text-align: center;">Nombre de Preferencias</h5>
                    <button type="button" class="close col-1" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <div class="form-group">
                        <textarea class="form-control" id="text-filtro"></textarea>
                      </div>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-success" id="bn-filtro-dato" data-dismiss="modal">Aceptar</button>
                    <button type="button" class="btn btn-danger"  id="bn-cancel-dato" data-dismiss="modal">Cancelar</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- FIN SECCION DE MODALES -->
      </div>
      <div class = "col-1">
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