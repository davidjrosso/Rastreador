<?php  
class CentroSalud implements JsonSerializable
{
	//DECLARACION DE VARIABLES
	private $id_centro;
	private $centro_salud;
	private $id_barrio;
	private $estado;
	private $coneccion_base;

	public function __construct(
			$coneccion_base=null,
			$id_centro=null,
			$centro_salud=null,
			$id_barrio=null,
			$estado=null
	) {
		$this->coneccion_base = $coneccion_base;
		if (!$id_centro) {
			$this->id_centro = $id_centro;
			$this->centro_salud = $centro_salud;
			$this->id_barrio = $id_barrio;
			$this->estado = $estado;
		} else {
			$consultar = "select *
                          from centros_salud 
                          where id_centro = " . $id_centro . " 
                            and estado = 1";
			$ejecutar_consultar = mysqli_query(
			$this->coneccion_base->Conexion, 
			$consultar) or die("Problemas al consultar filtro centro");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$row_id_centro = $ret["id_centro"];
				$row_centro_salud = $ret["centro_salud"];
				$row_id_barrio = $ret["id_barrio"];
				$row_estado = $ret["estado"];

				$this->id_centro = $row_id_centro;
				$this->centro_salud = $row_centro_salud;
				$this->id_barrio = $row_id_barrio;
				$this->estado = $row_estado;
			}
		}
	}

	public static function is_exist($coneccion, $id_centro)
	{
		$consulta = "select * 
					 from centros_salud 
					 where id_centro = $id_centro 
					   and estado = 1";
		$mensaje_error = "Hubo un problema al consultar los registros para validar";
		$Ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		) or die(
			$mensaje_error
		);
		$is_multiple = (mysqli_num_rows($Ret) >= 1);
		return $is_multiple;
	}

    public static function exist_nombre_con_diferente_id($coneccion, $nombre, $id_centro_salud)
	{
		$cant = 0;
		$consulta = "select * 
					 from centros_salud 
					 where centro_salud = '$nombre' 
					   and id_centro != $id_centro_salud
					   and estado = 1";

		$mensaje = "Hubo un problema al consultar los registros";
		$ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		);
		if (!$ret) throw new Exception($mensaje, 2);

		$cant = mysqli_num_rows($ret);
		return $cant;
    }

    public static function get_id_por_nombre($coneccion, $centro_salud)
	{
		$id_centro = 0;
        $consulta = "select * 
					 from centros_salud 
					 where centro_salud like '%$centro_salud%' 
					   and estado = 1";
		$mensaje_error = "Hubo un problema al consultar los registros";
		$ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		) or die(
			$mensaje_error
		);
		$row = mysqli_fetch_assoc($ret);
        $id_centro = (!empty($row["id_centro"])) ? $row["id_centro"] : 0;
		return $id_centro;
    }

    public static function get_list_nombre($coneccion, $centro_salud)
	{

		$list = [];
		$centro = null;
		if ($centro_salud) {
			$query = "SELECT id_centro, REPLACE(centro_salud, '\'', '') as centro_salud_s
					  FROM centros_salud
					  WHERE centro_salud LIKE '%$centro_salud%'
						and estado = 1";
			$mensaje = "Hubo un problema al consultar los registros";
			$ret = mysqli_query(
						$coneccion->Conexion,
						$query
			);

			if (!$ret) throw new Exception($mensaje, 2);

			while($row = mysqli_fetch_assoc($ret)) {
				$centro = new self(coneccion_base: $coneccion, 
								   id_centro: $row["id_centro"]
								   );
				$centro->set_centro_salud($row["centro_salud_s"]);
				$list[] = $centro;
			}

		}

		return $list;
    }

	// METODOS SET
	public function set_id_centro($id_centro){
		$this->id_centro = $id_centro;
	}

	public function set_centro_salud($centro_salud){
		$this->centro_salud = $centro_salud;
	}
	public function set_id_barrio($id_barrio){
		$this->id_barrio = $id_barrio;
	}

	public function set_estado($estado){
		$this->estado = $estado;
	}

	public function set_coneccion_base($coneccion_base){
		$this->coneccion_base = $coneccion_base;
	}

	//METODOS GET
	public function get_id_centro(){
		return $this->id_centro;
	}

	public function get_centro_salud(){
		return $this->centro_salud;
	}
	public function get_id_barrio(){
		return $this->id_barrio;
	}

	public function get_estado(){
		return $this->estado;
	}

	public function get_coneccion_base(){
		return $this->coneccion_base;
	}

	public function jsonSerialize() : mixed 
	{
		return [
			'id_centro' => $this->id_centro,
			'centro_salud' => $this->centro_salud,
			'estado' => $this->estado
		];
	}

	public function delete()
	{
		$consulta = "delete centros_salud
					 where id_centro = " . $this->get_id_centro();
		$mensaje_error = "No se pudo eliminar el centro de salud";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function udpate(){
		$consulta = "update centros_salud
					 set centro_salud = " . (($this->get_centro_salud()) ? "'" . $this->get_centro_salud() . "'" : "null") . ", 
						 estado = " . (($this->get_estado()) ? $this->get_estado() : "null") . "
					 where id_centro = " . $this->get_id_centro();
		$mensaje_error = "No se pudo modificar el centro de salud";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}
	public function save(){
		$consulta = "insert into centros_salud (
                                                id_centro,
                                                centro_salud,
                                                estado
                                                ) 
				values(
						" . (($this->get_id_centro()) ? $this->get_id_centro() : "null") . ",
						" . (($this->get_centro_salud()) ? "'" . $this->get_centro_salud() . "'" : "null") . ",
						" . (($this->get_estado()) ? $this->get_estado() : "null") . "
						)";
		$mensaje_error = "No se pudo insertar el centro de salud";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
		$this->id_centro = mysqli_insert_id($this->coneccion_base->Conexion);
	}
}