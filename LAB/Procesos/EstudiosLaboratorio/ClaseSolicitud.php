<?php

include_once("../../../Conexion/ConexionBD.php");

////////////////////////////////////////////////////////////////////////
///     CLASE DE PACIENTES								            ////
////////////////////////////////////////////////////////////////////////
class Paciente {

   //Método constructor
   function Paciente() {

   }

   /*    * ************************************************************************************** */
   /* Función para  Recuperar Nombre del Paciente                                            */
   /*    * ************************************************************************************** */

   function RecuperarNombre($idestablecimiento, $idexpediente, $IdNumeroExp) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         /* $SQL = "SELECT CONCAT_WS(' ', PrimerApellido, SegundoApellido, PrimerNombre, SegundoNombre, TercerNombre) AS Nombre
           FROM mnt_datospaciente INNER JOIN mnt_expediente ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
           where IdNumeroExp='$IdNumeroExp'"; */
         /* $SQL = "select (primer_nombre||' '||coalesce(segundo_nombre,'')||' '||coalesce(tercer_nombre,'')||' '||primer_apellido||' '||coalesce(segundo_apellido,'')) as nombre, mp.id_sexo
           from mnt_paciente mp
           join mnt_expediente me on (mp.id=me.id_paciente)
           where me.id_establecimiento=$lugar
           and me.id=$IdNumeroExp;"; */
         $SQL = "with tbl_datos_paciente as(
                            select e.id as idexpediente, e.numero as numero,
                            concat_ws (' ',d.primer_apellido,d.segundo_apellido, d.apellido_casada, d.primer_nombre, d.segundo_nombre, d.tercer_nombre) as nombre,
                            s.nombre AS sexoconv, extract(year from age(fecha_nacimiento)) AS Edad, conocido_por,id_sexo, id_establecimiento as idestab
                            FROM mnt_paciente d
                            JOIN mnt_expediente e ON (d.id=e.id_paciente)
                            JOIN ctl_sexo s on (s.id=d.id_sexo)
                            and habilitado=true
                            union
                            select e.id as idexpediente, e.numero as numero,
                            concat_ws (' ',d.primer_apellido,d.segundo_apellido, d.apellido_casada, d.primer_nombre, d.segundo_nombre, d.tercer_nombre) as nombre,
                            s.nombre AS sexoconv, extract(year from age(fecha_nacimiento)) AS Edad,'' as conocido_por,  id_sexo, id_establecimiento_origen as idestab
                            FROM mnt_paciente_referido d
                            JOIN mnt_expediente_referido e on (d.id= e.id_referido)
                            JOIN ctl_sexo s on (s.id=d.id_sexo)
                            where id_establecimiento_origen=$idestablecimiento)
                            select * from tbl_datos_paciente
                            where idexpediente= $idexpediente
                            and numero='$IdNumeroExp'";
         $Resultado = pg_query($SQL);
         // echo 'SQL'.$SQL;
         if (!$Resultado)
            return false;
         else
            return $Resultado;
         //or die('La consulta fall&oacute;: ' . mysql_error());
         /* $Rows = mysql_fetch_array($Resultado);
           $Nombre=$Rows['Nombre'];
           return $Nombre; */
      }
   }

   /*    * ************************************************************************************** */
   /* Función para  Recuperar IdSolicituEstudio de la solicitud del Paciente                 */
   /*    * ************************************************************************************** */

   function RecuperarIdSolicituEstudio($IdNumeroExp, $IdHistorialClinico,
           $idestab) {
      //   echo 'idnumexp'.$IdNumeroExp. ' idhist: '.$IdHistorialClinico.'<br/>';
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         /* $SQL = "SELECT id
           FROM sec_solicitudestudios
           WHERE id_historial_clinico = $IdHistorialClinico
           AND id_expediente = $IdNumeroExp"; */
         $SQL = "select id
                            from sec_solicitudestudios
                            where id_establecimiento_externo=$idestab
                            and case $idestab
                                    when id_establecimiento then id_historial_clinico=$IdHistorialClinico
                                    else id_dato_referencia=$IdHistorialClinico
                            end";
         //     echo 'Idsolicitud '.$SQL.'-<br\>';
         //exit;
         $Resultado = pg_query($SQL);
         // or die('La consulta fall&oacute;: ' . mysql_error());
         if (!$Resultado)
            return false;
         else {
            $num = pg_num_rows($Resultado);
            //  echo 'num: '.$num.'<br/>';
            if ($num == 1) {
               $Datos = pg_fetch_array($Resultado);
               return $Datos['id'];
            } else {
               $Datos = 0;
               return $Datos;
            }
         }
      }// fin if conectar
   }

// fin function RecuperarIdSolicituEstudio

//fin funcion verificar estado
   /*    * ************************************************************************************** */
   /* Función para  Crear la cita o actualizar la fecha de la cita                           */
   /*    * ************************************************************************************** */

   function IdCitaServApoyoInsertUpdate($IdSolicitudEstudio, $iduser,
           $IdNumeroExp, $LugardeAtencion, $IdCitaServApoyo, $badera) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         $recep = "select * from lab_recepcionmuestra where idsolicitudestudio=$IdSolicitudEstudio";
         $sql3 = pg_query($recep);
         $rec = pg_num_rows($sql3);
         if ($rec == 0) {
            $num = "SELECT (coalesce(MAX(t01.numeromuestra),0) + 1)
FROM lab_recepcionmuestra        t01
INNER JOIN sec_solicitudestudios t02 ON (t02.id = t01.idsolicitudestudio)
WHERE t01.fecharecepcion = current_date
AND t02.id_establecimiento = $LugardeAtencion";
            $sql2 = pg_query($num);
            $nmuestra = pg_fetch_array($sql2);

            $remuestra = "insert into lab_recepcionmuestra (numeromuestra, fecharecepcion, idsolicitudestudio, fechacita, idestablecimiento, idusuarioreg, fechahorareg) VALUES ($nmuestra[0], current_date, $IdSolicitudEstudio, current_date, $LugardeAtencion, $iduser, date_trunc('seconds',NOW()))";
            $rep = pg_query($remuestra);
            if (!$rep)
               return false;
         }
         // echo '<br/>Badera: '.$badera;
         if ($badera == 1) { // crear la cita
            $nextid = "select nextval('cit_citas_serviciodeapoyo_idcitaservapoyo_seq')";
            $sql = pg_query($nextid);
            $nextseq = pg_fetch_array($sql);
            $idnext = $nextseq[0];
            $InsertCit = "INSERT INTO cit_citas_serviciodeapoyo (id, fecha, id_solicitudestudios, idusuarioreg, fechahorareg)
                                  VALUES ($idnext,current_date,$IdSolicitudEstudio,$iduser,date_trunc('seconds',NOW()))";
            // echo 'inse: '.$InsertCit.'<br/>';
            $queryIns = pg_query($InsertCit);
            if (!$queryIns)
               return false;
            else {
               return $idnext;
            }
         } else { // actualizar la cita
            $UpdateCit = "update cit_citas_serviciodeapoyo
                                set fecha=current_date,
                                id_solicitudestudios=$IdSolicitudEstudio
                                where id=$IdCitaServApoyo";
            $query = pg_query($UpdateCit);
            //    echo $UpdateCit;
            if (!$query)
               return false;
            else
               return $IdCitaServApoyo;
            //   mysql_query($UpdateCit) or die('La consulta fall&oacute;: ' . mysql_error());
         }
      }// fin if conectar
   }
