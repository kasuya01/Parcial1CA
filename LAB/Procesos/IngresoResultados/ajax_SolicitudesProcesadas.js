var ventana_secundaria;

modal_elements.push({
   id: 'addexam_modal', func:'crearmodal', header:'Agregar Examen', footer:'', widthModal: '900'
 /*
},
{
  id: 'reportvih_modal', func:'reportfvihmodal', header:'FVIH-01', footer:'', widthModal: '900' */
});

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
function calc_edad() {
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
                $("#cmbEstablecimiento").select2({
                    allowClear: true,
                    dropdownAutoWidth: true
                });
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
//Fn pg Plantilla A
function GuardarResultados()
{
    idsolicitud = document.frmnuevo.txtidsolicitud.value;
    iddetalle = document.frmnuevo.txtiddetalle.value;
    idexamen = document.frmnuevo.txtidexamen.value;
    idrecepcion = document.frmnuevo.txtidrecepcion.value;
    resultado = document.getElementById('resultado_').value;
    idresultado = document.getElementById('idresultado_').value;
    marca = document.getElementById('marca_').value;
    lectura = document.getElementById('lectura_').value;
    interpretacion = document.getElementById('interpretacion_').value;
    observacion = document.getElementById('observacion_').value;
    responsable = document.getElementById('idempleado_').value;
    procedencia = document.frmnuevo.txtprocedencia.value;
    origen = document.frmnuevo.txtorigen.value;
    codigo = document.getElementById('codresultado_').value;
    fecha_realizacion = document.getElementById('fecha_realiza_').value;
    fecha_reporte = document.getElementById('fecha_reporte_').value;
    opcion = 3;

    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("opcion=" + opcion + "&idsolicitud=" + idsolicitud + "&iddetalle=" + iddetalle + "&idexamen=" + idexamen +
            "&idrecepcion=" + idrecepcion + "&resultado=" + encodeURIComponent(resultado) + "&lectura=" + lectura +
            "&interpretacion=" + interpretacion + "&observacion=" +observacion+
            "&responsable=" + responsable + "&procedencia=" + procedencia + "&origen=" + origen + "&codigo=" + codigo+ "&fecha_realizacion=" + fecha_realizacion+ "&fecha_reporte=" + fecha_reporte+"&idresultado="+idresultado+"&marca="+marca);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                alert(ajax.responseText);
                document.getElementById('btnGuardar').style.visibility = 'hidden';
                document.getElementById('Imprimir').style.display = 'initial';
                document.getElementById('addexam_modal').style.display = 'initial';


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
   // iddetalle = document.getElementById('txtiddetalle').value;
    idarea = document.getElementById('txtarea').value;
    idempleado = document.getElementById('cmbEmpleados').value;
    fecha_realizacion = document.getElementById('fecha_realizacion').value;
    fecha_reporte = document.getElementById('fecha_reporte').value;
    idempleado = document.getElementById('cmbEmpleados').value;
    txtidrecepcion = document.getElementById('txtidrecepcion').value;
    // f_consulta=document.getElementById('f_consulta').value;
    //DATOS PARA EL DETALLE DE LOS RESULTADOS
  //alert(f_consulta);
    valores_resultados = "";
    codigos_resultados = "";
    //valores_lecturas="";
    //	valores_inter="";
    valores_obser = "";
    codigos_examenes = "";
    examen_metodologia = "";
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
            examen_metodologia += document.getElementById('oidexametodologia[' + i + ']').value + "/";
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
            "&txtidrecepcion=" + txtidrecepcion +
            "&valores_resultados=" + encodeURIComponent(valores_resultados) +
            "&codigos_resultados=" + codigos_resultados +
            //"&valores_lecturas="+encodeURIComponent(valores_lecturas)+
            //"&valores_inter="+encodeURIComponent(valores_inter)+
            "&valores_obser=" + encodeURIComponent(valores_obser) +
            "&codigos_examenes=" + codigos_examenes +
            "&examen_metodologia=" + examen_metodologia+ "&tabuladores=" + tabuladores+"&fecha_realizacion="+fecha_realizacion+"&fecha_reporte="+fecha_reporte);
    //muy importante este encabezado ya que hacemos uso de un formulario

    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                alert(ajax.responseText);
                document.getElementById('btnGuardar').style.visibility = 'hidden';
                document.getElementById('btningresar').style.visibility = 'hidden';
                document.getElementById('Imprimir').style.display = "initial";
                document.getElementById('addexam_modal').style.display = 'initial';
                document.getElementById('divexamen').style.display = "none";
                if  ($("#agregarresults" ).length){
                  document.getElementById('agregarresults').style.visibility = 'hidden';
               }

            }
        }
    }

}
//Fn Pg
function ImprimirPlantillaA(idsolicitud, idexamen, resultado, f_resultado, lectura, interpretacion, observacion, responsable, sexo, idedad, txtnec, proce, origen, iddetalle, marca,fconsulta) {
    ventana_secundaria = window.open("ImprimirPlantillaA.php?var1=" + idsolicitud +
            "&var2=" + idexamen + "&var3=" +
            "&var3=" + encodeURIComponent(resultado) + "&var4=" + escape(lectura) +
            "&var5=" + escape(interpretacion) + "&var6=" + escape(observacion) +
            "&var7=" + responsable + "&var8=" + sexo + "&var9=" + idedad+ "&var10=" +f_resultado+ 
            "&var11=" + txtnec+ "&var12=" + proce+ "&var13=" + origen+ "&var14=" + iddetalle+
            "&var15=" + marca+"&var22="+fconsulta, "ImprimirA", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}

//function ImprimirPlantillaA1(idsolicitud,idarea,responsable,valores_resultados,codigos_resultados,valores_lecturas,valores_inter,valores_obser,cod_examen,establecimiento,sexo,idedad){
function ImprimirPlantillaA1(idsolicitud, idarea, responsable, valores_resultados, codigos_resultados, valores_obser, cod_examen, establecimiento, sexo, idedad, examen_metodologia, txtnec, fecha_reporte , procedencia, origen,fconsulta) {
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
            "&var12=" + idedad+
            "&var13=" + examen_metodologia+
            "&var15=" + fecha_reporte+
            "&var16=" + procedencia+
            "&var17=" + origen+
            "&var14=" + txtnec+
            "&var22="+fconsulta, "ImprimirA1", "width=700,ccc=500,menubar=no,scrollbars=yes,location=no");
}

