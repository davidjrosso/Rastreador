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

require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Elements.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/CtrGeneral.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Categoria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/CategoriaRol.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Account.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_Unificacion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Solicitud_EliminarCategoria.php");


class CategoriaController 
{

    public function listado_categorias($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();
            $DTGeneral = new CtrGeneral();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            $Filtro = null;
            $ID_Filtro = null;
            if (isset($_REQUEST["Filtro"])){
              $Filtro = $_REQUEST["Filtro"];
              $ID_Filtro = $_REQUEST["ID_Filtro"];
            }

            include("./Views/view_categorias.php");
        }
        exit();
    }

    public function mod_categoria($id_categoria)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_modcategorias.php");
        }
        exit();
    }

    public function datos_categoria($id_categoria)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_vercategorias.php");
        }
        exit();
    }

    public function crear_categoria($mensaje = null)
    {
        header(header: "Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_newcategorias.php");
        }
        exit();
    }

    public function crear_categoria_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $Codigo = (isset($_REQUEST["Codigo"]))?strtoupper($_REQUEST["Codigo"]):null;
        $Categoria = (isset($_REQUEST["Categoria"]))?$_REQUEST["Categoria"]:null;
        $ID_Forma = $_REQUEST["ID_Forma"];
        $ID = (isset($_REQUEST["ID"]))?$_REQUEST["ID"]:null;
        $Color = (isset($_REQUEST["CodigoColor"]))?$_REQUEST["CodigoColor"]:null;
        $GrupoUsuarios = (isset($_REQUEST["Tipo_Usuario"]))?$_REQUEST["Tipo_Usuario"]:null;

        $Fecha = date("Y-m-d");

        try {
            $Con = new Conexion();
            $Con->OpenConexion();
            if ($Color != null && $Color != "") {
                $ConsultarRegistros = "select * from solicitudes_crearcategorias where ID = '$ID' and estado = 1";
                $MensajeErrorConsulta = "Ocurrio un error en la consulta de solicitudes de creacion de categorias";
                $RetIguales = mysqli_query($Con->Conexion,$ConsultarRegistros) or die($MensajeErrorConsulta);
                $TomarCategoria = mysqli_fetch_assoc($RetIguales);
                $RetID_Categoria = $TomarCategoria["Categoria"];
                $RetCodigo_Categoria = $TomarCategoria["Codigo"];
                $ActualizarColor = "update solicitudes_crearcategorias set color = '$Color' where id = $ID and estado = 1";
                $MensajeErrorActualizarColor = "No se pudo actualizar el color";
                mysqli_query($Con->Conexion,$ActualizarColor) or die($MensajeErrorActualizarColor);

                $DetalleNot = "Se ha creado una nueva categoría: ".$RetID_Categoria." , codigo: ".$RetCodigo_Categoria."";
                $Expira = date("Y-m-d", strtotime($Fecha." + 30 days"));

                $ConsultaNot = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('".$DetalleNot."','".$Fecha."', '".$Expira."',1)";
                if (!$RetNot = mysqli_query($Con->Conexion,$ConsultaNot)) {
                    throw new Exception("Error al intentar registrar Notificacion. Consulta: ".$ConsultaNot, 3);
                }

                $Mensaje = "La solicitud de creacion de categoría se envió a los administradores para ser confirmada.";
                header('Location: /categoria/nueva?Mensaje=' . $Mensaje);
            } else {
                $Insert_Solicitud = "insert into solicitudes_crearcategorias(Fecha,Codigo,Categoria,ID_Forma,Color,Estado,ID_Usuario) values('{$Fecha}','{$Codigo}','{$Categoria}',{$ID_Forma},'{$Color}',1,{$ID_Usuario})";
                $MensajeError = "No se pudo enviar la solicitud";

                if (!$RetID = mysqli_query($Con->Conexion, $Insert_Solicitud)){
                    throw new Exception($MensajeError . " " . $Insert_Solicitud, 2);
                }

                $ConsultarID = "select id 
                                from solicitudes_crearcategorias 
                                where codigo = '$Codigo' 
                                and categoria = '$Categoria' 
                                and estado = 1 
                                limit 1";
                if (!$RetID = mysqli_query($Con->Conexion,$ConsultarID)) {
                    throw new Exception("No se pudo consultar el ID de la categoría cargada. Consulta: ".$ConsultarID, 2);
                }
                $Ret = mysqli_fetch_array($RetID);

                foreach ($GrupoUsuarios as $key => $value) {
                    $Insert_Solicitud = "insert into solicitudes_permisos(ID, ID_TipoUsuario, Fecha, estado) values('{$Ret["id"]}','{$value}','{$Fecha}', 1)";
                    $MensajeError = "No se pudo insertar la solicitud de creacion de permisos";
                    if(!$RetID = mysqli_query($Con->Conexion,$Insert_Solicitud)){
                        throw new Exception($MensajeError . " . Consulta :" . $Insert_Solicitud, 2);
                    }
                }
                $Mensaje = "La solicitud de creacion de categoría se envió a los administradores para ser confirmada.";
                header('Location: /categoria/colorcategoria?ID=' . $Ret["id"] . '&ID_Forma=' . $ID_Forma);
                $Con->CloseConexion();
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function color_categoria()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $ID_Usuario = $_SESSION["Usuario"];
            $usuario = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $usuario->get_id_tipo_usuario();
            $Element = new Elements();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_colorcategoria.php");
        }
        exit();  
    }

    public function insertar_categoria_control()
    {
        header('Content-Type: application/json;');
        $ID_Usuario = $_SESSION["Usuario"];
        $ID_Solicitud = $_REQUEST["ID"];
        $Codigo = strtoupper($_REQUEST["Codigo"]);
        $ID_Forma = $_REQUEST["ID_Forma"];
        $Categoria = $_REQUEST["Categoria"];
        $Color = base64_decode($_REQUEST["Color"]);
        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 1;
        $Detalles = "El usuario con ID: $ID_Usuario ha registrado una nueva Categoria. Datos: $Codigo - $Categoria";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $ConsultarRegistrosIguales = "select * from categoria where cod_categoria = '$Codigo' and estado = 1";
            if(!$RetIguales = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales)){
                throw new Exception("Problemas al consultar registros iguales. Consulta: ".$ConsultarRegistrosIguales, 0);		
            }
            $Resultado = mysqli_num_rows($RetIguales);
            if ($Resultado > 0) {
                mysqli_free_result($RetIguales);
                $Con->CloseConexion();
                $Mensaje = "Ya existe una categoria con ese Codigo";
                $response["mensaje_error"] = $Mensaje;
                echo json_encode($response);
            } else {
                $Consulta = "insert into categoria(cod_categoria,categoria,ID_Forma,color,estado) values('".$Codigo."','".$Categoria."',".$ID_Forma.",'".$Color."',1)";
                if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                    throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 1);		
                }

                $ConsultarID_Categoria = "select id_categoria from categoria where cod_categoria = '$Codigo' and categoria = '$Categoria' limit 1";
                if (!$RetID = mysqli_query($Con->Conexion,$ConsultarID_Categoria)) {
                    throw new Exception("No se pudo consultar el ID de la categoria cargada. Consulta: ".$ConsultarID_Categoria, 2);		
                }

                $TomarID_Categoria = mysqli_fetch_assoc($RetID);
                $RetID_Categoria = $TomarID_Categoria["id_categoria"];

                $ConsultaPermisos = "select ID_TipoUsuario
                                    from solicitudes_permisos
                                    where ID = {$ID_Solicitud}
                                    and estado = 1";
                $MessageError = "Problemas al consultar mostrar Solicitudes Permisos";
                if (!$Resultados = mysqli_query($Con->Conexion,$ConsultaPermisos)) {
                    throw new Exception("No se pudo insertar el conjunto de permisos. Consulta: ".$ConsultaPermisos, 2);
                }

                while ($RetPermisos = mysqli_fetch_array($Resultados)) {
                    $GrupoUsuarios = $RetPermisos["ID_TipoUsuario"];
                    $Insert_Permiso = "insert into categorias_roles(id_categoria, fecha, ID_TipoUsuario, estado) values('{$RetID_Categoria }', '{$Fecha}','{$GrupoUsuarios}', 1)";
                    $updatePermisos = "update solicitudes_permisos
                                    set estado = 0
                                    where ID = {$ID_Solicitud}
                                    and ID_TipoUsuario = {$GrupoUsuarios} 
                                    and estado = 1";
                    $MensajeError = "No se pudo dar de baja el permiso categoria {$RetID_Categoria } rol {$GrupoUsuarios}";
                    $ResultadosUpdate = mysqli_query($Con->Conexion,$updatePermisos) or die($MessageError);
                    if(!$RetID = mysqli_query($Con->Conexion,$Insert_Permiso)){
                        throw new Exception("No se pudo actualizar el permisos. Consulta: ".$Insert_Permiso, 2);
                    }
                }

                $Consulta = "select * 
                            from formas_categorias 
                            where id_forma = $ID_Forma";
                $consulta_resultado = mysqli_query($Con->Conexion,$Consulta);
                if (!$consulta_resultado) {
                    throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 1);		
                }
                $forma_categoria_row = mysqli_fetch_assoc($consulta_resultado);

                $forma_categoria = $forma_categoria_row["Forma_Categoria"];
                if (strlen($forma_categoria) > 1) {
                    $forma_categoria = substr($forma_categoria, 2);
                    $forma_categoria = substr($forma_categoria, 0, -1);
                } elseif (strlen($forma_categoria) == 1) {
                    $forma_categoria = ord($forma_categoria);
                }
                list($r, $g, $b) = sscanf($Color, "#%02x%02x%02x");
                $color_icono = substr($Color, 1);
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

                $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
                if (!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)) {
                    throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 2);
                }

                $MensajeErrorDatos = "Error al actualizar las solicitudes de categoria.";
                $ActualizarSolicitud = "update solicitudes_crearcategorias set Estado = 0 where codigo = '$Codigo' and categoria = '$Categoria'";
                $EjecutarConsultar = mysqli_query($Con->Conexion,$ActualizarSolicitud) or die($MensajeErrorDatos);

                $Con->CloseConexion();
                $Mensaje = "La categoria se creo Correctamente";
                $response["mensaje"] = $Mensaje;
                echo json_encode($response);
            }
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function sol_del_control($id_categoria)
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Categoria= $_REQUEST["ID"];
        $Fecha = date("Y-m-d");
        $Estado = 1;

        $Con = new Conexion();
        $Con->OpenConexion();

        $ConsultarCategoria = "select id_categoria, categoria, cod_categoria from categoria where id_categoria = $ID_Categoria";

        if (!$RetCategoria = mysqli_query($Con->Conexion,$ConsultarCategoria)) {
            throw new Exception("Problemas al consultar datos de categoria. Consulta: ".$ConsultarCategoria, 1);			
        }
        $TomarCategoria = mysqli_fetch_assoc($RetCategoria);
        $Categoria = $TomarCategoria['categoria'];
        $Cod_Categoria = $TomarCategoria["cod_categoria"];

        $Solicitud = new Solicitud_EliminarCategoria(0,$Fecha,$Categoria,$Cod_Categoria,$Estado,$ID_Usuario,$ID_Categoria);
        $Insert_Solicitud = "insert into solicitudes_eliminarcategorias(Fecha,Categoria,Cod_Categoria,Estado,ID_Usuario,ID_Categoria) values('{$Solicitud->getFecha()}','{$Solicitud->getCategoria()}','{$Solicitud->getCod_Categoria()}',{$Solicitud->getEstado()},{$Solicitud->getID_Usuario()},{$Solicitud->getID_Categoria()})";
        $MensajeError = "No se pudo enviar la solicitud";

        mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError." - ".$Insert_Solicitud);

        $Con->CloseConexion();
        $Mensaje = "La solicitud de eliminación se envió a los administradores para ser confirmada.";
        header('Location: /categorias?Mensaje='.$Mensaje);
    }
    public function del_categoria_control($id_categoria)
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Categoria = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Categoria. Datos: Categoria: $ID_Categoria";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Consulta = "update categoria set estado = 0 where id_categoria = $ID_Categoria";
            if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 0);		
            }
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                throw new Exception("Error al intentar registrar Accion. Consulta: " . $ConsultaAccion, 1);
            }

            $ConsultaSolicitud = "update solicitudes_eliminarcategorias set estado = 0 where ID_categoria = $ID_Categoria";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
                throw new Exception("Problemas en la consulta. Consulta: " . $ConsultaSolicitud, 3);			
            }

            $Con->CloseConexion();
            $Mensaje = "La categoria fue eliminada Correctamente";
            header('Location: /home?Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
    }

    public function sol_mod_control(
                                    $id_categoria = null,
                                    $mensaje = null,
                                    $mensaje_error = null
    ){
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Motivo = $_REQUEST["ID"];
        $Motivo = $_REQUEST["Motivo"];
        $Codigo = strtoupper($_REQUEST["Codigo"]);
        $ID_Categoria = $_REQUEST["ID_Categoria"];
        $Num_Motivo = 0;

        $Fecha = date("Y-m-d");
        $Estado = 1;

        if ($ID_Categoria > 0) {
            $Con = new Conexion();
            $Con->OpenConexion();

            $categoria = new Categoria(
                                       xConecction: $Con,
                                       xID_Categoria: $ID_Categoria
                                      );
            $Cod_Categoria = $categoria->getCod_Categoria();

            $Solicitud = new Solicitud_ModificarMotivo(
                                                  0,
                                                    $Fecha,
                                                   $Motivo,
                                                   $Codigo,
                                                   $Cod_Categoria,
                                                   $Num_Motivo,
                                                   $Estado,
                                                   $ID_Usuario,
                                                   $ID_Motivo
                                                      );
            $Insert_Solicitud = "insert into solicitudes_modificarmotivos(Fecha,Motivo,Codigo,Cod_Categoria,Num_Motivo,Estado,ID_Usuario,ID_Motivo) values('{$Solicitud->getFecha()}','{$Solicitud->getMotivo()}','{$Solicitud->getCodigo()}','{$Solicitud->getCod_Categoria()}',{$Solicitud->getNum_Motivo()},{$Solicitud->getEstado()},{$Solicitud->getID_Usuario()},{$Solicitud->getID_Motivo()})";
            $MensajeError = "No se pudo enviar la solicitud";

            mysqli_query($Con->Conexion,$Insert_Solicitud) or die($MensajeError);

            $Con->CloseConexion();
            $Mensaje = "La solicitud de modificación se envió a los administradores para ser confirmada.";
            header('Location: /motivos?Mensaje=' . $Mensaje);
        } else {
            $MensajeError = "Debe seleccionar una Categoria";
            header('Location: /motivo/editar?ID=' . $ID_Motivo . '&MensajeError=' . $MensajeError);
        }
        exit();
    }

    public function mod_categoria_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];
        $ID_Categoria = $_REQUEST["ID_Categoria"];
        $ID_Solicitud = $_REQUEST["ID"];
        $Codigo = strtoupper($_REQUEST["Codigo"]);
        $Categoria = $_REQUEST["Categoria"];
        $ID_Forma = $_REQUEST["ID_Forma"];
        $NuevoColor = base64_decode($_REQUEST["CodigoColor"]);

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $ConsultarDatosViejos = "select * from categoria where id_categoria = $ID_Categoria and estado = 1";
            $ErrorDatosViejos = "No se pudieron consultar los datos";
            if(!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)){
                throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
            }
            $TomarDatosViejos = mysqli_fetch_assoc($RetDatosViejos);
            $Cod_CategoriaViejo = $TomarDatosViejos["cod_categoria"];
            $CategoriaViejo = $TomarDatosViejos["categoria"];
            $ID_FormaViejo = $TomarDatosViejos["ID_Forma"];
            $ColorViejo = $TomarDatosViejos["color"];

            $Consulta = "update motivos 
                        set cod_categoria = '$Codigo' 
                        where cod_categoria = '$Cod_CategoriaViejo' 
                        and estado = 1";

            $CodigoColorEsc = mysqli_real_escape_string($Con->Conexion, $NuevoColor);
            $Consulta = "update categoria 
                        set cod_categoria = '$Codigo', 
                            categoria = '$Categoria', 
                            ID_Forma = $ID_Forma, 
                            color = '$CodigoColorEsc' 
                        where id_categoria = $ID_Categoria 
                        and estado = 1";
            
            if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                throw new Exception("Problemas en la consulta. Consulta: ".$Consulta, 2);		
            }

            $Detalles = "El usuario con ID: $ID_Usuario ha modificado la Categoria: $ID_Categoria. Datos: Dato Anterior: $Cod_CategoriaViejo , Dato Nuevo: $Codigo - Dato Anterior: $CategoriaViejo , Dato Nuevo: $Categoria - Dato Anterior: $ID_FormaViejo , Dato Nuevo: $ID_Forma - Dato Anterior: $ColorViejo , Dato Nuevo: $NuevoColor con id solicitud : $ID_Solicitud";
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
            }

            $ConsultaPermisos = "select tip.ID_TipoUsuario, IF(slp.ID_TipoUsuario IS NULL, 'si', 'no') as disable
                                from (SELECT * FROM solicitudes_permisos WHERE ID = {$ID_Solicitud} and estado = 1) slp right join Tipo_Usuarios tip ON slp.ID_TipoUsuario = tip.ID_TipoUsuario";
            $MessageError = "Problemas al consultar Solicitudes Permisos";
            if(!$Resultados = mysqli_query($Con->Conexion,$ConsultaPermisos)){
                throw new Exception("No se pudo insertar el conjunto de permisos. Consulta: ".$ConsultaPermisos, 2);
            }
            while ($RetPermisos = mysqli_fetch_array($Resultados)) {
                $GrupoUsuarios = $RetPermisos["ID_TipoUsuario"];

                $ConsultaPermisosCategoria = "select *
                                            from categorias_roles
                                            where id_categoria = {$ID_Categoria}
                                                and id_tipousuario = {$GrupoUsuarios} 
                                                and estado = 1";

                $MessageError = "Problemas al consultar Categorias Permisos";
                if(!$ResultadosPermisosCategorias = mysqli_query($Con->Conexion,$ConsultaPermisosCategoria)){
                    throw new Exception("No se pudo consultar conjunto de permisos sobre la categoria. Consulta: ".$ConsultaPermisosCategoria, 2);
                }

                if(mysqli_num_rows($ResultadosPermisosCategorias) == 0){
                    if( $RetPermisos["disable"] == "no"){
                        $Insert_Permiso = "insert into categorias_roles(id_categoria, fecha, ID_TipoUsuario, estado) values('{$ID_Categoria}', '{$Fecha}','{$GrupoUsuarios}', 1)";
                        if(!$RetID = mysqli_query($Con->Conexion,$Insert_Permiso)){
                            throw new Exception("No se pudo actualizar el permisos. Consulta: ".$Insert_Permiso, 2);
                        }
                        $updatePermisos = "update solicitudes_permisos
                                        set estado = 0
                                        where ID = {$ID_Solicitud}
                                            and ID_TipoUsuario = {$GrupoUsuarios} 
                                            and estado = 1";
                        $MensajeError = "No se pudo dar de baja el permiso categoria {$ID_Categoria} rol {$GrupoUsuarios}";
                        $ResultadosUpdate = mysqli_query($Con->Conexion,$updatePermisos) or die($MessageError);
                    }
                } else {
                    if( $RetPermisos["disable"] == "si"){
                        $updatePermisos = "update categorias_roles
                                        set estado = 0
                                        where id_categoria = {$ID_Categoria}
                                        and ID_TipoUsuario = {$GrupoUsuarios} 
                                        and estado = 1";
                        $MensajeError = "No se pudo dar de baja el permiso categoria {$ID_Categoria} rol {$GrupoUsuarios}";
                        if(!$RetID = mysqli_query($Con->Conexion,$updatePermisos)){
                            throw new Exception($MensajeError . ". Consulta: ".$updatePermisos, 2);
                        }
                    } else {
                        $updatePermisos = "update solicitudes_permisos
                                        set estado = 0
                                        where ID = {$ID_Solicitud}
                                        and ID_TipoUsuario = {$GrupoUsuarios} 
                                        and estado = 1";
                        $MensajeError = "No se pudo dar de baja el permiso categoria {$ID_Categoria} rol {$GrupoUsuarios}";
                        $ResultadosUpdate = mysqli_query($Con->Conexion,$updatePermisos) or die($MessageError);
                    }
                }
            }

            $ConsultaSolicitud = "update solicitudes_modificarcategorias set estado = 0 where Codigo = '$Codigo' and estado = 1";
            if(!$Ret = mysqli_query($Con->Conexion,$ConsultaSolicitud)){
                throw new Exception("Problemas en la consulta. Consulta: ".$ConsultaSolicitud, 3);			
            }

            $Con->CloseConexion();
            $Mensaje = "La categoria se modifico Correctamente";
            header('Location: /home.php?ID=' . $ID_Categoria . '&Mensaje=' . $Mensaje);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function sol_unif_control()
    {
        $id_usuario = $_SESSION["Usuario"];
        $id_categoria_unif = $_REQUEST["ID_Categoria_unif"];
        $id_categoria_del = $_REQUEST["ID_Categoria_del"];

        $fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        try {
            $con = new Conexion();
            $con->OpenConexion();

            if (!empty($id_categoria_unif) && !empty($id_categoria_del)) {
                $con = new Conexion();
                $con->OpenConexion();

                $existe_categoria_unif = Categoria::exist_categoria(
                                                            connection: $con,
                                                            id_categoria: $id_categoria_unif
                                                        );
                $existe_categoria_del = Categoria::exist_categoria(
                                                            connection: $con,
                                                            id_categoria: $id_categoria_del
                                                        );

                if(!$existe_categoria_unif || !$existe_categoria_del){
                    $con->CloseConexion();
                    $mensaje = "No existen la categoria a unificar";
                    header('Location: /categoria/unificar?MensajeError=' . $mensaje);
                } else {
                    $solicitud_unificacion = new Solicitud_Unificacion(
                        coneccion: $con,
                        xID_Usuario : $id_usuario,
                        xID_Registro_1 : $id_categoria_unif,
                        xTipoUnif: 6,
                        xID_Registro_2 : $id_categoria_del,
                        xFecha: $fecha
                    );
                    $solicitud_unificacion->save();
                
                    $con->CloseConexion();
                    $mensaje = "La solicitud de unificacion de categoria se envió a los administradores para ser confirmada.";
                    header('Location: /categoria/unificar?Mensaje=' . $mensaje);
                }

            } else {
                $mensaje_error = "Debe seleccionar una categoria";
                header('Location: /categoria/unificar?MensajeError=' . $mensaje_error);
            }

        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function unif_categoria($mensaje = null)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            $Con = new Conexion();
            $Con->OpenConexion();
            $ID_Usuario = $_SESSION["Usuario"];
            $account = new Account(account_id: $ID_Usuario);
            $TipoUsuario = $account->get_id_tipo_usuario();
            $Con->CloseConexion();

            $Element = new Elements();

            include("./Views/view_unifcategorias.php");
        }
        exit();
    }

    public function unif_categoria_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];
        $id_solicitud = $_REQUEST["ID"];
        $fecha = date("Y-m-d");
        $ID_TipoAccion = 2;

        try {
            $con = new Conexion();
            $con->OpenConexion();
            $solicitud = new Solicitud_unificacion(
                                                coneccion: $con,
                                                xID_Solicitud: $id_solicitud
                                                );

            $id_categoria_unif = $solicitud->getID_Registro_1();
            $id_categoria_del = $solicitud->getID_Registro_2();

            if (!empty($id_categoria_unif) && !empty($id_categoria_del)) {

                $categoria_unif = new Categoria(xID_Categoria: $id_categoria_unif, xConecction: $con);
                if (!Categoria::exist_categoria($con, $id_categoria_del)) {
                    $solicitud->delete();
                    $mensaje = "La categoria a unificar ya a sido unificada o no existe.";
                    header('Location: /home?MensajeError=' . $mensaje);
                } else {
                    $categoria_del = new Categoria(xID_Categoria: $id_categoria_del, xConecction: $con);
                    $cod_categoria_unif = $categoria_unif->getCod_Categoria();
                    $cod_categoria_del = $categoria_del->getCod_Categoria();
                    $consulta = "update motivo 
                                set cod_categoria = '$cod_categoria_unif' 
                                where cod_categoria = '$cod_categoria_del' 
                                and estado = 1";

                    if(!$Ret = mysqli_query($con->Conexion,$consulta)){
                        throw new Exception("Problemas en la consulta. Consulta: " . $consulta, 2);		
                    }

                    $categoria_del->delete();

                    $Detalles = "El usuario con ID: $ID_Usuario ha unificado la Categoria: $id_categoria_del con la categoria $id_categoria_unif.";
                    $accion = new Accion(
                                        xaccountid: $ID_Usuario,
                                        xDetalles: $Detalles,
                                        xFecha: $fecha,
                                        xID_TipoAccion: $ID_TipoAccion
                                        );
                    $accion->save();
                    $consulta_permisos = "select *
                                        from categorias_roles
                                        where id_categoria = $id_categoria_del
                                        and estado = 1";
                    $message_error = "Problemas al consultar categorias roles";
                    if (!$resultados = mysqli_query($con->Conexion,$consulta_permisos)) {
                        throw new Exception($message_error . ". Consulta: " . $consulta_permisos, 2);
                    }

                    while ($RetPermisos = mysqli_fetch_array($resultados)) {
                        $grupo_usuarios = $RetPermisos["id_tipousuario"];
                        $id_categoria_rol = $RetPermisos["id_categoria_rol"];
                        if (!CategoriaRol::exist_rol(connection: $con, 
                                                    id_categoria: $id_categoria_unif,
                                                    id_tipo_usuario: $grupo_usuarios)) {
                            $categoria_rol = new CategoriaRol(id_categoria: $id_categoria_unif,
                                                            id_tipo_usuario: $grupo_usuarios, 
                                                            fecha: $fecha,
                                                            conecction: $con,
                                                            estado: 1
                                                            );
                            $categoria_rol->save();
                        }
                        $categoria_rol = new CategoriaRol(
                                                        conecction: $con,
                                                        id_categoria_rol: $id_categoria_rol
                                                        );
                        $categoria_rol->delete();
                    }

                    $solicitud->delete();
                    $con->CloseConexion();
                    $Mensaje = "La categoria se unifico correctamente";
                    header('Location: /home?Mensaje=' . $Mensaje);
                }
            } else {
                $Mensaje = "Elija las categorias a unificar";
                header('Location: /home?MensajeError=' . $Mensaje);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        
    }

    public function buscar_categoria_lista()
    {
        $consultaBusqueda = $_REQUEST['valorBusqueda'];
        $id = $_REQUEST['ID'];
        //Filtro anti-XSS
        $caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
        $caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
        $consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

        $mensaje = "";

        if (isset($consultaBusqueda)) {

            $Con = new Conexion();
            $Con->OpenConexion();

            $query = "SELECT * 
                    FROM categoria 
                    WHERE categoria LIKE '%$consultaBusqueda%' 
                        and estado = 1";

            $consulta = mysqli_query($Con->Conexion, $query);

            $filas = mysqli_num_rows($consulta);

            if ($filas === 0) {
                $mensaje = "<p>No hay ningún registro con ese dato</p>";
            } else {
                $mensaje .= '<table class="table">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">Categoría</th>
                        <th scope="col">Cod_Categoria</th>	
                        <th scope="col">Acción</th>	
                        </tr>
                    </thead>
                    <tbody>';

                while($resultados = mysqli_fetch_array($consulta)) {
                    $ID_Categoria = $resultados["id_categoria"];			
                    $Categoria = $resultados['categoria'];
                    $Cod_Categoria = $resultados['cod_categoria'];
                    $mensaje .= '<tr>
                                    <th scope="row">' . $Categoria . '</th>
                                    <td>' . $Cod_Categoria . '</td>';
                    $mensaje .= '<td>
                                    <button type = "button" class = "btn btn-outline-success" onClick="seleccionCategoria(' . $id . ', \'' . $Categoria . '\',' . $ID_Categoria . ')" data-dismiss="modal">
                                        seleccionar
                                    </button>
                                </td>
                            </tr>';
                };

                $mensaje .= '</tbody>
                    </table>';

            };
            $Con->CloseConexion();

        };

        echo $mensaje;
    } 

    public function buscar_categorias_desc()
    {
        header('Content-Type: text/html; charset=utf-8');

        $consultaBusqueda = $_POST['valorBusqueda'];

        $json_string = file_get_contents('php://input');
        $lista_categoria = json_decode($json_string, true);

        //Filtro anti-XSS
        $caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
        $caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
        $consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

        $mensaje = "";

        if (isset($consultaBusqueda)) {

            $Con = new Conexion();
            $Con->OpenConexion();

            $query = "SELECT * 
                    FROM categoria 
                    WHERE categoria LIKE '%$consultaBusqueda%'
                        and estado = 1
                    ORDER BY tipo_categoria ASC, orden DESC";
            $consulta = mysqli_query($Con->Conexion, $query);

            $filas = mysqli_num_rows($consulta);

            if ($filas === 0) {
                $mensaje = "<p>No hay ningún registro con ese dato</p>";
            } else {
                $mensaje .= '<table class="table">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">Categoría</th>
                        <th scope="col">Cod_Categoria</th>	
                        <th scope="col">Acción</th>	
                        </tr>
                    </thead>
                    <tbody>';

                $valores_categorias = ($lista_categoria) ? array_values(array: $lista_categoria) : [];

                while($resultados = mysqli_fetch_array($consulta)) {
                    $ID_Categoria = $resultados["id_categoria"];			
                    $Categoria = $resultados['categoria'];
                    $Cod_Categoria = $resultados['cod_categoria'];
                    $mensaje .= '<tr>
                                    <th scope="row">' . $Categoria . '</th>
                                    <td>' . $Cod_Categoria . '</td>';

                    if (in_array($ID_Categoria, $valores_categorias)) {
                        $mensaje .= '<td>
                                        <button type = "button" style=\'width:12ch\' class = "btn btn-outline-success" onClick="addMultipleCategoria(\'' . $Categoria . '\',' . $ID_Categoria . ', this)">
                                            &#10003
                                        </button>
                                    </td>
                                </tr>';
                    } else {
                        $mensaje .= '<td>
                                        <button type = "button" class = "btn btn-outline-success" onClick="addMultipleCategoria(\'' . $Categoria . '\',' . $ID_Categoria . ', this)">
                                            seleccionar
                                        </button>
                                    </td>
                                </tr>';
                    }
                };

                $mensaje .= '</tbody>
                    </table>';

            };
            $Con->CloseConexion();

        };

        echo $mensaje;
    }

    public function buscar_categorias_filtrado()
    {
        $Filtro = $_REQUEST["Search"];
        $ID_Filtro = $_REQUEST["ID_Filtro"];

        header("Location: /categorias?Filtro=" . $Filtro  ."&ID_Filtro=" . $ID_Filtro);
    }
}