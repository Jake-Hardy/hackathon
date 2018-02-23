<?
require_once('../lib/init.php');
require_once('../lib/admin.php');

if (isset($_POST['season']) && is_numeric($_POST['season']) && strlen($_POST['season']) == 4) {
	// update season
	$r = App::setSeason($_POST['season']);
	if ($r != 1) {
		genError(STD_ERR);
		exit;
	}
	$_SESSION['notice'] = 'Season changed to '.$_POST['season'];
	header('location: index.php');
	exit;
}
else {
	// build array of years
	$arr = array(date('Y')-1,date('Y'),date('Y')+1);
		
	// display form
	include('../includes/admin_header.html');
?>
        <div id="content-header">
                <h2>Manage Season</h2>
        </div>

	<div class="center">
	<br />
	<form action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
	Season: <select name="season">
	  <option selected="selected"><? echo SEASON ?></option>
	  <option>----</option>
<?	
	foreach ($arr as $_) {
	  echo "<option>$_</option>";
	}
?>
	</select><br />
	<br /><input type="submit" value="Submit" />
	</form>
	</div>
<?
}
include('../includes/footer.html');
?>
