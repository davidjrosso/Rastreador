function ValidarMovFechas(){
	var Fecha_Desde = document.getElementById("Fecha_Desde").value;
	var Fecha_Hasta = document.getElementById("Fecha_Hasta").value;
	var Bandera = true;
	var Mensaje = "";

	if(Fecha_Desde == "" || Fecha_Desde == null){
		Mensaje += "Debe ingresar una Fecha desde.";
		Bandera = false;
	}

	if(Fecha_Hasta == "" || Fecha_Hasta == null){
		Mensaje += " Debe ingresar una Fecha hasta.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}
}