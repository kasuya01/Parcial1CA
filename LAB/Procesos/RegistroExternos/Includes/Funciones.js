var nav4 = window.Event ? true : false;
function acceptNum(evt){	
	var key = nav4 ? evt.which : evt.keyCode;	
//	alert(key);
	return ((key < 13) || (key >= 48 && key <= 57) || key==45);
}

function xmlhttp(){
		var xmlhttp;
		try{xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");}
		catch(e){
			try{xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}
			catch(e){
				try{xmlhttp = new XMLHttpRequest();}
				catch(e){
					xmlhttp = false;
				}
			}
		}
		if (!xmlhttp) 
				return null;
			else
				return xmlhttp;
}//xmlhttp

function Limpiar(){
	window.location.replace('Busqueda.php');
}
function LimpiarConExpe(nec){
	window.location.replace('Busqueda.php?nec='+nec);
}

function NoCero(ct){
//ct = id del textbox
var q=0;
var c=document.getElementById(ct).value.charAt(q);

while(c=='0') { ++q; c=document.getElementById(ct).value.charAt(q); }
document.getElementById(ct).value=document.getElementById(ct).value.substr(q);

} 
function MostrarInformacion(){
	   var A = document.getElementById('Datos');
	   var Expediente = document.getElementById('Expediente').value;
	   var IdEstablecimiento = document.getElementById('Establecimiento').value;
		document.getElementById('GetInfo').disabled=true;
		document.getElementById('Expediente').disabled=true;
		var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "<div align='center'>CARGANDO . . .</div>";
					}
				if(ajax.readyState==4){
					    A.innerHTML = ajax.responseText;
												
					}
			}

ajax.open("GET","Includes/ProcesoCIQ.php?IdNumeroExp="+Expediente+"&Opcion=1"+"&Establecimiento="+IdEstablecimiento,true);
		ajax.send(null);
		return false;
		
}//Imprimir historial clinico


function Cita(IdCita){
	var IdNumeroExp=document.getElementById('Expediente').value;
    	//var ajax = xmlhttp();
	var bandera=1;

    miVentana= window.open("AgendaCIQ.php?IdCita="+IdCita+"&IdNumeroExp="+IdNumeroExp+"&BanderaExt="+1,"miwin","width=900,height=900,scrollbars=yes");		
}//MostrarAgendaProcedimientos

function Activar(Bandera){
	var A = document.getElementById('Interno');
	var B = document.getElementById('Seleccion');
	var C = document.getElementById('Externo');
	var D = document.getElementById('Busqueda');

	if(Bandera==1){//Interna
		A.style.display = "inline";
		B.style.visibility = "hidden";
	}else{//Externa
		D.style.display = "inline";
		C.style.display = "hidden";
		B.style.visibility = "hidden";
		document.getElementById('NombreEstablecimiento').focus();
	}
}

