<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
?>
<html>
<head> 
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
<title>Resultados de Ex&aacute;menes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<script language="JavaScript" >
function Guardar(){
   	IngresarRegistro();
}
function LlenarComboExamen(idArea)
{
  LlenarExamenes(idArea);
}
function Buscar(){
	BuscarDatos();
}
function Actualizar(){
	enviarDatos();
}
function Nuevo(){
	MostraFormularioNuevo();
}

function salir()
 { Cerrar(); }


function CargarDatos()
{
  var vtmp=location.search;
	var vtmp2 = vtmp.substring(1,vtmp.length);
	//alert(vtmp2);
	var query = unescape(top.location.search.substring(1));
	var getVars = query.split(/&/);
	for ( i = 0; i < getVars.length; i++)
	{         if ( getVars[i].substr(0,5) == 'var1=' )//loops through this array and extract each name and value
	                        nec = getVars[i].substr(5);
			  if ( getVars[i].substr(0,5) == 'var2=' )
							examen = getVars[i].substr(5);
			  if ( getVars[i].substr(0,5) == 'var3=' )
							codigoex = getVars[i].substr(5);
			  if ( getVars[i].substr(0,5) == 'var4=' )
							area = getVars[i].substr(5);
			  if ( getVars[i].substr(0,5) == 'var5=' )
							iddetallesol = getVars[i].substr(5);
			  if ( getVars[i].substr(0,5) == 'var6=' )
						    idsolicitudsol= getVars[i].substr(5);	
			  if ( getVars[i].substr(0,5) == 'var7=' )
						    paciente= getVars[i].substr(5);
			  if ( getVars[i].substr(0,5) == 'var8=' )
						    idrecepcionsol = getVars[i].substr(5);
			  if ( getVars[i].substr(0,5) == 'var9=' )
					    nombrearea= getVars[i].substr(5);
			  if ( getVars[i].substr(0,5) == 'var10=' )
					    procedencia=escape(getVars[i].substr(5));
		     if ( getVars[i].substr(0,5) == 'var11=' )
					    origen=escape(getVars[i].substr(5));	
			 if ( getVars[i].substr(0,5) == 'var12=' )
					    impresion=escape(getVars[i].substr(5));
			  if ( getVars[i].substr(0,5) == 'var13=' )
					    establecimiento=escape(getVars[i].substr(5));
	}
	document.frmnuevo.txtnec.value=nec;
	document.frmnuevo.txtarea.value=area;
	document.frmnuevo.txtpaciente.value=paciente;
	document.frmnuevo.txtexamen.value=examen;
	document.frmnuevo.txtidsolicitud.value=idsolicitudsol;
	document.frmnuevo.txtiddetalle.value=iddetallesol;
	document.frmnuevo.txtidexamen.value=codigoex;
	document.frmnuevo.txtidrecepcion.value=idrecepcionsol;
	//document.frmnuevo.txtnombrearea.value=nombrearea;
	nombrearea=escape(document.frmnuevo.txtnombrearea.value=nombrearea);
	LlenarComboResponsable(area);
}
<?php   
 
	$bandera=$_GET['var12'];				    
?>
</script>
<?php 
//$nombrearea=nombrearea;?>
</head>

