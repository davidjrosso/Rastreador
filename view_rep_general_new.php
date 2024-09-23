<?php
session_start();
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
require_once "Controladores/Conexion.php";
require_once "Modelo/Persona.php";
require_once "sys_config.php";
require_once 'dompdf/autoload.inc.php';

header("Content-Type: text/html;charset=utf-8");


/*     CONTROL DE USUARIOS                    */
if (!isset($_SESSION["Usuario"])) {
  header("Location: Error_Session.php");
}

$Con = new Conexion();
$Con->OpenConexion();
$ID_Usuario = $_SESSION["Usuario"];
$ConsultarTipoUsuario = "select ID_TipoUsuario from accounts where accountid = $ID_Usuario";
$MensajeErrorConsultarTipoUsuario = "No se pudo consultar el Tipo de Usuario";
$EjecutarConsultarTipoUsuario = mysqli_query($Con->Conexion, $ConsultarTipoUsuario) or die($MensajeErrorConsultarTipoUsuario);
$Ret = mysqli_fetch_assoc($EjecutarConsultarTipoUsuario);
$TipoUsuario = $Ret["ID_TipoUsuario"];
$_SESSION["reporte_grafico"] = true;
$Con->CloseConexion();

?>
<!DOCTYPE html>
<html>

<head>
  <title>Rastreador III</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <!--<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->
  <link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />

  <script src="js/FileSaver.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <!--<script type="text/javascript" src = "js/Funciones.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
  <script src="js/acciones-reporte-grafico.js"></script>
  <script src="js/jquery.wordexport.js"></script>
  <script src="html2pdf.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/pdf-lib/dist/pdf-lib.js"></script>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <script src="https://cdn.jsdelivr.net/npm/ol@v10.1.0/dist/ol.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.1.0/ol.css">

  <script src="js/OpenLayers.js"></script>

  <script src="https://www.lactame.com/lib/image-js/0.21.2/image.min.js"></script>

  <script>
    const { PDFDocument, StandardFonts, rgb } = PDFLib

    function mostrar() {

      $("#expandir").removeAttr("style");
      document.getElementById("expandir").hidden = true;
      document.getElementById("ContenidoMenu").hidden = false;
      $("#ContenerdorPrincipal").removeAttr("style");

      var ContenidoMenu = document.getElementById("ContenidoMenu");
      ContenidoMenu.setAttribute("class", "col-md-2");
      document.getElementById("sidebar").style.width = "200px";

      var ContenidoTabla = document.getElementById("ContenidoTabla");
      ContenidoTabla.setAttribute("class", "col-md-10");

      document.getElementById("abrir").style.display = "none";
      document.getElementById("cerrar").style.display = "inline";
      $("#BarraDeNavHTabla").removeAttr("style");
    }

    function ocultar() {
      document.getElementById("expandir").hidden = false;
      document.getElementById("ContenidoMenu").hidden = true;
      $("#ContenerdorPrincipal").attr("style","margin-left:5px;");
      // var ContenidoMenu = document.getElementById("ContenidoMenu");
      // ContenidoMenu.setAttribute("class","col-md-1");
      // document.getElementById("sidebar").style.width = "3%"; //5


      //document.getElementById("ContenidoTabla").style.marginLeft = "0";
      var ContenidoTabla = document.getElementById("ContenidoTabla");
      ContenidoTabla.setAttribute("class", "col-md-12");

      $("#expandir").attr("style","padding-left:1px");
      $("#abrir").attr("style","display:inline;");
      //document.getElementById("abrir").style.display = "inline";
      document.getElementById("cerrar").style.display = "none";
      $("#BarraDeNavHTabla").attr("style","width: 95%; margin-left: 2%;");
    }

    function init() {
      if (map === null) {
        map = new OpenLayers.Map("basicMap");
        let mapnik = new OpenLayers.Layer.OSM();
        var fromProjection =  new OpenLayers.Projection("EPSG:3857");
        var toProjection = new OpenLayers.Projection("EPSG:4326");
        let position = new OpenLayers.LonLat(-64.11844, -32.17022).transform(toProjection, fromProjection);
        let zoom = 15;
        let positionFormas = null;
        let icon = null;
        let charCodeLetter = null;
        map.addLayer(mapnik);
        var markers = new OpenLayers.Layer.Markers( "Markers" );
        map.addLayer(markers);
        let popup = null;
        let size = new OpenLayers.Size(10,12);
        let offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
        let control = new OpenLayers.Control();
        OpenLayers.Util.extend(control, {
          draw: function () {
              this.box = new OpenLayers.Handler.Box( control,
                  {"done": this.notice},
                  {keyMask: OpenLayers.Handler.MOD_SHIFT});
              this.box.activate();
          },
          notice: function (bounds) {
              OpenLayers.Console.userError(bounds);
          }
        });
        map.addControl(control);
        map.addControl(new OpenLayers.Control.PanZoomBar());

        let markerClick = function(evt) {
            if (this.popup == null) {
                this.popup = this.createPopup(this.closeBox);
                map.addPopup(this.popup);
                this.popup.show();
            } else {
                this.popup.toggle();
            }
            OpenLayers.Event.stop(evt);
        };

        objectJsonTabla.forEach(function (elemento, indice, array) {
          pos = new OpenLayers.LonLat(elemento.lon, elemento.lat).transform(toProjection, fromProjection);
          positionFormas = pos;
          if (elemento.lista_formas_categorias) {
            let angulo = 360;
            let puntos = angulo / (Object.keys(elemento.lista_formas_categorias).length + 1);
            Object.keys(elemento.lista_formas_categorias).forEach(function (categoria, indice, array) {
                  charCodeLetter = (categoria.length == 1) ? categoria.charCodeAt(0) : categoria;
                  let color_categ = elemento.lista_formas_categorias[categoria].substring(1);
                  icon = new OpenLayers.Icon('./images/icons/motivos/' + charCodeLetter + '_' + color_categ + '.png', size, offset);
                  let marker = new OpenLayers.Marker(positionFormas, icon.clone());
                  markers.addMarker(marker);
                  positionFormas = positionFormas.add(Math.sin((indice + 1) * puntos) * 22, Math.cos((indice + 1) * puntos) * 22);
                  let feature = new OpenLayers.Feature(markers, positionFormas);
                  feature.closeBox = true;
                  feature.data.overflow = "hidden";
                  feature.data.popupContentHTML = ` <div style="display: inline-block; width: 80%; text-align: center;">
                                                      Detalles 
                                                    </div>
                                                    <button type="button" class="btn-close" aria-label="Close" style="border-radius: 25px; background-color: #2e353d; color: white; margin: 2% 2% 0 4%"  onclick="onClickOcultarPopup(this);"></button>
                                                    <div style='margin: 0 2% 1% 2%; height: 84%'>
                                                      <table style='text-align: center; color: black;  table-layout: fixed; width: 100%; height: 97%'>
                                                        <thead>
                                                          <tr style='color: black; background-color: white'>
                                                            <th style='background-color: white'></th>
                                                            <th style='background-color: white'></th>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                          <tr style='color: black;'>
                                                            <td>
                                                              Persona
                                                            </td>
                                                            <td>
                                                              <a href='javascript:window.open("view_modpersonas.php?ID=${elemento.id_persona}","Ventana${elemento.id_persona}" ,"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no")' target='_top' rel='noopener noreferrer'>
                                                                ${elemento.persona}
                                                            </td>
                                                          </tr>
                                                          <tr style='color: black;'>
                                                            <td>Años</td>
                                                            <td>${elemento.edad}</td>
                                                          </tr>
                                                          <tr style='color: black;'>
                                                            <td>Meses</td>
                                                            <td>${elemento.meses}</td>
                                                          </tr>
                                                          <tr style='color: black;'>
                                                            <td>Fech. Nac.</td>
                                                            <td>${elemento.fechanac}</td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </div>`;
                  marker.feature = feature;
                  //marker.events.register("mousedown", feature, markerClick);
                  marker.events.register("mousedown", feature, function() {
                      window.open("view_modpersonas.php?ID=" + elemento.id_persona,"Ventana" + elemento.id_persona ,"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no")
                  });
                  markers.addMarker(marker);
              });
            }
        });
        map.setCenter(position, zoom);
      }
    }

    var map = null;
    var nroFilasTabla = 0;
    var nroColumnasTabla = 0;
    var currCell = null;
    var editing = false;
    //var columnaIndice = 5;
    //var columnaIndice = 8;
    var columnaIndice = 10;
    var filaIndice = 1;
    var valInputRangePrev = columnaIndice;
    var focusBarraNavegacionH = false;
    var timeout = null;
    var rowsRequest = {};
    let listaDeRequest = new Array();
    let listaDePdf = new Array();
    let documentoPdf = PDFDocument.create()
    let nroPaginaPdf = 0;
    let nroPaginaGeneradas = 0;
    let thTable = null;

    $(document).on("keydown", function(e) {
      NavegacionConTeclado(e);
    });

    $(document).on("ready", function(e) {
      nroFilasTabla = $("#tablaMovimientos tbody > tr").length - 2;
      nroColumnasTabla = $("thead > tr > th").length - 2;
      let nroPag = (nroFilasTabla + 2) / 10;
      let floorPag = Math.floor((nroFilasTabla + 2) / 10);
      nroPaginaPdf = (nroPag > floorPag) ? (floorPag + 1) : floorPag;
      thTable = $("thead > tr > th");
      DesaplazamientoIniciaDeTabla();

      $("#input-zoom").on("input", function(e) {
        toggleZoom($('#input-zoom').prop("value"));
      });

      $("#zoomIncrementar").on("mousedown", function(e) {
        timeout = setInterval(function(){
          $('#input-zoom')[0].stepUp();
          toggleZoom($('#input-zoom').prop("value"));
        }, 37);
      });

      $("#zoomIncrementar").on("mouseup", function(e) {
        clearInterval(timeout);       
      });

      $("#zoomDecrementar").on("mousedown", function(e) {
        timeout = setInterval(function(){
          $('#input-zoom')[0].stepDown();
          toggleZoom($('#input-zoom').prop("value"));
        }, 37);        
      });

      $("#zoomDecrementar").on("mouseup", function(e) {
        clearInterval(timeout);
      });

      $("#BarraDeNavHTabla").attr("max", nroColumnasTabla + 1);

      $("#BarraDeNavHTabla").on("mousedown", function(e) {
        focusBarraNavegacionH = true;
      });

      $("#BarraDeNavHTabla").on("input", function(e) {
        //if (e.originalEvent.detail){
        if (focusBarraNavegacionH){
          navegacionConBarHNav(e);
        }
        //}
      });
      $("#BarraDeNavVTabla").on("input", function(e) {
        navegacionConBarVNav(e);
      });
      $("#BarraDeNavHTabla").on("mouseup", function(e) {
        focusBarraNavegacionH = false;
        actualizacionDePosicionBarraDenavegacionH(e, $(this).attr("value"));
      });
      $('thead tr >*').on("transitionstart", function(e) {
        var columnaRemoverClass = $("tbody tr > *:nth-child("+ (this.cellIndex + 1) +")[class ~='hiddenColTablaAnimacion'] div div");
        columnaRemoverClass.removeClass( "itemMotivoAccesible");
      });
      $('thead tr >*').on("transitionend", function(e) {
        var columnaRemoverClass = $("tbody tr > *:nth-child("+ (this.cellIndex + 1) +")[class ~='showColTablaAnimacion'] div div");
        columnaRemoverClass.addClass( "itemMotivoAccesible");
        columnaRemoverClass.removeClass( "showColTablaAnimacionfire");
        columnaRemoverClass.removeClass( "showColTablaAnimacion");
      });
      $("#map-modal").on("transitionend", function(e){
        if (!map) init();
      })
    });

    function fireKey(el) {
        var key = el;
        if(document.createEventObject)
        {
            var eventObj = document.createEventObject();
            eventObj.keyCode = key;
            el.fireEvent("onkeydown", eventObj);   
        } else if(document.createEvent)
        {
            var eventObj = document.createEvent("Events");
            eventObj.initEvent("keydown", true, true);
            eventObj.which = key;
            el.dispatchEvent(eventObj);
        }
    }

    function actualizacionDePosicionBarraDenavegacionH(e, element){
      var value = $("#BarraDeNavHTabla").val();
      var columnaActual = columnaIndice;
      if(value < (columnaIndice - 0.5)){
        columnaIndice--;
        headABorrar = $('thead tr > *:nth-child('+columnaIndice+')');
        columnaABorrar = $('tbody tr > *:nth-child('+columnaIndice+')');
        columnaABorrar.show();
        headABorrar.show();
        columnaABorrar.removeClass( "hiddenColTablaAnimacion");
        headABorrar.addClass( "hiddenColTablaAnimacion");
        columnaABorrar.removeClass( "hiddenColTablaAnimacionfire");
        headABorrar.removeClass( "hiddenColTablaAnimacionfire");
        columnaABorrar.addClass( "showColTablaAnimacion");
        headABorrar.addClass( "showColTablaAnimacion");
        columnaABorrar.addClass( "showColTablaAnimacionfire");
        headABorrar.addClass( "showColTablaAnimacionfire");
      } else if (value > (columnaIndice + 0.5)){
        headABorrar = $('thead tr > *:nth-child('+columnaIndice+')');
        columnaABorrar = $('tbody tr > *:nth-child('+columnaIndice+')');
        columnaABorrar.removeClass( "showColTablaAnimacion");
        headABorrar.removeClass( "showColTablaAnimacion");
        columnaABorrar.removeClass( "showColTablaAnimacionfire");
        headABorrar.removeClass( "showColTablaAnimacionfire");
        columnaABorrar.addClass( "hiddenColTablaAnimacion");
        headABorrar.addClass( "hiddenColTablaAnimacion");
        columnaABorrar.addClass( "hiddenColTablaAnimacionfire");
        headABorrar.addClass( "hiddenColTablaAnimacionfire");
        columnaIndice++;
      } else if(((columnaIndice - 0.5) < value) &&  (value < columnaIndice)){
        headABorrar = $('thead tr > *:nth-child('+(columnaIndice - 1) + ')');
        columnaABorrar = $('tbody tr > *:nth-child('+(columnaIndice - 1) + ')');
        columnaABorrar.removeClass( "showColTablaAnimacion");
        headABorrar.removeClass( "showColTablaAnimacion");
        columnaABorrar.removeClass( "showColTablaAnimacionfire");
        headABorrar.removeClass( "showColTablaAnimacionfire");
        columnaABorrar.addClass( "hiddenColTablaAnimacion");
        headABorrar.addClass( "hiddenColTablaAnimacion");
        columnaABorrar.addClass( "hiddenColTablaAnimacionfire");
        headABorrar.addClass( "hiddenColTablaAnimacionfire");
      } else if(( value < (columnaIndice + 0.5)) &&  (columnaIndice < value)){
        headABorrar = $('thead tr > *:nth-child('+columnaIndice+')');
        columnaABorrar = $('tbody tr > *:nth-child('+columnaIndice+')');
        columnaABorrar.show();
        headABorrar.show();
        columnaABorrar.removeClass( "hiddenColTablaAnimacion");
        headABorrar.addClass( "hiddenColTablaAnimacion");
        columnaABorrar.removeClass( "hiddenColTablaAnimacionfire");
        headABorrar.removeClass( "hiddenColTablaAnimacionfire");
        columnaABorrar.addClass( "showColTablaAnimacion");
        headABorrar.addClass( "showColTablaAnimacion");
        columnaABorrar.addClass( "showColTablaAnimacionfire");
        headABorrar.addClass( "showColTablaAnimacionfire");
      }

      if(Math.round(value) == Math.floor(value)){
        $("#BarraDeNavHTabla").val(Math.floor(value));
      } else {
        $("#BarraDeNavHTabla").val(Math.round(value));
      }
    }

    function navegacionConBarHNav(e){
      var value = $("#BarraDeNavHTabla").val();
      var movDecrec = (value < valInputRangePrev);
      var columnaActual = columnaIndice;
      if(columnaIndice + 1 <= value && (Math.floor(valInputRangePrev) == columnaIndice)){
        columnaActual = columnaIndice;
      } else if(value < columnaIndice && columnaIndice < valInputRangePrev){
        columnaActual = columnaIndice;
      } else {
        if(value < columnaIndice){
          columnaIndice--;
          columnaActual = columnaIndice;
        } else {
          columnaActual = (value < columnaIndice)? Math.floor(value): columnaIndice;
        }
      }
      valInputRangePrev = value;

      var margin = Math.abs(value - columnaActual);
      var width = 190;
      var updateMarginLeft =  "-" + margin*width + "px";
      if(columnaIndice + 1 <= value){
        updateMarginLeft =  "-190px";
      } else if(value < columnaIndice){
        updateMarginLeft =  "0px";
      }

      $("#BarraDeNavHTabla").attr("value", columnaIndice);
      headABorrar = $('thead tr > *:nth-child('+columnaActual+')');
      columnaABorrar = $('tbody tr > *:nth-child('+columnaActual+')');
      divABorrar = $('tbody tr > *:nth-child('+columnaActual+') div div');
      columnaABorrar.removeClass( "showColTablaAnimacion");
      columnaABorrar.removeClass( "showColTablaAnimacionfire");
      columnaABorrar.find("div div").removeClass( "itemMotivoAccesible");
      columnaABorrar.removeClass( "hiddenColTablaAnimacion");
      columnaABorrar.removeClass( "hiddenColTablaAnimacionfire");
      headABorrar.removeClass( "showColTablaAnimacion");
      headABorrar.removeClass( "showColTablaAnimacionfire");
      headABorrar.removeClass( "hiddenColTablaAnimacion");
      headABorrar.removeClass( "hiddenColTablaAnimacionfire");
      divABorrar.css("z-index", ((value <= columnaIndice) ? "300" : "-1"));
      columnaABorrar.css({
        "margin-left": updateMarginLeft,
        "border-right-width": ((value < columnaIndice) ? "1px" : "0px"),
        "border-left-width": ((value < columnaIndice) ? "1px" : "0px")
      });
      headABorrar.css("margin-left", updateMarginLeft);
      if(columnaIndice + 1 <= value){
        columnaIndice++;
      }
      $("#BarraDeNavHTabla").attr("value", columnaIndice);
    }


    function NavegacionConTeclado(e) {
        var columnaABorrar = null;
        var headABorrar = null;
        var filaABorrar = null;
        $("#BarraDeNavHTabla").attr("max", nroColumnasTabla + 1);
        $("#BarraDeNavHTabla").attr("value", columnaIndice);
        var tabla = $("table");
        tabla.scrollLeft(0);
        if (e.which == 39) {
            //right Arrow
            columnaABorrar = $('tbody tr > *:nth-child('+columnaIndice+')');
            headABorrar = $('thead tr > *:nth-child('+columnaIndice+')');
            divABorrar = $('tbody tr > *:nth-child('+columnaIndice+') div div');
            if(columnaIndice <= nroColumnasTabla){
              if(columnaIndice <= nroColumnasTabla){
                columnaABorrar.removeClass( "showColTablaAnimacion");
                headABorrar.removeClass( "showColTablaAnimacion");
                columnaABorrar.removeClass( "showColTablaAnimacionfire");
                headABorrar.removeClass( "showColTablaAnimacionfire");

                columnaABorrar.css({
                  "margin-left" : "",
                  "border-right-width" : "",
                  "border-left-width" : ""
                });
                divABorrar.css("z-index", "");

                columnaABorrar.addClass( "hiddenColTablaAnimacion");
                headABorrar.addClass( "hiddenColTablaAnimacion");
                columnaABorrar.addClass( "hiddenColTablaAnimacionfire");
                headABorrar.addClass( "hiddenColTablaAnimacionfire");
                columnaIndice++;
              }
            }
            //$("#BarraDeNavHTabla").attr("value", columnaIndice);
            document.getElementById("BarraDeNavHTabla").value = columnaIndice;
        } else if (e.which == 37) {
            // Left Arrow
            headABorrar = $('thead tr >*:nth-child('+(columnaIndice - 1 )+ ')');
            columnaABorrar = $('tbody tr > *:nth-child('+(columnaIndice - 1 )+')');
            if(columnaIndice >= 5){
              if(columnaIndice > 5){
                columnaIndice--;
                columnaABorrar.show();
                headABorrar.show();
                columnaABorrar.removeClass( "hiddenColTablaAnimacion");
                headABorrar.removeClass( "hiddenColTablaAnimacion");
                columnaABorrar.removeClass( "hiddenColTablaAnimacionfire");
                headABorrar.removeClass( "hiddenColTablaAnimacionfire");
                columnaABorrar.css({
                  "margin-left" : "",
                  "border-right-width" : "",
                  "border-left-width" : ""
                });
                //divABorrar.css("z-index", "");
                columnaABorrar.addClass( "showColTablaAnimacion");
                headABorrar.addClass( "showColTablaAnimacion");
                columnaABorrar.addClass( "showColTablaAnimacionfire");
                headABorrar.addClass( "showColTablaAnimacionfire");

              } else if (columnaIndice == 5){
                headABorrar.show();
                columnaABorrar.show();
                columnaABorrar.removeClass( "hiddenColTablaAnimacion");
                headABorrar.removeClass( "hiddenColTablaAnimacion");
                columnaABorrar.removeClass( "hiddenColTablaAnimacionfire");
                headABorrar.removeClass( "hiddenColTablaAnimacionfire");
                columnaABorrar.addClass( "showColTablaAnimacion");
                headABorrar.addClass( "showColTablaAnimacion");
                columnaABorrar.addClass( "showColTablaAnimacionfire");
                headABorrar.addClass( "showColTablaAnimacionfire");
              }
            }
            //$("#BarraDeNavHTabla").attr("value", columnaIndice);
            document.getElementById("BarraDeNavHTabla").value = columnaIndice;
        } /*else if (e.which == 38) {
            // Up Arrow
            filaABorrar = $('tbody tr:nth-child('+filaIndice+')');
            if(filaIndice > 2){
              filaIndice--;
              filaABorrar.show();
            } else if(filaIndice = 2){
              filaABorrar.show();
            }
            tabla.scrollTop(0);
            //$("#BarraDeNavVTabla").attr("value", filaIndice);
            document.getElementById("BarraDeNavVTabla").value = filaIndice;
        } else if (e.which == 40) {
            // Down Arrow
            filaABorrar = $('tbody tr:nth-child('+filaIndice+')');
            if(filaIndice <= nroFilasTabla){
              if(filaIndice < nroFilasTabla){
                filaABorrar.hide();
                filaIndice++;
              } else if(filaIndice = nroFilasTabla){
                filaABorrar.hide();
              }
            }
            tabla.scrollTop(0);
            //$("#BarraDeNavVTabla").attr("value", filaIndice);
            document.getElementById("BarraDeNavVTabla").value = filaIndice;
        }*/
        tabla.scrollLeft(0);
    }

    function DesaplazamientoIniciaDeTabla() {
        var columnaABorrar = null;
        var headABorrar = null;
        var filaABorrar = null;
        $("#BarraDeNavHTabla").attr("max", nroColumnasTabla + 1);
        $("#BarraDeNavHTabla").attr("value", columnaIndice);
        var tabla = $("table");
        tabla.scrollLeft(0);
        for (var i = 5; i<= (nroColumnasTabla - 1); i++) {
            columnaABorrar = $('tbody tr > *:nth-child('+columnaIndice+')');
            headABorrar = $('thead tr > *:nth-child('+columnaIndice+')');
            divABorrar = $('tbody tr > *:nth-child('+columnaIndice+') div div');
            if(columnaIndice <= nroColumnasTabla){
              columnaABorrar.removeClass(
                "showColTablaAnimacion showColTablaAnimacionfire"
              );
              headABorrar.removeClass(
                "showColTablaAnimacion showColTablaAnimacionfire"
              );

              columnaABorrar.css({
                "margin-left" : " -300px",
                "border-right-width" : "0px",
                "border-left-width" : "0px"
              });
              divABorrar.css("z-index", "-1");
              headABorrar.css("margin-left"," -300px");
              columnaIndice++;
            }
            document.getElementById("BarraDeNavHTabla").value = columnaIndice;
        }
        tabla.scrollLeft(0);
    }

    function navegacionConBarVNav(e){
      var value = parseInt(e.target.value);
      var nroFilasTabla = $("tbody > tr").length - 4;
      var filaABorrar = null;
      document.getElementById("BarraDeNavVTabla").value = filaIndice;
      $("#BarraDeNavVTabla").attr("max", nroFilasTabla);
      $("#BarraDeNavVTabla").attr("value", filaIndice);
      if(value < filaIndice){
        filaIndice--;
        filaABorrar = $('tbody tr:nth-child('+filaIndice+')');
        filaABorrar.show();
      } else if (value > filaIndice){
        filaABorrar = $('tbody tr:nth-child('+filaIndice+')');
        filaABorrar.hide();
        filaIndice++;
      }
      $("#BarraDeNavVTabla").attr("value", filaIndice);
    }
  </script>
  <style>
    #basicMap {
          width: 100%;
          height: 100%;
          margin: 0;
    }
    body {
      overflow-x: hidden;
    }

    #ContenidoTabla{
      padding-left: 0px;
    }

    div {
      user-select:none;
    }

    input[type="range"]{
      width: 80%;
      height:1.3rem;
      margin-left: 17%;
      opacity: 70%;
    }

    input[type="range"]::-webkit-slider-runnable-track {
      background-color: #add8e6;
      border-radius: 0.5rem;
      height: 0.8rem;
    }

    /* slider thumb */
    input[type="range"]::-webkit-slider-thumb {
      -webkit-appearance: none; /* Override default look */
      appearance: none;
      margin-top: -3.999999999999999px; /* Centers thumb on the track */
      background-color: #b9c3d0;
      border-radius: 0.1rem;
      height: 1.4rem;
      width: 1.9rem;
    }

    #BarraDeNavVTabla{
      margin-left: 86.5%;
      margin-bottom: 13.4%;
      transform:rotate(90deg);
      width: 26.1%;
      height:0.9rem;
      opacity: 70%;
    }

    table thead tr th {
      background-color: #ccc;
      position: sticky;
      top: 0;
      z-index: 100;
      display: block;
    }

    /* table tbody tr td{
        position: sticky;
        top: 100;
        z-index: 90;
        display: inline-block;
      } */

    tr {
      color: #fff;
    }
    
    td[id^="Contenido"]{
      color: #212529;
      background-color: #212529;
    }
    
    td {
      border: 1px solid black;
    }

    .table-responsive {
      height: 480px;
      width: 98%;
      /*overflow-y: hidden;*/
      overflow-x: hidden;
      position: absolute;
    }

    .table-fixeder {
      width: max-content;
      /* width: auto; */
      height: 470px;
      /* overflow-x: scroll;  */
      display: table;
      position: relative;
      /* white-space: nowrap;                  */
    }

    .table-fixeder thead {
      width: max-content;
      z-index: 2000;
      position: sticky;
      top: 0;
      display: block;
    }

    /* .table-fixeder tbody {
        height: 400px;
        width: max-content;
        z-index: 20;
        overflow-y: auto;
        overflow-x: hidden;        
      } */

    .table-fixeder td,
    .table-fixeder thead,
    .table-fixeder tbody,
    .table-fixeder tr,
    .table-fixeder th {
      display: block;
    }

    .table-fixeder tbody td,
    .table-fixeder tbody tr td,
    .table-fixeder thead>tr>th {
      float: left;
      border-bottom-width: 1.5px;
      width: 150px;
      height: 70px;
    }

    .td--extenso-height-127{
      height: 127px !important;
    }

    .table-fixeder tbody tr:nth-child(1) td{
      border-top-width: 0px;
    }

    .table-fixeder thead>tr>th {
      height: 60px;
    }

    .table-fixeder tbody tr td .Datos,
    .table-fixeder thead tr th .Datos {
      min-width: 150px;
      height: 70px;
    }

    .Datos {
      font-size: 18px;
      font-weight: bold;
    }

    .SinMovimientos td {
      background-color: #ccc;
    }

    .etFiltros {
      color: #FFF;
      background-color: #BBB;
      border-radius: 20px;
      padding: 8px;
      display: inline-block;
      margin: 3px;
    }

    .nombreCategoria {
      font-size: 10px;
      font-weight: bold;
      font-family: Arial, Helvetica, sans-serif;
      color: black;
      display: block;
      text-align: center;
    }

    /*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
    /*/////////////////////////////////////////INTENDANDO SOLUCIONAR EL SCROLL HORIZONTAL DE LOS DATOS////////////////////////////////////////////////////*/
    /*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
    #Contenido-1 {
      /*position: sticky;
      left: 0;*/
      width: 150px;
      /*z-index: 200;*/
      background-color: #FFF;
    }

    #Contenido-2 {
      /*position: sticky;
      
      left: 150px;*/
      width: 150px;
      /*z-index: 200;*/
      background-color: #FFF;
    }

    #Contenido-3 {
      /*position: sticky;
      left: 300px;*/
      width: 150px;
      /* 50px  ACAAA */
      /*z-index: 200;*/
      background-color: #FFF;
    }

    #Contenido-4 {
      /*position: sticky;
      left: 450px;*/
      /* 350px  ACAAA */
      width: 120px;
      /* 50px  ACAAA */
      /*z-index: 200;*/
      background-color: #FFF;
    }

    #Contenido-5 {
      /*position: sticky;
      left: 400px;*/
      width: 60px;
      /*z-index: 200;*/
      background-color: #FFF;
    }

    #Contenido-6 {
      /*position: sticky;
      left: 450px;*/
      width: 150px;
      /*z-index: 200;*/
      background-color: #FFF;
    }

    #Contenido-7 {
      /*position: sticky;
      left: 600px;*/
      width: 150px;
      /*z-index: 200;*/
      background-color: #FFF;
    }

    #Contenido-Titulo-1 {
      position: sticky;
      z-index: 200;
      /*left: 0;*/
      width: 150px;
    }

    #Contenido-Titulo-2 {
      position: sticky;
      z-index: 200;
      /*left: 150px;*/
      width: 150px;
    }

    #Contenido-Titulo-3 {
      position: sticky;
      /*left: 300px;*/
      z-index: 200;
      width: 150px;
      /* 50px  ACAAA */
    }

    #Contenido-Titulo-4 {
      position: sticky;
      /*left: 450px;*/
      z-index: 200;
      /* 350px  ACAAA */
      width: 120px;
      /* 50px  ACAAA */
    }

    #Contenido-Titulo-5 {
      position: sticky;
      /*left: 400px;*/
      z-index: 200;
      width: 70px;
    }

    #Contenido-Titulo-6 {
      position: sticky;
      /*left: 450px;*/
      z-index: 200;
      width: 150px;
    }

    #Contenido-Titulo-7 {
      position: sticky;
      z-index: 200;*/
      /*left: 600px;*/
      width: 150px;
    }

    #Contenido-Titulo-8 {
      position: sticky;
      z-index: 200;*/
      /*left: 600px;*/
      width: 150px;
    }
  
    #Contenido-Titulo-9 {
      position: sticky;
      z-index: 200;*/
      /*left: 600px;*/
      width: 150px;
    }
    
    div[id$="_popup_contentDiv"] {
      width: auto !important;
    }
    /*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
    /*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
    /*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
  </style>
</head>

<body>
  <div id="ContenerdorPrincipal" class="row">
  <?php
    if ($TipoUsuario == 1) {
      ?>
      <div class="col-md-2" id="expandir" hidden>
        <a id="abrir" class="btn btn-secondary btn-sm" href="javascript:void(0)" onclick="mostrar()">
          <i class="fa fa-arrows-alt fa-lg" color="tomato"></i>
        </a>
      </div>
      <div class="col-md-2" id="ContenidoMenu">
        <div class="nav-side-menu" id="sidebar">
          <a id="cerrar" class="btn btn-secondary btn-sm" href="javascript:void(0)" onclick="ocultar()">
            <i class="fa fa-arrow-left fa-lg"></i>
          </a>
          <div class="div--padding-10px">
            <?php $Element = new Elements();
              echo $Element->CBSessionNombre($ID_Usuario);
            ?>
          </div>
          <div class="brand">General</div>
          <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
          <div class="menu-list">
            <?php
            $Element->getMenuGeneral(0); ?>
          </div>
          <div class="brand">Actualizaciones</div>
          <div class="menu-list">

            <?php
            $Element->getMenuActualizaciones(0); ?>
          </div>
          <div class="brand">Reportes</div>
          <div class="menu-list">

            <?php
            $Element->getMenuReportes(1); ?>
          </div>
          <div class="brand">Unificación</div>
          <div class="menu-list">

            <?php
            $Element->getMenuUnificacion(0); ?>
          </div>
          <div class="brand">Seguridad</div>
          <div class="menu-list">

            <?php
            $Element->getMenuSeguridad(0); ?>
          </div>
          <div class="brand">El Proyecto</div>
          <div class="menu-list">
            <?php
            $Element->getMenuHistorial(0); ?>
          </div>
          <div class="brand btn-Salir" onClick="location.href = 'Controladores/CtrLogout.php'">Salir**</div>
        </div>
      </div>
      <?php
    }
    if($TipoUsuario == 2 || $TipoUsuario > 3){
      ?>
      <div class="col-md-2" id="expandir" hidden>
        <a id="abrir" class="btn btn-secondary btn-sm" href="javascript:void(0)" onclick="mostrar()">
          <i class="fa fa-arrows-alt fa-lg" color="tomato"></i>
        </a>
      </div>
      <div class="col-md-2" id="ContenidoMenu">
        <div class="nav-side-menu" id="sidebar" style="padding-left: 5px;">
          <a id="cerrar" class="btn btn-secondary btn-sm" href="javascript:void(0)" onclick="ocultar()">
            <i class="fa fa-arrow-left fa-lg"></i>
          </a>
          <div style="display:inline-block">
            <?php $Element = new Elements();
              echo $Element->CBSessionNombre($ID_Usuario);
            ?>
          </div>
          <div class="brand">General</div>
          <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>

          <div class="menu-list">

            <?php
            $Element->getMenuGeneral(0); ?>
          </div>
          <div class="brand">Actualizaciones</div>
          <div class="menu-list">

            <?php
            $Element->getMenuActualizaciones(0); ?>
          </div>
          <div class="brand">Reportes</div>
          <div class="menu-list">
  
            <?php
            $Element->getMenuReportes(0);?>
          </div>
          <div class="brand">El Proyecto</div>
          <div class="menu-list">
            <?php
            $Element->getMenuHistorial(0); ?>
          </div>
          <div class="brand btn-Salir" onClick="location.href = 'Controladores/CtrLogout.php'">Salir</div>
        </div>
      </div>
      <?php
    }
    if ($TipoUsuario == 3) {
      ?>
      <div class="col-md-2" id="expandir" hidden>
        <a id="abrir" class="btn btn-secondary btn-sm" href="javascript:void(0)" onclick="mostrar()">
          <i class="fa fa-arrows-alt fa-lg" color="tomato"></i>
        </a>
      </div>
      <div class="col-md-2" id="ContenidoMenu">
        <div class="nav-side-menu" id="sidebar" style="padding-left: 5px;">
          <a id="cerrar" class="btn btn-secondary btn-sm" href="javascript:void(0)" onclick="ocultar()">
            <i class="fa fa-arrow-left fa-lg"></i>
          </a>
          <div style="display:inline-block">
            <?php $Element = new Elements();
              echo $Element->CBSessionNombre($ID_Usuario);
            ?>
          </div>
          <div class="brand">General</div>
          <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>

          <div class="menu-list">

            <?php
            $Element->getMenuGeneral(0); ?>
          </div>
          <div class="brand">Actualizaciones</div>
          <div class="menu-list">

            <?php
            $Element->getMenuActualizaciones(0); ?>
          </div>
          <div class="brand">Reportes</div>
          <div class="menu-list">

            <?php
            $Element->getMenuReportes(1); ?>
          </div>
          <div class="brand">Unificación</div>
          <div class="menu-list">

            <?php
            $Element->getMenuUnificacion(0); ?>
          </div>
          <div class="brand">El Proyecto</div>
          <div class="menu-list">
            <?php
            $Element->getMenuHistorial(0); ?>
          </div>
          <div class="brand btn-Salir" onClick="location.href = 'Controladores/CtrLogout.php'">Salir</div>
        </div>
      </div>
    <?php } ?>

    <div class="col-md-10" id="ContenidoTabla">
      <div class="row">
        <div class="col"></div>
        <div class="col-10 Titulo">
          <p>Rastreador. Gráfico de co-evolución para la evaluación comunitaria de cobertura.<br>Sistema orientado a la
            georreferenciación.</p>
        </div>
        <div class="col"></div>
      </div><br>
      <div class="row">
        <div class="col">
          <!--<button class="btn btn-info btn-sm" onClick="toggleZoomScreen()">Zoom +</button> 
            <button class="btn btn-info btn-sm" onClick="toggleZoomScreenNormal()">Zoom -</button>-->
          <div class="number-input">
             <button id="zoomIncrementar" class="plus"></button>
             <input id="input-zoom" value="100" class="quantity" style="padding-right: 3px;" min="0" name="quantity" value="1" type="number">
             <div id="divporcentaje">%</div>
             <button id="zoomDecrementar"></button>
          </div>
        </div>
        <div class="col">
          <?php
          if (!isset($_REQUEST["Anio"])) {
            if (isset($_REQUEST["Fecha_Desde"])){
              $Fecha_Inicio = implode("-", array_reverse(explode("/", $_REQUEST["Fecha_Desde"])));
            } else{
              $Fecha_Inicio = null;
            }
            if (isset($_REQUEST["Fecha_Hasta"])){
              $Fecha_Fin = implode("-", array_reverse(explode("/", $_REQUEST["Fecha_Hasta"])));
            } else{
              $Fecha_Fin = null;
            }
  
            $ID_Persona = (isset($_REQUEST["ID_Persona"])) ? $_REQUEST["ID_Persona"] : null; 
            $Edad_Desde = (isset($_REQUEST["Edad_Desde"])) ? $_REQUEST["Edad_Desde"] : null; 
            $Edad_Hasta = (isset($_REQUEST["Edad_Hasta"])) ? $_REQUEST["Edad_Hasta"] : null;
            $Meses_Desde = (isset($_REQUEST["Meses_Desde"])) ? $_REQUEST["Meses_Desde"] : null;
            $Meses_Hasta = (isset($_REQUEST["Meses_Hasta"])) ? $_REQUEST["Meses_Hasta"] : null;
            $Domicilio = (isset($_REQUEST["Domicilio"])) ? $_REQUEST["Domicilio"] : null;
            $Manzana = (isset($_REQUEST["Manzana"])) ? $_REQUEST["Manzana"] : null;
            $Lote = (isset($_REQUEST["Lote"])) ? $_REQUEST["Lote"] : null;
            $Familia = (isset($_REQUEST["Familia"])) ? $_REQUEST["Familia"] : null;
            $Barrio = (isset($_REQUEST["ID_Barrio"])) ? $_REQUEST["ID_Barrio"] : null;

            $Nro_Carpeta = (isset($_REQUEST["Nro_Carpeta"])) ? $_REQUEST["Nro_Carpeta"] : null;
            $Nro_Legajo = (isset($_REQUEST["Nro_Legajo"])) ? $_REQUEST["Nro_Legajo"] : null;

            $ID_Motivo = (isset($_REQUEST["ID_Motivo"])) ? $_REQUEST["ID_Motivo"] : null;
            $ID_Motivo2 = (isset($_REQUEST["ID_Motivo2"])) ? $_REQUEST["ID_Motivo2"] : null;
            $ID_Motivo3 = (isset($_REQUEST["ID_Motivo3"])) ? $_REQUEST["ID_Motivo3"] : null;
            $MotivosOpciones = [
              "ID_Motivo" => $ID_Motivo,
              "ID_Motivo2" => $ID_Motivo2,
              "ID_Motivo3" => $ID_Motivo3,
            ];

            if (isset($_REQUEST["ID_Motivo4"])) {
              $ID_Motivo4 = $_REQUEST["ID_Motivo4"];
              $MotivosOpciones["ID_Motivo4"] = $ID_Motivo4;
            } else {
              $ID_Motivo4 = 0;
            }
            if (isset($_REQUEST["ID_Motivo5"])) {
              $ID_Motivo5 = $_REQUEST["ID_Motivo5"];
              $MotivosOpciones["ID_Motivo5"] = $ID_Motivo5;
            } else {
              $ID_Motivo5 = 0;
            }

            $ID_Categoria = (isset($_REQUEST["ID_Categoria"])) ? $_REQUEST["ID_Categoria"] : null;
            $ID_Escuela = (isset($_REQUEST["ID_Escuela"])) ? $_REQUEST["ID_Escuela"] : null;
            $Trabajo = (isset($_REQUEST["Trabajo"])) ? $_REQUEST["Trabajo"] : null;
            $Mostrar = (isset($_REQUEST["Mostrar"])) ? $_REQUEST["Mostrar"] : null;
            $ID_CentroSalud = (isset($_REQUEST["ID_CentroSalud"])) ? $_REQUEST["ID_CentroSalud"] : null;
            $ID_OtraInstitucion = (isset($_REQUEST["ID_OtraInstitucion"])) ? $_REQUEST["ID_OtraInstitucion"] : null;
            $ID_Responsable = (isset($_REQUEST["ID_Responsable"])) ? $_REQUEST["ID_Responsable"] : null;

            $cmb_seleccion = (isset($_REQUEST["cmb_seleccion"])) ? $_REQUEST["cmb_seleccion"] : null;
            $esPersonaSeleccionada = ($ID_Persona) ? ", IF(M.id_persona = $ID_Persona, 1, 0) as esPersona" : "";

            $Consulta = "SELECT M.id_movimiento, M.id_persona, MONTH(M.fecha) as 'Mes',
                                YEAR(M.fecha) as 'Anio', B.Barrio, P.manzana, P.lote,
                                P.familia, P.apellido, P.nombre, P.fecha_nac, P.domicilio,
                                ST_X(P.georeferencia) as lat, 
                                ST_Y(P.georeferencia) as lon, 
                                P.edad, P.meses 
                                $esPersonaSeleccionada
                         FROM movimiento M, 
                              persona P, 
                              barrios B, 
                              motivo MT, 
                              categoria C, 
                              centros_salud CS, 
                              otras_instituciones I, 
                              responsable R
                         WHERE M.id_persona = P.id_persona 
                           and B.ID_Barrio = P.ID_Barrio 
                           and M.id_centro = CS.id_centro 
                           and M.id_otrainstitucion = I.ID_OtraInstitucion 
                           and M.id_resp = R.id_resp
                           and (MT.id_motivo = M.motivo_1
                               or MT.id_motivo = M.motivo_2
                               or MT.id_motivo = M.motivo_3
                               or MT.id_motivo = M.motivo_4
                               or MT.id_motivo = M.motivo_5)
                           and MT.cod_categoria = C.cod_categoria
                           and M.estado = 1 
                           and P.estado = 1 
                           and MT.estado = 1 
                           and C.estado = 1 
                           and M.fecha between '$Fecha_Inicio' and '$Fecha_Fin'";
            $json_filtro = [];
            $filtros = [];
            $Con = new Conexion();
            $Con->OpenConexion();

            // Tabla asociada a los permisos de usuarios sobre las categorias
            $consultaPermisos = "CREATE TEMPORARY TABLE PERMISOS ";
        
            $motivosVisiblesParaUsuario =  "SELECT MT.id_motivo
                                            FROM motivo MT,
                                                categoria  C,
                                                categorias_roles CS
                                            WHERE C.cod_categoria = MT.cod_categoria
                                              and MT.estado = 1
                                              and C.estado = 1
                                              and CS.id_categoria = C.id_categoria
                                              and CS.id_tipousuario = $TipoUsuario
                                              and CS.estado = 1";
        
            $motivosVisiblesParaTodoUsuario = "SELECT MT.id_motivo
                                               FROM motivo MT,
                                                    categoria  C
                                               WHERE C.cod_categoria = MT.cod_categoria
                                                 and MT.estado = 1
                                                 and C.estado = 1               
                                                 and C.id_categoria NOT IN (SELECT id_categoria
                                                                            FROM categorias_roles CS)";

            $motivosVisibles = $consultaPermisos . $motivosVisiblesParaUsuario . " UNION DISTINCT " . $motivosVisiblesParaTodoUsuario;

            $MessageError = "Problemas al crear la tabla temporaria con todos los permisos";
            $motivosUsuario = mysqli_query(
                              $Con->Conexion, $motivosVisibles
                              ) or die($MessageError);

            if ($ID_Persona > 0) {
              $countPostfield = count(array_filter($_POST, function ($element){
                return $element;
              }));

              $ConsultaFlia = $Consulta;
              $ConsultarPersona = "select apellido, 
                                          nombre, 
                                          domicilio,
                                          ID_Barrio
                                   from persona 
                                   where ID_Persona = " . $ID_Persona . " 
                                     and estado = 1";
              $EjecutarConsultarPersona = mysqli_query($Con->Conexion, $ConsultarPersona) or die("Problemas al consultar filtro Persona");
              $RetConsultarPersona = mysqli_fetch_assoc($EjecutarConsultarPersona);

              if (($countPostfield - 3) == 1)  {
                if (!empty($RetConsultarPersona["domicilio"])) {
                  $domicilio = $RetConsultarPersona["domicilio"];
                  $persona = new Persona(ID_Persona : $ID_Persona);
                  $ConsultarPersdomicilio = "select id_persona
                                            from persona
                                            where domicilio like '%" . $persona->getCalle() . "%". $persona->getNroCalle()."%'
                                              and estado = 1";
                  $Consulta .= " and P.id_persona in ($ConsultarPersdomicilio)";
                } else {
                  $Consulta .= " and P.id_persona = $ID_Persona";
                }
              } else {
                $Consulta .= " and P.id_persona = $ID_Persona";
              }
              $filtros[] = "Persona: " . $RetConsultarPersona["apellido"] . ", " . $RetConsultarPersona["nombre"];
              $json_filtro[] = "Persona " . $RetConsultarPersona["apellido"] . " " . $RetConsultarPersona["nombre"];

              // TODO:
              // $ConsultaFlia .= " and P.domicilio like '%".$RetConsultarPersona["domicilio"]."%'";
              // echo $ConsultaFlia;
              // $EjecutarConsultaFlia = mysqli_query($Con->Conexion,$ConsultaFlia) or die("Problemas al consultar filtro Flia Persona");
              // $RetConsultaFlia = mysqli_fetch_assoc($EjecutarConsultaFlia);                                                                
          

            }

            if ($Edad_Desde !== null && $Edad_Desde !== "" && $Edad_Hasta !== null && $Edad_Hasta !== "") {
              // $Consulta .= " and P.edad between $Edad_Desde and $Edad_Hasta";
              $Consulta .= " and P.edad >= $Edad_Desde and P.edad <= $Edad_Hasta";
              $filtros[] = "Edad: Desde " . $Edad_Desde . " hasta " . $Edad_Hasta;
              $json_filtro[] = "Edad Desde " . $Edad_Desde . " hasta " . $Edad_Hasta;
            }

            /*
            if ($Meses_Desde !== null && $Meses_Desde !== "" && $Meses_Hasta !== null && $Meses_Hasta !== "") {
              // $Consulta .= " and P.edad between $Edad_Desde and $Edad_Hasta";
              $Consulta .= "and P.meses <= $Meses_Hasta";
              if ($Edad_Desde == null) {
                $Consulta .= " and P.edad >= $Edad_Desde";
                $filtros[] = "Meses: Desde " . $Meses_Desde . " hasta " . $Meses_Hasta;
              } else {
                $filtros[] = "Meses: Desde 0 hasta " . $Meses_Hasta;
              }
            }
            */
            /*
            if ($Meses_Desde !== null && $Meses_Desde !== "" && $Meses_Hasta !== null && $Meses_Hasta !== "") {
              // $Consulta .= " and P.edad between $Edad_Desde and $Edad_Hasta";
              $Consulta .= "and P.meses <= $Meses_Hasta";
              if ($Edad_Desde == null) {
                $Consulta .= " and P.edad >= $Edad_Desde";
                $filtros[] = "Meses: Desde " . $Meses_Desde . " hasta " . $Meses_Hasta;
              } else {
                $filtros[] = "Meses: Desde 0 hasta " . $Meses_Hasta;
              }
            }
            */

            if ($Edad_Desde !== null && $Edad_Desde !== "" && $Edad_Hasta !== null && $Edad_Hasta !== "") {
              // $Consulta .= " and P.edad between $Edad_Desde and $Edad_Hasta";
              $Consulta .= " and P.edad >= $Edad_Desde and P.edad <= $Edad_Hasta";
              $filtros[] = "Edad: Desde " . $Edad_Desde . " hasta " . $Edad_Hasta;
              $json_filtro[] = "Edad Desde " . $Edad_Desde . " hasta " . $Edad_Hasta;
              if ($Meses_Hasta !== null && $Meses_Hasta !== "") {
                // $Consulta .= " and P.edad between $Edad_Desde and $Edad_Hasta";
                $Consulta .= " and (P.edad < $Edad_Hasta or P.meses <= $Meses_Hasta)";
                if ($Meses_Desde != null) {
                  $Consulta .= " and P.meses >= $Meses_Desde ";
                  $filtros[] = "Meses: Desde " . $Meses_Desde . " hasta " . $Meses_Hasta;
                  $json_filtro[] = "Meses Desde " . $Meses_Desde . " hasta " . $Meses_Hasta;
                } else {
                  $filtros[] = "Meses: Desde 0 hasta " . $Meses_Hasta;
                  $json_filtro[] = "Meses Desde 0 hasta " . $Meses_Hasta;
                }
              }
            } else {
              if ($Meses_Desde !== null && $Meses_Desde !== "" && $Meses_Hasta !== null && $Meses_Hasta !== "") {
                // $Consulta .= " and P.edad between $Edad_Desde and $Edad_Hasta";
                $Consulta .= " and P.meses <= $Meses_Hasta and P.edad = 0 ";
                if ($Meses_Desde != null) {
                  $Consulta .= " and P.meses >= $Meses_Desde";
                  $filtros[] = "Meses: Desde " . $Meses_Desde . " hasta " . $Meses_Hasta;
                  $json_filtro[] = "Meses Desde " . $Meses_Desde . " hasta " . $Meses_Hasta;
                } else {
                  $filtros[] = "Meses: Desde 0 hasta " . $Meses_Hasta;
                  $json_filtro[] = "Meses Desde 0 hasta " . $Meses_Hasta;
                }
              }
            }

            if ($Domicilio != null && $Domicilio != "") {
              $Consulta .= " and P.domicilio like '%$Domicilio%'";
              $filtros[] = "Domicilio: " . $Domicilio;
              $json_filtro[] = "Domicilio " . $Domicilio;
            }
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // if($cmb_seleccion!= null && $cmb_seleccion != ""){
          

            // }
          
            if ($Manzana != null && $Manzana != "") {
              $Consulta .= " and P.manzana = '$Manzana'";
              $filtros[] = "Manzana: " . $Manzana;
              $json_filtro[] = "Manzana " . $Manzana;
            }

            if ($Lote != null && $Lote != "") {
              $Consulta .= " and P.lote = $Lote";
              $filtros[] = "Lote: " . $Lote;
              $json_filtro[] = "Lote " . $Lote;
            }

            if ($Familia != null && $Familia != "") {
              $Consulta .= " and P.familia = $Familia";
              $filtros[] = "Sublote: " . $Familia;
              $json_filtro[] = "Sublote " . $Familia;
            }
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($Nro_Carpeta != null && $Nro_Carpeta != "") {
              $Consulta .= " and P.nro_carpeta = '$Nro_Carpeta'";
              $filtros[] = "Nro_carpeta: " . $Nro_Carpeta;
              $json_filtro[] = "Nro_carpeta " . $Nro_Carpeta;
            }

            if ($Nro_Legajo != null && $Nro_Legajo != "") {
              $Consulta .= " and P.nro_legajo = '$Nro_Legajo'";
              $filtros[] = " Nro_legajo : " . $Nro_Legajo;
              $json_filtro[] = " Nro_legajo : " . $Nro_Legajo;
            }

            // ECHO "ACA ".$Consulta."<BR><BR>";
          
            if (count($Barrio) > 1) {
              $filtroBarrios = 'Barrios:';
              foreach ($Barrio as $key => $valueBarrio) {
                if ($key == $Barrio->array_key_first) {
                  $Consulta .= " and (";
                }
                if ($valueBarrio > 0) {
                  if ($key === count($Barrio) - 1) {
                    $Consulta .= " P.ID_Barrio = $valueBarrio )";
                  } else {
                    $Consulta .= " P.ID_Barrio = $valueBarrio or";
                  }
                  $ConsultarBarrio = "select Barrio from barrios where ID_Barrio = " . $valueBarrio . " limit 1";
                  $EjecutarConsultarBarrio = mysqli_query($Con->Conexion, $ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                  $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
                  if ($key == $Barrio->array_key_first) {
                    $filtroBarrios .= " " . $RetConsultarBarrio['Barrio'];
                  } else {
                    $filtroBarrios .= " - " . $RetConsultarBarrio['Barrio'];
                  }
                }
              }
              $filtros[] = $filtroBarrios;
            } else {
              if ($Barrio[0] > 0) {
                $Consulta .= " and P.ID_Barrio = $Barrio[0]";
                $ConsultarBarrio = "select Barrio from barrios where ID_Barrio = " . $Barrio[0] . " limit 1";
                $EjecutarConsultarBarrio = mysqli_query($Con->Conexion, $ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
                $filtros[] = "Barrio: " . $RetConsultarBarrio['Barrio'];
              }
            }

            // $Consulta.= ")";
          
            // if($Barrio > 0){
            //   $Consulta .= " and P.ID_Barrio = $Barrio";
            // }
          
            if ($ID_Escuela > 0) {
              $Consulta .= " and P.ID_Escuela = $ID_Escuela";
              $ConsultarEscuela = "select Escuela from escuelas where ID_Escuela = " . $ID_Escuela . " limit 1";
              $EjecutarConsultarEscuela = mysqli_query($Con->Conexion, $ConsultarEscuela) or die("Problemas al consultar filtro Escuela");
              $RetConsultarEscuela = mysqli_fetch_assoc($EjecutarConsultarEscuela);
              $filtros[] = "Escuela: " . $RetConsultarEscuela['Escuela'];
              $json_filtro[] = "Escuela " . $RetConsultarEscuela['Escuela'];
            }

            if ($Trabajo != null && $Trabajo != "") {
              $Consulta .= " and P.Trabajo like '%$Trabajo%'";
              $filtros[] = "Trabajo: " . $Trabajo;
              $json_filtro[] = "Trabajo " . $Trabajo;
            }
            //////////////////////////////////////////////////////////////////////////// MOTIVOS ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              $motivos = array_filter($MotivosOpciones, function($x) { return !empty($x); });
              $CantOpMotivos = count($motivos);

              if($CantOpMotivos > 0){
                $Consulta .= " and ";
                $lista_motivos = "(" . implode(",", $motivos) . ")";
                $consulta_motivos = " and (motivo_1 in $lista_motivos 
                                        or motivo_2 in $lista_motivos 
                                        or motivo_3 in $lista_motivos 
                                        or motivo_4 in $lista_motivos 
                                        or motivo_5 in $lista_motivos) ";
                if($CantOpMotivos > 1){
                  $Consulta .= " (";
                }
              } else {
                $consulta_motivos = "";
              }

              if($ID_Motivo > 0){
                $Consulta .= " (M.motivo_1 = $ID_Motivo 
                             or M.motivo_2 = $ID_Motivo 
                             or M.motivo_3 = $ID_Motivo
                             or M.motivo_4 = $ID_Motivo 
                             or M.motivo_5 = $ID_Motivo) ";

                $ConsultarMotivo = "select motivo 
                                    from motivo 
                                    where id_motivo = ".$ID_Motivo." limit 1";

                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);  
                $filtros[] = "Motivo 1: ". $RetConsultarMotivo['motivo'];
                $json_filtro[] = "Motivo 1 ". $RetConsultarMotivo['motivo'];
                //$filtrosSeleccionados["ID_Motivo1"] = $ID_Motivo;
              }
              if($ID_Motivo2 > 0){
                if($ID_Motivo > 0 ){
                  $Consulta .= " or ";
                }
                $Consulta .= " (M.motivo_1 = $ID_Motivo2 
                            or M.motivo_2 = $ID_Motivo2 
                            or M.motivo_3 = $ID_Motivo2 
                            or M.motivo_4 = $ID_Motivo2 
                            or M.motivo_5 = $ID_Motivo2) ";

                $ConsultarMotivo = "select motivo 
                                    from motivo 
                                    where id_motivo = " . $ID_Motivo2." limit 1";

                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);
                $filtros[] = "Motivo 2: " . $RetConsultarMotivo['motivo'];
                $json_filtro[] = "Motivo 2 " . $RetConsultarMotivo['motivo'];
                //$filtrosSeleccionados["ID_Motivo2"] = $ID_Motivo2;
              }

              if($ID_Motivo3 > 0){
                if($ID_Motivo > 0 || $ID_Motivo2 > 0){
                  $Consulta .= " or ";
                }

                $Consulta .= " (M.motivo_1 = $ID_Motivo3 
                            or M.motivo_2 = $ID_Motivo3 
                            or M.motivo_3 = $ID_Motivo3
                            or M.motivo_4 = $ID_Motivo3 
                            or M.motivo_5 = $ID_Motivo3) ";

                $ConsultarMotivo = "select motivo 
                                    from motivo 
                                    where id_motivo = ".$ID_Motivo3." limit 1";

                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);  
                $filtros[] = "Motivo 3: ".$RetConsultarMotivo['motivo'];
                $json_filtro[] = "Motivo 3 " . $RetConsultarMotivo['motivo'];
                //$filtrosSeleccionados["ID_Motivo3"] = $ID_Motivo3;
              }

              if($ID_Motivo4 > 0){
                if($ID_Motivo > 0 || $ID_Motivo2 > 0 || $ID_Motivo3 > 0){
                  $Consulta .= " or ";
                }

                $Consulta .= " (M.motivo_1 = $ID_Motivo4 
                            or M.motivo_2 = $ID_Motivo4 
                            or M.motivo_3 = $ID_Motivo4
                            or M.motivo_4 = $ID_Motivo4 
                            or M.motivo_5 = $ID_Motivo4) ";

                $ConsultarMotivo = "select motivo 
                                    from motivo 
                                    where id_motivo = ".$ID_Motivo4." limit 1";

                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);  
                $filtros[] = "Motivo 4: ".$RetConsultarMotivo['motivo'];
                $json_filtro[] = "Motivo 4 " . $RetConsultarMotivo['motivo'];
              }

              if($ID_Motivo5 > 0){
                if($ID_Motivo > 0 || $ID_Motivo2 > 0 || $ID_Motivo3 > 0 || $ID_Motivo4 > 0){
                  $Consulta .= " or ";
                }

                $Consulta .= "(M.motivo_1 = $ID_Motivo5
                            or M.motivo_2 = $ID_Motivo5
                            or M.motivo_3 = $ID_Motivo5
                            or M.motivo_4 = $ID_Motivo5 
                            or M.motivo_5 = $ID_Motivo5)";

                $ConsultarMotivo = "select motivo 
                                    from motivo 
                                    where id_motivo = ".$ID_Motivo5." limit 1";
                $EjecutarConsultarMotivo = mysqli_query($Con->Conexion,$ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
                $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);  
                $filtros[] = "Motivo 5: ".$RetConsultarMotivo['motivo'];
                $json_filtro[] = "Motivo 5 " . $RetConsultarMotivo['motivo'];
              }

              if($CantOpMotivos > 1){
                $Consulta .= ")";
              }

            /*
            if ($ID_Motivo > 0) {
              if ($ID_Motivo2 > 0 || $ID_Motivo3 > 0) {
                $Consulta .= " and (";
              } else {
                $Consulta .= " and ";
              }
              $Consulta .= " (M.motivo_1 = $ID_Motivo or M.motivo_2 = $ID_Motivo or M.motivo_3 = $ID_Motivo)";
              // $Consulta .= "M.motivo_1 = $ID_Motivo or M.motivo_2 = $ID_Motivo or M.motivo_3 = $ID_Motivo)";
              $ConsultarMotivo = "select motivo from motivo where id_motivo = " . $ID_Motivo . " limit 1";
              $EjecutarConsultarMotivo = mysqli_query($Con->Conexion, $ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
              $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);
              $filtros[] = "Motivo 1: " . $RetConsultarMotivo['motivo'];
            }

            if ($ID_Motivo2 > 0) {
              $Consulta .= " or (M.motivo_1 = $ID_Motivo2 or M.motivo_2 = $ID_Motivo2 or M.motivo_3 = $ID_Motivo2)";
              $ConsultarMotivo = "select motivo from motivo where id_motivo = " . $ID_Motivo2 . " limit 1";
              $EjecutarConsultarMotivo = mysqli_query($Con->Conexion, $ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
              $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);
              $filtros[] = "Motivo 2: " . $RetConsultarMotivo['motivo'];

              if ($ID_Motivo3 == 0) {
                $Consulta .= ")";
              }
            }

            if ($ID_Motivo3 > 0) {
              $Consulta .= " or (M.motivo_1 = $ID_Motivo3 or M.motivo_2 = $ID_Motivo3 or M.motivo_3 = $ID_Motivo3))";
              $ConsultarMotivo = "select motivo from motivo where id_motivo = " . $ID_Motivo3 . " limit 1";
              $EjecutarConsultarMotivo = mysqli_query($Con->Conexion, $ConsultarMotivo) or die("Problemas al consultar filtro Motivo");
              $RetConsultarMotivo = mysqli_fetch_assoc($EjecutarConsultarMotivo);
              $filtros[] = "Motivo 3: " . $RetConsultarMotivo['motivo'];
            }
            */


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($ID_Categoria > 0) {
              $Consulta .= " and  ((M.motivo_1 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria) or (M.motivo_2 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria) or (M.motivo_3 = MT.id_motivo and MT.cod_categoria = C.cod_categoria and C.id_categoria = $ID_Categoria))";
              $ConsultarCategoria = "select categoria from categoria where id_categoria = " . $ID_Categoria . " limit 1";
              $EjecutarConsultarCategoria = mysqli_query($Con->Conexion, $ConsultarCategoria) or die("Problemas al consultar filtro Categoria");
              $RetConsultarCategoria = mysqli_fetch_assoc($EjecutarConsultarCategoria);

              $consulta_categoria = "and C.id_categoria = " . $ID_Categoria;
              $filtros[] = "Categoria: " . $RetConsultarCategoria['categoria'];
              $json_filtro[] = "Categoria " . $RetConsultarCategoria['categoria'];
            } else {
              $consulta_categoria = "";
            }

            if ($ID_CentroSalud > 0) {
              $Consulta .= " and CS.id_centro = $ID_CentroSalud";
              $ConsultarCentroSalud = "select centro_salud from centros_salud where id_centro = " . $ID_CentroSalud . " limit 1";
              $EjecutarConsultarCentroSalud = mysqli_query($Con->Conexion, $ConsultarCentroSalud) or die("Problemas al consultar filtro Categoria");
              $RetConsultarCentroSalud = mysqli_fetch_assoc($EjecutarConsultarCentroSalud);
              $filtros[] = "Centro Salud: " . $RetConsultarCentroSalud['centro_salud'];
              $json_filtro[] = "Centro Salud " . $RetConsultarCentroSalud['centro_salud'];
            }

            if ($ID_OtraInstitucion > 0) {
              $Consulta .= " and I.ID_OtraInstitucion = $ID_OtraInstitucion";
              $ConsultarOtraInstitucion = "select Nombre from otras_instituciones where ID_OtraInstitucion = " . $ID_OtraInstitucion . " limit 1";
              $EjecutarConsultarOtraInstitucion = mysqli_query($Con->Conexion, $ConsultarOtraInstitucion) or die("Problemas al consultar filtro Categoria");
              $RetConsultarOtraInstitucion = mysqli_fetch_assoc($EjecutarConsultarOtraInstitucion);
              $filtros[] = "Otra Institucion: " . $RetConsultarOtraInstitucion['Nombre'];
              $json_filtro[] = "Otra Institucion " . $RetConsultarOtraInstitucion['Nombre'];
            }

            if ($ID_Responsable > 0) {
              $Consulta .= " and R.id_resp = $ID_Responsable";
              $ConsultarResponsable = "select responsable from responsable where id_resp = " . $ID_Responsable . " limit 1";
              $EjecutarConsultarResponsable = mysqli_query($Con->Conexion, $ConsultarResponsable) or die("Problemas al consultar filtro Responsable");
              $RetConsultarResponsable = mysqli_fetch_assoc($EjecutarConsultarResponsable);
              $filtros[] = "Responsable: " . $RetConsultarResponsable['responsable'];
              $json_filtro[] = "Responsable " . $RetConsultarResponsable['responsable'];
            }

            if ($ID_Persona > 0) {
              // SE PUEDE ROMPER
              //$Consulta .= " group by M.id_movimiento 
              //               order by B.Barrio DESC, P.domicilio DESC, P.manzana DESC, P.lote DESC, P.familia DESC,
              //                     P.domicilio DESC, P.apellido DESC, M.fecha DESC, M.id_movimiento DESC";
              $Consulta .= " group by M.id_persona 
                             order by esPersona DESC, B.Barrio DESC, P.domicilio DESC, P.manzana DESC, P.lote DESC, P.familia DESC,
                                   P.domicilio DESC, P.apellido DESC, M.fecha DESC, M.id_movimiento DESC";
            } else {
              // SE PUEDE ROMPER
              $Consulta .= " group by M.id_persona 
                             order by B.Barrio DESC, P.domicilio DESC , P.manzana DESC, P.lote DESC, P.familia DESC,
                                      P.domicilio DESC, P.apellido DESC, M.fecha DESC, M.id_movimiento DESC";
              //$Consulta .= " group by M.id_persona order by P.domicilio, P.apellido, M.id_movimiento";
            }
            //$Con->CloseConexion();

            // $Consulta .= " group by M.id_persona order by Anio, Mes, B.Barrio, P.domicilio, P.manzana, P.lote, P.familia, P.domicilio, P.apellido, M.id_movimiento";

            $MensajeError = "No se pudieron consultar los Datos";

            $Etiqueta_Fecha_Inicio = implode("-", array_reverse(explode("-", $Fecha_Inicio)));
            $Etiqueta_Fecha_Fin = implode("-", array_reverse(explode("-", $Fecha_Fin)));

            ?>
            <center>
              <p class="LblForm">ENTRE: <?php echo $Etiqueta_Fecha_Inicio . " Y " . $Etiqueta_Fecha_Fin; ?></p>
            </center>
            <!-- <span><i class="fa fa-filter"></i> Filtros </span> -->
            <span> Filtros seleccionados </span>
            <!-- < ?php echo "DEBUG: ".$Consulta; ?>       -->
            <?php
            // echo "DEBUG: ".$Consulta;
          
            foreach ($filtros as $value) {
              echo "<span class='etFiltros'>" . $value . "</span> ";

            }

            ?>

          </div>
          <div class="col">
            <button type="button" class="btn btn-danger" style="margin-left: 35%;"  onclick="location.href = 'view_general_new.php'">Atrás</button>
            <!--<button type="button" class="btn btn-secondary" onclick="enviarImprimir()">**Imprimir</button>-->
            <!--button type="button" class="btn btn-secondary" onclick="enviarImprimirPdf();"> Imprimir</button>-->
            <!--<button type="button" class="btn btn-secondary" data-toggle="modal" style="background-color: #ffc6b1; color: black; border-color: white; " data-target="#map-modal">S. I. G.</button>-->
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#configModal">Config</button>
          </div>

        </div>
        <br>
        <div class="row">
          <div class="offset-md-3 col-md-6">
            <?php echo NOMBRE_ENTIDAD ?>
          </div>
        </div>
          <div class="col-md-12">

            <div class="table-responsive" id="tabla-responsive">
              <?php
              //$Con = new Conexion();
              //$Con->OpenConexion();

              $tomarRetTodos = array();
              //$Con->ResultSet = mysqli_query($Con->Conexion, $Consulta) or die($MensajeError . " Consulta: " . $Consulta);
              $Ejecutar_Consulta_general = mysqli_query($Con->Conexion, $Consulta) or die("Error al consultar datos");
              $Con->ResultSet = $Ejecutar_Consulta_general;
            
              /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              // TOMANDO LOS ID DE LOS MOVIMIENTOS PARA LUEGO HACER LA COMPARACION PARA EL PINTADO DE LOS MOTIVOS.
              //$ResultadosPrincipal = $Con->ResultSet->fetch_array();
              // echo "DEBUG DATOS IDS: ".var_dump($ResultadosPrincipal[5]);
            
              //$arrIDMovimientos = array();

              // var_dump($ResultadosPrincipal["Manzana"]);
            
              // if($ResultadosPrincipal->num_rows > 1){
            
              // }
            
              // foreach($ResultadosPrincipal as $valor){   
              //     // echo var_dump($valor);               
              //     $arrIDMovimientos[] = $value["id_movimiento"];
              //     //TODO: revisar bien esto
              //     // $arrIDMovimientos[] = $value;
              // }

              /*while ($Ret = $Con->ResultSet->fetch_assoc()) {
                // echo "DEBUG :".$Ret['id_movimiento'];
                $arrIDMovimientos[] = $Ret['id_movimiento'];
              }*/

              // echo "DEBUG IDS:".var_dump($arrIDMovimientos);
            

              /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////   TABLA HEAD    ////////////////////////////////////////////////////////
              /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              $IndexCelda = 0;
              $nroColumnas = 0;
              //if ($Con->ResultSet->num_rows == 0) {
              if ($Ejecutar_Consulta_general->num_rows == 0) {
                // echo "<div class = 'col'></div>";
                echo "<div class = 'col-6'>";
                echo "<p class = 'TextoSinResultados'>No se encontraron Resultados</p>";
                echo "</div>";
                // echo "<div class = 'col'></div>";
              } else {


                // $Manzana_sel=true;
                // $Lote_sel=true;
                // $Familia_sel=true;
                
                $Table = "<table class='table table-fixeder table-bordered table-sm' cellspacing='0' id='tablaMovimientos' style='page-break-after:always;'>
                             <thead class='thead-dark'>
                              <tr align='center' valign='middle'>
                                <th id='Contenido-Titulo-1'>Barrio</th>
                                <th id='Contenido-Titulo-2'>Direc.</th>";
                $Table_imprimir = "<table cellspacing='0' id='tablaMovimientos' style='page-break-after:always;'>
                                    <thead>
                                      <tr align='center' valign='middle'>
                                      <th id='Contenido-Titulo-1'>Barrio</th>
                                      <th id='Contenido-Titulo-2'>Direc.</th>";
                $jsonTable = array();
                $mesesHeader = array();
                $nroColumnas += 2;

                /*
                if ($Manzana == "manzana") {
                  $Table .= "<th id='Contenido-Titulo-5' name='datosflia' style='max-width: 50px;'>Mz.</th>";
                  $Table_imprimir .= "<th id='Contenido-Titulo-5' name='datosflia'>Mz.</th>";
                  $nroColumnas += 1;
                }
                if ($Lote == "lote") {
                  $Table .= "<th id='Contenido-Titulo-6' name='datosflia' style='max-width: 50px;'>Lote</th>";
                  $Table_imprimir .= "<th id='Contenido-Titulo-6' name='datosflia'>Lote</th>";
                  $nroColumnas += 1;
                }
                if ($Familia == "familia") {
                  $Table .= "<th id='Contenido-Titulo-7' name='datosflia' style='max-width: 50px;'>Sublote</th>";
                  $Table_imprimir .= "<th id='Contenido-Titulo-7' name='datosflia'>Sublote</th>";
                  $nroColumnas += 1;
                }
                */
                $Table .= "<th id='Contenido-Titulo-5' name='datosflia' style='max-width: 50px; display: none'>Mz.</th>";
                $nroColumnas += 1;
                $Table .= "<th id='Contenido-Titulo-6' name='datosflia' style='max-width: 50px; display: none'>Lote</th>";
                $nroColumnas += 1;
                $Table .= "<th id='Contenido-Titulo-7' name='datosflia' style='max-width: 70px; display: none'>Sublote</th>";
                $nroColumnas += 1;
                $Table .= "<th id='Contenido-Titulo-3'>Persona</th>
                <th id='Contenido-Titulo-4' style='min-width: 120px;'>Fecha Nac.</th>";
                $nroColumnas += 2;           
                $Table .= "<th id='Contenido-Titulo-8' name='datosflia' style='max-width: 70px; display: none'>edad en años</th>";
                $nroColumnas += 1;
                $Table .= "<th id='Contenido-Titulo-9' name='datosflia' style='max-width: 70px; display: none'>meses</th>";
                $nroColumnas += 1;
              }

              $Tomar_Meses = mysqli_query($Con->Conexion, $Consulta) or die($MensajeError . " Consulta: " . $Consulta);

              /* TOMAR LOS MESES ENTRE LAS FECHAS  */
              $MesFecha_Inicio = new DateTime($Fecha_Inicio);
              $MesFecha_Fin = new DateTime($Fecha_Fin);

              $MesesDiferencia = $MesFecha_Inicio->diff($MesFecha_Fin);

              $MesesDiferencia = ($MesesDiferencia->y * 12) + $MesesDiferencia->m + 1;

              $Mes_Actual_Bandera = (int) $MesFecha_Inicio->format("m");
              $Anio_Actual_Bandera = $MesFecha_Inicio->format("y");
              for ($i = 0; $i < $MesesDiferencia; $i++) {
                if ($Mes_Actual_Bandera > 12) {
                  $Mes_Actual_Bandera = 01;
                  $Anio_Actual_Bandera++;
                }
                $arr[] = $Mes_Actual_Bandera . "/" . $Anio_Actual_Bandera;
                $Mes_Actual_Bandera++;
              }
              //$arr = array_reverse($arr);
              $nroColumnas += $MesesDiferencia;            

              /*             FIN TOMAR MESES */

              // while($RetMeses = mysqli_fetch_array($Tomar_Meses)){
              // 	$Bandera = 0;
              // 	//COMPARANDO LOS VALORES PARA NO TENER CAMPOS REPETIDOS
              // 	foreach ($arr as $key => $value) {
              // 		$Dato_Nuevo = $RetMeses["Mes"]."/".$RetMeses["Anio"];
              // 		if(strcmp($Dato_Nuevo, $value) == 0){
              // 			$Bandera = 1;              			
              // 		}else{
              // 			$Bandera = 0;              			
              // 		}              		
              // 	}
              //   //ACOMODANDO LAS FECHAS EN UN ARREGLO PARA CREAR LAS FILAS
              //     if($Bandera == 0){
              //       $arr[] = $RetMeses["Mes"]."/".$RetMeses["Anio"];                    
              //     }              	
            
              // }             
              // $arr_reverse = array_reverse($arr);
            
              // var_dump($Table);
              // array(13) { [0]=> string(5) "12/21" [1]=> string(4) "1/22" [2]=> string(4) "2/22" [3]=> string(4) "3/22"
              //    [4]=> string(4) "4/22" [5]=> string(4) "5/22" [6]=> string(4) "6/22" [7]=> string(4) "7/22"
              //     [8]=> string(4) "8/22" [9]=> string(4) "9/22" [10]=> string(5) "10/22" [11]=> string(5) "11/22" [12]=> string(5) "12/22" }
              foreach ($arr as $key => $value) {

                if ($value != "") {
                  // TODO: Cambiando de tamaño las columnas
                  $Table .= "<th name='DatosResultados' style='min-width: 190px;'>" . $value . "</th>";
                  $Table_imprimir .= "<th name='DatosResultados'>" . $value . "</th>";
                  $mesesHeader[] = $value;
                }

              }

              // ob_start();   
            
              $Table .= "</tr>
                    </thead>
                <tbody id='cuerpo-tabla'>";

              $Table_imprimir .= "</tr>
                              </thead>
                            <tbody id='cuerpo-tabla'>";

              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////  // FIN TABLE HEADER //////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            

              //	CREANDO FILTRO MOSTRAR - Mostrar = "0" Con Movimientos,  "1" sin movimientos.
              if ($Mostrar > 0) {
                //, P.nro_legajo, P.nro_carpeta
                $ConsultarTodos = "select P.id_persona, B.Barrio, P.manzana, P.lote, P.familia, 
                                          P.apellido, P.nombre, P.fecha_nac, P.domicilio
                                   from persona P, 
                                        barrios B, 
                                        movimiento M
                                   where not exists(select * 
                                                    from movimiento M2 
                                                    where M2.id_persona = P.id_persona) 
                                      and B.ID_Barrio = P.ID_Barrio 
                                      and P.estado = 1";

                if ($ID_Persona > 0) {
                  $ConsultarTodos .= " and P.id_persona = $ID_Persona";
                }

                if ($Edad_Desde != null && $Edad_Desde != "" && $Edad_Hasta != null && $Edad_Hasta != "") {
                  $ConsultarTodos .= " and P.edad > $Edad_Desde and P.edad < $Edad_Hasta";
                }

                if ($Meses_Desde != null && $Meses_Desde != "" && $Meses_Hasta != null && $Meses_Hasta != "") {
                  //$ConsultarTodos .= " and P.edad = 0 and P.meses > $Meses_Desde and P.meses < $Meses_Hasta";
                  $Consulta .= " and P.meses > $Meses_Desde and P.meses < $Meses_Hasta";
                }

                if ($Domicilio != null && $Domicilio != "") {
                  $ConsultarTodos .= " and P.domicilio like '%$Domicilio%'";
                }
                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                if ($Manzana != null && $Manzana != "") {
                  $ConsultarTodos .= " and P.manzana = '$Manzana'";
                }

                if ($Lote != null && $Lote != "") {
                  $ConsultarTodos .= " and P.lote = $Lote";
                }

                if ($Familia != null && $Familia != "") {
                  $ConsultarTodos .= " and P.familia = $Familia";
                }
                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
                if ($Nro_Carpeta != null && $Nro_Carpeta != "") {
                  $ConsultarTodos .= " and P.nro_carpeta = $Nro_Carpeta";
                }

                if ($Nro_Legajo != null && $Nro_Legajo != "") {
                  $ConsultarTodos .= " and P.nro_legajo = $Nro_Legajo";
                }
                if (count($Barrio) > 1) {
                  $filtroBarrios = 'Barrios:';
                  foreach ($Barrio as $key => $valueBarrio) {
                    if ($key == $Barrio->array_key_first) {
                      $ConsultarTodos .= " and (";
                    }
                    if ($valueBarrio > 0) {
                      if ($key === count($Barrio) - 1) {
                        $ConsultarTodos .= " P.ID_Barrio = $valueBarrio )";
                      } else {
                        $ConsultarTodos .= " P.ID_Barrio = $valueBarrio or";
                      }
                      $ConsultarBarrio = "select Barrio from barrios where ID_Barrio = " . $valueBarrio . " limit 1";
                      $EjecutarConsultarBarrio = mysqli_query($Con->Conexion, $ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                      $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
                      if ($key == $Barrio->array_key_first) {
                        $filtroBarrios .= " " . $RetConsultarBarrio['Barrio'];
                      } else {
                        $filtroBarrios .= " - " . $RetConsultarBarrio['Barrio'];
                      }
                    }
                  }
                  $filtros[] = $filtroBarrios;
                } else {
                  if ($Barrio[0] > 0) {
                    $ConsultarTodos .= " and P.ID_Barrio = $Barrio[0]";
                    $ConsultarBarrio = "select Barrio from barrios where ID_Barrio = " . $Barrio[0] . " limit 1";
                    $EjecutarConsultarBarrio = mysqli_query($Con->Conexion, $ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                    $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
                    $filtros[] = "Barrio: " . $RetConsultarBarrio['Barrio'];
                    $json_filtro[] = "Barrio " . $RetConsultarBarrio['Barrio'];
                  }
                }

                if ($ID_Escuela > 0) {
                  $ConsultarTodos .= " and P.ID_Escuela = $ID_Escuela";
                }

                if ($Trabajo != null && $Trabajo != "") {
                  $ConsultarTodos .= " and P.Trabajo like '%$Trabajo%'";
                }

                if ($ID_Persona > 0) {
                  $ConsultarTodos .= " group by P.id_movimiento 
                                       order by B.Barrio DESC, P.domicilio DESC, P.apellido DESC, P.nombre DESC";
                } else {
                  $ConsultarTodos .= " group by P.id_persona 
                                       order by B.Barrio DESC, P.domicilio DESC, P.apellido DESC, P.nombre DESC";
                }

                // $ConsultarTodos .= " group by P.id_persona order by P.apellido, P.nombre";            

                $MensajeErrorTodos = "No se pudieron consultar los datos de todas las personas";

                $EjecutarConsultarTodos = mysqli_query($Con->Conexion, $ConsultarTodos) or die($MensajeErrorTodos);

                // CAMBIOS CON TODOS                
                // $tomarRetTodos = mysqli_fetch_array($EjecutarConsultarTodos);

                while ($RetTodos = mysqli_fetch_assoc($EjecutarConsultarTodos)) {
                  // PASAR A TODOS
                  // if($RetTodos["fecha_nac"] == 'null'){
                  //   $Fecha_Nacimiento = "Sin Datos";
                  // }else{
                  //   $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$RetTodos["fecha_nac"])));
                  // }
            
                  // $Table .= "<tr class='SinMovimientos Datos'>";
                  // $Table .= "<td id='Contenido-1'>".$RetTodos["Barrio"]."</td><td id='Contenido-2'>".$RetTodos["domicilio"]."</td><td id='Contenido-3' name='datosflia' style='max-width: 50px;'>".$RetTodos["manzana"]."</td><td id='Contenido-4' name='datosflia' style='max-width: 50px;'>".$RetTodos["lote"]."</td><td id='Contenido-5' name='datosflia' style='max-width: 50px;'>".$RetTodos["familia"]."</td><td id='Contenido-6'><a href = 'javascript:window.open(\"view_modpersonas.php?ID=".$RetTodos["id_persona"]."\",\"Ventana".$RetTodos["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$RetTodos["apellido"].", ".$RetTodos["nombre"]."</a></td><td id='Contenido-7' style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";
            
                  // $ColSpans = $MesesDiferencia * 270;
                  // $Table .= "<td style='width:".$ColSpans."px'></td>";

                  //La abreviatura SM indica persona sin movimiento
                  $RetTodos['tipo'] = "SM";
                  $tomarRetTodos[] = $RetTodos;

                }
              }
              //$EjecutarConsulta2 = mysqli_query($Con->Conexion, $Consulta) or die("Error al consultar datos");
              // while ($Ret = mysqli_fetch_array($Con->ResultSet)) {                     
            
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
            

              while ($Ret = mysqli_fetch_array($Ejecutar_Consulta_general)) {

                // echo "A";
                // echo var_dump($Ret);                
                if ($Ret["fecha_nac"] == 'null') {
                  $Fecha_Nacimiento = "Sin Datos";
                } else {
                  $Fecha_Nacimiento = implode("-", array_reverse(explode("-", $Ret["fecha_nac"])));
                }

                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
                /////////////////////////////////// CODIGICANDO EL MUESTREO DE MESES DE UNA FORMA MAS EFICIENTE //////////////////////               
            
                $ID_Persona_Nuevo = $Ret["id_persona"];
                // PASAR A TODOS              
                // if($ID_Persona_Nuevo == $ID_Persona_Bandera){                                                 
                //   $Table .= "<tr style='border: 0px;'>";
                //   $Table .= "<td colspan = '7' style='border: 0px;'></td>";   
            
                // }else{                               
                //   $Table .= "<tr class='Datos'>";
                //   $Table .= "<td id='Contenido-1'>".$Ret["Barrio"]."</td><td id='Contenido-2'>".$Ret["domicilio"]."</td><td id='Contenido-3' name='datosflia' style='max-width: 50px;'>".$Ret["manzana"]."</td><td id='Contenido-4' name='datosflia' style='max-width: 50px;'>".$Ret["lote"]."</td><td id='Contenido-5' name='datosflia' style='max-width: 50px;'>".$Ret["familia"]."</td><td id='Contenido-6'><a href = 'javascript:window.open(\"view_modpersonas.php?ID=".$Ret["id_persona"]."\",\"Ventana".$Ret["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$Ret["apellido"].", ".$Ret["nombre"]."</a></td><td id='Contenido-7' style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";
                // }
            
                $ID_Persona_Bandera = $Ret["id_persona"];
                //if ($ID_Persona_Nuevo !== $ID_Persona_Bandera) {
                  // foreach ($arr as $key => $value) {
                  //     $Separar = explode("/",$value);
                  //     $Mes = $Separar[0];
                  //     $Anio = $Separar[1];                                          
                  //     $Consultar_Movimientos_Persona = "select * from movimiento where id_persona = ".$Ret["id_persona"]." and MONTH(fecha) = ".$Mes." and YEAR(fecha) like '%".$Anio."'";
            

                  //     $Tomar_Movimientos_Persona = mysqli_query($Con->Conexion,$Consultar_Movimientos_Persona) or die($MensajeErrorConsultar_Mov_Persona." - ".$Consultar_Movimientos_Persona);
            
                  // echo var_dump($arr);
            
                  // PASAR A TODOS
                  // $Table .= "<td name='DatosResultados' style='min-width:270px'><div class = 'row'>";                    
            
                  // while($Ret_Movimientos_Persona = mysqli_fetch_assoc($Tomar_Movimientos_Persona)){	                	
            
                  //   $Num_Movimientos_Persona = mysqli_num_rows($Tomar_Movimientos_Persona);
                  //echo $Ret_Movimientos_Persona['id_persona']." - ".$Ret_Movimientos_Persona['id_movimiento'];
            

                  // $Consultar_Datos_Movimientos = "select M.id_movimiento, MONTH(M.fecha) as 'Mes', YEAR(M.fecha) as 'Anio', M.motivo_1, M.motivo_2, M.motivo_3 from movimiento M, motivo MT, categoria C where (M.motivo_1 = MT.id_motivo or M.motivo_2 = MT.id_motivo or M.motivo_3 = MT.id_motivo) and MT.cod_categoria = C.cod_categoria and M.id_movimiento = ".$Ret_Movimientos_Persona['id_movimiento']." and M.id_persona = ".$Ret_Movimientos_Persona['id_persona']." group by M.id_movimiento";	                      
            


                  // $MensajeErrorConsultar_Datos_Movimientos = "No se pudieron consultar los datos del movimiento";
                  // $Tomar_Datos_Movimientos = mysqli_query($Con->Conexion,$Consultar_Datos_Movimientos) or die($MensajeErrorConsultar_Datos_Movimientos." - ".$Consultar_Datos_Movimientos);
                  // $Ret_Datos_Movimiento = mysqli_fetch_assoc($Tomar_Datos_Movimientos);
            
                  ////////////////////////////////////////////////////////////////                                             
                  ////////////////////////////////////////////////////////////////
                  ////////////////////////////////////////////////////////////////
            
                  // if($Ret_Datos_Movimiento["motivo_1"] > 1){
                  //   if($ID_Motivo > 0){
                  //     if($ID_Motivo == $Ret_Datos_Movimiento["motivo_1"]){
                  //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                  //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            
                  //       //echo $ConsultarCodyColor;               
            
                  //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
            
                  //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
            
                  //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                  //     }
                  //   }elseif($ID_Motivo2 > 0){
                  //     if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_1"]){
                  //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                  //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            
                  //       //echo $ConsultarCodyColor;               
            
                  //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
            
                  //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
            
                  //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                  //     }
                  //   }elseif($ID_Motivo3 > 0){
                  //     if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_1"]){
                  //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                  //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            
                  //       //echo $ConsultarCodyColor;               
            
                  //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
            
                  //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
            
                  //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                  //     }
                  //   }else{                                                        
                  //     $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                  //     $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            
                  //     //echo $ConsultarCodyColor;               
            
                  //     $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
            
                  //     $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
            
                  //     $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; padding: 0px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";
                  //   }
                  // }
            
                  // if($Ret_Datos_Movimiento["motivo_2"] > 1){
                  //   if($ID_Motivo > 0){
                  //     if($ID_Motivo == $Ret_Datos_Movimiento["motivo_2"]){
                  //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                  //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            
                  //       //echo $ConsultarCodyColor;               
            
                  //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_2"]);
            
                  //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
            
                  //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                  //     }
                  //   }elseif($ID_Motivo2 > 0){
                  //     if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_2"]){
                  //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                  //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            
                  //       //echo $ConsultarCodyColor;               
            
                  //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_2"]);
            
                  //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
            
                  //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                  //     }
                  //   }elseif($ID_Motivo3 > 0){
                  //     if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_2"]){
                  //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                  //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            
                  //       //echo $ConsultarCodyColor;               
            
                  //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_2"]);
            
                  //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
            
                  //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                  //     }
                  //   }else{ 
                  //     $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                  //     $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            

                  //     $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor);
            
                  //     $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
            


                  //     $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"]."; text-align= center;'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";
                  //   }
                  // }
            

                  // if($Ret_Datos_Movimiento["motivo_3"] > 1){
                  //   if($ID_Motivo > 0){
                  //     if($ID_Motivo == $Ret_Datos_Movimiento["motivo_3"]){
                  //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                  //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            
                  //       //echo $ConsultarCodyColor;               
            
                  //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_3"]);
            
                  //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
            
                  //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                  //     }
                  //   }elseif($ID_Motivo2 > 0){
                  //     if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_3"]){
                  //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                  //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            
                  //       //echo $ConsultarCodyColor;               
            
                  //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_3"]);
            
                  //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
            
                  //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                  //     }
                  //   }elseif($ID_Motivo3 > 0){
                  //     if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_3"]){
                  //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                  //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            
                  //       //echo $ConsultarCodyColor;               
            
                  //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_3"]);
            
                  //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
            
                  //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
                  //     }
                  //   }else{ 
                  //     $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                  //     $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            

                  //     $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor);
            
                  //     $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
            

                  //     $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";
                  //   }
                  // }     
                  ////////////////////////////////////////////////////////////////                                             
                  ////////////////////////////////////////////////////////////////
                  ////////////////////////////////////////////////////////////////
            
                  // }   
            
                  // $Table .= "</div></td>";
            
                  //$ID_Persona_Bandera = $Ret["id_persona"];



                //}

                //La abreviatura CM indica persona con movimiento
                $Ret['tipo'] = "CM";
                $tomarRetTodos[] = $Ret;
              }

              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            



              // SE PINTO ESTE CIERRE
              // $Table .= "</tr>";
            
              //////////////////////////////////////////////////////////////////////////////////
              // ACA REBERIA GRAFICAR EL ARREGLO DE LOS REGISTROS UNIDOS
              //////////////////////////////////////////////////////////////////////////////////
            
              /*
              foreach ($tomarRetTodos as $clave => $reg) {
                $regdomicilio[$clave] = $reg['domicilio'];
              }
              */
              //array_multisort($regdomicilio, SORT_DESC, $tomarRetTodos);

              //echo "DEBUG 1: ".var_dump($tomarRetTodos);    
              foreach ($tomarRetTodos as $clave => $RetTodos) {
                // echo var_dump($RetTodos);
                // echo "<br>";
                if ($RetTodos["fecha_nac"] == 'null') {
                  $Fecha_Nacimiento = "Sin Datos";
                } else {
                  $Fecha_Nacimiento = implode("-", array_reverse(explode("-", $RetTodos["fecha_nac"])));
                }

                if ($RetTodos["tipo"] == "SM") {
                  // echo "Entra aca SM";
                  $Table .= "<tr class='SinMovimientos Datos'>";
                  $Table_imprimir .= "<tr>";

                  $Table_imprimir .= "<td id='Contenido-1'>" . $RetTodos["Barrio"] . "</td>
                                      <td id='Contenido-2'>" . $RetTodos["domicilio"] . "</td>";
                  $jsonTable[$clave]["barrio"] = $RetTodos["Barrio"];
                  $jsonTable[$clave]["domicilio"] = $RetTodos["domicilio"];
                  $Table .= "<td id='Contenido-1' style='max-width: 100px;'>" . $RetTodos["Barrio"] . "</td>
                             <td id='Contenido-2' style='max-width: 100px;'>" . $RetTodos["domicilio"] . "</td>";
                  /*
                  if ($Manzana == "manzana") {
                    $Table .= "<td id='Contenido-5' name='datosflia' style='max-width: 50px;'>" . $RetTodos["manzana"] . "</td>";
                    $Table_imprimir .= "<td id='Contenido-5' name='datosflia' style='max-width: 100px;'>" . $RetTodos["manzana"] . "</td>";

                  }

                  if ($Lote == "lote") {
                    $Table .= "<td id='Contenido-6' name='datosflia' style='max-width: 50px;'>" . $RetTodos["lote"] . "</td>";
                    $Table_imprimir .= "<td id='Contenido-6' name='datosflia' style='max-width: 100px;'>" . $RetTodos["lote"] . "</td>";
                  }

                  if ($Familia == "familia") {
                    $Table .= "<td id='Contenido-7' name='datosflia' style='max-width: 60px;'>" . $RetTodos["familia"] . "</td>";
                    $Table_imprimir .= "<td id='Contenido-7' name='datosflia' style='max-width: 100px;'>" . $RetTodos["familia"] . "</td>";
                  }
                  */
                  $Table .= "<td id='Contenido-5' name='datosflia' style='max-width: 50px; display: none'>" . $RetTodos["manzana"] . "</td>";
                  $Table_imprimir .= "<td id='Contenido-5' name='datosflia' style='max-width: 100px;'>" . $RetTodos["manzana"] . "</td>";
                  $jsonTable[$clave]["manzana"] = $RetTodos["manzana"];
                  $Table .= "<td id='Contenido-6' name='datosflia' style='max-width: 50px; display: none'>" . $RetTodos["lote"] . "</td>";
                  $Table_imprimir .= "<td id='Contenido-6' name='datosflia' style='max-width: 100px;'>" . $RetTodos["lote"] . "</td>";
                  $jsonTable[$clave]["lote"] = $RetTodos["lote"];
                  $Table .= "<td id='Contenido-7' name='datosflia' style='max-width: 70px; display: none'>" . $RetTodos["familia"] . "</td>";
                  $Table_imprimir .= "<td id='Contenido-7' name='datosflia' style='max-width: 100px;'>" . $RetTodos["familia"] . "</td>";
                  $jsonTable[$clave]["familia"] = $RetTodos["familia"];
                  $Table .= " <td id='Contenido-3' style='overflow: hidden;'>
                                <div style='position: relative;z-index: 1000;'>
                                  <a href = 'javascript:window.open(\"view_modpersonas.php?ID=" . $RetTodos["id_persona"] . "\",\"Ventana" . $RetTodos["id_persona"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>" . 
                                    $RetTodos["apellido"] . ", " . $RetTodos["nombre"] . "
                                  </a>
                                </div>
                              </td>
                              <td id='Contenido-4' style='min-width: 120px;'>" . 
                                $Fecha_Nacimiento . "
                              </td>";
                  
                  $Table_imprimir .= " <td id='Contenido-3'>" . $RetTodos["apellido"] . ", " . $RetTodos["nombre"] . "</td>
                                       <td id='Contenido-4' style='max-width: 100px;'>" . $Fecha_Nacimiento . "</td>";
                  $jsonTable[$clave]["persona"] = $RetTodos["apellido"] . ", " . $RetTodos["nombre"];
                  $jsonTable[$clave]["id_persona"] = $RetTodos["id_persona"];
                  $jsonTable[$clave]["fechanac"] = $Fecha_Nacimiento;
                  $ColSpans = $MesesDiferencia * 270;
                  $Table .= "<td name='DatosSinResultados' style='width:" . $ColSpans . "px'></td>";
                  $Table_imprimir .= "<td name='DatosSinResultados' style='max-width: 100px;'></td>";
                } else {
                  //En este punto se cominza a procesar los movimientos asociados a una persona persona

                  // ACA IRIA TODO LO OTRO PARA LOS QUE SI TIENEN MOVIMIENTOS
                  $ID_Persona_Nuevo = $RetTodos["id_persona"];

                  // POSIBLE BUG
                  // if($ID_Persona_Nuevo == $ID_Persona_Bandera){                                                 
                  //   $Table .= "<tr style='border: 0px;'>";
                  //   $Table .= "<td colspan = '7' style='border: 0px;'></td>";                     
                  // }else{                               
                  //   $Table .= "<tr class='Datos'>";
                  //   $Table .= "<td id='Contenido-1'>".$RetTodos["Barrio"]."</td><td id='Contenido-2'>".$RetTodos["domicilio"]."</td><td id='Contenido-3' name='datosflia' style='max-width: 50px;'>".$RetTodos["manzana"]."</td><td id='Contenido-4' name='datosflia' style='max-width: 50px;'>".$RetTodos["lote"]."</td><td id='Contenido-5' name='datosflia' style='max-width: 50px;'>".$RetTodos["familia"]."</td><td id='Contenido-6'><a href = 'javascript:window.open(\"view_modpersonas.php?ID=".$RetTodos["id_persona"]."\",\"Ventana".$RetTodos["id_persona"]."\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$RetTodos["apellido"].", ".$RetTodos["nombre"]."</a></td><td id='Contenido-7' style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";
                  // }

                  //$Table_imprimir = (isset($Table_imprimir))? $Table_imprimir: $Table;
                  if (!isset($Table_imprimir)){
                    $Table_imprimir = $Table;
                  }
                  $tagsTD = "";
                  $tagsTD_imprimir ="";
                  $tdExtenso = false;

                  $Table .= "<tr class='Datos'>";
                  $Table_imprimir .= "<tr style='text-align:center;'>";
                  $nroColumnas = 70;
                  $tagsTD .= "<td id='Contenido-1'>" . $RetTodos["Barrio"] . "</td>
                              <td id='Contenido-2'>" . $RetTodos["domicilio"] . "</td>";
                  $tagsTD_imprimir .= "<td id='Contenido-1' style='text-align:center;font-size: 10px;max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;'>" . $RetTodos["Barrio"] . "</td>
                                       <td id='Contenido-2' style='text-align:center;font-size: 10px;max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;'>" . $RetTodos["domicilio"] . "</td>";
                  $jsonTable[$clave]["barrio"] = $RetTodos["Barrio"];
                  $jsonTable[$clave]["domicilio"] = $RetTodos["domicilio"];
                  $jsonTable[$clave]["lat"] = $RetTodos["lat"];
                  $jsonTable[$clave]["lon"] = $RetTodos["lon"];

                  /*
                  if ($Manzana == "manzana") {
                    $tagsTD .= "<td id='Contenido-5' name='datosflia' style='max-width: 50px;'>" . $RetTodos["manzana"] . "</td>";
                    $tagsTD_imprimir .= "<td id='Contenido-5' name='datosflia' style='max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;;font-size: 10px;'>" . $RetTodos["manzana"] . "</td>";
                  }

                  if ($Lote == "lote") {
                    $tagsTD .= "<td id='Contenido-6' name='datosflia' style='max-width: 50px;'>" . $RetTodos["lote"] . "</td>";
                    $tagsTD_imprimir .= "<td id='Contenido-6' name='datosflia' style='max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;;font-size: 10px;'>" . $RetTodos["lote"] . "</td>";
                  }

                  if ($Familia == "familia") {
                    $tagsTD .= "<td id='Contenido-7' name='datosflia' style='max-width: 60px;'>" . $RetTodos["familia"] . "</td>";
                    $tagsTD_imprimir .= "<td id='Contenido-7' name='datosflia' style='max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;;font-size: 10px;'>" . $RetTodos["familia"] . "</td>";
                  }
                  */
                  $tagsTD .= "<td id='Contenido-5' name='datosflia' style='max-width: 50px; display: none'>" . $RetTodos["manzana"] . "</td>";
                  $tagsTD_imprimir .= "<td id='Contenido-5' name='datosflia' style='max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;;font-size: 10px;'>" . $RetTodos["manzana"] . "</td>";
                  $jsonTable[$clave]["manzana"] = $RetTodos["manzana"];
                  $tagsTD .= "<td id='Contenido-6' name='datosflia' style='max-width: 50px; display: none'>" . $RetTodos["lote"] . "</td>";
                  $tagsTD_imprimir .= "<td id='Contenido-6' name='datosflia' style='max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;;font-size: 10px;'>" . $RetTodos["lote"] . "</td>";
                  $jsonTable[$clave]["lote"] = $RetTodos["lote"];
                  $tagsTD .= "<td id='Contenido-7' name='datosflia' style='max-width: 70px; display: none'>" . $RetTodos["familia"] . "</td>";
                  $tagsTD_imprimir .= "<td id='Contenido-7' name='datosflia' style='max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;;font-size: 10px;'>" . $RetTodos["familia"] . "</td>";
                  $jsonTable[$clave]["familia"] = $RetTodos["familia"];
                  $tagsTD .= "
                  <td id='Contenido-3' style='overflow: hidden;'>
                    <div style='position: relative;z-index: 1000;'>
                      <a href = 'javascript:window.open(\"view_modpersonas.php?ID=" . $RetTodos["id_persona"] . "\",\"Ventana" . $RetTodos["id_persona"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>" . 
                        $RetTodos["apellido"] . ", " . $RetTodos["nombre"] . "
                      </a>
                    </div>
                  </td>
                  <td id='Contenido-4' style='min-width: 120px;'>" .
                    $Fecha_Nacimiento . "
                  </td>";

                  $tagsTD_imprimir .= "<td id='Contenido-3' style='text-align:center;font-size: 10px;max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;'>". 
                                         $RetTodos["apellido"] . ", " . $RetTodos["nombre"] . "
                                       </td>
                                       <td id='Contenido-4' style='text-align:center;max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;;font-size: 10px;'>" . 
                                         $Fecha_Nacimiento . "
                                       </td>";
                  $jsonTable[$clave]["persona"] = $RetTodos["apellido"] . ", " . $RetTodos["nombre"];
                  $jsonTable[$clave]["id_persona"] = $RetTodos["id_persona"];
                  $jsonTable[$clave]["fechanac"] = $Fecha_Nacimiento;
                  $tagsTD .= "<td id='Contenido-8' name='datosflia' style='max-width: 70px; display: none; text-align: center; background-color: white;'>" . $RetTodos["edad"] . "</td>";
                  $tagsTD_imprimir .= "<td id='Contenido-8' name='datosflia' style='max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;;font-size: 10px;'>" .  $RetTodos["edad"] . "</td>";
                  $jsonTable[$clave]["edad"] = $RetTodos["edad"];
                  $tagsTD .= "<td id='Contenido-9' name='datosflia' style='max-width: 70px; display: none; text-align: center;  background-color: white;'>" . $RetTodos["meses"] . "</td>";
                  $tagsTD_imprimir .= "<td id='Contenido-9' name='datosflia' style='max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;;font-size: 10px;'>" . $RetTodos["meses"] . "</td>";
                  $jsonTable[$clave]["meses"] = $RetTodos["meses"];
                  foreach ($arr as $key => $value) {
                    $Separar = explode("/", $value);
                    $Mes = $Separar[0];
                    $Anio = $Separar[1];
                    $Consultar_Movimientos_Persona = "select M.id_movimiento,
                                                             M.motivo_1, 
                                                             max(M.motivo_1 = MI.id_motivo) as permiso_1,
                                                             M.motivo_2, 
                                                             max(M.motivo_2 = MI.id_motivo) as permiso_2,
                                                             M.motivo_3, 
                                                             max(M.motivo_3 = MI.id_motivo) as permiso_3,
                                                             M.motivo_4, 
                                                             max(M.motivo_4 = MI.id_motivo) as permiso_4,
                                                             M.motivo_5,
                                                             max(M.motivo_5 = MI.id_motivo) as permiso_5
                                                      from movimiento M,
                                                           motivo MT,
                                                           categoria C,
                                                           PERMISOS MI
                                                      where M.id_persona = " . $RetTodos["id_persona"] . " 
                                                        and MONTH(M.fecha) = " . $Mes . " 
                                                        and YEAR(M.fecha) = 20". $Anio . "
                                                        and (M.motivo_1 = MT.id_motivo 
                                                          or M.motivo_2 = MT.id_motivo
                                                          or M.motivo_3 = MT.id_motivo
                                                          or M.motivo_4 = MT.id_motivo
                                                          or M.motivo_5 = MT.id_motivo)
                                                        $consulta_motivos
                                                        and MT.id_motivo <> 1 
                                                        and MT.cod_categoria = C.cod_categoria
                                                        $consulta_categoria
                                                        and M.estado = 1 
                                                        and MI.id_motivo = MT.id_motivo 
                                                        group by M.id_movimiento, M.motivo_1, M.motivo_2, M.motivo_3, M.motivo_4, M.motivo_5";

                    $Tomar_Movimientos_Persona = mysqli_query($Con->Conexion, $Consultar_Movimientos_Persona) or die($MensajeErrorConsultar_Mov_Persona . " - " . $Consultar_Movimientos_Persona);
                    $IndexCelda += 1;
                    $nroMotivosEnFecha = 0;
                    if(mysqli_num_rows($Tomar_Movimientos_Persona) > 6){
                      $tdExtenso = true;
                    }
                    $tagsTD .= "<td name='DatosResultados' id=$IndexCelda style='min-width:190px'>
                                 <div class = 'row' style='margin:0'>";   
                    $tagsTD_imprimir .= "<td style='max-width: {$nroColumnas}px;min-width: {$nroColumnas}px;width:{$nroColumnas}px;height:38px;'>
                                          <div style='margin-left:-52px; padding-top:4px;height:38px;'>";

                    $tagsMotivos = "";

                    //$Num_Movimientos_Persona = mysqli_num_rows($Tomar_Movimientos_Persona);

                    while ($Ret_Movimientos_Persona = mysqli_fetch_assoc($Tomar_Movimientos_Persona)) {
                      /*$Consultar_Datos_Movimientos = "select M.id_movimiento, MONTH(M.fecha) as 'Mes', YEAR(M.fecha) as 'Anio',
                                                             M.motivo_1, M.motivo_2, M.motivo_3, M.motivo_4, M.motivo_5 
                                                      from movimiento M, 
                                                           motivo MT, 
                                                           categoria C 
                                                      where (M.motivo_1 = MT.id_motivo 
                                                             or M.motivo_2 = MT.id_motivo
                                                             or M.motivo_3 = MT.id_motivo
                                                             or M.motivo_4 = MT.id_motivo
                                                             or M.motivo_5 = MT.id_motivo) 
                                                            and MT.cod_categoria = C.cod_categoria 
                                                            and M.id_movimiento = " . $Ret_Movimientos_Persona['id_movimiento'] . " 
                                                            and M.id_persona = " . $Ret_Movimientos_Persona['id_persona'] . " 
                                                      group by M.id_movimiento
                                                      order by M.fecha DESC";
                      */

                      //$MensajeErrorConsultar_Datos_Movimientos = "No se pudieron consultar los datos del movimiento";
                      //$Tomar_Datos_Movimientos = mysqli_query($Con->Conexion, $Consultar_Datos_Movimientos) or die($MensajeErrorConsultar_Datos_Movimientos . " - " . $Consultar_Datos_Movimientos);
                      //if (mysqli_num_rows($Tomar_Datos_Movimientos) == 0) {
                        //continue;
                      //}
                      //$Ret_Datos_Movimiento = mysqli_fetch_assoc($Tomar_Datos_Movimientos);
                      $Ret_Datos_Movimiento = $Ret_Movimientos_Persona;
            
                      if ($Ret_Datos_Movimiento["motivo_1"] > 1 && $Ret_Datos_Movimiento["permiso_1"]) {
                        if ($ID_Motivo > 0) {
                          if ($ID_Motivo == $Ret_Datos_Movimiento["motivo_1"]) {
                            $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color, M.codigo 
                                                   from motivo M, 
                                                        categoria C, 
                                                        formas_categorias F 
                                                    where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_1"] . " 
                                                      and M.cod_categoria = C.cod_categoria 
                                                      and C.ID_Forma = F.ID_Forma 
                                                      and M.estado = 1 and 
                                                      C.estado = 1";
                            $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";

                            $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor . " - " . $ConsultarCodyColor . " valor:" . $Ret_Datos_Movimiento["motivo_1"]);
                            $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
                            $nroMotivosEnFecha += 1;
                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                         <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                          <span style='font-size: 30px; color: " . $RetMotivo["color"] . ";'>" . 
                                            $RetMotivo["Forma_Categoria"] . "
                                            <center>
                                              <span class='nombreCategoria'>" . 
                                                $RetMotivo["codigo"] . "
                                              </span>
                                             </center>
                                          </span>
                                        </a>
                                       </div>";
                            $marginLeft = (strlen($RetMotivo["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                               <div style='font-family: DejaVu Sans, Noto Sans Symbols 2; font-size: 7px; color: " . $RetMotivo["color"] . ";'>" . 
                                                 $RetMotivo["Forma_Categoria"] . "
                                               </div>
                                               <div style='font-size: 5.5px;'>" . 
                                                 $RetMotivo["codigo"] . "
                                               </div>
                                             </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo["Forma_Categoria"], 
                              $RetMotivo["codigo"],
                              $RetMotivo["color"]
                            ];
                            $forma_motivo = $RetMotivo["Forma_Categoria"];
                            if (strlen($forma_motivo) > 1) {
                              $forma_motivo = substr($forma_motivo, 2);
                              $forma_motivo = substr($forma_motivo, 0, -1);
                            }
                            $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo["color"];

                          }
                        }
                        if ($ID_Motivo2 > 0) {
                          if ($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_1"]) {
                            $ConsultarCodyColor = "select M.cod_categoria, 
                                                          F.Forma_Categoria, 
                                                          C.color, 
                                                          M.codigo
                                                    from motivo M, 
                                                         categoria C, 
                                                         formas_categorias F 
                                                    where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_1"] . " 
                                                      and M.cod_categoria = C.cod_categoria 
                                                      and C.ID_Forma = F.ID_Forma 
                                                      and M.estado = 1 
                                                      and C.estado = 1";
                            $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            
                            $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor . " - " . $ConsultarCodyColor . " valor:" . $Ret_Datos_Movimiento["motivo_1"]);
                            $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                          <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                            <span style='font-size: 30px; color: " . $RetMotivo["color"] . ";'>" . 
                                              $RetMotivo["Forma_Categoria"] . "
                                              <center>
                                                <span class='nombreCategoria'>" . $RetMotivo["codigo"] . "
                                                </span>
                                              </center>
                                            </span>
                                          </a>
                                      </div>";
                            $marginLeft = (strlen($RetMotivo["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                <div style='font-family: DejaVu Sans, Noto Sans Symbols 2; font-size: 7px; color: " . $RetMotivo["color"] . ";'>" . 
                                                  $RetMotivo["Forma_Categoria"] . "
                                                </div>
                                                <div style='font-size: 5.5px;'>" . 
                                                  $RetMotivo["codigo"] . "
                                                </div>
                                              </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo["Forma_Categoria"], 
                              $RetMotivo["codigo"],
                              $RetMotivo["color"]
                            ];
                            $forma_motivo = $RetMotivo["Forma_Categoria"];
                            if (strlen($forma_motivo) > 1) {
                              $forma_motivo = substr($forma_motivo, 2);
                              $forma_motivo = substr($forma_motivo, 0, -1);
                            }
                            $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo["color"];
                          }
                        }
                        if ($ID_Motivo3 > 0) {
                          if ($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_1"]) {
                            $ConsultarCodyColor = "select M.cod_categoria,
                                                          F.Forma_Categoria,
                                                          C.color, 
                                                          M.codigo 
                                                   from motivo M,
                                                        categoria C,
                                                        formas_categorias F
                                                        where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_1"] . " 
                                                          and M.cod_categoria = C.cod_categoria 
                                                          and C.ID_Forma = F.ID_Forma 
                                                          and M.estado = 1 
                                                          and C.estado = 1";
                            $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";

                            $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor . " - " . $ConsultarCodyColor . " valor:" . $Ret_Datos_Movimiento["motivo_1"]);
                            $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                        <span style='font-size: 30px; color: " . $RetMotivo["color"] . ";'>" . 
                                          $RetMotivo["Forma_Categoria"] . "
                                          <center>
                                            <span class='nombreCategoria'>" . 
                                              $RetMotivo["codigo"] . "
                                            </span>
                                          </center>
                                        </span>
                                      </div>";
                            $marginLeft = (strlen($RetMotivo["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                  <div style='font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px; color: " . $RetMotivo["color"] . "; '>" . 
                                                    $RetMotivo["Forma_Categoria"] . "
                                                  </div>
                                                  <div style='font-size: 5.5px;'>" . 
                                                    $RetMotivo["codigo"] . "
                                                  </div>
                                                </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo["Forma_Categoria"], 
                              $RetMotivo["codigo"],
                              $RetMotivo["color"]
                            ];
                            $forma_motivo = $RetMotivo["Forma_Categoria"];
                            if (strlen($forma_motivo) > 1) {
                              $forma_motivo = substr($forma_motivo, 2);
                              $forma_motivo = substr($forma_motivo, 0, -1);
                            }
                            $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo["color"];
                          }
                        }
                        if ($ID_Motivo4 > 0) {
                          if ($ID_Motivo4 == $Ret_Datos_Movimiento["motivo_1"]) {
                            $ConsultarCodyColor = "select M.cod_categoria,
                                                          F.Forma_Categoria,
                                                          C.color, 
                                                          M.codigo 
                                                   from motivo M,
                                                        categoria C,
                                                        formas_categorias F
                                                        where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_1"] . " 
                                                          and M.cod_categoria = C.cod_categoria 
                                                          and C.ID_Forma = F.ID_Forma 
                                                          and M.estado = 1 
                                                          and C.estado = 1";
                            $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";

                            $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor . " - " . $ConsultarCodyColor . " valor:" . $Ret_Datos_Movimiento["motivo_1"]);
                            $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                        <span style='font-size: 30px; color: " . $RetMotivo["color"] . ";'>" . 
                                          $RetMotivo["Forma_Categoria"] . "
                                          <center>
                                            <span class='nombreCategoria'>" . 
                                              $RetMotivo["codigo"] . "
                                            </span>
                                          </center>
                                        </span>
                                      </div>";
                            $marginLeft = (strlen($RetMotivo["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                  <div style='font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px; color: " . $RetMotivo["color"] . "; '>" . 
                                                    $RetMotivo["Forma_Categoria"] . "
                                                  </div>
                                                  <div style='font-size: 5.5px;'>" . 
                                                    $RetMotivo["codigo"] . "
                                                  </div>
                                                </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo["Forma_Categoria"], 
                              $RetMotivo["codigo"],
                              $RetMotivo["color"]
                            ];
                            $forma_motivo = $RetMotivo["Forma_Categoria"];
                            if (strlen($forma_motivo) > 1) {
                              $forma_motivo = substr($forma_motivo, 2);
                              $forma_motivo = substr($forma_motivo, 0, -1);
                            }
                            $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo["color"];
                        }
                        }
                        if ($ID_Motivo5 > 0) {
                          if ($ID_Motivo5 == $Ret_Datos_Movimiento["motivo_1"]) {
                            $ConsultarCodyColor = "select M.cod_categoria,
                                                          F.Forma_Categoria,
                                                          C.color, 
                                                          M.codigo 
                                                   from motivo M,
                                                        categoria C,
                                                        formas_categorias F
                                                        where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_1"] . " 
                                                          and M.cod_categoria = C.cod_categoria 
                                                          and C.ID_Forma = F.ID_Forma 
                                                          and M.estado = 1 
                                                          and C.estado = 1";
                            $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";

                            $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor . " - " . $ConsultarCodyColor . " valor:" . $Ret_Datos_Movimiento["motivo_1"]);
                            $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                        <span style='font-size: 30px; color: " . $RetMotivo["color"] . ";'>" . 
                                          $RetMotivo["Forma_Categoria"] . "
                                          <center>
                                            <span class='nombreCategoria'>" . 
                                              $RetMotivo["codigo"] . "
                                            </span>
                                          </center>
                                        </span>
                                      </div>";
                            $marginLeft = (strlen($RetMotivo["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                  <div style='font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px; color: " . $RetMotivo["color"] . "; '>" . 
                                                    $RetMotivo["Forma_Categoria"] . "
                                                  </div>
                                                  <div style='font-size: 5.5px;'>" . 
                                                    $RetMotivo["codigo"] . "
                                                  </div>
                                                </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo["Forma_Categoria"], 
                              $RetMotivo["codigo"],
                              $RetMotivo["color"]
                            ];
                            $forma_motivo = $RetMotivo["Forma_Categoria"];
                            if (strlen($forma_motivo) > 1) {
                              $forma_motivo = substr($forma_motivo, 2);
                              $forma_motivo = substr($forma_motivo, 0, -1);
                            }
                            $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo["color"];
                          }
                        }

                        if ($CantOpMotivos == 0) {
                          $ConsultarCodyColor = "select M.cod_categoria,
                                                        F.Forma_Categoria,
                                                        C.color,
                                                        M.codigo
                                                 from motivo M, 
                                                      categoria C, 
                                                      formas_categorias F 
                                                 where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_1"] . " 
                                                   and M.cod_categoria = C.cod_categoria 
                                                   and C.ID_Forma = F.ID_Forma 
                                                   and M.estado = 1 
                                                   and C.estado = 1";

                          $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
            
                          $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor . " - " . $ConsultarCodyColor . " valor:" . $Ret_Datos_Movimiento["motivo_1"]);
                          $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
                          $nroMotivosEnFecha += 1;

                          $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                          $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                      <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                        <span style='font-size: 30px; padding: 0px; color: " . $RetMotivo["color"] . ";'>" . 
                                          $RetMotivo["Forma_Categoria"] . "
                                          <center>
                                            <span class='nombreCategoria'>" . $RetMotivo["codigo"] . "</span>
                                          </center>
                                        </span>
                                      </a>
                                    </div>";
                          $marginLeft = (strlen($RetMotivo["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                          $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                              <div style='font-family: DejaVu Sans, Noto Sans Symbols 2; font-size: 7px; padding: 0px; color: " . $RetMotivo["color"] . ";'>" . 
                                                $RetMotivo["Forma_Categoria"] . "
                                              </div>
                                              <div style='font-size: 5.5px;'>" . 
                                                $RetMotivo["codigo"] . "
                                              </div>
                                            </div>";
                          $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo["Forma_Categoria"], 
                              $RetMotivo["codigo"],
                              $RetMotivo["color"]
                          ];
                          $forma_motivo = $RetMotivo["Forma_Categoria"];
                          if (strlen($forma_motivo) > 1) {
                            $forma_motivo = substr($forma_motivo, 2);
                            $forma_motivo = substr($forma_motivo, 0, -1);
                          }
                          $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo["color"];
                        }
                      }

                      if ($Ret_Datos_Movimiento["motivo_2"] > 1 && $Ret_Datos_Movimiento["permiso_2"]) {
                        if ($ID_Motivo > 0) {
                          if ($ID_Motivo == $Ret_Datos_Movimiento["motivo_2"]) {
                            $ConsultarCodyColor2 = "select M.cod_categoria, 
                                                           F.Forma_Categoria, 
                                                           C.color, 
                                                           M.codigo 
                                                    from motivo M, 
                                                         categoria C, 
                                                         formas_categorias F 
                                                    where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_2"] . " 
                                                      and M.cod_categoria = C.cod_categoria 
                                                      and C.ID_Forma = F.ID_Forma 
                                                      and M.estado = 1 
                                                      and C.estado = 1";
                            $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";


                            $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2 . " - " . $ConsultarCodyColor2 . " valor:" . $Ret_Datos_Movimiento["motivo_2"]);

                            $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);

                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                          <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                            <span style='font-size: 30px; padding: 0px; color: " . $RetMotivo2["color"] . ";'>" . $RetMotivo2["Forma_Categoria"] . "
                                              <center>
                                                <span class='nombreCategoria'>" . $RetMotivo2["codigo"] . "
                                                </span>
                                              </center>
                                            </span>
                                          </a>
                                        </div>";
                            $marginLeft = (strlen($RetMotivo2["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft;text-align: center; display: inline-block;'>
                                                <div style='font-family: DejaVu Sans, Noto Sans Symbols 2;font-size: 7px; padding: 0px; color: " . $RetMotivo2["color"] . ";'>" . 
                                                  $RetMotivo2["Forma_Categoria"] . "
                                                </div>
                                                <div style='font-size: 5.5px;'>" . 
                                                  $RetMotivo2["codigo"] . "
                                                </div>
                                              </div>";
                            
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo2["Forma_Categoria"], 
                                $RetMotivo2["codigo"],
                                $RetMotivo2["color"]
                            ];
                          $forma_motivo = $RetMotivo2["Forma_Categoria"];
                          if (strlen($forma_motivo) > 1) {
                            $forma_motivo = substr($forma_motivo, 2);
                            $forma_motivo = substr($forma_motivo, 0, -1);
                          }
                          $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo2["color"];
                          }
                        }
                        if ($ID_Motivo2 > 0) {
                          if ($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_2"]) {
                            $ConsultarCodyColor2 = "select M.cod_categoria, 
                                                           F.Forma_Categoria, 
                                                           C.color, 
                                                           M.codigo 
                                                    from motivo M, 
                                                         categoria C, 
                                                         formas_categorias F 
                                                    where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_2"] . " 
                                                      and M.cod_categoria = C.cod_categoria 
                                                      and C.ID_Forma = F.ID_Forma 
                                                      and M.estado = 1 
                                                      and C.estado = 1";
                            $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";


                            $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2 . " - " . $ConsultarCodyColor2 . " valor:" . $Ret_Datos_Movimiento["motivo_2"]);
                            $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                        <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                          <span style='font-size: 30px; color: " . $RetMotivo2["color"] . ";'>" . 
                                            $RetMotivo2["Forma_Categoria"] . "
                                            <center>
                                              <span class='nombreCategoria'>" . 
                                                $RetMotivo2["codigo"] . "
                                              </span>
                                            </center>
                                          </span>
                                        </a>
                                      </div>";
                            $marginLeft = (strlen($RetMotivo2["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                <div style=' font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px;  color: " . $RetMotivo2["color"] . ";'>" . 
                                                  $RetMotivo2["Forma_Categoria"] . "
                                                </div>
                                                <div style='font-size: 5.5px;'>" . 
                                                  $RetMotivo2["codigo"] . "
                                                </div>
                                              </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo2["Forma_Categoria"], 
                                $RetMotivo2["codigo"],
                                $RetMotivo2["color"]
                            ];
                          $forma_motivo = $RetMotivo2["Forma_Categoria"];
                          if (strlen($forma_motivo) > 1) {
                            $forma_motivo = substr($forma_motivo, 2);
                            $forma_motivo = substr($forma_motivo, 0, -1);
                          }
                          $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo2["color"];
                          }
                        }
                        if ($ID_Motivo3 > 0) {
                          if ($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_2"]) {
                            $ConsultarCodyColor2 = "select M.cod_categoria, 
                                                           F.Forma_Categoria, 
                                                           C.color, 
                                                           M.codigo 
                                                    from motivo M, 
                                                         categoria C, 
                                                         formas_categorias F 
                                                    where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_2"] . " 
                                                      and M.cod_categoria = C.cod_categoria 
                                                      and C.ID_Forma = F.ID_Forma 
                                                      and M.estado = 1 
                                                      and C.estado = 1";
                            $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";


                            $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2 . " - " . $ConsultarCodyColor2 . " valor:" . $Ret_Datos_Movimiento["motivo_2"]);

                            $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                          <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                            <span style='font-size: 30px; color: " . $RetMotivo2["color"] . ";'>" . 
                                              $RetMotivo2["Forma_Categoria"] . "
                                              <center>
                                                <span class='nombreCategoria'>" . 
                                                  $RetMotivo2["codigo"] . "
                                                </span>
                                              </center>
                                            </span>
                                          </a>
                                        </div>";
                            $marginLeft = (strlen($RetMotivo2["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                <div style=' font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px;  color: " . $RetMotivo2["color"] . ";'>" . 
                                                  $RetMotivo2["Forma_Categoria"] . "
                                                </div>
                                                <div style='font-size: 5.5px;'>" . 
                                                  $RetMotivo2["codigo"] . "
                                                </div>
                                              </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo2["Forma_Categoria"], 
                                $RetMotivo2["codigo"],
                                $RetMotivo2["color"]
                            ];
                            $forma_motivo = $RetMotivo2["Forma_Categoria"];
                            if (strlen($forma_motivo) > 1) {
                              $forma_motivo = substr($forma_motivo, 2);
                              $forma_motivo = substr($forma_motivo, 0, -1);
                            }
                            $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo2["color"];
                          }
                        }
                        if ($ID_Motivo4 > 0) {
                          if ($ID_Motivo4 == $Ret_Datos_Movimiento["motivo_2"]) {
                            $ConsultarCodyColor2 = "select  M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_2"] . " and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                            $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";


                            $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2 . " - " . $ConsultarCodyColor2 . " valor:" . $Ret_Datos_Movimiento["motivo_2"]);

                            $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                          <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                            <span style='font-size: 30px; color: " . $RetMotivo2["color"] . ";'>" . 
                                              $RetMotivo2["Forma_Categoria"] . "
                                              <center>
                                                <span class='nombreCategoria'>" . 
                                                  $RetMotivo2["codigo"] . "
                                                </span>
                                              </center>
                                            </span>
                                          </a>
                                        </div>";
                            $marginLeft = (strlen($RetMotivo2["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                <div style=' font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px;  color: " . $RetMotivo2["color"] . ";'>" . 
                                                  $RetMotivo2["Forma_Categoria"] . "
                                                </div>
                                                <div style='font-size: 5.5px;'>" . 
                                                  $RetMotivo2["codigo"] . "
                                                </div>
                                              </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo2["Forma_Categoria"], 
                                $RetMotivo2["codigo"],
                                $RetMotivo2["color"]
                            ];
                            $forma_motivo = $RetMotivo2["Forma_Categoria"];
                            if (strlen($forma_motivo) > 1) {
                              $forma_motivo = substr($forma_motivo, 2);
                              $forma_motivo = substr($forma_motivo, 0, -1);
                            }
                            $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo2["color"];
                          }
                        }
                        if ($ID_Motivo5 > 0) {
                          if ($ID_Motivo5 == $Ret_Datos_Movimiento["motivo_2"]) {
                            $ConsultarCodyColor2 = "select  M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_2"] . " and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                            $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";


                            $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2 . " - " . $ConsultarCodyColor2 . " valor:" . $Ret_Datos_Movimiento["motivo_2"]);

                            $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                          <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                            <span style='font-size: 30px; color: " . $RetMotivo2["color"] . ";'>" . 
                                              $RetMotivo2["Forma_Categoria"] . "
                                              <center>
                                                <span class='nombreCategoria'>" . 
                                                  $RetMotivo2["codigo"] . "
                                                </span>
                                              </center>
                                            </span>
                                          </a>
                                        </div>";
                            $marginLeft = (strlen($RetMotivo2["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                <div style=' font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px;  color: " . $RetMotivo2["color"] . ";'>" . 
                                                  $RetMotivo2["Forma_Categoria"] . "
                                                </div>
                                                <div style='font-size: 5.5px;'>" . 
                                                  $RetMotivo2["codigo"] . "
                                                </div>
                                              </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo2["Forma_Categoria"], 
                                $RetMotivo2["codigo"],
                                $RetMotivo2["color"]
                            ];
                            $forma_motivo = $RetMotivo2["Forma_Categoria"];
                            if (strlen($forma_motivo) > 1) {
                              $forma_motivo = substr($forma_motivo, 2);
                              $forma_motivo = substr($forma_motivo, 0, -1);
                            }
                            $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo2["color"];
                          }
                        }
                        if ($CantOpMotivos == 0) {
                          $ConsultarCodyColor2 = "select  M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_2"] . " and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                          $MensajeErrorConsultarCodyColor2 = "No se pudieron consultar los motivos de los Movimientos";


                          $TomarCodyColor2 = mysqli_query($Con->Conexion, $ConsultarCodyColor2) or die($MensajeErrorConsultarCodyColor2 . " - " . $ConsultarCodyColor2);

                          $RetMotivo2 = mysqli_fetch_assoc($TomarCodyColor2);
                          $nroMotivosEnFecha += 1;

                          $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                          $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                        <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                          <span style='font-size: 30px; color: " . $RetMotivo2["color"] . "; text-align: center;'>" . 
                                            $RetMotivo2["Forma_Categoria"] . "
                                            <center>
                                              <span class='nombreCategoria'>" . 
                                                $RetMotivo2["codigo"] . "
                                              </span>
                                            </center>
                                          </span>
                                        </a>
                                      </div>";
                          $marginLeft = (strlen($RetMotivo2["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                          $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center;  display: inline-block;'>
                                              <div style=' font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px;  color: " . $RetMotivo2["color"] . "; text-align= center;'>" . 
                                                $RetMotivo2["Forma_Categoria"] . "
                                              </div>
                                              <div style='font-size: 5.5px;'>" . 
                                                $RetMotivo2["codigo"] . "
                                              </div>
                                            </div>";
                          $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo2["Forma_Categoria"], 
                              $RetMotivo2["codigo"],
                              $RetMotivo2["color"]
                          ];
                          $forma_motivo = $RetMotivo2["Forma_Categoria"];
                          if (strlen($forma_motivo) > 1) {
                            $forma_motivo = substr($forma_motivo, 2);
                            $forma_motivo = substr($forma_motivo, 0, -1);
                          }
                          $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo2["color"];
                        }
                      }

                      if ($Ret_Datos_Movimiento["motivo_3"] > 1 && $Ret_Datos_Movimiento["permiso_3"]) {
                        if ($ID_Motivo > 0) {
                          if ($ID_Motivo == $Ret_Datos_Movimiento["motivo_3"]) {
                            $ConsultarCodyColor3 = "select  M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_3"] . " and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                            $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos de los Movimientos";
            
                            $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3 . " - " . $ConsultarCodyColor3 . " valor:" . $Ret_Datos_Movimiento["motivo_3"]);

                            $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                        <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                          <span style='font-size: 30px; color: " . $RetMotivo3["color"] . ";'>" . 
                                            $RetMotivo3["Forma_Categoria"] . "
                                              <center>
                                                <span class='nombreCategoria'>" . 
                                                  $RetMotivo3["codigo"] . "
                                                </span>
                                              </center>
                                            </span>
                                          </a>
                                        </div>";
                            $marginLeft = (strlen($RetMotivo3["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                <div style='font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px;  color: " . $RetMotivo3["color"] . ";'>" . 
                                                  $RetMotivo3["Forma_Categoria"] . "
                                                </div>
                                                <div style='font-size: 5.5px;'>" . 
                                                  $RetMotivo3["codigo"] . "
                                                </div>
                                              </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo3["Forma_Categoria"], 
                                $RetMotivo3["codigo"],
                                $RetMotivo3["color"]
                            ];
                          $forma_motivo = $RetMotivo3["Forma_Categoria"];
                          if (strlen($forma_motivo) > 1) {
                            $forma_motivo = substr($forma_motivo, 2);
                            $forma_motivo = substr($forma_motivo, 0, -1);
                          }
                          $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo3["color"];
                          }
                        }
                        if ($ID_Motivo2 > 0) {
                          if ($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_3"]) {
                            $ConsultarCodyColor3 = "select  M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_3"] . " and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                            $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos de los Movimientos";
            
                            $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3 . " - " . $ConsultarCodyColor3 . " valor:" . $Ret_Datos_Movimiento["motivo_3"]);
                            $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                          <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                            <span style='font-size: 30px; color: " . $RetMotivo3["color"] . ";'>" . 
                                              $RetMotivo3["Forma_Categoria"] . "
                                              <center>
                                                <span class='nombreCategoria'>" . 
                                                  $RetMotivo3["codigo"] . "
                                                </span>
                                              </center>
                                            </span>
                                          </a>
                                        </div>";
                            $marginLeft = (strlen($RetMotivo3["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                <div style=' font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px;  color: " . $RetMotivo3["color"] . ";'>" . 
                                                  $RetMotivo3["Forma_Categoria"] . "
                                                </div>
                                                <div style='font-size: 5.5px;'>" . 
                                                  $RetMotivo3["codigo"] . "
                                                </div>
                                              </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo3["Forma_Categoria"], 
                                $RetMotivo3["codigo"],
                                $RetMotivo3["color"]
                            ];
                            $forma_motivo = $RetMotivo3["Forma_Categoria"];
                            if (strlen($forma_motivo) > 1) {
                              $forma_motivo = substr($forma_motivo, 2);
                              $forma_motivo = substr($forma_motivo, 0, -1);
                            }
                            $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo3["color"];
                          }
                        }
                        if ($ID_Motivo3 > 0) {
                          if ($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_3"]) {
                            $ConsultarCodyColor3 = "select  M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_3"] . " and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                            $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos de los Movimientos";
            
                            $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3 . " - " . $ConsultarCodyColor3 . " valor:" . $Ret_Datos_Movimiento["motivo_3"]);
                            $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                          <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                            <span style='font-size: 30px; color: " . $RetMotivo3["color"] . ";'>" . 
                                              $RetMotivo3["Forma_Categoria"] . "
                                              <center>
                                                <span class='nombreCategoria'>" . 
                                                  $RetMotivo3["codigo"] . "
                                                </span>
                                              </center>
                                            </span>
                                          </a>
                                        </div>";
                            $marginLeft = (strlen($RetMotivo3["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                <div style=' font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px;  color: " . $RetMotivo3["color"] . ";'>" . 
                                                  $RetMotivo3["Forma_Categoria"] . "
                                                </div>
                                                <div style='font-size: 5.5px;'>" . 
                                                  $RetMotivo3["codigo"] . "
                                                </div>
                                              </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo3["Forma_Categoria"], 
                                $RetMotivo3["codigo"],
                                $RetMotivo3["color"]
                            ];
                            $forma_motivo = $RetMotivo3["Forma_Categoria"];
                            if (strlen($forma_motivo) > 1) {
                              $forma_motivo = substr($forma_motivo, 2);
                              $forma_motivo = substr($forma_motivo, 0, -1);
                            }
                            $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo3["color"];   
                          }
                        }
                        if ($ID_Motivo4 > 0) {
                          if ($ID_Motivo4 == $Ret_Datos_Movimiento["motivo_3"]) {
                            $ConsultarCodyColor3 = "select  M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_3"] . " and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                            $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos de los Movimientos";
            
                            $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3 . " - " . $ConsultarCodyColor3 . " valor:" . $Ret_Datos_Movimiento["motivo_3"]);
                            $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                          <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                            <span style='font-size: 30px; color: " . $RetMotivo3["color"] . ";'>" . 
                                              $RetMotivo3["Forma_Categoria"] . "
                                              <center>
                                                <span class='nombreCategoria'>" . 
                                                  $RetMotivo3["codigo"] . "
                                                </span>
                                              </center>
                                            </span>
                                          </a>
                                        </div>";
                            $marginLeft = (strlen($RetMotivo3["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                <div style=' font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px;  color: " . $RetMotivo3["color"] . ";'>" . 
                                                  $RetMotivo3["Forma_Categoria"] . "
                                                </div>
                                                <div style='font-size: 5.5px;'>" . 
                                                  $RetMotivo3["codigo"] . "
                                                </div>
                                              </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo3["Forma_Categoria"], 
                                $RetMotivo3["codigo"],
                                $RetMotivo3["color"]
                            ];
                            $forma_motivo = $RetMotivo3["Forma_Categoria"];
                            if (strlen($forma_motivo) > 1) {
                              $forma_motivo = substr($forma_motivo, 2);
                              $forma_motivo = substr($forma_motivo, 0, -1);
                            }
                            $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo3["color"];   
                          }
                        }
                        if ($ID_Motivo5 > 0) {
                          if ($ID_Motivo5 == $Ret_Datos_Movimiento["motivo_3"]) {
                            $ConsultarCodyColor3 = "select  M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_3"] . " and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                            $MensajeErrorConsultarCodyColor3 = "No se pudieron consultar los motivos de los Movimientos";
            
                            $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3 . " - " . $ConsultarCodyColor3 . " valor:" . $Ret_Datos_Movimiento["motivo_3"]);
                            $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);
                            $nroMotivosEnFecha += 1;

                            $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                            $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                          <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                            <span style='font-size: 30px; color: " . $RetMotivo3["color"] . ";'>" . 
                                              $RetMotivo3["Forma_Categoria"] . "
                                              <center>
                                                <span class='nombreCategoria'>" . 
                                                  $RetMotivo3["codigo"] . "
                                                </span>
                                              </center>
                                            </span>
                                          </a>
                                        </div>";
                            $marginLeft = (strlen($RetMotivo3["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                            $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block;'>
                                                <div style=' font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px;  color: " . $RetMotivo3["color"] . ";'>" . 
                                                  $RetMotivo3["Forma_Categoria"] . "
                                                </div>
                                                <div style='font-size: 5.5px;'>" . 
                                                  $RetMotivo3["codigo"] . "
                                                </div>
                                              </div>";
                            $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo3["Forma_Categoria"], 
                                $RetMotivo3["codigo"],
                                $RetMotivo3["color"]
                            ];
                            $forma_motivo = $RetMotivo3["Forma_Categoria"];
                            if (strlen($forma_motivo) > 1) {
                              $forma_motivo = substr($forma_motivo, 2);
                              $forma_motivo = substr($forma_motivo, 0, -1);
                            }
                            $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo3["color"];   
                          }
                        }
                        if ($CantOpMotivos == 0) {
                          $ConsultarCodyColor3 = "select  M.cod_categoria, F.Forma_Categoria, C.color, M.codigo from motivo M, categoria C, formas_categorias F where M.id_motivo = " . $Ret_Datos_Movimiento["motivo_3"] . " and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
                          $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";


                          $TomarCodyColor3 = mysqli_query($Con->Conexion, $ConsultarCodyColor3) or die($MensajeErrorConsultarCodyColor3 . " - " . $ConsultarCodyColor3);
                          $RetMotivo3 = mysqli_fetch_assoc($TomarCodyColor3);
                          $nroMotivosEnFecha += 1;

                          $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                          $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'>
                                        <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"Ventana" . $Ret_Datos_Movimiento["id_movimiento"] . "\",\"width=800,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                          <span style='font-size: 30px; color: " . $RetMotivo3["color"] . ";'>" . 
                                            $RetMotivo3["Forma_Categoria"] . "
                                            <center>
                                              <span class='nombreCategoria'>" . 
                                                $RetMotivo3["codigo"] . "
                                              </span>
                                            </center>
                                          </span>
                                        </a>
                                      </div>";
                          $marginLeft = (strlen($RetMotivo3["codigo"]) >= 4) ? "margin-left:10px" : "margin-left:2px";
                          $tagsMotivos .= "<div style = 'padding: 0; $marginLeft; text-align: center; display: inline-block; '>
                                              <div style=' font-family: DejaVu Sans, Noto Sans Symbols 2; font-size:  7px;  color: " . $RetMotivo3["color"] . ";'>" . 
                                                $RetMotivo3["Forma_Categoria"] . "
                                              </div>
                                              <div style='font-size: 5.5px;'>" . 
                                                $RetMotivo3["codigo"] . "
                                              </div>
                                            </div>";
                          $jsonTable[$clave]["$Mes/$Anio"][] = [$RetMotivo3["Forma_Categoria"], 
                              $RetMotivo3["codigo"],
                              $RetMotivo3["color"]
                          ];
                          $forma_motivo = $RetMotivo3["Forma_Categoria"];
                          if (strlen($forma_motivo) > 1) {
                            $forma_motivo = substr($forma_motivo, 2);
                            $forma_motivo = substr($forma_motivo, 0, -1);
                          }
                          $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = $RetMotivo3["color"];
                        }
                      }
                    }
                    $tagsMotivos .= ($nroMotivosEnFecha >= 6) ? "</div>" : "";
                    $tagsTD .= "</div></td>";
                    $tagsTD_imprimir .= $tagsMotivos . "</div></td>";
                    $ID_Persona_Bandera = $RetTodos["id_persona"];
                  }

                  if($tdExtenso){
                    $tdReemplazar = "~<td~";
                    $tdClassExtenso = "<td class='td--extenso-height-127'";
                    $tagsTD = preg_replace( $tdReemplazar, $tdClassExtenso, $tagsTD);
                  }

                  $Table = $Table . $tagsTD . "</tr>";

                }


                //////////////////////////////////////////////////////////////////////////////////
                //////////////////////////////////////////////////////////////////////////////////
            
              }

              if (isset($Table)) {
                $Table .= "</tbody>";
                $Table .= "</table>";
              } else {
                $Table = "";
              }

              if ($Con->ResultSet->num_rows > 0) {
                echo $Table;
              }

              $Con->CloseConexion();
          } else {
            echo "No se pudo obtener el año";
          }
          ?>
          </div>
          </div>
        </div>
      </div>
    </div>
    <input type="range" class="fixed-bottom form-range input--transform-rotate180" step="0.01" value="10" min="10" id="BarraDeNavHTabla">
    <!--<input type="range" class="fixed-bottom form-range" step="1" value="1" min="1" id="BarraDeNavVTabla">-->

    <div class="modal fade modal--show-overall" id="configModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 2001;">
      <div class="class_modal-dialog modal-dialog" role="document"  id="id_modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel" style="margin-left: auto;">Configurar resultados</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <ul type=none>
              <li><input type="checkbox" id="chk-fechaNac" checked> Fecha Nac</li>
              <li><input type="checkbox" id="chk-persona" checked> Persona</li> 
              <li><input type="checkbox" id="chk-domicilio" checked> Direc. </li>
              <li><input type="checkbox" id="chk-barrio" checked> Barrio </li>
              <li><input type="checkbox" id="chk-manzana"> Manzana </li>
              <li><input type="checkbox" id="chk-lote"> Lote </li>
              <li><input type="checkbox" id="chk-sublote"> Sublote </li>
              <li><input type="checkbox" id="chk-anios"> Años (edad) </li>
              <li><input type="checkbox" id="chk-meses"> Meses (edad) </li>
            </ul>
          </div>
          <div class="modal-footer modal-footer-flex-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onClick="configResultados()" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade modal--show-overall" id="map-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 2001; overflow: hidden">
      <div class="class_modal-dialog modal-dialog" role="document"  id="id_modal-dialog" style="min-width: 80%; height: 1000px;">
        <div class="modal-content" style="height: 60%;">
          <div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="padding-top: 0px">
          <div id="basicMap"></div>
          </div>
        </div>
      </div>
    </div>

    <script>
      (function () {
        var tabla = document.getElementById("tabla-responsive");
      })();

      <?php $_SESSION["meses"] = $mesesHeader; ?>
      var objectJsonTabla = <?php echo json_encode($jsonTable);?>;

      function toggleZoom(porcentaje){
        var Tabla = document.getElementById("tablaMovimientos");
        Tabla.style.zoom = porcentaje + "%";
      }

      function toggleZoomScreen() {
        var Tabla = document.getElementById("cuerpo-tabla");
        Tabla.style.height = "1800px";

        var DivTabla = document.getElementById("tabla-responsive");
        DivTabla.style.height = "1800px";

        var elements = document.getElementsByClassName("Datos");
        for (var i = 0; i < elements.length; i++) {
          var element = elements[i];
          element.style.fontSize = "18px";
        }

        var TTH = document.getElementsByTagName("th");
        var TTD = document.getElementsByTagName("td");
        for (var i = 0; i < TTH.length; i++) {
          TTH[i].removeAttribute("style");
          TTH[i].setAttribute("min-width", "400px");
          TTH[i].setAttribute("style", "font-size: 24px;");
        }

        for (var i = 0; i < TTD.length; i++) {
          TTD[i].removeAttribute("style");
          TTD[i].setAttribute("min-width", "400px");
        }

        var DatosFlia = document.getElementsByName("datosflia");
        for (var i = 0; i < DatosFlia.length; i++) {
          DatosFlia[i].removeAttribute("min-width");
          DatosFlia[i].setAttribute("style", "max-width: 50px; font-size: 24px;");    //50
        }

        var DatosResultados = document.getElementsByName("DatosResultados");
        for (var i = 0; i < DatosResultados.length; i++) {
          DatosResultados[i].setAttribute("style", "min-width: 250px; font-size: 28px;");
        }

        var DatosSinResultados = document.getElementsByName("DatosSinResultados");
        for (var i = 0; i < DatosSinResultados.length; i++) {
          DatosSinResultados[i].setAttribute("style", "min-width: 82%;");
        }
      }

      function toggleZoomScreenNormal() {
        var Tabla = document.getElementById("cuerpo-tabla");
        Tabla.style.height = "480px";

        var DivTabla = document.getElementById("tabla-responsive");
        DivTabla.style.height = "480px";

        var elements = document.getElementsByClassName("Datos");
        for (var i = 0; i < elements.length; i++) {
          var element = elements[i];
          element.style.fontSize = "18px";
        }

        var TTH = document.getElementsByTagName("th");
        var TTD = document.getElementsByTagName("td");
        for (var i = 0; i < TTH.length; i++) {
          TTH[i].removeAttribute("min-width");
          TTH[i].removeAttribute("style");
        }

        for (var i = 0; i < TTD.length; i++) {
          TTD[i].removeAttribute("style");
          TTD[i].setAttribute("min-width", "400px");
        }

        var DatosFlia = document.getElementsByName("datosflia");
        for (var i = 0; i < DatosFlia.length; i++) {
          DatosFlia[i].removeAttribute("min-width");
          DatosFlia[i].setAttribute("style", "max-width: 50px;");
        }

        var DatosResultados = document.getElementsByName("DatosResultados");
        for (var i = 0; i < DatosResultados.length; i++) {
          DatosResultados[i].setAttribute("style", "min-width: 190px;");
        }

        var DatosSinResultados = document.getElementsByName("DatosSinResultados");
        for (var i = 0; i < DatosSinResultados.length; i++) {
          DatosSinResultados[i].setAttribute("style", "min-width: 82%;");
        }
      }

      function enviarImprimir() {
        console.log($("p"));
        alert("llega");

        var ficha = document.getElementById("ContenidoTabla");
        // document.getElementById("tabla-responsive");

        var ventimp = window.open(' ', 'popimpr');
        ventimp.document.write(ficha.innerHTML);
        ventimp.document.close();
        ventimp.print();
        ventimp.close();


        /*
           const $elementoParaConvertir = document.body;        
            alert($elementoParaConvertir);   
                  html2pdf()
                      .set({
                          margin: 1,
                          filename: 'documento.pdf',
                          image: {
                              type: 'jpeg',
                              quality: 0.98
                          },
                          html2canvas: {
                              scale: 3, // A mayor escala, mejores gráficos, pero más peso
                              letterRendering: true,
                          },
                          jsPDF: {
                              unit: "in",
                              format: "a3",
                              orientation: 'portrait' // landscape o portrait
                          }
                      })
                      .from($elementoParaConvertir)
                      .save()
                      .catch(err => console.log(err));      
          */

      }


      function enviarImprimir_222() {
        var tablaMovimientos = document.getElementById("tabla-responsive");
        var tablaEnc = btoa(unescape(encodeURIComponent(tablaMovimientos.innerHTML)));

        var consulta = '<?php echo base64_encode($Consulta); ?>';
        var fechaInicio = '<?php echo base64_encode($Fecha_Inicio) ?>';
        var fechaFin = '<?php echo base64_encode($Fecha_Fin) ?>';

        console.log(consulta);

        var arrTabla = [];
        arrTabla = tablaEnc;
        console.log(arrTabla);

        location.href = 'pruebas_PDF.php?consulta=' + consulta + '&fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin;
      }

      function enviarImprimirPdf() {
        let filas = objectJsonTabla.forEach((element, index, array) => {envioDeFilasEnBloques(element, index, array);});
        if (rowsRequest != {}) {
          let request = new XMLHttpRequest();
          listaDeRequest.push(request);
          request.open("POST", "Controladores/GeneradorPdf.php", true);
          request.onreadystatechange = addPdf;
          request.send(JSON.stringify(rowsRequest));
          rowsRequest = {};
        }
      }

      var tituloBarrio = document.getElementById("Contenido-Titulo-1");
      var tituloDirec = document.getElementById("Contenido-Titulo-2");
      var tituloPersona = document.getElementById("Contenido-Titulo-3");
      var tituloFechaNac = document.getElementById("Contenido-Titulo-4");
      var tituloFlia = document.getElementById("Contenido-Titulo-5");
      var tituloMz = document.getElementById("Contenido-Titulo-6");
      var tituloLote = document.getElementById("Contenido-Titulo-7");

    </script>
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