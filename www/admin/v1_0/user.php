<?
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
					<div id="content-header">
						<h2>Admin - List Users</h2>
					</div>

						<!-- listings -->
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr class="odd">
								<th>Username</th>
								<th>Agency</th>
								<th>Status</th>
								<th>Access</th>
								<th>Name</th>
								<th>Phone</th>
								<th>Action</th>
							</tr>
<? 
$cnt=0;
foreach ($user as $_) { 
	$cnt++;
	$class = ($cnt % 2) ? 'even' : 'odd';
	// for inactive users
	if ($_->status == 2) {
		$class .= ' inactive';
	}
?>
							<tr class="<? echo $class ?>"> 
								<td valign="top"><? echo $_->username ?></td>
								<td valign="top"><? echo $_->name ?></td>
								<td valign="top"><? echo $arrSt[$_->status] ?></td>
								<td valign="top"><? echo $arrAL[$_->accessLevel] ?></td>
								<td valign="top"><? echo $_->firstName,' ',$_->lastName ?></td>
								<td valign="top"><? echo $_->phone ?></td>
								<td valign="top"><a href="user_edit.php?id=<? echo $_->id ?>">Edit</a>&nbsp;|&nbsp;<a href="user_delete.php?id=<? echo $_->id ?>" onclick="return confirm('Are you sure?');">Delete</a></td>
							</tr>
<? } ?>
						</table>
						<!--/listings-->

<p><a href="user_new.php">Add User</a></p>
<?
include('../includes/footer.html');
?>
