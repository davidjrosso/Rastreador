<?php 
class Solicitud_ModificarMotivo{
//DECLARACION DE VARIABLES
private $coneccion_base;
private $ID;
private $Fecha;
private $Motivo;
private $Codigo;
private $Cod_Categoria;
private $Num_Motivo;
private $Estado;
private $ID_Usuario;
private $ID_Motivo;

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
                                $coneccion_base = null,
                                $xID = null,
                                $xFecha = null,
                                $xMotivo = null,
                                $xCodigo = null,
                                $xCod_Categoria = null,
                                $xNum_Motivo = null,
                                $xEstado = null,
                                $xID_Usuario = null,
                                $xID_Motivo = null
    ) {
        $this->coneccion_base = $coneccion_base;

        if (!$xID) {
            $this->Fecha = $xFecha;
            $this->Motivo = $xMotivo;
            $this->Codigo = $xCodigo;
            $this->Cod_Categoria = $xCod_Categoria;
            $this->Num_Motivo = $xNum_Motivo;
            $this->Estado = $xEstado;
            $this->ID_Usuario = $xID_Usuario;
            $this->ID_Motivo = $xID_Motivo;
        } else {
                $consultar = "select *
                            from solicitudes_modificarmotivos 
                            where ID = " . $xID . " 
                                and estado = 1";
                $ejecutar_consultar = mysqli_query(
                    $this->coneccion_base->Conexion,
                    $consultar) or die("Problemas al consultar filtro archivos");
                $ret = mysqli_fetch_assoc($ejecutar_consultar);
                if (!is_null($ret)) {
                    $row_id = $ret["ID"];
                    $row_fecha= $ret["Fecha"];
                    $row_estado = $ret["Estado"];
                    $row_Motivo = $ret["Motivo"];
                    $row_id_usuario = $ret["ID_Usuario"];
                    $row_Codigo = $ret["Codigo"];
                    $row_Cod_Categoria = $ret["Cod_Categoria"];
                    $row_Num_Motivo = $ret["Num_Motivo"];
                    
                    $this->estado = $row_estado;
                    $this->ID = $row_id;
                    $this->Motivo = $row_Motivo;
                    $this->Codigo = $row_Codigo;
                    $this->Cod_Categoria = $row_Cod_Categoria;
                    $this->Num_Motivo = $row_Num_Motivo;
                    $this->Fecha = $row_fecha;
                    $this->ID_Usuario = $row_id_usuario;
                }

        }
    }

    public function delete() 
    {
		$ConsultaSolicitud = "update solicitudes_modificarmotivos 
                              set estado = 0
                              where ID = " . $this->ID;
		if(!$Ret = mysqli_query($this->coneccion_base->Conexion,$ConsultaSolicitud)){
			throw new Exception("Problemas en la consulta. ", 3);			
		}
    }
}
?>