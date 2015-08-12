<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////
///     CLASE CONEXION A LA BASE DE DATOS									                 ////
///////////////////////////////////////////////////////////////////////////////////////////////////////


/*class ConexionBD{
 var $conect;
 //M�todo constructor
 function ConexionBD(){
 }
 
 function conectar() {
   if(!($con=mysql_connect("localhost","labor","clinic0"))){
     echo"Error al conectar a la base de datos";	
     exit();
   }
   if (!mysql_select_db("siap",$con)) {
     echo "Error al seleccionar la base de datos";  
     exit();
   }
   $this->conect=$con;
   return true;	
 }// Fin funci�n Conexion
 
} // FIN CLASE CONEXION*/


include_once("../../../../Conexion/ConexionBD.php");


///////////////////////////////////////////////////////////////////////////////////////////////////////
///     CLASE DE PACIENTES													                 ////
///////////////////////////////////////////////////////////////////////////////////////////////////////
class Paciente{


//M�todo constructor
function Paciente(){
}
	
function CalculoDias($Conectar,$fechanac){
     //$con = new ConexionBD;
   if($Conectar==true){ 
       	$query="SELECT DATEDIFF(NOW( ),'$fechanac')";
	 $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
   }
}

   function ObtenerCodigoRango($Conectar,$dias){
 
     if($Conectar==true){  
       $query="SELECT * FROM mnt_rangoedad WHERE $dias BETWEEN edadini AND edadfin
            AND idedad <>4";
        $result = @pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
        }     
}
	
	
	/*****************************************************************************************/
	/*Funci�n para  Recuperar Nombre del Paciente                                            */
	/*****************************************************************************************/
	function RecuperarNombre($Conectar,$IdNumeroExp,$IdSolicitudEstudio) { 
		if($Conectar==true){
							
			$SQL = /*"SELECT CONCAT_WS(' ', PrimerApellido, SegundoApellido, PrimerNombre, SegundoNombre, TercerNombre) AS Nombre,
                                NombreSubServicio AS Origen,
                                NombreServicio AS Procedencia, mnt_empleados.NombreEmpleado AS Medico, mnt_establecimiento.Nombre AS Establecimiento,
                                Sexo,FechaNacimiento
                                FROM mnt_datospaciente 
                                INNER JOIN mnt_expediente ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
                                INNER JOIN sec_solicitudestudios ON mnt_expediente.IdNumeroExp=sec_solicitudestudios.IdNumeroExp
                                INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
                                INNER JOIN mnt_empleados ON mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
                                INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
                                INNER JOIN mnt_servicio  ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
                                INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
                                WHERE mnt_expediente.IdNumeroExp='$IdNumeroExp' AND IdSolicitudEstudio='$IdSolicitudEstudio'";*/
                                
                                
                                "WITH tbl_servicio AS (
                        SELECT t02.id,
                            CASE WHEN t02.nombre_ambiente IS NOT NULL THEN  	
                                CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'-->' ||t02.nombre_ambiente
                                     ELSE t02.nombre_ambiente
                                END
                            ELSE
                                CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'--> ' || t01.nombre
                                     WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=t01.nombre) THEN t01.nombre
                                END
                            END AS servicio 
                        FROM  ctl_atencion 				    t01 
                        INNER JOIN mnt_aten_area_mod_estab              t02 ON (t01.id = t02.id_atencion)
                        INNER JOIN mnt_area_mod_estab 	   	    t03 ON (t03.id = t02.id_area_mod_estab)
                        LEFT  JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab)
                        LEFT  JOIN mnt_servicio_externo 		    t05 ON (t05.id = t04.id_servicio_externo)
                        WHERE id_area_atencion = 3 and t02.id_establecimiento = 49
                        ORDER BY 2)
                    select 
                            mem.nombreempleado as medico,
                            (SELECT nombre FROM ctl_establecimiento WHERE id = sse.id_establecimiento_externo) AS nombre_establecimiento,
                            case WHEN id_expediente_referido is  null then 
                                                              ( mex.numero)
                                                               else (mer.numero) end as numero,
                            case WHEN id_expediente_referido is  null then 
                                                              (csex.nombre)
                                                               else (csexpar.nombre) end as sexo,                                  
                            case WHEN id_expediente_referido is  null  THEN 
                                    CONCAT_WS(' ', pa.primer_nombre, NULL,pa.segundo_nombre,NULL,pa.primer_apellido,NULL,pa.segundo_apellido)
                                    else  
                                      CONCAT_WS(' ', par.primer_nombre, NULL,par.segundo_nombre,NULL,par.primer_apellido,NULL,par.segundo_apellido)end as paciente,
                           tser.servicio,
                            case WHEN id_expediente_referido is  null then 
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        AGE(pa.fecha_nacimiento::timestamp)::text,
                                                    'years', 'años'),
                                                'year', 'año'),
                                            'mons', 'meses'),
                                        'mon', 'mes'),
                                    'days', 'días'),
                                 'day', 'día') 
                            else 
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        AGE(par.fecha_nacimiento::timestamp)::text,
                                                    'years', 'años'),
                                                'year', 'año'),
                                            'mons', 'meses'),
                                        'mon', 'mes'),
                                    'days', 'días'),
                                 'day', 'día') end AS edad,
                            t01.nombre as procedencia
                    from ctl_area_servicio_diagnostico casd 
                    join mnt_area_examen_establecimiento mnt4     on (mnt4.id_area_servicio_diagnostico=casd.id )
                    join lab_conf_examen_estab lcee 	      	  on (mnt4.id=lcee.idexamen) 
                    INNER JOIN sec_detallesolicitudestudios sdses ON (sdses.id_conf_examen_estab=lcee.id)
                    INNER JOIN sec_solicitudestudios sse          ON (sdses.idsolicitudestudio=sse.id) 
                    INNER JOIN lab_recepcionmuestra lrc           ON (sse.id= lrc.idsolicitudestudio )
                    INNER JOIN sec_historial_clinico shc 	  ON (sse.id_historial_clinico=shc.id )
                    inner join mnt_empleado mem 		  on (shc.id_empleado=mem.id) 
                    inner join mnt_expediente mex 		  on (shc.id_numero_expediente=mex.id) 
                    inner join mnt_paciente pa 			  on (mex.id_paciente=pa.id) 
                    inner join ctl_sexo csex 			  on (csex.id=pa.id_sexo) 
                    inner join mnt_aten_area_mod_estab mnt3 	  on (shc.idsubservicio=mnt3.id) 
                    inner join mnt_area_mod_estab m1 		  on (mnt3.id_area_mod_estab=m1.id) 
                    inner join ctl_atencion cat 		  on (mnt3.id_atencion=cat.id)
                    inner join tbl_servicio tser                  on (tser.id = mnt3.id AND tser.servicio IS NOT NULL)
                    inner join ctl_establecimiento ce 		  on (shc.idestablecimiento=ce.id ) 
                    inner join ctl_area_atencion t01 		  on ( m1.id_area_atencion=t01.id) 
                    inner join lab_recepcionmuestra lrm 	  on (sse.id=lrm.idsolicitudestudio) 
                    left join sec_diagnosticospaciente sdp 	  on (shc.id=sdp.idhistorialclinico) 
                    left join mnt_cie10 mc 			  on (mc.id=sdp.iddiagnostico1) 
                    left join sec_examenfisico sef 		  on (sef.idhistorialclinico=shc.id) 
                    LEFT  JOIN mnt_dato_referencia  mdr           on (sse.id_dato_referencia=mdr.id)
                    LEFT JOIN mnt_expediente_referido mer         on (mdr.id_expediente_referido=mer.id)
                    LEFT JOIN mnt_paciente_referido par   	  ON (mer.id_referido=par.id) 
                    left join ctl_sexo csexpar 			  on (csexpar.id=par.id_sexo)  
                    where  case WHEN id_expediente_referido is null then (mex.numero='$IdNumeroExp') 
                    ELSE (mer.numero='$IdNumeroExp') END  and sse.id=$IdSolicitudEstudio";
                        
                       // echo $SQL;
			
                                $Resultado = pg_query($SQL);
			
                                return $Resultado;		
		}
	}
	
} // Fin de la Clase PACIENTE

	




///////////////////////////////////////////////////////////////////////////////////////////////////////
///     CLASE DE LABORATORIO												                 ////
///////////////////////////////////////////////////////////////////////////////////////////////////////
class Laboratorio{


	//M�todo constructor
	function Paciente(){
	}
	
