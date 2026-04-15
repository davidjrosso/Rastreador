<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Accion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Modelo/Parametria.php');

class Persona implements JsonSerializable {
	//DECLARACION DE VARIABLES
	private $coneccion;
	private $Apellido;
	private $DNI;
	private $Edad;
	private $Estado;
	private $Fecha_Nacimiento;
	private $ID_Escuela;
	private $ID_Persona;
	private $Meses;
	private $Nombre;
	private $Obra_Social;
	private $Observaciones;


	public function __construct(
		$coneccion = null,
		$ID_Persona = null,
		$xApellido = null,
		$xNombre = null,
		$xDNI = null,
		$xEdad = null,
		$xMeses = null,
		$xFecha_Nacimiento = null,
		$xObra_Social = null,
		$xObservaciones = null,
		$xID_Escuela = null,
		$xEstado = null,
	) {
		$this->coneccion = $coneccion;
		if (!$ID_Persona) {
			$this->Apellido = $xApellido;
			$this->DNI = $xDNI;
			$this->Edad = $xEdad;
			$this->Estado = $xEstado;
			$this->Fecha_Nacimiento = $xFecha_Nacimiento;
			$this->ID_Escuela = $xID_Escuela;	
			$this->ID_Persona =$ID_Persona;
			$this->Meses = $xMeses;
			$this->Nombre = $xNombre;
			$this->Obra_Social = $xObra_Social;
			$this->Observaciones = $xObservaciones;
		} else {
			$Con = new Conexion();
			$Con->OpenConexion();
			$ConsultarPersona = "select *
								 from personas 
								 where ID_Persona = " . $ID_Persona . " 
								   and estado = 1";
			$EjecutarConsultarPersona = mysqli_query(
				$this->coneccion->Conexion,
				$ConsultarPersona) or die("Problemas al consultar filtro Persona");
			$ret = mysqli_fetch_assoc($EjecutarConsultarPersona);
	
			$ID_Persona = $ret["id_persona"];
			$apellido = $ret["apellido"];
			$nombre = $ret["nombre"];
			$dni = $ret["documento"];
			$edad = $ret["edad"];
			$meses = $ret["meses"];
			if(is_null($ret["fecha_nac"]) || $ret["fecha_nac"] == "null") {
				$fecha_nacimiento = "No se cargo fecha de nacimiento";
			} else {
				$fecha_nacimiento = implode("/", array_reverse(explode("-",$ret["fecha_nac"])));
			}

			$obra_Social = $ret["obra_social"];
			$observacion = $ret["observacion"];
			$ID_Escuela = $ret["ID_Escuela"];
			$estado = $ret["estado"];
			$this->ID_Persona = $ID_Persona;
			$this->Apellido = ($xApellido) ? $xApellido : $apellido;
			$this->Nombre = ($xNombre) ? $xNombre : $nombre;
			$this->DNI = ($xDNI) ? $xDNI : $dni;
			$this->Edad = ($xEdad) ? $xEdad : $edad;
			$this->Meses = ($xMeses) ? $xMeses : $meses;
			$this->Fecha_Nacimiento = ($xFecha_Nacimiento) ? $xFecha_Nacimiento : $fecha_nacimiento;
			$this->Obra_Social = ($xObra_Social) ? $xObra_Social : $obra_Social;
			$this->Observaciones = ($xObservaciones) ? $xObservaciones : $observacion;
			$this->ID_Escuela = ($xID_Escuela) ? $xID_Escuela : $ID_Escuela;	
			$this->Estado = ($xEstado) ? $xEstado : $estado;
			$Con->CloseConexion();
		}
	}


	//METODOS SET
	public function setID_Persona($xID_Persona){
		$this->ID_Persona = $xID_Persona;
	}

	public function setApellido($xApellido){
		$this->Apellido = $xApellido;
	}

	public function setNombre($xNombre){
		$this->Nombre = $xNombre;
	}

