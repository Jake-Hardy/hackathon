<?
require_once('../lib/init.php');
require_once('../lib/admin.php');

// Validate input
if (!isset($_GET['id']) || !preg_match("/^\d+$/",$_GET['id'])) {
	exit;
}
$id = $_GET['id'];

// Check if in use
$res = Agency::chkAgencyById($id);
if (isset($res) && $res != 0) {
	$_SESSION['notice'] = '<span class="status">Error: Agency in use.</span>';
	header('Location: agency.php');
	exit;
}

// Delete from database
$ret = Agency::delAgency($id);

// Check Return
if ($ret == 1) {
	// OK - Setup notification and redirect
	$_SESSION['notice'] = 'Agency Deleted!';
	header('Location: index.php');
	exit;
}
else {
        // Display error message
        genError(STD_ERR);
        exit;
}
?>
