<?php session_start();
    include_once("clsLab_Examenes.php");
    $lugar=$_SESSION['Lugar'];
    $usuario=$_SESSION['Correlativo'];
    $area=$_SESSION['Idarea'];
    //consulta los datos por su id
    $idconf=$_POST['idexamen'];
   //echo $idconf;
    $obj = new clsLab_Examenes;
    $consulta = $obj->consultarid($idconf,$lugar);
    $row = pg_fetch_array($consulta);

    //valores de las consultas
    $codexamen=$row['idexamen'];
    $mntid=$row['mntid'];
    $idarea=$row['idarea'];
  //  $mntidarea=$row['mntidarea'];
    $nombreexamen=$row['nombreexamen'];
    $idestandar=$row['idestandar'];
    $nombrearea=$row['nombrearea'];
    $nombreestandar=$row['descripcion'];
    $plantilla=$row['idplantilla'];
  //  $ctlidestandar=$row['ctlidestandar'];
    $nomplantilla=$row['plantilla'];
   // $observacion=$row['Observacion'];
    $ubicacion=$row['idubicacion'];
    //$IdPrograma=$row['idPrograma'];
    $EstandarResp=$row['estandarrep'];
    $IdEstandarResp=$row['ctlidestandarrep'];
   // echo $IdEstandarResp;
    $Descestandarrep=$row['descestandarrep'];
    $IdFormulario=$row['idformulario'];
    $NombreForm=$row['nombreformulario'];
    $Impresion=$row['impresion'];
    $Urgente=$row['urgente'];
    $idsexo=$row['idsexo'];
    $nombresexo=$row['sexo'];
    $Hab=$row['condicion'];
    $TiempoPrevio=$row['rangotiempoprev'];
    $metodologias=$row['metodologias'];
    $metodologias_text=$row['metodologias_text'];
    $id_metodologias_text=$row['id_metodologias_text'];
    $id_posible_resultado=$row['id_posible_resultado'];
    $posible_resultado=$row['posible_resultado'];
    
    
    /*
     * quitar signos raros en la cadena de texto de la consulta CONCAT
     */
    $metodologias = str_replace('{','',$metodologias);
    $metodologias = str_replace('}','',$metodologias);
    
    $metodologias_text = str_replace('{','',$metodologias_text);
    $metodologias_text = str_replace('}','',$metodologias_text);
    $metodologias_text = str_replace('"','',$metodologias_text);
    
    $id_metodologias_text = str_replace('{','',$id_metodologias_text);
    $id_metodologias_text = str_replace('}','',$id_metodologias_text);
    $id_metodologias_text = str_replace('"','',$id_metodologias_text);
    
    if (empty($idsexo)){
        $idsexo=4;
        $nombresexo="Ambos";}
  // echo $TiempoPrevio;
        
 //  echo  $idsexo."-".$idconf."-".$plantilla;
//echo $idexamen."***".$lugar."****".$nombreexamen."***".$IdPrograma."***".$Programa."***".$IdFormulario."***".$NombreForm;
//muestra los datos consultados en los campos del formulario

//echo "al cargar ".$Impresion;
?>

