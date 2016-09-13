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

class PDF extends PDF_PgSQL_Table
{

   function Header()
           {
                   $objdatos = new clsReporteResultados;

                   $establee=$_SESSION["nombre_estab"];
                   $this->Image('../../../Imagenes/paisanito.jpeg', 10,8,25,15);
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
		$this->Cell(65,5,utf8_decode($nombre_paciente),0,1,'L');
	//	$this->SetFillColor(224,235,255);
                 $this->SetFont('Arial','B',9);
		$this->Cell(40,5,'Procedencia',0,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(65,5,utf8_decode($procedencia),0,0,'L');
		$this->SetFont('Arial','B',9);
		//$this->SetFillColor(224,235,255);
                $this->Cell(40,5,'Origen',0,0,'L', true);
		 $this->SetFont('Arial','',9);
		$this->Cell(65,5,utf8_decode($servicio),0,1,'L');
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
		$this->Cell(65,5,utf8_decode($nombre_empleado),0,1,'L');

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
         $this->SetFillColor(255,255,255);
         $ban1=0;

        $this->Ln(2);
      $resultgetExamnResult    =  $this->getExamnResult($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);

      $this->SetFont('Arial','B',9);
      $this->SetFont('Arial','',9);
      $this->MuestrasRechazadas($resultgetExamnResult['RM']);

        if (count($resultgetExamnResult['RC']) > 0) {
            foreach($resultgetExamnResult['RC'] as $area) {

                $arrayPlantillas= ['A','B','C','D','E'] ;
                foreach ($arrayPlantillas as $pType) {
                    if(array_key_exists($pType, $area['plantillas'])) {
                       $this->bodyLayout($area, $pType);
                    }
                }
            }
        } else {

             {
                 $this->Cell(0,6,  ' -> Los examenes no han sido procesados aun...',0,1,'L');

            }
        }



        }//fin pdfid
      /**********************************/


/*
 * funciones array
 */

 function getExamnResult($idHistorialClinico, $idDatoReferencia, $idEstablecimiento) {
        $objdatos = new clsReporteResultados;
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
            foreach ($resultados as $row) {
                if($row['codigo_estado_detalle'] === 'RM') {
                    $newExam = array(
                            $row['id_examen'],
                            $row['codigo_examen'],
                            $row['nombre_examen']
                        );

                    $result['RM'] = $this->addExamnToLayout($result['RM'], $newExam, $row, $row['codigo_plantilla']);
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

        $result['RC'][$id]['plantillas'] = $this->addLayoutToArea( $result['RC'][$id]['plantillas'], $newPlantilla, $row );
                }
    }

            return $result;
        }
//
    }//fin getExamnResult


      //Funcion de Muestras Rechazadas
     function  MuestrasRechazadas($rm) {
        // $html = '';
        if(count($rm) > 0) {
         $this->Ln(1);
         $this->SetFont('Arial','B',14);
         $this->Cell(0,6,'MUESTRAS RECHAZADAS',0,1,'L', true);
         $this->Ln(1);
         $ye=$this->GetY();
         $this->SetDrawColor(4,79,144);
         $this->Line(7,$ye,195,$ye);
         $this->Ln(1);
         $this->SetFillColor(200,200,200);
         $this->SetFont('Arial','B',9);
         $this->SetX(7);
         $this->Cell(108,5,utf8_decode('NOMBRE DEL EXAMEN'),0,0,'L', true);
         $this->Cell(80,5,utf8_decode('MOTIVO DE RECHAZO'),0,1,'L', true);
         $this->Ln(1);
         $ye=$this->GetY();
         $this->SetDrawColor(4,79,144);
         $this->Line(7,$ye,195,$ye);
         $this->SetFont('Arial','',10);
         $this->SetWidths(array(55, 30, 50, 30,15,15));
         $this->SetFillColor(255,255,255);

         $this->Ln(1);
         $this->SetFillColor(255,255,255);

             foreach($rm as $examen) {
               $this->SetX(7);
               $this->Cell(108,5,utf8_decode($examen['nombre']),0,0,'L', true);
               $this->Cell(80,5,utf8_decode($examen['motivo_rechazo']),0,1,'L', true);
               $this->Ln(1);
             }
         }
        $this->SetTextColor(0,0,0);
     }//Fin muestrarechazada

