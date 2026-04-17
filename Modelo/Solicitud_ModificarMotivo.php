<?php 
class Solicitud_ModificarMotivo
{
//DECLARACION DE VARIABLES
    private $ID;
    private $Fecha;
    private $Motivo;
    private $Codigo;
    private $Cod_Categoria;
    private $Num_Motivo;
    private $Estado;
    private $ID_Usuario;
    private $ID_Motivo;
    private $coneccion;
    //METODOS SET
    public function setID($xID){
        $this->ID = $xID;
    }

    public function setFecha($xFecha){
        $this->Fecha = $xFecha;
    }

    public function setMotivo($xMotivo){
        $this->Motivo = $xMotivo;
    }

    public function setCodigo($xCodigo){
        $this->Codigo = $xCodigo;
    }

    public function setCod_Categoria($xCod_Categoria){
        $this->Cod_Categoria = $xCod_Categoria;
    }

    public function setNum_Motivo($xNum_Motivo){
        $this->Num_Motivo = $xNum_Motivo;
    }

    public function setEstado($xEstado){
        $this->Estado = $xEstado;
    }

    public function setID_Usuario($xID_Usuario){
        $this->ID_Usuario = $xID_Usuario;
    }

    public function setID_Motivo($xID_Motivo){
        $this->ID_Motivo = $xID_Motivo;
    }

    //METODOS GET
    public function getID(){
        return $this->ID;
    }

    public function getFecha(){
        return $this->Fecha;
    }

    public function getMotivo(){
        return $this->Motivo;
    }

    public function getCodigo(){
        return $this->Codigo;
    }

    public function getCod_Categoria(){
        return $this->Cod_Categoria;
    }

    public function getNum_Motivo(){
        return $this->Num_Motivo;
    }

    public function getEstado(){
        return $this->Estado;
    }

    public function getID_Usuario(){
        return $this->ID_Usuario;
    }

    public function getID_Motivo(){
        return $this->ID_Motivo;
    }

    //METODO CONSTRUCTOR
    public function __construct(
                $xID = null,
                $xFecha = null,
                $xMotivo = null,
                $xCodigo = null,
                $xCod_Categoria = null,
                $xNum_Motivo = null,
                $xEstado = null,
                $xID_Usuario = null,
                $xID_Motivo = null,
                $xConeccion = null
    ) {

        $this->coneccion = $xConeccion;
        if (empty($xID)) {
            $this->Fecha = $xFecha;
            $this->Motivo = $xMotivo;
            $this->Codigo = $xCodigo;
            $this->Cod_Categoria = $xCod_Categoria;
            $this->Num_Motivo = $xNum_Motivo;
            $this->Estado = $xEstado;
            $this->ID_Usuario = $xID_Usuario;
            $this->ID_Motivo = $xID_Motivo;
        } else {
            $query = "SELECT *
                      FROM solicitudes_modificarmotivos
                      WHERE ID = " . $this->getID();
            if(!$RetCod = mysqli_query($this->coneccion->Conexion, $query)){
                throw new Exception("Problemas al consultar cod_categoria. Consulta: " . $query, 1);			
            }
            $result = mysqli_fetch_array($RetCod);
            $this->ID = (!empty($xID)) ? $xID : $result["ID"];
            $this->Fecha = (!empty($xFecha)) ? $xFecha : $result["Fecha"];
            $this->Motivo = (!empty($xMotivo)) ? $xMotivo : $result["Motivo"];
            $this->Codigo = (!empty($xCodigo)) ? $xCodigo : $result["Codigo"];
            $this->Cod_Categoria = (!empty($xCod_Categoria)) ? $xCod_Categoria : $result["Cod_Categoria"];
            $this->Num_Motivo = (!empty($xNum_Motivo)) ? $xNum_Motivo : $result["Num_Motivo"];
            $this->Estado = (!empty($xEstado)) ? $xEstado : $result["Estado"];
            $this->ID_Usuario = (!empty($xID_Usuario)) ? $xID_Usuario : $result["ID_Usuario"];
            $this->ID_Motivo = (!empty($xID_Motivo)) ? $xID_Motivo : $result["ID_Motivo"];

        }        

    }

    public function save()
    {
        $consulta = "insert into solicitudes_modificarmotivos(
                                                            Fecha,
                                                            Codigo, 
                                                            Motivo,
                                                            Cod_Categoria, 
                                                            Num_Motivo, 
                                                            Estado, 
                                                            ID_Usuario, 
                                                            ID_Motivo
                                                           ) values (
                                                                      " . (($this->getFecha()) ? "'" . $this->getFecha() . "'" : "null") . ",
                                                                       " . (($this->getCodigo()) ? "'" . $this->getCodigo() . "'" : "null") . ",
                                                                       " . (($this->getMotivo()) ? "'" . $this->getMotivo() . "'" : "null") . ",
                                                                       " . (($this->getCod_Categoria()) ? "'" . $this->getCod_Categoria() . "'" : "null") .  ",
                                                                       " . (($this->getNum_Motivo()) ?  "'" . $this->getNum_Motivo() . "'" : "null") . ",
                                                                       " . (($this->getEstado()) ? $this->getEstado() :  "null")  . ",
                                                                       " . (($this->getID_Usuario()) ? $this->getID_Usuario() :  "null")  . ",
                                                                       " . (($this->getID_Motivo()) ? $this->getID_Motivo() : "null") . "
                                                                       )";
        $MensajeError = "No se pudo enviar la solicitud";
        mysqli_query($this->coneccion->Conexion, $consulta) or die($MensajeError);
		$this->ID = mysqli_insert_id($this->coneccion->Conexion);
    }
    

    public function delete()
    {
        $query = "delete solicitudes_modificarmotivos
                  where ID = " . $this->getID();

        if(!$RetCod = mysqli_query($this->coneccion->Conexion, $query)){
            throw new Exception("Problemas al consultar cod_categoria. Consulta: " . $query, 1);			
        }
    
    }
     

}
?>