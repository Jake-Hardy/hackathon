<?php
require_once('lib/init.php');
// Validate input
if (!isset($_REQUEST['season']) || !isset($_REQUEST['dob'])) {
        exit;
}
// Set vars
$season = $_REQUEST['season'];
$dob = $_REQUEST['dob'];
// Call ajax page
require_once('ajax.php');
// Call function, pass in vars
$duplicates = getDupes($dob,$season);


echo '<table align="center" border="0" cellpadding="3" cellspacing="0" id="dupes">';
echo '<tr><th colspan="2">Duplicates Found</th></tr>';
echo '<tr><td>'.$duplicates->firstName.' '.$duplicates->lastName.'</td><td>'.$duplicates->street.' '.$duplicates->city.', '.$duplicates->state.' '.$duplicates->zip.'</td><td>'.$duplicates->phone.'</td></tr>';
echo '</table>';

echo count($duplicates); 

// Loop through output array and display data
foreach ($duplicates as $_) {
	echo $_->firstName;
}

?>