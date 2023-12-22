function ValidarMotivo(){
	var Motivo = document.getElementById("Motivo").value;
	var Codigo = document.getElementById("Codigo").value;
	var ID_Categoria = document.getElementById("ID_Categoria").value;
	var Bandera = true;
	var Mensaje = "";

	if(Motivo == "" || Motivo == null){
		Mensaje += "Debe ingresar un Motivo.";
		Bandera = false;
	}

	if(Codigo == "" || Codigo == null){
		Mensaje += "Debe ingresar un Código.";
		Bandera = false;
	}

	if(ID_Categoria == 0 || ID_Categoria == "-Seleccione una Categoria-"){
		Mensaje += " Debe seleccionar una Categoría.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}

}