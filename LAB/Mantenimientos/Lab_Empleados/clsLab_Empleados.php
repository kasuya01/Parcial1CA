<?php

include_once("../../../Conexion/ConexionBD.php");

class clsLab_Empleados {

    //constructor	
    function clsLab_Empleados() {
        
    }

    //INSERTA UN REGISTRO          
    function insertar($IdEmpleado, $lugar, $idarea, $nombrecompleto,$nombre, $cargo, $usuario, $corr, $IdEstabExt, $apellido) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "INSERT INTO mnt_empleado(idempleado, id_establecimiento, id_tipo_empleado, idarea, nombre,apellido,nombreempleado, id_cargo_empleado, correlativo, idusuarioreg, fechahorareg, id_establecimiento_externo) 
                            VALUES('$IdEmpleado',$lugar,(SELECT id FROM mnt_tipo_empleado WHERE codigo = 'LAB'),'$idarea', '$nombre', '$apellido', UPPER('$nombrecompleto'),'$cargo','$corr',$usuario,(SELECT date_trunc('seconds',(SELECT now()))),$IdEstabExt)";
            $result = @pg_query($query);
            
            if (!$result)
                return false;
            else
                return true;
        }
    }

    function Insertar_Usuario($login, $idempleado, $pass, $niv, $lugar, $modalidad) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "INSERT INTO fos_user_user(id,username, username_canonical, email, email_canonical, enabled, password, locked, expired, roles, credentials_expired, firstname, lastname, gender, id_establecimiento, id_empleado, modulo, nivel, id_modalidad_estab) 
                            VALUES((SELECT last_value FROM fos_user_user_id_seq)+1,'$login','$login','".$login."@salud.gob.sv','".$login."@salud.gob.sv',true,md5('$pass'),false,false,'a:0:{}',false,(SELECT nombre FROM mnt_empleado WHERE idempleado = '$idempleado'),(SELECT apellido FROM mnt_empleado WHERE idempleado = '$idempleado'),'u',$lugar,(SELECT id from mnt_empleado WHERE idempleado = '$idempleado'),'LAB',$niv,$modalidad);
                      SELECT SETVAL('fos_user_user_id_seq', (SELECT MAX(id) FROM fos_user_user), true);";
            $result = pg_query($query);
            if (!$result)
                return 0;
            else
                return 1;
        }
    }

    //ACTUALIZA UN REGISTRO
    function actualizar($IdEmpleado, $lugar, $idarea, $nombre,$apellido,$nombrecompleto, $cargo, $usuario) {
    //actualizar($idempleado,$idarea,$nomempleado,$cargo)
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "UPDATE mnt_empleado SET nombreempleado = UPPER('$nombrecompleto'), nombre='$nombre', apellido='$apellido', idarea = '$idarea', id_cargo_empleado = $cargo, idusuariomod ='$usuario', fechahoramod = (SELECT date_trunc('seconds',(SELECT now()))) WHERE idempleado = '$IdEmpleado' AND id_establecimiento = $lugar";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }

    //ACTUALIZA UN REGISTRO
    function actualizar_Usuario($idempleado, $login, $niv, $lugar, $modalidad) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "UPDATE fos_user_user SET nivel = $niv, id_modalidad_estab = $modalidad WHERE id_empleado = (SELECT id FROM mnt_empleado WHERE idempleado = '$idempleado') AND id_establecimiento = $lugar";
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }

    //CONSULTA LOS REGISTROS
    function consultar($lugar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t01.idempleado,
                            t01.idarea,
                            t01.nombreempleado,
                            t02.id AS idcargoempleado,
                            t02.cargo,
                            t01.id_establecimiento
                      FROM mnt_empleado                        t01
                      INNER JOIN mnt_cargoempleados            t02 ON (t02.id = t01.id_cargo_empleado)
                      INNER JOIN ctl_area_servicio_diagnostico t03 ON (t03.id = t01.idarea)
                      INNER JOIN mnt_tipo_empleado             t04 ON (t04.id = t01.id_tipo_empleado)
                      INNER JOIN ctl_atencion                  t05 ON (t05.id = t03.id_atencion AND t05.codigo_busqueda = 'DCOLAB')
                      WHERE t04.codigo = 'LAB' AND t01.id_establecimiento = $lugar
                      ORDER BY t01.idarea, t01.idempleado";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //CONSULTA EXAMEN POR EL CODIGO
    function consultarid($IdEmpleado, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT t01.idempleado, 
                            t01.idarea,
                            t01.nombre,
                            t01.apellido,
                            t03.nombrearea,
                            t01.nombreempleado,
                            t02.id AS idcargoempleado,
                            t02.cargo,
                            t04.username AS login,
                            COALESCE(t04.id_modalidad_estab, 0) AS idmodalidad,
                            COALESCE(t07.nombre, '') AS nombremodalidad
                     FROM mnt_empleado                             t01
                     INNER JOIN mnt_cargoempleados                 t02 ON (t02.id = t01.id_cargo_empleado)
                     INNER JOIN ctl_area_servicio_diagnostico      t03 ON (t03.id = t01.idarea)
                     INNER JOIN fos_user_user                      t04 ON (t01.id = t04.id_empleado AND t01.id_establecimiento = t04.id_establecimiento)
                     INNER JOIN ctl_atencion                       t05 ON (t05.id = t03.id_atencion AND t05.codigo_busqueda = 'DCOLAB')
                     LEFT OUTER JOIN mnt_modalidad_establecimiento t06 ON (t06.id = t04.id_modalidad_estab)
                     LEFT OUTER JOIN ctl_modalidad                 t07 ON (t07.id = t06.id_modalidad)
                     WHERE t01.idempleado = '$IdEmpleado' AND t01.id_establecimiento = $lugar";
            // echo $query;
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA LEER EMPLEADOS
    function LeerEmpleados($lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {

            $query = "SELECT t01.idempleado,
                             t01.nombreempleado 
                      FROM mnt_empleado t01
                      INNER JOIN mnt_tipo_empleado  t02 ON (t02.id = t01.id_tipo_empleado)
                      WHERE t02.codigo ='LAB' AND t01.id_establecimiento = $lugar";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA LEER CARGOS
    function LeerCargos() {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT t01.id AS idcargoempleado,
                             t01.cargo 
                      FROM mnt_cargoempleados t01
                      INNER JOIN ctl_atencion t02 ON (t02.id = t01.id_atencion)
                      WHERE t02.codigo_busqueda = 'DCOLAB'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA LEER EL ULTIMO CODIGO INSERTADO
    function LeerUltimoCodigo($lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT (t01.correlativo + 1) AS ultimo 
                      FROM mnt_empleado t01
                      INNER JOIN mnt_tipo_empleado t02 ON (t02.id = t01.id_tipo_empleado)
                      WHERE t02.codigo = 'LAB' AND t01.id_establecimiento = $lugar
                      ORDER BY correlativo DESC LIMIT 1";
            $result = @pg_query($query);

            if (!$result)
                return false;
            else
                $row = pg_fetch_array($result);
            return $row[0];
        }
    }

    function BuscarEmpleado($IdEmpleado, $lugar) {
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT COUNT(*)
                      FROM mnt_empleado
                      WHERE idempleado = '$IdEmpleado' AND id_establecimiento = $lugar";
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    /*****************************FUNCIONES PARA MANEJO DE PAGINACION**************************** */

    //consultando el numero de registros de la tabla
    function NumeroDeRegistros($lugar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT * 
                      FROM mnt_empleado            t01
                      INNER JOIN mnt_tipo_empleado t02 ON (t02.id = t01.id_tipo_empleado)
                      WHERE t02.codigo = 'LAB' AND correlativo != 0 AND id_establecimiento = $lugar";
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

    function consultarpag($lugar, $RegistrosAEmpezar, $RegistrosAMostrar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t01.idempleado,
                             t03.idarea,
                             t03.nombrearea,
                             t01.nombreempleado,
                             t02.id AS idcargoempleado,
                             t02.cargo,
                             t01.id_establecimiento AS idestablecimiento,
                             CASE WHEN t04.enabled = true THEN 'Habilitado' ELSE 'Inhabilitado' END AS habilitado, 
                             CASE WHEN t04.enabled = true THEN 'H' ELSE 'I' END AS estadocuenta,
                             t04.username AS login,
                             COALESCE(t04.id_modalidad_estab, 0) AS id_modalidad_estab,
                             COALESCE(t08.nombre, '') AS nombre_modalidad
                      FROM mnt_empleado                             t01
                      INNER JOIN mnt_cargoempleados                 t02 ON (t02.id = t01.id_cargo_empleado)
                      INNER JOIN ctl_area_servicio_diagnostico      t03 ON (t03.id = t01.idarea)
                      INNER JOIN fos_user_user                      t04 ON (t01.id = t04.id_empleado AND t01.id_establecimiento = t04.id_establecimiento)
                      INNER JOIN mnt_tipo_empleado                  t05 ON (t05.id = t01.id_tipo_empleado)
                      INNER JOIN ctl_atencion                       t06 ON (t06.id = t03.id_atencion AND t06.codigo_busqueda = 'DCOLAB')
                      LEFT OUTER JOIN mnt_modalidad_establecimiento t07 ON (t07.id = t04.id_modalidad_estab)
                      LEFT OUTER JOIN ctl_modalidad                 t08 ON (t08.id = t07.id_modalidad)
                      WHERE t05.codigo = 'LAB' AND t01.id_establecimiento = $lugar
                      ORDER BY t01.idempleado LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
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
    //***************************FIN FUNCIONES PARA MANEJO DE PAGINACION***************************/

    function consultarModalidad($idEstablecimiento) {
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t01.id,
                             t02.nombre
                      FROM mnt_modalidad_establecimiento t01
                      INNER JOIN ctl_modalidad t02 ON (t02.id = t01.id_modalidad)
                      WHERE t01.id_establecimiento = $idEstablecimiento";
            
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }
}

//CLASE
class Estado {

    function EstadoCuenta($IdEmpleado, $Estado, $lugar) {
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            if ($Estado == 'H') {
                $query = "UPDATE fos_user_user SET enabled = false WHERE id_empleado = (SELECT id FROM mnt_empleado WHERE idempleado = '$IdEmpleado') AND id_establecimiento = $lugar";
                $result = pg_query($query);
            }
            if ($Estado == 'I') {
                $query = "UPDATE fos_user_user SET enabled = true WHERE id_empleado = (SELECT id FROM mnt_empleado WHERE idempleado = '$IdEmpleado') AND id_establecimiento = $lugar";
                $result = pg_query($query);
            }
        }
        $con->desconectar();
    }
}
//CLASE
?>
