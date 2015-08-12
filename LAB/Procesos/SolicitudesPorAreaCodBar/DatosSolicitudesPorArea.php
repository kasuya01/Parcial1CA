<?php session_start();
include_once("clsSolicitudesPorArea.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
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
	$idsolicitud=$_GET['var3'];

	//echo $idtipo;		
	
	$objdatos = new clsSolicitudesPorArea;
	//recuperando los valores generales de la solicitud
	$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
	$row = mysql_fetch_array($consulta);
		//obteniedo los datos generales de la solicitud
		//valores de las consultas
	$medico=$row['NombreMedico'];
	$idmedico=$row['IdMedico'];
	$paciente=$row['NombrePaciente'];
		//$edad=$row['Edad'];
		//$edad=$objdatos->calcular_edad($row['FechaNacimiento']);
	$sexo=$row['Sexo'];
	$precedencia=$row['Precedencia'];
	$origen=$row['Origen'];
	$FRecepcion=$row['FechaRecepcion'];
        $Peso=$row['Peso'];
        $Talla=$row['Talla'];
        $Diagnostico=$row['Diagnostico'];
  // echo $FRecepcion;
	$Nfecha=explode("-",$FRecepcion);
	//print_r($Nfecha);
        $Nfecharecep=$Nfecha[2]."/".$Nfecha[1]."/".$Nfecha[0];
        $Establecimiento=$row['Nombre'];  
		//$DatosClinicos=$row['DatosClinicos'];
	$fechasolicitud=$row['FechaSolicitud'];
	$muestra=$row['NumeroMuestra'];
        $ConocidoPor=$row['ConocidoPor'];
		//recuperando los valores del detalle de la solicitud
	$consultadetalle=$objdatos->DatosDetalleSolicitud($idarea,$idsolicitud,$idtipo);?>
	<form name='frmDatos' onload='alert("si");'>
		<table width='80%' border='0' align='center' >
			<tr>
				<td colspan='6' align='center' class='CobaltFieldCaptionTD'>DATOS SOLICITUD</td>
			</tr>
                        <tr>
				<td colspan="2">Establecimiento Solicitante:</td>
				<td colspan="4"><?php echo $Establecimiento?></td>
			</tr>
			<tr>
				
				<td>No.Muestra:</td>
				<td><?php echo $muestra?></td>
				<td>No.Orden: </td>
				<td><?php echo$idsolicitud ?></td>
                                <td>Fecha Recepci&oacute;n </td>
                                <td><?php echo $Nfecharecep?></td>
			</tr>
			<tr>
                                
                                <td>Paciente</td>
                                <td colspan="3" ><?php echo htmlentities($paciente)?> </td>
					<input name="suEdad" id="suEdad"  type="hidden" value="<?php echo $row['FechaNacimiento'] ?>"/>	
                                <td >NEC</td>
				<td><?php echo $idexpediente?></td>        
			    
			</tr>
                        <tr>
                              <td>Conocido por: </td>
                              <td colspan="3" ><?php echo htmlentities($ConocidoPor)?> </td>
                        </tr>
			<tr>
				<td>Edad:</td>
				<td colspan="3">
                                    <div id="divsuedad">
                                        <script language="JavaScript" type="text/javascript">
		               			calc_edad();
                                        </script>
                                    </div>
				</td>
				<td>Sexo:</td>
				<td colspan="1"><?php echo $sexo?></td>
			</tr>
			<tr>
			    <td>Procedencia:</td>
			    <td colspan="2"><?php echo $precedencia?></td>
			    <td>Origen:</td>
			    <td colspan="2"><?php echo htmlentities($origen) ?>
					<input name="idsolicitud" id="idsolicitud"  type="hidden" value="<?php $idsolicitud ?>"/>
					<input name="idexpediente" id="idexpediente"  type="hidden" value="<?php $idexpediente ?>"/>
					<input name="fechasolicitud" id="fechasolicitud"  type="hidden" value="<?php $fechasolicitud ?>"/>
					<input name="idarea" id="idarea"  type="hidden" value="<?php $idarea ?>"/>	
					
				</td>
			</tr>
			<tr>
				<td>M&eacute;dico:</td>
				<td colspan="5"><?php echo htmlentities($medico)?></td>
			</tr>
                        <tr>
                        <td>Diagnostico:</td>
                        <td colspan="4"><?php echo htmlentities($Diagnostico); ?></td>
                </tr>
                <tr>
                        <td>Peso:</td>
                 <? if(!empty($peso)){?>
                        <td><?php echo htmlentities($Peso); ?>&nbsp;&nbsp;Kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <?php }else{ ?>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <?php }?>           
                        <td>Talla:</td>
                 <? if(!empty($Talla)){?>
                        <td><?php echo htmlentities($Talla); ?>&nbsp;&nbsp;mts.</td>
              <?php }else{ ?>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
               <?php }?>                 
                        
                </tr>   
		</table>
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
					while($fila = mysql_fetch_array($consultadetalle)){?>
						<tr>
							<td  width="10%"><?php echo $fila['IdExamen']?></td>
							<td width="53%"><?php echo htmlentities($fila['NombreExamen'])?></td>	
                			<td width="14%"><?php echo htmlentities($fila['TipoMuestra'])?></td>	
					<?php if (!empty($fila['Indicacion'])){?>    				
							<td width="23%"><?php echo htmlentities($fila['Indicacion'])?></td>
						</tr>
				   <?php }else{?>
							<td width="23%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
						</tr> 
					<?php }
						$pos=$pos + 1;
					}
					mysql_free_result($consultadetalle);?>

				<input type='hidden' name='oculto' id='oculto' value='<?php echo $pos ?>' />
		</table>

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
				<textarea cols="60" rows="2" id="txtobservacion" name="txtobservacion" > </textarea>
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
