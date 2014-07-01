<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

include("../../../Conexion/ConexionBD.php");
include_once("cls_recepcion.php");

$recepcion = new clsRecepcion;
$con = new ConexionBD;
$Proceso = $_POST['Proceso'];

switch($Proceso){

	case 'fillEstab':
             $rslts='';
		$Idtipo=$_POST['idtipoEstab'];
             $dtIdEstab=$recepcion->LlenarCmbEstablecimiento($Idtipo);
              $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:350px">';
		$rslts .='<option value="0">--Seleccione Establecimiento--</option>';
               while ($rows =mysql_fetch_array( $dtIdEstab)){
		  $rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       }
				
		$rslts .= '</select>';
		echo $rslts;
             
        break;
	case 'fillServicio':
	      $rslts='';
              $IdServ=$_POST['idserv'];
	    //  echo $IdServ;
	      $dtserv=$recepcion->LlenarCmbServ($IdServ,$lugar);
	      	$rslts = '<select name="cmbSubServ" id="cmbSubServ" onChange="fillMed(this.value)" style="width:350px">';
			$rslts .='<option value="0">--Seleccione Subespecialidad--</option>';
			while ($rows =mysql_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       }
				
		$rslts .='</select>';
		echo $rslts;

        break;
        case 'fillMed':
 		$rslts='';
               $idSubEsp=$_POST['idSubEsp'];
              // echo $idSubEsp; 
               $dttipo=$recepcion->ObtenerServicio($idSubEsp);
               $row=mysql_fetch_array($dttipo);
               $Servicio=$row[0];
  //echo $Servicio;
             if ($Servicio=='EXTREF' OR $Servicio=='CONEXT'){
	      		$dtmed=$recepcion->LlenarCmbMed($idSubEsp,$lugar);
			$rslts = '<select name="cmbMedico" id="cmbMedico"  style="width:350px">';
				$rslts .='<option value="0">--selecione un M&eacute;dico--</option>';
				while ($rows =mysql_fetch_array($dtmed)){
					$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
				}
			$rslts .='</select>';
			echo $rslts;
	     }
             else{
			$dtmed=$recepcion->LlenarCmbMedicos($lugar);
			$rslts = '<select name="cmbMedico" id="cmbMedico"  style="width:350px">';
				$rslts .='<option value="0">--Seleccione un Servicio--</option>';
				while ($rows =mysql_fetch_array($dtmed)){
					$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
				}
			$rslts .='</select>';
			echo $rslts;

		}
       
        break;
	
	case 'fillestudios':	
	$rslts='';
		$IdArea=$_POST['id_area'];	
		$dtMed=$recepcion->LlenarCmbArea($IdArea,$lugar);
			
		$rslts = '<select id="cboEstudio" style="width:250px" onChange="fillmuestra(this.value)">';
		$rslts .='<option value="0">--Seleccione Estudios--</option>';
			
		while ($rows =mysql_fetch_array($dtMed)){
			$rslts.= '<option value="' . $rows[1] .'" >'. htmlentities($rows[0]).'</option>';
		}
				
		$rslts .= '</select>';		
		echo $rslts;
		
	break;
	
	case 'fillmuestra':
	$rslts='';
		$IdEstudio=$_POST['id_estudio'];
		$dtMed=$recepcion->LlenarCmbMuestra($IdEstudio);
		
		$rslts = '<select id="cboMuestra"" style="width:250px" onChange="fillorigen(this.value)">';
		$rslts .='<option value="0">--Seleccione Estudios--</option>';
			
		while ($rows =mysql_fetch_array($dtMed)){
			$rslts.= '<option value="' . $rows[1] .'" >'. htmlentities($rows[0]).'</option>';
		}
				
		$rslts .= '</select>';	
		echo $rslts;
		
	break;
	
	case 'fillorigen':
	$rslts='';
		$IdMuestra=$_POST['id_muestra'];	
		$dtMed=$recepcion->LlenarCmbOrigen($IdMuestra);
			
		$rslts = '<select id="cboOrigen" style="width:250px">';
		$rslts .='<option value="0">--Seleccione Estudios--</option>';
			
		while ($rows =mysql_fetch_array($dtMed)){
			$rslts.= '<option value="' . $rows[0] .'" >'. $rows[1].'</option>';
			
		}
		$rslts .= '</select>';
		echo $rslts;
		
	break;
	
	case 'agregarexamenes':
	
	$idexam=$_POST['id_examen'];
	$tipo=$_POST['id_tipomuestra'];
	$origen=$_POST['id_origenmuestra'];
	$indicaciones=$_POST['indicaciones'];
	
	$verify=$recepcion->VerificarDatos($idexam,$tipo,$origen);
	
	if ($verify > 0){
		$rslts = -1;
	}else{
		$rslts=$recepcion->InsertarRecepcionPiloto($idexam,$tipo,$origen,$indicaciones);
		$rows=$recepcion->RecuperarDatos();
	
	
		$rslts= '<table class="SaladFormTABLE" cellspacing="0" cellpadding="0" style="WIDTH: 937px; HEIGHT: 46px" align="center" border="1">
		<tr>
			<td width="121" height="21" valign="top" nowrap class="CobaltFieldCaptionTD"><p align="center"><strong><font color="#ffffff">Codigo
        Examen</font></strong></p></td>
        		<td width="168" valign="top" nowrap class="CobaltFieldCaptionTD" ><p align="center"><strong><font color="#ffffff">Nombre
        Examen</font> </strong></p></td>
        		<td width="96" valign="top"  nowrap class="CobaltFieldCaptionTD" ><p align="center"><strong><font color="#ffffff">Tipo
        Muestra</font> </strong></p></td>
        		<td width="88" valign="top" nowrap class="CobaltFieldCaptionTD" ><p align="center"><strong><font color="#ffffff">Origen </font></strong></p></td>
		    	<td width="219" valign="top" nowrap class="CobaltFieldCaptionTD"><p align="center"><strong><font color="#ffffff">Indicacion</font></strong></p></td>
		</tr>';
  
  while ($fila=mysql_fetch_array($rows)){
	if(!empty($fila[2])){
		if(!empty($fila[3])){
			if(!empty($fila[4])){
			$ObservacionEncontrado=$recepcion->RecupererObservacion($fila[0]);
			$OrigenEncontrado=$recepcion->BuscarOrigen($fila[3]);
			$TipoEncontrado=$recepcion->RecuperarTipoMuestra($fila[2]);
			$ExamenEncontrado=$recepcion->RecupererNombreExamen($fila[1]);
		
			$rslts.=  '<tr>
    <td class="SaladDataTD"><input type="hidden" name="{IdDetalleSolicitud_Name}" value="{IdDetalleSolicitud}" style="WIDTH: 36px; HEIGHT: 22px" size="4">'.$fila[1].'</td> 
    <td class="SaladDataTD">'.htmlentities($ExamenEncontrado).'</td> 
    <td class="SaladDataTD">'.htmlentities($TipoEncontrado).'</td> 
    <td class="SaladDataTD">'.htmlentities($OrigenEncontrado).'</td> 
    <td class="SaladDataTD">'.htmlentities($ObservacionEncontrado).'</td> 
  </tr>';
	}else{
			$OrigenEncontrado=$recepcion->BuscarOrigen($fila[3]);
			$TipoEncontrado=$recepcion->RecuperarTipoMuestra($fila[2]);
			$ExamenEncontrado=$recepcion->RecupererNombreExamen($fila[1]);
		
			$rslts.=  '<tr>
    <td class="SaladDataTD"><input type="hidden" name="{IdDetalleSolicitud_Name}" value="{IdDetalleSolicitud}" style="WIDTH: 36px; HEIGHT: 22px" size="4">'.$fila[1].'</td> 
    <td class="SaladDataTD">'.htmlentities($ExamenEncontrado).'</td> 
    <td class="SaladDataTD">'.htmlentities($TipoEncontrado).'</td> 
    <td class="SaladDataTD">'.htmlentities($OrigenEncontrado).'</td> 
    <td class="SaladDataTD">&nsbp;</td> 
  </tr>';
	     }
	}else{
			$ObservacionEncontrado=$recepcion->RecupererObservacion($fila[0]);
			$TipoEncontrado=$recepcion->RecuperarTipoMuestra($fila[2]);
			$ExamenEncontrado=$recepcion->RecupererNombreExamen($fila[1]);
		
			$rslts.=  '<tr>
    <td class="SaladDataTD"><input type="hidden" name="{IdDetalleSolicitud_Name}" value="{IdDetalleSolicitud}" style="WIDTH: 36px; HEIGHT: 22px" size="4">'.$fila[1].'</td> 
    <td class="SaladDataTD">'.htmlentities($ExamenEncontrado).'</td> 
    <td class="SaladDataTD">'.htmlentities($TipoEncontrado).'</td> 
    <td class="SaladDataTD"></td> 
    <td class="SaladDataTD">'.htmlentities($ObservacionEncontrado).'</td> 
  </tr>';
		}
	}else{
			$ObservacionEncontrado=$recepcion->RecupererObservacion($fila[0]);
			$ExamenEncontrado=$recepcion->RecupererNombreExamen($fila[1]);
		
			$rslts.=  '<tr>
    <td class="SaladDataTD"><input type="hidden" name="{IdDetalleSolicitud_Name}" value="{IdDetalleSolicitud}" style="WIDTH: 36px; HEIGHT: 22px" size="4">'.$fila[1].'</td> 
    <td class="SaladDataTD">'.htmlentities($ExamenEncontrado).'</td> 
    <td class="SaladDataTD"></td> 
    <td class="SaladDataTD"></td> 
    <td class="SaladDataTD">'.htmlentities($ObservacionEncontrado).'</td> 
  </tr>';
		}				 
	}
	
	$rslts.=	"</table></form>";
	}
	
	echo $rslts;	
	break;
	
	case 'searchpac':
	
	$nec=$_POST['nec'];
	$NecEncontrado=$recepcion->ValidarExpediente($nec);
			
	if($NecEncontrado > 0){
		echo 0;
	}else{
		echo 2;
	}			
	
	break;
	
	case 'enviar':
	
	//iniciamos las variables con los datos de los post recibidos
	$pn=$_POST['PN'];
	$pa=$_POST['PA'];
	$sxn=$_POST['SXN'];
	$fn=$_POST['FN'];
        $Nfecha=explode("/",$fn);
        $Nfn=$Nfecha[2]."-".$Nfecha[1]."-".$Nfecha[0];	
	$fechareg = date("Y-m-d h:i:s");
		
	$NecNuevo=$recepcion->ModificacionExpDatosPac($pn,$pa,$sxn,$Nfn,$fechareg,$lugar);
		
	echo $NecNuevo;
	break;
	
	case 'guardar':
	$rslts='';
	
	$Expediente=$_POST['nec'];
        $Establecimiento=$_POST['establecimiento'];
        $Servicio=$_POST['Serv'];
	$SubServ=$_POST['SubServ'];
	$Empleado=$_POST['med'];
	$Fconsulta=$_POST['fcon'];
        $NFecha=explode('/',$Fconsulta);
        $NFconsulta=$NFecha[2]."-".$NFecha[1]."-".$NFecha[0];
	$FechaHoraReg = (date("Y-m-d h:i:s"));
	$IdUsuarioReg = 200 ;
	$Impresiones=0;
	$CAMA=0;
	$Ip=$_SERVER['REMOTE_ADDR'];
        //echo $Expediente."#".$Establecimiento."#".$Servicio."#".$SubServ."#".$Empleado."#".$Fconsulta."#".$Ip;
	$link = mysqli_connect('localhost','labor', 'clinic0', 'siap1'); 
 	

	if (!$link) { 
		printf('No se Puede Conectar a la BD. Error:' . mysqli_connect_error()); 
		exit; 
	} 
	
	if ($result = mysqli_multi_query($link,  "call RecepcionPiloto('$Expediente',$Establecimiento,'$Servicio',$SubServ,'$Empleado','$NFconsulta',$IdUsuarioReg,'$FechaHoraReg',$Impresiones,$CAMA,'$Ip',$lugar,@erro)")) { 
           			
	} 

	mysqli_close($link);
	
	$rslts='<form name="" action="" method="post">
  <table class="" cellspacing="0" cellpadding="0" border="0">
    <!-- BEGIN Error -->
    <tr>
      <td class="SaladErrorDataTD">&nbsp;</td> 
    </tr>
    <!-- END Error -->
    <tr>
      <td class="SaladDataTD" align="center">&nbsp;
      <input class="" type="button" value="Guardar Solicitud" id="btnguardar" style="WIDTH: 170px; HEIGHT: 33px" disabled onclick="guardar()">
	  <input class="" type="button" value="Ingresar otra Solicitud" id="btnsolicitud" style="WIDTH: 170px; HEIGHT: 33px" onclick="cargar()">
    </tr> 
  </table>
</form>';
	
	echo $rslts;
	break;
}
?>