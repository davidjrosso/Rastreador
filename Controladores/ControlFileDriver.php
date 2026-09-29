<?php 
	session_start();
	header('Content-Type: application/json'); 
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Controladores/Conexion.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/sys_config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Movimiento.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/MovimientoMotivo.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Archivo.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Formulario.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Parametria.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Persona.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Responsable.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Barrio.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Calle.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/Motivo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Modelo/CentroSalud.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';


	use Google\Client;
	use Google\Service\Sheets\SpreadSheet;
	use Google\Service\Drive;


    try {

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$con = new Conexion();
			$con->OpenConexion();
			$private_key = Parametria::get_value_by_code($con, 'SECRET_KEY');

            $nombre_archivo = $_POST["archivo"];
			$private_key = Parametria::get_value_by_code($con, 'SECRET_KEY');

			$client_drive = new Google_Client();
			$client_drive->setAuthConfig(array("type" => TYPE_ACCOUNT,
										 "client_id" => CLIENT_ID,
										 "client_email" => CLIENT_EMAIL,
										 "private_key" => $private_key, 
										 "signing_algorithm" => "HS256"));

			$client_drive->addScope([Google_Service_Drive::DRIVE]);
			$service = new Google_Service_Drive($client_drive);
			$driveService = new Drive($client_drive);

			$list_file = $service->files->listFiles(['q' => "'name'='$nombre_archivo'"]);

        }
    } catch(Exception $e) {
        $con->CloseConexion();
        echo "Error Message: " . $e;
    }