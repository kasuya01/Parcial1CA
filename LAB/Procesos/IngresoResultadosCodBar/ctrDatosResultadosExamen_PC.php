<?php session_start();
//variables de SESSION
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsConsultarElementosPlantillaC.php");

//variables POST
$opcion=$_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsConsultarElementosPlantillaC;
   
switch ($opcion) 
{
  case 1:  //MOSTRANDO ANTIBIOTICOS ASOCIADOS A LA PLANTILLA
	$idtarjeta=$_POST['idtarjeta'];
	$idexamen=$_POST['idexamen'];

     
	$consulta=$objdatos->LeerAntibioticos($idtarjeta);
	 $pos=0;
	$imprimir="<table width='60%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td width='40%' class='StormyWeatherFieldCaptionTD'> CULTIVO CON CUENTA DE COLONIAS</td>
				<td width='60%' class='StormyWeatherDataTD'>
						<input name='txtcantidad' type='text' id='txtcantidad' size='50' >
				</td>
			</tr>
			<tr class='CobaltButton'>
				<td width='40%' >ANTIBIOTICO</td>
				<td width='60%' >INTERPRETACI&Oacute;N</td>
			</tr>";
	while($row = mysql_fetch_array($consulta))//ELEMENTOS)
	{
	    $imprimir.="<tr>
	    	        	<td class='StormyWeatherFieldCaptionTD'>".$row['Antibiotico']."</td>
	        	    	<td class='StormyWeatherDataTD'>
					<input size='40' name='txtresultado[".$pos."]' type='text' id='txtresultado[".$pos."]' >
					<input name='oidantibiotico[".$pos."]' type='hidden' id='oidantibiotico[".$pos."]' value='".$row['IdAntibiotico']."'>
				</td>
			</tr>";
		$pos=$pos+1;		
	}
	mysql_free_result($consulta);
	$imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>"	;
	    $imprimir.="<tr>
				<td colspan='2' class='StormyWeatherDataTD'  align='right'>
				<input type='button' name='Submit' value='Vista Previa de Resultados' onclick='MostrarVistaPreviaPlantillaC()'>
				</td>
			</tr>
	           </table>";
			   
	echo $imprimir;
   
   break;
   
   case 2://MOSTRANDO VISTA PREVIA DE LOS RESULTADO POSITIVOS INGRESADOS
   		$idexamen=$_POST['idexamen'];
		$idsolicitud= $_POST['idsolicitud'];
		$idempleado= $_POST['idempleado'];
		$idrecepcion= $_POST['idrecepcion'];
		$iddetalle= $_POST['iddetalle'];	
		$observacion= $_POST['observacion'];
		$codigos_antibioticos=$_POST['codigos_antibioticos'];
		$valores_antibioticos=$_POST['valores_antibioticos'];
		$idtarjeta=$_POST['idtarjeta'];
		$idbacteria=$_POST['idbacteria'];
		$cantidad=$_POST['cantidad'];
		$resultado="P";
                $establecimiento=$_POST['estab'];
		
		$Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
		$row_estab = mysql_fetch_array($Consulta_Estab);

		$vector_valores=EXPLODE("/",$valores_antibioticos);
        $vector_antibioticos=EXPLODE("/",$codigos_antibioticos);
		$consulta=$objdatos->LeerAntibioticos($idtarjeta);
		$consulta_datos=$objdatos->LeerDatos($idexamen);
		$datos_generales=$objdatos->MostrarDatosGenerales($idsolicitud,$lugar);
		$datos_empleado=$objdatos->DatosEmpleado($idempleado,$lugar);
		$datos_observacion=$objdatos->LeerObservacion($observacion);
		$bateria=$objdatos->NombreBacteria($idbacteria);
		
		$row_generales= mysql_fetch_array($datos_generales);
		$row_area = mysql_fetch_array($consulta_datos);
		$row_empleado = mysql_fetch_array($datos_empleado);
		$row_observacion = mysql_fetch_array($datos_observacion);
		$row_nombrebacteria= mysql_fetch_array($bateria);
	
		$imprimir="<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
		<tr>
			<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
				<p><strong>".$row_estab['Nombre']."</strong></p>
				<p><strong>&Aacute;rea de ".htmlentities($row_area['NombreArea'])." </strong></p></td>
		</tr>
		<tr>
			<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
			<td colspan='3'>".$establecimiento."</td>
			<td colspan='1' style='font:bold'><strong>Fecha</strong></td>
			<td colspan='1'>".$row_generales['Fecha']."</td><input name='suEdad' id='suEdad'  type='hidden'  value='".$row_generales['FechaNacimiento']."'/>
		</tr>
		<tr>	
			<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
			<td colspan='1' style='font:bold'>".htmlentities($row_generales['Procedencia'])."</td>
			<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='3' style='font:bold'>".htmlentities($row_generales['Origen'])."</td>
		</tr>
		<tr>
			<td colspan='1' style='font:bold'><strong>NEC</strong></td>
			<td colspan='1' >".$row_generales['IdNumeroExp']."</td>
			<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
			<td colspan='3'>".htmlentities($row_generales['NombrePaciente'])."</td>
		</tr>
		<tr>
			<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
			<td colspan='1'><div id='divsuedad'>
          
    			    </div></td>
			<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='1'>".$row_generales['Sexo']."</td>
		</tr>
		<tr>
			<td colspan='1' style='font:bold'><strong>Examen Realizado:</strong></td>
			<td colspan='4'style='font:bold'>".htmlentities($row_area['NombreExamen'])."</td>
		</tr>				 
		<tr>
			<td colspan='1'style='font:bold'><strong>Resultado:</strong></td>
			<td colspan='4'style='font:bold'>Positivo</td>
		</tr>
		<tr>
			<td colspan='1'>Organismo</td>
			<td colspan='4'>".htmlentities($row_nombrebacteria['Bacteria'])."</td>	 
		</tr>	
		<tr>
			<td style='font:bold'><strong>Validado Por</strong></td>
			<td colspan='2'>".htmlentities($row_empleado['NombreEmpleado'])."</td>
		</tr>
		<tr>
			<td colspan='6'>&nbsp;</td>
		</tr>
		
		<tr>";
			
		$imprimir.="<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
				<tr>
					<td style='font:bold' width='50%'  class='StormyWeatherFieldCaptionTD'><strong>CULTIVO CON CUENTA DE COLONIAS</strong></td>
					<td width='50%' class='StormyWeatherDataTD'>".htmlentities($cantidad)."</td>
				</tr>
				<tr class='CobaltButton'>
					<td width='50%' style='font:bold' ><strong>ANTIBIOTICO</strong></td>
					<td width='50%' style='font:bold' ><strong>INTERPRETACI&Oacute;N</strong></td>
				</tr>";
			mysql_free_result($consulta_datos);
			mysql_free_result($datos_generales);
			$pos=0;
			
		while($row = mysql_fetch_array($consulta))//ELEMENTOS)
		{
		$imprimir.="<tr>
				<td width='50%'>".$row['Antibiotico']."</td>
				<td width='50%'>".htmlentities($vector_valores[$pos]).
						"<input name='oidantibiotico[".$pos."]' type='hidden' id='oidantibiotico[".$pos."]' value='".$row['IdAntibiotico']."'>
					</td>
				</tr>";
				$pos=$pos+1;		
		}
			mysql_free_result($consulta);
			
			
			
			$imprimir.="<tr>
					<td colspan='2'>&nbsp;</td>
				</tr>
				<tr>
					<td><input type='button' name='Guardar'  id='Guardar' value='Guardar Resultados' onclick='GuardarResultadosPlantillaC()'\>
					<input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaC(".$idsolicitud.",\"".$idexamen."\",\"".$resultado."\",\"".htmlentities($row_empleado['NombreEmpleado'])."\",\"".htmlentities($row_generales['Procedencia'])."\",\"".htmlentities($row_generales['Origen'])."\",\"".htmlentities($observacion)."\",\"".htmlentities($valores_antibioticos)."\",\"".$codigos_antibioticos."\",".$idbacteria.",\"".htmlentities($cantidad)."\",".$idtarjeta.",\"".htmlentities($row_area['NombreArea'])."\",\"".htmlentities($establecimiento)."\") ;' /></td>
				<td><input type='button' name='Ingresar' id='Ingresar' value='Ingresar otro Resultado' onclick='IngresarOtro()'\><input type='button'  name='Submit' value='Cerrar' Onclick='salir();'></td>
					</td>
				</tr>
	           </table>
	</tr></table>";
			   
	echo $imprimir;
	break;
	
	case 3://GUARDANDO DATOS DE LOS RESULTADOS EN LAS TABLAS
		$idexamen=$_POST['idexamen'];
		$idsolicitud= $_POST['idsolicitud'];
		$idempleado= $_POST['idempleado'];
		$idrecepcion= $_POST['idrecepcion'];
		$iddetalle= $_POST['iddetalle'];
		$observacion= $_POST['observacion'];
		$codigos_antibioticos=$_POST['codigos_antibioticos'];
		$valores_antibioticos=$_POST['valores_antibioticos'];
		$idarea=$_POST['idarea'];
		$idtarjeta=$_POST['idtarjeta'];
		$tiporespuesta=$_POST['tiporespuesta'];
		$resultado=$_POST['resultado'];
		$idbacteria=$_POST['idbacteria'];
		$cantidad=$_POST['cantidad'];
		$nombrearea=$_POST['nombrearea'];
		$vector_valores=EXPLODE("/",$valores_antibioticos);
		$vector_antibioticos=EXPLODE("/",$codigos_antibioticos);
	//$objdatos = new clsConsultarElementosPlantillaC;
		$tamano_vector=count($vector_valores);
		$tamano_vectoantibiotico=count($vector_antibioticos); 

  
  $posele=0;
  $ban=0;
  //echo $v_id_elementos[1];
  if ($resultado=="P")
  {
	$codigoResultado=4;  
	$ultimo= $objdatos->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$resultado,$idempleado,$usuario,$codigoResultado,$lugar);
	if ($ultimo != "")
	{
		$idresultado=$ultimo;
		//insertando el detalle
		$iddetalleresultado=$objdatos->insertar_detalle($idresultado,$idbacteria,$idtarjeta,$cantidad,$lugar);
	
		//insertando el detalle de resultados de la tarjeta asociada
		if (($tamano_vector-1)>0)
		{
			for ($i=0; $i < $tamano_vector-1 ; $i++) //INSERTANDO ANTIBIOTICOS
			{				 
				if ($objdatos->insertar_resultadoantibioticos($iddetalleresultado,$vector_antibioticos[$i],$vector_valores[$i],$lugar)==false)
				 { 
					 $ban=1;
				 }
				$posele=$posele+1;
			}
			
		}
		if($ban==0){
			//actualiza el estado del detalle de la solicitud para indicar que el resultado esta completo para el examen
		
			echo "Datos Guardados";
		
			
			if (($objdatos->CambiarEstadoDetalle($iddetalle)==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==true))
			{    
				echo " Correctamente";
			}
		
		}
		else
		{
		
			echo "Los resultados no pueden ser guardados consulte al Administrador...";
		}
    }    
	
  }  
  else{ echo "Los resultados no pueden ser guardados consulte al Administrador...";}  
   break;
   
   
   case 4://MOSTRANDO COMBO DE OBSERVACIONES SEGUN EL FILTRO
      		$idexamen=$_POST['idexamen'];
		$idarea=$_POST['idarea'];
		$idtarjeta=$_POST['idtarjeta'];
		$tiporespuesta=$_POST['tiporespuesta'];
		
		$consulta_ob=$objdatos->LeerObservaciones($idarea,$tiporespuesta);
                  	
		 $imprimir="<table class='StormyWeatherFormTABLE' width='100%' >
				<tr>
					<td width='45%' class='StormyWeatherFieldCaptionTD'>Observaci&oacute;n</td>
					<td width='55%' class='StormyWeatherDataTD' ><select id='cmbObservacion' name='cmbObservacion' size='1' >
		     	  			<option value='0' >--Seleccione Observaci&oacute;n--</option>";

		  
	    while($row = mysql_fetch_array($consulta_ob))
		{
		      $imprimir.="<option value='" . $row['IdObservacion']. "'>" . $row['Observacion'] . "</option>";
		}
		 $imprimir.="</select></td>
					</tr></table>";
		echo $imprimir; 
		
   
   break;
   
   case 5:
   //MOSTRANDO VISTA PREVIA DE LOS RESULTADOS INGRESADOS CASO RESPUESTA NEGATIVOS
   // $objdatos = new clsConsultarElementosPlantillaC;
	$idexamen=$_POST['idexamen'];
	$idtarjeta=$_POST['idtarjeta'];
	$tiporespuesta=$_POST['tiporespuesta'];
	$idarea=$_POST['idarea'];
	$idsolicitud= $_POST['idsolicitud'];
	$idempleado= $_POST['idempleado'];
	$observacion= $_POST['observacion'];
	$resultado=$_POST['resultado'];
	$establecimiento=$_POST['estab'];
     
	$Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
	$row_estab = mysql_fetch_array($Consulta_Estab);
  
	$datos_generales=$objdatos->MostrarDatosGenerales($idsolicitud,$lugar);
	$datos_empleado=$objdatos->DatosEmpleado($idempleado,$lugar);
	$consulta_datos=$objdatos->LeerDatos($idexamen);
	$row_area= mysql_fetch_array($consulta_datos);
	$row_generales= mysql_fetch_array($datos_generales);
	$row_empleado = mysql_fetch_array($datos_empleado);
	$datos_observacion=$objdatos->LeerObservacion($observacion);
	$row_observacion = mysql_fetch_array($datos_observacion);
	//$observacion="Resultado Negativo";
	$imprimir="<table width='60%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
					<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
					<p><strong>".$row_estab['Nombre']."</strong></p>
					<p><strong>Area de ".htmlentities($row_area['NombreArea'])." </strong></p>
				</td>
			</tr>
			<tr>
				<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
				<td colspan='3'>".$establecimiento."</td>
				<td colspan='1' style='font:bold'><strong>Fecha</strong></td>
				<td colspan='1'>".$row_generales['Fecha']."<input name='suEdad' id='suEdad'  type='hidden'  value='".$row_generales['FechaNacimiento']."'/></td>
			
			</tr>
			<tr>	
				<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
				<td colspan='1' style='font:bold'>".htmlentities($row_generales['Procedencia'])."</td>
				<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
				<td colspan='3' style='font:bold'>".htmlentities($row_generales['Origen'])."</td>
			</tr>
			<tr>
				<td colspan='1' style='font:bold'><strong>NEC</strong></td>
				<td colspan='1'>".$row_generales['IdNumeroExp']."</td>
				<td colspan='1' style='font:bold'><strong>Paciente</strong></td>
				<td colspan='3'>".htmlentities($row_generales['NombrePaciente'])."</td>
			</tr>
				
			<tr> 	
				<td colspan='1'style='font:bold'><strong>Edad</strong></td>
				<td colspan='1'><div id='divsuedad'>
          
    				  </div></td>
				
				<td colspan='1' style='font:bold'><strong>Sexo</strong></td>
				<td colspan='1'>".$row_generales['Sexo']."</td>
			</tr>
							
			<tr>
			        <td style='font:bold'><strong>Examen Realizado</strong></td>
				 <td colspan='4'style='font:bold'>".htmlentities($row_area['NombreExamen'])."</td>
			</tr>				 
			<tr>
				<td style='font:bold'><strong>Validado Por</strong></td>
				 <td colspan='2'>".htmlentities($row_empleado['NombreEmpleado'])."</td>
			</tr>
			<tr>
				 <td style='font:bold'><strong>Resultado</strong></td>";
	mysql_free_result($consulta_datos);
	mysql_free_result($datos_generales);	 
					 
	switch($resultado)
	{
	   	case "P";
			$imprimir.=	"<td colspan='4'>Positivo</td>";
	   	break;
	   	case "N";
			$imprimir.=	"<td colspan='4'>Negativo</td>";
	   	break;
	   	case "O";
			$imprimir.=	"<td colspan='4'>--</td>";
	   	break;
	}
					 
	   $imprimir.= "</tr>
			<tr>
				<td style='font:bold'><strong>Observaci&oacute;n</strong></td>
			        <td colspan='4'>".htmlentities($row_observacion['Observacion'])."</td>
					 
			</tr>
			<tr>
			  	<td colspan='5' align='center'></td>
			</tr>
			<tr>
				<td colspan='5'>&nbsp;</td>
			</tr>
		<div id='divBotones'>
			<tr>
                                <td colspan='5' align='center'>
			                <input type='button' id='btnGuardar'  value='Guardar Resultados' onclick='GuardarResultadosNegativosPlantillaC()'>
					<input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaCN(".$idsolicitud.",\"".$idexamen."\",\"".$idarea."\",\"".$resultado."\",\"".htmlentities($row_empleado['NombreEmpleado'])."\",\"".htmlentities($row_generales['Procedencia'])."\",\"".htmlentities($row_generales['Origen'])."\",\"".htmlentities($row_observacion['Observacion'])."\",\"".htmlentities($establecimiento)."\") ;' />
					<input type='button' id='btnSalir' value='Cerrar' onclick='Cerrar()'>
				</td>

			</tr>
		</div>
	 </table>";
				 
	echo $imprimir;
	break;
	
case 6:
	$idexamen=$_POST['idexamen'];
	//$tiporespuesta=$_POST['tiporespuesta'];
	$idsolicitud= $_POST['idsolicitud'];
	$idempleado= $_POST['idempleado'];
	$idrecepcion= $_POST['idrecepcion'];
	$iddetalle= $_POST['iddetalle'];
	$observacion= $_POST['observacion'];
	$resultado=$_POST['resultado'];
	
	 if ($resultado=="N")
	{
	  $codigoResultado=2;
	   $ultimo=$objdatos->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$resultado,$idempleado,$usuario,$codigoResultado,$lugar);
	                      
	   echo "Datos Guardados";
	}
	 else{
		$codigoResultado=2;
		
	
	   $ultimo=$objdatos->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$resultado,$idempleado,$usuario,$codigoResultado,$lugar);
	   
	   echo "Datos Guardados";
	}
 
	if (($objdatos->CambiarEstadoDetalle($iddetalle)==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==false))
	     {    
			echo (" Correctamente");
		}
	
	break;
   
  
 }

?>