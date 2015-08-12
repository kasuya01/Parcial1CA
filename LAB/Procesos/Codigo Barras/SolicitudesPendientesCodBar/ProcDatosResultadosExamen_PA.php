<?php session_start();
include_once("../../../Conexion/ConexionBD.php"); 
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
   	GuardarResultados();
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

function VerResultados()
{
    if (ValidarCampos())
    {
	idexamen=document.frmnuevo.txtidexamen.value;
	idsolicitud=document.frmnuevo.txtidsolicitud.value;
    	iddetalle=document.frmnuevo.txtiddetalle.value;
	idarea=document.frmnuevo.txtarea.value;
	resultado=document.frmnuevo.txtresultado.value;
	lectura=document.getElementById('txtlectura').value;
	interpretacion=document.getElementById('txtinterpretacion').value;
	observacion=document.frmnuevo.txtcomentario.value;
	responsable=document.frmnuevo.cmbEmpleados.value;
	nombrearea=document.frmnuevo.txtnombrearea.value;
	procedencia=document.frmnuevo.txtprocedencia.value;
	origen=document.frmnuevo.txtorigen.value;
	impresion=document.frmnuevo.txtimpresion.value;
        establecimiento=document.frmnuevo.txtEstablecimiento.value;
	codresult=document.frmnuevo.cmbResultado2.value;
	//alert(codresult);
	MostrarResultadoExamen(idsolicitud,iddetalle,idarea,idexamen,resultado,lectura,interpretacion,observacion,responsable,nombrearea,procedencia,origen,impresion,establecimiento,codresult);
	
    }else
    {    alert("Complete la Informacion Requerida");   }
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
		        establecimiento=escape(getVars[i].substr(5));
		}
document.frmnuevo.txtnec.value=nec;
document.frmnuevo.txtarea.value=area;
document.frmnuevo.txtpaciente.value=paciente;
document.frmnuevo.txtexamen.value=examen;
document.frmnuevo.txtidsolicitud.value=idsolicitud;
document.frmnuevo.txtiddetalle.value=iddetalle;
document.frmnuevo.txtidexamen.value=idexamen;
document.frmnuevo.txtidrecepcion.value=idrecepcion;
document.frmnuevo.txtnombrearea.value=nombrearea;

LlenarComboResponsable(area);
//alert(area);
}

//FUNCION PARA VERIFICAR DATOS REQUERIDOS EN RESULTADOS
<?php  

$bandera=$_GET['var12'];

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
			 	<input type="hidden" name="txtidexamen" id="txtidexamen" />
			 	<input type="hidden" name="txtidrecepcion" id="txtidrecepcion" />
			 	<input type="hidden" name="txtarea" id="txtarea" />
			 	<input type="hidden" name="txtprocedencia" id="txtprocedencia" />
			 	<input type="hidden" name="txtorigen" id="txtorigen" />
			 	<input type="hidden" name="txtimpresion" id="txtimpresion" value="<?php echo $_GET['var12']?>"/>
			<input type="hidden" name="txtEstablecimiento" id="txtEstablecimiento" value="<?php echo $_GET['var13']?>" />
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
            		<td width="17%" class="StormyWeatherFieldCaptionTD">Examen </td>
            		<td width="83%" class="StormyWeatherDataTD"> <?php echo htmlentities($_GET['var2']);?>
            			<input type="hidden" name="txtexamen" id="txtexamen" disabled="disabled" size="60"  />
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
		        <td width="17%" class="StormyWeatherFieldCaptionTD">*Resultado</td>
                	<td width="83%" class="StormyWeatherDataTD">
				 <input type="text" name="txtresultado" id="txtresultado"/>
			</td>
           	</tr>
		<tr>
                	<td width="17%" class="StormyWeatherFieldCaptionTD">Lectura</td>
                 	<td width="83%" class="StormyWeatherDataTD"><textarea name="txtlectura" cols="50" id="txtlectura"></textarea></td>
           	</tr>
		<tr>
			<td width="17%" class="StormyWeatherFieldCaptionTD">Interpretaci&oacute;n</td>
		     	<td width="83%" class="StormyWeatherDataTD"><textarea name="txtinterpretacion" cols="50" id="txtinterpretacion"></textarea></td>
	        </tr>
		<tr>
           		<td width="17%" class="StormyWeatherFieldCaptionTD">Observaci&oacute;n</td>
           		<td width="83%" class="StormyWeatherDataTD"><textarea name="txtcomentario" cols="50" id="txtcomentario"></textarea></td>
        </tr>
		<tr>
		        <td  class="StormyWeatherFieldCaptionTD">*Resultado Tabulador</td>
                <td width="65%" class="StormyWeatherDataTD">
						<select id="cmbResultado2" name="cmbResultado2" size="1">
							<option value="0" >--Seleccione Resultado--</option>
							<?php 
                                  	$db = new ConexionBD;
								if($db->conectar()==true){
									$consulta = "SELECT * FROM lab_codigosresultados";
									$resultado = mysql_query($consulta);
									while ($rows = mysql_fetch_array($resultado)){
										echo '<option value="' . $rows[0] . '">' . $rows[0] . '  -  ' . $rows[1] . '</option>'; 
									}
//or die('La consulta fall&oacute;: ' mysql_error());
								}
							?>
						</select>
			   </td>
        </tr>
		<tr>
			<?php 
			if ($bandera==1){
		 	 ?>
		</tr>
			<td colspan="2" align="center" class="StormyWeatherDataTD" style="color:#DD0000; font:bold"><h3>El m&eacute;dico ha solicitado la impresi&oacute;n de este Resultado </h3></td>
			<tr>
			  <?php 
		  	}?>
            		<td colspan="2">
				<input type="button" name="Submit" value="Vista Previa Resultados" Onclick="VerResultados() ;"> 
            		</td>
          	</tr>
  	</table>
</form>
</div></td>
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
