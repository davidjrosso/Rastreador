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
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <base href="/">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"> 
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>   
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/ValidarMovimiento.js"></script>
  <script src="./dist/form.js"></script>
  <script defer src="./dist/control.js"></script>
  <script>
      let mensajeError = '<?php echo $mensaje_error;?>';
      let mensajeSuccess = '<?php echo $mensaje_success;?>';

       $(document).ready(function () {
              let date_input=$('input[name="Fecha"]');
              let container=$('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
              date_input.datepicker({
                  format: 'dd/mm/yyyy',
                  container: container,
                  todayHighlight: true,
                  autoclose: true,
                  closeText: 'Cerrar',
                  days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                  daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                  daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                  months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                  monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                  today: "Hoy",
                  monthsTitle: "Meses",
                  clear: "Borrar",
                  weekStart: 1,
              });
			        controlMensaje(mensajeSuccess, mensajeError);
          });

  </script>
</head>
<body>
<div class = "row">
<?php
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_MOVIMIENTO);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Nuevo Movimiento</p>
      </div>
      <div class="col"></div>
    </div><br>
    <div class="row">
      <div class="col"></div>
      <div class="col-10">
          <div class="row">
              <center>
                <button class = "btn btn-secondary btn-sm" onClick="location.href ='/movimiento/nuevo'">Agregar Nuevo Movimiento</button>
                <button class = "btn btn-secondary btn-sm" onClick="location.href='/persona/nuevo'">Agregar Nueva Persona</button>
                <button class = "btn btn-secondary btn-sm" onClick="location.href='/motivo/nuevo'">Agregar Nuevo Motivo</button>
                <button class = "btn btn-secondary btn-sm" onClick="location.href='/responsable/nuevo'">Agregar Nuevo Responsable</button>
              </center>
          </div>
          <br>
          <div class="row">
              <center>
                <button class = "btn btn-secondary btn-sm" onClick="location.href='/centrodesalud/nuevo'">Agregar Nuevo Centro</button>
              </center>
          </div>
      </div>
      <div class="col"></div>
    </div>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Carga -->
          <p class = "Titulos">Cargar Nuevo Movimiento</p>
          <form method = "post" onKeydown="return event.key != 'Enter';" action = "/insert_movimiento" onSubmit = "return ValidarMovimiento();">
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Fecha: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Fecha" data-date-format="dd/mm/yyyy" id="datepicker" width="100%" autocomplete="off" placeholder= "<?php echo implode("/", array_reverse(explode("-",date('Y-m-d')))); ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Persona: </label>
              <div class="col-md-10" id = "Persona">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalPersona">Seleccione una Persona</button>  
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 1: </label>
              <div class="col-md-9" id = "Motivo_1">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo_1">Seleccione un Motivo</button>   
              </div>
                <div class="col-md-1">
                  <button type="button" class="btn btn-primary" onClick="agregarMotivo()" id="agregarMotivoID">+</button>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 2: </label>
              <div class="col-md-10" id = "Motivo_2">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo_2">Seleccione un Motivo</button> 
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 3: </label>
              <div class="col-md-10" id = "Motivo_3">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo_3">Seleccione un Motivo</button> 
              </div>
            </div>
            <div id="contenedorMotivos">              
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Observaciones: </label>
              <div class="col-md-10">
                <textarea class = "form-control" row = "3" name = "Observaciones" id="Observaciones"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Responsable: </label>
              <div class = "col-md-9">
                <?php  
                /*if(isset($_SESSION["UltResponsable"])){
                  $xID_Responsable = $_SESSION["UltResponsable"];
                  echo $Element->CBModResponsables($xID_Responsable);
                }else{
                  echo $Element->CBResponsables();
                }*/
                echo $Element->CBResponsables();
                ?>
              </div>
              <div class="col-md-1">
                  <button type="button" class="btn btn-primary" onClick="agregarResponsable()" id="agregarResponsableID">+</button>
              </div>
            </div>
            <div id="contenedorResponsables">              
            </div>
            <div class="form-group row">
              <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Centro de Salud: </label>
              <div class = "col-md-10">
                <?php  
                /*if(isset($_SESSION["UltCentro"])){
                  $xID_Centro = $_SESSION["UltCentro"];
                  echo $Element->CBModCentros($xID_Centro);                  
                }else{
                  echo $Element->CBCentros();
                }*/
                echo $Element->CBCentros();
                ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="exampleFormControlSelect1" class="col-md-2 col-form-label LblForm">Otras Instituciones: </label>
              <div class = "col-md-10">
                <?php  
                /*if(isset($_SESSION["UltOtraInstitucion"])){
                  $xID_OtraInstitucion = $_SESSION["UltOtraInstitucion"];
                  echo $Element->CBModOtrasInstituciones($xID_OtraInstitucion);                  
                }else{
                  echo $Element->CBOtrasInstituciones();
                }*/
                echo $Element->CBOtrasInstituciones();
                ?>
              </div>
            </div>
            <div class="form-group row">
              <div class="offset-md-2 col-md-10" id = "InputsGenerales">
                <input type="hidden" name="ID_Persona" id = "ID_Persona" value = "0">
                <input type="hidden" name="ID_Motivo_1" id = "ID_Motivo_1" value = "0">
                <input type="hidden" name="ID_Motivo_2" id = "ID_Motivo_2" value = "0">
                <input type="hidden" name="ID_Motivo_3" id = "ID_Motivo_3" value = "0">
                <button type="submit" class="btn btn-outline-success">Guardar</button> 
                <button type="button" class="btn btn-outline-secondary" onClick="resetearForm()">Cancelar</button>
                <button type = "button" class = "btn btn-danger" onClick = "location.href = '/movimientos'">Atras</button>
              </div>
            </div>
          </form>
          <div class="row">
              <div class="col-10"></div>
              <div class="col-2">
                
              </div>
          </div>
          <!-- Fin Carga -->
           <!-- SECCION DE MODALES -->
      <!-- Modal SELECCION PERSONAS -->
      <div class="modal fade bd-example-modal-lg" id="ModalPersona" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Persona</h5>
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
                      <input class = "form-control" type="text" name="BuscarPersona" id = "SearchPersonas" onKeyUp="buscarPersonas()" autocomplete="off" placeholder="Ingrese el nombre, apellido o dni de la persona">
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
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION PERSONAS -->
      <!-- Modal SELECCION MOTIVO -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Motivo</h5>
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
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos_1" onKeyUp="buscarMotivos(1)" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos_1">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION MOTIVO -->
      <!-- Modal SELECCION MOTIVO -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Motivo</h5>
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
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos_2" onKeyUp="buscarMotivos(2)" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos_2">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION MOTIVO -->
      <!-- Modal SELECCION MOTIVO -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo_3" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Motivo</h5>
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
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos_3" onKeyUp="buscarMotivos(3)" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos_3">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION MOTIVO -->
      <!-- Modal SELECCION MOTIVO 4 -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Motivo</h5>
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
                      <input class = "form-control" type="text" name="BuscarMotivos4" id = "SearchMotivos_4" onKeyUp="buscarMotivos(4)" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos_4">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION MOTIVO -->
      <!-- Modal SELECCION MOTIVO 5 -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo_5" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Selección de Motivo</h5>
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
                      <input class = "form-control" type="text" name="BuscarMotivos5" id = "SearchMotivos_5" onKeyUp="buscarMotivos(5)" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos_5">
                    
                  </div>
                  <div class="col"></div>
                </div>                
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION MOTIVO -->
      <!-- FIN SECCION DE MODALES -->
  </div>
</div>
</div>
</body>
</html>