<?php session_start();
include_once("clsLab_Bacterias.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//variables POST
$idbacteria=$_POST['idbacteria'];
$bacteria=$_POST['bacteria'];
$opcion=$_POST['opcion'];
$Pag =$_POST['Pag'];
$objdatos = new clsLab_Bacterias;
$Clases = new clsLabor_Bacterias;
//$obje=new clsLab_Bacterias;
switch ($opcion) 
{
    case 1:  //INSERTAR	
		if ($objdatos->insertar($bacteria,$usuario)==true) 
                        //&& ($Clases->insertar_labo($bacteria,$usuario)==true)){
			echo "Registro Agregado";
        //}
                else{
			echo "No se pudo Agregar el Registro";
		}
    break;
    case 2:  //MODIFICAR      
		If ($objdatos->actualizar($idbacteria,$bacteria,$usuario)==true) 
                        //&& ($Clases->actualizar_labo($idbacteria,$bacteria,$usuario)==true)){
			echo "Registro Actualizado"	;	
		//}
		else{
			echo "No se pudo Actualizar el Registro";
		}
		
     break;
     case 3:  //ELIMINAR    
			if ($objdatos->eliminar($idbacteria)==true) 
                                //&& ($Clases->eliminar_labo($idbacteria)==true)){		
				echo "Registro Eliminado" ;		
			//}
			else{
				echo "El registro no pudo ser Eliminado ";
			}			
     break;
     case 4: // PAGINACION
		//para manejo de la paginacion
		$RegistrosAMostrar=10;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		//LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpag($RegistrosAEmpezar, $RegistrosAMostrar);
                
                
                
		//muestra los datos consultados en la tabla
	echo "<center><div class='table-responsive' style='width: 50%;'>
                <table width='25%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
                    <thead>
                            <tr>
                                    <th aling='center' > Modificar</th>
                                    <!--<td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
                                    <!--<td class='CobaltFieldCaptionTD'> Idbacteria</td> -->
                                    <th> Bacteria </th>	   
			    </tr>
                    </thead><tbody>";

		while($row = pg_fetch_array($consulta)){
			echo "<tr>
					<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('".$row[0]."')\"> </td>
				<!-- <td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('".$row[0]."')\"> </td> -->
					<!-- <td> $row[0] </td> -->
					<td>".htmlentities($row[1])."</td>
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
     case 5:  //buscar
            
	   $query = "SELECT id,bacteria FROM lab_bacterias
		      ";

				
		//VERIFICANDO LOS POST ENVIADOS
        		if (!empty($_POST['bacteria']))
                    		{ 
                    $query .= " WHERE bacteria ilike '%".$_POST['bacteria']."%' " ; }

		
		$query = substr($query ,0,strlen($query)-1);
	
		//para manejo de la paginacion
		$RegistrosAMostrar=10;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	 $query_search = $query." ORDER BY bacteria";
         
		//LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpagbus($query,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		
                
                
                echo "
                    <center><div class='table-responsive' style='width: 50%;'>
            <table width='25%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
                <thead>
                        <tr>
                                <th aling='center'> Modificar</th>
                                <!--<td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
                                <!--<td class='CobaltFieldCaptionTD'> Idbacteria</td> -->
                                <th > Bacteria </th>	   
			</tr>
                    </thead>
                    <tbody>";
                

		while($row = @pg_fetch_array($consulta)){
		      echo "<tr>
					<th aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('".$row[0]."')\"> </td>
				      <!-- <td aling ='center'> 
					 <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					 onclick=\"eliminarDato('".$row[0]."')\"> </td> -->
					<!-- <td> $row[0] </td> -->
					<td>".htmlentities($row[1])."</td> 
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