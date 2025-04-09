<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Controladores/Conexion.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Account.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/UserToken.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Accion.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Parametria.php");
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';


use Google\Client;

try {
    $con = new Conexion();
    $con->OpenConexion();
    $username = (isset($_REQUEST["username"])) ? $_REQUEST["username"]: null;

    $account_id_username = Account::exist_user_id(
                                             $con, 
                                       $username
                                                 );

    if ($account_id_username <= 0) {
        $MensajeError = "El usuario ingresado no existe.";
        header("Location: ../view_recuperar_password.php?MensajeError=" . $MensajeError);
    } else {
        if (UserToken::has_token_valid($con, $account_id_username)) {
            $user_token = new UserToken(
                                    coneccion_base: $con,
                                    account_id: $account_id_username
            );
            $user_token->set_estado(0);
            $user_token->update();
        }
        $fecha_creacion = date("Y-m-d");
        $fecha_expiracion = DateTime::createFromFormat(format: 'Y-m-d', datetime: date('Y-m-d'));
        $intervalo = new DateInterval('P1D');
        $fecha_expiracion->add($intervalo);
        $user_token = new UserToken(
                                coneccion_base: $con,
                                account_id: $account_id_username,
                                fecha_creacion: $fecha_creacion,
                                fecha_expiracion: $fecha_expiracion->format('Y-m-d'),
                                estado: 1
                          );
        $user_token->save();

        $link = "http://" . $_SERVER['HTTP_HOST'] . "/Controladores/modificacioncontraseña.php/" . $user_token->get_token();

        if ($account_id_username > 0) {
            $account_id = $account_id_username;
        }

        $user = new Account(
            account_id: $account_id
        );
        $email_user = $user->get_email();
        $nombre_user = $user->get_first_name();
        $apellido_user = $user->get_last_name();
        $username_mensaje = $user->get_user_name(); 
        $mensaje = "<html>
                        <head>
                            <title>Restablece tu usuario y/o contraseña</title>
                        </head>
                        <body>
                            <p>Hola $nombre_user $apellido_user, <br>
                               Hemos recibido una petición para restablecer usuario y/o contraseña de tu cuenta.</p>
                            <p>Si hiciste esta petición, haz clic en el siguiente enlace.</p>
                            <p> Usuario : " . $username_mensaje . " <br>
                                <strong>Enlace para restablecer tu usuario y/o contraseña</strong><br>
                                " . $link . "
                            </p>
                        </body>
                    </html>";

        $cabeceras = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $cabeceras .= 'From: martinmonnittola <martinmonnittola@gmail.com>' . "\r\n";
        $client_secret = new Parametria(
            coneccion_base: $con,
            codigo: "CLIENT_SECRET"
         );
        $parameter_refresh = new Parametria(
            coneccion_base: $con,
            codigo: "REFRESH_TOKEN_GMAIL"
           );
        $parameter_acces = new Parametria(
            coneccion_base: $con,
            codigo: "ACCESS_TOKEN_GMAIL"
         );
        $refresh_token = $parameter_refresh->get_valor();
        $access_token = $parameter_acces->get_valor();
        $client_id     = '533474323983-5pdm8082unidoml3ttp7ptkbdc0qnifg.apps.googleusercontent.com';
        $redirect_uri  = "http://" . $_SERVER['HTTP_HOST'] . "/Controladores/actualizacionAutenticacion.php";
        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret->get_valor());
        $client->setRedirectUri($redirect_uri);
        $client->addScope("https://www.googleapis.com/auth/gmail.send");
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        if ($client->isAccessTokenExpired()) {
            $client->refreshToken($refresh_token);
            $newtoken = $client->getAccessToken();
            $parameter_acces->set_valor($newtoken["access_token"]);
            $parameter_acces->update();  
            $client->setAccessToken($newtoken);
        } else {
            $client->setAccessToken($access_token);
        }

        $service = new Google_Service_Gmail($client);

        $fromemail = "no-reply@rastreador.com";

        $strRawMessage = "From: Email <$fromemail> \r\n";
        $strRawMessage .= "To: <$email_user>\r\n";
        $strRawMessage .= 'Subject: Recuperacion de Contrasena - Sistema Rastreador' . "\r\n";
        $strRawMessage .= "MIME-Version: 1.0\r\n";
        $strRawMessage .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
        $strRawMessage .= "$mensaje\r\n";
        $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
        $msg = new Google_Service_Gmail_Message();
        $msg->setRaw($mime);
        $service->users_messages->send("me", $msg);
    
        $fecha = date("Y-m-d");
        $detalles = "Solicitud de recuperacion de contraseña";
        $accion = new Accion(
                xFecha : $fecha,
                xDetalles : $detalles,
                xID_TipoAccion : 2,
                xaccountid: $account_id
        );
        $accion->save();
    
        $mensaje_error = "Se ha enviado el email de recuperacion a la cuenta del usuario $username_mensaje";
        header("Location: ../view_recuperar_password.php?MensajeError=" . $mensaje_error);
    }
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
