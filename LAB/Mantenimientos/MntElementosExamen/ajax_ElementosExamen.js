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

function ValidarCampos()
{  var resp = true;
	 if (document.getElementById('cmbExamen').value == "0")
		 {
			resp= false;		
		 }
	 if (document.getElementById('cmbArea').value == "0")
		 {
			resp= false;		
		 }	
   if (document.getElementById('txtelemento').value == "")
		 {
			resp= false;		
		 }	
   if (document.getElementById('cmbSubElementos').value == "0")
		 {
			resp= false;		
		 }			 
  return resp;
}

function ValidarCamposSubElemento()
{
     var resp = true;
	 if (document.getElementById('txtsubelemento').value == "")
		 {
			resp= false;		
		 }
	 return resp;
}

function LimpiarCampos(){
	document.getElementById('cmbExamen').value="0";
	document.frmnuevo.cmbArea.value="0";
	document.frmnuevo.txtelemento.value="";
	document.frmnuevo.cmbSubElementos.value="0";
	document.frmnuevo.txtunidadele.value="";
	document.frmnuevo.txtobservacionele.value="";
	document.frmnuevo.txtFechainicio.value="";
	document.frmnuevo.txtFechaFin.value="";
         document.frmnuevo.cmborden.value="0";
	
}

function LimpiarSubElementos(){
	document.frmnuevo.txtsubelemento.value="";
	document.frmnuevo.txtunidad.value="";
	document.frmnuevo.txtFechainicio.value="";
	document.frmnuevo.txtFechaFin.value="";
        document.frmnuevo.txtrangoini.value="";
        document.frmnuevo.txtrangofin.value="";
        document.frmnuevo.cmbSexo.value="0";
        document.frmnuevo.cmbEdad.value="0"; 
        //document.frmnuevo.cmborden.value="0";
	document.frmnuevo.txtsubelemento.focus();
	
}

function Cerrar(){
  windows.close;
}

function Cancelar()
{
    show_subelemento(1,idelemento);
    LimpiarSubElementos()
}

function IngresarRegistro(){ //INGRESAR REGISTROS DE ELEMENTOS
	//donde se mostrar� lo resultados
	//valores de los inputs
	if (ValidarCampos())
	{
		idarea=document.frmnuevo.cmbArea.value;
		idexamen=document.frmnuevo.cmbExamen.value;
		elemento=document.frmnuevo.txtelemento.value;
		subelemento=document.frmnuevo.cmbSubElementos.value;
                //rangoini=document.frmnuevo.txtrangoini.value;
               // rangofin=document.frmnuevo.txtrangofin.value;
		observacionele=document.frmnuevo.txtobservacionele.value;
		unidadele=document.frmnuevo.txtunidadele.value;
		Fechaini=document.frmnuevo.txtFechainicio.value;
		Fechafin=document.frmnuevo.txtFechaFin.value;
                orden=document.frmnuevo.cmborden.value;
		//alert( rangoini+"***"+Fechafin);
		var opcion=1;
		Pag=1;
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//archivo que realizar� la operacion
		ajax.open("POST", "ctrElementosExamen.php",true);
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
	ajax.send("idexamen="+idexamen+"&idarea="+idarea+"&elemento="+escape(elemento)+
        "&subelemento="+subelemento+"&observacionele="+escape(observacionele)+"&unidadele="+escape(unidadele)+
        "&Pag="+Pag+"&opcion="+opcion+"&Fechaini="+Fechaini+"&Fechafin="+Fechafin+"&orden="+orden);
   }
   else{
		alert("Complete los datos a Ingresar");
	}
}

function IngresarRegistroSubElemento()
{
   if (ValidarCamposSubElemento())
   {
	//valores de los cajas de texto
	idelemento=document.frmnuevo.idelemento.value;
	elemento=document.frmnuevo.txtelemento.value;
	idsubelemento="";	
	subelemento=document.frmnuevo.txtsubelemento.value;
	unidad=document.frmnuevo.txtunidad.value;
        rangoini=document.frmnuevo.txtrangoini.value;
	rangofin=document.frmnuevo.txtrangofin.value;
	Fechaini=document.frmnuevo.txtFechainicio.value;
	Fechafin=document.frmnuevo.txtFechaFin.value;
        sexo=document.frmnuevo.cmbSexo.value;
        redad=document.frmnuevo.cmbEdad.value;
        orden=document.frmnuevo.cmborden.value;
        //alert(rangoini);
	var opcion=1;
	Pag=1;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion
	ajax.open("POST", "ctrSubElementosExamen.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			alert(ajax.responseText);
			LimpiarSubElementos();
            show_subelemento(Pag,idelemento);
		}
	}
	//alert (Fechaini+"--"+Fechafin);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("subelemento="+escape(subelemento)+"&unidad="+unidad+
        "&rangoini="+rangoini+"&rangofin="+rangofin+"&idelemento="+idelemento+"&elemento="+elemento+
        "&Pag="+Pag+"&opcion="+opcion+"&Fechaini="+Fechaini+"&Fechafin="+Fechafin+"&sexo="+sexo+"&redad="+redad+"&orden="+orden);	
   }

  else{
		alert("Complete los datos a Ingresar");
	}
}

