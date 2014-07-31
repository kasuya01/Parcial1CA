<?php session_start();
include_once("clsLab_DatosFijosExamen.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$iddatosfijosexamen=$_POST['iddatosfijosexamen'];
//echo $iddatosfijosexamen;
//echo $iddatosfijosexamen;
$obj = new clsLab_DatosFijosExamen;
$consulta=$obj->consultarid($iddatosfijosexamen,$lugar);
$row = pg_fetch_array($consulta);
//$iddatosfijosexamen;
//valores de las consultas
$idarea=$row['idarea'];
//echo $idarea;
$nombrearea=$row['nombrearea'];
$idexamen=$row['idexamen'];
//echo $idexamen;
$nombreexamen=$row['nombreexamen'];
$unidades=$row['unidades'];
//echo $unidades;
$rangoinicio=$row['rangoinicio'];
$rangofin=$row['rangofin'];
$nota=$row['nota'];
$Fechaini=$row['fechaini'];
$Fechafin=$row['fechafin'];
$idsexo=$row['idsexo'];
$nombresexo=$row['sexo'];
if (empty($idsexo)){
    $idsexo=0;
$nombresexo="Ambos";}    

$idedad=$row['idedad'];
$rangoedad=$row['redad'];
//echo $idsexo,$nombresexo;
//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" action="">
    <table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
        <tr>
            <td colspan="3" align="center" class="CobaltFieldCaptionTD"><h3><strong>Datos Fijos de Resultados de Ex&aacute;menes de Laboratorio</h3></strong>
            </td>
	</tr>
	<tr>
            <td width="17%" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
            <td width="83%" class="StormyWeatherDataTD">
                <select id="cmbArea" name="cmbArea" size="1" onChange="LlenarComboExamen(this.value);">
                    <option value="0" >--Seleccione un &Aacute;rea--</option>";
                    <?php
			include('../Lab_Areas/clsLab_Areas.php');
			$objeareas=new clsLab_Areas;
			$consulta= $objeareas->consultaractivas();
			while($row = pg_fetch_array($consulta)){
                            echo "<option value='".$row['id']."'>".$row['nombrearea']."</option>";
                           
                        }
                            echo "<option value='" . $idarea . "' selected='selected'>" .htmlentities($nombrearea). "</option>";
                    ?>		  
		</select>
            </td>
        </tr>
        <tr>
            <td width="17%" class="StormyWeatherFieldCaptionTD">Examen </td>
            <td width="83%" class="StormyWeatherDataTD">
                <select id="cmbExamen" name="cmbExamen" size="1">
                    <option value="0">--Seleccione un Examen--</option>";
                    <?php
                        $consultaex = $obj->ExamenesPorArea($idarea);
                        while($row = mysql_fetch_array($consultaex))
                        {
                            echo "<option value='" . $row['idexamen']. "'>" . $row['nombreexamen'] . "</option>";
                        }						            	
                            echo "<option value='" . $idexamen . "' selected='selected'>" .$nombreexamen. "</option>";
                    ?>	
		</select>
            </td>
        </tr>
        <tr>
            <td width="17%" class="StormyWeatherFieldCaptionTD">Sexo</td>
            <td width="83%"  class="StormyWeatherDataTD">
                <select id="cmbSexo" name="cmbSexo" size="1" >
                     <option value="3">Ninguno</option>  
                     <option value="0">Ambos</option>
                    <?php
                            $consultaS= $obj->consultarsexo();
                            while($row =pg_fetch_array($consultaS)){
                                 echo "<option value='" . $row[0]. "'>". $row[1] . "</option>";

                            }

                            echo "<option value='" . $idsexo . "' selected='selected'>" .$nombresexo. "</option>";
                    ?>        
                </select>		  
             </td>        
        </tr>
         <tr>
                <td width="17%" class="StormyWeatherFieldCaptionTD">Rango Edad</td>
                <td width="83%"  class="StormyWeatherDataTD">
                    <select id="cmbEdad" name="cmbEdad" size="1" >
                        <option value="0" >--Seleccione un Rango de Edad--</option>
                            <?php
                                $conEdad = $obj->RangosEdades();
                                while($row = pg_fetch_array($conEdad)){
                                     echo "<option value='" . $row[0]. "'>". $row[1] . "</option>";
                                }
                                echo "<option value='" . $idedad . "' selected='selected'>" .$rangoedad. "</option>";
                            ?>    
                    </select>		  
                </td>        
        </tr>
	<tr>
            <td width="17%" class="StormyWeatherFieldCaptionTD">Unidades</td>
            <td width="83%" class="StormyWeatherDataTD"><input name="txtunidades" type="text" id="txtunidades" value="<?php echo htmlentities($unidades); ?>" size="10"></td>
   	</tr>
       	<tr>
            <td colspan="2" class="StormyWeatherDataTD">
		<fieldset><legend><span>Rangos</span></legend>
                    <table width="200" border="0" align="center" class="StormyWeatherFormTABLE">
                        <tr>
                                <td class="StormyWeatherFieldCaptionTD">Inicio</td>
                                        <input type="hidden" name="txtoculto" id="txtoculto" size="50" value="<?php echo $iddatosfijosexamen; ?>" />
                                <td class="StormyWeatherDataTD"> 
                                        <input name="txtrangoinicio" type="text" id="txtrangoinicio" value="<?php echo $rangoinicio; ?>" size="6">
                                </td>
                                <td class="StormyWeatherFieldCaptionTD">Fin </td>
                                <td class="StormyWeatherDataTD">
                                        <input name="txtrangofin" type="text" id="txtrangofin" value="<?php echo $rangofin; ?>" size="6">
                                </td>
                        </tr>
                    </table>
                </fieldset>               
            </td>
        </tr>
       	<tr>
            <td class="StormyWeatherFieldCaptionTD">Observaci&oacute;n</td>
            <td class="StormyWeatherDataTD">
          	<textarea name="txtnota" cols="50" rows="6" id="txtnota" ><?php echo htmlentities($nota); ?></textarea>
	    </td>
       	</tr>
	<tr>
            <td colspan="2" class="StormyWeatherDataTD">
                <table width="700" border="0" align="center" class="StormyWeatherFormTABLE">
                    <tr>
                        <td class="StormyWeatherFieldCaptionTD">Fecha Inicio</TD>
                        <td  class="StormyWeatherDataTD">
                            <input name="txtFechainicio1" type="text" id="txtFechainicio" value="<?php echo $Fechaini; ?>" size="8" >dd/mm/aaaa</td>
                        <td class="StormyWeatherFieldCaptionTD">Fecha Final</D>
                        <td  class="StormyWeatherDataTD">
                            <input name="txtFechaFin1" type="text" id="txtFechaFin" value="<?php echo $Fechafin; ?>" size="8" >dd/mm/aaaa</td>	
                    </tr>
                </table>
            </td>
	</tr>	
	<tr>
            <td class="StormyWeatherDataTD" colspan="2" align="right">
		<input type="button" name="btnGuardar" value="Actualizar" onClick="Actualizar() ;">
		<input type="button" name="btnBuscar" value="Buscar" Onclick="Buscar() ;">
		<input type="button" name="btnCancelar" value="Cancelar" onClick="window.location.replace('MntDatosFijosResultadosExamen.php');">			
            </td>
        </tr>
	</table>

</form>


