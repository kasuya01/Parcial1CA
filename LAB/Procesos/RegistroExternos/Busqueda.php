<?php
@session_start();
//include("../indexCitas2.php");
include_once("../../../Conexion/ConexionBD.php");
//$IdEstablecimiento=$_SESSION['IdEstablecimiento'];
$LugardeAtencion=$_SESSION['Lugar'];
$nec=$_GET['nec'];
$idest=$_GET['idest'];
$estabnombre=$_GET['estabnombre'];
$idexpedienteex=$_GET['idexpedienteex'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
//echo 'estab: '.$estabnombre;
//busqueda de establecimiento
  $con = new ConexionBD;
        if ($con->conectar() == true) {
           $query="
                select replace(nombre, '\"', '') as nombre
                from ctl_establecimiento where id=$idest; ";
           $result=  pg_query($query);
           $roweest=  pg_fetch_array($result);
           $nombreest=$roweest['nombre'];

           $query2="select *, extract(year from age(fecha_nacimiento)) AS edad, d.id as idpacienteref
            FROM mnt_paciente_referido d
            JOIN mnt_expediente_referido e on (d.id= e.id_referido)
            join ctl_sexo s on (s.id=d.id_sexo)
            where e.id=$idexpedienteex;";
           $result2=  pg_query($query2);
           $row2=  pg_fetch_array($result2);
           $nombre1=$row2['primer_nombre'];
           $nombre2=$row2['segundo_nombre'];
           $nombre3=$row2['tercer_nombre'];
           $apellido1=$row2['primer_apellido'];
           $apellido2=$row2['segundo_apellido'];
           $apellido3=$row2['tercer_apellido'];
           $apellidocasada=$row2['apellido_casada'];
           $sexo=$row2['nombre'];
           $sexoid=$row2['id_sexo'];
           $fechanaci=$row2['fecha_nacimiento'];
           $nombremama=$row2['nombre_madre'];
           $nombrepapa=$row2['nombre_padre'];
           $nombrerespon=$row2['nombre_responsable'];
           $edad=$row2['edad'];
           $pacienteref=$row2['idpacienteref'];


        }
?>
<html>
<head>
<title>Procedimientos..</title>

      <?php include_once $ROOT_PATH."/public/css.php";?>
      <?php include_once $ROOT_PATH."/public/js.php";?>
      <script type="text/javascript" src="scripts/prototype.js"></script>
      <script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
<!--<script type="text/javascript">
   $(document).ready(function(){
  // we call the function
 VerificarExistente(); //works
});

</script>-->
<script language="javascript" src="Includes/Funciones.js">
</script>
<!--<script type="text/javascript">
   $(document).ready(function() {
         $("#cmb_establecimiento").NombreEstablecimiento
    });
</script>-->
<!-- AUTOCOMPLETAR -->

</head>

<body class="MailboxPageBody" onload="VerificarExistente()"><br><br>
<center>
<!--<strong>
	<h2 align="center"><img class="MailboxInput" style="WIDTH: 57px; HEIGHT: 38px" height="94" src="../../../Iconos/buscar.gif" width="106">
            &nbsp;Busqueda de paciente Externo</h2>
	</strong> -->
   <input type="hidden" id="nombreestab" value="<?php echo $estabnombre ?>"/>
   <input type="hidden" id="nec" value= '<?php echo $nec;?>'>
<!--   <input type="hidden" id="idnumeroexpediente">-->
   <input type="hidden" id="idpacienteref" value="<?php echo $pacienteref;?>">
   <input type="hidden" id="PrimerNombre"  value="<?php echo $nombre1;?>">
   <input type="hidden" id="SegundoNombre" value="<?php echo $nombre2;?>">
   <input type="hidden" id="TercerNombre" value="<?php echo $nombre3;?>">
   <input type="hidden" id="PrimerApellido" value="<?php echo $apellido1;?>">
   <input type="hidden" id="SegundoApellido" value="<?php echo $apellido2;?>">
   <input type="hidden" id="CasadaApellido" value="<?php echo $apellidocasada;?>">
   <input type="hidden" id="FechaNacimiento" value="<?php echo $fechanaci;?>">
   <input type="hidden" id="Sexo" value="<?php echo $sexo;?>">
   <input type="hidden" id="id_sexo" value="<?php echo $sexoid;?>">
   <input type="hidden" id="NombreMadre" value="<?php echo $nombremama;?>">
   <input type="hidden" id="NombrePadre" value="<?php echo $nombrepapa;?>">
   <input type="hidden" id="NombreResponsable" value="<?php echo $nombrerespon;?>">
   <input type="hidden" id="idnumeroexpediente" value="<?php echo $$idexpedienteex;?>">
   <input type="hidden" id="edad" value="<?php echo $edad;?>">
<!--
<div id="Busqueda" >
   <div class='panel panel-primary' style="width: 95%">
               <table border="1" cellpadding="2" style="border:steelblue 1px; border-collapse: collapse" class='table table-bordered table-condensed table-white no-v-border'><thead><tr><td colspan="4"  style='background-color: #428bca; color:#ffffff; text-align:center' ><h3 align="center">
                        <img class="MailboxInput" style="WIDTH: 57px; HEIGHT: 38px" height="94" src="../../../Iconos/buscar.gif" width="106">
                              <span class='glyphicon glyphicon-folder-open'></span>
                        &nbsp;Busqueda de paciente Externo   </h3>
            </td></tr></thead>
                  <tbody><tr>
      <td align="left">Nombre del Establecimiento Externo:&nbsp;</td>
      <td colspan="3">
         <input class="0" maxlength="20" size="80" id="NombreEstablecimiento" value='<?php echo $nombreest;?>'></td>

    </tr>
    <tr>
        <td align="left">Nombre Paciente:&nbsp;</td>
        <td colspan="3"><input class="CobaltInput" maxlength="40" size="80" id="NombrePaciente">
        <input type="hidden" id="nec" value= '<?php echo $nec;?>'>
        </td>
    </tr>
    <tr style="border-bottom-color: #428BCA; border-bottom-style: inset">
        <td colspan="4" align="right">
           <input  type="button" id="ClearInfo" name="ClearInfo" value="NUEVA BUSQUEDA" onClick="javascript:LimpiarConExpe('<?php echo $nec; ?>');">
            <button type='button' align="center" class='btn btn-primary' id='ClearInfo' name="ClearInfo" onClick="javascript:LimpiarConExpe('<?php echo $nec; ?>');"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
           <br>
        </td>
    </tr>
    <tr style="border-style: none"><td><br><br></td></tr>
                  </tbody>
                  <thead>
                     <tr >
       <th style='background-color: #428bca; color:#ffffff; text-align:center' nowrap colspan="4"><h3 align="center">Datos de la Busqueda</h3></th>
    </tr>
    <tr>
        <th align="center"><h4 align="center">Numero Correlativo Interno</h4></th>
        <th align="center"><h4 align="center">Nombre del Paciente</h4></th>
        <th align="center"><h4 align="center">Nombre de la Madre</h4></th>
               </tr> </thead>
               <tbody>
    <tr>
        <td class="CobaltDataTD" align="center"> Enviodatos()
            <a class="CobaltDataLink" href="javascript: VerificarExistente();"><div id="IdNumeroExp"> </div></a></td>
	<td class="CobaltDataTD" align="center">
            <input type="hidden" id="PrimerNombre" >
            <input type="hidden" id="SegundoNombre" >
            <input type="hidden" id="TercerNombre" >
            <input type="hidden" id="PrimerApellido">
            <input type="hidden" id="SegundoApellido">
            <input type="hidden" id="CasadaApellido">
            <input type="hidden" id="FechaNacimiento">
            <input type="hidden" id="Sexo">
            <input type="hidden" id="id_sexo">
            <input type="hidden" id="NombreMadre">
            <input type="hidden" id="NombrePadre">
            <input type="hidden" id="NombreResponsable">
            <input type="hidden" id="idnumeroexpediente">

            <input type="hidden" id="edad">

	<div id="Paciente"><a class="CobaltDataLink"></a></div></td>
	<td class="CobaltDataTD" align="center">
	<div id="NombreMadre1"><a class="CobaltDataLink"></a></div></td>
    </tr></tbody>
<input type="hidden" id="NEC" value="">

</table></div>
</div>-->

<div id="Externo" style="display:block">
   <div class='panel panel-primary' style="width: 95%">
   <table border="0" cellpadding="2" style="border:steelblue 1px; border-collapse: collapse" class='table table-bordered table-condensed table-white no-v-border'>
      <thead>
       <tr>
      <td colspan="4" nowrap style='background-color: #428bca; color:#ffffff; text-align:center'>
         <h3 align="center"><span class='glyphicon glyphicon-list-alt'></span>
            Datos del Paciente</h3></td>
	</tr>

      </thead>
        <tbody>
            <tr>
                <td colspan="4" >
                    <div class="panel panel-info">
                    <div class="panel-heading" style="padding: 2px 15px !important">
                    <h4><b>Expediente <?php echo $nec;?> No encontrado</b>, Por Favor Registrarlo....&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h4> </div>
                     </div>
                </td>
            </tr>
           <tr>
              <td nowrap class="CobaltFieldCaptionTD"><strong>Establecimiento:<font color="#ff0000">*</font> </strong></td>
              <td class="CobaltDataTD" colspan="3"><input type="text" readonly="readonly" id='nombrestablecimiento' value='<?php echo $nombreest;?>' style="WIDTH: 527px"/></td>
           </tr>
      <tr>
            <td nowrap class="CobaltFieldCaptionTD"><strong>Numero de Expediente de Referencia:<font color="#ff0000">*</font> </strong></td>
            <td class="CobaltDataTD" colspan="4">
               <input class="CobaltInput" maxlength="80" size="21" id="NumeroExpediente_Referencia" style="WIDTH: 190px;" value="<?php echo $nec;?>"></td>
            </tr>
     <tr>
      <td nowrap class="CobaltFieldCaptionTD"><strong>Nombre:<font color="#ff0000">*</font> </strong></td>
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="PrimerNombre_Name" id="PrimerNombre_Name" placeholder="Primer Nombre" autofocus></td>
<!--      <td nowrap class="CobaltFieldCaptionTD"><strong>Segundo Nombre: <font color="#ff0000">*</font> </strong></td> -->
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="SegundoNombre_Name" id="SegundoNombre_Name" placeholder="Segundo Nombre"></td>
<!--      <td nowrap class="CobaltFieldCaptionTD"><strong>Tercer Nombre: <font color="#ff0000">*</font> </strong></td> -->
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="TercerNombre_Name" id="TercerNombre_Name" placeholder="Tercer Nombre"></td>
    </tr>
    <tr>
      <td nowrap class="CobaltFieldCaptionTD"><strong>Apellidos:<font color="#ff0000">*</font> </strong></td>
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="PrimerApellido_Name" id="PrimerApellido_Name" placeholder="Primer Apellido"></td>

      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="SegundoApellido_Name" id="SegundoApellido_Name" placeholder="Segundo Apellido"></td>
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="CasadaApellido_Name" id="CasadaApellido_Name" placeholder="Apellido de Casada"></td>
    </tr>
    <tr>
       <td nowrap class="CobaltFieldCaptionTD"><strong>Edad <font color="#ff0000">*</font> </strong></td>
      <td class="CobaltDataTD">
	  <input onBlur="esEdadValida(this)" onkeyup="limpiarcampoedad('Edad', this)" id="Edad" class="MailboxInput" maxlength="3" style="width:50px;" size="3" min="0" max="120">
          <input type="hidden" id="Edadini">
          <input id="FechaNacimiento_Name" maxlength="10" class="MailboxInput" style="width:135px;" size="10" disabled="disabled" style="">

      <td nowrap class="CobaltFieldCaptionTD" align="center"><strong>Sexo: <font color="#ff0000">*</font></strong></td>
      <td class="CobaltDataTD">
      <select id="Sexo_Name" class="form-control height" style="WIDTH: 190px;">
                  <option value="0" selected>Seleccionar</option>
                  <option value="1">Masculino</option>
		  <option value="2">Femenino</option>
        </select></td>
	</tr>

	<tr>
      <td nowrap class="CobaltFieldCaptionTD"><strong>Nombre de la Madre: </strong></td>
      <td class="CobaltDataTD" colspan="4">
        <input class="CobaltInput" maxlength="80" size="21" id="NombreMadre_Name" style="WIDTH: 527px;"></td>
        </tr>
        <tr>
      <td nowrap class="CobaltFieldCaptionTD"><strong>Nombre del Padre:</strong></td>
      <td class="CobaltDataTD" colspan="4">
        <input class="CobaltInput" maxlength="80" size="21" id="NombrePadre_Name" style="WIDTH: 527px;"></td>
        </tr>
           <tr>
      <td nowrap class="CobaltFieldCaptionTD"><strong>Nombre Responsable:</strong></td>
      <td class="CobaltDataTD" colspan="4">
        <input class="CobaltInput" maxlength="80" size="21" id="NombreResponsable_Name" style="WIDTH: 527px;"></td>
        </tr>
  <tr>
    <td colspan="4" align="center" nowrap class="MailboxFooterTD">
    <br/>
     <button type='button' align="center" class='btn btn-primary' id='GetInfor' name="GetInfo" onClick="javascript:GuardarInformacionExterna();"><span class='glyphicon glyphicon-floppy-disk'></span> Guardar Datos</button>
     <button type='button' align="center" class='btn btn-primary' id='ClearInfo' name="ClearInfo" onClick="javascript:LimpiarConExpe('<?php echo $nec; ?>');"><span class='glyphicon glyphicon-refresh'></span> Nueva Búsqueda</button>
<!--    <input type="button" id="GetInfor" name="GetInfo" value=" GUARDAR DATOS " onClick="javascript:GuardarInformacionExterna();">-->
	<!-- <input type="text" id="Establecimiento" value="<?php// echo $IdEstablecimiento; ?>">-->
	<input type="hidden" id="LugarAtencion" value="<?php echo $LugardeAtencion; ?>">
	<input type="hidden" id="EstablecimientoExterno" value="<?php echo $idest; ?>">

	<!--<input type="button" id="ClearInfo" name="ClearInfo" value="NUEVA BUSQUEDA" onClick="javascript:LimpiarConExpe('<?php //echo $nec; ?>');">-->
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;<div id="Datos"></div></td>
  </tr></tbody>
   </table></div></div>

<script>
	   new Autocomplete('NombreEstablecimiento', function() {

		   return 'respuesta.php?Bandera=2&ctr=1&q=' + this.value;
	   });
            new Autocomplete('NombrePaciente', function() {
		var IdEstablecimiento = document.getElementById('EstablecimientoExterno').value;
		var num_exp = document.getElementById('nec').value;
            //    alert (num_exp)
                if(IdEstablecimiento == '')
                {
                  alert("  Seleccione un establecimiento por favor")
                }
                else{
              alert('Estab'+IdEstablecimiento+ ' thisval: '+this.value+' num_exp: '+num_exp);
                return 'respuesta.php?Bandera=3&ctr=1&q='+this.value+'&IdEstablecimiento='+IdEstablecimiento+'&num_exp='+num_exp;
                }
	   });


	</script>


</body>
</center>
</html>