//Funcion utilizada para saber si se encuantra activo el proceso para envio de HL7
function ConsultaEnvioHL7($idestab) {
   $Conexion = new ConexionBD();
   $conectar = $Conexion->conectar();
   if ($conectar == true) {
      $SQL = "select * from lab_proceso_establecimiento where id_proceso_laboratorio=11 and activo=true and id_establecimiento=$idestab";
      $Resultado = pg_query($SQL);
      if (!$Resultado)
         return false;
      else {
         $num = pg_num_rows($Resultado);
         return $num;
      }
   }// fin if conectar
}//fin funcion ConsultaEnvioHL7

// fin function IdCitaServApoyoInsertUpdate
//
//	function FechaHoraNow($conectar){
//		if($conectar==true){
//			$SQL = "SELECT date_trunc('seconds',NOW()) as Ahora";
//			$Resultado = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
//			$Rows = mysql_fetch_array($Resultado);
//			$Nombre=$Rows['Ahora'];
//			return $Nombre;
//		}
//	}
}

// Fin de la Clase PACIENTE

class SolicitudLaboratorio {

   // Método Constructor de la clase SolicitudLaboratorio
   function SolicitudLaboratorio() {

   }

   /*    * ******************************************************************************* */
   /* Función para  Desplegar las Areas de Laboratorio                                */
   /*    * ******************************************************************************* */

//	function AreasLaboratorio($conectar){
//
//		if($conectar==true){
//                    $SQL="Select * from lab_areas
//			  Where Habilitado= 'S'
//			  Order by NombreArea Asc";
//
//			$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
//
//		}// FIn If conectar
//
//	}

   /*    * ******************************************************************************* */
   /* Función que devuelve la cantidad de Origenes de Muestra                         */
   /*    * ******************************************************************************* */
   function ExistenciaOrigenes($IdMuestra) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         $SQL = "SELECT count(*) as total
                FROM mnt_origenmuestra
                WHERE idtipomuestra=$IdMuestra";


         $Ejecutar = pg_query($SQL);
         if (!$Ejecutar) {
            return false;
         } else {
            return $Ejecutar;
            //   $Respuesta= pg_fetch_array($Ejecutar);
            /*   if($Respuesta['total']>0)
              return 1;
              else
              return 0; */
         }
      }
   }

   /*    * ******************************************************************************* */
   /* Función para  Desplegar las Muestrar de cada Examen                             */
   /*    * ******************************************************************************* */

//Funcion PG
   function CantiMuestra($IdExamen) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         /*  $SQL2="SELECT count(ltm.id) as totalmuestra
           FROM lab_tipomuestraporexamen ltme
           INNER JOIN  lab_tipomuestra ltm ON (ltme.idtipomuestra=ltm.id)
           WHERE idexamen=$IdExamen"; */
         $SQL2 = "select ltm.id as idtipo, tipomuestra as muestra, lte.id
                    from lab_tipomuestraporexamen  lte
                    join lab_tipomuestra ltm on (lte.idtipomuestra=ltm.id)
                    where lte.idexamen=$IdExamen
                       and lte.habilitado=true
                        order by tipomuestra ASC;";
         $result = pg_query($SQL2);
         if (!$result) {
            return false;
         } else {
            return $result;
         }
      }
   }

//Funcion PG
   function CantiSuministrante($IdExamen) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         $SQL2 = "select t2.id as idsumi, t2.suministrante, t1.id as id_examen_suministrante
                from lab_examen_suministrante t1
                join lab_suministrante t2 on (t2.id=t1.id_suministrante)
                where id_conf_examen_estab=$IdExamen";
         $result = pg_query($SQL2);
         if (!$result) {
            return false;
         } else {
            return $result;
         }
      }
   }

//Eliminar no se usuara
   function MostrarMuestra($IdExamen) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         /* $SQL="SELECT ltm.id as idtipo,tipomuestra as muestra
           FROM lab_tipomuestraporexamen  ltme
           INNER JOIN  lab_tipomuestra ltm ON  (ltm.id=ltme.idtipomuestra)
           WHERE idexamen=$IdExamen
           ORDER BY tipomuestra ASC;"; */

         $SQL = "select ltm.id as idtipo, tipomuestra as muestra, lte.id
                    from lab_tipomuestraporexamen  lte
                    join lab_tipomuestra ltm on (lte.idtipomuestra=ltm.id)
                    where lte.idexamen=(select lcee.id
                    from lab_conf_examen_estab lcee
                    where lcee.id=$IdExamen)
                    order by tipomuestra ASC;";

         $Ejecutar = pg_query($SQL);
         if (!$Ejecutar) {
            echo 'Error al ingresar';
            return false;
         } else {
            return $Ejecutar;
         }
         /*
           $Respuesta= pg_fetch_array($Ejecutar);

           $TotalMuestra=pg_query($SQL2);
           if (!$TotalMuestra){
           echo 'Error, de consulta de información';
           }
           $Muestras= pg_fetch_array($TotalMuestra);

           if($Muestras[0]>1){

           if(pg_num_rows($Ejecutar)>0){					/*
           $Origenes=$this->ExistenciaOrigenes($Respuesta['IdTipo']);
           if($Origenes>0){

           }
           else{


           }

           echo '<option value= "'.$Respuesta["IdTipo"].'">'
           .htmlentities($Respuesta["Muestra"]).'</option>';


           while($Respuesta= mysql_fetch_array($Ejecutar)) {
           echo '<option value= "'.$Respuesta["IdTipo"].'">'
           .htmlentities($Respuesta["Muestra"]).'</option>';
           }

           echo "</select>"; */

         /* 		} // Fin If Numero de Filas

           }else{

           echo "<input type='hidden' value='".$Respuesta["IdTipo"]."' name='M".$IdExamen."' id='M".$IdExamen."'>
           <input type='hidden' value='0' name='Origen".$IdExamen."' id='Origen".$IdExamen."'>";
           } //Fin else Numero de Filas
          */
      }// FIn If conectar
   }

