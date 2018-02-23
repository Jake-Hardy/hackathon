<?php
require_once('lib/init.php');
$UID = $_SESSION['uid'];
if (isset($_REQUEST['type'])) {
    // form submitted
	// validate pagination
	if (isset($_REQUEST['page']) && preg_match("/^\d+$/",$_REQUEST['page'])) {
	        $page = $_REQUEST['page'];
	}
	else {
	        $page = 1;
	}

	// Check which search method
	$res = array();
	switch($_REQUEST['type']) {
	case 1:
		// SSN Search
		if (isset($_POST['ssn'])) {
			// This lets us hash ssn before letting the browser see it for security
	    header('Location: search.php?type='.$_POST['type'].'&year='.$_POST['year'].'&data='.genHash($_POST['ssn']));
			exit;
		}
		if ($_GET['data']) {
			$res = App::SearchBySSN($_GET['data'],$_GET['year'],$UID);
		}
		break;
	case 2: 
		// Name Search
	        // validate pagination
	        if (isset($_REQUEST['page']) && preg_match("/^\d+$/",$_REQUEST['page'])) {
	          $page = $_REQUEST['page'];
	        }
	        else {
	        	$page = 1;
	        }
		$cnt = '';
		if ($_GET['lname']) {
			$cnt = App::SearchByLNameCnt($_GET['lname'],$_GET['year'],$UID);
			$res = App::SearchByLName($_GET['lname'],$_GET['year'],$UID,$page);
		}
		// Prepare Pagination
		$pcode = '<br />'.genPageLinks($page,$cnt);
		break;
	case 3:
		// DOB Search
		if ($_GET['dob']) {
			$res = App::SearchByDOB($_GET['dob'],$_GET['year'],$UID);
		}
		break;
	}
	
	// Display results
	include('includes/header.html');
  ?>
	<div id="content-header"><h2>Search Results</h2></div>
	<table width="650">
	<tr><th>Last Name</th><th>First Name</th><th>DOB</th><th>Submitted</th><th>Action</th></tr>
  <?php
	foreach ($res as $_) {
		echo '<tr><td>'.$_['lastName'].'</td><td>'.$_['firstName'].'</td><td>'.$_['dob'].'</td><td>'.$_['tstamp'].'</td><td><a href="viewApp.php?id='.$_['id'].'">View Application</a></td></tr>';
	}
	echo '</table>';
	// Display Error
	if (sizeof($res)<1)
		{
			echo '<br />Sorry, No records were found.';
		}
	// Display Pagination (for Name search)
	if (isset($pcode)) {
		echo $pcode;
	}
}
else {
    // Display Form
	include('includes/header.html');
  ?>
  <?php	
	// build array of years
	$arr = array(date('Y')-1,date('Y'));
  ?>
	<div id="content-header"><h2>Applicant Search</h2></div>
	<p class="center">Please use the forms below to search for applicants that have been submitted by your agency.</p>
	<div id="search">
		<fieldset><legend>SSN Search</legend>	
		  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		    <strong>SSN:</strong> <input type="text" name="ssn" size="9" maxlength="9">&nbsp;&nbsp;
	 	    <strong>Year:</strong> <select name="year">
				<option><?php echo SEASON ?></option>
				<option value="">All</option>
				<?php	
					foreach ($arr as $_) {
					  echo "<option>$_</option>";
					}
				?></select><br />
		    <span class="small">(no dash)</span><br /><br />
	       	    <input type="hidden" name="type" value="1">
	            <input type="submit" value="Submit">
	          </form>
	        </fieldset>
		<br />
		<fieldset><legend>Name Search</legend>
		  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
		    <strong>Last Name:</strong> <input type="text" name="lname" size="15">&nbsp;&nbsp;
		    <strong>Year:</strong> <select name="year">
				<option><?php echo SEASON ?></option>
				<option value="">All</option>
				<?php	
					foreach ($arr as $_) {
					  echo "<option>$_</option>";
					}
				?></select><br />
		    <span class="small">Hint: use '%' for a wildcard search</span><br /><br />
		    <input type="hidden" name="type" value="2">
		    <input type="submit" value="Submit">
		  </form>
		</fieldset>
		<br />
		<fieldset><legend>DOB Search</legend>
		  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
		    <strong>DOB:</strong> <input type="text" name="dob" size="15">&nbsp;&nbsp;
		    <strong>Year:</strong> <select name="year">
				<option><?php echo SEASON ?></option>
				<option value="">All</option>
				<?php	
					foreach ($arr as $_) {
					  echo "<option>$_</option>";
					}
				?></select><br />
		    <span class="small">(MM-DD-YYYY)</span><br /><br />
		    <input type="hidden" name="type" value="3">
		    <input type="submit" value="Submit">
		  </form>
		</fieldset>
		<br />
		<fieldset><legend>Application Lookup</legend>
		  <form action="viewApp.php" method="get">
		    <strong>ID:</strong> <input type="text" name="id" size="15"><br /><br />
		    <input type="submit" value="Submit">
		  </form>
		</fieldset>
		<br />
<!--
		<fieldset><legend>Renewal Search</legend>	
		  <form action="<?php //echo $_SERVER['PHP_SELF']; ?>" method="post">
		    <strong>SSN:</strong> <input type="text" name="ssn" size="9" maxlength="9">&nbsp;&nbsp;
	 	    <strong>Year:</strong> <?php //echo SEASON-1 ?><br />
		    <span class="small">(no dash)</span><br /><br />
				<input type="hidden" name="year" value="<?php //echo SEASON-1 ?>" />
	       	    <input type="hidden" name="type" value="1">
	            <input type="submit" value="Submit">
	          </form>
	        </fieldset>
		<br />
-->
	</div>
  <?php 
}
include('includes/footer.html');
?>