function GuardarInformacionExterna(){
	var A = document.getElementById('Datos');

	var IdEstablecimientoExterno = document.getElementById('EstablecimientoExterno').value;
	//var IdEstablecimiento = document.getElementById('Establecimiento').value;
	var PrimerApellido = document.getElementById('PrimerApellido_Name').value;
	var SegundoApellido = document.getElementById('SegundoApellido_Name').value;
	var CasadaApellido = document.getElementById('CasadaApellido_Name').value;
	var PrimerNombre = document.getElementById('PrimerNombre_Name').value;
	var SegundoNombre = document.getElementById('SegundoNombre_Name').value;
	var TercerNombre = document.getElementById('TercerNombre_Name').value;
	var FechaNacimiento = document.getElementById('FechaNacimiento_Name').value;
	var Sexo_Name = document.getElementById('Sexo_Name').value;
	var NombreMadre = document.getElementById('NombreMadre_Name').value;
	var NombrePadre = document.getElementById('NombrePadre_Name').value;
	var NombreResponsable = document.getElementById('NombreResponsable_Name').value;
	var LugardeAtencion = document.getElementById('LugarAtencion').value;
	var IdNumeroExpRef = document.getElementById('NumeroExpediente_Referencia').value;
        var IdNumeroExp = document.getElementById('nec').value;
        var idnumeroexpediente = document.getElementById('idnumeroexpediente').value;
        var idpacienteref = document.getElementById('idpacienteref').value;
       // alert(FechaNacimiento)
      //  alert (idpacienteref)
        if(idpacienteref==''){
            idpacienteref=0;
        }
        //alert ('Sexo_Name '+Sexo_Name+' IdEstablecimiento '+IdEstablecimientoExterno+' LugardeAtencion '+LugardeAtencion+' IdNumeroExpRef: '+IdNumeroExpRef+' IdNumeroExp '+IdNumeroExp);
	if(IdNumeroExp==""){ // si el campo esta vacio es un paciente nuevo que se va a registrar
                IdNumeroExp = 0; // se le coloca 0 para saber que es nuevo
                idnumeroexpediente = 0; // se le coloca 0 para saber que es nuevo
		
	}        
        
	if(document.getElementById('PrimerApellido_Name').value==""){
		alert(".: Error: No puede dejar el PRIMER APELLIDO en blanco");
		document.getElementById('PrimerApellido_Name').focus();
		return false;
	}
		
	if(document.getElementById('PrimerNombre_Name').value==""){
		alert(".: Error: No puede dejar el PRIMER NOMBRE en blanco");
		document.getElementById('PrimerNombre_Name').focus();
		return false;
	}
	
	if(document.getElementById('Sexo_Name').value==0){
		alert(".: Error: No puede dejar el SEXO del paciente en blanco");
		document.getElementById('Sexo_Name').focus();
		return false;
	}
	
	if(document.getElementById('NombreMadre_Name').value==""){
		alert(".: Error: No puede dejar el NOMBRE DE LA MADRE en blanco");
		document.getElementById('NombreMadre_Name').focus();
		return false;
	}
       
	
	
	document.getElementById('GetInfor').disabled=true;
	
	var ajax = xmlhttp();
		
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			A.innerHTML = "<div align='center'>GUARDANDO . . .</div>";
		}
               // alert(ajax.readyState)
		if(ajax.readyState==4){
		    	var respuesta = ajax.responseText.split('~');
                        var IdExpediente = respuesta[0];
                        var IdCitaServApoyo = respuesta[1];
                        var IdNumeroExpRef = respuesta[2];
                        //A.innerHTML = ajax.responseText

                        //alert(ajax.responseText);
                       // alert(IdExpediente+' - '+IdCitaServApoyo+' - '+IdNumeroExpRef+' '+IdEstablecimientoExterno)
                       // return false;
                        window.opener.pegarExp(IdExpediente,IdCitaServApoyo,IdEstablecimientoExterno, IdNumeroExpRef);
                        window.close();                     
		}
	}
//alert("IdEstablecimientoExterno="+IdEstablecimientoExterno+"&LugardeAtencion="+LugardeAtencion+"&PrimerApellido="+PrimerApellido+"&SegundoApellido="+SegundoApellido+"&CasadaApellido="+CasadaApellido+"&PrimerNombre="+PrimerNombre+"&SegundoNombre="+SegundoNombre+"&TercerNombre="+TercerNombre+"&FechaNacimiento="+FechaNacimiento+"&Sexo_Name="+Sexo_Name+"&NombreMadre="+NombreMadre+"&NombrePadre="+NombrePadre+"&NombreResponsable="+NombreResponsable+"&IdNumeroExpRef="+IdNumeroExpRef+"&IdNumeroExp="+idnumeroexpediente+"&idpacienteref="+idpacienteref)

ajax.open("GET","respuesta.php?Bandera=4&IdEstablecimientoExterno="+IdEstablecimientoExterno+"&LugardeAtencion="+LugardeAtencion+"&PrimerApellido="+PrimerApellido+"&SegundoApellido="+SegundoApellido+"&CasadaApellido="+CasadaApellido+"&PrimerNombre="+PrimerNombre+"&SegundoNombre="+SegundoNombre+"&TercerNombre="+TercerNombre+"&FechaNacimiento="+FechaNacimiento+"&Sexo_Name="+Sexo_Name+"&NombreMadre="+NombreMadre+"&NombrePadre="+NombrePadre+"&NombreResponsable="+NombreResponsable+"&IdNumeroExpRef="+IdNumeroExpRef+"&IdNumeroExp="+idnumeroexpediente+"&idpacienteref="+idpacienteref,true);
		ajax.send(null);
		return false;
		
}//GuardarInformacionExterna

