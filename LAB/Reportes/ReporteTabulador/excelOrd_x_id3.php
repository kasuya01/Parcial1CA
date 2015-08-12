<?php
/** Include path **/
ini_set('include_path', ini_get('include_path').';../../../public/PHPExcel/Classes/');

/** PHPExcel */
include 'PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include 'PHPExcel/Writer/Excel2007.php';
//
$d_fecha=date('Y-m');  
list($year, $month) = explode('-', $d_fecha);
$num = cal_days_in_month(CAL_GREGORIAN, $month, $year); // 31
//$filename = "Tabulador ".$mes.' '.$year. ".xlsx"; //GUARDANDO CON ESTE NOMBRE
//header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
////header("Content-Disposition: attachment; filename=abc.xlsx");  //
//header("Content-Disposition: attachment;filename=Tabulador_".$month."-".$year.".xlsx");
//header("Expires: 0");
//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//header("Cache-Control: private",false);

//echo date('H:i:s') . " Create new PHPExcel object hayh noooo q ondix con esto\n";
$objPHPExcel = new PHPExcel();//crea un nuevo objeto de excel
//echo date('H:i:s') . " Rename sheet\n";
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
//$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);
$objPHPExcel->getActiveSheet()->setTitle($month.' '.$year);
$objPHPExcel->createSheet(NULL, "xyz");
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setTitle('abc');
// Save Excel 2007 file

  //echo date('H:i:s') . " Write to Excel2007 format\n";
  $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
  $objWriter->save('php://output');
   exit();
//// Create new PHPExcel object
//echo date('H:i:s') . " Create new PHPExcel object\n";
//$objPHPExcel = new PHPExcel();
//
///** PHPExcel */
//include 'PHPExcel.php';
//
///** PHPExcel_Writer_Excel2007 */
//include 'PHPExcel/Writer/Excel2007.php';
//echo date('H:i:s') . " Create new PHPExcel object hayh noooo q ondix con esto\n";
//$objPHPExcel = new PHPExcel();
//// Rename sheet
//echo date('H:i:s') . " Rename sheet\n";
//$objPHPExcel->getActiveSheet()->setTitle('Simple');
//
//$objPHPExcel->createSheet(NULL, "xyz");
//$objPHPExcel->setActiveSheetIndex(1);
//$objPHPExcel->getActiveSheet()->setTitle('abc');
//
//// Save Excel 2007 file
//echo date('H:i:s') . " Write to Excel2007 format\n";
//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
//
//
//
//// Echo done
//echo date('H:i:s') . " Done writing file.\r\n";
?>