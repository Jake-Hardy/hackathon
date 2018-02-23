<?php
require_once('../lib/init.php');
require_once('../lib/admin.php');

if (isset($_POST['updated'])) {

	// update season
	$r = App::setSeason($_POST['season']);
	if (!$r) {
		genError(STD_ERR);
		exit;
	}
	$_SESSION['notice'] = 'Season changed to '.$_POST['season'];

	// update season
	$r = App::setTFT($_POST['tft']);
	if (!$r) {
		genError(STD_ERR);
		exit;
	}
	$_SESSION['notice'] = 'Toys for Tots Availble changed to : '.$_POST['tft'];

	header('location: index.php');
	exit;
}
else {
	// build array of years
//	$arr = array(date('Y')-1,date('Y'),date('Y')+1);

	for($x=2007;$x<date('Y')+2;$x++) {
		$arr[] = $x;
	}	
		
	// display form
	include('../includes/admin_header.html');
?>
        <div id="content-header">
                <h2>Manage Season</h2>
        </div>

	<div class="center">
	<br />
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	  <p>Season:
        <select name="season">
          <option selected="selected"><?php echo SEASON ?></option>
          <option>----</option>
  <?	
	foreach ($arr as $_) {
	  echo "<option>$_</option>";
	}
?>
        </select>
      </p>
	  <p>Toys for Tots Available? 
          <select name="tft">
          <option value=1 <?php echo (TFT==1)?"selected='selected'":"";?>>TRUE</option>
          <option value=0 <?php echo (TFT==0)?"selected='selected'":"";?>>FALSE</option>
          </select>
	  </p>
        
	  <p><input type="hidden" name="updated" value="1" /><input type="submit" value="Submit" /></p>
	</form>
	</div>
<?
}
include('../includes/footer.html');
?>
