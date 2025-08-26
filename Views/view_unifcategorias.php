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


require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Categoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/CategoriaRol.php");

header("Content-Type: text/html;charset=utf-8");

$Con = new Conexion();
$Con->OpenConexion();
$ID_Usuario = $_SESSION["Usuario"];
$account = new Account(account_id: $ID_Usuario);
$TipoUsuario = $account->get_id_tipo_usuario();
$Con->CloseConexion();

?>
<!DOCTYPE html>
<html>
<head>
  <title>Rastreador III</title>
  <meta charset="utf-8">
  <base href="/">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
  <script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="js/ValidarUnifPersonas.js"></script>
  <script src="./dist/control.js"></script>
  <script>
       function buscarCategoria_1(){
          var xBarrio = document.getElementById('SearchCategoria_1').value;
          var textoBusqueda = xBarrio;
          xmlhttp=new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              contenidosRecibidos = xmlhttp.responseText;
              document.getElementById("ResultadosCategoria_1").innerHTML=contenidosRecibidos;
              }
          }
          xmlhttp.open('POST', 'buscarCategoria_2.php?valorBusqueda=' + textoBusqueda, true); // Método post y url invocada
          xmlhttp.send();
        }

        function buscarCategoria_2(){
          var xBarrio = document.getElementById('SearchCategoria_2').value;
          var textoBusqueda = xBarrio;
          xmlhttp=new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              contenidosRecibidos = xmlhttp.responseText;
              document.getElementById("ResultadosCategoria_2").innerHTML=contenidosRecibidos;
              }
          }
          xmlhttp.open('POST', 'buscarCategoria_3.php?valorBusqueda=' + textoBusqueda, true); // Método post y url invocada
          xmlhttp.send();
        }

        function seleccionCategoria_1(xCategoria, xID){
          let categoria = $("#categoria_1");
          $("#ID_Categoria_unif").val(xID);
          categoria.html("");
          categoria.html(xCategoria);
        }

        function seleccionCategoria_2(xCategoria, xID){
          let categoria = $("#categoria_2");
          $("#ID_Categoria_del").val(xID);
          categoria.html("");
          categoria.html(xCategoria);
        }
  </script>

</head>
<body>
<div class = "row">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_UNIFICACION_CATEGORIA);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Unificar Categorias</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <p class = "TextoAdvertenciaUnificar">¡ADVERTENCIA! La segunda Categoria seleccionada se unirá a la primer Categoria seleccionada. La segunda Categoria se eliminará.</p>
          <br>
          <form method = "post" action = "Controladores/pedirunificarcategoria.php">
              <div class="form-group row">
                  <label for="categoria_1" class="col-md-3 col-form-label LblForm">Primer Categoria: </label>
                  <div class="col-md-9">
                    <button id="categoria_1" type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#modal-categoria-1">Seleccione una Categoria</button>  
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="categoria_2" class="col-md-3 col-form-label LblForm">Segunda Categoria: </label>
                  <div class="col-md-9">
                    <button id="categoria_2" type="button" class="btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#modal-categoria-2">Seleccione una Categoria</button>  
                  </div>
              </div>  
              <div class="form-group row">
              <div class="offset-md-3 col-md-9">
                <input type="hidden" name="ID_Categoria_unif" id="ID_Categoria_unif" value="0">
                <input type="hidden" name="ID_Categoria_del" id="ID_Categoria_del" value="0">
                <button type="submit" class="btn btn-outline-success">Aceptar</button>
                <button type="button" class="btn btn-outline-secondary" onclick="location.href = '/'">Volver</button>
              </div>
            </div>
          </form>
          <br><br>
          <!-- Fin Carga -->
          <!-- Modal SELECCION Categoria -->
          <div class="modal fade bd-example-modal-lg" id="modal-categoria-1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Selección de Categoria</h5>
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
                          <input class = "form-control" type="text" name="BuscarCategoria" id = "SearchCategoria_1" onKeyUp="buscarCategoria_1()" autocomplete="off">
                          <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">Buscar</span>
                          </div>  
                        </div>                    
                      </div>
                      <div class="col"></div>
                    </div>
                    <div class="row">
                      <div class="col"></div>
                      <div class="col-10" id = "ResultadosCategoria_1">
                        
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
          <!-- FIN MODAL SELECCION Categoria -->
          <!-- Modal SELECCION Categoria -->
          <div class="modal fade bd-example-modal-lg" id="modal-categoria-2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Selección de Categoria</h5>
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
                          <input class = "form-control" type="text" name="BuscarCategoria" id="SearchCategoria_2" onKeyUp="buscarCategoria_2()" autocomplete="off">
                          <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">Buscar</span>
                          </div>  
                        </div>                    
                      </div>
                      <div class="col"></div>
                    </div>
                    <div class="row">
                      <div class="col"></div>
                      <div class="col-10" id = "ResultadosCategoria_2">
                        
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
          <!-- FIN MODAL SELECCION Categoria -->
  </div>
</div>
</div>
<?php  
if(isset($_REQUEST["Mensaje"])){
  echo "<script type='text/javascript'>
  swal('".$_REQUEST["Mensaje"]."','','success');
</script>";
}
if(isset($_REQUEST["MensajeError"])){
  echo "<script type='text/javascript'>
  swal('".$_REQUEST["MensajeError"]."','','warning');
</script>";
}
?>
<?php
?>
</body>
</html>