	/******************************************************************************************/
	/*    Funci�n para  Recuperar Las solicitudes de Laboratorio                              */
	/******************************************************************************************/
	
	
	
	
	function SolicitudesLaboratorio($Conectar,$IdNumeroExp,$Pagina) { 
	
	// Paginacion de la consulta
	  $NoPagina=$Pagina;	
	  $RegistrosAMostrar=5;
	  if(isset($_POST['pag'])){
  			$RegistrosAEmpezar=($_POST['pag']-1)*$RegistrosAMostrar;
			$PagAct=$_POST['pag'];
	  //caso contrario los iniciamos
	 }else{
	  $RegistrosAEmpezar=0;
	  $PagAct=1;
	 }
	
	 
		if($Conectar==true){
			/**** Consultar General***/
			$SQL = /*"select  DATE_FORMAT(FechaSolicitud,'%e/ %m / %Y') as FechaSolicitud,
							case 1 when Estado='D' then 'No Procesada' 
					               when Estado='P' then 'No Completada'
								   when Estado='R' then 'No Procesada(Solicitud Recibida)' 
							       when Estado='C' then 'Procesada' END as Estado,
					        	   NombreEmpleado, NombreSubEspecialidad,NombreEmpleado, sec_solicitudestudios.IdSolicitudEstudio, 
								   FechaSolicitud as Fecha
					FROM sec_historial_clinico INNER JOIN sec_solicitudestudios
							   ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico			  
	          				   INNER JOIN mnt_empleados
			   				   ON mnt_empleados.IdEmpleado= sec_historial_clinico.IdEmpleado
  							   INNER JOIN mnt_subespecialidad
			   				   ON sec_historial_clinico.IdSubEspecialidad= mnt_subespecialidad.IdSubEspecialidad
					WHERE sec_historial_clinico.IdNumeroExp='$IdNumeroExp' AND 
						  sec_solicitudestudios.IdServicio='DCOLAB'
					ORDER BY Fecha desc LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";	*/
                                
                                "select 
                            mem.nombreempleado as medico,
                            (SELECT nombre FROM ctl_establecimiento WHERE id = sse.id_establecimiento_externo) AS nombre_establecimiento,
                            case WHEN id_expediente_referido is  null then 
                                                              ( mex.numero)
                                                               else (mer.numero) end as numero,
                            case WHEN id_expediente_referido is  null then 
                                                              (csex.nombre)
                                                               else (csexpar.nombre) end as sexo,                                  
                            case WHEN id_expediente_referido is  null  THEN 
                                    CONCAT_WS(' ', pa.primer_nombre, NULL,pa.segundo_nombre,NULL,pa.primer_apellido,NULL,pa.segundo_apellido)
                                    else  
                                      CONCAT_WS(' ', par.primer_nombre, NULL,par.segundo_nombre,NULL,par.primer_apellido,NULL,par.segundo_apellido)end as paciente,
                             t01.nombre as procedencia,
                             CASE sse.estado 
				 WHEN 1  THEN 'Diditada' 
                                 WHEN 2  THEN 'No Procesada(Solicitud Recibida)' 
                                 WHEN 3  THEN 'No Completada'
                                 WHEN 4  THEN 'Completa'
                                 WHEN 5  THEN 'Procesar Muestra'
                                 WHEN 6  THEN 'Muestra Rechazada'
                                 WHEN 7  THEN 'Resultado Completo'
                            END AS estado,  
				TO_CHAR(lrc.fecharecepcion, 'DD/MM/YYYY') as fecharecepcion,
				lcee.codigo_examen
                    from ctl_area_servicio_diagnostico casd 
                    join mnt_area_examen_establecimiento mnt4     on (mnt4.id_area_servicio_diagnostico=casd.id )
                    join lab_conf_examen_estab lcee 	      	  on (mnt4.id=lcee.idexamen) 
                    INNER JOIN sec_detallesolicitudestudios sdses ON (sdses.id_conf_examen_estab=lcee.id)
                    INNER JOIN sec_solicitudestudios sse          ON (sdses.idsolicitudestudio=sse.id) 
                    INNER JOIN lab_recepcionmuestra lrc           ON (sse.id= lrc.idsolicitudestudio )
                    INNER JOIN sec_historial_clinico shc 	  ON (sse.id_historial_clinico=shc.id )
                    inner join mnt_empleado mem 		  on (shc.id_empleado=mem.id) 
                    inner join mnt_expediente mex 		  on (shc.id_numero_expediente=mex.id) 
                    inner join mnt_paciente pa 			  on (mex.id_paciente=pa.id) 
                    inner join ctl_sexo csex 			  on (csex.id=pa.id_sexo) 
                    inner join mnt_aten_area_mod_estab mnt3 	  on (shc.idsubservicio=mnt3.id) 
                    inner join mnt_area_mod_estab m1 		  on (mnt3.id_area_mod_estab=m1.id) 
                    inner join ctl_atencion cat 		  on (mnt3.id_atencion=cat.id)
                   -- inner join tbl_servicio tser                  on (tser.id = mnt3.id AND tser.servicio IS NOT NULL)
                    inner join ctl_establecimiento ce 		  on (shc.idestablecimiento=ce.id ) 
                    inner join ctl_area_atencion t01 		  on ( m1.id_area_atencion=t01.id) 
                    inner join lab_recepcionmuestra lrm 	  on (sse.id=lrm.idsolicitudestudio) 
                    left join sec_diagnosticospaciente sdp 	  on (shc.id=sdp.idhistorialclinico) 
                    left join mnt_cie10 mc 			  on (mc.id=sdp.iddiagnostico1) 
                    left join sec_examenfisico sef 		  on (sef.idhistorialclinico=shc.id) 
                    LEFT  JOIN mnt_dato_referencia  mdr           on (sse.id_dato_referencia=mdr.id)
                    LEFT JOIN mnt_expediente_referido mer         on (mdr.id_expediente_referido=mer.id)
                    LEFT JOIN mnt_paciente_referido par   	  ON (mer.id_referido=par.id) 
                    left join ctl_sexo csexpar 			  on (csexpar.id=par.id_sexo)  
                    where  case WHEN id_expediente_referido is null then (mex.numero='$IdNumeroExp') 
                    ELSE (mer.numero='$IdNumeroExp') END  ORDER BY fecharecepcion desc LIMIT $RegistrosAMostrar offset  $RegistrosAEmpezar";
			
			
			
			$Ejecutar = pg_query($SQL);
	
			 $SQL2 = /*"select  DATE_FORMAT(FechaSolicitud,'%e/ %m / %Y') as FechaSolicitud,
							case 1 when Estado='D' then 'No Procesada' 
					               when Estado='P' then 'No Completada'
								   when Estado='R' then 'No Procesada(Solicitud Recibida)' 
							       when Estado='C' then 'Procesada' END as Estado,
					        	   NombreEmpleado, NombreSubEspecialidad,NombreEmpleado, sec_solicitudestudios.IdSolicitudEstudio
					FROM sec_historial_clinico INNER JOIN sec_solicitudestudios
							   ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico			  
	          				   INNER JOIN mnt_empleados
			   				   ON mnt_empleados.IdEmpleado= sec_historial_clinico.IdEmpleado
  							   INNER JOIN mnt_subespecialidad
			   				   ON sec_historial_clinico.IdSubEspecialidad= mnt_subespecialidad.IdSubEspecialidad
					WHERE sec_historial_clinico.IdNumeroExp='$IdNumeroExp' AND 
						  sec_solicitudestudios.IdServicio='DCOLAB' ";	*/
                                 "select 
                            mem.nombreempleado as medico,
                            (SELECT nombre FROM ctl_establecimiento WHERE id = sse.id_establecimiento_externo) AS nombre_establecimiento,
                            case WHEN id_expediente_referido is  null then 
                                                              ( mex.numero)
                                                               else (mer.numero) end as numero,
                            case WHEN id_expediente_referido is  null then 
                                                              (csex.nombre)
                                                               else (csexpar.nombre) end as sexo,                                  
                            case WHEN id_expediente_referido is  null  THEN 
                                    CONCAT_WS(' ', pa.primer_nombre, NULL,pa.segundo_nombre,NULL,pa.primer_apellido,NULL,pa.segundo_apellido)
                                    else  
                                      CONCAT_WS(' ', par.primer_nombre, NULL,par.segundo_nombre,NULL,par.primer_apellido,NULL,par.segundo_apellido)end as paciente,
                             t01.nombre as procedencia,
                             CASE sse.estado 
				 WHEN 1  THEN 'Diditada' 
                                 WHEN 2  THEN 'No Procesada(Solicitud Recibida)' 
                                 WHEN 3  THEN 'No Completada'
                                 WHEN 4  THEN 'Completa'
                                 WHEN 5  THEN 'Procesar Muestra'
                                 WHEN 6  THEN 'Muestra Rechazada'
                                 WHEN 7  THEN 'Resultado Completo'
                            END AS estado,  
				TO_CHAR(lrc.fecharecepcion, 'DD/MM/YYYY') as fecharecepcion,
				lcee.codigo_examen
                    from ctl_area_servicio_diagnostico casd 
                    join mnt_area_examen_establecimiento mnt4     on (mnt4.id_area_servicio_diagnostico=casd.id )
                    join lab_conf_examen_estab lcee 	      	  on (mnt4.id=lcee.idexamen) 
                    INNER JOIN sec_detallesolicitudestudios sdses ON (sdses.id_conf_examen_estab=lcee.id)
                    INNER JOIN sec_solicitudestudios sse          ON (sdses.idsolicitudestudio=sse.id) 
                    INNER JOIN lab_recepcionmuestra lrc           ON (sse.id= lrc.idsolicitudestudio )
                    INNER JOIN sec_historial_clinico shc 	  ON (sse.id_historial_clinico=shc.id )
                    inner join mnt_empleado mem 		  on (shc.id_empleado=mem.id) 
                    inner join mnt_expediente mex 		  on (shc.id_numero_expediente=mex.id) 
                    inner join mnt_paciente pa 			  on (mex.id_paciente=pa.id) 
                    inner join ctl_sexo csex 			  on (csex.id=pa.id_sexo) 
                    inner join mnt_aten_area_mod_estab mnt3 	  on (shc.idsubservicio=mnt3.id) 
                    inner join mnt_area_mod_estab m1 		  on (mnt3.id_area_mod_estab=m1.id) 
                    inner join ctl_atencion cat 		  on (mnt3.id_atencion=cat.id)
                   -- inner join tbl_servicio tser                  on (tser.id = mnt3.id AND tser.servicio IS NOT NULL)
                    inner join ctl_establecimiento ce 		  on (shc.idestablecimiento=ce.id ) 
                    inner join ctl_area_atencion t01 		  on ( m1.id_area_atencion=t01.id) 
                    inner join lab_recepcionmuestra lrm 	  on (sse.id=lrm.idsolicitudestudio) 
                    left join sec_diagnosticospaciente sdp 	  on (shc.id=sdp.idhistorialclinico) 
                    left join mnt_cie10 mc 			  on (mc.id=sdp.iddiagnostico1) 
                    left join sec_examenfisico sef 		  on (sef.idhistorialclinico=shc.id) 
                    LEFT  JOIN mnt_dato_referencia  mdr           on (sse.id_dato_referencia=mdr.id)
                    LEFT JOIN mnt_expediente_referido mer         on (mdr.id_expediente_referido=mer.id)
                    LEFT JOIN mnt_paciente_referido par   	  ON (mer.id_referido=par.id) 
                    left join ctl_sexo csexpar 			  on (csexpar.id=par.id_sexo)  
                    where  case WHEN id_expediente_referido is null then (mex.numero='$IdNumeroExp') 
                    ELSE (mer.numero='$IdNumeroExp') END  ORDER BY fecharecepcion desc LIMIT $RegistrosAMostrar offset  $RegistrosAEmpezar";
		
		   	$Ejecutar2 = pg_query($SQL2);
			$NroRegistros=mysql_num_rows($Ejecutar2);			
	 		
			
			echo "<div  class='outer' > 
					  <div class='inner' >    
						  <h3 align='left'>HISTORIAL DE RESULTADOS DE ESTUDIOS DE LABORATORIO</h3><br><br><br>
						  <table  cellspacing='1' cellpadding='3' border='0' align='center'>
						  <tr>
						      <td class='StormyWeatherFieldCaptionTD' nowrap><strong>Fecha de Consulta&nbsp; </td>
						      <td class='StormyWeatherFieldCaptionTD' nowrap><strong>Medico</font></td>
						      <td class='StormyWeatherFieldCaptionTD'  nowrap><strong>Especialidad</strong></td>
						      <td class='StormyWeatherFieldCaptionTD'  nowrap><strong>Estado Solicitud&nbsp;</strong></td>
  		 			      </tr>";
						  
			while ($Resultado = pg_fetch_array($Ejecutar)){
			echo "<tr>
			      <td class='StormyWeatherFieldCaptionTD'>
  			     <a  href='javascript:MostrarDetalleSolicitud(\"".$Resultado[5]."\", \"".$Resultado[0]."\") '>".$Resultado[0]."</td>
			      <td class='StormyWeatherFieldCaptionTD'>".$Resultado[2]."</td>
			      <td  class='StormyWeatherDataTD'>".$Resultado[3]."</td>
  			      <td  class='StormyWeatherDataTD'>".$Resultado[1]."</td>
			    </tr>";

			  } // Fin While Scar Datos
			  pg_free_result($Ejecutar); // Liberar memoria usada por consulta.

		} //Fin If Conectar
			echo"
				 <tr>
				      <td class='SaladDataTD' colspan='4'></td>
			    </tr>
			    <tr>
				   <td class='SaladColumnTD'  nowrap  colspan='4'>";
				   $this->Paginacion($IdNumeroExp,$NroRegistros,$PagAct,$RegistrosAMostrar);
			echo "</td>
			    </tr>
			</table><br><br>
		  </div>";
		
		echo "</div>";			
			
	} // Termina Funcion Solicitudes Laboratorios
	
	
	
	
	
	
	
