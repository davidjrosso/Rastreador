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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
  <!--<script type="text/javascript" src = "js/Funciones.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js" integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script> <!-- ESTO ES NECESARIO PARA QUE ANDE EN ESPAÑOL -->
  <script src="js/ValidarGeneral.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!--
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
-->
  <script>
    var cantBarrios = 1;
    var cantMotivos = 3;
    $(document).ready(function(){
              var date_input=$('input[name="Fecha_Desde"]'); //our date input has the name "date"
              var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
              date_input.datepicker({
                  format: 'dd/mm/yyyy',
                  container: container,
                  todayHighlight: true,
                  autoclose: true,
                  closeText: 'Cerrar', /* HASTA ACA */
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
              var date_input2=$('input[name="Fecha_Hasta"]'); //our date input has the name "date"
              var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
              date_input2.datepicker({
                  format: 'dd/mm/yyyy',
                  container: container,
                  todayHighlight: true,
                  autoclose: true,
                  closeText: 'Cerrar', /* HASTA ACA */
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
          });
   
    function buscarPersonas(){
      var xNombre = document.getElementById('SearchPersonas').value;
      var textoBusqueda = xNombre;
      xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          contenidosRecibidos = xmlhttp.responseText;
          document.getElementById("ResultadosPersonas").innerHTML=contenidosRecibidos;
          }
      }
      xmlhttp.open('POST', 'buscarPersonas.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
      xmlhttp.send();
    }

    function buscarMotivos(){
      var xMotivo = document.getElementById('SearchMotivos').value;
      var textoBusqueda = xMotivo;
      xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          contenidosRecibidos = xmlhttp.responseText;
          document.getElementById("ResultadosMotivos").innerHTML=contenidosRecibidos;
          }
      }
      xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
      xmlhttp.send();
    }

    function buscarMotivos2(){
      var xMotivo = document.getElementById('SearchMotivos2').value;
      var textoBusqueda = xMotivo;
      xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          contenidosRecibidos = xmlhttp.responseText;
          document.getElementById("ResultadosMotivos2").innerHTML=contenidosRecibidos;
          }
      }
      xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda='+textoBusqueda+'&number=2', true); // Método post y url invocada
      xmlhttp.send();
    }

    function buscarMotivos3(){
      var xMotivo = document.getElementById('SearchMotivos3').value;
      var textoBusqueda = xMotivo;
      xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          contenidosRecibidos = xmlhttp.responseText;
          document.getElementById("ResultadosMotivos3").innerHTML=contenidosRecibidos;
          }
      }
      xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda='+textoBusqueda+'&number=3', true); // Método post y url invocada
      xmlhttp.send();
    }

    function buscarMotivos4(motivoNumero){
      var xMotivo = document.getElementById('SearchMotivos' + motivoNumero).value;
      var textoBusqueda = xMotivo;
      xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          contenidosRecibidos = xmlhttp.responseText;
          document.getElementById("ResultadosMotivos" + motivoNumero).innerHTML=contenidosRecibidos;
          }
      }
      xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda='+textoBusqueda+'&number=' + motivoNumero, true); // Método post y url invocada
      xmlhttp.send();
    }

    function buscarCategorias(){
      var xCategoria = document.getElementById('SearchCategorias').value;
      var textoBusqueda = xCategoria;
      xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          contenidosRecibidos = xmlhttp.responseText;
          document.getElementById("ResultadosCategorias").innerHTML=contenidosRecibidos;
          }
      }
      xmlhttp.open('POST', 'buscarCategorias.php?valorBusqueda='+textoBusqueda, true); // Método post y url invocada
      xmlhttp.send();
    }
    
    function seleccionPersona(xNombre,xID){
      var Persona = document.getElementById("Persona");
      var ID_Persona = document.getElementById("ID_Persona");
      Persona.innerHTML = "";
      Persona.innerHTML = "<p>"+xNombre+" <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalPersona'><i class='fa fa-cog text-secondary'></i></button></p>";
      ID_Persona.setAttribute('value',xID);
      var BtnBarrios = document.getElementById("agregarBarrio");
      BtnBarrios.setAttribute('disabled', true);  
      var SelMostrar = document.getElementById("inpMostrar");
      SelMostrar.setAttribute('disabled', true);
    }

    function seleccionMotivo(xMotivo,xID,xNumber){
      if(xNumber > 1){
        var Motivo = document.getElementById("Motivo"+xNumber);
        var ID_Motivo = document.getElementById("ID_Motivo"+xNumber);
        Motivo.innerHTML = "";
        Motivo.innerHTML = "<p>"+xMotivo+" <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalMotivo"+xNumber+"'><i class='fa fa-cog text-secondary'></i></button></p>";
        ID_Motivo.setAttribute('value',xID);
      } else{
        var Motivo = document.getElementById("Motivo");
        var ID_Motivo = document.getElementById("ID_Motivo");
        Motivo.innerHTML = "";
        Motivo.innerHTML = "<p>"+xMotivo+" <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalMotivo'><i class='fa fa-cog text-secondary'></i></button></p>";
        ID_Motivo.setAttribute('value',xID);
      }
    }

    function seleccionCategoria(xCategoria,xID){
      var Categoria = document.getElementById("Categoria");
      var ID_Categoria = document.getElementById("ID_Categoria");
      Categoria.innerHTML = "";
      Categoria.innerHTML = "<p>"+xCategoria+" <button class='btn btn-sm btn-light' type='button' data-toggle='modal' data-target='#ModalCategoria'><i class='fa fa-cog text-secondary'></i></button></p>";
      ID_Categoria.setAttribute('value',xID);
    }

    function agregarBarrio(){
      cantBarrios++;
      var divContenedor = document.getElementById('contenedorBarrios');
      var divBarrio = document.createElement("div");
      divBarrio.setAttribute('class','form-group row');
      var labelBarrio = document.createElement("label");
      labelBarrio.setAttribute('class','col-md-2 col-form-label LblForm');
      labelBarrio.innerText = 'Barrio '+cantBarrios+':';
      var divSelectBarrio = document.createElement("div");
      divSelectBarrio.setAttribute('class','col-md-10');
      var select = `<?php $Element = new Elements(); echo $Element->CBRepBarrios(); ?>`;
      divSelectBarrio.innerHTML = select;      
      divBarrio.appendChild(labelBarrio);
      divBarrio.appendChild(divSelectBarrio);
      divContenedor.appendChild(divBarrio);

    }

    function resetearForm(){
      swal({
        title: "¿Está seguro?",
        text: "¿Seguro de querer resetear el formulario?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          reiniciarFormulario();
          // window.location.href = 'Controladores/DeletePersona.php?ID='+xID;
          //alert('SI');
        } else {        
        }
      });
    }

    function tomarElemento(xID){
        return document.getElementById(xID);
      }

      function crearElemento(xTipo){
        return document.createElement(xTipo);
      }

      function agregarAtributoxElemento(xElemento,xAtributo,xValue){
          xElemento.setAttribute(xAtributo,xValue);
      }

      function agregarEtiqueta(xElemento,xEtiqueta){
        xElemento.innerHTML = xEtiqueta;
      }

      function resetearValorElemento(xID){
        document.getElementById(xID).value = "";
      }

      function resetearValorSelect(xID){
        document.getElementById(xID).selectedIndex = 0;
      }

      function resetearValorDiv(xDiv){
        xDiv.innerHTML = "";
      }

      function agregarElementoxDiv(xDiv,xElemento){
        xDiv.appendChild(xElemento);
      }



      function reiniciarFormulario(){
        //RESETEANDO CAMPO FECHA
        resetearValorElemento("Fecha_Desde");        
        resetearValorElemento("Fecha_Hasta");  
        var fechaDesde = tomarElemento("Fecha_Desde");      
        var fechaHasta = tomarElemento("Fecha_Hasta");
        fechaDesde.value = "<?php echo implode("/", array_reverse(explode("-",date('Y-m-d',strtotime(date('Y-m-d')."- 1 year"))))); ?>";
        fechaHasta.value = "<?php echo implode("/", array_reverse(explode("-",date('Y-m-d')))); ?>";
        //RESETEANDO BOTON PERSONA
        var btnPersona = crearElemento("button");
        agregarAtributoxElemento(btnPersona,"type","button");
        agregarAtributoxElemento(btnPersona,"class","btn btn-lg btn-primary btn-block");
        agregarAtributoxElemento(btnPersona,"data-toggle","modal");
        agregarAtributoxElemento(btnPersona,"data-target","#ModalPersona");        
        agregarEtiqueta(btnPersona,"Seleccione una Persona");        
        var div_btnPersona = tomarElemento("Persona");
        resetearValorDiv(div_btnPersona);        
        agregarElementoxDiv(div_btnPersona,btnPersona);  
        //RESETANDO CAMPOS
        resetearValorElemento("Edad_Desde"); 
        resetearValorElemento("Edad_Hasta"); 
        resetearValorElemento("Domicilio"); 
        resetearValorElemento("manzana"); 
        resetearValorElemento("lote"); 
        resetearValorElemento("familia");       
        resetearValorElemento("Nro_Carpeta"); 
        resetearValorElemento("Nro_Legajo"); 
        resetearValorSelect("ID_Escuela");
        resetearValorElemento("Trabajo"); 
        //RESETEANDO BOTON SELECCIONE UN MOTIVO 1
        var btnMotivo_1 = crearElemento("button");
        agregarAtributoxElemento(btnMotivo_1,"type","button");
        agregarAtributoxElemento(btnMotivo_1,"class","btn btn-lg btn-primary btn-block");
        agregarAtributoxElemento(btnMotivo_1,"data-toggle","modal");
        agregarAtributoxElemento(btnMotivo_1,"data-target","#ModalMotivo");        
        agregarEtiqueta(btnMotivo_1,"Seleccione un Motivo");        
        var div_btnMotivo_1 = tomarElemento("Motivo");
        resetearValorDiv(div_btnMotivo_1);        
        agregarElementoxDiv(div_btnMotivo_1,btnMotivo_1); 
        //RESETEANDO BOTON SELECCIONE UN MOTIVO 2
        var btnMotivo_2 = crearElemento("button");
        agregarAtributoxElemento(btnMotivo_2,"type","button");
        agregarAtributoxElemento(btnMotivo_2,"class","btn btn-lg btn-primary btn-block");
        agregarAtributoxElemento(btnMotivo_2,"data-toggle","modal");
        agregarAtributoxElemento(btnMotivo_2,"data-target","#ModalMotivo2");        
        agregarEtiqueta(btnMotivo_2,"Seleccione un Motivo");        
        var div_btnMotivo_2 = tomarElemento("Motivo2");
        resetearValorDiv(div_btnMotivo_2);        
        agregarElementoxDiv(div_btnMotivo_2,btnMotivo_2);  
        //RESETEANDO BOTON SELECCIONE UN MOTIVO 3
        var btnMotivo_3 = crearElemento("button");
        agregarAtributoxElemento(btnMotivo_3,"type","button");
        agregarAtributoxElemento(btnMotivo_3,"class","btn btn-lg btn-primary btn-block");
        agregarAtributoxElemento(btnMotivo_3,"data-toggle","modal");
        agregarAtributoxElemento(btnMotivo_3,"data-target","#ModalMotivo3");        
        agregarEtiqueta(btnMotivo_3,"Seleccione un Motivo");        
        var div_btnMotivo_3 = tomarElemento("Motivo3");
        resetearValorDiv(div_btnMotivo_3);        
        agregarElementoxDiv(div_btnMotivo_3,btnMotivo_3);      
        //RESETEANDO BOTON SELECCIONE UNA MOTIVO 3
        var btnCategoria = crearElemento("button");
        agregarAtributoxElemento(btnCategoria,"type","button");
        agregarAtributoxElemento(btnCategoria,"class","btn btn-lg btn-primary btn-block");
        agregarAtributoxElemento(btnCategoria,"data-toggle","modal");
        agregarAtributoxElemento(btnCategoria,"data-target","#ModalCategoria");        
      agregarEtiqueta(btnCategoria,"Seleccione una Categoria");       
        var div_btnCategoria = tomarElemento("Categoria");
        resetearValorDiv(div_btnCategoria);        
        agregarElementoxDiv(div_btnCategoria,btnCategoria);              
        //RESETEANDO CENTRO DE SALUD
        resetearValorSelect("ID_Centro");
        //RESETEANDO OTRAS INSTITUCIONES
        resetearValorSelect("ID_OtraInstitucion");
        //RESETEANDO MOSTRAR PERSONAS
        resetearValorSelect("inpMostrar");
      }



    //################################################################

    function habilitar_seleccion(val) {
      // alert (val)
      if(val!=0){      
      
        if(val=="todos"){
          document.getElementById("div_manzana").hidden=false;
          document.getElementById("div_lote").hidden=false;
          document.getElementById("div_familia").hidden=false;            
        }
        else if(val=="manzana"){
          document.getElementById("div_manzana").hidden=false;  
          document.getElementById("div_lote").hidden=true;  
          document.getElementById("div_familia").hidden=true;   
        }
        else if(val=="lote"){
          document.getElementById("div_manzana").hidden=true;  
            document.getElementById("div_lote").hidden=false;  
           document.getElementById("div_familia").hidden=true;   
        
        }    
        else{
          document.getElementById("div_manzana").hidden=true;  
          document.getElementById("div_lote").hidden=true;  
         document.getElementById("div_familia").hidden=false;   
        }                             
      }

    }

      function habilitarMeses(xElemento){
        let edadHasta = $("#Edad_Hasta");
        let mesesDesde = $("#Meses_Desde");
        let mesesHasta = $("#Meses_Hasta");
        let valueElem = xElemento.value;
        let idInput = xElemento.id;
        if (idInput == "Edad_Desde") {
          if (valueElem === "") {
            mesesDesde.prop("readonly", false);
            mesesDesde.val("");
            mesesHasta.prop("readonly", false);
            edadHasta.prop("readonly", false);
          } else {
            mesesDesde.prop("readonly", true);
            edadHasta.prop("readonly", false);
            mesesDesde.val("0");
          }
        }
      }

      function habilitarEdad(xElemento){
        let edadHasta = $("#Edad_Hasta");
        let valueElem = xElemento.value;
        let idInput = xElemento.id;
        if (idInput == "Meses_Desde") {
          if (valueElem === "") {
            edadHasta.prop("readonly", false);
            edadHasta.val("");
          } else {
            edadHasta.prop('readonly', true);
            edadHasta.val("");
          }
        }
      }

      function agregarMotivo(){
      if (cantMotivos <= 4) {
        cantMotivos++;
        var divContenedor = document.getElementById('contenedorMotivos');
        var divMotivo = document.createElement("div");
        divMotivo.setAttribute('class','form-group row');
        var labelMotivo = document.createElement("label");
        labelMotivo.setAttribute('class','col-md-2 col-form-label LblForm');
        labelMotivo.innerText = 'Motivo '+ cantMotivos +':';
        var divBotonMotivo = document.createElement("div");
        divBotonMotivo.setAttribute("id", "Motivo" + cantMotivos);
        divBotonMotivo.setAttribute('class','col-md-10');
        var boton = "<button type = 'button' class = 'btn btn-lg btn-primary btn-block' data-toggle='modal' data-target='#ModalMotivo" + cantMotivos + "'>Seleccione un Motivo</button>";
        divBotonMotivo.innerHTML = boton;      
        divMotivo.appendChild(labelMotivo);
        divMotivo.appendChild(divBotonMotivo);
        divContenedor.appendChild(divMotivo);
        var divInputsGenerales = document.getElementById('InputsGenerales');
        var divInput = document.createElement("input");
        divInput.setAttribute("id", "ID_Motivo" + cantMotivos);
        divInput.setAttribute("name", "ID_Motivo" + cantMotivos);
        divInput.setAttribute("type", "hidden");
        divInputsGenerales.appendChild(divInput);
      }
    }

  </script>
