<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsReporteDemanda.php");
//creando los objetos de las clases
$obj = new clsReporteDemanda();
//variables POST
$opcion=$_POST['opcion'];


switch ($opcion) 
{
   case 1:// muestra la solicitud
      // include_once("clsImprimirResultado.php");
      //recuperando los valores generales de la solicitud
        //$fechanewcitasol = isset($_POST['fechanewcitasol']) ? $_POST['fechanewcitasol']
     // $fechanewcitasol=(empty($_POST['fechanewcitasol'])) ? 'NULL' : "'" . pg_escape_string(trim($_POST['fechanewcitasol'])) . "'";
      $fecha=1;
      $area=1;
      $examen=1;
      $idarea = $_POST['idarea'];
      if ($idarea==""){
      $area=0;
      $idarea=0;
      }
      $idexamen = $_POST['idexamen'];
//      $exp=(explode(',',$idexamen));
//      
//            $rslt.="<p>Examen=". $_POST['idexamen']." --explode=".$exp[1]."</p>";

      $examenes = count(array_filter(explode(',',$idexamen)));
      //echo 'examenes: '.$examenes;
      if ($examenes==0){
         $examen=0;
         $idexamen=0;
      }
      $d_fechadesde = $_POST['d_fechadesde'];
      if ($d_fechadesde==""){
         $fecha=0;
         $femu=@pg_fetch_array($obj->fechamuestra());
        /// $feact=;
         $d_fechadesde=$femu[0];
      }
      $d_fechahasta = $_POST['d_fechahasta'];
      if ($d_fechahasta==""){
       //  $toy2 = date('Y-m-d');
         $d_fechahasta=date('Y-m-d');
      }
//Aqui debo de llamar la consulta donde muestre de acuerdo a los datos ingresasod
      $ban=0;
      
      $rslt.='   <div class="panel panel-default">
               <div class="panel-body">

                       <h3>Reportes de demanda insatisfecha desde '.$d_fechadesde.' hasta '.$d_fechahasta.'
                          <div class="pull-right mouse-pointer" id="expand-compress-btn" data-toggle="tooltip" data-placement="top" title="" data-original-title="Expandir/Contraer resultados"><span class="fa fa-expand" style="padding-right:15px;"></span></div></h3>
                       <div class="panel-group" id="accordion">';
      
      //Mostrar el total por tipo de rechazo
      $resultgetExamnResult =$obj->getTotalEstado($area, $idarea, $examen, $idexamen, $fecha, $d_fechadesde, $d_fechahasta);
      if (pg_num_rows($resultgetExamnResult)>=1){
          $rslt.='<div class="panel panel-primary">

                             <div class="panel-heading" >
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" style="color: white; text-align: left">
                                   <h4 class="panel-title">
                                      Total por tipo de demanda insatisfecha
                                   </h4></a>
                             </div>
                             <div id="collapse1" class="panel-collapse collapse in">
                                <div class="panel-body">';
        
         $rslt.='<table  class="table table-hover table-bordered table-condensed table-white" style="width:60%">'
                 . '<thead><th>Tipo de Rechazo</th>'
                 . '<th>Total</th></thead><tbody>';
        while ($row=  pg_fetch_array($resultgetExamnResult)){
        $rslt.='<tr><td>'.$row["estado"].'</td>';
        $rslt.='<td>'.$row["cantidad"].'</td></tr>';
         }//fin while de totales por tipo de rechazo
       
       
       $rslt.='</tbody></table></div>
                             </div>
                          </div>';
       //$estrechazo=$obj->estadoRechazo();
       
       $rslt.='   <div class="panel panel-primary">
                             <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" style="color: white; text-align: left">
                                <h4 class="panel-title">
                                  Resultado por Motivo de Demanda Insatisfecha
                                </h4></a>
                             </div>
                             <div id="collapse2" class="panel-collapse collapse">
                                <div class="panel-body">';

         $resultmotivo=$obj->resultadomotivo($area, $idarea, $examen, $idexamen, $fecha, $d_fechadesde, $d_fechahasta);
           $rslt.='<table  class="table table-hover table-bordered table-condensed table-white" style="width:60%">'
                 . '<thead><th>Motivos</th>'
                 . '<th>Rechazo Temporal</th>'
                 . '<th>Rechazo Definitivo</th>'
                   . '</thead><tbody>';
            while ($row1=  pg_fetch_array($resultmotivo)){
               $rslt.='<tr><td>'.$row1["posible_observacion"].'</td>';
               $rslt.='<td>'.$row1["temporal"].'</td>';
               $rslt.='<td>'.$row1["definitivo"].'</td></tr>';
            }//fin while de totales por tipo de rechazo
       
       
       $rslt.='</tbody></table></div>
                             </div>
                          </div>';
       //Reporte por area y pruebas
        $rslt.='  <div class="panel panel-primary">
                             <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" style="color: white; text-align: left">
                                <h4 class="panel-title">
                                   Resultado de demanda insatisfecha por área y pruebas
                                </h4></a>
                             </div>
                             <div id="collapse3" class="panel-collapse collapse">
                                <div class="panel-body">';
                 $estrechazo=$obj->estadoRechazo();
         $rslt.= ' <div role="tabpanel">';
         $rslt.=' <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist" id="TabRechazo">';
         while ($row2=pg_fetch_array($estrechazo)){
            //Tab
          $rslt.='<li role="presentation"><a href="#'.$row2['rtab'].'" aria-controls="home" role="tab" data-toggle="tab">'.$row2['estado'].'</a></li>';
         }
         $rslt.='  </ul>  <!-- Tab panes -->'
                 . '<div class="tab-content">';
         
         $estrechazo=$obj->estadoRechazo();
          while ($row2=pg_fetch_array($estrechazo)){
         $rslt.='<div role="tabpanel" class="tab-pane" id="'.$row2['rtab'].'" style="overflow-x: scroll; overflow-y:visible;">';
         $idposiblerec=$obj->posiblesrechazos();
            $rslt.='<br><p>Detalle de '.$row2['estado'].' por Área y Examen</p><br>'
                    . '<table  class="table table-hover table-bordered table-condensed table-white" ><thead>';
            
            $rslt.='<tr><th rowspan=2 style="vertical-align:middle">Área</th>';
            $rslt.='<th rowspan=2 style="vertical-align:middle; width:40%">Prueba</th>';
            $pg_pos=pg_num_rows($idposiblerec);
            $rslt.='<th colspan='.$pg_pos.' style="text-align:center; " valign="middle">Posible Resultado</th>'
                    . '<th rowspan=2 style="vertical-align:middle">&nbsp;Total&nbsp;</th></tr><tr>';
            while ($row3=@pg_fetch_array($idposiblerec)){
               $rslt.='<th style="vertical-align:top">'.$row3['posible_observacion'].'</th>'; 
               }
            //}//fin row3 th
            $rslt.='</tr></thead><tbody>';
            
               $resposobservacion=$obj->resultadoposobservacion($area, $idarea, $examen, $idexamen, $fecha, $d_fechadesde, $d_fechahasta, $row2['id']);
               if (pg_num_rows($resposobservacion)>0){
               while ($row4=@pg_fetch_array($resposobservacion)){
                  $rslt.='<tr>';
                  $rslt.='<th>'.$row4['nombrearea'].'</th>';
                  $rslt.='<th>'.$row4['nombre_examen'].'</th>';
                  $var=$row4['idposres_cant'];//array compyesto por idposibleresultado |cantidad
                  // $var="1|2,3|1";
                    //   echo $var.'<br>';
                  $var1=explode(",", $var);
                  $num_tags = count($var1);
                 $idposiblerec=$obj->posiblesrechazos();
                     while ($row3=@pg_fetch_array($idposiblerec)){
                        $canti='-';
                        $bandera=0;
                         
                         for ($i=0; $i<$num_tags; $i++){
                      $fin=explode("|", $var1[$i]);//Aca separa el array de la consulta
                     // echo 'i: '.$i.'  --Var: '.$var1[$i].'<br>';
                      //echo 'fin1: '.$fin[0].' fin2: '.$fin[1].'<br>';
                        if ($row3['id']==$fin[0]){
                           $bandera=1;
                           $canti=$fin[1];
                        }
                     
                        }//fin recorrer array
                        $rslt.='<td>'.$canti.'</td>';
                     }//fin row3
                 
                  
                  $rslt.='<th>'.$row4['ct_total'].'</th>';
                  $rslt.='</tr>';
               }//fin row4
               }//fin if
               else {
                  $totcols=$pg_pos+3;
                  $rslt.='<tr><td colspan='.$totcols.'>....No existe demanda insatisfecha para esta opción....</td</tr>';
               }
         

         $rslt.='</tbody></table></div>';
           
            //fin tab
         }//fin while estrechazo
         $rslt.='</div></div>';
       
       $rslt.='</div>
                             </div>
                          </div>';
       
       $ban=1;
      }//fin if pg_num_rows >1 totales
      
      
      
      
      
      
      
      if ($ban==0){
         $rslt.='No hay información disponible para los filtros seleccionados..';
        // $rslt.="Area=".$area." --idArea=".$idarea." --Examenes=".$examen." --idexamen=".$idexamen." --d_fechadesde".$d_fechadesde." --d_fechahasta=".$d_fechahasta."<br/>";
      }
      
      
      $rslt.= ' </div> 
                                    
                 
               </div>
             </div>     ';
      
      echo $rslt;
      break;

   case 10: 
  		//$procedencia=$_POST['procedencia'];
		$fechainicio=$_POST['fechainicio'];
		$fechafin=$_POST['fechafin'];
		$IdArea=$_POST['area'];
		$ffechaini=$fechainicio." 00:00:00";
		$ffechafin=$fechafin." 23:59:59";
                $FechaI=explode('-',$_POST['fechainicio']);
		$FechaF=explode('-',$_POST['fechafin']);
		$FechaI2=$FechaI[2].'-'.$FechaI[1].'-'.$FechaI[0];
		$FechaF2=$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0];
		$j=0;
		$i=0;
		$k=0;
		$l=0;
                $m=0;
                $n=0;
                $p=0;
                $q=0;
                $r=0;
                $s=0;
                $ban=0;
		$arrayidCod = array();
                $arraynombres = array();
		$arrayProce = array();
		$arraycod = array();
		$arrayidExa = array();
                $arraydia=  array();
                $arrayfechas=array();
                $cadExam="";
		$cadCod=" ";
                $cadProce=" ";
		//echo $FechaI2;            
                /* Codigos de Resultados */
                $Datos=$obj->DatosGenerales($lugar,$IdArea);
                $rowDatos=mysql_fetch_array( $Datos);
                
                
                $NumCod=$obj->NumeroDeCodigos();
		$consulta = $obj->LeerCodigosResultados();
               
		while ($rowcod=mysql_fetch_array($consulta)){
			$arrayidCod[$j]=$rowcod[0];
			$arraynombres[$j]="C".$j;
			$j++;
		}
              // print_r($arrayidCod);
                for ($i=0;$i<$NumCod;$i++){
		   $cadCod=$cadCod."sum(if(lab_resultados.IdCodigoResultado=$arrayidCod[$i],1,0)) AS $arraynombres[$i],";
		}
               // echo $cadCod;
                          
                /* Códigos de Procedencia */
                $NumProce=$obj->NumeroDeProcedencias();
                $ConProce=$obj->LeerProcedencias();
		//print_r($ConProce);
                
                while ($rowproce=mysql_fetch_array($ConProce)){
			$arraycod[$k]=$rowproce[0];
			$arrayProce[$k]="P".$k;
			$k++;
		}
                
                for ($l=0;$l<$NumProce;$l++){
                    $cadProce=$cadProce."sum(if(mnt_servicio.IdProcedencia=$arraycod[$l],1,0)) AS $arrayProce[$l],";
		}
                  
                  //echo $cadProce;
		
                 /* Códigos Estandar de los examenes */
                  
                $NumExam=$obj->NumeroDeExamenes($IdArea,$lugar);
                $conExam=$obj->CodigosEstardarxarea($IdArea,$lugar);
                    // echo $IdArea." ".$lugar." ".$NumExam;         
                while ($rowexam=mysql_fetch_array($conExam)){
			$arrayidExa[$m]=$rowexam[0];
                        
                        //echo $rowcod[0];
			$m++;
                }
               // print_r($arrayidExa);
               
                for($i=0;$i<$NumExam;$i++){
                   $cadExam=$cadExam." AND ".$arrayidExa[$i];
                    
                }
               // echo $cadExam;
                /***************************/
                $fechauno = $FechaI2;
                $fechados = $FechaF2;

                $fechaaamostar = $fechauno;
                    while(strtotime($fechados) >= strtotime($fechauno))
                    {
                        if(strtotime($fechados) != strtotime($fechaaamostar))
                        {  
                            $arrayfechas[$q]=$fechaaamostar;
                            $arraydia[$q]=substr($fechaaamostar,8,2);
                            //echo substr($fechaaamostar,8,2);
                            
                           //echo "$fechaaamostar<br />";
                            $fechaaamostar = date("Y-m-j", strtotime($fechaaamostar . " + 1 day"));
                            $q++;
                        }
                        else
                        {
                           //
                           //  echo "$fechaaamostar<br />";
                            $arrayfechas[$q]=$fechaaamostar;
                            $arraydia[$q]=substr($fechaaamostar,8,2);
                            $q++;
                            break;
                        }
                    }
                    //print_r($arraydia);
                    
                   // print_r($arrayfechas);
                    $Tdias=count($arraydia);
                   // echo $Tdias;
                  
                /***************************/
                
		
		$total=$NumCod+$NumProce;
               // echo $total;
