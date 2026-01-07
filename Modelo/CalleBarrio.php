<?php  
class CalleBarrio 
{
	// DECLARACION DE VARIABLES
	private $id_geo;
	private $id_calle;
	private $max_num;
	private $min_num;
    private $punto_max_num;
    private $punto_min_num;
	private $pendiente;
	private $punto;
	private $offset_calle;
    private $figura;
	private $id_barrio;
	private $estado;

	public function __construct(
		$id_geo=null,
		$max_num=null,
		$id_calle=null,
		$min_num=null,
        $punto_max_num=null,
        $punto_min_num=null,
        $pendiente=null,
		$punto=null,
		$offset_calle=null,
        $figura=null,
        $id_barrio=null,
		$estado=null,
        $connection=null
	){

		if (!$id_geo) {
			$this->id_geo = $id_geo;
			$this->id_calle = $id_calle;
			$this->max_num = $max_num;
			$this->min_num = $min_num;
            $this->punto_max_num = $punto_max_num;
            $this->punto_min_num = $punto_min_num;
            $this->pendiente = $pendiente;
			$this->punto = $punto;
			$this->offset_calle = (!empty($offset_calle)) ? $offset_calle : 1;
			$this->figura = $figura;
			$this->id_barrio = $id_barrio;
			$this->estado = $estado;
		} else {
			$consultar = "select geo.*,
                                 ST_X(geo.pendiente) as lat_pendiente,
                                 ST_Y(geo.pendiente) as lon_pendiente,
                                 ST_X(geo.punto_max_num) as lat_point_max_num,
                                 ST_Y(geo.punto_max_num) as lon_point_max_num,
                                 ST_X(geo.punto_min_num) as lat_point_min_num,
                                 ST_Y(geo.punto_min_num) as lon_point_min_num,
                                 ST_X(geo.punto) as lat_punto,
                                 ST_Y(geo.punto) as lon_punto
						from calles_barrios geo
						where id_geo = $id_geo
							and estado = 1;";
			$ejecutar_consultar = mysqli_query(
				$connection->Conexion, 
				$consultar) or die("Problemas al consultar filtro Calle");
			if (!$ejecutar_consultar) {
				throw new Exception("Problemas al intentar consultar registros de Georeferencias", 0);
			}
			$ret = mysqli_fetch_assoc($ejecutar_consultar);

			$row_id_geo = $ret["id_geo"];
			$row_id_calle = $ret["id_calle"];
			$row_max_num = $ret["max_num"];
			$row_min_num = $ret["min_num"];
			$row_punto = "POINT(" . $ret["lat_punto"] . "," . $ret["lon_punto"]  . ")";
			$row_punto_max_num = "POINT(" . $ret["lat_point_max_num"] . "," . $ret["lon_point_max_num"]  . ")";
			$row_punto_min_num = "POINT(" . $ret["lat_point_min_num"] . "," . $ret["lon_point_min_num"]  . ")";
			$row_pendiente = "POINT(" . $ret["lat_pendiente"] . "," . $ret["lon_pendiente"]  . ")";
			$row_offset_calle = (!empty($ret["offset_calle"])) ? $ret["offset_calle"] : 1;
			$row_figura = $ret["figura"];
			$row_id_barrio = $ret["ID_Barrio"];
			$row_estado = $ret["estado"];

			$this->id_geo = $row_id_geo;
			$this->id_calle = $row_id_calle;
			$this->max_num = $row_max_num;
			$this->min_num = $row_min_num;
			$this->punto_max_num = $row_punto_max_num;
			$this->punto_min_num = $row_punto_min_num;
			$this->pendiente = $row_pendiente;
			$this->punto = $row_punto;
			$this->offset_calle = $row_offset_calle;
			$this->figura = $row_figura;
            $this->id_barrio = $row_id_barrio;
			$this->estado = $row_estado;

		}
	}

	public static function existe_georeferencia($id_calle, $num_calle, $connection)
	{
		$id_geo = null;
		if ($id_calle && $num_calle) {
			$consulta = "SELECT *
						 FROM calles_barrios
						 WHERE id_calle = $id_calle
                           AND ($num_calle BETWEEN min_num AND max_num
						   		OR (min_num = 0 AND max_num = 0))
						   AND estado = 1;";
			$query_object = mysqli_query($connection->Conexion, $consulta) or die("Error al consultar datos");
			if (mysqli_num_rows($query_object) > 0) {
				$ret = mysqli_fetch_assoc($query_object);
				$id_geo = $ret["id_geo"];
			}
		} else if ($id_calle && !$num_calle) {
			$consulta = "SELECT *
						 FROM calles_barrios
						 WHERE id_calle = $id_calle
                           AND min_num = 0
						   AND max_num = 0
						   AND estado = 1;";
			$query_object = mysqli_query($connection->Conexion, $consulta) or die("Error al consultar datos");
			if (mysqli_num_rows($query_object) > 0) {
				$ret = mysqli_fetch_assoc($query_object);
				$id_geo = $ret["id_geo"];
			}
		}
		return $id_geo;
	}

