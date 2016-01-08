<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsReporteRegistroDiario.php");
//creando los objetos de las clases
$obj = new clsReporteRegistroDiario();
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
      $rslt = "";
      $ban = 0;
      $idarea = $_POST['idarea'];
      if ($idarea==""){
        $area=0;
        $idarea=0;
      }

      $d_fechadesde = $_POST['d_fechadesde'];
      if ($d_fechadesde==""){
         $fecha=0;
         $femu=@pg_fetch_array($obj->fechamuestra());
        /// $feact=;
         $d_fechadesde=$femu[0];
      }
      //Consulta de muestras recibidas
      $resultgetExamnResult =$obj->getRegistroDiario($idarea, $d_fechadesde, $lugar);
      
      $rslt .= "<table border='1'>
                <tr>
                <td>&nbsp;</td>
                <td colspan='4' align='center'>IDENTIFICACION DE LA PERSONA</td>
                <td>&nbsp;</td>
                <td colspan='2' align='center'>PROCEDIMIENTO</td>
                <td colspan='2' align='center'>RESULTADO</td>
                <tr>
                
                <tr>
                <td width='50px'>No. ORDEN</td>
                <td width='300px' align='center'>NOMBRE</td>
                <td>EDAD</td>                
                <td>SEXO</td>
                <td>EXPEDIENTE</td>
                <td>PROCED</td>
                <td align='center'>NOMBRE</td>
                <td>CODIGO</td>
                <td width='300px' align='center'>COMENTARIO</td>
                <td width='50px'>CODIGO</td>
                <tr>";
      if (pg_num_rows($resultgetExamnResult)>=1){
          while ($row = pg_fetch_array($resultgetExamnResult)){
              $edad = str_replace('years','a',$row['edad']);
              $edad = str_replace('year','a',$edad);
              $edad = str_replace('mons','m',$edad);
              $edad = str_replace('mon','m',$edad);
              $edad = str_replace('days','d',$edad);
              $edad = str_replace('day','d',$edad);
              $rslt .= "<tr>
                        <td align='center'>".$row['numeromuestra']."</td>
                        <td>".strtoupper($row['paciente'])."</td>
                        <td>".$edad."</td>
                        <td align='center'>".substr($row['sexo'],0,1)."</td>
                        <td>".$row['idnumeroexp']."</td>
                        <td align='center'>".$row['idservicio']."</td>
                        <td>".$row['nombreexamen']."</td>
                        <td align='center'>".$row['idestandar']."</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>                            
                        </tr>";
          }
          echo "<form action='export_to_excel.php' method='GET'>
                    <input type='hidden' name='idarea' value='$idarea'>
                    <input type='hidden' name='d_fechadesde' value='$d_fechadesde'>
                    <input type='hidden' name='lugar' value='$lugar'>
                    <a href='#' onclick='popup(".'"export_to_excel.php?idarea='.$idarea.'&d_fechadesde='.$d_fechadesde.'&lugar='.$lugar.'"'.")' style='text-align: right' class='btn btn-primary'>Vista previa</a>
                </form>
          ";
          
          $ban = 1;
      }//fin if pg_num_rows >1 totales
      
      
      
      
      
      
      
      if ($ban==0){
         $rslt.="<tr><td colspan='11'>No hay información disponible para los filtros seleccionados..</td></tr>";
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