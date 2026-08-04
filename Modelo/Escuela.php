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
	){
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
				$row_codigo = $ret['Codigo'];
				$row_escuela = $ret['Escuela'];
				$row_cue = $ret['CUE'];
				$row_localidad = $ret['Localidad'];
				$row_departamento = $ret['Departamento'];
				$row_directora = $ret['Directora'];
				$row_telefono = $ret['Telefono'];
				$row_mail = $ret['Mail'];
				$row_observacion = $ret['Observaciones'];
				$row_nivel = $ret['ID_Nivel'];
				$row_estado = $ret['Estado'];

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

	public static function exist_nombre($name = null, $coneccion = null)
	{
		$query = "select *
				  from escuelas
				  where Escuela like '%$name%'";
		if (!$obj = mysqli_query($coneccion->Conexion, $query))	
			throw new Exception("Error query");
		$result = mysqli_num_rows($obj);
		return $result;
	}

	public static function exist_nombre_con_id($name = null, $id = null, $coneccion = null)
	{
		$query = "select *
				  from escuelas
				  where Escuela like '%$name%'
				  	and ID_Escuela <> $id";
		if (!$obj = mysqli_query($coneccion->Conexion, $query))	
			throw new Exception("Error query");
		$result = mysqli_num_rows($obj);
		return $result;
	}

	public static function exist_id($id = null, $coneccion = null)
	{
		$query = "select *
				  from escuelas
				  where ID_Escuela = $id
				  	and Estado = 1";
		if (!$obj = mysqli_query($coneccion->Conexion, $query))	
			throw new Exception("Error query");
		$result = mysqli_num_rows($obj);
		return $result;

	}

	public static function get_list_por_nombre($coneccion, $nombre)
	{
		$list = [];
		if ($nombre) {
			$consultar = "SELECT * 
						  FROM escuelas E, nivel_escuelas N 
						  WHERE E.ID_Nivel = N.ID_Nivel and E.Escuela LIKE '%$nombre%'
								and E.Estado = 1";
			$mensaje = "No se pudieron consultar los casos de igualdad en el Escuela";

			$ejec = mysqli_query($coneccion->Conexion, $consultar);
			if (!$ejec) throw new Exception($mensaje, 2);

			$list = mysqli_fetch_all($ejec);
		}
		return $list;

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
				  set Estado = 0
				  where ID_Escuela = " . $this->getID_Escuela();
		$ejecutar_consultar = mysqli_query(
			$this->coneccion_base->Conexion, 
			$query) or die("Problemas en query Escuelas");
	}

	public function update()
	{
        $consulta = "update escuelas
					 set Codigo =" . (!empty($this->getCodigo()) ? "'" . $this->getCodigo() . "'": "null") . ",
					 	 	Escuela =  " . (!empty($this->getEscuela()) ? "'" . $this->getEscuela() . "'": "null") . ",
							CUE =  " . (!empty($this->getCUE()) ? "'" . $this->getCUE() . "'": "null") . ",
					 		Localidad =  " . (!empty($this->getLocalidad()) ? "'" . $this->getLocalidad() . "'": "null") . ",
					 		Departamento =  " . (!empty($this->getDepartamento()) ? "'" . $this->getDepartamento() . "'": "null") . ",
					 		Directora =  " . (!empty($this->getDirectora()) ? "'" . $this->getDirectora() . "'": "null") . ",
					 		Telefono =  " . (!empty($this->getTelefono()) ? "'" . $this->getTelefono() . "'": "null") . ",
					 		Mail =  " . (!empty($this->getMail()) ? "'" . $this->getMail() . "'": "null") . ",
					 		ID_Nivel =  " . (!empty($this->getID_Nivel()) ? $this->getID_Nivel() : "null") . ",
					 		Estado =  " . (!empty($this->getEstado()) ? $this->getEstado() : "null") ."
					 where ID_Escuela=" . $this->getID_Escuela();
							;
		if (!$obj = mysqli_query($this->coneccion_base->Conexion, $consulta))
			throw new Exception("Error query");
		
	}

	public function save()
	{
        $consulta = "insert into escuelas(Codigo,
										  Escuela,
										  CUE,
										  Localidad,
										  Departamento,
										  Directora,
										  telefono,
										  Mail,
										  ID_Nivel,
										  Estado) 
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
