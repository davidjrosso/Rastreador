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
}
