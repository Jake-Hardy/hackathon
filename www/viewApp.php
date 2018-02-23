<?php
require_once('lib/init.php');

// Load Smarty 
require_once 'Smarty/Smarty.class.php';

// Validate input
if (!isset($_REQUEST['id']) || !preg_match("/^\d+$/",$_REQUEST['id'])) {
	genError('Invalid Id');
        exit;
}
$id = $_REQUEST['id'];

// Get application
$app = App::getApplication($id);

// Check if application exists
if ($app == null) {
	genError('The requested application was not found.');
        exit;
}
// Check if user is authorized to view application
$ret = User::chkUserInAgency($app['id'],$UID);
if ($ret != 1) {
	genError('You are not authorized to view this application.');
        exit;
}

// Setup Duplicate Notification
if ($app['dupe']) { 
	$_SESSION['notice'] = '<span class="status">Duplicated Application</span>&nbsp;&nbsp;&nbsp;(<a href="dupReport.php?appId='.$id.'">View Report</a>)'; 
}

// Get Member Data
list($pri,$sp,$mem) = App::getAppMembers($id);

$pri = $pri[0]; //there will only be one primary person
$sp = count($sp) > 0 ? $sp[0] : null; //there might not be a spouse

// Assign values to template
$VAL['appId']['html'] = $id;
$VAL['appId']['label'] = 'Application Id: ';
$VAL['agency']['html'] = $app['name'];
$VAL['agency']['label'] = 'Agency: ';
$VAL['user']['html'] = $app['username'];
$VAL['user']['label'] = 'Username: ';
$VAL['lastName']['html'] = $pri['lastName'];
$VAL['lastName']['label'] = 'Last Name';
$VAL['firstName']['html'] = $pri['firstName'];
$VAL['firstName']['label'] = 'First Name';
$VAL['dob']['html'] = $pri['dob'];
$VAL['dob']['label'] = 'DOB';
$VAL['age']['html'] = $pri['age'];
$VAL['age']['label'] = 'Age';
if ($sp) {
	$VAL['lastNameSp']['html'] = $sp['lastName'];
	$VAL['lastNameSp']['label'] = 'Spouse Last Name';
	$VAL['firstNameSp']['html'] = $sp['firstName'];
	$VAL['firstNameSp']['label'] = 'Spouse First Name';
	$VAL['dobSp']['html'] = $sp['dob'];
	$VAL['dobSp']['label'] = 'DOB';
	$VAL['ageSp']['html'] = $sp['age'];
	$VAL['ageSp']['label'] = 'Age';
}
$VAL['phone']['html'] = $app['phone'];
$VAL['phone']['label'] = 'Phone';
$VAL['street']['html'] = $app['street'];
$VAL['street']['label'] = 'Address';
$VAL['city']['html'] = $app['city'];
$VAL['city']['label'] = 'City';
$VAL['state']['html'] = $app['state'];
$VAL['state']['label'] = 'State';
$VAL['zip']['html'] = $app['zip'];
$VAL['zip']['label'] = 'Zip';
$VAL['familySize']['html'] = $app['familySize'];
$VAL['familySize']['label'] = 'Total # of people living in home: ';
/* OTHERS IN HOME */
$max = sizeof($mem);
if ($max > 0) {
	$VAL['others'] = 1;
	$VAL['lastName0']['label'] = 'Last Name';
	$VAL['firstName0']['label'] = 'First Name';
	$VAL['dob0']['label'] = 'DOB';
	$VAL['age0']['label'] = 'Age';
	$VAL['sex0']['label'] = 'SEX';
	$VAL['wishlist0']['label'] = 'Wishlist';
	for ($x=0;$x<$max;$x++) {
		$VAL['lastName'.$x]['html'] = $mem[$x]['lastName'];
		$VAL['firstName'.$x]['html'] = $mem[$x]['firstName'];
		$VAL['dob'.$x]['html'] = $mem[$x]['dob'];
		$VAL['age'.$x]['html'] = $mem[$x]['age'];
		$VAL['sex'.$x]['html'] = $mem[$x]['sex'];
		$VAL['wishlist'.$x]['html'] = $mem[$x]['wishlist'];
	}
}
/* END OTHERS */
$VAL['employer']['html'] = $app['employer'];
$VAL['employer']['label'] = 'Employer';
$VAL['income']['html'] = $app['income'];
$VAL['income']['label'] = 'Income';
$VAL['expense']['html'] = $app['expenses'];
$VAL['expense']['label'] = 'Expenses';
$VAL['ss']['label'] = $app['ss'] ? 'SS' : '';
$VAL['ssi']['label'] = $app['ssi'] ? 'SSI' : '';
$VAL['va']['label'] = $app['va'] ? 'VA' : '';
$VAL['tanf']['label'] = $app['tanf'] ? 'TANF' : '';
$VAL['fStamp']['label'] = $app['fstamps'] ? 'F/STAMPS' : '';
$VAL['other']['label'] = $app['other'] ? 'OTHER' : '';
$VAL['toys']['html'] = $arrToys[$app['toys']];
$VAL['toys']['label'] = 'Toys';
$VAL['food']['html'] = $arrFood[$app['food']];
$VAL['food']['label'] = 'Food';
$VAL['comments']['html'] = $app['comments'];
$VAL['comments']['label'] = 'Comments';
$VAL['tstamp']['html'] = $app['tstamp'];
$VAL['tstamp']['label'] = 'Submitted: ';
if($app['tstampUpd']) {
        $VAL['tstampUpd']['html'] = $app['tstampUpd'];
        $VAL['tstampUpd']['label'] = 'Updated: &nbsp;&nbsp;';
}

// Edit link
if ($app['season'] == SEASON && !isset($_GET['print']) && !($app['dupe'])) {
	$VAL['action']['html'] = '[<a href="viewApp.php?id='.$id.'&print=1">Print</a>] or [<a href="editApp.php?id='.$id.'">Edit</a>]';
	$VAL['action']['label'] = ' this application';
} elseif ($app['dupe']) { 
	$VAL['action']['html'] = '[<a href="viewApp.php?id='.$id.'&print=1">Print</a>]';
	$VAL['action']['label'] = ' this application. This application is a duplicate, any changes need to go thru United Way.';
} else {
	$VAL['action']['html'] = '[<a href="viewApp.php?id='.$id.'&print=1">Print</a>]';
	/*REMOVED 10/23/2012 DUE TO HASHING ISSUE
	$VAL['action']['html'] .= ' or [<a href="editApp.php?id='.$id.'&oid='.$id.'">Renew</a>]';
	*/
	$VAL['action']['label'] = ' this application';
}

// Create the template object
$tpl = new Smarty;
$tpl->template_dir = 'lib/templates';
$tpl->compile_dir  = 'lib/templates_c';

// assign array with form data
$tpl->assign('form_data', $VAL);

// parse and display the template
if (isset($_GET['print'])) { 
	include('includes/header_p.html'); 
} else {
	include('includes/header.html');
}
$tpl->display('viewApp.tpl');
if (isset($_GET['print'])) {
	include('includes/footer_p.html');
	echo "<script>window.print();</script>";
} else {
	include('includes/footer.html');
}
?>
