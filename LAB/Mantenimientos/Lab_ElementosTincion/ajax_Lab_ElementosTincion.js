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
	 
	 if (document.getElementById('txtElemTincion').value == "")								  
		 {
			resp= false;		
		 }
  return resp;  
}

function LimpiarCampos(){
	document.frmnuevo.txtidElemTincion.value="";
	document.frmnuevo.txtElemTincion.value="";
	document.frmnuevo.txtElemTincion.focus();
}

function IngresarRegistro(){ //INGRESAR REGISTROS	

	if (DatosCompletos())
		{
	   //donde se mostrar� lo resultados
		divResultado = document.getElementById('divinicial');
		//valores de los inputs
		idElemTin=document.frmnuevo.txtidElemTincion.value;
		ElemTin=document.frmnuevo.txtElemTincion.value;
		var opcion=1;
		var Pag=1;
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//uso del medotod POST
		//archivo que realizar� la operacion
		ajax.open("POST", "ctrLab_ElementosTincion.php",true);
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
		ajax.send("idElemTin="+idElemTin+"&ElemTin="+escape(ElemTin)+"&Pag="+Pag+"&opcion="+opcion);		
	}
	else{
		alert("Complete los datos a Ingresar");
	}	
}

function pedirDatos(idElemTin){   //CARGAR DATOS A MODIFICAR
	//donde se mostrar� el formulario con los datos
	divFormulario = document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
	divInicial= document.getElementById('divinicial');
	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//uso del medotod POST
	ajax.open("POST", "consulta_ElementosTincion.php");
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
	ajax.send("idElemTin="+ idElemTin );
	
}

function enviarDatos(){    //FUNCION PARA MODIFICAR
	//donde se mostrar� lo resultados
	divResultado = document.getElementById('divinicial');
	divFormulario = document.getElementById('divFrmModificar');
	divNuevo = document.getElementById('divFrmNuevo');
	//divInicio = document.getElementById('divinicial');

	//valores de los cajas de texto
	idElemTin=document.frmModificar.idElemTin.value;
	ElemTin=document.frmModificar.ElemTin.value;
	var opcion=2;
	Pag=1;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	ajax.open("POST", "ctrLab_ElementosTincion.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idElemTin="+idElemTin+"&ElemTin="+escape(ElemTin)+"&Pag="+Pag+"&opcion="+opcion);
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


function eliminarDato(idElemTin){ //FUNCION PARA ELIMINACION
	//donde se mostrar� el resultado de la eliminacion
	divResultado = document.getElementById('divinicial');
	//divInicio=document.getElementById('divinicial');
	var opcion=3;
	Pag=1;
	//usaremos un cuadro de confirmacion	
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if ( eliminar ) {
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		ajax.open("POST", "ctrLab_ElementosTincion.php",true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idElemTin="+idElemTin+"&ElemTin="+escape(ElemTin)+"&Pag="+Pag+"&opcion="+opcion);
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
	idElemTin=document.getElementById('txtidElemTincion').value;
	ElemTin=document.getElementById('txtElemTincion').value;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_ElementosTincion.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("Pag="+Pag+"&opcion="+opcion+"&ElemTin="+escape(ElemTin)+"&idElemTin="+idElemTin);
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
	idElemTin="";
	ElemTin="";
	
	//document.getElementById('divinicial').innerHTML = 'cargando eventos...';
	ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_ElementosTincion.php', true);
	ajax.onreadystatechange = function(){ 
	//ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText
		  }
	}
	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("Pag="+Pag+"&opcion="+opcion+"&ElemTin="+escape(ElemTin)+"&idElemTin="+idElemTin);	
}

function show_event_search(Pag)
{	
	opcion=5;
	idElemTin=document.getElementById('txtidElemTincion').value;
	ElemTin=document.getElementById('txtElemTincion').value;
	ajax=objetoAjax();
	ajax.open("POST", "ctrLab_ElementosTincion.php", true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		 }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("Pag="+Pag+"&opcion="+opcion+"&ElemTin="+escape(ElemTin)+"&idElemTin="+idElemTin);
}