	/******************************************************************************************/
	/*    Funci�n para  Recuperar El Detalle de las solicitudes de Laboratorio                 */
	/******************************************************************************************/
	function DetalleSolicitudLaboratorio($IdSolicitud,$FechaSolicitud,$Conectar) { 
		if($Conectar==true){
			$SQL = "SELECT lab_examenes.IdArea,NombreArea,count(lab_examenes.IdArea) as 	Total,
							sec_detallesolicitudestudios.IdSolicitudEstudio,sec_detallesolicitudestudios.EstadoDetalle,
							sec_detallesolicitudestudios.Observacion
					FROM sec_detallesolicitudestudios 
			  				    INNER JOIN lab_examenes 
			 				    ON lab_examenes.IdExamen=sec_detallesolicitudestudios.IdExamen
				  				INNER JOIN lab_areas
				  				ON lab_examenes.IdArea=lab_areas.IdArea
		 			WHERE sec_detallesolicitudestudios.IdSolicitudEstudio='$IdSolicitud'
						group by lab_examenes.IdArea order by lab_examenes.IdArea";
											  

			$Ejecutar = pg_query($SQL);

			echo "<div class='outer'> 
					  <div class='inner' >        
				  <br><br><br> <h3 align='center'>DETALLE DE SOLICITUD POR AREA CORRESPONDIENTE AL ".$FechaSolicitud ."  </h3>
					<table cellspacing='1' cellpadding='3' border='0' align='center'>
				  <tr>
				      <td class='SaladColumnTD' nowrap><font><strong>Nombre del Area</strong></font> </td>
				      <td class='SaladColumnTD' nowrap><font><strong>Total de Examenes Solicitados</strong></font></td>
				      <td class='SaladColumnTD' nowrap><font><strong>Consultar Resultados</strong></font></td>
			      </tr>";
			$Count=0;
			
			while ($Resultado = pg_fetch_array($Ejecutar)){
 	  		   echo "<tr>
			   		   <td class='SaladFieldCaptionTD'>".htmlentities ($Resultado[1])."</td>
			           <td class='SaladFieldCaptionTD' align='center'>".$Resultado[2]."</td>
					    <td class='SaladFieldCaptionTD'>";
				if($Resultado['EstadoDetalle']=='RC'){		
	    			echo"	   <a  href='javascript:MostrarResultadosExamen(\"".$Resultado[3]."\",\"".$Resultado[0]."\") '>
 				    Ver Resultado" ;
					$Count+=$Resultado[2];
				}
				else{

				     echo "Resultado No disponible: ".$Resultado["Observacion"];	 
				}
				echo " </a></td>					
			        </tr>";

			  } // Fin While Sacar Datos
			  
			  pg_free_result($Ejecutar); // Liberar memoria usada por consulta.
			  if($Count==0)
				echo "<tr><td class='SaladFieldCaptionTD' colspan='4'>&nbsp; </td></tr>"."
				 	 </tr><td class='SaladColumnTD' colspan='4'>No se Encontro Ningun Registro Procesado...!</td></tr></table>";
			  else	
   				echo "<tr><td class='SaladColumnTD' colspan='4'>Se Econtraron  ".$Count."  Examen(es) Procesado(s)</td></tr></table><br><br></div></div>";
				
				echo "<br><br><div id='DetalleResultados'>
					  </div>";
		} //Fin If Conectar


	} // Termina Funcion Detalle Solicitudes Laboratorios
	
	







