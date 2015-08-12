<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsConsultaResultados.php");

//variables POST

$opcion=$_POST['opcion'];

//$usuario=1;

//creando los objetos de las clases
$objdatos = new clsConsultaResultados;

switch ($opcion) 
{
  	case 1:  
		$idexpediente=$_POST['idexpediente'];
		$idarea=$_POST['idarea'];
		$idexamen=$_POST['idexamen'];
		$fecharecep=$_POST['fecharecep'];
		$IdEstab=$_POST['IdEstab'];//ya
		$IdServ=$_POST['IdServ'];
 		$IdSubServ=$_POST['IdSubServ'];
		$PNombre=$_POST['PNombre'];
		$SNomre=$_POST['SNombre'];
		$PApellido=$_POST['PApellido'];
		$SApellido=$_POST['SApellido'];
		$ban=0;  
		$cond1="";
                $cond2="";
                $condf1="";
                $condf2="";
                $where_with="";
                if (!empty($_POST['IdEstab'])) {
                    if ($_POST['IdEstab']<>$lugar){
                        $cond1 .= " t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " AND";
                        $cond2 .= " t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " AND";
                    }
                    else{
                         $cond1 .= " t02.id_establecimiento = " . $_POST['IdEstab'] . " AND";
                         $cond2 .= " t02.id_establecimiento = " . $_POST['IdEstab'] . " AND";
                    }
                   
                 }

                 if (!empty($_POST['IdServ'])) {
                     $cond1 .= " t13.id  = " . $_POST['IdServ'] . " AND";
                     $cond2 .= " t13.id  = " . $_POST['IdServ'] . " AND";
                     $where_with = "id_area_atencion = $IdServ AND ";
                 }

                 if (!empty($_POST['IdSubServ'])) {
                     $cond1 .= " t10.id = " . $_POST['IdSubServ'] . " AND";
                     $cond2 .= " t10.id = " . $_POST['IdSubServ'] . " AND";
                 }
                 
                 if (!empty($_POST['idexpediente'])) {
                    $cond1 .= " t06.numero = '" . $_POST['idexpediente'] . "' AND";
                    $cond2 .= " t06.numero = '" . $_POST['idexpediente'] . "' AND";
                 }
                 
                 if (!empty($_POST['PNombre'])) {
                    $cond1 .= " t07.primer_nombre ILIKE '" . $_POST['PNombre'] . "%' AND";
                    $cond2 .= " t07.primer_nombre ILIKE '" . $_POST['PNombre'] . "%' AND";
                }

                if (!empty($_POST['SNombre'])) {
                    $cond1 .= " t07.segundo_nombre ILIKE '" . $_POST['SNombre'] . "%' AND";
                    $cond2 .= " t07.segundo_nombre ILIKE '" . $_POST['SNombre'] . "%' AND";
                }

                if (!empty($_POST['PApellido'])) {
                    $cond1 .= " t07.primer_apellido ILIKE '" . $_POST['PApellido'] . "%' AND";
                    $cond2 .= " t07.primer_apellido ILIKE '" . $_POST['PApellido'] . "%' AND";
                }

                if (!empty($_POST['SApellido'])) {
                    $cond1 .= " t07.segundo_apellido ILIKE '" . $_POST['SApellido'] . "%' AND";
                    $cond2 .= " t07.segundo_apellido ILIKE '" . $_POST['SApellido'] . "%' AND";
                }
                 if((empty($_POST['idexpediente'])) AND (empty($_POST['primerapellido'])) AND (empty($_POST['segundoapellido'])) 
                         AND (empty($_POST['primernombre'])) AND (empty($_POST['segundonombre']))AND (empty($_POST['IdEstab'])) 
                         AND (empty($_POST['IdServ'])) AND (empty($_POST['IdSubServ'])) AND (empty($_POST['fecharecep'])))
		{
                    $ban=1;
		}
			
		if ($ban==0)
		{   
                    $condf1 = substr($cond1, 0, strlen($cond1) - 3);
                    $condf2 = substr($cond2, 0, strlen($cond2) - 3);
		}
               // echo "COND1=".$condf1;
	 $query = "WITH tbl_servicio AS (
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
                    FROM  ctl_atencion                  t01 
                    INNER JOIN mnt_aten_area_mod_estab              t02 ON (t01.id = t02.id_atencion)
                    INNER JOIN mnt_area_mod_estab           t03 ON (t03.id = t02.id_area_mod_estab)
                    LEFT  JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab)
                    LEFT  JOIN mnt_servicio_externo             t05 ON (t05.id = t04.id_servicio_externo)
                    WHERE $where_with t02.id_establecimiento = $lugar
                    ORDER BY 2)
            
                SELECT DISTINCT t02.id AS idsolicitudestudio, 
                TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                t03.numeromuestra, 
                t06.numero AS idnumeroexp, 
                t03.id AS idrecepcionmuestra, 
                CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido, t07.segundo_apellido,
                t07.apellido_casada) AS paciente, 
                t20.servicio AS nombresubservicio, 
                t13.nombre AS nombreservicio, 
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
                t02.id_expediente FROM sec_solicitudestudios t02  
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
                WHERE t16.idestado = 'P' OR t16.idestado = 'C' AND t02.id_establecimiento = $lugar AND $condf1
        
                UNION

                SELECT DISTINCT t02.id AS idsolicitudestudio,TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,  
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
                WHERE t16.idestado = 'P' OR t16.idestado = 'C' AND t02.id_establecimiento = $lugar AND $condf2";
				
			//echo $query;
			
			$consulta=$objdatos->ListadoResultadosPorArea($query);     
		// $consulta=$objdatos->ListadoResultadosPorArea($idarea,$idexpediente);
		echo "<center><div class='table-responsive' style='width: 80%;'>
                    <table width='80%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
				<thead><tr class='CobaltFieldCaptionTD'>
					<td>Fecha Recepci&oacute;n</td>
					<td>Muestra</td>
					<td>NEC </td>
					<td>Nombre Paciente</td>
					<td>Servicio</td>
					<td>Procedencia</td>
					<td>Establecimiento</td>
					<td>Estado Solicitud</td>
				</tr></thead><tbody>";    
		$pos=0;
		
		while ($row = pg_fetch_array($consulta))
			{
			$Estado=$row['estado'];
			$Proceso="DetalleResultado";
			echo "<tr>
					<td>".$row['fecharecepcion']."</td>
					<td>".$row['numeromuestra']."</td>";
				if(($Estado=="Completa")||($Estado=="En Proceso")){
			echo  "		<td>
						<a style ='text-decoration:underline;cursor:pointer;' 
                                                onclick='javascript:window.open(\"Resultados/ResultadosEstudios.php?IdNumeroExp=".$row['idnumeroexp'].
                                                "&IdSolicitudEstudio=".$row["idsolicitudestudio"]. //IdSolicitudEstudio
                                                "&FechaRecepcion=".$row['fecharecepcion']. //FechaRecepcion
                                                "&pag=1&Proceso=".$Proceso."&IdArea=".$row['idarea']. //IdArea
                                                "&FechaSolicitud=".$row['fechasolicitud']. //FechaSolicitud
                                                "&IdEstab=".$IdEstab.
                                                "&lugar=".$lugar."&Flag=1\")'>".$row['idnumeroexp']."</a></td>
					<td width='25%'>".htmlentities($row['paciente'])."</td>";
				}else{
						echo "<td>".$row['idnumeroexp']."</td>
						      <td width='25%'>".htmlentities($row['paciente'])."</td>";
					}
			
					echo	"<td>".$row['nombreservicio']."</td>
						<td width='10%'>".htmlentities($row['nombreservicio'])."</td>
			 			<td width='15%'>".htmlentities($row['estabext'])."</td>
						<td>$Estado</td>";
				
			
				"</tr>";
		
			$pos=$pos + 1;
			}
			
			pg_free_result($consulta);
			
		"<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
				</tbody></table></div></center>";
   
	break;
	case 5://LLENANDO COMBO DE Examenes
		$rslts='';
		
		$idarea=$_POST['idarea'];
		//echo $IdSubEsp;
		$dtExam=$objdatos->ExamenesPorArea($idarea,$lugar);	
		$rslts = '<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:250px">';
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
              // echo $Idtipoesta;
            	$dtIdEstab=$objdatos->LlenarCmbEstablecimiento($Idtipoesta);
              	$rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:375px">';
		$rslts .='<option value="0"> Seleccione Establecimiento </option>';
               while ($rows =pg_fetch_array( $dtIdEstab)){
		  $rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       }
				
		$rslts .= '</select>';
		echo $rslts;
   	break;
	case 7:// Llenar combo Subservicio
   	     $rslts='';
             $IdServ=$_POST['IdServicio'];
	   //  echo $IdServ;
	     $dtserv=$objdatos->LlenarCmbServ($IdServ,$lugar);
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:375px">';
			$rslts .='<option value="0"> Seleccione Subespecialidad </option>';
			while ($rows =pg_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}
				
	      $rslts .='</select>';
	      echo $rslts;
        break;	
   
   break;
	
    	
}

?>