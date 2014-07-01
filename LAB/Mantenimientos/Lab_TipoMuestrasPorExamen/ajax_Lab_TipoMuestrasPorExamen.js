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
	Pag="";
	idtipomuestra="";
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//archivo que realizará la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_TipoMuestrasPorExamen.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idarea="+idarea+"&opcion="+opcion+"&idexamen="+idexamen+"&Pag="+Pag+"&idtipomuestra="+idtipomuestra);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			document.getElementById('divExamen').innerHTML = ajax.responseText;
			
		}
	}	
}


function Guardar()
{
	//EN CASO DE TENER DATOS ASOCIADOS A LA TARJETA
	//VERIFICANDO QUE NO EXISTAN REGISTROS ELIMINADOS DE LA LISTA ASOCIADOS
	//EN CASO NO TENER DATOS ASOCIADOS LA TARJETA
	var list2= document.getElementById('ListAsociados');
	var combo=document.getElementById('cmbExamen');
	for (i=0 ; i<list2.options.length ; i++)
	{
		InsertarRegistro(combo.value,list2.options[i].value);
	}
}

function BuscandoAsociados()
{
    idexamen=document.getElementById('cmbExamen').value;
	idarea=document.getElementById('cmbArea').value;
	opcion=2;
	idtipomuestra="";
	Pag="";
    ajax=objetoAjax();
	ajax.open("POST", "ctrLab_TipoMuestrasPorExamen.php",true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idexamen="+idexamen+"&idtipomuestra="+idtipomuestra+"&opcion="+opcion+"&Pag="+Pag+"&idarea="+idarea);
	ajax.onreadystatechange=function() 
	{	if (ajax.readyState==4) 
		{
		   document.getElementById('divDatos').innerHTML = ajax.responseText;		
		}
	}
}

function InsertarRegistro(idexamen,idtipomuestra)
{
	opcion=1;
	Pag="";
	idarea="";
	ajax=objetoAjax();
	ajax.open("POST", "ctrLab_TipoMuestrasPorExamen.php",true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idexamen="+idexamen+"&idtipomuestra="+idtipomuestra+"&opcion="+opcion+"&Pag="+Pag+"&idarea="+idarea);
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) {
		    if(ajax.responseText=="OK"){
			 document.getElementById('divResultado').innerHTML = ajax.responseText;		
			}
			else{
				alert(ajax.responseText);
				}
		}
	}
}

function Eliminar()
{
	//EN CASO DE TENER DATOS ASOCIADOS A LA TARJETA
	//VERIFICANDO QUE NO EXISTAN REGISTROS ELIMINADOS DE LA LISTA ASOCIADOS
	//EN CASO NO TENER DATOS ASOCIADOS LA TARJETA
	var indice=document.getElementById('ListAsociados').selectedIndex;
	var list2= document.getElementById('ListAsociados');
	var combo=document.getElementById('cmbExamen').value;
	//var combo=document.getElementById('cmbExamen');
	var dato=list2.options[indice].value;
	opcion=3;
	
	ajax=objetoAjax();
	ajax.open("POST", "ctrLab_TipoMuestrasPorExamen.php",true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idexamen="+combo+"&idtipomuestra="+dato+"&opcion="+opcion);
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) {
		    if(ajax.responseText=="OK"){
			 document.getElementById('divResultado').innerHTML = ajax.responseText;		
			}
			else{
				alert(ajax.responseText);
				BuscandoAsociados()
			}
		}
	}
			
}


