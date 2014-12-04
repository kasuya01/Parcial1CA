<?php session_start();
include_once("clsSolicitudesPorArea.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<style type="text/css">
<!--
@media print{
#divBotones{display:none;}
#divimpresion{display:none;}
}



-->
</style>
<title>Datos del Generales de la Solicitud</title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesPorArea.js"></script>
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
			idarea= getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var3=' )
			idsolicitud = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var4=' )
			idexamen = getVars[i].substr(5);	
		if ( getVars[i].substr(0,5) == 'var5=' )
			idtipo = getVars[i].substr(5);
	}

// CargarDatosFormulario(idexpediente,idsolicitud,idarea,idtipo);
}
</script>

<script language="JavaScript" >
function Procesar()
{
 //alert (idtipo);
 fechasolicitud=document.frmDatos.fechasolicitud.value;
 ProcesarMuestra(idtipo,idexpediente,idarea,idsolicitud,fechasolicitud);

}

function Rechazar()
{
 //alert (idtipo);
 fechasolicitud=document.frmDatos.fechasolicitud.value;
 observacion=document.frmDatos.txtobservacion.value;
 RechazarMuestra(idtipo,idexpediente,idarea,idsolicitud,fechasolicitud,observacion);

}

//Esta funcion mandan a llamar


function calc_edad()
{
  var fecnac1=document.getElementById("suEdad").value;
  var fecnac2=fecnac1.substring(0,10);
//alert (fecnac2);
  var suEdades=calcular_edad(fecnac2);
       
  document.getElementById("divsuedad").innerHTML=suEdades;
}
</script>
</head>

<body onLoad="RecogeValor();">
<div id="divFormulario">
<?php 
	$idexpediente=$_GET['var1'];
	$idarea=$_GET['var2'];
	$idsolicitud=$_GET['var3'];
	$idtipo=$_GET['var5'];
	$idExamen=$_GET['var4'];
	//echo $idexpediente."  ".$idsolicitud."  ".$lugar;
	
	//echo $idtipo;		
	
	$objdatos = new clsSolicitudesPorArea;
	//recuperando los valores generales de la solicitud
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud);
		$row = pg_fetch_array($consulta);
		//obteniedo los datos generales de la solicitud
		//valores de las consultas
		
		
                
        $idsolicitudPadre = $row[0];
        $medico         = $row['medico'];
        $idmedico       = $row[1];
        $paciente       = $row['paciente'];
        $edad           = $row['edad'];
        $sexo           = $row['sexo'];
        $precedencia    = $row['nombreservicio'];
        $origen         = $row['nombresubservicio'];
        //$DatosClinicos=$row['DatosClinicos'];
        $fechasolicitud=$row['fechasolicitud'];
        //$FechaNac=$row['FechaNacimiento'];
        $Talla          = $row['talla'];
        $Peso           = $row['peso'];
        $Diagnostico    = $row['diagnostico'];
        $ConocidoPor    = $row['conocodidox'];
                
                
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idsolicitud);?>
	<?php
                $imprimir = "<form name='frmDatos'>
                    <table width='85%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td colspan='4' align='center' class='CobaltFieldCaptionTD'>DATOS SOLICITUD</td>
		   	</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Establecimiento</td>
                                <td class='StormyWeatherDataTD' colspan='3'>".$row['estabext']."</td>
			</tr>
                        <trr>
				<td class='StormyWeatherFieldCaptionTD'>Paciente</td>
				<td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($paciente) . " 
				     <input name='txtpaciente' id='txtpaciente' type='hidden' size='70' value='" . $paciente . "' disabled='disabled' /></td>
		    	</tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Conocido por</td>
				<td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($ConocidoPor) . " </td>
		    	</tr>
			<tr>
                                <td class='StormyWeatherFieldCaptionTD'>Edad</td>
				<td class='StormyWeatherDataTD'>$edad <input name='txtedad' id='txtedad' 
				   type='hidden' size='35' value='" . $edad . "' disabled='disabled' /></td>
                                       
    				</div></td>
				<td class='StormyWeatherFieldCaptionTD'>Sexo</td>
				<td class='StormyWeatherDataTD'>
				$sexo<input type='hidden' name='txtsexo' value='" . $sexo . "' disabled='disabled' /></td>
		    	</tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Procedencia</td>
				<td class='StormyWeatherDataTD'>$precedencia <input name='txtprecedencia' id='txtprecedencia' 
				type='hidden' size='35' value='" . $precedencia . "' disabled='disabled' /></td>
				<td class='StormyWeatherFieldCaptionTD'>Origen</td>
				<td class='StormyWeatherDataTD'>" . htmlentities($origen) . "
                                    <input name='txtorigen' id='txtorigen'  type='hidden' size='35' value='" . $origen . "' disabled='disabled' />
                                    
                                    <input name='idsolicitudPadre' id='idsolicitudPadre'  type='hidden' size='40' value='" . $idsolicitudPadre . "' disabled='disabled' />
                                    <input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='" . $idsolicitud . "' disabled='disabled' />
                                    <input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='" . $idexpediente . "' disabled='disabled' />
                                    <input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='" . $fechasolicitud . "' disabled='disabled' />
                                    <input name='idarea' id='idarea'  type='hidden' size='40' value='" . $idarea . "' disabled='disabled' />
                                    
				</td>
		        </tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>M&eacute;dico</td>
				<td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($medico) . "
					<input name='txtmedico' id='txtmedico'  type='hidden' size='70' value='" . $medico . "' disabled='disabled' /></td>
		        </tr>
                        <tr>
                                <td class='StormyWeatherFieldCaptionTD'>Diagnostico</td>
                                <td colspan='3' class='StormyWeatherDataTD'>" . $Diagnostico . "</td>
                        </tr>
                        <tr>
                                <td class='StormyWeatherFieldCaptionTD'>Peso</td>";
                 
        if (!empty($Peso))
            $imprimir .= "<td class='StormyWeatherDataTD'>" . $Peso . "&nbsp;&nbsp;Kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        else
            $imprimir .= "<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $imprimir .="<td class='StormyWeatherFieldCaptionTD'>Talla</td>";
        if (!empty($Talla))
            $imprimir .="<td class='StormyWeatherDataTD'>" . $Talla . "&nbsp;&nbsp;mts.</td>";
        else
            $imprimir .= "<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
       
		
            $imprimir.="</table>";
            
            echo $imprimir;
        ?>

	   <table width='95%' border='0' align='center'>
	   <tr>
		<td colspan='4' align='center' >ESTUDIO SOLICITADO</td>
	   </tr>
	   <tr>
                 <td>
		    <table border = 1 align='center'  width='85%'>
                       <tr class='CobaltFieldCaptionTD'>
                            <td width="10%"> IdExamen</td>
			    <td width="53%"> Examen </td>
			    <td width="14%"> Tipo Muestra </td>
			    <td width="23%"> Indicaci&oacute;n M&eacute;dica </td>
		       </tr>
