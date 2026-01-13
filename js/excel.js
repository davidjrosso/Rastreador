import jspreadsheet from "../node_modules/jspreadsheet-ce";
import ExcelJS from "../node_modules/exceljs";
import Chart from 'chart.js/auto';

let zoom = 100;

export function excel() {
    let list = [];
    let elems = [];
    let th = $("table th");
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

        $("tbody tr").each(function (index) {
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

        let spreadsheet = jspreadsheet(document.getElementById('excel_rev'), {
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
                onselection: selectionActive,
                search: true,
                toolbar:true,
                tabs: true,
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
                            if (zoom < 150) zoom += 10;
                            //o.table.style.zoom = zoom + "%";
                            spreadsheet[0].table.style.zoom = zoom + "%";
                        }
                    });

                    toolbar.items.push({
                        tooltip: 'zoom - (menos)',
                        type: 'icon',
                        content: 'remove_circle_outline',
                        onclick: function(e, x, y, n, items, section) {
                            if (zoom > 10) zoom -= 10;
                            spreadsheet[0].table.style.zoom = zoom + "%";
                        }
                    });

                    return toolbar;
                },
                contextMenu: function(o, x, y, e, items, section) {
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
                                    spreadsheet[0].deleteWorksheet(x);
                                }
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
                                    spreadsheet[0].createWorksheet(options, x);
                                }
                            });

                            itemsArr.push({
                                title: 'Renombrar Excel',
                                onclick: function(event) {
                                    insertInputTab(e.target);
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
                                    addChartLine(e.target, data);
                                }
                            });

                            itemsArr.push({
                                title: 'Insertar grafico Lineal',
                                onclick: function() {
                                    addChartLine(e.target, data);
                                }
                            });

                            itemsArr.push({
                                title: 'Insertar grafico Intervalo',
                                onclick: function() {
                                    addChartIntervalo(e.target, data);
                                }
                            });

                            itemsArr.push({
                                title: 'Insertar grafico Radar',
                                onclick: function() {
                                    addChartRadar(e.target, data);
                                }
                            });

                            itemsArr.push({
                                title: 'Insertar grafico P Area',
                                onclick: function() {
                                    addChartP(e.target, data);
                                }
                            });

                            itemsArr.push({
                                title: 'Insertar grafico Scatter',
                                onclick: function() {
                                    addChartC(e.target, data);
                                }
                            });

                            itemsArr.push({
                                title: 'Insertar grafico Torta',
                                onclick: function() {
                                    addChartTorta(e.target, data);
                                }
                            });

                            itemsArr.push({
                                title: 'Insertar grafico Dona',
                                onclick: function() {
                                    addChartDona(e.target, data);
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
                                title: 'Zoom + (mas)',
                                onclick: function() {
                                    if (zoom < 150) zoom += 10;
                                    o.table.style.zoom = zoom + "%";
                                }
                            });

                            itemsArr.push({
                                title: 'Zoom - (menos)',
                                onclick: function() {
                                    if (zoom > 10) zoom -= 10;
                                    o.table.style.zoom = zoom + "%";
                                }
                            });

                            itemsArr.push({ type: 'line' });                            
                        }

                        let list = [];
                        list = itemsArr.concat(items.slice(0, -2));
                        return list;
            },
            columnDrag: true
        });
        let text2 = "FX <input type='text' style='width: 90%; border-color: rgb(197 197 197); border-style: solid; border-width: 1.8px;' data-x='1' data-y='2' id='bar-element'>";
        //$(".jss_filter label").append(text);
        $(".jss_filter").prepend("<div style='flex-grow: 1; flex-basis: 60%;'> <label style='width: 100%; margin-bottom: 0rem;'>" + text2 + "</label> </div>");
        $("#bar-element").on("click", setFocusActive.bind(this, spreadsheet));
        $("#bar-element").on("blur", setFocus.bind(this, spreadsheet));
        $("#bar-element").on("keydown", setFocus.bind(this, spreadsheet));
        $("#bar-element").on("input", selectionChange.bind(this, spreadsheet));
    }
}

export async function excel_download(objectJsonTabla) {

    const vic = new ExcelJS.Workbook();
    let worksheet = vic.addWorksheet('rastreador', {properties:{tabColor: {argb: 'FFC0000'}}})

    let values = [];
    let count = objectJsonTabla.movimientos_general.length;

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
        worksheet.getRow(row).values  = Object.values(objectJsonTabla.movimientos_general[row]).slice(1);
        worksheet.getRow(row).font = {bgColor:{argb:'FFC000'}};
        worksheet.getRow(row).alignment = { vertical: 'middle', horizontal: 'center' };
        worksheet.getRow(row).style  = {bgColor:{argb:'FFC000'}};
        worksheet.getRow(row).height = 20;
    }

    values = objectJsonTabla.header_movimientos_general.map(function (e) {
        let val = {header: e, key: e, width: 30,  filterButton: true};
        return val;
    });

    worksheet.columns = values;
    const buffer = await vic.xlsx.writeBuffer();
    return buffer;

}

