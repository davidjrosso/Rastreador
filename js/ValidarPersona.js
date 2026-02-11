function ValidarPersona() {
	let apellido = document.getElementById("Apellido").value;
	let nombre = document.getElementById("Nombre").value;
	let Fecha_Nacimiento = document.getElementById("Fecha_Nacimiento").value;

	let division = Fecha_Nacimiento.split("/");
	let anios = division[0];

	let opcion_f = $("#opcion_f").prop("checked");
	opcion_f = $("#opcion_m").prop("checked") || opcion_f;
	opcion_f = $("#opcion_x").prop("checked") || opcion_f;

	let calle = $("#Calle").prop("value");
	let barrio = $("#ID_Barrio").prop("value");
	/*
	var Fecha_Nacimiento = document.getElementById("Fecha_Nacimiento").value;
	var ID_Barrio = document.getElementById("ID_Barrio").value;
	var ID_Escuela = document.getElementById("ID_Escuela").value;
	*/
	let bandera = true;
	let Mensaje = "";

	if (!opcion_f) Mensaje = "Seleccione una opcion de sexo";

	if (!calle) {
		Mensaje = "Seleccione una calle";
		bandera = false;
	}

	if (barrio == "0") {
		Mensaje = "Seleccione un barrio";
		bandera = false;
	}


	bandera = opcion_f && bandera;

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

	if (bandera == false) {
		swal(Mensaje,'','warning');
		return bandera;
	} else {
		return bandera;
	}

}