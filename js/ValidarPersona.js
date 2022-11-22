function ValidarPersona(){
	var Apellido = document.getElementById("Apellido").value;
	var Nombre = document.getElementById("Nombre").value;
	var Fecha_Nacimiento = document.getElementById("Fecha_Nacimiento").value;

	var division = Fecha_Nacimiento.split("/");
	var Anios = division[0];


	/*
	var Fecha_Nacimiento = document.getElementById("Fecha_Nacimiento").value;
	var ID_Barrio = document.getElementById("ID_Barrio").value;
	var ID_Escuela = document.getElementById("ID_Escuela").value;
	*/
	var Bandera = true;
	var Mensaje = "";

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

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}

}