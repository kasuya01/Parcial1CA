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

function TarjetasAsociadas(idtarjeta)
{	opcion=5;
	
	// opcion
	ajax=objetoAjax();
	ajax.open("POST", "ctrLab_AntibioticosPorTarjeta.php",true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idtarjeta="+idtarjeta+"&opcion="+opcion);
	ajax.onreadystatechange=function() 
	{	if (ajax.readyState==4) 
		{
		   //alert(ajax.responseText);
		   document.getElementById('divDatos').innerHTML = ajax.responseText;		
		}
	}
}

function GuardarDatosAsociaciados()
{
	//EN CASO DE TENER DATOS ASOCIADOS A LA TARJETA
	//VERIFICANDO QUE NO EXISTAN REGISTROS ELIMINADOS DE LA LISTA ASOCIADOS
	//EN CASO NO TENER DATOS ASOCIADOS LA TARJETA
	var list2= document.getElementById('ListAsociados');
	var combo=document.getElementById('cmbTarjetas');
	//alert(list2.options.length);
	for (i=0 ; i<list2.options.length ; i++)
	{
		InsertarRegistro(combo.value,list2.options[i].value);
	}
}

function AgregarDatosAsociaciados()
{
	//EN CASO DE TENER DATOS ASOCIADOS A LA TARJETA
	//VERIFICANDO QUE NO EXISTAN REGISTROS ELIMINADOS DE LA LISTA ASOCIADOS
	//EN CASO NO TENER DATOS ASOCIADOS LA TARJETA
	var list2= document.getElementById('ListAsociados');
	var combo=document.getElementById('cmbTarjetas');
	//alert(list2.options.length);
	//for (i=0 ; i<list2.options.length ; i++)
	//{
            InsertarRegistro(combo.value,list2.options[i].value);
	//}
}

function InsertarRegistro(idtarjeta,idantibiotico)
{
	opcion=1;
	
	//alert(opcion);
	ajax=objetoAjax();
	ajax.open("POST", "ctrLab_AntibioticosPorTarjeta.php",true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idtarjeta="+idtarjeta+"&idantibiotico="+idantibiotico+"&opcion="+opcion);
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

function EliminarItems()
{
	//EN CASO DE TENER DATOS ASOCIADOS A LA TARJETA
	//VERIFICANDO QUE NO EXISTAN REGISTROS ELIMINADOS DE LA LISTA ASOCIADOS
	//EN CASO NO TENER DATOS ASOCIADOS LA TARJETA
	var indice=document.getElementById('ListAsociados').selectedIndex;
	var list2= document.getElementById('ListAsociados');
	var tarjeta=document.getElementById('cmbTarjetas');
	var combo=tarjeta.value;
	var dato=list2.options[indice].value;
	opcion=3;
	//alert (tarjeta);
	//alert(combo+"**"+dato+"**"+indice);
	ajax=objetoAjax();
	ajax.open("POST", "ctrLab_AntibioticosPorTarjeta.php",true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idtarjeta="+combo+"&idantibiotico="+dato+"&opcion="+opcion);
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) {
		    if(ajax.responseText=="OK"){
			 document.getElementById('divResultado').innerHTML = ajax.responseText;		
			}
			else{
				alert(ajax.responseText);
				TarjetasAsociadas(combo);
			}
		}
	}
			
}

function AgregarItems()
{
	//EN CASO DE TENER DATOS ASOCIADOS A LA TARJETA
	//VERIFICANDO QUE NO EXISTAN REGISTROS ELIMINADOS DE LA LISTA ASOCIADOS
	//EN CASO NO TENER DATOS ASOCIADOS LA TARJETA
	var indice=document.getElementById('ListAsociados').selectedIndex;
	var list2= document.getElementById('ListAsociados');
	var tarjeta=document.getElementById('cmbTarjetas');
	var combo=tarjeta.value;
	var dato=list2.options[indice].value;
	opcion=4;
	//alert (tarjeta);
	//alert(combo+"**"+dato+"**"+indice);
	ajax=objetoAjax();
	ajax.open("POST", "ctrLab_AntibioticosPorTarjeta.php",true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idtarjeta="+combo+"&idantibiotico="+dato+"&opcion="+opcion);
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) {
		    if(ajax.responseText=="OK"){
			 document.getElementById('divResultado').innerHTML = ajax.responseText;		
			}
			else{
				alert(ajax.responseText);
				TarjetasAsociadas(combo);
			}
		}
	}
			
}




