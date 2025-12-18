import { PDFDocument, StandardFonts, rgb } from 'pdf-lib';
import swal from '../node_modules/sweetalert2';

export class RerpoteMovimiento {

    listaDeMovimientos = new Array();
    listaHeaderTotal = new Array();
    listaHeaderPers = new Array();
    listaDeRequest = new Array();
    detPersona = {};
    rowsRequest = {};
    detHeaderPers = {};
    listaDePdf = new Array();
    nroPaginaPdf = 0;
    idRequestField = 0;
    nroPaginaGeneradas = 0;
    countList = 0;
    indexList = 0;
    documentoPdf = PDFDocument.create();
    progress = 0.1;
    timer;

    constructor(
                movimientos = null,
                headersTodos = null,
                headersPers = null,
                detallePersona = null,
                detalleHeaderPers = null,
                listConfigResult = null
            ) {
        this.listaDeMovimientos = movimientos.map(function (velem, index, array) {
            listConfigResult.forEach(function (elemen, index, array) {
                delete velem[elemen];
            });
            return velem;
        });
        this.listaHeaderTotal = (headersTodos) ? headersTodos.filter(c => !listConfigResult.includes(c)) : null;
        this.listaHeaderPers = (headersPers) ? headersPers.filter(d => !listConfigResult.includes(d)) : null;
        this.detPersona = detallePersona;
        this.detHeaderPers = detalleHeaderPers;
    }

    sendRequest() {
        let filas = this.listaDeMovimientos.forEach((element, index, array) => {
                                                        this.envioDeFilasMultiplesEnBloques(
                                                                                            element, 
                                                                                            index,
                                                                                            array
                                                                                           );
        });
    }

    sendRequestOne() {
        let request = new XMLHttpRequest();
        let lista = {};
        let animacion = `<div class='icon-printer'>
                            <img class='icon-printer' src="/images/icons/impresora.gif" alt="an illustration of a gear" />
                        </div>
                        <progress id="bar-progress" max="100" value="0">70%</progress>`;
        let timer = null;
        swal.fire({
            title: "Proceso de impresion",
            html: animacion,
            text: "Los movimientos estan siendo incluidos en la impresion",
            icon: "warning",
            showConfirmButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                $("#liveToast").show();
                $("#liveToast").on("click", this.modalImpresionDeMovimiento);
            }
        });

