import jspreadsheet from "../node_modules/jspreadsheet-ce";
import ExcelJS from "../node_modules/exceljs";
import Chart from 'chart.js/auto';


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
            if ($(this).text().trim() == "Años") return true;
            if ($(this).text().trim() == "Meses") return true;

            if ($(this).text().trim() != "Edad") {
                list.push({
                        type: 'text',
                        title: $(this).text().trim(),
                        width:'300px'
                });
            } else {
                list.push({
                        type: 'text',
                        title: "Años",
                        width:'300px'
                });
                list.push({
                        type: 'text',
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
            })
            elems.push(rowlist);
        });
        let spreadsheet = jspreadsheet(document.getElementById('excel_rev'), {
            worksheets: [{
                data: elems,
                columns: list,
                filters: true,
                allowComments:true,
                search: true,
                worksheetName: "Excel",
                allowDeleteWorksheet: true,
                allowRenameWorksheet: true,
                allowMoveWorksheet: true,
                }],
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
                                title: 'delete excel',
                                onclick: function() {
                                    spreadsheet[0].deleteWorksheet(x);
                                }
                            });

                            itemsArr.push({
                                title: 'add excel',
                                onclick: function() {
                                    let name = prompt("Ingrese el nombre del excel");
                                    spreadsheet[0].createWorksheet({worksheetName: name, minDimensions: [15, 15]}, x);
                                }
                            });

                            itemsArr.push({ type: 'line' });
                        }

                        if (section == 'cell') {
                            let s = spreadsheet[0].getSelected(false);
                            
                            itemsArr.push({
                                title: 'insert grafico',
                                onclick: function() {
                                    addChart(e.target);
                                }
                            });

                            s = spreadsheet[0].getSelected(false);

                            itemsArr.push({
                                title: 'insert grafico radar',
                                onclick: function() {
                                    addChartRadar(e.target);
                                }
                            });

                            s = spreadsheet[0].getSelected(false);

                            itemsArr.push({
                                title: 'insert grafico P',
                                onclick: function() {
                                    addChartP(e.target);
                                }
                            });

                            s = spreadsheet[0].getSelected(false);

                            itemsArr.push({
                                title: 'insert grafico C',
                                onclick: function() {
                                    addChartC(e.target);
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
                        itemsArr.forEach((e) => list.push(e));
                        items.forEach((e) => list.push(e));
                        return list;
            },
            columnDrag: true
        });
        let text = $(".jss_filter label").html().replace("Search", "Buscar");
        let text2 = "FX <input type='text' class='' id='bar-element'>";
        $(".jss_filter label").text("");
        $(".jss_filter label").html(text);
        //$(".jss_filter label").append(text);
        $(".jss_filter").prepend("<label>" + text2 + "</label>");
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

export function addChart(object) {
    object.innerHTML = "<canvas></canvas>";
    const chart = new Chart(object.firstChild, {
        type: 'line',
        data: {
                labels: ['A', 'B', 'C'],
                datasets: [
                    {
                    label: 'Dataset 1',
                    data: [1, 2, 3],
                    borderColor: '#000000',
                    backgroundColor: '#000000',
                    },
                    {
                    label: 'Dataset 2',
                    data: [2, 3, 4],
                    borderColor: '#FF6384',
                    backgroundColor: '#FFB1C1',
                    }
                ]
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

export function addChartRadar(object) {
    object.innerHTML = "<canvas></canvas>";
    const chart = new Chart(object.firstChild, {
        type: 'radar',
        data: {
                labels: [
                    'Eating',
                    'Drinking',
                    'Sleeping',
                    'Designing',
                    'Coding',
                    'Cycling',
                    'Running'
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [65, 59, 90, 81, 56, 55, 40],
                    fill: true,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgb(255, 99, 132)',
                    pointBackgroundColor: 'rgb(255, 99, 132)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(255, 99, 132)'
                }, {
                    label: 'My Second Dataset',
                    data: [28, 48, 40, 19, 96, 27, 100],
                    fill: true,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgb(54, 162, 235)',
                    pointBackgroundColor: 'rgb(54, 162, 235)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(54, 162, 235)'
                }]
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

export function addChartP(object) {
    object.innerHTML = "<canvas></canvas>";
    const chart = new Chart(object.firstChild, {
        type: 'polarArea',
        data: {
                labels: [
                    'Red',
                    'Green',
                    'Yellow',
                    'Grey',
                    'Blue'
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [11, 16, 7, 3, 14],
                    backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(75, 192, 192)',
                    'rgb(255, 205, 86)',
                    'rgb(201, 203, 207)',
                    'rgb(54, 162, 235)'
                    ]
                }]
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

export function addChartC(object) {
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