<form name= "frmModificar" >
	<input name="opcion" type="hidden" value="N" />
	<table width="70%" border="0" align="center" class="StormyWeatherFormTABLE">
	    <tr>
                <td colspan="3" align="center" class="CobaltFieldCaptionTD"><strong><h3>Mantenimiento de Ex&aacute;menes de Laboratorio Cl&iacute;nico</h3></strong>
                </td>
            </tr>      
            <tr>
	       	<td class="StormyWeatherFieldCaptionTD">C&oacute;digo del Examen</td>
	       	<td class="StormyWeatherDataTD"><input type="text" name="txtidexamen" value="<?php echo $codexamen; ?>" disabled="disabled"  style="width:250px" class="form-control height placeholder"/></td>
	    </tr>
            <tr>
	       	<td class="StormyWeatherFieldCaptionTD">C&oacute;digo del &Aacute;rea</td>
	       	<td class="StormyWeatherDataTD">
			<select name="cmbArea" size="1"  style="width:75%" style="width:250px" class="form-control height">			 
                            <?php
				/*include('../Lab_Areas/clsLab_Areas.php');
				$objeareas = new clsLab_Areas;
				$consulta= $objeareas->consultaractivas($lugar);
				while($row = pg_fetch_array($consulta))
				{
					echo "<option value='" . $row['idarea']. "'>" . $row['nombrearea'] . "</option>";
				}*/						            	
				echo "<option value='" . $idarea . "' selected='selected'>" .htmlentities($nombrearea). "</option>";
                            ?>		  
	          	</select>
		</td>
	    </tr>
             <tr>
                            <td class="StormyWeatherFieldCaptionTD" >C&oacute;digo del Est&aacute;ndar</td>
                            <td class="StormyWeatherDataTD">
                                 <select name="cmbEstandar" id="cmbEstandar"  style="width:75%" class="form-control height"> 
                                     <?php
                                       /*  $consultaex= $obj->ExamenesPorArea($mntidarea,$lugar);
                                         while($row = pg_fetch_array($consultaex))
				           {
					     echo "<option value='" .$rows['id']."' >".$rows['idestandar']." - ".htmlentities($rows['descripcion'])."</option>";
                                           }*/
                                          echo "<option value='" . $mntid . "' selected='selected'>" . $idestandar . "-" .htmlentities($nombreestandar). "</option>"; 
                                     ?>
                                 </select>   
                            </td>
            </tr>
	    <tr>
	       	<td class="StormyWeatherFieldCaptionTD">Nombre del Examen </td>
	        <td class="StormyWeatherDataTD">
                    <input type="text" name="txtnombreexamen" style="width:75%" class="form-control height placeholder" size="50" value="<?php echo htmlentities($nombreexamen); ?>"/>
                    <input type="hidden" name="txtidconf" size="50" style="width:75%" class="form-control height placeholder" value="<?php echo $idconf ; ?>"/>
                    <input type="hidden" name="txtctlidestandar" size="50" value="<?php //echo $ctlidestandar ; ?>"/>
                </td>
	   </tr>
           <tr>
                <td class="StormyWeatherFieldCaptionTD">Plantilla</td>
                <td class="StormyWeatherDataTD">
                    <select id="cmbPlantilla" name="cmbPlantilla" size="1"  style="width:75%" class="form-control height">
                        <option value="0">--Seleccione una Plantilla--</option>
                            <?php
                            	include_once('clsLab_Examenes.php');
				$obje=new clsLab_Examenes;
				$consulta= $obje->LeerPlantilla();
				while($row = pg_fetch_array($consulta)){
			  		echo "<option value='" . $row[0]. "'>" .htmlentities($row[1]). "</option>";
				}
				echo "<option value='" . $plantilla . "' selected='selected'>" .$nomplantilla. "</option>";
                            ?>
	            </select>
		</td>
            </tr>
            <tr>
	         <td class="StormyWeatherFieldCaptionTD"><strong>Solicitado en </strong></td>
	         <td class="StormyWeatherDataTD">
                    <select id="cmbUbicacion" name="cmbUbicacion" size="1"  style="width:75%" class="form-control height">
			<?php
                            if ($ubicacion=='0'){
                                echo "<option value='".$ubicacion."' selected='selected'> Todas las procediencias </option>";
				echo "<option value='1' >Hospitalización y Emergencia</option>";
                                echo "<option value='4' >Laboratorio</option>";
                            }
                            if ($ubicacion=='1'){
                            	echo "<option value='".$ubicacion."' selected='selected'>Hospitalización y Emergencia</option>";
				echo "<option value='0' >Todas las procediencias</option>
                                      <option value='4' >Laboratorio</option>";
                            }
                             if ($ubicacion=='4'){
                            	echo "<option value='".$ubicacion."' selected='selected'>Laboratorio</option>";
				echo "<option value='0'>Todas las procediencias</option>
                                      <option value='4'>Hospitalización y Emergencia</option>";
                            }
                            
			?>
                    </select>
                  </td>
               </tr>
               <tr>
                    <td width="17%" class="StormyWeatherFieldCaptionTD">Formulario para Examen</td>
                    <td class="StormyWeatherDataTD">
			<div id="divFormulariosC">
                        	<select name="cmbConForm" size="1" id="cmbConForm"  style="width:75%" class="form-control height"> ";
                                 <?php 
                                 $consulta= $obj->consultar_formularios($lugar);
                                            while($row = pg_fetch_array($consulta)){
                                                echo "<option value='" . $row['0']. "'>" .$row['1'] . "</option>";
                                            }
                                 if (!empty($IdFormulario))	
                                            echo "<option value='" . $IdFormulario . "' selected='selected'>".htmlentities($NombreForm)."</option>";
                                       else
                                            echo "<option value='0' selected='selected'>Ninguno</option>";?>
				</select>
			</div>
                    </td>
		</tr>
               
               
		<tr>
                    <td class="StormyWeatherFieldCaptionTD" >C&oacute;digo en tabulador</td>
                    <td class="StormyWeatherDataTD">
                        <select id="cmbEstandarRep" name="cmbEstandarRep" size="1"  style="width:75%" class="form-control height">
                            <option value="0">--Seleccione un Est&aacute;ndar--</option>
                            	<?php
                                	$consulta= $obj->consultar_codigospruebas();
				while($row = pg_fetch_array($consulta)){
					echo "<option value='" . $row['0']. "'>" . $row['1'].'- '.htmlentities($row['2']). "</option>";
				}
                                                  $obje1=new clsLab_Examenes;
                                                 // $consulta2=$obje1->Obtener_NombreEstandar($IdEstandarResp);
                                                  //$rownom= pg_fetch_array($consulta2);
                                                  //$NombreEstandar=$rownom[0];
                                           echo "<option value='".$IdEstandarResp."' selected='selected'>".$EstandarResp.'-'.htmlentities($Descestandarrep). "</option>";		
                                                ?>	
			</select>   
                    </td>
		</tr>
		<tr>	
                    <td class="StormyWeatherFieldCaptionTD" >Tipo Etiqueta</td>
                    <td class="StormyWeatherDataTD">
                        <select id="cmbEtiqueta" name="cmbEtiqueta" size="1" style="width:75%" class="form-control height">
			<?php  
                            if (empty($Impresion)){
                                    $Impresion=0;
                                    echo "<option value=0 selected='selected'>Seleccione un tipo de Etiqueta</option>";
                                    echo "<option value='O'>Especial</option>";
                                    echo "<option value='G'>General</option>";
                             }
                            else if ($Impresion=="G"){
                                    echo "<option value='".$Impresion."' selected='selected'>General</option>";
                                    echo "<option value='O'>Especial</option>";
                            }
                            else if ($Impresion<>"G"){
                             //echo "combo".$Impresion;
                                    echo "<option value='O' selected='selected'>Especial</option>";
                                    echo "<option value='G'>General</option>";
                            }
			?>
			</select>
                    </td>
		</tr>
                <tr>
                    <td class="StormyWeatherFieldCaptionTD">Examen Solicitado en Urgente:</td>
                    <td class="StormyWeatherDataTD">
                        <select id="cmbUrgente" name="cmbUrgente" size="1"  style="width:75%" class="form-control height">
                            <?php  
				if ($Urgente=="0"){
                                    echo "<option value='0' selected='selected'>No</option>";
                                    echo "<option value='1'>Si</option>";
                                }
                                if ($Urgente=="1"){
                                    echo "<option value='1' selected='selected'>Si</option>";
                                    echo "<option value='0'>No</option>";
                                }
                            ?>
                        </select>
                      </td>
                </tr>
                <tr>
                      <td class="StormyWeatherFieldCaptionTD">Sexo al que se le realiza la prueba</td>
                      <td class="StormyWeatherDataTD">
                         <select id="cmbsexo" name="cmbsexo" size="1" style="width:75%" class="form-control">
                            
                             <option value="4">Ambos</option>
                                <?php
                                    $obje1=new clsLab_Examenes;
                                    $consulta= $obje1->catalogo_sexo();
                                    while($row = pg_fetch_array($consulta)){
                                        echo "<option value='" . $row['id']. "'>" .$row['nombre'] . "</option>";
                                    }
                                    echo "<option value='".$idsexo."' selected='selected'>".htmlentities($nombresexo). "</option>";
                                    
				?>
                         </select>    
                      </td>
                </tr>
                <tr>
                      <td class="StormyWeatherFieldCaptionTD">Habilitar Prueba</td>
                      <td class="StormyWeatherDataTD"> 
                          <select id="cmbHabilitar" name="cmbHabilitar" size="1"  style="width:75%" class="form-control height">
                                <option value="0">-- Seleccione Condici&oacute;n --</option>
                                 <?php
                                    if ($Hab=='H'){
                                      echo "<option value='".$Hab."' selected='selected'>Habilitado </option>";
                                      echo "<option value='I' >Inhabilitado</option>";

                                    }
                                    else{ 
                                      echo "<option value='".$Hab."' selected='selected'>Inhabilitado</option>";
                                      echo "<option value='H' >Habilitado</option>";

                                    }
                                ?>
                         </select>    
                       
                      </td>
                </tr>
                <tr> 
                            <td nowrap class="StormyWeatherFieldCaptionTD">Tiempo Previo para <br>entrega de resultado(en dias)&nbsp;</td>
                            <td class="StormyWeatherDataTD">
                            <input id="inidates" name="inidate" class="form-control" value="<?php echo $TiempoPrevio;?>"style="width:250px; height:28px;" maxlength=3 onkeypress='return isNumberKey(event);' ></td>
                </tr>
                <tr>
                    <td nowrap class="StormyWeatherFieldCaptionTD">Metodologías</td>
                    <td class="StormyWeatherDataTD">
                        <input type="hidden" name="metodologias_sel" id="metodologias_sel" value="<?php print $metodologias; ?>">
                        <input type="hidden" name="text_metodologias_sel" id="text_metodologias_sel" value="<?php print $metodologias_text; ?>">
                        <input type="hidden" name="id_metodologias_sel" id="id_metodologias_sel" value="<?php print $id_metodologias_text; ?>">
                        <button type='button' class='btn btn-default' name="add_metodologia" id="add_metodologia" style="width:250px; text-align: left;" onclick="popup('consulta_metodologias.php?form=frmModificar&metodologias_sel='+frmModificar.metodologias_sel.value+'&id_examen=<?php print $idconf; ?>'+'&text_metodologias_sel='+frmModificar.text_metodologias_sel.value+'&id_metodologias_sel='+frmModificar.id_metodologias_sel.value);"><span class='glyphicon glyphicon-th-list'></span> ..:Seleccionar Metodologías:..</button>
