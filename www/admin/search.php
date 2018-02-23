<?php
require_once('../lib/init.php');
require_once('../lib/admin.php');

if (isset($_REQUEST['type'])) {
    // form submitted
	$res = array();
	// Check which search method
	switch($_REQUEST['type']) {
	case 1:
		// SSN Search
                if (isset($_POST['ssn'])) {
                        // This lets us hash ssn before letting the browser see it for security
                        header('Location: search.php?type='.$_POST['type'].'&year='.$_POST['year'].'&data='.genHash($_POST['ssn']));
                        exit;
                }
		if ($_GET['data']) {
			$res = App::SearchBySSNAdmin($_GET['data'],$_GET['year']);
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
			$cnt = App::SearchByLNameAdminCnt($_GET['lname'],$_GET['year']);
			$res = App::SearchByLNameAdmin($_GET['lname'],$_GET['year'],$page);
		}

    // Prepare Pagination
    $pcode = '<br />'.genPageLinks($page,$cnt);

		break;
	case 3:
		// DOB Search
		if ($_GET['dob']) {
			$res = App::SearchByDOBAdmin($_GET['dob'],$_GET['year']);
		}
		break;
	}

	// Display results
	include('../includes/admin_header.html');
  ?>
	<div id="content-header"><h2>Admin - Search Results</h2></div>
	<table width="700">
	<tr><th>Last Name</th><th>First Name</th><th>DOB</th><th>Submitted</th><th>Agency</th><th>Action</th></tr>
  <?
	foreach ($res as $_) {
		echo '<tr><td>'.$_['lastName'].'</td><td>'.$_['firstName'].'</td><td>'.$_['dob'].'</td><td>'.$_['tstamp'].'</td><td>'.$_['name'].'</td><td><a href="viewApp.php?id='.$_['id'].'">View</a></td></tr>';
	}
	echo '</table>';

        // Display Error
        if (sizeof($res)<1) {
	        echo '<br />Sorry, No records were found.';
        }

        // Display Pagination (for Name search)
        if (isset($pcode)) {
                echo $pcode;
	}
}
else {
    // Display Form
	include('../includes/admin_header.html');
  ?>
  <?	
	// build array of years
	$arr = array(date('Y')-1,date('Y'));
  ?>
	<div id="content-header"><h2>Admin - Applicant Search</h2></div>
	<div id="search">
		<fieldset><legend>SSN Search</legend>	
		  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		    <strong>SSN:</strong> <input type="text" name="ssn" size="9" maxlength="9">&nbsp;&nbsp;
	 	    <strong>Year:</strong> <select name="year">
				<option><?php echo SEASON ?></option>
				<option value="">All</option>
				<?	
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
				<?	
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
				<?	
					foreach ($arr as $_) {
					  echo "<option>$_</option>";
					}
				?></select><br />
		    <span class="small">(MM-DD-YYYY)</span><br /><br />
		    <input type="hidden" name="type" value="3">
		    <input type="submit" value="Submit">
		  </form>
		</fieldset>
	</div>
  <?php 
}
include('../includes/footer.html');
?>