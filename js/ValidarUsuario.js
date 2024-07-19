function ValidarUsuario(){
	var Apellido = document.getElementById("Apellido").value;
	var Nombre = document.getElementById("Nombre").value;
	var UserName = document.getElementById("UserName").value;
	var UserPass = document.getElementById("UserPass").value;
	var ID_TipoUsuario = document.getElementById("ID_TipoUsuario").value;
	var Bandera = true;
	var Mensaje = "";

	if(Apellido == "" || Apellido == null){
		Mensaje += "Debe ingresar un Apellido.";
		Bandera = false;
	}

	if(Nombre == "" || Nombre == null){
		Mensaje += " Debe ingresar un Nombre.";
		Bandera = false;
	}

	if(UserName == "" || UserName == null){
		Mensaje += " Debe ingresar un Nombre de Usuario.";
		Bandera = false;
	}

	if(UserPass == "" || UserPass == null){
		Mensaje += " Debe ingresar un Password.";
		Bandera = false;
	}

	if(ID_TipoUsuario == 0 || ID_TipoUsuario == null){
		Mensaje += " Debe seleccionar un Tipo.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}

}




function ValidarModificacionUsuario(){
	var Apellido = document.getElementById("Apellido").value;
	var Nombre = document.getElementById("Nombre").value;
	var UserName = document.getElementById("UserName").value;
	var ID_TipoUsuario = document.getElementById("ID_TipoUsuario").value;
	var Bandera = true;
	var Mensaje = "";

	if(Apellido == "" || Apellido == null){
		Mensaje += "Debe ingresar un Apellido.";
		Bandera = false;
	}

	if(Nombre == "" || Nombre == null){
		Mensaje += " Debe ingresar un Nombre.";
		Bandera = false;
	}

	if(UserName == "" || UserName == null){
		Mensaje += " Debe ingresar un Nombre de Usuario.";
		Bandera = false;
	}

	if(ID_TipoUsuario == 0 || ID_TipoUsuario == null){
		Mensaje += " Debe seleccionar un Tipo.";
		Bandera = false;
	}

	if(Bandera == false){
		swal(Mensaje,'','warning');
		return Bandera;
	}else{
		return Bandera;
	}

}