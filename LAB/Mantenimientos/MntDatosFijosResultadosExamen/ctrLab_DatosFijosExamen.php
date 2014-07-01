<?php session_start();
include_once("clsLab_DatosFijosExamen.php");
include('../Lab_Areas/clsLab_Areas.php');
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

//variables POST

$opcion=$_POST['opcion'];

$objdatos = new clsLab_DatosFijosExamen;
$objeareas=new clsLab_Areas;
$Clases = new clsLabor_DatosFijosExamen;

switch ($opcion) 
{
	case 1:  //INSERTAR	
		//echo $nota;
		$idexamen=$_POST['idexamen'];
		$idarea=$_POST['idarea'];
		$unidades=$_POST['unidades'];
		$nota=$_POST['nota'];
                $sexo=$_POST['sexo'];
                $redad=$_POST['redad'];
              // echo $sexo."**".$redad;
		if (empty($_POST['rangoinicio'])){
			$rangoinicio="(NULL)";
		}else{
			$rangoinicio=$_POST['rangoinicio'];
		}
	
		if (empty($_POST['rangofin'])){
			$rangofin="(NULL)";
		}else{
			$rangofin=$_POST['rangofin'];
		}
		
        if (empty($_POST['Fechaini'])){
			$Fechaini="0000-00-00"; 
		}else{ 
			$FechaI=explode('/',$_POST['Fechaini']);
	  		$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
	  	}
		
		if (empty($_POST['Fechafin'])){
			$Fechafin="0000-00-00";
		}else{ 
			$FechaF=explode('/',$_POST['Fechafin']);
			$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];	
		}

		if ($objdatos->insertar($idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)==true) 
                     /*   && 
		    ($Clases->insertar_labo($idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)==true))*/
                {
		   	echo "Registro Agregado";
	   	}
		else{
			echo "No se pudo Agregar";			
		}
			
	break;
    	case 2:  //MODIFICAR      
			$idexamen=$_POST['idexamen'];
			$idarea=$_POST['idarea'];
			$unidades=$_POST['unidades'];
			$iddatosfijosresultado=$_POST['iddatosfijosexamen'];
		        $sexo=$_POST['sexo'];
                        $redad=$_POST['redad']; 
			if (empty($_POST['rangoinicio'])){
				$rangoinicio="(NULL)";
			}else{
				$rangoinicio=$_POST['rangoinicio'];
			}
		
			if (empty($_POST['rangofin'])){
				$rangofin="(NULL)";
			}else{
				$rangofin=$_POST['rangofin'];
			}
					$nota=$_POST['nota'];

			if (empty($_POST['Fechaini'])){
				$Fechaini="0000-00-00";
			}else{ 
				$FechaI=explode('/',$_POST['Fechaini']);
				$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
			}
			if (empty($_POST['Fechafin'])){
				$Fechafin="0000-00-00";
			}else{ 
				$FechaF=explode('/',$_POST['Fechafin']);
				$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];	
			}
					
			if ($objdatos->actualizar($iddatosfijosresultado,$idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)==true) 
                           /* && $Clases->actualizar_labo($iddatosfijosresultado,$idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)==true)*/
			{
				echo "Registro Actualizado"	;			
			}
			else{
				echo "No se pudo actualizar";
			}
			
	break;
	case 3:  //ELIMINAR    
		 //Vefificando Integridad de los datos
		$iddatosfijosresultado=$_POST['iddatosfijosresultado'];
			//echo $iddatosfijosresultado;
		 if ($objdatos->eliminar($iddatosfijosresultado,$lugar)==true){ 
                         /*&& $Clases->eliminar_labo($iddatosfijosresultado,$lugar)){		*/
			echo "Registro Eliminado" ;		
				
                    }
                    else{
                            echo "El registro no pudo ser eliminado ";
                    }			

			  
	break;
	case 4:// PAGINACION
		
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		  echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
				<td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td>
				<td class='CobaltFieldCaptionTD'> IdExamen </td>
				<td class='CobaltFieldCaptionTD'> Examen </td>
				<td class='CobaltFieldCaptionTD'> Unidades </td>	   
				<td class='CobaltFieldCaptionTD'> Valores Normales </td>
				<td class='CobaltFieldCaptionTD'> Observacion </td>
                                <td class='CobaltFieldCaptionTD'> Sexo</td>
                                <td class='CobaltFieldCaptionTD'> Rango de Edad </td>
				<td class='CobaltFieldCaptionTD'> Fecha Inicio </td>	 
				<td class='CobaltFieldCaptionTD'> Fecha Finalización </td>
                                
			</tr>";
		while($row = mysql_fetch_array($consulta)){
		  echo "<tr>
				<td aling='center'> 
                                    <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                    onclick=\"pedirDatos('".$row[0]."')\"> </td>
				<td aling ='center'> 
                                    <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                    onclick=\"eliminarDato('".$row[0]."')\"> </td>
				<td> $row[1] </td>
				<td>".htmlentities($row[2])."</td>";
			if (empty($row[3]))
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
				echo"<td>".htmlentities($row[3])."</td>";
					
                        if ((empty($row[4])) && (empty($row[5])))
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else 
		        echo "<td>".$row[4]."-".$row[5]."</td>";
			
			if (empty($row['Nota']))
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
				echo "<td>".htmlentities($row['Nota'])."</td>";	
                        
                        echo "<td>".$row['9']."</td>
                            <td>".$row['10']."</td>";
			
			if((empty($row[7])) || ($row[7]=="(NULL)") || ($row[7]=="00-00-0000"))
				     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
				else
					 echo"<td>".$row[7]."</td>";
			if((empty($row[8])) || ($row[8]=="(NULL)") || ($row[8]=="00/00/0000"))
			     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
			else
					echo"<td>".$row[8]."</td></tr>";
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
	case 5:  
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
	
	case 6: 
	   	  
	  break;
	case 7: //BUSQUEDA
		$idexamen=$_POST['idexamen'];
		$idarea=$_POST['idarea'];
		$unidades=$_POST['unidades'];
		$unidades=$_POST['unidades'];
		$nota=$_POST['nota'];
                $sexo=$_POST['sexo'];
                $redad=$_POST['redad'];
		if (empty($_POST['rangoinicio'])){
			$rangoinicio="(NULL)";
		}else{
			$rangoinicio=$_POST['rangoinicio'];
		}
	
		if (empty($_POST['rangofin'])){
			$rangofin="(NULL)";
		}else{
			$rangofin=$_POST['rangofin'];
		}

	  	
		$query = "SELECT IdDatosFijosResultado,lab_examenes.IdExamen,lab_examenes.NombreExamen,
			lab_datosfijosresultado.Unidades,lab_datosfijosresultado.RangoInicio,RangoFin,
			lab_datosfijosresultado.Nota,DATE_FORMAT(lab_datosfijosresultado.FechaIni,'%d/%m/%Y')AS FechaIni,
			DATE_FORMAT(lab_datosfijosresultado.FechaFin,'%d/%m/%Y')AS FechaFin,mnt_sexo.idsexo,
                        mnt_sexo.sexovn,mnt_rangoedad.idedad,mnt_rangoedad.nombregrupoedad  
                        FROM lab_datosfijosresultado 
                        INNER join lab_examenes ON lab_datosfijosresultado.IdExamen=lab_examenes.IdExamen
                        INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
                        INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
                        INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                        INNER JOIN mnt_sexo ON lab_datosfijosresultado.`idsexo` = mnt_sexo.idsexo
                        INNER JOIN mnt_rangoedad ON lab_datosfijosresultado.idedad = mnt_rangoedad.idedad
                        WHERE lab_examenesxestablecimiento.IdPlantilla='A' AND	lab_areasxestablecimiento.Condicion='H' 
			AND lab_examenesxestablecimiento.Condicion='H' AND lab_datosfijosresultado.IdEstablecimiento=$lugar AND ";
                        $ban=0;
                        //VERIFICANDO LOS POST ENVIADOS
                        if (!empty($_POST['idarea']))
                        { $query .= " lab_datosfijosresultado.IdArea='".$_POST['idarea']."' AND"; }

                        if (!empty($_POST['idexamen']))
                        { $query .= " lab_datosfijosresultado.IdExamen='".$_POST['idexamen']."' AND"; }

                        if (!empty($_POST['unidades']))
                        { $query .= " Unidades='".$_POST['unidades']."' AND"; }

                        if (!empty($_POST['rangoinicio']))
                        { $query .= " RangoInicio='".$_POST['rangoinicio']."' AND"; }

                        if (!empty($_POST['rangofin']))
                        { $query .= " RangoFin='".$_POST['rangofin']."' AND"; }

                        if (!empty($_POST['Fechaini']))
                        { 	$FechaI=explode('/',$_POST['Fechaini']);
                                $Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
                                $query .= " FechaIni='".$Fechaini."' AND"; }

                        if (!empty($_POST['Fechafin'])){
                                $FechaF=explode('/',$_POST['Fechafin']);
                                $Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
                                $query .= " FechaFin='".$Fechafin."' AND"; } 

                        if (!empty($_POST['nota']))
                        { $query .= " Nota='".$_POST['nota']."' AND"; }
                        
                        if (!empty($_POST['sexo']))
                        { $query .= " mnt_sexo.idsexo='".$_POST['sexo']."' AND"; }
                        
                        if (!empty($_POST['redad']))
                        { $query .= " mnt_rangoedad.idedad='".$_POST['redad']."' AND"; }

                        if((empty($_POST['idexamen'])) and (empty($_POST['idarea'])) and (empty($_POST['unidades'])) and 
                                (empty($_POST['rangoinicio'])) and (empty($_POST['rangofin'])) and (empty($_POST['nota'])) and 
                                (empty($_POST['Fechafin'])) and (empty($_POST['Fechaini'])) and (empty($_POST['sexo']))
                                and (empty($_POST['redad'])))
                        {
                                $ban=1;
                        }
                        if ($ban==0)
                        {   $query = substr($query ,0,strlen($query)-3); 
                            $query_search = $query. " ORDER BY lab_examenes.IdArea,lab_examenes.IdExamen";
                        }
				
		//echo $query_search;
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		//para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		//$obje=new clsLab_DatosFijosExamen;
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
			echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
				<tr>
					   <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
					   <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td>
					   <td class='CobaltFieldCaptionTD'> IdExamen </td>
					   <td class='CobaltFieldCaptionTD'> Examen </td>
					   <td class='CobaltFieldCaptionTD'> Unidades </td>	   
					   <td class='CobaltFieldCaptionTD'> Valores Normales </td>
                                           <td class='CobaltFieldCaptionTD'> Observación </td>
                                           <td class='CobaltFieldCaptionTD'> Sexo</td>
                                           <td class='CobaltFieldCaptionTD'> Rango de Edad </td>
					   <td class='CobaltFieldCaptionTD'> Fecha Inicio </td>	 
					   <td class='CobaltFieldCaptionTD'> Fecha Finalización </td>		   
				</tr>";
				while($row = mysql_fetch_array($consulta))
				{
                          echo "<tr>
					<td aling='center'> 
						<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
						onclick=\"pedirDatos('".$row['IdDatosFijosResultado']."')\">
					</td>
					<td aling ='center'> 
						<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
						onclick=\"eliminarDato('".$row['IdDatosFijosResultado']."')\"> </td>
					<td>".$row['IdExamen']."</td>
					<td>".htmlentities($row['NombreExamen'])."</td>";
			if (empty($row['Unidades']))
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
				echo"<td>".htmlentities($row['Unidades'])."</td>";
					
				echo "<td>".$row['RangoInicio']."-".$row['RangoFin']."</td>";
					
			if (empty($row['Nota']))
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
				echo "<td>".htmlentities($row['Nota'])."</td>";
                          echo "<td>".$row['10']."</td>
                            <td>".$row['12']."</td>";
						
			if(($row['FechaIni']=="(NULL)") || ($row['FechaIni']=="00/00/0000") || (empty($row['FechaIni'])) )
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
				echo "<td>".$row['FechaIni']."</td>";
								
			if((empty($row['FechaFin'])) || ($row['FechaFin']=="(NULL)") || ($row['FechaFin']=="00/00/0000")) 
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else	
				echo "<td>".$row['FechaFin']."</td>";
							
		  echo "</tr>";
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
		$idexamen=$_POST['idexamen'];
		$idarea=$_POST['idarea'];
		$unidades=$_POST['unidades'];
		$unidades=$_POST['unidades'];
	
		if (empty($_POST['rangoinicio'])){
			$rangoinicio="(NULL)";
		}else{
			$rangoinicio=$_POST['rangoinicio'];
		}
	
		if (empty($_POST['rangofin'])){
			$rangofin="(NULL)";
		}else{
			$rangofin=$_POST['rangofin'];
		}

		$query = "SELECT IdDatosFijosResultado,lab_examenes.IdExamen,lab_examenes.NombreExamen,
			lab_datosfijosresultado.Unidades,lab_datosfijosresultado.RangoInicio,RangoFin,
			lab_datosfijosresultado.Nota,DATE_FORMAT(lab_datosfijosresultado.FechaIni,'%d/%m/%Y')AS FechaIni,
			DATE_FORMAT(lab_datosfijosresultado.FechaFin,'%d/%m/%Y')AS FechaFin,mnt_sexo.idsexo,
                        mnt_sexo.sexovn,mnt_rangoedad.idedad,mnt_rangoedad.nombregrupoedad  
                        FROM lab_datosfijosresultado 
                        INNER join lab_examenes ON lab_datosfijosresultado.IdExamen=lab_examenes.IdExamen
                        INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
                        INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
                        INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                        INNER JOIN mnt_sexo ON lab_datosfijosresultado.`idsexo` = mnt_sexo.idsexo
                        INNER JOIN mnt_rangoedad ON lab_datosfijosresultado.idedad = mnt_rangoedad.idedad
                        WHERE lab_examenesxestablecimiento.IdPlantilla='A' AND	lab_areasxestablecimiento.Condicion='H' 
			AND lab_examenesxestablecimiento.Condicion='H' AND lab_datosfijosresultado.IdEstablecimiento=$lugar AND ";
		$ban=0;
		
		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['idarea']))
		{ $query .= " lab_datosfijosresultado.IdArea='".$_POST['idarea']."' AND"; }
		//else{$ban=1;}
		
		if (!empty($_POST['idexamen']))
		{ $query .= " lab_datosfijosresultado.IdExamen='".$_POST['idexamen']."' AND"; }
	//	else{$ban=1;}
		
		if (!empty($_POST['unidades']))
		{ $query .= " Unidades='".$_POST['unidades']."' AND"; }
		//else{$ban=1;}
		
		if (!empty($_POST['rangoinicio']))
		{ $query .= " RangoInicio='".$_POST['rangoinicio']."' AND"; }
		//else{$ban=1;}
		
		if (!empty($_POST['rangofin']))
		{ $query .= " RangoFin='".$_POST['rangofin']."' AND"; }
		//else{$ban=1;}

		if (!empty($_POST['nota']))
		{ $query .= " Nota='".$_POST['nota']."' AND"; }
		//else{$ban=1;}

		if (!empty($_POST['Fechaini']))
		{ 	$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
			$query .= " FechaIni='".$Fechaini."' AND"; }

		if (!empty($_POST['Fechafin'])){
			$FechaF=explode('/',$_POST['Fechafin']);
	  		$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
			$query .= " FechaFin='".$Fechafin."' AND"; } 
                        
                if (!empty($_POST['sexo']))
                {   $query .= " mnt_sexo.idsexo='".$_POST['sexo']."' AND"; }
                        
                if (!empty($_POST['redad']))
                {   $query .= " mnt_rangoedad.idedad='".$_POST['redad']."' AND"; }
	
	if((empty($_POST['cargo'])) and (empty($_POST['idarea'])) and (empty($_POST['nomempleado'])) 
         and (empty($_POST['idempleado'])) and (empty($_POST['sexo'])) and (empty($_POST['redad'])))
	{
		$ban=1;
	}
		
	if ($ban==0)
	{   $query = substr($query ,0,strlen($query)-3); 
	    $query_search = $query. " ORDER BY lab_examenes.IdExamen";
	}
       // echo $query_search;
	
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		//para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 //LAMANDO LA FUNCION DE LA CLASE 
		//$obje=new clsLab_DatosFijosExamen;
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		  echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
		        <tr>
			        <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
			        <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td>
				<td class='CobaltFieldCaptionTD'> IdExamen </td>
				<td class='CobaltFieldCaptionTD'> Examen </td>
				<td class='CobaltFieldCaptionTD'> Unidades </td>	   
				<td class='CobaltFieldCaptionTD'> Valores Normales </td>
			        <td class='CobaltFieldCaptionTD'> Observación </td>
                                <td class='CobaltFieldCaptionTD'> Sexo</td>
                                <td class='CobaltFieldCaptionTD'> Rango de Edad </td>
				<td class='CobaltFieldCaptionTD'> Fecha Inicio </td>	 
				<td class='CobaltFieldCaptionTD'> Fecha Finalización </td>		   
			</tr>";
		while($row = mysql_fetch_array($consulta))
		{
		  echo "<tr>
				<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('".$row['IdDatosFijosResultado']."')\"> </td>
				<td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row['IdDatosFijosResultado']."')\"> </td>
				<td>". $row['IdExamen']."</td>
				<td>".htmlentities($row['NombreExamen'])."</td>";
			
		if (empty($row['Unidades']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
		   echo "<td>".htmlentities($row['Unidades'])."</td>";
		   echo "<td>".$row['RangoInicio']."-".$row['RangoFin']."</td>";
		   
		if (empty($row['Nota']))
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".htmlentities($row['Nota'])."</td>";
		echo "<td>".$row['10']."</td>
                            <td>".$row['12']."</td>";
		if(($row['FechaIni']=="(NULL)") || ($row['FechaIni']=="00/00/0000") ||(empty($row['FechaIni'])))
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['FechaIni']."</td>";
								
		if((empty($row['FechaFin'])) || ($row['FechaFin']=="(NULL)") || ($row['FechaFin']=="00/00/0000")) 
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else	
			echo "<td>".$row['FechaFin']."</td>";

	echo "</tr>";
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
			   <td><a onclick=\"show_event_search('1')\">Primero</a></td>";
		//// desplazamiento

		 if($PagAct>1) 
			 echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
		 if($PagAct<$PagUlt)  
			 echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
			 if($PagUlt > 0)
				echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
			  </table>";
		
		//echo $query_search;
	   
	break;
}

?>