// Fin función Mostrar Muestra



   /*    * ************************************************************************************** */
   /* Función para  verificar estado de solicitud                    */
   /*    * ************************************************************************************** */

   function verificarestado($idsol) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
         if ($conectar == true) {
          $sql = "with tb_estadosol as(
                        select count(*) as total, estadodetalle
                        from sec_detallesolicitudestudios
                        where idsolicitudestudio=$idsol
                        group by estadodetalle
                        )
                    select (sum(total) - coalesce((select total from tb_estadosol where estadodetalle=7),0)) as cantotrosestados
                    from tb_estadosol;";
          $Ejecutar = pg_query($sql);
          $cantestarr = pg_fetch_array ($Ejecutar);
          $cantest= $cantestarr[0];
        //  echo($sql);
        //  var_dump($cantest);

          if ($cantest == 0){
            $sql1="update sec_solicitudestudios
                    set estado=4
                    where id=$idsol;";
            $result = pg_query($sql1);
          }

          if (!$Ejecutar) {
             return false;
          } else {
             return true;
         }
     }
   }//fin verificar estado



   /*    * ******************************************************************************* */
   /* Función para  Desplegar el Origen de la Muestra de un Examen                    */
   /*    * ******************************************************************************* */

//Fn Pg
   function MostrarOrigenMuestra($IdMuestra, $IdExamen) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         $SQL = "SELECT id, origenmuestra
                    FROM mnt_origenmuestra
                    WHERE idtipomuestra=$IdMuestra
                    ORDER BY origenmuestra ASC";
         $Ejecutar = pg_query($SQL);
         if (!$Ejecutar)
            return false;
         else
            return $Ejecutar;

         /* if(mysql_num_rows($Ejecutar)>0){
           echo "<strong><font color='darkblue'>Origen de Muestra:</font></strong>
           <select class='Origen' name='Origen".$IdExamen."'	Id='Origen".$IdExamen."' style='width:140px'>";

           while($Respuesta= mysql_fetch_array($Ejecutar)) {
           echo '<option value= "'.$Respuesta["IdOrigenMuestra"].'">'
           .htmlentities($Respuesta["OrigenMuestra"]).'</option>';
           }

           echo '</select>';
           }	// end if Numero de filas
           else
           echo "<input name='Origen".$IdExamen."'	Id='Origen".$IdExamen."' value='0' type='hidden'>"; */
      }// FIn If conectar
   }

// Fin función Mostrar Muestra


   /*    * ******************************************************************************* */
   /* Función para  Guardar Datos de la Solicitud					                  */
   /*    * ******************************************************************************* */

   function GuardarDatos($IdHistorialClinico, $IdNumeroExp, $idexpediente,
           $FechaSolicitud, $IdUsuarioReg, $IdExamen, $Indicacion,
           $IdTipoMuestra, $IdOrigen, $IdEstablecimiento, $lugar,
           $id_expediente_referido, $idsuministrante) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      //echo "funcion GuardarDatos IdHistorialClinico ".$IdHistorialClinico." IdNumeroExp ";//.$IdNumeroExp ." FechaSolicitud ". $FechaSolicitud." IdUsuarioReg ".$IdUsuarioReg." FechaHoraReg ".$FechaHoraReg." IdExamen ".$IdExamen." Indicacion ".$Indicacion." IdTipoMuestra ".$IdTipoMuestra." IdOrigen ".$IdOrigen." IdEstablecimiento ".$IdEstablecimiento;
      if ($conectar == true) {
         // echo 'idexamen'.$IdExamen.'<br/>';
         //$link = mysqli_connect('localhost','labor', 'clinic0', 'siap');

         /* COMPROBACION DE EXAMENES YA DADOS */
         if ($IdEstablecimiento == $lugar) {
            $resp1 = "select detsol.idexamen
                                from sec_solicitudestudios  sol
                                join sec_detallesolicitudestudios detsol on (sol.id=detsol.idsolicitudestudio)
                                where sol.id_expediente=$idexpediente
                                and sol.fecha_solicitud='$FechaSolicitud'
                                and id_historial_clinico= $IdHistorialClinico
                                and detsol.id_conf_examen_estab=$IdExamen
                                and estadodetalle in (1,5)";
            $p_id_dato_referencia = 'NULL';
         } else {
            $resp1 = "select detsol.idexamen
                                from sec_solicitudestudios  sol
                                join sec_detallesolicitudestudios detsol on (sol.id=detsol.idsolicitudestudio)
                                where sol.id_dato_referencia= $IdHistorialClinico
                                and sol.fecha_solicitud='$FechaSolicitud'
                                and detsol.id_conf_examen_estab=$IdExamen
                                and id_expediente is null
                                and estadodetalle in (1,5);";
            $p_id_dato_referencia = $IdHistorialClinico;
            $IdHistorialClinico = 'NULL';
         }
         /*          * cambio a pg$resp= "select sec_detallesolicitudestudios.IdExamen
           from sec_solicitudestudios
           inner join sec_detallesolicitudestudios
           on sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
           where sec_solicitudestudios.IdNumeroExp='$IdNumeroExp'
           and sec_solicitudestudios.FechaSolicitud='$FechaSolicitud'
           and IdHistorialClinico = $IdHistorialClinico
           and sec_detallesolicitudestudios.IdExamen='$IdExamen'"; */
         //   echo $resp1;

         $resp = pg_query($resp1);
         if (@pg_num_rows($resp) < 1) {
            /*             * ************************************ */
            //if(!$row=pg_fetch_array($resp)){
            //$call = "datos: ".$IdHistorialClinico.",".$IdNumeroExp.",".$FechaSolicitud.",".$IdUsuarioReg.",".$IdExamen.",".$Indicacion.",".$IdTipoMuestra.",".$IdOrigen.",".$IdEstablecimiento.",".$lugar.",".@erro;
            //echo $call;
            //exit;
            $envio = "select solicitudestudiosexternos($IdHistorialClinico,$idexpediente,'$FechaSolicitud',$IdUsuarioReg,$IdExamen,'$Indicacion',$IdTipoMuestra,$IdOrigen,$IdEstablecimiento,$lugar, $p_id_dato_referencia, false, 0, 'create','{}', false, $idsuministrante, $lugar)";

            $env = pg_query($envio);
            //echo '<br/>Envio'.$envio.'<br/>';
            if ($envio == true) {
               // echo $envio;
               return true;
            }
            //    mysqli_query($link,"call SolicitudEstudiosExternos($IdHistorialClinico,'$IdNumeroExp','$FechaSolicitud',$IdUsuarioReg,'$IdExamen','$Indicacion',$IdTipoMuestra,$IdOrigen,$IdEstablecimiento,$lugar,@erro)");
         }//fin de comprobacion de datos
      }// FIn If conectar
   }

