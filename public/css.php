<?php
   if(isset($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] == '443') {
       $REQUEST_SCHEME = 'https';
   } else {
       $REQUEST_SCHEME = 'http';
   }
   
   $base_url = $REQUEST_SCHEME.'://'.$_SERVER['HTTP_HOST'];
?>

<link href="<?php echo $base_url; ?>/Laboratorio/public/css/corelayout.css" rel="stylesheet">
<link href="<?php echo $base_url; ?>/Laboratorio/public/package/bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">

<link type="text/css" href="<?php echo $base_url; ?>/Laboratorio/public/jquery-ui-1.10.3.custom/css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
<link type="text/css" href="<?php echo $base_url; ?>/Laboratorio/public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />