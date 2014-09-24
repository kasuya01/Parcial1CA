<?php session_start();
include_once("ClaseSolicitud.php"); //Agregamos el Archivo con las clases y funciones a utilizar
	$Paciente= new Paciente;   
	$Laboratorio= new SolicitudLaboratorio; 	
	//$_SESSION["conectar"]=$conectar;
	$IdHistorialClinico=$_GET["IdHistorialClinico"];
        $IdCitaServApoyo=$_GET["IdCitaServApoyo"];
       // echo '<br/>idcitaservapoyo: '.$IdCitaServApoyo;
        $IdEstablecimiento=$_SESSION["IdEstablecimiento"];//Elegido en el combo
	$IdNumeroExp=$_SESSION["IdNumeroExp"];
	$idexpediente=$_SESSION["idexpediente"];
        $iduser=$_SESSION["IdUsuarioReg"];
        $LugardeAtencion=$_SESSION["lugar"]; //Lugar de donde proviene
	//$NombrePaciente=$Paciente->RecuperarNombre($IdEstablecimiento,$IdNumeroExp);  
        $NombrePa=  $Paciente->RecuperarNombre($IdEstablecimiento,$idexpediente); 
        $rowpa=  pg_fetch_array($NombrePa);
        $NombrePaciente= $rowpa['nombre'];
        $sexo=$rowpa['id_sexo'];
        //echo 'IdHistorialClinico:'.$IdHistorialClinico .' IdCitaServApoyo:  '.$IdCitaServApoyo.'  IdNumeroExp: '.$IdNumeroExp. '</br>';
        //exit;
        // recuperar el IdSolicituEstudio
        $IdSolicitudEstudio=$Paciente->RecuperarIdSolicituEstudio($idexpediente,$IdHistorialClinico);
        /* HACER AKI EL IF DE VERIFICACION DE IdCitaServApoyo Y ASI HACER EL INSERT O EL UPDATE*/
        //echo '$IdCitaServApoyo'.$IdCitaServApoyo.'<br/>';
        if($IdCitaServApoyo == "")
        {
            $IdCitaServApoyo=$Paciente->IdCitaServApoyoInsertUpdate($IdSolicitudEstudio,$iduser,$IdNumeroExp,$LugardeAtencion,$IdCitaServApoyo,1);
         
        }
        else
        {
            $IdCitaServApoyo=$Paciente->IdCitaServApoyoInsertUpdate($IdSolicitudEstudio,$iduser,$IdNumeroExp,$LugardeAtencion,$IdCitaServApoyo,0);
        }
        
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Examenes Solicitados a Laboratorio</title>
<link rel="stylesheet" type="text/css" href="./Estilo.css"></link>
<script language="javascript" src="./ajax.js"></script>
</head>

<!--<body onload="MostrarDetalle(<?php //echo $IdHistorialClinico;?>);">-->
<body >

<table class='Encabezado'  >
		  <tr>
		  	<td><font color='black'><b>No Expediente:</b> </font></td>
		  	<td><font color='black'><b><?php echo $IdNumeroExp;?></b></font></td></tr>
		  <tr>
		  	<td><font color='black'><b>Nombre Paciente:</b></font></td> 
			<td><font color='black'><b><?php echo $NombrePaciente;?></b></font></td></tr>
	</table><br>
