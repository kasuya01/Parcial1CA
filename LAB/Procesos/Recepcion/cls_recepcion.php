<?php

include_once("../../../Conexion/ConexionBD.php");

class clsRecepcion {

    //constructor	
    function clsRecepcion() {
        
    }

    function DatosEstablecimiento($lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $conNom = "SELECT mnt_establecimiento.IdTipoEstablecimiento,Nombre,NombreTipoEstablecimiento 
			    FROM mnt_establecimiento 
			    INNER JOIN mnt_tipoestablecimiento ON mnt_establecimiento.IdTipoEstablecimiento= mnt_tipoestablecimiento.IdTipoEstablecimiento
			    WHERE IdEstablecimiento=$lugar";
            $resul = mysql_query($conNom) or die('La consulta fall&oacute;: ' . mysql_error());
        }
        return $resul;
    }
//Fn PG
    function LlenarCmbEstablecimiento($Idtipo, $lugar, $idext) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $sqlText = "SELECT e.id as idestablecimiento,nombre 
                FROM ctl_establecimiento e 
                WHERE id_tipo_establecimiento=$Idtipo
                AND case  $idext
                    when 0 then e.id = $lugar 
                    else e.id= $idext
                    end
                ORDER BY nombre;";
            $dt = pg_query($sqlText);
            if (!$dt){
                return false;
            }
            else {
                return $dt;
            }
        }
      }
//Fn PG
    function seleccionarestablecimientos($Idtipo, $lugar, $idext) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $sqlText = "select id,nombre from ctl_establecimiento where id_tipo_establecimiento not in (12,13,29,28) order by nombre;";
            $dt = pg_query($sqlText);
            if (!$dt){
                return false;
            }
            else {
                return $dt;
            }
        }
      }
//Fn PG
    function LlenarCmbServ($IdServ, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            /*$sqlText = "select mnt_3.id,
                        CASE
                        WHEN mnt_3.nombre_ambiente IS NOT NULL
                        THEN  	CASE WHEN id_servicio_externo_estab IS NOT NULL
                                THEN mnt_ser.abreviatura ||'-->' ||mnt_3.nombre_ambiente
                                ELSE mnt_3.nombre_ambiente
                                END

                        ELSE
                                CASE WHEN id_servicio_externo_estab IS NOT NULL
                                THEN mnt_ser.abreviatura ||'--> ' || cat.nombre
                                ELSE cat.nombre
                                END
                        END AS servicio 
                        from ctl_atencion cat 
                        join mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion)
                        join mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id)
                        LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id
                        LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id
                        where id_area_atencion=$IdServ
                        and mnt_3.id_establecimiento=$lugar 
                        order by 2";*/
            $sqlText="with tbl_servicio as (select mnt_3.id,
CASE
WHEN mnt_3.nombre_ambiente IS NOT NULL
THEN  	
	CASE WHEN id_servicio_externo_estab IS NOT NULL
		THEN mnt_ser.abreviatura ||'-->' ||mnt_3.nombre_ambiente
		ELSE mnt_3.nombre_ambiente
	END

ELSE
CASE WHEN id_servicio_externo_estab IS NOT NULL 
	THEN mnt_ser.abreviatura ||'--> ' || cat.nombre
     WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=cat.nombre)
	THEN cat.nombre||'-'||cmo.nombre
