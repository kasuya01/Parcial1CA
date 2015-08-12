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
	 if (document.getElementById('txtidestandar').value == "")
		 {
			resp= false;		
		 }
	 if (document.getElementById('txtnombreestandar').value == "")								  
		 {
			resp= false;		
		 }
         if (document.getElementById('cmbgrupo').value == 0)								  
		 {
			resp= false;		
		 }         
  return resp;  
}

function LimpiarCampos(){
	document.frmnuevo.txtidestandar.value="";
	document.frmnuevo.txtnombreestandar.value="";
        document.frmnuevo.cmbgrupo.value=0;
	document.frmnuevo.txtidestandar.focus();
}

function IngresarRegistro(){ //INGRESAR REGISTROS	
	if (DatosCompletos())
	{
		//donde se mostrar� lo resultados
		divResultado = document.getElementById('divinicial');
		//valores de los inputs
		idestandar=document.frmnuevo.txtidestandar.value;
		descripcion=document.frmnuevo.txtnombreestandar.value;
                grupo=document.frmnuevo.cmbgrupo.value;
               //alert (grupo);
		var opcion=1;
		var Pag=1;
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//uso del medotod POST
		//archivo que realizar� la operacion
		ajax.open("POST", "ctrLab_CodigosEstandar.php",true);
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
		ajax.send("idestandar="+idestandar+"&descripcion="+descripcion+"&Pag="+Pag+"&opcion="+opcion+"&grupo="+grupo);		
	}
	else{
		alert("Complete los datos a Ingresar");
	}	
}

function pedirDatos(idestandar){   //CARGAR DATOS A MODIFICAR
	//donde se mostrar� el formulario con los datos
	divFormulario = document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
	divInicial= document.getElementById('divinicial');
	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//uso del medotod POST
	ajax.open("POST", "consulta_CodigosEstandar.php");
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
	ajax.send("idestandar="+ idestandar );
	
}

function enviarDatos(){    //FUNCION PARA MODIFICAR
	//donde se mostrar� lo resultados
	divResultado = document.getElementById('divinicial');
	divFormulario = document.getElementById('divFrmModificar');
	divNuevo = document.getElementById('divFrmNuevo');
	//valores de los cajas de texto
	idestandar=document.frmModificar.idestandar.value;
	descripcion=document.frmModificar.descripcion.value;
        grupo=document.frmModificar.cmdgrupo.value;
        
	var opcion=2;
	Pag=1;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	ajax.open("POST", "ctrLab_CodigosEstandar.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idestandar="+idestandar+"&descripcion="+descripcion+"&Pag="+Pag+"&opcion="+opcion+"&grupo="+grupo);
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
function eliminarDato(idestandar){ //FUNCION PARA ELIMINACION
	//donde se mostrar� el resultado de la eliminacion
	divResultado = document.getElementById('divinicial');
	var opcion=3;
	Pag=1;
	descripcion="";
	//usaremos un cuadro de confirmacion	
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if ( eliminar ) {
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		ajax.open("POST", "ctrLab_CodigosEstandar.php",true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idestandar="+idestandar+"&descripcion="+escape(descripcion)+"&Pag="+Pag+"&opcion="+opcion);
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
	idestandar=document.getElementById('txtidestandar').value;
	descripcion=document.getElementById('txtnombreestandar').value;
	grupo=document.getElementById('cmbgrupo').value;	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_CodigosEstandar.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idestandar="+idestandar+"&descripcion="+escape(descripcion)+"&Pag="+Pag+"&opcion="+opcion+"&grupo="+grupo);
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
	idestandar="";
	descripcion="";
	//document.getElementById('divinicial').innerHTML = 'cargando eventos...';
   ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_CodigosEstandar.php', true);
	ajax.onreadystatechange = function(){ 
	//ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText
		  }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idestandar="+idestandar+"&descripcion="+escape(descripcion)+"&Pag="+Pag+"&opcion="+opcion);	
}

function show_event_search(Pag)
{	
	opcion=5;
	idestandar=document.getElementById('txtidestandar').value;
	descripcion=document.getElementById('txtnombreestandar').value;
         grupo=document.getElementById('cmbgrupo').value;
	ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_CodigosEstandar.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		 }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idestandar="+idestandar+"&descripcion="+escape(descripcion)+"&Pag="+Pag+"&opcion="+opcion+"&grupo="+grupo);	
}

function Cancelar()
{
  show_event(1);
  LimpiarCampos();
}

