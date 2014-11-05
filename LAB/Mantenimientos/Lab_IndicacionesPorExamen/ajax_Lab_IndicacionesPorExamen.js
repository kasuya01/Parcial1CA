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
    idexamen="";
	idindicacion="";
	indicacion="";
	Pag="";
	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_IndicacionesPorExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&idindicacion="+idindicacion+"&Pag="+Pag+"&indicacion="+escape(indicacion));
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divExamen').innerHTML = ajax.responseText;
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
	indicacion=document.getElementById('txtindicacion').value;	
	idindicacion="";
	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_IndicacionesPorExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&idindicacion="+idindicacion+"&Pag="+Pag+"&indicacion="+escape(indicacion));
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divinicial').innerHTML = ajax.responseText;
			//alert(ajax.responseText);
		}
	}	
}

function LimpiarCampos(){
        document.frmnuevo.cmbArea.value=0;	
        document.frmnuevo.cmbExamen.value=0;
        document.frmnuevo.txtindicacion.value="";
       	document.frmnuevo.txtindicacion.focus();
}

function IngresarRegistro(){ //INGRESAR REGISTROS
	//donde se mostrar� lo resultados
	//valores de los inputs
	idarea=document.getElementById('cmbArea').value;
	idexamen=document.getElementById('cmbExamen').value
	indicacion=document.getElementById('txtindicacion').value;
	var opcion=1;
	Pag=1;
	idindicacion="";
	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion
	ajax.open("POST", "ctrLab_IndicacionesPorExamen.php",true);
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
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&idindicacion="+idindicacion+"&Pag="+Pag+"&indicacion="+escape(indicacion));
}

function pedirDatos(idindicacion){ //CARGAR DATOS A MODIFICAR
	//donde se mostrar� el formulario con los datos
	divFormulario = document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//uso del medotod POST
	ajax.open("POST", "consulta_IndicacionesPorExamen.php");
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
	ajax.send("idindicacion="+ idindicacion );
	
}

function enviarDatos(){//FUNCION PARA MODIFICAR
	//donde se mostrar� lo resultados
	divResultado = document.getElementById('divinicial');
	divFormulario = document.getElementById('divFrmModificar');
	divNuevo = document.getElementById('divFrmNuevo');
	
	//valores de los cajas de texto
	idindicacion=document.frmModificar.txtidindicacion.value;
	idarea=document.frmModificar.cmbArea.value;
	idexamen=document.frmModificar.cmbExamen.value;	
	indicacion=document.frmModificar.txtindicacion.value;
	var opcion=2;	
	Pag=1;
	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_IndicacionesPorExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&idindicacion="+idindicacion+"&Pag="+Pag+"&indicacion="+escape(indicacion));
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
      
function eliminarDato(idexamen){ //FUNCION PARA ELIMINACION
	//donde se mostrar� el resultado de la eliminacion
	divResultado = document.getElementById('divinicial');
	//divInicio=document.getElementById('divinicial');
	var opcion=3;
	Pag=1;
	
	idarea="";
	idindicacion="";
	indicacion="";
	
	//usaremos un cuadro de confirmacion	
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if ( eliminar ) {
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		ajax.open("POST", "ctrLab_IndicacionesPorExamen.php",true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&idindicacion="+idindicacion+"&Pag="+Pag+"&indicacion="+escape(indicacion));
		
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

function MostraFormularioNuevo()
{
   var opcion=6;
    idexamen="";
	idarea="";
	idindicacion="";
	indicacion="";
	Pag="";
	  
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_IndicacionesPorExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&idindicacion="+idindicacion+"&Pag="+Pag+"&indicacion="+escape(indicacion));
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divFrmNuevo').style.display="block";
			document.getElementById('divFrmNuevo').innerHTML = ajax.responseText;
			document.getElementById('divFrmModificar').style.display="none";
			//document.getElementById('divinicial').style.display="none";
		}
	}	
}

/*function SolicitarUltimoCodigo(idarea){
	ajax=objetoAjax();
	var opcion=5;
	idexamen="";
	idindicacion="";
	indicacion="";
	Pag="";
	
	ajax.open("POST", "ctrLab_IndicacionesPorExamen.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) 
		{
			document.getElementById('divCodigo').innerHTML = ajax.responseText;
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&idindicacion="+idindicacion+"&Pag="+Pag+"&indicacion="+escape(indicacion));
	}*/
	
function show_event(Pag)
{	opcion=4;
	ajax=objetoAjax();
	idexamen="";
	idarea="";
	idindicacion="";
	indicacion="";
		
	ajax.open("POST", 'ctrLab_IndicacionesPorExamen.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		}
	}
	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&idindicacion="+idindicacion+"&Pag="+Pag+"&indicacion="+escape(indicacion));
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
	indicacion=document.getElementById('txtindicacion').value;	
	idindicacion="";
	
    ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_IndicacionesPorExamen.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		   document.getElementById('divinicial').style.display="block";
		  // alert(ajax.responseText);
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//ajax.send("Pag="+Pag+"&opcion="+opcion);	
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&idindicacion="+idindicacion+"&Pag="+Pag+"&indicacion="+escape(indicacion));
}	


