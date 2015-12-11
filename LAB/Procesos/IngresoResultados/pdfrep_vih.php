<?php
require('../../../../pdfg.php');
require('../cReporte.php');


$clave =(int) $_GET['clave'];

//Se crea el objeto $objexcelRequi de clase cRequi

class PDF extends PDF_PgSQL_Table
{

function Header()
	{
	
		//Logo
		$this->Image('../../../../default/img/logo_mspas.jpeg', 10,8,20,15);
		//Fuente
		$this->SetFont('Arial','B',15);
		//Titulos
		$this->SetFillColor(200,220,255);
		$this->Cell(0,6,'Ministerio de Salud ',0,1,'C');
		$this->Ln(1);
		$this->SetFont('Arial','B',8);
		$this->Cell(0,1,'Unidad de Vigilancia  Laboratorial',0,1,'C');
		//Line(X1, Y1, X2, Y2);
	
		$this->SetFont('Arial','B',9);
		//Fecha
		$date = date ("j/m/Y"); 
		$this->Text(180,20,$date);// si es vertical 240
		
		//salto de linea
		$this->Ln(2);

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
    $this->Cell(0,10,html_entity_decode('P&aacute;gina ').$this->PageNo().'/{nb}',0,0,'C');
}
function pdfid($clave)
   	{	
		$numberor = $clave;
 $cant= strlen($clave);
	while ($cant<9)
	{
		$numberor='0'.$numberor;
		$cant= strlen($numberor);
	}
$objeto=new cReporte();

$consultartodos = $objeto->consultarordenid($clave);
$row =@pg_fetch_array($consultartodos);
$cuanto =@pg_num_rows($consultartodos);
$v_numerexpe = $row[1];
$v_nombreestab = $row[3];
$nombre = $row[4];
$d_fechaestresult = $row[7];
$v_medresporden = $row[8];
$d_fechatoma = $row[9];
$d_fechanaci = $row[10];
$v_sexo = $row[11];
$v_resul = $row[12];
$v_obserdet = $row[13];
$v_codprueba = $row[14];
$v_nombprueba = $row[15];
$v_posibleresult = $row[16];
$v_unidmedi = $row[17];
$respon = $row[18];
$v_nombseccion = $row[19];
$i_idestdetord = $row[20];

$totalvalor=0;
	/*$this->Ln(1);
	$this->SetFillColor(200,220,255);
		$this->Cell(0,6,'i_idordenlab '. $row[' i_idordenlab'],0,1,'C');
		$this->Ln(1);*/
	$this->Line(74,19.5,142,19.5);   //si es horizonatal: Line(74,19.5,220,19.5) si es vertical: Line(74,19.5,142,19.5);
		$this->SetFont('Arial','B',5);
		$this->Cell(0,6,'Seccion '. $v_nombseccion,0,1,'C');
		$this->Cell(0,1,'Resultado de '.$v_nombprueba,0,1,'C');
    	//Cabecera de tabla
		  $this->SetFont('Arial','B',11);
  $this->Ln(5);
   $this->Cell(0,5,'No de Orden:   '.$numberor,0,1,'C');
   $this->Ln(10);
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'No de Expediente',1,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(100,5,$v_numerexpe,1,1,'L');
		
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'Nombre',1,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(100,5,$nombre,1,1,'L');
		
		
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'Fecha Nacimiento',1,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(100,5,$d_fechanaci,1,1,'L');
		
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'Sexo',1,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(100,5,$v_sexo,1,1,'L');
		
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'Médico',1,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(100,5,$v_medresporden,1,1,'L');
		
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'Establecimiento',1,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(100,5,$v_nombreestab,1,1,'L');
		
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'Fecha Extracción',1,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(100,5,$d_fechatoma,1,1,'L');
		
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'Fecha Resultado',1,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(100,5,$d_fechaestresult,1,1,'L');
		
		$this->Line(74,19.5,142,19.5);  
		
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'Prueba',1,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(100,5,$v_codprueba.'  ' .$v_nombprueba,1,1,'L');
		
		if ($v_posibleresult==""){
			$resul=$v_resul;
		}
		else{
			if ($v_resul==""){
				$resul=$v_posibleresult;
			}
			else{
			$resul=$v_posibleresult.'  ' .$v_resul;
			}
		}
	
		
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'Resultado',1,0,'L', true);
		 $this->SetFont('Arial','BI',9);
		$this->Cell(100,5,$resul,1,1,'L');
		
		if ($v_obserdet!=" " ){
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'Observacion',1,0,'L', true);
		$this->SetFont('Arial','',9);
		$this->Cell(100,5,$v_obserdet,1,1,'L');
		}
		if ($i_idestdetord==3){
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'Estado',1,0,'L', true);
		$this->SetFont('Arial','I',9);
		$this->Cell(100,5,'Rechazada',1,1,'L');	
		}
		//salto de linea
		$this->Ln(4);
		
		$this->Cell(25);
		$this->SetFont('Arial','B',9);
		$this->SetFillColor(224,235,255);
		$this->Cell(50,5,'Responsable',1,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(100,5,$respon,1,1,'L');
		
   	}//fin funcion pdfRequi

}

$pdf=new PDF('P'); //p vertical
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->pdfid($clave);
$pdf->Output();
?>