echo "<table width='100%' higth='10%' border='0' align='center'>
        <tr>
            <td colspan='8' align='center'>
                <h3><strong>TABULADOR DIARIO DE ACTIVIDADES DE LABORATORIO CLINICO</h3></strong>
            </td>
	</tr>
        <tr>
            <td>Establecimiento:</td>
            <td Colspan='3'>".$rowDatos['Nombre']."</td>
        </tr>
        <tr>
            <td>Secci&oacute;n:</td>
            <td>".$rowDatos['Nombrearea']."</td>
        </tr>
         <tr><td colspan='4'>Per&iacute;odo del: ".$fechainicio." al ".$fechafin."</td>
        </tr>
      </table>";
        
echo"<table border='1' cellspacing='0' width='100%'>
        <tr>
            <td rowspan='3'>DIA</td>";
               for($t=0; $t<$NumExam;$t++){
                    echo"<td colspan='14'> Codigo Prueba: ".$arrayidExa[$t]."</td>";
                }
   echo"</tr>
        <tr>";
               for($t=0; $t<$NumExam;$t++){
                    echo "<td colspan='9' width='18%'> Resultado</td>
                          <td colspan='5' width='10%'> Servicio de Procedencia</td>";
               }
     echo"</tr>
          <tr>";
        for($t=0; $t<$NumExam;$t++){
             for ($n=0;$n<$NumCod;$n++){
                  echo "<td >".$arrayidCod[$n]."</td>";
             }
             for ($p=0;$p<$NumProce;$p++){
                  echo "<td>".$arraycod[$p]."</td>";
             }
         }            
     echo"</tr>
          <tr>";
          for ($r=0;$r<$Tdias;$r++){
                   echo"<td>".$arraydia[$r]."</td>";
                   for($t=0; $t<$NumExam;$t++){
                        $query="SELECT day(lab_resultados.FechaHoraReg) as dia,$cadCod $cadProce
			lab_resultados.FechaHoraReg
			FROM lab_resultados 
			INNER JOIN lab_examenes ON lab_resultados.IdExamen= lab_examenes.IdExamen
			INNER JOIN sec_solicitudestudios ON lab_resultados.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
			INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
			INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
                        INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio
			WHERE  lab_examenes.IdEstandar='".$arrayidExa[$t]."' 
                        AND date(lab_resultados.FechaHoraReg)='".$arrayfechas[$r]."' AND";
                        if (!empty($_POST['area']))
				{ $query .= " IdArea='".$_POST['area']."' AND";}
        
			if((empty($_POST['area'])) and (empty($_POST['fechainicio'])) and (empty($_POST['fechafin'])))
			{
				$ban=1;
			}
        
			if ($ban==0)
			{   $query = substr($query ,0,strlen($query)-3);
				$query_search = $query. " GROUP BY day(lab_resultados.FechaHoraReg) ORDER BY month(lab_resultados.FechaHoraReg),dia asc";
			}
                   
                        //echo $query_search;
                         $contador=$obj->ContarDatos($query_search);
                        // echo $contador;
                         if ($contador >0){
                            $consulta=$obj->BuscarExamenesporCodigo($query_search);
                           while ($row = mysql_fetch_array($consulta)){
                               //print_r($row);
                              // echo"<td>".$row[0]."</td>";
                            //   if (($row[0])!=0){
                                  for ($q=1;$q<=$total;$q++){
                                       if (!empty($row[$q]))
                                           echo "<td>".$row[$q]."</td>";
                                       else
                                           echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                                   }// del for 
                              /* }else{
                                    for ($q=0;$q<=$total;$q++)
                                         echo "<td>0</td>";

                                    }*/
                           }//while
                         }else {
                               for ($q=1;$q<=$total;$q++)
                                   echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                           }
                
                        
                   }//for de examenes
                 echo"</tr>"; 
         }//for de fecha
           
        
  echo" </tr>
     </table>";
                     //   }
                        
                 /*   echo"</tr>
                    <tr>";
            for ($l=0;$r<$Tdias;$r++){
               echo "<tr>
                         <td>".$arraydia[$r]."</td>
                     </tr>";
            }
           echo"</table>
            </td>";*/
                     

          /*  $Codexam=$obj->CodigosEstardarxarea($IdArea,$lugar);
             while ($rowexa=mysql_fetch_array($Codexam)){
                 for($s=0;$s<$Tdias;$s++){
             
                     $query="SELECT day(lab_resultados.FechaHoraReg) as dia,$cadCod $cadProce
			lab_resultados.FechaHoraReg
			FROM lab_resultados 
			INNER JOIN lab_examenes ON lab_resultados.IdExamen= lab_examenes.IdExamen
			INNER JOIN sec_solicitudestudios ON lab_resultados.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
			INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
			INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
                        INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio
			WHERE  lab_examenes.IdEstandar='".$rowexa[0]."' 
                        AND date(lab_resultados.FechaHoraReg)='".$arrayfechas[$s]."' AND";

			$ban=0;
			//VERIFICANDO LOS POST ENVIADOS
			/*if ((!empty($_POST['fechainicio'])) and (!empty($_POST['fechafin'])))
			   { $query .= "  lab_resultados.FechaHoraReg BETWEEN '$FechaI2' AND '$FechaF2' AND";}*/
        
			/*if (!empty($_POST['area']))
				{ $query .= " IdArea='".$_POST['area']."' AND";}
        
			if((empty($_POST['area'])) and (empty($_POST['fechainicio'])) and (empty($_POST['fechafin'])))
			{
				$ban=1;
			}
        
			if ($ban==0)
			{   $query = substr($query ,0,strlen($query)-3);
				$query_search = $query. " GROUP BY day(lab_resultados.FechaHoraReg) ORDER BY month(lab_resultados.FechaHoraReg);";
			}
                        
                        echo $query_search;
                        
                        
                    $consulta=$obj->BuscarExamenesporCodigo($query_search);
                    $row = mysql_fetch_array($consulta);
                   
                 /* echo "<td> 
                            <table border='1'  cellspacing='0'>
                                <tr>    
                                    <td colspan='14'> Codigo Prueba: ".$rowexa[0]."</td>
                                
                                </tr>
                                <tr>
                                    <td colspan='9'> Resultado</td>
                                    <td colspan='5'> Servicio de Procedencia</td>
                                </tr>
                                <tr>";
                                    for ($n=0;$n<$NumCod;$n++){
                                       echo "<td >".$arrayidCod[$n]."</td>";
                                    }
                                    for ($p=0;$p<$NumProce;$p++){
                                         echo "<td>".$arraycod[$p]."</td>";
                                    }            
                            echo "</tr>
                                  <tr>";
                       for($s=)      
                        for ($q=1;$q<=$total;$q++){
                            if (!empty($row[$q]))
                                echo "<td>".$row[$q]."</td>";
                            else
                                echo "<td> 0 </td>";
                        }
                         echo "</tr>
                                
                        </table></td>";*/
                    
		  
			
          //  }
         //   echo"</tr></table>";
          //   }
    break;	
    case 3://LLENANDO COMBO subservicio
	
		$rslts='';
		$proce=$_POST['proce'];
		//echo $IdSubEsp;
		$dtMed=$obj->LlenarSubServ($proce);	
		
		$rslts = '<select name="cboMedicos" id="cmbSubServicio" class="MailboxSelect" style="width:250px">';
		$rslts .='<option value="0">--Seleccione un Servicio--</option>';
			
		while ($rows =mysql_fetch_array($dtMed)){
			$rslts.= '<option value="' . $rows[1] .'" >'. htmlentities($rows[0]).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;
	
	break;	
        case 2://LLENANDO COMBO DE Examenes
		$rslts='';
		
		$idarea=$_POST['idarea'];
		//echo $IdSubEsp;
		$dtExam=$obj->ExamenesPorArea($idarea,$lugar);	
		$rslts = '<select id="cmbExamen" name="cmbExamen" class="height js-example-basic-multiple" style="width:100%" multiple="multiple">';
		$rslts .='<option></option>';
			
		while ($rows =pg_fetch_array($dtExam)){
			$rslts.= '<option value="' . $rows['id'] .'" >'. htmlentities($rows['idestandar']).'  &#09;'. htmlentities($rows['nombreexamen']).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;
		
	
   	break;
 }