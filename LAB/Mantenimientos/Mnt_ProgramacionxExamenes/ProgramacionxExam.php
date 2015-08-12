<?php
	include("../indexCitas2.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../../Webstyle/Themes/Cobalt/Style.css">

<script language="javascript">
var sendReq;
var accion=0;
var datos;

function FillArea(IdServicio){
     accion=1;

	 if(document.getElementById('cmbespecialidad').value==0){
	  alert("Seleccione una especialidad!");
        } else{


	  if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	  }


	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'IncludeFiles/admin.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded;charset=UTF-8');
	  var param = 'Proceso=FillArea';
          param += '&idservicio='+IdServicio;
	  sendReq.send(param);
	}
}

function FillExam(IdArea){
  accion=2;
  
	if(document.getElementById('cboSubEspecialidades').value==0){ 
	  alert("Seleccione una especialidad!");
	} else{
		  
	  if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	  }

	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'IncludeFiles/admin.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	
	  var param = 'Proceso=FillExam';
	  param += '&idarea='+IdArea;
	  sendReq.send(param);  	
	}
}

function FillExamRx(IdArea){
  accion=3;
  
	if(document.getElementById('cboSubEspecialidades').value==0){ 
	  alert("Seleccione una especialidad!");
	} else{
		  
	  if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	  }

	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'IncludeFiles/admin.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	
	  var param = 'Proceso=FillExamRx';
	  param += '&idarea='+IdArea;
	  sendReq.send(param);  
	}
}

function GuardarProgramacion()
{
	accion=4;
	
	idservicio= document.getElementById('cmbespecialidad').value;
	idarea= document.getElementById('cboSubEspecialidades').value;
	idexam= document.getElementById('cboMedicos').value;
	fecha= document.getElementById('inidate').value;

        if((idservicio==0) && (idarea==0) && (idexam==0) && (fecha==""))
        {
		 alert("No ha seleccionado ningun elemento.");
                 document.getElementById('cmbespecialidad').focus();
		 return false;
	}

        if((idservicio==0) || (idarea==0) || (idexam==0) || (fecha==""))
        {
		 alert("Algunos elementos del formulario no han sido seleccionados.");
                 if(document.getElementById('inidate').value=="")
                 {
                    document.getElementById('inidate').focus();
                 }
                 if(document.getElementById('cboMedicos').value==0)
                 {
                    document.getElementById('cboMedicos').focus();
                 }
                 if(document.getElementById('cboSubEspecialidades').value==0)
                 {
                    document.getElementById('cboSubEspecialidades').focus();
                 }
                 if(document.getElementById('cmbespecialidad').value==0)
                 {
                    document.getElementById('cmbespecialidad').focus();
                 }

                 return false;
	}


         if (window.XMLHttpRequest)
         {
                    sendReq = new XMLHttpRequest();
         } else if(window.ActiveXObject) {
            sendReq = new ActiveXObject("Microsoft.XMLHTTP");
         } else{
            alert("no pudo crearse el objeto")
         }

         sendReq.onreadystatechange = procesaEsp;
         sendReq.open("POST", 'IncludeFiles/admin.php', true);
         sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

         var param = 'Proceso=GuardarProgramacion';
         param += '&idservicio='+idservicio+'&idarea='+idarea+'&idexam='+idexam+'&fecha='+fecha;
         sendReq.send(param);
         //alert(param);
        
}

function buscarprogramacion(Pag){
	 accion=5;

	idservicio= document.getElementById('cmbespecialidad').value;
	idarea= document.getElementById('cboSubEspecialidades').value;
	idexam= document.getElementById('cboMedicos').value;
	
	if((idservicio==0) && (idarea==0) && (idexam==0))
        {
		 alert("No ha seleccionado ningun elemento para realizar la busqueda.");
		 return false;
	}

        if (window.XMLHttpRequest) {
            sendReq = new XMLHttpRequest();
	} else if(window.ActiveXObject) {
            sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	} else{
            alert("no pudo crearse el objeto")
	}
			
	sendReq.open("POST", 'IncludeFiles/admin.php', true);
	sendReq.onreadystatechange = procesaEsp;	 
	sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	
	var param = 'Proceso=buscarprogramacion';
	param += '&idservicio='+idservicio+'&idarea='+idarea+'&idexam='+idexam+'&Pag='+Pag;
	sendReq.send(param); 
	
 }