	public function setDNI($xDNI){
		$this->DNI = $xDNI;
	}

	public function setEdad($xEdad){
		$this->Edad = $xEdad;
	}

	public function setMeses($xMeses){
		$this->Meses = $xMeses;
	}

	public function setFecha_Nacimiento($xFecha_Nacimiento){
		$this->Fecha_Nacimiento = $xFecha_Nacimiento;
	}

	public function setObra_Social($xObra_Social){
		$this->Obra_Social = $xObra_Social;
	}

	public function setObservaciones($xObservaciones){
		$this->Observaciones = $xObservaciones;
	}

	public function setEstado($xEstado){
		$this->Estado = $xEstado;
	}

	public function setID_Escuela($xID_Escuela){
		$this->ID_Escuela = $xID_Escuela;
	}

	//METODOS GET
	public function getID_Persona(){
		return $this->ID_Persona;
	}

	public function getApellido(){
		return $this->Apellido;
	}

	public function getNombre(){
		return $this->Nombre;
	}

	public function getDNI(){
		return $this->DNI;
	}

	public function getEdad(){
		return $this->Edad;
	}

	public function getMeses(){
		return $this->Meses;
	}

	public function getFecha_Nacimiento(){
		return $this->Fecha_Nacimiento;
	}

	public function getObra_Social(){
		return $this->Obra_Social;
	}

	public function getObservaciones()
	{
		return $this->Observaciones;
	}

	public function getEstado()
	{
		return $this->Estado;
	}

	public function getID_Escuela()
	{
		return $this->ID_Escuela;
	}

	public function getEscuela()
	{
		$ConsultarEscuela = "select Escuela 
							from escuelas 
							where ID_Escuela = {$this->ID_Escuela}";
		$MensajeErrorConsultarEscuela = "No se pudo consultar la Escuela";
		$EjecutarConsultarEscuela = mysqli_query(
			$this->coneccion->Conexion,
			$ConsultarEscuela
			) or die($MensajeErrorConsultarEscuela);
		$RetEscuela = mysqli_fetch_assoc($EjecutarConsultarEscuela);
		$RetEscuela["Escuela"];
		return $RetEscuela["Escuela"];
	}

