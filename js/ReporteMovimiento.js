import { PDFDocument, StandardFonts, rgb } from 'pdf-lib';

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

    constructor(
                movimientos = null, 
                headersTodos = null,
                headersPers = null,
                detallePersona = null,
                detalleHeaderPers = null
            ) {
        this.listaDeMovimientos = movimientos;
        this.listaHeaderTotal = headersTodos;
        this.listaHeaderPers = headersPers;
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
            height = ((cantFiltros > 2) ? 1 : 0);
            this.countList += height;
        }

        if ((this.countList >= 19 || index == arrayL) 
            && this.idRequestField >= 1) {
            this.rowsRequest["header_movimientos_general"] = this.listaHeaderTotal;
            this.rowsRequest["head_movimientos_persona"] = this.listaHeaderPers;
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
        } else if ((this.countList >= 15 || index == arrayL) 
                   && this.idRequestField == 0) {
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

}