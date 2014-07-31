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
		//$nota=$_POST['nota'];
               // $sexo=$_POST['sexo'];
                $unidades=(empty($_POST['unidades'])) ? 'NULL' : "'" . pg_escape_string($_POST['unidades']) . "'"; 
                $nota=(empty($_POST['nota'])) ? 'NULL' : "'" . pg_escape_string($_POST['nota']) . "'";  
                $sexo=(empty($_POST['sexo'])) ? 'NULL' : "'" . pg_escape_string($_POST['sexo']) . "'";        
                $redad=(empty($_POST['redad'])) ? 'NULL' : "'" . pg_escape_string($_POST['redad']) . "'"; 
                $rangoinicio=(empty($_POST['rangoinicio'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangoinicio']) . "'";
                $rangofin=(empty($_POST['rangofin'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangofin']) . "'";
                $Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
		$Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";
                
              // echo $sexo."**".$redad;
		/*if (empty($_POST['rangoinicio'])){
			$rangoinicio="(NULL)";
		}else{
			$rangoinicio=$_POST['rangoinicio'];
		}
	
		if (empty($_POST['rangofin'])){
			$rangofin=NULL;
		}else{
			$rangofin=$_POST['rangofin'];
		}
                 if (empty($_POST['Fechaini'])){
			$Fechaini=NULL; 
		}else{ 
			$FechaI=explode('/',$_POST['Fechaini']);
	  		$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
	  	}
                
                //echo $Fechafin;
		if (empty($_POST['Fechafin'])){
			$Fechafin=NULL;
		}else{ 
                    if (!empty($Fechafin1)){
			$FechaF=explode('/',$Fechafin1);
                    $Fechafin=$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0];}	
		}*/

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
		        //$sexo=$_POST['sexo'];
                       // $sexo=(empty($_POST['sexo'])) ? 'NULL' : "'" . pg_escape_string($_POST['sexo']) . "'";
                        //echo $sexo;
                       // $redad=$_POST['redad']; 
                        $redad=(empty($_POST['redad'])) ? 'NULL' : "'" . pg_escape_string($_POST['redad']) . "'"; 
                       // echo $idexamen." id=".$iddatosfijosresultado;
                        //  $sexo=(empty($_POST['sexo'])) ? 'NULL' : "'" . pg_escape_string($_POST['sexo']) . "'";
			/*if (($_POST['sexo']=0)){
                            $sexo=NULL;
                         
                        }
                        else
                            $sexo=$_POST['sexo'];*/
                       /*  if (empty($_POST['sexo'])){
                            $sexo=NULL;
			}else{
                            $sexo=$_POST['sexo'];
			}*/
                        $sexo=(empty($_POST['sexo'])) ? 'NULL' : "'" . pg_escape_string($_POST['sexo']) . "'";  
                        $unidades=(empty($_POST['unidades'])) ? 'NULL' : "'" . pg_escape_string($_POST['unidades']) . "'"; 
                       /* if (empty($_POST['unidades'])){
                            $unidades=NULL;
			}else{
                            $unidades=$_POST['unidades'];
			}*/
                        
                        $rangoinicio=(empty($_POST['rangoinicio'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangoinicio']) . "'";
                        $rangofin=(empty($_POST['rangofin'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangofin']) . "'";
//                     
			//	$nota=$_POST['nota'];
                        $nota=(empty($_POST['nota'])) ? 'NULL' : "'" . pg_escape_string($_POST['nota']) . "'";  

			/*if (empty($_POST['Fechaini'])){
				$Fechaini=NULL;
			}else{ 
				$FechaI=explode('/',$_POST['Fechaini']);
				$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
			}
			if (empty($_POST['Fechafin'])){
				$Fechafin=NULL;
			}else{ 
				$FechaF=explode('/',$_POST['Fechafin']);
				$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];	
			}*/
			$Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
                        $Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";		
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
		while($row = pg_fetch_array($consulta)){
		  echo "<tr>
				<td aling='center'> 
                                    <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                    onclick=\"pedirDatos('".$row['id']."')\"> </td>
				<td aling ='center'> 
                                    <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                    onclick=\"eliminarDato('".$row['id']."')\"> </td>
				<td>". $row['idexamen'] ."</td>
				<td>".htmlentities($row['nombreexamen'])."</td>";
			if (empty($row['unidades']))
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
				echo"<td>".htmlentities($row['unidades'])."</td>";
					
                        if ((empty($row['rangoInicio'])) && (empty($row['rangofin'])))
                                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else 
                               echo "<td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
			
			if (empty($row['nota']))	
                            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                            echo "<td>".htmlentities($row['nota'])."</td>";	
                        
                        if (empty($row['sexo']))
                            echo "<td> Ambos </td>";
                        else
                            echo "<td>".$row['sexo']."</td>";
                        
                            echo "<td>".$row['redad']."</td>";
			
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
                
           	//echo "combo".$idarea; 
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
	
	case 6: 
	   	  
	  break;
	case 7: //BUSQUEDA
		$idexamen=$_POST['idexamen'];
		$idarea=$_POST['idarea'];
		$unidades=$_POST['unidades'];
		$unidades=$_POST['unidades'];
		$nota=$_POST['nota'];
                $sexo=$_POST['sexo'];
              //  echo $sexo;
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

	  	
		$query = "SELECT lab_datosfijosresultado.id,lab_examenes.idexamen,lab_examenes.nombreexamen, lab_datosfijosresultado.unidades,
                          lab_datosfijosresultado.rangoInicio,rangofin, lab_datosfijosresultado.nota,
                          to_char(lab_datosfijosresultado.fechaini,'YYYY/mm/dd') AS FechaIni,
                          to_char(lab_datosfijosresultado.fechafin,'YYYY-mm-dd') AS FechaFin, 
                          ctl_sexo.nombre as sexo,ctl_rango_edad.nombre as redad
                          FROM lab_datosfijosresultado 
                          INNER join lab_examenes ON lab_datosfijosresultado.idexamen=lab_examenes.id 
                          INNER JOIN lab_areas ON lab_examenes.idarea=lab_areas.id 
                          INNER JOIN lab_areasxestablecimiento ON lab_areas.id=lab_areasxestablecimiento.idarea 
                          INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen 
                          LEFT JOIN ctl_sexo ON lab_datosfijosresultado.idsexo = ctl_sexo.id 
                          INNER JOIN ctl_rango_edad ON lab_datosfijosresultado.idedad = ctl_rango_edad.id 
                          WHERE lab_examenesxestablecimiento.idplantilla=1 AND lab_examenesxestablecimiento.condicion='H' 
                          AND lab_areasxestablecimiento.condicion='H' AND lab_datosfijosresultado.idestablecimiento=$lugar AND ";
                        $ban=0;
                        //VERIFICANDO LOS POST ENVIADOS
                        if (!empty($_POST['idarea']))
                        { $query .= " lab_datosfijosresultado.idarea=".$_POST['idarea']." AND"; }

                        if (!empty($_POST['idexamen']))
                        { $query .= " lab_datosfijosresultado.idexamen=".$_POST['idexamen']." AND"; }

                        if (!empty($_POST['unidades']))
                        { $query .= " unidades='".$_POST['unidades']."' AND"; }

                        if (!empty($_POST['rangoinicio']))
                        { $query .= " rangoinicio='".$_POST['rangoinicio']."' AND"; }

                        if (!empty($_POST['rangofin']))
                        { $query .= " rangofin='".$_POST['rangofin']."' AND"; }
                        
                         if (!empty($_POST['nota']))
                        { $query .= " nota='".$_POST['nota']."' AND"; }
                        
                        if (!empty($_POST['sexo'])){
                            if ($_POST['sexo']<>3)
                              $query .= " ctl_sexo.id=".$_POST['sexo']." AND";
                        }
                        else
                        { $query .= " ctl_sexo.id is null AND"; }
                       
                        if (!empty($_POST['redad']))
                        { $query .= " ctl_rangoedad.id=".$_POST['redad']." AND"; }

                        if (!empty($_POST['Fechaini']))
                        { 	$FechaI=explode('/',$_POST['Fechaini']);
                                $Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
                                $query .= " fechaini='".$Fechaini."' AND"; }

                        if (!empty($_POST['Fechafin'])){
                                $FechaF=explode('/',$_POST['Fechafin']);
                                $Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
                                $query .= " fechafin='".$Fechafin."' AND"; } 

                       

                        if((empty($_POST['idexamen'])) and (empty($_POST['idarea'])) and (empty($_POST['unidades'])) and 
                                (empty($_POST['rangoinicio'])) and (empty($_POST['rangofin'])) and (empty($_POST['nota'])) and 
                                (empty($_POST['Fechafin'])) and (empty($_POST['Fechaini'])) and (empty($_POST['sexo']))
                                and (empty($_POST['redad'])))
                        {
                                $ban=1;
                        }
                        if ($ban==0)
                        {   $query = substr($query ,0,strlen($query)-3); 
                            $query_search = $query. " ORDER BY lab_examenes.idarea,lab_examenes.id";
                        }
				
		echo $query_search;
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
				while($row = pg_fetch_array($consulta))
				{
                          echo "<tr>
                                            <td aling='center'> 
						<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
						onclick=\"pedirDatos('".$row['id']."')\">
                                            </td>
                                            <td aling ='center'> 
						<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
						onclick=\"eliminarDato('".$row['id']."')\"> </td>
                                            <td>".$row['idexamen']."</td>
                                            <td>".htmlentities($row['nombreexamen'])."</td>";
                            if (empty($row['uidades']))
                                      echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                            else
                                      echo"<td>".htmlentities($row['unidades'])."</td>";
					
				     echo "<td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
					
                            if (empty($row['nota']))
                                    echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                            else
                                    echo "<td>".htmlentities($row['nota'])."</td>";
                            if (!empty($row['sexo']))
                                    echo "<td>".htmlentities($row['sexo'])."</td>";
                            else 
                                    echo "<td>Ambos</td>";
                                    echo "<td>".htmlentities($row['redad'])."</td>";
                           
						
			if(($row['fechaini']=="(NULL)") || ($row['fechaini']=="00/00/0000") || (empty($row['fechaini'])) )
			    echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
			    echo "<td>".$row['fechaini']."</td>";
								
			if((empty($row['fechafin'])) || ($row['fechafin']=="(NULL)") || ($row['fechafin']=="00/00/0000")) 
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else	
				echo "<td>".$row['fechafin']."</td>";
							
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
                // echo $sexo;
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

		$query = "SELECT lab_datosfijosresultado.id,lab_examenes.idexamen,lab_examenes.nombreexamen, lab_datosfijosresultado.unidades,
                          lab_datosfijosresultado.rangoInicio,rangofin, lab_datosfijosresultado.nota,
                          to_char(lab_datosfijosresultado.fechaini,'YYYY/mm/dd') AS FechaIni,
                          to_char(lab_datosfijosresultado.fechafin,'YYYY-mm-dd') AS FechaFin, 
                          ctl_sexo.nombre as sexo,ctl_rango_edad.nombre as redad
                          FROM lab_datosfijosresultado 
                          INNER join lab_examenes ON lab_datosfijosresultado.idexamen=lab_examenes.id 
                          INNER JOIN lab_areas ON lab_examenes.idarea=lab_areas.id 
                          INNER JOIN lab_areasxestablecimiento ON lab_areas.id=lab_areasxestablecimiento.idarea 
                          INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen 
                          LEFT JOIN ctl_sexo ON lab_datosfijosresultado.idsexo = ctl_sexo.id 
                          INNER JOIN ctl_rango_edad ON lab_datosfijosresultado.idedad = ctl_rango_edad.id 
                          WHERE lab_examenesxestablecimiento.idplantilla=1 AND lab_examenesxestablecimiento.condicion='H' 
                          AND lab_areasxestablecimiento.condicion='H' AND lab_datosfijosresultado.idestablecimiento=$lugar AND ";
		$ban=0;
		
		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['idarea']))
		{ $query .= " lab_datosfijosresultado.idarea='".$_POST['idarea']."' AND"; }
		//else{$ban=1;}
		
		if (!empty($_POST['idexamen']))
		{ $query .= " lab_datosfijosresultado.idexamen='".$_POST['idexamen']."' AND"; }
	//	else{$ban=1;}
		
		if (!empty($_POST['unidades']))
		{ $query .= " unidades='".$_POST['unidades']."' AND"; }
		//else{$ban=1;}
		
		if (!empty($_POST['rangoinicio']))
		{ $query .= " rangoinicio='".$_POST['rangoinicio']."' AND"; }
		//else{$ban=1;}
		
		if (!empty($_POST['rangofin']))
		{ $query .= " rangofin='".$_POST['rangofin']."' AND"; }
		//else{$ban=1;}

		if (!empty($_POST['nota']))
		{ $query .= " nota='".$_POST['nota']."' AND"; }
		//else{$ban=1;}

		
                        
               if (!empty($_POST['nota']))
                        { $query .= " nota='".$_POST['nota']."' AND"; }
                        
               if (!empty($_POST['sexo'])){
                    if ($_POST['sexo']<>3)
                        $query .= " ctl_sexo.id=".$_POST['sexo']." AND";
               }
               else
                 { $query .= " ctl_sexo.id is null AND"; }
                        
                if (!empty($_POST['redad']))
                {   $query .= " ctl_rangoedad.id='".$_POST['redad']."' AND"; }
                
                if (!empty($_POST['Fechaini']))
		{ 	$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
			$query .= " fechaini='".$Fechaini."' AND"; }

		if (!empty($_POST['Fechafin'])){
			$FechaF=explode('/',$_POST['Fechafin']);
	  		$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
			$query .= " fechafin='".$Fechafin."' AND"; } 
	
	if((empty($_POST['cargo'])) and (empty($_POST['idarea'])) and (empty($_POST['nomempleado'])) 
         and (empty($_POST['idempleado'])) and (empty($_POST['sexo'])) and (empty($_POST['redad'])))
	{
		$ban=1;
	}
		
	if ($ban==0)
	{   $query = substr($query ,0,strlen($query)-3); 
	    $query_search = $query. " ORDER BY lab_examenes.id";
	}
       //echo $query_search;
	
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
		while($row = pg_fetch_array($consulta))
		{
		  echo "<tr>
				<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('".$row['id']."')\"> </td>
				<td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row['id']."')\"> </td>
				<td>". $row['idexamen']."</td>
				<td>".htmlentities($row['nombreexamen'])."</td>";
			
		if (empty($row['unidades']))
					echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
		   echo "<td>".htmlentities($row['unidades'])."</td>";
		   echo "<td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
		   
		if (empty($row['nota']))
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".htmlentities($row['nota'])."</td>";
                if (!empty($row['sexo']))
                    echo "<td>".htmlentities($row['sexo'])."</td>";
                else 
                    echo "<td>Ambos</td>";
                echo "<td>".htmlentities($row['redad'])."</td>";
                
		if(($row['fechaini']=="(NULL)") || ($row['fechaini']=="00/00/0000") ||(empty($row['fechaini'])))
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechaini']."</td>";
								
		if((empty($row['fechafin'])) || ($row['fechafin']=="(NULL)") || ($row['fechafin']=="00/00/0000")) 
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else	
			echo "<td>".$row['fechafin']."</td>";

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