function Enviodatos(){
	var A = document.getElementById('Datos');
	var Expediente = $('nec').value;
	var IdEstablecimientoExterno = document.getElementById('EstablecimientoExterno').value;
	var LugardeAtencion = document.getElementById('LugarAtencion').value;
        var IdNumeroExpRef = 0;
        //alert("NEC: "+Expediente+"  EstablecimientoExterno: "+IdEstablecimientoExterno+"  LugardeAtencion: "+LugardeAtencion);
	if(document.getElementById('EstablecimientoExterno').value==""){
		alert(".: Error:Debe Elegir un Establecimiento");
		document.getElementById('EstablecimientoExterno').focus();
	}else{
		miVentana= window.open("AgendaCIQ.php?IdEstablecimientoExterno="+IdEstablecimientoExterno+"&IdNumeroExp="+Expediente+"&LugardeAtencion="+LugardeAtencion+"&BanderaExt="+2+"&IdNumeroExpRef="+IdNumeroExpRef,"miwin","width=900,height=900,scrollbars=yes");	
	}
}//Envio de Datos si el paciente ya ah sido ingresado en la DB pero que ahun tiene un correlativo....

function VerificarExistente(){
	
	var C = document.getElementById('Externo');
	var D = document.getElementById('Busqueda');
//var PrimerNombre_Name = document.getElementById('PrimerNombre_Name');
        var NEC = document.getElementById('nec').value;
        var idnumeroexpediente = document.getElementById('idnumeroexpediente').value;
        var PrimerNombre = document.getElementById('PrimerNombre').value;
        var SegundoNombre = document.getElementById('SegundoNombre').value;
        var TercerNombre = document.getElementById('TercerNombre').value;
        var PrimerApellido = document.getElementById('PrimerApellido').value;
        var SegundoApellido = document.getElementById('SegundoApellido').value;
        var CasadaApellido = document.getElementById('CasadaApellido').value;
        var FechaNacimiento = document.getElementById('FechaNacimiento').value;
        var Sexo = document.getElementById('Sexo').value;
        var id_sexo = document.getElementById('id_sexo').value;
        var NombreMadre = document.getElementById('NombreMadre').value;
        var NombrePadre = document.getElementById('NombrePadre').value;
        var NombreResponsable = document.getElementById('NombreResponsable').value;
        var Edad = document.getElementById('edad').value;
       // alert(FechaNacimiento)
        //var IdEstablecimientoExterno = document.getElementById('IdEstablecimientoExterno').value;
	//alert("nombre: "+PrimerNombre+ "  NEC: "+ NEC );
	if(document.getElementById('EstablecimientoExterno').value==""){
		alert(".: Error:Debe Elegir un Establecimiento");
		document.getElementById('EstablecimientoExterno').focus();
		return false;
	}        
        
	D.style.display = "none";
	C.style.display = "inline";
        document.getElementById('PrimerNombre_Name').value = PrimerNombre;
        document.getElementById('SegundoNombre_Name').value = SegundoNombre;
        document.getElementById('TercerNombre_Name').value = TercerNombre;
        document.getElementById('PrimerApellido_Name').value = PrimerApellido;
        document.getElementById('SegundoApellido_Name').value = SegundoApellido;
        document.getElementById('CasadaApellido_Name').value = CasadaApellido;
        document.getElementById('FechaNacimiento_Name').value = FechaNacimiento;
        document.getElementById('Sexo_Name').value = id_sexo;
        document.getElementById('NombreMadre_Name').value = NombreMadre;
        document.getElementById('NombrePadre_Name').value = NombrePadre;
        document.getElementById('NombreResponsable_Name').value = NombreResponsable;
	document.getElementById('NumeroExpediente_Referencia').value=NEC;
	document.getElementById('NumeroExpediente_Referencia').value=NEC;
	document.getElementById('Edad').value=Edad;
	document.getElementById('Edadini').value=Edad;
	document.getElementById('PrimerApellido_Name').focus();		
}

function esEdadValida(edad){
    edadfin=edad.value
    edadini=document.getElementById('Edadini').value;
    if (edadfin!=edadini) {
        if (edad != undefined && edad.value != "" ){

       var annios  =  parseInt(edad.value);
          if (annios > 120)
              {
             alert("Verifique la edad del paciente");
             document.getElementById('Edad').value=""
             return false;
          }
            var fecha=new Date();
            var diames=fecha.getDate();
            if (diames<10){diames="0"+diames}
            var mes=fecha.getMonth() +1 ;
            if (mes<10){mes="0"+mes}
            var ano=fecha.getFullYear();
            var annio=ano-annios;
            FechaCalculada=annio+"-"+mes+"-"+diames;
            document.getElementById("FechaNacimiento_Name").value=FechaCalculada;
            document.getElementById('Edadini').value=edadfin

        return true;
          }
      }
      if (edadfin==''){
          document.getElementById('Edad').value=edadini
      }
      
}

function limpiarcampoedad(campo){
    document.getElementById(campo).value=''
    /*$(campo).attr('placeholder','');*/
        
}