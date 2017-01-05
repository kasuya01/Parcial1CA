<?php
session_start();
$usuario = $_SESSION['Correlativo'];
$lugar = $_SESSION['Lugar'];
$area = $_SESSION['Idarea'];
include ("clsReporteResultados.php");

//variables POST

$opcion = $_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsReporteResultados;


/**********************************
 
 
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

                    $result['RM'] = addExamnToLayout($result['RM'], $newExam, $row, $row['codigo_plantilla']);
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

        $result['RC'][$id]['plantillas'] = addLayoutToArea( $result['RC'][$id]['plantillas'], $newPlantilla, $row );
                }
    }

            return $result;
        }
        
    }

 
    
    function getDatosGenerales($idHistorialClinico, $idDatoReferencia, $idEstablecimiento) 
    {
       // $em = $this->container->get('doctrine')->getManager();
       //$datosGenerales = $em->getRepository('MinsalSeguimientoBundle:SecSolicitudestudios')->obtenerDatosGenerales($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);
       
        
        // ¿ obtenerDatosGenerales ??
        $objdatos = new clsReporteResultados;
        $datosGenerales= $objdatos->obtenerDatosGenerales($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);

        
        
        return count($datosGenerales) > 0 ? $datosGenerales[0] : null;
    }

    
    
    
    
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

        $plantillas[ $newPlantilla[1] ]['examenes'] = addExamnToLayout($plantillas[ $newPlantilla[1] ]['examenes'], $newExam, $row, $newPlantilla[1]);

return $plantillas;
}




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
                $exams[ $newExam[2] ]['motivo_rechazo'] = $row['detalle_observacion'];
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

                    $exams[ $newExam[2] ]['elementos'] = addElementToExam($exams[ $newExam[2] ]['elementos'], $newElement, $row);
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

                        $exams[ $newExam[2] ]['bacterias'] = addBacterToExamn($exams[ $newExam[2] ]['bacterias'], $newBacter, $row);
                    }
                }
                break;
//            case 'D':
//                if( ! isset($exams[ $newExam[2] ]['elementos']) ){
//        $exams[ $newExam[2] ]['elementos'] = array();
//        }
//
//                if($row['codigo_estado_detalle'] === 'RC') {
//                    $newElement = array(
//                        $row['id_elemento_tincion'],
//                        $row['nombre_elemento_tincion'],
//                        $row['nombre_cantidad_tincion'],
//                        $row['id_cantidad_tincion']
//                    );
//
//                    $exams[ $newExam[2] ]['elementos'] = addTincionElementToExam($exams[ $newExam[2] ]['elementos'], $newElement);
//                }
//                break;
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

                    $exams[ $newExam[2] ]['procedimientos'] = addProcedureToExam($exams[ $newExam[2] ]['procedimientos'], $newProcedure);
                }
                break;
        }

        /*Falta Logica para Resultados de la Metodologia*/
        return $exams;
    }

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

        $elements[ $newElement[1] ]['subelementos'] = addSubElementToElement($elements[ $newElement[1] ]['subelementos'], $newSubelement);

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
    }

    function addTincionElementToExam($elements, $newElement) {
        if( ! isset($elements[ $newElement[1] ]) )
            {
                $elements[ $newElement[1] ] = array(
                                        'id'          => $newElement[0],
                                        'nombre'      => $newElement[1],
                                        'cantidad'    => $newElement[2],
                                        'id_cantidad' => $newElement[3]
                                                    );
            }

        return $elements;
    }

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
    }

    
    
    
    
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

        $bacters[ $newBacter[1] ]['tarjetas'] = addCardToBacter($bacters[ $newBacter[1] ]['tarjetas'], $newCard, $row);

        return $bacters;
    }

    
    
    
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

        $cards[ $newCards[1] ]['antibioticos'] = addAntibioticToCard($cards[ $newCards[1] ]['antibioticos'], $newAntibiotic);

        return $cards;
    }

    
    
    
    
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
    }
    
    
    
    // funciones otras
