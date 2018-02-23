<?php
class Database {
    // The database connection
    protected static $connection;

    /**
     * Connect to the database
     * 
     * @return bool false on failure / mysqli MySQLi object instance on success
     */
    public function connect() {    
        // Try and connect to the database
        if(!isset(self::$connection)) {
            // Load configuration as an array. Use the actual location of your configuration file
            $config = parse_ini_file('config.ini'); 
            self::$connection = new mysqli($config['host'],$config['username'],$config['password'],$config['dbname']);
        }

        // If connection was not successful, handle the error
        if(self::$connection === false) {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }
        return self::$connection;
    }

    /**
     * Query the database
     *
     * @param $query The query string
     * @return mixed The result of the mysqli::query() function
     */
    public function query($query, ...$args) {
        $prepared = $this->prepareAndBind($query, ...$args);
        $prepared->execute();
        $result = $prepared->get_result();
        return $result;
    }

    /**
     * Return one row given a query
     * 
     */
    public function getOne($query, ...$args) {
        $result = $this->query($query, ...$args);
        return $result->num_rows > 0 ? $result->fetch_row()[0]: null;
    }

    public function prepare($query) {
        // Connect to the database
        $connection = $this->connect();
        // Prepare statement
        if (!($stmt = $connection->prepare($query))) {
            error_log("Database::prepare: prepare failed. Query: ".$query);
        }
        return $stmt;
    }

    public function buildTypeString(...$args){
        $arrSize = count($args);
        $paramTypes = '';
        for($idx = 0;$idx < $arrSize;$idx++){ //build type string
            if(gettype($args[$idx]) === 'boolean'){
                $args[$idx] = intval($args[$idx]); //mysql likes bools as 1 or 0
            }
            $paramTypes .= self::getDBParamTypeStr($args[$idx]);
        }  
        return $paramTypes;
    }

    public function prepareAndBind($statement, ...$args) {
        if(!$this->connect()){
            return null;
        }
        
        if(!($prepared = self::$connection->prepare($statement)))
        {
            error_log("Database::prepare: prepare failed. Query: ".$statement);
        }
        $arrSize = count($args);
        if($arrSize > 0){
            $paramTypes = $this->buildTypeString(...$args);
            $prepared->bind_param($paramTypes,...$args);
        }
        return $prepared;
    }

    public function executePrepared($stmt, ...$args) {
        $arrSize = count($args);
        if($arrSize > 0){
            $paramTypes = $this->buildTypeString(...$args);
            $stmt->bind_param($paramTypes,...$args);
        }
        $ret = $stmt->execute();
        return $ret;
    }

    public function execute($stmt, ...$args) {
        $prepared = $this->prepareAndBind($stmt,...$args);
        $ret = $prepared->execute();
        return $ret;
    }

    /**
     * Fetch rows from the database (SELECT query)
     *
     * @param $query The query string
     * @return bool False on failure / array Database rows on success
     */
    public function select($query, ...$args) {
        $result = $this -> query($query, ...$args);
        if($result === false) {
            return false;
        }
        $rows = array();
        while ($row = $result -> fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch the last error from the database
     * 
     * @return string Database error message
     */
    public function error() {
        $connection = $this -> connect();
        return $connection -> error;
    }

    /**
     * Quote and escape value for use in a database query
     *
     * @param string $value The value to be quoted and escaped
     * @return string The quoted and escaped string
     */
    public function quote($value) {
        $connection = $this -> connect();
        return "'" . $connection -> real_escape_string($value) . "'";
    }

    /**
    * Link
    * Assumes connect() has been called. Otherwise returned vaule is useless}
    */
    public function link() { return Self::$connection; }

    /*
    *this is a wrapper for mysqli autoCommit
    */
    public function autoCommit($isOn){
        if(!$this->connect()){
            trigger_error('Database connection could not be established',E_USER_NOTICE);
        }
        self::$connection->autoCommit($isOn);
    }

    /*
    *this is a wrapper for mysqli commit
    */
    public function commit(){
        return self::$connection->commit();
    }

    public static function getDBParamTypeStr($statementParam){
        $type = gettype($statementParam);
        if($type === 'integer'){
            return 'i';
        }
        if($type === 'double'){
            return 'd';
        }
        if($type === 'string' || $type === 'NULL'){ 
            /*
            we can't really infer from null what the actuall type is, so we'll send it as string and mysql can usually coerce 
            it to the proper type. If that is untenable, use the 'WithTypeString' variant of the method you want to use and
            explicitly provide the type string.
            */
            return 's';
        }
        if($type === 'boolean'){
            trigger_error('For mySql bools should be converted to int.',E_USER_NOTICE);
        }
        else{
            trigger_error('object and array types are not handled here',E_USER_NOTICE);
        }
        return NULL;
    }

    public function getAll($queryStatement, ...$args){
        $result = $this->query($queryStatement, ...$args);
        $tbl = array();
        while($row = $result->fetch_row()){
            /*apparently, the below syntax is the preferred way to push single items to arrays.
            php is weird.
            */
            $tbl[] = $row;
        }
        return $tbl;
    }

    public function rollback(){
        return self::$connection->rollback();
    }
}
