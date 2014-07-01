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
	 if (document.getElementById('txtidarea').value == "")
		 {
			resp= false;		
		 }
	 if (document.getElementById('txtnombrearea').value == "")
		 {
			resp= false;		
		 }
     if (document.getElementById('cmbActiva').value == "0")
		 {
			resp= false;		
		 }		 
  return resp;
  
}

function LimpiarCampos(){
	document.frmnuevo.txtidarea.value="";
	document.frmnuevo.txtnombrearea.value="";
	document.frmnuevo.txtidarea.focus();
}

function IngresarRegistro(){ //INGRESAR REGISTROS
   if (DatosCompletos())
	{	//donde se mostrar� lo resultados
		divResultado = document.getElementById('divinicial');
		//divInicial= document.getElementById('divinicial');
		//valores de los inputs
		cod=document.frmnuevo.txtidarea.value;
		nom=document.frmnuevo.txtnombrearea.value;
		activo= document.getElementById('cmbActiva').value;
		tipo=document.getElementById('cmbTipo').value;
		//alert(activo);
		var opcion=1;
		Pag=1;
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//uso del medotod POST
		//archivo que realizar� la operacion
		//registro.php
		ajax.open("POST", "ctrLab_Areas.php",true);
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
		ajax.send("idarea="+cod+"&nombrearea="+nom+"&activo="+activo+"&Pag="+Pag+"&opcion="+opcion+"&tipo="+tipo);
	}
	else{
		alert("Complete los datos a Ingresar");
	}
}

function pedirDatos(idarea){ //CARGAR DATOS A MODIFICAR
	//donde se mostrar� el formulario con los datos
	divFormulario = document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
	//divResultado=document.getElementById('divresultado');
	//divInicial= document.getElementById('divinicial');
	//instanciamos el objetoAjax
	//alert(idarea);
	ajax=objetoAjax();
	//uso del medotod POST
	ajax.open("POST", "consulta_AreasLab.php");
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
	ajax.send("idarea="+idarea);	
}


function enviarDatos(){//FUNCION PARA MODIFICAR
	//donde se mostrar� lo resultados
	divResultado = document.getElementById('divinicial');
	divFormulario = document.getElementById('divFrmModificar');
	divNuevo = document.getElementById('divFrmNuevo');
	
	//valores de los cajas de texto
	idarea=document.frmModificar.idarea.value;
	nom=document.frmModificar.nombrearea.value;
	activo=document.frmModificar.cmbActiva.value;
	//activo= document.getElementById('cmbActiva').value;
	tipo=document.frmModificar.cmbTipo.value;
	//alert(activo);
	var opcion=2;
	Pag=1;
	//alert(nom);
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Areas.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	//alert(activo);
	ajax.send("idarea="+idarea+"&nombrearea="+nom+"&activo="+activo+"&Pag="+Pag+"&opcion="+opcion+"&tipo="+tipo);
	
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

function eliminarDato(idarea){ //FUNCION PARA ELIMINACION
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
		ajax.open("POST", "ctrLab_Areas.php",true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		//ajax.send("idarea="+idarea+"&opcion="+opcion);
		ajax.send("idarea="+idarea+"&Pag="+Pag+"&opcion="+opcion);
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
	idarea=document.frmnuevo.txtidarea.value;
	nom=document.frmnuevo.txtnombrearea.value;
        activo= document.getElementById('cmbActiva').value;
	tipo=document.getElementById('cmbTipo').value;

	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Areas.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&nombrearea="+nom+"&activo="+activo+"&Pag="+Pag+"&opcion="+opcion+"&tipo="+tipo);
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divinicial').innerHTML = ajax.responseText;
		}
	}	
}


function show_event(Pag)
{
	opcion=4;
	//alert(1);
	//document.getElementById('divinicial').innerHTML = 'cargando eventos...';
    ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_Areas.php', true);
	ajax.onreadystatechange = function(){ 
	//ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText
		  }
	}
	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("&Pag="+Pag+"&opcion="+opcion);	
}


function show_event_search(Pag)
{	
	opcion=5;
	idarea=document.getElementById('txtidarea').value;
	nom=document.getElementById('txtnombrearea').value;
	activo= document.getElementById('cmbActiva').value;
	tipo=document.getElementById('cmbTipo').value;
	ajax=objetoAjax();
	ajax.open("POST", "ctrLab_Areas.php", true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		 }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idarea="+idarea+"&nombrearea="+nom+"&activo="+activo+"&Pag="+Pag+"&opcion="+opcion+"&tipo="+tipo);
}
