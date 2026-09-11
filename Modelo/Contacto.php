<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Accion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Persona.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Contacto.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/TipoContacto.php');

class Contacto implements JsonSerializable {
	//DECLARACION DE VARIABLES
    private $coneccion;
    private $id_contacto;
    private $persona;
    private $valor;
    private $tipo_contacto;
    private $estado;


	public function __construct(
        $coneccion = null,
        $id_contacto = null,    
        $estado = null,
        $valor = null,
        $persona = null,
        $tipo_contacto = null
	) {
        $this->coneccion = $coneccion;
        if ($id_contacto) {
			$consultar = "select *
                            from contactos
                            where id_contacto = $id_contacto 
                            and estado = 1";
			$ejec = mysqli_query(
				$this->coneccion->Conexion,
				$consultar);
            if (!$ejec) throw new Exception("error al consultar los datos", 1);
			$ret = mysqli_fetch_assoc($ejec);
	
            if ($ret) {
                if (isset($ret["id_persona"])) $this->persona = new Persona(ID_Persona: $ret["id_persona"]);
                $row_valor = (isset($ret["valor"])) ? $ret["valor"] : $valor; 
                $row_estado = (isset($ret["estado"])) ? $ret["estado"] : $estado;
                if (isset($ret["id_tipo_contacto"])) $this->tipo_contacto = new TiposContacto(
                                                                                        coneccion: $this->coneccion->Conexion, 
                                                                                        id_tipo_contacto: $ret["id_tipo_contacto"]
                                                                                        ); 
                $this->id_contacto = $id_contacto;
                $this->valor = $row_valor;
                $this->estado = ($row_estado) ? $row_estado : 1;
            }


		} else {
                if (isset($persona)) $this->persona = $persona;
                $this->valor = (isset($valor)) ? $valor : null; 
                $this->estado = (isset($estado)) ? $estado : null;
                $this->id_contacto = $id_contacto;
                if (isset($tipo_contacto)) $this->tipo_contacto = $tipo_contacto;
        }
	}

	public static function tiene_contacto($coneccion, $persona)
	{
		$has = 0;
		$cons = "select id_persona 
                 from contactos
                 where id_persona = " .  $persona->get_id_persona() . "
                   and estado = 1";
		$mensaje = "Hubo un problema al consultar los registros para validar";
		$ret = mysqli_query($coneccion->Conexion,
			                $cons
		                    );
        if (!$ret) throw new Exception($mensaje, 1);

		$has = mysqli_num_rows($ret);

		return $has;
	}

    //METODOS SET
    public function set_id_contacto($id_contacto)
    {
        $this->id_contacto = $id_contacto;
    }

    public function set_persona($persona)
    {
        $this->persona = $persona;
    }
    public function set_valor($valor)
    {
        $this->valor = $valor;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function set_tipo_contacto($tipo_contacto)
    {
        $this->tipo_contacto = $tipo_contacto;
    }

    //METODOS GET
    public function get_id_contacto()
    {
        return $this->id_contacto;
    }

    public function get_persona()
    {
        return $this->persona;
    }
    public function get_valor()
    {
        return $this->valor;
    }

    public function get_estado()
    {
        return $this->estado;
    }

    public function get_tipo_contacto()
    {
        return $this->tipo_contacto;
    }

    public function jsonSerialize() {
        return [
        'id_persona' => $this->persona->get_id_persona(),
        'valor' => $this->get_valor(),
        'estado' => $this->estado,
        'id_contacto' => $this->get_id_contacto(),
        'id_tipo_contacto' => $this->get_tipo_contacto(),
        ];
    }

    public function update()
    {
        $consulta = "update contactos
                    set id_persona = " . ((!is_null($this->get_persona())) ? $this->get_persona()->get_id_persona() : "null") . ", 
                        valor = " . ((!is_null($this->get_valor())) ? "'" .  $this->get_valor() . "'" : "null") . ",
                        estado = " . ((!is_null($this->get_estado())) ? $this->get_estado() : "null") . ",
                        id_tipo_contacto = " . ((!is_null($this->get_tipo_contacto())) ? $this->get_tipo_contacto()->get_id_tipo_contacto() : "null") . ",
                        where id_contacto = " . $this->get_id_contacto();
        $mensaje = "No se pudo actualizar";
        if (!$Ret = mysqli_query($this->coneccion->Conexion, $consulta)) {
            throw new Exception($mensaje . $consulta, 2);
        }
    }

    public function save(){
        $consulta = "INSERT INTO contactos(
                                        id_persona,
                                        valor,
                                        estado,
                                        id_contacto, 
                                        id_tipo_contacto
                    )
                    VALUES (" . $this->get_persona()->get_id_persona() . " ,
                            " . ((!is_null($this->get_valor())) ? "'" . $this->get_valor() . "'" : null) . " ,
                            " . ((!is_null($this->get_estado())) ? $this->get_estado() : null) . " ,
                            " . ((!is_null($this->get_id_contacto())) ? $this->get_id_contacto() : "null") . ",
                            " . ((!is_null($this->get_tipo_contacto())) ? $this->get_tipo_contacto()->get_id_tipo_contacto() : "null") . "
                    )";
                    $mensaje = "No se pudo insertar";
                    $ret = mysqli_query($this->coneccion->Conexion, $consulta);
                    if (!$ret) throw new Exception($mensaje . $consulta, 2);
                    $this->id_contacto = mysqli_insert_id($this->coneccion->Conexion);
    }

	function delete()
	{
		$query = "update contactos
				  set estado = 0
				  where id_contacto = " . $this->get_id_contacto();
		$mensaje = "No se pudo eliminar";
		$ret = mysqli_query($this->coneccion->Conexion, $query);
		if (!$ret) throw new Exception($mensaje , 2);
	}
}