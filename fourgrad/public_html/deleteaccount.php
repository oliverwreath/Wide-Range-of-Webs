<?php
include('auth.php');
include('common.php');
include('header.php');

if(isset($_SESSION['userid']) ) {
	if($_POST['confirm']=="yes") {
		//Delete from User and Student/Tutor tables
		
		$connection = mysql_connect($server,$user,$pass);
		if (!$connection) {
			die('Could not connect: ' . mysql_error());
		}

		$current_userid = $_SESSION['userid'];
		
		mysql_select_db($db, $connection);
		$delete_user=mysql_query("DELETE FROM User WHERE id='$current_userid'");
		if (!$delete_user) {
			die('Could not delete account: ' . mysql_error());
		}

		$delete_tutor=mysql_query("DELETE FROM Tutor WHERE id='$current_userid'");
		if (!$delete_tutor) {
			die('Could not delete account: ' . mysql_error());
		}
		
		$delete_student=mysql_query("DELETE FROM Student WHERE id='$current_userid'");
		if (!$delete_student) {
			die('Could not delete account: ' . mysql_error());
		}
		
		print "Account deleted.";
		logout();
	} else { // Prompt
		print "Do you want to delete your account?";
		print "<form action='' method='post'>";
		print "<select name='confirm'>";
		print "<option value='yes'>Yes</option>";
		print "<option value='no'>No</option>";
		print "<input type='submit'>";
		print "</form>";
	}
}
else { // Must be logged in to delete own account.
	die("Not logged in.".mysql_error());
}
?>