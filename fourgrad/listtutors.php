<?php
include('header.php');
include('auth.php');
?>

<h2>List Tutors</h2>
<?php
$con = mysql_connect($server,$user,$pass);
if (!$con) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db($db, $con);
$tutors=mysql_query("SELECT * FROM `Department`,`offers`,`Tutor`,`Course`,`User` WHERE User.id=Tutor.id AND Course.CRN = offers.CRN AND offers.TutorID = Tutor.id AND EducationCode = Code ORDER BY User.id");
if (!tutors ) {
	die('Query error: ' . mysql_error());
}
?>
<TABLE border="1">
	<TH>#</TH>
	<TH>NAME</TH>
	<TH>EMAIL</TH>
	<TH>LANGUAGE</TH>
	<TH>LEVEL</TH>
	<TH>MAJOR</TH>
	<TH>COURSE TITLE</TH>
	<TH>COURSE MAJOR</TH>
	<TH>COURSE NUM</TH>
	<TH>PRICE</TH>
	<TH>AVG RATING</TH>

	<?php
	while($row = mysql_fetch_array($tutors))
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
		echo "\t<TD>".$row['Title'] . "</TD>\n";
		echo "\t<TD>".$row['Major'] . "</TD>\n";
		echo "\t<TD>".$row['Number'] . "</TD>\n";
		echo "\t<TD>".$row['price'] . "</TD>\n";
		echo "\t<TD>".$row['avgRating'] . "</TD>\n";

		echo "</TR>\n";

	}
	echo "</TABLE>";
	mysql_close($con);
	?>
</TABLE>
