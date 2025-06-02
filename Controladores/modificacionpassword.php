<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/Controladores/Conexion.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Accion.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/UserToken.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Account.php");


try {
	$con = new Conexion();
	$con->OpenConexion();

	if (empty($_SERVER["HTTP_REFERER"])) {
		$url = $_SERVER["REQUEST_URI"];
		$opciones = explode("/", $url);
		$token = $opciones[3];
		if (!UserToken::is_token_valid(coneccion: $con, token: $token)) {
			$con->CloseConexion();
			$mensaje = "El enlace de recuperacion de contraseña expiro o es invalido, por favor solicite un nuevo enlace.";
			header('Location: ../../view_recuperar_password.php?MensajeError=' . $mensaje);
		} else {
			$con->CloseConexion();
			header('Location: ../../view_modpassword.php?token=' . $token);
		}
	} else if (preg_match("~view_modpassword~", $_SERVER["HTTP_REFERER"])) {
		$userpass = (isset($_REQUEST["password"])) ? $_REQUEST["password"] : null;
		$token = (isset($_REQUEST["token"])) ? $_REQUEST["token"] : null;
		if (!UserToken::is_token_valid(coneccion: $con, token: $token)) {
			$con->CloseConexion();
			$mensaje = "El enlace de recuperacion de contraseña expiro o es invalido, por favor solicite un nuevo enlace.";
			header('Location: ../../view_modpassword.php?MensajeError=' . $mensaje);
		} else {
			$user_token = new UserToken(coneccion_base: $con, token: $token);
			$account_id = $user_token->get_account_id();
			$has8characters = (mb_strlen($userpass) >= 8);
			$hasAlpha = preg_match('~[a-zA-Z]+~', $userpass);
			$hasNum = preg_match('~[0-9]+~', $userpass);
			$hasNonAlphaNum = preg_match('~[\!\@#$%\?&\*\(\)_\-\+=]+~', $userpass);
			if (!($has8characters && $hasAlpha && $hasNum && !$hasNonAlphaNum)) {
				$mensaje = "La contraseña debe contener 8 caracteres, alfabeticos y numericos";
				header("Location: ../../view_modpassword.php?MensajeError=" . $mensaje);
			} else {
				$account = new Account(account_id: $account_id);
				$account->set_password($userpass);
				$account->update();
				$user_token = new UserToken(coneccion_base: $con, token: $token);
				$user_token->set_estado(0);
				$user_token->update();
				$con->CloseConexion();
				$mensaje = "Contraseña modificada";
				header("Location: ../index.php?Mensaje=" . $mensaje);
			}
		}
	}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
