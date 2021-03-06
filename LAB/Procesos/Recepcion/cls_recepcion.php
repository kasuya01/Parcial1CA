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
           // $sqlText = "select id,nombre from ctl_establecimiento where id_tipo_establecimiento not in (12,13,29,28) order by nombre;";
            $sqlText = "
select t01.id, case when t02.nombre ilike '%isss%' then (t01.nombre||' - ' ||t02.nombre)
		else t01.nombre end as nombre, id_tipo_establecimiento
from ctl_establecimiento 	t01
join ctl_tipo_establecimiento 	t02 on (t02.id=t01.id_tipo_establecimiento)
where id_tipo_establecimiento not in (12,13,29,28) order by id_tipo_establecimiento,  t01.nombre;";
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
    function LlenarCmbServ($IdServ,$lugar) {
        $con = new ConexionBD;
         $condicionAmbiente="";
        if ($con->conectar() == true) {
            $sql = "s";
             if ($IdServ==2){
               $condicionAmbiente=' AND mnt_3.nombre_ambiente IS NOT NULL';
             }
                $sqlText="WITH tbl_servicio as (SELECT mnt_3.id,
				  CASE
					  WHEN mnt_3.nombre_ambiente IS NOT NULL
					  	THEN mnt_3.nombre_ambiente
					  	ELSE cat.nombre
				  END AS nombre
				  FROM  ctl_atencion cat
						  JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion)
						  JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id)
						  JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4))
						  LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id
						  LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id
						  JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab)
						  JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad)
				  WHERE  mnt_2.id=$IdServ $condicionAmbiente
				  		 AND mnt_3.id_establecimiento=$lugar
                         and cat.id_tipo_atencion not in (3,5)
				  ORDER BY 2)
				  SELECT id, nombre FROM tbl_servicio WHERE nombre IS NOT NULL";
                //  echo $query;

            $dt = pg_query($sqlText) ;
            if  (!$dt)
                return false;
            else
                return $dt;
        }

    }

     function BuscarPorcedencia($idSubEsp){
          $con = new ConexionBD;
        if ($con->conectar() == true) {

          $sql="SELECT mnt_area_mod_estab.id_area_atencion from mnt_aten_area_mod_estab
                      INNER JOIN mnt_area_mod_estab on mnt_area_mod_estab.id= mnt_aten_area_mod_estab.id_area_mod_estab
                      WHERE mnt_aten_area_mod_estab.id=$idSubEsp";
             $dt = pg_query($sql) ;

            if  (!$dt)
                return false;
            else
                return $dt;
        }

     }
    function LlenarCmbMed($idSubEsp, $lugar) {//echo $IdSub;
        $con = new ConexionBD;
        if ($con->conectar() == true) {

         $sqlText="select distinct mem.id as idemp, nombreempleado as nombre, idempleado
                    from mnt_empleado_especialidad_estab empest
                    join mnt_empleado mem on (empest.id_empleado=mem.id)
                    where (id_aten_area_mod_estab=$idSubEsp
                     or id_tipo_empleado=2 )
                    and mem.habilitado=true
                    order by 2";
            $dt = pg_query($sqlText) ;

            if  (!$dt)
                return false;
            else
                return $dt;

        }
    }

    function LlenarCmbMedTodos($lugar) {//echo $IdSub;
        $con = new ConexionBD;
        if ($con->conectar() == true) {

        $sqlText="select DISTINCT (mem.id),mem.id as idemp ,nombreempleado as nombre, idempleado
                    from mnt_empleado_especialidad_estab empest
join mnt_empleado mem on (empest.id_empleado=mem.id)
where (mem.id_tipo_empleado=2 OR mem.id_tipo_empleado=4) and mem.habilitado=true order by nombre ";
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
    function DatosPaciente($nec, $idext, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $edad ="TO_CHAR(NOW(), 'DD/MM/YYYY') as fecha,
                         REPLACE(
                            REPLACE(
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                age(fecha_nacimiento::timestamp)::text,
                                            'years', 'años'),
                                        'year', 'año'),
                                    'mons', 'meses'),
                                'mon', 'mes'),
                            'days', 'días'),
                         'day', 'día') AS Edad";
            if ($idext==$lugar){
                $query_Search="select e.id as idexpediente, e.numero as numero,
                concat_ws (' ',d.primer_apellido,d.segundo_apellido, d.apellido_casada, d.primer_nombre, d.segundo_nombre, d.tercer_nombre) as nombre,
                s.nombre AS sexoconv, $edad, conocido_por,id_sexo, id_establecimiento as idestab
                FROM mnt_paciente d
                JOIN mnt_expediente e ON (d.id=e.id_paciente)
                JOIN ctl_sexo s on (s.id=d.id_sexo)
                where e.id_establecimiento=$idext
                and numero= '$nec'";

            }
            else{
                $query_Search="select e.id as idexpediente, e.numero as numero,
                concat_ws (' ',d.primer_apellido,d.segundo_apellido, d.apellido_casada, d.primer_nombre, d.segundo_nombre, d.tercer_nombre) as nombre,
                s.nombre AS sexoconv, $edad,'' as conocido_por,  id_sexo, id_establecimiento_origen as idestab
                FROM mnt_paciente_referido d
                JOIN mnt_expediente_referido e on (d.id= e.id_referido)
                JOIN ctl_sexo s on (s.id=d.id_sexo)
                where id_establecimiento_origen=$idext
                and  e.numero= '$nec'";
            }
            //echo $query_Search;
           /* $query_Search = "SELECT e.id, e.numero, (primer_apellido||' '||coalesce(segundo_apellido,'' )||' '||coalesce(apellido_casada,'')||', '||primer_nombre||' '||coalesce(segundo_nombre,'')||' '||coalesce(tercer_nombre,'')) as nombre ,
                    s.nombre AS sexoconv, extract(year from age(fecha_nacimiento)) AS Edad, conocido_por,id_sexo
                    FROM mnt_paciente d
                    INNER JOIN mnt_expediente e ON e.id_paciente = d.id
                    INNER JOIN ctl_sexo s on (d.id_sexo=s.id)
                    WHERE e.numero ='$nec'";*/
            // echo $query_Search;
        /*    $query_Search="
                with tbl_datos_paciente as(
                select e.id as idexpediente, e.numero as numero,
                concat_ws (' ',d.primer_apellido,d.segundo_apellido, d.apellido_casada, d.primer_nombre, d.segundo_nombre, d.tercer_nombre) as nombre,
                s.nombre AS sexoconv, age(fecha_nacimiento::timestamp)::text AS agedad, conocido_por,id_sexo, id_establecimiento as idestab
                FROM mnt_paciente d
                JOIN mnt_expediente e ON (d.id=e.id_paciente)
                JOIN ctl_sexo s on (s.id=d.id_sexo)
                where e.id_establecimiento=$idext
                and numero= '$nec'
                union
                select e.id as idexpediente, e.numero as numero,
                concat_ws (' ',d.primer_apellido,d.segundo_apellido, d.apellido_casada, d.primer_nombre, d.segundo_nombre, d.tercer_nombre) as nombre,
                s.nombre AS sexoconv, (fecha_nacimiento::timestamp)::text AS agedad,'' as conocido_por,  id_sexo, id_establecimiento_origen as idestab
                FROM mnt_paciente_referido d
                JOIN mnt_expediente_referido e on (d.id= e.id_referido)
                JOIN ctl_sexo s on (s.id=d.id_sexo)
                where id_establecimiento_origen=$idext
                and  e.numero= '$nec')
                select * from tbl_datos_paciente";*/
            $query = pg_query($query_Search);
            //echo '<br/>'.$query_Search.'<br/>';
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

    function VerificarExisteSolicitud($IdSubServicio,$IdEmpleado,$FechaConsulta,$idexpediente,$IdEstabExt,$lugar){
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $SQL = "SELECT t01.id_establecimiento, t01.id_establecimiento_externo,
                    CASE WHEN t01.id_establecimiento == t01.id_establecimiento_externo
                         THEN t04.id_empleado
                         ELSE t05.id_empleado
                         END AS id_empleado,
                    CASE WHEN t01.id_establecimiento == t01.id_establecimiento_externo
                         THEN t04.idsubservicio
                         ELSE t05.id_aten_area_mod_estab
                         END AS id_especialidad,
                    CASE WHEN t01.id_establecimiento == t01.id_establecimiento_externo
                         THEN t04.id
                         ELSE t05.id
                         END AS id_historial_clinico,
                    TO_DATE(t01.fecha_solicitud, 'YYYY-MM-DD') AS fecha_solicitud
                    FROM sec_solicitudestudios                 t01
                    INNER JOIN ctl_estado_servicio_diagnostico t02 ON (t02.id = t01.estado)
                    LEFT  JOIN cit_citas_serviciodeapoyo       t03 ON (t01.id = t03.id_solicitudestudios)
                    LEFT  JOIN sec_historial_clinico           t04 ON (t04.id = t01.id_historial_clinico)
                    LEFT  JOIN mnt_dato_referencia             t05 ON (t05.id = t01.id_dato_referencia)
                    WHERE CASE WHEN t01.id_establecimiento == t01.id_establecimiento_externo
                          THEN t04.idsubservicio = $IdSubServicio
                          ELSE t05.id_aten_area_mod_estab = $IdSubServicio
                          END
                    AND CASE WHEN t01.id_establecimiento == t01.id_establecimiento_externo
                        THEN t04.id_empleado = $IdEmpleado
                        ELSE t05.id_empleado = $IdEmpleado
                        END
                    AND CASE WHEN t01.id_establecimiento == t01.id_establecimiento_externo
                        THEN t04.fechaconsulta = $FechaConsulta
                        ELSE date(t01.fecha_solicitud) = date($FechaConsulta) END
                    AND CASE WHEN t01.id_establecimiento == t01.id_establecimiento_externo
                        THEN t04.id_expediente = $idexpediente
                        ELSE t05.id_expediente_referido = $idexpediente
                        END
                    AND t01.id_establecimiento_externo = $IdEstabExt";
          /* $SQL = "SELECT sec_solicitudestudios.id, sec_solicitudestudios.estado, to_char(cit_citas_serviciodeapoyo.fecha, 'YYYY-MM-DD') AS fecha_cita,
                    ctl_estado_servicio_diagnostico.descripcion
                          FROM sec_historial_clinico
                          INNER JOIN sec_solicitudestudios ON sec_solicitudestudios.id_historial_clinico= sec_historial_clinico.id
                          INNER JOIN ctl_estado_servicio_diagnostico ON ctl_estado_servicio_diagnostico.id = sec_solicitudestudios.estado
                          LEFT  JOIN cit_citas_serviciodeapoyo ON (sec_solicitudestudios.id = cit_citas_serviciodeapoyo.id_solicitudestudios)
                          WHERE sec_historial_clinico.idsubservicio=$IdSubServicio AND sec_historial_clinico.id_empleado= $IdEmpleado
                          AND sec_historial_clinico.fechaconsulta='$FechaConsulta' AND sec_solicitudestudios.id_expediente=$idexpediente";*/
            $result = pg_query($SQL);

        }
        if (!$result) {
            return false;
        } else {
            return $result;
        }
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
//
          $sql="SELECT mnt_area_mod_estab.id as codigo,
               CASE WHEN id_servicio_externo_estab IS NOT NULL
                       THEN mnt_servicio_externo.abreviatura ||'--'  || ctl_area_atencion.nombre
                       ELSE       ctl_modalidad.nombre ||'--' || ctl_area_atencion.nombre
                       END as nombre
               FROM mnt_area_mod_estab
               INNER JOIN  ctl_area_atencion  on (ctl_area_atencion.id = mnt_area_mod_estab.id_area_atencion AND (ctl_area_atencion.id_tipo_atencion=1 OR ctl_area_atencion.id_tipo_atencion=4))
               INNER JOIN  mnt_modalidad_establecimiento ON mnt_modalidad_establecimiento.id=mnt_area_mod_estab.id_modalidad_estab
               INNER JOIN ctl_modalidad ON ctl_modalidad.id = mnt_modalidad_establecimiento.id_modalidad
               LEFT JOIN mnt_servicio_externo_establecimiento ON (mnt_servicio_externo_establecimiento.id = mnt_area_mod_estab.id_servicio_externo_estab)
               LEFT JOIN mnt_servicio_externo ON (mnt_servicio_externo.id = mnt_servicio_externo_establecimiento.id_servicio_externo)
               WHERE mnt_area_mod_estab.id_establecimiento=$idestablecimiento
               ORDER by ctl_modalidad.nombre,mnt_servicio_externo.nombre,ctl_area_atencion.nombre";
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
