<?php

	//load the table default definitions DEFAULT!!!
	require("example1_def.inc");
	
	$columns =6; //number of Columns

	
	//Initialize the table class
	$pdf->tbInitialize($columns, true, true);
	
	//set the Table Type
	$pdf->tbSetTableType($table_default_table_type);
	
	
	$aSimpleHeader = array();
	
	//Table Header
	for($i=0; $i<$columns; $i++) {
		$aSimpleHeader[$i] = $table_default_header_type;
		$aSimpleHeader[$i]['TEXT'] = "Column " . ($i + 1) . " text";
		$aSimpleHeader[$i]['WIDTH'] = 30;
	}

	//set the Table Header
	$pdf->tbSetHeaderType($aSimpleHeader);
	
	//Draw the Header
	$pdf->tbDrawHeader();

	//Table Data Settings
	$aDataType = Array();
	for ($i=0; $i<$columns; $i++) $aDataType[$i] = $table_default_data_type;

	$pdf->tbSetDataType($aDataType);

	for ($j=1; $j<9; $j++)
	{
		$data = Array();
		$data[0]['TEXT'] = "Line $j Text 1kqjghsdkhqw ksdhqwkdh kqwhkqh wwekh qkwhe kqwh ekh qwkhe k.qwh ekh qwk.e";
		$data[0]['T_ALIGN'] = "L";//default in the example is C
		$data[1]['TEXT'] = "Line $j Text 2sjhqls qws qwk.dh k.qwhkqhwhqwh qwhqwoqwooqoi qweo;y qwo;eyi hqwe";
		$data[2]['TEXT'] = "Line $j Text 3 qgtwukt gqwuketgukqwgt ekutqwuket qwkuet ukq wtekuqt weukqwt";
		$data[2]['T_ALIGN'] = "R";
		
		$pdf->tbDrawData($data);
	}
	
	//output the table data to the pdf
	$pdf->tbOuputData();
	
	//draw the Table Border
	$pdf->tbDrawBorder();
	

?>
