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

function LimpiarCampos(){
	document.getElementById('txtidorigen').value="";
	document.getElementById('txtnombreorigen').value="";
	document.getElementById('cmbTipoMuestra').value="0";
	document.getElementById('txtidorigen').focus();
}

function IngresarRegistro(){ //INGRESAR REGISTROS
  	//donde se mostrar� lo resultados
		divResultado = document.getElementById('divinicial');
		//valores de los inputs
		idorigen=document.getElementById('txtidorigen').value;
		nombreorigen=document.getElementById('txtnombreorigen').value;
		tipomuestra=document.getElementById('cmbTipoMuestra').value;
		var opcion=1;
		var Pag=1;
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//uso del medotod POST
		//archivo que realizar� la operacion
		ajax.open("POST", "ctrMnt_OrigenMuestra.php",true);
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
		ajax.send("idorigen="+idorigen+"&nombreorigen="+escape(nombreorigen)+"&tipomuestra="+tipomuestra+"&Pag="+Pag+"&opcion="+opcion);

}

function pedirDatos(idorigen){ //CARGAR DATOS A MODIFICAR
	//donde se mostrar� el formulario con los datos
	divFormulario = document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
		//divResultado=document.getElementById('divresultado');
	//divInicial= document.getElementById('divinicial');
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//uso del medotod POST
	ajax.open("POST", "consulta_OrigenMuestra.php");
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			divFormulario.innerHTML = ajax.responseText
			divFormulario.style.display="block";
			divFormularioNuevo.style.display="none";			
		}
	}
	//como hacemos uso del metodo POST
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando el codigo 
	ajax.send("idorigen="+idorigen );	
}

function enviarDatos(){//FUNCION PARA MODIFICAR
	//donde se mostrar� lo resultados
	divResultado = document.getElementById('divinicial');
	divFormulario = document.getElementById('divFrmModificar');
	divNuevo = document.getElementById('divFrmNuevo');
	

	//valores de los cajas de texto
	idorigen=document.frmModificar.idorigen.value;
	nombreorigen=document.frmModificar.nombreorigen.value;
	tipomuestra=document.frmModificar.cmbTipoMuestra.value;
//	alert(nombreorigen);
	var opcion=2;
	Pag=1;
	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrMnt_OrigenMuestra.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idorigen="+idorigen+"&nombreorigen="+escape(nombreorigen)+"&tipomuestra="+tipomuestra+"&Pag="+Pag+"&opcion="+opcion);
	
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

function eliminarDato(idorigen){ //FUNCION PARA ELIMINACION
	//donde se mostrar� el resultado de la eliminacion
	divResultado = document.getElementById('divinicial');
	//divInicio=document.getElementById('divinicial');
	var opcion=3;
	Pag=1;
	nombreorigen="";
	tipomuestra="";
	
	//usaremos un cuadro de confirmacion	
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if ( eliminar ) {
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		ajax.open("POST", "ctrMnt_OrigenMuestra.php",true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		//ajax.send("idarea="+idarea+"&opcion="+opcion);
		ajax.send("idorigen="+idorigen+"&nombreorigen="+escape(nombreorigen)+"&tipomuestra="+tipomuestra+"&Pag="+Pag+"&opcion="+opcion);
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

function BuscarCodigo()
{
	var opcion=5;
	Pag=1;
        //valores de los cajas de texto
	idorigen=document.getElementById('txtidorigen').value;
	tipomuestra=document.getElementById('cmbTipoMuestra').value;
	nombreorigen=document.getElementById('txtnombreorigen').value;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrMnt_OrigenMuestra.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idorigen="+idorigen+"&nombreorigen="+escape(nombreorigen)+"&tipomuestra="+tipomuestra+"&Pag="+Pag+"&opcion="+opcion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divinicial').innerHTML = ajax.responseText;
		}
	}	
}

function show_event(Pag)
{
	opcion=4;
	idorigen="";
	nombreorigen="";
	tipomuestra="";
	
	//document.getElementById('divinicial').innerHTML = 'cargando eventos...';
    ajax=objetoAjax();
	ajax.open("POST", 'ctrMnt_OrigenMuestra.php', true);
	ajax.onreadystatechange = function(){ 
	//ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText
		  }
	}
	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idorigen="+idorigen+"&nombreorigen="+escape(nombreorigen)+"&Pag="+Pag+"&opcion="+opcion+"&tipomuestra="+tipomuestra);	
}

function show_event_search(Pag)
{	
	opcion=5;
	idorigen=document.getElementById('txtidorigen').value;
	nombreorigen=document.getElementById('txtnombreorigen').value;
	tipomuestra=document.getElementById('cmbTipoMuestra').value;
	ajax=objetoAjax();
	ajax.open("POST", "ctrMnt_OrigenMuestra.php", true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		 }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idorigen="+idorigen+"&nombreorigen="+escape(nombreorigen)+"&Pag="+Pag+"&opcion="+opcion+"&tipomuestra="+tipomuestra);
}


