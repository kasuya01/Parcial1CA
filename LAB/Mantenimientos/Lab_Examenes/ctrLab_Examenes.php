<?php session_start();
include_once("clsLab_Examenes.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

$objdatos = new clsLab_Examenes;
$Clases = new clsLabor_Examenes;

//$Pag =$_POST['Pag'];
$opcion=$_POST['opcion'];

//actualiza los datos del empleados

//echo $cond; 
//echo $ubicacion;
switch ($opcion) 
{
	case 1:  //INSERTAR	
		$idexamen=$_POST['idexamen'];
		$idarea=$_POST['idarea'];
		$nomexamen=$_POST['nomexamen'];
               //  $nota=(empty($_POST['nota'])) ? 'NULL' : "'" . pg_escape_string($_POST['nota']) . "'";  
		$idestandar=$_POST['idestandar'];
                //echo $idestandar;
		$plantilla=$_POST['plantilla'];
		//$observacion=(empty($_POST['observacion']))? 'NULL' : "'" . pg_escape_string($_POST['observacion']). "'";
		$ubicacion=$_POST['ubicacion'];
		$IdFormulario=$_POST['idformulario'];
		$IdEstandarResp=$_POST['idestandarRep'];
		//echo $IdEstandarResp." idPlantilla=".$plantilla;
                $etiqueta=$_POST['etiqueta'];
                $Urgente=$_POST['urgente'];
                $sexo=$_POST['sexo'];
                $metodologias_sel=$_POST['metodologias_sel'];
                //echo $sexo;
                if($sexo<>4)
                    $idsexo=$sexo;
                else    
                    $idsexo='NULL';
               // echo $idsexo;
                $Hab=$_POST['Hab'];
                
                $TiempoPrevio=$_POST['tiempoprevio'];
               //Echo $sexo."-".$idestandar."-". $Hab;  
			
               // $cond='H';
                if ($etiqueta=="O"){
                    $dato=$objdatos->obtener_letra($idarea);
                    $rowletra=pg_fetch_array($dato);
                    $rletra= $rowletra[0];
                    if (!empty($rletra)){
                    	$num=$rletra +1;
			if ($num==71){
                            $num=$num+1;
                            $letra=chr($num);
                                    //echo $letra;
			
                        }
                        else{
                            $letra=chr($num);
                                    
                        }
                    }
                    else{
                        $num=65; //"Letra A"; 
                        $letra=chr($num);
                    }

                 }
                 else {
                    $letra=$etiqueta;
                 }

                      //  echo $IdFormulario;
		 If ($objdatos->IngExamenxEstablecimiento($idexamen,$nomexamen,$Hab,$usuario,$IdFormulario,$IdEstandarResp,$plantilla,$letra,$Urgente,$ubicacion,$TiempoPrevio,$idsexo,$idestandar,$lugar,$metodologias_sel)==true)
		 {
                     /*
                      * Ingresar metodologías seleccionadas
                      */
                     
                     
                     
                     
                     
                   /* if($plantilla<>1){
                           // echo  $idexamen."  ".$idarea."  ".$usuario."  ".$lugar;
                          if($objdatos->AgregarDatosFijos($idexamen,$idarea,$usuario,$lugar)==true)
                               echo "Registro Agregado";
                          else 
                               echo "No se pudo agregar el registro";
                    }else*/
                             echo "Registro Agregado";
                 }
                 else{
                        echo "No se pudo Ingresar el Registro";			
		 }
	break;	
    case 2:  //MODIFICAR 
			$idexamen=$_POST['idexamen'];
			$idarea=$_POST['idarea'];
			$nomexamen=$_POST['nomexamen'];
			$idestandar=$_POST['idestandar'];
			$plantilla=$_POST['plantilla'];
			//$observacion=$_POST['observacion'];
			$ubicacion=$_POST['ubicacion'];
			$IdFormulario=$_POST['idformulario'];
			$IdEstandarResp=$_POST['idestandarRep'];
			$etiqueta=$_POST['Etiqueta'];
                        $Urgente=$_POST['urgente'];
                        $sexo=$_POST['idsexo']; 
                        $metodologias_sel=$_POST['metodologias_sel'];
                     //  echo $IdEstandarResp." sexo=".$sexo;
                       if($sexo<>4)
                            $idsexo=$sexo;
                       else    
                            $idsexo='NULL';
                         //echo $idsexo;
                            $Hab=$_POST['Hab'];
                            $TiempoPrevio=$_POST['Tiempo'];
                            $idconf=$_POST['idconf'];
                            $ctlidestandar=$_POST['ctlidestandar'];
                            $letra="";
				
				
			if ($etiqueta=="O"){
				$dato=$objdatos->obtener_letra($idarea);
				$rowletra=pg_fetch_array($dato);
				$rletra= $rowletra[0];

				if (!empty($rletra)){
                                    $num=$rletra+1;
                                    if ($num==71){
					$num=$num+1;
				    }
                                    $letra=chr($num);
				}
				else{
                                    $num=65;
                                    $letra=chr($num);
				}
			 }
                        
                         if ($etiqueta=="G"){
                            $num=71;
                            $letra=$etiqueta;
			 }

			// echo $idexamen."-".$lugar."-".$usuario."-".$IdFormulario."-".$IdEstandarResp."-".$plantilla."-".$letra."-".$Urgente."-".$ubicacion;
              	If($objdatos->ActExamenxEstablecimiento($idconf,$nomexamen,$lugar,$usuario,$IdFormulario,$IdEstandarResp,$plantilla,$letra,$Urgente,$ubicacion,$Hab,$TiempoPrevio,$idsexo,$idestandar,$ctlidestandar,$metodologias_sel)==true){
                    /*
                     * creando arreglo de elementos seleccionados
                     */
                    

                    echo "Registro Actualizado";
                   		
		}
		else{
                         echo "No se pudo actualizar en Registro";
		}
		
   	break;
	case 3:
                $cond=$_POST['condicion'];
		$idexamen=$_POST['idexamen'];
             //	echo $idexamen."-".$condicion;
		//$resultado=Estado::EstadoCuenta($idexamen,$cond,$lugar);
		$resultado=$objdatos->EstadoCuenta($idexamen,$cond,$lugar);
	break;
	case 4:// PAGINACION
		//para manejo de la paginacion
		
		$RegistrosAMostrar=15;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		 $consulta= $objdatos->consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
	    echo "<table border = 1 align='center'  class='StormyWeatherFormTABLE' width='85%'>
                        <tr>
                            <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
                            <td aling='center' class='CobaltFieldCaptionTD'> Habilitado</td>
                            <td class='CobaltFieldCaptionTD'> C&oacute;digo Examen </td>
                            <td class='CobaltFieldCaptionTD'> Nombre Examen </td>
                            <td class='CobaltFieldCaptionTD'> &Aacute;rea</td>
                            <td class='CobaltFieldCaptionTD'>Plantilla</td>
                            <td class='CobaltFieldCaptionTD'>C&oacute;digo del Est&aacute;ndar</td>
                            <td class='CobaltFieldCaptionTD'>Solicitado en</td>
     			    <td class='CobaltFieldCaptionTD'>Formulario</td>
			    <td class='CobaltFieldCaptionTD'>Tabulador</td>
                            <td class='CobaltFieldCaptionTD'>Tipo Viñeta</td>
                            <td class='CobaltFieldCaptionTD'>Urgente</td>
                            <td class='CobaltFieldCaptionTD'>Sexo</td>
                            <td class='CobaltFieldCaptionTD'>Tiempo Previo</td>
		      </tr>";
		while($row = pg_fetch_array($consulta)){
		 echo "<tr>
                            <td aling='center'> 
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"pedirDatos('".$row['id']."')\"> </td>
                            <td style='text-decoration:underline;cursor:pointer;' ".
                   	"onclick='Estado(\"".$row['id']."\",\"".$row['condicion']."\")'>".$row['cond']."</td>
                            <td>".$row['idexamen']." </td>
                            <td>".htmlentities($row['nombreexamen'])." </td>
                            <td>".htmlentities($row['nombrearea'])." </td>
                            <td>".htmlentities($row['idplantilla'])." </td>
                            <td>".htmlentities($row['idestandar'])." </td>
                            <td>".htmlentities($row['ubicacion'])."</td>";
		  
              if(!empty($row['nombreFormulario']))
		   echo"<td>".htmlentities($row['nombreformulario'])." </td> ";
              else       
                   echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                   echo "<td>".htmlentities($row['estandarrep'])." </td>";
              if ($row['impresion']=='G')                      
		   echo"<td>General</td>";
              else
                   echo"<td>Especial </td>";
              if($row['urgente']==0)
                    echo"<td>NO</td>";
              else
                    echo"<td>SI</td>";
               if(!empty($row['nombresexo']))      
                     echo"<td>".htmlentities($row['nombresexo'])." </td>";
               else
                    echo "<td>Ambos</td>";
               if(!empty($row['rangotiempoprev']))
                    echo"<td>".$row['rangotiempoprev']." </td><tr>";
               else       
                    echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><tr>";
                    
		}
		echo "</table>"; 

		//determinando el numero de paginas
		 $NroRegistros= $objdatos->NumeroDeRegistros($lugar);
		// echo $PagAct;
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;
		 
		 $PagUlt=$NroRegistros/$RegistrosAMostrar;
		// echo "NumRegistros=".$NroRegistros." Ultima=".$PagUlt." a mostrar ".$RegistrosAMostrar;
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
	case 5:// Se genera el Código del Examenen
            $idarea=$_POST['idarea'];  
           // echo $idarea;
            $idarea1=$objdatos->ObtenerCodigo($idarea);
            $area= $idarea1[0];
            $cod=$objdatos->LeerUltimoCodigo($idarea);
            $dato=substr("$cod", -3);
            $val=(int)$dato;
            //echo $val;
            $consulta= $val+1;
		if ($consulta >= 0 && $consulta  <= 9){
		$codigo=$area.'00'.$consulta;
		}
		if ($consulta >= 10 && $consulta  <= 99){
			$codigo=$area.'0'.$consulta;
			//document.frmnuevo.txtidexamen.value=idArea+'0'+numero;
		}
		if ($consulta >= 100 && $consulta  <= 999){
			$codigo=$area.$consulta;
			//document.frmnuevo.txtidexamen.value=idArea+numero;
		}
    		 echo "<input type='text' id='txtidexamen'  name='txtidexamen' value='".$codigo."'  />";
                   
    break;
	case 6:
		$idarea=$_POST['idarea'];
                
           	//echo "combo".$idarea; 
	  	$rslts='';
		$consultaex= $objdatos->ExamenesPorArea($idarea,$lugar);
		//$dtMed=$obj->LlenarSubServ($proce);	
		
		$rslts = '<select name="cmbEstandar" id="cmbEstandar" size="1" >';
		$rslts .='<option value="0">--Seleccione un Examen--</option>';
			
		while ($rows =pg_fetch_array($consultaex)){
			$rslts.= '<option value="' .$rows[0].'" >'.$rows['idestandar'].'-'.htmlentities($rows[1]).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;
	
	
	break;
	case 7: //BUSQUEDA
				$idexamen=$_POST['idexamen'];
				$idarea=$_POST['idarea'];
				$nomexamen=$_POST['nomexamen'];
				$idestandar=$_POST['idestandar'];
				$plantilla=$_POST['plantilla'];
				//$observacion=$_POST['observacion'];
				$Urgente=$_POST['urgente'];
				$ubicacion=$_POST['ubicacion'];
				$cond=$_POST['condicion'];	
				$IdFormulario=$_POST['idformulario'];
				$IdEstandarResp=$_POST['idestandarRep'];
                                $etiqueta=$_POST['etiqueta'];
                                $sexo=$_POST['sexo'];
				if($sexo<>4)
                                    $idsexo=$sexo;
                               else    
                                    $idsexo='NULL';
                                  //  echo $idsexo;
                                $Hab=$_POST['Hab'];
                            //   echo $idestandar;
                                //echo $etiqueta;
				$conExam=$objdatos->BuscarExamen($idexamen,$lugar);
                                
				//print_r ($conExam);
				$ExisExa=pg_fetch_array($conExam);
				//print_r ($ExisExa[0]);
				//echo $ExisExa[0];
                                  $query = "SELECT lab_conf_examen_estab.id,lab_conf_examen_estab.codigo_examen as idexamen, 
                                            lab_conf_examen_estab.nombre_examen as nombreexamen, ctl_area_servicio_diagnostico.nombrearea,lab_plantilla.idplantilla, 
                                            ctl_examen_servicio_diagnostico.idestandar, 
                                            (CASE WHEN lab_conf_examen_estab.ubicacion=0 THEN 'Todas las Procedencias' 
                                            WHEN lab_conf_examen_estab.ubicacion=1 THEN 'Hospitalización y Emergencia' 
                                            WHEN lab_conf_examen_estab.ubicacion=4 THEN 'Laboratorio' END ) AS Ubicacion, 
                                            (SELECT idestandar FROM ctl_examen_servicio_diagnostico 
                                            WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS estandarrep, 
                                            lab_conf_examen_estab.impresion,urgente, ctl_sexo.nombre AS nombresexo,lab_conf_examen_estab.condicion, 
                                            (CASE WHEN lab_conf_examen_estab.condicion='H' THEN 'Habilitado' 
                                            WHEN lab_conf_examen_estab.condicion='I' THEN 'Inhabilitado' END) AS cond,cit_programacion_exams.rangotiempoprev,
                                            mnt_formularios.nombreformulario
                                            FROM lab_conf_examen_estab 
                                            INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id 
                                            INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id 
                                            INNER JOIN ctl_examen_servicio_diagnostico ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico=ctl_examen_servicio_diagnostico.id 
                                            LEFT JOIN mnt_formularios ON lab_conf_examen_estab.idformulario=mnt_formularios.id 
                                            INNER JOIN lab_plantilla ON lab_conf_examen_estab.idplantilla=lab_plantilla.id 
                                            LEFT JOIN ctl_sexo ON lab_conf_examen_estab.idsexo= ctl_sexo.id 
                                            INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea 
                                            LEFT JOIN cit_programacion_exams ON lab_conf_examen_estab.id=cit_programacion_exams.id_examen_establecimiento 
                                            WHERE lab_areasxestablecimiento.condicion='H' AND lab_areasxestablecimiento.idestablecimiento=$lugar AND ";
					$ban=0;
					
						//VERIFICANDO LOS POST ENVIADOS
                                               // echo $ExisExa[0]; 
                                        if($ExisExa[0]>0){//si existe el examen 

                                             if (!empty($_POST['idexamen']))
                                                 { $query .= " lab_conf_examen_estab.codigo_examen='".$_POST['idexamen']."' AND"; }
                                        }	
                                        if (!empty($_POST['nomexamen']))
					{ $query .= " nombre_examen ilike'%".$_POST['nomexamen']."%' AND"; }
						
					if (!empty($_POST['idarea']))
					{ $query .= " ctl_area_servicio_diagnostico.id=".$_POST['idarea']."   AND"; }
						
					if (!empty($_POST['plantilla']))
					{ $query .= " lab_conf_examen_estab.idplantilla=".$_POST['plantilla']." AND"; }
						
					if (!empty($_POST['idestandar']))
					{ $query .= " mnt_area_examen_establecimiento.id=".$_POST['idestandar']." AND"; }
					
					if (!empty($_POST['idestandarRep']))
					{ $query .= " lab_conf_examen_estab.idestandarrep=".$_POST['idestandarRep']." AND"; }
						
					if (!empty($_POST['idformulario']))
					{ $query .= " lab_conf_examen_estab.idformulario='".$_POST['idformulario']."' AND"; }
                                        
                                        if (!empty($_POST['ubicacion']))
					{ $query .= " lab_conf_examen_estab.ubicacion='".$_POST['ubicacion']."' AND"; }
						
					if (!empty($_POST['etiqueta'])){
                                            if ($_POST['etiqueta']=='G')
                                                { $query .= "  lab_conf_examen_estab.impresion='G' AND"; }
                                            else    
                                                { $query .= "  lab_conf_examen_estab.impresion<>'G' AND"; } 
                                        }    
                                        if (!empty($_POST['urgente']))
                                            { $query .= " lab_conf_examen_estab.urgente='".$_POST['urgente']."' AND"; }
                                            
					if ($_POST['sexo']<>0)
					{ if($_POST['sexo']<>4)
                                            $query .= "  lab_conf_examen_estab.idsexo =".$_POST['sexo']." AND";
                                          else 
                                            $query .= "  lab_conf_examen_estab.idsexo IS NULL AND";
                                         } 
                                            
                                        
                                        if (!empty($_POST['Hab'])){
                                            if ($_POST['Hab']=='H')
                                                { $query .= "  lab_conf_examen_estab.condicion='H' AND"; }
                                            else    
                                                { $query .= "  lab_conf_examen_estab.condicion='I' AND"; } 
                                        }      
                                        
                                        else{$ban=1;}
						
						
					if ($ban==0)
					{    $query = substr($query ,0,strlen($query)-4);
					     $query_search = $query. " ORDER BY ctl_area_servicio_diagnostico.idarea,lab_conf_examen_estab.nombre_examen";
					}
					else {
                                            $query = substr($query ,0,strlen($query)-4);
                                            $query_search = $query. " ORDER BY ctl_area_servicio_diagnostico.idarea,lab_conf_examen_estab.nombre_examen";
					}
			
		 //echo$query_search;
                //para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
		
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		 /////LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);
		//echo $query_search;
		//muestra los datos consultados en la tabla
		echo "<table border = 1 align='center' width='100%' class='StormyWeatherFormTABLE'>
		      <tr>
		      	    <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
			    <td aling='center' class='CobaltFieldCaptionTD'> Habilitado</td>
			    <td class='CobaltFieldCaptionTD'> C&oacute;digo Examen </td>
			    <td class='CobaltFieldCaptionTD'> Nombre Examen </td>
			    <td class='CobaltFieldCaptionTD'> &Aacute;rea</td>
                            <td class='CobaltFieldCaptionTD'>Plantilla</td>
                            <td class='CobaltFieldCaptionTD'>C&oacute;digo del Est&aacute;ndar</td>
			    <td class='CobaltFieldCaptionTD'>Solicitado en</td>	
                            <td class='CobaltFieldCaptionTD'>Formulario</td>
                            <td class='CobaltFieldCaptionTD'>Tabulador</td>
                            <td class='CobaltFieldCaptionTD'>Tipo Viñeta</td>
                            <td class='CobaltFieldCaptionTD'>Urgente</td>
                            <td class='CobaltFieldCaptionTD'>Sexo</td>
                            <td class='CobaltFieldCaptionTD'>Tiempo Previo</td>
		      </tr>";
			while($row = pg_fetch_array($consulta)){
		echo "<tr>
                            <td aling='center'> 
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"pedirDatos('".$row[0]."')\"></td>
                            <td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' ".
				"onclick='Estado(\"".$row['id']."\",\"".$row['condicion']."\")'>".$row['cond']."</td>
                            <td>".$row['idexamen']." </td>
                            <td>".htmlentities($row['nombreexamen'])."</td>
                            <td>".htmlentities($row['nombrearea'])."</td>
                            <td>".htmlentities($row['idplantilla'])."</td>
                            <td>".htmlentities($row['idestandar'])."</td>
                            <td>".htmlentities($row['ubicacion'])."</td>";
		    
                        //if ($row['Ubicacion']=='0')
                              //   echo"<td>SI</td>";
                             // else
                               //  echo"<td>NO</td>";*/
                   if(!empty($row['nombreformulario']))
                       echo"<td>".htmlentities($row['nombreformulario'])." </td> ";
                   else
                      echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                      echo "<td>".htmlentities($row['estandarrep'])." </td>";
                    
                     if ($row['impresion']=='G')                      
		       echo"<td>General</td>";
                     else
                       echo"<td>Especial </td>";
                     if($row['urgente']==0)
                       echo"<td>NO</td>";
                     else
                       echo"<td>SI</td>";
                     
                  if(!empty($row['nombresexo']))      
                     echo"<td>".htmlentities($row['nombresexo'])." </td>";
                  else
                     echo "<td>Ambos</td>";
                  if(!empty($row['rangotiempoprev']))
                     echo"<td>".$row['rangotiempoprev']." </td><tr>";
                  else       
                        echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><tr>
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
	
	case 8://PAGINACION DE BUSQUEDA
		$idexamen=$_POST['idexamen'];
		$idarea=$_POST['idarea'];
		$nomexamen=$_POST['nomexamen'];
		$idestandar=$_POST['idestandar'];
		$plantilla=$_POST['plantilla'];
		//$observacion=$_POST['observacion'];
		$Urgente=$_POST['urgente'];
		$ubicacion=$_POST['ubicacion'];
		$cond=$_POST['condicion'];
		$IdFormulario=$_POST['idformulario'];
		$IdEstandarResp=$_POST['idestandarRep'];
                $etiqueta=$_POST['etiqueta'];
                $sexo=$_POST['sexo'];
                if($sexo<>4)
                    $idsexo=$sexo;
                else    
                    $idsexo='NULL';
               // echo $idsexo;
			//echo $IdFormulario."--".$IdEstandarResp;
		$conExam=$objdatos->BuscarExamen($idexamen,$lugar);
                                
				//print_r ($conExam);
		$ExisExa=pg_fetch_array($conExam);
				//print_r ($ExisExa[0]);
				
		
	        $query = "SELECT lab_conf_examen_estab.id,lab_conf_examen_estab.codigo_examen as idexamen, 
                          lab_conf_examen_estab.nombre_examen as nombreexamen, ctl_area_servicio_diagnostico.nombrearea,lab_plantilla.idplantilla, 
                          ctl_examen_servicio_diagnostico.idestandar, 
                          (CASE WHEN lab_conf_examen_estab.ubicacion=0 THEN 'Todas las Procedencias' 
                          WHEN lab_conf_examen_estab.ubicacion=1 THEN 'Hospitalización y Emergencia' 
                          WHEN lab_conf_examen_estab.ubicacion=4 THEN 'Laboratorio' END ) AS Ubicacion, 
                          (SELECT idestandar FROM ctl_examen_servicio_diagnostico 
                          WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS estandarrep, 
                          lab_conf_examen_estab.impresion,urgente, ctl_sexo.nombre AS nombresexo,lab_conf_examen_estab.condicion, 
                          (CASE WHEN lab_conf_examen_estab.condicion='H' THEN 'Habilitado' 
                          WHEN lab_conf_examen_estab.condicion='I' THEN 'Inhabilitado' END) AS cond,cit_programacion_exams.rangotiempoprev,
                          mnt_formularios.nombreformulario
                          FROM lab_conf_examen_estab 
                          INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id 
                          INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id 
                          INNER JOIN ctl_examen_servicio_diagnostico ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico=ctl_examen_servicio_diagnostico.id 
                          LEFT JOIN mnt_formularios ON lab_conf_examen_estab.idformulario=mnt_formularios.id INNER JOIN lab_plantilla ON lab_conf_examen_estab.idplantilla=lab_plantilla.id 
                          LEFT JOIN ctl_sexo ON lab_conf_examen_estab.idsexo= ctl_sexo.id INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea 
                          LEFT JOIN cit_programacion_exams ON lab_conf_examen_estab.id=cit_programacion_exams.id_examen_establecimiento 
                          WHERE lab_areasxestablecimiento.condicion='H' AND lab_areasxestablecimiento.idestablecimiento=$lugar AND ";
					$ban=0;
					
						//VERIFICANDO LOS POST ENVIADOS
                                               // echo $ExisExa[0]; 
                              if($ExisExa[0]>0){//si existe el examen 

                                             if (!empty($_POST['idexamen']))
                                                 { $query .= " lab_conf_examen_estab.codigo_examen='".$_POST['idexamen']."' AND"; }
                                        }		
                                 if (!empty($_POST['nomexamen']))
                                    { $query .= " nombre_examen ilike'%".$_POST['nomexamen']."%' AND"; }
						
				if (!empty($_POST['idarea']))
                                    { $query .= " ctl_area_servicio_diagnostico.id=".$_POST['idarea']."   AND"; }
						
				if (!empty($_POST['plantilla']))
                                    { $query .= " lab_conf_examen_estab.idplantilla=".$_POST['plantilla']." AND"; }
												
				if (!empty($_POST['idestandarRep']))
                                    { $query .= " lab_conf_examen_estab.idestandarrep=".$_POST['idestandarRep']." AND"; }
						
				if (!empty($_POST['idformulario']))
                                    { $query .= " lab_conf_examen_estab.idformulario='".$_POST['idformulario']."' AND"; }
                                        
                                if (!empty($_POST['Ubicacion']))
                                    { $query .= " lab_conf_examen_estab.ubicacion='".$_POST['Ubicacion']."' AND"; }
						
				if (!empty($_POST['etiqueta'])){
                                      if ($_POST['etiqueta']=='G')
                                           { $query .= "  lab_conf_examen_estab.impresion='G' AND"; }
                                      else    
                                           { $query .= "  lab_conf_examen_estab.impresion<>'G' AND"; } 
                                       }  
                                       
                                 if (!empty($_POST['urgente']))
                                            { $query .= " lab_conf_examen_estab.urgente='".$_POST['urgente']."' AND"; }
                                            
				if ($_POST['sexo']<>3)
                                    { if($_POST['sexo']<>0)
                                            $query .= "  lab_conf_examen_estab.idsexo =".$_POST['sexo']." AND";
                                      else 
                                            $query .= "  lab_conf_examen_estab.idsexo IS NULL AND";
                                    } 
                                            
                                if (!empty($_POST['Hab'])){
                                    if ($_POST['Hab']=='H')
                                        { $query .= "  lab_conf_examen_estab.condicion='H' AND"; }
                                    else    
                                        { $query .= "  lab_conf_examen_estab.condicion='I' AND"; } 
                                 }      
                                        
                                 else{$ban=1;}
						
						
				if ($ban==0)
				{   $query = substr($query ,0,strlen($query)-4);
				    $query_search = $query. " ORDER BY ctl_area_servicio_diagnostico.idarea,lab_conf_examen_estab.nombre_examen";
				}
				else {
                                    $query = substr($query ,0,strlen($query)-4);
                                    $query_search = $query. " ORDER BY ctl_area_servicio_diagnostico.idarea,lab_conf_examen_estab.nombre_examen";
                                }
			
		//echo $query_search;
		//require_once("clsLab_Examenes.php");
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		echo "<table border = 1 align='center' width='85%' class='StormyWeatherFormTABLE'>
		      <tr>
		      	    <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
                            <td aling='center' class='CobaltFieldCaptionTD'> Habilitado</td>
                            <td aling='center' class='CobaltFieldCaptionTD'> C&oacute;digo Examen </td>
                            <td aling='center' class='CobaltFieldCaptionTD'> Nombre Examen </td>
                            <td aling='center' class='CobaltFieldCaptionTD'> &Aacute;rea</td>
                            <td aling='center' class='CobaltFieldCaptionTD'>Plantilla</td>
                            <td aling='center' class='CobaltFieldCaptionTD'>C&oacute;digo del Est&aacute;ndar</td>
                            <td aling='center' class='CobaltFieldCaptionTD'>Solicitado en</td>
                            <td aling='center' class='CobaltFieldCaptionTD'>Formulario</td>
                            <td aling='center' class='CobaltFieldCaptionTD'>Tabulador</td>
                            <td aling='center' class='CobaltFieldCaptionTD'>Tipo Viñeta</td>
                            <td aling='center' class='CobaltFieldCaptionTD'>Urgente</td>
                            <td aling='center' class='CobaltFieldCaptionTD'>Sexo</td>
                            <td aling='center' class='CobaltFieldCaptionTD'>Tiempo Previo</td>
		      </tr>";
            while($row = pg_fetch_array($consulta)){
		echo "<tr>
                            <td aling='center'> 
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"pedirDatos('".$row[0]."')\"></td>
                            <td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' ".
				"onclick='Estado(\"".$row['id']."\",\"".$row['condicion']."\")'>".$row['cond']."</td>
                            <td>".$row['idexamen']." </td>
                            <td>".htmlentities($row['nombreexamen'])."</td>
                            <td>".htmlentities($row['nombrearea'])."</td>
                            <td>".htmlentities($row['idplantilla'])."</td>
                            <td>".htmlentities($row['idestandar'])."</td>
                            <td>".htmlentities($row['ubicacion'])."</td>";
		    
                        //if ($row['Ubicacion']=='0')
                              //   echo"<td>SI</td>";
                             // else
                               //  echo"<td>NO</td>";*/
                   if(!empty($row['nombreformulario']))
                       echo"<td>".htmlentities($row['nombreformulario'])." </td> ";
                   else
                      echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                      echo "<td>".htmlentities($row['estandarrep'])." </td>";
                    
                     if ($row['impresion']=='G')                      
		       echo"<td>General</td>";
                     else
                       echo"<td>Especial </td>";
                     if($row['urgente']==0)
                       echo"<td>NO</td>";
                     else
                       echo"<td>SI</td>";
                     
                  if(!empty($row['nombresexo']))      
                     echo"<td>".htmlentities($row['nombresexo'])." </td>";
                  else
                     echo "<td>Ambos</td>";
                  if(!empty($row['rangotiempoprev']))
                     echo"<td>".$row['rangotiempoprev']." </td><tr>";
                  else       
                        echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><tr>
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
	case 9://Muestra los formularios para cada programa
		$IdPrograma=$_POST['idprograma'];
        //echo $IdPrograma;  
	  	$rslts='';
		$consulta= $objdatos->consultar_formularios($lugar);
		
		$rslts = '<select name="cmbConForm" id="cmbConForm"   size"1">';
		$rslts .='<option value="0">--Seleccione--</option>';
			
		while ($rows =pg_fetch_array($consulta)){
			$rslts.= '<option value="' .$rows[0].'" >'.htmlentities($rows[1]).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;
		
	break;
}

?>