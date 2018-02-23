<?php
require_once('lib/init.php');

// Load QuickForm and Smarty
require_once 'admin/HTML/QuickForm.php';
require_once 'admin/HTML/QuickForm/Renderer/ArraySmarty.php';
require_once 'Smarty/Smarty.class.php';
$form = new HTML_QuickForm('firstForm');

$id = $_SESSION['uid'];
$rows = User::getAgencies($id);

// Get options for agency 
// User::getAgencies($rows['id'])
foreach ($rows as $_) {
	$arrAN[$_['id']] = $_['name'];
	$arrFDef[$_['id']] = $_['foodDefault'];
	$arrTDef[$_['id']] = $_['toysDefault'];
	$arrHFDef[$_['id']] = $_['hideFood'];
	$arrHTDef[$_['id']] = $_['hideToys'];
}

// Get list of city names from DB for auto complete
$db = new Database();
$cit = $db->select('SELECT distinct city FROM t_application');

// Turn objects into a flattened array
$arrC = array();
foreach ($cit as $item) {
	$arrC[] = $item['city'];
}


// Add elements to form
$an = $form->addElement('select', 'agency', 'Agency', $arrAN, 'onClick="chgFood(); chgToys();"');
  if (isset($_SESSION['agency'])) {
		$an->setValue($_SESSION['agency']);
  }
  $form->addRule('agency', 'Please select Agency', 'required');
  $form->addRule('agency', 'Agency ID must be less than 6 digits.', 'maxlength',5);
  $form->addRule('agency', 'Agency ID must only contain numbers.', 'numeric');

$form->addElement('text', 'lastName', 'Last Name', array('size' => 25, 'maxlength' => 64, 'onchange' => "calcNum(".CNT_OTHERS.")"));
  $form->addRule('lastName', 'Please enter Last Name', 'required', null, null);
  $form->addRule('lastName', 'Last Name must be less than 65 characters.', 'maxlength',64);

$form->addElement('text', 'firstName', 'First Name', array('size' => 25, 'maxlength' => 64));
  $form->addRule('firstName', 'Please enter First Name', 'required', null, null);
  $form->addRule('firstName', 'First Name must be less than 65 characters.', 'maxlength',64);

/*
$form->addElement('text', 'dob', 'DOB', array('size' => 10, 'maxlength' => 10));
  $form->addRule('dob', 'Please enter DOB', 'required');
  $form->addRule('dob', 'Please enter Valid Date', 'callback', 'chkDate');
*/

$form->addElement('text', 'dob_0', 'DOB', array('size' => 2, 'maxlength' => 2, 'onkeydown' =>"tabIt(this,'down',2)", 'onkeyup' => "tabIt(this,'up',2,this.form.dob_1)"));
  $form->addRule('dob_0', 'Please enter DOB', 'required');
  $form->addRule('dob_0', 'DOB must only contain numbers', 'numeric');

$form->addElement('text', 'dob_1', 'DOB', array('size' => 2, 'maxlength' => 2, 'onkeydown' =>"tabIt(this,'down',2)", 'onkeyup' => "tabIt(this,'up',2,this.form.dob_2)"));
  $form->addRule('dob_1', 'Please enter DOB', 'required');
  $form->addRule('dob_1', 'DOB must only contain numbers', 'numeric');

$form->addElement('text', 'dob_2', 'DOB', array('size' => 4, 'maxlength' => 4, 'onkeydown' =>"tabIt(this,'down',4)", 'onkeyup' => "tabIt(this,'up',4,this.form.ssn_0)"));
  $form->addRule('dob_2', 'Please enter DOB', 'required');
  $form->addRule('dob_2', 'DOB must only contain numbers', 'numeric');
 
$form->addElement('hidden', 'dob', '');


$form->addElement('text', 'ssn_0', 'SSN', array('size' => 3, 'maxlength' => 3, 'onkeydown' =>"tabIt(this,'down',3)", 'onkeyup' => "tabIt(this,'up',3,this.form.ssn_1)"));
  $form->addRule('ssn_0', 'Please enter SSN', 'required');
  $form->addRule('ssn_0', 'SSN must be exactly 9 digits', 'rangelength', array(3,3));
  $form->addRule('ssn_0', 'SSN must only contain numbers', 'numeric');