<?php
            $pos=0;
		while($fila = pg_fetch_array($consultadetalle)){?>
			<tr>
                            <td  width="10%"><?php echo $fila[0]?></td>
                            <td width="53%"><?php echo htmlentities($fila[1])?></td>	
                            <td width="14%"><?php echo htmlentities($fila[2])?></td>	
			<?php if (!empty($fila[3])){?>    				
                            <td width="23%"><?php echo htmlentities($fila[3])?></td>
			</tr>
                        <?php }else{?>
                             <td width="23%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr> 
                            <?php }
            $pos=$pos + 1;
            }

pg_free_result($consultadetalle);?>

 <input type='hidden' name='oculto' id='oculto' value='<?php echo $pos ?>' />
			</table>
						
  
</div>
<div id='divimpresion'>
	<table align="center">
		<tr>
			<td colspan="4" align="center">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" align="center">VALIDACI&Oacute;N DE RECEPCI&Oacute;N DE ESTUDIO
			</td>
		</tr>
		<tr>
			<td>Procesar Muestra</td>
			<td><select id="cmbProcesar" name="cmbProcesar" size="1" onChange="MostrarObservacion();" >
					<option value="0" >--Seleccione--</option>
					<option value="S" >Si</option>
					<option value="N" >No</option>		
				</select> 
			</td>
			<td colspan="2" >
				<input type="button" name="btnProcesar" id="btnProcesar" disabled="disabled"  value="Procesar Muestra" onClick='Procesar()'>
				<input type="button" name="btnRechazar" id="btnRechazar" disabled="disabled" value="Rechazar Muestra" onClick='Rechazar()'>
			</td>
		</tr>
	</table>	
</div>	
<div id="divObservacion" style="display:none" >
	<table align="center" width="55%">
		<tr>
			<td>Observacion</td>
			<td colspan="3">
				<textarea cols="60" rows="2" id="txtobservacion" name="txtobservacion" <span style="color: #0000FF;background-color:#87CEEB;"> </textarea>
			</td>
		</tr>
	</table>
</div>
</form>			
<div id="divCambioEstado">
</div>
<div id="divBotones">
<table align="center">
<td>
	<input type="button" name="btnimprimir" id="btnimprimir"  value="Imprimir" onClick="Imprimir();">
	<input type="button" name="btnCerrar"  value="Cerrar" onClick="Cerrar()">
</td>			
</table>			
</div>			
</body>
</html>
