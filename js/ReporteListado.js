$(function (e) {
    $("td[data-hc-persona]").on("click", function (e) {
        let id = $(this).attr("data-hc-persona");
        enviarAHistoriClinicaDePersona(id);
    });
    $("td[data-hc-persona]").on("mouseover", function (e) {
        let mensaje = $(`<div class="position-absolute item" style="z-index: 1100; width: max-content; left: 77%; top: -30%;">
                          <div class="toast dat-toast show" style="width:auto;" 
                               role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-body">
                                <span id="meses-hasta-dato">Historia Clinica Persona</span>
                            </div>
                          </div>
                        </div>`);
        $(this).append(mensaje);
    });

    $("td[data-hc-persona]").on("mouseout", function (e) {
        $(".item").remove();
    });

    $("td[data-hc-familia]").on("mouseover", function (e) {
        let mensaje = $(`<div class="position-absolute item" style="z-index: 1100; width: max-content; left: 77%; top: -30%;">
                          <div class="toast dat-toast show" style="width:auto;" 
                               role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-body">
                                <span>Historia Clinica Familiar</span>
                            </div>
                          </div>
                        </div>`);
        $(this).append(mensaje);
    });

    $("td[data-hc-familia]").on("mouseout", function (e) {
        $(".item").remove();
    });

    $("td[data-filtro]").on("mouseover", function (e) {
        let mensaje = $(`<div class="position-absolute item" style="z-index: 1100; width: max-content; left: 77%; top: -30%;">
                          <div class="toast dat-toast show" style="width:auto;" 
                               role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-body">
                                <span>Filtrado motivo</span>
                            </div>
                          </div>
                        </div>`);
        $(this).append(mensaje);
    });

    $("td[data-filtro]").on("mouseout", function (e) {
        $(".item").remove();
    });
})

function enviarAHistoriClinicaDePersona(idPersona) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "/view_vermovlistados.php";
    form.style.display = 'none';
    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = "ID_Persona";
    input.value = idPersona;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = "inicial-movimiento-check";
    input.value = 1;
    input.checked = true;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = "fin-movimiento-check";
    input.value = 1;
    input.checked = true;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'text';
    input.name = "Fecha_Desde";
    input.value = "01/10/2025";
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'text';
    input.name = "Fecha_Hasta";
    input.value = "01/10/2026";
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'text';
    input.name = "ID_Config";
    input.value = "table";
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = "fin-movimiento-check";
    input.value = 1;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'checkbox';
    input.name = "persona-historia-clinica";
    input.value = "true";
    input.checked = true;
    form.appendChild(input);

    document.body.appendChild(form);
    form.submit();
}