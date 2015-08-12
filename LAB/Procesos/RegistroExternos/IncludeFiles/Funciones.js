var nav4 = window.Event ? true : false;
function acceptNum(evt){	
	var key = nav4 ? evt.which : evt.keyCode;	
//	alert(key);
	return ((key < 13) || (key >= 48 && key <= 57) || key==45);
}

function xmlhttp(){
		var xmlhttp;
		try{xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");}
		catch(e){
			try{xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}
			catch(e){
				try{xmlhttp = new XMLHttpRequest();}
				catch(e){
					xmlhttp = false;
				}
			}
		}
		if (!xmlhttp) 
				return null;
			else
				return xmlhttp;
}//xmlhttp

function show_registros(Pag){
	   var A =  document.getElementById('divrespuesta');
	  
		var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "<div align='center'>CARGANDO . . .</div>";
					}
				if(ajax.readyState==4){
					    A.innerHTML = ajax.responseText;
												
					}
			}

	ajax.open("GET","IncludeFiles/Procesos.php?Proceso=show_registros"+"&Pag="+Pag,true);
	ajax.send(null);
	return false;
		
}

function Limpiar(){
	var t=2;

	for(t;t<=6;t++){
	var ID='chk_'+t;
	var dias=document.getElementById(ID);
		if(dias.checked==true){
			dias.checked=false;
		}
	}  
	document.getElementById('Id_Ciq').value="";
	document.getElementById('NombreCIQ').value="";
	document.getElementById('acupo').value="";
	document.getElementById('intervalodays').value="";
	document.getElementById('cantfija').value="";
	document.getElementById('cmbRngHr').value=0;
	document.getElementById('cmbyr').value=0;
	document.getElementById('NombreCIQ').focus();
}

function InsertarRegistro(){
   	var IdCIQ =  document.getElementById('Id_Ciq').value;
	var Acupo =  document.getElementById('acupo').value;
	var Intervalo =  document.getElementById('intervalodays').value;
	var IdRngHr =  document.getElementById('cmbRngHr').value;
	var CantidadFija =  document.getElementById('cantfija').value;
	var Yrs =  document.getElementById('cmbyr').value;
        var IdEmpleado = document.getElementById('IdEmpleado').value;
        
	var i=2;
	var chequeados='';
	
	for(i;i<=6;i++){
	var ID='chk_'+i;
	var cuales=document.getElementById(ID);
		if(cuales.checked==true){
			chequeados=i+"/"+chequeados;
		}
	}
	//alert (chequeados)
	var ajax = xmlhttp();
		
	//if (Descripcion != "" ){
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					
					}
				if(ajax.readyState==4){
				//alert(ajax.responseText)
					if(ajax.responseText == 2){
              					alert("Se ha ingresado el registro");
						Limpiar();
              					show_registros(1);
              				}else{
              					alert("No se ha podido ingresar el evento");
            					}		
				}
			}

	ajax.open("GET","IncludeFiles/Procesos.php?Proceso=InsertarRegistro"+"&IdCIQ="+IdCIQ+"&Acupo="+Acupo+"&Intervalo="+Intervalo+"&IdRngHr="+IdRngHr+"&CantidadFija="+CantidadFija+"&Yrs="+Yrs+"&chequeados="+chequeados+"&IdEmpleado="+IdEmpleado,true);
	ajax.send(null);
	return false;
	
	//}else{
		//alert("Debe Introducir una descripcion");
	//}
}

function UploadUser(Id){
	miVentana= window.open("ModProcedimiento.php?Id="+Id,"miwin","width=800,height=400");

}

function ModUser(Id){
	var IdCIQ =  document.getElementById('Id_Ciq').value;
	var Acupo =  document.getElementById('acupo').value;
	var Intervalo =  document.getElementById('intervalodays').value;
	var IdRngHr =  document.getElementById('cmbRngHr').value;
	var CantidadFija =  document.getElementById('cantfija').value;
	var Yrs =  document.getElementById('cmbyr').value;
	var IdRngHrViejo = document.getElementById('IdRngViejo').value;
        var IdEmpleado = document.getElementById('IdEmpleado').value;
        
	var i=2;
	var chequeados='';
	
	for(i;i<=6;i++){
	var ID='chk_'+i;
	var cuales=document.getElementById(ID);
		if(cuales.checked==true){
			chequeados=i+"/"+chequeados;
		}
	}

	var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					
					}
				if(ajax.readyState==4){
					//alert(ajax.responseText);
					if(ajax.responseText == 2){
              					alert("Se ha modificado el registro");
              					window.close();
						window.opener.show_registros(1);
              				}else{
              					alert("No se ha podido modificar el registro");
            					}		
				}
			}

	ajax.open("GET","IncludeFiles/Procesos.php?Proceso=ModUser"+"&IdCIQ="+IdCIQ+"&Acupo="+Acupo+"&Intervalo="+Intervalo+"&IdRngHr="+IdRngHr+"&CantidadFija="+CantidadFija+"&Yrs="+Yrs+"&chequeados="+chequeados+"&Id="+Id+"&IdRngHrViejo="+IdRngHrViejo+"&IdEmpleado="+IdEmpleado,true);
	ajax.send(null);
	return false;
	
}

