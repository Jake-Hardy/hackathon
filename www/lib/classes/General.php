<?php
/* General.php
general functions
*/

function genHash($plainText)
{
        $salt = '9Dj2a1x3He'; 
		//$hash = mhash(MHASH_SHA256,$plainText,$salt);
        $hash = hash('sha256',$plainText.$salt);
		
		//echo $hash; 		
		return $hash;
		/*return bin2hex($hash);*/
}

function genRandom()
{
	$code = md5(uniqid(rand(), true));
	return $code;
}

function encString($clear)
{
	$pubKey = openssl_pkey_get_public(file_get_contents(PUB_KEY));
	openssl_public_encrypt($clear,$crypt,$pubKey);
	openssl_free_key($pubKey);
	return bin2hex($crypt);
}

function buildQS($args) 
{  
	$query = "";  
	if (count($args) > 0) {  
		$sep = '?';  
		foreach($args as $arg => $value) {  
			$query .= $sep.$arg."=".$value;  
			$sep = '&amp;';  
		}  
	}  
	return $query; 
} 

function genPageLinks($page,$cnt)
{
	if ($cnt <= PER_PAGE) {
		return false;
	}
	
	// Break up QS to remove page
	$args = array();
	while (list($key, $val) = each($_GET)) {
		if ($key == 'page') { continue; }
		$args[$key] = $val;
	}

	// Build HTML
        $max = ceil($cnt / PER_PAGE);
        $ret = '';

        if ($page > 1) {
		$args['page'] = ($page -1);
                $ret .= '<a class="page" href="'.$_SERVER['PHP_SELF'].buildQS($args).'">&lt;&lt; Prev</a>&nbsp;&nbsp;';
        }
	else {
		$ret .= '<span class="pageInactive">&lt;&lt; Prev</span>&nbsp;&nbsp;';
	}
	$ret .= '<strong>Page '.$page.' of '.$max.'</strong>';
	if ($page < $max) {
		$args['page'] = ($page +1);
		$ret .= '&nbsp;&nbsp;<a class="page" href="'.$_SERVER['PHP_SELF'].buildQS($args).'">Next &gt;&gt;</a>';
	}
	else {
		$ret .= '&nbsp;&nbsp;<span class="pageInactive">Next &gt;&gt;</span>';
	}
	return $ret;
/*
	$ret = 'Page ';
	$x = 1;
	while (($x-1)*PER_PAGE < $cnt) {
		if ($x == $page) {
			$ret .= $page;
		}
		else {
			$ret .= '<a href="'.$_SERVER['PHP_SELF'].'?page='.$x.'">'.$x.'</a>';
		}
		$ret .= ', ';
		$x++;
	}
	return trim($ret,", ");
*/
}

function is_checked($value) {
	if ( strtoupper($value) == 'ON' || $value == 1 || $value == TRUE ) {
		return 1;
	}
	return 0;
}

function rmbr($string)
{
	return preg_replace('/\<br\s*\/?\>/i', "", $string);
}

function zeroize($value)
{
	return (isset($value) && is_numeric($value)) ? $value : '0';
}

function genError($msg)
{
	global $SHOWMENU;
	include($_SERVER["DOCUMENT_ROOT"].'/includes/header.html');
        echo '<div id="content-header"><h2>Error</h2></div>'."\n";
        echo '<p>'.$msg.'</p>'."\n";
        include($_SERVER["DOCUMENT_ROOT"].'/includes/footer.html');
}

/*
function genCSV($arr)
{
	$csv = "";
	while (list($key, $val) = each($arr))
	{
		$val = str_replace('"', '""', $val);
		$csv .= '"'.$val.'",';
	}
	$csv = substr($csv, 0, -1);
	$csv .= "\n";
	return empty($csv) ? false : $csv;
}
*/

function fputExcel($fh, $arr)
{
	$data = "";
	while (list($key, $val) = each($arr)) {
		$val = str_replace(array("\t","\r","\n",), '', $val);
		$val = str_replace('<br />', ' ', $val);
		$val = html_entity_decode($val);
		$data .= $val."\t";
	}
	$data = substr($data, 0, -1);
	$data .= "\r\n";
	if (!@fwrite($fh, $data)) {
		return FALSE;
	}
}

function dashify($date) {
	if (strlen($date) != 8) {
		return $date;
	}
	$mm = substr($date,0,2);
	$dd = substr($date,2,2);
	$yy = substr($date,4,4);
	return "$mm-$dd-$yy";
}

/* QuickForm Rules */

// Method to validate Dates
function chkDate($value) {
        $data = explode("-",dashify($value));
        if (count($data) != 3) {
                return false;
        }
        list($mm,$dd,$yy) = $data;
        if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd))
        {
                if (!checkdate($mm,$dd,$yy)) {
                        return false;
                }
                else {
                        return true;
                }
        }
        else {
                return false;
        }
}