	/******************************************************************************************/
	/*    Funci�n para  Resultados  de los examenes de Laboratorio                            */
	/******************************************************************************************/
	function ResultadosExamen($IdSolicitudEstudio,$IdArea,$Sexo,$idedad,$Conectar,$IdEstab,$lugar) { 
                                                
	// Esta Consulta muestra todos los examenes que tienen un resultado
	$SQL = /*"SELECT lab_resultados.IdExamen,lab_resultados.IdRecepcionMuestra, lab_examenesxestablecimiento.IdPlantilla,
                lab_resultados.Resultado,Lectura,lab_resultados.Interpretacion,lab_resultados.Observacion,NombreExamen,
		lab_areas.NombreArea,lab_resultados.IdRecepcionMuestra,Unidades,RangoInicio,RangoFin, 	
		lab_examenesxestablecimiento.IdPlantilla,lab_estadosdetallesolicitud.Descripcion,lab_resultados.Interpretacion,
		sec_detallesolicitudestudios.IdDetalleSolicitud,						  
		sec_detallesolicitudestudios.EstadoDetalle,mnt_empleados.NombreEmpleado,DATE_FORMAT(lab_resultados.FechaHoraReg ,'%e/ %m / %Y')  as FechaResultado		
		FROM lab_resultados 
		INNER JOIN lab_examenes ON lab_resultados.IdExamen=lab_examenes.IdExamen	
		INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen		
		INNER JOIN lab_datosfijosresultado ON lab_datosfijosresultado.IdExamen=lab_examenes.IdExamen								  
		INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
	 	INNER JOIN sec_detallesolicitudestudios ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud
		INNER JOIN lab_estadosdetallesolicitud ON lab_estadosdetallesolicitud.IdEstadoDetalle= sec_detallesolicitudestudios.EstadoDetalle
        	INNER JOIN mnt_empleados ON lab_resultados.Responsable=mnt_empleados.IdEmpleado	  									 
		WHERE (lab_datosfijosresultado.idsexo=$Sexo OR lab_datosfijosresultado.idsexo=3) 
                AND (idedad=4 OR idedad=$idedad) 
                AND DATE_FORMAT(lab_resultados.FechaHoraReg, '%Y/%m/%d') BETWEEN lab_datosfijosresultado.FechaIni 
                AND IF(lab_datosfijosresultado.FechaFin='0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin) 
                AND lab_datosfijosresultado.IdEstablecimiento=$lugar 
                AND lab_resultados.IdSolicitudEstudio='$IdSolicitudEstudio' 
                AND lab_examenes.IdArea='$IdArea'";*/
                
                "SELECT	lr.idexamen,			  
lr.idrecepcionmuestra,			
lr.resultado,lectura,			
lr.interpretacion,			
lr.observacion,				
idrecepcionmuestra			
FROM lab_resultados lr
INNER JOIN ctl_examen_servicio_diagnostico cesd ON lr.idexamen=cesd.id	
INNER JOIN lab_conf_examen_estab lcee 		ON cesd.id=lcee.idexamen		
INNER JOIN lab_datosfijosresultado 	ldf	ON ldf.id_conf_examen_estab=lcee.id
INNER JOIN mnt_area_examen_establecimiento mnt4 ON (mnt4.id=lcee.idexamen) 
INNER JOIN ctl_area_servicio_diagnostico casd   ON (mnt4.id_area_servicio_diagnostico=casd.id) 
INNER JOIN sec_detallesolicitudestudios sdse on sdse.id=lr.iddetallesolicitud
WHERE (ldf.idsexo=$Sexo OR ldf.idsexo=3) 
AND (ldf.idedad=4 OR ldf.idedad=$idedad) 
AND DATE_FORMAT(lr.FechaHoraReg, '%Y/%m/%d') 
BETWEEN lab_datosfijosresultado.FechaIni 
AND IF(ldf.fechafin='0000-00-00',CURDATE(),ldf.fechafin) 
AND ldf.idestablecimiento=$lugar 
AND lr.idsolicitudestudio=$IdSolicitudEstudio 
AND casd.id=$IdArea";
                
                
                
                   // echo $SQL;
	// Esta Consulta muestra todos los examenes que no fueron Procesados
	$SQL2="SELECT lab_examenes.IdExamen,NombreExamen,Descripcion,sec_detallesolicitudestudios.Observacion
               FROM  sec_detallesolicitudestudios 
               INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
               INNER JOIN lab_estadosdetallesolicitud ON lab_estadosdetallesolicitud.IdEstadoDetalle=sec_detallesolicitudestudios.EstadoDetalle
	       WHERE IdSolicitudEstudio=$IdSolicitudEstudio AND EstadoDetalle!='RC'
	       AND IdArea='$IdArea'
			Order By lab_examenes.IdExamen";
		// Si la Conexion es Buena, entonces ejecutamos la consulta (17-1 Parametros de la Consulta)
		if($Conectar==true){							  
			$Aceptados = pg_query($SQL);
			$Rechazados = pg_query($SQL2);
			$Count=0;

		 //	 Impresion de resultados segun la plantilla
			while ($Resultado = pg_fetch_array($Aceptados)){
	
				if($Resultado[2]=='A'){
					$ResultadoPlantillaA[]=$Resultado;					
					$Count++;				
				}
									
				elseif($Resultado[2]=='B')
					$ResultadoPlantillaB[]=$Resultado;
					
				elseif($Resultado[2]=='C')
					$ResultadoPlantillaC[]=$Resultado;

				elseif($Resultado[2]=='D')
					$ResultadoPlantillaD[]=$Resultado;
				
				elseif($Resultado[2]=='E')
					$ResultadoPlantillaE[]=$Resultado;
					
		  } // Fin While Sacar Datos
			$Count=0;	
		 /*******************************************************************************************************/
		 /*                            Mostrar Examenes Rechazados	                                    */
 		 /*******************************************************************************************************/
		  	echo "<div class='outer'> 
					  <div class='inner'>";  
					  
			if(isset($ResultadoPlantillaA))		  
					echo "<br><br> <h3 align='center'>EXAMENES REALIZADOS PARA EL AREA DE ----> ".
											htmlentities($ResultadoPlantillaA[0]['NombreArea'])."<----- <input type='button' value='CERRAR' onclick='window.close();'></h3>";
			elseif (isset($ResultadoPlantillaB))		  
				echo "<br><br> <h3 align='center'>EXAMENES REALIZADOS PARA EL AREA DE ----> ".
											htmlentities($ResultadoPlantillaB[0]['NombreArea'])."<----- <input type='button' value='CERRAR' onclick='window.close();'></h3>";
			elseif (isset($ResultadoPlantillaC))		  
				echo "<br><br> <h3 align='center'>EXAMENES REALIZADOS PARA EL AREA DE ----> ".
											htmlentities($ResultadoPlantillaC[0]['NombreArea'])."<----- <input type='button' value='CERRAR' onclick='window.close();'></h3>";
			elseif (isset($ResultadoPlantillaD))		  
				echo "<br><br> <h3 align='center'>EXAMENES REALIZADOS PARA EL AREA DE ----> ".
											htmlentities($ResultadoPlantillaD[0]['NombreArea'])."<----- <input type='button' value='CERRAR' onclick='window.close();'></h3>";
			elseif (isset($ResultadoPlantillaE))		  
				echo "<br><br> <h3 align='center'>EXAMENES REALIZADOS PARA EL AREA DE ----> ".
											htmlentities($ResultadoPlantillaE[0]['NombreArea'])."<----- <input type='button' value='CERRAR' onclick='window.close();'></h3>";
			else
				echo "<br><br> <h3 align='center'>ESTOS EXAMENES NO TIENEN RESULTADOS<----- <input type='button' value='CERRAR' onclick='window.close();'> </h3> ";

												
			/**/echo"<table class='SaladColumnTD'  cellspacing='1' cellpadding='3' border='0' align='center'>
					  <tr  class='CobaltFieldCaptionTD'>
						  <td class='SaladColumnTD' colspan='1' nowrap><font><strong>IdExamen</strong></font> </td>				   				
					  	  <td class='SaladColumnTD'  colspan='1' nowrap><font><strong>Nombre del Examen</strong></font> </td>				   						  <td class='SaladColumnTD'  colspan='1' nowrap><font><strong>Estado</strong></font> </td>
						  <td class='SaladColumnTD'  colspan='1' nowrap><font><strong>Causa Rechazo</strong></font> </td>
					  </tr>";		
					  
			while ($ResultadosRechazados = pg_fetch_array($Rechazados)){
				echo " 
						   <tr>
							   <td class='SaladFieldCaptionTD'> ".$ResultadosRechazados['IdExamen']."</td>
							   <td class='SaladFieldCaptionTD'>".htmlentities($ResultadosRechazados['NombreExamen'])."</td>
							   <td class='SaladFieldCaptionTD'>".$ResultadosRechazados['Descripcion']."</td>
							   <td class='SaladFieldCaptionTD'>".$ResultadosRechazados['Observacion']."</td>
							</tr> ";
				$Count++;
			}
			
			if($Count==0)
				echo "<tr><td class='SaladFieldCaptionTD' colspan='4'>Todos fueron Procesados</td></tr>
					  <tr><td class='SaladColumnTD' colspan='8'>$Count Examen(es) Fueron rechazados </td>
					  </tr></table>";
			else
				echo "<tr><td class='SaladColumnTD' colspan='8'>$Count Examen(es) Fueron rechazados </td>
					  </tr></table>";/**/
		$Count=0;
		
		 /*******************************************************************************************************/
		 /*                            Mostrar Detalle de la plantilla A	                                    */
 		 /*******************************************************************************************************/
			if(isset($ResultadoPlantillaA)){
				$this->TablaPlantillaA($ResultadoPlantillaA);
 	  		}  // Fin if PlantillaA
			$Count=0;
			
		 /*******************************************************************************************************/
		 /*                            Mostrar Detalle de la plantilla B	                                    */
 		 /*******************************************************************************************************/
			if(isset($ResultadoPlantillaB)){
				$this->TablaPlantillaB($ResultadoPlantillaB,$Sexo,$idedad);
 	  		}  // Fin if PlantillaB
			
 	     /******************************************************************************************************/
		 /*                            Mostrar Detalle de la plantilla C		                               */
 		 /******************************************************************************************************/
			if(isset($ResultadoPlantillaC)){
				$this->TablaPlantillaC($ResultadoPlantillaC,$IdSolicitudEstudio,$Conectar);
			}  // Fin if Plantilla C
		 
		 /******************************************************************************************************/
		 /*                            Mostrar Detalle de la plantilla D		                               */
 		 /******************************************************************************************************/
			if(isset($ResultadoPlantillaD)){
				$this->TablaPlantillaD($ResultadoPlantillaD,$IdSolicitudEstudio,$Conectar);
			}  // Fin if Plantilla D
		
		 /******************************************************************************************************/
		 /*                            Mostrar Detalle de la plantilla D		                               */
 		 /******************************************************************************************************/
			if(isset($ResultadoPlantillaE)){
				$this->TablaPlantillaE($ResultadoPlantillaE,$IdSolicitudEstudio,$Conectar);
			}  // Fin if Plantilla D
			
			
			
			echo "</div></div>
					  <div id='PlantillaB'></div>";
			

			$Count=0;
			
			  pg_free_result($Aceptados); //  Liberar memoria usada por consulta.
  			  pg_free_result($Rechazados); // Liberar memoria usada por consulta.
		} //Fin If Conectar


	} // Termina Funcion Resultados Examenes
	
	
	
	
	/*********************************************************************************************/
	/*  Dibujar Tabla con las diferentes plantillas de resultados de Laboratorio				 */
	/*********************************************************************************************/
	///////////////////////////////////////////////////////////////////////////////////////////////
	///      Plantilla A																		 //
	///////////////////////////////////////////////////////////////////////////////////////////////		
	function TablaPlantillaA($ResultadoPlantillaA){
		$Count=0;
		echo "
					   <br><br> <h2 align='center'> Plantilla A </h2>
							<table cellspacing='1' cellpadding='3' border='0' align='center'>
						  <tr  class='CobaltFieldCaptionTD'>
					<td colspan='1' nowrap><font><strong>Prueba Realizada</strong></font></td>				  <td   colspan='1' nowrap><font><strong>Resultado</strong></font> </td>
					  <td  colspan='1' nowrap><font><strong>Unidades</strong></font> </td>
					  <td  colspan='1' nowrap><font><strong>Rangos Normales</strong></font> </td>	
					  <td  colspan='1' nowrap><font><strong>Lectura</strong></font></td>
					   
					  	      				  <td  colspan='1' nowrap><font><strong>Interpretaci&oacute;n</strong></font> </td>				   					  <td  colspan='2' nowrap><font><strong>Observaci&oacute;n</strong></font> </td>

					  <td  colspan='1' nowrap><font><strong>Estado</strong></font> </td>	
<td  colspan='1' nowrap><font><strong>Validado por</strong></font></td> 					  
<td  colspan='1' nowrap><font><strong>Fecha Resultado</strong></font>
 </td>				   				
						  </tr>";		
				
				foreach($ResultadoPlantillaA as $Fila )
				{
						echo " 
							    <tr>
								   <td > ".htmlentities($Fila['NombreExamen'])."</td>
								   <td align='center'> <font>".$Fila['Resultado']."</font></td>
								   <td>".$Fila['Unidades']."</td>
								   <td>".$Fila['RangoInicio']." - ".$Fila['RangoFin']."</td>
								   <td> ".$Fila['Lectura']."</td>
								   <td>".$Fila['Interpretacion']."</td>
								   <td colspan='2'>".$Fila['Observacion']."</td>
								   <td>".$Fila['Descripcion']."</td>
								   <td>".$Fila['NombreEmpleado']."</td>
								   <td>".$Fila['FechaResultado']."</td>
								</tr> ";
	
						 $Count++;
					 
				} //Fin Recorrer Vector
			echo "<tr>
					<td colspan='8'>$Count Examen(es) Procesado(s) Con &Eacute;xito para el &aacute;rea
							  de ".htmlentities($ResultadoPlantillaA[0]['NombreArea'])." para la  Plantilla A</td></tr></table>";
	}// Fin Tabla Plantilla A
	
	
	///////////////////////////////////////////////////////////////////////////////////////////////
	///      Plantilla B																		 //
	///////////////////////////////////////////////////////////////////////////////////////////////		
		function TablaPlantillaB($ResultadoPlantillaB,$Sexo,$idedad){
				$Count=0;
				echo "
				    <h2 align='center'>
				   			  Plantilla B </h2>
					<table class='SaladColumnTD'  cellspacing='1' cellpadding='3' border='0' align='center'>
                                             <tr class='CobaltFieldCaptionTD'>
						  <td colspan='1' nowrap><font><strong>IdExamen</strong></font> </td>				   					
						  <td colspan='1' nowrap><font><strong>Nombre Examen</strong></font> </td>				   						  						  <td colspan='1' nowrap><font><strong>Estado</strong></font> </td>
                                                  <td colspan='1' nowrap><font><strong>Validado Por</strong></font> </td>
                                                  <td  colspan='1' nowrap><font><strong>Fecha Resultado</strong></font>	
                                            </tr>";		
			foreach($ResultadoPlantillaB as $Fila ){
					echo "
						   <tr>
							   <td> ".$Fila['IdExamen']."</td>
							   <td>".htmlentities($Fila['NombreExamen'])."</td>
							   <td>" ; 
					if($Fila['EstadoDetalle']=='RC')
						echo"   <a  href='javascript:ResultadosPlantillaB(\"".$Fila['IdDetalleSolicitud']."\", \"".$Sexo."\", \"".$idedad."\")'>
									".$Fila['Descripcion'];
					else
						echo $Fila['Descripcion'];

					echo "</a></td>	
                        <td> ".$Fila['NombreEmpleado']."</td>
						<td>".$Fila['FechaResultado']."</td>						
								</tr> ";
			 		 $Count++;
				 
			} //Fin Recorrer Vector
				echo "<tr><td colspan='8'>$Count Examen(es) Procesado(s) Con &Eacute;xito Para la Plantilla B
					  </td></tr>
					  </table><br>";
		} // Fin Tabla Plantilla B
	
	
	///////////////////////////////////////////////////////////////////////////////////////////////
	///      Plantilla C																		 //
	///////////////////////////////////////////////////////////////////////////////////////////////		
		function TablaPlantillaC($ResultadoPlantillaC, $IdSolicitudEstudio,$Conectar){
			
			
			$Count=0;
				echo "
				      <h2 align='center'>
				   			  Plantilla C </h2>
						<table cellspacing='1' cellpadding='3' border='0' align='center'>
					  <tr class='CobaltFieldCaptionTD'>
						  <td  colspan='1' nowrap><font><strong>IdExamen</strong></font></td>				   					
						  <td  colspan='1' nowrap><font><strong>Nombre Examen</strong></font></td>		   						  						  <td  colspan='1' nowrap><font><strong>Estado</strong></font></td>				
						  <td  colspan='1' nowrap><font><strong>Validado Por</strong></font></td>
						  <td  colspan='1' nowrap><font><strong>Fecha Resultado</strong></font>
					  </tr>";		
			foreach($ResultadoPlantillaC as $Fila ){
					echo "
						   <tr>
							   <td> ".$Fila['IdExamen']."</td>
							   <td>".htmlentities($Fila['NombreExamen'])."</td>
							   <td>" ; 
					if($Fila['EstadoDetalle']=='RC')
						echo"   <a  href='javascript:ResultadosPlantillaC(\"".$Fila['IdDetalleSolicitud']."\",\"".$Fila['Resultado']."\")'>
									".$Fila['Descripcion'];
					else
						echo $Fila['Descripcion'];

					echo "</a></td>
		<td>".htmlentities($Fila['NombreEmpleado'])."</td>	
		<td>".$Fila['FechaResultado']."</td>		
							</tr> ";
			 		 $Count++;
				 
			} //Fin Recorrer Vector
				echo "<tr><td colspan='8'>$Count Examen(es) Procesado(s) Con &Eacute;xito Para la Plantilla C
					  </td></tr>
					  </table><br>
					 "; return 0;
			}	// Fin Tabla Plantilla C
	
	
	///////////////////////////////////////////////////////////////////////////////////////////////
	///      Plantilla D																		 //
	///////////////////////////////////////////////////////////////////////////////////////////////		
		function TablaPlantillaD($ResultadoPlantillaD, $IdSolicitudEstudio,$Conectar){
			
			$Count=0;
				echo "
				  <h2 align='center'>Plantilla D </h2>
					<table class='SaladColumnTD'  cellspacing='1' cellpadding='3' border='0' align='center'>
					<tr class='CobaltFieldCaptionTD'>
			<td colspan='1' nowrap><font><strong>IdExamen</strong></font></td>				   					
		<td colspan='1' nowrap><font><strong>Nombre Examen</strong></font> </td>	
		<td colspan='1' nowrap><font><strong>Estado</strong></font> </td>
		<td colspan='1' nowrap><font><strong>Validado Por</strong></font> </td>	
		<td  colspan='1' nowrap><font><strong>Fecha Resultado</strong></font>
					  </tr>";		
			foreach($ResultadoPlantillaD as $Fila ){
					echo "
					   <tr>
						   <td> ".$Fila['IdExamen']."</td>
						   <td>".htmlentities($Fila['NombreExamen'])."</td>
						   <td>" ; 
					         if($Fila['EstadoDetalle']=='RC')
						echo"   <a  href='javascript:ResultadosPlantillaD(\"".$Fila['IdDetalleSolicitud']."\",\"".$Fila['Resultado']."\")'>
									".$Fila['Descripcion'];
					else
						echo $Fila['Descripcion'];

					echo "</a></td>	
<td> ".$Fila['NombreEmpleado']."</td>
 <td>".$Fila['FechaResultado']."</td>					
								</tr> ";
			 		 $Count++;
				 
			} //Fin Recorrer Vector
			echo "<tr><td  colspan='8'>$Count Examen(es) Procesado(s) Con &Eacute;xito Para la Plantilla D
					  </td></tr>
					  </table><br>";
			}	// Fin Tabla Plantilla D
	
		
	///////////////////////////////////////////////////////////////////////////////////////////////
	///      Plantilla E																		 //
	///////////////////////////////////////////////////////////////////////////////////////////////		
		function TablaPlantillaE($ResultadoPlantillaE){
				$Count=0;
				echo "
				    <h2 align='center'>
				   			  Plantilla E </h2>
						<table class='SaladColumnTD'  cellspacing='1' cellpadding='3' border='0' align='center'>
					  <tr class='CobaltFieldCaptionTD'>
						  <td colspan='1' nowrap><font><strong>IdExamen</strong></font> </td>				   					
					  <td colspan='1' nowrap><font><strong>Nombre Examen</strong></font> </td>				      <td colspan='1' nowrap><font><strong>Estado</strong></font> </td>				
					  <td colspan='1' nowrap><font><strong>Validado Por</strong></font> </td>
					  <td  colspan='1' nowrap><font><strong>Fecha Resultado</strong></font>
					  </tr>";		
			foreach($ResultadoPlantillaE as $Fila ){
					echo "
						   <tr>
							   <td> ".$Fila['IdExamen']."</td>
							   <td>".htmlentities($Fila['NombreExamen'])."</td>
							   <td>" ; 
					if($Fila['EstadoDetalle']=='RC')
						echo"   <a  href='javascript:ResultadosPlantillaE(\"".$Fila['IdDetalleSolicitud']."\",\"".$Fila['NombreExamen']."\")'>
									".$Fila['Descripcion'];
					else
						echo $Fila['Descripcion'];

					echo "</a></td>
							<td> ".$Fila['NombreEmpleado']."</td>
							<td>".$Fila['FechaResultado']."</td>						
								</tr> ";
			 		 $Count++;
				 
			} //Fin Recorrer Vector
				echo "<tr><td class='SaladColumnTD' colspan='8'>$Count Examen(es) Procesado(s) Con &Eacute;xito Para la Plantilla E
					  </td></tr>
					  </table><br>";
		} // Fin Tabla Plantilla E
	
	
	
	
	
	
	
