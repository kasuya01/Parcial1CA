<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsReporteExamenesporServicio.php");

//variables POST
$opcion=$_POST['opcion'];
//$pag =$_POST['pag'];

//creando los objetos de las clases
$obj = new clsReporteExamenesporServicio;

switch ($opcion) 
{
    case 1: 
        $procedencia=$_POST['procedencia'];
	$fechainicio=$_POST['fechainicio'];
	$fechafin=$_POST['fechafin'];
	$subservicio=$_POST['subservicio'];
	$ffechaini=$fechainicio." 00:00:00";
	$ffechafin=$fechafin." 23:59:59";
	$j=0;
	$i=0;
	$arrayidareas = array();
	$arrayareas = array();
	$cadena="";
        $cond1="";
        $cond2="";
        $query="";
        $cond0="and";
        $pos="";
        $ban=0;
	//VERIFICANDO LOS POST ENVIADOS
            if ((!empty($_POST['fechainicio'])) and (!empty($_POST['fechafin'])))
            { $cond1.= " AND  (t02 .fechahorareg >='".$ffechaini."' AND t02 .fechahorareg <='".$ffechafin."')        ";
                  $cond2.= " AND  (t02 .fechahorareg >='".$ffechaini."' AND t02 .fechahorareg <='".$ffechafin."')        ";
            }

            if (!empty($_POST['procedencia']))
            { $cond1 .="  AND  t12.id='".$_POST['procedencia']."' AND";
              $cond2 .= " AND t12.id='".$_POST['procedencia']."' AND";
            }
		
            if (!empty($_POST['subservicio']))
            { $cond1 .= "  t10.id='".$_POST['subservicio']."' AND";
              $cond2 .=" t10.id='".$_POST['subservicio']."' AND";
            }
				
            if((empty($_POST['procedencia'])) and (empty($_POST['subservicio'])) and (empty($_POST['fechainicio'])) and (empty($_POST['fechafin'])))
            {
		$ban=1;
            }
	
            $consulta=$obj->LeerAreas($lugar);
            $NroRegistros= $obj->NumeroDeRegistros($lugar);
            while ($rowareas=pg_fetch_array($consulta)){
		$arrayidareas[$j]=$rowareas[0];
                $arrayareas[$j]=$rowareas[1];
                $arraynombres[$j]="AREA".$j;
		$j++;
            }
	
            for ($i=0;$i<$NroRegistros;$i++){
               $cadena=$cadena.//"sum(if(t05.id='$arrayidareas[$i]',1,0)) AS AREA$i,";
                "SUM (CASE WHEN t05.id=$arrayidareas[$i] THEN 1 else 0 END )AS AREA$i,";
	    }
                
            if ($ban == 0) {
                $cond1 = substr($cond1, 0, strlen($query) - 3);
                $cond2 = substr($cond2, 0, strlen($query) - 3);
            }     
           $query ="select   
                    $cadena  
                    t13.nombre as servicio, 
                    t14.nombre as establecimiento, 
                    t11.nombre as subservicio,
                    SUM (CASE WHEN t01.id_conf_examen_estab<>1 THEN 1 else 0 END )AS total
                    FROM sec_detallesolicitudestudios           t01 
                    INNER JOIN   lab_resultados                 t02 	ON (t01.id=t02.iddetallesolicitud) 
                    INNER JOIN lab_conf_examen_estab            t03 	ON (t01.id_conf_examen_estab=t03.id) 
                    INNER JOIN mnt_area_examen_establecimiento  t04 	ON (t04.id=t03.idexamen) 
                    INNER JOIN ctl_area_servicio_diagnostico    t05 	ON (t05.id=t04.id_area_servicio_diagnostico) 
                    INNER JOIN lab_areasxestablecimiento        t06 	ON (t06.idarea=t05.id) 
                    INNER JOIN sec_solicitudestudios            t07 	ON (t07.id=t01.idsolicitudestudio) 
                    left JOIN sec_historial_clinico             t08 	ON (t07.id_historial_clinico=t08.id)
                    INNER JOIN  mnt_aten_area_mod_estab         t10 	ON (t10.id = t08.idsubservicio) 
                    INNER JOIN  ctl_atencion                    t11 	ON (t11.id = t10.id_atencion) 
                    INNER JOIN  mnt_area_mod_estab              t12 	ON (t12.id = t10.id_area_mod_estab) 
                    INNER JOIN   ctl_area_atencion              t13 	ON (t13.id = t12.id_area_atencion) 
                    INNER JOIN ctl_establecimiento              t14 	ON (t14.id = t08.idestablecimiento) 
                    WHERE t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado='RC') 
                    AND (t06.condicion='H') 
                    AND (t06.idestablecimiento=$lugar) $cond1
                    GROUP BY t13.nombre,t14.nombre,t11.nombre 
                    union 
                    select 
                    $cadena  
                    t13.nombre as servicio, 
                    t14.nombre as establecimiento, 
                    t11.nombre as subservicio,
                    SUM (CASE WHEN t01.id_conf_examen_estab<>1 THEN 1 else 0 END )AS total
                    from sec_detallesolicitudestudios           t01 
                    INNER JOIN   lab_resultados                 t02 	ON (t01.id=t02.iddetallesolicitud) 
                    INNER JOIN lab_conf_examen_estab            t03 	ON (t01.id_conf_examen_estab=t03.id) 
                    INNER JOIN mnt_area_examen_establecimiento  t04 	ON (t04.id=t03.idexamen) 
                    INNER JOIN ctl_area_servicio_diagnostico    t05 	ON (t05.id=t04.id_area_servicio_diagnostico) 
                    INNER JOIN lab_areasxestablecimiento        t06 	ON (t06.idarea=t05.id) 
                    INNER JOIN sec_solicitudestudios            t07 	ON (t07.id=t01.idsolicitudestudio) 
                    left join mnt_dato_referencia               t08		on (t07.id_dato_referencia=t08.id)		
                    INNER JOIN  mnt_aten_area_mod_estab         t10 	ON (t10.id = t08.id_aten_area_mod_estab) 
                    INNER JOIN  ctl_atencion                    t11 	ON (t11.id = t10.id_atencion) 
                    INNER JOIN  mnt_area_mod_estab              t12 	ON (t12.id = t10.id_area_mod_estab) 
                    INNER JOIN   ctl_area_atencion              t13 	ON (t13.id = t12.id_area_atencion) 
                    INNER JOIN ctl_establecimiento              t14 	ON (t14.id = t08.id_establecimiento) 
                    WHERE t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado='RC') 
                    AND (t06.condicion='H') 
                    AND (t06.idestablecimiento=$lugar) $cond2
                    GROUP BY t13.nombre,t14.nombre,t11.nombre";

		//echo $query_search;
	    $consulta=$obj->BuscarExamenesporSubServicio($query);
					
            if (!empty($_POST['procedencia']))
            {// reporte de una especialidad especificada
    //echo "dentro del if ";
		//GENERACION DE EXCEL
		$NombreExcel="Rep_pruebas_por_servicio".'_'.date('d_m_Y__h_i_s A');
	      	$nombrearchivo = "../../../Reportes/".$NombreExcel.".ods";
		$nombrearchivo;
	       	$punteroarchivo = fopen($nombrearchivo, "w+") 	 or die("El archivo de reporte no pudo crearse");
                			
                $consultaServicios=$obj->NombreServicio($procedencia);
	       	$consultaAreas=$obj->consultarareas($lugar);
	       	$rowServicio = pg_fetch_array($consultaServicios);
		$FechaI=explode('-',$_POST['fechainicio']);
		$FechaF=explode('-',$_POST['fechafin']);
		$FechaI2=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
		$FechaF2=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
        
              $imprimir="<center><table width='80%' border='0'  align='center'> ";
             $imprimir.="<tr>
                            <td  align='center'><h4>REGISTRO DE EXAMENES PRACTICADOS A LOS DIFERENTES SERVICIOS SEPARADOS POR SECCION</h4></td>
                        </tr>";
              /* echo '<center><tr>
                       <td align="center">
                                <a href="pdfOrd_x_examenesxservicio.php?procedencia='.$procedencia.'&subservicio='.$subservicio.'&fechainicio='.$fechainicio.'&fechafin='.$fechafin.'&con1='.$cond1.'&cond2='.$cond2.'&cadena='.$cadena.'&title=\'PDF\'" target="_blank"><h5></h5><img src="../../../Imagenes/icono-pdf.jpg" height="60" width="60" /></a> 
                         </td>
                </tr></center>';*/
                    echo"<center> <tr>     
                            <td align='center' >
                                <a href='".$nombrearchivo."'><H5>   DESCARGAR REPORTE <img src='../../../Imagenes/Calc_Icon.png'></H5></a>
                            </td>
                        </tr></center>";
                $imprimir .=" <tr align='center'>
                            <td align='center' >
                                <h4>PERIODO DEL:  ".$FechaI2." AL ".$FechaF2."</h4>
                            </td></tr>
                        <tr align='center'>
                            <td align='center' ><h5>PROCEDENCIA: <span style='color: #0101DF;'>".$rowServicio['servicio']."</h5></td>
                        </tr>
                    </table></center>";
        
        //otra tabla 
$imprimir.="<center>
                <div class='table-responsive' style='width: 70%;'>
                    <table width='60%' border='1' align='center' >
                    <thead>
                        <tr>
                            <th  width='8%'  align='left'><strong>Servicio</strong></th>";
	    while ($rowarea=pg_fetch_array($consultaAreas))
            {
                $imprimir.="<th  width='5%' align='center'><strong>".htmlentities($rowarea['nombrearea'])."</strong></th>";
	    }
                $imprimir.="<th  width='5%' align='center'><strong>TOTAL</strong></th>
                       </tr>
                    </thead>
                <tbody>"; 
		
        if(pg_num_rows($consulta))
        {     //echo "dentro";            
            while ($row = pg_fetch_array($consulta))
            {
                $ser=$row['subservicio'];
            $imprimir.="<tr>
                            <td width='10%' align='left' >".$row['subservicio']."</td>";
                          for($x=0;$x<$NroRegistros;$x++)
                            { 	
                            $area='area'.$x;
                $imprimir.="<td width='8%' align='center'>".$row[$area]."</td>";
                            }
                $imprimir.="<td width='8%' align='center'><strong>".$row['total']."</strong></td>
                        </tr>";
           
	    }
        $imprimir.="</table></center>";
        }else { //echo "nada";
           $imprimir .="<tr>
                            <th colspan='10'><span style='color: #575757;'>No se han encontrado reeeesultados...</span></th>
                        </tr>
                        </table>";
                //echo $imprimir;
             
         }  
        $imprimir.= "<br>";
        $imprimir.= "<br>";
   
        //CIERRE DE ARCHIVO EXCEL
        fwrite($punteroarchivo,$imprimir);
        fclose($punteroarchivo);
		//************************/ 
        echo $imprimir;
        }
