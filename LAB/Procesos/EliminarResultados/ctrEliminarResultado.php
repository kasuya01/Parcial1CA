<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsEliminarResultado.php");

//variables POST

$opcion=$_POST['opcion'];

//$pag =$_POST['pag'];
//creando los objetos de las clases
$objdatos = new clsEliminarResultado;

//echo $idexpediente;
switch ($opcion) 
{
  	case 1: 
                $ban=0;
		$IdEstab=$_POST['IdEstab'];
		$IdServ=$_POST['IdServ'];
 		$IdSubServ=$_POST['IdSubServ'];
                $idexpediente=$_POST['idexpediente'];
		$primernombre=$_POST['primernombre'];
		$segundonombre=$_POST['segundonombre'];
		$primerapellido=$_POST['primerapellido'];
		$segundoapellido=$_POST['segundoapellido'];
		$fecharecep=$_POST['fecharecep'];
		$cond1="";
                $cond2="";
                $condf1="";
                $condf2="";
                $where_with="";
             //  echo $IdEstab;
                if ($_POST['IdEstab']<>0) {
                    if ($_POST['IdEstab']<>$lugar){
                        $cond1 .= " t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " AND";
                        $cond2 .= " t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " AND";
                    }
                    else{
                         $cond1 .= " t02.id_establecimiento_externo = " . $lugar . " AND";
                         $cond2 .= " t02.id_establecimiento_externo = " . $lugar . " AND";
                    }
                   
                 }
                  if ($_POST['IdServ'] <> 0) {
            $cond1 .= " t12.id  = " . $_POST['IdServ'] . " AND";
            $cond2 .= " t12.id  = " . $_POST['IdServ'] . " AND";
            $where_with = "t03.id = $IdServ AND ";
        }

        if (!empty($_POST['IdSubServ'])) {
            $cond1 .= " t10.id = " . $_POST['IdSubServ'] . " AND";
            $cond2 .= " t10.id = " . $_POST['IdSubServ'] . " AND";
        }
               
                 
                 if (!empty($_POST['idexpediente'])) {
                    $cond1 .= " t06.numero = '" . $_POST['idexpediente'] . "' AND";
                    $cond2 .= " t06.numero = '" . $_POST['idexpediente'] . "' AND";
                 }
                 
                 if (!empty($_POST['primernombre'])) {
                    $cond1 .= " t07.primer_nombre ILIKE '" . $_POST['primernombre'] . "%' AND";
                    $cond2 .= " t07.primer_nombre ILIKE '" . $_POST['primernombre'] . "%' AND";
                }

                if (!empty($_POST['segundonombre'])) {
                    $cond1 .= " t07.segundo_nombre ILIKE '" . $_POST['segundonombre'] . "%' AND";
                    $cond2 .= " t07.segundo_nombre ILIKE '" . $_POST['segundonombre'] . "%' AND";
                }

                if (!empty($_POST['primerapellido'])) {
                    $cond1 .= " t07.primer_apellido ILIKE '" . $_POST['primerapellido'] . "%' AND";
                    $cond2 .= " t07.primer_apellido ILIKE '" . $_POST['primerapellido'] . "%' AND";
                }

                if (!empty($_POST['segundoapellido'])) {
                    $cond1 .= " t07.segundo_apellido ILIKE '" . $_POST['segundoapellido'] . "%' AND";
                    $cond2 .= " t07.segundo_apellido ILIKE '" . $_POST['segundoapellido'] . "%' AND";
                }
                 if((empty($_POST['idexpediente'])) AND (empty($_POST['primerapellido'])) AND (empty($_POST['segundoapellido'])) 
                         AND (empty($_POST['primernombre'])) AND (empty($_POST['segundonombre']))AND (empty($_POST['IdEstab'])) 
                         AND (empty($_POST['IdServ'])) AND (empty($_POST['IdSubServ'])) AND (empty($_POST['fecharecep'])))
		{
				$ban=1;
		}
			
		if ($ban==0)
		{   
                    $cond1 = substr($cond1, 0, strlen($query) - 3);
                    $cond2 = substr($cond2, 0, strlen($query) - 3);
                    $var1 = $lugar." AND ".$cond1;
                    $var2 = $lugar." AND ".$cond2;
                   
		}
                    
                else{

                    $var1 = $lugar;
                    $var2 = $lugar;
                }
               //echo "var1=".$var1;
                
            $query = "WITH tbl_servicio AS ( SELECT t02.id,
                CASE WHEN t02.nombre_ambiente IS NOT NULL THEN
                    t02.nombre_ambiente
                    ELSE
                            CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura  ||'   -   ' ||  t01.nombre
                                 WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=t01.nombre)
                                   
                                    THEN t01.nombre
                    END

                END AS servicio,
               (CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'-->'  || t06.nombre
                    ELSE   t07.nombre ||'-->' || t06.nombre
                END) as procedencia
                FROM ctl_atencion t01 
                INNER JOIN mnt_aten_area_mod_estab t02 ON (t01.id = t02.id_atencion) 
                INNER JOIN mnt_area_mod_estab t03 ON (t03.id = t02.id_area_mod_estab) 
                LEFT JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab) 
                LEFT JOIN mnt_servicio_externo t05 ON (t05.id = t04.id_servicio_externo) 
                INNER JOIN  ctl_area_atencion t06  on  t06.id = t03.id_area_atencion
                INNER JOIN ctl_modalidad  t07 ON t07.id = t03.id_modalidad_estab
                    WHERE $where_with t02.id_establecimiento = $lugar
                    ORDER BY 2)
            SELECT ordenar.* FROM (
                SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                t02.id AS idsolicitudestudio, 
                t03.numeromuestra, 
                t06.numero AS idnumeroexp, 
                t03.id AS idrecepcionmuestra, 
                CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,
                t07.tercer_nombre,
                t07.primer_apellido, 
                t07.segundo_apellido,
                t07.apellido_casada) AS paciente, 
                t20.servicio AS nombresubservicio, 
                t20.procedencia AS nombreservicio,
                t02.impresiones,
                t14.nombre, 
                t09.id AS idhistorialclinico, 
                TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, 
                t17.tiposolicitud AS prioridad, 
                t07.fecha_nacimiento AS fechanacimiento, 
                t19.id AS sexo,
                t02.id_establecimiento_externo, 
                (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                false AS referido,
                (SELECT descripcion FROM ctl_estado_servicio_diagnostico WHERE id=t02.estado) AS estado ,
                t02.id_expediente
                FROM sec_solicitudestudios t02  
                INNER JOIN lab_recepcionmuestra t03 ON (t02.id = t03.idsolicitudestudio) 
                INNER JOIN mnt_expediente t06 ON (t06.id = t02.id_expediente) 
                INNER JOIN mnt_paciente t07 ON (t07.id = t06.id_paciente) 
                INNER JOIN sec_historial_clinico t09 ON (t09.id = t02.id_historial_clinico) 
                INNER JOIN mnt_aten_area_mod_estab t10 ON (t10.id = t09.idsubservicio) 
                INNER JOIN ctl_atencion t11 ON (t11.id = t10.id_atencion) 
                INNER JOIN mnt_area_mod_estab t12 ON (t12.id = t10.id_area_mod_estab) 
                INNER JOIN ctl_area_atencion t13 ON (t13.id = t12.id_area_atencion) 
                INNER JOIN ctl_establecimiento t14 ON (t14.id = t09.idestablecimiento) 
                INNER JOIN cit_citas_serviciodeapoyo t15 ON (t02.id = t15.id_solicitudestudios) 
                INNER JOIN ctl_estado_servicio_diagnostico t16 ON (t16.id = t02.estado) 
                INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud) 
                INNER JOIN ctl_sexo t19 ON (t19.id = t07.id_sexo) 
                INNER JOIN tbl_servicio t20 ON (t20.id = t10.id AND t20.servicio IS NOT NULL) 
                
                WHERE (t16.idestado = 'P' OR t16.idestado = 'C') AND t02.id_establecimiento = $var1
        
                UNION

                SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion, t02.id AS idsolicitudestudio, 
                t03.numeromuestra, t06.numero AS idnumeroexp, t03.id AS idrecepcionmuestra, 
                CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido, t07.apellido_casada) AS paciente, 
                t11.nombre AS nombresubservicio, t13.nombre AS nombreservicio, t02.impresiones, t14.nombre, t09.id AS idhistorialclinico,
                TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, t17.tiposolicitud AS prioridad,
                t07.fecha_nacimiento AS fechanacimiento, t19.id AS sexo, t02.id_establecimiento_externo, 
                (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext, true AS referido,
                (SELECT descripcion FROM ctl_estado_servicio_diagnostico WHERE id=t02.estado) AS estado,t02.id_dato_referencia 
                FROM  sec_solicitudestudios t02
                INNER JOIN lab_recepcionmuestra t03 ON (t02.id = t03.idsolicitudestudio) 
                INNER JOIN mnt_dato_referencia t09 ON t09.id=t02.id_dato_referencia 
                INNER JOIN mnt_expediente_referido t06 ON (t06.id = t09.id_expediente_referido) 
                INNER JOIN mnt_paciente_referido t07 ON (t07.id = t06.id_referido) 
                INNER JOIN mnt_aten_area_mod_estab t10 ON (t10.id = t09.id_aten_area_mod_estab) 
                INNER JOIN ctl_atencion t11 ON (t11.id = t10.id_atencion) 
                INNER JOIN mnt_area_mod_estab t12 ON (t12.id = t10.id_area_mod_estab) 
                INNER JOIN ctl_area_atencion t13 ON (t13.id = t12.id_area_atencion) 
                INNER JOIN ctl_establecimiento t14 ON (t14.id = t09.id_establecimiento) 
                INNER JOIN cit_citas_serviciodeapoyo t15 ON (t02.id = t15.id_solicitudestudios) 
                INNER JOIN ctl_estado_servicio_diagnostico t16 ON (t16.id = t02.estado) 
                INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud) 
                INNER JOIN ctl_sexo t19 ON (t19.id = t07.id_sexo) 
                WHERE (t16.idestado = 'P' OR t16.idestado = 'C') AND t02.id_establecimiento = $var2 ) ordenar
         ORDER BY to_date(ordenar.fecharecepcion, 'DD/MM/YYYY') DESC";
            
         // echo $query;
		
		$consulta=$objdatos->BuscarSolicitudesPaciente($query); 
		$NroRegistros= $objdatos->NumeroDeRegistros($query);				

     $imprimir="<table width='100%' border='0' align='center'>
		    <tr>
			<td colspan='7' align='center' ><h3><strong>TOTAL DE SOLICITUDES: ".$NroRegistros."</strong></h3></td>
		    </tr>
		</table> "; 
    $imprimir.="<center>
        <div class='table-responsive' style='width: 85%;'>
                <table width='85%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
			<thead><tr>
				<th>Fecha Recepci&oacute;n</th>
				<th>NEC </th>
				<th>Nombre Paciente</th>
                                <th>Procedencia</th>
				<th>Servicio</th>
				<th>Establecimiento</th>
				<th>Estado Solicitud</th>
				<th>Fecha Consulta</th>
			</tr></thead><tbody>";    
		$pos=0;
		while ($row = pg_fetch_array($consulta))
		{ 
			$Idsolic=$row['idsolicitudestudio'];
			$fecha=$objdatos->BuscarRecepcion($Idsolic);
			$recepcion= pg_fetch_array($fecha);
			$fechacita=$objdatos->BuscarCita($Idsolic);
			$cita= pg_fetch_array($fechacita);
			if (!empty($recepcion)){
            $imprimir .="<tr>
				<td>".$recepcion['0']."</td>";
			}else{ 		
	   $imprimir .="<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			}
			
	           $imprimir .="<td><a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".$row['idnumeroexp']."</a>". 
					"<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["idsolicitudestudio"]."' />".
					"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["id_expediente"]."' /></td>".
                                        "<input name='expediente[".$pos."]' id='expediente[".$pos."]' type='hidden' size='60' value='".$row["idnumeroexp"]."' /></td>".
				"<td>".htmlentities($row['paciente'])."</td>
                                 <td>".htmlentities($row['nombreservicio'])."</td>    
				 <td>".htmlentities($row['nombresubservicio'])."</td>
				 <td>".htmlentities($row['estabext'])."</td>
				 <td>".$row['estado']."</td>
				 <td width='5%'>".$row['fechasolicitud']."</td>
			</tr>";

				$pos=$pos + 1;
			}
			
			PG_free_result($consulta);
			
		$imprimir .= "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
  
		</tbody></table></div>";
    			echo $imprimir;
		
		
		
	break;
    	
	case 2:  
	 
        break;
        case 3:
		//recuperando los valores generales de la solicitud
		$idexpediente=$_POST['idexpediente'];
		$idsolicitud=$_POST['idsolicitud'];
                $expediente=$_POST['expediente'];
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
		$row = pg_fetch_array($consulta);
               // echo $expediente;
		//obteniedo los datos generales de la solicitud
		$medico=$row['medico'];
		//$idmedico=$row['id_empleado'];
		$paciente=$row['paciente'];
		$edad=$row['edad'];
		$sexo=$row['sexo'];
		$precedencia=$row['procedencia'];
		$origen=$row['subservicio'];
		//$DatosClinicos=$row['DatosClinicos'];
		//$Estado=$row['estado'];
		//$fechasolicitud=$row['fechasolicitud'];
		$fecharecep=$row['fecharecep'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idsolicitud);
                $cant= $objdatos->ContarDatosDetalleSolicitud($idsolicitud);
                if($cant>=1){
                           // echo $cantidad = pg_fetch_array($cant);
                    $imprimir="<form name='frmDatos'>
                                <table width='50%' border='0' align='center' >
                                <tr>
                                    <td colspan='4' align='center' class='CobaltFieldCaptionTD'><h3><strong>DATOS SOLICITUD</strong></h3></td>
                                </tr>
                                <tr>    
                                    <th class='StormyWeatherFieldCaptionTD'>Establecimiento:</th>
                                    <td class='StormyWeatherDataTD' colspan='3'>".$row['estabext']."</td>
                                </tr>
                                <tr>
                                    <th class='StormyWeatherFieldCaptionTD'>Procedencia: </th>
                                    <td class='StormyWeatherDataTD'>".$precedencia."</td>
                                    <th class='StormyWeatherFieldCaptionTD'>Origen: </th>
                                    <td class='StormyWeatherDataTD'>".htmlentities($origen)."
                                                    <input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled'/>
                                                    <input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled'/></td>
                                </tr>
                                <tr>
                                    <th class='StormyWeatherFieldCaptionTD' width='25%'>Paciente: </th>
                                    <td class='StormyWeatherDataTD' >".htmlentities($paciente)."</td>
                                    <td class='StormyWeatherFieldCaptionTD'>Expediente</td>
                                    <td class='StormyWeatherDataTD'>".$expediente."</td>      
                                </tr>
                                <tr>
                                    <th class='StormyWeatherFieldCaptionTD'>Edad: </th>
                                    <td class='StormyWeatherDataTD'>". $edad."</td>
                                    <th class='StormyWeatherFieldCaptionTD'>Sexo: </th>
                                    <td class='StormyWeatherDataTD'>".$sexo."</td>
                                </tr>
                                <tr>
                                    <th class='StormyWeatherFieldCaptionTD'>Medico: </th>
                                    <td class='StormyWeatherDataTD'>".htmlentities($medico)."</td>
                                    <th class='StormyWeatherFieldCaptionTD'>Fecha Recepcion</th>
                                    <td class='StormyWeatherDataTD'>".$fecharecep."</td>
                                </tr>
                                </table>
                                <br><br>
                                <table width='90%' border='0' align='center'>
                                <tr>
                                    <td>
                                        <table border = 1 align='center' class='estilotabla' >
                                        <tr>
                                           <td colspan='6' class='CobaltFieldCaptionTD' align='center' >ESTUDIOS SOLICITADO</td>
                                        </tr>
                                        <tr>
                                            <td class='CobaltFieldCaptionTD'> Eliminar</td>
                                            <td class='CobaltFieldCaptionTD'> Código</td>
                                            <td class='CobaltFieldCaptionTD'> Examen </td>
                                            <td class='CobaltFieldCaptionTD'> IdArea </td>
                                            <td class='CobaltFieldCaptionTD'> Indicacion Medica </td>
                                            <td class='CobaltFieldCaptionTD'> Estado </td>
                                      </tr>";
                                            $pos=0;
                    while($fila = pg_fetch_array($consultadetalle)){
                        $imprimir .= "<tr>";
                         if($fila['estado']=="Resultado Completo")  { 	
                               $imprimir .="<td><img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" onclick=\"EliminarDatos('".$fila['iddetallesolicitud']."','".$fila['idsolicitudestudio']."','".$idexpediente."','".$fila['idplantilla']."')\"></td>
                                            <td>".htmlentities($fila['idestandar'])."</td>
                                            <td>".htmlentities($fila['nombre_examen'])."	
                                                    <input name='iddetalle[".$pos."]' type='hidden' id='iddetalle[".$pos."]' value='".$fila['iddetallesolicitud']."'>
                                                    <input name='idsolicitud[".$pos."]' type='hidden' id='idsolicitud[".$pos."]' value='".$fila['idsolicitudestudio']."'>
                                                    <input name='idexpediente[".$pos."]' type='hidden' id='idexpediente[".$pos."]' value='".$idexpediente."'></td>
                                                    <input name='idplantilla[".$pos."]' type='hidden' id='idplantilla[".$pos."]' value='".$fila['idplantilla']."'></td>    
                                            <td>".$fila['idarea']."</td>";	
                             if (!empty($fila['indicacion']))     				
                               $imprimir .="<td>".htmlentities($fila['indicacion'])."</td>";
                             else
                               $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>";
                               $imprimir .="<td>".$fila['estado']."</td>";
                           $imprimir .="</tr>";
                       }
                            $pos=$pos + 1;

                    }



                     $imprimir .= "<input type='hidden' name='oculto' id='oculto' value='".$pos."' />
                                        </table>

                                        </form>";
                     echo $imprimir;
                    pg_free_result($consultadetalle);
                }else{
                     $imprimir=" <table width='50%' border='0' align='center' >
                                <tr>
                                    <td colspan='4' align='center' class='CobaltFieldCaptionTD'><h3><strong>DATOS SOLICITUD</strong></h3></td>
                                </tr>
                                <tr>    
                                    <th class='StormyWeatherFieldCaptionTD'>Establecimiento:</th>
                                    <td class='StormyWeatherDataTD' colspan='3'>".$row['estabext']."</td>
                                </tr>
                                <tr>
                                    <th class='StormyWeatherFieldCaptionTD'>Procedencia: </th>
                                    <td class='StormyWeatherDataTD'>".$precedencia."</td>
                                    <th class='StormyWeatherFieldCaptionTD'>Origen: </th>
                                    <td class='StormyWeatherDataTD'>".htmlentities($origen)."
                                                    <input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled'/>
                                                    <input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled'/></td>
                                </tr>
                                <tr>
                                    <th class='StormyWeatherFieldCaptionTD' width='25%'>Paciente: </th>
                                    <td class='StormyWeatherDataTD' >".htmlentities($paciente)."</td>
                                    <td class='StormyWeatherFieldCaptionTD'>Expediente</td>
                                    <td class='StormyWeatherDataTD'>".$expediente."</td>      
                                </tr>
                                <tr>
                                    <th class='StormyWeatherFieldCaptionTD'>Edad: </th>
                                    <td class='StormyWeatherDataTD'>". $edad."</td>
                                    <th class='StormyWeatherFieldCaptionTD'>Sexo: </th>
                                    <td class='StormyWeatherDataTD'>".$sexo."</td>
                                </tr>
                                <tr>
                                    <th class='StormyWeatherFieldCaptionTD'>Medico: </th>
                                    <td class='StormyWeatherDataTD'>".htmlentities($medico)."</td>
                                    <th class='StormyWeatherFieldCaptionTD'>Fecha Recepcion</th>
                                    <td class='StormyWeatherDataTD'>".$fecharecep."</td>
                                </tr>
                                <tr>
                                    <td colspan='4' align='center' class='StormyWeatherDataTD' ><h3><strong> No Hay Resultados para Eliminar de esta Solicitud</strong></h3></td>
                                </tr>
                                </table>";
                     echo  $imprimir;
                }
                    
  break;
  case 4:

	include_once("clsEliminarResultado.php");
		$iddetalle=$_POST['iddetalle'];
		$idexpediente=$_POST['idexpediente'];
		$idsolicitud=$_POST['idsolicitud'];
                $idplantilla=$_POST['idplantilla'];
                //echo "iddetalle=".$iddetalle ;
		$dato=$objdatos->VerificaDetalle($idsolicitud,$iddetalle);
			 if ($dato == 1){
                //            echo "entro".$idplantilla;
				switch($idplantilla){
					case 1:/* ELIMINAR PLANTILLA A */
                                            $r=$objdatos->ObtenerIdResultado($idsolicitud,$iddetalle);
					    $result=pg_fetch_array($r);
					    $idresultado=$result[0];
						if ($objdatos->EliminarResultado($idresultado) == 1){
                                                    if ($objdatos->Eliminar_metodologia($iddetalle) == 1){
							//if (($objdatos->CambiarEstadoDetalle($iddetalle)==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==true))
							if (($objdatos->ActualizarEstadoDetalle($iddetalle)==true)&&($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true)){
								echo "Resultado Eliminado";}
                                                                
                                                    }            
						}
						else{
							echo "No se pudo eliminar el registro";
						}
							//echo "ENTRO A";
                                         break;	
					 case 2:/* ELIMINAR PLANTILLAS B,D Y E */
					 case 4:
					 case 5:
                                               //  echo $iddetalle;
						 $r=$objdatos->ObtenerIdResultado($idsolicitud,$iddetalle);
						 $result=pg_fetch_array($r);
						 $idresultado=$result[0];
                                                // echo $idresultado;
                                                 if($dr=$objdatos->Eliminar_metodologia($iddetalle)==1){
                                                     
                                                     if($dr=$objdatos->EliminarDetalleResultado($idresultado)==1){
                                                        
                                                         if ($objdatos->EliminarResultado($idresultado) == 1){
                                                                       
                                                             
                                                               if (($objdatos->ActualizarEstadoDetalle($iddetalle)==true)||($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true))
                                                                      echo "Resultado Eliminado";
                                                         }
                                                         else 
                                                             echo "No se pudo eliminar el registro";
                                                     }
                                                     else
                                                         echo "No se pudo eliminar el registro";
                                                 }
                                                 else
                                                         echo "No se pudo eliminar el registro";
                                                 
						  
					  // echo "ENTRO B,D,E";
					 break; 	
                                        case 3: /* ELIMINAR PLANTILLA C */
						$ban=0;
                                               $con = new ConexionBD;
                                                if($con->conectar()==true) 
                                                {
                                                        $query = "select id, idexamen from lab_conf_examen_estab where idexamen=(select id from  mnt_area_examen_establecimiento where id_examen_servicio_diagnostico=303) ";
                                                        $result_antib = pg_query($query);
                                                        $row_antibio = pg_fetch_array($result_antib);
                                                        $id_antib = $row_antibio['id'];
                                                        
                                                        $query1 = "select id, idexamen from lab_conf_examen_estab where idexamen=(select id from  mnt_area_examen_establecimiento where id_examen_servicio_diagnostico=352) ";
                                                        $result_res = pg_query($query1);
                                                        $row_res = pg_fetch_array($result_res);
                                                        $id_res = $row_res['id'];
                                                        $mnt_res= $row_res['idexamen'];
                                                        
                                                        $query2 = "select id,idexamen from lab_conf_examen_estab where idexamen=(select id from  mnt_area_examen_establecimiento where id_examen_servicio_diagnostico=345) ";
                                                        $result_bio = pg_query($query2);
                                                        $row_bio = pg_fetch_array($result_bio);
                                                        $id_bio = $row_bio['id'];
                                                        $mnt_bio=$row_bio['idexamen'];

                                                
                                                
                                                }
                                                //echo $idantbio=$objdatos->ObtenerIdAntibiograma();
						//$resultantib=pg_fetch_array($rantbio);
						//echo $idantbio;
                                            
                                            
                                                
						$r=$objdatos->ObtenerIdResultado($idsolicitud,$iddetalle);
								//$idresultado=$result['IdResultado'];
                                                
							
						while($result = pg_fetch_array($r)){
						   // echo "entro".$idplantilla;
                                                    $idresultado=$result['id']; 
								// echo $iddetalle;
								//$tr=$objetos->ObtenerTipoResultado($idresultado);
								//$tipo=mysql_fetch_array($tr);
					         $TipoResultado=$result['resultado'];
								//echo " tipo=".$TipoResultado;
								switch($TipoResultado){
								case 'Positivo':
						//	 while($result = mysql_fetch_array($r)){
                                                                   // $idresultado=$result['id'];
                                                                  // echo $idresultado;
                                                                    $rp=$objdatos->ObtenerIdResultadoPadre($idresultado);
                                                                    while($resultp = pg_fetch_array($rp)){
                                                                        $IdResultadop=$resultp['id'];
                                                                        $IdDetallep=$resultp['iddetallesolicitud'];
                                                                   $idexamenp=$resultp['idexamen'];
                                                                        //echo $idexamenp." - ".$id_antib;
                                                                        if ($idexamenp == $id_antib){
                                                                              //  echo  $IdResultadop;
                                                                                 $det=$objdatos->ObtenerIdDetalleRes($IdResultadop);
                                                                                 $detalle=pg_fetch_array($det);
                                                                                 $iddetalleres=$detalle[0];
                                                                 //	echo "SOL=".$idsolicitud." iddet".$iddetalle." idresultado".$idresultado." lo demas". $iddetalleres;
                                                                               if($dr=$objdatos->Eliminar_metodologia($IdDetallep)==1){  
                                                                                     if($dr=$objdatos->EliminarResultadoTarjeta($iddetalleres)==1){
                                                                                         if($dr=$objdatos->EliminarDetalleResultado($IdResultadop)==1){
                                                                                            if ($objdatos->EliminarResultado($IdResultadop) == 1){
                                                                                                 if (($objdatos->CancelarEstadoDetalle($IdDetallep)==true)||($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true))
                                                                                                     //echo "Resultado Eliminado";
                                                                                                  //   echo $IdDetallep;
                                                                                                     $ban=0;
                                                                                                 }
                                                                                                 else
                                                                                                     //echo "No se pudo eliminar el registro";
                                                                                                    $ban=1;
                                                                                             }
                                                                                             else
                                                                                                 //echo "No se pudo eliminar el registro";
                                                                                                $ban=1;
                                                                                     }else
                                                                                          //echo "No se pudo eliminar el registro";
                                                                                         $ban=1;
                                                                                }
                                                                       
                                                                        }else if (($idexamenp != $id_res) && ($idexamenp != $id_bio)){
                                                                                    if($dr=$objdatos->Eliminar_metodologia($IdDetallep)==1){
                                                                                        if($objdatos->EliminarResultado($IdResultadop) == 1){
                                                                                   // if ($idexamenp != $id_res && $idexamenp != $id_bio){
                                                                                            if (($objdatos->ActualizarEstadoDetalle($IdDetallep)==true)||($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true)){
                                                                                      //  echo "Resultado Eliminado";
                                                                                                 $ban=0;
                                                                                            }
                                                                                            else{
                                                                                                $ban=1;
                                                                                       // echo "No se pudo eliminar el registro";
                                                                                            }
                                                                                    
                                                                                   /* }else if
                                                                                        if($objdatos->CancelarEstadoDetalle($IdDetallep)==true){
                                                                                             echo  $ban=0;
                                                                                        }
                                                                                         else{
                                                                                            $ban=1;
                                                                                       // echo "No se pudo eliminar el registro";
                                                                                        }
                                                                                    }
                                                                                        
                                                                                    }*/
                                                                                        }else{
                                                                                            $ban=1; 
                                                                                    // echo "No se pudo eliminar el registro";    
                                                                                        }
                                                                                    }else{
                                                                                        $ban=1;   
                                                                                             //echo "No se pudo eliminar el registro";
                                                                                    }
                                                                        }   
                                                                        else{ 
                                                                            if($dr=$objdatos->Eliminar_metodologia($IdDetallep)==1){
                                                                                if ($objdatos->EliminarResultado($IdResultadop) == 1){
                                                                                   // if ($idexamenp != $id_res && $idexamenp != $id_bio){
                                                                                            if (($objdatos->CancelarEstadoDetalle($IdDetallep)==true)||($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true)){
                                                                                      //  echo "Resultado Eliminado";
                                                                                                $ban=0;
                                                                                            }
                                                                                            else{
                                                                                                $ban=1;
                                                                                       // echo "No se pudo eliminar el registro";
                                                                                            }
                                                                                    
                                                                                   /* }else if
                                                                                        if($objdatos->CancelarEstadoDetalle($IdDetallep)==true){
                                                                                             echo  $ban=0;
                                                                                        }
                                                                                         else{
                                                                                            $ban=1;
                                                                                       // echo "No se pudo eliminar el registro";
                                                                                        }
                                                                                    }
                                                                                        
                                                                                    }*/
                                                                                        }else{
                                                                                            $ban=1; 
                                                                                    // echo "No se pudo eliminar el registro";    
                                                                                        }
                                                                                    }else{
                                                                                        $ban=1;   
                                                                                             //echo "No se pudo eliminar el registro";
                                                                                    }
                                                                        }
                                                                        //echo $ban;
                                                                       

                                                                    }//while
                                                                     if ($ban==0){
                                                                            echo "Resultado Eliminado";
                                                                        }else
                                                                            echo "No se pudo eliminar el registro";
								break;
								case 'Negativo':
								case '---':
									//echo "idresultado=".$idresultado." iddetalle=".$iddetalle;
                                                                        $rp=$objdatos->ObtenerIdResultadoPadre($idresultado);
                                                                    while($resultp = pg_fetch_array($rp)){
                                                                        $IdResultadop=$resultp['id'];
                                                                        $IdDetallep=$resultp['iddetallesolicitud'];
                                                                        $idexamenp=$resultp['idexamen'];
                                                                        if ($idexamenp == $id_res){
                                                                            if($dr=$objdatos->Eliminar_metodologia($IdDetallep)==1){
                                                                                if ($objdatos->EliminarResultado($IdResultadop) == 1){
                                                                                    if (($objdatos->CancelarEstadoDetalle($IdDetallep)==true)||($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true)){
                                                                                        $ban=0;
                                                                                    }
                                                                                    else{
                                                                                        $ban=1;}
                                                                                }else {
                                                                                    $ban=1;
                                                                                }        
                                                                            }else{
                                                                                    $ban=1;} 
                                                                                    // echo "No se pudo eliminar el registro";    
                                                                            
                                                                        }      
                                                                                    
                                                                        else{
                                                                               if($dr=$objdatos->Eliminar_Metodologia($iddetalle)==1){  
                                                                                    if ($objdatos->EliminarResultado($idresultado) == 1){	 
											if (($objdatos->ActualizarEstadoDetalle($iddetalle)==true)||($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true)){
												$ban=0;
                                                                                                
                                                                                        }
                                                                                        else{
                                                                                            $ban=1;}
                                                                                    }
                                                                                    else{
                                                                                        $ban=1;}
											
										}else
											 $ban=1;
                                                                              		
                                                                        }
                                                                    }// while
                                                                     if ($ban==0){
                                                                            echo "Resultado Eliminado";
                                                                        }else
                                                                            echo "No se pudo eliminar el registro";
                                                                    
                                                                    
                                                                    
                                                                  
								break; 
								}	
										
						}// while	
								   
					 break;
			
	          }//switch de plantilla	
	   }else{
		     echo "NO HAY DATOS";}
	// }
 break;
case 5://LLENANDO COMBO DE Examenes
		$rslts='';
		
		$idarea=$_POST['idarea'];
		//echo $IdSubEsp;
		$dtExam=$objdatos->ExamenesPorArea($idarea,$lugar);	
		$rslts = '<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:250px">';
		$rslts .='<option value="0"> Seleccione Examen </option>';
			
		while ($rows =mysql_fetch_array($dtExam)){
			$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;
		
	
   	break;
	
	case 6:// Llenar Combo Establecimiento
		$rslts='';
		$Idtipoesta=$_POST['idtipoesta'];
              // echo $Idtipoesta;
                
                if ($Idtipoesta<>0){
                    $dtIdEstab=$objdatos->LlenarCmbEstablecimiento($Idtipoesta);
                    $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:500px height:20px " class="js-example-basic-single">';
                    //$rslts .='<option value="0"> Seleccione Establecimiento </option>';
                    while ($rows =pg_fetch_array( $dtIdEstab)){
                        $rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
                    }
		}else{
                    $dtIdEstab = $objdatos->LlenarTodosEstablecimientos();
                    $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:500px" class="js-example-basic-single">';
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
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:500px" class="form-control height">';
			$rslts .='<option value="0"> Seleccione Subespecialidad </option>';
			while ($rows =pg_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}
				
	      $rslts .='</select>';
	      echo $rslts;
        break;	
        
      

}//switch de opciones

?>