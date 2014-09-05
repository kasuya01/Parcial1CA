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