END
END AS servicio 
from ctl_atencion cat 
join mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion)
join mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id)
LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id
LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id
join mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab)
join ctl_modalidad cmo on (cmo.id=mme.id_modalidad)
where  mnt_2.id=$IdServ
and mnt_3.id_establecimiento=$lugar
order by 2)
select id, servicio from tbl_servicio where servicio is not null;
";
            $dt = pg_query($sqlText) ;            
            if  (!$dt)
                return false;
            else
                return $dt;
        }
        
    }

    function LlenarCmbMed($idSubEsp, $lugar) {//echo $IdSub;
        $con = new ConexionBD;
        if ($con->conectar() == true) {
     /*       $sqlText = "SELECT mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado 
			   FROM mnt_empleados 
			   INNER JOIN mnt_usuarios ON mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado 
				WHERE mnt_usuarios.IdSubServicio=$idSubEsp  AND mnt_empleados.IdEstablecimiento=$lugar ORDER BY mnt_empleados.NombreEmpleado";
            $dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());*/
            $sqlText="select mem.id as idemp, nombreempleado as nombre, idempleado  
from mnt_empleado_especialidad_estab empest
join mnt_empleado mem on (empest.id_empleado=mem.id)
where id_aten_area_mod_estab=$idSubEsp";
            $dt = pg_query($sqlText) ;
            
            if  (!$dt)
                return false;
            else
                return $dt;
            
        }
    }

    function LlenarCmbMedicos($lugar) {//echo $IdSub;
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $sqlText = "SELECT mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado 
			   FROM mnt_empleados 
			   WHERE  mnt_empleados.IdEstablecimiento=$lugar  AND IdTipoEmpleado='MED' AND IdEmpleado<>'MED0000'
			   ORDER BY mnt_empleados.NombreEmpleado
";
            $dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
        }
        return $dt;
    }
//Fn Pg
    function ObtenerServicio($idSubEsp) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
     /*       $sqlText = "SELECT IdServicio FROM mnt_subservicio WHERE IdSubServicio=$idSubEsp";
            $dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
        }
        return $dt;*/
            	$sqlText= "select id_atencion 
from mnt_aten_area_mod_estab mnt_3
where id=$idSubEsp";
		$dt = pg_query($sqlText);
                if (!$dt){
                    return false;
                }
                else {
                    return $dt;
                }
        }
    }

    function LlenarCmbOrigen($IdMuestra) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $sqlText = "SELECT IdOrigenMuestra, OrigenMuestra FROM mnt_origenmuestra WHERE IdTipoMuestra='$IdMuestra' ORDER BY OrigenMuestra";
            $dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
        }
        return $dt;
    }

    function LlenarCmbMuestra($IdEstudio) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $sqlText = "SELECT  TipoMuestra, lab_tipomuestraporexamen.IdTipoMuestra 
			   FROM lab_tipomuestraporexamen  
			   INNER JOIN lab_tipomuestra ON lab_tipomuestra.IdTipoMuestra=lab_tipomuestraporexamen.IdTipoMuestra 
			   WHERE lab_tipomuestraporexamen.idexamen='$IdEstudio' ORDER BY TipoMuestra";
            $dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
        }
        return $dt;
    }

    function LlenarCmbArea($IdArea, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $sqlText = "SELECT nombreexamen,lab_examenes.IdExamen FROM lab_examenes 
INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
WHERE IdArea='$IdArea'AND  lab_examenesxestablecimiento.Condicion='H'  AND IdEstablecimiento=$lugar ORDER BY nombreexamen ";
            $dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
        }
        return $dt;
    }

    function BuscarOrigen($IdOrigen) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $SQL = "SELECT OrigenMuestra FROM mnt_origenmuestra WHERE IdOrigenMuestra=$IdOrigen";
            $dt = mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());

            while ($row = mysql_fetch_array($dt)) {
                $valret = "$row[0]";
            }
        }
        return $valret;
    }

    function RecuperarTipoMuestra($IdTipoMuestra) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $SQL = "SELECT TipoMuestra FROM lab_tipomuestra WHERE IdTipoMuestra=$IdTipoMuestra";
            $dt = mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());

            while ($row = mysql_fetch_array($dt)) {
                $valret = "$row[0]";
            }
        }
        return $valret;
    }

    function RecupererNombreExamen($IdExamen) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $SQL = "SELECT NombreExamen FROM lab_examenes WHERE IdExamen='$IdExamen'";
            $dt = mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());

            while ($row = mysql_fetch_array($dt)) {
                $valret = "$row[0]";
            }
        }
        return $valret;
    }

    function InsertarRecepcionPiloto($idexam, $tipo, $origen, $indicaciones) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $SQL = "INSERT INTO recepcionpiloto(Id,IdExamen,IdTipoMuestra,IdOrigenMuestra,Observacion) VALUES(0,'$idexam',$tipo,$origen,'$indicaciones')";
            $dt = mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());
        }
        return -1;
    }

    function RecuperarDatos() {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $SQL = "SELECT * FROM recepcionpiloto";
            $dt = mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());
        }

        return $dt;
    }

    function RecupererObservacion($id) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $SQL = "SELECT Observacion FROM recepcionpiloto WHERE Id=$id";
            $dt = mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());

            while ($row = mysql_fetch_array($dt)) {
                $valret = "$row[0]";
            }
        }
        return $valret;
    }

