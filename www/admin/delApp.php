<?php
require_once('../lib/init.php');
require_once('../lib/admin.php');

// Validate input
if (!isset($_REQUEST['id']) || !preg_match("/^\d+$/",$_REQUEST['id'])) {
	exit;
}
$id = $_REQUEST['id'];

// Warn admin before proceeding
if (!isset($_POST['warned']) || $_POST['warned'] != 1) {
	include('../includes/admin_header.html');
?>
	<div id="content-header">
		<h2>Admin - Delete Application</h2>
	</div>

	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
	<p>
	<strong>By deleting an application, ALL family information will be lost permanently.</strong><br /><br />
	I accept this warning <input type="checkbox" name="warned" value="1" /><br /><br />
	<input type="hidden" name="id" value="<?php echo $id ?>">
	<input type="submit" value="Delete">&nbsp;&nbsp;
	<input type="button" value="Cancel" onclick="javascript:location.replace('index.php');">
	</p>
	</form>
<?
	include('../includes/footer.html');
	exit;
}

// Delete from database
$ret = App::delApp($id);

// Error Check
if ($ret===false) {
	genError($ret->getMessage());
}
else {
	// Setup notification and redirect
	$_SESSION['notice'] = 'Application Deleted!';
	header('Location: index.php');
}
exit;
