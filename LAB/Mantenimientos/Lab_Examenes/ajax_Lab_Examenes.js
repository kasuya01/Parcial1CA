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
	document.frmnuevo.txtidexamen.value="";
	document.frmnuevo.cmbArea.value="0";
	document.frmnuevo.txtnombreexamen.value="";
	document.frmnuevo.cmbEstandar.value="0";
	document.frmnuevo.cmbPlantilla.value="0";
	//document.frmnuevo.txtobservacion.value="";
        document.frmnuevo.cmbUbicacion.value="0";
	document.frmnuevo.cmbFormularios.value="0";
	document.frmnuevo.cmbEstandarRep.value="0";
        document.frmnuevo.cmbEtiqueta.value="0";
        document.frmnuevo.cmbUrgente.value="0";
        document.frmnuevo.cmbsexo.value="0";
        document.frmnuevo.cmbHabilitar.value="0";
        document.frmnuevo.metodologias_sel.value="";
        document.frmnuevo.text_metodologias_sel.value="";
        document.frmnuevo.id_metodologias_sel.value="";
        
        document.frmnuevo.inidate.valie="0";
	document.frmnuevo.txtnombreexamen.focus();
        
        obj1 = document.frmnuevo.add_metodologia;
            obj1.disabled = true;
}

function ValidarCampos()
{

  var resp = true;
           
	 
                 
          if (document.getElementById('cmbArea').value == "0")
		 {
			resp= false;
                       // alert(" 2 "+(document.getElementById('cmbArea').value));
		 }	        
	 if (document.getElementById('txtnombreexamen').value == "")
		 {
			resp= false;
                        //alert((" 3 "+document.getElementById('txtnombreexamen').value));
		 }
     	
   	if (document.getElementById('cmbPlantilla').value == "0")
		 {
			resp= false;	
                      //alert(" 4 "+(document.getElementById('cmbPlantilla').value));
		 }	
  	/*if (document.getElementById('cmbEstandar').value == "0")
		 {
			resp= false;
                        //alert(" 5 "+(document.getElementById('cmbEstandar').value));
           	 }	*/
        
         if (document.getElementById('cmbUbicacion').value == "")
		 {
			resp= false;	
                         //alert(" 6 "+(document.getElementById('cmbUbicacion').value));
		 }        
       
	if (document.getElementById('cmbEstandarRep').value == "0")
		 {
			resp= false;
                         //alert(" 7 "+(document.getElementById('cmbEstandarRep').value));
		 }
        if (document.getElementById('cmbEtiqueta').value == "0")
		 {
			resp= false;	
                         //alert(" 8 "+(document.getElementById('cmbEtiqueta').value));
		 }
                         
                // alert((document.getElementById('cmbsexo').value)+"  "+(document.getElementById('cmbHabilitar').value)+"  "+ (document.getElementById('txtidexamen').value)+"  "+document.getElementById('cmbArea').value));
         if (document.getElementById('cmbsexo').value == "0")
		 {
			resp= false;
                         //alert(" 10 "+(document.getElementById('cmbsexo').value));
		 }	 
      if (document.getElementById('cmbHabilitar').value == "0")
		 {
			resp= false;	
                       //  alert(" 11 "+(document.getElementById('cmbHabilitar').value));
		 }
                 
                   /* alert((document.getElementById('txtidexamen').value)
                         +" 2 "+(document.getElementById('cmbArea').value)
                         +" 3 "+(document.getElementById('txtnombreexamen').value)
                         +" 4 "+(document.getElementById('cmbPlantilla').value)
                         +" 5 "+(document.getElementById('cmbEstandar').value)
                         +" 6 "+(document.getElementById('cmbEtiqueta').value)
                         +" 7 "+(document.getElementById('cmbUrgente').value)
                         +" 8 "+(document.getElementById('cmbsexo').value)
                         +" 9 "+(document.getElementById('cmbHabilitar').value)+" 10 "+resp);*/
  return resp;
}