//FN PG
    function ValidarExpediente($nec, $idext, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
           // echo 'idext: '.$idext;
            if ($idext==$lugar){
            //    echo 'IF';
            $query_Search = "SELECT e.numero
, (primer_apellido||' '||coalesce(segundo_apellido,'' )||' '||coalesce(apellido_casada,'')
||', '||primer_nombre||' '||coalesce(segundo_nombre,'')||' '||coalesce(tercer_nombre,'')) as nombre 
FROM mnt_paciente d 
INNER JOIN mnt_expediente e ON e.id_paciente=d.id 
WHERE e.numero ='$nec'";
            /* $query_Search= 	"SELECT e.idnumeroexp, if(d.SegundoApellido IS NULL and d.SegundoNombre IS NULL, CONCAT(d.PrimerApellido,', ',d.PrimerNombre),
              IF(d.SegundoApellido IS NOT NULL and d.SegundoNombre IS NOT NULL,CONCAT(d.PrimerApellido,' ',d.SegundoApellido,', ',d.PrimerNombre,' ',d.SegundoNombre),
              IF(d.SegundoNombre IS NULL, CONCAT(d.PrimerApellido,' ',d.SegundoApellido,', ',d.PrimerNombre),CONCAT(d.PrimerApellido,', ',d.PrimerNombre,' ',d.SegundoNombre)))) AS Nombre
              FROM mnt_datospaciente d INNER JOIN mnt_expediente AS e ON e.idpaciente=d.idpaciente WHERE e.idnumeroexp ='$nec'"; */
            $query = pg_query($query_Search);
            //si no 
            }
            /*****EStE ELSE SIII solo para ver que pasa*////
            else{
              // echo 'Else';
             $query_Search="select e.numero, (primer_apellido||' '||coalesce(segundo_apellido,'' )||' '||coalesce(apellido_casada,'')||', '||primer_nombre||' '||coalesce(segundo_nombre,'')||' '||coalesce(tercer_nombre,'')) as nombre, id_establecimiento_origen  
                            from mnt_paciente_referido d
                            join mnt_expediente_referido e on (d.id = e.id_referido)
                            where e.numero= '$nec' 
                            and id_establecimiento_origen=$idext;";
             
             $query=  pg_query($query_Search);
            }
           /* if (pg_num_rows($query)==0){
                $query_Search = "SELECT e.numero
, (primer_apellido||' '||coalesce(segundo_apellido,'' )||' '||coalesce(apellido_casada,'')
||', '||primer_nombre||' '||coalesce(segundo_nombre,'')||' '||coalesce(tercer_nombre,'')) as nombre 
FROM mnt_paciente d 
INNER JOIN mnt_expediente e ON e.id_paciente=d.id 
WHERE e.numero ='$nec'";
            /* $query_Search= 	"SELECT e.idnumeroexp, if(d.SegundoApellido IS NULL and d.SegundoNombre IS NULL, CONCAT(d.PrimerApellido,', ',d.PrimerNombre),
              IF(d.SegundoApellido IS NOT NULL and d.SegundoNombre IS NOT NULL,CONCAT(d.PrimerApellido,' ',d.SegundoApellido,', ',d.PrimerNombre,' ',d.SegundoNombre),
              IF(d.SegundoNombre IS NULL, CONCAT(d.PrimerApellido,' ',d.SegundoApellido,', ',d.PrimerNombre),CONCAT(d.PrimerApellido,', ',d.PrimerNombre,' ',d.SegundoNombre)))) AS Nombre
              FROM mnt_datospaciente d INNER JOIN mnt_expediente AS e ON e.idpaciente=d.idpaciente WHERE e.idnumeroexp ='$nec'"; */
          /*  $query = pg_query($query_Search);
            }*/
              // echo '||'.$query_Search.'||';
            if (!$query)
                return false;
            // eor die('La consulta fallo&oacute;: ' . pg_error());
            else {
                $totalRegs = pg_num_rows($query);
           //     echo 'Total Registros: '.$totalRegs.'\n     finquerysearch';
                return $totalRegs;
            }
        }
    }

