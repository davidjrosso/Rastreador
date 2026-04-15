<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Accion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Parametria.php');

class Domicilio implements JsonSerializable {
	//DECLARACION DE VARIABLES
    private $coneccion;
    private $id_domicilio;
    private $Cambio_Domicilio;
	private $id_calle;
	private $Circunscripcion;
	private $Domicilio;
	private $Estado;
	private $Familia;
	private $Georeferencia;
	private $Localidad;
	private $Lote;
	private $Manzana;
	private $numero;
	private $Barrio;
    private $Seccion;

	public function __construct(
        $coneccion = null,
		$xBarrio = null,
		$xLocalidad = null,
		$xCircunscripcion = null,
        $id_domicilio = null,
        $xSeccion = null,
		$xManzana = null,
		$xLote = null,
		$xFamilia = null,
		$xCambio_Domicilio  = null,
		$xEstado = null,
		$xGeoreferencia = null,
		$xCalle = null,
		$xNro = null
	){
        $this->coneccion = $coneccion;
        if (!$id_domicilio ) {

			$ConsultarPersona = "select *,
										ST_X(georeferencia) as lat,
										ST_Y(georeferencia) as lon
								 from domicilios 
								 where id_calle = " . $xCalle . " 
								 and numero = $xNro 
								 and estado = 1";
			$EjecutarConsultarPersona = mysqli_query(
				$this->coneccion->Conexion,
				$ConsultarPersona) or die("Problemas al consultar filtro Persona");
			$ret = mysqli_fetch_assoc($EjecutarConsultarPersona);
			
			$barrio = (isset($ret["id_barrio"])) ? $ret["id_barrio"] : null;
			$localidad = (isset($ret["localidad"])) ? $ret["localidad"] : null;
			$circunscripcion = (isset($ret["circunscripcion"])) ? $ret["circunscripcion"] : null;
			$query_id_domicilio = (isset($ret["id_domicilio"])) ? $ret["id_domicilio"] : null;
			$seccion = (isset($ret["seccion"])) ? $ret["seccion"] : null;
			$manzana = (isset($ret["manzana"])) ? $ret["manzana"] : null;
			$lote = (isset($ret["lote"])) ? $ret["lote"] : null;
			$familia = (isset($ret["familia"])) ? $ret["familia"] : null;
			$cambio_Domicilio = (isset($ret["cambio_domicilio"])) ? $ret["cambio_domicilio"] : null;
			$estado = (isset($ret["estado"])) ? $ret["estado"] : null;
			$calle = (isset($ret["id_calle"])) ? $ret["id_calle"] : null;
			$nro = (isset($ret["numero"])) ? $ret["numero"] : null;
			$georefencia = (isset($ret["georeferencia"])) ? "POINT(" . $ret["lat"] . "," . $ret["lon"] . ")" : null;
			$this->Barrio = ($xBarrio) ? $xBarrio : $barrio;
			$this->Localidad = ($xLocalidad) ? $xLocalidad : $localidad;
			$this->Circunscripcion = ($xCircunscripcion) ? $xCircunscripcion : $circunscripcion;
            $this->id_domicilio = ($id_domicilio) ?  $id_domicilio : $query_id_domicilio ;
            $this->Seccion = ($xSeccion) ? $xSeccion : $seccion;
			$this->Manzana = ($xManzana) ? : $manzana;
			$this->Lote = ($xLote) ? $xLote : $lote;
			$this->Familia = ($xFamilia) ? $xFamilia : $familia;
			$this->Cambio_Domicilio = ($xCambio_Domicilio) ? $xCambio_Domicilio : $cambio_Domicilio;
			$this->Estado = ($xEstado) ? $xEstado : $estado;
			$this->Georeferencia = ($xGeoreferencia) ? $xGeoreferencia : $georefencia;
			$this->numero = ($xNro) ? $xNro : $nro;
			$this->id_calle = ($xCalle) ? $xCalle : $calle;

		} else {
			$Con = new Conexion();
			$Con->OpenConexion();
			$ConsultarPersona = "select *,
										ST_X(georeferencia) as lat,
										ST_Y(georeferencia) as lon
								 from domicilios 
								 where id_domicilio = " . $id_domicilio  . " 
								   and estado = 1";
			$EjecutarConsultarPersona = mysqli_query(
				$Con->Conexion,
				$ConsultarPersona) or die("Problemas al consultar filtro Persona");
			$ret = mysqli_fetch_assoc($EjecutarConsultarPersona);
			
			$barrio = $ret["ID_Barrio"];
			$localidad = $ret["localidad"];
			$circunscripcion = $ret["circunscripcion"];
            $seccion = $ret["seccion"];
			$manzana = $ret["manzana"];
			$lote = $ret["lote"];
			$familia = $ret["familia"];
			$cambio_Domicilio = $ret["cambio_domicilio"];
			$estado = $ret["estado"];
			$calle = $ret["id_calle"];
			$nro = $ret["numero"];
			$georefencia = (isset($ret["georeferencia"])) ? "POINT(" . $ret["lat"] . "," . $ret["lon"] . ")" : null;
			$this->Barrio = ($xBarrio) ? $xBarrio : $barrio;
			$this->Localidad = ($xLocalidad) ? $xLocalidad : $localidad;
			$this->Circunscripcion = ($xCircunscripcion) ? $xCircunscripcion : $circunscripcion;
            $this->id_domicilio = $id_domicilio ;
            $this->Seccion = ($xSeccion) ? $xSeccion : $seccion;
			$this->Manzana = ($xManzana) ? : $manzana;
			$this->Lote = ($xLote) ? $xLote : $lote;
			$this->Familia = ($xFamilia) ? $xFamilia : $familia;
			$this->Cambio_Domicilio = ($xCambio_Domicilio) ? $xCambio_Domicilio : $cambio_Domicilio;
			$this->Estado = ($xEstado) ? $xEstado : $estado;
			$this->Georeferencia = ($xGeoreferencia) ? $xGeoreferencia : $georefencia;
			$this->numero = ($xNro) ? $xNro : $nro;
			$this->id_calle = ($xCalle) ? $xCalle : $calle;
			$Con->CloseConexion();
		}
	}


//METODOS SET

public function setDomicilio($xDomicilio = null)
{
	$id_calle = (!$xDomicilio) ? $this->getId_Calle() : null;
	$numero_calle = (!$xDomicilio) ? trim($this->getNro()) : null;
	$domicilio = ($xDomicilio) ? $xDomicilio : null;
	$nombre_calle = null;

	$con = new Conexion();
	$con->OpenConexion();
	if (!is_null($id_calle)) {
		$nombre_calle = $this->getNombre_Calle();
		$domicilio = "$nombre_calle $numero_calle";
		$domicilio = str_replace(array('á','é','í','ó','ú','ñ'), array('a','e','i','o','u','n'), $domicilio);
	} else if ($domicilio) {
		$consulta = "select calle_open, id_calle
					 from calle
					 where lower(calle_nombre) like CONCAT(
															'%',
															REGEXP_REPLACE( 
																	REGEXP_REPLACE(
																					REGEXP_SUBSTR(
																							lower('$domicilio'), 
																							'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)'
																					),
																					'( )+',
																					'%'
																					),
																			'(\\\\.)',
																			''
																			),
															'%'
															)
					    and estado = 1
					 order by calle_nombre asc;";
		$query_object = mysqli_query($con->Conexion, $consulta) or die("Error al consultar datos");

		$nro_calle = trim($domicilio);
		$out = null;
		$ret = null;
		if (preg_match('~ [0-9]+$~', $nro_calle, $out)) {
			$nro_calle = trim($out[0]);
		} else {
			if (preg_match('~ [0-9]+ ([aA-zZ]|[0-9])+~', $nro_calle, $ret)) {
				$lista = explode(" ", trim($ret[0]));
				$nro_calle = trim($lista[0]);
			} else {
				preg_match('~^[0-9]+$~', $nro_calle, $out);
				if (!empty($out[0])) {
					$nro_calle = trim($out[0]);
				} else {
					$nro_calle = null;
				}
			}
		}

		if (mysqli_num_rows($query_object) > 0) {
			$ret = mysqli_fetch_assoc($query_object);
			if ($ret["id_calle"]) {
				$nombre_calle = $ret["calle_open"];
				$this->id_calle = $ret["id_calle"];
				$this->numero = (($nro_calle) ? $nro_calle : $this->getNro());
				$domicilio = "$nombre_calle " . $this->getNro();
			}
		}
		$domicilio = str_replace(array('á','é','í','ó','ú','ñ'), array('a','e','i','o','u','n'), $domicilio);
	}

	$Fecha = date("Y-m-d");
	if ($domicilio) {
		$up_query_go = Parametria::get_value_by_code($con, "UP_GOOGLE");
		if ($up_query_go) {
			$ch = curl_init();
			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . str_replace(" ", "+", trim($domicilio)) . "+Rio+Tercero,Cordoba&key=AIzaSyAdiF1F7NoZbmAzBWfV6rxjJrGsr1Yvb1g";
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER  , 1);
			$response = curl_exec($ch);
			$response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$arr_obj_json = json_decode($response);
			curl_close($ch);
			if ($response_status == 200) {
				$body_request = (($arr_obj_json) ? " " . json_encode($arr_obj_json->results[0]) : "");
			} else {
				$body_request = "- El estado de la respuesta de google api es : " . $response_status;
				$arr_obj_json = null;
			}
			$detalles = $url . $body_request;
			$accion = new Accion(
				xFecha : $Fecha,
				xDetalles : $detalles,
				xID_TipoAccion : 1
			);
			$accion->save();
	
			$center_rio_tercero_lat = -32.194998;
			$center_rio_tercero_lon = -64.1684546;
		} else {
			$arr_obj_json = null;
		}

		if ($arr_obj_json && $arr_obj_json->results) {
			if ((!is_null($arr_obj_json->results[0]->geometry->location->lat) 
				|| !is_null($arr_obj_json->results[0]->geometry->location->lng))
				&& ($center_rio_tercero_lat != $arr_obj_json->results[0]->geometry->location->lat)
				&& ($center_rio_tercero_lon != $arr_obj_json->results[0]->geometry->location->lng)
			) {
				$point = "POINT(" . $arr_obj_json->results[0]->geometry->location->lat . ", " . $arr_obj_json->results[0]->geometry->location->lng . ")";
				$this->Georeferencia = $point;
			} else {
				$ch = curl_init();
				$url = "https://nominatim.openstreetmap.org/search?street=" . str_replace(" ", "+", trim($domicilio)) . "&city=rio+tercero&format=jsonv2&limit=1&email=martinmonnittola@gmail.com";
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($ch);
				$arr_obj_json = json_decode($response);
				curl_close($ch);
				$body_request = (($arr_obj_json[0]) ? " " . json_encode($arr_obj_json[0]) : "");
				$body_request = str_replace("'", "", $body_request);
				$detalles = $url . $body_request;
				$accion = new Accion(
					xFecha : $Fecha,
					xDetalles : $detalles,
					xID_TipoAccion : 1
				);
				$accion->save();
				if ($arr_obj_json) {
					if (!is_null($arr_obj_json[0]->lat) || !is_null($arr_obj_json[0]->lon)) {
						$point = "POINT(" . $arr_obj_json[0]->lat . ", " . $arr_obj_json[0]->lon . ")";
						$this->Georeferencia = $point;
					} else {
						$this->Georeferencia = null;
					}
				} else {
					$this->Georeferencia = null;
				}
			}
		} else {
			$ch = curl_init();
			$url = "https://nominatim.openstreetmap.org/search?street=" . str_replace(" ", "+", trim($domicilio)) . "&city=rio+tercero&format=jsonv2&limit=1&email=martinmonnittola@gmail.com";
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			$arr_obj_json = json_decode($response);
			curl_close($ch);
			$body_request = ((isset($arr_obj_json[0])) ? " " . json_encode($arr_obj_json[0]) : "");
			$body_request = str_replace("'", "", $body_request);
			$detalles = $url . $body_request;
			$accion = new Accion(
				xFecha : $Fecha,
				xDetalles : $detalles,
				xID_TipoAccion : 1
			);
			$accion->save();
			if ($arr_obj_json) {
				if (!is_null($arr_obj_json[0]->lat) || !is_null($arr_obj_json[0]->lon)) {
					if (!is_null($this->getNro()) && $this->getNro() > 1000) {
						$ch = curl_init();
						$url = "https://nominatim.openstreetmap.org/reverse?lat=" . $arr_obj_json[0]->lat . "&lon=" . $arr_obj_json[0]->lon . "&format=jsonv2&city=rio+tercero&format=jsonv2&limit=1&email=martinmonnittola@gmail.com";
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$response = curl_exec($ch);
						$reverse_obj_json = json_decode($response);
						curl_close($ch);
						$address_number = $reverse_obj_json->address->house_number;
						if (abs($address_number -  $this->getNro()) > 100 ) {
							$ch = curl_init();
							$url = "https://api.tomtom.com/search/2/geocode/Cordoba,+Rio+Tercero," .  str_replace(" ", "+", trim($domicilio)) . ".json?storeResult=false&lat=-32.194998&lon=-64.1684546&radius=300000&view=Unified&key=Tj0CNZcoMipF9sVJ2GKE3LZ907yNogpt";
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$response = curl_exec($ch);
							$arr_obj_json = json_decode($response);
							curl_close($ch);
							if ($arr_obj_json) {
								if (!is_null($arr_obj_json->results[0]) || !is_null($arr_obj_json->results[0])) {
									$point = "POINT(" . $arr_obj_json->results[0]->position->lat . ", " . $arr_obj_json->results[0]->position->lon . ")";
									$this->Georeferencia = $point;
								} else {
									$this->Georeferencia = null;
								}
							} else {
								$this->Georeferencia = null;
							}

						} else {
							$point = "POINT(" . $arr_obj_json[0]->lat . ", " . $arr_obj_json[0]->lon . ")";
							$this->Georeferencia = $point;
						}
					} else {
						$point = "POINT(" . $arr_obj_json[0]->lat . ", " . $arr_obj_json[0]->lon . ")";
						$this->Georeferencia = $point;
					}
				} else {
					$this->Georeferencia = null;
				}
			} else {
				$this->Georeferencia = null;
			}
		}
	}
	$this->Domicilio = $domicilio;
	$con->CloseConexion();
}

