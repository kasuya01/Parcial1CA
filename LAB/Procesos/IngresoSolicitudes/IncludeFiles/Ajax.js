/* 
 * funciones javascript para realizar el AJAX
 */
function xmlhttp(){
    var xmlhttp;
    try{
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e){
        try{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e){
            try{
                xmlhttp = new XMLHttpRequest();
            }
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


function parseUri (str) {
    var	o   = parseUri.options,
    m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
    uri = {},
    i   = 14;

    while (i--) uri[o.key[i]] = m[i] || "";

    uri[o.q.name] = {};
    uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
        if ($1) uri[o.q.name][$1] = $2;
    });

    return uri;
};

parseUri.options = {
    strictMode: false,
    key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
    q:   {
        name:   "queryKey",
        parser: /(?:^|&)([^&=]*)=?([^&]*)/g
    },
    parser: {
        strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
        loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
    }
};



function trim(str,Obj){
    str = str.replace(/^(\s|\&nbsp;)*|(\s|\&nbsp;)*$/g,"");
    if(str==''){
        document.getElementById(Obj).value=str;
    }
    return(str);
}//trim


/*Filtracion de teclas*/
var nav4 = window.Event ? true : false;
function acceptNum(evt,Control){	
    var key = nav4 ? evt.which : evt.keyCode;	
    //alert(key);
    if(key==8){
        switch(Control){
            case "NombreEmpleado"   :
                document.getElementById("IdEmpleado").value="";
                break;
               
            case "NombreExamen":
                document.getElementById("IdExamen").value="";
                break;
        }           
           
    }else{
        return ((key < 13) || (key>=97 && key <=122)||(key>=65 && key<=90));
        //(key >= 48 && key <= 57) || key == 45
    }
}


function regresar(url){
    var Url=parseUri(window.location);
    //alert(Url.query);
    window.location=url+"?"+Url.query;
}



function DatosPaciente(IdNumeroExp){
    
    
    var ajax = xmlhttp();//Objeto AJAX

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            /*NOTHING*/	
            document.getElementById('avisos_errores').innerHTML="GUARDANDO INFORMACION ...";
        }
        if(ajax.readyState==4){

            /*if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../index.php';//Cambiar por direccion para iniciar sesion
            }*/
            var Respuesta=ajax.responseText;
            //alert(ajax.responseText)
            
            if(Respuesta=="ERROR_DATOS"){
                alert("Se genero un error con la base de datos! \n o no existe paciente!");
                document.getElementById('avisos_errores').innerHTML="ERROR DE DATOS";
                //se ocultan los botones si se generan errores
                document.getElementById('agregar').style.display="none";
                document.getElementById('cerrar').style.display="inline";
            }else{
                document.getElementById("avisos_errores").innerHTML="";
                var NombrePaciente=ajax.responseText;
                
                document.getElementById('NombrePaciente').innerHTML=NombrePaciente;
                document.getElementById('NombreEmpleado').focus();
            }

 
										
        }
    }
		
    ajax.open("GET","IncludeFiles/Procesos.php?Bandera=1&IdNumeroExp="+IdNumeroExp,true);
    ajax.send(null);
    return false;
    
}


function CargarCombo(IdEmpleado){
    //alert(IdEmpleado);
    var ajax = xmlhttp();//Objeto AJAX

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            /*NOTHING*/	
            document.getElementById('avisos_errores').innerHTML="OBTENIENDO INFORMACION ...";
        }
        if(ajax.readyState==4){

            /*if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../index.php';//Cambiar por direccion para iniciar sesion
            }*/
            var Respuesta=ajax.responseText;
            //alert(ajax.responseText)
            
            if(Respuesta=="ERROR_DATOS"){
                alert("Error de conexion con la base de datos!");
                document.getElementById('avisos_errores').innerHTML="ERROR DE CONEXION";
                //se ocultan los botones si se generan errores
                document.getElementById('agregar').style.display="none";
                document.getElementById('cerrar').style.display="inline";
            }else{
                
                var Respuesta=ajax.responseText;
                
                document.getElementById("combo_especialidades").innerHTML=Respuesta;
                document.getElementById('avisos_errores').innerHTML="";
            }

 
										
        }
    }
		
    ajax.open("GET","IncludeFiles/Procesos.php?Bandera=100&IdEmpleado="+IdEmpleado,true);
    ajax.send(null);
    return false;

}




function validar(){
    var Ok= true;
    var IdExamen=document.getElementById('IdExamen');
    var IdEmpleado=document.getElementById('IdEmpleado');
    
    if(trim(IdExamen.value,'IdExamen')==""){
        alert("Seleccione un examen valido!")
        document.getElementById("NombreExamen").focus();
        document.getElementById("NombreExamen").select();
        Ok= false;
        
    }
    
    if(trim(IdEmpleado.value,'IdEmpleado')==""){
        alert("Seleccione un examen valido!")
        document.getElementById("NombreEmpleado").focus();
        document.getElementById("NombreEmpleado").select();
        Ok= false;
    }
    
    if(Ok==true){
        GuardarExamen();
    }
        
    
}


