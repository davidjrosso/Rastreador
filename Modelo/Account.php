<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Controladores/Conexion.php");
class Account implements JsonSerializable {
	//DECLARACION DE VARIABLES
	private $account_id;
	private $email;
	private $estado;
	private $expired;
	private $expired_date;
	private $first_name;
	private $hint_answer;
	private $hint_question;
	private $id_tipo_usuario;
	private $initials;
	private $iva;
	private $last_name;
	private $last_tried_date;
	private $matricula;
	private $password;
	private $tries;
	private $user_name;



//METODOS SET
public function set_account_id($account_id){
	$this->account_id = $account_id;
}

public function set_email($email){
    $this->email = $email;
}

public function set_estado($estado){
    $this->estado = $estado;
}

public function set_first_name($first_name){
	$this->first_name = $first_name;
}

public function set_expired($expired){
    $this->expired = $expired;
}
public function set_expired_date($expired_date){
    $this->expired_date = $expired_date;
}

public function set_hint_answer($hint_answer){
    $this->hint_answer = $hint_answer;
}

public function set_hint_question($hint_question){
    $this->hint_question = $hint_question;
}

public function set_id_tipo_usuario($id_tipo_usuario){
    $this->id_tipo_usuario = $id_tipo_usuario;
}

public function set_initials($initials){
    $this->initials = $initials;
}

public function set_iva($iva){
    $this->iva = $iva;
}

public function set_last_tried_date($last_tried_date){
    $this->last_tried_date = $last_tried_date;
}

public function set_last_name($last_name){
    $this->last_name = $last_name;
}

public function set_matricula($matricula){
	$this->matricula = $matricula;
}

public function set_password($password){
	$this->password = md5($password);
}

public function set_tries($tries){
	$this->tries = $tries;
}

public function set_user_name($user_name){
	$this->user_name = $user_name;
}

//METODOS GET
public static function control_user_password($user_name, $user_pass){
	$con = new Conexion();
	$con->OpenConexion();
 	$consulta = "select * 
				 from accounts 
				 where username = '$user_name' 
				 and password = '$user_pass'
				 and estado = 1";
 	$rs = mysqli_query($con->Conexion,$consulta)or die("Problemas al tomar Sesion. Nombre de usuario o password incorrectos");
 	$cont = mysqli_num_rows($rs);
	$resultado = mysqli_fetch_assoc($rs);
	$control = ($cont > 0) ? $resultado['accountid'] : -1;
	return $control;
}

public static function exist_user($user_name){
	$con = new Conexion();
	$con->OpenConexion();
 	$consulta = "select * 
				 from accounts 
				 where username = '$user_name' 
				 and estado = 1";
 	$rs = mysqli_query($con->Conexion,$consulta)or die("Problemas al crear/modificar un usuario.");
 	$cont = mysqli_num_rows($rs);
	$exist = ($cont > 0) ? 1 : 0;
	return $exist;
}

public static function exist_account($account_id){
	$con = new Conexion();
	$con->OpenConexion();
 	$consulta = "select * 
				 from accounts 
				 where accountid = '$account_id' 
				 and estado = 1";
 	$rs = mysqli_query($con->Conexion,$consulta)or die("Problemas al crear/modificar un usuario.");
 	$cont = mysqli_num_rows($rs);
	$exist = ($cont > 0) ? 1 : 0;
	return $exist;
}

public function get_account_id(){
	return $this->account_id;
}

public function get_estado(){
    return $this->estado;
}

public function get_email(){
    return $this->email;
}

public function get_expired(){
    return $this->expired;
}
public function get_expired_date(){
    return $this->expired_date;
}

public function get_first_name(){
	return $this->first_name;
}

public function get_hint_answer(){
    return $this->hint_answer;
}

public function get_hint_question(){
    return $this->hint_question;
}

public function get_id_tipo_usuario(){
    return $this->id_tipo_usuario;
}

public function get_initials(){
    return $this->initials;
}

public function get_iva(){
    return $this->iva;
}

public function get_last_tried_date(){
    return $this->last_tried_date;
}

public function get_last_name(){
    return $this->last_name;
}

public function get_matricula(){
	return $this->matricula;
}

public function get_password(){
	return $this->password;
}

public function get_tries(){
	return $this->tries;
}

public function get_user_name(){
	return $this->user_name;
}

public function is_active() {
	$control_expiracion = 1;
	if(!empty($this->expired_date)){
		$fecha_actual = DateTime::createFromFormat(format: 'Y-m-d', datetime: date('Y-m-d'));
		$diferencia = $fecha_actual->diff($this->expired_date, true);
		$año = $diferencia->y;
		$control_expiracion = ($año >= 1) ? 0 : 1;
	}
	return $control_expiracion;
}

public function is_username_disponible($username) {
	$con = new Conexion();
	$con->OpenConexion();
	$consulta = "select * 
				 from accounts 
				 where username = '$username'
				 and accountid <> '" . $this->account_id . "' 
				 and estado = 1";
	$rs = mysqli_query(
		$con->Conexion,
		$consulta
	)or die("Problemas al crear/modificar un usuario.");
	$cont = mysqli_num_rows($rs);
	$exist = ($cont > 0) ? 0 : 1;
	return $exist;
}

public function jsonSerialize() {
	return [
        'accountid' => $this->account_id,
        'firstname' => $this->first_name,
        'lastname' => $this->last_name,
        'initials' => $this->initials,
        'username' => $this->user_name,
        'password' => $this->password,
        'email' => $this->email,
        'hintquestion' => $this->hint_question,
        'hintanswer' => $this->hint_answer,
        'expired' => $this->expired,
        'expireddate' => $this->expired_date,
        'tries' => $this->tries,
        'lasttrieddate' => $this->last_tried_date,
        'matricula' => $this->matricula,
        'iva' => $this->iva,
        'ID_TipoUsuario' => $this->id_tipo_usuario,
        'estado' => $this->estado
	];
}

public function update()
{
	$Con = new Conexion();
	$Con->OpenConexion();
	$Consulta = "update accounts 
				 set firstname = " . ((!is_null($this->get_first_name())) ? "'" . $this->get_first_name() . "'" : "null") . ", 
					 lastname = " . ((!is_null($this->get_last_name())) ? "'" . $this->get_last_name() . "'" : "null") . ", 
					 initials = " . ((!is_null($this->get_initials())) ? "'" . $this->get_initials() . "'" : "null") . ", 
					 username = " . ((!is_null($this->get_user_name())) ? "'" . $this->get_user_name() . "'" : "null") . ", 
					 password = " . ((!is_null($this->get_password())) ? "'" . $this->get_password() . "'" : "null") . ", 
					 email = " . ((!is_null($this->get_email())) ? "'" . $this->get_email() . "'" : "null") . ", 
					 hintquestion = " . ((!is_null($this->get_hint_question())) ? "'" . $this->get_hint_question() . "'" : "null") . ", 
					 hintanswer = " . ((!is_null($this->get_hint_answer())) ? "'" . $this->get_hint_answer() . "'" : "null") . ", 
					 expired = " . ((!is_null($this->get_expired())) ? "'" . $this->get_expired() . "'" : "null") . ", 
					 expireddate = " . ((!is_null($this->get_expired_date()->format('Y/m/d'))) ? "'" . $this->get_expired_date()->format('Y/m/d') . "'" : "null") . ", 
					 tries = " . ((!is_null($this->get_tries())) ? "'" . $this->get_tries() . "'" : "null") . ", 
					 lasttrieddate = " . ((!is_null($this->get_last_tried_date())) ? "'" . $this->get_last_tried_date() . "'" : "null") . ", 
					 matricula = " . ((!is_null($this->get_matricula())) ? "'" . $this->get_matricula() . "'" : "null") . ", 
					 iva = " . ((!is_null($this->get_iva())) ? "'" . $this->get_iva() . "'" : "null") . ", 
					 ID_TipoUsuario = " . ((!is_null($this->get_id_tipo_usuario())) ? "'" . $this->get_id_tipo_usuario() . "'" : "null") . ", 
					 estado = " . ((!is_null($this->get_estado())) ? $this->get_estado() : "null") . " 
				 where accountid = " . $this->get_account_id();
				 $MensajeErrorConsultar = "No se pudo actualizar la Persona";
				 if (!$Ret = mysqli_query($Con->Conexion, $Consulta)) {
					throw new Exception($MensajeErrorConsultar . $Consulta, 2);
				}
				 $Con->CloseConexion();
}

public function save(){
	$Con = new Conexion();
	$Con->OpenConexion();
	$consulta = "INSERT INTO accounts (
									  accountid, 
									  firstname, 
									  lastname, 
									  initials,
									  username, 
									  password, 
									  hintquestion, 
									  hintanswer, 
									  expired, 
									  expireddate,
									  tries, 
									  lasttrieddate, 
									  matricula, 
									  iva, 
									  ID_TipoUsuario,
									  estado 
				 )
				 VALUES ( " . ((!is_null($this->get_account_id())) ? "'" . $this->get_account_id() . "'" : "null") . ", 
						 " . ((!is_null($this->get_first_name())) ? "'" . $this->get_first_name() . "'" : "null") . ", 
						 " . ((!is_null($this->get_last_name())) ? "'" . $this->get_last_name() . "'" : "null") . ", 
						 " . ((!is_null($this->get_initials())) ? "'" . $this->get_initials() . "'" : "null") . ", 
						 " . ((!is_null($this->get_user_name())) ? "'" . $this->get_user_name() . "'" : "null") . ", 
						 " . ((!is_null($this->get_password())) ? "'" . $this->get_password() . "'" : "null") . ", 
						 " . ((!is_null($this->get_hint_question())) ? "'" . $this->get_hint_question() . "'" : "null") . ", 
						 " . ((!is_null($this->get_hint_answer())) ? "'" . $this->get_hint_answer() . "'" : "null") . ", 
						 " . ((!is_null($this->get_expired())) ? "'" . $this->get_expired() . "'" : "null") . ", 
						 " . ((!is_null($this->get_expired_date()->format('Y/m/d'))) ? "'" . $this->get_expired_date()->format('Y/m/d') . "'" : "null") . ", 
						 " . ((!is_null($this->get_tries())) ? "'" . $this->get_tries() . "'" : "null") . ", 
						 " . ((!is_null($this->get_last_tried_date())) ? $this->get_last_tried_date() : "null") . ", 
						 " . ((!is_null($this->get_matricula())) ? "'" . $this->get_matricula() . "'" : "null") . ", 
						 " . ((!is_null($this->get_iva())) ? $this->get_iva() : "null") . ", 
						 " . ((!is_null($this->get_id_tipo_usuario())) ? $this->get_id_tipo_usuario() : "null") . ",
						 1
				 )";
				 $MensajeErrorConsultar = "No se pudo insertar el usuario";
				 if (!$Ret = mysqli_query($Con->Conexion, $consulta)) {
					throw new Exception($MensajeErrorConsultar . $consulta, 2);
				 }
				 $Con->CloseConexion();
}

