<?php
require_once "bg.class.php";
$rhc = new RHC("username", "password");

if ($rhc) {
	// get temratures
	print_r($rhc->getDeviceList());
} else {
	die("Error!");
}

?>