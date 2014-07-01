<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include_once("clsLab_AntibioticosPorTarjeta.php");
//variables POST
/*$idantibioticoportarjeta=$_POST['idantibioticoportarjeta'];
$idantibiotico=$_POST['idantibiotico'];
$idtarjeta=$_POST['idtarjeta'];
$Pag =$_POST['Pag'];*/
$opcion=$_POST['opcion'];
//actualiza los datos del empleados
$objdatos = new clsLab_AntibioticosPorTarjeta;

switch ($opcion) 
{
    case 1:  //INSERTAR	
		 $idantibiotico=$_POST['idantibiotico'];
		 $idtarjeta=$_POST['idtarjeta'];
		//echo $idantibiotico."+".$idtarjeta;
		 $cant=$objdatos->Verificar_Antibiotico($idantibiotico,$idtarjeta,$lugar);
		 $cantidad=mysql_fetch_array($cant);
			//echo $cantidad[0];
		 if ($cantidad[0]==0){
			if ($objdatos->insertar($idantibiotico,$idtarjeta,$usuario,$lugar)==true)
			{
					echo "Datos Ingresados";		   
			}
			else{
					echo "El Registro no puede ser ingresado consulte al administrador";		
			}
		 }
		 else
		       echo "Los Antibioticos seleccionados ya estan asinados a la tarjeta";
		break;
    case 2:  //MODIFICAR      
		If ($objdatos->actualizar($idantibioticoportarjeta,$idantibiotico,$idtarjeta,$usuario)==true)
		{
			echo "Registro Actualizado"	;			
		}
		else{
			echo "No se pudo actualizar";
		}		
     break;
	 case 3:  //ELIMINAR    
		 //Vefificando Integridad de los datos
		 //$idantibioticoportarjeta=$_POST['idantibioticoportarjeta'];
		 $idantibiotico=$_POST['idantibiotico'];
		 $idtarjeta=$_POST['idtarjeta'];
		//  echo $idantibiotico."*".$idtarjeta;
		 if ($objdatos->eliminar($idantibiotico,$idtarjeta,$lugar)==true){		
			echo "Registro Eliminado" ;					
		}
		else{
				echo "El registro no pudo ser eliminado ";
		 }			
	break;
	case 4:// PAGINACION
		
	break;
	case 5:  //LLENAR LISTA
			//DIBUJANDO EL FORMULARIO NUEVAMENTE
			$idtarjeta=$_POST['idtarjeta'];
			$resultado= "<select name='ListAsociados' id='ListAsociados' size='8' multiple>";
							//LLENANDO LISTA DE ANTIBIOTICOS ASOCIADOS
							require_once('clsLab_AntibioticosPorTarjeta.php');
							$obj=new clsLab_AntibioticosPorTarjeta;
							$consulta_a= $obj->consultarasociados($idtarjeta);
							while($row = mysql_fetch_array($consulta_a))
							{
						$resultado .="<option value='" . $row['IdAntibiotico']. "'>" . $row['Antibiotico'] . "</option>";
						}
			$resultado .="</select>";
			echo  $resultado;
     
	break;
	
}

?>