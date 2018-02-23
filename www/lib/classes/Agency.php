<?php
/* Agency.php
SQL queries for Agency related functions
*/

Class Agency {
        // Select Methods
        public static function getAllAgencies() {
                $db = new Database();
                $qry = "SELECT t_agency.id,t_agency.name,count(t_user_agency.user_id) as users
				FROM t_agency
				LEFT OUTER JOIN t_user_agency ON t_user_agency.agency_id = t_agency.id
				GROUP BY t_agency.id 
				ORDER BY t_agency.name";
                return $db->query($qry);
        }

	public static function getAgencies() {
		$db = new Database();
		$qry = "SELECT id,name from t_agency ORDER BY name";
		$rows = $db->query($qry);
		return $rows;
	}

	public static function getAgencyById($id) {
		$db = new Database();
                $qry = "SELECT id,name,foodDefault,toysDefault,hideFood,hideToys from t_agency where id = ?";	
		return $db->select($qry,$id)[0];
	}

        public static function chkName($name) {
                $db = new Database();
                $qry = "SELECT id FROM t_agency WHERE name = ?";
                return $db->query($qry,$name)->num_rows > 0 ? true: false;
        }

        public static function chkAgencyById($id) {
                $db = new Database();
                $qry = "SELECT id FROM t_application WHERE agency_id = ? LIMIT 1";
                return $db->select($qry,$id); 
        }

	//Insert Methods
	public static function insAgency($name,$food,$toys,$hidefood,$hidetoys) {
		$db = new Database();
                $qry = "INSERT INTO t_agency (name,foodDefault,toysDefault,hideFood,hideToys) VALUES (?,?,?,?,?)";
		return $db->execute($qry,$name,$food,$toys,$hidefood,$hidetoys);
	}

	// Update Methods
	function updAgency($id,$name,$food,$toys,$hidefood,$hidetoys) {
		$db = new Database();
                $qry = "UPDATE t_agency SET name = ?, foodDefault = ?, toysDefault = ?, hideFood = ?, hideToys = ? WHERE id = ?";
		return $db->execute($qry,$id,$name,$food,$toys,$hidefood,$hidetoys);
	}
	
	// Delete Methods
	public static function delAgency($id) {
                $db = new Database();
                $q = array();

                // Begin Transaction
                $db->autoCommit(false);
                
                $qry1 = "DELETE FROM t_agency WHERE id = ? LIMIT 1";
                $qry2 = "DELETE FROM t_user_agency WHERE agency_id = ?";
                $q[] = $db->execute($qry1,$id);
                $q[] = $db->execute($qry2,$id);

                // Error Check
                $err = 0;
                foreach ($q as $_) {
                        if ($_ === false) 
                        { 
                                $err++; 
                        }
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