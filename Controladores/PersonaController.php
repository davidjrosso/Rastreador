<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/Controladores/Conexion.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Parametria.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Persona.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/Modelo/Calle.php");


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

            $mensaje_error = (isset($_REQUEST["MensajeError"])) ? $_REQUEST["MensajeError"] : "";
            $mensaje_success = (isset($_REQUEST["Mensaje"])) ? $_REQUEST["Mensaje"] : "";

            include("./Views/view_personas.php");
        }
        exit();
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