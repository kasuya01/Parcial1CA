<?php
   if(isset($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] == '443') {
       $REQUEST_SCHEME = 'https';
   } else {
       $REQUEST_SCHEME = 'http';
   }
   
   $base_url = $REQUEST_SCHEME.'://'.$_SERVER['HTTP_HOST'];
?>
<script src="<?php echo $base_url; ?>/Laboratorio/public/js/jquery.min.js"></script>
<script src="<?php echo $base_url; ?>/Laboratorio/public/package/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
<!--llamado al archivo de funciones del calendario-->
<!--<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/datepicker/jquery-1.11.1.min.js"></script>-->
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/datepicker/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/datepicker/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/datepicker/jquery-ui-timepicker-es.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/datepicker/script.js"></script> 
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/js/funciones_generales.js"></script> 