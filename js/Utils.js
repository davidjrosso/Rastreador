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
let checkInputDesde = false; 
let checkInputHasta = false; 


$(function (e) {
    $("#fecha-desde-inicial").on("click", function (e) {
        let date = new Date();
        if (!checkInputDesde) {
            $("#Fecha_Desde").val("");
            $("#inicial-movimiento-check").prop("value", "1");
            checkInputDesde = true;            
        } else {
            date.setFullYear(date.getFullYear() - 1);
            $("#Fecha_Desde").val(date);
            $('input[name="Fecha_Desde"]').datepicker("setDate", '-1y');
            $("#inicial-movimiento-check").prop("value", null);
            checkInputDesde = false;
        }
    });
   $("#fecha-hasta-inicial").on("click", function (e) {
        if (!checkInputHasta) {
            $("#Fecha_Hasta").val("");
            $("#fin-movimiento-check").prop("value", "1");            
            checkInputHasta = true;
        } else {
             $("#Fecha_Hasta").val(date);
            $('input[name="Fecha_Hasta"]').datepicker("setDate");
            $("#fin-movimiento-check").prop("value", null);            

            checkInputHasta = false;
        }
    });
});

function mostrar() {

    $("#expandir").css("display", "none");
    $("#ContenidoTabla").removeClass("div--padding-left-menu-active");
    $("#ContenidoMenu").css("display", "block");
    $("#cerrar").css("display", "inline-block");
}

function ocultar() {
    $("#expandir").attr("hidden", false);
    $("#ContenidoTabla").addClass("div--padding-left-menu-active");
    $("#ContenidoMenu").css("display", "none");
    $("#expandir").css("display", "block");
    $("#cerrar").css("display", "none");
}

function sendToRepL(datos) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "/view_vermovlistados.php";
    form.style.display = 'none';

    for (const key in datos) {
        if (Object.prototype.hasOwnProperty.call(datos, key)) {
            if (datos[key] instanceof Array) {
                datos[key].forEach(function (e) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key + "[]";
                    input.value = e;
                    form.appendChild(input);
                })
            } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = datos[key];
                form.appendChild(input);
            }
        }
    }

    document.body.appendChild(form);
    form.submit(); // Submit the form to initiate the navigation
}

function sendToNewMovimiento(datos) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "/view_newmovimientos.php";
    form.style.display = 'none';

    for (const key in datos) {
        if (Object.prototype.hasOwnProperty.call(datos, key)) {
            if (datos[key] instanceof Array) {
            datos[key].forEach(function (e) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key + "[]";
                input.value = e;
                form.appendChild(input);
            })
            } else {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = datos[key];
            form.appendChild(input);
            }
        }
    }

    document.body.appendChild(form);
    form.submit(); 
} 
