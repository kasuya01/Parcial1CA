<?php
ini_set('soap.wsdl_cache_enabled', '0');
ini_set('soap.wsdl_cache_ttl', '0');
ini_set('default_socket_timeout', 120);
include_once("../../../Conexion/ConexionEquipos.php");


function enviarSolicitudWS($id){
    $host= $_SERVER['HTTP_HOST'];
    list($modulo,$dominio)=explode(".", $host);
    $requestScheme=$_SERVER['REQUEST_SCHEME'];
    $return_='';
   $url = $requestScheme.'://siap.'.$dominio.'/app.php/soap/interfaceliswebservice';
    //echo $url;
  // $url = 'http://siaps.localhost/app_dev.php/soap/interfaceliswebservice';
    $action = 'checkin';
    $soapParameters = array('trace' => true, 'exceptions' => true);

    $array = array('AppUser'=>USUARIO_HL7,'Password'=>CLAVE_HL7);
    $json_array = json_encode($array);
    $array_param = array('json_array' => $json_array);


    try {
        $soapClient = new Soapclient($url.'?wsdl', $soapParameters);
    //    $soapClient->__setLocation('http://siaps.localhost/app_dev.php/soap/interfaceliswebservice');

    //    $soapClient->__setLocation($requestScheme.'://siap.'.$dominio.'/app.php/soap/interfaceliswebservice');
        $soapClient->__setLocation($url);
        $data = $soapClient->__soapCall($action, $array_param);
    } catch (Exception $e) {
        return 'false';
        //return $e->__toString();
    }

    $data = json_decode($data,true);


    if( $data['estado'] === true ) {
        $token       = $data['token'];
        $sendData    = json_encode( array('token' => $token, 'idSolicitud' => $id) );
        $array_param = array('json_array' => $sendData);
        try {

            $action= 'generarMensajeSolicitud';
            $mensaje = $soapClient->__soapCall($action, $array_param);
            $return_ .= 'Solicitud enviada con éxito';

        } catch (Exception $e) {
            // enviar mensaje de error a tabla
             return 'false';
            //return $e->__toString();
        }

        $sendData    = json_encode( array('token' => $token) );
        $array_param = array('json_array' => $sendData);
        try {

            $action= 'checkout';
            $mensaje = $soapClient->__soapCall($action, $array_param);
            $return_ .= '....';
            return $return_;

        } catch (Exception $e) {
            // enviar mensaje de error a tabla
            return 'false';
            //return $e->__toString();
        }

        //return 'Solicitud Enviada a Equipos:';
        //var_dump($mensaje);

    } else {
        return 'false';
    }


} //Fin funcion insertar
?>
