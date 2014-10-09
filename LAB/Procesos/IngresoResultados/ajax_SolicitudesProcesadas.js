var ventana_secundaria;

function objetoAjax() {
    var xmlhttp = false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}
//////////////*************FUNCIONES PARA EL MANEJO DE CADENAS ELININACION DE ESPACIOS EN BLANCO **********//////
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

//Esta funcion mandan a llamar
function calc_edad()
{
    var fecnac1 = document.getElementById("suEdad").value;
    var fecnac2 = fecnac1.substring(0, 10);
    var suEdades = calcular_edad(fecnac2);
    document.getElementById("divsuedad").innerHTML = suEdades;
}


//funcion para calculo de edad

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

// alert("dia: "+dia+" mes:"+mes+" anio:"+ano);
//        08       08        2010
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



function LlenarComboEstablecimiento(idtipoesta)
{
    ajax = objetoAjax();
    opcion = 6;
    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idtipoesta=" + idtipoesta);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4) {//4 The request is complete
            if (ajax.status == 200) {//200 means no error.
                respuesta = ajax.responseText;
                // alert (respuesta)
                document.getElementById('divEstablecimiento').innerHTML = respuesta;
            }
        }
    }
}

function LlenarComboServicio(IdServicio)
{
    ajax = objetoAjax();
    opcion = 7;
    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&IdServicio=" + IdServicio);
    ajax.onreadystatechange = function()
    {

        if (ajax.readyState == 4) {//4 The request is complete
            if (ajax.status == 200) {//200 means no error.
                respuesta = ajax.responseText;
                document.getElementById('divsubserv').innerHTML = respuesta;
            }
        }
    }
}

///////////////////////////////////////***********************//////////////////////////////////////
//FUNCION PARA GUARDAR RESULTADOS
function GuardarResultados()
{
    idsolicitud = document.frmnuevo.txtidsolicitud.value;
    iddetalle = document.frmnuevo.txtiddetalle.value;
    idexamen = document.frmnuevo.txtidexamen.value;
    idrecepcion = document.frmnuevo.txtidrecepcion.value;
    resultado = document.frmnuevo.txtresultado.value;
    lectura = document.frmnuevo.txtlectura.value;
    interpretacion = document.frmnuevo.txtinterpretacion.value;
    observacion = document.frmnuevo.txtcomentario.value;
    responsable = document.frmnuevo.cmbEmpleados.value;
    procedencia = document.frmnuevo.txtprocedencia.value;
    origen = document.frmnuevo.txtorigen.value;
    codigo = document.frmnuevo.cmbResultado2.value;
    opcion = 3;


    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("opcion=" + opcion + "&idsolicitud=" + idsolicitud + "&iddetalle=" + iddetalle + "&idexamen=" + idexamen +
            "&idrecepcion=" + idrecepcion + "&resultado=" + encodeURIComponent(resultado) + "&lectura=" + escape(lectura) +
            "&interpretacion=" + escape(interpretacion) + "&observacion=" + escape(observacion) +
            "&responsable=" + escape(responsable) + "&procedencia=" + procedencia + "&origen=" + origen + "&codigo=" + codigo);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                alert(ajax.responseText);
                document.getElementById('btnGuardar').style.visibility = 'hidden';

            }
        }

    }
}

function GuardarResultadosPlantillaA()
{
    opcion = 3;
    //DATOS DE ENCABEZADO DE LOS RESULTADOS
    //solicitud estudio
    idsolicitud = document.getElementById('txtidsolicitud').value;
    //idrecepcion	
    idrecepcion = document.getElementById('txtidrecepcion').value;
    //detallesolicitud
    iddetalle = document.getElementById('txtiddetalle').value;
    idarea = document.getElementById('txtarea').value;
    idempleado = document.getElementById('cmbEmpleados').value;

    //DATOS PARA EL DETALLE DE LOS RESULTADOS

    valores_resultados = "";
    codigos_resultados = "";
    //valores_lecturas="";
    //	valores_inter="";
    valores_obser = "";
    codigos_examenes = "";
    tabuladores = "";

    if (document.getElementById('oculto').value > 0)
    {
        for (i = 0; i < document.getElementById('oculto').value; i++)
        {
            valores_resultados += document.getElementById('txtresultado[' + i + ']').value + "/";
            codigos_resultados += document.getElementById('oiddetalle[' + i + ']').value + "/";
            //valores_lecturas += document.getElementById('txtlectura['+i+']').value +"/" ;
            //valores_inter+= document.getElementById('txtinter['+i+']').value +"/" ;
            valores_obser += document.getElementById('txtobser[' + i + ']').value + "/";
            codigos_examenes += document.getElementById('oidexamen[' + i + ']').value + "/";
            tabuladores += document.getElementById('txttab[' + i + ']').value + "/";
        }
    }

    ajax.open("POST", "ctrDatosResultadosExamen_PA.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion +
            "&idsolicitud=" + idsolicitud +
            "&idrecepcion=" + idrecepcion +
            "&iddetalle=" + iddetalle +
            "&idarea=" + idarea +
            "&idempleado=" + idempleado +
            "&valores_resultados=" + encodeURIComponent(valores_resultados) +
            "&codigos_resultados=" + codigos_resultados +
            //"&valores_lecturas="+encodeURIComponent(valores_lecturas)+
            //"&valores_inter="+encodeURIComponent(valores_inter)+
            "&valores_obser=" + encodeURIComponent(valores_obser) +
            "&codigos_examenes=" + codigos_examenes + "&tabuladores=" + tabuladores);
    //muy importante este encabezado ya que hacemos uso de un formulario

    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                alert(ajax.responseText);
                document.getElementById('btnGuardar').style.visibility = 'hidden';
            }
        }
    }

}

