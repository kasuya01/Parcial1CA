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
            $IdEstabExt = 0;
        } else if ($cargo == 1) {
            $niv = 2;
            $IdEstabExt = 0;
        } else if (($cargo == 2) or ( $cargo == 8)) {
            $niv = 33;
            $IdEstabExt = 0;
        } else if ($cargo == 7) {
            $niv = 31;
            $IdEstabExt = 0;
        } else if ($cargo == 11) {
            $niv = 4;
            $IdEstabExt = 1305;
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

        $query = "SELECT mnt_empleados.IdEmpleado,mnt_usuarios.estadocuenta, 
                    (CASE estadocuenta
                    WHEN 'H' THEN 'Habilitado'
                    ELSE 'Inhabilitado' END) AS Habilitado,
                    mnt_empleados.IdArea,lab_areas.NombreArea,
                    mnt_empleados.NombreEmpleado,mnt_cargoempleados.IdCargoEmpleado,
                    mnt_cargoempleados.Cargo,mnt_empleados.IdEstablecimiento, mnt_usuarios.login 
                    FROM mnt_empleados
                    INNER JOIN mnt_cargoempleados ON mnt_empleados.IdCargoEmpleado=mnt_cargoempleados.IdCargoEmpleado
                    INNER JOIN lab_areas ON mnt_empleados.IdArea=lab_areas.IdArea
                    INNER JOIN mnt_usuarios ON (mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado 
                    AND mnt_empleados.IdEstablecimiento=mnt_empleados.IdEstablecimiento) WHERE ";



        $conEmp = $objdatos->BuscarEmpleado($idempleado, $lugar);
        //print_r ($conExam);
        $ExisEmp = pg_fetch_array($conEmp);

        $ban = 0;
        $ban1 = 0;
        //echo $ExisEmp[0];
        //VERIFICANDO LOS POST ENVIADOS
        if ($ExisEmp[0] >= 1) {//si existe el empleado	
            // echo $idempleado;
            if (!empty($_POST['idempleado'])) {
                $query .= " mnt_empleados.IdEmpleado='" . $_POST['idempleado'] . "' AND";
            }
        }
        if (!empty($_POST['nomempleado'])) {
            $query .= " mnt_empleados.NombreEmpleado='" . $_POST['nomempleado'] . "' AND";
        }

        if (!empty($_POST['idarea'])) {
            $query .= " mnt_empleados.IdArea='" . $_POST['idarea'] . "' AND";
        }

        if (!empty($_POST['cargo'])) {
            $query .= " mnt_cargoempleados.IdCargoEmpleado='" . $_POST['cargo'] . "' AND";
        }

        if (!empty($_POST['login'])) {
            $query .= " mnt_usuarios.login='" . $_POST['login'] . "' AND";
        }

        if ((empty($_POST['cargo'])) and ( empty($_POST['idarea'])) and ( empty($_POST['nomempleado'])) and ( empty($_POST['idempleado'])) and ( empty($_POST['login']))) {
            $ban = 1;
        }


        if ($ban == 0) {
            $query = substr($query, 0, strlen($query) - 4);
            $query_search = $query . " AND IdTipoEmpleado='LAB' AND Correlativo<>0 AND mnt_empleados.IdEstablecimiento=$lugar order by idempleado";
            //  echo $query_search;
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
            $query = substr($query, 0, strlen($query) - 6);
            $query_search = $query . " AND IdTipoEmpleado='LAB' order by c.IdArea,IdEmpleado";

            //require_once("clsLab_Empleados.php");
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
            echo "</tr>
			  </table>";

            //echo $query_search;
        }


        //esto estaba en comentario
        //echo $query_search;
        break;

    case 8://PAGINACION DE BUSQUEDA
        $query = "SELECT mnt_empleados.IdEmpleado,mnt_empleados.IdArea,lab_areas.NombreArea,mnt_empleados.NombreEmpleado,
                          mnt_cargoempleados.IdCargoEmpleado,mnt_cargoempleados.Cargo,mnt_empleados.IdEstablecimiento,
                          IF(mnt_usuarios.EstadoCuenta='H','Habilitado','Inhabilitado')as Habilitado, mnt_usuarios.EstadoCuenta,
                          mnt_usuarios.login 
                          FROM mnt_empleados 
                          INNER JOIN mnt_cargoempleados ON mnt_empleados.IdCargoEmpleado=mnt_cargoempleados.IdCargoEmpleado
                          INNER JOIN lab_areas ON mnt_empleados.IdArea=lab_areas.IdArea
                          INNER JOIN mnt_usuarios ON (mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado 
                          AND mnt_empleados.IdEstablecimiento= mnt_usuarios.IdEstablecimiento) WHERE ";



        $ban = 0;
        $ban1 = 0;

        $conEmp = $objdatos->BuscarEmpleado($idempleado, $lugar);
        //print_r ($conExam);
        $ExisEmp = pg_fetch_array($conEmp);
        //VERIFICANDO LOS POST ENVIADOS

        if ($ExisEmp[0] >= 1) {//si existe el empleado	
            // echo $idempleado;
            if (!empty($_POST['idempleado'])) {
                $query .= " mnt_empleados.IdEmpleado='" . $_POST['idempleado'] . "' AND";
            }
        }

        if (!empty($_POST['nomempleado'])) {
            $query .= " mnt_empleados.NombreEmpleado='" . $_POST['nomempleado'] . "' AND";
        }

        if (!empty($_POST['idarea'])) {
            $query .= " mnt_empleados.IdArea='" . $_POST['idarea'] . "' AND";
        }

        if (!empty($_POST['cargo'])) {
            $query .= " mnt_cargoempleados.IdCargoEmpleado='" . $_POST['cargo'] . "' AND";
        }

        if (!empty($_POST['login'])) {
            $query .= " mnt_usuarios.login='" . $_POST['login'] . "' AND";
        }

        if ((empty($_POST['cargo'])) and ( empty($_POST['idarea'])) and ( empty($_POST['nomempleado'])) and ( empty($_POST['idempleado'])) and ( empty($_POST['login']))) {
            $ban = 1;
        }


        if ($ban == 0) {
            $query = substr($query, 0, strlen($query) - 4);
            $query_search = $query . " AND IdTipoEmpleado='LAB' AND mnt_empleados.IdEstablecimiento=$lugar order by IdEmpleado";
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
            $query = substr($query, 0, strlen($query) - 6);
            $query_search = $query . " AND IdTipoEmpleado='LAB' order by c.IdArea,IdEmpleado";

            //require_once("clsLab_Empleados.php");
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