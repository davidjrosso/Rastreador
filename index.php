<?php
/*
 *
 * This file is part of Rastreador3.
 *
 * Rastreador3 is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Rastreador3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Rastreador3; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 */

session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/PersonaController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/HomeController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/MovimientoController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CategoriaController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/EscuelaController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CentroSaludController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CalleController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/BarrioController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/OtraInstitucionController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/ResponsableController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/AccountController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/MotivoController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");

use PhpDevCommunity\Route;
use PhpDevCommunity\Router;
use \PhpDevCommunity\Exception\MethodNotAllowed;
use \PhpDevCommunity\Exception\RouteNotFound;


try {
	$con = new Conexion();
	$con->OpenConexion();
	$routes = [];	
	$metodo = $_SERVER["REQUEST_METHOD"];
	$url_request = $_SERVER["REQUEST_URI"];
	$parametria = new Parametria(
							     coneccion_base: $con,
							     id_parametria: "ENV_HOST"
							   );
	$host_url = $parametria->get_valor();
	$url = "https://" . $host_url;

	$routes[] = Route::get('home', '/', [HomeController::class, 'index']);
	$routes[] = Route::get('home_url', '/home', [HomeController::class, 'index']);
	$routes[] = Route::get('home_succes', '/home\?Mensaje={mensaje}', [HomeController::class, 'index']);
	$routes[] = Route::get('home_error', '/home\?MensajeError={mensaje}', [HomeController::class, 'index']);
	$routes[] = Route::get('login', '/login', [HomeController::class, 'login']);
	$routes[] = Route::post('login_control', '/login_control', [HomeController::class, 'login_control']);
	$routes[] = Route::get('logout', '/logout', [HomeController::class, 'logout_control']);
	$routes[] = Route::get('personas_listado', '/personas', [PersonaController::class, 'listado_personas']);
	$routes[] = Route::get('persona_ver', '/persona\?ID={ID}', [PersonaController::class, 'datos_persona']);
	$routes[] = Route::get('mod_persona', '/persona/editar\?ID={id}', [PersonaController::class, 'mod_persona']);
	$routes[] = Route::get('personas_eliminar', 'Controladores/DeletePersona.php\?ID={id}', [PersonaController::class, 'del_p_control']);
	$routes[] = Route::post('persona_mod_control', '/modificar_persona', [PersonaController::class, 'mod_persona_control']);
	$routes[] = Route::get('personas_unificar', '/personas/unificar', [PersonaController::class, 'unif_persona']);
	$routes[] = Route::post('personas_unif_control', 'unificarpersonas', [PersonaController::class, 'unif_persona_control']);
	$routes[] = Route::get('movimientos_listado', '/movimientos', [MovimientoController::class, 'listado_movimiento']);
	$routes[] = Route::get('movimientos_listado_filtro', '/movimientos\?Filtro={filtro}&ID_Filtro={id}', [MovimientoController::class, 'listado_movimiento']);
	$routes[] = Route::get('movimientos_listado_succes', '/movimientos\?Mensaje={mensaje}', [MovimientoController::class, 'listado_movimiento']);
	$routes[] = Route::get('movimiento', '/movimiento\?ID={id}', [MovimientoController::class, 'datos_movimiento']);
	$routes[] = Route::get('mod_movimiento', '/movimiento/editar\?ID={id}', [MovimientoController::class, 'mod_movimiento']);
	$routes[] = Route::post('mod_movimiento_control', '/modificar_movimiento', [MovimientoController::class, 'mod_movimiento_control']);
	$routes[] = Route::get('del_movimiento', '/delete_movimiento\?ID={id}', [MovimientoController::class, 'del_movimiento_control']);
	$routes[] = Route::post('buscar_movimientos', '/buscar_movimientos', [MovimientoController::class, 'buscar_movimientos']);
	$routes[] = Route::get('listado_categorias', '/categorias', [CategoriaController::class, 'listado_categorias']);
	$routes[] = Route::get('listado_categorias_success', '/categorias\?Mensaje={mensaje}', [CategoriaController::class, 'listado_categorias']);
	$routes[] = Route::get('datos_categoria', '/categoria\?ID={id}', [CategoriaController::class, 'datos_categoria']);
	$routes[] = Route::get('mod_categoria', '/categoria/editar\?ID={id}', [CategoriaController::class, 'mod_categoria']);
	$routes[] = Route::get('sol_del_control', 'pedireliminarcategoria\?ID={id}', [CategoriaController::class, 'sol_del_control']);
	$routes[] = Route::get('del_categoria', '/delete_categoria\?ID={id}', [CategoriaController::class, 'del_categoria_control']);
	$routes[] = Route::get('categoria', '/categoria/unificar', [CategoriaController::class, 'unif_categoria']);
	$routes[] = Route::post('sol_unif_control', 'pedirunificarcategoria', [CategoriaController::class, 'sol_unif_control']);
	$routes[] = Route::get('motivos_listado', '/motivos', [MotivoController::class, 'listado_motivos']);
	$routes[] = Route::get('mod_motivo', '/motivo/editar\?ID={id}', [MotivoController::class, 'mod_motivo']);
	$routes[] = Route::get('mod_motivo_succes', '/motivos\?Mensaje={mensaje}', [MotivoController::class, 'listado_motivos']);
	$routes[] = Route::get('mod_motivo_error', '/motivo/editar\?ID={id}&MensajeError={mensaje}', [MotivoController::class, 'mod_motivo']);
	$routes[] = Route::post('sol_mod_motivo', 'pedirmodificarmotivo', [MotivoController::class, 'sol_mod_control']);
	$routes[] = Route::post('mod_motivo_control', 'modificarmotivo', [MotivoController::class, 'mod_motivo_control']);
	$routes[] = Route::get('del_motivo_control', 'pedireliminarmotivo\?ID={id}', [MotivoController::class, 'del_motivo_control']);
	$routes[] = Route::get('unif_motivo', '/motivo/unificar', [MotivoController::class, 'unif_motivo']);
	$routes[] = Route::get('unif_motivo_success', '/motivo/unificar\?Mensaje={mensaje}', [MotivoController::class, 'unif_motivo']);
	$routes[] = Route::post('sol_unif_motivo_control', 'pedirunificarmotivos', [MotivoController::class, 'sol_unif_control']);
	$routes[] = Route::post('unif_motivo_control', 'unificarmotivos', [MotivoController::class, 'unif_motivo_control']);
	$routes[] = Route::get('listado_responsables', '/responsables', [ResponsableController::class, 'listado_responsables']);
	$routes[] = Route::get('mod_responsable', '/responsable/editar\?ID={id}', [ResponsableController::class, 'mod_responsable']);
	$routes[] = Route::get('sol_del_responsable', '/pedireliminarresponsable\?ID={id}', [ResponsableController::class, 'sol_del_responsable']);
	$routes[] = Route::get('unif_responsable', '/responsable/unificar', [ResponsableController::class, 'unif_responsable']);
	$routes[] = Route::get('responsables', '/pedirunificarresponsable', [ResponsableController::class, 'index']);
	$routes[] = Route::get('listado_centros_salud', '/centrosdesalud', [CentroSaludController::class, 'listado_centros_salud']);
	$routes[] = Route::get('listado_centros_salud_filtro', '/centrosdesalud\?Filtro={filt}&ID_Filtro={idfilt}', [CentroSaludController::class, 'listado_centros_salud']);
	$routes[] = Route::get('buscar_centos_salud', '/buscar_centos_salud', [CentroSaludController::class, 'buscar_centos_salud']);
	$routes[] = Route::get('mod_centro_salud', '/centrosalud/editar\?ID={id}', [CentroSaludController::class, 'mod_centro_salud']);
	$routes[] = Route::get('mod_centro_salud_control', '/mod_centro_salud', [CentroSaludController::class, 'mod_centro_salud_control']);
	$routes[] = Route::get('sol_del_centro_salud', '/pedireliminarcentrosalud\?ID={id}', [CentroSaludController::class, 'sol_del_responsable']);
	$routes[] = Route::get('unif_centro_salud', '/centroalud/unificar', [CentroSaludController::class, 'unif_centro_salud']);
	$routes[] = Route::get('sol_unif_centro_salud_control', '/pedirunificarcentrosalud', [CentroSaludController::class, 'sol_unif_centro_salud_control']);
	$routes[] = Route::get('accounts', '/usuarios', [AccountController::class, 'listado_accounts']);
	$routes[] = Route::get('mod_account', '/usuario/editar\?account_id={id}', [AccountController::class, 'mod_account']);
	$routes[] = Route::get('mod_account_success', '/usuario/editar\?account_id={id}&Mensaje={mensaje}', [AccountController::class, 'mod_account']);
	$routes[] = Route::get('mod_account_error', '/usuario/editar\?account_id={id}&MensajeError={mensaje}', [AccountController::class, 'mod_account']);
	$routes[] = Route::post('new_account', '/usuario/nuevo', [AccountController::class, 'new_account']);
	$routes[] = Route::post('mod_account_control', '/modificar_usuario', [AccountController::class, 'mod_account_control']);
	$routes[] = Route::get('del_account_control', '/delete_usuario\?ID={id}', [AccountController::class, 'del_account_control']);
	$routes[] = Route::get('listado_barrios', '/barrios', [BarrioController::class, 'listado_barrios']);
	$routes[] = Route::get('mod_barrio', '/barrio/editar\?ID={id}', [BarrioController::class, 'mod_barrio']);
	$routes[] = Route::post('mod_barrio_control', '/modificar_barrio', [BarrioController::class, 'mod_barrio_control']);
	$routes[] = Route::get('del_barrio_control', '/delete_barrio\?ID={id}', [BarrioController::class, 'del_barrio_control']);
	$routes[] = Route::get('new_barrio', '/barrio/nuevo', [BarrioController::class, 'new_barrio']);
	$routes[] = Route::get('unif_barrio', '/barrio/unificar', [BarrioController::class, 'unif_barrios']);
	$routes[] = Route::get('unif_barrio_success', '/barrio/unificar\?Mensaje={mensaje}', [BarrioController::class, 'unif_barrios']);
	$routes[] = Route::post('sol_unif_barrio', '/pedir_unificar_barrios', [BarrioController::class, 'sol_unif_barrio']);
	$routes[] = Route::post('unif_barrio_control', '/unificar_barrios', [BarrioController::class, 'unif_barrio_control']);
	$routes[] = Route::post('new_barrio_control', 	'/insertar_barrio', [BarrioController::class, 'new_barrio_control']);
	$routes[] = Route::get('listado_calles', '/calles', [CalleController::class, 'listado_calles']);
	$routes[] = Route::get('listado_calles_succes', '/calles\?Mensaje={mensaje}', [CalleController::class, 'listado_calles']);
	$routes[] = Route::get('mod_calle', '/calle/editar\?ID={id}', [CalleController::class, 'mod_calle']);
	$routes[] = Route::post('mod_calle_control', '/modificar_calle', [CalleController::class, 'mod_calle_control']);
	$routes[] = Route::get('del_calle_control', '/delete_calle\?ID={id}', [CalleController::class, 'del_calle_control']);
	$routes[] = Route::get('new_calle', '/calle/nueva', [CalleController::class, 'new_calle']);
	$routes[] = Route::post('new_calle_control', '/insertar_calle.php', [CalleController::class, 'new_calle_control']);
	$routes[] = Route::get('unif_calle', '/calle/unificar', [CalleController::class, 'unif_calle']);
	$routes[] = Route::post('unif_calle_control', '/unificar_direcciones', [CalleController::class, 'unif_calle_control']);
	$routes[] = Route::get('listado_escuelas', '/escuelas', [EscuelaController::class, 'listado_escuelas']);
	$routes[] = Route::get('listado_escuelas_success', '/escuelas\?Mensaje={mensaje}', [EscuelaController::class, 'listado_escuelas']);
	$routes[] = Route::get('escuelas', '/escuela/editar\?ID={id}', [EscuelaController::class, 'mod_escuela']);
	$routes[] = Route::get('unif_escuelas', '/escuela/unificar', [EscuelaController::class, 'unif_escuelas']);
	$routes[] = Route::post('sol_unif_escuela_control', '/pedir_unificar_escuelas', [EscuelaController::class, 'sol_unif_escuela_control']);
	$routes[] = Route::post('unif_escuela_control', '/unificar_escuelas', [EscuelaController::class, 'unif_escuela_control']);
	$routes[] = Route::get('delete_escuela_control', '/delete_escuela\?ID={id}', [EscuelaController::class, 'del_escuela_control']);
	$routes[] = Route::get('new_escuela', '/escuela/nueva', [EscuelaController::class, 'new_escuela']);
	$routes[] = Route::get('new_escuela_control', '/insertar_escuela', [EscuelaController::class, 'new_escuela_control']);
	$routes[] = Route::post('mod_escuela_control', '/modificar_escuela', [EscuelaController::class, 'mod_escuela_control']);
	$routes[] = Route::get('listado_otras_instituciones', '/otrasinstituciones', [OtraInstitucionController::class, 'listado_otras_instituciones']);
	$routes[] = Route::get('mod_otra_institucion', '/otrainstitucion/editar\?ID={id}', [OtraInstitucionController::class, 'mod_otra_institucion']);
	$routes[] = Route::get('mod_otra_institucion_control', 'modificar_otra_institucion', [OtraInstitucionController::class, 'mod_otra_institucion_control']);
	$routes[] = Route::get('unif_otra_institucion', '/otrainstitucion/unificar', [OtraInstitucionController::class, 'unif_otra_institucion']);
	$routes[] = Route::get('unif_otra_institucion_control', '/unificar_otra_institucion', [OtraInstitucionController::class, 'unif_otra_institucion_control']);
	$routes[] = Route::get('del_otra_institucion_control', '/delete_otra_institucion\?ID={id}', [OtraInstitucionController::class, 'del_otra_institucion_control']);
	$routes[] = Route::get('new_otra_institucion', '/view_newotrasinstituciones.php', [OtraInstitucionController::class, 'new_otra_institucion']);
	$routes[] = Route::get('new_otra_institucion_control', '/insertar_otra_institucion\?ID={id}', [OtraInstitucionController::class, 'new_otra_institucion_control']);
	$routes[] = Route::post('error_session', '/error_session.php', [HomeController::class, 'error_session']);

	$router = new Router($routes, $url);

	$route = $router->matchFromPath(path: $url_request, method: $metodo);

    $handler = $route->getHandler();
    $attributes = $route->getAttributes();
    $controllerName = $handler[0];
    $methodName = $handler[1] ?? null;
    $controller = new $controllerName();

    if (!is_callable($controller)) {
        $controller =  [$controller, $methodName];
    }

    echo $controller(...array_values($attributes));
	exit();

} catch (MethodNotAllowed $exception) {
	header("Content-Type: text/html;charset=utf-8");
	header("HTTP/1.0 405 Method Not Allowed");
	include("view_not_found_404.php");

} catch (RouteNotFound $exception) {
	header("Content-Type: text/html;charset=utf-8");
    header("HTTP/1.0 404 Not Found");
	include("view_not_found_404.php");

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
