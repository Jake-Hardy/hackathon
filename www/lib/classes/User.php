<?php
/* User.php
SQL queries for User related functions
include(database.php);
*/

Class User {

	// Select Methods
	static function chkLogin($user,$pass) {
		$db = new Database();
		$qry = "SELECT id FROM t_user WHERE username = ? AND password = ? AND status = 1 LIMIT 1";
		$rows = $db->select($qry,$user,genHash($pass));
		return count($rows) > 0 ? $rows[0]['id']:0;
	}

	static function chkUsername($user) {
		$db = new Database();
		$rows = $db -> select("SELECT id FROM t_user WHERE username = ? LIMIT 1",$user);
		return count($rows) > 0 ? $rows[0]['id']:0;
	}

	static function chkSession($rand,$ip) {
		$db = new Database();

		$qry = "SELECT login_id FROM t_session where hash = ?";

		$ret = $db->query($qry,$rand);
		if($ret === false){
			error_log("Database failed. Couldn't retrieve session.");
		}
		$retId = 0;
		while ($row = $ret -> fetch_assoc())
		{
			$retId = $row['login_id'];
		}
		if ($retId > 0)
		{
			$db->execute('UPDATE t_session SET tstamp = NOW() WHERE login_id = ?', $retId);
		}
		return $retId;
	}

   	static function chkUserById($id) {
		$db = new Database();

		$sql = "SELECT id FROM t_application WHERE user_id = ? LIMIT 1";
		$user = $db->select($sql,$id);

		return $user[0]['id'];
   	}

	static function chkUserInAgency($aid,$uid) {
		$db = new Database();
		$ret = $db->query('SELECT user_id from t_user_agency WHERE agency_id = ? AND user_id = ?', $aid,$uid);
		if ($ret->num_rows > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public static function getInfo($id) {
		$db = new Database();
		$uid = "";

		$qry = "SELECT accessLevel,firstName,dlFlag,passwordReset FROM t_user WHERE id = ?";
		$rows = $db->query($qry,$id);
		return $rows;
	}
	
	public static function getAgencies($id) {
		$db = new Database();
		$qry = "SELECT t_agency.id,t_agency.name,t_agency.foodDefault,t_agency.toysDefault,t_agency.hideFood,t_agency.hideToys FROM t_agency,t_user_agency WHERE t_agency.id = t_user_agency.agency_id AND t_user_agency.user_id = ? ORDER BY t_user_agency.type DESC, t_agency.name ASC";
		$rows = $db -> select($qry,$id);
		return $rows;
	}
	
	public static function getUser($id) {
		$db = new Database();
		$qry = "SELECT t_user.id,t_agency.name as agency_name,t_agency.id as agency_id,username,status,accessLevel,firstName,lastName,phone,dlFlag,email 
				FROM t_user 
				LEFT OUTER JOIN t_user_agency ON t_user_agency.user_id = t_user.id AND t_user_agency.type = '1' 
				LEFT OUTER JOIN t_agency ON t_user_agency.agency_id = t_agency.id 
				WHERE t_user.id = ? 
				ORDER BY username";
		$rows = $db -> query($qry,$id);
		$dict = $rows -> fetch_assoc();
		return $dict;
	}

	public static function getAllUsers() {
		$db = new Database();
		$qry = "SELECT t_user.id,t_agency.name,username,status,accessLevel,firstName,lastName,			phone,email
                                FROM t_user
				LEFT OUTER JOIN t_user_agency ON t_user_agency.user_id = t_user.id AND t_user_agency.type = '1'
				LEFT OUTER JOIN t_agency ON t_user_agency.agency_id = t_agency.id
				ORDER BY username";
		$rows = $db->query($qry);
		return $rows;
	}

	public static function getUsersByAgency($id) {
		$db = new Database();
		$qry = "SELECT t_user.id,NULL as name,username,status,accessLevel,firstName,lastName,phone,email
				FROM t_user, t_user_agency
				WHERE t_user_agency.user_id = t_user.id 
				AND t_user_agency.agency_id = ?
				ORDER BY username";
		return $db->query($qry,$id);
	}

	// Insert Methods
	public static function insUser ($fv) {
		$db = new Database();
		$q = array();

		// Begin Transaction
		$db->autoCommit(false);

		// User Info
		$q[] = $db->execute('INSERT INTO t_user (username,password,accessLevel,firstName,lastName,phone,dlFlag,email) 
					VALUES (?,?,?,?,?,?,?,?)',
					$fv['username'],
					genHash($fv['passwd']),
					$fv['access'],
					$fv['firstname'],
					$fv['lastname'],
					$fv['phone'],
					zeroize($fv['dlFlag']),
					$fv['email']);

		$uid = $db->query("SELECT LAST_INSERT_ID()");
		$uidt = 0;

		while ($row = $uid->fetch_assoc())
		{
			$uidt = $row['LAST_INSERT_ID()'];
		}

		if ($uidt == 0) 
		{
			return false;
		}

		// User's Primary and Additional Agencies
		// Primary Agency
		$qryPriAge = "INSERT INTO t_user_agency (user_id,agency_id,type) 
				VALUES (?,?,?)";
		$priAge = $db->execute($qryPriAge,$uidt,$fv['agency'],"1");

		// Additional Agencies
		if (isset($fv['agencySec'])) {
			foreach ($fv['agencySec'] as $_) 
			{
				$qryAddAge = "INSERT INTO t_user_agency (user_id,agency_id,type) 
						VALUES (?,?,?)";
				$addAge = $db->execute($qryAddAge,$uidt,$_,"0");
			}
		}

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

	public static function insSession ($uid,$rand,$ip) {
		$db = new Database();
		$delete = "DELETE FROM t_session WHERE login_id = ?";
		$insert = "INSERT INTO t_session (login_id,hash) VALUES (?,?)";
		$db->execute($delete,$uid);
		$db->execute($insert,$uid,$rand);
	}
		
	// Update Methods
	public static function updUser ($fv) {
		$db = new Database();
		$id = $fv['id'];
		$q = array();
                // Begin Transaction
                $db->autoCommit(false);

		// Update user info
		if ($fv['passwd']) { 
			// change password 
			$updPass = 'UPDATE t_user 
					SET username = ?, 
					password = ?, 
					accessLevel = ?, 
					status = ?, 
					firstName = ?, 
					lastName = ?, 
					phone = ?, 
					dlFlag = ?, 
					email = ? 
					WHERE id = ?';

			$q[] = $db->execute($updPass,
				$fv['username'],
				genHash($fv['passwd']),
				$fv['access'],
				$fv['status'],
				$fv['firstname'],
				$fv['lastname'],
				$fv['phone'],
				zeroize($fv['dlFlag']),
				$fv['email'],
				$id);
		}
		else { 
			// no password change
			$qryNoPass = 'UPDATE t_user 
					SET username = ?, 
					accessLevel = ?, 
					status = ?, 
					firstName = ?, 
					lastName = ?, 
					phone = ?, 
					dlFlag = ?, 
					email = ? 
					WHERE id = ?';
			$q[] = $db->execute($qryNoPass,
				$fv['username'],
				$fv['access'],
				$fv['status'],
				$fv['firstname'],
				$fv['lastname'],
				$fv['phone'],
				zeroize($fv['dlFlag']),
				$fv['email'],
				$id);
		}
		
		// Delete old agency info
		$qryDeleteUserAgency = "DELETE FROM t_user_agency WHERE user_id = ? ";
		$q[] = $db->execute($qryDeleteUserAgency,$id);

		// User's Primary and Additional Agencies
		// Primary Agency
		$qryPriAge = "INSERT INTO t_user_agency (user_id,agency_id,type) VALUES (?,?,?)";
		$priAge = $db -> execute($qryPriAge,$id,$fv['agency'],"1");

		// Additional Agencies
		if (isset($fv['agencySec'])) {
			foreach ($fv['agencySec'] as $_) 
			{
				$qryAddAge = "INSERT INTO t_user_agency (user_id,agency_id,type) VALUES (?,?,?)";
				$addAge = $db->execute($qryAddAge,$id,$_,"0");
			}
		}

		// Insert new agency info
		// Take out the prepare statement
                // !!!! $sth = $db->prepare('INSERT INTO t_user_agency (user_id,agency_id,type) VALUES (?,?,?)');
                // $data = array();
                // array_push($data, array($id,$fv['agency'],'1'));
                // if (isset($fv['agencySec'])) {
                //         foreach ($fv['agencySec'] as $_) {
		// 		if ($_ == 0) { continue; }
                //                 array_push($data, array($id,$_,'0'));
		// 		$qryInsert = "INSERT INTO t_user_agency (user_id,agency_id,type) VALUES (".$id.",".$_.",'0')";
		// 		$db->query($qryInsert);
                //         }
                // }
                // Take out the executeMultiple
		//$q[] = $db->executeMultiple($sth,$data);

                // Error Check
                $err = 0;
                foreach ($q as $_) {
                        if ($_ != 1) { $err++; }
                }
                if ($err) {
                        // Failure - Rollback
                        mysqli_rollback($db->link());
                        return false;
                }
                else {
                        // Success - Commit
                        $db->commit();
                        return true;
                }
	}

	static function resetPassword ($id) {
                $db = new Database();
		$alphanum = "abcdefghijklmnopqrstuvwxyz0123456789";
		$passwd = "";
		for($i=0;$i<8;$i++) {
			$passwd .= substr( $alphanum, rand(0,strlen($alphanum)), 1 );
		}
		$db->execute( 'UPDATE t_user SET password = ?, passwordReset = 1 WHERE id = ?', genHash( $passwd ), $id);
		return $passwd;
	}

	static function changePassword( $id, $passwd ) {
		$db = new Database();
		$qry = "UPDATE t_user SET password = ?, passwordReset = 0 WHERE id = ?";
		$db->execute($qry,genHash($passwd),$id);
	}

	// Delete Methods
	static function delUser ($id) {
                $db = new Database();
                $q = array();

		// Begin Transaction
		$db->autoCommit(false);
		
		// Run Delete Queries to Delete User and the User's Related Agencies
		$qryDeleteUser = "DELETE FROM t_user WHERE id = ? LIMIT 1";
		$qryDeleteAgency = "DELETE FROM t_user_agency WHERE user_id = ?";
		$q[] = $db->execute($qryDeleteUser,$id);
		$q[] = $db->execute($qryDeleteAgency,$id);

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

        static function delSession ($uid) {
                $db = new Database();
                $db->execute('DELETE FROM t_session WHERE login_id = ?',$uid);
        }

}

?>
