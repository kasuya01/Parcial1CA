<?php
require('../../../pdfg.php');
include_once("clsReporteTiporesultado.php");
//include_once("../cSolicitud.php");
//@session_start();
////consulta todos los registros
//$objeto = new cSolicitud(); 
//$ingre= $_SESSION["s_ingresar"];
//$consu= $_SESSION["s_consultar"]; 
//$elimi= $_SESSION["s_eliminar"];		
//$actua= $_SESSION["s_actualizar"]; 
//$rol = $_SESSION["s_rol"];
//$id_nivel= $_SESSION["s_nivelacceso"];
//$id_region=$_SESSION['s_idregion'];
//$region=$_SESSION['s_region'];
//$idsibasi=$_SESSION['s_idsibasi'];
//$sibasi=$_SESSION['s_sibasi'];  
//$idpadre=$_SESSION['s_idpadre'];  
//$idtipoestab=$_SESSION["s_idtipoestab"];
//$idestable=$_SESSION["s_idestablec"];
//
//$id_sibasi= $_GET['id_sibasi'];
//$id_establecimiento=$_GET['id_establecimiento'];
////$v_numexpediente=$_GET['v_numexpediente'];
//$fecha1=$_GET['fecha1'];
//$fecha2=$_GET['fecha2'];
//$id_grupoprueba=$_GET['id_grupoprueba'];
//$id_prueba=$_GET['id_prueba'];
//$id_sexo=$_GET['id_sexo'];
//
//
//if ($id_nivel==3  && $id_region!=""){
//    $regionid=$id_region;
//}
//else{
//    $regionid=0;
//}
//if(!isset($_GET['id_sexo']))
//	{
//	$id_sexo=0;
//	}
//if(!isset($_GET['id_sibasi']))
//	{
//	$id_sibasi=0;
//	}
//        
//if(!isset($_GET['fecha2'])|| $fecha2=="")
//	{
//	$fecha2=date('Y-m-d');
//   /*     echo 'fecha2 '.$fecha2;
//        $feco=  date('Y-m-d');
//        echo '<br>.Feco '.$feco;*/
//        
//	}
//if(!isset($_GET['fecha1']) || $fecha1=="")
//	{
//	//$fecha1='1900-01-01'; //si no eligio la opcion de fecha
//     $femu=@pg_fetch_array($objeto->fechamuestra());
//    $fecha1=$femu[0];
//	}
//if(!isset($_GET['id_grupoprueba']))
//	{
//	$id_grupoprueba=0;
//	}
//	
//if(!isset($_GET['id_prueba']))
//	{
//	$id_prueba=0;
//	}
//      //  echo 'idestab before: '.$id_establecimiento.'<br />';
//if(!isset($_GET['id_establecimiento']) || ($id_establecimiento==""))
//	{
//	$id_establecimiento=0;
//	}

class PDF extends PDF_PgSQL_Table
{

function Header()
	{
              //  $establee=$_SESSION["s_establec"];
		//$this->Image('../../../../default/img/logo_mspas.jpeg', 10,8,20,15);
		//$this->Image('../../../../default/img/ins.jpg', 180,8,20,15);
		//Fuente
		$this->SetFont('Arial','B',12);
		//Titulos
		$this->SetFillColor(200,220,255);
		$this->Cell(0,6,'Ministerio de Salud ',0,1,'C');
		$this->SetFont('Arial','B',10);
		$this->Cell(0,6,'Laboratorio Regional',0,1,'C');
		$this->Ln(2);
		$this->SetFont('Arial','B',8);
		$this->Cell(0,1,'Reporte Por Tipo de Resultado',0,1,'C');
		$this->Ln(1);
		$this->SetFont('Arial','B',7);
		$this->Cell(0,1,'',0,1,'C');
		//Line(X1, Y1, X2, Y2);
	
		$this->SetFont('Arial','B',9);
		//Fecha
		$date = date ("j/m/Y"); 
		$this->Text(160,20,$date);// si es vertical 240
		$this->Line(15,28,190,28); 
                
                //Se empiezan a escribir los datos del paciente
                $this->SetFont('Arial','B',7);
		//$this->Cell(0,6,'Establecimiento '. utf8_decode($establee),0,1,'C');
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

$pdf=new PDF('P'); //p vertical
$pdf->AliasNbPages();
$pdf->AddPage();
//
////First table: put all columns automatically
////for($i=0;$i<10;$i++)
//$objeto = new cSolicitud();
//
//$sql = $objeto->consulpagitipores($id_establecimiento, $id_sibasi,$fecha1, $fecha2, $id_grupoprueba,$id_prueba, $id_sexo, $regionid);  
//
////$pdf->Table($sql);
////$pdf->AddPage();
////Second table: specify 3 columns
//$pdf->Cell(0,1,'Reporte de Tipos de Resultados de Pruebas ( '.$fecha1.' / '.$fecha2.')',0,1,'C');
////$pdf->Cell(0,1,,0,1,'C');
////$pdf->MultiCell(150,5,$sql,0,0,'R');
//		
//$pdf->Ln(3);
//$pdf->AddCol('v_nombreprueba',55,'Prueba','L'); //25
//$pdf->AddCol('total',25,'Total','L'); //15
//$pdf->AddCol('normal',25,'Normal','L'); //15
//$pdf->AddCol('anormal',25,'Anormal','L'); //15
//$prop=array('HeaderColor'=>array(255,100,100),
//            'color1'=>array(210,245,255),
//            'color2'=>array(255,255,210),
//            'padding'=>2);
//
//$pdf->Table($sql,$prop);
/*$pdf->pdfRequi($clave, $siaf_i_idestadoaf, $masomenos, $siaf_f_valordolactfijo,
 $fecha, $siaf_d_fechaadactfijo,$porlugar, $siaf_i_idtipolocal,$siaf_i_idlocal, $siaf_i_idemp, $porcate,
 $siaf_i_idtipoaf, $siaf_i_idclasiaf, $donado, $permito);*/
$pdf->Output();
?>