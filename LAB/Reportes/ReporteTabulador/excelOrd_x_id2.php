<?php
error_reporting(E_ALL);
include_once '../../../public/PHPExcel/Classes/PHPExcel.php';
include_once '../../../public/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';
/** PHPExcel */
//include_once '../../../../default/PHPExcel/Classes/PHPExcel.php';
//include_once '../../../../default/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';
/** Incluir la libreria PHPExcel */
//require_once '../Classes/PHPExcel.php';
include_once("clsReporteTabuladores.php");
@session_start();
//ob_start();
//consulta todos los registros
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$lugar_name=$_SESSION['nombre_estab'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
//
$objeto = new clsReporteTabuladores();
$d_fecha=$_POST['d_fecha'];
$idarea=$_POST['cmbArea'];
$idmntareamodestab=$_POST['idmntareamodestab'];
$nombareamodestab=$_POST['nombareamodestab'];
//
//echo '<br>Area post_'.$idarea;
//echo '<br>dfecha_'.$d_fecha;
//echo '<br>idmntareamodestab_'.$idmntareamodestab.'<br>';
$reamde="";
$nominsti="";
if($idmntareamodestab==""){
   $insti=$objeto->buscarinstitucion($lugar);
   $cantins=@pg_num_rows($insti);
//   if ($cantins==1){
//      $rmntame=  pg_fetch_array($insti);
//      $idmntareamodestab=$rmntame['id'];
//      $reamde.=$idmntareamodestab.",";
//      $nominsti.=$rmntame['institucion'].",";
//      $cantis=1;
//   }
//   else{

      while ($rmntame=  pg_fetch_array($insti)){
         $idmntareamodestab=$rmntame['id'];
         $reamde.=$idmntareamodestab.",";
         $nombreamd=$rmntame['institucion'];
         $nominsti.=$nombreamd.",";
     // }
      }
}
else{
   $nombrearea=$objeto->nombreinstitucion($idmntareamodestab);
   $namestab=  pg_fetch_array($nombrearea);
   $reamde.=$idmntareamodestab.",";
   $nominsti.=$namestab['institucion'].",";
   $cantins=1;
}
//echo '<br>Vector:  '.count(array_filter(explode(',',$reamde))).'<br>Recmnarmoest: '.$reamde;
//echo 'Area post_'.$idarea;
//$idexamen=$_POST['cmbExamen'];

//$idpruebas=$_POST['id_prueba'];

//echo '<br>idpruebas_'.$idpruebas.' count_'.$pruebas;
if ($idarea=='' || $idarea==NULL){
  // echo 'entro';
    $idarea=0;
}
if(!isset($_POST['d_fecha'])|| $d_fecha=="")
	{
	$d_fecha=date('Y-m');
	}



$ame=0;
//echo '<br>readmeantes:'.$reamde;
//echo '<br>nombre:'.$nominsti;
$exreadme=  explode(',',$reamde);
$nomreadme=  explode(',',$nominsti);
//
//for ($ame=0; $ame <count(array_filter($exreadme)); $ame++){
//
//}
list($year, $month) = explode('-', $d_fecha);
$num = cal_days_in_month(CAL_GREGORIAN, $month, $year); // 31


//
$filename = "Tabulador ".$mes.' '.$year. ".xlsx"; //GUARDANDO CON ESTE NOMBRE


$objPHPExcel = new PHPExcel();
for ($ame=0; $ame <count(array_filter($exreadme)); $ame++){
   $idame=$exreadme[$ame];
   $objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex($ame);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO)->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->setTitle($nomreadme[$ame]);

$styleArray = array(
       'borders' => array(
             'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                    ),
              ),
       );
$styleArray1=array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
            'size'  => 7,
            'name'  => 'Verdana'
        ),
    );
$styleArray2=array(
    'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '000000'),
            'size'  => 7,
            'name'  => 'Verdana'
        ),
    );
