<?php
require_once('lib/init.php');

// Validate Input 
if (!isset($_GET['appId'])) {
	genError('No application submitted.');
	exit;
}
$id = $_GET['appId'];

// Get All Members
$ret = App::getAllAppMembers($id,$UID);

// Error Check
if (empty($ret)) {
	genError('You do not have permission to view this application.');
	exit;
}

// Start Output
include('includes/header.html');
?>
<h2 id="content-header">Duplicate Report</h2>

<table width="650" cellspacing="0" cellpadding="2">
  <tr><th>Relation</th><th>Duplicated With</th><th>App ID</th><th>Action</th></tr>

<?
// Loop over members
foreach ($ret as $mem) {
	// Check for Dupes
	$dups = App::getDupExistingInfo($id,$mem->lookup_id);
	
	// Output Table
	$x=1;
	foreach ($dups as $item) {
		if ($x == 1) {
			echo '<tr class="row2"><td>'.$mem->firstName.' '.$mem->lastName.'</td>';
		}
		else {
			echo '<tr><td></td>';
		}	
		echo '<td>'.$item->name.'</td><td>'.$item->id.'</td>';

		// Check if user is authorized to view application
		$ret = User::chkUserInAgency($item->aid,$UID);
		if ($ret == 1) {
			echo '<td><a href="viewApp.php?id='.$item->id.'">View Application</a></td></tr>';
		}
		else {
			echo '<td class="inactive">View Application</td></tr>';
		}
		$x++;
	}
}
echo '</table>';
echo '<br /><a href="viewApp.php?id='.$_GET['appId'].'">View Submitted Application</a><br />';

if (isset($_GET['r']) && $_GET['r'] == 'app') {	
	echo '<br /><a href="application.php">Add Another</a>';
}

include('includes/footer.html');
?>
