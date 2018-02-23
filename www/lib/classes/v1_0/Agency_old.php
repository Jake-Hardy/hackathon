<?
/* Agency.php
SQL queries for Agency related functions
*/

Class Agency {

        // Select Methods
        function getAllAgencies() {
                $db = Database::getInstance();
                return $db->getAll('SELECT t_agency.id,t_agency.name,count(t_user_agency.user_id) as users
				FROM t_agency
				LEFT OUTER JOIN t_user_agency ON t_user_agency.agency_id = t_agency.id
				GROUP BY t_agency.id 
				ORDER BY t_agency.name');
        }

	function getAgencies() {
		$db = Database::getInstance();
		return $db->getAll('SELECT id,name from t_agency');
	}

	function getAgencyById($id) {
		$db = Database::getInstance();	
		return $db->getRow('SELECT id,name,foodDefault,toysDefault from t_agency where id = ?', array($id));
	}

        function chkName($name) {
                $db = Database::getInstance();
                return $db->getOne('SELECT id FROM t_agency WHERE name = ?', array($name));
        }

        function chkAgencyById($id) {
                $db = Database::getInstance();
                return $db->getOne('SELECT id FROM t_application WHERE agency_id = ? LIMIT 1', array($id));
        }

	// Insert Methods
	function insAgency($name,$food,$toys) {
		$db = Database::getInstance();
		return $db->query('INSERT INTO t_agency (name,foodDefault,toysDefault) VALUES (?,?,?)', array($name,$food,$toys));
	}

	// Update Methods
	function updAgency($id,$name,$food,$toys) {
		$db = Database::getInstance();
		return $db->query('UPDATE t_agency SET name = ?, foodDefault = ?, toysDefault = ? WHERE id = ?', array($name,$food,$toys,$id));
	}
	
	// Delete Methods
	function delAgency($id) {
                $db = Database::getInstance();
                $q = array();

                // Begin Transaction
                $db->autoCommit(false);

                $q[] = $db->query('DELETE FROM t_agency WHERE id = ? LIMIT 1', array($id));
                $q[] = $db->query('DELETE FROM t_user_agency WHERE agency_id = ?', array($id));

                // Error Check
                $err = 0;
                foreach ($q as $_) {
                        if ($_ != 1) { $err++; }
                }
                if ($err) {
                        // Failure - Rollback
                        $db->rollback();
                        return false;
                }
                else {
                        // Success - Commit
                        $db->commit();
                        return true;
                }
	}
}


?>
