<?php  
require_once("Conexion.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Accion.php");

class CtrGeneral{
	//Instanciando la Conexion

	////////////////////////////////////////////////-MOVIMIENTOS-///////////////////////////////////////////////////
	public function getMovimientos($TipoUsuario){
		$Con = new Conexion();
		$Con->OpenConexion();

		$consultaGeneral = "CREATE TEMPORARY TABLE GIN " ;
		$consultaUsuario = "CREATE TEMPORARY TABLE INN ";

		$consulta = "SELECT MT.id_motivo
					 FROM motivo MT,
					 	  categoria  C,
						  categorias_roles CS
					 WHERE C.cod_categoria = MT.cod_categoria
					   and MT.estado = 1
					   and C.estado = 1";

		$motivosVisiblesParaUsuario = $consultaUsuario . $consulta . " 
											and CS.id_categoria = C.id_categoria
											and CS.id_tipousuario = $TipoUsuario
											and CS.estado = 1";

		$motivosVisiblesParaTodoUsuario = $consultaGeneral . $consulta . "
								   and C.id_categoria NOT IN (SELECT id_categoria
								                              FROM categorias_roles CS)";

		$MessageError = "Problemas al crear la tabla temporaria de usuarios";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaUsuario
									   ) or die($MessageError);

		$MessageError = "Problemas al crear la tabla temporaria general";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaTodoUsuario
									   ) or die($MessageError);

		$Consulta = "select M.id_movimiento,
							M.fecha,
							M.fecha_creacion,
							P.apellido,
							P.nombre,
							R.responsable
							from movimiento M,
								persona P,
								responsable R
							where M.id_persona = P.id_persona
								and M.id_resp = R.id_resp
								and ((M.motivo_1 IN (SELECT * FROM INN) 
								   OR M.motivo_1 IN (SELECT * FROM GIN))
								  OR (M.motivo_2 IN (SELECT * FROM INN) 
								   OR M.motivo_2 IN (SELECT * FROM GIN))
								  OR (M.motivo_3 IN (SELECT * FROM INN) 
								   OR M.motivo_3 IN (SELECT * FROM GIN))
								  OR (M.motivo_4 IN (SELECT * FROM INN) 
								   OR M.motivo_4 IN (SELECT * FROM GIN))
								  OR (M.motivo_5 IN (SELECT * FROM INN) 
								   OR M.motivo_5 IN (SELECT * FROM GIN)))
								and M.estado = 1
								and P.estado = 1
							order by M.fecha_creacion desc;";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		/*$Consulta =  "select M.id_movimiento, 
									M.fecha, 
									M.fecha_creacion, 
									P.apellido, 
									P.nombre, 
									R.responsable
									from movimiento M, 
										persona P, 
										responsable R
									where M.id_persona = P.id_persona 
										and M.id_resp = R.id_resp
										and (M.motivo_1 IN  (SELECT MT.id_motivo
																FROM motivo MT,
																		categoria  C,
																		categorias_roles CS
																WHERE CS.id_categoria = C.id_categoria
																	and C.cod_categoria = MT.cod_categoria
																and CS.id_tipousuario = $TipoUsuario
																	and CS.estado = 1) 
											AND M.motivo_2 IN  (SELECT MT.id_motivo
																FROM motivo MT,
																		categoria  C,
																		categorias_roles CS
																WHERE CS.id_categoria = C.id_categoria
																	and C.cod_categoria = MT.cod_categoria
																and CS.id_tipousuario = $TipoUsuario
																	and CS.estado = 1)
											AND M.motivo_3 IN  (SELECT MT.id_motivo
																FROM motivo MT,
																		categoria  C,
																		categorias_roles CS
																WHERE CS.id_categoria = C.id_categoria
																	and C.cod_categoria = MT.cod_categoria
																and CS.id_tipousuario = $TipoUsuario
																	and CS.estado = 1)
											AND M.motivo_4 IN  (SELECT MT.id_motivo
																FROM motivo MT,
																		categoria  C,
																		categorias_roles CS
																WHERE CS.id_categoria = C.id_categoria
																	and C.cod_categoria = MT.cod_categoria
																and CS.id_tipousuario = $TipoUsuario
																	and CS.estado = 1)
											AND M.motivo_5 IN  (SELECT MT.id_motivo
																FROM motivo MT,
																		categoria  C,
																		categorias_roles CS
																WHERE CS.id_categoria = C.id_categoria
																	and C.cod_categoria = MT.cod_categoria
																and CS.id_tipousuario = $TipoUsuario
																	and CS.estado = 1))
										and M.estado = 1 
										and P.estado = 1  
									order by M.fecha_creacion desc";*/

		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th style='width:15%'>Fecha Carga</th><th>Apellido</th><th>Nombre</th><th>Resp.</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha_creacion"])));
			$Table .= "<tr><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxID($ID, $TipoUsuario){
		$Con = new Conexion();
		$Con->OpenConexion();
		$consultaGeneral = "CREATE TEMPORARY TABLE GIN " ;
		$consultaUsuario = "CREATE TEMPORARY TABLE INN ";

		$consulta = "SELECT MT.id_motivo
					 FROM motivo MT,
					 	  categoria  C,
						  categorias_roles CS
					 WHERE C.cod_categoria = MT.cod_categoria
					   and MT.estado = 1
					   and C.estado = 1";

		$motivosVisiblesParaUsuario = $consultaUsuario . $consulta . " 
											and CS.id_categoria = C.id_categoria
											and CS.id_tipousuario = $TipoUsuario
											and CS.estado = 1";

		$motivosVisiblesParaTodoUsuario = $consultaGeneral . $consulta . "
								   and C.id_categoria NOT IN (SELECT id_categoria
								                              FROM categorias_roles CS)";

		$MessageError = "Problemas al crear la tabla temporaria de usuarios";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaUsuario
									   ) or die($MessageError);

		$MessageError = "Problemas al crear la tabla temporaria general";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaTodoUsuario
									   ) or die($MessageError);

		$Consulta = "select M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable 
					 from movimiento M,
					 	  persona P,
						  responsable R,
						  categoria C,
						  categorias_roles CS,
						  motivo MT
					 where M.id_persona = P.id_persona
					   and M.id_resp = R.id_resp 
					   and M.id_movimiento = $ID
					   and CS.id_categoria = C.id_categoria
					   and C.cod_categoria = MT.cod_categoria
								and ((M.motivo_1 IN (SELECT * FROM INN) 
								   OR M.motivo_1 IN (SELECT * FROM GIN))
								  OR (M.motivo_2 IN (SELECT * FROM INN) 
								   OR M.motivo_2 IN (SELECT * FROM GIN))
								  OR (M.motivo_3 IN (SELECT * FROM INN) 
								   OR M.motivo_3 IN (SELECT * FROM GIN))
								  OR (M.motivo_4 IN (SELECT * FROM INN) 
								   OR M.motivo_4 IN (SELECT * FROM GIN))
								  OR (M.motivo_5 IN (SELECT * FROM INN) 
								   OR M.motivo_5 IN (SELECT * FROM GIN)))
					   and CS.id_tipousuario = $TipoUsuario
					   and M.estado = 1 
					   and P.estado = 1
					   and CS.estado = 1 
					 group by M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable
					 order by M.fecha_creacion desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th style='width:15%'>Fecha Carga</th><th>Apellido</th><th>Nombre</th><th>Resp.</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha_creacion"])));
			$Table .= "<tr><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxFecha($Fecha,$TipoUsuario){
		$Fecha = implode("-", array_reverse(explode("/",$Fecha)));
		$Con = new Conexion();
		$Con->OpenConexion();
		$consultaGeneral = "CREATE TEMPORARY TABLE GIN " ;
		$consultaUsuario = "CREATE TEMPORARY TABLE INN ";

		$consulta = "SELECT MT.id_motivo
					 FROM motivo MT,
					 	  categoria  C,
						  categorias_roles CS
					 WHERE C.cod_categoria = MT.cod_categoria
					   and MT.estado = 1
					   and C.estado = 1";

		$motivosVisiblesParaUsuario = $consultaUsuario . $consulta . " 
											and CS.id_categoria = C.id_categoria
											and CS.id_tipousuario = $TipoUsuario
											and CS.estado = 1";

		$motivosVisiblesParaTodoUsuario = $consultaGeneral . $consulta . "
								   and C.id_categoria NOT IN (SELECT id_categoria
								                              FROM categorias_roles CS)";

		$MessageError = "Problemas al crear la tabla temporaria de usuarios";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaUsuario
									   ) or die($MessageError);

		$MessageError = "Problemas al crear la tabla temporaria general";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaTodoUsuario
									   ) or die($MessageError);

		$Consulta = "select M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable 
					 from movimiento M, 
					 	  persona P, 
						  responsable R,
						  categoria C,
						  categorias_roles CS,
						  motivo MT
					 where M.id_persona = P.id_persona 
					   and M.id_resp = R.id_resp 
					   and M.fecha = '$Fecha'
					   and CS.id_categoria = C.id_categoria
					   and C.cod_categoria = MT.cod_categoria
								and ((M.motivo_1 IN (SELECT * FROM INN) 
								   OR M.motivo_1 IN (SELECT * FROM GIN))
								  OR (M.motivo_2 IN (SELECT * FROM INN) 
								   OR M.motivo_2 IN (SELECT * FROM GIN))
								  OR (M.motivo_3 IN (SELECT * FROM INN) 
								   OR M.motivo_3 IN (SELECT * FROM GIN))
								  OR (M.motivo_4 IN (SELECT * FROM INN) 
								   OR M.motivo_4 IN (SELECT * FROM GIN))
								  OR (M.motivo_5 IN (SELECT * FROM INN) 
								   OR M.motivo_5 IN (SELECT * FROM GIN)))
					   and CS.id_tipousuario = $TipoUsuario
					   and M.estado = 1
					   and P.estado = 1
					   and CS.estado = 1 
					group by M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable
					order M.fecha_creacion desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th style='width:15%'>Fecha Carga</th><th>Apellido</th><th>Nombre</th><th>Resp.</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha_creacion"])));
			$Table .= "<tr><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxApellido($Apellido, $TipoUsuario){
		$Con = new Conexion();
		$Con->OpenConexion();
		$consultaGeneral = "CREATE TEMPORARY TABLE GIN " ;
		$consultaUsuario = "CREATE TEMPORARY TABLE INN ";

		$consulta = "SELECT MT.id_motivo
					 FROM motivo MT,
					 	  categoria  C,
						  categorias_roles CS
					 WHERE C.cod_categoria = MT.cod_categoria
					   and MT.estado = 1
					   and C.estado = 1";

		$motivosVisiblesParaUsuario = $consultaUsuario . $consulta . " 
											and CS.id_categoria = C.id_categoria
											and CS.id_tipousuario = $TipoUsuario
											and CS.estado = 1";

		$motivosVisiblesParaTodoUsuario = $consultaGeneral . $consulta . "
								   and C.id_categoria NOT IN (SELECT id_categoria
								                              FROM categorias_roles CS)";

		$MessageError = "Problemas al crear la tabla temporaria de usuarios";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaUsuario
									   ) or die($MessageError);

		$MessageError = "Problemas al crear la tabla temporaria general";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaTodoUsuario
									   ) or die($MessageError);

		$Consulta = "select M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable 
					 from movimiento M, 
					 	  persona P, 
						  responsable R,
						  categoria C,
						  categorias_roles CS,
						  motivo MT
					 where M.id_persona = P.id_persona 
					   and M.id_resp = R.id_resp 
					   and P.apellido like '%$Apellido%'
					   and CS.id_categoria = C.id_categoria
					   and C.cod_categoria = MT.cod_categoria
								and ((M.motivo_1 IN (SELECT * FROM INN) 
								   OR M.motivo_1 IN (SELECT * FROM GIN))
								  OR (M.motivo_2 IN (SELECT * FROM INN) 
								   OR M.motivo_2 IN (SELECT * FROM GIN))
								  OR (M.motivo_3 IN (SELECT * FROM INN) 
								   OR M.motivo_3 IN (SELECT * FROM GIN))
								  OR (M.motivo_4 IN (SELECT * FROM INN) 
								   OR M.motivo_4 IN (SELECT * FROM GIN))
								  OR (M.motivo_5 IN (SELECT * FROM INN) 
								   OR M.motivo_5 IN (SELECT * FROM GIN)))
					   and CS.id_tipousuario = $TipoUsuario
					   and M.estado = 1 
					   and P.estado = 1
					   and CS.estado = 1
					group by M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable 
					order by M.fecha_creacion desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th style='width:15%'>Fecha Carga</th><th>Apellido</th><th>Nombre</th><th>Resp.</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha_creacion"])));
			$Table .= "<tr><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxNombre($Nombre, $TipoUsuario){
		$Con = new Conexion();
		$Con->OpenConexion();
		$consultaGeneral = "CREATE TEMPORARY TABLE GIN " ;
		$consultaUsuario = "CREATE TEMPORARY TABLE INN ";

		$consulta = "SELECT MT.id_motivo
					 FROM motivo MT,
					 	  categoria  C,
						  categorias_roles CS
					 WHERE C.cod_categoria = MT.cod_categoria
					   and MT.estado = 1
					   and C.estado = 1";

		$motivosVisiblesParaUsuario = $consultaUsuario . $consulta . " 
											and CS.id_categoria = C.id_categoria
											and CS.id_tipousuario = $TipoUsuario
											and CS.estado = 1";

		$motivosVisiblesParaTodoUsuario = $consultaGeneral . $consulta . "
								   and C.id_categoria NOT IN (SELECT id_categoria
								                              FROM categorias_roles CS)";

		$MessageError = "Problemas al crear la tabla temporaria de usuarios";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaUsuario
									   ) or die($MessageError);

		$MessageError = "Problemas al crear la tabla temporaria general";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaTodoUsuario
									   ) or die($MessageError);

		$Consulta = "select M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable 
					 from movimiento M, 
						  persona P, 
						  responsable R,
						  categoria C,
						  categorias_roles CS,
						  motivo MT
					  where M.id_persona = P.id_persona 
						and M.id_resp = R.id_resp 
						and P.nombre like '%$Nombre%'
					    and CS.id_categoria = C.id_categoria
						and C.cod_categoria = MT.cod_categoria
								and ((M.motivo_1 IN (SELECT * FROM INN) 
								   OR M.motivo_1 IN (SELECT * FROM GIN))
								  OR (M.motivo_2 IN (SELECT * FROM INN) 
								   OR M.motivo_2 IN (SELECT * FROM GIN))
								  OR (M.motivo_3 IN (SELECT * FROM INN) 
								   OR M.motivo_3 IN (SELECT * FROM GIN))
								  OR (M.motivo_4 IN (SELECT * FROM INN) 
								   OR M.motivo_4 IN (SELECT * FROM GIN))
								  OR (M.motivo_5 IN (SELECT * FROM INN) 
								   OR M.motivo_5 IN (SELECT * FROM GIN)))
					    and CS.id_tipousuario = $TipoUsuario
						and M.estado = 1 
						and P.estado = 1
					   and CS.estado = 1 
					  group by M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable
					  order by M.fecha_creacion desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th style='width:15%'>Fecha Carga</th><th>Apellido</th><th>Nombre</th><th>Resp.</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha_creacion"])));
			$Table .= "<tr><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxDocumento($Documento, $TipoUsuario){
		$Con = new Conexion();
		$Con->OpenConexion();
		$consultaGeneral = "CREATE TEMPORARY TABLE GIN " ;
		$consultaUsuario = "CREATE TEMPORARY TABLE INN ";

		$consulta = "SELECT MT.id_motivo
					 FROM motivo MT,
					 	  categoria  C,
						  categorias_roles CS
					 WHERE C.cod_categoria = MT.cod_categoria
					   and MT.estado = 1
					   and C.estado = 1";

		$motivosVisiblesParaUsuario = $consultaUsuario . $consulta . " 
											and CS.id_categoria = C.id_categoria
											and CS.id_tipousuario = $TipoUsuario
											and CS.estado = 1";

		$motivosVisiblesParaTodoUsuario = $consultaGeneral . $consulta . "
								   and C.id_categoria NOT IN (SELECT id_categoria
								                              FROM categorias_roles CS)";

		$MessageError = "Problemas al crear la tabla temporaria de usuarios";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaUsuario
									   ) or die($MessageError);

		$MessageError = "Problemas al crear la tabla temporaria general";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaTodoUsuario
									   ) or die($MessageError);

		$Consulta = "select M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable 
					 from movimiento M,
					 	  persona P,
						  responsable R,
						  categoria C,
						  categorias_roles CS,
						  motivo MT
					 where M.id_persona = P.id_persona 
					   and M.id_resp = R.id_resp 
					   and P.documento like '%$Documento%' 
					   and CS.id_categoria = C.id_categoria
					   and C.cod_categoria = MT.cod_categoria
								and ((M.motivo_1 IN (SELECT * FROM INN) 
								   OR M.motivo_1 IN (SELECT * FROM GIN))
								  OR (M.motivo_2 IN (SELECT * FROM INN) 
								   OR M.motivo_2 IN (SELECT * FROM GIN))
								  OR (M.motivo_3 IN (SELECT * FROM INN) 
								   OR M.motivo_3 IN (SELECT * FROM GIN))
								  OR (M.motivo_4 IN (SELECT * FROM INN) 
								   OR M.motivo_4 IN (SELECT * FROM GIN))
								  OR (M.motivo_5 IN (SELECT * FROM INN) 
								   OR M.motivo_5 IN (SELECT * FROM GIN)))
					   and CS.id_tipousuario = $TipoUsuario
					   and M.estado = 1 
					   and P.estado = 1
					   and CS.estado = 1
					 group by M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable
					 order by M.fecha_creacion desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th style='width:15%'>Fecha Carga</th><th>Apellido</th><th>Nombre</th><th>Resp.</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha_creacion"])));
			$Table .= "<tr><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxResponsable($Responsable, $TipoUsuario){
		$Con = new Conexion();
		$Con->OpenConexion();
		$consultaGeneral = "CREATE TEMPORARY TABLE GIN " ;
		$consultaUsuario = "CREATE TEMPORARY TABLE INN ";

		$consulta = "SELECT MT.id_motivo
					 FROM motivo MT,
					 	  categoria  C,
						  categorias_roles CS
					 WHERE C.cod_categoria = MT.cod_categoria
					   and MT.estado = 1
					   and C.estado = 1";

		$motivosVisiblesParaUsuario = $consultaUsuario . $consulta . " 
											and CS.id_categoria = C.id_categoria
											and CS.id_tipousuario = $TipoUsuario
											and CS.estado = 1";

		$motivosVisiblesParaTodoUsuario = $consultaGeneral . $consulta . "
								   and C.id_categoria NOT IN (SELECT id_categoria
								                              FROM categorias_roles CS)";

		$MessageError = "Problemas al crear la tabla temporaria de usuarios";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaUsuario
									   ) or die($MessageError);

		$MessageError = "Problemas al crear la tabla temporaria general";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaTodoUsuario
									   ) or die($MessageError);

		$Consulta = "select M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable 
					 from movimiento M, 
					 	  persona P, 
						  responsable R,
						  categoria C,
						  categorias_roles CS,
						  motivo MT
					 where M.id_persona = P.id_persona
					   and (M.id_resp = R.id_resp 
					   	 or M.id_resp_2 = R.id_resp 
						 or M.id_resp_3 = R.id_resp 
						 or M.id_resp_4 = R.id_resp) 
					   and R.responsable like '%$Responsable%'
					   and CS.id_categoria = C.id_categoria
					   and C.cod_categoria = MT.cod_categoria
								and ((M.motivo_1 IN (SELECT * FROM INN) 
								   OR M.motivo_1 IN (SELECT * FROM GIN))
								  OR (M.motivo_2 IN (SELECT * FROM INN) 
								   OR M.motivo_2 IN (SELECT * FROM GIN))
								  OR (M.motivo_3 IN (SELECT * FROM INN) 
								   OR M.motivo_3 IN (SELECT * FROM GIN))
								  OR (M.motivo_4 IN (SELECT * FROM INN) 
								   OR M.motivo_4 IN (SELECT * FROM GIN))
								  OR (M.motivo_5 IN (SELECT * FROM INN) 
								   OR M.motivo_5 IN (SELECT * FROM GIN)))
					   and CS.id_tipousuario = $TipoUsuario
					   and M.estado = 1 
					   and P.estado = 1
					   and CS.estado = 1  
					 order by M.fecha_creacion desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th style='width:15%'>Fecha Carga</th><th>Apellido</th><th>Nombre</th><th>Resp.</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha_creacion"])));
			$Table .= "<tr><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxLegajo($Legajo, $TipoUsuario){
		$Con = new Conexion();
		$Con->OpenConexion();
		$consultaGeneral = "CREATE TEMPORARY TABLE GIN " ;
		$consultaUsuario = "CREATE TEMPORARY TABLE INN ";

		$consulta = "SELECT MT.id_motivo
					 FROM motivo MT,
					 	  categoria  C,
						  categorias_roles CS
					 WHERE C.cod_categoria = MT.cod_categoria
					   and MT.estado = 1
					   and C.estado = 1";

		$motivosVisiblesParaUsuario = $consultaUsuario . $consulta . " 
											and CS.id_categoria = C.id_categoria
											and CS.id_tipousuario = $TipoUsuario
											and CS.estado = 1";

		$motivosVisiblesParaTodoUsuario = $consultaGeneral . $consulta . "
								   and C.id_categoria NOT IN (SELECT id_categoria
								                              FROM categorias_roles CS)";

		$MessageError = "Problemas al crear la tabla temporaria de usuarios";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaUsuario
									   ) or die($MessageError);

		$MessageError = "Problemas al crear la tabla temporaria general";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaTodoUsuario
									   ) or die($MessageError);

		$Consulta = "select M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable 
					 from movimiento M, 
					 	  persona P, 
						  responsable R,
						  categoria C,
						  categorias_roles CS,
						  motivo MT
					 where M.id_persona = P.id_persona 
					   and (M.id_resp = R.id_resp 
						 or M.id_resp_2 = R.id_resp 
						 or M.id_resp_3 = R.id_resp 
						 or M.id_resp_4 = R.id_resp) 
					   and P.nro_legajo = '$Legajo'
					   and CS.id_categoria = C.id_categoria
					   and C.cod_categoria = MT.cod_categoria
								and ((M.motivo_1 IN (SELECT * FROM INN) 
								   OR M.motivo_1 IN (SELECT * FROM GIN))
								  OR (M.motivo_2 IN (SELECT * FROM INN) 
								   OR M.motivo_2 IN (SELECT * FROM GIN))
								  OR (M.motivo_3 IN (SELECT * FROM INN) 
								   OR M.motivo_3 IN (SELECT * FROM GIN))
								  OR (M.motivo_4 IN (SELECT * FROM INN) 
								   OR M.motivo_4 IN (SELECT * FROM GIN))
								  OR (M.motivo_5 IN (SELECT * FROM INN) 
								   OR M.motivo_5 IN (SELECT * FROM GIN)))
					   and CS.id_tipousuario = $TipoUsuario
					   and M.estado = 1 
					   and P.estado = 1
					   and CS.estado = 1  
					 order by M.fecha_creacion desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th style='width:15%'>Fecha Carga</th><th>Apellido</th><th>Nombre</th><th>Resp.</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha_creacion"])));
			$Table .= "<tr><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMovimientosxCarpeta($Carpeta, $TipoUsuario){
		$Con = new Conexion();
		$Con->OpenConexion();
		$consultaGeneral = "CREATE TEMPORARY TABLE GIN " ;
		$consultaUsuario = "CREATE TEMPORARY TABLE INN ";

		$consulta = "SELECT MT.id_motivo
					 FROM motivo MT,
					 	  categoria  C,
						  categorias_roles CS
					 WHERE C.cod_categoria = MT.cod_categoria
					   and MT.estado = 1
					   and C.estado = 1";

		$motivosVisiblesParaUsuario = $consultaUsuario . $consulta . " 
											and CS.id_categoria = C.id_categoria
											and CS.id_tipousuario = $TipoUsuario
											and CS.estado = 1";

		$motivosVisiblesParaTodoUsuario = $consultaGeneral . $consulta . "
								   and C.id_categoria NOT IN (SELECT id_categoria
								                              FROM categorias_roles CS)";

		$MessageError = "Problemas al crear la tabla temporaria de usuarios";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaUsuario
									   ) or die($MessageError);

		$MessageError = "Problemas al crear la tabla temporaria general";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaTodoUsuario
									   ) or die($MessageError);

		$Consulta = "select M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable 
					 from movimiento M, 
					 	  persona P, 
						  responsable R,
						  categoria C,
						  categorias_roles CS,
						  motivo MT,
					 where M.id_persona = P.id_persona 
					   and (M.id_resp = R.id_resp 
					   	 or M.id_resp_2 = R.id_resp 
						 or M.id_resp_3 = R.id_resp 
						 or M.id_resp_4 = R.id_resp) 
					   and P.nro_carpeta = '$Carpeta'
					   and CS.id_categoria = C.id_categoria
					   and C.cod_categoria = MT.cod_categoria
								and ((M.motivo_1 IN (SELECT * FROM INN) 
								   OR M.motivo_1 IN (SELECT * FROM GIN))
								  OR (M.motivo_2 IN (SELECT * FROM INN) 
								   OR M.motivo_2 IN (SELECT * FROM GIN))
								  OR (M.motivo_3 IN (SELECT * FROM INN) 
								   OR M.motivo_3 IN (SELECT * FROM GIN))
								  OR (M.motivo_4 IN (SELECT * FROM INN) 
								   OR M.motivo_4 IN (SELECT * FROM GIN))
								  OR (M.motivo_5 IN (SELECT * FROM INN) 
								   OR M.motivo_5 IN (SELECT * FROM GIN)))
					   and CS.id_tipousuario = $TipoUsuario
					   and M.estado = 1 
					   and P.estado = 1
					   and CS.estado = 1  
					 order by M.fecha_creacion desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th style='width:15%'>Fecha Carga</th><th>Apellido</th><th>Nombre</th><th>Resp.</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha_creacion"])));
			$Table .= "<tr><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}



	////////////////////////////////////////////////-AUDITORIAS-///////////////////////////////////////////////////
	public function get_acciones($filtro, $value=null){
		switch ($filtro) {
			case 'usuario':
			  $acciones = Accion::get_acciones_user_id($value);
			  break;
			case 'tipo_accion':
			  $acciones = Accion::get_acciones_tipo($value);
			  break;
			default:
			  $acciones = Accion::get_acciones();
			  break;
		}

		$table = "<table class='table'>
					<thead>
						<tr>
							<th style='vertical-align: middle;'>Fecha</th>
							<th style='vertical-align: middle;'>Usuario</th>
							<th style='vertical-align: middle;'>Descripcion</th>
							<th style='vertical-align: middle;'>tipo de accion</th>
						</tr>
					</thead>
					<tbody>";
		foreach ($acciones as $row) {
			$fecha = (!empty($row["Fecha"])) ? $row["Fecha"] : null;
			$account_id = (!empty($row["accountid"])) ? $row["accountid"] : null;
			$detalles = (!empty($row["Detalles"])) ? $row["Detalles"] : null;
			$id_tipo_accion = (!empty($row["ID_TipoAccion"])) ? $row["ID_TipoAccion"] : null;
			$table .= "<tr><td>" . $fecha . "</td><td>" . $account_id . "</td><td>" . $detalles . "</td><td>" . $id_tipo_accion ."</td></tr>";
		}
		$table .= "</tbody></table>";
		return $table;
	}

