<?php

class TipoAccion 
{
	// DECLARACION DE VARIABLES
	private $id_tipo_accion;
	private $tipo;
    private $coneccion_base;

	public function __construct(
		                        $id_tipo_accion = null,
		                        $tipo = null,
                                $coneccion_base = null
	) {
        if (!$id_tipo_accion) {
            $this->tipo = $tipo;
            $this->coneccion_base = $coneccion_base;
        } else {
            $consulta = "select * 
                        from TipoAcciones 
                        where ID_TipoAccion = '$id_tipo_accion'";
            $rs = mysqli_query(
                                $this->coneccion_base->Conexion,
                                $consulta
                                ) or die("Problemas al consultar las acciones.");
            $ret = mysqli_fetch_assoc($rs);
            if ($ret) {
                $this->id_tipo_accion = $ret["ID_TipoAccion"];
                $this->tipo = $ret["Tipo"];
            }
        }
	}

	public static function get_tipo_accion($coneccion_base)
    {
		$con = new Conexion();
		$con->OpenConexion();
		$consulta = "select * 
					 from TipoAcciones";
		$rs = mysqli_query(
                           $coneccion_base->Conexion,
                           $consulta
                           ) or die("Problemas al consultar las acciones.");
		$lista_tipo_acciones = [];
		while ($ret = mysqli_fetch_assoc($rs)) {
			$row["ID_TipoAccion"] = ((!empty($ret["ID_TipoAccion"])) ? $ret["ID_TipoAccion"] : null);
			$row["Tipo"] = ((!empty($ret["Tipo"])) ? $ret["Tipo"] : null);
			$lista_tipo_acciones[] = $row;
		}
		return $lista_tipo_acciones;
	}

	public static function get_tipo_acciones_by_id($coneccion_base, $id_tipo_accion)
    {
		$con = new Conexion();
		$con->OpenConexion();
		$consulta = "select * 
					from TipoAcciones 
					where ID_TipoAccion = '$id_tipo_accion'";
		$rs = mysqli_query(
                            $coneccion_base->Conexion,
                            $consulta
                            ) or die("Problemas al consultar las acciones.");
		$lista_tipo_acciones = [];
		while ($ret = mysqli_fetch_assoc($rs)) {
			$row["ID_TipoAccion"] = ((!empty($ret["ID_TipoAccion"])) ? $ret["ID_TipoAccion"] : null);
			$row["Tipo"] = ((!empty($ret["Tipo"])) ? $ret["Tipo"] : null);
			$lista_tipo_acciones[] = $row;
		}
		return $lista_tipo_acciones;
	}

	public static function get_acciones_tipo($id_tipo_accion){
		$con = new Conexion();
		$con->OpenConexion();
		$filtro = ($id_tipo_accion == 0) ? null : " and ID_TipoAccion = $id_tipo_accion"; 
		$consulta = "select * 
					 from TipoAcciones 
					 where accountid is not null
					" . $filtro . "
					order by Fecha desc";
		$rs = mysqli_query($con->Conexion,$consulta) or die("Problemas al consultar las acciones.");
		$lista_acciones = [];
		while ($ret = mysqli_fetch_assoc($rs)) {
			$row["accountid"] = ((!empty($ret["accountid"])) ? $ret["accountid"] : null);
			$row["Detalles"] = ((!empty($ret["Detalles"])) ? $ret["Detalles"] : null);
			$row["Fecha"] = ((!empty($ret["Fecha"])) ? $ret["Fecha"] : null);
			$row["ID_TipoAccion"] = (!empty($ret["ID_TipoAccion"])) ? $ret["ID_TipoAccion"] : null;
			$lista_acciones[] = $row;
		}
		return $lista_acciones;
	}

	// METODOS SET
	public function set_id_tipo_accion($id_tipo_accion)
    {
		$this->id_tipo_accion = $id_tipo_accion;
	}

	public function set_tipo($tipo)
    {
		$this->tipo = $tipo;
	}

	// METODOS GET
	public function get_id_tipo_accion(){
		return $this->id_tipo_accion;
	}

	public function get_tipo(){
		return $this->tipo;
	}

	public function save() {
		$consulta_accion = "insert into TipoAcciones(
                                                     ID_TipoAccion,
                                                     Tipo
                                                     ) 
									values (" . $this->id_tipo_accion . ",'" 
											. ((!$this->tipo) ? "null" : $this->tipo) . "'
                                            )";
		if(!$RetAccion = mysqli_query($this->coneccion_base->Conexion,$consulta_accion)){
			throw new Exception("Error al intentar registrar Accion. Consulta: ". $consulta_accion, 3);
		}
        $this->id_tipo_accion = mysqli_insert_id(
                                            $this->coneccion_base->Conexion
                                                );

	}

}
