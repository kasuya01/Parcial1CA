<?php

include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_Programas {

    //constructor	
    function clsLab_Programas() {
        
    }

    function insertar($Programa, $usuario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "INSERT INTO mnt_programas(NombrePrograma,IdusuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) 
	VALUES('$Programa','$usuario',NOW(),'$usuario',NOW())";
            $result = @mysql_query($query);
            if (!$result)
                return false;
            else
                $idultimo = @mysql_insert_id();
            return $idultimo;
        }
    }

    function IngProgramasxEstablecimiento($IdPrograma, $lugar, $cond, $usuario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "INSERT INTO mnt_programasxestablecimiento(IdPrograma,IdEstablecimiento,Condicion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) 
VALUES($IdPrograma,$lugar,'$cond',$usuario,NOW(),$usuario,NOW())";
            $result = mysql_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    function actualizar($IdPrograma, $Programa, $usuario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "UPDATE mnt_programas SET NombrePrograma='$Programa',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdPrograma='$IdPrograma'";
            $result = mysql_query($query);

            if (!$result)
                return false;
            else
                return true;
        }
    }

    function eliminar($IdPrograma) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "DELETE FROM mnt_programas WHERE IdPrograma='$IdPrograma'";
            $result = mysql_query($query);

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
            $query = "SELECT * FROM mnt_programas WHERE IdPrograma='$IdPrograma'";
            $result = mysql_query($query);
            $cuenta = mysql_num_rows($result);

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
            $query = "SELECT mnt_programas.IdPrograma,mnt_programas.NombrePrograma,mnt_programasxestablecimiento.Condicion,
	       IF(mnt_programasxestablecimiento.Condicion='H','Habilitado','Inhabilitado')as Cond
	       FROM mnt_programas 
               INNER JOIN mnt_programasxestablecimiento ON mnt_programas.IdPrograma=mnt_programasxestablecimiento.IdPrograma
	       WHERE mnt_programasxestablecimiento.IdEstablecimiento=$lugar ORDER BY IdPrograma LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
            $result = mysql_query($query);
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
            $query = "SELECT * FROM mnt_programas 
			   INNER JOIN mnt_programasxestasblecimiento 
			   ON mnt_programas.IdPrograma=mnt_programasxestablecimiento.IdPrograma
			   WHERE IdEstablecimiento=$lugar
			   ORDER BY mnt_programas.IdPrograma ";
            $result = @mysql_query($query);
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
            $query = $query . " LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
            $result = @mysql_query($query);
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
            $numreg = mysql_num_rows(mysql_query($query));
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
			   FROM mnt_programas
			   INNER JOIN mnt_programasxestablecimiento 
			   ON mnt_programas.IdPrograma=mnt_programasxestablecimiento.IdPrograma
			   WHERE IdEstablecimiento=$lugar";
            $numreg = mysql_num_rows(mysql_query($query));
            if (!$numreg)
                return false;
            else
                return $numreg;
        }
    }

    // consulta empleado por su codigo
    function consultarid($IdPrograma) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT * FROM mnt_programas WHERE IdPrograma='$IdPrograma'";

            $result = mysql_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function EstadoCuenta($idprograma, $cond, $lugar) {
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            if ($cond == 'H') {
                $query = "UPDATE mnt_programasxestablecimiento SET Condicion='I' WHERE IdPrograma='$idprograma' AND IdEstablecimiento=$lugar";
                $result = mysql_query($query);
                /* 	$query1= "UPDATE lab_examenes SET Habilitado='N' WHERE IdExamen='$idexamen'" ;
                  $result1 = mysql_query($query1); */
            }
            if ($cond == 'I') {
                $query = "UPDATE mnt_programasxestablecimiento SET Condicion='H' WHERE IdPrograma='$idprograma' AND IdEstablecimiento=$lugar";
                $result = mysql_query($query);
                /* $query1= "UPDATE lab_examenes SET Habilitado='S' WHERE IdExamen='$idexamen'"; */
                //$result1 = mysql_query($query1);
            }
        }
        $con->desconectar();
    }

}

//CLASE
?>