    /******************************************************************************************/
	/*    Funci�n para  Recuperar el detalle de la plantilla B		                          */
	/******************************************************************************************/
	function DetalleResultadoPlantillaB($IdDetalleSolicitud,$Conectar,$Sexo,$idedad){ 

		// Validando si el resultado es por ELementos o SubElementos
		   $SQLElementos="SELECT SubElemento
						  FROM sec_detallesolicitudestudios INNER JOIN lab_elementos
										  ON sec_detallesolicitudestudios.IdExamen=lab_elementos.IdExamen
						  Where IdDetalleSolicitud=$IdDetalleSolicitud 
                                                   limit 0,1";
		
		// Estas dos Consultas son por si los resultados son con Sub Elementos
			  $SQL = "SELECT lab_subelementos.IdElemento,Elemento,
					 lab_detalleresultado.IdSubElemento,lab_subelementos.SubElemento, lab_detalleresultado.Resultado,
						   lab_subelementos.Unidad,lab_subelementos.RangoInicio, lab_subelementos.RangoFin,
                                                   CONCAT_WS( '-', lab_subelementos.RangoInicio, lab_subelementos.RangoFin ) AS Rangos
					FROM lab_resultados 
                                        INNER JOIN lab_detalleresultado
					ON lab_detalleresultado.IdResultado=lab_resultados.IdResultado
					INNER JOIN lab_subelementos
					ON lab_subelementos.IdSubElemento=lab_detalleresultado.IdSubElemento
					INNER JOIN lab_elementos
					ON lab_elementos.IdElemento=lab_subelementos.IdElemento
					WHERE lab_resultados.IdDetalleSolicitud=$IdDetalleSolicitud 
                                        AND (lab_subelementos.idsexo =$Sexo OR lab_subelementos.idsexo =3)
                                        AND (idedad =4 OR idedad =$idedad)     
					ORDER BY lab_elementos.IdElemento asc, lab_subelementos.IdSubElemento asc";			
                       // echo $SQL;
			$SQL2="SELECT lab_subelementos.IdElemento,Elemento,NombreExamen						  
				   FROM lab_resultados INNER JOIN lab_detalleresultado
								       ON lab_detalleresultado.IdResultado=lab_resultados.IdResultado
								       INNER JOIN lab_subelementos
									   ON lab_subelementos.IdSubElemento=lab_detalleresultado.IdSubElemento
								       INNER JOIN lab_elementos
								       ON lab_elementos.IdElemento=lab_subelementos.IdElemento
									   INNER JOIN lab_examenes
				  				       ON lab_examenes.IdExamen=lab_resultados.IdExamen
					WHERE lab_resultados.IdDetalleSolicitud=$IdDetalleSolicitud      
					GROUP BY lab_subelementos.IdElemento
					ORDER BY lab_detalleresultado.IdSubElemento";	
                        
		// Verificamos las funciones y ejecutamos	
		if($Conectar==true){						  
		   $ValidarElementos = pg_query($SQLElementos);		
	 	   $TieneElementos = pg_fetch_array($ValidarElementos);
	   
		
		
	/******************************************************************************************************/
	/* Resultados con Sub Elementos 																	  */
	/******************************************************************************************************/
			
		if($TieneElementos['SubElemento']=='S'){				
			$DetalleResultado = pg_query($SQL);
			$DetalleResultado2 = pg_query($SQL);//paralelo
			$ConsultaElementos= pg_query($SQL2);
			while($VectorElementos = pg_fetch_array($ConsultaElementos)){
						$Elementos[]=$VectorElementos[1];
						$NombreExamen[]=$VectorElementos[2];
			}	
			
			echo "<div class='outer'> 
						  <div class='inner' >";  
				echo "
						<br><br> <h3 align='center'>Resultados del Examen ----> ".htmlentities($NombreExamen[0])."<-----</h3>";
				echo "<table  border='0'  width='80%'>
						  <tr>";
 			    $i=0;
			
			
			if(isset($Elementos)){		  
				
				$Element='';
				$i=0;
				$Resultado= pg_fetch_array($DetalleResultado2);//inicio
				while($Resultados= pg_fetch_array($DetalleResultado)){
					$Resultado= pg_fetch_array($DetalleResultado2);//posicion+1
                                          $gion='-';
                                     //if(empty($Resultados['RangoInicio'])){$Resultados['RangoInicio']='';$gion='';}
                                     //if(empty($Resultados['RangoFin'])){$Resultados['RangoFin']='';$gion='';}
                                     if(empty($Resultados['RangoInicio']) and empty($Resultados['RangoFin'])){$Resultados['RangoInicio']='';$Resultados['RangoFin']='';$gion='';}
				if($Resultados['Elemento']!=$Element){
					
					echo"<td style='vertical-align:top' width='10%'><table  border='0' >
					<tr class='CobaltFieldCaptionTD'>
						<td align='center' nowrap><strong>".$Resultados['Elemento']."
																		</strong></td>
						<td>Resultado</td>	
						<td>Unidades</td>
                                                <td>Rangos ref</td>";
                                          
					echo"<tr>
					<td nowrap><strong> ".htmlentities($Resultados['SubElemento'])."     </strong></td>";	
					echo"<td align='center' nowrap><strong> ".htmlentities($Resultados['Resultado'])." </strong></td>";
					echo "<td align='center' nowrap><strong>".htmlentities($Resultados['Unidad']). " </strong></td>";					
                                        echo "<td align='left' nowrap><strong>".$Resultados['RangoInicio']. " ".$gion." ".$Resultados['RangoFin']. " </strong></td></tr> ";  
					$Element=$Resultados['Elemento'];
				} // fin if
				else{

					echo"<tr>
                                        <td nowrap><strong> ".htmlentities($Resultados['SubElemento'])."   </strong></td>";	
					echo"<td align='center' nowrap><strong> ".htmlentities($Resultados['Resultado'])."</strong></td>";			
					echo"<td align='center' nowrap><strong>".htmlentities($Resultados['Unidad']). " </strong></td>";				
					 echo "<td align='center' nowrap><strong>".$Resultados['RangoInicio']. " ".$gion." ".$Resultados['RangoFin']. " </strong></td></tr> ";  
					if($Resultados['Elemento']!=$Resultado["Elemento"]){
						echo"</table></td>";
					}//if de diferencia de vectores
				
				}	// fin else
					$i++;							
		 		} // Fin while
				
        echo"</tr></table>";
			}		// Fin Elementos
				
					
				
			
		}	// Fin  Sub Elementos


	
	/****************************** Resultados con Sub Elementos*****************************/
	
	else{
	
	
	$SQL1="SELECT  IdElemento, Elemento, IdResultado,NombreExamen,lab_elementos.ObservElem
		FROM sec_detallesolicitudestudios 
					INNER JOIN  lab_elementos
					ON lab_elementos.IdExamen=sec_detallesolicitudestudios.IdExamen
					INNER JOIN lab_resultados
					ON lab_resultados.IdDetalleSolicitud=sec_detallesolicitudestudios.IdDetalleSolicitud	
					INNER JOIN lab_examenes
					ON lab_examenes.IdExamen=lab_resultados.IdExamen
				WHERE sec_detallesolicitudestudios.IdDetalleSolicitud=$IdDetalleSolicitud";
				
	$SQLNombre="SELECT distinct NombreExamen
				FROM sec_detallesolicitudestudios 
					INNER JOIN  lab_elementos
					ON lab_elementos.IdExamen=sec_detallesolicitudestudios.IdExamen
					INNER JOIN lab_resultados
					ON lab_resultados.IdDetalleSolicitud=sec_detallesolicitudestudios.IdDetalleSolicitud	
					INNER JOIN lab_examenes
					ON lab_examenes.IdExamen=lab_resultados.IdExamen
				WHERE sec_detallesolicitudestudios.IdDetalleSolicitud=$IdDetalleSolicitud";			
				
		$RespuestaNombre=pg_query($SQLNombre);
		$Elementos= pg_query($SQL1);
		$NombreExamen=pg_fetch_array($RespuestaNombre);
			$tabla='<table with="100%" align="center">';		
					echo "<div class='outer'> 
						  <div class='inner' >";  
				echo "
						<br><br> <h3 align='center'>Resultados del Examen ----> ".htmlentities($NombreExamen[0])."<-----</h3>";
			
	while ($row=pg_fetch_array($Elementos)){
				$IdElemento=$row["IdElemento"];
				$Elemento=$row["Elemento"];
				$IdResultado=$row["IdResultado"];
				$ObservElem=$row["ObservElem"];
				
			$SQL2="select * from lab_detalleresultado where IdElemento=".$IdElemento." and IdResultado=".$IdResultado;
				$resp=pg_query($SQL2);
				
				if($RespElemento=pg_fetch_array($resp)){
				/*Si la respuesta esta en el Elemento*/
					$Control=$RespElemento["Resultado"];
					$Resultado=$RespElemento["Observacion"];
				//$tabla.='<tr><td >Elemento</td><td>Resultado</td><td>Control Normal</td></tr>';
				$tabla.='<tr class="CobaltFieldCaptionTD"><td colspan="3">'.htmlentities($Elemento).'</td></tr>
					<tr>
						<td><strong>Resultado</strong></td>
						<td><strong>Control</strong></td>
						<td></td>
					</tr>
					<tr>
					<td>'.htmlentities($Resultado).' Seg.</td><td'.htmlentities($Control).' Seg.</td>
					<td>&nbsp;</td></tr>';
				
				
				}else{ 
				/*Si las respuestas estan en Subelementos*/			
					$SQL3="select lab_subelementos.SubElemento, lab_subelementos.ObservSubElem,
					 lab_detalleresultado.Resultado, lab_detalleresultado.Observacion
					from lab_detalleresultado
					inner join lab_subelementos
					on lab_subelementos.IdSubElemento=lab_detalleresultado.IdSubElemento
					inner join lab_elementos
					on lab_elementos.IdElemento=lab_subelementos.IdElemento
					where lab_elementos.IdElemento=".$IdElemento." and lab_detalleresultado.IdResultado=".$IdResultado;
					
					$RespSubElemento=pg_query($SQL3);
				
				//$tabla.='<tr><td colspan="4">Elemento</td></tr>';
				$tabla.='<tr class="CobaltFieldCaptionTD"><td colspan="3">'.htmlentities($Elemento).'</td></tr>';
				$tabla.='<tr><td >&nbsp;</td><td ><strong>Resultado</strong></td>
							<td><strong>Control</strong></td></tr>';
					while($ResulSubElemento=pg_fetch_array($RespSubElemento)){
						$NombreSubElemento=$ResulSubElemento["SubElemento"];
						$Resultado=$ResulSubElemento["Resultado"];
						$Control=$ResulSubElemento["Observacion"];
						$ObservSubElem=$ResulSubElemento["ObservSubElem"];
												
						$tabla.='<tr><td >'.htmlentities($NombreSubElemento).'</td><td >'.htmlentities($Resultado).' Seg.</td><td >'.$Control.' Seg.</td></tr>';
						
						if($ObservSubElem!='' and $ObservSubElem!=NULL){
							$tabla.='<tr><td colspan="3" >'.htmlentities($ObservSubElem).'</td></tr>';
						}
						
					}//While de SubElementos
					

				}//ELSE
				
				if($ObservElem!='' and $ObservElem!=NULL){
					$tabla.='<tr><td colspan="3" >'.htmlentities($ObservElem).'</td></tr>';
				}
				$tabla.='<tr><td colspan="3"><br></td></tr>';
	}//While Elementos Codigo Nuevo
	
	$tabla.='</table>';
	
	echo $tabla;

	
		} // FIn Resultados Solo con Elementos
	} // Fin Conexion Buena
	
  } // FIn Funcion Detalle Plantilla B
  

    
	/******************************************************************************************/
	/*    Funci�n para  Recuperar el detalle de la plantilla C		                          */
	/******************************************************************************************/
	function DetalleResultadoPlantillaC($IdDetalleSolicitud,$Conectar,$Resultado){ 

		// Resultado Positivo
		$SQL="SELECT Antibiotico, lab_resultadosportarjeta.Resultado, Cantidad, NombreExamen, CASE lab_resultados.Resultado  
			WHEN 'P' THEN 'Positivo'
			WHEN 'N' THEN 'Negativo'    
			END as Result,lab_resultados.Observacion,lab_bacterias.Bacteria
			  FROM lab_resultados     	            		    
					   INNER JOIN lab_detalleresultado
			 	       ON lab_detalleresultado.IdResultado=lab_resultados.IdResultado
  		   		       INNER JOIN lab_resultadosportarjeta
			           ON lab_resultadosportarjeta.IdDetalleResultado=lab_detalleresultado.IdDetalleResultado
			     	   INNER JOIN lab_antibioticos
		 		       ON lab_antibioticos.IdAntibiotico=lab_resultadosportarjeta.IdAntibiotico
					   INNER JOIN lab_examenes
 				       ON lab_examenes.IdExamen=lab_resultados.IdExamen
					   INNER JOIN lab_bacterias 
				ON lab_detalleresultado.IdBacteria=lab_bacterias.IdBacteria
 			 WHERE lab_resultados.IdDetalleSolicitud=$IdDetalleSolicitud";

 		// Resultado NEGATIVO
		$SQL2="SELECT lab_observaciones.Observacion,NombreExamen
			   FROM lab_resultados INNER JOIN lab_observaciones
 							       ON lab_resultados.Observacion=lab_observaciones.IdObservacion
								   INNER JOIN lab_examenes
								   ON lab_examenes.IdExamen= lab_resultados.IdExamen
               WHERE lab_resultados.IdDetalleSolicitud=$IdDetalleSolicitud";	 
			 
			$Antibioticos = pg_query($SQL);	
			$Count=0;
			if($Resultado=='N' or $Resultado=='O' ){
				if($Resultado=='N'){
						 $Resultado = pg_query($SQL2);	
						 $Negativo = pg_fetch_array($Resultado);
						 echo "<div class='outer'> 
							  <div class='inner' >";  
  	
			 			 echo "<br><br> <h3 align='center'> ------>".htmlentities($Negativo['NombreExamen'])." <-----</h3>";		
						 echo "				
							   <table cellspacing='1' cellpadding='3' border='0' align='center'>
							   <tr class='CobaltFieldCaptionTD'>
								   <td colspan='1' nowrap><strong>Resultado:
								   &nbsp;&nbsp; </strong></td>".
									"<td class='CobaltFieldCaptionTD'> NEGATIVO</td>
							   </tr>
							   
							   <tr>
									  <td class='CobaltFieldCaptionTD' colspan='1' nowrap><strong>Observacion
									  </strong></td>				   				     	  	  
		  							 <td > ".htmlentities($Negativo['Observacion'])."</td>
								</tr>";			
				}

				if ($Resultado=='O'){
						 $Resultado = pg_query($SQL2);	
						 $Negativo = pg_fetch_array($Resultado);
						 echo "<div class='outer'> 
							  <div class='inner' >";  
  	
			 			 echo "<br><br> <h3 align='center'> ------>".htmlentities($Negativo['NombreExamen'])." <-----</h3>";		
						 echo "				
							   <table cellspacing='1' cellpadding='3' border='0' align='center'>
							   <tr class='CobaltFieldCaptionTD'>
								   <td colspan='1' nowrap><strong>Resultado:
								   &nbsp;&nbsp; </strong></td>".
									"<td class='CobaltFieldCaptionTD'>--</td>
							   </tr>
							   
							   <tr>
									  <td class='CobaltFieldCaptionTD' colspan='1' nowrap><strong>Observacion
									  </strong></td>				   				     	  	  
		  							 <td > ".htmlentities($Negativo['Observacion'])."</td>
								</tr>";	
				}



			}else{
				while ($Resultado = pg_fetch_array($Antibioticos)){
	
					if($Count==0){
						echo "<div class='outer'> 
						  <div class='inner' >";  
	  
						echo "<br><br> <h3 align='center'> ------>".htmlentities($Resultado['NombreExamen'])." <-----</h3>";				
						echo "				
							   <table cellspacing='1' cellpadding='3' border='0' align='center'>
							   
							   
							   <tr>
								   <td class='CobaltFieldCaptionTD' colspan='1' nowrap><strong>RESULTADO 
								   :&nbsp;&nbsp;&nbsp;</TD>
								   <TD>".htmlentities($Resultado['Result'])." </strong></td>
							   </tr>
							   
							   
							   <tr>
								   <td class='CobaltFieldCaptionTD' colspan='1' nowrap><strong> 
								  ORGANISMO:&nbsp;&nbsp;&nbsp;</TD>
								   <TD>".htmlentities($Resultado['Bacteria'])." </strong></td>
							   </tr>
							   
							   
							   <tr>
								   <td class='CobaltFieldCaptionTD' colspan='1' nowrap><strong>CULTIVO CON CUENTAS DE 
								   COLONIAS:&nbsp;&nbsp;&nbsp;</TD>
								   <TD>".htmlentities($Resultado['Cantidad'])." </strong></td>
							   </tr>
							   
							   <tr class='CobaltFieldCaptionTD'>
									  <td colspan='1' nowrap><strong>ANTIBIOTICO
									  </strong></td>				   				     	  	  
									  <td colspan='1' nowrap><font Color='white'><strong>INTERPRETACI&Oacute;N
									  </strong></font> </td>   
								</tr>";										
					}
					
							
						echo "<tr>
								<td> ".htmlentities($Resultado['Antibiotico'])."</td>";
						echo"   <td> ".$Resultado['Resultado']."</td></tr>";
					$Count++;		
				//	 Impresion de resultados segun la plantilla			

				} // Fin While
			 } // Fil else	
			echo "</div></div>";
	
	}
	
	
	/******************************************************************************************/
	/*    Funci�n para  Recuperar el detalle de la plantilla D		                          */
	/******************************************************************************************/
	function DetalleResultadoPlantillaD($IdDetalleSolicitud,$Conectar,$Resultado){ 

		// Resultado Positivo
		$SQL="SELECT ElementoTincion, lab_cantidadestincion.CantidadTincion,NombreExamen
			  FROM lab_resultados     	            		    
  				     INNER JOIN lab_detalleresultado
				     ON lab_detalleresultado.IdResultado=lab_resultados.IdResultado
				     INNER JOIN lab_elementostincion
				     ON lab_elementostincion.IdElementosTincion=lab_detalleresultado.IdElemento
				     INNER JOIN lab_cantidadestincion
				     ON lab_cantidadestincion.IdCantidadesTincion=lab_detalleresultado.IdCantidad
				     INNER JOIN lab_examenes
				     ON lab_examenes.IdExamen=lab_resultados.IdExamen
			  WHERE lab_resultados.IdDetalleSolicitud=$IdDetalleSolicitud";

 		// Resultado NEGATIVO
		
			 
			$Resultados = pg_query($SQL);	
			$Count=0;

			while ($Resultado = pg_fetch_array($Resultados)){
	
					if($Count==0){
						echo "<div class='outer'> 
						  <div class='inner' >";  
	  
						echo "<br><br> <h3 align='center'> ------>".htmlentities($Resultado['NombreExamen'])." <-----</h3>";				
						echo "				
							   <table class='SaladColumnTD'  cellspacing='1' cellpadding='3' border='0' align='center'>  
							   <tr>
									  <td class='CobaltFieldCaptionTD' colspan='1' nowrap><strong>Elemento de Tinci&oacute;n
									  </strong></td>				   				     	  	  
									  <td class='CobaltFieldCaptionTD' colspan='1' nowrap><strong>Cantidad
									  </strong></td>   
								</tr>";										
					}
					
							
						echo "<tr>
								<td> ".htmlentities($Resultado['ElementoTincion'])."</td>";
						echo"   <td> ".htmlentities($Resultado['CantidadTincion'])."</td></tr>";
					$Count++;		
				//	 Impresion de resultados segun la plantilla			

				} // Fin While				
			echo "</div></div>";
	
	}
  
