<?
/* App.php
SQL queries for Application related functions
*/

Class App {

	// Select Methods
	function getApplication($id) {
		$db = Database::getInstance();
		return $db->getRow('SELECT t_agency.id,t_agency.name,t_user.username,tstamp,tstampUpd,season,t_application.phone,street,city,state,zip,familySize,employer,income,expenses,comments,ss,ssi,va,tanf,fstamps,other,toys,food,dupe 
				FROM t_application,t_agency,t_user 
				WHERE t_application.id = ? 
				AND t_agency.id = t_application.agency_id 
				AND t_user.id = t_application.user_id',array($id));
	}

	function getAppMembers($id) {
		$db = Database::getInstance();
		$r1 = $db->getRow('SELECT firstName,lastName,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,(?-YEAR(dob)) AS age FROM t_family WHERE application_id = ? AND type = 1',array(SEASON,$id));
		$r2 = $db->getRow('SELECT firstName,lastName,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,(?-YEAR(dob)) AS age FROM t_family WHERE application_id = ? AND type = 2',array(SEASON,$id));
		$r3 = $db->getAll('SELECT firstName,lastName,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,sex,(?-YEAR(dob)) AS age FROM t_family WHERE application_id = ? AND type = 3',array(SEASON,$id));
		return array($r1,$r2,$r3);
	}

	function getAppMembersEdit($id) {
		$db = Database::getInstance();
		$r1 = $db->getRow('SELECT t_family.id AS fid,t_lookup.id AS lid,firstName,lastName,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,data FROM t_family,t_lookup WHERE application_id = ? AND type = 1 AND t_family.lookup_id = t_lookup.id',array($id));
		$r2 = $db->getRow('SELECT t_family.id AS fid,t_lookup.id AS lid,firstName,lastName,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,data FROM t_family,t_lookup WHERE application_id = ? AND type = 2 AND t_family.lookup_id = t_lookup.id',array($id));
		$r3 = $db->getAll('SELECT t_family.id AS fid,t_lookup.id AS lid,firstName,lastName,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,sex,data FROM t_family,t_lookup WHERE application_id = ? AND type = 3 AND t_family.lookup_id = t_lookup.id',array($id));
		return array($r1,$r2,$r3);
	}

	function getAllAppMembers($id,$uid) {
		$db = Database::getInstance();
		return $db->getAll('SELECT t_family.lookup_id,t_family.firstName,t_family.lastName 
				FROM t_family,t_application,t_user_agency 
				WHERE t_family.application_id = ?
				AND t_application.id = t_family.application_id 
				AND t_user_agency.agency_id = t_application.agency_id 
				AND t_user_agency.user_id = ?
				ORDER BY t_family.lookup_id',array($id,$uid));
	}

	function getDupExistingInfo($appId,$lookId) {
		$db = Database::getInstance();
		return $db->getAll('SELECT t_application.id,t_agency.name,t_agency.id AS aid
                                FROM t_family,t_application,t_agency
                                WHERE t_family.lookup_id = ? 
                                AND t_family.application_id < ? 
                                AND t_application.id = t_family.application_id
                                AND t_agency.id = t_application.agency_id',array($lookId,$appId));
	}

	function getUsersHistoryCount($uid) {
		$db = Database::getInstance();
		return $db->getOne('SELECT count(id) FROM t_application WHERE user_id = ? AND season = ?',array($uid,SEASON));
	}

	function getUsersHistory($uid,$page) { 
		$db = Database::getInstance();
		$low = ($page -1) * PER_PAGE;
		return $db->getAll('SELECT t_family.lastName,t_application.familySize,t_application.tstamp,t_application.id
				FROM t_application,t_family 
				WHERE t_application.user_id = ?
				AND t_family.application_id = t_application.id
				AND t_family.type = 1
				AND t_application.season = ? 
				ORDER BY t_application.id DESC
				LIMIT ?,?',array($uid,SEASON,$low,PER_PAGE));
	}

	function getDupeApps($id,$page) {
		$db = Database::getInstance();
		$low = ($page -1) * PER_PAGE;
                return $db->getAll('SELECT t_family.lastName,t_application.familySize,t_application.tstamp,t_application.id
                                FROM t_application,t_family
                                WHERE dupe = "1"
				AND t_application.agency_id = ?
				AND t_family.application_id = t_application.id
				AND t_family.type = 1
				AND t_application.season = ?
				ORDER BY t_application.id
				LIMIT ?,?',array($id,SEASON,$low,PER_PAGE));
        }

	function getDupeApps_test($id,$page) {
		$db = Database::getInstance();
		$low = ($page -1) * PER_PAGE;
                return $db->getAll('SELECT t_family.lastName,t_application.familySize,t_application.tstamp,t_application.id
                                FROM t_application,t_family
                                WHERE dupe = "1"
								OR dupe1 = "1"
				AND t_application.agency_id = ?
				AND t_family.application_id = t_application.id
				AND t_family.type = 1
				AND t_application.season = ?
				ORDER BY t_application.id
				LIMIT ?,?',array($id,SEASON,$low,PER_PAGE));
        }

	function SearchBySSN($data,$year,$uid) {
		$db = Database::getInstance();
		if ($year) {
			return $db->getAll('SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob
				FROM t_lookup,t_family,t_application,t_user_agency
				WHERE data = ?
				AND t_lookup.season = ? 
				AND t_family.lookup_id = t_lookup.id
				AND t_application.id = t_family.application_id
				AND t_user_agency.user_id = ?
				AND t_application.agency_id = t_user_agency.agency_id
				ORDER BY tstamp',array($data,$year,$uid));
		}
		else {
			return $db->getAll('SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob
				FROM t_lookup,t_family,t_application,t_user_agency
				WHERE data = ?
				AND t_family.lookup_id = t_lookup.id
				AND t_application.id = t_family.application_id
				AND t_user_agency.user_id = ?
				AND t_application.agency_id = t_user_agency.agency_id
				ORDER BY tstamp',array($data,$uid));
		}
	}

	function SearchByLNameCnt($lname,$year,$uid) {
		$db = Database::getInstance();
		if ($year) {
			return $db->getOne('SELECT count(t_family.id) 
				FROM t_family,t_application,t_user_agency
				WHERE lastName LIKE ?
				AND t_application.id = t_family.application_id
				AND t_application.season = ?
				AND t_user_agency.user_id = ?
				AND t_application.agency_id = t_user_agency.agency_id',array($lname,$year,$uid));
		}
		else {
			return $db->getOne('SELECT count(t_family.id)
				FROM t_family,t_application,t_user_agency
				WHERE lastName LIKE ?
				AND t_application.id = t_family.application_id
				AND t_user_agency.user_id = ?
				AND t_application.agency_id = t_user_agency.agency_id',array($lname,$uid));
		}
	}	

	function SearchByLName($lname,$year,$uid,$page) {
		$db = Database::getInstance();
		$low = ($page -1) * PER_PAGE;
		if ($year) {
			return $db->getAll('SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob
                                FROM t_family,t_application,t_user_agency
                                WHERE lastName LIKE ?
                                AND t_application.id = t_family.application_id
                                AND t_application.season = ?
                                AND t_user_agency.user_id = ?
                                AND t_application.agency_id = t_user_agency.agency_id
				ORDER BY tstamp LIMIT ?,?',array($lname,$year,$uid,$low,PER_PAGE));
		}
		else {
                        return $db->getAll('SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob
                                FROM t_family,t_application,t_user_agency
                                WHERE lastName LIKE ?
                                AND t_application.id = t_family.application_id
                                AND t_user_agency.user_id = ?
                                AND t_application.agency_id = t_user_agency.agency_id
				ORDER BY tstamp LIMIT ?,?',array($lname,$uid,$low,PER_PAGE));
		}
	}

        function SearchByDOB($dob,$year,$uid) {
                $db = Database::getInstance();
                if ($year) {
                        return $db->getAll('SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob
                                FROM t_family,t_application,t_user_agency
                                WHERE dob = STR_TO_DATE(?,\'%m-%d-%Y\') 
                                AND t_application.id = t_family.application_id
                                AND t_application.season = ?
                                AND t_user_agency.user_id = ?
                                AND t_application.agency_id = t_user_agency.agency_id
                                ORDER BY tstamp',array($dob,$year,$uid));
                }
                else {
                        return $db->getAll('SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob
                                FROM t_family,t_application,t_user_agency
                                WHERE dob = STR_TO_DATE(?,\'%m-%d-%Y\') 
                                AND t_application.id = t_family.application_id
                                AND t_user_agency.user_id = ?
                                AND t_application.agency_id = t_user_agency.agency_id
                                ORDER BY tstamp',array($dob,$uid));
                }
        }

        function SearchBySSNAdmin($data,$year) {
                $db = Database::getInstance();
                if ($year) {
                        return $db->getAll('SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,t_agency.name
                                FROM t_lookup,t_family,t_application,t_agency
                                WHERE data = ?
                                AND t_lookup.season = ?
                                AND t_family.lookup_id = t_lookup.id
                                AND t_application.id = t_family.application_id
                                AND t_agency.id = t_application.agency_id
                                ORDER BY tstamp',array($data,$year));
                }
                else {
                        return $db->getAll('SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,t_agency.name
                                FROM t_lookup,t_family,t_application,t_agency
                                WHERE data = ?
                                AND t_family.lookup_id = t_lookup.id
                                AND t_application.id = t_family.application_id
                                AND t_agency.id = t_application.agency_id 
                                ORDER BY tstamp',array($ssn));
                }
        }

        function SearchByLNameAdminCnt($lname,$year) {
                $db = Database::getInstance();
                if ($year) {
                        return $db->getOne('SELECT count(t_family.id)
					FROM t_family,t_application
					WHERE lastName LIKE ? 
					AND t_application.season = ?
					AND t_application.id = t_family.application_id',array($lname,$year));
                }
                else {
                        return $db->getOne('SELECT count(t_family.id) FROM t_family WHERE lastName LIKE ?',array($lname));
                }
        }

        function SearchByLNameAdmin($lname,$year,$page) {
                $db = Database::getInstance();
		$low = ($page -1) * PER_PAGE;
                if ($year) {
                        return $db->getAll('SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,t_agency.name
                                FROM t_family,t_application,t_agency
                                WHERE lastName LIKE ?
                                AND t_application.id = t_family.application_id
                                AND t_application.season = ?
                                AND t_agency.id = t_application.agency_id 
                                ORDER BY tstamp LIMIT ?,?',array($lname,$year,$low,PER_PAGE));
                }
                else {
                        return $db->getAll('SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,t_agency.name
                                FROM t_family,t_application,t_agency
                                WHERE lastName LIKE ?
                                AND t_application.id = t_family.application_id
                                AND t_agency.id = t_application.agency_id 
                                ORDER BY tstamp LIMIT ?,?',array($lname,$low,PER_PAGE));
                }
        }

        function SearchByDOBAdmin($dob,$year) {
                $db = Database::getInstance();
                if ($year) {
                        return $db->getAll('SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,t_agency.name
                                FROM t_family,t_application,t_agency
                                WHERE dob = STR_TO_DATE(?,\'%m-%d-%Y\')
                                AND t_application.id = t_family.application_id
                                AND t_application.season = ?
                                AND t_agency.id = t_application.agency_id 
                                ORDER BY tstamp',array($dob,$year));
                }
                else {
                        return $db->getAll('SELECT lastName,firstName,tstamp,t_application.id,DATE_FORMAT(dob,\'%m-%d-%Y\') as dob,t_agency.name
                                FROM t_family,t_application,t_agency
                                WHERE dob = STR_TO_DATE(?,\'%m-%d-%Y\')
                                AND t_application.id = t_family.application_id
                                AND t_agency.id = t_application.agency_id 
                                ORDER BY tstamp',array($dob));
                }
        }

	function chkDupe($string,$id) {
		$db = Database::getInstance();
		$dup = $db->getOne('SELECT t_lookup.id FROM t_lookup,t_family,t_application
				WHERE data = ? AND t_lookup.season = ? 
				AND t_family.lookup_id = t_lookup.id
				AND t_application.id = t_family.application_id
				AND (t_application.dupe = "0" OR (t_application.dupe = "1" AND t_application.id <= ?))',array(genHash($string),SEASON,$id));
		
			// Error Check
		if (DB::isError($dup)) {
			genError(STD_ERR);
			exit;
		}
		return $dup > 0 ? true : false;
	}

	function getSeason() {
		$db = Database::getInstance();
		$r = $db->getOne('SELECT season from t_config');
		if (DB::isError($r)) {
			return date('Y');
		}
		else {
			return $r;
		}
	}
	function setSeason($val) {
		$db = Database::getInstance();
		return $db->query('UPDATE t_config SET season = ?',array($val));
	}

        // Insert Methods

	function insApplication($fv,$uid) {
                $q = array();
		$flg = 0;
		$fCnt = 0;
		$db = Database::getInstance();
	
		// Begin Transaction
		$db->autoCommit(false);

		// Application
		$q[] = $db->query('INSERT INTO t_application (agency_id,user_id,season,phone,street,city,state,zip,employer,income,expenses,comments,ss,ssi,va,tanf,fstamps,other,toys,food) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($fv['agency'],$uid,SEASON,$fv['phone'],$fv['street'],$fv['city'],$fv['state'],$fv['zip'],$fv['employer'],$fv['income'],$fv['expense'],$fv['comments'],zeroize($fv['ss']),zeroize($fv['ssi']),zeroize($fv['va']),zeroize($fv['tanf']),zeroize($fv['fStamp']),zeroize($fv['other']),$fv['toys'],$fv['food']));
		$appId = $db->getOne('SELECT LAST_INSERT_ID()');
		
		// prepare re-usable sql statements 
		$sth_selL = $db->prepare('SELECT id FROM t_lookup WHERE data = ? AND season = ? LIMIT 1');
		$sth_insL = $db->prepare('INSERT INTO t_lookup (data,season,dataExp) VALUES (?,?,?)');
		$sth_insApp = $db->prepare('INSERT INTO t_family (application_id,type,firstName,lastName,dob,lookup_id,sex) VALUES (?,?,?,?,STR_TO_DATE(?,\'%m-%d-%Y\'),?,?)');

		// Applicant
		$fCnt++; 
		$res = $db->execute($sth_selL, array(genHash($fv['ssn']),SEASON));
                  if (DB::isError($res)) { $db->rollback(); return false; } // Error Check
		$tmp = $res->fetchRow();
		if($tmp->id > 0) {
			// Duplicate
			$LID = $tmp->id;
			$flg++;
		}
		else {
			// New
			$q[] = $db->execute($sth_insL, array(genHash($fv['ssn']),SEASON,encString($fv['ssn'])));
			$LID = $db->getOne('SELECT LAST_INSERT_ID()');
		}
		$q[] = $db->execute($sth_insApp, array($appId,1,$fv['firstName'],$fv['lastName'],dashify($fv['dob']),$LID,null));

		// Spouse
		if ($fv['lastNameSp']) {
			$fCnt++;
			$res = $db->execute($sth_selL, array(genHash($fv['ssnSp']),SEASON));
			  if (DB::isError($res)) { $db->rollback(); return false; } // Error Check
			$tmp = $res->fetchRow();
			if($tmp->id > 0) {
				// Duplicate
				$LID = $tmp->id;
				$flg++;
			}
			else {
				// New
				$q[] = $db->execute($sth_insL, array(genHash($fv['ssnSp']),SEASON,encString($fv['ssnSp'])));
				$LID = $db->getOne('SELECT LAST_INSERT_ID()');
			}
			$q[] = $db->execute($sth_insApp, array($appId,2,$fv['firstNameSp'],$fv['lastNameSp'],dashify($fv['dobSp']),$LID,null));
		}

		// Others
		for ($x=0;$x<CNT_OTHERS;$x++) {
			if ($fv['lastName'.$x]) {
				$fCnt++;
				$res = $db->execute($sth_selL,array(genHash($fv['ssn'.$x]),SEASON));
				  if (DB::isError($res)) { $db->rollback(); return false; } // Error Check
				$tmp = $res->fetchRow();
				if($tmp->id > 0) {
					// Duplicate
					$LID = $tmp->id;
					$flg++;
				}
				else {
					//New
					$q[] = $db->execute($sth_insL, array(genHash($fv['ssn'.$x]),SEASON,encString($fv['ssn'.$x])));
					$LID = $db->getOne('SELECT LAST_INSERT_ID()');
				}
				$q[] = $db->execute($sth_insApp, array($appId,3,$fv['firstName'.$x],$fv['lastName'.$x],dashify($fv['dob'.$x]),$LID,$fv['sex'.$x]));
			}
		}

		// Update application with Family Count
		$q[] = $db->query('UPDATE t_application SET familySize = ? WHERE id = ?',array($fCnt,$appId));

		// Flag Dupes
		if ($flg > 0) {
			$q[] = $db->query('UPDATE t_application SET dupe = "1" WHERE id = ?',array($appId));
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
			if ($flg > 0) {
				return $appId;
			}
			else {
				return true;
			}
		}
	} // end function
	
	
	// Update Methods

	function updateApp($fv,$uid) {
		$db = Database::getInstance();
		$q = array();
		$clear = array();
		$flg = 0;
		$fCnt = 0;

		// Begin Transaction
		$db->autoCommit(false);

		// prepare re-usable sql statements
		$sthDelL = $db->prepare('DELETE FROM t_lookup WHERE id = ? LIMIT 1'); 
		$sthDelF = $db->prepare('DELETE FROM t_family WHERE id = ? LIMIT 1');
		$sthUpdA = $db->prepare('UPDATE t_application SET dupe = "0" WHERE id = ?');
		$sthUpdF = $db->prepare('UPDATE t_family SET firstName = ?,lastName = ?,dob = STR_TO_DATE(?,\'%m-%d-%Y\'),sex = ? WHERE id = ?');
		$sthUpdL = $db->prepare('UPDATE t_lookup SET data = ?, dataExp = ? WHERE id = ?');
		$sthUpdN = $db->prepare('UPDATE t_family SET lookup_id = ? WHERE id = ?');
		$sthInsL = $db->prepare('INSERT INTO t_lookup (data,season,dataExp) VALUES (?,?,?)');
		$sthInsF = $db->prepare('INSERT INTO t_family (application_id,type,firstName,lastName,dob,sex,lookup_id) VALUES (?,?,?,?,STR_TO_DATE(?,\'%m-%d-%Y\'),?,?)');
		
		// Primary
		$fCnt++;
		if ($fv['ssn'] == '*********' || genHash($fv['ssn']) == $fv['oldSsn']) {
			// No SSN change
			$r = $db->execute($sthUpdF,array($fv['firstName'],$fv['lastName'],dashify($fv['dob']),null,$fv['fid']));
			  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
		}
		else {
			// SSN Change
			$flg++;
                        $fm = $db->getAll('SELECT id,application_id FROM t_family WHERE lookup_id = ? ORDER BY application_id',array($fv['lid']));
                          if (DB::isError($fm)) { $db->rollback(); return false; } // Error Check
                        if (sizeof($fm) > 1) {
				// Applicant has dupes
				if ($fm[0]->application_id == $fv['id']) {
                        		// flag next application for clearing check
					array_push($clear, $fm[1]->application_id);
				}
				// Check New SSN
				$dc = $db->getOne('SELECT id FROM t_lookup WHERE data = ? AND season = ?',array(genHash($fv['ssn']),SEASON));
				  if (DB::isError($dc)) { $db->rollback(); return false; } // Error Check
				if ($dc > 0) {
					// New SSN Duplicated - use existing LID
					$LID = $dc;
				}
				else {
					// New SSN Clear - create new
					$r = $db->execute($sthInsL,array(genHash($fv['ssn']),SEASON,encString($fv['ssn'])));
					  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
					$LID = $db->getOne('SELECT LAST_INSERT_ID()');
				}
				// update t_family
				$r = $db->execute($sthUpdN,array($LID,$fv['fid']));
			}
			else {
				// No dupes	
				$dc = $db->getOne('SELECT id FROM t_lookup WHERE data = ? AND season = ?',array(genHash($fv['ssn']),SEASON));
				  if (DB::isError($dc)) { $db->rollback(); return false; } // Error Check
				if ($dc > 0) {
					// New SSN Duplicated - use found LID
					$r = $db->execute($sthUpdN,array($dc,$fv['fid']));
					  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
					// Delete orphaned LID
					$r = $db->execute($sthDelL,array($fv['lid']));
					  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
				}
				else {
					// New SSN Clear - Update old
					$r = $db->execute($sthUpdL,array(genHash($fv['ssn']),encString($fv['ssn']),$fv['lid']));
					  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
				}
			}	
		}
		// Spouse
		if (isset($fv['delSp']) && $fv['delSp'] == 1 && isset($fv['fidSp'])) { // Delete Spouse
			$flg++; 
                        // Check for Duplicates
                        $fm = $db->getAll('SELECT id,application_id FROM t_family WHERE lookup_id = ? ORDER BY application_id',array($fv['lidSp']));
                          if (DB::isError($fm)) { $db->rollback(); return false; } // Error Check
                        if (sizeof($fm) > 1) {
                                // DUPLICATE
				if ($fm[0]->application_id == $fv['id']) {
					// flag next application for clearing check
					array_push($clear, $fm[1]->application_id);
				}
				// Delete member only 
				$r = $db->execute($sthDelF,array($fv['fidSp']));
				  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
			}
                        else {
                                // CLEARED
                                // delete lookup data and member from t_family (via cascade)
                                $r = $db->execute($sthDelL,array($fv['lidSp']));
                                  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
                        }
		}
		else { // Update Spouse 
		if (isset($fv['fidSp'])) {
			// Existing
			$fCnt++;
			if ($fv['ssnSp'] == '*********' || genHash($fv['ssnSp']) == $fv['oldSsnSp']) {
				// No SSN Change
				$r = $db->execute($sthUpdF,array($fv['firstNameSp'],$fv['lastNameSp'],dashify($fv['dobSp']),null,$fv['fidSp']));
				  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
			}
			else {
	                        // SSN Change
				$flg++;
	                        $fm = $db->getAll('SELECT id,application_id FROM t_family WHERE lookup_id = ? ORDER BY application_id',array($fv['lidSp']));
	                          if (DB::isError($fm)) { $db->rollback(); return false; } // Error Check
	                        if (sizeof($fm) > 1) {
	                                // Applicant has dupes
	                                if ($fm[0]->application_id == $fv['id']) {
	                                        // flag next application for clearing check
	                                        array_push($clear, $fm[1]->application_id);
	                                }
	                                // Check New SSN
	                                $dc = $db->getOne('SELECT id FROM t_lookup WHERE data = ? AND season = ?',array(genHash($fv['ssnSp']),SEASON));
	                                  if (DB::isError($dc)) { $db->rollback(); return false; } // Error Check
	                                if ($dc > 0) {
	                                        // New SSN Duplicated - use existing LID
	                                        $LID = $dc;
	                                }
	                                else {
	                                        // New SSN Clear - create new
	                                        $r = $db->execute($sthInsL,array(genHash($fv['ssnSp']),SEASON,encString($fv['ssnSp'])));
	                                          if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
	                                        $LID = $db->getOne('SELECT LAST_INSERT_ID()');
	                                }
	                                // update t_family
	                                $r = $db->execute($sthUpdN,array($LID,$fv['fidSp']));
	                        }
	                        else {
	                                // No dupes
	                                $dc = $db->getOne('SELECT id FROM t_lookup WHERE data = ? AND season = ?',array(genHash($fv['ssnSp']),SEASON));
	                                  if (DB::isError($dc)) { $db->rollback(); return false; } // Error Check
	                                if ($dc > 0) {
	                                        // New SSN Duplicated - use found LID
	                                        $r = $db->execute($sthUpdN,array($dc,$fv['fidSp']));
	                                          if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
	                                        // Delete orphaned LID
	                                        $r = $db->execute($sthDelL,array($fv['lidSp']));
	                                          if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
	                                }
	                                else {
	                                        // New SSN Clear - Update old
	                                        $r = $db->execute($sthUpdL,array(genHash($fv['ssnSp']),encString($fv['ssnSp']),$fv['lidSp']));
	                                          if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
	                                }
	                        }
			}
		}
		elseif ($fv['lastNameSp']) {
			// New
			$fCnt++;
                        $dc = $db->getOne('SELECT id FROM t_lookup WHERE data = ? AND season = ?',array(genHash($fv['ssnSp']),SEASON));
                          if (DB::isError($dc)) { $db->rollback(); return false; } // Error Check
                        if ($dc > 0) {
                                // New SSN Duplicated
				$LID = $dc; 
                        }
                        else {
                                // New SSN Clear 
                                $r = $db->execute($sthInsL,array(genHash($fv['ssnSp']),SEASON,encString($fv['ssnSp'])));
                                  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
				$LID = $db->getOne('SELECT LAST_INSERT_ID()');
                        }
                        $r = $db->execute($sthInsF, array($fv['id'],2,$fv['firstNameSp'],$fv['lastNameSp'],dashify($fv['dobSp']),null,$LID));
			  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
		}
		}
		// Members
                for ($x=0;$x<CNT_OTHERS;$x++) {
			if (isset($fv['del'.$x]) && $fv['del'.$x] == 1 && isset($fv['fid'.$x])) { // Delete Member
			$flg++;
                        // Check for Duplicates
	                        $fm = $db->getAll('SELECT id,application_id FROM t_family WHERE lookup_id = ? ORDER BY application_id',array($fv['lid'.$x]));
	                          if (DB::isError($fm)) { $db->rollback(); return false; } // Error Check
	                        if (sizeof($fm) > 1) {
	                                // DUPLICATE
	                                if ($fm[0]->application_id == $fv['id']) {
	                                        // flag next application for clearing check
	                                        array_push($clear, $fm[1]->application_id);
	                                }
	                                // Delete member only 
	                                $r = $db->execute($sthDelF,array($fv['fid'.$x]));
	                                  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
	                        }
	                        else {
	                                // CLEARED
	                                // delete lookup data and member from t_family (via cascade)
	                                $r = $db->execute($sthDelL,array($fv['lid'.$x]));
	                                  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
	                        }
			}
			else { // Update Member
			if (isset($fv['fid'.$x])) {
				// Existing
				$fCnt++;
				if ($fv['ssn'.$x] == '*********' || genHash($fv['ssn'.$x]) == $fv['oldSsn'.$x]) {
					// No SSN Change
					$r = $db->execute($sthUpdF,array($fv['firstName'.$x],$fv['lastName'.$x],dashify($fv['dob'.$x]),$fv['sex'.$x],$fv['fid'.$x]));
					  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
				}
				else {
					// SSN Change
					$flg++;
	                                $fm = $db->getAll('SELECT id,application_id FROM t_family WHERE lookup_id = ? ORDER BY application_id',array($fv['lid'.$x]));
	                                  if (DB::isError($fm)) { $db->rollback(); return false; } // Error Check
		                        if (sizeof($fm) > 1) {
		                                // Applicant has dupes
		                                if ($fm[0]->application_id == $fv['id']) {
		                                        // flag next application for clearing check
		                                        array_push($clear, $fm[1]->application_id);
		                                }
		                                // Check New SSN
		                                $dc = $db->getOne('SELECT id FROM t_lookup WHERE data = ? AND season = ?',array(genHash($fv['ssn'.$x]),SEASON));
		                                  if (DB::isError($dc)) { $db->rollback(); return false; } // Error Check
		                                if ($dc > 0) {
		                                        // New SSN Duplicated - use existing LID
		                                        $LID = $dc;
		                                }
		                                else {
		                                        // New SSN Clear - create new
		                                        $r = $db->execute($sthInsL,array(genHash($fv['ssn'.$x]),SEASON,encString($fv['ssn'.$x])));
		                                          if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
		                                        $LID = $db->getOne('SELECT LAST_INSERT_ID()');
		                                }
		                                // update t_family
		                                $r = $db->execute($sthUpdN,array($LID,$fv['fid'.$x]));	
		                        }
		                        else {
		                                // No dupes
		                                $dc = $db->getOne('SELECT id FROM t_lookup WHERE data = ? AND season = ?',array(genHash($fv['ssn'.$x]),SEASON));
		                                  if (DB::isError($dc)) { $db->rollback(); return false; } // Error Check
		                                if ($dc > 0) {
		                                        // New SSN Duplicated - use found LID
		                                        $r = $db->execute($sthUpdN,array($dc,$fv['fid'.$x]));
		                                          if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
		                                        // Delete orphaned LID
		                                        $r = $db->execute($sthDelL,array($fv['lid'.$x]));
		                                          if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
		                                }
		                                else {
		                                        // New SSN Clear - Update old
		                                        $r = $db->execute($sthUpdL,array(genHash($fv['ssn'.$x]),encString($fv['ssn'.$x]),$fv['lid'.$x]));
		                                          if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
		                                }
		                        }

				}
			}
                        elseif ($fv['lastName'.$x]) {
				// New
				$fCnt++;
	                        $dc = $db->getOne('SELECT id FROM t_lookup WHERE data = ? AND season = ?',array(genHash($fv['ssn'.$x]),SEASON));
	                          if (DB::isError($dc)) { $db->rollback(); return false; } // Error Check
	                        if ($dc > 0) {
	                                // New SSN Duplicated
	                                $LID = $dc;
	                        }
	                        else {
	                                // New SSN Clear
	                                $r = $db->execute($sthInsL,array(genHash($fv['ssn'.$x]),SEASON,encString($fv['ssn'.$x])));
	                                  if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
	                                $LID = $db->getOne('SELECT LAST_INSERT_ID()');
	                        }
	                        $r = $db->execute($sthInsF, array($fv['id'],3,$fv['firstName'.$x],$fv['lastName'.$x],dashify($fv['dob'.$x]),$fv['sex'.$x],$LID));
	                          if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
                        }
			}
                }

                // Application
                $r = $db->query('UPDATE t_application SET agency_id = ?,user_id = ?,phone = ?,street = ?,city = ?,state = ?,zip = ?,familySize = ?,employer = ?,income = ?,expenses = ?,comments = ?,ss = ?,ssi = ?,va = ?,tanf = ?,fstamps = ?,other = ?,toys = ?,food = ?,tstampUpd = NOW() WHERE id = ?', array($fv['agency'],$uid,$fv['phone'],$fv['street'],$fv['city'],$fv['state'],$fv['zip'],$fCnt,$fv['employer'],$fv['income'],$fv['expense'],$fv['comments'],zeroize($fv['ss']),zeroize($fv['ssi']),zeroize($fv['va']),zeroize($fv['tanf']),zeroize($fv['fStamp']),zeroize($fv['other']),$fv['toys'],$fv['food'],$fv['id']));                 if (DB::isError($r)) { $db->rollback(); return false; } // Error Check;
	
		// Add current App to Dupe Check
		if ($flg) {
			array_push($clear, $fv['id']);
		}

                // Dupe Check
                if (sizeof($clear >0)) {
                        foreach ($clear as $AppId) {
                                // Get Members
                                $mems = $db->getAll('SELECT lookup_id FROM t_family WHERE application_id = ?',array($AppId));
                                  if (DB::isError($mems)) { $db->rollback(); return false; } // Error Check
                                if (sizeof($mems)<1) {
                                        continue;
                                }
                                // Loop thru Members
                                $flgDup = 0;
                                foreach ($mems as $mem) {
                                        // Check for Dupes
                                        $dup = $db->getOne('SELECT id FROM t_family WHERE lookup_id = ? AND application_id < ? LIMIT 1',array($mem->lookup_id,$AppId));
                                          if (DB::isError($dup)) { $db->rollback(); return false; } // Error Check
                                        // Set Dupe Flag if id returned
                                        if ($dup >0) {
                                                $flgDup = 1;
                                        }
                                }
                                // Clear Dupe Field if no dupes
                                if ($flgDup == 0) {
                                        $r = $db->execute($sthUpdA,array($AppId));
                                          if (DB::isError($r)) { $db->rollback(); return false; } // Error Check
                                }
                        }
                }
		// Success - Commit
               $db->commit();
               return true;
	} // end function
 		

	// Delete Methods

	function delApp($id) {
		$db = Database::getInstance();

		// Get Members IDs
		$mems = $db->getAll('SELECT id,lookup_id FROM t_family WHERE application_id = ?',array($id));

		// Error Check
		if (DB::isError($mems)) {
			return PEAR::RaiseError(STD_ERR);
		}
		if (sizeof($mems) <1) {
			return PEAR::RaiseError('Application not found');
		}

                // Begin Transaction
                $db->autoCommit(false);

		// prepare re-usable sql statements
		$sthDelF = $db->prepare('DELETE FROM t_family WHERE id = ? LIMIT 1');
		$sthDelL = $db->prepare('DELETE FROM t_lookup WHERE id = ? LIMIT 1');
		$sthDelA = $db->prepare('DELETE FROM t_application WHERE id = ? LIMIT 1');
		$sthUpdA = $db->prepare('UPDATE t_application SET dupe = "0" WHERE id = ?');

		// Loop thru Members
		$clear = array();
		foreach ($mems as $_) {
			// Check for Duplicates 
			$fm = $db->getAll('SELECT id,application_id FROM t_family WHERE lookup_id = ? ORDER BY application_id',array($_->lookup_id));
			if (DB::isError($fm)) { $db->rollback(); return PEAR::RaiseError(STD_ERR); } // Error Check
			$max = sizeof($fm);
			if ($max > 1) {
				// DUPLICATE
				$x=0;
				for ($x=0;$x<$max;$x++) {
					if ($x == 0 && $fm[$x]->application_id == $id) {
						// flag next application for clearing check, then delete member
						array_push($clear, $fm[$x+1]->application_id);
						$r = $db->execute($sthDelF,array($fm[$x]->id));
						if (DB::isError($r)) { $db->rollback(); return PEAR::RaiseError(STD_ERR); } // Error Check
						break;	
					}
					elseif ($x > 0 && $fm[$x]->application_id == $id) {
						// just delete member
						$r = $db->execute($sthDelF,array($fm[$x]->id));
						if (DB::isError($r)) { $db->rollback(); return PEAR::RaiseError(STD_ERR); } // Error Check
						break;
					}
					else {
						continue;
					}
				}
			}
			else {
				// CLEARED
				// delete lookup data and member from t_family (via cascade)
				$r = $db->execute($sthDelL,array($_->lookup_id));
				if (DB::isError($r)) { $db->rollback(); return PEAR::RaiseError(STD_ERR); } // Error Check
			}
		}

		// Delete Application
		$r = $db->execute($sthDelA,array($id));
		if (DB::isError($r)) { $db->rollback(); return PEAR::RaiseError(STD_ERR); } // Error Check

		// Dupe Check
		if (sizeof($clear >0)) {
			foreach ($clear as $AppId) {
				// Get Members 
				$mems = $db->getAll('SELECT lookup_id FROM t_family WHERE application_id = ?',array($AppId));
				if (DB::isError($mems)) { $db->rollback(); return PEAR::RaiseError(STD_ERR); } // Error Check
				if (sizeof($mems)<1) {
					continue;
				}
				// Loop thru Members
				$flgDup = 0;
				foreach ($mems as $mem) {
					// Check for Dupes
					$dup = $db->getOne('SELECT id FROM t_family WHERE lookup_id = ? AND application_id < ? LIMIT 1',array($mem->lookup_id,$AppId));
					if (DB::isError($dup)) { $db->rollback(); return PEAR::RaiseError(STD_ERR); } // Error Check
					// Set Dupe Flag if id returned	
					if ($dup >0) {
						$flgDup = 1;
					}
				}
				// Clear Dupe Field if no dupes
				if ($flgDup == 0) {
					$r = $db->execute($sthUpdA,array($AppId));
					if (DB::isError($r)) { $db->rollback(); return PEAR::RaiseError(STD_ERR); } // Error Check
				}
			}
		}
		// We've made it without errors - Commit transaction
		$db->commit();
		return true;
	} // end function
}

?>
