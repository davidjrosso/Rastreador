<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Calle.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Accion.php");


class PersonaController 
{

    public function listado_personas($mensaje = null)
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

            $Filtro = null;
            $ID_Filtro = null;

            if (isset($_REQUEST["Filtro"])) $Filtro = $_REQUEST["Filtro"];
            if (isset($_REQUEST["ID_Filtro"])) $ID_Filtro = $_REQUEST["ID_Filtro"];
            
            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_personas.php");
        }
        exit();
    }

    public function personas_filter()
    {
        $Filtro = $_REQUEST["Search"];
        $ID_Filtro = $_REQUEST["ID_Filtro"];
        header("Location: ../personas?Filtro=" . $Filtro . "&ID_Filtro=" . $ID_Filtro);
    }

    public function datos_persona($id_persona)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_verpersonas.php");
        }
        exit();
    }

    public function mod_persona($id_persona)
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_modpersonas.php");
        }
        exit();
    }

    function crear_persona()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {

            $id_usuario = $_SESSION["Usuario"];
            $account = new Account(account_id: $id_usuario);
            $tipo_usuario = $account->get_id_tipo_usuario();
            $Element = new Elements();
            $DTGeneral = new CtrGeneral();

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_newpersonas.php");
        }
        exit();    
    }

    function crear_persona_control()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $Apellido = ucwords($_REQUEST["Apellido"]);
        $Nombre = ucwords($_REQUEST["Nombre"]);
        $DNI = trim(str_replace(array('.'), '', $_REQUEST["DNI"]));

        $Nro_Legajo = $_REQUEST["Nro_Legajo"];
        if (empty($Nro_Legajo)) {
            $Nro_Legajo = null;
        }

        $Edad = $_REQUEST["Edad"];
        $Meses = $_REQUEST["Meses"];
        if (empty($Edad)) {
            $Edad = null;
        }

        if (empty($Meses)) {
            $Meses = null;
        }

        if (empty($_REQUEST["Fecha_Nacimiento"])) {
            $Fecha_Nacimiento = null;
        } else {
            $Fecha_Nacimiento = implode("-", array_reverse(explode("/",$_REQUEST["Fecha_Nacimiento"])));
        }

        $Nro_Carpeta = $_REQUEST["Nro_Carpeta"];
        if (empty($Nro_Carpeta)) {
            $Nro_Carpeta = null;
        }
        $Obra_Social = $_REQUEST["Obra_Social"];

        $id_nombre_calle = null;
        if(isset($_REQUEST["Calle"])){
            $calle = ucwords($_REQUEST["Calle"]);
            $nombre_calle = new Calle(id_calle : $calle);
            $Domicilio = $nombre_calle->get_calle_nombre();
            $id_nombre_calle = $nombre_calle->get_id_calle();
        }
        $nro_calle = null;
        if(isset($_REQUEST["NumeroDeCalle"])){
        $nro_calle = $_REQUEST["NumeroDeCalle"];
        $Domicilio .= " ". $nro_calle;
        }

        $georeferencia_point = null;
        if (!empty($_REQUEST["lat"])) {
            $lat_point = $_REQUEST["lat"];
            $georeferencia_point = "POINT(" . $lat_point;

            if (!empty($_REQUEST["lon"])){
                $lon_point = $_REQUEST["lon"];
                $georeferencia_point .= "," . $lon_point . ")";
            } else {
                $georeferencia_point = null;
            }
        }

        if (!isset($_REQUEST["ID_Barrio"])) {
            $ID_Barrio = null;
        } else {
            $ID_Barrio = $_REQUEST["ID_Barrio"];
        }
        if (empty($ID_Barrio)) {
            $ID_Barrio = 37;
        }

        $Localidad = ucwords($_REQUEST["Localidad"]);

        $Circunscripcion = 0;
        if (empty($Circunscripcion)) {
            $Circunscripcion = null;
        }
        $Seccion = 0;
        if (empty($Seccion)) {
            $Seccion = null;
        }
        $Manzana = $_REQUEST["Manzana"];
        if (empty($Manzana)) {
            $Manzana = null;
        }
        $Lote = $_REQUEST["Lote"];
        if (empty($Lote)) {
            $Lote = null;
        }
        $Familia = $_REQUEST["Familia"];
        if (empty($Familia)) {
            $Familia = null;
        }
        $Observaciones = ucfirst($_REQUEST["Observaciones"]);
        $Cambio_Domicilio = $_REQUEST["Cambio_Domicilio"];
        $Telefono = $_REQUEST["Telefono"];
        $Mail = ucfirst($_REQUEST["Mail"]);
        $Estado = 1;
        if (!isset($_REQUEST["ID_Escuela"])) {
            $ID_Escuela = null;
        } else {
            $ID_Escuela = $_REQUEST["ID_Escuela"];
        }

        $Trabajo = strtoupper($_REQUEST["Trabajo"]);

        if (empty($ID_Escuela)) {
            $ID_Escuela = 2;
        }

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 1;
        $Detalles = "El usuario con ID: $ID_Usuario ha registrado una nueva Persona. Datos: Apellido: $Apellido - Nombre: $Nombre - Documento: $DNI - Nro Legajo: $Nro_Legajo - Edad: $Edad - Meses: $Meses - Fecha de Nacimiento: $Fecha_Nacimiento - Telefono: $Telefono - E-Mail: $Mail - Nro Carpeta: $Nro_Carpeta - Obra Social: $Obra_Social - Domicilio: $Domicilio - Barrio: $ID_Barrio - Escuela: $ID_Escuela - Localidad: $Localidad - Circunscripcion: $Circunscripcion - Seccion: $Seccion - Manzana: $Manzana - Lote: $Lote - Familia: $Familia - Observaciones: $Observaciones - Cambio Domicilio: $Cambio_Domicilio";

        try {
            if (Persona::is_registered($DNI)) {
                $Mensaje = "Ya existe un Usuario con el mismo Apellido y Nombre que el que esta intentando crear. Por favor ingrese un DNI para identificar a la persona.";
                header('Location: /persona/nueva?MensajeError=' . $Mensaje);
            } else {
                $Persona = new Persona(
                    xApellido : $Apellido,
                    xBarrio : $ID_Barrio,
                    xCambio_Domicilio : $Cambio_Domicilio,
                    xCalle: $id_nombre_calle,
                    xCircunscripcion : $Circunscripcion,
                    xDNI : $DNI,
                    xDomicilio : $Domicilio,
                    xEdad : $Edad,
                    xEstado : $Estado,
                    xFamilia : $Familia,
                    xFecha_Nacimiento: $Fecha_Nacimiento,
                    xID_Escuela : $ID_Escuela,
                    xLote : $Lote,
                    xLocalidad : $Localidad,
                    xMail : $Mail,
                    xManzana : $Manzana,
                    xMeses : $Meses,
                    xNombre : $Nombre,
                    xNro: $nro_calle,
                    xNro_Carpeta: $Nro_Carpeta,
                    xNro_Legajo : $Nro_Legajo,
                    xObservaciones : $Observaciones,
                    xObra_Social: $Obra_Social,
                    xSeccion : $Seccion,
                    xTelefono : $Telefono,
                    xTrabajo : $Trabajo
                );
                $Persona->setDomicilio();
                if (!empty($georeferencia_point)) {
                    $Persona->setGeoreferencia($georeferencia_point);
                }
                $Persona->save();

                if ($Persona->getEdad() == 0) {
                    $Persona->update_edad_meses();
                }
                $accion = new Accion(
                    xaccountid: $ID_Usuario,
                    xFecha : $Fecha,
                    xDetalles: $Detalles,
                    xID_TipoAccion: $ID_TipoAccion	 
                );
                $accion->save();
                $Mensaje = "La persona fue registrada Correctamente";
                header('Location: /persona/nueva?Mensaje=' . $Mensaje);
            }
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        exit();    
    } 

    public function mod_persona_control()
    {
        header("Content-Type: text/html;charset=utf-8");

        $ID_Usuario = $_SESSION["Usuario"];

        $from_reporte_grafico = (!empty($_SESSION["from_reporte_grafico"])) ? true : false;

        $ID_Persona = $_REQUEST["ID"];
        $Apellido = ucwords($_REQUEST["Apellido"]);
        $Nombre = ucwords($_REQUEST["Nombre"]);
        $DNI = trim(str_replace(array('.'),'',$_REQUEST["DNI"]));
        $Nro_Legajo = $_REQUEST["Nro_Legajo"];

        $Edad = $_REQUEST["Edad"];
        $Meses = $_REQUEST["Meses"];

        if(empty($_REQUEST["Fecha_Nacimiento"])){
            $Fecha_Nacimiento = 'null';
        } else {
            $Fecha_Nacimiento = implode("-", array_reverse(explode("/",$_REQUEST["Fecha_Nacimiento"])));
        }

        ///////////////////////////CALCULAR EDAD//////////////////////////////////////////////////
        if($Edad == 'null' || $Edad == ""){
            list($ano,$mes,$dia) = explode("-",$Fecha_Nacimiento);
            $ano_diferencia = date("Y") - $ano;
            $mes_diferencia = date("m") - $mes;
            $dia_diferencia = date("d") - $dia;
            if($dia_diferencia < 0 || $mes_diferencia < 0){
                $ano_diferencia--;
            }
            $Edad = $ano_diferencia;
        }


        if($Edad == 0){
            $Fecha_Actual = new DateTime();
            $Fecha_Nacimiento_Registrada = new DateTime($Fecha_Nacimiento);
            $Diferencia = $Fecha_Nacimiento_Registrada->diff($Fecha_Actual);
            $Meses = $Diferencia->m;
        }
        /////////////////////////////////////////////////////////////////////////////////////////

        $georeferencia_point = null; 
        if (!empty($_REQUEST["lat"])) {
            $lat_point = $_REQUEST["lat"];
            $georeferencia_point = "POINT(" . $lat_point;

            if (!empty($_REQUEST["lon"])){
                $lon_point = $_REQUEST["lon"];
                $georeferencia_point .= "," . $lon_point . ")";
            } else {
                $georeferencia_point = null;
            }
        }

        $Nro_Carpeta = $_REQUEST["Nro_Carpeta"];
        $Obra_Social = $_REQUEST["Obra_Social"];
        $calle = null;
        $Domicilio = "";
        if (isset($_REQUEST["Calle"])) {
            $calle = ucwords($_REQUEST["Calle"]);
            $nombre_calle = new Calle(id_calle : $calle);
            $Domicilio = $nombre_calle->get_calle_open();
        }

        if (isset($_REQUEST["NumeroDeCalle"])) {
        $nro_calle = $_REQUEST["NumeroDeCalle"];
        if (!empty($Domicilio)) {
            $Domicilio .= " ". $nro_calle;
        } else {
            $Domicilio = null;
        }
        }
        $ID_Barrio = $_REQUEST["ID_Barrio"];
        $Localidad = ucwords($_REQUEST["Localidad"]);
        $Circunscripcion = 0;
        $Seccion = 0;
        $Manzana = $_REQUEST["Manzana"];
        $Lote = $_REQUEST["Lote"];
        $Familia = ucfirst($_REQUEST["Familia"]);
        $Observaciones = ucfirst($_REQUEST["Observaciones"]);
        $Cambio_Domicilio = $_REQUEST["Cambio_Domicilio"];
        $Telefono = $_REQUEST["Telefono"];
        $Mail = ucfirst($_REQUEST["Mail"]);
        $Estado = 1;
        $ID_Escuela = $_REQUEST["ID_Escuela"];
        $Trabajo = strtoupper($_REQUEST["Trabajo"]);

        if(empty($ID_Escuela)){
            $ID_Escuela = 2;
        }

        if(empty($Nro_Carpeta)){
            $Nro_Carpeta = null;
        }
        if(empty($Circunscripcion)){
            $Circunscripcion = null;
        }
        if(empty($Seccion)){
            $Seccion = null;
        }
        if(empty($Manzana)){
            $Manzana = null;
        }
        if(empty($Lote)){
            $Lote = null;
        }
        if(empty($Familia)){
            $Familia = null;
        }

        if(empty($ID_Barrio)){
            $ID_Barrio = 37;
        }

        $Persona = new Persona(
                            ID_Persona : $ID_Persona,
                            xApellido : $Apellido,
                            xNombre : $Nombre,
                            xDNI : $DNI,
                            xNro_Legajo : $Nro_Legajo,
                            xEdad : $Edad,
                            xMeses : $Meses,
                            xFecha_Nacimiento: $Fecha_Nacimiento,
                            xNro_Carpeta: $Nro_Carpeta,
                            xObra_Social: $Obra_Social,
                            xDomicilio : $Domicilio,
                            xBarrio : $ID_Barrio,
                            xLocalidad : $Localidad,
                            xCircunscripcion : $Circunscripcion,
                            xSeccion : $Seccion,
                            xManzana : $Manzana,
                            xLote : $Lote,
                            xFamilia : $Familia,
                            xObservaciones : $Observaciones,
                            xCambio_Domicilio : $Cambio_Domicilio,
                            xTelefono : $Telefono,
                            xMail : $Mail,
                            xID_Escuela : $ID_Escuela,
                            xEstado : $Estado,
                            xTrabajo : $Trabajo,
                            xCalle : $calle,
                            xNro : $nro_calle,
                            xGeoreferencia: $georeferencia_point
        );

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 2;
        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $ConsultarRegistrosIguales = "select * from persona where documento = '{$DNI}' and id_persona != $ID_Persona and estado = 1";
            $RetIguales = mysqli_query($Con->Conexion,$ConsultarRegistrosIguales);
            if (!$RetIguales) {
                throw new Exception("Problemas al intentar Consultar Registros Iguales", 0);		
            }

            $Registros = mysqli_num_rows($RetIguales);
            if ($Registros > 0 && !empty($DNI)) {
                mysqli_free_result($RetIguales);
                $Con->CloseConexion();
                $Mensaje = "Ya existe una Persona con ese Apellido y Nombre por Favor Introduzca Otros Datos";
                header('Location: /persona/editar?ID='.$ID_Persona.'&MensajeError='.$Mensaje);
            } else {
                $ConsultarDatosViejos = "select * from persona where id_persona = $ID_Persona and estado = 1";
                $ErrorDatosViejos = "No se pudieron consultar los datos";
                if (!$RetDatosViejos = mysqli_query($Con->Conexion,$ConsultarDatosViejos)) {
                    throw new Exception("Error al intentar registrar. Consulta: ".$ConsultarDatosViejos, 1);
                }

                $Persona_Viejo = new Persona($ID_Persona);
                $Persona_Viejo->setApellido($Persona->getApellido());
                $Persona_Viejo->setBarrio($Persona->getId_Barrio());
                $Persona_Viejo->setCamio_Domicilio($Persona->getCambio_Domicilio());
                $Persona_Viejo->setCircunscripcion($Persona->getCircunscripcion());
                $Persona_Viejo->setDNI($Persona->getDNI());
                $Persona_Viejo->setEdad($Persona->getEdad());
                $Persona_Viejo->setNombre($Persona->getNombre());
                $Persona_Viejo->setNro_Legajo($Persona->getNro_Legajo());
                $Persona_Viejo->setFamilia($Persona->getFamilia());
                $Persona_Viejo->setFecha_Nacimiento($Persona->getFecha_Nacimiento());
                $Persona_Viejo->setID_Escuela($Persona->getID_Escuela());
                $Persona_Viejo->setLocalidad($Persona->getLocalidad());
                $Persona_Viejo->setLote($Persona->getLote());
                $Persona_Viejo->setMail($Persona->getMail());
                $Persona_Viejo->setManzana($Persona->getManzana());
                $Persona_Viejo->setMeses($Persona->getMeses());
                $Persona_Viejo->setNro_Carpeta($Persona->getNro_Carpeta());
                $Persona_Viejo->setObra_Social($Persona->getObra_Social());
                $Persona_Viejo->setObservaciones($Persona->getObservaciones());
                $Persona_Viejo->setSeccion($Persona->getSeccion());
                $Persona_Viejo->setTrabajo($Persona->getTrabajo());
                $Persona_Viejo->setTelefono($Persona->getTelefono());
                $Persona_Viejo->setNro($Persona->getNro());
                $Persona_Viejo->setCalle($Persona->getId_Calle());

                if ($Persona->getId_Calle() && $Persona->getNro()){
                    $Persona_Viejo->setDomicilio();	
                } else {
                    $Persona_Viejo->setDomicilio($Persona->getDomicilio());
                }

                if ($georeferencia_point) {
                    $Persona_Viejo->setGeoreferencia(xGeoreferencia: $georeferencia_point);
                }
                $Persona_Viejo->update();

                $Detalles = "El usuario con ID: $ID_Usuario ha modificado una Persona. Datos modificados : ";
                $Detalles .= mysqli_real_escape_string($Con->Conexion, json_encode($Persona_Viejo));
                $Detalles .= " Datos anteriores : " .  mysqli_real_escape_string($Con->Conexion, json_encode($Persona));

                $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";

                if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                    throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 3);
                }

                // CREANDO NOTIFICACION PARA EL USUARIO		
                $DetalleNot = 'Se modifico la persona Nombre: '.$Persona->getApellido(). ', '.$Persona->getNombre(). (($Persona->getDNI() == null)?'':' dni: '. $Persona->getDNI());
                $Expira = date("Y-m-d", strtotime($Fecha." + 15 days"));

                $ConsultaNot = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('$DetalleNot','$Fecha', '$Expira',1)";
                if(!$RetNot = mysqli_query($Con->Conexion,$ConsultaNot)){
                    throw new Exception("Error al intentar registrar Notificacion. Consulta: ".$ConsultaNot, 3);
                }

                $Con->CloseConexion();
                $Mensaje = "La Persona fue modificada Correctamente";

                if ($from_reporte_grafico) {
                    $reporte = "true";
                } else {
                    $reporte = "false";
                }

                header('Location: /persona/editar?ID=' . $ID_Persona . '&Mensaje=' . $Mensaje . "&reporte=" . $reporte);		
            }

        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        exit();
    }

    public function unif_persona()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (!isset($_SESSION["Usuario"])) {
            include("./Views/Error_Session.php");
        } else {
            include("./Views/view_unifpersonas.php");
        }
        exit();
    }

    public function unif_persona_control()
    {
        $ID_Solicitud = isset($_REQUEST["ID_Solicitud"])? $_REQUEST["ID_Solicitud"] : null;
        $ID_Persona_1 = $_REQUEST["ID_Persona_1"];
        $ID_Persona_2 = $_REQUEST["ID_Persona_2"];
        $ID_Usuario = isset($_SESSION["Usuario"])? $_SESSION["Usuario"]: null;
        if ($ID_Persona_1 > 0 && $ID_Persona_2 > 0) {
            $con = new Conexion();
            $con->OpenConexion();
            
            if (($ID_Solicitud != null && $ID_Solicitud != "" )) {
                
                $consulta = "update movimiento 
                set id_persona = $ID_Persona_1 
                where id_persona = $ID_Persona_2";
                
                mysqli_query($con->Conexion,$consulta) or die("Problemas en la consulta");
                
                $ConsultaBajaPersona = "update persona 
                                        set estado = 0 
                                        where id_persona = $ID_Persona_2";

                $MensajeErrorBajaPersona = "No se pudo dar de baja la persona";

                mysqli_query($con->Conexion,$ConsultaBajaPersona) or die($MensajeErrorBajaPersona);

                $ConsultaSolicitud = "update solicitudes_unificacion
                                    set Estado = 0 
                                    where ID_Solicitud_Unificacion = $ID_Solicitud";

                if(!$Ret = mysqli_query($con->Conexion,$ConsultaSolicitud)){
                    throw new Exception("Problemas en la consulta. Consulta: " . $ConsultaSolicitud, 3);			
                }
                $con->CloseConexion();
                $Mensaje = "Los datos se unificaron Correctamente";
                header('Location: /home?Mensaje='.$Mensaje);
            } else {
                $consulta = "UPDATE movimiento 
                             SET id_persona = $ID_Persona_1 
                             WHERE id_persona = $ID_Persona_2";
                mysqli_query($con->Conexion,$consulta) or die("Problemas en la consulta");

                $ConsultaBajaPersona = "update persona 
                                        set estado = 0 
                                        where id_persona = $ID_Persona_2";

                $MensajeErrorBajaPersona = "No se pudo dar de baja la persona";
                mysqli_query($con->Conexion,$ConsultaBajaPersona) or die($MensajeErrorBajaPersona);
                $Mensaje = "Los datos se unificaron Correctamente";
                header('Location: /home?Mensaje=' . $Mensaje);
            }
        } else {
            $MensajeError = "Debe seleccionar Primera Persona y Segunda Persona";
            header('Location: /home?MensajeError=' . $MensajeError);
        }

    }

    public function delete_persona()
    {
        $ID_Usuario = $_SESSION["Usuario"];

        $ID_Persona = $_REQUEST["ID"];

        $Fecha = date("Y-m-d");
        $ID_TipoAccion = 3;
        $Detalles = "El usuario con ID: $ID_Usuario ha dado de baja una Persona. Datos: Persona: $ID_Persona";

        try {
            $Con = new Conexion();
            $Con->OpenConexion();

            $Consulta = "update persona set estado = 0 where id_persona = $ID_Persona";
            if(!$Ret = mysqli_query($Con->Conexion,$Consulta)){
                throw new Exception("Problemas en la Consulta. Consulta: ".$Consulta, 0);		
            }
            $ConsultaAccion = "insert into Acciones(accountid,Fecha,Detalles,ID_TipoAccion) values($ID_Usuario,'$Fecha','$Detalles',$ID_TipoAccion)";
            if(!$RetAccion = mysqli_query($Con->Conexion,$ConsultaAccion)){
                throw new Exception("Error al intentar registrar Accion. Consulta: ".$ConsultaAccion, 1);
            }

            $ConsultarDatos = "select * from persona where id_persona = $ID_Persona";
            $ErrorDatos = "No se pudieron consultar los datos : ";
            if(!$RetDatos = mysqli_query($Con->Conexion,$ConsultarDatos)){
                throw new Exception($ErrorDatos.$ConsultarDatos, 1);
            }

            $TomarDatos = mysqli_fetch_assoc($RetDatos);
            $Apellido = $TomarDatos["apellido"];
            $Nombre = $TomarDatos["nombre"];
            $DNI = $TomarDatos["documento"];
            
            // CREANDO NOTIFICACION PARA EL USUARIO		
            $DetalleNot = 'Se elimino la persona Nombre: '.$Apellido. ', '.$Nombre. (($DNI == null)?'':' dni: '. $DNI);
            $Expira = date("Y-m-d", strtotime($Fecha." + 15 days"));
            
            $ConsultaNot = "insert into notificaciones(Detalle, Fecha, Expira, Estado) values('$DetalleNot','$Fecha', '$Expira',1)";
            if(!$RetNot = mysqli_query($Con->Conexion,$ConsultaNot)){
                throw new Exception("Error al intentar registrar Notificacion. Consulta: ".$ConsultaNot, 3);
            }

            $Con->CloseConexion();
            $Mensaje = "La persona se elimino Correctamente";
            header('Location: ../personas?Mensaje='.$Mensaje);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
}
    }
}