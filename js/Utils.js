/*
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
    var ContenidoTabla = document.getElementById("ContenidoTabla");
    ContenidoTabla.setAttribute("class", "col-md-12");

    $("#expandir").attr("style", "padding-left:1px");
    $("#abrir").attr("style", "display:inline;");
    //document.getElementById("abrir").style.display = "inline";
    document.getElementById("cerrar").style.display = "none";
    $("#BarraDeNavHTabla").attr("style", "width: 95%; margin-left: 2%;");
}
*/
let checkInputDesde = false; 
let checkInputHasta = false; 


$(function (e) {
    $("#fecha-desde-inicial").on("click", function (e) {
        let date = new Date();
        if (!checkInputDesde) {
            $("#Fecha_Desde").val("");
            $("#inicial-movimiento-check").prop("value", "1");
            checkInputDesde = true;            
        } else {
            date.setFullYear(date.getFullYear() - 1);
            $("#Fecha_Desde").val(date);
            $('input[name="Fecha_Desde"]').datepicker("setDate", '-1y');
            $("#inicial-movimiento-check").prop("value", null);
            checkInputDesde = false;
        }
    });
   $("#fecha-hasta-inicial").on("click", function (e) {
        let date = new Date();
        if (!checkInputHasta) {
            $("#Fecha_Hasta").val("");
            $("#fin-movimiento-check").prop("value", "1");            
            checkInputHasta = true;
        } else {
             $("#Fecha_Hasta").val(date);
            $('input[name="Fecha_Hasta"]').datepicker("setDate");
            $("#fin-movimiento-check").prop("value", null);            

            checkInputHasta = false;
        }
    });
    $("#bn-new-filtro").on("click", newFiltro);
    $("#bn-filtro-dato").on("click", function (e) {
        newFiltro();
        datosFormulario();
        $("#text-filtro").val("");

    });
    $("select[data-select='1']").on("change", );
    $("#bn-filtro-dato").attr("disabled", true);
});

function mostrar() {

    $("#expandir").css("display", "none");
    $("#ContenidoTabla").removeClass("div--padding-left-menu-active");
    $("#ContenidoTabla").addClass("col-md-10");
    $("#ContenidoTabla").removeClass("col-md-12");
    $("#BarraDeNavHTabla").removeAttr("style");
    $("#ContenidoMenu").css("display", "block");
    $("#tabla-responsive").css("position", "relative");
    $("#cerrar").css("display", "inline-block");
}

function ocultar() {
    $("#expandir").attr("hidden", false);
    $("#ContenidoTabla").addClass("div--padding-left-menu-active");
    $("#ContenidoTabla").removeClass("col-md-10");
    $("#ContenidoTabla").addClass("col-md-12");
    $("#ContenidoMenu").css("display", "none");
    $("#tabla-responsive").css("position", "static");
    $("#expandir").css("display", "block");
    $("#BarraDeNavHTabla").css("margin", "auto");
    $("#cerrar").css("display", "none");
}

function sendToRepL(datos) {
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
                input.value = datos[key];
                form.appendChild(input);
            }
        }
    }

    document.body.appendChild(form);
    form.submit(); // Submit the form to initiate the navigation
}

function sendToNewMovimiento(datos) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "/view_newmovimientos.php";
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

    document.body.appendChild(form);
    form.submit(); 
}

function newFiltro() {
    let val = $("#message-text").prop("value");
    if (!val) val = $("#text-filtro").prop("value");
    let num = $("#list-tab-filtro a").length;
    let char = String.fromCharCode(97 + num);
    let node = $("<a class='list-group-item list-group-item-action " + ((num == 0) ? "active": "") + ` ' 
                  id='list-` + char + `-list' 
                  data-toggle='list' 
                  href='#list-` + char + `' 
                  role='tab' 
                  aria-controls='` + char + `'>` + 
                       val  + 
              `</a>`);
    let z = `  <div class='tab-pane fade ` + ((num == 0) ? "show active": "") + `'
                    id='list-` + char + `'
                    role='tabpanel'
                    aria-labelledby='list-` + char + `-list'>
                    <table class='table' id='tab-` + char + `'>
                        <thead>
                        <tr>
                            <th style='text-align: center; align-content: center;' colspan='3'>` + val + `</th>
                            <!--
                            <th style='max-width: 27px; padding-left: 4%; text-align: center;'>
                                <button class='btn btn-primary' data-tab='` + char + `' id='bn-` + char + `'
                                    style='text-align: center;' onclick="newSelect('` + char + `')">
                                    +
                                </button>
                            </th>
                            -->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    </table>
                </div>`;
    
    $("#list-tab-filtro").append(node);
    $("#nav-tabContent-filtro").append(z);
}

