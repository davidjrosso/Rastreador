<?php 
class Solicitud
{
    //DECLARACION DE VARIABLES
    private $coneccion;
    private $estado;
    private $fecha;
    private $id_tipo_grupo_operacion;
    private $id_tipo_accion;
    private $id_usuario;
    private $id_solicitud;

    public function __construct(
                                $id_solicitud=null,
                                $fecha=null,
                                $id_tipo_accion=null,
                                $id_usuario=null,
                                $coneccion=null,
                                $estado=null,
                                $id_tipo_grupo_operacion = null
    ) {
        $this->coneccion = $coneccion;
        if ($id_solicitud) {
            $consultar = "select *
                          from solicitudes
                            where id_solicitud = " . $id_solicitud . " 
                            and estado = 1";
            $ejec = mysqli_query(
                                 $coneccion->Conexion, 
                                 $consultar
                                );
            $ret = mysqli_fetch_assoc($ejec);
            if (!is_null($ret)) {
                $id_tipo_grupo_operacion = $ret["id_tipo_grupo_operacion"];
                $id_solicitud = $ret["id_solicitud"];
                $fecha = $ret["fecha"];
                $id_tipo_accion = $ret["id_tipo_accion"];
                $id_usuario = $ret["id_usuario"];
                $estado = $ret["estado"];

                $this->id_tipo_grupo_operacion = $id_tipo_grupo_operacion;
                $this->id_solicitud = $id_solicitud;
                $this->fecha = $fecha;
                $this->id_tipo_accion = $id_tipo_accion;
                $this->id_usuario = $id_usuario;
                $this->estado = ($estado) ? $estado : 0;
            }
        } else {
            $this->id_tipo_grupo_operacion = $id_tipo_grupo_operacion;
            $this->id_solicitud = $id_solicitud;
            $this->fecha = $fecha;
            $this->id_tipo_accion = $id_tipo_accion;
            $this->id_usuario = $id_usuario;
            $this->estado = ($estado) ? $estado : 0;
        }
    }

    //METODOS SET
    public function set_id_solicitud($id_solicitud)
    {
        $this->id_solicitud = $id_solicitud;
    }

    public function set_fecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function set_id_tipo_accion($id_tipo_accion)
    {
        $this->id_tipo_accion = $id_tipo_accion;
    }

    public function set_id_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function set_coneccion($coneccion)
    {
        $this->coneccion = $coneccion;
    }

    public function set_estado($estado)
    {
        $this->estado = $estado;
    }

    public function set_tipo_grupo_operacion($id_tipo_grupo_operacion)
    {
        $this->id_tipo_grupo_operacion = $id_tipo_grupo_operacion;
    }

    //METODOS GET
    public function get_id_solicitud()
    {
        return $this->id_solicitud;
    }

    public function get_fecha()
    {
        return $this->fecha;
    }

    public function get_id_tipo_accion()
    {
        return $this->id_tipo_accion;
    }

    public function get_id_usuario()
    {
        return $this->id_usuario;
    }

    public function get_coneccion_base()
    {
        return $this->coneccion;
    }

    public function get_estado()
    {
        return $this->estado;
    }
    
    public function get_id_tipo_grupo_operacion()
    {
        return $this->id_tipo_grupo_operacion;
    }

    public function delete() 
    {
        $consulta = "UPDATE solicitudes
                             SET estado = 0
                             WHERE id_solicitud = " . $this->get_id_solicitud();
        $mensaje = "No se pudo eliminar la solicitud";
        mysqli_query($this->coneccion->Conexion, $consulta);
    }

    public function save() 
    {
        $fecha = date(format: "Y-m-d");
        $consulta = "insert into solicitudes( 
                                                fecha,
                                                id_tipo_accion,
                                                id_usuario,
                                                estado,
                                                id_tipo_grupo_accion
                                                ) values(
                                                    '" . (($this->get_fecha()) ? $this->get_fecha() : $fecha) . "',
                                                    " . (($this->get_id_tipo_accion()) ? $this->get_id_tipo_accion() : "null") . ",
                                                    " . (($this->get_id_usuario()) ? $this->get_id_usuario() : "null") . ",
                                                    " . (($this->get_estado()) ? $this->get_estado() : "null") . ",
                                                    " . (($this->get_id_tipo_grupo_operacion()) ? $this->get_id_tipo_grupo_operacion() : "null") . "
                                                )";
        $mensaje_error = "No se pudo enviar la solicitud";
        mysqli_query($this->coneccion->Conexion, $consulta);
		$this->id_solicitud = mysqli_insert_id($this->coneccion->Conexion);
    }

}