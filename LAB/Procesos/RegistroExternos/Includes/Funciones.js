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
	var PrimerNombre = document.getElementById('PrimerNombre_Name').value;
	var FechaNacimiento = document.getElementById('FechaNacimiento_Name').value;
	var Sexo_Name = document.getElementById('Sexo_Name').value;
	var NombreMadre = document.getElementById('NombreMadre_Name').value;
	var LugardeAtencion = document.getElementById('LugarAtencion').value;
	var IdNumeroExpRef = document.getElementById('NumeroExpediente_Referencia').value;
        var IdNumeroExp = document.getElementById('NEC').value;
        
        //alert ('Sexo_Name '+Sexo_Name+' IdEstablecimiento '+IdEstablecimientoExterno+' LugardeAtencion '+LugardeAtencion+' IdNumeroExpRef: '+IdNumeroExpRef+' IdNumeroExp '+IdNumeroExp);
	if(IdNumeroExp==""){ // si el campo esta vacio es un paciente nuevo que se va a registrar
                IdNumeroExp = 0; // se le coloca 0 para saber que es nuevo
		
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
	
      /*  if(document.getElementById('IdNumeroExpRef').value==0){
		alert(".: Error: No puede dejar Numero de Expediente de Referencia en blanco");
		document.getElementById('IdNumeroExpRef').focus();
		return false;
	}*/
        
	
	
	document.getElementById('GetInfor').disabled=true;
	
	var ajax = xmlhttp();
		
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1){
			A.innerHTML = "<div align='center'>GUARDANDO . . .</div>";
		}
		if(ajax.readyState==4){
		    	var respuesta = ajax.responseText.split('~');
                        var IdExpediente = respuesta[0];
                        var IdCitaServApoyo = respuesta[1];
                        //A.innerHTML = ajax.responseText

                        //alert(ajax.responseText);
                        window.opener.pegarExp(IdExpediente,IdCitaServApoyo,IdEstablecimientoExterno);
                        window.close();                     
		}
	}

ajax.open("GET","respuesta.php?Bandera=4&IdEstablecimientoExterno="+IdEstablecimientoExterno+"&LugardeAtencion="+LugardeAtencion+"&PrimerApellido="+PrimerApellido+"&PrimerNombre="+PrimerNombre+"&FechaNacimiento="+FechaNacimiento+"&Sexo_Name="+Sexo_Name+"&NombreMadre="+NombreMadre+"&IdNumeroExpRef="+IdNumeroExpRef+"&IdNumeroExp="+IdNumeroExp,true);
		ajax.send(null);
		return false;
		
}//GuardarInformacionExterna

function Enviodatos(){
	var A = document.getElementById('Datos');
	var Expediente = $('NEC').value;
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
        var NEC = document.getElementById('NEC').value;
        var PrimerNombre = document.getElementById('PrimerNombre').value;
        var PrimerApellido = document.getElementById('PrimerApellido').value;
        var FechaNacimiento = document.getElementById('FechaNacimiento').value;
        var Sexo = document.getElementById('Sexo').value;
        var NombreMadre = document.getElementById('NombreMadre').value;
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
        document.getElementById('PrimerApellido_Name').value = PrimerApellido;
        document.getElementById('FechaNacimiento_Name').value = FechaNacimiento;
        document.getElementById('Sexo_Name').value = Sexo;
        document.getElementById('NombreMadre_Name').value = NombreMadre;
	document.getElementById('PrimerApellido_Name').focus();	
}

function esEdadValida(edad){

if (edad != undefined && edad.value != "" ){
   var annios  =  parseInt(edad.value);
      if (annios > 120)
	  {
         alert("Verifique la edad del paciente");
         return false;
      }
	var fecha=new Date();
	var diames=fecha.getDate();
	if (diames<10){diames="0"+diames}
	var mes=fecha.getMonth() +1 ;
	if (mes<10){mes="0"+mes}
	var ano=fecha.getFullYear();
	var annio=ano-annios;
	FechaCalculada=annio+"/"+mes+"/"+diames;
	document.getElementById("FechaNacimiento_Name").value=FechaCalculada;
	
    return true;
      }
}