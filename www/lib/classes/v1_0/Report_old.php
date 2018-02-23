<?
/* Report.php
SQL queries for Application Reports
*/

Class Report {

	function genAgeReport($uid) {
		$db = Database::getInstance();
		return $db->getAll('SELECT t_agency.name,t_application.toys,
			COUNT((SELECT t_family.id FROM t_family AS i_family WHERE ?-YEAR(dob) < 3 
				AND sex = "Male" AND t_family.id = i_family.id)) AS count3M,
			COUNT((SELECT t_family.id FROM t_family AS i_family WHERE ?-YEAR(dob) < 3 
				AND sex = "Female" AND t_family.id = i_family.id)) AS count3F,
			COUNT((SELECT t_family.id FROM t_family AS i_family WHERE ?-YEAR(dob) < 6 AND ?-YEAR(dob) >=3 
				AND SEX = "Male" AND t_family.id = i_family.id)) AS count35M,
			COUNT((SELECT t_family.id FROM t_family AS i_family WHERE ?-YEAR(dob) < 6 AND ?-YEAR(dob) >=3 
				AND SEX = "Female" AND t_family.id = i_family.id)) AS count35F,
			COUNT((SELECT t_family.id FROM t_family AS i_family WHERE ?-YEAR(dob) < 9 AND ?-YEAR(dob) >=6
				AND SEX = "Male" AND t_family.id = i_family.id)) AS count68M,
			COUNT((SELECT t_family.id FROM t_family AS i_family WHERE ?-YEAR(dob) < 9 AND ?-YEAR(dob) >=6 
				AND SEX = "Female" AND t_family.id = i_family.id)) AS count68F,
			COUNT((SELECT t_family.id FROM t_family AS i_family WHERE ?-YEAR(dob) < 13 AND ?-YEAR(dob) >=9
				AND SEX = "Male" AND t_family.id = i_family.id)) AS count912M,
			COUNT((SELECT t_family.id FROM t_family AS i_family WHERE ?-YEAR(dob) < 13 AND ?-YEAR(dob) >=9
				AND SEX = "Female" AND t_family.id = i_family.id)) AS count912F,
			COUNT((SELECT t_family.id FROM t_family AS i_family WHERE ?-YEAR(dob) < 15 AND ?-YEAR(dob) >=13
				AND SEX = "Male" AND t_family.id = i_family.id)) AS count1314M,
			COUNT((SELECT t_family.id FROM t_family AS i_family WHERE ?-YEAR(dob) < 15 AND ?-YEAR(dob) >=13
				AND SEX = "Female" AND t_family.id = i_family.id)) AS count1314F
			FROM t_family,t_application,t_agency,t_user_agency
			WHERE t_family.application_id = t_application.id 
			AND t_agency.id = t_application.agency_id 
			AND t_user_agency.user_id = ? 
			AND t_agency.id = t_user_agency.agency_id 
			AND t_family.type = 3
			AND dupe = "0"
			AND t_application.season = ? 
			GROUP BY t_application.agency_id,t_application.toys',array(SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,SEASON,$uid,SEASON));
	}

	function genDupeSummaryReport($uid) {
		$db = Database::getInstance();
		return $db->getAll('SELECT t_agency.name,t_agency.id,count(t_application.id) AS count
				FROM t_application,t_user_agency,t_agency 
				WHERE dupe = "1" 
				AND t_user_agency.user_id = ? 
				AND t_application.agency_id = t_user_agency.agency_id 
				AND t_agency.id = t_application.agency_id
				AND t_application.season = ? 
				GROUP BY t_agency.name',array($uid,SEASON));
	}

	function genDupeReport($id) {
                $db = Database::getInstance();
/*(?-YEAR(t_fam.dob))-("12-31"<RIGHT(t_fam.dob,5))*/
		return $db->getAll('SELECT t_fam.id AS individual_id,t_fam.application_id AS family_id,t_fam.type,t_fam.firstName,t_fam.lastName,t_fam.dob,
                        (?-YEAR(t_fam.dob)) AS age,t_fam.sex,t_ag.name AS agency,t_user.username,
                        t_app.tstamp,t_app.tstampUpd,t_app.season,t_app.phone,t_app.street,t_app.city,t_app.state,t_app.zip,t_app.familySize,
                        t_app.employer,t_app.income,t_app.expenses,t_app.comments,t_app.ss,t_app.ssi,t_app.va,t_app.tanf,t_app.fstamps,t_app.other,
                        t_app.toys,t_app.food,
				(SELECT i_ag.name FROM t_family AS i_fam,t_application AS i_app,t_agency AS i_ag
                                    WHERE i_fam.lookup_id = t_fam.lookup_id AND i_fam.application_id < t_fam.application_id
                                    AND i_app.agency_id = i_ag.id AND i_app.id = i_fam.application_id
                                    ORDER BY i_fam.application_id limit 1) AS dupedWith
                                FROM t_family AS t_fam,t_application AS t_app,t_agency AS t_ag,t_user
                                WHERE t_fam.application_id = t_app.id
                                AND t_app.agency_id = t_ag.id
                                AND t_ag.id = ?
                                AND t_user.id = t_app.user_id
                                AND t_app.season = ?
                                AND t_app.dupe = "1"
                                ORDER BY t_fam.application_id',array(SEASON,$id,SEASON));
	}

	function genClearedSummaryReport($uid) {
		$db = Database::getInstance();
		return $db->getAll('SELECT t_agency.name,t_agency.id,count(t_application.id) AS count,sum(familySize) AS numInd
				FROM t_application,t_user_agency,t_agency
				WHERE dupe = "0"
				AND t_user_agency.user_id = ?
				AND t_application.agency_id = t_user_agency.agency_id
				AND t_agency.id = t_application.agency_id
				AND t_application.season = ?
				GROUP BY t_agency.name',array($uid,SEASON));
	}	
	
	function genClearedReport($id) {
		$db = Database::getInstance();
		return $db->getAll('SELECT t_fam.id AS individual_id,t_fam.application_id AS family_id,t_fam.type,t_fam.firstName,t_fam.lastName,t_fam.dob,
			(?-YEAR(t_fam.dob)) AS age,t_fam.sex,t_ag.name AS agency,t_user.username,
			t_app.tstamp,t_app.tstampUpd,t_app.season,t_app.phone,t_app.street,t_app.city,t_app.state,t_app.zip,t_app.familySize,
			t_app.employer,t_app.income,t_app.expenses,t_app.comments,t_app.ss,t_app.ssi,t_app.va,t_app.tanf,t_app.fstamps,t_app.other,
			t_app.toys,t_app.food
				FROM t_family AS t_fam,t_application AS t_app,t_agency AS t_ag,t_user
				WHERE t_fam.application_id = t_app.id
				AND t_app.agency_id = t_ag.id
				AND t_ag.id = ? 
				AND t_user.id = t_app.user_id
				AND t_app.season = ? 
				AND t_app.dupe = "0"
				ORDER BY t_fam.application_id',array(SEASON,$id,SEASON));
	}

        function genDupeSummaryReportAdmin() {
                $db = Database::getInstance();
                return $db->getAll('SELECT t_agency.name,t_agency.id,count(t_application.id) AS count
                                FROM t_application,t_agency
                                WHERE dupe = "1"
                                AND t_agency.id = t_application.agency_id
                                AND t_application.season = ?
                                GROUP BY t_agency.name',array(SEASON));
        }

        function genDupeReportAdmin() {
                $db = Database::getInstance();
                return $db->getAll('SELECT t_fam.id AS individual_id,t_fam.application_id AS family_id,t_fam.type,t_fam.firstName,t_fam.lastName,t_fam.dob,
                        (?-YEAR(t_fam.dob)) AS age,t_fam.sex,t_ag.name AS agency,t_user.username,
                        t_app.tstamp,t_app.tstampUpd,t_app.season,t_app.phone,t_app.street,t_app.city,t_app.state,t_app.zip,t_app.familySize,
                        t_app.employer,t_app.income,t_app.expenses,t_app.comments,t_app.ss,t_app.ssi,t_app.va,t_app.tanf,t_app.fstamps,t_app.other,
                        t_app.toys,t_app.food,
                                (SELECT i_ag.name FROM t_family AS i_fam,t_application AS i_app,t_agency AS i_ag
                                    WHERE i_fam.lookup_id = t_fam.lookup_id AND i_fam.application_id < t_fam.application_id
                                    AND i_app.agency_id = i_ag.id AND i_app.id = i_fam.application_id
                                    ORDER BY i_fam.application_id limit 1) AS dupedWith
                                FROM t_family AS t_fam,t_application AS t_app,t_user,t_agency AS t_ag
                                WHERE t_fam.application_id = t_app.id
                                AND t_user.id = t_app.user_id
                                AND t_app.season = ?
                                AND t_app.dupe = "1"
				AND t_ag.id = t_app.agency_id
                                ORDER BY t_fam.application_id',array(SEASON,SEASON));
        }

        function genClearedSummaryReportAdmin() {
                $db = Database::getInstance();
                return $db->getAll('SELECT t_agency.name,t_agency.id,count(t_application.id) AS count,sum(familySize) AS numInd
                                FROM t_application,t_agency
                                WHERE dupe = "0"
                                AND t_agency.id = t_application.agency_id
                                AND t_application.season = ?
                                GROUP BY t_agency.name',array(SEASON));
        }

        function genClearedReportAdmin() {
                $db = Database::getInstance();
                return $db->getAll('SELECT t_fam.id AS individual_id,t_fam.application_id AS family_id,t_fam.type,t_fam.firstName,t_fam.lastName,t_fam.dob,
                        (?-YEAR(t_fam.dob)) AS age,t_fam.sex,t_ag.name AS agency,t_user.username,
                        t_app.tstamp,t_app.tstampUpd,t_app.season,t_app.phone,t_app.street,t_app.city,t_app.state,t_app.zip,t_app.familySize,
                        t_app.employer,t_app.income,t_app.expenses,t_app.comments,t_app.ss,t_app.ssi,t_app.va,t_app.tanf,t_app.fstamps,t_app.other,
                        t_app.toys,t_app.food
                                FROM t_family AS t_fam,t_application AS t_app,t_user,t_agency as t_ag
                                WHERE t_fam.application_id = t_app.id
                                AND t_user.id = t_app.user_id
                                AND t_app.season = ?
                                AND t_app.dupe = "0"
				AND t_ag.id = t_app.agency_id
                                ORDER BY t_fam.application_id',array(SEASON,SEASON));
        }

	function genFamilySizeReport($uid) {
		$db = Database::getInstance();
		return $db->getAll('SELECT t_agency.name,
				COUNT((SELECT i_application.id FROM t_application AS i_application 
					WHERE familySize < 4 AND i_application.id = t_application.id)) AS count13,
				COUNT((SELECT i_application.id FROM t_application AS i_application 
					WHERE familySize >= 4 AND familySize < 7 AND i_application.id = t_application.id)) AS count46,
				COUNT((SELECT i_application.id FROM t_application AS i_application 
					WHERE familySize >= 7 AND i_application.id = t_application.id)) AS count7
				FROM t_application,t_agency,t_user_agency 
				WHERE t_application.season = ? 
				AND t_application.agency_id = t_agency.id
				AND t_user_agency.user_id = ? 
				AND t_user_agency.agency_id = t_agency.id
				AND t_application.dupe = "0"
				GROUP BY t_agency.id ORDER BY t_agency.name',array(SEASON,$uid));
	}

	function genFoodReport($uid) {
		$db = Database::getInstance();
		return $db->getAll('SELECT t_ag.name,
				COUNT((SELECT i_app.id FROM t_application AS i_app
					WHERE food = "1" AND i_app.id = t_app.id)) AS food1,
				SUM((SELECT count(i_fam.id) FROM t_application AS i_app,t_family AS i_fam 
					WHERE food = "1" AND i_app.id = t_app.id AND i_app.id = i_fam.application_id)) AS food1c,
				COUNT((SELECT i_app.id FROM t_application AS i_app 
					WHERE food = "2" AND i_app.id = t_app.id)) AS food2,
				SUM((SELECT count(i_fam.id) FROM t_application AS i_app,t_family AS i_fam 
					WHERE food = "2" AND i_app.id = t_app.id AND i_app.id = i_fam.application_id)) AS food2c
				FROM t_application AS t_app,t_agency AS t_ag,t_user_agency AS t_u_a
				WHERE t_app.season = ? 
				AND t_app.agency_id = t_ag.id
				AND t_u_a.user_id = ? 
				AND t_u_a.agency_id = t_ag.id
				AND t_app.dupe = "0"
				GROUP BY t_ag.id',array(SEASON,$uid));
	}

	function genToysReport($uid) {
		$db = Database::getInstance();
		return $db->getAll('SELECT t_ag.name,
				COUNT(DISTINCT(SELECT i_app.id FROM t_application AS i_app
					WHERE toys = "1" AND i_app.id = t_app.id)) AS toys1,
				SUM((SELECT COUNT(i_fam.id) FROM t_application AS i_app,t_family AS i_fam
					WHERE toys = "1" AND i_app.id = t_app.id AND i_app.id = i_fam.application_id AND i_fam.id = t_fam.id)) AS toys1c,
				COUNT(DISTINCT(SELECT i_app.id FROM t_application AS i_app
					WHERE toys = "2" AND i_app.id = t_app.id)) AS toys2,
				SUM((SELECT COUNT(i_fam.id) FROM t_application AS i_app,t_family AS i_fam
					WHERE toys = "2" AND i_app.id = t_app.id AND i_app.id = i_fam.application_id AND i_fam.id = t_fam.id)) AS toys2c
                                FROM t_application AS t_app,t_agency AS t_ag,t_user_agency AS t_u_a, t_family AS t_fam
                                WHERE t_app.season = ? 
                                AND t_app.agency_id = t_ag.id
                                AND t_u_a.user_id = ? 
                                AND t_u_a.agency_id = t_ag.id
                                AND t_app.dupe = "0"
				AND t_fam.application_id = t_app.id
				AND t_fam.type = "3"
				AND ?-YEAR(t_fam.dob) < 15
				GROUP BY t_app.agency_id',array(SEASON,$uid,SEASON));
	}

	function genExport($uid) {
                $db = Database::getInstance();
                return $db->getAll('SELECT t_fam.id AS individual_id,t_fam.application_id AS family_id,t_fam.type,t_fam.firstName,t_fam.lastName,t_fam.dob,
                        (?-YEAR(t_fam.dob)) AS age,t_fam.sex,t_app.agency_id,t_user.username,
                        t_app.tstamp,t_app.tstampUpd,t_app.season,t_app.phone,t_app.street,t_app.city,t_app.state,t_app.zip,t_app.familySize,
                        t_app.employer,t_app.income,t_app.expenses,t_app.comments,t_app.ss,t_app.ssi,t_app.va,t_app.tanf,t_app.fstamps,t_app.other,
                        t_app.toys,t_app.food,t_lk.dataExp
                                FROM t_family AS t_fam,t_application AS t_app,t_user,t_user_agency AS t_ua,t_lookup AS t_lk
                                WHERE t_fam.application_id = t_app.id
                                AND t_user.id = t_app.user_id
                                AND t_app.season = ?
                                AND t_app.dupe = "0"
				AND t_ua.user_id = ?
				AND t_ua.agency_id = t_app.agency_id
				AND t_lk.id = t_fam.lookup_id
                                ORDER BY t_fam.application_id',array(SEASON,SEASON,$uid));
        }

}
