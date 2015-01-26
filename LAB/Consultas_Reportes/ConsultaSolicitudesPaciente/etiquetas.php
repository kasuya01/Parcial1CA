<?php session_start();
 if(isset($_SESSION['Correlativo'])){
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];

$ROOT_PATH = $_SESSION['ROOT_PATH'];
 $base_url  = $_SESSION['base_url'];
 
 ?>
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


.Estilo5 {font-size: 5pt}
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
<br><br>
<table  border="0"  align="rigth" class='estilotabla' >
<tr>
	<td>
	<div id="boton">	<!--<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />-->
                                <button type='button' align='center' class='btn btn-primary'  onClick=' window.print();' ><span class='glyphicon glyphicon-print'></span> Imprimir </button>
        </div>
	</td>
</tr>
</table> <br><br>

<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
    
<?php 

include_once("clsSolicitudesPorPaciente.php");
//variables POST
$idexpediente=$_GET['var1'];
$idsolicitud=$_GET['var2'];
//echo $idexpediente."-".$idsolicitud;
//$idexpediente='24509-03';
//$fechacita='2008-07-03';


$objdatos = new clsSolicitudesPorPaciente;
//echo $fechacita;
//recuperando los valores del detalle de la solicitud

$consultadetalle=$objdatos->VinetasRecepcion($idexpediente,$idsolicitud);

while($fila = pg_fetch_array($consultadetalle)){
 
    
    
 //impresion
    //if ($fila[7]=="G"){
        
        
                            //echo "esto es dentro del if";
                    //idestablecimiento 10 
			$codigo= $idsolicitud."-".$fila[2];
			$codigo1= $idsolicitud."      ".$fila[3];
		?>    
			<table width="30" border = "0" class="estilotabla">
		 		<tr align="center">
		   			<td  colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">
                                            <span class="Estilo5"><?php echo $fila[0];?></span>
                                                                        
						<span class="Estilo5"><?php echo $fila[6];?></span> 
					</td> 
					<td  colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">&nbsp;&nbsp;&nbsp;</td>
					<td  colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">
                                                                
                                            <span class="Estilo5"><?php echo"M=".$fila[1];?></span>
					</td>
				</tr>
				<tr>
					<!-- <td colspan="3" align="left" CELLPADDING="0" CELLSPACING="0"><img align="center" style="width: 100px; height: 25px;" src="/siap1/Laboratorio/Barcode2/barcode_img.php?num=<?php echo($codigo1) ?>&type=code128&imgtype=png" -->
					
                                   <!-- <td colspan="3" align="left" CELLPADDING="0" CELLSPACING="0"><img align="center" style="width: 100px; height: 25px;" src="/siap1/Laboratorio/Barcode2/barcode_img.php?num=<?php echo($codigo1) ?>&type=code128&imgtype=png"
						 alt="PNG: <?php echo($codigo1) ?>" title="PNG:<?php echo($codigo1) ?>"></td> -->
                                    
                                    <td colspan="3" align="left" CELLPADDING="0" CELLSPACING="0"><img align="center" style="width: 100px; height: 25px;" src="../../../Barcode2/barcode_img.php?num=<?php echo($codigo1) ?>&type=code128&imgtype=png"	
                                       alt="PNG: <?php echo($codigo1) ?>" title="PNG:<?php echo($codigo1) ?>"></td>
				</tr>
				<tr>                                                                               
					<td colspan="1" align="center" CELLPADDING="0" CELLSPACING="0"><span class="Estilo12"><?php echo $fila[4];?></span></td>
					<td colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">&nbsp;&nbsp;&nbsp;</td>     
					<td colspan="1" align="left" CELLPADDING="0" CELLSPACING="0"><span class="Estilo12"><?php echo $fila[7];?></span></td>
				</tr>
			</table>
		
			<br>
                        
		
		<?php
		 
                
                   // }
                
		/*else{     
                                   // echo "esto es dentro del else";

                                            //idestablecimiento     estanadar 
			$codigo= $idsolicitud."-".$fila[12]."-".$fila[10];
			$codigo1= $idsolicitud."&nbsp;&nbsp;&nbsp;".$fila[12];
			 // echo $codigo;
			?>
			<table width="30" border = "0"  class="estilotabla">
		 		<tr align="center">
                                    
		   			<td colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">
                                        <span class="Estilo5"><?php echo $fila[4];?></span>
                                        <span class="Estilo5"><?php echo $fila[3];?></span>
					</td> 
					<td colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">&nbsp;&nbsp;&nbsp;</td>
					<td colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">
						<span class="Estilo5"><?php echo "M=".$fila[5];?></span>
					</td>
					
		 		</tr>
				<tr>
					<td colspan="3" align="left" CELLPADDING="0" CELLSPACING="0">
						<img align="center" style="width: 100px; height: 25px;" src="../../../Barcode2/barcode_img.php?num=<?php echo($codigo1) ?>&type=code128&imgtype=png"
                                                     accesskey="" alt="PNG: <?php echo($codigo) ?>" title="PNG:<?php echo($codigo) ?>">
					</td>
				</tr>
				<tr>
					<td colspan="1" align="left" CELLPADDING="0" CELLSPACING="0"><span class="Estilo5"><?php echo $fila[8];?></span></td>
					<td colspan="1" align="left" CELLPADDING="0" CELLSPACING="0">&nbsp;&nbsp;&nbsp;</td>
					<td colspan="1" CELLPADDING="0" CELLSPACING="0"><span class="Estilo5"><?php echo $fila[10];?></span></td>
				</tr>
			</table>
		<br>
		<br>
			
	<?php
	}*/
}	
   ?>
</form>
</body>
</html>

<div id="divFormulario">

</div>

</body>
</html><?php
}else{?>
<script language="javascript">
	window.location="../../../login.php";
</script>
<?php }?>
