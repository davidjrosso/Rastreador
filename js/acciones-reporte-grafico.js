function configResultados() {
    let chkPersona = $('#chk-persona');
    let chkFechaNac = $('#chk-fechaNac');
    let chkDomicilio = $('#chk-domicilio');
    let chkBarrio = $('#chk-barrio');
    let chkManzana = $('#chk-manzana');
    let chkLote = $('#chk-lote');
    let chkSublote = $('#chk-sublote');
    let chkEdad = $('#chk-anios');
    let chkMeses = $('#chk-meses');

    let thBarrio = $('#Contenido-Titulo-1');
    let thDomicilio = $('#Contenido-Titulo-2');
    let thPersona = $('#Contenido-Titulo-3');
    let thFechaNac = $('#Contenido-Titulo-4');
    let thManzana = $('#Contenido-Titulo-5');
    let thLote = $('#Contenido-Titulo-6');
    let thSublote = $('#Contenido-Titulo-7');
    let thEdad = $('#Contenido-Titulo-8');
    let thMeses = $('#Contenido-Titulo-9');

    let trBarrio = $('tr #Contenido-1');
    let trDomicilio = $('tr #Contenido-2');
    let trPersona = $('tr #Contenido-3');
    let trFechaNac= $('tr #Contenido-4');
    let trManzana = $('tr #Contenido-5');
    let trLote = $('tr #Contenido-6');
    let trSublote = $('tr #Contenido-7');
    let trEdad = $('tr #Contenido-8');
    let trMeses = $('tr #Contenido-9');

    var tieneCheck = chkPersona.is(":checked") || chkFechaNac.is(":checked") ||
        chkDomicilio.is(":checked") || chkBarrio.is(":checked") ||
        chkManzana.is(":checked")  || chkLote.is(":checked") ||
        chkSublote.is(":checked") || chkEdad.is(":checked") || chkMeses.is(":checked");

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

        if (!chkEdad.is(":checked")) {
            thEdad.hide();
            trEdad.hide();
        } else {
            thEdad.show();
            trEdad.show();
        }
    
        if (!chkMeses.is(":checked")) {
            trMeses.hide();
            thMeses.hide();
        } else {
            trMeses.show();
            thMeses.show();
        }
    }

  }

