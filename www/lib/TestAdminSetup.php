<?php 

require_once('classes/Database.php');
require_once('classes/Agency.php');
require_once('classes/User.php');
require_once('classes/General.php');

function TestAgency(){
    $db = new Database();
    $result = Agency::insAgency("First Class Agency","1","1",0,0);

    $agdt = $db->query("SELECT LAST_INSERT_ID()");

    $row = $agdt->fetch_assoc();
    $agid = $row['LAST_INSERT_ID()'];

    $insData = array('username' => "rsi", 'passwd' =>"Password", 'agency'=>$agid,'firstname'=>"Sergey",'lastname' => "brin",'access' => '2', 'phone' => "", 'email' => "rsi_hackaton@mailinator.com",'dlFlag' => "1");
    $result = User::insUser($insData);

    $insData = array('username' => "joel", 'passwd' =>"Password", 'agency'=>$agid,'firstname'=>"Joel",'lastname' => "P",'access' => '2', 'phone' => "", 'email' => "joel.pridgen@ruralsourcing.com",'dlFlag' => "1");
    $result = User::insUser($insData);

    $insData = array('username' => "stan", 'passwd' =>"Password", 'agency'=>$agid,'firstname'=>"Stan",'lastname' => "s",'access' => '2', 'phone' => "", 'email' => "stanley.swinford@ruralsourcing.com",'dlFlag' => "1");
    $result = User::insUser($insData);

    $insData = array('username' => "morgan", 'passwd' =>"Password", 'agency'=>$agid,'firstname'=>"Morgan",'lastname' => "s",'access' => '2', 'phone' => "", 'email' => "morgan.smith@ruralsourcing.com",'dlFlag' => "1");
    $result = User::insUser($insData);

    $insData = array('username' => "kristine", 'passwd' =>"Password", 'agency'=>$agid,'firstname'=>"Kristine",'lastname' => "r",'access' => '2', 'phone' => "", 'email' => "kristine.rooks@ruralsourcing.com",'dlFlag' => "1");
    $result = User::insUser($insData);

    $insData = array('username' => "ashley", 'passwd' =>"Password", 'agency'=>$agid,'firstname'=>"Ashley",'lastname' => "c",'access' => '2', 'phone' => "", 'email' => "ashley.cordell@ruralsourcing.com",'dlFlag' => "1");
    $result = User::insUser($insData);

}

TestAgency();

?>