<?php
$SKIP_SESSION = 1; // Tell init to skip session check
require_once('lib/init.php');
require_once('lib/classes/Agency.php');

$agencies = Agency::getAgencies();

User::insUser( array( 'username' => 'barryg', 'passwd' => 'killer80', 'access' =>  2, 'firstname' => 'James', 'lastname' => 'Gilbert', 'phone' => '706-955-4683', 'dlFlag' => 1, 'email' => 'barry@compguyaug.com', 'agency' => $agencies[0]->id ) );

?>
