function objetoAjax(){
	var xmlhttp=false;
	try{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e){
		try{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}catch(E){
			xmlhttp = false;
  		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function DatosCompletos()
{
  var resp = true;
	 
	 if (document.getElementById('txtobservacion').value == "")								  
		 {
			resp= false;		
		 }
	if (document.getElementById('cmbArea').value == "0")								  
		 {
			resp= false;		
		 }
   	 
  return resp;  
}

function LimpiarCampos(){
	document.frmnuevo.txtidobservacion.value="";
	document.frmnuevo.txtobservacion.value="";
	document.frmnuevo.cmbArea.value="0";
	document.frmnuevo.cmbTipoRespuesta.value="0";
	document.frmnuevo.txtobservacion.focus();
}

function IngresarRegistro(){ //INGRESAR REGISTROS	
	if (DatosCompletos())
	{
		//donde se mostrar� lo resultados
		divResultado = document.getElementById('divinicial');
		//valores de los inputs
		idobservacion=document.frmnuevo.txtidobservacion.value;
		observacion=document.frmnuevo.txtobservacion.value;
		idarea=document.frmnuevo.cmbArea.value;
		tiporespuesta=document.frmnuevo.cmbTipoRespuesta.value;
		var opcion=1;
		var Pag=1;
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//uso del medotod POST
		//archivo que realizar� la operacion
		ajax.open("POST", "ctrLab_Observaciones.php",true);
		       
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				//mostrar resultados en esta capa
				//divResultado.innerHTML = ajax.responseText;
				alert(ajax.responseText);
				//llamar a funcion para limpiar los inputs
				LimpiarCampos();
                                show_event(1);
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idobservacion="+idobservacion+"&idarea="+idarea+"&tiporespuesta="+tiporespuesta+"&observacion="+escape(observacion)+"&Pag="+Pag+"&opcion="+opcion);		
	}
	else{
		alert("Complete los datos a Ingresar");
	}	
}

function pedirDatos(idobservacion){   //CARGAR DATOS A MODIFICAR
	//donde se mostrar� el formulario con los datos
	divFormulario = document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
	divInicial= document.getElementById('divinicial');
	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//uso del medotod POST
	ajax.open("POST", "consulta_Observaciones.php");
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			divFormulario.innerHTML = ajax.responseText
			divFormulario.style.display="block";
			divFormularioNuevo.style.display="none";
			divInicial.style.display="block";
		}
	}
	//como hacemos uso del metodo POST
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando el codigo 
	ajax.send("idobservacion="+ idobservacion );
	
}

function enviarDatos(){    //FUNCION PARA MODIFICAR
	//donde se mostrar� lo resultados
	//alert("lsaldasda");
	divResultado = document.getElementById('divinicial');
	divFormulario = document.getElementById('divFrmModificar');
	divNuevo = document.getElementById('divFrmNuevo');
	//divInicio = document.getElementById('divinicial');

	//valores de los cajas de texto
	idobservacion=document.frmModificar.idobservacion.value;
	observacion=document.frmModificar.txtobservacion.value;
	tiporespuesta=document.frmModificar.cmbTipoRespuesta.value;
	idarea=document.frmModificar.cmbArea.value;
	var opcion=2;
	Pag=1;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	ajax.open("POST", "ctrLab_Observaciones.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idobservacion="+idobservacion+"&idarea="+idarea+"&tiporespuesta="+tiporespuesta+"&observacion="+escape(observacion)+"&Pag="+Pag+"&opcion="+opcion);		
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			divResultado.style.display="block";
			//mostrar los nuevos registros en esta capa
			//divResultado.innerHTML = ajax.responseText
			alert(ajax.responseText);
			//una vez actualizacion ocultamos formulario
			divFormulario.style.display="none";
			divNuevo.style.display="block";
                        show_event(1); 
		}
	}	
}

function eliminarDato(idobservacion){ //FUNCION PARA ELIMINACION
	//donde se mostrar� el resultado de la eliminacion
	divResultado = document.getElementById('divinicial');
	//divInicio=document.getElementById('divinicial');
	var opcion=3;
	Pag=1;
	idarea="";
	tiporespuesta="";
	
	//usaremos un cuadro de confirmacion	
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if ( eliminar ) {
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		ajax.open("POST", "ctrLab_Observaciones.php",true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idobservacion="+idobservacion+"&idarea="+idarea+"&tiporespuesta="+tiporespuesta+"&observacion="+escape(observacion)+"&Pag="+Pag+"&opcion="+opcion);		
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				//mostrar resultados en esta capa
				//divResultado.innerHTML = ajax.responseText;
				alert(ajax.responseText);
				show_event(1);			
			}
		}

	}
}

function BuscarObservacion()
{
	var opcion=5;
	Pag=1;
        //valores de los cajas de texto
	idobservacion=document.getElementById('txtidobservacion').value;
	observacion=document.getElementById('txtobservacion').value;
	idarea=document.getElementById('cmbArea').value;
	tiporespuesta=document.getElementById('cmbTipoRespuesta').value;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Observaciones.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idobservacion="+idobservacion+"&idarea="+idarea+"&tiporespuesta="+tiporespuesta+"&observacion="+escape(observacion)+"&Pag="+Pag+"&opcion="+opcion);		
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divinicial').innerHTML = ajax.responseText;
		}
	}	
}

function Cancelar(){
	show_event(1);
}

function show_event(Pag)
{
	opcion=4;
	idobservacion="";
	observacion="";
	idarea="";
	tiporespuesta="";
    ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_Observaciones.php', true);
	ajax.onreadystatechange = function(){ 
	//ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		  // alert(ajax.responseText);
		   document.getElementById('divinicial').innerHTML = ajax.responseText
		  }
	}
	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("Pag="+Pag+"&opcion="+opcion+"&tiporespuesta="+tiporespuesta+"&idarea="+idarea+"&observacion="+escape(observacion)+"&idobservacion="+idobservacion);	
}

function show_event_search(Pag)
{	
	opcion=5;
	idobservacion=document.getElementById('txtidobservacion').value;
	observacion=document.getElementById('txtobservacion').value;
	idarea=document.getElementById('cmbArea').value;
	tiporespuesta=document.getElementById('cmbTipoRespuesta').value;
	ajax=objetoAjax();
	ajax.open("POST", "ctrLab_Observaciones.php", true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		 }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("Pag="+Pag+"&opcion="+opcion+"&tiporespuesta="+tiporespuesta+"&idarea="+idarea+"&observacion="+escape(observacion)+"&idobservacion="+idobservacion);	
}

