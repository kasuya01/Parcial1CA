<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsConsultaMuestrasRechazadas.php");

//variables POST
$opcion=$_POST['opcion'];

//$observacion=$_POST['observacion'];
$estadosolicitud="P";
//echo $fecharecep;

//creando los objetos de las clases
$objdatos = new clsConsultaMuestrasRechazadas;

switch ($opcion) 
{
  
    case 1:
        $ban = 0;
        $IdEstab        = $_POST['IdEstab'];
        $IdServ         = $_POST['IdServ'];
        $IdSubServ      = $_POST['IdSubServ'];
        $idarea         = $_POST['idarea'];
        $idexamen       = $_POST['idexamen'];
        $idexpediente   = $_POST['idexpediente'];
       // $fechasolicitud = $_POST['fechasolicitud'];
        $fecharecepcion = $_POST['fecharecep'];
        $PNombre        = $_POST['PNombre'];
        $SNomre         = $_POST['SNombre'];
        $PApellido      = $_POST['PApellido'];
        $SApellido      = $_POST['SApellido'];
       // $TipoSolic      = $_POST['TipoSolic'];
        $cond1="";
        $cond2="";
        $query="";
        $query2="";
        $where_with="";
      //  echo $IdEstab." - ".$lugar;
        if (!empty($_POST['IdEstab'])) {
           if ($_POST['IdEstab']<>$lugar){
               $cond1 .= " t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " AND";
               $cond2 .= " t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " AND";
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

        if (!empty($_POST['idarea'])) {
            $cond1 .= " t08.id = " . $_POST['idarea'] . " AND";
            $cond2 .= " t08.id = " . $_POST['idarea'] . " AND";
        }

        if (!empty($_POST['idexpediente'])) {
            $cond1 .= " t06.numero = '" . $_POST['idexpediente'] . "' AND";
            $cond2 .= " t06.numero = '" . $_POST['idexpediente'] . "' AND";
        }

        if (!empty($_POST['idexamen'])) {
             $cond1 .= " t04.id = " . $_POST['idexamen'] . " AND";
             $cond2 .= " t04.id = " . $_POST['idexamen'] . " AND";
        }

        if (!empty($_POST['fechasolicitud'])) {
             $cond1 .= " t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' AND";
             $cond2 .= " t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' AND";
        }

        if (!empty($_POST['fecharecep'])) {
             $cond1 .= " t03.fecharecepcion = '" . $_POST['fecharecep'] . "' AND";
             $cond2 .= " t03.fecharecepcion = '" . $_POST['fecharecep'] . "' AND";
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

        if (!empty($_POST['TipoSolic'])) {
            $cond1 .= " t17.idtiposolicitud = '" . $_POST['TipoSolic'] . "' AND";
            $cond2 .= " t17.idtiposolicitud = '" . $_POST['TipoSolic'] . "' AND";
        }

        if ((empty($_POST['idexpediente'])) AND ( empty($_POST['idarea'])) AND ( empty($_POST['fechasolicitud']))
                AND ( empty($_POST['IdEstab'])) AND ( empty($_POST['IdServ'])) AND ( empty($_POST['IdSubServ']))
                AND ( empty($_POST['PNombre'])) AND ( empty($_POST['SNombre'])) AND ( empty($_POST['PApellido']))
                AND ( empty($_POST['SApellido'])) AND ( empty($_POST['idexamen'])) AND ( empty($_POST['TipoSolic']))) {
            $ban = 1;
        }
        
        if ($ban == 0) {

            $cond1 = substr($cond1, 0, strlen($query) - 3);
            $cond2 = substr($cond2, 0, strlen($query) - 3);
            
          //  echo $query1;
           // $query_search = 
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
               (CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||' - '  || t06.nombre
                    ELSE   t07.nombre ||' - ' || t06.nombre
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
                SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                       t01.id ,
                       t02.id AS idsolicitudestudio,
                       t04.idplantilla, 
                       t01.id AS iddetallesolicitud,
                       t03.numeromuestra, 
                       t06.numero AS idnumeroexp, 
                       t03.id AS idrecepcionmuestra, 
                       t04.codigo_examen AS idexamen, 
                       t04.nombre_examen AS nombreexamen, 
                       t01.indicacion, t08.nombrearea, 
                       CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,
                       t07.segundo_apellido,t07.apellido_casada) AS paciente,
                       t20.servicio AS nombresubservicio,
                       t20.procedencia AS nombreservicio, 
                       t02.impresiones, 
                       t14.nombre, 
                       t09.id AS idhistorialclinico,
                       TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, 
                       t17.tiposolicitud AS prioridad, 
                       t07.fecha_nacimiento AS fechanacimiento, 
                       t19.nombre AS sexo, 
                       t18.idestandar,
                       t02.id_establecimiento_externo,
                       (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                        t01.observacion
            FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02                ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03                 ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04                ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05      ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_expediente t06                       ON (t06.id = t02.id_expediente) 
            INNER JOIN mnt_paciente t07                         ON (t07.id = t06.id_paciente) 
            INNER JOIN ctl_area_servicio_diagnostico t08        ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN sec_historial_clinico t09                ON (t09.id = t02.id_historial_clinico) 
            INNER JOIN mnt_aten_area_mod_estab t10              ON (t10.id = t09.idsubservicio) 
            INNER JOIN ctl_atencion t11                         ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12                   ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13                    ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14                  ON (t14.id = t09.idestablecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo t15            ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16      ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud t17                    ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico t18      ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19                             ON (t19.id = t07.id_sexo)
            INNER JOIN tbl_servicio t20                         ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
            WHERE (t16.idestado = 'RM')  
            AND t02.id_establecimiento = $lugar
            AND $cond1
        
            UNION

            SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                   t01.id ,
                   t02.id AS idsolicitudestudio,
                   t04.idplantilla, 
                   t01.id AS iddetallesolicitud,
                   t03.numeromuestra,
                   t06.numero AS idnumeroexp,
                   t03.id AS idrecepcionmuestra,
                   t04.codigo_examen AS idexamen,
                   t04.nombre_examen AS nombreexamen,
                   t01.indicacion, t08.nombrearea,
                   CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
                   t07.apellido_casada) AS paciente, 
                   t11.nombre AS nombresubservicio, 
                   t13.nombre AS nombreservicio, 
                   t02.impresiones, 
                   t14.nombre,
                   t09.id AS idhistorialclinico, 
                   TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, 
                   t17.tiposolicitud AS prioridad, 
                   t07.fecha_nacimiento AS fechanacimiento, 
                   t19.nombre AS sexo, 
                   t18.idestandar,
                   t02.id_establecimiento_externo,
                   (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                    t01.observacion
            FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02                    ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03                     ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04                    ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05          ON (t05.id = t04.idexamen)
            INNER JOIN mnt_dato_referencia t09                      ON t09.id=t02.id_dato_referencia 
            INNER JOIN mnt_expediente_referido t06                  ON (t06.id = t09.id_expediente_referido) 
            INNER JOIN mnt_paciente_referido t07                    ON (t07.id = t06.id_referido) 
            INNER JOIN ctl_area_servicio_diagnostico t08            ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN mnt_aten_area_mod_estab t10                  ON (t10.id = t09.id_aten_area_mod_estab) 
            INNER JOIN ctl_atencion t11                             ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12                       ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13                        ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14                      ON (t14.id = t09.id_establecimiento)
            INNER JOIN cit_citas_serviciodeapoyo t15                ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16          ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico t18          ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19                                 ON (t19.id = t07.id_sexo)
            WHERE (t16.idestado = 'RM') 
            AND t02.id_establecimiento = $lugar 
            AND $cond2) ordenar
            ORDER BY to_date(ordenar.fecharecepcion, 'DD/MM/YYYY') DESC";  
                  
  //  $query . " ORDER BY t03.fecharecepcion DESC";
    // echo $query;
        $consulta=$objdatos->ListadoSolicitudesPorArea($query);  
	$NroRegistros= $objdatos->NumeroDeRegistros($query);
 
        
        
        
        
              if ($NroRegistros==""){
                            $NroRegistros=0;
                            $imprimir= "<table width='90%' border='0'  align='center'>
          <center>
                <tr>
                        <td width='500'  align='center'  ><span style='color: #0101DF;'> <h4> TOTAL DE MUESTRAS RECHAZADAS:".$NroRegistros."</h4></span></td>
                </tr>
                </table>
         <table width='90%' border='0'  align='center'>
                   <td width='3000'></td>   <td ' > <button type='button'  class='btn btn-primary'  onclick='VistaPrevia(); '><span class='glyphicon glyphicon-print'></span> IMPRIMIR REPORTE </button> </td>
			<!--<td <td width='500'>  </td>  <td colspan='7'      style='color:#990000; font:bold'><a style ='text-decoration:underline;cursor:pointer; font:bold; size:36' onclick='VistaPrevia();'>IMPRIMIR REPORTE</a></td>	-->
		</tr>
          </center>
	</table> ";
                        }else{
              

  $imprimir= "<table width='90%' border='0'  align='center'>
          <center>
                <tr>
                        <td width='500'  align='center'  ><span style='color: #0101DF;'> <h4> TOTAL DE MUESTRAS RECHAZADAS:".$NroRegistros."</h4></span></td>
                </tr>
                </table>



        <table width='90%' border='0'  align='center'>
                   <td width='3000'></td>   <td ' > <button type='button'  class='btn btn-primary'  onclick='VistaPrevia(); '><span class='glyphicon glyphicon-print'></span> IMPRIMIR REPORTE </button> </td>
			<!--<td <td width='500'>  </td>  <td colspan='7'      style='color:#990000; font:bold'><a style ='text-decoration:underline;cursor:pointer; font:bold; size:36' onclick='VistaPrevia();'>IMPRIMIR REPORTE</a></td>	-->
		</tr>
          </center>
	</table> ";
                        }
        
        
        
        echo $imprimir;
        
        

        $consulta = $objdatos->ListadoSolicitudesPorArea($query);

        echo "<div class='table-responsive' style='width: 90%;'>
               <table width='80%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
              <thead>  <tr >
			<th>Muestra </th>
		        <th>NEC </th>
			<th>Paciente</th>
			<th>Id Examen</th>
			<th>Examen</th>
			<th>Observaci&oacute;n</th>
			<th>Servicio</th>
			<th>Procedencia</th>
			<th>Establecimiento</th>
			<th>Fecha Recepci&oacute;n</th>
			<th>Prioridad</th>
                    </tr></thead><tbody>"; 
        if(pg_num_rows($consulta)){
            $pos = 0;

            while ($row = pg_fetch_array($consulta)) {
             
                echo "<tr>
				   <td width='3%'>".$row['numeromuestra']."</td>
				   <td width='4%'>".$row['idnumeroexp']."</td>".
                                    "<input name='idsolicitudP[".$pos."]' id='idsolicitud1[".$pos."]' type='hidden' size='60' value='".$row[1]."' />".
                                    "<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row[1]."' />".
			            "<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row['idnumeroexp']."' />".
			            "<input name='idarea[".$pos."]' id='idarea[".$pos."]' type='hidden' size='60' value='".$idarea."' />".
			            "<input name='idexamen[".$pos."]' id='idexamen[".$pos."]' type='hidden' size='60' value='".$row['id']."' />".
			            "<input name='idestablecimiento[".$pos."]' id='idestablecimiento[".$pos."]' type='hidden' size='60' value='".$IdEstab."' />".
				  "<td width='17%'>".$row['paciente']."</td>
				   <td width='4%'>".$row['idestandar']."</td>
				   <td width='16%'>".htmlentities($row['nombreexamen'])."</td>
				   <td width='8%'>".htmlentities($row['observacion'])."</td>
				   <td width='8%'>".htmlentities($row['nombresubservicio'])."</td>
				   <td width='12%'>".htmlentities($row['nombreservicio'])."</td>
                                   <td width='23%'>".htmlentities($row['estabext'])."</td>
				   <td width='8%'>".$row['fecharecepcion']."</td>
				   <td width='8%'>".($row['prioridad'])."</td>
                                      
				 </tr>";
                
              
                $pos = $pos + 1;
            }
            pg_free_result($consulta);
            echo "<input type='hidden' name='oculto' id='text' value='" . $pos . "' />
                </table>";
            echo "<br><br>";
        } else {
            echo "<tr><td colspan='11'><span style='color: #575757;'>No se han encontrado resultados...</span></td></tr></table>";
        }


        break;
    
	case 5://LLENANDO COMBO DE Examenes
		$rslts='';
		
		$idarea=$_POST['idarea'];
		//echo $IdSubEsp;
		$dtExam=$objdatos->ExamenesPorArea($idarea,$lugar);	
		$rslts = '<select name="cmbExamen" id="cmbExamen" class="form-control height" style="width:500px">';
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
	   //  echo $IdServ;
	     $dtserv=$objdatos->LlenarCmbServ($IdServ,$lugar);
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" class="form-control height" style="width:500px">';
			$rslts .='<option value="0"> Seleccione Subespecialidad </option>';
			while ($rows =pg_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}
				
	      $rslts .='</select>';
	      echo $rslts;
        break;	
   
  
}

?>