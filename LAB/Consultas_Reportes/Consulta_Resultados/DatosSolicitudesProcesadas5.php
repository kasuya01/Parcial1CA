<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<title>Solicitudes Procesadas</title>
<script language="JavaScript" type="text/javascript" src="ajax_RecepcionMuestraLab.js"></script>

</head>

<body>
<?php 
include_once("clsSolicitudesProcesadas.php");
//variables POST
$idexpediente=$_POST['idexpediente'];
$idsolicitud=$_POST['idsolicitud'];
$idarea=$_POST['idarea'];

// $idexpediente='29004-3';
// $idsolicitud=1;
// $idarea='HEM';


$objdatos = new clsSolicitudesProcesadas;

//recuperando los valores generales de la solicitud
$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente);
$row = mysql_fetch_array($consulta);
//obteniedo los datos generales de la solicitud
//valores de las consultas
$medico=$row['NombreMedico'];
$idmedico=$row['IdMedico'];
$paciente=$row['NombrePaciente'];
$edad=$row['Edad'];
$sexo=$row['Sexo'];
$precedencia=$row['Precedencia'];
$origen=$row['Origen'];
$DatosClinicos=$row['DatosClinicos'];
//$idsolicitud=$row['IdSolicitudEstudio'];

//recuperando los valores del detalle de la solicitud
$consultadetalle=$objdatos->DatosDetalleSolicitud($idarea,$idsolicitud);

?>
<form id="frmSolicitud">
<table width="80%" border="0" align="center">
		  <tr>
		    <td colspan="4"  class="CobaltFieldCaptionTD"><h3><strong>DATOS SOLICITUD</strong></h3></td>
		    </tr>
		  <tr>
		    <td>Precedencia</td>
		    <td><input name="txtprecedencia" id="txtprecedencia" type="text" size="60" value=" <?php echo $precedencia; ?>" disabled="disabled" /></td>
		    <td>Origen</td>
		    <td><input name="txtorigen" id="txtorigen"  type="text" size="60" value=" <?php echo $origen; ?>" disabled="disabled" />
			<input name="idsolicitud" id="idsolicitud"  type="hidden" size="60" value=" <?php echo $idsolicitud; ?>" disabled="disabled" />
			<input name="idexpediente" id="idexpediente"  type="hidden" size="60" value=" <?php echo $idexpediente; ?>" disabled="disabled" />
			<input name="idexpediente" id="idexpediente"  type="hidden" size="60" value=" <?php echo $paciente; ?>" disabled="disabled" />
			</td>
		    </tr>
		  <tr>
		    <td></td>
		    <td></td>
		    <td></td>
		    <td></td>
		    </tr>
		  <tr>
		    <td>Medico</td>
		    <td colspan="3"><input name="txtmedico" id="txtmedico"  type="text" size="70" value=" <?php echo $medico; ?>" disabled="disabled" /></td>
		    </tr>
		  <tr>
		    <td>Paciente</td>
		    <td colspan="3"><input name="txtpaciente" id="txtpaciente" type="text" size="70" value="<?php echo $paciente; ?>" disabled="disabled" /></td>
		    </tr>
		  <tr>
		    <td>Edad</td>
		    <td><input type="text" name="txtedad" value=" <?php echo $edad; ?>" disabled="disabled" /></td>
		    <td>Sexo</td>
		    <td><input type="text" name="txtsexo" value=" <?php echo $sexo; ?>" disabled="disabled" /></td>
		    </tr>
		  <tr>
		    <td>Datos Clinicos</td>
		    <td colspan="3">
			<textarea cols="100" rows="5" name="txtindicacion" disabled="disabled" ><?php echo $DatosClinicos; ?></textarea>
			</td>		 
		  </tr>
	
  </table>
</td>
</tr>
</table>

<table width="80%" border="0" align="center">
<tr>
<td colspan="4" align="center">ESTUDIOS SOLICITADOS</td>
</tr>
<tr>
<td>
<table border = 1 align='center' class='estilotabla'>
	   <tr>
	   <td> IdExamen</td>
	   <td> Examen </td>
	   <td> Indicacion </td>
	   </tr>
<?php
$pos=0;
while($fila = mysql_fetch_array($consultadetalle)){
	?>      <tr>
			<td> <?php echo $fila['IdExamen'] ; ?>  </td>
			<td> <?php echo $fila['NombreExamen'] ; ?>  </td>			
			<td> <?php echo $fila['Indicacion'] ; ?>  </td>	
			</tr> 
<?php
$pos=$pos + 1;
}
?>
<input type="hidden" name="oculto" id="oculto" value="<?php echo $pos; ?>" />
</table>
</td>
</tr>
<tr>
<td>
<input type="text" name="oculto" id="oculto"  />
</td>
</tr>
</form>
</body>
</html>