/*
	public function get_acciones_fecha($Fecha,$TipoUsuario){
		$Fecha = implode("-", array_reverse(explode("/",$Fecha)));
		$Con = new Conexion();
		$Con->OpenConexion();
		$consultaGeneral = "CREATE TEMPORARY TABLE GIN " ;
		$consultaUsuario = "CREATE TEMPORARY TABLE INN ";

		$consulta = "SELECT MT.id_motivo
					 FROM motivo MT,
					 	  categoria  C,
						  categorias_roles CS
					 WHERE C.cod_categoria = MT.cod_categoria
					   and MT.estado = 1
					   and C.estado = 1";

		$motivosVisiblesParaUsuario = $consultaUsuario . $consulta . " 
											and CS.id_categoria = C.id_categoria
											and CS.id_tipousuario = $TipoUsuario
											and CS.estado = 1";

		$motivosVisiblesParaTodoUsuario = $consultaGeneral . $consulta . "
								   and C.id_categoria NOT IN (SELECT id_categoria
								                              FROM categorias_roles CS)";

		$MessageError = "Problemas al crear la tabla temporaria de usuarios";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaUsuario
									   ) or die($MessageError);

		$MessageError = "Problemas al crear la tabla temporaria general";
		$Con->ResultSet = mysqli_query(
									   $Con->Conexion,$motivosVisiblesParaTodoUsuario
									   ) or die($MessageError);

		$Consulta = "select M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable 
					 from movimiento M, 
					 	  persona P, 
						  responsable R,
						  categoria C,
						  categorias_roles CS,
						  motivo MT
					 where M.id_persona = P.id_persona 
					   and M.id_resp = R.id_resp 
					   and M.fecha = '$Fecha'
					   and CS.id_categoria = C.id_categoria
					   and C.cod_categoria = MT.cod_categoria
								and ((M.motivo_1 IN (SELECT * FROM INN) 
								   OR M.motivo_1 IN (SELECT * FROM GIN))
								  OR (M.motivo_2 IN (SELECT * FROM INN) 
								   OR M.motivo_2 IN (SELECT * FROM GIN))
								  OR (M.motivo_3 IN (SELECT * FROM INN) 
								   OR M.motivo_3 IN (SELECT * FROM GIN))
								  OR (M.motivo_4 IN (SELECT * FROM INN) 
								   OR M.motivo_4 IN (SELECT * FROM GIN))
								  OR (M.motivo_5 IN (SELECT * FROM INN) 
								   OR M.motivo_5 IN (SELECT * FROM GIN)))
					   and CS.id_tipousuario = $TipoUsuario
					   and M.estado = 1
					   and P.estado = 1
					   and CS.estado = 1 
					group by M.id_movimiento, M.fecha, M.fecha_creacion,P.apellido, P.nombre, R.responsable
					order M.fecha_creacion desc";
		$MessageError = "Problemas al intentar mostrar Movimientos";
		$Table = "<table class='table'><thead><tr><th style='width:15%'>Fecha Carga</th><th>Apellido</th><th>Nombre</th><th>Resp.</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Fecha = implode("/", array_reverse(explode("-",$Ret["fecha_creacion"])));
			$Table .= "<tr><td>".$Fecha."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["responsable"]."</td><td><a href = 'view_vermovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modmovimientos.php?ID=".$Ret["id_movimiento"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_movimiento"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}
	*/


	public function get_notificaciones($filtro, $value=null){
		switch ($filtro) {
			case 'usuario':
				$acciones = Accion::get_acciones_user_id($value);
			break;
			case 'tipo_accion':
				$acciones = Accion::get_acciones_tipo($value);
				break;
			default:
				$acciones = Accion::get_acciones();
			break;
		}

		$table = "<table class='table'>
					<thead>
						<tr>
							<th style='vertical-align: middle;'>Fecha</th>
							<th style='vertical-align: middle;'>Usuario</th>
							<th style='vertical-align: middle;'>Descripcion</th>
							<th style='vertical-align: middle;'>tipo de accion</th>
						</tr>
					</thead>
					<tbody>";
		foreach ($acciones as $row) {
			$fecha = (!empty($row["Fecha"])) ? $row["Fecha"] : null;
			$account_id = (!empty($row["accountid"])) ? $row["accountid"] : null;
			$detalles = (!empty($row["Detalles"])) ? $row["Detalles"] : null;
			$id_tipo_accion = (!empty($row["ID_TipoAccion"])) ? $row["ID_TipoAccion"] : null;
			$table .= "<tr><td>" . $fecha . "</td><td>" . $account_id . "</td><td>" . $detalles . "</td><td>" . $id_tipo_accion ."</td></tr>";
		}
		$table .= "</tbody></table>";
		return $table;
	}

	/*public function get_solicitudes($filtro, $value=null){
		switch ($filtro) {
			case 'caregoria':
				$acciones = Accion::get_categoria($value);
			break;
			case 'motivo':
				$acciones = Accion::get_motivo($value);
				break;
			case 'permiso':
				$acciones = Accion::get_permisos($value);
				break;
			case 'unificacion':
				$acciones = Accion::get_unificacion($value);
				break;
			default:
				$acciones = Accion::get_acciones();
			break;
		}

		$table = "<table class='table'>
					<thead>
						<tr>
							<th style='vertical-align: middle;'>Fecha</th>
							<th style='vertical-align: middle;'>Usuario</th>
							<th style='vertical-align: middle;'>Descripcion</th>
							<th style='vertical-align: middle;'>tipo de accion</th>
						</tr>
					</thead>
					<tbody>";
		foreach ($acciones as $row) {
			$fecha = (!empty($row["Fecha"])) ? $row["Fecha"] : null;
			$account_id = (!empty($row["accountid"])) ? $row["accountid"] : null;
			$detalles = (!empty($row["Detalles"])) ? $row["Detalles"] : null;
			$id_tipo_accion = (!empty($row["ID_TipoAccion"])) ? $row["ID_TipoAccion"] : null;
			$table .= "<tr><td>" . $fecha . "</td><td>" . $account_id . "</td><td>" . $detalles . "</td><td>" . $id_tipo_accion ."</td></tr>";
		}
		$table .= "</tbody></table>";
		return $table;
	}*/

	////////////////////////////////////////////////-PERSONAS-///////////////////////////////////////////////////

	public function getPersonas(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, 
					 CONCAT(UPPER(SUBSTRING(apellido,1,1)),LOWER(SUBSTRING(apellido,2))) as apellido, 
					 CONCAT(UPPER(SUBSTRING(nombre,1,1)),LOWER(SUBSTRING(nombre,2))) as nombre,
					 documento, 
					 IF(nro_legajo = 'null', '', nro_legajo) as nro_legajo 
					 from persona 
					 where estado = 1 
					 order by apellido, nombre";
		$MessageError = "Problemas al intentar mostrar Personas";
		$Table = "<table class='table'><thead><tr><th>Apellido</th><th>Nombre</th><th>Documento</th><th>Nro. Legajo</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td>".$Ret["nro_legajo"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		// $Table .= $Consulta;

		return $Table;
	}

	public function getPersonasxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, 
							CONCAT(UPPER(SUBSTRING(apellido,1,1)),LOWER(SUBSTRING(apellido,2))) as apellido, 
							CONCAT(UPPER(SUBSTRING(nombre,1,1)),LOWER(SUBSTRING(nombre,2))) as nombre, 
							documento, 
							IF(nro_legajo = 'null', '', nro_legajo) as nro_legajo
					 from persona
					 where id_persona = $ID 
					   and estado = 1 
					 order by apellido, nombre";
		$MessageError = "Problemas al intentar mostrar Personas por ID";
		$Table = "<table class='table'><thead><tr><th>Apellido</th><th>Nombre</th><th>Documento</th><th>Nro. Legajo</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td>".$Ret["nro_legajo"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getPersonasxApellido($Apellido){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, 
							CONCAT(UPPER(SUBSTRING(apellido,1,1)),LOWER(SUBSTRING(apellido,2))) as apellido, 
							CONCAT(UPPER(SUBSTRING(nombre,1,1)),LOWER(SUBSTRING(nombre,2))) as nombre, 
							documento, 
							IF(nro_legajo = 'null', '', nro_legajo) as nro_legajo
					 from persona
					 where apellido like '%$Apellido%' 
					   and estado = 1 
					 order by apellido, nombre";
		$MessageError = "Problemas al intentar mostrar Personas por Apellido";
		$Table = "<table class='table'><thead><tr><th>Apellido</th><th>Nombre</th><th>Documento</th><th>Nro. Legajo</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td>".$Ret["nro_legajo"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getPersonasxNombre($Nombre){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, 
							CONCAT(UPPER(SUBSTRING(apellido,1,1)),LOWER(SUBSTRING(apellido,2))) as apellido, 
							CONCAT(UPPER(SUBSTRING(nombre,1,1)),LOWER(SUBSTRING(nombre,2))) as nombre, 
							documento, 
							IF(nro_legajo = 'null', '', nro_legajo) as nro_legajo
					 from persona
					 where nombre like '%$Nombre%'
					   and estado = 1 
					 order by apellido, nombre";
		$MessageError = "Problemas al intentar mostrar Personas por Nombre";
		$Table = "<table class='table'><thead><tr><th>Apellido</th><th>Nombre</th><th>Documento</th><th>Nro. Legajo</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td>".$Ret["nro_legajo"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getPersonasxDNI($DNI){
		$buscDNI = trim(str_replace(array('.'),'',$DNI));
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, 
							CONCAT(UPPER(SUBSTRING(apellido,1,1)),LOWER(SUBSTRING(apellido,2))) as apellido, 
							CONCAT(UPPER(SUBSTRING(nombre,1,1)),LOWER(SUBSTRING(nombre,2))) as nombre, 
							documento, 
							IF(nro_legajo = 'null', '', nro_legajo) as nro_legajo 
					 from persona 
					 where documento like '%$buscDNI%' 
					   and estado = 1 
					 order by apellido, nombre";
		$MessageError = "Problemas al intentar mostrar Personas por Documento";
		$Table = "<table class='table'><thead><tr><th>Apellido</th><th>Nombre</th><th>Documento</th><th>Nro. Legajo</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td>".$Ret["nro_legajo"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getPersonasxLegajo($Legajo){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, 
							CONCAT(UPPER(SUBSTRING(apellido,1,1)),LOWER(SUBSTRING(apellido,2))) as apellido, 
							CONCAT(UPPER(SUBSTRING(nombre,1,1)),LOWER(SUBSTRING(nombre,2))) as nombre, 
							documento, 
							IF(nro_legajo = 'null', '', nro_legajo) as nro_legajo 
					 from persona 
					 where nro_legajo like '%$Legajo%' 
					   and estado = 1 
					 order by apellido, nombre";
		$MessageError = "Problemas al intentar mostrar Personas por Legajo";
		$Table = "<table class='table'><thead><tr><th>Apellido</th><th>Nombre</th><th>Documento</th></th><th>Nro. Legajo</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td>".$Ret["nro_legajo"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getPersonasxCarpeta($Carpeta){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, 
							CONCAT(UPPER(SUBSTRING(apellido,1,1)),LOWER(SUBSTRING(apellido,2))) as apellido, 
							CONCAT(UPPER(SUBSTRING(nombre,1,1)),LOWER(SUBSTRING(nombre,2))) as nombre,
							documento,
							IF(nro_legajo = 'null', '', nro_legajo) as nro_legajo 
					 from persona 
					 where nro_carpeta = '$Carpeta' 
					   and estado = 1 order by apellido, nombre";
		$MessageError = "Problemas al intentar mostrar Personas por Carpeta";
		$Table = "<table class='table'><thead><tr><th>Apellido</th><th>Nombre</th><th>Documento</th></th><th>Nro. Legajo</th><th colspan='3'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td>".$Ret["nro_legajo"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}
	

	public function getPersonasxDomicilio($Domicilio){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_persona, 
					 		CONCAT(UPPER(SUBSTRING(apellido,1,1)),LOWER(SUBSTRING(apellido,2))) as apellido, 
					 		CONCAT(UPPER(SUBSTRING(nombre,1,1)),LOWER(SUBSTRING(nombre,2))) as nombre, 
					 		documento, 
					 		IF(nro_legajo = 'null', '', nro_legajo) as nro_legajo,domicilio 
					 FROM persona 
					 WHERE domicilio LIKE '%$Domicilio%' 
					   AND estado = 1 
					 ORDER BY apellido, nombre";
		$MessageError = "Problemas al intentar mostrar Personas por Domicilio";
		$Table = "<table class='table'><thead><tr><th>Id</th><th>Apellido</th><th>Nombre</th><th>Documento</th></th><th>Nro. Legajo</th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["id_persona"]."</td><td>".$Ret["apellido"]."</td><td>".$Ret["nombre"]."</td><td>".$Ret["documento"]."</td><td>".$Ret["nro_legajo"]."</td><td><a href = 'view_verpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modpersonas.php?ID=".$Ret["id_persona"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_persona"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";
		return $Table;
	}
		
	////////////////////////////////////////////////-MOTIVOS-///////////////////////////////////////////////////

	public function getMotivos(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_motivo, M.motivo, M.codigo, C.categoria from motivo M, categoria C where M.cod_categoria = C.cod_categoria and M.estado = 1 and C.estado = 1 and M.id_motivo > 1 order by M.id_motivo";
		$MessageError = "Problemas al intentar mostrar Motivos";
		$Table = "<table class='table'><thead><tr><th>Motivo</th><th>Código</th><th>Categoría</th><th colspan='2'></th></tr></thead>";//<th>Número</th>  <td>".$Ret["num_motivo"]."</td>
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["motivo"]."</td><td>".$Ret["codigo"]."</td><td>".$Ret["categoria"]."</td><td><a href = 'view_modmotivos.php?ID=".$Ret["id_motivo"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_motivo"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMotivosxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_motivo, M.motivo, M.codigo, C.categoria from motivo M, categoria C where M.cod_categoria = C.cod_categoria and M.id_motivo = $ID and M.estado = 1 and C.estado = 1 and M.id_motivo > 1 order by M.id_motivo";
		$MessageError = "Problemas al intentar mostrar Motivos por ID";
		$Table = "<table class='table'><thead><tr><th>Motivo</th><th>Codigo</th><th>Categoría</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["motivo"]."</td><td>".$Ret["codigo"]."</td><td>".$Ret["categoria"]."</td><td><a href = 'view_modmotivos.php?ID=".$Ret["id_motivo"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_motivo"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMotivosxMotivo($Motivo){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_motivo, M.motivo, M.codigo, C.categoria from motivo M, categoria C where M.cod_categoria = C.cod_categoria and M.motivo like '%$Motivo%' and M.estado = 1 and C.estado = 1 and M.id_motivo > 1 order by M.id_motivo";
		$MessageError = "Problemas al intentar mostrar Motivos por Motivo";
		$Table = "<table class='table'><thead><tr><th>Motivo</th><th>Codigo</th><th>Categoría</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["motivo"]."</td><td>".$Ret["codigo"]."</td><td>".$Ret["categoria"]."</td><td><a href = 'view_modmotivos.php?ID=".$Ret["id_motivo"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_motivo"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMotivosxCodigo($Codigo){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_motivo, M.motivo, M.codigo, C.categoria from motivo M, categoria C where M.cod_categoria = C.cod_categoria and M.cod_categoria like '%$Codigo%' and M.estado = 1 and C.estado = 1 and M.id_motivo > 1 order by M.id_motivo";
		$MessageError = "Problemas al intentar mostrar Motivos por Codigo";
		$Table = "<table class='table'><thead><tr><th>Motivo</th><th>Codigo</th><th>Categoría</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["motivo"]."</td><td>".$Ret["codigo"]."</td><td>".$Ret["categoria"]."</td><td><a href = 'view_modmotivos.php?ID=".$Ret["id_motivo"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_motivo"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMotivosxNumero($Numero){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_motivo, M.motivo,  M.codigo, C.categoria  from motivo M, categoria C where M.cod_categoria = C.cod_categoria and M.num_motivo like '%$Numero%' and M.estado = 1 and C.estado = 1 and M.id_motivo > 1 order by M.id_motivo";
		$MessageError = "Problemas al intentar mostrar Motivos por Numero";
		$Table = "<table class='table'><thead><tr><th>Motivo</th><th>Codigo</th><th>Categoría</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["motivo"]."</td><td>".$Ret["codigo"]."</td><td>".$Ret["categoria"]."</td><td><a href = 'view_modmotivos.php?ID=".$Ret["id_motivo"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_motivo"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getMotivosxCategoria($Categoria){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select M.id_motivo, M.motivo,  M.codigo, C.categoria  from motivo M, categoria C where M.cod_categoria = C.cod_categoria and C.categoria like '%$Categoria%' and M.estado = 1 and C.estado = 1 and M.id_motivo > 1 order by M.id_motivo";
		$MessageError = "Problemas al intentar mostrar Motivos por Categoría";
		$Table = "<table class='table'><thead><tr><th>Motivo</th><th>Codigo</th><th>Categoría</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["motivo"]."</td><td>".$Ret["codigo"]."</td><td>".$Ret["categoria"]."</td><td><a href = 'view_modmotivos.php?ID=".$Ret["id_motivo"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_motivo"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Categoría</th><th>Forma</th><th>Color</th><th colspan='3'></th></tr></thead>";//<th>Codigo</th> <td>".$Ret["cod_categoria"]."</td>
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["categoria"]."</td><td style='font-size: 40px; text-align: center; padding: 0; color: ".$Ret["color"]."'>".$Ret["Forma_Categoria"]."</td><td style= 'background-color: ".$Ret["color"]."; color: #fff;'></td><td><a href = 'view_vercategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modcategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_categoria"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Categoría</th><th>Forma</th><th>Color</th><th colspan='3'></th></tr></thead>";//<th>Codigo</th>  <td>".$Ret["cod_categoria"]."</td>
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["categoria"]."</td><td style='font-size: 40px; text-align: center; padding: 0; color: ".$Ret["color"]."'>".$Ret["Forma_Categoria"]."</td><td style= 'background-color: ".$Ret["color"]."; color: #fff;'></td><td><a href = 'view_vercategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modcategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_categoria"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Categoría</th><th>Forma</th><th>Color</th><th colspan='3'></th></tr></thead>";//<th>Codigo</th>  <td>".$Ret["cod_categoria"]."</td>
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["categoria"]."</td><td style='font-size: 40px; text-align: center; padding: 0; color: ".$Ret["color"]."'>".$Ret["Forma_Categoria"]."</td><td style= 'background-color: ".$Ret["color"]."; color: #fff;'></td><td><a href = 'view_vercategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modcategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_categoria"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getCategoriasxCategoria($Categoria){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select C.id_categoria, C.cod_categoria, C.categoria, F.Forma_Categoria, C.color from categoria C, formas_categorias F where C.ID_Forma = F.ID_Forma and C.categoria like '%$Categoria%' and C.estado = 1 order by C.id_categoria";
		$MessageError = "Problemas al intentar mostrar Categorias por Categoría";
		$Table = "<table class='table'><thead><tr><th>Categoría</th><th>Forma</th><th>Color</th><th colspan='3'></th></tr></thead>";//<th>Codigo</th>  <td>".$Ret["cod_categoria"]."</td>
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["categoria"]."</td><td style='font-size: 40px; text-align: center; padding: 0; color: ".$Ret["color"]."'>".$Ret["Forma_Categoria"]."</td><td style= 'background-color: ".$Ret["color"]."; color: #fff;'></td><td><a href = 'view_vercategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/VerDatos.png' class = 'IconosAcciones'></a></td><td><a href = 'view_modcategorias.php?ID=".$Ret["id_categoria"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_categoria"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	////////////////////////////////////////////////-RESPONSABLES-///////////////////////////////////////////////////

	public function getResponsables(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_resp, responsable 
					 from responsable 
					 where estado = 1 
					   and id_resp <> 64
					 order by id_resp";
		$MessageError = "Problemas al intentar mostrar Responsables";
		$Table = "<table class='table'><thead><tr><th>Responsable</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["responsable"]."</td><td><a href = 'view_modresponsables.php?ID=".$Ret["id_resp"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_resp"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Responsable</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["responsable"]."</td><td><a href = 'view_modresponsables.php?ID=".$Ret["id_resp"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_resp"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Responsable</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["responsable"]."</td><td><a href = 'view_modresponsables.php?ID=".$Ret["id_resp"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_resp"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	////////////////////////////////////////////////-CENTROS DE SALUD-///////////////////////////////////////////////////

	public function getCentros(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_centro, centro_salud from centros_salud where estado = 1 and id_centro <> 7 order by id_centro";
		$MessageError = "Problemas al intentar mostrar Centros de Salud";
		$Table = "<table class='table'><thead><tr><th>Centro de Salud</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["centro_salud"]."</td><td><a href = 'view_modcentros.php?ID=".$Ret["id_centro"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_centro"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Centro de Salud</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["centro_salud"]."</td><td><a href = 'view_modcentros.php?ID=".$Ret["id_centro"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_centro"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Centro de Salud</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["centro_salud"]."</td><td><a href = 'view_modcentros.php?ID=".$Ret["id_centros"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["id_centro"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Escuela</th><th>Localidad</th><th>Nivel</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Escuela"]."</td><td>".$Ret["Localidad"]."</td><td>".$Ret["Nivel"]."</td><td><a href = 'view_modescuelas.php?ID=".$Ret["ID_Escuela"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["ID_Escuela"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Escuela</th><th>Localidad</th><th>Nivel</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Escuela"]."</td><td>".$Ret["Localidad"]."</td><td>".$Ret["Nivel"]."</td><td><a href = 'view_modescuelas.php?ID=".$Ret["ID_Escuela"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["ID_Escuela"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Escuela</th><th>Localidad</th><th>Nivel</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Escuela"]."</td><td>".$Ret["Localidad"]."</td><td>".$Ret["Nivel"]."</td><td><a href = 'view_modescuelas.php?ID=".$Ret["ID_Escuela"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick = 'Verificar(".$Ret["ID_Escuela"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'>
					<thead>
						<tr>
							<th>Id</th>
							<th>Apellido</th>
							<th>Nombre</th>
							<th>Nombre de Usuario</th>
							<th>E-Mail</th>
							<th></th>
							<th></th>
						</tr>
					</thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr>
							<td>".$Ret["accountid"]."</td>
							<td>".$Ret["lastname"]."</td>
							<td>".$Ret["firstname"]."</td>
							<td>".$Ret["username"]."</td>
							<td>".$Ret["email"]."</td>
							<td>
								<a  href = 'view_modusuario.php?account_id=".$Ret["accountid"]."'> 
									<img src='./images/icons/ModDatos.png' class = 'IconosAcciones'>
								</a>
							<td>
							<td><a onClick='Verificar(".$Ret["accountid"].")'>
								<img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a>
							</td>
						</tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getUsuariosxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Table = "";
		if(is_numeric($ID)){
			$Consulta = "select accountid, firstname, lastname, username, email from accounts where accountid = $ID and estado = 1 order by lastname";
			$MessageError = "Problemas al intentar mostrar Usuarios por ID";
			$Table = "<table class='table'>
						<thead>
							<tr>
								<th>Id</th>
								<th>Apellido</th>
								<th>Nombre</th>
								<th>Nombre de Usuario</th>
								<th>E-Mail</th>
								<th></th>
								<th></th>
							</tr>
						</thead>";
			$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$Table .= "<tr>
								<td>".$Ret["accountid"]."</td>
								<td>".$Ret["lastname"]."</td>
								<td>".$Ret["firstname"]."</td>
								<td>".$Ret["username"]."</td>
								<td>".$Ret["email"]."</td>
								<td>
								<a  href = 'view_modusuario.php?account_id=".$Ret["accountid"]."'>
										<img src='./images/icons/ModDatos.png' class = 'IconosAcciones'>
									</a>
								<td>
								<td>
									<a onClick='Verificar(".$Ret["accountid"].")'>
										<img src='./images/icons/DelDatos.png' class = 'IconosAcciones'>
									</a>
								</td>
							</tr>";
				$Table .= "</table>";
			}
		}
		$Con->CloseConexion();

		return $Table;
	}

	public function getUsuariosxUserName($xUserName){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select accountid, firstname, lastname, username, email from accounts where username like '%$xUserName%' and estado = 1 order by lastname";
		$MessageError = "Problemas al intentar mostrar Usuarios por UserName";
		$Table = "<table class='table'>
					<thead>
						<tr>
							<th>Id</th>
							<th>Apellido</th>
							<th>Nombre</th>
							<th>Nombre de Usuario</th>
							<th>E-Mail</th>
							<th></th>
							<th></th>
						</tr>
					</thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr>
							<td>".$Ret["accountid"]."</td>
							<td>".$Ret["lastname"]."</td>
							<td>".$Ret["firstname"]."</td>
							<td>".$Ret["username"]."</td>
							<td>".$Ret["email"]."</td>
							<td>
								<a  href = 'view_modusuario.php?account_id=".$Ret["accountid"]."'>
									<img src='./images/icons/ModDatos.png' class = 'IconosAcciones'>
								</a>
							<td>
							<td>
								<a onClick='Verificar(".$Ret["accountid"].")'>
									<img src='./images/icons/DelDatos.png' class = 'IconosAcciones'>
								</a>
							</td>
						</tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	////////////////////////////////////////////////-CALLES-///////////////////////////////////////////////////

	public function getCalles(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_calle, calle_nombre from calle where estado = 1 order by calle_nombre";
		$MessageError = "Problemas al intentar mostrar Calles";
		$Table = "<table class='table'>
					<thead>
						<tr>
							<th>Calle</th>
							<th colspan='2'></th>
						</tr>
					</thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr>
							<td>".$Ret["calle_nombre"]."</td>
							<td>
								<a href = 'view_modcalles.php?ID=".$Ret["ID_calle"]."'>
									<img src='./images/icons/ModDatos.png' class = 'IconosAcciones'>
								</a>
							</td>
							<td>
								<a onClick='Verificar(".$Ret["ID_calle"].")'>
									<img src='./images/icons/DelDatos.png' class = 'IconosAcciones'>
								</a>
							</td>
						</tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getCallesxID($ID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_calle, calle_nombre from calle where ID_Calle = $ID and estado = 1 order by calle_nombre";
		$MessageError = "Problemas al intentar mostrar Calle por ID";
		$Table = "<table class='table'><thead><tr><th>Calle</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["calle_nombre"]."</td><td><a href = 'view_modcalles.php?ID=".$Ret["ID_Calle"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_Calle"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}

	public function getCallesxCalle_nombre($xCalle_nombre){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_calle, calle_nombre 
					 from calle 
					 where calle_nombre like '%$xCalle_nombre%' 
					   and estado = 1 
					 order by calle_nombre";

		$MessageError = "Problemas al intentar mostrar Calles por Nombre";
		$Table = "<table class='table'><thead><tr><th>Calles</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["calle_nombre"]."</td><td><a href = 'view_modcalles.php?ID=".$Ret["ID_calle"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_calle"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Barrio</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Barrio"]."</td><td><a href = 'view_modbarrios.php?ID=".$Ret["ID_Barrio"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_Barrio"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Barrio</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Barrio"]."</td><td><a href = 'view_modbarrios.php?ID=".$Ret["ID_Barrio"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_Barrio"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Barrio</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Barrio"]."</td><td><a href = 'view_modbarrios.php?ID=".$Ret["ID_Barrio"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_Barrio"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
		}
		$Con->CloseConexion();
		$Table .= "</table>";

		return $Table;
	}
	/////////////////////////////////////// OTRAS INSTITUCIONES ////////////////////////////////////////////////
	public function getOtrasInstituciones(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_OtraInstitucion, Nombre, Telefono, Mail 
					 from otras_instituciones
					 where estado = 1
					   and ID_OtraInstitucion <> 1
					 order by Nombre";
		$MessageError = "Problemas al intentar mostrar Instituciones";
		$Table = "<table class='table'><thead><tr><th>Nombre</th><th>Telefono</th><th>E-Mail</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Nombre"]."</td><td>".$Ret["Telefono"]."</td><td>".$Ret["Mail"]."</td><td><a href = 'view_modotrasinstituciones.php?ID=".$Ret["ID_OtraInstitucion"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_OtraInstitucion"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Nombre</th><th>Telefono</th><th>E-Mail</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Nombre"]."</td><td>".$Ret["Telefono"]."</td><td>".$Ret["Mail"]."</td><td><a href = 'view_modotrasinstituciones.php?ID=".$Ret["ID_OtraInstitucion"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_OtraInstitucion"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Nombre</th><th>Telefono</th><th>E-Mail</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Nombre"]."</td><td>".$Ret["Telefono"]."</td><td>".$Ret["Mail"]."</td><td><a href = 'view_modotrasinstituciones.php?ID=".$Ret["ID_OtraInstitucion"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_OtraInstitucion"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Nombre</th><th>Telefono</th><th>E-Mail</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Nombre"]."</td><td>".$Ret["Telefono"]."</td><td>".$Ret["Mail"]."</td><td><a href = 'view_modotrasinstituciones.php?ID=".$Ret["ID_OtraInstitucion"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_OtraInstitucion"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Table = "<table class='table'><thead><tr><th>Nombre</th><th>Telefono</th><th>E-Mail</th><th colspan='2'></th></tr></thead>";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
			$Table .= "<tr><td>".$Ret["Nombre"]."</td><td>".$Ret["Telefono"]."</td><td>".$Ret["Mail"]."</td><td><a href = 'view_modotrasinstituciones.php?ID=".$Ret["ID_OtraInstitucion"]."'><img src='./images/icons/ModDatos.png' class = 'IconosAcciones'></a></td><td><a onClick='Verificar(".$Ret["ID_OtraInstitucion"].")'><img src='./images/icons/DelDatos.png' class = 'IconosAcciones'></a></td></tr>";
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
		$Consulta = "select S.ID_Solicitud_Unificacion, S.Fecha, S.ID_Registro_1, S.ID_Registro_2, T.TipoUnif, U.username, S.ID_TipoUnif from solicitudes_unificacion S, accounts U, tipos_unif T where S.ID_Usuario = U.accountid and S.ID_TipoUnif = T.ID_TipoUnif and S.Estado = 1 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		if($Regis > 0){
			$Table = "<table id='solicitudes-unificacion' class='table-responsive table-bordered'><thead><tr><th style='min-width:50px;'>Id</th><th style='min-width:100px;'>Fecha</th><th style='min-width:100px;'>Registro 1</th><th style='min-width:100px;'>Registro 2</th><th style='min-width:100px;'>Usuario</th><th style='min-width:100px;'>Tipo</th><th style='min-width:100px;'>Acción</th></tr></thead>";
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$ID_Registro_1 = $Ret["ID_Registro_1"];
				$ID_Registro_2 = $Ret["ID_Registro_2"];
				switch($Ret['ID_TipoUnif']){
					case 1: 
						$ConsultarMotivo_1 = "select Motivo from motivo where ID_Motivo = $ID_Registro_1 and estado = 1 limit 1";
						$MensajeErrorMotivo_1 = "No se pudo consultar el motivo 1";
						$ConsultarMotivo_2 = "select Motivo from motivo where ID_Motivo = $ID_Registro_2 and estado = 1 limit 1";
						$MensajeErrorMotivo_2 = "No se pudo consultar el motivo 2";					
					break;
					case 2: 
						$ConsultarMotivo_1 = "select apellido, nombre from persona where id_persona = $ID_Registro_1 and estado = 1 limit 1";
						$MensajeErrorMotivo_1 = "No se pudo consultar la persona 1";
						$ConsultarMotivo_2 = "select apellido, nombre from persona where id_persona = $ID_Registro_2 and estado = 1 limit 1";
						$MensajeErrorMotivo_2 = "No se pudo consultar la persona 2";					
					break;
					case 3: 
						$ConsultarMotivo_1 = "select centro_salud from centros_salud where id_centro = $ID_Registro_1 and estado = 1 limit 1";
						$MensajeErrorMotivo_1 = "No se pudo consultar el centro salud 1";
						$ConsultarMotivo_2 = "select centro_salud from centros_salud where id_centro = $ID_Registro_2 and estado = 1 limit 1";
						$MensajeErrorMotivo_2 = "No se pudo consultar el centro salud 2";					
					break;
					case 4: 
						$ConsultarMotivo_1 = "select Escuela from escuelas where ID_Escuela = $ID_Registro_1 and estado = 1 limit 1";
						$MensajeErrorMotivo_1 = "No se pudo consultar la escuela 1";
						$ConsultarMotivo_2 = "select Escuela from escuelas where ID_Escuela = $ID_Registro_2 and estado = 1 limit 1";
						$MensajeErrorMotivo_2 = "No se pudo consultar la escuela 2";					
					break;
					case 5: 
						$ConsultarMotivo_1 = "select Barrio from barrios where ID_Barrio = $ID_Registro_1 and estado = 1 limit 1";
						$MensajeErrorMotivo_1 = "No se pudo consultar el barrio 1";
						$ConsultarMotivo_2 = "select Barrio from barrios where ID_Barrio = $ID_Registro_2 and estado = 1 limit 1";
						$MensajeErrorMotivo_2 = "No se pudo consultar el barrio 2";					
					break;
					default: 
						$ConsultarMotivo_1 = "select Motivo from motivo where ID_Motivo = $ID_Registro_1 and estado = 1 limit 1";
						$MensajeErrorMotivo_1 = "No se pudo consultar el motivo 1";
						$ConsultarMotivo_2 = "select Motivo from motivo where ID_Motivo = $ID_Registro_2 and estado = 1 limit 1";
						$MensajeErrorMotivo_2 = "No se pudo consultar el motivo 2";	
					break;
				}
				$EjecutarConsultarMotivo_1 = mysqli_query($Con->Conexion,$ConsultarMotivo_1) or die($MensajeErrorMotivo_1);
				$EjecutarConsultarMotivo_2 = mysqli_query($Con->Conexion,$ConsultarMotivo_2) or die($MensajeErrorMotivo_2);
				$RetMotivo_1 = mysqli_fetch_assoc($EjecutarConsultarMotivo_1);
				$RetMotivo_2 = mysqli_fetch_assoc($EjecutarConsultarMotivo_2);
				$TipoUnif = $Ret["TipoUnif"];
				$ID_Solicitud = $Ret["ID_Solicitud_Unificacion"];
				switch($Ret['ID_TipoUnif']){
					case 1: 
						$Table .= "<tr><td>".$Ret["ID_Solicitud_Unificacion"]."</td><td>".$Fecha."</td><td>".$RetMotivo_1["Motivo"]."</td><td>".$RetMotivo_2["Motivo"]."</td><td>".$Ret["username"]."</td><td>".$TipoUnif."</td><td><button class='btn btn-success' onClick='VerificarUnificacion(".$ID_Registro_1.",".$ID_Registro_2.",\"".$TipoUnif."\",".$ID_Solicitud.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarUnificacion(".$Ret["ID_Solicitud_Unificacion"].")'><i class='fa fa-times'></i></button></td></tr>";				
					break;
					case 2: 
						$Table .= "<tr><td>".$Ret["ID_Solicitud_Unificacion"]."</td><td>".$Fecha."</td><td>".$RetMotivo_1["apellido"].", ".$RetMotivo_1["nombre"]."</td><td>".$RetMotivo_2["apellido"].", ".$RetMotivo_2["nombre"]."</td><td>".$Ret["username"]."</td><td>".$TipoUnif."</td><td><button class='btn btn-success' onClick='VerificarUnificacion(".$ID_Registro_1.",".$ID_Registro_2.",\"".$TipoUnif."\",".$ID_Solicitud.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarUnificacion(".$Ret["ID_Solicitud_Unificacion"].")'><i class='fa fa-times'></i></button></td></tr>";
					break;
					case 3: 
						$Table .= "<tr><td>".$Ret["ID_Solicitud_Unificacion"]."</td><td>".$Fecha."</td><td>".$RetMotivo_1["centro_salud"]."</td><td>".$RetMotivo_2["centro_salud"]."</td><td>".$Ret["username"]."</td><td>".$TipoUnif."</td><td><button class='btn btn-success' onClick='VerificarUnificacion(".$ID_Registro_1.",".$ID_Registro_2.",\"".$TipoUnif."\",".$ID_Solicitud.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarUnificacion(".$Ret["ID_Solicitud_Unificacion"].")'><i class='fa fa-times'></i></button></td></tr>";					
					break;
					case 4: 
						$Table .= "<tr><td>".$Ret["ID_Solicitud_Unificacion"]."</td><td>".$Fecha."</td><td>".$RetMotivo_1["Escuela"]."</td><td>".$RetMotivo_2["Escuela"]."</td><td>".$Ret["username"]."</td><td>".$TipoUnif."</td><td><button class='btn btn-success' onClick='VerificarUnificacion(".$ID_Registro_1.",".$ID_Registro_2.",\"".$TipoUnif."\",".$ID_Solicitud.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarUnificacion(".$Ret["ID_Solicitud_Unificacion"].")'><i class='fa fa-times'></i></button></td></tr>";					
					break;
					case 5: 
						$Table .= "<tr><td>".$Ret["ID_Solicitud_Unificacion"]."</td><td>".$Fecha."</td><td>".$RetMotivo_1["Barrio"]."</td><td>".$RetMotivo_2["Barrio"]."</td><td>".$Ret["username"]."</td><td>".$TipoUnif."</td><td><button class='btn btn-success' onClick='VerificarUnificacion(".$ID_Registro_1.",".$ID_Registro_2.",\"".$TipoUnif."\",".$ID_Solicitud.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarUnificacion(".$Ret["ID_Solicitud_Unificacion"].")'><i class='fa fa-times'></i></button></td></tr>";				
					break;
				}
				// $Table .= "<tr><td>".$Ret["ID_Solicitud_Unificacion"]."</td><td>".$Fecha."</td><td>".$RetMotivo_1["Motivo"]."</td><td>".$RetMotivo_2["Motivo"]."</td><td>".$Ret["username"]."</td><td>".$TipoUnif."</td><td><button class='btn btn-success' onClick='VerificarUnificacion(".$ID_Registro_1.",".$ID_Registro_2.",".$TipoUnif.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarUnificacion(".$Ret["ID_Solicitud_Unificacion"].")'><i class='fa fa-times'></i></button></td></tr>";
			}			
			$Table .= "</table>";
		}else{
			$Table = "No existen solicitudes de unificación pendientes de aprobación.";
		}
		$Con->CloseConexion();
		
		return $Table;
	}

	public function getCantSolicitudes_Crear_Motivo(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID from solicitudes_crearmotivos where Estado = 1";
		$MessageError = "Problemas al intentar consultar cantidad de Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		$Con->CloseConexion();		
		return $Regis;
	}

	public function getCantSolicitudes_Modificacion_Motivo(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID from solicitudes_modificarmotivos where Estado = 1";
		$MessageError = "Problemas al intentar consultar cantidad de Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		$Con->CloseConexion();		
		return $Regis;
	}

	public function getSolicitudes_Crear_Motivo(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select S.ID, S.Fecha, S.Motivo, S.Codigo, S.Cod_Categoria, S.Num_Motivo, U.username from solicitudes_crearmotivos S, accounts U where S.ID_Usuario = U.accountid and S.Estado = 1 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		if($Regis > 0){
			$Table = "<table class='table-responsive table-bordered'><thead><tr><th style='min-width:50px;'>Id</th><th style='min-width:100px;'>Fecha</th><th style='min-width:300px;'>Motivo</th><th style='min-width:100px;'>Codigo</th><th style='min-width:100px;'>Usuario</th><th style='min-width:100px;'>Acción</th></tr></thead>";
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Motivo = $Ret["Motivo"];
				$Codigo = $Ret["Codigo"];
				$Num_Motivo = $Ret["Num_Motivo"];
				$Usuario = $Ret["username"];
				$Categoria = $Ret["Cod_Categoria"];
				$Table .= "<tr><td>".$ID."</td><td>".$Fecha."</td><td>".$Motivo."</td><td>".$Codigo."</td><td>".$Usuario."</td><td><button class='btn btn-success' onClick='VerificarCrearMotivo(".$ID.",\"".$Fecha."\",\"".$Motivo."\",\"".$Codigo."\",".$Num_Motivo.",\"".$Categoria."\")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarCrearMotivo(".$Ret["ID"].")'><i class='fa fa-times'></i></button></td></tr>";
			}			
			$Table .= "</table>";
		}else{
			$Table = "No existen solicitudes de unificación pendientes de aprobación.";
		}
		$Con->CloseConexion();
		
		return $Table;
	}

	public function getSolicitudes_Modificacion_Motivo(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select S.ID, S.Fecha, S.Motivo, S.Codigo, S.Cod_Categoria, S.Num_Motivo, U.username, S.ID_Motivo from solicitudes_modificarmotivos S, accounts U where S.ID_Usuario = U.accountid and S.Estado = 1 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		if($Regis > 0){
			$Table = "<table class='table-responsive table-bordered'><thead><tr><th style='min-width:50px;'>Id</th><th style='min-width:100px;'>Fecha</th><th style='min-width:300px;'>Motivo</th><th style='min-width:100px;'>Codigo</th><th style='min-width:100px;'>Usuario</th><th style='min-width:100px;'>Acción</th></tr></thead>";
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Motivo = $Ret["Motivo"];
				$Codigo = $Ret["Codigo"];				
				$Num_Motivo = $Ret["Num_Motivo"];
				$Usuario = $Ret["username"];	
				$ID_Motivo = $Ret["ID_Motivo"];			
				$Table .= "<tr><td>".$ID."</td><td>".$Fecha."</td><td>".$Motivo."</td><td>".$Codigo."</td><td>".$Usuario."</td><td><button class='btn btn-success' onClick='VerificarModificarMotivo(".$ID.",\"".$Fecha."\",\"".$Motivo."\",\"".$Codigo."\",".$Num_Motivo.",".$ID_Motivo.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarModificacionMotivo(".$Ret["ID"].")'><i class='fa fa-times'></i></button></td></tr>";
			}			
			$Table .= "</table>";
		}else{
			$Table = "No existen solicitudes de unificación pendientes de aprobación.";
		}
		$Con->CloseConexion();
		
		return $Table;
	}

	public function get_solicitudes_motivo(){
		$Con = new Conexion();
		$Con->OpenConexion();

		$Table = "<table id='solicitudes-motivo' class='table-responsive table-bordered'>
		<thead>
		  <tr>
			  <th style='min-width:50px;'>Id</th>
			<th style='min-width:100px;'>Fecha</th>
			<th style='min-width:300px;'>Motivo</th>
			<th style='min-width:100px;'>Codigo</th>
			<th style='min-width:100px;'>Usuario</th>
			<th style='min-width:100px;'>Acción</th>
		  </tr>
		</thead>";

		$Consulta = "select S.ID, 
							S.Fecha, 
							S.Motivo, 
							S.Codigo, 
							S.Cod_Categoria, 
							S.Num_Motivo, 
							U.username 
					 from solicitudes_crearmotivos S, 
					 	  accounts U 
					 where S.ID_Usuario = U.accountid 
					   and S.Estado = 1 
					 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$regis_crear = mysqli_num_rows($Con->ResultSet);
		if($regis_crear > 0){
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Motivo = $Ret["Motivo"];
				$Codigo = $Ret["Codigo"];
				$Num_Motivo = $Ret["Num_Motivo"];
				$Usuario = $Ret["username"];
				$Categoria = $Ret["Cod_Categoria"];
				$Table .= "<tr><td>".$ID."</td><td>".$Fecha."</td><td>".$Motivo."</td><td>".$Codigo."</td><td>".$Usuario."</td><td><button class='btn btn-success' onClick='VerificarCrearMotivo(".$ID.",\"".$Fecha."\",\"".$Motivo."\",\"".$Codigo."\",".$Num_Motivo.",\"".$Categoria."\")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarCrearMotivo(".$Ret["ID"].")'><i class='fa fa-times'></i></button></td></tr>";
			}			
		}

		$Consulta = "select S.ID,
							S.Fecha, 
							S.Motivo, 
							S.Codigo, 
							S.Cod_Categoria, 
							S.Num_Motivo, 
							U.username, 
							S.ID_Motivo 
					 from solicitudes_modificarmotivos S, 
					 	  accounts U 
					 where S.ID_Usuario = U.accountid 
					   and S.Estado = 1 
					 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$regis_modificar = mysqli_num_rows($Con->ResultSet);
		if($regis_modificar > 0){
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Motivo = $Ret["Motivo"];
				$Codigo = $Ret["Codigo"];				
				$Num_Motivo = $Ret["Num_Motivo"];
				$Usuario = $Ret["username"];	
				$ID_Motivo = $Ret["ID_Motivo"];			
				$Table .= "<tr><td>".$ID."</td><td>".$Fecha."</td><td>".$Motivo."</td><td>".$Codigo."</td><td>".$Usuario."</td><td><button class='btn btn-success' onClick='VerificarModificarMotivo(".$ID.",\"".$Fecha."\",\"".$Motivo."\",\"".$Codigo."\",".$Num_Motivo.",".$ID_Motivo.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarModificacionMotivo(".$Ret["ID"].")'><i class='fa fa-times'></i></button></td></tr>";
			}
		}

		$Consulta = "select S.ID, 
							S.Fecha, 
							S.Motivo, 
							S.Cod_Categoria, 
							S.Num_Motivo, 
							U.username, 
							S.ID_Motivo 
					 from solicitudes_eliminarmotivos S, 
					 	  accounts U 
					 where S.ID_Usuario = U.accountid 
					   and S.Estado = 1 
					 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes eliminar motivos";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$regis_del = mysqli_num_rows($Con->ResultSet);
		if($regis_del > 0){
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Motivo = $Ret["Motivo"];
				$Cod_Categoria = $Ret["Cod_Categoria"];
				$Num_Motivo = $Ret["Num_Motivo"];
				$Usuario = $Ret["username"];	
				$ID_Motivo = $Ret["ID_Motivo"];			
				$Table .= "<tr><td>".$ID."</td><td>".$Fecha."</td><td>".$Motivo."</td><td>".$Cod_Categoria."</td><td>".$Usuario."</td><td><button class='btn btn-success' onClick='VerificarEliminarMotivo(".$ID_Motivo.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarEliminacionMotivo(".$Ret["ID"].")'><i class='fa fa-times'></i></button></td></tr>";
			}
		}

		$Table .= "</table>";

		$Con->CloseConexion();
		return $Table;
	}

	public function getCantSolicitudes_Crear_Categoria(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID 
					 from solicitudes_crearcategorias 
					 where Estado = 1";
		$MessageError = "Problemas al intentar consultar cantidad de Solicitudes Categorias";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		$Con->CloseConexion();		
		return $Regis;
	}

	public function getCantSolicitudes_Modificacion_Categoria(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID from solicitudes_modificarcategorias where Estado = 1";
		$MessageError = "Problemas al intentar consultar cantidad de Solicitudes Categorias";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		$Con->CloseConexion();		
		return $Regis;
	}


	public function getSolicitudes_Crear_Categoria(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select S.ID, S.Fecha, S.Codigo, S.Categoria, S.ID_Forma, S.Color, S.Categoria, U.username from solicitudes_crearcategorias S, accounts U where S.ID_Usuario = U.accountid and S.Estado = 1 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes Categorias";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		if($Regis > 0){
			$Table = "<table id='creacionCategoria' class='table-responsive table-bordered'>
						<thead>
							<tr>
								<th style='min-width:50px;'>Id</th>
								<th style='min-width:100px;'>Fecha</th>
								<th style='min-width:100px;'>Código</th>
								<th style='min-width:130px;'>Denominación</th>
								<th style='min-width:100px;'>Permisos</th>
								<th style='min-width:100px;'>Usuario</th>
								<th style='min-width:100px;'>Acción</th>
							</tr>
						</thead>";
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$ConsultaPermisos = "select  *
									 from solicitudes_permisos s inner join Tipo_Usuarios t on t.ID_TipoUsuario = s.ID_TipoUsuario
									 where ID = {$ID}
									   and estado = 1";
				$MessageError = "Problemas al intentar mostrar Solicitudes Permisos";
				$Resultados = mysqli_query($Con->Conexion,$ConsultaPermisos) or die($MessageError);
				$Permisos = ""; 
				while ($RetPermisos = mysqli_fetch_array($Resultados)) {
					$Permisos .= $RetPermisos["abreviacion"] . " " ;
				}
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Codigo = $Ret["Codigo"];
				$Categoria = $Ret["Categoria"];
				$ID_Forma = $Ret["ID_Forma"];
				$Color = $Ret["Color"];
				$Usuario = $Ret["username"];						
				$Table .= "<tr>
								<td>".$ID."</td>
								<td>".$Fecha."</td>
								<td>".$Codigo."</td>
								<td>".$Categoria."</td>
								<td>".(($Permisos !="")?$Permisos:"Ninguno")."</td>
								<td>".$Usuario."</td>
								<td>
									<button class='btn btn-success' onClick='VerificarCrearCategoria(".$ID.",\"".$Fecha."\",\"".$Codigo."\",\"".$Categoria."\",\"".$ID_Forma."\",\"".$Color."\")'>
										<i class='fa fa-check'></i>
									</button>
									<button class='btn btn-danger' onClick='CancelarCrearCategoria(".$Ret["ID"].")'>
										<i class='fa fa-times'></i>
									</button>
								</td>
							</tr>";
			}
			$Table .= "</table>";
		}else{
			$Table = "No existen solicitudes de unificación pendientes de aprobación.";
		}
		$Con->CloseConexion();
		
		return $Table;
	}

	public function getSolicitudes_Modificacion_Categoria(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select S.ID, S.Fecha, S.Codigo, S.Categoria, S.ID_Forma, S.NuevoColor, S.ID_Categoria, U.username from solicitudes_modificarcategorias S, accounts U where S.ID_Usuario = U.accountid and S.Estado = 1 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes Categorias";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		if($Regis > 0){
			$Table = "<table id='modificacionCategoria' class='table-responsive table-bordered'>
						<thead>
							<tr>
								<th style='min-width:50px;'>Id</th>
								<th style='min-width:100px;'>Fecha</th>
								<th style='min-width:300px;'>Código</th>
								<th style='min-width:130px;'>Denominación</th>
								<th style='min-width:100px;'>Permisos</th>
								<th style='min-width:100px;'>Usuario</th>
								<th style='min-width:100px;'>Acción</th>
							</tr>
						</thead>";
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$ConsultaPermisos = "select  *
									 from solicitudes_permisos s inner join Tipo_Usuarios t on t.ID_TipoUsuario = s.ID_TipoUsuario
									 where ID = {$ID}
									   and estado = 1";
				$MessageError = "Problemas al intentar mostrar Solicitudes Permisos";
				$Resultados = mysqli_query($Con->Conexion,$ConsultaPermisos) or die($MessageError);
				$Permisos = ""; 
				while ($RetPermisos = mysqli_fetch_array($Resultados)) {
					$Permisos .= $RetPermisos["abreviacion"] . " " ;
				}

				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Codigo = $Ret["Codigo"];
				$Categoria = $Ret["Categoria"];		
				$ID_Forma = $Ret["ID_Forma"];
				$NuevoColor = $Ret["NuevoColor"];	
				$ID_Categoria = $Ret["ID_Categoria"];	
				$Usuario = $Ret["username"];							
				$Table .= "<tr>
								<td>".$ID."</td>
								<td>".$Fecha."</td>
								<td>".$Codigo."</td>
								<td>".$Categoria."</td>
								<td>".(($Permisos !="")?$Permisos:"Ninguno")."</td>
								<td>".$Usuario."</td>
								<td>
									<button class='btn btn-success' onClick='VerificarModificarCategoria(".$ID.",\"".$Fecha."\",\"".$Codigo."\",\"".$Categoria."\",\"".$ID_Forma."\",\"".$NuevoColor."\",\"".$ID_Categoria."\")'>
										<i class='fa fa-check'></i>
									</button>
									<button class='btn btn-danger' onClick='CancelarModificacionCategoria(".$Ret["ID"].")'>
										<i class='fa fa-times'></i>
									</button>
								</td>
							</tr>";
			}			
			$Table .= "</table>";
		}else{
			$Table = "No existen solicitudes de unificación pendientes de aprobación.";
		}
		$Con->CloseConexion();
		
		return $Table;
	}

	public function get_solicitudes_categoria(){
		$Con = new Conexion();
		$Con->OpenConexion();

		$Table = "<table id='solicitudes-categoria' class='table-responsive table-bordered'>
					<thead>
						<tr>
							<th style='min-width:50px;'>Id</th>
							<th style='min-width:100px;'>Fecha</th>
							<th style='min-width:100px;'>Código</th>
							<th style='min-width:130px;'>Denominación</th>
							<th style='min-width:100px;'>Permisos</th>
							<th style='min-width:100px;'>Usuario</th>
							<th style='min-width:100px;'>Acción</th>
						</tr>
					</thead>";

		$Consulta = "select S.ID, 
							S.Fecha, 
							S.Codigo, 
							S.Categoria, 
							S.ID_Forma, 
							S.Color, 
							S.Categoria, 
							U.username 
					 from solicitudes_crearcategorias S, 
					 	  accounts U 
					 where S.ID_Usuario = U.accountid 
					   and S.Estado = 1 
					 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes Categorias";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$regis_crear = mysqli_num_rows($Con->ResultSet);
		if($regis_crear > 0){
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$ConsultaPermisos = "select  *
									 from solicitudes_permisos s inner join Tipo_Usuarios t on t.ID_TipoUsuario = s.ID_TipoUsuario
									 where ID = {$ID}
									   and estado = 1";
				$MessageError = "Problemas al intentar mostrar Solicitudes Permisos";
				$Resultados = mysqli_query($Con->Conexion,$ConsultaPermisos) or die($MessageError);
				$Permisos = ""; 
				while ($RetPermisos = mysqli_fetch_array($Resultados)) {
					$Permisos .= $RetPermisos["abreviacion"] . " " ;
				}
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Codigo = $Ret["Codigo"];
				$Categoria = $Ret["Categoria"];
				$ID_Forma = $Ret["ID_Forma"];
				$Color = $Ret["Color"];
				$Usuario = $Ret["username"];						
				$Table .= "<tr>
								<td>".$ID."</td>
								<td>".$Fecha."</td>
								<td>".$Codigo."</td>
								<td>".$Categoria."</td>
								<td>".(($Permisos !="")?$Permisos:"Ninguno")."</td>
								<td>".$Usuario."</td>
								<td>
									<button class='btn btn-success' onClick='VerificarCrearCategoria(".$ID.",\"".$Fecha."\",\"".$Codigo."\",\"".$Categoria."\",\"".$ID_Forma."\",\"".$Color."\")'>
										<i class='fa fa-check'></i>
									</button>
									<button class='btn btn-danger' onClick='CancelarCrearCategoria(".$Ret["ID"].")'>
										<i class='fa fa-times'></i>
									</button>
								</td>
							</tr>";
			}
		}
		$Consulta = "select S.ID, 
							S.Fecha, 
							S.Codigo, 
							S.Categoria, 
							S.ID_Forma, 
							S.NuevoColor, 
							S.ID_Categoria, 
							U.username 
					 from solicitudes_modificarcategorias S,
						 accounts U 
					 where S.ID_Usuario = U.accountid 
					   and S.Estado = 1
					 order by S.Fecha";

		$MessageError = "Problemas al intentar mostrar Solicitudes Categorias";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$regis_modificar = mysqli_num_rows($Con->ResultSet);

		if($regis_modificar > 0){
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$ConsultaPermisos = "select  *
									 from solicitudes_permisos s inner join Tipo_Usuarios t on t.ID_TipoUsuario = s.ID_TipoUsuario
									 where ID = {$ID}
									   and estado = 1";
				$MessageError = "Problemas al intentar mostrar Solicitudes Permisos";
				$Resultados = mysqli_query($Con->Conexion,$ConsultaPermisos) or die($MessageError);
				$Permisos = ""; 
				while ($RetPermisos = mysqli_fetch_array($Resultados)) {
					$Permisos .= $RetPermisos["abreviacion"] . " " ;
				}

				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Codigo = $Ret["Codigo"];
				$Categoria = $Ret["Categoria"];		
				$ID_Forma = $Ret["ID_Forma"];
				$NuevoColor = $Ret["NuevoColor"];	
				$ID_Categoria = $Ret["ID_Categoria"];	
				$Usuario = $Ret["username"];							
				$Table .= "<tr>
								<td>".$ID."</td>
								<td>".$Fecha."</td>
								<td>".$Codigo."</td>
								<td>".$Categoria."</td>
								<td>".(($Permisos !="")?$Permisos:"Ninguno")."</td>
								<td>".$Usuario."</td>
								<td>
									<button class='btn btn-success' onClick='VerificarModificarCategoria(".$ID.",\"".$Fecha."\",\"".$Codigo."\",\"".$Categoria."\",\"".$ID_Forma."\",\"".$NuevoColor."\",\"".$ID_Categoria."\")'>
										<i class='fa fa-check'></i>
									</button>
									<button class='btn btn-danger' onClick='CancelarModificacionCategoria(".$Ret["ID"].")'>
										<i class='fa fa-times'></i>
									</button>
								</td>
							</tr>";
			}			
		}

		$Consulta = "select S.ID, S.Fecha, 
					 S.Categoria, 
					 S.Cod_Categoria, 
					 U.username, 
					 S.ID_Categoria 
					 from solicitudes_eliminarcategorias S, 
						  accounts U
					 where S.ID_Usuario = U.accountid and S.Estado = 1 order by S.Fecha";

		$MessageError = "Problemas al intentar mostrar Solicitudes eliminar categorias";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$regis_eliminar = mysqli_num_rows($Con->ResultSet);
		if($regis_eliminar > 0){
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Categoria = $Ret["Categoria"];
				$Cod_Categoria = $Ret["Cod_Categoria"];				
				$Usuario = $Ret["username"];	
				$ID_Categoria = $Ret["ID_Categoria"];			
				$Table .= "<tr>
								<td>" . $ID . "</td>
								<td>" . $Fecha . "</td>
								<td>" . $Categoria . "</td>
								<td>" . $Cod_Categoria . "</td>
								<td>" . "Sin permisos" . "</td>
								<td>" . $Usuario . "</td>
								<td>
									<button class='btn btn-success' onClick='VerificarEliminarCategoria(".$ID_Categoria.")'>
										<i class='fa fa-check'></i>
									</button>
									<button class='btn btn-danger' onClick='CancelarEliminacionCategoria(".$Ret["ID"].")'>
										<i class='fa fa-times'></i>
									</button>
								</td>
							</tr>";
			}
		}
		$Table .= "</table>";

		if ($regis_crear > 0 && $regis_modificar > 0) {
			$Table = "No existen solicitudes de unificación pendientes de aprobación.";
		}
		
		$Con->CloseConexion();
		return $Table;
	}

	public function getCategorias_Roles_ID($XID){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select cr.id_categoria, tip.abreviacion from categorias_roles cr inner join Tipo_Usuarios tip on cr.ID_TipoUsuario = tip.ID_TipoUsuario
					 where cr.id_categoria = {$XID}
					   and cr.estado = 1";
		$MessageError = "Problemas al intentar mostrar Solicitudes Categorias";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		$Permisos = "";
		if($Regis > 0){
			while ($RetPermisos = mysqli_fetch_array($Con->ResultSet)) {
				$Permisos .= $RetPermisos["abreviacion"] . " - " ;
			}
			$Permisos = preg_replace("/- $/", "", $Permisos);
		}
		$Con->CloseConexion();
		
		return $Permisos;
	}

	public function getCantSolicitudes_EliminacionMotivo(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID from solicitudes_eliminarmotivos where Estado = 1";
		$MessageError = "Problemas al intentar consultar cantidad de Solicitudes eliminar motivo";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		$Con->CloseConexion();		
		return $Regis;
	}

	public function getSolicitudes_EliminacionMotivo(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select S.ID, S.Fecha, S.Motivo, S.Cod_Categoria, S.Num_Motivo, U.username, S.ID_Motivo from solicitudes_eliminarmotivos S, accounts U where S.ID_Usuario = U.accountid and S.Estado = 1 order by S.Fecha";
		$MessageError = "Problemas al intentar mostrar Solicitudes eliminar motivos";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		if($Regis > 0){
			$Table = "<table class='table-responsive table-bordered'>
						<thead>
							<tr>
								<th style='min-width:50px;'>Id</th>
								<th style='min-width:100px;'>Fecha</th>
								<th style='min-width:300px;'>Motivo</th>
								<th style='min-width:100px;'>Cod. Categoría</th>
								<th style='min-width:100px;'>Usuario</th>
								<th style='min-width:100px;'>Acción</th>
							</tr>
						</thead>";
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Motivo = $Ret["Motivo"];
				$Cod_Categoria = $Ret["Cod_Categoria"];
				$Num_Motivo = $Ret["Num_Motivo"];
				$Usuario = $Ret["username"];	
				$ID_Motivo = $Ret["ID_Motivo"];			
				$Table .= "<tr><td>".$ID."</td><td>".$Fecha."</td><td>".$Motivo."</td><td>".$Cod_Categoria."</td><td>".$Usuario."</td><td><button class='btn btn-success' onClick='VerificarEliminarMotivo(".$ID_Motivo.")'><i class='fa fa-check'></i></button><button class='btn btn-danger' onClick='CancelarEliminacionMotivo(".$Ret["ID"].")'><i class='fa fa-times'></i></button></td></tr>";
			}			
			$Table .= "</table>";
		}else{
			$Table = "No existen solicitudes de eliminar motivos pendientes de aprobación.";
		}
		$Con->CloseConexion();
		
		return $Table;
	}

	public function getCantSolicitudes_EliminacionCategoria(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID from solicitudes_eliminarcategorias where Estado = 1";
		$MessageError = "Problemas al intentar consultar cantidad de Solicitudes eliminar categoria";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		$Con->CloseConexion();		
		return $Regis;
	}

	public function getSolicitudes_EliminacionCategoria(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select S.ID, S.Fecha, 
							S.Categoria, 
							S.Cod_Categoria, 
							U.username, 
							S.ID_Categoria 
					 from solicitudes_eliminarcategorias S, 
					 	  accounts U
					 where S.ID_Usuario = U.accountid and S.Estado = 1 order by S.Fecha";

		$MessageError = "Problemas al intentar mostrar Solicitudes eliminar categorias";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		if($Regis > 0){
			$Table = "<table class='table-responsive table-bordered'>
						<thead>
							<tr>
								<th style='min-width:50px;'>Id</th>
								<th style='min-width:100px;'>Fecha</th>
								<th style='min-width:130px;'>Denominación</th>
								<th style='min-width:100px;'>Código</th>
								<th style='min-width:100px;'>Usuario</th>
								<th style='min-width:100px;'>Acción</th>
							</tr>
						</thead>";
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ID = $Ret["ID"];
				$Fecha = implode("/", array_reverse(explode("-",$Ret["Fecha"])));
				$Categoria = $Ret["Categoria"];
				$Cod_Categoria = $Ret["Cod_Categoria"];				
				$Usuario = $Ret["username"];	
				$ID_Categoria = $Ret["ID_Categoria"];			
				$Table .= "<tr>
								<td>".$ID."</td>
								<td>".$Fecha."</td>
								<td>".$Categoria."</td>
								<td>".$Cod_Categoria."</td>
								<td>".$Usuario."</td>
								<td>
									<button class='btn btn-success' onClick='VerificarEliminarCategoria(".$ID_Categoria.")'>
										<i class='fa fa-check'></i>
									</button>
									<button class='btn btn-danger' onClick='CancelarEliminacionCategoria(".$Ret["ID"].")'>
										<i class='fa fa-times'></i>
									</button>
								</td>
							</tr>";
			}
			$Table .= "</table>";
		}else{
			$Table = "No existen solicitudes de eliminar categoria pendientes de aprobación.";
		}
		$Con->CloseConexion();
		
		return $Table;
	}

	public function getSolicitudes_Notificaciones(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select N.ID_Notificacion, 
							N.Detalle, 
							N.Fecha, 
							N.Expira, 
							N.Estado 
					 from notificaciones N 
					 where N.Expira > CURDATE() 
					   and N.Estado = 1 
					order by N.Fecha";
		$MessageError = "Problemas al intentar mostrar Notificaciones";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		if($Regis > 0){
			$Table = "<table id='eliminarNotificaciones' class='table-responsive table-bordered'><thead><tr><th style='min-width:50px;'>Id</th><th style='min-width:100px;'>Fecha</th><th style='min-width:300px;'>Detalle</th><th style='min-width:100px;'>Expira</th><th style='min-width:100px;'>Acción</th></tr></thead>";
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$RetFecha = explode(" ", $Ret["Fecha"]);
				$RetExpira = explode(" ", $Ret["Expira"]);
				$ID_Notificacion = $Ret["ID_Notificacion"];
				$Fecha = implode("/", array_reverse(explode("-",$RetFecha[0])));
				$Detalle = $Ret["Detalle"];												
				$Expira = implode("/", array_reverse(explode("-",$RetExpira[0])));
				$Table .= "<tr><td>".$ID_Notificacion."</td><td>".$Fecha."</td><td>".$Detalle."</td><td>".$Expira."</td><td><button class='btn btn-danger' onClick='VerificarEliminarNotificacion(".$ID_Notificacion.")'><i class='fa fa-times'></i></button></td></tr>";
			}			
			$Table .= "</table>";
		}else{
			$Table = "No existen solicitudes de unificación pendientes de aprobación.";
		}
		$Con->CloseConexion();
		
		return $Table;
	}

	// NOTIFICACIONES DE USUARIOS
	public function getNotificaciones(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select ID_Notificacion, 
							Detalle, 
							Fecha, 
							Expira, 
							Estado 
					 from notificaciones 
					 where Expira > CURDATE() 
					   and Estado = 1";
		$MessageError = "Problemas al intentar mostrar Notificaciones";
		$Con->ResultSet = mysqli_query($Con->Conexion, $Consulta) or die($MessageError);
		$retNot = mysqli_fetch_assoc($Con->ResultSet);
		$Con->CloseConexion();
		$ret = ["cant" => mysqli_num_rows($Con->ResultSet), "value" => $retNot];
		return $ret;
	}

	public function get_cant_solicitudes_usuario(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select id_solicitud 
					 from solicitudes_usuarios 
					 where estado = 1";
		$MessageError = "Problemas al intentar consultar cantidad de Solicitudes de usuario";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		$Con->CloseConexion();		
		return $Regis;
	}

	public function get_solicitudes_usuario(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "select U.id_solicitud, 
							U.descripcion, 
							U.fecha, 
							U.tipo, 
							U.estado 
					 from solicitudes_usuarios U 
					 where U.estado = 1 
					order by U.fecha";
		$MessageError = "Problemas al intentar mostrar Notificaciones";
		$Con->ResultSet = mysqli_query($Con->Conexion,$Consulta) or die($MessageError);
		$Regis = mysqli_num_rows($Con->ResultSet);
		if($Regis > 0){
			$Table = "<table id='solicitud-usuario' class='table-responsive table-bordered'><thead><tr><th style='min-width:50px;'>Id</th><th style='min-width:100px;'>Fecha</th><th style='min-width:300px;'>Detalle</th><th style='min-width:100px;'>tipo</th><th style='min-width:100px;'>Acción</th></tr></thead>";
			while ($Ret = mysqli_fetch_array($Con->ResultSet)) {
				$ret_fecha = explode(" ", $Ret["fecha"]);
				$tipo = $Ret["tipo"];
				$ret_id_solicitud = $Ret["id_solicitud"];
				$fecha = implode("/", array_reverse(explode("-",$ret_fecha[0])));
				$descripcion = $Ret["descripcion"];												
				$Table .= "<tr>
							 <td>" . $ret_id_solicitud . "</td>
							 <td>" . $fecha . "</td>
							 <td>" . $descripcion . "</td>
							 <td>" . $tipo . "</td>
							 <td>
								 <button class='btn btn-success' onClick='ConfirmarModificacionUsario(" . $ret_id_solicitud . ")'>
								   <i class='fa fa-check'></i>
								 </button>
								 <button class='btn btn-danger' onClick='CancelarModificacionUsario(". $ret_id_solicitud . ")'>
								   <i class='fa fa-times'></i>
								 </button>
							 </td>
						   </tr>";
			}			
			$Table .= "</table>";
		}else{
			$Table = "No existen solicitudes de unificación pendientes de aprobación.";
		}
		$Con->CloseConexion();
		
		return $Table;
	}

	public function getMes($mes){
		 
		switch($mes){
			case "January": 
				$mesColumna = "Ene";
			break;
			case "February": 
				$mesColumna = "Feb";
			break;
			case "March": 
				$mesColumna ="Mar";
			break;
			case "April": 
				$mesColumna ="Abr";
			break;
			case "May": 
				$mesColumna ="May";
			break;
			case "June": 
				$mesColumna ="Jun";
			break;
			case "July":
				$mesColumna ="Jul";
			break;
			case "August":
				$mesColumna ="Ago";
			break;
			case "September":
				$mesColumna ="Sep";
			break;
			case "October":
				$mesColumna ="Oct";
				break;
			case "November":
				$mesColumna ="Nov";
				break;
			case "December":
				$mesColumna ="Dic";
				break;
			default: 
				$mesColumna ="Error";
			break;
		}
		return $mesColumna;
	}
}