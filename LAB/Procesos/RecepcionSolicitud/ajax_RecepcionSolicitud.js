function objetoAjax() {
    var xmlhttp = false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function xmlhttp() {
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch (e) {
            try {
                xmlhttp = new XMLHttpRequest();
            }
            catch (e) {
                xmlhttp = false;
            }
        }
    }
    if (!xmlhttp)
        return null;
    else
        return xmlhttp;
}//xmlhttp

/***********FUNCIONES PARA EL MANEJO DE CADENAS ELININACION DE ESPACIOS EN BLANCO **********/
function trim(str)
{
    var resultstr = "";

    resultstr = trimleft(str);
    resultstr = trimright(resultstr);
    return resultstr;
}

function trimright(str) {
    var resultStr = "";
    var i = 0;

    // Return immediately if an invalid value was passed in
    if (str + "" == "undefined" || str == null)
        return null;

    // Make sure the argument is a string
    str += "";

    if (str.length == 0)
        resultStr = "";
    else {
        // Loop through string starting at the end as long as there
        // are spaces.
        i = str.length - 1;
        while ((i >= 0) && (str.charAt(i) == " "))
            i--;

        // When the loop is done, we're sitting at the last non-space char,
        // so return that char plus all previous chars of the string.
        resultStr = str.substring(0, i + 1);
    }

    return resultStr;
}

function trimleft(str) {
    for (var k = 0; k < str.length && isWhitespace(str.charAt(k)); k++)
        ;
    return str.substring(k, str.length);
}

function isWhitespace(charToCheck) {
    var whitespaceChars = " \t\n\r\f";
    return (whitespaceChars.indexOf(charToCheck) != -1);
}

//funcion para calcular la edad
function calcular_edad(fecha) {
    //calculo la fecha de hoy
    hoy = new Date();

    //calculo la fecha que recibo
    //La descompongo en un array
    var array_fecha = fecha.split("/");
    //si el array no tiene tres partes, la fecha es incorrecta
    if (array_fecha.length != 3) {
        return false;
    }
    //compruebo que los ano, mes, dia son correctos
    var ano;
    ano = fecha.substring(6, 10);
    if (isNaN(ano)) {
        return false;
    }

    var mes;
    mes = fecha.substring(3, 5);
    if (isNaN(mes)) {
        return false;
    }

    var dia;
    dia = fecha.substring(0, 2);
    if (isNaN(dia)) {
        return false;
    }
    //si el aï¿½o de la fecha que recibo solo tiene 2 cifras hay que cambiarlo a 4
    if (ano <= 99) {
        ano += 1900;
    }

    //resto los aï¿½os de las dos fechas
    annios = hoy.getFullYear() - ano;
    edad = hoy.getFullYear() - ano - 1; //-1 porque no se si ha cumplido aï¿½os ya este aï¿½o
    //si resto los meses y me da menor que 0 entonces no ha cumplido aï¿½os. Si da mayor si ha cumplido


    var meses = hoy.getMonth() + 1 - mes;

    var dias = hoy.getUTCDate() - dia;


    //alert("Dias: "+dias+" Meses:"+meses+" Anios:"+annios+" Edad:"+edad);
//        -3         1        1        0
    var Minimo = "0 dias";
    var diasx = 0;
    if (dias < 0) {
        diasx = dias;
        dias = 30 + dias;
        if (meses == 1) {
            Minimo = dias + " DIAS";
        }

    }

    //alert(diasx+" dias:"+dias);

    if (Minimo == "0 dias" && dias >= 0) {
        Minimo = dias + " dias";
    }

    if (diasx < 0) {
        meses = meses - 1;
    }

    if (meses == 0 && annios == 0) {
        return dias + " DIAS";
    }
    if (annios == 0) {
        return meses + " MESES Y " + Minimo;
    }
    if (meses < 0) {
        meses = 12 + meses;
    }



    if (hoy.getMonth() + 1 - mes < 0) {
        return edad + " a\u00f1os y " + meses + " meses y " + Minimo;
      //       return edad;
    } //+ 1 porque los meses empiezan en 0
    if (hoy.getMonth() + 1 - mes > 0) {
        return (edad + 1) + " a\u00f1os y " + meses + " meses y " + Minimo;
//       return edad+1;
    }
    //entonces es que eran iguales. miro los dias
    //si resto los dias y me da menor que 0 entonces no ha cumplido aï¿½os. Si da mayor o igual si ha cumplido
    if (hoy.getUTCDate() - dia >= 0) {
        return (edad + 1) + " a\u00f1os y " + meses + " meses y " + Minimo;
//       return edad + 1;
    }
    return edad + " a\u00f1os y " + meses + " meses y " + Minimo;
//    return edad;
}