// Method to ensure all fields for Spouse and Others are either empty or filled
// Also checks for user entering the same ssn more than once
// Also checks all DOBs
function chkForm(&$fields) {
    $errors = array();
	$chk = array();
	$flg = 0;
	
	$fields['ssn'] = $fields['ssn_0']."".$fields['ssn_1']."".$fields['ssn_2'];
	//mail("wesley@m3agency.com","SSN after",$fields['ssn']);
	
	$fields['dob'] = $fields['dob_0']."-".$fields['dob_1']."-".$fields['dob_2'];
	if ($fields['lastNameSp']) {
		$fields['ssnSp'] = $fields['ssnSp_0']."".$fields['ssnSp_1']."".$fields['ssnSp_2'];
		//$fields['ssnSp'] = str_replace($fields['ssnSp'],"-","");

		$fields['dobSp'] = $fields['dobSp_0']."-".$fields['dobSp_1']."-".$fields['dobSp_2'];
	}

	$_name = $fields['firstName'].' '.$fields['lastName'];
	if ($fields['ssn'] != '*********') {
		if (!is_numeric($fields['ssn'])) {
			$errors['ssn_0'] = 'SSN must only contain numbers';
		}
		if (!checkSsn($fields['ssn'],$_name )) {
			$errors['ssn_0'] = 'SSN Blocked';
		}
	}
	
	$_name = $fields['firstNameSp'].' '.$fields['lastNameSp'];
	if (strlen($fields['ssnSp']) == 9) {
		if ($fields['ssnSp'] != '*********') {
			if (!is_numeric($fields['ssnSp'])) {
				$errors['ssnSp_0'] = 'SSN must only contain numbers';
			}
			if (!checkSsn($fields['ssnSp'],$_name)) {
				$errors['ssnSp_0'] = 'SSN Blocked';
			}
		}
	}
	if (!chkDate($fields['dob'])) {
		$errors['dob_0'] = 'Please enter a valid date';
	}
	if ($fields['dobSp'] && !chkDate($fields['dobSp'])) {
		$errors['dobSp_0'] = 'Please enter a valid date';
	}

	// Applicant
	array_push($chk, $fields['ssn']);

        // Spouse
        if ( ($fields['lastNameSp'] && $fields['firstNameSp'] && $fields['dobSp'] && ($fields['ssnSp'] && strlen($fields['ssnSp']) == 9) ) ||
             (!$fields['lastNameSp'] && !$fields['firstNameSp'] && !$fields['dobSp'] && !$fields['ssnSp']) ) {
        }
        else {
			$errors['lastNameSp'] = 'Please enter all fields for Spouse';
			$errors['firstNameSp'] = '&nbsp;';
			$errors['dobSp'] = '&nbsp;';
			$errors['ssnSp'] = '&nbsp;';
        }
	if (is_numeric($fields['ssnSp'])) {
		if (in_array($fields['ssnSp'],$chk)) {
			$errors['ssnSp'] = 'Duplicate';
			$flg++;
		}
		else {
			array_push($chk,$fields['ssnSp']);
		}
	}

        // Others
        for ($x=0;$x<CNT_OTHERS;$x++) {
		$fields['dob'.$x] = $fields['dob'.$x.'_0']."-".$fields['dob'.$x.'_1']."-".$fields['dob'.$x.'_2'];
		$fields['ssn'.$x] = $fields['ssn'.$x.'_0']."".$fields['ssn'.$x.'_1']."".$fields['ssn'.$x.'_2'];
		
		if (strlen($fields['dob'.$x])>2 && !chkDate($fields['dob'.$x])) {
			$errors['dob'.$x.'_0'] = 'Please enter a valid date';
		}

			$_name = $fields['firstName'.$x]." ".$fields['lastName'.$x];
	        if (strlen($fields['ssn'.$x]) == 9) {
        	        if ($fields['ssn'.$x] != '*********') {
                	        if (!is_numeric($fields['ssn'.$x])) {
                        	        $errors['ssn'.$x.'_0'] = 'SSN must only contain numbers';
                       		}
                        	if (!checkSsn($fields['ssn'.$x],$_name)) {
                                	$errors['ssn'.$x.'_0'] = 'SSN Blocked';
                        	}
                	}
        	}

                if ( ($fields['lastName'.$x] && $fields['firstName'.$x] && $fields['ssn'.$x] && $fields['sex'.$x]) ||
                     (!$fields['lastName'.$x] && !$fields['firstName'.$x] && !$fields['ssn'.$x] && !$fields['sex'.$x]) ) {
                }
                else {
                        $errors['lastName'.$x] = 'Please enter all fields';
                        $errors['firstName'.$x] = '&nbsp;';
                        $errors['dob'.$x] = '&nbsp;';
                        $errors['ssn'.$x] = '&nbsp;';
                        $errors['sex'.$x] = '&nbsp;';
                }
		if (is_numeric($fields['ssn'.$x])) {
			if (in_array($fields['ssn'.$x],$chk)) { 
				$errors['ssn'.$x] = 'Duplicate';
				$flg++;
			} 
			else { 
				array_push($chk,$fields['ssn'.$x]); 
			}
		}
        }
	if ($flg) {
		$_SESSION['notice'] = '<span class="status">Error: The same SSN was entered more than once in this form</span>';
	}

        return empty($errors) ? true : $errors;
}