  /*********************************************************************************************/
	/*  Dibujar Tabla con las diferentes plantillas de resultados de Laboratorio				 */
	/*********************************************************************************************/
	///////////////////////////////////////////////////////////////////////////////////////////////
	///      Plantilla E																		 //
	///////////////////////////////////////////////////////////////////////////////////////////////		
	function DetalleResultadoPlantillaE($IdDetalleSolicitud,$Conectar,$ResultadoPrueba){
		$Count=0;
		$SQL="SELECT DISTINCT nombreprocedimiento as Nombre,CONCAT_WS(' ',lab_detalleresultado.Resultado, unidades) as Resultado, 
                      CONCAT_WS('-',rangoinicio,rangofin) as Rango,
      		      lab_detalleresultado.Observacion,NombreExamen
		      FROM lab_resultados 	
                      INNER JOIN lab_detalleresultado ON lab_detalleresultado.IdResultado=lab_resultados.IdResultado
		      INNER JOIN lab_procedimientosporexamen ON lab_detalleresultado.IdProcedimiento=lab_procedimientosporexamen.idprocedimientoporexamen	
		      INNER JOIN lab_examenes ON lab_examenes.IdExamen=lab_resultados.IdExamen	
	              WHERE lab_resultados.IdDetalleSolicitud=$IdDetalleSolicitud     
		      ORDER BY lab_procedimientosporexamen.idprocedimientoporexamen asc";
			
		$Resultados = pg_query($SQL);	
			$Count=0;

			while ($Resultado = pg_fetch_array($Resultados)){ 
		
				if($Count==0){
					echo "<div class='outer'> 
					  <div class='inner' >";  
	  				echo "<br><br> <h3 align='center'> ------>".htmlentities($Resultado['NombreExamen'])." <-----</h3>";				
				echo "				
				   <table class='SaladColumnTD'  cellspacing='1' cellpadding='3' border='0' align='center'>  
				   <tr class='CobaltFieldCaptionTD' >
					  <td  colspan='1' nowrap><strong>Prueba
							  </strong></td>				   				     	  	  
					  <td colspan='1' nowrap><strong>Resultado
							  </strong></font> </td>   
									    <td  colspan='1' nowrap><strong>Rango Normal
									  </strong></td>
									    <td class='SaladColumnTD' colspan='1' nowrap><strong>Control Diario
									  </strong></td>   
								</tr>";										
					}
					
							
						echo "<tr>
								<td class='SaladFieldCaptionTD'> ".htmlentities($Resultado['Nombre'])."</td>
								<td class='SaladFieldCaptionTD'> ".htmlentities($Resultado['Resultado'])."</td>
								<td class='SaladFieldCaptionTD'> ".htmlentities($Resultado['Rango'])."</td>";
						echo"   <td class='SaladFieldCaptionTD'> ".htmlentities($Resultado['Observacion'])."</td></tr>";
					$Count++;		
				//	 Impresion de resultados segun la plantilla			

				} // Fin While				
			echo "</table></div></div>";
	
	}// Fin Tabla Plantilla E
  
  
  
  
  
  
  function Paginacion($IdNumeroExp,$NroRegistros,$PagAct,$RegistrosAMostrar){
  
 	 	  
	 $PagAnt=$PagAct-1;
	 $PagSig=$PagAct+1;
	 $PagUlt=$NroRegistros/$RegistrosAMostrar;
	
	 //verificamos residuo para ver si llevar� decimales
	 $Res=$NroRegistros%$RegistrosAMostrar;
	 // si hay residuo usamos funcion floor para que me
	 // devuelva la parte entera, SIN REDONDEAR, y le sumamos
	 // una unidad para obtener la ultima pagina
	 if($Res>0) $PagUlt=floor($PagUlt)+1;
	 
	 //desplazamiento
	 echo "<center>";
	 echo "<a  href='#' onclick=\"SolicitudesLaboratorio('$IdNumeroExp','1')\">Inicio</a> ";
	 if($PagAct>1) echo "<a href='#' onclick=\"SolicitudesLaboratorio('$IdNumeroExp','$PagAnt')\">Anterior</a> ";
	 echo "<strong>&nbsp;&nbsp;&nbsp; Pagina ".$PagAct."/".$PagUlt."&nbsp;&nbsp;&nbsp;</strong>";
	 if($PagAct<$PagUlt)  echo " <a href='#' onclick=\"SolicitudesLaboratorio('$IdNumeroExp','$PagSig')\">Siguiente</a> ";
	 echo "<a href='#' onclick=\"SolicitudesLaboratorio('$IdNumeroExp','$PagUlt')\">Final</a>";
  	 echo "</center>";
  
  }
 

} // Fin de la Clase Laboratorio


















