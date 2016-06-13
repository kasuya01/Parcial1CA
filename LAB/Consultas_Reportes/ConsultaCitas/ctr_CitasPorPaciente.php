<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsCitasPorPaciente.php");

//variables POST

//$fechainicio=$_POST['fechainicio'];
 $opcion=$_POST['opcion'];
//$fechafin=$_POST['fechafin'];


//echo$idexpediente."".$primernombre."".$segundonombre."".$primerapellido."".$segundoapellido."".$especialidad;
//creando los objetos de las clases
$objdatos = new clsCitasPorPaciente;
//echo $idexpediente;
switch ($opcion) 
{
  	
    case 1:
        $ban = 0;
        $IdEstab        = $_POST['IdEstab'];
        $IdServ         = $_POST['IdServ'];
        $IdSubServ      = $_POST['IdSubServ'];
        //$idarea         = $_POST['idarea'];
        //$idexamen       = $_POST['idexamen'];
        $idexpediente   = $_POST['idexpediente'];
        //$fechasolicitud = $_POST['fechasolicitud'];
        $fecharecepcion = $_POST['fecha'];
        $PNombre        = $_POST['primernombre'];
        $SNomre         = $_POST['segundonombre'];
        $PApellido      = $_POST['primerapellido'];
        $SApellido      = $_POST['segundoapellido'];
        //$TipoSolic      = $_POST['TipoSolic'];
        $cond1="";
        $cond2="";
        $query="";
        $query2="";
        $where_with="";
        
         $idexpediente="'".$idexpediente."'";
         $cond0="and";
        
        
        
        
      //  echo $IdEstab." - ".$lugar;
        if (!empty($_POST['IdEstab'])) {
           if ($_POST['IdEstab']<>$lugar){
               $cond1 .=$cond0. "  t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " ";
               $cond2 .=$cond0. "  t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " ";
           }
           /* else {
               $cond1 .= $cond0. "  t02.id_establecimiento = " . $lugar . " ";
               $cond2 .= $cond0. "  t02.id_establecimiento_externo = " . $lugar . " ";
            }*/
          
        }
        /*
       
        
         if (!empty($_POST['IdSubServ'])) {
            $cond1 .= " t10.id = " . $_POST['IdSubServ'] . " AND";
            $cond2 .= " t10.id = " . $_POST['IdSubServ'] . " AND";
        }
         */
        
      /*   if ($_POST['IdServ'] <> 0) {
            $cond1 .= " t12.id  = " . $_POST['IdServ'] . " AND";
            $cond2 .= " t12.id  = " . $_POST['IdServ'] . " AND";
            $where_with = "t03.id = $IdServ AND ";
        }*/

        if ($_POST['IdServ'] <> 0) {
            $cond1 .=$cond0 ."  t12.id  = " . $_POST['IdServ'] . "     ";
            $cond2 .=$cond0 ."  t12.id  = " . $_POST['IdServ'] . "     ";
            $where_with = "t03.id = $IdServ AND ";
        }
        if (!empty($_POST['IdSubServ'])) {
            $cond1 .= $cond0." t10.id = " . $_POST['IdSubServ'] . "     ";
            $cond2 .= $cond0." t10.id = " . $_POST['IdSubServ'] . "     ";
        }
        

        if (!empty($_POST['idarea'])) {
            $cond1 .= " and t08.id = " . $_POST['idarea'] . " ";
            $cond2 .= " and t08.id = " . $_POST['idarea'] . " ";
        }

        if (!empty($_POST['idexpediente'])) {
          $idexpediente="'".$idexpediente."'";
            
            $cond1 .= "and t06.numero = '".$_POST['idexpediente'] ."'    ";
            $cond2 .= "and t06.numero = '".$_POST['idexpediente'] ."'   ";
        }

        if (!empty($_POST['idexamen'])) {
             $cond1 .= " and t04.id = " . $_POST['idexamen'] . " ";
             $cond2 .= " and t04.id = " . $_POST['idexamen'] . " ";
        }

        if (!empty($_POST['fechasolicitud'])) {
             $cond1 .= " and t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' ";
             $cond2 .= " and  t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' ";
        }

        if (!empty($_POST['fecha'])) {
             $cond1 .= " and t15.fecha = '".$_POST['fecha']."'       ";
             $cond2 .= " and t15.fecha = '".$_POST['fecha']."'       ";
        }

        if (!empty($_POST['primernombre'])) {
          
            $cond1 .= " and t07.primer_nombre  ILIKE  '".$_POST['primernombre']."%'      ";
            $cond2 .= " and  t07.primer_nombre ILIKE  '".$_POST['primernombre']."%'      ";
        }

        if (!empty($_POST['segundonombre'])) {
             $cond1 .= " and t07.segundo_nombre  ILIKE '". $_POST['segundonombre'] ."%'       ";
             $cond2 .= " and t07.segundo_nombre  ILIKE '". $_POST['segundonombre'] ."%'       ";
        }

        if (!empty($_POST['primerapellido'])) {
            $cond1 .= " and  t07.primer_apellido ILIKE '".$_POST['primerapellido']."%'         ";
            $cond2 .="  and  t07.primer_apellido ILIKE '".$_POST['primerapellido']."%'         ";
        }

        if (!empty($_POST['segundoapellido'])) {
            $cond1 .=" and t07.segundo_apellido ILIKE '".$_POST['segundoapellido']."%'       ";
            $cond2 .=" and t07.segundo_apellido ILIKE '".$_POST['segundoapellido']."%'       ";
        }

        if (!empty($_POST['TipoSolic'])) {
            $cond1 .= " and t17.idtiposolicitud = '".$_POST['TipoSolic']."'  ";
            $cond2 .= " and t17.idtiposolicitud = '".$_POST['TipoSolic']."'  ";
        }

        if ((empty($_POST['idexpediente'])) AND ( empty($_POST['idarea'])) AND ( empty($_POST['fecha']))
                AND ( empty($_POST['IdEstab'])) AND ( empty($_POST['IdServ'])) AND ( empty($_POST['IdSubServ']))
                AND ( empty($_POST['primernombre'])) AND ( empty($_POST['segundonombre'])) AND ( empty($_POST['primerapellido']))
                AND ( empty($_POST['segundoapellido'])) AND ( empty($_POST['idexamen'])) AND ( empty($_POST['TipoSolic']))) {
            $ban = 1;
        }
        
         // echo $cond2;
         $query="WITH tbl_servicio AS ( SELECT t02.id, 
                CASE WHEN t02.nombre_ambiente IS NOT NULL THEN 
                    CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura  ||'   -   ' || t02.nombre_ambiente 
                          
                    END 
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
                WHERE t02.id_establecimiento =  $lugar ORDER BY 2)
                    
                SELECT ordenar.* FROM (
                    SELECT distinct (TO_CHAR(t15.fecha, 'DD/MM/YYYY')) AS fechacita,t02.id AS idsolicitudestudio, t06.numero AS idnumeroexp, 
                    CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido, t07.segundo_apellido,t07.apellido_casada) AS paciente,
                    t20.servicio AS nombresubservicio, t20.procedencia AS nombreservicio, t02.impresiones, t14.nombre, t09.id AS idhistorialclinico, 
                    TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, t17.tiposolicitud AS prioridad, t07.fecha_nacimiento AS fechanacimiento, 
                    t19.nombre AS sexo, t02.id_establecimiento_externo, (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext 
                FROM sec_solicitudestudios t02 
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
                WHERE (t16.idestado = 'D') 
                AND t02.id_establecimiento = $lugar
                 $cond1
        
            UNION

             SELECT distinct (TO_CHAR(t15.fecha, 'DD/MM/YYYY')) AS fechacita,t02.id AS idsolicitudestudio, t06.numero AS idnumeroexp, 
                CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,t07.apellido_casada) AS paciente, t11.nombre AS nombresubservicio,t13.nombre AS nombreservicio,
                t02.impresiones,t14.nombre,t09.id AS idhistorialclinico, TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud,t17.tiposolicitud AS prioridad,t07.fecha_nacimiento AS fechanacimiento, 
                t19.nombre AS sexo, t02.id_establecimiento_externo,(SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext 
                FROM sec_solicitudestudios t02 
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
            WHERE (t16.idestado = 'D') 
            AND t02.id_establecimiento = $lugar 
             $cond2) ordenar
                ORDER BY to_date(ordenar.fechacita, 'DD/MM/YYYY') DESC"; 

			$consulta=$objdatos->BuscarCitasPaciente($query);  
					/*  ----------Datos para  Pacgianción----------------*/
					$RegistrosAMostrar=20;
					$NroRegistros= $objdatos->NumeroDeRegistros($query);
	
                      
         if ($NroRegistros==""){
                            $NroRegistros=0;
                            $imprimir= "<table width='70%' border='0'  align='center'>
          <center>
                <tr>
                        <td width='500'  align='center'  ><span style='color: #0101DF;'> <h4> TOTAL DE PACIENTES CITADOS:".$NroRegistros."</h4></span></td>
                </tr>
                </table>
                
                <table width='65%' border='0'  align='center'>
                   <td width='1550'></td>   <td > <button type='button'  class='btn btn-primary'  onclick='VistaPrevia(); '><span class='glyphicon glyphicon-print'></span> IMPRIMIR REPORTE </button> </td>
			<!--<td <td width='500'>  </td>  <td colspan='7'      style='color:#990000; font:bold'><a style ='text-decoration:underline;cursor:pointer; font:bold; size:36' onclick='VistaPrevia();'>IMPRIMIR REPORTE</a></td>	-->
		</tr>
          </center>
	</table> ";
                        }ELSE {
                            
                            $imprimir= "<table width='70%' border='0'  align='center'>
          <center>
                <tr>
                        <td width='500'  align='center'  ><span style='color: #0101DF;'> <h4> TOTAL DE PACIENTES CITADOS:".$NroRegistros."</h4></span></td>
                </tr>
                </table>
                
                <table width='70%' border='0'  align='center'>
                   <td width='1600'></td>   
                   <td><button type='button'  class='btn btn-primary'  onclick='VistaPrevia(); '><span class='glyphicon glyphicon-print'></span> IMPRIMIR REPORTE </button> </td>
			<!--<td <td width='500'>  </td>  <td colspan='7'   style='color:#990000; font:bold'><a style ='text-decoration:underline;cursor:pointer; font:bold; size:36' onclick='VistaPrevia();'>IMPRIMIR REPORTE</a></td>	-->
		</tr>
          </center>
	</table> ";
                        }
                        
                        
    
	
			$imprimir.="<center><div class='table-responsive' style='width: 70%;'>
                <table width='70%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
                    <thead>
                                <tr> 
						<th>Fecha cita</th>
						<th>NEC </th>
						<th>Nombre Paciente</th>
						<th>Origen</th>
						<th>Procedencia</th>
						<th>Establecimiento</th>
					</tr>
                    </thead><tbody>";  
		if(pg_num_rows($consulta)){
                    $pos = 0;
                        
			while ($row = pg_fetch_array($consulta))
			{ 
			$imprimir .="<tr>
						<td  width='6%'>".$row['fechacita']."</td>
						<td  width='5%'>".$row['idnumeroexp']."
						    <input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row[1]."' />".
						   "<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row['idnumeroexp']."' />
						</td>
						<td  width='30%'>".htmlentities($row['paciente'])."</td>
						<td  width='14%'>".htmlentities($row['nombresubservicio'])."</td>
						<td  width='15%'>".htmlentities($row['nombreservicio'])."</td>
						<td  width='30%'>".htmlentities($row['estabext'])."</td>
					</tr>";
				$pos=$pos + 1;
			} 
                        
		
			pg_free_result($consulta);
		
		$imprimir .= "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
			</table>";
		echo $imprimir;
			
         } else {
             $imprimir .="<tr><td colspan='11'><span style='color: #575757;'>No se han encontrado resultados...</span></td></tr></table><br><br>";
                echo $imprimir;
             
         }
                
	break;
	case 5://LLENANDO COMBO DE Examenes
		$rslts='';
		
		$idarea=$_POST['idarea'];
		$dtExam=$objdatos->ExamenesPorArea($idarea,$lugar);	
		$rslts = '<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:500px">';
		$rslts .='<option value="0"> Seleccione Examen </option>';
			
		while ($rows =pg_fetch_array($dtExam)){
			$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;
		
	
   	break;
	
	case 6:// Llenar Combo Establecimiento
		$rslts='';
		$Idtipoesta=$_POST['idtipoesta'];
               	/*$dtIdEstab=$objdatos->LlenarCmbEstablecimiento($Idtipoesta);
              	$rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:375px" class="form-control height">';
		$rslts .='<option value="0"> Seleccione Establecimiento </option>';
               while ($rows =pg_fetch_array( $dtIdEstab)){
		  $rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       }*/
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
                    $rslts .='<option value="0"> -- No Hay Establecimiento -- </option>';
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
	     $dtserv=$objdatos->LlenarCmbServ($IdServ,$lugar);
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:500x" class="form-control height">';
             $rslts .='<option value="0"> Seleccione SubServicio </option>';
			while ($rows =pg_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}
				
	      $rslts .='</select>';
	      echo $rslts;
              
              
                

             
        break;	
    	
		
}

?>