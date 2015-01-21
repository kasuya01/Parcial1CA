<?php session_start();
include_once("clsLab_TarjetasVITEK.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$opcion=$_POST['opcion'];
$Pag =$_POST['Pag'];
$objdatos = new clsLab_TarjetasVITEK;
$Clases = new clsLabor_TarjetasVITEK;

switch ($opcion) {
	case 1:  //INSERTAR	
		$nombretarjeta=$_POST['nombretarjeta'];

		if (empty($_POST['Fechaini'])){
			$Fechaini="NULL";
		} else{ 
			$FechaI=explode('/',$_POST['Fechaini']);
			$Fechaini= '\''.$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0].'\'';
		}
		if (empty($_POST['Fechafin'])){
			$Fechafin="NULL";
		} else{ 
			$FechaF=explode('/',$_POST['Fechafin']);
			$Fechafin= '\''.$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0].'\'';
		}

		if (($objdatos->insertar($nombretarjeta,$usuario,$lugar,$Fechaini,$Fechafin)==true)) {
			echo "Registro Agregado";
		} else{
			echo "No se pudo actualizar";
		}

		break;
    case 2:  //MODIFICAR
	    $idtarjeta=$_POST['idtarjeta']; 
	    $nombretarjeta=$_POST['nombretarjeta'];   

	    if (empty($_POST['Fechaini'])){
	    	$Fechaini="NULL";
	    } else{ 
	    	$FechaI=explode('/',$_POST['Fechaini']);
	    	$Fechaini= '\''.$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0].'\'';
	    }
	    if (empty($_POST['Fechafin'])){
	    	$Fechafin="NULL";
	    } else{ 
	    	$FechaF=explode('/',$_POST['Fechafin']);
	    	$Fechafin= '\''.$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0].'\'';
	    }

	    if(($objdatos->actualizar($idtarjeta,$nombretarjeta,$usuario,$lugar,$Fechaini,$Fechafin)==true)) {
	    	echo "Registro Actualizado"	;	
	    } else{
	    	echo "No Se Pudo Actualizar, Complete Los Campos Por Favor";
	    }

	    break;
   	case 3:  //ELIMINAR    
	   	$idtarjeta=$_POST['idtarjeta']; 
	   	if (($objdatos->eliminar($idtarjeta,$lugar)==true)&& ($Clases->eliminar_labo($idtarjeta,$lugar)==true)){		
	   		echo "Registro Eliminado" ;		

	   	} else{
	   		echo "El registro no pudo ser eliminado ";
	   	}

	   	break;
    case 4:// PAGINACION
	    $Pag =$_POST['Pag'];
			//echo "llevo opcion 4";
			////para manejo de la paginacion
	    $RegistrosAMostrar=4;
	    $RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
	    $PagAct=$_POST['Pag'];

			 /////LAMANDO LA FUNCION DE LA CLASE 
	    $consulta= $objdatos->consultarpag($RegistrosAEmpezar,$RegistrosAMostrar,$lugar);

			//muestra los datos consultados en la tabla
	    echo "<center >
                
                <table border = 1 style='width: 60%;'  class='table table-hover table-bordered table-condensed table-white'>
			   <thead>
                            <tr>
                                <th  aling='center'> Modificar</th>
			    	<!--  <td class='CobaltFieldCaptionTD' aling='center'> Eliminar</th> -->
			    	<!--<th > IdTarjeta</th>-->
			    	<th > Nombre de Tarjeta </th>
			    	<th > Fecha Inicio </th>	 
			    	<th > Fecha Finalización </th>	
			    </tr>
                    </thead><tbody>
                    </center>";

	    while($row = pg_fetch_array($consulta)){
	    	echo "<tr>
			    	<td aling='center'> 
			    		<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			    		onclick=\"pedirDatos('".$row['id']."')\"> </td>
			    		<!-- <td aling ='center'> 
			    		<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			    		onclick=\"eliminarDato('".$row['id']."')\"> </td> -->
			    		<!--<td>".$row['id']."</td>-->
			    		<td>".htmlentities($row['nombretarjeta'])." </td>
			    		<td>".htmlentities($row['fechaini'])." </td>
			    		<td>".htmlentities($row['fechafin'])." </td>
			    	</tr>";
	    }
	    echo "</table>"; 

		//determinando el numero de paginas
	    $NroRegistros= $objdatos->NumeroDeRegistros($lugar);
	    $PagAnt=$PagAct-1;
	    $PagSig=$PagAct+1;

	    $PagUlt=$NroRegistros/$RegistrosAMostrar;

		//verificamos residuo para ver si llevar� decimales
	    $Res=$NroRegistros%$RegistrosAMostrar;

		//si hay residuo usamos funcion floor para que me
		//devuelva la parte entera, SIN REDONDEAR, y le sumamos
		//una unidad para obtener la ultima pagina
	    if($Res>0) $PagUlt=floor($PagUlt)+1;
		//echo $PagUlt."-". $Res;
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
    case 5: //Busqueda
        $nombretarjeta=$_POST['nombretarjeta'];
        echo $fechaini=$_POST['Fechaini'];
        $fechafin=$_POST['Fechafin'];
        

        if (empty($_POST['Fechaini'])){
        	$Fechaini="NULL";
        }else{ 
        	$FechaI=explode('/',$_POST['Fechaini']);
        	$Fechaini= '\''.$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0].'\'';
        }

        if (empty($_POST['Fechafin'])){
        	$Fechafin="NULL";
        }else{ 
        	$FechaF=explode('/',$_POST['Fechafin']);
        	$Fechafin= '\''.$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0].'\'';	
        }
        $query = "SELECT id,nombretarjeta,
                TO_CHAR(fechaini::timestamp, 'DD/MM/YYYY') AS fechaini, 
                TO_CHAR(fechafin::timestamp, 'DD/MM/YYYY') AS fechafin 
                FROM lab_tarjetasvitek 
                WHERE idestablecimiento=$lugar";

	//VERIFICANDO LOS POST ENVIADOS
        if (!empty($_POST['nombretarjeta']))
        	{ $query .= "AND nombretarjeta ilike '%".$_POST['nombretarjeta']."%' AND"; }

       /* if (!empty($_POST['Fechaini'])) {
        	$query .= "AND fechaini= ".$Fechaini." AND";
    	}*/
        
        if (!empty($_POST['Fechaini'])) {
             $query .= " AND fechaini = '" . $_POST['Fechaini'] . "' AND";
           
        }
        
        

        /*if (!empty($_POST['Fechafin'])) {
        	$query .= "AND fechafin = ".$Fechafin." AND";
        } */
        
         if (!empty($_POST['Fechafin'])) {
             $query .= " AND fechafin = '" . $_POST['Fechafin'] . "' AND";
           //  $cond2 .= " t02.fecha_solicitud = '" . $_POST['Fechaini'] . "' AND";
        }
        

        if((empty($_POST['nombretarjeta'])) and (empty($_POST['Fechaini'])) and (empty($_POST['Fechafin']))) {
        	$ban=1;
        }
        
        if ($ban==0){
    		$query = substr($query ,0,strlen($query)-4);
			//echo $query;
			//para manejo de la paginacion
    		$RegistrosAMostrar=4;
    		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
    		$PagAct=$_POST['Pag'];

				//LAMANDO LA FUNCION DE LA CLASE 
    		$consulta= $objdatos->consultarpagbus($query,$RegistrosAEmpezar, $RegistrosAMostrar);

				//muestra los datos consultados en la tabla
    		echo "<center >
                        <table border = 1 style='width: 60%;'  class='table table-hover table-bordered table-condensed table-white'>
			   <thead>
                            <tr>
                                <th  aling='center'> Modificar</th>
			    	<!--  <td class='CobaltFieldCaptionTD' aling='center'> Eliminar</th> -->
			    	<!--<th > IdTarjeta</th>-->
			    	<th > Nombre de Tarjeta </th>
			    	<th > Fecha Inicio </th>	 
			    	<th > Fecha Finalización </th>	
			    </tr>
                    </thead><tbody>
                    </center>";
                //echo $query;
         
                 while($row = pg_fetch_array($consulta)){
	    	echo "<tr>
			    	<td aling='center'> 
			    		<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			    		onclick=\"pedirDatos('".$row['id']."')\"> </td>
			    		<!-- <td aling ='center'> 
			    		<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			    		onclick=\"eliminarDato('".$row['id']."')\"> </td> -->
			    		<!--<td>".$row['id']."</td>-->
			    		<td>".htmlentities($row['nombretarjeta'])." </td>
			    		<td>".htmlentities($row['fechaini'])." </td>
			    		<td>".htmlentities($row['fechafin'])." </td>
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
        }
       	break;
}
?>