function BuscarDatos() {
    idexpediente      = document.getElementById('txtidexpediente').value;
    fechacita         = document.getElementById('txtfechasolicitud').value;
    idEstablecimiento = document.getElementById('cmbEstablecimiento').value;
    $( "#divResultado" ).empty();
    VerificarExistencia(idexpediente, fechacita, idEstablecimiento, false,0);
}

//FUNCION PARA VERIFICAR SI EXISTEN  DATOS DE LA SOLICITUD
function VerificarExistencia(idexpediente, fechacita, idEstablecimiento, omitir_verificacion, idsolicitud) {
    if (DatosCompletos() || omitir_verificacion) {
        //divResultado=document.getElementById('divResultado');
        ajax = objetoAjax();
        opcion = 2;
        //usando del medoto POST

        ajax.open("POST", "ctrRecepcionSolicitud.php", true);
        //muy importante este encabezado ya que hacemos uso de un formulario
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores

        ajax.send("idexpediente=" + idexpediente + "&fechacita=" + fechacita + "&opcion=" + opcion + "&idEstablecimiento=" + idEstablecimiento+ "&idsolicitud=" + idsolicitud);
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4) {	//mostrar los nuevos registros en esta capa
                if (ajax.status == 200) { //alert (ajax.responseText);
                    if (ajax.responseText.replace(/(\r\n|\n|\r| )/gm,'') == 'D') { //si existen datos para la solicitud
                        MostrarDatosGenerales(idexpediente, fechacita, idEstablecimiento, idsolicitud);

                    } else { //mueestra el mensaje de estado de la solicitud
                        alert(ajax.responseText);
                    }
                }
            }
        }
    } else {
        MostrarTodos();
    }
}

function LlenarEstablecimiento(IdTipoEstab)
{
    ajax = objetoAjax();
    opcion = 8;
    ajax.open("POST", "ctrRecepcionSolicitud.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //alert(IdTipoEstab+"-"+opcion);
    //enviando los valores
    ajax.send("IdTipoEstab=" + IdTipoEstab + "&opcion=" + opcion);


    ajax.onreadystatechange = function() {
        if (ajax.readyState === 4) {//4 The request is complete
            if (ajax.status === 200) {//200 means no error.
                resp = ajax.responseText;
                document.getElementById('divEstablecimiento').innerHTML = resp;
                $("[id^=cmbEstablecimiento]").select2({
                   allowClear: true
                });
            }
        }
    };
}

//FUNCION PARA RECUPERAR LOS DATOS GENERALES DE LA SOLICITUD
function MostrarDatosGenerales(idexpediente, fechacita, idEstablecimiento, idsolicitud) {
    //valores de los text
    document.getElementById('txtidexpediente').value = idexpediente;
    document.getElementById('txtfechasolicitud').value = fechacita;
    //console.log(idexpediente+' -- '+fechacita+' - - idsol '+idsolicitud)
    //instanciamos el objetoAjax
    ajax = objetoAjax();
    //usando del medoto POST
    ajax.open("POST", "RecepcionSolicitud.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("idexpediente=" + idexpediente + "&fechacita=" + fechacita + "&idEstablecimiento=" + idEstablecimiento+"&idsolicitud="+idsolicitud);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            if (ajax.status == 200)
            {
                //mostrar los nuevos registros en esta capa
                document.getElementById('divResultado').innerHTML = ajax.responseText;
                classdatepick();

                calc_edad();
            }
        }
    }
}

