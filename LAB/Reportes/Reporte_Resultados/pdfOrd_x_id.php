<?php
require('../../../pdfg.php');
include_once("clsReporteResultados.php");
@session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

class PDF extends PDF_PgSQL_Table
{

   function Header()
           {
                   $establee=$_SESSION["nombre_estab"];
                   $this->Image('../../../Imagenes/paisanito.jpeg', 10,8,20,15);
                   $this->Image('../../../Imagenes/escudo1.jpeg', 180,8,20,15);
                   //Fuente
                   $this->SetFont('Arial','B',12);
                   //Titulos
                   $this->SetFillColor(200,220,255);
                   $this->Cell(0,6,'Ministerio de Salud ',0,1,'C');
                   $this->SetFont('Arial','B',10);
                   $this->Cell(0,6,'Sistema de Informacion de Atencion de Pacientes',0,1,'C');
                   $this->Ln(2);
                   $this->SetFont('Arial','B',8);
                   $this->Cell(0,1,'Reporte Resultado de Paciente',0,1,'C');
                   $this->Ln(1);
                   $this->SetFont('Arial','B',7);
                   $this->Cell(0,1,'',0,1,'C');
                   //Line(X1, Y1, X2, Y2);

                   $this->SetFont('Arial','B',7);
                   //Fecha
                   $date = date ("j/m/Y"); 
                   $this->Text(160,20,$date);// si es vertical 240
                   $this->Line(15,28,190,28); 

                   //Se empiezan a escribir los datos del paciente
                   $this->SetFont('Arial','B',7);
                   $this->Cell(0,6,'Establecimiento '. utf8_decode($establee),0,1,'C');
                   $this->SetFont('Arial','B',10);
           //Cabecera de tabla
                   $this->Ln(5);
                   //Aqui la relaciona con encabezado de uno.php ubicado en /sigep/uno.php
                   parent::Header();
           }
  //Page footer
   function Footer()
   {
       //Position at 1.5 cm from bottom
       $this->SetY(-15);
       //Arial italic 7
       $this->SetFont('Arial','I',7);

       //Page number
       $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
   }
}//fin de la clase

