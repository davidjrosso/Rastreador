<?php

class PersonaDomicilio
{
// DECLARACION DE VARIABLES
    private $connection;
	private $id_persona_domicilio;
	private $id_persona;
	private $id_domicilio;
	private $estado;

	public function __construct(
        $connection = null,
		$id_persona_domicilio = null,
		$id_persona = null,
		$id_domicilio = null,
		$estado = null
	) {
		$this->connection = $connection;
		if (!$id_persona_domicilio) {
			$this->id_persona = $id_persona;
			$this->id_domicilio = $id_domicilio;
			$this->estado = $estado;
		} else {
			$Con = new Conexion();
			$Con->OpenConexion();
			$ConsultarPersona = "select *
								 from personas_domicilios 
								 where id_persona_domicilio = " . $id_persona_domicilio . " 
								   and estado = 1";
			$EjecutarConsultarPersona = mysqli_query(
				$Con->Conexion,
				$ConsultarPersona) or die("Problemas al consultar filtro Persona");
			$ret = mysqli_fetch_assoc($EjecutarConsultarPersona);
	
			$query_id_persona = $ret["id_persona"];
			$query_id_persona_domicilio = $ret["id_persona_domicilio"];
			$query_estado = $ret["estado"] ;
			$query_id_domicilio = $ret["id_domicilio"];
			$this->id_persona_domicilio = (!empty($id_persona_domicilio)) ? $id_persona_domicilio : $query_id_persona_domicilio;
			$this->id_persona = (!empty($id_persona)) ? $id_persona : $query_id_persona;
			$this->id_domicilio = (!empty($id_domicilio)) ? $id_domicilio : $query_id_domicilio;
			$this->estado = (!empty($estado)) ? $estado : $query_estado;			
			$Con->CloseConexion();
		}
	}


	//METODOS SET
	public function set_id_persona($xID_Persona){
		$this->id_persona = $xID_Persona;
	}

	public function set_estado($estado){
		$this->estado = $estado;
	}

	public function set_id_domicilio($id_domicilio){
		$this->id_domicilio = $id_domicilio;
	}

	public function set_id_persona_domicilio($id_persona_domicilio){
		$this->id_persona_domicilio = $id_persona_domicilio;
	}

	public function get_id_persona()
	{
		return $this->id_persona;
	}

	public function get_estado()
	{
		return $this->estado;
	}

	public function get_id_domicilio()
	{
		return $this->id_domicilio;
	}

	public function get_id_persona_domicilio(){
		return $this->id_persona_domicilio;
	}

	public function update()
	{
		$Con = new Conexion();
		$Con->OpenConexion();
		$Consulta = "update personas_domicilios
					set  " . ((!is_null($this->get_id_persona())) ? $this->get_id_persona() : "null") . ", 
						 " . ((!is_null($this->get_id_domicilio())) ? $this->get_id_domicilio() : "null") . ", 
					where id_persona_domicilio = " . $this->get_id_persona_domicilio();
					$MensajeErrorConsultar = "No se pudo actualizar la Persona";
					if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
						throw new Exception($MensajeErrorConsultar . $Consulta, 2);
					}
					$Con->CloseConexion();
	}

	public function save(){
		$Con = new Conexion();
		$Con->OpenConexion();
		$consulta = "INSERT INTO persona (
										id_persona,
										id_domicilio,
										estado 
					)
					VALUES ( 
							" . ((!is_null($this->get_id_persona())) ? $this->get_id_persona() : "null") . ", 
							" . ((!is_null($this->get_id_domicilio())) ? $this->get_id_domicilio() : "null") . ", 
							1
					)";
					$MensajeErrorConsultar = "No se pudo insertar la Persona";
					$ret = mysqli_query($Con->Conexion, $consulta);
					if (!$ret) {
						throw new Exception($MensajeErrorConsultar . $consulta, 2);
					}
					$this->id_persona_domicilio = mysqli_insert_id($Con->Conexion);
					$Con->CloseConexion();
	}

	function delete()
	{
		$Con = new Conexion();
		$Con->OpenConexion();

		$query = "update persona
				  set estado = 0
				  where id_persona = " . $this->getID_Persona();
		$MensajeErrorConsultar = "No se pudo insertar la Persona";
		$ret = mysqli_query($Con->Conexion, $query);
		if (!$ret) {
		throw new Exception($MensajeErrorConsultar . $query, 2);
		}
		$Con->CloseConexion();

	}	
}