function ImprimirPlantillaA(idsolicitud, idexamen, resultado, lectura, interpretacion, observacion, responsable, sexo, idedad) {

    ventana_secundaria = window.open("ImprimirPlantillaA.php?var1=" + idsolicitud +
            "&var2=" + idexamen + "&var3=" +
            "&var3=" + encodeURIComponent(resultado) + "&var4=" + escape(lectura) +
            "&var5=" + escape(interpretacion) + "&var6=" + escape(observacion) +
            "&var7=" + responsable + "&var8=" + sexo + "&var9=" + idedad, "ImprimirA", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}

//function ImprimirPlantillaA1(idsolicitud,idarea,responsable,valores_resultados,codigos_resultados,valores_lecturas,valores_inter,valores_obser,cod_examen,establecimiento,sexo,idedad){
function ImprimirPlantillaA1(idsolicitud, idarea, responsable, valores_resultados, codigos_resultados, valores_obser, cod_examen, establecimiento, sexo, idedad) {
//lert (sexo+" edad "+idedad);
    ventana_secundaria = window.open("ImprimirPlantillaA1.php?var1=" + idsolicitud +
            "&var2=" + idarea + "&var3=" + escape(responsable) +
            "&var4=" + encodeURIComponent(valores_resultados) +
            "&var5=" + codigos_resultados +
            //"&var6="+escape(valores_lecturas)+
            // "&var7="+encodeURIComponent(valores_inter)+
            "&var8=" + encodeURIComponent(valores_obser) +
            "&var9=" + codigos_examenes +
            "&var10=" + escape(establecimiento) +
            "&var11=" + sexo +
            "&var12=" + idedad, "ImprimirA1", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}

function ImprimirPlantillaB(idsolicitud, idexamen, responsable, procedencia, origen, observacion, valores_subelementos, codigos_subelementos, valores_elementos, codigos_elementos, controles, controles_ele, nombrearea, establecimiento, responsable, sexo, idedad) {

//alert(idedad);
    ventana_secundaria = window.open("ImprimirPlantillaB.php?var1=" + idsolicitud +
            "&var2=" + idexamen + "&var3=" + responsable + "&var4=" + escape(procedencia) +
            "&var5=" + escape(origen) + "&var6=" + escape(observacion) + "&var7=" + encodeURIComponent(valores_subelementos) +
            "&var8=" + codigos_subelementos + "&var9=" + escape(valores_elementos) +
            "&var10=" + codigos_elementos + "&var11=" + encodeURIComponent(controles) +
            "&var12=" + controles_ele + "&var13=" + escape(nombrearea) +
            "&var14=" + escape(establecimiento) + "&var15=" + escape(responsable) +
            "&var16=" + sexo + "&var17=" + idedad, "ImprimirB", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}

function ImprimirPlantillaC(idsolicitud, idexamen, resultado, responsable, procedencia, origen, observacion, valores_antibioticos, codigos_antibioticos, idbacteria, cantidad, idtarjeta, nombrearea, estab) {

    ventana_secundaria = window.open("ImprimirPlantillaC.php?var1=" + idsolicitud +
            "&var2=" + idexamen + "&var3=" + resultado +
            "&var4=" + encodeURIComponent(responsable) + "&var5=" + procedencia +
            "&var6=" + escape(origen) + "&var7=" + encodeURIComponent(observacion) +
            "&var8=" + encodeURIComponent(valores_antibioticos) +
            "&var9=" + codigos_antibioticos + "&var10=" + idbacteria +
            "&var11=" + encodeURIComponent(cantidad) + "&var12=" + idtarjeta + "&var13=" + escape(nombrearea) +
            "&var14=" + escape(estab), "ImprimirC", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}

function ImprimirPlantillaCN(idsolicitud, idexamen, idarea, resultado, responsable, procedencia, origen, observacion) {

    ventana_secundaria = window.open("ImprimirPlantillaCN.php?var1=" + idsolicitud +
            "&var2=" + idexamen + "&var3=" + idarea +
            "&var4=" + resultado + "&var5=" + responsable + "&var6=" + escape(procedencia) +
            "&var7=" + escape(origen) + "&var8=" + encodeURIComponent(observacion) +
            "&var9=" + escape(estab), "ImprimirCN", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}

function ImprimirPlantillaD(idsolicitud, idexamen, idresultado, idempleado, estab) {
//alert(idsolicitud+"-"+idexamen+"-"+idresultado+"-"+idempleado);
    ventana_secundaria = window.open("ImprimirPlantillaD.php?var1=" + idsolicitud +
            "&var2=" + idexamen + "&var3=" + idresultado + "&var4=" + idempleado + "&var5=" + escape(estab), "ImprimirD", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}

function ImprimirPlantillaE(idsolicitud, idexamen, responsable, procedencia, origen, cometarios, valores, codigos, observacion, estab, sexo, idedad) {
//alert (idarea);
    ventana_secundaria = window.open("ImprimirPlantillaE.php?var1=" + idsolicitud +
            "&var2=" + idexamen + "&var3=" + responsable +
            "&var4=" + procedencia + "&var5=" + origen +
            "&var6=" + encodeURIComponent(cometarios) + "&var7=" + encodeURIComponent(valores) +
            "&var8=" + codigos + "&var9=" + encodeURIComponent(observacion) +
            "&var10=" + escape(estab) + "&var11=" + sexo +
            "&var12=" + idedad, "ImprimirE", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}



//************************************************ funciones plantilla C ********************************************** 
//FUNCION PARA MOSTRAR ANTIBIOTICOS ASOCIADOS A UNA TARJETA plantilla C     OPCION 1
function MostrarAntibioticos()
{
    // if(validartarjeta()){
    opcion = 1;
    idexamen = document.frmnuevo.txtidexamen.value;
    idtarjeta = document.frmnuevo.cmbTarjeta.value;

    ajax.open("POST", "ctrDatosResultadosExamen_PC.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idtarjeta=" + idtarjeta);


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                document.getElementById('divexamen').style.display = "block";
                document.getElementById('divexamen').innerHTML = ajax.responseText;

            }
        }
    }
}

function ValidarCamposPlantillaC()
{
    var resp = true;
    if (document.getElementById('cmbEmpleados').value == "0")
    {
        resp = false;
    }
    for (i = 0; i < document.getElementById('oculto').value; i++)
    {
        if (document.getElementById('txtresultado[' + i + ']').value == "")
        {
            resp = false;
        }

    }
    return resp;
}


//FUNCION PARA MOSTRAR DATOS PREVIOS DE LA PLANTILLA C  	OPCION 2
function MostrarVistaPreviaPlantillaC()
{
    if (ValidarCamposPlantillaC())
    {
        opcion = 2;
        //DATOS DE ENCABEZADO DE LOS RESULTADOS
        //idexamen
        idexamen = document.getElementById('txtidexamen').value;
        //solicitud estudio
        idsolicitud = document.getElementById('txtidsolicitud').value;
        //idrecepcion	
        idrecepcion = document.getElementById('txtidrecepcion').value;
        //detallesolicitud
        iddetalle = document.getElementById('txtiddetalle').value;

        //observacion
        observacion = document.getElementById('cmbObservacion').value;
        //responsable(idempleado)
        idempleado = document.getElementById('cmbEmpleados').value;
        //idarea="";

        idtarjeta = document.frmnuevo.cmbTarjeta.value;
        //tiporespuesta="";
        cantidad = document.getElementById('txtcantidad').value;
       
        //idbacteria="";
        idbacteria = document.getElementById('cmbOrganismo').value;
        //nombrearea="";
        estab = document.getElementById('txtEstablecimiento').value;
        alert(estab);
        //DATOS PARA EL DETALLE DE LOS RESULTADOS
        valores_antibioticos = "";
        codigos_antibioticos = "";
        if (document.getElementById('oculto').value > 0)
        {
            for (i = 0; i < document.getElementById('oculto').value; i++)
            {
                valores_antibioticos += document.getElementById('txtresultado[' + i + ']').value + "/";
                codigos_antibioticos += document.getElementById('oidantibiotico[' + i + ']').value + "/";
            }
        }
        ajax.open("POST", "ctrDatosResultadosExamen_PC.php", true);
        //muy importante este encabezado ya que hacemos uso de un formulario
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores
        ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idsolicitud=" + idsolicitud +
                "&idrecepcion=" + idrecepcion + "&iddetalle=" + iddetalle + "&observacion=" + observacion +
                "&idempleado=" + idempleado + "&valores_antibioticos=" + escape(valores_antibioticos) +
                "&codigos_antibioticos=" + codigos_antibioticos + "&idtarjeta=" + idtarjeta +
                "&idbacteria=" + idbacteria + "&cantidad=" + encodeURIComponent(cantidad) + "& estab=" + estab);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa
                    document.getElementById('divresultado').style.display = "block";
                    document.getElementById('divresultado').innerHTML = ajax.responseText;
                    calc_edad();

                }
            }
        }
    }// del if
    else
    {
        alert("Complete los datos a Ingresar")
    }
}

