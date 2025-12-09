import jspreadsheet from "../node_modules/jspreadsheet-ce";
import ExcelJS from "../node_modules/exceljs";


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
        jspreadsheet(document.getElementById('excel_rev'), {
            worksheets: [{
                data: elems,
                columns: list,
                filters: true,
                allowComments:true,
                search: true,
                worksheetName: "Excel"
                }],
                search: true,
                toolbar:true,
                tabs: true,
                onbeforecreateworksheet: function(config, index) {
                    return {
                        minDimensions: [5, 5],
                        worksheetName: 'excel  ' + index
                    }
                }
        });
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
