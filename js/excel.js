import jspreadsheet from "../node_modules/jspreadsheet-ce";

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
                search: true
                }],
                search: true,
                toolbar:true
        });
    }
    
}