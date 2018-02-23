<?php
/*
United Way Christmas Clearinghouse
by Nick Deyoe <nick@massmediamktg.com>
Copyright 2007 Mass Media Marketing
*/


$pathAddition = __DIR__.'/../admin/';
set_include_path(get_include_path().PATH_SEPARATOR.$pathAddition);

// Output header
header('Content-Type: text/html; charset=utf-8');
include("classes/App.php");

$app = new App();

// Check for maintenance mode
$MAINT=0;
/*
if ($_SERVER['REMOTE_ADDR'] != '76.125.24.52') {
	$MAINT = 1;
}
*/
if ($MAINT == 1) { header('Location: /maintenance.html'); exit; }

// Error reporing
//ini_set("display_errors", 1);
//ini_set("log_errors", 1);
error_reporting(E_ALL);

// Load common modules
require_once('classes/Database.php');
require_once('classes/General.php');
require_once('classes/User.php');
require_once('classes/App.php');
require_once('classes/Agency.php');
require_once('classes/Report.php');

// Session values
ini_set('session.gc_maxlifetime',2880); # 48 Mins
//ini_set('session.save_path','/tmp/uwags');
//ini_set('session.use_only_cookies',1);

// Increase Memory
ini_set('memory_limit','256M');

/********************************
/* IMPORTANT NOTE
/* The logout threshold is set in 3 places:
/* 1) Above in session.gc_maxlifetime
/* 2) In general.js, in timer function
/* 3) in bin/sessionCron.php, in SQL
/*******************************/

// Start Session and Authenticate

if(!isset($SKIP_SESSION)) {
	session_start();
	if ((!isset($_SESSION['auth'])) || ($_SESSION['auth'] != 1)) {
		header('Location: /login.php?r='.urlencode($_SERVER['REQUEST_URI']));
		exit();
	}
	$UID = User::chkSession($_SESSION['rand'],$_SERVER['REMOTE_ADDR']);
	if (!$UID) {
                header('Location: /login.php?r='.urlencode($_SERVER['REQUEST_URI']));
                exit();
	}
	$SHOWMENU = 1;
}

// Define common values
define('CNT_OTHERS', 12); // Number of other members to list
define('PER_PAGE', 20); // 20 // Number of records on paginated pages
define('STD_ERR', "There was an error processing your request. Please use your browser's refresh button to try again.<br />Please contact the system administrator if this error persists."); // Standerd Error Message
define('PUB_KEY','file:///'.__DIR__.'/../public.pem');

// Get Season from DB
$season = $app->getSeason();
define('SEASON', $season);
$tft = $app->getTFT();
define('TFT', $tft);

// Setup arrays
$arrType = array(''=>'Type', 1=>'Applicant', 2=>'Spouse', 3=>'Member'); // Types of Family Members

// Toy Choices
if(TFT):
	$_globalHideToys = 0;
	$arrToys = array(''=>'Toys', 1=>'Provided By Agency', 2=>'Needed From TFT', 0=>'Not Required'); 
else: 
	$_globalHideToys = 1;
	$arrToys = array(''=>'Toys', 1=>'Provided By Agency', 0=>'Not Required'); 
endif; 

// Food Choices
$arrFood = array(''=>'Food', 1=>'Provided By Agency', 2=>'Needed From SA', 0=>'Not Required'); 

// Blocked SSNs
$arrBlock = array('123456789', '987654321'); 

?>