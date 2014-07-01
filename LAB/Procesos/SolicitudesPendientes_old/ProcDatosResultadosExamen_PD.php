<?php session_start();

$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

?>
<html>
<head> 
<title>Resultados de Ex&aacute;menes de Laboratorio</title>
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
function Cancelar()
{
    show_event(1);
}
function calc_edad()
{
  var fecnac1=document.getElementById("suEdad").value;
  var fecnac2=fecnac1.substring(0,10);
  var suEdades=calcular_edad(fecnac2);
  document.getElementById("divsuedad").innerHTML=suEdades;
}

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
	document.frmnuevo.txtnombrearea.value=nombrearea;
	
	LlenarComboResponsable(area);
	
}
<?php   
 
	$bandera=$_GET['var12'];				    
?>
</script>
</head>

<body onLoad="CargarDatos();">
<table align="center" width="100%">
<tr>
<td>
<div  id="divFrmNuevo" >
<form name="frmnuevo">
<table width="70%" border="0" align="center" class="StormyWeatherFormTABLE">
    	<tr class="CobaltButton" ><td colspan="4" align="center"> <h3>DATOS GENERALES</h3></td></tr>
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
        <td width="30%" class="StormyWeatherFieldCaptionTD">NEC</td>
	    <td width="70%" class="StormyWeatherDataTD"><?php echo $_GET['var1'] ?>
		    <input type="hidden" name="txtnec" id="txtnec" disabled="disabled" />
		    <input type="hidden" name="txtidsolicitud" id="txtidsolicitud" />
			<input type="hidden" name="txtiddetalle" id="txtiddetalle" />
			<input type="hidden" name="txtidexamen" id="txtidexamen" />
			<input type="hidden" name="txtidrecepcion" id="txtidrecepcion" />
			<input type="hidden" name="txtarea" id="txtarea" >
			<input type="hidden" name="txtprocedencia" id="txtprocedencia" />
			<input type="hidden" name="txtorigen" id="txtorigen" />
			<input type="hidden" name="txtestablecimiento" id="txtestablecimiento" value="<?php echo $_GET['var13'];?>" />	
	    </td>
	</tr>
	<tr>
		<td width="30%" class="StormyWeatherFieldCaptionTD">Paciente</td>
		<td width="70%" class="StormyWeatherDataTD"> <?php echo $_GET['var7'] ?>
		<input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" /></td>
	</tr>
	<tr>
        <td width="30%" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
        <td width="70%" class="StormyWeatherDataTD"><?php echo $_GET['var9'] ?>
		   <input type="hidden" name="txtnombrearea" id="txtnombrearea" disabled="disabled" size="60" />  </td>
    </tr>
    <tr>
        <td width="30%" class="StormyWeatherFieldCaptionTD">Examen </td>
        <td width="70%" class="StormyWeatherDataTD"><?php echo $_GET['var2'] ?>
            <input type="hidden" name="txtexamen" id="txtexamen" disabled="disabled" size="60" />			 </td>
    </tr>
	<tr>
	    <td width="30%" class="StormyWeatherFieldCaptionTD">*Validado Por</td>
        <td width="70%" class="StormyWeatherDataTD">
			 <div id="divEncargado">
			 <select id="cmbEmpleados" name="cmbEmpleados" size="1" >
			 <option value="0" >--Seleccione Empleado--</option>
			 </select>
			 </div>
		</td>
    </tr>
	<tr>
        <td width="30%" class="StormyWeatherFieldCaptionTD">Elemento Encontrado </td>
        <td width="70%" class="StormyWeatherDataTD">
		    <select id="cmbElemento" name="cmbElemento" size="1" >
			<option value="0" >--Seleccione Elemento--</option>
			<?php
				include('clsPlantillaD.php');
				$obj=new clsPlantillaD;
				$consulta_bac= $obj->LeerElementosTincion();
				while($row = mysql_fetch_array($consulta_bac)){
				echo "<option value='" . $row[0]. "'>" . $row[1] . "</option>";
				}
				mysql_free_result($consulta_bac);
			?>	
			</select>	
		</td>
    </tr>		  
	<tr>
		<td width="20%" class="StormyWeatherFieldCaptionTD">Cantidad</td>
		<td width="80%" class="StormyWeatherDataTD">
		    <select id="cmbCantidad" name="cmbCantidad" size="1" >
		    <option value="0" >--Seleccione Cantidad--</option>
		   	<?php
				$obj=new clsPlantillaD;
				$consulta_bac= $obj->LeerCantidadTincion();
				while($row = mysql_fetch_array($consulta_bac)){
				echo "<option value='" . $row[0]. "'>" . $row[1] . "</option>";
				}
				mysql_free_result($consulta);
			?>	
			</select>
		</td>
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
	 <?php 
		 if ($bandera==1){
		 	 ?>
			</tr>
				<td colspan="2" class="StormyWeatherDataTD" align="center" style="color:#DD0000; font:bold"><h3>El m&eacute;dico ha solicitado la impresi&oacute;n de este Resultado </h3></td>
			<tr>
		  <?php 
		  }?>
	<tr>
	    <td colspan="2" class="StormyWeatherDataTD" align="rigth">
			<div id="divBotonGuardar"  align="rigth">
		       <input type="button" name="btnGuardar" value="Guardar Resultado" Onclick="GuardarPlantillaD()">
		    </div>  
	    </td>
    </tr>
	<tr>
		<td colspan="2" class="StormyWeatherDataTD" align="rigth">
		  <div id="divBotonPrevie" style="display:none" >
                    <table width="100%" >
                          <td class="StormyWeatherDataTD" colspan="2" align="rigth">
                                  <input type="button" name="btnGuardar" value="Agregar Elemento" Onclick="GuardarElemento()">
                                  <input type="button" name="btnVista"   value="Vista Previa" Onclick="VistaPrevia()">
                          </td>
                    </table>
		  </div>		  
		</td>
	</tr>
	</table>
		   
</form>
</div></td>
</tr>
<tr>
<td align="center">
<div  id="divresultado" >
</div>

</td>
</tr>
<tr>
<td align="center">
<div  id="divpreview" >
</div>
</td>
</tr>
</table>
</body>
</html>
