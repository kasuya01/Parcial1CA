<?php session_start();
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">  
<title>Antibioticos por Tarjeta</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_AntibioticosPorTarjeta.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
</head>
<script language="JavaScript" type="text/javascript">
var resto = new Array();
var c = 0;
 
function AgregarItemsLista()
{
   var j=0; 
   var encontrado = false; 
   var texto = new Array(); 
   var valor = new Array(); 
   var combotarjeta=document.getElementById('cmbTarjetas');
   var list1= document.getElementById('ListAntibioticos');
   var list2= document.getElementById('ListAsociados');
   // C�digo para ver cual son los que hay que eliminar de un select e incluirlos en el otro. 
	if (combotarjeta.value != 0)
   	{
		for (i=0 ; i<list1.options.length ; i++) 
		{ 	// Los que est�n seleccionados, comprobamos que no est�n en la segunda lista y si es asi, lo a�adimos a esta. 
			if (list1.options[i].selected) 
			{ 		j=0; 
					encontrado = false; 
				while (j < list2.options.length && !encontrado) 
				{ 
					if (list1.options[i].value == list2.options[j].value) 
					{ 
						encontrado = true; 
					} 
						j++; 
				} 
					if (!encontrado) 
					{ 
						texto[texto.length] = list1.options[i].text; 
						valor[valor.length] = list1.options[i].value; 
					} 
					else{alert("El Antibiotico ya esta asociado..")}
			} 
		} 
		   // Eliminamos de uno y lo incluimos en el otro. 
			for (h=texto.length-1;h>=0;h--) 
			{ 
				list2.options[list2.options.length] = new Option (texto[h], valor[h]); 
				list1.options[texto[h]] = null; 
			} 
	}
	else{
		alert("Seleccione un Tarjeta VITEK");
	}
}
function VerArreglo()
{	//alert(c);
	for (i=0; i < c; i++ )
	{
	  alert(resto[i]);
	}
	
}
function EliminarItemsLista()
{ 
	indice=document.getElementById('ListAsociados').selectedIndex;
	var lista = document.getElementById('ListAsociados').options;
  // alert(indice);
	if (document.getElementById('ListAsociados').length!=0){
		if (indice != (-1))
		{	//guardando en un arreglo temporal
			resto[c]=document.getElementById('ListAsociados').value;
			c++;
			//eliminando el elemento de la lista
			lista[indice] = null;
		}
		else
		{
		  alert("Seleccione el Antibiotico a eliminar");
		}
	}
	else alert("No hay Antibiotico que eliminar")
}

function GuardarItems()
{
	GuardarDatosAsociaciados();
}


function LlenarLista(idTarjeta)
{
   TarjetasAsociadas(idTarjeta);
}
</script>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" >

<?php 

//echo "*".$corr."-".$lugar."-".$area."-".$nivel;
if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
?><br>

<div  id="divFrmNuevo" >
	<form name="frmnuevo">
		<table width="50%" border="0"  align="center" class="StormyWeatherFormTABLE">
			<tr>
				<td colspan="3" class="CobaltFieldCaptionTD" align="center"><h3><strong>Antibi&oacute;ticos por Tarjeta</strong></h3>
				</td>
			</tr>
			<tr>
				<td align="center" width="35%">
					<table width="100%" border="0">
						<tr>
			<!--<input type="hidden" name="txtoculto" id="txtoculto" /> -->
				<td width="47%" class="StormyWeatherFieldCaptionTD">Tarjeta VITEK </td>
				<td width="53%"  class="StormyWeatherDataTD">
					<select name="cmbTarjetas" id="cmbTarjetas" onChange="LlenarLista(this.value);">
					<option value="0" >--Seleccione un Tarjeta--</option>
					<?php
						include_once('../Lab_TarjetasVITEK/clsLab_TarjetasVITEK.php');
						$obj=new clsLab_TarjetasVITEK;
						$consulta= $obj->consultar($lugar);
						while($row = pg_fetch_array($consulta))
						{
				          echo "<option value='" . $row['IdTarjeta']. "'>" . $row['NombreTarjeta'] . "</option>";
						}
					?>		
					</select>
					
				</td>
			</tr>
						<tr>
							<td colspan="2">
								<table width="100%" border="0" class="StormyWeatherFormTABLE">
									<tr>
											<td width="40%" align="center" class="StormyWeatherFieldCaptionTD" >Lista Antibi&oacute;ticos 				  </td>
											<td width="10%" class="StormyWeatherDataTD">&nbsp;</td>
											<td width="40%" align="center" class="StormyWeatherFieldCaptionTD" >Antibi&oacute;tico asociado por Tarjeta</td>
									</tr>
									<tr>
											<td width="47%" align="center" class="StormyWeatherDataTD">
												<select name="ListAntibioticos" id="ListAntibioticos" size="8" multiple>
												<?php
													include_once('../Lab_Antibioticos/clsLab_Antibioticos.php');
													$obj=new clsLab_Antibioticos;
													$consulta= $obj->consultar($lugara);
													while($row = pg_fetch_array($consulta))
													{
													echo "<option value='" . $row['IdAntibiotico']. "'>" . $row['Antibiotico'] . "</option>";
													}
												?>		
												</select>            
											</td>
											<td width="6%" align="center" class="StormyWeatherDataTD">
												<table width="100%" border="0" >
													<tr>
														<td align="center" class="StormyWeatherDataTD">
																<input type="button" name="Agregar" value=">>" onClick="AgregarItemsLista();" ></td>
													</tr>
													<tr>
														<td align="center" class="StormyWeatherDataTD">&nbsp;</td>
													</tr>
													<tr>
														<td align="center" class="StormyWeatherDataTD">
																<input type="button" name="Eliminar" value="<<"  onClick="EliminarItemsLista();" >
														</td>
													</tr>
												</table>
											</td>
											<td width="47%" align="center" class="StormyWeatherDataTD">
												<div id="divDatos">
													<select name="ListAsociados" id="ListAsociados" size="8" multiple>
													</select>
												</div>
											</td>
									</tr>
									<tr>
										<td colspan="3" align="right" class="StormyWeatherDataTD">
											<input type="button" name="btnGuardar" value="Guardar Muestras" onClick="GuardarItems();">
											<input type="button" name="submit1" value="Eliminar Muestra" onClick="EliminarItems() ;" >
										</td>
								   </tr>
								</table>
							</td>
						</tr>
					</table>
					</td>
				
		
		</form>
	</div>
	<div id="divResultado" style="display:none" >
	</div>
</body>
</html>
