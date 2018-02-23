<?
require_once('lib/init.php');

if(isset($UID)){
    User::delSession($UID);
}
session_destroy(); 

header('Location: login.php');
exit;
?>
