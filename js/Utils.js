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

function calcularEdad() {
		let fecha = document.getElementById("Fecha_Nacimiento").value;
		if (fecha && fecha.length) {
			fecha = fecha.split('/').reverse().join('-');
		}
		let fecha_m = new Date();
		let cm = new Date(fecha + " GMT-0300");

		let yar_a = fecha_m.getFullYear();
		let yar = cm.getFullYear();

		let meses = fecha_m.getMonth() + 1;
		let meses_y = cm.getMonth() + 1;
		let mes = meses - meses_y;

		let dia = fecha_m.getDate();
		let dia_y = cm.getDate();
		let yar_g = yar_a - yar;
		

		if (yar <= yar_a) {
			
			if (yar_g == 0) {
				mes = meses - meses_y;

				if ( mes >= 1) {
					if (dia_y > dia ) 
					mes--;
				}

			} else {
				mes = meses - meses_y;
				if (mes <= -1) {
					mes = (meses_y - 12) + meses;
						if (dia_y > dia ) 
							mes--;
						yar_g--;	
					} else if ( mes >= 1) {
						if (dia_y > dia ) 
							mes--;
					} else {
						if (dia_y > dia ) {
							mes = 11;                            
							yar_g--;						
						}
							
				}
			}
			let yar_persona = document.getElementById("Edad");
			yar_persona.value = yar_g;

			let mes_persona = document.getElementById("Meses");                  
			mes_persona.value = mes;
		} else {
			let yar_persona = document.getElementById("Edad");
			yar_persona.value = 0;

			let mes_persona = document.getElementById("Meses");                  
			mes_persona.value = 0;
		}

}

function buscarMotivosGeneral(id_Motivo){
    let xMotivo = document.getElementById("SearchMotivos" + id_Motivo).value;
    let bodyJson = Object.fromEntries(listaMotivos);
    let textoBusqueda = xMotivo;
    let vs = $("#select-motivo" + id_Motivo)[0].value;
    xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
        contenidosRecibidos = xmlhttp.responseText;
        document.getElementById("ResultadosMotivos" + id_Motivo).innerHTML=contenidosRecibidos;
        }
    }
    xmlhttp.open('POST', 'buscarMotivos.php?valorBusqueda=' + textoBusqueda + '&number=' + id_Motivo + "&vs=" + vs, true); // Método post y url invocada
    xmlhttp.send(JSON.stringify(bodyJson));
}
