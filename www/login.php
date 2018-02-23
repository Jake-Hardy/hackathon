<?php
error_reporting(E_ALL);

$SKIP_SESSION = 1; // Tell init to skip session check
require_once('lib/init.php');

if (isset($_POST['username']) && isset($_POST['password'])) { 
    // form submitted 
	$id = User::chkLogin($_POST['username'],$_POST['password']);
		
	if ($id > 0) {
		$rand = genRandom();
		User::insSession($id,$rand,$_SERVER['REMOTE_ADDR']);
		session_start();
		session_unset();		// Clear old session dataadm
		session_regenerate_id();	// Prevent Session Fixation 	

		$_SESSION['auth'] = 1;
		$_SESSION['rand'] = $rand;

		// get info
		$info = User::getInfo($id);

		while ($row = $info -> fetch_assoc()) 
		{
			$_SESSION['fn'] = $row['firstName'];
			$_SESSION['al'] = $row['accessLevel'];
			$_SESSION['dl'] = $row['dlFlag'];
			$_SESSION['uid'] = $id;
		}
		$info = $info -> fetch_assoc();
        	if ($info['passwordReset'] == 1) {
			header('Location: /chgPasswd.php');
		} else if (isset($_POST['r'])) {
			header('Location: http://'.$_SERVER["SERVER_NAME"].''.urldecode($_POST['r']));
		}
		else {
			header('Location: /index.php');
		}
		exit;
	} 
	else { 
        // authentication failed 

		if (isset($_POST['r'])) {
			header('Location: /login.php?error=1&r='.$_POST['r']);
		}
		else {
			header('Location: /login.php?error=1');
		}
		exit();
	} 
}//are both fields set 
else { 
    // display login form 
	include('includes/header.html');
?> 
	<div id="content-header">
		<h2>System Login</h2>
	</div>
    <div id="login-box">
		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" > 
<?php if (isset($_GET['error']) && $_GET['error'] == 1) { ?>
		<p><span class="error">Invalid Username or Password.</span></p>
<?php } else { ?>
		<p><span>Please enter your Username and Password.</span></p>
<?php } ?>
		<table align="center" cellpadding="0" cellspacing="5">
		<tr><td align="right">Username: </td><td align="left"><input type="text" name="username"  id="username"class="login" value="<?php if (isset($_COOKIE['username'])) { echo $_COOKIE['username']; } ?>" /></td></tr>
		<tr><td align="right">Password: </td><td align="left"><input type="password" class="login" name="password"  id="password"/></td></tr>
		<tr><td colspan=2>
        <input type="submit" name="submit" value="Sign In" />
<?php if (isset($_GET['r']) && $_GET['r'] != '/logoff.php') {
	echo '  <input type="hidden" name="r" value="'.urlencode($_GET['r']).'" />';
} ?>
        </td></tr>
		</table>
		</form>
		<p><a href="/resetPassword.php">Reset Password</a></p>
	</div>

<?php
	include('includes/footer.html');
} 
?>