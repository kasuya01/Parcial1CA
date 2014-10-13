<?php 
@session_start();
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
    $querySelect="select id, replace(nombre, '\"', '') as nombre
                from ctl_establecimiento
                where nombre ilike '%$Busqueda%'
                and id != ".$_SESSION["Lugar"];
            $resp=pg_query($querySelect);
    while($row=pg_fetch_array($resp)){
            $Nombre=$row["nombre"];
            $IdEstablecimientoExterno=$row["id"];

    ?>
    <li onselect="this.text.value = '<?php echo $Nombre;?>';$('EstablecimientoExterno').value='<?php echo $IdEstablecimientoExterno;?>';"> 
            <span><?php echo $IdEstablecimientoExterno;?></span>
            <strong><?php echo $Nombre;?></strong>
    </li>
    <?php
    }

    break;

case 3:
    $Busqueda=$_GET['q'];
    $IdEstablecimientoExterno=$_GET['IdEstablecimiento'];
    $numexpediente=$_GET['num_exp'];
    $querySelect="select mer.id as idnumeroexp, numero, (primer_nombre||' '||coalesce(segundo_nombre,'' )||' '||coalesce(tercer_nombre,'')
||' '||primer_apellido||' '||coalesce(segundo_apellido,'')||' '||coalesce(apellido_casada,'')) as paciente_referido, mpr.id as idpacienteref,nombre_madre, nombre_padre, nombre_responsable, fecha_nacimiento, id_sexo, cse.nombre as sexo, primer_nombre, primer_apellido, segundo_nombre, segundo_apellido, tercer_nombre, apellido_casada
from mnt_paciente_referido mpr
join mnt_expediente_referido mer on (mpr.id=mer.id_referido)
join ctl_sexo cse on (cse.id=mpr.id_sexo)
where  ((((array[upper(primer_nombre), upper(segundo_nombre), upper(primer_apellido),upper(segundo_apellido)])::varchar[] && (regexp_split_to_array(upper('$Busqueda'), E'\\\s+'):: varchar[])) = true)
or (concat_ws(' ',primer_apellido,segundo_apellido, apellido_casada,primer_nombre,segundo_nombre, tercer_nombre)) ilike '%$Busqueda%')
and id_establecimiento_origen=$IdEstablecimientoExterno
and numero ilike '%$numexpediente%'
order by 3";
            $resp=pg_query($querySelect);
    if($row=pg_fetch_array($resp)){
    do{
            $Nombre=$row["paciente_referido"];
            $IdNumeroExp=$row["numero"];
            $idnumeroexpediente=$row["idnumeroexp"];
            $NombreMadre=$row["nombre_madre"];
            $NombrePadre=$row["nombre_padre"];
            $NombreResponsable=$row["nombre_responsable"];
            $PrimerNombre=$row["primer_nombre"];
            $SegundoNombre=$row["segundo_nombre"];
            $TercerNombre=$row["tercer_nombre"];
            $PrimerApellido=$row["primer_apellido"];
            $SegundoApellido=$row["segundo_apellido"];
            $CasadaApellido=$row["apellido_casada"];
            $FechaNacimiento=$row["fecha_nacimiento"];
            $Sexo=$row["sexo"];
            $id_sexo=$row["id_sexo"];
            $idpacienteref=$row["idpacienteref"];
            //$id_establecimiento_origen=$row["id_establecimiento_origen"];
            
            $cacular_edad= "select fn_calcular_edad_referido($idpacienteref, 'completa') as edad; ";
            $query= pg_query($cacular_edad);
            $edad=  pg_fetch_array($query);
            $caledad= $edad['edad'];
                        

    ?>
    <li onselect="this.text.value = '<?php echo $Nombre;?>'; 
        $('IdNumeroExp').innerHTML = '<?php echo $IdNumeroExp;?>'; 
        $('NombreMadre1').innerHTML = '<?php echo $NombreMadre;?>';
        $('nec').value = '<?php echo $IdNumeroExp;?>'; 
        $('Paciente').innerHTML = '<?php echo $Nombre;?>';
        $('PrimerNombre').value = '<?php echo $PrimerNombre;?>'; 
        $('SegundoNombre').value = '<?php echo $SegundoNombre;?>'; 
        $('TercerNombre').value = '<?php echo $TercerNombre;?>'; 
        $('PrimerApellido').value = '<?php echo $PrimerApellido;?>';
        $('SegundoApellido').value = '<?php echo $SegundoApellido;?>';
        $('CasadaApellido').value = '<?php echo $CasadaApellido;?>';
        $('FechaNacimiento').value = '<?php echo $FechaNacimiento;?>'; 
        $('Sexo').value = '<?php echo $Sexo;?>'; 
        $('NombreMadre').value = '<?php echo $NombreMadre;?>';
        $('NombrePadre').value = '<?php echo $NombrePadre;?>';
        $('NombreResponsable').value = '<?php echo $NombreResponsable;?>';
        $('id_sexo').value = '<?php echo $id_sexo;?>'; 
        $('idnumeroexpediente').value = '<?php echo $idnumeroexpediente;?>'; 
        $('idpacienteref').value = '<?php echo $idpacienteref;?>'; 
        $('edad').value = '<?php echo $caledad;?>';"> 
            <span><?php echo $IdNumeroExp;?></span>
            <strong><?php echo $Nombre;?></strong>
    </li>
    <?php
    }while($row=pg_fetch_array($resp));
    }else{
    ?>
    <li onselect="this.text.value = 'NO EXISTE'; VerificarExistente();"<?php echo $querySelect;?> > 
            <span>---</span>
            <strong>NO EXISTE, INGRESAR DATOS</strong>
    </li>
    <?php
    }
    break;

