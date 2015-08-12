<?php session_start();
include_once("clsLab_Posresultado.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//variables POST
//$idantibiotico=$_POST['idantibiotico'];

 $opcion=$_POST['opcion'];

$objdatos = new clsLab_Posresultado;
//$Clases = new clsLabor_Antibioticos;

switch ($opcion) 
{
    case 1:  //INSERTAR	
        $posreusltado=$_POST['posreusltado'];
        
       if ($objdatos->insertar($posreusltado,$usuario)==true) 
               //&& ($Clases->insertar_labo($antibiotico,$usuario)==true))
       {
	    echo "Registro Agregado";
        }
	else{
	    echo "No se pudo Agregar el Registro";
	}
		
    break;
    case 2:  //MODIFICAR    
         $idposresultado=$_POST['idposresultado'];
        $posresultado=$_POST['posresultado'];
        $cmbEstado=$_POST['cmbEstado'];
        
	if ($objdatos->actualizar($idposresultado,$posresultado,$cmbEstado,$usuario)==true) 
                //&& ($Clases->actualizar_labo($idantibiotico,$antibiotico,$usuario)==true)){
		echo "Registro Actualizado"	;	
	//}
	else{
		echo "No se pudo Actualizar el Registro";
	}
		
     break;
    /* case 3:  //ELIMINAR    
	     //Vefificando Integridad de los datos
		if (($objdatos->VerificarIntegridad($idantibiotico))==true){		
			echo "El Registro No puede ser eliminado tiene datos asociados" ;							
		}
		else{
			 if(($objdatos->eliminar($idantibiotico)==true) && ($Clases->eliminar_labo($idantibiotico)==true)){		
				echo "Registro Eliminado" ;					
			}
			else{
				echo "El registro no pudo ser eliminado ";
			}			
		}
     break;*/
     case 4:// PAGINACION
		//echo "llevo opcion 4";
		////para manejo de la paginacion
         $Pag =$_POST['Pag'];
		$RegistrosAMostrar=10;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		
		$consulta= $objdatos->consultarpag($RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		echo "<center >
               <table border = 1 style='width: 50%;'  class='table table-hover table-bordered table-condensed table-white table-striped'>
	           <thead>
                        <tr>
			   <th aling='center' > Modificar</th>
			  <!-- <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
			  <!-- <td class='CobaltFieldCaptionTD'> IdAntibiotico</td> -->
			   <th > Posible Resultado </th>	
                           <th >Estado </th>	
			   </tr>
                   </thead><tbody>
                    </center>";

		while($row = pg_fetch_array($consulta)){
			echo "<tr>
					<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('".$row[0]."')\"> </td>
			<!-- <td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row[0]."')\"> </td> -->
					<!-- <td> $row[0] </td> -->
					<td>".htmlentities($row['posible_resultado'])."</td>
                                        <td>".htmlentities($row['habilitado'])."</td>
					</tr>";
		}
		echo "</table>"; 

		//determinando el numero de paginas
		 $NroRegistros= $objdatos->NumeroDeRegistros();
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;
		 
		 $PagUlt=$NroRegistros/$RegistrosAMostrar;
		 
		 //verificamos residuo para ver si llevar� decimales
		 $Res=$NroRegistros%$RegistrosAMostrar;
		 //si hay residuo usamos funcion floor para que me
		 //devuelva la parte entera, SIN REDONDEAR, y le sumamos
		 //una unidad para obtener la ultima pagina
		 if($Res>0) $PagUlt=floor($PagUlt)+1;

		 echo "<table align='center'>
		       <tr>
			   <td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
			   </tr>
			   <tr>
			   <td>
			   <a onclick=\"show_event('1')\">Primero</a> </td>";
                 
		//// desplazamiento

		 if($PagAct>1) 
			 echo "<td> <a onclick=\"show_event('$PagAnt')\">Anterior</a> </td>";
		 if($PagAct<$PagUlt)  
			 echo "<td> <a onclick=\"show_event('$PagSig')\">Siguiente</a> </td>";
			 echo "<td> <a onclick=\"show_event('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
			  </table>";
                         
                         
                         
                         echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                                    {
                             
					 echo " <li ><a  href='javascript: show_event(".$i.")'>$i</a></li>";
                                     }
                    echo " </ul></center>";
      break;
      case 5: //buscar
            
	    $query = "SELECT id,posible_resultado,(CASE WHEN habilitado='t' THEN 'Habilitado'
		WHEN habilitado='f' THEN 'Inhabilitado' END) AS habilitado FROM lab_posible_resultado
		      WHERE ";
				
		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['txtposibleresultado']))
		{ $query .= "posible_resultado ilike'%".$_POST['txtposibleresultado']."%' "; }
                
                $query=$query."order by posible_resultado ";
		
		$query = substr($query ,0,strlen($query)-1);
		//echo $query;
		//para manejo de la paginacion
		$RegistrosAMostrar=10;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		//LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpagbus($query,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		/*echo "<table border = 1 align='center' class='estilotabla'>
			   <tr>
			   <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
			  <!-- <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
			 <!--  <td class='CobaltFieldCaptionTD'> IdAntibiotico</td> -->
			   <td class='CobaltFieldCaptionTD'> Antibiotico </td>	   
			   </tr>";*/
                
                echo "<center >
               <table border = 1 style='width: 50%;'  class='table table-hover table-bordered table-condensed table-white table-striped'>
	           <thead>
                        <tr>
			   <th aling='center' > Modificar</th>
			  <!-- <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
			  <!-- <td class='CobaltFieldCaptionTD'> IdAntibiotico</td> -->
			   <th > Posible Resultado </th>	
                           <th >Estado </th>	
			   </tr>
                   </thead><tbody>
                    </center>";
                

		while($row = pg_fetch_array($consulta)){
			echo "<tr>
					<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('".$row[0]."')\"> </td>
					<!-- <td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row[0]."')\"> </td> -->
					<!-- <td> $row[0] </td> -->
					<td>".htmlentities($row['posible_resultado'])."</td>
                                            <td>".htmlentities($row['habilitado'])."</td>
					</tr>";
		}
		echo "</table>"; 

		//determinando el numero de paginas
		 $NroRegistros= $objdatos->NumeroDeRegistrosbus($query);
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;
		 
		 $PagUlt=$NroRegistros/$RegistrosAMostrar;
		 
		 //verificamos residuo para ver si llevar� decimales
		 $Res=$NroRegistros%$RegistrosAMostrar;
		 //si hay residuo usamos funcion floor para que me
		 //devuelva la parte entera, SIN REDONDEAR, y le sumamos
		 //una unidad para obtener la ultima pagina
		 if($Res>0) $PagUlt=floor($PagUlt)+1;

		 echo "<table align='center'>
		       <tr>
			   <td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
			   </tr>
			   <tr>
			   <td>
			   <a onclick=\"show_event_search('1')\">Primero</a> </td>";
		//// desplazamiento

		  if($PagAct>1) 
			 echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
		 if($PagAct<$PagUlt)  
			 echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
			 echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
			  </table>";
                         
                          echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                                    {
                             
					 echo " <li ><a  href='javascript: show_event_search(".$i.")'>$i</a></li>";
                                     }
                    echo " </ul></center>";
      break;
	
}

?>