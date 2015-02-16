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

//Fn Pg

function show_event(Pag)
{
	opcion=4;
	
	ajax=objetoAjax();
	ajax.open("POST", 'ctrLab_metodologia.php', true);
	ajax.onreadystatechange = function(){ 
	if (ajax.readyState==4) {
		   //mostrar resultados en esta capa
		   document.getElementById('divinicial').innerHTML = ajax.responseText;
		  }
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("&Pag="+Pag+"&opcion="+opcion);	
}

//Fn Pg
function LlenarExamenes(idarea)
{         // alert (idarea);
	var opcion=5;
       //instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_metodologia.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
                  //mostrar los nuevos registros en esta capa
                  document.getElementById('divExamen').innerHTML = ajax.responseText;
//                  document.getElementById('cmbMetodologia').value=0;
//                  document.getElementById('cmbreporta').value=0;
                   
                   buscaranteriores()
                        
		}
	}	
}
//fn pg

function buscaranteriores(){ //INGRESAR REGISTROS
	//donde se mostrar� lo resultados
	//valores de los inputs
	idarea=document.getElementById('cmbArea').value;
	idexamen=document.getElementById('cmbExamen').value;
        //alert (idexamen)
          //  alert('iadrea: '+idarea+' /idexa:  '+idexamen+' /sexo:  '+sexo+'/edad:'+redad)
            var opcion=10;
            Pag=1;
            //instanciamos el objetoAjax
            ajax=objetoAjax();
            //archivo que realizar� la operacion
            ajax.open("POST", "ctrLab_metodologia.php",true);
            ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
                       // alert (ajax.responseText)
                  document.getElementById('divMetodo').innerHTML = ajax.responseText;
                 // if (document.getElementById('cmbMetodologia').value!=0){
                     buscareporta()
//                  }
//                  else
//                     $("#cmbreporta option[value='0']").attr('selected', 'selected');
		}
	}
            ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            //enviando los valores
            ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen);
        
        return false;
	//alert (sexo+"--"+redad);
	
}
//fn pg

function buscareporta(){ //INGRESAR REGISTROS
	//donde se mostrar� lo resultados
	//valores de los inputs
	idexamen=document.getElementById('cmbExamen').value;
	idmetodologia=document.getElementById('cmbMetodologia').value;
      //  alert (idmetodologia)
          //  alert('iadrea: '+idarea+' /idexa:  '+idexamen+' /sexo:  '+sexo+'/edad:'+redad)
            var opcion=11;
            Pag=1;
            //instanciamos el objetoAjax
            ajax=objetoAjax();
            //archivo que realizar� la operacion
            ajax.open("POST", "ctrLab_metodologia.php",true);
            ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
                       // alert (ajax.responseText)
                  document.getElementById('divReporta').innerHTML = ajax.responseText;
                  if (document.getElementById('cmbreporta')!=0){
                   document.frmnuevo.add_posresultado.disabled = false;
                   buscaposresultprevios()
                     
                  }
		}
	}
            ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            //enviando los valores
            ajax.send("idmetodologia="+idmetodologia+"&opcion="+opcion+"&idexamen="+idexamen);
        return false;
	//alert (sexo+"--"+redad);
	
}


function buscaposresultprevios(){ //INGRESAR REGISTROS
	//donde se mostrar� lo resultados
	//valores de los inputs
	idexamen=document.getElementById('cmbExamen').value;
	idmetodologia=document.getElementById('cmbMetodologia').value;
      //  alert (idmetodologia)
          //  alert('iadrea: '+idarea+' /idexa:  '+idexamen+' /sexo:  '+sexo+'/edad:'+redad)
            var opcion=12;
            Pag=1;
            //instanciamos el objetoAjax
            ajax=objetoAjax();
            //archivo que realizar� la operacion
            ajax.open("POST", "ctrLab_metodologia.php",true);
            ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
                       // alert (ajax.responseText)
                  document.getElementById('divReporta').innerHTML = ajax.responseText;
                  if (document.getElementById('cmbreporta')!=0){
                   document.frmnuevo.add_posresultado.disabled = false;
                   buscaposresultprevios()
                     
                  }
		}
	}
            ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            //enviando los valores
            ajax.send("idmetodologia="+idmetodologia+"&opcion="+opcion+"&idexamen="+idexamen);
        return false;
	//alert (sexo+"--"+redad);
	
}


///Fin de funciones de metodologia





function Estado(idatofijo){

var opcion=9;
	//alert(idexamen+"-"+condicion);
	ajax=objetoAjax();
	//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_metodologia.php",true);
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



function LimpiarCampos(){
	document.getElementById('cmbArea').value="0";
	document.getElementById('cmbExamen').value="0";
        document.getElementById('cmbMetodologia').value="0";
        document.getElementById('cmbreporta').value="0";
	document.getElementById('txtFechaFin').value="";
         var today = new Date();
         var dd = today.getDate();
         var mm = today.getMonth()+1; //January is 0!
         var yyyy = today.getFullYear();

         if(dd<10) {
             dd='0'+dd
         } 

         if(mm<10) {
             mm='0'+mm
         } 

         today = yyyy+'-'+mm+'-'+dd;
	document.getElementById('txtFechainicio').value=today;
}

function IngresarRegistro(){ //INGRESAR REGISTROS
	//donde se mostrar� lo resultados
	//valores de los inputs
	idexamen=document.getElementById('cmbExamen').value;
	idmetodologia=document.getElementById('cmbMetodologia').value;
	cmbreporta=document.getElementById('cmbreporta').value;
	Fechaini=document.getElementById('txtFechainicio').value;
	Fechafin=document.getElementById('txtFechaFin').value;	
        posresultados_sel=frmnuevo.posresultados_sel.value;
        text_posresultados_sel=frmnuevo.text_posresultados_sel.value;
        id_posresultados_sel=frmnuevo.id_posresultados_sel.value;
	alert (idmetodologia+"--"+posresultados_sel);
        return false
	var opcion=1;
	Pag=1;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizar� la operacion
	ajax.open("POST", "ctrLab_metodologia.php",true);
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
	ajax.send("idmetodologia="+idmetodologia+"&opcion="+opcion+"&idexamen="+idexamen+"&cmbreporta="+ cmbreporta+"&Pag="+Pag+"&Fechaini="+Fechaini+"&Fechafin="+Fechafin+"&posresultados_sel="+posresultados_sel+"&text_posresultados_sel="+text_posresultados_sel+"&id_posresultados_sel="+id_posresultados_sel);
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
	ajax.open("POST", "ctrLab_metodologia.php",true);
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
		ajax.open("POST", "ctrLab_metodologia.php",true);
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
	ajax.open("POST", "ctrLab_metodologia.php",true);
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
	ajax.open("POST", 'ctrLab_metodologia.php', true);
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
	ajax.open("POST", 'ctrLab_metodologia.php', true);
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