function MostrarTodos() {
    idexpediente      = document.getElementById('txtidexpediente').value;
    fechacita         = document.getElementById('txtfechasolicitud').value;
    idEstablecimiento = document.getElementById('cmbEstablecimiento').value;
    var parameters = {'opcion': 9};

    parameters['idexpediente'] = idexpediente;
    parameters['fechacita'] = fechacita;
    parameters['idEstablecimiento'] = idEstablecimiento;

    jQuery.ajax({
        url: 'ctrRecepcionSolicitud.php',
        type: 'post',
        dataType: 'json',
        async: true,
        data: parameters,
        success: function(data) {
            if(data.status){
                searchAllBuild(data);
            }
            else
                alert('Error al procesar los registros...');
        },
        error: function(jqXHR, exception) {
            if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
        }
    });
    //

}


function calc_edad()
{
    //var total = document.getElementById('topei').value;
    var total=1;
    //alert(posicion);

    //for (i = 0; i < document.getElementById('topei').value; i++) {
        //fecnac1 = document.getElementById('suEdad[' + i + ']').value;
        //fecnac2 = fecnac1.substring(0, 10);
        //suEdades = calcular_edad(fecnac2);
        // alert(suEdades);
        //document.getElementById('divsuedad[' + i + ']').innerHTML = suEdades;
    //}
}


//FUNCION PARA CAMBIAR ESTADO DE CADA DETALLE DE LA SOLICITUD
function CambiarEstadoDetalleSolicitud(estado)
{
    idsolicitud = document.getElementById('txtidsolicitud').value;
    idexpediente = document.getElementById('txtidexpediente').value;
    fechasolicitud = document.getElementById('txtfechasolicitud').value;
    opcion = 5;
    idsolicitud = trim(idsolicitud);
    idexpediente = trim(idexpediente);
    fechasolicitud = trim(fechasolicitud);
    //instanciamos el objetoAjax
    ajax = objetoAjax();
    //usando del medoto POST
    ajax.open("POST", "ctrRecepcionSolicitud.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("idexpediente=" + idexpediente + "&fechasolicitud=" + fechasolicitud + "&idsolicitud=" + idsolicitud + "&opcion=" + opcion + "&estado=" + estado);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {
                //mostrar los nuevos registros en esta capa
                alert(ajax.responseText);
            }
        }
    }

}

function imprimiretiquetas(posicion)
{//cambiar imprimir  etiquetas1.php  por imprimir.php
	idexpediente=document.getElementById('txtidexpediente').value;
	idsolicitud=document.getElementById('txtidsolicitud[' + posicion + ']').value;


	//alert idexpediente;
	ventana_secundaria = window.open("../../Consultas_Reportes/ConsultaSolicitudesPaciente/etiquetas.php?var1="+idexpediente+"&var2="+idsolicitud,"etiquetas",										"width=500,height=600,menubar=no,location=no,scrollbars=yes") ;

}


function EnviarDatosSolicitud(posicion)
{
    idexpediente = document.getElementById('txtidexpediente').value;
    fechacita = document.getElementById('txtfechasolicitud').value;
    idsolicitud = document.getElementById('txtidsolicitud[' + posicion + ']').value;
    idEstablecimiento = document.getElementById('cmbEstablecimiento').value;
    //alert (idEstablecimiento);
    ventana_secundaria = window.open("Solicitud.php?var1=" + idexpediente +
            "&var2=" + fechacita + "&var3=" + idsolicitud + "&var4=" + idEstablecimiento, "etiquetas",
            "width=1000,height=600,menubar=no,location=no,scrollbars=yes");

}



