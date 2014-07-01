<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<link href="/PrimerSitio/paginalab.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<style type="text/css">
<!--
@media print{
#boton{display:none;}
}


.Estilo5 {font-size: 5pt}
.Estilo12 {font-size: 4pt}
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
						fechacita= getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var3=' )
						idsolicitud = getVars[i].substr(5);	
			if ( getVars[i].substr(0,5) == 'var=' )
						idestablecimiento = getVars[i].substr(5);								
		}
//alert(idexpendiente);
//CargarDatosEtiqueta( idexpediente,fechacita);
}

// imprimir(){
// document.getElementById('btnImprimir').style.visibility="hidden";
// window.print();
// document.getElementById('btnImprimir').style.visibility="visible";
// }
</script>
</head>

<body onLoad="RecogeValor();">

<table  border="0"  align="rigth" class='estilotabla' >
<tr>
	<td>
		<div id="boton"><input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" /></div>
	</td>
</tr>
</table>


<?php 

include_once("clsRecepcionSolicitud.php");
//variables POST
$idexpediente=$_GET['var1'];
$fechacita=$_GET['var2'];
$idsolicitud=$_GET['var3'];
$IdEstablecimiento=$_GET['var4'];
//echo "sol=".$idsolicitud."EXP=".$idexpediente."FECHA".$fechacita."Establecimiento=".$IdEstablecimiento;
//$idsolicitud=80001;
$Nfecha=explode("/",$fechacita);
//print_r($Nfecha);
$Nfechacita=$Nfecha[2]."-".$Nfecha[1]."-".$Nfecha[0]; 
 //$idexpediente='100100-07';
// echo $_GET['var3'];


$objdatos = new clsRecepcionSolicitud;
//echo $fechacita;
//recuperando los valores del detalle de la solicitud
$consultadetalle=$objdatos->VinetasRecepcion($idexpediente,$Nfechacita,$idsolicitud,$IdEstablecimiento);

while($fila = mysql_fetch_array($consultadetalle)){
	if ($fila[6]=="G"){
	  $codigo= $idsolicitud."-".$fila[10];
	  $codigo1= $idsolicitud."&nbsp;&nbsp;&nbsp;".$fila[10];
	//echo $fila[6]."   ".$codigo1;
	?>    
	
		<table width="35" border = "0" class="estilotabla">
	 		<tr align="center">
	   			<td  colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">
					<span class="Estilo5"><?php echo $fila[2];?></span>
				</td> 
				<td  colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">&nbsp;&nbsp;&nbsp;</td>
				<td  colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">
					<span class="Estilo5"><?php echo "M=".$fila[4];?></span>
				</td>
				
	 		</tr>
			
			<tr>
				<td colspan="3" align="left" CELLPADDING="0" CELLSPACING="0"><img align="center" style="width: 100px; height: 25px;" src="../../../Barcode2/barcode_img.php?num=<?php echo($codigo) ?>&type=code128&imgtype=png"
					 alt="PNG: <?php echo($codigo1) ?>" title="PNG:<?php echo($codigo1) ?>">
				<!--<img align="center" 
				style="width: 105px; height: 30px;" src="../../../Barcode2/barcode_img.phpBarcode2/barcode_img.php?num=<?php //echo($codigo) ?>&type=code128&imgtype=png"
					 alt="PNG: <?php //echo($codigo1) ?>" title="PNG:<?php// echo($codigo1) ?>">--></td>
			</tr>
			<tr>
				<td colspan="1" align="center" CELLPADDING="0" CELLSPACING="0"><span class="Estilo12"><?php echo $fila[7];?></span></td>
				<td  colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">&nbsp;&nbsp;&nbsp;</td>
				<td  colspan="1" align="left" CELLPADDING="0" CELLSPACING="0"><span class="Estilo12"><?php echo $fila[11];?></span></td>
			</tr>
		
		</table>
		
		<br>

   <?php
    }
	else{
			$codigo= $idsolicitud."-".$fila[10]."-".$fila[9];
			 $codigo1= $idsolicitud."&nbsp;&nbsp;&nbsp;".$fila[10];
			  //echo $codigo;
	?>
	
		<table width="30%" border = "0"  class="estilotabla">
	 		<tr align="center">
	   			<td  colspan="2" align="left" CELLPADDING="0" CELLSPACING="0">
					<span class="Estilo5"><?php echo $fila[2];?></span>
				</td> 
				<td  colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">
					<span class="Estilo5"><?php echo "M=".$fila[4];?></span>
				</td>
			</tr>
			<tr>
				<td  colspan="3" align="left" CELLPADDING="0" CELLSPACING="0">
					<img align="center" style="width: 100px; height: 25px;" src="../../../Barcode2/barcode_img.php?num=<?php echo($codigo) ?>&type=code128&imgtype=png"
					 alt="PNG: <?php echo($codigo) ?>" title="PNG:<?php echo($codigo) ?>">
				</td>
			</tr>
			<tr>
				<td colspan="1" align="left" CELLPADDING="0" CELLSPACING="0"><span class="Estilo5"><?php echo $fila[7];?></span></td>
				<td  colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">&nbsp;&nbsp;&nbsp;</td>
				<td colspan="1" CELLPADDING="0" CELLSPACING="0"><span class="Estilo5"><?php echo $fila[9];?></span></td>
			</tr>
			
			
		</table>
		<br>
		<br>
	<?php
	}
}	
mysql_free_result($consultadetalle);
//$codigo= '1234556' ;
    ?>
</form>
</body>
</html>


<div id="divFormulario">

</div>

</body>
</html>
