<?php
require_once('../lib/init.php');
require_once('../lib/admin.php');

include('../includes/admin_header.html');
?>
	<div id="content-header">
		<h2>Admin Menu</h2>
	</div>

	<ul>
		<li>Manage Users
			<ul>
				<li><a href="user.php">List Users</a></li>
				<li><a href="user_new.php">New User</a></li>
			</ul>
		</li><br />
		<li>Manage Agencies 
			<ul>
				<li><a href="agency.php">List Agencies</a></li>
				<li><a href="agency_new.php">New Agency</a></li>
			</ul>
		</li><br />
		<li>Manage Applications</li>
			<ul>
				<li><a href="viewApp.php">Application Lookup</a></li>
				<li><a href="search.php">Applicant Search</a></li>
			</ul>
		</li><br />
		<li>Reports</li>
			<ul>
				<li><a href="reports.php?type=1">Run Duplicate Report</a></li>
				<li><a href="reports.php?type=2">Run Cleared Report</a></li>
			</ul>
		</li><br />
		<li><a href="season.php">Manage Season</a></li><br />
	</ul>

<?
include('../includes/footer.html');
?>
