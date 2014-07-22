<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include_once("clsLab_Areas.php");

//variables POST
//$idarea=$_POST['idarea'];
//$nom=$_POST['nombrearea'];
//$activo=$_POST['activo'];

//$Pag =$_POST['Pag'];
$opcion=$_POST['opcion'];
//actualiza los datos del empleados
$objdatos = new clsLab_Areas;

//echo $lugar."-".$area."-".$usuario;
switch ($opcion) 
{
  case 1:  //INSERTAR	
		$idarea=$_POST['idarea'];
		$nom=$_POST['nombrearea'];
		$tipo=$_POST['tipo'];
		$activo=$_POST['activo'];
                $idinsertado=0;
		//echo $activo;
      	if($objdatos->insertar($idarea,$nom,$usuario,$tipo,$lugar)==true)
		{ 
            $consultaUltimoReg=$objdatos->recuperarultimoreg();
            $idinsertado=$objdatos->ultimoregistroinsertado_lab_areas;
            if ($activo=='S'){
                      $cond='H';
            }else{ 
                      $cond='I'; 
			}
            //echo $cond;
			if($objdatos->IngresarAreaxEstablecimiento($idinsertado,$activo,$lugar,$usuario)==true){
			echo "Registro Agregado";
			//$objeda=new clsLab_Areas;
			//$valor=$objdatos->InsertarParametro($idarea,$nom);
			
 		} //if de establesimiento
           	else{
                     echo "No se pudo Agregar el Registro";
                     }
		}
		else{
			echo "No se pudo Agregar el Registro";
		}
  break;
  case 2:  //MODIFICAR 
		$idarea=$_POST['idarea'];
		$nom=$_POST['nombrearea'];
		$tipo=$_POST['tipo'];
		$activo=$_POST['activo'];
		
	// echo $activo;
	//echo $lugar."-".$area."-".$usuario."-".$nom."-".$activo."-".$idarea;
	
 	if ($activo=='S')
        	$cond='H';
    else 
        	$cond='I'; 
	
         
//ECHO $activo."-".$cond;
	If ($objdatos->actualizar($idarea,$nom,$usuario,$tipo)==true){
            //echo $lugar."-".$area."-".$usuario."-".$nom."-".$activo."-".$idarea;
		
               If($objdatos->ActualizarxEstablercimiento($idarea,$activo,$lugar,$usuario)==true){
			echo "Registro Actualizado"	;	            // ($idarea,$activo,$lugar,$usuario)
		}
                else 
                    echo "No se pudo Actualizar el Registro";
    	}
	else
		echo "No se pudo Actualizar el Registro";
  break;
  case 3:  //ELIMINAR    
		 //Vefificando Integridad de los datos
		$idarea=$_POST['idarea'];
		//$nom=$_POST['nombrearea'];
	if ($objdatos->VerificarIntegridad($idarea)==true){		
		echo "El Registro No puede ser eliminado tiene datos asociados" ;							
	}
	else{
		//echo $idarea;
		if ($objdatos->eliminar($idarea)==true){
			if ($objdatos->EliminarxEstablecimiento($idarea)==true){
                        } 
			else{
				echo "El registro no pudo ser eliminado 1 ";
			}
                        	echo "Registro Eliminado" ;		
		}else// de Eliminar
			echo "El registro no pudo ser eliminado ";
						
	}//else de integridad
  break;
  case 4:// PAGINACION
		$Pag =$_POST['Pag'];
		//echo $Pag; 
		//para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 //LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
		   <tr>
			   <td class='CobaltFieldCaptionTD' aling='center'> Modificar</td>
			 <!--  <td class='CobaltFieldCaptionTD' aling='center'> Eliminar</td> -->
			   <td class='CobaltFieldCaptionTD'> IdArea</td>
			   <td class='CobaltFieldCaptionTD'> Nombre </td>
			   <td class='CobaltFieldCaptionTD'>Activa </td>
				<td class='CobaltFieldCaptionTD'>Tipo &Aacute;rea</td>
			   </tr>";

		while($row = pg_fetch_array($consulta)){
			echo "<tr>
					<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('".$row['IdArea']."')\"> </td>
					<!-- <td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row['IdArea']."')\"> </td> -->
					<td>".$row['IdArea']."</td>
					<td>".htmlentities($row['NombreArea'])."</td>";
					if ($row['Condicion']=='H')
						echo "<td>SI</td>";
					else	
						echo "<td>NO</td>";
					
					if ($row['Administrativa']=='S')
						echo "<td>Administrativa</td>";
					else	
						echo "<td>Técnica</td>";
					echo"</tr>";
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
  case 5:// echo $nom."-".$idarea;
		$Pag =$_POST['Pag'];
		$idarea=$_POST['idarea'];
		$nom=$_POST['nombrearea'];
		$activo=$_POST['activo'];
		$tipo=$_POST['tipo'];
		//echo $activo;
		//$Pag =$_POST['Pag'];
	    $query = "SELECT lab_areas.id,lab_areas.nombrearea,administrativa,lab_areasxestablecimiento.condicion,idestablecimiento
		      FROM lab_areas
		      INNER JOIN lab_areasxestablecimiento ON lab_areas.id =lab_areasxestablecimiento.id  
		      WHERE lab_areasxestablecimiento.idestablecimiento=$lugar AND";
					
		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['idarea']))
		{ $query .= " lab_areas.IdArea like '%".$_POST['idarea']."%' AND"; }
		
		if (!empty($_POST['nombrearea']))
		{ $query .= " NombreArea like '%".$_POST['nombrearea']."%' AND"; }

		if (!empty($_POST['activo']))
		{ $query .= " lab_areasxestablecimiento.Condicion='".$_POST['activo']."' AND"; }
		
		if (!empty($_POST['tipo']))
		{ $query .= " lab_areas.Administrativa='".$_POST['tipo']."' AND"; }
		
		$query = substr($query ,0,strlen($query)-3);
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
                            <td class='CobaltFieldCaptionTD' aling='center'> Modificar</td>
			 <!--   <td class='CobaltFieldCaptionTD' aling='center'> Eliminar</td> -->
			    <td class='CobaltFieldCaptionTD'> IdArea</td>
			    <td class='CobaltFieldCaptionTD'> Nombre </td>
			    <td class='CobaltFieldCaptionTD'>Activa </td>
			    <td class='CobaltFieldCaptionTD'>Tipo &Aacute;rea</td>			   
			 </tr>";

		while($row = pg_fetch_array($consulta)){
		   echo "<tr>
                            <td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('".$row[0]."')\"> </td>
			  <!--  <td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row[0]."')\"> </td> -->
			    <td>".$row[0]."</td>
			    <td>".htmlentities($row[1])."</td>";
		   if($row[2]=='H')
                       echo "<td>SI</td>";
		   else	
			echo "<td>NO</td>";
		   if ($row[3]=='S')
			echo "<td>Administrativa</td>";
		   else	
			echo "<td>Técnica</td>";
                    echo"</tr>";
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