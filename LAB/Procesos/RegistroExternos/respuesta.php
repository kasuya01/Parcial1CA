<?php session_start();
//include("../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBD.php");
$con = new ConexionBD;
$con->conectar();
//$Control=$_GET['ctr'];

switch($_GET["Bandera"]){

case 1:

break;

case 2:
    $Busqueda=$_GET['q'];
    $querySelect="select IdEstablecimiento,Nombre
                    from mnt_establecimiento
                    where Nombre like '%$Busqueda%'
                    and IdEstablecimiento <> ".$_SESSION["Lugar"];
            $resp=mysql_query($querySelect);
    while($row=mysql_fetch_array($resp)){
            $Nombre=$row["Nombre"];
            $IdEstablecimientoExterno=$row["IdEstablecimiento"];

    ?>
    <li onselect="this.text.value = '<?php echo htmlentities($Nombre);?>';$('EstablecimientoExterno').value='<?php echo $IdEstablecimientoExterno;?>';"> 
            <span><?php echo $IdEstablecimientoExterno;?></span>
            <strong><?php echo htmlentities($Nombre);?></strong>
    </li>
    <?php
    }

    break;

case 3:
    $Busqueda=$_GET['q'];
    $IdEstablecimientoExterno=$_GET['IdEstablecimiento'];
    $querySelect="
    SELECT DISTINCT mnte.IdNumeroExp, CONCAT_WS(' ',mntd.PrimerApellido,mntd.SegundoApellido,mntd.PrimerNombre,mntd.SegundoNombre, mntd.TercerNombre)AS Paciente, 
    NombreMadre, PrimerNombre, PrimerApellido, FechaNacimiento, Sexo
    FROM mnt_expediente mnte 
    INNER JOIN mnt_datospaciente mntd ON mntd.IdPaciente=mnte.IdPaciente 
    INNER JOIN cit_citasxserviciodeapoyo csp ON mnte.IdNumeroExp = csp.IdNumeroExp
    WHERE csp.IdEstablecimientoExterno = $IdEstablecimientoExterno
    AND (CONCAT_WS(' ',mntd.PrimerApellido,mntd.SegundoApellido,mntd.PrimerNombre,mntd.SegundoNombre, mntd.TercerNombre) LIKE '%$Busqueda%' OR mnte.IdNumeroExp LIKE '%$Busqueda%') 
    AND mnte.IdNumeroExp NOT LIKE '%*%' 
    AND mnte.IdNumeroExp NOT LIKE '%-%'";
    /*
    select mnte.IdNumeroExp, concat_ws(' ',mntd.PrimerApellido,mntd.SegundoApellido,mntd.PrimerNombre,mntd.SegundoNombre, mntd.TercerNombre)as Paciente, NombreMadre
    from mnt_expediente mnte
    inner join mnt_datospaciente mntd on mntd.IdPaciente=mnte.IdPaciente
    where (concat_ws(' ',mntd.PrimerApellido,mntd.SegundoApellido,mntd.PrimerNombre,mntd.SegundoNombre, mntd.TercerNombre) like '%$Busqueda%'
    or IdNumeroExp like '%$Busqueda%')
    and mnte.IdNumeroExp not like '%*%' and mnte.IdNumeroExp not like '%-%' 
     */

    //echo $querySelect;
            $resp=mysql_query($querySelect);
    if($row=mysql_fetch_array($resp)){
    do{
            $Nombre=$row["Paciente"];
            $IdNumeroExp=$row["IdNumeroExp"];
            $NombreMadre=$row["NombreMadre"];
            $PrimerNombre=$row["PrimerNombre"];
            $PrimerApellido=$row["PrimerApellido"];
            $FechaNacimiento=$row["FechaNacimiento"];
            $Sexo=$row["Sexo"];

    ?>
    <li onselect="this.text.value = '<?php echo htmlentities($Nombre);?>'; $('IdNumeroExp').innerHTML = '<?php echo $IdNumeroExp;?>'; $('NombreMadre1').innerHTML = '<?php echo $NombreMadre;?>';$('NEC').value = '<?php echo $IdNumeroExp;?>'; $('Paciente').innerHTML = '<?php echo htmlentities($Nombre);?>';
        $('PrimerNombre').value = '<?php echo $PrimerNombre;?>'; $('PrimerApellido').value = '<?php echo $PrimerApellido;?>';$('FechaNacimiento').value = '<?php echo $FechaNacimiento;?>'; $('Sexo').value = '<?php echo $Sexo;?>'; $('NombreMadre').value = '<?php echo $NombreMadre;?>';"> 
            <span><?php echo $IdNumeroExp;?></span>
            <strong><?php echo htmlentities($Nombre);?></strong>
    </li>
    <?php
    }while($row=mysql_fetch_array($resp));
    }else{
    ?>
    <li onselect="this.text.value = 'NO EXISTE'; VerificarExistente();"> 
            <span>---</span>
            <strong>NO EXISTE, INGRESAR DATOS</strong>
    </li>
    <?php
    }
    break;

case 4:
    /*
     * &IdEstablecimientoExterno="+IdEstablecimientoExterno+"&Establecimiento="+IdEstablecimiento+
     * "&PrimerApellido="+PrimerApellido+"&PrimerNombre="+PrimerNombre+"&FechaNacimiento="+FechaNacimiento+
     * "&Sexo_Name="+Sexo_Name+"&NombreMadre="+NombreMadre+"&IdNumeroExpRef="+IdNumeroExpRef
     */
    //echo "estas en el case 4";
    // CAPTURA DE LOS DATOS 
    $IdEstablecimientoExterno=$_GET['IdEstablecimientoExterno'];
    $LugardeAtencion=$_GET['LugardeAtencion'];
    $PrimerApellido=$_GET['PrimerApellido'];
    $PrimerNombre=$_GET['PrimerNombre'];
    $NombreMadre=$_GET['NombreMadre'];
    $FechaNacimiento=$_GET['FechaNacimiento'];
    $Sexo_Name=$_GET['Sexo_Name'];
    $IdNumeroExpRef=$_GET['IdNumeroExpRef'];
    $IdNumeroExp=$_GET['IdNumeroExp'];
    $iduser=$_SESSION['iduser']; //usuario logeado de mnt_usuario
    
    // SI ES PACIENTE EXTERNO SE REGISTRAR AL PACIENTE EN LAS mnt_datospaciente y en mnt_expediente
    // CON EL IDUSUARIO = 100 YA QUE NO SE CREO EN ESDOMED SINO EN OTRO SERVICO EN ESTE CASO LABORATORIO.
    if($IdNumeroExp == 0)
    {
        $InsertDP = "INSERT INTO mnt_datospaciente
            (PrimerApellido, PrimerNombre, Sexo, FechaNacimiento, NombreMadre, 
             IdUsuarioReg, FechaHoraReg)
        VALUES 
            ('$PrimerApellido','$PrimerNombre',$Sexo_Name,'$FechaNacimiento','$NombreMadre',
            100,NOW())";
        mysql_query($InsertDP);
	$IdPaciente=mysql_insert_id();
        
        $InsertExp = "INSERT INTO mnt_expediente
            (IdNumeroExp, IdPaciente, CreadoEn, Estado, IdUsuarioReg, 
            FechaHoraReg, IdEstablecimiento)
        VALUES 
            ('$IdPaciente',$IdPaciente,0,1,100,
            NOW(),$LugardeAtencion)";
        mysql_query($InsertExp);
	$IdNumeroExp=$IdPaciente;
        
    }        

        

 // SI EL PACIENTE ESTA REGISTRADO SOLO AGREGAR LA CITA EN cit_citasxserviciodeapoyo
        $InsertCit = "INSERT INTO cit_citasxserviciodeapoyo
            (Fecha, IdUsuarioReg, FechaHoraReg, IdNumeroExp, IdEstablecimiento, IdNumeroExpExterno, IdEstablecimientoExterno)
        VALUES 
            (CURDATE(),$iduser,NOW(), '$IdNumeroExp',$LugardeAtencion, '$IdNumeroExpRef', $IdEstablecimientoExterno)";
        //$queryIns = 
        mysql_query($InsertCit);
	$IdCitaServApoyo=mysql_insert_id();
    
        echo $IdNumeroExp.'~'.$IdCitaServApoyo;

break;

}//fin switch 
?>
