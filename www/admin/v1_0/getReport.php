<?
// IE Hack
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
    session_cache_limiter("must-revalidate"); 
}

require_once('../lib/init.php');
require_once('../lib/admin.php');

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
	while (list($key,$val) = each($rep[0])) {
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
		$row->type = $arrType[$row->type];
		$row->food = $arrFood[$row->food];
		$row->toys = $arrToys[$row->toys];
                fputExcel($out,$row);
        }
        fclose($out);
}

elseif ($_GET['type'] == 2) {
	if (isset($_REQUEST['id']) && preg_match("/^\d+$/",$_REQUEST['id'])) {
		$id = $_REQUEST['id'];

		// Run Agency Cleared Report
		$rep = Report::genClearedReport($id);
	}
	else {
		$id = 'all';

		// Run Combined Cleared Report
		$rep = Report::genClearedReportAdmin();
	}

	// Get Field Names
	$na = array();
	while (list($key,$val) = each($rep[0])) {
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
		$row->type = $arrType[$row->type];
		$row->food = $arrFood[$row->food];
		$row->toys = $arrToys[$row->toys];
		fputExcel($out,$row); 
	}
	fclose($out);
}


else {
	genError('Invalid Report');
	exit;
}

?>
