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
      $idarea = $_POST['idarea'];
      if ($idarea=="")
         $idarea=0;
      $idexamen = $_POST['idexamen'];
      if ($idexamen=="")
         $idexamen=0;
      $d_fechadesde = $_POST['d_fechadesde'];
      if ($d_fechadesde==""){
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
      

      $resultgetExamnResult = getExamnResult($idHistorialClinico,
              $idDatoReferencia, $idEstablecimiento);
      $resultgetDatosGenerales = getDatosGenerales($idHistorialClinico,
              $idDatoReferencia, $idEstablecimiento);


      $consulta = $objdatos->obtenerDatosGenerales($idHistorialClinico,
              $idDatoReferencia, $idEstablecimiento);
      $row = @pg_fetch_array($consulta);


      $nombre_establecimiento = $row['nombre_establecimiento'];
      $procedencia = $row['procedencia'];
      $servicio = $row['servicio'];
      $nombre_empleado = $row['nombre_empleado'];
      $numero_expediente = $row['numero_expediente'];
      $nombre_paciente = $row['nombre_paciente'];
      $fecha_solicitud = $row['fecha_solicitud'];


      //  DATOS GENERALES

      $imprimir = "<br> <form name='frmDatos'>
            <div class='table-responsive' style='width: 80%;'>
                <table width='70%' border='0' align='center' class='table table-hover table-bordered table-condensed table-white'>
			<thead>
                                    <tr>
                                            <th colspan='4' align='center' style='background-color: #428bca; color: #ffffff'>
                                                    <h3>  <center>  <strong>DATOS SOLICITUD</strong>   </center>  </h3></th>
                                    </tr>
                        </thead><tbody>
			<tr>

				<td>Establecimiento</td>
                                <td colspan='3'>" . $nombre_establecimiento . "</td>
			</tr>
		        <tr>
				<td>Procedencia</td>
				<td colspan='1'>" . $procedencia . " 
                        </tr>
                        <tr>
                                <td>Origen</td>
                                <td>" . $servicio . "</td>
                        </tr>
                        <tr>
				<td>M&eacute;dico</td>
				<td colspan='3'>" . $nombre_empleado . "
			</tr>
                        <tr>
				<td>No. Expediente</td>
				<td>" . $numero_expediente . "</td>
			</tr>
                        <tr>
				<td>Nombre Paciente</td>
				<td>" . $nombre_paciente . "</td>
			</tr>
                        <tr>
                                <td>Fecha Recepción</td>
                                <td colspan='3'>" . $fecha_solicitud . "</td>
                        </tr>
                </tbody>
            </table>
        </div>";

      echo $imprimir;



      /*
       * Impresion de Resutlados
       */
      $print = '';

      $print .= MuestrasRechazadas($resultgetExamnResult['RM']);

      if (count($resultgetExamnResult['RC']) > 0) {
         foreach ($resultgetExamnResult['RC'] as $area) {
            //----areas
            /* <div class="panel panel-primary">
              <div class="panel-heading mouse-pointer" role="tab" id="heading-{{ area.codigo }}" data-toggle="collapse" data-target="#area-{{ area.codigo }}" aria-expanded="false" aria-controls="area-{{ area.codigo }}">
              <h4 class="panel-title">
              {{area.nombre}}
              </h4>
              </div>
              <div id="area-{{ area.codigo }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-area-{{ area.codigo }}">
              <div class="panel-body">
              <div class="table-responsive">
              {% set arrayPlantillas = ['A','B','C','D','E'] %}
              {% for pType in arrayPlantillas %}
              {% if area.plantillas[pType] is defined %}
              {% include 'MinsalLaboratorioBundle:Custom:SecSolicitudestudios/bodyLayout.html.twig' with {'pType': pType} %}
              {% endif %}
              {% endfor %}
              </div>
              </div>
              </div>
              </div> */
            // <div class="panel panel-success">...</div>




            /* $print.= "<div class='panel-heading mouse-pointer' role='tab' id='heading".$area['codigo']."' data-toggle='collapse' data-target='#".$area['codigo']."' ".$aria."-expanded='false' aria-controls='".$area.['codigo']."'>
              <h4 class='panel-title'>
              ".$area['nombre']."
              </h4>
              </div>
              <div id='".$area['codigo']."' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='heading-".$area['codigo']."'>
              <div class='panel-body'> </div>
              </div>"; */

            $print.= "<div class='panel panel-success'>
                                        <div class='panel-heading mouse-pointer' role='tab' id='heading- data-toggle='collapse' >
                                            <h4 class='panel-title'>
                                                " . $area['nombre'] . "
                                            </h4>
                                        </div>
                        </div>";

            $arrayPlantillas = ['A', 'B', 'C', 'D', 'E'];
            foreach ($arrayPlantillas as $pType) {
               if (array_key_exists($pType, $area['plantillas'])) {
                  $print .= bodyLayout($area, $pType);
               }
            }
         }
      } else {
         //  $print = 'Los examenes no han sido procesados aun...';
         {
            $print = " <table > <tr><td colspan='11'><span style='color: #575757;'>Los examenes no han sido procesados aun...</span></td></tr></tbody></table></div>";
         }
      }


      echo $print;
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