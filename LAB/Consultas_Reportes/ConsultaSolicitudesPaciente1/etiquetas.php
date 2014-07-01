<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="/PrimerSitio/paginalab.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<style type="text/css">
<!--
@media print{
#boton{display:none;}
}


.Estilo5 {font-size: 10pt}
.Estilo12 {font-size: 6pt}
-->
</style>
<title>Datos de las Viñetas</title>
<script language="JavaScript" type="text/javascript" src="ajax_RecepcionSolicitud.js"></script>
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
							idsolicitud= getVars[i].substr(5);
				}
//alert(idexpendiente);
//CargarDatosEtiqueta( idexpediente,fechacita);
}

</script>
</head>

<body onLoad="RecogeValor();">

<table  border="0"  align="rigth" class='estilotabla' >
<tr>
	<td>
	<div id="boton">	<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" /></div>
	</td>
</tr>
</table>


<?php 

include_once("clsSolicitudesPorPaciente.php");
//variables POST
$idexpediente=$_GET['var1'];
$idsolicitud=$_GET['var2'];
//echo $idexpediente;
//$idexpediente='24509-03';
//$fechacita='2008-07-03';


$objdatos = new clsSolicitudesPorPaciente;
//echo $fechacita;
//recuperando los valores del detalle de la solicitud

$consultadetalle=$objdatos->VinetasRecepcion($idexpediente,$idsolicitud);

while($fila = mysql_fetch_array($consultadetalle)){
		if ($fila[6]=="G"){
		?>    
			<table width="173" border = 0  class="estilotabla">
				<tr>
					<td width="54"><span class="Estilo12"><strong>Expediente:</strong></span></td>
					<td width="47"><span class="Estilo12"><?php echo $fila[2];?></span></td>  
					<td width="37"><strong class="Estilo12"> Muestra:</strong></td> 
					<td width="17"><span class="Estilo12"><?php echo $fila[4];?></span></td>
				</tr>
				<tr>  
					<td height="20" style='font:bold'><span class="Estilo12"><strong>Paciente:</strong></span></td>
					<td colspan="3" class="Estilo5" align="left" >
						<div align="justify" class="Estilo12">
							<?php echo htmlentities($fila[3]);?>
						</div>
					</td>
				</tr>
				<tr>	 
					<td><span class="Estilo12"><strong>&Aacute;rea:</strong></span></td> 
					<td colspan="3"><span class="Estilo12"><?php echo $fila[0];?></span></td>				
				</tr>
				<tr>
					<td><strong class="Estilo12">Tipo Muestra:</strong></td>
					<td colspan="3"><span class="Estilo12"><?php echo $fila[1];?></span></td>	 		 
				</tr>
			</table>
			<br>
			<br>

		<?php
		}
		else{ 
		?>
			<table width="173" border = 0  class="estilotabla">
				<tr>
					<td width="54"><span class="Estilo12"><strong>Expediente:</strong></span></td>
					<td width="47"><span class="Estilo12"><?php echo $fila[2];?></span></td>  
					<td width="37"><strong class="Estilo12"> Muestra:</strong></td> 
					<td width="17"><span class="Estilo12"><?php echo $fila[4];?></span></td>
				</tr>
				<tr>  
					<td height="20" style='font:bold'><span class="Estilo12"><strong>Paciente:</strong></span></td>
					<td colspan="3" class="Estilo5" align="left" >
						<div align="justify" class="Estilo12">
							<?php echo htmlentities($fila[3]);?>
						</div>
					</td>
				</tr>
				<tr>	 
					<td><span class="Estilo12"><strong>&Aacute;rea:</strong></span></td> 
					<td colspan="3"><span class="Estilo12"><?php echo $fila[0];?></span></td>				
				</tr>
				<tr>
					<td><strong class="Estilo12">Prueba:</strong></td>
					<td colspan="3"><span class="Estilo12"><?php echo $fila[5];?></span></td>	 		 
				</tr>
			</table>
			<br>
			<br>
	<?php
	}
}	
   ?>
</form>
</body>
</html>

<div id="divFormulario">

</div>

</body>
</html>
