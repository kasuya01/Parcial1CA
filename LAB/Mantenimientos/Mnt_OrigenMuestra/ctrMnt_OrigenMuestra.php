<?php

session_start();
include_once("clsMnt_OrigenMuestra.php");
$usuario = $_SESSION['Correlativo'];
$lugar = $_SESSION['Lugar'];
$area = $_SESSION['Idarea'];
//variables POST
$idorigen = $_POST['idorigen'];
$nombreorigen = $_POST['nombreorigen'];
$tipomuestra = $_POST['tipomuestra'];
$Pag = $_POST['Pag'];

$opcion = $_POST['opcion'];
//actualiza los datos del empleados
$objdatos = new clsMnt_OrigenMuestra;

switch ($opcion) {
    case 1:  //INSERTAR	
        //echo $tipomuestra;
        $respuesta=$objdatos->insertar($nombreorigen, $tipomuestra, $usuario);
        if ($respuesta == true) {
            echo "Registro Agregado";
        } else {
             if ($respuesta==0){
                        echo "Registro previamente ingresado";		
                    }
                    else{
                        echo $respuesta."Error al agregar la muestra ".$nombretipo;		
                    }
        }


        break;
    case 2:  //MODIFICAR  
        If ($objdatos->actualizar($idorigen, $tipomuestra, $nombreorigen, $usuario) == true) {
            echo "Registro Actualizado";
        } else {
            echo "No se pudo Actualizado";
        }




        break;
    case 3:  //ELIMINAR    
        //Vefificando Integridad de los datos
        if ($objdatos->eliminar($idorigen, $usuario) == true) {
            echo "Registro inhabilitado";
        } else {
            echo "El registro no pudo ser inhabilitado";
        }


        break;
    case 4:// PAGINACION
        ////para manejo de la paginacion
        $RegistrosAMostrar = 4;
        $RegistrosAEmpezar = ($_POST['Pag'] - 1) * $RegistrosAMostrar;
        $PagAct = $_POST['Pag'];

        /////LAMANDO LA FUNCION DE LA CLASE 

        $consulta = $objdatos->consultarpag($RegistrosAEmpezar, $RegistrosAMostrar);

        //muestra los datos consultados en la tabla
        echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
			   <tr>
			   <td  class='CobaltFieldCaptionTD' aling='center'> Modificar</td>
			   <td  class='CobaltFieldCaptionTD' aling='center'> Inhabilitar</td>		
			   <td class='CobaltFieldCaptionTD'> Origen Muestra </td>
                            <td class='CobaltFieldCaptionTD'> Tipo de Muestra </td>	
			   </tr>";

        while ($row = pg_fetch_array($consulta)) {
            echo "<tr>
					<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('" . $row['id'] . "')\"> </td>
					<td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('" . $row['id'] . "')\"> </td>
					<td> " . $row['origenmuestra'] . "</td>
					<td> " . $row['tipomuestra'] . "</td>
					</tr>";
        }
        echo "</table>";

        //determinando el numero de paginas
        $NroRegistros = $objdatos->NumeroDeRegistros();
        $PagAnt = $PagAct - 1;
        $PagSig = $PagAct + 1;

        $PagUlt = ceil($NroRegistros / $RegistrosAMostrar);

        echo "<table align='center'>
		       <tr>
			   <td colspan=3 align='center'> <strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong> </td>
			   </tr>
			   <tr>
			   <td>
			   <a onclick=\"show_event('1')\">Primero</a> </td>";
        //// desplazamiento

        if ($PagAct > 1)
            echo "<td> <a onclick=\"show_event('$PagAnt')\">Anterior</a> </td>";
        if ($PagAct < $PagUlt)
            echo "<td> <a onclick=\"show_event('$PagSig')\">Siguiente</a> </td>";
        echo "<td> <a onclick=\"show_event('$PagUlt')\">Ultimo</a></td>";
        echo "</tr>
			  </table>";
        break;
        
        
    case 5:
         $query = "select mom.*, tipomuestra from mnt_origenmuestra mom 
                join lab_tipomuestra ltm on (mom.idtipomuestra=ltm.id) WHERE  mom.habilitado=true ";
     
        //VERIFICANDO LOS POST ENVIADOS
        if ($tipomuestra!=0) {
            $query.="and mom.idtipomuestra=$tipomuestra" ;
        }

        if (!empty($_POST['nombreorigen'])) {
            $query .= "and origenmuestra ilike '%$nombreorigen%' ";
        }

        $NroRegistros = $objdatos->NumeroDeRegistrosbus($query);
         if ($NroRegistros>0){
        //para manejo de la paginacion
        $RegistrosAMostrar = 4;
        $RegistrosAEmpezar = ($_POST['Pag'] - 1) * $RegistrosAMostrar;
        $PagAct = $_POST['Pag'];

        //LAMANDO LA FUNCION DE LA CLASE 
        $consulta = $objdatos->consultarpagbus($query, $RegistrosAEmpezar, $RegistrosAMostrar);

        //muestra los datos consultados en la tabla
        echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
			    <tr>
                            <td  class='CobaltFieldCaptionTD' aling='center'> Modificar</td>
                            <td  class='CobaltFieldCaptionTD' aling='center'> Inhabilitar</td>
                            <td class='CobaltFieldCaptionTD'> Origen Muestra </td>
                            <td class='CobaltFieldCaptionTD'> Tipo de Muestra </td>
                            </tr>";

        while ($row = pg_fetch_array($consulta)) {
            echo "<tr>
					<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('" . $row['id'] . "')\"> </td>
					<td aling ='center'> 
					<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"eliminarDato('" . $row['id'] . "')\"> </td>
					<td> ".$row['origenmuestra']." </td>
					<td>" . $row['tipomuestra']. "</td>
					</tr>";
        }
        echo "</table>";

        //determinando el numero de paginas
        
        $PagAnt = $PagAct - 1;
        $PagSig = $PagAct + 1;
        $PagUlt=ceil($NroRegistros/$RegistrosAMostrar);
		 

        echo "<table align='center'>
		       <tr>
			   <td colspan=3 align='center'> <strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong> </td>
			   </tr>
			   <tr>
			   <td>
			   <a onclick=\"show_event_search('1')\">Primero</a> </td>";
        //// desplazamiento

        if ($PagAct > 1)
            echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
        if ($PagAct < $PagUlt)
            echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
        echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
        echo "</tr>
			  </table>";
        }//fin if registros>0
                else{
                    echo '<center><h2>No existen registros que coincidan con la búsqueda </center></h2>';
                    }
        break;
}
?>