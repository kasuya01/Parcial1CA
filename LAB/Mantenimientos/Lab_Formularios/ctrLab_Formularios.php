<?php session_start();
include_once("clsLab_Formularios.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//variables POST

$opcion=$_POST['opcion'];
//$opcion=$_POST['opcion'];
$objdatos = new clsLab_Formularios;
//$Clases = new clsLabor_Programas;
//$obje=new clsLab_Bacterias;
switch ($opcion) 
{
    case 1:  //INSERTAR	
			$Formulario=$_POST['Formulario'];
			$IdPrograma=$_POST['IdPrograma'];
			//echo $Formulario."***".$IdPrograma;
			
			$IdForm=$objdatos->insertar($Formulario,$usuario);
			//echo  $IdForm;
			if ($IdForm != 0){
				//echo $IdForm."**".$IdPrograma."**".$lugar."**".$usuario;
				if($objdatos->IngFormularioxEstablecimiento($IdForm,$IdPrograma,$lugar,'H',$usuario)==true){
							 
					echo "Registro Agregado";
				}
				else{
					echo "No se pudo Agregar el Registro";
				} 
			}
   break;
    case 2:  //MODIFICAR   
			$IdFormulario=$_POST['IdFormulario'];
			$Formulario=$_POST['Formulario'];
			$IdPrograma=$_POST['IdPrograma'];
			echo $IdFormulario."***".$Formulario."***".$IdPrograma;
			if (($objdatos->actualizar($IdFormulario,$Formulario,$usuario)==true) && ($objdatos->actualizarxestablecimiento($IdFormulario,$IdPrograma,$lugar,$usuario)==true)){
				echo "Registro Actualizado"	;	                                                
			}
	 	    else{
			   echo "No se pudo Actualizar el Registro";
			}
		
     break;
     case 3:  //ELIMINAR 
			$IdFormulario=$_POST['IdFormulario'];
			//if (($objdatos->eliminar($IdPrograma)==true) && ($Clases->eliminar_labo($IdPrograma)==true)){		
			if ($objdatos->eliminar($IdFormulario)==true) {		
				echo "Registro Eliminado" ;		
			}
			else{
				echo "El registro no pudo ser Eliminado ";
			}			
     break;
     case 4: // PAGINACION
		$Pag =$_POST['Pag'];
		//echo $Pag; 
		//para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		//LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpag($RegistrosAEmpezar, $RegistrosAMostrar,$lugar);

		//muestra los datos consultados en la tabla
	  echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
			    <tr>
			        <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
				    <td aling='center' class='CobaltFieldCaptionTD'> Habilitado</td>
			        <td class='CobaltFieldCaptionTD'> IdFormulario</td>
			        <td class='CobaltFieldCaptionTD'> Formulario </td>	
					<td class='CobaltFieldCaptionTD'> Programa de Salud </td>		
			    </tr>";

		while($row = mysql_fetch_array($consulta)){
		  echo "<tr>
					<td aling='center'> 
						<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
						onclick=\"pedirDatos('".$row[0]."')\"> </td>
					<td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' ".
						"onclick='Estado(\"".$row[0]."\",\"".$row[2]."\")'>".$row[3]."</td>
					<td>$row[0]</td>
					<td>".htmlentities($row[1])."</td>
					<td>".htmlentities($row[4])."</td>
					
				</tr>";
		}
	  echo" </table>"; 

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
     case 5:  //buscar
            
			$Formulario=$_POST['Formulario'];
			$IdPrograma=$_POST['IdPrograma'];
			
			$query = "SELECT mnt_formularios.IdFormulario,NombreFormulario,NombrePrograma	 
					  FROM mnt_formulariosxestablecimiento
					  INNER JOIN mnt_formularios ON mnt_formularios.IdFormulario=mnt_formulariosxestablecimiento.IdFormulario 
					  INNER JOIN mnt_programasxestablecimiento ON mnt_formulariosxestablecimiento.IdPrograma=mnt_programasxestablecimiento.IdPrograma
					  INNER JOIN mnt_programas ON mnt_programasxestablecimiento.IdPrograma=mnt_programas.IdPrograma
					  WHERE mnt_formulariosxestablecimiento.IdEstablecimiento=$lugar AND ";
				
			//VERIFICANDO LOS POST ENVIADOS
			if (!empty($_POST['Formulario']))
			{ $query .= " NombreFormulario like'%".$_POST['Formulario']."%' "; }
			
			if (!empty($_POST['IdPrograma']))
			{ $query .= " IdPrograma=".$_POST['IdPrograma']."%' "; }
			
			$query = substr($query ,0,strlen($query)-1);
			//echo $query;
			//para manejo de la paginacion
			$RegistrosAMostrar=4;
			$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
			$PagAct=$_POST['Pag'];
	
			//LAMANDO LA FUNCION DE LA CLASE 
			$consulta= $objdatos->consultarpagbus($query,$RegistrosAEmpezar, $RegistrosAMostrar);

			//muestra los datos consultados en la tabla
			echo "<table border = 1 align='center' class='estilotabla'>
				  <tr>
				   <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
				   <td aling='center' class='CobaltFieldCaptionTD'> Habilitado</td>
				   <td class='CobaltFieldCaptionTD'> IdFormulario</td>
				   <td class='CobaltFieldCaptionTD'> Formulario </td>	   
				   </tr>";

			while($row = mysql_fetch_array($consulta)){
				  echo "<tr>
						<td aling='center'> 
						<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
						onclick=\"pedirDatos('".$row[0]."')\"> </td>
						<td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' ".
						"onclick='Estado(\"".$row[0]."\",\"".$row[2]."\")'>".$row[3]."</td>
						<td> $row[0] </td>
						<td>".htmlentities($row[1])."</td>
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
				  </table>";

     break;
	 case 6:
				
				$idform=$_POST['idform'];
				$cond=$_POST['condicion'];
				echo "CRT ".$cond."-".$idform;
				//$resultado=Estado::EstadoCuenta($idexamen,$cond,$lugar);
				$resultado=$objdatos->EstadoCuenta($idform,$cond,$lugar);
	 break;
}

?>