function pedirDatos(idelemento){ //CARGAR DATOS A MODIFICAR
	//donde se mostrar� el formulario con los datos
	divFormulario = document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//uso del medotod POST
	ajax.open("POST", "consulta_ElementosExamen.php");
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
	ajax.send("idelemento="+idelemento);
	
}

function pedirDatosSubElementos(idsubelemento)
{ //CARGAR DATOS A MODIFICAR SUBELEMENTOS
	//donde se mostrar� el formulario con los datos
	divFormulario = document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
	opcion=5;
	Pag=1;
	
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//uso del medotod POST
	ajax.open("POST", "ctrSubElementosExamen.php");
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
	ajax.send("idsubelemento="+idsubelemento+"&Pag="+Pag+"&opcion="+opcion);	
}

//FUNCION PARA MODIFICAR ELEMENTO DE EXAMEN
function enviarDatos(){
	//donde se mostrar� lo resultados
	divResultado = document.getElementById('divinicial');
	divFormulario = document.getElementById('divFrmModificar');
	divNuevo = document.getElementById('divFrmNuevo');

	//valores de los cajas de texto
	idelemento=document.frmModificar.idelemento.value;
	elemento=document.frmModificar.txtelemento.value;
	idarea=document.frmModificar.cmbArea.value;
	idexamen=document.frmModificar.cmbExamen.value;	
	subelemento=document.frmModificar.cmbSubElementos.value;
	observacionele=document.frmModificar.txtobservacionele.value;
	unidadele=document.frmModificar.txtunidadele.value;
	Fechaini=document.frmModificar.txtFechainicio.value;
	Fechafin=document.frmModificar.txtFechaFin.value;
        orden=document.frmModificar.cmborden.value;
    //alert(Fechaini+"***"+Fechafin);
	
	var opcion=2;	
	Pag=1;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrElementosExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idexamen="+idexamen+"&idarea="+idarea+"&idelemento="+idelemento+"&elemento="+escape(elemento)+"&subelemento="+subelemento+"&observacionele="
	+escape(observacionele)+"&unidadele="+escape(unidadele)+"&Pag="+Pag+"&opcion="+opcion+"&Fechaini="+Fechaini+"&Fechafin="+Fechafin+"&orden="+orden);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			divResultado.style.display="block";
			//mostrar los nuevos registros en esta capa
			//divResultado.innerHTML =ajax.responseText
                         alert(ajax.responseText);
			//una vez actualizacion ocultamos formulario
			divFormulario.style.display="none";
			divNuevo.style.display="block";
			//divInicio.style.display="none";
                        LimpiarCampos();
						show_event(1);
		}
	}	
}

function enviarDatosSubElemento()
{
	//donde se mostrar lo resultados
	divResultado = document.getElementById('divinicial');
	divFormulario = document.getElementById('divFrmModificar');
	divNuevo = document.getElementById('divFrmNuevo');
	//valores de los cajas de texto
	idelemento=document.frmModificar.idelemento.value;
	elemento=document.frmModificar.txtelemento.value;
	idsubelemento=document.frmModificar.idsubelemento.value;	
	subelemento=document.frmModificar.txtsubelemento.value;
        rangoini=document.frmModificar.txtrangoini.value;
	rangofin=document.frmModificar.txtrangofin.value;
	unidad=document.frmModificar.txtunidad.value;
	Fechaini=document.frmModificar.txtFechainicio.value;
	Fechafin=document.frmModificar.txtFechaFin.value;
        sexo=document.frmModificar.cmbSexo.value;
        redad=document.frmModificar.cmbEdad.value;
        orden=document.frmModificar.cmborden.value;
       // alert("Rango fin="+rangofin);
	var opcion=2;	
	Pag=1;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrSubElementosExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
        //alert(rangoini+"-"+rangofin+"-"+sexo+"-"+redad);
	ajax.send("idsubelemento="+idsubelemento+"&subelemento="+escape(subelemento)+"&unidad="+unidad+
        "&rangoini="+rangoini+"&rangofin="+rangofin+"&idelemento="+idelemento+"&elemento="+escape(elemento)+
        "&Pag="+Pag+"&opcion="+opcion+"&Fechaini="+Fechaini+"&Fechafin="+Fechafin+"&sexo="+sexo+"&redad="+redad+"&orden="+orden);	
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			divResultado.style.display="block";
			//mostrar los nuevos registros en esta capa
			 alert(ajax.responseText);
			//una vez actualizacion ocultamos formulario
			divFormulario.style.display="none";
			divNuevo.style.display="block";
			//divInicio.style.display="none";
             LimpiarSubElementos();
             show_subelemento(Pag,idelemento);
		}
	}	
}