// Fin función Guardar Datos


   /*    * ******************************************************************************* */
   /* Función para  Recuperar el Nombre de la Muestra del Examen	                  */
   /*    * ******************************************************************************* */

//function RecuperarMuestra($IdMuestra){
//	$SQL="SELECT TipoMuestra
//		  FROM lab_tipomuestra
//		  WHERE IdTipoMuestra=$IdMuestra";
//
//	$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
//	$Respuesta= mysql_fetch_array($Ejecutar);
//	return $Respuesta["TipoMuestra"];
//
//}// Fin función Recuperar Muestra


   /*    * ******************************************************************************* */
   /* Funcion para recuperar el nombre del origen de la muestra	                  */
   /*    * ******************************************************************************* */
//
//function RecuperarOrigen($IdOrigenMuestra){
//	$SQL="SELECT OrigenMuestra
//		  FROM mnt_origenmuestra
//		  WHERE IdOrigenMuestra=$IdOrigenMuestra";
//
//	$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
//	$Respuesta= mysql_fetch_array($Ejecutar);
//	return $Respuesta["OrigenMuestra"];
//
//}// Fin función Recuperar Muestra


   /*    * ******************************************************************************* */
   /* Función para  Mostrar Detalle de los examenes solicitados		                  */
   /*    * ******************************************************************************* */
   function DetalleEstudiosLaboratorio($conectar, $IdHistorialClinico,
           $IdEstablecimiento) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {

         /* $SQL="SELECT IdDetalleSolicitud, sec_detallesolicitudestudios.IdExamen AS IdExamen,
           Indicacion, IdTipoMuestra, IdOrigenMuestra,
           sec_solicitudestudios.IdHistorialClinico AS  IdHistorialClinico, IdServicio, NombreExamen, Urgente, sec_detallesolicitudestudios.IdSolicitudEstudio
           FROM sec_solicitudestudios
           INNER JOIN sec_detallesolicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio = sec_solicitudestudios.IdSolicitudEstudio
           INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico = sec_historial_clinico.IdHistorialClinico
           INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen = lab_examenes.IdExamen
           INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
           WHERE sec_historial_clinico.IdHistorialClinico = $IdHistorialClinico
           AND sec_solicitudestudios.IdServicio = 'DCOLAB'
           AND sec_solicitudestudios.IdEstablecimiento = $IdEstablecimiento
           ORDER BY IdArea asc, NombreExamen asc"; */
         $SQL = "SELECT sds.id as iddetallesolicitud, sds.id_conf_examen_estab as idexamen,
indicacion, sds.idtipomuestra,tipomuestra, idorigenmuestra, origenmuestra,
case when id_historial_clinico is null then id_dato_referencia
else id_historial_clinico
end as idhistorialclinico,  id_atencion, estado, sds.idsolicitudestudio,
codigo_examen,nombre_examen,urgente, to_char(f_tomamuestra, 'YYYY-MM-DD HH24:MI') as f_tomamuestra, lsu.suministrante, les.id as id_examen_suministrante
from sec_solicitudestudios sse
left join sec_historial_clinico shc 			on (sse.id_historial_clinico=shc.id)
join sec_detallesolicitudestudios sds 		on (sds.idsolicitudestudio= sse.id)
join mnt_area_examen_establecimiento mnt4	on (sds.idexamen=mnt4.id)
join lab_conf_examen_estab lce			on (sds.id_conf_examen_estab=lce.id)
left join lab_tipomuestra ltm			on (sds.idtipomuestra=ltm.id)
left join mnt_origenmuestra mom			on (sds.idorigenmuestra=mom.id)
left join lab_examen_suministrante les  on (les.id_suministrante=sds.id_suministrante and les.id_conf_examen_estab=sds.id_conf_examen_estab)
left join lab_suministrante   lsu       on (lsu.id=les.id_suministrante)
where sse.id_establecimiento_externo=$IdEstablecimiento
and case $IdEstablecimiento
	when sse.id_establecimiento then id_historial_clinico=$IdHistorialClinico
	else id_dato_referencia=$IdHistorialClinico
    end
and sse.id_atencion= (select id from ctl_atencion where codigo_busqueda='DCOLAB')";
         $Ejecutar = pg_query($SQL);
         if (!$Ejecutar) {
            return false;
         } else {
            return $Ejecutar;
         }

         //echo $SQL;
         /* echo " <form  method='post' name='Editar' id='Editar'>
           <table class='General2'><tr><td colspan='8' class='TdTitulo2' >EXAMENES SOLICITADOS A LABORATORIO</td></tr>
           <tr class='TdEncabezado'><td>C&oacute;digo</td>
           <td>Nombre Examen</td>
           <td>Tipo Muestra</td>
           <td>Origen Muestra</td>
           <td>Indicaci&oacute;n</td>
           <td>Borrar</td>
           <td>Urgente</td>
           </tr>";

           $Ejecutar=mysql_query($SQL) or die ("Warning...: La consulta Falló ...!". mysql_error());
           $i=0;
           $t=0;
           while($Respuesta= mysql_fetch_array($Ejecutar)) {
           echo "<tr class='TdResultados'><td>".$Respuesta["IdExamen"]."</td>";
           echo "<td>".htmlentities($Respuesta["NombreExamen"]). "</td>";
           echo "<td><font color='red'><b>".htmlentities($this->RecuperarMuestra($Respuesta["IdTipoMuestra"])).
           "</b></font></td>";
           echo "<td><font color='darkblue'><b>".htmlentities($this->RecuperarOrigen($Respuesta["IdOrigenMuestra"])).
           "</b></font></td>";
           echo "<td><input type='text' id='Indicacion".$Respuesta['IdExamen']."' name='Indicacion".$Respuesta['IdExamen']."'
           value='".htmlentities ($Respuesta["Indicacion"]). "'>";
           echo "<input type='hidden' name='IdDetalle".$Respuesta['IdExamen']."' Id='IdDetalle".$Respuesta['IdExamen']."'
           value='".$Respuesta['IdDetalleSolicitud']."'></td>";

           echo "<td><input type='checkbox' name='ExamenesLab' value='".$Respuesta['IdExamen']."'/></td>";

           //$Bandera='S' SI YA HAY UNA SOLICITUD URGENTE
           $Respuesta3=  $this->RecuperarData($IdHistorialClinico, $IdEstablecimiento,$Bandera);

           if($Respuesta3 !=NULL || $Respuesta3 !=''){
           $SQL4=	"SELECT sec_detallesolicitudestudios.IdExamen,IdDetalleSolicitud,Urgente FROM sec_detallesolicitudestudios
           INNER JOIN lab_examenesxestablecimiento ON sec_detallesolicitudestudios.IdExamen=lab_examenesxestablecimiento.IdExamen
           WHERE IdSolicitudEstudio =$Respuesta3";
           $Ejecutar4=mysql_query($SQL4) or die ("Warning...: La consulta Fallo ...!". mysql_error());


           while($Respuesta4= mysql_fetch_array($Ejecutar4)) {

           if($Respuesta4["Urgente"]==1 && $Respuesta['IdDetalleSolicitud']==$Respuesta4['IdDetalleSolicitud']){
           echo "<td><input type='checkbox' id='Detalle".$t."' checked='checked' value='".$Respuesta4['IdDetalleSolicitud']."'/></td>";
           $t++;
           }
           }
           if($Respuesta["Urgente"]==1 && $Respuesta3 != $Respuesta['IdSolicitudEstudio']){
           echo "<td><input type='checkbox' id='Detalle".$t."' value='".$Respuesta['IdDetalleSolicitud']."'/></td>";
           $t++;
           }
           }

           if($Respuesta3 == NULL OR $Respuesta3 ==''){
           if($Respuesta["Urgente"]==1){
           echo "<td><input type='checkbox' id='Detalle".$t."' value='".$Respuesta['IdDetalleSolicitud']."'/></td>";
           $t++;
           }
           }
           echo "</tr>";
           $i++;

           } // Fin While para sacar datos

           echo "<tr><td colspan='6'><br><b>Total de Examenes Solicitados: $i</b> </td></tr></table><br>"; */

         /*          * ******************************************************************************** */
         /* $SQL2="select Impresiones from sec_solicitudestudios where IdHistorialClinico=".$IdHistorialClinico." and IdServicio='DCOLAB'";
           $Ejecutar2=mysql_query($SQL2) or die ("Warning...: La consulta Fallo ...!". mysql_error());
           $Respuesta2=mysql_fetch_array($Ejecutar2);


           if($Respuesta2["Impresiones"]==1){
           $check="<input id='Imprimir' type='checkbox'onclick='ImprimirResultados(".$IdHistorialClinico.");' checked='checked'>";
           }else{
           $check="<input id='Imprimir' type='checkbox'onclick='ImprimirResultados(".$IdHistorialClinico.");'>";
           }

           echo "<tr><td colspan='6' align='right'><br><h3><b>".$check."Resultado de Examenes Impresos [Pre-Operatorios]</b></h3> </td></tr></table><br>";

           /************************************************************************************* */
         /* $estadocita="select Impresiones from sec_solicitudestudios where IdHistorialClinico=".$IdHistorialClinico." and IdServicio='DCOLAB'";
           $resultado=mysql_query($estadocita) or die ("Warning...: La consulta Fallo ...!". mysql_error());


           echo "<table class='General2'>
           <tr><td align='right'>
           <input type='button'  value='Guardar Cambios' class='boton2'  onclick='GuardarCambios();'>
           <input type='button' name='Agregar' value='Agregar Examen' class='boton2' onclick='AgregarExamen();'>
           <input type='button' name='Terminar' value='Terminar Solitud' class='boton2' onclick='Urgente();'>
           </td></tr></table>
           <input type='hidden' name='total' id='total' value='".$i."'>
           <input type='hidden' id='totalurgente' value='".$t."'>
           <input type='hidden' id='IdHistorialClinico' name= 'IdHistorialClinico' value='".$IdHistorialClinico."'>
           </form>"; */
      } // Fin conectar
   }