	// METODOS SET
	public function set_id_calle($id_calle)
    {
		$this->id_calle = $id_calle;
	}

	public function set_id_geo($id_geo)
    {
		$this->id_geo = $id_geo;
	}

	public function set_max_num($max_num)
    {
		$this->max_num = $max_num;
	}

	public function set_estado($estado)
    {
		$this->estado = $estado;
	}

	public function set_min_num($min_num)
    {
		$this->min_num = $min_num;
	}

    public function set_punto_max_num($punto_max_num)
    {
		$this->punto_max_num = $punto_max_num;
	}

    public function set_punto_min_num($punto_min_num)
    {
		$this->punto_min_num = $punto_min_num;
	}

	public function set_pendiente($pendiente)
    {
		$this->pendiente = $pendiente;
	}

	public function set_pendiente_lat_lon($lat, $lon)
    {
		$this->pendiente = "POINT(" . trim($lat) . "," . trim($lon) . ")";
	}

	public function set_pendiente_by_min_max_punto()
    {
        $punto_max_num_lat = floatval($this->get_lat_punto_max_num());
        $punto_max_num_lon = floatval($this->get_lon_punto_max_num());
        $punto_min_num_lat = floatval($this->get_lat_punto_min_num());
        $punto_min_num_lon = floatval($this->get_lon_punto_min_num());
        $max_num = floatval($this->get_max_num());
        $min_num = floatval($this->get_min_num());
		if ($max_num && $min_num) {
			$pendiente_lat = ($punto_max_num_lat - $punto_min_num_lat)/($max_num - $min_num);
			$pendiente_lon = ($punto_max_num_lon - $punto_min_num_lon)/($max_num - $min_num);
			$this->pendiente = "POINT($pendiente_lat,$pendiente_lon)";
		}
	}

	public function set_punto($punto)
    {
		$this->punto = $punto;
	}

	public function set_offset_calle($offset_calle)
    {
		$this->offset_calle = $offset_calle;
	}

	public function set_punto_lat_lon($lan, $lon)
    {
		$this->punto = "POINT($lan, $lon)";
	}

	public function set_figura($figura)
    {
		$this->figura = $figura;
	}

	public function set_id_barrio($id_barrio)
    {
		$this->id_barrio = $id_barrio;
	}

	// METODOS GET
	public function get_id_calle()
    {
		return $this->id_calle;
	}

	public function get_id_geo()
    {
		return $this->id_geo;
	}

	public function get_max_num()
    {
		return $this->max_num;
	}

	public function get_estado()
    {
		return $this->estado;
	}

	public function get_min_num()
    {
		return $this->min_num;
	}

	public function get_punto_max_num()
    {
		return $this->punto_max_num;
	}

	public function get_lat_punto_max_num()
    {
        $lat_str = array();
		$expr_reg = "~([ ]*[-]{0,1}[0-9]+\.[0-9]+[ ]*,|[ ]*[-]{0,1}[0-9]+\.[0-9]+E[-]{0,1}[0-9]+[ ]*,)~";
		$punto_max_num_dato = str_replace(" ", "", $this->punto_max_num);
        $lat = substr($punto_max_num_dato, 6);
        $lat = substr($lat, 0, -1);
		$check = preg_match($expr_reg, $lat, $lat_str);
        $lat = substr($lat_str[0], 0, -1);
		return  $lat;
	}

	public function get_lon_punto_max_num()
    {
        $lon_str = array();
		$expr_reg = "~(,[ ]*[-]{0,1}[0-9]+\.[0-9]+[ ]*|,[ ]*[-]{0,1}[0-9]+\.[0-9]+E[-]{0,1}[0-9]+[ ]*)~";
		$punto_max_num_dato = str_replace(" ", "", $this->punto_max_num);
        $lon = substr($punto_max_num_dato, 6);
        $lon_str = substr($lon, 0, -1);
		$check = preg_match($expr_reg , $lon, $lon_str);
        $lon = substr($lon_str[0], 1);
		return  $lon;
	}

	public function get_punto_min_num()
    {
		return $this->punto_min_num;
	}

