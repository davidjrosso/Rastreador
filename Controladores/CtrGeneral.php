<?php  
require_once 'Conexion.php';

class CtrGeneral{
	//Instanciando la Conexion

	////////////////////////////////////////////////-MOVIMIENTOS-///////////////////////////////////////////////////
	public function getMovimientos(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_movimiento, M.fecha, P.apellido, P.nombre, R.responsable from movimiento M, persona P, responsable R where M.id_persona = P.id_persona and M.id_resp = R.id_resp and M.estado = 1 and P.estado = 1 order by M.id_movimiento desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Fecha</th><th>Apellido</th><th>Nombre</th><th>Resp.</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha"])));
			$Table .= "<tr><td>".$Ret["id_movimiento"]."</td><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_movimiento, M.fecha, P.apellido, P.nombre, R.responsable from movimiento M, persona P, responsable R where M.id_persona = P.id_persona and M.id_resp = R.id_resp and M.id_movimiento = $ID and M.estado = 1 and P.estado = 1 order by M.id_movimiento desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Fecha</th><th>Apellido</th><th>Nombre</th><th>Resp.</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha"])));
			$Table .= "<tr><td>".$Ret["id_movimiento"]."</td><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxFecha($Fecha){
		$Fecha = implode("-", array_reverse(explode("/",$Fecha)));
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_movimiento, M.fecha, P.apellido, P.nombre, R.responsable from movimiento M, persona P, responsable R where M.id_persona = P.id_persona and M.id_resp = R.id_resp and M.fecha = '$Fecha' and M.estado = 1 and P.estado = 1 order by M.id_movimiento desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Fecha</th><th>Apellido</th><th>Nombre</th><th>Resp.</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha"])));
			$Table .= "<tr><td>".$Ret["id_movimiento"]."</td><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxApellido($Apellido){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_movimiento, M.fecha, P.apellido, P.nombre, R.responsable from movimiento M, persona P, responsable R where M.id_persona = P.id_persona and M.id_resp = R.id_resp and P.apellido like '%$Apellido%' and M.estado = 1 and P.estado = 1 order by M.id_movimiento desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Fecha</th><th>Apellido</th><th>Nombre</th><th>Resp.</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha"])));
			$Table .= "<tr><td>".$Ret["id_movimiento"]."</td><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxNombre($Nombre){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_movimiento, M.fecha, P.apellido, P.nombre, R.responsable from movimiento M, persona P, responsable R where M.id_persona = P.id_persona and M.id_resp = R.id_resp and P.nombre like '%$Nombre%' and M.estado = 1 and P.estado = 1 order by M.id_movimiento desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Fecha</th><th>Apellido</th><th>Nombre</th><th>Resp.</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha"])));
			$Table .= "<tr><td>".$Ret["id_movimiento"]."</td><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxDocumento($Documento){		
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_movimiento, M.fecha, P.apellido, P.nombre, R.responsable from movimiento M, persona P, responsable R where M.id_persona = P.id_persona and M.id_resp = R.id_resp and P.documento like '%$Documento%' and M.estado = 1 and P.estado = 1 order by M.id_movimiento desc";
		$MessageError = "Problemas al intentar mostrar Movimientos ".$Consulta;
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Fecha</th><th>Apellido</th><th>Nombre</th><th>Resp.</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha"])));
			$Table .= "<tr><td>".$Ret["id_movimiento"]."</td><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxResponsable($Responsable){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_movimiento, M.fecha, P.apellido, P.nombre, R.responsable from movimiento M, persona P, responsable R where M.id_persona = P.id_persona and M.id_resp = R.id_resp and R.responsable like '%$Responsable%' and M.estado = 1 and P.estado = 1 order by M.id_movimiento desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Fecha</th><th>Apellido</th><th>Nombre</th><th>Resp.</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha"])));
			$Table .= "<tr><td>".$Ret["id_movimiento"]."</td><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	////////////////////////////////////////////////-PERSONAS-///////////////////////////////////////////////////

	public function getPersonas(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, apellido, nombre, documento from persona where estado = 1 order by apellido";
		$MessageError = "Problemas al intentar mostrar Personas";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Apellido</th><th>Nombre</th><th>Documento</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_persona"]."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getPersonasxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, apellido, nombre, documento from persona where id_persona = $ID and estado = 1 order by apellido";
		$MessageError = "Problemas al intentar mostrar Personas por ID";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Apellido</th><th>Nombre</th><th>Documento</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_persona"]."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getPersonasxApellido($Apellido){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, apellido, nombre, documento from persona where apellido like '%$Apellido%' and estado = 1 order by apellido";
		$MessageError = "Problemas al intentar mostrar Personas por Apellido";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Apellido</th><th>Nombre</th><th>Documento</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_persona"]."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getPersonasxNombre($Nombre){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, apellido, nombre, documento from persona where nombre like '%$Nombre%' and estado = 1 order by apellido";
		$MessageError = "Problemas al intentar mostrar Personas por Nombre";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Apellido</th><th>Nombre</th><th>Documento</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_persona"]."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getPersonasxDNI($DNI){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, apellido, nombre, documento from persona where documento like '%$DNI%' and estado = 1 order by apellido";
		$MessageError = "Problemas al intentar mostrar Personas por Documento";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Apellido</th><th>Nombre</th><th>Documento</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_persona"]."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	////////////////////////////////////////////////-MOTIVOS-///////////////////////////////////////////////////

	public function getMotivos(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_motivo, M.motivo, M.cod_categoria, M.codigo, C.categoria from motivo M, categoria C where M.cod_categoria = C.cod_categoria and M.estado = 1 and C.estado = 1 and M.id_motivo > 1 order by M.id_motivo";
		$MessageError = "Problemas al intentar mostrar Motivos";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Motivo</th><th>Codigo</th><th>Codigo Categoria</th><th>Categoria</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_motivo"]."</td><td>".$Ret["motivo"]."</td><td>".$Ret["codigo"]."</td><td>".$Ret["cod_categoria"]."</td><td>".$Ret["categoria"]."</td><td><a href = 'view_modmotivos.php?ID=".$Ret["id_motivo"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_motivo"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMotivosxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_motivo, M.motivo, M.cod_categoria, M.codigo, C.categoria from motivo M, categoria C where M.cod_categoria = C.cod_categoria and M.id_motivo = $ID and M.estado = 1 and C.estado = 1 and M.id_motivo > 1 order by M.id_motivo";
		$MessageError = "Problemas al intentar mostrar Motivos por ID";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Motivo</th><th>Codigo</th><th>Codigo Categoria</th><th>Categoria</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_motivo"]."</td><td>".$Ret["motivo"]."</td><td>".$Ret["codigo"]."</td><td>".$Ret["cod_categoria"]."</td><td>".$Ret["categoria"]."</td><td><a href = 'view_modmotivos.php?ID=".$Ret["id_motivo"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_motivo"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMotivosxMotivo($Motivo){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_motivo, M.motivo, M.cod_categoria, M.codigo, C.categoria from motivo M, categoria C where M.cod_categoria = C.cod_categoria and M.motivo like '%$Motivo%' and M.estado = 1 and C.estado = 1 and M.id_motivo > 1 order by M.id_motivo";
		$MessageError = "Problemas al intentar mostrar Motivos por Motivo";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Motivo</th><th>Codigo</th><th>Codigo Categoria</th><th>Categoria</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_motivo"]."</td><td>".$Ret["motivo"]."</td><td>".$Ret["codigo"]."</td><td>".$Ret["cod_categoria"]."</td><td>".$Ret["categoria"]."</td><td><a href = 'view_modmotivos.php?ID=".$Ret["id_motivo"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_motivo"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMotivosxCodigo($Codigo){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_motivo, M.motivo, M.cod_categoria, M.codigo, C.categoria from motivo M, categoria C where M.cod_categoria = C.cod_categoria and M.codigo like '%$Codigo%' and M.estado = 1 and C.estado = 1 and M.id_motivo > 1 order by M.id_motivo";
		$MessageError = "Problemas al intentar mostrar Motivos por Codigo";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Motivo</th><th>Codigo</th><th>Codigo Categoria</th><th>Categoria</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_motivo"]."</td><td>".$Ret["motivo"]."</td><td>".$Ret["codigo"]."</td><td>".$Ret["cod_categoria"]."</td><td>".$Ret["categoria"]."</td><td><a href = 'view_modmotivos.php?ID=".$Ret["id_motivo"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_motivo"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMotivosxNumero($Numero){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_motivo, M.motivo, M.cod_categoria, M.codigo, C.categoria from motivo M, categoria C where M.cod_categoria = C.cod_categoria and M.num_motivo like '%$Numero%' and M.estado = 1 and C.estado = 1 and M.id_motivo > 1 order by M.id_motivo";
		$MessageError = "Problemas al intentar mostrar Motivos por Numero";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Motivo</th><th>Codigo</th><th>Codigo Categoria</th><th>Categoria</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_motivo"]."</td><td>".$Ret["motivo"]."</td><td>".$Ret["codigo"]."</td><td>".$Ret["cod_categoria"]."</td><td>".$Ret["categoria"]."</td><td><a href = 'view_modmotivos.php?ID=".$Ret["id_motivo"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_motivo"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMotivosxCategoria($Categoria){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_motivo, M.motivo, M.cod_categoria, M.codigo, C.categoria from motivo M, categoria C where M.cod_categoria = C.cod_categoria and C.categoria like '%$Categoria%' and M.estado = 1 and C.estado = 1 and M.id_motivo > 1 order by M.id_motivo";
		$MessageError = "Problemas al intentar mostrar Motivos por Categoria";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Motivo</th><th>Codigo</th><th>Codigo Categoria</th><th>Categoria</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_motivo"]."</td><td>".$Ret["motivo"]."</td><td>".$Ret["codigo"]."</td><td>".$Ret["cod_categoria"]."</td><td>".$Ret["categoria"]."</td><td><a href = 'view_modmotivos.php?ID=".$Ret["id_motivo"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_motivo"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	////////////////////////////////////////////////-CATEGORIAS-///////////////////////////////////////////////////

	public function getCategorias(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select C.id_categoria, C.cod_categoria, C.categoria, F.Forma_Categoria, C.color from categoria C, formas_categorias F where C.ID_Forma = F.ID_Forma and C.estado = 1 order by C.id_categoria";
		$MessageError = "Problemas al intentar mostrar Categorias";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Codigo</th><th>Categoria</th><th>Forma</th><th>Color</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_categoria"]."</td><td>".$Ret["cod_categoria"]."</td><td>".$Ret["categoria"]."</td><td style='font-size: 40px; text-align: center; padding: 0; color: ".$Ret["color"]."'>".$Ret["Forma_Categoria"]."</td><td style= 'background-color: ".$Ret["color"]."; color: #fff;'></td><td><a href = 'view_vercategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modcategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_categoria"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getCategoriasxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select C.id_categoria, C.cod_categoria, C.categoria, F.Forma_Categoria, C.color from categoria C, formas_categorias F where C.ID_Forma = F.ID_Forma and C.id_categoria = $ID and C.estado = 1 order by C.id_categoria";
		$MessageError = "Problemas al intentar mostrar Categorias por ID";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Codigo</th><th>Categoria</th><th>Forma</th><th>Color</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_categoria"]."</td><td>".$Ret["cod_categoria"]."</td><td>".$Ret["categoria"]."</td><td style='font-size: 40px; text-align: center; padding: 0; color: ".$Ret["color"]."'>".$Ret["Forma_Categoria"]."</td><td style= 'background-color: ".$Ret["color"]."; color: #fff;'></td><td><a href = 'view_vercategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modcategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_categoria"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getCategoriasxCodigo($Codigo){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select C.id_categoria, C.cod_categoria, C.categoria, F.Forma_Categoria, C.color from categoria C, formas_categorias F where C.ID_Forma = F.ID_Forma and C.cod_categoria like '%$Codigo%' and C.estado = 1 order by C.id_categoria";
		$MessageError = "Problemas al intentar mostrar Categorias por Codigo";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Codigo</th><th>Categoria</th><th>Forma</th><th>Color</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_categoria"]."</td><td>".$Ret["cod_categoria"]."</td><td>".$Ret["categoria"]."</td><td style='font-size: 40px; text-align: center; padding: 0; color: ".$Ret["color"]."'>".$Ret["Forma_Categoria"]."</td><td style= 'background-color: ".$Ret["color"]."; color: #fff;'></td><td><a href = 'view_vercategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modcategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_categoria"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getCategoriasxCategoria($Categoria){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select C.id_categoria, C.cod_categoria, C.categoria, F.Forma_Categoria, C.color from categoria C, formas_categorias F where C.ID_Forma = F.ID_Forma and C.categoria like '%$Categoria%' and C.estado = 1 order by C.id_categoria";
		$MessageError = "Problemas al intentar mostrar Categorias por Categoria";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Codigo</th><th>Categoria</th><th>Forma</th><th>Color</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_categoria"]."</td><td>".$Ret["cod_categoria"]."</td><td>".$Ret["categoria"]."</td><td style='font-size: 40px; text-align: center; padding: 0; color: ".$Ret["color"]."'>".$Ret["Forma_Categoria"]."</td><td style= 'background-color: ".$Ret["color"]."; color: #fff;'></td><td><a href = 'view_vercategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modcategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_categoria"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	////////////////////////////////////////////////-RESPONSABLES-///////////////////////////////////////////////////

	public function getResponsables(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_resp, responsable from responsable where estado = 1 order by id_resp";
		$MessageError = "Problemas al intentar mostrar Responsables";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Responsable</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_resp"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_modresponsables.php?ID=".$Ret["id_resp"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_resp"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getResponsablesxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_resp, responsable from responsable where id_resp = $ID and estado = 1 order by id_resp";
		$MessageError = "Problemas al intentar mostrar Responsables por ID";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Responsable</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_resp"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_modresponsables.php?ID=".$Ret["id_resp"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_resp"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getResponsablesxResponsable($Responsable){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_resp, responsable from responsable where responsable like '%$Responsable%' and estado = 1 order by id_resp";
		$MessageError = "Problemas al intentar mostrar Responsables por Responsable";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Responsable</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_resp"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_modresponsables.php?ID=".$Ret["id_resp"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_resp"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	////////////////////////////////////////////////-CENTROS DE SALUD-///////////////////////////////////////////////////

	public function getCentros(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_centro, centro_salud from centros_salud where estado = 1 order by id_centro";
		$MessageError = "Problemas al intentar mostrar Centros de Salud";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Centro de Salud</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_centro"]."</td><td>".$Ret["centro_salud"]."</td><td><a href = 'view_modcentros.php?ID=".$Ret["id_centro"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_centro"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getCentrosxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_centro, centro_salud from centros_salud where id_centro = $ID and estado = 1 order by id_centro";
		$MessageError = "Problemas al intentar mostrar Centros de Salud por ID";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Centro de Salud</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_centro"]."</td><td>".$Ret["centro_salud"]."</td><td><a href = 'view_modcentros.php?ID=".$Ret["id_centro"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_centro"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getCentrosxCentro($Centro){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_centro, centro_salud from centros_salud where centro_salud like '%$Centro%' and estado = 1 order by id_centro";
		$MessageError = "Problemas al intentar mostrar Centros de Salud por Nombre de Centro";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Centro de Salud</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_centro"]."</td><td>".$Ret["centro_salud"]."</td><td><a href = 'view_modcentros.php?ID=".$Ret["id_centro"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_centro"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	////////////////////////////////////////////////-ESCUELAS-///////////////////////////////////////////////////

	public function getEscuelas(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select E.ID_Escuela, E.Escuela, E.Localidad, N.Nivel from escuelas E, nivel_escuelas N where E.ID_Nivel = N.ID_Nivel and E.Estado = 1 order by E.ID_Escuela";
		$MessageError = "Problemas al intentar mostrar Escuelas";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Escuela</th><th>Localidad</th><th>Nivel</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["ID_Escuela"]."</td><td>".$Ret["Escuela"]."</td><td>".$Ret["Localidad"]."</td><td>".$Ret["Nivel"]."</td><td><a href = 'view_modescuelas.php?ID=".$Ret["ID_Escuela"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["ID_Escuela"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getEscuelasxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select E.ID_Escuela, E.Escuela, E.Localidad, N.Nivel from escuelas E, nivel_escuelas N where E.ID_Nivel = N.ID_Nivel and E.ID_Escuela = $ID and E.Estado = 1 order by E.ID_Escuela";
		$MessageError = "Problemas al intentar mostrar Escuelas por ID";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Escuela</th><th>Localidad</th><th>Nivel</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["ID_Escuela"]."</td><td>".$Ret["Escuela"]."</td><td>".$Ret["Localidad"]."</td><td>".$Ret["Nivel"]."</td><td><a href = 'view_modescuelas.php?ID=".$Ret["ID_Escuela"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["ID_Escuela"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getEscuelasxEscuela($Escuela){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select E.ID_Escuela, E.Escuela, E.Localidad, N.Nivel from escuelas E, nivel_escuelas N where E.ID_Nivel = N.ID_Nivel and E.Escuela like '%$Escuela%' and E.Estado = 1 order by E.ID_Escuela";
		$MessageError = "Problemas al intentar mostrar Escuelas por Nombre de Escuela";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Escuela</th><th>Localidad</th><th>Nivel</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["ID_Escuela"]."</td><td>".$Ret["Escuela"]."</td><td>".$Ret["Localidad"]."</td><td>".$Ret["Nivel"]."</td><td><a href = 'view_modescuelas.php?ID=".$Ret["ID_Escuela"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["ID_Escuela"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	////////////////////////////////////////////////-USUARIOS-///////////////////////////////////////////////////

	public function getUsuarios(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select accountid, firstname, lastname, username, email from accounts where estado = 1 order by lastname";
		$MessageError = "Problemas al intentar mostrar Usuarios";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Apellido</th><th>Nombre</th><th>Nombre de Usuario</th><th>E-Mail</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["accountid"]."</td><td>".$Ret["lastname"]."</td><td>".$Ret["firstname"]."</td><td>".$Ret["username"]."</td><td>".$Ret["email"]."</td><td><a onClick='Verificar(".$Ret["accountid"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getUsuariosxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select accountid, firstname, lastname, username, email from accounts where accountid = $ID and estado = 1 order by lastname";
		$MessageError = "Problemas al intentar mostrar Usuarios por ID";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Apellido</th><th>Nombre</th><th>Nombre de Usuario</th><th>E-Mail</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["accountid"]."</td><td>".$Ret["lastname"]."</td><td>".$Ret["firstname"]."</td><td>".$Ret["username"]."</td><td>".$Ret["email"]."</td><td><a onClick='Verificar(".$Ret["accountid"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getUsuariosxUserName($xUserName){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select accountid, firstname, lastname, username, email from accounts where username like '%$xUserName%' and estado = 1 order by lastname";
		$MessageError = "Problemas al intentar mostrar Usuarios por UserName";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Apellido</th><th>Nombre</th><th>Nombre de Usuario</th><th>E-Mail</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["accountid"]."</td><td>".$Ret["lastname"]."</td><td>".$Ret["firstname"]."</td><td>".$Ret["username"]."</td><td>".$Ret["email"]."</td><td><a onClick='Verificar(".$Ret["accountid"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	////////////////////////////////////////////////-BARRIOS-///////////////////////////////////////////////////

	public function getBarrios(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_Barrio, Barrio from barrios where estado = 1 order by Barrio";
		$MessageError = "Problemas al intentar mostrar Barrios";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Barrio</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["ID_Barrio"]."</td><td>".$Ret["Barrio"]."</td><td><a href = 'view_modbarrios.php?ID=".$Ret["ID_Barrio"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_Barrio"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getBarriosxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_Barrio, Barrio from barrios where ID_Barrio = $ID and estado = 1 order by Barrio";
		$MessageError = "Problemas al intentar mostrar Barrios por ID";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Barrio</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["ID_Barrio"]."</td><td>".$Ret["Barrio"]."</td><td><a href = 'view_modbarrios.php?ID=".$Ret["ID_Barrio"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_Barrio"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getBarriosxBarrio($xBarrio){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_Barrio, Barrio from barrios where Barrio like '%$xBarrio%' and estado = 1 order by Barrio";
		$MessageError = "Problemas al intentar mostrar Barrios por Nombre";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Barrio</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["ID_Barrio"]."</td><td>".$Ret["Barrio"]."</td><td><a href = 'view_modbarrios.php?ID=".$Ret["ID_Barrio"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_Barrio"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}
	/////////////////////////////////////// OTRAS INSTITUCIONES ////////////////////////////////////////////////
	public function getOtrasInstituciones(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_OtraInstitucion, Nombre, Telefono, Mail from otras_instituciones where estado = 1 order by Nombre";
		$MessageError = "Problemas al intentar mostrar Instituciones";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Nombre</th><th>Telefono</th><th>E-Mail</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["ID_OtraInstitucion"]."</td><td>".$Ret["Nombre"]."</td><td>".$Ret["Telefono"]."</td><td>".$Ret["Mail"]."</td><td><a href = 'view_modotrasinstituciones.php?ID=".$Ret["ID_OtraInstitucion"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_OtraInstitucion"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getOtrasInstitucionesxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_OtraInstitucion, Nombre, Telefono, Mail from otras_instituciones where ID_OtraInstitucion = $ID and estado = 1 order by Nombre";
		$MessageError = "Problemas al intentar mostrar Instituciones";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Nombre</th><th>Telefono</th><th>E-Mail</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["ID_OtraInstitucion"]."</td><td>".$Ret["Nombre"]."</td><td>".$Ret["Telefono"]."</td><td>".$Ret["Mail"]."</td><td><a href = 'view_modotrasinstituciones.php?ID=".$Ret["ID_OtraInstitucion"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_OtraInstitucion"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getOtrasInstitucionesxNombre($Nombre){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_OtraInstitucion, Nombre, Telefono, Mail from otras_instituciones where Nombre like '%$Nombre%' and estado = 1 order by Nombre";
		$MessageError = "Problemas al intentar mostrar Instituciones";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Nombre</th><th>Telefono</th><th>E-Mail</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["ID_OtraInstitucion"]."</td><td>".$Ret["Nombre"]."</td><td>".$Ret["Telefono"]."</td><td>".$Ret["Mail"]."</td><td><a href = 'view_modotrasinstituciones.php?ID=".$Ret["ID_OtraInstitucion"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_OtraInstitucion"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getOtrasInstitucionesxTelefono($Telefono){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_OtraInstitucion, Nombre, Telefono, Mail from otras_instituciones where Telefono like '%$Telefono%' and estado = 1 order by Nombre";
		$MessageError = "Problemas al intentar mostrar Instituciones";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Nombre</th><th>Telefono</th><th>E-Mail</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["ID_OtraInstitucion"]."</td><td>".$Ret["Nombre"]."</td><td>".$Ret["Telefono"]."</td><td>".$Ret["Mail"]."</td><td><a href = 'view_modotrasinstituciones.php?ID=".$Ret["ID_OtraInstitucion"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_OtraInstitucion"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getOtrasInstitucionesxMail($Mail){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_OtraInstitucion, Nombre, Telefono, Mail from otras_instituciones where Mail like '%$Mail%' and estado = 1 order by Nombre";
		$MessageError = "Problemas al intentar mostrar Instituciones";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Nombre</th><th>Telefono</th><th>E-Mail</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["ID_OtraInstitucion"]."</td><td>".$Ret["Nombre"]."</td><td>".$Ret["Telefono"]."</td><td>".$Ret["Mail"]."</td><td><a href = 'view_modotrasinstituciones.php?ID=".$Ret["ID_OtraInstitucion"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_OtraInstitucion"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function getCantSolicitudes_Unificacion(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_Solicitud_Unificacion from solicitudes_unificacion where Estado = 1";
		$MessageError = "Problemas al intentar consultar cantidad de Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		$Con->CloseConexion();		
		return $Regis;
	}

	public function getSolicitudes_Unificacion(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select S.ID_Solicitud_Unificacion, S.Fecha, S.ID_Registro_1, S.ID_Registro_2, T.TipoUnif, U.username from solicitudes_unificacion S, accounts U, tipos_unif T where S.ID_Usuario = U.accountid and S.ID_TipoUnif = T.ID_TipoUnif and S.Estado = 1 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		if($Regis > 0){
			$Table = "<table class='table-responsive table-bordered'><thead><tr><th style='min-width:50px;'>Id</th><th style='min-width:100px;'>Fecha</th><th style='min-width:150px;'>Registro 1</th><th style='min-width:150px;'>Registro 2</th><th style='min-width:120px;'>Usuario</th><th style='min-width:100px;'>Tipo</th><th style='min-width:100px;'>Accion</th></tr></thead>";
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$ID_Registro_1 = $Ret["ID_Registro_1"];
				$ID_Registro_2 = $Ret["ID_Registro_2"];
				$ID_Solicitud = $Ret["ID_Solicitud_Unificacion"];
				switch($Ret["TipoUnif"]){
					case 'MOTIVO': 
						$ConsultarMotivo_1 = "select Motivo from motivo where ID_Motivo = $ID_Registro_1 and estado = 1 limit 1";
						$ConsultarMotivo_2 = "select Motivo from motivo where ID_Motivo = $ID_Registro_2 and estado = 1 limit 1";
						break;
					case 'PERSONAS': 
						$ConsultarMotivo_1 = "select Apellido, Nombre from persona where id_persona = $ID_Registro_1 and estado = 1 limit 1";
						$ConsultarMotivo_2 = "select Apellido, Nombre from persona where id_persona = $ID_Registro_2 and estado = 1 limit 1";
						break;
					case 'CENTROS SALUD': 
						$ConsultarMotivo_1 = "select centro_salud from centros_salud where id_centro = $ID_Registro_1 and estado = 1 limit 1";
						$ConsultarMotivo_2 = "select centro_salud from centros_salud where id_centro = $ID_Registro_2 and estado = 1 limit 1";
						break;	
					case 'ESCUELAS': 
						$ConsultarMotivo_1 = "select Codigo from escuelas where ID_Escuela = $ID_Registro_1 and Estado = 1 limit 1";
						$ConsultarMotivo_2 = "select Codigo from escuelas where ID_Escuela = $ID_Registro_2 and Estado = 1 limit 1";
						break;	
					case 'BARRIOS': 
						$ConsultarMotivo_1 = "select Barrio from barrios where ID_Barrio = $ID_Registro_1 and estado = 1 limit 1";
						$ConsultarMotivo_2 = "select Barrio from barrios where ID_Barrio = $ID_Registro_2 and estado = 1 limit 1";
						break;			
				}
				// $ConsultarMotivo_1 = "select Motivo from motivo where ID_Motivo = $ID_Registro_1 and estado = 1 limit 1";
				$MensajeErrorMotivo_1 = "No se pudo consultar el registro 1";
				// $ConsultarMotivo_2 = "select Motivo from motivo where ID_Motivo = $ID_Registro_2 and estado = 1 limit 1";
				$MensajeErrorMotivo_2 = "No se pudo consultar el registro 2";
				$EjecutarConsultarMotivo_1 = mysqli_query($Con->Conexion,$ConsultarMotivo_1) or die($MensajeErrorMotivo_1);
				$EjecutarConsultarMotivo_2 = mysqli_query($Con->Conexion,$ConsultarMotivo_2) or die($MensajeErrorMotivo_2);
				$RetMotivo_1 = mysqli_fetch_assoc($EjecutarConsultarMotivo_1);
				$RetMotivo_2 = mysqli_fetch_assoc($EjecutarConsultarMotivo_2);
				$TipoUnif = $Ret["TipoUnif"];
				switch($TipoUnif){
					case 'MOTIVO': $Table .= "<tr><td>".$Ret["ID_Solicitud_Unificacion"]."</td><td>".$Fecha."</td><td>".$RetMotivo_1["Motivo"]."</td><td>".$RetMotivo_2["Motivo"]."</td><td>".$Ret["username"]."</td><td>".$TipoUnif."</td><td><button class='btn btn-success' onClick=\"VerificarUnificacion(".$ID_Registro_1.",".$ID_Registro_2.",'".$TipoUnif."',".$ID_Solicitud.")\"><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarUnificacion(".$Ret["ID_Solicitud_Unificacion"].")'><i class='fa fa-times'></i></button></td></tr>";
						break;
					case 'PERSONAS': $Table .= "<tr><td>".$Ret["ID_Solicitud_Unificacion"]."</td><td>".$Fecha."</td><td>".$RetMotivo_1["Apellido"].", ".$RetMotivo_1["Nombre"]."</td><td>".$RetMotivo_2["Apellido"].", ".$RetMotivo_2["Nombre"]."</td><td>".$Ret["username"]."</td><td>".$TipoUnif."</td><td><button class='btn btn-success' onClick=\"VerificarUnificacion(".$ID_Registro_1.",".$ID_Registro_2.",'".$TipoUnif."',".$ID_Solicitud.")\"><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarUnificacion(".$Ret["ID_Solicitud_Unificacion"].")'><i class='fa fa-times'></i></button></td></tr>";
						break;
					case 'CENTROS SALUD': $Table .= "<tr><td>".$Ret["ID_Solicitud_Unificacion"]."</td><td>".$Fecha."</td><td>".$RetMotivo_1["centro_salud"]."</td><td>".$RetMotivo_2["centro_salud"]."</td><td>".$Ret["username"]."</td><td>".$TipoUnif."</td><td><button class='btn btn-success' onClick=\"VerificarUnificacion(".$ID_Registro_1.",".$ID_Registro_2.",'".$TipoUnif."',".$ID_Solicitud.")\"><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarUnificacion(".$Ret["ID_Solicitud_Unificacion"].")'><i class='fa fa-times'></i></button></td></tr>";
						break;	
					case 'ESCUELAS': $Table .= "<tr><td>".$Ret["ID_Solicitud_Unificacion"]."</td><td>".$Fecha."</td><td>".$RetMotivo_1["Codigo"]."</td><td>".$RetMotivo_2["Codigo"]."</td><td>".$Ret["username"]."</td><td>".$TipoUnif."</td><td><button class='btn btn-success' onClick=\"VerificarUnificacion(".$ID_Registro_1.",".$ID_Registro_2.",'".$TipoUnif."',".$ID_Solicitud.")\"><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarUnificacion(".$Ret["ID_Solicitud_Unificacion"].")'><i class='fa fa-times'></i></button></td></tr>";
						break;	
					case 'BARRIOS': $Table .= "<tr><td>".$Ret["ID_Solicitud_Unificacion"]."</td><td>".$Fecha."</td><td>".$RetMotivo_1["Barrio"]."</td><td>".$RetMotivo_2["Barrio"]."</td><td>".$Ret["username"]."</td><td>".$TipoUnif."</td><td><button class='btn btn-success' onClick=\"VerificarUnificacion(".$ID_Registro_1.",".$ID_Registro_2.",'".$TipoUnif."',".$ID_Solicitud.")\"><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarUnificacion(".$Ret["ID_Solicitud_Unificacion"].")'><i class='fa fa-times'></i></button></td></tr>";
						break;			
				}
				// $Table .= "<tr><td>".$Ret["ID_Solicitud_Unificacion"]."</td><td>".$Fecha."</td><td>".$RetMotivo_1["Motivo"]."</td><td>".$RetMotivo_2["Motivo"]."</td><td>".$Ret["username"]."</td><td>".$TipoUnif."</td><td><button class='btn btn-success' onClick=\"VerificarUnificacion(".$ID_Registro_1.",".$ID_Registro_2.",'".$TipoUnif."',".$ID_Solicitud.")\"><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarUnificacion(".$Ret["ID_Solicitud_Unificacion"].")'><i class='fa fa-times'></i></button></td></tr>";
			}			
			$Table .= "</table>";
		}else{
			$Table = "No existen solicitudes de unificación pendientes de aprobación.";
		}
		$Con->CloseConexion();
		
		return $Table;
	}

	public function getCantSolicitudes_Modificacion(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID from solicitudes_modificarmotivos where Estado = 1";
		$MessageError = "Problemas al intentar consultar cantidad de Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		$ConsultaCategoria = "select ID from solicitudes_modificarcategorias where Estado = 1";
		$MessageErrorCategoria = "Problemas al intentar consultar cantidad de Solicitudes";
		$cantCategoria = mysqli_query($Con->Conexion,$ConsultaCategoria) or die($MessageErrorCategoria);
		$RegisCategoria = mysqli_num_rows($cantCategoria);
		$Con->CloseConexion();		
		return $Regis+$RegisCategoria;
	}

	public function getSolicitudes_Modificacion(){
		$Con = new Conexion();
		$Con->OpenConexion();
		// CONSULTANDO MODIFICAR MOVITOS
		$Consulta = "select S.ID, S.Fecha, S.Motivo, S.Codigo, S.Cod_Categoria, S.Num_Motivo, U.username, S.ID_Motivo from solicitudes_modificarmotivos S, accounts U where S.ID_Usuario = U.accountid and S.Estado = 1 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		// CONSULTANDO MODIFICAR CATEGORIAS
		$ConsultaModCategoria = "select S.ID, S.Fecha, S.Codigo, S.Categoria, F.Forma_Categoria, S.ID_Forma, S.NuevoColor, U.username, C.id_categoria from solicitudes_modificarcategorias S, accounts U, formas_categorias F, categoria C where S.ID_Usuario = U.accountid and S.ID_Forma = F.ID_Forma and S.Codigo = C.cod_categoria and S.Estado = 1 order by S.Fecha";
		$MessageErrorCategoria = "Problemas al intentar mostrar Solicitudes";
		$EjecutarModCategoria = mysqli_query($Con->Conexion,$ConsultaModCategoria) or die($MessageErrorCategoria);
		$RegisCategoria = mysqli_num_rows($EjecutarModCategoria);

		if($Regis > 0){
			$Table = "<table class='table-responsive table-bordered'><thead><tr><th style='min-width:50px;'>Id</th><th style='min-width:100px;'>Fecha</th><th style='min-width:300px;'>Motivo</th><th style='min-width:100px;'>Codigo</th><th style='min-width:100px;'>Usuario</th><th style='min-width:100px;'>Accion</th></tr></thead>";
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Motivo = $Ret["Motivo"];
				$Codigo = $Ret["Codigo"];
				$Cod_Categoria = $Ret["Cod_Categoria"];
				$Num_Motivo = $Ret["Num_Motivo"];
				$Usuario = $Ret["username"];	
				$ID_Motivo = $Ret["ID_Motivo"];			
				$Table .= "<tr><td>".$ID."</td><td>".$Fecha."</td><td>".$Motivo."</td><td>".$Codigo."</td><td>".$Usuario."</td><td><button class='btn btn-success' onClick='VerificarModificarMotivo(".$ID.",\"".$Fecha."\",\"".$Motivo."\",\"".$Codigo."\",\"".$Cod_Categoria."\",".$Num_Motivo.",".$ID_Motivo.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarModificacion(".$Ret["ID"].")'><i class='fa fa-times'></i></button></td></tr>";
			}			
			$Table .= "</table>";
		}else{
			$Table = "No existen solicitudes de modificación de motivos pendientes de aprobación.";
		}

		if($RegisCategoria > 0){
			$Table2 = "<table class='table-responsive table-bordered'><thead><tr><th style='min-width:50px;'>Id</th><th style='min-width:100px;'>Fecha</th><th style='min-width:100px;'>Codigo</th><th style='min-width:200px;'>Categoria</th><th style='min-width:50px;'>Forma</th><th style='min-width:100px;'>Nuevo Color</th><th style='min-width:100px;'>Usuario</th><th style='min-width:100px;'>Accion</th></tr></thead>";
			while ($Ret = mysqli_fetch_array($EjecutarModCategoria)) {
				$ID = $Ret["ID"];
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Codigo = $Ret["Codigo"];
				$Categoria = $Ret["Categoria"];
				$ID_Forma = $Ret["ID_Forma"];
				$Forma = $Ret["Forma_Categoria"];
				$NuevoColor = $Ret["NuevoColor"];	
				$Usuario = $Ret["username"];
				$ID_Categoria = $Ret["id_categoria"];
				$Table2 .= "<tr><td>".$ID."</td><td>".$Fecha."</td><td>".$Codigo."</td><td>".$Categoria."</td><td style='color: ".$NuevoColor.";'>".$Forma."</td><td>".$NuevoColor."</td><td>".$Usuario."</td><td><button class='btn btn-success' onClick='VerificarModificarCategoria(".$ID.",\"".$Fecha."\",\"".$Codigo."\",\"".$Categoria."\",".$ID_Forma.",\"".$NuevoColor."\",".$ID_Categoria.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarModificacionCategoria(".$Ret["ID"].")'><i class='fa fa-times'></i></button></td></tr>";
			}			
			$Table2 .= "</table>";
		}else{
			$Table2 = "No existen solicitudes de modificación de categorias pendientes de aprobación.";
		}
		$Con->CloseConexion();
		
		return $Table."<br>".$Table2;
	}

	public function getCantSolicitudes_Eliminacion(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID from solicitudes_eliminarmotivos where Estado = 1";
		$MessageError = "Problemas al intentar consultar cantidad de Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		$Con->CloseConexion();		
		return $Regis;
	}

	public function getSolicitudes_Eliminacion(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select S.ID, S.Fecha, S.Motivo, S.Cod_Categoria, S.Num_Motivo, U.username, S.ID_Motivo from solicitudes_eliminarmotivos S, accounts U where S.ID_Usuario = U.accountid and S.Estado = 1 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		if($Regis > 0){
			$Table = "<table class='table-responsive table-bordered'><thead><tr><th style='min-width:50px;'>Id</th><th style='min-width:100px;'>Fecha</th><th style='min-width:300px;'>Motivo</th><th style='min-width:100px;'>Cod. Categoria</th><th style='min-width:100px;'>Usuario</th><th style='min-width:100px;'>Accion</th></tr></thead>";
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Motivo = $Ret["Motivo"];
				$Cod_Categoria = $Ret["Cod_Categoria"];
				$Num_Motivo = $Ret["Num_Motivo"];
				$Usuario = $Ret["username"];	
				$ID_Motivo = $Ret["ID_Motivo"];			
				$Table .= "<tr><td>".$ID."</td><td>".$Fecha."</td><td>".$Motivo."</td><td>".$Cod_Categoria."</td><td>".$Usuario."</td><td><button class='btn btn-success' onClick='VerificarEliminarMotivo(".$ID_Motivo.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarEliminacion(".$Ret["ID"].")'><i class='fa fa-times'></i></button></td></tr>";
			}			
			$Table .= "</table>";
		}else{
			$Table = "No existen solicitudes de unificación pendientes de aprobación.";
		}
		$Con->CloseConexion();
		
		return $Table;
	}

/*
 *
 * This file is part of Rastreador3.
 *
 * Rastreador3 is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Rastreador3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Rastreador3; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 */

}
?>