// FIn funcion mostrar detalle de Laboratorio
//funcion buscar
//Fn Pg
   function buscardetsol($Respuesta3) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         $SQL4 = "select sec.idexamen,sec.id as iddetallesolicitud, urgente
from sec_detallesolicitudestudios sec
join lab_conf_examen_estab lcee on (sec.id_conf_examen_estab=lcee.id)
where idsolicitudestudio=$Respuesta3";
         $Ejecutar4 = pg_query($SQL4);
         if (!$Ejecutar4) {
            return false;
         } else {
            return $Ejecutar4;
         }
      }
   }

//fin buscardetsol
//Fn Pg

   function impresionessoli($idhistorialclinico, $idestab) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         $SQL4 = "select impresiones
                from sec_solicitudestudios sse
                where sse.id_establecimiento_externo=$idestab
                and case $idestab
                        when sse.id_establecimiento then id_historial_clinico=$idhistorialclinico
                        else id_dato_referencia=$idhistorialclinico
                    end
                and id_atencion=(select id from ctl_atencion where codigo_busqueda= 'DCOLAB');";
         $Ejecutar4 = pg_query($SQL4);
         if (!$Ejecutar4) {
            return false;
         } else {
            return $Ejecutar4;
         }
      }
   }

//fin impresionessol
//Fn Pg

   function ActualizarSolicitud($iddetalle, $ftomamx) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         $SQL4 = "update sec_solicitudestudios
set idtiposolicitud=1
where id=(select idsolicitudestudio from sec_detallesolicitudestudios where id=$iddetalle)');";
         $Ejecutar4 = pg_query($SQL4);
         if (!$Ejecutar4) {
            return false;
         } else {
            return true;
         }
      }
   }

