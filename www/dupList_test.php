<?php
require_once('lib/init.php');
include('includes/header.html');

// validate pagination
if (isset($_REQUEST['page']) && preg_match("/^\d+$/",$_REQUEST['page'])) {
	$page = $_REQUEST['page'];
}
else {
	$page = 1;
}

// validate Input
if (!isset($_REQUEST['id']) || !preg_match("/^\d+$/",$_REQUEST['id'])) {
	genError('Invalid ID');
	exit;
}
$id = $_REQUEST['id'];

if (!isset($_REQUEST['cnt']) || !is_numeric($_REQUEST['id'])) {
	genError('Invalid Count');
	exit;
}
$cnt = $_REQUEST['cnt'];

// Check if user is authorized to view report
$ret = User::chkUserInAgency($id,$UID);
if ($ret != 1) {
	genError('You are not authorized to view this report.');
	exit;
}

// Get records
$res = App::getDupeApps_test($id,$page);
?>

<div id="content-header">
	<h2>Duplicate Applications</h2>
</div>
<p class="center">Below is a list of your agency's duplicated applications for the <?php echo SEASON ?> season.</p>

<table width="600">
<tr><th>Family Name</th><th>Family Size</th><th>Submitted</th><th>Action</th></tr>
<?
foreach ($res as $_) {
	echo '<tr><td>'.$_['lastName'].'</td><td>'.$_['familySize'].'</td><td>'.$_['tstamp'].'</td><td><a href="viewApp.php?id='.$_['id'].'">View Application</a></td></tr>';
}
echo '</table>';

// Output Pagination
echo '<br />';
echo genPageLinks($page,$cnt);

include('includes/footer.html');
?>
