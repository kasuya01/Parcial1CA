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
<title>Reporte de Muestras Rechazadas</title>
<script language="JavaScript" type="text/javascript" src="ajax_ConsultaMuestrasPendientes.js"></script>
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
                        idarea = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var2=' )
						idexpediente = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var3=' )
						fecharecep = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var4=' )
				IdEstab = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var5=' )
				IdServ = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var6=' )
				IdSubServ = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var7=' )
				idexamen = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var8=' )
				PNombre = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var9=' )
				SNombre = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var10=' )
				PApellido = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var11=' )
				SApellido = getVars[i].substr(5);
		}

// CargarDatosFormulario(especialidad,fechainicio,fechafin,medico);
}

function otroreport(){
	window.location.replace('Proc_ConsultaMuestrasRechazadas.php');
}



imprimir(){
document.getElementById('btnImprimir').style.visibility="hidden";
window.print();
document.getElementById('btnImprimir').style.visibility="visible";
}
</script>
</head>

<body onLoad="RecogeValor();">

<?php 
include_once("clsConsultaMuestrasRechazadas.php");

$objdatos = new clsConsultaMuestrasRechazadas;

$query="SELECT sec_solicitudestudios.IdSolicitudEstudio,lab_recepcionmuestra.NumeroMuestra,sec_solicitudestudios.IdNumeroExp,lab_examenes.IdExamen,
		lab_examenes.NombreExamen,Indicacion,DATE_FORMAT(lab_recepcionmuestra.FechaRecepcion,'%e/ %m / %Y') AS 	
		FechaRecepcion,sec_detallesolicitudestudios.observacion,mnt_subservicio.NombreSubServicio,mnt_servicio.NombreServicio,
		mnt_establecimiento.Nombre,CONCAT_WS(' ',PrimerApellido,NULL,SegundoApellido,',',PrimerNombre,NULL,SegundoNombre) AS NombrePaciente 
	FROM sec_detallesolicitudestudios  
	INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio 
	INNER JOIN lab_recepcionmuestra  ON sec_detallesolicitudestudios.IdSolicitudEstudio=lab_recepcionmuestra.IdSolicitudEstudio 
	INNER JOIN lab_examenes  ON sec_detallesolicitudestudios.IdExamen= lab_examenes.IdExamen
	INNER JOIN lab_areas 	 ON  lab_examenes.IdArea=lab_areas.IdArea
	INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
	INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
	INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio
	INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
	INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp=mnt_expediente.IdNumeroExp
	INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
	WHERE estadodetalle='RM' AND lab_recepcionmuestra.FechaRecepcion<=CURRENT_DATE AND";
		$ban=0;
			//VERIFICANDO LOS POST ENVIADOS
	// $estadodetalle='D';  //estado en que la muestra ha sido tomada
	if (!empty($_GET['var4']))
		{ $query .= " sec_historial_clinico.IdEstablecimiento ='".$_GET['var4']."' AND";}	
			
		if (!empty($_GET['var5']))
		{ $query .= " mnt_subservicio.IdServicio ='".$_GET['var5']."' AND";}
		
		if (!empty($_GET['var6']))
		{ $query .= " mnt_subservicio.IdSubServicio ='".$_GET['var6']."' AND";}
	
		if (!empty($_GET['var1']))
		{ $query .= " lab_areas.IdArea='".$_GET['var1']."' AND";}
 
		if (!empty($_GET['var7']))
		{ $query .= " lab_examenes.IdExamen='".$_GET['var7']."' AND";}		
		
		if (!empty($_GET['var2']))
		{ $query .= " sec_solicitudestudios.IdNumeroExp='".$_GET['var2']."' AND";}
		
		if (!empty($_GET['var3']))
		{$Nfecha=explode("/",$_GET['var3']);
		//print_r($Nfecha);
                $Nfecharecep=$Nfecha[2]."-".$Nfecha[1]."-".$Nfecha[0]; 
		$query .= " lab_recepcionmuestra.fecharecepcion='".$Nfecharecep."' AND";}
		
		/*if (!empty($_GET['var3']))
		{ $query .= " lab_recepcionmuestra.FechaRecepcion='".$_GET['var3']."' AND";}*/
		
		if((empty($_GET['var2'])) AND (empty($_GET['var1'])) AND (empty($_GET['var3'])) AND (empty($_GET['var4'])) AND (empty($_GET['var5'])) AND (empty($_GET['var6'])) AND (empty($_GET['var8'])) AND (empty($_GET['var9'])) AND (empty($_GET['var10'])) AND (empty($_GET['var11'])) AND (empty($_GET['var7'])))
		{
			$ban=1;
		}
		
	
		
		if ($ban==0){
			
				$query = substr($query ,0,strlen($query)-3);
				$query_search = $query. " order by NumeroMuestra";
			
		}
	//echo $query_search;
	
	?>
 <table width="100%" border="0" align='center'>
			<tr>
				<td colspan="7" align="center"><h3><strong>REPORTE DE MUESTRAS RECHAZADAS
				</h3></strong></td>
			</tr>
			</table>
  <?php 
  $consulta=$objdatos->ListadoSolicitudesPorArea($query_search); ?>
 <table width="100%" border="1" cellspacing="0" align="center">
			
    <tr >
		<td width="5%"  class="StormyWeatherDataTD" style="color:#000000; font:bold" align="center"><h4><strong>Muestra</h4></strong></td>
		<td width="6%" class="StormyWeatherDataTD" style="color:#000000; font:bold" ><h4><strong>NEC </strong><h4></td>
		<td width="20%" class="StormyWeatherDataTD" style="color:#000000; font:bold" ><h4><strong>
