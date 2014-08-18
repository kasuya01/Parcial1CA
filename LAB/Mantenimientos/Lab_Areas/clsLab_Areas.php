<?php

include_once("../../../Conexion/ConexionBD.php");

//implementamos la clase lab_areas
class clsLab_Areas {

    var $ultimoregistroinsertado_lab_areas = 0;

    //constructor	
    function clsLab_Areas() {
    }

    /* Función para ingresar un área */

    function insertar($idarea, $nombrearea, $usuario, $tipo, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "INSERT INTO ctl_area_servicio_apoyo(idarea,nombrearea,idusuarioreg,fechahorareg,idusuariomod,fechahoramod, administrativa,id_atencion) 
			VALUES('$idarea','$nombrearea','$usuario', (SELECT date_trunc('seconds',(SELECT now()))),'$usuario', (SELECT date_trunc('seconds',(SELECT now()))), '$tipo', (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))";

            $result = @pg_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    function recuperarultimoreg() {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT id FROM lab_areas ORDER BY id DESC LIMIT 1;";

            $result = @pg_query($query);
            $row = pg_fetch_array($result);
            $this->ultimoregistroinsertado_lab_areas = $row['id'];


            if (!$result)
                return false;
            else
                return true;
        }
    }

//
    /* Función para ingresar un área asociada a un establecimiento */
    function ingresarareaxestablecimiento($idarea, $cond, $lugar, $usuario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "INSERT INTO lab_areasxestablecimiento(idarea,idestablecimiento,condicion,idusuarioreg,idusuariomod,fechahorareg,fechahoramod)"
                    . "         VALUES($idarea,$lugar,'$cond','$usuario','$usuario',(SELECT date_trunc('seconds',(SELECT now()))), (SELECT date_trunc('seconds',(SELECT now()))))";
            $result = @pg_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    /* Función para actualizar datos de una área */
    function actualizar($idarea, $nom, $usuario, $tipo) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {

            $query = "UPDATE lab_areas SET nombrearea = '$nom', idusuariomod = $usuario, fechahoramod=(SELECT date_trunc('seconds',(SELECT now()))), administrativa='$tipo' WHERE idarea = '$idarea'";

            $result = @pg_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    /* Función para actualizar datos de una área asociadad a un establecimiento */

    function ActualizarxEstablercimiento($idarea, $cond, $lugar, $usuario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {

            $query = "UPDATE lab_areasxestablecimiento SET condicion='$cond',idestablecimiento=$lugar,idusuariomod=$usuario,fechahoramod=(SELECT date_trunc('seconds',(SELECT now()))) WHERE idarea = (SELECT id FROM lab_areas WHERE idarea = '$idarea')";
            $result = @pg_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    /* Función para eliminar un área asociada a un establecimiento */

    function EliminarxEstablecimiento($idarea) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "DELETE FROM lab_areasxestablecimiento WHERE idarea = (SELECT id FROM lab_areas WHERE idarea = '$idarea')";
            $result = @pg_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    /* Eliminar un área */

    function eliminar($idarea) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "DELETE FROM lab_areas WHERE idarea = '$idarea'";
            $result = @pg_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    // consulta las areas de la BD
    function consultar($lugar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t01.idarea,
                             t01.nombrearea,
                             t02.condicion,
                             t01.id
                      FROM lab_areas t01
                      INNER JOIN lab_areasxestablecimiento t02 ON (t01.id = t02.idarea)
                      WHERE t02.condicion='H' AND t02.idestablecimiento = $lugar
                      ORDER BY nombrearea";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }
    //Fn Pg.. consultada por MntTipoMuestrasPorExamen.php
    // consulta las areas de la BD
    function consultaractivas($lugar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t01.id AS idarea, t01.nombrearea
                           FROM lab_areas t01
                           INNER JOIN lab_areasxestablecimiento t02 ON (t01.id = t02.idarea)
                           WHERE t02.condicion = 'H' AND t01.administrativa = 'N' 
                            AND t02.idestablecimiento = $lugar 
                           ORDER BY nombrearea";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    // consulta las areas de la BD
    function consultarareas($lugar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT * FROM lab_areas t01
                      INNER JOIN lab_areasxestablecimiento t02 ON (t01.id = t02.idarea)
                      WHERE t02.idestablecimiento = $lugar AND condicion = 'H'
                      ORDER BY nombrearea";
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

//FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
    function VerificarIntegridad($idarea) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT * FROM lab_examenes WHERE idarea = (SELECT id FROM lab_areas WHERE idarea = '$idarea')";
            $result = @pg_query($query);
            $cuenta = pg_num_rows($result);

            if ($cuenta > 0)
                return true;
            else
                return false;
        }
    }

//************************************************FUNCIONES PARA MANEJO DE PAGINACION***************************************************/
    //consultando el numero de registros de la tabla
    function NumeroDeRegistros($lugar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t01.idarea,
                             t01.nombrearea
                      FROM lab_areas t01
                      INNER JOIN lab_areasxestablecimiento t02 ON (t01.id = t02.idarea)
                      WHERE t02.idestablecimiento = $lugar";
            $numreg = pg_num_rows(pg_query($query));
            if (!$numreg)
                return false;
            else
                return $numreg;
        }
    }

    function consultarpag($lugar, $RegistrosAEmpezar, $RegistrosAMostrar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t01.idarea,
                             t01.nombrearea, 
                             t02.condicion,
                             t01.administrativa
                      FROM lab_areas t01
                      INNER JOIN lab_areasxestablecimiento t02 ON (t01.id = t02.idarea)
                      WHERE t02.idestablecimiento = $lugar
                      ORDER BY T01.id LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

//******************************FIN FUNCIONES PARA MANEJO DE PAGINACION****************************/
    // consulta empleado por su codigo
    function consultarid($idarea, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT t01.idarea, 
                             t01.nombrearea,
                             t01.administrativa,
                             t02.condicion,
                             t02.idestablecimiento
                      FROM lab_areas t01
                      INNER JOIN lab_areasxestablecimiento t02 ON (t01.id = t02.idarea)
                      WHERE t01.idarea = '$idarea'AND t02.idestablecimiento = $lugar";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function consultarpagbus($query, $RegistrosAEmpezar, $RegistrosAMostrar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = $query . " LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function NumeroDeRegistrosbus($query) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $numreg = pg_num_rows(pg_query($query));
            if (!$numreg)
                return false;
            else
                return $numreg;
        }
    }

}
//CLASE
?>