///////////////////////////////////////////////////////////////////////////////////////////////////////
///     CLASE DE IMAGENOLOGIA											                 ////
///////////////////////////////////////////////////////////////////////////////////////////////////////
class Imagenologia{
	//M�todo constructor
	function Imagenologia(){
	} 


	/******************************************************************************************************/
	/* Solicitud de Estudios de RX																		  */
	/******************************************************************************************************/
	function SolicitudesImagenologia($Conexion,$IdNumeroExp,$Pagina){
	 
			 // Paginacion de la consulta
		  $NoPagina=$Pagina;	
		  $RegistrosAMostrar=5;
		  if(isset($_POST['pag'])){
				$RegistrosAEmpezar=($_POST['pag']-1)*$RegistrosAMostrar;
				$PagAct=$_POST['pag'];
		  //caso contrario los iniciamos
		 }else{
		  $RegistrosAEmpezar=0;
		  $PagAct=1;
		 }
			 
	
	
		$SQL="SELECT  DATE_FORMAT(FechaSolicitud,'%e/ %m / %Y') as FechaSolicitud,
					  case 1 when Estado='EP' then 'En Proceso' 
					         when Estado='IN' then 'No Completada' 
				  	         when Estado='C' then 'Procesada' END as Estado,
						 	 NombreEmpleado, NombreSubEspecialidad,NombreEmpleado, sec_solicitudestudios.IdSolicitudEstudio
			  FROM sec_historial_clinico INNER JOIN sec_solicitudestudios
			  							 ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico			   						  			     INNER JOIN mnt_empleados
									     ON mnt_empleados.IdEmpleado= sec_historial_clinico.IdEmpleado
							  	   	     INNER JOIN mnt_subespecialidad
							  		     ON sec_historial_clinico.IdSubEspecialidad= mnt_subespecialidad.IdSubEspecialidad
			WHERE sec_historial_clinico.IdNumeroExp='$IdNumeroExp' AND 
			      sec_solicitudestudios.IdServicio='DCORX'
				  ORDER BY FechaSolicitud desc LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
		
		
		// Paginaci�n
		$SQL2="SELECT   sec_solicitudestudios.IdSolicitudEstudio
			  FROM sec_historial_clinico INNER JOIN sec_solicitudestudios
			  							 ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico			   						  			     INNER JOIN mnt_empleados
									     ON mnt_empleados.IdEmpleado= sec_historial_clinico.IdEmpleado
							  	   	     INNER JOIN mnt_subespecialidad
							  		     ON sec_historial_clinico.IdSubEspecialidad= mnt_subespecialidad.IdSubEspecialidad
			WHERE sec_historial_clinico.IdNumeroExp='$IdNumeroExp' AND 
			      sec_solicitudestudios.IdServicio='DCORX'
				  ORDER BY FechaSolicitud desc";		  
			$Ejecutar2 = pg_query($SQL2);
			$NroRegistros=mysql_num_rows($Ejecutar2);	
				  
		if($Conexion==true){						  
		   $Solicitudes = pg_query($SQL);		
	 	   echo "<div  class='outer3'> 
					  <div class='inner' >    
						  <h3 align='center'>SOLICITUDES DE ESTUDIOS DE IMAGENOLOGIA</h3><br><br><br>
						  <table  cellspacing='1' cellpadding='3' border='0' align='center'>
						  <tr>
						      <td class='SaladColumnTD' nowrap><strong>Fecha de Consulta&nbsp;</strong></td>
						      <td class='SaladColumnTD'  nowrap><strong>Medico</strong></td>
						      <td class='SaladColumnTD'  nowrap><strong>Especialidad</strong></td>
						      <td class='SaladColumnTD'  nowrap><strong>Estado Solicitud&nbsp;</strong></td>
  		 			      </tr>";
						  
			while ($Resultado = pg_fetch_array($Solicitudes)){
				echo "<tr>
				      <td class='SaladFieldCaptionTD'>
  					  <a  href='javascript: DetalleSolicitudRx(\"".$Resultado['IdSolicitudEstudio']."\",\"".
					  $Resultado['FechaSolicitud']."\") '>".
					  $Resultado['FechaSolicitud']."</a></td>
				      <td class='SaladFieldCaptionTD'>".$Resultado['NombreEmpleado']."</td>
				      <td class='SaladFieldCaptionTD'>".$Resultado['NombreSubEspecialidad']."</td>
  				      <td class='SaladFieldCaptionTD'>".$Resultado['Estado']."</td>
				    </tr>";

			  } // Fin While Sacar Datos
			  pg_free_result($Solicitudes); // Liberar memoria usada por consulta.		
		} // Fin Conexion
	
			echo"<tr>
				      <td class='SaladDataTD' colspan='4'></td>
			    </tr>
			    <tr>
				   <td class='SaladColumnTD'  nowrap  colspan='4'><strong>";
				   $this->Paginacion($IdNumeroExp,$NroRegistros,$PagAct,$RegistrosAMostrar);
			echo "</strong></td></tr>
				</table><br><br>
			  </div>
			</div>";
	
	}// Fin funcion Solicitud Rx

