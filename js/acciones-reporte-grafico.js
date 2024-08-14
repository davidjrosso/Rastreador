function configResultados() {
    var chkPersona = $('#chk-persona');
    var chkFechaNac = $('#chk-fechaNac');
    var chkDomicilio = $('#chk-domicilio');
    var chkBarrio = $('#chk-barrio');
    var chkManzana = $('#chk-manzana');
    var chkLote = $('#chk-lote');
    var chkSublote = $('#chk-sublote');
    var chkAnios = $('#chk-anios');
    var chkMeses = $('#chk-meses');

    var thBarrio = $('#Contenido-Titulo-1');
    var thDomicilio = $('#Contenido-Titulo-2');
    var thPersona = $('#Contenido-Titulo-3');
    var thFechaNac = $('#Contenido-Titulo-4');
    var thManzana = $('#Contenido-Titulo-5');
    var thLote = $('#Contenido-Titulo-6');
    var thSublote = $('#Contenido-Titulo-7');
    //var thFechaNac = $('#Contenido-Titulo-8');

    var trBarrio = $('tr #Contenido-1');
    var trDomicilio = $('tr #Contenido-2');
    var trPersona = $('tr #Contenido-3');
    var trFechaNac= $('tr #Contenido-4');
    var trManzana = $('tr #Contenido-5');
    var trLote = $('tr #Contenido-6');
    var trSublote = $('tr #Contenido-7');
    //var trFechaNac= $('tr #Contenido-8');

    var tieneCheck = chkPersona.is(":checked") || chkFechaNac.is(":checked") ||
        chkDomicilio.is(":checked") || chkBarrio.is(":checked") ||
        chkManzana.is(":checked")  || chkLote.is(":checked") ||
        chkSublote.is(":checked");

    if (tieneCheck) {
        if (!chkPersona.is(":checked")) {
            trPersona.hide();
            thPersona.hide();
        } else {
            trPersona.show();
            thPersona.show();
        }

        if (!chkFechaNac.is(":checked")) {
            thFechaNac.hide();
            trFechaNac.hide();
        } else {
            thFechaNac.show();
            trFechaNac.show();
        }

        if (!chkDomicilio.is(":checked")) {
            thDomicilio.hide();
            trDomicilio.hide();
        } else {
            thDomicilio.show();
            trDomicilio.show();
        }

        if (!chkBarrio.is(":checked")) {
            thBarrio.hide();
            trBarrio.hide();
        } else {
            thBarrio.show();
            trBarrio.show();
        }

        if (!chkManzana.is(":checked")) {
            trManzana.hide();
            thManzana.hide();
        } else {
            trManzana.show();
            thManzana.show();
        }

        if (!chkLote.is(":checked")) {
            trLote.hide();
            thLote.hide();
        } else {
            trLote.show();
            thLote.show();
        }

        if (!chkSublote.is(":checked")) {
            trSublote.hide();
            thSublote.hide();
        } else {
            trSublote.show();
            thSublote.show();
        }
    }
    /*
    if (!chkAnio.is(":checked")) {
        trAnio.hide();
        thAnio.hide();
    } else {
        trAnio.show();
        thAnio.show();
    }

    if (!chkMeses.is(":checked")) {
        trMeses.hide();
        thMeses.hide();
    } else {
        trMeses.show();
        thMeses.show();
    }
    */
  }

  function base64ToArrayBuffer(data) {
    var binaryString = window.atob(data);
    var binaryLen = binaryString.length;
    var bytes = new Uint8Array(binaryLen);
    for (var i = 0; i < binaryLen; i++) {
        var ascii = binaryString.charCodeAt(i);
        bytes[i] = ascii;
    }
    return bytes;
  }

  async function mergePdfs() {
    const documentoPdf = await PDFDocument.create();
    const actions = listaDePdf.map(async pdfBuffer => {
        const pdf = await PDFDocument.load(pdfBuffer);
        const copiedPages = await documentoPdf.copyPages(pdf, pdf.getPageIndices());
        copiedPages.forEach((page) => {
            documentoPdf.addPage(page);
        });
    });
    await Promise.all(actions);
    const mergedPdfFile = await documentoPdf.save();
    return mergedPdfFile;
  }

  /* function addPdf(element) {
    if ((this.readyState == 4) && (this.status == 200)) {
        let response = element.currentTarget.response;
        let arrBuffer = base64ToArrayBuffer(response);
        listaDePdf.push(arrBuffer);
        var blob = new Blob([arrBuffer], { type: "application/pdf" });
        var url1 = window.URL.createObjectURL(blob);
        window.open(url1);
    }
  }*/

  async function addPdf(element) {
    if ((this.readyState == 4) && (this.status == 200)) {
        let response = element.currentTarget.response;
        let arrBuffer = base64ToArrayBuffer(response);
        let requestIdRequest = this.getResponseHeader("x-request-id");
        listaDePdf[requestIdRequest] = arrBuffer;
        listaDeRequest.splice(requestIdRequest, 1);
        nroPaginaGeneradas++;
        if (listaDeRequest.length === 0) {
            nroPaginaGeneradas = 0;
            documentoPdf = await mergePdfs();
            listaDePdf = new Array();
            var blob = new Blob([documentoPdf], { type: "application/pdf" });
            var url1 = window.URL.createObjectURL(blob);
            window.open(url1);
        }
    }
  }

  /*function envioDeFilasEnBloques(index, elemento) {
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
    let cells = elemento.cells;
    let rows = {};

    let tdBarrio = elemento.cells[0].innerText;
    let tdDomicilio = cells[1].innerText;
    let tdManzana = cells[2].innerText;
    let tdLote = cells[3].innerText;
    let tdSublote = cells[4].innerText;
    let tdPersona = cells[5].innerText;
    let tFechaNac= cells[6].innerText;
    for (let i = 7; i < (nroColumnasTabla + 2); i++) {
        rows[thTable[i].innerText] = cells[i].innerText;
        
    }

    if (chkPersona.is(":checked")) {
        rows["persona"] = tdPersona;
    }

    if (chkFechaNac.is(":checked")) {
        rows["fechanac"] = tFechaNac;
    }

    if (chkDomicilio.is(":checked")) {
        rows["domicilio"] = tdDomicilio;
    }

    if (chkBarrio.is(":checked")) {
        rows["barrio"] = tdBarrio;
    }

    if (chkManzana.is(":checked")) {
        rows["manzana"] = tdManzana;
    }

    if (chkLote.is(":checked")) {
        rows["lote"] = tdLote;
    }

    if (chkSublote.is(":checked")) {
        rows["sublote"] = tdSublote;
    }
    rowsRequest[fila] = rows;

    if (fila == 9) {
        let request = new XMLHttpRequest();
        request.open("POST", "Controladores/GeneradorPdf.php", true);
        request.onreadystatechange = addPdf;
        request.send(JSON.stringify(rowsRequest));
        listaDeRequest.push(request);
        rowsRequest = {};
    }
  }*/

