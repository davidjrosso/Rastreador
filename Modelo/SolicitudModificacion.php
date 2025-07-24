<?php 
class SolicitudModificacion
{
    //DECLARACION DE VARIABLES
    private $coneccion_base;
    private $estado;
    private $fecha;
    private $id_registro;
    private $id_tipo;
    private $id_usuario;
    private $id_solicitud;
    private $valor;

    public function __construct(
                                $id_solicitud=null,
                                $fecha=null,
                                $id_registro=null,
                                $id_tipo=null,
                                $id_usuario=null,
                                $valor=null,
                                $coneccion_base=null,
                                $estado=null
    ){
        $this->coneccion_base = $coneccion_base;
        if ($id_solicitud) {
            $consultar = "select *
                          from solicitudes_modificacion 
                            where id_solicitud = " . $id_solicitud . " 
                            and estado = 1";
            $ejecutar_consultar = mysqli_query(
            $coneccion_base->Conexion, 
            $consultar) or die("Problemas al consultar filtro de solicitudes_modificacion");
            $ret = mysqli_fetch_assoc($ejecutar_consultar);
            if (!is_null($ret)) {
                $id_solicitud = $ret["id_solicitud"];
                $fecha = $ret["fecha"];
                $valor = $ret["valor"];
                $id_registro = $ret["id_registro"];
                $id_tipo = $ret["id_tipo"];
                $id_usuario = $ret["id_usuario"];
                $estado = $ret["estado"];

                $this->id_solicitud = $id_solicitud;
                $this->fecha = $fecha;
                $this->valor = $valor;
                $this->id_registro = $id_registro;
                $this->id_tipo = $id_tipo;
                $this->id_usuario = $id_usuario;
                $this->estado = ($estado) ? $estado : 0;
            }
        } else {
            $this->id_solicitud = $id_solicitud;
            $this->fecha = $fecha;
            $this->id_registro = $id_registro;
            $this->id_tipo = $id_tipo;
            $this->id_usuario = $id_usuario;
            $this->valor = $valor;
            $this->coneccion_base = $coneccion_base;
            $this->estado = ($estado) ? $estado : 1;
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

    public function set_id_registro($id_registro)
    {
        $this->id_registro = $id_registro;
    }

    public function set_id_tipo($id_tipo)
    {
        $this->id_tipo = $id_tipo;
    }

    public function set_id_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function set_valor($valor)
    {
        $this->valor = $valor;
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
    public function get_id_solicitud()
    {
        return $this->id_solicitud;
    }

    public function get_fecha()
    {
        return $this->fecha;
    }

    public function get_id_tipo()
    {
        return $this->id_tipo;
    }

    public function get_id_usuario()
    {
        return $this->id_usuario;
    }

    public function get_valor()
    {
        return $this->valor;
    }

    public function get_id_registro()
    {
        return $this->id_registro;
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
        $consulta = "UPDATE solicitudes_modificacion
                             SET estado = 0
                             WHERE id_solicitud = " . $this->get_id_solicitud();
        $mensaje_error = "No se pudo enviar la solicitud";
        mysqli_query($this->coneccion_base->Conexion,$consulta) or die($mensaje_error);
    }

    public function save() 
    {
        $fecha = date(format: "Y-m-d");
        $consulta = "insert into solicitudes_modificacion( 
                                                          fecha,
                                                          valor,
                                                          id_tipo,
                                                          id_usuario,
                                                          id_registro,
                                                          estado
                                                          ) values(
                                                              '" . (($this->get_fecha()) ? $this->get_fecha() : $fecha) . "',
                                                               " . (($this->get_valor()) ? "'" . $this->get_valor() . "'" : 'null') . ",
                                                               " . $this->get_id_tipo() . ",
                                                               " . $this->get_id_usuario() . ",
                                                               " . $this->get_id_registro() . ",
                                                               " . $this->get_estado() . "
                                                            )";
        $mensaje_error = "No se pudo enviar la solicitud";
        mysqli_query($this->coneccion_base->Conexion,$consulta) or die($mensaje_error);
		$this->id_solicitud = mysqli_insert_id($this->coneccion_base->Conexion);
    }

}