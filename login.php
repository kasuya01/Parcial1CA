<html>
<head>
<title>Identificaci&oacute;n de Usuarios</title>
<script language='javascript'>
function ValidarContrasena()
{
 var query = unescape(top.location.search.substring(1));
 alert(query );
}
</script>
</style>
<link rel="stylesheet" type="text/css" href="./Themes/Mailbox/Style.css">
<link rel="stylesheet" type="text/css" href="./public/css/corelayout.css">
<link rel="shortcut icon" href="/Laboratorio/Imagenes/favicon.ico" />
</head>
<body>
<center>
   <div id="bannerlogo">
        <img id="img-large" src="../../Laboratorio/Imagenes/header-SUIS.png"  >
        <img id="img-small" src="../../Laboratorio/Imagenes/header-SUIS_small.png" >
   </div>
<!--   <table width="100%" border="0" bgcolor="#FFFFFF">
		<tr>
                   <td colspan="2"><center>
                      <img id="Image1" height="86"  src="../../Laboratorio/Imagenes/header-SUIS.png"  name="Image1"></center>
                   </td>
               </tr>
</table>-->
<!--		<table width="100%" border="0" bgcolor="#FFFFFF">
		<tr>
		    <td align="left"><img id="Image1" style="width: 181px; height: 76px;" height="86" src="./Imagenes/paisanito.png" width="210" name="Image1"></td> 
		    <td style="vertical-align:top">
 			 <h2 align="center" >
              Ministerio de Salud
              <br> Sistema de Informaci&oacute;n de Atenci&oacute;n de Pacientes<br><br>
  <font face="Verdana" size="2" align="center"><font color="#ff0000" size="2"><strong></strong></font></font></h2>
  </td>
  </tr>
</table>-->
</center><br><br><br>
<form action="validar.php" method="post" enctype="multipart/form-data" >
<table align="center" width="100%" border="0" class="MailboxFormTABLE">

  <tr>
        <td bordercolor="#FFFFFF">
            <table width="34%" border="2" align="center"  class="MailboxFormTABLE" cellpadding="4" cellspacing="4">
                <tr>
                    <td class="MailboxFieldCaptionTD" colspan="3" align="center" ><h2>Autenticaci&oacute;n de Usuarios</h2></td>
                </tr>
                <tr>
                    <td width="10%" rowspan="2" class="MailboxDataTD">
                        <input type="image" name="imageField" src="../Laboratorio/Imagenes/usuarios.jpg"></td>
                    <td width="10%" class="MailboxFieldCaptionTD"><strong>Usuario</strong></td>
                    <td width="80%" class="MailboxDataTD" ><input type="text" name="txtlogin" id="txtlogin"></td>
              </tr>
              <tr>
                    <td class="MailboxFieldCaptionTD"><strong>Contraseña</strong></td>
                    <td class="MailboxDataTD"><input type="password" name="txtpassword" id="txtpassword"></td>
              </tr>
              <tr>
                    <td class="MailboxFieldCaptionTD" colspan="3" align="center" ><input type="submit" name="Submit" value="Aceptar" ></td>
              </tr>
            </table>
	</td>
  </tr>
</table>

</form>
<!--<div id="footer" style="bottom: 0; position: absolute; width:80%; margin: 0px auto;">
   <center>
      <img src="../Laboratorio/Imagenes/footer-SUIS.png" width="90%" height="15px"/></center><br>

</div>-->
</body>
</html>
