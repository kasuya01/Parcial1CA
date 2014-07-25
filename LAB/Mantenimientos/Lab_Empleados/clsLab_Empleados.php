<?php

include_once("../../../Conexion/ConexionBD.php");

class clsLab_Empleados {

    //constructor	
    function clsLab_Empleados() {
        
    }

//INSERTA UN REGISTRO          
    function insertar($IdEmpleado, $lugar, $idarea, $nomempleado, $cargo, $usuario, $corr, $IdEstabExt) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {//ME QUEDE PENDIENTE
            $query = "INSERT INTO mnt_empleado(idempleado,id_establecimiento,id_tipo_empleado,idarea,nombreempleado,id_cargo_empleado,
        correlativo,idusuarioreg,fechahorareg,IdEstablecimientoExt) 
        VALUES('$IdEmpleado',$lugar,'LAB','$idarea',UCASE('$nomempleado'),'$cargo','$corr',$usuario,NOW(),$usuario,NOW(),$IdEstabExt)";
            $result = @pg_query($query);
            //  echo $query;
            if (!$result)
                return false;
            else
                return true;
        }
    }

    function Insertar_Usuario($login, $idempleado, $pass, $niv, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "INSERT INTO mnt_usuarios(login,password,nivel,modulo,Grupo,IdEmpleado,EstadoCuenta,IdSubServicio,IdEstablecimiento) 
VALUES('$login',md5('$pass'),$niv,'LAB',0,'$idempleado','H',0,$lugar)";
            $result = pg_query($query);
            if (!$result)
                return 0;
            else
                return 1;
        }
    }

    //ACTUALIZA UN REGISTRO
    function actualizar($IdEmpleado, $lugar, $idarea, $nomempleado, $cargo, $usuario) {
    //actualizar($idempleado,$idarea,$nomempleado,$cargo)
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "UPDATE mnt_empleados SET NombreEmpleado=UCASE('$nomempleado'), idarea='$idarea', idcargoempleado=$cargo, IdUsuarioMod ='$usuario',FechaHoraMod=NOW() WHERE IdEmpleado='$IdEmpleado' AND IdEstablecimiento=$lugar";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }

    //ACTUALIZA UN REGISTRO
    function actualizar_Usuario($idempleado, $login, $niv, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "UPDATE mnt_usuarios SET login='$login',nivel=$niv WHERE IdEmpleado='$idempleado' AND IdEstablecimiento=$lugar";
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
                      FROM mnt_empleado             t01
                      INNER JOIN mnt_cargoempleados t02 ON (t02.id = t01.id_cargo_empleado)
                      INNER JOIN lab_areas          t03 ON (t03.id = t01.idarea)
                      INNER JOIN mnt_tipo_empleado  t04 ON (t04.id = t01.id_tipo_empleado)
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
                            t03.nombrearea,
                            t01.nombre,
                            t02.id AS idcargoempleado,
                            t02.cargo,
                            t04.username AS login
                     FROM mnt_empleado             t01
                     INNER JOIN mnt_cargoempleados t02 ON (t02.id = t01.id_cargo_empleado)
                     INNER JOIN lab_areas          t03 ON (t03.id = t01.idarea
                     INNER JOIN fos_user_user      t04 ON (t01.id = t04.id_empleado AND t01.id_establecimiento = t04.id_establecimiento)
                     WHERE t01.idempleado = $IdEmpleado AND t01.id_establecimiento = $lugar";
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
                      FROM mnt_empleadot 01
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
            // $query = "select (ultimoregistro + 1) AS ultimoregistro from  mnt_codigosgenerados WHERE idcodigogenerado='EMPLAB'";
            $query = "SELECT (Correlativo+1) AS ultimo FROM mnt_empleados where IdTipoEmpleado='LAB' AND IdEstablecimiento=$lugar  ORDER BY Correlativo DESC LIMIT 1";
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
            $query = "SELECT count(*) FROM mnt_empleados WHERE IdEmpleado='$IdEmpleado' 
                 AND IdEstablecimiento=$lugar";
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    /*     * ***************************FUNCIONES PARA MANEJO DE PAGINACION**************************** */

    //consultando el numero de registros de la tabla
    function NumeroDeRegistros($lugar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT * 
                      FROM mnt_empleado            t01
                      INNER JOIN mnt_tipo_empleado t02 ON (t02.id = t01.id_tipo_empleado)
                      WHERE t02.cargo = 'LAB' AND correlativo != 0 AND id_establecimiento = $lugar";
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
                             t04.username AS login
                      FROM mnt_empleado             t01
                      INNER JOIN mnt_cargoempleados t02 ON (t02.id = t01.id_cargo_empleado)
                      INNER JOIN lab_areas          t03 ON (t03.id = t01.idarea)
                      INNER JOIN fos_user_user      t04 ON (t01.id = t04.id_empleado AND t01.id_establecimiento = t04.id_establecimiento)
                      INNER JOIN mnt_tipo_empleado  t05 ON (t05.id = t01.id_tipo_empleado)
                      WHERE t05.codigo = 'LAB' AND t01.id_establecimiento = $lugar
                      ORDER BY t01.idempleado LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";

            //echo $query;
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
            //echo $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

//***************************FIN FUNCIONES PARA MANEJO DE PAGINACION***************************/
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