$styleArray3=array(
    'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '000000'),
            'size'  => 5.5,
            'name'  => 'Verdana'
        ),
    );
   $objPHPExcel->getActiveSheet()->getStyle('A1:AEZ5')->applyFromArray($styleArray1);
   $objPHPExcel->getActiveSheet()->getStyle('A3:A39')->applyFromArray($styleArray1);
   $objPHPExcel->getActiveSheet()->getStyle('B6:AEZ40')->applyFromArray($styleArray2);
   for ($col = 'A'; $col != 'AEZ'; $col++) {
       $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth(3.05);
   }//fin for columnas aa a zz
   $ini='B';
   $fin=$ini+14;
   $startletra='A';
   $letini='B';
   $unresul='0';
   $o=5;
   $todos=$o+$num;
   $final=$todos+1;
   $var=0;
   //Desde aqui debo de configurar
   $idpruebas= isset($_POST['cmbExamen']) ? $_POST['cmbExamen'] : null;
$pruebas=count($idpruebas);
/*echo $idpruebas[0].'-'.$idpruebas[1].' - '.$pruebas.' - '.$_POST['cmbExamen'];
exit();*/
//Si ha seleccionado algunas pruebas del catalogo
if ($pruebas==0){ //Si no selecciono ninguna prueba por defecto le cargara el reporte de todas las que tengan en el catalogo
    $prues=$objeto->prxmes($month, $year, $lugar, $idarea, $idame);
    $pruebas=@pg_num_rows($prues);
    $var=0;
    while($resultser=@pg_fetch_array($prues)){
       $idpruebas[$var]=$resultser['id_pruebadetsol'];
       $var++;
    }
}
//if ($pruebas==0){ //Si no selecciono ninguna prueba por defecto le cargara el reporte de todas las que tengan en el catalogo
//    $prues=$objeto->prxmes($month, $year, $lugar);
//    $pruebas=@pg_num_rows($prues);
//    $var=0;
//    while($resultser=@pg_fetch_array($prues)){
//       $idpruebas[$var]=$resultser['id_pruebadetsol'];
//       $var++;
//    }
//}
//if ($pruebas!=0){

 $cantcolafec=$pruebas*14; //--14 columnas
    for ($i=0; $i<$pruebas; $i++){
        $idpr=$idpruebas[$i];
        $rowprueba= @pg_fetch_array($objeto->pruebasid($idpr));
        $vcodprue=$rowprueba['v_codprueba'];


            $codpro=0;
            $codres=0;
            for ($j=1; $j<=14; $j++){
                $startletra++;

                if ($j<=9){
                    $codres++;
                    $objPHPExcel->getActiveSheet()->SetCellValue($startletra.'5', $codres);
                    for ($n=1;$n<=$num; $n++){
                        $o++;
                        $objPHPExcel->getActiveSheet()->getRowDimension($n)->setRowHeight(12.5);
                        //manda a consultar la cantidad de resultados de acuerdo a :
                        //$idpr= id de la prueba; $j=codigo de la prueba que este pasando,
                        //$n=dia por el que esta pasando;  $month=mes, $year=año
                        $resultcant=@pg_fetch_array($objeto->prxdia($idpr, $j,$n, $month, $year, $lugar, $idame));
                        $valor=$resultcant['res'];
                        if ($valor==0){
                            $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, '');
                        }
                        else{
                            $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, $valor);
                        }
                    }
                    $o=5;
                    $n=1;
                }
                if ($j>9){
                    $codpro++;

                    $objPHPExcel->getActiveSheet()->SetCellValue($startletra.'5', $codpro);
                    for ($n=1;$n<=$num; $n++){
                        $o++;
                     //   echo 'anio: '.$year;
                        //manda a consultar la cantidad de resultados de acuerdo a :
                        //$idpr= id de la prueba; $j=codigo de establecimiento,
                        //$n=dia por el que esta pasando;  $month=mes, $year=año
                        $resultser=@pg_fetch_array($objeto->prxservicio($idpr, $lugar,$n, $month, $year, $idame));
                        if ($codpro==1){
                            $codser="uno";
                            $resuno=$resultser['uno'];
                            if ($resuno==0){
                                $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, '');
                            }
                            else{
                                $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, $resuno);
                            }
                        } else if ($codpro==2){
                            $codser="dos";
                            $resdos=$resultser['dos'];
                            if ($resdos==0){
                              $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, '');
                            }
                            else{
                                $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, $resdos);
                            }
                        } else if ($codpro==3){
                            $codser="tres";
                            $restres=$resultser['tres'];
                            if ($restres==0){
                              $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, '');
                            }
                            else{
                              $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, $restres);
                            }
                        } else if ($codpro==4){
                            $codser="cuatro";
                            $resultser4=@pg_fetch_array($objeto->prxservicioref($idpr, $lugar,$n, $month, $year, $idame));
                            $rescuatro=$resultser4['cuatro'];
                            if ($rescuatro==0){
                                $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, '');
                            }
                            else{
                                $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, $rescuatro);
                            }

                        } else if ($codpro==5){
                            $codser="cinco";
                        /*    $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, '');*/
                        $restres=$resultser['cinco'];
                        if ($restres==0){
                          $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, '');
                        }
                        else{
                          $objPHPExcel->getActiveSheet()->SetCellValue($startletra.$o, $restres);
                        }
                        }
                    }
                    $o=5;
                    $n=1;

                }
                if ($j==9){
                     $unresul=$startletra;
                }

                $starletrafin=$startletra;
                $objPHPExcel->getActiveSheet()->getStyle($startletra.$final)->applyFromArray($styleArray1);
                $objPHPExcel->getActiveSheet()->setCellValue($startletra.$final, '=SUM('.$startletra.'6:'.$startletra.$todos.')');
            }
