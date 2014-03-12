<?php
include("auth.php");
$con = mysql_connect($server,$user,$pass);
if (!$con) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db($db, $con);
$courseCode = $_GET['id'] ;
$majors=mysql_query("SELECT * FROM `Course` WHERE Major='$courseCode' ORDER BY `Course`.`Number` ASC");
if (!majors ) {
	die('Query error: ' . mysql_error());
}
echo '[';
echo '{"optionValue": -1, "optionDisplay": "Select Course:"},';
while($row = mysql_fetch_array($majors))
{
	$number=$row['Number'];
	$title = $row['Title'];
	$crn = $row['CRN'];
	echo '{"optionValue": '.$crn.', "optionDisplay": "'.$number.' - '. $title.'"},';
}
echo '{"optionValue": 0, "optionDisplay": ""}]';
?>



