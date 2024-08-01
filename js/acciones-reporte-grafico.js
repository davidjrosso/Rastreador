function configResultados() 
{
    var chkPersona = $('#chkPersona');
    var chkFechaNac = $('#chkFechaNac');
    var chkDomicilio = $('#chkDomicilio');
    var chkBarrio = $('#chkBarrio');

    var thBarrio = $('#Contenido-Titulo-1');
    var thDomicilio = $('#Contenido-Titulo-2');
    var thPersona = $('#Contenido-Titulo-3');
    var thFechaNac = $('#Contenido-Titulo-4');

    var trBarrio = $('#Contenido-1');
    var trDomicilio = $('#Contenido-2');
    var trPersona = $('#Contenido-3');
    var trFechaNac= $('#Contenido-4');

    console.log(chkPersona.is(":checked"));
    console.log(chkFechaNac.is(":checked"));
    console.log(chkDomicilio.is(":checked"));
    console.log(chkBarrio.is(":checked"));

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
        //trBarrio.hide();
        thBarrio.hide();
    } else {
        //trBarrio.show();
        thBarrio.show();
    }
  }