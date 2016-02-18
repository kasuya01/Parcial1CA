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
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/package/AdminLTE/plugins/daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/package/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/package/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!--llamado al archivo de funciones del calendario-->
<!--<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/datepicker/jquery-1.11.1.min.js"></script>-->
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/datepicker/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/datepicker/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/datepicker/jquery-ui-timepicker-es.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/datepicker/script.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/js/funciones_generales.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/package/select2-3.4.5/select2.min.js"></script>

<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/package/lou-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/package/quicksearch/jquery.quicksearch.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/package/AdminLTE/dist/js/app.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/Laboratorio/public/package/AdminLTE/plugins/fullcalendar/fullcalendar.min.js"></script>
