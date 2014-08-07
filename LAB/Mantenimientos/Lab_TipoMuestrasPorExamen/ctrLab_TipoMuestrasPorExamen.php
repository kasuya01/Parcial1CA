<?php session_start();
include_once("clsLab_TipoMuestrasPorExamen.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$objdatos = new clsLab_TipoMuestrasPorExamen;
//variables POST
	//session_start();
	$idexamen=$_POST['idexamen'];
	$idarea=$_POST['idarea'];
	$Pag =$_POST['Pag'];
	$opcion=$_POST['opcion'];
    $idtipomuestra=$_POST['idtipomuestra'];

switch ($opcion) 
{
	case 1:  //INSERTAR	
			$idexamen=$_POST['idexamen'];
			$idtipomuestra=$_POST['idtipomuestra'];
	        $cant=$objdatos->Verificar_Muestra($idexamen,$idtipomuestra);
		    $cantidad=pg_fetch_array($cant);
		//	echo 'cant: '.$cantidad[0]. ' |idtipomuestra:'.$idtipomuestra;
			if ($cantidad[0]==0){
				if ($objdatos->insertar($idexamen,$idtipomuestra,$usuario)==true)
				{
					echo "Datos Ingresados";		   
					//echo "Datos Ingresados".$idexamen.' /'.$idtipomuestra;		   
				}
				else{
				//echo $idexamen.$idtipomuestra;
					echo "El Registro no puede ser ingresado consulte al administrador";	
				}
			}
			ELSE 
			    echo "Ya existen los elementos seleccionados";
		break;
	case 2:  //LLENAR LISTA
	//DIBUJANDO EL FORMULARIO NUEVAMENTE
		$resultado= "<select name='ListAsociados' id='ListAsociados' size='8' multiple>";
							//LLENANDO LISTA DE MUESTRAS ASOCIADOS
		//$obj=new clsLab_TipoMuestrasPorExamen;
		$consulta_a= $objdatos->consultarasociados($idexamen);
			while($row = pg_fetch_array($consulta_a))
			{
			$resultado.="<option value='" . $row['idtipomuestra']. "'>" . $row['tipomuestra'] . "</option>";
			}
		$resultado.="</select>";
                
			echo  $resultado;
	break;
	case 3://Elimininar elemmentos de la lista
			$idexamen=$_POST['idexamen'];
			$idtipomuestra=$_POST['idtipomuestra'];
			
			//echo $idexamen."*".$idtipomuestra;  
			if ($objdatos->Eliminar($idexamen,$idtipomuestra, $usuario)==true)
				echo "Dato Eliminado";
			else
				echo "No se pudo eliminar el dato";
	break;
	
	case 5:  //LLENAR COMBO DE EXAMENES  
	//DIBUJANDO EL FORMULARIO NUEVAMENTE
		$idarea=$_POST['idarea'];
            
		$resultado= "<select id='cmbExamen' name='cmbExamen' size='1'onchange='BuscandoAsociados();' >";
					//// LLENAR EL COMBO ////
				$consultaex= $objdatos->ExamenesPorArea($idarea,$lugar);
                                
                if (pg_num_rows($consultaex)>0){
                      $resultado .= "<option value='0'>--Seleccione un Examen--</option>";
				while($rowex = pg_fetch_array($consultaex))
				{
                                  
		 $resultado .= "<option value='" . $rowex['id']. "'>" . $rowex['nombreexamen'] . "</option>";
				}
			  ///// FINALIZA LLENADO /////
		$resultado.= "</select>";
                }
                else{
                    $resultado.= '<option value="0">No tiene examenes asociados</option>';
                }
	echo  $resultado;
     
	break;
	
	
	
}

?>