<?php
require "countryDataAccess.php";
require "countryDataControl.php";

//only accept parameters we are expecting
if(isset($_POST['Country'])) {
	$validArray = array('Country','cCode','cCity','Currency','Lang','flag_image','dialing_code','timezone','region','source');

	foreach($_POST as $itemData => $itemValue) {
	  	if(in_array($itemData,$validArray)) {
	    	$data[$itemData] = $itemValue;
		}
	}

	$countryDataControl = new countryDataControl($data);

	$countryDataControl->show();

	$countryDataControl->set();

} else {
	$countryClass = new countryDataControl();
	$countryClass->showForm();
}