import {RerpoteMovimiento} from "./ReporteMovimiento.js";

let listaOrden = new Map();
let intervalo = null;

$(function (e) {
    $("td[data-hc-persona]").on("click", function (e) {
        let id = $(this).attr("data-hc-persona");
        enviarAHistoriClinicaDePersona(id);
    });

    $("td[data-hc-persona]").on("mouseover", function (e) {
        let mensaje = $(`<div class="position-absolute item" style="z-index: 1100; width: max-content; left: -12px; top: -36px;">
                          <div class="toast dat-toast show" style="width:auto;" 
                               role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-body">
                                <span id="meses-hasta-dato">Persona</span>
                            </div>
                          </div>
                        </div>`);
        $(this).append(mensaje);
    });

    $("td[data-hc-persona]").on("mouseout", function (e) {
        $(".item").remove();
    });

    $("td[data-hc-familia]").on("mouseover", function (e) {
        let mensaje = $(`<div class="position-absolute item" style="z-index: 1100; width: max-content; left: -12px; top: -36px;">
                          <div class="toast dat-toast show" style="width:auto;" 
                               role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-body">
                                <span>Familia</span>
                            </div>
                          </div>
                        </div>`);
        $(this).append(mensaje);
    });

    $("td[data-hc-familia]").on("mouseout", function (e) {
        $(".item").remove();
    });

    $("input[data-order-option]").on("click", function (e) {
        orderOptionCheck($(this).attr("data-order-option"));
    })

    $("a[data-id-movimiento-mod]").on("click", function (e) {
        window.open("view_modmovimientos.php?ID=" + $(this).attr("data-id-movimiento-mod"), "Ventana" + $(this).attr("data-id-movimiento-mod") + ", width=1100, height=500, scrollbars=no, top=150, left=250, resizable=no");
    })

    $("a[data-id-persona-mod]").on("click", function (e) {
        window.open('view_modpersonas.php?ID=' + $(this).attr("data-id-persona-mod"),'Ventana' + $(this).attr("data-id-persona-mod") + ', width=800,height=500,scrollbars=no,top=150,left=250,resizable=no');
    })

    $("td[data-filtro]").on("mouseover", function (e) {
        let mensaje = $(`<div class="position-absolute item" style="z-index: 1100; width: max-content; left: -12px; top: -36px;">
                          <div class="toast dat-toast show" style="width:auto;" 
                               role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-body">
                                <span>Motivo</span>
                            </div>
                          </div>
                        </div>`);
        $(this).append(mensaje);
    });

    $("td[data-filtro]").on("mouseout", function (e) {
        $(".item").remove();
    });

    $("#enviar_imprimir").on("click", enviarImprimirPdf);
    
    $("#btn-orden-modal").on("click", function (e) {
        ordenResultados();
    });

    $("#input-zoom").on("input", function (e) {
        toggleZoom($('#input-zoom').prop("value"));
    });

    $("#zoomIncrementar").on("mousedown", function (e) {
        intervalo = setInterval(function () {
        let value = null;
        $('#input-zoom')[0].stepUp();
        value = $('#input-zoom').prop("value");
        toggleZoom(value);
    }, 37);
    });

    $("#zoomIncrementar").on("mouseup", function (e) {
        clearInterval(intervalo);
    });

    $("#zoomDecrementar").on("mousedown", function (e) {
        intervalo = setInterval(function () {
            let value = null;
            $('#input-zoom')[0].stepDown();
            value = $('#input-zoom').prop("value");
            toggleZoom(value);
        }, 37);
    });

    $("#zoomDecrementar").on("mouseup", function (e) {
        clearInterval(intervalo);
    });

    $("#boton-fullscreen").on("click", function (e) {
        if (!window.fullscreen) {
            $("#excel-modal div[class='modal-content']")[0].requestFullscreen();
            window.fullscreen = true;
        } else {
            window.document.exitFullscreen();
            window.fullscreen = false;
        }
    });

    $("button[class='close']").on("click", function (e) {
        if (window.fullscreen) {
        window.document.exitFullscreen();
        window.fullscreen = false;
        }
    });

})

function enviarAHistoriClinicaDePersona(idPersona) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "/view_vermovlistados.php";
    form.style.display = 'none';
    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = "ID_Persona";
    input.value = idPersona;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = "inicial-movimiento-check";
    input.value = 1;
    input.checked = true;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = "fin-movimiento-check";
    input.value = 1;
    input.checked = true;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'text';
    input.name = "Fecha_Desde";
    input.value = "01/10/2025";
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'text';
    input.name = "Fecha_Hasta";
    input.value = "01/10/2026";
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'text';
    input.name = "ID_Config";
    input.value = "table";
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = "fin-movimiento-check";
    input.value = 1;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'checkbox';
    input.name = "persona-historia-clinica";
    input.value = "true";
    input.checked = true;
    form.appendChild(input);

    document.body.appendChild(form);
    form.submit();
}

