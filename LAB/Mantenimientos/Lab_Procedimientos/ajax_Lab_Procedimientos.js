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


function LlenarRango(idexa)
{
	var opcion=11;
   
	 //instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Procedimientos.php",true);
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

/*function Estado(idlppe,condicion){

var opcion=9;
	//alert(idexamen+"-"+condicion);
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Procedimientos.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
      // alert(idlppe+"-"+condicion);
	ajax.send("idlppe="+idlppe+"&condicion="+condicion+"&opcion="+opcion);

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
}*/
function show_event(Pag)
{
	opcion=4;
	
	ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_Procedimientos.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		  }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("&Pag="+Pag+"&opcion="+opcion);	
}

function MostrarSubElementos1()
{ 
  idelemento=document.frmModificar.idelemento.value;
  
  elemento=document.frmModificar.txtelemento.value;
  //alert(idelemento+"---"+elemento);
  ventana_secundaria = window.open("MntSubElementosExamen1.php?var1="+idelemento+
			"&var2="+escape(elemento),"Resultados","width=1100,height=900,menubar=no,scrollbars=yes") ;
   
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
                          if (document.getElementById('cmborden').value === "0") {
					error.push('Rango');
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
			} if (document.frmModificar.cmborden.value === "0") {
					error.push('Rango');
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
        document.getElementById('cmborden').value="0";
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

function enviarasubelemntos(){
    
	ajax.open("POST", "consulta_SubElemento.php",true);
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
        cmborden=document.getElementById('cmborden').value;
      //  alert(sexo);
		var opcion=1;
		Pag=1;
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//archivo que realizar� la operacion
		ajax.open("POST", "ctrLab_Procedimientos.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				//mostrar resultados en esta capa
				document.getElementById('divinicial').innerHTML = ajax.responseText;
	                        alert(ajax.responseText);
				LimpiarCampos();
				 show_event(1);
                                 ventanasecundaria();
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&proce="+escape(proce)+
	                "&unidades="+escape(unidades)+"&Pag="+Pag+"&rangoini="+rangoini+"&rangofin="+rangofin+
	                "&Fechaini="+Fechaini+"&Fechafin="+Fechafin+"&sexo="+sexo+"&redad="+redad+"&cmborden="+cmborden);
  	} else {
  		alert(datosCompletos['errorMessage']);
	}

}

function ventanasecundaria(){


                mismo=document.getElementById('mismo').value;
		idexamen=document.getElementById('cmbExamen').value	
ventana_secundaria = window.open("consulta_SubElemento1.php?var1="+mismo+
									  "&var2="+idexamen,"solicitud",										"width=800,height=700,menubar=no,location=no,scrollbars=yes") 
/*ajax.open("POST", "consulta_SubElemento1.php");
		ajax.send("mismo="+ mismo);	*/							 
}
function Cerrar(){
	//window.opener.location.href = window.opener.location.href;
    window.close();
}

function pedirDatos(idproce){ //CARGAR DATOS A MODIFICAR
	//donde se mostrar� el formulario con los datos
	divFormulario=document.getElementById('divFrmModificar');
	divFormularioNuevo=document.getElementById('divFrmNuevo');
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//alert(idproce);
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
function show_subelemento(Pag,idelemento)
{
	opcion=4;
	idsubelemento="";
	subelemento="";
	elemento="";
	unidad="";
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
                 cmborden=document.frmModificar.cmborden.value;
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
	                "&sexo="+sexo+"&redad="+redad+"&cmborden="+cmborden);
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