//FUNCION PARA GUARDAR LOS RESULTADOS POSITOVOS DE LA PLANTILLA C
function GuardarResultadosPlantillaC()
{

    opcion = 3;
    //solicitud estudio
    idsolicitud = document.getElementById('txtidsolicitud').value;

    //idrecepcion	
    idrecepcion = document.getElementById('txtidrecepcion').value;

    //detallesolicitud
    iddetalle = document.getElementById('txtiddetalle').value;

    //idexamen
    idexamen = document.getElementById('txtidexamen').value;

    //observacion
    observacion = document.getElementById('cmbObservacion').value;

    //responsable(idempleado)
    idempleado = document.getElementById('cmbEmpleados').value;

    idbacteria = document.getElementById('cmbOrganismo').value;

    idtarjeta = document.getElementById('cmbTarjeta').value;

    resultado = document.getElementById('cmbResultado').value;
    cantidad = document.getElementById('txtcantidad').value;
    nombrearea = "";

    //hasta aqui todos los datos estan bien
    //DATOS PARA EL DETALLE DE LOS RESULTADOS
    valores_antibioticos = "";
    codigos_antibioticos = "";


    if (document.getElementById('oculto').value > 0)
    {
        for (i = 0; i < document.getElementById('oculto').value; i++)
        {
            valores_antibioticos += document.getElementById('txtresultado[' + i + ']').value + "/";
            codigos_antibioticos += document.getElementById('oidantibiotico[' + i + ']').value + "/";
        }
    }
    //alert(valores_antibioticos);
    //alert(codigos_antibioticos);
    ajax.open("POST", "ctrDatosResultadosExamen_PC.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion +
            "&iddetalle=" + iddetalle + "&observacion=" + encodeURIComponent(observacion) + "&idempleado=" + idempleado + "&valores_antibioticos=" +
            encodeURIComponent(valores_antibioticos) + "&codigos_antibioticos=" + codigos_antibioticos + "&idtarjeta=" + idtarjeta +
            "&tiporespuesta=" + tiporespuesta + "&idarea=" + idarea + "&nombrearea=" + escape(nombrearea) + "&resultado=" + resultado +
            "&idbacteria=" + idbacteria + "&cantidad=" + encodeURIComponent(cantidad));
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                alert(ajax.responseText);
                document.getElementById('Guardar').style.visibility = "hidden";



            }
        }
    }
}

function IngresarOtro() {

    document.getElementById('divresultado').style.display = "none";
    document.getElementById('divexamen').style.display = "none";

}

function validartarjeta()
{
    var resp = true;
    if (document.getElementById('cmbEmpleados').value == "0")
    {
        resp = false;
    }

    return resp;
}

function LlenarComboExamen(idarea)
{
    ajax = objetoAjax();
    opcion = 5;
    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idarea=" + idarea);
    ajax.onreadystatechange = function()
    {

        if (ajax.readyState == 4) {//4 The request is complete
            if (ajax.status == 200) {//200 means no error.
                //respuesta = ajax.responseText;	
                // alert (respuesta)
                document.getElementById('divExamen').innerHTML = ajax.responseText;
            }

        }
    }
}

//FUNCION PARA MOSTRAR OBSERVACIONES DEL RESULTADO DE LA PLANTILLA C
function LlenarObservaciones()
{
    idexamen = document.frmnuevo.txtidexamen.value;
    idtarjeta = document.frmnuevo.cmbTarjeta.value;
    tiporespuesta = document.frmnuevo.cmbResultado.value;
    idarea = document.frmnuevo.txtarea.value;

    opcion = 4;

    if (document.frmnuevo.cmbResultado.value == "P")
    {

        document.getElementById('divResPositivo').style.display = "block";
        document.getElementById('divBotonPrevie').style.display = "none";
        document.getElementById('divObservacion').style.display = "none";

    }
    else {
        document.getElementById('divResPositivo').style.display = "none";
        document.getElementById('divBotonPrevie').style.display = "block";
        document.getElementById('divObservacion').style.display = "block";
    }

    ajax.open("POST", "ctrDatosResultadosExamen_PC.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idtarjeta=" + idtarjeta +
            "&tiporespuesta=" + tiporespuesta + "&idarea=" + idarea);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                document.getElementById('divObservacion').innerHTML = ajax.responseText;

            }
        }
    }
}

//FUNCION PARA MOSTRAR DATOS PREVIOS NEGATIVOS DE PLANTILLA C
function PreviosNegativos()
{

    if (validartarjeta())
    {
        idexamen = document.frmnuevo.txtidexamen.value;
        idtarjeta = document.frmnuevo.cmbTarjeta.value;
        tiporespuesta = document.frmnuevo.cmbResultado.value;
        idarea = document.frmnuevo.txtarea.value;
        idsolicitud = document.frmnuevo.txtidsolicitud.value;
        idempleado = document.frmnuevo.cmbEmpleados.value;
        observacion = document.frmnuevo.cmbObservacion.value;
        resultado = document.frmnuevo.cmbResultado.value;
        estab = document.frmnuevo.txtEstablecimiento.value;
        //alert(estab);	
        opcion = 5;

        if (document.frmnuevo.cmbResultado.value == "P")
        {
            document.getElementById('divResPositivo').style.display = "block";
        }
        else {
            document.getElementById('divResPositivo').style.display = "none";
            document.getElementById('divBotonPrevie').style.display = "block";
        }
        ajax.open("POST", "ctrDatosResultadosExamen_PC.php", true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idtarjeta=" + idtarjeta + "&tiporespuesta=" + tiporespuesta +
                "&idarea=" + idarea + "&idsolicitud=" + idsolicitud + "&idempleado=" + idempleado + "&observacion=" + encodeURIComponent(observacion) +
                "&resultado=" + resultado + "&estab=" + estab);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa
                    document.getElementById('divresultado').style.display = "block";
                    document.getElementById('divresultado').innerHTML = ajax.responseText;
                    //alert(ajax.responseText)
                    calc_edad();
                }
            }
        }
    }
    else
    {
        alert("Seleccione el nombre del Responsable")
    }
}

// FUNCION PARA GUARDAR LOS RESLTADOS NEGATIVOS DE LA PLANTILLA C
function GuardarResultadosNegativosPlantillaC()
{
    opcion = 6;
    //solicitud estudio
    idsolicitud = document.getElementById('txtidsolicitud').value;
    //idrecepcion	
    idrecepcion = document.getElementById('txtidrecepcion').value;
    //detallesolicitud
    iddetalle = document.getElementById('txtiddetalle').value;
    //idexamen
    idexamen = document.getElementById('txtidexamen').value;
    //observacion
    observacion = document.getElementById('cmbObservacion').value;
    resultado = document.getElementById('cmbResultado').value;
    idempleado = document.getElementById('cmbEmpleados').value;

    ajax.open("POST", "ctrDatosResultadosExamen_PC.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("opcion=" + opcion + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion + "&iddetalle=" + iddetalle +
            "&idexamen=" + idexamen + "&observacion=" + escape(observacion) + "&resultado=" + resultado + "&idempleado=" + idempleado);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                alert(ajax.responseText);
                document.getElementById('divresultado').style.display = "block";
                document.getElementById('divexamen').style.display = "none";
                document.getElementById('btnGuardar').style.visibility = 'hidden';

            }
        }
    }

}


