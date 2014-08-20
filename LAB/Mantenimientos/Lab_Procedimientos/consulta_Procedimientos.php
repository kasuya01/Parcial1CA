<?php session_start();
include_once("clsLab_Procedimientos.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idproce=$_POST['idproce'];
//echo $idproce;
$obj = new clsLab_Procedimientos;

$consulta=$obj->consultarid($idproce,$lugar);
$row = pg_fetch_array($consulta);

//valores de las consultas
$idarea=$row['id'];
$nombrearea=$row['nombrearea'];
$idexamen=$row['id'];
$nombreexamen=$row['nombreexamen'];
$proce=$row['nombreprocedimiento'];
$unidades=$row['unidades'];
$rangoini=$row['rangoinicio'];
$rangofin=$row['rangofin'];
$Fechaini=$row['fechaini'];
list($anio, $mes, $dia) = split('[/.-]', $Fechaini);
$Fechaini = $anio . "/" . $mes . "/" . $dia;

$Fechafin=$row['fechafin'];
list($anio, $mes, $dia) = split('[/.-]', $Fechafin);
$Fechafin = $anio . "/" . $mes . "/" . $dia;
$idsexo=$row['id'];
$nombresexo=$row['abreviatura'];
$idedad=$row['id'];
$rangoedad=$row['nombre'];
//muestra los datos consultados en los campos del formulario



?>
<form name= "frmModificar" action="" >
<table width="5
55%" border="0" align="center" class="StormyWeatherFormTABLE">
	<tr>
		<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Procedimientos de Ex&aacute;menes de Laboratorio</strong></h3>
		</td>
	</tr>	
	<tr>
        	<td  width="17%" class="StormyWeatherFieldCaptionTD" width="17%">&Aacute;rea</td>
        	<td width="83%" class="StormyWeatherDataTD">
			<select id="cmbArea" name="cmbArea" size="1" onChange="LlenarComboExamen(this.value);">
			<option value="0" >--Seleccione un &Aacute;rea--</option>";
			<?php
			include('../Lab_Areas/clsLab_Areas.php');
			$objeareas=new clsLab_Areas;
			$consulta= $objeareas->consultaractivas();
			while($row = pg_fetch_array($consulta)){
			echo "<option value='" . $row['id']. "'>" .htmlentities($row['nombrearea']) . "</option>";
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
				while($row = pg_fetch_array($consultaex))
				{
					echo "<option value='" . $row['id']. "'>" . $row['nombreexamen'] . "</option>";
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
                    <option value="0" >--Seleccione Sexo--</option>
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
	    	<td width="17%" class="StormyWeatherFieldCaptionTD">Procedimiento </td>
	    	<td width="83%" class="StormyWeatherDataTD"><input name="txtproc" type="text" id="txtproc" value="<?php echo htmlentities($proce); ?>" size="50"></td>
	</tr>
	<tr>
	    	<td width="17%" class="StormyWeatherFieldCaptionTD">Unidades</td>
	    	<td width="83%" class="StormyWeatherDataTD"><input name="txtunidades" type="text" id="txtunidades" value="<?php echo htmlentities($unidades); ?>" size="10">		  </td>
	</tr>
    	<tr>
        	<td colspan="2" class="StormyWeatherDataTD">
			<fieldset><legend><span>Rangos</span></legend>
			<table width="200" border="0" align="center" class="StormyWeatherFormTABLE">
			<tr>
				<td class="StormyWeatherFieldCaptionTD">Inicio</td>
                        <input type="hidden" name="txtoculto" id="txtoculto" size="50" value="<?php echo $idproce; ?>" />
				<td class="StormyWeatherDataTD"><input name="txtrangoinicio" type="text" id="txtrangoini" value="<?php echo $rangoini; ?>" size="8">
				</td>
				<td class="StormyWeatherFieldCaptionTD">Fin </td>
				<td class="StormyWeatherDataTD"><input name="txtrangofin" type="text" id="txtrangofin" value="<?php echo $rangofin; ?>" size="8"></td>
			</tr>
			</table>
			</fieldset>               
		</td>
    	</tr>
    	<tr>
        	<td colspan="2" class="StormyWeatherDataTD">
				<table width="850" border="0" align="center" class="StormyWeatherFormTABLE">
				<tr>
					<td width="17%" class="StormyWeatherFieldCaptionTD">Fecha Inicio</TD>
					<td width="30%" class="StormyWeatherDataTD">
						<input name="txtFechainicio1" type="text" id="txtFechainicio" value="<?php echo $Fechaini; ?>" size="8" >dd/mm/aaaa</td>
					<td width="17%" class="StormyWeatherFieldCaptionTD">Fecha Final</D>
					<td width="30%" class="StormyWeatherDataTD">
						<input name="txtFechaFin1" type="text" id="txtFechaFin" value="<?php echo $Fechafin; ?>" size="8" >dd/mm/aaaa</td>
				</tr>
				</table>
			</td>		
		</tr>
    	<tr>
        	<td class="StormyWeatherDataTD" colspan="2" align="right"><input type="button" name="btnGuardar" value="Actualizar" onClick="Actualizar() ;">
			<input type="button" name="btnBuscar" value="Buscar" Onclick="Buscar() ;">
		    	<input type="button" name="btnCancelar" value="Cancelar" onClick="window.location.replace('MntProcedimientosExamen.php')">			
		</td>
    </tr>
</table>
</form>

