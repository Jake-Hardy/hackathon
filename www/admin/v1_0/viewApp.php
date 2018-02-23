<?
require_once('../lib/init.php');
require_once('../lib/admin.php');

// Load Smarty 
require_once 'Smarty/Smarty.class.php';

// Validate input
if (!isset($_REQUEST['id']) || !preg_match("/^\d+$/",$_REQUEST['id'])) {
	// output lookup form
	include('../includes/admin_header.html');
	?>
	<div id="content-header"><h2>Admin - Application Lookup</h2></div>
	<div class="center">
		<br />
		<form action="<? echo $_SERVER['PHP_SELF'] ?>" method="get">
		<strong>Application ID: </strong>&nbsp;&nbsp;<input type="text" name="id" size="10" maxlength="10" /><br />
		<br />
		<input type="submit" value="Submit" />
		</form>
	</div>
	<?
	include('../includes/footer.html');
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

// Setup Duplicate Notification
if ($app->dupe) {
        $_SESSION['notice'] = '<span class="status">Duplicated Application</span>';
}

// Get Member Data
list($pri,$sp,$mem) = App::getAppMembers($id);

// Assign values to template
$VAL['appId']['html'] = $id;
$VAL['appId']['label'] = 'Application Id: ';
$VAL['agency']['html'] = $app->name;
$VAL['agency']['label'] = 'Agency: ';
$VAL['user']['html'] = $app->username;
$VAL['user']['label'] = 'Username: ';
$VAL['lastName']['html'] = $pri->lastName;
$VAL['lastName']['label'] = 'Last Name';
$VAL['firstName']['html'] = $pri->firstName;
$VAL['firstName']['label'] = 'First Name';
$VAL['dob']['html'] = $pri->dob;
$VAL['dob']['label'] = 'DOB';
$VAL['age']['html'] = $pri->age;
$VAL['age']['label'] = 'Age';
if ($sp->lastName) {
	$VAL['lastNameSp']['html'] = $sp->lastName;
	$VAL['lastNameSp']['label'] = 'Spouse Last Name';
	$VAL['firstNameSp']['html'] = $sp->firstName;
	$VAL['firstNameSp']['label'] = 'Spouse First Name';
	$VAL['dobSp']['html'] = $sp->dob;
	$VAL['dobSp']['label'] = 'DOB';
	$VAL['ageSp']['html'] = $sp->age;
	$VAL['ageSp']['label'] = 'Age';
}
$VAL['phone']['html'] = $app->phone;
$VAL['phone']['label'] = 'Phone';
$VAL['street']['html'] = $app->street;
$VAL['street']['label'] = 'Address';
$VAL['city']['html'] = $app->city;
$VAL['city']['label'] = 'City';
$VAL['state']['html'] = $app->state;
$VAL['state']['label'] = 'State';
$VAL['zip']['html'] = $app->zip;
$VAL['zip']['label'] = 'Zip';
$VAL['familySize']['html'] = $app->familySize;
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
	for ($x=0;$x<$max;$x++) {
		$VAL['lastName'.$x]['html'] = $mem[$x]->lastName;
		$VAL['firstName'.$x]['html'] = $mem[$x]->firstName;
		$VAL['dob'.$x]['html'] = $mem[$x]->dob;
		$VAL['age'.$x]['html'] = $mem[$x]->age;
		$VAL['sex'.$x]['html'] = $mem[$x]->sex;
	}
}
/* END OTHERS */
$VAL['employer']['html'] = $app->employer;
$VAL['employer']['label'] = 'Employer';
$VAL['income']['html'] = $app->income;
$VAL['income']['label'] = 'Income';
$VAL['expense']['html'] = $app->expenses;
$VAL['expense']['label'] = 'Expenses';
$VAL['ss']['label'] = $app->ss ? 'SS' : '';
$VAL['ssi']['label'] = $app->ssi ? 'SSI' : '';
$VAL['va']['label'] = $app->va ? 'VA' : '';
$VAL['tanf']['label'] = $app->tanf ? 'TANF' : '';
$VAL['fStamp']['label'] = $app->fstamps ? 'F/STAMPS' : '';
$VAL['other']['label'] = $app->other ? 'OTHER' : '';
$VAL['toys']['html'] = $arrToys[$app->toys];
$VAL['toys']['label'] = 'Toys';
$VAL['food']['html'] = $arrFood[$app->food];
$VAL['food']['label'] = 'Food';
$VAL['comments']['html'] = $app->comments;
$VAL['comments']['label'] = 'Comments';
$VAL['tstamp']['html'] = $app->tstamp;
$VAL['tstamp']['label'] = 'Submitted: ';
if($app->tstampUpd) {
	$VAL['tstampUpd']['html'] = $app->tstampUpd;
	$VAL['tstampUpd']['label'] = 'Updated: &nbsp;&nbsp;';
}
// Edit/Delete links
if ($app->season == SEASON) {
	$VAL['action']['html'] = '[<a href="editApp.php?id='.$id.'">Edit</a>] or [<a href="delApp.php?id='.$id.'">Delete</a>]';
	$VAL['action']['label'] = ' this application';
}

// Create the template object
$tpl =& new Smarty;
$tpl->template_dir = '../lib/templates';
$tpl->compile_dir  = '../lib/templates_c';

// assign array with form data
$tpl->assign('form_data', $VAL);

// parse and display the template
include('../includes/header.html');
$tpl->display('viewApp.tpl');
include('../includes/footer.html');
?>