function RegistrarNumeroMuestra(posicion)//Registrando Numero de Muestra asociado al paciente
{
    idsolicitud = document.getElementById('txtidsolicitud[' + posicion + ']').value;
    fechacita = document.getElementById('txtfecha[' + posicion + ']').value;
    //objeto AJAX
    ajax = xmlhttp();
    //usando del medoto POST
    opcion = 4;

    ajax.open("POST", "ctrRecepcionSolicitud.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("idsolicitud=" + idsolicitud + "&fechacita=" + fechacita + "&opcion=" + opcion + "&idexpediente=" + idexpediente);
    ajax.onreadystatechange = function()
    {
        //alert(ajax.readyState);
        if (ajax.readyState == 2) {
            //alert('btnActualizar['+posicion+']');
            document.getElementById('btnActualizar[' + posicion + ']').disabled = true;
        }
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {
                if (ajax.responseText.replace(/(\r\n|\n|\r| )/gm,'') != "N")
                {
                    if (ajax.responseText.replace(/(\r\n|\n|\r| )/gm,'') != "NN")
                    {

                        alert(ajax.responseText);
                        HabilitarBoton(idsolicitud, posicion);//habilitando el boton de Impresion de Vi�etas
                        document.getElementById('btnActualizar[' + posicion + ']').disabled = true;
                        document.getElementById('btnRechazar[' + posicion + ']').disabled = true;
                    }
                    else {
                        alert(ajax.responseText);
                        alert("Problemas al Asignar Numero de Muestra..Consulte al Administrador del Sistema 1");
                    }
                }
                else {
                    alert(ajax.responseText);
                    alert("Problema al Asignar Numero de Muestra..Consulte al Administrador del Sistema 2");
                }
            }
        }
    }
}
//Fn_PG
//FUNCION PARA CREAR ARCHIVO DE LA SOLICITUD
function OpcionRechazo(idrechazo, k) {

   //alert(posicion)
   opcion = 11;
   //instanciamos el objetoAjax
   ajax = objetoAjax();
   //usando del medoto POST
   ajax.open("POST", "ctrRecepcionSolicitud.php", true);
   //muy importante este encabezado ya que hacemos uso de un formulario
   ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   //enviando los valores
   ajax.send("&opcion=" + opcion + "&idrechazo=" + idrechazo+"&idk="+ k);
   ajax.onreadystatechange = function() {
       if (ajax.readyState == 4) {
         if (ajax.status == 200)
         {
            //mostrar los nuevos registros en esta capa
            $("#rowdetalle_"+k).css("background-color", "#FFFFE6");
            document.getElementById('divopcionvalidar_'+k).style.display = "none";
            document.getElementById('divopcionrechazo_'+k).style.display = "block";
            document.getElementById('divopcionrechazo_'+k).innerHTML = ajax.responseText;
            // ajax.responseText;
         }
      }
   }
}
//Fn_PG
//FUNCION PARA CREAR ARCHIVO DE LA SOLICITUD
function cancelrechazo(cmbrechazo, k) {

   //alert(cmbrechazo+' - '+k)

    //instanciamos el objetoAjax
    ajax = objetoAjax();
    //usando del medoto POST
    if (cmbrechazo=='xyz'){
    //   alert ('entro')
      document.getElementById('divopcionrechazo_'+k).style.display = "none";
      document.getElementById('divopcionvalidar_'+k).style.display = "block";
//      document.getElementById('divopcionvalidar_'+k).innerHTML = ajax.responseTex
       //$("#validarmuestra_"+k+" option[value='1']").attr('selected', 'selected');
  //     $("#validarmuestra_"+k+" option[value='1']").attr('selected', 'selected');
       $('#validarmuestra_'+k).val("1");
       $("#rowdetalle_"+k).css("background-color", "#FFFFFF");
      document.getElementById('divnewdate_'+k).style.display = "none";

      $( "#divnewdate_"+k ).empty();
     // alert ($('input[name="f_newdate_"]').length)
      if ($('input[name="f_newdate_"]').length == 0){
         $( ".newdate" ).addClass( "hide_me" );
      }
    }
    else{
      opcion = 12;
      temporal=$("#validarmuestra_"+k).val();     
      //if(temporal==1){
         ajax = objetoAjax();
         //usando del medoto POST
         ajax.open("POST", "ctrRecepcionSolicitud.php", true);
         //muy importante este encabezado ya que hacemos uso de un formulario
         ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
         //enviando los valores

         ajax.send("&opcion=" + opcion + "&cmbrechazo=" + cmbrechazo+"&idk="+ k+"&temporal="+temporal);
         ajax.onreadystatechange = function() {
             if (ajax.readyState == 4) {
               if (ajax.status == 200)
               {
                  //mostrar los nuevos registros en esta capa
               //   document.getElementById('colnewdate').style.display = "block";
                 $( ".hide_me" ).removeClass( "hide_me" )
                  document.getElementById('divnewdate_'+k).style.display = "block";
                  document.getElementById('divnewdate_'+k).innerHTML = ajax.responseText;
                     
                   classdate();
                 //  $("#f_newdate_"+k).datepicker({ minDate: newdates });
                   // changenewdate(k);
                  // ajax.responseText;
               }
            }
         }
      //}
    }

}
//fn cambiar la nueva fecha
function changenewdate(k){
   //alert (k)
  // var newdates=moment($('#f_tomamuestra_'+k).val()).format("YYYY-MM-DD");
  if (($('#validarmuestra_'+k).val())==2){
   /* 
   var dateFormat = $('#f_tomamuestra_'+k).val();
   console.log(dateFormat)
  var dateFormat = $.datepicker.formatDate('yy-mm-dd', new Date(dateFormat));
 alert (dateFormat)
   $("#f_newdate_"+k).datepicker('option', 'minDate', new Date(dateFormat));
    */  
  // alert (newdates)
//  //$("#f_newdate_"+k).datepicker({ minDate: newdates });
//   $("#f_newdate_"+k).datepicker("option", "minDate", new Date(newdates));   
//   
   //var $datepickerStart = moment($('#f_tomamuestra_'+k).val()).format("YYYY-MM-DD"); 
  //var newdates=moment($('#f_tomamuestra_'+k).val()).format("YYYY-MM-DD");
//   $( "#f_newdate_"+k ).datepicker({
//  minDate: new Date(newdates)
//});
// $( "#f_newdate_"+k ).datepicker( "option", "minDate", new Date(newdates) );
  }
}