export function listConfigResultados() {
    let list = [];
    if (!document.getElementById('chkFecha').checked) list.push('Fecha');
    if (!document.getElementById('chkMotivos').checked) {
        list.push('Motivo 1');
        list.push('Motivo 2');
        list.push('Motivo 3');
    }
    if (!document.getElementById('chkPersona').checked) list.push('Persona');
    if (!document.getElementById('chkDNI').checked) list.push('DNI');
    if (!document.getElementById('chkFechaNac').checked) list.push('Fecha Nac');
    if (!document.getElementById('chkEdad').checked) list.push('Años');
    if (!document.getElementById('chkMeses').checked) list.push('Meses');
    if (!document.getElementById('chkObraSocial').checked) list.push('Obra Social');
    if (!document.getElementById('chkDomicilio').checked) list.push('Domicilio');
    if (!document.getElementById('chkBarrio').checked) list.push('Barrio');
    if (!document.getElementById('chkLocalidad').checked) list.push('Localidad');

    if (!document.getElementById('chkObservaciones').checked) list.push('Observaciones');
    if (!document.getElementById('chkObservacionMedicina').checked) list.push('Otras Observaciones');
    if (!document.getElementById('chkResponsable').checked) list.push('Responsable');
    if (!document.getElementById('chkCentrosSalud').checked) list.push('Centro Salud');
    if (!document.getElementById('chkOtrasInstituciones').checked) list.push('Otra Institucion');

    return list;
}

export function enviarImprimirPdf() {

    let listConfigResult = listConfigResultados();
    let reporte = null;
    if (objectJsonTabla["movimientos_general"] !== undefined) {
        for (let index = 4; index > objectJsonTabla["cant_resp"]; index--) {
        objectJsonTabla["header_movimientos_general"].splice(objectJsonTabla["header_movimientos_general"].indexOf("Responsable" + index), 1);
        }
        reporte = new RerpoteMovimiento(
                                        objectJsonTabla["movimientos_general"],
                                        objectJsonTabla["header_movimientos_general"],
                                        objectJsonTabla["head_movimientos_persona"],
                                        null,
                                        null,
                                        listConfigResult
                                    );
        reporte.setFiltros(window.filtroSeleccionados);
        reporte.enviarRequestListado();
    } else if (objectJsonTabla["movimientos_persona"] !== undefined) {
        reporte = new RerpoteMovimiento(
                                        objectJsonTabla["movimientos_persona"],
                                        objectJsonTabla["header_movimientos_general"],
                                        objectJsonTabla["head_movimientos_persona"],
                                        objectJsonTabla["det_persona"],
                                        null,
                                        listConfigResult
                                    );
        reporte.setFiltros(window.filtroSeleccionados);
        reporte.enviarRequestListado();
    }
}

function characterAscii(elem) {
    let caracter = null;
    switch (elem) {
        case "á":
        caracter = "a";
        break;
        case "é":
        caracter = "e";
        break;
        case "í":
        caracter = "i";
        break;
        case "ó":
        caracter = "o";
        break;
        case "ú":
        caracter = "u";
        break;
        case "ñ":
        caracter = "n";
        break;
        default:
        caracter = '';
        break;
    }
    return caracter;
    }

function orderOption(idOption, orden) {
if (listaOrden.has(idOption)) listaOrden.delete(idOption);
if (orden != 0) listaOrden.set(idOption, orden);

if (orden == 0) {
    $("#boton-asc-" + idOption).hide();
    $("#boton-desc-" + idOption).hide();
    $("#boton-disable-" + idOption).show();
} else if (orden == -1) {
    $("#boton-asc-" + idOption).show();
    $("#boton-desc-" + idOption).hide();
    $("#boton-disable-" + idOption).hide();
} else {
    $("#boton-asc-" + idOption).hide();
    $("#boton-desc-" + idOption).show();
    $("#boton-disable-" + idOption).hide();
}
}

function orderOptionCheck(idOption) {
if (listaOrden.has(idOption)) {
    listaOrden.delete(idOption);
} else {
    listaOrden.set(idOption, -1);
}

if (idOption >= 1 && listaOrden.has(0)) {
    listaOrden.delete(0);
    listaOrden.set(0, 1);
}
}

