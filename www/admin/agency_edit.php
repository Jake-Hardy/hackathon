<?php
require_once('../lib/init.php');
require_once('../lib/admin.php');

// Validate input
if (!isset($_REQUEST['id']) || !preg_match("/^\d+$/",$_REQUEST['id'])) {
	exit;
}
$id = $_REQUEST['id'];

// Load original values
$row = Agency::getAgencyById($id);

// Load QuickForm
require_once 'HTML/QuickForm.php';
$form = new HTML_QuickForm('firstForm');

// Method to detect duplicate Agencies
function checkName ($name) { 
	if ($name != $_POST['oldname']) {
		if (Agency::chkName($name)) {
			return 0;
		}
		else {
			return 1;
		}
	}
	else {
		return 1;
	}
}

// Add elements to form
$form->addElement('hidden', 'id', $id);

$form->addElement('hidden', 'oldname', $row['name']);

$form->addElement('text', 'name', 'Name:', array('size' => 25, 'maxlength' => 64, 'value' => $row['name']));
  $form->addRule('name', 'Please enter Name', 'required');
  $form->addRule('name', 'Name must contain 1 - 64 characters.', 'rangelength',array(1,64));
  $form->addRule('name', 'Name is already in use', 'callback', 'checkName');

$fd =& $form->addElement('select', 'food', 'Food Default', $arrFood);
  $fd->setValue($row['foodDefault']);

$hf =& $form->addElement('advcheckbox', 'hidefood', 'Hide Needed From SA' );
$hf->setChecked($row['hideFood']==1);

$td =& $form->addElement('select', 'toys', 'Toys Default', $arrToys);
  $td->setValue($row['toysDefault']);

$ht =& $form->addElement('advcheckbox', 'hidetoys', 'Hide Needed From TFT' );
$ht->setChecked($row['hideToys']==1);

$form->addElement('submit', null, 'Update');

// pre-validation filters
$form->applyFilter('__ALL__', 'trim');

// Validate form 
if ($form->validate()) {
	// post-validation filters
	$form->applyFilter('__ALL__', 'htmlentities');

	// Insert into database
	$ret = Agency::updAgency($id,$form->exportValue('name'),$form->exportValue('food'),$form->exportValue('toys'),is_checked($form->exportValue('hidefood')),is_checked($form->exportValue('hidetoys')));

	// Check Return
	if ($ret == 1) {
	        // OK - Setup notification and redirect
	        $_SESSION['notice'] = 'Agency "'.$form->exportValue('name').'" updated!';
	        header('Location: index.php');
	        exit;
	}
	else {
	        // Display error message
		genError(STD_ERR);
        	exit;
	}
}

// Output the form
include('../includes/admin_header.html');
?>
<div id="content-header">
	<h2>Admin - Edit Agency</h2>
</div>
<br />
<?
$form->display();
include('../includes/footer.html');
?>


