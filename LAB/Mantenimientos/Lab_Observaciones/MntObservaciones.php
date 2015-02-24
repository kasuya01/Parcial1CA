<?php session_start();
include('../Lab_Areas/clsLab_Areas.php');
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
<title>Mantenimiento de Observaciones</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_Observaciones.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
 <script language="JavaScript" type="text/javascript">

                function buscarObs()
                {
                    if ((document.getElementById('cmbArea').value == 0) 
                        &&(document.getElementById('cmbTipoRespuesta').value == 0) &&  
                       (document.getElementById('txtobservacion').value == "")){
               alert("Debe de Ingresar un Parámetro de Búsqueda ");
                    }
                    else {
                        BuscarObservacion();
                    }
                    
                    //idarea=document.getElementById('cmbArea').value;
                    //BuscarObservacion();
                }
 </script>
        </head>
</head>

<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);">

<?php 
if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
?><br>

<table align="center" width="100%">
<tr>
<td>
<div  id="divFrmNuevo" >
<form name="frmnuevo" action="" onSubmit="IngresarRegistro(); return false">
<table width="60%" border="0" align="center" class="StormyWeatherFormTABLE">
    <tr>
		<td colspan="2" class="CobaltFieldCaptionTD" align="center"><H3><strong>Mantenimiento de Observaciones</strong></H3>
		 <input type="hidden" name="txtidobservacion" id="txtidobservacion" disabled="disabled" />
                 </td>
	</tr>	
	<!-- <tr>
		<td class="StormyWeatherFieldCaptionTD">Idobservacion</td>
        <td class="StormyWeatherDataTD"><input type="text" name="txtidobservacion" id="txtidobservacion" disabled="disabled" />
		</td>
    </tr> -->
	<tr>
        <td class="StormyWeatherFieldCaptionTD">C&oacute;digo del &Aacute;rea</td>
        <td class="StormyWeatherDataTD">
            <input type="hidden" name="txtidobservacion" id="txtidobservacion"  disabled="disabled" />
			<select id="cmbArea" class="form-control height" style="width:250px" name="cmbArea" size="1">
			<option value="0" >--Seleccione un &Aacute;rea--</option>
			<?php
			$objeareas=new clsLab_Areas;
			$consulta= $objeareas->consultaractivas($lugar);
			while($row = pg_fetch_array($consulta)){
			echo "<option value='" . $row['idarea']. "'>" . $row['nombrearea'] . "</option>";
			}
			?>		  
          </select>
	    </td>
    </tr>
	<tr>
        <td class="StormyWeatherFieldCaptionTD">Tipo Respuesta </td>
        <td class="StormyWeatherDataTD">
			<select id="cmbTipoRespuesta"  class="form-control height" style="width:250px" name="cmbTipoRespuesta" size="1" >
			<option value="0" >--Seleccione Respuesta--</option>
			<option value="P" >Positivo</option>
			<option value="N" >Negativo</option>
			<option value="O" >Otro</option>
			</select>
		</td>
    </tr>
    <tr>
        <td class="StormyWeatherFieldCaptionTD">Observaci&oacute;n </td>
        <td class="StormyWeatherDataTD"><textarea name="txtobservacion" cols="60" placeholder="Ingrese Observación... " id="txtobservacion"<span style="color: #0000FF;background-color:#87CEEB;"></textarea></td>
    </tr>
    
   <!-- <tr>
        <td colspan="2" class="StormyWeatherDataTD" align="right">
            <input type="submit" name="Submit" value="Guardar">
	        <input type="button" name="btnbuscar" value="Buscar" onClick="BuscarObservacion();">
			 <input type="button" name="Submit" value="Cancelar" onClick="window.location.replace('MntObservaciones.php')">
        </td>
     </tr>-->
     
     <tr>
         <td class="StormyWeatherDataTD" colspan="6" align="right">
                                
                                <button type='submit' align="center" class='btn btn-primary' id='buscarsolicitud' ><span class='glyphicon glyphicon-floppy-disk'> </span>Guardar</button>
                                <button type='button' align="center" class='btn btn-primary' id='buscarsolicitud' onclick='buscarObs(); '><span class='glyphicon glyphicon-search'></span>Buscar</button>
                                <button type='button' align="center" class='btn btn-primary' id='nuevabusqueda' onclick="window.location.replace('MntObservaciones.php')"><span class='glyphicon glyphicon-refresh'></span>Nueva Busqueda</button>
                
                </td>
                
     </tr>
     
</table>
</form>

</div>
</td>
</tr>
<tr>
<td>
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