//*******************************************************************************************



//FUNCION PARA MOSTRAR RESULTADOS
function MostrarResultadoExamen(idsolicitud, iddetalle, idarea, idexamen, resultado, lectura, interpretacion, observacion, responsable, nombrearea, procedencia, origen, impresion, establecimiento, codigo, fechanac, sexo)
{
    ajax = objetoAjax();
    opcion = 4;

    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    //alert (codresult);
    // alert (fechanac+"--"+&sexo);
    ajax.send("opcion=" + opcion + "&idarea=" + area +
            "&idsolicitud=" + idsolicitud + "&iddetalle=" + iddetalle + "&idexamen=" + idexamen +
            "&resultado=" + encodeURIComponent(resultado) + "&lectura=" + encodeURIComponent(lectura) + "&interpretacion=" + encodeURIComponent(interpretacion) +
            "&observacion=" + encodeURIComponent(observacion) + "&responsable=" + responsable + "&nombrearea=" + nombrearea +
            "&procedencia=" + procedencia + "&origen=" + origen + "&impresion=" + impresion + "&establecimiento=" + establecimiento + "&codigo=" + codigo +
            "&fechanac=" + fechanac + "&sexo=" + sexo);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                document.getElementById('divresultado').style.display = "block";
                document.getElementById('divresultado').innerHTML = ajax.responseText;
                calc_edad();
            }
        }
    }
}

function ValidarCamposPlantillaA()
{
    var resp = true;

    for (i = 0; i < document.getElementById('oculto').value; i++)
    {
        if (document.getElementById('txtresultado[' + i + ']').value == "")
        {
            resp = false;
        }
    }
    return resp;
}

function ValidarCamposPlantillaB()
{
    var resp = true;

    for (i = 0; i < document.getElementById('oculto').value; i++)
    {
        if (document.getElementById('txtresultadosub[' + i + ']').value == "")
        {
            resp = false;
        }
    }

    for (i = 0; i < document.getElementById('ocultoele').value; i++)
    {
        if (document.getElementById('txtresultadoele[' + i + ']').value == "")
        {
            resp = false;
        }

    }
    if (document.getElementById('cmbTabulador').value == 0)
    {
        resp = false;
    }
    return resp;
}






function MostrarVistaPreviaPlantillaB(){

    if (ValidarCamposPlantillaB()) {
        opcion = 2;
        //DATOS DE ENCABEZADO DE LOS RESULTADOS
        //solicitud estudio
        idsolicitud = document.getElementById('txtidsolicitud').value;
        //idrecepcion	
        idrecepcion = document.getElementById('txtidrecepcion').value;
        //detallesolicitud
        iddetalle = document.getElementById('txtiddetalle').value;
        //idexamen
        idexamen = document.getElementById('txtidexamen').value;
        //observacion
        observacion = document.getElementById('txtobservacion').value;
        //responsable(idempleado)
        idempleado = document.getElementById('cmbEmpleados').value;

        procedencia = document.getElementById('txtprocedencia').value;
        origen = document.getElementById('txtorigen').value;
        estab = document.getElementById('txtEstablecimiento').value;
        tab = document.getElementById('cmbTabulador').value;
        fechanac = document.getElementById('txtFechaNac').value;
        sexo = document.getElementById('txtSexo').value;
        // alert (idsolicitud+"-"+idrecepcion+"-"+iddetalle+"-"+idexamen+"-"+observacion+"-"+estab);
        // alert (fechanac+"-"+sexo);
        //DATOS PARA EL DETALLE DE LOS RESULTADOS
        //if (ValidarCamposPlantillaB())
        //{

        valores_subelementos = "";
        codigos_subelementos = "";
        valores_elementos = "";
        codigos_elementos = "";
        controles = "";
        controles_ele = "";
        if (document.getElementById('oculto').value > 0) {
            for (i = 0; i < document.getElementById('oculto').value; i++) {
                valores_subelementos += document.getElementById('txtresultadosub[' + i + ']').value + "/";
                codigos_subelementos += document.getElementById('oidsubelemento[' + i + ']').value + "/";
                controles += document.getElementById('txtcontrol[' + i + ']').value + "/";
            }
        }
        
        if (document.getElementById('ocultoele').value > 0) {
            for (i = 0; i < document.getElementById('ocultoele').value; i++) {
                valores_elementos += document.getElementById('txtresultadoele[' + i + ']').value + "/";
                codigos_elementos += document.getElementById('oidelemento[' + i + ']').value + "/";
                controles_ele += document.getElementById('txtcontrolele[' + i + ']').value + "/";
            }
        }

        ajax.open("POST", "ctrDatosResultadosExamen_PB.php", true);
        //muy importante este encabezado ya que hacemos uso de un formulario
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores
        //ajax.send("opcion="+opcion+"&idexamen="+idexamen);
        ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion +
                "&iddetalle=" + iddetalle + "&observacion=" + escape(observacion) + "&idempleado=" + idempleado + "&procedencia=" + escape(procedencia) +
                "&origen=" + escape(origen) + "&valores_subelementos=" + encodeURIComponent(valores_subelementos) + "&codigos_subelementos=" + codigos_subelementos +
                "&valores_elementos=" + encodeURIComponent(valores_elementos) + "&codigos_elementos=" + codigos_elementos + "&controles=" + encodeURIComponent(controles) +
                "&controles_ele=" + encodeURIComponent(controles_ele) + "&estab=" + escape(estab) + "&tab=" + tab + "&fechanac=" + fechanac + "&sexo=" + sexo);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa
                    document.getElementById('divresultado').style.display = "block";
                    document.getElementById('divresultado').innerHTML = ajax.responseText;
                    calc_edad();
                    //alert(ajax.responseText);
                }
            }
        }

    }
    else
    {
        alert("Complete los datos a Ingresar")
    }

}