//Fn PG
    function DatosPaciente($nec, $idext) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
           /* $query_Search = "SELECT e.id, e.numero, (primer_apellido||' '||coalesce(segundo_apellido,'' )||' '||coalesce(apellido_casada,'')||', '||primer_nombre||' '||coalesce(segundo_nombre,'')||' '||coalesce(tercer_nombre,'')) as nombre ,
                    s.nombre AS sexoconv, extract(year from age(fecha_nacimiento)) AS Edad, conocido_por,id_sexo
                    FROM mnt_paciente d
                    INNER JOIN mnt_expediente e ON e.id_paciente = d.id
                    INNER JOIN ctl_sexo s on (d.id_sexo=s.id)
                    WHERE e.numero ='$nec'";*/
            // echo $query_Search;        
            $query_Search="
                with tbl_datos_paciente as(
                select e.id as idexpediente, e.numero as numero, 
                concat_ws (' ',d.primer_apellido,d.segundo_apellido, d.apellido_casada, d.primer_nombre, d.segundo_nombre, d.tercer_nombre) as nombre,  
                s.nombre AS sexoconv, extract(year from age(fecha_nacimiento)) AS Edad, conocido_por,id_sexo, id_establecimiento as idestab
                FROM mnt_paciente d 
                JOIN mnt_expediente e ON (d.id=e.id_paciente) 
                JOIN ctl_sexo s on (s.id=d.id_sexo)
                where e.id_establecimiento=$idext
                union 
                select e.id as idexpediente, e.numero as numero, 
                concat_ws (' ',d.primer_apellido,d.segundo_apellido, d.apellido_casada, d.primer_nombre, d.segundo_nombre, d.tercer_nombre) as nombre, 
                s.nombre AS sexoconv, extract(year from age(fecha_nacimiento)) AS Edad,'' as conocido_por,  id_sexo, id_establecimiento_origen as idestab
                FROM mnt_paciente_referido d
                JOIN mnt_expediente_referido e on (d.id= e.id_referido)
                JOIN ctl_sexo s on (s.id=d.id_sexo)
                where id_establecimiento_origen=$idext)
                select * from tbl_datos_paciente
                where numero= '$nec'";
            $query = pg_query($query_Search);
           // echo '<br/>'.$query_Search.'<br/>';
            $num = pg_num_rows($query);
            //echo 'ŃUM: '.$num;
            //mysql_fetch_row($query);
            //echo "numero de resultados ".$num;
            if (!$query)
                return false;
            // eor die('La consulta fallo&oacute;: ' . pg_error());
            else {
                if ($num >0) {
                    $Datos = pg_fetch_array($query);
              //      echo 'Datos'.$Datos;
                    return $Datos;
                } else {
                    $Datos = 0;
                    return $Datos;
                }
            }
        }
        //return $Datos;
    }

    function ModificacionExpDatosPac($pn, $pa, $sxn, $fn, $fechareg, $lugar) {

        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $sqlText = "INSERT INTO mnt_datospaciente(IdPaciente,PrimerApellido,SegundoApellido,PrimerNombre,SegundoNombre,TercerNombre,Sexo,FechaNacimiento,HoraNacimiento,PaisNacimiento,LugarNacimiento,EstadoCivil,DocuLegalIdentidad,NumDocLegalIdentidad,Ocupacion,Direccion,TelefonoCasa,DeptoDomicilio,MunicipioDomicilio,AreaGeografica,LugarTrabajo,TelefonoTrabajo,Asegurado,Noafiliacion,NombrePadre,NombreMadre,NombreConyuge,NombreResponsable,DireccionResponsable,TeleCasaResponsable,ProporcionoDatos,Parentesco,DocLegalProporDatos,NoDocLegalProporDatos,Observaciones,IdUsuarioReg,IdUsuarioMod,FechaHoraReg,FechaHoraMod) VALUES('0',UCASE('$pa'),' ',UCASE('$pn'),' ',' ','$sxn','$fn','00:00:00','0',' ','0','7','-','0',' ',' ','6','601','0',' ',' ','2',' ',' ','n',' ',' ',' ',' ',' ','0','7',' ',' ','200',' ',NOW(),'0000-00-00 00:00:00')";
            $queryIns = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
            $iddato = mysql_insert_id();

            $sqlInsert = "INSERT INTO mnt_expediente(IdNumeroExp,IdPaciente,IdUsuarioReg,IdUsuarioMod,FechaHoraReg,FechaHoraMod,IdEstablecimiento) VALUES($iddato,$iddato,'200',' ',NOW(),'0000-00-00 00:00:00',$lugar)";
            $queryIns = mysql_query($sqlInsert) or die('La consulta fall&oacute;:' . mysql_error());
        }
        return $iddato;
    }

    function VerificarDatos($idexam, $tipo, $origen) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $SQL = "SELECT * FROM recepcionpiloto WHERE IdExamen='$idexam' AND IdTipoMuestra=$tipo AND IdOrigenMuestra=$origen";
            $dt = mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());
            $totalRegs = mysql_numrows($dt);
        }
        return $totalRegs;
    }

