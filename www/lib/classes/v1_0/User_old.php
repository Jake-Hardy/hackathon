<?
/* User.php
SQL queries for User related functions
*/

Class User {

	// Select Methods
	function chkLogin($user,$pass) {
		$db = Database::getInstance();
		return $db->getOne('SELECT id FROM t_user WHERE username = ? AND password = ? AND status = 1', array($user,genHash($pass)));
	}

	function chkUsername($user) {
		$db = Database::getInstance();
		return $db->getOne('SELECT id FROM t_user WHERE username = ?', array($user));
	}

	function chkSession($rand,$ip) {
		$db = Database::getInstance();
		$ret = $db->getOne('SELECT login_id FROM t_session where hash = sha1(?)', array($rand.$ip));
		if ($ret > 0)
		{
			$db->query('UPDATE t_session SET tstamp = NOW() WHERE login_id = ?', array($ret));
		}
		return $ret;
	}

        function chkUserById($id) {
                $db = Database::getInstance();
                return $db->getOne('SELECT id FROM t_application WHERE user_id = ? LIMIT 1', array($id));
        }

	function chkUserInAgency($aid,$uid) {
		$db = Database::getInstance();
		$ret = $db->getOne('SELECT user_id from t_user_agency WHERE agency_id = ? AND user_id = ?', array($aid,$uid));
		if ($ret > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	function getInfo($id) {
		$db = Database::getInstance();
		return $db->getRow('SELECT accessLevel,firstName,dlFlag FROM t_user WHERE id = ?', array($id));
	}
	
	function getAgencies($id) {
		$db = Database::getInstance();
		return $db->getAll('SELECT t_agency.id,t_agency.name,t_agency.foodDefault,t_agency.toysDefault FROM t_agency,t_user_agency WHERE t_agency.id = t_user_agency.agency_id AND t_user_agency.user_id = ? ORDER BY t_user_agency.type DESC, t_agency.name ASC', array($id));
	}
	
	function getUser($id) {
		$db = Database::getInstance();
		return $db->getRow('
			SELECT t_user.id,t_agency.name as agency_name,t_agency.id as agency_id,username,status,accessLevel,firstName,lastName,phone,dlFlag
			FROM t_user
			LEFT OUTER JOIN t_user_agency ON t_user_agency.user_id = t_user.id AND t_user_agency.type = "1"
			LEFT OUTER JOIN t_agency ON t_user_agency.agency_id = t_agency.id
			WHERE t_user.id = ?
			ORDER BY username', array($id));
	}

	function getAllUsers() {
		$db = Database::getInstance();
		return $db->getAll('SELECT t_user.id,t_agency.name,username,status,accessLevel,firstName,lastName,phone
                                FROM t_user
				LEFT OUTER JOIN t_user_agency ON t_user_agency.user_id = t_user.id AND t_user_agency.type = "1"
				LEFT OUTER JOIN t_agency ON t_user_agency.agency_id = t_agency.id
				ORDER BY username');
	}

	function getUsersByAgency($id) {
		$db = Database::getInstance();
		return $db->getAll('SELECT t_user.id,NULL as name,username,status,accessLevel,firstName,lastName,phone
				FROM t_user, t_user_agency
				WHERE t_user_agency.user_id = t_user.id 
				AND t_user_agency.agency_id = ?
				ORDER BY username',array($id));
	}

	// Insert Methods
	function insUser ($fv) {
		$db = Database::getInstance();
		$q = array();

                // Begin Transaction
                $db->autoCommit(false);

		// User Info
		$q[] = $db->query('INSERT INTO t_user (username,password,accessLevel,firstName,lastName,phone,dlFlag) VALUES (?,?,?,?,?,?,?)',array($fv['username'],genHash($fv['passwd']),$fv['access'],$fv['firstname'],$fv['lastname'],$fv['phone'],zeroize($fv['dlFlag'])));
		$uid = $db->getOne('SELECT LAST_INSERT_ID()');

		// User's Primary and Additional Agencies
		$sth = $db->prepare('INSERT INTO t_user_agency (user_id,agency_id,type) VALUES (?,?,?)');
		$data = array();
		array_push($data, array($uid,$fv['agency'],'1'));
		if (isset($fv['agencySec'])) {
			foreach ($fv['agencySec'] as $_) {
				array_push($data, array($uid,$_,'0'));
			}
		}
		$q[] = $db->executeMultiple($sth,$data);

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

	function insSession ($uid,$rand,$ip) {
		$db = Database::getInstance();
		$db->query('DELETE FROM t_session WHERE login_id = ?',array($uid));
		$db->query('INSERT INTO t_session (login_id,hash) VALUES (?,SHA1(?))',array($uid,$rand.$ip));
	}
		
	// Update Methods
	function updUser ($fv) {
		$db = Database::getInstance();
		$id = $fv['id'];
		$q = array();

                // Begin Transaction
                $db->autoCommit(false);

		// Update user info
		if ($fv['passwd']) { // change password 
			$q[] = $db->query('UPDATE t_user SET username = ?, password = ?, accessLevel = ?, status = ?, firstName = ?, lastName = ?, phone = ?, dlFlag = ? WHERE id = ?',array($fv['username'],genHash($fv['passwd']),$fv['access'],$fv['status'],$fv['firstname'],$fv['lastname'],$fv['phone'],zeroize($fv['dlFlag']),$id));
		}
		else { // no password change
			$q[] = $db->query('UPDATE t_user SET username = ?, accessLevel = ?, status = ?, firstName = ?, lastName = ?, phone = ?, dlFlag = ? WHERE id = ?',array($fv['username'],$fv['access'],$fv['status'],$fv['firstname'],$fv['lastname'],$fv['phone'],zeroize($fv['dlFlag']),$id));
		}
		
		// Delete old agency info
		$q[] = $db->query('DELETE FROM t_user_agency WHERE user_id = ?',array($id));

		// Insert new agency info
                $sth = $db->prepare('INSERT INTO t_user_agency (user_id,agency_id,type) VALUES (?,?,?)');
                $data = array();
                array_push($data, array($id,$fv['agency'],'1'));
                if (isset($fv['agencySec'])) {
                        foreach ($fv['agencySec'] as $_) {
				if ($_ == 0) { continue; }
                                array_push($data, array($id,$_,'0'));
                        }
                }
                $q[] = $db->executeMultiple($sth,$data);

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

	// Delete Methods
	function delUser ($id) {
                $db = Database::getInstance();
                $q = array();

                // Begin Transaction
                $db->autoCommit(false);
		
		$q[] = $db->query('DELETE FROM t_user WHERE id = ? LIMIT 1', array($id));
		$q[] = $db->query('DELETE FROM t_user_agency WHERE user_id = ?', array($id));

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

        function delSession ($uid) {
                $db = Database::getInstance();
                $db->query('DELETE FROM t_session WHERE login_id = ?',array($uid));
        }

}

?>