function newSelect(e) {
    let tab = $("#bn-" + e).attr("data-tab");
    let num = $("#tab-" + tab + " tbody tr").length;
    let row = $(`<tr>
                    <td style='text-align: center; align-content: center; width: 36%;'>
                        <select id='e-` + num + `-` + tab +`' class="form-control" data-select='1' onChange="datos('` + tab +`','` + num + `')">
                            <option>Default select</option>
                            <option data-dec='ID_Persona'>Persona</option>
                            <option data-dec='Edad_Desde'>Años - Desde</option>
                            <option data-dec='Edad_Hasta'>Años - Hasta</option>
                            <option data-dec='Meses_Desde'>Meses - Desde</option>
                            <option data-dec='Meses_Hasta'>Meses - Hasta</option>
                            <option data-dec='ID_Barrio'>Barrio</option>
                            <option data-dec='7'>Domicilio/Familia</option>
                            <option data-dec='Manzana'>Manzana</option>
                            <option data-dec='Lote'>Lote</option>
                            <option data-dec='Familia'>Sub-lote</option>
                            <option data-dec='ID_Categoria'>Categoría</option>
                            <option data-dec='ID_Motivo'>Motivo</option>
                            <option data-dec='ID_Centro'>Centro Salud</option>
                            <option data-dec='Nro_Carpeta'>Nro. Carpeta</option>
                            <option data-dec='Nro_Legajo'>Nro. Legajo</option>
                            <option data-dec='ID_OtraInstitucion'>Otras Instituciones</option>
                            <option data-dec='ID_Escuela'>Escuela</option>
                            <option data-dec='ID_Responsable'>Responsable</option>
                            <option data-dec='inpMostrar'>Mostrar Personas</option>
                        </select>
                    </td>
                    <td style='text-align: center; align-content: center; width: 36%;' id>
                        <select class="form-control" disabled>
                            <option>Default select</option>
                        </select>
                    </td>
                    <!---
                    <td style='text-align: center; align-content: center; max-width: 45px;'>
                        <button class='btn btn-success' style='display: inline;'
                                onClick='cargaDatos(" . 
                                                                $row['id_filtro'] . "," . 
                                                                $ret['fecha'] . "
                                                                )'>
                            &#10003;
                        </button>
                        <button class='btn btn-danger' style='display: inline;'
                                onClick='cargaDatos(" . 
                                                                $row['id_filtro'] . "," . 
                                                                $ret['fecha'] . "
                                                                )'>
                            X
                        </button>
                        <button class='btn btn-warning' style='display: inline; color: white;'
                                onClick='cargaDatos(" . 
                                                                $row['id_filtro'] . "," . 
                                                                $ret['fecha'] . "
                                                                )'>
                            &#9998;
                        </button>                        
                    </td>
                    -->
                 </tr>
                `);
    $("#tab-" + tab + " tbody").append(row);

}

