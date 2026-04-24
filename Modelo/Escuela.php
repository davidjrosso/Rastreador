<?php  
class Escuela
{
	//DECLARACION DE VARIABLES
	private $coneccion_base;
	private $ID_Escuela;
	private $Codigo;
	private $Escuela;
	private $CUE;
	private $Localidad;
	private $Departamento;
	private $Directora;
	private $Telefono;
	private $Mail;
	private $ID_Nivel;
	private $observaciones;
	private $Estado;

	//METODO CONSTRUCTOR
	public function __construct(
								$coneccion_base=null,
								$xID_Escuela=null,
								$xCodigo=null,
								$xEscuela=null,
								$xCUE=null,
								$xLocalidad=null,
								$xDepartamento=null,
								$xDirectora=null,
								$xTelefono=null,
								$xMail=null,
								$xID_Nivel=null,
								$observaciones=null,
								$xEstado=null
	) {
		$this->coneccion_base = $coneccion_base;
		if (!$xID_Escuela) {
			$this->ID_Escuela = $xID_Escuela;
			$this->Codigo = $xCodigo;
			$this->Escuela = $xEscuela;
			$this->CUE = $xCUE;
			$this->Localidad = $xLocalidad;
			$this->Departamento = $xDepartamento;
			$this->Directora = $xDirectora;
			$this->Telefono = $xTelefono;
			$this->Mail = $xMail;
			$this->observaciones = $observaciones;
			$this->ID_Nivel = $xID_Nivel;
			$this->Estado = ($xEstado) ? $xEstado : 1;
		} else {
			$consultar_usuario = "select *
								 from escuelas 
								 where ID_Escuela = " . $xID_Escuela . " 
								   and estado = 1";
			$ejecutar_consultar = mysqli_query(
				$this->coneccion_base->Conexion, 
				$consultar_usuario) or die("Problemas al consultar filtro Escuelas");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {

				$row_codigo = ($xCodigo) ? $xCodigo : $ret["codigo"];
				$row_escuela = ($xEscuela) ? $xEscuela : $ret['escuela'];
				$row_cue = ($xCUE) ? $xCUE : $ret['cue'];
				$row_localidad = ($xLocalidad) ? $xLocalidad : $ret['localidad'];
				$row_departamento = ($xDepartamento) ? $xDepartamento :$ret['departamento'];
				$row_directora = ( $xDirectora) ?  $xDirectora : $ret['directora'];
				$row_telefono = ($xTelefono) ? $xTelefono : $ret['telefono'];
				$row_mail = ($xMail) ? $xMail : $ret['mail'];
				$row_observacion = ($observaciones) ? $observaciones : $ret['observaciones'];
				$row_nivel = ($xID_Nivel) ? $xID_Nivel : $ret['id_nivel'];
				$row_estado = ($xEstado) ? $xEstado : $ret['estado'];

				$this->ID_Escuela = $xID_Escuela;
				$this->ID_Escuela = $xID_Escuela;
				$this->Codigo = $row_codigo;
				$this->Escuela = $row_escuela;
				$this->CUE = $row_cue;
				$this->Localidad = $row_localidad;
				$this->Departamento = $row_departamento;
				$this->Directora = $row_directora;
				$this->Telefono = $row_telefono;
				$this->Mail = $row_mail;
				$this->observaciones = $row_observacion;
				$this->ID_Nivel = $row_nivel;
				$this->Estado = ($row_estado) ? $row_estado : 0;
			}
		}
	}

	public static function exist_name($name = null, $coneccion = null)
	{
		$query = "select *
				  from escuelas
				  where escuela like '%$name%'";
		if (!$obj = mysqli_query($coneccion->Conexion, $query))	
			throw new Exception("Error query");
		$result = mysqli_num_rows($obj);
		return $result;
	}

	public static function exist_name_with_id($name = null, $id = null, $coneccion = null)
	{
		$query = "select *
				  from escuelas
				  where escuela like '%$name%'
				  	and id_escuela <> $id";
		if (!$obj = mysqli_query($coneccion->Conexion, $query))	
			throw new Exception("Error query");
		$result = mysqli_num_rows($obj);
		return $result;
	}

	public static function exist_id($id = null, $coneccion = null)
	{
		$query = "select *
				  from escuelas
				  where id_escuela = $id
				  	and estado = 1";
		if (!$obj = mysqli_query($coneccion->Conexion, $query))	
			throw new Exception("Error query");
		$result = mysqli_num_rows($obj);
		return $result;

	}
	//METODOS SET
	public function setID_Escuela($xID_Escuela)
	{
		$this->ID_Escuela = $xID_Escuela;
	}

	public function setCodigo($xCodigo)
	{
		$this->Codigo = $xCodigo;
	}

	public function setEscuela($xEscuela)
	{
		$this->Escuela = $xEscuela;
	}

	public function setCUE($xCUE)
	{
		$this->CUE = $xCUE;
	}

