<?php
/* App.php
SQL queries for Application related functions
include(database.php);
*/

Class App {
	
	// Select Methods
	public static function getApplication($id) {
		$db = new Database();
		$qry = "SELECT t_agency.id as id,t_agency.name,t_user.username,tstamp,tstampUpd,season,t_application.phone,street,city,state,zip,familySize,employer,income,expenses,comments,ss,ssi,va,tanf,fstamps,other,toys,food,dupe 
				FROM t_application,t_agency,t_user 
				WHERE t_application.id = ? 
					AND t_agency.id = t_application.agency_id 
					AND t_user.id = t_application.user_id
				LIMIT 1";
		/*
		1 agency could have multiple apps, 1 user could have multiple apps
		but 1 app can only have 1 agency and 1 user.
		therefore, I'm only returning one.
		*/
		return $db->select ($qry,$id)[0];
	}
	public static function getAppMembers($id) {
		$db = new Database();
		$r1 = $db->select('SELECT firstName,lastName,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,(?-YEAR(dob)) AS age FROM t_family WHERE application_id = ? AND type = 1',SEASON,$id);
		$r2 = $db->select('SELECT firstName,lastName,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,(?-YEAR(dob)) AS age FROM t_family WHERE application_id = ? AND type = 2',SEASON,$id);
		$r3 = $db->select('SELECT firstName,lastName,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,sex,(?-YEAR(dob)) AS age,wishlist FROM t_family WHERE application_id = ? AND type = 3',SEASON,$id);
		return array($r1,$r2,$r3);
	}

	static function getAppMembersEdit($id) {
		$db = new Database();
		$r1 = $db->select("SELECT t_family.id AS fid,t_lookup.id AS lid,firstName,lastName,DATE_FORMAT(dob,'%m-%d-%Y') as dob,data FROM t_family,t_lookup WHERE application_id = ? AND type = 1 AND t_family.lookup_id = t_lookup.id LIMIT 1",$id);
		$r2 = $db->select("SELECT t_family.id AS fid,t_lookup.id AS lid,firstName,lastName,DATE_FORMAT(dob,'%m-%d-%Y') as dob,data FROM t_family,t_lookup WHERE application_id = ? AND type = 2 AND t_family.lookup_id = t_lookup.id LIMIT 1",$id);
		$r3 = $db->select("SELECT t_family.id AS fid,t_lookup.id AS lid,firstName,lastName,DATE_FORMAT(dob,'%m-%d-%Y') as dob,sex,wishlist,data FROM t_family,t_lookup WHERE application_id = ? AND type = 3 AND t_family.lookup_id = t_lookup.id",$id);
		return array($r1,$r2,$r3);
	}

	public static function getAllAppMembers($id, $uid) {
		$db = new Database ();
		return $db->getAll ( 'SELECT t_family.lookup_id,t_family.firstName,t_family.lastName 
				FROM t_family,t_application,t_user_agency 
				WHERE t_family.application_id = ?
				AND t_application.id = t_family.application_id 
				AND t_user_agency.agency_id = t_application.agency_id 
				AND t_user_agency.user_id = ?
				ORDER BY t_family.lookup_id', 
				$id,
				$uid 
		);
	}
	function getDupExistingInfo($appId, $lookId) {
		$db = new Database ();
		return $db->getAll ( 'SELECT t_application.id,t_agency.name,t_agency.id AS aid
                                FROM t_family,t_application,t_agency
                                WHERE t_family.lookup_id = ? 
                                AND t_family.application_id < ? 
                                AND t_application.id = t_family.application_id
                                AND t_agency.id = t_application.agency_id',
				$lookId,
				$appId 
		);
	}
	static function getUsersHistoryCount($uid) {
		$db = new Database ();
		$qry = "SELECT id FROM t_application WHERE user_id = ? AND season = ?";
		return $db->query($qry,$uid,SEASON)->num_rows;
	}
	static function getUsersHistory($uid, $page, $sort) {
		$db = new Database ();
		$low = ($page - 1) * PER_PAGE;
		$qry = "SELECT t_family.lastName,t_application.familySize,t_application.tstamp,t_application.id 
				FROM t_application,t_family 
				WHERE t_application.user_id = ? 
					AND t_family.application_id = t_application.id 
					AND t_family.type = 1 
					AND t_application.season = ? 
					ORDER BY ? LIMIT ?,?";
		return $db->select($qry,$uid,SEASON,$sort,$low,PER_PAGE);
	}
	function getDupeApps($id, $page) {
		$db = new Database ();
		$low = ($page - 1) * PER_PAGE;
		$qry = "SELECT t_family.lastName,t_application.familySize,t_application.tstamp,t_application.id
                                FROM t_application,t_family
                                WHERE dupe = '1'
				AND t_application.agency_id = ?
				AND t_family.application_id = t_application.id
				AND t_family.type = 1
				AND t_application.season = ?
				ORDER BY t_application.id
				LIMIT ?,?";
		return $db->query($qry,$id,SEASON,$low,PER_PAGE);
	}
	static function SearchBySSN($data, $year, $uid) {
		$db = new Database ();
		if ($year) {
			$qry = "SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,'%m-%d-%Y') as dob FROM t_lookup,t_family,t_application,t_user_agency 
					WHERE data = ? 
					AND t_lookup.season = ? 
					AND t_family.lookup_id = t_lookup.id 
					AND t_application.id = t_family.application_id 
					AND t_user_agency.user_id = ? 
					AND t_application.agency_id = t_user_agency.agency_id 
					ORDER BY lastName,firstName";
			return $db->select ($qry,$data,$year,$uid);
		} else {
			$qry = "SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,'%m-%d-%Y') as dob 
					FROM t_lookup,t_family,t_application,t_user_agency 
					WHERE data = ? 
					AND t_family.lookup_id = t_lookup.id 
					AND t_application.id = t_family.application_id 
					AND t_user_agency.user_id = ? 
					AND t_application.agency_id = t_user_agency.agency_id 
					ORDER BY lastName,firstName";
			return $db->select($qry,$data,$uid);
		}
	}
	static function SearchByLNameCnt($lname, $year, $uid) {
		$db = new Database ();
		if ($year) {
			$qry = "SELECT t_family.id 
					FROM t_family,t_application,t_user_agency 
					WHERE lastName LIKE ?
					AND t_application.id = t_family.application_id 
					AND t_application.season = ? 
					AND t_user_agency.user_id = ? 
					AND t_application.agency_id = t_user_agency.agency_id";
			return $db->query ($qry,$lname,$year,$uid)->num_rows;
		} else {
			$qry = "SELECT t_family.id 
					FROM t_family,t_application,t_user_agency 
					WHERE lastName LIKE ? 
					AND t_application.id = t_family.application_id 
					AND t_user_agency.user_id = ? 
					AND t_application.agency_id = t_user_agency.agency_id";
			return $db->query($qry,$lname,$uid)->num_rows;
		}
	}
	static function SearchByLName($lname, $year, $uid, $page) {
		$db = new Database ();
		$low = ($page - 1) * PER_PAGE;
		if ($year) {
			$qry = "SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,'%m-%d-%Y') as dob ".
					"FROM t_family,t_application,t_user_agency ".
					"WHERE lastName LIKE ? ".
					"AND t_application.id = t_family.application_id ".
					"AND t_application.season = ? ".
					"AND t_user_agency.user_id = ? ".
					"AND t_application.agency_id = t_user_agency.agency_id ".
					"ORDER BY lastName,firstName LIMIT ?,?";
			return $db->select($qry,$lname,$year,$uid,$low,PER_PAGE);
		} else {
			$qry = "SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,'%m-%d-%Y') as dob ". 
					"FROM t_family,t_application,t_user_agency ".
					"WHERE lastName LIKE ? ".
					"AND t_application.id = t_family.application_id ".
					"AND t_user_agency.user_id = ? ".
					"AND t_application.agency_id = t_user_agency.agency_id ".
					"ORDER BY lastName,firstName LIMIT ?,?";
			return $db->select($qry,$lname,$uid,$low,PER_PAGE);
		}
	}
	static function SearchByDOB($dob, $year, $uid) {
		$db = new Database ();
		if ($year) {
			$qry = "SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,'%m-%d-%Y') as dob FROM t_family,t_application,t_user_agency WHERE dob = STR_TO_DATE(?,'%m-%d-%Y') AND t_application.id = t_family.application_id AND t_application.season = ? AND t_user_agency.user_id = ? AND t_application.agency_id = t_user_agency.agency_id ORDER BY lastName,firstName";
			error_log ( $qry );
			return $db->select ($qry,$dob,$year,$uid);
		} else {
			$qry = "SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,'%m-%d-%Y') as dob FROM t_family,t_application,t_user_agency WHERE dob = STR_TO_DATE(?,'%m-%d-%Y') AND t_application.id = t_family.application_id AND t_user_agency.user_id = ? AND t_application.agency_id = t_user_agency.agency_id ORDER BY lastName,firstName";
			error_log ( $qry );
			return $db->select ( $qry,$dob,$uid);
		}
	}
	static function SearchBySSNAdmin($data, $year) {
		$db = new Database ();
		if ($year) {
			$qry = "SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,'%m-%d-%Y') as dob,t_agency.name FROM t_lookup,t_family,t_application,t_agency WHERE data = ? AND t_lookup.season = ? AND t_family.lookup_id = t_lookup.id AND t_application.id = t_family.application_id AND t_agency.id = t_application.agency_id ORDER BY lastName,firstName";
			return $db->select ($qry,$data,$year);
		} else {
			$qry = "SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,'%m-%d-%Y') as dob,t_agency.name FROM t_lookup,t_family,t_application,t_agency WHERE data = ? AND t_family.lookup_id = t_lookup.id AND t_application.id = t_family.application_id AND t_agency.id = t_application.agency_id ORDER BY lastName,firstName";
			return $db->select ( $qry,$data);
		}
	}
	static function SearchByLNameAdminCnt($lname, $year) {
		$db = new Database ();
		if ($year) {
			$qry = "SELECT t_family.id FROM t_family,t_application WHERE lastName LIKE ? AND t_application.season = ? AND t_application.id = t_family.application_id";
			return $db->query($qry,$lname,$year)->num_rows;
		} else {
			$qry = "SELECT t_family.id FROM t_family WHERE lastName LIKE ?";
			return $db->query($qry,$lname)->num_rows;
		}
	}
	static function SearchByLNameAdmin($lname, $year, $page) {
		$db = new Database ();
		$low = ($page - 1) * PER_PAGE;
		if ($year) {
			$qry = "SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,'%m-%d-%Y') as dob,t_agency.name 
					FROM t_family,t_application,t_agency 
					WHERE lastName LIKE ? 
					AND t_application.id = t_family.application_id 
					AND t_application.season = ?
					AND t_agency.id = t_application.agency_id 
					ORDER BY lastName,firstName LIMIT ?,?";
			return $db->select($qry,$lname,$year,$low,PER_PAGE);
		} else {
			$qry = "SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,'%m-%d-%Y') as dob,t_agency.name FROM t_family,t_application,t_agency WHERE lastName LIKE ? AND t_application.id = t_family.application_id AND t_agency.id = t_application.agency_id ORDER BY lastName,firstName LIMIT ?,?";
			return $db->select ($qry,$lname,$low,PER_PAGE);
		}
	}
	static function SearchByDOBAdmin($dob, $year) {
		$db = new Database ();
		if ($year) {
			$qry = "SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,'%m-%d-%Y') as dob,t_agency.name ".
			"FROM t_family,t_application,t_agency ".
			"WHERE dob = STR_TO_DATE(?,'%m-%d-%Y') ".
			"AND t_application.id = t_family.application_id ".
			"AND t_application.season = ? ".
			"AND t_agency.id = t_application.agency_id ".
			"ORDER BY lastName,firstName"; 
			return $db->select($qry,$dob,$year);
		} else {
			$qry = "SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,'%m-%d-%Y') as dob,t_agency.name ".
			"FROM t_family,t_application,t_agency ".
			"WHERE dob = STR_TO_DATE(?,'%m-%d-%Y') ".
			"AND t_application.id = t_family.application_id ".
			"AND t_agency.id = t_application.agency_id  ".
			"ORDER BY lastName,firstName"; 
			
			return $db->select($qry,$dob);
		}
	}
	public static function chkDupe($string, $id) {
		$db = new Database ();
		$dup = $db->query ( "SELECT t_lookup.id FROM t_lookup,t_family,t_application
				WHERE data = ? AND t_lookup.season = ? 
				AND t_family.lookup_id = t_lookup.id
				AND t_application.id = t_family.application_id
				AND (t_application.dupe = '0' OR (t_application.dupe = '1' AND t_application.id <= ?))",
				genHash ( $string ),
				SEASON,
				$id 
		);
		// Error Check
		if(!$dup) {
			genError ( STD_ERR );
			exit ();
		}
		return $dup->num_rows > 0 ? true : false;
	}
	static function getSeason() {
		$db = new Database ();
		
		$rows = $db->query ( "SELECT season from t_config" );
		if (! isset ( $rows )) {
			return date ( 'Y' );
		} else {
			$season = "";
			while ( $row = $rows->fetch_assoc () ) {
				$season = $row ['season'];
			}
			return $season;
		}
	}
	function setSeason($val) {
		$db = new Database();
		return $db->execute('UPDATE t_config SET season = ?',$val);
	}
	static function getTFT() {
		$db = new Database ();
		$rows = $db->query ( "SELECT tft FROM t_config LIMIT 1");
		
		if (! isset ( $rows )) {
			return FALSE;
		} else {
			$configDict = $rows->fetch_assoc();
			return ($configDict['tft'] == '1' ? TRUE : FALSE);
		}
	}
	function setTFT($val) {
		$db = new Database ();
		return $db->execute('UPDATE t_config SET tft = ?',$val);
	}
	
	// Insert Methods
	static function insApplication($fv, $uid) {
		$q = array ();
		$flg = 0;
		$fCnt = 0;
		$db = new Database ();
		
		// Begin Transaction
		mysqli_autocommit ( $db->link (), false );
		mysqli_begin_transaction ( $db->link () );
		
		// Combine ssn fields
		$fv ['ssn'] = $fv ['ssn_0'] . "" . $fv ['ssn_1'] . "" . $fv ['ssn_2'];
		
		// Combine ssnSp fields
		$fv ['ssnSp'] = $fv ['ssnSp_0'] . "" . $fv ['ssnSp_1'] . $fv ['ssnSp_2'];
		
		// Combine dob fields
		$fv ['dob'] = $fv ['dob_0'] . '-' . $fv ['dob_1'] . '-' . $fv ['dob_2'];
		
		// Combine dobSP fields
		$fv ['dobSp'] = $fv ['dobSp_0'] . '-' . $fv ['dobSp_1'] . '-' . $fv ['dobSp_2'];
		
		// Application
		$q[] = $db->execute('INSERT INTO t_application (agency_id,user_id,season,phone,street,city,
										state,zip,employer,income,expenses,comments,ss,ssi,va,
										tanf,fstamps,other,toys,food) 
							VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', 
							$fv['agency'],
							$uid,
							SEASON,
							$fv['phone'],
							$fv['street'],
							$fv['city'],
							$fv['state'],
							$fv['zip'],
							$fv['employer'],
							$fv['income'],
							$fv['expense'],
							$fv['comments'],
							((isset($fv['ss']) && is_numeric($fv['ss'])) ? $fv['ss'] : '0'),
							((isset($fv['ssi']) && is_numeric($fv['ssi'])) ? $fv['ssi'] : '0'),
							((isset($fv['va']) && is_numeric($fv['va'])) ? $fv['va'] : '0'),
							((isset($fv['tanf']) && is_numeric($fv['tanf'])) ? $fv['tanf'] : '0'),
							((isset($fv['fStamp']) && is_numeric($fv['fStamp'])) ? $fv['fStamp'] : '0'),
							((isset($fv['other']) && is_numeric($fv['other'])) ? $fv['other'] : '0'),
							$fv['toys'],
							$fv['food']);

		$appId = $db->getOne ( 'SELECT LAST_INSERT_ID()' );
		
		// prepare re-usable sql statements
		$sth_selL = $db->prepare ( 'SELECT id FROM t_lookup WHERE data = ? AND season = ? LIMIT 1' );
		$sth_insApp = $db->prepare ( 'INSERT INTO t_family (application_id,type,firstName,lastName,dob,lookup_id,sex,wishlist) VALUES (?,?,?,?,STR_TO_DATE(?,\'%m-%d-%Y\'),?,?,?)' );
		$sth_insL = $db->prepare ( 'INSERT INTO t_lookup (data,season,dataExp) VALUES (?,?,?)' );
		$sth_selR = $db->prepare ( 'SELECT data,dataExp from t_lookup WHERE id = ?' );
		// Applicant
		$fCnt ++;
		
		// If this is a renewal app, use the passed lid to get ssn
		if (isset ( $fv ['lid'] ) && $fv ['lid'] > 0) { // Renewal
			if (! $db->executePrepared($sth_selR, $fv ['lid'] )) {
				$db->rollback();
				return false;
			} // Error Check
			$sth_selR->bind_result ( $ssnH, $ssnE );
			$sth_selR->fetch();
		} 
		else { // Normal App
			$ssnH = genHash ( $fv ['ssn'] );
			$ssnE = encString ( $fv ['ssn'] );
		}
		
		// Dupe Check
		if (! $db->executePrepared($sth_selL, $ssnH, 2007 )) {
			$db->rollback ();
			return false;
		} // Error Check
		$sth_selL->bind_result($id);
		$sth_selL->fetch();
		
		if ($id > 0) {
			// Duplicate
			$LID = $id;
			$flg ++;
		} else {
			// New
			$db->executePrepared($sth_insL, $ssnH, SEASON, $ssnE);
			$LID = $db->getOne ( 'SELECT LAST_INSERT_ID()' );
		}
		if(!$db->executePrepared($sth_insApp, $appId, 1, $fv ['firstName'], $fv ['lastName'], dashify ( $fv ['dob'] ), $LID, null, null )) {
			$q[] = false;
			error_log("Error inserting appliation data into t_family. $db->execute returned false.");
		}
		
		// Spouse
		if ($fv ['lastNameSp']) {
			$fCnt ++;
			
			// If this is a renewal app, use the passed lid to get ssn
			if (isset ( $fv ['lidSp'] ) && $fv ['lidSp'] > 0) { // Renewal
				$res = $db->executePrepared($sth_selR,$fv ['lidSp']);
				if (!$res) {
					$db->rollback ();
					return false;
				} // Error Check
				$res = $$sth_selL->get_result();
				$selR = $res->fetch_assoc();
				/*
				 * REMOVED 5-22-2012 ******
				 * $ssnSpH = $selR->data;
				 * $ssnSpE = $selR->dataExp;
				 * /************************
				 */
				$ssnSpH = genHash ( $fv ['ssnSp'] );
				$ssnSpE = encString ( $fv ['ssnSp'] );
			} 
			else { // Normal App
				$ssnSpH = genHash ( $fv ['ssnSp'] );
				$ssnSpE = encString ( $fv ['ssnSp'] );
			}
			
			$res = $db->executePrepared($sth_selL,
					$ssnSpH,
					SEASON 
			);
			if (!$res) {
				$db->rollback ();
				return false;
			} // Error Check
			$res = $sth_selL->get_result();
			$tmp = $res->fetch_assoc();
			if ($tmp['id'] > 0) {
				// Duplicate
				$LID = $tmp['id'];
				$flg ++;
			} 
			else {
				// New
				$q [] = $db->executePrepared($sth_insL,
						$ssnSpH,
						SEASON,
						$ssnSpE 
				);
				$LID = $db->getOne ( 'SELECT LAST_INSERT_ID()' );
			}
			$q [] = $db->executePrepared($sth_insApp,
					$appId,
					2,
					$fv ['firstNameSp'],
					$fv ['lastNameSp'],
					dashify ( $fv ['dobSp'] ),
					$LID,
					null,
					null 
			);
		}
		
		// Others
		for($x = 0; $x < CNT_OTHERS; $x ++) {
			
			// Combine ssn fields
			$fv ['ssn' . $x] = $fv ['ssn' . $x . '_0'] . "" . $fv ['ssn' . $x . '_1'] . "" . $fv ['ssn' . $x . '_2'];
			
			// Combine dob fields
			$fv ['dob' . $x] = $fv ['dob' . $x . '_0'] . '-' . $fv ['dob' . $x . '_1'] . '-' . $fv ['dob' . $x . '_2'];
			
			if ($fv ['lastName' . $x]) {
				$fCnt ++;
				
				// If this is a renewal app, use the passed lid to get ssn
				if (isset ( $fv ['lid' . $x] ) && $fv ['lid' . $x] > 0) { // Renewal
					$res = $db->executePrepared($sth_selR,$fv ['lid' . $x]);
					if(!$res){
						$db->rollback ();
						return false;
					} // Error Check
					$res = $sth_selL->get_result();
					$selR = $res->fetch_assoc();
					/*
					 * REMOVED 5-22-2012 ******
					 * $ssnHa[$x] = $selR->data;
					 * $ssnEa[$x] = $selR->dataExp;
					 * /************************
					 */
					$ssnHa [$x] = genHash ( $fv ['ssn' . $x] );
					$ssnEa [$x] = encString ( $fv ['ssn' . $x] );
				} 
				else { // Normal App
					$ssnHa [$x] = genHash ( $fv ['ssn' . $x] );
					$ssnEa [$x] = encString ( $fv ['ssn' . $x] );
				}
				
				$res = $db->executePrepared($sth_selL,
						$ssnHa [$x],
						SEASON 
				);
				if (!$res) {
					$db->rollback ();
					return false;
				} // Error Check
				$res = $sth_selL->get_result();
				$tmp = $res->fetch_assoc();
				if ($tmp['id'] > 0) {
					// Duplicate
					$LID = $tmp['id'];
					$flg ++;
				} 
				else {
					// New
					$q [] = $db->executePrepared($sth_insL,
							$ssnHa [$x],
							SEASON,
							$ssnEa [$x] 
					);
					$LID = $db->getOne ('SELECT LAST_INSERT_ID()');
				}
				$q [] = $db->executePrepared($sth_insApp,
						$appId,
						3,
						$fv ['firstName' . $x],
						$fv ['lastName' . $x],
						dashify ( $fv ['dob' . $x] ),
						$LID,
						$fv ['sex' . $x],
						$fv ['wishlist' . $x] 
				);
			}
		}
		
		// Update application with Family Count
		$q [] = $db->execute('UPDATE t_application 
								SET familySize = ? 
								WHERE id = ?',$fCnt,$appId); 
		
		// Flag Dupes
		if ($flg > 0) {
			$q [] = $db->execute('UPDATE t_application SET dupe = "1" WHERE id = ?',$appId );
		}
		
		// Error Check
		$err = 0;
		foreach ( $q as $_ ) {
			if (!$_) {
				$err ++;
			}
		}
		if ($err) {
			// Failure - Rollback
			mysqli_rollback($db->link());
			return false;
		} else {
			// Success - Commit
			mysqli_commit ( $db->link() );
			if ($flg > 0) {
				return $appId;
			} else {
				return true;
			}
		}

	} // end function
	  
	// Update Methods
	static function updateApp($fv, $uid) {
		$db = new Database ();
		$q = array ();
		$clear = array ();
		$flg = 0;
		$fCnt = 0;
		
		// Begin Transaction
		$db->autoCommit ( false );
		
		// Combine ssn fields
		$fv ['ssn'] = $fv ['ssn_0'] . "" . $fv ['ssn_1'] . "" . $fv ['ssn_2'];
		
		// Combine ssnSp fields
		$fv ['ssnSp'] = $fv ['ssnSp_0'] . "" . $fv ['ssnSp_1'] . $fv ['ssnSp_2'];
		
		// Combine dob fields
		$fv ['dob'] = $fv ['dob_0'] . '-' . $fv ['dob_1'] . '-' . $fv ['dob_2'];
		
		// Combine dobSP fields
		$fv ['dobSp'] = $fv ['dobSp_0'] . '-' . $fv ['dobSp_1'] . '-' . $fv ['dobSp_2'];
		
		// prepare re-usable sql statements
		$sthDelL = $db->prepare ( 'DELETE FROM t_lookup WHERE id = ? LIMIT 1' );
		$sthDelF = $db->prepare ( 'DELETE FROM t_family WHERE id = ? LIMIT 1' );
		$sthUpdA = $db->prepare ( 'UPDATE t_application SET dupe = "0" WHERE id = ?' );
		$sthUpdF = $db->prepare ( 'UPDATE t_family SET firstName = ?,lastName = ?,dob = STR_TO_DATE(?,\'%m-%d-%Y\'),sex = ?,wishlist = ? WHERE id = ?' );
		$sthUpdL = $db->prepare ( 'UPDATE t_lookup SET data = ?, dataExp = ? WHERE id = ?' );
		$sthUpdN = $db->prepare ( 'UPDATE t_family SET lookup_id = ? WHERE id = ?' );
		$sthInsL = $db->prepare ( 'INSERT INTO t_lookup (data,season,dataExp) VALUES (?,?,?)' );
		$sthInsF = $db->prepare ( 'INSERT INTO t_family (application_id,type,firstName,lastName,dob,sex,lookup_id,wishlist) VALUES (?,?,?,?,STR_TO_DATE(?,\'%m-%d-%Y\'),?,?,?)' );
		
		// Primary
		$fCnt ++;
		if ($fv ['ssn'] == '*********' || genHash ( $fv ['ssn'] ) == $fv ['oldSsn']) {
			// No SSN change
			$r = $db->executePrepared($sthUpdF,
			    $fv['firstName'],
				$fv ['lastName'],
				dashify ( $fv ['dob'] ),
				null,
				null,
				$fv ['fid'] 
			);
			if (!$r) {
				$db->rollback ();
				return false;
			} // Error Check
		} else {
			// SSN Change
			$flg ++;
			$fm = $db->select('SELECT id,application_id FROM t_family WHERE lookup_id = ? ORDER BY application_id',
					$fv['lid'] 
			);
			if (!$fm) {
				$db->rollback ();
				return false;
			} // Error Check
			if (sizeof ($fm) > 1) {
				// Applicant has dupes
				if ($fm[0]['application_id'] == $fv['id']) {
					// flag next application for clearing check
					array_push ($clear,$fm[1]['application_id']);
				}
				// Check New SSN
				$dc = $db->getOne ( 'SELECT id FROM t_lookup WHERE data = ? AND season = ?',genHash ( $fv ['ssn'] ),SEASON );
				if (!$dc) {
					$db->rollback ();
					return false;
				} // Error Check
				if ($dc > 0) {
					// New SSN Duplicated - use existing LID
					$LID = $dc;
				} else {
					// New SSN Clear - create new
					$r = $db->executePrepared($sthInsL,
							genHash ( $fv ['ssn'] ),
							SEASON,
							encString ( $fv ['ssn'] ) 
					);
					if (!$r) {
						$db->rollback ();
						return false;
					} // Error Check
					$LID = $db->getOne ( 'SELECT LAST_INSERT_ID()' );
				}
				// update t_family
				$r = $db->executePrepared($sthUpdN,
						$LID,
						$fv ['fid'] 
				);
			} else {
				// No dupes
				$dc = $db->getOne ( 'SELECT id FROM t_lookup WHERE data = ? AND season = ?',
						genHash ( $fv ['ssn'] ),
						SEASON 
				);
				if (!$dc) {
					$db->rollback ();
					return false;
				} // Error Check
				if ($dc > 0) {
					// New SSN Duplicated - use found LID
					$r = $db->executePrepared($sthUpdN,
							$dc,
							$fv ['fid'] 
					);
					if (!$r) {
						$db->rollback ();
						return false;
					} // Error Check
					                                                        // Delete orphaned LID
					$r = $db->executePrepared($sthDelL,
							$fv ['lid'] 
					);
					if (!$r) {
						$db->rollback ();
						return false;
					} // Error Check
				} else {
					// New SSN Clear - Update old
					$r = $db->executePrepared($sthUpdL,
							genHash ( $fv ['ssn'] ),
							encString ( $fv ['ssn'] ),
							$fv ['lid'] 
					);
					if (!$r) {
						$db->rollback ();
						return false;
					} // Error Check
				}
			}
		}
		// Spouse
		if (isset ( $fv ['delSp'] ) && $fv ['delSp'] == 1 && isset ( $fv ['fidSp'] )) { // Delete Spouse
			$flg ++;
			// Check for Duplicates
			$fm = $db->getAll ( 'SELECT id,application_id FROM t_family WHERE lookup_id = ? ORDER BY application_id',
					$fv ['lidSp'] 
			);
			if (!$fm) {
				$db->rollback ();
				return false;
			} // Error Check
			if (sizeof ( $fm ) > 1) {
				// DUPLICATE
				if ($fm [0]['application_id'] == $fv ['id']) {
					// flag next application for clearing check
					array_push ( $clear, $fm [1]['application_id'] );
				}
				// Delete member only
				$r = $db->executePrepared($sthDelF,
						$fv ['fidSp'] 
				);
				if (!$r) {
					$db->rollback ();
					return false;
				} // Error Check
			} else {
				// CLEARED
				// delete lookup data and member from t_family (via cascade)
				$r = $db->executePrepared($sthDelL,
						$fv ['lidSp'] 
				);
				$r &= $db->executePrepared($sthDelF,
						$fv ['fidSp'] 
				);
				if (!$r) {
					$db->rollback ();
					return false;
				} // Error Check
			}
		} else { // Update Spouse
			if (isset ( $fv ['fidSp'] )) {
				// Existing
				$fCnt ++;
				if ($fv ['ssnSp'] == '*********' || genHash ( $fv ['ssnSp'] ) == $fv ['oldSsnSp']) {
					// No SSN Change
					$r = $db->executePrepared($sthUpdF,
							$fv ['firstNameSp'],
							$fv ['lastNameSp'],
							dashify ( $fv ['dobSp'] ),
							null,
							null,
							$fv ['fidSp'] 
					);
					if (!$r) {
						$db->rollback ();
						return false;
					} // Error Check
				} else {
					// SSN Change
					$flg ++;
					$fm = $db->select('SELECT id,application_id FROM t_family WHERE lookup_id = ? ORDER BY application_id',
							$fv ['lidSp'] 
					);
					if (!$fm) {
						$db->rollback ();
						return false;
					} // Error Check
					if (sizeof ( $fm ) > 1) {
						// Applicant has dupes
						if ($fm [0]['application_id'] == $fv ['id']) {
							// flag next application for clearing check
							array_push ( $clear, $fm [1]->application_id );
						}
						// Check New SSN
						$dc = $db->getOne ( 'SELECT id FROM t_lookup WHERE data = ? AND season = ?',
								genHash ( $fv ['ssnSp'] ),
								SEASON 
						);
						if (!$dc) {
							$db->rollback ();
							return false;
						} // Error Check
						if ($dc > 0) {
							// New SSN Duplicated - use existing LID
							$LID = $dc;
						} else {
							// New SSN Clear - create new
							$r = $db->executePrepared($sthInsL,
									genHash ( $fv ['ssnSp'] ),
									SEASON,
									encString ( $fv ['ssnSp'] ) 
							);
							if (!$r) {
								$db->rollback ();
								return false;
							} // Error Check
							$LID = $db->getOne ( 'SELECT LAST_INSERT_ID()' );
						}
						// update t_family
						$r = $db->executePrepared($sthUpdN,
								$LID,
								$fv ['fidSp'] 
						);
					} else {
						// No dupes
						$dc = $db->getOne ( 'SELECT id FROM t_lookup WHERE data = ? AND season = ?',
								genHash ( $fv ['ssnSp'] ),
								SEASON 
						);
						if (!$dc) {
							$db->rollback ();
							return false;
						} // Error Check
						if ($dc > 0) {
							// New SSN Duplicated - use found LID
							$r = $db->executePrepared($sthUpdN,
									$dc,
									$fv ['fidSp'] 
							);
							if (!$r) {
								$db->rollback ();
								return false;
							} // Error Check
							                                                        // Delete orphaned LID
							$r = $db->executePrepared($sthDelL,
									$fv ['lidSp'] 
							);
							if (!$r) {
								$db->rollback ();
								return false;
							} // Error Check
						} else {
							// New SSN Clear - Update old
							$r = $db->executePrepared($sthUpdL,
									genHash ( $fv ['ssnSp'] ),
									encString ( $fv ['ssnSp'] ),
									$fv ['lidSp'] 
							);
							if (!$r) {
								$db->rollback ();
								return false;
							} // Error Check
						}
					}
				}
			} elseif ($fv ['lastNameSp']) {
				// New
				$fCnt ++;
				$dc = $db->query ( 'SELECT id FROM t_lookup WHERE data = ? AND season = ?', 
						genHash ( $fv ['ssnSp'] ),
						SEASON 
				);
				if ($dc === false) {
					$db->rollback ();
					return false;
				} // Error Check
				if ($dc->num_rows > 0) {
					// New SSN Duplicated
					$LID = $dc['id'];
				} else {
					// New SSN Clear
					$r = $db->executePrepared($sthInsL,
							genHash ( $fv ['ssnSp'] ),
							SEASON,
							encString ( $fv ['ssnSp'] ) 
					);
					if (!$r) {
						$db->rollback ();
						return false;
					} // Error Check
					$LID = $db->getOne ( 'SELECT LAST_INSERT_ID()' );
				}
				$r = $db->executePrepared($sthInsF,
						$fv ['id'],
						2,
						$fv ['firstNameSp'],
						$fv ['lastNameSp'],
						dashify ( $fv ['dobSp'] ),
						null,
						$LID,
						null 
				);
				if (!$r) {
					$db->rollback ();
					return false;
				} // Error Check
			}
		}
		// Members
		for($x = 0; $x < CNT_OTHERS; $x ++) {
			
			// Combine ssn fields
			$fv ['ssn' . $x] = $fv ['ssn' . $x . '_0'] . "" . $fv ['ssn' . $x . '_1'] . "" . $fv ['ssn' . $x . '_2'];
			
			// Combine dob fields
			$fv ['dob' . $x] = $fv ['dob' . $x . '_0'] . '-' . $fv ['dob' . $x . '_1'] . '-' . $fv ['dob' . $x . '_2'];
			
			if (isset ( $fv ['del' . $x] ) && $fv ['del' . $x] == 1 && isset ( $fv ['fid' . $x] )) { // Delete Member
				$flg ++;
				// Check for Duplicates
				$fm = $db->select ( 'SELECT id,application_id FROM t_family WHERE lookup_id = ? ORDER BY application_id',
						$fv ['lid' . $x] 
				);
				if (!$fm) {
					$db->rollback ();
					return false;
				} // Error Check
				if (sizeof ( $fm ) > 1) {
					// DUPLICATE
					if ($fm [0]['application_id'] == $fv ['id']) {
						// flag next application for clearing check
						array_push ( $clear, $fm [1]['application_id'] );
					}
					// Delete member only
					$r = $db->executePrepared($sthDelF,
							$fv ['fid' . $x] 
					);
					if (!$r) {
						$db->rollback ();
						return false;
					} // Error Check
				} else {
					// CLEARED
					// delete lookup data and member from t_family (via cascade)
					$r = $db->executePrepared($sthDelL,
							$fv ['lid' . $x] 
					);
					$r &= $db->executePrepared($sthDelF,
						$fv ['fid' . $x] 
					);
					if (!$r) {
						$db->rollback ();
						return false;
					} // Error Check
				}
			} else { // Update Member
				if (isset ( $fv ['fid' . $x] )) {
					// Existing
					$fCnt ++;
					if ($fv ['ssn' . $x] == '*********' || genHash ( $fv ['ssn' . $x] ) == $fv ['oldSsn' . $x]) {
						// No SSN Change
						$r = $db->executePrepared($sthUpdF,
								$fv ['firstName' . $x],
								$fv ['lastName' . $x],
								dashify ( $fv ['dob' . $x] ),
								$fv ['sex' . $x],
								$fv ['wishlist' . $x],
								$fv ['fid' . $x] 
						);
						if (!$r) {
							$db->rollback ();
							return false;
						} // Error Check
					} else {
						// SSN Change
						$flg ++;
						$fm = $db->select( 'SELECT id,application_id FROM t_family WHERE lookup_id = ? ORDER BY application_id',
								$fv ['lid' . $x] 
						);
						if (!$fm) {
							$db->rollback ();
							return false;
						} // Error Check
						if (sizeof ( $fm ) > 1) {
							// Applicant has dupes
							if ($fm [0]['application_id'] == $fv ['id']) {
								// flag next application for clearing check
								array_push ( $clear, $fm [1]['application_id'] );
							}
							// Check New SSN
							$dc = $db->query ( 'SELECT id FROM t_lookup WHERE data = ? AND season = ?', 
									genHash ( $fv ['ssn' . $x] ),
									SEASON 
							);
							if ($dc === false) {
								$db->rollback ();
								return false;
							} // Error Check
							if ($dc->num_rows > 0) {
								// New SSN Duplicated - use existing LID
								$LID = $dc;
							} else {
								// New SSN Clear - create new
								$r = $db->executePrepared($sthInsL,
										genHash ( $fv ['ssn' . $x] ),
										SEASON,
										encString ( $fv ['ssn' . $x] ) 
								);
								if (!$r) {
									$db->rollback ();
									return false;
								} // Error Check
								$LID = $db->getOne ( 'SELECT LAST_INSERT_ID()' );
							}
							// update t_family
							$r = $db->executePrepared($sthUpdN,
									$LID,
									$fv ['fid' . $x] 
							);
						} else {
							// No dupes
							$dc = $db->query ( 'SELECT id FROM t_lookup WHERE data = ? AND season = ?',
									genHash ( $fv ['ssn' . $x] ),
									SEASON 
							);
							if ($dc === false) {
								$db->rollback ();
								return false;
							} // Error Check
							if ($dc->num_rows > 0) {
								// New SSN Duplicated - use found LID
								$r = $db->executePrepared($sthUpdN,
										$dc,
										$fv ['fid' . $x] 
								);
								if (!$r) {
									$db->rollback ();
									return false;
								} // Error Check
								                                                        // Delete orphaned LID
								$r = $db->executePrepared($sthDelL,
										$fv ['lid' . $x] 
								);
								if (!$r) {
									$db->rollback ();
									return false;
								} // Error Check
							} else {
								// New SSN Clear - Update old
								$r = $db->executePrepared($sthUpdL,
										genHash ( $fv ['ssn' . $x] ),
										encString ( $fv ['ssn' . $x] ),
										$fv ['lid' . $x] 
								);
								if (!$r) {
									$db->rollback ();
									return false;
								} // Error Check
							}
						}
					}
				} elseif ($fv ['lastName' . $x]) {
					// New
					$fCnt ++;
					$dc = $db->query ( 'SELECT id FROM t_lookup WHERE data = ? AND season = ?', 
							genHash ( $fv ['ssn' . $x] ),
							SEASON 
					);
					if ($dc === false) {
						$db->rollback ();
						return false;
					} // Error Check
					if ($dc->num_rows > 0) {
						// New SSN Duplicated
						$LID = $dc['id'];
					} else {
						// New SSN Clear
						$r = $db->executePrepared($sthInsL,
								genHash ( $fv ['ssn' . $x] ),
								SEASON,
								encString ( $fv ['ssn' . $x] ) 
						);
						if (!$r) {
							$db->rollback ();
							return false;
						} // Error Check
						$LID = $db->getOne ( 'SELECT LAST_INSERT_ID()' );
					}
					$r = $db->executePrepared($sthInsF,
							$fv ['id'],
							3,
							$fv ['firstName' . $x],
							$fv ['lastName' . $x],
							dashify ( $fv ['dob' . $x] ),
							$fv ['sex' . $x],
							$LID,
							$fv ['wishlist' . $x] 
					);
					if (!$r) {
						$db->rollback ();
						return false;
					} // Error Check
				}
			}
		}
		
		// Application
		/*
		 * $msg = "DUPLICATE: ".zeroize($fv['dupe']);
		 * mail("wesley@m3agency.com","APPLICATION UPDATE",$msg);
		 */
		$r = $db->execute('UPDATE t_application SET agency_id = ?,user_id = ?,phone = ?,street = ?,city = ?,state = ?,zip = ?,familySize = ?,employer = ?,income = ?,expenses = ?,comments = ?,ss = ?,ssi = ?,va = ?,tanf = ?,fstamps = ?,other = ?,toys = ?,food = ?, dupe = ?, tstampUpd = NOW() WHERE id = ?',
				$fv ['agency'],
				$uid,
				$fv ['phone'],
				$fv ['street'],
				$fv ['city'],
				$fv ['state'],
				$fv ['zip'],
				$fCnt,
				$fv ['employer'],
				$fv ['income'],
				$fv ['expense'],
				$fv ['comments'],
				(isset($fv ['ss']) && is_numeric($fv ['ss'])) ? $fv ['ss'] : '0',
				(isset($fv ['ssi']) && is_numeric($fv ['ssi'])) ? $fv ['ssi'] : '0',
				(isset($fv ['va']) && is_numeric($fv ['va'])) ? $fv ['va'] : '0',
				(isset($fv ['tanf']) && is_numeric($fv ['tanf'])) ? $fv ['tanf'] : '0',
				(isset($fv ['fStamp']) && is_numeric($fv ['fStamp'])) ? $fv ['fStamp'] : '0',
				(isset($fv ['other']) && is_numeric($fv ['other'])) ? $fv ['other'] : '0',
				$fv ['toys'],
				$fv ['food'],
				(isset($fv ['dupe']) && is_numeric($fv ['dupe'])) ? $fv ['dupe'] : '0',
				$fv ['id'] 
		);
		if (!$r) {
			$db->rollback ();
			return false;
		} // Error Check;
		                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
		// Add current App to Dupe Check
		if ($flg) {
			array_push ( $clear, $fv ['id'] );
		}
		
		// Dupe Check
		if (sizeof ( $clear > 0 )) {
			foreach ( $clear as $AppId ) {
				// Get Members
				$mems = $db->select ( 'SELECT lookup_id FROM t_family WHERE application_id = ?',$AppId );
				if (!$mems) {
					$db->rollback ();
					return false;
				} // Error Check
				if (sizeof ( $mems ) < 1) {
					continue;
				}
				// Loop thru Members
				$flgDup = 0;
				foreach ( $mems as $mem ) {
					// Check for Dupes
					$dup = $db->query ( 'SELECT id FROM t_family WHERE lookup_id = ? AND application_id < ? LIMIT 1',$mem['lookup_id'],$AppId);
					if ($dup === false) {
						$db->rollback ();
						return false;
					} // Error Check
					                                                          // Set Dupe Flag if id returned
					if ($dup->num_rows > 0) {
						$flgDup = 1;
					}
				}
				// Clear Dupe Field if no dupes
				if ($flgDup == 0) {
					$r = $db->executePrepared($sthUpdA,
							$AppId 
					);
					if (!$r) {
						$db->rollback ();
						return false;
					} // Error Check
				}
			}
		}
		// Success - Commit
		$db->commit();
		return true;
	} // end function
	  
	// Delete Methods
	public static function delApp($id) {
		$db = new Database ();
		
		// Get Members IDs
		$mems = $db->select ( 'SELECT id,lookup_id FROM t_family WHERE application_id = ?',$id);

		// Error Check
		if ($mems===false) {
			trigger_error("Invalid object passed to SQL", E_USER_WARNING);
			return false;
		}
		if (sizeof ( $mems ) < 1) {
			trigger_error("Application not found", E_USER_NOTICE);
			return false;
		}
		
		// Begin Transaction
		$db->autoCommit ( false );
		
		// prepare re-usable sql statements
		$sthDelF = $db->prepare ( 'DELETE FROM t_family WHERE id = ? LIMIT 1' );
		$sthDelL = $db->prepare ( 'DELETE FROM t_lookup WHERE id = ? LIMIT 1' );
		$sthDelA = $db->prepare ( 'DELETE FROM t_application WHERE id = ? LIMIT 1' );
		$sthUpdA = $db->prepare ( 'UPDATE t_application SET dupe = "0" WHERE id = ?' );
		
		// Loop thru Members
		$clear = array ();
		foreach ( $mems as $_ ) {
			// Check for Duplicates
			$fm = $db->select ( 'SELECT id,application_id FROM t_family WHERE lookup_id = ? ORDER BY application_id', $_['lookup_id']);
			if ($fm===false) {
				$db->rollback ();
				trigger_error("Invalid object passed to SQL", E_USER_WARNING);
				return false;
			} // Error Check
			$max = sizeof ( $fm );
			if ($max > 1) {
				// DUPLICATE
				$x = 0;
				for($x = 0; $x < $max; $x ++) {
					if ($x == 0 && $fm [$x]['application_id'] == $id) {
						// flag next application for clearing check, then delete member
						array_push ( $clear, $fm [$x + 1]['application_id'] );
						$r = $db->executePrepared($sthDelF,
								$fm [$x]->id 
						);
						if ($r===false) {
							$db->rollback ();
							trigger_error("Invalid object passed to SQL", E_USER_WARNING);
							return false;
						} // Error Check
						break;
					} elseif ($x > 0 && $fm [$x]['application_id'] == $id) {
						// just delete member
						$r = $db->executePrepared($sthDelF,
								$fm [$x]['id'] 
						);
						if ($r===false) {
							$db->rollback ();
							trigger_error("Invalid object passed to SQL", E_USER_WARNING);
							return false;
						} // Error Check
						break;
					} else {
						continue;
					}
				}
			} else {
				// CLEARED
				// delete lookup data and member from t_family (via cascade)
				$r = $db->executePrepared($sthDelL,$_['lookup_id']);
				$r &= $db->executePrepared($sthDelF,
								$fm [$x]['id'] 
						);
				if ($r===false) {
					$db->rollback ();
					trigger_error("Invalid object passed to SQL", E_USER_WARNING);
					return false;
				} // Error Check
			}
		}
		
		// Delete Application
		$r = $db->executePrepared($sthDelA,
				$id 
		);
		if ($r===false) {
			$db->rollback ();
			trigger_error("Invalid object passed to SQL", E_USER_WARNING);
			return false;
		} // Error Check
		                                                                            
		// Dupe Check
		if (sizeof ( $clear > 0 )) {
			foreach ( $clear as $AppId ) {
				// Get Members
				$mems = $db->select ( 'SELECT lookup_id FROM t_family WHERE application_id = ?',
						$AppId 
				);
				if ($mems===false) {
					$db->rollback ();
					trigger_error("Invalid object passed to SQL", E_USER_WARNING);
					return false;
				} // Error Check
				if (sizeof ( $mems ) < 1) {
					continue;
				}
				// Loop thru Members
				$flgDup = 0;
				foreach ( $mems as $mem ) {
					// Check for Dupes
					$dup = $db->query ( 'SELECT id FROM t_family WHERE lookup_id = ? AND application_id < ? LIMIT 1',
							$mem['lookup_id'],
							$AppId 
					);
					if ($dup === false) {
						$db->rollback ();
						trigger_error("Invalid object passed to SQL", E_USER_WARNING);
						return false;
					} // Error Check
					                                                                              // Set Dupe Flag if id returned
					if ($dup->num_rows > 0) {
						$flgDup = 1;
					}
				}
				// Clear Dupe Field if no dupes
				if ($flgDup == 0) {
					$r = $db->executePrepared($sthUpdA,
							$AppId 
					);
					if ($r===false) {
						$db->rollback ();
						trigger_error("Invalid object passed to SQL", E_USER_WARNING);
						return false;;
					} // Error Check
				}
			}
		}
		// We've made it without errors - Commit transaction
		$db->commit ();
		return true;
	} // end function
}
?>