public function __construct(
	$account_id = null,
	$email = null,
    $estado = null,
	$expired = null,
	$expired_date = null,
	$first_name = null,
	$hint_answer = null,
	$hint_question = null,
	$id_tipo_usuario = null,
	$initials = null,
	$iva = null,
	$last_name = null,
	$last_tried_date = null,
	$matricula = null,
	$password = null,
	$tries = null,
	$user_name = null
){
	$fecha_actual = DateTime::createFromFormat(format: 'Y-m-d', datetime: date('Y-m-d'));
	if (!$account_id) {
		$this->email = $email;
		$this->expired = $expired;
		$this->expired_date = ($expired_date) ? $expired_date : $fecha_actual;
		$this->first_name = $first_name;
		$this->hint_answer = $hint_answer;
		$this->hint_question = $hint_question;
		$this->id_tipo_usuario = $id_tipo_usuario;
		$this->initials = $initials;
		$this->iva = $iva;
		$this->last_name = $last_name;
		$this->last_tried_date = $last_tried_date;
		$this->matricula = $matricula;	
		$this->password = ($password) ? md5($password) : null;
		$this->tries = $tries;
		$this->user_name = $user_name;
        $this->estado = $estado;

	} else {
		$con = new Conexion();
        $con->OpenConexion();
		$consultar_usuario = "select *
							 from accounts 
							 where accountid = " . $account_id . " 
							   and estado = 1";
		$ejecutar_consultar_persona = mysqli_query(
			$con->Conexion, 
			$consultar_usuario) or die("Problemas al consultar filtro Usuario");
		$ret = mysqli_fetch_assoc($ejecutar_consultar_persona);
		if (!is_null($ret)) {
			$usr_account_id = $ret["accountid"];
			$usr_first_name = $ret["firstname"];
			$usr_last_name = $ret["lastname"];
			$usr_initials = $ret["initials"];
			$usr_user_name = $ret["username"];
			$usr_password = $ret["password"];
			$usr_iva = $ret["iva"];
			$usr_matricula = $ret["matricula"];
			$usr_estado = $ret["estado"];
			$usr_id_tipo_usuario = $ret["ID_TipoUsuario"];
			$usr_last_tried_date = $ret["lasttrieddate"];
			$usr_email = $ret["email"];
			$usr_tries = $ret["tries"];
			$usr_expired = $ret["expired"];
			$usr_expired_date = (!empty($ret["expireddate"])) ? DateTime::createFromFormat('Y-m-d', $ret["expireddate"]) : $expired_date;
			$usr_hint_answer = $ret["hintanswer"];
			$usr_hint_question = $ret["hintquestion"];
			$usr_estado = $ret["estado"];	

			$this->account_id = $usr_account_id;
			$this->email = ($email) ? $email : $usr_email;
			$this->expired = ($expired) ? $expired : $usr_expired;
			$this->expired_date = ($usr_expired_date) ? $usr_expired_date : $fecha_actual;
			$this->first_name = ($first_name) ? $first_name : $usr_first_name;
			$this->hint_answer = ($hint_answer) ? $hint_answer : $usr_hint_answer;
			$this->hint_question = ($hint_question) ? $hint_question : $usr_hint_question;
			$this->id_tipo_usuario = ($id_tipo_usuario) ? $id_tipo_usuario : $usr_id_tipo_usuario;
			$this->initials = ($initials) ? $initials : $usr_initials;
			$this->iva = ($iva) ? $iva : $usr_iva;
			$this->last_name = (!empty($last_name)) ? $last_name : $usr_last_name;
			$this->last_tried_date = ($last_tried_date) ? $last_tried_date : $usr_last_tried_date;
			$this->matricula = ($matricula) ? $matricula : $usr_matricula;
			$this->password = ($password) ?  md5($password) :  md5($usr_password);
			$this->tries = ($tries) ? $tries : $usr_tries;
			$this->user_name = ($user_name) ? $user_name : $usr_user_name;
			$this->estado = ($estado) ? $estado : $usr_estado;	
		}
		$con->CloseConexion();
	}
}

}
