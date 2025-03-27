<?php
session_start();
require_once "Controladores/Elements.php";
require_once "Controladores/CtrGeneral.php";
require_once "Controladores/Conexion.php";
require_once "Modelo/Persona.php";
require_once "Modelo/Motivo.php";
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
$width_dispay = (isset($_REQUEST["width-display"])) ? $_REQUEST["width-display"] : null;

?>
<!DOCTYPE html>
<html>

<head>
  <title>Rastreador III</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta charset="utf-8">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="stylesheet" type="text/css" href="css/Estilos.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />

  <script src="js/FileSaver.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
  <script src="js/acciones-reporte-grafico.js"></script>
  <script src="js/jquery.wordexport.js"></script>
  <script src="html2pdf.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/pdf-lib/dist/pdf-lib.js"></script>
  <script src="https://www.lactame.com/lib/image-js/0.21.2/image.min.js"></script>
  <script src="./dist/mapa.js"></script>

  <script>
    const { PDFDocument, StandardFonts, rgb } = PDFLib;

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
      $("#ContenerdorPrincipal").attr("style", "margin-left:5px;");
      // var ContenidoMenu = document.getElementById("ContenidoMenu");
      // ContenidoMenu.setAttribute("class","col-md-1");
      // document.getElementById("sidebar").style.width = "3%"; //5

      //document.getElementById("ContenidoTabla").style.marginLeft = "0";
      var ContenidoTabla = document.getElementById("ContenidoTabla");
      ContenidoTabla.setAttribute("class", "col-md-12");

      $("#expandir").attr("style", "padding-left:1px");
      $("#abrir").attr("style", "display:inline;");
      //document.getElementById("abrir").style.display = "inline";
      document.getElementById("cerrar").style.display = "none";
      $("#BarraDeNavHTabla").attr("style", "width: 95%; margin-left: 2%;");
    }

    var map = null;
    var widthDispay = <?php echo (($width_dispay) ? $width_dispay : "0"); ?>;
    var nroFilasTabla = 0;
    var nroColumnasTabla = 0;
    var nroColumnaInicial = 0;
    var tablaBody = null;
    var tablaHead = null;
    var tablaBodyRows = null;
    var tablaHeadRow = null;
    var currCell = null;
    var editing = false;
    var columnaIndice = 10;
    var filaIndice = 1;
    var valInputRangePrev = columnaIndice;
    var focusBarraNavegacionH = false;
    var timeout = null;
    var rowsRequest = {};
    let listaDeRequest = new Array();
    let listaDePdf = new Array();
    let documentoPdf = PDFDocument.create();
    let nroPaginaPdf = 0;
    let nroPaginaGeneradas = 0;
    let thTable = null;
    let fullscreen = false;

    $(document).on("keydown", function (e) {
      NavegacionConTeclado(e);
    });

    $(document).on("ready", function (e) {
      tablaBody = $("#tablaMovimientos tbody");
      tablaHead = $("#tablaMovimientos thead");
      nroFilasTabla = $("#tablaMovimientos tbody > tr").length - 2;
      nroColumnasTabla = $("thead > tr > th").length - 2;
      let nroPag = (nroFilasTabla + 2) / 10;
      let floorPag = Math.floor((nroFilasTabla + 2) / 10);
      nroPaginaPdf = (nroPag > floorPag) ? (floorPag + 1) : floorPag;
      thTable = $("thead > tr > th");

      $("#input-zoom").on("input", function (e) {
        toggleZoom($('#input-zoom').prop("value"));
      });

      $("#zoomIncrementar").on("mousedown", function (e) {
        let nroColumnDisplonible = nroColumnasTabla + 2;
        timeout = setInterval(function () {
          $('#input-zoom')[0].stepUp();
          let nroColumnVisible = nroColumnDisplonible - columnaIndice;
          let valor = $('#input-zoom').prop("value") / 100;
          let widthColumns = widthDispay  - 200 - 29 - 30 - (150 * 3 + 120) * valor - nroColumnVisible * 190 * valor;
          let columnDisplonible = widthColumns / (190 * valor);
          tablaHead.css({
                        'transform-origin' : '0 0',
                        'transform' : 'scale(' + valor + ')'
                        });
          tablaBody.css({'transform-origin': '0 0',
                         'transform' : 'scale(' + valor + ')'
                        });
          if ((0.5 > Math.floor(columnDisplonible)) && columnaIndice <= nroColumnasTabla) {
              actualizacionDePosicionBarraDenavegacionH(e, columnaIndice + 0.7);
          }
        }, 37);
      });

      $("#zoomIncrementar").on("mouseup", function (e) {
        clearInterval(timeout);
      });

      $("#zoomDecrementar").on("mousedown", function (e) {
          let nroColumnDisplonible = nroColumnasTabla + 2;
          timeout = setInterval(function () {
              $('#input-zoom')[0].stepDown();
              let nroColumnVisible = nroColumnDisplonible - columnaIndice + 1;
              let valor = $('#input-zoom').prop("value") / 100;
              let widthColumns = widthDispay  - 200 - 29 - 30 - (150 * 3 + 120) * valor - nroColumnVisible * 190 * valor;
              let columnDisplonible = widthColumns / (190 * valor);

              tablaHead.css({
                            'transform-origin' : '0 0',
                            'transform' : 'scale(' + valor + ')'
                            });
              tablaBody.css({'transform-origin': '0 0',
                            'transform' : 'scale(' + valor + ')'
                            });
              if ((1 <= columnDisplonible) && columnaIndice > 10) {
                actualizacionDePosicionBarraDenavegacionH(e, (columnaIndice - 0.7));
              }
        }, 50);
      });

      $("#zoomDecrementar").on("mouseup", function (e) {
        clearInterval(timeout);
      });

      $("#BarraDeNavHTabla").on("mousedown", function (e) {
        focusBarraNavegacionH = true;
      });

      $("#BarraDeNavHTabla").on("input", function (e) {
        if (focusBarraNavegacionH) {
          if (!(tablaBodyRows && tablaHeadRow)) {
            tablaBodyRows = $("#tablaMovimientos tbody > tr");
            tablaHeadRow = $("#tablaMovimientos thead > tr");
          }
          navegacionConBarHNav(e);
        }
      });

      $("#BarraDeNavHTabla").on("mouseup", function (e) {
        focusBarraNavegacionH = false;
        actualizacionDePosicionBarraDenavegacionH(e, $(this).prop("value"));
      });

      $('thead tr >*').on("transitionstart", function (e) {
        var columnaRemoverClass = $("tbody tr > *:nth-child(" + (this.cellIndex + 1) + ")[class ~='hiddenColTablaAnimacion'] div div");
        columnaRemoverClass.removeClass("itemMotivoAccesible");
      });

      $('thead tr >*').on("transitionend", function (e) {
        var columnaRemoverClass = $("tbody tr > *:nth-child(" + (this.cellIndex + 1) + ")[class ~='showColTablaAnimacion'] div div");
        columnaRemoverClass.addClass("itemMotivoAccesible");
        columnaRemoverClass.removeClass("showColTablaAnimacionfire");
        columnaRemoverClass.removeClass("showColTablaAnimacion");
      });

      $("#boton-desplegale").on("click", function (e) {
        $("#desplegable").toggle();
      });

      $("button[class='close']").on("click", function (e) {
        if (fullscreen) {
          document.exitFullscreen();
          fullscreen = false;
        }
      });

      $("#boton-min").on("click", function (e) {
        $("button[class='ol-zoom-out']").click();
      });

      $("#boton-plus").on("click", function (e) {
        $("button[class='ol-zoom-in']").click();
      });

      $("#boton-animation").on("click", function (e) {
        animacionDeMapa(map, objectJsonTabla);
      });

      $("#boton-paused").on("click", function (e) {
        animacionPaused(map);
      });

      $("#boton-stop").on("click", function (e) {
        animacionStop(map);
        carga(map, objectJsonTabla);
      });

      $("#boton-increment").on("click", function (e) {
        map.incrementar();
      });

      $("#boton-decrement").on("click", function (e) {
        map.decrementar();
      });

      $("#boton-fullscreen").on("click", function (e) {
        //$("button[title='Toggle full-screen']").click();
        if (!fullscreen) {
          $("#map-modal div[class='modal-content']")[0].requestFullscreen();
          fullscreen = true;
        } else {
          document.exitFullscreen();
          fullscreen = false;
        }
      });
    });

    function fireKey(el) {
      var key = el;
      if (document.createEventObject) {
        var eventObj = document.createEventObject();
        eventObj.keyCode = key;
        el.fireEvent("onkeydown", eventObj);
      } else if (document.createEvent) {
        var eventObj = document.createEvent("Events");
        eventObj.initEvent("keydown", true, true);
        eventObj.which = key;
        el.dispatchEvent(eventObj);
      }
    }

    function actualizacionDePosicionBarraDenavegacionH(e, element) {
      let value = (element) ? element : $("#BarraDeNavHTabla").val();
      let columnaActual = columnaIndice;
      if (value < (columnaIndice - 0.5)) {
        columnaIndice--;
        headABorrar = $('thead tr > *:nth-child(' + columnaIndice + ')');
        columnaABorrar = $('tbody tr > *:nth-child(' + columnaIndice + ')');
        columnaABorrar.show();
        headABorrar.show();
        columnaABorrar.removeClass("hiddenColTablaAnimacion");
        headABorrar.addClass("hiddenColTablaAnimacion");
        columnaABorrar.removeClass("hiddenColTablaAnimacionfire");
        headABorrar.removeClass("hiddenColTablaAnimacionfire");
        columnaABorrar.addClass("showColTablaAnimacion");
        headABorrar.addClass("showColTablaAnimacion");
        columnaABorrar.addClass("showColTablaAnimacionfire");
        headABorrar.addClass("showColTablaAnimacionfire");
      } else if (value > (columnaIndice + 0.5)) {
        headABorrar = $('thead tr > *:nth-child(' + columnaIndice + ')');
        columnaABorrar = $('tbody tr > *:nth-child(' + columnaIndice + ')');
        columnaABorrar.removeClass("showColTablaAnimacion");
        headABorrar.removeClass("showColTablaAnimacion");
        columnaABorrar.removeClass("showColTablaAnimacionfire");
        headABorrar.removeClass("showColTablaAnimacionfire");
        columnaABorrar.addClass("hiddenColTablaAnimacion");
        headABorrar.addClass("hiddenColTablaAnimacion");
        columnaABorrar.addClass("hiddenColTablaAnimacionfire");
        headABorrar.addClass("hiddenColTablaAnimacionfire");
        columnaIndice++;
      } else if (((columnaIndice - 0.5) < value) && (value < columnaIndice)) {
        headABorrar = $('thead tr > *:nth-child(' + (columnaIndice - 1) + ')');
        columnaABorrar = $('tbody tr > *:nth-child(' + (columnaIndice - 1) + ')');
        columnaABorrar.removeClass("showColTablaAnimacion");
        headABorrar.removeClass("showColTablaAnimacion");
        columnaABorrar.removeClass("showColTablaAnimacionfire");
        headABorrar.removeClass("showColTablaAnimacionfire");
        columnaABorrar.addClass("hiddenColTablaAnimacion");
        headABorrar.addClass("hiddenColTablaAnimacion");
        columnaABorrar.addClass("hiddenColTablaAnimacionfire");
        headABorrar.addClass("hiddenColTablaAnimacionfire");
      } else if ((value < (columnaIndice + 0.5)) && (columnaIndice < value)) {
        headABorrar = $('thead tr > *:nth-child(' + columnaIndice + ')');
        columnaABorrar = $('tbody tr > *:nth-child(' + columnaIndice + ')');
        columnaABorrar.show();
        headABorrar.show();
        columnaABorrar.removeClass("hiddenColTablaAnimacion");
        headABorrar.addClass("hiddenColTablaAnimacion");
        columnaABorrar.removeClass("hiddenColTablaAnimacionfire");
        headABorrar.removeClass("hiddenColTablaAnimacionfire");
        columnaABorrar.addClass("showColTablaAnimacion");
        headABorrar.addClass("showColTablaAnimacion");
        columnaABorrar.addClass("showColTablaAnimacionfire");
        headABorrar.addClass("showColTablaAnimacionfire");
      }

      if (Math.round(value) == Math.floor(value)) {
        $("#BarraDeNavHTabla").val(Math.floor(value));
      } else {
        $("#BarraDeNavHTabla").val(Math.round(value));
      }
    }

    function navegacionConBarHNav(e) {
      let value = $("#BarraDeNavHTabla").val();
      if (!valInputRangePrev) {
        valInputRangePrev = value;
      }
      if (1 < Math.abs(columnaIndice - value)) {
        if (columnaIndice < value) {
          for (let index = columnaIndice; index <= value; index++) {
              let columnaActual = columnaIndice;
              let updateMarginLeft = "-190px";

              //headABorrar = $('thead tr > *:nth-child(' + columnaActual + ')');
              //columnaABorrar = $('tbody tr > *:nth-child(' + columnaActual + ')');
              //divABorrar = $('tbody tr > *:nth-child(' + columnaActual + ') div div');
              headABorrar = tablaHeadRow.find('> *:nth-child(' + columnaActual + ')');
              columnaABorrar = tablaBodyRows.find('> *:nth-child(' + columnaActual + ')');
              divABorrar = columnaABorrar.find('div div');
              columnaABorrar.removeClass("showColTablaAnimacion");
              columnaABorrar.removeClass("showColTablaAnimacionfire");
              columnaABorrar.find("div div").removeClass("itemMotivoAccesible");
              columnaABorrar.removeClass("hiddenColTablaAnimacion");
              columnaABorrar.removeClass("hiddenColTablaAnimacionfire");
              headABorrar.removeClass("showColTablaAnimacion");
              headABorrar.removeClass("showColTablaAnimacionfire");
              headABorrar.removeClass("hiddenColTablaAnimacion");
              headABorrar.removeClass("hiddenColTablaAnimacionfire");
              divABorrar.css("z-index", "-1");
              columnaABorrar.css({
                "margin-left": updateMarginLeft,
                "border-right-width": "0px",
                "border-left-width":  "0px"
              });
              headABorrar.css("margin-left", updateMarginLeft);
              columnaIndice = index;
              valInputRangePrev = index;
              $("#BarraDeNavHTabla").attr("value", columnaIndice);
              $("#BarraDeNavHTabla").prop("value", columnaIndice);
          }
        } else {
          for (let index = columnaIndice; value <= index ; index--) {
              let columnaActual = columnaIndice;
              let updateMarginLeft = "0px";

              //headABorrar = $('thead tr > *:nth-child(' + columnaActual + ')');
              //columnaABorrar = $('tbody tr > *:nth-child(' + columnaActual + ')');
              //divABorrar = $('tbody tr > *:nth-child(' + columnaActual + ') div div');
              headABorrar = tablaHeadRow.find('> *:nth-child(' + columnaActual + ')');
              columnaABorrar = tablaBodyRows.find('> *:nth-child(' + columnaActual + ')');
              divABorrar = columnaABorrar.find('div div');

              columnaABorrar.removeClass("showColTablaAnimacion");
              columnaABorrar.removeClass("showColTablaAnimacionfire");
              columnaABorrar.find("div div").removeClass("itemMotivoAccesible");
              columnaABorrar.removeClass("hiddenColTablaAnimacion");
              columnaABorrar.removeClass("hiddenColTablaAnimacionfire");

              headABorrar.removeClass("showColTablaAnimacion");
              headABorrar.removeClass("showColTablaAnimacionfire");
              headABorrar.removeClass("hiddenColTablaAnimacion");
              headABorrar.removeClass("hiddenColTablaAnimacionfire");

              divABorrar.css("z-index", "300");
              columnaABorrar.css({
                "margin-left": updateMarginLeft,
                "border-right-width":  "1px",
                "border-left-width": "1px"
              });
              headABorrar.css("margin-left", updateMarginLeft);
              columnaIndice = index;
              valInputRangePrev = index;
              $("#BarraDeNavHTabla").attr("value", index);
              $("#BarraDeNavHTabla").prop("value", index);
          }
        }
      } else {
          let columnaActual = columnaIndice;
          if (value <= columnaIndice && columnaIndice < valInputRangePrev) {
              let columnaActual = columnaIndice;

              //headABorrar = $('thead tr > *:nth-child(' + columnaActual + ')');
              //columnaABorrar = $('tbody tr > *:nth-child(' + columnaActual + ')');
              //divABorrar = $('tbody tr > *:nth-child(' + columnaActual + ') div div');
              headABorrar = tablaHeadRow.find('> *:nth-child(' + columnaActual + ')');
              columnaABorrar = tablaBodyRows.find('> *:nth-child(' + columnaActual + ')');
              divABorrar = columnaABorrar.find('div div');
              divABorrar.css("z-index", "300");
              columnaABorrar.css({
                "margin-left": "0px",
                "border-right-width": "1px",
                "border-left-width": "1px"
              });
              headABorrar.css("margin-left", "0px");
              columnaIndice--;
              columnaActual = columnaIndice;
              let margin = Math.abs(value - columnaActual);
              let width = 190;
              let updateMarginLeft = "-" + margin * width + "px";
              $("#BarraDeNavHTabla").attr("value", columnaIndice);
              headABorrar = $('thead tr > *:nth-child(' + columnaActual + ')');
              columnaABorrar = $('tbody tr > *:nth-child(' + columnaActual + ')');
              divABorrar = $('tbody tr > *:nth-child(' + columnaActual + ') div div');
              columnaABorrar.removeClass("showColTablaAnimacion");
              columnaABorrar.removeClass("showColTablaAnimacionfire");
              columnaABorrar.find("div div").removeClass("itemMotivoAccesible");
              columnaABorrar.removeClass("hiddenColTablaAnimacion");
              columnaABorrar.removeClass("hiddenColTablaAnimacionfire");
              headABorrar.removeClass("showColTablaAnimacion");
              headABorrar.removeClass("showColTablaAnimacionfire");
              headABorrar.removeClass("hiddenColTablaAnimacion");
              headABorrar.removeClass("hiddenColTablaAnimacionfire");
              divABorrar.css("z-index", "-1");
              columnaABorrar.css({
                "margin-left": updateMarginLeft,
                "border-right-width": "0px",
                "border-left-width": "0px"
              });
              headABorrar.css("margin-left", updateMarginLeft);
              $("#BarraDeNavHTabla").attr("value", columnaIndice);
              valInputRangePrev = value;

          } else if (value <= columnaIndice &&  valInputRangePrev <= columnaIndice) {
              if (value < columnaIndice 
                  && valInputRangePrev == columnaIndice 
                  && valInputRangePrev != nroColumnaInicial
                ) {
                //headABorrar = $('thead tr > *:nth-child(' + columnaActual + ')');
                //columnaABorrar = $('tbody tr > *:nth-child(' + columnaActual + ')');
                //divABorrar = $('tbody tr > *:nth-child(' + columnaActual + ') div div');
                headABorrar = tablaHeadRow.find('> *:nth-child(' + columnaActual + ')');
                columnaABorrar = tablaBodyRows.find('> *:nth-child(' + columnaActual + ')');
                divABorrar = columnaABorrar.find('div div');
                divABorrar.css("z-index", "300");
                columnaABorrar.css({
                  "margin-left": "0px",
                  "border-right-width": "1px",
                  "border-left-width": "1px"
                });
                headABorrar.css("margin-left", "0px");
              }
              columnaIndice--;
              let columnaActual = columnaIndice;
              let margin = Math.abs(value - columnaActual);
              let width = 190;
              let updateMarginLeft = "-" + margin * width + "px";
              $("#BarraDeNavHTabla").attr("value", columnaIndice);
              headABorrar = $('thead tr > *:nth-child(' + columnaActual + ')');
              columnaABorrar = $('tbody tr > *:nth-child(' + columnaActual + ')');
              divABorrar = $('tbody tr > *:nth-child(' + columnaActual + ') div div');
              columnaABorrar.removeClass("showColTablaAnimacion");
              columnaABorrar.removeClass("showColTablaAnimacionfire");
              columnaABorrar.find("div div").removeClass("itemMotivoAccesible");
              columnaABorrar.removeClass("hiddenColTablaAnimacion");
              columnaABorrar.removeClass("hiddenColTablaAnimacionfire");
              headABorrar.removeClass("showColTablaAnimacion");
              headABorrar.removeClass("showColTablaAnimacionfire");
              headABorrar.removeClass("hiddenColTablaAnimacion");
              headABorrar.removeClass("hiddenColTablaAnimacionfire");
              columnaABorrar.css({
                "margin-left": updateMarginLeft,
                "border-right-width": "1px",
                "border-left-width": "0px"
              });
              headABorrar.css("margin-left", updateMarginLeft);
              divABorrar.css("z-index","-1");
              $("#BarraDeNavHTabla").attr("value", columnaIndice);
              valInputRangePrev = value;

          } else if (value >= columnaIndice && columnaIndice > valInputRangePrev) {
            columnaIndice++;
            let columnaActual = columnaIndice;
            let margin = Math.abs(value - columnaActual);
            let width = 190;
            let updateMarginLeft = "-" + margin * width + "px";
            $("#BarraDeNavHTabla").attr("value", columnaIndice);
            //headABorrar = $('thead tr > *:nth-child(' + columnaActual + ')');
            //columnaABorrar = $('tbody tr > *:nth-child(' + columnaActual + ')');
            //divABorrar = $('tbody tr > *:nth-child(' + columnaActual + ') div div');
            headABorrar = tablaHeadRow.find('> *:nth-child(' + columnaActual + ')');
            columnaABorrar = tablaBodyRows.find('> *:nth-child(' + columnaActual + ')');
            divABorrar = columnaABorrar.find('div div');
            columnaABorrar.removeClass("showColTablaAnimacion");
            columnaABorrar.removeClass("showColTablaAnimacionfire");
            columnaABorrar.find("div div").removeClass("itemMotivoAccesible");
            columnaABorrar.removeClass("hiddenColTablaAnimacion");
            columnaABorrar.removeClass("hiddenColTablaAnimacionfire");
            headABorrar.removeClass("showColTablaAnimacion");
            headABorrar.removeClass("showColTablaAnimacionfire");
            headABorrar.removeClass("hiddenColTablaAnimacion");
            headABorrar.removeClass("hiddenColTablaAnimacionfire");
            divABorrar.css("z-index", "-1");
            columnaABorrar.css({
              "margin-left": updateMarginLeft,
              "border-right-width": "0px",
              "border-left-width": "0px"
            });
            headABorrar.css("margin-left", updateMarginLeft);
            $("#BarraDeNavHTabla").attr("value", columnaIndice);
            valInputRangePrev = value;
          } else if (value >= columnaIndice &&  valInputRangePrev >= columnaIndice) {
                let columnaActual = columnaIndice;
                let margin = Math.abs(value - columnaActual);
                let width = 190;
                let updateMarginLeft = "-" + margin * width + "px";
                $("#BarraDeNavHTabla").attr("value", columnaIndice);
                //headABorrar = $('thead tr > *:nth-child(' + columnaActual + ')');
                //columnaABorrar = $('tbody tr > *:nth-child(' + columnaActual + ')');
                //divABorrar = $('tbody tr > *:nth-child(' + columnaActual + ') div div');
                headABorrar = tablaHeadRow.find('> *:nth-child(' + columnaActual + ')');
                columnaABorrar = tablaBodyRows.find('> *:nth-child(' + columnaActual + ')');
                divABorrar = columnaABorrar.find('div div');
                columnaABorrar.removeClass("showColTablaAnimacion");
                columnaABorrar.removeClass("showColTablaAnimacionfire");
                columnaABorrar.find("div div").removeClass("itemMotivoAccesible");
                columnaABorrar.removeClass("hiddenColTablaAnimacion");
                columnaABorrar.removeClass("hiddenColTablaAnimacionfire");
                headABorrar.removeClass("showColTablaAnimacion");
                headABorrar.removeClass("showColTablaAnimacionfire");
                headABorrar.removeClass("hiddenColTablaAnimacion");
                headABorrar.removeClass("hiddenColTablaAnimacionfire");
                divABorrar.css("z-index",  "-1");
                columnaABorrar.css({
                  "margin-left": updateMarginLeft,
                  "border-right-width": "0px",
                  "border-left-width": "0px"
                });
                headABorrar.css("margin-left", updateMarginLeft);
                $("#BarraDeNavHTabla").attr("value", columnaIndice);
                valInputRangePrev = value;
          }

      }
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
        columnaABorrar = $('tbody tr > *:nth-child(' + columnaIndice + ')');
        headABorrar = $('thead tr > *:nth-child(' + columnaIndice + ')');
        divABorrar = $('tbody tr > *:nth-child(' + columnaIndice + ') div div');
        if (columnaIndice <= nroColumnasTabla) {
          if (columnaIndice <= nroColumnasTabla) {
            columnaABorrar.removeClass("showColTablaAnimacion");
            headABorrar.removeClass("showColTablaAnimacion");
            columnaABorrar.removeClass("showColTablaAnimacionfire");
            headABorrar.removeClass("showColTablaAnimacionfire");

            columnaABorrar.css({
              "margin-left": "",
              "border-right-width": "",
              "border-left-width": ""
            });
            divABorrar.css("z-index", "");

            columnaABorrar.addClass("hiddenColTablaAnimacion");
            headABorrar.addClass("hiddenColTablaAnimacion");
            columnaABorrar.addClass("hiddenColTablaAnimacionfire");
            headABorrar.addClass("hiddenColTablaAnimacionfire");
            columnaIndice++;
          }
        }
        //$("#BarraDeNavHTabla").attr("value", columnaIndice);
        document.getElementById("BarraDeNavHTabla").value = columnaIndice;
      } else if (e.which == 37) {
        // Left Arrow
        headABorrar = $('thead tr >*:nth-child(' + (columnaIndice - 1) + ')');
        columnaABorrar = $('tbody tr > *:nth-child(' + (columnaIndice - 1) + ')');
        if (columnaIndice >= 5) {
          if (columnaIndice > 5) {
            columnaIndice--;
            columnaABorrar.show();
            headABorrar.show();
            columnaABorrar.removeClass("hiddenColTablaAnimacion");
            headABorrar.removeClass("hiddenColTablaAnimacion");
            columnaABorrar.removeClass("hiddenColTablaAnimacionfire");
            headABorrar.removeClass("hiddenColTablaAnimacionfire");
            columnaABorrar.css({
              "margin-left": "",
              "border-right-width": "",
              "border-left-width": ""
            });
            //divABorrar.css("z-index", "");
            columnaABorrar.addClass("showColTablaAnimacion");
            headABorrar.addClass("showColTablaAnimacion");
            columnaABorrar.addClass("showColTablaAnimacionfire");
            headABorrar.addClass("showColTablaAnimacionfire");

          } else if (columnaIndice == 5) {
            headABorrar.show();
            columnaABorrar.show();
            columnaABorrar.removeClass("hiddenColTablaAnimacion");
            headABorrar.removeClass("hiddenColTablaAnimacion");
            columnaABorrar.removeClass("hiddenColTablaAnimacionfire");
            headABorrar.removeClass("hiddenColTablaAnimacionfire");
            columnaABorrar.addClass("showColTablaAnimacion");
            headABorrar.addClass("showColTablaAnimacion");
            columnaABorrar.addClass("showColTablaAnimacionfire");
            headABorrar.addClass("showColTablaAnimacionfire");
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
      for (var i = 5; i <= (nroColumnasTabla - 1); i++) {
        columnaABorrar = $('tbody tr > *:nth-child(' + columnaIndice + ')');
        headABorrar = $('thead tr > *:nth-child(' + columnaIndice + ')');
        divABorrar = $('tbody tr > *:nth-child(' + columnaIndice + ') div div');
        if (columnaIndice <= nroColumnasTabla) {
          columnaABorrar.removeClass(
            "showColTablaAnimacion showColTablaAnimacionfire"
          );
          headABorrar.removeClass(
            "showColTablaAnimacion showColTablaAnimacionfire"
          );

          columnaABorrar.css({
            "margin-left": " -300px",
            "border-right-width": "0px",
            "border-left-width": "0px"
          });
          divABorrar.css("z-index", "-1");
          headABorrar.css("margin-left", " -300px");
          columnaIndice++;
        }
        document.getElementById("BarraDeNavHTabla").value = columnaIndice;
      }
      tabla.scrollLeft(0);
    }

    function navegacionConBarVNav(e) {
      var value = parseInt(e.target.value);
      var nroFilasTabla = $("tbody > tr").length - 4;
      var filaABorrar = null;
      document.getElementById("BarraDeNavVTabla").value = filaIndice;
      $("#BarraDeNavVTabla").attr("max", nroFilasTabla);
      $("#BarraDeNavVTabla").attr("value", filaIndice);
      if (value < filaIndice) {
        filaIndice--;
        filaABorrar = $('tbody tr:nth-child(' + filaIndice + ')');
        filaABorrar.show();
      } else if (value > filaIndice) {
        filaABorrar = $('tbody tr:nth-child(' + filaIndice + ')');
        filaABorrar.hide();
        filaIndice++;
      }
      $("#BarraDeNavVTabla").attr("value", filaIndice);
    }
  </script>
  <style>
    body {
      overflow-x: hidden;
    }

    #ContenidoTabla {
      padding-left: 0px;
    }

    div {
      user-select: none;
    }

    input[type="range"] {
      width: 80%;
      height: 1.3rem;
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
      -webkit-appearance: none;
      /* Override default look */
      appearance: none;
      margin-top: -3.999999999999999px;
      /* Centers thumb on the track */
      background-color: #b9c3d0;
      border-radius: 0.1rem;
      height: 1.4rem;
      width: 1.9rem;
    }

    #BarraDeNavVTabla {
      margin-left: 86.5%;
      margin-bottom: 13.4%;
      transform: rotate(90deg);
      width: 26.1%;
      height: 0.9rem;
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

    td[id^="Contenido"] {
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

    .td--extenso-height-127 {
      height: 127px !important;
    }

    .table-fixeder tbody tr:nth-child(1) td {
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
      font-size: 0.84rem;
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
      z-index: 200;
      */
      /*left: 600px;*/
      width: 150px;
    }

    #Contenido-Titulo-8 {
      position: sticky;
      z-index: 200;
      */
      /*left: 600px;*/
      width: 150px;
    }

    #Contenido-Titulo-9 {
      position: sticky;
      z-index: 200;
      */
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

          <div class="brand">Auditoria</div>
          <div class="menu-list">
            <?php $Element = new Elements();
            $Element->getMenuNotificacion(0); ?>
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
    if ($TipoUsuario == 2 || $TipoUsuario > 3) {
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
            $Element->getMenuReportes(0); ?>
          </div>
          <div class="brand">Auditoria</div>
          <div class="menu-list">
            <?php $Element = new Elements();
            $Element->getMenuNotificacion(0); ?>
          </div>
          <div class="brand">Auditoria</div>
          <div class="menu-list">
              <?php /*$Element = new Elements();
              $Element->getMenuNotificacion(0);*/?>
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
          <div class="brand">Auditoria</div>
          <div class="menu-list">
            <?php $Element = new Elements();
            $Element->getMenuNotificacion(0); ?>
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
      <div class="row" style="margin-bottom: 0.5rem;">
        <div class="col-3">
          <!--<button class="btn btn-info btn-sm" onClick="toggleZoomScreen()">Zoom +</button> 
            <button class="btn btn-info btn-sm" onClick="toggleZoomScreenNormal()">Zoom -</button>-->
          <div class="number-input">
            <button id="zoomIncrementar" class="plus"></button>
            <input id="input-zoom" value="60" class="quantity" style="padding-right: 3px;" min="0" name="quantity"  type="number">
            <div id="divporcentaje">%</div>
            <button id="zoomDecrementar"></button>
          </div>
        </div>
        <?php
          if (!isset($_REQUEST["Anio"])) {
            if (isset($_REQUEST["Fecha_Desde"])) {
              $Fecha_Inicio = implode("-", array_reverse(explode("/", $_REQUEST["Fecha_Desde"])));
            } else {
              $Fecha_Inicio = null;
            }
            if (isset($_REQUEST["Fecha_Hasta"])) {
              $Fecha_Fin = implode("-", array_reverse(explode("/", $_REQUEST["Fecha_Hasta"])));
            } else {
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
            $Mostrar = (isset($_REQUEST["Mostrar"])) ? $_REQUEST["Mostrar"] : 0;
            $ID_CentroSalud = (isset($_REQUEST["ID_CentroSalud"])) ? $_REQUEST["ID_CentroSalud"] : null;
            $ID_OtraInstitucion = (isset($_REQUEST["ID_OtraInstitucion"])) ? $_REQUEST["ID_OtraInstitucion"] : null;
            $ID_Responsable = (isset($_REQUEST["ID_Responsable"])) ? $_REQUEST["ID_Responsable"] : null;

            $cmb_seleccion = (isset($_REQUEST["cmb_seleccion"])) ? $_REQUEST["cmb_seleccion"] : null;
            $esPersonaSeleccionada = ($ID_Persona) ? ", IF(M.id_persona = $ID_Persona, 1, 0) as esPersona" : "";
            $width_dispay = $width_dispay - 200 - (150 * 3 + 120) * 0.6 - 29;
            $motivos = array_filter($MotivosOpciones, 
                                 function ($x) {
                                              return !empty($x); 
                                           }
                                    );
            $CantOpMotivos = count($motivos);

            $Con = new Conexion();
            $Con->OpenConexion();

            $filtro_motivo = array_reduce($motivos, 
                                       function ($motivos, $valor){
                                                    $con = new Conexion();
                                                    $con->OpenConexion();
                                                    $ret_motivo = new Motivo(
                                                                             coneccion_base: $con, 
                                                                             id_motivo: $valor
                                                                            );
                                                    $con->CloseConexion();
                                                    return $motivos . " - " . $ret_motivo->get_motivo();
                                                 },
                                        "Motivos: "
                                         );

            $listaDeMotivos = "(" . implode(",", array_filter($MotivosOpciones)) . ")";

            $consultaGeneralPermisos = "CREATE TEMPORARY TABLE GIN " ;
            $consultaUsuarioPermisos = "CREATE TEMPORARY TABLE INN ";
        
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
                                              and MT.id_motivo <> 1   
                                              and C.id_categoria NOT IN (SELECT id_categoria
                                                                          FROM categorias_roles CS
                                                                          where estado = 1)";
            $motivosVisiblesParaUsuario = $consultaUsuarioPermisos . $motivosVisiblesParaUsuario;
            $motivosVisiblesParaTodoUsuario = $consultaGeneralPermisos . $motivosVisiblesParaTodoUsuario;
            $MessageError = "Problemas al crear la tabla temporaria de usuarios";
            $motivosUsuario = mysqli_query(
                              $Con->Conexion,$motivosVisiblesParaUsuario
                              ) or die($MessageError);
        
            $MessageError = "Problemas al crear la tabla temporaria general";
            $motivosTodoUsuario = mysqli_query(
                              $Con->Conexion,$motivosVisiblesParaTodoUsuario
                              ) or die($MessageError);

            $Consulta = "SELECT M.id_movimiento, M.fecha, M.id_persona, MONTH(M.fecha) as 'Mes',
                                YEAR(M.fecha) as 'Anio', B.Barrio, P.manzana, P.documento, P.obra_social,
                                P.localidad, P.edad, P.meses, P.lote, P.familia, UPPER(P.apellido) as apellido, P.fecha_nac,
                                P.nombre as nombre, P.fecha_nac, P.domicilio, M.motivo_1, M.motivo_2, M.motivo_3,
                                M.motivo_4, M.motivo_5, MT.motivo, R.responsable, M.observaciones, CS.centro_salud,
                                I.Nombre as 'NombreInst', MST.id_motivo, MST.nro_motivo, L.calle_nombre, P.nro,
                                ST_X(P.georeferencia) as lat, ST_Y(P.georeferencia) as lon, C.color, CF.Forma_Categoria,
                                MT.codigo, C.tipo_categoria";

            $lon_person = null;
            $lat_person = null;
            $json_filtro = [];
            $filtros = [];
            $filtrosSeleccionados = [];
            
            $filtrosSeleccionados["Fecha_Desde"] = $_REQUEST["Fecha_Desde"];
            $filtrosSeleccionados["Fecha_Hasta"] = $_REQUEST["Fecha_Hasta"];

            if ($ID_Persona > 0) {
                $ConsultarPersona = "select apellido, nombre
                              from persona
                              where ID_Persona = " . $ID_Persona." limit 1";

                $EjecutarConsultarPersona = mysqli_query($Con->Conexion,$ConsultarPersona) or die("Problemas al consultar filtro Persona");
                $RetConsultarPersona = mysqli_fetch_assoc($EjecutarConsultarPersona);
                $filtrosSeleccionados["ID_Persona"] = $ID_Persona;
            }

            $persona_query = "SELECT *
                              FROM persona 
                              WHERE estado = 1";

            $motivo_query = "SELECT *
                              FROM motivo
                              WHERE ";
            $categoria_query = "SELECT *
                                FROM categoria ";
            $movimiento_query = "SELECT *
                                  FROM movimiento 
                                  WHERE fecha between '$Fecha_Inicio' and '$Fecha_Fin'
                                    AND estado = 1";

            if ($Edad_Desde !== null && $Edad_Desde !== "" && $Edad_Hasta !== null && $Edad_Hasta !== "") {
              $persona_query .= " and edad >= $Edad_Desde and edad <= $Edad_Hasta";
              $filtros[] = "Edad: Desde " . $Edad_Desde . " hasta " . $Edad_Hasta;
              if ($Meses_Hasta !== null && $Meses_Hasta !== "") {
                $Consulta .= " and (edad < $Edad_Hasta or meses <= $Meses_Hasta)";
                if ($Meses_Desde != null) {
                  $Consulta .= " and meses >= $Meses_Desde ";
                  $filtros[] = "Meses: Desde " . $Meses_Desde . " hasta " . $Meses_Hasta;
                } else {
                  $filtros[] = "Meses: Desde 0 hasta " . $Meses_Hasta;
                }
              }
            } else {
              if ($Meses_Desde !== null && $Meses_Desde !== "" && $Meses_Hasta !== null && $Meses_Hasta !== "") {
                $persona_query .= " and meses <= $Meses_Hasta and edad = 0 ";
                if ($Meses_Desde != null) {
                  $Consulta .= " and meses >= $Meses_Desde";
                  $filtros[] = "Meses: Desde " . $Meses_Desde . " hasta " . $Meses_Hasta;
                } else {
                  $filtros[] = "Meses: Desde 0 hasta " . $Meses_Hasta;
                }
              }
            }

            if($Domicilio != null && $Domicilio != ""){
              $persona_query .= " and domicilio like '%$Domicilio%'";
              $filtros[] = "Domicilio: " . $Domicilio;
              $filtrosSeleccionados["Domicilio"] = $Domicilio;
            }

            if($Manzana != null && $Manzana != ""){
              $persona_query .= " and manzana = '$Manzana'";
              $filtros[] = "Manzana: " . $Manzana;
              $filtrosSeleccionados["Manzana"] = $Manzana;
            }

            if($Lote != null && $Lote != ""){
              $persona_query .= " and lote = $Lote";
              $filtros[] = "Lote: " . $Lote;
              $filtrosSeleccionados["Lote"] = $Lote;
            }

            if($Familia != null && $Familia != ""){
              $persona_query .= " and familia = $Familia";
              $filtros[] = "Sublote: " . $Familia;
              $filtrosSeleccionados["Familia"] = $Familia;
            }

            if($Nro_Carpeta != null && $Nro_Carpeta != ""){
              $persona_query .= " and nro_carpeta = '$Nro_Carpeta'";
              $filtros[] = "Nro_carpeta: " . $Nro_Carpeta;
            }
            $filtrosSeleccionados["Nro_Carpeta"] = $Nro_Carpeta;

            if($Nro_Legajo != null && $Nro_Legajo != ""){
              $persona_query .= " and nro_legajo = '$Nro_Legajo'";
              $filtros[] =  " Nro_legajo : " . $Nro_Legajo;
              $filtrosSeleccionados["Nro_Legajo"] = $Nro_Legajo;
            }

            if(count((Array)$Barrio) > 1){
              $filtroBarrios = 'Barrios:';
              foreach($Barrio as $key => $valueBarrio){
                if($key == $Barrio->array_key_first){
                  $persona_query .= " and (";
                }
                if($valueBarrio > 0){
                  if($key === count($Barrio) - 1){
                    $persona_query .= " ID_Barrio = $valueBarrio )";
                  }else{
                    $persona_query .= " ID_Barrio = $valueBarrio or";
                  }

                  $ConsultarBarrio = "select Barrio 
                                      from barrios 
                                      where ID_Barrio = " . $valueBarrio." limit 1";

                  $EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                  $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
                  if($key == $Barrio->array_key_first){
                    $filtroBarrios .= " " . $RetConsultarBarrio['Barrio'];
                  }else{
                    $filtroBarrios .= " - " . $RetConsultarBarrio['Barrio'];
                  }
                }
              }
              $filtros[] = $filtroBarrios;
            } else {
              if ($Barrio[0] > 0) {
                $persona_query .= " and ID_Barrio = $Barrio[0]";
                $ConsultarBarrio = "select Barrio 
                                    from barrios 
                                    where ID_Barrio = " . $Barrio[0]." limit 1";
                $EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die("Problemas al consultar filtro Barrios");
                $RetConsultarBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
                $filtros[] = "Barrio: " . $RetConsultarBarrio['Barrio'];
                $filtrosSeleccionados["ID_Barrio"] = $Barrio[0];
              }
            }


            if($ID_Escuela > 0){                
              $persona_query .= " and ID_Escuela = $ID_Escuela";
              $ConsultarEscuela = "select Escuela 
                                    from escuelas 
                                    where ID_Escuela = " . $ID_Escuela." limit 1";
              $EjecutarConsultarEscuela = mysqli_query($Con->Conexion,$ConsultarEscuela) or die("Problemas al consultar filtro Escuela");
              $RetConsultarEscuela = mysqli_fetch_assoc($EjecutarConsultarEscuela);  
              $filtros[] = "Escuela: " . $RetConsultarEscuela['Escuela'];
              $filtrosSeleccionados["ID_Escuela"] = $ID_Escuela;
            }

            if($Trabajo != null && $Trabajo != ""){
              $persona_query .= " and Trabajo like '%$Trabajo%'";
              $filtros[] = "Trabajo: " . $Trabajo;
              $filtrosSeleccionados["Trabajo"] = $Trabajo;                
            }

            if($ID_Categoria > 0){
              $categoria_query .= " WHERE id_categoria = $ID_Categoria";

              $ConsultarCategoria = "select categoria
                                    from categoria
                                    where id_categoria = " . $ID_Categoria." limit 1";

              $EjecutarConsultarCategoria = mysqli_query($Con->Conexion,$ConsultarCategoria) or die("Problemas al consultar filtro Categoria");
              $RetConsultarCategoria = mysqli_fetch_assoc($EjecutarConsultarCategoria);
              $filtros[] = "Categoria: " . $RetConsultarCategoria['categoria'];
              $filtrosSeleccionados["ID_Categoria"] = $ID_Categoria;
            }

            if($ID_CentroSalud > 0){
              $movimiento_query  .= " AND id_centro = $ID_CentroSalud";
              $ConsultarCentroSalud = "select centro_salud 
                                        from centros_salud 
                                        where id_centro = " . $ID_CentroSalud." limit 1";
              $EjecutarConsultarCentroSalud = mysqli_query($Con->Conexion,$ConsultarCentroSalud) or die("Problemas al consultar filtro Categoria");
              $RetConsultarCentroSalud = mysqli_fetch_assoc($EjecutarConsultarCentroSalud);                  
              $filtros[] = "Centro Salud: " . $RetConsultarCentroSalud['centro_salud'];
              $filtrosSeleccionados["ID_CentroSalud"] = $ID_CentroSalud;
            }

            if($ID_OtraInstitucion > 0){
              $movimiento_query  .= " and ID_OtraInstitucion = $ID_OtraInstitucion";
              $ConsultarOtraInstitucion = "select Nombre 
                                            from otras_instituciones 
                                            where ID_OtraInstitucion = " . $ID_OtraInstitucion." limit 1";
              $EjecutarConsultarOtraInstitucion = mysqli_query($Con->Conexion,$ConsultarOtraInstitucion) or die("Problemas al consultar filtro Categoria");
              $RetConsultarOtraInstitucion = mysqli_fetch_assoc($EjecutarConsultarOtraInstitucion);   
              $filtros[] = "Otra Institucion: " . $RetConsultarOtraInstitucion['Nombre'];
              $filtrosSeleccionados["ID_OtraInstitucion"] = $ID_OtraInstitucion;
            }

            if($ID_Responsable > 0){
              $movimiento_query  .= " and id_resp = $ID_Responsable";
              $ConsultarResponsable = "select responsable 
                                        from responsable 
                                        where id_resp = " . $ID_Responsable." limit 1";
              $EjecutarConsultarResponsable = mysqli_query($Con->Conexion,$ConsultarResponsable) or die("Problemas al consultar filtro Responsable");
              $RetConsultarResponsable = mysqli_fetch_assoc($EjecutarConsultarResponsable);   
              $filtros[] = "Responsable: " . $RetConsultarResponsable['responsable'];
              $filtrosSeleccionados["ID_Responsable"] = $ID_Responsable;
            }

            if (count(array_filter($MotivosOpciones))) {
              $motivo_query = "(" . $motivo_query;
              $motivo_query .= " id_motivo in $listaDeMotivos)";
              $filtros[] = $filtro_motivo;
            } else {
              $motivo_query = "motivo";
            }

            if ($ID_Persona > 0) {
              $countPostfield = count(array_filter($_POST, function ($element) {
                return $element;
              }));

              $ConsultaFlia = $Consulta;
              $ConsultarPersona = "select apellido, 
                                          nombre, 
                                          domicilio,
                                          ID_Barrio,
                                          calle,
                                          nro,
                                          ST_X(georeferencia) as lat, 
                                          ST_Y(georeferencia) as lon
                                   from persona 
                                   where ID_Persona = " . $ID_Persona . " 
                                     and estado = 1";
              $EjecutarConsultarPersona = mysqli_query($Con->Conexion, $ConsultarPersona) or die("Problemas al consultar filtro Persona");
              $RetConsultarPersona = mysqli_fetch_assoc($EjecutarConsultarPersona);
              $lon_person = (!empty($RetConsultarPersona["lon"])) ? $RetConsultarPersona["lon"]: null; 
              $lat_person = (!empty($RetConsultarPersona["lat"])) ? $RetConsultarPersona["lat"]: null;
              if (($countPostfield - 3) == 1) {
                if (!(empty($RetConsultarPersona["domicilio"]) && (empty($RetConsultarPersona["calle"]) || empty($RetConsultarPersona["nro"])))) {
                  $domicilio = $RetConsultarPersona["domicilio"];
                  $persona = new Persona(ID_Persona: $ID_Persona);
                  $domicilioPersona = "";
                  if ($persona->getCalle() && $persona->getNroCalle()) {
                    $domicilioPersona = "domicilio like '%" . $persona->getCalle() . "%" . $persona->getNroCalle() . "%' or ";
                  }
                  $ConsultarPersdomicilio = "select id_persona
                                            from persona
                                            where ($domicilioPersona (calle = " . (($persona->getId_Calle()) ? $persona->getId_Calle() : "null") . "
                                                  and nro = " . (($persona->getNro()) ? $persona->getNro() : "null") . "))
                                              and estado = 1";
                  $persona_query  .= " and id_persona in ($ConsultarPersdomicilio)";
                } else {
                  $persona_query  .= " and id_persona = $ID_Persona";
                }
              } else {
                $persona_query  .= " and id_persona = $ID_Persona";
              }
              $filtros[] = "Persona: " . $RetConsultarPersona["apellido"] . ", " . $RetConsultarPersona["nombre"];
              $json_filtro[] = "Persona " . $RetConsultarPersona["apellido"] . " " . $RetConsultarPersona["nombre"];
            }
            if ($Mostrar) {
                $Consulta .=  " FROM ($movimiento_query) M
                                  INNER JOIN movimiento_motivo MST 
                                  ON (M.id_movimiento = MST.id_movimiento)
                                  INNER JOIN $motivo_query MT
                                  ON (MST.id_motivo = MT.id_motivo)
                                  INNER JOIN ((SELECT * FROM GIN) UNION (SELECT * FROM INN)) GN
                                  ON (GN.id_motivo = MT.id_motivo)
                                  RIGHT JOIN ($persona_query) P 
                                  ON (M.id_persona = P.id_persona)
                                  INNER JOIN barrios B 
                                  ON (B.ID_Barrio = P.ID_Barrio)                                    
                                  LEFT JOIN ($categoria_query) C
                                  ON (C.cod_categoria = MT.cod_categoria)
                                  LEFT JOIN centros_salud CS
                                  ON (M.id_centro = CS.id_centro)
                                  LEFT JOIN otras_instituciones I
                                  ON (M.id_otrainstitucion = I.ID_OtraInstitucion)
                                  LEFT JOIN responsable R
                                  ON (R.id_resp = M.id_resp)
                                  LEFT JOIN calle L 
                                  ON (L.id_calle = P.calle)
                                  LEFT JOIN formas_categorias CF 
                                  ON (CF.ID_Forma = C.ID_Forma)";
                                
            } else {
                $Consulta .=  " FROM ($movimiento_query) M
                                  INNER JOIN movimiento_motivo MST 
                                  ON (M.id_movimiento = MST.id_movimiento)
                                  INNER JOIN $motivo_query MT
                                  ON (MST.id_motivo = MT.id_motivo)
                                  INNER JOIN ((SELECT * FROM GIN) UNION (SELECT * FROM INN)) GN
                                  ON (GN.id_motivo = MT.id_motivo)
                                  INNER JOIN ($persona_query) P 
                                  ON (M.id_persona = P.id_persona)
                                  INNER JOIN barrios B 
                                  ON (B.ID_Barrio = P.ID_Barrio)                                    
                                  INNER JOIN ($categoria_query) C
                                  ON (C.cod_categoria = MT.cod_categoria)
                                  INNER JOIN centros_salud CS
                                  ON (M.id_centro = CS.id_centro)
                                  INNER JOIN otras_instituciones I
                                  ON (M.id_otrainstitucion = I.ID_OtraInstitucion)
                                  INNER JOIN responsable R
                                  ON (R.id_resp = M.id_resp)
                                  LEFT JOIN calle L 
                                  ON (L.id_calle = P.calle)
                                  LEFT JOIN formas_categorias CF 
                                  ON (CF.ID_Forma = C.ID_Forma)";

            }
  
            $ConsultarMovimientosPersona = $Consulta;


            if ($ID_Persona > 0) {
              $Consulta .= " group by M.id_movimiento 
                             order by B.Barrio DESC, P.domicilio DESC, P.manzana DESC, P.lote DESC, P.familia DESC,
                                   P.domicilio DESC, P.apellido DESC, M.fecha ASC, M.id_movimiento ASC";
              //$Consulta .= " order by esPersona DESC, B.Barrio DESC, P.domicilio DESC, P.manzana DESC, P.lote DESC, P.familia DESC,
              //                     P.domicilio DESC, P.apellido DESC, M.fecha DESC, M.id_movimiento DESC";
            } else {
              $Consulta .= " order by B.Barrio DESC, P.domicilio DESC , P.manzana DESC, P.lote DESC, P.familia DESC,
                                      P.domicilio DESC, P.apellido DESC, P.id_persona ASC, M.fecha ASC, M.id_movimiento ASC";
            }

            $MensajeError = "No se pudieron consultar los Datos";

            $Etiqueta_Fecha_Inicio = implode("-", array_reverse(explode("-", $Fecha_Inicio)));
            $Etiqueta_Fecha_Fin = implode("-", array_reverse(explode("-", $Fecha_Fin)));

          ?>
          <div class="col-5">
            <center>
              <p class="LblForm">ENTRE: <?php echo $Etiqueta_Fecha_Inicio . " Y " . $Etiqueta_Fecha_Fin; ?></p>
            </center>
            <!-- <span><i class="fa fa-filter"></i> Filtros </span> -->
          </div>
          <div class="col-4">
            <button type="button" class="btn btn-danger" style="margin-left: 27%;"
              onclick="location.href = 'view_general_new.php'">Atrás</button>
            <!--<button type="button" class="btn btn-secondary" onclick="enviarImprimir()">**Imprimir</button>-->
            <!--button type="button" class="btn btn-secondary" onclick="enviarImprimirPdf();"> Imprimir</button>-->
            <button type="button" class="btn btn-secondary" data-toggle="modal"
              style="background-color: #ffc6b1; color: black; border-color: white; " data-target="#map-modal">S. I. G.</button>
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#configModal">Config</button>
          </div>
        </div>
        <?php
          if (count($filtros) > 0) {
        ?>
        <p style="text-align: center; margin-bottom: 0.7rem"> Filtros seleccionados 
        <?php
            foreach ($filtros as $value) {
              echo "<span class='etFiltros'>" . $value . "</span> ";

            }
        ?>
        </p>
        <?php
          }
        ?>
        <div class="row">
          <div class="offset-md-3 col-md-6">
           <!-- <?php echo NOMBRE_ENTIDAD ?> -->
          </div>
        </div>
        <div class="col-md-12">

          <div class="table-responsive" id="tabla-responsive">
            <?php
          
            $tomarRetTodos = array();

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
            //     $arrIDMovimientos[] = $value["id_movimiento"];
            //     //TODO: revisar bien esto
            //     // $arrIDMovimientos[] = $value;
            // }
          
            /*while ($Ret = $Con->ResultSet->fetch_assoc()) {
              $arrIDMovimientos[] = $Ret['id_movimiento'];
            }*/
          

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
                             <thead id='header-tabla' class='thead-dark' style='transform-origin : 0 0; transform : scale(0.6);'>
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

            $nro_col_disponible = floor($width_dispay/ (190 * 0.6));
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
              $fecha_tabla["fecha"] = $Mes_Actual_Bandera . "/" . $Anio_Actual_Bandera;
              $fecha_tabla["anio"] = $Anio_Actual_Bandera + 2000;
              $fecha_tabla["mes"] = $Mes_Actual_Bandera;
              if ($i >= ($MesesDiferencia - $nro_col_disponible)) {
                $fecha_tabla["td_hidden"] = "";
                $fecha_tabla["div_hidden"] = "";
              } else {
                $fecha_tabla["td_hidden"] = " margin-left: -300px; border-right-width: 0px; border-left-width: 0px;"; 
                $fecha_tabla["div_hidden"] = "z-index: -1";
              }
              $arr[] = $fecha_tabla;
              $Mes_Actual_Bandera++;
            }
            //$arr = array_reverse($arr);
            $nroColumnas += $MesesDiferencia;
            $nro_column = count($arr);

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

            $nro_column_header = $nro_column;
            foreach ($arr as $key => $value) {
              if ($value != "") {
                // TODO: Cambiando de tamaño las columnas
                if ($nro_column_header <= $nro_col_disponible) {
                  $Table .= "<th name='DatosResultados' style='min-width: 190px;'>" . $value["fecha"] . "</th>";
                } else {
                  $Table .= "<th name='DatosResultados' style='min-width: 190px; margin-left: -300px;'>" . $value["fecha"] . "</th>";
                }
                $mesesHeader[] = $value["fecha"];
                $nro_column_header--;
              }
            }

            // ob_start();   
          
            $Table .= "</tr>
                    </thead>
                <tbody id='cuerpo-tabla' style='border-style: none; transform-origin : 0 0; transform : scale(0.6);'>";

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////  // FIN TABLE HEADER //////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
          
            /*
            //	CREANDO FILTRO MOSTRAR - Mostrar = "0" Con Movimientos,  "1" sin movimientos.
            if ($Mostrar > 0) {
              //, P.nro_legajo, P.nro_carpeta
              $ConsultarTodos = "SELECT P.id_persona, B.Barrio, P.manzana, P.lote, P.familia, 
                                          P.apellido, P.nombre, P.fecha_nac, P.domicilio,
                                          L.calle_nombre, P.nro, P.edad, P.meses
                                   FROM (persona P, 
                                        barrios B) LEFT JOIN 
                                        calle L ON (L.id_calle = P.calle)
                                   WHERE not exists(select * 
                                                    from movimiento M2 
                                                    where M2.id_persona = P.id_persona) 
                                      AND B.ID_Barrio = P.ID_Barrio 
                                      AND P.estado = 1";

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

              $ConsultarTodos .= " group by P.id_persona 
                                   order by B.Barrio DESC, P.domicilio DESC, P.apellido DESC, P.nombre DESC";

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
                // $Table .= "<td id='Contenido-1'>".$RetTodos["Barrio"]."</td><td id='Contenido-2'>".$RetTodos["domicilio"]."</td><td id='Contenido-3' name='datosflia' style='max-width: 50px;'>".$RetTodos["manzana"]."</td><td id='Contenido-4' name='datosflia' style='max-width: 50px;'>".$RetTodos["lote"]."</td><td id='Contenido-5' name='datosflia' style='max-width: 50px;'>".$RetTodos["familia"]."</td><td id='Contenido-6'><a href = 'javascript:window.open(\"view_modpersonas.php?ID=".$RetTodos["id_persona"]."\",\"Ventana".$RetTodos["id_persona"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$RetTodos["apellido"].", ".$RetTodos["nombre"]."</a></td><td id='Contenido-7' style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";
          
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
              //   $Table .= "<td id='Contenido-1'>".$Ret["Barrio"]."</td><td id='Contenido-2'>".$Ret["domicilio"]."</td><td id='Contenido-3' name='datosflia' style='max-width: 50px;'>".$Ret["manzana"]."</td><td id='Contenido-4' name='datosflia' style='max-width: 50px;'>".$Ret["lote"]."</td><td id='Contenido-5' name='datosflia' style='max-width: 50px;'>".$Ret["familia"]."</td><td id='Contenido-6'><a href = 'javascript:window.open(\"view_modpersonas.php?ID=".$Ret["id_persona"]."\",\"Ventana".$Ret["id_persona"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>".$Ret["apellido"].", ".$Ret["nombre"]."</a></td><td id='Contenido-7' style='max-width: 100px;'>".$Fecha_Nacimiento."</td>";
              // }
          
              $ID_Persona_Bandera = $Ret["id_persona"];
              //if ($ID_Persona_Nuevo !== $ID_Persona_Bandera) {
              // foreach ($arr as $key => $value) {
              //     $Separar = explode("/",$value);
              //     $Mes = $Separar[0];
              //     $Anio = $Separar[1];                                          
              //     $Consultar_Movimientos_Persona = "select * from movimiento where id_persona = ".$Ret["id_persona"]." and MONTH(fecha) = ".$Mes." and YEAR(fecha) like '%".$Anio."'";
          

              //     $Tomar_Movimientos_Persona = mysqli_query($Con->Conexion,$Consultar_Movimientos_Persona) or die($MensajeErrorConsultar_Mov_Persona." - ".$Consultar_Movimientos_Persona);
          
          
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
                    
              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
          
              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
          
              //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
              //     }
              //   }elseif($ID_Motivo2 > 0){
              //     if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_1"]){
              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
                    
              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
          
              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
          
              //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
              //     }
              //   }elseif($ID_Motivo3 > 0){
              //     if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_1"]){
              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
                    
              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
          
              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
          
              //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
              //     }
              //   }else{                                                        
              //     $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_1"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
              //     $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
                    
              //     $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_1"]);
          
              //     $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
          
              //     $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; padding: 0px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";
              //   }
              // }
          
              // if($Ret_Datos_Movimiento["motivo_2"] > 1){
              //   if($ID_Motivo > 0){
              //     if($ID_Motivo == $Ret_Datos_Movimiento["motivo_2"]){
              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
                    
              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_2"]);
          
              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
          
              //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
              //     }
              //   }elseif($ID_Motivo2 > 0){
              //     if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_2"]){
              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
                    
              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_2"]);
          
              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
          
              //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
              //     }
              //   }elseif($ID_Motivo3 > 0){
              //     if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_2"]){
              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
                    
              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_2"]);
          
              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
          
              //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
              //     }
              //   }else{ 
              //     $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_2"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
              //     $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
          

              //     $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor);
          
              //     $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
          


              //     $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"]."; text-align= center;'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";
              //   }
              // }
          

              // if($Ret_Datos_Movimiento["motivo_3"] > 1){
              //   if($ID_Motivo > 0){
              //     if($ID_Motivo == $Ret_Datos_Movimiento["motivo_3"]){
              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
                    
              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_3"]);
          
              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
          
              //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
              //     }
              //   }elseif($ID_Motivo2 > 0){
              //     if($ID_Motivo2 == $Ret_Datos_Movimiento["motivo_3"]){
              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
                    
              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_3"]);
          
              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
          
              //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
              //     }
              //   }elseif($ID_Motivo3 > 0){
              //     if($ID_Motivo3 == $Ret_Datos_Movimiento["motivo_3"]){
              //       $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
              //       $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
                    
              //       $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor." valor:".$Ret_Datos_Movimiento["motivo_3"]);
          
              //       $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
          
              //       $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";                                  
              //     }
              //   }else{ 
              //     $ConsultarCodyColor = "select M.cod_categoria, F.Forma_Categoria, C.color from motivo M, categoria C, formas_categorias F where M.id_motivo = ".$Ret_Datos_Movimiento["motivo_3"]." and M.cod_categoria = C.cod_categoria and C.ID_Forma = F.ID_Forma and M.estado = 1 and C.estado = 1";
              //     $MensajeErrorConsultarCodyColor = "No se pudieron consultar los motivos de los Movimientos";
          

              //     $TomarCodyColor = mysqli_query($Con->Conexion, $ConsultarCodyColor) or die($MensajeErrorConsultarCodyColor." - ".$ConsultarCodyColor);
          
              //     $RetMotivo = mysqli_fetch_assoc($TomarCodyColor);
          

              //     $Table .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center;'><a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=".$Ret_Datos_Movimiento["id_movimiento"]."\",\"Ventana".$Ret_Datos_Movimiento["id_movimiento"]."\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'><span style='font-size: 30px; color: ".$RetMotivo["color"].";'>".$RetMotivo["Forma_Categoria"]."<center><span class='nombreCategoria' color: '".$RetMotivo["color"]."'>".$RetMotivo["cod_categoria"]."</span></center></span></a></div>";
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
          
            $tomar_movimientos = mysqli_query($Con->Conexion, $Consulta) or die($MensajeError . " Consulta: " . $Consulta);

            $json_row = [];
            $view = 0;
            $count = 0;
            $imprimir = false;
            $carga = false;
            $con_movimiento = false;

            $nroMotivosEnFecha = 0;
            $clave = 0;
            $tagsTD = "";
            $tagsTD_imprimir = "";

            while ($RetTodos = mysqli_fetch_assoc($tomar_movimientos)) {
                $count++;
                if (empty($RetTodos["id_movimiento"])) {


                  if ($RetTodos["fecha_nac"] == 'null') {
                    $Fecha_Nacimiento = "Sin Datos";
                  } else {
                    $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$RetTodos["fecha_nac"])));
                  }

                  $Apellido = $RetTodos["apellido"];
                  $Nombre = $RetTodos["nombre"];
                  $id_persona = $RetTodos["id_persona"];
                  $sin_datos = "";

                  $DNI = $RetTodos["documento"];
                  $Edad = $RetTodos["edad"];
                  $Meses = $RetTodos["meses"];
                  $Obra_Social = $RetTodos["obra_social"];
                  $Domicilio = $RetTodos["domicilio"];
                  $Barrio = $RetTodos["Barrio"];
                  $Localidad = $RetTodos["localidad"];
                  
                  $Table .= "<tr class='Datos'>";
                  $jsonTable[$clave]["barrio"] = $RetTodos["Barrio"];
                  $jsonTable[$clave]["domicilio"] = ((empty($RetTodos["domicilio"])) ? $RetTodos["domicilio"] : $RetTodos["calle_nombre"] . " " . $RetTodos["nro"]);
                  $Table .= "<td id='Contenido-1'>" . $RetTodos["Barrio"] . "</td>
                               <td id='Contenido-2'>" . ((empty($RetTodos["domicilio"])) ? $RetTodos["domicilio"] : $RetTodos["calle_nombre"] . " " . $RetTodos["nro"]) . "</td>";
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
                  $jsonTable[$clave]["manzana"] = $RetTodos["manzana"];
                  $Table .= "<td id='Contenido-6' name='datosflia' style='max-width: 50px; display: none'>" . $RetTodos["lote"] . "</td>";
                  $jsonTable[$clave]["lote"] = $RetTodos["lote"];
                  $Table .= "<td id='Contenido-7' name='datosflia' style='max-width: 70px; display: none'>" . $RetTodos["familia"] . "</td>";
                  $jsonTable[$clave]["familia"] = $RetTodos["familia"];
                  $Table .= " <td id='Contenido-3' style='overflow: hidden;'>
                                  <div style='position: relative;z-index: 1000;'>
                                    <a href = 'javascript:window.open(\"view_modpersonas.php?ID=" . $RetTodos["id_persona"] . "\",\"Ventana" . $RetTodos["id_persona"] . "\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>" .
                    $RetTodos["apellido"] . ", " . $RetTodos["nombre"] . "
                                    </a>
                                  </div>
                                </td>
                                <td id='Contenido-4' style='min-width: 120px;'>" .
                    $Fecha_Nacimiento . "
                                </td>";
                  $jsonTable[$clave]["persona"] = $RetTodos["apellido"] . ", " . $RetTodos["nombre"];
                  $jsonTable[$clave]["id_persona"] = $RetTodos["id_persona"];
                  $jsonTable[$clave]["fechanac"] = $Fecha_Nacimiento;
  
                  $Table .= "<td id='Contenido-8' name='datosflia' style='max-width: 70px; display: none; text-align: center; background-color: white;'>" . $RetTodos["edad"] . "</td>";
                  $jsonTable[$clave]["edad"] = $RetTodos["edad"];
                  $Table .= "<td id='Contenido-9' name='datosflia' style='max-width: 70px; display: none; text-align: center;  background-color: white;'>" . $RetTodos["meses"] . "</td>";
                  $jsonTable[$clave]["meses"] = $RetTodos["meses"];
  
                  foreach ($arr as $key => $value) {
                      $Table .= "<td name='DatosResultados' id=$IndexCelda style='min-width: 190px'>
                                 </td>";
                  }
                  continue;

                } else {
                  $con_movimiento = true;
                }

                if ($view != $RetTodos["id_persona"] && $con_movimiento) {

                  $imprimir = false;
                  $view = $RetTodos["id_persona"];
                  $carga = true;
                  $clave++;
                }

                if ($carga && !$imprimir && $con_movimiento) {
                  if ($RetTodos["fecha_nac"] == 'null') {
                    $Fecha_Nacimiento = "Sin Datos";
                  } else {
                    $Fecha_Nacimiento = implode("-", array_reverse(explode("-",$RetTodos["fecha_nac"])));
                  }
                  $imprimir = true;
                  $ID_Movimiento = $RetTodos["id_movimiento"];
                  $id_persona = $RetTodos["id_persona"];
                  $Fecha = implode("-", array_reverse(explode("-",$RetTodos["fecha"])));
                  $Apellido = $RetTodos["apellido"];
                  $Nombre = $RetTodos["nombre"];
                  $Observaciones = $RetTodos["observaciones"];
                  $Responsable = $RetTodos["responsable"];

                  $CentroSalud = $RetTodos["centro_salud"];
                  $OtraInstitucion = $RetTodos["NombreInst"];
                  /////////////////////////////////////////////////////////////
                  $DNI = $RetTodos["documento"];
                  $Edad = $RetTodos["edad"];
                  $Meses = $RetTodos["meses"];
                  $Obra_Social = $RetTodos["obra_social"];
                  $Domicilio = $RetTodos["domicilio"];
                  $Barrio = $RetTodos["Barrio"];
                  $Localidad = $RetTodos["localidad"];
                  /////////////////////////////////////////////////////////////
                  $carga = false;


                  $tagsTD = "";
                  $tagsTD_imprimir = "";
                  $tdExtenso = false;
  
                  $Table .= "<tr class='Datos'>";
                  $nroColumnas = 70;
                  $tagsTD .= "<td id='Contenido-1'>" . $RetTodos["Barrio"] . "</td>
                                <td id='Contenido-2'>" . ((empty($RetTodos["domicilio"])) ? $RetTodos["domicilio"] : $RetTodos["calle_nombre"] . " " . $RetTodos["nro"]) . "</td>";
                  $jsonTable[$clave]["barrio"] = $RetTodos["Barrio"];
                  $jsonTable[$clave]["domicilio"] = ((empty($RetTodos["domicilio"])) ? $RetTodos["domicilio"] : $RetTodos["calle_nombre"] . " " . $RetTodos["nro"]);
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
                  $jsonTable[$clave]["lote"] = $RetTodos["lote"];
                  $tagsTD .= "<td id='Contenido-7' name='datosflia' style='max-width: 70px; display: none'>" . $RetTodos["familia"] . "</td>";
                  $jsonTable[$clave]["familia"] = $RetTodos["familia"];
                  $tagsTD .= "
                    <td id='Contenido-3' style='overflow: hidden;'>
                      <div style='position: relative;z-index: 1000;'>
                        <a href = 'javascript:window.open(\"view_modpersonas.php?ID=" . $RetTodos["id_persona"] . "\",\"Ventana" . $RetTodos["id_persona"] . "\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")' target='_top' rel='noopener noreferrer'>" .
                    $RetTodos["apellido"] . ", " . $RetTodos["nombre"] . "
                        </a>
                      </div>
                    </td>
                    <td id='Contenido-4' style='min-width: 120px;'>" .
                    $Fecha_Nacimiento . "
                    </td>";
                  $jsonTable[$clave]["persona"] = $RetTodos["apellido"] . ", " . $RetTodos["nombre"];
                  $jsonTable[$clave]["id_persona"] = $RetTodos["id_persona"];
                  $jsonTable[$clave]["fechanac"] = $Fecha_Nacimiento;
                  $tagsTD .= "<td id='Contenido-8' name='datosflia' style='max-width: 70px; display: none; text-align: center; background-color: white;'>" . $RetTodos["edad"] . "</td>";
                  $jsonTable[$clave]["edad"] = $RetTodos["edad"];
                  $tagsTD .= "<td id='Contenido-9' name='datosflia' style='max-width: 70px; display: none; text-align: center;  background-color: white;'>" . $RetTodos["meses"] . "</td>";
                  $jsonTable[$clave]["meses"] = $RetTodos["meses"];

                  $tagsMotivos = "";
                }

                if ($RetTodos["id_movimiento"]
                    && ($view == $RetTodos["id_persona"]) 
                    && $imprimir
                    && $con_movimiento) {
                    $nro_col_body = $nro_column;
                    foreach ($arr as $key => $value) {
                        $Mes = $value["mes"];
                        $Anio = $value["anio"];
                        $tagsMotivos = "";
                        $nroMotivosEnFecha = 0;
                        $td_hidden = " margin-left: -300px; border-right-width: 0px; border-left-width: 0px;";
                        $div_hidden = "z-index: -1;";
                        $tagsTD .= "<td name='DatosResultados' id=$IndexCelda style='min-width: 190px; " . $value["td_hidden"] . "'>
                                      <div class = 'row' style='margin:0'>";
                          while (!empty($RetTodos) && $view == $RetTodos["id_persona"]) {

                            if (!empty($RetTodos)
                                && ($RetTodos["Mes"] == $value["mes"])
                                && ($RetTodos["Anio"] == $value["anio"])) {
                                    $motivo = in_array($RetTodos["id_motivo"], array_values($MotivosOpciones));
                                    if (($CantOpMotivos == 0) || $motivo) {
                                          $nroMotivosEnFecha += 1;
                                          $tagsMotivos .= ($nroMotivosEnFecha == 7) ? "<div>" : "";
                                          $tagsTD .= "<div class = 'col-md-2' style = 'padding: 0; text-align: center; " . $value["div_hidden"] . "'>
                                                        <a style='text-decoration: none;' href = 'javascript:window.open(\"view_vermovimientos.php?ID=" . $RetTodos["id_movimiento"] . "\",\"Ventana" . $RetTodos["id_movimiento"] . "\",\"width=1100,height=500,scrollbars=no,top=150,left=250,resizable=no\")'>
                                                          <span style='font-size: 30px; color: " . $RetTodos["color"] . ";'>" .
                                                            $RetTodos["Forma_Categoria"] . "
                                                            <center>
                                                              <span class='nombreCategoria'>" .
                                                                $RetTodos["codigo"] . "
                                                              </span>
                                                            </center>
                                                          </span>
                                                        </a>
                                                      </div>";
                                          $marginLeft = (strlen($RetTodos["codigo"]) >= $nro_col_disponible) ? "margin-left:10px" : "margin-left:2px";

                                          $jsonTable[$clave]["$Mes/$Anio"][] = [
                                                                                $RetTodos["Forma_Categoria"],
                                                                                $RetTodos["codigo"],
                                                                                $RetTodos["color"]
                                                                                ];
                                          $forma_motivo = $RetTodos["Forma_Categoria"];
                                          if (strlen($forma_motivo) > 1) {
                                              $forma_motivo = substr($forma_motivo, 2);
                                              $forma_motivo = substr($forma_motivo, 0, -1);
                                          }
                                          $jsonTable[$clave]["lista_formas_categorias"][$forma_motivo] = [
                                                                                                          $RetTodos["color"],
                                                                                                          $RetTodos["tipo_categoria"],
                                                                                                          $RetTodos["fecha"]
                                                                                                          ];
                                  }
                                  $count++;
                                  $RetTodos = mysqli_fetch_assoc($tomar_movimientos);
                            } else {
                              break;
                            }
                        }

                        $tagsMotivos .= ($nroMotivosEnFecha >= 6) ? "</div>" : "";
                        if ($nroMotivosEnFecha > 6) {
                          $tdExtenso = true;
                        }
                        $tagsTD .= "</div></td>";
                        $nro_col_body--;

                    }

                    mysqli_data_seek($tomar_movimientos, $count - 1);
                    $count--;
                    
                    if ($tdExtenso) {
                      $tdReemplazar = "~<td~";
                      $tdClassExtenso = "<td class='td--extenso-height-127'";
                      $tagsTD = preg_replace($tdReemplazar, $tdClassExtenso, $tagsTD);
                    }

                    $Table = $Table . $tagsTD . "</tr>";
                    continue;
                }

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
  <input type="range" class="fixed-bottom form-range input--transform-rotate180" 
         step="0.2" value="<?php echo (($nro_column) ? ($nro_column - $nro_col_disponible + 10) : "10") ?>" min="10"
         max="<?php echo (($nro_column) ? $nro_column + 8 : "") ?>"
    id="BarraDeNavHTabla">
  <!--<input type="range" class="fixed-bottom form-range" step="1" value="1" min="1" id="BarraDeNavVTabla">-->

  <div class="modal fade modal--show-overall" id="configModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 2001;">
    <div class="class_modal-dialog modal-dialog" role="document" id="id_modal-dialog">
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
          <button type="button" class="btn btn-primary" onClick="configResultados()"
            data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade modal--show-overall" id="map-modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 2001; overflow: hidden">
    <div class="class_modal-dialog modal-dialog" role="document" id="id_modal-dialog"
      style="min-width: 80%; height: 1000px;">
      <div class="modal-content" style="height: 60%;">
        <div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <button type="button" id="boton-desplegale" class="button-arrow" aria-label="desplegable">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
            </svg>
          </button>
          <button type="button" id="boton-fullscreen" class="button-fullscreen" aria-label="fullscreen">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm5.854 8.803a.5.5 0 1 1-.708-.707L9.243 6H6.475a.5.5 0 1 1 0-1h3.975a.5.5 0 0 1 .5.5v3.975a.5.5 0 1 1-1 0V6.707z"/>
              </svg>
          </button>
          <button type="button" id="boton-plus" class="button-plus" aria-label="plus">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-zoom-in" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11M13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0"/>
                <path d="M10.344 11.742q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1 6.5 6.5 0 0 1-1.398 1.4z"/>
                <path fill-rule="evenodd" d="M6.5 3a.5.5 0 0 1 .5.5V6h2.5a.5.5 0 0 1 0 1H7v2.5a.5.5 0 0 1-1 0V7H3.5a.5.5 0 0 1 0-1H6V3.5a.5.5 0 0 1 .5-.5"/>
              </svg>
          </button>
          <button type="button" id="boton-min" class="button-min" aria-label="min">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-zoom-out" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11M13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0"/>
              <path d="M10.344 11.742q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1 6.5 6.5 0 0 1-1.398 1.4z"/>
              <path fill-rule="evenodd" d="M3 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5"/>
            </svg>
          </button>
          <button type="button" id="boton-decrement" class="button-min" aria-label="decrement">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-rewind-circle" viewBox="0 0 16 16">
              <path d="M7.729 5.055a.5.5 0 0 0-.52.038l-3.5 2.5a.5.5 0 0 0 0 .814l3.5 2.5A.5.5 0 0 0 8 10.5V8.614l3.21 2.293A.5.5 0 0 0 12 10.5v-5a.5.5 0 0 0-.79-.407L8 7.386V5.5a.5.5 0 0 0-.271-.445"/>
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8"/>
            </svg>
          </button>
          <button type="button" id="boton-animation" class="button-min" aria-label="animation">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-square" viewBox="0 0 16 16">
              <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
              <path d="M5.795 12.456A.5.5 0 0 1 5.5 12V4a.5.5 0 0 1 .832-.374l4.5 4a.5.5 0 0 1 0 .748l-4.5 4a.5.5 0 0 1-.537.082"/>
            </svg>
          </button>
          <button type="button" id="boton-paused" class="button-min" aria-label="paused">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pause-circle" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
              <path d="M5 6.25a1.25 1.25 0 1 1 2.5 0v3.5a1.25 1.25 0 1 1-2.5 0zm3.5 0a1.25 1.25 0 1 1 2.5 0v3.5a1.25 1.25 0 1 1-2.5 0z"/>
            </svg>
          </button>
          <button type="button" id="boton-stop" class="button-min" aria-label="stop">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stop-btn" viewBox="0 0 16 16">
              <path d="M6.5 5A1.5 1.5 0 0 0 5 6.5v3A1.5 1.5 0 0 0 6.5 11h3A1.5 1.5 0 0 0 11 9.5v-3A1.5 1.5 0 0 0 9.5 5z"/>
              <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z"/>
            </svg>
          </button>
          <button type="button" id="boton-increment" class="button-min" aria-label="increment">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-fast-forward-circle" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
              <path d="M4.271 5.055a.5.5 0 0 1 .52.038L8 7.386V5.5a.5.5 0 0 1 .79-.407l3.5 2.5a.5.5 0 0 1 0 .814l-3.5 2.5A.5.5 0 0 1 8 10.5V8.614l-3.21 2.293A.5.5 0 0 1 4 10.5v-5a.5.5 0 0 1 .271-.445"/>
            </svg>
          </button>
        </div>
        <div class="modal-body" style="padding-top: 0px">
          <div id="basicMap" class="map"></div>
          <div id="desplegable" style="display: none; pointer-events: none; position: absolute; top: 3px; left: 20px; z-index: 1000">
            <?php
            foreach ($filtros as $value) {
                echo "<span class='etFiltros'>" . $value . "</span> ";
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function () {
      var tabla = document.getElementById("tabla-responsive");
    })();

    columnaIndice = <?php echo (($nro_column) ? ($nro_column - $nro_col_disponible + 10) : "10"); ?>;
    valInputRangePrev = columnaIndice;
    nroColumnaInicial = columnaIndice;

    <?php $_SESSION["meses"] = $mesesHeader; ?>
    var objectJsonTabla = <?php echo json_encode(value: array_values($jsonTable), flags: true); ?>;


    function toggleZoom(porcentaje) {
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
      let filas = objectJsonTabla.forEach((element, index, array) => { envioDeFilasEnBloques(element, index, array); });
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

    if (!map) {
      map = initAnimation(
                          <?php echo ($lat_person ? $lat_person : "null"); ?>,
                          <?php echo ($lon_person ? $lon_person : "null"); ?>,
                          null
                        );
      carga(map, objectJsonTabla);
    };

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