$form->addElement('text', 'ssn_1', 'SSN', array('size' => 2, 'maxlength' => 2, 'onkeydown' =>"tabIt(this,'down',2)", 'onkeyup' => "tabIt(this,'up',2,this.form.ssn_2)"));
  $form->addRule('ssn_1', 'Please enter SSN', 'required');
  $form->addRule('ssn_1', 'SSN must be exactly 9 digits', 'rangelength', array(2,2));
  $form->addRule('ssn_1', 'SSN must only contain numbers', 'numeric');

$form->addElement('text', 'ssn_2', 'SSN', array('size' => 4, 'maxlength' => 4, 'onkeydown' =>"tabIt(this,'down',4)", 'onkeyup' => "tabIt(this,'up',4,this.form.ssn_0_2)"));
  $form->addRule('ssn_2', 'Please enter SSN', 'required');
  $form->addRule('ssn_2', 'SSN must be exactly 9 digits', 'rangelength', array(4,4));
  $form->addRule('ssn_2', 'SSN must only contain numbers', 'numeric');
 
$form->addElement('text', 'ssn_0_2', 'SSN', array('size' => 3, 'maxlength' => 3, 'onkeydown' =>"tabIt(this,'down',3)", 'onkeyup' => "tabIt(this,'up',3,this.form.ssn_1_2)"));
  $form->addRule('ssn_0_2', 'Please enter SSN', 'required');
  $form->addRule('ssn_0_2', 'SSN must be exactly 9 digits', 'rangelength', array(3,3));
  $form->addRule('ssn_0_2', 'SSN must only contain numbers', 'numeric');
$form->addElement('text', 'ssn_1_2', 'SSN', array('size' => 2, 'maxlength' => 2, 'onkeydown' =>"tabIt(this,'down',2)", 'onkeyup' => "tabIt(this,'up',2,this.form.ssn_2_2)"));
  $form->addRule('ssn_1_2', 'Please enter SSN', 'required');
  $form->addRule('ssn_1_2', 'SSN must be exactly 9 digits', 'rangelength', array(2,2));
  $form->addRule('ssn_1_2', 'SSN must only contain numbers', 'numeric');

$form->addElement('text', 'ssn_2_2', 'SSN', array('size' => 4, 'maxlength' => 4, 'onkeydown' =>"tabIt(this,'down',4)", 'onkeyup' => "tabIt(this,'up',4,this.form.lastNameSp)"));
  $form->addRule('ssn_2_2', 'Please enter SSN', 'required');
  $form->addRule('ssn_2_2', 'SSN must be exactly 9 digits', 'rangelength', array(4,4));
  $form->addRule('ssn_2_2', 'SSN must only contain numbers', 'numeric');
  
$form->addElement('hidden', 'ssn', '');
$form->addElement('hidden', 'ssn_2', '');
  
$form->addRule(array('ssn_0','ssn_0_2'), 'ERROR: SSN\'s must match', 'compare', 'eq');
$form->addRule(array('ssn_1','ssn_1_2'), 'ERROR: SSN\'s must match', 'compare', 'eq');
$form->addRule(array('ssn_2','ssn_2_2'), 'ERROR: SSN\'s must match', 'compare', 'eq');

$form->addElement('text', 'lastNameSp', 'Spouse Last Name', array('size' => 25, 'maxlength' => 64, 'onchange' => "calcNum(".CNT_OTHERS.")"));
  $form->addRule('lastNameSp', 'Last Name must be less than 65 characters.', 'maxlength',64);

$form->addElement('text', 'firstNameSp', 'Spouse First Name', array('size' => 25, 'maxlength' => 64));
  $form->addRule('firstNameSp', 'First Name must be less than 65 characters.', 'maxlength',64);

/*
$form->addElement('text', 'dobSp', 'DOB', array('size' => 10, 'maxlength' => 10));
  $form->addRule('dobSp', 'Please enter Valid Date', 'callback', 'chkDate');
*/

$form->addElement('text', 'dobSp_0', 'DOB', array('size' => 2, 'maxlength' => 2, 'onkeydown' =>"tabIt(this,'down',2)", 'onkeyup' => "tabIt(this,'up',2,this.form.dobSp_1)"));
  $form->addRule('dobSp_0', 'DOB must only contain numbers', 'numeric');

$form->addElement('text', 'dobSp_1', 'DOB', array('size' => 2, 'maxlength' => 2, 'onkeydown' =>"tabIt(this,'down',2)", 'onkeyup' => "tabIt(this,'up',2,this.form.dobSp_2)"));
  $form->addRule('dobSp_1', 'DOB must only contain numbers', 'numeric');

