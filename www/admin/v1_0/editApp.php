<?
require_once('../lib/init.php');
require_once('../lib/admin.php');

// VALIDATE INPUT
if (!isset($_REQUEST['id']) || !preg_match("/^\d+$/",$_REQUEST['id'])) {
	genError('Invalid Id');
        exit;
}
$id = $_REQUEST['id'];

// Load QuickForm and Smarty
require_once 'HTML/QuickForm.php';
require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
require_once 'Smarty/Smarty.class.php';
$form = new HTML_QuickForm('firstForm');

// Get options for agency 
foreach (Agency::getAgencies() as $_) {
	$arrAN[$_->id] = $_->name;
}

// Get application
$app = App::getApplication($id);

// Check if application exists
if ($app == null) {
	genError('The requested application was not found.');
        exit;
}

// Get Member Data
list($pri,$sp,$mem) = App::getAppMembersEdit($id);

// Prevent warnings later
$mem = array_pad($mem, 12, null);

// Add elements to form
$form->addElement('hidden', 'id', $id);
 
$an =& $form->addElement('select', 'agency', 'Agency', $arrAN);
  $an->setValue($app->id);
  $form->addRule('agency', 'Please select Agency', 'required');
  $form->addRule('agency', 'Agency ID must be less than 6 digits.', 'maxlength',5);
  $form->addRule('agency', 'Agency ID must only contain numbers.', 'numeric');

$form->addElement('text', 'lastName', 'Last Name', array('value' => $pri->lastName, 'size' => 25, 'maxlength' => 64, 'onchange' => "calcNum(".CNT_OTHERS.")"));
  $form->addRule('lastName', 'Please enter Last Name', 'required', null, null);
  $form->addRule('lastName', 'Last Name must be less than 65 characters.', 'maxlength',64);

$form->addElement('text', 'firstName', 'First Name', array('value' => $pri->firstName, 'size' => 25, 'maxlength' => 64));
  $form->addRule('firstName', 'Please enter First Name', 'required', null, null);
  $form->addRule('firstName', 'First Name must be less than 65 characters.', 'maxlength',64);

$form->addElement('text', 'dob', 'DOB', array('value' => $pri->dob, 'size' => 10, 'maxlength' => 10));
  $form->addRule('dob', 'Please enter DOB', 'required');
  $form->addRule('dob', 'Please enter Valid Date', 'callback', 'chkDate');

$pssn =& $form->addElement('text', 'ssn', 'SSN', array('size' => 9, 'maxlength' => 9));
  $form->addRule('ssn', 'Please enter SSN', 'required');
  $form->addRule('ssn', 'SSN must be exactly 9 digits', 'rangelength', array(9,9));
if(!isset($_REQUEST['ssn']) || $_REQUEST['ssn'] != '*********') {
  $form->addRule('ssn', 'SSN must only contain numbers', 'numeric');
}
  if ($pri->data) { 
	if (!$_POST) { $pssn->setValue('*********'); }
	$form->addElement('hidden', 'oldSsn', $pri->data);
	$form->addElement('hidden', 'fid', $pri->fid);
	$form->addElement('hidden', 'lid', $pri->lid);  
  } 

$form->addElement('text', 'lastNameSp', 'Spouse Last Name', array('value' => $sp->lastName, 'size' => 25, 'maxlength' => 64, 'onchange' => "calcNum(".CNT_OTHERS.")"));
  $form->addRule('lastNameSp', 'Last Name must be less than 65 characters.', 'maxlength',64);

$form->addElement('text', 'firstNameSp', 'Spouse First Name', array('value' => $sp->firstName, 'size' => 25, 'maxlength' => 64));
  $form->addRule('firstNameSp', 'First Name must be less than 65 characters.', 'maxlength',64);

$form->addElement('text', 'dobSp', 'DOB', array('value' => $sp->dob, 'size' => 10, 'maxlength' => 10));
  $form->addRule('dobSp', 'Please enter Valid Date', 'callback', 'chkDate');

$sssn =& $form->addElement('text', 'ssnSp', 'SSN', array('size' => 9, 'maxlength' => 9));
  $form->addRule('ssnSp', 'SSN must be exactly 9 digits', 'rangelength', array(9,9));
if(!isset($_REQUEST['ssnSp']) || $_REQUEST['ssnSp'] != '*********') {
  $form->addRule('ssnSp', 'SSN must only contain numbers', 'numeric');
} 
  if ($sp->data) {
	if (!$_POST) { $sssn->setValue('*********'); }
	$form->addElement('hidden', 'oldSsnSp', $sp->data);
	$form->addElement('hidden', 'fidSp', $sp->fid);
	$form->addElement('hidden', 'lidSp', $sp->lid);
  }

$form->addElement('advcheckbox', 'delSp', 'Del');

$form->addElement('text', 'phone', 'Phone', array('value' => $app->phone, 'size' => 15, 'maxlength' => 20));
  $form->addRule('phone', 'Phone must be less than 21 characters.', 'maxlength',20);

$form->addElement('text', 'street', 'Address', array('value' => $app->street, 'size' => 25, 'maxlength' => 128));
  $form->addRule('street', 'Please enter Street', 'required');
  $form->addRule('street', 'Street must be less than 129 characters.', 'maxlength',128);

$form->addElement('text', 'city', 'City', array('value' => $app->city, 'size' => 25, 'maxlength' => 64));
  $form->addRule('city', 'Please enter City', 'required');
  $form->addRule('city', 'Street must be less than 65 characters.', 'maxlength',64);

$st =& $form->addElement('select', 'state', 'State', array('' => 'State', 'GA' => 'Georgia', 'SC' => 'South Carolina'));
  $form->addRule('state', 'Please enter State', 'required');
  $form->addRule('state', 'State code must be exactly 2 characters.', 'rangelength',array(2,2));
    if(!$_POST) { $st->setValue($app->state); }

$form->addElement('text', 'zip', 'Zip', array('value' => $app->zip, 'size' => 10, 'maxlength' => 10));
  $form->addRule('zip', 'Please enter Zip', 'required');
  $form->addRule('zip', 'Zip must be less than 11 characters.', 'maxlength',10);

$form->addElement('text', 'familySize', 'Total # of people living in home', array('value' => $app->familySize, 'size' => 3, 'maxlength' => 2, 'disabled' => 'disabled'));

/* OTHERS IN HOME */
for ($x=0;$x<CNT_OTHERS;$x++) {
	$form->addElement('text', 'lastName'.$x, 'Last Name', array('value' => $mem[$x]->lastName, 'size' => 24, 'maxlength' => 64, 'onchange' => "calcNum(".CNT_OTHERS.")"));
	  $form->addRule('lastName'.$x, 'Last Name must be less than 65 characters.', 'maxlength',64);

	$form->addElement('text', 'firstName'.$x, 'First Name', array('value' => $mem[$x]->firstName, 'size' => 25, 'maxlength' => 64));
	  $form->addRule('firstName'.$x, 'First Name must be less than 65 characters.', 'maxlength',64);

	$form->addElement('text', 'dob'.$x, 'DOB', array('value' => $mem[$x]->dob, 'size' => 10, 'maxlength' => 10));
  	  $form->addRule('dob'.$x, 'Please enter Valid Date', 'callback', 'chkDate');

	$ars[$x] =& $form->addElement('text', 'ssn'.$x, 'SSN', array('size' => 9, 'maxlength' => 9));
	  $form->addRule('ssn'.$x, 'SSN must be exactly 9 digits', 'rangelength', array(9,9));
	if(!isset($_REQUEST['ssn'.$x]) || $_REQUEST['ssn'.$x] != '*********') {
	  $form->addRule('ssn'.$x, 'SSN must only contain numbers', 'numeric');
	}
          if($mem[$x]->data) { 
		if(!$_POST) { $ars[$x]->setValue('*********'); }
		$form->addElement('hidden', 'oldSsn'.$x, $mem[$x]->data);
		$form->addElement('hidden', 'fid'.$x, $mem[$x]->fid);
		$form->addElement('hidden', 'lid'.$x, $mem[$x]->lid);
	  }
	
	$msx =& $form->addElement('select', 'sex'.$x, 'Sex', array('' => 'Sex', 'Male' => 'Male', 'Female' => 'Female'));
	  if(isset($mem[$x]->sex) && !$_POST) {
	  	$msx->setValue($mem[$x]->sex);
	  }
	$form->addElement('advcheckbox', 'del'.$x, 'Del');
}
/* END OTHERS */

