<?php

class TipoGrupoOperacion
{
	// DECLARACION DE VARIABLES
	private $estado;
	private $id_tipo_grupo_operacion;
	private $tipo;
    private $coneccion;

	public function __construct(
		                        $id_tipo_grupo_operacion = null,
		                        $tipo = null,
                                $coneccion = null,
								$estado = null
	) {
        if (!$id_tipo_grupo_operacion) {
            $this->tipo = $tipo;
			$this->estado = $estado;
            $this->coneccion = $coneccion;
        } else {
            $consulta = "select * 
                        from tipo_grupo_operaciones 
                        where id_tipo_grupo_operacion = '$id_tipo_grupo_operacion'";
            $rs = mysqli_query(
                                $this->coneccion->Conexion,
                                $consulta
                                ) or die("Problemas al consultar las acciones.");
            $ret = mysqli_fetch_assoc($rs);
            if ($ret) {
				$this->estado = $ret["estado"];
                $this->id_tipo_grupo_operacion = $ret["id_tipo_grupo_operacion"];
                $this->tipo = $ret["tipo"];
            }
        }
	}

	public static function get_lista_tipo_grupo_operacion($coneccion, $tipo)
	{
		$filtro = ($tipo) ? null : " and lower(tipo) like lower('%$tipo%')"; 
		$consulta = "select * 
					 from tipo_grupo_operaciones
					 where estado = 1
					" . $filtro;
		$rs = mysqli_query($coneccion->Conexion, $consulta);
		$lista_acciones = [];
		while ($ret = mysqli_fetch_assoc($rs)) {
			$lista_acciones[] = new self(
									   coneccion: $coneccion, 
									   id_tipo_grupo_operacion: $ret["id_tipo_grupo_operacion"]
									  );
		}
		return $lista_acciones;
	}

	public static function get_id_por_tipo($coneccion, $tipo)
	{
		$id = 0;
		$consulta = "select * 
					 from tipo_grupo_operaciones
					 where estado = 1
					   and lower(tipo) like lower('%$tipo%')";
		$rs = mysqli_query($coneccion->Conexion, $consulta);
		if ($rs) {
			$ret = mysqli_fetch_assoc($rs);
			if ($ret) $id = $ret["id_tipo_grupo_operacion"];
		}

		return $id;
	}

	// METODOS SET
	public function set_id_tipo_grupo_operacion($id_tipo_grupo_operacion)
    {
		$this->id_tipo_grupo_operacion = $id_tipo_grupo_operacion;
	}

	public function set_tipo($tipo)
    {
		$this->tipo = $tipo;
	}

	public function set_estado($estado)
    {
		$this->estado = $estado;
	}

	// METODOS GET
	public function get_id_tipo_grupo_operacion(){
		return $this->id_tipo_grupo_operacion;
	}

	public function get_tipo(){
		return $this->tipo;
	}

	public function get_estado()
    {
		return $this->estado;
	}

	public function delete()
	{
		$consulta = "update tipo_grupo_operaciones
					 set estado = " . ((!$this->estado) ? "null" : $this->estado) . "
					 where id_tipo_grupo_operacion = " . $this->id_tipo_grupo_operacion;
		if(!$accion = mysqli_query($this->coneccion->Conexion, $consulta)){
			throw new Exception("Error al intentar eliminar.", 3);
		}
	}


	public function save()
	{
		$consulta = "insert into tipo_grupo_operaciones(
                                                     id_tipo_grupo_operacion,
                                                     tipo,
													 estado
                                                     ) 
									values (" . $this->id_tipo_grupo_operacion . ",'" 
											. ((!$this->tipo) ? "null" : $this->tipo) . "',
											1
                                            )";
		if(!$RetAccion = mysqli_query($this->coneccion->Conexion, $consulta)){
			throw new Exception("Error al intentar registrar.", 3);
		}
        $this->id_tipo_grupo_operacion = mysqli_insert_id(
                                            $this->coneccion->Conexion
                                                );

	}

	public function update()
	{
		$consulta = "update tipo_grupo_operaciones
							set tipo = '" . ((!$this->tipo) ? "null" : $this->tipo) . "',
								estado = " . ((!$this->estado) ? "null" : $this->estado) . "
							where id_tipo_grupo_operacion = " . $this->id_tipo_grupo_operacion;
		if(!$RetAccion = mysqli_query($this->coneccion->Conexion, $consulta)){
			throw new Exception("Error al intentar actualizar. Consulta: ". $consulta, 3);
		}
	}

}