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

function LlenarExamenes(idarea)
{
	var opcion=5;
   
	 //instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Procedimientos.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divExamen').innerHTML = ajax.responseText;
		}
	}	
}

function DatosCompletos(tipo) {
	var error = [];
	var data = new Object();
	var error_message = "";

	switch(tipo) {
		case 1:
			if (document.getElementById('cmbArea').value === "0") {
				error.push('Área');
			}
			if (document.getElementById('cmbExamen').value === "0") {
					error.push('Exámen');
			}
			if (document.getElementById('txtproc').value === "") {
					error.push('Procedimiento');	
			}
		    if (document.getElementById('txtFechainicio').value === "") {
					error.push('Fecha de Inicio');		
			}
		    if (document.getElementById('cmbSexo').value === "0") {
					error.push('Sexo');
			}
		    if (document.getElementById('cmbEdad').value === "0") {
					error.push('Rango Edad');
			}
			break;
		case 2:
			if (document.frmModificar.cmbArea.value === "0") {
				error.push('Área');
			}
			if (document.frmModificar.cmbExamen.value === "0") {
					error.push('Exámen');
			}
			if (document.frmModificar.txtproc.value === "") {
					error.push('Procedimiento');	
			}
		    if (document.frmModificar.txtFechainicio.value === "") {
					error.push('Fecha de Inicio');		
			}
		    if (document.frmModificar.cmbSexo.value === "0") {
					error.push('Sexo');
			}
		    if (document.frmModificar.cmbEdad.value === "0") {
					error.push('Rango Edad');
			}
			break;
	}
	
	if(error.length > 0) {

		if(error.length === 1)
			errorMessage = "Error...\n\nEl siguiente campo no han sido completado: \n\n";
		else
			errorMessage = "Error...\n\nLos siguientes campos no han sido completados: \n\n";

        for (i = 0; i < error.length; i++) {
            errorMessage += error[i] + "\n";
        }

        data['status'] = false;
		data['errorMessage'] = errorMessage;
	} else {
		data['status'] = true;
	}

  return data;  
}

function LimpiarCampos(){
	document.getElementById('cmbArea').value="0";
	document.getElementById('cmbExamen').value="0";
	document.getElementById('txtproc').value="";
	document.getElementById('txtunidades').value="";
	document.getElementById('txtrangoini').value="";
	document.getElementById('txtrangofin').value="";
	document.getElementById('txtFechainicio').value="";
	document.getElementById('txtFechaFin').value="";
    document.getElementById('cmbSexo').value="0";
    document.getElementById('cmbEdad').value="0";
	//document.getElementById('txtnota').value="";
}

function IngresarRegistro(){ //INGRESAR REGISTROS
	//donde se mostrar� lo resultados
	//valores de los inputs
	var datosCompletos = DatosCompletos(1);
  	if (datosCompletos['status']) {	//donde se mostrar� lo resultados
		idarea=document.getElementById('cmbArea').value;
		idexamen=document.getElementById('cmbExamen').value
		proce=document.getElementById('txtproc').value;
		unidades=document.getElementById('txtunidades').value;
		rangoini=document.getElementById('txtrangoini').value;
		rangofin=document.getElementById('txtrangofin').value;
		Fechaini=document.getElementById('txtFechainicio').value;
		Fechafin=document.getElementById('txtFechaFin').value;
        sexo=document.getElementById('cmbSexo').value;
        redad=document.getElementById('cmbEdad').value;
        
        //alert(Fechaini+"**"+Fechafin);
		var opcion=1;
		Pag=1;
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//archivo que realizar� la operacion
		ajax.open("POST", "ctrLab_Procedimientos.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				//mostrar resultados en esta capa
				//document.getElementById('divinicial').innerHTML = ajax.responseText;
	                        alert(ajax.responseText);
				LimpiarCampos();
				 show_event(1);
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&proce="+escape(proce)+
	                "&unidades="+escape(unidades)+"&Pag="+Pag+"&rangoini="+rangoini+"&rangofin="+rangofin+
	                "&Fechaini="+Fechaini+"&Fechafin="+Fechafin+"&sexo="+sexo+"&redad="+redad);
  	} else {
  		alert(datosCompletos['errorMessage']);
	}

}

function pedirDatos(idproce){ //CARGAR DATOS A MODIFICAR
	//donde se mostrar� el formulario con los datos
	divFormulario=document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//alert("esto----> ".idproce);
	//uso del medotod POST
	ajax.open("POST", "consulta_Procedimientos.php");
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			document.getElementById('divFrmModificar').innerHTML = ajax.responseText
			divFormularioNuevo.style.display="none";
			divFormulario.style.display="block"; 
		}
	}
	//como hacemos uso del metodo POST
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando el codigo 
	ajax.send("idproce="+ idproce);
	
}

