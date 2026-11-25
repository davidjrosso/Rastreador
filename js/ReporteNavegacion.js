$(function (e) {
    $("td[data-id-motivo]").on("click", function (e) {
        sendToCheck($(this).attr("data-id-motivo"));
    });

    $("td[data-send-pers-list]").on("click", function (e) {
        sendToPersonaListado(
                             $(this).attr("data-send-pers-list"),
                             Window.datos   
                            );
    });

    $("button[data-send-to-rep-l]").on("click", function (e) {
        sendToRepL(Window.datosRetorno);
    });

    $("button[data-send-to-list]").on("click", function (e) {
        window.location.href = 'view_listados.php';
    });

    $("#rep_grafico").on("click", function (e) {
        sendToRepGrafico(Window.datos);
    });

    $("#grilla_tabla").on("click", function (e) {
        sendToRepGrillaLista(Window.datos);
    });

    $("#btn-config-modal").on("click", function (e) {
        configResultados();
    });

    configResultados();
});


function sendToRepL(datos) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "/view_vermovlistados.php";
    form.style.display = 'none';

    for (const key in datos) {
        if (Object.prototype.hasOwnProperty.call(datos, key)) {
            if (key == "familia-historia-clinica") continue;
            if (key == "persona-historia-clinica") continue;
            if (datos[key] instanceof Array) {
                datos[key].forEach(function (e) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key + "[]";
                    input.value = e;
                    form.appendChild(input);
                })
            } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = datos[key];
                form.appendChild(input);
            }
        }
    }

    document.body.appendChild(form);
    form.submit();
    }


    function sendToRepGrafico(datos) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "/view_rep_general_new.php";
        form.style.display = 'none';

        for (const key in datos) {
            if (Object.prototype.hasOwnProperty.call(datos, key)) {
                if (datos[key] instanceof Array) {
                datos[key].forEach(function (e) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key + "[]";
                    input.value = e;
                    form.appendChild(input);
                })
                } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = datos[key];
                form.appendChild(input);
                }
            }
        }

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = "width-display";
        input.value = window.screen.availWidth;
        form.appendChild(input);

        
        document.body.appendChild(form);
        form.submit(); 
    }

    function sendToPersonaListado(idPersona, datos) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "/view_vermovlistados.php";
        form.style.display = 'none';

        for (const key in datos) {
            if (Object.prototype.hasOwnProperty.call(datos, key)) {
                if (datos[key] instanceof Array) {
                datos[key].forEach(function (e) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key + "[]";
                    input.value = e;
                    form.appendChild(input);
                });
                } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                if (key == "ID_Persona") {
                    input.value = idPersona;
                } else {
                    input.value = datos[key];
                }
                form.appendChild(input);
                }
            }
        }
        const input = document.createElement('input');
        input.type = 'checkbox';
        input.name = "familia-historia-clinica";
        input.value = "true";
        input.checked = true;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit(); 
    }

    function sendToRepGrillaLista(datos) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "/view_vermovlistados.php";
        form.style.display = 'none';

        for (const key in datos) {
            if (Object.prototype.hasOwnProperty.call(datos, key)) {
                if (datos[key] instanceof Array) {
                datos[key].forEach(function (e) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key + "[]";
                    input.value = e;
                    form.appendChild(input);
                })
                } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                if (key == "ID_Config") {
                    if (datos[key] == "table") {
                    input.value = "grid";
                    } else {
                    input.value = "table";
                    }
                } else {
                    input.value = datos[key];
                }
                form.appendChild(input);
                }
            }
        }

        document.body.appendChild(form);
        form.submit(); 
    }

    function sendToCheck(id) {
    window.location.href = "view_listados.php?id_motivo=" + id;
    }

  function configResultados() {
    let chkFecha = document.getElementById('chkFecha').checked;
    let chkMotivos = document.getElementById('chkMotivos').checked;
    let chkPersona = document.getElementById('chkPersona').checked;
    let chkDNI = document.getElementById('chkDNI').checked;
    let chkFechaNac = document.getElementById('chkFechaNac').checked;
    let chkEdad = document.getElementById('chkEdad').checked;
    let chkMeses = document.getElementById('chkMeses').checked;
    let chkObraSocial = document.getElementById('chkObraSocial').checked;
    let chkDomicilio = document.getElementById('chkDomicilio').checked;
    let chkBarrio = document.getElementById('chkBarrio').checked;
    let chkLocalidad= document.getElementById('chkLocalidad').checked;

    let chkObservaciones= document.getElementById('chkObservaciones').checked;
    let chkObservacionMedicina= document.getElementById('chkObservacionMedicina').checked;
    let chkResponsable= document.getElementById('chkResponsable').checked;
    let chkCentrosSalud= document.getElementById('chkCentrosSalud').checked;
    let chkOtrasInstituciones = document.getElementById('chkOtrasInstituciones').checked;

    let trFecha = document.getElementsByClassName('trFecha');
    let trMotivos= document.getElementsByClassName('trMotivos');
    let trPersona = document.getElementsByClassName('trPersona');
    let trDNI= document.getElementsByClassName('trDNI');
    let trFechaNac= document.getElementsByClassName('trFechaNac');
    let trEdad = document.getElementsByClassName('trEdad');
    let trMeses = document.getElementsByClassName('trMeses');
    let trObraSocial = document.getElementsByClassName('trObraSocial');
    let trDomicilio = document.getElementsByClassName('trDomicilio');
    let trBarrio = document.getElementsByClassName('trBarrio');
    let trLocalidad = document.getElementsByClassName('trLocalidad');

    let trObservaciones= document.getElementsByClassName('trObservaciones');
    let trObservacionMedicina= document.getElementsByClassName('trObservacionesMedicina');
    let trResponsable= document.getElementsByClassName('trResponsable');
    let trCentrosSalud= document.getElementsByClassName('trCentrosSalud');
    let trOtrasInstituciones = document.getElementsByClassName('trOtrasInstituciones');

    if(!chkFecha){
      for (let i = 0; i < trFecha.length; i++) {        
        trFecha[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trFecha.length; i++) {        
        trFecha[i].removeAttribute('hidden');        
      }
    }

    if(!chkMotivos){
      for (let i = 0; i < trMotivos.length; i++) {        
        trMotivos[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trMotivos.length; i++) {        
        trMotivos[i].removeAttribute('hidden');        
      }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if(!chkPersona){
      for (let i = 0; i < trPersona.length; i++) {        
        trPersona[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trPersona.length; i++) {        
        trPersona[i].removeAttribute('hidden');        
      }
    }

    if(!chkDNI){
      for (let i = 0; i < trDNI.length; i++) {        
        trDNI[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trDNI.length; i++) {        
        trDNI[i].removeAttribute('hidden');        
      }
    }

    if(!chkFechaNac){
      for (let i = 0; i < trFechaNac.length; i++) {        
        trFechaNac[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trFechaNac.length; i++) {        
        trFechaNac[i].removeAttribute('hidden');        
      }
    }

    if(!chkEdad){
      for (let i = 0; i < trEdad.length; i++) {        
        trEdad[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trEdad.length; i++) {        
        trEdad[i].removeAttribute('hidden');        
      }
    }

    if(!chkMeses){
      for (let i = 0; i < trMeses.length; i++) {        
        trMeses[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trMeses.length; i++) {        
        trMeses[i].removeAttribute('hidden');        
      }
    }

    if(!chkObraSocial){
      for (let i = 0; i < trObraSocial.length; i++) {        
        trObraSocial[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trObraSocial.length; i++) {        
        trObraSocial[i].removeAttribute('hidden');        
      }
    }

    if(!chkDomicilio){
      for (let i = 0; i < trDomicilio.length; i++) {        
        trDomicilio[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trDomicilio.length; i++) {        
        trDomicilio[i].removeAttribute('hidden');        
      }
    }

    if(!chkBarrio){
      for (let i = 0; i < trBarrio.length; i++) {        
        trBarrio[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trBarrio.length; i++) {        
        trBarrio[i].removeAttribute('hidden');        
      }
    }

    if(!chkLocalidad){
      for (let i = 0; i < trLocalidad.length; i++) {        
        trLocalidad[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trLocalidad.length; i++) {        
        trLocalidad[i].removeAttribute('hidden');        
      }
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if(!chkObservaciones){
      for (let i = 0; i < trObservaciones.length; i++) {        
        trObservaciones[i].setAttribute('hidden', true);        
      }
    } else {  
      for (let i = 0; i < trObservaciones.length; i++) {        
        trObservaciones[i].removeAttribute('hidden');        
      }
    }

    if(!chkObservacionMedicina){
      for (let i = 0; i < trObservacionMedicina.length; i++) {        
        trObservacionMedicina[i].setAttribute('hidden', true);        
      }
    } else {  
      for (let i = 0; i < trObservacionMedicina.length; i++) {        
        trObservacionMedicina[i].removeAttribute('hidden');        
      }
    }

    if(!chkResponsable){
      for (let i = 0; i < trResponsable.length; i++) {        
        trResponsable[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trResponsable.length; i++) {        
        trResponsable[i].removeAttribute('hidden');        
      }
    }

    if(!chkCentrosSalud){
      for (let i = 0; i < trCentrosSalud.length; i++) {        
        trCentrosSalud[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trCentrosSalud.length; i++) {        
        trCentrosSalud[i].removeAttribute('hidden');        
      }
    }

    if(!chkOtrasInstituciones){
      for (let i = 0; i < trOtrasInstituciones.length; i++) {        
        trOtrasInstituciones[i].setAttribute('hidden', true);        
      }
    }else{
      for (let i = 0; i < trOtrasInstituciones.length; i++) {        
        trOtrasInstituciones[i].removeAttribute('hidden');        
      }
    }
  }