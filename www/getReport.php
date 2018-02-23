<?php
// IE Hack
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
    session_cache_limiter("must-revalidate"); 
}

require_once('lib/init.php');
$UID = $_SESSION['uid'];
// Check which report submitted

if ($_GET['type'] == 1 && isset($_REQUEST['id']) && preg_match("/^\d+$/",$_REQUEST['id'])) {
	$id = $_REQUEST['id'];
error_log($id);
error_log("getReport");
	// Check if user is authorized to view report 
	$ret = User::chkUserInAgency($id,$UID);
	if ($ret != 1) {
	        genError('You are not authorized to view this report.');
	        exit;
	}

	// Run Agency Duplicate Report
	$rep = Report::genDupeReport($id);

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

elseif ($_GET['type'] == 2 && isset($_REQUEST['id']) && preg_match("/^\d+$/",$_REQUEST['id'])) {
	$id = $_REQUEST['id'];

	// Check if user is authorized to view report 
	$ret = User::chkUserInAgency($id,$UID);
	if ($ret != 1) {
	        genError('You are not authorized to view this report.',1);
	        exit;
	}

	// Run Agency Cleared Report
	$rep = Report::genClearedReport($id);

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
		$row2=array();
		foreach ($row as $key=>$value) {
			$row2[$key]=$value;
		}
		$row2['type'] = $arrType[$row['type']];
		$row2['food'] = $arrFood[$row['food']];
		$row2['toys'] = $arrToys[$row['toys']];
		fputExcel($out,$row2); 
	}


	fclose($out);
}

elseif ($_GET['type'] == 3) {
        // Run Age Report
        $rep = Report::genAgeReport($UID);

        // Output headers
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=ToysByAgeReport.xls");
        header("Expires: 0");

        // Output Excel Data
        $out = fopen('php://output', 'w');
	fputExcel($out, array('Agency','Source','Ages 0-2 Male','Ages 0-2 Female','Ages 3-5 Male','Ages 3-5 Female','Ages 6-8 Male','Ages 6-8 Female','Ages 9-12 Male','Ages 9-12 Female','Ages 13-14 Male','Ages 13-14 Female')); 
        foreach ($rep as $ag) {
                fputExcel($out, array($ag['name'],$arrToys[$ag['toys']],$ag['count3M'],$ag['count3F'],$ag['count35M'],$ag['count35F'],$ag['count68M'],$ag['count68F'],$ag['count912M'],$ag['count912F'],$ag['count1314M'],$ag['count1314F']));
        }
        fclose($out);
}

elseif ($_GET['type'] == 4) {
        // Run Family Size Report
        $rep = Report::genFamilySizeReport($UID);

        // Output headers
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=FamilySizeReport.xls");
        header("Expires: 0");

        // Output Excel Data
        $out = fopen('php://output', 'w');
        fputExcel($out, array('Agency','1-3 Members','4-6 Members','7+ Members'));
        foreach ($rep as $ag) {
                fputExcel($out, array($ag['name'],$ag['count13'],$ag['count46'],$ag['count7']));
        }
        fclose($out);
}

elseif ($_GET['type'] == 5) {
        // Run Food Report
        $rep = Report::genFoodReport($UID);

        // Output headers
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=FoodReport.xls");
        header("Expires: 0");

       // Output Excel Data
        $out = fopen('php://output', 'w');
        fputExcel($out, array('Agency',$arrFood[1].' - Families',$arrFood[1].' - Individuals',$arrFood[2].' - Families',$arrFood[2].' - Individuals'));
        foreach ($rep as $ag) {
                fputExcel($out, array($ag['name'],$ag['food1'],$ag['food1c'],$ag['food2'],$ag['food2c']));
        }
        fclose($out);
}

elseif ($_GET['type'] == 6) {
        // Run Food Report
        error_log("GET REPORT TOYS");
        //error_log($UID['uid']);
        $rep = Report::genToysReport($UID, FALSE);
        $rep2 = Report::genToysReport($UID, TRUE);

        // Output headers
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=ToysReport.xls");
        header("Expires: 0");

        $out = fopen('php://output', 'w');

       // Output Excel Data
        fputExcel($out, array('TOTAL - 12 & UNDER ONLY'));
        fputExcel($out, array('Agency',$arrToys[1].' - Families',$arrToys[1].' - Individuals',$arrToys[2].' - Families',$arrToys[2].' - Individuals'));
        foreach ($rep2 as $ag) {
                fputExcel($out, array($ag['name'],$ag['toys1'],$ag['toys1c'],$ag['toys2'],$ag['toys2c']));
        }

        fputExcel($out, array(''));
        fputExcel($out, array('TOTAL - ALL AGES'));
        fputExcel($out, array('Agency',$arrToys[1].' - Families',$arrToys[1].' - Individuals',$arrToys[2].' - Families',$arrToys[2].' - Individuals'));
        foreach ($rep as $ag) {
                fputExcel($out, array($ag['name'],$ag['toys1'],$ag['toys1c'],$ag['toys2'],$ag['toys2c']));
        }


        fclose($out);

}

elseif ($_GET['type'] == 7) {

	// Check Permission
	if ($_SESSION['dl'] != 1) {
		genError('You do not have permission to view this report.');
		exit;
	}

        // Run Export 
        $rep = Report::genExport($UID);

        // Get Field Names
        $na = array();
        while (list($key,$val) = each($rep[0])) {
                array_push($na,$key);
        }
        array_unshift($rep,$na);

        // Output headers
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=export.xls");
        header("Expires: 0");

        // Output Excel Data
        $out = fopen('php://output', 'w');
        foreach ($rep as $row) {
                fputExcel($out,$row);
        }
        fclose($out);
}

else {
	genError('Invalid Report');
	exit;
}

?>