function enviarDatos(){//FUNCION PARA MODIFICAR
	//donde se mostrar� lo resultados
	var datosCompletos = DatosCompletos(2);

  	if (datosCompletos['status']) {
		divResultado = document.getElementById('divinicial');
		divFormulario = document.getElementById('divFrmModificar');
		divNuevo = document.getElementById('divFrmNuevo');
		//valores de los cajas de texto
		proce=document.frmModificar.txtproc.value;
		unidades=document.frmModificar.txtunidades.value;
		idarea=document.frmModificar.cmbArea.value;
		idexamen=document.frmModificar.cmbExamen.value;	
		rangoini=document.frmModificar.txtrangoini.value;
		rangofin=document.frmModificar.txtrangofin.value;
		//nota=document.frmModificar.txtnota.value;
		idproce=document.frmModificar.txtoculto.value;
		Fechaini=document.frmModificar.txtFechainicio.value;
		Fechafin=document.frmModificar.txtFechaFin.value;
		sexo=document.frmModificar.cmbSexo.value;
	    redad=document.frmModificar.cmbEdad.value;
		//alert(idproce+'***'+Fechaini+'***'+Fechafin);
		var opcion=2;	
		Pag=1;
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//usando del medoto POST
		//archivo que realizar� la operacion ->actualizacion.php
		ajax.open("POST", "ctrLab_Procedimientos.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&proce="+escape(proce)+"&unidades="+escape(unidades)+"&Pag="+Pag
		+"&rangoini="+rangoini+"&rangofin="+rangofin+"&idproce="+idproce+"&Fechaini="+Fechaini+"&Fechafin="+Fechafin+
	                "&sexo="+sexo+"&redad="+redad);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4) 
			{
				divResultado.style.display="block";
				//mostrar los nuevos registros en esta capa
				//divResultado.innerHTML = ajax.responseText
	                        alert(ajax.responseText);
				//una vez actualizacion ocultamos formulario
				divFormulario.style.display="none";
				divNuevo.style.display="block";
				LimpiarCampos();
				show_event(1); 
				
			}
		}
	} else {
		alert(datosCompletos['errorMessage']);
	}
}

function eliminarDato(idproce){ //FUNCION PARA ELIMINACION
	//donde se mostrar� el resultado de la eliminacion
	divResultado = document.getElementById('divinicial');
	var opcion=3;
	Pag=1;
	//alert(idproce);
	//usaremos un cuadro de confirmacion	
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if ( eliminar ) {
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		ajax.open("POST", "ctrLab_Procedimientos.php",true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("&idproce="+idproce+"&opcion="+opcion+"&Pag="+Pag);
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

function BuscarDatos()
{
	var opcion=7;
	Pag=1;
    //valores de los cajas de texto
	idarea=document.getElementById('cmbArea').value;
	idexamen=document.getElementById('cmbExamen').value;
	proce=document.getElementById('txtproc').value;
	unidades=document.getElementById('txtunidades').value;
	rangoini=document.getElementById('txtrangoini').value;
	rangofin=document.getElementById('txtrangofin').value;
	Fechaini=document.getElementById('txtFechainicio').value;
	Fechafin=document.getElementById('txtFechaFin').value;
    sexo=document.getElementById('cmbSexo').value;
    redad=document.getElementById('cmbEdad').value;
	//nota=document.getElementById('txtnota').value;
      // alert(sexo+"--"+redad); 
	idproce="";
	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Procedimientos.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&proce="+escape(proce)+
        "&unidades="+escape(unidades)+"&Pag="+Pag+"&rangoini="+rangoini+"&rangofin="+rangofin+
        "&idproce="+idproce+"&Fechaini="+Fechaini+"&Fechafin="+Fechafin+"&sexo="+sexo+"&redad="+redad);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divinicial').innerHTML = ajax.responseText;
			//alert(ajax.responseText);
		}
	}	
}

function show_event(Pag)
{	opcion=4;
	ajax=objetoAjax();
	
	ajax.open("POST", 'ctrLab_Procedimientos.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("opcion="+opcion+"&Pag="+Pag);
} 

function show()
{
	show_event(1);
	//show_event_search(1);
}


function show_event_search(Pag)
{	opcion=8;
	idarea=document.getElementById('cmbArea').value;
	idexamen=document.getElementById('cmbExamen').value;
	proce=document.getElementById('txtproc').value;	
	unidades=document.getElementById('txtunidades').value;	
	rangoini=document.getElementById('txtrangoini').value;
	rangofin=document.getElementById('txtrangofin').value;
	Fechaini=document.getElementById('txtFechainicio').value;
	Fechafin=document.getElementById('txtFechaFin').value;
	sexo=document.getElementById('cmbSexo').value;
        redad=document.getElementById('cmbEdad').value;
	
        ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_Procedimientos.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		   document.getElementById('divinicial').style.display="block";
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&proce="+escape(proce)+
            "&unidades="+escape(unidades)+"&Pag="+Pag+"&rangoini="+rangoini+"&rangofin="+rangofin+
            "&Fechaini="+Fechaini+"&Fechafin="+Fechafin+"&sexo="+sexo+"&redad="+redad);
}	

function Cancelar()
{
  show_event(1);
  LimpiarCampos(); 
}