//FUNCION PARA CREAR ARCHIVO DE LA SOLICITUD
function Crear_Archivo(idsolicitud, posicion) {
    NEC = document.getElementById('txtidexpediente').value;
    Solicitud = window.document.getElementById('txtidsolicitud[' + posicion + ']').value;

    //alert(posicion)
    opcion = 6;
    //instanciamos el objetoAjax
    ajax = objetoAjax();
    //usando del medoto POST
    ajax.open("POST", "ctrRecepcionSolicitud.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("&opcion=" + opcion + "&NEC=" + NEC + "&Solicitud=" + Solicitud);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            if (ajax.status == 200)
            {
                ajax.responseText;
            }
        }
    }
}

function IngresarDatosTemp(idsolicitud, posicion) {
    NEC = document.getElementById('txtidexpediente').value;
    Solicitud = window.document.getElementById('txtidsolicitud[' + posicion + ']').value;
    //NEC=document.getElementById('txtidexpediente').value;
    //alert( NEC);
    opcion = 7;
    //instanciamos el objetoAjax
    ajax = objetoAjax();
    //usando del medoto POST
    ajax.open("POST", "ctrRecepcionSolicitud.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("&opcion=" + opcion + "&NEC=" + NEC + "&Solicitud=" + Solicitud);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            if (ajax.status == 200)
            {
                ajax.responseText;
            }
        }
    }
}


