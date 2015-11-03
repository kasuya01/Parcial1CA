<?php
session_start();
$nivel = $_SESSION['NIVEL'];
$corr = $_SESSION['Correlativo'];
$lugar = $_SESSION['Lugar'];
$area = $_SESSION['Idarea'];
//echo $nivel;
if ($nivel == 1) {
    include_once ('../../../PaginaPrincipal/index_laboratorio2.php');
}
if ($nivel == 2) {
    include_once ('../../../PaginaPrincipal/index_laboratorio22.php');
}
if ($nivel == 31) {
    include_once ('../../../PaginaPrincipal/index_laboratorio31.php');
}
if ($nivel == 33) {
    include_once ('../../../PaginaPrincipal/index_laboratorio33.php');
}
if ($nivel == 5) {
        include_once ('../../../PaginaPrincipal/index_laboratorio52.php');}
if ($nivel == 6) {
        include_once ('../../../PaginaPrincipal/index_laboratorio62.php');}
if ($nivel == 7) {
        include_once ('../../../PaginaPrincipal/index_laboratorio72.php'); } 
$ROOT_PATH = $_SESSION['ROOT_PATH'];
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <?php include_once $ROOT_PATH."/public/css.php";?>
        <?php include_once $ROOT_PATH."/public/js.php";?>
        <script src="../../../ObjetoAjax/ajax.js" type="text/javascript"></script>
        <script language="javascript">

            var accion = 0;
            var parametro = '';
            var sendReq = objetoAjax();
            

            function IngresoExamenes() {
                var id_exp = document.getElementById('IdNumeroExp_Name').value;

                day = new Date();
                id = day.getTime();
                var URL = "../IngresoSolicitudes/IngresoSolicitudes.php?IdNumeroExp="
                        + id_exp;
                eval("page" + id + " = window.open(URL, '" + id
                        + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1030,height=580,left = 150,top = 70');");

            }

            function  busquedaexp() {//funcion para buscar los datos  del paciente en base a lo ingresado en el campo IdNumeroExp_Name
                accion = 1;
                proceso='busquedaexp';
                id_exp = document.getElementById('IdNumeroExp_Name').value
                document.getElementById('divrespuesta').innerHTML="";
                document.getElementById('datos').innerHTML="";
                document.getElementById('cita').innerHTML="";
                //document.getElementById('divrespuesta').innerHTML="";
                
                
                if ((id_exp.length <= 0)) {
                    alert("Ingrese Numero de Expediente para la busqueda!!");
                    return false;
                }

                if ((!IsNumeric(document.getElementById('IdNumeroExp_Name').value))) {
                    alert('Por favor solo introduzca numeros en este campo')
                    document.getElementById('IdNumeroExp_Name').focus();
                    return false;
                }
                
                 sendReq.open("POST", "ajax_CitasServicios.php", true);
                //muy importante este encabezado ya que hacemos uso de un formulario
                sendReq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //enviando los valores

                sendReq.send("Proceso="+proceso+"&id_exp=" + id_exp);
                

            //    sendReq.open("POST", 'ajax_CitasServicios.php', true);
              //  sendReq.setRequestHeader('Content-Type',+'application/x-www-form-urlencoded');

               /*sendReq.onreadystatechange = function()
                {
                    if (sendReq.readyState == 4) {//4 The request is complete
                        alert(sendReq.status)
                        if (sendReq.status == 200) {//200 means no error.
                        
                            respuesta = sendReq.responseText;
                            alert (respuesta)
                            sendReq.onreadystatechange = procesaSearch;
                        }
                    }
                    }*/
                
               sendReq.onreadystatechange = procesaSearch;
                
                //var param = "Proceso=busquedaexp";
                //param += "&id_exp=" + id_exp;
              
               // sendReq.send("Proceso="+proceso+"&id_exp=" + id_exp);
                
            }

            function  mostrardetalle(idexp,establecimiento) {//recibe el numero de expediente para que se muestre el detalle de este
                accion = 2; 
                //alert ('llega aqui')

                sendReq.open("POST", 'ajax_CitasServicios.php', true);
                sendReq.onreadystatechange = procesaSearch;
                sendReq.setRequestHeader('Content-Type',
                        'application/x-www-form-urlencoded');

                var param = 'Proceso=mostrardetalle';
                param += '&id_exp=' + idexp + '&establecimiento=' + establecimiento;
               // alert (' llega: ' +param)
                sendReq.send(param);
            }

            function  darcita(idsolicitudestudio, idservicio, idexp, fecha) {//funcion que recibe el id de solicitud de estudio, el servicio, y el numero de expediente para ser enviados a otro proceso
                accion = 3;
                comprobar(idsolicitudestudio, idservicio, idexp);
//                sendReq.open("POST", 'ajax_CitasServicios.php', true);
//                sendReq.onreadystatechange = procesaSearch;
//                sendReq.setRequestHeader('Content-Type',
//                        'application/x-www-form-urlencoded');
                sendReq.open("POST", 'ajax_CitasServicios.php', true);
                //alert(sendReq.onreadystatechange)
                sendReq.onreadystatechange = procesaSearch;
//                    sendReq.onreadystatechange = function()
//                    {
//                        alert (sendReq.readyState)
//                        if (sendReq.readyState == 4) {//4 The request is complete
//                            alert(sendReq.status)
//                            if (sendReq.status == 200) {//200 means no error.
//                               
//                                respuesta = sendReq.responseText;
//                                alert('respuesta: '+respuesta)
//                                return false;
//                                //document.getElementById('divsubserv').innerHTML = respuesta;
//                            }
//                        }
//                    }
                sendReq.setRequestHeader('Content-Type',
                        'application/x-www-form-urlencoded');
                var param = 'Proceso=darcita';
                param += '&idsolicitudestudio=' + idsolicitudestudio + '&idservicio=' + idservicio+'&id_exp='+idexp+'&fecha='+fecha;
                
              
              //  var param = 'Proceso=darcita';
              //  param += '&idsolicitudestudio=' + idsolicitudestudio + '&idservicio='+ idservicio + '&id_exp=' + idexp;
              //  alert ('darcita:'+param)
                 sendReq.send(param);
               // sendReq.send('Proceso='+'darcita'+'&idsolicitudestudio=' + idsolicitudestudio + '&idservicio='
                     //   + idservicio + '&id_exp=' + idexp);
            }

            function comprobar(idsolicitudestudio, idservicio, idexp) {
               // alert ('Llego a comprobar')
                sendReq.open("POST", 'ajax_CitasServicios.php', true);
                sendReq.onreadystatechange = procesaSearch;
                sendReq.setRequestHeader('Content-Type',
                        'application/x-www-form-urlencoded');
                var param = 'Proceso=comprobar';
                param += '&idsolicitudestudio=' + idsolicitudestudio + '&idservicio='
                        + idservicio + '&id_exp=' + id_exp;
                
//                alert ('comprobar: '+sendReq.onreadystatechange)
//                sendReq.onreadystatechange = function()
//                {
//                    alert(sendReq.status)
//                    if (sendReq.readyState == 4) {//4 The request is complete
//                        alert(sendReq.status)
//                        if (sendReq.status == 200) {//200 means no error.
//
//                            respuesta = sendReq.responseText;
//                            alert ('comprobar: '+respuesta)
//                            return respuesta
//                            //sendReq.onreadystatechange = procesaSearch;
//                        }
//                    }
//                }
                sendReq.send(param);
                
            }
        // function  prueba(){//funcion que recibe el id de solicitud de estudio, el servicio, y el numero de expediente para ser enviados a otro proceso
            // accion=4;

            // fecha="2008-02-13";
            // sendReq.open("POST", 'ajax_CitasServicios.php', true);
            // sendReq.onreadystatechange = procesaSearch;	 
            // sendReq.setRequestHeader('Content-Type',
            // 'application/x-www-form-urlencoded');

            // var param = 'Proceso=prueba';
            // param += '&fecha='+fecha;
            // sendReq.send(param);  	
        // }

            function procesaSearch() {
                if (sendReq.readyState == 4) {
                    if (sendReq.status == 200) {
                        respuesta = sendReq.responseText;
                       // alert('respuesta: '+respuesta+' -accion: '+accion);
                        switch (accion) {
                            case 1:
                                document.getElementById('divrespuesta').innerHTML =
                                        respuesta;
                                break;
                            case 2:
                                if (!(respuesta == -1)) {
                                    document.getElementById('datos').innerHTML =
                                            respuesta;
                                } else {
                                    alert(
                                            ".: Este paciente NO tiene solicitud de estudio de examenes")
                                }
                                break;
                            case 3:
                                if (!(respuesta == -1)) {
                                    document.getElementById('cita').innerHTML =
                                            respuesta;
                                } else {
                                    alert(
                                            "..:Debe Programar Primero la Cita Medica");
                                    window.replace = 
                                            busquedaexp.php
                                }
                                break;
                            case 4:
                                document.getElementById('divrespuesta').innerHTML =
                                        respuesta;
                                break;
                        }
                    }
                }
            }

            function IsNumeric(
                    sText) {//funcion para verificar si el dato que entra es numerico
                var ValidChars = "0123456789-";

                var IsNumber = true;
                var Char;

                for (i = 0; i < sText.length && IsNumber == true; i++)
                {
                    Char = sText.charAt(
                            i); //descubrir que caracter esta llenando una posicion con un string 
                    if (ValidChars.indexOf(Char)
                            == -1) //Despues utilizamos el metodo indexOf para buscar nuestra lista de caracteres validos(ValidChars), si no existe entonces
                    {
                        IsNumber =
                                false;//esto significa que el usuario a ingresado un caracter invalido
                    }
                }
                return IsNumber;
            }


            jQuery(document).ready(function($) {
               var localOptions = setFullCalendarOptions($('#calendar'));

               localOptions['dayClick'] = function(date, jsEvent, view){
                  alert(date);
               };
                localOptions['dayRender'] = function(date, cell){
                  alert(date.format('DD/MM/YYYY'));
               };

               $('#calendar').fullCalendar(localOptions);
            });
        </script>
    </head>

    <body>
    <center>
      

<!--        <div id="divinicial" class="container">-->

<!--            <form action="" name="frmhistorial" enctype="multipart/form-data"  class="form-inline">
                <h3>Busqueda&nbsp;de Solicitudes de Examenes</h3>
                <br>-->
                
<!--                <div class="form-group">
                    <label for="expediente"><strong>No. Expediente:&nbsp;</strong></label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email">
                   
                        <input class="StormyWeatherInput" maxlength="10" id="IdNumeroExp_Name" placeholder="Ingrese Número de Expediente">
                    
                </div>-->
<!--                <table class="StormyWeatherFormTABLE" cellspacing="1" cellpadding="0" border="0"  >
                     BEGIN Error 
                    <tr>
                        <td class="StormyWeatherErrorDataTD" colspan="6">&nbsp;</td> 
                    </tr>
                     END Error 
                    <tr>
                        <td class="StormyWeatherFieldCaptionTD" align="left"><strong>No. Expediente:&nbsp;</strong></td> 
                        <td class="StormyWeatherDataTD">
                            <input class="StormyWeatherInput" maxlength="10" id="IdNumeroExp_Name"></td> 
                    </tr>


                    <tr>
                        <td colspan="6" align="right" nowrap class="StormyWeatherFooterTD">	
                            <input type="button" name="btnSearch" id="btnSearch" value="Buscar" class="StormyWeatherButton" onClick="busquedaexp()">			
                            <input type="button" name="btnSearch" id="btnSearch" value="p" class="StormyWeatherButton" onClick="prueba()">
                        </td>	
                    </tr> 
                </table>-->
<br>
 <div id="divinicial" class="container">
    <div id='calendar' class="fullcalendar"></div>
      </div>
<!--            </form>
        </div>-->

        <div id="divrespuesta">

        </div>

        <div id="datos">
        </div>

        <div id="cita">
        </div>


    </center>
</body>
</html>
