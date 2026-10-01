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