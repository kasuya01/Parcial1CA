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
	//archivo que realizar� la operacion ->actualizacion.php
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
        var fin =0;
        var comboarea=document.getElementById('cmbArea').value;
        if (list2.options.length >0){
            for (i=0 ; i<list2.options.length ; i++)
            {
                if (i==(list2.options.length-1)){
                    fin=1;
                }
            //    alert(i+' fin:'+fin)
                    InsertarRegistro(combo.value,list2.options[i].value, comboarea, fin, i);
            }
        }
        else{
            alert ('Seleccione como minimo un tipo de muestra');
            return false;
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

function InsertarRegistro(idexamen,idtipomuestra, idarea, fin, i)
{
   //alert ('Insertar Registro idexamen'+idexamen+ 'idtipomuestra'+idtipomuestra)
	opcion=1;
	Pag="";
	idarea="";
	ajax=objetoAjax();
	ajax.open("POST", "ctrLab_TipoMuestrasPorExamen.php",true);
     //   alert( ajax.onreadystatechange)
	ajax.onreadystatechange=function() 
	{

		if (ajax.readyState==4) {
                //    alert ('Response Text:'+ajax.responseText+':Fin')
                    if (fin==1){
                        alert(ajax.responseText);
                    }
		 /*   if(ajax.responseText=="OK"){
			 document.getElementById('divResultado').innerHTML = ajax.responseText;		
			}
			else{
				
				}*/
		}
	}
         ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idexamen="+idexamen+"&idtipomuestra="+idtipomuestra+"&opcion="+opcion+"&Pag="+Pag+"&idarea="+idarea+"&i="+i);

}
//Fn PG
function Eliminar()
{
	//EN CASO DE TENER DATOS ASOCIADOS A LA TARJETA
	//VERIFICANDO QUE NO EXISTAN REGISTROS ELIMINADOS DE LA LISTA ASOCIADOS
	//EN CASO NO TENER DATOS ASOCIADOS LA TARJETA
	var indice=document.getElementById('ListAsociados').selectedIndex;
	var list2= document.getElementById('ListAsociados');
	var combo=document.getElementById('cmbExamen').value;	
	var dato=list2.options[indice].value;
       
	opcion=3;
	
	ajax=objetoAjax();
	ajax.open("POST", "ctrLab_TipoMuestrasPorExamen.php",true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
       // alert(combo+'-'+dato)
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

function AgregarItemsLista()
{
   var j=0; 
   var encontrado = false; 
   var texto = new Array(); 
   var valor = new Array(); 
   var combo=document.getElementById('cmbExamen');
   var list1 = document.getElementById('ListMuestras');
   var list2 = document.getElementById('ListAsociados');
   var muestras="";
   // C�digo para ver cual son los que hay que eliminar de un select e incluirlos en el otro. 
	if (combo.value != 0)
	{
            for (i=0 ; i<list1.options.length ; i++) 
            { 	// Los que est�n seleccionados, comprobamos que no est�n en la segunda lista y si es asi, lo a�adimos a esta. 
                if (list1.options[i].selected) 
                {   j=0; 
                    encontrado = false; 
                    while (j < list2.options.length && !encontrado) 
                    { 
                        if (list1.options[i].value == list2.options[j].value) 
			{ 
                      /*      var x = document.getElementById("mySelect").selectedIndex;
    var y = document.getElementById("mySelect").options;
    alert("Index: " + y[x].index + " is " + y[x].text);*/
                            encontrado = true;
                            if (muestras==""){
                                muestras=list1.options[i].text;
                            }
                            else{
                                 muestras=muestras+','+list1.options[i].text;
                            }
                           
                        //    alert (list1.options[i].value+'-'+i+list1.options[i].text)
                            //muestras= muestras+document.getElementById('cmbExamen').selectedOptions[1].text
                            
			} 
			j++; 
                    } 
					if (!encontrado) 
					{ 
						texto[texto.length] = list1.options[i].text; 
						valor[valor.length] = list1.options[i].value; 
					} 
			} 
		} 
                if (encontrado==true){
                    alert("La(s) Muestra(s) "+muestras+" ya esta(n) asociada(s)..")
                }
                
		   // Eliminamos de uno y lo incluimos en el otro. 
		for (h=texto.length-1;h>=0;h--) 
		{ 
			list2.options[list2.options.length] = new Option (texto[h], valor[h]); 
			list1.options[texto[h]] = null; 
			} 
	}
	else{
		alert("Seleccione un Examen");
	}
}