</head>
<body>
<div class = "row">
<?php
  $Element = new Elements();
  echo $Element->menuDeNavegacion($TipoUsuario, $ID_Usuario, $Element::PAGINA_REPORTE_GRAFICO);
  ?>
  <div class = "col-md-9">
    <div class="row">
      <div class="col"></div>
      <div class="col-10 Titulo">
        <p>Reporte gráfico</p>
      </div>
      <div class="col"></div>
    </div><br>
    <br>
     <div class = "row">
      <div class = "col-10">
          <!-- Carga -->
          <p class = "Titulos">Parámetros</p>
          <form method = "post" onKeydown="return event.key != 'Enter';" action = "view_rep_general_new.php" onSubmit = "return ValidarGeneral();">
            <div class="form-group row">
                <label for="inputPassword" class="col-md-2 col-form-label LblForm">Fecha desde *: </label>
                <div class="col-md-10">
                    <input type="text" name="Fecha_Desde" id = "Fecha_Desde" class="form-control" autocomplete="off" value = "<?php echo implode("/", array_reverse(explode("-",date('Y-m-d',strtotime(date('Y-m-d')."- 1 year"))))); ?>">
                </div>
            </div> 
            <div class="form-group row">
                <label for="inputPassword" class="col-md-2 col-form-label LblForm">Fecha hasta *: </label>
                <div class="col-md-10">
                    <input type="text" name="Fecha_Hasta" id = "Fecha_Hasta" class="form-control" autocomplete="off" value = "<?php echo implode("/", array_reverse(explode("-",date('Y-m-d')))); ?>">
                </div>
            </div>
            <div class="form-group row" style="margin-bottom: 0.6rem;">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Persona: </label>
              <div class="col-md-10" id = "Persona">
              	 	<button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalPersona">Seleccione una Persona</button>                  
              </div>
            </div>
            <div class="row LblForm col-md-2" style="margin-bottom: 1.04%; font-size: 1.031rem">
              Edad <br>
            </div>
            <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Desde (años): </label>
                  <div class="col-md-10">
                      <input type="number" name="Edad_Desde" id="Edad_Desde" class="form-control" autocomplete="off" placeholder="Sólo Números" min="0" onkeyup="habilitarMeses(this)">
                      <input type="hidden" name="ID_Persona" id = "ID_Persona" value = "0">
                  </div>
            </div> 
            <div class="form-group row">
                <label for="inputPassword" class="col-md-2 col-form-label LblForm">Hasta (años): </label>
                <div class="col-md-10">
                    <input type="number" name="Edad_Hasta" id="Edad_Hasta" class="form-control" autocomplete="off" placeholder="Sólo Números" min="0" onkeyup="habilitarMeses(this)">
                </div>
            </div> 
            <div class="form-group row">
                  <label for="inputPassword" class="col-md-2 col-form-label LblForm">Desde (Meses): </label>
                  <div class="col-md-10">
                      <input type="number" name="Meses_Desde" id="Meses_Desde" class="form-control" autocomplete="off" placeholder="Sólo Números" min="0" onkeyup="habilitarEdad(this)">
                  </div>
            </div> 
            <div class="form-group row">
                <label for="inputPassword" class="col-md-2 col-form-label LblForm">Hasta (Meses): </label>
                <div class="col-md-10">
                    <input type="number" name="Meses_Hasta" id="Meses_Hasta" class="form-control" autocomplete="off" placeholder="Sólo Números" min="0" max="11">
                </div>
            </div> 
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Barrio: </label>
              <div class="col-md-9">
                <?php  
                $Element = new Elements();
                echo $Element->CBRepBarrios();
                ?>
              </div>
              <div class="col-md-1">
                  <button type="button" class="btn btn-primary" onClick="agregarBarrio()" id="agregarBarrioID">+</button>
              </div>
            </div>
            <div id="contenedorBarrios">              
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Domicilio/Familia: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Domicilio" id="Domicilio" autocomplete="off">
              </div>
            </div>
            <!-- ################################################################################ -->
            <!--
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Maz./lote/sub-lote: </label>
              <div class="col-md-10">
                <select class="form-control" name="cmb_seleccion" id="cmb_seleccion"  onchange="habilitar_seleccion(this.value)">
                  <option value="0">seleccionar</option>
                  <option value="manzana">Manzana</option>
                  <option value="lote">Lote</option>
                  <option value="familia">Sub-lote</option>
                  <option value="todos">Todos</option>
                </select>
              </div>
            </div>
            -->
            <div class="form-group row" id="div_manzana">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Manzana: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Manzana" id="manzana" autocomplete="off">
              </div>
            </div>
            <div class="form-group row" id="div_lote">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Lote: </label>
              <div class="col-md-10">
                <input type="number" class="form-control" name = "Lote" id="lote" autocomplete="off">
              </div>
            </div>
            <div class="form-group row" id="div_familia">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Sub-lote: </label>
              <div class="col-md-10">
                <input type="number" class="form-control" name = "Familia" id="familia" autocomplete="off">
              </div>
            </div>

