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
$idexamen=$_GET['var6'];

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

  var suEdades=calcular_edad(fecnac2);

  document.getElementById("divsuedad").innerHTML=suEdades;
}

</script>
<style type="text/css">
<!--
@media print{
#boton{display:none;}
}
@media print{
#boton1{display:none;}
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
//creando los objetos de las clases
$obj = new clsImprimirResultado;

	$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
	$row_estab = mysql_fetch_array($Consulta_Estab);

	if ($idexamen=="COA006"){
		$consulta_datos=$obj->LeerDatos($idexamen);
		$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
		$row_generales= mysql_fetch_array($datos_generales);
		$row_area = mysql_fetch_array($consulta_datos);
	?>
	<table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>
		<tr>
			<td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                        <td align="center" colspan="4" width="60%" class="Estilo6">
				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
                                <p><strong><?php  echo                        $row_estab['Nombre'] ?></strong></p>
				<p><strong>Area de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p>
			</td>
                        <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
		</tr>
                <tr>
                        <td colspan='6'>&nbsp;</td>
                </tr>
		<tr>
			<td colspan='1' class="Estilo5">Establecimiento Solicitante:</td>
			<td colspan='2' class="Estilo6"><?php echo htmlentities($row_generales['Nombre']) ?></td>
			<td colspan='1' class="Estilo5">Fecha Resultado:</td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['Fecha']?>
				<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
			</td>
		</tr>

		<tr>
			<td colspan='1' class="Estilo5">NEC</td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['IdNumeroExp']?></td>

			<td colspan='1' class="Estilo5"><strong>Paciente:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Edad:</strong></td>
			<td colspan='2' class="Estilo6">
				<div id="divsuedad">
        				<script language="JavaScript" type="text/javascript">
               					calc_edad();
          				</script>
    		   	</div>
			</td>

			<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='2'><?php echo $row_generales['Sexo']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Procedencia:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo htmlentities($row_generales['Procedencia'])?></td>

			<td colspan='1' class="Estilo5"><strong>Servicio:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo htmlentities($row_generales['Origen'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Validado Por:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_generales['NombreEmpleado'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Examen Realizado:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_area['NombreExamen'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Observacion:<strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_generales['Observacion'])?></td>
		</tr>
			<?php mysql_free_result($consulta_datos);
			      mysql_free_result($datos_generales);
			      $consulta=$obj->LeerProcesoExamen($idsolicitud,$iddetalle);
			?>
		<tr>
			<table width='100%' border='0' align='center' class='StormyWeatherFormTABLE' >
				<tr >
					<td class="Estilo5"> Prueba </td>
					<td class="Estilo5"> Resultado </td>
					<td class="Estilo5"> Unidades </td>
					<td class="Estilo5"colspan='2' > Control Diario </td>
				</tr>
					<?php	$pos=0;
					while($row = mysql_fetch_array($consulta))//ELEMENTOS
					{?>
				<tr>
					<td class="Estilo5"  width='25%'align='left'><?PHP echo htmlentities($row['NombreProcedimiento'])?></td>
					<td align='center' width='30%' >
						<input name='oidprueba[<?php $pos ?>]' type='hidden' id='oidprueba[<?php $pos ?>]' value='<?php $row['IdProcedimientoporexamen'] ?>'><?php echo htmlentities($row['Resultado'])?></td>
					<td class="Estilo5" align='center' width='30%'><?php echo $row['Unidades']?></td>
					<td class="Estilo5" align='center'><?php echo htmlentities($row['Observacion'])?></td>	<td><?php echo htmlentities($row['Unidades'])?></td>
				</tr>
					<?php $pos=$pos + 1;
					}
						mysql_free_result($consulta);?>
			</table>
		</tr>
		<tr>
			<td colspan="7" align="center" >
				<div id="boton1">
					<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
					<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;"/>
				</div>
			</td>
		</tr>
	</table>

	<?php
	}else{
	$consulta_datos=$obj->LeerDatos($idexamen);
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);

	$row_generales= mysql_fetch_array($datos_generales);
	$row_area = mysql_fetch_array($consulta_datos);


	?>
	<table width='100%' border='0' align='center' cellspacing="0">
		<tr>
			<td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                        <td align="center" colspan="4" width="60%" class="Estilo6">
				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
				 <p><strong><?php  echo                        $row_estab['Nombre'] ?></strong></p>
				<p><strong>Area de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p>
			</td>
                         <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
		</tr>
                <tr>
                    <td colspan='6'>&nbsp;</td>
                </tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Establecimiednto Solicitante:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo htmlentities($row_generales['Nombre']) ?></td>
			<td colspan='1' class="Estilo5"><strong>Fecha Resultado:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['Fecha']?>
				<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
			</td>
		</tr>

		<tr>
			<td colspan='1' class="Estilo5"><strong>NEC</strong></td>
			<td colspan='5' class="Estilo7"><?php echo $row_generales['IdNumeroExp'] ?></td>
                </tr>
                <tr>

			<td colspan='1' class="Estilo5"><strong>Paciente:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_generales['NombrePaciente'])?></td>

		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Edad:</strong></td>
			<td colspan='2' class="Estilo6">
				<div id="divsuedad">
        				<script language="JavaScript" type="text/javascript">
               					calc_edad();
          				</script>
    		   	</div>
			</td>

			<td colspan='1' class="Estilo5"><strong>Sexo:</strong></td>
			<td colspan='1' class="Estilo6"><?php echo $row_generales['Sexo']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Procedencia:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['Procedencia']?></td>

			<td colspan='1' class="Estilo5"><strong>Servicio:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['Origen']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Examen Realizado:</strong></td>
			<td colspan='4' class="Estilo6"><?php echo htmlentities($row_area['NombreExamen'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Validado Por:</strong></td>
			<td colspan='4' class="Estilo6"><?php echo htmlentities($row_generales['NombreEmpleado'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Observacion:</strong></td>
			<td colspan='4' class="Estilo6"><?php echo htmlentities($row_generales['Observacion'])?></td>
		</tr>
                <tr>
                    <td colspan='6'>&nbsp;</td>
                </tr>
		<tr>
                    <td colspan='6'>
			<?php mysql_free_result($consulta_datos);
			      mysql_free_result($datos_generales);
			      $consulta=$obj->LeerProcesoExamen($idsolicitud,$iddetalle);

	        ?>
			<table width='100%' border='0' align='center' cellspacing="0">
				<tr >
                                        <td class="Estilo5" width='25%' align='left'><strong> Prueba </strong> </td>
					<td class="Estilo5" width='30%' align='center'><strong> Resultado </strong></td>
					<td class="Estilo5" width='20%' align='center'><strong> Unidades </strong></td>
					<td class="Estilo5" width='30%' align='center'><strong> Rango </strong></td>
				</tr>
					<?php $pos=0;
				while($row = mysql_fetch_array($consulta))//ELEMENTOS
					{  ?>
				<tr>
					<td width='25%' align='left' class="Estilo5"><?php echo htmlentities($row['NombreProcedimiento'])?></td>
					<td width='30%' align='center' class="Estilo5"><input name='oidprueba[<?php $pos?>]' type='hidden' id='oidprueba[<?php $pos?>]' value='<?php $row['IdProcedimientoporexamen']?>'><?php echo htmlentities($row['Resultado'])?></td>
					<td width='20%' align='center' class="Estilo5"><?php echo htmlentities($row['Unidades'])?></td>
					<td width='30%' align='center' class="Estilo5"align='center'><?php
                                              if((!empty($row['rangoinicio'])) AND (!empty($row['rangoinicio'])))
                                                    echo $row['RangoInicio']." - ".$row['rangofin']?>
                                        </td>
                                </tr>
					<?php  $pos=$pos + 1;
					}
					mysql_free_result($consulta);?>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
                <div id="boton">
                     <table align="center">
                        <tr>
                            <td colspan="7" align="center" >
                                <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                                <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;"/>
                            </td>
			</tr>
                     </table>
                </div>

		<?php }?>
