function ValidarMovEdades(){
	var Edad_Desde = document.getElementById("Edad_Desde").value;
	var Edad_Hasta = document.getElementById("Edad_Hasta").value;
	var Bandera = true;
	var Mensaje = "";

	if(Edad_Desde == "" || Edad_Desde == null){
		Mensaje += "Debe ingresar una Edad Desde.";
		Bandera = false;
	}

	if(Edad_Hasta == "" || Edad_Hasta == null){
		Mensaje += " Debe ingresar una Edad Hasta.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}
}