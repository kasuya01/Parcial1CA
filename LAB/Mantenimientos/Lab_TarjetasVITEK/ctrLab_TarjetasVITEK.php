<?php session_start();
include_once("clsLab_TarjetasVITEK.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//echo $lugar;
//variables POST
//$idtarjeta=$_POST['idtarjeta'];
//$nombretarjeta=$_POST['nombretarjeta'];
$opcion=$_POST['opcion'];
$Pag =$_POST['Pag'];
$objdatos = new clsLab_TarjetasVITEK;
$Clases = new clsLabor_TarjetasVITEK;
switch ($opcion) 
{
	case 1:  //INSERTAR	
		$nombretarjeta=$_POST['nombretarjeta'];
		
		if (empty($_POST['Fechaini'])){
			$Fechaini="(NULL)";
		}else{ 
			$FechaI=explode('/',$_POST['Fechaini']);
	  		$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
	  	}
		if (empty($_POST['Fechafin'])){
			$Fechafin="(NULL)";
		}else{ 
			$FechaF=explode('/',$_POST['Fechafin']);
			$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];	
		}
		
       	if (($objdatos->insertar($nombretarjeta,$usuario,$lugar,$Fechaini,$Fechafin)==true)) //&& ($Clases->insertar_labo($nombretarjeta,$usuario,$lugar,$Fechaini,$Fechafin)==true))
	   	{
			    echo "Registro Agregado";
		}
		else{
			echo "No se pudo actualizar";
		}
		
    break;
    case 2:  //MODIFICAR
		$idtarjeta=$_POST['idtarjeta']; 
		$nombretarjeta=$_POST['nombretarjeta'];   
		
		if (empty($_POST['Fechaini'])){
			$Fechaini="(NULL)";
		}else{ 
			$FechaI=explode('/',$_POST['Fechaini']);
	  		$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
	  	}
		if (empty($_POST['Fechafin'])){
			$Fechafin="(NULL)";
		}else{ 
			$FechaF=explode('/',$_POST['Fechafin']);
			$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];	
		}
		
		If (($objdatos->actualizar($idtarjeta,$nombretarjeta,$usuario,$lugar,$Fechaini,$Fechafin)==true) && ($Clases->actualizar_labo($idtarjeta,$nombretarjeta,$usuario,$lugar,$Fechaini,$Fechafin)==true)){
			echo "Registro Actualizado"	;	
		}
		else{
			echo "No se pudo actualizar";
		}
		
    break;
   	case 3:  //ELIMINAR    
		$idtarjeta=$_POST['idtarjeta']; 
	    if (($objdatos->eliminar($idtarjeta,$lugar)==true)&& ($Clases->eliminar_labo($idtarjeta,$lugar)==true)){		
				echo "Registro Eliminado" ;		
				
			}
			else{
				echo "El registro no pudo ser eliminado ";
			}
       		
    break;
    case 4:// PAGINACION
		$Pag =$_POST['Pag'];
		//echo "llevo opcion 4";
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpag($RegistrosAEmpezar,$RegistrosAMostrar,$lugar);

		//muestra los datos consultados en la tabla
		echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
			   <tr>
			   <td class='CobaltFieldCaptionTD' aling='center'> Modificar</td>
			   <td class='CobaltFieldCaptionTD' aling='center'> Eliminar</td>
			   <td class='CobaltFieldCaptionTD'> IdTarjeta</td>
			   <td class='CobaltFieldCaptionTD'> Nombre de Tarjeta </td>
				<td class='CobaltFieldCaptionTD'> Fecha Inicio </td>	 
				<td class='CobaltFieldCaptionTD'> Fecha Finalización </td>	
			   </tr>";

		while($row = pg_fetch_array($consulta)){
			echo "<tr>
					<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('".$row['IdTarjeta']."')\"> </td>
					<td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row['IdTarjeta']."')\"> </td>
					<td>".$row['IdTarjeta']."</td>
					<td>".htmlentities($row['NombreTarjeta'])." </td>
					<td>".htmlentities($row['FechaIni'])." </td>
					<td>".htmlentities($row['FechaFin'])." </td>
					</tr>";
		}
		echo "</table>"; 

		//determinando el numero de paginas
		 $NroRegistros= $objdatos->NumeroDeRegistros($lugar);
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;
		 
		 $PagUlt=$NroRegistros/$RegistrosAMostrar;
		 
		 //verificamos residuo para ver si llevar� decimales
		 $Res=$NroRegistros%$RegistrosAMostrar;

		 //si hay residuo usamos funcion floor para que me
		 //devuelva la parte entera, SIN REDONDEAR, y le sumamos
		 //una unidad para obtener la ultima pagina
		 if($Res>0) $PagUlt=floor($PagUlt)+1;