public function setCalleNro($xDomicilio = null)
{
	$igual = true;
	$id_calle = (!$xDomicilio) ? $this->getId_Calle() : null;
	$numero_calle = (!$xDomicilio) ? trim($this->getNro()) : null;
	$domicilio = ($xDomicilio) ? $xDomicilio : null;
	$nombre_calle = null;

	$con = new Conexion();
	$con->OpenConexion();
	if (!is_null($id_calle)) {
		$nombre_calle = $this->getNombre_Calle();
		$domicilio = "$nombre_calle $numero_calle";
		$domicilio = str_replace(array('á','é','í','ó','ú','ñ'), array('a','e','i','o','u','n'), $domicilio);
	} else if ($domicilio) {
		$consulta = "select calle_open, id_calle
					 from calle
					 where lower(calle_nombre) like CONCAT(
															'%',
															REGEXP_REPLACE( 
																REGEXP_REPLACE( 
																		REGEXP_REPLACE(
																						REGEXP_SUBSTR(
																								lower('$domicilio'), 
																								'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)'
																						),
																						'( )+',
																						'%'
																						),
																				'(\\\\.)',
																				''
																			),
																			'S/N',
																			''
																		),
															'%'
															)
					    and estado = 1
					 order by calle_nombre asc;";
		$query_object = mysqli_query($con->Conexion, $consulta) or die("Error al consultar datos");

		$nro_calle = trim($domicilio);
		$out = null;
		$ret = null;
		if (preg_match('~ [0-9]+$~', $nro_calle, $out)) {
			$nro_calle = trim($out[0]);
			$igual = $igual && ($this->getNroCalle() == $nro_calle);
		} else {
			if (preg_match('~ [0-9]+ ([aA-zZ]|[0-9])+~', $nro_calle, $ret)) {
				$lista = explode(" ", trim($ret[0]));
				$nro_calle = trim($lista[0]);
				$igual = $igual && ($this->getNroCalle() == $nro_calle);
			} else {
				preg_match('~^[0-9]+$~', $nro_calle, $out);
				if (!empty($out[0])) {
					$nro_calle = trim($out[0]);
					$igual = $igual && ($this->getNro() == $nro_calle);
				} else {
					$nro_calle = null;
				}
			}
		}

		if (mysqli_num_rows($query_object) > 0) {
			$ret = mysqli_fetch_assoc($query_object);
			if ($ret["id_calle"] != $this->id_calle
				|| $nro_calle != $this->getNro()) {
				$nombre_calle = $ret["calle_open"];
				$this->id_calle = $ret["id_calle"];
				$domicilio = "$nombre_calle " . $this->getNro();
				$igual = false;
			}
		}
		$domicilio = str_replace(array('á','é','í','ó','ú','ñ'), array('a','e','i','o','u','n'), $domicilio);
	}

	$igual = $igual && !is_null($this->getGeoreferencia());
	$con->CloseConexion();
	return $igual;
}

