<?php session_start();
include_once("clsSolicitudesPorArea.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
 $ROOT_PATH = $_SESSION['ROOT_PATH'];
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<?php include_once $ROOT_PATH."/public/css.php";?>
<?php include_once $ROOT_PATH."/public/js.php";?>
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
        
//CargarDatosFormulario(idexpediente,idarea,idsolicitud,idarea,idtipo);
}
</script>

<script language="JavaScript" >
function Procesar()
{
 //alert (idtipo);
 fechasolicitud=document.frmDatos.fechasolicitud.value;
 idsol=document.getElementById('idsol').value;
 rChecked = $('input:checkbox:checked:enabled').length;
 if (rChecked==0){
    alert ('Seleccione al menos una prueba para Procesar.');
    $("#cmbProcesar option[value='0']").attr('selected', 'selected');
    $("#btnProcesar").attr("disabled", "disabled");
    $("#btnRechazar").attr("disabled", "disabled");
    return false;
 }

 ProcesarMuestra(idtipo,idexpediente,idarea,idsol,fechasolicitud);

}

function Rechazar()
{
 //alert (idtipo);
 fechasolicitud=document.frmDatos.fechasolicitud.value;
 observacion=document.frmDatos.txtobservacion.value;
  idsol=document.getElementById('idsol').value;
 rChecked = $('input:checkbox:checked:enabled').length;
 if (rChecked==0){
    alert ('Seleccione al menos una prueba para Rechazar.');
    $("#cmbProcesar option[value='0']").attr('selected', 'selected');
    $("#btnProcesar").attr("disabled", "disabled");
    $("#btnRechazar").attr("disabled", "disabled");
    document.frmDatos.txtobservacion.value="";
    document.getElementById('divObservacion').style.display="none";
    return false;
 }
 RechazarMuestra(idtipo,idexpediente,idarea,idsol,fechasolicitud,observacion);

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
        $subservicio=$_GET['var6'];
	//echo $idexpediente."  ".$idsolicitud."  ".$lugar;
	

	$objdatos = new clsSolicitudesPorArea;
        
      $nombe=$objdatos->nombrepaciente($idsolicitud,$idexpediente);
      $row = pg_fetch_array($nombe);
      $nombrepaciente       = $row['paciente'];
      $nombreexamen= $row['nombreexamen'];
	//recuperando los valores generales de la solicitud
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
		$row = pg_fetch_array($consulta);
		//obteniedo los datos generales de la solicitud
		//valores de las consultas
		//echo $consulta;
	if (pg_num_rows($consulta)>0){
            
          // echo "dentro del if";
            
            
            
        
                
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
        $idsol    = $row['idsol'];
                
                
		//recuperando los valores del detalle de la solicitud
		 $consultadetalle=$objdatos->DatosDetalleSolicitud($idsolicitud, $idarea);
               // echo $consultadetalle;
                ?>
                
	<?php
                $imprimir = "<form name='frmDatos'>
                   <input type='hidden' id='idsol' name='idsol' value='".$idsol. "'/>
                    <table width='85%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td colspan='4' align='center' class='CobaltFieldCaptionTD'>DATOS SOLICITUD</td>
		   	</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Establecimiento</td>
                                <td class='StormyWeatherDataTD' colspan='3'>".$row['estabext']."</td>
			</tr>
                        <tr>
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
				<td class='StormyWeatherDatsec_solicitudestudiosaTD'>$precedencia <input name='txtprecedencia' id='txtprecedencia' 
				type='hidden' size='35' value='" . $precedencia . "' disabled='disabled' /></td>
				<td class='StormyWeatherFieldCaptionTD'>Origen</td>
				<td class='StormyWeatherDataTD'>" . htmlentities($subservicio) . "
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

<!--   <table width='95%' border='0' align='center' class="table table-hover table-bordered table-condensed table-white">
	   <tr>
		<td colspan='4' align='center' ></td>
   --><br>
   <br>
                    <table border = 0 align='center'  width='80%' class="table table-hover table-bordered table-white">
                       <thead>
                          <tr><th colspan="7" style="text-align: center">ESTUDIO SOLICITADO</th></tr>
                       <tr class='CobaltFieldCaptionTD'>
                            <td width="8%"> Validar</td>
                            <td width="6%"> Cód. Examen</td>
			    <td width="44%"> Examen </td>
			    <td width="8%"> No. Muestra </td>
			    <td width="10%"> Tipo Muestra </td>
			    <td width="12%"> Indicaci&oacute;n M&eacute;dica </td>
			    <td width="12%"> F. Toma Muestra </td>
		       </tr>
                       </thead><tbody>
<?php
            $pos=0;
		while($fila = pg_fetch_array($consultadetalle)){?>
			<tr>
                           <td  width="8%"><input type="checkbox" id="valida_detalle" name="valida_detalle<?php echo $fila['iddetalle'];?>" value="<?php echo $fila['iddetalle'];?>" selected/></td>
                            <td  width="6%"><?php echo $fila['estandar']?></td>
                            <td width="44%"><?php echo htmlentities($fila['nombre_examen'])?></td>	
                            <td width="8%"><?php echo htmlentities($fila['numeromuestra'])?></td>	
                            <td width="10%"><?php echo htmlentities($fila['tipomuestra'])?></td>	
			<?php if (!empty($fila['indicacion'])){?>    				
                            <td width="12%"><?php echo htmlentities($fila['indicacion'])?></td>
			
                        <?php }else{?>
                             <td width="12%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
			
                            <?php } ?>
                             <td width="12%"><small><?php echo htmlentities($fila['f_tomamuestra'])?></small></td>	</tr> 
                        <?php
                            
            $pos=$pos + 1;
            }

pg_free_result($consultadetalle);?>

 <input type='hidden' name='oculto' id='oculto' value='<?php echo $pos ?>' />
   </tbody></table>
						
  
</div>
<div id='divimpresion'>
	<table align="center">
		<tr>
			<td colspan="4" align="center">&nbsp;</td>
		</tr>
		<tr>
                   <td colspan="4" align="center"><strong><h4>VALIDACI&Oacute;N DE RECEPCI&Oacute;N DE ESTUDIO</h4></strong>
			</td>
		</tr>
		<tr>
			<td>Procesar Muestra:</td>
                        <td><select id="cmbProcesar" name="cmbProcesar" size="1" onChange="MostrarObservacion();" class="form-control height" >
					<option value="0" >--Seleccione--</option>
					<option value="S" >Si</option>
					<option value="N" >No</option>		
				</select> 
			</td>
			<td colspan="2" >
				
			</td>
		</tr>
	</table>	
</div>	
   <br>
<div id="divObservacion" style="display:none" >
	<table align="center" width="55%">
		<tr>
			<td>Observacion: </td>
			<td colspan="3">
                           <textarea cols="60" rows="2" id="txtobservacion" name="txtobservacion" class="form-control"> </textarea>
			</td>
		</tr>
	</table>
</div>
   <br>
</form>			
<div id="divCambioEstado">
</div>
<div id="divBotones">
<table align="center">
<td>
   <button type="button"  name="btnProcesar" id="btnProcesar" disabled="disabled"  value="Procesar Muestra" class="btn btn-primary" onClick='Procesar()'><span class="glyphicon glyphicon-ok-circle"></span>&nbsp;Procesar Muestra</button>
   <button type="button" name="btnRechazar" id="btnRechazar" disabled="disabled" value="Rechazar Muestra" class="btn btn-primary" onClick='Rechazar()'><span class="glyphicon glyphicon-remove-circle"></span>&nbsp;Rechazar Muestra</button>
   <button type="button" name="btnimprimir" id="btnimprimir"  value="Imprimir" onClick="Imprimir();" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span>&nbsp;Imprimir</button>
   <button type="button" name="btnCerrar" class="btn btn-primary" value="Cerrar" onClick="Cerrar()"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cerrar</button>
</td>	


</table>
   <br>
 <?php
    }else {
     // echo   $consulta;
      //          echo "dentro del else";
            
     echo '<br><br><br><br><img src="../../../Imagenes/indice.jpeg" valign="middle"  border="0" height="60" width="80" />';
     
     echo "<center> <h1> El Resultado Del Examen:<span style='color: #0101DF;'> $nombreexamen</span>, De: <span style='color: #0101DF;'> $nombrepaciente</span>, Ya a Sido Procesado.</h1> ";
            
     
     echo " <button type='submit' class='fg-button ui-state-default ui-corner-all' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' />Cerrar</button></center>";
         
     }
 ?>
    
</div>			
</body>
</html>