function datos(e, num) {
    let d = $('#e-' + num + '-' + e).find(":selected").val();
    let nodo = $('#e-' + num + '-' + e).parent().parent().children().eq(1);
    let b = $('#e-' + num + '-' + e).find(":selected").attr("data-dec");
    let op = null;
    switch (b) {
        case "ID_Persona" :
            nodo.html("");
            nodo.append(`<input type="text" name="Años - Desde" id = "Años - Desde" class="form-control" autocomplete="off">`);
            break;
        case "Edad_Desde" :
            nodo.html("");
            nodo.append(`<input type="text" name="Años - Desde" id = "Años - Desde" class="form-control" autocomplete="off">`);
            break;
        case "Edad_Hasta" :
            nodo.html(`<input type="text" name="Años - Hasta" id = "Años - Hasta" class="form-control" autocomplete="off">`);
            nodo.append();
            break;
        case "Meses_Desde" :
            nodo.html(`<input type="text" name="Meses - Desde" id = "Meses - Desde" class="form-control" autocomplete="off">`);
            nodo.append();
            break;
        case "Meses_Hasta" :
            nodo.html(`<input type="text" name="Meses - Hasta" id = "Meses - Hasta" class="form-control" autocomplete="off">`);
            nodo.append();
            break;
        case "ID_Barrio" :
            op = $("#ID_Barrio").clone();
            op.attr("id", "id-barrio-" +  num + "-" + e);
            op.attr("name", "id-barrio-" +  num + "-" + e);
            op.removeClass();
            op.addClass("form-control");
            nodo.html("");
            nodo.append(op);
            break;
        case "Calle" :
            nodo.html("");
            nodo.append();
            break;
        case "Manzana" :
            nodo.html(`<input type="text" name="Manzana" id = "Manzana" class="form-control" autocomplete="off">`);
            nodo.append();
            break;
        case "Lote" :
            nodo.html(`<input type="text" name="Lote" id = "Lote" class="form-control" autocomplete="off">`);
            nodo.append();
            break;
        case "Familia" :
            nodo.html(`<input type="text" name="Sub-lote" id = "Sub-lote" class="form-control" autocomplete="off">`);
            nodo.append();
            break;
        case "ID_Categoria" :
            nodo.html("");
            nodo.append();
            break;
        case "ID_Motivo" :
            nodo.html("");
            nodo.append();
            break;
        case "ID_Centro" :
            op = $("#ID_Centro").clone();
            op.attr("id", "id-centro-" +  num + "-" + e);
            op.attr("name", "id-centro-" +  num + "-" + e);
            op.removeClass();
            op.addClass("form-control");
            nodo.html("");
            nodo.append(op);
            break;
        case "Nro_Carpeta" :
            nodo.html(`<input type="text" name="nro-carpeta" id = "nro-carpeta" class="form-control" autocomplete="off">`);
            nodo.append();
            break;
        case "Nro_Legajo" :
            nodo.html(`<input type="text" name="nro-legajo" id = "nro-legajo" class="form-control" autocomplete="off">`);
            nodo.append();
            break;
        case "ID_OtraInstitucion" :
            op = $("#ID_OtraInstitucion").clone();
            op.attr("id", "id-ins-" +  num + "-" + e);
            op.attr("name", "id-ins-" +  num + "-" + e);
            nodo.html("");
            nodo.append(op);
            break;
        case "ID_Escuela" :
            op = $("#ID_Escuela").clone();
            op.attr("id", "id-escuela-" +  num + "-" + e);
            op.attr("name", "id-escuela-" +  num + "-" + e);
            nodo.html("");
            nodo.append(op);
            break;
        case "ID_Responsable" :
            op = $("#ID_Responsable").clone();
            op.attr("id", "id-responsable-" +  num + "-" + e);
            op.attr("name", "id-responsable-" +  num + "-" + e);
            nodo.html("");
            nodo.append(op);
            break;
        case "inpMostrar" :
            op = $("#inpMostrar").clone();
            op.attr("id", "id-mov-" +  num + "-" + e);
            op.attr("name", "id-mov-" +  num + "-" + e);
            nodo.html("");
            nodo.append(op);
            break;
    }
}