function ImprimirPlantillaB(idsolicitud, idexamen, responsable, procedencia, origen, observacion, valores_subelementos, codigos_subelementos, valores_elementos, codigos_elementos, controles, controles_ele, nombrearea, establecimiento, responsable, sexo, idedad, valores_combos,idestab,f_tomamuestra,tipomuestra,fconsulta) {

//alert(f_tomamuestra+" muestra= "+tipomuestra);
    ventana_secundaria = window.open("ImprimirPlantillaB.php?var1=" + idsolicitud +
            "&var2=" + idexamen + "&var3=" + responsable + "&var4=" + escape(procedencia) +
            "&var5=" + origen + "&var6=" + escape(observacion) + "&var7=" + encodeURIComponent(valores_subelementos) +
            "&var8=" + codigos_subelementos + "&var9=" + escape(valores_elementos) +
            "&var10=" + codigos_elementos + "&var11=" + encodeURIComponent(controles) +
            "&var12=" + controles_ele + "&var13=" + nombrearea +
            "&var14=" + escape(establecimiento) + "&var15=" + responsable +
            "&var16=" + sexo + "&var17=" + idedad + "&var18=" + valores_combos +"&var19="+idestab+
            "&var20="+f_tomamuestra+"&var21="+tipomuestra+"&var22="+fconsulta, "ImprimirB", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}

function ImprimirPlantillaC(idsolicitud, idexamen, resultado, responsable, procedencia, origen, observacion, valores_antibioticos, codigos_antibioticos, idbacteria, cantidad, idtarjeta, nombrearea, estab,idobservacion,valores_interpretacion,f_tomamuestra,tipomuestra,fconsulta) {
//alert(valores_interpretacion);
    ventana_secundaria = window.open("ImprimirPlantillaC.php?var1=" + idsolicitud +
            "&var2=" + idexamen +
            "&var3=" + resultado +
            "&var4=" + encodeURIComponent(responsable) +
            "&var5=" + procedencia +
            "&var6=" + escape(origen) +
            "&var7=" + encodeURIComponent(observacion) +
            "&var8=" + encodeURIComponent(valores_antibioticos) +
            "&var9=" + codigos_antibioticos +
            "&var10=" + idbacteria +
            "&var11=" + encodeURIComponent(cantidad) +
            "&var12=" + idtarjeta +
            "&var13=" + escape(nombrearea) +
            "&var14=" + escape(estab) +
            "&var15=" + idobservacion +
            "&var16=" + valores_interpretacion+
            "&var17="+f_tomamuestra+
            "&var18="+tipomuestra+
            "&var22="+fconsulta, "ImprimirC", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}

function ImprimirPlantillaCN(idsolicitud, idexamen, idarea, resultado, responsable, procedencia, origen, observacion,f_tomamuestra,tipomuestra,fconsulta) {
//alert (f_tomamuestra);
    ventana_secundaria = window.open("ImprimirPlantillaCN.php?\n\
          var1=" + idsolicitud +
            "&var2=" + idexamen +
            "&var3=" + idarea +
            "&var4=" + resultado +
            "&var5=" + responsable +
            "&var6=" + escape(procedencia) +
            "&var7=" + escape(origen) +
            "&var8=" + encodeURIComponent(observacion) +
            "&var9=" + escape(estab)+
            "&var10="+f_tomamuestra+
            "&var11="+tipomuestra+
            "&var22="+fconsulta, "ImprimirCN", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}

function ImprimirPlantillaD(idsolicitud, idexamen, idresultado, idempleado, estab,f_tomamuestra,tipomuestra) {
//alert(idsolicitud+"-"+idexamen+"-"+idresultado+"-"+idempleado);
    ventana_secundaria = window.open("ImprimirPlantillaD.php?var1=" + idsolicitud +
            "&var2=" + idexamen + "&var3=" + idresultado + "&var4=" + idempleado + "&var5=" + escape(estab) +
            "&var6="+f_tomamuestra + "&var7="+tipomuestra, "ImprimirD", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}

function ImprimirPlantillaE(idsolicitud, idexamen, responsable, procedencia, origen, cometarios, valores, codigos, observacion, estab, sexo, idedad, valores_combos,f_tomamuestra,tipomuestra) {
//alert (idsolicitud+" examen= "+idexamen+" responsable= "+responsable+" procedencia= "+procedencia+
//"origen= "+origen+" cometarios= "+cometarios+" valores= "+valores+" codigos= "+codigos+" observacion= "+observacion+" estab= "+estab+" sexo "+sexo+" edad= "+idedad);
//alert(f_tomamuestra);
    ventana_secundaria = window.open("ImprimirPlantillaE.php?var1=" + idsolicitud +
            "&var2=" + idexamen + "&var3=" + responsable +
            "&var4=" + procedencia + "&var5=" + origen +
            "&var6=" + encodeURIComponent(cometarios) + "&var7=" + encodeURIComponent(valores) +
            "&var8=" + codigos + "&var9=" + encodeURIComponent(observacion) +
            "&var10=" + escape(estab) + "&var11=" + sexo +
            "&var12=" + idedad + "&var13=" +valores_combos +"&var14="+f_tomamuestra+
            "&var15="+tipomuestra , "ImprimirE", "width=950,ccc=700,menubar=no,scrollbars=yes,location=no");
}



//************************************************ funciones plantilla C **********************************************
//FUNCION PARA MOSTRAR ANTIBIOTICOS ASOCIADOS A UNA TARJETA plantilla C     OPCION 1
function MostrarAntibioticos()
{
    // if(validartarjeta()){
    opcion = 1;
    idexamen = document.frmnuevo.txtidexamen.value;
    idtarjeta = document.frmnuevo.cmbTarjeta.value;
    fecharealiz = document.frmnuevo.txtresultrealiza.value;
    fecharesultado = document.frmnuevo.txtresultfin.value;
    f_tomamuestra=document.frmnuevo.txtf_tomamuestra;
    tipomuestra=document.frmnuevo.txttipomuestra;
    f_consulta=document.getElementById('f_consulta').value;
    //alert (fecharealiz+" * "+fecharesultado);
    ajax.open("POST", "ctrDatosResultadosExamen_PC.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idtarjeta=" + idtarjeta+
    "&fecharealiz=" + fecharealiz + "&fecharesultado="+fecharesultado+"&f_tomamuestra="+f_tomamuestra+"&tipomuestra="+tipomuestra+"&f_consulta="+f_consulta );

//+ "&fecharealiz=" + fecharealiz + "&fecharesultado="+fecharesultado
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
     if (document.frmnuevo.txtresultrealiza.value == "")
    {
        resp = false;
    }
     if (document.frmnuevo.txtresultfin.value == "")
    {
        resp = false;
    }

    return resp;


   /* for (i = 0; i < document.getElementById('oculto').value; i++)

    {  // dato [i]= document.getElementById('cmbresultado[' + i + ']'.value;
        //alert(dato[i]);
        if (document.getElementById('cmbresultado[' + i + ']').value == 0)
        {
            resp = false;
        }

    }
    return resp;*/
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
        observacion = document.getElementById('txtobservacion').value;
        //observacion
        idobservacion = document.getElementById('cmbObservacion').value;
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
       // alert(estab);
        fecharealiz = document.getElementById('txtresultrealiza').value;
        fecharesultado =document.getElementById('txtresultfin').value;
        f_tomamuestra=document.getElementById('txtf_tomamuestra').value;
        tipomuestra=document.getElementById('txttipomuestra').value;
        resiembras=document.getElementById('txtresiembras').value;
        bioquimicas=document.getElementById('txtbioquimicas').value;
        //f_consulta=document.getElementById('f_consulta').value;
        f_consulta=document.getElementById('f_consulta').value;
      //  alert(f_consulta);
      //  alert (resiembras+" - "+bioquimicas);
        //DATOS PARA EL DETALLE DE LOS RESULTADOS
        valores_antibioticos = "";
        codigos_antibioticos = "";
        valores_interpretacion = "";
        if (document.getElementById('oculto').value > 0)
        {
            for (i = 0; i < document.getElementById('oculto').value; i++)
            {
                valores_antibioticos += document.getElementById('txtresultado[' + i + ']').value + "/";
                codigos_antibioticos += document.getElementById('oidantibiotico[' + i + ']').value + "/";
                valores_interpretacion += document.getElementById('cmbresultado[' + i + ']').value + "/";
            }

        }
       // alert (valores_interpretacion);
        ajax.open("POST", "ctrDatosResultadosExamen_PC.php", true);
        //muy importante este encabezado ya que hacemos uso de un formulario
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores
        ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idsolicitud=" + idsolicitud +
                "&idrecepcion=" + idrecepcion + "&iddetalle=" + iddetalle + "&observacion=" + observacion +
                "&idempleado=" + idempleado + "&valores_antibioticos=" + encodeURIComponent(valores_antibioticos) +
                "&codigos_antibioticos=" + codigos_antibioticos + "&idtarjeta=" + idtarjeta +
                "&idbacteria=" + idbacteria + "&cantidad=" + encodeURIComponent(cantidad) +
                "&estab=" + estab+"&idobservacion=" + idobservacion + "&fecharealiz=" + fecharealiz +
                "&fecharesultado="+fecharesultado+"&valores_interpretacion="+valores_interpretacion+"&f_tomamuestra="+f_tomamuestra+
                "&tipomuestra="+tipomuestra+"&resiembras="+resiembras+"&bioquimicas="+bioquimicas+"&f_consulta="+f_consulta);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa
                    document.getElementById('divresultado').style.display = "block";
                    document.getElementById('divresultado').innerHTML = ajax.responseText;
                   // document.getElementById('Imprimir').style.visibility = "hidden";
                   // calc_edad();

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
    idobservacion = document.getElementById('cmbObservacion').value;
   // alert (idobservacion);
    observacion = document.getElementById('txtobservacion').value;
    //responsable(idempleado)
    idempleado = document.getElementById('cmbEmpleados').value;

    idbacteria = document.getElementById('cmbOrganismo').value;

    idtarjeta = document.getElementById('cmbTarjeta').value;

    resultado = document.getElementById('cmbResultado').value;
    cantidad = document.getElementById('txtcantidad').value;
    fecharealiz = document.getElementById('txtresultrealiza').value;
    fecharesultado =document.getElementById('txtresultfin').value;
    numresiembras=document.getElementById('txtresiembras').value;
    numbioquimicas=document.getElementById('txtbioquimicas').value;
    nombrearea = "";
 //  alert (numresiembras+" ** "+numbioquimicas);
    //hasta aqui todos los datos estan bien
    //DATOS PARA EL DETALLE DE LOS RESULTADOS
    valores_antibioticos = "";
    codigos_antibioticos = "";
    valores_interpretacion = "";


    if (document.getElementById('oculto').value > 0)
    {
        for (i = 0; i < document.getElementById('oculto').value; i++)
        {
            valores_antibioticos += document.getElementById('txtresultado[' + i + ']').value + "/";
            codigos_antibioticos += document.getElementById('oidantibiotico[' + i + ']').value + "/";
            valores_interpretacion += document.getElementById('cmbresultado[' + i + ']').value + "/";
        }
    }
  // alert(valores_antibioticos);
    //alert(codigos_antibioticos);
    // alert(valores_interpretacion);
    ajax.open("POST", "ctrDatosResultadosExamen_PC.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion +
            "&iddetalle=" + iddetalle + "&observacion=" + encodeURIComponent(observacion) + "&idempleado=" + idempleado + "&valores_antibioticos=" +
            encodeURIComponent(valores_antibioticos) + "&codigos_antibioticos=" + codigos_antibioticos + "&idtarjeta=" + idtarjeta +
            "&tiporespuesta=" + tiporespuesta + "&idarea=" + idarea + "&nombrearea=" + escape(nombrearea) + "&resultado=" + resultado +
            "&idbacteria=" + idbacteria + "&cantidad=" + encodeURIComponent(cantidad)+"&idobservacion=" + idobservacion +
            "&fecharealiz=" + fecharealiz + "&fecharesultado="+fecharesultado+"&valores_interpretacion="+valores_interpretacion+
            "&numresiembras="+numresiembras+"&numbioquimicas="+numbioquimicas);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                alert(ajax.responseText);
                 document.getElementById('btnGuardar').style.visibility = 'hidden';
                document.getElementById('Imprimir').style.display = 'initial';
                document.getElementById('addexam_modal').style.display = 'initial';
                document.getElementById('divexamen').style.display = "block";
                if  ($("#agregarresults" ).length){
                  document.getElementById('agregarresults').style.visibility = 'hidden';



                //document.getElementById('btnGuardar').style.visibility = 'hidden';
                //document.getElementById('Imprimir').style.visibility='visible';
                //document.getElementById('btnGuardar').style.visibility = "hidden";
               // document.getElementById('addexam_modal').style.display = 'initial';

               }

            }
        }
    }
}

function IngresarOtro() {

    document.getElementById('divresultado').style.display = "none";
    document.getElementById('divexamen').style.display = "none";
    document.getElementById('divResPositivo').style.display = "block";
    document.frmnuevo.cmbOrganismo.value = 0 ;
    document.getElementById('cmbTarjeta').value = "0";
}

function validartarjeta()
{
    var resp = true;
    if (document.getElementById('cmbEmpleados').value == "0")
    {
        resp = false;
    }
    if((document.getElementById('cmbObservacion').value =="0")&&(document.getElementById('txtObservacion').value==""))
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
                //alert (ajax.responseText)
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
    idareaPA=document.frmnuevo.txtidareaPA.value;
   // tipomuestra=document.frmnuevo.txttipomuestra;
   
   //alert("Aqui "+idareaPA);
    opcion = 4;
//alert(f_tomamuestra);
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
        document.getElementById('divexamen').style.display = "none";
    }

    ajax.open("POST", "ctrDatosResultadosExamen_PC.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idtarjeta=" + idtarjeta +
            "&tiporespuesta=" + tiporespuesta + "&idarea=" + idarea + "&idareaPA=" + idareaPA);
    //+"&f_tomamuestra="+f_tomamuestra+"&tipomuestra="+tipomuestra
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
     //    iddetalle = document.txtiddetalle.value;
       // observacion = document.frmnuevo.cmbObservacion.value;
       // alert("ajax"+observacion);
        idobservacion = document.getElementById('cmbObservacion').value;
       // resiembras= document.getElementById('txtresiembra').value;
        resultado = document.frmnuevo.cmbResultado.value;
        estab = document.frmnuevo.txtEstablecimiento.value;
        fecharealiz = document.frmnuevo.txtresultrealiza.value;
        fecharesultado =document.frmnuevo.txtresultfin.value;
        f_tomamuestra=document.getElementById('txtf_tomamuestra').value;
        tipomuestra=document.getElementById('txttipomuestra').value;
        resiembras=document.getElementById('txtresiembra').value;
        idareaPA=document.getElementById('txtidareaPA').value;
        observacion=document.getElementById('txtObservacion').value;
       f_consulta=document.getElementById('f_consulta').value;
      //  alert(iddetalle);
      //  alert (idobservacion +" - "+observacion);
        opcion = 5;

        if (document.frmnuevo.cmbResultado.value == "P")
        {
            document.getElementById('divResPositivo').style.display = "block";
        }
        else {
           // alert ( resiembras);
            resiembras=document.getElementById('txtresiembra').value;
            document.getElementById('divResPositivo').style.display = "none";
            document.getElementById('divBotonPrevie').style.display = "block";
        }
       // alert(resiembras);
        ajax.open("POST", "ctrDatosResultadosExamen_PC.php", true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idtarjeta=" + idtarjeta + "&tiporespuesta=" + tiporespuesta +
                "&idarea=" + idarea + "&idsolicitud=" + idsolicitud + "&idempleado=" + idempleado +
                "&resultado=" + resultado + "&estab=" + estab + "&idobservacion=" + idobservacion + "&fecharealiz=" + fecharealiz + "&fecharesultado=" + fecharesultado+
                "&f_tomamuestra="+f_tomamuestra+"&tipomuestra="+ tipomuestra + "&resiembras=" + resiembras +"&idareaPA="+idareaPA+"&observacion="+observacion+
                "&f_consulta="+f_consulta);
        //"&observacion=" + encodeURIComponent(observacion) +
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa
                    document.getElementById('divresultado').style.display = "block";
                    document.getElementById('divresultado').innerHTML = ajax.responseText;
                    //alert(ajax.responseText)
                   // calc_edad();
                }
            }
        }
    }
    else
    {
        alert("Complete los datos a Ingresar")
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
   // observacion = document.getElementById('txtobservacion').value;
    idobservacion = document.getElementById('cmbObservacion').value;
    resultado = document.getElementById('cmbResultado').value;
    idempleado = document.getElementById('cmbEmpleados').value;
    fecharealiz = document.getElementById('txtresultrealiza').value;
    fecharesultado =document.getElementById('txtresultfin').value;
    resiembras=document.getElementById('txtresiembras').value;
    observacion=document.getElementById('txtObservacion').value;
 //  alert(fecharesultado);
    ajax.open("POST", "ctrDatosResultadosExamen_PC.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("opcion=" + opcion + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion + "&iddetalle=" + iddetalle +
            "&idexamen=" + idexamen + "&resultado=" + resultado + "&idempleado=" + idempleado + "&idobservacion="+ idobservacion +
            "&fecharealiz=" + fecharealiz + "&fecharesultado="+fecharesultado +"&resiembras=" + resiembras + "&observacion="+observacion);
    // "&observacion=" + escape(observacion) +
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
                document.getElementById('Imprimir').style.display = 'initial';
                document.getElementById('addexam_modal').style.display = 'initial';
                if  ($("#agregarresults" ).length){
                  document.getElementById('agregarresults').style.visibility = 'hidden';
                }
            }
        }
    }
}


