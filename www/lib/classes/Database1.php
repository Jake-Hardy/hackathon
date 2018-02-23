<?php

/**#@+
 * Includes
 */
 require_once('DB.php'); 
/**#@-*/

Class Database {

	/**
	 * @var DB
	 * @access public
	 */
	private $db;
	private static $instance ;
	/**
	 * Create instance of cDatabaseConnection and make {@link DB} object
	 */
	function __construct() {
		$servername = "agsmonl01";
		$username = "daniel.hanson";
		$password = "rse5tDZv7dtUljnT";
		$dbname = "daniel.hanson";

		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);

		// Check connection
		if ($conn->connect_error) {
    		die("Connection failed: " . $conn->connect_error);
		} 

		//$user	= 'daniel.hanson';
		//$pass	= 'rse5tDZv7dtUljnT';
		//$host	= 'localhost';
		//$name	= 'daniel.hanson';

		// Data Source Name: This is the universal connection string
		//$dsn = "mysql://$user:$pass@$host/$name";
		// Debug - mysqlDbg

		// DB::connect will return a PEAR DB object on success
		// or an PEAR DB Error object on error
		//$this->db = DB::connect($dsn);
		
		// With DB::isError you can differentiate between an error or
		// a valid connection.
		//if (DB::isError($this->db)) {
			//die($this->db->getDebugInfo());
			//die($this->db->getMessage());
		//}
		//$this->db->query("SET NAMES 'utf8'");
		//$this->db->setFetchMode(DB_FETCHMODE_OBJECT);

	}

	/**
	* Get a reference to the static instance of the database connection object
	*
	* @return reference a reference to the DB object
	*/
	public static function getInstance() {
		if (!self::$instance) { 
			self::$instance = new Database(); 
			return $instance ;
		}
	}
}

?>


