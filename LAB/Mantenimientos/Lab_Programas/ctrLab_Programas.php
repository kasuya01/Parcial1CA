<?php session_start();
include_once("clsLab_Programas.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//variables POST
/*$IdPrograma=$_POST['IdPrograma'];
$Programa=$_POST['Programa'];
$Pag =$_POST['Pag'];*/
$opcion=$_POST['opcion'];$opcion=$_POST['opcion'];
$objdatos = new clsLab_Programas;
//$Clases = new clsLabor_Programas;
//$obje=new clsLab_Bacterias;
switch ($opcion) 
{
    case 1:  //INSERTAR	
			$Programa=$_POST['Programa'];
			//echo $Programa;
		//if (($objdatos->insertar($Programa,$usuario)==true) && ($Clases->insertar_labo($Programa,$usuario)==true)){
			$IdProg=$objdatos->insertar($Programa,$usuario);
			
			if ($IdProg!= ""){
				//echo $IdProg;
				if($objdatos->IngProgramasxEstablecimiento($IdProg,$lugar,'H',$usuario)==true){
					echo "Registro Agregado";
				}
				else{
					echo "No se pudo Agregar el Registro";
				} 
			}
   break;
    case 2:  //MODIFICAR   
			$IdPrograma=$_POST['IdPrograma'];
			$Programa=$_POST['Programa'];
		//If (($objdatos->actualizar($IdPrograma,$Programa,$usuario)==true) && ($Clases->actualizar_labo($IdPrograma,$Programa,$usuario)==true)){
			if ($objdatos->actualizar($IdPrograma,$Programa,$usuario)==true) {
				echo "Registro Actualizado"	;	
		   }
	 	   else{
			   echo "No se pudo Actualizar el Registro";
		}
		
     break;
     case 3:  //ELIMINAR 
			$IdPrograma=$_POST['IdPrograma'];
			//if (($objdatos->eliminar($IdPrograma)==true) && ($Clases->eliminar_labo($IdPrograma)==true)){		
			if ($objdatos->eliminar($IdPrograma)==true) {		
				echo "Registro Eliminado" ;		
			}
			else{
				echo "El registro no pudo ser Eliminado ";
			}			
     break;
     case 4: // PAGINACION
		$Pag =$_POST['Pag'];
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
			   <td class='CobaltFieldCaptionTD'> IdPrograma</td>
			   <td class='CobaltFieldCaptionTD'> Programa </td>	   
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
            $Programa=$_POST['Programa'];
			
			$query = "SELECT mnt_programas.IdPrograma,NombrePrograma 
					  FROM mnt_programasxesteblecimiento
					  INNER JOIN mnt_programas ON mnt_programas.IdPrograma=mnt_programasxesteblecimiento.IdPrograma 
					  WHERE IdEstablecimiento=$lugar AND ";
				
			//VERIFICANDO LOS POST ENVIADOS
			if (!empty($_POST['Programa']))
			{ $query .= " NombrePrograma like'%".$_POST['Programa']."%' "; }
		
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
				   <td class='CobaltFieldCaptionTD'> IdPrograma</td>
				   <td class='CobaltFieldCaptionTD'> Programa </td>	   
				   </tr>";

			while($row = mysql_fetch_array($consulta)){
				  echo "<tr>
						<td aling='center'> 
						<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
						onclick=\"pedirDatos('".$row[0]."')\"> </td>
						<td aling ='center'> 
						<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
						onclick=\"eliminarDato('".$row[0]."')\"> </td>
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
				$cond=$_POST['condicion'];
				$idprograma=$_POST['idprog'];
             //	echo $idexamen."-".$condicion;
				//$resultado=Estado::EstadoCuenta($idexamen,$cond,$lugar);
				$resultado=$objdatos->EstadoCuenta($idprograma,$cond,$lugar);
	 break;
}

?>