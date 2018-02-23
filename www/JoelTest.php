<?php 

    require_once("lib/classes/Database.php");

    class Gands{
        private $pizza;
        private $milkshake;
        public $sandwich;

        public function __construct(){
            $this->pizza = 0;
            $this->milkshake = 0;
            $this->sandwich = 0;
        }

        public function __get($name){
            if(property_exists($this,$name)){
                switch($name){
                    case 'pizza':
                        return $this->pizza;
                    case 'milkshake':
                        return $this->$name + 1;
                    default:
                        return null;
                }
                
            }
        }

        public function __set($name,$value){
            if(property_exists($this,$name)){
                switch($name){
                    case 'pizza':
                        $this->pizza = $value;
                        break;
                    case 'milkshake':
                        $this->$name = $value + 2;
                        break;
                    default:

                }
                
            }
        }

        public function SetA($value){
            $this->pizza = $value;
        }

        public function GetA(){
            return $this->pizza;
        }
    }

    class Statica{
        public static $a;
        public function addAndPrint(){
            self::$a++;
            print(self::$a);
        }

        public function addAndPrint2(){
            // $this->a++;
            // print($this->a);

            $this::$a++;
            print($this::$a);
        }

        public function justPrint(){
            print($this::$a);
        }
    }

    class overLoading{

    }

    class DumbArray implements ArrayAccess{
        private $store = [];

        public function offsetSet($offset,$value){
            if(is_null($offset)){
                $this->store[] = $value;
            }
            else{
                $this->store[$offset] = $value;
            }
        }

        public function &offsetGet($offset){
            return $this->store[$offset];
        }

        public function offsetExists($offset){
            return isset($this->store[$offset]);
        }

        public function offsetUnset($offset){
            unset($this->store[$offset]);
        }

        public function __toString(){
            return "Aye!";
        }

    }

    function demoGands(){
        $t1 = new Gands();

        $t1->sandwich = 12;
        print($t1->sandwich);

        $t1->SetA(8);
        print($t1->GetA());

        $t1->pizza = 5;
        $t1->milkshake = 7;

        print($t1->pizza);
        print($t1->milkshake);
    }
    
    function areArraysReferences($arr){
        $arr[0] = 5;
    }

    function explRef(&$arr){
        $arr[1] = 3;
    }

    function demoAreArraysRef(){
        $arr = [7,9];
        areArraysReferences($arr);
        var_dump($arr);
        explRef($arr);
        var_dump($arr);
    }

    function demoStaticVars(){
        $s1 = new Statica();
        $s1->addAndPrint();
        $s1->addAndPrint2();
        $s1::$a++;
        print($s1::$a);
        $s2 = new Statica();
        $s2->justPrint();
        $s2->addAndPrint2();
        $s1->justPrint();
    }

    function defaulto($a = []){
        $a[] = 5;
        print("It didn't crash");
        print($a[0]);
    }

    function arrayTestStuff(){
        // $arr = "";
        // $thing = $arr['foo'];
        $d1 = new DumbArray();
        $arr1 = [];
        $arr2 = [];
        $arr1['c'] = "Hello";
        $arr1['e'] = "Red";
        $arr2['d'] = "Goodbye";
        $arr2['f'] = "Blue";
        $d1['a'] = $arr1;
        $d1['b'] = $arr2;
        print($d1['a']['c']);
        $d1['a']['c'] = 7;
        print($d1['a']['c']);
        $r = $d1['b']['d'];
        $r = 14;
        print($d1['b']['d']);
        print($d1);
    }

    function demoIsSetStuff(){
        $a = ["color" => ["Red","Blue"],"num" => 5];
        $b = null;
        print("Hello");
        print((int)isset($a["num"]["Red"]));
        print((int)isset($a["num"]));
        print((int)isset($b["num"]["Red"]));
    }

    function demoDatabaseStuff(){
        $db = new Database();
        $db->autoCommit ( false );
        $db->execute("INSERT INTO `daniel.hanson`.`t_agency`
        (`name`,
        `foodDefault`,
        `toysDefault`,
        `hideFood`,
        `hideToys`)
        VALUES
        ('db test4',
        '0',
        '0',
        2,
        2);");
        $db->rollback();
        print("All done");
    }

    function demoKvpStuff(){
        $a = array("k1" => "val1","k2" => "val2");
        
    }
?>