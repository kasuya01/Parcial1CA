<?php session_start();
include_once("clsLab_Posresultado.php");
$lugar=$_SESSION['Lugar'];
$corr=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idposres=$_POST['idposres'];
$obj = new clsLab_Posresultado;
$consulta=$obj->consultarid($idposres);
$row = pg_fetch_array($consulta);

//valores de las consultas
$posibleresultado=$row['posible_resultado'];
$habilitado=$row['habilitado'];
$estado=$row['estado'];

//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar">
  <input name="opcion" type="hidden" value="N" />
	<table width="40%" border="0" align="center" class="StormyWeatherFormTABLE">
		<tr>
			<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Posible Resultado</strong></h3>
			</td>
		</tr>
		<!--<tr>
			<td class="StormyWeatherFieldCaptionTD">IdAntibiotico</td>
			<td class="StormyWeatherDataTD"><input name="idantibiotico" id="idantibiotico" type="text" value="<?php echo $posibleresultado; ?>" disabled="disabled" />
			</td>
		</tr> -->
		<tr>
			<td class="StormyWeatherFieldCaptionTD">Posible Resultado</td>
			<td class="StormyWeatherDataTD"><input name="posresultado" id="posresultado" type="text" class="form-control height" value="<?php echo htmlentities($posibleresultado); ?>" size="40" />
                            <input name="idposresultado" id="idposresultado" type="hidden" value="<?php echo $idposres; ?>" disabled="disabled" />
                        </td> 
                </tr>
                         
                         <tr>
            <td width="17%" class="StormyWeatherFieldCaptionTD">Estado</td>
            <td width="83%"  class="StormyWeatherDataTD">
                <select id="cmbEstado" name="cmbEstado" size="1"  style="width:50%"  class="form-control height" >
                    <option value="0" >--Seleccione Estado--</option>
                    <?php
                      /*  $consultaS= $obj->consultarsexo();
                        while($row =pg_fetch_array($consultaS)){*/
                        	//if($row['id'] === $idsexo)
                    if($habilitado=='t'){
                         echo "<option value='f' selected='selected'>Inhabilitado</option>";
                         echo "<option value='" . $habilitado . "' selected='selected'>" .$estado. "</option>";
                       
                    }
                    else {
                        echo "<option value='true' selected='selected'>Habilitado</option>";
                        echo "<option value='" . $habilitado . "' selected='selected'>" .$estado. "</option>";
                        
                    }
                    ?>        
                </select>		  
             </td>        
        </tr>
		<!--<tr>
			<td class="StormyWeatherDataTD" colspan="2" align="right">
				<input type="button" name="btnActualizar" value="Actualizar" onclick="ActualizarDatos()" />
				<input type="button" name="btnNuevo" value="Nuevo" onClick="window.location.replace('MntAntibioticos.php')" />
			</td>
                </tr>-->
                
                <tr>  
            <td class="StormyWeatherDataTD" colspan="6" align="right">
                <button type='button' align="center" class='btn btn-primary'  onclick='ActualizarDatos(); '><span class='glyphicon glyphicon-repeat'></span> Actualizar </button>
                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntPosresultado.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                <!--
                <input type="button" name="btnSubElementos" value="SubElementos" Onclick="MostrarSubElementos() ;">  
                -->
                 </td>
        </tr>

	</table>
</form>