function eliminarDato(idelemento){ //FUNCION PARA ELIMINACION
	//donde se mostrar� el resultado de la eliminacion
	divResultado = document.getElementById('divinicial');
	//divInicio=document.getElementById('divinicial');
	var opcion=3;
	Pag=1;
	idexamen="";	
	idarea="";
	elemento="";
	subelemento="";
	observacionele="";
	unidadele="";
	
	//usaremos un cuadro de confirmacion	
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if ( eliminar ) {
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		ajax.open("POST", "ctrElementosExamen.php",true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idexamen="+idexamen+"&idarea="+idarea+"&idelemento="+idelemento+"&elemento="+escape(elemento)+"&subelemento="+subelemento+"&observacionele="+escape(observacionele)+"&unidadele="+escape(unidadele)+"&Pag="+Pag+"&opcion="+opcion);
		
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				//mostrar resultados en esta capa
			       alert(ajax.responseText);
				   show_event(1);	
				 LimpiarCampos();
				
			}
		}

	}
}

function eliminarDatoSubElemento(idsubelemento){ //FUNCION PARA ELIMINACION
	//donde se mostrar� el resultado de la eliminacion
	divResultado = document.getElementById('divinicial');
	//divInicio=document.getElementById('divinicial');
	var opcion=3;
	Pag=1;
	//idelemento="";
	elemento="";
	subelemento="";
	unidad="";
	
	//usaremos un cuadro de confirmacion	
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if ( eliminar ) {
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		ajax.open("POST", "ctrSubElementosExamen.php",true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		
		//ajax.send("idsubelemento="+idSubelemento+"&idelemento="+idelemento+"&Pag="+Pag+"&opcion="+opcion);	
		ajax.send("idsubelemento="+idsubelemento+"&subelemento="+escape(subelemento)+"&unidad="+escape(unidad)+"&idelemento="+idelemento+"&elemento="+escape(elemento)+"&Pag="+Pag+"&opcion="+opcion);	
		
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				//mostrar resultados en esta capa
				//divResultado.innerHTML = ajax.responseText;
                                 alert(ajax.responseText);
				//divInicio.style.display="none";
				 show_subelemento(Pag,idelemento);
			}
		}

	}
}

function show_event(Pag)
{
	opcion=4;
		
	ajax=objetoAjax();
	ajax.open("POST", 'ctrElementosExamen.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = unescape(ajax.responseText);
		  }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("Pag="+Pag+"&opcion="+opcion);	
}

function show_subelemento(Pag,idelemento)
{ //alert(idelemento);
	opcion=4;
	idsubelemento="";
	subelemento="";
	elemento="";
	unidad="";
       // alert(idelemento);
	//alert(idelemento);
	ajax=objetoAjax();
	ajax.open("POST", 'ctrSubElementosExamen.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = unescape(ajax.responseText);
		  }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("&idelemento="+idelemento+"&Pag="+Pag+"&opcion="+opcion);	
}


function show_event_search(Pag)
{	
	opcion=8;
	idarea=document.getElementById('cmbArea').value;
	idexamen=document.getElementById('cmbExamen').value;
	elemento=document.getElementById('txtelemento').value;
	subelemento=document.getElementById('cmbSubElementos').value;
	observacionele=document.getElementById('txtobservacionele').value;
	unidadele=document.getElementById('txtunidadele').value;
	Fechaini=document.getElementById('txtFechainicio').value;
	Fechafin=document.getElementById('txtFechaFin').value;	
	idelemento="";
	//idestandar=document.getElementById('cmbEstandar').value;
	ajax=objetoAjax();
	ajax.open("POST", 'ctrElementosExamen.php', true);
	ajax.onreadystatechange = function(){ 
            if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = unescape(ajax.responseText);
		   
            }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idexamen="+idexamen+"&idarea="+idarea+"&idelemento="+idelemento+"&subelemento="+subelemento+"&elemento="+escape(elemento)
	+"&observacionele="+observacionele+"&unidadele="+escape(unidadele)+"&Pag="+Pag+"&opcion="+opcion+"&Fechaini="+Fechaini+"&Fechafin="+Fechafin);	
}	



