<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//Datos Recibidos
$iddetalle=$_GET['var1'];
$idsolicitud=$_GET['var2'];
$idplatilla=$_GET['var3'];
$expediente=$_GET['var4'];
$idarea=$_GET['var5'];
$IdExamen=$_GET['var6'];

//echo $iddetalle."*soli".$idsolicitud."*plantilla".$idplatilla."*exp".$expediente."*area".$idarea."*exam".$IdExamen
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_ImprimirResultado.js"></script> 
<!--<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">-->
<script language="JavaScript">
function calc_edad()
{
  var fecnac1=document.getElementById("suEdad").value;
  var fecnac2=fecnac1.substring(0,10);
//alert (fecnac2);
  var suEdades=calcular_edad(fecnac2);
 // alert(suEdades);
       
  document.getElementById("divsuedad").innerHTML=suEdades;
}
</script>
<style type="text/css">
<!--
@media print{
#boton{display:none;}
}


.Estilo5 {font-family: Helvetica; font-size: 10pt}
.Estilo6 {font-family: Helvetica; font-size: 9pt}
.Estilo7 {font-family: Helvetica; font-size: 11pt}
-->
</style>


</head>

<body>
<?php 
include_once("clsImprimirResultado.php");
$objdatos = new clsImprimirResultado;
$conExt=$objdatos->BuscarEstablecimientoExterno($usuario,$lugar);
$EstablecimientoExt= mysql_fetch_array($conExt);
$EstabExt=$EstablecimientoExt[0];
//echo $EstabExt;
$Consulta_Estab=$objdatos->Nombre_Establecimiento($EstabExt);
$row_estab = mysql_fetch_array($Consulta_Estab);	
$consulta=$objdatos->MostrarResultadoGenerales($idsolicitud,$IdExamen,$lugar);
$row = mysql_fetch_array($consulta);
$nombre=$row['NombreArea'];

?>
<table align="center" width="100%">
<tr>
	<td>
	<div  id="divImpresion" >
            <form name="frmimpresion" >
		<table width='100%' align='center' class='StormyWeatherFormTABLE'>
                    <tr>
                        <td colspan='1' align="left" width='20%'><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                        <td align="center" colspan='4' width='60%'><img id="Image2" style="WIDTH: 100px; HEIGHT: 55px" height="86" src="../../../Imagenes/INS.png" width="210" name="Image2"></td>
                        <td colspan='1' align="right" width='20%'><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.gif" width="210" name="Image3"></td>
                    </tr>	
                    <tr>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
                        <td align="center" colspan="4" width="60%" class="Estilo6">
					<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
					<p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
					<p><strong>ÁREA DE <?php echo $nombre; ?> </strong></p>
			</td>
                        <td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
		    </tr>
		     <tr>
			<td colspan='6' align='center'>&nbsp;&nbsp;&nbsp;</td>
		     </tr>
		     <tr>
			<td colspan="1" class="Estilo5"><strong>Establecimiento Solicitante:</strong></td>
			<td colspan="2" class="Estilo6"><?php echo htmlentities($row['Nombre']);?></td>
			<td colspan="1" class="Estilo5"><strong>Fecha Resultado:</strong></td>
			<td colspan="2" class="Estilo6"><?php echo $row['Fecha'];?></td>
				<input name='suEdad' id='suEdad' type='hidden'  value=<?php echo $row['FechaNacimiento']?>>
		     </tr>
		     <tr>
			<td colspan="1" class="Estilo5"><strong>NEC</strong></td>
			<td colspan="5" class="Estilo7"><?php echo $row['IdNumeroExp'];?></td>
                     </tr>
                     <tr>
			<td colspan="1" class="Estilo5"><strong>Paciente:</strong></td>
			<td colspan="5" class="Estilo6"><?php echo htmlentities($row['NombrePaciente'])?></td>
                    </tr>	
                    <tr>
			<td colspan="1" class="Estilo5"><strong>Edad:</strong></td>
			<td colspan="2" class="Estilo6"><div id="divsuedad">
        				<script language="JavaScript" type="text/javascript">
               					calc_edad();
          				</script>
    		   		    </div>
				</td>
				
				<td colspan="1" class="Estilo5"><strong>Sexo:</strong></td>
				<td colspan="2" class="Estilo6"><?php echo $row['Sexo']?></td>					
			</tr>	
			
			<?php 
				$consulta2=$objdatos->MostrarDatosFijosPlantillaA($IdExamen,$lugar,$idsolicitud);
				$fila = mysql_fetch_array($consulta2);
			?>
                        <tr>
				<td colspan="1" class="Estilo5"><strong>Procedencia:</strong></td>
				<td colspan="2" class="Estilo6"><?php echo htmlentities($row['Procedencia'])?></td>
				<td colspan="1" class="Estilo5"><strong>Servicio:</strong></td>
				<td colspan="2" class="Estilo6"><?php echo htmlentities($row['Origen'])?></td>
			</tr>
			<tr>
				<td  colspan='1' class="Estilo5"><strong>Validado Por: </strong></td>
				<td  colspan='5' class="Estilo6"><?php echo htmlentities($row['NombreEmpleado'])?></td>
			</tr>
			<tr>
				<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td colspan='6' align='center' class="Estilo5" ><strong>DETALLE DE RESULTADOS</strong></td>
			</tr>
                        <tr>
                            <td  colspan="6">
                                <table width="100%"  align="center" border="0" class="StormyWeatherFormTABLE">
                                    <tr class="CobaltButton">
                                            <td align="left" class="Estilo5"><strong> Prueba Realizada</strong></td>
                                            <td align="center" class="Estilo5"><strong>Resultado</strong></td>
                                            <td align="center" class="Estilo5"><strong>Unidades</strong></td>
                                            <td align="center" class="Estilo5"><strong>Rangos Normales<strong></td>
                                            <td align="center" class="Estilo5"><strong>Lectura</strong></td>
                                            <td align="center" class="Estilo5"><strong>Interpretaci&oacute;n</strong></td>
                                            <td align="center" class="Estilo5"><strong>Observaci&oacute;n</strong></td>
                                    </tr>	
                                    <tr>
                                            <td align="left" class="Estilo5"><?php echo htmlentities($fila['NombreExamen'])?></td>
                                            <td align="center" class="Estilo5"><?php echo htmlentities($row['Resultado'])?></td>
                                            <td align="center" class="Estilo5"><?php echo htmlentities($fila['Unidades']) ?></td>
                                            <td align="center" class="Estilo5"><?php echo $fila['RangoInicio']."-".$fila['RangoFin']?></td>
                                            <td align="justify" class="Estilo5"><?php echo htmlentities($row['Lectura'])?></td>
                                            <td align="justify" class="Estilo5"><?php echo htmlentities($row['Interpretacion'])?></td>
                                            <td align="justify" class="Estilo5"><?php echo htmlentities($row['Observacion'])?></td>
                                    </tr> 
                                </table>
			  </td>
                        </tr>
                    </table>
                    <div id="boton">
                        <table align='center' border="0">
                            <tr>
				<td colspan="7" align="center" >
                                    <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                                    <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="window.close();" />
				 	 
				</td>
                            </tr>		
                        </table>
                      </div>  
		</form>
	</div>
</tr>
</table>