case 4:
    $IdEstablecimientoExterno=$_GET['IdEstablecimientoExterno'];
    $LugardeAtencion=$_GET['LugardeAtencion'];
    $PrimerApellido=(empty($_GET['PrimerApellido'])) ? 'NULL' : "'" . pg_escape_string($_GET['PrimerApellido']) . "'";
    //$PrimerApellido=$_GET['PrimerApellido'];
    $SegundoApellido=(empty($_GET['SegundoApellido'])) ? 'NULL' : "'" . pg_escape_string($_GET['SegundoApellido']) . "'";
    
  //  $SegundoApellido=$_GET['SegundoApellido'];
    $CasadaApellido=(empty($_GET['CasadaApellido'])) ? 'NULL' : "'" . pg_escape_string($_GET['CasadaApellido']) . "'";
   // $CasadaApellido=$_GET['CasadaApellido'];
    $PrimerNombre=(empty($_GET['PrimerNombre'])) ? 'NULL' : "'" . pg_escape_string($_GET['PrimerNombre']) . "'";
    //$PrimerNombre=$_GET['PrimerNombre'];
    $SegundoNombre=(empty($_GET['SegundoNombre'])) ? 'NULL' : "'" . pg_escape_string($_GET['SegundoNombre']) . "'";
  //  $SegundoNombre=$_GET['SegundoNombre'];
    $TercerNombre=(empty($_GET['TercerNombre'])) ? 'NULL' : "'" . pg_escape_string($_GET['TercerNombre']) . "'";
    //$TercerNombre=$_GET['TercerNombre'];
    $NombreMadre=(empty($_GET['NombreMadre'])) ? 'NULL' : "'" . pg_escape_string($_GET['NombreMadre']) . "'";
  //  $NombreMadre=$_GET['NombreMadre'];
    $NombrePadre=(empty($_GET['NombrePadre'])) ? 'NULL' : "'" . pg_escape_string($_GET['NombrePadre']) . "'";
   // $NombrePadre=$_GET['NombrePadre'];
    $NombreResponsable=(empty($_GET['NombreResponsable'])) ? 'NULL' : "'" . pg_escape_string($_GET['NombreResponsable']) . "'";
    //$NombreResponsable=$_GET['NombreResponsable'];
    $FechaNacimiento=(empty($_GET['FechaNacimiento'])) ? 'NULL' : "'" . pg_escape_string($_GET['FechaNacimiento']) . "'";
   // echo 'FechaNAc: '.$FechaNacimiento;
    //$FechaNacimiento=$_GET['FechaNacimiento'];
    $Sexo_Name=$_GET['Sexo_Name'];
    $IdNumeroExpRef=(empty($_GET['IdNumeroExpRef'])) ? 'NULL' : "'" . pg_escape_string($_GET['IdNumeroExpRef']) . "'";
   // $IdNumeroExpRef=$_GET['IdNumeroExpRef'];
    $IdNumeroExp=$_GET['IdNumeroExp'];
    $idpacienteref=$_GET['idpacienteref'];
    $iduser=$_SESSION["Correlativo"]; //usuario logeado de mnt_usuario
    if($IdNumeroExp == 0)
    {
        $nextseqpr="SELECT nextval('mnt_paciente_referido_id_seq') as idpacreferido;";
        $querypr= pg_query($nextseqpr);
        $fetchseq= @pg_fetch_array($querypr);
        $seqpr= $fetchseq['idpacreferido'];
        $InsertDP = "INSERT INTO mnt_paciente_referido (id,primer_nombre, segundo_nombre, tercer_nombre, primer_apellido, segundo_apellido, apellido_casada, fecha_nacimiento, nombre_responsable, nombre_madre, nombre_padre, id_sexo, id_user, fecha_registro, asegurado)
values ($seqpr,$PrimerNombre, $SegundoNombre, $TercerNombre, $PrimerApellido, $SegundoApellido, $CasadaApellido, $FechaNacimiento, $NombreResponsable, $NombreMadre, $NombrePadre, $Sexo_Name, $iduser, NOW(), false);";

        $sql=  pg_query($InsertDP);
	//$IdPaciente=mysql_insert_id();
        $nextseqex="SELECT nextval('mnt_expediente_referido_id_seq') as idexpreferido;";
        $queryex= pg_query($nextseqex);
        $fetchseqexp= @pg_fetch_array($queryex);
        $seqexp= $fetchseqexp['idexpreferido'];
        
        $InsertExp = "INSERT into mnt_expediente_referido(id, numero, id_referido, id_establecimiento, id_establecimiento_origen, fecha_creacion, hora_creacion, id_creacion_expediente)
values ($seqexp,$IdNumeroExpRef, $seqpr, $LugardeAtencion,$IdEstablecimientoExterno, current_date, current_time, 1);";
        $sql=  pg_query($InsertExp);
	$IdNumeroExp=$seqexp;
        
    }
    else{
        $updapacref="update mnt_paciente_referido 
            set primer_nombre=$PrimerNombre, 
            segundo_nombre=$SegundoNombre, 
            tercer_nombre=$TercerNombre, 
            primer_apellido=$PrimerApellido, 
            segundo_apellido=$SegundoApellido, 
            apellido_casada=$CasadaApellido, 
            fecha_nacimiento=$FechaNacimiento, 
            nombre_responsable=$NombreResponsable, 
            nombre_madre=$NombreMadre, 
            nombre_padre=$NombrePadre, 
            id_sexo=$Sexo_Name, 
            id_user_mod=$iduser, 
            fecha_registro=NOW()
            where id=$idpacienteref";
        $result=  pg_query($updapacref);
        
        $updaexpref="update mnt_expediente_referido
            set numero=$IdNumeroExpRef,
            fecha_creacion=current_date,
            hora_creacion=current_time
            where id= $IdNumeroExp";
        $res=  pg_query($updaexpref);
    }

    $nextid="select nextval('cit_citas_serviciodeapoyo_idcitaservapoyo_seq')"; 
    $sql=  pg_query($nextid);
    $nextseq=  pg_fetch_array($sql);
    $idnext=$nextseq[0];
    $InsertCit = "INSERT INTO cit_citas_serviciodeapoyo (id, fecha, idusuarioreg, fechahorareg)
                                  VALUES ($idnext,current_date,$iduser,NOW())";
                   //echo 'inse: '.$InsertCit.'<br/>';
                    $queryIns = pg_query($InsertCit);
        echo $IdNumeroExp.'~'.$idnext.'~'.$_GET['IdNumeroExpRef'];

break;

}//fin switch 
?>