//*******************************************************************************************



//FUNCION PARA MOSTRAR RESULTADOS  Plantilla A
function MostrarResultadoExamen(idsolicitud, iddetalle, idarea, idexamen, resultado, lectura, interpretacion, observacion, responsable, nombrearea, procedencia, origen, impresion, establecimiento, codigo, fechanac, sexo, cmbmetodologia, nec, fecha_realizacion, fecha_reporta, idresultado, marca,f_consulta)
{
    ajax = objetoAjax();
    opcion = 4;
    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
  
    //ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
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
            "&fechanac=" + fechanac + "&sexo=" + sexo+"&cmbmetodologia="+cmbmetodologia+"&nec="+nec+"&fecha_realizacion="+fecha_realizacion+
            "&fecha_reporta="+fecha_reporta+"&idresultado="+idresultado+"&marca="+marca+"&f_consulta="+f_consulta);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                document.getElementById('divresultado').style.display = "block";
                document.getElementById('divresultado').innerHTML = ajax.responseText;
                  window.location.href = '#divresultado';





      //  document.getElementById('fecha_realizacion').value="";
      //  document.getElementById('fecha_reporte').value=document.getElementById('fecha_reporteaux').value;
//        if(idresultado=='x')
//            document.getElementById('txtresultado').value="";
//        else
//        {
//           $("#idresultado option[value='xyz']").attr('selected', 'selected');
//           //$("#cmbResultado2 option[value='0']").attr('selected', 'selected');
//           $("#cmbResultado2").append('<option value=0>Seleccione un resultado</option>');
//        }
        //document.getElementById('txtlectura').value="";
      //  document.getElementById('txtinterpretacion').value="";
      //  document.getElementById('txtcomentario').value="";
      //  document.getElementById('txtlectura').value="";
      //  document.getElementById('txtmarca').value="";
      //  document.getElementById('txtinterpretacion').value="";
       // document.getElementById('cmbResultado2').value=0
      //  document.getElementById('cmbEmpleados').value=0
        document.getElementById('cmbEmpleadosfin').value=0
        $("#cmbResultado2 option[value='0']").attr('selected', 'selected');
                //calc_edad();
            }
        }
    }
}

//Fn PG
//funcion utilizado para agregar resultados con metodologias
function agregaresultado(act)
{
     var formulario='frmnuevo';
     var cantsubele=0;
     var cantele=0;
     if (ValidarCampos())
    {
	idexamen=document.frmnuevo.txtidexamen.value;
	idsolicitud=document.frmnuevo.txtidsolicitud.value;
        iddetalle=document.frmnuevo.txtiddetalle.value;
	idarea=document.frmnuevo.txtarea.value;
	//resultado=document.frmnuevo.txtresultado.value;
        idresultado=document.getElementById('idresultado').value;
        if (idresultado=='x'){
           resultado=document.getElementById('txtresultado').value;
        }
        else
        {
           resultado=$("#idresultado").find('option:selected').text();
        }
	//lectura=document.getElementById('txtlectura').value;
	//interpretacion=document.getElementById('txtinterpretacion').value;
	fecha_realizacion=document.getElementById('fecha_realizacion').value;
	fecha_reporte=document.getElementById('fecha_reporte').value;
	lectura=document.frmnuevo.txtlectura.value;
	marca=document.frmnuevo.txtmarca.value;
	observacion=document.frmnuevo.txtcomentario.value;
	responsable=document.frmnuevo.cmbEmpleados.value;
	responsable_nombre=$("#cmbEmpleados option:selected").text();
	nombrearea=document.frmnuevo.txtnombrearea.value;
	procedencia=document.frmnuevo.txtprocedencia.value;
	origen=document.frmnuevo.txtorigen.value;
	impresion=document.frmnuevo.txtimpresion.value;
        establecimiento=document.frmnuevo.txtEstablecimiento.value;
	codresult=document.frmnuevo.cmbResultado2.value;
	codresult_txt=$("#cmbResultado2 option:selected").text();
        fechanac=document.frmnuevo.txtFechaNac.value;
        sexo=document.frmnuevo.txtSexo.value;
        f_consulta=document.frmnuevo.f_consulta.value;
      //  alert (f_consulta);
       // cmbmetodologia=document.frmnuevo.cmbmetodologia.value;
        var metodologia= document.getElementById('cmbmetodologia');
        var cmbmetodologia=metodologia.value;
        var txtmetodologia=metodologia.options[metodologia.selectedIndex].text;
        txtnec=document.frmnuevo.txtnec.value;
        txtexamen=document.frmnuevo.txtexamen.value;
    }
    else{
            alert('No ha ingresado los campos obligatorios')
            return false;
	}
    canti=$('#cantresultfin').val();
    var cant_campos=document.getElementById('cant_campos').value;
    var num_campos=document.getElementById('num_campos').value;
    var valor_numexdetorde=parseInt(num_campos)+1;
    var id_camposexorde=cant_campos=parseInt(num_campos)+1;
    var cant=$('#addresultado >tbody >tr').length;
   // alert (cant)
    var html = '';
    if (cant == 0){
            masunresultado(1)
            cant1=cant+1;
    }
    if (cant>=1)
    {
       if (canti==0){
        document.getElementById('v_resultfin').value="";
     }
        document.getElementById('v_obseresultfin').value="";
        document.getElementById('txtlecturafin').value="";
        document.getElementById('txtinterpretacionfin').value="";
       // $("#i_idrecha option[value='1']").attr('selected', 'selected');
        if (act!=2){
                cancelaResult();
                }
    }

    //ELEMENTOS del resultado

    html += '<tr><td  style="word-wrap:break-word;">' + txtexamen + '<input type="hidden" id="hdnidexamen_'+id_camposexorde+'" name="hdnidexamen_" value="'+idexamen+'"><input type="hidden" id="hdntxtexamen_'+id_camposexorde+'" name="hdntxtexamen_" value="'+txtexamen+'"></td>';
		/*html += '<td  style="word-wrap:break-word; font-size:86%; padding:0px 0px 0px;">' + d_fechatoma+'<br />'+t_horatoma +
		'<input type="hidden" id="hdnFecToma_'+id_camposexorde+'" name="hdnFecToma_" value="'+d_fechatoma+'">'+
		'<input type="hidden" id="hdnHorToma_'+id_camposexorde+'" name="hdnHorToma_" value="'+t_horatoma+'"></td>';	*/
                html += '<td  style="word-wrap:break-word; font-size:86%; padding:0px 0px 0px;">' + txtmetodologia + '<input type="hidden" id="hdnIdMetodologia_'+id_camposexorde+'" name="hdnIdMetodologia_" value="'+cmbmetodologia+'"><input type="hidden" id="hdnTxtMetodologia_'+id_camposexorde+'" name="hdnTxtMetodologia_" value="'+txtmetodologia+'"></td>';
		html += '<td  style="word-wrap:break-word; font-size:86%; padding:0px 0px 0px;">' + fecha_realizacion + '<input type="hidden" id="hdnFecProc_'+id_camposexorde+'" name="hdnFecProc_" value="'+fecha_realizacion+'"></td>';
		html += '<td  style="word-wrap:break-word; font-size:86%; padding:0px 0px 0px;">' + fecha_reporte + '<input type="hidden" id="hdnFecResu_'+id_camposexorde+'" name="hdnFecResu_" value="'+fecha_reporte+'"></td>';
                html += '<td  style="word-wrap:break-word; font-size:86%; padding:0px 0px 0px;">' + responsable_nombre + '<input type="hidden" id="hdnResp_'+id_camposexorde+'" name="hdnResp_" value="'+responsable+'"><input type="hidden" id="hdnNomResp_'+id_camposexorde+'" name="hdnNomResp_" value="'+responsable_nombre+'"></td>';
		html += '<td  style="word-wrap:break-word;">' + resultado + '<input type="hidden" id="hdnResult_'+id_camposexorde+'" name="hdnResult_" value="'+resultado+'"><input type="hidden" id="hdnIdResult_'+id_camposexorde+'" name="hdnIdResult_" value="'+idresultado+'"></td>';
                html += '<td  style="word-wrap:break-word;">' + marca + '<input type="hidden" id="hdnMarca_'+id_camposexorde+'" name="hdnMarca_" value="'+marca+'"></td>';                 html += '<td  style="word-wrap:break-word;">' + lectura + '<input type="hidden" id="hdnLectura_'+id_camposexorde+'" name="hdnLectura_" value="'+lectura+'"></td>';
		//html += '<td  style="word-wrap:break-word;">' + lectura + '<input type="hidden" id="hdnLectura_'+id_camposexorde+'" name="hdnLectura_" value="'+lectura+'"></td>';

		//html += '<td  style="word-wrap:break-word;">' + interpretacion + '<input type="hidden" id="hdnInterpreta_'+id_camposexorde+'" name="hdnInterpreta_" value="'+interpretacion+'"></td>';
		html += '<td  style="word-wrap:break-word;">' + observacion + '<input type="hidden" id="hdnObserva_'+id_camposexorde+'" name="hdnObserva_" value="'+observacion+'"></td>';
		html += '<td  style="word-wrap:break-word;">' + codresult_txt + '<input type="hidden" id="hdnCodResult_'+id_camposexorde+'" name="hdnCodResult_" value="'+codresult+'"><input type="hidden" id="hdnTxtCodResult_'+id_camposexorde+'" name="hdnTxtCodResult_" value="'+codresult_txt+'"></td>';

                html += '<td align="center"><input type="hidden" id="hdn_numexOrd'+id_camposexorde+'" name="hdn_numexOrd" value="'+valor_numexdetorde+'"/>'+
				'<input type="hidden" id="hdnIdCamposexOrd_'+id_camposexorde+'" name="hdnIdCamposexOrd[]" value="'+id_camposexorde +'" />'+
                                '<input type="hidden" id="f_consulta'+id_camposexorde+'" name="hdnf_consulta" value="'+f_consulta +'" />'+
				'<img class="delete" src="../../../public/images/delete2.png" style="cursor:pointer;" onclick="eliminarmasresultado(this)"/> </td></tr>';

        html += '</table>';
        html += '</td></tr>';

        $('#addresultado').append(html);
        document.getElementById('cant_campos').value=id_camposexorde;
	document.getElementById('num_campos').value=id_camposexorde;

        document.getElementById('fecha_realizacion').value="";
        document.getElementById('fecha_reporte').value=document.getElementById('fecha_reporteaux').value;
       // document.getElementById('txtresultado').value="";
        //document.getElementById('txtlectura').value="";
      //  document.getElementById('txtinterpretacion').value="";
        document.getElementById('txtcomentario').value="";
        document.getElementById('txtlectura').value="";
        document.getElementById('txtmarca').value="";

        if (canti==0)
        document.getElementById('v_resultfin').value="";
        document.getElementById('d_resultfin').value="";
        document.getElementById('v_obseresultfin').value="";
        document.getElementById('txtlecturafin').value="";
        document.getElementById('txtinterpretacionfin').value="";
       // $("#cmbmetodologia option[value='0']").attr('selected', 'selected');
        document.getElementById('cmbmetodologia').value=0
        //$("#cmbEmpleados option[value='0']").attr('selected', 'selected');
        document.getElementById('cmbEmpleados').value=0
        //$("#cmbResultado2 option[value='0']").attr('selected', 'selected');
        //document.getElementById('cmbResultado2').value=0
        //$("#cmbEmpleados2 option[value='0']").attr('selected', 'selected');
       // document.getElementById('cmbEmpleados2').value=0
       // $("#cmbEmpleadosfin option[value='0']").attr('selected', 'selected');
        document.getElementById('cmbEmpleadosfin').value=0
        $('#responde').css('display','');
        $('#valresult').css('display','none');
        $('#cmbEmpleados').select2("val", 0);
        $("#idresultado").remove();
        $("#txtresultado").remove();
        $("#divResult").load(location.href + " #divResult>*", "");
        $("#divResult" ).append( '<textarea  name="txtresultado" cols="50" size="43"  id="txtresultado" placeholder="Debe seleccionar una metodología" disabled="disabled" style="width:96%"/></textarea> <input type="hidden" id="idresultado" name="idresultado" value="x"/>' );
        setCodResultado('xyz')

}