export function addChartLine(object, data) {
    let list = [];
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
        options: {}
    });
}

export function addChartRadar(object, data) {
    let list = [];
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
            onClick: (e) => {
            const canvasPosition = getRelativePosition(e, chart);

            const dataX = chart.scales.x.getValueForPixel(canvasPosition.x);
            const dataY = chart.scales.y.getValueForPixel(canvasPosition.y);
            }
        }
    });
}

export function addChartP(object, data) {
    let list = [];
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
            ]
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
            }
        }
    });
}

export function addChartC(object, data) {
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
            }
        }
    });
}

export function addChartTorta(object, data) {
    let list = [];
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
            }
        }
    });
}

export function addChartDona(object, data) {
    let list = [];
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
            }
        }
    });
}

export function addChartBubble(object, data) {
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
        options: {}
    });
}

export function addChartIntervalo(object, data) {
    let list = [];
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
        options: {}
    });
}

function selectionActive(instance, x1, y1, x2, y2, origin) {
    if ($("#bar-element").length && instance.getSelected()[0].element.childNodes.length) {
        $("#bar-element").val(instance.getSelected()[0].element.childNodes[0].nodeValue);
        $("#bar-element").prop("data-x", x1);
        $("#bar-element").prop("data-y", y1);
    }
}

function selectionChange(spreadsheet, e) {
    let x = $("#bar-element").prop("data-x");
    let y = $("#bar-element").prop("data-y");
    let element = null;
    if ((x || x == 0)  && (y || y == 0)) {
        element = $("#bar-element").prop("value");
        spreadsheet[0].setValueFromCoords(x, y, element);
    }
}

function setFocusActive(spreadsheet) {
    let x = $("#bar-element").prop("data-x");
    let y = $("#bar-element").prop("data-y");
    let element = null;
    if ((x || x == 0)  && (y || y == 0)) {
        //spreadsheet[0].updateSelectionFromCoords(x, y, x, y);
        element = spreadsheet[0].getCellFromCoords(x, y);
        element.blur();
        element.classList.add("highlight-selected");
        element.classList.add("highlight");
        element.classList.add("highlight-top");
        element.classList.add("highlight-bottom");
        element.classList.add("highlight-left");
        element.classList.add("highlight-right");
    }
}

function setFocus(spreadsheet, e) {
    let x = $("#bar-element").prop("data-x");
    let y = $("#bar-element").prop("data-y");
    let element = null;
    if ((x || x == 0)  && (y || y == 0) 
        && (e.key === "Enter" || e.type === "blur")) {
        //spreadsheet[0].updateSelectionFromCoords(x, y, x, y);
        element = spreadsheet[0].getCellFromCoords(x, y);
        element.classList.remove("highlight-selected");
        element.classList.remove("highlight");
        element.classList.remove("highlight-top");
        element.classList.remove("highlight-bottom");
        element.classList.remove("highlight-left");
        element.classList.remove("highlight-right");
    }
}

function setValActive(spreadsheet) {
    let x = $("#bar-element").prop("data-x");
    let y = $("#bar-element").prop("data-y");
    if ((x || x == 0)  && (y || y == 0)) {
        spreadsheet[0].setValueFromCoords(x, y, $("#bar-element").val());
    }
}

function insertInputTab(tab) {
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

function getContextMenu(o, x, y, e, items, section) {
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
                spreadsheet[0].deleteWorksheet(x);
            }
        });

        itemsArr.push({
            title: 'Agregar Excel',
            onclick: function() {
                let name = prompt("Ingrese el nombre del excel");
                spreadsheet[0].createWorksheet({worksheetName: name, minDimensions: [15, 15]}, x);
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
                addChartLine(e.target, data);
            }
        });

        itemsArr.push({
            title: 'Insertar grafico Lineal',
            onclick: function() {
                addChartLine(e.target, data);
            }
        });

        itemsArr.push({
            title: 'Insertar grafico Intervalo',
            onclick: function() {
                addChartIntervalo(e.target, data);
            }
        });

        itemsArr.push({
            title: 'Insertar grafico Radar',
            onclick: function() {
                addChartRadar(e.target, data);
            }
        });

        itemsArr.push({
            title: 'Insertar grafico P Area',
            onclick: function() {
                addChartP(e.target, data);
            }
        });

        itemsArr.push({
            title: 'Insertar grafico Scatter',
            onclick: function() {
                addChartC(e.target, data);
            }
        });

        itemsArr.push({
            title: 'Insertar grafico Torta',
            onclick: function() {
                addChartTorta(e.target, data);
            }
        });

        itemsArr.push({
            title: 'Insertar grafico Dona',
            onclick: function() {
                addChartDona(e.target, data);
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
                addChartBubble(e.target, data);
            }
        });

        itemsArr.push({
            title: 'Zoom menos',
            onclick: function() {
                addChartBubble(e.target, data);
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