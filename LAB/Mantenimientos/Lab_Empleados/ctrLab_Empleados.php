<?php

session_start();
include_once("clsLab_Empleados.php");
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];
//variables POST
$idempleado  = $_POST['idempleado'];
$idarea      = $_POST['idarea'];
$nomempleado = $_POST['nomempleado'];
$Pag         = $_POST['Pag'];
$opcion      = $_POST['opcion'];
$cargo       = $_POST['cargo'];
$login       = $_POST['login'];
//actualiza los datos del empleados
$objdatos = new clsLab_Empleados;
//echo $idempleado;
switch ($opcion) {
    case 1:  //INSERTAR
        $pass = "123";
        $corr = $objdatos->LeerUltimoCodigo($lugar);
        if (($cargo == 6) or ( $cargo == 10)) {
            $niv = 1;
            $IdEstabExt = 'null';
        } else if ($cargo == 1) {
            $niv = 2;
            $IdEstabExt = 'null';
        } else if (($cargo == 2) or ( $cargo == 8)) {
            $niv = 33;
            $IdEstabExt = 'null';
        } else if ($cargo == 7) {
            $niv = 31;
            $IdEstabExt = 'null';
        } else if ($cargo == 11) {
            $niv = 4;
            $IdEstabExt = 1037; //Codigo del establecimiento de Laboratorio Central
        }

        //echo $cargo."y nivel".$niv;
        if (($objdatos->insertar($idempleado, $lugar, $idarea, $nomempleado, $cargo, $usuario, $corr, $IdEstabExt) == true) && ($objdatos->Insertar_Usuario($login, $idempleado, $pass, $niv, $lugar) == 1)) {
            echo "Registro Agregado";
        } else {
            echo "No se pudo Agregar el Registro";
        }
        break;
    case 2:  //MODIFICAR      

        if (($cargo == 6) or ( $cargo == 10)) {
            $niv = 1;
        } else if ($cargo == 1) {
            $niv = 2;
        } else if (($cargo == 2) or ( $cargo == 8)) {
            $niv = 33;
        } else if ($cargo == 7) {
            $niv = 31;
        } else if ($cargo == 11) {
            $niv = 4;
        }
        //echo $cargo."y nivel".$niv;
        If (($objdatos->actualizar($idempleado, $lugar, $idarea, $nomempleado, $cargo, $usuario) == true) &&
                ($objdatos->actualizar_Usuario($idempleado, $login, $niv, $lugar)) == true) {
            echo "Registro Actualizado";
        } else {
            echo "No se pudo actualizar";
        }
        break;
    case 3:
        $resultado = Estado::EstadoCuenta($idempleado, $_POST['EstadoCuenta'], $lugar);
        break;
    case 4:// PAGINACION
        ////para manejo de la paginacion
        $RegistrosAMostrar = 4;
        $RegistrosAEmpezar = ($_POST['Pag'] - 1) * $RegistrosAMostrar;
        $PagAct            = $_POST['Pag'];

        /////LAMANDO LA FUNCION DE LA CLASE 
        $consulta = $objdatos->consultarpag($lugar, $RegistrosAEmpezar, $RegistrosAMostrar);
        
        //muestra los datos consultados en la tabla
        echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
		<tr>
                    <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
                    <td aling='center' class='CobaltFieldCaptionTD'> Habilitado</td>
                    <td aling='center' class='CobaltFieldCaptionTD'> C&oacute;digo Empleado </td>
                    <td aling='center' class='CobaltFieldCaptionTD'> Nombre Empleado </td>
                    <td aling='center' class='CobaltFieldCaptionTD'> &Aacute;rea</td>
                    <td aling='center' class='CobaltFieldCaptionTD'> Cargo </td>
                    <td aling='center' class='CobaltFieldCaptionTD'> Usuario </td>	   
		</tr>";
        while ($row = pg_fetch_array($consulta)) {
            echo "<tr>
                    <td aling='center'> 
                        <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			onclick=\"pedirDatos('" . $row['idempleado'] . "')\">
                    </td>
                    <td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;'
                        onclick='Estado(\"" . $row['idempleado'] . "\",\"" . $row['estadocuenta'] . "\")'>" . $row['habilitado'] . 
                    "</td>
                    <td>" . $row['idempleado'] . "</td>
                    <td>" . htmlentities($row['nombreempleado']) . "</td>
                    <td>" . htmlentities($row['nombrearea']) . "</td>
                    <td>" . htmlentities($row['cargo']) . "</td>
                    <td>" . htmlentities($row['login']) . "</td>
		</tr>";
        }
        echo "</table>";

        //determinando el numero de paginas
        $NroRegistros = $objdatos->NumeroDeRegistros($lugar);
        $PagAnt = $PagAct - 1;
        $PagSig = $PagAct + 1;
        //echo $NroRegistros;
        $PagUlt = $NroRegistros / $RegistrosAMostrar;
        
        //verificamos residuo para ver si llevar� decimales
        $Res = $NroRegistros % $RegistrosAMostrar;

        //si hay residuo usamos funcion floor para que me
        //devuelva la parte entera, SIN REDONDEAR, y le sumamos
        //una unidad para obtener la ultima pagina
        if ($Res > 0)
            $PagUlt = floor($PagUlt) + 1;
        //echo "Pagina Ultima:".$PagUlt ."Res".$Res ."act".$PagAct;
        echo "<table align='center'>
		<tr>
                    <td colspan=3 align='center'> <strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong> </td>
                </tr>
		<tr>
                    <td><a onclick=\"show_event('1')\" style='cursor: pointer;'>Primero</a></td>";
        //// desplazamiento

        if ($PagAct > 1)
            echo "<td> <a onclick=\"show_event('$PagAnt')\" style='cursor: pointer;'>Anterior</a> </td>";
        if ($PagAct < $PagUlt)
            echo "<td> <a onclick=\"show_event('$PagSig')\" style='cursor: pointer;'>Siguiente</a> </td>";
        echo "<td> <a onclick=\"show_event('$PagUlt')\" style='cursor: pointer;'>Ultimo</a></td>";
        echo "</tr>
	    </table>";
        break;
    case 5:  //LEER ULTIMO CODIGO  
        $consulta = $objdatos->LeerUltimoCodigo($idarea);

        if ($consulta >= 0 && $consulta <= 9) {
            $codigo = $idarea . '00' . $consulta;
        }
        if ($consulta >= 10 && $consulta <= 99) {
            $codigo = $idarea . '0' . $consulta;
            //document.frmnuevo.txtidempleado.value=idArea+'0'+numero;
        }
        if ($consulta >= 100 && $consulta <= 999) {
            $codigo = $idarea . $consulta;
            //document.frmnuevo.txtidempleado.value=idArea+numero;
        }
        echo "<input type='text' id='txtidempleado'  name='txtidempleado' value='" . $codigo . "'  />";
        break;

    case 6:

        break;

    case 7: //BUSQUEDA
        $query = "SELECT t01.idempleado,
                         t04.enabled AS estadocuenta, 
                         CASE WHEN enabled = true THEN 'Habilitado'
                              ELSE 'Inhabilitado' END AS Habilitado,
                         t01.idarea,
                         t03.nombrearea,
                         t01.nombreempleado,
                         t02.id AS idcargoempleado,
                         t02.cargo,
                         t01.id_establecimiento AS idestablecimiento,
                         t04.username AS login
                    FROM mnt_empleado                        t01
                    INNER JOIN mnt_cargoempleados            t02 ON (t02.id = t01.id_cargo_empleado)
                    INNER JOIN ctl_area_servicio_diagnostico t03 ON (t03.id = t01.idarea)
                    INNER JOIN fos_user_user                 t04 ON (t01.id = t04.id_empleado AND t01.id_establecimiento = t04.id_establecimiento)
                    INNER JOIN ctl_atencion                  t05 ON (t05.id = t03.id_atencion AND t05.codigo_busqueda = 'DCOLAB') WHERE ";

        $conEmp = $objdatos->BuscarEmpleado($idempleado, $lugar);
        $ExisEmp = pg_fetch_array($conEmp);
        $ban = 0;
        $ban1 = 0;
        
        //VERIFICANDO LOS POST ENVIADOS
        if ($ExisEmp[0] >= 1) {//si existe el empleado	
            // echo $idempleado;
            if (!empty($_POST['idempleado'])) {
                $query .= " t01.idempleado ILIKE '%" . $_POST['idempleado'] . "%' AND";
            }
        }
        if (!empty($_POST['nomempleado'])) {
            $query .= " t01.nombreempleado ILIKE '%" . $_POST['nomempleado'] . "%' AND";
        }

        if (!empty($_POST['idarea'])) {
            $query .= " t01.idarea = " . $_POST['idarea'] . " AND";
        }

        if (!empty($_POST['cargo'])) {
            $query .= " t01.id_cargo_empleado = " . $_POST['cargo'] . " AND";
        }

        if (!empty($_POST['login'])) {
            $query .= " t04.username ILIKE '%" . $_POST['login'] . "%' AND";
        }

        if ((empty($_POST['cargo'])) and ( empty($_POST['idarea'])) and ( empty($_POST['nomempleado'])) and ( empty($_POST['idempleado'])) and ( empty($_POST['login']))) {
            $ban = 1;
        }


        if ($ban == 0) {
            $query = substr($query, 0, strlen($query) - 4);
            $query_search = $query . " AND id_tipo_empleado = (SELECT id FROM mnt_tipo_empleado WHERE codigo = 'LAB') AND correlativo !=0 AND t01.id_establecimiento = $lugar ORDER BY t01.idempleado";
            
            ////para manejo de la paginacion
            $RegistrosAMostrar = 4;
            $RegistrosAEmpezar = ($_POST['Pag'] - 1) * $RegistrosAMostrar;
            $PagAct = $_POST['Pag'];

            //ENVIANDO A EJECUTAR LA BUSQUEDA!!
            /////LAMANDO LA FUNCION DE LA CLASE 
            $consulta = $objdatos->consultarpagbus($query_search, $RegistrosAEmpezar, $RegistrosAMostrar);
            // echo $consulta;
            //muestra los datos consultados en la tabla
            echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
                        <tr>
                            <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
                            <td aling='center' class='CobaltFieldCaptionTD'> Habilitado</td>
                            <td aling='center' class='CobaltFieldCaptionTD'> C&oacute;digo Empleado </td>
                            <td aling='center' class='CobaltFieldCaptionTD'> Nombre Empleado </td>
                            <td aling='center' class='CobaltFieldCaptionTD'> &Aacute;rea</td>
                            <td aling='center' class='CobaltFieldCaptionTD'> Cargo </td>
			    <td aling='center' class='CobaltFieldCaptionTD'> Usuario </td>	    
			 </tr>";

            while ($row = pg_fetch_array($consulta)) {
                echo"<tr>	
                            <td aling='center'> 
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"pedirDatos('" . $row['idempleado'] . "')\">
		 	    </td>" .
                "<td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' " .
                "onclick='Estado(\"" . $row['idempleado'] . "\",\"" . $row['estadocuenta'] . "\")'>" . $row['habilitado'] . "</td>" .
                "<td>" . $row['idempleado'] . "</td>
                            <td>" . htmlentities($row['nombreempleado']) . "</td>
                            <td>" . htmlentities($row['nombrearea']) . "</td>
                            <td>" . htmlentities($row['cargo']) . "</td>
                            <td>" . htmlentities($row['login']) . "</td>
                         </tr>";
            }
            echo "</table>";

            //determinando el numero de paginas
            $NroRegistros = $objdatos->NumeroDeRegistrosbus($query_search);
            $PagAnt = $PagAct - 1;
            $PagSig = $PagAct + 1;

            $PagUlt = $NroRegistros / $RegistrosAMostrar;
            // echo $PagUlt;
            //verificamos residuo para ver si llevar� decimales
            $Res = $NroRegistros % $RegistrosAMostrar;

            //si hay residuo usamos funcion floor para que me
            //devuelva la parte entera, SIN REDONDEAR, y le sumamos
            //una unidad para obtener la ultima pagina
            if ($Res > 0)
                $PagUlt = floor($PagUlt) + 1;

            echo "<table align='center'>
		    <tr>
			<td colspan=3 align='center'> <strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong> </td>
		    </tr>
		    <tr>
			<td>
                            <a onclick=\"show_event_search('1')\" style='cursor: pointer;'>Primero</a> </td>";
            //// desplazamiento

            if ($PagAct > 1)
                echo "<td><a onclick=\"show_event_search('$PagAnt')\" style='cursor: pointer;'>Anterior</a></td>";
            if ($PagAct < $PagUlt)
                echo "<td><a onclick=\"show_event_search('$PagSig')\" style='cursor: pointer;'>Siguiente</a></td>";
            echo "<td><a onclick=\"show_event_search('$PagUlt')\" style='cursor: pointer;'>Ultimo</a></td>";
            echo "</tr>
		</table>";
        }
        else {
            $query_search = $query . " AND id_tipo_empleado = (SELECT id FROM mnt_tipo_empleado WHERE codigo = 'LAB') order by t03.idarea, t01.idempleado";

            ////para manejo de la paginacion
            $RegistrosAMostrar = 4;
            $RegistrosAEmpezar = ($_POST['Pag'] - 1) * $RegistrosAMostrar;
            $PagAct = $_POST['Pag'];

            //ENVIANDO A EJECUTAR LA BUSQUEDA!!
            /////LAMANDO LA FUNCION DE LA CLASE 
            $consulta = $objdatos->consultarpagbus($query_search, $RegistrosAEmpezar, $RegistrosAMostrar);
            //echo $consulta;
            //muestra los datos consultados en la tabla
            echo "<table border = 1 align='center' class='estilotabla'>
		      <tr>
			   <td aling='center' aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
			   <td aling='center' aling='center' class='CobaltFieldCaptionTD'> Eliminar</td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Area</td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Codigo Empleado </td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Nombre Empleado </td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Cargo </td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Usuario </td>	   
		     </tr>";
            while ($row = pg_fetch_array($consulta)) {
                echo "<tr>	
                            <td aling='center'> 
                            	<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('" . $row['idempleado'] . "')\">
		 	    </td>" .
                "<td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' " .
                "onclick='Estado(\"" . $row['idempleado'] . "\",\"" . $row['estadocuenta'] . "\")'>" . $row['habilitado'] . "</td>" .
                "<td>" . $row['idempleado'] . "</td>
				<td>" . htmlentities($row['nombreempleado']) . "</td>
				<td>" . htmlentities($row['nombrearea']) . "</td>
				<td>" . htmlentities($row['cargo']) . "</td>
				<td>" . htmlentities($row['login']) . "</td>
			</tr>";
            }
            echo "</table>";

            //determinando el numero de paginas
            $NroRegistros = $objdatos->NumeroDeRegistrosbus($query_search);
            $PagAnt = $PagAct - 1;
            $PagSig = $PagAct + 1;

            $PagUlt = $NroRegistros / $RegistrosAMostrar;

            //verificamos residuo para ver si llevar� decimales
            $Res = $NroRegistros % $RegistrosAMostrar;

            //si hay residuo usamos funcion floor para que me
            //devuelva la parte entera, SIN REDONDEAR, y le sumamos
            //una unidad para obtener la ultima pagina
            if ($Res > 0)
                $PagUlt = floor($PagUlt) + 1;

            echo "<table align='center'>
		       <tr>
			   <td colspan=3 align='center'> <strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong> </td>
			   </tr>
			   <tr>
			   <td>
			   <a onclick=\"show_event('1')\" style='cursor: pointer;'>Primero</a> </td>";
            //// desplazamiento

            if ($PagAct > 1)
                echo "<td> <a onclick=\"show_event('$PagAnt')\" style='cursor: pointer;'>Anterior</a> </td>";
            if ($PagAct < $PagUlt)
                echo "<td> <a onclick=\"show_event('$PagSig')\" style='cursor: pointer;'>Siguiente</a> </td>";
            echo "<td> <a onclick=\"show_event('$PagUlt')\" style='cursor: pointer;'>Ultimo</a></td>";
            echo "</tr>
                </table>";
        }
        break;

    case 8://PAGINACION DE BUSQUEDA
        $query = "SELECT t01.idempleado,
                         t01.idarea,
                         t03.nombrearea,
                         t01.nombreempleado,
                         t02.id AS idcargoempleado,
                         t02.cargo,
                         t01.id_establecimiento AS idestablecimiento,
                         CASE WHEN t04.enabled = true THEN 'Habilitado'
                                ELSE 'Inhabilitado' END AS Habilitado,
                         t04.enabled AS estadocuenta,
                         t04.username AS login 
                  FROM mnt_empleado                        t01
                  INNER JOIN mnt_cargoempleados            t02 ON (t02.id = t01.id_cargo_empleado)
                  INNER JOIN ctl_area_servicio_diagnostico t03 ON (t03.id = t01.idarea)
                  INNER JOIN fos_user_user                 t04 ON (t01.id = t04.id_empleado AND t01.id_establecimiento = t04.id_establecimiento)
                  INNER JOIN ctl_atencion                  t05 ON (t05.id = t03.id_atencion AND t05.codigo_busqueda = 'DCOLAB') WHERE ";

        $ban = 0;
        $ban1 = 0;

        $conEmp = $objdatos->BuscarEmpleado($idempleado, $lugar);
        $ExisEmp = pg_fetch_array($conEmp);
        
        //VERIFICANDO LOS POST ENVIADOS
        if ($ExisEmp[0] >= 1) {//si existe el empleado	
            if (!empty($_POST['idempleado'])) {
                $query .= " t01.idempleado ILIKE '%" . $_POST['idempleado'] . "%' AND";
            }
        }

        if (!empty($_POST['nomempleado'])) {
            $query .= " t01.nombreempleado ILIKE '%" . $_POST['nomempleado'] . "%' AND";
        }

        if (!empty($_POST['idarea'])) {
            $query .= " t01.idarea = " . $_POST['idarea'] . " AND";
        }

        if (!empty($_POST['cargo'])) {
            $query .= " t02.id = " . $_POST['cargo'] . " AND";
        }

        if (!empty($_POST['login'])) {
            $query .= " t04.username ILIKE '%" . $_POST['login'] . "%' AND";
        }

        if ((empty($_POST['cargo'])) and ( empty($_POST['idarea'])) and ( empty($_POST['nomempleado'])) and ( empty($_POST['idempleado'])) and ( empty($_POST['login']))) {
            $ban = 1;
        }


        if ($ban == 0) {
            $query = substr($query, 0, strlen($query) - 4);
            $query_search = $query . " AND t01.id_tipo_empleado = (SELECT id FROM mnt_tipo_empleado WHERE codigo = 'LAB') AND t01.id_establecimiento = $lugar ORDER BY t01.idempleado";
            //  echo $query_search;
            ////para manejo de la paginacion
            $RegistrosAMostrar = 4;
            $RegistrosAEmpezar = ($_POST['Pag'] - 1) * $RegistrosAMostrar;
            $PagAct = $_POST['Pag'];

            //ENVIANDO A EJECUTAR LA BUSQUEDA!!
            /////LAMANDO LA FUNCION DE LA CLASE 
            $consulta = $objdatos->consultarpagbus($query_search, $RegistrosAEmpezar, $RegistrosAMostrar);
            //echo $consulta;
            //muestra los datos consultados en la tabla
            echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
                        <tr>
                            <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
                            <td aling='center' class='CobaltFieldCaptionTD'> Habilitado</td>
                            <td aling='center' class='CobaltFieldCaptionTD'> C&oacute;digo Empleado </td>
                            <td aling='center' class='CobaltFieldCaptionTD'> Nombre Empleado </td>
			    <td aling='center' class='CobaltFieldCaptionTD'> &Aacute;rea</td>
			    <td aling='center' class='CobaltFieldCaptionTD'> Cargo </td>
                            <td aling='center' class='CobaltFieldCaptionTD'> Usuario </td>	    
			</tr>";

            while ($row = pg_fetch_array($consulta)) {
                echo "<tr>	<td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('" . $row['idempleado'] . "')\">
		 	        </td>" .
                "<td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' " .
                "onclick='Estado(\"" . $row['idempleado'] . "\",\"" . $row['estadocuenta'] . "\")'>" . $row['habilitado'] . "</td>" .
                "<td>" . $row['idempleado'] . "</td>
				<td>" . htmlentities($row['nombreempleado']) . "</td>
				<td>" . htmlentities($row['nombrearea']) . "</td>
				<td>" . htmlentities($row['cargo']) . "</td>
				<td>" . htmlentities($row['login']) . "</td>
			</tr>";
            }
            echo "</table>";

            //determinando el numero de paginas
            $NroRegistros = $objdatos->NumeroDeRegistrosbus($query_search);
            $PagAnt = $PagAct - 1;
            $PagSig = $PagAct + 1;

            $PagUlt = $NroRegistros / $RegistrosAMostrar;
            // echo $NroRegistros;
            //verificamos residuo para ver si llevar� decimales
            $Res = $NroRegistros % $RegistrosAMostrar;
            //echo $NroRegistros."  siguiente".$PagSig."  Anterior".$PagAnt;
            //si hay residuo usamos funcion floor para que me
            //devuelva la parte entera, SIN REDONDEAR, y le sumamos
            //una unidad para obtener la ultima pagina
            if ($Res > 0)
                $PagUlt = floor($PagUlt) + 1;

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

            //echo $query_search;
        }
        else {
            $query_search = $query . " AND IdTipoEmpleado='LAB' order by c.IdArea,IdEmpleado";
            ////para manejo de la paginacion
            $RegistrosAMostrar = 4;
            $RegistrosAEmpezar = ($_POST['Pag'] - 1) * $RegistrosAMostrar;
            $PagAct = $_POST['Pag'];

            //ENVIANDO A EJECUTAR LA BUSQUEDA!!
            /////LAMANDO LA FUNCION DE LA CLASE 
            //$obje=new clsLab_Empleados;
            $consulta = $objdatos->consultarpagbus($query_search, $RegistrosAEmpezar, $RegistrosAMostrar);
            //echo $consulta;
            //muestra los datos consultados en la tabla
            echo "<table border = 1 align='center' class='estilotabla'>
		      <tr>
			   <td aling='center' aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
			   <td aling='center' aling='center' class='CobaltFieldCaptionTD'> Eliminar</td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Area</td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Codigo Empleado </td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Nombre Empleado </td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Cargo </td>
			   <td aling='center' class='CobaltFieldCaptionTD'> Usuario </td>	   
		     </tr>";
            while ($row = pg_fetch_array($consulta)) {
                echo "<tr>
                            <td aling='center'> 
					<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
					onclick=\"pedirDatos('" . $row['idempleado'] . "')\">
		 	    </td>" .
                "<td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' " .
                "onclick='Estado(\"" . $row['idempleado'] . "\",\"" . $row['estadocuenta'] . "\")'>" . $row['habilitado'] . "</td>" .
                "<td>" . $row['idempleado'] . "</td>
				<td>" . htmlentities($row['nombreempleado']) . "</td>
				<td>" . htmlentities($row['nombrearea']) . "</td>
				<td>" . htmlentities($row['cargo']) . "</td>
				<td>" . htmlentities($row['login']) . "</td>
			</tr>";
            }
            echo "</table>";

            //determinando el numero de paginas
            $NroRegistros = $objdatos->NumeroDeRegistrosbus($query_search);
            $PagAnt = $PagAct - 1;
            $PagSig = $PagAct + 1;

            $PagUlt = $NroRegistros / $RegistrosAMostrar;
            // echo $PagUlt;
            //verificamos residuo para ver si llevar� decimales
            $Res = $NroRegistros % $RegistrosAMostrar;

            //si hay residuo usamos funcion floor para que me
            //devuelva la parte entera, SIN REDONDEAR, y le sumamos
            //una unidad para obtener la ultima pagina
            if ($Res > 0)
                $PagUlt = floor($PagUlt) + 1;

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
            echo"</tr>
		       </table>";

            //echo $query_search;
        }


        //esto estaba en comentario
        //echo $query_search;
        break;

    case 9:
        //LEER ULTIMO CODIGO  
        $consulta = $objdatos->LeerUltimoCodigo($lugar);

        if ($consulta >= 0 && $consulta <= 9) {
            $codigo = 'LAB000' . $consulta;
        }
        if ($consulta >= 10 && $consulta <= 99) {
            $codigo = 'LAB00' . $consulta;
        }
        if ($consulta >= 100 && $consulta <= 999) {
            $codigo = 'LAB0' . $consulta;
        }
        echo "<input type='text' id='txtidempleado'  name='txtidempleado' value='" . $codigo . "'  />";

        break;
}
?>