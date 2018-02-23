<?php
require_once('../lib/init.php');
require_once('../lib/admin.php');

// Check input
if (isset($_REQUEST['id']) && preg_match("/^\d+$/",$_REQUEST['id'])) {
	// Get users in Agency
	$user = User::getUsersByAgency($_REQUEST['id']);
}
else {
	//Get all Users
	$user = User::getAllUsers();
}

include('../includes/admin_header.html');
?>
<style>
	#inactive-button{
		text-align:right;
		padding:8px;
	}
	th{
		padding-right:6px;
	}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script>
$(document).ready(function () {

	$(".inactive").hide();	

	jQuery('#toggle-inactive').click(function () {
		$(".inactive").toggle();
	});
});
</script>

					<div id="content-header">
						<h2>Admin - List Users</h2>
					</div>
						<div id="inactive-button">
							<button id="toggle-inactive">Hide/Show Inactive</button>
						</div>
						<!-- listings -->
						<table width="100%" cellpadding="0" cellspacing="0" id="users">
							<tr class="odd">
								<th>Username</th>
								<th>Agency</th>
								<th>Status</th>
								<th>Access</th>
								<th>Name</th>
								<th>Phone</th>
								<th>Email</th>
								<th>Action</th>
							</tr>
<?php 
$cnt=0;
foreach ($user as $_) { 
	$cnt++;
	$class= '';
	// for inactive users
	if ($_['status'] == 2) {
		$class .= ' inactive';
	}
?>
							<tr class="<?php echo $class ?>"> 
								<td valign="top"><?php echo $_['username'] ?></td>
								<td valign="top"><?php echo $_['name'] ?></td>
								<td valign="top"><?php echo $_['status'] ?></td>
								<td valign="top"><?php echo $_['accessLevel'] ?></td>
								<td valign="top"><?php echo $_['firstName'],' ',$_['lastName'] ?></td>
								<td valign="top"><?php echo $_['phone'] ?></td>
								<td valign="top"><?php echo $_['email'] ?></td>
								<td valign="top"><a href="user_edit.php?id=<?php echo $_['id'] ?>">Edit</a>&nbsp;|&nbsp;<a href="user_delete.php?id=<?php echo $_['id'] ?>" onclick="return confirm('Are you sure?');">Delete</a></td>
							</tr>
<?php } ?>
						</table>
						<!--/listings-->

<p><a href="user_new.php">Add User</a></p>
<?
include('../includes/footer.html');
?>
