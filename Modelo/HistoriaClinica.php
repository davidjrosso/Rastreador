<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Accion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Parametria.php');

class HistoriaClinica implements JsonSerializable
{
	//DECLARACION DE VARIABLES
    private $coneccion;
    private $id_historia_clinica;
    private $ID_Persona;
	private $Nro_Legajo;
	private $Nro_Carpeta;
    private $id_centro_salud;
    private $estado;

	public function __construct(
        $coneccion = null,
        $id_historia_clinica = null,
        $ID_Persona = null,
		$xNro_Legajo = null,
		$xNro_Carpeta = null,
		$xEstado = null,
        $id_centro_salud = null
    ) {
        $this->coneccion = $coneccion;
        if (!$id_historia_clinica) {
			$ConsultarPersona = "select *
								 from historia_clinica 
								 where id_persona = " . $ID_Persona . " 
                                   and id_centro_salud = $id_centro_salud
                                     and estado = 1";
			$EjecutarConsultarPersona = mysqli_query(
				$this->coneccion->Conexion,
				$ConsultarPersona) or die("Problemas al consultar filtro Persona");
			$ret = mysqli_fetch_assoc($EjecutarConsultarPersona);

			$id_persona = $ret["id_persona"];
            $query_id_historia_clinica = $ret["id_historia_clinica"];
            $nro_Carpeta = $ret["nro_carpeta"];
			$nro_Legajo = $ret["nro_legajo"];
            $query_id_centro_salud = $ret["id_centro_salud"];
            $estado = $ret["estado"];

			$this->ID_Persona = (!empty($ID_Persona)) ? $ID_Persona : $id_persona;
            $this->id_historia_clinica = (!empty($id_historia_clinica)) ? $id_historia_clinica : $query_id_historia_clinica;
            $this->Nro_Legajo = ($xNro_Legajo) ? $xNro_Legajo : $nro_Legajo;
			$this->Nro_Carpeta = ($xNro_Carpeta) ? $xNro_Carpeta : $nro_Carpeta;
            $this->id_centro_salud = (!empty($id_centro_salud)) ? $id_centro_salud : $query_id_centro_salud;
            $this->estado = ($xEstado) ? $xEstado : $estado;

        } else {
			$ConsultarPersona = "select *
								 from historia_clinica 
								 where id_historia_clinica = " . $id_historia_clinica . " 
								   and estado = 1";
			$EjecutarConsultarPersona = mysqli_query(
				$this->coneccion->Conexion,
				$ConsultarPersona) or die("Problemas al consultar filtro Persona");
			$ret = mysqli_fetch_assoc($EjecutarConsultarPersona);
	
			$id_persona = $ret["id_persona"];
            $query_id_historia_clinica = $ret["id_historia_clinica"];
            $nro_Carpeta = $ret["nro_carpeta"];
			$nro_Legajo = $ret["nro_legajo"];
            $query_id_centro_salud = $ret["id_centro_salud"];
            $estado = $ret["estado"];
			$this->ID_Persona = (!empty($ID_Persona)) ? $ID_Persona : $id_persona;
            $this->id_historia_clinica = (!empty($id_historia_clinica)) ? $id_historia_clinica : $query_id_historia_clinica;
            $this->Nro_Legajo = ($xNro_Legajo) ? $xNro_Legajo : $nro_Legajo;
			$this->Nro_Carpeta = ($xNro_Carpeta) ? $xNro_Carpeta : $nro_Carpeta;
            $this->id_centro_salud = (!empty($id_centro_salud)) ? $id_centro_salud : $query_id_centro_salud;
            $this->estado = ($xEstado) ? $xEstado : $estado;
		}
	}


    
    //METODOS SET
    public function setID_Persona($xID_Persona)
    {
        $this->ID_Persona = $xID_Persona;
    }

    public function set_id_historia_clinica($id_historia_clinica)
    {
        $this->id_historia_clinica = $id_historia_clinica ;
    }
    
    public function setNro_Legajo($xNro_Legajo)
    {
        $this->Nro_Legajo = $xNro_Legajo;
    }

