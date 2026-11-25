import {Excel} from "./excel.js";

$(function (e) {
    let excel = new Excel();

    $("#excel").on("click", function (e) {
        if (excel) {
        excel.delete();
        }
        excel = new Excel();
        excel.init();
    });

    $("#excel_descarga").on("click", function (e) {

        let listConfigResult = listConfigResultados();
        for (let index = 2; index < 5; index++) {
            window.objectJsonTabla["header_movimientos_general"].splice(
                                window.objectJsonTabla["header_movimientos_general"].indexOf("Responsable" + index),
                                1);
        }
        excel.getMotivos(window.objectJsonTabla, listConfigResult);
    });

});