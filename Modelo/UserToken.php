<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Controladores/Conexion.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Account.php");

class UserToken implements JsonSerializable
{
	private $token;
    private $account_id;
	private $fecha_creacion;
	private $fecha_expiracion;
    private $estado;
	private $coneccion_base;

	public function __construct(
		$coneccion_base=null,
		$token=null,
		$account_id=null,
		$fecha_creacion=null,
        $estado=null,
		$fecha_expiracion=null
	) {
		$this->coneccion_base = $coneccion_base;
		$fecha_actual = DateTime::createFromFormat(format: 'Y-m-d', datetime: date('Y-m-d'));
		$fecha_exp = DateTime::createFromFormat(format: 'Y-m-d', datetime: date('Y-m-d'));
        $intervalo = new DateInterval('P1D');
        $fecha_exp->add($intervalo);

		if ($fecha_expiracion && $account_id) {
			$this->token = $this->token_account();
			$this->account_id = $account_id;
			$this->fecha_creacion = ($fecha_creacion) ? $fecha_creacion : $fecha_actual->format('Y-m-d');
			$this->fecha_expiracion = ($fecha_expiracion) ? $fecha_expiracion : $fecha_exp->format('Y-m-d');
			$this->estado = ($estado) ? $estado : 1;
		} else if (!$fecha_expiracion && $account_id) {
			$consultar = "select *
						  from users_tokens
						  where accountid = " . $account_id . "
						  	and estado = 1";
			$ejecutar_consultar = mysqli_query(
				$this->coneccion_base->Conexion,
				$consultar) or die("Problemas al consultar filtro token");
			$ret = mysqli_fetch_assoc($ejecutar_consultar);
			if (!is_null($ret)) {
				$query_token = $ret["token"];
				$query_account_id= $ret["accountid"];
				$query_fecha_creacion = $ret["fecha_creacion"];
				$query_fecha_expiracion = $ret["fecha_expiracion"];
                $query_estado = $ret["estado"];
				$this->token = $query_token;
				$this->account_id = $query_account_id;
				$this->fecha_creacion = $query_fecha_creacion;
				$this->fecha_expiracion = $query_fecha_expiracion;
                $this->estado = $query_estado;
			}
		}
	}

	public static function has_token_valid(
								 $coneccion,
								 $account_id
	)	{
        $fecha = date("Y-m-d");
		$consultar = "select *
					  from users_tokens
					  where accountid = " . $account_id . " 
					  	and	fecha_expiracion >= '" . $fecha . "'
                        and fecha_creacion	<= '" . $fecha . "'
						and estado = 1";
		$ejecutar_consultar = mysqli_query(
		$coneccion->Conexion,
		$consultar) or die("Problemas al consultar filtro de token");
		$exist = (mysqli_num_rows($ejecutar_consultar) >= 1);
		return $exist;
	}
	
	private function crypto_rand_secure($min, $max) {
		$range = $max - $min;
		if ($range < 0) return $min;
		$log = log($range, 2);
		$bytes = (int) ($log / 8) + 1;
		$bits = (int) $log + 1;
		$filter = (int) (1 << $bits) - 1;
		do {
			$rnd = hexdec(bin2hex(
									  openssl_random_pseudo_bytes(
																  $bytes
																		  )));
			$rnd = $rnd & $filter;
		} while ($rnd >= $range);
		return $min + $rnd;
	}

	private function token_account($length=32){
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		for($i=0;$i<$length;$i++){
			$token .= $codeAlphabet[$this->crypto_rand_secure(
														 0,
														 strlen($codeAlphabet)
														 )
								   ];
		}
		return $token;
	}
	public function set_token($token)
	{
		$this->token = $token;
	}

	public function set_account_id($account_id)
	{
		$this->account_id = $account_id;
	}

	public function set_fecha_creacion($fecha_creacion)
	{
		$this->fecha_creacion = $fecha_creacion;
	}

	public function set_fecha_expiracion($fecha_expiracion)
	{
		$this->fecha_expiracion = $fecha_expiracion;
	}

	public function set_estado($estado)
	{
		$this->estado = $estado;
	}
	
	public function get_token(){
		return $this->token;
	}

	public function get_account_id()
	{
		return $this->account_id;
	}

	public function get_fecha_creacion()
	{
		return $this->fecha_creacion;
	}

	public function get_fecha_expiracion()
	{
		return $this->fecha_expiracion;
	}

	public function get_estado()
	{
		return $this->estado;
	}

	public function jsonSerialize() 
	{
		$account = new Account(account_id: $this->account_id);
		return [
			'token' => $this->token,
			'fecha_creacion' => $this->fecha_creacion,
			'fecha_expiracion' => $this->fecha_expiracion,
			'account' => $account->jsonSerialize()
		];
	}
	public function update()
	{
		$consulta = "update users_tokens 
					set fecha_creacion = " . ((!is_null($this->get_fecha_creacion())) ? "'" . $this->get_fecha_creacion() . "'" : "null") . ", 
						fecha_expiracion = " . ((!is_null($this->get_fecha_expiracion())) ? "'" . $this->get_fecha_expiracion() . "'" : "null") . ", 
						accountid = " . ((!is_null($this->get_account_id())) ? $this->get_account_id()  : "null") . ",
						estado = " . ((!is_null($this->get_estado())) ? $this->get_estado()  : "0") . "
					where token = '" . $this->get_token() . "'";
		$mensaje_error = "No se pudo actualizar el token";
		$ret = mysqli_query($this->coneccion_base->Conexion,
						    $consulta
						   );
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}

	public function save()
	{
		$consulta = "INSERT INTO users_tokens ( 
										token, 
										fecha_creacion, 
										fecha_expiracion,
										accountid,
										estado
					)
					VALUES ( " . ((!is_null($this->get_token())) ? "'" . $this->get_token() . "'" : "null") . ", 
							" . ((!is_null($this->get_fecha_creacion())) ? "'" . $this->get_fecha_creacion() . "'" : "null") . ", 
							" . ((!is_null($this->get_fecha_expiracion())) ? "'" . $this->get_fecha_expiracion() . "'" : "null") . ", 
							" . ((!is_null($this->get_account_id())) ? $this->get_account_id() : "null") . ",
							" . ((!is_null($this->get_estado())) ? $this->get_estado() : "null") . "
					)";
		$mensaje_error = "No se pudo insertar el token";
		$ret = mysqli_query($this->coneccion_base->Conexion, $consulta);
		if (!$ret) {
			throw new Exception($mensaje_error . $consulta, 2);
		}
	}
}
