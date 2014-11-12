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
<style type="text/css">
			*{ font-size:12px; font-family:verdana; }
			h1 { font-size:22px; }
			input { width:250px; border: 2px solid #CCC; line-height:20px;height:20px; border-radius:3px; padding:2px; }
		</style>
<link type="text/css" href="../../../public/jquery-ui-1.10.3.custom/css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
<link type="text/css" href="../../../public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />

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
document.frmnuevo.referido.value=referido;
//LlenarComboMetodologia(idexamen, area);
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
$referido=$_GET['referido'];
if (!$IdHistorial){
    
$condatos=$objdatos->condatos($IdHistorial, $lugar);

/*

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

        $resultado = mysql_query($condatos);*/
	$rows = pg_fetch_array($resultado);
        
        $Peso=$rows['Peso'];
        $Talla=$rows['Talla'];
        $Diagnostico=$rows['Diagnostico'];
        $ConocidoPor=$rows['ConocidoPor'];
  }
  else{
      $Peso='-';
      $Talla='-';
      $Diagnostico='-';
      $ConocidoPor='-';
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
						<input type="hidden" name="txtprocedencia" id="txtprocedencia" value="<?php echo $_GET['var10']?>" />
						<input type="hidden" name="txtorigen" id="txtorigen" value="<?php echo $_GET['var11']?>"/>
						<input type="hidden" name="txtimpresion" id="txtimpresion" />
						<input type="hidden" name="txtEstablecimiento" id="txtEstablecimiento" value="<?php echo $_GET['var13']?>" />	
						<input type="hidden" name="txtFechaNac" id="txtFechaNac" value="<?php echo $_GET['var14']?>" />
                                                <input type="hidden" name="txtSexo" id="txtSexo" value="<?php echo $_GET['var15']?>" />
                                                <input type="hidden" name="txtIdEstandar" id="txtIdEstandar" value="<?php echo $_GET['var16']?>" />
                                                <input type="hidden" name="txtIdHistorial" id="txtIdHistorial" value="<?php echo $_GET['var17']?>" />
                                                <input type="hidden" name="referido" id="referido" value="<?php echo $_GET['referido']?>" />
				</tr>
                                
				<tr>
					<td class="StormyWeatherFieldCaptionTD">Paciente</td>
					<td colspan="3" class="StormyWeatherDataTD"><?php echo $_GET['var7'];?>
						<input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
						</td>
				</tr>
                                <tr>
                                        <td class="StormyWeatherFieldCaptionTD">Conocido Por</td>
                                         <td colspan="3" class="StormyWeatherDataTD"><?php echo $ConocidoPor;?>
                            <input type="hidden" id="conocido_por" name="conocido_por" value="<?php echo $ConocidoPor;?>">
                                        </td>
                                </tr>
                                <tr>
                                <td class="StormyWeatherFieldCaptionTD">Procedencia</td>
                                <td class="StormyWeatherDataTD"><?php echo $_GET['var10'];?></td>
                                <td class="StormyWeatherFieldCaptionTD">Servicio</td>
                                <td class="StormyWeatherDataTD"><?php echo $_GET['var11'];?></td>
                                </tr>
				<tr>
                                        <td  class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
                                        <td colspan="3" class="StormyWeatherDataTD"> <?php echo htmlentities($_GET['var9']) ;?>
                                               <input type="hidden" name="txtnombrearea" id="txtnombrearea" disabled="disabled" size="60" />
                                        </td>
                                </tr>
                                <tr>
                                        <td class="StormyWeatherFieldCaptionTD">Diagnostico</td>
                                        <td colspan="3" class="StormyWeatherDataTD"><?php 
                                            if(!empty($Diagnostico))
                                                echo $Diagnostico;?>                                            
                                        </td>
                                </tr>
                                <tr>
                                        <td class="StormyWeatherFieldCaptionTD">Peso</td>
                                        <td class="StormyWeatherDataTD"><?php 
                                                if (!empty($Peso))
                                                    echo $Peso." Kg";?></td>
                                        <td class="StormyWeatherFieldCaptionTD">Talla</td>
                                        <td class="StormyWeatherDataTD"><?php 
                                                if(!empty($Talla))
                                                    echo $Talla." cm";?></td>
                                </tr>
                                <tr>
                            <td class="StormyWeatherFieldCaptionTD">*Fecha Realización </td>
                            <td  colspan="1" class="StormyWeatherDataTD"> 
                                <input type="text" class="datepicker" name="fecha_realizacion" id="fecha_realizacion" size="60"  placeholder="aaaa-mm-dd" />
                            </td>
                             <td class="StormyWeatherFieldCaptionTD" width="196 px">*Fecha Reporte </td>
                            <td  colspan="1" class="StormyWeatherDataTD"> 
                                <input type="text" class="datepicker" name="fecha_reporte" id="fecha_reporte" size="60"  value="<?php echo date("Y-m-d h:m"); ?>"  />                                               <input type="hidden" name="fecha_reporteaux" id="fecha_reporteaux" size="60"  value="<?php echo date("Y-m-d h:m"); ?>"  /> 
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
                                        <td class="StormyWeatherDataTD" colspan="4"><center>
                                              <button type="button" id="btningresar" name="btningresar" align="center" class="fg-button ui-state-default ui-corner-all" value="Ingresar Resultados" Onclick="IngresarTodosResultados() ;"> Ingresar Resultados</button>
                                        </center></td>
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
		<div id="valresult" style="display:none; width: 70%;">  
                <table align="left" width="100%" border="0"cellpadding="2" >
                <tr>
                        <td><b>Valido Resultado:</b></td>
                        <td colspan="4">
                                <div id="divEncargado1">
                                    <select id="cmbEmpleadosfin" name="cmbEmpleadosfin" size="1">
                                        <option value="0" >--Seleccione Empleado--</option>
                                    </select>
                                </div>										
                        </td>
                </tr>
               
                </table>

                
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
 <script type="text/javascript" src="../../../public/datepicker/jquery-1.11.1.min.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/jquery-ui.min.js"></script>
		<script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-addon.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-addon-i18n.min.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-es.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/jquery-ui-sliderAccess.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/script.js"></script> 
</html>