function envioDeFilasMultiplesEnBloques(elemento, index, array){
    let objectJson = elemento;
    let fila = (index - 14) % 20;
    var chkPersona = $('#chkPersona');
    var chkFechaNac = $('#chkFechaNac');
    var chkDomicilio = $('#chkDomicilio');
    var chkBarrio = $('#chkBarrio');
    var chkManzana = $('#chk-manzana');
    var chkLote = $('#chk-lote');
    var chkSublote = $('#chk-sublote');
    var chkAnios = $('#chkEdad');
    var chkMeses = $('#chkMeses');

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
    if (fila >= 0) {
        rowsRequest[fila] = objectJson;
    } else {
        rowsRequest[index] = objectJson;
    }

    if (fila == 19 && idRequestField >= 1) {
        let request = new XMLHttpRequest();
        request.open("POST", "Controladores/GeneradorPdf.php", true);
        request.setRequestHeader("x-request-id", idRequestField);
        request.onreadystatechange = addPdf;
        request.send(JSON.stringify(rowsRequest));
        listaDeRequest[idRequestField] = request;
        rowsRequest = {};
        idRequestField++;
    } else if ((index == 13 && idRequestField == 0)) {
        rowsRequest["head_det_persona"] = objectJsonTabla["head_det_persona"];
        rowsRequest["det_persona"] = objectJsonTabla["det_persona"];
        rowsRequest["fecha_desde"] = fechaDesde;
        rowsRequest["fecha_hasta"] = fechaHasta;
        let request = new XMLHttpRequest();
        request.open("POST", "Controladores/GeneradorPdf.php", true);
        request.setRequestHeader("x-request-id", idRequestField);
        request.onreadystatechange = addPdf;
        request.send(JSON.stringify(rowsRequest));
        listaDeRequest[idRequestField] = request;
        rowsRequest = {};
        idRequestField++;
    }
}

  function envioDeFilasEnBloques(elemento, index, array) {
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
        request.onreadystatechange = addPdf;
        request.send(JSON.stringify(rowsRequest));
        listaDeRequest.push(request);
        rowsRequest = {};
    }
  }