<!-- ################################################################################ -->
            <!--<div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Trabajo: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Trabajo" id="Trabajo" autocomplete="off">
              </div>
            </div>-->
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Categoría: </label>
              <div class="col-md-10" id = "Categoria">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalCategoria">Seleccione una Categoria</button>  
              </div>
            </div>

            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 1: </label>
              <div class="col-md-10" id = "Motivo">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo">Seleccione un Motivo</button>   
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 2: </label>
              <div class="col-md-10" id = "Motivo2">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo2">Seleccione un Motivo</button>   
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Motivo 3: </label>
              <div class="col-md-9" id = "Motivo3">
                <button type = "button" class = "btn btn-lg btn-primary btn-block" data-toggle="modal" data-target="#ModalMotivo3">Seleccione un Motivo</button>   
              </div>
              <div class="col-md-1">
                  <button type="button" class="btn btn-primary" onClick="agregarMotivo()" id="agregarMotivoID">+</button>
              </div>
            </div>
            <div id="contenedorMotivos">              
            </div>

            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Centro Salud: </label>
              <div class="col-md-10">
                <?php  
                $Element = new Elements();
                echo $Element->CBRepCentros();
                ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Nro. Carpeta: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Nro_Carpeta" id="Nro_Carpeta" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Nro. Legajo: </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name = "Nro_Legajo" id="Nro_Legajo" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Otras Instituciones: </label>
              <div class="col-md-10">
                <?php  
                $Element = new Elements();
                echo $Element->CBRepOtrasInstituciones();
                ?>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Escuela: </label>
              <div class="col-md-10">
                <?php  
                $Element = new Elements();
                echo $Element->CBRepEscuelas();
                ?>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Responsable: </label>
              <div class="col-md-10">
                <?php  
                $Element = new Elements();
                echo $Element->CBRepResponsable();
                ?>
              </div>
            </div>
            <!--
            <div class="form-group row">
              <label for="inputPassword" class="col-md-2 col-form-label LblForm">Mostrar Personas: </label>
              <div class="col-md-10">
                <select class="form-control" name="Mostrar" id="inpMostrar">
                	<option value="0">Con Movimientos</option>
                	<option value="1">Todos</option>
                </select>
              </div>
            </div> -->
            <div class="form-group row">
              <div class="offset-md-2 col-md-10">
                <div class="offset-md-2 col-md-10" id = "InputsGenerales">
                    <input type="hidden" name="ID_Motivo" id = "ID_Motivo" value = "0">
                    <input type="hidden" name="ID_Motivo2" id = "ID_Motivo2" value = "0">
                    <input type="hidden" name="ID_Motivo3" id = "ID_Motivo3" value = "0">
                    <input type="hidden" name="ID_Categoria" id = "ID_Categoria" value = "0">
                    <button type="submit" class="btn btn-outline-success">Aceptar</button>
                    <button type="button" class="btn btn-outline-secondary" onClick="resetearForm()">Cancel</button>
                    <button type = "button" class = "btn btn-outline-secondary" onClick = "location.href = 'view_inicio.php'">Volver</button>
                  </div>
              </div>
            </div>
          </form>
          <div class="row">
              <div class="col-10"></div>
              <!-- <div class="col-2">
                <button type = "button" class = "btn btn-outline-secondary" onClick = "location.href = 'view_inicio.php'">Volver</button>
              </div> -->
          </div>
          <br><br><br>
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
				        			<input class = "form-control" type="text" name="BuscarPersona" id = "SearchPersonas" autofocus onKeyUp="buscarPersonas()" autocomplete="off" placeholder="Ingrese el nombre, apellido, documento o legajo">
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
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                      <input class = "form-control" type="text" name="BuscarMotivos" id = "SearchMotivos" onKeyUp="buscarMotivos()" autocomplete="off">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">Buscar</span>
                      </div>  
                    </div>                    
                  </div>
                  <div class="col"></div>
                </div>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10" id = "ResultadosMotivos">
                    
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
      <!-- Modal SELECCION MOTIVO 2 -->
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                      <input class = "form-control" type="text" name="BuscarMotivos2" id = "SearchMotivos2" onKeyUp="buscarMotivos2()" autocomplete="off">
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
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
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
                      <input class = "form-control" type="text" name="BuscarMotivos3" id = "SearchMotivos3" onKeyUp="buscarMotivos3()" autocomplete="off">
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
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo4" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                      <input class = "form-control" type="text" name="BuscarMotivos4" id = "SearchMotivos4" onKeyUp="buscarMotivos4(4)" autocomplete="off">
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
      <div class="modal fade bd-example-modal-lg" id="ModalMotivo5" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                      <input class = "form-control" type="text" name="BuscarMotivos5" id = "SearchMotivos5" onKeyUp="buscarMotivos4(5)" autocomplete="off">
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
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
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
                      <input class = "form-control" type="text" name="BuscarCategorias" id = "SearchCategorias" onKeyUp="buscarCategorias()" autocomplete="off">
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
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>             
            </div>
          </div>
        </div>
      </div>
      <!-- FIN MODAL SELECCION CATEGORIA -->
          <!-- FIN SECCION DE MODALES -->
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