//fin ActualizarSolicitud



   /*    * ******************************************************************************* */
   /* Funcion para Borrar un Examen de la solicitud				                  */
   /*    * ******************************************************************************* */

   function BorrarExamen($IdDetalle) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         $SQL = "delete from sec_detallesolicitudestudios
            where id=$IdDetalle";
         $Ejecutar = pg_query($SQL);
         if (!$Ejecutar)
            return false;
         else
            return true;
      }//fin conectar
   }

// Fin función Recuperar Muestra
//Fn PG

   function ActualizarIndicacion($IdDetalle, $Indicacion, $ftomamx) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      $SQL = "UPDATE sec_detallesolicitudestudios
			  SET indicacion=$Indicacion,
                             f_tomamuestra='$ftomamx',
                             id_estado_rechazo=1,
                             f_estado=current_date
			  WHERE id=$IdDetalle;";

      $Ejecutar = pg_query($SQL);
      if (!$Ejecutar) {
         return false;
      } else
         return true;
   }

//Fn PG
   function BorrarSolicitud($idsolicitud) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      $SQL = "select count(*) from sec_detallesolicitudestudios
where idsolicitudestudio=$idsolicitud";
      $Ejecutar = pg_query($SQL);
      $cant = pg_fetch_array($Ejecutar);
      if ($cant[0] == 0) {
         $SQL0 = "delete from lab_recepcionmuestra where idsolicitudestudio=$idsolicitud";
         $Ejecutar0 = pg_query($SQL0);
         $SQL1 = "delete from cit_citas_serviciodeapoyo
                        where id_solicitudestudios=$idsolicitud";
         $Ejecutar1 = pg_query($SQL1);
         $SQL2 = "delete  from sec_solicitudestudios
                        where id=$idsolicitud";
         $Ejecutar2 = pg_query($SQL2);
         //  echo 'Eju: '.$SQL2;
      }

      if (!$Ejecutar) {
         return false;
      } else
         return true;
   }

   /*    * *************************************************** */

//Fn PG
   function Impresiones($Bandera, $IdHistorialClinico, $idsol) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();

      $sql = "update sec_solicitudestudios
            set Impresiones='$Bandera'
            where id_atencion= (select id from ctl_atencion where codigo_busqueda= 'DCOLAB')
            and id=$idsol;";
      $Ejecutar = pg_query($sql);
      if (!$Ejecutar) {
         return false;
      } else {
         return true;
      }
      /*
        $sql="update sec_solicitudestudios set Impresiones='".$Bandera."' where IdServicio='DCOLAB' and IdHistorialClinico='".$IdHistorialClinico."'"; */

      //$Ejecutar = mysql_query($sql) or die('La consulta fall&oacute;: ' . mysql_error());
   }

//Fn PG
   function SolicitudUrgente($Bandera, $IdHistorialClinico, $idsol) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();

      $sql = "update sec_solicitudestudios
            set idtiposolicitud='$Bandera'
            where id_atencion= (select id from ctl_atencion where codigo_busqueda= 'DCOLAB')
            and id=$idsol;";
      $Ejecutar = pg_query($sql);
      if (!$Ejecutar) {
         return false;
      } else {
         return true;
      }
      /*
        $sql="update sec_solicitudestudios set Impresiones='".$Bandera."' where IdServicio='DCOLAB' and IdHistorialClinico='".$IdHistorialClinico."'"; */

      //$Ejecutar = mysql_query($sql) or die('La consulta fall&oacute;: ' . mysql_error());
   }

   /*    * ********************************************************* */

   function FechaServer() {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $SQL = "SELECT now()";
         $db = mysql_query($SQL) or die('La consulta Fall&oacute;:' . mysql_error());
         $vareturn = mysql_fetch_array($db);
      }
      return $vareturn[0];
   }

//
// function FechaActual(){
//	$con = new ConexionBD;
//	if ($con->conectar()==true){
//		$SQL ="select DATE_FORMAT(current_date(),'%Y/%m/%d') as FechaServidor";
//		$db = mysql_query($SQL) or die('La consulta Fall&oacute;:' . mysql_error());
//		$vareturn = mysql_fetch_array($db);
//	}
//	return $vareturn[0];
// }
//
   function VerificarExisteSolicitud($IdSubServicio, $IdEmpleado, $FechaConsulta) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "select * from sec_historial_clinico where idsubservicio= $IdSubServicio AND id_empleado= $IdEmpleado AND fechaconsulta='$FechaConsulta'";
         $db = pg_num_rows($query);
         $result = pg_fetch_array($db);
      }
      if (!$result)
         return false;
      else
         return $result;
   }

   function CrearNuevaSolicitudUrgente($IdHistorialClinico, $IdEstablecimiento,
           $IdDetalle) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();

      $FechaActual = $this->FechaActual();
      $FechaServer = $this->FechaServer();
      $Detalles = explode(',', $IdDetalle);
      $tamano = sizeof($Detalles);
      $Bandera = 'S';

      $sql = "SELECT id_expediente, id as idsolicitudestudio, idusuarioreg
FROM sec_solicitudestudios sse
where id_historial_clinico=$IdHistorialClinico
and id_establecimiento=49;$IdEstablecimiento";
      $Ejecutar = pg_query($sql);
      if (!$Ejecutar)
         return false;


      while ($Answer = pg_fetch_array($Ejecutar)) {
         $NumeroExpediente = $Answer["id_expediente"];
         $IdSolicitudEstudiosNormal = $Answer["idsolicitudestudio"];
         $IdUsuarioReg = $Answer["idusuarioreg"];
      }

      //Se crea la Nueva Solicitud de Estudios para los examenes URGENTES

      $sqlInsert = "INSERT INTO sec_solicitudestudios (IdNumeroExp,IdHistorialClinico,Estado,FechaSolicitud,IdUsuarioReg,FechaHoraReg,IdServicio,IdEstablecimiento,IdTipoSolicitud)
                VALUES ('$NumeroExpediente','$IdHistorialClinico','D','$FechaActual','$IdUsuarioReg','$FechaServer','DCOLAB','$IdEstablecimiento','S')";
      $queryIns = mysql_query($sqlInsert) or die('La consulta fall&oacute;:' . mysql_error());
      $IdSolicitudEstudiosUrgente = mysql_insert_id();

      for ($e = 0; $tamano > $e; $e++) {
         $SQL = "UPDATE sec_detallesolicitudestudios SET IdSolicitudEstudio=$IdSolicitudEstudiosUrgente
                WHERE IdDetalleSolicitud=$Detalles[$e]";
         $Execute = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
      }

      $IdSolicitudEstudiosUrgentes = $this->RecuperarData($IdHistorialClinico,
              $IdEstablecimiento, $Bandera);

      //Insertar la Cita de Solicitud Urgente
      $sqlInsertCita = "INSERT INTO cit_citasxserviciodeapoyo (Fecha,IdSolicitudEstudio,IdUsuarioReg,FechaHoraReg,IdEstablecimiento)
                VALUES ('$FechaActual','$IdSolicitudEstudiosUrgentes','$IdUsuarioReg','$FechaServer','$IdEstablecimiento')";
      $query = mysql_query($sqlInsertCita) or die('La consulta fall&oacute;:' . mysql_error());
   }

