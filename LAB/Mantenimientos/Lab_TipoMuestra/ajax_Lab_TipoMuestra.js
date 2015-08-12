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
	if (document.getElementById('txtnombretipo').value == "")
		 {
			resp= false;		
		 }
  return resp;
  
}

function LimpiarCampos(){
	//document.frmnuevo.txtidtipo.value="";
	document.frmnuevo.txtnombretipo.value="";
	document.frmnuevo.txtnombretipo.focus();
} 

function IngresarRegistro(){ //INGRESAR REGISTROS
   if (DatosCompletos())
	{
		//donde se mostrar� lo resultados
		divResultado = document.getElementById('divresultado');
		divInicial= document.getElementById('divinicial');
		//valores de los inputs
		//cod=document.frmnuevo.txtidtipo.value;
		nom=document.frmnuevo.txtnombretipo.value;
		var opcion=1;
		Pag=1;
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//uso del medotod POST
		//archivo que realizar� la operacion
		//registro.php
		ajax.open("POST", "ctrLab_TipoMuestra.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
			  //document.getElementById('divinicial').innerHTML = ajax.responseText	
			//mostrar resultados en esta capa
			//document.getElementById('divinicial').innerHTML = ajax.responseText;
			alert(ajax.responseText);
			LimpiarCampos();
			show_event(1);
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("nombretipo="+nom+"&opcion="+opcion+"&Pag="+Pag);
	}
	else{
		alert("Complete los datos a Ingresar");
	}
	
}

function pedirDatos(idtipo){ //CARGAR DATOS A MODIFICAR
	divFormulario=document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//Pag=1;
	//uso del medotod POST
	ajax.open("POST", "consulta_TipoMuestra.php");
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			//document.getElementById('divFrmModificar').innerHTML = ajax.responseText
			//document.getElementById('divFrmModificar').style.display="block";
			//document.getElementById('divFrmNuevo').style.display="none";
			document.getElementById('divFrmModificar').innerHTML = ajax.responseText
			divFormularioNuevo.style.display="none";
			divFormulario.style.display="block"; 
			}
	}
	//como hacemos uso del metodo POST
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando el codigo 
//	ajax.send("idtipo="+idtipo+"&Pag="+Pag );	
ajax.send("idtipo="+idtipo);	
} 

function enviarDatos(){//FUNCION PARA MODIFICAR
	divFormulario = document.getElementById('divFrmModificar');
	divNuevo = document.getElementById('divFrmNuevo');
	divResultado = document.getElementById('divinicial');
	
	//valores de los cajas de texto
	idtipo=document.frmModificar.idtipo.value;
	nom=document.frmModificar.nombretipo.value;
	var opcion=2;
	Pag=1;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	ajax.open("POST", "ctrLab_TipoMuestra.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idtipo="+idtipo+"&nombretipo="+nom+"&opcion="+opcion+"&Pag="+Pag);
	
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa		
			//una vez actualizacion ocultamos formulario		
 			alert(ajax.responseText);
			//mostrar los nuevos registros en esta capa
			divResultado.innerHTML = ajax.responseText
			//una vez actualizacion ocultamos formulario
			divFormulario.style.display="none";
			divNuevo.style.display="block";
 			show_event(1);

		}
	}	
}

function eliminarDato(idtipo){ //FUNCION PARA ELIMINACION
	//donde se mostrar� el resultado de la eliminacion
	divResultado = document.getElementById('divinicial');
	//divInicio=document.getElementById('divinicial');
	var opcion=3;
	nom="";
	Pag=1;
	//Pag="";
	//usaremos un cuadro de confirmacion	
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if ( eliminar ) {
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		ajax.open("POST", "ctrLab_TipoMuestra.php",true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		//ajax.send("idtipo="+idtipo+"&opcion="+opcion);
		ajax.send("idtipo="+idtipo+"&nombretipo="+nom+"&opcion="+opcion+"&Pag="+Pag);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				//mostrar resultados en esta capa
				 alert(ajax.responseText);
				 show_event(1);
				//divInicio.style.display="none";
				
			}
		}

	}
}

function BuscarCodigo()
{
	var opcion=5;
	Pag=1;
        //valores de los cajas de texto
	cod="";
	nom=document.getElementById('txtnombretipo').value;
      alert 
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
       // alert(nom);
	ajax.open("POST", "ctrLab_TipoMuestra.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idtipo="+cod+"&nombretipo="+nom+"&opcion="+opcion+"&Pag="+Pag);	
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
	idtipo="";
	nombretipo="";
	ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_TipoMuestra.php', true);
	ajax.onreadystatechange = function(){ 
	//ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText
		  }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idtipo="+idtipo+"&nombretipo="+nombretipo+"&Pag="+Pag+"&opcion="+opcion);	
}

function show_event_search(Pag)
{	
	opcion=5;
	//cod=document.getElementById('txtidtipo').value;
	nom=document.getElementById('txtnombretipo').value;
	ajax=objetoAjax();
	ajax.open("POST", "ctrLab_TipoMuestra.php", true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		 }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("nombretipo="+nom+"&opcion="+opcion+"&Pag="+Pag);	
}


