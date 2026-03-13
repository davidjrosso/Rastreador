<?php
class OtraInstitucion{
    //DECLARACION DE VARIABLES
    private $ID_OtraInstitucion;
    private $Nombre;
    private $Telefono;
    private $Mail;
    private $Estado;
    private $coneccion;

    //METODO CONSTRUCTOR
    public function __construct(
                                $xConeccion = null,
                                $xID_OtraInstitucion = null,
                                $xNombre = null,
                                $xTelefono = null,
                                $xMail = null,
                                $xEstado = null
    ) {
        $this->coneccion = $xConeccion;
        if ($xID_OtraInstitucion) {
            $consulta = "select * 
                        from otras_instituciones 
                        where ID_OtraInstitucion = $xID_OtraInstitucion 
                        and estado = 1";
            $mensaje_error = "Hubo un problema al consultar los registros";
            $ret = mysqli_query(
                        $this->coneccion->Conexion,
                        $consulta
            ) or die(
                $mensaje_error
            );
            $row = mysqli_fetch_assoc($ret);
            $this->ID_OtraInstitucion = (!isset($xID_OtraInstitucion)) ? $row['ID_OtraInstitucion'] : $xID_OtraInstitucion;
            $this->Nombre = (!isset($xNombre)) ? $row['Nombre'] : $xNombre;
            $this->Telefono = (!isset($xTelefono)) ? $row['Telefono'] : $xTelefono;
            $this->Mail = (!isset($xMail)) ? $row['Mail'] : $xMail;
            $this->Estado = (!isset($xEstado)) ? $row['Estado'] : $xID_OtraInstitucion;
        } else {
            $this->ID_OtraInstitucion = $xID_OtraInstitucion;
            $this->Nombre = $xNombre;
            $this->Telefono = $xTelefono;
            $this->Mail = $xMail;
            $this->Estado = $xEstado;
        }
    }
    
    public static function get_id_by_name($coneccion, $name)
    {
        $consulta = "select * 
                     from otras_instituciones 
                     where lower(Nombre) like lower('%$name%') 
                       and estado = 1";
        $mensaje_error = "Hubo un problema al consultar los registros";
        $ret = mysqli_query(
                    $coneccion->Conexion,
                    $consulta
        ) or die(
            $mensaje_error
        );
        $row = mysqli_fetch_assoc($ret);
        $id = (empty($row["ID_OtraInstitucion"])) ? 0 : $row["ID_OtraInstitucion"];
        return $id;
    }

    //METODOS SET
    public function setID_OtraInstitucion($xID_OtraInstitucion)
    {
        $this->ID_OtraInstitucion = $xID_OtraInstitucion;
    }

    public function setNombre($xNombre)
    {
        $this->Nombre = $xNombre;
    }

    public function setTelefono($xTelefono)
    {
        $this->Telefono = $xTelefono;
    }

    public function setMail($xMail)
    {
        $this->Mail = $xMail;
    }

    public function setEstado($xEstado)
    {
        $this->Estado = $xEstado;
    }

    //METODOS GET
    public function getID_OtraInstitucion()
    {
        return $this->ID_OtraInstitucion;
    }

    public function getNombre()
    {
        return $this->Nombre;
    }

    public function getTelefono()
    {
        return $this->Telefono;
    }

    public function getMail()
    {
        return $this->Mail;
    }

    public function getEstado()
    {
        return $this->Estado;
    }

    public function save()
    {
        $consulta = "INSERT INTO otras_instituciones (
                                        Nombre, 
                                        Telefono, 
                                        Mail, 
                                        Estado 
                    )
                    VALUES ( " . ((!is_null($this->getNombre())) ? "'" . $this->getNombre() . "'" : "null") . ", 
                            " . ((!is_null($this->getTelefono())) ? "'" . $this->getTelefono() . "'" : "null") . ", 
                            " . ((!is_null($this->getMail())) ? "'" . $this->getTelefono() . "'" : "null") . ", 
                            " . ((!is_null($this->getEstado())) ? $this->getEstado() : "null") . " 
                    )";
                    $MensajeErrorConsultar = "No se pudo insertar la institucion";
                    $ret = mysqli_query($this->coneccion->Conexion, $consulta);
                    if (!$ret) {
                        throw new Exception($MensajeErrorConsultar . $consulta, 2);
                    }
                    $this->ID_OtraInstitucion = mysqli_insert_id($this->coneccion->Conexion);

    }
    
    public function update()
    {
        $Consulta = "update otras_instituciones 
                    set Nombre = " . ((!is_null($this->getNombre())) ? "'" . $this->getNombre() . "'" : "null") . ", 
                        Telefono = " . ((!is_null($this->getTelefono())) ? "'" . $this->getTelefono() . "'" : "null") . ", 
                        Mail = " . ((!is_null($this->getMail())) ? "'" . $this->getMail() . "'" : "null") . ", 
                        Estado = " . ((!is_null($this->getEstado())) ? $this->getEstado() : "null") . " 
                    where ID_OtraInstitucion = " . $this->getID_OtraInstitucion();
                    $MensajeErrorConsultar = "No se pudo actualizar la otra institucion";
                    if (!$Ret = mysqli_query($this->coneccion->Conexion, $Consulta)) {
                        throw new Exception($MensajeErrorConsultar . $Consulta, 2);
                    }
        
    }

    public function delete()
    {
        $Consulta = "update otras_instituciones 
                    set Estado = 0 
                    where ID_OtraInstitucion = " . $this->getID_OtraInstitucion();
                    $MensajeErrorConsultar = "No se pudo delete  otra institucion";
                    if (!$Ret = mysqli_query($this->coneccion->Conexion, $Consulta)) {
                        throw new Exception($MensajeErrorConsultar . $Consulta, 2);
                    }
        
    }

}