public function setCalleNroConBarrio(
									$domicilio=null,
									$id_barrio=null,
									$coneccion=null
									)
{
	$igual = true;
	$id_calle = (!$domicilio) ? $this->getId_Calle() : null;
	$numero_calle = (!$domicilio) ? trim($this->getNro()) : null;
	$domicilio_info = ($domicilio) ? $domicilio : null;
	$nombre_calle = null;

	if (!is_null($id_calle)) {
		$nombre_calle = $this->getNombre_Calle();
		$domicilio_info = "$nombre_calle $numero_calle";
		$domicilio_info = str_replace(array('á','é','í','ó','ú','ñ'), array('a','e','i','o','u','n'), $domicilio_info);
	} else if ($domicilio_info) {
		$nro_calle = trim($domicilio_info);
		$out = null;
		$ret = null;
		if (preg_match('~ [0-9]+$~', $nro_calle, $out)) {
			$nro_calle = trim($out[0]);
			$igual = $igual && ($this->getNroCalle() == $nro_calle);
		} else {
			if (preg_match('~ [0-9]+ ([aA-zZ]|[0-9])+~', $nro_calle, $ret)) {
				$lista = explode(" ", trim($ret[0]));
				$nro_calle = trim($lista[0]);
				$igual = $igual && ($this->getNroCalle() == $nro_calle);
			} else {
				preg_match('~^[0-9]+$~', $nro_calle, $out);
				if (!empty($out[0])) {
					$nro_calle = trim($out[0]);
					$igual = $igual && ($this->getNro() == $nro_calle);
				} else {
					$nro_calle = null;
				}
			}
		}

		$calle_query = "";
		$barrio_query = "";
		if (!empty($nro_calle)) $calle_query = "AND $nro_calle BETWEEN cs.min_num AND cs.max_num";
		if (!empty($id_barrio)) $barrio_query = "AND id_barrio = $id_barrio";

		$consulta = "SELECT c.calle_open, c.id_calle
					 FROM calle  c INNER JOIN calles_barrios cs ON (c.id_calle = cs.id_calle)
					 WHERE lower(calle_nombre) LIKE CONCAT(
															'%',
															REGEXP_REPLACE( 
																REGEXP_REPLACE( 
																		REGEXP_REPLACE(
																						REGEXP_SUBSTR(
																								lower('$domicilio_info'), 
																								'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)'
																						),
																						'( )+',
																						'%'
																						),
																				'(\\\\.)',
																				''
																			),
																			'S/N',
																			''
																		),
															'%'
															)
					   $calle_query
					   $barrio_query
					   AND c.estado = 1
					   AND cs.estado = 1
					 ORDER BY c.calle_nombre ASC;";
		$query_object = mysqli_query($coneccion->Conexion, $consulta) or die("Error al consultar datos");

		if (mysqli_num_rows($query_object) > 0) {
			$ret = mysqli_fetch_assoc($query_object);
			if ($ret["id_calle"] != $this->id_calle
				|| $nro_calle != $this->getNro()) {
				$nombre_calle = $ret["calle_open"];
				$this->id_calle = $ret["id_calle"];
				$domicilio_info = "$nombre_calle " . $this->getNro();
				$igual = false;
			}
		}
		$domicilio_info = str_replace(array('á','é','í','ó','ú','ñ'), array('a','e','i','o','u','n'), $domicilio_info);
	}

	$igual = $igual && !is_null($this->getGeoreferencia());

	return $igual;
}

