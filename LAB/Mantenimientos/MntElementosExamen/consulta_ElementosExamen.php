<?php session_start();
include_once("clsElementosExamen.php");
include('../Lab_Areas/clsLab_Areas.php');
$objeareas=new clsLab_Areas;
$obj = new clsElementosExamen;

$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];

//consulta los datos por su id
$idelemento=$_POST['idelemento'];

$consulta=$obj->consultarid($idelemento,$lugar);
$row = pg_fetch_array($consulta);

//valores de las consultas
$codexamen=$row['codexamen'];
$subelemento=$row['subelemento'];
$nombreelemento=$row['elemento'];
$cod=$row['cod'];
$unidadele=$row['unidadelem'];
$observacionele=$row['observelem'];
$idexamen=$row['idexamen'];
$idarea=$row['idarea'];
$nombreexamen=$row['nombre_examen'];
$nombrearea=$row['nombrearea'];
$Fechaini=$row['fechaini'];
$Fechafin=$row['fechafin'];
//echo $Fechaini." - ".$Fechafin; 
$orden=$row['orden'];

//echo $Fechaini."".$Fechafin;
//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" >
  <input name="opcion" type="hidden" value="N" />
  <table width="45%" border="0" align="center" class="StormyWeatherFormTABLE">
		<tr>
                    <td colspan="4" align="center" class="CobaltFieldCaptionTD"><h3><strong>Elementos de  Examen</h3></strong>
                    </td>
		</tr>	
		<tr>
                    <td width="17%" class="StormyWeatherFieldCaptionTD">&Aacute;rea
			<input name="idelemento" id="idelemento" type="hidden" value="<?php echo $idelemento ?>" />
                        <input name="idexamen" id="idexamen" type="hidden" value="<?php echo $codexamen ?>" />
                        <input name="cod" id="cod" type="hidden" value="<?php echo $cod ?>" />
                        <input name="txtexamen" id="txtexamen" type="hidden" value="<?php echo $nombreexamen ?>" />
                    </td>
                    <td width="83%" class="StormyWeatherDataTD" colspan="3">
			<select id="cmbArea" name="cmbArea" style="width:50%" onChange="MostrarExamenes(this.value);" class="form-control height">
                            <option value="0" >--Seleccione un &Aacute;rea--</option>
				<?php
				
				$consulta= $objeareas->consultar();
				while($row = pg_fetch_array($consulta)){
					echo "<option value='" . $row['id']. "'>" . htmlentities($row['nombrearea']) . "</option>";
				}
				echo "<option value='" . $idarea . "' selected='selected'>" .htmlentities($nombrearea). "</option>";
				?>		  
				</select>		  
		    </td>
                </tr>
                <tr>
		    <td width="17%" class="StormyWeatherFieldCaptionTD">Examen </td>
                    <td width="83%" class="StormyWeatherDataTD" colspan="3">
                        <select id="cmbExamen" name="cmbExamen" style="width:50%" class="form-control height">
                            <option value="0">--Seleccione un Examen--</option>
				<?php
				$consultaex = $obj->ExamenesPorArea($idarea);
				while($row = pg_fetch_array($consultaex))
				{
					echo "<option value='" . $row['id']. "'>" . $row['nombreexamen'] . "</option>";
				}						            	
					echo "<option value='" . $idexamen . "' selected='selected'>" .htmlentities($nombreexamen). "</option>";
				?>	
			</select>
                    </td>
		</tr>
		<tr>
                    <td class="StormyWeatherFieldCaptionTD">Elemento</td>
		    <td class="StormyWeatherDataTD" colspan="3"><textarea name="txtelemento" cols="75" rows="2" id="txtelemento"><?php echo htmlentities($nombreelemento); ?></textarea>              
                </tr>
		<tr>
		    <td class="StormyWeatherFieldCaptionTD">Unidad</td>
		    <td class="StormyWeatherDataTD" colspan="3"><input name="txtunidadele" type="text" id="txtunidadele" value="<?php echo htmlentities($unidadele); ?>" size="15"></td>
                </tr>
		<tr>
		    <td class="StormyWeatherFieldCaptionTD">Observci&oacute;n o Interpretaci&oacute;n</td>
		    <td class="StormyWeatherDataTD" colspan="3"><textarea name="txtobservacionele" cols="75" rows="4"><?php echo htmlentities($observacionele); ?></textarea>
		</tr>
		<tr>
		    <td width="17%" class="StormyWeatherFieldCaptionTD">SubElementos</td>
		    <td width="83%" class="StormyWeatherDataTD" colspan="3">
                        <select id="select" name="cmbSubElementos" style="width:50%" class="form-control height" >
                            <option value="0" >--Seleccione--</option>
                            <option value="S" >Si</option>
                            <option value="N" >No</option>
				<?php
					if ($subelemento =="S")
						{echo "<option value='".$subelemento."' selected='selected'>SI</option>";}
					else
						{echo "<option value='" . $subelemento . "' selected='selected'>NO</option>";}
			  
				?>
				</select>
			</td>
	    </tr>
		<tr>
                            <td class="StormyWeatherFieldCaptionTD" style="width:150px">Fecha Inicio *</td>
                            <td  class="StormyWeatherDataTD">
                               <input  name="txtFechainicio1" type="text" id="txtFechainicio1" size="20" class="date form-control height placeholder"  placeholder="aaaa-mm-dd" style="width:75%"  value="<?php echo $Fechaini; ?>"/>		  
                                 
			
                            </td>      
                            <td class="StormyWeatherFieldCaptionTD" style="width:150px" >Fecha Final</td>
                            <td  class="StormyWeatherDataTD">
                                <input name="txtFechaFin1" type="text" id="txtFechaFin1" size="20" class="date form-control height placeholder"  placeholder="aaaa-mm-dd" style="width:75%" value="<?php echo $Fechafin; ?>" />
                            </td>      
                        </tr>   
                <tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Orden </td>
                            <td width="83%"  class="StormyWeatherDataTD" colspan="3"> <div id="divRango">
                                <select   name="cmborden"  id="cmborden"  style="width:50%"  class="form-control height" > 
                                    <option value="0">--Seleccione un Orden--</option>
                                    <?php 
                                 $datosDB=0;
                                   // echo "<option value='" . $orden . "' selected='selected'>" .$orden. "</option>";
                                $conorden = $obj->llenarrangoele($idexamen);
                                while($row = pg_fetch_array($conorden)){
                                	if($row['orden'] === $orden){
                                		echo "<option value='" . $orden . "' selected='selected'>" .$orden. "</option>";
                                        } else{
                                            
                                            $datosDB=$obj->existeOrdenele($idexamen);
                                            for ($index = 1 ; $index <=25; $index++) 
                                                    {
                                                      $rest=areglo ($datosDB,$index);
                                                      if($rest==0){
                                                        echo '<OPTION VALUE="'.$index.'">'.$index.'</OPTION>';  
                                                      }


                                            }
                                        }    
                                         
                                    	                                     
                                   }
                             
                                    ?> 
                                </select>
                           </td>		
		</tr>
        <tr>
            <td colspan="4" class="StormyWeatherDataTD" align="right">
				<button type="button"  value="Actualizar" class="btn btn-primary" onClick="enviarDatos();"><span class="glyphicon glyphicon-repeat"></span> Actualizar </button>
                                
				<!--<button name="Submit2"  class="btn btn-primary" value="Buscar" Onclick="Buscar() ;"><span class='glyphicon glyphicon-search'></span>  Buscar </button>-->
				<button name="Submit2" value="Nuevo" class="btn btn-primary" onClick="window.location.replace('MntElementosExamen.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>			
			   	<input type="button" name="btnSubElementos"  class="btn btn-primary"value="SubElementos" Onclick="MostrarSubElementos() ;">   
			</td>
        </tr>
         <?php
        function existeOrden($idexamen){
          $respuesta=0;
          //$objdatos = new clsElementosExamen;
          $consulta=$obj->llenarrangoele($idexamen);
          $hola=array();                      
                                while ($row=pg_fetch_array($consulta))
                                    {
                                       /* if($row['orden']==$index)
                                        {
                                            $respuesta=1;
                                        }else{
                                           $respuesta=0; 
                                        }
                                        echo $row['orden'];  */
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