function eliminarmasresultado(r){
var eliminar = confirm("Seguro que desea eliminar la opci\u00f3n seleccionada")
if (eliminar) {
var i=r.parentNode.parentNode.rowIndex;
document.getElementById('addresultado').deleteRow(i);
            var cant=$('#addresultado >tbody >tr').length;
		if (cant==0)
		{
			masunresultado(0)
			document.getElementById('cant_campos').value=0;
			document.getElementById('num_campos').value=0;
		}
		else
		{
		cant= document.getElementById('cant_campos').value;
		document.getElementById('cant_campos').value=(parseInt(cant)-1);
		}
		return false;
	}
}//fin funcion eliminar

//fn pg

function masunresultado(valor){
valor=parseInt(valor);
   if (valor==1)
	{
	mostrarDivmasunoresultado();
	}
	else
	ocultarDivmasunoresultado();
}
//fn pg
function mostrarDivmasunoresultado()
{
    $('#masunoresultado').css('display','');
}
//fn pg
function ocultarDivmasunoresultado()
{
    $('#masunoresultado').css('display','none');
    $('#responde').css('display','none');
    $('#valresult').css('display','none');
}
//Fn pg
//funcion utilizada para validara recepcion d seccion
function cancelaResult(){
	$('#valresult').css('display','none');
	$('#responde').css('display','');
}//fin busqueda
//funcion utilizada para validara recepcion d seccion
function ValidarResultado(){

var cant=$('#addresultado >tbody >tr').length
var html = '';
if (cant == 0){
	alert ("No ha ingresado ningun resultado, no puede validar")
	return false;
}
else
{

	$('#valresult').css('display','');
	$('#responde').css('display','none');
	$('#divresultado2').css('display','none');
        canti=$('#cantresultfin').val();
        if (cant==1){
         if (canti==0){
            document.getElementById('v_resultfin').value=document.getElementById('hdnResult_1').value;
         }
            $("#cmbEmpleadosfin").select2("val", $('#hdnResp_1').val());
            document.getElementById('d_resultfin').value=document.getElementById('hdnFecResu_1').value;
           }
        else {
            a=document.getElementById('num_campos').value;
            document.getElementById('d_resultfin').value=document.getElementById('hdnFecResu_'+a).value;
            // document.getElementById('cmbEmpleadosfin').value=document.getElementById('hdnResp_'+a).value;
             $("#cmbEmpleadosfin").select2("val", $('#hdnResp_'+a).val());
        if(canti==0){
            document.getElementById('v_resultfin').value=document.getElementById('hdnResult_'+a).value;
            }
         }
        //  document.getElementById('f_consulta').value=document.getElementById('hdnf_consulta').value;
        
//alert(document.getElementById('f_consulta').value=document.getElementById('hdnf_consulta').value);
}
}
//Fn_pg
////////////////////////////////////////*******************************************************
//Funcion para guardar orden con resultado

//funcionn utilizada para enviar los datos ingresados en el formulario
function enviarDatosResult(val, paso){
	var formulario = 'frmnuevo';
	var i=0;
	var j=0;
	var parametros="";
        divFormulario = document.getElementById('formulario');
        divResultado = document.getElementById('resultado');
        divResponde = document.getElementById('enviado');
       
        val=parseInt(val);
        paso=parseInt(paso);

 //i_idgruprue=document.getElementById('i_idgruprue').value;
 parametros=parametros+"&idsolicitud="+document.getElementById('txtidsolicitud').value;
 parametros=parametros+"&iddetalle="+document.getElementById('txtiddetalle').value;
 parametros=parametros+"&val="+val;
 parametros=parametros+"&idrecepcion="+document.getElementById('txtidrecepcion').value;
 
 //parametros=parametros+"&resultado="+document.getElementById('txtresultado').value;

 if (val==1)
 {
    idresultadofin=document.getElementById('idresultadofin').value;
        if (idresultadofin=='x'){
           v_resultfin=document.getElementById('v_resultfin').value;
        }
        else
        {
           if (idresultadofin!='xyz')
               v_resultfin=$("#idresultadofin").find('option:selected').text();
            else
               v_resultfin="";
        }
 //v_resultfin=document.getElementById('v_resultfin').value;
 d_resultfin=document.getElementById('d_resultfin').value;
 cmbEmpleadosfin=document.getElementById('cmbEmpleadosfin').value;
 	if (v_resultfin=="" || d_resultfin=="" || cmbEmpleadosfin==0)
	{
		alert ("Revise que ha ingresado información en Resultado, Fecha Resultado y Persona que valido por favor");
		return false;
	}
 parametros=parametros+"&v_resultfin="+v_resultfin;
 parametros=parametros+"&idresultadofin="+document.getElementById('idresultadofin').value;
 parametros=parametros+"&v_obseresultfin="+document.getElementById('v_obseresultfin').value;
 parametros=parametros+"&cmbEmpleadosfin="+document.getElementById('cmbEmpleadosfin').value;
 parametros=parametros+"&d_resultfin="+document.getElementById('d_resultfin').value;
 parametros=parametros+"&v_interpretacion="+document.getElementById('txtinterpretacionfin').value;
 parametros=parametros+"&v_lectura="+document.getElementById('txtlecturafin').value;
 parametros=parametros+"&fconsulta="+document.getElementById('f_consulta').value;
//alert(document.getElementById('f_consulta').value);
/*	//consultar cuantas pruebas de seguimiento tiene
cantsegui=document.getElementById('cantsegui').value;
 parametros=parametros+"&cantsegui="+document.getElementById('cantsegui').value;
 var s=0
 $('input:checkbox:checked').each(function(i) {
                   	s = parseInt(i)+1;
					parametros=parametros+"&hdn_idflujo"+s+"="+this.value;
                });
 parametros=parametros+"&cantidadSegCheck="+s;*/

 }
 /*
 parametros=parametros+"&i_idemppl="+document.getElementById('i_idemppl').value;
 parametros=parametros+"&i_idgruprue="+document.getElementById('i_idgruprue').value;
 parametros=parametros+"&i_idestabOrdena="+document.getElementById('i_idestabOrdenantes').value;*/

if ((document.getElementById('cantele'))!= null){

 cantele=document.getElementById('cantele').value;
 }
 else {
	cantele=0;
	}
	 parametros=parametros+"&cantele="+cantele;
	var i=0;
	var j=0;
	var nothing="";

	 var cantresult=$('#addresultado >tbody >tr').length;

		if (cantresult>0)
		{
		$('input[name="hdn_numexOrd"]').each(function(i) {
					j = parseInt(i)+1;
					parametros=parametros+"&hdnidexamen_"+j+"="+document.getElementById("hdnidexamen_" + this.value).value;
					parametros=parametros+"&hdnIdMetodologia_"+j+"="+document.getElementById("hdnIdMetodologia_" + this.value).value;
					parametros=parametros+"&hdnFecProc_"+j+"="+document.getElementById("hdnFecProc_" + this.value).value;
					/*parametros=parametros+"&hdnFecToma_"+j+"="+document.getElementById("hdnFecToma_" + this.value).value;
					parametros=parametros+"&hdnHorToma_"+j+"="+document.getElementById("hdnHorToma_" + this.value).value;	*/
					parametros=parametros+"&hdnFecResu_"+j+"="+document.getElementById("hdnFecResu_" + this.value).value;
					parametros=parametros+"&hdnResp_"+j+"="+document.getElementById("hdnResp_" + this.value).value;
					parametros=parametros+"&hdnResult_"+j+"="+document.getElementById("hdnResult_" + this.value).value;
					parametros=parametros+"&hdnIdResult_"+j+"="+document.getElementById("hdnIdResult_" + this.value).value;
					//parametros=parametros+"&hdnLectura_"+j+"="+document.getElementById("hdnLectura_" + this.value).value;
					//parametros=parametros+"&hdnInterpreta_"+j+"="+document.getElementById("hdnInterpreta_" + this.value).value;
					//parametros=parametros+"&hdnIdTipoRes_"+j+"="+document.getElementById("hdnIdTipoRes_" + this.value).value;
					parametros=parametros+"&hdnMarca_"+j+"="+document.getElementById("hdnMarca_" + this.value).value;
					parametros=parametros+"&hdnLectura_"+j+"="+document.getElementById("hdnLectura_" + this.value).value;
					parametros=parametros+"&hdnObserva_"+j+"="+document.getElementById("hdnObserva_" + this.value).value;
					parametros=parametros+"&hdnCodResult_"+j+"="+document.getElementById("hdnCodResult_" + this.value).value;
                                        parametros=parametros+"&f_consulta="+document.getElementById("f_consulta" + this.value).value;
					/*if (i_idgruprue==4){
					parametros=parametros+"&hdnMarcReac_"+j+"="+document.getElementById("hdnMarcReac_" + this.value).value;
					parametros=parametros+"&hdnLectExa_"+j+"="+document.getElementById("hdnLectExa_" + this.value).value;
					}
					else{
					parametros=parametros+"&hdnMarcReac_"+j+"="+nothing;
					parametros=parametros+"&hdnLectExa_"+j+"="+nothing;
					}
                                        */



					//reac=document.getElementById("hdnReacExa_" + this.value).value;
					//alert ('reacexa'+reac.length)
					//removerOri();
				   }
				);
		}
		cantidadnum=j;
	  parametros=parametros+"&cantidadnum="+j;
//alert (parametros)
//cerrardivgruprue();

  //instanciamos el objetoAjax
  ajax=objetoAjax();
  //uso del medoto POST
  //archivo que realizarï¿½ la operacion
  //registro.php
  if (paso==0){
      //opcion = 4;
     // parametros=parametros+"&opcion="+12;
	//ajax.open("POST", "../ingresar/registro.php",true);
        ajax.open("POST", "ctrSolicitudesProcesadas.php", true);

  }
  else
  {
	//ajax.open("POST", "../validar/registro2.php",true);
  }
  ajax.onreadystatechange=function() {
	if (ajax.readyState==4)
	{
		//mostrar resultados en esta capa
	/*	divResponde.innerHTML = ajax.responseText;
		divFormulario.innerHTML = "";*/
		//busquedAnalisis();
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                document.getElementById('divresultado').style.display = "block";
                document.getElementById('divresultado').innerHTML = ajax.responseText;
               // calc_edad();
           //    alert (parametros)
           if (val==1)
                VerResultados2(parametros);
             document.getElementById('agregarresults').style.visibility = 'hidden';
                //alert(ajax.responseText);
            }
	}
  }

  ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
  ajax.send(parametros+"&opcion="+12)

}//fin enviarDatos