	/******************************************************************************************************/
	/* Detalle de Solicitud de Estudios de RX																		  */
	/******************************************************************************************************/
	function DetalleSolicitudRx($IdSolicitudEstudio,$Conexion,$FechaSolicitud){
		//echo "Hola mundo";
		$SQL="SELECT sec_detallesolicitudestudios.IdExamen, rx_examenes.NombreExamen, rx_estados.Estado,EstadoDetalle,
 					 sec_detallesolicitudestudios.IdDetalleSolicitud
			  FROM  sec_solicitudestudios INNER JOIN sec_detallesolicitudestudios
			  						  ON sec_solicitudestudios.IdSolicitudEstudio=sec_detallesolicitudestudios.IdSolicitudEstudio
					 				  INNER JOIN rx_examenes
						 		      ON rx_examenes.IdExamen=sec_detallesolicitudestudios.IdExamen
									  INNER JOIN rx_estados
									  ON sec_detallesolicitudestudios.EstadoDetalle=rx_estados.IdEstado
			  WHERE sec_detallesolicitudestudios.IdSolicitudEstudio=$IdSolicitudEstudio";

			echo "<div class='outer3'> 
				  <div class='inner' >
					  <br><br> <h3 align='center'>DETALLE   DE   EXAMENES PARA LA SOLICITUD CON FECHA DE ".$FechaSolicitud."  </h3>
					  <table class='SaladColumnTD'  cellspacing='1' cellpadding='3' border='0' align='center'>
					  <tr>
						  <td class='SaladColumnTD' colspan='1' nowrap><font><strong>IdExamen</strong></font> </td>				   				
					  	  <td class='SaladColumnTD'  colspan='1' nowrap><font><strong>Nombre del Examen</strong></font> </td>				   						  <td class='SaladColumnTD'  colspan='1' nowrap><font><strong>Estado</strong></font> </td>
					  </tr>";	
				
		if($Conexion==true){						  
		   $Solicitudes = pg_query($SQL);		
		   $Count=0;
   		   $Count2=0;
		   while ($Resultado = pg_fetch_array($Solicitudes)){
				echo " <tr>";
				if($Resultado['EstadoDetalle']=='A')		   
								echo "<td class='SaladFieldCaptionTD'> <a  href='javascript: ResultadosRx(\"".
							  	  $Resultado['IdDetalleSolicitud']."\",\"".
							  	  $Resultado['NombreExamen']."\" ) '>".
								  $Resultado['IdExamen']."</a></td>";
				else
							echo "<td class='SaladFieldCaptionTD' align='left'>".$Resultado['IdExamen']."</td>";								


			  echo "<td class='SaladFieldCaptionTD' align='left'>".htmlentities($Resultado['NombreExamen'])."</td>
			   <td class='SaladFieldCaptionTD' align='left'>".$Resultado['Estado']."</td>
							</tr> ";
				if($Resultado['EstadoDetalle']=='A')
					$Count++;
				else
					$Count2++;	
					
			}
			

				echo "<tr><td class='SaladFieldCaptionTD' colspan='3'>Examenes Procesados con Exito----> $Count</td></tr>
					  <tr><td class='SaladColumnTD' colspan='8'>$Count2 Examen(es) no Fueron procesados Completamente </td>
					  </tr></table></div></div>
					  <div id='ResultadosRx'>
 					  </div>";

		   
		} // Fin If Conectar   
	} // Fin Detalle SolicitudRx


	/******************************************************************************************************/
	/* Resultados de Estudios de RX																		  */
	/******************************************************************************************************/
	function ResultadosRx($IdDetalleSolicitud,$NombreExamen,$Conexion){
	 	$SQL="SELECT sec_detallesolicitudestudios.IdExamen, rx_radiografias.Estado,
				     LecturaPlaca,DATE_FORMAT(FechaLectura,'%e/ %m / %Y') as FechaLectura
			  FROM rx_radiografias INNER JOIN sec_detallesolicitudestudios
						 	       ON sec_detallesolicitudestudios.IdDetalleSolicitud= rx_radiografias.IdDetalleSolicitud							
			  WHERE sec_detallesolicitudestudios.IdDetalleSolicitud=$IdDetalleSolicitud";

	echo "<div class='outer3'> 
			  <div class='inner' >
				  <br><br> <h3 align='center'>Resultados del Estudio de  ".$NombreExamen."  </h3>
				  <table class='SaladColumnTD'   width='800' cellspacing='1' cellpadding='3' border='0' align='center'>
				 <tr>
				   <td class='SaladColumnTD' colspan='1' nowrap><font  color='darkblue'><strong>IdExamen</strong></font> </td>				   				   			  
		   		   <td class='SaladColumnTD'  colspan='2' width='70%' align='justify' nowrap><font  color='darkblue'>
					   <strong>Lectura</strong></font> </td>
        		   <td class='SaladColumnTD'  colspan='1' nowrap><font  color='darkblue'><strong>Fecha de Lectura</strong>
			   		</font> </td>
				  </tr>";	
	
	if($Conexion==true){						  
		   $ResultadosSQL = pg_query($SQL);		
      

		while ($Resultado = pg_fetch_array($ResultadosSQL)){
				echo "<tr>
				      <td class='SaladFieldCaptionTD'>".$Resultado['IdExamen']."</td>
				      <td class='SaladFieldCaptionTD'  colspan='2' align='justify'>".$Resultado['LecturaPlaca']."</td>
  				      <td class='SaladFieldCaptionTD'>".$Resultado['FechaLectura']."</td>
				    </tr>";

			  } // Fin While Sacar Datos
			  pg_free_result($ResultadosSQL); // Liberar memoria usada por consulta.		
		} // Fin Conexion
					  
		echo "<tr><td class='SaladFieldCaptionTD' colspan='4' nowrap> . </td></tr>				   				
				<tr><td class='SaladColumnTD' colspan='8'>Resultado Procesado Exitosamente
					  </td></tr>
					  </table><br><br>
					  </div></div>";			  
	} // Fin Resultados Rx
	
	
	function Paginacion($IdNumeroExp,$NroRegistros,$PagAct,$RegistrosAMostrar){
  
 	 	  
	 $PagAnt=$PagAct-1;
	 $PagSig=$PagAct+1;
	 $PagUlt=$NroRegistros/$RegistrosAMostrar;
	
	 //verificamos residuo para ver si llevar� decimales
	 $Res=$NroRegistros%$RegistrosAMostrar;
	 // si hay residuo usamos funcion floor para que me
	 // devuelva la parte entera, SIN REDONDEAR, y le sumamos
	 // una unidad para obtener la ultima pagina
	 if($Res>0) $PagUlt=floor($PagUlt)+1;
	 
	 //desplazamiento
	 echo "<center>";
	 echo "<a  href='#' onclick=\"SolicitudesRX('$IdNumeroExp','1')\">Inicio</a> ";
	 if($PagAct>1) echo "<a href='#' onclick=\"SolicitudesRX('$IdNumeroExp','$PagAnt')\">Anterior</a> ";
	 echo "<strong>&nbsp;&nbsp;&nbsp; Pagina ".$PagAct."/".$PagUlt."&nbsp;&nbsp;&nbsp;</strong>";
	 if($PagAct<$PagUlt)  echo " <a href='#' onclick=\"SolicitudesRX('$IdNumeroExp','$PagSig')\">Siguiente</a> ";
	 echo "<a href='#' onclick=\"SolicitudesRX('$IdNumeroExp','$PagUlt')\">Final</a>";
  	 echo "</center>";
  
  }
 
	
	
}	// Fin Clase Imagenologia
						  
?>
