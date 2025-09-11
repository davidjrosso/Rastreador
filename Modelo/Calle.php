<?php  
class Calle {
	// DECLARACION DE VARIABLES
	private $id_calle;
	private $codigo_calle;
	private $calle_abreviado;
	private $estado;
	private $calle_open;
	private $calle_nombre;
	private $geocoder;

	public function __construct(
		$calle_nombre = null,
		$codigo_calle = null,
		$id_calle = null,
		$calle_open = null,
		$calle_abreviado = null,
		$geocoder = null,
		$estado = null
	){

		if (!$id_calle) {
			$this->id_calle = $id_calle;
			$this->calle_abreviado = $calle_abreviado;
			$this->calle_open = $calle_open;
			$this->codigo_calle = $codigo_calle;
			$this->calle_nombre = $calle_nombre;
			$this->estado = $estado;
			$this->geocoder = $geocoder;
		} else {
			$Con = new Conexion();
			$Con->OpenConexion();
			$consultar = "select *
						from calle 
						where id_calle = $id_calle
							and estado = 1
						order by calle_nombre ASC";
			$ejecutar_consultar_calle = mysqli_query(
				$Con->Conexion, 
				$consultar) or die("Problemas al consultar filtro Calle");
			if (!$ejecutar_consultar_calle) {
				throw new Exception("Problemas al intentar Consultar Registros de Calle", 0);
			}
			$ret = mysqli_fetch_assoc($ejecutar_consultar_calle);

			$id_calle = $ret["id_calle"];
			$codigo_calle = $ret["codigo_calle"];
			$calle_nombre = $ret["calle_nombre"];
			$calle_open = $ret["calle_open"];
			$estado = $ret["estado"];
			$calle_abreviado = $ret["calle_abreviado"];
			$geocoder = $ret["geocoder"];

			$this->id_calle = $id_calle;
			$this->codigo_calle = $codigo_calle;
			$this->calle_open = $calle_open;
			$this->calle_nombre = $calle_nombre;
			$this->estado = $estado;
			$this->calle_abreviado = $calle_abreviado;
			$this->geocoder = $geocoder;

			$Con->CloseConexion();
		}
	}

