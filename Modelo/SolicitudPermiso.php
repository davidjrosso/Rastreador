<?php 
class SolicitudPermiso
{
    //DECLARACION DE VARIABLES
    private $coneccion_base;
    private $estado;
    private $fecha;
    private $id;
    private $id_tipo_usuario;
    private $id_solicitud_permiso;

    public function __construct(
                                $coneccion_base=null,
                                $id_solicitud_permiso=null,
                                $fecha=null,
                                $id=null,
                                $id_tipo_usuario=null,
                                $estado=null
    ){
        $this->coneccion_base = $coneccion_base;
        if ($id_solicitud_permiso) {
            $consultar = "select *
                          from solicitudes_permisos 
                            where id_solicitud = " . $id_solicitud_permiso . " 
                            and estado = 1";
            $ejecutar_consultar = mysqli_query(
            $coneccion_base->Conexion, 
            $consultar) or die("Problemas al consultar filtro de solicitudes_permisos");
            $ret = mysqli_fetch_assoc($ejecutar_consultar);
            if (!is_null($ret)) {
                $row_id_solicitud_permiso = $ret["id_solicitud"];
                $row_fecha = $ret["fecha"];
                $row_id = $ret["ID"];
                $row_id_tipo_usuario = $ret["ID_TipoUsuario"];
                $row_estado = $ret["estado"];

                $this->id_solicitud_permiso = $row_id_solicitud_permiso;
                $this->fecha = $row_fecha;
                $this->id = $row_id;
                $this->id_tipo_usuario = $row_id_tipo_usuario;
                $this->estado = ($estado) ? $estado : 0;
            }
        } else {
            $this->fecha = $fecha;
            $this->id = $id;
            $this->id_tipo_usuario = $id_tipo_usuario;
            $this->coneccion_base = $coneccion_base;
            $this->estado = ($estado) ? $estado : 1;
        }
    }

    //METODOS SET
    public function set_id_solicitud_permiso($id_solicitud_permiso)
    {
        $this->id_solicitud_permiso = $id_solicitud_permiso;
    }

    public function set_fecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function set_id_tipo_usuario($id_tipo_usuario)
    {
        $this->id_tipo_usuario = $id_tipo_usuario;
    }

    public function set_coneccion_base($coneccion_base)
    {
        $this->coneccion_base = $coneccion_base;
    }

    public function set_estado($estado)
    {
        $this->estado = $estado;
    }

    //METODOS GET
    public function get_id_solicitud_permiso()
    {
        return $this->id_solicitud_permiso;
    }

    public function get_fecha()
    {
        return $this->fecha;
    }

    public function get_id_tipo_usuario()
    {
        return $this->id_tipo_usuario;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_coneccion_base()
    {
        return $this->coneccion_base;
    }

    public function get_estado()
    {
        return $this->estado;
    }

    public function delete() 
    {
        $fecha = date(format: "Y-m-d");
        $consulta = "UPDATE solicitudes_permisos
                             SET estado = 0
                             WHERE id_solicitud_permiso = " . $this->get_id_solicitud_permiso();
        $mensaje_error = "No se pudo enviar la solicitud";
        mysqli_query($this->coneccion_base->Conexion,$consulta) or die($mensaje_error);
    }

    public function save() 
    {
        $fecha = date(format: "Y-m-d");
        $consulta = "insert into solicitudes_permisos( 
                                                      fecha,
                                                      ID_TipoUsuario,
                                                      id,
                                                      estado
                                                            ) values(
                                                                " . (($this->get_fecha()) ? "'" . $this->get_fecha() . "'" : $fecha) . ",
                                                                " . $this->get_id_tipo_usuario() . ",
                                                                " . $this->get_id() . ",
                                                                " . $this->get_estado() . "
                                                            )";
        $mensaje_error = "No se pudo enviar la solicitud";
        mysqli_query($this->coneccion_base->Conexion,$consulta) or die($mensaje_error);
		$this->id_solicitud = mysqli_insert_id($this->coneccion_base->Conexion);
    }

}