//            $starletrafin='D';
//            $supto=15;
          $supto=$final;
          $supto++;
          $objPHPExcel->setActiveSheetIndex($ame)->mergeCells($letini.$supto.':'.$starletrafin.$supto);
          $rowto= @pg_fetch_array($objeto->pruebatotallab($idpr, $month,$year,$lugar, $idame));
          $totodos=$rowto['total'];
          $objPHPExcel->getActiveSheet()->SetCellValue($letini.$supto, $totodos);
          $objPHPExcel->getActiveSheet()->getStyle($letini.$supto)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($letini.$supto)->applyFromArray($styleArray1);
         // $final=$final-1;
         $objPHPExcel->getActiveSheet()->getStyle($letini.'3:'.$starletrafin.'3')->getFont()->setSize(7);
         $objPHPExcel->setActiveSheetIndex($ame)->mergeCells($letini.'3:'.$starletrafin.'3');
         $objPHPExcel->getActiveSheet()->SetCellValue($letini.'3', 'Código de Prueba: '.$vcodprue);
        $objPHPExcel->setActiveSheetIndex($ame)->mergeCells($letini.'4:'.$unresul.'4');
         $objPHPExcel->getActiveSheet()->SetCellValue($letini.'4', 'Resultado');
         $unresul++;
        $objPHPExcel->setActiveSheetIndex($ame)->mergeCells($unresul.'4:'.$starletrafin.'4');
        $objPHPExcel->getActiveSheet()->SetCellValue($unresul.'4', 'Servicio de Procedencia');
            $starletrafin++;
            $letini=$starletrafin;
            $unresul='0';
        }

        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$supto, 'TOTAL');
$final++;
$objPHPExcel->setActiveSheetIndex($ame)->mergeCells('A3:A5');
$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'DIA');
    $k=5; //5 xq va despues de todos los encabezados
for ($i=1;$i<=$num; $i++){
    $k++;
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$k, $i);
}
$k++;
 $objPHPExcel->getActiveSheet()->SetCellValue('A'.$k, 'Total');
