<?php
include '../../../Conexion/ConexionBD.php';
$conn = new ConexionBD;
$conn->conectar();
$Busqueda = $_GET['q'];

switch ($_GET["Bandera"]) {
    case 1:
        $querySelect = "select le.IdExamen,NombreExamen
                from lab_examenes le
                inner join lab_examenesxestablecimiento lepe
                on lepe.IdExamen=le.IdExamen
                where NombreExamen like '%$Busqueda%'";

        $resp = mysql_query($querySelect);
        while ($row = mysql_fetch_array($resp)) {
            $NombreExamen = $row["NombreExamen"];
            $IdExamen = $row["IdExamen"];
            ?>
            <li onselect="this.text.value = '<?php echo htmlentities($NombreExamen); ?>';$('IdExamen').value='<?php echo $IdExamen; ?>';validar();"> 
                <span><?php echo $IdExamen; ?></span>
                <strong><?php echo htmlentities($NombreExamen); ?></strong>
            </li>
            <?php
        }
        break;

    case 2:
        //empleados
        $querySelect = "select distinct mnt_empleados.IdEmpleado,NombreEmpleado
        from mnt_empleados
        inner join mnt_usuarios
        on mnt_usuarios.IdEmpleado=mnt_empleados.IdEmpleado
                where NombreEmpleado like '%$Busqueda%'
        and IdTipoEmpleado='MED'";

        $resp = mysql_query($querySelect);
        while ($row = mysql_fetch_array($resp)) {
            $NombreExamen = $row["NombreEmpleado"];
            $IdExamen = $row["IdEmpleado"];
            ?>
            <li onselect="this.text.value = '<?php echo htmlentities($NombreExamen); ?>';$('IdEmpleado').value='<?php echo $IdExamen; ?>';CargarCombo('<?= $IdExamen; ?>');"> 
                <span><?php echo $IdExamen; ?></span>
                <strong><?php echo htmlentities($NombreExamen); ?></strong>
            </li>
            <?php
        }

    break;
}
$conn->desconectar();
?>