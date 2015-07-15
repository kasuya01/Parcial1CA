<?php
require('../../../pdfg.php');
include_once("clsReporteResultados.php");
//include_once("ctrlFuncionesResultados.php");
@session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$objdatos = new clsReporteResultados;
$idexpediente       = $_GET['idexpediente'];
$idsolicitud        = $_GET['idsolicitud'];
$idHistorialClinico = $_GET['idHistorialClinico'];
$idDatoReferencia   = $_GET['idDatoReferencia'];
$idEstablecimiento  = $_GET['IdEstablecimiento'];
$subservicio        = $_GET['subservicio'];

class PDF extends PDF_PgSQL_Table
{

   function Header()
           {
                   $objdatos = new clsReporteResultados;

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
                     $idHistorialClinico = $_GET['idHistorialClinico'];
                     $idDatoReferencia   = $_GET['idDatoReferencia'];
                     $idEstablecimiento  = $_GET['IdEstablecimiento'];
                       if($idDatoReferencia==""){
                
                           $idDatoReferencia=0;

                       }else {
                           $idDatoReferencia=$idDatoReferencia;
                       }

                       if($idHistorialClinico==""){
                           $idHistorialClinico=0;
                       }else{
                           $idHistorialClinico=$idHistorialClinico;
                       }
//                       
//                       $resultgetDatosGenerales =   getDatosGenerales($idHistorialClinico, $idDatoReferencia, $idEstablecimiento); 
                       
       
                  $consulta= $objdatos->obtenerDatosGenerales($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);
                  $row = @pg_fetch_array($consulta);

                   $nombre_establecimiento = $row['nombre_establecimiento'];
                   $procedencia            = $row['procedencia'];
                   $servicio               = $row['servicio'];
                   $nombre_empleado        = $row['nombre_empleado'];
                   $numero_expediente      = $row['numero_expediente'];
                   $nombre_paciente        = $row['nombre_paciente'];
                   $fecha_solicitud        = $row['fecha_solicitud'];
                   $fecha_recepcion        = $row['fecha_recepcion'];

                //Inicio Datos Generales   
	//	$this->Cell(25);
		$this->SetFont('Arial','B',9);
                $this->SetFillColor(255,255,255);
	//	$this->SetFillColor(224,235,255);
		$this->Cell(40,5,'Establecimiento',0,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(65,5,utf8_decode($nombre_establecimiento),0,1,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(40,5,'No. Expediente',0,0,'L', true);
		$this->SetFont('Arial','',9);
		$this->Cell(65,5,$numero_expediente,0,1,'L');
                $this->SetFont('Arial','B',9);
		$this->Cell(40,5,'Nombre Paciente',0,0,'L', true);
		$this->SetFont('Arial','',9);
		$this->Cell(65,5,$nombre_paciente,0,1,'L');
	//	$this->SetFillColor(224,235,255);
                 $this->SetFont('Arial','B',9);
		$this->Cell(40,5,'Procedencia',0,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(65,5,utf8_decode($procedencia),0,0,'L');
		$this->SetFont('Arial','B',9);
		//$this->SetFillColor(224,235,255);
                $this->Cell(40,5,'Origen',0,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(65,5,$servicio,0,1,'L');
		//$this->SetFillColor(224,235,255);		
		$this->SetFont('Arial','B',9);
		$this->Cell(40,5,utf8_decode('Fecha Solicitud'),0,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(65,5,$fecha_solicitud,0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(40,5,utf8_decode('Fecha de Recepción'),0,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(45,5,$fecha_recepcion,0,1,'L');
                $this->SetFont('Arial','B',9);
		//$this->SetFillColor(224,235,255);
                $this->Cell(40,5,utf8_decode('Médico'),0,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(65,5,$nombre_empleado,0,1,'L');
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
                
                $this->Line(5,70,200,70);   //si es horizonatal: Line(74,19.5,220,19.5) si es vertical: Line(5,81,200,81);
		//salto de linea
		$this->Ln(2);
                
                //fin Datos Generales
                     
                   parent::Header();
           }
  //Page footer
   function Footer()
   {
       //Position at 1.5 cm from bottom
       $this->SetY(-15);
       //Arial italic 7
       $this->SetFont('Arial','I',7);
       $this->Cell(75,5,utf8_decode('NOTA: (*) IMPLICA VALOR FUERA DE RANGO DE REFERENCIA'),0,0,'L');
       //Page number
       $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'R');
   }
   
   //Clase donde estan los resultados
   
      function pdfid($i_idsolicitud, $idgrupoprueba, $nombregrupoprueba, $v_codgrupoprueba, $i_idestablocal)
   	{	
         $objdatos = new clsReporteResultados;
         $idexpediente       = $_GET['idexpediente'];
         $idsolicitud        = $_GET['idsolicitud'];
         $idHistorialClinico = $_GET['idHistorialClinico'];
         $idDatoReferencia   = $_GET['idDatoReferencia'];
         $idEstablecimiento  = $_GET['IdEstablecimiento'];
         $subservicio        = $_GET['subservicio'];
         $this->SetFont('Arial','B',12);
		//Titulos
         $this->SetFillColor(200,220,255);
         $ban1=0;
         $this->Cell(0,6, $v_codgrupoprueba. ' - '.utf8_decode($nombregrupoprueba),0,1,'L');
         /* 
        * Impresion de Resutlados
        */
       // $print = '';
        
        
      $resultgetExamnResult    =  $this->getExamnResult($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);
      $this->MuestrasRechazadas($resultgetExamnResult['RM']);

//        if (count($resultgetExamnResult['RC']) > 0) {
//            foreach($resultgetExamnResult['RC'] as $area) {
//             $print.= "<div class='panel panel-info'>
//                                        <div class='panel-heading mouse-pointer' role='tab' id='heading-URI' data-toggle='collapse' >
//                                            <h4 class='panel-title' style='text-align:left'>
//                                                ".$area['nombre']."
//                                            </h4>
//                                        </div>
//                        </div>";
//                 
//                $arrayPlantillas= ['A','B','C','D','E'] ;
//                foreach ($arrayPlantillas as $pType) {
//                    if(array_key_exists($pType, $area['plantillas'])) {
//                        $print .= bodyLayout($area, $pType);
//                    }
//                }
//            }    
//        } else {
//          //  $print = 'Los examenes no han sido procesados aun...';
//             {
//                 $print = " <table > <tr><td colspan='11'><span style='color: #575757;'>Los examenes no han sido procesados aun...</span></td></tr></tbody></table></div>";
//            }
//        }
        
        
      //  echo $print;
        }//fin pdfid	
      /**********************************/
 
 



/*
 * funciones array 
 */

 function getExamnResult($idHistorialClinico, $idDatoReferencia, $idEstablecimiento) {
        $objdatos = new clsReporteResultados;
        //$em = $this->container->get('doctrine')->getManager();
         //$resultados = $em->getRepository('MinsalSeguimientoBundle:SecSolicitudestudios')->obtenerResultadoSolicitudExamen($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);
        if($idHistorialClinico === null || $idHistorialClinico === '') {
            $idHistorialClinico = 0;
        }
        
        if($idDatoReferencia === null || $idDatoReferencia === '') {
            $idDatoReferencia = 0;
        }
        
        $result = $objdatos->obtenerResultadoSolicitudExamen($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);
        if($resultados = pg_fetch_all($result)) {
            $result = array();
            $result['RC'] = array();
            $result['RM'] = array();
            //var_dump($resultados);
            foreach ($resultados as $row) {
                if($row['codigo_estado_detalle'] === 'RM') {
                    $newExam = array(
                            $row['id_examen'],
                            $row['codigo_examen'],
                            $row['nombre_examen']
                        );

                 //   $result['RM'] = addExamnToLayout($result['RM'], $newExam, $row, $row['codigo_plantilla']);
                } else {
        $id = $row['nombre_area'];

        if( ! isset( $result['RC'][$id] ) )
         {
            $result['RC'][$id] = array();
            $result['RC'][$id]['id']     = $row['id_area'];
            $result['RC'][$id]['codigo'] = $row['codigo_area'];
            $result['RC'][$id]['nombre'] = $row['nombre_area'];
        }

        if( ! isset($result['RC'][$id]['plantillas']) ){
        $result['RC'][$id]['plantillas'] = array();
                    }

                    $newPlantilla = array(
                            $row['id_plantilla'],
                            $row['codigo_plantilla'],
                            $row['nombre_plantilla']
                        );

      //  $result['RC'][$id]['plantillas'] = addLayoutToArea( $result['RC'][$id]['plantillas'], $newPlantilla, $row );
                }
    }

            return $result;
        }
//        
    }//fin getExamnResult
  

      //Funcion de Muestras Rechazadas       
     function  MuestrasRechazadas($rm) {
        // $html = '';
        $this->SetFont('Arial','B',11);
        $this->Cell(0,6,'MUESTRAS RECHAZADAS',0,1,'L', true);
        $this->SetFont('Arial','B',11);
        $this->Cell(55,5,utf8_decode('Nombre del Examen'),0,0,'L', true);
        $this->Cell(30,5,utf8_decode('Motivo de Rechazo'),0,1,'L', true);
        $this->SetFont('Arial','',10);       
        $this->SetWidths(array(55, 30, 50, 30,15,15));
        $this->SetX(15);
        $this->SetFillColor(255,255,255);
        $this->SetX(10);


         if(count($rm) > 0) {
             foreach($rm as $examen) {
                 $this->Row1(array(utf8_decode($examen['nombre']),utf8_decode($examen['motivo_rechazo'])));
             }
         } else {
              //$this->Row1(array('No existen examenes rechazados...',''));
             $this->Cell(0,6,'No existen examenes rechazados...',0,1,'L', true);
         }



        $this->SetTextColor(0,0,0);      
         //return $html;                            
     }//Fin muestrarechazada

}//fin de la clase

$pdf=new PDF('p'); //p vertical
$pdf->AliasNbPages();
$grupopruecon=$objdatos->grupoprueconsul($idsolicitud, $lugar);
                                                        
while ($row=@pg_fetch_array($grupopruecon)){
    $pdf->AddPage();
    $pdf->pdfid($idsolicitud, $row['idgrupo'], $row['nombregrupo'], $row['codgrupo'], $lugar);
}
//$pdf->AddPage();
ob_end_clean();
$pdf->Output();

?>