    public function setNro_Carpeta($xNro_Carpeta)
    {
        $this->Nro_Carpeta = $xNro_Carpeta;
    }

    public function set_id_centro_salud($id_centro_salud)
    {
        $this->id_centro_salud = $id_centro_salud;
    }

    public function setEstado($xEstado)
    {
        $this->estado = $xEstado;
    }


    //METODOS GET
    public function getID_Persona()
    {
        return $this->ID_Persona;
    }

    public function get_id_historia_clinica()
    {
        return $this->id_historia_clinica;
    }
    
    public function getNro_Legajo()
    {
        return $this->Nro_Legajo;
    }

    public function getNro_Carpeta()
    {
        return $this->Nro_Carpeta;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function get_id_centro_salud()
    {
        return $this->id_centro_salud;
    }

    public function jsonSerialize(): mixed
    {
        return [
        'ID_Persona' => $this->ID_Persona,
        'Nro_Legajo' => $this->Nro_Legajo,
        'Nro_Carpeta' => $this->Nro_Carpeta,
        'Estado' => $this->estado,
        ];
    }

    public function update_NroCarpeta()
    {
        $Con = new Conexion();
        $Con->OpenConexion();
        $Consulta = "update historia_clinica 
                    set nro_carpeta = " . ((!is_null($this->getNro_Carpeta())) ? "'" . intval($this->getNro_Carpeta()) . "'" : "null") . " 
                    where id_historia_clinica = " . $this->get_id_historia_clinica();
                    $MensajeErrorConsultar = "No se pudo actualizar la Persona";
                    if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
                        throw new Exception($MensajeErrorConsultar . $Consulta, 2);
                    }
                    $Con->CloseConexion();
    }

    public function update()
    {
        $Con = new Conexion();
        $Con->OpenConexion();
        $Consulta = "update historia_clinica 
                     set  nro_legajo = " . ((!is_null($this->getNro_Legajo())) ? "'" . $this->getNro_Legajo() . "'" : "null") . ", 
                        nro_carpeta = " . ((!is_null($this->getNro_Carpeta())) ? "'" . $this->getNro_Carpeta() . "'" : "null") . ", 
                        id_centro_salud = " . ((!is_null($this->get_id_centro_salud())) ? $this->get_id_centro_salud() : "null") . "
                    where id_historia_clinica = " . $this->get_id_historia_clinica();
                    $MensajeErrorConsultar = "No se pudo actualizar la hc";
                    if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
                        throw new Exception($MensajeErrorConsultar . $Consulta, 2);
                    }
                    $Con->CloseConexion();
    }

    public function save()
    {
        $Con = new Conexion();
        $Con->OpenConexion();
        $consulta = "INSERT INTO historia_clinica (
                                        nro_legajo,
                                        nro_carpeta, 
                                        estado,
                                        id_centro_salud 
                    )
                    VALUES ( ". ((!is_null($this->getNro_Legajo())) ? "'" . $this->getNro_Legajo() . "'" : "null") . ", 
                            " . ((!is_null($this->getNro_Carpeta())) ? "'" . $this->getNro_Carpeta() . "'" : "null") . ", 
                            1,
                            " . ((!is_null($this->get_id_centro_salud())) ? $this->get_id_centro_salud() : "null") . "                            
                    )";
                    $MensajeErrorConsultar = "No se pudo insertar la Persona";
                    $ret = mysqli_query($Con->Conexion, $consulta);
                    if (!$ret) {
                        throw new Exception($MensajeErrorConsultar . $consulta, 2);
                    }
                    $this->id_historia_clinica = mysqli_insert_id($Con->Conexion);
                    $Con->CloseConexion();
    }

	function delete()
	{
		$Con = new Conexion();
		$Con->OpenConexion();

		$query = "update historia_clinica
				  set estado = 0
				  where id_historia_clinica = " . $this->get_id_historia_clinica();
		$MensajeErrorConsultar = "No se pudo insertar la hc";
		$ret = mysqli_query($Con->Conexion, $query);
		if (!$ret) {
		throw new Exception($MensajeErrorConsultar . $query, 2);
		}
		$Con->CloseConexion();

	}
}