	public function get_lat_punto_min_num()
    {
        $lat_str = array();
		$expr_reg = "~([ ]*[-]{0,1}[0-9]+\.[0-9]+[ ]*,|[ ]*[-]{0,1}[0-9]+\.[0-9]+E[-]{0,1}[0-9]+[ ]*,)~";
		$punto_min_num_dato = str_replace(" ", "", $this->punto_min_num);
        $lat = substr($punto_min_num_dato, 6);
        $lat = substr($lat, 0, -1);
		$check = preg_match($expr_reg, $lat, $lat_str);
        $lat = substr($lat_str[0], 0, -1);
		return  $lat;
	}

	public function get_lon_punto_min_num()
    {
        $lon_str = array();
		$expr_reg = "~(,[ ]*[-]{0,1}[0-9]+\.[0-9]+[ ]*|,[ ]*[-]{0,1}[0-9]+\.[0-9]+E[-]{0,1}[0-9]+[ ]*)~";
		$punto_min_num_dato = str_replace(" ", "", $this->punto_min_num);
        $lon = substr($punto_min_num_dato, 6);
        $lon_str = substr($lon, 0, -1);
		$check = preg_match($expr_reg , $lon, $lon_str);
        $lon = substr($lon_str[0], 1);
		return  $lon;
	}

	public function get_pendiente()
    {
		return $this->pendiente;
	}

	public function get_pendiente_lat()
    {
        $lat_str = array();
		$expr_reg = "~([ ]*[-]{0,1}[0-9]+\.[0-9]+[ ]*,|[-]{0,1}[0-9]+\.[0-9]+E[-]{0,1}[0-9]+[ ]*,)~";
		$pendiente_dato = str_replace(" ", "", $this->pendiente);
        $lat = substr($pendiente_dato, 6);
        $lat = substr($lat, 0, -1);
		$check = preg_match($expr_reg, $lat, $lat_str);
        $lat = substr($lat_str[0], 0, -1);
		return $lat;
	}

	public function get_pendiente_lon()
    {
        $lon_str = array();
		$expr_reg = "~(,[ ]*[-]{0,1}[0-9]+\.[0-9]+$|,[ ]*[-]{0,1}[0-9]+\.[0-9]+E[-]{0,1}[0-9]+$)~";
		$pendiente_dato = str_replace(" ", "", $this->pendiente);
        $lon = substr($pendiente_dato, 6);
        $lon = substr($lon, 0, -1);
		$check = preg_match($expr_reg ,  $lon, $lon_str);
        $lon = substr($lon_str[0], 1);
		return $lon;
	}

	public function get_offset_calle()
    {
		return $this->offset_calle;
	}

    public function get_figura()
    {
		return $this->figura;
	}

	public function get_punto()
    {
		return $this->punto;
	}

	public function get_id_barrio()
    {
		return $this->id_barrio;
	}

	public function get_punto_lat()
    {
        $lat_str = array();
		$expr_reg = "~([ ]*[-]{0,1}[0-9]+\.[0-9]+[ ]*,|[ ]*[-]{0,1}[0-9]+\.[0-9]+E[-]{0,1}[0-9]+[ ]*,)~";
		$punto_dato = str_replace(" ", "", $this->punto);
        $lat = substr($punto_dato, 6);
        $lat = substr($lat, 0, -1);
		$check = preg_match($expr_reg, $lat, $lat_str);
        $lat = substr($lat_str[0], 0, -1);
		return $lat;
	}

	public function get_punto_lon()
    {
        $lon_str = array();
		$expr_reg = "~(,[ ]*[-]{0,1}[0-9]+\.[0-9]+|,[ ]*[-]{0,1}[0-9]+\.[0-9]+E[-]{0,1}[0-9]+)~";
		$punto_dato = str_replace(" ", "", $this->punto);
        $lon = substr($punto_dato, 6);
        $lon = substr($lon, 0, -1);
		$check = preg_match($expr_reg ,  $lon, $lon_str);
        $lon = substr($lon_str[0], 1);
		return $lon;
	}

