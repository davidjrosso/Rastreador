function ValidarResponsable(){
	var Responsable = document.getElementById("Responsable").value;
	var Bandera = true;
	var Mensaje = "";

	if(Responsable == "" || Responsable == null){
		Mensaje += "Debe ingresar un Responsable.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}
}