function GuardarResultadosPlantillaB()
{
    //  if(ValidarCamposPlantillaB())
    //{
    opcion = 3;
    //DATOS DE ENCABEZADO DE LOS RESULTADOS
    //solicitud estudio
    idsolicitud = document.getElementById('txtidsolicitud').value;
    //idrecepcion	
    idrecepcion = document.getElementById('txtidrecepcion').value;
    //detallesolicitud
    iddetalle = document.getElementById('txtiddetalle').value;
    //idexamen
    idexamen = document.getElementById('txtidexamen').value;
    //observacion
    observacion = document.getElementById('txtobservacion').value;
    //responsable(idempleado)
    idempleado = document.getElementById('cmbEmpleados').value;

    procedencia = document.getElementById('txtprocedencia').value;
    origen = document.getElementById('txtorigen').value;
    tab = document.getElementById('cmbTabulador').value;

    //DATOS PARA EL DETALLE DE LOS RESULTADOS
    valores_subelementos = "";
    codigos_subelementos = "";
    valores_elementos = "";
    codigos_elementos = "";
    controles = "";
    controles_ele = "";
    if (document.getElementById('oculto').value > 0)
    {
        for (i = 0; i < document.getElementById('oculto').value; i++)
        {
            valores_subelementos += document.getElementById('txtresultadosub[' + i + ']').value + "/";
            codigos_subelementos += document.getElementById('oidsubelemento[' + i + ']').value + "/";
            controles += document.getElementById('txtcontrol[' + i + ']').value + "/";
        }
    }
    if (document.getElementById('ocultoele').value > 0)
    {
        for (i = 0; i < document.getElementById('ocultoele').value; i++)
        {
            valores_elementos += document.getElementById('txtresultadoele[' + i + ']').value + "/";
            codigos_elementos += document.getElementById('oidelemento[' + i + ']').value + "/";
            controles_ele += document.getElementById('txtcontrolele[' + i + ']').value + "/";
        }
    }


    ajax.open("POST", "ctrDatosResultadosExamen_PB.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion +
            "&iddetalle=" + iddetalle + "&observacion=" + escape(observacion) + "&idempleado=" + idempleado + "&procedencia=" + procedencia + "&origen=" + origen + "&valores_subelementos=" +
            encodeURIComponent(valores_subelementos) + "&codigos_subelementos=" + codigos_subelementos + "&valores_elementos=" + encodeURIComponent(valores_elementos) +
            "&codigos_elementos=" + codigos_elementos + "&controles=" + encodeURIComponent(controles) + "&controles_ele=" + encodeURIComponent(controles_ele) + "&tab=" + tab);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                //alert(ajax.responseText);
                //document.getElementById('btnGuardar').disabled=disabled;
                alert(ajax.responseText);
                document.getElementById('btnGuardar').style.visibility = 'hidden';
                //document.getElementById('btnGuardar').style.visibility='hidden';
            }
        }
    }
}

//FUNCION LLENAR COMBO DE RESPONSABLES
function LlenarComboResponsable(idarea)
{
    ajax = objetoAjax();
    opcion = 2;
    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idarea=" + idarea);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                document.getElementById('divEncargado').innerHTML = ajax.responseText;
                //alert(ajax.responseText);
            }
        }
    }

}

function CargarExamenes(idsolicitud, idarea, fechanac, sexo, idestandar, idhistorial)
{
    ajax = objetoAjax();
    FechaNac = fechanac;
    Sexo = sexo;
    opcion = 1;
    //alert(FechaNac+" "+Sexo);	
    ajax.open("POST", "ctrDatosResultadosExamen_PA.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    // alert (FechaNac+" - "+Sexo+" - "+idhistorial);
    //alert("historial="+idhistorial);
    ajax.send("opcion=" + opcion + "&idsolicitud=" + idsolicitud + "&idarea=" + idarea +
            "&FechaNac=" + FechaNac + "&Sexo=" + Sexo + "&IdEstandar=" + idestandar + "&idhistorial=" + idhistorial);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                document.getElementById('divexamen').style.display = "block";
                document.getElementById('divexamen').innerHTML = ajax.responseText;
            }
        }
    }
}


//FUNCION PARA CARGAR LOS ELEMENTOS Y SUBELEMENTOS DE UN EXAMEN
function CargarElementosExamen(codigoex, fechanac, sexo, idestandar, idhistorial)
{
    ajax = objetoAjax();
    idexamen = codigoex;
    FechaNac = fechanac;
    Sexo = sexo;
    opcion = 1;

    //alert("Entro a cargar otros datos, opcion: "+opcion+"examen "+idexamen);
    ajax.open("POST", "ctrDatosResultadosExamen_PB.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&FechaNac=" + FechaNac + "&Sexo=" + Sexo + "&idestandar=" + idestandar + "&idhistorial=" + idhistorial);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                document.getElementById('divexamen').style.display = "block";
                document.getElementById('divexamen').innerHTML = ajax.responseText;

            }
        }
    }
}


function validaempleado()
{
    var resp = true;

    if (document.frmnuevo.cmbEmpleados.value == "0")
    {
        resp = false;
    }
    return resp;
}


function IngresarResultados() {
    if (validaempleado()) {
        codigoex = document.frmnuevo.txtidexamen.value;
        fechanac = document.frmnuevo.txtFechaNac.value;
        sexo = document.frmnuevo.txtSexo.value;
        idestandar = document.frmnuevo.txtIdEstandar.value;
        idhistorial = document.frmnuevo.txtIdHistorial.value;
        CargarElementosExamen(codigoex, fechanac, sexo, idestandar, idhistorial);
    } else {
        alert("Ingrese el nombre de Responsable");
    }
}


function IngresarTodosResultados()
{

    if (validaempleado())
    {
        idsolicitud = document.frmnuevo.txtidsolicitud.value;
        idarea = document.frmnuevo.txtarea.value;
        fechanac = document.frmnuevo.txtFechaNac.value;
        sexo = document.frmnuevo.txtSexo.value;
        idestandar = document.frmnuevo.txtIdEstandar.value;
        idhistorial = document.frmnuevo.txtIdHistorial.value;
        // alert(fechanac+" - "+sexo);
        CargarExamenes(idsolicitud, idarea, fechanac, sexo, idestandar, idhistorial);
    }
    else
    {
        alert("Ingrese el nombre de Responsable");
    }
}

