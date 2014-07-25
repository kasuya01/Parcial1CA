<?php session_start();
include_once("clsLab_TipoMuestra.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//variables POST
$idtipo=$_POST['idtipo'];
$nombretipo=$_POST['nombretipo'];
$opcion=$_POST['opcion'];
$Pag=$_POST['Pag'];
//actualiza los datos del empleados
$objdatos = new clsLab_TipoMuestra;

switch ($opcion) 
{
	case 1:  //INSERTAR
            $respuesta=$objdatos->insertar($nombretipo,$usuario);
		if ($respuesta==true)
	    	{
		   echo "Registro Agregado";
	   	}
		else{
                    if ($respuesta==0){
                        echo "Registro previamente ingresado";		
                    }
                    else{
                        echo $respuesta."Error al agregar la muestra ".$nombretipo;		
                    }
				
		}
		
	break;
    	case 2:  //MODIFICAR      
		if ($objdatos->actualizar($idtipo,$nombretipo,$usuario)==true)
		{
			echo "Registro Actualizado";			
		}
		else{
			echo "No se pudo actualizar";
		}
		
	break;
	case 3:  //ELIMINAR    
		  //verificando integridad de los datos
      		$numreg=$objdatos->VerificarIntegridad($idtipo);
	  	if ($numreg == 0)
	 	{
			if ($objdatos->eliminar($idtipo)==true){
	       			 echo "Registro Inhabilitado";
			
	      	  	}
	     	  	else{
	       			echo "El registro no pudo ser inhabilitado ";
	    	 	}
	   	}
		else{
			echo "El registro no puede ser eliminado esta asociado a un Examen";
		}
		
			  
	break;
	case 4:// PAGINACION
		
		//para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpag($RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
				echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
			   <tr>
			   <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Inhabilitado</td>
			   <td class='CobaltFieldCaptionTD'> Nombre Muestra </td>	   
			   </tr>";

		while($row = pg_fetch_array($consulta)){
			echo "<tr>
					<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('".$row['id']."')\"> </td>
					<td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row['id']."')\"> </td>
					<td>".htmlentities($row['tipomuestra'])."</td>
					</tr>";
		}
		echo "</table>"; 
		//determinando el numero de paginas
		 $NroRegistros= $objdatos->NumeroDeRegistros();
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;
		 
		 $PagUlt=ceil($NroRegistros/$RegistrosAMostrar);
		 
		 //verificamos residuo para ver si llevar� decimales
		// $Res=ceil($NroRegistros/$RegistrosAMostrar);
		 

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
	
	
	case 5: //BUSQUEDA   

                    $query = " SELECT id,tipomuestra FROM lab_tipomuestra WHERE tipomuestra ilike '%".$_POST['nombretipo']."%' and habilitado=true"; 
                $NroRegistros =$objdatos->NumeroDeRegistrosbus($query);
                if ($NroRegistros>0){
		//para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];	
		//LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpagbus($query,$RegistrosAEmpezar, $RegistrosAMostrar);
		//muestra los datos consultados en la tabla
		echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
			   <tr>
			   <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Inhabilitado</td>
			   <td class='CobaltFieldCaptionTD'> Nombre Muestra </td>	   
			   </tr>";
		while($row = pg_fetch_array($consulta)){
			echo "<tr>
					<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('".$row['id']."')\"> </td>
					<td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row['id']."')\"> </td>
					<td>".$row['tipomuestra']."</td>
					</tr>";
		}
		echo "</table>"; 

		//determinando el numero de paginas
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;		 
		 $PagUlt=ceil($NroRegistros/$RegistrosAMostrar);
		 
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
                }
                else{
                    echo '<center><h2>No existen registros que coincidan con '.$_POST['nombretipo'].'</center></h2>';
                    }
	   	 
	break;

}

?>