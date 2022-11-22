function ValidarUnifMotivos(){
	var ID_Motivo_1 = document.getElementById("ID_Motivo_1").value;
	var ID_Motivo_2 = document.getElementById("ID_Motivo_2").value;
	var Bandera = true;
	var Mensaje = "";

	if(ID_Motivo_1 == 0){
		Mensaje = "Debe seleccionar un Primer Motivo.";
		Bandera = false;
	}

	if(ID_Motivo_2 == 0){
		Mensaje = "Debe seleccionar un Segundo Motivo.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}
}