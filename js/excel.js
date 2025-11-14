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
                allowComments:true
                }],
                toolbar:[{
                        type: 'i',
                        content: 'undo',
                        onclick: function() {
                            table.undo();
                        }
                    },
                    {
                        type: 'i',
                        content: 'redo',
                        onclick: function() {
                            table.redo();
                        }
                    },
                    {
                        type: 'i',
                        content: 'save',
                        onclick: function () {
                            table.download();
                        }
                    },
                    {
                        type: 'select',
                        k: 'font-family',
                        v: ['Arial','Verdana']
                    },
                    {
                        type: 'select',
                        k: 'font-size',
                        v: ['9px','10px','11px','12px','13px','14px','15px','16px','17px','18px','19px','20px']
                    },
                    {
                        type: 'i',
                        content: 'format_align_left',
                        k: 'text-align',
                        v: 'left'
                    },
                    {
                        type:'i',
                        content:'format_align_center',
                        k:'text-align',
                        v:'center'
                    },
                    {
                        type: 'i',
                        content: 'format_align_right', 
                        k: 'text-align',
                        v: 'right'
                    },
                    {
                        type: 'i',
                        content: 'format_bold',
                        k: 'font-weight',
                        v: 'bold'
                    },
                    {
                        type: 'color',
                        content: 'format_color_text',
                        k: 'color'
                    },
                    {
                        type: 'color',
                        content: 'format_color_fill',
                        k: 'background-color'
                    },
                ]
        });
    }
    
}