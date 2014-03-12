<?php
include('header.php');
include('auth.php');
?>

<h2>Profile</h2>
<?php
$tutorid=intval($_GET["tutorid"]);
if(isset($tutorid) && $tutorid!="") {

}
$con = mysql_connect($server,$user,$pass);
if (!$con) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db($db, $con);
$tutor=mysql_query("SELECT * FROM `Tutor`,`Department`,`User`  WHERE User.id='$tutorid' AND Tutor.id='$tutorid' AND Tutor.EducationCode = Department.Code LIMIT 0,1");
if (!tutor) {
	die('Query error: ' . mysql_error());
}
?>
<TABLE border="1">
	
<?php
$tutorinfo = mysql_fetch_array($tutor);
$name=$tutorinfo['name'];
$price=$tutorinfo['price'];
$language=$tutorinfo['language'];
$education=$tutorinfo['education'];
$major=$tutorinfo['Name'];
$email=$tutorinfo['email'];

echo "<TR><TD>Name</TD><TD>".$name." (".$tutorid.")</TD></TR>";
echo "<TR><TD>Price/hour</TD><TD>$".$price."</TD></TR>";
echo "<TR><TD>Language</TD><TD>".$language."</TD></TR>";;
echo "<TR><TD>Education</TD><TD>".$education."</TD></TR>";
echo "<TR><TD>Major</TD><TD>".$major."</TD></TR>";
echo "<TR><TD>Email</TD><TD><a href='mailto:".$email."'>".$email."</a></TD></TR>";

?>
</TABLE>
<p></p>
<TABLE border="1">
	<TH>CRN</TH>
	<TH>Major</TH>
	<TH>Number</TH>
	<TH>Title</TH>
	<TH>Contract for this Course</TH>
	<?php

	$courses=mysql_query("SELECT * FROM `Course` NATURAL JOIN `offers` WHERE TutorID = '$tutorid'");
	if (!tutor) {
		die('Query error: ' . mysql_error());
	}

	while($courseinfo = mysql_fetch_array($courses))  {
		$crn = $courseinfo['CRN'];
		$major=$courseinfo['Major'];
		$number=$courseinfo['Number'];
		$title=$courseinfo['Title'];
		echo "<TR><TD>".$crn."</TD>";
		echo "<TD>".$major."</TD>";
		echo "<TD>".$number."</TD>";
		echo "<TD>".$title."</TD>";
		echo "<TD><a href='makecontract.php?tutorid=$tutorid&CRN=$crn'>Request Contract</a></TD>";
		echo "</TR>";
	}

	?>

</TABLE>
