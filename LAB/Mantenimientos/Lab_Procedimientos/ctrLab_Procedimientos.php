<?php session_start();
include_once("clsLab_Procedimientos.php");
//include_once("Clases_labo.php");//lkillm

$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//variables POST


//$Pag =$_POST['Pag'];
$opcion=$_POST['opcion'];
//echo $opcion;
$objdatos = new clsLab_Procedimientos;
$Clases = new clsLabor_Procedimientos;



switch ($opcion) 
{
     case 1:  //INSERTAR	
		$idexamen=$_POST['idexamen'];
		$proce=$_POST['proce'];
		$idarea=$_POST['idarea'];
		$unidades=$_POST['unidades'];
                $sexo=$_POST['sexo'];
                $redad=$_POST['redad'];
                
		if (empty($_POST['rangoini'])){
			$rangoini="(NULL)";
		}
		else{
			$rangoini=$_POST['rangoini'];
		}
	
		if (empty($_POST['rangofin'])){
			$rangofin="(NULL)";
		}else{
			$rangofin=$_POST['rangofin'];
		}
	
		if (empty($_POST['Fechaini'])){
				$Fechaini="(NULL)";
		}else{ 
			$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
		}
		if (empty($_POST['Fechafin'])){
			$Fechafin="(NULL)";
		}else{ 
			$FechaF=explode('/',$_POST['Fechafin']);
			$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];	
		}
		//echo $Fechaini."-".$Fechafin; 
                
		if ($objdatos->insertar($proce,$idarea,$idexamen,$unidades,$rangoini,$rangofin,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)==true) 
                        /*&& 
                   ($Clases->insertar_labo($proce,$idarea,$idexamen,$unidades,$rangoini,$rangofin,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)==true))*/
		{
			echo "Registro Agregado";
		}
		else{
			echo "No se pudo Agregar";			
		}
				
    break;
    
    case 2:  //MODIFICAR      
	         $idexamen=$_POST['idexamen'];
		$proce=$_POST['proce'];
		$idarea=$_POST['idarea'];
		$unidades=$_POST['unidades'];
		$idproce=$_POST['idproce'];
                
                $sexo=$_POST['sexo'];
                $redad=$_POST['redad'];
		if (empty($_POST['rangoini'])){
			$rangoini="(NULL)";
		}
		else{
			$rangoini=$_POST['rangoini'];
		}
		
		if (empty($_POST['rangofin'])){
			$rangofin="(NULL)";
		}else{
			$rangofin=$_POST['rangofin'];
		}
		
		if (empty($_POST['Fechaini'])){
				$Fechaini="(NULL)";
		}else{ 
			$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini=$FechaI[2].'-'.$FechaI[1].'-'.$FechaI[0];
		}
		
		if (empty($_POST['Fechafin'])){
			$Fechafin=" ";
		}else{ 
			$FechaF=explode('/',$_POST['Fechafin']);
			$Fechafin=$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0];	
		}
		
		if ($objdatos->actualizar($idproce,$proce,$idarea,$idexamen,$unidades,$rangoini,$rangofin,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)==true){ 
                    //if($Clases->actualizar_labo($idproce,$proce,$idarea,$idexamen,$unidades,$rangoini,$rangofin,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)==true)
                    
			echo "Registro Actualizado";			
                    
                   // else
			//echo "2 No se pudo actualizar LABo";
                        
		}
		else
                    echo "No se pudo actualizar";
                    
	break;
	case 3:  //ELIMINAR    
		//Vefificando Integridad de los datos
		$idproce =$_POST['idproce']; 
			//echo $idproce."**".$lugar;
			 if ($objdatos->eliminar($idproce,$lugar)==true){
                                 //&& ($Clases->eliminar_labo($idproce,$lugar))){		
				echo "Registro Eliminado" ;		
					
				}
			else{
				echo "El registro no pudo ser eliminado ";
				}			
	break;
		
	case 4:// PAGINACION
		//require_once("clsLab_Procedimientos.php");
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
                
		 /////LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
	  echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
                    <tr>
                        <td  class='CobaltFieldCaptionTD' aling='center'> Modificar</td>
			<!-- <td  class='CobaltFieldCaptionTD' aling='center'> Eliminar</td> -->
		<!--	<td class='CobaltFieldCaptionTD'> IdExamen </td> -->
			<td class='CobaltFieldCaptionTD'> Examen </td>
			<td class='CobaltFieldCaptionTD'> Procedimiento </td>
			<td class='CobaltFieldCaptionTD'> Unidades </td>	   
			<td class='CobaltFieldCaptionTD'> Valores Normales </td>
                        <td class='CobaltFieldCaptionTD'> Sexo</td>
                        <td class='CobaltFieldCaptionTD'> Rango de Edad </td>
			<td class='CobaltFieldCaptionTD'> Fecha Inicio </td>	 
			<td class='CobaltFieldCaptionTD'> Fecha Finalización </td>
                    </tr>";

          while($row = pg_fetch_array($consulta)){
            
              
               echo "<tr>
                    <td aling='center'> 
                        <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			onclick=\"pedirDatos('".$row[10]."')\"> </td>
                   <!-- <td aling ='center'> 
			 <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			onclick=\"eliminarDato('".$row['id']."',$lugar)\"> </td> -->
                 <!--id examen <td>".$row[0]."aaa"."</td> -->
                    <td>".htmlentities($row[1])."</td>
                    <td>".htmlentities($row['nombreprocedimiento'])."</td>
                    <td>".htmlentities($row['unidades'])."</td>
                    <td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
              echo "<td>".$row[8]."</td>
                    <td>".$row['nombre']."</td>";
	   if(($row['fechaini']=="(NULL)") || ($row['fechaini']=="00/00/0000") ||(empty($row['fechaini'])) )
              echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
	   else
	      echo "<td>".$row['fechaini']."</td>";
								
			if((empty($row['fechafin'])) || ($row['fechafin']=="(NULL)") || ($row['fechafin']=="00/00/0000")) 
		      echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else	
			  echo "<td>".$row['fechafin']."</td>
			          ";
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
	case 5:  //LLENAR COMBO DE EXAMENES  
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
	
	case 6: //DIBUJANDO EL FORMULARIO DE NUEVO
	   	  
	break;
	case 7: //BUSQUEDA
		$idexamen=$_POST['idexamen'];
		$proce=$_POST['proce'];
		$idarea=$_POST['idarea'];
		$unidades=$_POST['unidades'];
                $sexo=$_POST['sexo'];
                $redad=$_POST['redad'];
		if (empty($_POST['rangoini'])){
			$rangoini="(NULL)";
		}
		else{
			$rangoini=$_POST['rangoini'];
		}
	
		if (empty($_POST['rangofin'])){
			$rangofin="(NULL)";
		}else{
			$rangofin=$_POST['rangofin'];
		}
		if (empty($_POST['Fechaini'])){
			$Fechaini="(NULL)";
		}else{ 
			$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini=$FechaI[2].'-'.$FechaI[1].'-'.$FechaI[0];
		}
		if (empty($_POST['Fechafin'])){
			$Fechafin="(NULL)";
		}else{ 
			$FechaF=explode('/',$_POST['Fechafin']);
			$Fechafin=$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0];	
		}
		 $query = /*"select  
                            lppe.id,lcee.id, lcee.nombre_examen, 
                            lppe.nombreprocedimiento, unidades,rangoinicio,rangofin, 
                            (lppe.fechaini) AS fechaini, (lppe.fechafin)AS fechafin, 
                            cex.id,cex.abreviatura,cre.id,cre.nombre,lppe.idsexo
                           
                            from mnt_area_examen_establecimiento mnt4
                            join lab_conf_examen_estab lcee on (mnt4.id=lcee.idexamen)
                            join lab_procedimientosporexamen lppe on (lppe.id_conf_examen_estab=lcee.id)
                            left JOIN ctl_sexo cex ON lppe.idsexo = cex.id 
                            left JOIN ctl_rango_edad  cre ON lppe.idrangoedad = cre.id 
                            where id_establecimiento=$lugar AND" ;*/
                 
                 "select  lppe.id,   lcee.id, lcee.nombre_examen, lppe.nombreprocedimiento, unidades,rangoinicio,rangofin,
                    (lppe.fechaini) AS fechaini, (lppe.fechafin)AS fechafin, cex.id,cex.abreviatura,cre.id,cre.nombre,lppe.idsexo 
                from ctl_area_servicio_diagnostico casd join mnt_area_examen_establecimiento mnt4 on 
                mnt4.id_area_servicio_diagnostico=casd.id join lab_conf_examen_estab lcee on 
                (mnt4.id=lcee.idexamen) 
                join lab_procedimientosporexamen lppe on (lppe.id_conf_examen_estab=lcee.id) 
                left JOIN ctl_sexo cex ON lppe.idsexo = cex.id left 
                JOIN ctl_rango_edad cre ON lppe.idrangoedad = cre.id 
                where id_establecimiento=$lugar AND ";
                 
                
              
		$ban=0;
		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['idarea']))
		{ $query .= " (case '".$_POST['idarea']."'
                            when '0' then id_area_servicio_diagnostico >=0
                            else  (id_area_servicio_diagnostico='".$_POST['idarea']."')end)
                                 AND"; }
		
		if (!empty($_POST['idexamen']))
		{ $query .= " (case '".$_POST['idexamen']."'
                                    when '0' then lcee.id >=0
                                    else  (lcee.id='".$_POST['idexamen']."') end) 
                         AND"; }
                         
    if (!empty($_POST['proce']))
		{ $query .= " nombreprocedimiento ilike '%%".$_POST['proce']."%%' AND"; }
                
                
		
		if (!empty($_POST['unidades']))
		{ $query .= " unidades ilike '%%".$_POST['unidades']."%%' AND"; }
	
		if (!empty($_POST['rangoini']))
		{ $query .= " rangoinicio'%%".$_POST['rangoini']."%%' AND"; }
		
		if (!empty($_POST['rangofin']))
		{ $query .= " rangofin'%%".$_POST['rangofin']."%%' AND"; }
                
                if (!empty($_POST['idsexo']))
		{ $query .= "(case '".$_POST['sexo']."'	
                        when '1' then lppe.idsexo='".$_POST['sexo']."'
                        when '2' then lppe.idsexo='".$_POST['sexo']."'
                        when '3' then lppe.idsexo='".$_POST['sexo']."'
	else (lppe.idsexo >=1 or lppe.idsexo=NULL) end)  AND"; }
                
                 if (!empty($_POST['redad']))
		{ $query .= " (case '".$_POST['redad']."'	
	when '1' then lppe.idrangoedad='".$_POST['redad']."'
	when '2' then lppe.idrangoedad='".$_POST['redad']."'
	when '3' then lppe.idrangoedad='".$_POST['redad']."'
	when '4' then lppe.idrangoedad='".$_POST['redad']."'
	else (lppe.idrangoedad >=1 or lppe.idrangoedad=NULL) end) AND"; }

		if (!empty($_POST['Fechaini']))
		{ 	$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
			$query .= " fechaini='".$Fechaini."' AND"; }

		if (!empty($_POST['Fechafin'])){
			$FechaF=explode('/',$_POST['Fechafin']);
	  		$Fechafin=$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0];
			$query .= " fechafin='".$Fechafin."' AND"; } 
		
		if((empty($_POST['idarea'])) and (empty($_POST['idexamen'])) and (empty($_POST['proce'])) and (empty($_POST['unidades'])) and (empty($_POST['rangoini'])) 
		and (empty($_POST['rangofin'])) and (empty($_POST['Fechafin'])) and (empty($_POST['Fechaini'])) and (empty($_POST['sexo'])) and (empty($_POST['redad'])))
		{
			$ban=1;
		}
		if ($ban==0)
		{   $query = substr($query ,0,strlen($query)-3); 
		 $query_search = $query. " ORDER BY lppe.id_conf_examen_estab";
		//echo $query_search;
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		//require_once("clsLab_Procedimientos.php");
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		//$obje=new clsLab_Procedimientos;
		///echo $query_search;
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar,$RegistrosAMostrar);

		//muestra los datos consultados en la tabla
	 echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
               <tr>
                    <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
                  <!--  <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
                 <!--  <td class='CobaltFieldCaptionTD'> IdExamen </td> -->
                    <td class='CobaltFieldCaptionTD'> Examen </td>
                    <td class='CobaltFieldCaptionTD'> Procedimiento </td>
                    <td class='CobaltFieldCaptionTD'> Unidades </td>	   
                    <td class='CobaltFieldCaptionTD'> Rangos </td>
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
			onclick=\"pedirDatos('".$row[0]."')\"> </td>
                   <!-- <td aling ='center'> 
			 <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			onclick=\"eliminarDato('".$row['id']."',$lugar)\"> </td> -->
                   <!--   <td>".$row[1]."oooo"."</td>  -->
                    <td>".htmlentities($row['nombre_examen'])."</td>
                    <td>".htmlentities($row['nombreprocedimiento'])."</td>
                    <td>".htmlentities($row['unidades'])."</td>
                    <td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
              echo "<td>".$row['abreviatura']."</td>
                    <td>".$row['nombre']."</td>";
	   if(($row['fechaini']=="(NULL)") || ($row['fechaini']=="00/00/0000") ||(empty($row['fechaini'])) )
              echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
	   else
	      echo "<td>".$row['fechaini']."</td>";
								
			if((empty($row['fechafin'])) || ($row['fechafin']=="(NULL)") || ($row['fechafin']=="00/00/0000")) 
		      echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else	
			  echo "<td>".$row['fechafin']."</td>
			          ";
		 echo"</tr>";
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
		
		}
		else {
			$query = substr($query ,0,strlen($query)-6);
			$query_search = $query. " ORDER BY lab_procedimientosporexamen.id_area_examen_establecimiento";
            		
		
		
		//echo $query_search;
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		//require_once("clsLab_Procedimientos.php");
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		//$obje=new clsLab_Procedimientos;
		
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
				echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
					   
					   <tr>
					   <td class='CobaltFieldCaptionTD' aling='center'> Modificar</td>
					 <!--  <td class='CobaltFieldCaptionTD'aling='center'> Eliminar</td> -->
					 <!--  <td class='CobaltFieldCaptionTD'> IdExamen</td> -->
					   <td class='CobaltFieldCaptionTD'> Examen </td>  
					   <td class='CobaltFieldCaptionTD'> Procedimiento </td>
					   <td class='CobaltFieldCaptionTD'> Unidades </td>	   
					   <td class='CobaltFieldCaptionTD'> Rangos </td>
                                           <td class='CobaltFieldCaptionTD'> Rangos </td>
                                           <td class='CobaltFieldCaptionTD'> Sexo</td>
					   <td class='CobaltFieldCaptionTD'> Fecha Inicio </td>	 
					   <td class='CobaltFieldCaptionTD'> Fecha Finalización </td>
					      
					   </tr>";
			   while($row = pg_fetch_array($consulta))
			   {
				     echo "<tr>
                                                <td aling='center'> 
                                                    <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                                    onclick=\"pedirDatos('".$row[0]."')\"> </td>
						<!-- <td aling ='center'> 
                                                    <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                                    onclick=\"eliminarDato('".$row['id']."',$lugar)\"> </td> -->
                                               <!-- <td>". $row['id']."uuuu"."</td> -->
						<td>".htmlentities($row['nombreexamen'])."</td>
						<td>".htmlentities($row['nombreprocedimiento'])."</td>
						<td>".htmlentities($row['unidades'])."</td>
						<td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
                                          echo "<td>".$row['abreviatura']."</td>
                                                <td>".$row['nombe']."</td>";
							
                                        if(($row['fechaini']=="(NULL)") || ($row['fechaini']=="00/00/0000") ||(empty($row['fechaini'])) )
					    echo"<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
					else
                                            echo"<td>".$row['FechaIni']." </td>";
								
					if((empty($row['fechafin'])) || ($row['fechafin']=="(NULL)") || ($row['fechafin']=="00/00/0000"))
					    echo"<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
					else	
                                            echo"<td>".$row['fechafin']."</td>";
				    echo"</tr>";
                            }
                            echo "</table>"; 
		//echo $query_search;
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
			   <a onclick=\"show_event_search(('1')\">Primero</a> </td>";
		//// desplazamiento

		 if($PagAct>1) 
			 echo "<td> <a onclick=\"show_event_search(('$PagAnt')\">Anterior</a> </td>";
		 if($PagAct<$PagUlt)  
			 echo "<td> <a onclick=\"show_event_search(('$PagSig')\">Siguiente</a> </td>";
			 if($PagUlt > 0)
				echo "<td> <a onclick=\"show_event_search(('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
			  </table>";
		
			  }
	  break;
	case 8://PAGINACION DE BUSQUEDA
		$idexamen=$_POST['idexamen'];
		$proce=$_POST['proce'];
		$idarea=$_POST['idarea'];
		$unidades=$_POST['unidades'];
                $sexo=$_POST['sexo'];
                $redad=$_POST['redad'];
                
		if (empty($_POST['rangoini'])){
			$rangoini="(NULL)";
		}
		else{
			$rangoini=$_POST['rangoini'];
		}
		
		if (empty($_POST['rangofin'])){
			$rangofin="(NULL)";
		}else{
			$rangofin=$_POST['rangofin'];
		}
              
                
		if (empty($_POST['Fechaini'])){
			$Fechaini="(NULL)";
		}else{ 
			$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
		}

		if (empty($_POST['Fechaini'])){
			$Fechafin="(NULL)";
		}else{ 
			$FechaF=explode('/',$_POST['Fechafin']);
			$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];	
		}

		 $query = "select  
                            lppe.id,mnt4.id, lcee.nombre_examen, 
                            lppe.nombreprocedimiento, unidades,rangoinicio,rangofin, 
                            (lppe.fechaini) AS fechaini, (lppe.fechafin)AS fechafin, 
                            cex.id,cex.abreviatura,cre.id,cre.nombre,lppe.idsexo
                            from mnt_area_examen_establecimiento mnt4
                            join lab_conf_examen_estab lcee on (mnt4.id=lcee.idexamen)
                            join lab_procedimientosporexamen lppe on (lppe.id_conf_examen_estab=lcee.id)
                            left JOIN ctl_sexo cex ON lppe.idsexo = cex.id 
                            left JOIN ctl_rango_edad  cre ON lppe.idrangoedad = cre.id 
                            where id_establecimiento=$lugar AND";
                 
               
                 
                // corregir!!
 
 
            $ban=0;

		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['idarea']))
		{ $query .= " (case '".$_POST['idarea']."'
                            when '0' then id_area_servicio_diagnostico >=0
                            else  (id_area_servicio_diagnostico='".$_POST['idarea']."')end)
                                 AND"; }
		
		if (!empty($_POST['idexamen']))
		{ $query .= " (case '".$_POST['idexamen']."'
                                    when '0' then lppe.id >=0
                                    else  (lppe.id='".$_POST['idexamen']."') end) 
                         AND"; }
                         
    if (!empty($_POST['proce']))
		{ $query .= " nombreprocedimiento ilike '%%".$_POST['proce']."%%' AND"; }
                
                
		
		if (!empty($_POST['unidades']))
		{ $query .= " unidades ilike '%%".$_POST['unidades']."%%' AND"; }
	
		if (!empty($_POST['rangoini']))
		{ $query .= " rangoinicio'%%".$_POST['rangoini']."%%' AND"; }
		
		if (!empty($_POST['rangofin']))
		{ $query .= " rangofin'%%".$_POST['rangofin']."%%' AND"; }
                
                if (!empty($_POST['idsexo']))
		{ $query .= "
                    (case '".$_POST['sexo']."'	
                        when '1' then lppe.idsexo='".$_POST['sexo']."'
                        when '2' then lppe.idsexo='".$_POST['sexo']."'
                        when '3' then lppe.idsexo='".$_POST['sexo']."'
	else (lppe.idsexo >=1 or lppe.idsexo=NULL) end)  AND"; }
                
                 if (!empty($_POST['redad']))
		{ $query .= " (case '".$_POST['redad']."'	
	when '1' then lppe.idrangoedad='".$_POST['redad']."'
	when '2' then lppe.idrangoedad='".$_POST['redad']."'
	when '3' then lppe.idrangoedad='".$_POST['redad']."'
	when '4' then lppe.idrangoedad='".$_POST['redad']."'
	else (lppe.idrangoedad >=1 or lppe.idrangoedad=NULL) end) AND"; }

		if (!empty($_POST['Fechaini']))
		{ 	$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
			$query .= " fechaini='".$Fechaini."' AND"; }

		if (!empty($_POST['Fechafin'])){
			$FechaF=explode('/',$_POST['Fechafin']);
	  		$Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
			$query .= " fechafin='".$Fechafin."' AND"; } 
		
	
		if((empty($_POST['idarea'])) and (empty($_POST['idexamen'])) and (empty($_POST['proce'])) 
                    and (empty($_POST['unidades'])) and (empty($_POST['rangoini'])) and (empty($_POST['rangofin'])) 
                    and (empty($_POST['Fechaini'])) and (empty($_POST['Fechafin'])) and (empty($_POST['sexo'])) 
                    and (empty($_POST['redad']))  )
				{
				$ban=1;
				}
	
		if ($ban==0)
		{   $query = substr($query ,0,strlen($query)-3); 
		    $query_search = $query. " ORDER BY lcee.id";
		}
		else {
			$query = substr($query ,0,strlen($query)-6);
			$query_search = $query. " ORDER BY lcee.id";
		}
           // echo $query_search;
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		//require_once("clsLab_Procedimientos.php");
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		//$obje=new clsLab_Procedimientos;
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
                      <tr>
                          <td class='CobaltFieldCaptionTD' aling='center'> Modificar</td>
			<!--  <td class='CobaltFieldCaptionTD' aling='center'> Eliminar</td> -->
			<!-- <td class='CobaltFieldCaptionTD'> IdExamen </td>  -->
			  <td class='CobaltFieldCaptionTD'> Examen </td>
			  <td class='CobaltFieldCaptionTD'> Procedimiento </td>
			  <td class='CobaltFieldCaptionTD'> Unidades </td>	   
			  <td class='CobaltFieldCaptionTD'> Rangos </td>
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
				onclick=\"pedirDatos('".$row[0]."')\"> </td>
			<!-- <td aling ='center'> 
				 <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"eliminarDato('".$row['id']."',$lugar)\"> </td> -->
			<!-- <td>". $row[0]."</td> -->
			<td>".htmlentities($row['nombre_examen'])."</td>
			<td>".htmlentities($row['nombreprocedimiento'])."</td>
			<td>".htmlentities($row['unidades'])."</td>
                        <td>".$row['rangoInicio']."-".$row['rangofin']."</td>";
                  echo "<td>".$row['abreviatura']."</td>
                        <td>".$row['nombre']."</td>";
                if(($row['fechaini']=="(NULL)") || ($row['fechaini']=="00/00/0000") || (empty($row['fechaini'])) )
                   echo"<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
                   echo"<td>".$row['fechaini']." </td>";
						
		if((empty($row['fechafin'])) || ($row['fechafin']=="(NULL)") || ($row['fechafin']=="00/00/0000"))
                  echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else	
                  echo "<td>".$row['fechafin']."</td>";
		echo"</tr>";
	}
	echo "</table>"; 
		//determinando el numero de paginas
		//echo $query_search;
		
		 $NroRegistros= $objdatos->NumeroDeRegistrosbus($query_search);
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;
		 
		 $PagUlt=$NroRegistros/$RegistrosAMostrar;
		 
		 //verificamos residuo para ver si llevar decimales
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