    public function geo_lat_by_number($number)
    {
        $point = floatval($this->get_punto_lat());
        $min_num = floatval($this->get_min_num());
		$sign_pendiente = 1;
		$lat = $point;
		$num_domicilio = floatval($number);
		$dir_par = ($num_domicilio % 2) * -2 + 1;
		
		$pendiente_vector = $this->get_pendiente();
		$pendiente_lat_sign = 1;
		$offset_calle = $this->get_offset_calle();
		if ($number && $pendiente_vector) {
			$pendiente_lat = floatval($this->get_pendiente_lat());
			$pendiente_lon = floatval($this->get_pendiente_lon());
			$modulo = sqrt((1/$pendiente_lat) ** 2 + (1/$pendiente_lon) ** 2);
			if ($pendiente_lat > 0 && $pendiente_lon < 0) $pendiente_lat_sign = 1;
			if ($pendiente_lat < 0 && $pendiente_lon < 0) $pendiente_lat_sign = -1;
			if ($pendiente_lat < 0 && $pendiente_lon > 0) $pendiente_lat_sign = 1;
			if ($pendiente_lat > 0 && $pendiente_lon > 0) $pendiente_lat_sign = -1;
		}

		if ($number && $pendiente_vector) {
			$pendiente = floatval($this->get_pendiente_lat());
			$lat += $pendiente * ($number - $min_num);
			if ($offset_calle && ($offset_calle > 1 || $offset_calle < 1)) {
				$lat += (1/($pendiente * $modulo)) * $pendiente_lat_sign * $dir_par * 0.0001 * $offset_calle;
			} else {
				$lat += (1/($pendiente * $modulo)) * $pendiente_lat_sign * $dir_par * 0.0001;
			}
		}
        return $lat;
    }

    public function geo_lon_by_number($number)
    {
        $point = floatval($this->get_punto_lon());
        $min_num = floatval($this->get_min_num());
		$lon = $point;
		$num_domicilio = floatval($number);
		$dir_par = ($num_domicilio % 2) * -2 + 1;

		$pendiente_vector = $this->get_pendiente();
		$pendiente_lon_sign = 1;
		$offset_calle = $this->get_offset_calle();

		if ($number && $pendiente_vector) {
			$pendiente_lat = floatval($this->get_pendiente_lat());
			$pendiente_lon = floatval($this->get_pendiente_lon());
			$modulo = sqrt((1/$pendiente_lat) ** 2 + (1/$pendiente_lon) ** 2);
			if ($pendiente_lat > 0 && $pendiente_lon < 0) $pendiente_lon_sign = -1;
			if ($pendiente_lat < 0 && $pendiente_lon < 0) $pendiente_lon_sign = 1;
			if ($pendiente_lat < 0 && $pendiente_lon > 0) $pendiente_lon_sign = -1;
			if ($pendiente_lat > 0 && $pendiente_lon > 0) $pendiente_lon_sign = 1;
		}

		if ($number && $this->get_pendiente()) {
			$pendiente = floatval($this->get_pendiente_lon());
			$lon += $pendiente * ($number - $min_num);
			if ($offset_calle && ($offset_calle > 1 || $offset_calle < 1)) {
				$lon += (1/($pendiente * $modulo)) * $pendiente_lon_sign * $dir_par * 0.0001 * $offset_calle;
			} else {
				$lon += (1/($pendiente * $modulo)) * $pendiente_lon_sign * $dir_par * 0.0001;
			}
		}
        return $lon;
    }

	public function update($connection)
    {
		$connection->OpenConexion();
		$consulta = "update calles_barrios 
					set id_calle = " . ((!is_null($this->get_id_calle())) ? $this->get_id_calle() : "null") . ", 
						max_num = " . ((!is_null($this->get_max_num())) ? $this->get_max_num() : "null") . ", 
						min_num = " . ((!is_null($this->get_min_num())) ? $this->get_min_num() : "null") . ", 
						punto_max_num = " . ((!is_null($this->get_punto_max_num())) ? "'" . $this->get_punto_max_num() . "'" : "null") . ", 
						punto_min_num = " . ((!is_null($this->get_punto_min_num())) ? "'" . $this->get_punto_min_num() . "'" : "null") . ", 
						pendiente = " . ((!is_null($this->get_pendiente())) ? "'" . $this->get_pendiente() . "'" : "null") . ", 
						punto = " . ((!is_null($this->get_punto())) ? "'" . $this->get_punto() . "'" : "null") . ",
						offset_calle = " . ((!is_null($this->get_offset_calle())) ? $this->get_offset_calle() : "0") . ",
						figura = " . ((!is_null($this->get_figura())) ? $this->get_figura() : "null") . ",
                        ID_Barrio = " . ((!is_null($this->get_id_barrio())) ? $this->get_id_barrio() : "null") . ",
						estado = " . ((!is_null($this->get_estado())) ? "'" . $this->get_estado() . "'" : "null") . " 
					where id_geo = " . $this->get_id_geo();
					$mensaje_error = "No se pudo actualizar la Georeferencia";
		if (!$Ret = mysqli_query($connection->Conexion, $consulta)) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}
}
