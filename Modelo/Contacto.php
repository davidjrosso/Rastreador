<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Accion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Parametria.php');

class Contacto implements JsonSerializable {
	//DECLARACION DE VARIABLES
    private $coneccion;
    private $id_contacto;
    private $Estado;
	private $Mail;
    private $id_persona;
    private $Telefono;
	private $Trabajo;


	public function __construct(
        $coneccion = null,
        $id_contacto = null,    
        $xTelefono = null,
		$xMail = null,
        $id_persona = null,
        $xEstado = null,
		$xTrabajo = null
	) {
        $this->coneccion = $coneccion;
        if (!$id_contacto && $id_persona) {
			$ConsultarPersona = "select *
                                 from contactos
								 where id_persona = " . $id_persona . " 
								   and estado = 1";
			$EjecutarConsultarPersona = mysqli_query(
				$this->coneccion->Conexion,
				$ConsultarPersona) or die("Problemas al consultar filtro Persona");
			$ret = mysqli_fetch_assoc($EjecutarConsultarPersona);
	
			$id_contacto = (isset($ret["id_contacto"])) ? $ret["id_contacto"] : null;
			$telefono = (isset($ret["telefono"])) ? $ret["telefono"] : null;
			$mail = (isset($ret["mail"])) ? $ret["mail"] : null;
            $query_id_persona = (isset($ret["id_persona"])) ? $ret["id_persona"] : null;
            $estado = (isset($ret["estado"])) ? $ret["estado"] : null;
			$trabajo = (isset($ret["trabajo"])) ? $ret["trabajo"] : null;
			$this->id_contacto = $id_contacto;
			$this->Telefono = ($xTelefono) ? $xTelefono : $telefono;
			$this->Mail = ($xMail) ? $xMail : $mail;
            $this->id_persona = (!empty($id_persona)) ? $id_persona : $query_id_persona;
            $this->Estado = ($xEstado) ? $xEstado : $estado;
			$this->Trabajo = ($xTrabajo) ? $xTrabajo : $trabajo;

		} else {
			$ConsultarPersona = "select *
                                 from contactos
								 where id_contacto = " . $id_contacto . " 
								   and estado = 1";
			$EjecutarConsultarPersona = mysqli_query(
				$this->coneccion->Conexion,
				$ConsultarPersona) or die("Problemas al consultar filtro Persona");
			$ret = mysqli_fetch_assoc($EjecutarConsultarPersona);
	
			$id_contacto = (isset($ret["id_contacto"])) ? $ret["id_contacto"] : null;
			$telefono = (isset($ret["telefono"])) ? $ret["telefono"] : null;
			$mail = (isset($ret["mail"])) ? $ret["mail"] : null;
            $query_id_persona = (isset($ret["id_persona"])) ? $ret["id_persona"] : null;
            $estado = (isset($ret["estado"])) ? $ret["estado"] : null;
			$trabajo = (isset($ret["trabajo"])) ? $ret["trabajo"] : null;
			$this->id_contacto = $id_contacto;
			$this->Telefono = ($xTelefono) ? $xTelefono : $telefono;
			$this->Mail = ($xMail) ? $xMail : $mail;
            $this->id_persona = (!empty($id_persona)) ? $id_persona : $query_id_persona;
            $this->Estado = ($xEstado) ? $xEstado : $estado;
			$this->Trabajo = ($xTrabajo) ? $xTrabajo : $trabajo;
		}
	}

	public static function tiene_contacto($coneccion, $id_persona)
	{
		$has = 0;
		$ConsRegistrosIguales = "select id_persona 
								from contactos
								where id_persona = $id_persona
								  and estado = 1";
		$MensajeErrorRegistrosIguales = "Hubo un problema al consultar los registros para validar";
		$ret = mysqli_query($coneccion->Conexion,
			$ConsRegistrosIguales
		);

		$has = mysqli_num_rows($ret);

		return $has;
	}

    //METODOS SET
    public function set_id_contacto($id_contacto)
    {
        $this->id_contacto = $id_contacto;
    }

    public function setTelefono($xTelefono){
    $this->Telefono = $xTelefono;
    }

    public function setMail($xMail){
        $this->Mail = $xMail;
    }

    public function set_id_persona($id_persona)
    {
        $this->id_persona = $id_persona;
    }

    public function setEstado($xEstado){
        $this->Estado = $xEstado;
    }

    public function setTrabajo($xTrabajo){
        $this->Trabajo = $xTrabajo;
    }

    //METODOS GET
    public function get_id_contacto(){
        return $this->id_contacto;
    }

    public function getTelefono()
    {
        return $this->Telefono;
    }

    public function getMail()
    {
        return $this->Mail;
    }

    public function get_id_persona($id_persona)
    {
        return $this->id_persona;
    }

    public function getEstado()
    {
        return $this->Estado;
    }

    public function getTrabajo()
    {
        return $this->Trabajo;
    }

    public function jsonSerialize() {
        return [
        'Telefono' => $this->Telefono,
        'Mail' => $this->Mail,
        'Estado' => $this->Estado,
        'Trabajo' => $this->Trabajo,
        ];
    }

    public function update()
    {
        $Consulta = "update contactos
                    set telefono = " . ((!is_null($this->getTelefono())) ? "'" . $this->getTelefono() . "'" : "null") . ", 
                        mail = " . ((!is_null($this->getMail())) ? "'" . $this->getMail() . "'" : "null") . ", 
                        trabajo = " . ((!is_null($this->getTrabajo())) ? "'" . $this->getTrabajo() . "'" : "null") . "
                        where id_contacto = " . $this->get_id_contacto();
                    $MensajeErrorConsultar = "No se pudo actualizar la Persona";
                    if (!$Ret = mysqli_query($this->coneccion->Conexion, $Consulta)) {
                        throw new Exception($MensajeErrorConsultar . $Consulta, 2);
                    }
    }

    public function save(){
        $Con = new Conexion();
        $Con->OpenConexion();
        $consulta = "INSERT INTO contactos(
                                        telefono, 
                                        mail, 
                                        trabajo, 
                                        estado 
                    )
                    VALUES ( " . ((!is_null($this->getTelefono())) ? "'" . $this->getTelefono() . "'" : "null") . ", 
                            " . ((!is_null($this->getMail())) ? "'" . $this->getMail() . "'" : "null") . ", 
                            " . ((!is_null($this->getTrabajo())) ? "'" . $this->getTrabajo() . "'" : "null") . ",
                            1
                    )";
                    $MensajeErrorConsultar = "No se pudo insertar la Persona";
                    $ret = mysqli_query($Con->Conexion, $consulta);
                    if (!$ret) {
                        throw new Exception($MensajeErrorConsultar . $consulta, 2);
                    }
                    $this->id_contacto = mysqli_insert_id($Con->Conexion);
                    $Con->CloseConexion();
    }

	function delete()
	{
		$Con = new Conexion();
		$Con->OpenConexion();

		$query = "update contactos
				  set estado = 0
				  where id_contacto = " . $this->get_id_contacto();
		$MensajeErrorConsultar = "No se pudo insertar la Persona";
		$ret = mysqli_query($Con->Conexion, $query);
		if (!$ret) {
		throw new Exception($MensajeErrorConsultar . $query, 2);
		}
		$Con->CloseConexion();

	}
}
