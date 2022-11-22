function ValidarGeneral(){
	var Anio = document.getElementById("Anio").value;
	var Bandera = true;
	var Mensaje = "";

	if(Anio == "" || Anio == null){
		Mensaje += "Debe ingresar un Año.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}
}