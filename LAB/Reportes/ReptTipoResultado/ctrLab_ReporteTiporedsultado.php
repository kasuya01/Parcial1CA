<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsReporteTiporedsultado.php");

//variables POST
$opcion=$_POST['opcion'];
//$pag =$_POST['pag'];

//creando los objetos de las clases
$obj = new clsReporteTiporedsultado;

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
            $cond1 .= " t07.id = " . $_POST['idarea'] . " AND";
            //$cond2 .= " t07.id = " . $_POST['idarea'] . " AND";
        }
                if (!empty($_POST['idexamen'])) {
             $cond1 .= " t05.id = " . $_POST['idexamen'] . " AND";
             //$cond2 .= " t05.id = " . $_POST['idexamen'] . " AND";
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
                        
                         $cadena=$cadena."SUM (CASE WHEN t02.id=$si THEN 1 else 0 END )AS codigo$si,";
		}
	
		/*for ($i=0;$i<$NroRegistros;$i++){
		    $cadena=$cadena."SUM (CASE WHEN t02.id=$arrayidareas[$i] THEN 1 else 0 END )AS codigo$i,";
                    
                    echo $i;
                  }*/
                  
                  $imprimir.="<table width='90%' border='1' align='center' >
                                <tr style='background:#BBBEC9'>
                                <td class='CobaltFieldCaptionTD' width='5%'><strong>Prueba</strong></td>";
		
                 $consulta=$obj->codigoresultado();
                while ($consulcodigo=pg_fetch_array($consulta))
                        {
                             $imprimir.="<td class='CobaltFieldCaptionTD' width='12%'><strong>".htmlentities($consulcodigo['resultado'])."</strong></td>";
			}
                            $imprimir.="<td class='CobaltFieldCaptionTD' width='12%'><strong>TOTAL</strong></td></tr>";
		
           //  echo $query1;
           // $query_search = 
            //echo $cond1;
            //echo $cond2;
        }     
       // echo $cond2;
		//print_r($arraynombres);	
		  echo $query ="SELECT $cadena
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
                      t07.nombrearea,t05.nombre_examen  " ;
                  
                  
                  
                
		
echo "<table width='35%' border='0'  align='center'>
        <center>
            <tr>
                <td colspan='11'><span style='color: #0101DF;'> <h2> RESULTADO DE PRUEBA DE LA BUSQUEDA </h2> </span>
                </td>
            </tr>
        </center>
     </table> "; 
                  
		$consulta=$obj->ListadoSolicitudesPorArea($query);  
	$NroRegistros= $obj->ContarNumeroDeRegistros($query);
       
   if(pg_num_rows($consulta))
    {     //echo "dentro";            
              
                $Nombrepdf="Rep_TipodeResultado".'_'.date('d_m_Y__h_i_s A');
	      	$nombrearchivo = "../../../Reportes1/".$Nombrepdf.".pdf";
		$nombrearchivo;
	       	$punteroarchivo = fopen($nombrearchivo, "w+") 	 or die("El archivo de reporte no pudo crearse");
                
                
                echo"   <table width='100%' hight='10%' align='center'>
         <tr>
	       		<td colspan='28' align='center'>
                            <a href='".$nombrearchivo."'><H5>DESCARGAR REPORTE  <img src='../../../Imagenes/icono-pdf.jpg'></H5></a>
                        </td>
	           </tr> 
                   
     </table>";  
                
                
                
                
                 fwrite($punteroarchivo,$imprimir);
	    fclose($punteroarchivo);
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
                                            $imprimir.="<td width='14%'><strong> <span style='color: #0101DF;'>".$row['total']."</strong></td>
                                </tr>";
	     }
                
                
    }else { 
             $imprimir .="<tr><td colspan='11'><span style='color: #575757;'>No se han encontrado reeeesultados...</span></td></tr></table>";
                //echo $imprimir;
          }  
           
           
$imprimir.="</table>";

//CIERRE DE ARCHIVO pdf
	    fwrite($punteroarchivo,$imprimir);
	    fclose($punteroarchivo);
		
	     

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

        $rslts = '<select name="cmbExamen" id="cmbExamen" class="ui-corner-all" style="width:200px">';
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