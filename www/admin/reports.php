<?php
require_once('../lib/init.php');
require_once('../lib/admin.php');
include('../includes/admin_header.html');

// Check if report submitted
if (!isset($_GET['type'])) {
	// Default
?>
	<div id="content-header"><h2>Reports</h2></div>
	<p>Please choose which report you would like to run</p>
	<ul>
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?type=1">Run Duplicate Report</a></li>
	<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?type=2">Run Cleared Report</a></li>
	</ul>
<?php
}
else {
  if ($_GET['type'] == 1) {
	// Run Duplicate Summary Report
	$rep = Report::genDupeSummaryReportAdmin();

	// Output Table
	echo '<div id="content-header"><h2>Duplicate Report Summary</h2></div><p>&nbsp;</p>';
	echo '<table width="600" align="center" border="1" cellpadding="3" cellspacing="0" id="report">';
	echo '<tr><th>Agency</th><th>Duplicate Applications</th><th>Action</th></tr>';
	$dupetot = 0;
	foreach ($rep as $_) {
		$dupetot+=$_['count'];
		echo '<tr><td>'.$_['name'].'</td><td>'.$_['count'].'</td><td><a href="getReport.php?type=1&id='.$_['id'].'">Download Report</a></td></tr>';
	}
    echo '<tr><th>Total</th><th>'.$dupetot.'</th><th>-</th></tr>';
	echo '</table>';
        echo '<p align="center"><a href="getReport.php?type=1">Click Here</a> to download a combined report.</p>';
  }

  elseif ($_GET['type'] == 2) {
	// Run Cleared Summary Report
	$rep = Report::genClearedSummaryReportAdmin();

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
        echo '<p align="center"><a href="getReport.php?type=2">Click Here</a> to download a combined report.</p>';
 }

  else {
?>
        <div id="content-header"><h2>Error</h2></div>
	Invalid Report
<?
  }
}
include('../includes/footer.html');
?>
