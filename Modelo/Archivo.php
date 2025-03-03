<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Accion.php");
class Archivo 
{
	private $id_archivo;
	private $centro_salud;
	private $archivo;
	private $enlace;
	private $planilla;
    private $id_file;
	private $estado;
	private $coneccion_base;

	public function __construct(
		$coneccion_base=null,
		$id_archivo=null,
		$centro_salud=null,
		$archivo=null,
		$enlace=null,
		$id_file=null,
		$planilla=null,
		$estado=null
	){
		$this->coneccion_base = $coneccion_base;
		if (!$id_archivo) {
			$this->id_archivo = $id_archivo;
			$this->centro_salud = $centro_salud;
			$this->estado = $estado;
			$this->archivo = $archivo;
			$this->enlace = $enlace;
			$this->id_file = $id_file;
			$this->planilla = $planilla;
		} else {
			$consultar = "select *
						  from archivos 
						  where id_archivo = " . $id_archivo . " 
							and estado = 1";
			$ejecutar_consultar = mysqli_query(
				$this->coneccion_base->Conexion,
				$consultar) or die("Problemas al consultar filtro archivos");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_archivo = $ret["archivo"];
				$row_id_archivo = $ret["id_archivo"];
				$row_centro_salud = $ret["centro_salud"];
				$row_estado = $ret["estado"];
				$row_enlace = $ret["enlace"];
				$row_id_file = $ret["id_file"];
				$row_planilla = $ret["planilla"];
				
				$this->id_archivo = $row_id_archivo;
				$this->archivo = $row_archivo;
				$this->estado = $row_estado;
				$this->planilla = $row_planilla;
				$this->enlace = $row_enlace;
				$this->id_file = $row_id_file;
				$this->centro_salud = $row_centro_salud;
			}
		}
	}
	
	public static function exist(
								 $coneccion, 
                                 $id_archivo
	)	{
		$consultar = "select *
					  from archivo 
					  where id_archivo = " . $id_archivo . " 
						and estado = 1";
		$ejecutar_consultar = mysqli_query(
		$coneccion->Conexion,
		$consultar) or die("Problemas al consultar filtro archivos");
		$is_exist = (mysqli_num_rows($ejecutar_consultar) >= 1);
		return $is_exist;
	} 
	//METODOS SET
	public function set_id_archivo($id_archivo)
	{
		$this->id_archivo = $id_archivo;
	}

	public function set_archivo($archivo)
	{
		$this->archivo = $archivo;
	}

	public function set_estado($estado)
	{
		$this->estado = $estado;
	}

	public function set_enlace($enlace)
	{
		$this->enlace = $enlace;
	}

	public function set_planilla($planilla)
	{
		$this->planilla = $planilla;
	}
	public function set_id_file($id_file)
	{
		$this->id_file = $id_file;
	}
	
	public function set_centro_salud($centro_salud){
		$this->centro_salud = $centro_salud;
	}

	//METODOS GET
	
	public function get_id_archivo(){
		return $this->id_archivo;
	}
    public function get_archivo(){
		return $this->archivo;
	}
    public function get_id_file(){
		return $this->id_file;
	}
	public function get_estado()
	{
		return $this->estado;
	}

	public function get_enlace()
	{
		return $this->enlace;
	}

	public function get_planilla()
	{
		return $this->planilla;
	}

    public function get_centro_salud(){
		return $this->centro_salud;
	}
	public function jsonSerialize() 
	{
		$centro_salud = new CentroSalud(id_centro: $this->centro_salud);
		return [
			'id_archivo' => $this->id_archivo,
			'archivo' => $this->archivo,
			'estado' => $this->estado,
			'enlace' => $this->enlace,
            'centro_salud' => $centro_salud->jsonSerialize()
		];
	}

	public function update()
	{
		$consulta = "update archivo
					set archivo = " . ((!is_null($this->get_id_archivo())) ? "'" . $this->get_id_archivo() . "'" : "null") . ", 
						enlace = " . ((!is_null($this->get_enlace())) ? "'" . $this->get_enlace() . "'" : "null") . ", 
						planilla = " . ((!is_null($this->get_planilla())) ? "'" . $this->get_planilla() . "'" : "null") . ", 
						centro_salud = " . ((!is_null($this->get_centro_salud())) ? "'" . $this->get_centro_salud() . "'" : "null") . "
					where id_archivo = " . $this->get_id_archivo();
		$mensaje_error = "No se pudo actualizar el archivo";
		$ret = mysqli_query($this->coneccion_base, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function save()
	{
		$consulta = "INSERT INTO archivos ( 
										archivo, 
										enlace, 
										planilla,
										centro_salud,
										estado
					)
					VALUES ( " . ((!is_null($this->get_archivo())) ? "'" . $this->get_archivo() . "'" : "null") . ", 
							" . ((!is_null($this->get_enlace())) ? "'" . $this->get_enlace() . "'" : "null") . ", 
							" . ((!is_null($this->get_planilla())) ? "'" . $this->get_planilla() . "'" : "null") . ", 
							" . ((!is_null($this->get_centro_salud())) ? $this->get_centro_salud() : "null") . ",
							" . ((!is_null($this->get_estado())) ? "'" . $this->get_estado() . "'" : "null") . "
					)";
		$mensaje_error = "No se pudo insertar el archivo";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
		$this->id_archivo = mysqli_insert_id($this->coneccion_base->Conexion);

	}
}