public function setCalle($xCalle)
{
	$this->id_calle = $xCalle;
}

public function setNro($xNro)
{
	$nro_calle = trim($xNro);
	$out = null;
	$ret = null;
	if (preg_match('~ [0-9]+$~', $nro_calle, $out)) {
		$nro_calle = trim($out[0]);
	} else {
		if (preg_match('~ [0-9]+ ([aA-zZ]|[0-9])+~', $nro_calle, $ret)) {
			$lista = explode(" ", trim($ret[0]));
			$nro_calle = trim($lista[0]);
		} else {
			preg_match('~^[0-9]+$~', $nro_calle, $out);
			if (!empty($out[0])) {
				$nro_calle = trim($out[0]);
			} else {
				$nro_calle = null;
			}
		}
	}
	$this->numero = ((!is_null($nro_calle)) ? $nro_calle : null);
}

public function setBarrio($xBarrio){
	$this->Barrio = $xBarrio;
}

public function setLocalidad($xLocalidad){
	$this->Localidad = $xLocalidad;
}

public function setCircunscripcion($xCircunscripcion){
	$this->Circunscripcion = $xCircunscripcion;
}

public function setSeccion($xSeccion){
	$this->Seccion = $xSeccion;
}

public function setManzana($xManzana){
	$this->Manzana = $xManzana;
}

