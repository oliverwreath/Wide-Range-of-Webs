<?php
include('auth.php');
session_start();
?>
<img src="logo.png" alt="">
<br>
<?php
$loggedin=isset($_SESSION['userid']);
if($loggedin)
{
	print "Logged in. UserID: ";
	print $_SESSION['userid']. "";
	print " <a href=logout.php>Logout</a>";
}
else
{
	print "Not logged in. <a href=login.php>Login</a> or <a href=createuser.php>Register</a> ";
}
echo "<br>";
?>
<a href="/">Home</a>
|
<a href="listtutors.php">List Tutors</a>
|
<a href="createuser.php">Create User</a>
|
<a href="allcourses.php">All Courses</a>
|
<a href="addmajor.php">Add Major</a>
|
<a href="modifyTutor.php">Modify Tutor Information</a>
<hr>