function GuardarExamen(){
    var IdNumeroExp=document.getElementById('IdNumeroExp').value;
    var IdEmpleado=document.getElementById('IdEmpleado').value;
    var IdSubServicio=document.getElementById('IdSubServicio').value;
    var IdExamen=document.getElementById('IdExamen').value;
    var IdSolicitudEstudio=document.getElementById('IdSolicitudEstudio').value;
    
    var ajax = xmlhttp();//Objeto AJAX

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            /*NOTHING*/	
            document.getElementById('avisos_errores').innerHTML="GUARDANDO INFORMACION ...";
        }
        if(ajax.readyState==4){

            /*if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../index.php';//Cambiar por direccion para iniciar sesion
            }*/
            var Respuesta=ajax.responseText;
            //alert(ajax.responseText)
            
            if(Respuesta=="ERROR_DATOS"){
                alert("Se genero un error con la base de datos! \n no se guardo la informacion!");
                document.getElementById('avisos_errores').innerHTML="ERROR DE CONEXION";
                //se ocultan los botones si se generan errores
                document.getElementById('agregar').style.display="none";
                document.getElementById('cerrar').style.display="inline";
            }else{
                
                var IdSolicitudEstudio=ajax.responseText;
                document.getElementById("IdSolicitudEstudio").value=IdSolicitudEstudio;
                
                document.getElementById('NombreEmpleado').disabled=true;
                document.getElementById('IdSubServicio').disabled=true;
                document.getElementById("IdExamen").value="";
                document.getElementById("NombreExamen").value="";
                document.getElementById("NombreExamen").focus();
                
                DetalleSolicitudEstudio();
                
            }

 
										
        }
    }
		
    ajax.open("GET","IncludeFiles/Procesos.php?Bandera=2&IdNumeroExp="+IdNumeroExp+"&IdEmpleado="+IdEmpleado+"&IdSubServicio="+IdSubServicio+"&IdExamen="+IdExamen+"&IdSolicitudEstudio="+IdSolicitudEstudio,true);
    ajax.send(null);
    return false;

}

function DetalleSolicitudEstudio(){
    var IdSolicitudEstudio=document.getElementById('IdSolicitudEstudio').value;
    
    var ajax = xmlhttp();//Objeto AJAX

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            /*NOTHING*/	
            document.getElementById('avisos_errores').innerHTML="OBTENIENDO INFORMACION ...";
        }
        if(ajax.readyState==4){

            /*if(ajax.responseText=="ERROR_SESSION"){
                alert('La sesion ha caducado \n vuelva a iniciar sesion!');
                window.location='../index.php';//Cambiar por direccion para iniciar sesion
            }*/
            var Respuesta=ajax.responseText;
            //alert(ajax.responseText)
            
            if(Respuesta=="ERROR_SESSION"){
                alert("La sesion ha terminado \n vuelva a iniciar sesion.-");
                window.close();
            }else{
                document.getElementById('avisos_errores').innerHTML="";
                var Respuesta=ajax.responseText;
                document.getElementById("Detalle").innerHTML=Respuesta;
                document.getElementById("NombreExamen").focus();
            }

 
										
        }
    }
		
    ajax.open("GET","IncludeFiles/Procesos.php?Bandera=3&IdSolicitudEstudio="+IdSolicitudEstudio,true);
    ajax.send(null);
    return false; 
    
    
}

function EliminarExamenes(){
    
    var Examenes=document.getElementsByName("examenes");
    var Tope = Examenes.length;
    var i=0;
    // alert(Tope+" OK");
    
    for(i;i<Tope;i++){
        
        if(Examenes[i].checked==true){
            var IdDetalleSolicitud=Examenes[i].value;
            ProcesoEliminar(IdDetalleSolicitud);
        }
    }
    
    
    if(i==Tope){
        DetalleSolicitudEstudio();
    }
  
    
}

function ProcesoEliminar(IdDetalleSolicitud){
    var ajax = xmlhttp();//Objeto AJAX

    ajax.onreadystatechange=function(){
        if(ajax.readyState==1){
            /*NOTHING*/	
            document.getElementById('avisos_errores').innerHTML="ELIMINANDO EXAMENES...";
        }
        if(ajax.readyState==4){

            var Respuesta=ajax.responseText;
            //alert(ajax.responseText)
            
            if(Respuesta=="ERROR_SESSION"){
                alert("La sesion ha terminado \n vuelva a iniciar sesion.-");
                window.close();
            }else{
                document.getElementById('avisos_errores').innerHTML="";
                        
            }

 
										
        }
    }
		
    ajax.open("GET","IncludeFiles/Procesos.php?Bandera=4&IdDetalleSolicitud="+IdDetalleSolicitud,true);
    ajax.send(null);
    return false; 
}

function EliminarSolicitud(){
    var confirmacion=confirm("Desea Eliminar la solicitud de examenes?");
    
    if(confirmacion==true){
        var IdSolicitudEstudio=document.getElementById("IdSolicitudEstudio").value
        var ajax = xmlhttp();//Objeto AJAX

        ajax.onreadystatechange=function(){
            if(ajax.readyState==1){
                /*NOTHING*/	
                document.getElementById('avisos_errores').innerHTML="ELIMINANDO SOLICITUD...";
            }
            if(ajax.readyState==4){

                var Respuesta=ajax.responseText;
                //alert(ajax.responseText)
            
                if(Respuesta=="ERROR_SESSION"){
                    alert("La sesion ha terminado \n vuelva a iniciar sesion.-");
                    window.close();
                }else{
                    document.getElementById('avisos_errores').innerHTML="";
                    alert(ajax.responseText);
                    alert("Solicitud Eliminada!");
                    window.close();
                }

 
										
            }
        }
		
        ajax.open("GET","IncludeFiles/Procesos.php?Bandera=5&IdSolicitudEstudio="+IdSolicitudEstudio,true);
        ajax.send(null);
        return false; 
    }
}


function Terminar(IdNumeroExp){
    
    window.opener.mostrardetalle(IdNumeroExp);
    window.close();    
    
}