     //addExamenToLayout
          function addExamnToLayout($exams, $newExam, $row, $tipoPlantilla) {
        if( ! isset($exams[ $newExam[2] ]) )
        {
                $exams[ $newExam[2] ] = array(
                                        'id'                    => $newExam[0],
                                        'codigo'                => $newExam[1],
                                        'nombre'                => $newExam[2],
                                        'id_estado_detalle'     => $row['id_estado_detalle'],
                                        'codigo_estado_detalle' => $row['codigo_estado_detalle'],
                                        'nombre_estado_detalle' => $row['nombre_estado_detalle']
                                            );
        }

        if( ! isset($exams[ $newExam[2] ]['resultadoFinal']) ){
            $exams[ $newExam[2] ]['resultadoFinal'] = array();
        }

        switch ($row['codigo_estado_detalle']) {
            case 'RM':
                $exams[ $newExam[2] ]['motivo_rechazo'] = $row['nombre_observacion_rechazo'];
                break;
            case 'RC':
    $exams[ $newExam[2] ]['resultadoFinal'] = array(
                                            'id_empleado'           => $row['id_empleado'],
                                            'nombre_empleado'       => $row['nombre_empleado'],
                                            'fecha_resultado'       => $row['fecha_resultado'],
                                            'urgente'               => $row['urgente']
                                        );
                break;

            default:
                # code...
                break;
        }

        switch ($tipoPlantilla) {
            case 'A':
                if($row['codigo_estado_detalle'] === 'RC') {
                    $exams[ $newExam[2] ]['resultadoFinal']['id_resultado']             = $row['id_resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['resultado']                = $row['resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['id_posible_resultado']     = $row['id_posible_resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['nombre_posible_resultado'] = $row['nombre_posible_resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['lectura']                  = $row['lectura'];
                    $exams[ $newExam[2] ]['resultadoFinal']['interpretacion']           = $row['interpretacion'];
                    $exams[ $newExam[2] ]['resultadoFinal']['observacion']              = $row['resultado_observacion'];
                    $exams[ $newExam[2] ]['resultadoFinal']['unidad']                   = $row['unidades'];
                    $exams[ $newExam[2] ]['resultadoFinal']['rango_inicio']             = $row['rango_inicio'];
                    $exams[ $newExam[2] ]['resultadoFinal']['rango_fin']                = $row['rango_fin'];
                }
                break;
            case 'B':
                if( ! isset($exams[ $newExam[2] ]['elementos']) ){
        $exams[ $newExam[2] ]['elementos'] = array();
        }

                if($row['codigo_estado_detalle'] === 'RC') {
                    $newElement = array(
                        $row['id_elemento'],
                        $row['nombre_elemento'],
                        $row['resultado_elemento'],
                        $row['id_posible_resultado_elemento'],
                        $row['nombre_posible_resultado_elemento'],
                        $row['unidad_elemento'],
                        $row['control_normal_elemento']
                    );

                    $exams[ $newExam[2] ]['elementos'] = $this->addElementToExam($exams[ $newExam[2] ]['elementos'], $newElement, $row);
                }
                break;
            case 'C':
                if( ! isset($exams[ $newExam[2] ]['bacterias']) ){
        $exams[ $newExam[2] ]['bacterias'] = array();
        }

                if($row['codigo_estado_detalle'] === 'RC') {
                    $exams[ $newExam[2] ]['resultadoFinal']['id_resultado']             = $row['id_resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['resultado']                = $row['resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['id_posible_resultado']     = $row['id_posible_resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['nombre_posible_resultado'] = $row['nombre_posible_resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['id_observacion']           = $row['id_observacion_bacteria'];
                    $exams[ $newExam[2] ]['resultadoFinal']['nombre_observacion']       = $row['nombre_observacion_bacteria'];
                    $exams[ $newExam[2] ]['resultadoFinal']['codigo_observacion']       = $row['codigo_observacion_bacteria'];

                    if($row['id_observacion_bacteria'] === null) {
                        $newBacter = array(
                            $row['id_bacteria'],
                            $row['nombre_bacteria'],
                            $row['id_resultado'],
                            $row['resultado'],
                            $row['id_posible_resultado'],
                            $row['nombre_posible_resultado'],
                            $row['cantidad_bacterias']
                        );
                         $exams[ $newExam[2] ]['bacterias'] = $this->addBacterToExamn($exams[ $newExam[2] ]['bacterias'], $newBacter, $row);

                    }
                }
                break;

            default:
                if( ! isset($exams[ $newExam[2] ]['procedimientos']) ){
        $exams[ $newExam[2] ]['procedimientos'] = array();
        }

                if($row['codigo_estado_detalle'] === 'RC') {
                    $exams[ $newExam[2] ]['resultadoFinal']['observacion'] = $row['resultado_observacion'];

                    $newProcedure = array(
                        $row['id_procedimiento'],
                        $row['nombre_procedimiento'],
                        $row['unidad_procedimiento'],
                        $row['rango_inicio_procedimiento'],
                        $row['rango_fin_procedimiento'],
                        $row['resultado_procedimiento'],
                        $row['id_posible_resultado_procedimiento'],
                        $row['nombre_posible_resultado_procedimiento'],
                        $row['control_diario_procedimiento']
                    );

                    $exams[ $newExam[2] ]['procedimientos'] = $this->addProcedureToExam($exams[ $newExam[2] ]['procedimientos'], $newProcedure);
                }
                break;
        }

        /*Falta Logica para Resultados de la Metodologia*/
        return $exams;
    }//Fin addExamnToLayout

    //addLayoutToArea

     function addLayoutToArea($plantillas, $newPlantilla, $row) {
         if( ! isset($plantillas[ $newPlantilla[1] ]) )
             {
                     $plantillas[ $newPlantilla[1] ] = array(
                                                 'id'     => $newPlantilla[0],
                                                 'codigo' => $newPlantilla[1],
                                                 'nombre' => $newPlantilla[2]
                                                             );
             }

                 if( ! isset($plantillas[ $newPlantilla[1] ]['examenes']) )
                     {
                             $plantillas[ $newPlantilla[1] ]['examenes'] = array();
                     }

                 $newExam = array(
                         $row['id_examen'],
                         $row['codigo_examen'],
                         $row['nombre_examen']
                     );

                 $plantillas[ $newPlantilla[1] ]['examenes'] = $this->addExamnToLayout($plantillas[ $newPlantilla[1] ]['examenes'], $newExam, $row, $newPlantilla[1]);

      return $plantillas;
      }//addLayoutToArea

      //BodyLayout
          // funciones otras
function bodyLayout($area, $pType) {
    $html = '';
    foreach($area['plantillas'] as $plantilla) {
        if($plantilla['codigo'] === $pType) {
           $index=0;
            foreach($plantilla['examenes'] as  $examen) {
                $examStatus = $examen['codigo_estado_detalle'];
                $this->headerLayout($examen, $examStatus, $pType, $area, $index);
                if($examStatus === 'RC') {
                   $this->plantillas($examen, $pType);
                }
                 $this->footerLayout($examen, $examStatus);

                $index++;
            }
        }
    }

    return $html;
   }//Fin bodyLayout

   //fn headerLayout
   function headerLayout($examen, $examStatus, $pType, $area, $index) {
      $header="";
      $valorY=  $this->GetY();
      $maxY=280;
    switch ($pType){
       case 'A':
               if (($maxY-30)<$valorY){
                  $this->AddPage();
               }
          break;
       case 'B':
               if (($this->GetY()>=85)){
               $this->AddPage();
               }
          break;
       case 'C':
          break;
       default :
           if (($maxY-55)<$valorY){
                  $this->AddPage();
               }
          break;
    }
    if ($index==0){
         $this->Ln(3);
               $this->SetFont('Arial','B',14);
               $this->Ln(1);
               $this->SetFillColor(255,255,255);
               $this->Cell(0,6,utf8_decode($area['nombre']),0,1,'L', true);
               $this->Ln(1);
    }

      $this->SetFont('Arial','B',9);
      $ye=$this->GetY();
      $this->SetDrawColor(4,79,144);
      $this->Line(7,$ye,195,$ye);
      $this->SetX(7);
      $this->SetFont('Arial','B',11);
       $this->SetWidths(array(110, 18, 60));
       $this->Ln(1);
      $this->Row1(array(utf8_decode($examen['nombre']),'Estado:',utf8_decode($examen['nombre_estado_detalle'])));
      $this->Ln(1);
   }//fin HEaderLayout

   //fn footerLayout
   function footerLayout($examen, $examStatus) {
      if($examStatus === 'RC') {
         $this->SetX(15);
      $this->SetFont('Arial','BI',8);
       $this->SetWidths(array(25,81,32,20,15,10));
      $this->Row1(array('Validado por:',utf8_decode($examen['resultadoFinal']['nombre_empleado']),' Fecha de Resultado:',$examen['resultadoFinal']['fecha_resultado'], 'Urgente:', $examen['resultadoFinal']['urgente']));
      }
      $this->SetX(7);
      $this->SetFont('Arial','B',8);
      $this->SetFillColor(255, 255, 255);
      $this->SetTextColor(184,184,184);
     $this->Cell(0, 1, '........................................................................................................................................................................................................................................', 0, 1, 'C',true);
     $this->SetTextColor(0,0,0);
      $this->Ln(8);
   }//fin footerLayout
   //fn Plantillas

   function plantillas($examen, $pType){
       $plantilla = 'plantilla'.$pType;

       return $this->$plantilla($examen);
   //   }
   }//fin plantillas
   //
   //PLANTILLA A
   function plantillaA($examen) {
       $html = '';
        $this->SetX(7);
        $this->SetFillColor(200,200,200);
        $this->SetFont('Arial','B',9);
         $this->Cell(55,5,utf8_decode('RESULTADO'),0,0,'L', true);
         $this->Cell(30,5,utf8_decode('UNIDADES'),0,0,'L', true);
         $this->Cell(50,5,utf8_decode('RANGOS NORMALES'),0,0,'L', true);
         $this->Cell(53,5,utf8_decode('OBSERVACIONES'),0,1,'L', true);
        $this->Ln(1);
         $ye=$this->GetY();
         $this->SetDrawColor(4,79,144);
         $this->Line(7,$ye,195,$ye);
         $this->Ln(0);
         $this->SetFont('Arial','',10);
         $this->SetWidths(array(55, 30, 50, 53));
         $this->SetX(7);

      if (isset($examen['resultadoFinal']['id_posible_resultado']) && $examen['resultadoFinal']['id_posible_resultado'] !== '') {
         $resultado_p=$examen['resultadoFinal']['nombre_posible_resultado'];

      } else {
         $resultado_p=$examen['resultadoFinal']['resultado'];

      }

      $unidad_p=$examen['resultadoFinal']['unidad'];
      $rangos_p=$examen['resultadoFinal']['rango_inicio'].' - '.$examen['resultadoFinal']['rango_fin'];
      $observacion_p=$examen['resultadoFinal']['observacion'];
      $this->SetFillColor(255,255,255);
      $this->SetX(7);
      $this->Row1(array(utf8_decode($resultado_p),  utf8_decode($unidad_p),utf8_decode($rangos_p),utf8_decode($observacion_p)));
      $this->SetTextColor(0,0,0);
      $this->Ln(2);

   }//Fin PlantillaA

   //Fn plantilla B
   // PLANTILLA B
function plantillaB($examen) {
    $html= '';

     $this->SetX(7);
        $this->SetFillColor(200,200,200);
        $this->SetFont('Arial','B',9);
         $this->Cell(68,5,utf8_decode(''),0,0,'L', true);
         $this->Cell(60,5,utf8_decode('RESULTADO'),0,0,'L', true);
         $this->Cell(25,5,utf8_decode('UNIDADES'),0,0,'L', true);
         $this->Cell(35,5,utf8_decode('RANGOS NORMALES'),0,1,'L', true);
         $this->Ln(1);
         $ye=$this->GetY();
         $this->SetDrawColor(4,79,144);
         $this->Line(7,$ye,195,$ye);
          $this->Ln(1);



         $this->SetFillColor(255,255,255);

    foreach ($examen['elementos'] as $elemento){

        if ($examen['codigo'] === 'H50' ){

            $elenomb_b=$elemento['nombre'];


            if($elemento['id_posible_resultado'] !== null || $elemento['id_posible_resultado'] !== '') {
               $elemreult_b=$elemento['nombre_posible_resultado'].' '.$elemento['resultado'];

           } else {
               $elemreult_b=$elemento['resultado'];
            }
            $elemunit_b=$elemento['unidad'];
            $elecontnormal_b=$elemento['control_normal'].' '.$elemento['unidad'];

        } else {
            $elenomb_b=$elemento['nombre'];
            $elemreult_b='';
            $elemunit_b='';
            $elecontnormal_b='';
        }
         $this->SetX(7);
         $this->SetWidths(array(68, 60, 25, 35));

        $this->Row1(array(utf8_decode($elenomb_b),  utf8_decode($elemreult_b), utf8_decode($elemunit_b) , utf8_decode($elecontnormal_b)));


        foreach($elemento['subelementos'] as $subelemento) {
            $subelemnomb_b=$subelemento['nombre'];


            if($subelemento['id_posible_resultado'] !== null || $subelemento['id_posible_resultado'] !== '') {
               if ($subelemento['id_posible_resultado']==null){
                 $subelemres_b= $subelemento['resultado'];
              }
              else{
                 $subelemres_b= $subelemento['nombre_posible_resultado'];
              }
              // $subelemres_b=$subelemento['resultado'].' '. $subelemento['nombre_posible_resultado'];
            } else {
               $subelemres_b=$subelemento['resultado'];
            }
            $subeleunit_b=$subelemento['unidad'];

            if($examen['codigo'] === 'H50') {
               $subelecontnormal_b=$subelemento['control_normal'].' - '.$elemento['unidad'];
            } else {
               $subelecontnormal_b=$subelemento['rango_inicio'].' - '.$subelemento['rango_fin'];
            }
         $this->SetWidths(array(68, 60, 25, 35));
         $this->SetX(10);
         $this->Row1(array(utf8_decode($subelemnomb_b),  utf8_decode($subelemres_b), utf8_decode($subeleunit_b) , utf8_decode($subelecontnormal_b)));
        }
    }
    $this->Ln(2);

   }//fin plantilla B

  //addElementtoExam

    function addElementToExam($elements, $newElement, $row) {
        if( ! isset($elements[ $newElement[1] ]) )
        {
            $elements[ $newElement[1] ] = array(
                                        'id'                       => $newElement[0],
                                        'nombre'                   => $newElement[1],
                                        'resultado'                => $newElement[2],
                                        'id_posible_resultado'     => $newElement[3],
                                        'nombre_posible_resultado' => $newElement[4],
                                        'unidad'                   => $newElement[5],
                                        'control_normal'           => $newElement[6]
                                                );
        }

        if( ! isset($elements[ $newElement[1] ]['subelementos']) )
            {
                    $elements[ $newElement[1] ]['subelementos'] = array();
            }

        $newSubelement = array(
            $row['id_subelemento'],
            $row['nombre_subelemento'],
            $row['resultado_subelemento'],
            $row['id_posible_resultado_subelemento'],
            $row['nombre_posible_resultado_subelemento'],
            $row['unidad_subelemento'],
            $row['rango_inicio_subelemento'],
            $row['rango_fin_subelemento'],
            $row['control_normal_subelemento']
        );

        $elements[ $newElement[1] ]['subelementos'] = $this->addSubElementToElement($elements[ $newElement[1] ]['subelementos'], $newSubelement);

        return $elements;
    }





     function addSubElementToElement($subelements, $newSubelement) {
        if( ! isset($subelements[ $newSubelement[1] ]) )
            {
                $subelements[ $newSubelement[1] ] = array(
                                        'id'                       => $newSubelement[0],
                                        'nombre'                   => $newSubelement[1],
                                        'resultado'                => $newSubelement[2],
                                        'id_posible_resultado'     => $newSubelement[3],
                                        'nombre_posible_resultado' => $newSubelement[4],
                                        'unidad'                   => $newSubelement[5],
                                        'rango_inicio'             => $newSubelement[6],
                                        'rango_fin'                => $newSubelement[7],
                                        'control_normal'           => $newSubelement[8]
                                        );
            }

        return $subelements;
    }//fin
  //
   //PLANTILLA E
function plantillaE($examen) {
   $this->SetX(7);
   $this->SetFillColor(200,200,200);
   $this->SetFont('Arial','B',9);
   $this->Cell(55,5,utf8_decode('PRUEBA'),0,0,'L', true);
   $this->Cell(53,5,utf8_decode('RESULTADOS'),0,0,'L', true);
   $this->Cell(20,5,utf8_decode('UNIDADES'),0,0,'C', true);
   $this->Cell(30,5,utf8_decode('RANGOS'),0,0,'C', true);
   $this->Cell(30,5,utf8_decode('CONTROL DIARIO'),0,1,'C', true);
   $this->SetWidths(array(55, 55, 20, 30,30));
   $this->Ln(1);
   $ye=$this->GetY();
   $this->SetDrawColor(4,79,144);
   $this->Line(7,$ye,195,$ye);

                    foreach ($examen['procedimientos'] as $procedimiento){
                       $procnombre_e=$procedimiento['nombre'];
                          if($procedimiento['id_posible_resultado'] !== null && $procedimiento['id_posible_resultado'] !== '') {
                             $procresult_e=$procedimiento['nombre_posible_resultado'];

                             } else {
                                $procresult_e=$procedimiento['resultado'];
                            }
                            $procunidad_e=$procedimiento['unidad'];
                            $procrangos_e=$procedimiento['rango_inicio'].' -  '.$procedimiento['rango_fin'];
                            $proccontrol_e=$procedimiento['control_diario'];
                            $this->Row1(array(utf8_decode($procnombre_e),  utf8_decode($procresult_e), utf8_decode($procunidad_e) , utf8_decode($procrangos_e), utf8_decode($proccontrol_e)));
                }
                $this->Cell(0,6,utf8_decode('Observación: '.$examen['resultadoFinal']['observacion']),0,1,'L');
         $this->Ln(2);


}//Plantilla E
   //addProcedureToExam
    function addProcedureToExam($procedures, $newProcedure) {
        if( ! isset($procedures[ $newProcedure[1] ]) )
        {
                $procedures[ $newProcedure[1] ] = array(
                                        'id'                       => $newProcedure[0],
                                        'nombre'                   => $newProcedure[1],
                                        'unidad'                   => $newProcedure[2],
                                        'rango_inicio'             => $newProcedure[3],
                                        'rango_fin'                => $newProcedure[4],
                                        'resultado'                => $newProcedure[5],
                                        'id_posible_resultado'     => $newProcedure[6],
                                        'nombre_posible_resultado' => $newProcedure[7],
                                        'control_diario'           => $newProcedure[8]
                    );
        }

        return $procedures;
    }//fin addProcedureToExam
// PLANTILLA C
function plantillaC($examen) {
    $html="";
    //$html.="plantillaC";
    if( $examen['resultadoFinal']['id_posible_resultado'] !== null || $examen['resultadoFinal']['id_posible_resultado'] != '' ){
            $resfinnombre_c="   ". $examen['resultadoFinal']['nombre_posible_resultado']."   ";
   } else {
$resfinnombre_c="    ".$examen['resultadoFinal']['resultado']."   ";

   }
   $this->SetX(7);
      $this->SetFont('Arial','B',9);
      $this->Cell(0, 5, 'RESULTADO:'.$resfinnombre_c, 0,1, 'C');
     // $this->SetFont('Arial','B',9);
      //$this->Cell(120, 5, $resfinnombre_c, 0, 1, 'L');
//    $html.="<div class='row' style='font-size: 17px;padding-top: 20px;padding-bottom: 20px;'>
//    <div class='col-md-12 col-sm-12'>
//        Resultado: <strong>";
//
//               $html.= "</strong>
//                </div>";

     if( $examen['resultadoFinal']['id_observacion'] !==(null) ){
         $this->Cell(0, 6, $examen['resultadoFinal']['nombre_observacion'], 0, 1, 'C');
//             $html.="<div class='col-md-12 col-sm-12'>
//                    Observacion: <strong>".$examen['resultadoFinal']['nombre_observacion']."</strong>
//                </div>";
            }
//            $html.="</div>";
 if (count($examen['bacterias']) > 0) {
 //if ($examen['bacterias'] |length > 0 ){
//  $html.=  "<table class='table table-white'>
//        <tbody>";
            foreach ($examen['bacterias'] as $bacteria){
               $this->SetX(7);
           // {% for bacteria in examen.bacterias %}
            $this->SetFillColor(255,255,255);
            //$this->Line(7,$ye,195,$ye);
            $this->Ln(1);
//            $ye=$this->GetY();
//            $this->SetDrawColor(230,230,230);
//            $this->Line(7,$ye,195,$ye);
            $this->SetX(7);
            $this->SetDrawColor(165,165,165);
            $this->SetLineWidth(0.1);
            $this->SetFont('Arial','B',9);
            $this->Cell(70,5,'ORGANISMO:','LT',0,'L', true);
            $this->SetFont('Arial','',9);
            $this->Cell(118,5,utf8_decode($bacteria['nombre']),'TR',1,'L');
            $this->SetFont('Arial','B',9);
            $this->SetX(7);
            $this->Cell(70,5,'CULTIVO CON CUENTA DE COLONIAS:','L',0,'L', true);
            $this->SetFont('Arial','',9);
            $this->Cell(118,5,utf8_decode($bacteria['cantidad']),'R',1,'L');
            //$this->Ln(1);
            $this->SetX(7);
            $this->SetFillColor(200,200,200);
            $this->SetFont('Arial','B',9);

            $this->Cell(105,5,utf8_decode('ANTIBIOTICO'),'L',0,'L', true);
            $this->Cell(30,5,utf8_decode('LECTURA'),0,0,'L', true);
            $this->Cell(53,5,utf8_decode('INTERPRETACIÓN'),'R',1,'L', true);
            //$this->Cell(53,5,utf8_decode('OBSERVACIONES'),0,1,'L', true);
      //     $this->Ln(1);
            $ye=$this->GetY();
            $this->SetDrawColor(194,194,194);
            $this->Line(7,$ye,195,$ye);
            $this->Ln(0);
            $this->SetFont('Arial','',10);
            $this->SetWidths(array(55, 30, 50, 53));
            $this->SetX(7);
//              $html.="  <tr>
//                    <td colspan='4'>
//                        <div class='row'>
//                            <div class='col-md-12 col-sm-12'>
//                                <table class='heading-bact-pc'>
//                                    <tbody>
//                                        <tr>
//                                            <td>Organismo:</td>
//                                            <td style='padding-left:15px;'><strong>".$bacteria['nombre']."</strong></td>
//                                        </tr>
//                                        <tr>
//                                            <td>Cultivo con cuenta de Colonias:</td>
//                                            <td style='padding-left:15px;'><strong>".$bacteria['cantidad']."</strong></td>
//                                        </tr>
//                                    </tbody>
//                                </table>
//                            </div>
//                        </div>
//                    </td>
//                </tr>
//                <tr style='font-weight: bold;'>
//                    <td></td>
//                    <td>Antibiotico</td>
//                    <td>Lectura</td>
//                    <td>Interpretacion</td>
//                </tr>";
               foreach ($bacteria['tarjetas'] as $tarjeta){
                        foreach ($tarjeta['antibioticos'] as $antibiotico){
                            if($antibiotico['id_posible_resultado'] !== null || $antibiotico['id_posible_resultado'] !== '') {
                                 $nombreposres_c=$antibiotico['nombre_posible_resultado' ];
                             }else {
                               $nombreposres_c=$antibiotico['resultado'];
                                          }
                           $this->SetWidths(array(105, 30, 53));
                           $this->Ln(1);
                           $this->SetX(10);
                           $this->Row1(array(utf8_decode($antibiotico['nombre']),utf8_decode($antibiotico['lectura']),utf8_decode($nombreposres_c) ));

//                                $html.="  <tr>
//                                      <td></td>
//                                      <td>". $antibiotico['nombre']."</td>
//                                      <td>".$antibiotico['lectura']."</td>
//                                      <td>";
//                                          //{% if antibiotico.id_posible_resultado is not null or antibiotico.id_posible_resultado != '' %}
//
//                                     $html.= "</td>
//                                  </tr>";
                        }
                }
                $ye=$this->GetY();
            //$this->SetDrawColor(4,79,144);
            $this->Line(7,$ye,195,$ye);
                $this->Ln(3);
            }
//       $html.= "</tbody>
//                </table>";
      //return $html;
}
}//Fin plantillaC
//Fn addBacterToExam
 function addBacterToExamn($bacters, $newBacter, $row) {
        if( ! isset($bacters[ $newBacter[1] ]) )
            {
                    $bacters[ $newBacter[1] ] = array(
                                        'id'                       => $newBacter[0],
                                        'nombre'                   => $newBacter[1],
                                        'id_resultado'             => $newBacter[2],
                                        'resultado'                => $newBacter[3],
                                        'id_posible_resultado'     => $newBacter[4],
                                        'nombre_posible_resultado' => $newBacter[5],
                                        'cantidad'                 => $newBacter[6]
                                                    );
            }

        if( ! isset($bacters[ $newBacter[1] ]['tarjetas']) )
            {
                    $bacters[ $newBacter[1] ]['tarjetas'] = array();
            }

        $newCard = array(
            $row['id_tarjeta'],
            $row['nombre_tarjeta']
        );

        $bacters[ $newBacter[1] ]['tarjetas'] = $this->addCardToBacter($bacters[ $newBacter[1] ]['tarjetas'], $newCard, $row);

        return $bacters;
    }//fin addBacterToExam
    //Fn addCardToBacter

    function addCardToBacter($cards, $newCards, $row) {
        if( ! isset($cards[ $newCards[1] ]) )
            {
                            $cards[ $newCards[1] ] = array(
                                        'id'             => $newCards[0],
                                        'nombre'         => $newCards[1]);
            }

        if( ! isset($cards[ $newCards[1] ]['antibioticos']) ){
            $cards[ $newCards[1] ]['antibioticos'] = array();
        }

        $newAntibiotic = array(
            $row['id_antibiotico'],
            $row['nombre_antibiotico'],
            $row['resultado_antibiotico'],
            $row['lectura_antibiotico'],
            $row['id_posible_resultado_antibiotico'],
            $row['nombre_posible_resultado_antibiotico']
        );

        $cards[ $newCards[1] ]['antibioticos'] = $this->addAntibioticToCard($cards[ $newCards[1] ]['antibioticos'], $newAntibiotic);

        return $cards;
    }//fn addCardToBacter

    //fn addAntibioticToCard
    function addAntibioticToCard($antibiotics, $newAntibiotic) {
        if( ! isset($antibiotics[ $newAntibiotic[1] ]) ){
$antibiotics[ $newAntibiotic[1] ] = array(
                                        'id'                       => $newAntibiotic[0],
                                        'nombre'                   => $newAntibiotic[1],
                                        'resultado'                => $newAntibiotic[2],
                                        'lectura'                  => $newAntibiotic[3],
                                        'id_posible_resultado'     => $newAntibiotic[4],
                                        'nombre_posible_resultado' => $newAntibiotic[5]
);
}

        return $antibiotics;
    }//fn addAntibioticToCard



}//fin de la clase
//////////////////////////////////////////////////
$pdf=new PDF('p'); //p vertical
$pdf->AliasNbPages();
$grupopruecon=$objdatos->grupoprueconsul($idsolicitud, $lugar, $idHistorialClinico, $idDatoReferencia);

$pdf->AddPage();
$pdf->pdfid($idsolicitud, '', '', '', $lugar);
ob_end_clean();
$pdf->Output();

?>
