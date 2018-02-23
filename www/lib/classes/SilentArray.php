<?php 
    class SilentArray implements ArrayAccess, Countable, Iterator{
        public $store = null;
        public $standInVal = null;
        public $shouldCoerceArraysToSilent = false;

        public function __construct($store = [],$standInVal = ''){
            $this->store = $store;
            $this->standInVal = $standInVal;
        }

        public function offsetSet($offset,$value){
            if(is_null($offset)){
                $this->store[] = $value;
            }
            else{
                $this->store[$offset] = $value;
            }
        }

        public function &offsetGet($offset){
            return isset($this->store[$offset]?$this->store[$offset]:$this->standInVal);
        }

        public function offsetExists($offset){
            return isset($this->store[$offset]);
        }

        public function offsetUnset($offset){
            unset($this->store[$offset]);
        }

        public function count(){
            return count($this->store);
        }
    }
?>