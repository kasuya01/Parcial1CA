<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include_once("clsElementosExamen.php");
include('../Lab_Areas/clsLab_Areas.php');
$objdatos = new clsElementosExamen;
$objeareas=new clsLab_Areas;
//$Clases = new clsLabor_ElementosExamen;

//variables POST
$opcion=$_POST['opcion'];

//actualiza los datos del empleados

switch ($opcion) 
{
	case 1:  //INSERTAR	
		$idexamen=$_POST['idexamen'];
		$nomelemento=$_POST['elemento'];	
		$subelemento=$_POST['subelemento'];
                $unidadele=(empty($_POST['unidadele'])) ? 'NULL' : "'" . pg_escape_string($_POST['unidadele']) . "'";
                $observacionele=(empty($_POST['observacionele'])) ? 'NULL' : "'" . pg_escape_string($_POST['observacionele']) . "'";  
		$Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
                $Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";
		
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
		$unidadele=(empty($_POST['unidadele'])) ? 'NULL' : "'" . pg_escape_string($_POST['unidadele']) . "'";
                $observacionele=(empty($_POST['observacionele'])) ? 'NULL' : "'" . pg_escape_string($_POST['observacionele']) . "'";  
		$Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
                $Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";
		
		If ($objdatos->actualizar($idelemento,$nomelemento,$subelemento,$unidadele,$observacionele,$usuario,$lugar,$Fechaini,$Fechafin)==true) 
                        /*&& $Clases->actualizar_labo($idelemento,$nomelemento,$subelemento,$unidadele,$observacionele,$usuario,$lugar,$Fechaini,$Fechafin)==true){*/
			echo "Registro Actualizado";			
		
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
			<td class='CobaltFieldCaptionTD'> Examen </td>
			<td class='CobaltFieldCaptionTD'> Elemento </td>
			<td class='CobaltFieldCaptionTD'> Unidad </td>
			<td class='CobaltFieldCaptionTD'> Observación</td>
			<td class='CobaltFieldCaptionTD'> Tiene Sub-Elemento</td>
			<td class='CobaltFieldCaptionTD'> Fecha Inicio</td>	
			<td class='CobaltFieldCaptionTD'> Fecha Fin</td>		
                    </tr>";
		while($row = pg_fetch_array($consulta)){
                echo"<tr>
			<td aling='center'> 
                            <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                            onclick=\"pedirDatos('".$row['idelemento']."')\"> </td>
				<td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row['idelemento']."')\"> </td>
				<td>".$row['nombre_examen']."</td>
				<td>".htmlentities($row['elemento'])."</td>";
				if (empty($row['unidadelem']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 
			        echo"<td>".htmlentities($row['unidadelem'])."</td>";
				
				if (empty($row['observelem']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 		
					echo"<td>".htmlentities($row['observelem'])."</td>";
				
				echo "<td>".htmlentities($row['subelemento'])."</td>";
				//echo $row['fechaini'];
                             if (!empty($row['fechaini']))
                                echo "<td>".$row['fechaini']."</td>";
                            else
                                 echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
                            
                             if(!empty($row['fechafin']))
                                 echo "<td>".$row['fechafin']."</td>";
                            else
                                 echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";	
                          
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
                //echo $lugar;
		//$unidadele=$_POST['unidadele'];	
		//$observacionele=$_POST['observacionele'];
                $unidadele=(empty($_POST['unidadele'])) ? 'NULL' : "'" . pg_escape_string($_POST['unidadele']) . "'";
                $observacionele=(empty($_POST['observacionele'])) ? 'NULL' : "'" . pg_escape_string($_POST['observacionele']) . "'";  
		$subelemento=$_POST['subelemento'];
		$Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
		$Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";
                
		$query = "SELECT lab_elementos.id,lab_conf_examen_estab.codigo_examen as idexamen,lab_conf_examen_estab.nombre_examen,unidadelem,observelem,subelemento,elemento,
                          lab_conf_examen_estab.nombre_examen,mnt_area_examen_establecimiento.id_area_servicio_diagnostico as idarea,
                         (CASE WHEN subelemento='S' 
                         THEN 'SI' 
                         ELSE 'NO' END ) AS subelemento,unidadelem,observelem,
                         to_char(fechaini,'dd/mm/YYYY') AS fechaini,
                         to_char(fechafin,'dd/mm/YYYY') AS fechafin  
                         FROM lab_elementos  
			 INNER JOIN lab_conf_examen_estab ON lab_elementos.id_conf_examen_estab=lab_conf_examen_estab.id 
                         INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                         INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                         INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
			 WHERE lab_conf_examen_estab.condicion='H' 
			 AND lab_areasxestablecimiento.condicion='H' AND lab_conf_examen_estab.idplantilla=2 
			 AND lab_elementos.idestablecimiento=$lugar AND";
                
               /* SELECT nombrearea, 
                lab_elementos.idestablecimiento,*/
                
		//$ban1=0;
		$ban=0;

		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['idarea']))
		{ $query .= " mnt_area_examen_establecimiento.id_area_servicio_diagnostico=".$_POST['idarea']." AND"; }
		
		if (!empty($_POST['idexamen']))
		{ $query .= " lab_conf_examen_estab.id=".$_POST['idexamen']." AND"; }
					
		if (!empty($_POST['elemento']))
		{ $query .= " TRANSLATE(elemento,'ÁÉÍÓÚáéíóú','AEIOUaeiou') ilike '%".$_POST['elemento']."%' AND"; }
		
		if (!empty($_POST['unidadele']))
		{ $query .= " unidadelem ilike '".$_POST['unidadele']."' AND"; }
		
		if (!empty($_POST['observacionele']))
		{ $query .= " observelem ilike '".$_POST['observacionele']."' AND"; }
		
		if (!empty($_POST['subelemento']))
		{ $query .= " subelemento = '".$_POST['subelemento']."' AND"; }
		
		if (!empty($_POST['Fechaini']))
		{ 	$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
			$query .= " fechaini='".$Fechaini."' AND"; }

		if (!empty($_POST['Fechafin'])){
			$FechaF=explode('/',$_POST['Fechafin']);
	  		$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
			$query .= " fechafin=".$Fechafin." AND"; } 			
     //  else{$ban=1;}
		if((empty($_POST['idarea'])) and (empty($_POST['idexamen'])) and (empty($_POST['elemento'])) and (empty($_POST['unidadele'])) and (empty($_POST['observacionele'])) and (empty($_POST['subelemento'])) and (empty($_POST['Fechafin'])) and (empty($_POST['Fechaini'])))
		{
			$ban=1;
		}
		if ($ban==0)
		{    $query = substr($query ,0,strlen($query)-4);
			 $query_search = $query. " ORDER BY mnt_area_examen_establecimiento.id_area_servicio_diagnostico,lab_elementos.id ";
			 
		}
		else {
                        $query = substr($query ,0,strlen($query)-4);
			$query_search = $query. " ORDER BY mnt_area_examen_establecimiento.id_area_servicio_diagnostico,lab_elementos.id";
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
			    <td class='CobaltFieldCaptionTD'>  Examen </td>
			    <td class='CobaltFieldCaptionTD'> Elemento </td>
				<td class='CobaltFieldCaptionTD'> Unidad </td>
				<td class='CobaltFieldCaptionTD'> Observación</td>
				<td class='CobaltFieldCaptionTD'> Tiene Sub-Elemento</td>
				<td class='CobaltFieldCaptionTD'> Fecha Inicio</td>	
				<td class='CobaltFieldCaptionTD'> Fecha Fin</td>		
		      </tr>";
		while($row = pg_fetch_array($consulta)){
		echo "<tr>
		        <td aling='center'> 
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"pedirDatos('".$row['id']."')\"> </td>
			    <td aling ='center'> 
				<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"eliminarDato('".$row['id']."')\"> </td>
			    <td>".$row['nombre_examen']."</td>
			    <td>".htmlentities($row['elemento'])."</td>";
			    
				if (empty($row['unidadelem']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 
			        echo"<td>".htmlentities($row['unidadelem'])."</td>";
				
				if (empty($row['observelem']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 		
					echo"<td>".htmlentities($row['observelem'])."</td>";
				
				echo"<td>".htmlentities($row['subelemento'])."</td>";
				//echo $row['fechaini'];
				if (($row['fechaini']=="(NULL)") || (empty($row['fechaini'])))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 		
					echo "<td>".htmlentities($row['fechaini'])."</td>";
				
				if (($row['fechafin']=="0000-00-00") ||($row['fechafin']=="(NULL)") ||(empty($row['fechafin'])))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 	
					echo "<td>".htmlentities($row['fechafin'])."</td>
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
		
		$query = "SELECT lab_elementos.id,lab_conf_examen_estab.codigo_examen as idexamen,lab_conf_examen_estab.nombre_examen,unidadelem,observelem,subelemento,elemento,
                          lab_conf_examen_estab.nombre_examen,mnt_area_examen_establecimiento.id_area_servicio_diagnostico as idarea,
                         (CASE WHEN subelemento='S' 
                         THEN 'SI' 
                         ELSE 'NO' END ) AS subelemento,unidadelem,observelem,
                         to_char(fechaini,'dd/mm/YYYY') AS fechaini,
                         to_char(fechafin,'dd/mm/YYYY') AS fechafin  
                         FROM lab_elementos  
			 INNER JOIN lab_conf_examen_estab ON lab_elementos.id_conf_examen_estab=lab_conf_examen_estab.id 
                         INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                         INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                         INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
			 WHERE lab_conf_examen_estab.condicion='H' 
			 AND lab_areasxestablecimiento.condicion='H' AND lab_conf_examen_estab.idplantilla=2 
			 AND lab_elementos.idestablecimiento=$lugar AND";
		//$ban1=0;
		$ban=0;

		if (!empty($_POST['idarea']))
		{ $query .= " mnt_area_examen_establecimiento.id_area_servicio_diagnostico=".$_POST['idarea']." AND"; }
		
		if (!empty($_POST['idexamen']))
		{ $query .= " lab_conf_examen_estab.id=".$_POST['idexamen']." AND"; }
					
		if (!empty($_POST['elemento']))
		{ $query .= " TRANSLATE(elemento,'ÁÉÍÓÚáéíóú','AEIOUaeiou') ilike '%".$_POST['elemento']."%' AND"; }
		
		if (!empty($_POST['unidadele']))
		{ $query .= " unidadelem ilike '".$_POST['unidadele']."' AND"; }
		
		if (!empty($_POST['observacionele']))
		{ $query .= " observelem ilike '".$_POST['observacionele']."' AND"; }
		
		if (!empty($_POST['subelemento']))
		{ $query .= " subelemento = '".$_POST['subelemento']."' AND"; }
		
		if (!empty($_POST['Fechaini']))
		{ 	$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
			$query .= " fechaini='".$Fechaini."' AND"; }

		if (!empty($_POST['Fechafin'])){
			$FechaF=explode('/',$_POST['Fechafin']);
	  		$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
			$query .= " fechafin=".$Fechafin." AND"; } 			
     //  else{$ban=1;}
		if((empty($_POST['idarea'])) and (empty($_POST['idexamen'])) and (empty($_POST['elemento'])) and (empty($_POST['unidadele'])) and (empty($_POST['observacionele'])) and (empty($_POST['subelemento'])) and (empty($_POST['Fechafin'])) and (empty($_POST['Fechaini'])))
		{
			$ban=1;
		}
		if ($ban==0)
		{    $query = substr($query ,0,strlen($query)-4);
			 $query_search = $query. " ORDER BY mnt_area_examen_establecimiento.id_area_servicio_diagnostico,lab_elementos.id ";
			 
		}
		else {
                        $query = substr($query ,0,strlen($query)-4);
			$query_search = $query. " ORDER BY mnt_area_examen_establecimiento.id_area_servicio_diagnostico,lab_elementos.id";
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
			   <td class='CobaltFieldCaptionTD'> Examen </td>
			   <td class='CobaltFieldCaptionTD'> Elemento </td>
			    <td class='CobaltFieldCaptionTD'> Unidad </td>
				<td class='CobaltFieldCaptionTD'> Observación</td>
				<td class='CobaltFieldCaptionTD'> Tiene Sub-Elemento</td>
				<td class='CobaltFieldCaptionTD'> Fecha Inicio</td>	
				<td class='CobaltFieldCaptionTD'> Fecha Fin</td>	
		      </tr>";
		while($row = pg_fetch_array($consulta)){
		echo "<tr>
		           <td aling='center'> 
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"pedirDatos('".$row['id']."')\"> </td>
			   <td aling ='center'> 
				<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"eliminarDato('".$row['id']."')\"> </td>
			   <td>".$row['nombre_examen']."</td>
			   <td>".htmlentities($row['elemento'])."</td>";
			   
			    if (empty($row['unidadelem']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 
			        echo"<td>".htmlentities($row['unidadelem'])."</td>";
				
				if (empty($row['observelem']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 		
					echo"<td>".htmlentities($row['observelem'])."</td>";
				
				echo "<td>".htmlentities($row['subelemento'])."</td>";
				
				if (($row['fechaini']=="00/00/0000") || ($row['fechaini']=="(NULL)") || (empty($row['fechaini'])))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 		
					echo "<td>".htmlentities($row['fechaini'])."</td>";
				
				if (($row['fechafin']=="00/00/0000") || ($row['fechafin']=="(NULL)") || (empty($row['fechafin'])))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				else 	
					echo "<td>".htmlentities($row['fechafin'])."</td>
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
			
		while ($rows =pg_fetch_array($consultaex)){
			$rslts.= '<option value="' .$rows[0].'" >'.htmlentities($rows[1]).'</option>';
		}
		$rslts .= '</select>';
		echo $rslts;	
		
  		
	break;
}

?>