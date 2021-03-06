<?php

include_once("../Laboratorio/Conexion/ConexionBD.php");

//implementamos la clase lab_areas
class clsUsuarios {

    //constructor
    function clsUsuarios() {

    }

// validaci�n de existencia de usuario
    function validarexistencia($login, $password) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
        echo     $query = "SELECT count(*)
              FROM fos_user_user t01
              WHERE t01.username ='$login' AND t01.password = md5('$password') AND t01.modulo = 'LAB'";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else {
                $row = pg_fetch_array($result);
                return $row[0];
            }
        }
    }

    //devuelve el nivel del usuario logeado
    function obtenernivel($login, $password) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "select nivel from fos_user_user
               where username = '$login'
                    and password = md5('$password') and modulo='LAB'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else {
                $row = pg_fetch_array($result);
                return $row[0];
            }
        }
    }

    //devuelve el establecimiento del usuario logeado
    function obtenerlugar($login, $password) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t02.id_establecimiento
               FROM fos_user_user       t01
               INNER JOIN mnt_empleados t02 ON (t02.id = t01.id_empleado
               WHERE username = '$login' and password= md5('$password') and modulo='LAB'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else {
                $row = pg_fetch_array($result);
                return $row[0];
            }
        }
    }

    //devuelve el nivel del usuario logeado
    function obtenerarea($login, $password) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "select nivel
               from fos_user_user
               where username='$login' and password= md5('$password') and modulo='LAB'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else {
                $row = pg_fetch_array($result);
                return $row[0];
            }
        }
    }

    //devuelve los datos del usuario logeado
    function datosusuario($login, $password) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
           echo $query = "SELECT
                      t01.id AS correlativo,
                      t02.id_establecimiento,
                      t02.idarea,
                      t01.nivel,
                      t01.id_empleado,
                      replace (t03.nombre,'\"','') as nombre_establecimiento,
                      t04.id as id_tipo_establecimiento,
                    case when id_tipo_establecimiento in (1,30,31,14) then true
                         else  false
                         end as hospital
		FROM fos_user_user      t01
		INNER JOIN mnt_empleado t02 ON (t02.id = t01.id_empleado AND t01.id_establecimiento = t02.id_establecimiento)
        INNER JOIN ctl_establecimiento t03 on (t03.id=t02.id_establecimiento)
        INNER JOIN ctl_tipo_establecimiento t04 on (t04.id = t03.id_tipo_establecimiento)
		WHERE username='$login' AND password= md5('$password') AND modulo='LAB'";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else {
                return $result;
            }
        }
    }

}

//CLASE
?>