function addSelect(char, num, d) {

    let row = $(`<tr>
                    <td style='text-align: center; align-content: center; width: 36%;'>
                        <select id='e-` + num + `-` + char +`' class="form-control" data-select='1' onChange="datos('` + char +`','` + num + `')">
                            <option>Default select</option>
                            <option data-dec='ID_Persona'>Persona</option>
                            <option data-dec='Edad_Desde'>Años - Desde</option>
                            <option data-dec='Edad_Hasta'>Años - Hasta</option>
                            <option data-dec='Meses_Desde'>Meses - Desde</option>
                            <option data-dec='Meses_Hasta'>Meses - Hasta</option>
                            <option data-dec='ID_Barrio'>Barrio</option>
                            <option data-dec='Calle'>Domicilio/Familia</option>
                            <option data-dec='Manzana'>Manzana</option>
                            <option data-dec='Lote'>Lote</option>
                            <option data-dec='Familia'>Sub-lote</option>
                            <option data-dec='ID_Categoria'>Categoría</option>
                            <option data-dec='ID_Motivo'>Motivo</option>
                            <option data-dec='ID_Centro'>Centro Salud</option>
                            <option data-dec='Nro_Carpeta'>Nro. Carpeta</option>
                            <option data-dec='Nro_Legajo'>Nro. Legajo</option>
                            <option data-dec='ID_OtraInstitucion'>Otras Instituciones</option>
                            <option data-dec='ID_Escuela'>Escuela</option>
                            <option data-dec='ID_Responsable'>Responsable</option>
                            <option data-dec='inpMostrar'>Mostrar Personas</option>
                        </select>
                    </td>
                    <td style='text-align: center; align-content: center; width: 36%;' id>
                        <select class="form-control" disabled>
                            <option>Default select</option>
                        </select>
                    </td>
                    <!--
                    <td style='text-align: center; align-content: center; max-width: 45px;'>
                        <button class='btn btn-success' style='display: inline;'
                                onClick='cargaDatos(" . 
                                                                $row['id_filtro'] . "," . 
                                                                $ret['fecha'] . "
                                                                )'>
                            &#10003;
                        </button>
                        <button class='btn btn-danger' style='display: inline;'
                                onClick='cargaDatos(" . 
                                                                $row['id_filtro'] . "," . 
                                                                $ret['fecha'] . "
                                                                )'>
                            X
                        </button>
                        <button class='btn btn-warning' style='display: inline; color: white;'
                                onClick='cargaDatos(" . 
                                                                $row['id_filtro'] . "," . 
                                                                $ret['fecha'] . "
                                                                )'>
                            &#9998;
                        </button>                        
                    </td>
                    -->
                 </tr>
                `);
    $("#tab-" + char + " tbody").append(row);
    $
}

