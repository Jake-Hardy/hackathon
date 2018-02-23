<?
require_once('../lib/init.php');
require_once('../lib/admin.php');

// Load QuickForm
require_once 'HTML/QuickForm.php';
$form = new HTML_QuickForm('firstForm');

// Method to detect duplicate status
function checkName ($name) { 
	if (Agency::chkName($name)) {
		return 0;
	}
	else {
		return 1;
	}
}

// Add elements to form
$form->addElement('text', 'name', 'Name:', array('size' => 25, 'maxlength' => 64));
  $form->addRule('name', 'Please enter Name', 'required');
  $form->addRule('name', 'Name must contain 1 - 64 characters.', 'rangelength',array(1,64));
  $form->addRule('name', 'Name is already in use', 'callback', 'checkName');

$form->addElement('select', 'food', 'Food Default', $arrFood);

$form->addElement('select', 'toys', 'Toys Default', $arrToys);

$form->addElement('submit', null, 'Submit');

// pre-validation filters
$form->applyFilter('__ALL__', 'trim');

// Validate form 
if ($form->validate()) {
	// post-validation filters
	$form->applyFilter('__ALL__', 'htmlentities');

	// Insert into database
	$ret = Agency::insAgency($form->exportValue('name'),$form->exportValue('food'),$form->exportValue('toys'));

        // Check Return
        if ($ret == 1) {
                // OK - Setup notification and redirect
	        $_SESSION['notice'] = 'Agency "'.$form->exportValue('name').'" successfully added!';
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
	<h2>Admin - New Agency</h2>
</div>
<br />
<?
$form->display();
include('../includes/footer.html');
?>
