<?php

//implementamos la clase lab_areas
class cls_Clases {

    //constructor	

    function Query($Solicitud, $FechaRecepcion, $IdServicio, $IdSubServicio, $IdEmpleado, $IdNumeroExp, $Nombres, $PrimerApellido, $APELL2, $FechaNacimiento, $Sexo, $IdExamen, $CODIGOSUMI, $Impresiones, $CAMA, $FechaActual, $Bandera, $IdDetalleSolicitud, $Indicacion, $IdTipoMuestra, $IdOrigenMuestra, $IdPlantilla, $Observacion, $EstadoDetalle, $IdRecepcionMuestra, $NumeroMuestra) {

        if (($CAMA || $Observacion || $Impresiones || $Indicacion) != '' OR ( $CAMA || $Observacion || $Impresiones || $Indicacion) != null) {
            $CAMA = $CAMA;
            $Observacion = $Observacion;
            $Impresiones = $Impresiones;
            $Indicacion = $Indicacion;
        } else {
            $CAMA = 0;
            $Observacion = '(NULL)';
            $Impresiones = 0;
            $Indicacion = '(NULL)';
        }
        $query1 = "INSERT INTO laboratorio.his2lis(ORDEN,FSOLICITUD,ORIGEN,SERVICIO,DOCTOR,IDENTIFICA,NOMBRE,APELL1,APELL2,
FNAC,SEXO,CODIGO,CODIGOSUMI,HABITACION,CAMA,DATESYSTEM,PROCESADO,IdDetalleSolicitud,Indicacion,
IdTipoMuestra,IdOrigenMuestra,IdPlantilla,Observacion,Estado,IdRecepcionMuestra,NumeroMuestra) VALUES($Solicitud,'$FechaRecepcion','$IdServicio',$IdSubServicio,'$IdEmpleado','$IdNumeroExp','$Nombres','$PrimerApellido','$APELL2','$FechaNacimiento','$Sexo','$IdExamen','$CODIGOSUMI','$Impresiones',$CAMA,now(),$Bandera,$IdDetalleSolicitud,'$Indicacion',$IdTipoMuestra,$IdOrigenMuestra,'$IdPlantilla','$Observacion','$EstadoDetalle',$IdRecepcionMuestra,$NumeroMuestra)";

        $queryIns = mysql_query($query1) or die('La consulta fall&oacute;:' . mysql_error());
        return $query1;
    }

}

//CLASE
?>
