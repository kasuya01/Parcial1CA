<?php

include_once("../../../Conexion/ConexionBD.php");

class clsLab_IndicacionesPorExamen {

    //CONSTRUCTOR
    function clsLab_IndicacionesPorExamen() {
        
    }

    //INSERTA UN REGISTRO          
    function insertar($idexamen, $idarea, $indicacion, $usuario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "INSERT INTO mnt_indicacionesporexamen
		     (id_conf_examen_estab,indicacion,idservicio,idusuarioreg,fechahorareg,idusuariomod,fechahoramod) 
		      VALUES('$idexamen','$indicacion','98',$usuario,NOW(),$usuario,NOW())";
            $result = @pg_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    //ACTUALIZA UN REGISTRO
    function actualizar($idexamen, $idarea, $indicacion, $idindicacion, $usuario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
           $query = "UPDATE mnt_indicacionesporexamen SET indicacion='$indicacion', id_conf_examen_estab='$idexamen', 
		        idusuariomod= '$usuario',idservicio='98', fechahoramod=NOW() 
		       WHERE id=$idindicacion";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }

    //ELIMINA UN REGISTRO
    function eliminar($idindicacion) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "DELETE FROM mnt_indicacionesporexamen WHERE IdIndicacionPorExamen=$idindicacion";
            $result = @pg_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    //CONSULTA LOS REGISTROS
    function consultar() {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT id_conf_examen_estab,indicacion FROM mnt_indicacionesporexamen
		       WHERE idservicio=3
		       ORDER BY id_conf_examen_estab";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //CONSULTA EXAMEN POR EL CODIGO
    function consultarid_indicacion($idindicacion) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "select casd.id,casd.nombrearea,lcee.id,lcee.nombre_examen,mipe.indicacion		
                        from ctl_area_servicio_diagnostico casd
			join mnt_area_examen_establecimiento mnt4 on   mnt4.id_area_servicio_diagnostico=casd.id
			join lab_conf_examen_estab lcee on (mnt4.id=lcee.idexamen) 
			join mnt_indicacionesporexamen mipe on (mipe.id_conf_examen_estab=lcee.id) 
                        where mipe.id=$idindicacion
                        ORDER BY mnt4.id";


            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //RECUPERAR EXAMENES POR AREA
    function ExamenesPorArea($idarea) {
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT lcee.id,lcee.nombre_examen 
                    FROM mnt_area_examen_establecimiento maees
                    INNER JOIN lab_conf_examen_estab lcee ON maees.id= lcee.idexamen 
                    WHERE maees.id_area_servicio_diagnostico=$idarea
                    AND lcee.condicion='H' 
                    ORDER BY lcee.idexamen";
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

//*************************************************FUNCIONES PARA MANEJO DE PAGINACION******************************************************/
    //consultando el numero de registros de la tabla
    function NumeroDeRegistros() {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
           $query = "SELECT * FROM mnt_indicacionesporexamen
                     INNER JOIN lab_conf_examen_estab 
                     ON lab_conf_examen_estab.id=mnt_indicacionesporexamen.id_conf_examen_estab
                     WHERE lab_conf_examen_estab.condicion='H'";
            $numreg = pg_num_rows(pg_query($query));
            if (!$numreg)
                return false;
            else
                return $numreg;
        }
    }

    function NumeroDeRegistrosbus($query_search) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = $query_search;
            $numreg = pg_num_rows(pg_query($query));
            if (!$numreg)
                return false;
            else
                return $numreg;
        }
    }

    function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
           $query = "SELECT mipe.id,
                                casd.id,
                                casd.nombrearea,
                                lcee.id,
                                lcee.nombre_examen,
                                mipe.indicacion 
                        FROM ctl_area_servicio_diagnostico casd 
                        JOIN mnt_area_examen_establecimiento    mnt4   ON (mnt4.id_area_servicio_diagnostico=casd.id)
                        JOIN lab_conf_examen_estab              lcee   ON (mnt4.id=lcee.idexamen) 
                        JOIN mnt_indicacionesporexamen          mipe   ON (mipe.id_conf_examen_estab=lcee.id) 
                        WHERE lcee.condicion='H'
                        ORDER BY casd.nombrearea, lcee.nombre_examen 
                        LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";

            /*  "SELECT id,idservicio,id_conf_examen_estab,indicacion FROM mnt_indicacionesporexamen LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar "; */
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function consultarpagbus($query_search, $RegistrosAEmpezar, $RegistrosAMostrar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = $query_search . " LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

//***************************************FIN FUNCIONES PARA MANEJO DE PAGINACION***********************************/
}

//CLASE
?>