<body onLoad="CargarDatos();">
<table align="center" width="100%">
<tr>
	<td>
	<div  id="divFrmNuevo" style="display:block" >
	<form name="frmnuevo">
	<table width="70%" border="0" align="center" class="StormyWeatherFormTABLE" >
		<tr class="CobaltButton">
			<td colspan="5" align="center"> <h3>DATOS GENERALES</h3></td>
		</tr>
		<tr>
			<td class="StormyWeatherFieldCaptionTD" colspan="1">Establecimiento</td>
			<td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var13'];?></td>
		</tr>
		<tr>
			<td class="StormyWeatherFieldCaptionTD" colspan="1">Procedencia</td>
		  	<td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var10'] ?></td>
		</tr>
		<tr>
			<td class="StormyWeatherFieldCaptionTD" colspan="1">Servicio</td>
		  	<td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var11'] ?></td>
		</tr>
		<tr>
			<td colspan="1" class="StormyWeatherFieldCaptionTD">NEC</td>
		   	<td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var1'] ?>
				<input type="hidden" name="txtnec" id="txtnec" disabled="disabled" />
				<input type="hidden" name="txtidsolicitud" id="txtidsolicitud" />
				<input type="hidden" name="txtiddetalle" id="txtiddetalle" />
				<input type="hidden" name="txtidexamen" id="txtidexamen" />
				<input type="hidden" name="txtidrecepcion" id="txtidrecepcion" />
				<input type="hidden" name="txtarea" id="txtarea" />
				<input type="hidden" name="txtprocedencia" id="txtprocedencia" />
				<input type="hidden" name="txtorigen" id="txtorigen" />	
				<input type="hidden" name="txtEstablecimiento" id="txtEstablecimiento" value="<?php echo $_GET['var13']?>" />
			</td>
			
		</tr>
		<tr>
			<td colspan="1" class="StormyWeatherFieldCaptionTD">Paciente</td>
			<td colspan="4" class="StormyWeatherDataTD" ><?php echo $_GET['var7'] ?>
				<input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" /></td>
		</tr>
		<tr>
			<td colspan="1" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
            		<td width="83%" class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var9'] ?>
			  <input type="hidden" name="txtnombrearea" id="txtnombrearea" disabled="disabled">
			</td>
        	</tr>
        	<tr>
            		<td colspan="1" width="17%" class="StormyWeatherFieldCaptionTD">Examen</td>
            		<td width="83%" class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var2'] ?>
            			<input type="hidden" name="txtexamen" id="txtexamen" disabled="disabled" size="60" /></td>
        	</tr>
		<tr>
			<td colspan="1" width="45%" class="StormyWeatherFieldCaptionTD">*Validado Por</td>
            		<td width="50%" class="StormyWeatherDataTD" colspan="4">
				<div id="divEncargado">
					<select id="cmbEmpleados" name="cmbEmpleados" size="1" >
						<option value="0" >--Seleccione Empleado--</option>
				   	</select>
				</div>
			</td>
			
        	</tr>   
        	<tr>
            		<td width="17%" class="StormyWeatherFieldCaptionTD">Resultado</td>
            		<td width="83%" class="StormyWeatherDataTD" colspan="4"><select id="cmbResultado" name="cmbResultado" size="1" onChange="LlenarObservaciones();" >
                		<option value="0">--Seleccione--</option>
                		<option value="P">Positivo</option>
				<option value="N">Negativo</option>
				<option value="O">Otro</option>
                		</select>
			</td>
        	</tr>
	
		<tr><td colspan="5" class="StormyWeatherDataTD">
			<div id="divObservacion" style="display:none">
				<table class="StormyWeatherFormTABLE" width="100%" >
					<tr>
						<td width="35%" class="StormyWeatherFieldCaptionTD">Observaci&oacute;n</td>
						<td width="55%" class="StormyWeatherDataTD" >
							<select id="cmbObservacion" name="cmbObservacion" size="1" >
							    <option value="0" >--Seleccione Observaci&oacute;n--</option>
							</select>
						</td>
					</tr>
				</table>
			</div></td>
			
		</tr>
		<tr width="17%" class="StormyWeatherDataTD">
			<td colspan="5">
		        	<div id="divResPositivo" style="display:none" >
		          		<table class="StormyWeatherFormTABLE" width="100%" >
						<tr>
							<td width="38%" class="StormyWeatherFieldCaptionTD">Organismos</td>
							<td width="62%" class="StormyWeatherDataTD"><select id="cmbOrganismo" name="cmbOrganismo" size="1"  >
								<option value="0" >--Seleccione una bacteria--</option>
							<?php
								include('clsConsultarElementosPlantillaC.php');
								$obj=new clsConsultarElementosPlantillaC;
								$consulta_bac= $obj->LeerBacterias();
								while($row = mysql_fetch_array($consulta_bac)){
								echo "<option value='" . $row[0]. "'>" . $row[1] . "</option>";
								}
								mysql_free_result($consulta_bac);
							?>	
							</select>			
							</td>
						</tr>
						<tr>
							<td width="17%" class="StormyWeatherFieldCaptionTD">Tarjeta</td>
							<td width="83%" class="StormyWeatherDataTD"><select id="cmbTarjeta" name="cmbTarjeta" size="1" onChange="MostrarAntibioticos();">
								<option value="0" >-- Seleccione una tarjeta --</option>
								<?php
									$obj=new clsConsultarElementosPlantillaC;
									$consulta= $obj->LeerTarjeta($lugar);
									while($row = mysql_fetch_array($consulta)){
									echo "<option value='" . $row['IdTarjeta']. "'>" . $row['NombreTarjeta'] . "</option>";
									}
									mysql_free_result($consulta);
								?>
								</select>
							</td>
						</tr>
				  	</table>
			    	</div>
			</td>
		</tr>
	 <?php 
	if ($bandera==1){
	?>
		<tr>
			<td colspan="5"  class="StormyWeatherDataTD" align="center" style="color:#DD0000; font:bold"><h3>El m&eacute;dico ha solicitado la impresi&oacute;n de este Resultado </h3></td>
		</tr>
		  <?php 
	}?>
		<tr class="StormyWeatherDataTD">
			<td height="100%" colspan="6" class="StormyWeatherDataTD" >
				<div id="divBotonPrevie" style="display:none">
					<table width="100%" height="100%" >
						<td  align="right"><input type="button" name="Submit" value="Mostrar Datos Previos" Onclick="PreviosNegativos() ;"> </td>
					</table>
				</div>
		 
			</td>
		</tr>
	</table>
	</form>
	</div>
	</td>
 </tr>
 <tr>
	<td>
     <div  id="divexamen" style="display:none" >
    </div>
    <div  id="divresultado" style="display:none">
    </div>
    </td>
 </tr>
</table>
</body>
</html>
