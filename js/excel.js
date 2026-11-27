import jspreadsheet from "../node_modules/jspreadsheet-ce";
import ExcelJS from "../node_modules/exceljs";
import Chart from 'chart.js/auto';
import Jsuit from 'jsuites';
import $ from "jquery";

export class Excel {
    #zoom = null;
    #charts = new Map();
    #spreadsheet = null;
    #chart = null;
    #cellSelection = [];
    
    constructor() {
        this.#zoom = 100;
    }

    init() {
        let list = [];
        let elems = [];
        let tableCount = $("table").length;
        let th = null;
        let length = $("#excel_rev").children().length;
        let columnsadd = [{
                            type: 'text',
                            width:200,
                        },
                        {
                            type: 'calendar',
                            width:200,
                        },
                        {
                            type: 'checkbox',
                            width:200,
                        }];
        if (length == 0) {
            if (tableCount == 1) {
                th = $("table thead th:not([hidden='true'])");
            } else {
                th = $("table:nth-child(1) tbody tr td:nth-child(1)");
            }

            th.each(function (index) {
                let typeData = "text";
                let text = $(this).text().trim();

                if (!text) return true;
                if (text == "Años") return true;
                if (text == "Meses") return true;
                if (text == "Fecha") typeData = "calendar";
                if (text == "Fecha Nac.") typeData = "calendar";

                if (text != "Edad") {
                    if (typeData == "calendar") {
                        list.push({
                                type: typeData,
                                options: { format:'DD/MM/YYYY' },
                                title: text,
                                width:'300px'
                        });
                    } else {
                        list.push({
                                type: typeData,
                                title: text,
                                width:'300px'
                        });
                    }

                } else {
                    list.push({
                            type: typeData,
                            title: "Años",
                            width:'300px'
                    });
                    list.push({
                            type: typeData,
                            title: "Meses",
                            width:'300px'
                    });
                }
            });

            if (tableCount == 1) {
                $("tbody tr").each(function (index) {
                    let rowlist = [];
                    let qry = ":not([hidden]):not(:nth-child(2)):not(:nth-child(3)):not(:nth-child(5)):not(:nth-child(7)):not(:nth-child(9))";
                    $(this).children(qry).each(function (e) {
                        if (!e.hidden) {
                            rowlist.push(
                                $(this).text().trim()
                            );            
                        }
                    });
                    elems.push(rowlist);
                });
            } else {
                $("tbody").each(function (index) {
                    let rowlist = [];
                    $(this).children().each(function (e) {
                        $(this).children(":nth-child(2)").each(function (e) {
                            if (!e.hidden) {
                                rowlist.push(
                                    $(this).text().trim()
                                );            
                            }
                        });
                    });
                    elems.push(rowlist);
                });
            }

            let dictionary = {
                'Insert a new column before': 'Insertar una nueva columna antes',
                'Insert a new column after': 'Insertar una nueva columna despues',
                'Insert a new row before': 'Insertar una nueva fila antes',
                'Insert a new row after': 'Insertar una nueva fila despues',
                'Delete selected columns': 'Eliminar columnas seleccionadas',
                'Delete selected rows': 'Eliminar filas seleccionadas',
                'Rename this column': 'Renombrar esta columna',
                'Add comments': 'Agregar comentario',
                'Copy': 'Copiar',
                'Paste': 'Pegar',
                'Order ascending': 'Orden ascendente',
                'Order descending': 'Orden descendente',
                'Edit comments': 'Editar comentarios',
                'Jan': 'Enero',
                'Feb': 'Febrero',
                'Mar': 'Marzo',
                'Apr': 'Abril',
                'May': 'Mayo',
                'Jun': 'Junio',
                'Jul': 'Julio',
                'Aug': 'Agosto',
                'Sep': 'Septiembre',
                'Oct': 'Octubre',
                'Nov': 'Noviembre',
                'Dec': 'Diciembre',
                'January': 'Enero',
                'February': 'Febrero',
                'March': 'Marzo',
                'April': 'Abril',
                'May': 'Mayo',
                'June': 'Junio',
                'July': 'Julio',
                'August': 'Agosto',
                'September': 'Septiembre',
                'October': 'Octubre',
                'November': 'Noviembre',
                'December': 'Diciembre',
                'Sunday': 'Domingo',
                'Monday': 'Lunes',
                'Tuesday': 'Martes',
                'Wednesday': 'Mircoles',
                'Thursday': 'Jueves',
                'Friday': 'Virenes',
                'Saturday': 'Sabado',
                'Done': 'Hecho',
                'Reset': 'Apagar',
                'Update': 'Atualizar',
                'Search': 'Buscar'
            }

            jspreadsheet.setDictionary(dictionary);

            this.#spreadsheet = jspreadsheet(document.getElementById('excel_rev'), {
                worksheets: [{
                        data: elems,
                        columns: list,
                        filters: true,
                        allowComments:true,
                        search: true,
                        tableOverflow: true,
                        worksheetName: "Excel",
                        allowDeleteWorksheet: true,
                        allowRenameWorksheet: true,
                        allowMoveWorksheet: true,
                        columnDrag: true
                    },
                    {
                        minDimensions: [14, 14],
                        filters: true,
                        allowComments:true,
                        search: true,
                        tableOverflow: true,
                        worksheetName: "Totales y Graficos",
                        allowDeleteWorksheet: true,
                        allowRenameWorksheet: true,
                        allowMoveWorksheet: true,
                        columnDrag: true
                    }],
                    onselection: this.selectionActive.bind(this),
                    search: true,
                    toolbar:true,
                    tabs: true,
                    tableOverflow: true,
                    allowDeleteWorksheet: true,
                    allowRenameWorksheet: true,
                    allowMoveWorksheet: true,
                    onbeforecreateworksheet: function(config, index) {
                        return {
                            minDimensions: [5, 5],
                            worksheetName: 'excel  ' + index
                        }
                    },
                    toolbar: function (toolbar) {
                        toolbar.items.push({
                            tooltip: 'zoom + (mas)',
                            type: 'icon',
                            content: 'control_point',
                            onclick: function(e, x, y, n, items, section) {
                                if (this.#zoom < 150) this.#zoom += 10;
                                //o.table.style.zoom = zoom + "%";
                                this.#spreadsheet[0].table.style.zoom = this.#zoom + "%";
                            }.bind(this)
                        });

                        toolbar.items.push({
                            tooltip: 'zoom - (menos)',
                            type: 'icon',
                            content: 'remove_circle_outline',
                            onclick: function(e, x, y, n, items, section) {
                                if (this.#zoom > 10) this.#zoom -= 10;
                                this.#spreadsheet[0].table.style.zoom = this.#zoom + "%";
                            }.bind(this)
                        });

                        
                        /*if (this.existChart(s[0].x, s[0].y)) {
                        }*/
                        toolbar.items.push({
                            tooltip: 'descarga de grafico',
                            type: 'icon',
                            content: 'arrow_circle_down',
                            active: false,
                            onclick: function(e, x, y, n, items, section) {
                                let s = this.#spreadsheet[0].getSelected(false);
                                let chart = this.#charts.get(s[0].x + "-" + s[0].y);
                                let charUrl = chart.toBase64Image('image/jpeg', 10);
                                let blob = new Blob(
                                                    [chart.toBase64Image('image/jpeg', 1)], 
                                                    {type: "image/jpeg"}
                                );
                                let url1 = window.URL.createObjectURL(blob);
                                //window.open(url1);
                                let link = document.createElement('a');
                                document.body.appendChild(link); //required in FF, optional for Chrome
                                link.href = charUrl;
                                link.download = "file.jpeg";
                                link.click();
                                window.URL.revokeObjectURL(data);
                                link.remove();
                            }.bind(this)
                        });

                        toolbar.items.push({
                            type: "divisor"
                        });

                        return toolbar;
                    }.bind(this),
                    contextMenu: function(o, x, y, e, items, section) {
                            let itemsArr = [];
                            if (section == 'header') {
                                itemsArr.push({
                                    title: 'Execute one action',
                                    onclick: function() {
                                        alert('test')
                                    }.bind(this)
                                });

                                itemsArr.push({ type: 'line' });
                            }

                            if (section == 'tabs') {
                                itemsArr.push({
                                    title: 'Eliminar Excel',
                                    onclick: function() {
                                        this.#spreadsheet[0].deleteWorksheet(x);
                                    }.bind(this)
                                });

                                itemsArr.push({
                                    title: 'Agregar Excel',
                                    onclick: function() {
                                        let options = {
                                            minDimensions: [14, 14],
                                            filters: true,
                                            allowComments:true,
                                            search: true,
                                            allowDeleteWorksheet: true,
                                            allowRenameWorksheet: true,
                                            allowMoveWorksheet: true
                                        };
                                        this.#spreadsheet[0].createWorksheet(options, x);
                                    }.bind(this)
                                });

                                itemsArr.push({
                                    title: 'Renombrar Excel',
                                    onclick: function(event) {
                                        this.insertInputTab(e.target);
                                    }.bind(this)
                                });

                                itemsArr.push({ type: 'line' });
                            }

                            if (section == 'cell') {
                                let data = new Map();
                                let s = o.getSelected(false);
                                s.forEach(function (val, ind, array) {
                                    let text = (val.element.innerText) ? val.element.innerText : "";
                                    if (data.has(val.x)) {
                                        data.get(val.x).push(text);
                                    } else {
                                        data.set(val.x, []);
                                    }
                                });

                                itemsArr.push({
                                    title: 'Insertar grafico Personalizado',
                                    onclick: function() {
                                        this.#charts.set(
                                            x + "-" + y,
                                            {
                                              chart: this.addChartLine(e.target, data),
                                              width: this.#spreadsheet[0].getWidth(),
                                              height: this.#spreadsheet[0].getHeight()
                                            }
                                        );
                                    }.bind(this)
                                });

                                if (this.existChart(x, y)) {
                                    itemsArr.push({
                                        title: 'Zoom + (mas)   grafico',
                                        onclick: function() {
                                            let chart = this.#charts.get(x + "-" + y);
                                            chart.width += 50;
                                            chart.height += 50;
                                            this.#spreadsheet[0].setWidth(x, chart.width);
                                            this.#spreadsheet[0].setHeight(y, chart.height);
                                            chart.resize(chart.width, chart.height);
                                            chart.render();
                                        }.bind(this)
                                    });
    
                                    itemsArr.push({
                                        title: 'Zoom - (menos)   grafico',
                                        onclick: function() {
                                            let chart = this.#charts.get(x + "-" + y);
                                            if (chart.width > 50) chart.width -= 50;
                                            if (chart.height > 50) chart.height -= 50;
                                            this.#spreadsheet[0].setWidth(x, chart.width);
                                            this.#spreadsheet[0].setHeight(y, chart.height);
                                            chart.resize(chart.width, chart.height);
                                            chart.render();
                                        }.bind(this)
                                    });

                                    itemsArr.push({
                                        title: 'Descargar grafico',
                                        onclick: function() {
                                            let chart = this.#charts.get(x + "-" + y);
                                            let charUrl = chart.toBase64Image('image/jpeg', 10);
                                            let blob = new Blob(
                                                                [chart.toBase64Image('image/jpeg', 1)], 
                                                                {type: "image/jpeg"}
                                            );
                                            let url1 = window.URL.createObjectURL(blob);
                                            //window.open(url1);
                                            let link = document.createElement('a');
                                            document.body.appendChild(link); //required in FF, optional for Chrome
                                            link.href = charUrl;
                                            link.download = "file.jpeg";
                                            link.click();
                                            window.URL.revokeObjectURL(data);
                                            link.remove();

                                        }.bind(this)
                                    });
                                }

                                itemsArr.push({
                                    title: 'Insertar grafico Lineal',
                                    onclick: function() {
                                        this.#charts.set(
                                            x + "-" + y,
                                            this.addChartLine(e.target, data)
                                        );
                                    }.bind(this)
                                });

                                itemsArr.push({
                                    title: 'Insertar grafico Intervalo',
                                    onclick: function() {
                                        this.#charts.set(
                                            x + "-" + y,
                                            this.addChartIntervalo(e.target, data)
                                        );
                                    }.bind(this)
                                });

                                itemsArr.push({
                                    title: 'Insertar grafico Radar',
                                    onclick: function() {
                                        this.#charts.set(
                                            x + "-" + y,
                                            this.addChartRadar(e.target, data)
                                        );
                                    }.bind(this)
                                });

                                itemsArr.push({
                                    title: 'Insertar grafico P Area',
                                    onclick: function() {
                                        this.#charts.set(
                                            x + "-" + y,
                                            this.addChartP(e.target, data)
                                        );
                                    }.bind(this)
                                });

                                itemsArr.push({
                                    title: 'Insertar grafico Scatter',
                                    onclick: function() {
                                        this.#charts.set(
                                            x + "-" + y,
                                            this.addChartC(e.target, data)
                                        );
                                    }.bind(this)
                                });