function addDatos(e, num, b, d) {
    let nodo1 = $('#e-' + num + '-' + e).parent().parent().children().eq(0).children();
    let nodo2 = $('#e-' + num + '-' + e).parent().parent().children().eq(1);
    let op = null;
    let index = $("option[data-dec='" + b + "']")[0].index;
    switch (b) {
        case "ID_Persona" :
            nodo2.html("");
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            nodo2.append(`<input type="text" name="id-persona" id = "id-persona" class="form-control" autocomplete="off">`);
            nodo2.children().prop("value", d);
            nodo2.children().prop("disabled", true);
            break;
        case "Edad_Desde" :
            nodo2.html("");
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            nodo2.append(`<input type="text" name="Años - Desde" id = "Años - Desde" class="form-control" autocomplete="off">`);
            nodo2.children().prop("value", d);
            nodo2.children().prop("disabled", true);
            break;
        case "Edad_Hasta" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            nodo2.html(`<input type="text" name="Años - Hasta" id = "Años - Hasta" class="form-control" autocomplete="off">`);
            nodo2.children().prop("value", d);
            nodo2.children().prop("disabled", true);
            break;
        case "Meses_Desde" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            nodo2.html(`<input type="text" name="Meses - Desde" id = "Meses - Desde" class="form-control" autocomplete="off">`);
            nodo2.children().prop("value", d);
            nodo2.children().prop("disabled", true);
            break;
        case "Meses_Hasta" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            nodo2.html(`<input type="text" name="Meses - Hasta" id = "Meses - Hasta" class="form-control" autocomplete="off">`);
            nodo2.append();
            nodo2.children().prop("value", index);
            nodo2.children().prop("disabled", true);
            break;
        case "ID_Barrio" :
            nodo1.prop("selectedIndex", 6);
            nodo1.prop("disabled", true);
            op = $("#ID_Barrio").clone();
            op.attr("id", "id-barrio-" +  num + "-" + e);
            op.attr("name", "id-barrio-" +  num + "-" + e);
            op.removeClass();
            op.addClass("form-control");
            op.prop("disabled", true);
            nodo2.html("");
            nodo2.append(op);
            op.prop("selectedIndex", index);
            break;
        case "Calle" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            nodo2.html("");
            nodo2.children().prop("value", d);
            nodo2.children().prop("disabled", true);
            break;
        case "Manzana" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            nodo2.html(`<input type="text" name="Manzana" id = "Manzana" class="form-control" autocomplete="off">`);
            nodo2.children().prop("value", d);
            nodo2.children().prop("disabled", true);
            break;
        case "Lote" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            nodo2.html(`<input type="text" name="Lote" id = "Lote" class="form-control" autocomplete="off">`);
            nodo2.children().prop("value", d);
            nodo2.children().prop("disabled", true);
            break;
        case "Familia" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            nodo2.html(`<input type="text" name="Sub-lote" id = "Sub-lote" class="form-control" autocomplete="off">`);
            nodo2.append();
            nodo2.children().prop("value", d);
            nodo2.children().prop("disabled", true);
            break;
        case "ID_Categoria" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            nodo2.html("");
            nodo2.children().prop("value", d);
            nodo2.children().prop("disabled", true);
            break;
        case "ID_Motivo" :
            nodo2.html("");
            nodo2.children().prop("value", d);
            nodo2.children().prop("disabled", true);
            break;
        case "ID_Centro" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            op = $("#ID_Centro").clone();
            op.attr("id", "id-centro-" +  num + "-" + e);
            op.attr("name", "id-centro-" +  num + "-" + e);
            op.removeClass();
            op.addClass("form-control");
            op.prop("disabled", true);
            nodo2.html("");
            nodo2.append(op);
            op.prop("selectedIndex", d);
            break;
        case "Nro_Carpeta" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            nodo2.html(`<input type="text" name="nro-carpeta" id = "nro-carpeta" class="form-control" autocomplete="off">`);
            nodo2.children().prop("value", d);
            nodo2.children().prop("disabled", true);
            break;
        case "Nro_Legajo" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            nodo2.html(`<input type="text" name="nro-legajo" id = "nro-legajo" class="form-control" autocomplete="off">`);
            nodo2.append();
            nodo2.children().prop("value", d);
            nodo2.children().prop("disabled", true);
            break;
        case "ID_OtraInstitucion" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            op = $("#ID_OtraInstitucion").clone();
            op.attr("id", "id-ins-" +  num + "-" + e);
            op.attr("name", "id-ins-" +  num + "-" + e);
            op.prop("disabled", true);
            nodo2.html("");
            nodo2.append(op);
            op.prop("selectedIndex", d);
            break;
        case "ID_Escuela" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            op = $("#ID_Escuela").clone();
            op.attr("id", "id-escuela-" +  num + "-" + e);
            op.attr("name", "id-escuela-" +  num + "-" + e);
            op.prop("disabled", true);
            nodo2.html("");
            nodo2.append(op);
            op.prop("selectedIndex", d);
            break;
        case "ID_Responsable" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            op = $("#ID_Responsable").clone();
            op.attr("id", "id-responsable-" +  num + "-" + e);
            op.attr("name", "id-responsable-" +  num + "-" + e);
            nodo2.html("");
            nodo2.append(op);
            op.prop("selectedIndex", d);
            op.prop("disabled", true);
            break;
        case "inpMostrar" :
            nodo1.prop("selectedIndex", index);
            nodo1.prop("disabled", true);
            op = $("#inpMostrar").clone();
            op.attr("id", "id-mov-" +  num + "-" + e);
            op.attr("name", "id-mov-" +  num + "-" + e);
            op.prop("disabled", true);
            nodo2.html("");
            nodo2.append(op);
            op.prop("value", d);
            break;
    }
}

function datosFormulario() {
    let num = $("#list-tab-filtro a").length - 1;
    let char = String.fromCharCode(97 + num);
    let index = 1;

    $("form input[data-pre]").each(function (ind, e) {
        if (!e.value || e.value == "0") return;
        addSelect(char, index);
        addDatos(char, index, e.id, e.value);
        index++;
    });

    $("form select[data-pre]").each(function (ind, e) {
        if (!e.value || e.value == "0") return;
        addSelect(char, index);
        addDatos(char, index, e.id, e.selectedIndex);
        index++;
    });

}