	public static function existe_calle($xDomicilio = null)
	{
		$calle = null;
		$con = new Conexion();
		$con->OpenConexion();
		if ($xDomicilio) {
			$consulta = "select *
						 from calle
						 where lower(calle_nombre) like CONCAT(
																'%',
																REGEXP_REPLACE( 
																		REGEXP_REPLACE(
																						REGEXP_SUBSTR(
																								lower('$xDomicilio'), 
																								'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)|cortadero( )*[()a-zA-Zá-úÁ-Ú ]+'
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
			if (mysqli_num_rows($query_object) > 0) {
				$ret = mysqli_fetch_assoc($query_object);
				$calle = $ret["calle_nombre"];
			};
		}
		$con->CloseConexion();
		return $calle;
	}

	public static function existe_id_calle($id_calle=null, $connection=null)
	{
		if ($id_calle) {
			$consulta = "select *
						 from calle
						 where id_calle = $id_calle
						   and estado = 1
						 order by calle_nombre asc;";
			$query_object = mysqli_query(
								  $connection->Conexion, 
								  $consulta
								  	   ) or die("Error al consultar datos");
			if (mysqli_num_rows($query_object) > 0) {
				$existe_calle = true;
			};
		}
		return $existe_calle;
	}

	public static function existe_calle_con_barrio_nro(
														$calle=null,
														$id_bario=null,
														$nro_calle=null,
														$connection=null
														)
	{
		$calle = null;
		if ($calle && $id_bario && $nro_calle) {
			$consulta = "SELECT *
						 FROM calle c INNER JOIN calles_barrios cs ON (c.id_calle = cs.id_calle)
						 WHERE lower(calle_nombre) LIKE CONCAT(
																'%',
																REGEXP_REPLACE( 
																		REGEXP_REPLACE(
																						REGEXP_SUBSTR(
																								lower('$calle'), 
																								'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)|cortadero( )*[()a-zA-Zá-úÁ-Ú ]+'
																						),
																						'( )+',
																						'%'
																						),
																				'(\\\\.)',
																				''
																				),
																'%'
																)
						  AND $nro_calle BETWEEN cs.min_num AND cs.max_num
						  AND id_barrio = $id_bario
						  AND c.estado = 1
						  AND cs.estado = 1
						 ORDER BY c.calle_nombre ASC;";
			$query_object = mysqli_query($connection->Conexion, $consulta) or die("Error al consultar datos");
			if (mysqli_num_rows($query_object) > 0) {
				$ret = mysqli_fetch_assoc($query_object);
				$calle = $ret["calle_nombre"];
			};
		}
		return $calle;
	}


	public static function existe_calle_con_barrio(
													$domicilio=null,
													$id_bario=null,
													$connection=null
												  )
	{
		$calle = null;
		$nro_calle = null;

		if ($domicilio) {
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
		}

		if ($domicilio && $id_bario && $nro_calle) {
			$consulta = "SELECT *
						 FROM calle c INNER JOIN calles_barrios cs ON (c.id_calle = cs.id_calle)
						 WHERE lower(calle_nombre) LIKE CONCAT(
																'%',
																REGEXP_REPLACE( 
																		REGEXP_REPLACE(
																						REGEXP_SUBSTR(
																								lower('$domicilio'), 
																								'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)|cortadero( )*[()a-zA-Zá-úÁ-Ú ]+'
																						),
																						'( )+',
																						'%'
																						),
																				'(\\\\.)',
																				''
																				),
																'%'
																)
						  AND $nro_calle BETWEEN cs.min_num AND cs.max_num
						  AND id_barrio = $id_bario
						  AND c.estado = 1
						  AND cs.estado = 1
						 ORDER BY c.calle_nombre ASC;";
			$query_object = mysqli_query($connection->Conexion, $consulta) or die("Error al consultar datos");
			if (mysqli_num_rows($query_object) > 0) {
				$ret = mysqli_fetch_assoc($query_object);
				$calle = $ret["calle_nombre"];
			};
		} else if ($domicilio && $id_bario && !$nro_calle) {
			$consulta = "SELECT *
						 FROM calle c INNER JOIN calles_barrios cs ON (c.id_calle = cs.id_calle)
						 WHERE lower(calle_nombre) LIKE IF(lower('$domicilio') LIKE '%cortadero%',
						 								   CONCAT(
																'%',
																REGEXP_REPLACE( 
																		REGEXP_REPLACE(
																						REGEXP_SUBSTR(
																									  lower('$domicilio'), 
																									  'cortadero( )*[()a-zA-Zá-úÁ-Ú ]+'
																									  ),
																						'( )+',
																						'%'
																						),
																				'(\\\\.)',
																				''
																				),
																'%'
																),
						 									CONCAT(
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
															)
						  AND id_barrio = $id_bario
						  AND c.estado = 1
						  AND cs.estado = 1
						 ORDER BY c.calle_nombre ASC;";
			$query_object = mysqli_query($connection->Conexion, $consulta) or die("Error al consultar datos");
			if (mysqli_num_rows($query_object) > 0) {
				$ret = mysqli_fetch_assoc($query_object);
				$calle = $ret["calle_nombre"];
			};
		}
		return $calle;
	}

	public static function get_id_by_nombre($xDomicilio = null)
	{
		$calle = null;
		$con = new Conexion();
		$con->OpenConexion();
		if ($xDomicilio) {
			$consulta = "select *
						 from calle
						 where lower(calle_nombre) like CONCAT(
																'%',
																REGEXP_REPLACE( 
																		REGEXP_REPLACE(
																						REGEXP_SUBSTR(
																								lower('$xDomicilio'), 
																								'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)|cortadero( )*[()a-zA-Zá-úÁ-Ú ]+'
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
			if (mysqli_num_rows($query_object) > 0) {
				$ret = mysqli_fetch_assoc($query_object);
				$calle = $ret["id_calle"];
			};
		}
		$con->CloseConexion();
		return $calle;
	}

	public static function get_id_by_nombre_barrio(
													$domicilio=null,
													$id_bario=null,
													$connection=null
												  )
	{
		$calle = null;
		$nro_calle = null;

		if ($domicilio) {
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
		}

		if ($domicilio && $id_bario && $nro_calle) {
			$consulta = "SELECT *
						 FROM calle c INNER JOIN calles_barrios cs ON (c.id_calle = cs.id_calle)
						 WHERE lower(calle_nombre) LIKE CONCAT(
																'%',
																REGEXP_REPLACE( 
																		REGEXP_REPLACE(
																						REGEXP_SUBSTR(
																								lower('$domicilio'), 
																								'([1-9]+( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*( )+[a-zA-Zá-úÁ-Ú]+(\\\\.)*)|([a-zA-Zá-úÁ-Ú]+(\\\\.)*)|cortadero( )*[()a-zA-Zá-úÁ-Ú ]+'
																						),
																						'( )+',
																						'%'
																						),
																				'(\\\\.)',
																				''
																				),
																'%'
																)
						  AND $nro_calle BETWEEN cs.min_num AND cs.max_num
						  AND id_barrio = $id_bario
						  AND c.estado = 1
						  AND cs.estado = 1
						 ORDER BY c.calle_nombre ASC;";
			$query_object = mysqli_query($connection->Conexion, $consulta) or die("Error al consultar datos");
			if (mysqli_num_rows($query_object) > 0) {
				$ret = mysqli_fetch_assoc($query_object);
				$calle = $ret["id_calle"];
			};
		} else if ($domicilio && $id_bario && !$nro_calle) {
			$consulta = "SELECT *
						 FROM calle c INNER JOIN calles_barrios cs ON (c.id_calle = cs.id_calle)
						 WHERE lower(calle_nombre) LIKE IF(lower('$domicilio') LIKE '%cortadero%',
						 								   CONCAT(
																'%',
																REGEXP_REPLACE( 
																		REGEXP_REPLACE(
																						REGEXP_SUBSTR(
																									  lower('$domicilio'), 
																									  'cortadero( )*[()a-zA-Zá-úÁ-Ú ]+'
																									  ),
																						'( )+',
																						'%'
																						),
																				'(\\\\.)',
																				''
																				),
																'%'
																),
						 								   CONCAT(
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
															)
						  AND id_barrio = $id_bario
						  AND c.estado = 1
						  AND cs.estado = 1
						 ORDER BY c.calle_nombre ASC;";
			$query_object = mysqli_query($connection->Conexion, $consulta) or die("Error al consultar datos");
			if (mysqli_num_rows($query_object) > 0) {
				$ret = mysqli_fetch_assoc($query_object);
				$calle = $ret["id_calle"];
			};
		}
		return $calle;
	}

	// METODOS SET
	public function set_id_calle($id_calle){
		$this->id_calle = $id_calle;
	}

	public function set_codigo_calle($codigo_calle){
		$this->codigo_calle = $codigo_calle;
	}

	public function set_calle_abreviado($calle_abreviado){
		$this->calle_abreviado = $calle_abreviado;
	}

	public function set_estado($estado){
		$this->estado = $estado;
	}

	public function set_calle_open($calle_open){
		$this->calle_open = $calle_open;
	}

	public function set_calle_nombre($calle_nombre){
		$this->calle_nombre = $calle_nombre;
	}

	public function set_geocoder($geocoder){
		$this->geocoder = $geocoder;
	}

	// METODOS GET
	public function get_id_calle(){
		return $this->id_calle;
	}

	public function get_codigo_calle(){
		return $this->codigo_calle;
	}

	public function get_calle_abreviado(){
		return $this->calle_abreviado;
	}

	public function get_estado(){
		return $this->estado;
	}

	public function get_calle_open(){
		return $this->calle_open;
	}

	public function get_calle_nombre(){
		return $this->calle_nombre;
	}

	public function get_geocoder(){
		return $this->geocoder;
	}

	public function update(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "update calle 
					set calle_nombre = " . ((!is_null($this->get_calle_nombre())) ? "'" . $this->get_calle_nombre() . "'" : "null") . ", 
						codigo_calle = " . ((!is_null($this->get_codigo_calle())) ? "'" . $this->get_codigo_calle() . "'" : "null") . ", 
						id_calle = " . ((!is_null($this->get_id_calle())) ? "'" . $this->get_id_calle() . "'" : "null") . ", 
						calle_abreviado = " . ((!is_null($this->get_calle_abreviado())) ? "'" . $this->get_calle_abreviado() . "'" : "null") . ", 
						estado = " . ((!is_null($this->get_estado())) ? "'" . $this->get_estado() . "'" : "null") . ", 
						calle_open = " . ((!is_null($this->get_calle_open())) ? "'" . $this->get_calle_open() . "'" : "null") . ",
						geocoder = " . ((!is_null($this->get_geocoder())) ? "'" . $this->get_geocoder() . "'" : "null") . "
					where id_calle = " . $this->get_id_calle();
					$MensajeErrorConsultar = "No se pudo actualizar la Calle";
		if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
			throw new Exception($MensajeErrorConsultar . $Consulta, 2);
		}
		$Con->CloseConexion();
	}
}
