<?php 

require_once('../classes/Database.php');
require_once('../classes/Agency.php');
require_once('../classes/User.php');
require_once('../classes/General.php');
require_once('../classes/UWAssert.php');

class UWAssert{
    public static function AreEqual($actual,$expected){
        if($actual != $expected){
            echo "<p style='background-color:#ff0000; color:#ffffff'>Expected value was $expected but actual value was $actual </p>";
        }
        else{
            echo "<p style='background-color:#00ff00; color:#ffffff'>Test passed!</p>";
        }
    }
}

function InsertAdminUsers(){
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

function testAgency(){
    $db = new Database();
    $agencyName = "TestAgency".Time();
    $food = "1";
    $toys = "1";
    $hideFood = 0;
    $hideToys = 0;

    $result = Agency::insAgency($agencyName,$food,$toys,$hideFood,$hideToys);

    UWAssert::AreEqual($result,true);

    $agdt = $db->query("SELECT LAST_INSERT_ID()");

    $idRow = $agdt->fetch_assoc();
    $agid = $idRow['LAST_INSERT_ID()'];

    $tbl = $db->select('SELECT * FROM t_agency WHERE id = ?',$agid);

    UWAssert::AreEqual(count($tbl),1);
    $row = $tbl[0];
    UWAssert::AreEqual($row['name'],$agencyName);
    UWAssert::AreEqual($row['foodDefault'],$food);
    UWAssert::AreEqual($row['toysDefault'],$toys);
    UWAssert::AreEqual($row['hideFood'],$hideFood);
    UWAssert::AreEqual($row['hideToys'],$hideToys);

    $agencyName = "TestAgencyUpd".Time();
    $food = "0";
    $toys = "0";
    $hideFood = 1;
    $hideToys = 1;

    $result = Agency::updAgency($agid,$agencyName,$food,$toys,$hideFood,$hideToys);

    UWAssert::AreEqual($result,true);

    $tbl = $db->select('SELECT * FROM t_agency WHERE id = ?',$agid);

    UWAssert::AreEqual(count($tbl),1);
    $row = $tbl[0];
    UWAssert::AreEqual($row['name'],$agencyName);
    UWAssert::AreEqual($row['foodDefault'],$food);
    UWAssert::AreEqual($row['toysDefault'],$toys);
    UWAssert::AreEqual($row['hideFood'],$hideFood);
    UWAssert::AreEqual($row['hideToys'],$hideToys);

    $result = Agency::delAgency($agid);

    UWAssert::AreEqual($result,true);

    $tbl = $db->select('SELECT * FROM t_agency WHERE id = ?',$agid);

    UWAssert::AreEqual(count($tbl),0);

    $tbl = $db->select('SELECT * FROM t_agency WHERE name = ?',$agencyName);

    UWAssert::AreEqual(count($tbl),0);
}

testAgency();

?>