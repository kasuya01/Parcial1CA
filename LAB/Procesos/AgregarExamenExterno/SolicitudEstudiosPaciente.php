<?php session_start();
include_once("clsAgregarExamen.php");
if(isset($_SESSION['Correlativo']))
{
    $usuario=$_SESSION['Correlativo'];
    $lugar=$_SESSION['Lugar'];
    $area=$_SESSION['Idarea'];
    $nivel=$_SESSION['NIVEL'];
   // echo $area;
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<title>Datos del Generales de la Solicitud</title>
<script language="JavaScript" type="text/javascript" src="ajax_AgregarExamen.js"></script>
<script language="JavaScript" type="text/javascript">
    function CargarExamenes(area)
    {
        alert(area);
       // document.frmDatos.txtarea.value=area;
        LlenarComboExamen(area);
    }
</script>
</head>
<?php 
	$idexpediente=$_GET['var1'];	
	$idsolicitud=$_GET['var2'];
	$idestablecimiento=$_GET['var3'];
	
	//echo $usuario."##".$idsolicitud;
	$obj = new clsAgregarExamen;
	$ConEmp=$obj->ObtenerCodigoTecnico($usuario);
	$rowEmp=mysql_fetch_array($ConEmp);
	$CodEmpleado=$rowEmp[0];
	//echo $CodEmpleado;
	
	$conEst=$obj->DatosEstablecimiento($idestablecimiento);
	$rowEst = mysql_fetch_array($conEst);
	$tipo=$rowEst[0];
	$nombrEstab=$rowEst[1];
	$nomtipo=$rowEst[2];
        
        $ConArea=$obj->DatosArea($area);
        $rowArea = mysql_fetch_array($ConArea);
	
        $area1=$area;
        $nomarea=$rowArea[0];
	
	$consulta=$obj->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
	$row = mysql_fetch_array($consulta);
	
	
//valores de las consultas

	//obteniedo los datos generales de la solicitud
	//valores de las consultas
	$medico=$row['NombreMedico'];
	$idmedico=$row['IdMedico'];
	$paciente=$row['NombrePaciente'];
	$edad=$row['Edad'];
	$sexo=$row['Sexo'];
	$procedencia=$row['Precedencia'];
	$origen=$row['Origen'];
	$DatosClinicos=$row['DatosClinicos'];
	$Estado=$row['Estado'];
	$fechasolicitud=$row['FechaSolicitud'];
	$FechaNac=$row['FechaNacimiento'];
	//recuperando los valores del detalle de la solicitud
	$consultadetalle=$obj->DatosDetalleSolicitud($idexpediente,$idsolicitud,$lugar);
?>
<body onLoad="CargarExamenes($area);" >
<div id="divFormulario">
    <form name="frmDatos">
        <table width="90%" border="0" align="center" class="StormyWeatherFormTABLE">
            <tr class="CobaltButton"><td colspan="4" align="center"><h3>DATOS GENERALES</h3></td></tr>
            <tr>
                <td width="6%" class="StormyWeatherFieldCaptionTD">Establecimiento Solicitante</td>
		<td width="12%" class="StormyWeatherDataTD"><?php echo $nombrEstab;?></td> 
                    <input type="hidden" name="txtidsolicitud" id="txtidsolicitud" value="<?php echo $idsolicitud ?>"/>
                    <input type="hidden" name="txtempleado" id="txtempleado" value="<?php echo $CodEmpleado ?>"/>
            </tr>	    
            <tr>
                <td width="6%" class="StormyWeatherFieldCaptionTD">Procedencia</td>
		<td width="12%" class="StormyWeatherDataTD"><?php echo $procedencia;?></td>
            </tr>
            <tr>
                <td width="6%" class="StormyWeatherFieldCaptionTD">Servicio</td>
		<td width="12%" class="StormyWeatherDataTD"><?php echo $origen;?></td>
            </tr>
            <tr>
	        <td width="6%" class="StormyWeatherFieldCaptionTD">NEC</td>
                <td width="12%" class="StormyWeatherDataTD"><?php echo $idexpediente; ?>
            </tr>
            <tr>
                <td width="6%" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
                <td width="12%" class="StormyWeatherDataTD">
                	<select id="cmbArea" name="cmbArea" size="1"  onchange="LlenarComboExamen(this.value)" >
				<option value="0" >--Seleccione Área--</option>
						<?php
                                                     echo '<option value="'.$area1.'" >'.htmlentities($nomarea).'</option>';	
						/*	$conArea= $obj->ConsultarAreas();
							while($row = mysql_fetch_array($conArea)){
							echo "<option value='" . $row[0]. "'>" . $row[1] . "</option>";
							}
							mysql_free_result($consulta_bac);*/
						?>	
					</select></td>
			</tr>
			<tr>
				<td width="6%" class="StormyWeatherFieldCaptionTD">Examen</td>
		        <td width="12%" class="StormyWeatherDataTD">
					<div id="divExamen">
						<select id="cmbExamen" name="cmbExamen" size="1" onchange="LlenarComboMuestra(this.value)">
							<option value="0" >--Seleccione Examen--</option>
						</select>
					</div>	
					
				</td>
			</tr>	
			<tr>
				<td width="6%" class="StormyWeatherFieldCaptionTD">Tipo Muestra</td>
		        <td width="12%" class="StormyWeatherDataTD">
					<div id="divMuestra">
						<select id="cmbMuestra" name="cmbMuestra" size="1" onchange="LlenarComboOrigen(this.value)">
							<option value="0" >--Seleccione Tipo de Muestra--</option>
						</select>
					</div>	
				</td>
			</tr>
			<tr>
				<td width="6%" class="StormyWeatherFieldCaptionTD">Origen Muestra</td>
		        <td width="12%" class="StormyWeatherDataTD">
					<div id="divOrigen">
						<select id="cmbOrigen" name="cmbOrigen" size="1" >
							<option value="0" >--Seleccione Origen Muestra--</option>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<td width="6%" class="StormyWeatherFieldCaptionTD">Indicaci�n</td>
		        <td width="12%" class="StormyWeatherDataTD"><textarea name="txtindicacion" cols="50" id="txtindicacion"></textarea></td>
			</tr>
			<tr>
				<td width="6%" class="StormyWeatherFieldCaptionTD">Observaci�n</td>
		        <td width="12%" class="StormyWeatherDataTD"><textarea name="txtObservacion" cols="50" id="txtObservacion"></textarea></td>
			</tr>
			<tr>
				<td width="6%" colspan="2" class="StormyWeatherFieldCaptionTD" align="right">
					<input type="button" name="Submit" value="Guardar Cambios" Onclick="GuardarExamen();"> </td>
			</tr>
				
		</table>	
	</form>
</div>

</body>
</html>
<?php
}
else{?>
<script language="javascript">
	window.location="../../../login.php";
</script>
<?php }?>