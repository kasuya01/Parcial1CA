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

 $query = "SELECT mnt_empleados.IdEmpleado AS IdMedico,NombreEmpleado AS NombreMedico,NombreSubServicio AS Origen,
		NombreServicio AS Procedencia, mnt_expediente.IdNumeroExp AS IdNumeroExp , 
		CONCAT_WS(' ',PrimerApellido,NULL,SegundoApellido,',',PrimerNombre,NULL,SegundoNombre) AS NombrePaciente,
		DATE_FORMAT(cit_citasxserviciodeapoyo.Fecha,'%e/ %m / %Y') AS Fecha,sec_solicitudestudios.IdSolicitudEstudio, CASE sec_solicitudestudios.Estado 
		WHEN 'D' THEN 'Digitada'
		WHEN 'R' THEN 'Recibida'
			 WHEN 'P' THEN 'En Proceso'    
			 WHEN 'C' THEN 'Completa' END AS Estado,mnt_establecimiento.Nombre
			 FROM sec_historial_clinico 
			 INNER JOIN sec_solicitudestudios  ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
			 INNER JOIN mnt_empleados ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
			 INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp= mnt_expediente.IdNumeroExp
			 INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente  
			 INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
			 INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
			 INNER JOIN cit_citasxserviciodeapoyo ON sec_solicitudestudios.IdSolicitudEstudio =cit_citasxserviciodeapoyo.IdSolicitudEstudio
			 INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
			 WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_solicitudestudios.Estado='D' AND sec_solicitudestudios.IdEstablecimiento=$lugar  AND";
			
			$ban=0;
			
				//VERIFICANDO LOS POST ENVIADOS
			
		if (!empty($_GET['var1']))
		{ $query .= " sec_solicitudestudios.IdNumeroExp='".$_GET['var1']."' AND";}
		
		if (!empty($_GET['var2']))
		{ $Nfecha=explode("/",$_GET['var2']);
		  //print_r($Nfecha);
                   $Nfechacon=$Nfecha[2]."-".$Nfecha[1]."-".$Nfecha[0]; 
			$query .= " cit_citasxserviciodeapoyo.Fecha='".$Nfechacon."' AND";}
		
		if (!empty($_GET['var5']))
		{ $query .= " mnt_datospaciente.PrimerApellido='".$_GET['var5']."' AND";}
				
		if (!empty($_GET['var6']))
		{ $query .= " mnt_datospaciente.SegundoApellido='".$_GET['var6']."' AND";}
				
		if (!empty($_GET['var3']))
			{ $query .= " mnt_datospaciente.PrimerNombre='".$_GET['var3']."' AND";}
			
		if (!empty($_GET['var4']))
		{ $query .= " mnt_datospaciente.SegundoNombre='".$_GET['var4']."' AND";}
				
		if (!empty($_GET['var7']))
		{ $query .= " sec_historial_clinico.IdEstablecimiento='".$_GET['var7']."' AND";}
	
		if (!empty($_GET['var8']))
		{ $query .= " mnt_subservicio.IdServicio ='".$_GET['var8']."' AND";}
		
		if (!empty($_GET['var9']))
		{ $query .= " mnt_subservicio.IdSubServicio ='".$_GET['var9']."' AND";}
		
		if((empty($_GET['var1'])) AND (empty($_GET['var2'])) AND (empty($_GET['var5'])) AND (empty($_GET['var6'])) AND (empty($_GET['var3'])) AND (empty($_GET['var4'])) AND (empty($_GET['var7'])) AND (empty($_GET['var8'])) AND (empty($_GET['var9'])))
		{
				$ban=1;
		}
			
		if ($ban==0)
		{    $query = substr($query ,0,strlen($query)-3);
			$query_search = $query. " ORDER BY PrimerApellido";
		}
	
		//ECHO $query_search;
		
             $consulta1=$objdatos->BuscarCitasPaciente($query_search); 
			 
			$row1 = pg_fetch_array($consulta1);?>
 <table width="100%" border="0" align='center'>
			<tr>
				<td colspan="7" align="center"><h3><strong>REPORTE DE CITAS PROGRAMADAS
				</h3></strong></td>
			</tr>
			<tr>
			<td colspan="7" align="center"><h3><strong><?php echo $row1['Procedencia']; ?></strong></h3></td>
			</td>
			</tr>
			
			</table>
  <?php 
  $consulta=$objdatos->BuscarCitasPaciente($query_search); ?>
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
		<td width="8%"><?php echo $row['Fecha']; ?></td>
		<td width="7%"><?php echo $row['IdNumeroExp'];?></td>
		<td width="25%"><?php echo $row['NombrePaciente'];?></td>
		<td width="15%"><?php echo $row['Origen'];?></td>
		<td width="15%"><?php echo $row['Procedencia'];?></td>
		<td width="30%"><?php echo $row['Nombre'];?></td>	
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
