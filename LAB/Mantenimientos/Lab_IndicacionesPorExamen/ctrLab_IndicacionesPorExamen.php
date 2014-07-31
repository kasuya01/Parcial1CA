<?php session_start();
include_once("clsLab_IndicacionesPorExamen.php");
include('../Lab_Areas/clsLab_Areas.php');
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

			
//variables POST
$idexamen=$_POST['idexamen'];
$idarea=$_POST['idarea'];
$idindicacion=$_POST['idindicacion'];
$indicacion=$_POST['indicacion'];
$Pag =$_POST['Pag'];
$opcion=$_POST['opcion'];
//actualiza los datos del empleados
$objdatos = new clsLab_IndicacionesPorExamen;
$objeareas=new clsLab_Areas;
//$usuario=1;
switch ($opcion) 
{
    case 1:  //INSERTAR	
      if ($objdatos->insertar($idexamen,$idarea,$indicacion,$usuario)==true)
	   {
		   echo "Registro Agregado";
		   
     	}
		else{
			echo "No se pudo actualizar";			
		}
		
		break;
    case 2:  //MODIFICAR      
	
		If ($objdatos->actualizar($idexamen,$idarea,$indicacion,$idindicacion,$usuario)==true)
		               //actualizar($indicacion,$idindicacion,$usuario)
		{
			echo "Registro Actualizado"	;			
		}
		else{
			echo "No se pudo actualizar";
		}
		
     break;
	 case 3:  //ELIMINAR    
		 //Vefificando Integridad de los datos
		 if ($objdatos->eliminar($idexamen)==true){		
			echo "Registro Eliminado" ;		
				
			}
			else{
				echo "El registro no pudo ser eliminado ";
			}			
		
	break;
	case 4:// PAGINACION
		//echo "llevo opcion 4";
		//para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		//$obje=new clsLab_IndicacionesPorExamen;
		$consulta= $objdatos->consultarpag($RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
				echo "<table border = 1 align='center' width='60%' class='StormyWeatherFormTABLE'>
					   <tr>
						<td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
						<td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td>
						<td class='CobaltFieldCaptionTD'> IdArea </td>
						<td class='CobaltFieldCaptionTD'> IdExamen </td>
						<td class='CobaltFieldCaptionTD'> Indicaci&oacute;n </td>	   
					   </tr>";

				while($row = pg_fetch_array($consulta)){
                                    echo "<tr>
						<td aling='center'> 
                                        		<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
							onclick=\"pedirDatos('".$row[0]."')\"> </td>
						<td aling ='center'> 
							<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
							onclick=\"eliminarDato('".$row[0]."')\"> </td>
						<td> $row[1] </td>
						<td> $row[2] </td>
						<td>".htmlentities( $row[3])."</td>
					</tr>";
				}
                                echo "</table>"; 

		//determinando el numero de paginas
		 $NroRegistros= $objdatos->NumeroDeRegistros();
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;
		 
		 $PagUlt=$NroRegistros/$RegistrosAMostrar;
		 
		 //verificamos residuo para ver si llevar� decimales
		 $Res=$NroRegistros%$RegistrosAMostrar;
		 //si hay residuo usamos funcion floor para que me
		 //devuelva la parte entera, SIN REDONDEAR, y le sumamos
		 //una unidad para obtener la ultima pagina
		 if($Res>0) $PagUlt=floor($PagUlt)+1;

		 echo "<table align='center'>
		       <tr>
			   <td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
			   </tr>
			   <tr>
			   <td>
			   <a onclick=\"show_event('1')\">Primero</a> </td>";
		//// desplazamiento

		 if($PagAct>1) 
			 echo "<td> <a onclick=\"show_event('$PagAnt')\">Anterior</a> </td>";
		 if($PagAct<$PagUlt)  
			 echo "<td> <a onclick=\"show_event('$PagSig')\">Siguiente</a> </td>";
			  if($PagUlt > 0)
				echo "<td> <a onclick=\"show_event('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
			  </table>";
	break;
	case 5:  //LLENAR COMBO DE EXAMENES  
	//DIBUJANDO EL FORMULARIO NUEVAMENTE
                $idarea=$_POST['idarea'];
                $consultaex= $objdatos->ExamenesPorArea($idarea);
           	//echo $idarea; 
	  	$resultado='';
		      $resultado = "<select id='cmbExamen' name='cmbExamen' size='1'>
					<option value='0'>--Seleccione un Examen--</option>";
						
				while($rowex = pg_fetch_array($consultaex))
						{
		$resultado .= "<option value='" . $rowex[0]. "'>" .htmlentities($rowex[1]) . "</option>";
						}
						///// FINALIZA LLENADO /////
		$resultado .= "</select>";
               
                echo  $resultado;
     
	break;
	
	case 6: //DIBUJANDO EL FORMULARIO DE NUEVO
	   $resultado= "<form name='frmnuevo'>
		        <table width='52%' border='0' align='center' class='StormyWeatherFormTABLE'>
			    <tr>
					<td colspan='2' class='CobaltFieldCaptionTD' align='center'><h3><strong>Mantenimiento de Indicaciones por Ex&aacute;menes de Laboratorio Cl&iacute;nico</strong></h3>
					</td>
				</tr>	
				<tr>
					<td class='StormyWeatherFieldCaptionTD'>&Aacute;rea</td>
					<td class='StormyWeatherDataTD'>
						<select id='cmbArea' name='cmbArea' size='1' onChange='LlenarComboExamen(this.value);'>
						<option value='0' >--Seleccione un &Aacute;rea--</option>";
						/////////////  LLENAR EL COMBO DE AREAS///////////
						//include('../Lab_Areas/clsLab_Areas.php');
						//$objeareas=new clsLab_Areas;
						$consulta= $objeareas->consultaractivas($lugar);
						while($row = mysql_fetch_array($consulta))
						{
		$resultado .= "<option value='" . $row['IdArea']. "'>" . $row['NombreArea'] . "</option>";
						}
		$resultado .= "</select>
					</td>
		        </tr>
		        <tr>
					<td class='StormyWeatherFieldCaptionTD'>Examen </td>
					<td class='StormyWeatherDataTD'><select id='cmbExamen' name='cmbExamen' size='1'>
					<option value='0'>--Seleccione un Examen--</option>";
        $resultado .= "</select>
					</td>
				</tr>
				<tr>
					<td class='StormyWeatherFieldCaptionTD'>Indicaci&oacute;n</td>
					<td class='StormyWeatherDataTD'><textarea name='txtindicacion' cols='60' rows='4' id='txtindicacion'></textarea></td>
				</tr>
				<tr>
					<td class='StormyWeatherDataTD' colspan='2' align='right'>
						<input type='button' name='Submit' value='Insertar' Onclick='Guardar() ;'> 
						<input type='button' name='Submit2' value='Buscar' Onclick='Buscar() ;'>
					</td>
				</tr>
				</table>
        </form>";
	  echo  $resultado;
	  
	  break;
	case 7: //BUSQUEDA
		
		$query = "SELECT IdIndicacionPorExamen,IdArea,IdExamen,Indicacion FROM mnt_indicacionesporexamen WHERE ";
		$ban=0;
		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['idexamen']))
		{ $query .= " IdExamen='".$_POST['idexamen']."' AND"; }
		else{$ban=1;}
		
		if (!empty($_POST['idarea']))
		{ $query .= " IdArea='".$_POST['idarea']."' AND"; }
		else{$ban=1;}
		
		if (!empty($_POST['indicacion']))
		{ $query .= " Indicacion='".$_POST['indicacion']."' AND"; }
		else{$ban=1;}
		if ($ban==0)
		{    $query = substr($query ,0,strlen($query)-4);
			 $query_search = $query. " AND IdServicio='DCOLAB'";
		}
		else {
			$query_search = $query. " IdServicio='DCOLAB'";
		}
		echo $query_search;
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		
		//require_once("clsLab_IndicacionesPorExamen.php");
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		//$obje=new clsLab_IndicacionesPorExamen;
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		   echo "<table border = 1 align='center'  width='60%' class='StormyWeatherFormTABLE'>
		        <tr>
				<td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
				<td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td>
				<td class='CobaltFieldCaptionTD'> IdArea </td>
				<td class='CobaltFieldCaptionTD'> IdExamen </td>
				<td class='CobaltFieldCaptionTD'> Indicaci&oacute;n </td>	   
			</tr>";

			while($row = pg_fetch_array($consulta)){
		  echo "<tr>
		  		<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('".$row['IdIndicacionPorExamen']."')\"> </td>
				<td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row['IdIndicacionPorExamen']."')\"> </td>
				<td> $row[1] </td>
				<td> $row[2] </td>
				<td>".htmlentities( $row[3])."</td>
				</tr>";
				}
		echo "</table>"; 

		//determinando el numero de paginas
		 $NroRegistros= $objdatos->NumeroDeRegistrosbus($query_search);
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;
		 
		 $PagUlt=$NroRegistros/$RegistrosAMostrar;
		 
		 //verificamos residuo para ver si llevar� decimales
		 $Res=$NroRegistros%$RegistrosAMostrar;
		 //si hay residuo usamos funcion floor para que me
		 //devuelva la parte entera, SIN REDONDEAR, y le sumamos
		 //una unidad para obtener la ultima pagina
		 if($Res>0) $PagUlt=floor($PagUlt)+1;

		 echo "<table align='center'>
		       <tr>
			   <td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
			   </tr>
			   <tr>
			   <td>
			   <a onclick=\"show_event_search('1')\">Primero</a> </td>";
		//// desplazamiento

		 if($PagAct>1) 
			 echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
		 if($PagAct<$PagUlt)  
			 echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
			  if($PagUlt > 0)
				echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
			  </table>";
		
	break;
	case 8://PAGINACION DE BUSQUEDA
		$query = "SELECT id,idarea,ideExamen,indicacion FROM mnt_indicacionesporexamen WHERE ";
		$ban=0;
		
		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['idexamen']))
		{ $query .= " idexamen='".$_POST['idexamen']."' AND"; }
		else{$ban=1;}
		
		if (!empty($_POST['idarea']))
		{ $query .= " idarea='".$_POST['idarea']."' AND"; }
		else{$ban=1;}
		
		if (!empty($_POST['indicacion']))
		{ $query .= " Indicacion='".$_POST['indicacion']."' AND"; }
		else{$ban=1;}
		
		if ($ban==0)
		{    $query = substr($query ,0,strlen($query)-4);
			 $query_search = $query. " AND IdServicio='DCOLAB'";
		}
		else {
			$query_search = $query. " IdServicio='DCOLAB'";
		}
		echo $query_search;
		//require_once("clsLab_IndicacionesPorExamen.php");
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		//$obje=new clsLab_IndicacionesPorExamen;
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
				echo "<table border = 1 align='center'  width='60%' class='StormyWeatherFormTABLE'>
					   <tr>
					   <tr>
					   <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
					   <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td>
					   <td class='CobaltFieldCaptionTD'> IdArea </td>
					   <td class='CobaltFieldCaptionTD'> IdExamen </td>
					   <td class='CobaltFieldCaptionTD'> Indicaci&oacute;n </td>	   
					   </tr>";

				while($row = pg_fetch_array($consulta)){
					echo "<tr>
							<td aling='center'> 
							<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
							onclick=\"pedirDatos('".$row[0]."')\"> </td>
							<td aling ='center'> 
							<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
							onclick=\"eliminarDato('".$row[0]."')\"> </td>
							<td> $row[1] </td>
							<td> $row[2] </td>
							<td>".htmlentities($row[3])."</td>
							</tr>";
				}
		echo "</table>"; 

		//determinando el numero de paginas
		 $NroRegistros= $obje->NumeroDeRegistrosbus($query_search);
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;
		 
		 $PagUlt=$NroRegistros/$RegistrosAMostrar;
		 
		 //verificamos residuo para ver si llevar� decimales
		 $Res=$NroRegistros%$RegistrosAMostrar;
		 //si hay residuo usamos funcion floor para que me
		 //devuelva la parte entera, SIN REDONDEAR, y le sumamos
		 //una unidad para obtener la ultima pagina
		 if($Res>0) $PagUlt=floor($PagUlt)+1;

	  echo "<table align='center'>
			
			    <tr>
					<td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
				</tr>
				<tr>
					<td>
						<a onclick=\"show_event_search('1')\">Primero</a> </td>";
		//// desplazamiento
		 if($PagAct>1) 
			 echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
		 if($PagAct<$PagUlt)  
			 echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
			 echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
			  </table>";
	break;
}

?>