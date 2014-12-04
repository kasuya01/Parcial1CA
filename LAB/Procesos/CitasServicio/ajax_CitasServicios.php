<?php
session_start();

include("../../../Conexion/ConexionBD.php");
include_once("Cls_CitasServicio.php");

$citaserv = new clsCitasServicio;
$con = new ConexionBD;

$IdUsuarioReg=$_SESSION['iduser'];
//$lugar = $_SESSION['Lugar'];
	if(isset($_POST['Proceso'])){
		$Proceso = $_POST['Proceso'];
	}else{
		$Proceso = $_GET['Proceso'];
	}
$IdEstablecimiento = $_SESSION['Lugar'];
$fechareg = $citaserv->FechaServer();
$idexp=$_POST['id_exp'];
//echo $idexp.' - '.$IdEstablecimiento.' - '.$_POST['Proceso'];

switch($Proceso){
	
////	Procesos para el otorgamiento de CITAS PARA LOS SERVICIOS DE APOYO.	
	
	case 'busquedaexp'://busqueda de los datos del paciente en base al numero de expediente, ya que al otorgar la cita del servicio de apoyo TODO paciente debe poseer un numero de expediente clinico
	$rslt='';
	
	$dtSub=$citaserv->ValidarExpediente($idexp, $IdEstablecimiento);
			
	$rslt = "<table border='1' cellpadding='4' class='StormyWeatherFormTABLE'>".
					"<tr>".
					"<td class='StormyWeatherFieldCaptionTD' align='center'><strong>No. de Registro</strong></td>".
					"<td class='StormyWeatherFieldCaptionTD' align='center'><strong>Nombre del Paciente</strong></td>".
					"</tr>";
	
								
	while ($row =pg_fetch_array($dtSub)){
					
		$rslt .="<tr><td class='StormyWeatherDataTD' align='center'>";
                $rslt .="<a class=\"MailboxDataLink\" href=\"javascript:mostrardetalle(".$row['id'].", $IdEstablecimiento)\">".$row['numero']."</a></td>";
		$rslt .="<td class='StormyWeatherDataTD'>".$row['nombre']."</td></tr>";
	}
        
	echo $rslt;
	//echo $idexp.' - '.$IdEstablecimiento;
	break;
	
	case 'mostrardetalle'://genera el detalle de la(s) solicitud(es) que tiene asignado un paciente en base a su seguimiento clinico
	$rslt='';
       //     $rslt.='idexp: '.$_POST['id_exp'].' idestab '.$IdEstablecimiento;
	
	$dtSub=$citaserv->MostrarDetalleSolicitudes($_POST['id_exp'],$IdEstablecimiento);
	$totalRegs= pg_num_rows($dtSub);
	
	if($totalRegs > 0){	 
	
	$rslt.= '<br>'.
			'<br>'.
			"<table border='1' cellpadding='4' class='MailboxFormTABLE'>".
			'<tr>'.
			'<td nowrap class="StormyWeatherFieldCaptionTD"><strong>Numero de Expediente</strong></td>'.
			'<td nowrap class="StormyWeatherFieldCaptionTD"><strong>Fecha de Solicitud de Examenes</strong></td>'.
			'<td nowrap class="StormyWeatherFieldCaptionTD"><strong>Dar Cita del Servicio de Apoyo</strong></td>'.
			'</tr>';
									
		while ($row =pg_fetch_array($dtSub)){
					
			$rslt.='<tr>'.
					'<td class="StormyWeatherDataTD" align="center"> '.$row['numero'].' </td>'.
					'<td class="StormyWeatherDataTD" align="center"> '.$row['fecha_solicitud'].' </td>'.
					'<td class="StormyWeatherDataTD" align="center">'.
					'<a class="StormyWeatherDataLink" href="javascript:darcita('.$row[2].','.$row['idatencion'].','.$_POST['id_exp'].')">'.
					'&nbsp;'.$row['nombre'].'</a></td>'.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
					'</tr>'.
					'<tr>';
		}
	}else{
		
		$rslt = -1;}                                                                                                                                                                                                                                                                                                                                                             
	
	echo $rslt;
	break;
	
	case 'darcita':///proceso para asignar cita a los servicios de apoyo en este caso solo para el area de Bacteriologia, Laboratorio
		$rslt='';
		$var='';
		$actual='';
		$fecha='';
		$tiempo_max='';
		$bandera='';
		$dt_Ex='';
		$employee="Todos";
		$codigo="QUI045";
		$variable=0;
                $idexp=$_POST['id_exp'];
                $id_qui045=$citaserv->buscaridexamen($codigo);
               // echo 'Buahh'.$id_qui045;
        
	
	//if ($con->conectar()==true){
	//verifica si el usuario ha dado clic en el link de laboratorio o de rayos x.	
	//echo $_POST['idservicio'].' - '.$_POST['fecha'].' -isset:  '.isset($_POST['fecha']);	
	if ($_POST['idservicio']== 98){//Proceso para dar cita al area de laboratorio
	if (isset($_POST['fecha'])){
		$Fecha=$_POST['fecha'];
        	
	}else{
		$Fecha='';
	}
        
			$nombredelservicio= 'LABORATORIO';

			$dtSub=$citaserv->ListExamenesTiempoPrev($_POST['idsolicitudestudio'],$IdEstablecimiento);
                        $IdSubServicio=$citaserv->ObtenerSubServicio($_POST['idsolicitudestudio'],$Fecha, $idexp);
			
			$Duplos=$citaserv->Duplos($_POST["idsolicitudestudio"],$IdSubServicio);
			

			if($row2=pg_fetch_array($Duplos)){
				if($row2['estado']==4){
					//setlocale(LC_TIME, 'Spanish'); 
				
							
				$rslt ='<table><tr><td colspan="5" align="center" ><h5>FECHA DE CITA PARA LABORATORIO :</h5></tr>
				<tr><td colspan="5" align="center" ><h1>Solicitud de Laboratorio REALIZADA</h1></td></tr>
				<tr><td style="color:#990000; font:bold" align="center"><h1> '.ucwords(strftime("%d de %B %Y",htmlentities(strtotime($row2["fecha"])))).'</h1></td></tr>
				<tr><td align="center"><h5>TOTAL DE EXAMENES:</h5></tr>';
				
				
				$rslt.='<tr><td align="center" style="color:#990000; font:bold"><h3>'.pg_num_rows($dtSub).'</h3></td></tr>
				<tr><td colspan="5" height="5"><hr></td></tr>';
				
				
				while ($row=pg_fetch_array($dtSub)){
					$rslt.='<tr align="left">'.
								'<td>'.htmlentities($row["nombreexam"]).'</td>';
				}
		
				//Mostramos en pantalla los nombres de los examanes que se le indicaron 
					
				$rslt.='<tr><td colspan="5" height="3"></td></tr>
				<tr><td colspan="5" height="7"></td></tr>
				</table>';

				}
                                if ($row2['estado']==1){
				//Ya existe cita para esta solicitud
				setlocale(LC_TIME, 'Spanish'); 
				
							
				$rslt ='<table><tr><td colspan="5" align="center" ><h5>FECHA DE CITA PARA LABORATORIO :</h5></tr>
				<tr><td colspan="5" align="center" ><h1>Paciente ya cuenta con Fecha de Laboratorio</h1></td></tr>
				<tr><td style="color:#990000; font:bold" align="center"><h1> '.ucwords(strftime("%d de %B %Y",htmlentities(strtotime($row2["fecha"])))).'</h1></td></tr>
				<tr><td align="center"><h5>TOTAL DE EXAMENES A REALIZARCE:</h5></tr>';
				
				
				$rslt.='<tr><td align="center" style="color:#990000; font:bold"><h3>'.pg_num_rows($dtSub).'</h3></td></tr>
				<tr><td colspan="5" height="5"><hr></td></tr>';
				
				
				while ($row=pg_fetch_array($dtSub)){
					$rslt.='<tr align="left">'.
								'<td>'.htmlentities($row["nombreexam"]).'</td>';
				}
				
							
				//Mostramos en pantalla los nombres de los examanes que se le indicaron 
					
				$rslt.='<tr><td colspan="5" height="3"></td></tr>
				<tr><td colspan="5" height="7"></td></tr>
				</table>';
				}
				echo $rslt;
				
			}else{
				$array_labexamens = array();	
				$array_namexams = array();
				$i=1;	
					
				while ($row =pg_fetch_array($dtSub)){
					$array_labexamens[$i] = $row['idexamen'];
					$array_namexams[$i] = $row['nombreexam'];
					$i++;
				}
				//Sacamos los tama�os de los arreglos de examanes y del tiempo previo que necesita cada uno para realizarce
				$tamano = sizeof($array_labexamens);
				
				$dd_encontrado=array_search($id_qui045,$array_labexamens);
				if ($dd_encontrado>=0 and $dd_encontrado <>""){
					$foundcreatinina=1;
				}else{
					$foundcreatinina=0;			
				}
                              //  echo 'dd_encontrado:'.$dd_encontrado.'  --foundcrea: '.$foundcreatinina;
				
				$tiempo_max=$citaserv->MaxTiempoPrevdeExamenes($_POST['idsolicitudestudio'],$IdEstablecimiento);
				//echo $tiempo_max;
				$l=0;
				$t=1;
				
				$dt_Cm=$citaserv->ObtenerFechaCitaMedica($_POST['id_exp']);
				
				while ($row =pg_fetch_array($dt_Cm)){
						$fecha="$row[0]";//Sacamos la proxima fecha de la cita medica otorgada el dia de la cita.
				}
				//var_dump( '--------FECHA:'.$fecha.' --'.(!empty($fecha)).' ** '. (empty($fecha)).' --');
			if (!empty($fecha)){
                            //echo 'entro a empty';
				//Genera la fecha de la cita de servicio considerando el tiempo maximo del examen base.
				$actual = $citaserv->RESTAR($fecha,$tiempo_max);
                                $rowfechas =  pg_fetch_array($actual);
                                $diaServ =$rowfechas['fecha_int'];
                                $actual =$rowfechas['fecha_actual'];
                               
				//$actual=date("Y-m-d", strtotime("-" .$tiempo_max. "days",strtotime($fecha)));
				/***SE VERIFICA SI LA FECHA ES IGUAL A LA FECHA ACTUAL SI LO ES SE AUMENTA EN UN DIA**/
				//echo "normal ".$actual;
				//$actual=$citaserv->ValidarFecha($actual);
				//	echo "FECHA= ".$actual;		
				$bandera=0;
				echo ' bandera:'.$bandera;
				while($bandera==0){
                                    echo 'entro';
                                }
                                        //echo 'actual: '.$actual;
					//identificamos que dia de la semana tiene la fecha generada
//					$dd = $citaserv->parse_day($actual);
					//comprobamos si ese dia de la semana no es sabado ni domingo
//					$weekend = $citaserv->diaslaborales($dd);
					//comprobamos si esa fecha es dia festivo fijo
//					$esFest = $citaserv->dias_festivos($actual,$employee);
//					//comprobamos cuantas solicitudes de examenes se tienen para esta fecha encontrada no permite un cupo mayor a 20
//					$existencia = $citaserv->ContarFechas($actual);
//					//generamos un arreglo para comprobar cuantas solicitudes de esta fecha contienen el examen de creatinina
//					$creatinina = $citaserv->ContarCreatinina($actual,$id_qui045);
									
			/*if ($dd == 5 || $dd==6){//verificamos q si es Jueves o Viernes
				if ($existencia <= 60){//el techo de examenes sea de 60 solamente
					if($weekend == 0 && $esFest == 0){
						//comprobamos si ese dia de la semana no es sabado ni domingo
						$weekend = $citaserv->diaslaborales($dd);	
						$datos = $citaserv->InsertarCitaServicio($actual,$_POST['idsolicitudestudio'],$fechareg,$variable,$IdUsuarioReg);
						//$datos = $citaserv->InsertarCitaServicio($actual,$_POST['idsolicitudestudio'],$fechareg,$variable);
						$bandera=1;
						
					}else{
						//Sumamaos un dia mas a la fecha generadada
						$actual=$citaserv->subdays($actual);
						//Verificamos que dia de la semana tiene la fecha nueva generada.
						$dd = $citaserv->parse_day($actual);
						}
				}else{
					//Sumamaos un dia mas a la fecha generadada
					$actual=$citaserv->subdays($actual);
					
					//Verificamos que dia de la semana tiene la fecha nueva generada.
					$dd = $citaserv->parse_day($actual);
					
					}
			}else{*/
			//Hemos definido un techo para realizar examenes 
//				if ($existencia <= 30){
//					if($weekend == 0 && $esFest == 0){
//                                            echo '......::entro a insert::...';
//						//comprobamos si ese dia de la semana no es sabado ni domingo
//						//$weekend = $citaserv->diaslaborales($dd);							
//						//$datos = $citaserv->InsertarCitaServicio($actual,$_POST['idsolicitudestudio'],$fechareg,$variable,$IdUsuarioReg);
//						$bandera=1;
//						
//					}else{
//						//Sumamaos un dia mas a la fecha generadada
//						$actual=$citaserv->subdays($actual);
//						//Verificamos que dia de la semana tiene la fecha nueva generada.
//						$dd = $citaserv->parse_day($actual);
//						}
//				}else{
//					//Sumamaos un dia mas a la fecha generadada
//					$actual=$citaserv->subdays($actual);
//					//Verificamos que dia de la semana tiene la fecha nueva generada.
//					$dd = $citaserv->parse_day($actual);
//					}
			//}	
//		}		
//				$rslt ='<br><br>';
//				$rslt .='<table cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
//				$rslt .='<tr><td colspan="5" height="5" width="700"></td></tr>';
//				$rslt .='<tr><td colspan="5" align="center" ><h5>FECHA DE CITA PARA LABORATORIO :</h5></tr>'; 
//				$rslt .='<tr><td style="color:#990000; font:bold" align="center"><h1>' .$actual; 
//				$rslt .='</h1></td></tr>';
//				$rslt .='<tr><td align="center"><h5>HORA DE LA CITA:</h5></tr>'; 
//				$rslt .='<tr><td align="center" style="color:#990000; font:bold"><h3>6:00 a.m<h3>';
//				$rslt .='<tr><td colspan="5" height="5"></td></tr>';
//				$rslt .='<tr><td align="center"><h5>TOTAL DE EXAMENES A REALIZARCE:</h5></tr>'; 
//				$rslt .='<tr><td align="center" style="color:#990000; font:bold"><h3>' .$tamano;
//				$rslt .='</h3></td></tr>';
//				$rslt .='<tr><td colspan="5" height="5"><hr></td></tr>';
//				
//				//Mostramos en pantalla los nombres de los examanes que se le indicaron 
//					for($i=1;$i<=$tamano;$i++){
//						$rslt .='<tr align="left">'.
//								'<td>'.htmlentities($array_namexams[$i]).'</td>';
//					}
//					
//				$rslt .='<tr><td colspan="5" height="3"></td></tr>';
//				$rslt .='<tr><td colspan="5" height="7"></td></tr>';
//				$rslt .='</table>';
			
			}else{$rslt= -1;}

			//echo $rslt;
			}
//				
		}//If Duplos
			
	
	break;

	case 'prueba':
	
	$fecha=$_POST['fecha'];
	$rslts='';
	
	$link = mysqli_connect('localhost','root', '','siap'); 
	
 	if (!$link) { 
		$rslts = 'No se Puede Conectar a la BD. Error:' . mysqli_connect_error(); 
		exit; 
	} 
	//$result = mysqli_multi_query($link,  "call ComprobarCreatinina('$fecha',@erro,@cant); select @cant;");
    $result = mysqli_query($link,  "call ComprobarCreatinina('$fecha',@erro,@cant);");
	// while ($row = mysqli_fetch_array($result)){
		// echo $row[0];
		// echo "<br />";
		// echo $row[1];
	// }
	if (!$result){
		echo "error de proceduer";
		exit;
	}
	
	$row = mysqli_fetch_array($result,MYSQLI_NUM);
		printf("%s (%s)\n",$row[0],$row[1]);		
	
	
	//echo $rslts;
	mysqli_close($link);
	break;
	
	case 'comprobar':
        $rslts='0';
	/*$dd = $citaserv->buscar($_POST['idsolicitudestudio'],$_POST['idservicio']);
	if ($dd==0){
		$rslts=0;
	}else {$rslts=-1;}*/
	echo $rslts;
	break;
}
