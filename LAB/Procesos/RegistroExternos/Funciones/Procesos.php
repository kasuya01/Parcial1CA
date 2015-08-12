<?php session_start();
include("../../../Conexion/ConexionBD.php");
include_once("../../../Funciones/Common.php");
include_once("ClsCombinaciones.php"); 

$clase= new clsCombinaciones;
$con = new ConexionBD;
$comun = new clsGeneral;

$Proceso = $_GET["Bandera"];
$IdUsuarioReg=$_SESSION['usereg'];
$IdEstablecimiento=$_SESSION['IdEstablecimiento'];	
$LugardeAtencion=$_SESSION['Area'];
$FechaReg = $comun->FechaServer();

switch($Proceso){

	case 1:
	$IdCiq=$_GET["IdCiq"];
	
	//Despliegue de Usuarios
		$resp=$clase->ObtenerUsuarios($IdCiq);
		$resp2=$clase->ObtenerUsuarios(0);
		
	//Formacion de Multiselect usuarios Activos
		$combo1='<select multiple="multiple" id="Activos[]" name="Activos[]"  size="10" style="width:340;">';
			$count=0;
			while($row=mysql_fetch_array($resp)){
				$combo1.="<option value='".$row["IdUser"]."'>".htmlentities($row["NombreUsuario"])."</option>";
				$count++;
			}//while
			if($count==0){$combo1.="<option value='0'>SIN USUARIOS ACTIVOS</option>";}
		$combo1.="</select>";
		
		
		
	//Formacion de Multiselect usuarios Inactivos
		$combo2='<select multiple="multiple" id="Inactivos[]" name="Inactivos[]"  size="10" style="width:340;">';
		$count=0;
			while($row=mysql_fetch_array($resp2)){
				$combo2.="<option value='".$row["IdUser"]."'>".htmlentities($row["NombreUsuario"])."</option>";
				$count++;
			}
			if($count==0){$combo2.="<option value='0'>SIN USUARIOS BLOQUEADOS</option>";}
		$combo2.="</select>";
		
		
		
		
		echo $combo1.'~'.$combo2;
		
	break;
	case 2:
		//Bloquero de Usuarios
		$Datos=$_GET["Valores"];
		$Array=split(',',$Datos);
		$tope=sizeof($Array);
		
		echo $tope;
		$inf='';
		for($i=0;$i<$tope;$i++){
			$proceso->Bloqueo($Array[$i]);
		}
		
	break;
	case 3:
		//Habilitacion de Usuarios
		$Datos=$_GET["Valores"];
		$Array=split(',',$Datos);
		$tope=sizeof($Array);
		
		$inf='';
		for($i=0;$i<$tope;$i++){
			$proceso->Habilitar($Array[$i]);
		}		
		
	break;
}

?>