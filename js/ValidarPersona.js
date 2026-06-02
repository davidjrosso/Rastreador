import Swal from 'sweetalert2';


$(function (e) {
	$("#form-persona").on("submit", ValidarPersona);
})

function ValidarPersona(e) {
	let apellido = $("#Apellido").prop("value");
	let nombre = $("#Nombre").prop("value");
	let Fecha_Nacimiento = $("#Fecha_Nacimiento").prop("value");
	let dni = $("#documento").prop("value");

	let division = Fecha_Nacimiento.split("/");
	let anios = division[0];
	let bandera = true;
	let Mensaje = "";
	let check = false;
	check = check || $("#opcion_m").prop("checked");
	check = check || $("#opcion_f").prop("checked");
	check = check || $("#opcion_x").prop("checked");

	let calle = $("#Calle").prop("value");
	let barrio = $("#ID_Barrio").prop("value");

	if (!check) {
		Mensaje = "Seleccione una opcion de sexo";
		bandera = false;
	}
	if (dni.length > 8 || dni.length < 7) {
		bandera = false;
		Mensaje = "El DNI tiene que tener 7 u 8 digitos"
	}

	/*
	if (!calle) {
		Mensaje = "Seleccione una calle";
		bandera = false;
	}

	if (barrio == "0") {
		Mensaje = "Seleccione un barrio";
		bandera = false;
	}
	*/

	bandera = check && bandera;

	// if(Apellido == "" || Apellido == null){
	// 	Mensaje += "Debe ingresar un Apellido.";
	// 	Bandera = false;
	// }

	// if(Nombre == "" || Nombre == null){
	// 	Mensaje += " Debe ingresar un Nombre.";
	// 	Bandera = false;
	// }

	/*
	if(Anios.length == 2){
		Mensaje += " El formato de año no es correcto. Por favor ingrese el año completo.";
		Bandera = false;
	}
	*/

	/*
	if(Fecha_Nacimiento == "" || Fecha_Nacimiento == null){
		Mensaje += " Debe ingresar una Fecha.";
		Bandera = false;
	}

	if(ID_Barrio == "- Seleccione un Barrio -" || ID_Barrio == 0){
		Mensaje += " Debe seleccionar un Barrio.";
		Bandera = false;
	}

	if(ID_Escuela == "- Seleccione una Escuela -" || ID_Escuela == 0){
		Mensaje += " Debe seleccionar una Escuela.";
		Bandera = false;
	}
	*/

	if (!bandera) {
		e.preventDefault();
		Swal.fire(Mensaje,'','warning');
		return bandera;
	} else {
		return bandera;
	}

}

export function calcularEdad() {
	let fecha = document.getElementById("Fecha_Nacimiento").value;
	let cumpleanos = null;
	if (fecha !== null && fecha.length != 0) {
		fecha = fecha.split('/').reverse().join('-');
		cumpleanos = new Date(fecha + " GMT-0300");
	} else {
		cumpleanos = new Date();
	}

	let mes = cumpleanos.getMonth() + 1;
	let ano = cumpleanos.getFullYear();
	let dia = cumpleanos.getDate();

	let fecha_hoy = new Date();
	let ahora_ano = fecha_hoy.getYear();
	let ahora_mes = fecha_hoy.getMonth() + 1;
	let ahora_dia = fecha_hoy.getDate();

	let edad = (ahora_ano + 1900) - ano;
	if (ahora_mes < mes) {
		edad--;
	}

	if ((mes == ahora_mes) && (ahora_dia < dia)) {
		edad--;
	}

	if (edad > 1900) {
		edad -= 1900;
	}

	let meses = 0;

	if (ahora_mes > mes && dia > ahora_dia)
		meses = ahora_mes - mes - 1;
	else if (ahora_mes > mes)
		meses = ahora_mes - mes
	if (ahora_mes < mes && dia < ahora_dia)
		meses = 12 - (mes - ahora_mes);
	else if (ahora_mes < mes)
		meses = 12 - (mes - ahora_mes + 1);
	if (ahora_mes == mes && dia > ahora_dia)
		meses = 11;

	let Anios = document.getElementById("Edad");
	Anios.value = edad;

	let Meses = document.getElementById("Meses");
	Meses.value = meses;
}

function ValidarDocumento(){
	let Documento = document.getElementById("documento");
	let NroDocumento = Documento.value;
	if (NroDocumento.toString().length < 8){
		NotShowModalError();
		return true;
	}

	const DniNoRepetido = "<p>No hay ningún registro con ese nombre, documento o legajo</p>";
	xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		var contenidosRecibidos = xmlhttp.responseText;
		if(DniNoRepetido != contenidosRecibidos){ 
			Documento.value = "";
			swal({
				title: "El Documento ingresado "+ NroDocumento +" ya esta registrado",
				icon: "info",
				text: "Por favor ingrese un Documento diferente",
				confirmButtonText: 'OK'
			})
		}
		}
	}
	xmlhttp.open('POST', 'buscarPersonas.php?valorBusqueda='+NroDocumento, true); // Método post y url invocada
	xmlhttp.send();

}