$pdf=new PDF('p'); //p vertical
$pdf->AliasNbPages();
$pdf->AddPage();
ob_end_clean();
$pdf->Output();
//$i_idsolicitud=$_GET['i_idsolicitud'];
//$i_idestablocal=$_GET['idlocal'];
//$objeto = new cSolicitud();
//
////Se crea el objeto $objexcelRequi de clase cRequi
//
//class PDF extends PDF_PgSQL_Table
//{
//
//function Header()
//	{
//	
//                $objeto = new cSolicitud();
//                $i_idsolicitud=$_GET['i_idsolicitud'];
//                $i_idestablocal=$_GET['idlocal'];
//                $row=@pg_fetch_array($objeto->consolicitudid($i_idsolicitud, $i_idestablocal));
//                $v_numexpediente=$row['v_numexpediente'];
//                $nombre=$row['nombre'];
//                $edad=$row['edad'];
//                $c_sexo=$row['c_sexo'];
//                $estabsolicito=$row['estabsolicito'];
//                $padreestabsolicito=$row['padreestabsolicito'];
//                $d_fechasolicitud=$row['d_fechasolicitud'];
//                $d_fecharecepcion=$row['d_fecharecepcion'];
//                $v_diagnostico=$row['v_diagnostico'];
//                $b_embarazada=$row['b_embarazada'];
//                if ($b_embarazada=='t')
//                {
//                    $embarazada='Si';
//                }
//                 else {
//                     $embarazada='No';
//                }
//                $i_controlemb=$row['i_controlemb'];
//                switch ($i_controlemb) {
//                    case 1: $control='1era vez';
//                        break;
//                    case 2: $control='2da vez';
//                        break;
//                    case 3: $control='3ra vez';
//                        break;
//                    default:$control='--';
//                        break;
//                }
//                $b_veterano=$row['b_veterano'];
//                $b_fvg=$row['b_fvg'];
//                if ($b_veterano=='t')
//                {
//                        $veterano="Veterano";
//                }
//                
//                 else {
//                     if ($b_fvg=='t'){
//                         $veterano="Fam. Veterano";
//                     }
//                     else{
//                        $veterano="No";
//                     }
//                }
//                $v_dui=$row['v_dui'];
//                $v_nombremedico=$row['v_nombremedico'];
//                $idpersona=$row['idpersona'];
//
//
//                $emple=$_SESSION["s_empleado"];
//                $establee=$_SESSION["s_establec"];
//
//		//Logo
//		$this->Image('../../../../default/img/logo_mspas.jpeg', 10,6,30,20);
//		//$this->Image('../../../../default/img/logo_mspas.jpeg', 10,8,20,15);--antes logo pequeño
//		$this->Image('../../../../default/img/ins.jpg', 180,8,20,15);
//		//Fuente
//		$this->SetFont('Arial','B',12);
//		//Titulos
//		$this->SetFillColor(200,220,255);
//		$this->Cell(0,6,'Ministerio de Salud ',0,1,'C');
//		$this->SetFont('Arial','B',10);
//		$this->Cell(0,6,'Laboratorio Regional',0,1,'C');
//		$this->Ln(2);
//		$this->SetFont('Arial','B',8);
//		$this->Cell(0,1,'Resultados de Solicitud',0,1,'C');
//		$this->Ln(1);
//		$this->SetFont('Arial','B',7);
//		$this->Cell(0,1,'',0,1,'C');
//		//Line(X1, Y1, X2, Y2);
//	
//		$this->SetFont('Arial','B',9);
//		//Fecha
//		$date = date ("j/m/Y"); 
//		$this->Text(160,20,$date);// si es vertical 240
//		$this->Line(15,28,190,28); 
//                
//                //Se empiezan a escribir los datos del paciente
//                $this->SetFont('Arial','B',7);
//		$this->Cell(0,6,'Establecimiento '. utf8_decode($establee),0,1,'C');
//    	//Cabecera de tabla
//                $this->Ln(5);
//		//$this->Cell(25);
//		$this->SetFont('Arial','B',9);
//		$this->SetFillColor(255,255,255);
//		$this->Cell(40,5,'Orden No',0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//		$this->Cell(65,5,$i_idsolicitud,0,0,'L');
//                $this->SetFont('Arial','B',9);
//                $this->Cell(40,5,utf8_decode('Fecha de Impresión'),0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//		$this->Cell(65,5,date('Y-m-d'),0,1,'L');
//		$this->SetFont('Arial','B',9);
//		$this->Cell(40,5,'No de Expediente',0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//		$this->Cell(65,5,utf8_decode($v_numexpediente) ,0,0,'L');
//		$this->SetFont('Arial','B',9);
//		$this->Cell(40,5,utf8_decode('Hora Impresión'),0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//                 $fecha2=time();
//		$this->Cell(65,5,date("H:i:s",$fecha2) ,0,1,'L');
//		$this->Ln(1);
//		
//		
//	//	$this->Cell(25);
//		$this->SetFont('Arial','B',9);
//	//	$this->SetFillColor(224,235,255);
//		$this->Cell(40,5,'Paciente',0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//		$this->Cell(65,5,utf8_decode($nombre),0,0,'L');
//		$this->SetFont('Arial','B',9);
//	//	$this->SetFillColor(224,235,255);
//		$this->Cell(40,5,'Fecha Solicitud',0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//		$this->Cell(65,5,utf8_decode($d_fechasolicitud),0,1,'L');
//		$this->SetFont('Arial','B',9);
//		//$this->SetFillColor(224,235,255);
//                $this->Cell(40,5,'Sexo',0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//		$this->Cell(65,5,$c_sexo,0,0,'L');
//		$this->SetFont('Arial','B',9);
//		//$this->SetFillColor(224,235,255);
//                $this->Cell(40,5,'Fecha Entrada LR',0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//		$this->Cell(65,5,$d_fecharecepcion,0,1,'L');
//                $this->SetFont('Arial','B',9);
//		$this->Cell(40,5,'Edad',0,0,'L', true);
//		$this->SetFont('Arial','',9);
//		$this->Cell(65,5,$edad,0,0,'L');
//                $this->SetFont('Arial','B',9);
//		$this->Cell(40,5,utf8_decode('Médico'),0,0,'L', true);
//		$this->SetFont('Arial','',9);
//		$this->Cell(65,5,$v_nombremedico,0,1,'L');		
//		//$this->SetFillColor(224,235,255);		
//		$this->SetFont('Arial','B',9);
//		$this->Cell(40,5,'Sibasi',0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//		$this->Cell(65,5,utf8_decode($padreestabsolicito),0,0,'L');
//		$this->SetFont('Arial','B',9);
//		$this->Cell(40,5,'Control Embarazada',0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//		$this->Cell(45,5,utf8_decode($control),0,1,'L');
//                $this->SetFont('Arial','B',9);
//		$this->Cell(40,5,'Establecimiento',0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//		$this->Cell(65,5,utf8_decode($estabsolicito),0,0,'L');
//                $this->SetFont('Arial','B',9);
//		$this->Cell(40,5,'Otro Tipo Paciente',0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//		$this->Cell(65,5,utf8_decode($veterano),0,1,'L');
//                 $this->SetFont('Arial','B',9);
//		$this->Cell(40,5,'Comentario',0,0,'L', true);
//		 $this->SetFont('Arial','',9);
//		$this->Cell(45,5,utf8_decode($v_diagnostico),0,1,'L');
//                
//                $this->Line(5,81,200,81);   //si es horizonatal: Line(74,19.5,220,19.5) si es vertical: Line(74,19.5,142,19.5);
//		//salto de linea
//		$this->Ln(2);
//
//		//Aqui la relaciona con encabezado de uno.php ubicado en /sigep/uno.php
//		parent::Header();
//	}
//	//Pie de Pagina
////Page footer
//function Footer()
//{
//    $this->Ln(6);
//    
//    //Position at 1.5 cm from bottom
//    $this->SetY(-15);
//    //Arial italic 7
//    $this->SetFont('Arial','I',7);
//    
//    $this->Image('../../../../default/img/rnlc.jpg', 95,260,50,10);	
//    //Page number
//    $this->Cell(75,5,utf8_decode('NOTA: (*) IMPLICA VALOR FUERA DE RANGO DE REFERENCIA'),0,0,'L');
//    $this->Cell(0,10,html_entity_decode('Pag. ').$this->PageNo().'/{nb}',0,0,'R');
//}
//
//
//function pdfid($i_idsolicitud, $idgrupoprueba, $nombregrupoprueba, $v_codgrupoprueba, $i_idestablocal)
//   	{	
//        $this->SetFont('Arial','B',12);
//		//Titulos
//        $this->SetFillColor(200,220,255);
//		
//        $objeto = new cSolicitud();
//        $row=@pg_fetch_array($objeto->consolicitudid($i_idsolicitud, $i_idestablocal));
//        $idpersona=$row['idpersona'];
//        $ban1=0;
//        $this->Cell(0,6, $v_codgrupoprueba. ' '.utf8_decode($nombregrupoprueba),0,1,'L');
//        $grupopruecon=$objeto->grupoprueconsul($i_idsolicitud, $i_idestablocal);
//  
//        $this->Line(5,88,200,88);
//        $this->Ln(2);
//         $empleval=$objeto->validadosempleprue($i_idsolicitud, $idgrupoprueba, $i_idestablocal);          
//          while ($row2=@pg_fetch_array($empleval)){
//            $resulprueba=$objeto->resultadoprueba($i_idsolicitud, $idgrupoprueba, $row2['id_empleadovalida'], $i_idestablocal);
//            $this->SetFont('Arial','B',11);
//            $this->Cell(55,5,utf8_decode('PARAMETRO'),0,0,'L', true);
//            $this->Cell(30,5,utf8_decode('RESULTADO'),0,0,'L', true);
//            $this->Cell(50,5,utf8_decode('OBSERVACIONES'),0,0,'L', true);
//            $this->Cell(30,5,utf8_decode('UNIDADES'),0,0,'L', true);
//            $this->Cell(30,5,utf8_decode('RANGO'),0,1,'L', true);
//            $this->SetFont('Arial','',10);       
//            $this->SetWidths(array(55, 30, 50, 30,15,15));
//            $this->SetX(15);
//           // $this->Row(array(utf8_decode('PARAMETRO'),  utf8_decode('RESULTADO'),utf8_decode('OBSERVACIONES'),utf8_decode('OBSERVACIONES'),utf8_decode('UNIDADES'),  utf8_decode('RANGO')));
//               while ($row3=@pg_fetch_array($resulprueba)){
//                    $iddet=$row3['iddet'];
//                    $idexres=$row3['idexaresultado'];
//                    if (is_numeric($row3['v_resultado'])) {
//                        $res=(round($row3['v_resultado'], 2));
//                    }
//                    else
//                    {
//                        $res=$row3['v_resultado'];
//                    }
//                    $valornormalres=$objeto->valornormalresul($row3['id_pruebadetsol'], $idpersona, $i_idestablocal);
//                    $row4=@pg_fetch_array($valornormalres);
//                    $unidades=$row4['v_unidades'];
//                    $valorinicial=$row4['v_valorinicial'];
//                    $valorfinal=$row4['v_valorfin'];
//                    if ($row3['b_examreporta']=='f'){
//                         $this->SetFont('Arial','I',10);      
//                    }
//                    else{
//                        $this->SetFont('Arial','',10);     
//                    }
//                     if ($row3['id_codigoresultado']==3){
//                        $this->SetTextColor(82,0,0);
//                        $res='*'.$res;
//
//                     }
//                     else
//                     {
//                        $this->SetTextColor(0,0,0);
//                        $res=' '.$res;
//                      }
//         
//                    $this->SetFillColor(255,255,255);
//                    $this->SetX(10);
//                    $this->Row1(array(utf8_decode($row3['v_nombreprueba']),$res,utf8_decode($row3['v_observaexaresultado']),utf8_decode($unidades),$valorinicial,$valorfinal));
//                    $this->SetTextColor(0,0,0);         
//                    
//                    $elemresult=$objeto->elementosresult($idexres, $i_idestablocal);
//                    while ($row5=@pg_fetch_array($elemresult)){
//                        //elementos
//                        $idelem=$row5['idelesub'];
//                        $idexaresultado=$row5['idexa'];
//                        $resultadoselemsub=$objeto->resultadoselemsub($idelem, $idexaresultado, $i_idestablocal); 
//                        $rowres=@pg_fetch_array($resultadoselemsub);
//
//                        $valornormalelemsubres=$objeto->valnormalelemsub($idelem, $idpersona, $idexaresultado, $i_idestablocal);
//                        $row6=@pg_fetch_array($valornormalres);
//
//                        $v_resultadoelemsub=$rowres['v_resultadoelemsub'];
//                        $v_observaelesubresultado=$rowres['v_observaelesubresultado'];
//                        $i_codigoresultado=$rowres['i_codigoresultado'];
//                        $unidadese=$row6['v_unidades'];
//                        $valoriniciale=$row6['v_valorinicial'];
//                        $valorfinale=$row6['v_valorfin'];
//
//                         if (is_numeric($v_resultadoelemsub)) {
//                                $res2=(round($v_resultadoelemsub, 2));
//                            }
//                         else
//                            {
//                                $res2=$v_resultadoelemsub;
//                            }
//                        if ($row5['b_elemsubreporta']=='f'){
//                            $this->SetFont('Arial','U',10);
//                            //$this->$Ln(2);
//                            
//                        }
//                        else{
//                            $this->SetFont('Arial','',10);
//                        }
//                        
//                        if ($i_codigoresultado==3){
//                            $this->SetTextColor(82,0,0);
//                            $res2='*'.$res2;
//                        }
//                        else
//                        {
//                            $this->SetTextColor(0,0,0);
//                            $res2=' '.$res2;
//                        }
//                        $this->SetFillColor(255,255,255);
//                        $this->SetX(10);
//                        $this->Row1(array(utf8_decode($row5['v_nombrelemsub']),  utf8_decode($res2),utf8_decode($v_observaelesubresultado),utf8_decode($unidadese),$valoriniciale,$valorfinale));
//                        if ($row5['b_elemsubreporta']=='f'){
//                            $ye=$this->GetY();
//                            $this->SetDrawColor(4,79,144);
//                            $this->Line(10,$ye,190,$ye);
//                            //$this->$Ln(2);
//                            
//                        }
//                        else{
//                            $this->SetFont('Arial','',10);
//                        }
//                        
//                         $this->SetFont('Arial','',10);
//                        $this->SetTextColor(0,0,0);       
//                        
//                        $subelemresult=$objeto->subelementosresult($idelem, $idexaresultado, $i_idestablocal);
//                       while ($row7=@pg_fetch_array($subelemresult)){
//                        $idsubelem=$row7['idsubele'];
//                        $resultadosub=$objeto->resultadoselemsub($idsubelem, $idexaresultado, $i_idestablocal); 
//                        $rowsub=@pg_fetch_array($resultadosub);
//
//                        $valornormalelemsubres=$objeto->valnormalelemsub($idsubelem, $idpersona,$idexaresultado, $i_idestablocal);
//                        $row8=@pg_fetch_array($valornormalelemsubres);
//                        $unidadeses=$row8['v_unidades'];
//                        $valoriniciales=$row8['v_valorinicial'];
//                        $valorfinales=$row8['v_valorfin'];
//
//
//                       if (is_numeric($rowsub['v_resultadoelemsub'])) {
//                           $res3=(round($rowsub['v_resultadoelemsub'], 2));
//                       }
//                       else
//                       {
//                           $res3=$rowsub['v_resultadoelemsub'];
//                       }
//
//                       if ($rowsub['i_codigoresultado']==3){
//                           $this->SetTextColor(82,0,0);
//                            $res3='*'.$res3;
//                       }
//                       else
//                       {
//                           $this->SetTextColor(0,0,0);
//                            $res3=' '.$res3;
//                       }
//                         $this->Row1(array(utf8_decode($row7['v_nombrelemsub']),  utf8_decode($res3),utf8_decode($rowsub['v_observaelesubresultado']),utf8_decode($unidadeses),$valoriniciales,$valorfinales));    
//                           
//                       }//fin row7
//                     $this->Ln(2);
//                    }//fin row5
//                     $this->Ln(2);
//                    
//         
//                    
//            } //Fin $row3  
//             $this->SetTextColor(0,0,0);   
//            //$this->Ln(5);
//            $this->Cell(175,5,utf8_decode('Validado: '.$row2["nombre"].' - '.$row2["v_juntavigilancia"]),0,1,'R');
//      
//          } //fin while row2 empleval       
//
//
//   	}//fin funcion pdfRequi
//
//}//fin clase
//
//
//$pdf=new PDF('P', 'mm', 'Letter'); //p vertical
//
//$pdf->AliasNbPages();
//$ban=0;
//$grupopruecon=$objeto->grupoprueconsul($i_idsolicitud, $i_idestablocal);
//                                                        
//while ($row=@pg_fetch_array($grupopruecon)){
//    $pdf->AddPage();
//    $pdf->pdfid($i_idsolicitud, $row['id'], $row['v_grupoprueba'], $row['v_codgrupoprueba'], $i_idestablocal);
//}
//$pdf->pdfid($i_idsolicitud);
//$pdf->Output();
?>