	public static function is_exist($coneccion, $id_persona)
	{
		$consulta = "select * 
					from personas 
					where id_persona = $id_persona 
					and estado = 1";
		$mensaje_error = "Hubo un problema al consultar los registros para validar";
		$ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		) or die(
			$mensaje_error
		);
		$is_multiple = (mysqli_num_rows($ret) >= 1);
		return $is_multiple;
	}

	public static function is_registered($documento)
	{
		$Con = new Conexion();
		$Con->OpenConexion();

		$ConsRegistrosIguales = "select id_persona 
								from personas 
								where documento like '%" . $documento. "%' 
								and estado = 1";
		$MensajeErrorRegistrosIguales = "Hubo un problema al consultar los registros para validar";
		$ret = mysqli_query($Con->Conexion,
			$ConsRegistrosIguales
		) or die(
			$MensajeErrorRegistrosIguales . " Consulta: " . $ConsRegistrosIguales
		);
		$is_multiple = (mysqli_num_rows($ret) >= 1);
		$Con->CloseConexion();
		return $is_multiple;
	}

	public static function is_registered_with_id($coneccion, $documento, $id_persona)
	{
		$ConsRegistrosIguales = "select id_persona 
								from personas 
								where documento like '%" . $documento. "%' 
								  and id_persona <> $id_persona
								and estado = 1";
		$MensajeErrorRegistrosIguales = "Hubo un problema al consultar los registros para validar";
		$ret = mysqli_query($coneccion->Conexion,
			$ConsRegistrosIguales
		) or die(
			$MensajeErrorRegistrosIguales . " Consulta: " . $ConsRegistrosIguales
		);
		$is_multiple = (mysqli_num_rows($ret) >= 1);
		return $is_multiple;
	}


	public static function get_id_persona_by_dni($coneccion, $documento)
	{
		$consulta = "select id_persona 
					from personas 
					where documento like '%" . $documento. "%' 
					and estado = 1";
		$mensaje_error = "Hubo un problema al consultar el id de la persona";
		$ret = mysqli_query(
					$coneccion->Conexion,
					$consulta
		) or die(
			$mensaje_error . " Consulta: " . $consulta
		);
		$row = mysqli_fetch_assoc($ret);
		$id = (empty($row["id_persona"])) ? null : $row["id_persona"];
		return $id;
	}

	public function jsonSerialize() {
		return [
		'ID_Persona' => $this->ID_Persona,
		'Nombre' => $this->Nombre,
		'Apellido' => $this->Apellido,
		'DNI' => $this->DNI,
		'Edad' => $this->Edad,
		'Meses' => $this->Meses,
		'Fecha_Nacimiento' => $this->Fecha_Nacimiento,
		'Obra_Social' => $this->Obra_Social,
		'Observaciones' => $this->Observaciones,
		'ID_Escuela' => $this->ID_Escuela,	
		'Estado' => $this->Estado,
		];
	}

	public function update()
	{
		$fecha = implode(
			"-",
				array_reverse(explode(
										"/",
											$this->getFecha_Nacimiento()
													)
									)
						);
		$Consulta = "update personas 
					set apellido = " . ((!is_null($this->getApellido())) ? "'" . $this->getApellido() . "'" : "null") . ", 
						nombre = " . ((!is_null($this->getNombre())) ? "'" . $this->getNombre() . "'" : "null") . ", 
						documento = " . ((!is_null($this->getDNI())) ? "'" . $this->getDNI() . "'" : "null") . ", 
						edad = " . ((!is_null($this->getEdad())) ? "'" . $this->getEdad() . "'" : "null") . ", 
						fecha_nac = " . ((!is_null($this->getFecha_Nacimiento())) ? "'" . $fecha . "'" : "null") . ", 
						obra_social = " . ((!is_null($this->getObra_Social())) ? "'" . $this->getObra_Social() . "'" : "null") . ", 
						observacion = " . ((!is_null($this->getObservaciones())) ? "'" . $this->getObservaciones() . "'" : "null") . ", 
						ID_Escuela = " . ((!is_null($this->getID_Escuela())) ? "'" . $this->getID_Escuela() . "'" : "null") . ", 
						meses = " . ((!is_null($this->getMeses())) ? "'" . $this->getMeses() . "'" : "null") . ", 
					where id_persona = " . $this->getID_Persona();
					$MensajeErrorConsultar = "No se pudo actualizar la Persona";
					if (!$Ret = mysqli_query($this->coneccion->Conexion, $Consulta)) {
						throw new Exception($MensajeErrorConsultar . $Consulta, 2);
					}
	}


	public function update_edad_meses()
	{

		$Edad = (isset($this->Edad)) ? $this->Edad : null;
		$Meses = (isset($this->Meses)) ? $this->Meses : null;
		$Fecha_Nacimiento = $this->Fecha_Nacimiento;
		if ($Fecha_Nacimiento != 'null' && !empty($Fecha_Nacimiento)) {
			if (substr_count("-", $Fecha_Nacimiento)) {
				list($ano, $mes, $dia) = explode("-", $Fecha_Nacimiento);
			} else {
				list($ano, $mes, $dia) = explode("/", $Fecha_Nacimiento);
			}
			$ano_diferencia = date("Y") - $ano;
			$mes_diferencia = date("m") - $mes;
			$dia_diferencia = date("d") - $dia;
			if ($ano_diferencia > 0) {
				if ($mes_diferencia == 0) {
					if ($dia_diferencia < 0) {
						$ano_diferencia--;
					}
				} elseif ($mes_diferencia < 0) {
					$ano_diferencia--;
				}
			} else {
				if ($mes_diferencia > 0) {
					if ($dia_diferencia < 0) {
						$mes_diferencia--;
					}
				}
			}
			$Edad = $ano_diferencia;
			$Meses = $mes_diferencia;
		}

		//PROBAR SI ESTO DA LA DIFERENCIA ENTRE MESES NOMAS O TAMBIEN TOMA LOS AÑOS COMO MESES EN ESE CASO TOMAR LA CANTIDAD DE AÑOS Y MULTIPLICARLO POR 12 Y A ESO RESTARLE AL RESULTADO DEL TOTAL DE MESES DE DIFERENCIA.
		if ($Fecha_Nacimiento != 'null' && !empty($Fecha_Nacimiento)) {
			$Fecha_Actual = new DateTime();
			if (substr_count("-", $Fecha_Nacimiento)) {
				$fecha_activacion_registrada = DateTime::createFromFormat('d-m-Y', 
																		$Fecha_Nacimiento);
				$Diferencia = $fecha_activacion_registrada->diff($Fecha_Actual);
				$Meses = $Diferencia->m;
				$Edad = $Diferencia->y;
			} else if (substr_count("/", $Fecha_Nacimiento)){
				$fecha_activacion_registrada = DateTime::createFromFormat('d/m/Y', 
																		$Fecha_Nacimiento);
				$Diferencia = $fecha_activacion_registrada->diff($Fecha_Actual);
				$Meses = $Diferencia->m;
				$Edad = $Diferencia->y;
			}
		}

		$consulta = "update personas
					set edad = " . ((!is_null($Edad)) ? "'" . $Edad . "'" : "null") . ", 
						meses = " . ((!is_null($Meses)) ? "'" . $Meses . "'" : "null") . " 
					where id_persona = " . $this->getID_Persona();
		$MensajeErrorConsultar = "No se pudo actualizar la Persona";
		if (!$Ret = mysqli_query($this->coneccion->Conexion, $consulta)) {
			throw new Exception($MensajeErrorConsultar . $consulta, 2);
		}
	}

	public function save(){
		$consulta = "INSERT INTO personas (
										apellido, 
										nombre, 
										documento, 
										edad, 
										fecha_nac, 
										obra_social,
										observacion, 
										ID_Escuela, 
										meses, 
										estado 
					)
					VALUES ( " . ((!is_null($this->getApellido())) ? "'" . $this->getApellido() . "'" : "null") . ", 
							" . ((!is_null($this->getNombre())) ? "'" . $this->getNombre() . "'" : "null") . ", 
							" . ((!is_null($this->getDNI())) ? "'" . $this->getDNI() . "'" : "null") . ", 
							" . ((!is_null($this->getEdad())) ? $this->getEdad() : "null") . ", 
							" . ((!is_null($this->getFecha_Nacimiento())) ? "'" . $this->getFecha_Nacimiento() . "'" : "null") . ", 
							" . ((!is_null($this->getObra_Social())) ? "'" . $this->getObra_Social() . "'" : "null") . ", 
							" . ((!is_null($this->getObservaciones())) ? "'" . $this->getObservaciones() . "'" : "null") . ", 
							" . ((!is_null($this->getID_Escuela())) ? $this->getID_Escuela() : "null") . ", 
							" . ((!is_null($this->getMeses())) ? "'" . $this->getMeses() . "'" : "null") . ", 
							1
					)";
					$MensajeErrorConsultar = "No se pudo insertar la Persona";
					$ret = mysqli_query($this->coneccion->Conexion, $consulta);
					if (!$ret) {
						throw new Exception($MensajeErrorConsultar . $consulta, 2);
					}
					$this->ID_Persona = mysqli_insert_id($this->coneccion->Conexion);
	}

	function delete()
	{
		$Con = new Conexion();
		$Con->OpenConexion();

		$query = "update personas
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
