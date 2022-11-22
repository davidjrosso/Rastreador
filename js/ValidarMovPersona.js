function ValidarMovPersona(){
	var ID_Persona = document.getElementById("ID_Persona").value;
	var Bandera = true;
	var Mensaje = "";

	if(ID_Persona == 0){
		Mensaje += "Debe seleccionar una Persona.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}
}