$form->addElement('text', 'dobSp_2', 'DOB', array('size' => 4, 'maxlength' => 4, 'onkeydown' =>"tabIt(this,'down',4)", 'onkeyup' => "tabIt(this,'up',4,this.form.ssnSp_0)"));
  $form->addRule('dobSp_2', 'DOB must only contain numbers', 'numeric');
 
$form->addElement('hidden', 'dobSp', '');


$form->addElement('text', 'ssnSp_0', 'SSN', array('size' => 3, 'maxlength' => 3, 'onkeydown' =>"tabIt(this,'down',3)", 'onkeyup' => "tabIt(this,'up',3,this.form.ssnSp_1)"));
  $form->addRule('ssnSp_0', 'SSN must be exactly 9 digits', 'rangelength', array(3,3));
  $form->addRule('ssnSp_0', 'SSN must only contain numbers', 'numeric');

$form->addElement('text', 'ssnSp_1', 'SSN', array('size' => 2, 'maxlength' => 2, 'onkeydown' =>"tabIt(this,'down',2)", 'onkeyup' => "tabIt(this,'up',2,this.form.ssnSp_2)"));
  $form->addRule('ssnSp_1', 'SSN must be exactly 9 digits', 'rangelength', array(2,2));
  $form->addRule('ssnSp_1', 'SSN must only contain numbers', 'numeric');

$form->addElement('text', 'ssnSp_2', 'SSN', array('size' => 4, 'maxlength' => 4, 'onkeydown' =>"tabIt(this,'down',4)", 'onkeyup' => "tabIt(this,'up',4,this.form.ssnSp_0_2)"));
  $form->addRule('ssnSp_2', 'SSN must be exactly 9 digits', 'rangelength', array(4,4));
  $form->addRule('ssnSp_2', 'SSN must only contain numbers', 'numeric');
 
$form->addElement('text', 'ssnSp_0_2', 'SSN', array('size' => 3, 'maxlength' => 3, 'onkeydown' =>"tabIt(this,'down',3)", 'onkeyup' => "tabIt(this,'up',3,this.form.ssnSp_1_2)"));
  $form->addRule('ssnSp_0_2', 'SSN must be exactly 9 digits', 'rangelength', array(3,3));
  $form->addRule('ssnSp_0_2', 'SSN must only contain numbers', 'numeric');

$form->addElement('text', 'ssnSp_1_2', 'SSN', array('size' => 2, 'maxlength' => 2, 'onkeydown' =>"tabIt(this,'down',2)", 'onkeyup' => "tabIt(this,'up',2,this.form.ssnSp_2_2)"));
  $form->addRule('ssnSp_1_2', 'SSN must be exactly 9 digits', 'rangelength', array(2,2));
  $form->addRule('ssnSp_1_2', 'SSN must only contain numbers', 'numeric');

$form->addElement('text', 'ssnSp_2_2', 'SSN', array('size' => 4, 'maxlength' => 4, 'onkeydown' =>"tabIt(this,'down',4)", 'onkeyup' => "tabIt(this,'up',4,this.form.phone)"));
  $form->addRule('ssnSp_2_2', 'SSN must be exactly 9 digits', 'rangelength', array(4,4));
  $form->addRule('ssnSp_2_2', 'SSN must only contain numbers', 'numeric');

$form->addElement('hidden', 'ssnSp', '');
$form->addElement('hidden', 'ssnSp_2', '');
$form->addRule(array('ssnSp_0','ssnSp_0_2'), 'ERROR: SSN\'s must match', 'compare', 'eq');
$form->addRule(array('ssnSp_1','ssnSp_1_2'), 'ERROR: SSN\'s must match', 'compare', 'eq');
$form->addRule(array('ssnSp_2','ssnSp_2_2'), 'ERROR: SSN\'s must match', 'compare', 'eq');



$form->addElement('text', 'phone', 'Phone', array('size' => 15, 'maxlength' => 20));
  $form->addRule('phone', 'Phone must be less than 21 characters.', 'maxlength',20);

$form->addElement('text', 'street', 'Address', array('size' => 25, 'maxlength' => 128));
error_log("Form adding validation on street: ".isset($_SESSION['agency']) ? "Yes agency" :"no agency"." <---");
if(isset($_SESSION['agency']) && $_SESSION['agency'] != '31') {
	error_log("Should have street validation");
  $form->addRule('street', 'Please enter Street', 'required');
  $form->addRule('street', 'Street must be less than 129 characters.', 'maxlength',128);
}

