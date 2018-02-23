<?php
// IE Hack
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
    session_cache_limiter("must-revalidate"); 
}

require_once('../lib/init.php');
require_once('../lib/admin.php');
$UID = $_SESSION['uid'];
// Check which report submitted

if ($_GET['type'] == 1) {
	if (isset($_REQUEST['id']) && preg_match("/^\d+$/",$_REQUEST['id'])) {
		$id = $_REQUEST['id'];

		// Run Agency Duplicate Report
		$rep = Report::genDupeReport($id);
	}
	else {
		$id = 'all';
		
		// Run Combined Duplicate Report
		$rep = Report::genDupeReportAdmin();
	}

	// Get Field Names
	$na = array();
	foreach($rep[0] as $key => $item){
		array_push($na,$key);
	}
	array_unshift($rep,$na);

        // Output headers
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=dupesReport_agency-$id.xls");
        header("Expires: 0");

        // Output Excel Data
        $out = fopen('php://output', 'w');
	foreach ($rep as $row) {
		$row2=array();
		foreach ($row as $key=>$value) {
			$row2[$key]=$value;
		}
		if(!isset($row2['type']))
		{
			fputExcel($out,$row2); 
			continue;
		}
		$row2['type'] = $arrType[$row['type']];
		$row2['food'] = $arrFood[$row['food']];
		$row2['toys'] = $arrToys[$row['toys']];
		fputExcel($out,$row2); 
	}
    fclose($out);
}

elseif ($_GET['type'] == 2) {
	if (isset($_REQUEST['id']) && preg_match("/^\d+$/",$_REQUEST['id'])) {
		$id = $_REQUEST['id'];

		// Run Agency Cleared Report
		$rep = Report::genClearedReport($id);
		error_log("Cleared");
	}
	else {
		$id = 'all';

		// Run Combined Cleared Report
		set_time_limit(120);	// This report takes awhile
		$rep = Report::genClearedReportAdmin();
		error_log("Cleared Admin");
		// Note - in the future as the number of apps grows we may have to run this sql in batches, basically like pagination. keep the filehandle open and loop over the output part.
	}

	// Get Field Names
	$na = array();
	while (list($key,$val) = each($rep)) {
		array_push($na,$key);
	}
	array_unshift($rep,$na);

	// Output headers 
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=clearedReport_agency-$id.xls");
	header("Expires: 0");  

	// Output Excel Data
	$out = fopen('php://output', 'w');
	foreach ($rep as $row) {
		$row2=array();
		foreach ($row as $key=>$value) 
		{
			$row2[$key]=$value;
		}
		$row2['type'] = $arrType[$row['type']];
		$row2['food'] = "";//$arrFood[$row['food']];
		$row2['toys'] = $arrToys[$row['toys']];
		fputExcel($out,$row2); 
	}
	fclose($out);
	
}


else {
	genError('Invalid Report');
	exit;
}

?>
