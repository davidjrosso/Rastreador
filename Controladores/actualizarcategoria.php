<?php 
require_once 'Conexion.php';
require_once '../Modelo/Persona.php';

try {
	$Con = new Conexion();
	$Con->OpenConexion();	
	$Consulta = "select * 
				 from categoria C inner join formas_categorias F on (C.id_forma = F.id_forma)
				 where C.estado = 1";
	$consulta_resultado = mysqli_query($Con->Conexion,$Consulta);
	if(!$consulta_resultado){
		throw new Exception("Problemas al intentar Consultar Registros de Personas", 0);
	}
	while ($forma_categoria_row = mysqli_fetch_assoc($consulta_resultado)) {
		$forma_categoria = $forma_categoria_row["Forma_Categoria"];
		if (strlen($forma_categoria) > 1) {
		$forma_categoria = substr($forma_categoria, 2);
		$forma_categoria = substr($forma_categoria, 0, -1);
		} elseif (strlen($forma_categoria) == 1) {
			$forma_categoria = ord($forma_categoria );
		}
		$color_icono = substr($forma_categoria_row["color"], 1);
		list($r, $g, $b) = sscanf($forma_categoria_row["color"], "#%02x%02x%02x");
		$file_path = "../images/icons/motivos/" . $forma_categoria . "_" . $color_icono . ".png";
		$file_path_common = "../images/icons/motivos/" . $forma_categoria . ".png";
	
		if (!file_exists($file_path)) {
			$imagen = imagecreatefrompng($file_path_common);
			$is_filter = imagefilter($imagen, IMG_FILTER_COLORIZE, $r, $g, $b);
			$negro = imagecolorallocate($imagen, 0, 0, 0);
			imagecolortransparent($imagen, $negro);
			if (!$is_filter) {
				throw new Exception("Error al intentar filtrar el color del icono.", 2);
			}
			$fd = imagepng($imagen, $file_path);
			imagedestroy($imagen);
		}
	}

	if(!$consulta_resultado){
	throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 1);		
	}

	$mensaje = "Se actualizarion las direcciones correctamente";
	$Con->CloseConexion();
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode("mensaje: $mensaje");
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
}