//$form->addElement('text', 'city', 'City', array('size' => 25, 'maxlength' => 64));
$form->addElement('autocomplete', 'city', 'City', $arrC, array('size' => 25, 'maxlength' => 64));
if (isset($_SESSION['agency']) && $_SESSION['agency'] != '31') {
	error_log("Should have city validation");
  $form->addRule('city', 'Please enter City', 'required');
  $form->addRule('city', 'Street must be less than 65 characters.', 'maxlength',64);
}

$form->addElement('select', 'state', 'State', array('' => 'State', 'GA' => 'Georgia', 'SC' => 'South Carolina'));
if (isset($_SESSION['agency']) && $_SESSION['agency'] != '31') {
  $form->addRule('state', 'Please enter State', 'required');
  $form->addRule('state', 'State code must be exactly 2 characters.', 'rangelength',array(2,2));
}

$form->addElement('text', 'zip', 'Zip', array('size' => 10, 'maxlength' => 10));
if (isset($_SESSION['agency']) && $_SESSION['agency'] != '31') {
	error_log("Should have zip validation");
  $form->addRule('zip', 'Please enter Zip', 'required');
  $form->addRule('zip', 'Zip must be less than 11 characters.', 'maxlength',10);
}

$form->addElement('text', 'familySize', 'Total # of people living in home', array('size' => 3, 'maxlength' => 2, 'disabled' => 'disabled'));

