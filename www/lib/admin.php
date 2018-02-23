<?php

// Load common modules
require_once('classes/Agency.php');

// Check Access Level
if (!isset($_SESSION['al']) || $_SESSION['al'] != 2) {
	echo 'Permission Denied.';
	exit;
}

// Setup common drop down values
$arrSt = array(1=>'Active', 2=>'Inactive'); // Status choices
$arrAL = array(1=>'User', 2=>'Admin'); // Access Level choices
?>
