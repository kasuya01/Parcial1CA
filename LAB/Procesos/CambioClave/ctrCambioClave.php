<?php session_start();
include_once("clsCambioClave.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$objdatos = new clsCambioClave;

//variables POST

$opcion=$_POST['opcion'];
//actualiza los datos del empleados


switch ($opcion) 
{
  case 1:  //Cambiar Password de ususario	
	$cActual=$_POST['ClaveActual'];
	$nClave=$_POST['NuevaClave'];
	$nClaveC=$_POST['NuevaClave1'];
    
	//Obtengo el C�digo del empleado
        $Cod=$objdatos->ObtenerCodigoEmpleado($usuario,$lugar);
        $row_Emp=pg_fetch_array($Cod);
         $Cod;
		$Id=$row_Emp['id'];
                $Id=$usuario;
	// Verifica que la clave introducida se la correcta
        if ($nClave=$nClaveC){
              $Id;
			if($objdatos->VerificarClave($cActual,$Id,$lugar)==true){
			
				if ($objdatos->ActualizarClave($nClave,$lugar,$Id)==true){
					echo "La Clave fue Cambiada con Exitoooo";
				}
				else
	                echo "La clave no se pudo cambiar consulte con el administrador";
			}
			else
				"La Clave no Existe";
        }
		else 
			echo "vuelva ingresar la nueva clave";
	

   
  break;
 
  
 }

?>