//FUNCION PARA MOSTRAR DATOS DE LA SOLICITUD
function MostrarDatos(posicion)
{
    idexpediente = document.getElementById('idexpediente[' + posicion + ']').value;
    idsolicitud = document.getElementById('idsolicitud[' + posicion + ']').value;
    idarea = document.getElementById('cmbArea').value;
    paciente = document.getElementById('paciente[' + posicion + ']').value;
    examen = document.getElementById('examen[' + posicion + ']').value;
    detallesolicitud = document.getElementById('iddetalle[' + posicion + ']').value;
    idexamen = document.getElementById('idexamen[' + posicion + ']').value;
    idrecepcion = document.getElementById('idrecepcion[' + posicion + ']').value;
    plantilla = document.getElementById('plantilla[' + posicion + ']').value;
    nombrearea = document.getElementById('nombrearea[' + posicion + ']').value;
    procedencia = document.getElementById('procedencia[' + posicion + ']').value;
    origen = document.getElementById('origen[' + posicion + ']').value;
    impresion = document.getElementById('impresion[' + posicion + ']').value;
    estab = document.getElementById('establecimiento[' + posicion + ']').value;
    FechaNac = document.getElementById('FechaNac[' + posicion + ']').value;
    Sexo = document.getElementById('Sexo[' + posicion + ']').value;
    IdEstandar = document.getElementById('IdEstandar[' + posicion + ']').value;
    IdHistorial = document.getElementById('IdHistorial[' + posicion + ']').value;
    referido = document.getElementById('referido[' + posicion + ']').value;
    estabext = document.getElementById('estabext[' + posicion + ']').value;
    //alert ("Plnatilla="+plantilla+" Experiente="+idexpediente+" Solicitud="+idsolicitud+" idarea="+idarea+" idhistorial="+IdHistorial+" IdEstandar="+IdEstandar);
    //idhistorial=document.getElementById('idhistorial['+posicion+']').value;
    //alert(plantilla);
    // alert(idexamen);
    // alert(idarea+"**" +plantilla);
    switch (plantilla)
    {
        case "1":
            if (idarea == 12) {
                ventana_secundaria = window.open("ProcDatosResultadosExamen_PA1.php?var1=" + idexpediente +
                        "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idarea + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                        "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                        "&var12=" + impresion + "&var13=" + estab + "&var14=" + FechaNac + "&var15=" + Sexo + "&var16=" + IdEstandar +
                        "&var17=" + IdHistorial + "&referido=" + referido + "&var18="+estabext, "Resultados", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
            }
            else {
                ventana_secundaria = window.open("ProcDatosResultadosExamen_PA.php?var1=" + idexpediente +
                        "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idarea + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                        "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                        "&var12=" + impresion + "&var13=" + estab + "&var14=" + FechaNac + "&var15=" + Sexo + "&var16=" + IdEstandar +
                        "&var17=" + IdHistorial + "&referido=" + referido+ "&var18="+estabext, "Resultados", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
            }
            break;
        case "2":
            ventana_dos = window.open("ProcDatosResultadosExamen_PB.php?var1=" + idexpediente +
                    "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idarea + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                    "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                    "&var12=" + impresion + "&var13=" + estab + "&var14=" + FechaNac + "&var15=" + Sexo + "&var16=" + IdEstandar +
                    "&var17=" + IdHistorial + "&referido=" + referido + "&var18="+estabext, "Resultados", "width=950,height=700,menubar=no,scrollbars=yes,location=no");
            break;
        case "3":
            alert ("Plnatilla="+plantilla+" Experiente="+idexpediente+" Solicitud="+idsolicitud+" idarea="+idarea);
            ventana_dos = window.open("ProcDatosResultadosExamen_PC.php?var1=" + idexpediente +
                    "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idarea + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                    "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                    "&var12=" + impresion + "&var13=" + estab + "&var16=" + IdEstandar +
                    "&var17=" + IdHistorial + "&referido=" + referido+ "&var18="+estabext, "Resultados", "width=950,height=650,menubar=no,scrollbars=yes,location=no");
            break;
        case "4":
            ventana_dos = window.open("ProcDatosResultadosExamen_PD.php?var1=" + idexpediente +
                    "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idarea + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                    "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                    "&var12=" + impresion + "&var13=" + estab + "&var14=" + IdEstandar + "&var16=" + IdEstandar +
                    "&var17=" + IdHistorial + "&referido=" + referido+ "&var18="+estabext, "Resultados", "width=950,height=700,menubar=no,scrollbars=yes");
            break;
        case "5":
            //alert(nombrearea);
            ventana_dos = window.open("ProcDatosResultadosExamen_PE.php?var1=" + idexpediente +
                    "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idarea + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                    "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                    "&var12=" + impresion + "&var13=" + estab + "&var14=" + FechaNac + "&var15=" + Sexo + "&var16=" + IdEstandar +
                    "&var17=" + IdHistorial + "&referido=" + referido+ "&var18="+estabext, "Resultados", "width=950,height=950,menubar=no,scrollbars=yes");
            break;
    }


}

function MostrarObservaciones(tiporespuesta)
{


}


function Cerrar()
{
    self.close()
}


/*function MostrarVistaPrevia()
 {
 alert("voy ha mostrar previos");
 }*/


//***************************************  SOLICITUDES POR �REA *****************************

function SolicitudesPorArea() {
    ajax   = objetoAjax();
    opcion = 1;
    idarea         = document.getElementById('cmbArea').value;
    idexpediente   = document.getElementById('txtexpediente').value;
    idexamen       = document.getElementById('cmbExamen').value;
    IdEstab        = document.getElementById('cmbEstablecimiento').value;
    IdServ         = document.getElementById('CmbServicio').value;
    IdSubServ      = document.getElementById('cmbSubServ').value;
    fechasolicitud = document.getElementById('txtfechasolicitud').value;
    fecharecepcion = document.getElementById('txtfecharecep').value;
    PNombre        = document.getElementById('PrimerNombre').value;
    SNombre        = document.getElementById('SegundoNombre').value;
    PApellido      = document.getElementById('PrimerApellido').value;
    SApellido      = document.getElementById('SegundoApellido').value;
    TipoSolic      = document.getElementById('cmbTipoSolic').value;

    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idarea=" + idarea + "&idexpediente=" + idexpediente + "&idexamen=" + idexamen + "&IdEstab=" + IdEstab +
            "&IdServ=" + IdServ + "&IdSubServ=" + IdSubServ + "&PNombre=" + PNombre + "&SNombre=" + SNombre + "&PApellido=" + PApellido + "&SApellido=" +
            SApellido + "&fechasolicitud=" + fechasolicitud + "&fecharecepcion=" + fecharecepcion + "&TipoSolic=" + TipoSolic);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                document.getElementById('divBusqueda').innerHTML = ajax.responseText;
            }
        }
    }
}

function MostrarVistaPreviaPlantillaA1()
{

    if (ValidarCamposPlantillaA())
    {
        opcion = 2;
        //DATOS DE ENCABEZADO DE LOS RESULTADOS
        //solicitud estudio
        idsolicitud = document.getElementById('txtidsolicitud').value;
        //idrecepcion	
        idrecepcion = document.getElementById('txtidrecepcion').value;
        //detallesolicitud
        iddetalle = document.getElementById('txtiddetalle').value;
        idarea = document.getElementById('txtarea').value;
        idempleado = document.getElementById('cmbEmpleados').value;
        procedencia = document.getElementById('txtprocedencia').value;
        origen = document.getElementById('txtorigen').value;
        estab = document.getElementById('txtEstablecimiento').value;
        fechanac = document.getElementById('txtFechaNac').value;
        sexo = document.getElementById('txtSexo').value;
        //DATOS PARA EL DETALLE DE LOS RESULTADOS

        valores_resultados = "";
        codigos_resultados = "";
        valores_lecturas = "";
        valores_inter = "";
        valores_obser = "";
        codigos_examenes = "";

        if (document.getElementById('oculto').value > 0)
        {
            for (i = 0; i < document.getElementById('oculto').value; i++)
            {
                valores_resultados += document.getElementById('txtresultado[' + i + ']').value + "/";
                codigos_resultados += document.getElementById('oiddetalle[' + i + ']').value + "/";
                //valores_lecturas += document.getElementById('txtlectura['+i+']').value +"/" ;
                //valores_inter+= document.getElementById('txtinter['+i+']').value +"/" ;
                valores_obser += document.getElementById('txtobser[' + i + ']').value + "/";
                codigos_examenes += document.getElementById('oidexamen[' + i + ']').value + "/";
            }
        }

        //alert(valores_resultados);
        ajax.open("POST", "ctrDatosResultadosExamen_PA.php", true);
        //muy importante este encabezado ya que hacemos uso de un formulario
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores
        ajax.send("opcion=" + opcion +
                "&idsolicitud=" + idsolicitud +
                "&idrecepcion=" + idrecepcion +
                "&iddetalle=" + iddetalle +
                "&idarea=" + idarea +
                "&idempleado=" + idempleado +
                "&procedencia=" + procedencia +
                "&origen=" + origen +
                "&valores_resultados=" + encodeURIComponent(valores_resultados) +
                "&codigos_resultados=" + codigos_resultados +
                //"&valores_lecturas="+encodeURIComponent(valores_lecturas)+
                //"&valores_inter="+encodeURIComponent(valores_inter)+
                "&valores_obser=" + encodeURIComponent(valores_obser) +
                "&codigos_examenes=" + escape(codigos_examenes) +
                "&estab=" + estab + "&fechanac=" + fechanac + "&sexo=" + sexo);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa
                    document.getElementById('divresultado').style.display = "block";
                    document.getElementById('divresultado').innerHTML = ajax.responseText;
                    calc_edad();
                }
            }
        }

    }
    else
    {
        alert("Complete los datos a Ingresar")
    }

}

