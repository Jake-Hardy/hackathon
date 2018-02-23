<?
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
<? 
$cnt=0;
foreach ($ag as $_) { 
	$cnt++;
	$class = ($cnt % 2) ? 'even' : 'odd';
?>
							<tr class="<? echo $class ?>">
								<td valign="top"><? echo $_->name ?></td>
								<td valign="top"><? echo $_->users ?> &nbsp;(<a href="user.php?id=<? echo $_->id ?>">list</a>)</td>
								<td valign="top"><a href="agency_edit.php?id=<? echo $_->id ?>">Edit</a>&nbsp;|&nbsp;<a href="agency_delete.php?id=<? echo $_->id ?>" onclick="return confirm('Are you sure?');">Delete</a></td>
							</tr>
<? } ?>
						</table>
						<!--/listings-->

<p><a href="agency_new.php">New Agency</a></p>
<?
include('../includes/footer.html');
?>
