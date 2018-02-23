<?php
require_once('lib/init.php');
include('includes/header.html');

$UID = $_SESSION['uid'];
// Check if report submitted
if (!isset($_GET['type'])) {
	// Default
?>
	<div id="content-header"><h2>Reports</h2></div>
	<p>Please choose which report you would like to run</p>
	<ul>
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?type=1">Run Duplicate Report</a></li>
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?type=2">Run Cleared Report</a></li>
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?type=3">Run Toys by Age Report</a></li>
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?type=4">Run Family Size Report</a></li>
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?type=5">Run Food Report</a></li>
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?type=6">Run Toys Report</a></li>
<?php if ($_SESSION['dl'] == 1) { ?>
	<li><a href="getReport.php?type=7">Export</a></li>
<?php } ?>
	</ul>
<?php
}
else {
  if ($_GET['type'] == 1) {
	// Run Duplicate Summary Report
	
	$rep = Report::genDupeSummaryReport($UID);

	// Output Table
	echo '<div id="content-header"><h2>Duplicate Report Summary</h2></div><p>&nbsp;</p>';
	echo '<table width="600" align="center" border="1" cellpadding="3" cellspacing="0" id="report">';
	echo '<tr><th>Agency</th><th>Duplicate Applications</th><th>Action</th></tr>';
	$dupetot = 0;
	foreach ($rep as $_) {
		$dupetot+=$_['count'];
		echo '<tr><td>'.$_['name'].'</td><td>'.$_['count'].'&nbsp;&nbsp;(<a href="dupList.php?id='.$_['id'].'&cnt='.$_['count'].'">List</a>)</td><td><a href="getReport.php?type=1&id='.$_['id'].'">Download Report</a></td></tr>';
	}
    echo '<tr><th>Total</th><th>'.$dupetot.'</th><th>-</th></tr>';
	echo '</table>';
  }

  elseif ($_GET['type'] == 2) {
	// Run Cleared Summary Report
	$rep = Report::genClearedSummaryReport($UID);

        // Output Table
        echo '<div id="content-header"><h2>Cleared Report Summary</h2></div><p>&nbsp;</p>';
       	echo '<table width="600" align="center" border="1" cellpadding="3" cellspacing="0" id="report">';
        echo '<tr><th>Agency</th><th>Cleared Applications</th><th>Individuals</th><th>Action</th></tr>';
		$counttot = 0;
		$numIndtot = 0;
        foreach ($rep as $_) {
			$counttot+=$_['count'];
			$numIndtot+=$_['numInd'];
        	echo '<tr><td>'.$_['name'].'</td><td>'.$_['count'].'</td><td>'.$_['numInd'].'</td><td><a href="getReport.php?type=2&id='.$_['id'].'">Download Report</a></td></tr>';
        }
        echo '<tr><th>Total</th><th>'.$counttot.'</th><th>'.$numIndtot.'</th><th>-</th></tr>';
		echo '</table>';
 }

  elseif ($_GET['type'] == 3) {
	// Run Age Report
	$rep = Report::genAgeReport($UID);

	// Output Table 
	echo '<div id="content-header"><h2>Toys by Age Report</h2></div><p>&nbsp;</p>';
	echo '<table width="725" align="center" border="1" cellpadding="3" cellspacing="0" id="report">';
	echo '<tr><th colspan="2">Toys by Age Report</th><th colspan="2">Ages 0-2</th><th colspan="2">Ages 3-5</th><th colspan="2">Ages 6-8</th><th colspan="2">Ages 9-12</th><th colspan="2">Ages 13-14</th></tr>';
	echo '<tr><th>Agency</th><th>Source</th><th>M</th><th>F</th><th>M</th><th>F</th><th>M</th><th>F</th><th>M</th><th>F</th><th>M</th><th>F</th></tr>';
		$count3Mtot = 0;
		$count3Ftot = 0;
		$count35Mtot = 0;
		$count35Ftot = 0;
		$count68Mtot = 0;
		$count68Ftot = 0;
		$count912Mtot = 0;
		$count912Ftot = 0;
		$count1314Mtot = 0;
		$count1314Ftot = 0;
	foreach ($rep as $ag) {
		$count3Mtot+=$ag['count3M'];
		$count3Ftot+=$ag['count3F'];
		$count35Mtot+=$ag['count35M'];
		$count35Ftot+=$ag['count35F'];
		$count68Mtot+=$ag['count68M'];
		$count68Ftot+=$ag['count68F'];
		$count912Mtot+=$ag['count912M'];
		$count912Ftot+=$ag['count912F'];
		$count1314Mtot+=$ag['count1314M'];
		$count1314Ftot+=$ag['count1314F'];
		echo '<tr class="row'.$ag['toys'].'"><td>'.$ag['name'].'</td><td>'.$arrToys[$ag['toys']].'</td><td>'.$ag['count3M'].'</td><td>'.$ag['count3F'].'</td><td>'.$ag['count35M'].'</td><td>'.$ag['count35F'].'</td><td>'.$ag['count68M'].'</td><td>'.$ag['count68F'].'</td><td>'.$ag['count912M'].'</td><td>'.$ag['count912F'].'</td><td>'.$ag['count1314M'].'</td><td>'.$ag['count1314F'].'</td></tr>';
	} 
	echo '<tr><th>Total</th><th>-</th><th>'.$count3Mtot.'</th><th>'.$count3Ftot.'</th><th>'.$count35Mtot.'</th><th>'.$count35Ftot.'</th><th>'.$count68Mtot.'</th><th>'.$count68Ftot.'</th><th>'.$count912Mtot.'</th><th>'.$count912Ftot.'</th><th>'.$count1314Mtot.'</th><th>'.$count1314Ftot.'</th></tr>';
	echo '</table>';
        echo '<p align="center"><a href="getReport.php?type=3">Click Here</a> to download this report.</p>';
  }

  elseif ($_GET['type'] == 4) {
	// Run Family Size Report
	$rep = Report::genFamilySizeReport($UID);

	// Output Table
        echo '<div id="content-header"><h2>Family Size Report</h2></div><p>&nbsp;</p>';
        echo '<table width="600" align="center" border="1" cellpadding="3" cellspacing="0" id="report">';
        echo '<tr><th>Family Size Report</th><th align="center" colspan="3">Number of Households with:</th></tr>';
        echo '<tr><th>Agency</th><th>1-3 Members</th><th>4-6 Members</th><th>7+ Members</th></tr>';
		$count13ctot = 0;
		$count46tot = 0;
		$count7tot = 0;
        foreach ($rep as $ag) {
			$count13ctot+=$ag['count13'];
			$count46tot+=$ag['count46'];
			$count7tot+=$ag['count7'];
			echo '<tr><td>'.$ag['name'].'</td><td>'.$ag['count13'].'</td><td>'.$ag['count46'].'</td><td>'.$ag['count7'].'</td></tr>';
        }
		echo '<tr><th>Total</th><th>'.$count13ctot.'</th><th>'.$count46tot.'</th><th>'.$count7tot.'</th></tr>';
        echo '</table>';
        echo '<p align="center"><a href="getReport.php?type=4">Click Here</a> to download this report.</p>';
  }

  elseif ($_GET['type'] == 5) {
	// Run Food Report
	$rep = Report::genFoodReport($UID);

	// Output Table
	echo '<div id="content-header"><h2>Food Report</h2></div><p>&nbsp;</p>';
	echo '<table width="650" align="center" border="1" cellpadding="3" cellspacing="0" id="report">';
	echo '<tr><th>Food Report</th><th colspan="2" align="center">'.$arrFood[1].'</th><th colspan="2" align="center">'.$arrFood[2].'</th></tr>';
	echo '<tr><th>Agency</th><th>Families</th><th>Individuals</th><th>Families</th><th>Individuals</th></tr>';
	$food1tot = 0;
	$food1ctot = 0;
	$food2tot = 0;
	$food2ctot = 0;
	foreach ($rep as $ag) {
		$food1tot+=$ag['food1'];
		$food1ctot+=$ag['food1c'];
		$food2tot+=$ag['food2'];
		$food2ctot+=$ag['food2c'];
		echo '<tr><td>'.$ag['name'].'</td><td>'.$ag['food1'].'</td><td>'.$ag['food1c'].'</td><td>'.$ag['food2'].'</td><td>'.$ag['food2c'].'</td></tr>';
	}
	echo '<tr><th>Total</th><th>'.$food1tot.'</th><th>'.$food1ctot.'</th><th>'.$food2tot.'</th><th>'.$food2ctot.'</th></tr>';
	echo '</table>'; 
	echo '<p align="center"><a href="getReport.php?type=5">Click Here</a> to download this report.</p>';
  }

  elseif ($_GET['type'] == 6) {

        // Run Toys Report
		error_log("UID for Toys");
		error_log(strval($UID));
		error_log(gettype($UID));
		$test = $UID;
        $rep = Report::genToysReport($test,FALSE);
        $rep2 = Report::genToysReport($test,TRUE);

        echo '<div id="content-header"><h2>Toys Report</h2></div><p>&nbsp;</p>';


        // Output Table
        echo '<table width="650" align="center" border="1" cellpadding="3" cellspacing="0" id="report">';
		echo '<tr><th colspan=5 bgcolor="#F7CB52">TOTAL - 12 & UNDER</th></td>';
        echo '<tr><th>Toys Report</th><th colspan="2" align="center">'.$arrToys[1].'</th><th colspan="2" align="center">'.$arrToys[2].'</th></tr>';
        echo '<tr><th>Agency</th><th>Families</th><th>Individuals</th><th>Families</th><th>Individuals</th></tr>';
        $toys1tot = 0;
		$toys1ctot = 0; 
		$toys2tot = 0;
		$toys2ctot = 0;
	    foreach ($rep2 as $ag) {
				$toys1tot+=$ag['toys1'];
				$toys1ctot+=$ag['toys1c'];
				$toys2tot+=$ag['toys2'];
				$toys2ctot+=$ag['toys2c'];
                echo '<tr><td>'.$ag['name'].'</td><td>'.$ag['toys1'].'</td><td>'.$ag['toys1c'].'</td><td>'.$ag['toys2'].'</td><td>'.$ag['toys2c'].'</td></tr>';
        }
        echo '<tr><th>Total</th><th>'.$toys1tot.'</th><th>'.$toys1ctot.'</th><th>'.$toys2tot.'</th><th>'.$toys2ctot.'</th></tr>';
        echo '</table>';


        // Output Table
        echo '<p>&nbsp;</p>';
        echo '<table width="650" align="center" border="1" cellpadding="3" cellspacing="0" id="report">';
		echo '<tr><th colspan=5 bgcolor="#F7CB52">TOTAL - ALL AGES</th></td>';
        echo '<tr><th>Toys Report</th><th colspan="2" align="center">'.$arrToys[1].'</th><th colspan="2" align="center">'.$arrToys[2].'</th></tr>';
        echo '<tr><th>Agency</th><th>Families</th><th>Individuals</th><th>Families</th><th>Individuals</th></tr>';
        $toys1tot = 0;
		$toys1ctot = 0; 
		$toys2tot = 0;
		$toys2ctot = 0;
	    foreach ($rep as $ag) {
				$toys1tot+=$ag['toys1'];
				$toys1ctot+=$ag['toys1c'];
				$toys2tot+=$ag['toys2'];
				$toys2ctot+=$ag['toys2c'];
                echo '<tr><td>'.$ag['name'].'</td><td>'.$ag['toys1'].'</td><td>'.$ag['toys1c'].'</td><td>'.$ag['toys2'].'</td><td>'.$ag['toys2c'].'</td></tr>';
        }
        echo '<tr><th>Total</th><th>'.$toys1tot.'</th><th>'.$toys1ctot.'</th><th>'.$toys2tot.'</th><th>'.$toys2ctot.'</th></tr>';
        echo '</table>';
        echo '<p align="center"><a href="getReport.php?type=6">Click Here</a> to download this report.</p>';



 }

  else {
?>
        <div id="content-header"><h2>Error</h2></div>
	Invalid Report
<?
  }
}
include('includes/footer.html');
?>
