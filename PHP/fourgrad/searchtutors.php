<?php
include('auth.php');
include('header.php');
$con = mysql_connect($server,$user,$pass);
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}
mysql_select_db($db, $con);
?>

<h2>Basic Search For Tutors</h2>

<?php
$loggedin=isset($_SESSION['userid']);
if($loggedin)
{
?>
	<form action="" method="post">
	<table>
	<tr >
	<td>Cost between($):</td>
	<td><input type="text" name="minprice" /> and <input type="text" name="maxprice" /></td>
	</tr>
	<tr >
	<td>Department / Course:</td>
	<td><?php include("courseselector.php"); ?></td>
	</tr>
	
	<tr >
	<td>Tutor Education:</td>
	<td><select name="educationlevel"><option value="-1">Select Degree:</option><option value="Master">Master</option><option value="PhD">PhD</option><option value="Bachelor">Bachelor</option></select></td></td>
	</tr>
	
	<tr ><td><input type="submit" /></td></tr>
	</tr>
	
	</table>
	
	</form>
	
			<?php

	$sql="SELECT Tutor.id,email,language,education,EducationCode,price,avgRating,Department.Name,User.name From  `Tutor`,`User`,`Department` WHERE Department.Code=Tutor.EducationCode AND User.id=Tutor.id AND Tutor.id IN(SELECT DISTINCT(TutorID) FROM `Tutor`,`offers`,`Course`,`Department` WHERE Tutor.id=offers.TutorID AND offers.CRN=Course.CRN AND Course.Major=Department.Code ";
			$criteriaset = false;
			
			if(
			isset($_POST["minprice"]) &&
			isset($_POST["maxprice"]) &&
			$_POST["maxprice"]!=""&&$_POST["minprice"]!=""
			) {
				$minprice = $_POST["minprice"];
				$maxprice = $_POST["maxprice"];
				$sql .= " AND price<$maxprice AND price>=$minprice";
				$criteriaset=true;
			}
			if(
			isset($_POST['ctlDept']) && $_POST['ctlDept']!='-1') {
				$ctlDept = $_POST["ctlDept"];
				$sql .= " AND Course.Major='$ctlDept'";
				$criteriaset=true;
			}
			
			if(
			isset($_POST['ctlCourse']) && $_POST['ctlCourse']!='-1') {
				$ctlCourse = $_POST["ctlCourse"];
				$sql .= " AND offers.CRN='$ctlCourse'";
				$criteriaset=true;
			}
			
			if(
			isset($_POST['educationlevel']) && $_POST['educationlevel']!='-1') {
				$educationlevel = $_POST["educationlevel"];
				$sql .= " AND Tutor.education='$educationlevel'";
				$criteriaset=true;
			}
			
			if(!$criteriaset) {
				$sql .= " AND FALSE";
			}
	
			$sql .=") ORDER BY Tutor.id";
	
	

			$result = mysql_query($sql);
			if (!$result )
			{
				die('Could not connect: ' . mysql_error());
			}
			?>
	
	<TABLE border="1">
		<TH>#</TH>
		<TH>NAME</TH>
		<TH>EMAIL</TH>
		<TH>LANGUAGE</TH>
		<TH>LEVEL</TH>
		<TH>MAJOR</TH>
		<TH>PRICE</TH>
		<TH>AVG RATING</TH>
		<TH>CHOOSE</TH>
	
		<?php
		while($row = mysql_fetch_array($result))
		{
	
			$username = $row['name'];
			$email = $row['email'];
	
			echo "<TR>";
			echo "\t<TD>".$row['id'] . "</TD>\n";
			echo "\t<TD>".$row['name'] . "</TD>\n";
			echo "\t<TD>".$row['email'] . "</TD>\n";
			echo "\t<TD>".$row['language'] . "</TD>\n";
			echo "\t<TD>".$row['education'] . "</TD>\n";
			echo "\t<TD>".$row['Name'] . "</TD>\n";
			echo "\t<TD>".$row['price'] . "</TD>\n";
			echo "\t<TD>".$row['avgRating'] . "</TD>\n";
			echo "\t<TD><a href='profile.php?tutorid=".$row['id'] . "'>Select Tutor</a></TD>\n";
			echo "</TR>\n";
		}
		echo "</TABLE>";

		?>
	</TABLE>
<?php
}
else
{
    print "You must be logged in to perform this operation";
}
		mysql_close($con);
?>