function lessThan(a, b, indice) {
let ret_valor = false;
let elementA = null;
let elementB = null;

if ((indice >= 7 && indice <= 8) 
    || indice == 5) {
    elementA = parseInt(a.trim());
    elementB = parseInt(b.trim());
    if (isNaN(elementA) && !isNaN(elementB)) {
    ret_valor = true;  
    } else if (!isNaN(elementA) && isNaN(elementB)) {
    ret_valor = false;
    } else if (isNaN(elementA) && isNaN(elementB)) {
    ret_valor = (a < b);
    } else {
    ret_valor = (elementA < elementB);
    }

} else if (indice == 0 || indice == 6) {
    elementA = a.split("-");
    elementB = b.split("-");
    elementB.reverse();
    elementA.reverse();
    elementA = elementA.join("-");
    elementB = elementB.join("-");
    ret_valor = (Date.parse(elementA) < Date.parse(elementB));
} else {
    elementA = a.trim().toLowerCase();
    elementB = b.trim().toLowerCase();
    elementA = elementA.replace(/[áéíóúñ]/, characterAscii);
    elementB = elementB.replace(/[áéíóúñ]/, characterAscii);
    ret_valor = (elementA < elementB);
}
return ret_valor;
}

function moreThan(a, b, indice) {
let ret_valor = false;
let elementA = null;
let elementB = null;

if ((indice >= 7 && indice <= 8) 
    || indice == 5) {
    elementA = parseInt(a.trim());
    elementB = parseInt(b.trim());
    if (isNaN(elementA) && !isNaN(elementB)) {
    ret_valor = false;  
    } else if (!isNaN(elementA) && isNaN(elementB)) {
    ret_valor = true;
    } else if (isNaN(elementA) && isNaN(elementB)) {
    ret_valor = (a > b);
    } else {
    ret_valor = (elementA > elementB);
    }

} else if (indice == 0 || indice == 6) {
    elementA = a.split("-");
    elementB = b.split("-");
    elementB.reverse();
    elementA.reverse();
    elementA = elementA.join("-");
    elementB = elementB.join("-");

    ret_valor = (Date.parse(elementA) > Date.parse(elementB));
} else {
    elementA = a.trim().toLowerCase();
    elementB = b.trim().toLowerCase();
    elementA = elementA.replace(/[áéíóúñ]/, characterAscii);
    elementB = elementB.replace(/[áéíóúñ]/, characterAscii);
    ret_valor = (elementA > elementB);
}
return ret_valor;
}

function orderListOption(elementoA, elementoB, listInd, listVal) {
    let listaFilas = [].concat(listInd);
    let listaValores = [].concat(listVal);
    let result = 0;
    let elem = null;
    if (listInd.length > 0) {
    let listaIndices = (listInd) ? listInd.slice(1) : [];
    let lista = (listVal) ? listVal.slice(1) : [];
    let indice = (listaFilas) ? listaFilas.shift() : null;
    let orden = (listaValores) ? listaValores.shift() : null;
    let nodeInnerA = elementoA.children[indice];
    let nodeInnerB = elementoB.children[indice];
    nodeInnerA = (nodeInnerA) ? nodeInnerA.outerText : null;
    nodeInnerB = (nodeInnerB) ? nodeInnerB.outerText : null;

    if (moreThan(nodeInnerA, nodeInnerA, indice)) {
        result = (-1) * orden;
    } else if (lessThan(nodeInnerA, nodeInnerB, indice)) {
        result = 1 * orden;
    } else {
        result = orderListOption(elementoA, elementoB, listaIndices, lista);
    }
    }
    return result;
}

function ordenResultados() {
let filasTabla = $("#tabla-movimiento-general tbody > tr");
filasTabla = jQuery.unique(filasTabla);
let filas = filasTabla.get();
$("#tabla-movimiento-general tbody").empty();

let listElement = Array.from(listaOrden.keys());
let listValues = Array.from(listaOrden.values());
filas.sort(function (elementoA, elementoB) {
    let listaFilas = [].concat(listElement);
    let listaAsign = [].concat(listValues);
    let result = 0;
    let listaIndices = (listElement) ? listElement.slice(1) : [];
    let listaVal = (listValues) ? listValues.slice(1) : [];
    let indice = (listaFilas) ? listaFilas.shift() : null;
    let orden = (listaAsign) ? listaAsign.shift() : null;
    let nodeInnerA = elementoA.children[indice];
    let nodeInnerB = elementoB.children[indice];
    nodeInnerA = (nodeInnerA) ? nodeInnerA.outerText : null;
    nodeInnerB = (nodeInnerB) ? nodeInnerB.outerText : null;
    if (listaOrden) {
        if (moreThan(nodeInnerA, nodeInnerB, indice)) {
        result = (-1) * orden;
        } else if (lessThan(nodeInnerA, nodeInnerB, indice)) {
        result = 1 * orden;
        } else {
        result = orderListOption(elementoA, elementoB, listaIndices, listaVal);
        }
    }
    return result;
});

$("#tabla-movimiento-general tbody").append(filas);

}