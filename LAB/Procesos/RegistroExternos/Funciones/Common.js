function Inint_AJAX() {
   try { return new ActiveXObject("Msxml2.XMLHTTP");  } catch(e) {} //IE
   try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch(e) {} //IE
   try { return new XMLHttpRequest();          } catch(e) {} //Native Javascript
   alert("XMLHttpRequest not supported");
   return null;
};

//***** validacion de teclas
var nav4 = window.Event ? true : false;
function acceptNum(evt,id){	
	var key = nav4 ? evt.which : evt.keyCode;	
// 	alert(key);
	if(id=='Password'){
	   if(key==13){
		Validar();
	   }
		return(key);
	}
	
	
	//solo numeros
	//return ((key < 13) || (key >= 48 && key <= 57) || key == 45);
	
}

function NoCero(ct){
//ct = id del textbox
var q=0; 
var c=document.getElementById(ct).value.charAt(q); 

while(c=='0') { ++q; c=document.getElementById(ct).value.charAt(q); } 
document.getElementById(ct).value=document.getElementById(ct).value.substr(q);

}

//**************************************************
//ELIMINACION DE ESPACIOS VACIOS

function trim(str,Obj){
	str = str.replace(/^(\s|\&nbsp;)*|(\s|\&nbsp;)*$/g,"");
	if(str==''){document.getElementById(Obj).value=str;}
	return(str);
}//trim

//*****************************************


//*******************************************************************
//ADMINISTRACION DE USUARIOS

   function Cargar(){
	var Activos = document.getElementById('Permitidos');
	var Inactivos = document.getElementById('Bloqueados');
	var IdCiq = document.getElementById('cmbIdCiq').value;
		var ajax = Inint_AJAX();

		ajax.onreadystatechange=function(){
				if(ajax.readyState==1){
					

				}
				if(ajax.readyState==4){
				alert(ajax.responseText);
					/*var Respuesta=ajax.responseText.split('~');
					Activos.innerHTML=Respuesta[0];
					Inactivos.innerHTML=Respuesta[1];*/
					
				}
			}
		
		ajax.open("GET","./Funciones/Procesos.php?Bandera=1"+"&IdCiq="+IdCiq,true);
		//alert (IdCiq)
		ajax.send(null);
		return false;
	
   }


   function Bloquear(){
	var Contenedor=document.getElementById('Activos[]');
	var Tope = Contenedor.options.length;
	var Valores = new Array();
	

		for(i=0;i<Tope;i++){
			
			 if (Contenedor.options[i].selected){			
				Valores[i]=Contenedor[i].value;
			 }//Condicion			
		}

		var Value=Contenedor[Tope-1].value;//Valor en la primera posicion
		var ValoresCount=Valores.length;

		if(ValoresCount==0 || Value==0){
			alert('No hay usuarios para bloquear !');
		}else{
				var ajax = Inint_AJAX();
	
			ajax.onreadystatechange=function(){
					if(ajax.readyState==1){
						document.getElementById('Movimiento').innerHTML='<img src="../imagenes/barra.gif">   ...Trabajando...';
					}
					if(ajax.readyState==4){
						document.getElementById('Movimiento').innerHTML='&nbsp;';
						Cargar();
					}
				}
			
			ajax.open("GET","./Funciones/Procesos.php?Bandera=2&Valores="+Valores,true);
			ajax.send(null);
			return false;
		}
   }




   function Habilitar(){
	var Contenedor=document.getElementById('Inactivos[]');
	var Tope = Contenedor.options.length;
	var Valores = new Array();
	
	
		for(var i=0;i<Tope;i++){
			
			 if (Contenedor.options[i].selected){			
				Valores[i]=Contenedor[i].value;
			 }//Condicion			
		}
			
			var Value=Contenedor[Tope-1].value; //Valor en la primera posicion
			var ValoresCount=Valores.length;

		if(ValoresCount==0 || Value==0){
			alert('No hay usuarios para habilitar !');
		}else{
				var ajax = Inint_AJAX();
	
			ajax.onreadystatechange=function(){
					if(ajax.readyState==1){
						document.getElementById('Movimiento').innerHTML='<img src="../imagenes/barra.gif">   ...Trabajando...';
					}
					if(ajax.readyState==4){
					
						document.getElementById('Movimiento').innerHTML='&nbsp;';
						Cargar();
					}
				}
			
			ajax.open("GET","./Funciones/Procesos.php?Bandera=3&Valores="+Valores,true);
			ajax.send(null);
			return false;
		}
   }
//**********************************************************************