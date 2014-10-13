<?php

include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_Formularios {

    //constructor	
    function clsLab_Formularios() {
        
    }

    function insertar($Formulario, $usuario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "INSERT INTO mnt_formularios(nombreformulario,idusuarioreg,fechahoraReg) 
                      VALUES('$Formulario','$usuario',(SELECT date_trunc('seconds',(SELECT now())))) RETURNING id";
            $result = @pg_query($query);
            
            if (!$result)
                return false;
            else
                $insert_row = pg_fetch_row($result);
                return $insert_row[0];
        }
    }
    
    function verifyUnique($nombre, $atencion, $establecimiento) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT *
                      FROM mnt_formularios t01
                      INNER JOIN mnt_formulariosxestablecimiento t02 ON (t01.id = t02.idformulario)
                      WHERE t01.nombreformulario ILIKE '$nombre' AND t02.id_atencion = $atencion AND t02.idestablecimiento = $establecimiento";
            $result = @pg_query($query);
            if ($result)
                if(pg_num_rows($result) > 0)
                    return true;
                else
                    return false;
            else
                return false;
        }
    }

    function IdUltimoProgramaInsertado() {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT MAX(id) AS id FROM mnt_formularios";
            $result = @pg_query($query);
            $row_Id = pg_fetch_array($result);
            $Id = $row_Id[0];
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function IngFormularioxEstablecimiento($IdForm, $Idprograma, $lugar, $cond, $usuario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "INSERT INTO mnt_formulariosxestablecimiento(idformulario, id_atencion, idestablecimiento,condicion,idusuarioreg,fechahorareg) 
                      VALUES($IdForm,$Idprograma,$lugar,'$cond',$usuario,(SELECT date_trunc('seconds',(SELECT now()))))";

            $result = pg_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    function actualizar($IdFormulario, $Formulario, $usuario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "UPDATE mnt_formularios SET nombreformulario='$Formulario', idusuariomod = $usuario, fechahoramod = (SELECT date_trunc('seconds',(SELECT now()))) WHERE id = '$IdFormulario'";
            $result = pg_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    function actualizarxestablecimiento($IdFormulario, $IdPrograma, $lugar, $usuario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "UPDATE mnt_formulariosxestablecimiento SET id_atencion = $IdPrograma, idusuariomod = $usuario, fechahoramod = (SELECT date_trunc('seconds',(SELECT now())))"
                    ."WHERE idformulario = $IdFormulario AND idestablecimiento = $lugar";
            $result = pg_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    function eliminar($IdFormulario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "DELETE FROM mnt_formularios WHERE id = '$IdFormulario'";
            $result = pg_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    //FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
    function VerificarIntegridad($IdPrograma) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT * FROM mnt_formularios WHERE id = '$IdFormulario'";
            $result = pg_query($query);
            $cuenta = pg_num_rows($result);

            if ($cuenta > 0)
                return true;
            else
                return false;
        }
    }

    // consulta las areas de la BD
    function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar, $lugar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t01.id AS idformulario,
                             t01.nombreformulario,
                             t02.condicion,
                             CASE WHEN t02.condicion = 'H' THEN 'Habilitado' ELSE 'Inhabilitado' END AS cond,
                             t03.nombre AS nombreprograma
                      FROM mnt_formularios t01
                      INNER JOIN mnt_formulariosxestablecimiento t02 ON (t01.id = t02.idformulario)
                      INNER JOIN ctl_atencion                    t03 ON (t03.id = t02.id_atencion)
                      WHERE t02.idestablecimiento = $lugar
                      ORDER BY t01.id LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function consultarProgramas($lugar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t01.id AS idprograma,
                             t01.nombre AS nombreprograma
                      FROM ctl_atencion t01
                      WHERE t01.id_tipo_atencion = 6
                      ORDER BY t01.nombre";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function consultar($lugar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT * 
                      FROM mnt_formularios t01
                      INNER JOIN mnt_formulariosxestablecimiento t02 ON (t01.id = t02.idformulario)
                      WHERE idestablecimiento = $lugar
                      ORDER BY t01.id";
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
            $result = @pg_query($query);
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

    //consultando el numero de registros de la tabla
    function NumeroDeRegistros($lugar) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT * 
                      FROM mnt_formularios t01
                      INNER JOIN mnt_formulariosxestablecimiento t02 ON (t01.id = t02.idformulario)
                      WHERE t02.idestablecimiento = $lugar";
            $numreg = pg_num_rows(pg_query($query));
            if (!$numreg)
                return false;
            else
                return $numreg;
        }
    }

    // consulta empleado por su codigo
    function consultarid($IdFormulario, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT t01.id AS IdFormulario,
                             t01.nombreformulario,
                             t03.id AS idprograma,
                             t03.nombre AS nombreprograma
                      FROM mnt_formularios t01
                      INNER JOIN mnt_formulariosxestablecimiento t02 ON (t01.id = t02.idformulario)
                      INNER JOIN ctl_atencion                    t03 ON (t03.id = t02.id_atencion)
                      WHERE t02.id = $IdFormulario AND t02.idestablecimiento = $lugar AND t03.id_tipo_atencion = 6";

            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function EstadoCuenta($IdFormulario, $cond, $lugar) {
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            if ($cond == 'H') {
                $query = "UPDATE mnt_formulariosxestablecimiento SET condicion = 'I' 
			  WHERE idformulario = $IdFormulario AND idestablecimiento = $lugar";
                $result = pg_query($query);
            }
            if ($cond == 'I') {
                $query = "UPDATE mnt_formulariosxestablecimiento SET condicion = 'H' 
			  WHERE idformulario = $IdFormulario AND idestablecimiento = $lugar";
                $result = pg_query($query);
            }
        }
        $con->desconectar();
    }
}
//CLASE
?>
