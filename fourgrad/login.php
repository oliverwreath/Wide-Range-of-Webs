<?php
include("auth.php");
include("header.php");
session_start();
?>

<?php

if($_POST["email"]!="" && $_POST["password"]!="") {
	$email = $_POST["email"];
	$password = $_POST["password"];
} 
else 
{
	print "Please enter credentials.<br/>\n";
	print "<form action='' method='post'>";
	print "<table><tr>";
	print "<td>email</td>";
	print "<td><input type='text' name='email' />";
	print "</td></tr>";
	print "<tr>";
	print "<td>Password</td>";
	print "<td><input type='password' name='password' />";
	print "</td></tr></table>";
	print "<input type='submit' />";
	print "</form>";
}

$con = mysql_connect($server,$user,$pass);
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

mysql_select_db($db, $con);
$sql = "SELECT * FROM `User` WHERE email = '$email' LIMIT 0,1 ";

$result= mysql_query($sql);
if (!$result )
{
	die('Could not connect: ' . mysql_error());
}
$row = mysql_fetch_array($result);

if($row['id']!=NULL && $row['password']==$password) {
	// Place userid in session. User is considered LOGGED IN.
	$_SESSION['userid'] = $row['id'];
	echo "Login success.";
	echo '<meta http-equiv="refresh" content="0;url=http://www.skywalkerhunter.com/fourgrad/index.php" />';
} else {
	die('Access denied. Please enter valid credentials.');
}

?>