//*************************************** FUNCIONES PARA LA PLANTILLA D *************************************************
function GuardarPlantillaD()
{

    ajax = objetoAjax();
    opcion = 1;
    //DATOS DE ENCABEZADO DE LOS RESULTADOS
    //solicitud estudio
    idsolicitud = document.getElementById('txtidsolicitud').value;
    //idrecepcion	
    idrecepcion = document.getElementById('txtidrecepcion').value;
    //detallesolicitud
    iddetalle = document.getElementById('txtiddetalle').value;
    procedencia = document.getElementById('txtprocedencia').value;
    origen = document.getElementById('txtorigen').value;
    //idexamen
    idexamen = document.getElementById('txtidexamen').value;
    //observacion
    //observacion= document.getElementById('txtobservacion').value;
    //responsable(idempleado)
    idempleado = document.getElementById('cmbEmpleados').value;

    idelemento = document.getElementById('cmbElemento').value;
    idcantidad = document.getElementById('cmbCantidad').value;
    tab = document.getElementById('cmbResultado2').value;
    idestandar = document.getElementById('txtIdEstandar').value;
    //alert (tab);
    idresultado = 0;
    //alert ("tabulador"+tab+"IdSoli"+idsolicitud);
    if (validarplantillaD())
    {
        ajax.open("POST", "ctrDatosResultadosPlantillaD.php", true);

        //muy importante este encabezado ya que hacemos uso de un formulario
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores
        ajax.send("opcion=" + opcion +
                "&idsolicitud=" + idsolicitud +
                "&idrecepcion=" + idrecepcion +
                "&iddetalle=" + iddetalle +
                "&idexamen=" + idexamen +
                "&idempleado=" + idempleado +
                "&idelemento=" + idelemento +
                "&idcantidad=" + idcantidad +
                "&idresultado=" + idresultado +
                "&tab=" + tab);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa
                    document.getElementById('divresultado').innerHTML = ajax.responseText;
                    document.getElementById('divBotonPrevie').style.display = "block";
                    document.getElementById('divBotonGuardar').style.display = "none";

                }
            }
        }
    }
    else {
        alert("Complete la Informacion requerida");
    }
}

//FUNCION PARA GUARDAR ELEMENTO DE TINCION A UN RESULTADO
function GuardarElemento()
{

    ajax = objetoAjax();
    opcion = 2;
    //DATOS DE ENCABEZADO DE LOS RESULTADOS
    //solicitud estudio
    idsolicitud = document.getElementById('txtidsolicitud').value;
    //idrecepcion	
    idrecepcion = document.getElementById('txtidrecepcion').value;
    //detallesolicitud
    iddetalle = "";
    //idexamen
    idexamen = "";
    idempleado = document.getElementById('cmbEmpleados').value;
    //DATOS DEL NUEVO ELEMENTO
    idelemento = document.getElementById('cmbElemento').value;
    idcantidad = document.getElementById('cmbCantidad').value;
    idresultado = document.getElementById('oresultado').value;
    tab = document.getElementById('cmbResultado2').value;
    //alert (tab+"opc=2");
    if (validarplantillaD())
    {
        ajax.open("POST", "ctrDatosResultadosPlantillaD.php", true);

        //muy importante este encabezado ya que hacemos uso de un formulario
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores
        ajax.send("opcion=" + opcion + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion
                + "&iddetalle=" + iddetalle + "&idexamen=" + idexamen + "&idempleado=" + idempleado
                + "&idelemento=" + idelemento + "&idcantidad=" + idcantidad + "&idresultado=" + idresultado + "&tab" + tab);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa

                    if (ajax.responseText == "N")
                    {
                        alert("El elemento ya esta asignado al resultado!");
                    }
                    else
                    {
                        document.getElementById('divresultado').innerHTML = ajax.responseText;
                        document.getElementById('divBotonPrevie').style.display = "block";
                        document.getElementById('divBotonGuardar').style.display = "none";
                    }
                }
            }
        }
    }
    else {
        alert("Complete la Informaci�n requerida");
    }
}

//FUNCION DE VALIDACION DE PLANTILLA
function validarplantillaD()
{
    var resp = true;
    if (document.getElementById('cmbEmpleados').value == "0")
    {
        resp = false;
    }
    if (document.getElementById('cmbCantidad').value == "0")
    {
        resp = false;
    }

    if (document.getElementById('cmbElemento').value == "0")
    {
        resp = false;
    }
    return resp;
}

//FUNCION PARA LA VISTA PREVIA
function VistaPrevia()
{
    ajax = objetoAjax();
    opcion = 3;
    //DATOS DE ENCABEZADO DE LOS RESULTADOS
    //solicitud estudio
    idsolicitud = document.getElementById('txtidsolicitud').value;
    //idrecepcion	
    idrecepcion = document.getElementById('txtidrecepcion').value;
    idexamen = document.getElementById('txtidexamen').value;
    idempleado = document.getElementById('cmbEmpleados').value;
    //DATOS DEL NUEVO ELEMENTO
    idelemento = document.getElementById('cmbElemento').value;
    idcantidad = document.getElementById('cmbCantidad').value;
    idresultado = document.getElementById('oresultado').value;
    estab = document.getElementById('txtestablecimiento').value;
    tab = document.getElementById('cmbResultado2').value;
    //alert(tab);
    ajax.open("POST", "ctrDatosResultadosPlantillaD.php", true);

    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion
            + "&idexamen=" + idexamen + "&idempleado=" + idempleado + "&idelemento=" + idelemento + "&idcantidad=" + idcantidad + "&idresultado=" + idresultado +
            "&estab=" + estab + "&tab=" + tab);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa

                document.getElementById('divpreview').innerHTML = ajax.responseText;
                document.getElementById('divBotonPrevie').style.display = "block";
                document.getElementById('divBotonGuardar').style.display = "none";
                calc_edad();
            }
        }
    }

}

