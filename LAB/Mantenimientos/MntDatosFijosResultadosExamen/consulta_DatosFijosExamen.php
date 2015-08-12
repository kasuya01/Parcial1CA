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

$consulta1=$obj->consultarhabilitado($iddatosfijosexamen);
$row = pg_fetch_array($consulta1);
$habilitado=$row['habilitado'];


$consulta=$obj->consultarid($iddatosfijosexamen,$lugar);
$row = pg_fetch_array($consulta);
//$iddatosfijosexamen;
//valores de las consultas
$idarea=$row['idarea'];
//echo $idarea;
$nombrearea=$row['nombrearea'];
$idexamen=$row['idexamen'];
//echo $idexamen;
$nombreexamen=$row['nombre_examen'];
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
            <td colspan="4" align="center" class="CobaltFieldCaptionTD"><h3><strong>Datos Fijos de Resultados de Ex&aacute;menes de Laboratorio</h3></strong>
            </td>
	</tr>
	<tr>
            <td width="17%" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
            <td width="83%" class="StormyWeatherDataTD" colspan="3">
                <select id="cmbArea" name="cmbArea" size="1" style="width:75%" onChange="LlenarComboExamen(this.value);" class="form-control height">
                    <option value="0" >--Seleccione un &Aacute;rea--</option>";
                    <?php
			include('../Lab_Areas/clsLab_Areas.php');
			$objeareas=new clsLab_Areas;
			$consulta= $objeareas->consultaractivas($lugar);
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
            <td width="83%" class="StormyWeatherDataTD" colspan="3">
                <select id="cmbExamen" name="cmbExamen" style="width:75%" size="1" class="form-control height"> 
                    <option value="0">--Seleccione un Examen--</option>";
                    <?php
                        $consultaex = $obj->ExamenesPorArea($idarea,$lugar);
                        while($row = pg_fetch_array($consultaex))
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
            <td width="83%"  class="StormyWeatherDataTD" colspan="3">
                <select id="cmbSexo" name="cmbSexo" size="1" style="width:75%" class="form-control height">
                     <option value="3">--Seleccione Sexo--</option>  
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
                <td width="83%" colspan="3" class="StormyWeatherDataTD">
                    <select id="cmbEdad" name="cmbEdad" style="width:75%" size="1" class="form-control height">
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
            <td width="83%" class="StormyWeatherDataTD" colspan="3"><input class="form-control height placeholder" size="10" style="width:250px" name="txtunidades" type="text" id="txtunidades" value="<?php echo htmlentities($unidades); ?>" size="10"></td>
   	</tr>
       	<tr>
            <td  class="StormyWeatherFieldCaptionTD">Rango Inicio</td>
                <input type="hidden" name="txtoculto" id="txtoculto" size="50"  value="<?php echo $iddatosfijosexamen; ?>" />
            <td class="StormyWeatherDataTD" > 
                <input name="txtrangoinicio" type="text" id="txtrangoinicio" style="width:250px" placeholder="Ingrese Rango Inicial" class="form-control height placeholder" value="<?php echo $rangoinicio; ?>" size="6">
            </td>
            <td class="StormyWeatherFieldCaptionTD" style="width:250px">Rango Final </td>
            <td class="StormyWeatherDataTD">
                <input name="txtrangofin" type="text" id="txtrangofin" style="width:250px" placeholder="Ingrese Rango Final" class="form-control  height placeholder"value="<?php echo $rangofin; ?>" size="6">
            </td>
        </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD">Observaci&oacute;n</td>
            <td class="StormyWeatherDataTD" colspan="3">
          	<textarea name="txtnota" cols="65" rows="3" id="txtnota" style="width:98%" class="form-control"><?php echo htmlentities($nota); ?></textarea>
                
	    </td>
       	</tr>
	<tr>
            
                        <td class="StormyWeatherFieldCaptionTD">Fecha Inicio</TD>
                        <td  class="StormyWeatherDataTD">
                            <input name="txtFechainicio1" type="text" id="txtFechainicio" size="25" class="date form-control  height placeholder" placeholder="aaaa-mm-dd" value="<?php echo $Fechaini; ?>" size="8" ></td>
                        <td class="StormyWeatherFieldCaptionTD">Fecha Final</D>
                        <td  class="StormyWeatherDataTD">
                            <input name="txtFechaFin1" type="text" id="txtFechaFin" size="25" class="date form-control  height placeholder" placeholder="aaaa-mm-dd" value="<?php echo $Fechafin; ?>" size="8" ></td>	
                   
	</tr>	
	<!--<tr>
            <td class="StormyWeatherDataTD" colspan="2" align="right">
		<input type="button" name="btnGuardar" value="Actualizar" onClick="Actualizar() ;">
		<input type="button" name="btnBuscar" value="Buscar" Onclick="Buscar() ;">
		<input type="button" name="btnCancelar" value="Cancelar" onClick="window.location.replace('MntDatosFijosResultadosExamen.php');">			
            </td>
        </tr>-->
        
          <?php  
                if ($habilitado=="Inhabilitado"){
                    
                    ?>
        
        <tr>  
            <td class="StormyWeatherDataTD" colspan="6" align="right">
                
                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntDatosFijosResultadosExamen.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
            </td>
        </tr>
        
        <?php        
                } else {
                  ?>
        
        <tr>  
            <td class="StormyWeatherDataTD" colspan="6" align="right">
                <button type='button' align="center" class='btn btn-primary'  onclick='Actualizar(); '><span class='glyphicon glyphicon-repeat'></span> Actualizar </button>
                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntDatosFijosResultadosExamen.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
            </td>
    </tr>
    <?php 
                }
                
                ?>
        
	</table>

</form>


