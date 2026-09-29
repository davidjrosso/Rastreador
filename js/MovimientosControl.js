import $ from "jquery";

$(function (e) {
    $("#bn-carga-mv").on("click", function (e) {
        let filtroId = $("#tabla-mov").attr("data-id-filtro");
        let valor = $("#tabla-mov").attr("data-filtro");
        let offset = $(this).attr("data-offset");
        let limit = $(this).attr("data-limit");
        $("#circle").addClass("loader");
        $(this).css("background-color", "#e8a0a7");
        let req = "offset=" + offset + "&limit=" + limit +
                   "&filtro_tipo=" + filtroId +
                   "&filtro=" + valor;
        let url = '/listado_movimientos_filtro_control';
        let request = $.ajax({
            type:"POST",
            url : url,
            async: true,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: req,
            success : listaMovimientos.bind(this),
        });
    });

});

function listaMovimientos(e) {
    e.lista_mv.forEach(element => {
        let apellido = element.apellido;
        let nombre = element.nombre;
        let fechaCarga = element.fecha_creacion;
        let idMovimiento = element.id_movimiento;
        let responsable = element.responsable;
        let row = $(`<tr>
                <td>` + fechaCarga + `</td>
                <td>` + apellido + `</td>
                <td>` + nombre + `</td>
                <td>` + responsable + `</td>
                <td>
                <a href = 'view_vermovimientos.php?ID=` + idMovimiento + `'>
                    <img src='./images/icons/VerDatos.png' class = 'IconosAcciones'>
                </a>
                </td>
                <td>
                <a href = 'view_modmovimientos.php?ID=` + idMovimiento + `'>
                    <img src='./images/icons/ModDatos.png' class = 'IconosAcciones'>
                </a>
                </td>
                <td>
                <a onClick = 'Verificar(` + idMovimiento + `)'>
                    <img src='./images/icons/DelDatos.png' class = 'IconosAcciones'>
                </a>
                </td>
            </tr>`);
        $("#tabla-mov tbody").append(row);
    });
    $("#circle").removeClass("loader");
    if (e.prox) {
        $(this).attr("data-offset", e.offset);
        $(this).css("background-color", "#dc3545");
    } else {
        $(this).removeClass("btn-danger");
        $(this).addClass("btn-info");
        $(this).text("carga completa");
    }

}