public function setLote($xLote){
	$this->Lote = $xLote;
}

public function setFamilia($xFamilia){
	$this->Familia = $xFamilia;
}

public function setGeoreferencia($xGeoreferencia){
	$this->Georeferencia = $xGeoreferencia;
}

public function setCamio_Domicilio($xCambio_Domicilio){
	$this->Cambio_Domicilio = $xCambio_Domicilio;
}

public function setEstado($xEstado){
	$this->Estado = $xEstado;
}


//METODOS GET

public function get_id_domicilio()
{
    return $this->id_domicilio;
}

public function getId_Calle(){
	return $this->id_calle;
}

public function getNombre_Calle(){
	$con = new Conexion();
	$con->OpenConexion();
	$calle_open = null;
	if (!empty($this->getId_Calle())) {
		$consulta_calle = "select *
						   from calle 
						   where id_calle = " . $this->getId_Calle() . " 
							 and estado = 1";
		$result = mysqli_query($con->Conexion, $consulta_calle);
		if (!$result) {
			$mensaje_error_consultar = "No se pudo consultar la tabla calle";
			throw new Exception($mensaje_error_consultar . $consulta_calle, 2);
		}
		$result_row = mysqli_fetch_assoc($result);
		$calle_open = $result_row["calle_open"];
	}
	return $calle_open;
}

public function getNro(){
	return $this->numero;
}

public function getNroCalle()
{
	$LongString = strlen($this->Domicilio);
	if ($LongString > 3) {
	  $StringDelimitado = chunk_split($this->Domicilio,$LongString - 4,"-");
	  $PartesDireccion = explode("-", $StringDelimitado);
	  $NroDomActual = (int) filter_var($PartesDireccion[1], FILTER_SANITIZE_NUMBER_INT);
	  if ($NroDomActual == 0) {
		$NroDomActual = null;
	  }
	} else {
	  $NroDomActual = null;
	}
	return $NroDomActual;
}

