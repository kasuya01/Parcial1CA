<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsSolicitudesPorServicioPeriodo.php");

//variables POST

  $opcion=$_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsSolicitudesPorServicioPeriodo;


switch ($opcion) 
{
  case 1:  
       
      $ban = 0;
       $IdEstab=$_POST['IdEstab'];
	$IdServ=$_POST['IdServ'];
 	$IdSubServ=$_POST['IdSubServ'];
        $fechainicio=$_POST['fechainicio'];
	$fechafin=$_POST['fechafin'];
	$medico=$_POST['medico'];
        $cond1="";
        $cond2="";
        $query="";
        $query2="";
        $where_with="";
        
     
         $cond0="and";
         
         $pag=$_POST['pag'];
	$registros = 20;
	$pag =$_POST['pag'];
	$inicio = ($pag-1) * $registros;
        
        $IdEstab=$_POST['IdEstab'];
        
        
      //  echo $IdEstab." - ".$lugar;
        if ($_POST['IdEstab']<>0) {
           if ($_POST['IdEstab']<>$lugar){
               $cond1 .= " AND t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " ";
               $cond2 .= " AND t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " ";
            }
            else {
               $cond1 .= " AND t02.id_establecimiento_externo = " . $lugar . " ";
               $cond2 .= " AND t02.id_establecimiento_externo = " . $lugar . " ";
            }
          
        }
       if ($_POST['IdServ'] <> 0) {
            $cond1 .= " AND t12.id  = " . $_POST['IdServ'] . " ";
            $cond2 .= " AND t12.id  = " . $_POST['IdServ'] . " ";
            $where_with = "t03.id = $IdServ AND ";
        }

        if (!empty($_POST['IdSubServ'])) {
            $cond1 .= "AND t10.id = " . $_POST['IdSubServ'] . " ";
            $cond2 .= "AND t10.id = " . $_POST['IdSubServ'] . " ";
        }
       
 
      /*  if (!empty($_POST['IdServ'])) {
            $cond1 .=$cond0 ."  t13.id  = " . $_POST['IdServ'] . "     ";
            $cond2 .=$cond0 ."  t13.id  = " . $_POST['IdServ'] . "     ";
            $where_with = "id_area_atencion = $IdServ AND ";
        }*/
        
        if (!empty($_POST['medico']))
		{ $cond1 .= "AND  t24.id='".$_POST['medico']."' ";
                  $cond2 .= "AND  t24.id='".$_POST['medico']."' ";
                }
	
               
                
                 if ((!empty($_POST['fechainicio'])) and (!empty($_POST['fechafin'])))
	{ /* $Nfechaini=explode("/",$fechainicio);
	  $Nfechafin=explode("/",$fechafin);
		 	//print_r($Nfecha);
                $Nfechaini=$Nfechaini[2]."-".$Nfechaini[1]."-".$Nfechaini[0]; 
		$Nfechafin=$Nfechafin[2]."-".$Nfechafin[1]."-".$Nfechafin[0]; */
                     
		$cond1 .= "AND t02.fecha_solicitud BETWEEN '".$_POST['fechainicio']."' AND '".$_POST['fechafin']."' AND ";
                $cond2 .= "AND t02.fecha_solicitud BETWEEN '".$_POST['fechainicio']."' AND '".$_POST['fechafin']."' AND ";
                
        }
                
                
      if((empty($_POST['especialidad'])) and (empty($_POST['medico'])) and (empty($_POST['fechainicio'])) 
	and (empty($_POST['fechafin'])) and (empty($_POST['IdEstab'])) and (empty($_POST['IdServ'])))
	{
		$ban=1;
	}
           if ($ban == 0) {

            $cond1 = substr($cond1, 0, strlen($query) - 5);
            $cond2 = substr($cond2, 0, strlen($query) - 5);
            
          //  echo $query1;
           // $query_search = 
        // echo  $cond1;
        //  echo $cond2;
        }   
        
       
        $query= " WITH tbl_servicio AS ( SELECT t02.id, CASE WHEN t02.nombre_ambiente IS NOT NULL THEN 
            CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||' - ' || t02.nombre_ambiente END ELSE 
            CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||' - ' || t01.nombre WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=t01.nombre) THEN t01.nombre END END AS servicio, 
            (CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'-->' || t06.nombre ELSE t07.nombre ||'-->' || t06.nombre END) as procedencia 
            FROM ctl_atencion t01 
            INNER JOIN mnt_aten_area_mod_estab t02 ON (t01.id = t02.id_atencion) 
            INNER JOIN mnt_area_mod_estab t03 ON (t03.id = t02.id_area_mod_estab) 
            LEFT JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab) 
            LEFT JOIN mnt_servicio_externo t05 ON (t05.id = t04.id_servicio_externo) 
            INNER JOIN ctl_area_atencion t06 on t06.id = t03.id_area_atencion 
            INNER JOIN ctl_modalidad t07 ON t07.id = t03.id_modalidad_estab WHERE t02.id_establecimiento = $lugar ORDER BY 2) 

            SELECT distinct(t02.id) , 
            t20.procedencia AS nombreservicio, 
            t19.nombre AS sexo, 
            t24.nombreempleado as medico, 
            CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido, t07.segundo_apellido,t07.apellido_casada) AS paciente,
            (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
            t20.servicio AS nombresubservicio,
            CASE t01.estadodetalle WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
               WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
               WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
               WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
               WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
               WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
               WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' 
               WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='CA') THEN 'Cancelada' END AS estado, 
            t06.numero as expediente,
            TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud 
            FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02 ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04 ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05 ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_expediente t06 ON (t06.id = t02.id_expediente) 
            INNER JOIN mnt_paciente t07 ON (t07.id = t06.id_paciente) 
            INNER JOIN ctl_area_servicio_diagnostico t08 ON (t08.id = t05.id_area_servicio_diagnostico AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN sec_historial_clinico t09 ON (t09.id = t02.id_historial_clinico) 
            INNER JOIN mnt_aten_area_mod_estab t10 ON (t10.id = t09.idsubservicio) 
            INNER JOIN ctl_atencion t11 ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12 ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13 ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14 ON (t14.id = t09.idestablecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo t15 ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16 ON (t16.id = t02.estado) 
            INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico t18 ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19 ON (t19.id = t07.id_sexo) 
            INNER JOIN tbl_servicio t20 ON (t20.id = t10.id AND t20.servicio IS NOT NULL) 
            LEFT JOIN mnt_empleado t24 ON (t09.id_empleado=t24.id) 
            WHERE t02.id_establecimiento = $lugar AND t01.estadodetalle <> 8  $cond1 
            
            UNION

           SELECT distinct(t02.id), 
           t13.nombre AS nombreservicio, 
           t19.nombre AS sexo, 
           t24.nombreempleado as medico, CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido, t07.apellido_casada) AS paciente, 
           (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
           t11.nombre AS nombresubservicio, 
           CASE t01.estadodetalle WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
                WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
                WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
                WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
                WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
                WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
                WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo'
                WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='CA') THEN 'Cancelada' 
            END AS estado,
            t06.numero as expediente,
            TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud 
            FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02 ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04 ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05 ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_dato_referencia t09 ON t09.id=t02.id_dato_referencia 
            INNER JOIN mnt_expediente_referido t06 ON (t06.id = t09.id_expediente_referido) 
            INNER JOIN mnt_paciente_referido t07 ON (t07.id = t06.id_referido) 
            INNER JOIN ctl_area_servicio_diagnostico t08 ON (t08.id = t05.id_area_servicio_diagnostico AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN mnt_aten_area_mod_estab t10 ON (t10.id = t09.id_aten_area_mod_estab) 
            INNER JOIN ctl_atencion t11 ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12 ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13 ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14 ON (t14.id = t09.id_establecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo t15 ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16 ON (t16.id = t02.estado) 
            INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud)
            INNER JOIN ctl_examen_servicio_diagnostico t18 ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19 ON (t19.id = t07.id_sexo) 
            LEFT JOIN mnt_empleado t24 ON (t09.id_empleado=t24.id) 
            WHERE  t02.id_establecimiento = $lugar AND t01.estadodetalle <> 8   $cond2"; 
      //	echo $query;
         $consulta=$objdatos->BuscarSolicitudesEspecialidad($query); 

        /*  ----------Datos para  Pacgianción----------------*/
	$RegistrosAMostrar=10;
	$RegistrosAEmpezar=($_POST['pag']-1)*$RegistrosAMostrar;
	$PagAct=$_POST['pag'];
				
	$consulta=$objdatos->consultarpag($query,$RegistrosAEmpezar,$RegistrosAMostrar);
	$NroRegistros= $objdatos->NumeroDeRegistros($query);
        
        
        
    if ($NroRegistros==""){
            $NroRegistros=0;
        $imprimir= "<table width='85%' border='0'  align='center'>
          <center>
                <tr>
                    <td align='center'  ><span style='color: #0101DF;'> <h4> TOTAL DE SOLICITUDES:".$NroRegistros."</h4></span></td>
                </tr>
                <br>
                
                
                <tr>
                    <td  align='right' > <button type='button'  class='btn btn-primary'  onclick='VistaPrevia(); '><span class='glyphicon glyphicon-print'></span> IMPRIMIR REPORTE </button> </td>
		</tr>
          </center>
	</table> ";
    }ELSE {
        $imprimir= "<table width='85%' border='0'  align='center'>
          <center>
                <tr>
                    <td   align='center'  ><span style='color: #0101DF;'> <h4> TOTAL DE SOLICITUDES:".$NroRegistros."</h4></span></td>
                </tr>
                </table>
                
                <table width='85%' border='0'  align='center'>
                    <td align='right' > <button type='button'  class='btn btn-primary'  onclick='VistaPrevia(); '><span class='glyphicon glyphicon-print'></span> IMPRIMIR REPORTE </button> </td>
			
		</tr>
          </center>
	</table> ";
                        }
				

 
 
$imprimir.="<center><div class='table-responsive' style='width: 85%;'>
                <table width='75%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
                    <thead>
                            <tr>
				<th>Fecha Solicitud </th>
				<th>NEC             </th>
				<th>Nombre Paciente </th>
				<th>Medico          </th>
				<th>Origen          </th>
				<th>Procedencia     </th>
				<th>Establecimiento </th>
				<th>Estado Solicitud</th>
                            </tr>
                    </thead><tbody>"; 
if(pg_num_rows($consulta)){
                    $pos = 0;
 	
        while ($row = pg_fetch_array($consulta))
	{ 
                $imprimir .="<tr>
				<td width='7%'>".$row['fechasolicitud']."</td>
				<td width='5%'>".$row['expediente']."</td>". 
                                    "<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row[1]."' />".
                                    "<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row['expediente']."' />".
			       "<td width='20%'>".$row['paciente']."</td>
                                <td width='15%'>".htmlentities($row['medico'])."</td>
			    	<td width='10%'>".htmlentities($row['nombresubservicio'])."</td>
			    	<td width='12%'>".htmlentities($row['nombreservicio'])."</td>
			    	<td width='20%'>".htmlentities($row['estabext'])."</td>	
			    	<td width='15%'>".$row['estado']."</td>
	            </tr>";

	$pos=$pos + 1;
	}
	
	pg_free_result($consulta);
	
   	$imprimir .= "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
   
	            </table>";
    
	echo $imprimir;
        
        
	//determinando el numero de paginas
	$PagAnt=$PagAct-1;
	$PagSig=$PagAct+1;
				 
	$PagUlt=$NroRegistros/$RegistrosAMostrar;
				 
	//verificamos residuo para ver si llevar� decimales
	$Res=$NroRegistros%$RegistrosAMostrar;
	//si hay residuo usamos funcion floor para que me
	//devuelva la parte entera, SIN REDONDEAR, y le sumamos
	//una unidad para obtener la ultima pagina
	 if($Res>0) $PagUlt=floor($PagUlt)+1;
	    echo "<table align='center'>
                 <ul class='pagination'>
		       <tr>
				<td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
	               </tr>
		       <tr>
				<td><a onclick=\"BuscarDatos('1')\">Primero</a> </td>";
				//// desplazamiento

	if($PagAct>1) 
	 		 echo "<td>  <a onclick=\"BuscarDatos('$PagAnt')\">Anterior</a> </td>";
	 if($PagAct<$PagUlt)  
			 echo "<td> <a onclick=\"BuscarDatos('$PagSig')\">Siguiente</a> </td>";
		 	 echo "<td> <a onclick=\"BuscarDatos('$PagUlt')\">Ultimo</a></td></tr>
                 </table> ";
                         
                         
	   echo " <center> <ul class='pagination'>";
		 $numPags ='';
			 for ($i=1 ; $i<=$PagUlt; $i++){
                              /*  if ($i==1){
                                
                                 echo " <li class='active'><a href='#'> $i <span class='sr-only'>(current)</span></a></li>";
                                     
                                    // $numPags .= "<a >$pag</a>";
                                     
                                 }
                                 
					// 
							
				 else{*/
					 echo " <li ><a  href='javascript: BuscarDatos(".$i.")'>$i</a></li>";
                                // }
			 }
				// echo "<li><a>".$numPags."</a></<li>
                 echo " </ul></center>";
                                 
                              
                                 } else {
             $imprimir .="<tr><td colspan='11'><span style='color: #575757;'>No se han encontrado resultados...</span></td></tr></table>";
                echo $imprimir;
                                 }

	break;
    	
	case 2:  // solicitud estudios
		$idexpediente=$_POST['idexpediente'];
		$idsolicitud=$_POST['idsolicitud'];
		include_once("clsSolicitudesPorServicioPeriodo.php");
		//recuperando los valores generales de la solicitud
		
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud);
		$row = pg_fetch_array($consulta);
		//obteniedo los datos generales de la solicitud
		//valores de las consultas
		$medico=$row['NombreMedico'];
		$idmedico=$row['IdMedico'];
		$paciente=$row['NombrePaciente'];
		$edad=$row['Edad'];
		$sexo=$row['Sexo'];
		$precedencia=$row['Precedencia'];
		$origen=$row['Origen'];
		$DatosClinicos=$row['DatosClinicos'];
		$Estado=$row['Estado'];
		$fechasolicitud=$row['FechaSolicitud'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idexpediente,$idsolicitud);
		$imprimir="<form name='frmDatos'>
    		<table width='70%' border='0' align='center'>
			<tr>
				<td colspan='4' align='center' class='CobaltFieldCaptionTD'><h3><strong>DATOS SOLICITUD</strong></h3></td>
			</tr>
			<tr>
				<td>Procedencia: </td>
				<td>".$precedencia."</td>
				<td>Origen: </td>
				<td>".$origen."
					<input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled' />
					<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled' />
					<input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='".$fechasolicitud."' disabled='disabled' />
				</td>
			</tr>
			<tr>
	    			<td colspan='4'></td>
	    			
			</tr>
			<tr>
		    		<td>Medico: </td>
		    		<td colspan='3'>".$medico."</td>
			</tr>
			<tr>
	    			<td>Paciente: </td>
		   		<td colspan='3'>".$paciente."</td>
		    	</tr>
		  	<tr>
		    		<td>Edad: </td>
		    		<td>". $edad."</td>
		    		<td>Sexo: </td>
		    		<td>".$sexo."</td>
		   	 </tr>
		  
	    	</table>
		<table width='90%' border='0' align='center'>
			<tr>
				<td colspan='4' align='center'>ESTUDIOS SOLICITADO</td>
			</tr>
			<tr>
				<td>
		<table border = 1 align='center' class='estilotabla'>
		   	<tr>
		   		<td> IdExamen</td>
		   		<td> Examen </td>
		   		<td> IdArea </td>
		  		<td> Indicacion Medica </td>
		   		<td> Estado </td>
		   	</tr>";
		$pos=0;
	while($fila = pg_fetch_array($consultadetalle)){
          $imprimir .= "<tr>
				<td width='8%>".$fila['IdExamen']."</td>
				<td width='25%>".htmlentities($fila['NombreExamen'])."</td>	
          		        <td width='8%>".$fila['IdArea']."</td>";	
                 if (!empty($fila['Indicacion'])){     				
		   $imprimir .="<td width='20%>".htmlentities($fila['Indicacion'])."</td>";
		   $imprimir .="<td width='15%>".$fila['Estado']."</td>	
		       </tr>";
                }else{
		   $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>
				<td>".$fila['Estado']."</td>
                       </tr>";	
		}
                  $pos=$pos + 1;
        }

pg_free_result($consultadetalle);

 $imprimir .= "<input type='hidden' name='oculto' id='oculto' value='".$pos."' />
		</table>
		<div id='divImpresion' style='display:block' >
			<table aling='center'>
			<tr>
				<p></p>
			</tr>
			<tr>
				<td>
					<input type='button' name='btnImprimir' id='btnImprimir'  value='Imprimir Reporte' onClick='Imprimir()'>
					<input type='button' name='btnCerrar'  value='Cerrar' onClick='Cerrar()'></td>
					</td>
				</tr>
				</table>
			</div>
			</form>";
     echo $imprimir;
	 
	 
   	break;
	case 3://LLENANDO COMBO DE EMPLEADOS
                 
		$rslts='';
		$IdSubServicio=$_POST['idsubservicio'];
		$consulta=$objdatos->cantidadMedicos($IdSubServicio);
                $row = pg_fetch_array($consulta);
                $cantidad=$row['cantidad'];
                //echo $cantidad;
                
                if ($cantidad==0){
                    $dtmed=$objdatos->LlenarCmbMedicos($IdSubServicio);
			$rslts = '<select name="cboMedicos" id="cboMedicos"  style="width:443px" class="form-control height">';
			$rslts .='<option value="0">-- NO HAY MEDICOS --</option>';
				while ($rows =pg_fetch_array($dtmed)){
					$rslts.= '<option value="' . $rows['idemp'] .'" >'. $rows['nombre'].'</option>';
				//}
		}
                $rslts .='</select>';
			echo $rslts;
                    
                }else {
                
			$dtmed=$objdatos->LlenarCmbMedicos($IdSubServicio);
			$rslts = '<select name="cboMedicos" id="cboMedicos"  style="width:443px" class="form-control height">';
				$rslts .='<option value="0">--Seleccione un Medico--</option>';
				while ($rows =pg_fetch_array($dtmed)){
					$rslts.= '<option value="' . $rows['idemp'] .'" >'. $rows['nombre'].'</option>';
				//}
		}
                $rslts .='</select>';
			echo $rslts;

	
                }
	 
	
   break;	
   case 4:// Vista Previa Reporte 
   //echo $medico."+".$IdSubEsp."+".$especialidad."-".$fechainicio."-".$fechafin;
	$especialidad=$_POST['especialidad'];
	$fechainicio=$_POST['fechainicio'];
	$fechafin=$_POST['fechafin'];
	$medico=$_POST['medico'];
    	$query = "SELECT  sec_historial_clinico.IdNumeroExp,sec_solicitudestudios.IdSolicitudEstudio,
                  DATE_FORMAT(sec_solicitudestudios.FechaSolicitud ,'%e/ %m / %Y') AS FechaSolicitud,mnt_subservicio.NombreSubServicio AS origen, mnt_servicio.NombreServicio AS procedencia,mnt_empleados.NombreEmpleado AS medico, 
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
		
		
		//	ECHO $query_search;
             $consulta1=$objdatos->BuscarSolicitudesEspecialidad($query_search); 
			 
			$row1 = pg_fetch_array($consulta1);
  	$imprimir=" <table width='90%' border='0' align='center'>
		    <tr>
			<td colspan='7' align='center'><h3><strong>REPORTE DE SOLICITUDES POR ESPECIALIDAD
				</h3></strong></td>
		   </tr>
		   <tr>
			<td colspan='7' align='center'><h3><strong>".htmlentities($row1['procedencia'])."</h3></strong></td>
			</td>
		   </tr>
		   <tr>
			<td colspan='7' align='center'><h3><strong>".htmlentities($row1['origen'])."</h3></strong></td>
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
        break;
	case 6:// Llenar Combo Establecimiento
		$rslts='';
		$Idtipoesta=$_POST['idtipoesta'];
              // echo $Idtipoesta;
          
		 if ($Idtipoesta<>0){
                    $dtIdEstab=$objdatos->LlenarCmbEstablecimiento($Idtipoesta);
                    $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:500px" class="form-control height">';
                    //$rslts .='<option value="0"> Seleccione Establecimiento </option>';
                    while ($rows =pg_fetch_array( $dtIdEstab)){
                        $rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
                    }
		}else{
                    $dtIdEstab = $objdatos->LlenarTodosEstablecimientos();
                    $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:500px" class="form-control height">';
                    $rslts .='<option value="0"> Seleccione Establecimiento </option>';
                    while ($rows = pg_fetch_array($dtIdEstab)) {
                        $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
                    }
                }   
                
		$rslts .= '</select>';
		echo $rslts;
                
                	
                
                
   	break;
        
        
        
        
	case 7:// Llenar combo Subservicio
   	     $rslts='';
             $IdServ=$_POST['IdServicio'];
	   //  echo $IdServ;
	     $dtserv=$objdatos->LlenarCmbServ($IdServ,$lugar);
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:500px" onChange="BuscarMedicos(this.value)" class="form-control height">';
			$rslts .='<option value="0"> Seleccione Subespecialidad </option>';
			while ($rows =pg_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}
				
	      $rslts .='</select>';
	      echo $rslts;
        break;	
        case 11:// mensaje de alert
		echo" <center><table border='0' width='50%'>
                        <center> <tr>
                        <td width='30'>
                        <div width='10%' class='alert alert-info' role='alert'>
                        
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                            <span aria-hidden='true'> &times;</span>
                                    </button> 
                                            <strong>
                                                        <center>¡Complete el rango de las fechas!</center>
                                            </strong>
                                 </td>           
                           </tr> 
                           </center>
                    </div>
                    </table></center>";
		//echo "<div class='alert alert-info'>¡Complete el rango de las fechas!</center></div>";
   	break;

 }