function show_programacion(Pag)
    {
    accion=6;
    document.getElementById('divresultado').innerHTML = 'cargando eventos...';
  
    if (window.XMLHttpRequest) {
        sendReq = new XMLHttpRequest();
      } else if(window.ActiveXObject) {
        sendReq = new ActiveXObject("Microsoft.XMLHTTP");
      } else{
        alert("no pudo crearse el objeto")
      }
    
    sendReq.open("POST", 'IncludeFiles/admin.php', true);
    sendReq.onreadystatechange = procesaEsp;     
    sendReq.setRequestHeader('Content-Type',
    'application/x-www-form-urlencoded');
    
    var param = 'Proceso=show_programacion';
    param += '&Pag='+Pag;
    sendReq.send(param);      
}

function delProg(Id){
    accion=7;    
    
    if(confirm("Esta seguro que quiere eliminar este proceso?")){
    
        if (window.XMLHttpRequest) {
                sendReq = new XMLHttpRequest();
              } else if(window.ActiveXObject) {
                sendReq = new ActiveXObject("Microsoft.XMLHTTP");
              } else{
                alert("no pudo crearse el objeto")
              }
        
        sendReq.open("POST", 'IncludeFiles/admin.php', true);
        sendReq.onreadystatechange = procesaEsp;    
        sendReq.setRequestHeader('Content-Type',
        'application/x-www-form-urlencoded');
          
        var param = 'Proceso=delProg';
        param += '&id='+Id;
        sendReq.send(param);  
        alert(param);
    }
}

function uploadProg(Id){
    accion=8;
        
            
        if (window.XMLHttpRequest) {
                sendReq = new XMLHttpRequest();
              } else if(window.ActiveXObject) {
                sendReq = new ActiveXObject("Microsoft.XMLHTTP");
              } else{
                alert("no pudo crearse el objeto")
              }
        
        sendReq.open("POST", 'IncludeFiles/admin.php',true);
        sendReq.onreadystatechange = procesaEsp;     
        sendReq.setRequestHeader('Content-Type',
        'application/x-www-form-urlencoded');
          
        var param = 'Proceso=uploadProg';
        param += '&id='+Id;
        sendReq.send(param);  
        //alert(param);
}

function modProg(Id){
    accion=9;
    
        TiempoPrev = document.getElementById('inidate').value;
        
        if (window.XMLHttpRequest) {
            sendReq = new XMLHttpRequest();
            } else if(window.ActiveXObject) {
                sendReq = new ActiveXObject("Microsoft.XMLHTTP");
            } else{
                alert("no pudo crearse el objeto")
            }
                                
            sendReq.open("POST", 'IncludeFiles/admin.php', true);
            sendReq.onreadystatechange = procesaEsp;     
            sendReq.setRequestHeader('Content-Type',
            'application/x-www-form-urlencoded');
            
            var param = 'Proceso=modProg';
            param += '&TiempoPrev='+TiempoPrev+'&Id='+Id;
            sendReq.send(param);  
            //alert(param);
}

function procesaEsp(){
 if (sendReq.readyState == 4){//4 The request is complete
   if (sendReq.status == 200){//200 means no error.
	  respuesta = sendReq.responseText;	
	  //alert (respuesta)
	  switch(accion){
	  	case 1://Llenado del cmb para los SERVICIOS
			document.getElementById('lySubesp').innerHTML = respuesta;
                        break;
		case 2://Llenado del cmb para las AREAS DE LOS SERVICIOS
		  	document.getElementById('lyMed').innerHTML = respuesta;
			break;
		case 3://Llenado del cmb para los EXAMENES DE CADA AREA
			document.getElementById('lyMed').innerHTML = respuesta;
			break;
		case 4://Ingresar Programacion
			if(respuesta == 2){
			  alert("Se ha ingresado el evento");
                          show_programacion(1);
			}else{
			  alert("Este Examen ya esta ingresado");
			}
			break;
		case 5://Busqueda de Programacion
			document.getElementById('divrespuesta').innerHTML = respuesta;
			document.getElementById('divresultado').style.display="none";
			break;
		case 6://listado de eventos
            		document.getElementById('divresultado').innerHTML = respuesta;
            		break;
		case 7://eliminar un evento
        		if(respuesta == 2){
             			alert("Se ha eliminado el evento");
              			show_programacion(1);
            		}else{
              			alert("No se ha podido eliminar el evento");
            		}
            		break;
        	case 8://Subir datos para modificarlos
           		 document.getElementById('divinicial').innerHTML = respuesta;
            		break;
        	case 9://Modificar un evento
            		if(respuesta == 2){ 
              			alert("Se ha actualizado el evento");
               			location.href='ProgramacionxExam.php';
            		}else{
                		alert("No se ha podido actualizar el evento");
            		}
            		break;
		}		
	}else {
	  alert('Se han presentado problemas en la peticion');
    }
  }  
} 

