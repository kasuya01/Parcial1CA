<?php session_start();
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">  
<title>Antibioticos por Tarjeta</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_AntibioticosPorTarjeta.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
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
					else{alert("El Antibiotico ya esta asociado...")}
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
if ($nivel == 5) {
        include_once ('../../../PaginaPrincipal/index_laboratorio52.php');}
if ($nivel == 6) {
        include_once ('../../../PaginaPrincipal/index_laboratorio62.php');}
if ($nivel == 7) {
        include_once ('../../../PaginaPrincipal/index_laboratorio72.php'); }         
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
				<td align="center" class="StormyWeatherFieldCaptionTD">Tarjeta VITEK </td>
				<td width="53%"  class="StormyWeatherDataTD">
					<select name="cmbTarjetas" id="cmbTarjetas" onChange="LlenarLista(this.value);" style="width:250px"  class="form-control height">
					<option value="0" >--Seleccione una Tarjeta--</option>
					<?php
						include_once('../Lab_TarjetasVITEK/clsLab_TarjetasVITEK.php');
						$obj=new clsLab_TarjetasVITEK;
						$consulta= $obj->consultar($lugar);
						while($row = pg_fetch_array($consulta))
						{
				          echo "<option value='" . $row['id']. "'>" . $row['nombretarjeta'] . "</option>";
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
											<td width="40%" align="center" class="StormyWeatherFieldCaptionTD" >Antibi&oacute;tico Asociado por Tarjeta</td>
									</tr>
									<tr>
											<td width="47%" align="center" class="StormyWeatherDataTD">
												<select name="ListAntibioticos" id="ListAntibioticos" size="8" multiple>
												<?php
													include_once('../Lab_Antibioticos/clsLab_Antibioticos.php');
													$obj=new clsLab_Antibioticos;
													echo $consulta= $obj->consultar();
													while($row = pg_fetch_array($consulta))
													{
													echo "<option value='" . $row['id']. "'>" . $row['antibiotico'] . "</option>";
													}
												?>		
												</select>            
											</td>
											<td width="6%" align="center" class="StormyWeatherDataTD">
												<table width="100%" border="0" >
													<tr>
														<!--<td align="center" class="StormyWeatherDataTD">
																<input type="button" name="Agregar" value=">>" onClick="AgregarItemsLista();" >
                                                                                                                </td>-->
													
                                                                                                        
                                                                                                                 <td align="center" class="StormyWeatherDataTD" colspan="6" align="right">
                                                                                                                        <button type='button' align="center" class='btn btn-primary'  onclick='AgregarItemsLista(); '><span class='glyphicon glyphicon-forward'></span></button>
                                                                                                                </td>
                                                                                                        
                                                                                                        </tr>
                                                                                                        <tr>
														<td align="center" class="StormyWeatherDataTD">&nbsp;</td>
													</tr>
													<tr>
														<!--<td align="center" class="StormyWeatherDataTD">
																<input type="button" name="Eliminar" value="<<"  onClick="EliminarItemsLista();" >
														</td>-->
                                                                                                                
                                                                                                                
                                                                                                                <td align="center" class="StormyWeatherDataTD" colspan="6" align="right">
                                                                                                                <button type='button' align="center" class='btn btn-primary'  onclick='EliminarItemsLista(); '><span class='glyphicon glyphicon-backward'></span></button>
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
									<!--<tr>
										<td colspan="3" align="right" class="StormyWeatherDataTD">
											<input type="button" name="btnGuardar" value="Guardar Muestras" onClick="GuardarItems();">
											<input type="button" name="submit1" value="Eliminar Muestra" onClick="EliminarItems() ;" >
										</td>
                                                                        </tr>-->
                                                                   
                                                                   <tr>
                                                                       <td class="StormyWeatherDataTD" colspan="6" align="right">
                                
                                                                            
                                                                            <button type='button' align="center" class='btn btn-primary'  onclick='GuardarItems(); '><span class='glyphicon glyphicon-floppy-disk'></span> Guardar </button>
                                                                            <button type='button' align="center" class='btn btn-primary'  onclick='AgregarItems(); '><span class='glyphicon glyphicon-remove-circle'></span> Adicionar Antibiótico</button>
                                                                            <button type='button' align="center" class='btn btn-primary'  onclick='EliminarItems(); '><span class='glyphicon glyphicon-remove-circle'></span> Eliminar </button>
                                                                            

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
