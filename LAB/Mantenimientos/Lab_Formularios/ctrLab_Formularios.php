<?php

session_start();
include_once("clsLab_Formularios.php");
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];

//variables POST
$opcion = $_POST['opcion'];
$objdatos = new clsLab_Formularios;

switch ($opcion) {
    case 1:  //INSERTAR
        $Formulario = $_POST['Formulario'];
        $IdPrograma = $_POST['IdPrograma'];

        if(!$objdatos->verifyUnique($Formulario, $IdPrograma, $lugar)) {

            $IdForm = $objdatos->insertar($Formulario, $usuario);
            if ($IdForm != 0) {
                if ($objdatos->IngFormularioxEstablecimiento($IdForm, $IdPrograma, $lugar, 'H', $usuario) == true) {
                    echo "Registro Agregado";
                } else {
                    echo "No se pudo Agregar el Registro";
                }
            }
        } else {
            echo "Error...\nEl Formulario: $Formulario ya ha sido registrado en el programa seleccionado, por favor ingrese otro formulario.";
        }
        break;
    case 2:  //MODIFICAR
        $IdFormulario = $_POST['IdFormulario'];
        $Formulario = $_POST['Formulario'];
        $IdPrograma = $_POST['IdPrograma'];
        echo "***\n" . $Formulario . "\n***";
        if (($objdatos->actualizar($IdFormulario, $Formulario, $usuario) == true) && ($objdatos->actualizarxestablecimiento($IdFormulario, $IdPrograma, $lugar, $usuario) == true)) {
            echo "\nActualizada Exitosamente";
        } else {
            echo "\nEror...\nNo se pudo Actualizar el Registro";
        }

        break;
    case 3:  //ELIMINAR
        $IdFormulario = $_POST['IdFormulario'];
        //if (($objdatos->eliminar($IdPrograma)==true) && ($Clases->eliminar_labo($IdPrograma)==true)){
        if ($objdatos->eliminar($IdFormulario) == true) {
            echo "Registro Eliminado";
        } else {
            echo "El registro no pudo ser Eliminado ";
        }
        break;
    case 4: // PAGINACION
        $Pag = $_POST['Pag'];
        //para manejo de la paginacion
        $RegistrosAMostrar = 4;
        $RegistrosAEmpezar = ($_POST['Pag'] - 1) * $RegistrosAMostrar;
        $PagAct = $_POST['Pag'];

        //LAMANDO LA FUNCION DE LA CLASE
        $consulta = $objdatos->consultarpag($RegistrosAEmpezar, $RegistrosAMostrar, $lugar);
        if($objdatos->NumeroDeRegistros($lugar) > 0) {
            //muestra los datos consultados en la tabla
            echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
                    <tr>
                        <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
                        <td aling='center' class='CobaltFieldCaptionTD'> Habilitado</td>
                        <!--td class='CobaltFieldCaptionTD'> IdFormulario</td-->
                        <td class='CobaltFieldCaptionTD'> Formulario </td>
                        <td class='CobaltFieldCaptionTD'> Programa de Salud </td>
                    </tr>";

            while ($row = pg_fetch_array($consulta)) {
                echo "<tr>
                        <td aling='center'>
                            <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\"
                            onclick=\"pedirDatos('" . $row[0] . "')\"> </td>
                        <td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' " .
                        "onclick='Estado(\"" . $row[0] . "\",\"" . $row[2] . "\")'>" . $row[3] . "</td>
                        <!--td>$row[0]</td-->
                        <td>" . htmlentities($row[1]) . "</td>
                        <td>" . htmlentities($row[4]) . "</td>
                    </tr>";
            }
            echo" </table>";

            //determinando el numero de paginas
            $NroRegistros = $objdatos->NumeroDeRegistros($lugar);
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
                        <td><a onclick=\"show_event('1')\" style='cursor: pointer;'>Primero</a> </td>";
            //// desplazamiento
            if ($PagAct > 1)
                echo "  <td><a onclick=\"show_event('$PagAnt')\" style='cursor: pointer;'>Anterior</a> </td>";
            if ($PagAct < $PagUlt)
                echo "  <td><a onclick=\"show_event('$PagSig')\" style='cursor: pointer;'>Siguiente</a> </td>";
            echo "      <td><a onclick=\"show_event('$PagUlt')\" style='cursor: pointer;'>Ultimo</a></td>";
            echo "  </tr>
                  </table>";
        } else {
            echo '<center><h2>No existen registros para mostrar</center></h2>';
        }
        break;
    case 5:  //buscar
        $Formulario = $_POST['Formulario'];
        $IdPrograma = $_POST['cmbFormulario'];

        $query = "SELECT t01.id AS idformulario,
                         t01.nombreformulario,
                         t02.condicion,
                         CASE WHEN t02.condicion = 'H' THEN 'Habilitado' ELSE 'Inhabilitado' END AS cond,
                         t03.nombre AS nombreprograma
                  FROM mnt_formularios t01
                  INNER JOIN mnt_formulariosxestablecimiento t02 ON (t01.id = t02.idformulario)
                  INNER JOIN ctl_atencion                    t03 ON (t03.id = t02.id_atencion)
                  WHERE t02.idestablecimiento = $lugar AND";

        //VERIFICANDO LOS POST ENVIADOS
        if (!empty($_POST['Formulario'])) {
            $query .= " t01.nombreformulario ILIKE '%" . $_POST['Formulario'] . "%' AND";
        }

        if ($_POST['cmbFormulario'] != 0) {
            $query .= " t02.id_atencion = " . $_POST['cmbFormulario'] . " AND";
        }

        $query = substr($query, 0, strlen($query) - 3);

        //para manejo de la paginacion
        $RegistrosAMostrar = 4;
        $RegistrosAEmpezar = ($_POST['Pag'] - 1) * $RegistrosAMostrar;
        $PagAct = $_POST['Pag'];

        //LAMANDO LA FUNCION DE LA CLASE
        $consulta = $objdatos->consultarpagbus($query, $RegistrosAEmpezar, $RegistrosAMostrar);
        $NroRegistros = $objdatos->NumeroDeRegistrosbus($query);

        if($NroRegistros > 0) {
            //muestra los datos consultados en la tabla
            echo "<table border = 1 align='center' class='estilotabla'>
                    <tr>
                        <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
                        <td aling='center' class='CobaltFieldCaptionTD'> Habilitado</td>
                        <td class='CobaltFieldCaptionTD'>Formulario</td>
                        <td class='CobaltFieldCaptionTD'>Programa de Salud</td>	   
                    </tr>";

            while ($row = pg_fetch_array($consulta)) {
                echo "<tr>
                        <td aling='center'>
                            <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\"
                            onclick=\"pedirDatos('" . $row[0] . "')\"> </td>
                        <td class='CobaltDataTD' style='text-decoration:underline;cursor:pointer;' " .
                        "onclick='Estado(\"" . $row[0] . "\",\"" . $row[2] . "\")'>" . $row[3] . "</td>
                        <!--td> $row[0] </td-->
                        <td>" . htmlentities($row[1]) . "</td>
                        <td>" . htmlentities($row[4]) . "</td>
                    </tr>";
            }
            echo "</table>";

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
                    <tr><td colspan=3 align='center'> <strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong></td></tr>
                    <tr>
                        <td><a onclick=\"show_event_search('1')\" style='cursor: pointer;'>Primero</a> </td>";
            //// desplazamiento

            if ($PagAct > 1)
                echo "  <td> <a onclick=\"show_event_search('$PagAnt')\" style='cursor: pointer;'>Anterior</a> </td>";
            if ($PagAct < $PagUlt)
                echo "  <td> <a onclick=\"show_event_search('$PagSig')\" style='cursor: pointer;'>Siguiente</a> </td>";
            echo "      <td> <a onclick=\"show_event_search('$PagUlt')\" style='cursor: pointer;'>Ultimo</a></td>";
            echo "  </tr>
                </table>";
        } else {
            echo '<center><h2>No existen registros para mostrar</center></h2>';
        }
        break;
    case 6:

        $idform = $_POST['idform'];
        $cond = $_POST['condicion'];
        echo "CRT " . $cond . "-" . $idform;
        //$resultado=Estado::EstadoCuenta($idexamen,$cond,$lugar);
        $resultado = $objdatos->EstadoCuenta($idform, $cond, $lugar);
        break;
}
?>