// check ssn for blocked or repeating digit (5)
function checkSsn($ssn, $name='')
{
	global $arrBlock;

	if (preg_match('/(\d)(?:\1){4,}/', $ssn)) { 
			//mail( "bsteele@uwcsra.org, support@m3agency.com", 'CSRAChristmas.org -- SSN Failed', "Someone entered a SSN with more than 4 repeating digits, ".$ssn, "From: CSRA Christmas <admin@csrachristmas.org>");		
			
			$user = User::getUser($_SESSION['uid']);
			
			$msg = "Someone entered a SSN with more than 4 repeating digits.\r\n";		
			$msg .= "Applicant: ".$name."\r\n";
			$msg .= "SSN #: ".$ssn."\r\n";
			$msg .= "------------------------------------------"."\r\n";
			$msg .= "First Name: ".$_SESSION['fn']."\r\n";
			$msg .= "UserName: ".$user['username']."\r\n";
			$msg .= "Agency ID: ".$user['agency_name']."\r\n";
			//$msg .= implode(",",$_SESSION);
			//print_r($_SESSION); exit; 
			mail( "codom@uwcsra.org", 'CSRAChristmas.org -- SSN Failed', $msg, "From: CSRA Christmas <admin@csrachristmas.org>");		

			//mail( "wesley@m3agency.com", 'CSRAChristmas.org -- SSN Failed', $msg, "From: CSRA Christmas <admin@csrachristmas.org>");		
			
	}
	if (preg_match('/(?!000)(?!666)^([0-8]\d{2})(\d{2})(\d{4})$/', $ssn)) {
		return true;
	}
	else if (in_array($ssn, $arrBlock)) {

			$user = User::getUser($_SESSION['uid']);
			
			$msg = "Someone entered a Blocked SSN.\r\n";		
			$msg .= "Applicant: ".$name."\r\n";
			$msg .= "SSN #: ".$ssn."\r\n";
			$msg .= "------------------------------------------"."\r\n";
			$msg .= "First Name: ".$_SESSION['fn']."\r\n";
			$msg .= "UserName: ".$user->username."\r\n";
			$msg .= "Agency ID: ".$user->agency_name."\r\n";
			//$msg .= implode(",",$_SESSION);
			//print_r($_SESSION); exit; 
			mail( "codom@uwcsra.org", 'CSRAChristmas.org -- SSN Blocked', $msg, "From: CSRA Christmas <admin@csrachristmas.org>");		

			//mail( "wesley@m3agency.com", 'CSRAChristmas.org -- SSN Blocked', $msg, "From: CSRA Christmas <admin@csrachristmas.org>");		

		return false;
	}	
	return false;
}

// Method to prevent dupes when editing
function chkFormDupe($fields) {
	$fields['ssn'] = $fields['ssn_0']."".$fields['ssn_1']."".$fields['ssn_2'];
	$fields['ssnSp'] = $fields['ssnSp_0']."".$fields['ssnSp_1']."".$fields['ssnSp_2'];
	// First run chkForm for standard errors, and skip this function if any found.
	$first = chkForm($fields);
	if (is_array($first)) {
		return $first;
	}
	// Now lets check for dupes
	$errors = array();
	// Others
	for ($x=0;$x<CNT_OTHERS;$x++) {
		$fields['ssn'.$x] = $fields['ssn'.$x.'_0']."".$fields['ssn'.$x.'_1']."".$fields['ssn'.$x.'_2'];
		if (is_numeric($fields['ssn'.$x]) && strlen($fields['ssn'.$x]) == 9 && genHash($fields['ssn'.$x]) != @$fields['oldSsn'.$x]) {
			if (App::chkDupe($fields['ssn'.$x],$fields['id'])) {
				$errors['ssn'.$x] = 'Duplicated';
			}
		}
	}
	// Primary
	if (is_numeric($fields['ssn']) && strlen($fields['ssn']) == 9 && genHash($fields['ssn']) != @$fields['oldSsn']) {
		if (App::chkDupe($fields['ssn'],$fields['id'])) {
			$errors['ssn'] = 'Duplicated'; 
		}
	}
	// Spouse
	if (is_numeric($fields['ssnSp']) && strlen($fields['ssnSp']) == 9 && genHash($fields['ssnSp']) != @$fields['oldSsnSp']) {
		if (App::chkDupe($fields['ssnSp'],$fields['id'])) {
			$errors['ssnSp'] = 'Duplicated'; 
		}
	}
	if ($errors) {
		$_SESSION['notice'] = '<span class="status">Error: requested SSN change or addition would cause a duplicate</span>';
	}
	
	return true;
	//return empty($errors) ? true : $errors;
}

/*
*I don't like doing it this way as it is very fragile, but regular call_user_func
*only accepts pass by value, and these guys demand pass by reference 
*/
function UW_call_user_func($funcName,&$valArr,&$filesArr){
	if($funcName === 'chkForm'){
		return chkForm($valArr);
	}
	if($funcName === 'chkFormDupe'){
		return chkFormDupe($valArr);
	}
	trigger_error('unhandled function call',E_WARNING);
}

?>
