<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Controladores/Conexion.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Account.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Modelo/Accion.php");

try {
    $con = new Conexion();
    $con->OpenConexion();
    //$vc = ini_set("SMTP","ssl://smtp.gmail.com");
    //$vc = ini_set("smtp_port","465");
    $username = (isset($_REQUEST["username"])) ? $_REQUEST["username"]: null;

    $account_id_username = Account::exist_user_id(
                                             $con, 
                                       $username
                                                 );

    if ($account_id_username <= 0) {
        $MensajeError = "El usuario ingresado no existe.";
        header("Location: ../view_recuperar_password.php?MensajeError=" . $MensajeError);
    }

    $link = "";

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
                           Hemos recibido una petici&oacuten para restablecer usuario y/o contraseña de tu cuenta.</p>
                        <p>Si hiciste esta petici&oacuten, haz clic en el siguiente enlace.</p>
                        <p> Usuario : " . $username_mensaje . " <br>
                            <strong>Enlace para restablecer tu usuario y/o contraseña</strong><br>
                            <a href=" . $link . "> Restablecer contrase&ntildea </a>
                        </p>
                    </body>
                </html>";

    $cabeceras = 'MIME-Version: 1.0' . "\r\n";
    $cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $cabeceras .= 'From: martinmonnittola <martinmonnittola@gmail.com>' . "\r\n";

    $check = mail(to: $email_user,
                  subject: "Recuperacion de contraseña - Rastreador", 
                  message: $mensaje
                 );

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
    echo $email_user;
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
?>