function IngresarRegistro(){ //INGRESAR REGISTROS
	//donde se mostrar� lo resultados
	//valores de los inputs
if (ValidarCampos())
{
	idarea=document.frmnuevo.cmbArea.value;
	idexamen=document.frmnuevo.txtidexamen.value;
	nomexamen=document.frmnuevo.txtnombreexamen.value;
	idestandar=document.frmnuevo.cmbEstandar.value;
	plantilla=document.frmnuevo.cmbPlantilla.value;
	//observacion=document.frmnuevo.txtobservacion.value;
	ubicacion=document.frmnuevo.cmbUbicacion.value;
	idestandarRep=document.frmnuevo.cmbEstandarRep.value;
	idformulario=document.frmnuevo.cmbFormularios.value;
	etiqueta=document.frmnuevo.cmbEtiqueta.value;
        urgente=document.frmnuevo.cmbUrgente.value;
	sexo=document.frmnuevo.cmbsexo.value;
        Hab=document.frmnuevo.cmbHabilitar.value;
        tiempoprevio=document.getElementById('inidate').value;
        metodologias_sel=frmnuevo.metodologias_sel.value;
        text_metodologias_sel=frmnuevo.text_metodologias_sel.value;
        id_metodologias_sel=frmnuevo.id_metodologias_sel.value;
      // alert(sexo);
	//codempresa=document.frmnuevo.txttxtcodempresa.value;
	//alert(idestandar);
	//alert (idPrograma+"*****"+idestandarRep);
	
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
        //alert (sexo);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idexamen="+idexamen+"&idarea="+idarea+"&nomexamen="+nomexamen+
	"&idestandar="+idestandar+"&Pag="+Pag+"&opcion="+opcion+"&plantilla="+plantilla+"&ubicacion="+ubicacion+
	"&idformulario="+idformulario+"&idestandarRep="+idestandarRep+"&etiqueta="+etiqueta+"&urgente="+urgente+
        "&sexo="+sexo+"&Hab="+Hab+"&tiempoprevio="+tiempoprevio+"&metodologias_sel="+metodologias_sel+"&text_metodologias_sel="+text_metodologias_sel+"&id_metodologias_sel="+id_metodologias_sel);
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



function LlenarComboFormConsulta(idprograma)
{
	var opcion=9;
       //instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Examenes.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	//alert(idprograma);
	ajax.send("idprograma="+idprograma+"&opcion="+opcion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divFormulariosC').innerHTML = ajax.responseText;
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
	ajax.send("idexamen="+idexamen);
	
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
	//observacion=document.frmModificar.txtobservacion.value;
	idestandarRep=document.frmModificar.cmbEstandarRep.value;
	idformulario=document.frmModificar.cmbConForm.value;
	Etiqueta=document.frmModificar.cmbEtiqueta.value;
	ubicacion=document.frmModificar.cmbUbicacion.value;
        urgente=document.frmModificar.cmbUrgente.value;
        idsexo=document.frmModificar.cmbsexo.value;
        Hab=document.frmModificar.cmbHabilitar.value;
        idconf=document.frmModificar.txtidconf.value;
        ctlidestandar=document.frmModificar.txtctlidestandar.value;
        Tiempo=document.frmModificar.inidate.value;
        metodologias_sel=frmModificar.metodologias_sel.value;
        text_metodologias_sel=frmModificar.text_metodologias_sel.value;
        id_metodologias_sel=frmModificar.id_metodologias_sel.value;
	//alert (text_metodologias_sel);
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
	ajax.send("idexamen="+idexamen+"&idarea="+idarea+"&nomexamen="+nomexamen+
	"&idestandar="+idestandar+"&Pag="+Pag+"&opcion="+opcion+"&plantilla="+plantilla+"&ubicacion="+ubicacion+
	"&idformulario="+idformulario+"&idestandarRep="+idestandarRep+"&Etiqueta="+Etiqueta+"&urgente="+urgente+
        "&idsexo="+idsexo+"&Hab="+Hab+"&Tiempo="+Tiempo+"&idconf="+idconf+"&ctlidestandar="+ctlidestandar+"&metodologias_sel="+metodologias_sel+"&text_metodologias_sel="+text_metodologias_sel+"&id_metodologias_sel="+id_metodologias_sel);
//+"&observacion="+observacion
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			divResultado.style.display="block";
			//mostrar los nuevos registros en esta capa
			//divResultado.innerHTML = ajax.responseText
                        //  alert(ajax.responseText);
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
	//observacion=document.getElementById('txtobservacion').value;
	idestandarRep=document.getElementById('cmbEstandarRep').value;
	idformulario=document.getElementById('cmbFormularios').value;
	etiqueta=document.getElementById('cmbEtiqueta').value;
        sexo=document.getElementById('cmbsexo').value;
        Hab=document.getElementById('cmbHabilitar').value;
	urgente= document.getElementById('cmbUrgente').value;
     // alert(idestandar);
         condicion="";
	ubicacion= document.getElementById('cmbUbicacion').value;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Examenes.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
        //"&idestandar="+idestandar+
        //+"&observacion="+observacion
	ajax.send("idexamen="+idexamen+"&idestandar="+idestandar+"&idarea="+idarea+"&nomexamen="+nomexamen+
	"&Pag="+Pag+"&opcion="+opcion+"&plantilla="+plantilla+"&ubicacion="+ubicacion+
	"&condicion="+condicion+"&idformulario="+idformulario+"&idestandarRep="+idestandarRep+"&etiqueta="+etiqueta+
        "&sexo="+sexo+"&Hab="+Hab+"&urgente="+urgente);	
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
	
    
	ajax.open("POST", "ctrLab_Examenes.php",true);
	ajax.onreadystatechange=function() {
            if (ajax.readyState==4) 
            {
		document.getElementById('divCodigo').innerHTML = ajax.responseText;
            }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	
	ajax.send("&idarea="+idarea+"&opcion="+opcion);
	
	}
        
        function LlenarExamenes(idarea)
{         // alert (idarea);
	var opcion=6;
       //instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_Examenes.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divExamen').innerHTML = ajax.responseText;
                        SolicitarUltimoCodigo(idarea);
		}
	}	
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
	//observacion=document.getElementById('txtobservacion').value;
	idestandarRep=document.getElementById('cmbEstandarRep').value;
	idformulario=document.getElementById('cmbFormularios').value;
        etiqueta=document.getElementById('cmbEtiqueta').value;
        sexo=document.getElementById('cmbsexo').value;
        Hab=document.getElementById('cmbHabilitar').value;
	urgente= document.getElementById('cmbUrgente').value;
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
	ajax.send("idexamen="+idexamen+"&idarea="+idarea+"&nomexamen="+nomexamen+
	"&idestandar="+idestandar+"&Pag="+Pag+"&opcion="+opcion+"&plantilla="+plantilla+"&ubicacion="+ubicacion+
	"&condicion="+condicion+"&idformulario="+idformulario+"&idestandarRep="+idestandarRep+"&etiqueta="+etiqueta+
        "&sexo="+sexo+"&Hab="+Hab+"&urgente="+urgente);	
//+"&observacion="+observacion
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
      // alert(idexamen+"-"+condicion);
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

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
 
            return true;
}   


