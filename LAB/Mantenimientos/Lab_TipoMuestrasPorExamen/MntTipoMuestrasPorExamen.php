<?php 
include('../Lab_Areas/clsLab_Areas.php');
include('../Lab_TipoMuestra/clsLab_TipoMuestra.php');
session_start();
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
$objeareas=new clsLab_Areas;
$consulta= $objeareas->consultaractivas($lugar);
$obje=new clsLab_TipoMuestra;
$consulta2= $obje->consultar();
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
<title>Mantenimiento de Examenes de Laboratorio</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_TipoMuestrasPorExamen.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<script language="JavaScript" >
var resto = new Array();
var c = 0;

function LlenarComboExamen(idArea)
{
   
  LlenarExamenes(idArea);   
}

function EliminarItemsLista()
{ 
//alert("ENTRO");
       indice=document.getElementById('ListAsociados').selectedIndex;
	var lista = document.getElementById('ListAsociados').options;
       // alert (indice);
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
		  alert("Seleccione la muestra a eliminar");
		}
	}
	else alert("No hay muestra que eliminar");
}
</script>
</head>

<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" >
<?php 
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
<table align="center" width="100%">
<tr>
<td>
<div id="divOculto">
<input type="hidden" name="txtoculto" id="txtoculto" value="NNN"/>
</div>
<div  id="divFrmNuevo" >
<form name="frmnuevo">
<table width="40%" border="0" align="center" class="StormyWeatherFormTABLE">
    <tr>
        <td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Asociaci&oacute;n de Tipos de Muestra por Examen</strong></h3>
	</td>
    </tr>
    <tr>
        <td class="StormyWeatherFieldCaptionTD" colspan="1">&Aacute;rea</td>
        <td class="StormyWeatherDataTD" colspan="1">
            <select id="cmbArea" name="cmbArea" size="1" onChange="LlenarExamenes(this.value);" class="form-control height">
                <option value="0" >--Seleccione un &Aacute;rea--</option>
                    <?php
			
			while($row = pg_fetch_array($consulta)){
			echo "<option value='" . $row['idarea']. "'>" . $row['nombrearea'] . "</option>";
			}
			pg_free_result($row);		
                    ?>		  
             </select>		  
	</td>
     </tr>
     <tr>
        <td class="StormyWeatherFieldCaptionTD">Examen </td>
        <td class="StormyWeatherDataTD" colspan="1">
            <div id="divExamen">
                <select id="cmbExamen" name="cmbExamen" size="1" class="form-control height">
                    <option value="0">--Seleccione un Examen--</option>
			
		</select>
            </div>		  
	</td>
    </tr>
    <tr>		  
        <td colspan="2">
            <table width="100%" height="80%" border="0"  class="StormyWeatherFormTABLE"  >
                <tr>
                    <td class="StormyWeatherFieldCaptionTD" width="45%"><div align="center">Tipos de Muestra </div>
                    </td>
                    <td  class="StormyWeatherDataTD" width="10%" class="StormyWeatherDataTD"><div align="center"></div>
                    </td>
                    <td width="45%" class="StormyWeatherFieldCaptionTD"><div align="center">Muestras Asociados </div>
                    </td>
                </tr>
                <tr>
                    <td class="StormyWeatherDataTD" align="center">
                        <select id="ListMuestras" name="ListMuestras" size="8" multiple>
                            <?php
                                while($row = pg_fetch_array($consulta2)){
                                 echo "<option value='" . $row['id']. "'>" . $row['tipomuestra'] . "</option>";
                                }
                             ?>
			</select>
                    </td>
                    <td  rowspan="3" class="StormyWeatherDataTD" align="center"><input name="button" type="button" id='btnAgregar' onClick="AgregarItemsLista();"  value="&gt;&gt;" ><br>
                        <input type="button" id="btnEliminar" name="btnEliminar" value="<<"  onClick="EliminarItemsLista();" >
                    </td>
                    <td  class="StormyWeatherDataTD" align="center">
                        <div id="divDatos">
                            <select name="ListAsociados" id="ListAsociados" size="8" multiple>
                            </select>
                        </div>	
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="right" class="StormyWeatherDataTD">
                        <!--<input type="button" name="Submit" value="Guardar Muestras" onClick="Guardar() ;" >-->
                        <button type='button' align="center" class='btn btn-primary' id="abrir"  onclick='Guardar(); '><span class='glyphicon glyphicon-floppy-disk'></span> Guardar </button>
                        <!--<input type="button" name="submit1" value="Eliminar Muestra" onClick="Eliminar() ;" >-->
                        <button type='button' aling='center' class='btn btn-primary' id='submit1' onclick='Eliminar();'>Eliminar Muestra</button> 
                    </td>
               
		</tr>
            </table>              
        </td>
    </tr>
   </table>
</form>
</div>
</td>
	</tr>
	<tr>
		<td>
			<div  id="divresultado" >

			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div  id="divFrmModificar" >

			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div  id="divinicial" >

			</div>
		</td>
	</tr>
</table>
</body>
</html>