/* OTHERS IN HOME */
for ($x=0;$x<CNT_OTHERS;$x++) {
	$form->addElement('text', 'lastName'.$x, 'Last Name', array('size' => 22, 'maxlength' => 64, 'onchange' => "calcNum(".CNT_OTHERS.")"));
	  $form->addRule('lastName'.$x, 'Last Name must be less than 65 characters.', 'maxlength',64);

	$form->addElement('text', 'firstName'.$x, 'First Name', array('size' => 22, 'maxlength' => 64));
	  $form->addRule('firstName'.$x, 'First Name must be less than 65 characters.', 'maxlength',64);

/*
	$form->addElement('text', 'dob'.$x, 'DOB', array('size' => 10, 'maxlength' => 10));
  	  $form->addRule('dob'.$x, 'Please enter Valid Date', 'callback', 'chkDate');
*/

	$form->addElement('text', 'dob'.$x.'_0', 'DOB', array('size' => 2, 'maxlength' => 2, 'onkeydown' =>"tabIt(this,'down',2)", 'onkeyup' => "tabIt(this,'up',2,this.form.dob".$x."_1)"));
	  $form->addRule('dob'.$x.'_0', 'DOB must only contain numbers', 'numeric');

	$form->addElement('text', 'dob'.$x.'_1', 'DOB', array('size' => 2, 'maxlength' => 2, 'onkeydown' =>"tabIt(this,'down',2)", 'onkeyup' => "tabIt(this,'up',2,this.form.dob".$x."_2)"));
	  $form->addRule('dob'.$x.'_1', 'DOB must only contain numbers', 'numeric');

	$form->addElement('text', 'dob'.$x.'_2', 'DOB', array('size' => 4, 'maxlength' => 4, 'onkeydown' =>"tabIt(this,'down',4)", 'onkeyup' => "tabIt(this,'up',4,this.form.ssn".$x."_0)"));
	  $form->addRule('dob'.$x.'_2', 'DOB must only contain numbers', 'numeric');
 
	$form->addElement('hidden', 'dob'.$x, '');


	$form->addElement('text', 'ssn'.$x.'_0', 'SSN', array('size' => 3, 'maxlength' => 3, 'onkeydown' =>"tabIt(this,'down',3)", 'onkeyup' => "tabIt(this,'up',3,this.form.ssn".$x."_1)"));
	  $form->addRule('ssn'.$x.'_0', 'SSN must be exactly 9 digits', 'rangelength', array(3,3));
	  $form->addRule('ssn'.$x.'_0', 'SSN must only contain numbers', 'numeric');

	$form->addElement('text', 'ssn'.$x.'_1', 'SSN', array('size' => 2, 'maxlength' => 2, 'onkeydown' =>"tabIt(this,'down',2)", 'onkeyup' => "tabIt(this,'up',2,this.form.ssn".$x."_2)"));
	  $form->addRule('ssn'.$x.'_1', 'SSN must be exactly 9 digits', 'rangelength', array(2,2));
	  $form->addRule('ssn'.$x.'_1', 'SSN must only contain numbers', 'numeric');

	$form->addElement('text', 'ssn'.$x.'_2', 'SSN', array('size' => 4, 'maxlength' => 4, 'onkeydown' =>"tabIt(this,'down',4)", 'onkeyup' => "tabIt(this,'up',4,this.form.ssn".$x."_0_2)"));
	  $form->addRule('ssn'.$x.'_2', 'SSN must be exactly 9 digits', 'rangelength', array(4,4));
	  $form->addRule('ssn'.$x.'_2', 'SSN must only contain numbers', 'numeric');


	$form->addElement('text', 'ssn'.$x.'_0_2', 'SSN', array('size' => 3, 'maxlength' => 3, 'onkeydown' =>"tabIt(this,'down',3)", 'onkeyup' => "tabIt(this,'up',3,this.form.ssn".$x."_1_2)"));
	  $form->addRule('ssn'.$x.'_0_2', 'SSN must be exactly 9 digits', 'rangelength', array(3,3));
	  $form->addRule('ssn'.$x.'_0_2', 'SSN must only contain numbers', 'numeric');

	$form->addElement('text', 'ssn'.$x.'_1_2', 'SSN', array('size' => 2, 'maxlength' => 2, 'onkeydown' =>"tabIt(this,'down',2)", 'onkeyup' => "tabIt(this,'up',2,this.form.ssn".$x."_2_2)"));
	  $form->addRule('ssn'.$x.'_1_2', 'SSN must be exactly 9 digits', 'rangelength', array(2,2));
	  $form->addRule('ssn'.$x.'_1_2', 'SSN must only contain numbers', 'numeric');

	$form->addElement('text', 'ssn'.$x.'_2_2', 'SSN', array('size' => 4, 'maxlength' => 4, 'onkeydown' =>"tabIt(this,'down',4)", 'onkeyup' => "tabIt(this,'up',4,this.form.sex".$x.")"));
	  $form->addRule('ssn'.$x.'_2_2', 'SSN must be exactly 9 digits', 'rangelength', array(4,4));
	  $form->addRule('ssn'.$x.'_2_2', 'SSN must only contain numbers', 'numeric');

 
	$form->addElement('hidden', 'ssn'.$x, '');
	$form->addElement('hidden', 'ssn'.$x.'_2', '');
	$form->addRule(array('ssn'.$x.'_0','ssn'.$x.'_0_2'), 'ERROR: SSN\'s must match', 'compare', 'eq');
	$form->addRule(array('ssn'.$x.'_1','ssn'.$x.'_1_2'), 'ERROR: SSN\'s must match', 'compare', 'eq');
	$form->addRule(array('ssn'.$x.'_2','ssn'.$x.'_2_2'), 'ERROR: SSN\'s must match', 'compare', 'eq');
	

/*
	$form->addElement('text', 'ssn'.$x, 'SSN', array('size'=> 9,'maxlength' => 9, 'class' => 'ssn'));
	$form->addRule('ssn'.$x, 'SSN must be exactly 9 digits', 'rangelength', array(9,11));
	//$form->addRule('ssn'.$x, 'SSN must only contain numbers', 'numeric');
	$form->addRule('ssn'.$x, 'ERROR: SSN format invalid', 'regex', '/^\d{3}\-\d{2}\-\d{4}$/');

	$form->addElement('text', 'ssn'.$x.'_2', 'SSN', array('size'=> 9,'maxlength' => 9, 'class' => 'ssn'));
	$form->addRule('ssn'.$x.'_2', 'SSN must be exactly 9 digits', 'rangelength', array(9,11));
	//$form->addRule('ssn'.$x.'_2', 'SSN must only contain numbers', 'numeric');
	$form->addRule('ssn'.$x.'_2', 'ERROR: SSN format invalid', 'regex', '/^\d{3}\-\d{2}\-\d{4}$/');

	$form->addRule(array('ssn'.$x,'ssn'.$x.'_2'), 'ERROR: SSN\'s must match', 'compare', 'eq');
*/	

/*
	$form->addElement('text', 'ssn'.$x, 'SSN', array('size' => 9, 'maxlength' => 9));
	  $form->addRule('ssn'.$x, 'SSN must be exactly 9 digits', 'rangelength', array(9,9));
	  $form->addRule('ssn'.$x, 'SSN must only contain numbers', 'numeric');
*/

	$form->addElement('select', 'sex'.$x, 'Sex', array('' => 'Sex', 'Male' => 'Male', 'Female' => 'Female'));

	$form->addElement('textarea', 'wishlist'.$x, 'Wishlist', array('cols' => 80,'rows' => 6));
	
	$form->addElement('hidden', 'tick'.$x, '0');
	
	$form->addElement('checkbox', 'show', 'List', '', array('onclick' => "toggle($x);"));
}
/* END OTHERS */

