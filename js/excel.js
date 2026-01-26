import jspreadsheet from "../node_modules/jspreadsheet-ce";
import ExcelJS from "../node_modules/exceljs";
import Chart from 'chart.js/auto';
import Jsuit from 'jsuites';
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
        let th = $("table thead th:not([hidden='true'])");
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
            th.each(function (index) {
                let typeData = "text";
                let text = $(this).text().trim();

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

            $("tbody tr td:not([hidden='true'])").each(function (index) {
                let rowlist = [];
                $(this).children().each(function (e) {
                    rowlist.push(
                        $(this).text().trim()
                    );            
                });
                elems.push(rowlist);
            });

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
                                })

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
                                                <div id='form'>
                                                    <form id='root'>
                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group'>
                                                                    <label>Titulo</label>
                                                                    <input type='text' id='titulo' name='titulo' value='grafico' data-validation='titulo'>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group'>
                                                                    <label>Subtitulo</label>
                                                                    <input type='text' id='subtitulo' name='subtitulo' value='grafico' data-validation='subtitulo'>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group'>
                                                                    <label>Etiqueta de Eje</label>
                                                                    <input id='etiqueta' type='text' name='etiqueta' value='Etiqueta'>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group'>
                                                                    <label>Eje</label>
                                                                    <select id='eje' name='eje'>
                                                                    <option value="0">X</option>
                                                                    <option value="1">Y</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group'>
                                                                    <label>Ancho de imagen</label>
                                                                    <input type='number' id='width' value='200' name='width' data-validation='width'>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group'>
                                                                    <label>Alto de imagen</label>
                                                                    <input type='number' id='height' value='200' name='height' data-validation='height'>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group'>
                                                                    <label>Color</label>
                                                                    <input id='color' value="#009688" data-validation='color'/>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group'>
                                                                    <label>Datos</label>
                                                                    <select id='datos' name='datos'>
                                                                    <option value="-1">Seleccionar Dato</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='row'>
                                                            <div class='column'>
                                                                <div class='form-group'>
                                                                    <label>Color Dato</label>
                                                                    <input id='color-datos' value="#009688" data-validation='color-datos'/>
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
                            this.#chart.render()
                        }.bind(this));
        }
    }

    async excel_download(
                         objectJsonTabla,
                         listConfig
                        ) {

        const vic = new ExcelJS.Workbook();
        let worksheet = vic.addWorksheet('rastreador', {properties:{tabColor: {argb: 'FFC0000'}}})

        let values = [];
        let count = objectJsonTabla.movimientos_general.length;

        let listaDeMovimientos = objectJsonTabla.movimientos_general.map(function (velem, index, array) {
            listConfig.forEach(function (elemen, index, array) {
                delete velem[elemen];
            });
            return velem;
        });

        for(let row = 1; row < count; row++) {
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
            worksheet.getRow(row).values  = Object.values(listaDeMovimientos[row]).slice(1);
            worksheet.getRow(row).font = {bgColor:{argb:'FFC000'}};
            worksheet.getRow(row).alignment = { vertical: 'middle', horizontal: 'center' };
            worksheet.getRow(row).style  = {bgColor:{argb:'FFC000'}};
            worksheet.getRow(row).height = 20;
        }

        values = objectJsonTabla.header_movimientos_general.filter(function (e) {
            return !listConfig.includes(e);
        });

        values = values.map(function (e) {
            let val = {header: e, key: e, width: 30,  filterButton: true};
            return val;
        });

        worksheet.columns = values;
        const buffer = await vic.xlsx.writeBuffer();
        return buffer;

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
        if (this.#spreadsheet) this.#spreadsheet[0].destroyAll();
    }
    
}

