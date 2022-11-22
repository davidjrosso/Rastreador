function ValidarMovimiento(){
	//var Fecha = document.getElementById("datepicker").value;
	var ID_Persona = document.getElementById("ID_Persona").value;
	var ID_Motivo_1 = document.getElementById("ID_Motivo_1").value;
	var ID_Responsable = document.getElementById("ID_Responsable").value;
	var Mensaje = "";
	var Bandera = true;
	/*if(Fecha == "" || Fecha == null){
		Mensaje += "Debe seleccionar una Fecha.";
		Bandera = false;
	}
	*/

	if(ID_Persona == 0){
		Mensaje += " Debe seleccionar una Persona.";	
		Bandera = false;
	}

	if(ID_Motivo_1 == 0){
		Mensaje += " Debe seleccionar un Motivo 1.";	
		Bandera = false;
	}

	if(ID_Responsable == '-Seleccione un Responsable-'){
		Mensaje += " Debe seleccionar un Responsable.";	
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;	
	}else{
		return Bandera;
	}
	
}