$j=0;
if ($pruebas>0){
}

if ($month==01){
$mes="Enero";
} else if ($month==02){
 $mes="Febrero";
} else if ($month==03){
 $mes="Marzo";
} else if ($month==04){
 $mes="Abril";
} else if ($month==05){
 $mes="Mayo";
} else if ($month==06){
 $mes="Junio";
} else if ($month==07){
 $mes="Julio";
} else if ($month==08){
 $mes="Agosto";
} else if ($month==09){
 $mes="Septiembre";
} else if ($month==10){
 $mes="Octubre";
} else if ($month==11){
 $mes="Noviembre";
} else if ($month==12){
 $mes="Diciembre";
}
//Poner Borde hasta la ultima fila
$objPHPExcel->getActiveSheet()->getStyle('A3:'.$starletrafin.$supto)->applyFromArray($styleArray);
$final++;
$objPHPExcel->setActiveSheetIndex($ame)->mergeCells('B'.$final.':'.'AZ'.$final);
$objPHPExcel->getActiveSheet()->getStyle('A'.$final.':'.$starletrafin.$final)->applyFromArray($styleArray3);
  $objPHPExcel->getActiveSheet()->getStyle('A'.$final.':'.$starletrafin.$final)->getFont()->setSize(5);
//$objPHPExcel->getActiveSheet()->SetCellValue('B'.$final, 'Resultados: 1 NORMAL, 2 NEGATIVO, 3 ANORMAL, 4 POSITIVO, 5 MUESTRA INADECUADA, 6 OTROS, 7 REACTIVO, 8 INDETERMINADO Y 9 NO REACTIVO             PROCEDENCIA: 1 CONSULTA EXTERNA, 2 HOSPITALIZACION, 3 EMERGENCIA, 4 REFERIDO  Y 5 OTROS'  );

   //Hasta aqui debo de configurar


   $objPHPExcel->getActiveSheet()->freezePane('A6');
   $objPHPExcel->getActiveSheet()->getPageSetup()->setColumnsToRepeatAtLeftByStartAndEnd('A');
   $final=$final+2;
   $t2="\t\t\t\t";

   $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&C&H&C&9 MINISTERIO DE SALUD'."\n".' Tabulador Diario de Actividades de laboratorio Clinico'."\n".'Institución:'.$nomreadme[$ame].' ( X )'."\n".'Establecimiento:'.$lugar_name."\t\t\t\t".'Sección:        '."\t\t\t".'Mes:'.$mes."\t".'Año: '.$year);
   $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&C&H&5Resultados: 1 NORMAL, 2 NEGATIVO, 3 ANORMAL, 4 POSITIVO, 5 MUESTRA INADECUADA, 6 OTROS, 7 REACTIVO, 8 INDETERMINADO y 9 NO REACTIVO             PROCEDENCIA: 1 CONSULTA EXTERNA, 2 HOSPITALIZACION, 3 EMERGENCIA, 4 REFERIDO  Y 5 OTROS'."\n \n \n \n \n \n \n \n \n".'&C&H&9Nombre del Responsable '.$t2.'Firma  del Profesional de laboratorio  '.$t2.' JVPLC &R&8 '."\n \n \n \n".'Pag. &P of &N');
   // Rename sheet
   $pageMargins = $objPHPExcel->getActiveSheet()->getPageMargins();

   // margin is set in inches (0.5cm)
   $margin = 0.8;
   $pageMargins->setTop($margin);
   $pageMargins->setBottom(1.10);
   $pageMargins->setLeft(0.4);
   $pageMargins->setRight(0.4);
   $pageMargins->setHeader(0.3);
   $pageMargins->setFooter(0.3);
   $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
   $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(0);
   $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(1);

}//Fin del for


//

