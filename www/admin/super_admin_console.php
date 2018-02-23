<?php
require_once('../lib/init.php');
include('../includes/header.html'); 
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="../site.css">
<script>
    $( function() {
         $( "#datepicker" ).datepicker();
    } );
</script>
<!--additionally, i think this should have a link to it in the admin menu-->
<div id="content-header">
	<h2>Welcome, <?php echo $_SESSION['fn'] ?></h2>
</div>
<?php if ($_SESSION['al'] == 2) { ?>

<!--some kind of date box -->
<!--calender that you can click on to set deadline. where is the deadline saved or is it a business rule-->
<?} ?>
<div class="superAdmin">
    <div class="application">
        <form name="applicationForm">
            <p><strong>Set deadline for receiving applications</strong></p>
            <p>Date: <input type="text" id="datepicker"></p>
            <input type="submit" value="Submit">
            <!--calender that you can click on to set deadline. where is the deadline saved or is it a business rule-->
            
        </form>
</div>
    <div class="changeEmail">
    <div class="vertical_line"></div>
        <form name="changeEmailForm"
            <p><strong>Change default emails:</strong></p>
            <!--THIS WILL NEED TO BE CHANGED TO SUPPORT DYNAMIC GROWTH-->
            <p>Please select an email template</p>
            <select>
            <option value="new">New user created</option>
            </select>
            <textarea name="emailContent" rows="10" cols="50"></textarea>
            <br>
            <input type="submit" value="Submit">
        </form>
    </div>   
</div>
<div class="adminOtherOptions">
<p>Other options:</p>
<!--maybe make this a list at some point?-->
<p><a href="../user_edit.php">Change user roles</a></p>
<!--I think this should go to the user edit page and be a new field-->
</div>
<?php include('../includes/footer.html'); ?>