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
$tutors=mysql_query("SELECT * FROM `Tutor` LIMIT 0, 100");
if (!tutors ) {
	die('Query error: ' . mysql_error());
}
?>
<TABLE border="1">
	<TH>#</TH>
	<TH>NAME</TH>
	<TH>EMAIL</TH>
	<TH>EDUCATION</TH>
	<TH>LANGUAGE</TH>
	<TH>PRICE</TH>
	<TH>AVG RATING</TH>
	
	//Situation for INNERJOIN?
	<?php
	while($row = mysql_fetch_array($tutors))
	{
		$id=$row['id'];
		$sql = "SELECT * FROM `User` WHERE id='$id' LIMIT 0, 1000";
		
		//Fetch info from User table  
		$users = mysql_query($sql);
		if (!users) {
			die('Query error: ' . mysql_error());
		}

		$userrow = mysql_fetch_array($users);
		$username = $userrow['name'];
		$email = $userrow['email'];

		echo "<TR>";
		echo "\t<TD>".$row['id'] . "</TD>\n";
		echo "\t<TD>".$username . "</TD>\n";
		echo "\t<TD>".$email . "</TD>\n";
		echo "\t<TD>".$row['education'] . "</TD>\n";
		echo "\t<TD>".$row['language'] . "</TD>\n";
		echo "\t<TD>".$row['price'] . "</TD>\n";
		echo "\t<TD>".$row['avgRating'] . "</TD>\n";
		echo "</TR>\n";

	}
	echo "</TABLE>";
	mysql_close($con);
	?>
</TABLE>
