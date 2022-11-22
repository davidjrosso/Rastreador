function ValidarMovEdades(){
	var Familia = document.getElementById("Familia").value;
	var Manzana = document.getElementById("Manzana").value;
	var Lote = document.getElementById("Lote").value;;	

	if(Familia == "" || Familia == null){
		Mensaje += "Debe ingresar un Numero de Familia.";
		Bandera = false;
	}

	if(Manzana == "" || Manzana == null){
		Mensaje += " Debe ingresar una Manzana.";
		Bandera = false;
	}

	if(Lote == "" || Lote == null){
		Mensaje += " Debe ingresar un Lote.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}
}