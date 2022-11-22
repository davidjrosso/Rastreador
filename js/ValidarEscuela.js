function ValidarEscuela(){
	var ID_Nivel = document.getElementById("ID_Escuela").value;	
	var Bandera = true;
	var Mensaje = "";

	if(ID_Nivel == 0 || ID_Nivel == null){
		Mensaje += " Debe seleccionar un Nivel Escolar.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}

}