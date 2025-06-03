<?php  
class Parametria implements JsonSerializable {
    //DECLARACION DE VARIABLES
    private $coneccion_base;
    private $id_parametria;
    private $fecha;
    private $fecha_validez;
    private $fecha_expiracion;
    private $valor;
    private $codigo;
    private $estado;

    //METODO CONSTRUCTOR
    public function __construct(
                                $id_parametria=null,
                                $fecha=null,
                                $fecha_validez=null,
                                $fecha_expiracion=null,
                                $valor=null,
                                $codigo=null,
                                $estado=null,
                                $coneccion_base=null
                                )
    {
        $consulta = "select *
                    from parametrias 
                    where lower(codigo) = lower('" . $codigo . "') 
                    and estado = 1";
        $ejecutar_consulta = mysqli_query(
            $coneccion_base->Conexion,
            $consulta) or die("Problemas al consultar Parametria");
        $cant = mysqli_num_rows($ejecutar_consulta);
        if ($cant < 1) {
            $this->fecha = $fecha;
            $this->fecha_validez = $fecha_validez;
            $this->fecha_expiracion = $fecha_expiracion;
            $this->valor = $valor;
            $this->codigo = $codigo;
            $this->estado = $estado;
        } else {
            $ret = mysqli_fetch_assoc($ejecutar_consulta);
            $this->id_parametria = (!is_null($ret["id_parametria"])) ? $ret["id_parametria"] : $id_parametria;
            $this->fecha = (!is_null($ret["fecha"])) ? $ret["fecha"] : $fecha;
            $this->fecha_validez = (!is_null($ret["fecha_validez"])) ? $ret["fecha_validez"] : $fecha_validez;
            $this->fecha_expiracion = (!is_null($ret["fecha_expiracion"])) ? $ret["fecha_expiracion"] : $fecha_expiracion;
            $this->valor = (!is_null($ret["valor"])) ? $ret["valor"] : $valor;
            $this->codigo = (!is_null($ret["codigo"])) ? $ret["codigo"] : $codigo;
            $this->estado = (!is_null($ret["estado"])) ? $ret["estado"] : $estado;
        }
        $this->coneccion_base = $coneccion_base;
    }

    public static function get_value_by_code($coneccion, $code)
    {
        $consulta = "select *
                     from parametrias 
                     where lower(codigo) = lower('" . $code . "') 
                     and estado = 1";
        $ejecutar_consulta = mysqli_query(
            $coneccion->Conexion, 
            $consulta) or die("Problemas al consultar filtro Usuario");
        $ret = mysqli_fetch_assoc($ejecutar_consulta);
        $valor = (!is_null($ret["valor"])) ? $ret["valor"] : null;
        return $valor; 
    }

    //METODOS SET
    public function set_id_parametria($id_parametria)
    {
        $this->id_parametria = $id_parametria;
    }

    public function set_fecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function set_fecha_validez($fecha_validez)
    {
        $this->fecha_validez = $fecha_validez;
    }

    public function set_fecha_expiracion($fecha_expiracion)
    {
        $this->fecha_expiracion = $fecha_expiracion;
    }

    public function set_valor($valor)
    {
        $this->valor = $valor;
    }
    public function set_codigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function set_estado($estado)
    {
        $this->estado = $estado;
    }

    //METODOS GET
    public function get_id_parametria()
    {
        return $this->id_parametria;
    }

    public function get_fecha()
    {
        return $this->fecha;
    }

    public function get_fecha_expiracion()
    {
        return $this->fecha_expiracion;
    }

    public function get_fecha_validez()
    {
        return $this->fecha_validez;
    }

    public function get_valor()
    {
        return $this->valor;
    }

    public function get_codigo()
    {
        return $this->codigo;
    }

    public function get_estado()
    {
        return $this->estado;
    }

    public function jsonSerialize() 
    {
        return [
        'id_parametria' => $this->id_parametria,
        'fecha' => $this->fecha,
        'fecha_validez' => $this->fecha_validez,
        'fecha_expiracion' => $this->fecha_expiracion,
        'valor' => $this->valor,
        'codigo' => $this->codigo,
        'estado' => $this->estado
        ];
    }

    public function update($coneccion) 
    {
        $consulta = "update parametrias 
                        set valor = " . ((!is_null($this->get_valor())) ? "'" . $this->get_valor() . "'" : "null") . "
                        where codigo = '" . $this->get_codigo() . "'";
                        $mensaje_error_consulta = "No se pudo actualizar la Parametria";
                        if (!$Ret = mysqli_query($coneccion->Conexion, $consulta)) {
                            throw new Exception($mensaje_error_consulta . $consulta, 2);
                    }
    }
}
