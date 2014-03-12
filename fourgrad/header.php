<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">


<html>

  <head>
	<style type="text/css">
	a, body
	{
		font-family:Arial;
		font-size:13px;
	}
	select, input, button
	{
		font-family:Arial;
		background-color:#FFFFFF;
		font-size:13px;
		border: 2px solid #33CCFF;
		padding: 5px;
	}
	table
	{
		font-family:Arial;
		border: 1px dashed #33CCFF;
		font-size:13px;
		padding:5px 5px 5px 5px;
		text-align:left;
		padding-top:5px;
		padding-bottom:5px;
		background-color:#F1FCFF;
		color:#000000;
	}
	tr:hover, tr.highlight
	{
		color:#000;
		background-color:#FFFFFF;
	}
	</style>
    <title>Illini Tutor Search</title>

  </head>
  <body>
	<?php include_once(".././analytic/analyticstracking.php") ?>
	
    	<img src="logo.png" alt="ITS"><br>

	
	<?php
	$loggedin=isset($_SESSION['userid']);
	if($loggedin)
	{
		print "Welcome Back! UserID: ";
		print $_SESSION['userid']. "";
		print " <a href=logout.php>Logout</a>";
	}
	else
	{
		print "Not logged in. <a href=login.php>Login</a> or <a href=createuser.php>Register</a> ";
	}
	$userid=$_SESSION['userid'];
	echo "<br>";
	?>
	<a href="http://yanliangh.org">Home of Yanliang</a>
	|
	<a href="index.php">Home of Tutor Search</a>
	|
	<a href="listtutors.php">List Tutors</a>
	|
	<a href="createuser.php">Create User</a>
	|
	<a href="allcourses.php">All Courses</a>
	|
	<a href="tutorView.php">Tutor View</a>
	|
	<a href="studentView.php">Student View</a>
	|
	<a href="modifyTutorCourses.php">Modify Tutor Courses</a>
	<!--   |
	<a href="findMyTutor.php">Find My Tutor</a> -->
	|
	<a href="contracts.php">Contracts</a>
	|
	Search for Tutors
	<a href="searchtutors.php"><sup>Basic</sup></a>
	<a href="searchtutorsADV.php"><sup>Advanced</sup></a>
	<a href="related_search.php"><sup>Related</sup></a>
	|
	<a href="myTutor.php">Auto Tutor Matcher</a>
	
	<hr>

	</body>
</html>