//FN PG
//funcion para consultar tipo de hospital al que pertenece el establecimiento
    function tipoestactual($idestablecimiento) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $sql = "SELECT a.id as idtipoestablecimiento, a.nombre as nombretipoestablecimiento
        FROM ctl_tipo_establecimiento a
        INNER JOIN ctl_establecimiento b ON a.id = b.id_tipo_establecimiento
        WHERE b.id = $idestablecimiento";
            $result = pg_query($sql);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }

//FN PG
//funcion para consultar tipo de areas que tiene el establecimiento
    function tipoestservicio($idestablecimiento) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
//            $sql = "select distinct(mame.id), (caa.nombre||'-'||cmo.nombre) as nombre
//                from ctl_area_atencion caa
//                join mnt_area_mod_estab mame on(caa.id=mame.id_area_atencion)
//                join mnt_modalidad_establecimiento mme on (mme.id=mame.id_modalidad_estab)
//                join ctl_modalidad cmo on (cmo.id=mme.id_modalidad)
//                where mame.id_establecimiento=$idestablecimiento";
           $sql="SELECT mnt_area_mod_estab.id as codigo,
               CASE WHEN id_servicio_externo_estab IS NOT NULL 
                       THEN mnt_servicio_externo.abreviatura ||'--'  || ctl_area_atencion.nombre
                       ELSE       ctl_modalidad.nombre ||'--' || ctl_area_atencion.nombre 
                       END as nombre
               FROM mnt_area_mod_estab
               INNER JOIN  ctl_area_atencion  on  ctl_area_atencion.id = mnt_area_mod_estab.id_area_atencion
               INNER JOIN ctl_modalidad ON ctl_modalidad.id = mnt_area_mod_estab.id_modalidad_estab
               LEFT JOIN mnt_servicio_externo_establecimiento ON (mnt_servicio_externo_establecimiento.id = mnt_area_mod_estab.id_servicio_externo_estab) 
               LEFT JOIN mnt_servicio_externo ON (mnt_servicio_externo.id = mnt_servicio_externo_establecimiento.id_servicio_externo) 
               WHERE mnt_area_mod_estab.id_establecimiento=$idestablecimiento
               ORDER by mnt_area_mod_estab.id,ctl_modalidad.nombre,ctl_area_atencion.nombre";
            $result = pg_query($sql);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }//fin tipoestservicio
   
}

//clase
?>