public function getBarrio()
{
	if (!empty($this->Barrio)) {
		$Con = new Conexion();
		$Con->OpenConexion();
		$ConsultarBarrio = "select * 
							from barrios 
							where ID_Barrio = {$this->Barrio}";
		$MensajeErrorBarrio = "No se pudo consultar el Barrio de la persona";
		$EjecutarConsultarBarrio = mysqli_query($Con->Conexion,$ConsultarBarrio) or die($MensajeErrorBarrio);
		$RetBarrio = mysqli_fetch_assoc($EjecutarConsultarBarrio);
		$barrio = $RetBarrio["Barrio"];
		$Con->CloseConexion();
	} else {
		$barrio = null;
	}
	return $barrio;
}

public function getId_Barrio()
{
	return $this->Barrio;
}

public function getLocalidad()
{
	return $this->Localidad;
}

public function getCircunscripcion()
{
	return $this->Circunscripcion;
}

public function getSeccion()
{
	return $this->Seccion;
}

public function getManzana()
{
	return $this->Manzana;
}

public function getLote()
{
	return $this->Lote;
}

public function getFamilia()
{
	return $this->Familia;
}

public function getGeoreferencia()
{
	return $this->Georeferencia;
}

public function getLonguitud()
{
	$con = new Conexion();
	$con->OpenConexion();
	$consulta = "select p.*,
						ST_Y(p.georeferencia) as lon
				 from domicilios p
				 where id_domicilio = " . $this->get_id_domicilio();
	$query_object = mysqli_query($con->Conexion, $consulta) or die("Error al consultar datos");
	$ret = mysqli_fetch_assoc($query_object);
	return $ret["lon"];
}
public function getLatitud()
{
	$con = new Conexion();
	$con->OpenConexion();
	$consulta = "select p.*,
						ST_X(p.georeferencia) as lat
				 from domicilios p
				 where id_domicilio  = " . $this->get_id_domicilio();
	$query_object = mysqli_query($con->Conexion, $consulta) or die("Error al consultar datos");
	$ret = mysqli_fetch_assoc($query_object);
	return $ret["lat"];
}


public function getCambio_Domicilio()
{
	return $this->Cambio_Domicilio;
}


public function getEstado()
{
	return $this->Estado;
}

public function igual_domicilio($domicilio){
	$con = new Conexion();
	$con->OpenConexion();
	$igual = true;
	$consulta = "select calle_open, id_calle
				 from calle
				 where lower(calle_nombre) like CONCAT(
				 									'%',
				 									REGEXP_REPLACE( 
				 											REGEXP_REPLACE(
				 															REGEXP_SUBSTR(
				 																	lower('$domicilio'), 
				 																	'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)'
				 															),
				 															'( )+',
				 															'%'
				 															),
				 													'(\\\\.)',
				 													''
				  													),
				 									'%'
				 									)
				 and estado = 1
				 order by calle_nombre asc;";
	$query_object = mysqli_query($con->Conexion, $consulta) or die("Error al consultar datos");

	$nro_calle = trim($domicilio);
	$out = null;
	$ret = null;
	if (preg_match('~ [0-9]+$~', $nro_calle, $out)) {
		$nro_calle = trim($out[0]);
	} else {
		if (preg_match('~ [0-9]+ ([aA-zZ]|[0-9])+~', $nro_calle, $ret)) {
			$lista = explode(" ", trim($ret[0]));
			$nro_calle = trim($lista[0]);
		} else {
			preg_match('~^[0-9]+$~', $nro_calle, $out);
			if (!empty($out[0])) {
				$nro_calle = trim($out[0]);
			} else {
				$nro_calle = null;
			}
		}
	}

	if (mysqli_num_rows($query_object) > 0) {
		$id_calle = (empty($ret["calle_open"])) ? 0 : $ret["calle_open"];
		if ($id_calle != $this->getId_Calle()
			|| $this->getNro() != $nro_calle) {
			$igual = false;
		}
	}
	return $igual;
}


public function igual_calle($domicilio){
	$con = new Conexion();
	$con->OpenConexion();
	$igual = true;
	$consulta = "select calle_open, id_calle
				 from calle
				 where lower(calle_nombre) like CONCAT(
				 									'%',
				 									REGEXP_REPLACE( 
				 											REGEXP_REPLACE(
				 															REGEXP_SUBSTR(
				 																	lower('$domicilio'), 
				 																	'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)'
				 															),
				 															'( )+',
				 															'%'
				 															),
				 													'(\\\\.)',
				 													''
				  													),
				 									'%'
				 									)
				 and estado = 1
				 order by calle_nombre asc;";
	$query_object = mysqli_query($con->Conexion, $consulta) or die("Error al consultar datos");
	$ret = mysqli_fetch_assoc($query_object);
	if (mysqli_num_rows($query_object) > 0) {
		$id_calle = (empty($ret["calle_open"])) ? 0 : $ret["calle_open"];
		if ($id_calle != $this->getId_Calle()) {
			$igual = false;
		}
	}
	return $igual;
}

