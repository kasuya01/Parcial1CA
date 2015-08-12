<?php session_start();
include_once("clsLab_metodologia.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idmetodologia=$_POST['idmetodologia'];
//echo $iddatosfijosexamen;
//echo $iddatosfijosexamen;
$obj = new clsLab_metodologia();

$consulta1=$obj->consultarhabilitado($idmetodologia);
$row = pg_fetch_array($consulta1);
$habilitado=$row['activo'];


$consulta=$obj->consultarid($idmetodologia,$lugar);
$row = pg_fetch_array($consulta);
//$iddatosfijosexamen;
//valores de las consultas
$idarea=$row['id_area'];
//echo $idarea;
$nombrearea=$row['nombrearea'];
$idexamen=$row['id_examen'];
$nombreexamen=$row['nombre_examen'];
$nombrearea=$row['nombrearea'];
$nombrereporta=$row['nombre_reporta'];

$Fechaini=$row['fecha_inicio'];
$Fechafin=$row['fecha_fin'];
$b_reporta=$row['b_reporta'];
if ($b_reporta=='t'){
    $reporta='true';
}
 else {
    $reporta='false';
}

//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" action="">
    <table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
        <tr>
            <td colspan=4" align="center" class="CobaltFieldCaptionTD"><h3><strong>Configuración de metodología</h3></strong>
            </td>
	</tr>
	<tr>
            <td width="17%" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
            <td width="83%" class="StormyWeatherDataTD" colspan="3">
               <input type="text" readonly="readonly" class="form-control height"  style="width:75%" id="Area" name="Area" value="<?php echo $nombrearea?>"
               <input type="hidden" class="form-control height" id="cmbArea" name="cmbArea" value="<?php echo $idarea?>"

            </td>
        </tr>
        <tr>
            <td width="17%" class="StormyWeatherFieldCaptionTD">Examen </td>
            <td width="83%" class="StormyWeatherDataTD" colspan="3">
               <input type="text" disabled class="form-control height" id="Examen" name="Examen" style="width:75%" value="<?php echo $nombreexamen; ?>">
               <input type="hidden" id="cmbExamen" name="cmbExamen" value="<?php echo $idexamen; ?>">
<!--                <select id="cmbExamen" name="cmbExamen" size="1">
                    <option value="0">--Seleccione un Examen--</option>";
                    <?php
//                        $consultaex = $obj->ExamenesPorArea($idarea,$lugar);
//                        while($row = pg_fetch_array($consultaex))
//                        {
//                            echo "<option value='" . $row['idexamen']. "'>" . $row['nombreexamen'] . "</option>";
//                        }						            	
//                            echo "<option value='" . $idexamen . "' selected='selected'>" .$nombreexamen. "</option>";
                    ?>	
		</select>-->
            </td>
        </tr>
        <tr>
            <td width="17%" class="StormyWeatherFieldCaptionTD">Metodología</td>
            <td class="StormyWeatherDataTD" colspan="3">
               <input type="text" readonly="readonly" class="form-control height"  style="width:75%" id="metodologia" name="metodologia" value="<?php echo $nombrereporta?>"/>
               <input type="hidden" class="form-control height" id="cmbMetodologia" name="cmbMetodologia" value="<?php echo $idmetodologia?>"/>

            </td>
        </tr>
        <tr>
            <td width="17%" class="StormyWeatherFieldCaptionTD">Reporta</td>
            <td class="StormyWeatherDataTD" colspan="3">
               <select id="cmbreporta" name="cmbreporta" size="1" class="form-control height"  style="width:75%">   
                    <?php
                    if ($b_reporta=='t'){
                       echo ' <option value="true" selected >Si</option>
                        <option value="false" >No</option>  ';
                    }
                     else {
                          echo '<option value="true" >Si</option>
                        <option value="false" selected >No</option>  ';
                     }
                   
                    ?>                      
               </select>		  
             </td>        
        </tr>
           <tr>
                        <td width="17%" class="StormyWeatherFieldCaptionTD">Posibles Resultados</td>
                        
                        
                        <td class="StormyWeatherDataTD" colspan="3">
                           <div id="posresultprevios">
                            <input type="hidden" name="posresultados_sel" id="posresultados_sel" value="">
                            <input type="hidden" name="text_posresultados_sel" id="text_posresultados_sel">
                            <input type="hidden" name="id_posresultados_sel" id="id_posresultados_sel">
                           </div>
                            <button type='button' class='btn btn-default'  name="add_posresultado" id="add_posresultado" style="width:75%; text-align: left" onclick="popup('consulta_posresultados.php?form=frmModificar&posresultados_sel='+document.getElementById('posresultados_sel').value+
                                        '&text_posresultados_sel='+document.getElementById('text_posresultados_sel').value+
                                        '&nombre='+$('#cmbMetodologia').text()+ '&id_posresultados_sel='+document.getElementById('id_posresultados_sel').value+'&actualizar='+1);"><span class='glyphicon glyphicon-th-list'></span> ..:Seleccionar Posibles Resultados:..</button>
                           
                        </td>      
                     </tr>                    
                     <tr>
                        <td class="StormyWeatherFieldCaptionTD">Fecha Inicio *</td>
                        <td  class="StormyWeatherDataTD">
                            <input  name="txtFechainicio" type="text" id="txtFechainicio" size="25" class="date form-control  height placeholder"  placeholder="aaaa-mm-dd" style="width:100%"  value="<?php echo $Fechaini ?>"/>		  
			</td>      
                          <td class="StormyWeatherFieldCaptionTD"  width="17%">Fecha Final</td>
                        <td  class="StormyWeatherDataTD">
                           <input name="txtFechaFin" type="text" id="txtFechaFin" size="28" class="date form-control height placeholder"  placeholder="aaaa-mm-dd" style="width:100%" value="<?php echo $Fechafin?>" />
			</td>      
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
                
                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntLab_metodologia.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
            </td>
        </tr>
        
        <?php        
                } else {
                  ?>
        
        <tr>  
            <td class="StormyWeatherDataTD" colspan="6" align="right">
                <button type='button' align="center" class='btn btn-primary'  onclick='Guardar(); '><span class='glyphicon glyphicon-repeat'></span> Actualizar </button>
                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntLab_metodologia.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
            </td>
    </tr>
    <?php 
                }
                
                ?>
        
	</table>

</form>


