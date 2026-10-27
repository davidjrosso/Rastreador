$(function (e) {
    $("#SearchBarrios_1").on("keyup", function (e) {
        buscarBarrios(1);
    });
    $("#SearchBarrios_2").on("keyup",  function (e) {
        buscarBarrios(2);
    });
});

function buscarBarrios(id) {
    var xBarrio = document.getElementById('SearchBarrios_' + id).value;
    var textoBusqueda = xBarrio;
    xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
        contenidosRecibidos = xmlhttp.responseText;
        document.getElementById("ResultadosBarrios_" + id).innerHTML = contenidosRecibidos;
        }
    }
    xmlhttp.open('POST', 'buscar_barrio', true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlhttp.send('valorBusqueda=' + textoBusqueda + '&id='  + id);
}

function seleccionBarrio_1(xBarrio, xID) {
    var Barrio = document.getElementById("Barrio_1");
    var ID_Barrio = document.getElementById("ID_Barrio_1");
    Barrio.innerHTML = "";
    Barrio.innerHTML = "<p>" + xBarrio + "</p>";
    ID_Barrio.setAttribute('value', xID);
}

function seleccionBarrio_2(xBarrio, xID) {
    var Barrio = document.getElementById("Barrio_2");
    var ID_Barrio = document.getElementById("ID_Barrio_2");
    Barrio.innerHTML = "";
    Barrio.innerHTML = "<p>" + xBarrio + "</p>";
    ID_Barrio.setAttribute('value', xID);
}