function bodyLayout($area, $pType) {
    $html = '';



    foreach($area['plantillas'] as $plantilla) {
        if($plantilla['codigo'] === $pType) {
            foreach($plantilla['examenes'] as $examen) {
                $examStatus = $examen['codigo_estado_detalle'];
                $html .= headerLayout($examen, $examStatus);
               // $html .= MuestrasRechazadas($examen,$examStatus);
                if($examStatus === 'RC') {
                    $html .= plantillas($examen, $pType);
                }

                $html .= '</div>';
            }
        }
    }

    return $html;
}
    
function headerLayout($examen, $examStatus) {
    $header="";
    //$header.=  "headerLayout";
    
    $header.=  "
            <div class='panel panel-primary'>
                <div class='panel-heading'>
                    <h5 style='margin: 0px;'>
                        <div class='row'>
                            <div class='col-md-3 col-sm-3'>
                                ".$examen['nombre']."
                            </div>
                            <div class='col-md-2 col-sm-2'>
                                Estado: <strong>".$examen['nombre_estado_detalle']."</strong>
                            </div>";
    
    if($examStatus === 'RC') {
        $header.= "
                            <div class='col-md-3 col-sm-3'>
                                Validado por: <strong>".$examen['resultadoFinal']['nombre_empleado']."</strong>
                            </div>
                            <div class='col-md-2 col-sm-2'>
                                Fecha de Resultado: <strong>".$examen['resultadoFinal']['fecha_resultado']."</strong>
                            </div>
                            <div class='col-md-2 col-sm-2'>
                                Urgente: <strong>".$examen['resultadoFinal']['urgente']."</strong>
                            </div>";
    }
    
    $header.= "        </div>
                    </h5>
                </div>";

    return $header;
}

function  MuestrasRechazadas($rm) {
    $html = '';
    $html.= "<div class='panel panel-warning'>
                                        <div class='panel-heading mouse-pointer' role='tab' id='heading-URI' data-toggle='collapse' >
                                            <h4 class='panel-title' style='text-align:left;'>MUESTRAS RECHAZADAS
                                            </h4>
                                        </div>
                        </div>";
    $html.= "<div class='panel panel-primary'>
                                        <div class='panel-heading mouse-pointer' role='tab' id='heading- data-toggle='collapse' >
                                            
                                        
                        </div>";
    $html.="<table class='table table-hover  table-condensed table-white' >
                <thead>
                    <tr>
                        <th style='background-color:#FFFFF2 !important;'>Nombre del Examen</th>
                        <th style='background-color:#FFFFF2 !important;'>Estado</th>
                        <th style='background-color:#FFFFF2 !important;'>Motivo de Rechazo</th>
                    </tr>
                </thead>
                <tbody>";
    
    if(count($rm) > 0) {
        foreach($rm as $examen) {
            $html.= "<tr>
                        <td> ".$examen['nombre']."</td>
                        <td> ".$examen['nombre_estado_detalle']."</td>
                        <td> ".$examen['motivo_rechazo']."</td>
                    </tr>";
        }
    } else {
        $html.=" <tr>
                    <td>No existen examenes rechazados...</td>
                </tr>";
    }
    
    $html.= "</tbody>
        </table></div>";
          
    return $html;                            
}

