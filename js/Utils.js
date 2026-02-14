/*
function mostrar() {

    $("#expandir").removeAttr("style");
    document.getElementById("expandir").hidden = true;
    document.getElementById("ContenidoMenu").hidden = false;
    $("#ContenerdorPrincipal").removeAttr("style");

    var ContenidoMenu = document.getElementById("ContenidoMenu");
    ContenidoMenu.setAttribute("class", "col-md-2");
    document.getElementById("sidebar").style.width = "200px";

    var ContenidoTabla = document.getElementById("ContenidoTabla");
    ContenidoTabla.setAttribute("class", "col-md-10");

    document.getElementById("abrir").style.display = "none";
    document.getElementById("cerrar").style.display = "inline";
    $("#BarraDeNavHTabla").removeAttr("style");
}

function ocultar() {
    document.getElementById("expandir").hidden = false;
    document.getElementById("ContenidoMenu").hidden = true;
    $("#ContenerdorPrincipal").attr("style", "margin-left:5px;");
    var ContenidoTabla = document.getElementById("ContenidoTabla");
    ContenidoTabla.setAttribute("class", "col-md-12");

    $("#expandir").attr("style", "padding-left:1px");
    $("#abrir").attr("style", "display:inline;");
    //document.getElementById("abrir").style.display = "inline";
    document.getElementById("cerrar").style.display = "none";
    $("#BarraDeNavHTabla").attr("style", "width: 95%; margin-left: 2%;");
}
*/

function mostrar() {

    $("#expandir").css("display", "none");
    $("#ContenidoTabla").removeClass("div--padding-left-menu-active");
    $("#ContenidoMenu").css("display", "block");
    $("#cerrar").css("display", "inline");
}

function ocultar() {
    $("#expandir").attr("hidden", false);
    $("#ContenidoTabla").addClass("div--padding-left-menu-active");
    $("#ContenidoMenu").css("display", "none");
    $("#expandir").css("display", "block");
    $("#cerrar").css("display", "none");
}