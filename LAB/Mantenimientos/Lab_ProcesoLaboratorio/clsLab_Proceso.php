<?php

include_once("../../../Conexion/ConexionBD.php");

//implementamos la clase lab_areas
class clsLab_Proceso {

    var $ultimoregistroinsertado_lab_areas = 0;

    //constructor
    function clsLab_Proceso() {

    }

    public function getLabProceso() {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
         $query = "select t01.id as idprocest, t01.activo as active, proceso, t02.id as idproclab
            from lab_proceso_establecimiento t01
            join lab_proceso_laboratorio t02 on (t02.id=t01.id_proceso_laboratorio);";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    public function updateProcLabEstablecimiento($idEstablecimiento, $proclab) {
        $con = new ConexionBD;
        $proclab = implode(",",$proclab);

        if ($con->conectar() == true) {
            if($proclab !== "") {
                $query = "update lab_proceso_establecimiento 
set activo=true
where id_proceso_laboratorio in ($proclab)
and id_establecimiento=$idEstablecimiento;"
                        . "update lab_proceso_establecimiento 
set activo=false
where id_proceso_laboratorio not in ($proclab)
and id_establecimiento=$idEstablecimiento;";
            } else {
                $query = "update lab_proceso_establecimiento 
set activo=false
where id_establecimiento=$idEstablecimiento;";
            }

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

}
//CLASE
?>