$form->addElement('text', 'employer', 'Employer', array('value' => $app->employer, 'size' => 25, 'maxlength' => 64));
  $form->addRule('employer', 'Street must be less than 65 characters.', 'maxlength',64);

$form->addElement('text', 'income', 'Income', array('value' => $app->income, 'size' => 10, 'maxlength' => 12));
  $form->addRule('income', 'Income must be less than 13 characters.', 'maxlength',12);

$form->addElement('text', 'expense', 'Expenses', array('value' => $app->expenses, 'size' => 10, 'maxlength' => 12));
  $form->addRule('expense', 'Expenses must be less than 13 characters.', 'maxlength',12);

$ckSS =& $form->addElement('advcheckbox', 'ss', 'SS');
  if(!$_POST) { $ckSS->setValue($app->ss); }

$ckSSI =& $form->addElement('advcheckbox', 'ssi', 'SSI');
  if(!$_POST) { $ckSSI->setValue($app->ssi); }

$ckVA =& $form->addElement('advcheckbox', 'va', 'VA');
  if(!$_POST) { $ckVA->setValue($app->va); }

$ckT =& $form->addElement('advcheckbox', 'tanf', 'TANF');
  if(!$_POST) { $ckT->setValue($app->tanf); }

$ckF =& $form->addElement('advcheckbox', 'fStamp', 'F/STAMPS');
  if(!$_POST) { $ckF->setValue($app->fstamps); }

$ckO =& $form->addElement('advcheckbox', 'other', 'OTHER');
  if(!$_POST) { $ckO->setValue($app->other); }

$ty =& $form->addElement('select', 'toys', 'Toys', $arrToys);
  $form->addRule('toys', 'Please select Toys', 'required');
  if(!$_POST) { $ty->setValue($app->toys); }

$fd =& $form->addElement('select', 'food', 'Food', $arrFood);
  $form->addRule('food', 'Please select Food', 'required');
  if(!$_POST) { $fd->setValue($app->food); }

$cm =& $form->addElement('textarea', 'comments', 'Comments',array('cols' => 40,'rows' => 6));
  if(!$_POST) { $cm->setValue(rmbr($app->comments)); }

$form->addElement('submit', 'Submit', 'Update Application');
$form->addElement('reset', 'Cancel', 'Cancel', 'onClick="location.replace(\'index.php\')"');

  $form->addFormRule('chkFormDupe');

// pre-validation filters
$form->applyFilter('__ALL__', 'trim');

// Validate form 
if ($form->validate()) {
	// post-validation filters
	$form->applyFilter('__ALL__', 'htmlentities');
	$form->applyFilter('comments', 'nl2br');

	// Insert into database
	$ret = App::updateApp($form->exportValues(),$UID);

	// Check return
	if ($ret == 1) {
		// OK - Setup notification and redirect
		$_SESSION['notice'] = 'Application for "'.$form->exportValue('lastName').'" family updated!';
		header('Location: viewApp.php?id='.$id);
		exit;
	}
	else {
		// Display error message
		genError(STD_ERR);
		exit;
	}
}

// Create the template object
$tpl =& new Smarty;
$tpl->template_dir = '../lib/templates';
$tpl->compile_dir  = '../lib/templates_c';

// Create the renderer object
$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl);

// Setup Error Messages
$renderer->setRequiredTemplate('{$label}{if $required}<span class="smerr">*</span>{/if}');
$renderer->setErrorTemplate('{if $error}<span class="smerr">{$error}</span><br />{/if}{$html}');

// build the HTML for the form
$form->accept($renderer);

// assign array with form data
$tpl->assign('form_data', $renderer->toArray());

// parse and display the template
include('../includes/admin_header.html');
$tpl->display('application.tpl');
include('../includes/footer.html');
?>
