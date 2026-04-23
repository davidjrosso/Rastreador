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