<div id='RespuestaAjax'>
	<?php
	$result=$Laboratorio->DetalleEstudiosLaboratorio($conectar,$IdHistorialClinico,$IdEstablecimiento);
        $Bandera='S';
        if ($result!=false){
            echo " <form  method='post' name='Editar' id='Editar'>
                    <table class='General2'><tr><td colspan='8' class='TdTitulo2' >EXAMENES SOLICITADOS A LABORATORIO</td></tr>
                    <tr class='TdEncabezado'><td>C&oacute;digo</td>
                        <td>Nombre Examen</td>
                        <td>Tipo Muestra</td>
                        <td>Origen Muestra</td>
                        <td>Indicaci&oacute;n</td>
                        <td>Borrar</td>
                     <!--   <td>Urgente</td>-->
                    </tr>";
            $i=0;
            $t=0;
            $s=0;
            while ($Respuesta=pg_fetch_array($result)){
                echo "<tr class='TdResultados'><td>".$Respuesta["codigo_examen"]."</td>";         
                echo "<td>".($Respuesta["nombre_examen"]). "</td>";
                echo "<td><font color='#084B8A'><b>".($Respuesta["tipomuestra"]). "</b></font></td>";
  		echo "<td><font color='#084B8A'><b>".($Respuesta["origenmuestra"])."</b></font></td>";
  		echo "<td><input type='text' id='Indicacion".$Respuesta['idexamen']."' name='Indicacion".$Respuesta['idexamen']."'value='". ($Respuesta["indicacion"]). "'>";                                 
                echo "<input type='hidden' name='IdDetalle".$Respuesta['idexamen']."' Id='IdDetalle".$Respuesta['idexamen']."'value='".$Respuesta['iddetallesolicitud']."'></td>";		  echo "<td><input type='checkbox' name='ExamenesLab' value='".$Respuesta['idexamen']."'/></td>";
              /*  $Respuesta3=$Laboratorio->RecuperarData($IdHistorialClinico,$IdEstablecimiento,$Bandera);
                if ($Respuesta3!=NULL || $Respuesta3!=''){
                    $buscadetsol=$Laboratorio->buscardetsol($Respuesta3);
                    while ($Respuesta4=  pg_fetch_array($buscadetsol)){
                         if($Respuesta4["urgente"]==1 && $Respuesta['iddetallesolicitud']==$Respuesta4['iddetallesolicitud']){	
                             echo "<td><input type='checkbox' id='Detalle".$Respuesta['idexamen']."' checked='checked' value='".$Respuesta4['iddetallesolicitud']."'/></td> ";
                             $t++;
                         }    
                    }//fin while respuesta4
                    if($Respuesta["urgente"]==1 && $Respuesta3 != $Respuesta['idsolicitudestudio']){	                                       
                        echo "<td><input type='checkbox' id='Detalle".$Respuesta['idexamen']."' value='".$Respuesta['iddetallesolicitud']."'/></td>";
                                 $t++;
                      } //if fin respuestaurgente=1
                }//fin if respuesta3
               
                 if($Respuesta3 == NULL OR $Respuesta3 ==''){				 
                   if($Respuesta["urgente"]==1){	  
                   echo "<td><input type='checkbox' id='Detalle".$Respuesta['idexamen']."' value='".$Respuesta['iddetallesolicitud']."'/></td>";
                   $t++;     
                   }//fin if respuesta urgente=1                            	
		}//fin if Respuesta3=null
                */
                echo "</tr>";
		$i++;	 
                //aqui me quede
            }//fin while respuesta
            echo "<tr><td colspan='6'><br><b>Total de Examenes Solicitados:". $i."</b> </td></tr></table><br>";
            
            $Ejecutar2=$Laboratorio->impresionessoli($IdHistorialClinico);          
            $Respuesta2=pg_fetch_array($Ejecutar2);
                    if($Respuesta2["impresiones"]==1){
                    $check="<input id='Imprimir' type='checkbox'onclick='ImprimirResultados(".$IdHistorialClinico.", ".$IdSolicitudEstudio.");' checked='checked'>";
                    }else{
                    $check="<input id='Imprimir' type='checkbox'onclick='ImprimirResultados(".$IdHistorialClinico.", ".$IdSolicitudEstudio.");'>";
                    }
                    echo "<tr><td colspan='6' align='right'><br><h3><b>".$check."Resultado de Examenes Impresos [Pre-Operatorios]</b></h3> </td></tr></table><br>";
		
                    echo "<table class='General2'>
                            <tr><td align='right'>
                            <input type='button'  value='Guardar Cambios' class='boton2'  onclick='GuardarCambios();'> 
                            <input type='button' name='Agregar' value='Agregar Examen' class='boton2' onclick='AgregarExamen();'> 
                            <input type='button' name='Terminar' value='Terminar Solitud' class='boton2' onclick='Urgente();'> 
                            </td></tr></table>
                            <input type='hidden' name='total' id='total' value='".$i."'>
                            <input type='hidden' id='totalurgente' value='".$t."'>    
                            <input type='hidden' id='IdHistorialClinico' name= 'IdHistorialClinico' value='".$IdHistorialClinico."'>
                            </form>";
            
            
            
            
        }   //fin result=false     
                
	?>
	</div>
	
<div id='Cambios'></div>
<div id='Cambios'></div>
<form name="Parametros" method="post">
	<input type="hidden" id="IdHistorialClinico" value="<?php echo $_GET["IdHistorialClinico"]; ?>">
	<input type="hidden" id="IdNumeroExp"		 value="<?php echo $_SESSION["IdNumeroExp"]; ?>">
   	<input type="hidden" id="FechaSolicitud" 	 value="<?php echo $_SESSION["Fecha"]; ?>">
   	<input type="hidden" id="IdUsuarioReg" 		 value="<?php echo $_SESSION["IdUsuarioReg"]; ?>">
   	<input type="hidden" id="FechaHoraReg" 		 value="<?php echo $_SESSION["FechaHoraReg"]; ?>">
   	<input type="hidden" id="idexpediente" 		 value="<?php echo $_SESSION["idexpediente"]; ?>">
   	<input type="hidden" id="FechaConsulta" 		 value="<?php echo $_SESSION["FechaConsulta"]; ?>">
   	<input type="hidden" id="IdCitaServApoyo" 		 value="<?php echo $IdCitaServApoyo; ?>">
   	<input type="hidden" id="lugar" 		 value="<?php echo $LugardeAtencion; ?>">
   	<input type="hidden" id="IdEstablecimiento" 		 value="<?php echo $IdEstablecimiento; ?>">
   	<input type="hidden" id="Sexo" 		 value="<?php echo $sexo; ?>">

</form>
</body>
</html>
