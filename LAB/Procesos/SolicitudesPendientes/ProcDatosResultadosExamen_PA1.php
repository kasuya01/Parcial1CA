<?php session_start();
include_once("../../../Conexion/ConexionBD.php");
$db = new ConexionBD;
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">  
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
		        FechaNac=escape(getVars[i].substr(5)); 
                  if ( getVars[i].substr(0,5) == 'var15=' )
                        Sexo=escape(getVars[i].substr(5));   
                  if ( getVars[i].substr(0,5) == 'var16=' )
                        IdEstandar=escape(getVars[i].substr(5));   
                  if ( getVars[i].substr(0,5) == 'var17=' )
                        IdHistorial=escape(getVars[i].substr(5));   
                  
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
$IdEstandar=$_GET['var16'];
$IdHistorial=$_GET['var17'];
  if($db->conectar()==true){
        $condatos = "SELECT sec_examenfisico.Peso, sec_examenfisico.Talla, Diagnostico, ConocidoPor
                   FROM sec_historial_clinico
                   INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp = mnt_expediente.IdNumeroExp
                   INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente = mnt_datospaciente.IdPaciente
                   LEFT JOIN sec_diagnosticospaciente ON sec_historial_clinico.IdHistorialClinico = sec_diagnosticospaciente.IdHistorialClinico
                   LEFT JOIN mnt_cie10 ON sec_diagnosticospaciente.IdDiagnostico1 = mnt_cie10.IdCie10
                   LEFT JOIN sec_examenfisico ON sec_historial_clinico.IdHistorialClinico = sec_examenfisico.IdHistorialClinico
                   WHERE sec_historial_clinico.IdHistorialClinico=$IdHistorial
                   AND sec_historial_clinico.IdEstablecimiento =$lugar";

        $resultado = mysql_query($condatos);
	$rows = mysql_fetch_array($resultado);
        
        $Peso=$rows['Peso'];
        $Talla=$rows['Talla'];
        $Diagnostico=$rows['Diagnostico'];
        $ConocidoPor=$rows['ConocidoPor'];
        //echo $Peso." * ".$Talla." * ".$Diagnostico." * ".$ConocidoPor;
  }
//$usuario=$_SESSION['correlativo'];
//$IdEstandar=$_GET['var16'];
//$IdHistorial=$_GET['var17'];
//echo $examen;
?>
</script>
</head>

<body onLoad="RecogeValor();">
<table align="center" width="100%">
<tr>
	<td>
		<div  id="divFrmNuevo" style="display:block" >
		<form name="frmnuevo" method="get" action="ProcDatosResultadosExamen_PA.php" enctype="multipart/form-data">
			<table width="70%" border="0" align="center" class="StormyWeatherFormTABLE">
				<tr>
					<td colspan="4" align="center" class="CobaltFieldCaptionTD"><h3>INGRESO DE RESULTADOS</h3></td>
				</tr>
				<tr>
					<td class="StormyWeatherFieldCaptionTD">Establecimiento Solicitante</td>
		  			<td colspan="3" class="StormyWeatherDataTD"><?php echo $_GET['var13'];?></td>
				</tr>
				<tr>
					<td class="StormyWeatherFieldCaptionTD">Procedencia</td>
					<td  colspan="3" class="StormyWeatherDataTD"><?php echo $_GET['var10'];?></td>
				</tr>
				<tr>
					<td class="StormyWeatherFieldCaptionTD">Origen</td>
					<td colspan="3" class="StormyWeatherDataTD"><?php echo $_GET['var11'];?></td>
				</tr>
				<tr>
					<td class="StormyWeatherFieldCaptionTD">NEC</td>
					<td class="StormyWeatherDataTD"><?php echo $_GET['var1'];?></td>
					<td class="StormyWeatherFieldCaptionTD">No. Order</td>
					<td class="StormyWeatherDataTD"><?php echo $_GET['var6'];?>
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
						<input type="hidden" name="txtFechaNac" id="txtFechaNac" value="<?php echo $_GET['var14']?>" />
                                                <input type="hidden" name="txtSexo" id="txtSexo" value="<?php echo $_GET['var15']?>" />
                                                <input type="hidden" name="txtIdEstandar" id="txtIdEstandar" value="<?php echo $_GET['var16']?>" />
                                                <input type="hidden" name="txtIdHistorial" id="txtIdHistorial" value="<?php echo $_GET['var17']?>" />
				</tr>
                                
				<tr>
					<td class="StormyWeatherFieldCaptionTD">Paciente</td>
					<td colspan="3" class="StormyWeatherDataTD"><?php echo htmlentities($_GET['var7']);?>
						<input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
						</td>
				</tr>
                                <tr>
                                        <td class="StormyWeatherFieldCaptionTD">Conocido Por</td>
                                        <td colspan="3" class="StormyWeatherDataTD"><?php 
                                            if(!empty($ConocidoPor))
                                                echo htmlentities($ConocidoPor);?>
                                            <input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
                                        </td>
                                </tr>
                                <tr>
                                        <td class="StormyWeatherFieldCaptionTD">Diagnostico</td>
                                        <td colspan="3" class="StormyWeatherDataTD"><?php 
                                            if(!empty($Diagnostico))
                                                echo htmlentities($Diagnostico);?>
                                            <input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
                                        </td>
                                </tr>
                                <tr>
                                        <td class="StormyWeatherFieldCaptionTD">Peso</td>
                                        <td class="StormyWeatherDataTD"><?php 
                                                if (!empty($Peso))
                                                    echo htmlentities($Peso)." Kg";?></td>
                                        <td class="StormyWeatherFieldCaptionTD">Talla</td>
                                        <td class="StormyWeatherDataTD"><?php 
                                                if(!empty($Talla))
                                                    echo htmlentities($Talla)." cm";?></td>
                                </tr>
				<tr>
                                        <td  class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
                                        <td colspan="3" class="StormyWeatherDataTD"> <?php echo htmlentities($_GET['var9']) ;?>
                                               <input type="hidden" name="txtnombrearea" id="txtnombrearea" disabled="disabled" size="60" />
                                        </td>
                                </tr>
                                <tr>
                                        <td class="StormyWeatherFieldCaptionTD">*Validado Por</td>
                                        <td colspan="3" class="StormyWeatherDataTD">
                                              <div id="divEncargado">
                                                    <select id="cmbEmpleados" name="cmbEmpleados" size="1">
                                                             <option value="0" >--Seleccione Empleado--</option>
                                                    </select>
                                              </div>
                                        </td>
                                </tr>
                                <tr>	 
                                        <td colspan="4" class="StormyWeatherDataTD">
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