//De aqui en adelante ya lo puse
//$objPHPExcel->getActiveSheet()->freezePane('A6');
//$objPHPExcel->getActiveSheet()->getPageSetup()->setColumnsToRepeatAtLeftByStartAndEnd('A');
//$final=$final+2;
//$t2="\t\t\t\t";
//$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&C&H&C&9 MINISTERIO DE SALUD'."\n".' Tabulador Diario de Actividades de laboratorio Clinico'."\n".'Instituciones:  MINSAL ( X )    FOSALUD ()       Convenio ISSS()'."\n".'Establecimiento:'.$lugar_name."\t\t\t\t\t\t".'Sección:        '."\t\t\t\t".'Mes:'.$mes."\t".'Año: '.$year);
////objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('Resultados: 1 NORMAL, 2 NEGATIVO, 3 ANORMAL, 4 POSITIVO, 5 MUESTRA INADECUADA, 6 OTROS, 7 REACTIVO, 8 INDETERMINADO Y 9 NO REACTIVO             PROCEDENCIA: 1 CONSULTA EXTERNA, 2 HOSPITALIZACION, 3 EMERGENCIA, 4 REFERIDO  Y 5 OTROS');
////$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&C&H&9Nombre del Responsable '.$t2.'Firma  del Profesional de laboratorio  '.$t2.' JVPLC &R&8 '."\n \n".'Pag. &P of &N');
//$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&C&H&5Resultados: 1 NORMAL, 2 NEGATIVO, 3 ANORMAL, 4 POSITIVO, 5 MUESTRA INADECUADA, 6 OTROS, 7 REACTIVO, 8 INDETERMINADO y 9 NO REACTIVO             PROCEDENCIA: 1 CONSULTA EXTERNA, 2 HOSPITALIZACION, 3 EMERGENCIA, 4 REFERIDO  Y 5 OTROS'."\n \n \n \n \n \n \n \n \n".'&C&H&9Nombre del Responsable '.$t2.'Firma  del Profesional de laboratorio  '.$t2.' JVPLC &R&8 '."\n \n \n \n".'Pag. &P of &N');
//// Rename sheet
//$pageMargins = $objPHPExcel->getActiveSheet()->getPageMargins();
//
//// margin is set in inches (0.5cm)
//$margin = 0.8;
//$pageMargins->setTop($margin);
//$pageMargins->setBottom(1.10);
//$pageMargins->setLeft(0.4);
//$pageMargins->setRight(0.4);
//$pageMargins->setHeader(0.3);
//$pageMargins->setFooter(0.3);
///*$pageMargins->setLeft($margin);
//$pageMargins->setRight($margin);*/
//$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
//$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(0);
//$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(1);
////$objPHPExcel->getActiveSheet()->getHeaderFooter()->setAlignWithMargins(true);
//Hasta aqui me lleve
//
//// Save Excel 2007 file
//
//
////  //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
////  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
////  $objWriter->save('php://output');
////   exit();
////// Echo done
//
//
//
//
//
//// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="pruebaReal.xlsx"');
//header('Cache-Control: max-age=0');
ob_end_clean();
//$filename = "Tabulador ".$mes.' '.$year. ".xlsx"; //GUARDANDO CON ESTE NOMBRE
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
//header("Content-Disposition: attachment; filename=abc.xlsx");  //
header("Content-Disposition: attachment;filename=Tabulador_".$month."-".$year.".xlsx");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save('php://output');
//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
//}
//echo "<br><br/><br><br><font color='#C35617' align='center'><center><img src='../../../Imagenes/alerta.png' valign='middle'/>  NO EXISTEN REGISTROS RELACIONADOS CON LA B&Uacute;SQUEDA<br/><br/></center></font>";
//
//// Establecer propiedades
//$objPHPExcel->getProperties()
//->setCreator("Cattivo")
//->setLastModifiedBy("Cattivo")
//->setTitle("Documento Excel de Prueba")
//->setSubject("Documento Excel de Prueba")
//->setDescription("Demostracion sobre como crear archivos de Excel desde PHP.")
//->setKeywords("Excel Office 2007 openxml php")
//->setCategory("Pruebas de Excel");
//
//// Agregar Informacion
//$objPHPExcel->setActiveSheetIndex(0)
//->setCellValue('A1', 'Valor 1')
//->setCellValue('B1', 'Valor 2')
//->setCellValue('C1', 'Total')
//->setCellValue('A2', '10')
//->setCellValue('C2', '=sum(A2:B2)');
//
//// Renombrar Hoja
//$objPHPExcel->getActiveSheet()->setTitle('Tecnologia Simple');
//
//// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
//$objPHPExcel->setActiveSheetIndex(0);
//
//// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
////header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
////header('Content-Disposition: attachment;filename="pruebaReal.xlsx"');
////header('Cache-Control: max-age=0');
//
////$filename = "Tabulador ".$mes.' '.$year. ".xlsx"; //GUARDANDO CON ESTE NOMBRE
//header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
////header("Content-Disposition: attachment; filename=abc.xlsx");  //
//header("Content-Disposition: attachment;filename=Tabulador_".$month."-".$year.".xlsx");
//header("Expires: 0");
//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//header("Cache-Control: private",false);
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
////$objWriter->save('php://output');
////$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//$objWriter->save('php://output');
//exit;




