<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsReporteTiporesultado.php");

//variables POST
$opcion=$_POST['opcion'];
//$pag =$_POST['pag'];

//creando los objetos de las clases
$obj = new clsReporteTiporesultado;

switch ($opcion) 
{   
    
    
    
	case 1: 
                $idarea     =$_POST['idarea'];
                $idexamen   =$_POST['idexamen'];
  		$procedencia=$_POST['procedencia'];
		$fechainicio=$_POST['fechainicio'];
		$fechafin   =$_POST['fechafin'];
		$subservicio=$_POST['subservicio'];
		$ffechaini  =$fechainicio." 00:00:00";
		$ffechafin  =$fechafin." 23:59:59";
		
		$cadena="";
                $cond1="";
                //$cond2="";
                $query="";
                $cond0="and";
                
                
                $ban=0;
		//VERIFICANDO LOS POST ENVIADOS
                
                 if (!empty($_POST['idarea'])) {
            $cond1 .= " t05.id = " . $_POST['idarea'] . " AND";
            //$cond1 .= " t05.id = " . $_POST['idarea'] . " AND";
        }
                if (!empty($_POST['idexamen'])) {
             $cond1 .= " t03.id = " . $_POST['idexamen'] . " AND";
             //$cond1 .= " t03.id = " . $_POST['idexamen'] . " AND";
        }
                
                
		if ((!empty($_POST['fechainicio'])) and (!empty($_POST['fechafin'])))
		{ $cond1.= "   (t01.fecha_resultado >='".$ffechaini."' AND t01.fecha_resultado <='".$ffechafin."')         ";
                 // $cond2.= " AND  (t02 .fechahorareg >='".$ffechaini."' AND t02 .fechahorareg <='".$ffechafin."')    AND     ";
                }
			
		if (!empty($_POST['procedencia']))
		{ $cond1 .=" id_area_atencion='".$_POST['procedencia']."' AND";
                 // $cond2 .= " id_area_atencion='".$_POST['procedencia']."' AND";
                }
		
		if (!empty($_POST['subservicio']))
		{ $cond1 .= " t10.id='".$_POST['subservicio']."' AND";
                  //$cond2 .=" t10.id='".$_POST['subservicio']."' AND";
                }
				
		if((empty($_POST['procedencia'])) and (empty($_POST['subservicio'])) and (empty($_POST['fechainicio'])) and (empty($_POST['fechafin'])))
		{
			$ban=1;
		}
	
              
                    if ($ban == 0) {

            $cond1 = substr($cond1, 0, strlen($query) - 3);
            $cond2 = substr($cond2, 0, strlen($query) - 3);
            
            $consulta=$obj->codigoresultado();
	    $NroRegistros= $obj->NumeroDeRegistros();
		while ($rowareas=pg_fetch_array($consulta)){
                     $si=$rowareas['id'];
                    
                        
			//$arrayidareas[$j]=$rowareas['id'];
                       // $arrayareas[$j]=$rowareas[1];
			//$arraynombres[$j]="codigo".$j;
			//$j++;
                        
                         $cadena=$cadena."count (CASE WHEN t02.id=$si THEN '$si'  else null END )AS codigo$si,";
                         
            //count (CASE WHEN t02.id=1 THEN 'uno' else null END )AS codigo1,
		}
	
		/*for ($i=0;$i<$NroRegistros;$i++){
		    $cadena=$cadena."SUM (CASE WHEN t02.id=$arrayidareas[$i] THEN 1 else 0 END )AS codigo$i,";
                    
                    echo $i;
                  }*/
           
                
                
                  $imprimir.=" 
                      <table width='90%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
                            <thead>  
                                  <tr>
                                <th > <strong>Prueba</strong></th>";
		
                 $consulta=$obj->codigoresultado();
                while ($consulcodigo=pg_fetch_array($consulta))
                        {
                             $imprimir.="<th ><strong>".htmlentities($consulcodigo['resultado'])."</strong></th>";
			}
                            $imprimir.="<th> <strong>TOTAL</strong></th>
                                   </tr></thead><tbody>";
		
           //  echo $query1;
           // $query_search = 
            //
            //echo $cond1;
            //echo $cond2;
        }     
       // echo $cond2;
		//print_r($arraynombres);	
		  $query =/*"SELECT $cadena
                      SUM (CASE WHEN t02.id<>0 THEN 1 else 0 END )AS total,
                      t02.id, 
                      t02.resultado, 
                      t07.nombrearea,
                      t05.nombre_examen as nombre_examen
                      FROM lab_resultado_metodologia    t01
            JOIN lab_codigosresultados  		t02 ON (t02.id=t01.id_codigoresultado)
            JOIN lab_codigosxexamen			t03 ON (t03.idresultado=t02.id)
            JOIN lab_examen_metodologia 		t04 ON (t04.id=t01.id_examen_metodologia)
            JOIN lab_conf_examen_estab 			t05 ON (t05.id=t04.id_conf_exa_estab)
            INNER JOIN mnt_area_examen_establecimiento  t06 ON (t06.id = t05.idexamen) 
            JOIN ctl_area_servicio_diagnostico    	t07 ON (t07.id = t06.id_area_servicio_diagnostico)
            where $cond1   
                          GROUP BY  t02.id, 
                      t02.resultado, 
                      t07.nombrearea,t05.nombre_examen  " ;*/
                          
                "SELECT $cadena
                id_examen_metodologia,  
                t03.nombre_examen,
                t05.nombrearea,
                count(*) as total 
                from lab_resultado_metodologia          t01 
                JOIN lab_codigosresultados              t02 ON (t02.id = t01.id_codigoresultado)
                join lab_conf_examen_estab              t03 on (t03.id = t01.id_examen_metodologia)
                JOIN mnt_area_examen_establecimiento    t04 ON (t04.id = t03.idexamen) 
                JOIN ctl_area_servicio_diagnostico      t05 ON (t05.id = t04.id_area_servicio_diagnostico) 
                where $cond1 

                group by t01.id_examen_metodologia, t03.nombre_examen,t05.nombrearea
                order by t03.nombre_examen ";
                  
                  
           $Nombrepdf="TipodeResultado".'_'.date('d_m_Y');
	      	$nombrearchivo = "../../../Reportes/".$Nombrepdf.".pdf";
                	
                $nombrearchivoe = "../../../Reportes/".$Nombrepdf.".ods";
               // $idarea=1;
                //$idexamen=32;
                $archivopdf= "../../../Reportes/".$Nombrepdf.".pdf?idarea=".$idarea."&idexamen=".$idexamen."&fechaini=".$fechainicio."&fechafin=".$fechafin;
		

                //$nombrearchivo;
	        //$punteroarchivo = fopen($nombrearchivo, "w+") 	 or die("El archivo de reporte no pudo crearse");
                //  $punteroarchivo1 = fopen($nombrearchivoe, "w+") 	 or die("El archivo de reporte no pudo crearse");  
                
		
 /* echo "<table width='85%' border='0'  align='center'>
         <tr>
                <td colspan='7' align='center'> <span style='color: #0101DF;'> <h3> RESULTADO DE PRUEBA DE LA BUSQUEDA </h3> </span>
                </td>
            </tr>
        
     </table> "; */

echo "<table width='85%' border='0' align='center'>
			<tr>
				<td colspan='7' align='center' ><span style='color: #0101DF;'><h4><strong> RESULTADO DE PRUEBAS DE LA BÚSQUEDA   </strong></h4></span></td>
			</tr>
		</table> ";
       // echo $imprimir;


                  
	$consulta=$obj->ListadoSolicitudesPorArea($query);  
	$NroRegistros= $obj->ContarNumeroDeRegistros($query);
       
   if(pg_num_rows($consulta))
    {     //echo "dentro";            
              
       
       
       
//    echo '<h1>Resultado de Pruebas por Sexo del Paciente  &emsp;&emsp;&emsp; <a href="/url/preanalitica/solicitud/exporta/pdfOrd_x_genero.php?id_sibasi='.$id_sibasi.'&id_establecimiento='.$id_establecimiento.'&fecha1='.$fecha1.'&fecha2='.$fecha2.'&id_grupoprueba='.$id_grupoprueba.'&id_prueba='.$id_prueba.'&id_sexo='.$id_sexo.'&title=\'PDF\'" target="_blank"><img align="center" src="/default/img/icons/pdf2.png" title="Exportar a PDF" alt="Exportar a PDF" style="padding-right:5px"></a></h1><br/>'; 
    echo '<table width="100%" border="0" height="10%" align="center " >
                <tr>
                      <td width="1000">  </td>  <td colspan="28"   >
                                <a href="pdfOrd_x_tiporesultado.php?idarea='.$idarea.'&idexamen='.$idexamen.'&fechaini='.$fechainicio.'&fechafin='.$fechafin.'&title=\'PDF\'" target="_blank"><h5></h5><img src="../../../Imagenes/icono-pdf.jpg" height="60" width="60" /></a> 
                         </td>
                </tr>
          </table>';
//      echo '<table width="100%" height="10%" align="center">'
//    . '<tr><td colspan="28" align="center">'
//            . '<a href="pdfOrd_x_tiporesultado.php?title=\'PDF\'" target="_blank"><h5>Descargar Reporte PDF </h5><img src="../../../Imagenes/icono-pdf.jpg"/></a> '
//            . '</td></tr></table>';
//      
    //  echo '<hr>';
    //  echo '<hr>';
      

//                echo"   <table  width='100%' hight='10%' align='center'>
//         <tr>
//	       		<td colspan='28' align='center'>
//                            <a href='../../../Reportes/".$Nombrepdf.".pdf?idarea=".$idarea."&idexamen=".$idexamen."&fechaini=".$fechainicio."&fechafin=".$fechafin target=blank'><H5>DESCARGAR REPORTE PDF <img src='../../../Imagenes/icono-pdf.jpg'></H5></a>
//                                <a href='".$nombrearchivoe."'><H5>DESCARGAR REPORTE   </H5></a>
//        </td>
//	           </tr> 
//                   
//     </table>";  
                
                
                
                
                // fwrite($punteroarchivo,$imprimir);
	    //fclose($punteroarchivo);
		//************************/ 
	     // echo $imprimir;
            while ($row = pg_fetch_array($consulta))
            {
                   $ser=$row['subservicio'];
                 // echo "nada". $a1=$row['area1'];
                 // echo  $ser;
                   
                     $imprimir.="<tr>
                                                <td width='15%'>".$row['nombre_examen']."</td>";
                                        $consulta1=$obj->codigoresultado();
		while ($rowareas=pg_fetch_array($consulta1))
                    {
                        $si=$rowareas['id'];
                        $codi='codigo'.$si;
                        $imprimir.="<td width='10%'>".$row[$codi]."</td>";
                    }
                                            $imprimir.="<td width='14%'><strong> <span style='color: ##0101DF ;'>".$row['total']."</strong></td>
                                </tr>";
	     }
                
                
    }else { 
             $imprimir .="<tr><td colspan='11'><span style='color: #575757;'>No se han encontrado reeeesultados...</span></td></tr></table>";
                //echo $imprimir;
          }  
           
           
$imprimir.="</table>";
$imprimir.="<br>";
$imprimir.="<br>";
//CIERRE DE ARCHIVO pdf
	   /* fwrite($punteroarchivo,$imprimir);
	    fclose($punteroarchivo);
		
	       fwrite($punteroarchivo1,$imprimir);
	    fclose($punteroarchivo1);*/
		

echo $imprimir;


        break;
    
	
	  
case 3://LLENANDO COMBO subservicio
                  
		$rslts='';
		$proce=$_POST['proce'];
		//echo $proce;
		$dtMed=$obj->LlenarSubServ($proce,$lugar);	
		
		$rslts = '<select name="cboMedicos" id="cmbSubServicio" class="MailboxSelect" style="width:250px">';
		$rslts .='<option value="0">--Seleccione un Servicio--</option>';
			
		while ($rows =pg_fetch_array($dtMed)){
			$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;
	
	break;	
        case 4:// Vista Previa Reporte 
		//echo $medico."+".$IdSubEsp."+".$especialidad."-".$fechainicio."-".$fechafin;
   
        break;
        case 5:  //LLENAR COMBO DE EXAMENES  
        $rslts = '';
 
        $idarea = $_POST['idarea'];
       //echo $idarea;
        //$dtExam = $objdatos->ExamenesPorArea($idarea, $lugar);
        
        $dtMed=$obj->ExamenesPorArea($idarea, $lugar);

        $rslts = '<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:196px">';
        $rslts .='<option value="0">--Seleccione Examen--</option>';
        while ($rows =pg_fetch_array($dtMed)){
			$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;
        
        
        

        break;
        
//	echo $imprimir;


///////////////////////////////////////////////////////////////// Archivo
 //$Nombre=$NEC."_".$Solicitud."_".$Fecha;
	//		 $nombrearchivo = './../../../Solicitudes/'.$Nombre.'.pet';
		//	// "../ReportesExcel/".$NombreExcel.".xls";
	 //$punteroarchivo = fopen($nombrearchivo, "w+") or die.("El archivo de reporte no pudo crearse");
 }
 ?>

 