//echo $PagUlt."-". $Res;
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
			 echo "<td> <a onclick=\"show_event('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
			  </table>";
	break;
        case 5: //Busqueda
			$nombretarjeta=$_POST['nombretarjeta'];
			
			if (empty($_POST['Fechaini'])){
				$Fechaini="(NULL)";
			}else{ 
				$FechaI=explode('/',$_POST['Fechaini']);
				$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
			}
			
			if (empty($_POST['Fechafin'])){
				$Fechafin="(NULL)";
			}else{ 
				$FechaF=explode('/',$_POST['Fechafin']);
				$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];	
			}
		   $query = "SELECT IdTarjeta,NombreTarjeta,FechaIni,FechaFin FROM lab_tarjetasvitek WHERE IdEstablecimiento=$lugar AND ";
				
			//VERIFICANDO LOS POST ENVIADOS
			if (!empty($_POST['nombretarjeta']))
				{ $query .= " NombreTarjeta like'%".$_POST['nombretarjeta']."%' AND"; }
			
			if (!empty($_POST['Fechaini']))
				{$FechaI=explode('/',$_POST['Fechaini']);
				$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
				$query .= " FechaIni='".$Fechaini."' AND"; }

			if (!empty($_POST['Fechafin'])){
				$FechaF=explode('/',$_POST['Fechafin']);
				$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
				$query .= " FechaFin='".$Fechafin."' AND"; } 	
				
			if((empty($_POST['nombretarjeta'])) and (empty($_POST['Fechaini'])) and (empty($_POST['Fechafin'])))
			{
				$ban=1;
			}
			if ($ban==0){
		
						$query = substr($query ,0,strlen($query)-4);
					//echo $query;
					//para manejo de la paginacion
					$RegistrosAMostrar=4;
					$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
					$PagAct=$_POST['Pag'];
	
					//LAMANDO LA FUNCION DE LA CLASE 
					$consulta= $objdatos->consultarpagbus($query,$RegistrosAEmpezar, $RegistrosAMostrar);

					//muestra los datos consultados en la tabla
					echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
							 <tr>
							 <td class='CobaltFieldCaptionTD' aling='center'> Modificar</td>
							 <td class='CobaltFieldCaptionTD' aling='center'> Eliminar</td>
							 <td class='CobaltFieldCaptionTD' > IdTarjeta</td>
							 <td class='CobaltFieldCaptionTD'> Nombre de Tarjeta </td>
							 <td class='CobaltFieldCaptionTD'> Fecha Inicio </td>	 
							 <td class='CobaltFieldCaptionTD'> Fecha Finalización </td>	
							 </tr>";

					while($row = pg_fetch_array($consulta)){
						echo "<tr>
							<td aling='center'> 
							<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
							onclick=\"pedirDatos('".$row[0]."')\"> </td>
							<td aling ='center'> 
							<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
							onclick=\"eliminarDato('".$row[0]."')\"> </td>
							<td>".$row['IdTarjeta']."</td>
							<td>".htmlentities($row['NombreTarjeta'])." </td>
							<td>".$row['FechaIni']."</td>
							<td>".$row['FechaFin']."</td>
								  </tr>";
					}
					echo "</table>"; 

					//determinando el numero de paginas
					 $NroRegistros= $objdatos->NumeroDeRegistrosbus($query);
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
						  </table>";}
        break;
	
}

?>