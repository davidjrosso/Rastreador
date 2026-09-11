<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Accion.php');

class TiposContacto implements JsonSerializable {
	//DECLARACION DE VARIABLES
    private $coneccion;
    private $id_tipo_contacto;
    private $estado;
    private $tipo;

	public function __construct(
        $coneccion = null,
        $id_tipo_contacto = null,    
        $tipo = null,
        $estado = null
	) {
        $this->coneccion = $coneccion;
        if ($id_tipo_contacto) {
			$consultar = "select *
                            from tipos_contactos
                            where id_tipo_contacto = $id_tipo_contacto
                            and estado = 1";
			$ejec = mysqli_query(
				$this->coneccion->Conexion,
				$consultar);
            if (!$ejec) throw new Exception("Error al consultar datos", 1);
			$ret = mysqli_fetch_assoc($ejec);
	
            if (!$ret)
			$row_id_tipo_contacto = (isset($ret["id_tipo_contacto"])) ? $ret["id_tipo_contacto"] : null;
            $row_estado = (isset($ret["estado"])) ? $ret["estado"] : null;
            $row_tipo = (isset($ret["tipo"])) ? $ret["tipo"] : null;
			$this->id_tipo_contacto = $row_id_tipo_contacto;
            $this->estado = ($row_estado) ? $row_estado : $estado;
			$this->tipo = ($row_tipo) ? $row_tipo : $tipo;

		} else {
			$this->id_tipo_contacto = $id_tipo_contacto;
            $this->estado = ($estado) ? $estado : 1;
			$this->tipo = ($tipo) ? $tipo : null;
        }
	}

	public static function existe_id_tipo_contacto($coneccion, $id_tipo_contacto)
	{
		$has = 0;
		$ConsRegistrosIguales = "select id_tipo_contacto 
								from tipos_contactos
								where id_tipo_contacto = $id_tipo_contacto
								  and estado = 1";
		$MensajeErrorRegistrosIguales = "Hubo un problema al consultar los registros para validar";
		$ret = mysqli_query($coneccion->Conexion,
			$ConsRegistrosIguales
		);

		$has = mysqli_num_rows($ret);

		return $has;
	}

    //METODOS SET
    public function set_id_tipo_contacto($id_tipo_contacto)
    {
        $this->id_tipo_contacto = $id_tipo_contacto;
    }

    public function set_estado($estado){
        $this->estado = $estado;
    }

    public function set_tipo($tipo)
    {
        $this->tipo = $tipo;
    }

    //METODOS GET
    public function get_id_tipo_contacto(){
        return $this->id_tipo_contacto;
    }

    public function get_estado()
    {
        return $this->estado;
    }

    public function get_tipo()
    {
        return $this->tipo;
    }

    public function jsonSerialize() {
        return [
        'estado' => $this->estado,
        'id_tipo_contacto' => $this->id_tipo_contacto,
        'tipo' => $this->tipo
        ];
    }

    public function update()
    {
        $consulta = "update tipos_contactos
                    set estado = " . ((!is_null($this->get_estado())) ? $this->get_estado() : "null") . ",  
                        tipo = " . ((!is_null($this->get_tipo())) ? "'" . $this->get_tipo() . "'" : "null") . "
                        where id_tipo_contacto = " . $this->get_id_tipo_contacto();
        $mensaje = "No se pudo actualizar la Persona";
        if (!$ret = mysqli_query($this->coneccion->Conexion, $consulta)) {
            throw new Exception($mensaje . $consulta, 2);
        }
    }

    public function save(){

        $consulta = "INSERT INTO tipos_contactos(
                                        id_tipo_contacto,
                                        tipo,
                                        estado 
                    )
                    VALUES (" . ((!is_null($this->get_id_tipo_contacto())) ? $this->get_id_tipo_contacto() : "null") . ",
                            " . ((!is_null($this->get_tipo())) ? "'" . $this->get_tipo() . "'" : "null") . ",
                            " . ((!is_null($this->get_estado())) ? $this->get_estado() : "null") . "
                    )";
        $mensaje = "No se pudo insertar";
        $ret = mysqli_query($this->coneccion->Conexion, $consulta);
        if (!$ret) {
            throw new Exception($mensaje . $consulta, 2);
        }
        $this->id_tipo_contacto = mysqli_insert_id($this->coneccion->Conexion);
    }

	function delete()
	{

		$query = "update tipos_contactos
				  set estado = 0
				  where id_tipo_contacto = " . $this->get_id_tipo_contacto();
		$mensaje = "No se pudo eliminar";
		$ret = mysqli_query($this->coneccion->Conexion, $query);
		if (!$ret) {
		    throw new Exception($mensaje . $query, 2);
		}

	}
}