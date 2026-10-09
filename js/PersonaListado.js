import $ from "jquery";

$(function (e) {
    $("#bn-carga-personas").on("click", function (e) {
        let filtroId = $("#tabla-personas").attr("data-id-filtro");
        let valor = $("#tabla-personas").attr("data-filtro");
        let offset = $(this).attr("data-offset");
        let limit = $(this).attr("data-limit");
        $("#circle").addClass("loader");
        $(this).css("background-color", "#b2b9c0");
        let req = "offset=" + offset + "&limit=" + limit +
                   "&filtro_tipo=" + filtroId +
                   "&filtro=" + valor;
        let url = '/listado_personas_filtro_control';
        let request = $.ajax({
            type:"POST",
            url : url,
            async: true,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: req,
            success : listaPersonas.bind(this),
        });
    });

});

function listaPersonas(e) {
    e.lista_personas.forEach(element => {
        let apellido = element.apellido;
        let nombre = element.nombre;
        let legajo = element.nro_legajo ?? "";
        let idPersona = element.id_persona;
        let documento = element.documento;
        let row = $(`<tr>
                <td>` + apellido + `</td>
                <td>` + nombre + `</td>
                <td>` + documento + `</td>
                <td>` + legajo + `</td>
                <td>
                <a href = 'view_verpersonas.php?ID=` + idPersona + `'>
                    <img src='./images/icons/VerDatos.png' class = 'IconosAcciones'>
                </a>
                </td>
                <td>
                <a href = 'view_modpersonasphp?ID=` + idPersona + `'>
                    <img src='./images/icons/ModDatos.png' class = 'IconosAcciones'>
                </a>
                </td>
                <td>
                <a onClick = 'Verificar(` + idPersona + `)'>
                    <img src='./images/icons/DelDatos.png' class = 'IconosAcciones'>
                </a>
                </td>
            </tr>`);
        $("#tabla-personas tbody").append(row);
    });
    $("#circle").removeClass("loader");
    if (e.prox) {
        $(this).attr("data-offset", e.offset);
        $(this).css("background-color", "#6c757d");
    } else {
        $(this).removeClass("btn-secondary");
        $(this).addClass("btn-info");
        $(this).text("carga completa");
    }

}