// Fin función CrearNuevaSolicitudUrgente

   function SearchNuevaSolicitudUrgente($IdHistorialClinico, $IdEstablecimiento,
           $IdDetalleUrgentes) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();

      $Detalles = explode(",", $IdDetalleUrgentes);
      $size = sizeof($Detalles);
      $arreglo = array();
      $arregloultimo = array();
      $x = 0;
      $y = 0;

      $IdSolicitudEstudiosUrgente = $this->RecuperarData($IdHistorialClinico,
              $IdEstablecimiento, 'S');
      $IdSolicitudEstudios = $this->RecuperarData($IdHistorialClinico,
              $IdEstablecimiento, 'R');

      $SQL4 = "SELECT IdDetalleSolicitud  FROM sec_detallesolicitudestudios
            WHERE IdSolicitudEstudio =$IdSolicitudEstudiosUrgente AND IdEstablecimiento=$IdEstablecimiento";
      $Ejecutar4 = mysql_query($SQL4) or die("Warning...: La consulta Fallo ...!" . mysql_error());

      while ($DetallesActuales = mysql_fetch_array($Ejecutar4)) {
         $arreglo[$x] = $DetallesActuales[0];
         $x++;
      }

      //Devolver el IdDetalle deschequeado

      foreach ($arreglo as $value2) {
         $encontrado = false;
         foreach ($Detalles as $value1) {
            if ($value1 == $value2) {
               $encontrado = true;
               $break;
            }
         }
         if ($encontrado == false) {
            $arregloultimo[$y] = $value2;
            $y++;
         }
      }
      //print_r($arregloultimo);
      $tamano = sizeof($arregloultimo);
      for ($e = 0; $tamano > $e; $e++) {
         $SQL = "UPDATE sec_detallesolicitudestudios SET IdSolicitudEstudio=$IdSolicitudEstudios
                    WHERE IdDetalleSolicitud=$arregloultimo[$e] AND IdEstablecimiento=$IdEstablecimiento AND IdSolicitudEstudio=$IdSolicitudEstudiosUrgente";
         $Execute = mysql_query($SQL) or die('La consulta fall&oacute23;: ' . mysql_error());
      }

      //Agregar IdDetalle's nuevos que fueron chequeados
      for ($r = 0; $size > $r; $r++) {
         $SQLS = "UPDATE sec_detallesolicitudestudios SET IdSolicitudEstudio=$IdSolicitudEstudiosUrgente
                WHERE IdDetalleSolicitud=$Detalles[$r] AND IdEstablecimiento=$IdEstablecimiento AND IdSolicitudEstudio=$IdSolicitudEstudios";
         $Executes = mysql_query($SQLS) or die('La consulta fall&oacute24;: ' . mysql_error());
      }
   }

// Fin función SearchNuevaSolicitudUrgente

   function BorrarSolicitudUrgente($IdHistorialClinico, $IdEstablecimiento) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();

      $IdSolicitudEstudiosUrgente = $this->RecuperarData($IdHistorialClinico,
              $IdEstablecimiento, 'S');
      $IdSolicitudEstudios = $this->RecuperarData($IdHistorialClinico,
              $IdEstablecimiento, 'R');

      $SQL = "UPDATE sec_detallesolicitudestudios
SET idsolicitudestudio= $IdSolicitudEstudios
WHERE idsolicitudestudio=$IdSolicitudEstudiosUrgente
AND id_establecimiento =$IdEstablecimiento";
      $Execute = pg_query($SQL);

      $sql = "DELETE from cit_citasxserviciodeapoyo WHERE IdSolicitudEstudio=$IdSolicitudEstudiosUrgente";
      $Ejecutar = mysql_query($sql) or die('La consulta fall&oacute;: ' . mysql_error());

      $sqly = "DELETE from sec_solicitudestudios
            WHERE IdSolicitudEstudio=$IdSolicitudEstudiosUrgente AND IdHistorialClinico=$IdHistorialClinico AND IdEstablecimiento=$IdEstablecimiento";
      $Ejecutar = mysql_query($sqly) or die('La consulta fall&oacute;: ' . mysql_error());
   }

// Fin función BorrarSolicitudUrgente
//Fn PG

   function RecuperarData($IdHistorialClinico, $IdEstablecimiento, $Bandera) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         $sqlbusqueda = "select sse.id as idsol, lts.idtiposolicitud as idtiposol
                from sec_solicitudestudios sse
                join lab_tiposolicitud lts on (sse.idtiposolicitud=lts.id)
                where id_historial_clinico=$IdHistorialClinico
                and id_establecimiento=$IdEstablecimiento
                and lts.idtiposolicitud='$Bandera';";
         //select el nuevo IdSolicitudUrgente
         /* $sqlbusqueda=   "SELECT IdSolicitudEstudio FROM sec_solicitudestudios
           WHERE IdHistorialClinico=$IdHistorialClinico AND IdEstablecimiento=$IdEstablecimiento AND IdTipoSolicitud='$Bandera'"; */
         $querys = pg_query($sqlbusqueda);
         $row = pg_fetch_array($querys);
         if (!$querys) {
            return false;
         } else {
            return $row['idsol'];
         }
      }
   }

//fin recuperardata
}

// Fin de la Clase SolicitudLaboratorio

class Establecimiento {

   // Método Constructor de la clase SolicitudLaboratorio
   function Establecimiento() {

   }

   function IdEstablecimiento($IdUsuarioReg) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      $sql = "SELECT IdEstablecimiento FROM mnt_usuarios WHERE IdUser=$IdUsuarioReg";
      $ejecutar = mysql_query($sql) or die('La consulta fall&oacute ' . mysql_error());
      $campo = mysql_fetch_array($ejecutar);
      return $campo[0];
   }

}