//FUNCION PARA CAMBIAR ESTADO DE LA SOLICITUD
function CambiarEstadoSolicitud(estado, idsolicitud, posicion)
{
    idexpediente = document.getElementById('txtidexpediente').value;
    idhistorial = document.getElementById('idhistorial').value;
    referido = document.getElementById('referido').value;
    Solicitud = document.getElementById('txtidsolicitud[' + posicion + ']').value;
    fechacita = "";
     cantresult=$('input[name="hdn_numexOrd"]').length;;
    parametros="";
    i=0;
    j=0;
    if (cantresult>0)
         {
         $('input[name="hdn_numexOrd"]').each(function(i) {
            j = parseInt(i)+1;
          parametros=parametros+"&f_tomamuestra_"+j+"="+document.getElementById("f_tomamuestra_" + this.value).value;
          parametros=parametros+"&iddetalle_"+j+"="+document.getElementById("iddetalle_" + this.value).value;
          parametros=parametros+"&i_idexamen_"+j+"="+document.getElementById("i_idexamen_" + this.value).value;
          validarmuestra=document.getElementById("validarmuestra_" + this.value).value;
          parametros=parametros+"&validarmuestra_"+j+"="+validarmuestra
          if (validarmuestra!=1){
            cmbrechazo_= $("#cmbrechazo_"+this.value).val();
             parametros=parametros+"&cmbrechazo_"+j+"="+cmbrechazo_;
            //En casp que se necesite guardar la siguiente fecha de cita
               if (validarmuestra==2){
               parametros=parametros+"&cmbrechazo_"+j+"="+cmbrechazo_;
               parametros=parametros+"&f_newdate_"+j+"="+$("#f_newdate_"+this.value).val();
            }
//            else{
//               parametros=parametros+"&cmbrechazo_"+j+"=0";
//            }
          }
          else{
             parametros=parametros+"&cmbrechazo_"+j+"=0";
          }
         }
         );
   }
   cantidadnum=j;
   parametros=parametros+"&cantidadnum="+j;
  // alert (parametros)
    opcion = 1;
    idsolicitud = idsolicitud;
    //alert(posicion)
    //instanciamos el objetoAjax
    ajax = objetoAjax();
    //usando del medoto POST
    ajax.open("POST", "ctrRecepcionSolicitud.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("idexpediente=" + idexpediente+ "&fechacita=" + fechacita + "&opcion=" + opcion + "&estado=" + estado + "&idsolicitud=" + idsolicitud + "&Solicitud=" + Solicitud+"&idhistorial="+idhistorial+"&referido="+referido+parametros);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            if (ajax.status == 200)
            {
                //mostrar los nuevos registros en esta capa
                document.getElementById('divCambioEstado').style.display = "none";
                document.getElementById('divCambioEstado').innerHTML = ajax.responseText;

                //verificando el cambio de estado
                if (ajax.responseText.replace(/(\r\n|\n|\r| )/gm,'') == "Y")
                {
                   $(".datepicker").prop( "disabled", true );
                    //alert(ajax.responseText);
                    /* ****** ingresar datos temporales ********************* */
                    //IngresarDatosTemp(Solicitud,posicion);
                    alert("La solicitud fue procesada con exito...");
                    //Crear_Archivo(Solicitud,posicion);

                }
                else {
                    alert(ajax.responseText);
                    alert("La solicitud no puedo ser procesada, contacte al Administrador 1");
                }
            }
        }
    }
}


//FUNCION QUE VERIFICA QUE LOS PARAMETROS DE BUSQUEDA ESTEN COMPLETOS
function DatosCompletos()
{
    var resp = true;
    if (document.getElementById('txtidexpediente').value == "") {
        resp = false;
    }
    if (document.getElementById('txtfechasolicitud').value == "") {
        resp = false;
    }
    return resp;
}

//FUNCION PARA BUSCAR DATOS DE LA SOLICITUD

//FUNCION PARA HABILITAR BOTON Y PROCESAR LA SOLICITUD CAMPBIANDO DE ESTADO
function HabilitarBoton(idsolicitud, posicion) {
    jQuery.ajaxSetup({
        error: function(jqXHR, exception) {
            if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
        }
    });

    jQuery.ajax({
        url: 'ctrRecepcionSolicitud.php',
        async: true,
        dataType: 'json',
        type: 'POST',
        data: { opcion: 10 },
        success: function(object) {
            if(object.status) {
                var estado;

                if(object.data[0].numero === "0") {
                    estado = 'P';
                } else {
                    estado = 'R';
                }

                idsolicitud = document.getElementById('txtidsolicitud[' + posicion + ']').value;
                valor = document.getElementById('txtprecedencia[' + posicion + ']').value;
                if (valor != " ") {
                    //VERIFICANDO QUE LA SOLICITUD HAYA SIDO PROCESADA
                    //Cambia el estado de la solicitud
                    CambiarEstadoSolicitud(estado, idsolicitud, posicion);
                    //CambiarEstadoDetalleSolicitud('TR');
                    //Habilita el boton para la impresion
                    div = document.getElementById('divoculto[' + posicion + ']');
                    div.style.display = "block";
                } else {
                    alert("No se encontraron datos que procesar...");
                }
            } else {
                alert('Error al actualizar el estado de la Solicitud')
            }
        }
    });
}

