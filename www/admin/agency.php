<?php
require_once('../lib/init.php');
require_once('../lib/admin.php');

//Get all Status
$ag = Agency::getAllAgencies();

include('../includes/admin_header.html');
?>
					<div id="content-header">
						<h2>Admin - List Agencies</h2>
					</div>
						<!-- listings -->
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr class="odd">
								<th>Agency Name</th>
								<th>Number of Users</th>
								<th>Action</th>
							</tr>
<?php 
$cnt=0;
foreach ($ag as $_) { 
	$cnt++;
	$class = ($cnt % 2) ? 'even' : 'odd';
?>
							<tr class="<?php echo $class ?>">
								<td valign="top"><?php echo $_['name'] ?></td>
								<td valign="top"><?php echo $_['users'] ?> &nbsp;(<a href="user.php?id=<?php echo $_['id'] ?>">list</a>)</td>
								<td valign="top"><a href="agency_edit.php?id=<?php echo $_['id'] ?>">Edit</a>&nbsp;|&nbsp;<a href="agency_delete.php?id=<?php echo $_['id'] ?>" onclick="return confirm('Are you sure?');">Delete</a></td>
							</tr>
<?php } ?>
						</table>
						<!--/listings-->

<p><a href="agency_new.php">New Agency</a></p>
<?php
include('../includes/footer.html');
?>
