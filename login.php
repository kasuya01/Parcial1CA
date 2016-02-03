<?php include_once 'public/css.php';
include_once 'public/js.php';?>
<html>
   <head>
      <title>Identificaci&oacute;n de Usuarios</title>
      <script language='javascript'>
         function ValidarContrasena()
         {
            var query = unescape(top.location.search.substring(1));
            alert(query);
         }
      </script>
   </style>
<!--   <link rel="stylesheet" type="text/css" href="./Themes/Mailbox/Style.css">
   <link rel="stylesheet" type="text/css" href="./public/css/corelayout.css">-->
   <link rel="shortcut icon" href="/Laboratorio/Imagenes/favicon.ico" />
</head>
<body>
<center>
   <div id="bannerlogo">
      <img id="img-large" src="../../Laboratorio/Imagenes/header-SUIS.png"  >
      <img id="img-small" src="../../Laboratorio/Imagenes/header-SUIS_small.png" >
   </div>

</center><br><br>
<!--<form action="validar.php" method="post" enctype="multipart/form-data" >
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

</form>-->
<!--<div id="footer" style="bottom: 0; position: absolute; width:80%; margin: 0px auto;">
   <center>
      <img src="../Laboratorio/Imagenes/footer-SUIS.png" width="90%" height="15px"/></center><br>

</div>-->
   <div class="form-box" id="login-box">
      <div class="header"></div>
         <form action="validar.php" method="post" enctype="multipart/form-data" class="bs-example bs-example-form">
            <div class="body bg-gray-light" style="background-color: #eaeaec !important">


            <div class="form-group control-group">
               <label for="username" style="text-align:left;">Nombre de Usuario</label>
               <input type="text" class="form-control" value="" required="required" placeholder="Usuario"  name="txtlogin" id="txtlogin">
            </div>


            <div class="form-group control-group">
               <label>Contraseña</label>
               <input type="password" class="form-control" required="required" placeholder="Contraseña" name="txtpassword" id="txtpassword">
            </div>

            <!--<div class="form-group">
                <input type="checkbox" id="remember_me" name="_remember_me" value="on"/>
            Remember me
        </div>-->

         </div>

         <div class="footer">
            <center><button type="submit" id="_submit" name="_submit" class="btn btn-primary btn-block">Aceptar</button></center>
            <!--<p><a href="/app_dev.php/resetting/request" class="text-center">Forgotten password?</a></p>-->
         </div>
      </form>
   </div>
</body>
</html>
