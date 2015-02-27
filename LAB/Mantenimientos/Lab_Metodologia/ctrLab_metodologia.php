<?php session_start();
include_once("clsLab_metodologia.php");
include('../Lab_Areas/clsLab_Areas.php');
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

//variables POST

$opcion=$_POST['opcion'];

$objdatos = new clsLab_metodologia();
$objeareas=new clsLab_Areas;
$Clases = new clsLabor_DatosFijosExamen;

switch ($opcion) 
{
	case 1:  //INSERTAR	
		
		$idexamen=$_POST['idexamen'];
		$idmetodologia=$_POST['idmetodologia'];
		$cmbreporta=$_POST['cmbreporta'];
		
                $Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
		$Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";
                $posresultados_sel=$_POST['posresultados_sel'];
                $text_posresultados_sel=$_POST['text_posresultados_sel'];
                $id_posresultados_sel=$_POST['id_posresultados_sel'];
                
        

		if ($objdatos->insertar($idexamen,$idmetodologia,$cmbreporta,$usuario,$lugar,$Fechaini,$Fechafin,$posresultados_sel,$text_posresultados_sel,$id_posresultados_sel)==true) 
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
			$iddatosfijosresultado=$_POST['iddatosfijosexamen'];
		        $redad=(empty($_POST['redad'])) ? 'NULL' : "'" . pg_escape_string($_POST['redad']) . "'";
                        $sexo=(empty($_POST['sexo'])) ? 'NULL' : "'" . pg_escape_string($_POST['sexo']) . "'";  
                        $unidades=(empty($_POST['unidades'])) ? 'NULL' : "'" . pg_escape_string($_POST['unidades']) . "'";
                        $rangoinicio=(empty($_POST['rangoinicio'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangoinicio']) . "'";
                        $rangofin=(empty($_POST['rangofin'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangofin']) . "'";
                        $nota=(empty($_POST['nota'])) ? 'NULL' : "'" . pg_escape_string($_POST['nota']) . "'";  
                        $Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
                        $Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";
                       // echo $sexo;
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
		$RegistrosAMostrar=10;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		  echo "<center >
               <table border = 1 style='width: 60%;'  class='table table-hover table-bordered table-condensed table-white'>
	           <thead>
                        <tr>
				<th aling='center' > Modificar</td>
				<!-- <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
                                <th > Cód. Estándar              </th>
				<th > Examen                </th>
				<th > Metodología              </th>	   
				<th title='Reporta resultado en metodología'> Reporta Resultado      </th>
                        </tr>
                    </thead><tbody>
                    </center>";
		while($row = pg_fetch_array($consulta)){
                     echo "<tr>
				<td aling='center'> 
                                    <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                    onclick=\"pedirDatos('".$row['id']."')\"> </td>
                                   
                                  
				<td>". $row['idestandar'] ."</td>
				<td>".htmlentities($row['nombre_examen'])."</td>";
			echo "<td>".$row['nombre_reporta']."</td>";	
                        
                        if (($row['b_reporta'])=='t')
                            echo "<td title='Reporta resultado de metodología'> Si  </td>";
                        else
                            echo "<td title='No Reporta resultado de metodología'>No </td>";
            echo "</tr>";
		}
                  
                        
	echo "</tbody></table>"; 
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
                         
                         
                                echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                                    {
                             
					 echo " <li ><a  href='javascript: show_event(".$i.")'>$i</a></li>";
                                     }
                    echo " </ul></center>";
        
	break;
        case 5:   //fn pg
		$idarea=$_POST['idarea'];
            $rslts='';
                if ($idarea==0){
                   $rslts .= '<select name="cmbExamen" id="cmbExamen" size="1" class="form-control height" style="width:75%" onchange="buscaranteriores();">';
                    $rslts .='<option value="0">--Seleccione un Examen--</option>';
                   
                   $rslts .= '</select>';
                }
                else{
                  
		$consultaex= $objdatos->ExamenesPorArea($idarea,$lugar);
		//$dtMed=$obj->LlenarSubServ($proce);	
		
		$rslts .= '<select name="cmbExamen" id="cmbExamen" size="1" class="form-control height" style="width:75%" onchange="buscaranteriores();">';
                if (pg_num_rows($consultaex)==0){
                   $rslts .='<option value="0">No tiene Examenes con metodologías asociadas</option>';
                }
                else{
                   $rslts .='<option value="0">--Seleccione un Examen--</option>';
			
		while ($rows =pg_fetch_array($consultaex)){
			$rslts.= '<option value="' .$rows[0].'" >'.htmlentities($rows[1]).'</option>';
		}
			
                }
			
		$rslts .= '</select>';
                }                
           	//echo "combo".$idarea; 
	  	
		echo $rslts;

	break;
	
	case 6: 
	   	  
	  break;
	case 7: //BUSQUEDA
		$idexamen=$_POST['idexamen'];
		$idarea=$_POST['idarea'];
		$idemetodologia=$_POST['idemetodologia'];
		$idreporta=$_POST['idreporta'];		              
                $Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
		
	  	
		$query = "SELECT lab_datosfijosresultado.id,
                                lab_conf_examen_estab.codigo_examen as idexamen,
                                lab_conf_examen_estab.nombre_examen as nombreexamen, 
                                lab_datosfijosresultado.unidades,
                                lab_datosfijosresultado.rangoinicio,
                                rangofin, lab_datosfijosresultado.nota, 
                                to_char(lab_datosfijosresultado.fechaini,'dd/mm/YYYY') AS FechaIni, 
                                to_char(lab_datosfijosresultado.fechafin,'dd/mm/YYYY') AS FechaFin, 
                                ctl_sexo.nombre as sexo,
                                ctl_rango_edad.nombre as redad,
                                CASE lab_datosfijosresultado.fechafin 
                                    WHEN lab_datosfijosresultado.fechafin THEN 'Inhabilitado'
                                    ELSE 'Habilitado' END AS habilitado,
                                    lab_datosfijosresultado.id as idatofijo
                         FROM lab_datosfijosresultado
                         INNER JOIN lab_conf_examen_estab           ON lab_datosfijosresultado.id_conf_examen_estab=lab_conf_examen_estab.id 
                         INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id 
                         INNER JOIN ctl_area_servicio_diagnostico   ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id 
                         INNER JOIN lab_areasxestablecimiento       ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea 
                         LEFT JOIN ctl_sexo                         ON lab_datosfijosresultado.idsexo = ctl_sexo.id 
                         INNER JOIN ctl_rango_edad                  ON lab_datosfijosresultado.idedad = ctl_rango_edad.id 
                         WHERE lab_conf_examen_estab.idplantilla=1 
                         AND lab_conf_examen_estab.condicion='H' 
                         AND lab_areasxestablecimiento.condicion='H' 
                         AND lab_datosfijosresultado.idestablecimiento=$lugar   ";
                        $ban=0;
                        //VERIFICANDO LOS POST ENVIADOS
                        if (!empty($_POST['idarea']))
                        { $query .= " AND  mnt_area_examen_establecimiento.id_area_servicio_diagnostico=".$_POST['idarea']."        "; }

                        if (!empty($_POST['idexamen']))
                        { $query .= " AND lab_conf_examen_estab.id=".$_POST['idexamen']."  "; }

                       if (!empty($_POST['unidades']))
                        { $query .= " AND unidades='".$_POST['unidades']."'     "; }

                        if (!empty($_POST['rangoinicio']))
                        { $query .= "AND  rangoinicio='".$_POST['rangoinicio']."'    "; }

                        if (!empty($_POST['rangofin']))
                        { $query .= " AND  rangofin='".$_POST['rangofin']."'     "; }
                        
                         if (!empty($_POST['nota']))
                        { $query .= " AND nota ILIKE '%".$_POST['nota']."%'     "; }
                        
                      /*  if (!empty($_POST['sexo'])){
                            if ($_POST['sexo']<>3)
                              $query .= " AND ctl_sexo.id=".$_POST['sexo']."  ";
                        }
                        else
                        { $query .= " AND ctl_sexo.id is null   "; }*/
                        
                        if ( !empty( $_POST['sexo'] ) && $_POST['sexo'] !== '0' ) 
                            {
                              //  $query .= "  AND CASE WHEN ctl_sexo.id IS NULL THEN 'NULL' ELSE ctl_sexo.id::text END = '".$_POST['sexo']."'   ";
                        
                              if ( $_POST['sexo']==3){
                    
                                            $query .= "AND ((ctl_sexo.id IS NULL) or (ctl_sexo.id=".$_POST['sexo']."))        ";
                             } else{
                                    $query .="AND ctl_sexo.id=".$_POST['sexo']."         ";
                    
                                    }
                            }
                        
                        
                       
                        if (!empty($_POST['redad']))
                        { $query .= " AND ctl_rango_edad.id=".$_POST['redad']."      "; }

                        if (!empty($_POST['Fechaini']))
                        { 	/*$FechaI=explode('/',$_POST['Fechaini']);
                                $Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];*/
                            
                                $query .= " AND  lab_datosfijosresultado.fechaini='".$_POST['Fechaini']."'      "; }

                        if (!empty($_POST['Fechafin'])){
                                /*$FechaF=explode('/',$_POST['Fechafin']);
                                $Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];*/
                                $query .= " AND  fechafin='".$_POST['Fechafin']."'   "; } 

                       

                        if((empty($_POST['idexamen'])) and (empty($_POST['idarea'])) and (empty($_POST['unidades'])) and 
                                (empty($_POST['rangoinicio'])) and (empty($_POST['rangofin'])) and (empty($_POST['nota'])) and 
                                (empty($_POST['Fechafin'])) and (empty($_POST['Fechaini'])) and (empty($_POST['sexo']))
                                and (empty($_POST['redad'])))
                        {
                                $ban=1;
                        }
                        if ($ban==0)
                        {   $query = substr($query ,0,strlen($query)-3); 
                            $query_search = $query. "  ORDER BY mnt_area_examen_establecimiento.id_area_servicio_diagnostico,lab_conf_examen_estab.id";
                        }
				
		// echo $query_search;
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		//para manejo de la paginacion
		$RegistrosAMostrar=10;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		//$obje=new clsLab_DatosFijosExamen;
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
			 echo "<center >
               <table border = 1 style='width: 80%;'  class='table table-hover table-bordered table-condensed table-white'>
	           <thead>
                        <tr>
				<th aling='center' > Modificar</td>
				<!-- <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
				<th > Habilitado              </th>
                                <th > IdExamen              </th>
				<th > Examen                </th>
				<th > Unidades              </th>	   
				<th > Valores Normales      </th>
				<th > Observacion           </th>
                                <th > Sexo                  </th>
                                <th > Rango de Edad         </th>
				<th > Fecha Inicio          </th>	 
				<th > Fecha Finalización    </th>
                        </tr>
                    </thead><tbody>
                    </center>";
				while($row = @pg_fetch_array($consulta)){
                    $idatofijo=$row['idatofijo'];
                    $habilitado= $row['habilitado'];
                   
                  if ($habilitado=='Habilitado'){
                      
                      echo "<tr>
				<td aling='center'> 
                                    <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                    onclick=\"pedirDatos('".$row['id']."')\"> </td>
				<!--<td aling ='center'> 
                                            <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                            onclick=\"eliminarDato('".$row['id']."')\"> 
                                   </td> -->
                                   
                                    <td width='6%'><span style='color: #0101DF;'>
                   	 <a style ='text-decoration:underline;cursor:pointer;' onclick='Estado(\"".$row['idatofijo']."\",\"".$row['habilitado']."\")'>".$row['habilitado']."</a></td>
                                   
                                  
				<td>". $row['codigo_examen'] ."</td>
				<td>".htmlentities($row['nombre_examen'])."</td>";
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
			//echo $row[7];
			if((empty($row[7])) || ($row[7]=="NULL") || ($row[7]=="00-00-0000"))
				     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
				else
					 echo"<td>".$row[7]."</td>";
			if((empty($row[8])) || ($row[8]=="(NULL)") || ($row[8]=="00/00/0000"))
			     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
			else
					echo"<td>".$row[8]."</td></tr>";
            echo "</tr>";
                      
                      
                  }ELSE{
                    
		  echo "<tr>
				<td aling='center'> 
                                    <img src='../../../Imagenes/Search.png' style=\"text-decoration:underline;cursor:pointer;\" 
                                    onclick=\"pedirDatos('".$row['id']."')\"height='40' width='50'> </td>
				<!--<td aling ='center'> 
                                            <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                            onclick=\"eliminarDato('".$row['id']."')\"> 
                                   </td> -->
                                   <td>". $row['habilitado'] ."</td>
				<td>". $row['codigo_examen'] ."</td>
				<td>".htmlentities($row['nombre_examen'])."</td>";
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
			//echo $row[7];
			if((empty($row[7])) || ($row[7]=="NULL") || ($row[7]=="00-00-0000"))
				     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
				else
					 echo"<td>".$row[7]."</td>";
			if((empty($row[8])) || ($row[8]=="(NULL)") || ($row[8]=="00/00/0000"))
			     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
			else
					echo"<td>".$row[8]."</td></tr>";
            echo "</tr>";
                  }
                                    
                                    
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
                         
                            echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                                    {
                             
					 echo " <li ><a  href='javascript: show_event_search(".$i.")'>$i</a></li>";
                                     }
                    echo " </ul></center>";
	  break;
	case 8://PAGINACION DE BUSQUEDA
		$idexamen=$_POST['idexamen'];
		$idarea=$_POST['idarea'];
		$unidades=(empty($_POST['unidades'])) ? 'NULL' : "'" . pg_escape_string($_POST['unidades']) . "'"; 
                $nota=(empty($_POST['nota'])) ? 'NULL' : "'" . pg_escape_string($_POST['nota']) . "'";  
                $sexo=(empty($_POST['sexo'])) ? 'NULL' : "'" . pg_escape_string($_POST['sexo']) . "'";        
                $redad=(empty($_POST['redad'])) ? 'NULL' : "'" . pg_escape_string($_POST['redad']) . "'"; 
                $rangoinicio=(empty($_POST['rangoinicio'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangoinicio']) . "'";
                $rangofin=(empty($_POST['rangofin'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangofin']) . "'";
                $Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
		$Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";
		

		$query = "SELECT lab_datosfijosresultado.id,
                        lab_conf_examen_estab.codigo_examen as idexamen,
                        lab_conf_examen_estab.nombre_examen as nombreexamen, 
                        lab_datosfijosresultado.unidades,
                        lab_datosfijosresultado.rangoinicio,
                        rangofin, lab_datosfijosresultado.nota, 
                        to_char(lab_datosfijosresultado.fechaini,'dd/mm/YYYY') AS FechaIni, 
                        to_char(lab_datosfijosresultado.fechafin,'dd/mm/YYYY') AS FechaFin, 
                        ctl_sexo.nombre as sexo,
                        ctl_rango_edad.nombre as redad,
                        CASE lab_datosfijosresultado.fechafin 
                            WHEN lab_datosfijosresultado.fechafin THEN 'Inhabilitado'
                            ELSE 'Habilitado' END AS habilitado,
                        lab_datosfijosresultado.id as idatofijo
                            FROM lab_datosfijosresultado
                            INNER JOIN lab_conf_examen_estab           ON lab_datosfijosresultado.id_conf_examen_estab=lab_conf_examen_estab.id 
                            INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id 
                            INNER JOIN ctl_area_servicio_diagnostico   ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id 
                            INNER JOIN lab_areasxestablecimiento       ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea 
                            LEFT JOIN ctl_sexo                         ON lab_datosfijosresultado.idsexo = ctl_sexo.id 
                            INNER JOIN ctl_rango_edad                  ON lab_datosfijosresultado.idedad = ctl_rango_edad.id 
                         WHERE lab_conf_examen_estab.idplantilla=1 
                         AND lab_conf_examen_estab.condicion='H' 
                         AND lab_areasxestablecimiento.condicion='H' 
                         AND lab_datosfijosresultado.idestablecimiento=$lugar  and  ";
		$ban=0;
		
		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['idarea']))
		{ $query .= " mnt_area_examen_establecimiento.id_area_servicio_diagnostico='".$_POST['idarea']."' AND"; }
		//else{$ban=1;}
		
		if (!empty($_POST['idexamen']))
		{ $query .= " lab_conf_examen_estab.id='".$_POST['idexamen']."' AND"; }
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
                        
                        
                        
                        
             /*  if (!empty($_POST['sexo'])){
                    if ($_POST['sexo']<>3)
                        $query .= " ctl_sexo.id=".$_POST['sexo']." AND";
               }
               else
                 { $query .= " ctl_sexo.id is null AND"; }*/
                 
                 
                 
                 
                 
                        
              /*  if (!empty($_POST['redad']))
                {   $query .= " ctl_rangoedad.id='".$_POST['redad']."' AND"; }*/
                        
                        
                         if ( !empty( $_POST['sexo'] ) && $_POST['sexo'] !== '0' ) 
                            {
                              //  $query .= "  AND CASE WHEN ctl_sexo.id IS NULL THEN 'NULL' ELSE ctl_sexo.id::text END = '".$_POST['sexo']."'   ";
                        
                              if ( $_POST['sexo']==3){
                    
                                            $query .= " ((ctl_sexo.id IS NULL) or (ctl_sexo.id=".$_POST['sexo']."))        ";
                             } else{
                                    $query .=" ctl_sexo.id=".$_POST['sexo']."         ";
                    
                                    }
                            }
                        
                        
                       
                        if (!empty($_POST['redad']))
                        { $query .= "  ctl_rango_edad.id=".$_POST['redad']."      "; }

                        
                
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
	    $query_search = $query. " ORDER BY mnt_area_examen_establecimiento.id_area_servicio_diagnostico,lab_conf_examen_estab.id";
	}
      //echo $query_search;
	
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		//para manejo de la paginacion
		$RegistrosAMostrar=10;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 //LAMANDO LA FUNCION DE LA CLASE 
		//$obje=new clsLab_DatosFijosExamen;
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		  echo "<center >
               <table border = 1 style='width: 80%;'  class='table table-hover table-bordered table-condensed table-white'>
	           <thead>
                        <tr>
				<th aling='center' > Modificar</td>
				<!-- <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
				<th > Habilitado              </th>
                                <th > IdExamen              </th>
				<th > Examen                </th>
				<th > Unidades              </th>	   
				<th > Valores Normales      </th>
				<th > Observacion           </th>
                                <th > Sexo                  </th>
                                <th > Rango de Edad         </th>
				<th > Fecha Inicio          </th>	 
				<th > Fecha Finalización    </th>
                        </tr>
                    </thead><tbody>
                    </center>";
		while($row = @pg_fetch_array($consulta)){
		   $idatofijo=$row['idatofijo'];
                    $habilitado= $row['habilitado'];
                   
                  if ($habilitado=='Habilitado'){
                      
                      echo "<tr>
				<td aling='center'> 
                                    <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                    onclick=\"pedirDatos('".$row['id']."')\"> </td>
				<!--<td aling ='center'> 
                                            <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                            onclick=\"eliminarDato('".$row['id']."')\"> 
                                   </td> -->
                                   
                                    <td width='6%'><span style='color: #0101DF;'>
                   	 <a style ='text-decoration:underline;cursor:pointer;' onclick='Estado(\"".$row['idatofijo']."\",\"".$row['habilitado']."\")'>".$row['habilitado']."</a></td>
                                   
                                  
				<td>". $row['codigo_examen'] ."</td>
				<td>".htmlentities($row['nombre_examen'])."</td>";
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
			//echo $row[7];
			if((empty($row[7])) || ($row[7]=="NULL") || ($row[7]=="00-00-0000"))
				     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
				else
					 echo"<td>".$row[7]."</td>";
			if((empty($row[8])) || ($row[8]=="(NULL)") || ($row[8]=="00/00/0000"))
			     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
			else
					echo"<td>".$row[8]."</td></tr>";
            echo "</tr>";
                      
                      
                  }ELSE{
                    
		  echo "<tr>
				<td aling='center'> 
                                    <img src='../../../Imagenes/Search.png' style=\"text-decoration:underline;cursor:pointer;\" 
                                    onclick=\"pedirDatos('".$row['id']."')\"height='40' width='50'> </td>
				<!--<td aling ='center'> 
                                            <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
                                            onclick=\"eliminarDato('".$row['id']."')\"> 
                                   </td> -->
                                   <td>". $row['habilitado'] ."</td>
				<td>". $row['codigo_examen'] ."</td>
				<td>".htmlentities($row['nombre_examen'])."</td>";
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
			//echo $row[7];
			if((empty($row[7])) || ($row[7]=="NULL") || ($row[7]=="00-00-0000"))
				     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
				else
					 echo"<td>".$row[7]."</td>";
			if((empty($row[8])) || ($row[8]=="(NULL)") || ($row[8]=="00/00/0000"))
			     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
			else
					echo"<td>".$row[8]."</td></tr>";
            echo "</tr>";
                  }
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
                 
                   echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                                    {
                             
					 echo " <li ><a  href='javascript: show_event_search(".$i.")'>$i</a></li>";
                                     }
                    echo " </ul></center>";
		
		//echo $query_search;
	   
	break;
         
        case 9:  //habilitado
             
                
		$idatofijo=$_POST['idatofijo'];
                //$fechafinhabilitado="NULL";
             //	echo $idexamen."-".$condicion;
		//$resultado=Estado::EstadoCuenta($idexamen,$cond,$lugar);
		if($objdatos->Estadohabilitado($idatofijo,$usuario)==true )
                {
                   // echo "cambio";
                }else{
                   // echo "no cambio";
                }
	break;
        
        case 10: //fn pg
           $idexamen=$_POST['idexamen'];
           $rslts='';
           if ($idexamen==0){
               $rslts .= '<select name="cmbMetodologia" id="cmbMetodologia" size="1" class="form-control height" style="width:75%"  onchange="buscareporta();">';
                $rslts .='<option value="0">--Seleccione una Metodología--</option>';	
                $rslts .= '</select>';
           }
           else{
              $consultaex=$objdatos->buscarmetodologiasxexa($idexamen, $lugar);
           $rslts .= '<select name="cmbMetodologia" id="cmbMetodologia" size="1" class="form-control height" style="width:75%"  onchange="buscareporta();">';
           if (pg_num_rows($consultaex)==1){
              $rows =pg_fetch_array($consultaex);
              $rslts.= '<option value="' .$rows['id'].'" >'.htmlentities($rows['nombre_reporta']).'</option>';
           }   
           else{
              $rslts .='<option value="0">--Seleccione una Metodología--</option>';	
		while ($rows =pg_fetch_array($consultaex)){
			$rslts.= '<option value="' .$rows['id'].'" >'.htmlentities($rows['nombre_reporta']).'</option>';
		}
           }
           
				
		$rslts .= '</select>';
           }
           
		echo $rslts;
             
        break;
        
        case 11://fn pg
           $idexamen=$_POST['idexamen'];
           $idmetodologia=$_POST['idmetodologia'];
           $rslts='';
           if ($idmetodologia==0){
              $rslts .= '<select name="cmbreporta" id="cmbreporta" size="1" class="form-control height" style="width:75%">';
              $rslts.= '<option value="0" >--Seleccione una opción--</option>';
//              $rslts.= '<option value="true" >Si</option>';
//              $rslts.= '<option value="false" >No</option>';
              $rslts .= '</select>';
           }
           else{
              $consultaex=$objdatos->buscardatosmetodologia($idexamen, $idmetodologia, $lugar);
            $rows =pg_fetch_array($consultaex);
            $b_reporta=$rows['b_reporta'];
           $rslts .= '<select name="cmbreporta" id="cmbreporta" size="1" class="form-control height" style="width:75%">';
           if ($b_reporta=='t'){
              $rslts.= '<option value="true" selected>Si</option>';
              $rslts.= '<option value="false" >No</option>';
            }   
           else{
              $rslts .='<option value="true" >Si</option>';	
              $rslts .='<option value="false" selected>No</option>';               }		
		$rslts .= '</select>';
           }
           
		echo $rslts;
             
        break;
        
        case 12://fn pg
           $idexamen=$_POST['idexamen'];
           $idmetodo=$_POST['idmetodologia'];
           $rslts='';
           $idmetodologia="";
           $textmetodologia="";
           $idposresultmetodologia="";
           
           $consultaex=$objdatos->buscarposresultmet($idmetodo);
           $cuantos=pg_num_rows($consultaex);
           if ($cuantos>0){
              while ($row= pg_fetch_array($consultaex)){
                 $idmetodologia.=$row['id_posible_resultado'].",";
                 $textmetodologia.=$row['posible_resultado'].",";
                 $idposresultmetodologia.=$row['id_codigoresultado'].",";
              }
              
              $rslts .= ' <input type="hidden" name="posresultados_sel" id="posresultados_sel" value="'.$idmetodologia.'">
           <input type="hidden" name="text_posresultados_sel" id="text_posresultados_sel" value="'.$textmetodologia.'">
                            <input type="hidden" name="id_posresultados_sel" id="id_posresultados_sel" value="'.$idposresultmetodologia.'">';
           
           }
           else{
               $rslts .= ' <input type="hidden" name="posresultados_sel" id="posresultados_sel" value="">
           <input type="hidden" name="text_posresultados_sel" id="text_posresultados_sel" value="">
                            <input type="hidden" name="id_posresultados_sel" id="id_posresultados_sel" value="">';
           }
              //$rslts='0';
                    
		echo $rslts;
             
        break;
}

?>