<?php session_start(); 
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

include ("clsPlantillaD.php");
//creando los objetos de las clases
$obj = new clsPlantillaD;

//variables POST
$opcion=$_POST['opcion'];


switch ($opcion) 
{
  case 1:  
	//variables POST
	$idsolicitud= $_POST['idsolicitud'];
	$idexamen=$_POST['idexamen'];//*
	$iddetalle= $_POST['iddetalle'];
	$idrecepcion= $_POST['idrecepcion'];
	$idempleado= $_POST['idempleado'];
	//$observacion= $_POST['observacion'];
	$idelemento= $_POST['idelemento'];
	$idcantidad= $_POST['idcantidad'];
	$idresultado= $_POST['idresultado'];
	$tab=$_POST['tab'];
	$resultado="";
   	//guardandolos datos generales del resultado
	 $ultimo= $obj->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$idempleado,$usuario,$tab,$lugar);
	//guardando el detalle del resultado
	$obj->insertar_elemento($ultimo,$idelemento,$idcantidad,$lugar);
	$obj->CambiarEstadoDetalle($iddetalle);
	$obj->CambiarEstadoSolicitud($idsolicitud);
	//consultando los resultados ingresados
	$idresultado=$ultimo;
	$consulta=$obj->MostrarElementosAgregados($idresultado);
	$resultado="<input name='oresultado' type='hidden' id='oresultado' value='".$ultimo."'>
		<table aling='center' border='1'>
			<tr class='CobaltButton'>
				<td aling='center' >Eliminar</td>
				<td aling='center' >Elemento</td>
				<td aling='center' >Cantidad</td>
			</tr>
			<tr>";
	while($row = mysql_fetch_array($consulta)){
	  $resultado.= "<tr>
				<td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"EliminarElemento('".$row[0]."')\"> </td>
				<td> $row[0] </td>
				<td> $row[1] </td>
			</tr>";
	}
	$resultado.="</table>";
	
	echo $resultado;
	
   break;
   
   case 2:  // AGREGA NUEVO ELEMENTO DE TINCION AL RESULTADO
	$idelemento= $_POST['idelemento'];
	$idcantidad= $_POST['idcantidad'];
	$idresultado= $_POST['idresultado'];
	$tab= $_POST['tab'];
	$resultado="";
	//VERIFICANDO QUE NO EXISTA ESTE ELEMENTO PARA ESTE RESULTADO
	$reg=$obj->ValidacionElemento($idresultado,$idelemento);
	
	if($reg == 0)
	 {
	    
		$obj->insertar_elemento($idresultado,$idelemento,$idcantidad,$lugar);
		//consultando los resultados ingresados
		
		$consulta=$obj->MostrarElementosAgregados($idresultado);
		$resultado.="
			<input name='oresultado' type='hidden' id='oresultado' value='".$idresultado."'>
			<table aling='center' border='1'>
			<tr class='CobaltButton'>
			<td aling='center' >Eliminar</td>
			<td aling='center' >Elemento</td>
			<td aling='center' >Cantidad</td>
				</tr>
				<tr>";
		while($row = mysql_fetch_array($consulta)){
				$resultado.= "<tr>
							<td aling ='center'> 
								<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
								onclick=\"EliminarElemento('".$row[0]."')\"> </td>
							<td> $row[0] </td>
							<td> $row[1] </td>
						</tr>";
					}
			mysql_free_result($consulta);
					
		$resultado.="</table>";
		
		
	}
	else{$resultado="N";}
		echo $resultado;
   break;
  
   case 3: //VISTA PREVIA DE LOS RESULTADOS
   	$idexamen=$_POST['idexamen'];//
	$idsolicitud= $_POST['idsolicitud'];
	$idrecepcion= $_POST['idrecepcion'];
	$idempleado= $_POST['idempleado'];
	$idresultado= $_POST['idresultado'];
	$establecimiento=$_POST['estab'];
	$tab= $_POST['tab'];
	
	$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
	$row_estab = mysql_fetch_array($Consulta_Estab);

	$consulta=$obj->MostrarElementosAgregados($idresultado);
    	$consulta_datos=$obj->LeerDatos($idexamen);
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud);
	$datos_empleado=$obj->DatosEmpleado($idempleado);
	$row_generales= mysql_fetch_array($datos_generales);
	$row_area = mysql_fetch_array($consulta_datos);
	$row_empleado = mysql_fetch_array($datos_empleado);

   $resultado ="
		<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
					<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
					<p><strong>".$row_estab['Nombre']."</strong></p>
					<p><strong>&Aacute;rea de ".htmlentities($row_area['NombreArea'])." </strong></p>
				</td>
			</tr>
			<tr>
				<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
				<td colspan='3'>".$establecimiento."</td>
				<td colspan='1' style='font:bold'><strong>Fecha</strong></td>
				<td colspan='1'>".$row_generales['Fecha']."</td>
                        </tr>
			<tr>	
				<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
				<td colspan='1' style='font:bold'>".htmlentities($row_generales['Procedencia'])."</td>
				
				<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
				<td style='font:bold' colspan='2'>".$row_generales['Origen']."</td>
			</tr>	
			<tr>
				<td colspan='1' style='font:bold'><strong>NEC</strong></td>
				<td colspan='1'>".$row_generales['IdNumeroExp']."</td>
				<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
				<td colspan='3'>".htmlentities($row_generales['NombrePaciente'])."</td>
				<input name='suEdad' id='suEdad'  type='hidden'  value='".$row_generales['FechaNacimiento']."'/>
			</tr>
			<tr>
				
				<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
				<td colspan='1'><div id='divsuedad'>
          
    				  </div></td>
				<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
				<td colspan='1'>".$row_generales['Sexo']."</td>
			</tr>
			
			<tr>
				<td style='font:bold'><strong>Examen Realizado:</strong></td>
				<td colspan='5'style='font:bold'>".htmlentities($row_area['NombreExamen'])."</td>
			</tr>				 
			<tr>
				<td style='font:bold'><strong>Validado Por:</strong></td>
				<td colspan='5'>".htmlentities($row_empleado['NombreEmpleado'])."</td>
			</tr>";
			$nomcod=$obj->ObtenerNombreCodigo($tab);
                                         $row_codigo= mysql_fetch_array($nomcod);
										// echo $row_codigo[0];
			$resultado.=" <tr><td style='font:bold' >Resultado Tabulador:</td><td colspan='4'>".$row_codigo[0]."</td></tr>
			<tr>
				<td colspan='6'>&nbsp;</td>
			</tr>
			<tr>
				<td></td>
				<td colspan='4' aling='center' >
					<table width='100%' border='1'aling='center' >
						<tr>
							<td style='font:bold' aling='center' class='StormyWeatherDataTD'><strong>Elemento de Tinci&oacute;n</strong>
							</td>
							<td style='font:bold' aling='center' class='StormyWeatherDataTD'><strong>Cantidad</strong>
							</td>
						</tr>";
						while($row = mysql_fetch_array($consulta)){
						$resultado.= "
						<tr>
							<td>$row[0]</td>
							<td>$row[1]</td>
						</tr>";
						}
						mysql_free_result($consulta);
						mysql_free_result($consulta_datos);
						mysql_free_result($datos_generales);
		            $resultado.="</table>
				</td>
			</tr>";
		$resultado.="
			<tr>
				<td coslpan='6' aling='center'>
					<input type='submit' id='btnImprimir' value='Imprimir' Onclick='ImprimirPlantillaD(".$idsolicitud.",\"".$idexamen."\",".$idresultado.",\"".$idempleado."\",\"".htmlentities($establecimiento)."\") ;' />
					<input type='button' id='btnSalir' value='Cerrar' onclick='Cerrar()'>
				</td>
			</tr>
		</table>";
	echo $resultado;
   break;
   
   case 4:
	
	$idelemento= $_POST['idelemento'];
	$idresultado= $_POST['idresultado']; 
	$resultado=" ";
   $obj->EliminarElementoDetalle($idresultado,$idelemento);
   $consulta=$obj->MostrarElementosAgregados($idresultado);
	$resultado="
				<input name='oresultado' type='hidden' id='oresultado' value='".$idresultado."'>
				<table aling='center' border='1'>
				<tr >
				<td aling='center' class='CobaltFieldCaptionTD'>Eliminar</td>
				<td aling='center' class='CobaltFieldCaptionTD'>Elemento</td>
				<td aling='center' class='CobaltFieldCaptionTD'>Cantidad</td>
				</tr>
				<tr>";
	while($row = mysql_fetch_array($consulta)){
			$resultado.= "<tr>
							<td aling ='center'> 
							<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
							onclick=\"EliminarElemento('".$row[0]."')\"> </td>
							<td> $row[0] </td>
							<td> $row[1] </td>
							</tr>";
				}
				mysql_free_result($consulta);
				
	$resultado.="</table>";
	echo $resultado;
   break;
   
  }

?>