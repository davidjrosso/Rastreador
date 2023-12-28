function ValidarCategoria(){
	var Codigo = document.getElementById("Codigo").value;
	var Categoria = document.getElementById("Categoria").value;
	var Bandera = true;
	var Mensaje = "";

	if(Codigo == "" || Codigo == null){
		Mensaje += "Debe ingresar un Código.";
		Bandera = false;
	}

	if(Categoria == "" || Categoria == null){
		Mensaje += " Debe ingresar una Categoría.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}
}