function ValidarUnifPersonas(){
	var ID_Persona_1 = document.getElementById("ID_Persona_1").value;
	var ID_Persona_2 = document.getElementById("ID_Persona_2").value;
	var Bandera = true;
	var Mensaje = "";

	if(ID_Persona_1 == 0){
		Mensaje += "Debe seleccionar una Primera Persona.";
		Bandera = false;
	}

	if(ID_Persona_2 == 0){
		Mensaje += " Debe seleccionar una Segunda Persona.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}
}