function plantillas($examen, $pType){
    $plantilla = 'plantilla'.$pType;
    
    return $plantilla($examen);
}
//PLANTILLA A
function plantillaA($examen) {
    $html = '';
    //$html.="plantillaA";
    $html.="
            <table class='table table-white''>
              <thead>
                  <tr>
                      <th>Resultado</th>
                      <th>Unidades</th>
                      <th>Rangos Normales</th>
                      <th>Observacion</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>";

    if (isset($examen['resultadoFinal']['id_posible_resultado']) && $examen['resultadoFinal']['id_posible_resultado'] !== '') {
        $html.= "<td>".$examen['resultadoFinal']['nombre_posible_resultado']."</td>";
      // $html.= "<td>".$examen['resultadoFinal']['resultado']."</td>";
    } else {
       $html.= "<td>".$examen['resultadoFinal']['resultado']."</td>";
        // $html.= "<td>".$examen['resultadoFinal']['nombre_posible_resultado']."</td>";
    }
    
    $html.= "
                    <td>".$examen['resultadoFinal']['unidad']."</td>
                    <td>".$examen['resultadoFinal']['rango_inicio']."-".$examen['resultadoFinal']['rango_fin']."</td>
                    <td>".$examen['resultadoFinal']['observacion']."</td>
                </tr>
            </tbody>
        </table>";

    return $html;
}
// PLANTILLA B
function plantillaB($examen) {
    $html= '';
   // $html.="plantillaB";
    $html.= "<table class='table table-hover table-bordered table-condensed table-white''>
                <thead>
                    <tr>
                        <th colspan='2'></th>
                        <th colspan=>Resultado</th>
                        <th colspan=>Unidades</th>
                        <th>Rangos de Referencia</th>
                        <th>Control Normal</th>
                    </tr>
                </thead>
                <tbody>";

    foreach ($examen['elementos'] as $elemento){               
        $html.= "<tr>";
        
        if ($examen['codigo'] === 'H50' ){
            $html.= "
                 <td  colspan='2' class='pb-element'>".$elemento['nombre']."</td>
                    <td>";
            
            if($elemento['id_posible_resultado'] !== null || $elemento['id_posible_resultado'] !== '') {
                $html.= " ".$elemento['nombre_posible_resultado']."";
                $html.="    ".$elemento['resultado']."  ";
           } else {
                $html.="    ".$elemento['resultado']."  ";
            }
            $html.=" </td>
                    <td> ".$elemento['unidad']."</td>
                    <td>-</td>
                    <td>".$elemento['control_normal']."  ' ' ". $elemento['unidad']."</td>";
        } else {
            $html.= '<td colspan="6" class="pb-element">'.$elemento['nombre'].'</td>';
        }
        
        $html.= '</tr>';
        
        foreach($elemento['subelementos'] as $subelemento) {
            $html.= '<tr>
                    <td></td>
                    <td class="pb-subelement">'.$subelemento['nombre'].'</td>
                    <td>';
            
            if($subelemento['id_posible_resultado'] !== null || $subelemento['id_posible_resultado'] !== '') {
              // $html.= $subelemento['nombre_posible_resultado'];
                 $html.= "".$subelemento['resultado']."";
                 $html.= $subelemento['nombre_posible_resultado'];
            } else {
                $html.= "".$subelemento['resultado']."";
                 //$html.= $subelemento['nombre_posible_resultado'];
            }

            $html.= '</td>
                        <td>'.$subelemento['unidad'].'</td>';

            if($examen['codigo'] === 'H50') {
                $html.= '<td>-</td>
                <td>'.$subelemento['control_normal'].' - '.$elemento['unidad'].'</td>';
            } else {
                $html.= '<td>'.$subelemento['rango_inicio'].' - '.$subelemento['rango_fin'].'</td>
                <td></td>';
            }
            
            $html.= '</tr>';
        }
    }
    
    $html.= '</body>
            </table>';
    
    return $html;
}
// PLANTILLA C
function plantillaC($examen) {
    $html="";
    //$html.="plantillaC";
    $html.="<div class='row' style='font-size: 17px;padding-top: 20px;padding-bottom: 20px;'>
    <div class='col-md-12 col-sm-12'>
        Resultado: <strong>";
                             if( $examen['resultadoFinal']['id_posible_resultado'] !== null || $examen['resultadoFinal']['id_posible_resultado'] != '' ){
                                 $html.="   ". $examen['resultadoFinal']['ombre_posible_resultado']."   ";
                                } else {
                                          $html.=  "    ".$examen['resultadoFinal']['resultado']."   ";
                                                    
                                }
               $html.= "</strong>
                </div>";
               
     if( $examen['resultadoFinal']['id_observacion'] !==(null) ){
             $html.="<div class='col-md-12 col-sm-12'>
                    Observacion: <strong>".$examen['resultadoFinal']['nombre_observacion']."</strong>
                </div>";
            }
            $html.="</div>";
 if (count($examen['bacterias']) > 0) {
 //if ($examen['bacterias'] |length > 0 ){
  $html.=  "<table class='table table-white'>
        <tbody>";
            foreach ($examen['bacterias'] as $bacteria){
           // {% for bacteria in examen.bacterias %}
              $html.="  <tr>
                    <td colspan='4'>
                        <div class='row'>
                            <div class='col-md-12 col-sm-12'>
                                <table class='heading-bact-pc'>
                                    <tbody>
                                        <tr>
                                            <td>Organismo:</td>
                                            <td style='padding-left:15px;'><strong>".$bacteria['nombre']."</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Cultivo con cuenta de Colonias:</td>
                                            <td style='padding-left:15px;'><strong>".$bacteria['cantidad']."</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr style='font-weight: bold;'>
                    <td></td>
                    <td>Antibiotico</td>
                    <td>Lectura</td>
                    <td>Interpretacion</td>
                </tr>";
               foreach ($bacteria['tarjetas'] as $tarjeta){
                        foreach ($tarjeta['antibioticos'] as $antibiotico){
                                $html.="  <tr>
                                      <td></td>
                                      <td>". $antibiotico['nombre']."</td>
                                      <td>".$antibiotico['lectura']."</td>
                                      <td>";
                                          //{% if antibiotico.id_posible_resultado is not null or antibiotico.id_posible_resultado != '' %}
                                           if($antibiotico['id_posible_resultado'] !== null || $antibiotico['id_posible_resultado'] !== '') {
                                              $html.="    ".$antibiotico['nombre_posible_resultado' ]."   ";
                                          }else {
                                            $html.=" ". $antibiotico['resultado']." " ;
                                          }
                                     $html.= "</td>
                                  </tr>";
                        }
                }
            }
       $html.= "</tbody>
                </table>";
       return $html;
}
}
//PLANTILLA D
function plantillaD($examen) {
   $html="";
    //$html.="plantillaD";
            $html.= "<table class='table table-white'>
                        <thead>
                            <tr>
                                <th>Elmento de Tincion</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                    <tbody>";
            foreach ($examen['elementos'] as $elemento){

                $html.="<tr>
                            <td>".$elemento['nombre']."</td>
                            <td>".$elemento['cantidad']."</td>
                        </tr>";
                }
            $html.=" </tbody>
                    </table>";
   
   return $html;
}
//PLANTILLA E
function plantillaE($examen) {
    //var_dump($examen);
$html="";
    //$html.="plantillaE";
        $html.="<table class='table table-white'>
            <thead>
                <tr>
                    <th>Prueba</th>
                    <th>Resultado</th>
                    <th>Unidades</th>
                    <th>Rango</th>
                    <th>Control Diario</th>
                </tr>
            </thead>
            <tbody>";
                    foreach ($examen['procedimientos'] as $procedimiento){
                        $html.=" <tr>
                        <td>".$procedimiento['nombre']."</td>
                        <td>";
                          if($procedimiento['id_posible_resultado'] !== null && $procedimiento['id_posible_resultado'] !== '') {

                                $html.= "".$procedimiento['nombre_posible_resultado']."  ";
                             } else {
                                $html.= $procedimiento['resultado'];
                            }
                       $html.= '</td>
                        <td>'. $procedimiento['unidad'] .'</td>
                        <td>'. $procedimiento['rango_inicio'].' -  '.$procedimiento['rango_fin'].'</td>
                        <td>'. $procedimiento['control_diario'].'</td>
                    </tr>';
                }
        $html.="  </tbody>
                    </table>
                    <div class='row' style='font-size: 17px;padding-top: 20px;padding-bottom: 20px;'>
                <div class='col-md-12 col-sm-12'>
                    Observaci&oacute;n: <strong>". $examen['resultadoFinal']['observacion']."</strong>
                </div>
            </div>";
        

            
     return $html;
}
?>
