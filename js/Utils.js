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
        let date = new Date();
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

    $("div[data-id-element]").on("click", function (e) {
      scrollAlInput($(this).attr("data-id-element"));
    });

    $("button[data-dismiss]").on("click", function (e) {
      $("div[data-id-element]").css("display", "none");
    });
});

function mostrar() {

    $("#expandir").css("display", "none");
    $("#ContenidoTabla").removeClass("div--padding-left-menu-active");
    $("#ContenidoTabla").addClass("col-md-10");
    $("#ContenidoTabla").removeClass("col-md-12");
    $("#BarraDeNavHTabla").removeAttr("style");
    $("#ContenidoMenu").css("display", "block");
    $("#tabla-responsive").css("position", "relative");
    $("#cerrar").css("display", "inline-block");
}

function ocultar() {
    $("#expandir").attr("hidden", false);
    $("#ContenidoTabla").addClass("div--padding-left-menu-active");
    $("#ContenidoTabla").removeClass("col-md-10");
    $("#ContenidoTabla").addClass("col-md-12");
    $("#ContenidoMenu").css("display", "none");
    $("#tabla-responsive").css("position", "static");
    $("#expandir").css("display", "block");
    $("#BarraDeNavHTabla").css("margin", "auto");
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
    form.submit();
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

function scrollAlInput(identificador) {
    const inputDestino = document.getElementById(identificador);
    inputDestino.scrollIntoView({ behavior: 'smooth', block: 'center' });
    setTimeout(() => inputDestino.focus(), 400); 
}
