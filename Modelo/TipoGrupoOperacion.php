<?php

class TipoGrupoOperacion
{
	// DECLARACION DE VARIABLES
	private $estado;
	private $id_tipo_grupo_operacion;
	private $tipo;
    private $coneccion_base;

	public function __construct(
		                        $id_tipo_grupo_operacion = null,
		                        $tipo = null,
                                $coneccion_base = null,
								$estado = null
	) {
        if (!$id_tipo_grupo_operacion) {
            $this->tipo = $tipo;
			$this->estado = $estado;
            $this->coneccion_base = $coneccion_base;
        } else {
            $consulta = "select * 
                        from tipo_grupo_operacion 
                        where id_tipo_grupo_operacion = '$id_tipo_grupo_operacion'";
            $rs = mysqli_query(
                                $this->coneccion_base->Conexion,
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

	public static function get_lista_tipo_grupo_operacion($id_tipo_grupo_operacion)
	{
		$con = new Conexion();
		$con->OpenConexion();
		$filtro = ($id_tipo_grupo_operacion) ? null : " and lower(tipo) like lower('%$id_tipo_grupo_operacion%')"; 
		$consulta = "select * 
					 from tipo_grupo_operacion 
					 where estado = 1
					" . $filtro . "
					order by Fecha desc";
		$rs = mysqli_query($con->Conexion,$consulta) or die("Problemas al consultar.");
		$lista_acciones = [];
		while ($ret = mysqli_fetch_assoc($rs)) {
			$lista_acciones[] = new self(
									   coneccion_base: $con, 
									   id_tipo_grupo_operacion: $ret["id_tipo_grupo_operacion"]
									  );
		}
		return $lista_acciones;
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
		$consulta = "delete tipo_grupo_operacion
					 where id_tipo_grupo_operacion = " . $this->id_tipo_grupo_operacion;
		if(!$accion = mysqli_query($this->coneccion_base->Conexion, $consulta)){
			throw new Exception("Error al intentar eliminar.", 3);
		}
	}


	public function save()
	{
		$consulta = "insert into tipo_grupo_operacion(
                                                     id_tipo_grupo_operacion,
                                                     tipo,
													 estado
                                                     ) 
									values (" . $this->id_tipo_grupo_operacion . ",'" 
											. ((!$this->tipo) ? "null" : $this->tipo) . "',
											1
                                            )";
		if(!$RetAccion = mysqli_query($this->coneccion_base->Conexion, $consulta)){
			throw new Exception("Error al intentar registrar.", 3);
		}
        $this->id_tipo_grupo_operacion = mysqli_insert_id(
                                            $this->coneccion_base->Conexion
                                                );

	}

	public function update()
	{
		$consulta = "update tipo_grupo_operacion
							set tipo = '" . ((!$this->tipo) ? "null" : $this->tipo) . "',
								estado = " . ((!$this->estado) ? "null" : $this->estado) . "
							where id_tipo_grupo_operacion = " . $this->id_tipo_grupo_operacion;
		if(!$RetAccion = mysqli_query($this->coneccion_base->Conexion, $consulta)){
			throw new Exception("Error al intentar actualizar. Consulta: ". $consulta, 3);
		}
	}

}