//Fin clase Establecimiento
//PG
class CrearHistorialClinico {

   // Método Constructor de la clase SolicitudLaboratorio
   function Historia() {

   }

//Funcion PG
   function HistorialClinico($IdNumeroExp, $IdEstablecimiento, $IdSubServicio,
           $IdEmpleado, $FechaConsulta, $iduser, $ippc, $idexpediente, $lugar) {
      //$ippc=$_SERVER["REMOTE_ADDR"];
      if ($IdEstablecimiento == $lugar) {
         $seq = "SELECT nextval('sec_historial_clinico_id_seq');";
         $res = pg_query($seq);
         $row = pg_fetch_row($res);
         $idseq = $row[0];
         $sqlInsertCita = " insert into sec_historial_clinico (id, fechaconsulta, idsubservicio,        idusuarioreg, fechahorareg, piloto, ipaddress, idestablecimiento, id_numero_expediente, id_empleado)
values($idseq,'$FechaConsulta', $IdSubServicio, $iduser,date_trunc('seconds',NOW()), 'V', '$ippc', $lugar, $idexpediente, $IdEmpleado)";
         //echo $sqlInsertCita;
      } else {
         $seq = "SELECT nextval('mnt_dato_referencia_id_seq');";
         $res = pg_query($seq);
         $row = pg_fetch_row($res);
         $idseq = $row[0];
         $sqlInsertCita = "insert into mnt_dato_referencia(id, id_expediente_referido, id_empleado, id_aten_area_mod_estab, fecha_horareg, idusuarioreg, id_establecimiento)
values($idseq,$idexpediente, $IdEmpleado,$IdSubServicio, date_trunc('seconds',NOW()), $iduser, $lugar)";
         /*   $sqlInsertCita= "insert into sec_historial_clinico (id, fechaconsulta, idsubservicio,        idusuarioreg, fechahorareg, piloto, ipaddress, idestablecimiento, idnumeroexp, id_numero_expediente, id_empleado)
           values($idseq,'$FechaConsulta', $IdSubServicio, $iduser,NOW(), 'V', '$ippc', $lugar, '$IdNumeroExp', $idexpediente, $IdEmpleado)"; */
      }

      $query = pg_query($sqlInsertCita);
      if (!$query) {
         return false;
      } else {
         //echo '<br\>'.$sqlInsertCita.'<br\>';
         return $idseq;
      }
   }

//Funcion PG
   function buscarareas($lugar) {
      //$ippc=$_SERVER["REMOTE_ADDR"];
      $query = "select distinct(casd.id), nombrearea
                from ctl_area_servicio_diagnostico casd
                join mnt_area_examen_establecimiento mnt4 on (casd.id=mnt4.id_area_servicio_diagnostico)
                inner join lab_areasxestablecimiento lae ON lae.idarea = casd.id
            where id_establecimiento=$lugar
            and activo=true
            and administrativa='N'
            AND condicion = 'H'
            order by nombrearea;";
      $result = pg_query($query);
      //echo '<br/>'.$query.'<br/>';
      if (!$result) {
         return false;
      } else {

         return $result;
      }
   }

   function busca_mnt_area_exa_est($lugar, $idarea, $idsexo,
           $IdHistorialClinico, $idestab) {
      //$ippc=$_SERVER["REMOTE_ADDR"];
      // echo $idarea.'-area sexo-'.$idsexo.'<br/>';
      $query = "select distinct lcee.id as idconf, nombre_examen, mnt4.id_examen_servicio_diagnostico
            from mnt_area_examen_establecimiento mnt4
            join lab_conf_examen_estab lcee on (mnt4.id=lcee.idexamen)
            join lab_examen_suministrante lesu on (lcee.id=lesu.id_conf_examen_estab)
            join lab_suministrante lsum on (lsum.id = lesu.id_suministrante)
            where id_area_servicio_diagnostico=$idarea
            and mnt4.activo=true
            and lesu.activo=true
            and condicion= 'H'
            and id_establecimiento=$lugar
            and (idsexo is NULL or idsexo=$idsexo)
            and ubicacion=0
            and lcee.id not in (select id_conf_examen_estab
		  from sec_solicitudestudios sse
		  join sec_detallesolicitudestudios sds on (sse.id =sds.idsolicitudestudio)
		  where case $idestab
                                    when id_establecimiento then id_historial_clinico=$IdHistorialClinico
                                    else id_dato_referencia=$IdHistorialClinico
                            end)
            and (id_tipo_conexion in (select case when id_proceso_laboratorio=11 then 2 --hl7
						    when id_proceso_laboratorio=12  then 3 --base intermedia
						    else null
						end
					from lab_proceso_establecimiento t04
					where t04.id in (11,12)
					and t04.activo =true)
	        or id_tipo_conexion is null)
            order by nombre_examen asc;";
            /*Debido a que en lab_proceso_establecimiento se configura:
            el id_proceso_laboratorio = 11 activo si el establecimiento tiene equipos con conexion de equipos automatizados
            el id_proceso_laboratorio = 12 activo si el establecimiento tiene equipos con conexion de equipos por base intermedia
            Y la tabla suministrante tiene:
             id_tipo_conexion = 1 si es por medio de hl7
             id_tipo_conexion */
      $result = pg_query($query);
      if (!$result) {
         return false;
      } else {

         return $result;
      }
   }

   function buscarperfil($sexo) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         $query = "select distinct t1.id, t1.nombre
               from lab_perfil t1
               join lab_perfil_prueba t2 on (t1.id=id_perfil)
               join lab_perfil_sexo t3 on (t1.id=t3.id_perfil)
               where t1.habilitado=true
               and t2.habilitado=true
               and (t3.id_sexo=$sexo or id_sexo is null)
               order by t1.nombre;";
         $result = pg_query($query);
         if (!$result) {
            return false;
         } else {

            return $result;
         }
      }
   }

   function buscarperfilprueba($idperfil) {
      $Conexion = new ConexionBD();
      $conectar = $Conexion->conectar();
      if ($conectar == true) {
         $query = "select * from lab_perfil_prueba where id_perfil =$idperfil  and habilitado=true;";
         $result = pg_query($query);
         if (!$result) {
            return false;
         } else {
            return $result;
         }
      }
   }

//fin funcion buscarperfil



}

//Fin clase HistorialClinico
?>