//Fn Pg para ver los resultados cuando es plantilla A2

function VerResultados2(parametros)
{
    ajax = objetoAjax();
    opcion = 13;

    parametros=parametros+"&nombrearea="+document.frmnuevo.txtnombrearea.value;
    parametros=parametros+"&procedencia="+document.frmnuevo.txtprocedencia.value;
    parametros=parametros+"&origen="+document.frmnuevo.txtorigen.value;
    parametros=parametros+"&establecimiento="+document.frmnuevo.txtEstablecimiento.value;
    parametros=parametros+"&fechanac="+document.frmnuevo.txtFechaNac.value;
    parametros=parametros+"&sexo="+document.frmnuevo.txtSexo.value;
    parametros=parametros+"&txtnec="+document.frmnuevo.txtnec.value;
    parametros=parametros+"&txtpaciente="+document.frmnuevo.txtpaciente.value;
    parametros=parametros+"&conocido_por="+document.frmnuevo.txtpaciente.value;
    parametros=parametros+"&idexamen="+document.frmnuevo.txtidexamen.value;
    parametros=parametros+"&f_consulta="+document.frmnuevo.f_consulta.value;
  //  alert(document.frmnuevo.f_consulta.value);
  //  parametros=parametros+"&opcion="+13;
    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    //ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    //alert (codresult);
    // alert (fechanac+"--"+&sexo);
    //alert (parametros)
    ajax.send(parametros+"&opcion="+13);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                $('#valresult').css('display','none');
                document.getElementById('divresultado2').style.display = "block";
                document.getElementById('divresultado2').innerHTML = ajax.responseText;
                window.location.href = '#divresultado2';
              //  calc_edad();
            }
        }
    }

}//fin mostrarresultados plantillaA2

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
        if ((document.getElementById('txtresultadosub[' + i + ']').value == "")||(document.getElementById('txtresultadosub[' + i + ']').value == 0))
        {
            resp = false;
        }
    }

    for (i = 0; i < document.getElementById('ocultoele').value; i++)
    {
        if ((document.getElementById('txtresultadoele[' + i + ']').value == "")||(document.getElementById('txtresultadoele[' + i + ']').value == ""))
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
       // alert (observacion);
        //responsable(idempleado)
        idempleado = document.getElementById('cmbEmpleados').value;

        procedencia = document.getElementById('txtprocedencia').value;
        origen = document.getElementById('txtorigen').value;
        estab = document.getElementById('txtEstablecimiento').value;
        tab = document.getElementById('cmbTabulador').value;
        fechanac = document.getElementById('txtFechaNac').value;
        sexo = document.getElementById('txtSexo').value;
        fecharealiz = document.getElementById('txtresultrealiza').value;
        fecharesultado=document.getElementById('txtfresultado').value;
        subservicio=document.getElementById('txtsubservicio').value;
        idestab = document.getElementById('txtIdEstablecimiento').value;
        f_tomamuestra=document.getElementById('txtf_tomamuestra').value;
        tipomuestra=document.getElementById('txttipomuestra').value;
         f_consulta=document.getElementById('txtf_consulta').value;
        // alert(f_consulta);
       // alert(f_tomamuestra+"  "+tipomuestra);
        // alert (idsolicitud+"-"+idrecepcion+"-"+iddetalle+"-"+idexamen+"-"+observacion+"-"+estab);
        // alert (fechanac+"-"+sexo);
        //DATOS PARA EL DETALLE DE LOS RESULTADOS


        valores_subelementos = "";
        codigos_subelementos = "";
        valores_elementos = "";
        codigos_elementos = "";
        controles = "";
        controles_ele = "";
        valores_combos="";
        if (document.getElementById('oculto').value > 0) {
            for (i = 0; i < document.getElementById('oculto').value; i++) {
                if ($("select[id='txtresultadosub["+i+"]'] option:selected").length>1){
                   var selectBox = document.getElementById('txtresultadosub['+i+']');
                   valores_subelementos +=GetSelectValues(selectBox )+ '|';
                   //console.log(valores_subelementos);
                   valores_combos +=  "/";
            }
                else{
                valores_subelementos += document.getElementById('txtresultadosub[' + i + ']').value + "|";
                valores_combos += document.getElementById('totcombo[' + i + ']').value + "/";
               }

                codigos_subelementos += document.getElementById('oidsubelemento[' + i + ']').value + "/";
                controles += document.getElementById('txtcontrol[' + i + ']').value + "/";
                //alert(valores_combos);
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
                "&iddetalle=" + iddetalle + "&observacion=" + observacion + "&idempleado=" + idempleado + "&procedencia=" + escape(procedencia) +
                "&origen=" + escape(origen) + "&valores_subelementos=" + escape(valores_subelementos) + "&codigos_subelementos=" + codigos_subelementos +
                "&valores_elementos=" + escape(valores_elementos) + "&codigos_elementos=" + codigos_elementos + "&controles=" + encodeURIComponent(controles) +
                "&controles_ele=" + encodeURIComponent(controles_ele) + "&estab=" + encodeURIComponent(estab) + "&tab=" + tab + "&fechanac=" + fechanac + "&sexo=" + sexo+
                "&fecharealiz=" + fecharealiz + "&fecharesultado="+fecharesultado+"&subservicio="+subservicio+"&valores_combos="+ valores_combos+"&idestab="+idestab+
                "&f_tomamuestra="+f_tomamuestra+"&tipomuestra="+encodeURIComponent(tipomuestra)+"&f_consulta="+f_consulta);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa
                    document.getElementById('divresultado').style.display = "block";
                    document.getElementById('divresultado').innerHTML = ajax.responseText;
                    window.location.href = '#divresultado';
                    // document.getElementById('Imprimir').style.visibility = "hidden";
                    //calc_edad();
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
  //  alert(observacion);
    //responsable(idempleado)
    idempleado = document.getElementById('cmbEmpleados').value;

    procedencia = document.getElementById('txtprocedencia').value;
    origen = document.getElementById('txtorigen').value;
    tab = document.getElementById('cmbTabulador').value;
    fecharealiz = document.getElementById('txtresultrealiza').value;
    fecharesultado=document.getElementById('txtfresultado').value;
    //DATOS PARA EL DETALLE DE LOS RESULTADOS
    valores_subelementos = "";
    codigos_subelementos = "";
    valores_elementos = "";
    codigos_elementos = "";
    controles = "";
    controles_ele = "";
    valores_combos="";
    if (document.getElementById('oculto').value > 0)
    {
        for (i = 0; i < document.getElementById('oculto').value; i++)
        {
            if ($("select[id='txtresultadosub["+i+"]'] option:selected").length>1){
                   var selectBox = document.getElementById('txtresultadosub['+i+']');
                   valores_subelementos +=GetSelectValues(selectBox )+ '|';
                   valores_combos +=  "/";
            }
                else{
                valores_subelementos += document.getElementById('txtresultadosub[' + i + ']').value + "|";
                valores_combos += document.getElementById('totcombo[' + i + ']').value + "/";
               }
            /*valores_subelementos += document.getElementById('txtresultadosub[' + i + ']').value + "/";
            valores_combos += document.getElementById('totcombo[' + i + ']').value + "/";*/
            codigos_subelementos += document.getElementById('oidsubelemento[' + i + ']').value + "/";
            controles += document.getElementById('txtcontrol[' + i + ']').value + "/";
           // posresult+= document.getElementById('oposible_res[' + i + ']').value + "/";
          // alert (valores_combos);
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
            "&iddetalle=" + iddetalle + "&observacion=" + observacion + "&idempleado=" + idempleado + "&procedencia=" + procedencia + "&origen=" + origen + "&valores_subelementos=" +
            encodeURIComponent(valores_subelementos) + "&codigos_subelementos=" + codigos_subelementos + "&valores_elementos=" + encodeURIComponent(valores_elementos) +
            "&codigos_elementos=" + codigos_elementos + "&controles=" + encodeURIComponent(controles) + "&controles_ele=" + encodeURIComponent(controles_ele) + "&tab=" + tab +
            "&fecharealiz="+ fecharealiz + "&fecharesultado="+fecharesultado+"&valores_combos="+encodeURIComponent(valores_combos));
    //+"& posresult="+ posresult
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
                document.getElementById('Imprimir').style.display = 'initial';
                document.getElementById('addexam_modal').style.display = 'initial';
                document.getElementById('divexamen').style.display = "block";
                if  ($("#agregarresults" ).length){
                  document.getElementById('agregarresults').style.visibility = 'hidden';
               }
            }
        }
    }
}

//Fn PG
//FUNCION LLENAR COMBO DE RESPONSABLES
function LlenarComboResponsable(idarea)
{
    //alert(idarea)
    ajax = objetoAjax();
    opcion = 2;
    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idarea=" + idarea);
    ajax.onreadystatechange = function()
    {
       //alert('ajax.readyState: '+ajax.readyState)
        if (ajax.readyState == 4)
        {
           // alert(ajax.status)
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                document.getElementById('divEncargado').innerHTML = ajax.responseText;
                $("#cmbEmpleados").select2({
             	 allowClear: true,
             	 dropdownAutoWidth: true
               });
                //alert(ajax.responseText);
            }
        }
    }
    comboempfin=document.getElementById('cmbEmpleadosfin');
    if(comboempfin!=null)
        LlenarComboResponsable2(idarea);


}
//Fn PG
//FUNCION LLENAR COMBO DE RESPONSABLES
function LlenarComboResponsable2(idarea)
{// alert(idarea);
    ajax2 = objetoAjax();
    opcion = 11;
    ajax2.open("POST", "ctrSolicitudesProcesadas.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax2.send("opcion=" + opcion + "&idarea=" + idarea);
    ajax2.onreadystatechange = function()
    {
       //alert('ajax.readyState: '+ajax.readyState)
        if (ajax2.readyState == 4)
        {
           // alert(ajax.status)
            if (ajax2.status == 200)
            {  //mostrar los nuevos registros en esta capa
                document.getElementById('divEncargado1').innerHTML = ajax2.responseText;
                $("#cmbEmpleadosfin").select2({
             	 allowClear: true,
             	 dropdownAutoWidth: true
               });
                //alert(ajax.responseText);
            }
        }
    }

}
//Fn PG
//FUNCION UTILIZADA PARA ELEGIR METODOLOGIAS
function LlenarComboMetodologia(idexamen, area)
{
    ajax = objetoAjax();
    opcion = 10;
    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            /*if (ajax.status == 200)
            {  */
                //mostrar los nuevos registros en esta capa
               // alert(ajax.responseText)
                if (ajax.responseText==0){
                    document.getElementById("metodo").style.display = "none";
                }
                else{
                    document.getElementById('divMetodologia').innerHTML = ajax.responseText;
                }
                LlenarComboResponsable(area);
                //alert(ajax.responseText);
           // }
        }
    }

}

