<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include_once("clsLab_Examenes.php");
$objdatos = new clsLab_Examenes;
$Clases = new clsLabor_Examenes;

$Pag =$_POST['Pag'];
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
				//echo $etiqueta; 
			
				if ($etiqueta=="O"){
				//echo  "entro";
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
						$letra=chr($num);}
					}
					else{
					  $num=65;
					   $letra=chr($num);}
					
					//echo $num."-".$letra;
				}
				else {
					$letra=$etiqueta;}
				                //$activo=$_POST['activo'];
				$activo='S';
                $cond='H';
                     echo $plantilla;
				If (($objdatos->insertar($idexamen,$idarea,$nomexamen,$idestandar,$plantilla,$observacion,$activo,$letra,$ubicacion,$usuario)==true) && ($objdatos->IngExamenxEstablecimiento($idexamen,$lugar,$cond,$usuario,$IdFormulario,$IdEstandarResp)==true) && ($Clases->insertar_labo($idexamen,$idarea,$nomexamen,$idestandar,$plantilla,$observacion,$activo,$ubicacion,$usuario)==true))
				{
				    if($plantilla!="A"){
					       $objdatos->insertar_fijos($idexamen,$idarea,$lugar,$usuario);
					}
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
				//echo $IdFormulario;
              	If(($objdatos->actualizar($idexamen,$idarea,$nomexamen,$idestandar,$observacion,$plantilla,$etiqueta,$ubicacion,$usuario)==true)&& $objdatos->ActExamenxEstablecimiento($idexamen,$lugar,$usuario,$IdFormulario,$IdEstandarResp) && ($Clases->actualizar_labo($idexamen,$idarea,$nomexamen,$idestandar,$observacion,$plantilla,$ubicacion,$usuario)==true)){

                       echo "Registro Actualizado"	;			
				}
				else{
					   echo "No se pudo actualizar en Registro";
				}
		
   	break;
	case 3:
                $cond=$_POST['condicion'];
				$idexamen=$_POST['idexamen'];
            	$resultado=$objdatos->EstadoCuenta($idexamen,$cond,$lugar);
				
	break;
	case 4:// PAGINACION
		//para manejo de la paginacion
		$RegistrosAMostrar=4;
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
					<td class='CobaltFieldCaptionTD'>Consulta Externa</td>
     			    <td class='CobaltFieldCaptionTD'>Formulario</td>
				    <td class='CobaltFieldCaptionTD'>Tabulador</td>
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
					<td>".htmlentities($row['IdEstandar'])." </td>	";
		    if ($row['Ubicacion']=='0')
		       echo"<td>SI</td>";
		    else
		       echo"<td>NO</td>";
			   echo"<td>".htmlentities($row['NombreFormulario'])." </td> 
					<td>".htmlentities($row['IdEstandarRep'])." </td>
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
		$consulta= $objdatos->consultar_formularios($IdPrograma);
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
				$activo=$_POST['activo'];
				$ubicacion=$_POST['ubicacion'];
				$cond=$_POST['condicion'];	
				$IdFormulario=$_POST['idformulario'];
				$IdEstandarResp=$_POST['idestandarRep'];
				
				$conExam=$objdatos->BuscarExamen($idexamen,$lugar);
				//print_r ($conExam);
				$ExisExa=mysql_fetch_array($conExam);
				//print_r ($ExisExa[0]);
				
				$query = "SELECT lab_examenes.IdExamen,lab_examenes.IdEstandar,lab_examenes.IdArea,lab_examenes.NombreExamen,
				 		 lab_codigosestandar.Descripcion,lab_areas.NombreArea,lab_examenes.Idplantilla,
						 lab_examenesxestablecimiento.Condicion,
						 IF(lab_examenesxestablecimiento.Condicion='H','Habilitado','Inhabilitado')as Cond,
						 lab_examenes.Ubicacion,NombreFormulario,lab_examenesxestablecimiento.IdEstandarRep  
						 FROM lab_examenes 
						 INNER JOIN lab_areas  ON lab_examenes.IdArea=lab_areas.IdArea
						 INNER JOIN lab_codigosestandar  ON lab_examenes.IdEstandar=lab_codigosestandar.IdEstandar
						 INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen 
						 LEFT JOIN mnt_formularios ON lab_examenesxestablecimiento.IdFormulario=mnt_formularios.IdFormulario
						 WHERE IdEstablecimiento=$lugar AND ";
						$ban=0;
					
						//VERIFICANDO LOS POST ENVIADOS
					if($ExisExa[0]>=1){//si existe el examen 
				   			
						if (!empty($_POST['idexamen']))
						{ $query .= " lab_examenes.IdExamen='".$_POST['idexamen']."' AND"; }
					}	
						if (!empty($_POST['nomexamen']))
						{ $query .= " NombreExamen like'%".$_POST['nomexamen']."%' AND"; }
						
						if (!empty($_POST['idarea']))
						{ $query .= " lab_areas.IdArea='".$_POST['idarea']."' AND"; }
						
						if (!empty($_POST['plantilla']))
						{ $query .= " Idplantilla='".$_POST['plantilla']."' AND"; }
						
						if (!empty($_POST['idestandar']))
						{ $query .= " lab_codigosestandar.IdEstandar='".$_POST['idestandar']."' AND"; }
						
						if (!empty($_POST['idestandarRep']))
						{ $query .= " lab_codigosestandar.IdEstandar='".$_POST['idestandarRep']."' AND"; }
						
						if (!empty($_POST['idformulario']))
						{ $query .= " mnt_formularios.IdFormulario='".$_POST['idformulario']."' AND"; }
						
						if (!empty($_POST['ubicacion']))
						{ $query .= "  lab_examenes.Ubicacion ='".$_POST['ubicacion']."' AND"; }
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
		////para manejo de la paginacion
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
			        <td class='CobaltFieldCaptionTD'>Consulta Externa</td>	
					<td class='CobaltFieldCaptionTD'>Formulario</td>
				    <td class='CobaltFieldCaptionTD'>Tabulador</td>
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
					<td>".htmlentities($row['IdEstandar'])." </td>";
		    if ($row['Ubicacion']=='0')
		       echo"<td>SI</td>";
		    else
		       echo"<td>NO</td>";
			   echo"<td>".htmlentities($row['NombreFormulario'])." </td> 
					<td>".htmlentities($row['IdEstandarRep'])." </td>
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
				$activo=$_POST['activo'];
				$ubicacion=$_POST['ubicacion'];
				$cond=$_POST['condicion'];
				$IdFormulario=$_POST['idformulario'];
				$IdEstandarResp=$_POST['idestandarRep'];
				$conExam=$objdatos->BuscarExamen($idexamen,$lugar);
				//print_r ($conExam);
				$ExisExa=mysql_fetch_array($conExam);
				//print_r ($ExisExa[0]);
				
		$query = "SELECT lab_examenes.IdExamen,lab_examenes.IdEstandar,lab_examenes.IdArea,lab_examenes.NombreExamen,
			  lab_codigosestandar.Descripcion,lab_areas.NombreArea,lab_examenes.Idplantilla,
			  lab_examenesxestablecimiento.Condicion,IF(lab_examenesxestablecimiento.Condicion='H','Habilitado','Inhabilitado')as Cond,lab_examenes.Ubicacion,
			  mnt_formularios.NombreFormulario,lab_examenesxestablecimiento.IdEstandarRep
			  FROM lab_examenes 
			  INNER JOIN lab_areas  ON lab_examenes.IdArea=lab_areas.IdArea
			  INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
			  INNER JOIN lab_codigosestandar  ON lab_examenes.IdEstandar=lab_codigosestandar.IdEstandar
			  INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen 
			  LEFT JOIN mnt_formularios ON lab_examenesxestablecimiento.IdFormulario= mnt_formularios.IdFormulario
			  WHERE lab_examenesxestablecimiento.IdEstablecimiento=$lugar AND lab_areasxestablecimiento.IdEstablecimiento=$lugar AND lab_areasxestablecimiento.Condicion='H' AND ";
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
		{ $query .= " Idplantilla='".$_POST['plantilla']."' AND"; }
		
		if (!empty($_POST['idestandar']))
		{ $query .= " lab_codigosestandar.IdEstandar='".$_POST['idestandar']."' AND"; }
        
		if (!empty($_POST['idestandar']))
		{ $query .= " lab_codigosestandar.IdEstandar='".$_POST['idestandar']."' AND"; }
        
		if (!empty($_POST['idestandar']))
		{ $query .= " lab_codigosestandar.IdEstandar='".$_POST['idestandar']."' AND"; }
        
		if (!empty($_POST['ubicacion']))
		{ $query .= "  lab_examenes.Ubicacion ='".$_POST['ubicacion']."' AND"; }
		
		if (!empty($_POST['idestandarRep']))
		{ $query .= " lab_codigosestandar.IdEstandar='".$_POST['idestandarRep']."' AND"; }
		
		if (!empty($_POST['idformulario']))
		{ $query .= " mnt_formularios.IdFormulario='".$_POST['idformulario']."' AND"; }
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
					<td class='CobaltFieldCaptionTD'> C&oacute;digo Examen </td>
					<td class='CobaltFieldCaptionTD'> Nombre Examen </td>
					<td class='CobaltFieldCaptionTD'> &Aacute;rea</td>
                    <td class='CobaltFieldCaptionTD'>Plantilla</td>
                    <td class='CobaltFieldCaptionTD'>C&oacute;digo del Est&aacute;ndar</td>
					<td class='CobaltFieldCaptionTD'>Consulta Externa</td>
					<td class='CobaltFieldCaptionTD'>Formulario</td>
				    <td class='CobaltFieldCaptionTD'>Tabulador</td>	
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
				     <td>".htmlentities($row['IdEstandarRep'])." </td></tr>";
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
           
	  	$rslts='';
		$consulta= $objdatos->consultar_formularios($IdPrograma);
		
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