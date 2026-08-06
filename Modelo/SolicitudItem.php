<?php 
class SolicitudItem
{
    //DECLARACION DE VARIABLES
    private $coneccion;
    private $estado;
    private $valor;
    private $id_solicitud_item;
    private $id_solicitud;
    private $identificador;


    public function __construct(
                                $id_solicitud=null,
                                $valor=null,
                                $id_solicitud_item=null,
                                $identificador=null,
                                $coneccion=null,
                                $estado=null,
    ) {
        $this->coneccion = $coneccion;
        if ($id_solicitud_item) {
            $consultar = "select *
                          from solicitudes_items
                            where id_solicitud_item = " . $id_solicitud_item . " 
                            and estado = 1";
            $ejec = mysqli_query(
                                 $coneccion->Conexion, 
                                 $consultar
                                );
            $ret = mysqli_fetch_assoc($ejec);
            if (!is_null($ret)) {
                $id_solicitud = $ret["id_solicitud"];
                $valor = $ret["valor"];
                $id_solicitud_item = $ret["id_solicitud_item"];
                $identificador = $ret["identificador"];
                $estado = $ret["estado"];

                $this->id_solicitud = $id_solicitud;
                $this->valor = $valor;
                $this->id_solicitud_item = $id_solicitud_item;
                $this->identificador = $identificador;
                $this->estado = ($estado) ? $estado : 0;
            }
        } else {
            $this->id_solicitud = $id_solicitud;
            $this->valor = $valor;
            $this->id_solicitud_item = $id_solicitud_item;
            $this->identificador = $identificador;
            $this->estado = ($estado) ? $estado : 0;
        }
    }

    //METODOS SET
    public function set_id_solicitud($id_solicitud)
    {
        $this->id_solicitud = $id_solicitud;
    }

    public function set_valor($valor)
    {
        $this->valor = $valor;
    }

    public function set_id_solicitud_item($id_solicitud_item)
    {
        $this->id_solicitud_item = $id_solicitud_item;
    }

    public function set_identificador($identificador)
    {
        $this->identificador = $identificador;
    }

    public function set_coneccion($coneccion)
    {
        $this->coneccion = $coneccion;
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

    public function get_valor()
    {
        return $this->valor;
    }

    public function get_id_solicitud_item()
    {
        return $this->id_solicitud_item;
    }

    public function get_identificador()
    {
        return $this->identificador;
    }

    public function get_coneccion_base()
    {
        return $this->coneccion;
    }

    public function get_estado()
    {
        return $this->estado;
    }

    public function delete() 
    {
        $consulta = "UPDATE solicitudes_items
                     SET estado = 0
                     WHERE id_solicitud_item = " . $this->get_id_solicitud_item();
        $mensaje = "No se pudo eliminar itme de la solicitud";
        mysqli_query($this->coneccion->Conexion, $consulta);
    }

    public function save() 
    {
        $consulta = "insert into solicitudes_items( 
                                                valor,
                                                id_solicitud_item,
                                                identificador,
                                                estado,
                                                ) values(
                                                    '" . (($this->get_valor()) ? "'" . $this->get_valor() . "'" : "null") . "',
                                                    " . (($this->get_id_solicitud_item()) ? $this->get_id_solicitud_item() : "null") . ",
                                                    " . (($this->get_identificador()) ? "'" . $this->get_identificador() . "'" : "null") . ",
                                                    " . (($this->get_estado()) ? $this->get_estado() : "null") . "
                                                )";
        $mensaje_error = "No se pudo agregar item a la solicitud";
        mysqli_query($this->coneccion->Conexion, $consulta);
		$this->id_solicitud_item = mysqli_insert_id($this->coneccion->Conexion);
    }

}