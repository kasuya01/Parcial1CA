<?php session_start();
include_once("clsLab_Observaciones.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//variables POST
$idobservacion=$_POST['idobservacion'];
 $observacion=htmlentities($_POST['observacion']);
 $idarea=$_POST['idarea'];
 $tiporespuesta=$_POST['tiporespuesta'];
$opcion=$_POST['opcion'];
$Pag =$_POST['Pag'];

$objdatos = new clsLab_Observaciones;

switch ($opcion) 
{
    case 1:  //INSERTAR	
        if ($objdatos->insertar($idarea,$observacion,$tiporespuesta,$usuario)==true)
	   {
			    echo "Registro Agregado";
		}
		else{
			echo "No se pudo Agregar el Registro ";
			//echo $idarea.$observacion.$tiporespuesta;
		}
    break;		
    case 2:  //MODIFICAR      
	If ($objdatos->actualizar($idobservacion,$observacion,$tiporespuesta,$usuario)==true){
		echo "Registro Actualizado"	;	
		
	}
	ELSE{
		echo "No se pudo actualizar";
	}
    break;
    case 3:  //ELIMINAR    
	if ($objdatos->eliminar($idobservacion)==true){		
		echo "Registro Eliminado" ;		
	}
	else{
		echo "El registro no pudo ser eliminado ";
	}			
		
    break;
    case 4:// PAGINACION
	////para manejo de la paginacion
	$RegistrosAMostrar=10;
	$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
	$PagAct=$_POST['Pag'];
	
	 /////LAMANDO LA FUNCION DE LA CLASE 
	$consulta= $objdatos->consultarpag($RegistrosAEmpezar, $RegistrosAMostrar);
        
        
	//muestra los datos consultados en la tabla
    echo "<center><div class='table-responsive' style='width: 60%;'>
            <table width='50%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
                <thead>
                <tr>
		    <th aling='center'> Modificar</th>
		    <!--<td class='CobaltFieldCaptionTD' aling='center'> Eliminar</td> -->
		    <th> Area</th> 
		    <th> observacion </th>
                    <th> Tipo de Respuesta</th>		   
	        </tr></thead><tbody>";
    
             while($row = pg_fetch_array($consulta)){
		echo "<tr>
			<td aling='center'> 
			<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			onclick=\"pedirDatos('".$row[0]."')\"> </td>
			<!-- <td aling ='center'> 
			<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			onclick=\"eliminarDato('".$row[0]."')\"> </td> -->
			 <td>". $row[1] ."</td> 
			<td>".htmlentities($row['observacion'])."</td>";
                         $resp=$row['tiporespuesta'];
                          if ($resp=='S')
                             echo "<td>Positivo</td>";
                          else if($resp=='N') 
                             echo "<td>Negativo</td>";	
                          else 	
			     echo "<td>Otro</td>";						
		  echo  "</tr>";
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
    case 5://buscar
	$query = "SELECT lb1.id,  
                         c1.idarea, 
                         observacion, tiporespuesta,
                            CASE tiporespuesta WHEN 'P' THEN 'POSITIVO' 
                                               WHEN 'N' THEN 'NEGATIVO' 
                                               WHEN 'O' THEN 'OTRO' 
                            END AS tiporespuesta1
                     FROM lab_observaciones lb1
		     inner join ctl_area_servicio_diagnostico c1 on (c1.id=lb1.idarea)
		      WHERE ";
		
	//VERIFICANDO LOS POST ENVIADOS
	if (!empty($_POST['idarea']))
		{ $query .=" c1.id=".$_POST['idarea']." AND "; }
		
	if (!empty($_POST['tiporespuesta']))
		{ $query .="tiporespuesta='".$_POST['tiporespuesta']."'AND "; }
	
	if (!empty($_POST['observacion']))
		{ $query .="observacion ilike '%".$_POST['observacion']."%' AND ";}
                  	
		 $query = substr($query ,0,strlen($query)-4);
		 $query_search = $query."ORDER  BY c1.idarea,observacion";	
       //echo $query_search ;
	//para manejo de la paginacion
		$RegistrosAMostrar=10;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	//  echo 
		//LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar,$RegistrosAMostrar);

		//muestra los datos consultados en la tabla
	echo "<center><div class='table-responsive' style='width: 60%;'>
            <table width='50%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
                <thead>
                <tr>
		    <th aling='center'> Modificar</th>
		    <!--<td class='CobaltFieldCaptionTD' aling='center'> Eliminar</td> -->
		    <th> Area</th> 
		    <th> observacion </th>
                    <th> Tipo de Respuesta</th>		   
	        </tr></thead><tbody>";
             while($row = @pg_fetch_array($consulta)){
		echo "<tr>
			<td aling='center'> 
			<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			onclick=\"pedirDatos('".$row[0]."')\"> </td>
			<!-- <td aling ='center'> 
			<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			onclick=\"eliminarDato('".$row[0]."')\"> </td> -->
			<td>". $row[1] ."</td> 
			<td>".htmlentities($row['observacion'])."</td>";
                         $resp=$row['tiporespuesta'];
                          if ($resp=='P')
                             echo "<td>Positivo</td>";
                          else if($resp=='N') 
                             echo "<td>Negativo</td>";	
                          else 	
			     echo "<td>Otro</td>";						
		echo  "</tr>";
		}
		echo "</table>"; 

		//determinando el numero de paginas
		 $NroRegistros= $objdatos->NumeroDeRegistrosbus($query_search);
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;
		 
		 $PagUlt=$NroRegistros/$RegistrosAMostrar;
		 
		 //verificamos residuo para ver si llevar� decimales
		 $Res=$NroRegistros%$RegistrosAMostrar;
		 //si hay residuo usamos funcion floor para que me
		 //devuelva la parte entera, SIN REDONDEAR, y le sumamos
		 //una unidad para obtener la ultima pagina
		 if($Res>0) $PagUlt=floor($PagUlt)+1;
                 else $PagUlt=$PagUlt;

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