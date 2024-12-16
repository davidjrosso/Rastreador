<?php  
class Parametria implements JsonSerializable {
    //DECLARACION DE VARIABLES
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
                                $estado=null
                                )
    {
        $this->id_parametria = $id_parametria;
        $this->fecha = $fecha;
        $this->fecha_validez = $fecha_validez;
        $this->fecha_expiracion = $fecha_expiracion;
        $this->valor = $valor;
        $this->codigo = $codigo;
        $this->estado = $estado;
    }

    public static function get_value_by_code($coneccion, $code)
    {
        $consulta = "select *
                     from parametrias 
                     where lower(codigo) = " . strtolower($code) . " 
                     and estado = 1";
        $ejecutar_consulta = mysqli_query(
            $coneccion->Conexion, 
            $consulta) or die("Problemas al consultar filtro Usuario");
        $ret = mysqli_fetch_assoc($ejecutar_consulta);
        $valor = (!is_null($ret["valor"])) ? $ret["valor"] : null;
        return $valor; 
    }

    //METODOS SET
    public function set_id_parametria($id_parametria){
        $this->id_parametria = $id_parametria;
    }

    public function set_fecha($fecha){
        $this->fecha = $fecha;
    }

    public function set_fecha_validez($fecha_validez){
        $this->fecha_validez = $fecha_validez;
    }

    public function set_fecha_expiracion($fecha_expiracion){
        $this->fecha_expiracion = $fecha_expiracion;
    }

    public function set_valor($valor){
        $this->valor = $valor;
    }
    public function set_codigo($codigo){
        $this->codigo = $codigo;
    }

    public function set_estado($estado){
        $this->estado = $estado;
    }

    //METODOS GET
    public function get_id_parametria(){
        return $this->id_parametria;
    }

    public function get_fecha(){
        return $this->fecha;
    }

    public function get_fecha_expiracion(){
        return $this->fecha_expiracion;
    }

    public function get_fecha_validez(){
        return $this->fecha_validez;
    }

    public function get_valor(){
        return $this->valor;
    }

    public function get_codigo(){
        return $this->codigo;
    }

    public function get_estado(){
        return $this->estado;
    }

    public function jsonSerialize() {
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
}