function rechazosolicitud(idcmbrechazoest){
   if (idcmbrechazoest==2){
      $( "#newdatesol" ).show();
       classdate();
       $('.date').datepicker({ minDate: 1 });
   }
   else{
      $( "#newdatesol" ).hide();
      $( "#newdatesol" ).val();

   }
   $('select[id^=validarmuestra_]').each(function() { $(this).val(idcmbrechazoest);});

}
//
//function oprechazosolfull(idop){
//   if (idop!=0){
//      //$('select[id^=cmbrechazo_]').each(function() { $(this).val(idop);});
//      $('input[id^=divopcionvalidar_]').each(function() { $(this).hide();});
//      $('input[id^=divopcionrechazo_]').each(function() { $(this).show();});
//      $('select[id^=cmbrechazo_]').each(function() { $(this).val(idop);});
////      document.getElementById('divopcionvalidar_'+k).style.display = "none";
////      document.getElementById('divopcionrechazo_'+k).style.display = "block";
//
//   }
////   else{
////      $( "#newdatesol" ).hide();
////   }
////   $('select[id^=validarmuestra_]').each(function() { $(this).val(idcmbrechazoest);});
////
//}


function cancelarechazo(){
   $('select[id^=validarmuestra_]').each(function() { $(this).val(1);});
   $('#cmbrechazoest').val(0);
   $('#cmbrechazosol').val(0);
   $('#fechanewcitasol').val(null);
   $('#observacionrechazo').val(null);
   $('#newdatesol').hide();


}
//function to set fecharechazo > fechacita 
function setfecharechazo(){
   
   classdate();
   fechacita = $('#fecha_solicitud').val();
   $("#fecharechazo").datepicker("option", "minDate", '2016-06-02');
}


function cancelarsolicitud(){
   cmbrechazoest=$('#cmbrechazoest').val();
   cmbrechazosol= $('#cmbrechazosol').val();
   fechanewcitasol=$('#fechanewcitasol').val();
   fecharechazo=$('#fecharechazo').val();
   observacionrechazo=$('#observacionrechazo').val();
   idsolicitud=$('input[id^="txtidsolicitud[0]"]').val();
   fechacita = $('input[id^="txtfecha[0]"]').val();
   pasar=1;
   if ((cmbrechazoest==0)||(cmbrechazoest==0)||(fecharechazo=='')){
   pasar=0;
   alert ("Favor verificar que ha completado todos los campos obligatorios")
   return false;
   }
   if (cmbrechazoest==2 && fechanewcitasol==''){
      alert ("Favor ingresar la fecha de la nueva cita")
      return false;
   }
 console.log('aca va al otro'+'cmbrechazoest'+ cmbrechazoest+ 'cmbrechazosol'+ cmbrechazosol+'fechanewcitasol'+ fechanewcitasol+ 'observacionrechazo'+ observacionrechazo+'idsolicitud'+idsolicitud+ 'fechacita'+fechacita+'fecharechazo'+fecharechazo)
      jQuery.ajax({
          url: 'ctrRecepcionSolicitud.php',
          async: true,
          dataType: 'json',
          type: 'POST',
          data: { opcion: 13 , cmbrechazoest: cmbrechazoest, cmbrechazosol: cmbrechazosol,fechanewcitasol: fechanewcitasol, observacionrechazo: observacionrechazo,idsolicitud:idsolicitud, fechacita:fechacita, fecharechazo:fecharechazo },
        success: function(object) {           
            if(object.status) {
                alert ('Solicitud cancelada');
                  $('#myModal').modal('hide');
                  window.location='Proc_RecepcionSolicitud.php'
            } else {
                alert('Error al cancelar la Solicitud')
            }
        }
    });

};

 //fn pg
 function updatealldates(){
    $( "input[name^='f_tomamuestra_']" ).val( $('#fgentomamxgen').val() );
 };
