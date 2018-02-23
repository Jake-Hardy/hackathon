<?
require_once('../lib/init.php');
require_once('../lib/admin.php');

// Validate input
if (!isset($_REQUEST['id']) || !preg_match("/^\d+$/",$_REQUEST['id'])) {
	exit;
}
$id = $_REQUEST['id'];

// Load QuickForm
require_once 'HTML/QuickForm.php';
$form = new HTML_QuickForm('firstForm');

// Load original values
$user = User::getUser($id);
$ags = User::getAgencies($id);

// Build list of additional agencies
$arrAD = array();
foreach ($ags as $_) {
	if ($_->id != $user->agency_id) {
		array_push($arrAD, $_->id);
	}
}

// Get list of agencies
foreach (Agency::getAgencies() as $_) {
        $arrAN[$_->id] = $_->name;
}

// Method to detect duplicate logins
function checkUsername ($username) { 
	if ($username != $_POST['oldusername']) {
		if (User::chkUsername($username)) {
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

$form->addElement('hidden', 'oldusername', $user->username);

$form->addElement('text', 'username', 'Username:', array('size' => 25, 'maxlength' => 25, 'value' => $user->username));
  $form->addRule('username', 'Please enter Username', 'required');
  $form->addRule('username', 'Username must contain 5 - 25 characters.', 'rangelength',array(5,25));
  $form->addRule('username', 'Username is already in use', 'callback', 'checkUsername');

$form->addElement('password', 'passwd', 'New Password:', array('size' => 25, 'maxlength' => 25));
  $form->addRule('passwd', 'Password must contain 5 - 25 characters.', 'rangelength',array(5,25));

$se =& $form->addElement('select', 'agency', 'Primary Agency:', $arrAN);
  $form->addRule('agency', 'Please select Agency', 'required');
  $form->addRule('agency', 'Agency ID must be less than 6 digits.', 'maxlength',5);
  $form->addRule('agency', 'Agency ID must only contain numbers.', 'numeric');
  if ($user->agency_id == '') {
	$se->addOption('','');
  }
  $se->setValue($user->agency_id);

$ad =& $form->addElement('select', 'agencySec', 'Additional Agencies:<br /><span style="font-size:85%">use Ctrl for multiple</span>', $arrAN, array('multiple' => 'multiple', 'size' => 6));
  $form->addGroupRule('agencySec', 'Agency ID must be less than 6 digits.', 'maxlength',5);
  $form->addGroupRule('agencySec', 'Agency ID must only contain numbers.', 'numeric');
  $ad->addOption('No Add\'l Agencies',0);
  $ad->setValue($arrAD);

$st =& $form->addElement('select', 'status', 'Status:', $arrSt);
  $form->addRule('status', 'Please select Status', 'required');
  $st->setValue($user->status);

$al =& $form->addElement('select', 'access', 'Access:', $arrAL);
  $form->addRule('access', 'Please select Access', 'required');
  $al->setValue($user->accessLevel);

$form->addElement('text', 'firstname', 'First Name:', array('size' => 25, 'maxlength' => 64, 'value' => $user->firstName));
  $form->addRule('firstname', 'Please enter First Name', 'required');
  $form->addRule('firstname', 'First Name must be less then 65 characters.', 'maxlength',64);

$form->addElement('text', 'lastname', 'Last Name:', array('size' => 25, 'maxlength' => 64, 'value' => $user->lastName));
  $form->addRule('lastname', 'Please enter Last Name', 'required');
  $form->addRule('lastname', 'Last Name must be less then 65 characters.', 'maxlength',64);

$form->addElement('text', 'phone', 'Phone', array('size' => 15, 'maxlength' => 20, 'value' => $user->phone));
  $form->addRule('phone', 'Phone must be less than 21 characters.', 'maxlength',20);

$ck =& $form->addElement('advcheckbox', 'dlFlag', 'SSN Access');
  if(!$_POST) { $ck->setValue($user->dlFlag); }

$form->addElement('submit', null, 'Update');

// pre-validation filters
$form->applyFilter('__ALL__', 'trim');

// Validate form 
if ($form->validate()) {
	// post-validation filters
	$form->applyFilter('__ALL__', 'htmlentities');

	// Insert into database
	$ret = User::updUser($form->exportValues());

        // Check Return
        if ($ret == 1) {
                // OK - Setup notification and redirect
	        $_SESSION['notice'] = 'User "'.$form->exportValue('username').'" successfully updated!';
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
	<h2>Admin - Edit User</h2>
</div>
<br />
<?
$form->display();
include('../includes/footer.html');
?>
