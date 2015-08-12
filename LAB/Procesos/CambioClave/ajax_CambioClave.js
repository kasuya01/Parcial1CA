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
	
	document.frmnuevo.txtClaveActual.value="";
	document.frmnuevo.txtNuevaClave.value="";
     	document.frmnuevo.txtNuevaClave1.value="";
	document.frmnuevo.txtClaveActual.focus();
}

function ActualizarClave()
{
	/*if (DatosCompletos())
	{*/
		var opcion=1;
		////Pag=1;
		//valores de los cajas de texto
		ClaveActual=document.frmnuevo.txtClaveActual.value;
		NuevaClave=document.frmnuevo.txtNuevaClave.value;
		NuevaClave1= document.frmnuevo.txtNuevaClave1.value;

		//alert (ClaveActual+"-"+NuevaClave+"-"+NuevaClave1);
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//archivo que realiza la operacion ->actualizacion.php
		ajax.open("POST", "ctrCambioClave.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("ClaveActual="+escape(ClaveActual)+"&NuevaClave="+escape(NuevaClave)+"&NuevaClave1="+escape(NuevaClave1)+"&opcion="+opcion);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				//mostrar los nuevos registros en esta capa
				//document.getElementById('divinicial').innerHTML = ajax.responseText;
				alert(ajax.responseText);
				LimpiarCampos();
			}
		}
	/*}else{
		alert("Complete los datos a Ingresar");
		
	}*/
}

function IngresarRegistro(){ //INGRESAR REGISTROS
        if (DatosCompletos())
	{	//donde se mostrar� lo resultados
		divResultado = document.getElementById('divinicial');
		//divInicial= document.getElementById('divinicial');
		//valores de los inputs
		ClaveActual=document.frmnuevo.txtClaveActual.value;
		NuevaClave=document.frmnuevo.txtNuevaClave.value;
     		NuevaClave1= document.frmnuevo.txtNuevaClave1.value;
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
		ajax.send("idarea="+cod+"&nombrearea="+escape(nom)+"&activo="+activo+"&Pag="+Pag+"&opcion="+opcion);
	}
	else{
		alert("Complete los datos a Ingresar");
	}
}











