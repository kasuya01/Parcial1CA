<?php
ini_set('soap.wsdl_cache_enabled', '0');
ini_set('soap.wsdl_cache_ttl', '0');
ini_set('default_socket_timeout', 120);

function enviarSolicitudWS($id){
    $host= $_SERVER['HTTP_HOST'];
    list($modulo,$dominio)=explode(".", $host);
    $requestScheme=$_SERVER['REQUEST_SCHEME'];
    $return_='';
    $url = $requestScheme.'://siap.'.$dominio.'/app.php/soap/interfaceliswebservice?wsdl';
    //echo $url;
   //$url = 'http://siap.localhost/app_dev.php/soap/interfaceliswebservice?wsdl';
    $action = 'checkin';
    $soapParameters = array('trace' => true, 'exceptions' => true);

    $array = array('AppUser'=>'eautomatizado','Password'=>'34ut0m4t1z4d0');
    $json_array = json_encode($array);
    $array_param = array('json_array' => $json_array);


    try {
        $soapClient = new Soapclient($url, $soapParameters);
    //    $soapClient->__setLocation('http://siap.localhost/app_dev.php/soap/interfaceliswebservice');

        $soapClient->__setLocation($requestScheme.'://siap.'.$dominio.'/app.php/soap/interfaceliswebservice');
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
            return 'Solicitud enviada con éxito';

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
            return '....';
            //return $return_;

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