function CargarExamenes(idsolicitud, idarea, fechanac, sexo, idestandar, idhistorial, fecha_realizacion, fecha_reporte, txtnec, observaciongnral,f_consulta)
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
            "&FechaNac=" + FechaNac + "&Sexo=" + Sexo + "&IdEstandar=" + idestandar + "&idhistorial=" + idhistorial+
            "&fecha_realizacion=" + fecha_realizacion+ "&fecha_reporte=" + fecha_reporte+ "&txtnec=" + txtnec+
            "&observaciongnral="+observaciongnral+"&f_consulta="+f_consulta);
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
function CargarElementosExamen(codigoex, fechanac, sexo, idestandar, idhistorial,fecharealiz,fecharesultado,subservicio,f_tomamuestra,tipomuestra,f_consulta)
{
    ajax = objetoAjax();
    idexamen = codigoex;
    FechaNac = fechanac;
    Sexo = sexo;
    opcion = 1;
//alert (tipomuestra);
    //alert("Entro a cargar otros datos, opcion: "+opcion+"examen "+idexamen);
    ajax.open("POST", "ctrDatosResultadosExamen_PB.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    i
    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&FechaNac=" + FechaNac + "&Sexo=" + Sexo +
              "&idestandar=" + idestandar + "&idhistorial=" + idhistorial+"&fecharealiz="+fecharealiz+
              "&fecharesultado="+fecharesultado+"&f_tomamuestra="+f_tomamuestra+"&tipomuestra="+tipomuestra+"&subservicio="+subservicio+"&f_consulta="+f_consulta);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
               document.getElementById('divexamen').style.display = "block";
               document.getElementById('divexamen').innerHTML = ajax.responseText;
               window.location.href = '#divexamen';
              iniciarselects2();

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

function validadatosplantilla()
{
    var resp = true;

    if (document.frmnuevo.cmbEmpleados.value == "")
    {
        resp = false;
    }

     if (document.frmnuevo.txtresultrealiza.value == "")
    {
        resp = false;
    }
     if (document.frmnuevo.txtresultfin.value == "")
    {
        resp = false;
    }
    return resp;
}

function IngresarResultados() {
    if (validadatosplantilla()) {
        codigoex = document.frmnuevo.txtidexamen.value;
        fechanac = document.frmnuevo.txtFechaNac.value;
        sexo = document.frmnuevo.txtSexo.value;
        idestandar = document.frmnuevo.txtIdEstandar.value;
        idhistorial = document.frmnuevo.txtIdHistorial.value;
        fecharealiz = document.frmnuevo.txtresultrealiza.value;
        fecharesultado=document.frmnuevo.txtresultfin.value;
        subservicio=document.frmnuevo.txtsubservicio.value;
        f_tomamuestra=document.frmnuevo.txtf_tomamuestra.value;
        tipomuestra=document.frmnuevo.txttipomuestra.value;
        f_consulta=document.frmnuevo.f_consulta.value;
       // alert (f_consulta);
        //alert (fecharealiz+'-'+fecharesultado);
        CargarElementosExamen(codigoex, fechanac, sexo, idestandar, idhistorial,fecharealiz,fecharesultado,subservicio,f_tomamuestra,tipomuestra,f_consulta);
    } else {
        alert("Por favor ingrese los campos obligatorios(*)");
    }
}


function IngresarTodosResultados()
{
    resp=true;
    if (document.frmnuevo.fecha_reporte.value == ""){
             resp=false
             }
         if (document.frmnuevo.fecha_realizacion.value == ""){
                resp=false
             }
    if (validaempleado() && resp)
    {
        idsolicitud = document.frmnuevo.txtidsolicitud.value;
        idarea = document.frmnuevo.txtarea.value;
        fechanac = document.frmnuevo.txtFechaNac.value;
        sexo = document.frmnuevo.txtSexo.value;
        idestandar = document.frmnuevo.txtIdEstandar.value;
        idhistorial = document.frmnuevo.txtIdHistorial.value;
        fecha_realizacion = document.frmnuevo.fecha_realizacion.value;
        fecha_reporte = document.frmnuevo.fecha_reporte.value;
        fecha_reporte = document.frmnuevo.fecha_reporte.value;
        txtnec = document.frmnuevo.txtnec.value;
        observaciongnral=document.frmnuevo.observaciongnral.value;
        f_consulta=document.frmnuevo.f_consulta.value;
        CargarExamenes(idsolicitud, idarea, fechanac, sexo, idestandar, idhistorial, fecha_realizacion, fecha_reporte, txtnec, observaciongnral,f_consulta);
    }
    else
    {
        alert("Por favor ingrese los campos obligatorios(*)");
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
    cant_metodologia = document.getElementById('cant_metodologia[' + posicion + ']').value;
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
    idestabext = document.getElementById('idestabext[' + posicion + ']').value;
    f_tomamuestra=document.getElementById('f_tomamuestra[' + posicion + ']').value;
    tipomuestra=document.getElementById('tipomuestra[' + posicion + ']').value;
    idareaPA=document.getElementById('idareaPA[' + posicion + ']').value;
    fecha_recepcion=document.getElementById('fecha_recepcion[' + posicion + ']').value;
    origenmuestra= document.getElementById('origenmuestra[' + posicion + ']').value;
    edad= document.getElementById('edad[' + posicion + ']').value;
    nomsexo= document.getElementById('nomsexo[' + posicion + ']').value;
    datofijo= document.getElementById('dato_fijo[' + posicion + ']').value;
    f_consulta=document.getElementById('f_consulta[' + posicion + ']').value;
    
//alert(idareaPA);
    //alert ("Plnatilla="+plantilla+" Experiente="+idexpediente+" Solicitud="+idsolicitud+" idarea="+idarea+" idhistorial="+IdHistorial+" IdEstandar="+IdEstandar);
    //idhistorial=document.getElementById('idhistorial['+posicion+']').value;
    //alert(plantilla);
    // alert(idexamen);
     //alert(idarea+"**" +plantilla);
     posicion_x=(screen.width/2)-(450/2);
     posicion_y=(screen.height/2)-(350/2);
    switch (plantilla)
    {
        case "1":
            if (idareaPA == 12 && datofijo=='true') {
                ventana_secundaria = window.open("ProcDatosResultadosExamen_PA1.php?var1=" + idexpediente +
                        "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idareaPA + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                        "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                        "&var12=" + impresion + "&var13=" + estab + "&var14=" + FechaNac + "&var15=" + Sexo + "&var16=" + IdEstandar +
                        "&var17=" + IdHistorial + "&referido=" + referido + "&var18="+estabext+"&var19="+idestabext+"&fecha_recepcion="+fecha_recepcion+
                        "&var20="+tipomuestra+"&origenmuestra="+origenmuestra+"&f_consulta="+f_consulta , "Resultados", "width=1200,height=900,menubar=no,scrollbars=yes,location=no");
            }
            else {
              //  alert(cant_metodologia)
                if (cant_metodologia==0){
                    ventana_secundaria = window.open("ProcDatosResultadosExamen_PA.php?var1=" + idexpediente +
                        "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idareaPA + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                        "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                        "&var12=" + impresion + "&var13=" + estab + "&var14=" + FechaNac + "&var15=" + Sexo + "&var16=" + IdEstandar +
                        "&var17=" + IdHistorial + "&referido=" + referido+ "&var18="+estabext+"&var19="+idestabext+"&fecha_recepcion="+fecha_recepcion+
                        "&var20="+tipomuestra+"&origenmuestra="+origenmuestra +"&f_consulta="+f_consulta, "Resultados", "width=1200,height=900,scrollbars=yes,location=no");
                }
                else{
                   ventana_secundaria = window.open("ProcDatosResultadosExamen_PA2.php?var1=" + idexpediente +
                        "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idareaPA + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                        "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                        "&var12=" + impresion + "&var13=" + estab + "&var14=" + FechaNac + "&var15=" + Sexo + "&var16=" + IdEstandar +
                        "&var17=" + IdHistorial + "&referido=" + referido+ "&var18="+estabext+"&var19="+idestabext+"&fecha_recepcion="+fecha_recepcion+
                        "&var20="+tipomuestra+"&origenmuestra="+origenmuestra+"&f_consulta="+f_consulta, "Resultados", "width=1200,height=900,scrollbars=yes,location=no");
                }
            }
            break;
        case "2":
          // alert (f_tomamuestra);
                ventana_dos = window.open("ProcDatosResultadosExamen_PB.php?var1=" + idexpediente +
                    "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idarea + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                    "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                    "&var12=" + impresion + "&var13=" + estab + "&var14=" + FechaNac + "&var15=" + Sexo + "&var16=" + IdEstandar +
                    "&var17=" + IdHistorial + "&referido=" + referido + "&var18="+encodeURIComponent(estabext) + "&var19="+idestabext +
                    "&var20="+ f_tomamuestra + "&var21="+tipomuestra +"&fecha_recepcion="+fecha_recepcion+"&origenmuestra="+origenmuestra+
                    "&edad="+edad+"&nomsexo="+nomsexo+"&f_consulta="+f_consulta, "Resultados", "width=950,height=700,menubar=no,scrollbars=yes,location=no");
            break;
        case "3":
          // alert (origenmuestra);
            //alert (nomsexo);
           // alert(idareaPA);
                ventana_dos = window.open("ProcDatosResultadosExamen_PC.php?var1=" + idexpediente +
                    "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idarea + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                    "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                    "&var12=" + impresion + "&var13=" + estab +"&var14=" + FechaNac + "&var15=" + Sexo + "&var16=" + IdEstandar + "&var17=" + IdHistorial +
                    "&referido=" + referido + "&var18="+estabext +"&var19="+f_tomamuestra+"&var20="+tipomuestra+"&fecha_recepcion="+fecha_recepcion+
                    "&var21="+idestabext+"&origenmuestra="+origenmuestra+"&edad="+edad+"&nomsexo="+nomsexo+"&idareaPA="+idareaPA+
                    "&f_consulta="+f_consulta, "Resultados", "width=950,height=650,menubar=no,scrollbars=yes,location=no");
            break;
        case "4":
           //
           //  alert ("tipo_muestra="+tipomuestra+"& toma-muestra="+f_tomamuestra);
                ventana_dos = window.open("ProcDatosResultadosExamen_PD.php?var1=" + idexpediente +
                    "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idarea + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                    "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                    "&var12=" + impresion + "&var13=" + estab + "&var14=" + FechaNac+ "&var15=" + Sexo +"&var16=" + IdEstandar +
                    "&var17=" + IdHistorial + "&referido=" + referido+ "&var18="+estabext+"&var18="+estabext+"&var19="+f_tomamuestra+
                    "&var20="+ tipomuestra+"&fecha_recepcion="+fecha_recepcion+"&var21="+idestabext+"&origenmuestra="+origenmuestra+
                    "&edad="+edad+"&nomsexo="+nomsexo+"&f_consulta="+f_consulta, "Resultados", "width=950,height=700,menubar=no,scrollbars=yes");
            break;
        case "5":
        // alert (nomsexo);
                ventana_dos = window.open("ProcDatosResultadosExamen_PE.php?var1=" + idexpediente +
                    "&var2=" + examen + "&var3=" + idexamen + "&var4=" + idarea + "&var5=" + detallesolicitud + "&var6=" + idsolicitud +
                    "&var7=" + paciente + "&var8=" + idrecepcion + "&var9=" + nombrearea + "&var10=" + procedencia + "&var11=" + origen +
                    "&var12=" + impresion + "&var13=" + estab + "&var14=" + FechaNac + "&var15=" + Sexo + "&var16=" + IdEstandar +
                    "&var17=" + IdHistorial + "&referido=" + referido + "&var18="+estabext+"&var19="+ f_tomamuestra+
                    "&var20="+tipomuestra+"&fecha_recepcion="+fecha_recepcion+"&var21="+idestabext+"&origenmuestra="+origenmuestra+
                    "&edad="+edad+"&nomsexo="+nomsexo+"&f_consulta="+f_consulta, "Resultados", "width=950,height=950,menubar=no,scrollbars=yes");
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
//alert(idarea);
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
                $(document).ready(function() {
                   // $('#dataresultados').DataTable();
                    setDataTables();
                } );
               
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
        dias = document.getElementById('dias').value;
        txtnec = document.getElementById('txtnec').value;
        fecha_realizacion = document.getElementById('fecha_realizacion').value;
        fecha_reporte = document.getElementById('fecha_reporte').value;
        f_consulta=document.getElementById('f_consulta').value;
        //DATOS PARA EL DETALLE DE LOS RESULTADOS
    // alert (f_consulta);
        valores_resultados = "";
        codigos_resultados = "";
        valores_lecturas = "";
        valores_inter = "";
        valores_obser = "";
        codigos_examenes = "";
        examen_metodologia = "";

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
                examen_metodologia += document.getElementById('oidexametodologia[' + i + ']').value + "/";
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
                "&examen_metodologia=" + escape(examen_metodologia) +
                "&estab=" + estab + "&fechanac=" + fechanac + "&sexo=" + sexo+
                "&dias=" + dias+ "&txtnec=" + txtnec+ "&fecha_realizacion=" + fecha_realizacion+
                "&fecha_reporte=" + fecha_reporte+"&f_consulta="+f_consulta);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa
                    document.getElementById('divresultado').style.display = "block";
                    document.getElementById('divresultado').innerHTML = ajax.responseText;
                    //calc_edad();
                    window.location.href = '#divresultado';
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
   // alert(idsolicitud);
    //idrecepcion
    idrecepcion = document.getElementById('txtidrecepcion').value;
   // alert(idrecepcion);
    //detallesolicitud
    iddetalle = document.getElementById('txtiddetalle').value;
   // alert(idrecepcion);
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
   //alert(idestandar);
    fecharealiz = document.getElementById('txtresultrealiza').value;
    //alert(fecharealiz);
    fecharesultado = document.getElementById('txtresultfin').value;
     //alert(idestandar+"FECHA REALIZA"+fecharealiz+" FECHA RESULTADO "+fecharesultado);
    //alert("FECHA REALIZA"+fecharealiz+" FECHA RESULTADO "+fecharesultado);
    //alert (tab);
    idresultado = 0;
    //alert ("tabulador"+tab+"IdSoli"+idsolicitud);
    if (validarplantillaD())
    {
        ajax.open("POST", "ctrDatosResultadosPlantillaD.php", true);

        //muy importante este encabezado ya que hacemos uso de un formulario
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores
        ajax.send("opcion=" + opcion + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion +
                "&iddetalle=" + iddetalle + "&idexamen=" + idexamen + "&idempleado=" + idempleado +
                "&idelemento=" + idelemento + "&idcantidad=" + idcantidad + "&idresultado=" + idresultado +
                "&tab=" + tab + "&fecharealiz=" + fecharealiz + "&fecharesultado="+fecharesultado);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa
                    document.getElementById('divresultado').innerHTML = ajax.responseText;
                    document.getElementById('divBotonPrevie').style.display = "block";
                    document.getElementById('divBotonGuardar').style.display = "none";
                    document.getElementById('addexam_modal').style.display = 'initial';
                    if  ($("#agregarresults" ).length){
                        document.getElementById('agregarresults').style.visibility = 'hidden';
                    }

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
    f_tomamuestra=document.getElementById('txtf_tomamuestra').value;
    tipomuestra=document.getElementById('txttipomuestra').value;
   // fecharealiz = document.getElementById('txtresultrealiza').value;
    //alert(fecharealiz);
    //fecharesultado = document.getElementById('txtresultfin').value;
    //alert (tab+"opc=2");
    if (validarplantillaD())
    {
        ajax.open("POST", "ctrDatosResultadosPlantillaD.php", true);

        //muy importante este encabezado ya que hacemos uso de un formulario
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores

         ajax.send("opcion=" + opcion + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion
                + "&iddetalle=" + iddetalle + "&idexamen=" + idexamen + "&idempleado=" + idempleado
                + "&idelemento=" + idelemento + "&idcantidad=" + idcantidad + "&idresultado=" + idresultado
                + "&tab" + tab + "&f_tomamuestra=" + f_tomamuestra+"&tipomuestra=" + tipomuestra);
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
                        document.getElementById('addexam_modal').style.display = 'initial';
                    if  ($("#agregarresults" ).length){
                        document.getElementById('agregarresults').style.visibility = 'hidden';
                    }


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
    f_tomamuestra=document.getElementById('txtf_tomamuestra').value;
    tipomuestra=document.getElementById('txttipomuestra').value;

    //alert(tab);
    ajax.open("POST", "ctrDatosResultadosPlantillaD.php", true);

    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion +
              "&idexamen=" + idexamen + "&idempleado=" + idempleado + "&idelemento=" + idelemento + "&idcantidad=" + idcantidad + "&idresultado=" + idresultado +
              "&estab=" + estab + "&tab=" + tab + "&f_tomamuestra=" + f_tomamuestra+"&tipomuestra=" + tipomuestra);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa

                document.getElementById('divpreview').innerHTML = ajax.responseText;
                document.getElementById('divBotonPrevie').style.display = "block";
                document.getElementById('divBotonGuardar').style.display = "none";
                //document.getElementById('Imprimir').style.display = 'initial';
                //document.getElementById('addexam_modal').style.display = 'initial';
                //if  ($("#agregarresults" ).length){
                 // document.getElementById('agregarresults').style.visibility = 'hidden';
               // }
                //calc_edad();
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
    if (validadatosplantilla())
    {
        codigoex = document.frmnuevo.txtidexamen.value;
        fechanac = document.frmnuevo.txtFechaNac.value;
        sexo = document.frmnuevo.txtSexo.value;
        fecharealiz = document.frmnuevo.txtresultrealiza.value;
        fecharesultado=document.frmnuevo.txtresultfin.value;
        f_tomamuestra=document.frmnuevo.txtf_tomamuestra;
        tipomuestra=document.frmnuevo.txttipomuestra;
       //alert(fecharealiz+" - "+fecharesultado);
      // alert(codigoex);
        CargarProcesosExamen(codigoex, fechanac, sexo,fecharealiz,fecharesultado,f_tomamuestra,tipomuestra);
    }
    else
    {
        alert("Ingrese los campos marcados con (*)");
    }
}

function CargarProcesosExamen(codigoex, fechanac, sexo,fecharealiz,fecharesultado,f_tomamuestra,tipomuestra)
{
    ajax = objetoAjax();
    idexamen = codigoex;

    opcion = 1;
    //alert(fecharealiz+" - "+fecharesultado);
    //observacion = document.getElementById('txtobservacion').value;
    ajax.open("POST", "ctrDatosResultadosPlantillaE.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
// "&observacion=" + observacion +
    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen +
             "&fechanac=" + fechanac + "&sexo=" + sexo + "&fecharealiz=" + fecharealiz +
             "&fecharesultado="+fecharesultado+"&f_tomamuestra="+f_tomamuestra+"&tipomuestra="+tipomuestra);
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

    for (i = 0; i < document.getElementById('oculto').value; i++){
       (document.getElementById('txtresultado[' + i + ']').value )

        if ((document.getElementById('txtresultado[' + i + ']').value == "")||(document.getElementById('txtresultado[' + i + ']').value == 0))
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

function MostrarVistaPreviaPlantillaE()
{
   if (ValidarCamposPlantillaE()) {
       // ajax = objetoAjax();
       opcion = 2;
       idexamen = document.getElementById('txtidexamen').value;
       idsolicitud = document.getElementById('txtidsolicitud').value;
       estab = document.getElementById('txtestablecimiento').value;
       observacion = document.getElementById('txtobservacion').value;
       idempleado = document.getElementById('cmbEmpleados').value;
       tab = document.getElementById('cmbTabulador').value;
       fechanac = document.getElementById('txtFechaNac').value;
       sexo = document.getElementById('txtSexo').value;
       fecharealiz = document.getElementById('txtresultrealiza').value;
       fecharesultado=document.getElementById('txtfresultado').value;
       f_tomamuestra=document.getElementById('txtf_tomamuestra').value;
       tipomuestra=document.getElementById('txttipomuestra').value;
       f_consulta=document.getElementById('f_consulta').value;
        //DATOS PARA EL DETALLE DE LOS RESULTADOS
        valores = "";
        codigos = "";
        comentarios = "";
        valores_combos = "";
        if (document.getElementById('oculto').value > 0)
        {
            for (i = 0; i < document.getElementById('oculto').value; i++)
            {
                valores += document.getElementById('txtresultado[' + i + ']').value + "/";
                codigos += document.getElementById('oidprueba[' + i + ']').value + "/";
                valores_combos += document.getElementById('totcombo[' + i + ']').value + "/";
              //  alert (codigos);

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
        ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idsolicitud=" + idsolicitud + "&observacion=" + observacion +
                "&idempleado=" + idempleado + "&valores=" + encodeURIComponent(valores) + "&codigos=" + codigos + "&comentarios=" + encodeURIComponent(comentarios) +
                "&estab=" + estab + "&tab=" + tab + "&fechanac=" + fechanac + "&sexo=" + sexo + "&fecharealiz=" + fecharealiz + "&fecharesultado="+fecharesultado +
                "&valores_combos="+ valores_combos+"&f_tomamuestra="+f_tomamuestra+"&tipomuestra="+tipomuestra+"&f_consulta="+f_consulta);
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                if (ajax.status == 200)
                {  //mostrar los nuevos registros en esta capa
                    document.getElementById('divresultado').style.display = "block";
                    document.getElementById('divresultado').innerHTML = ajax.responseText;
                    //calc_edad();
                }
            }
        }
    }
   else
    {
       alert("Complete los datos a Ingresar");
    }
}

function GuardarPlantillaE()
{    ajax = objetoAjax();
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
    fecharealiz = document.getElementById('txtresultrealiza').value;
    fecharesultado=document.getElementById('txtfresultado').value;
    //DATOS PARA EL DETALLE DE LOS RESULTADOS
    valores = "";
    codigos = "";
    valores_combos = "";
    if (document.getElementById('oculto').value > 0)
    {
        for (i = 0; i < document.getElementById('oculto').value; i++)
        {
            valores += document.getElementById('txtresultado[' + i + ']').value + "/";
            codigos += document.getElementById('oidprueba[' + i + ']').value + "/";
            comentarios += document.getElementById('txtcomentario[' + i + ']').value + "/";
            valores_combos += document.getElementById('totcombo[' + i + ']').value + "/";
           // alert(codigos);
        }
    }

    ajax.open("POST", "ctrDatosResultadosPlantillaE.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    //alert(tab);
    ajax.send("opcion=" + opcion + "&idexamen=" + idexamen + "&idsolicitud=" + idsolicitud + "&idrecepcion=" + idrecepcion +
            "&iddetalle=" + iddetalle + "&observacion=" + observacion + "&idempleado=" + idempleado +
            "&valores=" + encodeURIComponent(valores) + "&codigos=" + codigos + "&comentarios=" + comentarios + "&tab=" + tab +
            "&fecharealiz=" + fecharealiz + "&fecharesultado=" + fecharesultado + "&valores_combos="+valores_combos);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            if (ajax.status == 200)
            {  //mostrar los nuevos registros en esta capa
                alert(ajax.responseText);
                document.getElementById('btnGuardar').style.visibility = 'hidden';
                document.getElementById('Imprimir').style.display = 'initial';
                document.getElementById('addexam_modal').style.display = 'initial';
                if  ($("#agregarresults" ).length){
                  document.getElementById('agregarresults').style.visibility = 'hidden';
                }

            }
        }
    }
}
//Fn Pg
//Funcion revisa si fecha seleccionada es mayor que la actual
//function valfechasolicita(obj){
// //fecha0=document.getElementById('d_fechatoma').value;
//fecha1=obj.value;
//var fecha_actual = new Date() ;
//	var dia = fecha_actual.getDate()
//	var mes = fecha_actual.getMonth() + 1
//	var anio = fecha_actual.getFullYear()
//	var hora = fecha_actual.getHours()
//        var minu = fecha_actual.getMinutes()
//
//	if (mes<10)
//		mes='0'+mes
//	if (dia<10)
//		dia='0'+dia
//        if (hora<10)
//            hora = '0'+hora
//        if (minu<10)
//            minu = '0'+minu
//
//
//	fechact=parseInt(anio+""+mes+""+dia+""+hora+""+minu);
//
////var f0 = fecha0.split('-');
////var fechaPri = parseInt(f0[0]+f0[1]+f0[2]);
//hola=fecha1.split(/[- :]/);
//var fecha2 = parseInt(f2[0]+f2[1]+f2[2]);
//var f2 = fecha1.split('-');
//var fecha2 = parseInt(f2[0]+f2[1]+f2[2]);
//
//
//    if (fecha1!="" && fecha2>fechact)
//    {
//    alert ('La fecha de Solicitud de examen es mayor que la fecha actual')
//    document.getElementById('d_fechasolicitud').value=""
//    return false;
//    }
//}





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
//                    alert ('paso lo otro: '+val.tiposolicitud)
//                    jQuery('#cmbTipoSolic').append($("<option></option>").attr("value",val.idtiposolicitud).text(val.tiposolicitud));
                         $('#cmbTipoSolic').append($('<option>', {
                            value: val.idtiposolicitud,
                            text : val.tiposolicitud
                        }));
                });
            }
        }
    });
}


