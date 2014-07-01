<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script> 
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<script language="JavaScript" >
function Guardar(){
   	GuardarResultadosPlantillaA();
	//window.close();
}
function ValidarCampos()
{
  var resp = true;
	 if (document.frmnuevo.cmbEmpleados.value == "0")
		 {
			resp= false;		
		 }
	 if (document.frmnuevo.txtresultado.value == "")
		 {
			resp= false;		
		 }
     
  return resp;
}


function RecogeValor()
{
var vtmp=location.search;
var vtmp2 = vtmp.substring(1,vtmp.length);
var query = unescape(top.location.search.substring(1));
var getVars = query.split(/&/);
for ( i = 0; i < getVars.length; i++)
		{
			if ( getVars[i].substr(0,5) == 'var1=' )//loops through this array and extract each name and value
                        nec = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var2=' )
						examen = getVars[i].substr(5);
			 if ( getVars[i].substr(0,5) == 'var3=' )
					    idexamen= getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var4=' )
						area = getVars[i].substr(5);
			 if ( getVars[i].substr(0,5) == 'var5=' )
						iddetalle = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var6=' )
					    idsolicitud= getVars[i].substr(5);		
			if ( getVars[i].substr(0,5) == 'var7=' )
						paciente = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var8=' )
					    idrecepcion= getVars[i].substr(5);
		  if ( getVars[i].substr(0,5) == 'var9=' )
					    nombrearea=escape(getVars[i].substr(5));
		  if ( getVars[i].substr(0,5) == 'var10=' )
					    procedencia=escape(getVars[i].substr(5));
		  if ( getVars[i].substr(0,5) == 'var11=' )
					    origen=escape(getVars[i].substr(5));
		  if ( getVars[i].substr(0,5) == 'var12=' )
					    impresion=escape(getVars[i].substr(5));
		  if ( getVars[i].substr(0,5) == 'var13=' )
					    estab=escape(getVars[i].substr(5));
		  if ( getVars[i].substr(0,5) == 'var14=' )
					    idhistorial=escape(getVars[i].substr(5));
		}
document.frmnuevo.txtnec.value=nec;
document.frmnuevo.txtarea.value=area;
document.frmnuevo.txtpaciente.value=paciente;
//document.frmnuevo.txtexamen.value=examen;
document.frmnuevo.txtidsolicitud.value=idsolicitud;
document.frmnuevo.txtiddetalle.value=iddetalle;
//document.frmnuevo.txtidexamen.value=idexamen;
document.frmnuevo.txtidrecepcion.value=idrecepcion;
document.frmnuevo.txtnombrearea.value=nombrearea;

LlenarComboResponsable(area);
//alert(area);
}

//FUNCION PARA VERIFICAR DATOS REQUERIDOS EN RESULTADOS
<?php  
//$nombrearea=$_GET['var9'];
$examen=$_GET['var2'];
$bandera=$_GET['var12'];
//$usuario=$_SESSION['correlativo'];
?>
</script>
</head>

<body onLoad="RecogeValor();">
<table align="center" width="100%">
<tr>
	<td>
		<div  id="divFrmNuevo" style="display:block" >
		<form name="frmnuevo" method="get" action="ProcDatosResultadosExamen_PA.php" enctype="multipart/form-data">
			<table width="60%" border="0" align="center" class="StormyWeatherFormTABLE">
				<tr>
					<td colspan="2" align="center" class="CobaltFieldCaptionTD"><h3>INGRESO DE RESULTADOS</h3></td>
				</tr>
				<tr>
					<td class="StormyWeatherFieldCaptionTD">Establecimiento</td>
		  			<td class="StormyWeatherDataTD"><?php echo $_GET['var13'];?></td>
				</tr>
				<tr>
					<td class="StormyWeatherFieldCaptionTD">Procedencia</td>
					<td class="StormyWeatherDataTD"><?php echo $_GET['var10'];?></td>
				</tr>
				<tr>
					<td class="StormyWeatherFieldCaptionTD">Origen</td>
					<td class="StormyWeatherDataTD"><?php echo $_GET['var11'];?></td>
				</tr>
				<tr>
					<td width="17%" class="StormyWeatherFieldCaptionTD">NEC</td>
					<td width="83%" class="StormyWeatherDataTD"><?php echo $_GET['var1'];?>
						<input type="hidden" name="txtnec" id="txtnec" disabled="disabled" />
						<input type="hidden" name="txtidsolicitud" id="txtidsolicitud" />
						<input type="hidden" name="txtiddetalle" id="txtiddetalle" />
						<!--<input type="hidden" name="txtidexamen" id="txtidexamen" />-->
						<input type="hidden" name="txtidrecepcion" id="txtidrecepcion" />
						<input type="hidden" name="txtarea" id="txtarea" />
						<input type="hidden" name="txtprocedencia" id="txtprocedencia" />
						<input type="hidden" name="txtorigen" id="txtorigen" />
						<input type="hidden" name="txtimpresion" id="txtimpresion" />
						<input type="hidden" name="txtEstablecimiento" id="txtEstablecimiento" value="<?php echo $_GET['var13']?>" />	
						<input type="hidden" name="txtidhistorial" id="txtidhistorial" value="<?php echo$_GET['var14']?>" />
					</td>
				</tr>
				<tr>
					<td width="17%" class="StormyWeatherFieldCaptionTD">Paciente</td>
					<td width="83%" class="StormyWeatherDataTD"><?php echo htmlentities($_GET['var7']);?>
						<input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
						</td>
				</tr>
				<tr>
					<td width="17%" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
					<td width="83%" class="StormyWeatherDataTD"> <?php echo htmlentities($_GET['var9']) ;?>
						<input type="hidden" name="txtnombrearea" id="txtnombrearea" disabled="disabled" size="60" />
						</td>
				</tr>
				
				<tr>
					<td width="17%" class="StormyWeatherFieldCaptionTD">*Validado Por</td>
					<td width="83%" class="StormyWeatherDataTD">
						<div id="divEncargado">
							<select id="cmbEmpleados" name="cmbEmpleados" size="1">
								<option value="0" >--Seleccione Empleado--</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>	 
					<td colspan="2">
						<input type="button" name="Submit" value="Ingresar Resultados" Onclick="IngresarTodosResultados() ;"> 
					</td>
				</tr>
			</table>
		</form>
		</div>
	</td>
</tr>
<tr>
	<td>
		<div  id="divexamen" style="display:none">

		</div>
	</td>
</tr>
<tr>
	<td>
		<div  id="divresultado" style="display:none"></div></td>
</tr>
<tr>
	<td>
	</td>
</tr>
</table>
</body>
</html>
