<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Controladores/Conexion.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Persona.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Movimiento.php");

class Formulario implements JsonSerializable
{
	//DECLARACION DE VARIABLES
	private $id_formulario;
	private $email;
	private $responsable;
	private $estado;
	private $persona;
	private $movimiento;
	private $fecha;
	private $coneccion_base;

	public function __construct(
		$coneccion_base=null,
		$id_formulario=null,
		$email=null,
		$estado=null,
		$persona=null,
		$movimiento=null,
		$responsable=null,
		$fecha=null
	){
		$fecha_actual = DateTime::createFromFormat(format: 'Y-m-d', datetime: date('Y-m-d'));
		$this->coneccion_base = $coneccion_base;
		if (!$id_formulario) {
			$this->email = $email;
			$this->estado = $estado;
			$this->movimiento = $movimiento;
			$this->fecha = ($fecha) ? $fecha : $fecha_actual;
			$this->persona = $persona;
			$this->responsable = $responsable;
		} else {
			$consultar = "select *
						  from formularios 
						  where id_formulario = " . $id_formulario . " 
							and estado = 1";
			$ejecutar_consultar = mysqli_query(
				$this->coneccion_base,
				$consultar) or die("Problemas al consultar filtro Usuario");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$form_email = $ret["email"];
				$form_persona = $ret["persona"];
				$form_responsable = $ret["responsable"];
				$form_estado = $ret["estado"];
				$form_movimiento = $ret["movimiento"];
				$form_fecha = $ret["fecha"];
				$form_id_formulario = $ret["id_formulario"];
	
				$this->id_formulario = $form_id_formulario;
				$this->email = $form_email;
				$this->estado = $form_estado;
				$this->movimiento = $form_movimiento;
				$this->persona = $form_persona;
				$this->responsable = $form_responsable;
				$this->fecha = $form_fecha;
			}
		}
	}
	

	//METODOS SET
	public function set_id_formulario($id_formulario)
	{
		$this->id_formulario = $id_formulario;
	}

	public function set_email($email)
	{
		$this->email = $email;
	}

	public function set_estado($estado)
	{
		$this->estado = $estado;
	}

	public function set_persona($persona)
	{
		$this->persona = $persona;
	}

	public function set_movimiento($movimiento)
	{
		$this->movimiento = $movimiento;
	}

	public function set_fecha($fecha)
	{
		$this->fecha = $fecha;
	}

	
	public function set_responsable($responsable){
		$this->responsable = $responsable;
	}

	//METODOS GET
	
	public function get_id_formulario(){
		return $this->id_formulario;
	}

	public function get_estado()
	{
		return $this->estado;
	}

	public function get_email()
	{
		return $this->email;
	}

	public function get_persona()
	{
		return $this->persona;
	}

	public function get_movimiento()
	{
		return $this->movimiento;
	}

	public function get_fecha()
	{
		return $this->fecha;
	}

	public function get_responsable()
	{
		return $this->responsable;
	}

	public function jsonSerialize() 
	{
		$persona = new Persona(ID_Persona: $this->persona);
		return [
			'id_formulario' => $this->id_formulario,
			'email' => $this->email,
			'estado' => $this->estado,
			'movimiento' => $this->movimiento,
			'persona' => $persona->jsonSerialize()
		];
	}

	public function update()
	{
		$consulta = "update formularios 
					set email = " . ((!is_null($this->get_email())) ? "'" . $this->get_email() . "'" : "null") . ", 
						persona = " . ((!is_null($this->get_persona())) ? "'" . $this->get_persona() . "'" : "null") . ", 
						estado = " . ((!is_null($this->get_estado())) ? "'" . $this->get_estado() . "'" : "null") . ", 
						movimiento = " . ((!is_null($this->get_movimiento())) ? "'" . $this->get_movimiento() . "'" : "null") . "
					where id_formulario = " . $this->get_id_formulario();
		$mensaje_error = "No se pudo actualizar la Persona";
		$ret = mysqli_query($this->coneccion_base, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function save()
	{
		$consulta = "INSERT INTO formulario ( 
										email, 
										movimiento, 
										persona,
										responsable,
										fecha,
										estado
					)
					VALUES ( " . ((!is_null($this->get_email())) ? "'" . $this->get_email() . "'" : "null") . ", 
							" . ((!is_null($this->get_movimiento())) ? "'" . $this->get_movimiento() . "'" : "null") . ", 
							" . ((!is_null($this->get_persona())) ? "'" . $this->get_persona() . "'" : "null") . ", 
							" . ((!is_null($this->get_responsable())) ? $this->get_responsable() : "null") . ",
							" . ((!is_null($this->get_fecha())) ? "'" . $this->get_fecha() . "'" : "null") . ", 
							" . ((!is_null($this->get_estado())) ? "'" . $this->get_estado() . "'" : "null") . "
					)";
		$mensaje_error = "No se pudo insertar el usuario";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
		$this->id_formulario = mysqli_insert_id($this->coneccion_base->Conexion);

	}
}
