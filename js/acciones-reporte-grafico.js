function configResultados() 
{
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