                                itemsArr.push({
                                    title: 'Insertar grafico Torta',
                                    onclick: function() {
                                        this.#charts.set(
                                            x + "-" + y,
                                            this.addChartTorta(e.target, data)
                                        );
                                    }.bind(this)
                                });

                                itemsArr.push({
                                    title: 'Insertar grafico Dona',
                                    onclick: function() {
                                        this.#charts.set(
                                            x + "-" + y,
                                            this.addChartDona(e.target, data)
                                        );
                                        }.bind(this)
                                });

                                itemsArr.push({
                                    title: 'Insertar grafico Bubble',
                                    onclick: function() {
                                        this.#charts.set(
                                            x + "-" + y,
                                            this.addChartBubble(e.target, data)
                                        );
                                    }.bind(this)
                                });

                                if (this.existChart(x, y)) {
                                    itemsArr.push({
                                        title: 'Editar',
                                        onclick: function() {
                                            let chart = this.#charts.get(x + "-" + y);
                                            let datos = chart.config._config.data;
                                            if (this.#chart) this.#chart.destroy();
                                            $("#modal-crs")[0].modal.open();
                                            this.#chart = new Chart(document.getElementById('charts'), {
                                                    type: chart.config._config.type,
                                                    data: datos,
                                                    options: chart.config._config.options,
                                                    plugins: chart.config._config.plugins
                                            });
                                            this.#chart.resize(300, 300);
                                            this.#spreadsheet[0].resetSelection(true)
                                        }.bind(this)
                                    });

                                    itemsArr.push({
                                        title: 'Eliminar Grafico',
                                        onclick: function() {
                                            let chart = this.#charts.get(x + "-" + y);
                                            let cell = this.#spreadsheet[0].getCellFromCoords(x, y);
                                            cell.removeChild(cell.firstChild);
                                            chart.destroy()
                                        }.bind(this)
                                    })
                                }
                                itemsArr.push({ type: 'line' });

                                itemsArr.push({
                                    title: 'Zoom + (mas)',
                                    onclick: function() {
                                        if (this.#zoom < 150) this.#zoom += 10;
                                        o.table.style.zoom = this.#zoom + "%";
                                    }.bind(this)
                                });

                                itemsArr.push({
                                    title: 'Zoom - (menos)',
                                    onclick: function() {
                                        if (this.#zoom > 10) this.#zoom -= 10;
                                        o.table.style.zoom = this.#zoom + "%";
                                    }.bind(this)
                                });

                                itemsArr.push({ type: 'line' });                            
                            }

                            let list = [];
                            list = itemsArr.concat(items.slice(0, -2));
                            return list;
                }.bind(this),
                columnDrag: true
            });
            let text2 = "FX <input type='text' style='width: 90%; border-color: rgb(197 197 197); border-style: solid; border-width: 1.8px;' data-x='1' data-y='2' id='bar-element'>";
            //$(".jss_filter label").append(text);
            $(".jss_filter").prepend("<div style='flex-grow: 1; flex-basis: 60%;'> <label style='width: 100%; margin-bottom: 0rem;'>" + text2 + "</label> </div>");
            $("#bar-element").on("click", this.setFocusActive.bind(this, this.#spreadsheet));
            $("#bar-element").on("blur", this.setFocus.bind(this, this.#spreadsheet));
            $("#bar-element").on("input", this.selectionChange.bind(this, this.#spreadsheet));
            $(".jss_container").append($(`<div id='modal-crs' title='Editor de Grafico'>
                                                <div style='display:flex'>
                                                <div id='chart'>
                                                    <canvas id='charts'>
                                                    </canvas>
                                                </div>
                                                <div class='col' id='form'>
                                                    <form id='root'>
                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group row'>
                                                                    <label class='col-4 col-form-label LblForm'>Titulo</label>
                                                                    <input class='col-8 form-control' style='' type='text' id='titulo' name='titulo' value='grafico' data-validation='titulo'>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group row'>
                                                                    <label class='col-4 col-form-label LblForm'>Subtitulo</label>
                                                                    <input class='col-8 form-control' type='text' id='subtitulo' name='subtitulo' value='grafico' data-validation='subtitulo'>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group row'>
                                                                    <label class='col-4 col-form-label LblForm'>Etiqueta de Eje</label>
                                                                    <input class='col-8 form-control' id='etiqueta' type='text' name='etiqueta' value='Etiqueta'>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group row'>
                                                                    <label class='col-4 col-form-label LblForm'>Eje</label>
                                                                    <select class='col-8 form-control' id='eje' name='eje'>
                                                                    <option value="0">X</option>
                                                                    <option value="1">Y</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group row'>
                                                                    <label class='col-4 col-form-label LblForm'>Ancho de imagen</label>
                                                                    <input class='col-8 form-control' type='number' id='width' value='200' name='width' data-validation='width'>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group row'>
                                                                    <label class='col-4 col-form-label LblForm'>Alto de imagen</label>
                                                                    <input class='col-8 form-control' type='number' id='height' value='200' name='height' data-validation='height'>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group row'>
                                                                    <label class='col-4 col-form-label LblForm'>Color</label>
                                                                    <input class='col-8 form-control' id='color' value="#009688" data-validation='color'/>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group row'>
                                                                    <label class='col-4 col-form-label LblForm'>Datos</label>
                                                                    <select class='col-8 form-control' id='datos' name='datos'>
                                                                    <option value="-1">Seleccionar Dato</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group row'>
                                                                    <label class='col-4 col-form-label LblForm'>Color Dato</label>
                                                                    <input class='col-8 form-control' id='color-datos' value="#009688" data-validation='color-datos'/>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        </form>
                                                </div>
                                            </div>
                                        </div>`)
                                        );

            let modal = jSuites.modal(document.getElementById('modal-crs'), {
                closed: true,
                width: 500,
                validations: {
                    color: function(value) {
                    }.bind(this),
                    titulo: function(value) {
                        this.#chart.options.plugins.title.text = value;
                        this.#chart.update()
                    }.bind(this)
                },
                height: 300
            });
            jSuites.color(document.getElementById('color'), {
                onchange: function(e, color) {
                            this.#chart.config._config.options.plugins.customCanvasBackgroundColor.color = color;
                            this.#chart.update()
                          }.bind(this),
                palette: [
                    ['#001969', '#233178', '#394a87', '#4d6396', '#607ea4', '#7599b3' ],
                    ['#00429d', '#2b57a7', '#426cb0', '#5681b9', '#6997c2', '#7daeca' ],
                    ['#3659b8', '#486cbf', '#597fc5', '#6893cb', '#78a6d1', '#89bad6' ],
                    ['#003790', '#315278', '#48687a', '#5e7d81', '#76938c', '#8fa89a' ],
                ]
            });
            jSuites.color(document.getElementById('color-datos'), {
                onchange: function(e, color) {
                            this.#chart.config._config.options.plugins.customCanvasBackgroundColor.color = color;
                            this.#chart.update()
                          }.bind(this),
                palette: [
                    ['#001969', '#233178', '#394a87', '#4d6396', '#607ea4', '#7599b3' ],
                    ['#00429d', '#2b57a7', '#426cb0', '#5681b9', '#6997c2', '#7daeca' ],
                    ['#3659b8', '#486cbf', '#597fc5', '#6893cb', '#78a6d1', '#89bad6' ],
                    ['#003790', '#315278', '#48687a', '#5e7d81', '#76938c', '#8fa89a' ],
                ]
            });
            $("#titulo").on("input", function(e) {
                            this.#chart.config._config.options.plugins.title.text = $("#titulo").prop("value");
                            this.#chart.update()
                        }.bind(this));
            $("#subtitulo").on("input", function(e) {
                            this.#chart.config._config.options.plugins.subtitle.text = $("#subtitulo").prop("value");
                            this.#chart.update()
            }.bind(this));
            $("#etiqueta").on("input", function(e) {
                            let option = $("#eje")[0].children[$("#eje").prop("value")].firstChild.nodeValue.toLowerCase();
                            this.#chart.config._config.options.plugins.scales[option].title = $("#etiqueta").prop("value");
                            this.#chart.update()
            }.bind(this));
            $("#color").on("input", function(e) {
                            this.#chart.config._config.options.plugins.customCanvasBackgroundColor.color = $("#color").prop("value");
                            this.#chart.render()
            }.bind(this));
            $("#width").on("input", function(e) {
                            this.#chart.resize($("#width").prop("value"), this.#chart.height);
                            this.#chart.width = $("#width").prop("value");
                            this.#chart.render()
                        }.bind(this));
            $("#height").on("input", function(e) {
                            this.#chart.resize(this.#chart.width, $("#height").prop("value"));
                            this.#chart.height = $("#height").prop("value");
                            this.#chart.render();
                        }.bind(this));
        }
    }

    getMotivos(objectJsonTabla, listConfig) {
        const list = $("td[data-id-motivo]").map(function (ind, element) {
            return element.getAttribute("data-id-motivo");
        });

        let url = 'motivos/lista_motivos_detalle';
        let datos = JSON.stringify(Array.from(new Set(list)));
        let request = $.ajax({
            type:"POST",
            url : url,
            async: true,
            contentType: 'application/json',
            dataType: 'json',
            data: datos,
            success : function (data, status, requestHttp) {
                if (requestHttp.responseJSON.motivos) {
                    let excelFlujo = this.excel_download(
                        objectJsonTabla,
                        listConfig,
                        requestHttp.responseJSON.motivos
                    );
                    excelFlujo.then(function (result) {
                        let url = null;
                        let blob = new Blob([result], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        url = URL.createObjectURL(blob);
                        let w = window.open(url);
                    });
                }
            }.bind(this)});

    }

    async excel_download(
                         objectJsonTabla,
                         listConfig,
                         codigosMotivos
                        ) {

        const workBook = new ExcelJS.Workbook();
        let nombreSheetDatos = 'rastreador';
        let worksheet = workBook.addWorksheet(nombreSheetDatos, 
                                                {properties:{
                                                   tabColor: {
                                                      argb: '00bfff'}
                                                 }});
        let tableCount = $("table").length;
        let values = [];
        let value = null;
        let count = objectJsonTabla.movimientos_general.length;
        let th = [];
        let cant_r = 0;
        let parser = new DOMParser();
        let listaDeMovimientos = objectJsonTabla.movimientos_general.map(function (velem, index, array) {
            cant_r = velem["cant_resp"];
            for (let l = 2; l <= 4; l++) {
                if (l <= cant_r) velem["Responsable"] += "-" + velem["Responsable" + l];
                delete velem["Responsable" + l];
            }
            listConfig.forEach(function (elemen, index, array) {
                delete velem[elemen];
                if (elemen == "Responsable") {
                    delete velem["Responsable2"];
                    delete velem["Responsable3"];
                    delete velem["Responsable4"];
                }
            });
            const ObsGrl = parser.parseFromString(velem["Observaciones"], 'text/html');
            const textObsGral = ObsGrl.body.textContent;
            const ObsMed = parser.parseFromString(velem["Otras Observaciones"], 'text/html');
            const textObsMed = ObsMed.body.textContent;
            velem["Observaciones"] = (textObsGral === "null") ? "" : textObsGral;
            velem["Otras Observaciones"] = (textObsMed === "null") ? "" : textObsMed;
            delete velem["cant_resp"];
            delete velem["height"];
            return velem;
        });

        for(let row = 1; row < count + 2; row++) {
            if (row == 1) {
                worksheet.getRow(row).border = {
                                                top: {style:'thick', color: {argb:'000000'}},
                                                left: {style:'thick', color: {argb:'000000'}},
                                                bottom: {style:'thick', color: {argb:'000000'}},
                                                right: {style:'thick', color: {argb:'000000'}}
                                            };
                worksheet.getRow(row).alignment = { vertical: 'middle', horizontal: 'center' };
                worksheet.getRow(row).height = 25;

                worksheet.getRow(row).fill = {
                                            type: 'pattern',
                                            pattern:'solid',
                                            fgColor:{argb:'bbc7d1'},
                                            };
                continue;
            }

            value = Object.values(listaDeMovimientos[row - 2]).slice(1);
            value.push(listaDeMovimientos[row - 2]["Responsable"]);
            worksheet.getRow(row).values  = value;
            worksheet.getRow(row).font = {bgColor:{argb:'FFC000'}};
            worksheet.getRow(row).alignment = { vertical: 'middle', horizontal: 'center' };
            worksheet.getRow(row).style  = {bgColor:{argb:'FFC000'}};
            worksheet.getRow(row).height = 20;
        }

        if (tableCount == 1) {
            values = objectJsonTabla.header_movimientos_general.filter(function (e) {
                return !listConfig.includes(e);
            });
        } else {
            th = $("table:nth-child(1) tbody tr td:nth-child(1)");
            th.each(function (e, val) {
                if (!listConfig.includes(val.innerText)) values.push(val.innerText);
            });
        }

        values = values.map(function (e) {
            let val = {header: e, key: e, width: 30,  filterButton: true};
            return val;
        });

        worksheet.columns = values;

        worksheet = workBook.addWorksheet('CIE-10',
                                          {properties:{
                                                   tabColor: {
                                                      argb: 'FFC0000'}
                                          }});

        let criterios = [
            {codigo: 'A00 - B99', regex: '[aAbB][0-9][0-9]\\.[0-9X]'},
            {codigo: 'C00 - D48', regex: '[cCdD]([0-4][0-9]|4[0-8])\\.[0-9X]'},
            {codigo: 'D50 - D89', regex: '[dD][5-7][0-9]\\.[0-9X]'},
            {codigo: 'E00 - E89', regex: '[eE][0-8][0-9]\\.[0-9X]'},
            {codigo: 'F00 - F99', regex: '[fF][0-9][0-9]\\.[0-9X]'},
            {codigo: 'G00 - G99', regex: '[gG][0-9][0-9]\\.[0-9X]'},
            {codigo: 'H00 - H59', regex: '[hH][0-5][0-9]\\.[0-9X]'},
            {codigo: 'H60 - H95', regex: '[hH]([6-8][0-9]|9[0-5])\\.[0-9X]'},
            {codigo: 'I00 - I99', regex: '[iI][0-9][0-9]\\.[0-9X]'},
            {codigo: 'J00 - J99', regex: '[jJ][0-9][0-9]\\.[0-9X]'},
            {codigo: 'K00 - K93', regex: '[kK]([0-8][0-9]|9[0-3])\\.[0-9X]'},
            {codigo: 'L00 - L99', regex: '[lL][0-9][0-9]\\.[0-9X]'},
            {codigo: 'M00 - M99', regex: '[mM][0-9][0-9]\\.[0-9X]'},
            {codigo: 'N00 - N99', regex: '[nN][0-9][0-9]\\.[0-9X]'},
            {codigo: 'O00 - O99', regex: '[oO][0-9][0-9]\\.[0-9X]'},
            {codigo: 'P00 - P96', regex: '[pP]([0-8][0-9]|9[0-6])\\.[0-9X]'},
            {codigo: 'Q00 - Q99', regex: '[qQ][0-9][0-9]\\.[0-9X]'},
            {codigo: 'R00 - R99', regex: '[rR][0-9][0-9]\\.[0-9X]'},
            {codigo: 'S00 - T98', regex: '[sStT]([0-8][0-9]|9[0-8]\\.[0-9X]'},
            {codigo: 'V01 - Y98', regex: '[vVyY][0-9][0-9]\\.[0-9X]'},
            {codigo: 'Z00 - Z99', regex: '[zZ][0-9][0-9]\\.[0-9X]'},
            {codigo: 'U00 - U99', regex: '[uU][0-9][0-9]\\.[0-9X]'}
        ];

        let filasTabla = criterios.map((item) => {
            let formulaRegex = "IFERROR(COUNTIF(" + nombreSheetDatos + "!D2:D50000, \"" + item.regex + "\"), 0)";
            let subElement = "IFERROR(OFFSET(INDIRECT(ADDRESS(ROW(), COLUMN())),  0, 1)/C24, 0)";
            let elemento = [
                    item.codigo,
                    {formula: subElement},
                    {formula: formulaRegex}
            ];
            return elemento;
        });

        worksheet.addTable({
            name: 'TablaResumenCIE10',
            ref: 'A1',
            headerRow: true,
            totalsRow: true,
            style: {
            theme: 'TableStyleMedium2',
            showRowStripes: true,
            },
            columns: [
            { name: 'Capitulos', totalsRowLabel: 'Total General:', filterButton: true },
            { name: 'Frecuencia', totalsRowFunction: 'sum', filterButton: false },
            { name: 'Cantidad', totalsRowFunction: 'sum', filterButton: true }
            ],
            rows: filasTabla,
        });

        worksheet.getColumn(1).width = 20;
        worksheet.getColumn(2).width = 32;
        worksheet.getColumn(3).width = 22;
        worksheet.getColumn(3).numFmt = '#,##0';
        worksheet.getColumn(4).width = 33;
        worksheet.getColumn(5).width = 33;

        let estilosCSS = {
            encabezado: {
                font: { name: 'Arial', size: 11, bold: true, color: { argb: 'FFFFFFFF' } },
                fill: { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF188038' } },
                alignment: { vertical: 'middle', horizontal: 'center' },
                border: {
                top: { style: 'thin', color: { argb: 'FF137333' } },
                bottom: { style: 'medium', color: { argb: 'FF137333' } }
                }
        },
        celdaBase: {
                font: { name: 'Arial', size: 10, color: { argb: 'FF202124' } },
                border: {
                    bottom: { style: 'thin', color: { argb: 'FFDADCE0' } },
                    right: { style: 'thin', color: { argb: 'FFDADCE0' } },
                },
                alignment: { vertical: 'middle', horizontal: 'center' }
            }
        };

        let headerRow = worksheet.getRow(1);
        headerRow.height = 26;
        headerRow.eachCell((cell) => this.aplicarClase(cell, estilosCSS.encabezado));

        filasTabla = criterios.map((item) => {
            let formulaRegex = "IFERROR(COUNTIFS(" + nombreSheetDatos + "!D2:D50000, \"" + item.regex + "\"), 0)";
            let subElement = "IFERROR(OFFSET(INDIRECT(ADDRESS(ROW(), COLUMN())),  0, 1)/C24, 0)";
            let elemento = [
                    "Monte Grande",
                    item.codigo,
                    {formula: subElement},
                    {formula: formulaRegex}
            ];
            return elemento;
        });

        worksheet.addTable({
            name: 'BCIE10',
            ref: 'A27',
            headerRow: true,
            totalsRow: true,
            style: {
                theme: 'TableStyleMedium2',
                showRowStripes: true,
            },
            columns: [
                { name: 'Barrio', totalsRowLabel: 'Total General:', filterButton: true},
                { name: 'Capitulos', filterButton: true},
                { name: 'Frecuencia', numFmt: '#,##0.00', totalsRowFunction: 'sum', filterButton: false},
                { name: 'Cantidad', numFmt: '#,##0', totalsRowFunction: 'sum', filterButton: true}
            ],
            rows: filasTabla,
        });

        headerRow = worksheet.getRow(27);
        headerRow.height = 26;
        headerRow.eachCell((cell) => this.aplicarClase(cell, estilosCSS.encabezado));


        let listaFiltr = codigosMotivos.filter(function (e) {
            const regex = /[aA-zZ][0-9][0-9]\.[0-9X]/;
            return  regex.test(e.codigo);
        });

        let lista = listaFiltr.map(function (e) {
            let regexC = "IFERROR(COUNTIF(" + nombreSheetDatos + "!C2:C50000, \"" + e.motivo + "\"), 0)";
            let regexB = "IFERROR(COUNTIFS(" + nombreSheetDatos + "!C2:C50000, \"" + e.motivo + "\"," + nombreSheetDatos + "!N2:N50000, \"Monte Grande\"), 0)";
            let element = [e.codigo,
                           e.motivo,
                           {formula: regexC},
                           {formula: regexB}
                        ]
            return element;
        });

        worksheet.addTable({
            name: 'CIE10',
            ref: 'A52',
            headerRow: true,
            totalsRow: true,
            style: {
                theme: 'TableStyleMedium2',
                showRowStripes: true
            },
            columns: [
                { name: 'Codigos', filterButton: true },
                { name: 'Denominacion', filterButton: false },
                { name: 'Cantidad Total', totalsRowFunction: 'sum', filterButton: false },
                { name: 'Cant Monte Grande', totalsRowFunction: 'sum', filterButton: false }
            ],
            rows: lista,
        });

        headerRow = worksheet.getRow(52);
        headerRow.height = 26;
        headerRow.eachCell((cell) => this.aplicarClase(cell, estilosCSS.encabezado));

        worksheet.eachRow((row, number) => {
            let check = number == 1 || number == 27 || number == 52;
            if (check) return;
            row.height = 22;

            row.eachCell((cell) => {
                this.aplicarClase(cell, estilosCSS.celdaBase);
            });
        });

        worksheet = workBook.addWorksheet('OTROSMOTIVOS',
                                          {properties:{
                                                   tabColor: {
                                                      argb: '003fff'}
                                          }}
        );

        estilosCSS = {
            encabezado: {
                font: { name: 'Arial', size: 11, bold: true, color: { argb: 'FFFFFFFF' } },
                fill: { type: 'pattern', pattern: 'solid', fgColor: { argb: '0e0d47' } },
                alignment: { vertical: 'middle', horizontal: 'center' },
                border: {
                top: { style: 'thin', color: { argb: '0e0d47' } },
                bottom: { style: 'medium', color: { argb: '0e0d47' } }
                }
        },
        celdaBase: {
                font: { name: 'Arial', size: 10, color: { argb: 'FF202124' } },
                fill: { type: 'pattern', pattern: 'solid', fgColor: { argb: 'f0fcff' } },
                border: {
                    bottom: { style: 'thin', color: { argb: 'FFDADCE0' } },
                    right: { style: 'thin', color: { argb: 'FFDADCE0' } },
                },
                alignment: { vertical: 'middle', horizontal: 'center' }
            }
        };

        let listaMotivos = codigosMotivos.filter(function (e) {
            const regex = /[aA-zZ][0-9][0-9]\.[0-9X]/;
            return  !regex.test(e.codigo);
        });

        let categorias = new Map();
        const rev = new DOMParser();
        let listaCategorias = listaMotivos.map((item) => {
            let doc = rev.parseFromString(item.forma, "text/html");
            categorias.set(item.cod_categoria, {color: item.color, icono: doc.documentElement.textContent});
            return item.cod_categoria
        });

        let setCategorias = new Set(listaCategorias);
        listaCategorias = Array.from(setCategorias);

        let longTablaCategConMargen = listaCategorias.length + 4;
        let longTablaBarriosCategConMargen = listaCategorias.length + 4;
        let longTablaMotivosCategSinMargen = listaMotivos.length + 1;

        let filaIncial = longTablaCategConMargen + longTablaBarriosCategConMargen + 2;
        let filaSubTot = listaCategorias.length + 2;
        
        filasTabla = listaCategorias.map((categoria) => {
            let formulaRegex = "IFERROR(SUMIFS(OTROSMOTIVOS!D" + filaIncial + ":D50000, OTROSMOTIVOS!A" + filaIncial + ":A50000, \"" + categoria + "\"), 0)";
            let subElement = "IFERROR(ROUND(OFFSET(INDIRECT(ADDRESS(ROW(), COLUMN())),  0, 1)/D" + filaSubTot  + ", 2), 0)";

            let elemento = [
                    categoria,
                    {richText: [
                        { text: ' ' + categorias.get(categoria).icono +  '', font: { color: { argb: '' + categorias.get(categoria).color.slice(1) + '' }, bold: true } }
                    ]},
                    {formula: subElement},
                    {formula: formulaRegex}
            ];
            return elemento;
        });


        worksheet.addTable({
            name: 'TablaCategorias',
            ref: 'A1',
            headerRow: true,
            totalsRow: true,
            style: {
                theme: 'TableStyleMedium2',
                showRowStripes: true,
            },
            columns: [
                { name: 'Categorias', totalsRowLabel: 'Total General:', filterButton: true },
                { name: ',', filterButton: false},
                { name: 'Frecuencia', numFmt: '#,##0.00', totalsRowFunction: 'sum', filterButton: false },
                { name: 'Cantidad', numFmt: '#,##0', totalsRowFunction: 'sum', filterButton: true }
            ],
            rows: filasTabla
        });

        worksheet.getColumn(1).width = 20;
        worksheet.getColumn(2).width = 32;
        worksheet.getColumn(3).width = 22;
        worksheet.getColumn(4).width = 33;
        worksheet.getColumn(5).width = 33;

        headerRow = worksheet.getRow(1);
        headerRow.height = 26;
        headerRow.eachCell((cell) => this.aplicarClase(cell, estilosCSS.encabezado));

        worksheet.mergeCells("A1", "B1");

        filasTabla = listaCategorias.map((categoria) => {
            let formulaRegex = "IFERROR(SUMIFS(OTROSMOTIVOS!E" + filaIncial + ":E50000, OTROSMOTIVOS!A" + filaIncial + ":A50000, \"" + categoria + "\"), 0)";
            let subElement = "IFERROR(ROUND(OFFSET(INDIRECT(ADDRESS(ROW(), COLUMN())),  0, 1)/E" + (longTablaCategConMargen + longTablaBarriosCategConMargen + longTablaMotivosCategSinMargen + 1)  + ", 2), 0)";
            let elemento = [
                    "Monte Grande",
                    categoria,
                    {formula: subElement},
                    {formula: formulaRegex}
            ];
            return elemento;
        });

        worksheet.addTable({
            name: 'BarriosCategoria',
            ref: 'A' + (longTablaCategConMargen + 1),
            headerRow: true,
            totalsRow: true,
            style: {
                theme: 'TableStyleMedium2',
                showRowStripes: true,
            },
            columns: [
                { name: 'Barrio', totalsRowLabel: 'Total General:'},
                { name: 'Categorias'},
                { name: 'Frecuencia', numFmt: '#,##0.00', totalsRowFunction: 'sum'},
                { name: 'Cantidad', numFmt: '#,##0', totalsRowFunction: 'sum'}
            ],
            rows: filasTabla,
        });

        headerRow = worksheet.getRow((longTablaCategConMargen + 1));
        headerRow.height = 26;
        headerRow.eachCell((cell) => this.aplicarClase(cell, estilosCSS.encabezado));

        lista = listaMotivos.map(function (e) {
            let motivos = "SUM(COUNTIF(" + nombreSheetDatos + "!C2:C50000, \"" + e.motivo + "\"),";
            motivos += ", COUNTIF(" + nombreSheetDatos + "!D2:D50000, \"" + e.motivo + "\")";
            motivos += ",COUNTIF(" + nombreSheetDatos + "!E2:E50000, \"" + e.motivo + "\"))";

            let regexC = "IFERROR(" + motivos + ", 0)";
            let motivoBarrio = "SUM(COUNTIFS(" + nombreSheetDatos + "!C2:C50000, \"" + e.motivo + "\"," + nombreSheetDatos + "!N2:N50000, \"Monte Grande\")";
            motivoBarrio += ",COUNTIFS(" + nombreSheetDatos + "!D2:D50000, \"" + e.motivo + "\"," + nombreSheetDatos + "!N2:N50000, \"Monte Grande\")";
            motivoBarrio += ",COUNTIFS(" + nombreSheetDatos + "!E2:E50000, \"" + e.motivo + "\"," + nombreSheetDatos + "!N2:N50000, \"Monte Grande\"))";
            let regexB = "IFERROR(" + motivoBarrio + ", 0)";
            let element = [e.cod_categoria,
                           e.motivo,
                           e.codigo,
                           {formula: regexC},
                           {formula: regexB}
                        ]
            return element;
        });

        worksheet.addTable({
            name: 'MotivosCategoria',
            ref: 'A' + (longTablaCategConMargen + longTablaBarriosCategConMargen + 1),
            headerRow: true,
            totalsRow: true,
            style: {
            theme: 'TableStyleMedium2',
            showRowStripes: true,
            },
            columns: [
                { name: 'Codigo Categoria', filterButton: false},
                { name: 'Denominacion', filterButton: false },
                { name: 'Codigos Motivo', filterButton: false },
                { name: 'Cantidad Total', numFmt: '#,##0', totalsRowFunction: 'sum'},
                { name: 'Cant Monte Grande', numFmt: '#,##0', totalsRowFunction: 'sum'}
            ],
            rows: lista,
        });

        headerRow = worksheet.getRow(longTablaCategConMargen + longTablaBarriosCategConMargen + 1);
        headerRow.height = 26;
        headerRow.eachCell((cell) => this.aplicarClase(cell, estilosCSS.encabezado));

        worksheet.eachRow((row, number) => {
            let check = number == 1 ||
                        number == (longTablaCategConMargen + 1) ||
                        number == (longTablaCategConMargen + longTablaBarriosCategConMargen + 1);
            if (check) return;
            row.height = 22;

            row.eachCell((cell) => {
                this.aplicarClase(cell, estilosCSS.celdaBase);
            });
        });

        let rango = new Array(longTablaCategConMargen - 2);
        for (let i of rango.keys()) {
            if (i <= 1) continue;
            const cell = worksheet.getCell("A" + i);
            const valor = categorias.get(cell.value);
            cell.fill = {
                          type: 'pattern',
                          pattern: 'solid',
                          fgColor: { argb: valor.color.slice(1) }
                        };
        };

        rango = new Array(longTablaBarriosCategConMargen - 2);
        for (let i of rango.keys()) {
            if (i <= 1) continue;
            const cell = worksheet.getCell("B" + (i + longTablaCategConMargen));
            const valor = categorias.get(cell.value);
            if (valor) {
                cell.fill = {
                              type: 'pattern',
                              pattern: 'solid',
                              fgColor: { argb: valor.color.slice(1) }
                            };
            }
        }; 

        rango = new Array(longTablaMotivosCategSinMargen + 1);
        for (let i of rango.keys()) {
            if (i <= 1) continue;
            let cell = worksheet.getCell("A" + (i + longTablaCategConMargen + longTablaBarriosCategConMargen));
            const valor = categorias.get(cell.value);
            if (valor) {
                cell.fill = {
                              type: 'pattern',
                              pattern: 'solid',
                              fgColor: { argb: valor.color.slice(1) }
                            };
                cell = worksheet.getCell("C" + (i + longTablaCategConMargen + longTablaBarriosCategConMargen));
                cell.fill = {
                              type: 'pattern',
                              pattern: 'solid',
                              fgColor: { argb: valor.color.slice(1) }
                            };
            }
        }; 

        const buffer = await workBook.xlsx.writeBuffer();
        return buffer;
    }

    aplicarClase(celda, ...clases) {
        Object.assign(celda, ...clases);
    }

    addChartLine(object, data) {
        let list = [];
        const plugin = {
            id: 'customCanvasBackgroundColor',
            beforeDraw: (chart, args, options) => {
                const {ctx} = chart;
                ctx.save();
                ctx.globalCompositeOperation = 'destination-over';
                ctx.fillStyle = options.color || '#99ffff';
                ctx.fillRect(0, 0, chart.width, chart.height);
                ctx.restore();
            }
        };
        object.innerHTML = "<canvas></canvas>";
        data.forEach(function (val, ind, map) {
            list.push({
                label: 'Dataset ' + ind,
                data: val,
                borderColor: '#000000',
                backgroundColor: '#000000'
            });
        });
        const chart = new Chart(object.firstChild, {
            type: 'line',
            data: {
                    labels: list[0].data,
                    datasets: list.slice(1)
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: "",
                    },
                    subtitle: {
                        display: true,
                        text: ''
                    },
                    scales: {
                        x: {
                            display: true,
                            title: "R",
                            color: "black"
                        },
                        y: {
                            display: true,
                            title: "G",
                            color: "black"
                        }
                    },
                    customCanvasBackgroundColor: {
                            color: 'white',
                    }
                },
                devicePixelRatio: 3
            },
            plugins: [plugin]
        });
        return chart;
    }

    addChartRadar(object, data) {
        let list = [];
        const plugin = {
            id: 'customCanvasBackgroundColor',
            beforeDraw: (chart, args, options) => {
                const {ctx} = chart;
                ctx.save();
                ctx.globalCompositeOperation = 'destination-over';
                ctx.fillStyle = options.color || '#99ffff';
                ctx.fillRect(0, 0, chart.width, chart.height);
                ctx.restore();
            }
        };
        object.innerHTML = "<canvas></canvas>";
        data.forEach(function (val, ind, map) {
            list.push({
                label: 'Dataset ' + ind,
                data: val,
                fill: true,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgb(255, 99, 132)',
                pointBackgroundColor: 'rgb(255, 99, 132)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(255, 99, 132)'
            });
        });
        const chart = new Chart(object.firstChild, {
            type: 'radar',
            data: {
                labels: list[0].data,
                datasets: list.slice(1)
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: "",
                    },
                    customCanvasBackgroundColor: {
                            color: 'white',
                    },
                    subtitle: {
                        display: true,
                        text: ''
                    },
                    scales: {
                        x: {
                            display: true,
                            title: "",
                            color: "#0000"
                        },
                        y: {
                            display: true,
                            title: "",
                            color: "#0000"
                        }
                    }
                },
                devicePixelRatio: 3
            },
            plugins: [plugin]
        });
        return chart;
    }

    addChartP(object, data) {
        let list = [];
        const plugin = {
            id: 'customCanvasBackgroundColor',
            beforeDraw: (chart, args, options) => {
                const {ctx} = chart;
                ctx.save();
                ctx.globalCompositeOperation = 'destination-over';
                ctx.fillStyle = options.color || '#99ffff';
                ctx.fillRect(0, 0, chart.width, chart.height);
                ctx.restore();
            }
        };
        object.innerHTML = "<canvas></canvas>";
        data.forEach(function (val, ind, map) {
            list.push({
                label: 'Dataset ' + ind,
                data: val,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: "",
                        },
                        customCanvasBackgroundColor: {
                                color: 'white',
                        },
                        subtitle: {
                            display: true,
                            text: ''
                        }
                    },
                devicePixelRatio: 3
            },
            plugins: [plugin]
        });
        });
        const chart = new Chart(object.firstChild, {
            type: 'polarArea',
            data: {
                labels: list[0].data,
                datasets: list.slice(1)
            },
            options: {
                onClick: (e) => {
                const canvasPosition = getRelativePosition(e, chart);

                const dataX = chart.scales.x.getValueForPixel(canvasPosition.x);
                const dataY = chart.scales.y.getValueForPixel(canvasPosition.y);
                },
                plugins: {
                    title: {
                        display: true,
                        text: "",
                    },
                    customCanvasBackgroundColor: {
                            color: 'white',
                    },
                    subtitle: {
                        display: true,
                        text: ''
                    },
                    scales: {
                        x: {
                            display: true,
                            title: "",
                            color: "#0000"
                        },
                        y: {
                            display: true,
                            title: "",
                            color: "#0000"
                        }
                    }
                },
                devicePixelRatio: 3
            },
            plugins: [plugin]
        });
        return chart;
    }

    addChartC(object, data) {
        const plugin = {
            id: 'customCanvasBackgroundColor',
            beforeDraw: (chart, args, options) => {
                const {ctx} = chart;
                ctx.save();
                ctx.globalCompositeOperation = 'destination-over';
                ctx.fillStyle = options.color || '#99ffff';
                ctx.fillRect(0, 0, chart.width, chart.height);
                ctx.restore();
            }
        };
        object.innerHTML = "<canvas></canvas>";
        const chart = new Chart(object.firstChild, {
            type: 'scatter',
            data: {
                    datasets: [{
                        label: 'Scatter Dataset',
                        data: [{
                                x: -10,
                                y: 0
                            }, {
                                x: 0,
                                y: 10
                            }, {
                                x: 10,
                                y: 5
                            }, {
                                x: 0.5,
                                y: 5.5
                        }],
                        backgroundColor: 'rgb(255, 99, 132)'
                    }],
                    },
            options: {
                onClick: (e) => {
                const canvasPosition = getRelativePosition(e, chart);

                const dataX = chart.scales.x.getValueForPixel(canvasPosition.x);
                const dataY = chart.scales.y.getValueForPixel(canvasPosition.y);
                },
                plugins: {
                    title: {
                        display: true,
                        text: "",
                    },
                    customCanvasBackgroundColor: {
                            color: 'white',
                    },
                    subtitle: {
                        display: true,
                        text: ''
                    },
                    scales: {
                        x: {
                            display: true,
                            title: "",
                            color: "#0000"
                        },
                        y: {
                            display: true,
                            title: "",
                            color: "#0000"
                        }
                    }
                },
                devicePixelRatio: 3
            },
            plugins: [plugin]
        });
        return chart;
    }

    addChartTorta(object, data) {
        let list = [];
        const plugin = {
            id: 'customCanvasBackgroundColor',
            beforeDraw: (chart, args, options) => {
                const {ctx} = chart;
                ctx.save();
                ctx.globalCompositeOperation = 'destination-over';
                ctx.fillStyle = options.color || '#99ffff';
                ctx.fillRect(0, 0, chart.width, chart.height);
                ctx.restore();
            }
        };
        object.innerHTML = "<canvas></canvas>";
        data.forEach(function (val, ind, map) {
            list.push({
                label: 'Dataset ' + ind,
                data: val,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                ],
                hoverOffset: 4
            });
        });
        const chart = new Chart(object.firstChild, {
            type: 'pie',
            data: {
                labels: list[0].data,
                datasets: list.slice(1)
            },
            options: {
                onClick: (e) => {
                const canvasPosition = getRelativePosition(e, chart);

                const dataX = chart.scales.x.getValueForPixel(canvasPosition.x);
                const dataY = chart.scales.y.getValueForPixel(canvasPosition.y);
                },
                plugins: {
                    title: {
                        display: true,
                        text: "",
                    },
                    customCanvasBackgroundColor: {
                            color: 'white',
                    },
                    subtitle: {
                        display: true,
                        text: ''
                    },
                    scales: {
                        x: {
                            display: true,
                            title: "",
                            color: "#0000"
                        },
                        y: {
                            display: true,
                            title: "",
                            color: "#0000"
                        }
                    }
                },
                devicePixelRatio: 3
            },
            plugins: [plugin]
        });
        return chart;
    }

    addChartDona(object, data) {
        let list = [];
        const plugin = {
            id: 'customCanvasBackgroundColor',
            beforeDraw: (chart, args, options) => {
                const {ctx} = chart;
                ctx.save();
                ctx.globalCompositeOperation = 'destination-over';
                ctx.fillStyle = options.color || '#99ffff';
                ctx.fillRect(0, 0, chart.width, chart.height);
                ctx.restore();
            }
        };
        object.innerHTML = "<canvas></canvas>";
        data.forEach(function (val, ind, map) {
            list.push({
                label: 'Dataset ' + ind,
                data: val,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                ],
                borderWidth: 1
            });
        });
        const chart = new Chart(object.firstChild, {
            type: 'doughnut',
            data: {
                labels: list[0].data,
                datasets: list.slice(1)
            },
            options: {
                onClick: (e) => {
                const canvasPosition = getRelativePosition(e, chart);

                const dataX = chart.scales.x.getValueForPixel(canvasPosition.x);
                const dataY = chart.scales.y.getValueForPixel(canvasPosition.y);
                },
                plugins: {
                    title: {
                        display: true,
                        text: "",
                    },
                    customCanvasBackgroundColor: {
                            color: 'white',
                    },
                    subtitle: {
                        display: true,
                        text: ''
                    },
                    scales: {
                        x: {
                            display: true,
                            title: "",
                            color: "#0000"
                        },
                        y: {
                            display: true,
                            title: "",
                            color: "#0000"
                        }
                    }
                },
                devicePixelRatio: 3
            },
            plugins: [plugin]
        });
        return chart;
    }

    addChartBubble(object, data) {
        const plugin = {
            id: 'customCanvasBackgroundColor',
            beforeDraw: (chart, args, options) => {
                const {ctx} = chart;
                ctx.save();
                ctx.globalCompositeOperation = 'destination-over';
                ctx.fillStyle = options.color || '#99ffff';
                ctx.fillRect(0, 0, chart.width, chart.height);
                ctx.restore();
            }
        };
        object.innerHTML = "<canvas></canvas>";
        const chart = new Chart(object.firstChild, {
            type: 'bubble',
            data: {
                datasets: [{
                    label: 'First Dataset',
                    data: [{
                            x: 20,
                            y: 30,
                            r: 15
                        }, {
                            x: 40,
                            y: 10,
                            r: 10
                        }],
                        backgroundColor: 'rgb(255, 99, 132)'
                    }]
                },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: "",
                    },
                    customCanvasBackgroundColor: {
                            color: 'white',
                    },
                    subtitle: {
                        display: true,
                        text: ''
                    },
                    scales: {
                        x: {
                            display: true,
                            title: "",
                            color: "#0000"
                        },
                        y: {
                            display: true,
                            title: "",
                            color: "#0000"
                        }
                    }
                },
                devicePixelRatio: 3
            },
            plugins: [plugin]
        });
        return chart;
    }

    addChartIntervalo(object, data) {
        let list = [];
        const plugin = {
            id: 'customCanvasBackgroundColor',
            beforeDraw: (chart, args, options) => {
                const {ctx} = chart;
                ctx.save();
                ctx.globalCompositeOperation = 'destination-over';
                ctx.fillStyle = options.color || '#99ffff';
                ctx.fillRect(0, 0, chart.width, chart.height);
                ctx.restore();
            }
        };
        object.innerHTML = "<canvas></canvas>";
        data.forEach(function (val, ind, map) {
            list.push({
                label: 'Dataset ' + ind,
                data: val,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                ],
                borderWidth: 1
            });
        });
        const chart = new Chart(object.firstChild, {
            type: 'bar',
            data: {
                labels: list[0].data,
                datasets: list.slice(1)
                },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: "",
                    },
                    customCanvasBackgroundColor: {
                            color: 'white',
                    },
                    subtitle: {
                        display: true,
                        text: ''
                    },
                    scales: {
                        x: {
                            display: true,
                            title: "",
                            color: "#0000"

                        },
                        y: {
                            display: true,
                            title: "",
                            color: "#0000"
                        }
                    }
                },
                devicePixelRatio: 3
            },
            plugins: [plugin]
        });
        return chart;
    }

    selectionActive(instance, x1, y1, x2, y2, origin) {
        let isInner = null;
        if (origin.type == "mousedown") this.setFocus(instance, x1, y1, origin);
        if (origin && $("#bar-element").length && instance.getSelected()[0].element.childNodes.length) {
            if (origin.ctrlKey) {
                this.#cellSelection = this.#cellSelection.filter(function (e) {
                    if (this.isSubset(e, x1, y1, x2, y2)) {
                        this.setListFocus(e);
                        return false;
                    }
                    return true;
                }.bind(this));

                isInner = this.isInnerSet(instance.getSelection());
                if (!isInner) this.#cellSelection.push(instance.getSelection());

                this.#cellSelection.forEach(element => {
                    let elem = null;
                    let classList = null;


                    for (let x = element[0]; x <= element[2]; x++) {
                        elem = this.#spreadsheet[0].getCellFromCoords(x, element[1]);
                        classList = elem.classList;
                        classList.add("highlight-top");
                        classList.add("highlight");
                        elem = this.#spreadsheet[0].getCellFromCoords(x, element[3]);
                        classList = elem.classList;
                        classList.add("highlight-bottom");
                        classList.add("highlight");
                    }
                    for (let e = element[1]; e <= element[3]; e++) {
                        elem = this.#spreadsheet[0].getCellFromCoords(element[0], e);
                        classList = elem.classList;
                        classList.add("highlight-left");
                        classList.add("highlight");
                        elem = this.#spreadsheet[0].getCellFromCoords(element[2], e);
                        classList = elem.classList;
                        classList.add("highlight-right");
                        classList.add("highlight");
                    }
                });

            } else {
                if (origin.type == "mousedown") {
                    this.#cellSelection.forEach(element => {
                        for (let x = element[0]; x <= element[2]; x++) {
                            for (let e = element[1]; e <= element[3]; e++) {
                                this.setFocousOut(instance, x, e);                            
                            }                        
                        }
                    });
                }
                this.#cellSelection = [instance.getSelection()];
            }
            $("#bar-element").val(instance.getSelected()[0].element.childNodes[0].nodeValue);
            $("#bar-element").prop("data-x", x1);
            $("#bar-element").prop("data-y", y1);
        }
    }

    selectionChange(spreadsheet, e) {
        let x = $("#bar-element").prop("data-x");
        let y = $("#bar-element").prop("data-y");
        let element = null;
        if ((x || x == 0)  && (y || y == 0)) {
            element = $("#bar-element").prop("value");
            this.#spreadsheet[0].setValueFromCoords(x, y, element);
            this.#spreadsheet[0].resetSelection(true)
        }
    }

    setFocusActive(spreadsheet) {
        let x = $("#bar-element").prop("data-x");
        let y = $("#bar-element").prop("data-y");
        let element = null;
        if ((x || x == 0)  && (y || y == 0)) {
            //spreadsheet[0].updateSelectionFromCoords(x, y, x, y);
            element = this.#spreadsheet[0].getCellFromCoords(x, y);
            element.blur();
            this.#spreadsheet[0].resetSelection(true)
            element.classList.add("highlight-selected");
            element.classList.add("highlight");
            element.classList.add("highlight-top");
            element.classList.add("highlight-bottom");
            element.classList.add("highlight-left");
            element.classList.add("highlight-right");
        }
    }

    setFocousIn(spreadsheet, x ,y) {
        let element = spreadsheet.getCellFromCoords(x, y);
        let classList = element.classList;
        classList.add("highlight-selected");
        classList.add("highlight");
        classList.add("highlight-top");
        classList.add("highlight-bottom");
        classList.add("highlight-left");
        classList.add("highlight-right");

    }
    
    setFocousOut(spreadsheet, x ,y) {
        let element = spreadsheet.getCellFromCoords(x, y);
        let classList = element.classList;
        classList.remove("highlight-selected");
        classList.remove("highlight");
        classList.remove("highlight-top");
        classList.remove("highlight-bottom");
        classList.remove("highlight-left");
        classList.remove("highlight-right");

    }

    isSubset(list, x1, y1, x2, y2) {
        let check = false;
        if (list[0] >= x1 && list[2] <= x2) {
            if (list[1] >= y1 && list[3] <= y2) check = true;
        }
        return check;
    }

    isInnerSet(list) {
        let check = false;
        check = this.#cellSelection.reduce(function (acumulador, value) {
            return acumulador || this.isSubset(list, value[0], value[1],value[2], value[3]);
        }.bind(this), false);
        return check;
    }

    setListFocus(element) {
        let elem = null;
        let classList = null;

        for (let x = element[0]; x <= element[2]; x++) {
            elem = this.#spreadsheet[0].getCellFromCoords(x, element[1]);
            classList = elem.classList;
            classList.remove("highlight-top");
            classList.remove("highlight");
            elem = this.#spreadsheet[0].getCellFromCoords(x, element[3]);
            classList = elem.classList;
            classList.remove("highlight-bottom");
            classList.remove("highlight");
        }
        for (let e = element[1]; e <= element[3]; e++) {
            elem = this.#spreadsheet[0].getCellFromCoords(element[0], e);
            classList = elem.classList;
            classList.remove("highlight-left");
            classList.remove("highlight");
            elem = this.#spreadsheet[0].getCellFromCoords(element[2], e);
            classList = elem.classList;
            classList.remove("highlight-right");
            classList.remove("highlight");
        }
    }

    setFocus(spreadsheet, xselect, yselect, e) {
        let x = $("#bar-element").prop("data-x");
        let y = $("#bar-element").prop("data-y");
        let element = null;
        if ((x || x == 0)  && (y || y == 0) 
            && (x != xselect || y != yselect)
            && (e.key === "Enter" || e.type === "mousedown"
                 || e.type === "blur")) {
            //spreadsheet[0].updateSelectionFromCoords(x, y, x, y);
            //element = this.#spreadsheet[0].getCellFromCoords(x, y);
            element = spreadsheet.getCellFromCoords(x, y);
            element.classList.remove("highlight-selected");
            element.classList.remove("highlight");
            element.classList.remove("highlight-top");
            element.classList.remove("highlight-bottom");
            element.classList.remove("highlight-left");
            element.classList.remove("highlight-right");
        }
    }

    setValActive(spreadsheet) {
        let x = $("#bar-element").prop("data-x");
        let y = $("#bar-element").prop("data-y");
        if ((x || x == 0)  && (y || y == 0)) {
            this.#spreadsheet[0].setValueFromCoords(x, y, $("#bar-element").val());
            this.#spreadsheet[0].resetSelection(true)
        }
    }

    insertInputTab(tab) {
        let excel = tab.innerText;
        let text = "<input type='text' style='width: 100%; border-color: rgb(197 197 197); border-style: solid; border-width: 1.8px;' value='" + excel + "' data-prev='" + excel + "' id='tab-element'>";
        tab.innerHTML = text;
        tab.childNodes[0].focus();
        $("#tab-element").on("blur", function (e) {
            let val = $("#tab-element").val();
            if (val) tab.innerHTML = val;
        });
        $("#tab-element").on("keydown", function (e) {
            let val = $("#tab-element").val();
            let prev = $("#tab-element").prop("data-prev");
            if (!val) val = prev;
            if (e.key === "Enter") tab.innerHTML = val;
        });
    }

    getContextMenu(o, x, y, e, items, section) {
        let itemsArr = [];
        if (section == 'header') {
            itemsArr.push({
                title: 'Execute one action',
                onclick: function() {
                    alert('test')
                }
            });

            itemsArr.push({ type: 'line' });
        }

        if (section == 'tabs') {
            itemsArr.push({
                title: 'Eliminar Excel',
                onclick: function() {
                    this.#spreadsheet[0].deleteWorksheet(x);
                }
            });

            itemsArr.push({
                title: 'Agregar Excel',
                onclick: function() {
                    let name = prompt("Ingrese el nombre del excel");
                    this.#spreadsheet[0].createWorksheet({worksheetName: name, minDimensions: [15, 15]}, x);
                }
            });

            itemsArr.push({
                title: 'Renombrar Excel',
                onclick: function() {
                    let name = prompt("Ingrese el nombre del excel");
                    if (name) e.target.innerText = name;
                }
            });

            itemsArr.push({ type: 'line' });
        }

        if (section == 'cell') {
            let data = new Map();
            let s = o.getSelected(false);
            s.forEach(function (val, ind, array) {
                let text = (val.element.innerText) ? val.element.innerText : "";
                if (data.has(val.x)) {
                    data.get(val.x).push(text);
                } else {
                        data.set(val.x, []);
                }
            })

            itemsArr.push({
                title: 'Insertar grafico Personalizado',
                onclick: function() {
                    this.addChartLine(e.target, data);
                }
            });

            itemsArr.push({
                title: 'Insertar grafico Lineal',
                onclick: function() {
                    this.addChartLine(e.target, data);
                }
            });

            itemsArr.push({
                title: 'Insertar grafico Intervalo',
                onclick: function() {
                    this.addChartIntervalo(e.target, data);
                }
            });

            itemsArr.push({
                title: 'Insertar grafico Radar',
                onclick: function() {
                    this.addChartRadar(e.target, data);
                }
            });

            itemsArr.push({
                title: 'Insertar grafico P Area',
                onclick: function() {
                    this.addChartP(e.target, data);
                }
            });

            itemsArr.push({
                title: 'Insertar grafico Scatter',
                onclick: function() {
                    this.addChartC(e.target, data);
                }
            });

            itemsArr.push({
                title: 'Insertar grafico Torta',
                onclick: function() {
                    this.addChartTorta(e.target, data);
                }
            });

            itemsArr.push({
                title: 'Insertar grafico Dona',
                onclick: function() {
                    this.addChartDona(e.target, data);
                }
            });

            itemsArr.push({
                title: 'Insertar grafico Bubble',
                onclick: function() {
                    addChartBubble(e.target, data);
                }
            });

            itemsArr.push({ type: 'line' });

            itemsArr.push({
                title: 'Zoom mas',
                onclick: function() {
                    this.addChartBubble(e.target, data);
                }
            });

            itemsArr.push({
                title: 'Zoom menos',
                onclick: function() {
                    this.addChartBubble(e.target, data);
                }
            });
        }

        /*
        itemsArr.push({
            title: 'Save as',
            shortcut: 'Ctrl + S',
            icon: 'save',
            onclick: function () {
                o.download();
            }
        });
        */
        /*
        // About
        itemsArr.push({
            title: 'About',
            onclick: function() {
                alert('https://jspreadsheet.com');
            }
        });
        */
        let list = [];
        list = itemsArr.concat(items.slice(0, -2));
        return list;
    }

    existChart(x, y) {
        return this.#charts.has(x + "-" + y);
    }

    delete() {
        if (this.#spreadsheet && this.#spreadsheet[0]) {
            this.#spreadsheet[0].destroyAll();
        }
    }
    
}

