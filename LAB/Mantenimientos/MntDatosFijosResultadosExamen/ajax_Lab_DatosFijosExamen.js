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
{         // alert (idarea);
	var opcion=5;
       //instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_DatosFijosExamen.php",true);
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


function Estado(idatofijo){

var opcion=9;
	//alert(idexamen+"-"+condicion);
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_DatosFijosExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
    // alert(idatofijo);
	ajax.send("idatofijo="+idatofijo+"&opcion="+opcion);

	ajax.onreadystatechange=function() {
		 if(ajax.readyState==1){
                    // alert("if 1");
                                       
                 }
		 if (ajax.readyState==4) {
		//alert(ajax.responseText);
                //alert("if2");
			show_event(1); 
                       
		 }
	}	
}

function show_event(Pag)
{
	opcion=4;
	
	ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_DatosFijosExamen.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		  }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("&Pag="+Pag+"&opcion="+opcion);	
}



function LimpiarCampos(){
	document.getElementById('cmbArea').value="0";
	document.getElementById('cmbExamen').value="0";
        document.getElementById('cmbSexo').value="0";
        document.getElementById('cmbEdad').value="0";
	document.getElementById('txtunidades').value="";
	document.getElementById('txtrangoinicio').value="";
	document.getElementById('txtrangofin').value="";
	document.getElementById('txtnota').value="";
	document.getElementById('txtFechaFin').value="";
	document.getElementById('txtFechainicio').value="";
}

function IngresarRegistro(){ //INGRESAR REGISTROS
	//donde se mostrar� lo resultados
	//valores de los inputs
	idarea=document.getElementById('cmbArea').value;
	idexamen=document.getElementById('cmbExamen').value;
        sexo=document.getElementById('cmbSexo').value;
        redad=document.getElementById('cmbEdad').value;
	unidades=document.getElementById('txtunidades').value;
	rangoinicio=document.getElementById('txtrangoinicio').value;
	rangofin=document.getElementById('txtrangofin').value;
	Fechaini=document.getElementById('txtFechainicio').value;
	Fechafin=document.getElementById('txtFechaFin').value;	
	nota=document.getElementById('txtnota').value;
	//alert (sexo+"--"+redad);
	var opcion=1;
	Pag=1;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion
	ajax.open("POST", "ctrLab_DatosFijosExamen.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
		
        alert(ajax.responseText);
			LimpiarCampos();
			show_event(Pag);
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+
        "&unidades="+escape(unidades)+"&Pag="+Pag+"&rangoinicio="+rangoinicio+
        "&rangofin="+rangofin+"&nota="+escape(nota)+"&Fechaini="+Fechaini+
        "&Fechafin="+Fechafin+"&sexo="+sexo+"&redad="+redad);
}


function pedirDatos(iddatosfijosexamen){ //CARGAR DATOS A MODIFICAR
	//donde se mostrar� el formulario con los datos
	divFormulario = document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//uso del medotod POST
	ajax.open("POST", "consulta_DatosFijosExamen.php");
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
	//enviando el codigo f
	//alert(iddatosfijosexamen);
	ajax.send("iddatosfijosexamen="+ iddatosfijosexamen );
	
}

function enviarDatos(){//FUNCION PARA MODIFICAR
	//donde se mostrar� lo resultados
	divResultado = document.getElementById('divinicial');
	divFormulario = document.getElementById('divFrmModificar');
	divNuevo = document.getElementById('divFrmNuevo');
	//valores de los cajas de texto
	unidades=document.frmModificar.txtunidades.value;
	idarea=document.frmModificar.cmbArea.value;
	idexamen=document.frmModificar.cmbExamen.value;	
	rangoinicio=document.frmModificar.txtrangoinicio.value;
	rangofin=document.frmModificar.txtrangofin.value;
	nota=document.frmModificar.txtnota.value;
	Fechaini=document.frmModificar.txtFechainicio.value;
	Fechafin=document.frmModificar.txtFechaFin.value;	
	iddatosfijosexamen=document.frmModificar.txtoculto.value;
      
        sexo=document.frmModificar.cmbSexo.value;
        //  alert (sexo);
        redad=document.frmModificar.cmbEdad.value;
	var opcion=2;	
	Pag=1;
	//alert(Fechaini);
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_DatosFijosExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&unidades="+escape(unidades)+
                "&Pag="+Pag+"&rangoinicio="+rangoinicio+"&rangofin="+rangofin+"&nota="+escape(nota)+
                "&iddatosfijosexamen="+iddatosfijosexamen+"&Fechaini="+Fechaini+"&Fechafin="+Fechafin+
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
			//LimpiarCampos();
			show_event(1);


		}
	}	
}

function eliminarDato(iddatosfijosresultado){ //FUNCION PARA ELIMINACION
	//donde se mostrar� el resultado de la eliminacion
	divResultado = document.getElementById('divinicial');
	var opcion=3;
	Pag=1;
	
	//usaremos un cuadro de confirmacion	
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if ( eliminar ) {
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		ajax.open("POST", "ctrLab_DatosFijosExamen.php",true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("opcion="+opcion+"&iddatosfijosresultado="+iddatosfijosresultado+"&Pag="+Pag);
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
	unidades=document.getElementById('txtunidades').value;
	rangoinicio=document.getElementById('txtrangoinicio').value;
	rangofin=document.getElementById('txtrangofin').value;
	nota=document.getElementById('txtnota').value;
	Fechaini=document.getElementById('txtFechainicio').value;
	Fechafin=document.getElementById('txtFechaFin').value;	
	sexo=document.getElementById('cmbSexo').value;
        redad=document.getElementById('cmbEdad').value;
	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_DatosFijosExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&unidades="+escape(unidades)+
        "&Pag="+Pag+"&rangoinicio="+rangoinicio+"&rangofin="+rangofin+"&nota="+escape(nota)+
        "&Fechaini="+Fechaini+"&Fechafin="+Fechafin+"&sexo="+sexo+"&redad="+redad);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divinicial').innerHTML = ajax.responseText;
			//alert(ajax.responseText);
		}
	}	
}

function MostraFormularioNuevo()
{
   var opcion=6;
   
	  
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_IndicacionesPorExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("opcion="+opcion);
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

function show_event(Pag)
{	opcion=4;
	ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_DatosFijosExamen.php', true);
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
	unidades=document.getElementById('txtunidades').value;	
	rangoinicio=document.getElementById('txtrangoinicio').value;
	rangofin=document.getElementById('txtrangofin').value;
	nota=document.getElementById('txtnota').value;
	Fechaini=document.getElementById('txtFechainicio').value;
	Fechafin=document.getElementById('txtFechaFin').value;	
	sexo=document.getElementById('cmbSexo').value;
        redad=document.getElementById('cmbEdad').value;
    ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_DatosFijosExamen.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		   document.getElementById('divinicial').style.display="block";
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&unidades="+escape(unidades)+
                "&Pag="+Pag+"&rangoinicio="+rangoinicio+"&rangofin="+rangofin+"&nota="+escape(nota)+
                "&sexo="+sexo+"&redad="+redad);
}	


