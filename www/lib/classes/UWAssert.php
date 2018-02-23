<?php 
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
?>