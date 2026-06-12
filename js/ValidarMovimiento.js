$(function (e) {
	$("#form-movimiento").on("submit", ValidarMovimiento);
});

function ValidarMovimiento(e) {
	//var Fecha = document.getElementById("datepicker").value;
	let ID_Persona = document.getElementById("ID_Persona").value;
	let ID_Motivo_1 = document.getElementById("ID_Motivo_1").value;
	let ID_Responsable = document.getElementById("ID_Responsable").value;
	let ID_salud = $("#ID_Centro").prop("value");
	let Mensaje = "";
	let Bandera = true;
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

	if(ID_salud == '-Seleccione un Centro de Salud-'){
		Mensaje += " Debe seleccionar un Centro de Salud.";	
		Bandera = false;
	}

	if(!Bandera){
		swal(Mensaje,'','warning');
		e.preventDefault();	
		return Bandera;	
	}else{
		return Bandera;
	}
	
}