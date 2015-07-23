<?php session_start();
include('../Lab_Areas/clsLab_Areas.php');
include_once("clsLab_Procedimientos.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idproce=$_POST['idproce'];

 $idproce;
$obj = new clsLab_Procedimientos;


$consulta1=$obj->consulhabilitado($idproce);
$row = pg_fetch_array($consulta1);
$habilitado=$row['habilitado'];

//echo $habilitado;

$consulta=$obj->consultarid($idproce,$lugar);
$row = pg_fetch_array($consulta);

//valores de las consultas
//$nombreelemento=$row['elemento'];

$orden=$row['orden'];
$idarea=$row['idarea'];
$nombrearea=$row['nombrearea'];
$idexamen=$row['idexamen'];
$nombreexamen=$row['nombreexamen'];
$proce=$row['nombreprocedimiento'];
$unidades=$row['unidades'];
$rangoini=$row['rangoinicio'];
$rangofin=$row['rangofin'];
$Fechaini=$row['fechaini'];
/*list($anio, $mes, $dia) = split('[/.-]', $Fechaini);
$Fechaini = $anio . "/" . $mes . "/" . $dia;*/

$Fechafin=$row['fechafin'];
/*if($Fechafin !== null) {
	list($anio, $mes, $dia) = split('[/.-]', $Fechafin);
	$Fechafin = $anio . "/" . $mes . "/" . $dia;*/
//}
$idsexo=$row['idsexo'];
$nombresexo=$row['sexovn'];
$idedad=$row['idedad'];
$rangoedad=$row['nombregrupoedad'];
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
			<select id="cmbArea" name="cmbArea" size="1"  style="width:50%"  class="form-control height" onChange="LlenarComboExamen(this.value);">
				<option value="0" >--Seleccione un &Aacute;rea--</option>
				<?php
					$objeareas=new clsLab_Areas;
                    $consulta= $objeareas->consultaractivas($lugar);
                    while($row = pg_fetch_array($consulta)){
                        if($row['idarea'] === $idarea)
	               			echo "<option value='" . $idarea . "' selected='selected'>" .htmlentities($nombrearea). "</option>";
	               		else
				 			echo "<option value='" . $row['idarea']. "'>" .htmlentities($row['nombrearea']) . "</option>";
                    }
				?>
           	</select>		  
		</td>
    	</tr>
    	<tr>
        	<td width="17%" class="StormyWeatherFieldCaptionTD">Examen </td>
        	<td width="83%" class="StormyWeatherDataTD">
                <select id="cmbExamen" name="cmbExamen" size="1"  style="width:50%"  class="form-control height">
            		<option value="0">--Seleccione un Examen--</option>
	           		<?php
						$consultaex = $obj->ExamenesPorArea($idarea, $lugar);
						while($row = @pg_fetch_array($consultaex)) {
							if($row['idexamen'] === $idexamen)
								echo "<option value='" . $idexamen . "' selected='selected'>" .$nombreexamen. "</option>";
							else
								echo "<option value='" . $row['idexamen']. "'>" . $row['nombreexamen'] . "</option>";
						}
					?>
		    	</select>
            </td>
    	</tr>
       <tr>
            <td width="17%" class="StormyWeatherFieldCaptionTD">Sexo</td>
            <td width="83%"  class="StormyWeatherDataTD">
                <select id="cmbSexo" name="cmbSexo" size="1"  style="width:50%"  class="form-control height" >
                    <option value="0" >--Seleccione Sexo--</option>
                    <?php
                        $consultaS= $obj->consultarsexo();
                        while($row =pg_fetch_array($consultaS)){
                        	if($row['id'] === $idsexo)
                        		echo "<option value='" . $idsexo . "' selected='selected'>" .$nombresexo. "</option>";
                        	else
                            	echo "<option value='" . $row['id']. "'>". $row['nombre'] . "</option>";
                        }

                        if($idsexo === "NULL")
                        	echo "<option value='" . $idsexo . "' selected='selected'>" .$nombresexo. "</option>";
                        else
                        	echo "<option value='NULL'>Ambos</option>";
                    ?>        
                </select>		  
             </td>        
        </tr>
         <tr>
                <td width="17%" class="StormyWeatherFieldCaptionTD">Rango Edad</td>
                <td width="83%"  class="StormyWeatherDataTD">
                    <select id="cmbEdad" name="cmbEdad" size="1"  style="width:50%"  class="form-control height" >
                        <option value="0" >--Seleccione un Rango de Edad--</option>
                            <?php
                                $conEdad = $obj->RangosEdades();
                                while($row = pg_fetch_array($conEdad)){
                                	if($row['id'] === $idedad)
                                		echo "<option value='" . $idedad . "' selected='selected'>" .$rangoedad. "</option>";
                                	else
                                    	echo "<option value='" . $row['id']. "'>". $row['nombre'] . "</option>";
                                }
                            ?>    
                    </select>		  
                </td>        
        </tr>
	<tr>
	    	<td width="17%" class="StormyWeatherFieldCaptionTD">Procedimiento </td>
	    	<td width="83%" class="StormyWeatherDataTD"><input name="txtproc" type="text" id="txtproc" style="width:250px"   class="form-control height placeholder" value="<?php echo htmlentities($proce); ?>" size="50"></td>
	</tr>
	<tr>
	    	<td width="17%" class="StormyWeatherFieldCaptionTD">Unidades</td>
	    	<td width="83%" class="StormyWeatherDataTD"><input name="txtunidades" type="text" id="txtunidades" style="width:250px"   class="form-control height placeholder" value="<?php echo htmlentities($unidades); ?>" size="10">		  </td>
	
        <tr>
                <td width="17%" class="StormyWeatherFieldCaptionTD">Orden</td>
                    <td width="83%"  class="StormyWeatherDataTD">
                        <div id="divRango">
                            <select   name="cmborden"  id="cmborden"  style="width:50%"  class="form-control height" > 
                                <option value="0">--Seleccione un Orden--</option>
                                        <!--disabled="disabled"-->
                                            <?php
                                $conEdad = $obj->Rangos($idproce);
                                while($row = pg_fetch_array($conEdad)){
                                	//if($row['orden'] === $orden){
                                		echo "<option value='" . $orden . "' selected='selected'>" .$orden. "</option>";
                                       // } else
                                         /*   $datosDB=0;
                                             $datosDB=existeOrden($idexamen);*/
                                                for ($index = 1 ; $index <=25 ; $index++) 
                                                    {
                                                      //$rest=areglo ($datosDB,$index);
                                                     // if($rest==0){
                                                      if($index<>$orden){    
                                                        echo '<OPTION VALUE="'.$index.'">'.$index.'</OPTION>';  
                                                      }


                                                    }
                                            
                                         
                                    	                                     
                                }
                            ?>    
                                </select>
		        </div>
                </td>
        </tr>    
                                           
                                           
                    

        </tr>
          <tr>
              <?php  
                if ($habilitado=="f"){
                    
                    ?>llenarrangosubele(
               <td nowrap class="StormyWeatherFieldCaptionTD">Posibles Resultado </td>
                         <?php
                         echo "<td class='StormyWeatherDataTD'>
                                    <button type='button' align='center' disabled='disabled' class='btn btn-default'  onclick='popup(".'"consulta_SubElemento_1.php?idproce='.$idproce.'"'.")' >  <span class='glyphicon glyphicon-th-list'></span>  ..:Seleccionar Resultado:.. </button>
                                    </td>"; ?>
               <?php        
                } else {
                  ?> 
               <td nowrap class="StormyWeatherFieldCaptionTD">Posibles Resultado </td>
                         <?php
                         echo "<td class='StormyWeatherDataTD'>
                                    <button type='button' align='center' class='btn btn-default'  onclick='popup(".'"consulta_SubElemento.php?idproce='.$idproce.'"'.")' >  <span class='glyphicon glyphicon-th-list'></span>  ..:Seleccionar Resultado:.. </button>
                                    </td>"; ?>
               <?php 
                }
                
                ?> 
                       
         </tr>
        
        
        
        
    	<tr>
        	<td colspan="2" class="StormyWeatherDataTD">
			
			<table width="850" border="0" align="center" class="StormyWeatherFormTABLE">
			<tr>
				<td width="19%" class="StormyWeatherFieldCaptionTD">Rango Inicio</td>
                                    <input type="hidden" name="txtoculto" id="txtoculto" value="<?php echo $idproce; ?>" />
				<td  class="StormyWeatherDataTD">
                                        <input name="txtrangoinicio" type="text" id="txtrangoini" style="width:260px"   class="form-control height placeholder" value="<?php echo $rangoini; ?>" size="28">
				</td>
				<td width="17%" class="StormyWeatherFieldCaptionTD">Rango Fin </td>
				<td class="StormyWeatherDataTD">
                                    <input name="txtrangofin" type="text" id="txtrangofin" style="width:280px"   class="form-control height placeholder" value="<?php echo $rangofin; ?>" size="28">
                                </td>
			</tr>
			</table>
			             
		</td>
    	</tr>
    	<tr>
        	<td colspan="2" class="StormyWeatherDataTD">
				<table width="850" border="0" align="center" class="StormyWeatherFormTABLE">
				<tr>
					<td width="19%" class="StormyWeatherFieldCaptionTD">Fecha Inicio</TD>
					<td  class="StormyWeatherDataTD">
						<input name="txtFechainicio1" type="text" id="txtFechainicio1" style="width:260px"   class="form-control height placeholder" value="<?php echo $Fechaini; ?>" size="28" >
                                        </td>
					<td width="17%" class="StormyWeatherFieldCaptionTD">Fecha Final</D>
					<td  class="StormyWeatherDataTD">
                                         <?php  if($Fechafin==""){
                                             ?>
                                            <input name="txtFechaFin1" type="text" id="txtFechaFin1" style="width:280px"   class="form-control height placeholder" placeholder="Ingrese Fecha  dd/mm/aaaa" size="28" >
                                              <?php 
                                            }else {
                                               ?>
                                              <input name="txtFechaFin1" type="text" id="txtFechaFin" style="width:280px"   class="form-control height placeholder" value="<?php echo $Fechafin; ?>" size="28" >
                                        
                                           <?php  } ?>
                                            
                                            
					<!--	<input name="txtFechaFin1" type="text" id="txtFechaFin" style="width:200px"   class="form-control height placeholder" value="<?php echo $Fechafin; ?>" size="28" >
                                        -->
                                        </td>
				</tr>
				</table>
			</td>		
		</tr>
    	<!--<tr>
        	<td class="StormyWeatherDataTD" colspan="2" align="right">
                        <input type="button" name="btnGuardar" value="Actualizar" onClick="Actualizar() ;">
			<input type="button" name="btnBuscar" value="Buscar" Onclick="Buscar() ;">
		    	<input type="button" name="btnCancelar" value="Cancelar" onClick="window.location.replace('MntProcedimientosExamen.php')">			
		</td>
        </tr>-->
                <?php  
                if ($habilitado=="f"){
                    
                    ?>
                
                 <tr>  
            <td class="StormyWeatherDataTD" colspan="6" align="right">
               
                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntProcedimientosExamen.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
            </td>
    </tr>
             <?php        
                } else {
                  ?>
    
        <tr>  
            <td class="StormyWeatherDataTD" colspan="6" align="right">
                <button type='button' align="center" class='btn btn-primary'  onclick='Actualizar(); '><span class='glyphicon glyphicon-repeat'></span> Actualizar </button>
                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntProcedimientosExamen.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                <!--
                <input type="button" name="btnSubElementos" value="SubElementos" Onclick="MostrarSubElementos() ;">  
                -->
                 </td>
        </tr>
    <?php 
                }
                
                ?> 
    
    
    
    <?php
    
    function existeOrden($idexamen){
          $respuesta=0;
          $objdatos = new clsLab_Procedimientos;
          $consulta=$objdatos->llenarrangoproc($idexamen);
          $hola=array();                      
                                while ($row=pg_fetch_array($consulta))
                                    {
                                        if($row['orden']==$index)
                                        {
                                            $respuesta=1;
                                        }else{
                                           $respuesta=0; 
                                        }
                                        echo $row['orden']; 
                                    $hola[]=$row['orden'];
                                    }
                                    
           return $hola;                        
        }
    function areglo ($arr,$dato){
        $respuesta=0;
        $max = sizeof($arr);
        for ($index = 0 ; $index<$max; $index++) 
            {
               if($dato<>$arr[$index]){
                   $respuesta=0;//no mostrar
              }else{
                    $respuesta=1;//si mostrar
                    $index=$max;
                    
               } 
            }
            return $respuesta;    
    }    
    
    ?>
    
</table>
</form>

