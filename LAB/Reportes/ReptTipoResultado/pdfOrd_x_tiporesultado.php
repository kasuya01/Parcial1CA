<?php
require('../../../pdfg.php');
include_once("clsReporteTiporesultado.php");
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
                   $this->Image('../../../Imagenes/escudo1.jpeg', 260,8,20,15);
                   //Fuente
                   $this->SetFont('Arial','B',12);
                   //Titulos
                   $this->SetFillColor(200,220,255);
                   $this->Cell(0,6,'Ministerio de Salud ',0,1,'C');
                   $this->SetFont('Arial','B',10);
                   $this->Cell(0,6,'Sistema de Informacion de Atencion de Pacientes',0,1,'C');
                   $this->Ln(2);
                   $this->SetFont('Arial','B',8);
                   $this->Cell(0,1,'Reporte Por Tipo de Resultado',0,1,'C');
                   $this->Ln(1);
                   $this->SetFont('Arial','B',7);
                   $this->Cell(0,1,'',0,1,'C');
                   //Line(X1, Y1, X2, Y2);

                   $this->SetFont('Arial','B',7);
                   //Fecha
                   $date = date ("j/m/Y"); 
                   $this->Text(240,20,$date);// si es vertical 240
                   $this->Line(15,28,270,28); 

                   //Se empiezan a escribir los datos del paciente
                   $this->SetFont('Arial','B',7);
                   $this->Cell(0,6,'Establecimiento '. utf8_decode($establee),0,1,'C');
                   $this->SetFont('Arial','B',10);
           //Cabecera de tabla
                   $this->Ln(5);
                   //Aqui la relaciona con encabezado de uno.php ubicado en /sigep/uno.php
                   parent::Header();
           }
           //Pie de Pagina
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
}

$pdf=new PDF('L'); //p vertical
$pdf->AliasNbPages();
$pdf->AddPage();
$idarea    =$_GET['idarea'];
$idexamen   =$_GET['idexamen'];
$fechainicio=$_GET['fechaini'];
$fechafin   =$_GET['fechafin'];
$cond1="";


if (!empty($idarea)) {
   $cond1 .= " t05.id = " . $idarea. " AND";
   //$cond1 .= " t05.id = " . $_POST['idarea'] . " AND";
}
if (!empty($idexamen)) {
$cond1 .= " t03.id = " . $idexamen . " AND";
//$cond1 .= " t03.id = " . $_POST['idexamen'] . " AND";
}


if ((!empty($fechainicio)) and (!empty($fechafin)))
{ $cond1.= "   (t01.fecha_resultado >='".$fechainicio."' AND t01.fecha_resultado <='".$fechafin."')         ";
 // $cond2.= " AND  (t02 .fechahorareg >='".$ffechaini."' AND t02 .fechahorareg <='".$ffechafin."')    AND     ";
}


//consulta todos los registros
$objeto = new clsReporteTiporesultado(); 
$sql = $objeto->reporte_tiporesultado($idarea, $idexamen,$fechainicio, $fechafin, $cond1);  
$pdf->Cell(0,1,'Reporte de Tipos de Resultados ( '.$fechainicio.' / '.$fechafin.')',0,1,'C');
$pdf->Ln(5);

//$cond1 = substr($cond1, 0, strlen($query) - 3);
//echo '<br>Cond1: '.$cond1.' postarea: '.$_GET['idarea'].'<br>';

$pdf->Ln(5);
$pdf->SetFont('Arial','I',10 );
$pdf->AddCol('v_nombreprueba',50,'Prueba','L'); //25
//$pdf->AddCol($sql,50,$sql,'L'); //25
////
$consulta=$objeto->codigoresultado();
 while ($consulcodigo=pg_fetch_array($consulta))
                        {
                        $pdf->AddCol('codigo'.$consulcodigo['id'],23,$consulcodigo['resultado'],'L'); //15                            
			}
$pdf->AddCol('total',20,'Total','L'); //25                           
$prop=array('HeaderColor'=>array(255,100,100),
            'color1'=>array(210,245,255),
            'color2'=>array(255,255,210),
            'padding'=>2 );		
$pdf->Table($sql,$prop);

ob_end_clean();
$pdf->Output();
?>