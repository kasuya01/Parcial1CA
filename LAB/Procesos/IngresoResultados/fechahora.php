<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">  
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"> 
  <head> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Adding a Timepicker to jQuery UI Datepicker</title> 
		
<!--		<link rel="stylesheet" media="all" type="text/css" href="../../../public/css/jquery-ui-timepicker-addon.css" />-->
	<style type="text/css">
			*{ font-size:12px; font-family:verdana; }
			h1 { font-size:22px; }
			.wrapper { width:900px; margin:0px auto; padding:15px;background-color:#eee; }
			input { width:250px; border: 2px solid #CCC; line-height:20px;height:20px; border-radius:10px; padding:5px; }
		</style>
                <link type="text/css" href="../../../public/jquery-ui-1.10.3.custom/css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
                <link type="text/css" href="../../../public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
		
	</head> 
	<body>
		<div class="wrapper">
		
			<h1>Adding a Timepicker to jQuery UI Datepicker</h1>			
			<p>The timepicker addon adds a timepicker to jQuery UI Datepicker, thus the datepicker and slider components (jQueryUI) are required for using any of these. </p>
			<p>In addition all datepicker options are still available through the timepicker addon.</p>
			
			<!--
				HTML Structure
			-->
			<div class="example-container">
				<p><b>Add a simple datetimepicker to jQuery UI's datepicker</b></p>
				<div>
					<input type="text" value="" id="basic_example_1" name="basic_example_1" class="hasDatePicker"/>
				</div>					
			</div>
			
			<div class="example-container">
				<p><b>Add only a timepicker:</b></p>
				<div>
					<input type="text" value="" id="basic_example_2" name="basic_example_2" />
				</div>
			</div>

	 	</div>
		
		<!--loading jQuery and other library-->
<!--		<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>-->
                <script type="text/javascript" src="../../../public/datepicker/dp/jquery-1.11.1.min.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/dp/jquery-ui.min.js"></script>
		<script type="text/javascript" src="../../../public/datepicker/dp/jquery-ui-timepicker-addon.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/dp/jquery-ui-timepicker-addon-i18n.min.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/dp/jquery-ui-sliderAccess.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/dp/script.js"></script>
	</body> 
</html> 
