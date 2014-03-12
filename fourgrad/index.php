<?php
include('header.php');
include('common.php');
include('auth.php');
?>
<h2>Home</h2>

<?php
if(loggedin_user()==GUEST) {
?>

You are a <b>Guest</b><p>
Not logged in. <a href=login.php>Login</a> or <a href=createuser.php>Register</a>

<?php
} else if (loggedin_user()==STUDENT) {
?>

You are a <b>Student</b><p>
<h4>Menu</h4>
<TABLE border="1">
<TR><TD><a href="contracts.php">My Contracts</a></TD></TR>
<TR><TD>Search for Tutors
<a href="searchtutors.php"><sup>Basic</sup></a>
<a href="searchtutorsADV.php"><sup>Advanced</sup></a>
<a href="related_search.php"><sup>Related</sup></a></TD></TR>
<TR><TD><a href="listtutors.php">List Tutors</a></TD></TR>
<TR><TD><a href="studentView.php">Modify Student Properties</a></TD></TR>
<TR><TD>&nbsp;</TD></TR>
<TR><TD><a href="deleteaccount.php">Delete Account</a><br/></TD></TR>
</TABLE>
<h4>My Information</h4>
<TABLE border="1">
	<TH>Email</TH>
	<TH>Name</TH>
	<TH>Major</TH>
<?php
		// starting a connection with the database
		$connection = mysql_connect($server,$user,$pass);
		if (!$connection) {
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($db, $connection);
		$user_id = $_SESSION['userid'];
		$query = "SELECT * FROM `User`,`Student`,`Department` WHERE Student.id=User.id AND User.id = '$user_id' AND Department.Code=Major";
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			echo "<TR><TD>".$row["email"]."</TD>";
			echo "<TD>".$row["name"]."</TD>";
			echo "<TD>".$row["Name"]."</TD></TR>";
		}
?>
</TABLE>

<?php

} else {
?>

You are a <b>Tutor</b><p>
<h4>Dashboard</h4>

<TABLE border="1">
<TR><TD><a href="contracts.php">My Contracts</a></TD></TR>
<TR><TD><a href="tutorView.php">Modify Tutor Properties</a></TD></TR>
<TR><TD><a href="modifyTutorCourses.php">Add/Remove Tutor Courses</a></TD></TR>
<TR><TD>&nbsp;</TD></TR>
<TR><TD><a href="deleteaccount.php">Delete Account</a><br/></TD></TR>
</TABLE>

<h4>My Information</h4>
<TABLE border="1">
	<TH>Language</TH>
	<TH>Major</TH>
	<TH>Level</TH>
	<TH>My Price</TH>
	<TH>Email</TH>
	<TH>Name</TH>
	<TH>My Rating</TH>
<?php
		// starting a connection with the database
		$connection = mysql_connect($server,$user,$pass);
		if (!$connection) {
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($db, $connection);
		$user_id = $_SESSION['userid'];
		$query = "SELECT * FROM `User`,`Tutor`,`Department` WHERE Tutor.id=User.id AND Tutor.id = '$user_id' AND Department.Code=EducationCode";
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			echo "<TR><TD>".$row["language"]."</TD>";
			echo "<TD>".$row["education"]."</TD>";
			echo "<TD>".$row["Name"]."</TD>";
			echo "<TD>".$row["price"]."</TD>";
			echo "<TD>".$row["email"]."</TD>";
			echo "<TD>".$row["name"]."</TD>";
			echo "<TD>".$row["avgRating"]."</TD></TR>";
		}
?>
</TABLE>
<h4>Courses Offered</h4>
<TABLE border="1">
	<TH>CRN</TH>
	<TH>Major</TH>
	<TH>Number</TH>
	<TH>Title</TH>
<?php
		$query = "SELECT * FROM `offers`,`Course` WHERE TutorID = '$user_id' AND offers.CRN=Course.CRN";
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			echo "<TR><TD>".$row["CRN"]."</TD>";
			echo "<TD>".$row["Major"]."</TD>";
			echo "<TD>".$row["Number"]."</TD>";
			echo "<TD>".$row["Title"]."</TD></TR>";
		}
?>
</TABLE>
<?php
}
?>