function EliminarElemento(idelemento)
{
    ajax = objetoAjax();
    opcion = 4;
    //DATOS DE ENCABEZADO DE LOS RESULTADOS
    //solicitud estudio
    idsolicitud = document.getElementById('txtidsolicitud').value;
    //idrecepcion	
    idrecepcion = document.getElementById('txtidrecepcion').value;
    //detallesolicitud
    iddetalle = document.getElementById('txtiddetalle').value;
    //idexamen
    idexamen = document.getElementById('txtidexamen').value;
    idempleado = document.getElementById('cmbEmpleados').value;
    //DATOS DEL NUEVO ELEMENTO
    idelemento = document.getElementById('cmbElemento').value;
    idcantidad = "";
    idresultado = document.getElementById('oresultado').value;

    ajax.open("POST", "ctrDatosResultadosPlantillaD.php", true);

    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion
            + "&iddetalle=" + iddetalle + "&idexamen=" + idexamen + "&idempleado=" + idempleado
            + "&idelemento=" + idelemento + "&idcantidad=" + idcantidad + "&idresultado=" + idresultado);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa

                document.getElementById('divresultado').innerHTML = ajax.responseText;
                document.getElementById('divBotonPrevie').style.display = "block";
                document.getElementById('divBotonGuardar').style.display = "none";

            }
        }
    }
}

function ImprimirD() {
//document.getElementById('divTema').style.display="none";
    document.getElementById('divFrmNuevo').style.display = "none";
    document.getElementById('divresultado').style.display = "none";
    document.getElementById('divBotonPrevie').style.display = "block";
    document.getElementById('divBotonGuardar').style.display = "none";
    document.getElementById('btnSalir').style.visibility = 'hidden';
//document.getElementById('btnGuardar').style.visibility='hidden';
    document.getElementById('btnImprimir').style.visibility = 'hidden';

    window.print();
//document.getElementById('btnSalir').style.visibility='visible';
}

//********************************************** FUNCIONES PARA LA PLANTILLA E *****************************************
function IngresarResultadosPlantillaE()
{
    if (validaempleado())
    {
        codigoex = document.frmnuevo.txtidexamen.value;
        fechanac = document.frmnuevo.txtFechaNac.value;
        sexo = document.frmnuevo.txtSexo.value;
        //alert(codigoex);
        CargarProcesosExamen(codigoex, fechanac, sexo);
    }
    else
    {
        alert("Ingrese el nombre de Responsable");
    }
}

function CargarProcesosExamen(codigoex, fechanac, sexo)
{
    ajax = objetoAjax();
    idexamen = codigoex;

    opcion = 1;

    observacion = document.getElementById('txtobservacion').value;
    ajax.open("POST", "ctrDatosResultadosPlantillaE.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores

    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&observacion=" + observacion + "&fechanac=" + fechanac + "&sexo=" + sexo);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                document.getElementById('divexamen').style.display = "block";
                document.getElementById('divexamen').innerHTML = ajax.responseText;

            }
        }
    }
}





function ValidarCamposPlantillaE()
{
    var resp = true;

    for (i = 0; i < document.getElementById('oculto').value; i++)
    {
        if (document.getElementById('txtresultado[' + i + ']').value == "")
        {
            resp = false;
        }
    }

    return resp;
}

function MostrarVistaPreviaPlantillaE()
{
    if (ValidarCamposPlantillaE())
    {

        ajax = objetoAjax();
        idexamen = window.document.frmnuevo.txtidexamen.value;
        opcion = 2;
        idsolicitud = document.getElementById('txtidsolicitud').value;
        estab = document.getElementById('txtestablecimiento').value;
        observacion = document.getElementById('txtobservacion').value;
        idempleado = document.getElementById('cmbEmpleados').value;
        tab = document.getElementById('cmbTabulador').value;
        fechanac = document.getElementById('txtFechaNac').value;
        sexo = document.getElementById('txtSexo').value;

        //DATOS PARA EL DETALLE DE LOS RESULTADOS
        valores = "";
        codigos = "";
        comentarios = "";
        if (document.getElementById('oculto').value > 0)
        {
            for (i = 0; i < document.getElementById('oculto').value; i++)
            {
                valores += document.getElementById('txtresultado[' + i + ']').value + "/";
                codigos += document.getElementById('oidprueba[' + i + ']').value + "/";
            }
        }
        if (document.getElementById('oculto').value > 0)
        {
            for (i = 0; i < document.getElementById('oculto').value; i++)
            {
                comentarios += document.getElementById('txtcomentario[' + i + ']').value + "/";

            }
        }
        ajax.open("POST", "ctrDatosResultadosPlantillaE.php", true);
        //muy importante este encabezado ya que hacemos uso de un formulario
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        //enviando los valores
        ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idsolicitud=" + idsolicitud + "&observacion=" + escape(observacion) +
                "&idempleado=" + idempleado + "&valores=" + encodeURIComponent(valores) + "&codigos=" + codigos + "&comentarios=" + encodeURIComponent(comentarios) +
                "&estab=" + estab + "&tab=" + tab + "&fechanac=" + fechanac + "&sexo=" + sexo);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa
                    document.getElementById('divresultado').style.display = "block";
                    document.getElementById('divresultado').innerHTML = ajax.responseText;
                    calc_edad();
                }
            }
        }
    }
    else
    {
        alert("Complete los datos a Ingresar")
    }
}

function GuardarPlantillaE()
{
    ajax = objetoAjax();
    idexamen = document.frmnuevo.txtidexamen.value;
    opcion = 3;
    //solicitud estudio
    idsolicitud = document.getElementById('txtidsolicitud').value;
    //idrecepcion	
    idrecepcion = document.getElementById('txtidrecepcion').value;
    //detallesolicitud
    iddetalle = document.getElementById('txtiddetalle').value;
    observacion = document.getElementById('txtobservacion').value;
    idempleado = document.getElementById('cmbEmpleados').value;
    tab = document.getElementById('cmbTabulador').value;
    //DATOS PARA EL DETALLE DE LOS RESULTADOS
    valores = "";
    codigos = "";
    if (document.getElementById('oculto').value > 0)
    {
        for (i = 0; i < document.getElementById('oculto').value; i++)
        {
            valores += document.getElementById('txtresultado[' + i + ']').value + "/";
            codigos += document.getElementById('oidprueba[' + i + ']').value + "/";
            comentarios += document.getElementById('txtcomentario[' + i + ']').value + "/";
        }
    }

    ajax.open("POST", "ctrDatosResultadosPlantillaE.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    //alert(tab);
    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion +
            "&iddetalle=" + iddetalle + "&observacion=" + encodeURIComponent(observacion) + "&idempleado=" + idempleado
            + "&valores=" + encodeURIComponent(valores) + "&codigos=" + codigos + "&comentarios=" + encodeURIComponent(comentarios) + "&tab=" + tab);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                alert(ajax.responseText);
                document.getElementById('btnGuardar').style.visibility = 'hidden';

            }
        }
    }
}

function llenarComboTipoSolicitud() {
    jQuery.ajaxSetup({
        error: function(jqXHR, exception) {
            if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status === 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status === 500) {
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
        url: 'ctrSolicitudesProcesadas.php',
        async: true,
        dataType: 'json',
        type: 'POST',
        data: { opcion: 9 },
        success: function(object) {
            if(object.status) {
                jQuery.each(object.data, function(idx, val) {
                    jQuery('#cmbTipoSolic').append($("<option></option>").attr("value",val.idtiposolicitud).text(val.tiposolicitud));
                });
            }
        }
    });
}
