<?php
require_once('lib/init.php');
include('includes/header.html'); 
?>

<div id="content-header">
	<h2>Welcome, <?php echo $_SESSION['fn'] ?></h2>
</div>

<p>Please use the menu above to make your selection.</p>
<p>&nbsp;</p>
<?php if ($_SESSION['al'] == 2) { ?>
<p><a href="/admin/">Admin Menu</a></p>
<?php } ?>
<p><a href="/chgPasswd.php">Change Password</a></p>
<?php include('includes/footer.html'); ?>
