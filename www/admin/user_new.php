<?php
require_once('../lib/init.php');
require_once('../lib/admin.php');

// Load QuickForm
require_once 'HTML/QuickForm.php';
$form = new HTML_QuickForm('firstForm');

// Get list of agencies
 $agArray = Agency::getAgencies();
foreach (Agency::getAgencies() as $_) {
        $arrAN[$_['id']] = $_['name'];
}

$temp = array();
while ($row = $agArray -> fetch_assoc())
{
  $temp[] = array();
}

// Method to detect duplicate logins
function checkUsername ($username) { 
	if (User::chkUsername($username)) {
		return 0;
	}
	else {
		return 1;
	}
}

// Add elements to form
$form->addElement('text', 'username', 'Username:', array('size' => 25, 'maxlength' => 25));
  $form->addRule('username', 'Please enter Username', 'required');
  $form->addRule('username', 'Username must contain 5 - 25 characters.', 'rangelength',array(5,25));
  $form->addRule('username', 'Username is already in use', 'callback', 'checkUsername');

$form->addElement('password', 'passwd', 'Password:', array('size' => 25, 'maxlength' => 25));
  $form->addRule('passwd', 'Please enter Password', 'required');
  $form->addRule('passwd', 'Password must contain 5 - 25 characters.', 'rangelength',array(5,25));

$se =& $form->addElement('select', 'agency', 'Primary Agency:', array(null => 'Agency'));
  $se->loadArray($arrAN);
  $form->addRule('agency', 'Please select Agency', 'required');
  $form->addRule('agency', 'Agency ID must be less than 6 digits.', 'maxlength',5);
  $form->addRule('agency', 'Agency ID must only contain numbers.', 'numeric');

$form->addElement('select', 'agencySec', 'Additional Agencies:<br /><span style="font-size:85%">use Ctrl for multiple</span>', $arrAN, array('multiple' => 'multiple', 'size' => 6));
  $form->addGroupRule('agencySec', 'Agency ID must be less than 6 digits.', 'maxlength',5);
  $form->addGroupRule('agencySec', 'Agency ID must only contain numbers.', 'numeric');

$form->addElement('select', 'access', 'Access:', $arrAL);
  $form->addRule('access', 'Please select Access', 'required');

$form->addElement('text', 'firstname', 'First Name:', array('size' => 25, 'maxlength' => 64));
  $form->addRule('firstname', 'Please enter First Name', 'required');
  $form->addRule('firstname', 'First Name must be less then 65 characters.', 'maxlength',64);

$form->addElement('text', 'lastname', 'Last Name:', array('size' => 25, 'maxlength' => 64));
  $form->addRule('lastname', 'Please enter Last Name', 'required');
  $form->addRule('lastname', 'Last Name must be less then 65 characters.', 'maxlength',64);

$form->addElement('text', 'phone', 'Phone', array('size' => 15, 'maxlength' => 20));
  $form->addRule('phone', 'Phone must be less than 21 characters.', 'maxlength',20);

$form->addElement('text', 'email', 'Email', array('size' => 25, 'maxlength' => 64));
  $form->addRule('email', 'Please enter an email address', 'required');
  $form->addRule('email', 'Last Name must be less then 65 characters.', 'maxlength',64);

$form->addElement('advcheckbox', 'dlFlag', 'SSN Access');

$form->addElement('submit', null, 'Submit');

// pre-validation filters
$form->applyFilter('__ALL__', 'trim');

// Validate form 
if ($form->validate()) {
	// post-validation filters
	$form->applyFilter('__ALL__', 'htmlentities');

	// Insert into database
	$ret = User::insUser($form->exportValues());

        // Check Return
        if ($ret == 1) {
                // OK - Setup notification and redirect
	        $_SESSION['notice'] = 'User "'.$form->exportValue('username').'" successfully added!';
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
	<h2>Admin - New User</h2>
</div>
<br />
<?
$form->display();
include('../includes/footer.html');
?>