Nombre Paciente</strong><h4>
                </td>
		<td width="6%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Id Examen</strong><h4></td>
		<td width="10%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Nombre Examen</strong><h4></td>
		<td width="10%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Observaci&oacute;n</strong><h4></td>
		<td width="10%" class="StormyWeatherDataTD" style="color:#000000; font:bold" align="center"><h4><strong>Servicio</strong><h4></td>
		<td width="10%" class="StormyWeatherDataTD" style="color:#000000; font:bold" align="center"><h4><strong>Procedencia</strong><h4></td>
		<td width="12%" class="StormyWeatherDataTD" style="color:#000000; font:bold" align="center"><h4><strong>Establecimiento</strong><h4></td>
		<td width="7%" class="StormyWeatherDataTD" style="color:#000000; font:bold" ><h4><strong>Fecha Recepci&oacute;n</strong><h4></td>
	</tr>    
   <?php $pos=0;
    while ($row = mysql_fetch_array($consulta))
	{ ?>
			<tr >
				<td width="5%" align="center"><?php echo $row['NumeroMuestra']; ?></td>
				<td width="6%"><?php echo $row['IdNumeroExp'];?></td>
				<td width="20%" ><?php echo $row['NombrePaciente'];?></td>
				<td width="6%"><?php echo $row['IdExamen'];?></td>
				<td width="10%"><?php echo htmlentities($row['NombreExamen']);?></td>
				 <?if(!empty($row['observacion']))
					echo "<td width='10%'>".htmlentities($row['observacion'])."</td>";
				
			 	   else	
					echo "<td width='10%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";?>
				
				<td width='10%' align="center"><?php echo htmlentities($row['NombreSubServicio']); ?></td>
				<td width='10%' align="center"><?php echo htmlentities($row['NombreServicio']); ?></td>
				<td width='12%' align="center"><?php echo htmlentities($row['Nombre']); ?></td>
				<td width="7%"><?php echo $row['FechaRecepcion'];?></td>
		   </tr>
 <?php
	$pos=$pos + 1;
	}
	
	mysql_free_result($consulta);?>
	
   <input type="hidden" name="oculto" id="text" value='".$pos."' /> 
   
		</table>
    
	<div id="boton">
	<table width="90%" border="0" align="center">
			<tr>
			<td colspan="7" align="center">	
			<div id="boton">	
			<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
			<input type="button" name="btncl" id="btnct" value="Regresar" onClick="window.close();"> </div>
			</div>	</td>
				
			</tr></table>
</body>
</html>
