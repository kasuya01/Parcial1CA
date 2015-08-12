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
	document.getElementById('txtidexamen').value="";
	document.frmnuevo.cmbArea.value="0";
	document.frmnuevo.txtnombreexamen.value="";
	document.frmnuevo.cmbEstandar.value="0";
	document.frmnuevo.cmbPlantilla.value="0";
	document.frmnuevo.txtobservacion.value="";
    document.frmnuevo.cmbUbicacion.value="";
	document.frmnuevo.cmbFormularios.value="0";
	document.frmnuevo.cmbPrograma.value="0";
	document.frmnuevo.cmbEtiqueta.value="G";
	document.frmnuevo.txtnombreexamen.focus();
}

function ValidarCampos()
{

  var resp = true;
	 if (document.getElementById('txtidexamen').value == "")
		 {
			resp= false;		
		 }
	 if (document.getElementById('txtnombreexamen').value == "")
		 {
			resp= false;		
		 }
     	if (document.getElementById('cmbArea').value == "0")
		 {
			resp= false;		
		 }	
   	if (document.getElementById('cmbPlantilla').value == "0")
		 {
			resp= false;		
		 }	
  	 if (document.getElementById('cmbEstandar').value == "0")
		 {
			resp= false;		
		 }	
	 if (document.getElementById('cmbUbicacion').value == "")
		 {
			resp= false;		
		 }		
	if (document.getElementById('cmbEstandarRep').value == "")
		 {
			resp= false;		
		 }	
  return resp;
}

function IngresarRegistro(){ //INGRESAR REGISTROS
	//donde se mostrar� lo resultados
	//valores de los inputs
if (ValidarCampos())
{
	idarea=document.frmnuevo.cmbArea.value;
	idexamen=document.getElementById('txtidexamen').value
	nomexamen=document.frmnuevo.txtnombreexamen.value;
	idestandar=document.frmnuevo.cmbEstandar.value;
	plantilla=document.frmnuevo.cmbPlantilla.value;
	observacion=document.frmnuevo.txtobservacion.value;
	ubicacion=document.getElementById('cmbUbicacion').value;
	idestandarRep=document.frmnuevo.cmbEstandarRep.value;
	idformulario=document.frmnuevo.cmbFormularios.value;
	etiqueta=document.frmnuevo.cmbEtiqueta.value;
	//codempresa=document.frmnuevo.txttxtcodempresa.value;
	
	//alert (etiqueta);
	
	var opcion=1;
	Pag=1;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realiza la operacion
	ajax.open("POST", "ctrLab_Examenes.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			//document.getElementById('divinicial').innerHTML = ajax.responseText;
                        alert(ajax.responseText);
			LimpiarCampos();
			show_event(Pag);
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idexamen="+idexamen+"&observacion="+escape(observacion)+"&idarea="+idarea+"&nomexamen="+escape(nomexamen)+"&idestandar="+idestandar+"&Pag="+Pag+"&opcion="+opcion+"&plantilla="+plantilla+"&ubicacion="+ubicacion+"&idformulario="+idformulario+"&idestandarRep="+idestandarRep+"&etiqueta="+etiqueta);
   }

else{
		alert("Complete los datos a Ingresar");
	}
}

function LlenarComboFormulario(idprograma)
{
	var opcion=6;
       //instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Examenes.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idprograma="+idprograma+"&opcion="+opcion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divFormularios').innerHTML = ajax.responseText;
		}
	}	
}

function LlenarComboForm(idprograma)
{
	var opcion=9;
       //instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Examenes.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idprograma="+idprograma+"&opcion="+opcion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divForm').innerHTML = ajax.responseText;
		}
	}	
}


function pedirDatos(idexamen){ //CARGAR DATOS A MODIFICAR
	//donde se mostrar� el formulario con los datos
	divFormulario = document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	
	//uso del medotod POST
	ajax.open("POST", "consulta_Examenes.php");
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
	ajax.send("idexamen="+ idexamen );
	
}

function enviarDatos(){//FUNCION PARA MODIFICAR
	//donde se mostrar� lo resultados
	divResultado = document.getElementById('divinicial');
	divFormulario = document.getElementById('divFrmModificar');
	divNuevo = document.getElementById('divFrmNuevo');

	//valores de los cajas de texto
	idexamen=document.frmModificar.txtidexamen.value;
	nomexamen=document.frmModificar.txtnombreexamen.value;
	idarea=document.frmModificar.cmbArea.value;
	idestandar=document.frmModificar.cmbEstandar.value;	
	plantilla=document.frmModificar.cmbPlantilla.value;	
	observacion=document.frmModificar.txtobservacion.value;
	idestandarRep=document.frmModificar.cmbEstandarRep.value;
	idformulario=document.frmModificar.cmbConForm.value;
	//activo= document.frmModificar.cmbActiva.value;	
        activo="";
        condicion="";
        ubicacion=document.frmModificar.cmbUbicacion.value;
	//alert (activo);
	var opcion=2;	
	Pag=1;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Examenes.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idexamen="+idexamen+"&idarea="+idarea+"&observacion="+escape(observacion)+"&nomexamen="+escape(nomexamen)+"&idestandar="+idestandar+"&Pag="+Pag+"&opcion="+opcion+"&plantilla="+plantilla+"&activo="+activo+"&ubicacion="+ubicacion+"&condicion="+condicion+"&idformulario="+idformulario+"&idestandarRep="+idestandarRep);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			divResultado.style.display="block";
			//mostrar los nuevos registros en esta capa
			//divResultado.innerHTML = ajax.responseText
                          alert(ajax.responseText);
			//una vez actualizacion ocultamos formulario
			divFormulario.style.display="none";
			divNuevo.style.display="block";
                        LimpiarCampos();
			show_event(1);
			//divInicio.style.display="none";
		}
	}	
}