function MostrarSubElementos()
{
  idelemento=document.frmModificar.idelemento.value;
  //alert(idelemento);
  elemento=document.frmModificar.txtelemento.value;

  examen=document.frmModificar.txtexamen.value;
  idexamen=document.frmModificar.idexamen.value;
  cod=document.frmModificar.cod.value;
  //alert(idexamen);
  ventana_secundaria = window.open("MntSubElementosExamen.php?var1="+idelemento+
    "&var2="+escape(elemento)+"&var3="+examen+"&var4="+idexamen+"&var5="+cod,"Resultados","width=1100,height=900,menubar=no,scrollbars=yes") ;
   
}

function LlenarRangoSub(idelemento)
{
	var opcion=11;
   
	 //instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrSubElementosExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idelemento="+idelemento+"&opcion="+opcion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divRango').innerHTML = ajax.responseText;
		}
	}	
}

function LlenarRango(idexa)
{
	var opcion=11;
   
	 //instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrElementosExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idexa="+idexa+"&opcion="+opcion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divRango').innerHTML = ajax.responseText;
		}
	}	
}

function MostrarFormularioNuevo()
{  var opcion=6;
	idarea="";
	idexamen="";
	elemento="";
	subelemento="";
	idelemento="";
	observacionele="";
	unidadele="";

	Pag="";
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrElementosExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("opcion="+opcion);
	
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
		//	mostrar los nuevos registros en esta capa
			//document.getElementById('divFrmNuevo').style.display="block";
			document.getElementById('divExamen').innerHTML = ajax.responseText;
			//document.getElementById('divFrmModificar').style.display="none";
			
		}
	}	
}


function MostrarFormularioNuevoSub()
{  var opcion=6;
	
	idelemento="";
	elemento="";
	idsubelemento="";	
	subelemento="";
	unidad="";
	Pag="";
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizara la operacion ->actualizacion.php
	ajax.open("POST", "ctrElementosExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idexamen="+idexamen+"&opcion="+opcion);
	
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
		//	mostrar los nuevos registros en esta capa
			document.getElementById('divFrmNuevo').style.display="block";
			document.getElementById('divFrmNuevo').innerHTML = ajax.responseText;
			document.getElementById('divFrmModificar').style.display="none";
			
		}
	}	
}

function SolicitarUltimoCodigo(idarea){
	
	ajax=objetoAjax();
	var opcion=5;
	idexamen="";
        nomexamen="";
	idestandar="";
	plantilla="";
	Pag="";
	ajax.open("POST", "ctrElementosExamen.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) 
		{
			document.getElementById('divCodigo').innerHTML = ajax.responseText;
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	
	ajax.send("idarea="+idarea+"&opcion="+opcion);
	
	}
	
function MostrarExamenes(idarea)
{
	
	ajax=objetoAjax();
	var opcion=9;
	idexamen="";
        idelemento="";
	subelemento="";
	elemento="";
	observacionele="";
	unidadele="";
	Pag="";
	ajax.open("POST", 'ctrElementosExamen.php', true);
	ajax.onreadystatechange = function()
	{ 	if (ajax.readyState==4) 
		{  document.getElementById('divExamen').innerHTML = ajax.responseText;
		  
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idarea="+idarea+"&opcion="+opcion);	

}

function BuscarDatos()
{
var opcion=7;
var	Pag=1;
	
    //valores de los cajas de texto
	idarea=document.getElementById('cmbArea').value;
	idexamen=document.getElementById('cmbExamen').value;
	idelemento="";
	elemento=document.getElementById('txtelemento').value;
	subelemento=document.getElementById('cmbSubElementos').value;
	observacionele=document.getElementById('txtobservacionele').value;
	unidadele=document.getElementById('txtunidadele').value;
	Fechaini=document.getElementById('txtFechainicio').value;
	Fechafin=document.getElementById('txtFechaFin').value;		
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrElementosExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	//ajax.send("idexamen="+idexamen+"&idarea="+idarea+"&nomexamen="+nomexamen+"&idestandar="+idestandar+"&Pag="+Pag+"&opcion="+opcion+"&plantilla="+plantilla);	
	ajax.send("idexamen="+idexamen+"&idarea="+idarea+"&idelemento="+idelemento+"&elemento="+escape(elemento)+"&subelemento="+escape(subelemento)+"&unidadele="+escape(unidadele)+"&observacionele="+escape(observacionele)+"&Pag="+Pag+"&opcion="+opcion+"&Fechaini="+Fechaini+"&Fechafin="+Fechafin);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divinicial').innerHTML = ajax.responseText;
		}
	}	
}	