function ComboBloqueoHorario(){
    var IdCIQ =  document.getElementById('Id_Ciq').value;
    var A =  document.getElementById('cmbHorario');
	  
		var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
                    if(ajax.readyState==4){
                        A.innerHTML = ajax.responseText;
                    }
		}

	ajax.open("GET","IncludeFiles/Procesos.php?Proceso=ComboBloqueoHorario"+"&IdCIQ="+IdCIQ,true);
	ajax.send(null);
	return false;
}

function LimpiarBloque(){
	var t=2;

	for(t;t<=6;t++){
	var ID='chk_'+t;
	var dias=document.getElementById(ID);
		if(dias.checked==true){
			dias.checked=false;
		}
	}  
	document.getElementById('Id_Ciq').value="";
	document.getElementById('NombreCIQ').value="";
	document.getElementById('inidate').value="";
	document.getElementById('findate').value="";
	document.getElementById('cmbRngHr').value=0;
	document.getElementById('NombreCIQ').focus();
}

function mayor(fecha1,fecha2){
    f1=fecha1.split('/');
    f2=fecha2.split('/');
    fecha_1=new Date(f1[2],f1[1]-1,f1[0]);
    fecha_2=new Date(f2[2],f2[1]-1,f2[0]);
   
    if (fecha_1>=fecha_2)
        return true
    else
        return false
}

function FormatoFecha(Fecha){
    var regex = /^(\d{4})\/(\d{1,2})\/(\d{1,2})$/;
    if(!regex.test(Fecha)){       
        return false;
    }else{
        return true;
    }
}

function Validar(){
    var inidate = document.getElementById('inidate').value;
    var findate = document.getElementById('findate').value;
    var IdRngHr =  document.getElementById('cmbRngHr').value;
    var IdCiq = document.getElementById('Id_Ciq').value;
    var NombreCiq = document.getElementById('NombreCIQ').value;
    
    respuesta=mayor(findate,inidate);
    respuesta1=FormatoFecha(inidate);
    respuesta2=FormatoFecha(findate);
    
    if (respuesta==false){
            alert('La fecha final deber ser mayor a la fecha inicio');
            return false;
        } 
        
    if (respuesta1==false){
            alert("Ingrese una fecha con formato aaaa/mm/dd");
            document.getElementById('inidate').value='';  
            return false;
    }
    
    if (respuesta2==false){
            alert("Ingrese una fecha con formato aaaa/mm/dd");
            document.getElementById('findate').value='';  
            return false;
    }
    
    if (IdRngHr==0 || IdCiq=="" || NombreCiq==""){
        alert("Faltan Parametros de ingreso Obligatorios");
        return false;
    }
    
    return;
}

function BloqueoProcedimientos(){
    var IdCIQ =  document.getElementById('Id_Ciq').value;
    var inidate = document.getElementById('inidate').value;
    var findate = document.getElementById('findate').value;
    var IdRngHr =  document.getElementById('cmbRngHr').value;

    var i=2;
    var chequeados='';
	
    for(i;i<=6;i++){
	var ID='chk_'+i;
	var cuales=document.getElementById(ID);
		if(cuales.checked==true){
			chequeados=i+"/"+chequeados;
		}
    }
    
    Validar();
    var ajax = xmlhttp();
		
	ajax.onreadystatechange=function(){
            if(ajax.readyState==1){
            }
                if(ajax.readyState==4){
                    //alert(ajax.responseText)
                    if(ajax.responseText == 2){
              		alert("Se ha ingresado el registro");
			LimpiarBloque();
              		show_bloqueos(1);
                    }else{
              		alert("No se ha podido ingresar el evento");
            		}		
                    }
	}

	ajax.open("GET","IncludeFiles/Procesos.php?Proceso=BloqueoProcedimientos"+"&IdCIQ="+IdCIQ+"&inidate="+inidate+"&findate="+findate+"&IdRngHr="+IdRngHr+"&chequeados="+chequeados,true);
	ajax.send(null);
	return false;
}

function show_bloqueos(Pag){
	   var A =  document.getElementById('divrespuesta');
	  
		var ajax = xmlhttp();
		
		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
						A.innerHTML = "<div align='center'>CARGANDO . . .</div>";
					}
				if(ajax.readyState==4){
					    A.innerHTML = ajax.responseText;
												
					}
			}

	ajax.open("GET","IncludeFiles/Procesos.php?Proceso=show_bloqueos"+"&Pag="+Pag,true);
	ajax.send(null);
	return false;
		
}

function delEvt(IdEvento){
        
    if(confirm("Esta seguro que quiere eliminar este proceso?")){
    
      var ajax = xmlhttp();
		
	ajax.onreadystatechange=function(){
          if(ajax.readyState==4){
                    //alert(ajax.responseText)
                   if(ajax.responseText == 2){
              		alert("Se ha eliminado el registro");
              		show_bloqueos(1);
                    }else{
              		alert("No se ha podido eliminar el evento");
            		}		
                    }
	}

	ajax.open("GET","IncludeFiles/Procesos.php?Proceso=delEvt"+"&IdEvento="+IdEvento,true);
	ajax.send(null);
	return false;
    }
}