function BuscarDatos()
{
	var opcion=7;
	Pag=1;
    //valores de los cajas de texto
	idarea=document.getElementById('cmbArea').value;
	idexamen=document.getElementById('txtidexamen').value;
	nomexamen=document.getElementById('txtnombreexamen').value;
	plantilla=document.getElementById('cmbPlantilla').value;
	idestandar=document.getElementById('cmbEstandar').value;
	observacion=document.getElementById('txtobservacion').value;
	idestandarRep=document.getElementById('cmbEstandarRep').value;
	idformulario=document.getElementById('cmbFormularios').value;
	etiqueta=document.getElementById('cmbEtiqueta').value;
	//activo= document.getElementById('cmbActiva').value;
         activo="";
         condicion="";
	ubicacion= document.getElementById('cmbUbicacion').value;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Examenes.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idexamen="+idexamen+"&observacion="+escape(observacion)+"&idarea="+idarea+"&nomexamen="+escape(nomexamen)+"&idestandar="+idestandar+"&Pag="+Pag+"&opcion="+opcion+"&plantilla="+plantilla+"&activo="+activo+"&ubicacion="+ubicacion+"&condicion="+condicion+"&idformulario="+idformulario+"&idestandarRep="+idestandarRep);	
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divinicial').innerHTML = ajax.responseText;
		}
	}	
}	


//Funcion para generar código de Examen
function SolicitarUltimoCodigo(idarea){
	
	ajax=objetoAjax();
	var opcion=5;
	idexamen="";
        nomexamen="";
	idestandar="";
	plantilla="";
	Pag="";
	observacion="";
	activo="";
	ubicacion="";
        condicion="";
	ajax.open("POST", "ctrLab_Examenes.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) 
		{
			document.getElementById('divCodigo').innerHTML = ajax.responseText;
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	
	ajax.send("idexamen="+idexamen+"&observacion="+escape(observacion)+"&idarea="+idarea+"&nomexamen="+escape(nomexamen)+"&idestandar="+idestandar+"&Pag="+Pag+"&opcion="+opcion+"&plantilla="+plantilla+"&activo="+activo+"&ubicacion="+ubicacion+"&condicion="+condicion);
	
	}
	
function show_event(Pag)
{
	opcion=4;
	
	ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_Examenes.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		  }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("&Pag="+Pag+"&opcion="+opcion);	
}

function show_event_search(Pag)
{	
	opcion=8;
	 //valores de los cajas de texto
	idarea=document.getElementById('cmbArea').value;
	idexamen=document.getElementById('txtidexamen').value;
	nomexamen=document.getElementById('txtnombreexamen').value;
	plantilla=document.getElementById('cmbPlantilla').value;
	idestandar=document.getElementById('cmbEstandar').value;
	observacion=document.getElementById('txtobservacion').value;
	idestandarRep=document.frmnuevo.cmbEstandarRep.value;
	idformulario=document.frmnuevo.cmbFormularios.value;
	
	//activo= document.getElementById('cmbActiva').value;
         activo="";
         condicion="";
	ubicacion= document.getElementById('cmbUbicacion').value;
	ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_Examenes.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		   
		 }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idexamen="+idexamen+"&observacion="+escape(observacion)+"&idarea="+idarea+"&nomexamen="+escape(nomexamen)+"&idestandar="+idestandar+"&Pag="+Pag+"&opcion="+opcion+"&plantilla="+plantilla+"&activo="+activo+"&ubicacion="+ubicacion+"&condicion="+condicion);	
}	

function Estado(idexamen,condicion){

var opcion=3;
	//alert(idexamen+"-"+condicion);
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Examenes.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
       // alert (idempleado+"-"+EstadoCuenta);
	   alert(idexamen+"-"+condicion);
	ajax.send("idexamen="+idexamen+"&condicion="+condicion+"&opcion="+opcion);

	ajax.onreadystatechange=function() {
		 if(ajax.readyState==1){
                                       
                 }
		 if (ajax.readyState==4) {
		//alert(ajax.responseText);
			show_event(1); 
		 }
	}	
}