<!--                        <input type="button" value="..." name="add_metodologia" id="add_metodologia" onclick="popup('consulta_metodologias.php?form=frmModificar&metodologias_sel='+frmModificar.metodologias_sel.value+'&id_examen=<?php  //print $idconf; ?>'+'&text_metodologias_sel='+frmModificar.text_metodologias_sel.value+'&id_metodologias_sel='+frmModificar.id_metodologias_sel.value);"/>-->
                </tr>
                <tr>
                                <td nowrap class="StormyWeatherFieldCaptionTD">Posibles Resultado </td>
                                <td class='StormyWeatherDataTD'>
                                  <input type="hidden" name="resultado" id="resultado" value="<?php print $id_posible_resultado; ?>">
                            <input type="hidden" name="resultado_nombre" id="resultado_nombre" value="<?php print $posible_resultado; ?>">
                            <input type="hidden" name="id_resultado" id="id_resultado">
                            <button type='button' class='btn btn-default' name="add_presultado" id="add_presultado" style="width:250px; text-align: left;" onclick="
                                popup('consulta_metodologias1.php?form=frmModificar&resultado='+frmModificar.resultado.value+
                                        '&resultado_nombre='+frmModificar.resultado_nombre.value+
                                        '&nombre='+$('input[name=txtnombreexamen]').val()+ '&id_resultado='+frmModificar.id_resultado.value);"><span class='glyphicon glyphicon-th-list'></span> ..:Seleccionar Resultado:..</button>
                                
                                 <?php
//                                 echo "<button type='button' style='width:250px; text-align:left;' name='add_presultado' id='add_presultado' align='center'  class='btn btn-default'  onclick='popup(".'"consulta_SubElemento1.php?idconf='.$idconf.'"'.")' >  <span class='glyphicon glyphicon-th-list'></span>  ..:Seleccionar Resultado:.. </button>
//                                            "; ?>
                                
                              </td>
                        </tr>       
		<tr>
                    <td colspan="2" align="right" class="StormyWeatherDataTD">
                        <!--<input type="button" name="btnActualizar" value="Actualizar" onclick="Modificar();" />
		   	<input type="button" name="btnNuevo" value="Nuevo" onClick="window.location.replace('MntExamenes.php')" />
                        -->
                          <button type='button' align="center" class='btn btn-primary'  onclick="Modificar();"><span class='glyphicon glyphicon-search'></span>  Actualizar </button>
                          <button type='button' align="center" class='btn btn-primary'  onClick="window.location.replace('MntExamenes.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                    </td>
                </tr>
	</table> 
</form>