function cancelar(){
	if(confirm("Esta seguro que quiere cancelar este proceso?")){
		location.href='ProgramacionxExam.php';
	}
}

function validarSiNumero(numero){
    if (!/^([0-9])*$/.test(numero))
    alert("Debe digitar solamente numeros en este campo");
    document.getElementById('inidate').focus();
}
</script>
</head>

<body text="#000000" class="MailboxPageBody" onLoad="show_programacion(1); document.getElementById('cmbespecialidad').focus();">
<center>

<div id="divinicial">

<form action="" name="frmsearchdate" enctype="multipart/form-data" >
<br>
<br>
<strong>
    <h2 align="center"><img class="MailboxInput" style="WIDTH: 38px; HEIGHT: 38px" height="38" src="../../Iconos/ChronologicalReview.png" width="80">&nbsp;Programacion
  por&nbsp; Examenes</h2>
    </strong> 	
	<table class="CobaltFormTABLE" cellspacing="1" cellpadding="0" border="1" >
	    <!-- BEGIN Error -->
	    <tr>
	      <td class="CobaltErrorDataTD" colspan="6">&nbsp;</td> 
	    </tr>
		<!-- END Error -->
		
		<tr>
		<td nowrap class="CobaltFieldCaptionTD">Servicios de Apoyo&nbsp;</td>
		<td class="CobaltDataTD">
			<select name="cmbespecialidad" id="cmbespecialidad" onChange="FillArea(this.value)" style="width:200px">
			<option value="0">--Seleccione Servicio--</option>
			<?php
			include_once("../Conexion/ConexionBD.php");
			$con = new ConexionBD;
			  
			  if($con->conectar()==true){			  
			  $consulta  = "SELECT IdServicio,NombreServicio FROM mnt_servicio WHERE IdTipoServicio='DCO'";
			  $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
			  
			  //por cada registro encontrado en la tabla me genera un <option>
			  while ($rows = mysql_fetch_array($resultado)){
				echo '<option value="' . $rows['IdServicio'] . '" >' . $rows['NombreServicio'] . '</option>'; 
			  }
			
			  }
			?>
			</select>
		</td>
		</tr>
	
	<tr>
                <td nowrap class="CobaltFieldCaptionTD">
                        Area&nbsp;
                </td>
		<td class="CobaltDataTD" style="width:160px;">
			<div id="lySubesp" style="position:relative; top: 0;">
			<select name="cboSubEspecialidades" id="cboSubEspecialidades" class="CobaltSelect" style="width:200px">
				<option value="0">--Seleccione Area--</option>
			</select>
			</div>
                        
		</td>
	</tr>
	
	<tr>
	<td nowrap class="CobaltFieldCaptionTD">Examenes&nbsp;</td>
		<td class="CobaltDataTD">
			<div id="lyMed">
			<select name="cboMedicos" id="cboMedicos" class="CobaltSelect" style="width:200px;">
				<option value="0">--Seleccione Examen--</option>
			</select>
			</div>
	</td>
	</tr>
	<tr>
		<td nowrap class="CobaltFieldCaptionTD">Tiempo Previo(en dias):&nbsp;</td>
		<td class="CobaltDataTD">
		<input id="inidate" class="CobaltInput" style="width:28px; height:20px;" maxlength=3 onChange='validarSiNumero(this.value);' ></td>
	</tr>
	
	<tr>
		<td colspan="6" align="right" nowrap class="CobaltFooterTD">
		<input type="button" name="btnSearch" id="btnSearch" value="Guardar" class="CobaltButton" onClick="GuardarProgramacion()">
		<input type="button" name="btnSearch" id="btnSearch" value="Buscar" class="CobaltButton" onClick="buscarprogramacion(1)">
		<input type="button" name="btnCan" id="btnCan" value="Cancelar" class="CobaltButton" onClick="cancelar()">
		</td>	
	</tr> 
	</table>
</form>
	
</div>

	<div id="divrespuesta">
	</div>
	 <div  id="divresultado">
    
    </div>

	</center>
</body>
</html>