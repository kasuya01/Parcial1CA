        <?php session_start();

if (!isset($_SESSION['iduser'])) {
    //SI LA SESION HA EXPIRADO NO SE DEJA REALIZAR ACCIONES AL USUARIO
    echo "ERROR_SESSION";
} else {

    include 'Clases.php';
    /*
     * PROCESOS PARA EL INGRESO DE SOLICITUDES DE ESTUDIOS
     */
    $IdUsuario = $_SESSION['iduser'];
    $IdEstablecimiento = $_SESSION['Lugar'];
    $IpAddress = $_SERVER['REMOTE_ADDR'];
//objetos
    $conn = new ConexionBD;
    $conn->Conectar();
    $puntero = new solicitudes;

    switch ($_GET["Bandera"]) {
        case 100:
            //Carga de especialidades
            $IdEmpleado = $_GET["IdEmpleado"];
            $resp = $puntero->ObtenerEspecialidades($IdEmpleado);

            if ($row = mysql_fetch_array($resp)) {
                $combo = "<select id='IdSubServicio'>";
                do {
                    $combo.="<option value='" . $row["IdSubServicio"] . "'>" . $row["NombreSubServicio"] . "</option>";
                } while ($row = mysql_fetch_array($resp));
                $combo.="</select>";
            } else {

                $combo = "<select id='IdSubServicio'><option value='0'>NO HAY DATOS</option></select>";
            }

            echo $combo;
            break;

        case 1:
            //Obtencion de Datos Generales de Pacientes
            $IdNumeroExp = $_GET["IdNumeroExp"];

            $DatosPaciente = $puntero->DatosPaciente($IdNumeroExp);
            if ($row = mysql_fetch_array($DatosPaciente)) {

                echo $row[0];
            } else {
                echo "ERROR_DATOS";
            }

            break;

        case 2:
            //Ingreso de solicitud y detalle
            $IdNumeroExp = $_GET["IdNumeroExp"];
            $IdEmpleado = $_GET["IdEmpleado"];
            $IdSubServicio = $_GET["IdSubServicio"];
            $IdExamen = $_GET["IdExamen"];



            //Obtener Historial Clinico
            $IdHistorialClinico = $puntero->ObtenerHistorialClinico($IdNumeroExp, $IdEmpleado, $IdSubServicio, $IpAddress);
            if ($IdHistorialClinico == NULL or $IdHistorialClinico == "") {
                /*
                 * INGRESO DE DATOS POR PRIMERA VEZ PARA LA SOLICITUD
                 */
                //Ingreso de HistorialClinico
                $IdHistorialClinico = $puntero->IngresoHistorialClinico($IdNumeroExp, $IdEmpleado, $IdSubServicio, $IdEstablecimiento, $IdUsuario, $IpAddress);


                //Ingreso SoliciudEstudio
                $IdSolicitudEstudio = $puntero->IngresoSolicitudEstudio($IdNumeroExp, $IdHistorialClinico, $IdUsuario, "DCOLAB", $IdEstablecimiento);


                //Ingreso de detalle
                $puntero->IngresoDetalleSolicitudEstudio($IdSolicitudEstudio, $IdExamen);
            } else {
                /*
                 * INGRESO DE SOLICITUDES CUANDO YA EXISTE 
                 */
                if ($_GET["IdSolicitudEstudio"] != 0 and $_GET["IdSolicitudEstudio"] != "") {
                    $IdSolicitudEstudio = $_GET["IdSolicitudEstudio"];
                } else {
                    $IdSolicitudEstudio = $puntero->ObtenerSolicitudEstudio($IdHistorialClinico);
                }

                $Existe = $puntero->ObtenerDetalleSolicitudEstudio($IdSolicitudEstudio, $IdExamen);
                if ($row = mysql_fetch_array($Existe)) {
                }else{
                    $puntero->IngresoDetalleSolicitudEstudio($IdSolicitudEstudio, $IdExamen);
                }
            }

            echo $IdSolicitudEstudio;
            break;
        case 3:
            //Listado de examenes a realizar
            $IdSolicitudEstudio = $_GET["IdSolicitudEstudio"];

            $result = $puntero->ObtenerDetalleSolicitudEstudio($IdSolicitudEstudio);

            $salida = "<table width='100%'>
                     <tr><td colspan=3 align=center>Detalle de Solicitud</td></tr>
                     <tr><td align=center>Codigo</td><td align=center> Examen </td><td align=center> Eliminar </td></tr>
                     <tr><td colspan=3><hr/></td></tr>";
            if ($row = mysql_fetch_array($result)) {
                do {
                    $salida.="<tr>
                                <td align=center>" . $row["IdExamen"] . "</td><td>" . $row["NombreExamen"] . "</td>
                                <td align=center><input type='checkbox' name='examenes' value='" . $row["IdDetalleSolicitud"] . "'/></td>
                              </tr>";
                } while ($row = mysql_fetch_array($result));
            } else {
                $salida.="<tr><td colspan=3 align=center>NO HAY DATOS INGRESADOS.-</td></tr>";
            }

            $salida.="</table>";

            echo $salida;

            break;
        case 4:
            //Eliminar examenes
            $IdDetalleSolicitud = $_GET["IdDetalleSolicitud"];

            $puntero->EliminarExamenes($IdDetalleSolicitud);


            break;
        case 5:
            $IdSolicitudEstudio=$_GET["IdSolicitudEstudio"];
            //echo $IdSolicitudEstudio;
            $puntero->EliminarSolicitud($IdSolicitudEstudio);
            
            break;
    }

    $conn->desconectar();
}
?>