	public function setLocalidad($xLocalidad)
	{
		$this->Localidad = $xLocalidad;
	}

	public function setDepartamento($xDepartamento)
	{
		$this->Departamento = $xDepartamento;
	}

	public function setDirectora($xDirectora)
	{
		$this->Directora = $xDirectora;
	}

	public function setTelefono($xTelefono)
	{
		$this->Telefono = $xTelefono;
	}

	public function setMail($xMail)
	{
		$this->Mail = $xMail;
	}

	public function set_observaciones($observaciones)
	{
		$this->observaciones = $observaciones;
	}

	public function set_coneccion_base($coneccion_base)
	{
		$this->coneccion_base = $coneccion_base;
	}

	public function setID_Nivel($xID_Nivel)
	{
		$this->ID_Nivel = $xID_Nivel;
	}

	public function setEstado($xEstado)
	{
		$this->Estado = $xEstado;
	}

	//METODOS GET
	public function getID_Escuela()
	{
		return $this->ID_Escuela;
	}

	public function getCodigo()
	{
		return $this->Codigo;
	}

	public function getEscuela()
	{
		return $this->Escuela;
	}

	public function getCUE()
	{
		return $this->CUE;
	}

	public function getLocalidad()
	{
		return $this->Localidad;
	}

	public function getDepartamento()
	{
		return $this->Departamento;
	}

	public function getDirectora()
	{
		return $this->Directora;
	}

	public function getTelefono()
	{
		return $this->Telefono;
	}

	public function getMail()
	{
		return $this->Mail;
	}

	public function getID_Nivel()
	{
		return $this->ID_Nivel;
	}

	public function getEstado()
	{
		return $this->Estado;
	}

	public function delete()
	{
		$query = "update escuelas
				  set estado = 0
				  where id_escuela = " . $this->getID_Escuela();
		$ejecutar_consultar = mysqli_query(
			$this->coneccion_base->Conexion, 
			$query) or die("Problemas en query Escuelas");
	}

	public function update()
	{
        $consulta = "update   escuelas
					 set codigo =" . (!empty($this->getCodigo()) ? "'" . $this->getCodigo() . "'": "null") . ",
					 	 	escuela =  " . (!empty($this->getEscuela()) ? "'" . $this->getEscuela() . "'": "null") . ",
							cue =  " . (!empty($this->getCUE()) ? "'" . $this->getCUE() . "'": "null") . ",
					 		localidad =  " . (!empty($this->getLocalidad()) ? "'" . $this->getLocalidad() . "'": "null") . ",
					 		departamento =  " . (!empty($this->getDepartamento()) ? "'" . $this->getDepartamento() . "'": "null") . ",
					 		directora =  " . (!empty($this->getDirectora()) ? "'" . $this->getDirectora() . "'": "null") . ",
					 		telefono =  " . (!empty($this->getTelefono()) ? "'" . $this->getTelefono() . "'": "null") . ",
					 		mail =  " . (!empty($this->getMail()) ? "'" . $this->getMail() . "'": "null") . ",
					 		id_nivel =  " . (!empty($this->getID_Nivel()) ? $this->getID_Nivel() : "null") . ",
					 		estado =  " . (!empty($this->getEstado()) ? $this->getEstado() : "null") ."
					 where id_escuela=" . $this->getID_Escuela();
							;
		if (!$obj = mysqli_query($this->coneccion_base->Conexion, $consulta))
			throw new Exception("Error query");
		
	}

	public function save()
	{
        $consulta = "insert into escuelas(codigo,escuela,cue,localidad,departamento,directora,telefono,mail,id_nivel,estado) 
					 values(" . (!empty($this->getCodigo()) ? "'" . $this->getCodigo() . "'": "null") . ","
					 		   . (!empty($this->getEscuela()) ? "'" . $this->getEscuela() . "'": "null") . ","
							   . (!empty($this->getCUE()) ? "'" . $this->getCUE() . "'": "null"). ","
					 		   . (!empty($this->getLocalidad()) ? "'" . $this->getLocalidad() . "'": "null") . ","
					 		   . (!empty($this->getDepartamento()) ? "'" . $this->getDepartamento() . "'": "null") . ","
					 		   . (!empty($this->getDirectora()) ? "'" . $this->getDirectora() . "'": "null") . ","
					 		   . (!empty($this->getTelefono()) ? "'" . $this->getTelefono() . "'": "null") . ","
					 		   . (!empty($this->getMail()) ? "'" . $this->getMail() . "'": "null") . ","
					 		   . (!empty($this->getID_Nivel()) ? $this->getID_Nivel() : "null") . ","
					 		   . (!empty($this->getEstado()) ? $this->getEstado() : "null")
							   . ")";
		if (!$ret = mysqli_query($this->coneccion_base->Conexion, $consulta))
			throw new Exception("Error query");
		$this->ID_Escuela = null;
	}
}