function configColumnasTabla() {
    let chkPersona = $('#chkPersona');
    let chkFechaNac = $('#chkFechaNac');
    let chkDomicilio = $('#chkDomicilio');
    let chkBarrio = $('#chkBarrio');
    let chkManzana = $('#chk-manzana');
    let chkLote = $('#chk-lote');
    let chkSublote = $('#chk-sublote');
    let chkMotivo = $('#chkMotivos');
    let chkDni = $('#chkDNI');
    let chkAnios = $('#chkEdad');
    let chkMeses = $('#chkMeses');
    let chkObraSocial = $('#chkObraSocial');
    let chkLocalidad = $('#chkLocalidad');
    let chkObservaciones = $('#chkObservaciones');
    let chkResponsable = $('#chkResponsable');
    let chkCentroSalud = $('#chkCentrosSalud');
    let chkOtraInstitucion = $('#chkOtrasInstituciones');
    let chkFecha = $('#chkFecha');

    let indiceHead = 0;  

    if (!chkPersona.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Persona");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Persona");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Persona");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Persona");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Persona");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Persona");
            }
        }
    }

    if (!chkFechaNac.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Fecha Nac");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Fecha Nac");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Fecha Nac");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Fecha Nac");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Fecha Nac");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Fecha Nac");
            }
        }
    }

    if (!chkDomicilio.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Domicilio");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Domicilio");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Domicilio");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Domicilio");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Domicilio");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Domicilio");
            }
        }
    }

    if (!chkMotivo.is(":checked")) {
        let indiceHead1 = 0;
        let indiceHead2 = 0;
        let indiceHead3 = 0;
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead1 = objectJsonTabla["header_movimientos_general"].indexOf("Motivo 1");
        } else {
            indiceHead1 = objectJsonTabla["head_movimientos_persona"].indexOf("Motivo 1");
        }

        if (indiceHead1 >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead1, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead1, 1);
            }
        }

        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead2 = objectJsonTabla["header_movimientos_general"].indexOf("Motivo 2");
        } else {
            indiceHead2 = objectJsonTabla["head_movimientos_persona"].indexOf("Motivo 2");
        }

        if (indiceHead2 >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead2, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead2, 1);
            }
        }

        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead3 = objectJsonTabla["header_movimientos_general"].indexOf("Motivo 3");
        } else {
            indiceHead3 = objectJsonTabla["head_movimientos_persona"].indexOf("Motivo 3");
        }

        if (indiceHead3 >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead3, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead3, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Motivo 1");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Motivo 1");
                objectJsonTabla["header_movimientos_general"].push("Motivo 2");
                objectJsonTabla["header_movimientos_general"].push("Motivo 3");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Motivo 1");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Motivo 1");
                objectJsonTabla["head_movimientos_persona"].push("Motivo 2");
                objectJsonTabla["head_movimientos_persona"].push("Motivo 3");
            }
        }
    }

    if (!chkDni.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("DNI");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("DNI");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("DNI");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("DNI");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("DNI");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("DNI");
            }
        }
    }
    if (!chkFecha.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Fecha");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Fecha");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Fecha");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Fecha");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Fecha");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Fecha");
            }
        }
    }
    if (!chkAnios.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Años");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Años");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Años");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Años");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Años");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Años");
            }
        }
    }
    if (!chkMeses.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Meses");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Meses");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Meses");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Meses");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Meses");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Meses");
            }
        }
    }
    if (!chkObraSocial.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Obra Social");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Obra Social");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Obra Social");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Obra Social");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Obra Social");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Obra Social");
            }
        }
    }
    if (!chkLocalidad.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Localidad");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Localidad");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Localidad");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Localidad");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Localidad");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Localidad");
            }
        }
    }
    if (!chkObservaciones.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Observaciones");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Observaciones");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Observaciones");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Observaciones");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Observaciones");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Observaciones");
            }
        }
    }
    if (!chkResponsable.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Responsable");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Responsable");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Responsable");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Responsable");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Responsable");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Responsable");
            }
        }
    }
    if (!chkCentroSalud.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Centro Salud");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Centro Salud");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Centro Salud");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Centro Salud");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Centro Salud");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Centro Salud");
            }
        }
    }
    if (!chkOtraInstitucion.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Otra Institucion");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Otra Institucion");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Otra Institucion");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Otra Institucion");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Otra Institucion");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Otra Institucion");
            }
        }
    }

    if (!chkBarrio.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Barrio");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Barrio");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Barrio");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Barrio");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Barrio");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Barrio");
            }
        }
    }

    if (!chkManzana.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Mza");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Mza");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Mza");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Mza");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Mza");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Mza");
            }
        }
    }

    if (!chkLote.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Lote");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Lote");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("Lote");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("Lote");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("Lote");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("Lote");
            }
        }
    }

    if (!chkSublote.is(":checked")) {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("SubLote");
        } else {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("SubLote");
        }
        if (indiceHead >= 0) {
            if (objectJsonTabla["header_movimientos_general"]) {
                objectJsonTabla["header_movimientos_general"].splice(indiceHead, 1);
            }
            if (objectJsonTabla["head_movimientos_persona"]) {
                objectJsonTabla["head_movimientos_persona"].splice(indiceHead, 1);
            }
        }
    } else {
        if (objectJsonTabla["header_movimientos_general"]) {
            indiceHead = objectJsonTabla["header_movimientos_general"].indexOf("SubLote");
            if (indiceHead < 0) {
                objectJsonTabla["header_movimientos_general"].push("SubLote");
            }
        }
        if (objectJsonTabla["head_movimientos_persona"]) {
            indiceHead = objectJsonTabla["head_movimientos_persona"].indexOf("SubLote");
            if (indiceHead < 0) {
                objectJsonTabla["head_movimientos_persona"].push("SubLote");
            }
        }
    }
}

function ocultarPopup(evt) {
if (this.popup != null) {
    if (!this.popup.hidden) {
    this.popup.hide();
    }
}
OpenLayers.Event.stop(evt);
}

function mostrarPoup(evt) {
if (this.popup == null) {
    this.popup = this.createPopup(this.closeBox);
    map.addPopup(this.popup);
    this.popup.show();
} else {
    this.popup.toggle();
}
OpenLayers.Event.stop(evt);
}

function onClickOcultarPopup(element){
element.parentNode.parentNode.parentNode.style.display = 'none'
}