//Fn Pg
// Parametros para seleccionar establecimiento x tipo 1

function setCodResultado(idresultado)
{
    ajax = objetoAjax();
    opcion = 14;
    idexamen=document.getElementById('txtidexamen').value;
    idEstandar=document.getElementById('txtIdEstandar').value;
    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idresultado=" + idresultado+ "&idexamen="+idexamen+ "&idEstandar="+idEstandar);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4) {//4 The request is complete
            if (ajax.status == 200) {//200 means no error.
                respuesta = ajax.responseText;
              //  alert (respuesta)
                document.getElementById('divCodResultado').innerHTML = respuesta;
            }
        }
    }
}

//Fn Pg
// Parametros para seleccionar diferentes resultados de acuerdo a la metodología
function buscarPosResMet(idexametodologia)
{
    ajax = objetoAjax();
    opcion = 15;
  //  idexamen=document.getElementById('txtidexamen').value;
    ajax.open("POST", "ctrSolicitudesProcesadas.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion=" + opcion + "&idexametodologia=" + idexametodologia);
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4) {//4 The request is complete
            if (ajax.status == 200) {//200 means no error.
                respuesta = ajax.responseText;
              //  alert (respuesta)
                document.getElementById('divResult').innerHTML = respuesta;
                idres=$('#idresultado').val();
                if (idres=='x'){
                  setCodResultado('x')
                }
                if ($('#idresultado').prop('tagName')=='SELECT') {
                    $("#idresultado").select2({
                     allowClear: true,
                     dropdownAutoWidth: true
                   });
               }
//                 if (idexametodologia==0){
//                  setCodResultado('x')
//                }

            }
        }
    }
}

