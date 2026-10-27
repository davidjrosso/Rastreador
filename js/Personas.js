
$(function (e) {
    $("#SearchPersonas_1").on("click", function (e) {
        buscarPersonas(1);
    });
    $("#SearchPersonas_2").on("click", function (e) {
        buscarPersonas(2);
    })
})

function buscarPersonas(id){
    let xNombre = null;
    let xmlhttp = null;
    let textoBusqueda = null;
    let contenidosRecibidos = null;
    let url = null;

    xNombre = (id) ? $('#SearchPersonas_' + id).val() : $('#SearchPersonas').val();
    textoBusqueda = xNombre;
    url = 'valorBusqueda=' + textoBusqueda;
    if (id) url += '&ID=' + id;

    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            contenidosRecibidos = xmlhttp.responseText;
            if (id) {
                $("#ResultadosPersonas_"  + id).html(contenidosRecibidos);
                $("button[data-id-persona]").on("click", function (e) {
                    let nombre = $(this).attr("data-nombre");
                    let idPersona = $(this).attr("data-id-persona");
                    seleccionPersona(nombre, idPersona, id);
                });
                
            } else {                
                $("#ResultadosPersonas").html(contenidosRecibidos);
            }
        }
    }
    xmlhttp.open('POST', 'buscar_personas', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send(url);
}

function seleccionPersona(xNombre, idPersona, idElement){
    var Persona = document.getElementById("Persona_" + idElement);
    var ID_Persona = document.getElementById("ID_Persona_" + idElement);
    Persona.innerHTML = "";
    Persona.innerHTML = "<p>" + xNombre + "</p>";
    ID_Persona.setAttribute('value', idPersona);
}