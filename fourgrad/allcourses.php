<?php
include('header.php');
include('auth.php');
?>

<h2>List Courses</h2>
<?php
$con = mysql_connect($server,$user,$pass);
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("fourgrad_db", $con);
$sql = "SELECT * FROM `Course` ORDER BY Major";

$result = mysql_query($sql);
?>
<TABLE border=1>
	<TH>CRN</TH>
	<TH>Major</TH>
	<TH>Number</TH>
	<TH>Title</TH>

	<?php
	while($row = mysql_fetch_array($result))
	{
		echo "<TR>";
		echo "\t<TD>".$row['CRN'] . "</TD>\n";
		echo "\t<TD>".$row['Major'] . "</TD>\n";
		echo "\t<TD>".$row['Number'] . "</TD>\n";
		echo "\t<TD>".$row['Title'] . "</TD>\n";
		echo "</TR>\n";

	}
	echo "</TABLE>";
	mysql_close($con);
	?>