//fn pg
function crearmodal() {
  var idsolicitud=jQuery("#solicitud_").val();
  var content='';
  content+= crearmodaladdexam(idsolicitud);
  content+='<div id="agregarexamen">';
  content+= detallemodaladdexam(idsolicitud)
  content+='</div>';

   return content;
}

function crearmodaladdexam(idsolicitud){
   var content='';
   var idexpediente=$("#idexpediente_").val();
   var fecharecepcion=$("#fecharecepcion").val();
   var idestablecimiento=$("#idestabext_").val();
   var banderacerrar=1;
  jQuery.ajax({
    url: "../AgregarExamen/SolicitudEstudiosPaciente.php",
    type: "GET",
    async: false,
    data: {var1:idexpediente, var2:idsolicitud, var3:idestablecimiento, var4:fecharecepcion, var5:banderacerrar},
    dataType: "html",
    success: function(html) {
     content+=html;
    }


  });
  return content;
}
function detallemodaladdexam(idsolicitud){
   var content= '';
   jQuery.ajax({
      url: "ctrSolicitudesProcesadas.php",
      method: "POST",
      async: false,
      data: {solicitud: idsolicitud, opcion: 16 },
      dataType: "json",
      success: function (object) {
         if (object.status) {
            content +=
                     '<table class="table table-bordered table-condensed table-hover table-white">'+
                     '<thead>'+
                        '<tr><th colspan="2">'+
                        '<center>Listado de examenes solicitados en la orden de laboratorio'+
                        '</center></th></tr>'+
                        '<tr><th>Exámen</th>'+
                        '<th>Estado</th>'+
                        '</tr>'+
                     '</thead>'+
                     '<tbody>';
            jQuery.each(object.data,function(index, value){
               content+='<tr><td> '+value.nombre_examen+'</td>'+
                        '<td> '+value.descripcion+'</td></tr>';
            });

            content+='</tbody></table>';
         }
      }
   });

   return content;
}

function reloaddetallemodal(idsolicitud){
   var content='';

  jQuery('#agregarexamen').empty();
  content=detallemodaladdexam(idsolicitud);
  jQuery('#agregarexamen').append(content);
}

function default_subelementos(idelemento){
    idexamen=$('#txtidexamen').val();
//    console.log(idexamen+' - '+idelemento);
    jQuery.ajax({
       url: "ctrSolicitudesProcesadas.php",
       method: "POST",
       async: false,
       data: {idelemento: idelemento, opcion: 17, idexamen: idexamen },
       dataType: "json",
       success: function (object) {
           //console.log(object.status)
          if (object.status) {
              $('input[id^="oidsubelemento"]').each(function(i) {
                 var idsubfuera=this.value;
                // var name=this.prop('id');
                 var $input = $( this );
                  var id_val= $input.attr( "id" );
                  var str= id_val.replace("]", "[");

                ready = str.split('[');


            //      console.log(this.value+' / '+$input+' /position'+ready[1]);
                  jQuery.each(object.data,function(index, value){
                      if (value.idsubelemento==idsubfuera){
                          $('[name="txtresultadosub['+ready[1]+']"]').select2('val',value.id_posible_resultado);
                        //  $('#txtresultadosub['+ready[1]+']').val(value.id_posible_resultado);
                        //  console.log('estos son los que tengo q setear'+value.idsubelemento+' - '+this.value);
                      }
                //     console.log('idsubelemento'+value.idsubelemento+ ' oid: '+this.value);
                  });
              });
        /*     content +='<table class="table table-bordered table-condensed table-hover table-white">'+
                      '<thead>'+
                         '<tr><th colspan="2">'+
                         '<center>Listado de examenes solicitados en la orden de laboratorio'+
                         '</center></th></tr>'+
                         '<tr><th>Exámen</th>'+
                         '<th>Estado</th>'+
                         '</tr>'+
                      '</thead>'+
                      '<tbody>';*/
             /*jQuery.each(object.data,function(index, value){
                content+='<tr><td> '+value.nombre_examen+'</td>'+
                         '<td> '+value.descripcion+'</td></tr>';
             });*/

            // content+='</tbody></table>';
          }
       }
   });

}
/*
//Fn para modal del reporte vih

function reportfvihmodal() {
  var idsolicitud=jQuery("#solicitud_").val();
  var content='';
  content+= crearmodalapdfvih(idsolicitud);
  content+='<div id="agregarexamen">';
  content+= detallemodaladdexam(idsolicitud)
  content+='</div>';

   return content;
}


function crearmodalapdfvih(idsolicitud){
   var content='';
   var idexpediente=$("#idexpediente_").val();
   var fecharecepcion=$("#fecharecepcion").val();
   var idestablecimiento=$("#idestabext_").val();
   var banderacerrar=1;
  jQuery.ajax({
    url: "pdfOrd_x_id.php",
    type: "GET",
    async: false,
    data: {var1:idexpediente, var2:idsolicitud, var3:idestablecimiento, var4:fecharecepcion, var5:banderacerrar},
    dataType: "html",
    success: function(html) {
     content+=html;
    }


  });
  return content;
}
*/
function inicia_elementos(){
      classdatepick();
}

function refreshParent() {
        //window.opener.location.reload();
        opener.MostrarSolicitudes();
    }
function focusselect2(nombre){
  $("select[id^=txtresultadosub]").select2({
    shouldFocusInput: function() {
        return false
    },
    formatResult: function(result){
        return result.text;
    },
    formatSelection: function (object, container){
        var text = object.text;

        return text;
    }
  });
}

function noseobserva(thisid){
  document.getElementById('txtresultadosub['+thisid+']').value = 'No Se Observa';
}
function blastocystis(thisid){
  document.getElementById('txtresultadosub['+thisid+']').value = 'Blastocystis hominis';
}
//funcion para obtener el texto del select multiple
function GetSelectValues(select) {
  var result = [];
  var options = select && select.options;
  var opt;

  for (var i=0, iLen=options.length; i<iLen; i++) {
    opt = options[i];

    if (opt.selected) {
      result.push(opt.text);
    }
  }
  return result.toString();
}