public static function is_exist($coneccion, $id_domicilio )
{
	$consulta = "select * 
				 from domicilios 
				 where id_domicilio  = $id_domicilio  
				   and estado = 1";
	$mensaje_error = "Hubo un problema al consultar los registros para validar";
	$ret = mysqli_query(
				$coneccion->Conexion,
				$consulta
	) or die(
		$mensaje_error
	);
	$is_multiple = (mysqli_num_rows($ret) >= 1);
	return $is_multiple;
}

public static function is_registered($coneccion, $id_calle, $numero)
{
	$ConsRegistrosIguales = "select id_domicilio  
							 from domicilios 
							 where id_calle = $id_calle
                                 and numero = $numero
                                 and estado = 1";
	$MensajeErrorRegistrosIguales = "Hubo un problema al consultar los registros para validar";
	$ret = mysqli_query($coneccion->Conexion,
		$ConsRegistrosIguales
	) or die(
		$MensajeErrorRegistrosIguales . " Consulta: " . $ConsRegistrosIguales
	);
	$exist = (mysqli_num_rows($ret) >= 1);
	return $exist;
}

public function jsonSerialize() {
	return [
	'Barrio' => $this->Barrio,
	'Localidad' => $this->Localidad,
	'Circunscripcion' => $this->Circunscripcion,
	'Seccion' => $this->Seccion,
	'Manzana' => $this->Manzana,
	'Lote' => $this->Lote,
	'Familia' => $this->Familia,
	'Cambio_Domicilio' => $this->Cambio_Domicilio,

	'Estado' => $this->Estado,
	'Georeferencia' => $this->Georeferencia,
	'Nro' => $this->numero,
	'Calle' => $this->id_calle
	];
}

public function update_geo()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$Consulta = "update domicilios 
				 set georeferencia = " . ((!is_null($this->getGeoreferencia())) ? $this->getGeoreferencia() : "null") . " 
				 where id_domicilio  = " . $this->get_id_domicilio();
				 $MensajeErrorConsultar = "No se pudo actualizar la Persona ";
				 if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
					throw new Exception($MensajeErrorConsultar . $Consulta, 2);
				}
				 $Con->CloseConexion();
}

public function update_calle()
{
	$con = new Conexion();
	$con->OpenConexion();
	$consulta = "update domicilios 
				 set id_calle = " . ((!is_null($this->getId_Calle())) ? $this->getId_Calle() : "null") . " 
				 where id_domicilio  = " . $this->get_id_domicilio();
	$mensaje_error_consultar = "No se pudo actualizar la Persona ";
	if (!$Ret = mysqli_query($con->Conexion, $consulta)) {
		throw new Exception($mensaje_error_consultar . $consulta, 2);
	}
	$con->CloseConexion();
}

public function update_nro()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$Consulta = "update domicilios 
				 set numero = " . ((!is_null($this->getNro())) ? $this->getNro() : "null") . " 
				 where id_domicilio  = " . $this->get_id_domicilio();
				 $MensajeErrorConsultar = "No se pudo actualizar la Persona ";
				 if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
					throw new Exception($MensajeErrorConsultar . $Consulta, 2);
				}
				 $Con->CloseConexion();
}

public function update_familia()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$Consulta = "update domicilios 
				 set familia = " . ((!is_null($this->getFamilia())) ? intval($this->getFamilia()) : "null") . " 
				 where id_domicilio  = " . $this->get_id_domicilio();
				 $MensajeErrorConsultar = "No se pudo actualizar la Persona ";
				 if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
					throw new Exception($MensajeErrorConsultar . $Consulta, 2);
				}
				 $Con->CloseConexion();
}


public function update_barrio()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$Consulta = "update domicilios 
				 set ID_Barrio = " . ((!is_null($this->getId_Barrio())) ? $this->getId_Barrio() : "null") . " 
				 where id_domicilio  = " . $this->get_id_domicilio();
				 $MensajeErrorConsultar = "No se pudo actualizar la Persona ";
				 if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
					throw new Exception($MensajeErrorConsultar . $Consulta, 2);
				}
				 $Con->CloseConexion();
}

