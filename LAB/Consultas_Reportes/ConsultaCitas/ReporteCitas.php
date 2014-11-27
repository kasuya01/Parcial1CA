<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//include_once("clsSolicitudesPorArea.php");

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
<title>Reporte Citas Programadas</title>
<script language="JavaScript" type="text/javascript" src="ajax_CitasPorPaciente.js"></script>
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
                        idexpediente = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var2=' )
						fecha = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var3=' )
						primernombre = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var4=' )
						segundonombre = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var5=' )
						primerapellido = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var6=' )
						segundoapellido = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var7=' )
						IdEstab= getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var8=' )
						IdServ= getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var9=' )
						IdSubServ= getVars[i].substr(5);
		}

// CargarDatosFormulario(especialidad,fechainicio,fechafin,medico);
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

include_once("clsCitasPorPaciente.php");
//$especialidad=$_GET['var1'];
// echo $fechacita=$_GET['var2'];
$query_search="";
//$fechafin=$_GET['var3'];
//$medico=$_GET['var4'];
$objdatos = new clsCitasPorPaciente;
$cond2="";
        $query="";
        $query2="";
        $where_with="";
        $cond0="and";
 
         
         if (!empty($_GET['var7'])) {
           if ($_GET['var7']<>$lugar){
               $cond1 .=$cond0. "  t02.id_establecimiento_externo = " . $_GET['var7'] . " ";
               $cond2 .=$cond0. "  t02.id_establecimiento_externo = " . $_GET['var7'] . " ";
           }
          
        }
              if (!empty($_GET['var9'])) {
            $cond1 .= $cond0." t10.id = " . $_GET['var9'] . " ";
            $cond2 .= $cond0." t10.id = " . $_GET['var9'] . " ";
        }   
        
        if (!empty($_GET['var8'])) {
            $cond1 .=$cond0 ."  t13.id  = " . $_GET['var8'] . "     ";
            $cond2 .=$cond0 ."  t13.id  = " . $_GET['var8'] . "     ";
            $where_with = "id_area_atencion = ".$_GET['var8']."  AND ";
        }
            
       

       

        if (!empty($_GET['var1'])) {
          $idexpediente="'".$idexpediente."'";
            
            $cond1 .= "and t06.numero = '".$_GET['var1'] ."'    ";
            $cond2 .= "and t06.numero = '".$_GET['var1'] ."'   ";
        }

       

        

        if (!empty($_GET['var2'])) {
             $cond1 .= " and t03.fecharecepcion = '".$_GET['var2']."'       ";
             $cond2 .= " and t03.fecharecepcion = '".$_GET['var2']."'       ";
        }

        if (!empty($_GET['var3'])) {
          
            $cond1 .= " and t07.primer_nombre  ILIKE  '".$_GET['var3']."%'      ";
            $cond2 .= " and  t07.primer_nombre ILIKE  '".$_GET['var3']."%'      ";
        }

        if (!empty($_GET['var4'])) {
             $cond1 .= " and t07.segundo_nombre  ILIKE '". $_GET['var4'] ."%'       ";
             $cond2 .= " and t07.segundo_nombre  ILIKE '". $_GET['var4'] ."%'       ";
        }

        if (!empty($_GET['var5'])) {
            $cond1 .= " and  t07.primer_apellido ILIKE '".$_GET['var5']."%'         ";
            $cond2 .="  and  t07.primer_apellido ILIKE '".$_GET['var5']."%'         ";
        }

        if (!empty($_GET['var6'])) {
            $cond1 .=" and t07.segundo_apellido ILIKE '".$_GET['var6']."%'       ";
            $cond2 .=" and t07.segundo_apellido ILIKE '".$_GET['var6']."%'       ";
        }

        

        if ((empty($_POST['idexpediente'])) AND ( empty($_POST['idarea'])) AND ( empty($_POST['fecha']))
                AND ( empty($_POST['IdEstab'])) AND ( empty($_POST['IdServ'])) AND ( empty($_POST['IdSubServ']))
                AND ( empty($_POST['primernombre'])) AND ( empty($_POST['segundonombre'])) AND ( empty($_POST['primerapellido']))
                AND ( empty($_POST['segundoapellido'])) AND ( empty($_POST['idexamen'])) AND ( empty($_POST['TipoSolic']))) {
            $ban = 1;
        }
        
        if ($ban == 0) {

            $cond1 = substr($cond1, 0, strlen($query) - 3);
            $cond2 = substr($cond2, 0, strlen($query) - 3);
            
          //  echo $query1;
           // $query_search = 
            //echo $cond1;
            //echo $cond2;
        }     
       // echo $cond2;
         $query="WITH tbl_servicio AS (
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
                    FROM  ctl_atencion                              t01 
                    INNER JOIN mnt_aten_area_mod_estab              t02 ON (t01.id = t02.id_atencion)
                    INNER JOIN mnt_area_mod_estab                   t03 ON (t03.id = t02.id_area_mod_estab)
                    LEFT  JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab)
                    LEFT  JOIN mnt_servicio_externo                 t05 ON (t05.id = t04.id_servicio_externo)
                    WHERE $where_with t02.id_establecimiento = $lugar
                    ORDER BY 2)
            
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
            FROM sec_detallesolicitudestudios           t01 
            INNER JOIN sec_solicitudestudios            t02     ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra             t03     ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab            t04     ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento  t05     ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_expediente                   t06     ON (t06.id = t02.id_expediente) 
            INNER JOIN mnt_paciente                     t07     ON (t07.id = t06.id_paciente) 
            INNER JOIN ctl_area_servicio_diagnostico    t08     ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN sec_historial_clinico            t09     ON (t09.id = t02.id_historial_clinico) 
            INNER JOIN mnt_aten_area_mod_estab          t10     ON (t10.id = t09.idsubservicio) 
            INNER JOIN ctl_atencion                     t11     ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab               t12     ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion                t13     ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento              t14     ON (t14.id = t09.idestablecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo        t15     ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico  t16     ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud                t17     ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico  t18     ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo                         t19     ON (t19.id = t07.id_sexo)
            INNER JOIN tbl_servicio                     t20     ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
            WHERE (t16.idestado = 'D') 
            AND t02.id_establecimiento = $lugar
             $cond1
        
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
                FROM sec_detallesolicitudestudios       t01 
            INNER JOIN sec_solicitudestudios            t02     ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra             t03     ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab            t04     ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento  t05     ON (t05.id = t04.idexamen)
            INNER JOIN mnt_dato_referencia              t09     ON t09.id=t02.id_dato_referencia 
            INNER JOIN mnt_expediente_referido          t06     ON (t06.id = t09.id_expediente_referido) 
            INNER JOIN mnt_paciente_referido            t07     ON (t07.id = t06.id_referido) 
            INNER JOIN ctl_area_servicio_diagnostico    t08     ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN mnt_aten_area_mod_estab          t10     ON (t10.id = t09.id_aten_area_mod_estab) 
            INNER JOIN ctl_atencion                     t11     ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab               t12     ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion                t13     ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento              t14     ON (t14.id = t09.id_establecimiento)
            INNER JOIN cit_citas_serviciodeapoyo        t15     ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico  t16     ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud                t17     ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico  t18     ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo                         t19     ON (t19.id = t07.id_sexo)
            WHERE (t16.idestado = 'D')
            AND t02.id_establecimiento = $lugar 
            $cond2"; 
	
		//ECHO $query_search;
		
             $consulta1=$objdatos->BuscarCitasPaciente($query); 
			 
			$row1 = pg_fetch_array($consulta1);?>
 <table width="100%" border="0" align='center'>
			<tr>
				<td colspan="7" align="center"><h3><strong>REPORTE DE CITAS PROGRAMADAS
				</h3></strong></td>
			</tr>
			<tr>
			<td colspan="7" align="center"><h3><strong><?php echo $row1['nombreservicio']; ?></strong></h3></td>
			</td>
			</tr>
			
			</table>
  <?php 
  $consulta=$objdatos->BuscarCitasPaciente($query); ?>
 <table width="80%" border="1" align="center">
        <tr>
		<td width="8%"  class="StormyWeatherDataTD" style="color:#000000; font:bold" ><h4><strong>Fecha cita</h4></strong></td>
		<td width="8%" class="StormyWeatherDataTD" style="color:#000000; font:bold" ><h4><strong>NEC </strong><h4></td>
		<td width="25%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Nombre Paciente</strong><h4></td>
		<td width="15%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Origen</strong><h4></td>
		<td width="15%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Procedencia</strong><h4></td>
		<td width="30%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Establecimiento</strong><h4></td>
	</tr>    
   <?php $pos=0;
    while ($row = pg_fetch_array($consulta))
	{ ?>
	<tr>
		<td width="8%"><?php echo $row['fecharecepcion']; ?></td>
		<td width="7%"><?php echo $row['idnumeroexp'];?></td>
		<td width="25%"><?php echo $row['paciente'];?></td>
		<td width="15%"><?php echo $row['nombresubservicio'];?></td>
		<td width="15%"><?php echo $row['nombreservicio'];?></td>
		<td width="30%"><?php echo $row['estabext'];?></td>	
	</tr>
 <?php
	$pos=$pos + 1;
	}
	
	pg_free_result($consulta);?>
	
   <input type="hidden" name="oculto" id="text" value='".$pos."' /> 
</table>
    
<div id="boton">
	<table width="90%" border="0" align="center">
		<tr>
			<td colspan="7" align="center">	
					
					<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
					<input type="button" name="btncl" id="btnct" value="Regresar" onClick="window.close();"> </div>
					</td>
				
		</tr>
	</table>
</div>
</body>
</html>
