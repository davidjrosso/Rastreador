import swal from 'sweetalert2';

export class Preferencia {

    #listOpciones = [];    

    newFiltro() {
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

    addItemPreferencia(item, valor, texto) {
        let preferencia = {};
        preferencia[item] = valor;
        preferencia["text"] = null;
        this.#listOpciones.push(preferencia);
    }

    newSelect(e) {
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
                                <option data-dec='manzana'>Manzana</option>
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

    datos(e, num) {
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
            case "manzana" :
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


    addSelect(char, num) {
        let row = $(`<tr style='text-align: center;'>
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
                                <option data-dec='manzana'>Manzana</option>
                                <option data-dec='lote'>Lote</option>
                                <option data-dec='familia'>Sub-lote</option>
                                <option data-dec='ID_Categoria'>Categoría</option>
                                <option data-dec='ID_Categoria2'>Categoría 2</option>
                                <option data-dec='ID_Categoria3'>Categoría 3</option>
                                <option data-dec='ID_Categoria4'>Categoría 4</option>
                                <option data-dec='ID_Categoria5'>Categoría 5</option>
                                <option data-dec='ID_Motivo'>Motivo</option>
                                <option data-dec='ID_Motivo2'>Motivo 2</option>
                                <option data-dec='ID_Motivo3'>Motivo 3</option>
                                <option data-dec='ID_Motivo4'>Motivo 4</option>
                                <option data-dec='ID_Motivo5'>Motivo 5</option>
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

    addDatos(e, num, id, value, valueOp) {
        let nodo1 = $('#e-' + num + '-' + e).parent().parent().children().eq(0).children();
        let nodo2 = $('#e-' + num + '-' + e).parent().parent().children().eq(1);
        let op = null;
        let index = $("option[data-dec='" + id + "']")[0].index;
        let exis = false;
        let texto = "";
        let vc = id;
        if (vc.search(/(ID_Categoria[0-9]|ID_Motivo[0-9])/) >= 0) vc = vc.substring(0, vc.length - 1); 
        switch (vc) {
            case "ID_Persona" :
                nodo2.html("");
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                nodo2.append(`<input type="text" name="id-persona" id = "id-persona" class="form-control" autocomplete="off">`);
                texto = $("#Persona").text();
                nodo2.children().prop("value", texto);
                nodo2.children().prop("disabled", true);
                this.#listOpciones.push(
                                          {"ID_Persona" : value,
                                           "text" : texto}
                                        );
                break;
            case "Edad_Desde" :
                nodo2.html("");
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                nodo2.append(`<input type="text" name="Años - Desde" id = "Años - Desde" class="form-control" autocomplete="off">`);
                nodo2.children().prop("value", value);
                nodo2.children().prop("disabled", true);
                this.#listOpciones.push(
                                          {"Edad_Desde" : value,
                                           "text" : texto}
                                        );
                break;
            case "Edad_Hasta" :
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                nodo2.html(`<input type="text" name="Años - Hasta" id = "Años - Hasta" class="form-control" autocomplete="off">`);
                nodo2.children().prop("value", value);
                nodo2.children().prop("disabled", true);
                this.#listOpciones.push(
                                          {"Edad_Hasta" : value,
                                           "text" : texto}
                                        );
                break;
            case "Meses_Desde" :
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                nodo2.html(`<input type="text" name="Meses - Desde" id = "Meses - Desde" class="form-control" autocomplete="off">`);
                nodo2.children().prop("value", value);
                nodo2.children().prop("disabled", true);
                this.#listOpciones.push(
                                          {"Meses_Desde" : value,
                                           "text" : texto}
                                        );
                break;
            case "Meses_Hasta" :
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                nodo2.html(`<input type="text" name="Meses - Hasta" id = "Meses - Hasta" class="form-control" autocomplete="off">`);
                nodo2.children().prop("value", value);
                nodo2.children().prop("disabled", true);
                this.#listOpciones.push(
                                        {"Meses_Hasta" : value,
                                         "text" : texto}
                                        );
                break;
            case "ID_Barrio" :
                nodo1.prop("selectedIndex", 6);
                nodo1.prop("disabled", true);
                exis = this.#listOpciones.find(
                                               (e) => e.hasOwnProperty("ID_Barrio")
                                              );
                op = $("#ID_Barrio").clone();
                op.attr("id", "id-barrio-" +  num + "-" + e);
                op.attr("name", "id-barrio-" +  num + "-" + e);
                op.removeClass();
                op.addClass("form-control");
                op.prop("disabled", true);
                if (!exis) this.#listOpciones.push({
                                                      "ID_Barrio" : [],
                                                      "text" : []
                                                    }); 
                let barrio = this.#listOpciones.find(
                                        (e) => e.hasOwnProperty("ID_Barrio")
                );

                nodo2.html("");
                nodo2.append(op);
                op.prop("selectedIndex", value);
                barrio["ID_Barrio"].push(valueOp);
                barrio["text"].push(texto);
                break;
            case "Calle" :
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                nodo2.html("");
                nodo2.children().prop("value", value);
                nodo2.children().prop("disabled", true);
                this.#listOpciones.push(
                                          {"Calle" : value,
                                           "text" : texto}
                                        );
                break;
            case "manzana" :
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                nodo2.html(`<input type="text" name="Manzana" id = "Manzana" class="form-control" autocomplete="off">`);
                nodo2.children().prop("value", value);
                nodo2.children().prop("disabled", true);
                this.#listOpciones.push({
                                           "Manzana" : value,
                                           "text" : texto
                                         });
                break;
            case "lote" :
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                nodo2.html(`<input type="text" name="Lote" id = "Lote" class="form-control" autocomplete="off">`);
                nodo2.children().prop("value", value);
                nodo2.children().prop("disabled", true);
                this.#listOpciones.push({
                                           "Lote" : value,
                                           "text" : texto
                                         });
                break;
            case "familia" :
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                nodo2.html(`<input type="text" name="Sub-lote" id = "Sub-lote" class="form-control" autocomplete="off">`);
                nodo2.children().prop("value", value);
                nodo2.children().prop("disabled", true);
                this.#listOpciones.push({
                                           "Familia" : value,
                                           "text" : texto
                                         });
                break;
            case "ID_Categoria" :
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                exis = this.#listOpciones.find(
                                               (e) => e.hasOwnProperty("ID_Categoria")
                                              );
                nodo2.html(`<input type="text" name="categoria" id = "categoria" class="form-control" autocomplete="off">`);
                nodo2.children().prop("disabled", true);
                if (!exis) this.#listOpciones.push({
                                                      "ID_Categoria" : [],
                                                      "text" : []
                                                    }); 
                let categoria = this.#listOpciones.find(
                                        (e) => e.hasOwnProperty("ID_Categoria")
                );
                categoria["ID_Categoria"].push(value);
                if (!exis) texto = $("#Categoria").text();
                if (exis) texto = $("#Categoria" + categoria["ID_Categoria"].length).text();
                nodo2.children().prop("value", texto);
                categoria["text"].push(texto);
                break;
            case "ID_Motivo" :
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                exis = this.#listOpciones.find(
                                                   (e) => e.hasOwnProperty("ID_Motivo")
                                                  );
                nodo2.html("");
                nodo2.children().prop("value", value);
                nodo2.children().prop("disabled", true);
                nodo2.html(`<input type="text" name="motivo" id = "motivo" class="form-control" autocomplete="off">`);
                nodo2.children().prop("disabled", true);
                if (!exis) this.#listOpciones.push(
                                          {"ID_Motivo" : [],
                                           "text" : []}
                                             ); 
                let motivo = this.#listOpciones.find(
                                        (e) => e.hasOwnProperty("ID_Motivo")
                );
                motivo["ID_Motivo"].push(value);
                if (!exis) texto = $("#Motivo").text();
                if (exis) texto = $("#Motivo" + motivo["ID_Motivo"].length).text();
                nodo2.children().prop("value", texto);
                motivo["text"].push(texto);
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
                op.prop("selectedIndex", value);
                this.#listOpciones.push(
                                          {"ID_Centro" : value,
                                           "text" : texto}
                                        );
                break;
            case "Nro_Carpeta" :
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                nodo2.html(`<input type="text" name="nro-carpeta" id = "nro-carpeta" class="form-control" autocomplete="off">`);
                nodo2.children().prop("value", value);
                nodo2.children().prop("disabled", true);
                this.#listOpciones.push(
                                          {"Nro_Carpeta" : value,
                                           "text" : texto}
                                        );
                break;
            case "Nro_Legajo" :
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                nodo2.html(`<input type="text" name="nro-legajo" id = "nro-legajo" class="form-control" autocomplete="off">`);
                nodo2.append();
                nodo2.children().prop("value", value);
                nodo2.children().prop("disabled", true);
                this.#listOpciones.push(
                                          {"Nro_Legajo" : value,
                                           "text" : texto}
                                        );
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
                op.prop("selectedIndex", value);
                this.#listOpciones.push(
                                          {"ID_OtraInstitucion" : value,
                                           "text" : texto}
                                        );
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
                op.prop("selectedIndex", value);
                this.#listOpciones.push(
                                          {"ID_Escuela" : value,
                                           "text" : texto}
                                        );
                break;
            case "ID_Responsable" :
                nodo1.prop("selectedIndex", index);
                nodo1.prop("disabled", true);
                exis = this.#listOpciones.find(
                                                   (e) => e.hasOwnProperty("ID_Responsable")
                                                  );
                op = $("#ID_Responsable").clone();
                op.attr("id", "id-responsable-" +  num + "-" + e);
                op.attr("name", "id-responsable-" +  num + "-" + e);
                nodo2.html("");
                nodo2.append(op);
                op.prop("selectedIndex", value);
                op.prop("disabled", true);
                if (!exis) this.#listOpciones.push(
                                          {"ID_Responsable" : [],
                                           "text" : []}
                                             ); 
                let responsable = this.#listOpciones.find(
                                        (e) => e.hasOwnProperty("ID_Responsable")
                );
                responsable["ID_Responsable"].push(valueOp);
                responsable["text"].push(texto);
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
                op.prop("value", value);
                this.#listOpciones.push(
                                          {"inpMostrar" : value,
                                           "text" : texto}
                                        );
                break;
        }
    }

    datosFormulario() {
        let num = $("#list-tab-filtro a").length - 1;
        let char = String.fromCharCode(97 + num);
        let index = 1;
        let texto = "";
        $("form input[data-pre]").each(function (ind, e) {
            if (!e.value || e.value == "0") return;
            this.addSelect(char, index);
            this.addDatos(char, index, e.id, e.value);
            index++;
        }.bind(this));

        $("form select[data-pre]").each(function (ind, e) {
            let valueOp = null;
            if (!e.value || e.value == "0") return;
            valueOp = e.children[e.selectedIndex].value;
            this.addSelect(char, index);
            this.addDatos(char, index, e.id, e.selectedIndex, valueOp);
            index++;
        }.bind(this));

        this.addItemPreferencia( "titulo", 
                                $("#list-" + char + "-list").text().trim(),
                                null);
    }

    sendRequestPreferencia() {
      let addres = "/preferencia/solicitud_nueva_preferencia";
      $.ajax({
        url : addres,
        method: "post",
        dataType: "json",
        contentType: "application/json",
        data: JSON.stringify(this.#listOpciones),
        success : function (data, status, requestHttp) {
            swal.fire({
                title: "",
                html: "Se envió al administrar la preferencia para su autorización.",
                icon: "success",
                customClass: {
                    htmlContainer: "text-dialog"
                },
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: `OK`,
                cancelButtonText: `Cancel`
            });
        }.bind(this),
        error: function (data, status, requestHttp) {
             swal.fire({
                title: "",
                html: "hubo un error.",
                icon: "warning",
                customClass: {
                    htmlContainer: "text-dialog"
                },
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: `OK`,
                cancelButtonText: `Cancel`
            });
        }
      });
    }
}

let preferencia = new Preferencia();

$(function (e) {
    $("#bn-new-filtro").on("click", preferencia.newFiltro());
    $("#bn-filtro-dato").on("click", function (e) {
        preferencia.newFiltro();
        preferencia.datosFormulario();
        $("#text-filtro").val("");
    });

    $("#bn-filtro-dato").on("click", function (e) {
        $("#save-data").toggle();
        $("#send-admin").toggle();
    });

    $("#cancel-data").on("click", function (e) {
        $(this).toggle();
    });
    $("#send-admin").on("click", function (e) {
        preferencia.sendRequestPreferencia();
    });
});