public function update_direccion()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$Consulta = "update domicilios 
				 set georeferencia = " . ((!is_null($this->getGeoreferencia())) ? $this->getGeoreferencia() : "null") . ", 
					 calle = " . ((!is_null($this->getId_Calle())) ? $this->getId_Calle() : "null") . ", 
					 numero = " . ((!is_null($this->getNro())) ? $this->getNro() : "null") . ", 
					 familia = " . ((!is_null($this->getFamilia())) ? intval($this->getFamilia()) : "null") . ",
					 ID_Barrio = " . ((!is_null($this->getId_Barrio())) ? $this->getId_Barrio() : "null") . "
				 where id_domicilio  = " . $this->get_id_domicilio();
	$mensaje_error_consulta = "No se pudo actualizar la Persona";
	if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
		throw new Exception($mensaje_error_consulta . $Consulta, 2);
	}
	$Con->CloseConexion();
}

public function update()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$Consulta = "update domicilios 
				 set 
					 id_barrio = " . ((!is_null($this->getId_Barrio())) ? "'" . $this->getId_Barrio() . "'" : "null") . ", 
					 localidad = " . ((!is_null($this->getLocalidad())) ? "'" . $this->getLocalidad() . "'" : "null") . ", 
					 circunscripcion = " . ((!is_null($this->getCircunscripcion())) ? "'" . $this->getCircunscripcion() . "'" : "null") . ", 
					 seccion = " . ((!is_null($this->getSeccion())) ? "'" . $this->getSeccion() . "'" : "null") . ", 
					 manzana = " . ((!is_null($this->getManzana())) ? "'" . $this->getManzana() . "'" : "null") . ", 
					 lote = " . ((!is_null($this->getLote())) ? $this->getLote() : "null") . ", 
					 familia = " . ((!is_null($this->getFamilia())) ? $this->getFamilia() : "null") . ", 
					 cambio_domicilio = " . ((!is_null($this->getCambio_Domicilio())) ? "'" . $this->getCambio_Domicilio() . "'" : "null") . ", 
					 georeferencia = " . ((!is_null($this->getGeoreferencia())) ? $this->getGeoreferencia() : "null") . ", 
					 id_calle = " . ((!is_null($this->getId_Calle())) ? $this->getId_Calle() : "null") . ", 
					 numero = " . ((!is_null($this->getNro())) ? $this->getNro() : "null") . " 
				 where id_domicilio  = " . $this->get_id_domicilio();
				 $MensajeErrorConsultar = "No se pudo actualizar la Persona";
				 if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
					throw new Exception($MensajeErrorConsultar . $Consulta, 2);
				}
				 $Con->CloseConexion();
}

public function save(){
	$Con = new Conexion();
	$Con->OpenConexion();
	$consulta = "INSERT INTO domicilios (
									  id_barrio, 
									  localidad, 
									  circunscripcion, 
									  seccion,
									  manzana, 
									  lote, 
									  familia, 
									  cambio_domicilio,
									  georeferencia,
									  id_calle,
									  numero, 
									  estado 
				 )
				 VALUES ( " . ((!is_null($this->getId_Barrio())) ? $this->getId_Barrio() : "null") . ", 
						 " . ((!is_null($this->getLocalidad())) ? "'" . $this->getLocalidad() . "'" : "null") . ", 
						 " . ((!is_null($this->getCircunscripcion())) ? $this->getCircunscripcion() : "null") . ", 
						 " . ((!is_null($this->getSeccion())) ? $this->getSeccion() : "null") . ", 
						 " . ((!is_null($this->getManzana())) ? "'" . $this->getManzana() . "'" : "null") . ", 
						 " . ((!is_null($this->getLote())) ? $this->getLote() : "null") . ", 
						 " . ((!is_null($this->getFamilia())) ? $this->getFamilia() : "null") . ", 
						 " . ((!is_null($this->getCambio_Domicilio())) ? "'" . $this->getCambio_Domicilio() . "'" : "null") . ", 
						 " . ((!is_null($this->getGeoreferencia())) ? $this->getGeoreferencia() : "null") . ",
						 " . ((!empty($this->getId_Calle())) ? $this->getId_Calle() : "null") . ",
						 " . ((!empty($this->getNro())) ? $this->getNro() : "null") . ",
						 1
				 )";
				 $MensajeErrorConsultar = "No se pudo insertar la Persona";
				 $ret = mysqli_query($Con->Conexion, $consulta);
				 if (!$ret) {
					throw new Exception($MensajeErrorConsultar . $consulta, 2);
				 }
				 $this->id_domicilio= mysqli_insert_id($Con->Conexion);
				 $Con->CloseConexion();
}

	function delete()
	{
		$Con = new Conexion();
		$Con->OpenConexion();

		$query = "update domicilios
				  set estado = 0
				  where id_domicilio = " . $this->get_id_domicilio();
		$MensajeErrorConsultar = "No se pudo insertar la Persona";
		$ret = mysqli_query($Con->Conexion, $query);
		if (!$ret) {
		throw new Exception($MensajeErrorConsultar . $query, 2);
		}
		$Con->CloseConexion();

	}
}