// ******************************************* LAS TODAS   PROCEDENCIAS **************************
    else{ //reporte de todas las especialidades
         //  GENERACION DE EXCEL
        $j=0;
        $i=0;
        $arrayidareas = array();
        $arrayareas = array();
        $cadena="";
        $NombreExcel="Rep_General_de_pruebas_por_servicio".'_'.date('d_m_Y__h_i_s A');
        $nombrearchivo = "../../../Reportes/".$NombreExcel.".ods";
        $punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");
            //***********************
        $FechaI=explode('-',$_POST['fechainicio']);
        $FechaF=explode('-',$_POST['fechafin']);
        $FechaI2=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
        $FechaF2=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
        $imprimir="<CENTER><table width='80%' border='0' hight='6%' align='center'>";
                         
        $imprimir.= "<tr>
                            <td  align='center'><h3>REGISTRO DE EXAMENES PRACTCADOS A LOS DIFERENTES SERVICIOS SEPARADOS POR SECCION</h3></td>
                        </tr>";
        echo"<CENTER> <tr align='center'>     
                            <td align='center' >
                                <a href='".$nombrearchivo."'><H5>   DESCARGAR REPORTE <img src='../../../Imagenes/Calc_Icon.png'></H5></a>
                            </td>
                        </tr></CENTER>";
                   
              $imprimir.=" <CENTER><tr>
                            <td  align='center'><h3>PERIODO DEL:  ".$FechaI2." AL ".$FechaF2."</h3></td>
                        </tr></CENTER>
                                        
                    </table></CENTER>";
	echo $imprimir;
	fwrite($punteroarchivo,$imprimir);
	$consultaSubServicio=$obj->consultaTodosServicios($lugar); 
                
        while($row = pg_fetch_array($consultaSubServicio)){
        
                //*********ConsultaExterna********
                
                
                
              //  $ConsultaExterna=$obj->ConsultaExterna();
               // $row = pg_fetch_array($ConsultaExterna);
                 $servicio=$row[0];
                 $nomServicio=$row[1];
                 
                 
         $imprimir="<CENTER><table width='80%' border='0' hight='6%' align='center'>
                        <tr align='center'>
                            <td  align='center' ><h4>PROCEDENCIA: <span style='color: #0101DF;'>".$nomServicio."</h4></td>
                        </tr>
                    </table></CENTER>";
        $cantidad1=$obj->cantidadxservicio1($servicio,$ffechaini,$ffechafin,$lugar);
        $cantidad2=$obj->cantidadxservicio2($servicio,$ffechaini,$ffechafin,$lugar);
              // echo "cantidad1=". $cantidad1."  cantidad2=".$cantidad2;
                if($cantidad1 > 0 or  $cantidad2 > 0)
                //if($cantidad1 >0 )    
                {
                    $cantidad=1;
                    $cantidadcon=$cantidad;
            
                }
                else {
                    $cantidad=0;
                    $cantidadcon=$cantidad;
                }
                        
               //  echo $servicio." - ".$nomServicio ." - ".$cantidad;
                $consulta=$obj->LeerAreas($lugar);
		$NroRegistros= $obj->NumeroDeRegistros($lugar);
		while ($rowareas=pg_fetch_array($consulta))
                {
			$arrayidareas[$j]=$rowareas[0];
                        $arrayareas[$j]=$rowareas[1];
			$arraynombres[$j]="AREA".$j;
			$j++;
		}
	
		for ($i=0;$i<$NroRegistros;$i++)
                {
		   $cadena=$cadena.//"sum(if(t05.id='$arrayidareas[$i]',1,0)) AS AREA$i,";
                     "SUM (CASE WHEN t05.id=$arrayidareas[$i] THEN 1 else 0 END )AS AREA$i,";
		}
                                
               
                                
        if ($cantidadcon>0  )
        {
           $imprimir.="<center>
                <div class='table-responsive' style='width: 70%;'>
                    <table width='80%' border='1' align='center' >
                    <thead>
                        <tr>
                            <th  width='10%' align='center'><strong>Servicio</strong></th>";
                           $consultaAreas=$obj->consultarareas($lugar);
                      while ($rowarea=pg_fetch_array($consultaAreas))
                        {
                $imprimir.="<th width='8%' align='center'><strong>".htmlentities($rowarea['nombrearea'])."</strong></th>";
                        }
                $imprimir.="<th width='8%' align='center'><strong>TOTAL</strong></th>

                        </tr>
                    </thead>
                <tbody>"; 
                     // fwrite($punteroarchivo,$imprimir);                      
                        //   echo $servicio;
                                //echo $servicio."-".$ffechaini."-".$ffechafin;
                        $consulta=$obj->subserviciosxservicio($cadena,$servicio,$ffechaini,$ffechafin,$lugar);
                while ($row = pg_fetch_array($consulta))

                        {        
                                        $ser=$row['subservicio'];
                                                    // echo  $ser;
                                        $imprimir.="<tr>
                                                <td width='10%' align='left'>".$row['subservicio']."</td>";
                                                for($x=0;$x<$NroRegistros;$x++)
                                                { 	
                                                   $area='area'.$x;
                                                    $imprimir.="<td width='8%' align='center'>".$row[$area]."</td>";
                                                    
                                                }  
                                                // fwrite($punteroarchivo,$imprimir);
                                                    $imprimir.="<td width='8%' align='center'><strong>".$row['total']."</strong></td>
                                               </tr>";
                            $pos=$pos + 1;
                            
                        }      
                            pg_free_result($consulta);
                            
                            $imprimir .= "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
                                </table></DIV></center><br>";
                       //  fwrite($punteroarchivo,$imprimir);     
                            //$imprimir1=$imprimir;
                               // echo $imprimir;
                 //   fwrite($punteroarchivo,$imprimir);         
                  //  fclose($punteroarchivo);
                          //  echo  $imprimir; 
            }else {
                         $consultaAreas=$obj->consultarareas($lugar);
                                     $imprimir.="<center>
                 <div class='table-responsive' style='width: 70%;'>
                    <table width='80%' border='1' align='center' >
                        <thead>
                            <tr>
                                <td  width='10%' align='left'><strong>Servicio</strong></td>";
                        while ($rowarea=pg_fetch_array($consultaAreas))
                        {
                    $imprimir.="<td  width='12%' align='center'><strong>".htmlentities($rowarea['nombrearea'])."</strong></td>";
                          
                        }
                    $imprimir.="<td  width='10%'><strong>TOTAL</strong></td></tr></thead>";

               $imprimir.= "<tr>
                                <td colspan='13'><span style='color: #0101DF;'> No se han encontrado resultados...</span></td>
                            </tr>
                    </table>
                </div><br>";

                   //   
                      //  fwrite($punteroarchivo,$imprimir);
                    //fclose($punteroarchivo);

                    // echo  $imprimir; 
                  }
                echo  $imprimir;  
                 
                   
fwrite($punteroarchivo,$imprimir);

           }
           // fwrite($punteroarchivo,$imprimir);
     fclose($punteroarchivo);
         

       		
}
	break;
    
	
	  
    case 3://LLENANDO COMBO subservicio
                    
		$rslts='';
		$proce=$_POST['proce'];
		//echo $proce;
		$dtMed=$obj->LlenarSubServ($proce,$lugar);	
		
		$rslts = '<select name="cboMedicos" id="cmbSubServicio" class="form-control height" style="width:250px">';
		$rslts .='<option value="0">--Seleccione un Servicio--</option>';
			
		while ($rows =pg_fetch_array($dtMed)){
			$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;
	
	break;	
    case 4:// Vista Previa Reporte 
		//echo $medico."+".$IdSubEsp."+".$especialidad."-".$fechainicio."-".$fechafin;
    $query = "SELECT  sec_historial_clinico.IdNumeroExp, 
	      sec_solicitudestudios.IdSolicitudEstudio,
	      DATE_FORMAT(sec_solicitudestudios.FechaSolicitud ,'%e/ %m / %Y') AS FechaSolicitud,
	       mnt_subservicio.NombreSubServicio AS origen, mnt_servicio.NombreServicio AS procedencia,
			mnt_empleados.NombreEmpleado AS medico, 
			CONCAT_WS(' ',PrimerApellido,NULL,SegundoApellido,',',PrimerNombre,NULL,SegundoNombre) as NombrePaciente,
			CASE sec_solicitudestudios.Estado 
				WHEN 'D' THEN 'Digitada'
				WHEN 'R' THEN 'Recibida'
				WHEN 'P' THEN 'En Proceso'    
				WHEN 'C' THEN 'Completa' END AS Estado
			FROM sec_historial_clinico 
			INNER JOIN sec_solicitudestudios ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
			INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
			INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio= mnt_servicio.IdServicio 
			INNER JOIN mnt_empleados ON sec_historial_clinico.IdEmpleado= mnt_empleados.IdEmpleado
			INNER JOIN mnt_expediente ON sec_solicitudestudios.IdNumeroExp= mnt_expediente.IdNumeroExp
			INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente= mnt_datospaciente.IdPaciente
			WHERE  sec_solicitudestudios.IdServicio ='DCOLAB' AND";
		$ban=0;
			//VERIFICANDO LOS POST ENVIADOS
	if (!empty($_POST['especialidad']))
	{ $query .= " sec_historial_clinico.IdSubEspecialidad='".$_POST['especialidad']."' AND";}
	
	if (!empty($_POST['medico']))
		{ $query .= " sec_historial_clinico.IdEmpleado='".$_POST['medico']."' AND";}
	
	if ((!empty($_POST['fechainicio'])) and (!empty($_POST['fechafin'])))
	{ $query .= " sec_solicitudestudios.FechaSolicitud BETWEEN '".$_POST['fechainicio']."' AND '".$_POST['fechafin']."' ";}
  
  
  	if((empty($_POST['especialidad'])) and (empty($_POST['medico'])) and (empty($_POST['fechainicio'])) and (empty($_POST['fechafin'])))
	{
				$ban=1;
	}
			
	if ($ban==0)
	{   $query = substr($query ,0,strlen($query)-1);
		$query_search = $query. " order by PrimerApellido";
	}
		
		
			ECHO $query_search;
             $consulta1=$objdatos->BuscarSolicitudesEspecialidad($query_search); 
			 
			$row1 = pg_fetch_array($consulta1);
  $imprimir=" <table width='90%' higth='10%' border='0' align='center'>
              <tr>
                    <td colspan='7' align='center'><h3><strong>REPORTE DE SOLICITUDES POR ESPECIALIDAD
			</h3></strong></td>
	      </tr>
	      <tr>
			<td colspan='7' align='center'><h3>".htmlentities($row1['procedencia'])."</h3></td>
	               </td>
	      </tr>
			<tr>
			<td colspan='7' align='center'><h4>".htmlentities($row1['origen'])."</h4></td>
			</td>
			</tr>
			</table>";
  $consulta=$objdatos->BuscarSolicitudesEspecialidad($query_search); 
 $imprimir.="<table width='75%' border='1' align='center'>
			
            <tr class='CobaltFieldCaptionTD'>
				<td>Fecha Solicitud </td>
				<td>NEC </td>
				<td>Nombre Paciente</td>
				<td>Medico</td>
				<td>Estado Solicitud</td>
		    </tr>";    
 $pos=0;
    while ($row = pg_fetch_array($consulta))
	{ 
$imprimir .="<tr>
		   <td width='11%'>".$row['FechaSolicitud']."</td>
		   <td width='8%'>".$row['IdNumeroExp']."</td>". 
		   "<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
		   "<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' />".
		   "<td width='31%'>".htmlentities($row['NombrePaciente'])."</td>
		 
		  
		    <td width='31%'>".htmlentities($row['medico'])."</td>
		    <td width='15%'>".$row['Estado']."</td>
		   </tr>";

	$pos=$pos + 1;
	}
	
	pg_free_result($consulta);
	
   $imprimir .= "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
   
		</table>";
    
	echo $imprimir;
	
	echo "<table width='90%' border='0' align='center'>
			<tr>
				<td colspan='7' align='center'>	
				<div id='boton'>	<input type='button' id='btnSalir' value='Cerrar' class='MailboxButton' onClick='cerrar()'></div>
				</td>
				
			</tr></table>";
//	echo $imprimir;


///////////////////////////////////////////////////////////////// Archivo
 //$Nombre=$NEC."_".$Solicitud."_".$Fecha;
	//		 $nombrearchivo = './../../../Solicitudes/'.$Nombre.'.pet';
		//	// "../ReportesExcel/".$NombreExcel.".xls";
	 //$punteroarchivo = fopen($nombrearchivo, "w+") or die.("El archivo de reporte no pudo crearse");
 }