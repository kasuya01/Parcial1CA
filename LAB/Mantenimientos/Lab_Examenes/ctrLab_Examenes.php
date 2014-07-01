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
		$idestandar=$_POST['idestandar'];
		$plantilla=$_POST['plantilla'];
		$observacion=$_POST['observacion'];
		$ubicacion=$_POST['ubicacion'];
		$IdFormulario=$_POST['idformulario'];
		$IdEstandarResp=$_POST['idestandarRep'];
		$etiqueta=$_POST['etiqueta'];
                $Urgente=$_POST['urgente'];
                $sexo=$_POST['sexo'];
                $Hab=$_POST['Hab'];
                
                $TiempoPrevio=$_POST['tiempoprevio'];
                //echo $Hab;
			
                $cond='H';
                    if ($etiqueta=="O"){
                            $dato=$objdatos->obtener_letra($idarea);
                            $rowletra=mysql_fetch_array($dato);
                            $rletra= $rowletra[0];
                            if (!empty($rletra)){
                            	$num=$rletra +1;
				if ($num==71){
                                    $num=$num+1;
                                    $letra=chr($num);
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
		If (($objdatos->insertar($idexamen,$idarea,$nomexamen,$idestandar,$observacion,$usuario,$sexo)==true) && 
                    ($objdatos->IngExamenxEstablecimiento($idexamen,$lugar,$cond,$usuario,$IdFormulario,$IdEstandarResp,$plantilla,$letra,$Urgente,$ubicacion,$TiempoPrevio)==true))
		
                    {
                        if($plantilla<>"A"){
                           // echo  $idexamen."  ".$idarea."  ".$usuario."  ".$lugar;
                            if($objdatos->AgregarDatosFijos($idexamen,$idarea,$usuario,$lugar)==true)
                               echo "Registro Agregado";
                            else 
                               echo "No se pudo agregar el registro";
                        }else
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
			$observacion=$_POST['observacion'];
			$ubicacion=$_POST['ubicacion'];
			$IdFormulario=$_POST['idformulario'];
			$IdEstandarResp=$_POST['idestandarRep'];
			$etiqueta=$_POST['Etiqueta'];
                        $Urgente=$_POST['urgente'];
                        $sexo=$_POST['idsexo'];
                        $Hab=$_POST['Hab'];
                        
                        $TiempoPrevio=$_POST['Tiempo'];
			//echo $Hab; 
			$letra="";
				
				
			if ($etiqueta=="O"){
				$dato=$objdatos->obtener_letra($idarea);
				$rowletra=mysql_fetch_array($dato);
				$rletra= $rowletra[0];
//echo $rowletra[0];
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
              	If(($objdatos->actualizar($idexamen,$idarea,$nomexamen,$idestandar,$observacion,$usuario,$sexo)==true)
		&& ($objdatos->ActExamenxEstablecimiento($idexamen,$lugar,$usuario,$IdFormulario,$IdEstandarResp,$plantilla,$letra,$Urgente,$ubicacion,$Hab,$TiempoPrevio)==true)){
		               
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
		while($row = mysql_fetch_array($consulta)){
		 echo "<tr>
                            <td aling='center'> 
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"pedirDatos('".$row[0]."')\"> </td>
                            <td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' ".
                   	"onclick='Estado(\"".$row['IdExamen']."\",\"".$row['Condicion']."\")'>".$row['Cond']."</td>
                            <td>".$row['IdExamen']." </td>
                            <td>".htmlentities($row['NombreExamen'])." </td>
                            <td>".htmlentities($row['NombreArea'])." </td>
                            <td>".htmlentities($row['IdPlantilla'])." </td>
                            <td>".htmlentities($row['IdEstandar'])." </td>
                            <td>".htmlentities($row['Ubicacion'])."</td>";
		  
                    if(!empty($row['NombreFormulario']))
		       echo"<td>".htmlentities($row['NombreFormulario'])." </td> ";
                    else       
                         echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                     echo "<td>".htmlentities($row['IdEstandarRep'])." </td>";
                     if ($row['Impresion']=='G')                      
		       echo"<td>General</td>";
                     else
                       echo"<td>Especial </td>";
                     if($row['Urgente']==0)
                        echo"<td>NO</td>";
                     else
                       echo"<td>SI</td>
                                 ";
                     echo"<td>".htmlentities($row['sexovn'])." </td>";
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
			$cod=$objdatos->LeerUltimoCodigo($idarea);
            $dato=substr("$cod", -3);
            $val=(int)$dato;
            $consulta= $val+1;
		if ($consulta >= 0 && $consulta  <= 9){
		$codigo=$idarea.'00'.$consulta;
		}
		if ($consulta >= 10 && $consulta  <= 99){
			$codigo=$idarea.'0'.$consulta;
			//document.frmnuevo.txtidexamen.value=idArea+'0'+numero;
		}
		if ($consulta >= 100 && $consulta  <= 999){
			$codigo=$idarea.$consulta;
			//document.frmnuevo.txtidexamen.value=idArea+numero;
		}
    		 echo "<input type='text' id='txtidexamen'  name='txtidexamen' value='".$codigo."'  />";
                   
    break;
	case 6:
		$IdPrograma=$_POST['idprograma'];
           	//echo $idarea; 
	  	$rslts='';
		$consulta= $objdatos->consultar_formularios($IdPrograma,$lugar);
		//$dtMed=$obj->LlenarSubServ($proce);	
		
		$rslts = '<select name="cmbFormularios" id="cmbFormularios" size="1" >';
		$rslts .='<option value="0">--Seleccione--</option>';
			
		while ($rows =mysql_fetch_array($consulta)){
			$rslts.= '<option value="' .$rows[0].'" >'.htmlentities($rows[1]).'</option>';
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
				$observacion=$_POST['observacion'];
				//$activo=$_POST['activo'];
				$ubicacion=$_POST['ubicacion'];
				$cond=$_POST['condicion'];	
				$IdFormulario=$_POST['idformulario'];
				$IdEstandarResp=$_POST['idestandarRep'];
                                $etiqueta=$_POST['etiqueta'];
				$idsexo=$_POST['sexo'];
                                $Hab=$_POST['Hab'];
                                //echo $Hab;
                                //echo $etiqueta;
				$conExam=$objdatos->BuscarExamen($idexamen,$lugar);
                                
				//print_r ($conExam);
				$ExisExa=mysql_fetch_array($conExam);
				//print_r ($ExisExa[0]);
				//echo $ExisExa[0];
				$query = "SELECT lab_examenes.IdExamen,lab_examenes.IdEstandar,lab_examenes.IdArea,lab_examenes.NombreExamen,
					 lab_codigosestandar.Descripcion,lab_areas.NombreArea,lab_examenesxestablecimiento.Idplantilla,
					 lab_examenesxestablecimiento.Condicion,
					 IF(lab_examenesxestablecimiento.Condicion='H','Habilitado','Inhabilitado')as Cond,
					 CASE lab_examenesxestablecimiento.Ubicacion
                                                WHEN 0 THEN 'Todas las Procedencias'
                                                WHEN 1 then 'Hospitalización y Emergencia'
                                                WHEN 4 then 'Laboratorio'
                                         END AS Ubicacion,
                                         NombreFormulario,lab_examenesxestablecimiento.IdEstandarRep,
                                         lab_examenesxestablecimiento.Impresion,Urgente,mnt_sexo.sexovn,mnt_sexo.IdSexo,
                                         lab_examenesxestablecimiento.Impresion,rangotiempoprev  
					 FROM lab_examenesxestablecimiento 
					 INNER JOIN lab_examenes ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
					 INNER JOIN lab_areasxestablecimiento ON lab_examenes.IdArea=lab_areasxestablecimiento.IdArea
					 INNER JOIN lab_areas ON lab_areasxestablecimiento.IdArea=lab_areas.IdArea
					 INNER JOIN lab_codigosestandar ON lab_examenes.IdEstandar= lab_codigosestandar.IdEstandar
                                         LEFT JOIN mnt_formularios ON lab_examenesxestablecimiento.IdFormulario=mnt_formularios.IdFormulario 
                                         INNER JOIN mnt_sexo ON lab_examenes.IdSexo= mnt_sexo.IdSexo
                                         LEFT JOIN cit_programacionxexams ON lab_examenesxestablecimiento.idexamen=cit_programacionxexams.idexam
					 WHERE lab_areasxestablecimiento.Condicion='H' AND lab_areasxestablecimiento.IdEstablecimiento=$lugar
					 AND lab_examenesxestablecimiento.IdEstablecimiento=$lugar AND ";
					$ban=0;
					
						//VERIFICANDO LOS POST ENVIADOS
                                               // echo $ExisExa[0]; 
                                        if($ExisExa[0]>0){//si existe el examen 

                                             if (!empty($_POST['idexamen']))
                                                 { $query .= " lab_examenes.IdExamen='".$_POST['idexamen']."' AND"; }
                                        }	
                                        if (!empty($_POST['nomexamen']))
					{ $query .= " NombreExamen like'%".$_POST['nomexamen']."%' AND"; }
						
					if (!empty($_POST['idarea']))
					{ $query .= " lab_areas.IdArea='".$_POST['idarea']."' AND"; }
						
					if (!empty($_POST['plantilla']))
					{ $query .= " lab_examenesxestablecimiento.Idplantilla='".$_POST['plantilla']."' AND"; }
						
					if (!empty($_POST['idestandar']))
					{ $query .= " lab_codigosestandar.IdEstandar='".$_POST['idestandar']."' AND"; }
					
					if (!empty($_POST['idestandarRep']))
					{ $query .= " lab_codigosestandar.IdEstandar='".$_POST['idestandarRep']."' AND"; }
						
					if (!empty($_POST['idformulario']))
					{ $query .= " lab_examenesxestablecimiento.IdFormulario='".$_POST['idformulario']."' AND"; }
                                        
                                        if (!empty($_POST['ubicacion']))
					{ $query .= " lab_examenesxestablecimiento.Ubicacion='".$_POST['ubicacion']."' AND"; }
						
					if (!empty($_POST['etiqueta'])){
                                            if ($_POST['etiqueta']=='G')
                                                { $query .= "  lab_examenesxestablecimiento.Impresion='G' AND"; }
                                            else    
                                                { $query .= "  lab_examenesxestablecimiento.Impresion<>'G' AND"; } 
                                        }        
					if (!empty($_POST['sexo']))
					{ $query .= "  lab_examenes.IdSexo ='".$_POST['sexo']."' AND"; }
                                        
                                        if (!empty($_POST['Hab'])){
                                            if ($_POST['Hab']=='H')
                                                { $query .= "  lab_examenesxestablecimiento.Condicion='H' AND"; }
                                            else    
                                                { $query .= "  lab_examenesxestablecimiento.Condicion='I' AND"; } 
                                        }      
                                        
                                        else{$ban=1;}
						
						
					if ($ban==0)
					{    $query = substr($query ,0,strlen($query)-4);
					     $query_search = $query. " ORDER BY lab_examenes.IdArea,lab_examenes.IdExamen";
					}
					else {
                                            $query = substr($query ,0,strlen($query)-4);
                                            $query_search = $query. " ORDER BY lab_examenes.IdArea,lab_examenes.IdExamen";
					}
			
		//echo $query_search;
                //para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
		
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		 /////LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);
		//echo $query_search;
		//muestra los datos consultados en la tabla
		echo "<table border = 1 align='center' width='85%' class='StormyWeatherFormTABLE'>
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
			while($row = mysql_fetch_array($consulta)){
		echo "<tr>
                            <td aling='center'> 
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"pedirDatos('".$row[0]."')\"></td>
                            <td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' ".
				"onclick='Estado(\"".$row['IdExamen']."\",\"".$row['Condicion']."\")'>".$row['Cond']."</td>
                            <td>".$row['IdExamen']." </td>
                            <td>".htmlentities($row['NombreExamen'])."</td>
                            <td>".htmlentities($row['NombreArea'])."</td>
                            <td>".htmlentities($row['Idplantilla'])."</td>
                            <td>".htmlentities($row['IdEstandar'])."</td>
                            <td>".htmlentities($row['Ubicacion'])."</td>";
		    
              //if ($row['Ubicacion']=='0')
		    //   echo"<td>SI</td>";
		   // else
		     //  echo"<td>NO</td>";*/
                       echo"<td>".htmlentities($row['NombreFormulario'])." </td> 
                            <td>".htmlentities($row['IdEstandarRep'])." </td>";
                    
                       if ($row['Impresion']=='G')                      
		       echo"<td>General</td>";
                     else
                       echo"<td>Especial </td>";
                     if($row['Urgente']==0)
                       echo"<td>NO</td>";
                     else
                       echo"<td>SI</td>";
                     
                       echo"<td>".htmlentities($row['sexovn'])." </td>";
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
		$observacion=$_POST['observacion'];
		//$activo=$_POST['activo'];
		$ubicacion=$_POST['ubicacion'];
		$cond=$_POST['condicion'];
		$IdFormulario=$_POST['idformulario'];
		$IdEstandarResp=$_POST['idestandarRep'];
                $etiqueta=$_POST['etiqueta'];
                $idsexo=$_POST['sexo'];
			//echo $IdFormulario."--".$IdEstandarResp;
		$conExam=$objdatos->BuscarExamen($idexamen,$lugar);
				//print_r ($conExam);
		$ExisExa=mysql_fetch_array($conExam);
				//print_r ($ExisExa[0]);
				
		
			  $query = "SELECT lab_examenes.IdExamen,lab_examenes.IdEstandar,lab_examenes.IdArea,lab_examenes.NombreExamen,
				    lab_codigosestandar.Descripcion,lab_areas.NombreArea,lab_examenesxestablecimiento.Idplantilla,
				    lab_examenesxestablecimiento.Condicion,
				    IF(lab_examenesxestablecimiento.Condicion='H','Habilitado','Inhabilitado')as Cond,
				    CASE lab_examenesxestablecimiento.Ubicacion
                                                WHEN 0 THEN 'Todas las Procedencias'
                                                WHEN 1 then 'Hospitalización y Emergencia'
                                                WHEN 4 then 'Laboratorio'
                                    END AS Ubicacion,
                                    NombreFormulario,lab_examenesxestablecimiento.IdEstandarRep,
                                    lab_examenesxestablecimiento.Impresion,Urgente,sexovn,mnt_sexo.IdSexo,lab_examenesxestablecimiento.Impresion,
                                    rangotiempoprev  
                                    FROM lab_examenesxestablecimiento 
				    INNER JOIN lab_examenes ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
				    INNER JOIN lab_areasxestablecimiento ON lab_examenes.IdArea=lab_areasxestablecimiento.IdArea
				    INNER JOIN lab_areas ON lab_areasxestablecimiento.IdArea=lab_areas.IdArea
				    INNER JOIN lab_codigosestandar ON lab_examenes.IdEstandar= lab_codigosestandar.IdEstandar
				    LEFT JOIN mnt_formularios ON lab_examenesxestablecimiento.IdFormulario=mnt_formularios.IdFormulario
                                    INNER JOIN mnt_sexo ON lab_examenes.IdSexo= mnt_sexo.IdSexo
                                    LEFT JOIN cit_programacionxexams ON lab_examenesxestablecimiento.idexamen=cit_programacionxexams.idexam
				    WHERE lab_areasxestablecimiento.Condicion='H'  
				    AND lab_areasxestablecimiento.IdEstablecimiento=$lugar
				    AND lab_examenesxestablecimiento.IdEstablecimiento=$lugar AND ";
                          
                          
                          
                          
		$ban=0;
		
		//VERIFICANDO LOS POST ENVIADOS
		if($ExisExa[0]>=1){//verifica si existe el examen 
				   			
			if (!empty($_POST['idexamen']))
				{ $query .= " lab_examenes.IdExamen='".$_POST['idexamen']."' AND"; }
		}	
		
		if (!empty($_POST['nomexamen']))
		{ $query .= " NombreExamen='".$_POST['nomexamen']."' AND"; }
		
		if (!empty($_POST['idarea']))
		{ $query .= " lab_areas.IdArea='".$_POST['idarea']."' AND"; }
		
		if (!empty($_POST['plantilla']))
		{ $query .= " lab_examenesxestablecimiento.Idplantilla='".$_POST['plantilla']."' AND"; }
		
		if (!empty($_POST['idestandar']))
		{ $query .= " lab_codigosestandar.IdEstandar='".$_POST['idestandar']."' AND"; }
        
		if (!empty($_POST['idestandar']))
		{ $query .= " lab_codigosestandar.IdEstandar='".$_POST['idestandar']."' AND"; }
        
		if (!empty($_POST['idestandar']))
		{ $query .= " lab_codigosestandar.IdEstandar='".$_POST['idestandar']."' AND"; }
        
		if (!empty($_POST['ubicacion']))
		{ $query .= " lab_examenesxestablecimiento.Ubicacion='".$_POST['ubicacion']."' AND"; }
               
		
		if (!empty($_POST['idestandarRep']))
		{ $query .= " lab_codigosestandar.IdEstandar='".$_POST['idestandarRep']."' AND"; }
		
		if (!empty($_POST['etiqueta'])){
                   if ($_POST['etiqueta']=='G')
                         { $query .= "  lab_examenesxestablecimiento.Impresion='G' AND"; }
                   else    
                         { $query .= "  lab_examenesxestablecimiento.Impresion<>'G' AND"; } 
                }        
		if (!empty($_POST['sexo']))
		{ $query .= "  lab_examenes.IdSexo ='".$_POST['sexo']."' AND"; }
                
                if (!empty($_POST['Hab'])){
                    if ($_POST['Hab']=='H')
                        { $query .= "  lab_examenesxestablecimiento.Condicion='H' AND"; }
                    else    
                        { $query .= "  lab_examenesxestablecimiento.Condicion='I' AND"; } 
                    }      
                                        
		else{$ban=1;}
		
		
		if ($ban==0)
		{    $query = substr($query ,0,strlen($query)-4);
			 $query_search = $query. " ORDER BY lab_examenes.IdArea,lab_examenes.IdExamen";
		}
		else {
			$query = substr($query ,0,strlen($query)-4);
			$query_search = $query. " ORDER BY lab_examenes.IdArea,lab_examenes.IdExamen";
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
            while($row = mysql_fetch_array($consulta)){
		echo "<tr>
                            <td aling='center'> 
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"pedirDatos('".$row[0]."')\"> </td>
                            <td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' ".
				"onclick='Estado(\"".$row['IdExamen']."\",\"".$row['Condicion']."\")'>".$row['Cond']."</td>
                            <td>".$row['IdExamen']." </td>
                            <td>".htmlentities($row['NombreExamen'])." </td>
                            <td>".htmlentities($row['NombreArea'])." </td>
                            <td>".htmlentities($row['Idplantilla'])." </td>
                            <td>".htmlentities($row['IdEstandar'])." </td>	";
		   
                if ($row['Ubicacion']=='0')
		       echo"<td>SI</td>";
		    else
		       echo"<td>NO</td>";
		       echo"<td>".htmlentities($row['NombreFormulario'])." </td> 
			    <td>".htmlentities($row['IdEstandarRep'])." </td>";
                   
                    if ($row['Impresion']=='G')                      
		       echo"<td>General</td>";
                    else
                       echo"<td>Especial </td>";
                    if($row['Urgente']==0)
                        echo"<td>NO</td>";
                    else
                         echo"<td>SI</td>";
                         echo"<td>".htmlentities($row['sexovn'])." </td>";
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
		$consulta= $objdatos->consultar_formularios($IdPrograma,$lugar);
		
		$rslts = '<select name="cmbConForm" id="cmbConForm"   size"1">';
		$rslts .='<option value="0">--Seleccione--</option>';
			
		while ($rows =mysql_fetch_array($consulta)){
			$rslts.= '<option value="' .$rows[0].'" >'.htmlentities($rows[1]).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;
		
	break;
}

?>