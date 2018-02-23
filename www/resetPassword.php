<?php
$SKIP_SESSION = 1; // Tell init to skip session check
require_once('lib/init.php');
require_once('lib/classes/User.php');

if (isset($_POST['username']) && isset($_POST['email'])) { 
    // form submitted 
	$usr = new User();
	$uid = User::chkUsername($_POST['username']);

	if ($uid > 0) {
		$info = User::getUser(531);
		if ( $info['email'] != "" && $_POST['email'] == $info['email'] ) {
			// Reset Password
			$passwd = User::resetPassword($uid);

			try{
				mail( $info['email'], 'CSRAChristmas.org Password', 
					"Your CSRAChristmas.org password has been reset. Your new password is:\n".$passwd, 
					"From: CSRA Christmas <admin@csrachristmas.org>" );
			}
			catch(Exception $e){
				header('Location: /resetPassword.php?error=3');
			}
			// Send email
			
			header('Location: /resetPassword.php?error=2');
			exit();
		} else {
			header('Location: /resetPassword.php?error=1');
			exit();
		}
	} 
	else { 
		header('Location: /resetPassword.php?error=1');
		exit();
	} 
} 
else { 
    // display login form 
	include('includes/header.html');
?> 
	<div id="content-header">
		<h2>Reset Password</h2>
	</div>

	<div id="login-box">
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"> 
<?php if(isset($_GET['error']) ){ ?>
	<?php if ($_GET['error'] == 1) { ?>
		<p><span class="error">Invalid Username or Email.</span></p>
<?php } elseif ($_GET['error'] == 2) { ?>
		<p><span class="error">Your temporary password has emailed to you.</span></p>
		<p><a href="/">Login here</a> once you have received the email.</p>
<?php } else { ?>
	<p><span class="error">The mail server is not functioning.</span></p>
<?php } ?>
<?php }
else{
?>
	<p><span>Please enter your Username and Email.</span></p>
<?php }?>
		<table align="center" cellpadding="0" cellspacing="5">
		<tr><td align="right">Username: </td><td align="left"><input type="text" name="username" class="login" value="<?php if (isset($_COOKIE['username'])) { echo $_COOKIE['username']; } ?>" /></td></tr>
		<tr><td align="right">Email: </td><td align="left"><input type="text" class="login" name="email" /></td></tr>
		</table>
		<p><input type="submit" name="submit" value="Reset Password" /></p>
		</form>
	</div>

<?php 
	include('includes/footer.html');
} 
?> 