$form->addElement('text', 'employer', 'Employer', array('size' => 25, 'maxlength' => 64));
  $form->addRule('employer', 'Street must be less than 65 characters.', 'maxlength',64);

$form->addElement('text', 'income', 'Income', array('size' => 10, 'maxlength' => 12));
  $form->addRule('income', 'Income must be less than 13 characters.', 'maxlength',12);

$form->addElement('text', 'expense', 'Expenses', array('size' => 10, 'maxlength' => 12));
  $form->addRule('expense', 'Expenses must be less than 13 characters.', 'maxlength',12);

$form->addElement('advcheckbox', 'ss', 'SS');

$form->addElement('advcheckbox', 'ssi', 'SSI');

$form->addElement('advcheckbox', 'va', 'VA');

$form->addElement('advcheckbox', 'tanf', 'TANF');

$form->addElement('advcheckbox', 'fStamp', 'F/STAMPS');

$form->addElement('advcheckbox', 'other', 'OTHER');

$form->addElement('select', 'toys', 'Toys', $arrToys);
  $form->addRule('toys', 'Please select Toys', 'required');

$form->addElement('select', 'food', 'Food', $arrFood);
  $form->addRule('food', 'Please select Food', 'required');

$form->addElement('textarea', 'comments', 'Comments',array('cols' => 40,'rows' => 6));

$form->addElement('submit', 'Submit', 'Submit Application');

$form->addFormRule('chkForm');



// pre-validation filters
$form->applyFilter('__ALL__', 'trim');

// Validate form 
if ($form->validate()) {
	// post-validation filters
	$form->applyFilter('__ALL__', 'htmlentities');
	$form->applyFilter('comments', 'nl2br');

        for ($x=0;$x<CNT_OTHERS;$x++) {
                $form->applyFilter('wishlist'.$x, 'nl2br');
        }

        // Remember agency
        $_SESSION['agency'] = $form->exportValue('agency');

	// Insert into database	and Check for duplicates
	$ret = App::insApplication($form->exportValues(),$UID);

	// Check return
	if ($ret > 1) {
		// DUPLICATED - Setup notification and redirect
		$_SESSION['notice'] = '<span class="status">Warning: Application for "'.$form->exportValue('lastName').'" family duplicated.</span>';
		header('Location: dupReport.php?r=app&appId='.$ret);
		exit;
	}
	elseif ($ret == 1) {
		// UNIQUE - Setup notification and redirect
		$_SESSION['notice'] = 'Application for "'.$form->exportValue('lastName').'" family successfully added!';
		header('Location: application.php');
		exit;
	}
	else {
		// Display error message
		genError(STD_ERR);
		exit;

	}
}

// Create the template object
$tpl = new Smarty();
$tpl->template_dir = 'lib/templates';
$tpl->compile_dir  = 'lib/templates_c';

// Create the renderer object
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);

// Setup Error Messages
$renderer->setRequiredTemplate('{$label}{if $required}<span class="smerr">*</span>{/if}');
$renderer->setErrorTemplate('{$html}{if $error}<br/><span class="smerr">{$error}</span>{/if}');

// build the HTML for the form
$form->accept($renderer);

// assign array with form data
$tpl->assign('form_data', $renderer->toArray());
$tpl->assign('arrFDef', $arrFDef); // adds food default by agency
$tpl->assign('arrTDef', $arrTDef); // adds toys default by agency
$tpl->assign('arrHTDef', $arrHTDef);
$tpl->assign('arrHFDef', $arrHFDef);
$tpl->assign('globalHideToys', $_globalHideToys);
// parse and display the template
include('includes/header.html');
$tpl->display('application.tpl');
include('includes/footer.html');
?>
