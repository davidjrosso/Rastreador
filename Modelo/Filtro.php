<?php  
class Filtro implements JsonSerializable
{
	//DECLARACION DE VARIABLES
    private $id_usuario;
    private $fecha;
    private $nro_carpeta;
    private $id_persona;
    private $id_escuela;
    private $titulo;
    private $id_filtro;
    private $nro_legajo;	
    private $id_otra_institucion;
    private $id_centro_salud;
	private $estado;
	private $coneccion_base;
    private $movimientos;

	private $id_tipo_usuario;

    public function __construct(
			$coneccion_base = null,
			$id_filtro = null,
            $fecha = null,
            $id_persona = null,
            $id_escuela = null,
            $nro_carpeta = null,
            $id_centro_salud = null,
            $nro_legajo = null,
            $titulo = null,
            $id_tipo_usuario = null,
			$id_usuario = null,
            $id_otra_institucion = null,

			$estado = null,
            $movimientos = null
	) {
		$this->coneccion_base = $coneccion_base;
		if (!$id_filtro) {
			$this->id_centro_salud = $id_centro_salud;
			$this->id_persona = $id_persona;
			$this->nro_legajo = $nro_legajo;
            $this->nro_carpeta = $nro_carpeta;
            $this->id_usuario = $id_usuario;
            $this->id_escuela = $id_escuela;
            $this->titulo = $titulo;
            $this->id_otra_institucion = $id_otra_institucion;
            $this->id_tipo_usuario = $id_tipo_usuario;
            $this->id_filtro = $id_filtro;
            $this->fecha = $fecha;
            $this->estado = $estado;
		} else {
			$consultar = "select *
                          from filtros
                          where id_filtro = " . $id_filtro . " 
                            and estado = 1";
			$ejecutar_consultar = mysqli_query(
			$this->coneccion_base->Conexion, 
			$consultar) or die("Problemas al consultar filtro");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_id_filtro = $ret["id_filtro"];
				$row_id_centro_salud = $ret["id_centro_salud"];
                $row_id_persona = $ret["id_persona"];
                $row_id_escuela = $ret["id_escuela"];
				$row_estado = $ret["estado"];
                $row_fecha = $ret["fecha"];
                $row_nro_carpeta = $ret["nro_carpeta"];
                $row_titulo = $ret["titulo"];
                $row_id_tipousuario = $ret["id_tipousuario"];
                $row_id_otra_institucion = $ret["id_otra_institucion"];
                $row_nro_legajo = $ret["nro_legajo"];
                $row_id_usuario = $ret["id_usuario"];

                $this->id_centro_salud = $row_id_centro_salud;
                $this->id_persona = $row_id_persona;
                $this->nro_legajo = $row_nro_legajo;
                $this->id_usuario = $row_id_usuario;
                $this->id_escuela = $row_id_escuela;
                $this->nro_carpeta = $row_nro_carpeta;
                $this->titulo = $row_titulo;
                $this->id_otra_institucion = $row_id_otra_institucion;
                $this->id_tipo_usuario = $row_id_tipousuario;
                $this->id_filtro = $row_id_filtro;
                $this->fecha = $row_fecha;
                $this->estado = $row_estado;

			}
		}
	}

	public static function is_exist($coneccion, $id_filtro)
	{
		$consulta = "select * 
					 from filtros
					 where id_filtro = $id_filtro
					   and estado = 1";
		$mensaje_error = "Hubo un problema al consultar los registros para validar";
		$Ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		) or die(
			$mensaje_error
		);
		$is_multiple = (mysqli_num_rows($Ret) >= 1);
		return $is_multiple;
	}

    public static function get_id_by_title($coneccion, $titulo)
    {
        $consulta = "select id_filtro 
					 from filtros 
					 where titulo like '%$titulo%' 
					   and estado = 1";
		$mensaje_error = "Hubo un problema al consultar los registros";
		$ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		) or die(
			$mensaje_error
		);
		$row = mysqli_fetch_assoc($ret);
        $id_filtro = (!empty($row["id_filtro"])) ? $row["id_filtro"] : 1;
		return $id_filtro;
    }

	// METODOS SET

	public function set_id_filtro($id_filtro)
	{
		$this->id_filtro = $id_filtro;
	}

	public function set_id_centro_salud($id_centro_salud)
	{
		$this->id_centro_salud = $id_centro_salud;
	}

	public function set_id_persona($id_persona)
	{
		$this->id_persona = $id_persona;
	}

	public function set_fecha($fecha)
	{
		$this->fecha = $fecha;
	}

	public function set_id_escuela($id_escuela)
	{
		$this->id_escuela = $id_escuela;
	}

	public function set_nro_carpeta($nro_carpeta)
	{
		$this->nro_carpeta = $nro_carpeta;
	}

    public function set_id_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function set_id_tipo_usuario($id_tipo_usuario)
    {
        $this->id_tipo_usuario = $id_tipo_usuario;
    }

    public function set_id_otra_institucion($id_otra_institucion)
    {
        $this->id_otra_institucion = $id_otra_institucion;
    }

    public function set_nro_legajo($nro_legajo)
    {
        $this->nro_legajo = $nro_legajo;
    }

    public function set_titulo($titulo)
    {
        $this->titulo = $titulo;
    }

	public function set_estado($estado){
		$this->estado = $estado;
	}

	public function set_coneccion_base($coneccion_base)
	{
		$this->coneccion_base = $coneccion_base;
	}

	
	//METODOS GET

	public function get_id_filtro()
	{
		return $this->id_filtro;
	}

	public function get_id_centro_salud()
	{
		return $this->id_centro_salud;
	}

	public function get_id_persona()
	{
		return $this->id_persona;
	}

	public function get_fecha()
	{
		return $this->fecha;
	}

	public function get_id_escuela()
	{
		return $this->id_escuela;
	}

	public function get_nro_carpeta()
	{
		return $this->nro_carpeta;
	}

    public function get_id_usuario()
    {
        return $this->id_usuario;
    }

    public function get_id_tipo_usuario()
    {
        return $this->id_tipo_usuario;
    }

    public function get_id_otra_institucion()
    {
        return $this->id_otra_institucion;
    }

    public function get_nro_legajo()
    {
        return $this->nro_legajo;
    }

    public function get_titulo()
    {
        return $this->titulo;
    }

	public function get_estado()
	{
		return $this->estado;
	}

	public function get_coneccion_base()
	{
		return $this->coneccion_base;
	}

	public function jsonSerialize() 
	{
		return [
			'id_centro_salud' => $this->id_centro_salud,
			'id_escuela' => $this->id_escuela,
			'estado' => $this->estado
		];
	}

	public function udpate()
    {
		$consulta = "update filtros
					 set id_centro_salud = " . (($this->get_id_centro_salud()) ? $this->get_id_centro_salud() : "null") . ", 
						 id_escuela = " . (($this->get_id_escuela()) ? $this->get_id_escuela() : "null") . ", 
						 id_persona = " . (($this->get_id_persona()) ? $this->get_id_persona() : "null") . ", 
						 nro_carpeta = " . (($this->get_nro_carpeta()) ? "'" . $this->get_nro_carpeta() . "'" : "null") . ", 
						 nro_legajo = " . (($this->get_nro_legajo()) ? "'" . $this->get_nro_legajo() . "'" : "null") . ", 
						 id_usuario = " . (($this->get_id_usuario()) ?  $this->get_id_usuario() : "null") . ", 
						 fecha = " . (($this->get_fecha()) ? "'" . $this->get_fecha() . "'" : "null") . ", 
						 id_tipo_usuario = " . (($this->get_id_tipo_usuario()) ? $this->get_id_tipo_usuario() : "null") . ", 
						 id_otra_institucion = " . (($this->get_id_otra_institucion()) ? $this->get_id_otra_institucion() : "null") . ", 
						 titulo = " . (($this->get_titulo()) ? "'" . $this->get_titulo() . "'" : "null") . ", 
					 	 estado = " . (($this->get_estado()) ? $this->get_estado() : "null") . "
					 where id_filtro = " . $this->get_id_filtro();
		$mensaje_error = "No se pudo modificar el filtro";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function save()
    {
		$consulta = "insert into filtros (
											id_centro_salud,
											id_escuela,
											id_persona,
											nro_carpeta,
											nro_legajo,
											id_usuario,
											id_tipo_usuario,
											id_otra_institucion,
											fecha,
											titulo,
											estado
											) 
				values(
						" . (($this->get_id_centro_salud()) ? $this->get_id_centro_salud() : "null") . ",
						" . (($this->get_id_escuela()) ? $this->get_id_escuela() : "null") . ",
						" . (($this->get_id_persona()) ? $this->get_id_persona() : "null") . ",
						" . (($this->get_nro_carpeta()) ? "'" . $this->get_nro_carpeta() . "'" : "null") . ",
						" . (($this->get_nro_legajo()) ? "'" . $this->get_nro_legajo() . "'" : "null") . ",
						" . (($this->get_id_usuario()) ? $this->get_id_usuario() : "null") . ",
						" . (($this->get_id_tipo_usuario()) ? $this->get_id_tipo_usuario() : "null") . ",
						" . (($this->get_id_otra_institucion()) ? $this->get_id_otra_institucion() : "null") . ",
						" . (($this->get_fecha()) ? "'" . $this->get_fecha() . "'" : "null") . ",
						" . (($this->get_titulo()) ? $this->get_titulo() : "null") . "

						" . (($this->get_estado()) ? $this->get_estado() : "null") . "
						)";
		$mensaje_error = "No se pudo insertar";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
		$this->id_filtro = mysqli_insert_id($this->coneccion_base->Conexion);
	}
}