<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$nivel=$_SESSION['NIVEL'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<link rel="stylesheet" type="text/css" href="../../Webstyle/Themes/Cobalt/Style.css">
<style type="text/css">
<!--
@media print{
#boton{display:none;}
}


.Estilo5 {font-size: 10pt}
.Estilo12 {font-size: 6pt}
-->
</style>
<title>Reporte de Solicitudes por Sub-Servicio</title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudPorServicioPeriodo.js"></script>
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
<script language="JavaScript" >
function RecogeValor()
{
var vtmp=location.search;
var vtmp2 = vtmp.substring(1,vtmp.length);
var query = unescape(top.location.search.substring(1));
var getVars = query.split(/&/);
for ( i = 0; i < getVars.length; i++)
	{
		if ( getVars[i].substr(0,5) == 'var1=' )//loops through this array and extract each name and value
               		subservicio = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var2=' )
			fechainicio = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var3=' )
			fechafin = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var4=' )
			medico = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var5=' )
			IdEstab = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var6=' )
			IdServ = getVars[i].substr(5);
	}

}

function otroreport(){
	window.location.replace('SolicitudPorServicioPeriodo.php');
}

function imprimir(){
document.getElementById('btnImprimir').style.visibility="hidden";
window.print();
document.getElementById('btnImprimir').style.visibility="visible";
}
</script>
</head>

<body onLoad="RecogeValor();">

<?php 
include_once("clsSolicitudesPorServicioPeriodo.php");
     $especialidad   =$_GET['var1'];
    $fechainicio    =$_GET['var2'];
      $fechafin       =$_GET['var3'];                    
     $IdServ         =$_GET['var6'];               
     $IdEstab        =$_GET['var5'];
    $medico         =$_GET['var4'];
    $objdatos       = new clsSolicitudesPorServicioPeriodo;
    $cond1="";
    $cond2="";
    $ban="";
    
 if (!empty($_GET['var5'])) {
           if ($_GET['var5']<>$lugar){
               $cond1 .= " and   t02.id_establecimiento_externo = " . $_GET['var5'] . " ";
               $cond2 .= " and  t02.id_establecimiento_externo = " . $_GET['var5'] . " ";
           }
          
        }
        
        if (!empty($_GET['var1'])) {
            $cond1 .= " and  t10.id = " . $_GET['var1'] . "    ";
            $cond2 .= " and t10.id = " . $_GET['var1'] . "   ";
        }
            //var6
        if (!empty($_GET['var6'])) {
            $cond1 .=" and  t13.id  = " . $_GET['var6'] . "     ";
            $cond2 .=" and  t13.id  = " . $_GET['var6'] . "     ";
            $where_with = "id_area_atencion = $IdServ AND ";
        }
        
        if (!empty($_GET['var4']))
		{ $cond1 .= "   and t24.id='".$_GET['var4']."' ";
                  $cond2 .= "   and t24.id='".$_GET['var4']."' ";
                }
	
	if ((!empty($_GET['var2'])) and (!empty($_GET['var3'])))
	{ //$Nfechaini=explode("/",$fechainicio);
	  //$Nfechafin=explode("/",$fechafin);
		 	//print_r($Nfecha);
        //$Nfechaini=$Nfechaini[2]."-".$Nfechaini[1]."-".$Nfechaini[0]; 
	//$Nfechafin=$Nfechafin[2]."-".$Nfechafin[1]."-".$Nfechafin[0]; 
		$cond1 .= " and     t02.fecha_solicitud BETWEEN '".$_GET['var2']."'     AND     '".$_GET['var3']."'     ";
                $cond2 .= " and     t02.fecha_solicitud BETWEEN '".$_GET['var2']."'     AND     '".$_GET['var3']."'     ";}
           
         
                
      if((empty($_GET['var1'])) and (empty($_POST['var4'])) and (empty($_GET['var2'])) and (empty($_GET['var3'])) and (empty($_GET['var5'])))
		{
				$ban=1;
		}
           /*if ($ban == 0) {

            $cond1 = substr($cond1, 0, strlen($query) - 3);
            $cond2 = substr($cond2, 0, strlen($query) - 3);
            
           //echo $query1;
           // $query_search = 
            $cond1;
           //echo $cond2;
        }     */
       $query="SELECT 
                    t01.id,
		   t13.nombre AS nombreservicio, 
		   t19.nombre AS sexo,
                   t24.nombreempleado as medico,
                   CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
                   t07.apellido_casada) AS paciente,
                   (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                   t11.nombre AS nombresubservicio,
                  CASE t01.estadodetalle 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'    
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado,
		  t06.numero as expediente,
                  TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud
	    FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02 		ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03 		ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04 		ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05 	ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_expediente t06 			ON (t06.id = t02.id_expediente) 
            INNER JOIN mnt_paciente t07 			ON (t07.id = t06.id_paciente) 
            INNER JOIN ctl_area_servicio_diagnostico t08 	ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN sec_historial_clinico t09 		ON (t09.id = t02.id_historial_clinico) 
            INNER JOIN mnt_aten_area_mod_estab t10 		ON (t10.id = t09.idsubservicio) 
            INNER JOIN ctl_atencion t11 			ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12 			ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13 			ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14 			ON (t14.id = t09.idestablecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo t15 		ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16 	ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud t17 			ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico t18 	ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19 				ON (t19.id = t07.id_sexo)
           -- INNER JOIN tbl_servicio t20 			ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
            left join sec_diagnostico_paciente 		t21 on (t21.id_historial_clinico=t09.id) 
            left join mnt_snomed_cie10 			t22 on (t22.id=t21.id_snomed) 
            left join sec_signos_vitales 			t23 on (t23.id_historial_clinico=t09.id) 
            left join mnt_empleado 			t24 on (t09.id_empleado=t24.id)
            inner join ctl_area_servicio_diagnostico 	t25 on (t25.id=t05.id_area_servicio_diagnostico) 
            WHERE t01.idestablecimiento=$lugar  $cond1 

UNION

            SELECT t01.id,
                   t13.nombre AS nombreservicio, 
		   t19.nombre AS sexo,
                   t24.nombreempleado as medico,
                   CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
                   t07.apellido_casada) AS paciente, 
                  (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
		   t11.nombre AS nombresubservicio, 
		CASE t01.estadodetalle 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'    
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado,
			t06.numero as expediente,
			TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud
FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02 		ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03 		ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04	 	ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05  	ON (t05.id = t04.idexamen)
            INNER JOIN mnt_dato_referencia t09 			ON t09.id=t02.id_dato_referencia 
            INNER JOIN mnt_expediente_referido t06 		ON (t06.id = t09.id_expediente_referido) 
            INNER JOIN mnt_paciente_referido t07 		ON (t07.id = t06.id_referido) 
            INNER JOIN ctl_area_servicio_diagnostico t08 	ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN mnt_aten_area_mod_estab t10 		ON (t10.id = t09.id_aten_area_mod_estab) 
            INNER JOIN ctl_atencion t11 			ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12 			ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13 			ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14 			ON (t14.id = t09.id_establecimiento)
	    INNER JOIN ctl_examen_servicio_diagnostico t18 	ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19 				ON (t19.id = t07.id_sexo)
            left join sec_diagnostico_paciente 		t21 on (t21.id_historial_clinico=t09.id) 
            left join mnt_snomed_cie10 			t22 on (t22.id=t21.id_snomed) 
            left join sec_signos_vitales 			t23 on (t23.id_historial_clinico=t09.id) 
            left join mnt_empleado 				t24 on (t09.id_empleado=t24.id)
            inner join ctl_area_servicio_diagnostico 	t25 on (t25.id=t05.id_area_servicio_diagnostico) 
            where t01.idestablecimiento= $lugar  $cond2 order by fechasolicitud desc ";
		
	//ECHO $query_search;
		
        $consulta1=$objdatos->BuscarSolicitudesEspecialidad($query); 
			 
	$row1 = @pg_fetch_array($consulta1);?>
 	<table width="60%" border="0"  align='center'>
		<tr>
			<td colspan="7" align="center"><h3><strong>REPORTE DE SOLICITUDES POR SUB-SERVICIO
			</h3></strong></td>
		</tr>
		<tr>
			<td colspan="7" align="center"><h3><strong><?php echo $row1['nombreservicio']; ?></strong></h3></td>
			</td>
		</tr>
		
	</table>
 	 <?php 
         $consulta=$objdatos->BuscarSolicitudesEspecialidad($query); ?>
 	<table width="80%" border="1" align="center" cellspacing="0">
		<tr>
			<td width="10%" class="StormyWeatherDataTD" style="color:#000000; font:bold" ><h4><strong>Fecha Solicitud</h4></strong></td>
			<td width="6%" class="StormyWeatherDataTD" style="color:#000000; font:bold" ><h4><strong>NEC </strong><h4></td>
			<td width="20%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Nombre Paciente</strong><h4></td>
			<td width="18%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>M&eacute;dico</strong><h4></td>
			<td width="12%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Origen</strong><h4></td>
			<td width="12%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Procedencia</strong><h4></td>
			<td width="15%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Establecimiento</strong><h4></td>
			<td width="15%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Estado Solicitud</strong><h4></td>
		</tr>    
	<?php $pos=0;
    	while ($row = @pg_fetch_array($consulta))
	{ ?>
		<tr>
			<td width="10%"><?php echo $row['fechasolicitud']; ?></td>
			<td width="6%"><?php echo $row['expediente'];?></td>
			<td width="20%"><?php echo $row['paciente'];?></td>
			<td width="18%"><?php echo $row['medico'];?></td>
			<td width="12%"><?php echo $row['nombresubservicio'];?></td>
			<td width="12%"><?php echo $row['nombreservicio'];?></td>
			<td width="15%"><?php echo $row['estabext'];?></td>
			<td width="15%"><?php echo $row['estado'];?></td>
		</tr>
	<?php
		$pos=$pos + 1;
	}
		@pg_free_result($consulta);
	?>
	
  		<input type="hidden" name="oculto" id="text" value='".$pos."' /> 
   
	</table>
    <br>
    	<table width="90%" border="0" align="center">
	<tr>
		<td colspan="7" align="center">	
			<div id="boton">	
				<!--<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
				<input type="button" name="btncl" id="btnct" value="Regresar" onClick="window.close();"> </div>
				-->
                                <button type='button' align="center" class='btn btn-primary'  onclick='window.print(); '><span class='glyphicon glyphicon-print'></span> Imprimir </button>
                                <button type='button' align="center" class='btn btn-primary'  onClick="window.close();"><span class='glyphicon glyphicon-arrow-left'></span> Regresar </button>
                    
                               
                    
                    </div>
		</td>
	</tr>
	</table>
    <br>
</body>
</html>
