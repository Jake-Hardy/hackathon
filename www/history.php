<?php
require_once('lib/init.php');
include('includes/header.html');

$UID = $_SESSION['uid'];

// validate pagination
if (isset($_REQUEST['page']) && preg_match("/^\d+$/",$_REQUEST['page'])) {
	$page = $_REQUEST['page'];
}
else {
	$page = 1;
}

if (isset($_GET['sort'])) {
	$_SESSION['sort'] = $_GET['sort'];
}
else {
	$_SESSION['sort'] = 'date';
}

$sort = $_SESSION['sort'];

if ($sort == 'date') {
	$sort = 't_application.id DESC';
}
else {
	$sort = 't_family.lastName ASC';
}

//print($sort);

// Get number of records
$cnt = App::getUsersHistoryCount($UID);

// Get records
$res = App::getUsersHistory($UID,$page,$sort);

?>

<div id="content-header">
	<h2>My History</h2>
</div>
<p class="center">Below is a list of all applications you have submitted for the <?php echo SEASON ?> season.</p>

<table width="600">
<tr><th><a href="history.php?sort=name">Family Name</a></th><th>Family Size</th><th><a href="history.php?sort=date">Submitted</a></th><th>Action</th></tr>
<?php
foreach ($res as $_) {
	echo '<tr><td>'.$_['lastName'].'</td><td>'.$_['familySize'].'</td><td>'.$_['tstamp'].'</td><td><a href="viewApp.php?id='.$_['id'].'">View Application</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="viewApp.php?id='.$_['id'].'&print=1">Print</a></td></tr>';
}
echo '</table>';

if (sizeof($res)<1) {
		echo '<br />Sorry, No applications were found.';
}
// Output Pagination
echo '<br />';
echo genPageLinks($page,$cnt);

include('includes/footer.html');
?>
