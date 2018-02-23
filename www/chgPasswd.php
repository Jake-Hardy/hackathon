<?php
require_once('lib/init.php');

if(ISSET($_POST['passwd1'])) 
{
  if ( $_POST['passwd1'] != $_POST['passwd2'] ) 
	{
		header('Location: /chgPasswd.php?error=1');
  } 
	else 
	{
    $UID = User::chkSession($_SESSION['rand'],$_SERVER['REMOTE_ADDR']);
		$id = $_SESSION['uid'];
    User::changePassword( $id, $_POST['passwd1'] );
		header('Location: /chgPasswd.php?error=2');
  }
  exit();
}

include('includes/header.html'); 

if (isset($_GET['error']) && $_GET['error'] == 2) {
?>
		<p>Your password has been updated.</p>
<p>Please use the menu above to make your selection.</p>
<p>&nbsp;</p>
<?php if ($_SESSION['al'] == 2) { ?>
<p><a href="/admin/">Admin Menu</a></p>
<?php } ?>
<?php } else { ?>

	<div id="login-box">
<p>Change your password.</p>
<p>&nbsp;</p>
<form action="/chgPasswd.php" method="post">
<?php if (isset($_GET['error']) && $_GET['error'] == 1) { ?>
		<p><span class="error">Password must match.</span></p>
<?php } ?>
		<table align="center" cellpadding="0" cellspacing="5">
		<tr><td align="right">Password: </td><td align="left"><input type="password" name="passwd1" class="login" /></td></tr>
		<tr><td align="right">Confirm Password: </td><td align="left"><input type="password" class="login" name="passwd2" /></td></tr>
		</table>
		<p><input type="submit" name="submit" value="Change Password" /></p>
</form>
</div>
<?
}

include('includes/footer.html');
?>