////Crear un objeto de excel
//$objPHPExcel = new PHPExcel();//crea un nuevo objeto de excel
//for ($ame=0; $ame <2; $ame++){
//   $objPHPExcel->createSheet($ame);
//$objPHPExcel->setActiveSheetIndex($ame);
//$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
////$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);
//$objPHPExcel->getActiveSheet()->setTitle($ame);
//$styleArray = array(
//       'borders' => array(
//             'allborders' => array(
//                    'style' => PHPExcel_Style_Border::BORDER_THIN,
//                    'color' => array('argb' => '000000'),
//                    ),
//              ),
//       );
//$styleArray1=array(
//    'font'  => array(
//        'bold'  => true,
//        'color' => array('rgb' => '000000'),
//            'size'  => 7,
//            'name'  => 'Verdana'
//        ),
//    );
//$styleArray2=array(
//    'font'  => array(
//        'bold'  => false,
//        'color' => array('rgb' => '000000'),
//            'size'  => 6,
//            'name'  => 'Verdana'
//        ),
//    );
//$styleArray3=array(
//    'font'  => array(
//        'bold'  => false,
//        'color' => array('rgb' => '000000'),
//            'size'  => 5.5,
//            'name'  => 'Verdana'
//        ),
//    );
////$objPHPExcel->getActiveSheet()->getStyle('A1:HZ5')->applyFromArray($styleArray1);
////$objPHPExcel->getActiveSheet()->getStyle('A3:A39')->applyFromArray($styleArray1);
////$objPHPExcel->getActiveSheet()->getStyle('B6:HZ40')->applyFromArray($styleArray2);
////for ($col = 'A'; $col != 'HZ'; $col++) {
////	//$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
////    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth(4.25);
////}
////    $ini='B';
////    $fin=$ini+14;
////$startletra='A';
////$letini='B';
////$unresul='0';
////$o=5;
////$todos=$o+$num;
////$final=$todos+1;
////$var=0;
////
////$pageMargins = $objPHPExcel->getActiveSheet()->getPageMargins();
////
////// margin is set in inches (0.5cm)
////$margin = 0.8;
////$pageMargins->setTop($margin);
////$pageMargins->setBottom($margin);
////$pageMargins->setHeader(0.3);
////$pageMargins->setFooter(0.3);
/////*$pageMargins->setLeft($margin);
////$pageMargins->setRight($margin);*/
////$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
////$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(0);
////$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(1);
//
//
//}//fin for
//
//// Save Excel 2007 file
//
//
//  $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//  $objWriter->save('php://output');
//   exit();
//// Echo done
//

//ob_end_clean();
?>
