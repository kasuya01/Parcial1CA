<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include_once("clsElementosExamen.php");
include('../Lab_Areas/clsLab_Areas.php');
$objdatos = new clsElementosExamen;
$objeareas=new clsLab_Areas;
$Clases = new clsLabor_ElementosExamen;

//variables POST
$opcion=$_POST['opcion'];

//actualiza los datos del empleados

switch ($opcion) 
{
	case 1:  //INSERTAR	
		$idexamen=$_POST['idexamen'];
		$nomelemento=$_POST['elemento'];	
		$subelemento=$_POST['subelemento'];
		$observacionele=$_POST['observacionele'];
		$unidadele=$_POST['unidadele'];
		 if (empty($_POST['Fechaini'])){
			$Fechaini="NULL";
		}else{ 
			$FechaI=explode('/',$_POST['Fechaini']);
	  		$Fechaini=$FechaI[2].'-'.$FechaI[1].'-'.$FechaI[0];
	  	}
		
		if (empty($_POST['Fechafin'])){
			$Fechafin="NULL";
		}else{ 
			$FechaF=explode('/',$_POST['Fechafin']);
			$Fechafin=$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0];	
		}
		
		//echo $Fechaini."-".$Fechafin;
            if ($objdatos->insertar($idexamen,$nomelemento,$subelemento,$usuario,$observacionele,$unidadele,$lugar,$Fechaini,$Fechafin)==true) 
                /*&& ($Clases->insertar_labo($idexamen,$nomelemento,$subelemento,$usuario,$observacionele,$unidadele,$lugar,$Fechaini,$Fechafin)==true)){*/
			echo "Registro Agregado";
	   	
	   	else
			echo "No se pudo Agregar el Elemento";			
	   	
	break;
	case 2:  //MODIFICAR  
		$idelemento=$_POST['idelemento'];
		$nomelemento=$_POST['elemento'];
		$subelemento=$_POST['subelemento'];
		$unidadele=$_POST['unidadele'];	
		$observacionele=$_POST['observacionele'];
		
		if (empty($_POST['Fechaini'])){
			$Fechaini="NULL";
		}else{ 
			$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini=$FechaI[2].'-'.$FechaI[1].'-'.$FechaI[0];
		}
		
		if (empty($_POST['Fechafin'])){
			$Fechafin="NULL";
		}else{ 
                //echo $_POST['Fechafin'];
			$FechaF=explode('/',$_POST['Fechafin']);
			$Fechafin=$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0];	
		}
		//echo $Fechafin;
	//echo $idelemento."&&&&".$nomelemento."&&&&".$subelemento."&&&&".$unidadele."&&&&".$observacionele."&&&&".$Fechaini."&&&&".$Fechafin;
		If ($objdatos->actualizar($idelemento,$nomelemento,$subelemento,$unidadele,$observacionele,$usuario,$lugar,$Fechaini,$Fechafin)==true) 
                        /*&& $Clases->actualizar_labo($idelemento,$nomelemento,$subelemento,$unidadele,$observacionele,$usuario,$lugar,$Fechaini,$Fechafin)==true){*/
			echo "Registro Actualizado"	;			
		
		else
			echo "No se pudo actualizar";
		
		
	break;
	case 3:  //ELIMINAR    
		 //Vefificando Integridad de los datos
		$idelemento=$_POST['idelemento'];
		
		If (($objdatos->eliminar($idelemento,$lugar)==true) && ($Clases->eliminar_labo($idelemento,$lugar))){		
			echo "Registro Eliminado" ;		
		}
		else{
			echo "El registro no pudo ser eliminado";
		}			
	break;
	case 4:// PAGINACION
		$Pag =$_POST['Pag'];
		//echo  $Pag;
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar);
		//muestra los datos consultados en la tabla
        echo "<table border = 1 align='center' class='StormyWeatherFormTABLE' width='85%'>
                    <tr>
                        <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
			<td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td>
			<td class='CobaltFieldCaptionTD'> C&oacute;digo Examen </td>
			<td class='CobaltFieldCaptionTD'> Elemento </td>
			<td class='CobaltFieldCaptionTD'> Unidad </td>
			<td class='CobaltFieldCaptionTD'> Observación</td>
			<td class='CobaltFieldCaptionTD'> Tiene Sub-Elemento</td>
			<td class='CobaltFieldCaptionTD'> Fecha Inicio</td>	
			<td class='CobaltFieldCaptionTD'> Fecha Fin</td>		
                    </tr>";
		while($row = mysql_fetch_array($consulta)){
                echo"<tr>
			<td aling='center'> 
                            <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                            onclick=\"pedirDatos('".$row['IdElemento']."')\"> </td>
				<td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row['IdElemento']."')\"> </td>
				<td>".$row['IdExamen']."</td>
				<td>".htmlentities($row['Elemento'])."</td>";
				if (empty($row['UnidadElem']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 
			        echo"<td>".htmlentities($row['UnidadElem'])."</td>";
				
				if (empty($row['ObservElem']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 		
					echo"<td>".htmlentities($row['ObservElem'])."</td>";
				
				echo "<td>".htmlentities($row['SubElemento'])."</td>";
				
				if (($row['FechaIni']=="00-00-0000") ||($row['FechaIni']=="(NULL)") ||(empty($row['FechaIni'])))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 		
					echo "<td>".htmlentities($row['FechaIni'])."</td>";
				
				if (($row['FechaFin']=="00-00-0000") ||($row['FechaFin']=="(NULL)") ||(empty($row['FechaFin'])) )
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 	
					echo "<td>".htmlentities($row['FechaFin'])."</td>";
		    echo "</tr>";
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
			<td><a onclick=\"show_event('1')\">Primero</a> </td>";
		//// desplazamiento
	 if($PagAct>1) 
		  echo "<td> <a onclick=\"show_event('$PagAnt')\">Anterior</a> </td>";
	 if($PagAct<$PagUlt)  
	          echo "<td> <a onclick=\"show_event('$PagSig')\">Siguiente</a> </td>";
		  echo "<td> <a onclick=\"show_event('$PagUlt')\">Ultimo</a></td>";
		  echo "</tr>
		 </table>";
	break;
	case 5:  //LEER ULTIMO CODIGO  
	break;
	case 6: 
  	break;
	 
	case 7: //BUSQUEDA
		$idarea=$_POST['idarea'];
		$idexamen=$_POST['idexamen'];
		$nomelemento=$_POST['elemento'];
		$unidadele=$_POST['unidadele'];	
		$observacionele=$_POST['observacionele'];
		$subelemento=$_POST['subelemento'];
		
		$query = "SELECT IdElemento,lab_elementos.IdExamen,UnidadElem,ObservElem,SubElemento,Elemento,
                          lab_examenes.NombreExamen,lab_examenes.IdArea,IF(SubElemento='S','SI','NO')AS SubElemento,UnidadElem,ObservElem,DATE_FORMAT(FechaIni,'%d/%m/%Y')AS FechaIni,
			DATE_FORMAT(FechaFin,'%d/%m/%Y')AS FechaFin 
			FROM lab_elementos  
			INNER JOIN lab_examenes ON lab_elementos.IdExamen=lab_examenes.IdExamen 
			INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen	
			INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea 
			INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
			WHERE lab_examenesxestablecimiento.Condicion='H' 
			AND lab_areasxestablecimiento.Condicion='H' AND lab_examenesxestablecimiento.IdPlantilla='B' 
			AND lab_elementos.IdEstablecimiento=$lugar AND";
		//$ban1=0;
		$ban=0;

		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['idarea']))
		{ $query .= " lab_examenes.IdArea='".$_POST['idarea']."' AND"; }
		
		if (!empty($_POST['idexamen']))
		{ $query .= " lab_elementos.IdExamen='".$_POST['idexamen']."' AND"; }
					
		if (!empty($_POST['elemento']))
		{ $query .= " Elemento='".$_POST['elemento']."' AND"; }
		
		if (!empty($_POST['unidadele']))
		{ $query .= " UnidadElem ='".$_POST['unidadele']."' AND"; }
		
		if (!empty($_POST['observacionele']))
		{ $query .= " ObservElem ='".$_POST['observacionele']."' AND"; }
		
		if (!empty($_POST['subelemento']))
		{ $query .= " SubElemento='".$_POST['subelemento']."' AND"; }
		
		if (!empty($_POST['Fechaini']))
		{ 	$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
			$query .= " FechaIni='".$Fechaini."' AND"; }

		if (!empty($_POST['Fechafin'])){
			$FechaF=explode('/',$_POST['Fechafin']);
	  		$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
			$query .= " FechaFin='".$Fechafin."' AND"; } 
			
     //  else{$ban=1;}
		if((empty($_POST['idarea'])) and (empty($_POST['idexamen'])) and (empty($_POST['elemento'])) and (empty($_POST['unidadele'])) and (empty($_POST['observacionele'])) and (empty($_POST['subelemento'])) and (empty($_POST['Fechafin'])) and (empty($_POST['Fechaini'])))
		{
			$ban=1;
		}
		if ($ban==0)
		{    $query = substr($query ,0,strlen($query)-4);
			 $query_search = $query. " ORDER BY lab_examenes.IdArea,IdElemento ";
			 
		}
		else {
				 $query = substr($query ,0,strlen($query)-6);
			$query_search = $query. " ORDER BY lab_examenes.IdArea,IdElemento";
		}	
	//echo $query_search;
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
		
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		 /////LAMANDO LA FUNCION DE LA CLASE 
		
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);
	
		//muestra los datos consultados en la tabla
	  echo "<table border = 1 align='center' class='StormyWeatherFormTABLE' width='85%'>
		      <tr>
			    <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
			    <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td>
			    <td class='CobaltFieldCaptionTD'> C&oacute;digo Examen </td>
			    <td class='CobaltFieldCaptionTD'> Elemento </td>
				<td class='CobaltFieldCaptionTD'> Unidad </td>
				<td class='CobaltFieldCaptionTD'> Observación</td>
				<td class='CobaltFieldCaptionTD'> Tiene Sub-Elemento</td>
				<td class='CobaltFieldCaptionTD'> Fecha Inicio</td>	
				<td class='CobaltFieldCaptionTD'> Fecha Fin</td>		
		      </tr>";
		while($row = mysql_fetch_array($consulta)){
		echo "<tr>
		        <td aling='center'> 
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"pedirDatos('".$row['IdElemento']."')\"> </td>
			    <td aling ='center'> 
				<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"eliminarDato('".$row['IdElemento']."')\"> </td>
			    <td>".$row['IdExamen']."</td>
			    <td>".htmlentities($row['Elemento'])."</td>";
			    
				if (empty($row['UnidadElem']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 
			        echo"<td>".htmlentities($row['UnidadElem'])."</td>";
				
				if (empty($row['ObservElem']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 		
					echo"<td>".htmlentities($row['ObservElem'])."</td>";
				
				echo"<td>".htmlentities($row['SubElemento'])."</td>";
				
				if (($row['FechaIni']=="0000-00-00") ||($row['FechaIni']=="(NULL)") || (empty($row['FechaIni'])))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 		
					echo "<td>".htmlentities($row['FechaIni'])."</td>";
				
				if (($row['FechaFin']=="0000-00-00") ||($row['FechaFin']=="(NULL)") ||(empty($row['FechaFin'])))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 	
					echo "<td>".htmlentities($row['FechaFin'])."</td>
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
		    <td><a onclick=\"show_event_search('1')\">Primero</a> </td>";
		//// desplazamiento
          	 if($PagAct>1) 
			 echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
		 if($PagAct<$PagUlt)  
			 echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
			 echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
		</table>";
	break;
	case 8://PAGINACION DE BUSQUEDA
	
		$idarea=$_POST['idarea'];
		$idexamen=$_POST['idexamen'];
		$nomelemento=$_POST['elemento'];
		$unidadele=$_POST['unidadele'];	
		$observacionele=$_POST['observacionele'];
		$subelemento=$_POST['subelemento'];
		
		$query = "SELECT IdElemento,lab_elementos.IdExamen,UnidadElem,ObservElem,SubElemento,Elemento,
			lab_examenes.NombreExamen,lab_examenes.IdArea,IF(SubElemento='S','SI','NO')AS SubElemento,UnidadElem,ObservElem,
			DATE_FORMAT(FechaIni,'%d/%m/%Y')AS FechaIni,DATE_FORMAT(FechaFin,'%d/%m/%Y')AS FechaFin  
			FROM lab_elementos  
			INNER JOIN lab_examenes ON lab_elementos.IdExamen=lab_examenes.IdExamen 
			INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen	
			INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea 
			INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
			WHERE lab_examenesxestablecimiento.Condicion='H' 
			AND lab_areasxestablecimiento.Condicion='H' AND lab_examenesxestablecimiento.IdPlantilla='B' 
			AND lab_elementos.IdEstablecimiento=$lugar AND";
		//$ban1=0;
		$ban=0;

		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['idarea']))
		{ $query .= " lab_examenes.IdArea='".$_POST['idarea']."' AND"; }
		
		if (!empty($_POST['idexamen']))
		{ $query .= " lab_elementos.IdExamen='".$_POST['idexamen']."' AND"; }
					
		if (!empty($_POST['elemento']))
		{ $query .= " Elemento='".$_POST['elemento']."' AND"; }
		
		if (!empty($_POST['unidadele']))
		{ $query .= " UnidadElem ='".$_POST['unidadele']."' AND"; }
		
		if (!empty($_POST['observacionele']))
		{ $query .= " ObservElem ='".$_POST['observacionele']."' AND"; }
		
		if (!empty($_POST['subelemento']))
		{ $query .= " SubElemento='".$_POST['subelemento']."' AND"; }
		
	
		
     		if((empty($_POST['idarea'])) and (empty($_POST['idexamen'])) and (empty($_POST['elemento'])) and (empty($_POST['unidadele'])) and (empty($_POST['observacionele'])) and (empty($_POST['subelemento'])) and (empty($_POST['Fechaini'])) and (empty($_POST['Fechafin'])))
		{
			$ban=1;
		}
		if ($ban==0)
		{    $query = substr($query ,0,strlen($query)-4);
			 $query_search = $query. " ORDER BY lab_examenes.IdArea,IdElemento ";
			 
		}
	
	
		//echo $ban;
		//echo $query_search;
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
		
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		 /////LAMANDO LA FUNCION DE LA CLASE 
		
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);
	
		//muestra los datos consultados en la tabla
		echo "<table border = 1 align='center' class='StormyWeatherFormTABLE' width='85%'>
		      <tr>
			   <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td>
			   <td class='CobaltFieldCaptionTD'> C&oacute;digo Examen </td>
			   <td class='CobaltFieldCaptionTD'> Elemento </td>
			    <td class='CobaltFieldCaptionTD'> Unidad </td>
				<td class='CobaltFieldCaptionTD'> Observación</td>
				<td class='CobaltFieldCaptionTD'> Tiene Sub-Elemento</td>
				<td class='CobaltFieldCaptionTD'> Fecha Inicio</td>	
				<td class='CobaltFieldCaptionTD'> Fecha Fin</td>	
		      </tr>";
		while($row = mysql_fetch_array($consulta)){
		echo "<tr>
		           <td aling='center'> 
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"pedirDatos('".$row['IdElemento']."')\"> </td>
			   <td aling ='center'> 
				<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"eliminarDato('".$row['IdElemento']."')\"> </td>
			   <td>".$row['IdExamen']."</td>
			   <td>".htmlentities($row['Elemento'])."</td>";
			   
			    if (empty($row['UnidadElem']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 
			        echo"<td>".htmlentities($row['UnidadElem'])."</td>";
				
				if (empty($row['ObservElem']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 		
					echo"<td>".htmlentities($row['ObservElem'])."</td>";
				
				echo "<td>".htmlentities($row['SubElemento'])."</td>";
				
				if (($row['FechaIni']=="00/00/0000") || ($row['FechaIni']=="(NULL)") || (empty($row['FechaIni'])))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 		
					echo "<td>".htmlentities($row['FechaIni'])."</td>";
				
				if (($row['FechaFin']=="00/00/0000") || ($row['FechaFin']=="(NULL)") || (empty($row['FechaFin'])))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 	
					echo "<td>".htmlentities($row['FechaFin'])."</td>
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
		    <td><a onclick=\"show_event_search('1')\">Primero</a> </td>";
		//// desplazamiento
          	 if($PagAct>1) 
			 echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
		 if($PagAct<$PagUlt)  
			 echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
			 echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
		</table>";
			 
			  
	break;
	
	case 9: //LLENAR COMBO DE EXAMENES  
		$idarea=$_POST['idarea'];
           	//echo $idarea; 
	  	$rslts='';
		$consultaex= $objdatos->ExamenesPorArea($idarea,$lugar);
		//$dtMed=$obj->LlenarSubServ($proce);	
		
		$rslts = '<select name="cmbExamen" id="cmbExamen" size="1" >';
		$rslts .='<option value="0">--Seleccione un Examen--</option>';
			
		while ($rows =mysql_fetch_array($consultaex)){
			$rslts.= '<option value="' .$rows[0].'" >'.htmlentities($rows[1]).'</option>';
		}
		$rslts .= '</select>';
		echo $rslts;	
		
  		
	break;
}

?>