        request.open("POST", "Controladores/GeneradorPdf.php", true);
        request.setRequestHeader("x-request-id", this.idRequestField);
        request.onreadystatechange = async function(element) {
            if (request.readyState == 4 && request.status == 200) {
                let response = element.currentTarget.response;
                let arrBuffer = this.base64ToArrayBuffer(response);
                this.listaDePdf[0] = arrBuffer;
                this.documentoPdf = await this.mergePdfs();
                let blob = new Blob([this.documentoPdf], {type: "application/pdf"});
                let url1 = window.URL.createObjectURL(blob);
                this.impresionMovimientos(url1);
                window.open(url1);
                clearInterval(this.timer);
            }
        }.bind(this);
        lista = this.listaDeMovimientos.reduce((obj, item, index) => {
            obj[index] = item;
            return obj;
        }, {});
        lista["header_movimientos_general"] = this.listaHeaderTotal;
        lista["head_movimientos_persona"] = this.listaHeaderPers;
        lista["head_det_persona"] = this.detHeaderPers;
        lista["det_persona"] = this.detPersona;
        lista["fecha_desde"] = fechaDesde;
        lista["fecha_hasta"] = fechaHasta;
        lista["fitros"] = filtroSeleccionados;
        lista["cant"] = this.listaDeMovimientos.length;
        request.send(JSON.stringify(lista));
        this.timer = setInterval(this.dialogOnProgress.bind(this), 3100);
    }

    base64ToArrayBuffer(data) {
        var binaryString = window.atob(data);
        var binaryLen = binaryString.length;
        var bytes = new Uint8Array(binaryLen);
        for (var i = 0; i < binaryLen; i++) {
            var ascii = binaryString.charCodeAt(i);
            bytes[i] = ascii;
        }
        return bytes;
    }

    async copiadoDePaginas(pdfBinary){
        const pdf = await PDFDocument.load(pdfBinary);
        const copiedPages = await this.documentoPdf.copyPages(pdf, pdf.getPageIndices());
        copiedPages.forEach((page) => {
            this.documentoPdf.addPage(page);
        });
    }

    async mergePdfs() {
        this.documentoPdf = await PDFDocument.create();
        for (let i = 0; i < this.listaDePdf.length; i++) {
            const pdf = await PDFDocument.load(this.listaDePdf[i]);
            const copiedPages = await this.documentoPdf.copyPages(pdf, pdf.getPageIndices());
            copiedPages.forEach((page) => {
                this.documentoPdf.addPage(page);
            });
        }
        const mergedPdfFile = await this.documentoPdf.save();
        return mergedPdfFile;
    }

    async addPdf(element, index, array) {
        let response = element.currentTarget.response;
        let arrBuffer = this.base64ToArrayBuffer(response);
        let requestIdRequest = this.getResponseHeader("x-request-id");
        this.listaDePdf[requestIdRequest] = arrBuffer;
        let listElements = this.listaDeRequest.splice(requestIdRequest, 1);
        this.nroPaginaPdf--;
        if (this.listaDeRequest.length === 0 || this.nroPaginaPdf == 0) {
            this.nroPaginaGeneradas = 0;
            this.documentoPdf = await this.mergePdfs();
            this.listaDePdf = new Array();
            let blob = new Blob([this.documentoPdf], { type: "application/pdf" });
            let url1 = window.URL.createObjectURL(blob);
            window.open(url1);
        }
    }

    envioDeFilasMultiplesEnBloques(elemento, index, array) {
        let arrayL = array.length - 1;
        let objectJson = elemento;
        this.countList++;
        let height = ((elemento.height > 23) ? 0.7 : 0);
        this.countList += height;
        delete objectJson.height;
        this.rowsRequest[this.indexList] = objectJson;
        this.indexList++;

        if (this.idRequestField == 0) {
            height = ((cantFiltros > 2) ? 2 : 0);
            this.countList += height;
        }

        if ((this.countList >= 19 || index == arrayL) 
            && this.idRequestField >= 1) {
            this.rowsRequest["header_movimientos_general"] = this.listaHeaderTotal;
            this.rowsRequest["head_movimientos_persona"] = this.listaHeaderPers;
            this.rowsRequest["cont_movimientos"] = this.indexList;
            if (index == arrayL) this.rowsRequest["last"] = true;

            let request = new XMLHttpRequest();
            request.open("POST", "Controladores/GeneradorPdf.php", true);
            request.setRequestHeader("x-request-id", this.idRequestField);
            request.onreadystatechange = async function(element) {
                if (request.readyState == 4 && request.status == 200) {
                    let response = element.currentTarget.response;
                    let arrBuffer = this.base64ToArrayBuffer(response);
                    let requestIdRequest = request.getResponseHeader("x-request-id");
                    this.listaDePdf[requestIdRequest] = arrBuffer;
                    this.listaDeRequest.splice(requestIdRequest, 1);
                    this.nroPaginaPdf--;
                    if (this.listaDeRequest.length === 0 || this.nroPaginaPdf == 0) {
                        this.nroPaginaGeneradas = 0;
                        this.documentoPdf = await this.mergePdfs();
                        this.listaDePdf = new Array();
                        let blob = new Blob([this.documentoPdf], {type: "application/pdf"});
                        let url1 = window.URL.createObjectURL(blob);
                        window.open(url1);
                    }
                }
              }.bind(this);
            request.send(JSON.stringify(this.rowsRequest));

           /*
            let request = $.ajax({
                url : "Controladores/GeneradorPdf.php",
                method : "POST",
                data : JSON.stringify(this.rowsRequest),
                headers: {
                    "x-request-id" : this.idRequestField
                },
                success : async function(data) {
                    let response = element.currentTarget.response;
                    let arrBuffer = this.base64ToArrayBuffer(response);
                    let requestIdRequest = this.getResponseHeader("x-request-id");
                    this.listaDePdf[requestIdRequest] = arrBuffer;
                    this.listaDeRequest.splice(requestIdRequest, 1);
                    this.nroPaginaPdf--;
                    if (this.listaDeRequest.length === 0 || this.nroPaginaPdf == 0) {
                        this.nroPaginaGeneradas = 0;
                        documentoPdf = await mergePdfs();
                        this.listaDePdf = new Array();
                        let blob = new Blob([documentoPdf], { type: "application/pdf" });
                        let url1 = window.URL.createObjectURL(blob);
                        window.open(url1);
                    }
                  }.bind(this)
              });
              */
            this.listaDeRequest[this.idRequestField] = request;
            this.nroPaginaPdf++;
            this.rowsRequest = {};
            this.idRequestField++;
            this.countList = 0;
            this.indexList = 0;
        } else if ((this.countList >= 11 || index == arrayL)
                   && this.idRequestField == 0) {
            
            if (index == arrayL) this.rowsRequest["last"] = true;

            this.rowsRequest["header_movimientos_general"] = this.listaHeaderTotal;
            this.rowsRequest["head_movimientos_persona"] = this.listaHeaderPers;
            this.rowsRequest["head_det_persona"] = this.detHeaderPers;
            this.rowsRequest["det_persona"] = this.detPersona;
            this.rowsRequest["fecha_desde"] = fechaDesde;
            this.rowsRequest["fecha_hasta"] = fechaHasta;
            this.rowsRequest["fitros"] = filtroSeleccionados;
            this.rowsRequest["cont_movimientos"] = this.indexList; 

            let request = new XMLHttpRequest();
            request.open("POST", "Controladores/GeneradorPdf.php", true);
            request.setRequestHeader("x-request-id", this.idRequestField);
            request.onreadystatechange = async function(element) {
                if (request.readyState == 4 && request.status == 200) {
                    let response = element.currentTarget.response;
                    let arrBuffer = this.base64ToArrayBuffer(response);
                    let requestIdRequest = request.getResponseHeader("x-request-id");
                    this.listaDePdf[requestIdRequest] = arrBuffer;
                    this.listaDeRequest.splice(requestIdRequest, 1);
                    this.nroPaginaPdf--;
                    if (this.listaDeRequest.length === 0 || this.nroPaginaPdf == 0) {
                        this.nroPaginaGeneradas = 0;
                        this.documentoPdf = await this.mergePdfs();
                        this.listaDePdf = new Array();
                        let blob = new Blob([this.documentoPdf], { type: "application/pdf" });
                        let url1 = window.URL.createObjectURL(blob);
                        window.open(url1);
                    }
                }
              }.bind(this);
            request.send(JSON.stringify(this.rowsRequest));
            
            /*
            let request = $.ajax({
                url : "Controladores/GeneradorPdf.php",
                method : "POST",
                data : JSON.stringify(this.rowsRequest),
                headers: {
                    "x-request-id" : this.idRequestField
                },
                success : async function(data) {
                    let response = element.currentTarget.response;
                    let arrBuffer = this.base64ToArrayBuffer(response);
                    let requestIdRequest = this.getResponseHeader("x-request-id");
                    this.listaDePdf[requestIdRequest] = arrBuffer;
                    this.listaDeRequest.splice(requestIdRequest, 1);
                    this.nroPaginaPdf--;
                    if (this.listaDeRequest.length === 0 || this.nroPaginaPdf == 0) {
                        this.nroPaginaGeneradas = 0;
                        documentoPdf = await mergePdfs();
                        this.listaDePdf = new Array();
                        let blob = new Blob([documentoPdf], { type: "application/pdf" });
                        let url1 = window.URL.createObjectURL(blob);
                        window.open(url1);
                    }
                  }.bind(this)
              });
              */
            this.listaDeRequest[this.idRequestField] = request;
            this.nroPaginaPdf++;
            this.rowsRequest = {};
            this.idRequestField++;
            this.countList = 0;
            this.indexList = 0;
        }
    }

    envioDeFilasEnBloques(elemento, index, array) {
        let objectJson = elemento;
        let fila = index % 10;
        var chkPersona = $('#chk-persona');
        var chkFechaNac = $('#chk-fechaNac');
        var chkDomicilio = $('#chk-domicilio');
        var chkBarrio = $('#chk-barrio');
        var chkManzana = $('#chk-manzana');
        var chkLote = $('#chk-lote');
        var chkSublote = $('#chk-sublote');
        var chkAnios = $('#chk-anios');
        var chkMeses = $('#chk-meses');
    
        if (!chkPersona.is(":checked")) {
            delete objectJson.persona;
        }
    
        if (!chkFechaNac.is(":checked")) {
            delete objectJson.fechanac;
        }
    
        if (!chkDomicilio.is(":checked")) {
            delete objectJson.domicilio;
        }
    
        if (!chkBarrio.is(":checked")) {
            delete objectJson.barrio;
        }
    
        if (!chkManzana.is(":checked")) {
            delete objectJson.manzana;
        }
    
        if (!chkLote.is(":checked")) {
            delete objectJson.lote;
        }
    
        if (!chkSublote.is(":checked")) {
            delete objectJson.sublote;
        }
        rowsRequest[fila] = objectJson;
    
        if (fila == 9) {
            let request = new XMLHttpRequest();
            request.open("POST", "Controladores/GeneradorPdf.php", true);
            request.onreadystatechange = this.addPdf.bind(this);
            request.send(JSON.stringify(this.rowsRequest));
            this.listaDeRequest.push(request);
            this.rowsRequest = {};
        }
    }

    dialogImpresionMovimientos(data = null,status = null, request = null) {
        $("#liveToast").hide();

        let mensaje = "";

        swal.fire({
            title: "<strong>La impresion finalizó</strong>",
            icon: "success",
            html: mensaje,
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText: `<i class="fa fa-thumbs-up"></i> OK`,
            confirmButtonAriaLabel: "Thumbs up, great!",
            cancelButtonAriaLabel: "Thumbs down"
        });
    }

    dialogErrorImpresion(data, status, request) {
        swal.fire({
          title: "Fallo de la Impresion",
          text: "Los movimientos no han sido impresos",
          icon: "error",
          showCancelButton: false
        });
    }

    modalImpresionDeMovimiento() {
        let progreso = $("#progress-toast").text()
        let animacion = `<div>
                            <img class='icon-printer' src="/images/icons/impresora.gif" alt="an illustration of printer" />
                         </div>
                         <progress id="bar-progress-modal" max="100" value="` + progreso + `">70%</progress>`;
        $("#liveToast").hide();
        swal.fire({
          title: "Proceso de carga",
          html: animacion,
          text: "Los registros estan siendo cargados al sistema",
          icon: "warning",
          showConfirmButton: true
        }).then((result) => {
          if (result.isConfirmed) {
            $("#liveToast").show();
          }
        });
    }

    impresionMovimientos(url) {
        $("#liveToast").hide();

        let mensaje = `<a href='` + url + `' target='_blank'>
                         <img class='icon-printer' src="/images/icons/descargar.gif" alt="an illustration of printer">
                       </a>`;

        swal.fire({
            title: "<strong>La impresion finalizó</strong>",
            icon: "success",
            html: mensaje,
            showCloseButton: false,
            focusConfirm: false,
            confirmButtonText: `<i class="fa fa-thumbs-up"></i> OK`,
            confirmButtonAriaLabel: "Thumbs up, great!",
            cancelButtonAriaLabel: "Thumbs down"
        });
    }

    dialogOnProgress() {
        this.progress += 0.05;
        $("#bar-progress").val(this.progress * 100);
        $("#progress-toast").text(parseInt(this.progress * 100));
        $("#bar-progress-modal").val(parseInt(this.progress * 100));
    }

}