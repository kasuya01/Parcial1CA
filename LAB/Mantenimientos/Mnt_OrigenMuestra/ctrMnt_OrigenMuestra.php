<?php session_start();
include_once("clsMnt_OrigenMuestra.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//variables POST
$idorigen=$_POST['idorigen'];
$nombreorigen=$_POST['nombreorigen'];
$tipomuestra=$_POST['tipomuestra'];
$Pag =$_POST['Pag'];

$opcion=$_POST['opcion'];
//actualiza los datos del empleados
$objdatos = new clsMnt_OrigenMuestra;

switch ($opcion) 
{
    case 1:  //INSERTAR	
	//echo $tipomuestra;
		if ($objdatos->insertar($nombreorigen,$tipomuestra,$usuario)==true)
		{	echo "Registro Agregado";}
		else{
			echo "No se pudo Agregar";}
		
		
		break;
    case 2:  //MODIFICAR  
        //echo $nombreorigen;    
		
		If ($objdatos->actualizar($idorigen,$tipomuestra,$nombreorigen,$usuario)==true){
				echo "Registro Actualizado";
		}
		else{
			 echo "No se pudo Actualizado";
		}
			
		
		
		
     	break;
	case 3:  //ELIMINAR    
	
		 //Vefificando Integridad de los datos
		if ($objdatos->eliminar($idorigen)==true){		
			echo "Registro Eliminado";		
			
	      	}else{
                	echo "El registro no pudo ser eliminado";
		}	
				
		
	break;
	case 4:// PAGINACION
		
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		 
		$consulta= $objdatos->consultarpag($RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
			   <tr>
			   <td  class='CobaltFieldCaptionTD' aling='center'> Modificar</td>
			   <td  class='CobaltFieldCaptionTD' aling='center'> Eliminar</td>
			   <td class='CobaltFieldCaptionTD'> Código Origen</td>
			   <td class='CobaltFieldCaptionTD'> Origen Muestra </td>	   
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
					<td> ".htmlentities($row[2])."</td>
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
			 echo "<td> <a onclick=\"show_event('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
			  </table>";
	break;
	case 5:
		$query = "SELECT IdOrigenMuestra,OrigenMuestra FROM mnt_origenmuestra WHERE ";
				
		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['tipomuestra']))
		{ $query .= " IdTipoMuestra = '".$_POST['tipomuestra']."' AND"; }
		
		if (!empty($_POST['nombreorigen']))
		{ $query .= " OrigenMuestra like'%".$_POST['nombreorigen']."%' AND"; }
		
		$query = substr($query ,0,strlen($query)-4);
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
			   <td  class='CobaltFieldCaptionTD' aling='center'> Modificar</td>
			   <td  class='CobaltFieldCaptionTD' aling='center'> Eliminar</td>
			   <td class='CobaltFieldCaptionTD'> Código Origen</td>
			   <td class='CobaltFieldCaptionTD'> Origen Muestra </td>	   
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
}

?>