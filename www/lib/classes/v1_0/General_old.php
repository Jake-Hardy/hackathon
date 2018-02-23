<?
/* General.php
general functions
*/

function genHash($plainText)
{
        $salt = '9Dj2a1x3He'; 
        $hash = mhash(MHASH_SHA256,$plainText,$salt);
	return bin2hex($hash);
}

function genRandom()
{
	$code = md5(uniqid(rand(), true));
	return $code;
}

function encString($clear)
{
	$pubKey = openssl_get_publickey(PUB_KEY);
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
		$val = str_replace(array("\t","\r","\n"), '', $val);
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
function chkForm($fields) {
        $errors = array();
	$chk = array();
	$flg = 0;

	// Applicant
	array_push($chk, $fields['ssn']);

        // Spouse
        if ( ($fields['lastNameSp'] && $fields['firstNameSp'] && $fields['dobSp'] && $fields['ssnSp']) ||
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
                if ( ($fields['lastName'.$x] && $fields['firstName'.$x] && $fields['dob'.$x] && $fields['ssn'.$x] && $fields['sex'.$x]) ||
                     (!$fields['lastName'.$x] && !$fields['firstName'.$x] && !$fields['dob'.$x] && !$fields['ssn'.$x] && !$fields['sex'.$x]) ) {
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

// Method to prevent dupes when editing
function chkFormDupe($fields) {
	// First run chkForm for standard errors, and skip this function if any found.
	$first = chkForm($fields);
	if (is_array($first)) {
		return $first;
	}
	// Now lets check for dupes
	$errors = array();
	// Others
	for ($x=0;$x<CNT_OTHERS;$x++) {
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
	return empty($errors) ? true : $errors;
}

?>
