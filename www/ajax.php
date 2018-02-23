<?php
	// Grab duplicates for AJAX
	function getDupes($dob,$season) {
		$db = Database::getInstance();
		return $db->getRow('SELECT t_application_test.id, tstamp, tstampUpd, season, phone, street, city, state, zip, t_family_test.firstName, t_family_test.lastName
				FROM t_application_test
					LEFT OUTER JOIN t_family_test ON 
						t_application_test.id = t_family_test.application_id
				WHERE t_family_test.dob = STR_TO_DATE(?,\'%m-%d-%Y\')
				AND t_application_test.season = ?',array($dob,$season));
	}
?>